<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTributeRequest;
use App\Models\Tribute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class TributeController extends Controller
{
    public function index(): View
    {
        $config = config('tribute');

        $hasTable = Schema::hasTable('tributes');

        $tracks = collect($config['music'] ?? [])
            ->filter(fn (array $track) => is_file(public_path($track['file'])))
            ->map(fn (array $track) => [
                'title' => $track['title'],
                'note' => $track['note'] ?? '',
                'src' => asset($track['file']),
            ])
            ->values();

        return view('tribute', [
            't' => $config,
            'tracks' => $tracks,
            'portrait' => $this->imageOrNull($config['portrait'] ?? null),
            'moments' => collect($config['moments'] ?? [])->map(fn (array $m) => $m + [
                'src' => $this->imageOrNull($m['image'] ?? null),
            ]),
            'visitorTributes' => $hasTable
                ? Tribute::approved()->whereNotNull('message')->where('message', '!=', '')->latest('approved_at')->limit(60)->get()
                : collect(),
            'candleCount' => $hasTable ? Tribute::count() : 0,
        ]);
    }

    public function store(StoreTributeRequest $request): JsonResponse|RedirectResponse
    {
        $data = $request->validated();
        $moderate = (bool) config('tribute.moderate', true);

        $tribute = Tribute::create([
            'name' => trim($data['name']),
            'relation' => isset($data['relation']) ? trim($data['relation']) : null,
            'message' => isset($data['message']) ? trim($data['message']) : null,
            'approved_at' => $moderate ? null : now(),
            'ip_hash' => hash('sha256', $request->ip().config('app.key')),
        ]);

        $thanks = $tribute->hasMessage() && $moderate
            ? 'Thank you. Your candle is lit, and your words will appear once the family has seen them.'
            : 'Thank you. Your candle now burns alongside the others.';

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $thanks,
                'candles' => Tribute::count(),
            ], 201);
        }

        return redirect()->to(route('tribute').'#candle')->with('lit', $thanks);
    }

    private function imageOrNull(?string $path): ?string
    {
        return $path && is_file(public_path($path)) ? asset($path) : null;
    }
}
