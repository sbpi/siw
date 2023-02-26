<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putMTPermanente
*
* { Description :- 
*    Mantém a tabela principal de materiais ou serviços
* }
*/

class dml_putMTPermanente {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_usuario, $p_chave, $p_copia, 
        $p_localizacao, $p_almoxarifado, $p_projeto, $p_sqcc, $p_material, $p_entrada, $p_situacao, 
        $p_forn_garantia,$p_numero_rgp, $p_tombamento, $p_descricao, $p_codigo_externo, $p_numero_serie,
        $p_marca, $p_modelo, $p_fim_garantia, $p_vida_util, $p_observacao, $p_cc_patrimonial,
        $p_cc_depreciacao, $p_valor_brl, $p_valor_usd, $p_valor_eur, $p_data_brl, $p_data_usd, $p_data_eur, 
        &$p_chave_nova) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putMTPermanente';
     $params=array('p_operacao'         =>array($operacao,                      B_VARCHAR,         1),
                   'p_cliente'          =>array(tvl($p_cliente),                B_INTEGER,        32),
                   'p_usuario'          =>array(tvl($p_usuario),                B_INTEGER,        32),
                   'p_chave'            =>array(tvl($p_chave),                  B_INTEGER,        32),
                   'p_copia'            =>array(tvl($p_copia),                  B_INTEGER,        32),
                   'p_localizacao'      =>array(tvl($p_localizacao),            B_INTEGER,        32),
                   'p_almoxarifado'     =>array(tvl($p_almoxarifado),           B_INTEGER,        32),
                   'p_projeto'          =>array(tvl($p_projeto),                B_INTEGER,       110),
                   'p_sqcc'             =>array(tvl($p_sqcc),                   B_INTEGER,       130),
                   'p_material'         =>array(tvl($p_material),               B_INTEGER,        32),
                   'p_entrada'          =>array(tvl($p_entrada),                B_INTEGER,      2000),
                   'p_situacao'         =>array(tvl($p_situacao),               B_INTEGER,       255),
                   'p_forn_garantia'    =>array(tvl($p_forn_garantia),          B_INTEGER,        30),
                   'p_numero_rgp'       =>array(tvl($p_numero_rgp),             B_INTEGER,        30),
                   'p_tombamento'       =>array(tvl($p_tombamento),             B_DATE,           32),
                   'p_descricao'        =>array(tvl($p_descricao),              B_VARCHAR,       500),
                   'p_codigo_externo'   =>array(tvl($p_codigo_externo),         B_VARCHAR,        50),
                   'p_numero_serie'     =>array(tvl($p_numero_serie),           B_VARCHAR,        50),
                   'p_marca'            =>array(tvl($p_marca),                  B_VARCHAR,        50),
                   'p_modelo'           =>array(tvl($p_modelo),                 B_VARCHAR,        50),
                   'p_fim_garantia'     =>array(tvl($p_fim_garantia),           B_DATE,           32),
                   'p_vida_util'        =>array(tvl($p_vida_util),              B_INTEGER,        32),
                   'p_observacao'       =>array(tvl($p_observacao),             B_VARCHAR,       500),
                   'p_cc_patrimonial'   =>array(tvl($p_cc_patrimonial),         B_VARCHAR,        25),
                   'p_cc_depreciacao'   =>array(tvl($p_cc_depreciacao),         B_VARCHAR,        25),
                   'p_valor_brl'        =>array(toNumber(tvl($p_valor_brl)),    B_NUMERIC,      18,2),
                   'p_valor_usd'        =>array(toNumber(tvl($p_valor_usd)),    B_NUMERIC,      18,2),
                   'p_valor_eur'        =>array(toNumber(tvl($p_valor_eur)),    B_NUMERIC,      18,2),
                   'p_data_brl'         =>array(toNumber(tvl($p_data_brl)),     B_DATE,           32),
                   'p_data_usd'         =>array(toNumber(tvl($p_data_usd)),     B_DATE,           32),
                   'p_data_eur'         =>array(toNumber(tvl($p_data_eur)),     B_DATE,           32),
                   'p_chave_nova'       =>array(&$p_chave_nova,                 B_INTEGER,        32)
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
