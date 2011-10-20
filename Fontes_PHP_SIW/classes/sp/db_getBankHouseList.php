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

    $SQL = "select a.sq_agencia, b.codigo sq_banco, a.nome, a.codigo,$crlf" .
           "       case a.padrao when 'S' then 'Sim' else 'Não' end padrao,$crlf" .
           "       case a.ativo  when 'S' then 'Sim' else 'Não' end ativo $crlf".
           "  from co_agencia a, co_banco b $crlf".
           " where a.sq_banco   = b.sq_banco $crlf" .
           (($p_sq_banco > '')  ? "   and b.sq_banco           = $p_sq_banco$crlf" : "") .
           (($p_nome > '')      ? "   and acentos(a.nome)   like '%'".C."acentos($p_nome)".C."'%'$crlf" : "") .
           (($p_codigo > '')    ? "   and a.codigo             = $p_codigo$crlf" : "");

    $l_rs = $sql->getInstanceOf($dbms, $SQL, $recordcount, 1);
    return $l_rs;
  }
}
?>
