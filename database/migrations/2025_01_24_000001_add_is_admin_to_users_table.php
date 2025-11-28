<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'is_admin')) {
                $afterColumn = null;

                if (Schema::hasColumn('users', 'theme_preference')) {
                    $afterColumn = 'theme_preference';
                } elseif (Schema::hasColumn('users', 'password')) {
                    $afterColumn = 'password';
                } elseif (Schema::hasColumn('users', 'remember_token')) {
                    $afterColumn = 'remember_token';
                }

                $column = $table->boolean('is_admin')->default(false);

                if ($afterColumn) {
                    $column->after($afterColumn);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_admin')) {
                $table->dropColumn('is_admin');
            }
        });
    }
};

