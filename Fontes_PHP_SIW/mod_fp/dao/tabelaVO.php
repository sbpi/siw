<?php

class tabelaVO{
	private $nomeTab;
    private $nomeId;
    private $valorId;
    private $nomeCampos;
    private $valorCampos;
    private $array;
    
    function getNomeTab(){ return $this->nomeTab; }
	function getNomeId(){ return $this->nomeId; }
	function getValorId(){ return $this->valorId; }
	function getNomeCampos(){ return $this->nomeCampos; }
	function getValorCampos(){ return $this->valorCampos; }
	function getArray(){ return $this->array; }

	function setNomeTab( $nomeTab ){
		$this->nomeTab = $nomeTab;
	}
	function setNomeId( $nomeId ){
		$this->nomeId = $nomeId;
	}
	function setValorId( $valorId ){
		$this->valorId = $valorId;
	}
	function setNomeCampos( $nomeCampos ){
		$this->nomeCampos = $nomeCampos;
	}
	function setValorCampos( $valorCampos ){
		$this->valorCampos = $valorCampos;
	}
	function setArray( $array ){
		$this->array = $array;
	}
}
?>