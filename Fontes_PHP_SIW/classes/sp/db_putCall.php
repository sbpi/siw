<?
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_putCall
*
* { Description :- 
*    Grava dados da ligação
* }
*/

class db_putCall {
   function getInstanceOf($dbms, $p_operacao, $p_chave, $p_destino, $p_sq_cc, $p_contato, $p_assunto, $p_pessoa, $p_fax, $p_trabalho) {
     $sql=$strschema.'SP_PUTCALL';
     $params=array('p_operacao'                  =>array($p_operacao,                                      B_VARCHAR,         1),
                   'p_chave'                     =>array($p_chave,                                         B_INTEGER,        32),
                   'p_destino'                   =>array(tvl($p_destino),                                  B_INTEGER,        32),
                   'p_sq_cc'                     =>array(tvl($p_sq_cc),                                    B_INTEGER,        32),
                   'p_contato'                   =>array(tvl($p_contato),                                  B_VARCHAR,        60),
                   'p_assunto'                   =>array(tvl($p_assunto),                                  B_VARCHAR,      1000),
                   'p_pessoa'                    =>array(tvl($p_pessoa),                                   B_INTEGER,        32),
                   'p_fax'                       =>array(tvl($p_fax),                                      B_VARCHAR,         1),
                   'p_trabalho'                  =>array(tvl($p_trabalho),                                 B_VARCHAR,         1)
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
