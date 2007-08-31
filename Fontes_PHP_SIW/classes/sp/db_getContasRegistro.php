<?
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getContasRegisro
*
* { Description :- 
*    Recupera os registros de um cronograma de prestação de contas
* }
*/

class db_getContasRegistro {
   function getInstanceOf($dbms, $p_chave, $p_prestacao_contas, $p_contas_cronograma, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_GETCONTASREGISTRO';
     $params=array('p_chave'                     =>array($p_chave,                                         B_INTEGER,        32),
                   'p_prestacao_contas'          =>array(tvl($p_prestacao_contas),                         B_INTEGER,        32),
                   'p_contas_cronograma'         =>array(tvl($p_contas_cronograma),                        B_INTEGER,        32),
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
