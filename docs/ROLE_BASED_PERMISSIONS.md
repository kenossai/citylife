# Role-Based Permission System - Implementation Guide

## Overview

The City Life Church management system now includes a comprehensive role-based permission system for staff access control. This system provides granular permissions for different areas of the application while maintaining the existing member authentication system.

## Architecture

### Dual Authentication System
- **Staff/Admin Users** (`users` table) - Role-based permissions via `web` guard
- **Church Members** (`members` table) - Basic authentication via `member` guard  

### Core Components

#### 1. Database Tables
- `permissions` - Individual permissions (e.g., 'members.create', 'courses.edit')
- `roles` - Role definitions (e.g., 'pastor', 'admin', 'volunteer_coordinator')
- `role_permission` - Many-to-many relationship between roles and permissions
- `user_roles` - Assigns roles to users with metadata (expiration, conditions)

#### 2. Models
- `Permission` - Manages individual permissions
- `Role` - Manages roles and role-permission relationships  
- `UserRole` - Pivot model for user-role assignments
- `User` - Extended with role and permission methods

#### 3. Filament Resources
- `PermissionResource` - Manage permissions in admin panel
- `RoleResource` - Manage roles and assign permissions
- `StaffUserResource` - Manage staff users and assign roles

#### 4. Middleware
- `CheckPermission` - Protects routes with permission checks

## Permission Categories

The system organizes permissions into logical categories:

- **System** - User management, roles, system settings
- **Members** - Member CRUD operations, data export
- **Courses** - Course management and enrollments
- **Pastoral** - Pastoral care, reminders, confidential notes
- **Worship** - Worship departments and schedules
- **Technical** - Technical departments and equipment
- **Communications** - Email, SMS, templates
- **Reports** - Analytics and report generation
- **GDPR** - Data protection compliance

## Default Roles

### Administrative Roles
- **Super Administrator** - Complete system access (priority: 1000)
- **Administrator** - Most administrative functions (priority: 900)

### Ministry Roles
- **Pastor** - Pastoral care and member management (priority: 800)
- **Member Coordinator** - Member data management (priority: 700)
- **Worship Leader** - Worship department management (priority: 600)
- **Technical Coordinator** - Technical department management (priority: 600)
- **Volunteer Coordinator** - Volunteer management (priority: 500)
- **Communications Manager** - Communication management (priority: 400)

### Basic Roles
- **General Staff** - Basic staff access (priority: 200)
- **Volunteer** - Limited volunteer access (priority: 100)

## Usage Examples

### Checking Permissions in Code

```php
// Check if user has specific permission
if (Auth::user()->hasPermission('members.create')) {
    // Allow member creation
}

// Check if user has any of multiple permissions
if (Auth::user()->hasAnyPermission(['members.view_all', 'members.create'])) {
    // Allow access
}

// Check if user has a specific role
if (Auth::user()->hasRole('pastor')) {
    // Pastor-specific functionality
}
```

### Using Middleware

```php
// Protect routes with permissions
Route::middleware(['auth', 'permission:members.create'])->group(function () {
    Route::post('/members', [MemberController::class, 'store']);
});

// Multiple permission options (OR logic)
Route::middleware(['auth', 'permission:members.view_all,members.create'])->group(function () {
    Route::get('/members', [MemberController::class, 'index']);
});
```

### Assigning Roles to Users

```php
// Assign a role
$user = User::find(1);
$user->assignRole('pastor');

// Assign role with expiration
$user->assignRole('volunteer', Auth::user(), now()->addMonths(6));

// Assign multiple roles
$user->syncRoles(['pastor', 'worship_leader'], Auth::user());

// Remove a role
$user->removeRole('volunteer');
```

### Managing Role Permissions

```php
// Grant permission to role
$role = Role::find(1);
$role->grantPermission('members.create');

// Revoke permission
$role->revokePermission('members.delete');

// Check if role has permission
if ($role->hasPermission('members.view_all')) {
    // Role has permission
}
```

## Admin Interface

### Accessing the Admin Panel
Navigate to `/admin` and login with a staff user account.

