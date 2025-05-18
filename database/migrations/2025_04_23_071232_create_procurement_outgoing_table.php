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
        Schema::create('procurement_outgoing', function (Blueprint $table) {
            $table->id();
            $table->dateTime('received_date')->nullable();
            $table->string('end_user')->nullable();
            $table->string('pr_no')->nullable();
            $table->string('particulars')->nullable();
            $table->string('amount')->nullable();
            $table->string('creditor')->nullable();
            $table->string('remarks')->nullable();
            $table->string('responsibility')->nullable();
            $table->string('received_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_outgoing');
    }
};