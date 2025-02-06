<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_travels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_travel_status_id')->default(1);
            $table->string('name_applicant');
            $table->string('destination');
            $table->dateTime('departure_date');
            $table->dateTime('return_date');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('order_travel_status_id')
                ->references('id')
                ->on('order_travel_status');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_travels');
    }
};
