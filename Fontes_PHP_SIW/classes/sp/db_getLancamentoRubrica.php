<?php
extract($GLOBALS); include_once($w_dir_volta."classes/db/DatabaseQueriesFactory.php");
/**
* class db_getLancamentoRubrica
*
* { Description :- 
*    Recupera os dados da tabela FN_LANCAMENTO_RUBRICA
* }
*/

class db_getLancamentoRubrica {
   function getInstanceOf($dbms, $p_chave, $p_chave_aux, $p_sq_rubrica_origem, $p_sq_rubrica_destino) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_GETLANCAMENTORUBRICA';
     $params=array("p_chave"                =>array($p_chave,               B_NUMERIC,   32),
                   "p_chave_aux"            =>array($p_chave_aux,           B_NUMERIC,   32),
                   "p_sq_rubrica_origem"    =>array($p_sq_rubrica_origem,   B_NUMERIC,   32),
                   "p_sq_rubrica_destino"   =>array($p_sq_rubrica_destino,  B_NUMERIC,   32),
                   "p_result"    =>array(null,           B_CURSOR,    -1)
                  );
     $l_rs = new DatabaseQueriesFactory; $l_rs = $l_rs->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(0); if(!$l_rs->executeQuery()) { error_reporting($l_error_reporting); TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); }
     else {
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
