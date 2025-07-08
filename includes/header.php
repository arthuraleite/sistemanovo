<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/config.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= BASE_URL ?>/dashboard.php">Sistema</a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/dashboard.php">Dashboard</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Cadastros
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/clientes/index.php">Clientes</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/produtos/index.php">Produtos</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/orcamentos/index.php">Orçamentos</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/pedidos/index.php">Pedidos</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/financeiro/index.php">Financeiro</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/configuracoes/index.php">Configurações</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="nav-link">
                        Olá, <?= htmlspecialchars($_SESSION['usuario']['nome']); ?>!
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/logout.php">Sair</a>
                </li>
            </ul>


        </div>
    </div>
</nav>
