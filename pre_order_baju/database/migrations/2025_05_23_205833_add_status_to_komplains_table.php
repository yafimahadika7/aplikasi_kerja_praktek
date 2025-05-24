<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('komplains', function (Blueprint $table) {
            $table->enum('status', ['open', 'closed'])->default('open')->after('kontak');
        });
    }

    public function down(): void
    {
        Schema::table('komplains', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};