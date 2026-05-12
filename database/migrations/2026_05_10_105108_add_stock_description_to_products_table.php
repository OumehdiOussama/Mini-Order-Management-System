<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add stock, description, and is_active columns to products table.
     * Idempotent — safe to run even if columns already exist.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('products', 'stock')) {
                $table->unsignedInteger('stock')->default(0)->after('price');
            }
            if (!Schema::hasColumn('products', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('stock');
            }
        });

        // Add indexes using raw SQL check (Doctrine removed in Laravel 11)
        $dbName = DB::connection()->getDatabaseName();

        $hasIsActiveIndex = DB::select("
            SELECT COUNT(*) as cnt FROM information_schema.statistics
            WHERE table_schema = ? AND table_name = 'products' AND index_name = 'products_is_active_index'
        ", [$dbName])[0]->cnt > 0;

        $hasStockIndex = DB::select("
            SELECT COUNT(*) as cnt FROM information_schema.statistics
            WHERE table_schema = ? AND table_name = 'products' AND index_name = 'products_stock_index'
        ", [$dbName])[0]->cnt > 0;

        Schema::table('products', function (Blueprint $table) use ($hasIsActiveIndex, $hasStockIndex) {
            if (!$hasIsActiveIndex) {
                $table->index('is_active');
            }
            if (!$hasStockIndex) {
                $table->index('stock');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            try { $table->dropIndex('products_is_active_index'); } catch (\Exception $e) {}
            try { $table->dropIndex('products_stock_index'); } catch (\Exception $e) {}
            if (Schema::hasColumn('products', 'is_active'))   $table->dropColumn('is_active');
            if (Schema::hasColumn('products', 'stock'))       $table->dropColumn('stock');
            if (Schema::hasColumn('products', 'description')) $table->dropColumn('description');
        });
    }
};
