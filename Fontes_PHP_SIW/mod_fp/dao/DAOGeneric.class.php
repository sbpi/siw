<?php
	class DAOGeneric {
		
		private $tabela;		//construir tabela			TabelaVO tabela;
		private $tabelas;		//construir tabelas			private TabelaVO[] tabelas;
		private $MyDataSource;	//construir MyDataSource 	private MyDataSource data = new MyDataSource();
		private $id;
		private $campo;		
		
		function getId(){
			return $this->id;
		}
		function setId( $id ){
			$this->id = $id;
		}
		
		function getCampo(){
			return $this->campo;
		}
		function setCampo( $campo ){
			$this->campo = $campo;
		}
		
		function getTabela(){
			return $this->tabela;
		}
		function setTabela( $tabela ){
			$this->tabela = $tabela;
		}
		
		function getTabelas(){
			return $this->tabelas;
		}
		function setTabelas( $tabelas ){
			$this->tabelas = $tabelas;
		}
		
		function incluir($tabela, $campos, $camposValores){
			$campos = implode(",", $campos);
			$camposValores = "'".implode("','", $camposValores)."'";
		
			$sSql = "insert into ".$tabela." ( ".$campos." ) values ( " .$camposValores." )";
			return $sSql;
		}
		
		function alterar($tabela, $campos, $camposValores, $campoFiltro, $valorFiltro){
			if($tabela && $campos && $camposValores && $campoFiltro && $valorFiltro){
				if( is_array($campos) ){
					for($i=0; $i < count($campos); $i++)
						$array[$i] = $campos[$i]." = '".$camposValores[$i]."'";
					$nomeCampos = implode(",", $array);
				}else{
					$nomeCampos = $campos." = '".$camposValores."'";
				}
			$sSql = "UPDATE ".$tabela." SET ".$nomeCampos." WHERE ".$campoFiltro." = '".$valorFiltro."'";
			return $sSql;
			}
		}
		
		function excluir($tabela, $campo, $campoValor){
			$sSql = "DELETE FROM ".$tabela." WHERE ".$campo." = '".$campoValor."'";
			return $sSql;
		}
	}
?>