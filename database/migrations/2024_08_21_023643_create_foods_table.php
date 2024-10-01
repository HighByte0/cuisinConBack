<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foods', function (Blueprint $table) {
            $table->id(); // Creates an auto-incrementing ID column
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 8, 2); // Decimal for price
            $table->integer('stars')->default(0);
            $table->integer('people')->default(0);
            $table->integer('selected_people')->default(0);
            $table->string('img')->nullable(); // Nullable image path
            $table->string('location')->nullable(); // Nullable location
            $table->foreignId('type_id')->constrained('food_types'); // References `id` on `types`
            $table->foreignId('user_id')->constrained('users')->default(1); // References `id` on `users`
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
        Schema::dropIfExists('foods');
    }
}
