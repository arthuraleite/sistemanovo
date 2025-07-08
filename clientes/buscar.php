<?php
require_once '../includes/db.php';

$stmt = $pdo->query("SELECT id, nome, telefone, email FROM clientes ORDER BY nome");
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// DataTables espera um array JSON com a chave "data"
echo json_encode(['data' => $clientes]);
