<?php
session_start();

// Destrói todas as variáveis de sessão
session_unset();

// Destrói a sessão
session_destroy();

// Redireciona para a página de login ou para a página inicial
header("Location: login.php"); // Substitua "login.php" pela página de login ou página inicial desejada
exit();
?>