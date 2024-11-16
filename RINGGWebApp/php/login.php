<?php
session_start();
require 'db.php'; // Certifique-se de que 'db.php' inicializa $conn corretamente
include 'nav.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Sanitizar o email
    $senha = $_POST['senha'];

    // Buscar usuário no banco de dados
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['user_id'] = $user['id']; // Armazenar ID do usuário na sessão
        header("Location: dashboard.php"); // Redirecionar para a página do dashboard
        exit();
    } else {
        // Evitar informações detalhadas sobre o erro em ambientes de produção
        echo "Credenciais incorretas.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../../css/pagIndex.css">
    <meta charset="UTF-8">
    <title>Login</title>
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
        <h2>Login</h2>
        <form method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" required>

            <button type="submit">Login</button>
        </form>
        <a href="register.php">Não tem uma conta ainda?</a>
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