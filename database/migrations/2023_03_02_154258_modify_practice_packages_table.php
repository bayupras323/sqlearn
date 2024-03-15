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
        Schema::rename('practice_packages', 'packages');
        Schema::table('packages', function (Blueprint $table) {
            $table->dropForeign('practice_packages_user_id_foreign');
            $table->dropForeign('practice_packages_topic_id_foreign');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('topic_id')
                ->references('id')
                ->on('topics')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('packages', 'practice_packages');
        Schema::table('practice_packages', function (Blueprint $table) {
            $table->dropForeign('packages_user_id_foreign');
            $table->dropForeign('packages_topic_id_foreign');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('topic_id')
                ->references('id')
                ->on('topics')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
};
