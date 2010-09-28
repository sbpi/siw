<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putUnidade_PA
*
* { Description :- 
*    Mantém a tabela de unidades responsáveis pelo monitoramento do protocolo
* }
*/

class dml_putUnidade_PA {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_chave, $p_unidade_pai, $p_registra_documento, $p_autua_processo, 
        $p_prefixo, $p_nr_documento, $p_nr_tramite, $p_nr_transferencia, $p_nr_eliminacao, $p_arquivo_setorial, $p_ativo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_PE.'SP_PUTUNIDADE_PA';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_unidade_pai'               =>array(tvl($p_unidade_pai),                              B_INTEGER,        32),
                   'p_registra_documento'        =>array(tvl($p_registra_documento),                       B_VARCHAR,         1),
                   'p_autua_processo'            =>array(tvl($p_autua_processo),                           B_VARCHAR,         1),
                   'p_prefixo'                   =>array(tvl($p_prefixo),                                  B_VARCHAR,         5),
                   'p_nr_documento'              =>array(tvl($p_nr_documento),                             B_INTEGER,        32),
                   'p_nr_tramite'                =>array(tvl($p_nr_tramite),                               B_INTEGER,        32),
                   'p_nr_transferencia'          =>array(tvl($p_nr_transferencia),                         B_INTEGER,        32),
                   'p_nr_eliminacao'             =>array(tvl($p_nr_eliminacao),                            B_INTEGER,        32),
                   'p_arquivo_setorial'          =>array(tvl($p_arquivo_setorial),                         B_VARCHAR,         1),
                   'p_ativo'                     =>array(tvl($p_ativo),                                    B_VARCHAR,         1)
                  );
     $l_rs = new DatabaseQueriesFactory; $l_rs = $l_rs->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
