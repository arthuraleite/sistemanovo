<?php
require_once '../includes/db.php';

$id = $_GET['id'] ?? null;
$cliente = [
    'id' => '',
    'nome' => '',
    'cpf_cnpj' => '',
    'tipo' => '',
    'telefone' => '',
    'email' => '',
    'cep' => '',
    'logradouro' => '',
    'numero' => '',
    'bairro' => '',
    'cidade' => '',
    'inscricao_municipal' => '',
    'inscricao_estadual' => '',
    'responsavel' => '',
    'observacoes' => ''
];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = ?");
    $stmt->execute([$id]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="modal-header">
    <h5 class="modal-title"><?= $id ? 'Editar Cliente' : 'Cadastrar Cliente' ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form id="formCliente" method="POST" action="salvar.php">
    <div class="modal-body">
        <input type="hidden" name="id" value="<?= $cliente['id'] ?>">

        <!-- Dados Pessoais -->
        <div class="mb-3">
            <label>CPF/CNPJ</label>
            <input type="text" name="cpf_cnpj" id="cpf_cnpj" class="form-control" value="<?= $cliente['cpf_cnpj'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Nome</label>
            <input type="text" name="nome" id="nome" class="form-control" value="<?= $cliente['nome'] ?>">
        </div>

        <div class="mb-3">
            <label>Tipo</label>
            <input type="text" id="tipo" name="tipo" class="form-control" readonly value="<?= $cliente['tipo'] == 'J' ? 'Jurídico' : 'Físico' ?>">
        </div>

        <div class="mb-3">
            <label>Telefone</label>
            <input type="text" name="telefone" id="telefone" class="form-control" value="<?= $cliente['telefone'] ?>">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= $cliente['email'] ?>">
        </div>

        <!-- Endereço -->
        <div class="mb-3">
            <label>CEP</label>
            <input type="text" name="cep" id="cep" class="form-control" value="<?= $cliente['cep'] ?>">
        </div>

        <div class="mb-3">
            <label>Logradouro</label>
            <input type="text" name="logradouro" id="logradouro" class="form-control" value="<?= $cliente['logradouro'] ?>">
        </div>

        <div class="mb-3">
            <label>Número</label>
            <input type="text" name="numero" class="form-control" value="<?= $cliente['numero'] ?>">
        </div>

        <div class="mb-3">
            <label>Bairro</label>
            <input type="text" name="bairro" id="bairro" class="form-control" value="<?= $cliente['bairro'] ?>">
        </div>

        <div class="mb-3">
            <label>Cidade</label>
            <input type="text" name="cidade" id="cidade" class="form-control" value="<?= $cliente['cidade'] ?>">
        </div>

        <!-- Dados Jurídicos -->
        <div id="dadosJuridicos" style="display: none;">
            <div class="mb-3">
                <label>Inscrição Municipal</label>
                <input type="text" name="inscricao_municipal" class="form-control" value="<?= $cliente['inscricao_municipal'] ?>">
            </div>

            <div class="mb-3">
                <label>Inscrição Estadual</label>
                <input type="text" name="inscricao_estadual" class="form-control" value="<?= $cliente['inscricao_estadual'] ?>">
            </div>

            <div class="mb-3">
                <label>Responsável</label>
                <input type="text" name="responsavel" class="form-control" value="<?= $cliente['responsavel'] ?>">
            </div>
        </div>

        <!-- Observações -->
        <div class="mb-3">
            <label>Observações</label>
            <textarea name="observacoes" class="form-control"><?= $cliente['observacoes'] ?></textarea>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Salvar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
    </div>
</form>

<script>
    $(document).on('shown.bs.modal', '#modalCliente', function () {
        aplicarMascarasCliente();
          $('#cpf_cnpj').trigger('input');
    });
</script>