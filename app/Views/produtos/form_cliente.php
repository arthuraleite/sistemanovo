<form action="/produtos/salvar" method="POST">
    <?php if (isset($produto['id'])): ?>
        <input type="hidden" name="id" value="<?= $produto['id'] ?>">
    <?php endif; ?>

    <div class="modal-header">
        <h5 class="modal-title"><?= isset($produto['id']) ? 'Editar Produto' : 'Novo Produto' ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body">
        <label>Descrição:</label>
        <input type="text" name="descricao" class="form-control" required value="<?= $produto['descricao'] ?? '' ?>">

        <label>Valor Unitário:</label>
        <input type="number" step="0.01" name="valor" class="form-control" required value="<?= $produto['valor'] ?? '' ?>">
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Salvar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
    </div>
</form>
