<?php

namespace App\Console\Commands;

use App\Mail\NotificationMail;
use App\Models\Service;
use App\Models\VendorService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendNotificationEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily notification emails for critical services and vendor services to admin only';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Sending daily notification emails...');

        $today = Carbon::today();
        $fiveDaysFromNow = $today->copy()->addDays(5);

        // Get critical client services (overdue + expiring within next 5 days)
        $criticalServices = Service::with(['client', 'vendor'])
            ->where(function($query) use ($today, $fiveDaysFromNow) {
                $query->where('end_date', '<', $today) // Overdue
                      ->orWhereBetween('end_date', [$today, $fiveDaysFromNow]); // Upcoming
            })
            ->orderByRaw('CASE WHEN end_date < ? THEN 0 ELSE 1 END, end_date ASC', [$today])
            ->get();

        // Get critical vendor services (overdue + expiring within next 5 days)
        $criticalVendorServices = VendorService::with('vendor')
            ->where(function($query) use ($today, $fiveDaysFromNow) {
                $query->where('end_date', '<', $today) // Overdue
                      ->orWhereBetween('end_date', [$today, $fiveDaysFromNow]); // Upcoming
            })
            ->orderByRaw('CASE WHEN end_date < ? THEN 0 ELSE 1 END, end_date ASC', [$today])
            ->get();

        if ($criticalServices->isEmpty() && $criticalVendorServices->isEmpty()) {
            $this->info('No critical services or vendor services found. No emails sent.');
            return 0;
        }

        // Get admin email from env
        $adminEmail = env('ADMIN_EMAIL');

        if (!$adminEmail) {
            $this->error('Admin email not configured in .env');
            return 1;
        }

        // Send email to admin email directly
        try {
            Mail::to($adminEmail)->send(new NotificationMail($criticalServices, $criticalVendorServices));
            $this->info("Email sent to admin: {$adminEmail}");
        } catch (\Exception $e) {
            $this->error("Failed to send email to admin {$adminEmail}: {$e->getMessage()}");
            return 1;
        }

        $this->info('Successfully sent notification email to admin.');
        return 0;
    }
}