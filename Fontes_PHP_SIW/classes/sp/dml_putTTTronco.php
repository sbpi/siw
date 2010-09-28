<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putTTTronco
*
* { Description :- 
*    Mantém a tabela de centrais telefônicas
* }
*/

class dml_putTTTronco {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_cliente, $p_sq_central_fone, $p_sq_pessoa_telefone, $p_codigo, $p_ativo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTTTTRONCO';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        18),
                   'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        18),
                   'p_sq_centrap_fone'           =>array(tvl($p_sq_central_fone),                          B_INTEGER,        18),
                   'p_sq_pessoa_telefone'        =>array(tvl($p_sq_pessoa_telefone),                       B_INTEGER,        18),
                   'p_codigo'                    =>array(tvl($p_codigo),                                   B_VARCHAR,        10),
                   'p_ativo'                     =>array(tvl($p_ativo),                                    B_VARCHAR,         1)
                  );
     $l_rs = new DatabaseQueriesFactory; $l_rs = $l_rs->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); 
     error_reporting(0); 
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
