<?php 
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: php/login.php"); // Redirecionar para login se não estiver autenticado
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espaço de Trabalho</title>
    <link rel="stylesheet" href="../css/pagIndex.css">
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <script src="https://kit.fontawesome.com/602c4605e3.js" crossorigin="anonymous"></script>
</head>
<body>
    <nav>
        <div class="icone-ringg"><a href="http://ricardohoster.byethost7.com/RINGG/index.html"><img src="../img/img2.jpg"></a></div>
        <div class="nav-mob">
          <ul>
            <a href="../quemsomos.html"><li><i class="fa-solid fa-question"></i></li></a>
            <a href="../ajuda.html"><li><i class="fa-solid fa-circle-info"></i></li></a>
            <a href="workstation.php"><li><i class="fa-solid fa-laptop"></i></li></a>
            <a href="php/dashboard.php"><li><i class="fa-solid fa-user"></i></li></a>
          </ul>
        </div>
        <div class="navbar">
          <div class="op-nav">
            <ul>
              <a href="../quemsomos.html"><li>Suporte</li></a>
              <a href="php/dashboard.php"><li>Dashboard</li></a>
              <a href="../ajuda.html"><li>Ajuda</li></a>
              <a href="workstation.php"><li>Espaço de Trabalho</li></a>
              <a href="php/login.php"><li>ENTRAR</li></a>
            </ul>
          </div>
        </div>
      </nav>

      <header>
        <h1>Espaço de Trabalho</h1>
        <button onclick="addColumn()">Adicionar Coluna</button>
        <button onclick="mandarColunaPuBanco(); mandarCardsPuBanco();">Salvar Mudanças</button>
      </header>
    
      <main id="board">

      </main>
    
      <!-- Modal de Detalhes do Cartão -->
      <div id="cardModal" class="modal">
        <div class="modal-content">
          <span class="close" onclick="closeModal()">&times;</span>
          <h2>Detalhes do Card</h2>
          <label for="cardTitle">Título do Card:</label>
          <input type="text" id="cardTitle">
      
          <p id="creationDate"></p>
          <label for="dueDate">Data de Prazo:</label>
          <input type="date" id="dueDate">

          <h3>Lista de Tarefas</h3>
          <ul id="taskList"></ul>
          <button onclick="addCheckbox()">Adicionar Tarefa</button>
          <button onclick="deleteCheckboxes()">Deletar Tarefas Selecionadas</button>
    
          <h3>Cor do Card</h3>
          <input type="color" id="cardColorPicker" onchange="changeCardColor()">
          
          <!-- Botão para deletar o card -->
          <button onclick="deleteCard()">Deletar Card</button>
        </div>
      </div>
      
      <footer>
        <div class="footer-container">
          <div class="footer-section">
            <h4>Sobre Nós</h4>
            <p>RINGG é uma plataforma para apoiar o desenvolvimento acadêmico e pessoal de jovens estudantes.</p>
          </div>
          <div class="footer-section">
            <h4>Links Rápidos</h4>
            <ul>
              <li><a href="quemsomos.html">Suporte</a></li>
              <li><a href="ajuda.html">Ajuda</a></li>
              <li><a href="RINGGWebApp/index.html">Aplicativo Web</a></li>
              <li><a href="RINGGWebApp/php/login.php">Entrar</a></li>
            </ul>
          </div>
          <div class="footer-section">
            <h4>Siga-nos</h4>
            <div class="social-icons">
              <a href="https://facebook.com" target="_blank"><i class="fa-brands fa-facebook"></i></a>
              <a href="https://twitter.com" target="_blank"><i class="fa-brands fa-twitter"></i></a>
              <a href="https://instagram.com" target="_blank"><i class="fa-brands fa-instagram"></i></a>
              <a href="https://linkedin.com" target="_blank"><i class="fa-brands fa-linkedin"></i></a>
            </div>
          </div>
        </div>
        <div class="footer-bottom">
          <p>&copy; 2024 RINGG. Todos os direitos reservados.</p>
        </div>
      </footer>
</body>
</html>
