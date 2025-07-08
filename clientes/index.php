<?php
session_start();
include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Clientes</h3>
    </div>

    <div class="mb-3">
        <button class="btn btn-primary" id="btnNovoCliente">Cadastrar Cliente</button>
    </div>

    <table id="tabela-clientes" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Email</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            <!-- preenchido via AJAX -->
        </tbody>
    </table>
    <div id="export-buttons-clientes"></div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalCliente" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" id="conteudoModalCliente">
      <!-- Formulário será carregado aqui via AJAX -->
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>

<!-- DataTables e exportação -->
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="../assets/js/clientes_form.js"></script>

<script>
$(document).ready(function () {
    const tabela = $('#tabela-clientes').DataTable({
        ajax: 'buscar.php',
        columns: [
            { data: 'nome' },
            { data: 'telefone' },
            { data: 'email' },
            {
                data: null,
                className: 'text-center',
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-sm btn-warning me-1" onclick="editarCliente(${row.id})">Editar</button>
                        <button class="btn btn-sm btn-danger" onclick="excluirCliente(${row.id})">Excluir</button>
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
                    columns: [0, 1, 2]
                }
            }
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
        },
        initComplete: function () {
            tabela.buttons().container().appendTo('#export-buttons-clientes');
        }
    });

    $('#btnNovoCliente').click(function () {
        $.get('form_modal.php', function (html) {
            $('#conteudoModalCliente').html(html);
            $('#modalCliente').modal('show');
        });
    });
});

function editarCliente(id) {
    $.get('form_modal.php?id=' + id, function (html) {
        $('#conteudoModalCliente').html(html);
        $('#modalCliente').modal('show');
    });
}

function excluirCliente(id) {
    if (confirm('Tem certeza que deseja excluir este cliente?')) {
        $.post('deletar.php', { id: id }, function (res) {
            if (res.success) {
                $('#tabela-clientes').DataTable().ajax.reload();
            } else {
                alert('Erro ao excluir: ' + res.message);
            }
        }, 'json');
    }
}
</script>
