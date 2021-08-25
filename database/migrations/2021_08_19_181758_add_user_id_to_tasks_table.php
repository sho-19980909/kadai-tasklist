<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            //user_idカラムを追加
            $table->unsignedBigInteger('user_id');
            
            //外部キー制約(データベース側の整合性を担保するために利用。)
            // database/migrationsにある'tasks'テーブルの'user_id'カラムに存在しない'users'テーブルの'id'カラムが存在しないようにする機能。
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 外部キー制約の削除
        $table->dropForeign('tasks_user_id_foreign');
        
        // usr_idカラムを削除する
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumun('user_id');
        });
    }
}
