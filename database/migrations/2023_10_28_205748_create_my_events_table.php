<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('my_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->nullOnDelete()->onUpdate('cascade');
            $table->foreignId('doctor_id')->constrained('users')->nullOnDelete()->onUpdate('cascade');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->date('date');
            $table->tinyInteger('status');
            $table->tinyInteger('is_all_day');
            $table->string('title');
            $table->string('description');
            $table->bigInteger('event_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_events');
    }
};
