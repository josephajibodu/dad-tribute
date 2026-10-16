<?php

namespace App\Models;

use Database\Factories\TributeFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tribute extends Model
{
    /** @use HasFactory<TributeFactory> */
    use HasFactory;

    protected $fillable = ['name', 'relation', 'message', 'approved_at', 'ip_hash'];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
        ];
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->whereNotNull('approved_at');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->whereNull('approved_at');
    }

    public function hasMessage(): bool
    {
        return filled($this->message);
    }
}
