<?php
    require_once "bd.php";

    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $contato = $_POST['contato'];

    $sql = "INSERT INTO clientes (nome, cpf, contato) VALUES (:nome, :cpf, :contato)";
    $stmt = $bd->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':contato', $contato);

    if ($stmt->execute()){
        echo "<script>
        alert('🆒 cadastro realizado!');
        window.location.href='../cliente.php';
        </script>";
    } else {
        echo "<script>
        alert('ERRO');
        window.location.href='../cliente.php';
        </script>";
    }
?>