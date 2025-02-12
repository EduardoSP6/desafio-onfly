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
        Schema::create('travel_orders', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            $table->unsignedBigInteger('user_id')->index();

            $table->foreign('user_id', 'travel_orders_user_id_foreign')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('order_id')->unique();

            $table->string('destination')->index();

            $table->date('departure_date')->index();

            $table->date('return_date')->index();

            $table->string('status')->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_orders');
    }
};
