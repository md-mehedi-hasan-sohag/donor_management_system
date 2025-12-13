<?php

namespace App\Services;

use App\Models\User;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthService
{
    /**
     * Register a new user
     */
    public function register(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'email_verified_at' => now(),
        ]);

        return $user;
    }

    /**
     * Attempt to authenticate a user
     */
    public function login(array $credentials, bool $remember = false): bool
    {
        return Auth::attempt($credentials, $remember);
    }

    /**
     * Log out the current user
     */
    public function logout(): void
    {
        Auth::logout();
    }

    /**
     * Send password reset link to user
     */
    public function sendPasswordResetLink(string $email): bool
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            // Return true but don't reveal if user exists (security)
            return true;
        }

        // Delete any existing tokens
        $this->deleteExistingResetTokens($email);

        // Generate and store new token
        $token = $this->createResetToken($email);

        // Send email
        $this->sendResetEmail($user, $token);

        return true;
    }

    /**
     * Reset user password with token
     */
    public function resetPassword(string $email, string $token, string $newPassword): array
    {
        // Find the token record
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$resetRecord) {
            return [
                'success' => false,
                'message' => 'Invalid or expired reset token.'
            ];
        }

        // Verify token
        if (!Hash::check($token, $resetRecord->token)) {
            return [
                'success' => false,
                'message' => 'Invalid or expired reset token.'
            ];
        }

        // Check if token is expired (24 hours)
        if ($this->isTokenExpired($resetRecord->created_at)) {
            $this->deleteExistingResetTokens($email);
            return [
                'success' => false,
                'message' => 'Reset token has expired. Please request a new one.'
            ];
        }

        // Update password
        $user = User::where('email', $email)->first();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found.'
            ];
        }

        $user->password = Hash::make($newPassword);
        $user->save();

        // Delete used token
        $this->deleteExistingResetTokens($email);

        return [
            'success' => true,
            'message' => 'Password reset successfully!'
        ];
    }

    /**
     * Delete existing reset tokens for email
     */
    private function deleteExistingResetTokens(string $email): void
    {
        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->delete();
    }

    /**
     * Create password reset token
     */
    private function createResetToken(string $email): string
    {
        $token = Str::random(60);

        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        return $token;
    }

    /**
     * Send password reset email
     */
    private function sendResetEmail(User $user, string $token): void
    {
        $resetUrl = url('/reset-password/' . $token . '?email=' . urlencode($user->email));

        $template = EmailTemplate::where('slug', 'password-reset')->first();

        if ($template) {
            $emailData = $template->render([
                'name' => $user->name,
                'reset_url' => $resetUrl,
            ]);

            Mail::send([], [], function ($message) use ($user, $emailData) {
                $message->to($user->email)
                    ->subject($emailData['subject'])
                    ->html($emailData['body']);
            });
        }
    }

    /**
     * Check if reset token is expired
     */
    private function isTokenExpired($createdAt): bool
    {
        return now()->diffInHours($createdAt) > 24;
    }

    /**
     * Get the authenticated user
     */
    public function getAuthenticatedUser(): ?User
    {
        return Auth::user();
    }
}
