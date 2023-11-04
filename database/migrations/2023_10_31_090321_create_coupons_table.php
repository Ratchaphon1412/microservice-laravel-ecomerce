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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['Percent', 'Number']);
            $table->integer('discount');
            $table->string('code')->unique();
            $table->date('expire_date');
            $table->integer('limit_coupon');
            $table->timestamps();
            $table->softDeletes(); //'deleted_at' timestamp nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
