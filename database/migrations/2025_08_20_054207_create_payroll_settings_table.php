<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 // database/migrations/xxxx_xx_xx_xxxxxx_create_payroll_settings_table.php
public function up()
{
    Schema::create('payroll_settings', function (Blueprint $table) {
        $table->id();
        $table->decimal('epf_rate', 5, 2)->default(8.00);
        $table->decimal('etf_rate', 5, 2)->default(3.00);
        $table->decimal('tax_threshold', 12, 2)->default(100000.00);
        $table->decimal('tax_rate', 5, 2)->default(6.00);
        $table->timestamps();
    });
    
    // Insert default values
    DB::table('payroll_settings')->insert([
        'epf_rate' => 8.00,
        'etf_rate' => 3.00,
        'tax_threshold' => 100000.00,
        'tax_rate' => 6.00,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
};
