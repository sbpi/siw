<?
include_once('classes/db/DatabaseQueriesFactory.php');
/**
* class dml_SiwModSeg
*
* { Description :- 
*    Manipula registros de DM_Segmento_Vinculo
* }
*/

class dml_SiwModSeg {
   function getInstanceOf($dbms, $operacao, $objetivo_especifico, $sq_modulo, $sq_segmento, $comercializar, $ativo) {
     $sql='sp_putSiwModSeg';
     $params=array('operacao'               =>array($operacao,              B_VARCHAR,      1),
                   'objetivo_especifico'    =>array($objetivo_especifico,   B_VARCHAR,   4000),
                   'sq_modulo'              =>array($sq_modulo,             B_NUMERIC,     32),
                   'sq_segmento'            =>array($sq_segmento,           B_NUMERIC,     32),
                   'comercializar'          =>array($comercializar,         B_VARCHAR,      1),
                   'ativo'                  =>array($ativo,                 B_VARCHAR,      1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) return false;  else return true;
   }
}
?>
