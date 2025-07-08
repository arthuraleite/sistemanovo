<?php
require_once '../includes/db.php';

$stmt = $pdo->query("SELECT id, descricao, valor FROM produtos ORDER BY descricao");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['data' => $produtos]);
