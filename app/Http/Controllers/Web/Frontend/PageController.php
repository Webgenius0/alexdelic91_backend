<?php

namespace App\Http\Controllers\Web\Frontend;

use App\Models\DynamicPage;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function privacyAndPolicy() {
        $dynamicPage = DynamicPage::query()
                        ->where('status','active')
                        ->where('id', 1)
                        ->firstOrFail();
        return view('frontend.layouts.pages.singleDynamicPage', compact('dynamicPage'));
    }

    public function termsAndConditions() {
        $dynamicPage = DynamicPage::query()
                        ->where('status','active')
                        ->where('id', 2)
                        ->firstOrFail();
        return view('frontend.layouts.pages.singleDynamicPage', compact('dynamicPage'));
    }
}
