<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_puMtBaixaBem
*
* { Description :- 
*    Grava a tela inicial da baixa de bens patrimoniais
* }
*/

class dml_putMtBaixaBem {
   function getInstanceOf($dbms, $p_operacao,$p_cliente,$p_usuario,$p_chave,$p_menu,$p_unidade,$p_descricao,
           $p_observacao,$p_almoxarifado,$p_fornecedor,$p_tipo_movimentacao,&$p_chave_nova, &$p_codigo_interno) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putMtBaixaBem';
     $params=array('p_operacao'               =>array($p_operacao,                                B_VARCHAR,        30),
                   'p_cliente'                =>array(tvl($p_cliente),                            B_INTEGER,        32),
                   'p_usuario'                =>array(tvl($p_usuario),                            B_INTEGER,        32),
                   'p_chave'                  =>array(tvl($p_chave),                              B_INTEGER,        32),
                   'p_menu'                   =>array(tvl($p_menu),                               B_INTEGER,        32),  
                   'p_unidade'                =>array(tvl($p_unidade),                            B_INTEGER,        32),  
                   'p_descricao'              =>array(tvl($p_descricao),                          B_VARCHAR,       500),
                   'p_observacao'             =>array(tvl($p_observacao),                         B_VARCHAR,       500),
                   'p_almoxarifado'           =>array(tvl($p_almoxarifado),                       B_INTEGER,        32),              
                   'p_fornecedor'             =>array(tvl($p_fornecedor),                         B_INTEGER,        32),                   
                   'p_tipo_movimentacao'      =>array(tvl($p_tipo_movimentacao),                  B_INTEGER,        32),
                   'p_chave_nova'             =>array(&$p_chave_nova,                             B_INTEGER,        32),
                   'p_codigo_interno'         =>array(&$p_codigo_interno,                         B_VARCHAR,        60)
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
