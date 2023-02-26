<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putSolicOpiniao
*
* { Description :- 
*    Registra a opinião do solicitante quanto ao atendimento da solicitação.
* }
*/

class dml_putSolicOpiniao {
   function getInstanceOf($dbms, $p_chave, $p_opiniao, $p_motivo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTSOLICOPINIAO';
     $params=array('p_chave'                     =>array($p_chave,                                         B_INTEGER,        32),
                   'p_opiniao'                   =>array($p_opiniao,                                       B_INTEGER,        18),
                   'p_motivo'                    =>array($p_motivo,                                        B_VARCHAR,      1000)
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
