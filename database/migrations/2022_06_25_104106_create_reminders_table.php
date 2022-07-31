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
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->uuid('pid')->unique();
            $table->foreignId('user_id')->constrained("users")->onDelete('cascade');
            $table->string('reminder_title', 300);
            $table->string('reminder_descriptions', 850)->nullable();
            $table->enum('privacy', ['private', 'shared', 'public'])->default('private');
            $table->dateTime('due_date');
            $table->text('link')->nullable();
            $table->text('people')->nullable();
            $table->boolean('first_notified')->default(false);
            $table->boolean('second_notified')->default(false);
            $table->softDeletes();
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
        Schema::dropIfExists('reminders');
    }
};
