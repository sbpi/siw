<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putIndice 
*
* { Description :- 
*    Mant�m a tabela de �ndice
* }
*/

class dml_putIndice  {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_sq_indice_tipo, $p_sq_usuario, $p_sq_sistema, $p_nome, $p_descricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTINDICE';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_sq_indice_tipo'            =>array(tvl($p_sq_indice_tipo),                           B_INTEGER,        32),
                   'p_sq_usuario'                =>array(tvl($p_sq_usuario),                               B_INTEGER,        32),
                   'p_sq_sistema'                =>array(tvl($p_sq_sistema),                               B_INTEGER,        32),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,        30),
                   'p_descricao'                 =>array(tvl($p_descricao),                                B_VARCHAR,      4000)                   
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(E_ERROR); 
     if(!$l_rs->executeQuery()) { 
       error_reporting($l_error_reporting); 
       TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); 
     } else {
       error_reporting($l_error_reporting); 
       return true;
     }
   }
}
?>
