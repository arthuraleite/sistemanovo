<div class="mb-3 mt-4">
    <label>Adicionar Movimentação</label>
    <div class="row g-2">
        <div class="col-md-3">
            <input type="text" class="form-control money" id="valorMovimentacao" placeholder="Valor">
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control" id="descricaoMovimentacao" placeholder="Descrição">
        </div>
        <div class="col-md-3">
            <input type="date" class="form-control" id="dataMovimentacao" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-md-2">
            <select id="formaPagamento" class="form-select">
                <option value="">Forma de Pagamento</option>
                <?php foreach ($formasPagamento as $fp): ?>
                    <option value="<?= $fp['id'] ?>"><?= htmlspecialchars($fp['forma']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-outline-primary w-100" id="btnAdicionarMovimentacao">+</button>
        </div>
    </div>
</div>

<table class="table table-sm align-middle" id="tabela-movimentacoes">
    <thead>
        <tr>
            <th>Valor</th>
            <th>Descrição</th>
            <th>Data</th>
            <th>Forma de Pagamento</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <!-- Movimentações adicionadas via JS -->
    </tbody>
</table>
