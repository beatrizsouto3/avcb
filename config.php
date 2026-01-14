<?php
$host = 'localhost';
$port = '5432';
$dbname = 'formulario-avcb';
$user = 'postgres';
$password = '1234';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    echo "Conexão bem-sucedida ao PostgreSQL!";

} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>