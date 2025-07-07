<?php

namespace App\Controllers;

use App\Models\Usuario;

class AuthController
{
    public function login()
    {
        session_start();
        if (isset($_SESSION['usuario'])) {
            header('Location: /dashboard');
            exit;
        }

        require 'app/Views/auth/login.php';
    }

    public function autenticar()
    {
        session_start();
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $usuario = (new Usuario())->buscarPorEmail($email);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario'] = [
                'id' => $usuario['id'],
                'nome' => $usuario['nome'],
                'email' => $usuario['email'],
                'tipo' => $usuario['tipo']
            ];
            header('Location: /dashboard');
            exit;
        } else {
            $_SESSION['erro'] = 'Usuário ou senha inválidos.';
            header('Location: /login');
            exit;
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /login');
        exit;
    }
}
