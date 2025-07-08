<div class="mb-3">
    <label>Adicionar Produtos</label>
    <div class="input-group">
        <select class="form-select" id="selectProduto">
            <option value="">Selecione um produto</option>
            <option value="novo" data-nome="" data-valor="">Não Cadastrado</option>
            <?php foreach ($produtos as $p): ?>
                <option value="<?= $p['id'] ?>" data-nome="<?= htmlspecialchars($p['descricao']) ?>" data-valor="<?= number_format($p['valor'], 2, ',', '') ?>">
                    <?= htmlspecialchars($p['descricao']) ?> - R$ <?= number_format($p['valor'], 2, ',', '') ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button class="btn btn-outline-primary" type="button" id="btnAdicionarProduto">Adicionar</button>
    </div>
</div>

<table class="table table-sm align-middle" id="tabela-produtos-pedido">
    <thead>
        <tr>
            <th>Produto</th>
            <th>Valor Unitário</th>
            <th>Qtde</th>
            <th>Subtotal</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <!-- Linhas adicionadas via JS -->
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="text-end"><strong>Total do Pedido:</strong></td>
            <td><input type="text" readonly class="form-control-plaintext" id="totalPedido" name="totalPedido" value="0,00"></td>
            <td></td>
        </tr>
    </tfoot>
</table>
