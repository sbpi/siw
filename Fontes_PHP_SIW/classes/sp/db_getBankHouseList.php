<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/sp/db_exec.php');
/**
 * class sp_getBankHouseList
 *
 * { Description :- 
 *    Recupera as agências existentes
 * }
 */
class db_getBankHouseList {

  function getInstanceOf($dbms, $p_sq_banco, $p_nome, $p_ordena, $p_codigo) {
    extract($GLOBALS, EXTR_PREFIX_SAME, 'strchema');

    $params=array("p_sq_banco"     =>array($p_sq_banco,       B_NUMERIC,     32),
                  "p_nome"         =>array($p_nome,           B_VARCHAR,     40),
                  "p_codigo"       =>array($p_codigo,         B_VARCHAR,     30),
                  "p_result"       =>array(null,              B_CURSOR,      -1)
                 );

    $sql = new db_exec; $par = $sql->normalize($params); extract($par,EXTR_OVERWRITE);

    $SQL = "select a.sq_agencia, b.codigo sq_banco, a.nome, a.codigo, a.ativo, a.padrao,$crlf" .
           "       case a.padrao when 'S' then 'Sim' else 'Não' end as nm_padrao,$crlf" .
           "       case a.ativo  when 'S' then 'Sim' else 'Não' end nm_ativo $crlf".
           "  from co_agencia          a$crlf" .
           "       inner join co_banco b on (a.sq_banco = b.sq_banco)$crlf" .
           " where 1 = 1$crlf".
           "   and ($p_sq_banco is null or ($p_sq_banco is not null and b.sq_banco      = $p_sq_banco))$crlf".
           "   and ($p_nome     is null or ($p_nome     is not null and acentos(a.nome) like '%'".C."acentos($p_nome)".C."'%'))$crlf".
           "   and ($p_codigo   is null or ($p_codigo   is not null and a.codigo        = $p_codigo))$crlf";

    $l_rs = $sql->getInstanceOf($dbms, $SQL, $params);
    return $l_rs;
  }
}
?>
