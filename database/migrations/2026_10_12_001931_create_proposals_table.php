<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->string('project_title');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->enum('status', ['new', 'approved', 'disapproved'])->default('new');
            $table->text('description')->nullable();
            $table->text('objective');
            $table->text('attachment');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('est_budget');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('project_lead')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
