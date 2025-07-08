<?php
require_once '../includes/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$id = $_GET['id'] ?? null;
$produto = [
    'id' => '',
    'descricao' => '',
    'valor' => ''
];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Formata valor somente se for numérico
$valorFormatado = is_numeric($produto['valor']) ? number_format($produto['valor'], 2, ',', '') : '';
?>

<div class="modal-header">
    <h5 class="modal-title"><?= $id ? 'Editar Produto' : 'Cadastrar Produto' ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form method="POST" action="salvar.php">
    <div class="modal-body">
        <input type="hidden" name="id" value="<?= $produto['id'] ?>">

        <div class="mb-3">
            <label>Descrição</label>
            <input type="text" name="descricao" class="form-control" required
                   value="<?= htmlspecialchars($produto['descricao']) ?>">
        </div>

        <div class="mb-3">
            <label>Valor (R$)</label>
            <input type="text" name="valor" id="valor" class="form-control" required
                   value="<?= $valorFormatado ?>">
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Salvar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
    </div>
</form>

<!-- Máscara de valor -->
<script>
$(function () {
    $('#valor').mask('000.000.000,00', {reverse: true});
});
</script>
