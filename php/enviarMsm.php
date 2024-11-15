<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
          
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
$user_id = $_POST['user_id'];
$mensagem = $_POST['mot_imp'];

// Prepara e executa a consulta SQL
$stmt = $conn->prepare("INSERT INTO suporte (user_id, mensagem) VALUES (?, ?)");
$stmt->bind_param("is", $user_id, $mensagem);

if ($stmt->execute()) {
    echo "Mensagem enviada com sucesso!";
    header('Location: ../quemsomos.php');
} else {
    echo "Erro ao enviar a mensagem: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>