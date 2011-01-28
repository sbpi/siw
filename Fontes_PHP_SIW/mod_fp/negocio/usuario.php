<?php
session_start();
include '../dao/usuarioDAO.class.php';
include '../dao/DAOGeneric.class.php';
include '../dao/retornaDados.php';

$usuario = new Usuario();
$data = new MyDataSource();
$daogeneric = new DAOGeneric();
$retorna = new retornaDados();

	switch($_GET['action']){
		case login:
			$user 		= $_POST['login'];
			$pass 	 	= md5( $_POST['senha'] );
			$tabela	 	= "usuarios";
			$campos	 	= "";
			$campoUser	= "login";
			$campoPass	= "senha";
			$sql = $usuario->login($user, $pass, $tabela, $campos, $campoUser, $campoPass);
			
			$data->ExecDatabase( $sql );
			$data->ViewDatabase();
			
			if( $data->Lista['status'] == "ATIVO" ){
				$_SESSION['SQ_PESSOA'] = $data->Lista['id_usuario'];
				$_SESSION['id_funcionario'] = $data->Lista['id_funcionario'];
				$_SESSION['login'] = $data->Lista['login'];
				header("Location: ../view/painel.php");
			}else{
				header("Location: ../view/login.html");
			}
		break;

		case buscar:
			$tabela = "funcionarios";
			$where = "nome LIKE '%".$_POST['busca']."%'";
			$sql = $retorna->tabelaSQL("",$tabela,$where);
			$data->ExecDatabase( $sql );
			$data->ViewDatabase();
			include '../view/resultadobusca.php';
		break;

		case listar:
			$tabela = array("usuarios", "funcionarios");
			$campos = array("funcionarios.nome","usuarios.id_usuario","usuarios.login");
			$where 	= "usuarios.id_funcionario = funcionarios.id_funcionario";
			$sql = $retorna->tabelasSQL($campos,$tabela,$where);
			$data->ExecDatabase( $sql );
			$data->ViewDatabase();
			include '../view/usuarios.php';
		break;

		case cadastro :
			$id_funcionario = $_GET['id_funcionario'];
			$tabela = "permissoes";
			$sql = $retorna->tabelaSQL("",$tabela,"");
			$data->ExecDatabase( $sql );
			$data->ViewDatabase();
			include '../view/cadastrarusuario.php';
		break;
		
		case cadastrar:
			$tabela			= "usuarios";
			$campos			= array("id_usuario", "id_funcionario", "id_permissao", "login", "senha", "status");
			$camposValores	= array(null, $_POST['id_funcionario'], $_POST['id_permissao'], $_POST['login'], md5($_POST['senha']), $_POST['status']);

			$sql = $daogeneric->incluir($tabela, $campos, $camposValores);
			if( $data->ExecDatabase($sql) )
				header("Location: ../negocio/usuario.php?action=listar");
		break;
		
		case alteracaousuario :
			$tabela = "permissoes";
			$sql = $retorna->tabelaSQL("",$tabela,"");
			$permissao = new MyDataSource();
			$permissao->ExecDatabase( $sql );
			$permissao->ViewDatabase();

			$tabela = array("usuarios", "funcionarios");
			$campo = array("funcionarios.nome","usuarios.id_funcionario","usuarios.login","usuarios.senha","usuarios.id_permissao","usuarios.status");
			$where 	= "usuarios.id_funcionario = funcionarios.id_funcionario AND usuarios.id_usuario = ".$_GET['id'];
			$sql = $retorna->tabelasSQL($campos,$tabela,$where);
			$data->ExecDatabase( $sql );
			$data->ViewDatabase();
			include '../view/alterarusuario.php';
		break;
		
		case alterar:
			$tabela	= "usuarios";
			if( $_POST['senha'] ){
				$campos = array("id_permissao","login", "senha", "status");
				$camposValores = array($_POST['id_permissao'], $_POST['login'], md5($_POST['senha']), $_POST['status']);
			}else{
				$campos = array("id_permissao","login", "status");
				$camposValores = array($_POST['id_permissao'], $_POST['login'], $_POST['status']);
			}
			$campoFiltro = "id_usuario";
			$valorFiltro = $_POST['id_usuario'];
			
			$sql = $daogeneric->alterar($tabela, $campos, $camposValores, $campoFiltro, $valorFiltro);
			if( $data->ExecDatabase($sql) )
				header("Location: ../negocio/usuario.php?action=listar");
		break;
		
		case excluir:
			$tabela = "usuarios";
			$campo 	= "id_usuario";
			$campoValor	= $_GET['id_usuario']; 
			
			$sql = $daogeneric->excluir($tabela, $campo, $campoValor);
			if( $data->ExecDatabase($sql) )
				header("Location: ../negocio/usuario.php?action=listar");
		break;
	
		case logout:
			$usuario->logout();
			header("Location: ../view/login.html");
		break;
	}
?>