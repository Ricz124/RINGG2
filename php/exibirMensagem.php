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

// Verifica se o ID da mensagem foi enviado
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Consulta para obter a mensagem e o usuário correspondente
    $stmt = $conn->prepare("SELECT s.id AS suporte_id, s.mensagem, s.resposta, u.nome, u.email 
                            FROM suporte s
                            JOIN users u ON s.user_id = u.id
                            WHERE s.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $suporte_id = $row['suporte_id'];
        $mensagem = $row['mensagem'];
        $resposta = $row['resposta'];
        $nome = $row['nome'];
        $email = $row['email'];
    } else {
        $error = "Nenhuma mensagem encontrada com o ID fornecido.";
    }

    $stmt->close();
}

// Verifica se a resposta foi enviada
if (isset($_POST['resposta']) && isset($_POST['suporte_id'])) {
    $suporte_id = $_POST['suporte_id'];
    $resposta = $_POST['resposta'];

    // Prepara e executa a consulta SQL para atualizar a resposta
    $stmt = $conn->prepare("UPDATE suporte SET resposta = ? WHERE id = ?");
    $stmt->bind_param("si", $resposta, $suporte_id);

    if ($stmt->execute()) {
        $success = "Resposta enviada com sucesso!";
    } else {
        $error = "Erro ao enviar a resposta: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responder Mensagem</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #53B8A6;
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            color: #333;
            margin-top: 20px;
        }

        p {
            color: #333;
            font-size: 1.1em;
            margin-bottom: 10px;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #53B8A6;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .error {
            color: #d9534f;
            font-weight: bold;
        }

        .success {
            color: #5cb85c;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Responder Mensagem</h1>
        <form action="exibirMensagem.php" method="post">
            <label for="id">ID da Mensagem:</label>
            <input type="text" name="id" id="id" required>
            <button type="submit">Exibir Mensagem</button>
        </form>

        <?php if (isset($suporte_id)): ?>
            <h2>Detalhes da Mensagem</h2>
            <p><strong>ID da Mensagem:</strong> <?php echo htmlspecialchars($suporte_id); ?></p>
            <p><strong>Nome do Usuário:</strong> <?php echo htmlspecialchars($nome); ?></p>
            <p><strong>Email do Usuário:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>Mensagem:</strong> <?php echo htmlspecialchars($mensagem); ?></p>
            <p><strong>Resposta Atual:</strong> <?php echo htmlspecialchars($resposta ? $resposta : 'Nenhuma resposta'); ?></p>

            <h2>Responder à Mensagem</h2>
            <form action="exibirMensagem.php" method="post">
                <input type="hidden" name="suporte_id" value="<?php echo htmlspecialchars($suporte_id); ?>">
                <textarea name="resposta" id="resposta" cols="60" rows="10"></textarea>
                <br>
                <button type="submit">Enviar Resposta</button>
            </form>
        <?php elseif (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <p class="success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
