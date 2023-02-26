<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putTTRamalUsuario
*
* { Description :- 
*    Mantém a tabela de centrais telefônicas
* }
*/

class dml_putTTRamalUsuario {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_chave_aux, $p_chave_aux2, $p_inicio, $p_fim) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTTTRAMALUSUARIO';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array($p_chave,                                         B_INTEGER,        18),
                   'p_chave_aux'                 =>array($p_chave_aux,                                     B_INTEGER,        18),
                   'p_chave_aux2'                =>array(nvl($p_chave_aux2,$p_inicio),                     B_DATE,           32),
                   'p_inicio'                    =>array(tvl($p_inicio),                                   B_DATE,           32),
                   'p_fim'                       =>array(tvl($p_fim),                                      B_DATE,           32)
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
