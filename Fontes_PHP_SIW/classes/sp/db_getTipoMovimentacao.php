<?
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getTipoMovimentacao
*
* { Description :- 
*    Recupera os tipos de movimentação
* }
*/

class db_getTipoMovimentacao {
   function getInstanceOf($dbms, $p_cliente, $p_chave, $p_nome, $p_entrada, $p_saida, $p_orcamentario, $p_consumo, $p_permanente, $p_inativa_bem, $p_ativo, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_GetTipoMovimentacao';
     $params=array('p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,        60),
                   'p_entrada'                   =>array(tvl($p_entrada),                                  B_VARCHAR,         1),
                   'p_saida'                     =>array(tvl($p_saida),                                    B_VARCHAR,         1),
                   'p_orcamentario'              =>array(tvl($p_orcamentario),                             B_VARCHAR,         1),
                   'p_consumo'                   =>array(tvl($p_consumo),                                  B_VARCHAR,         1),
                   'p_permanente'                =>array(tvl($p_permanente),                               B_VARCHAR,         1),
                   'p_inativa_bem'               =>array(tvl($p_inativa_bem),                              B_VARCHAR,         1),
                   'p_ativo'                     =>array(tvl($p_ativo),                                    B_VARCHAR,         1),
                   'p_restricao'                 =>array(tvl($p_restricao),                                B_VARCHAR,        30),
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
