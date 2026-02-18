<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Payment;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionService
{
    public function createTransaction(array $data)
    {
        return DB::transaction(function () use ($data) {
            $branch = Branch::findOrFail($data['branch_id']);

            // 1. Validate & Calculate Items
            $details = [];
            $subtotal = 0;

            foreach ($data['items'] as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);

                if (! $product) {
                    throw ValidationException::withMessages(['items' => "Product ID {$item['product_id']} not found."]);
                }

                if ($product->branch_id != $branch->id) {
                    throw ValidationException::withMessages(['items' => "Product {$product->name} does not belong to this branch."]);
                }

                if ($product->stock < $item['quantity']) {
                    throw ValidationException::withMessages(['items' => "Insufficient stock for {$product->name}. available: {$product->stock}"]);
                }

                // Determine price based on customer type (e.g. member) if applicable
                // For now use the price sent or default to sell_price
                $price = $item['price'] ?? $product->sell_price;
                $lineTotal = $price * $item['quantity'];

                $subtotal += $lineTotal;

                $details[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'line_total' => $lineTotal,
                ];
            }

            // 2. Calculate Totals
            $tax = $data['tax'] ?? 0; // Or calculate based on subtotal * 0.11
            $discount = $data['discount'] ?? 0;

            if (isset($data['promotion_id']) && $data['promotion_id']) {
                $promotion = \App\Models\Promotion::find($data['promotion_id']);
                if ($promotion && $promotion->is_active && \Illuminate\Support\Carbon::now()->between($promotion->start_date, $promotion->end_date)) {
                    // Check min purchase
                    if ($subtotal >= $promotion->min_purchase) {
                        if ($promotion->type == 'percentage') {
                            $discount = ($subtotal * $promotion->value) / 100;
                        } elseif ($promotion->type == 'nominal') {
                            $discount = $promotion->value;
                        }
                    }
                }
            }

            $total = $subtotal + $tax - $discount;

            $payAmount = $data['pay_amount'];
            $changeAmount = $payAmount - $total;

            if ($changeAmount < 0 && $data['payment_method'] !== 'qris') {
                throw ValidationException::withMessages(['pay_amount' => 'Insufficient payment.']);
            }

            // 3. Create Transaction
            $transaction = Transaction::create([
                'branch_id' => $branch->id,
                'user_id' => $data['user_id'],
                'member_id' => $data['member_id'] ?? null,
                'promotion_id' => $data['promotion_id'] ?? null,
                'invoice_number' => $this->generateInvoiceNumber($branch->code),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'pay_amount' => $payAmount,
                'change_amount' => max(0, $changeAmount),
                'status' => $data['status'] ?? 'completed',
                'notes' => $data['notes'] ?? null,
            ]);

            // 4. Create Details & Update Stock
            foreach ($details as $detail) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $detail['product']->id,
                    'quantity' => $detail['quantity'],
                    'price' => $detail['price'],
                    'purchase_price' => $detail['product']->buy_price,
                    'subtotal' => $detail['line_total'],
                ]);

                // Reduce Stock
                $detail['product']->decrement('stock', $detail['quantity']);

                // Log Movement
                StockMovement::create([
                    'product_id' => $detail['product']->id,
                    'user_id' => $data['user_id'],
                    'type' => 'out',
                    'quantity' => $detail['quantity'],
                    'reference_type' => 'transaction',
                    'reference_id' => $transaction->id,
                    'notes' => 'Sales Transaction',
                ]);
            }

            // 5. Create Payment
            Payment::create([
                'transaction_id' => $transaction->id,
                'method' => $data['payment_method'],
                'amount' => $payAmount, // Assuming single payment for now
                'reference_number' => $data['payment_reference'] ?? null,
            ]);

            return $transaction->load('details.product', 'payments');
        });
    }

    private function generateInvoiceNumber($branchCode)
    {
        $date = \Illuminate\Support\Carbon::now()->format('Ymd');
        $prefix = "INV/{$branchCode}/{$date}/";

        // Find last invoice today
        $lastInvoice = Transaction::where('invoice_number', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice->invoice_number, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix.str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
