<?php
session_start();
include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Produtos</h3>
    </div>
    <button class="btn btn-primary" id="btnNovoProduto">Cadastrar Produto</button>

    <table id="tabela-produtos" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Descrição</th>
                <th>Valor</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            <!-- preenchido via AJAX -->
        </tbody>
    </table>
    <div id="export-buttons"></div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalProduto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="conteudoModalProduto">
      <!-- Formulário será carregado aqui via AJAX -->
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>

<!-- DataTables e máscara de moeda -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


<!-- CSS dos botões -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<!-- JS para exportação -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>


<script>
$(document).ready(function () {
    const tabela = $('#tabela-produtos').DataTable({
        ajax: 'buscar.php',
        columns: [
            { data: 'descricao' },
            {
                data: 'valor',
                render: function (data) {
                    return 'R$ ' + parseFloat(data).toFixed(2).replace('.', ',');
                }
            },
            {
                data: null,
                className: 'text-center',
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-sm btn-warning me-1" onclick="editarProduto(${row.id})">Editar</button>
                        <button class="btn btn-sm btn-danger" onclick="excluirProduto(${row.id})">Excluir</button>
                    `;
                }
            }
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Excel',
                className: 'btn btn-success'
            },
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                className: 'btn btn-danger',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: {
                    columns: [0, 1]
                }
            }
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
        },
        initComplete: function () {
            // Move os botões para a div personalizada
            tabela.buttons().container().appendTo('#export-buttons');
        }
    });



    $('#btnNovoProduto').click(function () {
        $.get('form_modal.php', function (html) {
        $('#conteudoModalProduto').html(html);
        $('#modalProduto').modal('show');
    });

    });
});

function editarProduto(id) {
    $.get('form_modal.php?id=' + id, function (html) {
        $('#conteudoModalProduto').html(html);
        $('#modalProduto').modal('show');
    });
}

function excluirProduto(id) {
    if (confirm('Tem certeza que deseja excluir este produto?')) {
        $.post('deletar.php', { id: id }, function (res) {
            if (res.success) {
                $('#tabela-produtos').DataTable().ajax.reload();
            } else {
                alert('Erro ao excluir: ' + res.message);
            }
        }, 'json');
    }
}
</script>
