<!-- a1a2fee2-3cf0-4c45-8ebc-7f02e5c655d5 20fe825a-d764-4870-9e4e-304f79bf0ac3 -->
# Laravel CRM Application Build Plan

## Database Schema & Relationships

### Models to Create

- **Client** - CRM client management
- **Project** - belongs to Client, has many Tasks
- **Task** - belongs to Project (nullable) OR standalone, many-to-many with Users
- **File** - polymorphic attachment (morphTo: Client, Project, Task)
- **Role** - Admin, Manager, User roles
- **Notification** - task assignments, status changes, due date reminders

### Key Relationships

```php
User: belongsToMany(Task), belongsTo(Role), hasMany(Notification)
Client: hasMany(Project), hasMany(Task), morphMany(File)
Project: belongsTo(Client), hasMany(Task), morphMany(File)
Task: belongsTo(Project nullable), belongsToMany(User), morphMany(File)
File: morphTo (Client|Project|Task), belongsTo(User uploader)
Role: hasMany(User)
```

## Implementation Steps

### 1. Database Migrations

Create migrations for:

- `roles` table (id, name, description)
- `clients` table (id, name, email, phone, company, address, status, user_id creator)
- `projects` table (id, client_id, name, description, status, start_date, end_date, budget, user_id creator)
- `tasks` table (id, project_id nullable, title, description, status, priority, due_date, user_id creator)
- `task_user` pivot table (task_id, user_id, assigned_at)
- `files` table (id, fileable_type, fileable_id, user_id, filename, path, size, mime_type)
- `notifications` table (Laravel's default notification table)
- Add `role_id` to users table

### 2. Models & Relationships

- `app/Models/Role.php` - hasMany(User)
- `app/Models/Client.php` - hasMany(Project, Task), morphMany(File), belongsTo(User creator)
- `app/Models/Project.php` - belongsTo(Client), hasMany(Task), morphMany(File), belongsTo(User creator)
- `app/Models/Task.php` - belongsTo(Project nullable), belongsToMany(User assignees), morphMany(File), belongsTo(User creator)
- `app/Models/File.php` - morphTo(fileable), belongsTo(User uploader)
- Update `app/Models/User.php` - belongsTo(Role), belongsToMany(Task), hasMany(Client, Project, Task, File)

### 3. Seeders & Factories

- `RoleSeeder` - seed Admin, Manager, User roles
- `ClientFactory` & `ClientSeeder` - sample clients
- `ProjectFactory` & `ProjectSeeder` - sample projects
- `TaskFactory` & `TaskSeeder` - sample tasks with assignments
- Update `UserSeeder` - assign roles to users

### 4. Middleware & Policies

- `app/Http/Middleware/CheckRole.php` - role-based access control
- `app/Policies/ClientPolicy.php` - Admin/Manager can CRUD, Users view only
- `app/Policies/ProjectPolicy.php` - Admin/Manager can CRUD, Users view assigned
- `app/Policies/TaskPolicy.php` - Creators and assignees can update, Admin/Manager can delete
- `app/Policies/FilePolicy.php` - Uploader and resource owners can manage

### 5. Controllers (Resource Controllers)

- `ClientController` - CRUD operations with file upload support
- `ProjectController` - CRUD with client relationship, file uploads
- `TaskController` - CRUD with project relationship (nullable), user assignments, file uploads
- `FileController` - download, delete file actions
- `DashboardController` - statistics and overview

### 6. Form Requests

- `StoreClientRequest` & `UpdateClientRequest` - validation rules
- `StoreProjectRequest` & `UpdateProjectRequest` - validation with client_id
- `StoreTaskRequest` & `UpdateTaskRequest` - validation with project_id nullable, assigned users
- `FileUploadRequest` - file validation (max size, allowed types)

### 7. Notifications

- `app/Notifications/TaskAssigned.php` - email when task assigned
- `app/Notifications/TaskStatusChanged.php` - email when status changes
- `app/Notifications/TaskDueSoon.php` - email 24h before due date
- Create scheduled command for due date reminders: `app/Console/Commands/SendTaskDueReminders.php`

### 8. Views (Blade Templates)

Leverage existing Breeze layout: `resources/views/layouts/app.blade.php`

Create views:

- `dashboard.blade.php` - update with CRM stats
- `clients/index.blade.php` - list with search/filter
- `clients/create.blade.php` - form with file upload
- `clients/show.blade.php` - details with projects, tasks, files
- `clients/edit.blade.php` - edit form
- `projects/index.blade.php` - list with filters
- `projects/create.blade.php` - form with client select, file upload
- `projects/show.blade.php` - details with tasks, files
- `projects/edit.blade.php` - edit form
- `tasks/index.blade.php` - list with filters (status, priority, assigned user)
- `tasks/create.blade.php` - form with project select (nullable), user multi-select
- `tasks/show.blade.php` - details with assignees, files, comments section
- `tasks/edit.blade.php` - edit form with assignments
- `components/file-upload.blade.php` - reusable file upload component
- `components/file-list.blade.php` - display files with download/delete

### 9. Routes

Update `routes/web.php`:

- Resource routes for clients, projects, tasks
- File upload/download/delete routes
- Dashboard route with stats
- Role-based route protection using middleware

### 10. File Storage Configuration

- Configure `config/filesystems.php` for local/public disk
- Create storage symlink for file access
- Implement file upload/download logic in controllers

### 11. Mail Configuration

- Update `.env` with mail settings
- Create mail templates: `resources/views/emails/task-assigned.blade.php`, etc.
- Configure queue for async email sending

### 12. Frontend Enhancements

- Add Tailwind components for CRM UI (tables, cards, forms)
- Create reusable components for status badges, priority indicators
- Add JavaScript for multi-select user assignments
- File drag-and-drop upload interface

### 13. Testing & Data

- Update `database/seeders/DatabaseSeeder.php` to run all seeders
- Create sample data: 3 roles, 5 users, 10 clients, 20 projects, 50 tasks
- Test all CRUD operations with different roles
- Test file uploads and downloads
- Test email notifications (use Mailtrap/Log driver)

## Key Files to Modify

- `routes/web.php` - add all resource routes
- `app/Models/User.php` - add role relationship and task assignments
- `config/filesystems.php` - ensure public disk configured
- `database/seeders/DatabaseSeeder.php` - orchestrate all seeders
- `app/Console/Kernel.php` - schedule daily task due reminders
- `.env` - configure mail and file storage settings

### To-dos

- [ ] Create all migrations (roles, clients, projects, tasks, task_user pivot, files, update users table)
- [ ] Create and configure all models with proper relationships (Role, Client, Project, Task, File)
- [ ] Create factories and seeders for all models with sample data
- [ ] Implement authorization policies and role-checking middleware
- [ ] Create form request validation classes for all resources
- [ ] Build resource controllers for Client, Project, Task, File with CRUD operations
- [ ] Create notification classes and scheduled command for task reminders
- [ ] Build all Blade views for clients, projects, tasks with file upload components
- [ ] Define all resource routes with proper middleware and route names
- [ ] Configure file storage, implement upload/download logic, create storage symlink
- [ ] Build dashboard with CRM statistics and overview
- [ ] Configure mail settings, create email templates, set up queue for notifications
- [ ] Run seeders, test all CRUD operations, file uploads, and notifications with different roles