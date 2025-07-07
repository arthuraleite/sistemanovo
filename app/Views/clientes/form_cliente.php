<form action="/clientes/salvar" method="POST" id="formCliente">
    <?php if (isset($cliente['id'])): ?>
        <input type="hidden" name="id" value="<?= $cliente['id'] ?>">
    <?php endif; ?>

    <div class="modal-header">
        <h5 class="modal-title"><?= isset($cliente['id']) ? 'Editar Cliente' : 'Novo Cliente' ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body">
        <label>Nome:</label>
        <input type="text" name="nome" class="form-control" required value="<?= $cliente['nome'] ?? '' ?>">

        <label>Telefone:</label>
        <input type="text" name="telefone" class="form-control" value="<?= $cliente['telefone'] ?? '' ?>">

        <label>Email:</label>
        <input type="email" name="email" class="form-control" value="<?= $cliente['email'] ?? '' ?>">

        <label>CPF/CNPJ:</label>
        <input type="text" name="cpf_cnpj" id="cpf_cnpj" class="form-control" required value="<?= $cliente['cpf_cnpj'] ?? '' ?>">

        <div id="camposPJ" style="display: none; margin-top: 20px;">
            <label>Inscrição Estadual:</label>
            <input type="text" name="inscricao_estadual" class="form-control" value="<?= $cliente['inscricao_estadual'] ?? '' ?>">

            <label>Inscrição Municipal:</label>
            <input type="text" name="inscricao_municipal" class="form-control" value="<?= $cliente['inscricao_municipal'] ?? '' ?>">

            <label>Responsável (nome):</label>
            <input type="text" name="responsavel_nome" class="form-control" value="<?= $cliente['responsavel_nome'] ?? '' ?>">

            <label>Responsável (contato):</label>
            <input type="text" name="responsavel_contato" class="form-control" value="<?= $cliente['responsavel_contato'] ?? '' ?>">

            <hr>
            <h5>Endereço</h5>
            <label>CEP:</label>
            <input type="text" name="cep" id="cep" class="form-control" value="<?= $cliente['cep'] ?? '' ?>">

            <label>Logradouro:</label>
            <input type="text" name="logradouro" id="logradouro" class="form-control" value="<?= $cliente['logradouro'] ?? '' ?>">

            <label>Número:</label>
            <input type="text" name="numero" class="form-control" value="<?= $cliente['numero'] ?? '' ?>">

            <label>Bairro:</label>
            <input type="text" name="bairro" id="bairro" class="form-control" value="<?= $cliente['bairro'] ?? '' ?>">

            <label>Cidade:</label>
            <input type="text" name="cidade" id="cidade" class="form-control" value="<?= $cliente['cidade'] ?? '' ?>">

            <label>Estado:</label>
            <input type="text" name="estado" id="estado" class="form-control" value="<?= $cliente['estado'] ?? '' ?>">
        </div>

        <label class="mt-3">Observações:</label>
        <textarea name="observacao" class="form-control"><?= $cliente['observacao'] ?? '' ?></textarea>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Salvar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const cpfCnpj = document.getElementById('cpf_cnpj');
    const camposPJ = document.getElementById('camposPJ');

    function detectarTipo() {
        const valor = cpfCnpj.value.replace(/\D/g, '');
        camposPJ.style.display = valor.length > 11 ? 'block' : 'none';
    }

    cpfCnpj.addEventListener('input', detectarTipo);
    detectarTipo(); // inicial

    const cep = document.getElementById('cep');
    if (cep) {
        cep.addEventListener('blur', () => {
            const valor = cep.value.replace(/\D/g, '');
            if (valor.length === 8) {
                fetch(`https://viacep.com.br/ws/${valor}/json/`)
                    .then(res => res.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('logradouro').value = data.logradouro;
                            document.getElementById('bairro').value = data.bairro;
                            document.getElementById('cidade').value = data.localidade;
                            document.getElementById('estado').value = data.uf;
                        }
                    });
            }
        });
    }
});
</script>
