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
        Schema::rename('practices', 'exercises');
        Schema::table('exercises', function (Blueprint $table) {
            $table->dropForeign('practices_database_id_foreign');
            $table->foreign('database_id')
                ->references('id')
                ->on('databases')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('package_id')
                ->after('id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->enum('type', ['ERD', 'DDL', 'DML'])->after('package_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('exercises', 'practices');
        Schema::table('practices', function (Blueprint $table) {
            $table->dropForeign('exercises_database_id_foreign');
            $table->foreign('database_id')
                ->references('id')
                ->on('databases')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->dropForeign('exercises_package_id_foreign');
            $table->dropColumn(['package_id', 'type']);
        });
    }
};
