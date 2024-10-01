<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePriceToIntegerInFoodsTable extends Migration
{
    public function up()
    {
        Schema::table('foods', function (Blueprint $table) {
            // Change the price column from decimal to integer
            $table->integer('price')->change();
        });
    }

    public function down()
    {
        Schema::table('foods', function (Blueprint $table) {
            // Revert the price column back to decimal
            $table->decimal('price', 8, 2)->change();
        });
    }
}
