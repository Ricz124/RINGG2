<?php
// Conexão com o banco de dados
$servername = "sql103.byethost7.com";
$username = "b7_37575800";
$password = "asdf1234ert";
$dbname = "b7_37575800_ringg_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Obtém os parâmetros do corpo da requisição
$data = json_decode(file_get_contents("php://input"), true);
$columnId = $data['columnId'];
$cardId = $data['cardId'];

// Verifica se os IDs são válidos
if (!is_numeric($columnId) || !is_numeric($cardId)) {
    echo json_encode(["success" => false, "message" => "IDs inválidos"]);
    exit;
}

// Verifica se o card existe na coluna especificada
$sql = "SELECT * FROM cards WHERE id = ? AND column_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $cardId, $columnId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Card não encontrado"]);
    exit;
}

// Deleta o card
$sql = "DELETE FROM cards WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cardId);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Card deletado com sucesso"]);
} else {
    echo json_encode(["success" => false, "message" => "Erro ao deletar o card: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>