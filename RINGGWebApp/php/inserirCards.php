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
        // Prepara a consulta para verificar se o card já existe
        $checkCardStmt = $pdo->prepare("SELECT COUNT(*) FROM cards WHERE id = :id AND column_id = :column_id");

        // Prepara a consulta para atualizar um card existente
        $updateCardStmt = $pdo->prepare("UPDATE cards SET title = :title, creation_date = :creation_date, due_date = :due_date, color = :color, tasks = :tasks WHERE id = :id AND column_id = :column_id");

        // Prepara a consulta para inserir um novo card caso não exista
        $insertCardStmt = $pdo->prepare("INSERT INTO cards (id, column_id, title, creation_date, due_date, color, tasks) VALUES (:id, :column_id, :title, :creation_date, :due_date, :color, :tasks)");

        // Percorre cada coluna
        foreach ($data['columns'] as $column) {
            $columnId = $column['id'];

            // Percorre cada card na coluna
            foreach ($column['cards'] as $card) {
                $cardId = $card['id'];
                $cardTitle = $card['title'];
                $creationDate = $card['creationDate'];
                $dueDate = $card['dueDate'];
                $color = $card['color'];
                $tasks = json_encode($card['tasks']);

                // Verifica se o card já existe para a coluna atual
                $checkCardStmt->execute([
                    ':id' => $cardId,
                    ':column_id' => $columnId
                ]);

                // Se o card já existe, faz a atualização
                if ($checkCardStmt->fetchColumn() > 0) {
                    $updateCardStmt->execute([
                        ':id' => $cardId,
                        ':column_id' => $columnId,
                        ':title' => $cardTitle,
                        ':creation_date' => $creationDate,
                        ':due_date' => $dueDate,
                        ':color' => $color,
                        ':tasks' => $tasks
                    ]);
                    $response["cards"][] = [
                        "cardId" => $cardId,
                        "columnId" => $columnId,
                        "action" => "updated"
                    ];
                } else {
                    // Caso contrário, faz a inserção
                    $insertCardStmt->execute([
                        ':id' => $cardId,
                        ':column_id' => $columnId,
                        ':title' => $cardTitle,
                        ':creation_date' => $creationDate,
                        ':due_date' => $dueDate,
                        ':color' => $color,
                        ':tasks' => $tasks
                    ]);
                    $response["cards"][] = [
                        "cardId" => $cardId,
                        "columnId" => $columnId,
                        "action" => "inserted"
                    ];
                }
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