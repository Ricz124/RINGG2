<?php
session_start();
require 'db.php'; // Certifique-se de que 'db.php' inicializa $conn corretamente

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
    <link rel="stylesheet" href="../../css/nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <h2>Cadastro</h2>
    <form method="POST">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" required><br>

        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>