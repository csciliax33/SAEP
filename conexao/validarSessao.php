<?php
$tempoExpiracao = 30 * 60;

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$autenticado = isset($_SESSION['usuario_nome']);
$ultimaAtividade = $_SESSION['ultima_atividade'] ?? null;
$sessaoExpirada = $autenticado
    && (!is_int($ultimaAtividade) || time() - $ultimaAtividade >= $tempoExpiracao);

if (!$autenticado || $sessaoExpirada) {
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $paginaLogin = strpos($script, '/conexao/') !== false ? '../index.php' : 'index.php';

    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $cookie = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $cookie['path'],
            $cookie['domain'],
            $cookie['secure'],
            $cookie['httponly']
        );
    }
    session_destroy();

    header('Location: ' . $paginaLogin . ($sessaoExpirada ? '?expirada=1' : ''));
    exit;
}

$_SESSION['ultima_atividade'] = time();
