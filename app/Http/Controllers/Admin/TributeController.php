<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tribute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TributeController extends Controller
{
    public function index(): View
    {
        return view('admin.tributes', [
            'tributes' => Tribute::latest()->get(),
            'counts' => $this->counts(),
        ]);
    }

    /**
     * Show a visitor's message on the tribute page.
     */
    public function approve(Request $request, Tribute $tribute): JsonResponse|RedirectResponse
    {
        $tribute->update(['approved_at' => $tribute->approved_at ?? now()]);

        return $this->respond($request, $tribute, "{$tribute->name}'s message now shows on the tribute page.");
    }

    /**
     * Take a visitor's message off the tribute page and put it back in the waiting list.
     */
    public function unapprove(Request $request, Tribute $tribute): JsonResponse|RedirectResponse
    {
        $tribute->update(['approved_at' => null]);

        return $this->respond($request, $tribute, "{$tribute->name}'s message is hidden from the tribute page.");
    }

    /**
     * Remove a tribute for good, candle and all.
     */
    public function destroy(Request $request, Tribute $tribute): JsonResponse|RedirectResponse
    {
        $tribute->delete();

        return $this->respond($request, null, "{$tribute->name}'s tribute has been removed.");
    }

    /**
     * Answer the page's in-place update with the redrawn tribute and fresh totals, or send a plain form back to the list.
     */
    private function respond(Request $request, ?Tribute $tribute, string $status): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status,
                'html' => $tribute ? view('admin.tribute', ['tribute' => $tribute])->render() : null,
                'counts' => $this->counts(),
            ]);
        }

        return redirect()->back(fallback: route('admin.tributes'))->with('status', $status);
    }

    /**
     * @return array{candles: int, messages: int, pending: int}
     */
    private function counts(): array
    {
        $withMessage = Tribute::query()->whereNotNull('message')->where('message', '!=', '');

        return [
            'candles' => Tribute::count(),
            'messages' => (clone $withMessage)->count(),
            'pending' => (clone $withMessage)->pending()->count(),
        ];
    }
}
