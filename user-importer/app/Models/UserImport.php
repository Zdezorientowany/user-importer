<?php

namespace App\Models;

use App\ImportStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserImport extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'status', 'passed', 'failed'];

    protected $casts = [
        'status' => ImportStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
