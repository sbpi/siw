<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putAssunto_PA
*
* { Description :- 
*    Mantém a tabela de assuntos
* }
*/

class dml_putAssunto_PA {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_cliente, $p_chave_pai, $p_codigo, $p_descricao, $p_detalhamento, 
        $p_observacao, $p_corrente_guarda, $p_corrente_anos, $p_intermed_guarda, $p_intermed_anos, 
        $p_final_guarda, $p_final_anos, $p_destinacao_final, $p_provisorio, $p_ativo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_PE.'SP_PUTASSUNTO_PA';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_chave_pai'                 =>array(tvl($p_chave_pai),                                B_INTEGER,        32),
                   'p_codigo'                    =>array(tvl($p_codigo),                                   B_VARCHAR,        10),
                   'p_descricao'                 =>array(tvl($p_descricao),                                B_VARCHAR,       255),
                   'p_detalhamento'              =>array(tvl($p_detalhamento),                             B_VARCHAR,      2000),
                   'p_observacao'                =>array(tvl($p_observacao),                               B_VARCHAR,      2000),
                   'p_corrente_guarda'           =>array(tvl($p_corrente_guarda),                          B_INTEGER,        32),
                   'p_corrente_anos'             =>array(tvl($p_corrente_anos),                            B_INTEGER,        32),
                   'p_intermed_guarda'           =>array(tvl($p_intermed_guarda),                          B_INTEGER,        32),
                   'p_intermed_anos'             =>array(tvl($p_intermed_anos),                            B_INTEGER,        32),
                   'p_final_guarda'              =>array(tvl($p_final_guarda),                             B_INTEGER,        32),
                   'p_final_anos'                =>array(tvl($p_final_anos),                               B_INTEGER,        32),
                   'p_destinacao_final'          =>array(tvl($p_destinacao_final),                         B_INTEGER,        32),
                   'p_provisorio'                =>array(tvl($p_provisorio),                               B_VARCHAR,         1),
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
