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
        Schema::table('komplains', function (Blueprint $table) {
            $table->string('topik')->after('kontak')->nullable();
        });
    }

    public function down()
    {
        Schema::table('komplains', function (Blueprint $table) {
            $table->dropColumn('topik');
        });
    }
};
