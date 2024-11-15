<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responder Mensagem</title>
    <script>
        function enviarResposta(event) {
            event.preventDefault(); // Previne o envio padrão do formulário

            // Envia o formulário via AJAX
            var form = document.getElementById('respostaForm');
            var formData = new FormData(form);

            fetch('enviarResposta.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // Exibe o alerta com a resposta do servidor
                form.reset(); // Limpa o formulário
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Ocorreu um erro ao enviar a resposta.');
            });
        }
    </script>
</head>
<body>
    <h1>Responder Mensagem</h1>
    <form action="exibirMensagem.php" method="post">
        <label for="id">ID da Mensagem:</label>
        <input type="text" name="id" id="id" required>
        <button type="submit">Exibir Mensagem</button>
    </form>

    <?php
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

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
            echo "<h2>Detalhes da Mensagem</h2>";
            echo "<p><strong>ID da Mensagem:</strong> " . $row['suporte_id'] . "</p>";
            echo "<p><strong>Nome do Usuário:</strong> " . $row['nome'] . "</p>";
            echo "<p><strong>Email do Usuário:</strong> " . $row['email'] . "</p>";
            echo "<p><strong>Mensagem:</strong> " . $row['mensagem'] . "</p>";
            echo "<p><strong>Resposta Atual:</strong> " . ($row['resposta'] ? $row['resposta'] : 'Nenhuma resposta') . "</p>";

            // Formulário para responder à mensagem
            echo "<h2>Responder à Mensagem</h2>";
            echo "<form id='respostaForm' action='php/enviarResposta.php' method='post' onsubmit='enviarResposta(event)'>";
            echo "<input type='hidden' name='suporte_id' value='" . $row['suporte_id'] . "'>";
            echo "<textarea name='resposta' id='resposta' cols='60' rows='10'></textarea>";
            echo "<br>";
            echo "<button type='submit'>Enviar Resposta</button>";
            echo "</form>";
        } else {
            echo "<p>Nenhuma mensagem encontrada com o ID fornecido.</p>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>