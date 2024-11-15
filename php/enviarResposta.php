<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../RINGGWebApp/php/login.php");
    exit();
}

// Conexão com o banco de dados
$servername = "sql103.byethost7.com";
$username = "b7_37575800";
$password = "asdf1234ert";
$dbname = "b7_37575800_ringg_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtém os dados do formulário
$suporte_id = $_POST['suporte_id'];
$resposta = $_POST['resposta'];

// Prepara e executa a consulta SQL
$stmt = $conn->prepare("UPDATE suporte SET resposta = ? WHERE id = ?");
$stmt->bind_param("si", $resposta, $suporte_id);

if ($stmt->execute()) {
    echo "Resposta enviada com sucesso!";
} else {
    echo "Erro ao enviar a resposta: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>