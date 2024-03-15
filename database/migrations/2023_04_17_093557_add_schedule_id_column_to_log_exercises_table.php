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
        Schema::table('log_exercises', function (Blueprint $table) {
            $table->foreignId('schedule_id')->nullable()->after('student_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_exercises', function (Blueprint $table) {
            $table->dropColumn('schedule_id');
        });
    }
};
