<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getCiaTrans
*
* { Description :- 
*    Recupera as companhias de transportes
* }
*/

class db_getCiaTrans {
   function getInstanceOf($dbms, $p_cliente, $p_chave, $p_nome, $p_sigla, $p_aereo, $p_rodoviario, $p_aquaviario, 
        $p_padrao, $p_ativo, $p_chave_aux, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getCiaTrans';
     $params=array('p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,        30),
                   'p_sigla'                     =>array(tvl($p_sigla),                                    B_VARCHAR,        20),
                   'p_aereo'                     =>array(tvl($p_aereo),                                    B_VARCHAR,         1),
                   'p_rodoviario'                =>array(tvl($p_rodoviario),                               B_VARCHAR,         1),
                   'p_aquaviario'                =>array(tvl($p_aquaviario),                               B_VARCHAR,         1),
                   'p_padrao'                    =>array(tvl($p_padrao),                                   B_VARCHAR,         1),
                   'p_ativo'                     =>array(tvl($p_ativo),                                    B_VARCHAR,         1),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
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