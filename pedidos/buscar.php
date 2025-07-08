<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../includes/db.php';

header('Content-Type: application/json');

$sql = "SELECT pedidos.*, clientes.nome AS cliente_nome FROM pedidos 
        JOIN clientes ON pedidos.cliente_id = clientes.id";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

$resultado = [];

foreach ($dados as $linha) {
    $resultado[] = [
        'id' => $linha['id'],
        'cliente' => $linha['cliente_nome'],
        'status' => $linha['status'],
        'valor_total' => number_format($linha['valor_total'], 2, ',', '.'),
        'acoes' => '' // preenchido no JS
    ];
}

echo json_encode(['data' => $resultado]);
