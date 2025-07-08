<?php
require_once 'db.php'; // Conexão com o banco

// Buscar todas as formas de pagamento cadastradas
function buscarFormasPagamento() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM formas_pagamento ORDER BY forma ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Buscar todos os clientes
function buscarClientes() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, nome FROM clientes ORDER BY nome ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Buscar todos os produtos
function buscarProdutos() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, descricao, valor FROM produtos ORDER BY descricao ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Formatar valor monetário (R$ 1.234,56)
function formatarValor($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

// Função para limpar máscara de moeda (ex: "1.234,56" => 1234.56)
function valorParaDecimal($valor) {
    $valor = str_replace('.', '', $valor);
    $valor = str_replace(',', '.', $valor);
    return floatval($valor);
}
