<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPAParametro
*
* { Description :- 
*    Mant�m a tabela de par�metros do m�dulo protocolo e arquivos.
* }
*/

class dml_putPAParametro {
   function getInstanceOf($dbms, $p_cliente, $p_despacho_arqcentral, $p_despacho_desarqcentral, $p_despacho_emprestimo, 
           $p_despacho_devolucao, $p_despacho_autuar, $p_despacho_arqsetorial, $p_despacho_anexar, $p_despacho_apensar, 
           $p_despacho_eliminar, $p_despacho_desmembrar, $p_arquivo_central, $p_limite_interessados, $p_ano_corrente,
           $p_envio_externo, $p_emite_guia_remessa) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putPAParametro';
     $params=array('p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_despacho_arqcentral'       =>array(tvl($p_despacho_arqcentral),                      B_INTEGER,        32),
                   'p_despacho_desarqcentral'    =>array(tvl($p_despacho_desarqcentral),                   B_INTEGER,        32),
                   'p_despacho_emprestimo'       =>array(tvl($p_despacho_emprestimo),                      B_INTEGER,        32),
                   'p_despacho_devolucao'        =>array(tvl($p_despacho_devolucao),                       B_INTEGER,        32),
                   'p_despacho_autuar'           =>array(tvl($p_despacho_autuar),                          B_INTEGER,        32),
                   'p_despacho_arqsetorial'      =>array(tvl($p_despacho_arqsetorial),                     B_INTEGER,        32),
                   'p_despacho_anexar'           =>array(tvl($p_despacho_anexar),                          B_INTEGER,        32),
                   'p_despacho_apensar'          =>array(tvl($p_despacho_apensar),                         B_INTEGER,        32),
                   'p_despacho_eliminar'         =>array(tvl($p_despacho_eliminar),                        B_INTEGER,        32),
                   'p_despacho_desmembrar'       =>array(tvl($p_despacho_desmembrar),                      B_INTEGER,        32),
                   'p_arquivo_central'           =>array(tvl($p_arquivo_central),                          B_INTEGER,        32),
                   'p_limite_interessados'       =>array(tvl($p_limite_interessados),                      B_INTEGER,        32),
                   'p_ano_corrente'              =>array(tvl($p_ano_corrente),                             B_INTEGER,        32),
                   'p_envio_externo'             =>array(tvl($p_envio_externo),                            B_VARCHAR,         1),
                   'p_emite_guia_remessa'       =>array(tvl($p_emite_guia_remessa),                        B_VARCHAR,         1)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(E_ERROR); 
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
