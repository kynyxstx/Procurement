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
            $table->dateTime('received_date');
            $table->string('end_user');
            $table->string('pr_no');
            $table->string('particulars');
            $table->string('amount');
            $table->string('creditor');
            $table->string('remarks');
            $table->string('responsibility');
            $table->string('received_by');
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
