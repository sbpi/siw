<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getConvOutroRep
*
* { Description :- 
*    Recupera os representantes de uma solicitacao
* }
*/

class db_getConvOutroRep {
   function getInstanceOf($dbms, $p_chave, $p_sq_pessoa, $p_chave_aux, $p_tipo ) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_GetConvOutroRep';
     $params=array('p_chave'                     =>array($p_chave,                                         B_INTEGER,        32),
                   'p_sq_pessoa'                 =>array(tvl($p_sq_pessoa),                                B_INTEGER,        32),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_tipo'                      =>array(tvl($p_tipo),                                     B_INTEGER,        32),
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
