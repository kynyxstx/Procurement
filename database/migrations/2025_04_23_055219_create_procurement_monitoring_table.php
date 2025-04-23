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
        Schema::create('procurement_monitoring', function (Blueprint $table) {
            $table->id();
            $table->string('pr_no');
            $table->string('title');
            $table->string('processor');
            $table->string('supplier');
            $table->string('end-user');
            $table->string('status');
            $table->dateTime('date_endorsement');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_monitoring');
    }
};
