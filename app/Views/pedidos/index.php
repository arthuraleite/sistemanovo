<?php require 'app/Views/layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Pedidos</h2>
    <button class="btn btn-primary" id="btnNovoPedido">+ Novo Pedido</button>
</div>

<?php if (isset($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
<?php elseif (isset($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
<?php endif; ?>

<div class="card p-3 shadow-sm rounded-3">
    <table id="tabela-pedidos" class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Cliente</th>
                <th>Data</th>
                <th>Previsão</th>
                <th>Status</th>
                <th class="text-end">Valor Total</th>
                <th class="text-center" style="width: 180px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['cliente']) ?></td>
                    <td><?= date('d/m/Y', strtotime($p['data_pedido'])) ?></td>
                    <td><?= date('d/m/Y', strtotime($p['previsao_entrega'])) ?></td>
                    <td><?= $p['status'] ?></td>
                    <td class="text-end">
                        R$
                        <?php
                            $valor = 0;
                            $itens = (new \App\Models\Pedido())->listarItens($p['id']);
                            foreach ($itens as $item) {
                                $valor += $item['subtotal'];
                            }
                            echo number_format($valor, 2, ',', '.');
                        ?>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary btnEditar" data-id="<?= $p['id'] ?>">Editar</button>
                        <a href="/pedidos/excluir/<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
                        <a href="/pedidos/pagamentos/<?= $p['id'] ?>" class="btn btn-sm btn-outline-dark">Pagamentos</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="modalPedido" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl"><div class="modal-content" id="modalPedidoContent"></div></div>
</div>

<script src="/assets/js/table.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    tablejs(document.getElementById('tabela-pedidos'));

    document.getElementById('btnNovoPedido').addEventListener('click', () => {
        fetch('/pedidos/modal')
            .then(res => res.text())
            .then(html => {
                document.getElementById('modalPedidoContent').innerHTML = html;
                new bootstrap.Modal(document.getElementById('modalPedido')).show();
            });
    });

    document.querySelectorAll('.btnEditar').forEach(btn => {
        btn.addEventListener('click', () => {
            fetch('/pedidos/modal/' + btn.dataset.id)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('modalPedidoContent').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('modalPedido')).show();
                });
        });
    });
});
</script>

<?php require 'app/Views/layout/footer.php'; ?>
