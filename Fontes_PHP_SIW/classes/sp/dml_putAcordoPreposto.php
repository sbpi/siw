<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putAcordoPreposto
*
* { Description :- 
*    Mantém a tabela de prepostos e representantes de uma das partes do acordo
* }
*/

class dml_putAcordoPreposto {
   function getInstanceOf($dbms, $operacao, $p_tipo, $p_chave, $p_sq_acordo_outra_parte, $p_sq_pessoa, $p_chave_aux,  $p_cargo,
           $p_nome, $p_nome_resumido, $p_sexo, $p_rg_numero, $p_rg_emissao, $p_rg_emissor, $p_passaporte, $p_sq_pais_passaporte, 
           $p_ddd, $p_nr_telefone, $p_nr_fax, $p_nr_celular, $p_email) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putAcordoPreposto';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_tipo'                      =>array($p_tipo,                                          B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_sq_acordo_outra_parte'     =>array(tvl($p_sq_acordo_outra_parte),                    B_INTEGER,        32),
                   'p_sq_pessoa'                 =>array(tvl($p_sq_pessoa),                                B_INTEGER,        32),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_cargo'                     =>array(tvl($p_cargo),                                    B_VARCHAR,        40),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,        60),
                   'p_nome_resumido'             =>array(tvl($p_nome_resumido),                            B_VARCHAR,        21),
                   'p_sexo'                      =>array(tvl($p_sexo),                                     B_VARCHAR,         1),
                   'p_rg_numero'                 =>array(tvl($p_rg_numero),                                B_VARCHAR,        30),
                   'p_rg_emissao'                =>array(tvl($p_rg_emissao),                               B_DATE,           32),
                   'p_rg_emissor'                =>array(tvl($p_rg_emissor),                               B_VARCHAR,        30),
                   'p_passaporte'                =>array(tvl($p_passaporte),                               B_VARCHAR,        20),
                   'p_sq_pais_passaporte'        =>array(tvl($p_sq_pais_passaporte),                       B_INTEGER,        32),
                   'p_ddd'                       =>array(tvl($p_ddd),                                      B_VARCHAR,         4),
                   'p_nr_telefone'               =>array(tvl($p_nr_telefone),                              B_VARCHAR,        25),
                   'p_nr_fax'                    =>array(tvl($p_nr_fax),                                   B_VARCHAR,        25),
                   'p_nr_celular'                =>array(tvl($p_nr_celular),                               B_VARCHAR,        25),
                   'p_email'                     =>array(tvl($p_email),                                    B_VARCHAR,        60)
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
