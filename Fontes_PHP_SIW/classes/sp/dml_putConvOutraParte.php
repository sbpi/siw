<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putConvOutraParte
*
* { Description :- 
*    Mantém a tabela de tipos de documento
* }
*/

class dml_putConvOutraParte {
   function getInstanceOf($dbms, $operacao, $p_restricao, $p_sq_acordo_outra_parte, $p_chave, $p_sq_pessoa, $p_tipo, $p_chave_aux, $p_cpf, $p_cnpj, $p_nome,
        $p_nome_resumido, $p_sexo, $p_nascimento, $p_rg_numero, $p_rg_emissao, $p_rg_emissor, $p_passaporte, $p_sq_pais_passaporte, 
        $p_inscricao_estadual, $p_logradouro, $p_complemento, $p_bairro, $p_sq_cidade, $p_cep, $p_ddd, $p_nr_telefone, $p_nr_fax, 
        $p_nr_celular, $p_email, $p_sq_agencia, $p_op_conta, $p_nr_conta, $p_sq_pais_estrang, $p_aba_code, $p_swift_code, 
        $p_endereco_estrang, $p_banco_estrang, $p_agencia_estrang, $p_cidade_estrang, $p_informacoes, $p_codigo_deposito, 
        $p_pessoa_atual) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putConvOutraParte';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_restricao'                 =>array($p_restricao,                                     B_VARCHAR,        10),
                   'p_sq_acordo_outra_parte'     =>array(tvl($p_sq_acordo_outra_parte),                    B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_sq_pessoa'                 =>array(tvl($p_sq_pessoa),                                B_INTEGER,        32),
                   'p_tipo'                      =>array(tvl($p_tipo),                                     B_INTEGER,        32),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_cpf'                       =>array(tvl($p_cpf),                                      B_VARCHAR,        14),                   
                   'p_cnpj'                      =>array(tvl($p_cnpj),                                     B_VARCHAR,        18),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,        60),
                   'p_nome_resumido'             =>array(tvl($p_nome_resumido),                            B_VARCHAR,        21),
                   'p_sexo'                      =>array(tvl($p_sexo),                                     B_VARCHAR,         1),
                   'p_nascimento'                =>array(tvl($p_nascimento),                               B_DATE,           32),
                   'p_rg_numero'                 =>array(tvl($p_rg_numero),                                B_VARCHAR,        30),
                   'p_rg_emissao'                =>array(tvl($p_rg_emissao),                               B_DATE,           32),
                   'p_rg_emissor'                =>array(tvl($p_rg_emissor),                               B_VARCHAR,        30),
                   'p_passaporte'                =>array(tvl($p_passaporte),                               B_VARCHAR,        20),
                   'p_sq_pais_passaporte'        =>array(tvl($p_sq_pais_passaporte),                       B_INTEGER,        32),
                   'p_inscricao_estadual'        =>array(tvl($p_inscricao_estadual),                       B_VARCHAR,        20),
                   'p_logradouro'                =>array(tvl($p_logradouro),                               B_VARCHAR,        60),
                   'p_complemento'               =>array(tvl($p_complemento),                              B_VARCHAR,        20),
                   'p_bairro'                    =>array(tvl($p_bairro),                                   B_VARCHAR,        30),
                   'p_sq_cidade'                 =>array(tvl($p_sq_cidade),                                B_INTEGER,        32),
                   'p_cep'                       =>array(tvl($p_cep),                                      B_VARCHAR,         9),
                   'p_ddd'                       =>array(tvl($p_ddd),                                      B_VARCHAR,         4),
                   'p_nr_telefone'               =>array(tvl($p_nr_telefone),                              B_VARCHAR,        25),
                   'p_nr_fax'                    =>array(tvl($p_nr_fax),                                   B_VARCHAR,        25),
                   'p_nr_celular'                =>array(tvl($p_nr_celular),                               B_VARCHAR,        25),
                   'p_email'                     =>array(tvl($p_email),                                    B_VARCHAR,        60),
                   'p_sq_agencia'                =>array(tvl($p_sq_agencia),                               B_INTEGER,        32),
                   'p_op_conta'                  =>array(tvl($p_op_conta),                                 B_VARCHAR,         6),
                   'p_nr_conta'                  =>array(tvl($p_nr_conta),                                 B_VARCHAR,        30),
                   'p_sq_pais_estrang'           =>array(tvl($p_sq_pais_estrang),                          B_INTEGER,        32),
                   'p_aba_code'                  =>array(tvl($p_aba_code),                                 B_VARCHAR,        12),
                   'p_swift_code'                =>array(tvl($p_swift_code),                               B_VARCHAR,        30),
                   'p_endereco_estrang'          =>array(tvl($p_endereco_estrang),                         B_VARCHAR,       100),
                   'p_banco_estrang'             =>array(tvl($p_banco_estrang),                            B_VARCHAR,        20),
                   'p_agencia_estrang'           =>array(tvl($p_agencia_estrang),                          B_VARCHAR,        60),
                   'p_cidade_estrang'            =>array(tvl($p_cidade_estrang),                           B_VARCHAR,        60),
                   'p_informacoes'               =>array(tvl($p_informacoes),                              B_VARCHAR,       200),
                   'p_codigo_deposito'           =>array(tvl($p_codigo_deposito),                          B_VARCHAR,        50),
                   'p_pessoa_atual'              =>array(tvl($p_pessoa_atual),                             B_INTEGER,        32)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
