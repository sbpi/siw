<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putXMLDadoFisico_PPA
*
* { Description :- 
*    Mantém a tabela PPA - Dado Físico
* }
*/

class dml_putXMLDadoFisico_PPA {
   function getInstanceOf($dbms, &$p_resultado, $p_cliente, $p_ano, $p_cd_programa, $p_cd_acao_ppa, $p_cd_localizador_ppa, $p_qtd_ano_1, $p_qtd_ano_2, $p_qtd_ano_3, $p_qtd_ano_4, $p_qtd_ano_5, $p_qtd_ano_6, $p_observacao, $p_cumulativa) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTXMLDADOFISICO_PPA';
     $params=array('p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_ano'                       =>array(tvl($p_ano),                                      B_INTEGER,        32),
                   'p_cd_programa'               =>array(tvl($p_cd_programa),                              B_VARCHAR,         4),
                   'p_cd_acao_ppa'               =>array(tvl($p_cd_acao_ppa),                              B_VARCHAR,         7),
                   'p_cd_localizador_ppa'        =>array(tvl($p_cd_localizador_ppa),                       B_VARCHAR,         7),
                   'p_qtd_ano_1'                 =>array(toNumber(tvl(str_replace('.',',',$p_qtd_ano_1))), B_NUMERIC,      18,4),
                   'p_qtd_ano_2'                 =>array(toNumber(tvl(str_replace('.',',',$p_qtd_ano_2))), B_NUMERIC,      18,4),
                   'p_qtd_ano_3'                 =>array(toNumber(tvl(str_replace('.',',',$p_qtd_ano_3))), B_NUMERIC,      18,4),
                   'p_qtd_ano_4'                 =>array(toNumber(tvl(str_replace('.',',',$p_qtd_ano_4))), B_NUMERIC,      18,4),
                   'p_qtd_ano_5'                 =>array(toNumber(tvl(str_replace('.',',',$p_qtd_ano_5))), B_NUMERIC,      18,4),
                   'p_qtd_ano_6'                 =>array(toNumber(tvl(str_replace('.',',',$p_qtd_ano_6))), B_NUMERIC,      18,4),
                   'p_observacao'                =>array(tvl($p_observacao),                               B_VARCHAR,      4000),
                   'p_cumulativa'                =>array(tvl($p_cumulativa),                               B_VARCHAR,         1)
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
