<?php
session_start();
include '../dao/usuarioDAO.class.php';
include '../dao/DAOGeneric.class.php';
include '../dao/retornaDados.php';

$data = new MyDataSource();
$daogeneric = new DAOGeneric();
$retorna = new retornaDados();

	switch($_GET['action']){
		case listar:
			$tabela = "permissoes";
			$sql = $retorna->tabelaSQL("",$tabela,"");
			$data->ExecDatabase( $sql );
			$data->ViewDatabase();
			include '../view/permissoes.php';
		break;
		
		case cadastro:
			include '../view/cadastrarpermissao.php';
		break;
		
		case cadastrar:
			$tabela			= "permissoes";
			$campos			= array("id_permissao", "permissao");
			$camposValores	= array(null, $_POST['permissao']);
			$sql = $daogeneric->incluir($tabela, $campos, $camposValores);
			if( $data->ExecDatabase($sql) )
				header("Location: ../negocio/permissao.php?action=listar");
		break;
		
		case alteracao:
			$tabela = "permissoes";
			$campos = "";
			$where 	= "id_permissao = ".$_GET['id_permissao'];
			$sql = $retorna->tabelaSQL($campos,$tabela,$where);
			$data->ExecDatabase( $sql );
			$data->ViewDatabase();
			include '../view/alterarpermissao.php';
		break;
		
		case alterar:
			$tabela	= "permissoes";
			$campos = "permissao";
			$camposValores = $_POST['permissao'];
			$campoFiltro = "id_permissao";
			$valorFiltro = $_POST['id_permissao'];
			
			$sql = $daogeneric->alterar($tabela, $campos, $camposValores, $campoFiltro, $valorFiltro);
			if( $data->ExecDatabase($sql) )
				header("Location: ../negocio/permissao.php?action=listar");
		break;
		
		case excluir:
			$tabela = "permissoes";
			$campo 	= "id_permissao";
			$campoValor	= $_GET['id_permissao']; 
			$sql = $daogeneric->excluir($tabela, $campo, $campoValor);
			if( $data->ExecDatabase($sql) )
				header("Location: ../negocio/permissao.php?action=listar");
		break;
	}
?>