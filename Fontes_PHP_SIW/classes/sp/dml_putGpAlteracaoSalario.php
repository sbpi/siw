<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putGpAlteracaoSalario
*
* { Description :- 
*    Mantém a tabela de parametros
* }
*/

class dml_putGpAlteracaoSalario {
   function getInstanceOf($dbms, $p_operacao, $p_chave, $p_chave_aux, $p_data_alteracao, $p_novo_valor,$p_funcao, $p_motivo){
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'sp_putGpAlteracaoSalario';
     $params=array('p_operacao'                  =>array(tvl($p_operacao),                       B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                          B_INTEGER,        32),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                      B_INTEGER,        32),     
                   'p_data_alteracao'            =>array(tvl($p_data_alteracao),                 B_DATE,           32),
                   'p_novo_valor'                =>array(toNumber(tvl($p_novo_valor)),           B_NUMERIC,      18,2),     
                   'p_funcao'                    =>array(tvl($p_funcao),                         B_VARCHAR,        90),
                   'p_motivo'                    =>array(tvl($p_motivo),                         B_VARCHAR,       255)     
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
