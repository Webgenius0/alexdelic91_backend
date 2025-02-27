<?php

namespace App\Http\Controllers\Api\Banner;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class BannerController extends Controller
{
    use ApiResponse;

    public function __invoke()
    {
        $banner = Banner::where('status', 'active')->get();

        if ($banner->isEmpty()) {
            return $this->error([], 'No banner found', 200);
        }

        return $this->success($banner, 'Banners fetched successfully', 200);
    }
}
