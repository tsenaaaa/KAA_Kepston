<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('destinasis') && !Schema::hasColumn('destinasis', 'rating')) {
            Schema::table('destinasis', function (Blueprint $table) {
                $table->decimal('rating', 3, 2)->nullable()->after('tiktok');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('destinasis') && Schema::hasColumn('destinasis', 'rating')) {
            Schema::table('destinasis', function (Blueprint $table) {
                $table->dropColumn('rating');
            });
        }
    }
};
