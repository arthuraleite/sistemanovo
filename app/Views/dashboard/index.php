<?php require 'app/Views/layout/header.php'; ?>

<h2 class="mb-4">Dashboard</h2>

<div class="row mb-4">
    <!-- Resumo financeiro -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header">Resumo Financeiro (Mês Atual)</div>
            <div class="card-body">
                <canvas id="graficoFinanceiro" height="150"></canvas>
            </div>
        </div>
    </div>

    <!-- Contas a pagar -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header">Contas a Pagar (Próximos 7 dias)</div>
            <div class="card-body">
                <?php if (empty($contasVencendo)): ?>
                    <p class="text-muted">Nenhuma conta prevista.</p>
                <?php else: ?>
                    <ul class="list-group">
                        <?php foreach ($contasVencendo as $c): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= htmlspecialchars($c['descricao']) ?>
                                <span class="badge bg-danger">
                                    Vence em <?= date('d/m', strtotime($c['data_vencimento'])) ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Pedidos em andamento -->
<div class="card shadow-sm mb-4">
    <div class="card-header">Pedidos em Andamento</div>
    <div class="card-body table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th>Cliente</th>
                    <th>Status</th>
                    <th>Previsão de Entrega</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($emAndamento as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['cliente']) ?></td>
                        <td><?= $p['status'] ?></td>
                        <td><?= date('d/m/Y', strtotime($p['previsao_entrega'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pedidos prontos -->
<div class="card shadow-sm mb-5">
    <div class="card-header">Pedidos Prontos (Aguardando Entrega)</div>
    <div class="card-body table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th>Cliente</th>
                    <th>Telefone</th>
                    <th>Status</th>
                    <th>Previsão de Entrega</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prontos as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['cliente']) ?></td>
                        <td><?= htmlspecialchars($p['telefone'] ?? '—') ?></td>
                        <td><?= $p['status'] ?></td>
                        <td><?= date('d/m/Y', strtotime($p['previsao_entrega'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Gráfico JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('graficoFinanceiro').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Entradas', 'Saídas'],
        datasets: [{
            data: [<?= $resumoFinanceiro['entrada'] ?>, <?= $resumoFinanceiro['saida'] ?>],
            backgroundColor: ['#198754', '#dc3545']
        }]
    },
    options: {
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});
</script>

<?php require 'app/Views/layout/footer.php'; ?>
