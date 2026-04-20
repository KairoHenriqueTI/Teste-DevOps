<?php
$host = getenv('DB_HOST') ?: 'db';
$db   = getenv('DB_DATABASE') ?: 'testdb';
$user = getenv('DB_USER') ?: 'appuser';
$pass = getenv('DB_PASSWORD') ?: 'apppass';

try {
	$dsn = "mysql:host={$host};dbname={$db};charset=utf8mb4";
	$pdo = new PDO($dsn, $user, $pass, [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	]);

	$stmt = $pdo->query('SELECT message FROM greetings LIMIT 1');
	$row = $stmt->fetch();
	if ($row && isset($row['message'])) {
		echo htmlspecialchars($row['message'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
	} else {
		echo "Olá, mundo! teste else";
	}
} catch (Exception $e) {
	echo "Olá, mundo! exception";
}
