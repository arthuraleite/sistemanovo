<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Cliente
{
    private $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    public function listarTodos()
    {
        $sql = "SELECT * FROM clientes ORDER BY nome";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function existeCpfCnpj($cpf_cnpj, $id = null)
    {
        $sql = "SELECT id FROM clientes WHERE cpf_cnpj = ?";
        $params = [$cpf_cnpj];

        if ($id) {
            $sql .= " AND id != ?";
            $params[] = $id;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() ? true : false;
    }

    public function salvar($dados)
    {
        if (!empty($dados['id'])) {
            // UPDATE
            $stmt = $this->db->prepare("UPDATE clientes SET 
                nome=?, telefone=?, email=?, cpf_cnpj=?, 
                inscricao_estadual=?, inscricao_municipal=?, 
                responsavel_nome=?, responsavel_contato=?,
                cep=?, logradouro=?, numero=?, bairro=?, cidade=?, estado=?, observacao=?
                WHERE id=?");
            return $stmt->execute([
                $dados['nome'], $dados['telefone'], $dados['email'], $dados['cpf_cnpj'],
                $dados['inscricao_estadual'], $dados['inscricao_municipal'],
                $dados['responsavel_nome'], $dados['responsavel_contato'],
                $dados['cep'], $dados['logradouro'], $dados['numero'], $dados['bairro'], $dados['cidade'], $dados['estado'],
                $dados['observacao'],
                $dados['id']
            ]);
        } else {
            // INSERT
            $stmt = $this->db->prepare("INSERT INTO clientes (
                nome, telefone, email, cpf_cnpj, 
                inscricao_estadual, inscricao_municipal, 
                responsavel_nome, responsavel_contato,
                cep, logradouro, numero, bairro, cidade, estado, observacao
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            return $stmt->execute([
                $dados['nome'], $dados['telefone'], $dados['email'], $dados['cpf_cnpj'],
                $dados['inscricao_estadual'], $dados['inscricao_municipal'],
                $dados['responsavel_nome'], $dados['responsavel_contato'],
                $dados['cep'], $dados['logradouro'], $dados['numero'], $dados['bairro'], $dados['cidade'], $dados['estado'],
                $dados['observacao']
            ]);
        }
    }

    public function excluir($id)
    {
        $stmt = $this->db->prepare("DELETE FROM clientes WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
