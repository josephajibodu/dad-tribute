<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tribute;
use Illuminate\View\View;

class TributeController extends Controller
{
    public function index(): View
    {
        $tributes = Tribute::latest()->get();

        return view('admin.tributes', [
            'tributes' => $tributes,
            'candleCount' => $tributes->count(),
            'messageCount' => $tributes->filter->hasMessage()->count(),
            'pendingCount' => $tributes->filter(fn (Tribute $tribute) => $tribute->hasMessage() && ! $tribute->approved_at)->count(),
        ]);
    }
}
