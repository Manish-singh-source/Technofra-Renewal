<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            // Drop the amount column
            $table->dropColumn('amount');

            // Add the billing_date column as nullable first
            $table->date('billing_date')->nullable()->after('end_date');
        });

        // Update existing records to set billing_date to end_date
        DB::table('services')->update(['billing_date' => DB::raw('end_date')]);

        // Make billing_date not nullable
        Schema::table('services', function (Blueprint $table) {
            $table->date('billing_date')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            // Drop the billing_date column
            $table->dropColumn('billing_date');

            // Add back the amount column
            $table->decimal('amount', 10, 2)->after('end_date');
        });
    }
};
