<?
include_once('classes/db/DatabaseQueriesFactory.php');
/**
* class dml_CoTpPessoa
*
* { Description :- 
*    Manipula registros de CO_TIPO_PESSOA
* }
*/

class dml_CoTpPessoa {
   function getInstanceOf($dbms, $operacao, $chave, $p_nome, $p_padrao, $p_ativo) {
     $sql='sp_putCoTpPessoa';
     $params=array('operacao'          =>array($operacao,          B_VARCHAR,      1),
                   'chave'             =>array($chave,             B_NUMERIC,     32),
                   'p_nome'            =>array($p_nome,            B_VARCHAR,     25),
                   'p_padrao'          =>array($p_padrao,          B_VARCHAR,      1),
                   'p_ativo'           =>array($p_ativo,           B_VARCHAR,      1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) return false;  else return true;
   }
}
?>
