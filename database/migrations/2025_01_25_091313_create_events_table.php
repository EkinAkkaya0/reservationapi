<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->foreignId('venue_id')->constrained('venues')->onDelete('cascade'); // venue_id, venues tablosuna bağlı
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('status'); // Etkinlik durumu (örneğin: active, canceled, completed)
            $table->timestamps(); // created_at ve updated_at alanları
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
