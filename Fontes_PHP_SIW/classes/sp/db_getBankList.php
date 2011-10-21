<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/sp/db_exec.php');
/**
* class sp_getBankList
*
* { Description :- 
*    Recupera os bancos existentes
* }
*/

class db_getBankList {
  function getInstanceOf($dbms, $p_codigo, $p_nome, $p_ativo) {
    extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getBankList';
    $params=array("p_codigo"   =>array($p_codigo,      B_VARCHAR,     30),
                  "p_nome"     =>array($p_nome,        B_VARCHAR,     30),
                  "p_ativo"    =>array($p_ativo,       B_VARCHAR,      1),
                  "p_result"   =>array(null,           B_CURSOR,      -1)
                 );

    $sql = new db_exec; $par = $sql->normalize($params); extract($par,EXTR_OVERWRITE);

    $SQL = "select a.sq_banco, a.codigo, a.nome, a.ativo, a.codigo".C."' - '".C."a.nome as descricao, a.padrao, a.exige_operacao,$crlf" .
           "       case a.padrao when 'S' then 'Sim' else 'Não' end as padrao,$crlf" .
           "       case a.ativo  when 'S' then 'Sim' else 'Não' end as ativo $crlf".
           "  from co_banco a$crlf".
           " where ($p_nome   is null or ($p_nome   is not null and acentos(nome) like '%'".C."acentos($p_nome)".C."'%'))$crlf".
           "   and ($p_codigo is null or ($p_codigo is not null and codigo = $p_codigo))$crlf".
           "   and ($p_ativo  is null or ($p_ativo  is not null and ativo  = $p_ativo))$crlf".
           "order by a.padrao desc, a.codigo";
    $l_rs = $sql->getInstanceOf($dbms, $SQL, $params);
    return $l_rs;
   }
}    
?>
