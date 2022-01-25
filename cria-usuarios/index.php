<?php
// Inclui o arquivo de configuração
include('../config.php');

// Variavél para preencher o erro (se existir)
$erro = false;

// Apaga usuários
if ( isset( $_GET['del'] ) ) {
	// Delete de cara (sem confirmação)
	$pdo_insere = $conexao_pdo->prepare('DELETE FROM users_panel WHERE ID=?');
	$pdo_insere->execute( array( (int)$_GET['del'] ) );
	
	// Redireciona para o index.php
	header('location: index.php');
}

// Verifica se algo foi postado para publicar ou editar
if ( isset( $_POST ) && ! empty( $_POST ) ) {
	// Cria as variáveis
	foreach ( $_POST as $chave => $valor ) {
		$$chave = $valor;
		
		// Verifica se existe algum campo em branco
		if ( empty ( $valor ) ) {
			// Preenche o erro
			$erro = 'Existem campos em branco.';
		}
	}
	
	// Verifica se as variáveis foram configuradas
//	if ( empty( $form_nome ) || empty( $form_senha ) || empty( $form_email ) || empty( $form_numero ) || empty( $form_professor ) || empty( $form_saldo ) || empty( $form_turma ) || empty( $form_matricula ) || empty( $form_serie ) ) {
//		$erro = 'Existem campos em branco.';
//	}
	
	// Verifica se o usuário existe
	$pdo_verifica = $conexao_pdo->prepare('SELECT * FROM users_panel WHERE user = ?');
	$pdo_verifica->execute( array( $form_user ) );
	
	// Captura os dados da linha
	$user_id = $pdo_verifica->fetch();
	$user_id = $user_id['ID'];
	
	// Verifica se tem algum erro
	if ( ! $erro ) {
		// Se o usuário existir, atualiza
		if ( ! empty( $user_id ) ) {
			$pdo_insere = $conexao_pdo->prepare('UPDATE users_panel SET user=?,password=?,plan=?,expiry=?,concurrents=?,boottime=?,metodo=?,vip=? WHERE user_id=?');
			$pdo_insere->execute( array(  $form_user,  crypt( $form_password ),$form_plan,$form_expiry,$form_concurrents,$form_boottime,$form_metodo, $form_vip ) );
			
		// Se o usuário não existir, cadastra novo
		} else {
			$pdo_insere = $conexao_pdo->prepare('INSERT INTO users_panel (user,password,plan,expiry,concurrents,boottime,metodo,vip) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
			$pdo_insere->execute( array(  $form_user,  crypt( $form_password ),$form_plan,$form_expiry,$form_concurrents,$form_boottime,$form_metodo, $form_vip ) );
		}
	}
}
?>

<html>
	<head>
		<meta charset="UTF-8">
		
		<title>Login</title>
	</head>
	<body>
		<p>Para editar, apenas digite o nome de usuário que deseja editar.</p>
		<p><a href="../login.php">Clique aqui</a> para testar.</p>
		<form action="" method="post">
			<table>
				<tr>
					<td>user</td>
				</tr>
				<tr>
					<td><input type="text" name="form_user" required></td>
				</tr>
				<tr>
					<td>password</td>
				</tr>
				<tr>
					<td><input type="password" name="form_password" required></td>
				</tr>
				<tr>
					<td>plan</td>
				</tr>
				<tr>
					<td><input type="text" name="form_plan" required></td>
				</tr>
				<tr>
					<td>expiry</td>
				</tr>
				<tr>
					<td><input type="text" name="form_expiry" required></td>
				</tr>
				<tr>
					<td>concurrents</td>
				</tr>
				<tr>
					<td><input type="text" name="form_concurrents" required></td>
				</tr>
				<tr>
					<td>metodo</td>
				</tr>
				<tr>
					<td><input type="text" name="form_metodo" required></td>
				</tr>
				<tr>
					<td>boottime</td>
				</tr>
				<tr>
					<td><input type="text" name="form_boottime" required></td>
				</tr>
				<tr>
					<td>vip</td>
				</tr>
				<tr>
					<td><input type="text" name="form_vip" required></td>
				</tr>
				
				<?php if ( ! empty ( $erro ) ) :?>
					<tr>
						<td style="color: red;"><?php echo $erro;?></td>
					</tr>
				<?php endif; ?>
				
				<tr>
					<td><input type="submit" value="Entrar"></td>
				</tr>
			</table>
		</form>
		
		<?php 
		// Mostra os usuários
		$pdo_verifica = $conexao_pdo->prepare('SELECT * FROM users_panel ORDER BY ID');
		$pdo_verifica->execute();
		?>
		
		<table border="1" cellpadding="5">
		<tr>
			<th>ID</th>
			<th>user</th>
			<th>password</th>
			<th>plan</th>
			<th>expiry</th>
			<th>concurrents</th>
			<th>metodo</th>
			<th>vip</th>
			<th>Ação</th>
		</tr>
		<?php
		while( $fetch = $pdo_verifica->fetch() ) {
			echo '<tr>';
			echo '<td>' . $fetch['ID'] . '</td>';
			echo '<td>' . $fetch['user'] . '</td>';
			echo '<td>' . $fetch['password'] . '</td>';
			echo '<td>' . $fetch['plan'] . '</td>';
			echo '<td>' . $fetch['expiry'] . '</td>';
			echo '<td>' . $fetch['concurrents'] . '</td>';
			echo '<td>' . $fetch['metodo'] . '</td>';
			echo '<td>' . $fetch['vip'] . '</td>';
			echo '<td> <a style="color:red;" href="?del=' . $fetch['ID'] . '">Apagar</a> </td>';
			echo '</tr>';
		}
		?>
		</table>
	</body>
</html>
