<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putCVIdent
*
* { Description :- 
*    Mantém os dados de identificacao do colaborador
* }
*/

class dml_putCVIdent {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_chave, $p_nome, $p_nome_resumido, $p_nascimento, $p_sexo, $p_sq_estado_civil, $p_sq_formacao, $p_sq_etnia, $p_sq_deficiencia, $p_cidade, $p_rg_numero, $p_rg_emissor, $p_rg_emissao, $p_cpf, $p_passaporte_numero, $p_sq_pais_passaporte, $p_foto, $p_tamanho, $p_tipo, $p_nome_original, $p_chave_nova) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'SP_PUTCVIDENT';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_cliente'                   =>array($p_cliente,                                       B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_nome'                      =>array($p_nome,                                          B_VARCHAR,        60),
                   'p_nome_resumido'             =>array($p_nome_resumido,                                 B_VARCHAR,        21),
                   'p_foto'                      =>array(tvl($p_foto),                                     B_VARCHAR,       255),
                   'p_tamanho'                   =>array(tvl($p_tamanho),                                  B_INTEGER,        32),
                   'p_tipo'                      =>array(tvl($p_tipo),                                     B_VARCHAR,       100),
                   'p_nome_original'             =>array(tvl($p_nome_original),                            B_VARCHAR,       255),
                   'p_nascimento'                =>array($p_nascimento,                                    B_DATE,           32),
                   'p_sexo'                      =>array($p_sexo,                                          B_VARCHAR,         1),
                   'p_sq_estado_civil'           =>array($p_sq_estado_civil,                               B_INTEGER,        32),
                   'p_sq_formacao'               =>array($p_sq_formacao,                                   B_INTEGER,        32),
                   'p_sq_etnia'                  =>array($p_sq_etnia,                                      B_INTEGER,        32),
                   'p_sq_deficiencia'            =>array(tvl($p_sq_deficiencia),                           B_INTEGER,        32),
                   'p_cidade'                    =>array($p_cidade,                                        B_INTEGER,        32),
                   'p_rg_numero'                 =>array($p_rg_numero,                                     B_VARCHAR,        30),
                   'p_rg_emissor'                =>array($p_rg_emissor,                                    B_VARCHAR,        30),
                   'p_rg_emissao'                =>array($p_rg_emissao,                                    B_DATE,           32),
                   'p_cpf'                       =>array($p_cpf,                                           B_VARCHAR,        14),
                   'p_passaporte_numero'         =>array(tvl($p_passaporte_numero),                        B_VARCHAR,        20),
                   'p_sq_pais_passaporte'        =>array(tvl($p_sq_pais_passaporte),                       B_INTEGER,        32),
                   'p_chave_nova'                =>array(&$p_chave_nova,                                   B_INTEGER,        32)
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
