<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putMatServ
*
* { Description :- 
*    Mantém a tabela principal de materiais ou serviços
* }
*/

class dml_putMatServ {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_usuario, $p_chave, $p_copia, $p_tipo_material, 
        $p_unidade_medida, $p_nome, $p_descricao, $p_detalhamento, $p_apresentacao, $p_codigo_interno, 
        $p_codigo_externo, $p_exibe_catalogo, $p_vida_util, $p_ativo, $p_chave_nova) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putMatServ';
     $params=array('p_operacao'         =>array($operacao,                      B_VARCHAR,         1),
                   'p_cliente'          =>array(tvl($p_cliente),                B_INTEGER,        32),
                   'p_usuario'          =>array(tvl($p_usuario),                B_INTEGER,        32),
                   'p_chave'            =>array(tvl($p_chave),                  B_INTEGER,        32),
                   'p_copia'            =>array(tvl($p_copia),                  B_INTEGER,        32),
                   'p_tipo_material'    =>array(tvl($p_tipo_material),          B_INTEGER,        32),
                   'p_unidade_medida'   =>array(tvl($p_unidade_medida),         B_INTEGER,        32),
                   'p_nome'             =>array(tvl($p_nome),                   B_VARCHAR,       110),
                   'p_descricao'        =>array(tvl($p_descricao),              B_VARCHAR,       130),
                   'p_detalhamento'     =>array(tvl($p_detalhamento),           B_VARCHAR,      2000),
                   'p_apresentacao'     =>array(tvl($p_apresentacao),           B_VARCHAR,       255),
                   'p_codigo_interno'   =>array(tvl($p_codigo_interno),         B_VARCHAR,        30),
                   'p_codigo_externo'   =>array(tvl($p_codigo_externo),         B_VARCHAR,        30),
                   'p_exibe_catalogo'   =>array(tvl($p_exibe_catalogo),         B_VARCHAR,         1),
                   'p_vida_util'        =>array(tvl($p_vida_util),              B_INTEGER,        32),
                   'p_ativo'            =>array(tvl($p_ativo),                  B_VARCHAR,         1),
                   'p_chave_nova'       =>array(&$p_chave_nova,                 B_INTEGER,        32)
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
