<?php
session_start(); // Iniciar a sessão

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirecionar para login se não estiver autenticado
    exit();
}

// Exibir informações do usuário
$user_id = $_SESSION['user_id'];
$user_nome = $_SESSION['user_nome'];

echo "<h1>Bem-vindo, $user_nome!</h1>"; // Exibir o nome do usuário
echo "Seu ID de usuário é: " . $user_id;
?>
<a href="../workstation.php">Ir para o Espaço de Trabalho</a>
<a href="logout.php">Sair</a>