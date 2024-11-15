<?php
session_start()
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Suporte ao Usuário</title>
    <script></script>
    <link rel="stylesheet" href="css/QmSomospag.css">
    <!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
-->
<script src="https://kit.fontawesome.com/602c4605e3.js" crossorigin="anonymous"></script>
</head>
<body>
    <nav>
        <div class="icone-ringg"><a href="http://ricardohoster.byethost7.com/RINGG/index.html"><img src="img/img2.jpg"></a></div>
        <div class="nav-mob">
          <ul>
            <a href="quemsomos.php"><li><i class="fa-solid fa-question"></i></li></a>
            <a href="ajuda.html"><li><i class="fa-solid fa-circle-info"></i></li></a>
            <a href="RINGGWebApp/workstation.php"><li><i class="fa-solid fa-laptop"></i></li></a>
            <a href="RINGGWebApp/php/dashboard.php"><li><i class="fa-solid fa-user"></i></li></a>
          </ul>
        </div>
        <div class="navbar">
          <div class="op-nav">
            <ul>
              <a href="quemsomos.php"><li>Suporte</li></a>
              <a href="RINGGWebApp/php/dashboard.php"><li>Dashboard</li></a>
              <a href="ajuda.html"><li>Ajuda</li></a>
              <a href="RINGGWebApp/workstation.php"><li>Espaço de Trabalho</li></a>
              <a href="RINGGWebApp/php/login.php"><li>ENTRAR</li></a>
            </ul>
          </div>
        </div>
      </nav>

      <div class="banner-nosco"><img src="img/imgNosco.png" alt="" srcset=""></div>

      <div class="site_corpo">
        <div class="titulo-ass">ASSISTÊNCIA</div>

        <div class="ass-desc">
          Aqui você pode enviar uma mensagem para nos informar de algum motivo pelo qual está tendo dificuldades com o Web-Aplicatívo.
        </div>
            <div class="ass-form">
                <form action="php/enviarMsm.php" method="post">
                    <label for="Motivo do Pedido:"></label>
                    <textarea name="mot_imp" id="mot_imp" cols="60" rows="10"></textarea>
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <br>
                    <button class="enviar" type="submit">ENVIAR</button>
                </form>
            </div>
      </div>
        <br>
        <br> 
      <footer>
        <div class="footer-container">
          <div class="footer-section">
            <h4>Sobre Nós</h4>
            <p>RINGG é uma plataforma para apoiar o desenvolvimento acadêmico e pessoal de jovens estudantes.</p>
          </div>
          <div class="footer-section">
            <h4>Links Rápidos</h4>
            <ul>
              <li><a href="quemsomos.php">Suporte</a></li>
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