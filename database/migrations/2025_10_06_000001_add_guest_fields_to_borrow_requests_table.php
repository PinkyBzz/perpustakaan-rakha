<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('borrow_requests', function (Blueprint $table) {
            // Make user_id nullable to allow guest borrow
            // Need to drop and re-add foreign key to modify nullability
            $table->dropForeign(['user_id']);
            $table->foreignId('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            $table->string('guest_name')->nullable()->after('user_id');
            $table->string('guest_contact')->nullable()->after('guest_name'); // phone / email
            $table->string('guest_identifier')->nullable()->after('guest_contact'); // e.g., NIS / NIK
            $table->boolean('is_guest')->default(false)->after('guest_identifier');
        });
    }

    public function down(): void
    {
        Schema::table('borrow_requests', function (Blueprint $table) {
            // Warning: down() will fail if there are rows with null user_id
            // We attempt to revert structure only.
            $table->dropColumn(['guest_name', 'guest_contact', 'guest_identifier', 'is_guest']);
            $table->dropForeign(['user_id']);
            $table->foreignId('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
