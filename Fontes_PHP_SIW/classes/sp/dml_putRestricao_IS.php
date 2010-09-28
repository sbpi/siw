<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putRestricao_IS
*
* { Description :- 
*    Mantém a tabela IS_RESTRICAO
* }
*/

class dml_putRestricao_IS {
   function getInstanceOf($dbms, $operacao, $p_restricao, $p_chave, $p_chave_aux, $p_cd_subacao, $p_sq_isprojeto, $p_cd_tipo_restricao, $p_cd_tipo_inclusao, $p_cd_competencia, $p_superacao, $p_relatorio, $p_tempo_habil, $p_descricao, $p_providencia, $p_observacao_controle, $p_observacao_monitor, $p_ano, $p_cliente) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTRESTRICAO_IS';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_restricao'                 =>array($p_restricao,                                     B_VARCHAR,        11),
                   'p_chave'                     =>array($p_chave,                                         B_INTEGER,        32),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_cd_subacao'                =>array(tvl($p_cd_subacao),                               B_VARCHAR,         4),
                   'p_sq_isprojeto'              =>array(tvl($p_sq_isprojeto),                             B_INTEGER,        32),
                   'p_cd_tipo_restricao'         =>array(tvl($p_cd_tipo_restricao),                        B_INTEGER,        32),
                   'p_cd_tipo_inclusao'          =>array(tvl($p_cd_tipo_inclusao),                         B_VARCHAR,         2),
                   'p_cd_competencia'            =>array($p_cd_competencia,                                B_VARCHAR,         2),
                   'p_superacao'                 =>array(tvl($p_superacao),                                B_DATE,           32),
                   'p_relatorio'                 =>array(tvl($p_relatorio),                                B_VARCHAR,         1),
                   'p_tempo_habil'               =>array(tvl($p_tempo_habil),                              B_VARCHAR,         1),
                   'p_descricao'                 =>array(tvl($p_descricao),                                B_VARCHAR,      4000),
                   'p_providencia'               =>array(tvl($p_providencia),                              B_VARCHAR,      4000),
                   'p_observacao_controle'       =>array(tvl($p_observacao_controle),                      B_VARCHAR,      4000),
                   'p_observacao_monitor'        =>array(tvl($p_observacao_monitor),                       B_VARCHAR,      4000),
                   'p_ano'                       =>array(tvl($p_ano),                                      B_INTEGER,        32),
                   'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32)
                  );
     $l_rs = new DatabaseQueriesFactory; $l_rs = $l_rs->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); 
     error_reporting(0); 
     if(!$l_rs->executeQuery()) { 
       error_reporting($l_error_reporting); 
       TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); 
     } else {
       error_reporting($l_error_reporting); 
       return true;
     }
   }
}
?>
