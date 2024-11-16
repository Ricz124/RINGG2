<?php
session_start();
require 'db.php'; // Certifique-se de que 'db.php' inicializa $conn corretamente
include 'nav.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografando a senha

    // Inserir no banco de dados
    $sql = "INSERT INTO users (nome, senha, email) VALUES (:nome, :senha, :email)";
    $stmt = $conn->prepare($sql);
    
    // Executar a consulta com os parâmetros corretos
    if ($stmt->execute([':nome' => $nome, ':email' => $email, ':senha' => $senha])) {
        $_SESSION['user_id'] = $conn->lastInsertId(); // Armazenar ID do usuário na sessão
        $_SESSION['user_nome'] = $nome;
        header("Location: dashboard.php"); // Redirecionar para a página do dashboard
        exit();
    } else {
        echo "Erro ao registrar usuário.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <link rel="stylesheet" href="loginStyle.css">
    <link rel="stylesheet" href="../../css/pagIndex.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        h2 {
            color: #53B8A6;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #53B8A6;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }

        a {
            color: #53B8A6;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Cadastro</h2>
        <form method="POST">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" required>

            <button type="submit">Cadastrar</button>
        </form>
        <a href="login.php">Já tem uma conta?</a>
    </div>

    <footer>
        <div class="footer-container">
          <div class="footer-section">
            <h4>Sobre Nós</h4>
            <p>RINGG é uma plataforma para apoiar o desenvolvimento acadêmico e pessoal de jovens estudantes.</p>
          </div>
          <div class="footer-section">
            <h4>Links Rápidos</h4>
            <ul>
              <li><a href="quemsomos.html">Suporte</a></li>
              <li><a href="ajuda.html">Ajuda</a></li>
              <li><a href="RINGGWebApp/index.html">Aplicativo Web</a></li>
              <li><a href="RINGGWebApp/php/login.php">Entrar</a></li>
            </ul>
          </div>
          <div class="footer-section">
            <h4>Siga-nos</h4>
            <div class="social-icons">
              <a href="https://facebook.com" target="_blank"><i class="fa-brands fa-facebook"></i></a>
              <a href="https://twitter.com" target="_blank"><i class="fa-brands fa-twitter"></i></a>
              <a href="https://instagram.com" target="_blank"><i class="fa-brands fa-instagram"></i></a>
              <a href="https://linkedin.com" target="_blank"><i class="fa-brands fa-linkedin"></i></a>
            </div>
          </div>
        </div>
        <div class="footer-bottom">
          <p>&copy; 2024 RINGG. Todos os direitos reservados.</p>
        </div>
      </footer>
</body>
</html>