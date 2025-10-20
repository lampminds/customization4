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
        Schema::create('lmp_countries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('iso_3')->nullable()->unique();
            $table->string('iso_2')->nullable()->unique();
            $table->string('phonecode')->nullable();
            $table->string('capital')->nullable();
            $table->foreignId('currency_id')->nullable()->constrained('lmp_currencies');
            $table->string('tld')->nullable();
            $table->foreignId('subregion_id')->nullable()->constrained('lmp_subregions')->onDelete('cascade');
            $table->string('emoji')->nullable();
            $table->string('emojiU')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lmp_countries');
    }
};
