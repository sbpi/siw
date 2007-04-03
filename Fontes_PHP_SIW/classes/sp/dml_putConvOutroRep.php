<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putConvOutroRep
*
* { Description :- 
*    Mantém a tabela de tipos de documento
* }
*/

class dml_putConvOutroRep {
   function getInstanceOf($dbms, $operacao, $p_restricao, $p_chave, $p_sq_pessoa, $p_sq_acordo_outra_parte, $p_chave_aux, $p_cpf, $p_nome, $p_nome_resumido, $p_sexo, $p_rg_numero, $p_rg_emissao, $p_rg_emissor, $p_ddd, $p_nr_telefone, $p_nr_fax, $p_nr_celular, $p_email, $p_cargo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTCONVOUTROREP';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_restricao'                 =>array($p_restricao,                                     B_VARCHAR,        10),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_sq_pessoa'                 =>array(tvl($p_sq_pessoa),                                B_INTEGER,        32),
                   'p_sq_acordo_outra_parte'     =>array(tvl($p_sq_acordo_outra_parte),                    B_INTEGER,        32),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_cpf'                       =>array(tvl($p_cpf),                                      B_VARCHAR,        14),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,        60),
                   'p_nome_resumido'             =>array(tvl($p_nome_resumido),                            B_VARCHAR,        15),
                   'p_sexo'                      =>array(tvl($p_sexo),                                     B_VARCHAR,         1),
                   'p_rg_numero'                 =>array(tvl($p_rg_numero),                                B_VARCHAR,        30),
                   'p_rg_emissao'                =>array(tvl($p_rg_emissao),                               B_DATE,           32),
                   'p_rg_emissor'                =>array(tvl($p_rg_emissor),                               B_VARCHAR,        30),
                   'p_ddd'                       =>array(tvl($p_ddd),                                      B_VARCHAR,         4),
                   'p_nr_telefone'               =>array(tvl($p_nr_telefone),                              B_VARCHAR,        25),
                   'p_nr_fax'                    =>array(tvl($p_nr_fax),                                   B_VARCHAR,        25),
                   'p_nr_celular'                =>array(tvl($p_nr_celular),                               B_VARCHAR,        25),
                   'p_email'                     =>array(tvl($p_email),                                    B_VARCHAR,        60),
                   'p_cargo'                     =>array(tvl($p_cargo),                                    B_VARCHAR,        40)
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
