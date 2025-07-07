<form action="/financeiro/salvar-recorrente" method="POST">
    <?php if (isset($recorrente['id'])): ?>
        <input type="hidden" name="id" value="<?= $recorrente['id'] ?>">
    <?php endif; ?>

    <div class="modal-header">
        <h5 class="modal-title"><?= isset($recorrente) ? 'Editar Conta Recorrente' : 'Nova Conta Recorrente' ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body">
        <div class="mb-3">
            <label>Descrição:</label>
            <input type="text" name="descricao" class="form-control" value="<?= $recorrente['descricao'] ?? '' ?>" required>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Tipo:</label>
                <select name="tipo" class="form-select" required>
                    <option value="saida" <?= (isset($recorrente['tipo']) && $recorrente['tipo'] == 'saida') ? 'selected' : '' ?>>Saída</option>
                    <option value="entrada" <?= (isset($recorrente['tipo']) && $recorrente['tipo'] == 'entrada') ? 'selected' : '' ?>>Entrada</option>
                </select>
            </div>
            <div class="col-md-6">
                <label>Valor (previsto):</label>
                <input type="number" name="valor" step="0.01" class="form-control" value="<?= $recorrente['valor'] ?? '' ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Próximo Vencimento:</label>
                <input type="date" name="data_vencimento" class="form-control" value="<?= $recorrente['data_vencimento'] ?? date('Y-m-d') ?>" required>
            </div>
            <div class="col-md-6">
                <label>Frequência:</label>
                <select name="frequencia" class="form-select" required>
                    <?php
                    $opcoes = ['mensal', 'semanal', 'quinzenal', 'anual'];
                    foreach ($opcoes as $freq):
                        $sel = (isset($recorrente['frequencia']) && $recorrente['frequencia'] === $freq) ? 'selected' : '';
                        echo "<option value=\"$freq\" $sel>" . ucfirst($freq) . "</option>";
                    endforeach;
                    ?>
                </select>
            </div>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="ativo" value="1"
                <?= !isset($recorrente['ativo']) || $recorrente['ativo'] ? 'checked' : '' ?>>
            <label class="form-check-label">Ativo</label>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Salvar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
    </div>
</form>
