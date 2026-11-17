<?php
    $host = "localhost";
    $dbname = "oficina";
    $user = "root";
    $senha = "";

    try{
        $bd = new PDO("mysql:host=$host; dbname=$dbname", $user,$senha);
        $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        echo "Erro: " . $e->getMessage();
    }
?>