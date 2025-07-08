<?php
session_start();
require_once 'db.php';

$usuario = trim($_POST['usuario'] ?? '');
$senha = trim($_POST['senha'] ?? '');

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1");
$stmt->bindParam(':usuario', $usuario);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($senha, $user['senha'])) {
    $_SESSION['usuario'] = [
        'id' => $user['id'],
        'nome' => $user['nome'],
        'usuario' => $user['usuario'],
        'tipo' => $user['tipo']
    ];
    header("Location: ../dashboard.php");
    exit;
}
else
{
    $_SESSION['erro_login'] = 'Usuário ou senha inválidos.';
    header("Location: ../login.php");
    exit;

}