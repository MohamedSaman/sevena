<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('attendances', function (Blueprint $table) {
        $table->decimal('late_hours', 5, 2)->default(0)->after('time_worked'); 
        // decimal(5,2) means up to 999.99 hours
    });
}

public function down()
{
    Schema::table('attendances', function (Blueprint $table) {
        $table->dropColumn('late_hours');
    });
}

};
