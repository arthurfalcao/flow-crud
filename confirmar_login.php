<?php
require_once 'config.php';
require_once 'banco-usuario.php';
require_once 'logica-usuario.php';

$usuario = buscaUsuario($conexao, $_POST["email"], $_POST["senha"]);
if ($usuario == null) {
	$_SESSION["danger"] = "Usuário ou senha inválida.";
	header("Location: login.php");
} else {
	$_SESSION["success"] = "Usuário logado com sucesso.";
	loginUser($usuario["email"]);
	header("Location: login.php");
}
die();

$email = isset($_POST['email']) ? $_POST['email'] : '';
$senha = isset($_POST['senha']) ? md5($_POST['senha']) : '';

if (empty($email) || empty($senha)) {
	echo "<script>alert('Email ou senha incorretos'); history.back();</script>";
	exit;
}

$stmt = $conexao->prepare("SELECT * FROM T_USUARIO WHERE email = ? AND senha = ?");
$stmt->bindParam(1, $email);
$stmt->bindParam(2, $senha);

$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($users) <= 0){
    echo "<script>alert('Email ou senha incorretos'); history.back();</script>";
    exit;
}

$user = $users[0];

session_start();
$_SESSION['logged_in'] = true;
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['nome'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_cidade'] = $user['cidade'];
$_SESSION['user_uf'] = $user['uf'];

echo "<script>alert('Usuário logado com sucesso');</script>";
echo "<script language=\"javascript\">window.location=\"login.php\";</script>";

 ?>
