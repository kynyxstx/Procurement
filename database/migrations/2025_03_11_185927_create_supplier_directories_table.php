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
        Schema::create('supplier_directories', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_name');
            $table->text('address');
            $table->text('items');
            $table->string('contact_person');
            $table->string('position');
            $table->string('mobile_no', 11);
            $table->string('telephone_no')->nullable();
            $table->string('email_address')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_directories');
    }
};
