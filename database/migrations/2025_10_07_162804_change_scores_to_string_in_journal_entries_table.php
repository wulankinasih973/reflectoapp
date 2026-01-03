<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            $table->string('skor_mood', 512)->nullable()->change();
            $table->string('skor_kecemasan', 512)->nullable()->change();
            $table->string('skor_stres', 512)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            $table->double('skor_mood')->nullable()->change();
            $table->double('skor_kecemasan')->nullable()->change();
            $table->double('skor_stres')->nullable()->change();
        });
    }
};
