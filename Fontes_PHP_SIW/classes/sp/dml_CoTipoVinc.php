<?
include_once('classes/db/DatabaseQueriesFactory.php');
/**
* class dml_CoTipoVinc
*
* { Description :- 
*    Manipula registros de CO_TIPO_VINCULO
* }
*/

class dml_CoTipoVinc {
   function getInstanceOf($dbms, $operacao, $chave, $sq_tipo_pessoa, $cliente, $nome, $interno, $contratado, $padrao, $ativo) {
     $sql='sp_putCoTipoVinc';
     $params=array('operacao'           =>array($operacao,          B_VARCHAR,      1),
                   'chave'              =>array($chave,             B_NUMERIC,     32),
                   'sq_tipo_pessoa'     =>array($sq_tipo_pessoa,    B_NUMERIC,     32),
                   'cliente'            =>array($cliente,           B_NUMERIC,     32),
                   'nome'               =>array($nome,              B_VARCHAR,     20),
                   'interno'            =>array($interno,           B_VARCHAR,      1),
                   'contratado'         =>array($contratado,        B_VARCHAR,      1),
                   'padrao'             =>array($padrao,            B_VARCHAR,      1),
                   'ativo'              =>array($ativo,             B_VARCHAR,      1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) return false;  else return true;
   }
}
?>
