<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('hashed_session_id')->nullable();
            $table->string('name');
            $table->integer('price');
            $table->text('memo')->nullable();
            $table->string('status')->default('pending');
            $table->timestampTz('status_changed_at');
            $table->timestampsTz();

            $table->index(['user_id', 'status', 'status_changed_at']);
        });

        DB::statement('
            ALTER TABLE items ADD CONSTRAINT items_owner_id_check
            CHECK ((user_id IS NOT NULL AND hashed_session_id IS NULL)
            OR (user_id IS NULL AND hashed_session_id IS NOT NULL))   
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
