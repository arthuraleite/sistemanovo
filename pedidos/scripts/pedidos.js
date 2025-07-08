let countProduto = 0;
let countMovimentacao = 0;

// Máscara de moeda
function aplicarMascaraMoeda(campo) {
    try { $(campo).unmask(); } catch (e) {}
    $(campo).mask('000.000.000,00', { reverse: true });
}

// Adicionar Produto
$('#btnAdicionarProduto').on('click', function () {
    const option = $('#selectProduto option:selected');
    const id = option.val();
    const nome = option.data('nome') || '';
    const valor = option.data('valor') || '0,00';

    if (!id) return;

    const row = `
        <tr data-id="${countProduto}">
            <td><input type="text" name="produto[${countProduto}]" class="form-control" value="${nome}" required></td>
            <td><input type="text" name="valorUnt[${countProduto}]" class="form-control valorUnt" value="${valor}" required></td>
            <td><input type="number" name="qtde[${countProduto}]" class="form-control qtde" value="1" required></td>
            <td><input type="text" class="form-control-plaintext valorSubtotal" readonly value="${valor}"></td>
            <td><button type="button" class="btn btn-sm btn-danger btnRemoverProduto">X</button></td>
        </tr>
    `;
    $('#tabela-produtos-pedido tbody').append(row);
    aplicarMascaraMoeda(`input[name="valorUnt[${countProduto}]"]`);
    atualizarTotais();
    countProduto++;
});

// Remover Produto
$(document).on('click', '.btnRemoverProduto', function () {
    $(this).closest('tr').remove();
    atualizarTotais();
});

// Atualizar subtotal e total
$(document).on('keyup change', '.valorUnt, .qtde', function () {
    const row = $(this).closest('tr');
    const valor = row.find('.valorUnt').val().replace(/\./g, '').replace(',', '.');
    const qtde = parseFloat(row.find('.qtde').val()) || 0;
    const subtotal = (parseFloat(valor) * qtde).toFixed(2).replace('.', ',');
    row.find('.valorSubtotal').val(subtotal);
    atualizarTotais();
});

function atualizarTotais() {
    let total = 0;
    $('#tabela-produtos-pedido tbody tr').each(function () {
        const val = $(this).find('.valorSubtotal').val().replace(/\./g, '').replace(',', '.');
        total += parseFloat(val) || 0;
    });
    $('#totalPedido').val(total.toFixed(2).replace('.', ','));

    atualizarSaldo();
}

// Adicionar Movimentação
$('#btnAdicionarMovimentacao').on('click', function () {
    const valor = $('#valorMovimentacao').val();
    const descricao = $('#descricaoMovimentacao').val();
    const data = $('#dataMovimentacao').val();
    const forma = $('#formaPagamento').val();
    const formaTexto = $('#formaPagamento option:selected').text();

    if (!valor || !descricao || !data || !forma) {
        alert('Preencha todos os campos da movimentação.');
        return;
    }

    const row = `
        <tr data-id="${countMovimentacao}">
            <td><input type="text" readonly class="form-control-plaintext" name="valorMovimentacao[${countMovimentacao}]" value="${valor}"></td>
            <td><input type="text" readonly class="form-control-plaintext" name="descMovimentacao[${countMovimentacao}]" value="${descricao}"></td>
            <td><input type="text" readonly class="form-control-plaintext" name="dataMovimentacao[${countMovimentacao}]" value="${data}"></td>
            <td><input type="text" readonly class="form-control-plaintext" name="formaPagamento[${countMovimentacao}]" value="${formaTexto}"></td>
            <td><button type="button" class="btn btn-sm btn-danger btnRemoverMovimentacao">X</button></td>
        </tr>
    `;
    $('#tabela-movimentacoes tbody').append(row);
    $('#valorMovimentacao').val('');
    $('#descricaoMovimentacao').val('');
    $('#dataMovimentacao').val('<?= date("Y-m-d") ?>');
    $('#formaPagamento').val('');
    countMovimentacao++;

    atualizarSaldo();
});

// Remover Movimentação
$(document).on('click', '.btnRemoverMovimentacao', function () {
    $(this).closest('tr').remove();
    atualizarSaldo();
});

// Atualizar Saldo do Pedido
function atualizarSaldo() {
    let totalPedido = parseFloat($('#totalPedido').val().replace(/\./g, '').replace(',', '.')) || 0;
    let totalPago = 0;

    $('#tabela-movimentacoes tbody tr').each(function () {
        const val = $(this).find('input[name^="valorMovimentacao"]').val().replace(/\./g, '').replace(',', '.');
        totalPago += parseFloat(val) || 0;
    });

    const saldo = totalPedido - totalPago;
    $('#saldoDoPedido').text(saldo.toFixed(2).replace('.', ','));

    // Atualizar status automaticamente
    if (saldo <= 0 && totalPedido > 0) {
        $('#status').val('Liquidado');
        $('#dataLiquidado').val(new Date().toISOString().split('T')[0]);
    }
}