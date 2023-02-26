<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putUnidade_CL
*
* { Description :- 
*    Mantém a tabela de unidades responsáveis pelo monitoramento de compras e licitações
* }
*/

class dml_putUnidade_CL {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_chave, $p_unidade_pai, $p_realiza_compra, 
        $p_solicita_compra, $p_registra_pesquisa, $p_registra_contrato, $p_registra_judicial, 
        $p_controla_banco_ata, $p_controla_banco_preco, $p_codifica_item, $p_codificacao_restrita, 
        $p_padrao, $p_ativo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_PE.'SP_PUTUNIDADE_CL';
     $params=array('p_operacao'              =>array($operacao,                              B_VARCHAR,         1),
                   'p_cliente'               =>array(tvl($p_cliente),                        B_INTEGER,        32),
                   'p_chave'                 =>array(tvl($p_chave),                          B_INTEGER,        32),
                   'p_unidade_pai'           =>array(tvl($p_unidade_pai),                    B_INTEGER,        32),
                   'p_realiza_compra'        =>array(tvl($p_realiza_compra),                 B_VARCHAR,         1),
                   'p_solicita_compra'       =>array(tvl($p_solicita_compra),                B_VARCHAR,         1),
                   'p_registra_pesquisa'     =>array(tvl($p_registra_pesquisa),              B_VARCHAR,         1),
                   'p_registra_contrato'     =>array(tvl($p_registra_contrato),              B_VARCHAR,         1),
                   'p_registra_judicial'     =>array(tvl($p_registra_judicial),              B_VARCHAR,         1),
                   'p_controla_banco_ata'    =>array(tvl($p_controla_banco_ata),             B_VARCHAR,         1),
                   'p_controla_banco_preco'  =>array(tvl($p_controla_banco_preco),           B_VARCHAR,         1),
                   'p_codifica_item'         =>array(tvl($p_codifica_item),                  B_VARCHAR,         1),
                   'p_codificacao_restrita'  =>array(tvl($p_codificacao_restrita),           B_VARCHAR,         1),
                   'p_padrao'                =>array(tvl($p_padrao),                         B_VARCHAR,         1),
                   'p_ativo'                 =>array(tvl($p_ativo),                          B_VARCHAR,         1)
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
