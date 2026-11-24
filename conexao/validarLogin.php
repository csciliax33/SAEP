<?php
require_once "bd.php";
session_start();

$email = $_POST['login'];
$senha = $_POST['senha'];

$sql="SELECT * FROM usuario WHERE login=:login LIMIT 1";
$stmt = $bd->prepare($sql);
$stmt->bindParam(':login', $email);
$stmt->execute();

if($stmt->rowCount() > 0){

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    if(password_verify($senha, $usuario['senha'])){
        session_regenerate_id(true);
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_login'] = $usuario['login'];
        $_SESSION['ultima_atividade'] = time();
        echo "<script> alert('Seja Bem vindo');window.location.href = '../inicio.php'; </script>";
    }else{
        echo "
            <script>
                alert('🙊 Senha incorreto!');
                window.location.href = '../index.php';
            </script>";
    }

}else{
 echo "
  <script>
    alert('🙈 E-mail incorreto!');
    window.location.href = '../index.php';
    </script>";
}


?>