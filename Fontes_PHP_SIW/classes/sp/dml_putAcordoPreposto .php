<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putAcordoPreposto 
*
* { Description :- 
*    Grava a tela de preposto
* }
*/

class dml_putAcordoPreposto  {
   function getInstanceOf($dbms, $operacao, $p_restricao, $p_chave, $p_chave_aux, $p_sq_pessoa, $p_cpf, $p_nome, $p_nome_resumido, $p_sexo, $p_rg_numero, $p_rg_emissao, $p_rg_emissor) {
     $sql=$strschema.'SP_PUTACORDOPREPOSTO';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_restricao'                 =>array($p_restricao,                                     B_VARCHAR,        10),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_sq_pessoa'                 =>array(tvl($p_sq_pessoa),                                B_INTEGER,        32),
                   'p_cpf'                       =>array(tvl($p_cpf),                                      B_VARCHAR,        14),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,        60),
                   'p_nome_resumido'             =>array(tvl($p_nome_resumido),                            B_VARCHAR,        21),
                   'p_sexo'                      =>array(tvl($p_sexo),                                     B_VARCHAR,         1),
                   'p_rg_numero'                 =>array(tvl($p_rg_numero),                                B_VARCHAR,        30),
                   'p_rg_emissao'                =>array(tvl($p_rg_emissao),                               B_DATE,           32),
                   'p_rg_emissor'                =>array(tvl($p_rg_emissor),                               B_VARCHAR,        30)
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
