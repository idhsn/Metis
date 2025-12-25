<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/src/Database/Database.php';

$pdo = Database::getConnection();

$stmt = $pdo->query('SELECT * FROM members');
$members = $stmt->fetchAll();
echo "Members: " . count($members) . "\n";
