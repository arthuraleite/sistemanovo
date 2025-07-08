<?php
require_once '../includes/db.php';
require_once '../includes/funcoes.php';

$id = $_GET['id'] ?? null;
$pedido = [
    'id' => '',
    'cliente_id' => '',
    'data_pedido' => date('Y-m-d'),
    'previsao_entrega' => '',
    'status' => 'Em Andamento',
    'pronto_em' => '',
    'entregue_em' => '',
    'liquidado_em' => '',
    'observacoes' => '',
    'valor_total' => 0
];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id = ?");
    $stmt->execute([$id]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
}

$clientes = $pdo->query("SELECT id, nome FROM clientes ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
$produtos = $pdo->query("SELECT * FROM produtos ORDER BY descricao")->fetchAll(PDO::FETCH_ASSOC);
$formas_pgto = $pdo->query("SELECT * FROM formas_pagamento ORDER BY forma")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />

<div class="modal-header">
    <h5 class="modal-title"><?= $id ? 'Editar Pedido' : 'Novo Pedido' ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form action="salvar.php" method="POST" id="formPedido">
    <div class="modal-body">
        <input type="hidden" name="id" value="<?= $pedido['id'] ?>">

        <!-- CLIENTE -->
        <div class="mb-3">
            <label class="form-label">Cliente</label>
            <div class="row g-2">
                <div class="col-auto">
                    <button type="button" class="btn btn-primary" id="btnNovoClientePedido" title="Novo Cliente">
                        <i class="bi bi-plus-circle"></i>
                    </button>
                </div>
                <div class="col">
                    <select name="cliente_id" id="cliente_id" class="form-select select2" required>
                        <option value=""></option>
                        <?php foreach ($clientes as $cli): ?>
                            <option value="<?= $cli['id'] ?>" <?= $cli['id'] == $pedido['cliente_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cli['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- DATAS E STATUS -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Data do Pedido</label>
                <input type="date" name="data_pedido" class="form-control" value="<?= $pedido['data_pedido'] ?>">
            </div>
            <div class="col-md-4">
                <label>Previsão de Entrega</label>
                <input type="date" name="previsao_entrega" class="form-control" value="<?= $pedido['previsao_entrega'] ?>" required> 
            </div>
            <div class="col-md-4">
                <label>Status</label>
                <select name="status" class="form-select">
                    <?php
                    $statusList = ['Em Andamento', 'Pronto', 'Entregue', 'Liquidado', 'Cancelado'];
                    foreach ($statusList as $status) {
                        $selected = $status == $pedido['status'] ? 'selected' : '';
                        echo "<option value=\"$status\" $selected>$status</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <!-- DATES -->
        <div class="row mb-3">
            <div class="col-md-4"><label>Pronto em</label><input type="date" name="pronto_em" class="form-control" value="<?= $pedido['pronto_em'] ?>"></div>
            <div class="col-md-4"><label>Entregue em</label><input type="date" name="entregue_em" class="form-control" value="<?= $pedido['entregue_em'] ?>"></div>
            <div class="col-md-4"><label>Liquidado em</label><input type="date" name="liquidado_em" class="form-control" value="<?= $pedido['liquidado_em'] ?>"></div>
        </div>

        <!-- OBS -->
        <div class="mb-3">
            <label>Observações</label>
            <textarea name="observacoes" class="form-control"><?= htmlspecialchars($pedido['observacoes']) ?></textarea>
        </div>

        <!-- PRODUTOS -->
        <hr><h5>Produtos</h5>
        <div class="mb-3">
            <label class="form-label">Produto</label>
            <div class="row g-2">
                <div class="col-auto">
                    <button type="button" class="btn btn-primary" id="btnNovoProdutoPedido" title="Novo Produto">
                        <i class="bi bi-plus-circle"></i>
                    </button>
                </div>
                <div class="col">
                    <select class="form-select select2" id="produtoSelect">
                        <option value="">Selecionar Produto</option>
                        <?php foreach ($produtos as $p): ?>
                            <option value="<?= $p['id'] ?>" data-descricao="<?= htmlspecialchars($p['descricao']) ?>" data-valor="<?= number_format($p['valor'], 2, ',', '') ?>">
                                <?= $p['descricao'] ?> - R$ <?= number_format($p['valor'], 2, ',', '') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary" type="button" id="adicionarProduto">Adicionar</button>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered" id="tabelaProdutos">
                <thead><tr><th>Descrição</th><th>Valor Unitário</th><th>Qtd</th><th>Subtotal</th><th>Ações</th></tr></thead>
                <tbody></tbody>
            </table>
        </div>

        <div class="mb-3 text-end">
            <label>Total do Pedido:</label>
            <input type="text" class="form-control text-end" name="valor_total" id="valor_total" readonly value="0,00">
        </div>

        <!-- MOVIMENTACOES -->
        <hr><h5>Movimentações</h5>
        <div class="row g-2 mb-3">
            <div class="col-md-3"><input type="text" class="form-control" placeholder="Descrição" id="descMov"></div>
            <div class="col-md-2"><input type="text" class="form-control" placeholder="Valor" id="valorMov"></div>
            <div class="col-md-2"><input type="date" class="form-control" id="dataMov" value="<?= date('Y-m-d') ?>"></div>
            <div class="col-md-3">
                <select class="form-select" id="formaMov">
                    <option selected disabled>Forma de Pagamento</option>
                    <?php foreach ($formas_pgto as $fp): ?>
                        <option value="<?= $fp['id'] ?>"><?= $fp['forma'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2"><button type="button" class="btn btn-primary w-100" id="adicionarMov">Adicionar</button></div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered" id="tabelaMovs">
                <thead><tr><th>Descrição</th><th>Valor</th><th>Data</th><th>Forma</th><th>Ações</th></tr></thead>
                <tbody></tbody>
            </table>
        </div>

        <div class="alert alert-info text-end">
            Total Recebido: <span id="totalRecebido">R$ 0,00</span><br>
            Diferença: <span id="diferencaValor">R$ 0,00</span>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Salvar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
    </div>
</form>

<!-- Scripts finais -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Scripts personalizados -->
<script src="../assets/js/pedidos_form.js"></script>
