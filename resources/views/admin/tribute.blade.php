@php
    $status = match (true) {
        ! $tribute->hasMessage() => 'candle',
        $tribute->approved_at !== null => 'approved',
        default => 'pending',
    };
@endphp
<article class="tribute" id="tribute-{{ $tribute->id }}" data-status="{{ $status }}">
    <div class="tribute__head">
        <span class="tribute__name">{{ $tribute->name }}</span>
        @if ($tribute->relation)
            <span class="tribute__relation">{{ $tribute->relation }}</span>
        @endif
        @if ($status === 'pending')
            <span class="badge badge--pending">Waiting</span>
        @elseif ($status === 'approved')
            <span class="badge">Shown</span>
        @endif
        <time class="tribute__date" datetime="{{ $tribute->created_at->toIso8601String() }}">{{ $tribute->created_at->format('D j M Y, g:ia') }}</time>
    </div>
    @if ($tribute->hasMessage())
        <p class="tribute__message">{{ $tribute->message }}</p>
    @else
        <p class="tribute__empty">Lit a candle, no message.</p>
    @endif
    <div class="tribute__actions">
        @if ($status === 'pending')
            <form method="POST" action="{{ route('admin.tributes.approve', $tribute) }}" data-action>
                @csrf
                <button type="submit" class="action action--primary">Approve</button>
            </form>
        @elseif ($status === 'approved')
            <form method="POST" action="{{ route('admin.tributes.unapprove', $tribute) }}" data-action>
                @csrf
                @method('DELETE')
                <button type="submit" class="action">Hide from page</button>
            </form>
        @endif
        <form method="POST" action="{{ route('admin.tributes.destroy', $tribute) }}" data-action data-confirm="Remove {{ $tribute->name }}'s tribute for good? This also puts out their candle and can't be undone.">
            @csrf
            @method('DELETE')
            <button type="submit" class="action action--danger">Remove</button>
        </form>
    </div>
</article>
