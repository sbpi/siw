<?php
session_start();
include '../dao/mydatasource.class.php';
include '../dao/DAOGeneric.class.php';
include '../dao/retornaDados.php';

$data = new MyDataSource();
$daogeneric = new DAOGeneric();
$retorna = new retornaDados();

	switch($_GET['action']){
		case listar:
			$tabela = "cargos";
			$sql = $retorna->tabelaSQL("",$tabela,"");
			$data->ExecDatabase( $sql );
			$data->ViewDatabase();
			include '../view/cargos.php';
		break;
		
		case cadastro:
			include '../view/cadastrarcargo.php';
		break;
		
		case cadastrar:
			$tabela			= "cargos";
			$campos			= array("id_cargo", "nome");
			$camposValores	= array(null, $_POST['nome']);
			$sql = $daogeneric->incluir($tabela, $campos, $camposValores);
			if( $data->ExecDatabase($sql) )
				header("Location: ../view/painel.php");
		break;
		
		case alteracao:
			$tabela = "cargos";
			$campos = "";
			$where 	= "id_cargo = ".$_GET['id_cargo'];
			$sql = $retorna->tabelaSQL($campos,$tabela,$where);
			$data->ExecDatabase( $sql );
			$data->ViewDatabase();
			include '../view/alterarcargo.php';
		break;
		
		case alterar:
			$tabela	= "cargos";
			$campos = "nome";
			$camposValores = $_POST['nome'];
			$campoFiltro = "id_cargo";
			$valorFiltro = $_POST['id_cargo'];
			
			$sql = $daogeneric->alterar($tabela, $campos, $camposValores, $campoFiltro, $valorFiltro);
			if( $data->ExecDatabase( $sql ) )
				header("Location: ../view/painel.php");
		break;
		
		case excluir:
			$tabela = "cargos";
			$campo 	= "id_cargo";
			$campoValor	= $_GET['id_cargo']; 
			$sql = $daogeneric->excluir($tabela, $campo, $campoValor);
			$data->ExecDatabase( $sql );
			header("Location: ../view/painel.php");
		break;
	}
?>