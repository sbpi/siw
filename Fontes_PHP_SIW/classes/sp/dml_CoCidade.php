<?
include_once('classes/db/DatabaseQueriesFactory.php');
/**
* class dml_CoCidade
*
* { Description :- 
*    Manipula registros de CO_CIDADE
* }
*/

class dml_CoCidade {
   function getInstanceOf($dbms, $operacao, $chave, $p_ddd, $p_codigo_ibge, $p_sq_pais, $p_sq_regiao, $p_co_uf, $p_nome, $p_capital) {
     $sql='sp_putCoCidade';
     $params=array('operacao'          =>array($operacao,          B_VARCHAR,      1),
                   'chave'             =>array($chave,             B_NUMERIC,     32),
                   'p_ddd'             =>array($p_ddd,             B_VARCHAR,      4),
                   'p_codigo_ibge'     =>array($p_codigo_ibge,     B_VARCHAR,     20),
                   'p_sq_pais'         =>array($p_sq_pais,         B_NUMERIC,     32),
                   'p_sq_regiao'       =>array($p_sq_regiao,       B_NUMERIC,     32),
                   'p_co_uf'           =>array($p_co_uf,           B_VARCHAR,      3),
                   'p_nome'            =>array($p_nome,            B_VARCHAR,     60),
                   'p_capital'         =>array($p_capital,         B_VARCHAR,      1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) return false;  else return true;
   }
}
?>
