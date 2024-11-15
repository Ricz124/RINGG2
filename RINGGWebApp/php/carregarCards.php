<?php
session_start();
header('Content-Type: application/json');

// Verifique se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Usuário não autenticado."]);
    exit;
}

$user_id = $_SESSION['user_id']; // Obtém o ID do usuário da sessão

// Verifique se o columnId foi passado como parâmetro
if (!isset($_GET['columnId'])) {
    echo json_encode(["error" => "Parâmetro columnId não fornecido."]);
    exit;
}

$columnId = $_GET['columnId'];

// Configurações de conexão com o banco de dados (ajuste conforme necessário)
$host = 'sql103.byethost7.com';
$dbname = 'b7_37575800_ringg_db';
$username = 'b7_37575800';
$password = 'asdf1234ert';

try {
    // Conectar ao banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepara a consulta para buscar os cartões da coluna específica e do usuário autenticado
    $stmt = $pdo->prepare("SELECT id, title, creation_date, due_date, color, tasks FROM cards WHERE column_id = :column_id AND user_id = :user_id");
    $stmt->execute([':column_id' => $columnId, ':user_id' => $user_id]);

    // Busca os dados dos cartões
    $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Mapeia o campo due_date para dueDate
    foreach ($cards as &$card) {
        $card['dueDate'] = $card['due_date'];
        unset($card['due_date']);
    }

    // Retorna os dados dos cartões como JSON
    echo json_encode(['cards' => $cards]);

} catch (PDOException $e) {
    // Em caso de erro na conexão ou na execução do SQL
    echo json_encode(["error" => "Erro ao conectar ao banco de dados: " . $e->getMessage()]);
}
?>