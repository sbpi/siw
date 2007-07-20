<?
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getContasCronograma
*
* { Description :- 
*    Recupera os crongramas de prestação de contas.
* }
*/

class db_getContasCronograma {
   function getInstanceOf($dbms, $p_chave, $p_siw_solicitacao, $p_prestacao_contas, $p_inicio, $p_fim, $p_limite, $p_tipo, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_GETCONTASCRONOGRAMA';
     $params=array('p_chave'                     =>array($p_chave,                                         B_INTEGER,        32),
                   'p_siw_solicitacao'           =>array(tvl($p_siw_solicitacao),                          B_INTEGER,        32),
                   'p_prestacao_contas'          =>array(tvl($p_prestacao_contas),                         B_INTEGER,        32),
                   'p_inicio'                    =>array(tvl($p_inicio),                                   B_DATE,           32),
                   'p_fim'                       =>array(tvl($p_fim),                                      B_DATE,           32),
                   'p_limite'                    =>array(tvl($p_limite),                                   B_DATE,           32),
                   'p_tipo'                      =>array(tvl($p_tipo),                                     B_VARCHAR,         1),
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
