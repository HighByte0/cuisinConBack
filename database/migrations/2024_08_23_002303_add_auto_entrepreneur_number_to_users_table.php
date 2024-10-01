<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAutoEntrepreneurNumberToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('auto_entrepreneur_number')->nullable(); // Add the 'auto_entrepreneur_number' column
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('auto_entrepreneur_number'); // Remove the column if rolling back
        });
    }
}
