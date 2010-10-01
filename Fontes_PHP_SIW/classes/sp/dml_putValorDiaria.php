<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putMeioTrans
*
* { Description :- 
*    Insere os registros dos Valores de Diárias
* }
*/

class dml_putValorDiaria {
     function getInstanceOf($dbms, $p_operacao, $p_cliente, $p_nacional, $p_continente, $p_sq_pais, $p_sq_cidade, 
             $p_sq_moeda, $p_tipo_diaria, $p_categoria, $p_valor, $p_chave) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putValorDiaria';
     $params=array('p_operacao'           => array(tvl($p_operacao),              B_VARCHAR,  1),      
                   'p_cliente'            => array(tvl($p_cliente),               B_INTEGER, 32),     
                   'p_nacional'           => array(tvl($p_nacional),              B_VARCHAR,  1),     
                   'p_continente'         => array(tvl($p_continente),            B_INTEGER, 32),        
                   'p_sq_pais'            => array(tvl($p_sq_pais),               B_INTEGER, 32),              
                   'p_sq_cidade'          => array(tvl($p_sq_cidade),             B_INTEGER, 32),                 
                   'p_sq_moeda'           => array(tvl($p_sq_moeda),              B_INTEGER, 32),       
                   'p_tipo_diaria'        => array(tvl($p_tipo_diaria),           B_VARCHAR,  2),           
                   'p_categoria'          => array(tvl($p_categoria),             B_INTEGER, 32),        
                   'p_valor'              => array(toNumber(tvl($p_valor)),       B_NUMERIC, 18,2),    
                   'p_chave'              => array(tvl($p_chave),                 B_INTEGER, 32)   
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); 
     error_reporting(0); 
     if(!$l_rs->executeQuery()) { error_reporting($l_error_reporting); TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); 
     } else {
       error_reporting($l_error_reporting); 
       return true;
     }
   }
}
?>
