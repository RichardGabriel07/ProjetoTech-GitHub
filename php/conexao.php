<?php 
 
// Configurações de conexão com o banco de dados 
$host = 'localhost'; 
$db = 'projeto_tech'; 
$user = 'root'; 
$pass = '';      // senha padrão do EasyPHP geralmente é vazia 
$charset = 'utf8mb4'; 
 
// DSN com aspas corretas 
$dsn = "mysql:host=$host;dbname=$db;charset=$charset"; 
 
// Opções recomendadas para o PDO 
$options = [ 
     // Lança exceções em erros 
    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, 
    // Retorna resultados como array associativo 
    PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC, 
    // Usa prepared statements nativos 
    PDO::ATTR_EMULATE_PREPARES=>false,                   
]; 
 
try { 
    $pdo = new PDO($dsn, $user, $pass, $options); 
} catch (PDOException $e) { 
    die('Erro na conexão com o banco de dados: ' . $e->getMessage()); 
} 
?>