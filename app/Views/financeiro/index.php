<?php require 'app/Views/layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Financeiro</h2>
    <div>
        <button class="btn btn-primary me-2" id="btnNovaMovimentacao">+ Nova Movimentação</button>
        <button class="btn btn-outline-dark" id="btnNovoRecorrente">+ Conta Recorrente</button>
    </div>
</div>

<?php if (isset($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
<?php endif; ?>

<!-- Movimentações -->
<div class="card p-3 shadow-sm rounded-3 mb-4">
    <h5 class="mb-3">Entradas e Saídas</h5>
    <table class="table table-bordered table-hover align-middle" id="tabela-mov">
        <thead class="table-light">
            <tr>
                <th>Tipo</th>
                <th>Data</th>
                <th>Descrição</th>
                <th>Forma de Pagamento</th>
                <th>Vinculado ao Pedido</th>
                <th class="text-end">Valor</th>
                <th class="text-center" style="width: 130px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($movimentacoes as $m): ?>
                <tr>
                    <td>
                        <span class="badge bg-<?= $m['tipo'] == 'entrada' ? 'success' : 'danger' ?>">
                            <?= ucfirst($m['tipo']) ?>
                        </span>
                    </td>
                    <td><?= date('d/m/Y', strtotime($m['data'])) ?></td>
                    <td><?= htmlspecialchars($m['descricao']) ?></td>
                    <td><?= htmlspecialchars($m['forma_pagamento'] ?? '—') ?></td>
                    <td class="text-center"><?= $m['pedido_id'] ? '✔️' : '—' ?></td>
                    <td class="text-end">R$ <?= number_format($m['valor'], 2, ',', '.') ?></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary btnEditarMov" data-id="<?= $m['id'] ?>">Editar</button>
                        <a href="/financeiro/excluir/<?= $m['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirma?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Recorrentes -->
<div class="card p-3 shadow-sm rounded-3 mb-5">
    <h5 class="mb-3">Contas Recorrentes</h5>
    <table class="table table-bordered table-hover align-middle" id="tabela-rec">
        <thead class="table-light">
            <tr>
                <th>Descrição</th>
                <th>Tipo</th>
                <th>Valor Previsto</th>
                <th>Frequência</th>
                <th>Próx. Vencimento</th>
                <th class="text-center" style="width: 130px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recorrentes as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['descricao']) ?></td>
                    <td>
                        <span class="badge bg-<?= $r['tipo'] == 'entrada' ? 'success' : 'danger' ?>">
                            <?= ucfirst($r['tipo']) ?>
                        </span>
                    </td>
                    <td>R$ <?= number_format($r['valor'], 2, ',', '.') ?></td>
                    <td><?= ucfirst($r['frequencia']) ?></td>
                    <td><?= date('d/m/Y', strtotime($r['data_vencimento'])) ?></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary btnEditarRec" data-id="<?= $r['id'] ?>">Editar</button>
                        <a href="/financeiro/excluir-recorrente/<?= $r['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirma?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modais -->
<div class="modal fade" id="modalFinanceiro" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content" id="modalFinanceiroContent"></div></div></div>

<script src="/assets/js/table.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    tablejs(document.getElementById('tabela-mov'));
    tablejs(document.getElementById('tabela-rec'));

    document.getElementById('btnNovaMovimentacao').addEventListener('click', () => {
        fetch('/financeiro/modal').then(r => r.text()).then(html => {
            document.getElementById('modalFinanceiroContent').innerHTML = html;
            new bootstrap.Modal(document.getElementById('modalFinanceiro')).show();
        });
    });

    document.getElementById('btnNovoRecorrente').addEventListener('click', () => {
        fetch('/financeiro/modal-recorrente').then(r => r.text()).then(html => {
            document.getElementById('modalFinanceiroContent').innerHTML = html;
            new bootstrap.Modal(document.getElementById('modalFinanceiro')).show();
        });
    });

    document.querySelectorAll('.btnEditarMov').forEach(btn => {
        btn.addEventListener('click', () => {
            fetch('/financeiro/modal/' + btn.dataset.id).then(r => r.text()).then(html => {
                document.getElementById('modalFinanceiroContent').innerHTML = html;
                new bootstrap.Modal(document.getElementById('modalFinanceiro')).show();
            });
        });
    });

    document.querySelectorAll('.btnEditarRec').forEach(btn => {
        btn.addEventListener('click', () => {
            fetch('/financeiro/modal-recorrente/' + btn.dataset.id).then(r => r.text()).then(html => {
                document.getElementById('modalFinanceiroContent').innerHTML = html;
                new bootstrap.Modal(document.getElementById('modalFinanceiro')).show();
            });
        });
    });
});
</script>

<?php require 'app/Views/layout/footer.php'; ?>