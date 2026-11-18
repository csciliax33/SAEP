<?php
    require_once "bd.php";

    $placa = $_POST['placa'];
    $modelo = $_POST['modelo'];
    $marca = $_POST['marca'];

    $sql = "INSERT INTO veiculos (placa, modelo, marca) VALUES (:placa, :modelo, :marca)";
    $stmt = $bd->prepare($sql);
    $stmt->bindParam(':placa', $placa);
    $stmt->bindParam(':modelo', $modelo);
    $stmt->bindParam(':marca', $marca);

    if ($stmt->execute()){
        echo "<script>
        alert('🆒 cadastro realizado!');
        window.location.href='../veiculo.php';
        </script>";
    } else {
        echo "<script>
        alert('ERRO');
        window.location.href='../veiculo.php';
        </script>";
    }
?>