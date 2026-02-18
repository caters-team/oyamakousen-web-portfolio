<?php
require_once __DIR__ . '/../config/database.php';

// IDの取得
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header('Location: index.php');
    exit;
}

$pdo = getDbConnection();

try {
    $stmt = $pdo->prepare('DELETE FROM news WHERE id = ?');
    $stmt->execute([$id]);
    
    header('Location: index.php?deleted=1');
    exit;
} catch (PDOException $e) {
    error_log('Delete error: ' . $e->getMessage());
    header('Location: index.php?error=1');
    exit;
}