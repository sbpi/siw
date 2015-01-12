<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putXMLPrograma_PPA
*
* { Description :- 
*    Mantém a tabela PPA - Programa
* }
*/

class dml_putXMLPrograma_PPA {
   function getInstanceOf($dbms, &$p_resultado, $p_cliente, $p_ano, $p_chave, $p_orgao, $p_tipo_org, $p_orgao_siorg, $p_tipo_prog, $p_nome, $p_mes_ini, $p_ano_ini, $p_mes_fim, $p_ano_fim, $p_objetivo, $p_publico_alvo, $p_justificativa, $p_estrategia, $p_valor_estimado, $p_temporario, $p_padronizado, $p_observacao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTXMLPROGRAMA_PPA';
     $params=array('p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_ano'                       =>array(tvl($p_ano),                                      B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_VARCHAR,         4),
                   'p_orgao'                     =>array(tvl($p_orgao),                                    B_VARCHAR,         5),
                   'p_tipo_org'                  =>array(tvl($p_tipo_org),                                 B_VARCHAR,         1),
                   'p_orgao_siorg'               =>array(tvl($p_orgao_siorg),                              B_INTEGER,        32),
                   'p_tipo_prog'                 =>array(tvl($p_tipo_prog),                                B_INTEGER,        32),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,       200),
                   'p_mes_ini'                   =>array(tvl($p_mes_ini),                                  B_VARCHAR,         2),
                   'p_ano_ini'                   =>array(tvl($p_ano_ini),                                  B_VARCHAR,         4),
                   'p_mes_fim'                   =>array(tvl($p_mes_fim),                                  B_VARCHAR,         2),
                   'p_ano_fim'                   =>array(tvl($p_ano_fim),                                  B_VARCHAR,         4),
                   'p_objetivo'                  =>array(tvl($p_objetivo),                                 B_VARCHAR,      4000),
                   'p_publico_alvo'              =>array(tvl($p_publico_alvo),                             B_VARCHAR,      4000),
                   'p_justificativa'             =>array(tvl(substr($p_justificativa,0,3999)),             B_VARCHAR,      4000),
                   'p_estrategia'                =>array(tvl(substr($p_estrategia,0,3999)),                B_VARCHAR,      4000),
                   'p_valor_estimado'            =>array(toNumber(tvl(str_replace('.',',',$p_valor_estimado))), B_NUMERIC, 18,2),
                   'p_temporario'                =>array(tvl($p_temporario),                               B_VARCHAR,         1),
                   'p_padronizado'               =>array(tvl($p_padronizado),                              B_VARCHAR,         1),
                   'p_observacao'                =>array(tvl($p_observacao),                               B_VARCHAR,      4000)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); 
     error_reporting(0); 
     if(!$l_rs->executeQuery()) { 
       error_reporting($l_error_reporting); 
       $Err = $l_rs->getError();
       $p_resultado = $Err['message'];
     } else {
       error_reporting($l_error_reporting); 
       return true;
     }
   }
}
?>
