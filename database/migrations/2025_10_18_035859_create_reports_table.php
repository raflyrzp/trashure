<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Report;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('waste_type_id')->nullable()->constrained('waste_types')->nullOnDelete();

            $table->text('description');
            $table->string('location', 255);
            $table->string('photo_url', 255);

            $table->string('status', 50)->default(Report::STATUS_PENDING);
            $table->timestamp('reported_at')->useCurrent();
            $table->timestamp('verified_at')->nullable();
            $table->text('admin_notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
