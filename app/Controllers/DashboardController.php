<?php

namespace App\Controllers;

use App\Models\Pedido;
use App\Models\Movimentacao;
use App\Models\MovimentacaoRecorrente;

class DashboardController
{
    public function index()
    {
        $pedidoModel = new Pedido();
        $movModel = new Movimentacao();
        $recorrenteModel = new MovimentacaoRecorrente();

        $emAndamento = $pedidoModel->listarPorStatus(['Aberto', 'Em Andamento']);
        $prontos = $pedidoModel->listarPorStatus(['Pronto']);

        $contasVencendo = $recorrenteModel->listarVencendoEmDias(7);

        $resumoFinanceiro = $movModel->resumoMensal();

        require 'app/Views/dashboard/index.php';
    }
}
