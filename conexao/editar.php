<?php
require_once __DIR__ . '/validarSessao.php';
require_once __DIR__ . '/bd.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id < 1) {
    echo '<script>alert("ID de agendamento inválido."); window.location.href = "../agendamento.php";</script>';
    exit;
}

$stmt = $bd->prepare(
    'SELECT id, data, horario, clientes_id, veiculos_id FROM agendamentos WHERE id = :id'
);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$agendamento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$agendamento) {
    echo '<script>alert("Agendamento não encontrado."); window.location.href = "../agendamento.php";</script>';
    exit;
}

$clientes = $bd->query('SELECT id, nome FROM clientes ORDER BY nome')->fetchAll(PDO::FETCH_ASSOC);
$veiculos = $bd->query('SELECT id, marca, modelo, placa FROM veiculos ORDER BY marca')->fetchAll(PDO::FETCH_ASSOC);
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = trim((string) ($_POST['data'] ?? ''));
    $horario = trim((string) ($_POST['horario'] ?? ''));
    $clienteId = filter_var($_POST['clientes_id'] ?? null, FILTER_VALIDATE_INT);
    $veiculoId = filter_var($_POST['veiculos_id'] ?? null, FILTER_VALIDATE_INT);
    $clienteIds = array_map('intval', array_column($clientes, 'id'));
    $veiculoIds = array_map('intval', array_column($veiculos, 'id'));

    if ($data === '' || $horario === ''
        || $clienteId === false || !in_array($clienteId, $clienteIds, true)
        || $veiculoId === false || !in_array($veiculoId, $veiculoIds, true)
    ) {
        $mensagem = 'Preencha todos os campos e selecione um cliente e um veículo válidos.';
        $agendamento['data'] = $data;
        $agendamento['horario'] = $horario;
        $agendamento['clientes_id'] = $clienteId ?: '';
        $agendamento['veiculos_id'] = $veiculoId ?: '';
    } else {
        $stmt = $bd->prepare(
            'UPDATE agendamentos
             SET data = :data, horario = :horario, clientes_id = :clientes_id, veiculos_id = :veiculos_id
             WHERE id = :id'
        );
        $stmt->bindValue(':data', $data);
        $stmt->bindValue(':horario', $horario);
        $stmt->bindValue(':clientes_id', $clienteId, PDO::PARAM_INT);
        $stmt->bindValue(':veiculos_id', $veiculoId, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo '<script>alert("Agendamento atualizado com sucesso."); window.location.href = "../agendamento.php";</script>';
        exit;
    }
} else {
    $data = $agendamento['data'];
    $horario = substr($agendamento['horario'], 0, 5);
    $clienteId = (int) $agendamento['clientes_id'];
    $veiculoId = (int) $agendamento['veiculos_id'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar agendamento | Oficina</title>
</head>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            color: #252b30;
            font-family: "Segoe UI", Arial, sans-serif;
            background: #f2f1ed;
        }

        header {
            padding: 24px 16px;
            text-align: center;
            color: #fff;
            background: #26313b;
            border-bottom: 4px solid #f2b632;
        }

        header h1 {
            margin: 0;
            font-size: clamp(1.6rem, 5vw, 2.2rem);
        }

        .menu {
            padding: 12px 16px;
            background: #394652;
        }

        .menu ul {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px 24px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .menu a {
            color: #fff;
            font-weight: 700;
            text-decoration: none;
        }

        .menu a:hover {
            color: #f2b632;
            text-decoration: underline;
        }

        main {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            width: min(1100px, calc(100% - 32px));
            margin: 40px auto;
        }

        .boas-vindas,
        .login-card {
            padding: 28px;
            border: 1px solid #ddd;
            border-top: 4px solid #f2b632;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 3px 10px #00000012;
        }

        h2 {
            margin: 0 0 16px;
            color: #26313b;
            font-size: 1.4rem;
        }

        .boas-vindas p {
            margin: 0;
            line-height: 1.6;
        }

        .login-card h2 {
            text-align: center;
        }

        form,
        table {
            width: 100%;
        }

        .tabela-container {
            width: 100%;
            overflow-x: auto;
        }

        .clientes-table {
            min-width: 720px;
            border-collapse: collapse;
            text-align: left;
        }

        .clientes-table th,
        .clientes-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #e3e3df;
            overflow-wrap: anywhere;
        }

        .clientes-table th {
            color: #fff;
            background: #394652;
            font-size: 0.85rem;
            letter-spacing: 0.04em;
        }

        .clientes-table tbody tr:nth-child(even) {
            background: #f7f6f2;
        }

        .clientes-table tbody tr:hover {
            background: #fff3d4;
        }

        .clientes-table .sem-clientes {
            color: #68717a;
            text-align: center;
        }

        td:first-child {
            width: 72px;
            padding: 8px 10px 8px 0;
            font-weight: 700;
        }

        input,
        select {
            width: 100%;
            min-width: 0;
            padding: 10px;
            border: 1px solid #c9c8c2;
            border-radius: 4px;
            color: #252b30;
            font: inherit;
            background: #fff;
        }

        button {
            padding: 10px 24px;
            border: 0;
            border-radius: 4px;
            color: #252b30;
            font: inherit;
            font-weight: 700;
            background: #f2b632;
            cursor: pointer;
        }

        footer {
            padding: 16px 12px;
            color: #fff;
            text-align: center;
            background: #26313b;
            border-top: 3px solid #f2b632;
        }

        footer p {
            margin: 0;
        }

        @media (max-width: 480px) {
            main {
                grid-template-columns: 1fr;
                margin: 24px auto;
            }

            td:first-child {
                width: 60px;
            }
        }

               .tabela-container {
            width: 100%;
            overflow-x: auto;
        }

        .clientes-table {
            border-collapse: collapse;
            margin-top: 12px;
            min-width: 100%;
            text-align: left;
        }

        .clientes-table th:first-child,
        .clientes-table td:first-child {
            min-width: 140px;
        }

        .clientes-table td:first-child {
            width: auto;
            padding: 11px 12px;
        }

        .clientes-table th,
        .clientes-table td {
            padding: 11px 12px;
            border-bottom: 1px solid #e3e3df;
        }

        .clientes-table th {
            color: #fff;
            background: #394652;
            font-size: 0.85rem;
            letter-spacing: 0.04em;
        }

        .clientes-table tbody tr:nth-child(even) {
            background: #f7f6f2;
        }

        .clientes-table tbody tr:hover {
            background: #fff3d4;
        }

        .clientes-table .sem-clientes {
            color: #68717a;
            text-align: center;
        }
    </style>

<body>
    <h1>Editar agendamento</h1>

    <?php if ($mensagem !== ''): ?>
        <p role="alert"><?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="post" action="editar.php?id=<?= (int) $id ?>">
        <label for="data">Data</label>
        <input id="data" type="date" name="data"
               value="<?= htmlspecialchars((string) $agendamento['data'], ENT_QUOTES, 'UTF-8') ?>" required>

        <label for="horario">Horário</label>
        <input id="horario" type="time" name="horario"
               value="<?= htmlspecialchars((string) $agendamento['horario'], ENT_QUOTES, 'UTF-8') ?>" required>

        <label for="clientes_id">Cliente</label>
        <select id="clientes_id" name="clientes_id" required>
            <option value="">Selecione um cliente</option>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?= (int) $cliente['id'] ?>"
                    <?= (int) $cliente['id'] === (int) $agendamento['clientes_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cliente['nome'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="veiculos_id">Veículo</label>
        <select id="veiculos_id" name="veiculos_id" required>
            <option value="">Selecione um veículo</option>
            <?php foreach ($veiculos as $veiculo): ?>
                <option value="<?= (int) $veiculo['id'] ?>"
                    <?= (int) $veiculo['id'] === (int) $agendamento['veiculos_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($veiculo['marca'] . ' - ' . $veiculo['modelo'] . ' (' . $veiculo['placa'] . ')', ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Atualizar agendamento</button>
        <a href="../agendamento.php">Cancelar</a>
    </form>
</body>
</html>