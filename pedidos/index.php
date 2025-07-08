<?php
session_start();
include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Pedidos</h3>
    </div>

    <button class="btn btn-primary" id="btnNovoPedido">Cadastrar Pedido</button>

    <table id="tabela-pedidos" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Itens</th>
                <th>Valor Total</th>
                <th>Status</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <div id="export-buttons-pedidos"></div>
</div>

<!-- Modal Pedido -->
<div class="modal fade" id="modalPedido" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content" id="conteudoModalPedido"></div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>

<!-- DataTables e dependências -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function () {
    const tabela = $('#tabela-pedidos').DataTable({
        ajax: 'buscar.php',
        columns: [
            { data: 'cliente_nome' },
            { data: 'itens' },
            {
                data: 'valor_total',
                render: function (data) {
                    return 'R$ ' + parseFloat(data).toFixed(2).replace('.', ',');
                }
            },
            { data: 'status' },
            {
                data: null,
                className: 'text-center',
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-sm btn-warning me-1" onclick="editarPedido(${row.id})">Editar</button>
                        <button class="btn btn-sm btn-danger" onclick="excluirPedido(${row.id})">Excluir</button>
                    `;
                }
            }
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Exportar Excel',
                className: 'btn btn-success'
            },
            {
                extend: 'pdfHtml5',
                text: 'Exportar PDF',
                className: 'btn btn-danger',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: {
                    columns: [0, 1, 2, 3]
                }
            }
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
        },
        initComplete: function () {
            tabela.buttons().container().appendTo('#export-buttons-pedidos');
        }
    });

    $('#btnNovoPedido').click(function () {
        $.get('form_modal.php', function (html) {
            $('#conteudoModalPedido').html(html);
            $('#modalPedido').modal('show');
        });
    });
});

function editarPedido(id) {
    $.get('form_modal.php?id=' + id, function (html) {
        $('#conteudoModalPedido').html(html);
        $('#modalPedido').modal('show');
    });
}

function excluirPedido(id) {
    if (confirm('Tem certeza que deseja excluir este pedido?')) {
        $.post('deletar.php', { id: id }, function (res) {
            if (res.success) {
                $('#tabela-pedidos').DataTable().ajax.reload();
            } else {
                alert('Erro ao excluir: ' + res.message);
            }
        }, 'json');
    }
}
</script>
