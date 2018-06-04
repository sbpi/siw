<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putNovoTramite
*
* { Description :- 
*    Encaminha a solicitação de um trâmite para outro
* }
*/

class dml_putNovoTramite {
   function getInstanceOf($dbms, $p_menu, $p_solic, $p_pessoa, $p_tramiteAtual, $p_tramiteNovo, $p_destinatario, 
           $p_tipo_log, $p_despacho, $p_observacao, $p_justificativa1, $p_justificativa2) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putNovoTramite';
     $params=array('p_menu'             =>array($p_menu,                  B_INTEGER,        32),
                   'p_solic'            =>array($p_solic,                 B_INTEGER,        32),
                   'p_pessoa'           =>array($p_pessoa,                B_INTEGER,        32),
                   'p_tramiteAtual'     =>array($p_tramiteAtual,          B_INTEGER,        32),
                   'p_tramiteNovo'      =>array(tvl($p_tramiteNovo),      B_INTEGER,        32),
                   'p_destinatario'     =>array($p_destinatario,          B_INTEGER,        32),
                   'p_tipo_log'         =>array(tvl($p_tipo_log),         B_INTEGER,        32),
                   'p_despacho'         =>array($p_despacho,              B_VARCHAR,      2000),
                   'p_observacao'       =>array(tvl($p_observacao),       B_VARCHAR,      2000),
                   'p_justificativa1'   =>array(tvl($p_justificativa1),   B_VARCHAR,      2000),
                   'p_justificativa2'   =>array(tvl($p_justificativa2),   B_VARCHAR,      2000)
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
