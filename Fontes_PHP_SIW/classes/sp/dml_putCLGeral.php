<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putCLGeral
*
* { Description :- 
*    Mantém a tabela principal de pedido de compra, licitacao e ARP
* }
*/

class dml_putCLGeral {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_menu, $p_unidade, $p_solicitante,$p_cadastrador, $p_executor, 
        $p_plano, $p_objetivo, $p_sqcc, $p_solic_pai, $p_justificativa, $p_objeto, $p_observacao,$p_inicio, $p_fim, 
        $p_moeda, $p_valor, $p_codigo,$p_prioridade, $p_aviso, $p_dias, $p_cidade, $p_decisao_judicial, $p_numero_original, 
        $p_data_recebimento, $p_arp, $p_interno, $p_especie_documento,$p_financeiro, $p_rubrica, $p_lancamento, 
        $p_observacao_log, &$p_chave_nova, $p_copia) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putCLGeral';
     $params=array('p_operacao'             =>array($operacao,                    B_VARCHAR,         1),
                   'p_chave'                =>array(tvl($p_chave),                B_INTEGER,        32),
                   'p_copia'                =>array(tvl($p_copia),                B_INTEGER,        32),
                   'p_menu'                 =>array($p_menu,                      B_INTEGER,        32),
                   'p_unidade'              =>array(tvl($p_unidade),              B_INTEGER,        32),
                   'p_solicitante'          =>array(tvl($p_solicitante),          B_INTEGER,        32),
                   'p_cadastrador'          =>array(tvl($p_cadastrador),          B_INTEGER,        32),
                   'p_executor'             =>array(tvl($p_executor),             B_INTEGER,        32),
                   'p_plano'                =>array(tvl($p_plano),                B_INTEGER,        32),
                   'p_objetivo'             =>array(tvl($p_objetivo),             B_VARCHAR,      2000),
                   'p_sqcc'                 =>array(tvl($p_sqcc),                 B_INTEGER,        32),
                   'p_solic_pai'            =>array(tvl($p_solic_pai),            B_INTEGER,        32),
                   'p_justificativa'        =>array(tvl($p_justificativa),        B_VARCHAR,      2000),
                   'p_objeto'               =>array(tvl($p_objeto),               B_VARCHAR,      2000),
                   'p_observacao'           =>array(tvl($p_observacao),           B_VARCHAR,      2000),
                   'p_inicio'               =>array(tvl($p_inicio),               B_DATE,           32),
                   'p_fim'                  =>array(tvl($p_fim),                  B_DATE,           32),
                   'p_moeda'                =>array(tvl($p_moeda),                B_INTEGER,        32),
                   'p_valor'                =>array(toNumber(tvl($p_valor)),      B_NUMERIC,      18,2),
                   'p_codigo'               =>array(tvl($p_codigo),               B_VARCHAR,        60),
                   'p_prioridade'           =>array(tvl($p_prioridade),           B_INTEGER,        32),
                   'p_aviso'                =>array(tvl($p_aviso),                B_VARCHAR,         1),
                   'p_dias'                 =>array(nvl($p_dias,0),               B_INTEGER,        32),
                   'p_cidade'               =>array(tvl($p_cidade),               B_INTEGER,        32),
                   'p_decisao_judicial'     =>array(tvl($p_decisao_judicial),     B_VARCHAR,         1),
                   'p_numero_original'      =>array(tvl($p_numero_original),      B_VARCHAR,        30),
                   'p_data_recebimento'     =>array(tvl($p_data_recebimento),     B_DATE,           32),
                   'p_arp'                  =>array(tvl($p_arp),                  B_VARCHAR,         1),
                   'p_interno'              =>array(tvl($p_interno),              B_VARCHAR,         1),
                   'p_especie_documento'    =>array(tvl($p_especie_documento),    B_INTEGER,        32),
                   'p_financeiro'           =>array(tvl($p_financeiro),           B_INTEGER,        32),
                   'p_rubrica'              =>array(tvl($p_rubrica),              B_INTEGER,        32),
                   'p_lancamento'           =>array(tvl($p_lancamento),           B_INTEGER,        32),
                   'p_observacao_log'       =>array(tvl($p_observacao),           B_VARCHAR,      2000),
                   'p_chave_nova'           =>array(&$p_chave_nova,               B_INTEGER,        32)
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
