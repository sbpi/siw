<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putTipoGuarda_PA
*
* { Description :- 
*    Mantém a tabela de tipo de guardas
* }
*/

class dml_putTipoGuarda_PA {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_cliente, $p_sigla, $p_descricao, $p_fase_corrente, $p_fase_intermed, $p_fase_final, $p_destinacao_final, $p_ativo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_PE.'SP_PUTTIPOGUARDA_PA';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_sigla'                     =>array(tvl($p_sigla),                                    B_VARCHAR,         4),
                   'p_descricao'                 =>array(tvl($p_descricao),                                B_VARCHAR,       255),
                   'p_fase_corrente'             =>array(tvl($p_fase_corrente),                            B_VARCHAR,         1),
                   'p_fase_intermed'             =>array(tvl($p_fase_intermed),                            B_VARCHAR,         1),
                   'p_fase_final'                =>array(tvl($p_fase_final),                               B_VARCHAR,         1),
                   'p_destinacao_final'          =>array(tvl($p_destinacao_final),                         B_VARCHAR,         1),                   
                   'p_ativo'                     =>array(tvl($p_ativo),                                    B_VARCHAR,         1)
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
