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

    public function createCheckoutSession($carts, $shipping_fee = 0, $payment_id, $voucher = 0, bool $is_ebook = false)
    {
        if (!$is_ebook) {
            $lineItems = $this->formartItems($carts, $shipping_fee);
        } else {
            // $lineItems = $this->formatEbook($carts);
        }

        $checkoutData = [
            'success_url' => env('APP_URL') . '/international-return/{CHECKOUT_SESSION_ID}/' . $payment_id,
            'line_items' => $lineItems,
            'mode' => 'payment',
            'cancel_url' => route('cart.index'),
        ];

        if ($voucher > 0) {
            $checkoutData['discounts'] = [[
                'coupon' => $this->createCoupon($voucher),
            ]];
        }

        $session = $this->stripe->checkout->sessions->create($checkoutData);
        return $session;
    }

    private function createCoupon($voucher)
    {
        $coupon = $this->stripe->coupons->create([
            'amount_off' => $voucher,
            'currency'   => 'vnd',
            'duration'   => 'once',
        ]);
        return $coupon->id;
    }
    public function createEbookCheckoutSession($ebooks, string $payment_id, string $voucher = null)
    {
        $lineItems = $this->formatEbook($ebooks, $voucher);

        if (empty($lineItems)) {
            throw new \Exception('Không có ebook nào để thanh toán');
        }
        $payload = [
            'success_url' => env('APP_URL') . '/ebook-international-return/{CHECKOUT_SESSION_ID}/' . $payment_id,
            'cancel_url'  => route('ebook.payment'),
            'mode'        => 'payment',
            'line_items'  => $lineItems,
        ];
        if ($voucher) {
            $payload['discounts'] = [['coupon' => $voucher]];
        }
        return $this->stripe->checkout->sessions->create($payload);
    }
    private function formatEbook($items, $voucher = null): array
    {
        $line_items = $items->map(function ($item) {
            if (($item->item_type ?? null) !== 'ebook' || empty($item->ebook)) {
                return null;
            }
            $ebook      = $item->ebook;
            $price      = (int) $ebook->price;
            $quantity   = (int) ($item->quantity ?? 1);
            $name       = $ebook->title ?? 'Ebook';
            $description = strip_tags($ebook->description ?? '');

            if ($price <= 0 || $quantity <= 0) {
                return null;
            }
            return [
                'price_data' => [
                    'currency'     => 'VND',
                    'product_data' => [
                        'name'        => $name,
                        'description' => $description,
                    ],
                    'unit_amount'  => $price,
                ],
                'quantity' => $quantity,
            ];
        })
            ->filter()
            ->values()
            ->toArray();
        return $line_items;
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
                    if ($flashsale->discount_type === "percent") {
                        $price = ($item->sku->sale_price ?? $item->sku->price) - (($item->sku->sale_price ?? $item->sku->price) * $flashsale->discount_amount / 100);
                    } elseif ($flashsale->discount_type === "specific") {
                        $price = ($item->sku->sale_price ?? $item->sku->price) - $flashsale->discount_amount;
                    } else {
                        $price = ($item->sku->sale_price ?? $item->sku->price);
                    }
                } else {
                    $price = ($item->sku->sale_price ?? $item->sku->price);
                }
            } elseif ($item_type === 'combo') {
                $price = $item->combo->sale_price;
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
    public function refundStripe($chargeId, $amount = null)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_API_SECRET'));
        $params = ['charge' => $chargeId];
        if ($amount) {
            $params['amount'] = $amount; // đơn vị: cent
        }
        return \Stripe\Refund::create($params);
    }
}
