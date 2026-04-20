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

## Modules

| Module | Description |
|--------|-------------|
| **Schools** | Multi-tenant school management (Super Admin only) |
| **Users** | User CRUD with role assignment per school |
| **Academic** | School years, grade levels, SHS tracks/strands, CHED programs, subjects, sections |
| **Enrollment** | Student registration (LRN for DepEd, Student ID for CHED), section assignment |
| **Grading** | DepEd components (WW, PT, QA) with transmutation table; CHED GPA system |
| **Attendance** | Daily per-class attendance recording |

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