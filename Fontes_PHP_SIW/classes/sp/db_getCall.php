<?
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getCall
*
* { Description :- 
*    Recupera a lista de clientes do SIW
* }
*/

class db_getCall {
   function getInstanceOf($dbms, $p_chave, $p_pessoa, $p_tipo, $p_restricao, $p_sq_cc, $p_contato, $p_numero, $p_inicio, $p_fim, $p_ativo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_GETCALL';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_pessoa'                    =>array($p_pessoa,                                        B_INTEGER,        32),
                   'p_tipo'                      =>array($p_tipo,                                          B_INTEGER,        32),
                   'p_restricao'                 =>array(tvl($p_restricao),                                B_VARCHAR,        20),
                   'p_sq_cc'                     =>array(tvl($p_sq_cc),                                    B_INTEGER,        32),
                   'p_contato'                   =>array(tvl($p_contato),                                  B_VARCHAR,        60),
                   'p_numero'                    =>array(tvl($p_numero),                                   B_VARCHAR,        20),
                   'p_inicio'                    =>array(tvl($p_inicio),                                   B_DATE,           32),
                   'p_fim'                       =>array(tvl($p_fim),                                      B_DATE,           32),
                   'p_ativo'                     =>array(tvl($p_ativo),                                    B_VARCHAR,         1),
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
