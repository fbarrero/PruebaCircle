<?php 
	
	require 'admin/config.php';
	require 'funciones.php';

	session_start();
	if (isset($_SESSION['usuario'])) {
		header('Location: registroHorasv2.php');
	}

	$conexion = conexion($bd_config);
	$username = '';
	$errores = '';

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$username = limpiarDatos($_POST['usuarioSesion']);
		$passcode = limpiarDatos($_POST['passSesion']);
		if (empty($username)) {
			$errores = '<li>Por favor ingrese un usuario</li>';
		}if(empty($passcode)){
			$errores = $errores . '<li>Por favor ingrese la contraseña</li>';
		}
		if (!empty($username) && !empty($passcode)) {
			$resultado = validarLogin($username,$passcode);
			$cont = 0;
			while ($fila = mysql_fetch_array($resultado)) {
				$cont = $cont + 1;
				$_SESSION['rol'] = $fila['rol'];
			}
			if ($cont!=1) {
				$errores = '<li>Usuario y/o contraseña incorrectos</li>';
			}else{
				$_SESSION['usuario'] = $username;
				setcookie("uservmca",$username,0);
				if (strcmp($passcode,'vmca2017') === 0) {
					$_SESSION['restablecer'] = true;					
					header('Location: restablecerPassword.php');
				}else{
					header('Location: registroHorasv2.php');
					
				}
			}
		}
	}

	require 'views/login.view.php';
?>