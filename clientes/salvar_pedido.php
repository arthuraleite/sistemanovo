<?php
require_once '../includes/db.php';
require_once '../includes/funcoes.php';

// Configura resposta como JSON
header('Content-Type: application/json');

try {
    // Coletar e validar dados
    $nome = trim($_POST['nome'] ?? '');
    $cpf_cnpj = preg_replace('/\D/', '', $_POST['cpf_cnpj'] ?? '');
    
    if (empty($nome) {
        throw new Exception('Nome é obrigatório');
    }
    
    if (empty($cpf_cnpj) {
        throw new Exception('CPF/CNPJ é obrigatório');
    }

    // Verificar se já existe
    $stmt = $pdo->prepare("SELECT id FROM clientes WHERE cpf_cnpj = ?");
    $stmt->execute([$cpf_cnpj]);
    
    if ($stmt->fetch()) {
        throw new Exception('CPF/CNPJ já cadastrado');
    }

    // Determinar tipo (F ou J)
    $tipo = strlen($cpf_cnpj) === 11 ? 'F' : 'J';

    // Inserir cliente
    $stmt = $pdo->prepare("INSERT INTO clientes (
        nome, cpf_cnpj, tipo, telefone, email, cep, logradouro, numero,
        bairro, cidade, inscricao_municipal, inscricao_estadual,
        responsavel, observacoes
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->execute([
        $nome,
        $cpf_cnpj,
        $tipo,
        $_POST['telefone'] ?? '',
        $_POST['email'] ?? '',
        $_POST['cep'] ?? '',
        $_POST['logradouro'] ?? '',
        $_POST['numero'] ?? '',
        $_POST['bairro'] ?? '',
        $_POST['cidade'] ?? '',
        $_POST['inscricao_municipal'] ?? '',
        $_POST['inscricao_estadual'] ?? '',
        $_POST['responsavel'] ?? '',
        $_POST['observacoes'] ?? ''
    ]);

    $clienteId = $pdo->lastInsertId();

    // Retorna sucesso com dados do cliente
    echo json_encode([
        'success' => true,
        'cliente' => [
            'id' => $clienteId,
            'nome' => $nome,
            'cpf_cnpj' => $cpf_cnpj
        ]
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}