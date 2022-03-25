<?php
include('config.php')
$user = $_GET['user'];
$password = $_GET['password'];
// Verifica se estamos conectados ao BD
if ( ! isset( $conexao_pdo ) || ! is_object( $conexao_pdo ) ) {
	exit('Erro na conexão com o banco de dados.');
}

// Verifica se os campos de usuário e senha existem
// E se não estão em branco
if ( 
  isset ( $user ) && 
  isset ( $password ) &&
  ! empty ( $user ) &&
  ! empty ( $password ) 
) {
	// Faz a consulta do nome de usuário na base de dados
	$pdo_checa_user = $conexao_pdo->prepare('SELECT * FROM users_panel WHERE user = ? LIMIT 1');
	$verifica_pdo = $pdo_checa_user->execute( array( $user = $_GET['user'] ) );
	
	// Verifica se a consulta foi realizada com sucesso
	if ( ! $verifica_pdo ) {
		$erro = $pdo_checa_user->errorInfo();
		exit( $erro[2] );
	}
	
	// Busca os dados da linha encontrada
	$fetch_usuario = $pdo_checa_user->fetch();
	
	// Verifica se a senha do usuário está correta
	if ( crypt( $password, $fetch_usuario['password'] ) === $fetch_usuario['password'] ) {
		// O usuário está logado
		$_SESSION['logado']    =  true;
		$userr      =  $fetch_usuario['user'];
		echo ''.$fetch_usuario
	} else {
		// Continua deslogado
		$_SESSION['logado']     = false;
		
		// Preenche o erro para o usuário
		$_SESSION['login_erro'] = 'Usuário ou senha inválidos';
	}
}
?>
