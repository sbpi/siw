<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putMtConsumoGeral
*
* { Description :- 
*    Grava os dados principais do pedido de material de consumo
* }
*/

class dml_putMtConsumoGeral {
  function getInstanceOf($dbms, $operacao, $p_chave, $p_menu, $p_unidade, $p_solicitante,$p_cadastrador, 
        $p_justificativa, $p_observacao,$p_fim, $p_codigo, $p_copia, $p_obs_log, &$p_chave_nova) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putMtConsumoGeral';
     $params=array('p_operacao'             =>array($operacao,                    B_VARCHAR,         1),
                   'p_chave'                =>array(tvl($p_chave),                B_INTEGER,        32),
                   'p_copia'                =>array(tvl($p_copia),                B_INTEGER,        32),
                   'p_menu'                 =>array($p_menu,                      B_INTEGER,        32),
                   'p_unidade'              =>array(tvl($p_unidade),              B_INTEGER,        32),
                   'p_solicitante'          =>array(tvl($p_solicitante),          B_INTEGER,        32),
                   'p_cadastrador'          =>array(tvl($p_cadastrador),          B_INTEGER,        32),
                   'p_justificativa'        =>array(tvl($p_justificativa),        B_VARCHAR,      2000),
                   'p_observacao'           =>array(tvl($p_observacao),           B_VARCHAR,      2000),
                   'p_fim'                  =>array(tvl($p_fim),                  B_DATE,           32),
                   'p_codigo'               =>array(tvl($p_codigo),               B_VARCHAR,        60),
                   'p_observacao_log'       =>array(tvl($p_obs_log),              B_VARCHAR,      2000),
                   'p_chave_nova'           =>array(&$p_chave_nova,               B_INTEGER,        32)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(E_ERROR); 
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
