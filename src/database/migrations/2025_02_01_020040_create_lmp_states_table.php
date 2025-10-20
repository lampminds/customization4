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
        Schema::create('lmp_states', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('country_id')->constrained('lmp_countries')->onDelete('cascade');
            $table->foreignId('state_type_id')->nullable()->constrained('lmp_state_types')->onDelete('cascade');
            $table->integer('level')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->foreignId('timezone_id')->nullable()->constrained('lmp_timezones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lmp_states');
    }
};
