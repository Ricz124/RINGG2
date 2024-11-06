<?php
session_start();
header('Content-Type: application/json');

// Recebe o conteúdo da requisição POST (JSON)
$json = file_get_contents('php://input');

// Decodifica o JSON em um array associativo PHP
$data = json_decode($json, true);

// Array para armazenar a resposta
$response = [];

if ($data) {
    // Percorre cada coluna
    foreach ($data['columns'] as $column) {
        // Armazena os dados da coluna
        $columnData = [
            "columnId" => $column['id'],
            "columnTitle" => $column['title'],
            "cards" => []
        ];

        // Percorre cada cartão dentro da coluna
        foreach ($column['cards'] as $card) {
            // Armazena os dados do cartão
            $cardData = [
                "cardId" => $card['id'],
                "cardTitle" => $card['title'],
                "cardCreationDate" => $card['creationDate'],
                "cardDueDate" => $card['dueDate'],
                "cardColor" => $card['color'],
                "tasks" => []
            ];

            // Percorre cada tarefa dentro do cartão
            foreach ($card['tasks'] as $task) {
                $taskData = [
                    "taskText" => $task['text'],
                    "taskCompleted" => $task['completed']
                ];

                // Adiciona a tarefa ao cartão
                $cardData["tasks"][] = $taskData;
            }

            // Adiciona o cartão à coluna
            $columnData["cards"][] = $cardData;
        }

        // Adiciona a coluna à resposta
        $response["columns"][] = $columnData;
    }
} else {
    $response["error"] = "Erro ao decodificar os dados JSON.";
}

// Retorna a resposta como JSON
echo json_encode($response);
?>
