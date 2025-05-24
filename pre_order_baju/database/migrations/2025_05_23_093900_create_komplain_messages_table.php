<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKomplainMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('komplain_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('komplain_id')->constrained()->onDelete('cascade');
            $table->text('pesan');
            $table->enum('pengirim', ['pelanggan', 'admin']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('komplain_messages');
    }
}