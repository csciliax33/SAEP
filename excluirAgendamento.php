<?php
require_once __DIR__ . '/conexao/validarSessao.php';
 require_once "C:\sos\htdocs\celia\php\simulado08\conexao\bd.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id < 1) {
    $mensagem = 'ID de agendamento inválido.';
} else {
    try {
        $stmt = $bd->prepare('DELETE FROM agendamentos WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $mensagem = $stmt->rowCount() > 0
            ? 'Registro excluído com sucesso!'
            : 'Agendamento não encontrado.';
    } catch (PDOException $e) {
        $errorInfo = $e->errorInfo;
        if ($e->getCode() !== '23000'
            || !isset($errorInfo[1])
            || (int) $errorInfo[1] !== 1451
        ) {
            throw $e;
        }

        $mensagem = 'Não é possível remover este agendamento porque há clientes vinculados.';
    }
}

echo '<script>alert(' . json_encode($mensagem) . '); window.location.href = "agendamento.php";</script>';
