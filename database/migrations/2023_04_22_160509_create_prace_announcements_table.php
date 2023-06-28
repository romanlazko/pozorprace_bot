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
        Schema::create('prace_announcements', function (Blueprint $table) {
            $table->id();
            
            $table->bigInteger('chat')->nullable();
            $table->string('city')->nullable();
            $table->string('type')->nullable();
            $table->string('title')->nullable();
            $table->text('caption')->nullable();
            $table->text('additional_info')->nullable();
            $table->string('salary_type')->nullable();
            $table->string('salary')->nullable();
            $table->string('education')->nullable();
            $table->string('workplace')->nullable();
            $table->unsignedBigInteger('views')->default('0');
            $table->string('status')->default('new')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prace_announcements');
    }
};
