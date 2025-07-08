<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

include 'includes/header.php';
?>

<div class="container mt-4">
    <h2>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario']['nome']) ?>!</h2>
    <p>Escolha uma opção no menu acima para começar.</p>
</div>

<?php include 'includes/footer.php'; ?>
