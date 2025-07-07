<?php require 'app/Views/layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Clientes</h2>
    <button class="btn btn-primary" id="btnNovoCliente">+ Novo Cliente</button>
</div>

<?php if (isset($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
<?php elseif (isset($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
<?php endif; ?>

<div class="card p-3 shadow-sm rounded-3">
    <table id="tabela-clientes" class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>CPF/CNPJ</th>
                <th class="text-center" style="width: 150px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['nome']) ?></td>
                    <td><?= htmlspecialchars($c['telefone']) ?></td>
                    <td><?= htmlspecialchars($c['email']) ?></td>
                    <td><?= htmlspecialchars($c['cpf_cnpj']) ?></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary btnEditar" data-id="<?= $c['id'] ?>">Editar</button>
                        <a href="/clientes/excluir/<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="modalCliente" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg"><div class="modal-content" id="modalClienteContent"></div></div>
</div>

<script src="/assets/js/table.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    tablejs(document.getElementById('tabela-clientes'));

    document.getElementById('btnNovoCliente').addEventListener('click', () => {
        fetch('/clientes/modal')
            .then(res => res.text())
            .then(html => {
                document.getElementById('modalClienteContent').innerHTML = html;
                new bootstrap.Modal(document.getElementById('modalCliente')).show();
            });
    });

    document.querySelectorAll('.btnEditar').forEach(btn => {
        btn.addEventListener('click', () => {
            fetch('/clientes/modal/' + btn.dataset.id)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('modalClienteContent').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('modalCliente')).show();
                });
        });
    });
});
</script>

<?php require 'app/Views/layout/footer.php'; ?>
