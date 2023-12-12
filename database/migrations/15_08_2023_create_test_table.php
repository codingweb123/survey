<?php
use SDK\Schema;
use SDK\Blueprint;
class CreateTestTable extends Schema
{
    public static function up(): void
    {
        Schema::create("test", function () {
            $table = new Blueprint("test");
            $table->id();
            $table->string("title");
            $table->save();
        });
    }
    public static function down(): void
    {
        Schema::delete("test");
    }
}