<?php

namespace Modules\DetailModule\Livewire\Components;

use App\Models\FlashsaleProduct;
use App\Models\ProductCombo;
use Carbon\Carbon;
use Illuminate\Container\Attributes\Log;
use Illuminate\Support\Facades\Log as FacadesLog;
use Livewire\Component;
use Livewire\Attributes\On;

class Flashsale extends Component
{
    public $skuId;
    public $data;
    public $flashSaleProduct;
    public $type;

    public function mount($skuId)
    {
        if($this->type === 'combo') {
            return;
        }
        $this->skuId = $skuId;

        if ($this->checkFlashSale()) {
            $sku = $this->flashSaleProduct->sku;
            $flashsale = $this->flashSaleProduct->flashsale;

            $this->data = [
                'startTime' => Carbon::parse($flashsale->started_at)->toIso8601String(),
                'endTime' => Carbon::parse($flashsale->expired_at)->toIso8601String(),
                'sold' => $sku->sold ?? 0,
                'quantity' => $sku->quantity ?? 0,
            ];
            $this->dispatch('flashsaleExisted');
        }
    }
    #[On('flashsaleExisted')]
    public function dispatchFlashsale()
    {
        $this->dispatch('flashsaleUpdated');
    }

    public function checkFlashSale()
    {
        $now = Carbon::now();

        $flashSaleProduct = FlashsaleProduct::with(['sku', 'flashsale'])
            ->where('sku_id', $this->skuId)
            ->whereHas('flashsale', function ($q) use ($now) {
                $q->where('started_at', '<=', $now)
                    ->where('expired_at', '>=', $now);
            })
            ->first();

        if ($flashSaleProduct) {
            $this->flashSaleProduct = $flashSaleProduct;
            return true;
        } else {
            $this->flashSaleProduct = null;
            return false;
        }
    }

    public function render()
    {
        return view('detailmodule::livewire.components.flashsale');
    }
}
