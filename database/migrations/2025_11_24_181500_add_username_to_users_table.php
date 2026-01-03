<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('username')->nullable()->after('name');
            });
        }

        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('user')->after('password');
            });

            DB::table('users')->whereNull('role')->update(['role' => 'user']);
        }

        $seenUsernames = [];
        DB::table('users')->select('id', 'username', 'name')->orderBy('id')->chunk(100, function ($users) use (&$seenUsernames) {
            foreach ($users as $user) {
                $base = $user->username ?? $user->name ?? 'user';
                $base = trim($base) !== '' ? $base : 'user';
                $slug = Str::slug($base, '_');
                $slug = $slug !== '' ? $slug : 'user';
                $original = $slug;
                $suffix = 1;

                while (in_array($slug, $seenUsernames, true) || DB::table('users')->where('username', $slug)->where('id', '!=', $user->id)->exists()) {
                    $slug = $original . '_' . $suffix;
                    $suffix++;
                }

                DB::table('users')->where('id', $user->id)->update(['username' => $slug]);
                $seenUsernames[] = $slug;
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                return;
            }

            $table->unique('username');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'username')) {
                $table->dropUnique('users_username_unique');
                $table->dropColumn('username');
            }

            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
