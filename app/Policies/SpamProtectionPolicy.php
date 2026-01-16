<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class SpamProtectionPolicy
{
    /**
     * Determine whether the user can view spam protection settings.
     */
    public function viewSettings(User $user): bool
    {
        return $user->hasRole('developer')
            || $user->hasRole('admin')
            || $user->hasPermission('spam_protection.view_settings');
    }

    /**
     * Determine whether the user can update spam protection settings.
     */
    public function updateSettings(User $user): bool
    {
        return $user->hasRole('developer')
            || $user->hasPermission('spam_protection.update_settings');
    }

    /**
     * Determine whether the user can view spam reports.
     */
    public function viewReports(User $user): bool
    {
        return $user->hasRole('developer')
            || $user->hasRole('admin')
            || $user->hasPermission('spam_protection.view_reports');
    }

    /**
     * Determine whether the user can manage blocked IPs.
     */
    public function manageBlockedIps(User $user): bool
    {
        return $user->hasRole('developer')
            || $user->hasRole('admin')
            || $user->hasPermission('spam_protection.manage_blocked_ips');
    }

    /**
     * Determine whether the user can delete spam submissions.
     */
    public function deleteSpamSubmissions(User $user): bool
    {
        return $user->hasRole('developer')
            || $user->hasPermission('spam_protection.delete_spam');
    }

    /**
     * Determine whether the user can whitelist IPs or emails.
     */
    public function manageWhitelist(User $user): bool
    {
        return $user->hasRole('developer')
            || $user->hasPermission('spam_protection.manage_whitelist');
    }

    /**
     * Determine whether the user can configure spam detection rules.
     */
    public function configureRules(User $user): bool
    {
        return $user->hasRole('developer')
            || $user->hasPermission('spam_protection.configure_rules');
    }

    /**
     * Determine whether the user can view spam logs.
     */
    public function viewLogs(User $user): bool
    {
        return $user->hasRole('developer')
            || $user->hasRole('admin')
            || $user->hasPermission('spam_protection.view_logs');
    }
}
