<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_puLCDados
*
* { Description :- 
*    Grava a tela de dados da análise do certame
* }
*/

class dml_putCLDados {
   function getInstanceOf($dbms, $restricao, $p_chave, $p_sq_lcmodalidade, $p_numero_processo, $p_abertura,
                $p_envelope_1, $p_envelope_2, $p_envelope_3, $p_numero_certame,$p_numero_ata, $p_tipo_reajuste, 
                $p_indice_base, $p_sq_eoindicador, $p_limite_variacao, $p_sq_lcfonte_recurso, $p_sq_espec_despesa, 
                $p_sq_lcjulgamento, $p_sq_lcsituacao, $p_financeiro_unico, $p_homologacao, $p_data_diario, 
                $p_pagina_diario, $p_ordem, $p_dias,$p_dias_item, $p_protocolo, $p_fim, $p_prioridade, $p_observacao, 
                $p_fundo_fixo, $p_quantidade,$p_detalhamento) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putClDados';
     $params=array('p_restricao'              =>array($restricao,                                 B_VARCHAR,        30),
                   'p_chave'                  =>array(tvl($p_chave),                              B_INTEGER,        32),
                   'p_sq_lcmodalidade'        =>array(tvl($p_sq_lcmodalidade),                    B_INTEGER,        32),                   
                   'p_numero_processo'        =>array(tvl($p_numero_processo),                    B_VARCHAR,        30),
                   'p_abertura'               =>array(tvl($p_abertura),                           B_DATE,           32),
                   'p_envelope_1'             =>array(tvl($p_envelope_1),                         B_DATE,           32),
                   'p_envelope_2'             =>array(tvl($p_envelope_2),                         B_DATE,           32),
                   'p_envelope_3'             =>array(tvl($p_envelope_3),                         B_DATE,           32),
                   'p_numero_certame'         =>array(tvl($p_numero_certame),                     B_VARCHAR,        50),
                   'p_numero_ata'             =>array(tvl($p_numero_ata),                         B_VARCHAR,        30),
                   'p_tipo_reajuste'          =>array(tvl($p_tipo_reajuste),                      B_INTEGER,        32),
                   'p_indice_base'            =>array(tvl($p_indice_base),                        B_VARCHAR,         7),
                   'p_sq_eoindicador'         =>array(tvl($p_sq_eoindicador),                     B_INTEGER,        32),                   
                   'p_limite_variacao'        =>array(toNumber(tvl($p_limite_variacao)),          B_NUMERIC,        32),
                   'p_sq_lcfonte_recurso'     =>array(tvl($p_sq_lcfonte_recurso),                 B_INTEGER,        32),
                   'p_sq_espec_despesa'       =>array(tvl($p_sq_espec_despesa),                   B_INTEGER,        32),
                   'p_sq_lcjulgamento'        =>array(tvl($p_sq_lcjulgamento),                    B_INTEGER,        32),
                   'p_sq_lcsituacao'          =>array(tvl($p_sq_lcsituacao),                      B_INTEGER,        32),
                   'p_financeiro_unico'       =>array($p_financeiro_unico,                        B_VARCHAR,         1),
                   'p_homologacao'            =>array(tvl($p_homologacao),                        B_DATE,           32),
                   'p_data_diario'            =>array(tvl($p_data_diario),                        B_DATE,           32),
                   'p_pagina_diario'          =>array(tvl($p_pagina_diario),                      B_INTEGER,        32),
                   'p_ordem'                  =>array(tvl($p_ordem),                              B_VARCHAR,        10),
                   'p_dias'                   =>array(tvl($p_dias),                               B_INTEGER,        32),
                   'p_dias_item'              =>array(tvl($p_dias_item),                          B_INTEGER,        32),                   
                   'p_protocolo'              =>array(tvl($p_protocolo),                          B_VARCHAR,        30),
                   'p_fim'                    =>array(tvl($p_fim),                                B_DATE,           32),
                   'p_prioridade'             =>array(tvl($p_prioridade),                         B_INTEGER,        32),
                   'p_observacao'             =>array(tvl($p_observacao),                         B_VARCHAR,      2000),
                   'p_fundo_fixo'             =>array(tvl($p_fundo_fixo),                         B_VARCHAR,         1),
                   'p_quantidade'             =>array(tonumber(tvl($p_quantidade)),               B_NUMERIC,      18,2),
                   'p_detalhamento'           =>array(tvl($p_detalhamento),                       B_VARCHAR,      4000)
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
