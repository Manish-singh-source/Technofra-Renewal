<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationMail;
use App\Models\User;

class Service extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'vendor_id',
        'service_name',
        'service_details',
        'start_date',
        'end_date',
        'billing_date',
        'status',
        'five_days_notified',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'billing_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($service) {
            $fiveDaysFromNow = Carbon::today()->addDays(5);
            if ($service->end_date->isSameDay($fiveDaysFromNow) && !$service->five_days_notified) {
                $adminEmail = env('ADMIN_EMAIL');
                $admin = User::where('email', $adminEmail)->first();
                if ($admin) {
                    Mail::to($adminEmail)->send(new NotificationMail([$service], $admin));
                    $service->five_days_notified = true;
                }
            }
        });
    }

    /**
     * Get the client that owns the service.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the vendor that owns the service.
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the status badge color.
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'active' => 'success',
            'inactive' => 'secondary',
            'expired' => 'danger',
            'pending' => 'warning',
            default => 'primary'
        };
    }
}
