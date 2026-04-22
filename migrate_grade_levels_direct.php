<?php
// Standalone migration script using CodeIgniter database
define('BASEPATH', TRUE);
require_once('application/config/database.php');

try {
    // Connect to MySQL using CodeIgniter config
    $host = $db['default']['hostname'];
    $user = $db['default']['username'];
    $pass = $db['default']['password'];
    $dbname = $db['default']['database'];
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully.\n";
    
    // Check if school_id column exists
    $checkColumn = $pdo->query("SHOW COLUMNS FROM grade_levels LIKE 'school_id'");
    if ($checkColumn->rowCount() == 0) {
        echo "Adding school_id column to grade_levels table...\n";
        $pdo->exec("ALTER TABLE `grade_levels` ADD COLUMN `school_id` int(11) UNSIGNED NOT NULL DEFAULT 1 AFTER `id`");
        echo "Column added successfully.\n";
    } else {
        echo "school_id column already exists.\n";
    }
    
    // Update existing records
    echo "Updating existing grade_levels to have school_id = 1...\n";
    $pdo->exec("UPDATE `grade_levels` SET `school_id` = 1 WHERE `school_id` IS NULL OR `school_id` = 0");
    echo "Records updated.\n";
    
    // Add index if it doesn't exist
    $checkIndex = $pdo->query("SHOW INDEX FROM grade_levels WHERE Key_name = 'idx_school_id'");
    if ($checkIndex->rowCount() == 0) {
        echo "Adding index on school_id...\n";
        $pdo->exec("ALTER TABLE `grade_levels` ADD INDEX `idx_school_id` (`school_id`)");
        echo "Index added.\n";
    } else {
        echo "Index already exists.\n";
    }
    
    echo "\nMigration completed successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
