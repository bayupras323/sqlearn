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
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign('schedules_practice_packages_id_foreign');
            $table->renameColumn('practice_packages_id', 'package_id');
            $table->dropColumn('tipe');
            $table->enum('type', ['exam', 'practice'])->after('name');
            $table->foreign('package_id')
                ->references('id')
                ->on('packages')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('name', 100)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->renameColumn('package_id', 'practice_packages_id');
            $table->dropColumn('type');
            $table->string('tipe', 20)->after('name');
        });
    }
};
