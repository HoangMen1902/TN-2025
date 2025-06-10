<?php

namespace App\Services;

use Stripe\StripeClient;

class Stripe
{
    private $private_token;
    private $public_token;
    private $stripe;
    public function __construct()
    {
        $this->stripe = new StripeClient($this->private_token);
        $this->private_token = env("STRIPE_API_SECRET");
        $this->public_token = env("STRIPE_API_PUBLIC");
    }

    public function createCheckoutSession($carts, $shipping_fee, $voucher = null)
    {
        $lineItems = $this->formartItems($carts, $shipping_fee, $voucher);
        $session = $this->stripe->checkout->sessions->create([
            'success_url' => env('APP_URL'),
            'line_items' => $lineItems,
            'mode' => 'payment',
            'cancel_url' => env('/gio-hang'),
        ]);
        return $session;
    }

    public function formartItems($items, $shipping_fee, $voucher = null)
{
    $line_items = array_map(function ($item) {
        $price = 0;
        $item_type = $item->item_type;
        if ($item_type === "sku") {
            if ($item->sku->flashsales && !$item->sku->flashsales->isExpired) {
                if ($item->sku->flashsales->discount->discount_type === "percent") {
                    $price = $item->sku->sale_price - ($item->sku->sale_price * $item->sku->flashsales->discount->discount_amount / 100);
                } elseif ($item->sku->flashsales->discount->discount_type === "specific") {
                    $price = $item->sku->sale_price - $item->sku->flashsales->discount->discount_amount;
                } else {
                    $price = $item->sku->sale_price;
                }
            } else {
                $price = $item->sku->sale_price;
            }
        }

        $price = (int) $price;

        return [
            'price_data' => [
                'currency' => 'VND',
                'product_data' => [
                    'name' => $item_type === "sku" ? $item->sku->product->name : $item->combo->combo_name,
                    'description' => $item_type === "sku" ? $item->sku->product->short_description : $item->combo->description,
                ],
                'unit_amount' => $price,
            ],
            'quantity' => $item->quantity,
        ];
    }, $items);

    if ($shipping_fee > 0) {
        $line_items[] = [
            'price_data' => [
                'currency' => 'VND',
                'product_data' => [
                    'name' => 'Phí vận chuyển',
                ],
                'unit_amount' => (int) $shipping_fee,
            ],
            'quantity' => 1,
        ];
    }

    return $line_items;
}

}
