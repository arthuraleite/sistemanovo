<?php
require_once '../includes/db.php';

// Recebe o ID via POST
$id = $_POST['id'] ?? null;

header('Content-Type: application/json');

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

// Verificar se o cliente existe
$stmt = $pdo->prepare("SELECT id FROM clientes WHERE id = ?");
$stmt->execute([$id]);

if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Cliente não encontrado']);
    exit;
}

// Excluir
$stmt = $pdo->prepare("DELETE FROM clientes WHERE id = ?");
if ($stmt->execute([$id])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao excluir']);
}
