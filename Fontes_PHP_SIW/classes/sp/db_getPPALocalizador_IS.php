<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getPPALocalizador_IS
*
* { Description :- 
*    Recupera as restrições de uma ação
* }
*/

class db_getPPALocalizador_IS {
   function getInstanceOf($dbms, $cliente, $ano, $p_programa, $p_acao, $p_unidade, $p_subacao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_GETPPALOCALIZADOR_IS';
     $params=array('p_cliente'                   =>array(tvl($cliente),                                    B_INTEGER,        32),
                   'p_ano'                       =>array(tvl($ano),                                        B_INTEGER,        32),
                   'p_programa'                  =>array(tvl($p_programa),                                 B_VARCHAR,         4),
                   'p_acao'                      =>array(tvl($p_acao),                                     B_VARCHAR,         4),
                   'p_unidade'                   =>array(tvl($p_unidade),                                  B_VARCHAR,         5),
                   'p_subacao'                   =>array(tvl($p_subacao),                                  B_VARCHAR,         4),
                   'p_result'                    =>array(null,                                             B_CURSOR,         -1)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(E_ERROR);
     if(!$l_rs->executeQuery()) {
       error_reporting($l_error_reporting);
       TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__);
     } else {
       error_reporting($l_error_reporting); 
       if ($l_rs = $l_rs->getResultData()) {
         return $l_rs;
       } else {
         return array();
       }
     }
   }
}
?>
