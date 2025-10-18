<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('point_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('points');
            $table->timestamp('granted_at')->useCurrent();
            $table->timestamps();
            $table->unique('report_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_histories');
    }
};
