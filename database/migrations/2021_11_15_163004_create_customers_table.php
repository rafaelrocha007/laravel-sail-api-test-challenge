<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'customers',
            function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name')->nullable();
                $table->string('email')->unique();
                $table->float('latitude', '15', '10')->nullable();
                $table->float('longitude', '15', '10')->nullable();
                $table->unsignedBigInteger('company_id');
                $table->unsignedBigInteger('city_id');
                $table->unsignedBigInteger('gender_id');
                $table->unsignedBigInteger('title_id')->nullable();
                $table->timestamps();

                $table->foreign('company_id')->references('id')->on('companies');
                $table->foreign('city_id')->references('id')->on('cities');
                $table->foreign('gender_id')->references('id')->on('genders');
                $table->foreign('title_id')->references('id')->on('titles');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
