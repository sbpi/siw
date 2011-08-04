<?php
extract($GLOBALS);
include_once($w_dir_volta . 'classes/db/DatabaseQueriesFactory.php');

/**
 * class dml_putTipoDocumento
 *
 * { Description :- 
 *    Mantém a tabela de tipos de documento
 * }
 */
class dml_putTipoDocumento {

  function getInstanceOf($dbms, $operacao, $p_chave, $p_cliente, $p_nome, $p_sigla, $p_detalha_item, $p_codigo_externo, $p_especie_documento, $p_ativo) {
    extract($GLOBALS, EXTR_PREFIX_SAME, 'strchema');
    $sql = $strschema . 'sp_putTipoDocumento';
    $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                  'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                  'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                  'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,        50),
                  'p_sigla'                     =>array(tvl($p_sigla),                                    B_VARCHAR,        15),
                  'p_detalha_item'              =>array(tvl($p_detalha_item),                             B_VARCHAR,         1),
                  'p_codigo_externo'            =>array(tvl($p_codigo_externo),                           B_VARCHAR,        30),
                  'p_especie_documento'         =>array(tvl($p_especie_documento),                        B_INTEGER,        18),
                  'p_ativo'                     =>array(tvl($p_ativo),                                    B_VARCHAR,         1)
                 );
    $lql = new DatabaseQueriesFactory;
    $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
    $l_error_reporting = error_reporting();
    error_reporting(0);
    if (!$l_rs->executeQuery()) {
      error_reporting($l_error_reporting);
      TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__);
    } else {
      error_reporting($l_error_reporting);
      return true;
    }
  }

}

?>
