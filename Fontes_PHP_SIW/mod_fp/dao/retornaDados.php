<?php
	class retornaDados {
		/*function __construct(){
		}*/
		
		function getRetornaValores($campos, $tabela, $where){
			$resultado = $this->tabelaSQL($campos, $tabela, $where);
        return $resultado;
    	}
		
		function getDados($campos, $tabela, $where, $order){
			$resultado = tabelasSQL($campos, $tabela, $where);
		}
		
		
		function tabelaSQL($campos, $tabela, $where){
			if(!$campos)
				$campos = "*";
			else
				$campos = implode(",", $campos);
			
			if($where != NULL && count($where) > 0 )
				$sql = "SELECT ".$campos." FROM ".$tabela." WHERE ". $where;
			else
				$sql = "SELECT ".$campos." FROM ".$tabela;
			return $sql;
		}
		
		function tabelasSQL($campos, $tabela, $where){
			if(!$campos)
				$campos = "*";
			else
				$campos = implode(",", $campos);
			$tabela = implode(",", $tabela);
			
			if($where != NULL && count($where) > 0 )
				$sql = "SELECT ".$campos." FROM ".$tabela." WHERE ". $where;
			else
				$sql = "SELECT ".$campos." FROM ".$tabela;
			return $sql;
		}
		
		
		function tabelasSQLxxxxxxx( $field, $tabela, $where, $order = null ){
			$fields = "";
			
			for($i=0; $i <= count($field) -1; $i++)
				$fields = $fields . $field[$i]. " , ";
			if( count($field) > 1 )
				if( $field == null )
					$order = " order by ". $field[1];
				else
					$order = "order by ". $order;
			$fields = substr($fields, 0, count($fields)-2);
			if( $where != null && count($where)>0 )
				$resultado = "SELECT ".$fields." FROM ".$tabela." WHERE ".$where.$order;
			else
				$resultado = "SELECT ".$fields." FROM ".$tabela;
			return $resultado;
		}
	}
?>