<?
include_once('classes/db/DatabaseQueriesFactory.php');
/**
* class dml_CoSegmento
*
* { Description :- 
*    Manipula registros de CO_Segmento
* }
*/

class dml_CoSegmento {
   function getInstanceOf($dbms, $operacao, $chave, $nome, $padrao, $ativo) {
     $sql='sp_putCoSegmento';
     $params=array('operacao'   =>array($operacao,  B_VARCHAR,      1),
                   'chave'      =>array($chave,     B_NUMERIC,     32),
                   'nome'       =>array($nome,      B_VARCHAR,     40),
                   'padrao'     =>array($padrao,    B_VARCHAR,      1),
                   'ativo'      =>array($ativo,     B_VARCHAR,      1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) return false;  else return true;
   }
}
?>
