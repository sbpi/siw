<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getViagemBenef
*
* { Description :- 
*    Recupera a lista de viajantes de um projeto
* }
*/

class db_getViagemBenef {
   function getInstanceOf($dbms, $p_chave, $p_cliente, $p_pessoa, $p_restricao, $p_cpf, $p_nome, $p_dt_ini, $p_dt_fim, $p_chave_aux) {
     $sql=$strschema.'SP_GETVIAGEMBENEF';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_cliente'                   =>array($p_cliente,                                       B_INTEGER,        32),
                   'p_pessoa'                    =>array(tvl($p_pessoa),                                   B_INTEGER,        32),
                   'p_restricao'                 =>array(tvl($p_restricao),                                B_VARCHAR,        50),
                   'p_cpf'                       =>array(tvl($p_cpf),                                      B_VARCHAR,        14),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,        20),
                   'p_dt_ini'                    =>array(tvl($p_dt_ini),                                   B_DATE,           32),
                   'p_dt_fim'                    =>array(tvl($p_dt_fim),                                   B_DATE,           32),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
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
