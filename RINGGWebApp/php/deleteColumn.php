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

// Obtém o parâmetro do corpo da requisição
$data = json_decode(file_get_contents("php://input"), true);
$columnId = $data['columnId'];

// Verifica se o ID da coluna é válido
if (!is_numeric($columnId)) {
    echo json_encode(["success" => false, "message" => "ID da coluna inválido"]);
    exit;
}

// Verifica se a coluna existe
$sql = "SELECT * FROM columns WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $columnId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Coluna não encontrada"]);
    exit;
}

// Deleta todos os cards da coluna
$sql = "DELETE FROM cards WHERE column_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $columnId);
$stmt->execute();

// Deleta a coluna
$sql = "DELETE FROM columns WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $columnId);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Coluna e seus cards deletados com sucesso"]);
} else {
    echo json_encode(["success" => false, "message" => "Erro ao deletar a coluna: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>