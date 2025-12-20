<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('destinasis') && !Schema::hasColumn('destinasis', 'alamat')) {
            Schema::table('destinasis', function (Blueprint $table) {
                $table->string('alamat')->nullable()->after('deskripsi');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('destinasis') && Schema::hasColumn('destinasis', 'alamat')) {
            Schema::table('destinasis', function (Blueprint $table) {
                $table->dropColumn('alamat');
            });
        }
    }
};
