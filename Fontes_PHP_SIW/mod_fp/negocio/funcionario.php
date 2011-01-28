<?php
session_start();
include '../dao/usuarioDAO.class.php';
include '../dao/DAOGeneric.class.php';
include '../dao/retornaDados.php';

$data = new MyDataSource();
$daogeneric = new DAOGeneric();
$retorna = new retornaDados();

	switch($_GET['action']){
		case buscar:
			$dataDepartamento = new MyDataSource();
			$tabela = "departamentos";
			$sql = $retorna->tabelaSQL("",$tabela,"");
			$dataDepartamento->ExecDatabase( $sql );
			$dataDepartamento->ViewDatabase();
			
			$flag = false;
			$campos = array("f.id_funcionario","f.nome","f.matricula","f.cpf","d.departamento");
			$tabela = array("funcionarios f","departamentos d");
			
			if( $_POST['matricula'] != "" ){
				$where = "f.matricula = '".$_POST['matricula']."'";
				$flag = true;
			}
			
			if( $_POST['nome'] != "" ){
				if($flag){
					$where .= " AND f.nome LIKE '%".$_POST['nome']."%'";
				}else{
					$where = "f.nome LIKE '%".$_POST['nome']."%'";
				}
				$flag = true;
			}
			
			if( $_POST['cpf'] != "" ){
				if($flag){
					$where .= " AND f.cpf = '".$_POST['cpf']."'";
				}else{
					$where = "f.cpf = '".$_POST['cpf']."'";
				}
				$flag = true;
			}
			
			if( $_POST['id_funcionario'] != "" ){
				if($flag){
					$where .= " AND f.id_departamento = '".$_POST['id_funcionario']."'";
				}else{
					$where = "f.id_departamento = '".$_POST['id_funcionario']."'";
				}
				$flag = true;
			}
			
			if( $flag ){
				$where .= " AND f.id_departamento = d.id_departamento";
				$sql = $retorna->tabelasSQL($campos,$tabela,$where);
				$data->ExecDatabase( $sql );
				$data->ViewDatabase();
				include '../view/result_funcionario.php';
			}else{
				include '../view/result_funcionario.php';
			}
		break;
		
		case listar:
			$tabela = "funcionarios";
			$sql = $retorna->tabelaSQL("",$tabela,"");
			$data->ExecDatabase( $sql );
			$data->ViewDatabase();
			include '../view/funcionarios.php';
		break;

		case cadastro:
			$dataDepartamento = new MyDataSource();
			$dataUf = new MyDataSource();

			$tabela = "departamentos";
			$sql = $retorna->tabelaSQL("",$tabela,"");
			$dataDepartamento->ExecDatabase( $sql );
			$dataDepartamento->ViewDatabase();

			$tabela = "uf";
			$sql = $retorna->tabelaSQL("",$tabela,"");
			$dataUf->ExecDatabase( $sql );
			$dataUf->ViewDatabase();

			$tabela = "cargos";
			$sql = $retorna->tabelaSQL("",$tabela,"");
			$data->ExecDatabase( $sql );
			$data->ViewDatabase();
			include '../view/cadastrarfuncionario.php';
		break;

		case cadastrar:
			$tabela			= "funcionarios";
			$campos			= array("id_funcionario", "id_departamento", "matricula", "cpf", "nome", "id_cargo", "id_uf", "cidade", "bairro", "logradouro", "cep", "prefixo_telefone", "telefone", "email", "salariobase");
			$camposValores	= array(null, $_POST['id_departamento'], $_POST['matricula'], $_POST['cpf'], $_POST['nome'], $_POST['id_cargo'], $_POST['id_uf'], $_POST['cidade'], $_POST['bairro'], $_POST['logradouro'], $_POST['cep'], $_POST['prefixo_telefone'], $_POST['telefone'], $_POST['email'], $_POST['salariobase']);
			$sql = $daogeneric->incluir($tabela, $campos, $camposValores);
			if( $data->ExecDatabase( $sql ) )
				header("Location: ../negocio/funcionario.php?action=listar");
		break;

		case alteracao:
			$dataDepartamento = new MyDataSource();
			$dataUf = new MyDataSource();

			$tabela = "departamentos";
			$sql = $retorna->tabelaSQL("",$tabela,"");
			$dataDepartamento->ExecDatabase( $sql );
			$dataDepartamento->ViewDatabase();

			$tabela = "uf";
			$sql = $retorna->tabelaSQL("",$tabela,"");
			$dataUf->ExecDatabase( $sql );
			$dataUf->ViewDatabase();

			$dataCargo = new MyDataSource();
			$tabela = "cargos";
			$sql = $retorna->tabelaSQL("",$tabela,"");
			$dataCargo->ExecDatabase( $sql );
			$dataCargo->ViewDatabase();

			$tabela = "funcionarios";
			$campos = "";
			$where 	= "id_funcionario = ".$_GET['id'];
			$sql = $retorna->tabelaSQL($campos,$tabela,$where);
			$data->ExecDatabase( $sql );
			$data->ViewDatabase();
			include '../view/alterarfuncionario.php';
		break;

		case alterar:
			$tabela	= "funcionarios";
			$campos = array("id_departamento", "matricula", "cpf", "nome", "id_cargo", "id_uf", "cidade", "bairro", "logradouro", "cep", "prefixo_telefone", "telefone", "email", "salariobase");
			$camposValores = array($_POST['id_departamento'], $_POST['matricula'], $_POST['cpf'], $_POST['nome'], $_POST['id_cargo'], $_POST['id_uf'], $_POST['cidade'], $_POST['bairro'], $_POST['logradouro'], $_POST['cep'], $_POST['prefixo_telefone'], $_POST['telefone'], $_POST['email'], $_POST['salariobase']);
			$campoFiltro = "id_funcionario";
			$valorFiltro = $_POST['id_funcionario'];

			$sql = $daogeneric->alterar($tabela, $campos, $camposValores, $campoFiltro, $valorFiltro);
			if( $data->ExecDatabase( $sql ) )
				header("Location: ../negocio/funcionario.php?action=listar");
		break;
		
		case excluir:
			$tabela = "funcionarios";
			$campo 	= "id_funcionario";
			$campoValor	= $_GET['id_funcionario']; 
			$sql = $daogeneric->excluir($tabela, $campo, $campoValor);
			if( $data->ExecDatabase( $sql ) )
				header("Location: ../negocio/funcionario.php?action=listar");
		break;
	}
?>