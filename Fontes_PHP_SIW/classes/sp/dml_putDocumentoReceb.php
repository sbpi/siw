<?
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getDocumentoReceb
*
* { Description :- 
*    Registra o recebimento de protocolos
* }
*/

class dml_putDocumentoReceb {
   function getInstanceOf($dbms, $p_pessoa, $p_unid_autua, $p_nu_guia, $p_ano_guia) {

     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putDocumentoReceb';
     $params=array('p_pessoa'               =>array($p_pessoa,                                  B_INTEGER,        32),
                   'p_unid_autua'           =>array(tvl($p_unid_autua),                         B_INTEGER,        32),
                   'p_nu_guia'              =>array(tvl($p_nu_guia),                            B_INTEGER,        32),
                   'p_ano_guia'             =>array(tvl($p_ano_guia),                           B_INTEGER,        32)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); 
     error_reporting(0); 
     if(!$l_rs->executeQuery()) { 
       error_reporting($l_error_reporting); 
       $Err = $l_rs->getError();
       $p_resultado = $Err['message'];
     } else {
       error_reporting($l_error_reporting); 
       return true;
     }
   }
}
?>
