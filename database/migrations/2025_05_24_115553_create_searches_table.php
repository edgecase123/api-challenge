<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('searches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('search_list_id');
            $table->string('term', '32');
            $table->string('field', '32');
            $table->integer('limit')->default(100);
            $table->timestamps();

            $table->foreign('search_list_id')
                ->references('id')
                ->on('search_lists')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('searches');
    }
};
