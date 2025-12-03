<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('sliders', function (Blueprint $table) {
            // Kita tambahkan kolom 'type' setelah 'image_path'
            // Isinya nanti 'image' atau 'video'
            $table->string('type', 20)->default('image')->after('image_path');
        });
    }
    
    public function down()
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
