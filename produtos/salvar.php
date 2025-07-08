<?php
require_once '../includes/db.php';

$id = $_POST['id'] ?? null;
$descricao = trim($_POST['descricao'] ?? '');
$valorBr = $_POST['valor'] ?? '0,00';

// Converte valor de moeda brasileiro para formato SQL (ex: R$ 1.200,50 → 1200.50)
$valor = str_replace(['.', ','], ['', '.'], $valorBr);

if ($id) {
    // Atualizar
    $sql = "UPDATE produtos SET descricao = :descricao, valor = :valor WHERE id = :id";
    $params = [
        'id' => $id,
        'descricao' => $descricao,
        'valor' => $valor
    ];
} else {
    // Inserir
    $sql = "INSERT INTO produtos (descricao, valor) VALUES (:descricao, :valor)";
    $params = [
        'descricao' => $descricao,
        'valor' => $valor
    ];
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

// Redireciona para recarregar listagem
header("Location: index.php");
exit;
