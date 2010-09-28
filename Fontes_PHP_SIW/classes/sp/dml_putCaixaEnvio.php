<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putCaixaEnvio
*
* { Description :- 
*    Encaminha uma caixa de arquivamento
* }
*/

class dml_putCaixaEnvio {
   function getInstanceOf($dbms, $p_menu, $p_chave, $p_pessoa,$p_interno, $p_unidade_origem, $p_unidade_destino,
        $p_tipo_despacho,$p_despacho,$p_nu_guia, $p_ano_guia, $p_unidade_autuacao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putCaixaEnvio';
     $params=array('p_menu'                     =>array($p_menu,                                    B_INTEGER,        32),
                   'p_chave'                    =>array($p_chave,                                   B_INTEGER,        32),
                   'p_pessoa'                   =>array($p_pessoa,                                  B_INTEGER,        32),
                   'p_interno'                  =>array($p_interno,                                 B_VARCHAR,         1),
                   'p_unidade_origem'           =>array($p_unidade_origem,                          B_INTEGER,        32),
                   'p_unidade_destino'          =>array($p_unidade_destino,                         B_INTEGER,        32),
                   'p_tipo_despacho'            =>array($p_tipo_despacho,                           B_INTEGER,        32),
                   'p_despacho'                 =>array(tvl($p_despacho),                           B_VARCHAR,      2000),
                   'p_nu_guia'                  =>array(&$p_nu_guia,                                B_INTEGER,        32),
                   'p_ano_guia'                 =>array(&$p_ano_guia,                               B_INTEGER,        32),
                   'p_unidade_autuacao'         =>array(&$p_unidade_autuacao,                       B_INTEGER,        32)
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
