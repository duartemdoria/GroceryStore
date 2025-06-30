<?php
// Ficheiro de Configuração da Base de Dados
// Substitua os valores abaixo pelos da sua configuração local

// Definição das credenciais de acesso
$db_host = 'localhost';         // Normalmente é 'localhost' ou '127.0.0.1'
$db_name = 'mercearia_db';      // O nome da base de dados que criámos
$db_user = 'root';              // O seu utilizador da base de dados (o padrão do XAMPP/MAMP é 'root')
$db_pass = '';                  // A sua palavra-passe da base de dados (o padrão do XAMPP/MAMP é vazia)
$charset = 'utf8mb4';           // Conjunto de caracteres

// DSN (Data Source Name) - define a ligação
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=$charset";

// Opções para o PDO (PHP Data Objects)
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lança exceções em caso de erro
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retorna os resultados como arrays associativos
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Usa preparações de queries nativas do MySQL
];

try {
    // Cria uma nova instância do PDO para a ligação
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
    // Se a ligação falhar, termina o script e mostra uma mensagem de erro
    // Numa aplicação real, poderia registar este erro num ficheiro em vez de o mostrar ao utilizador
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>
