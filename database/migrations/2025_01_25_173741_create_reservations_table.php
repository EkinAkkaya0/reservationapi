<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('reservations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('event_id')->constrained()->onDelete('cascade');
        $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
        $table->decimal('total_amount', 8, 2);
        $table->timestamp('expires_at');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('reservations');
}

};
