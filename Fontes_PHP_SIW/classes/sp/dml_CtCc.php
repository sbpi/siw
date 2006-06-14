<?
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_CtCc
*
* { Description :- 
*    Manipula registros de CT_CC
* }
*/

class dml_CtCc {
   function getInstanceOf($dbms, $operacao, $chave, $sq_cc_pai, $cliente, $nome, $descricao, $sigla, $receita, $regular, $ativo) {
     $sql=$strschema.'sp_putCtCc';
     $params=array('operacao'   =>array($operacao,  B_VARCHAR,      1),
                   'chave'      =>array($chave,     B_NUMERIC,     32),
                   'sq_cc_pai'  =>array($sq_cc_pai, B_NUMERIC,     32),
                   'cliente'    =>array($cliente,   B_NUMERIC,     32),
                   'nome'       =>array($nome,      B_VARCHAR,     60),
                   'descricao'  =>array($descricao, B_VARCHAR,    500),
                   'sigla'      =>array($sigla,     B_VARCHAR,     20),
                   'receita'    =>array($receita,   B_VARCHAR,      1),
                   'regular'    =>array($regular,   B_VARCHAR,      1),
                   'ativo'      =>array($ativo,     B_VARCHAR,      1)
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
