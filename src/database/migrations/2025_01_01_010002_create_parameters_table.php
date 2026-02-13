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
        Schema::create('parameters', function (Blueprint $table) {
            $table->id();
            $table->string('category', 50)->comment('Allows to group parameters');
            $table->string('code', 200)->unique();
            $table->unsignedTinyInteger('type_id')->default(0);
            $table->text('value')->nullable()->comment('Contents vary according to the type');
            $table->unsignedTinyInteger('mode_id')->default(0);
            $table->text('help')->nullable()->comment('Help text visible to users');
            $table->text('comments')->nullable()->comment('Internal comments');
            lmpStamps($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parameters');
    }
};
