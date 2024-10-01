<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key with auto-increment
            $table->string('data', 225)->nullable(); // Data column with a maximum length of 225 characters
            $table->tinyInteger('status')->nullable(); // Status column
            $table->unsignedInteger('user_id')->nullable(); // User ID column, unsigned
            $table->unsignedInteger('vendor_id')->nullable(); // Vendor ID column, unsigned
            $table->unsignedInteger('delivery_man_id')->nullable(); // Delivery Man ID column, unsigned
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_notifications');
    }
}
