<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Exception\OAuth\InvalidRequestException;
use Stripe\PaymentIntent;

class StripeService
{
    private $private_token;
    private $public_token;
    private $stripe;
    public function __construct()
    {
        
        $this->private_token = env("STRIPE_API_SECRET");
        $this->public_token = env("STRIPE_API_PUBLIC");
        $this->stripe = new StripeClient($this->private_token);
        Stripe::setApiKey(env('STRIPE_API_SECRET'));
    }

    public function checkCheckoutId($checkoutId)
    {
        try {
            $session = StripeSession::retrieve($checkoutId);
            return !empty($session) && $session->id === $checkoutId;
        } catch (InvalidRequestException $e) {
            Log::error($e->getMessage());
            return false;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function createCheckoutSession($carts, $shipping_fee, $payment_id, $voucher = null)
    {
        $lineItems = $this->formartItems($carts, $shipping_fee, $voucher);
        $session = $this->stripe->checkout->sessions->create([
            'success_url' => env('APP_URL') . '/international-return/{CHECKOUT_SESSION_ID}/' . $payment_id,
            'line_items' => $lineItems,
            'mode' => 'payment',
            'cancel_url' => route('cart.index'),
        ]);
        return $session;
    }


    public static function getChargeId(string $checkoutId): mixed
    {
        $session = StripeSession::retrieve($checkoutId);
        $paymentIntentId = $session->payment_intent;
        $findIntent = PaymentIntent::retrieve($paymentIntentId);
        return $findIntent->latest_charge;
    }

    public function formartItems($items, $shipping_fee, $voucher = null)
    {
        $line_items = $items->map(function ($item) {
            $price = 0;
            $item_type = $item->item_type;
            if ($item_type === "sku") {
                $flashsale = $item->sku->flashsales->first(function ($fs) {
                    return !$fs->isExpired();
                });
                if ($flashsale) {
                    if ($flashsale->discount->discount_type === "percent") {
                        $price = ($item->sku->sale_price ?? $item->sku->price) - (($item->sku->sale_price ?? $item->sku->price) * $flashsale->discount->discount_amount / 100);
                    } elseif ($flashsale->discount->discount_type === "specific") {
                        $price = ($item->sku->sale_price ?? $item->sku->price) - $flashsale->discount->discount_amount;
                    } else {
                        $price = ($item->sku->sale_price ?? $item->sku->price);
                    }
                } else {
                    $price = ($item->sku->sale_price ?? $item->sku->price);
                }
            }

            $price = (int) $price;

            return [
                'price_data' => [
                    'currency' => 'VND',
                    'product_data' => [
                        'name' => $item_type === "sku" ? $item->sku->product->name : $item->combo->combo_name,
                        'description' => $item_type === "sku" ? strip_tags($item->sku->product->short_description) : strip_tags($item->combo->description),
                    ],
                    'unit_amount' => $price,
                ],
                'quantity' => $item->quantity,
            ];
        })->toArray();

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
