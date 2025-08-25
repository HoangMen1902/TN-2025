<?php

namespace Modules\ImageSearch\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Image;


class ImageSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('imagesearch::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('imagesearch::create');
    }


    public function handle(Request $request)
    {
        $data = $request->input('imageSearch');

        if (!$data) {
            return back()->with('error', 'Vui lòng chọn ảnh hợp lệ và đảm bảo ảnh đã được tải lên hoàn toàn');
        }

        $data_json_decoded = json_decode($data, true);

        if (!isset($data_json_decoded['data']) || !isset($data_json_decoded['name'])) {
            return back()->with('error', 'Dữ liệu ảnh không hợp lệ');
        }

        $base64 = $data_json_decoded['data'];
        $extension = pathinfo($data_json_decoded['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;

        Storage::disk('public')->put("temp/$filename", base64_decode($base64));
        $inputImage = storage_path("app/public/temp/$filename");
        Log::info('Uploaded Image');
        Image::load($inputImage)->fit(Fit::Crop, 224, 224)->save($inputImage);
        Log::info('Resized Image');
        $isWin = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

        $pythonPath = $isWin
            ? base_path('.venv_clip/Scripts/python.exe')
            : base_path('.venv_clip/bin/python3');

        $scriptPath = base_path('app/Tools/ImgFinder/find.py');
        $cachePath = base_path('app/Tools/ImgFinder/features_cache.pt');

        $cmd = escapeshellcmd("$pythonPath \"$scriptPath\" match \"$inputImage\" \"$cachePath\"");
        $output = trim(shell_exec($cmd . ' 2>&1'));
        Log::info('Output: ' . $output);
        if ($output === false || $output === null) {
            return back()->with('error', 'Không tìm thấy sản phẩm nào tương tự :(');
        }

        $product = Product::where('thumbnail', 'LIKE', "%$output%")
            ->where('product_status', 'active')
            ->first();

        Log::info('Product:' . $product);

        if ($product) {
            return redirect('/chi-tiet/' . $product->slug)->with('success', 'Đã tìm thấy sản phẩm phù hợp');
        }

        $sku = ProductSku::with('product')
            ->where('images', 'LIKE', "%$output%")
            ->first();

        if ($sku && $sku->product && $sku->product->product_status === 'active') {
            return redirect('/chi-tiet/' . $sku->product->slug);
        }

        Log::info('SKU:', $sku?->toArray() ?? []);
        Log::info('Product:', $sku?->product?->toArray() ?? []);
        Log::info('Product status:', [$sku?->product?->product_status]);


        return back()->with('error', 'Không tìm thấy sản phẩm nào tương tự :((');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('imagesearch::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('imagesearch::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
