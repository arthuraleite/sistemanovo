<form action="/pedidos/salvar" method="POST">
    <?php if (isset($pedido['id'])): ?>
        <input type="hidden" name="id" value="<?= $pedido['id'] ?>">
    <?php endif; ?>

    <div class="modal-header">
        <h5 class="modal-title"><?= isset($pedido['id']) ? 'Editar Pedido' : 'Novo Pedido' ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body">
        <div class="row mb-3">
            <div class="col-md-8">
                <label>Cliente:</label>
                <select name="cliente_id" class="form-select" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($clientes as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= (isset($pedido['cliente_id']) && $pedido['cliente_id'] == $c['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label>Status:</label>
                <select name="status" class="form-select" required>
                    <?php
                    $status = ['Aberto', 'Em Andamento', 'Pronto', 'Concluído', 'Entregue', 'Liquidado', 'Cancelado'];
                    foreach ($status as $s):
                    ?>
                        <option value="<?= $s ?>" <?= (isset($pedido['status']) && $pedido['status'] == $s) ? 'selected' : '' ?>><?= $s ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Data do Pedido:</label>
                <input type="date" name="data_pedido" class="form-control" required value="<?= $pedido['data_pedido'] ?? date('Y-m-d') ?>">
            </div>
            <div class="col-md-6">
                <label>Previsão de Entrega:</label>
                <input type="date" name="previsao_entrega" class="form-control" required value="<?= $pedido['previsao_entrega'] ?? date('Y-m-d', strtotime('+3 days')) ?>">
            </div>
        </div>

        <hr>
        <h5>Itens do Pedido</h5>
        <table class="table table-sm" id="tabela-itens">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th style="width: 120px;">Valor</th>
                    <th style="width: 100px;">Qtd</th>
                    <th style="width: 120px;">Subtotal</th>
                    <th style="width: 50px;"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($itens)): ?>
                    <?php foreach ($itens as $i => $item): ?>
                        <tr>
                            <td><input type="text" name="descricao[]" class="form-control" required value="<?= $item['descricao'] ?>"></td>
                            <td><input type="number" name="valor_unitario[]" class="form-control valor" step="0.01" required value="<?= $item['valor_unitario'] ?>"></td>
                            <td><input type="number" name="quantidade[]" class="form-control qtd" required value="<?= $item['quantidade'] ?>"></td>
                            <td><input type="number" name="subtotal[]" class="form-control subtotal" step="0.01" readonly value="<?= $item['subtotal'] ?>"></td>
                            <td><button type="button" class="btn btn-sm btn-danger btnRemover">×</button></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <button type="button" class="btn btn-sm btn-secondary" id="btnAdicionarItem">+ Adicionar Item</button>

        <div class="mt-3 text-end">
            <strong>Total: R$ <span id="totalPedido">0,00</span></strong>
        </div>

        <label class="mt-3">Observações:</label>
        <textarea name="observacao" class="form-control"><?= $pedido['observacao'] ?? '' ?></textarea>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Salvar Pedido</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
    </div>
</form>

<script>
function atualizarSubtotal(tr) {
    const valor = parseFloat(tr.querySelector('.valor').value) || 0;
    const qtd = parseFloat(tr.querySelector('.qtd').value) || 0;
    const subtotal = valor * qtd;
    tr.querySelector('.subtotal').value = subtotal.toFixed(2);
}

function atualizarTotal() {
    let total = 0;
    document.querySelectorAll('.subtotal').forEach(input => {
        total += parseFloat(input.value) || 0;
    });
    document.getElementById('totalPedido').innerText = total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('btnAdicionarItem').addEventListener('click', () => {
        const row = `
            <tr>
                <td><input type="text" name="descricao[]" class="form-control" required></td>
                <td><input type="number" name="valor_unitario[]" class="form-control valor" step="0.01" required></td>
                <td><input type="number" name="quantidade[]" class="form-control qtd" required></td>
                <td><input type="number" name="subtotal[]" class="form-control subtotal" step="0.01" readonly></td>
                <td><button type="button" class="btn btn-sm btn-danger btnRemover">×</button></td>
            </tr>`;
        document.querySelector('#tabela-itens tbody').insertAdjacentHTML('beforeend', row);
    });

    document.addEventListener('input', e => {
        if (e.target.classList.contains('valor') || e.target.classList.contains('qtd')) {
            const tr = e.target.closest('tr');
            atualizarSubtotal(tr);
            atualizarTotal();
        }
    });

    document.addEventListener('click', e => {
        if (e.target.classList.contains('btnRemover')) {
            e.target.closest('tr').remove();
            atualizarTotal();
        }
    });

    document.querySelectorAll('tr').forEach(tr => atualizarSubtotal(tr));
    atualizarTotal();
});
</script>
