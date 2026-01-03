<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->text('isi_jurnal');          
            $table->integer('skor_mood');        
            $table->integer('skor_kecemasan');  
            $table->integer('skor_stres');      

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('journal_entries');
    }
};
