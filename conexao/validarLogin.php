<?php
require_once "bd.php";

$email = $_POST['login'];
$senha = $_POST['senha'];

$sql="SELECT * FROM usuario WHERE login=:login LIMIT 1";
$stmt = $bd->prepare($sql);
$stmt->bindParam(':login', $email);
$stmt->execute();

if($stmt->rowCount() > 0){

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    if(password_verify($senha, $usuario['senha'])){
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