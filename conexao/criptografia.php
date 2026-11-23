<?php
//Chave de criptografia (16 BYTES = AES-128)
define('CHAVE_SECRETA', 'senai_protecaopb');
define('METODO', 'AES-128-CBC');

function criptografar($texto){
    //Gerar um IV aleatorio
    $iv = openssl_random_pseudo_bytes(16);

    //criptografar
    $criptografado = openssl_encrypt($texto, METODO,
        CHAVE_SECRETA, OPENSSL_RAW_DATA, $iv);

    return base64_encode($iv . $criptografado);
}

function descriptografar($dadosBase64){
    $dados = base64_decode($dadosBase64, true);

    if ($dados === false || strlen($dados) < 32) {
        return $dadosBase64;
    }

    $iv = substr($dados, 0, 16);
    $criptografado = substr($dados, 16);

    $texto = openssl_decrypt($criptografado, METODO,
        CHAVE_SECRETA, OPENSSL_RAW_DATA, $iv);

    return $texto === false ? $dadosBase64 : $texto;
}

?>