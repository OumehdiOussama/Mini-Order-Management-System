<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add stock, description, and is_active columns to products table.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            // Columns
            if (!Schema::hasColumn('products', 'description')) {
                $table->text('description')->nullable()->after('name');
            }

            if (!Schema::hasColumn('products', 'stock')) {
                $table->unsignedInteger('stock')->default(0)->after('price');
            }

            if (!Schema::hasColumn('products', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('stock');
            }

            // Indexes (PostgreSQL safe)
            try {
                $table->index('is_active', 'products_is_active_index');
            } catch (\Exception $e) {
                // index already exists -> ignore
            }

            try {
                $table->index('stock', 'products_stock_index');
            } catch (\Exception $e) {
                // index already exists -> ignore
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            try {
                $table->dropIndex('products_is_active_index');
            } catch (\Exception $e) {}

            try {
                $table->dropIndex('products_stock_index');
            } catch (\Exception $e) {}

            if (Schema::hasColumn('products', 'is_active')) {
                $table->dropColumn('is_active');
            }

            if (Schema::hasColumn('products', 'stock')) {
                $table->dropColumn('stock');
            }

            if (Schema::hasColumn('products', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};