<?php
require_once '../includes/db.php';

function valorEmReaisParaFloat($valor) {
    $valor = str_replace('.', '', $valor); // remove milhar
    $valor = str_replace(',', '.', $valor); // troca vírgula por ponto
    return floatval($valor);
}

try {
    $pdo->beginTransaction();

    $id = $_POST['id'] ?? null;
    $cliente_id = $_POST['cliente_id'] ?? null;
    $data_pedido = $_POST['data_pedido'] ?? date('Y-m-d');
    $previsao_entrega = $_POST['previsao_entrega'] ?? null;
    $status = $_POST['status'] ?? 'Em Andamento';
    $pronto_em = $_POST['pronto_em'] ?? null;
    $entregue_em = $_POST['entregue_em'] ?? null;
    $liquidado_em = $_POST['liquidado_em'] ?? null;
    $observacoes = $_POST['observacoes'] ?? '';
    $valor_total = valorEmReaisParaFloat($_POST['valor_total_pedido'] ?? '0,00');

    if ($id) {
        // Atualiza pedido existente
        $stmt = $pdo->prepare("UPDATE pedidos SET cliente_id = ?, data_pedido = ?, previsao_entrega = ?, status = ?, pronto_em = ?, entregue_em = ?, liquidado_em = ?, observacoes = ?, valor_total = ? WHERE id = ?");
        $stmt->execute([$cliente_id, $data_pedido, $previsao_entrega, $status, $pronto_em, $entregue_em, $liquidado_em, $observacoes, $valor_total, $id]);
    } else {
        // Cria novo pedido
        $stmt = $pdo->prepare("INSERT INTO pedidos (cliente_id, data_pedido, previsao_entrega, status, pronto_em, entregue_em, liquidado_em, observacoes, valor_total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$cliente_id, $data_pedido, $previsao_entrega, $status, $pronto_em, $entregue_em, $liquidado_em, $observacoes, $valor_total]);
        $id = $pdo->lastInsertId();
    }

    // Remove itens anteriores, se for edição
    $pdo->prepare("DELETE FROM pedido_itens WHERE pedido_id = ?")->execute([$id]);

    // Insere novos itens
    if (isset($_POST['produto']) && is_array($_POST['produto'])) {
        foreach ($_POST['produto'] as $key => $descricao) {
            $valor_unitario = valorEmReaisParaFloat($_POST['valorUnt'][$key]);
            $quantidade = intval($_POST['qtde'][$key]);
            $subtotal = $valor_unitario * $quantidade;

            $stmt = $pdo->prepare("INSERT INTO pedido_itens (pedido_id, descricao, valor_unitario, quantidade, subtotal) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$id, $descricao, $valor_unitario, $quantidade, $subtotal]);
        }
    }

    // Remove movimentações anteriores, se for edição
    $pdo->prepare("DELETE FROM movimentacoes WHERE pedido_id = ?")->execute([$id]);

    // Insere movimentações
    $total_pago = 0;
    if (isset($_POST['descMovimentacao'])) {
        foreach ($_POST['descMovimentacao'] as $key => $descricao) {
            $valor = valorEmReaisParaFloat($_POST['valorMovimentacao'][$key]);
            $data = $_POST['dataMovimentacao'][$key];
            $forma_pagamento = $_POST['formaPagamento'][$key];

            $stmt = $pdo->prepare("INSERT INTO movimentacoes (pedido_id, valor, descricao, data, forma_pagamento) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$id, $valor, $descricao, $data, $forma_pagamento]);

            $total_pago += $valor;
        }
    }

    // Se o total pago for igual ao total do pedido, marca como Liquidado
    if ($total_pago >= $valor_total && $valor_total > 0) {
        $data_liquidado = $_POST['dataMovimentacao'][array_key_last($_POST['dataMovimentacao'])] ?? date('Y-m-d');
        $stmt = $pdo->prepare("UPDATE pedidos SET status = 'Liquidado', liquidado_em = ? WHERE id = ?");
        $stmt->execute([$data_liquidado, $id]);
    }

    $pdo->commit();
    header("Location: index.php");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Erro ao salvar o pedido: " . $e->getMessage();
    exit;
}