### Managing Permissions
- **View**: Admin → Permissions
- **Create**: Define new permissions with categories
- **Edit**: Modify permission details
- **Delete**: Remove custom permissions (system permissions protected)

### Managing Roles
- **View**: Admin → Roles  
- **Create**: Define new roles with permission assignments
- **Edit**: Modify role details and permissions
- **Delete**: Remove roles (checks for assigned users)

### Managing Staff Users
- **View**: Admin → Staff Users
- **Create**: Add new staff accounts
- **Edit**: Modify user details and role assignments
- **Roles**: Assign/remove roles with optional expiration

## Permission Naming Convention

Use dot notation for hierarchical permissions:
- `category.action` - e.g., `members.create`
- `category.subcategory.action` - e.g., `finance.donations.view`

## Security Features

### System Protection
- **System permissions** cannot be deleted
- **System roles** cannot be deleted  
- **Super admin role** has automatic access to everything
- **Role assignment tracking** with assignment date and user

### Data Integrity
- **Cascade deletion** for role-permission relationships
- **User role validation** before role deletion
- **JSON validation** for role settings
- **Audit trails** for permission changes

## API Reference

### User Model Methods

```php
// Role checks
hasRole(string $role): bool
hasAnyRole(array $roles): bool  
hasAllRoles(array $roles): bool

// Permission checks
hasPermission(string $permission): bool
hasPermissionInCategory(string $category): bool
hasAnyPermission(array $permissions): bool
getAllPermissions(): Collection

// Role management
assignRole(Role|string $role, ?User $assignedBy = null, ?\DateTimeInterface $expiresAt = null): void
removeRole(Role|string $role): void
syncRoles(array $roles, ?User $assignedBy = null): void
```

### Role Model Methods

```php
// Permission management
hasPermission(string $permission): bool
hasPermissionInCategory(string $category): bool
grantPermission(Permission|string $permission): void
revokePermission(Permission|string $permission): void

// Static helpers
getDefaultRoles(): array
```

### Permission Model Methods

```php
// Static helpers
getCategories(): array
getGroupedPermissions(): Collection
```

## Database Seeding

The system includes comprehensive seeders:

```bash
# Seed permissions
php artisan db:seed --class=PermissionSeeder

# Seed roles and assign permissions  
php artisan db:seed --class=RoleSeeder

# Assign roles to existing users
php artisan db:seed --class=UserRoleSeeder
```

## Troubleshooting

### Common Issues

1. **Permission denied errors**
   - Check user has required role/permission
   - Verify middleware is properly applied
   - Ensure user is authenticated with correct guard

2. **Role assignment not working**
   - Verify role exists and is active
   - Check for expired role assignments
   - Ensure proper user model relationships

3. **Admin panel access issues**
   - Confirm user has admin-level role
   - Check Filament authentication guard
   - Verify user account is active

### Debug Commands

```bash
# Check user permissions
php artisan tinker
>>> $user = User::find(1);
>>> $user->getAllPermissions();
>>> $user->roles;

# Verify role permissions
>>> $role = Role::find(1);  
>>> $role->permissions;
>>> $role->hasPermission('members.create');
```

## Migration and Maintenance

### Adding New Permissions
1. Create permission via admin panel or seeder
2. Assign to appropriate roles
3. Update middleware protection as needed

### Creating Custom Roles  
1. Use admin panel or seeder
2. Define appropriate permissions
3. Set priority level
4. Assign to users

### User Management
1. Create staff users via admin panel
2. Assign appropriate roles based on responsibilities
3. Set role expiration for temporary access
4. Monitor and audit role assignments

## Best Practices

1. **Principle of Least Privilege** - Assign minimum permissions needed
2. **Regular Audits** - Review role assignments periodically  
3. **Clear Naming** - Use descriptive permission and role names
4. **Documentation** - Document custom roles and permissions
5. **Testing** - Test permission checks thoroughly
6. **Backup** - Regular backups of permission configuration

This role-based permission system provides enterprise-level access control while maintaining simplicity for church staff to manage effectively.
