<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
 * class dml_putGPFeriasDias
 *
 * { Description :-
 *    Grava a tela de outra parte
 * }
 */

class dml_putGPFeriasDias  {
  function getInstanceOf($dbms,$operacao,$p_chave,$p_cliente,$p_faixa_inicio,$p_faixa_fim,$p_dias_ferias,$p_ativo) {
    extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putGPFeriasDias';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array($p_chave,                                         B_INTEGER,        32),
                   'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_faixa_inicio'              =>array(tvl($p_faixa_inicio),                             B_INTEGER,        32),
                   'p_faixa_fim'                 =>array(tvl($p_faixa_fim),                                B_INTEGER,        32),     
                   'p_dias_ferias'               =>array(tvl($p_dias_ferias),                              B_INTEGER,        32),
                   'p_ativo'                     =>array($p_ativo,                                         B_VARCHAR,         1)
    );
    $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
