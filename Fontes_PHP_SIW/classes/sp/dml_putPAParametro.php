<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPAParametro
*
* { Description :- 
*    Mantém a tabela de parâmetros do módulo protocolo e arquivos.
* }
*/

class dml_putPAParametro {
   function getInstanceOf($dbms, $p_cliente, $p_despacho_arqcentral, $p_despacho_emprestimo, $p_despacho_devolucao,  $p_despacho_autuar, $p_despacho_arqsetorial, 
            $p_despacho_anexar, $p_despacho_apensar, $p_despacho_eliminar,$p_arquivo_central,
            $p_limite_interessados, $p_ano_corrente) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTPAPARAMETRO';
     $params=array('p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_despacho_arqcentral'       =>array(tvl($p_despacho_arqcentral),                      B_INTEGER,        32),
                   'p_despacho_emprestimo'       =>array(tvl($p_despacho_emprestimo),                      B_INTEGER,        32),
                   'p_despacho_devolucao'        =>array(tvl($p_despacho_devolucao),                       B_INTEGER,        32),
                   'p_despacho_autuar'           =>array(tvl($p_despacho_autuar),                          B_INTEGER,        32),
                   'p_despacho_arqsetorial'      =>array(tvl($p_despacho_arqsetorial),                     B_INTEGER,        32),
                   'p_despacho_anexar'           =>array(tvl($p_despacho_anexar),                          B_INTEGER,        32),
                   'p_despacho_apensar'          =>array(tvl($p_despacho_apensar),                         B_INTEGER,        32),
                   'p_despacho_eliminar'         =>array(tvl($p_despacho_eliminar),                        B_INTEGER,        32),
                   'p_arquivo_central'           =>array(tvl($p_arquivo_central),                          B_INTEGER,        32),
                   'p_limite_interessados'       =>array(tvl($p_limite_interessados),                      B_INTEGER,        32),
                   'p_ano_corrente'              =>array(tvl($p_ano_corrente),                             B_INTEGER,        32),
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
