<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('client_name', 100);
            $table->text('address1')->nullable();
            $table->text('address2')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->double('latitude', 10, 6)->nullable();
            $table->double('longitude', 10, 6)->nullable();
            $table->string('phone_no1', 20)->nullable();
            $table->string('phone_no2', 20)->nullable();
            $table->string('zip', 20)->nullable();
            $table->date('start_validity')->nullable();
            $table->date('end_validity')->nullable();
            $table->enum('status', ['Active', 'Inactive']);
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });

        Schema::table('users', function($table) {
            $table->foreign('client_id')->nullable()->references('id')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table) {                      
            $table->dropForeign('users_client_id_foreign');
            $table->dropIndex('users_client_id_foreign');
        });

        Schema::dropIfExists('clients');        
    }
}
