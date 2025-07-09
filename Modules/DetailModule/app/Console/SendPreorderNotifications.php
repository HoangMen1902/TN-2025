<?php
namespace Modules\DetailModule\Console;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Preorder;
use App\Models\Notification;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;

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
        $preorders = Preorder::where('product_id', $product->id)
            ->where('status', 'pending')
            ->get();

        if ($preorders->isEmpty()) return;

        DB::transaction(function () use ($product, $preorders) {
            $notification = Notification::create([
                'name' => 'Sản phẩm "' . $product->name . '" đã có hàng!',
                'content' => 'Sản phẩm bạn đặt trước hiện đã có hàng. Hãy kiểm tra ngay!',
                'thumbnail' => $product->thumbnail,
                'notification_type' => 'Ưu đãi độc quyền',
            ]);

            foreach ($preorders as $preorder) {
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
