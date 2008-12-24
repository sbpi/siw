<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getPD_Financeiro
*
* { Description :- 
*    Recupera as possibilidades de vincula��o financeira
* }
*/

class db_getPD_Financeiro {
   function getInstanceOf($dbms, $p_cliente, $p_chave, $p_solic, $p_rubrica, $p_lancamento, $p_diaria, $p_hospedagem, 
        $p_veiculo, $p_seguro, $p_bilhete, $p_reembolso, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getPD_Financeiro';
     $params=array('p_cliente'              =>array(tvl($p_cliente),                            B_INTEGER,        32),
                   'p_chave'                =>array(tvl($p_chave),                              B_INTEGER,        32),
                   'p_solic'                =>array(tvl($p_solic),                              B_INTEGER,        32),
                   'p_rubrica'              =>array(tvl($p_rubrica),                            B_INTEGER,        32),
                   'p_lancamento'           =>array(tvl($p_lancamento),                         B_INTEGER,        32),
                   'p_diaria'               =>array(tvl($p_diaria),                             B_VARCHAR,         1),
                   'p_hospedagem'           =>array(tvl($p_hospedagem),                         B_VARCHAR,         1),
                   'p_veiculo'              =>array(tvl($p_veiculo),                            B_VARCHAR,         1),
                   'p_seguro'               =>array(tvl($p_seguro),                             B_VARCHAR,         1),
                   'p_bilhete'              =>array(tvl($p_bilhete),                            B_VARCHAR,         1),
                   'p_reembolso'            =>array(tvl($p_reembolso),                          B_VARCHAR,         1),
                   'p_restricao'            =>array(tvl($p_restricao),                          B_VARCHAR,        30),
                   'p_result'               =>array(null,                                       B_CURSOR,         -1)
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
