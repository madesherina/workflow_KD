<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=db_kontendigital', 'root', '');
    $stmt = $pdo->query('SELECT migration FROM migrations ORDER BY batch DESC, migration DESC LIMIT 10');
    foreach($stmt as $row) {
        echo $row[0] . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
