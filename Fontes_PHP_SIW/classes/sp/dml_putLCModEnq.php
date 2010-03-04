<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putLCModEnq
*
* { Description :- 
*    Mantém a tabela de enquadramentos de uma situação de certame
* }
*/

class dml_putLCModEnq {
   function getInstanceOf($dbms,$operacao,$p_chave, $p_chave_aux, $p_sigla, $p_descricao, $p_ativo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putLCModEnq';
     $params=array('p_operacao'          =>array($operacao,                                B_VARCHAR,         1),
                   'p_chave'             =>array(tvl($p_chave),                            B_INTEGER,        32),
                   'p_chave_aux'         =>array(tvl($p_chave_aux),                        B_INTEGER,        32),
                   'p_sigla'             =>array(tvl($p_sigla),                            B_VARCHAR,        20),
                   'p_descricao'         =>array(tvl($p_descricao),                        B_VARCHAR,       255),
                   'p_ativo'             =>array($p_ativo,                                 B_VARCHAR,         1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
