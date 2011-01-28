<?php
session_start();
include '../dao/mydatasource.class.php';
include '../dao/DAOGeneric.class.php';
include '../dao/retornaDados.php';

$data = new MyDataSource();
$daogeneric = new DAOGeneric();
$retorna = new retornaDados();

	switch($_GET['action']){
		case "gerarprevia":
			$tabela = "funcionarios";
			$campos = "";
			$where 	= "id_funcionario = ".$_GET['id'];
			$sql = $retorna->tabelaSQL($campos,$tabela,$where);
			$data->ExecDatabase( $sql );
			$data->ViewDatabase();
			
			$dataBeneficio = new MyDataSource();
			$tabelaBeneficio = "beneficios";
			$whereBeneficio 	= "id_funcionario = ".$_GET['id'];
			$sql = $retorna->tabelaSQL("",$tabelaBeneficio,$whereBeneficio);
			$dataBeneficio->ExecDatabase( $sql );
			$dataBeneficio->ViewDatabase();
			include '../view/resultado.php';
		break;
	}
?>