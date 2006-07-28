<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putTTPrefixo 
*
* { Description :- 
*    Mantém a tabela de centrais telefônicas
* }
*/

class dml_putTTPrefixo  {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_prefixo, $p_localidade, $p_sigla, $p_uf, $p_ddd, $p_controle, $p_degrau) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTTTPREFIXO';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        18),
                   'p_prefixo'                   =>array(tvl($p_prefixo),                                  B_VARCHAR,        15),
                   'p_localidade'                =>array(tvl($p_localidade),                               B_VARCHAR,        25),
                   'p_sigla'                     =>array(tvl($p_sigla),                                    B_VARCHAR,         4),
                   'p_uf'                        =>array(tvl($p_uf),                                       B_VARCHAR,         2),
                   'p_ddd'                       =>array(tvl($p_ddd),                                      B_VARCHAR,         4),
                   'p_controle'                  =>array(tvl($p_controle),                                 B_VARCHAR,        16),
                   'p_degrau'                    =>array(tvl($p_degrau),                                   B_VARCHAR,         3)
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
