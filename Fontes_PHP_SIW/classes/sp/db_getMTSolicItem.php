<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getMTSolicItens
*
* { Description :- 
*    Recupera dados da tabela de itens da entrada/saída de material.
* }
*/

class db_getMTSolicItem {
   function getInstanceOf($dbms, $p_chave,$p_solicitacao, $p_material, $p_cancelado, $p_tipo_material, $p_sq_cc, $p_codigo, 
                          $p_nome, $p_aviso, $p_invalida, $p_valida, $p_branco, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getMTSolicItem';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_solicitacao'               =>array(tvl($p_solicitacao),                              B_INTEGER,        32),
                   'p_material'                  =>array(tvl($p_material),                                 B_INTEGER,        32),
                   'p_cancelado'                 =>array(tvl($p_cancelado),                                B_VARCHAR,         1),
                   'p_tipo_material'             =>array(tvl($p_tipo_material),                            B_INTEGER,        32),
                   'p_sq_cc'                     =>array(tvl($p_sq_cc),                                    B_INTEGER,        32),
                   'p_codigo'                    =>array(tvl($p_codigo),                                   B_VARCHAR,        30),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,       110),
                   'p_aviso'                     =>array(tvl($p_aviso),                                    B_VARCHAR,         1),
                   'p_invalida'                  =>array(tvl($p_invalida),                                 B_VARCHAR,         1),
                   'p_valida'                    =>array(tvl($p_valida),                                   B_VARCHAR,         1),
                   'p_branco'                    =>array(tvl($p_branco),                                   B_VARCHAR,         1),
                   'p_restricao'                 =>array(tvl($p_restricao),                                B_VARCHAR,        15),
                   'p_result'                    =>array(null,                                             B_CURSOR,         -1)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
