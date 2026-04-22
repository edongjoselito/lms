<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'lms_db';

// Connect to database
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "=== USER INFO ===\n";
$user_email = 'edgardo.amigo@lms.com';
$result = $conn->query("SELECT id, email, school_id, role_id FROM users WHERE email = '$user_email'");
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo "User ID: " . $user['id'] . "\n";
    echo "Email: " . $user['email'] . "\n";
    echo "School ID: " . $user['school_id'] . "\n";
    echo "Role ID: " . $user['role_id'] . "\n";
} else {
    echo "User not found\n";
}

echo "\n=== STUDENT INFO ===\n";
if (isset($user)) {
    $user_id = $user['id'];
    $result = $conn->query("SELECT id, user_id, school_id FROM students WHERE user_id = '$user_id'");
    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        echo "Student ID: " . $student['id'] . "\n";
        echo "User ID: " . $student['user_id'] . "\n";
        echo "School ID: " . $student['school_id'] . "\n";
    } else {
        echo "Student not found\n";
    }
}

echo "\n=== SUBJECTS INFO ===\n";
if (isset($student)) {
    $student_school_id = $student['school_id'];
    echo "Student's School ID: $student_school_id\n\n";
    
    $result = $conn->query("SELECT id, code, name, school_id FROM subjects LIMIT 10");
    if ($result->num_rows > 0) {
        echo "First 10 subjects:\n";
        while ($row = $result->fetch_assoc()) {
            echo "ID: " . $row['id'] . ", Code: " . $row['code'] . ", Name: " . $row['name'] . ", School ID: " . $row['school_id'] . "\n";
        }
    } else {
        echo "No subjects found\n";
    }
    
    echo "\nSubjects matching student's school_id ($student_school_id):\n";
    $result = $conn->query("SELECT id, code, name, school_id FROM subjects WHERE school_id = '$student_school_id'");
    if ($result->num_rows > 0) {
        echo "Count: " . $result->num_rows . "\n";
        while ($row = $result->fetch_assoc()) {
            echo "ID: " . $row['id'] . ", Code: " . $row['code'] . ", Name: " . $row['name'] . "\n";
        }
    } else {
        echo "No subjects found with this school_id\n";
    }
    
    echo "\nSubjects with NULL school_id:\n";
    $result = $conn->query("SELECT id, code, name, school_id FROM subjects WHERE school_id IS NULL");
    if ($result->num_rows > 0) {
        echo "Count: " . $result->num_rows . "\n";
        while ($row = $result->fetch_assoc()) {
            echo "ID: " . $row['id'] . ", Code: " . $row['code'] . ", Name: " . $row['name'] . "\n";
        }
    } else {
        echo "No subjects with NULL school_id\n";
    }
}

$conn->close();
