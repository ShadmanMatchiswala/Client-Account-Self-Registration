<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');            
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('email', 150)->unique();
            // $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 20)->nullable();
            $table->string('profile_uri', 255)->nullable();
            $table->timestamp('last_password_reset', $precision = 0)->nullable();
            $table->enum('status', ['Active', 'Inactive']);
            $table->rememberToken();
            $table->timestamps();            
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            // $table->foreign('client_id')->nullable()->references('id')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
