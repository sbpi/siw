<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putTTCentral
*
* { Description :- 
*    Mantém a tabela de centrais telefônicas
* }
*/

class dml_putTTCentral {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_cliente, $p_sq_pessoa_endereco, $p_arquivo_bilhetes, $p_recupera_bilhetes) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTTTCENTRAL';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        18),
                   'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        18),
                   'p_sq_pessoa_endereco'        =>array(tvl($p_sq_pessoa_endereco),                       B_INTEGER,        18),
                   'p_arquivo_bilhetes'          =>array(tvl($p_arquivo_bilhetes),                         B_VARCHAR,        60),
                   'p_recupera_bilhetes'         =>array(tvl($p_recupera_bilhetes),                        B_VARCHAR,         1)
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
