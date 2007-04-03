<?
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getAcordoAditivo
*
* { Description :- 
*    Recupera os aditivos de um contrato
* }
*/

class db_getAcordoAditivo {
   function getInstanceOf($dbms, $p_cliente, $p_chave, $p_chave_aux, $p_protocolo, $p_codigo, $p_inicio, $p_fim, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_GETACORDOADITIVO';
     $params=array('p_cliente'                   =>array($p_cliente,                                       B_INTEGER,        32),
                   'p_chave'                     =>array($p_chave,                                         B_INTEGER,        32),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_protocolo'                 =>array($p_protocolo,                                     B_INTEGER,        32),
                   'p_codigo'                    =>array(tvl($p_codigo),                                   B_VARCHAR,        30),
                   'p_inicio'                    =>array(tvl($p_inicio),                                   B_DATE,           32),
                   'p_fim'                       =>array(tvl($p_fim),                                      B_DATE,           32),
                   'p_restricao'                 =>array(tvl($p_restricao),                                B_VARCHAR,        30),
                   'p_result'                    =>array(null,                                             B_CURSOR,         -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
