<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putCVEscola
*
* { Description :- 
*    Mantém os dados de formação acadêmica do colaborador
* }
*/

class dml_putCVEscola {
   function getInstanceOf($dbms, $operacao, $p_pessoa, $p_chave, $p_sq_area_conhecimento, $p_sq_pais, $p_sq_formacao, $p_nome, $p_instituicao, $p_inicio, $p_fim) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'SP_PUTCVESCOLA';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_pessoa'                    =>array($p_pessoa,                                        B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_sq_area_conhecimento'      =>array(tvl($p_sq_area_conhecimento),                     B_INTEGER,        32),
                   'p_sq_pais'                   =>array($p_sq_pais,                                       B_INTEGER,        32),
                   'p_sq_formacao'               =>array($p_sq_formacao,                                   B_INTEGER,        32),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,        80),
                   'p_instituicao'               =>array($p_instituicao,                                   B_VARCHAR,       100),
                   'p_inicio'                    =>array($p_inicio,                                        B_VARCHAR,         7),
                   'p_fim'                       =>array(tvl($p_fim),                                      B_VARCHAR,         7)
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
