<?
include_once('classes/db/DatabaseQueriesFactory.php');
/**
* class dml_PutCoPesTel
*
* { Description :- 
*    Mantém os telefones da pessoa
* }
*/

class dml_PutCoPesTel {
   function getInstanceOf($dbms, $operacao, $chave, $p_pessoa, $p_tipo_telefone, $p_cidade, $p_ddd, $p_numero, $p_padrao) {
     $sql='sp_putCoPesTel';
     $params=array('operacao'           =>array($operacao,          B_VARCHAR,      1),
                   'chave'              =>array($chave,             B_NUMERIC,     32),
                   'p_pessoa'           =>array($p_pessoa,          B_NUMERIC,     32),
                   'p_ddd'              =>array($p_ddd,             B_VARCHAR,      4),
                   'p_numero'           =>array($p_numero,          B_VARCHAR,     25),
                   'p_tipo_telefone'    =>array($p_tipo_telefone,   B_VARCHAR,     15),
                   'p_cidade'           =>array($p_cidade,          B_NUMERIC,     32),
                   'p_padrao'           =>array($p_padrao,          B_VARCHAR,      1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) return false;  else return true;
   }
}
?>
