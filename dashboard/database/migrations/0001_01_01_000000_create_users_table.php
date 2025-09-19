<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration sets up the initial database schema for the application.
// Note: It's customized to use 'teacher_id' instead of 'email' for user identification.
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This method is executed when the 'php artisan migrate' command is run.
     * It builds the database tables required for the application to function.
     */
    public function up(): void
    {
        // Create the main 'users' table to store application user accounts.
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key (e.g., 1, 2, 3...).
            $table->string('name'); // The user's full name.

            // CHANGE 1: Use 'teacher_id' as the unique identifier instead of 'email'.
            // The unique constraint ensures no two users can have the same teacher_id.
            $table->string('teacher_id')->unique();

            // CHANGE 2: The 'email_verified_at' column is intentionally removed
            // because this application does not use an email verification flow.
            $table->string('password'); // The user's hashed password.
            $table->rememberToken(); // Stores a token for "remember me" functionality.
            $table->timestamps(); // Adds 'created_at' and 'updated_at' columns.
        });

        // Create the table to store tokens for password reset requests.
        // This table is also customized to use 'teacher_id' as the key.
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            // CHANGE 3: 'teacher_id' links the reset token to a user. It's the
            // primary key here, meaning a user can only have one active reset token.
            $table->string('teacher_id')->primary();
            $table->string('token'); // The secure, random token for the reset request.
            $table->timestamp('created_at')->nullable(); // Timestamp of when the token was created.
        });

        // Create the standard Laravel 'sessions' table to handle user session data.
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // The unique session ID.
            $table->foreignId('user_id')->nullable()->index(); // ID of the logged-in user (if any). Indexed for faster lookups.
            $table->string('ip_address', 45)->nullable(); // The client's IP address.
            $table->text('user_agent')->nullable(); // The client's browser/device information.
            $table->longText('payload'); // The serialized session data itself.
            $table->integer('last_activity')->index(); // Timestamp of the user's last activity. Indexed for performance.
        });
    }

    /**
     * Reverse the migrations.
     *
     * This method is executed when the 'php artisan migrate:rollback' command is run.
     * It drops all the tables created in the 'up' method to reset the database state.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};