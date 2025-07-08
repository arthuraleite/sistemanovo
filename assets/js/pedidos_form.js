$(document).ready(function () {
    const maskCurrency = (val) => {
        return parseFloat(val).toFixed(2).replace('.', ',');
    };

    function recalcularTotais() {
        let total = 0;
        $('#tabelaProdutos tbody tr').each(function () {
            const subtotal = parseFloat($(this).find('.subtotal').val().replace('.', '').replace(',', '.')) || 0;
            total += subtotal;
        });
        $('#valor_total').val(maskCurrency(total));

        let totalPago = 0;
        $('#tabelaMovs tbody tr').each(function () {
            const val = parseFloat($(this).find('.valor').text().replace('.', '').replace(',', '.')) || 0;
            totalPago += val;
        });

        const diff = total - totalPago;

        $('#totalRecebido').text('R$ ' + maskCurrency(totalPago));
        $('#diferencaValor').text('R$ ' + maskCurrency(diff));

        if (diff <= 0 && total > 0) {
            $('#status').val('Liquidado');
            let ultimaData = $('#tabelaMovs tbody tr:last-child').find('.data').text();
            if (ultimaData) $('input[name="liquidado_em"]').val(ultimaData);
        }
    }

    // Adicionar Produto
    $('#adicionarProduto').click(function () {
        const option = $('#produtoSelect option:selected');
        const id = option.val();
        const descricao = option.data('descricao');
        const valor = option.data('valor');

        if (!id) return;

        const row = `
            <tr>
                <td>
                    <input type="text" name="produtos[][descricao]" class="form-control" value="${descricao}" required>
                </td>
                <td>
                    <input type="text" name="produtos[][valor_unitario]" class="form-control valor" value="${valor}" required>
                </td>
                <td>
                    <input type="number" name="produtos[][quantidade]" class="form-control quantidade" min="1" value="1" required>
                </td>
                <td>
                    <input type="text" class="form-control subtotal" readonly value="${valor}">
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger removerProduto">Remover</button>
                </td>
            </tr>
        `;

        $('#tabelaProdutos tbody').append(row);
        $('#produtoSelect').val('');
        recalcularTotais();
    });

    // Calcular Subtotal
    $('#tabelaProdutos').on('input', '.valor, .quantidade', function () {
        const row = $(this).closest('tr');
        const valor = parseFloat(row.find('.valor').val().replace('.', '').replace(',', '.')) || 0;
        const qtd = parseFloat(row.find('.quantidade').val()) || 0;
        const subtotal = valor * qtd;
        row.find('.subtotal').val(maskCurrency(subtotal));
        recalcularTotais();
    });

    // Remover Produto
    $('#tabelaProdutos').on('click', '.removerProduto', function () {
        $(this).closest('tr').remove();
        recalcularTotais();
    });

    // Adicionar Movimentação
    $('#adicionarMov').click(function () {
        const desc = $('#descMov').val();
        const valor = $('#valorMov').val().replace('.', '').replace(',', '.');
        const data = $('#dataMov').val();
        const formaId = $('#formaMov').val();
        const formaText = $('#formaMov option:selected').text();

        if (!desc || !valor || !data || !formaId) return;

        const row = `
            <tr>
                <td>${desc}<input type="hidden" name="movs[][descricao]" value="${desc}"></td>
                <td class="valor">${valor}<input type="hidden" name="movs[][valor]" value="${valor}"></td>
                <td class="data">${data}<input type="hidden" name="movs[][data]" value="${data}"></td>
                <td>${formaText}<input type="hidden" name="movs[][forma_pagamento_id]" value="${formaId}"></td>
                <td><button type="button" class="btn btn-sm btn-danger removerMov">Remover</button></td>
            </tr>
        `;
        $('#tabelaMovs tbody').append(row);

        $('#descMov').val('');
        $('#valorMov').val('');
        $('#dataMov').val(new Date().toISOString().split('T')[0]);
        $('#formaMov').val('');

        recalcularTotais();
    });

    // Remover movimentação
    $('#tabelaMovs').on('click', '.removerMov', function () {
        $(this).closest('tr').remove();
        recalcularTotais();
    });

    // Abrir modal de novo cliente a partir do formulário de pedido
    $(document).on('click', '#btnNovoClientePedido', function() {
        // Oculta o modal de pedido temporariamente
        $('#modalPedido').modal('hide');

        $.get('../clientes/form_modal.php', function(html) {

            const novoTexto = html.replace('/salvar.php', "/salvar_pedido.php");
            console.log(novoTexto);
            // console.log(html);
            const modal = `
            <div class="modal fade" id="modalClienteInterno" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">${html}</div>
            </div>
            </div>
            `

            $('body').append(modal);
            const modalInstance = new bootstrap.Modal(document.getElementById('modalClienteInterno'));

            // Aplica as máscaras após o modal ser completamente carregado
            $('#modalClienteInterno').on('shown.bs.modal', function() {
                // Versão otimizada da função de máscaras
                function aplicarMascarasClienteModal() {
                    // Máscara CPF/CNPJ
                    $('#cpf_cnpj').on('input', function() {
                        const valor = $(this).val().replace(/\D/g, '');
                        const isCNPJ = valor.length > 11;
                        
                        if (isCNPJ) {
                            $(this).val(valor.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5'));
                            $('#dadosJuridicos').show();
                            document.getElementById('tipo').value = "Jurídica";
                        } else {
                            $(this).val(valor.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4'));
                            $('#dadosJuridicos').hide();
                            document.getElementById('tipo').value = "Física";
                        }
                    });

                    // Máscara telefone
                    $('#telefone').on('input', function() {
                        const valor = $(this).val().replace(/\D/g, '');
                        $(this).val(valor.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3'));
                    });

                    // Máscara CEP
                    $('#cep').on('input', function() {
                        const valor = $(this).val().replace(/\D/g, '');
                        $(this).val(valor.replace(/(\d{5})(\d{3})/, '$1-$2'));
                    });

                    // Busca CEP
                    $('#cep').on('blur', function() {
                        const cep = $(this).val().replace(/\D/g, '');
                        if (cep.length === 8) {
                            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                                .then(response => response.json())
                                .then(dados => {
                                    if (!dados.erro) {
                                        $('#logradouro').val(dados.logradouro);
                                        $('#bairro').val(dados.bairro);
                                        $('#cidade').val(dados.localidade);
                                    }
                                });
                        }
                    });
                }

                // Aplica as máscaras
                aplicarMascarasClienteModal();
            });

            modalInstance.show();

            // Ao fechar o modal de cliente, remove e mostra novamente o de pedido
            $('#modalClienteInterno').on('hidden.bs.modal', function() {
                $(this).remove();
                $('#modalPedido').modal('show');
            });
        });
    });


    // Abrir modal para novo produto
    $(document).on('click', '#btnNovoProdutoPedido', function () {
        // Oculta o modal do pedido
        $('#modalPedido').modal('hide');

        $.get('../produtos/form_modal.php', function (html) {
            const modal = `
                <div class="modal fade" id="modalProdutoInterno" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">${html}</div>
                </div>
                </div>
            `;
            $('body').append(modal);

            const modalInstance = new bootstrap.Modal(document.getElementById('modalProdutoInterno'));
            modalInstance.show();

            // Ao fechar o modal de produto, remove e reexibe o de pedido
            $('#modalProdutoInterno').on('hidden.bs.modal', function () {
                $('#modalProdutoInterno').remove();
                $('#modalPedido').modal('show');
            });
        });
    });

});