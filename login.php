<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

<div class="card shadow p-4" style="width: 100%; max-width: 400px;">
    <h4 class="text-center mb-4">Acesso ao Sistema</h4>

    <?php if (!empty($_SESSION['erro_login'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['erro_login']; unset($_SESSION['erro_login']); ?></div>
    <?php endif; ?>

    <form method="POST" action="includes/auth.php">
        <div class="mb-3">
            <label>Usuário</label>
            <input type="text" name="usuario" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label>Senha</label>
            <input type="password" name="senha" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>
</div>

</body>
</html>
