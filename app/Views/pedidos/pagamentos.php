<?php require 'app/Views/layout/header.php'; ?>

<h2 class="mb-4">Pagamentos do Pedido</h2>

<a href="/pedidos" class="btn btn-secondary mb-3">← Voltar para pedidos</a>

<div class="card shadow-sm p-3">
    <?php if (empty($pagamentos)): ?>
        <div class="alert alert-info">Nenhum pagamento registrado para este pedido.</div>
    <?php else: ?>
        <table class="table table-bordered align-middle" id="tabela-pagamentos">
            <thead class="table-light">
                <tr>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th>Forma de Pagamento</th>
                    <th class="text-end">Valor</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $total = 0;
                    foreach ($pagamentos as $pg):
                        $total += $pg['valor'];
                ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($pg['data'])) ?></td>
                        <td><?= htmlspecialchars($pg['descricao']) ?></td>
                        <td><?= htmlspecialchars($pg['forma_pagamento']) ?></td>
                        <td class="text-end">R$ <?= number_format($pg['valor'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total Recebido:</th>
                    <th class="text-end text-success">R$ <?= number_format($total, 2, ',', '.') ?></th>
                </tr>
            </tfoot>
        </table>
    <?php endif; ?>
</div>

<script src="/assets/js/table.js"></script>
<script>
    tablejs(document.getElementById('tabela-pagamentos'));
</script>

<?php require 'app/Views/layout/footer.php'; ?>
