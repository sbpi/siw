<?
include_once('classes/db/DatabaseQueriesFactory.php');
/**
* class dml_CoForm
*
* { Description :- 
*    Manipula registros de CO_Form
* }
*/

class dml_CoForm {
   function getInstanceOf($dbms, $operacao, $chave, $p_tipo, $p_nome, $p_ordem, $p_ativo) {
     $sql='sp_putCoForm';
     $params=array('operacao'          =>array($operacao,          B_VARCHAR,      1),
                   'chave'             =>array($chave,             B_NUMERIC,     32),
                   'p_tipo'            =>array($p_tipo,            B_VARCHAR,      1),
                   'p_nome'            =>array($p_nome,            B_VARCHAR,     50),
                   'p_ordem'           =>array($p_ordem,           B_NUMERIC,     32),
                   'p_ativo'           =>array($p_ativo,           B_VARCHAR,      1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) return false;  else return true;
   }
}
?>
