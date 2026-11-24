<?php
require_once __DIR__ . '/validarSessao.php';
    require_once "bd.php";

    $data = $_POST['data'];
    $horario = $_POST['horario'];
    $cliente_id = $_POST['clientes_id'];
    $veiculo_id = $_POST['veiculos_id'];



    $sql = "INSERT INTO agendamentos (data, horario, veiculos_id, clientes_id) VALUES (:data, :horario, :veiculos_id, :clientes_id)";
    $stmt = $bd->prepare($sql);
    $stmt->bindParam(':data', $data);
    $stmt->bindParam(':horario', $horario);
    $stmt->bindParam(':veiculos_id', $veiculo_id);
    $stmt->bindParam(':clientes_id', $cliente_id);

    if ($stmt->execute()){
        echo "<script>
        alert('🆒 cadastro realizado!');
        window.location.href='../agendamento.php';
        </script>";
    } else {
        echo "<script>
        alert('ERRO');
        window.location.href='../agendamento.php';
        </script>";
    }
?>