<form action="/financeiro/salvar" method="POST">
    <?php if (isset($movimentacao['id'])): ?>
        <input type="hidden" name="id" value="<?= $movimentacao['id'] ?>">
    <?php endif; ?>

    <div class="modal-header">
        <h5 class="modal-title"><?= isset($movimentacao) ? 'Editar Movimentação' : 'Nova Movimentação' ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Tipo:</label>
                <select name="tipo" class="form-select" required>
                    <option value="entrada" <?= (isset($movimentacao) && $movimentacao['tipo'] == 'entrada') ? 'selected' : '' ?>>Entrada</option>
                    <option value="saida" <?= (isset($movimentacao) && $movimentacao['tipo'] == 'saida') ? 'selected' : '' ?>>Saída</option>
                </select>
            </div>
            <div class="col-md-6">
                <label>Data:</label>
                <input type="date" name="data" class="form-control" value="<?= $movimentacao['data'] ?? date('Y-m-d') ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label>Descrição:</label>
            <input type="text" name="descricao" class="form-control" value="<?= $movimentacao['descricao'] ?? '' ?>" required>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Valor:</label>
                <input type="number" name="valor" step="0.01" class="form-control" value="<?= $movimentacao['valor'] ?? '' ?>" required>
            </div>
            <div class="col-md-6">
                <label>Forma de Pagamento:</label>
                <select name="forma_pagamento_id" class="form-select" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($formas as $f): ?>
                        <option value="<?= $f['id'] ?>" <?= (isset($movimentacao) && $movimentacao['forma_pagamento_id'] == $f['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($f['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label>Vincular a Pedido (opcional):</label>
            <select name="pedido_id" class="form-select">
                <option value="">Nenhum</option>
                <?php foreach ($pedidos as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= (isset($movimentacao) && $movimentacao['pedido_id'] == $p['id']) ? 'selected' : '' ?>>
                        #<?= $p['id'] ?> - <?= htmlspecialchars($p['cliente']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Salvar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
    </div>
</form>
