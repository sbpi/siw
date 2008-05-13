<?
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getAcordoNota
*
* { Description :- 
*    Recupera as autorizações de fornecimento um pedido
* }
*/

class db_getAcordoFornecimento {
   function getInstanceOf($dbms, $p_cliente, $p_chave, $p_chave_aux, $p_material, $p_numero, $p_dt_ini, $p_dt_fim, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getAcordoFornecimento';
     $params=array('p_cliente'              =>array($p_cliente,                              B_INTEGER,        32),
                   'p_chave'                =>array($p_chave,                                B_INTEGER,        32),
                   'p_chave_aux'            =>array(tvl($p_chave_aux),                       B_INTEGER,        32),
                   'p_material'             =>array(tvl($p_material),                        B_INTEGER,        32),
                   'p_numero'               =>array(tvl($p_numero),                          B_VARCHAR,        30),
                   'p_dt_ini'               =>array(tvl($p_dt_ini),                          B_DATE,           32),
                   'p_dt_fim'               =>array(tvl($p_dt_fim),                          B_DATE,           32),
                   'p_restricao'            =>array(tvl($p_restricao),                       B_VARCHAR,        30),
                   'p_result'               =>array(null,                                    B_CURSOR,         -1)
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
