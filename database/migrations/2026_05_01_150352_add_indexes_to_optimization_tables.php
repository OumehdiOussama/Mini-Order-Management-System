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
        Schema::table('orders', function (Blueprint $table) {
            $table->index('status');
            $table->index('customer_id');
            $table->index('created_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('email');
        });
        
        Schema::table('products', function (Blueprint $table) {
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['customer_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['email']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });
    }
};
