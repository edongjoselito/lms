# LMS - Learning Management System

A multi-tenant Learning Management System supporting **DepEd (K-12)** and **CHED (Higher Education)** academic structures. Built with CodeIgniter 3, Bootstrap 5, and MySQL.

---

## Requirements

- XAMPP (Apache + MySQL + PHP 8.x)
- PHP 8.0 or higher
- MySQL 5.7+ / MariaDB 10.3+

## Installation

1. Clone or copy this project to your XAMPP htdocs folder:
   ```
   /Applications/XAMPP/xamppfiles/htdocs/lms/
   ```

2. Start Apache and MySQL from the XAMPP control panel.

3. Import the database:
   - Open phpMyAdmin at `http://localhost/phpmyadmin`
   - Go to the **Import** tab and select `sql/install.sql`
   - Or run from terminal:
     ```bash
     /Applications/XAMPP/xamppfiles/bin/mysql -u root -h 127.0.0.1 < sql/install.sql
     ```

4. Open the application at: **http://localhost/lms/**

---

## Default Login Credentials

All default accounts use the same password: **`password`**

| Role | Email | Password | School | Access Level |
|------|-------|----------|--------|--------------|
| **Super Admin** | `admin@lms.com` | `password` | Platform (all schools) | Full platform management, school CRUD, switch between schools |
| **School Admin** | `admin@school.com` | `password` | Default School | School-level admin: users, academic, enrollment, grades |
| **Registrar** | `registrar@school.com` | `password` | Default School | Enrollment, student records, sections |
| **Teacher** | `teacher@school.com` | `password` | Default School | Class records, grade encoding, attendance |
| **Student** | `student@school.com` | `password` | Default School | View grades, attendance, class schedule |
| **Parent** | `parent@school.com` | `password` | Default School | View child's grades and attendance |

### Login Flow

- **Super Admin** logs in → redirected to **School Selector** → pick a school → School Dashboard
- **School Admin / Registrar / Teacher** logs in → goes directly to school **Dashboard**
- **Student / Parent** logs in → goes to their respective dashboard

---

## Multi-Tenant Architecture

Each school operates in isolation. Data is scoped by `school_id`:

- **Super Admin** manages all schools from a platform-level view
- **School-level users** (admin, registrar, teacher, student, parent) only see data for their school
- Super Admin can **switch between schools** using the sidebar or topbar

---

## Key Features & Enhancements

### Course-Based Learning System
- **Course Structure**: Courses contain modules with lessons and quizzes
- **Sequential Access**: Students must complete lessons in order (configurable)
- **Progress Tracking**: Real-time progress bars for course completion
- **Student Progress View**: Teachers can monitor individual student progress

### Enrollment Management
- **Enrollment Keys**: Teachers set mandatory keys for course enrollment to prevent unauthorized access
- **Student Unenrollment**: Students can unenroll from mistakenly joined courses
- **Bulk CSV Import**: Download CSV template and bulk enroll students by email (auto-creates new students if not found)
- **Manual Enrollment**: Teachers can manually enroll students from available list

### Automatic Attendance Tracking
- **Login/Logout Based**: Attendance is automatically recorded when students log in/out
- **Duration Calculation**: System calculates total time spent in LMS per session
- **Course-Specific Views**: Teachers can view attendance filtered by course
- **Student History**: Individual student attendance history with login/logout times

### User Management
- **Profile Management**: All users can view and edit their profile (name, email)
- **Password Change**: Secure password change with current password verification
- **Student Mode**: Teachers can switch to student mode to test the interface and verify access restrictions

### Security & Access
- **Student Auto-Logout**: Automatic logout after 5 minutes of inactivity for students
- **Role-Based Access**: Different interfaces and permissions for each role
- **School Isolation**: Multi-tenant architecture with school-level data isolation

### System Configuration
- **Manila Timezone**: All date/time functions use Asia/Manila timezone (PST)
- **Responsive Design**: Full-width forms and modern UI for better usability

---

## Modules

| Module | Description |
|--------|-------------|
| **Schools** | Multi-tenant school management (Super Admin only) |
| **Users** | User CRUD with role assignment per school, profile management, password change |
| **Courses** | Course-based learning management with modules, lessons, quizzes, enrollment keys |
| **Academic** | School years, grade levels, SHS tracks/strands, CHED programs, subjects, sections |
| **Enrollment** | Student registration (LRN for DepEd, Student ID for CHED), section assignment, course enrollment with key validation, bulk CSV import |
| **Grading** | DepEd components (WW, PT, QA) with transmutation table; CHED GPA system |
| **Attendance** | Automatic login/logout tracking with duration calculation, course-specific attendance views |
| **Student Mode** | Teachers can switch to student mode to test the interface and access restrictions |

---

## Tech Stack

- **Backend:** PHP 8.x, CodeIgniter 3
- **Database:** MySQL / MariaDB
- **Frontend:** Bootstrap 5, Bootstrap Icons
- **Server:** XAMPP (Apache)

---

## Database

- Schema file: `sql/install.sql`
- Multi-tenant migration: `sql/migrate_multitenant.sql`
- Database name: `lms_db`
- Default DB config: `root` user, no password, host `127.0.0.1`