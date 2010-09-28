<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putXMLRestricaoAcao_SIG
*
* { Description :- 
*    Mantém a tabela SIG - Restricao Acao
* }
*/

class dml_putXMLRestricaoAcao_SIG {
   function getInstanceOf($dbms, $p_resultado, $p_cliente, $p_ano, $p_cd_programa, $p_cd_acao, $p_cd_subacao, $p_cd_tipo_restricao, $p_cd_restricao_acao, $p_cd_tipo_inclusao, $p_cd_competencia, $p_inclusao, $p_descricao, $p_providencia, $p_relatorio, $p_tempo_habil, $p_observacao_monitor, $p_observacao_controle) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTXMLRESTRICAOACAO_SIG';
     $params=array('p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_ano'                       =>array(tvl($p_ano),                                      B_INTEGER,        32),
                   'p_cd_programa'               =>array(tvl($p_cd_programa),                              B_VARCHAR,         4),
                   'p_cd_acao'                   =>array(tvl($p_cd_acao),                                  B_VARCHAR,         4),
                   'p_cd_subacao'                =>array(tvl($p_cd_subacao),                               B_VARCHAR,         4),
                   'p_cd_tipo_restricao'         =>array(tvl($p_cd_tipo_restricao),                        B_INTEGER,        32),
                   'p_cd_restricao_acao'         =>array(tvl($p_cd_restricao_acao),                        B_INTEGER,        32),
                   'p_cd_tipo_inclusao'          =>array(tvl($p_cd_tipo_inclusao),                         B_VARCHAR,         2),
                   'p_cd_competencia'            =>array(tvl($p_cd_competencia),                           B_VARCHAR,         2),
                   'p_inclusao'                  =>array(tvl(str_replace('T',' ',$p_inclusao)),            B_VARCHAR,        20),
                   'p_descricao'                 =>array(tvl($p_descricao),                                B_VARCHAR,      4000),
                   'p_providencia'               =>array(tvl($p_providencia),                              B_VARCHAR,      4000),
                   'p_relatorio'                 =>array(tvl($p_relatorio),                                B_VARCHAR,         1),
                   'p_tempo_habil'               =>array(tvl($p_tempo_habil),                              B_VARCHAR,         1),
                   'p_observacao_monitor'        =>array(tvl($p_observacao_monitor),                       B_VARCHAR,      4000),
                   'p_observacao_controle'       =>array(tvl($p_observacao_controle),                      B_VARCHAR,      4000)
                  );
     $l_rs = new DatabaseQueriesFactory; $l_rs = $l_rs->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); 
     error_reporting(0); 
     if(!$l_rs->executeQuery()) { 
       error_reporting($l_error_reporting); 
       $Err = $l_rs->getError();
       $p_resultado = $Err['message'];
     } else {
       error_reporting($l_error_reporting); 
       return true;
     }
   }
}
?>
