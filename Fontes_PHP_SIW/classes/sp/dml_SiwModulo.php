<?
include_once('classes/db/DatabaseQueriesFactory.php');
/**
* class dml_SiwModulo
*
* { Description :- 
*    Manipula registros de SIW_Modulo
* }
*/

class dml_SiwModulo {
   function getInstanceOf($dbms, $operacao, $chave, $nome, $sigla, $objetivo_geral) {
     $sql='sp_putSiwModulo';
     $params=array('operacao'       =>array($operacao,          B_VARCHAR,      1),
                   'chave'          =>array($chave,             B_NUMERIC,     32),
                   'nome'           =>array($nome,              B_VARCHAR,     60),
                   'sigla'          =>array($sigla,             B_VARCHAR,      3),
                   'objetivo_geral' =>array($objetivo_geral,    B_VARCHAR,   4000)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) return false;  else return true;
   }
}
?>
