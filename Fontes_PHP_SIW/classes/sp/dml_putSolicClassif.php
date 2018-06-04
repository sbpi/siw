<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putSolicClassif
*
* { Description :- 
*    Executa procedure para ajuste de rubrica e fonte de recursos de lançamento financeiro.
* }
*/

class dml_putSolicClassif {
   function getInstanceOf($dbms, $p_usuario, $p_observacao, $p_rubrica, $p_fonte, $p_lancamento, $p_item) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putSolicClassif';
     $params=array('p_usuario'         =>array($p_usuario,         B_NUMERIC,     32),
                   'p_observacao'      =>array($p_observacao,      B_VARCHAR,   2000),
                   'p_rubrica'         =>array($p_rubrica,         B_NUMERIC,     32),
                   'p_fonte'           =>array($p_fonte,           B_NUMERIC,     32),
                   'p_lancamento'      =>array($p_lancamento,      B_NUMERIC,     32),
                   'p_item'            =>array($p_item,            B_NUMERIC,     32)
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
