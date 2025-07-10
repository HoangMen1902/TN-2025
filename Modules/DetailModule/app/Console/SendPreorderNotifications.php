<?php

namespace Modules\DetailModule\Console;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Preorder;
use App\Models\Notification;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProductBackInStockMail;
use Illuminate\Support\Facades\Log;

class SendPreorderNotifications extends Command
{
    protected $signature = 'preorder:notify';

    protected $description = 'Gửi thông báo cho người dùng đã đặt trước sản phẩm khi có hàng';

    public function handle()
    {
        $products = Product::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->whereHas('preorders', function ($q) {
                $q->where('status', 'pending');
            })
            ->get();

        foreach ($products as $product) {
            $this->notifyPreorderUsers($product);
        }

        $this->info('Đã gửi thông báo đặt trước cho sản phẩm có hàng.');
    }

    protected function notifyPreorderUsers(Product $product)
    {
        Log::info('🔔 Đang xử lý sản phẩm: ' . $product->name);

        $preorders = Preorder::where('product_id', $product->id)
            ->where('status', 'pending')
            ->get();

        if ($preorders->isEmpty()) {
            Log::info('❌ Không có preorder nào cho sản phẩm: ' . $product->name);
            return;
        }

        DB::transaction(function () use ($product, $preorders) {
            $notification = Notification::create([
                'name' => 'Sản phẩm "' . $product->name . '" đã có hàng!',
                'content' => 'Sản phẩm bạn đặt trước hiện đã có hàng. Hãy kiểm tra ngay!',
                'thumbnail' => $product->thumbnail,
                'notification_type' => 'Ưu đãi độc quyền',
            ]);

            foreach ($preorders as $preorder) {
                Log::info('✅ Đã gửi mail: ' . $preorder->user->email);
                Mail::to($preorder->user->email)->send(new ProductBackInStockMail($product));
                Log::info('✅ Đã gửi mail cho sản phẩm: ' . $product->name);
                UserNotification::create([
                    'user_id' => $preorder->user_id,
                    'notification_id' => $notification->id,
                    'notification_status' => 'unread',
                ]);

                $preorder->update(['status' => 'notified']);
            }
        });
    }
}
