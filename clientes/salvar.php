<?php
session_start();
require_once '../includes/db.php';

// Coletar dados
$id = $_POST['id'] ?? null;
$nome = trim($_POST['nome'] ?? '');
$cpf_cnpj = preg_replace('/\D/', '', $_POST['cpf_cnpj'] ?? '');
$telefone = $_POST['telefone'] ?? '';
$email = $_POST['email'] ?? '';
$cep = $_POST['cep'] ?? '';
$logradouro = $_POST['logradouro'] ?? '';
$numero = $_POST['numero'] ?? '';
$bairro = $_POST['bairro'] ?? '';
$cidade = $_POST['cidade'] ?? '';
$insc_mun = $_POST['inscricao_municipal'] ?? '';
$insc_est = $_POST['inscricao_estadual'] ?? '';
$responsavel = $_POST['responsavel'] ?? '';
$observacoes = $_POST['observacoes'] ?? '';

// Detectar tipo (F ou J)
$tipo = strlen($cpf_cnpj) === 11 ? 'F' : 'J';

// Verificar duplicidade CPF/CNPJ
if (!$id) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM clientes WHERE cpf_cnpj = ?");
    $stmt->execute([$cpf_cnpj]);
    if ($stmt->fetchColumn() > 0) {
        echo "<script>alert('CPF/CNPJ já cadastrado.'); history.back();</script>";
        exit;
    }
} else {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM clientes WHERE cpf_cnpj = ? AND id != ?");
    $stmt->execute([$cpf_cnpj, $id]);
    if ($stmt->fetchColumn() > 0) {
        echo "<script>alert('CPF/CNPJ já está em uso por outro cliente.'); history.back();</script>";
        exit;
    }
}

if ($id) {
    // Atualizar
    $sql = "UPDATE clientes SET 
        nome = :nome, cpf_cnpj = :cpf_cnpj, tipo = :tipo,
        telefone = :telefone, email = :email,
        cep = :cep, logradouro = :logradouro, numero = :numero,
        bairro = :bairro, cidade = :cidade,
        inscricao_municipal = :insc_mun, inscricao_estadual = :insc_est,
        responsavel = :responsavel, observacoes = :observacoes
        WHERE id = :id";
} else {
    // Inserir
    $sql = "INSERT INTO clientes (
        nome, cpf_cnpj, tipo, telefone, email, cep, logradouro, numero,
        bairro, cidade, inscricao_municipal, inscricao_estadual,
        responsavel, observacoes
    ) VALUES (
        :nome, :cpf_cnpj, :tipo, :telefone, :email, :cep, :logradouro, :numero,
        :bairro, :cidade, :insc_mun, :insc_est, :responsavel, :observacoes
    )";
}

$params = [
    'nome' => $nome,
    'cpf_cnpj' => $cpf_cnpj,
    'tipo' => $tipo,
    'telefone' => $telefone,
    'email' => $email,
    'cep' => $cep,
    'logradouro' => $logradouro,
    'numero' => $numero,
    'bairro' => $bairro,
    'cidade' => $cidade,
    'insc_mun' => $insc_mun,
    'insc_est' => $insc_est,
    'responsavel' => $responsavel,
    'observacoes' => $observacoes
];

if ($id) $params['id'] = $id;

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

// Redirecionar (reload da página para atualizar tabela)
header("Location: index.php");
exit;
