<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('destinasis')) {
            Schema::create('destinasis', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->text('deskripsi')->nullable();
                $table->string('kategori')->nullable();
                $table->string('foto')->nullable();
                $table->string('tiktok')->nullable();
                $table->decimal('rating', 3, 2)->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('destinasis');
    }
};
