<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getCLSolicItens
*
* { Description :- 
*    Recupera dados da tabela de itens da compra.
* }
*/

class db_getCLSolicItem {
   function getInstanceOf($dbms, $p_chave,$p_solicitacao, $p_material, $p_cancelado, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getCLSolicItem';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_solicitacao'               =>array(tvl($p_solicitacao),                              B_INTEGER,        32),
                   'p_material'                  =>array(tvl($p_material),                                 B_INTEGER,        32),
                   'p_cancelado'                 =>array(tvl($p_cancelado),                                B_VARCHAR,         1),
                   'p_restricao'                 =>array(tvl($p_restricao),                                B_VARCHAR,        15),
                   'p_result'                    =>array(null,                                             B_CURSOR,         -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(0);
     if(!$l_rs->executeQuery()) { error_reporting($l_error_reporting); TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); }
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
