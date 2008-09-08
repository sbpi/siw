<?
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_PutCoPesConBan
*
* { Description :- 
*    Mantém as contas bancárias da pessoa
* }
*/

class dml_PutCoPesConBan {
   function getInstanceOf($dbms, $operacao, $chave, $p_pessoa, $p_tipo_conta, $p_agencia, $p_oper, $p_numero, $p_ativo, $p_padrao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putCoPesConBan';
     $params=array('operacao'           =>array($operacao,          B_VARCHAR,      1),
                   'chave'              =>array($chave,             B_NUMERIC,     32),
                   'p_pessoa'           =>array($p_pessoa,          B_NUMERIC,     32),
                   'p_agencia'          =>array($p_agencia,         B_NUMERIC,     32),
                   'p_oper'             =>array($p_oper,            B_VARCHAR,      6),
                   'p_numero'           =>array($p_numero,          B_VARCHAR,     30),
                   'p_tipo_conta'       =>array($p_tipo_conta,      B_VARCHAR,      1),
                   'p_ativo'            =>array($p_ativo,           B_VARCHAR,      4),
                   'p_padrao'           =>array($p_padrao,          B_VARCHAR,      1)
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
