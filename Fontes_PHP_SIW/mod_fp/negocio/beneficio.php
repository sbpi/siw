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
			$tabela = array("beneficios b", "funcionarios f", "lista_beneficio lb");
			$campos = array("b.id_beneficio","lb.nome","f.nome funcionario","b.periodicidade","b.desconto","b.valor_beneficio");
			$where 	= "lb.id_lista_beneficio = b.id_lista_beneficio AND b.id_funcionario = f.id_funcionario AND b.id_funcionario = ".$_GET['id_funcionario'];
			$sql = $retorna->tabelasSQL($campos,$tabela,$where);
			$data->ExecDatabase( $sql );
			$data->ViewDatabase();
			
			include '../view/beneficios.php';
		break;
		
		case cadastro:
			$tabela = "funcionarios";
			$campos = "";
			$where 	= "id_funcionario = ".$_GET['id_funcionario'];
			$sql 	= $retorna->tabelaSQL($campos,$tabela,$where);
			$data->ExecDatabase( $sql );
			$data->ViewDatabase();
			
			$dataBeneficio = new MyDataSource();
			$tabela = "lista_beneficio";
			$sql 	= $retorna->tabelaSQL("",$tabela,"");
			$dataBeneficio->ExecDatabase( $sql );
			$dataBeneficio->ViewDatabase();
			
			include '../view/cadastrarbeneficio.php';
		break;
		
		case cadastrar:
			$tabela			= "beneficios";
			$campos			= array("id_beneficio", "id_funcionario", "id_lista_beneficio", "periodicidade", "desconto", "valor_beneficio");
			$camposValores	= array(null, $_POST['id_funcionario'], $_POST['id_beneficio'], $_POST['periodicidade'], $_POST['desconto'], $_POST['valor_beneficio']);
			$sql = $daogeneric->incluir($tabela, $campos, $camposValores);
			if( $data->ExecDatabase($sql) )
				header("Location: ../negocio/beneficio.php?action=listar&id_funcionario=".$_POST['id_funcionario']);
		break;
		
		case alteracao:
			$dataBeneficio = new MyDataSource();
			$tabela = "lista_beneficio";
			$sql 	= $retorna->tabelaSQL("",$tabela,"");
			$dataBeneficio->ExecDatabase( $sql );
			$dataBeneficio->ViewDatabase();
			
			$tabela = array("beneficios b", "funcionarios f", "lista_beneficio lb");
			$campos = array("b.id_lista_beneficio","f.nome funcionario","b.id_funcionario","lb.nome","b.periodicidade","b.desconto","b.valor_beneficio","b.id_beneficio");
			$where 	= "lb.id_lista_beneficio = b.id_lista_beneficio AND b.id_funcionario = f.id_funcionario AND b.id_beneficio = ".$_GET['id_beneficio'];
			$sql = $retorna->tabelasSQL($campos,$tabela,$where);
			$data->ExecDatabase( $sql );
			$data->ViewDatabase();
			include '../view/alterarbeneficio.php';
		break;
		
		case alterar:
			$tabela	= "beneficios";
			$campos = array("id_lista_beneficio","periodicidade","desconto","valor_beneficio");
			$camposValores = array($_POST['id_lista_beneficio'],$_POST['periodicidade'],$_POST['desconto'],$_POST['valor_beneficio']);
			$campoFiltro = "id_beneficio";
			$valorFiltro = $_POST['id_beneficio'];
			
			$sql = $daogeneric->alterar($tabela, $campos, $camposValores, $campoFiltro, $valorFiltro);
			if( $data->ExecDatabase( $sql ) )
				header("Location: ../negocio/beneficio.php?action=listar&id_funcionario=".$_POST['id_funcionario']);
		break;
		
		case excluir:
			$tabela = "beneficios";
			$campo 	= "id_beneficio";
			$campoValor	= $_GET['id_beneficio']; 
			$sql = $daogeneric->excluir($tabela, $campo, $campoValor);
			if( $data->ExecDatabase( $sql ) )
				header("Location: ../negocio/beneficio.php?action=listar&id_funcionario=".$_GET['id_funcionario']);
		break;
	}

?>