<?php require 'app/Views/layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Produtos</h2>
    <button class="btn btn-primary" id="btnNovoProduto">+ Novo Produto</button>
</div>

<?php if (isset($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
<?php elseif (isset($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
<?php endif; ?>

<div class="card p-3 shadow-sm rounded-3">
    <table id="tabela-produtos" class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Descrição</th>
                <th>Valor Unitário</th>
                <th class="text-center" style="width: 150px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['descricao']) ?></td>
                    <td>R$ <?= number_format($p['valor'], 2, ',', '.') ?></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary btnEditar" data-id="<?= $p['id'] ?>">Editar</button>
                        <a href="/produtos/excluir/<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="modalProduto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog"><div class="modal-content" id="modalProdutoContent"></div></div>
</div>

<script src="/assets/js/table.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    tablejs(document.getElementById('tabela-produtos'));

    document.getElementById('btnNovoProduto').addEventListener('click', () => {
        fetch('/produtos/modal')
            .then(res => res.text())
            .then(html => {
                document.getElementById('modalProdutoContent').innerHTML = html;
                new bootstrap.Modal(document.getElementById('modalProduto')).show();
            });
    });

    document.querySelectorAll('.btnEditar').forEach(btn => {
        btn.addEventListener('click', () => {
            fetch('/produtos/modal/' + btn.dataset.id)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('modalProdutoContent').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('modalProduto')).show();
                });
        });
    });
});
</script>

<?php require 'app/Views/layout/footer.php'; ?>
