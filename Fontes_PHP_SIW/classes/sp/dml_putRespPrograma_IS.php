<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putRespPrograma_IS
*
* { Description :- 
*    Atualiza os responsaveis e seus dados no Programa PPA
* }
*/

class dml_putRespPrograma_IS {
   function getInstanceOf($dbms, $p_chave, $p_nm_gerente_programa, $p_fn_gerente_programa, $p_em_gerente_programa, $p_nm_gerente_executivo, $p_fn_gerente_executivo, $p_em_gerente_executivo, $p_nm_gerente_adjunto, $p_fn_gerente_adjunto, $p_em_gerente_adjunto) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTRESPPROGRAMA_IS';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_nm_gerente_programa'       =>array(tvl($p_nm_gerente_programa),                      B_VARCHAR,        60),
                   'p_fn_gerente_programa'       =>array(tvl($p_fn_gerente_programa),                      B_VARCHAR,        20),
                   'p_em_gerente_programa'       =>array(tvl($p_em_gerente_programa),                      B_VARCHAR,        60),
                   'p_nm_gerente_executivo'      =>array(tvl($p_nm_gerente_executivo),                     B_VARCHAR,        60),
                   'p_fn_gerente_executivo'      =>array(tvl($p_fn_gerente_executivo),                     B_VARCHAR,        20),
                   'p_em_gerente_executivo'      =>array(tvl($p_em_gerente_executivo),                     B_VARCHAR,        60),
                   'p_nm_gerente_adjunto'        =>array(tvl($p_nm_gerente_adjunto),                       B_VARCHAR,        60),
                   'p_fn_gerente_adjunto'        =>array(tvl($p_fn_gerente_adjunto),                       B_VARCHAR,        20),
                   'p_em_gerente_adjunto'        =>array(tvl($p_em_gerente_adjunto),                       B_VARCHAR,        60)
                  );
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
