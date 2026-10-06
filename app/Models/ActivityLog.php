<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'module',
        'ip_address',
        'user_agent',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log(string $action, string $description, string $module = 'General', ?int $userId = null): self
    {
        return self::create([
            'user_id' => $userId ?? (Auth::check() ? Auth::id() : null),
            'action' => $action,
            'description' => $description,
            'module' => $module,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
