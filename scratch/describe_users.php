<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=db_kontendigital', 'root', '');
    $table = 'users';
    echo "Table: $table\n";
    $stmt = $pdo->query("DESCRIBE $table");
    foreach($stmt as $row) {
        echo "  {$row['Field']} - {$row['Type']}\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
