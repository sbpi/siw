<?
include_once('classes/db/DatabaseQueriesFactory.php');
/**
* class dml_CoPais
*
* { Description :- 
*    Manipula registros de CO_PAIS
* }
*/

class dml_CoPais {
   function getInstanceOf($dbms, $operacao, $chave, $p_nome, $p_ativo, $p_padrao, $p_ddi, $p_sigla) {
     $sql='sp_putCoPais';
     $params=array('operacao'          =>array($operacao,          B_VARCHAR,      1),
                   'chave'             =>array($chave,             B_NUMERIC,     32),
                   'p_nome'            =>array($p_nome,            B_VARCHAR,     60),
                   'p_ativo'           =>array($p_ativo,           B_VARCHAR,      1),
                   'p_padrao'          =>array($p_padrao,          B_VARCHAR,      1),
                   'p_ddi'             =>array($p_ddi,             B_VARCHAR,     10),
                   'p_sigla'           =>array($p_sigla,           B_VARCHAR,      3)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) return false;  else return true;
   }
}
?>
