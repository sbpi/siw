<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putViagemEnvio
*
* { Description :- 
*    Encaminha a solicitacao
* }
*/

class dml_putViagemEnvio {
   function getInstanceOf($dbms, $p_menu, $p_chave, $p_pessoa, $p_tramite, $p_novo_tramite, $p_devolucao, $p_despacho, 
        $p_justificativa, $p_justif_dia_util) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putViagemEnvio';
     $params=array('p_menu'                      =>array($p_menu,                                B_INTEGER,        32),
                   'p_chave'                     =>array($p_chave,                               B_INTEGER,        32),
                   'p_pessoa'                    =>array($p_pessoa,                              B_INTEGER,        32),
                   'p_tramite'                   =>array($p_tramite,                             B_INTEGER,        32),
                   'p_novo_tramite'              =>array(tvl($p_novo_tramite),                   B_INTEGER,        32),
                   'p_devolucao'                 =>array($p_devolucao,                           B_VARCHAR,         1),
                   'p_despacho'                  =>array(tvl($p_despacho),                       B_VARCHAR,      2000),
                   'p_justificativa'             =>array(tvl($p_justificativa),                  B_VARCHAR,      2000),
                   'p_justif_dia_util'           =>array(tvl($p_justif_dia_util),                B_VARCHAR,      2000)
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
