<?php
    require_once __DIR__ . '/conexao/validarSessao.php';
    require_once "C:\sos\htdocs\celia\php\simulado08\conexao\bd.php";

    $id = $_GET['id'];

    $sql = "DELETE FROM clientes WHERE id=:id";
    $stmt = $bd->prepare($sql);
    $stmt->bindParam(':id', $id);

     if($stmt->execute()){
        echo "<script>alert('Registro excluído com sucesso!');
        window.location.href = 'cliente.php';
        </script>";
    }else{
        echo "<script>alert('Erro ao excluir!');
        window.location.href = 'cliente.php';
        </script>";
    }

?>