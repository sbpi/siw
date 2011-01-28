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
			$tabela = "departamentos";
			$sql = $retorna->tabelaSQL("",$tabela,"");
			$data->ExecDatabase( $sql );
			$data->ViewDatabase();
			include '../view/departamentos.php';
		break;
		
		case cadastro:
			include '../view/cadastrardepartamento.php';
		break;
		
		case cadastrar:
			$tabela			= "departamentos";
			$campos			= array("id_departamento", "departamento");
			$camposValores	= array(null, $_POST['departamento']);
			$sql = $daogeneric->incluir($tabela, $campos, $camposValores);
			if( $data->ExecDatabase($sql) )
				header("Location: ../view/painel.php");
		break;
		
		case alteracao:
			$tabela = "departamentos";
			$campos = "";
			$where 	= "id_departamento = ".$_GET['id_departamento'];
			$sql = $retorna->tabelaSQL($campos,$tabela,$where);
			$data->ExecDatabase( $sql );
			$data->ViewDatabase();
			include '../view/alterardepartamento.php';
		break;
		
		case alterar:
			$tabela	= "departamentos";
			$campos = "departamento";
			$camposValores = $_POST['departamento'];
			$campoFiltro = "id_departamento";
			$valorFiltro = $_POST['id_departamento'];
			
			$sql = $daogeneric->alterar($tabela, $campos, $camposValores, $campoFiltro, $valorFiltro);
			if( $data->ExecDatabase( $sql ) )
				header("Location: ../view/painel.php");
		break;
		
		case excluir:
			$tabela = "departamentos";
			$campo 	= "id_departamento";
			$campoValor	= $_GET['id_departamento']; 
			$sql = $daogeneric->excluir($tabela, $campo, $campoValor);
			$data->ExecDatabase($sql);
			
			/*// se deletar o departamento, serão atualizadas as tabelas de funcionarios para 0;
			if( $data->ExecDatabase($sql) ){
				
				$data2 = new MyDataSource();
				$daogeneric2 = new DAOGeneric();
				
				$tabela	= "funcionario";
				$campos = "id_departamento";
				$camposValores = "3";
				$campoFiltro = "id_departamento";
				$valorFiltro = $_GET['id_departamento'];
				$sql2 = $daogeneric2->alterar($tabela, $campos, $camposValores, $campoFiltro, $valorFiltro);
				$data2->ExecDatabase($sql2);
			}
			echo $sql2;*/
			header("Location: ../view/painel.php");
		break;
	}
?>