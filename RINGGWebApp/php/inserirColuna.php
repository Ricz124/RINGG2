<?php
session_start();
header('Content-Type: application/json');

// Verifique se o usuário está autenticado, ou seja, se a variável de sessão `user_id` está definida
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

    // Recebe o conteúdo da requisição POST (JSON)
    $json = file_get_contents('php://input');

    // Decodifica o JSON em um array associativo PHP
    $data = json_decode($json, true);

    // Array para armazenar a resposta
    $response = [];

    if ($data) {
        // Prepara a consulta para verificar se a coluna já existe
        $checkColumnStmt = $pdo->prepare("SELECT COUNT(*) FROM columns WHERE id = :id AND user_id = :user_id");

        // Prepara a consulta para atualizar o título de uma coluna existente
        $updateColumnStmt = $pdo->prepare("UPDATE columns SET title = :title WHERE id = :id AND user_id = :user_id");

        // Prepara a consulta para inserir uma nova coluna caso não exista
        $insertColumnStmt = $pdo->prepare("INSERT INTO columns (id, user_id, title) VALUES (:id, :user_id, :title)");

        // Percorre cada coluna
        foreach ($data['columns'] as $column) {
            // Extrai os dados da coluna
            $columnId = $column['id'];
            $columnTitle = $column['title'];

            // Verifica se a coluna já existe para o usuário atual
            $checkColumnStmt->execute([
                ':id' => $columnId,
                ':user_id' => $user_id
            ]);

            // Se a coluna já existe, faz a atualização
            if ($checkColumnStmt->fetchColumn() > 0) {
                $updateColumnStmt->execute([
                    ':id' => $columnId,
                    ':user_id' => $user_id,
                    ':title' => $columnTitle
                ]);
                $response["columns"][] = [
                    "columnId" => $columnId,
                    "columnTitle" => $columnTitle,
                    "action" => "updated"
                ];
            } else {
                // Caso contrário, faz a inserção
                $insertColumnStmt->execute([
                    ':id' => $columnId,
                    ':user_id' => $user_id,
                    ':title' => $columnTitle
                ]);
                $response["columns"][] = [
                    "columnId" => $columnId,
                    "columnTitle" => $columnTitle,
                    "action" => "inserted"
                ];
            }
        }
    } else {
        $response["error"] = "Erro ao decodificar os dados JSON.";
    }

    // Retorna a resposta como JSON
    echo json_encode($response);

} catch (PDOException $e) {
    // Em caso de erro na conexão ou na execução do SQL
    echo json_encode(["error" => "Erro ao conectar ao banco de dados: " . $e->getMessage()]);
}
?>
