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
        $table->decimal('over_time', 5, 2)->default(0)->after('late_hours'); 
        // decimal(5,2) allows values like 123.45 hours
    });
}

public function down()
{
    Schema::table('attendances', function (Blueprint $table) {
        $table->dropColumn('over_time');
    });
}

};
