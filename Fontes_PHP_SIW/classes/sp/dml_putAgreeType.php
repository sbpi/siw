<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putAgreeType
*
* { Description :- 
*    Mantém a tabela de tipos de contrato
* }
*/

class dml_putAgreeType {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_chave_pai, $p_cliente, $p_nome, $p_sigla, $p_modalidade, $p_prazo_indeterm, $p_pessoa_juridica, $p_pessoa_fisica, $p_ativo) {
     $sql=$strschema.'SP_PUTAGREETYPE';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_chave_pai'                 =>array(tvl($p_chave_pai),                                B_INTEGER,        32),
                   'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,        60),
                   'p_sigla'                     =>array(tvl($p_sigla),                                    B_VARCHAR,        10),
                   'p_modalidade'                =>array(tvl($p_modalidade),                               B_VARCHAR,         1),
                   'p_prazo_indeterm'            =>array(tvl($p_prazo_indeterm),                           B_VARCHAR,         1),
                   'p_pessoa_juridica'           =>array(tvl($p_pessoa_juridica),                          B_VARCHAR,         1),
                   'p_pessoa_fisica'             =>array(tvl($p_pessoa_fisica),                            B_VARCHAR,         1),
                   'p_ativo'                     =>array(tvl($p_ativo),                                    B_VARCHAR,         1)
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
