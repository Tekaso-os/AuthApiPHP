<?php
/**
 * Este arquivo contém as configurações necessárias para
 * o sistema de login funcionar corretamente.
 */

/* Define o limite de tempo da sessão em 60 minutos */

/**
 * PDO - Conexão com a base de dados - Aula 28
 * http://www.tutsup.com/2014/07/23/pdo-em-php/
 */
 
// Variáveis da conexão
$base_dados  = 'apiauth';
$usuario_bd  = 'root';
$senha_bd    = 'usbw';
$host_db     = 'localhost';
$charset_db  = 'UTF8';
$conexao_pdo = null;


// Concatenação das variáveis para detalhes da classe PDO
$detalhes_pdo  = 'mysql:host=' . $host_db . ';';
$detalhes_pdo .= 'dbname='. $base_dados . ';';
$detalhes_pdo .= 'charset=' . $charset_db . ';';

// Tenta conectar
try {
    // Cria a conexão PDO com a base de dados
    $conexao_pdo = new PDO($detalhes_pdo, $usuario_bd, $senha_bd);
} catch (PDOException $e) {
    // Se der algo errado, mostra o erro PDO
    print "Erro: " . $e->getMessage() . "<br/>";
   
    // Mata o script
    die();
}

$user = $_GET['user'];
$password = $_GET['password'];

$pdo_checa_user = $conexao_pdo->prepare('SELECT * FROM users_panel WHERE user = ? LIMIT 1');
$verifica_pdo = $pdo_checa_user->execute( array( $user = $_GET['user'] ) );

// Verifica se a consulta foi realizada com sucesso
if ( ! $verifica_pdo ) {
    $erro = $pdo_checa_user->errorInfo();
    exit( $erro[2] );
}

// Busca os dados da linha encontrada
$fetch_usuario = $pdo_checa_user->fetch();
if ( crypt( $password, $fetch_usuario['password'] ) === $fetch_usuario['password'] ) {

        // action to perform
      // print_r ($fetch_usuario);
      echo $fetch_usuario['user'];
      echo "|";
      echo $fetch_usuario['plan'];
      echo "|";
      echo $fetch_usuario['expiry'];
      echo "|";
      echo $fetch_usuario['concurrents'];
      echo "|";
      echo $fetch_usuario['boottime'];
      echo "|";
      echo $fetch_usuario['vip'];


}
else{
    echo "fail";
    echo "|";
    echo "fail";
    echo "|";
    echo "fail";
}
?>