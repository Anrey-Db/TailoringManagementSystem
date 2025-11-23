# Authentication System Setup

## Overview
The Tailoring Management System now includes a complete user authentication system with login, registration, and logout functionality.

## Features Implemented

### 1. **Authentication Controllers**
- **LoginController** - Handles user login and logout
- **RegisterController** - Handles user registration

### 2. **Authentication Views**
- **Login Page** (`resources/views/auth/login.blade.php`)
  - Email and password input fields
  - "Remember me" checkbox
  - Link to registration page
  - Modern gradient UI design
  - Error message display

- **Register Page** (`resources/views/auth/register.blade.php`)
  - Full name, email, and password fields
  - Password confirmation field
  - Password strength requirements
  - Link to login page
  - Modern gradient UI design
  - Error message display

### 3. **Routes**
All routes have been updated in `routes/web.php`:
- **Public Routes (Guest Only)**
  - `GET /login` - Show login form
  - `POST /login` - Process login
  - `GET /register` - Show registration form
  - `POST /register` - Process registration

- **Protected Routes (Requires Authentication)**
  - All dashboard, customer, measurement, order, and payment routes
  - `POST /logout` - Logout user

### 4. **Navigation Bar Updates**
The main layout (`resources/views/layouts/app.blade.php`) now includes:
- User profile dropdown in the navbar
- Display of current user's name
- Logout button in the dropdown

## Test Credentials

Two test users have been created:

| Email | Password | Name |
|-------|----------|------|
| admin@tailoring.local | password123 | Admin User |
| test@tailoring.local | password123 | Test User |

## How to Use

### User Login
1. Navigate to `http://localhost:8000/login`
2. Enter email and password
3. Optionally check "Remember me"
4. Click "Login"
5. You'll be redirected to the dashboard

### User Registration
1. Navigate to `http://localhost:8000/register`
2. Enter full name, email, and password
3. Confirm your password
4. Click "Create Account"
5. You'll be automatically logged in and redirected to the dashboard

### User Logout
1. Click on your name in the top-right corner of the navbar
2. Click "Logout"
3. You'll be redirected to the login page

## Security Features

- ✅ Password hashing using bcrypt
- ✅ CSRF protection on all forms
- ✅ Session management
- ✅ Email uniqueness validation on registration
- ✅ Guest middleware prevents logged-in users from accessing login/register
- ✅ Auth middleware protects all application routes
- ✅ Password minimum length: 8 characters
- ✅ Session regeneration on login/logout
- ✅ Remember token for persistent sessions

## Database
The authentication system uses the `users` table which includes:
- `id` - Primary key
- `name` - User's full name
- `email` - User's email (unique)
- `email_verified_at` - Email verification timestamp (nullable)
- `password` - Hashed password
- `remember_token` - Token for persistent sessions
- `created_at` - Account creation timestamp
- `updated_at` - Last update timestamp

## Configuration
Authentication is configured in:
- `config/auth.php` - Authentication configuration
- `app/Models/User.php` - User model with authentication traits
- `.env` - Environment variables for app configuration

## Next Steps (Optional Enhancements)
- Email verification
- Password reset functionality
- Two-factor authentication
- Role-based access control (admin, staff, customer)
- User profile management
- Activity logging

## Troubleshooting

**Cannot access the application?**
- Ensure you're not logged in. If logged in, the public routes redirect to dashboard.
- Clear your browser cookies if having session issues.

**Wrong password error?**
- Ensure caps lock is off
- Check that you're using the correct email

**Cannot create an account?**
- Email must be unique
- Password must be at least 8 characters
- Password confirmation must match the password field
