<?php
session_start(); // Iniciar a sessão

include 'nav.php';

// Redirecionar para login se o usuário não estiver autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Configurações de conexão com o banco de dados (ajuste conforme necessário)
$host = 'sql103.byethost7.com';
$dbname = 'b7_37575800_ringg_db';
$username = 'b7_37575800';
$password = 'asdf1234ert';

try {
    // Conectar ao banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obter o ID do usuário da sessão
    $user_id = $_SESSION['user_id'];

    // Buscar informações do usuário
    $stmtUser = $pdo->prepare("SELECT nome, email FROM users WHERE id = :user_id");
    $stmtUser->execute([':user_id' => $user_id]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    // Verificar se o usuário foi encontrado
    if (!$user) {
        echo "<h1>Usuário não encontrado</h1>";
        exit();
    }

    // Buscar cards que estão prestes a expirar
    $stmtCards = $pdo->prepare("SELECT title, due_date FROM cards WHERE user_id = :user_id AND due_date >= CURDATE() ORDER BY due_date ASC LIMIT 5");
    $stmtCards->execute([':user_id' => $user_id]);
    $cards = $stmtCards->fetchAll(PDO::FETCH_ASSOC);

    // Buscar mensagem e resposta do usuário
    $stmtMessage = $pdo->prepare("SELECT mensagem, resposta FROM suporte WHERE user_id = :user_id ORDER BY id DESC LIMIT 1");
    $stmtMessage->execute([':user_id' => $user_id]);
    $message = $stmtMessage->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Em caso de erro na conexão ou na execução do SQL
    echo "<h1>Erro ao conectar ao banco de dados: " . $e->getMessage() . "</h1>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <link rel="stylesheet" href="../../css/pagIndex.css">
    <script src="https://kit.fontawesome.com/602c4605e3.js" crossorigin="anonymous"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Itim&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Red+Hat+Display:wght@300..900&display=swap');

        * {
            padding: 0;
            margin: auto;
            font-family: 'Red Hat Display';
            font-weight: 500;
        }

        a{
            text-decoration: none;
            color: black; 
        }

        body {
            background-color: #f4f4f4;
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

        p {
            color: #333;
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li:hover {
            background-color: #fff;
            color: #333;
        }

        footer {
            width: 100%;
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            margin-top: 20px;
        }

        .footer-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            padding: 10px 20px;
        }

        .footer-section {
            flex: 1;
            padding: 10px;
            min-width: 200px;
        }

        .footer-section h4 {
            margin-bottom: 10px;
            font-size: 1.2em;
        }

        .footer-section p,
        .footer-section ul,
        .footer-section a {
            color: #fff;
            font-size: 0.9em;
            text-decoration: none;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section ul li {
            margin: 5px 0;
        }

        .footer-section .social-icons a {
            color: #fff;
            font-size: 1.5em;
            margin: 0 10px;
            transition: color 0.3s;
        }

        .footer-section .social-icons a:hover {
            color: #00bcd4;
        }

        .footer-bottom {
            background-color: #222;
            padding: 10px 0;
        }

        .footer-bottom p {
            margin: 0;
            font-size: 0.8em;
        }

        .message-section {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .message-section h2 {
            color: #53B8A6;
            margin-bottom: 10px;
        }

        .message-section p {
            color: #333;
            font-size: 1.1em;
            margin-bottom: 10px;
        }

        .nav-links{
            display: flex;
            flex-direction: row;
            gap: 20px;
        }

        .nav-links a:hover{
            font-size: larger;
            color: #53B8A6;
        }

        .message-section .response {
            color: #007bff;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bem-vindo, <?php echo htmlspecialchars($user['nome']); ?>!</h1>
        <p>Seu ID de usuário é: <?php echo htmlspecialchars($user_id); ?></p>
        <p>Seu email é: <?php echo htmlspecialchars($user['email']); ?></p>

        <?php if (!empty($cards)): ?>
            <h2>Cards que estão prestes a expirar:</h2>
            <ul>
                <?php foreach ($cards as $card): ?>
                    <li><?php echo htmlspecialchars($card['title']); ?> (Vencimento: <?php echo htmlspecialchars($card['due_date']); ?>)</li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nenhum card prestes a expirar.</p>
        <?php endif; ?>

        <div class="message-section">
            <h2>Sua Última Mensagem e Resposta</h2>
            <?php if ($message): ?>
                <p><strong>Sua Mensagem:</strong> <?php echo htmlspecialchars($message['mensagem']); ?></p>
                <p class="response"><strong>Resposta:</strong> <?php echo htmlspecialchars($message['resposta'] ? $message['resposta'] : 'Ainda não há resposta.'); ?></p>
            <?php else: ?>
                <p>Você ainda não enviou nenhuma mensagem.</p>
            <?php endif; ?>
        </div>

        <div class="nav-links">
            <a href="../workstation.php">Ir para o Espaço de Trabalho</a>
            <a href="logout.php">Sair</a>
        </div>
    </div>

    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h4>Sobre Nós</h4>
                <p>Informações sobre a empresa.</p>
            </div>
            <div class="footer-section">
                <h4>Links Úteis</h4>
                <ul>
                    <li><a href="#">Política de Privacidade</a></li>
                    <li><a href="#">Termos de Serviço</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Redes Sociais</h4>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2023 Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>