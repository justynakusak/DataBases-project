<?php
function connection() {
$dsn = 'pgsql:host=localhost;dbname=DB_PROJ_KUSAK';
$user = 'jkusak';
$password = 'chcialbys';
try{
    $pdo = new PDO($dsn, $user, $password);
}catch(PDOException $e){
    echo 'Connection failed: ' . $e->getMessage();
}
return $pdo;
}
?>
