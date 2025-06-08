<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('user_gmail')->unique();
            $table->string('user_password');
            $table->string('user_nombre');
            $table->string('user_apellido');
            $table->integer('user_tipo')->default(1); 
            $table->boolean('user_activo')->default(true);
            $table->string('remember_token')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};