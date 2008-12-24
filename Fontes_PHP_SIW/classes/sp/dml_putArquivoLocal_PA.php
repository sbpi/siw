<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putArquivoLocal_PA
*
* { Description :- 
*    Mantém a tabela de Almoxarifado
* }
*/
class dml_putArquivoLocal_PA{
   function getInstanceOf($dbms, $operacao, $p_chave, $p_nome, $p_local, $p_local_pai, $p_ativo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTARQUIVOLOCAL_PA';
     $params=array('p_operacao'         =>array($operacao,                   B_VARCHAR,         1),
                   'p_chave'            =>array(tvl($p_chave),               B_INTEGER,        32),
                   'p_nome'             =>array(tvl($p_nome),                B_VARCHAR,        60),
                   'p_local'            =>array(tvl($p_local),               B_INTEGER,        32),
                   'p_local_pai'        =>array(tvl($p_local_pai),           B_INTEGER,        32),
                   'p_ativo'            =>array(tvl($p_ativo),               B_VARCHAR,         1)                   );
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
