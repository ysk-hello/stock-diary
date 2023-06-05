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
        Schema::create('company_diaries', function (Blueprint $table) {
            $table->id();
            $table->char('company_code', 10);
            $table->date('date');
            $table->text('text')->nullable();
            $table->char('judgement', 1)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            
            $table->foreign('company_code')->references('code')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_diaries');
    }
};
