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
        Schema::table('destinasis', function (Blueprint $table) {
            $table->integer('reviews_count')->nullable()->after('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('destinasis', function (Blueprint $table) {
            $table->dropColumn('reviews_count');
        });
    }
};
