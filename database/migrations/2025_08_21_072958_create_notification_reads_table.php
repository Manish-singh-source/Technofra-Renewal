<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->string('notification_type'); // 'expired', 'expiring_today', 'expiring_tomorrow', etc.
            $table->timestamp('read_at');
            $table->timestamps();

            // Ensure a user can only mark a specific notification as read once
            $table->unique(['user_id', 'service_id', 'notification_type']);

            // Index for faster queries
            $table->index(['user_id', 'service_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_reads');
    }
};
