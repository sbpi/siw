<?php
include 'mydatasource.class.php';

class Usuario {
	private $idusuario;
	private $login;
	private $idfuncionario;
	
	/*function __construct(){
		$this->idusuario = "0";
		$this->idfuncionario = "0";
		$this->login = "renington";
	}*/
	
	function getIdUsuario(){
		return $this->idusuario;
	}
	function setIdUsuario($idusuario){
		$this->idusuario = $idusuario;
	}
	
	function getLogin(){
		return $this->login;
	}
	function setLogin($login){
		//$this->login = strtoupper(addslashes($login));
		$this->login = $login;
	}
	
	/*function getSenha(){
		return $this->senha;
	}
	function setSenha( $senha ){
		$this->senha = $senha;
		//$this->senha = md5(strtoupper(addslashes( $senha )));
	}*/
	
	function getIdFuncionario(){
		return $this->idfuncionario;
	}
	function setIdFuncionario( $idfuncionario ){
		$this->idfuncionario = $idfuncionario;
	}
	
	function login($usuario, $senha, $tabela, $campos, $campousuario, $campopass ){
		if($campos == "")
			$campos = "*";
			
		$sSql = "select " .$campos." from ".$tabela." where ".$campousuario." = '".$usuario."' and ".$campopass." = '".$senha."'";
		return $sSql;
	}	
	
	function logout() {
		session_unset();
		session_destroy();
		session_unregister("id_usuario");
	}
}
?>