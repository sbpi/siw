<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putRestricaoPrograma_IS
*
* { Description :- 
*    Mantém a tabela de restrições do programa
* }
*/

class dml_putRestricaoPrograma_IS {
   function getInstanceOf($dbms, $operacao, $cliente, $ano, $p_cd_programa, $p_chave_aux, $p_cd_tipo_restricao, $p_cd_tipo_inclusao, $p_cd_competencia, $p_superacao, $p_relatorio, $p_tempo_habil, $p_descricao, $p_providencia, $p_observacao_controle, $p_observacao_monitor) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTRESTRICAOPROGRAMA_IS';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_cliente'                   =>array($cliente,                                         B_INTEGER,        32),
                   'p_ano'                       =>array($ano,                                             B_INTEGER,        32),
                   'p_cd_programa'               =>array($p_cd_programa,                                   B_VARCHAR,         4),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_cd_tipo_restricao'         =>array(tvl($p_cd_tipo_restricao),                        B_INTEGER,        32),
                   'p_cd_tipo_inclusao'          =>array(tvl($p_cd_tipo_inclusao),                         B_VARCHAR,         2),
                   'p_cd_competencia'            =>array($p_cd_competencia,                                B_VARCHAR,         2),
                   'p_superacao'                 =>array(tvl($p_superacao),                                B_DATE,           32),
                   'p_relatorio'                 =>array(tvl($p_relatorio),                                B_VARCHAR,         1),
                   'p_tempo_habil'               =>array(tvl($p_tempo_habil),                              B_VARCHAR,         1),
                   'p_descricao'                 =>array(tvl($p_descricao),                                B_VARCHAR,      4000),
                   'p_providencia'               =>array(tvl($p_providencia),                              B_VARCHAR,      4000),
                   'p_observacao_controle'       =>array(tvl($p_observacao_controle),                      B_VARCHAR,      4000),
                   'p_observacao_monitor'        =>array(tvl($p_observacao_monitor),                       B_VARCHAR,      4000)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
