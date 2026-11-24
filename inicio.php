<?php
require_once __DIR__ . '/conexao/validarSessao.php';
$nomeUsuario = $_SESSION['usuario_nome'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oficina | Portal</title>
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
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            width: min(850px, calc(100% - 32px));
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

        td:first-child {
            width: 72px;
            padding: 8px 10px 8px 0;
            font-weight: 700;
        }

        input {
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

        .botao-sair {
            display: flex;
            justify-content: center;
            margin: 0 0 40px;
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
    </style>
</head>
<body>
    <header>
        <h1>OFICINA MECANICA ⚙️🔧</h1>
    </header>

    <div class="menu">
        <nav aria-label="Menu principal">
            <ul>
                <li><a href="#">Início</a></li>
                <li><a href="cliente.php">Cliente</a></li>
                <li><a href="veiculo.php">Veículo</a></li>
                <li><a href="agendamento.php">Agendamento</a></li>
            </ul>
        </nav>
    </div>

   

    <main>
        <section class="boas-vindas">
            <h2>Página Inicial</h2>
            <p>Bem-vindo, <strong><?= htmlspecialchars($nomeUsuario, ENT_QUOTES, 'UTF-8') ?></strong>, à nossa oficina mecânica!</p>
        </section>

        
    </main>

    <form class="botao-sair" action="logout.php" method="post">
        <button type="submit">Sair</button>
    </form>

    <footer>
        <p>Copyright © 2026 - <strong>celia</strong></p>
    </footer>
</body>
</html>
