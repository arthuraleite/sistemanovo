<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Core/Helpers.php';

use App\Controllers\{ClientesController, ProdutosController, PedidosController, UsuariosController, OrcamentosController, FinanceiroController};

session_start();

$urlPublicas = ['login', 'autenticar'];

$rota = $_GET['url'] ?? '';
$rotaAtual = explode('/', $rota)[0];

if (!in_array($rotaAtual, $urlPublicas) && empty($_SESSION['usuario'])) {
    header('Location: /login');
    exit;
}

// Roteamento básico
$url = $_GET['url'] ?? '';
$partes = explode('/', trim($url, '/'));

$controlador = ucfirst($partes[0] ?? 'clientes') . 'Controller';
$metodo = $partes[1] ?? 'index';
$param = $partes[2] ?? null;

$classe = "App\\Controllers\\$controlador";
if (class_exists($classe) && method_exists($classe, $metodo)) {
    $instancia = new $classe;
    $instancia->$metodo($param);
} else {
    http_response_code(404);
    echo "Página não encontrada";
}
