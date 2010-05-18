<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getGPContrato
*
* { Description :- 
*    Recupera os contratos dos colaboradores
* }
*/

class db_getGPContrato {
   function getInstanceOf($dbms, $p_cliente, $p_chave, $p_sq_pessoa, $p_modalidade_contrato, $p_unidade_lotacao, $p_filhos_lotacao, $p_unidade_exercicio, $p_filhos_exercicio, $p_afastamento, $p_dt_ini, $p_dt_fim, $p_data, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'SP_GETGPCONTRATO';
     $params=array('p_cliente'                   =>array($p_cliente,                                       B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_sq_pessoa'                 =>array(tvl($p_sq_pessoa),                                B_INTEGER,        32),
                   'p_modalidade_contrato'       =>array(tvl($p_modalidade_contrato),                      B_INTEGER,        32),
                   'p_unidade_lotacao'           =>array(tvl($p_unidade_lotacao),                          B_INTEGER,        32),
                   'p_filhos_lotacao'            =>array(tvl($p_filhos_lotacao),                           B_VARCHAR,         1),
                   'p_unidade_exercicio'         =>array(tvl($p_unidade_exercicio),                        B_INTEGER,        32),
                   'p_filhos_exercicio'          =>array(tvl($p_filhos_exercicio),                         B_VARCHAR,         1),
                   'p_afastamento'               =>array(tvl($p_afastamento),                              B_VARCHAR,      1000),
                   'p_dt_ini'                    =>array(tvl($p_dt_ini),                                   B_DATE,           32),
                   'p_dt_fim'                    =>array(tvl($p_dt_fim),                                   B_DATE,           32),
                   'p_data'                      =>array(tvl($p_data),                                     B_DATE,           32),
                   'p_restricao'                 =>array(tvl($p_restricao),                                B_VARCHAR,        20),
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
