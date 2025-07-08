function aplicarMascarasCliente() {
    const $cpfCnpj = $('#cpf_cnpj');
    
    if (!$cpfCnpj.length) return;

    // Máscara dinâmica otimizada
    $cpfCnpj.on('input', function() {
        const valor = $(this).val().replace(/\D/g, '');
        const isCNPJ = valor.length > 11;
        
        // Aplica a formatação sem usar .mask()
        if (isCNPJ) {
            $(this).val(formatarCNPJ(valor));
            $('#dadosJuridicos').show();
        } else {
            $(this).val(formatarCPF(valor));
            $('#dadosJuridicos').hide();
        }
    });

    // Funções de formatação otimizadas
    function formatarCPF(valor) {
        return valor.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
    }

    function formatarCNPJ(valor) {
        return valor.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
    }

    // Máscaras para outros campos (leves)
    $('#telefone').on('input', function() {
        const valor = $(this).val().replace(/\D/g, '');
        $(this).val(valor.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3'));
    });

    $('#cep').on('input', function() {
        const valor = $(this).val().replace(/\D/g, '');
        $(this).val(valor.replace(/(\d{5})(\d{3})/, '$1-$2'));
    });

    // Busca CEP otimizada
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
                })
                .catch(() => console.log('Erro ao buscar CEP'));
        }
    });
}
