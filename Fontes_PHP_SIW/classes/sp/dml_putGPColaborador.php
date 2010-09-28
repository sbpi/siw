<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putGPColaborador
*
* { Description :- 
*    Mantém os dados de um do colaborador
* }
*/

class dml_putGPColaborador {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_sq_pessoa, $p_ctps_numero, $p_ctps_serie, $p_ctps_emissor, $p_ctps_emissao, $p_pis_pasep, $p_pispasep_numero, $p_pispasep_cadastr, $p_te_numero, $p_te_zona, $p_te_secao, $p_reservista_numero, $p_reservista_csm, $p_tipo_sangue, $p_doador_sangue, $p_doador_orgaos, $p_observacoes) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'sp_putGPColaborador';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_cliente'                   =>array($p_cliente,                                       B_INTEGER,        32),
                   'p_sq_pessoa'                 =>array($p_sq_pessoa,                                     B_INTEGER,        32),
                   'p_ctps_numero'               =>array(tvl($p_ctps_numero),                              B_VARCHAR,        20),
                   'p_ctps_serie'                =>array(tvl($p_ctps_serie),                               B_VARCHAR,         5),
                   'p_ctps_emissor'              =>array(tvl($p_ctps_emissor),                             B_VARCHAR,        30),
                   'p_ctps_emissao'              =>array(tvl($p_ctps_emissao),                             B_DATE,           32),
                   'p_pis_pasep'                 =>array($p_pis_pasep,                                     B_VARCHAR,         1),
                   'p_pispasep_numero'           =>array(tvl($p_pispasep_numero),                          B_VARCHAR,        20),
                   'p_pispasep_cadastr'          =>array(tvl($p_pispasep_cadastr),                         B_DATE,           32),
                   'p_te_numero'                 =>array(tvl($p_te_numero),                                B_VARCHAR,        20),
                   'p_te_zona'                   =>array(tvl($p_te_zona),                                  B_VARCHAR,         3),
                   'p_te_secao'                  =>array(tvl($p_te_secao),                                 B_VARCHAR,         4),
                   'p_reservista_numero'         =>array(tvl($p_reservista_numero),                        B_VARCHAR,        15),
                   'p_reservista_csm'            =>array(tvl($p_reservista_csm),                           B_VARCHAR,         4),
                   'p_tipo_sangue'               =>array(tvl($p_tipo_sangue),                              B_VARCHAR,         5),
                   'p_doador_sangue'             =>array(tvl($p_doador_sangue),                            B_VARCHAR,         1),
                   'p_doador_orgaos'             =>array(tvl($p_doador_orgaos),                            B_VARCHAR,         1),
                   'p_observacoes'               =>array(tvl($p_observacoes),                              B_VARCHAR,      2000)
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
