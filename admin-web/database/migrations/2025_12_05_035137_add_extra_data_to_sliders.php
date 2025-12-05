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
            // Kolom ini akan menyimpan Quote dalam format JSON
            $table->text('extra_data')->nullable()->after('type'); 
        });
    }

    public function down() 
    { 
        Schema::table('sliders', function (Blueprint $table) { 
            $table->dropColumn('extra_data'); 
        }); 
    }
};
