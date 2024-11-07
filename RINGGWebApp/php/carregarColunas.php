<?php
session_start();
header('Content-Type: application/json');

// Verifique se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Usuário não autenticado."]);
    exit;
}

$user_id = $_SESSION['user_id']; // Obtém o ID do usuário da sessão

// Configurações de conexão com o banco de dados (ajuste conforme necessário)
$host = 'sql103.byethost7.com';
$dbname = 'b7_37575800_ringg_db';
$username = 'b7_37575800';
$password = 'asdf1234ert';

try {
    // Conectar ao banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepara a consulta para buscar as colunas do usuário
    $stmt = $pdo->prepare("SELECT id, title FROM columns WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $user_id]);

    // Busca os dados das colunas
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retorna os dados das colunas como JSON
    echo json_encode(['columns' => $columns]);

} catch (PDOException $e) {
    // Em caso de erro na conexão ou na execução do SQL
    echo json_encode(["error" => "Erro ao conectar ao banco de dados: " . $e->getMessage()]);
}
?>
