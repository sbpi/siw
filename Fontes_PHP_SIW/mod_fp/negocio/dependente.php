<?php
session_start();
include '../dao/mydatasource.class.php';
include '../dao/DAOGeneric.class.php';
include '../dao/retornaDados.php';

$data = new MyDataSource();
$daogeneric = new DAOGeneric();
$retorna = new retornaDados();

	switch( $_GET['action'] ){
		case listar:
			if( !isset($_GET['id_funcionario']) ) //Ao tentar acessar diretamente, redirecionará para evitar erro.
				header("Location: ../negocio/funcionario.php?action=listar");
			$tabela = "dependentes";
			$where = "id_funcionario = ".$_GET['id_funcionario'];
			$sql = $retorna->tabelaSQL("",$tabela,$where);
			$data->ExecDatabase( $sql );
			$data->ViewDatabase();
			include '../view/dependentes.php';
		break;

		case cadastro:
			include '../view/cadastrardependente.php';
		break;

		case cadastrar:
			$tabela			= "dependentes";
			$campos			= array("id_dependente", "id_funcionario", "nome", "parentesco");
			$camposValores	= array(null, $_POST['id_funcionario'], $_POST['nome'], $_POST['parentesco']);
			$sql = $daogeneric->incluir($tabela, $campos, $camposValores);
			if( $data->ExecDatabase($sql) )
				header("Location: ../negocio/dependente.php?action=listar&id_funcionario=".$_POST['id_funcionario']);
		break;

		case alteracao:
			$tabela = "dependentes";
			$campos = "";
			$where 	= "id_dependente = ".$_GET['id_dependente'];
			$sql = $retorna->tabelaSQL($campos,$tabela,$where);
			$data->ExecDatabase( $sql );
			$data->ViewDatabase();
			include '../view/alterardependente.php';
		break;

		case alterar:
			$tabela	= "dependentes";
			$campos = array("id_funcionario", "nome", "parentesco");
			$camposValores = array($_POST['id_funcionario'], $_POST['nome'], $_POST['parentesco']);
			$campoFiltro = "id_dependente";
			$valorFiltro = $_POST['id_dependente'];
			
			$sql = $daogeneric->alterar($tabela, $campos, $camposValores, $campoFiltro, $valorFiltro);
			if( $data->ExecDatabase( $sql ) )
				header("Location: ../negocio/dependente.php?action=listar&id_funcionario=".$_POST['id_funcionario']);
		break;

		case excluir:
			$tabela = "dependentes";
			$campo 	= "id_dependente";
			$campoValor	= $_GET['id_dependente']; 
			$sql = $daogeneric->excluir($tabela, $campo, $campoValor);
			if( $data->ExecDatabase( $sql ) )
				header("Location: ../negocio/dependente.php?action=listar&id_funcionario=".$_GET['id_funcionario']);
		break;
	}
?>