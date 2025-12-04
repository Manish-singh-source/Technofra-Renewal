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
        Schema::table('vendor_services', function (Blueprint $table) {
            $table->enum('plan_type', ['monthly', 'yearly', 'quarterly'])->after('service_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_services', function (Blueprint $table) {
            $table->dropColumn('plan_type');
        });
    }
};
