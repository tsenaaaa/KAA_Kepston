<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('destinasis')) {
            Schema::table('destinasis', function (Blueprint $table) {
                if (!Schema::hasColumn('destinasis', 'latitude')) {
                    $table->decimal('latitude', 10, 7)->nullable()->after('alamat');
                }
                if (!Schema::hasColumn('destinasis', 'longitude')) {
                    $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('destinasis')) {
            Schema::table('destinasis', function (Blueprint $table) {
                if (Schema::hasColumn('destinasis', 'longitude')) {
                    $table->dropColumn('longitude');
                }
                if (Schema::hasColumn('destinasis', 'latitude')) {
                    $table->dropColumn('latitude');
                }
            });
        }
    }
};
