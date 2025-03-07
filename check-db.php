<?php
try {
    $pdo = new PDO("mysql:host=" . getenv('DB_HOST') . ";port=" . getenv('DB_PORT') . ";dbname=" . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
    echo "Database connection successful\n";
} catch (PDOException $e) {
    file_put_contents('/var/www/html/storage/logs/db-error.log', 'Connection failed: ' . $e->getMessage() . "\n", FILE_APPEND);
    exit(1);
}