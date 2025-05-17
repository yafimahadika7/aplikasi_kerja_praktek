<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRoleColumnOnUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Ubah kolom role jadi ENUM
            $table->enum('role', ['admin', 'produk', 'operation', 'finance'])->default('admin')->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Kembalikan jadi string biasa jika rollback
            $table->string('role')->default('user')->change();
        });
    }
}