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
        Schema::create('boats', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('code', 20)->unique();
            $table->string('boat_name', 100);
            $table->string('owner_name', 50);
            $table->string('owner_address', 255);
            $table->string('boat_size', 30);
            $table->string('captain', 100);
            $table->tinyInteger('member');
            $table->text('foto');
            $table->text('document');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boats');
    }
};
