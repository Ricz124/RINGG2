<?php
session_start(); // Iniciar a sessão

// Redirecionar para login se o usuário não estiver autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Exibir informações do usuário com segurança
$user_id = $_SESSION['user_id'];
$user_nome = isset($_SESSION['user_nome']) ? htmlspecialchars($_SESSION['user_nome']) : 'Usuário';

// Exibir as informações de forma segura
echo "<h1>Bem-vindo, $user_nome!</h1>";
echo "Seu ID de usuário é: " . htmlspecialchars($user_id);
?>
<a href="../workstation.php">Ir para o Espaço de Trabalho</a>
<a href="logout.php">Sair</a>