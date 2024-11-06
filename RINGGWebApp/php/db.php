<?php
$host = 'sql103.byethost7.com'; // ou o endereço do seu servidor de banco de dados
$db = 'b7_37575800_ringg_db';
$user = 'b7_37575800';
$pass = 'asdf1234ert';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Conexão falhou: " . $e->getMessage();
}
?>