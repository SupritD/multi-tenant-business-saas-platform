# Multi-Tenant Business SaaS Platform - Project Overview

This document provides a comprehensive overview of the core files and their usage in the Multi-Tenant Business SaaS Platform project. The architecture is built on Laravel and heavily focuses on multi-tenancy, subscription management, and fine-grained access control using roles and permissions.

---

## 1. Core Models (`app/Models/`)
Models represent the data structures and relationships within the application.

*   **`Tenant.php`**: Represents a single organization or business (the "tenant") using the SaaS platform. It acts as the core entity to which users, subscriptions, and customized data belong.
*   **`User.php`**: Represents an individual who can log in. Users belong to a specific tenant (or can be system administrators).
*   **`Role.php`**: Defines a set of responsibilities (e.g., Admin, Manager, Employee) that can be assigned to a user.
*   **`Permission.php`**: Represents a specific granular action a user is allowed to perform (e.g., `create_invoice`, `delete_user`). Permissions are attached to Roles.
*   **`UserRole.php`**: A pivot model that manages the many-to-many relationship mapping between Users and Roles.
*   **`Plan.php`**: Represents a SaaS subscription tier (e.g., Basic, Pro, Enterprise). It defines the pricing, billing cycle, and limits.
*   **`Feature.php`**: Represents a specific capability provided by the platform (e.g., "Advanced Reporting", "API Access"). Features are linked to Plans to determine what a subscribed tenant can access.
*   **`Subscription.php`**: Links a Tenant to a specific Plan, tracking their active subscription status, start/end dates, and billing information.

---

## 2. Business Logic Services (`app/Services/`)
Services encapsulate complex business rules and operations, keeping controllers and models clean.

*   **`AccessService.php`**: Centralizes the logic to determine if a user or tenant has the right to access a specific resource or perform an action based on their roles, permissions, and active plan features.
*   **`RoleAssignmentService.php`**: Handles the logic for assigning roles to users, ensuring that only valid roles (e.g., within the same tenant context) are attached.
*   **`SubscriptionService.php`**: Manages the lifecycle of a tenant's subscription. This includes creating new subscriptions, handling upgrades/downgrades between plans, and managing cancellations or renewals.

---

## 3. Middleware (`app/Http/Middleware/`)
Middleware intercepts incoming HTTP requests to enforce security and access control policies before they reach the controllers.

*   **`EnsureTenantAccess.php`**: Verifies that the current user belongs to the tenant context they are trying to access, preventing cross-tenant data leaks. (Registered as `tenant`).
*   **`EnsurePermission.php`**: Checks if the authenticated user has the specific permission required to access a route or perform an action. (Registered as `permission`).
*   **`EnsureFeatureAccess.php`**: Verifies that the tenant's current subscription plan includes the specific feature required by the route they are trying to access. (Registered as `feature`).
*   **`EnsureAccess.php`**: A potentially composite middleware that might combine multiple checks (like tenant and permission) for streamlined route protection. (Registered as `access`).

*Note: These middleware are registered with their respective aliases in `bootstrap/app.php`.*

---

## 4. Database Schema Migrations (`database/migrations/`)
Migrations define the structure of the database tables. Key migrations include:

*   `..._create_tenants_table.php`, `..._create_users_table.php`
*   `..._create_roles_table.php`, `..._create_permissions_table.php`, `..._create_user_roles_table.php`, `..._create_role_permissions_table.php` (RBAC system)
*   `..._create_plans_table.php`, `..._create_features_table.php`, `..._create_plan_features_table.php` (SaaS Packaging)
*   `..._create_tenant_features_table.php`, `..._create_subscriptions_table.php` (Tenant Billing & Access)

---

## 5. Database Seeders (`database/seeders/`)
Seeders are used to populate the database with essential initial data or dummy data for testing.

*   **`DatabaseSeeder.php`**: The main entry point that calls all other seeders in the correct order.
*   **`PlanSeeder.php` & `FeatureSeeder.php`**: Creates the default subscription tiers (e.g., Free, Pro) and the available platform features.
*   **`PlanFeatureSeeder.php`**: Maps which features belong to which plans.
*   **`RoleSeeder.php` & `PermissionSeeder.php`**: Defines the system's default roles (e.g., Super Admin, Tenant Owner) and all available granular permissions.
*   **`RolePermissionSeeder.php`**: Assigns the default permissions to the default roles.
*   **`TenantSeeder.php` & `UserSeeder.php`**: Creates initial test tenants and admin users.
*   **`SubscriptionSeeder.php`**: Creates sample subscriptions for the seeded tenants to facilitate testing.

---

## 6. Application Configuration (`bootstrap/app.php`)
The `bootstrap/app.php` file initializes the Laravel application and configures its core components. In this project, it has been customized to register the custom middleware (`tenant`, `permission`, `feature`, `access`) so they can be easily applied to routes in `routes/web.php`.
