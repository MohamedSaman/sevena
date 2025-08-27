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
   // database/migrations/xxxx_xx_xx_xxxxxx_create_system_settings_table.php
public function up()
{
    Schema::create('system_settings', function (Blueprint $table) {
        $table->id();
        $table->string('key')->unique();
        $table->text('value')->nullable();
        $table->timestamps();
    });
    
    // Insert default values
    $defaultSettings = [
        ['key' => 'enable_email_notifications', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'auto_calculate_salary', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'enable_two_factor', 'value' => '0', 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'default_currency', 'value' => 'LKR', 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'date_format', 'value' => 'YYYY-MM-DD', 'created_at' => now(), 'updated_at' => now()],
    ];
    
    DB::table('system_settings')->insert($defaultSettings);
}
};
