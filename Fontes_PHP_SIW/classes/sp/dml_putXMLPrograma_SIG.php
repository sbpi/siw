<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putXMLPrograma_SIG
*
* { Description :- 
*    Mantém a tabela SIG - Programa
* }
*/

class dml_putXMLPrograma_SIG {
   function getInstanceOf($dbms, $p_resultado, $p_cliente, $p_ano, $p_chave, $p_tipo_org, $p_orgao, $p_nome, $p_tipo_prog, $p_macro, $p_mes_ini, $p_ano_ini, $p_mes_fim, $p_ano_fim, $p_objetivo, $p_publico_alvo, $p_justificativa, $p_estrategia, $p_ln_programa, $p_valor_estimado, $p_valor_ppa, $p_temporario, $p_contexto, $p_atual_contexto, $p_estagio, $p_andamento, $p_cronograma, $p_perc_execucao, $p_comentario_sit, $p_atual_sit, $p_situacao_atual, $p_resultados_obt, $p_atual_sit_atual, $p_coment_execucao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTXMLPROGRAMA_SIG';
     $params=array('p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_ano'                       =>array(tvl($p_ano),                                      B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_VARCHAR,         4),
                   'p_tipo_org'                  =>array(tvl($p_tipo_org),                                 B_VARCHAR,         1),
                   'p_orgao'                     =>array(tvl($p_orgao),                                    B_VARCHAR,         5),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,       120),
                   'p_tipo_prog'                 =>array(tvl($p_tipo_prog),                                B_INTEGER,        32),
                   'p_macro'                     =>array(tvl($p_macro),                                    B_VARCHAR,         2),
                   'p_mes_ini'                   =>array(tvl($p_mes_ini),                                  B_VARCHAR,         2),
                   'p_ano_ini'                   =>array(tvl($p_ano_ini),                                  B_VARCHAR,         4),
                   'p_mes_fim'                   =>array(tvl($p_mes_fim),                                  B_VARCHAR,         2),
                   'p_ano_fim'                   =>array(tvl($p_ano_fim),                                  B_VARCHAR,         4),
                   'p_objetivo'                  =>array(tvl($p_objetivo),                                 B_VARCHAR,      4000),
                   'p_publico_alvo'              =>array(tvl($p_publico_alvo),                             B_VARCHAR,      4000),
                   'p_justificativa'             =>array(tvl(substr($p_justificativa,0,3999)),             B_VARCHAR,      4000),
                   'p_estrategia'                =>array(tvl(substr($p_estrategia,0,3999)),                B_VARCHAR,      4000),
                   'p_ln_programa'               =>array(tvl($p_ln_programa),                              B_VARCHAR,       120),
                   'p_valor_estimado'            =>array(toNumber(tvl(str_replace('.',',',$p_valor_estimado))), B_NUMERIC,      18,2),
                   'p_valor_ppa'                 =>array(toNumber(tvl(str_replace('.',',',$p_valor_ppa))), B_NUMERIC,      18,2),
                   'p_temporario'                =>array(tvl($p_temporario),                               B_VARCHAR,         1),
                   'p_contexto'                  =>array(tvl(substr($p_contexto,0,3999)),                  B_VARCHAR,      4000),
                   'p_atuap_contexto'            =>array(tvl($p_atual_contexto),                           B_DATE,           32),
                   'p_estagio'                   =>array(tvl($p_estagio),                                  B_VARCHAR,         2),
                   'p_andamento'                 =>array(tvl($p_andamento),                                B_VARCHAR,         2),
                   'p_cronograma'                =>array(tvl($p_cronograma),                               B_VARCHAR,         2),
                   'p_perc_execucao'             =>array(tvl($p_perc_execucao),                            B_INTEGER,        32),
                   'p_comentario_sit'            =>array(tvl(substr($p_comentario_sit,0,3999)),            B_VARCHAR,      4000),
                   'p_atuap_sit'                 =>array(tvl($p_atual_sit),                                B_DATE,           32),
                   'p_situacao_atual'            =>array(tvl(substr($p_situacao_atual,0,3999)),            B_VARCHAR,      4000),
                   'p_resultados_obt'            =>array(tvl(substr($p_resultados_obt,0,3999)),            B_VARCHAR,      4000),
                   'p_atuap_sit_atual'           =>array(tvl($p_atual_sit_atual),                          B_DATE,           32),
                   'p_coment_execucao'           =>array(tvl(substr($p_coment_execucao,0,3999)),           B_VARCHAR,      4000)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
