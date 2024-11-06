<?php
session_start();
require 'db.php'; // Certifique-se de que 'db.php' inicializa $conn corretamente

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
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="POST">
        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" required><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>
