<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putCategoriaDiaria
*
* { Description :- 
*    Mantém a tabela de categorias de diária
* }
*/
class dml_putCategoriaDiaria {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_chave, $p_nome, $p_ativo, $p_tramite, $p_prest_contas, $p_valor_complemento) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putCategoriaDiaria';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,        60),
                   'p_ativo'                     =>array(tvl($p_ativo),                                    B_VARCHAR,         1),
                   'p_tramite'                   =>array(tvl($p_tramite),                                  B_VARCHAR,         1),
                   'p_prest_contas'              =>array(tvl($p_prest_contas),                             B_INTEGER,        32),
                   'p_valor_complemento'         =>array(toNumber(tvl($p_valor_complemento)),              B_NUMERIC,      18,2),
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