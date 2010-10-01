<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_PutGPPensionista 
*
* { Description :- 
*    Grava a tela de outra parte
* }
*/

class dml_PutGPPensionista  {
   function getInstanceOf($dbms, $operacao, $p_restricao, $p_chave, $p_chave_aux, $p_colaborador, $p_sq_pessoa, 
        $p_cpf, $p_nome, $p_nome_resumido, $p_sexo, $p_rg_numero, $p_rg_emissao, $p_rg_emissor, 
        $p_ddd, $p_nr_telefone, $p_nr_fax, $p_nr_celular, $p_sq_agencia, $p_op_conta, $p_nr_conta, 
        $p_tipo, $p_valor, $p_inicio, $p_fim, $p_observacao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_PutGPPensionista';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_restricao'                 =>array($p_restricao,                                     B_VARCHAR,        10),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_colaborador'               =>array(tvl($p_colaborador),                              B_INTEGER,        32),
                   'p_sq_pessoa'                 =>array(tvl($p_sq_pessoa),                                B_INTEGER,        32),
                   'p_cpf'                       =>array(tvl($p_cpf),                                      B_VARCHAR,        14),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,        60),
                   'p_nome_resumido'             =>array(tvl($p_nome_resumido),                            B_VARCHAR,        21),
                   'p_sexo'                      =>array(tvl($p_sexo),                                     B_VARCHAR,         1),
                   'p_rg_numero'                 =>array(tvl($p_rg_numero),                                B_VARCHAR,        30),
                   'p_rg_emissao'                =>array(tvl($p_rg_emissao),                               B_DATE,           32),
                   'p_rg_emissor'                =>array(tvl($p_rg_emissor),                               B_VARCHAR,        30),
                   'p_ddd'                       =>array(tvl($p_ddd),                                      B_VARCHAR,         4),
                   'p_nr_telefone'               =>array(tvl($p_nr_telefone),                              B_VARCHAR,        25),
                   'p_nr_fax'                    =>array(tvl($p_nr_fax),                                   B_VARCHAR,        25),
                   'p_nr_celular'                =>array(tvl($p_nr_celular),                               B_VARCHAR,        25),
                   'p_sq_agencia'                =>array(tvl($p_sq_agencia),                               B_INTEGER,        32),
                   'p_op_conta'                  =>array(tvl($p_op_conta),                                 B_VARCHAR,         6),
                   'p_nr_conta'                  =>array(tvl($p_nr_conta),                                 B_VARCHAR,        30),
                   'p_tipo'                      =>array(tvl($p_tipo),                                     B_INTEGER,        30),
                   'p_valor'                     =>array(toNumber(tvl($p_valor)),                          B_NUMERIC,      18,2),
                   'p_inicio'                    =>array(tvl($p_inicio),                                   B_DATE,           32),
                   'p_fim'                       =>array(tvl($p_fim),                                      B_DATE,           32),     
                   'p_observacao'                =>array(tvl($p_observacao),                               B_VARCHAR,        1000)
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
