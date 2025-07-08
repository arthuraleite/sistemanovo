<?php
require_once '../includes/db.php';

header('Content-Type: application/json');

$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

// Verifica se o produto existe
$stmt = $pdo->prepare("SELECT id FROM produtos WHERE id = ?");
$stmt->execute([$id]);

if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Produto não encontrado']);
    exit;
}

// Exclui o produto
$stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
$sucesso = $stmt->execute([$id]);

echo json_encode(['success' => $sucesso]);
