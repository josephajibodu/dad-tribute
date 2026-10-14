<?php

namespace App\Console\Commands;

use App\Models\Tribute;
use Illuminate\Console\Command;

class ReviewTributes extends Command
{
    protected $signature = 'tributes:review {--approve-all : Approve every pending message without asking}';

    protected $description = 'Read messages left by visitors and approve or remove them';

    public function handle(): int
    {
        $pending = Tribute::pending()->oldest()->get();

        if ($pending->isEmpty()) {
            $this->info('No messages waiting. Candles lit so far: '.Tribute::count());

            return self::SUCCESS;
        }

        if ($this->option('approve-all')) {
            Tribute::pending()->update(['approved_at' => now()]);
            $this->info("Approved {$pending->count()} message(s).");

            return self::SUCCESS;
        }

        $this->info("{$pending->count()} message(s) waiting.");

        foreach ($pending as $tribute) {
            $this->newLine();
            $this->line("<fg=yellow>#{$tribute->id}</> <options=bold>{$tribute->name}</>".($tribute->relation ? " - {$tribute->relation}" : ''));
            $this->line('<fg=gray>'.$tribute->created_at->format('D j M Y, g:ia').'</>');
            $this->line($tribute->hasMessage() ? $tribute->message : '<fg=gray>(lit a candle, no message)</>');

            $choice = $this->choice('What should happen to this one?', ['approve', 'remove', 'skip', 'stop'], 'approve');

            match ($choice) {
                'approve' => $tribute->update(['approved_at' => now()]),
                'remove' => $tribute->delete(),
                default => null,
            };

            if ($choice === 'stop') {
                break;
            }
        }

        $this->newLine();
        $this->info('Done.');

        return self::SUCCESS;
    }
}
