<?php
extract($GLOBALS);include_once($w_dir_volta.'classes/sp/db_exec.php');
/**
* class dml_putMeioTrans
*
* { Description :- 
*    Mantém a tabela de meios de transporte
* }
*/

class dml_putMeioTrans {
  function getInstanceOf($dbms, $operacao, $p_cliente, $p_chave, $p_nome, $p_aereo, $p_rodoviario, $p_ferroviario, $p_aquaviario, $p_ativo) {
    extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putMeioTrans';
    $params=array('p_operacao'         =>array($operacao,                       B_VARCHAR,         1),
                 'p_cliente'           =>array(tvl($p_cliente),                 B_INTEGER,        32),
                 'p_chave'             =>array(tvl($p_chave),                   B_INTEGER,        32),
                 'p_nome'              =>array(tvl($p_nome),                    B_VARCHAR,        60),
                 'p_aereo'             =>array(tvl($p_aereo),                   B_VARCHAR,         1),
                 'p_rodoviario'        =>array(tvl($p_rodoviario),              B_VARCHAR,         1),
                 'p_ferroviario'       =>array(tvl($p_ferroviario),             B_VARCHAR,         1),
                 'p_aquaviario'        =>array(tvl($p_aquaviario),              B_VARCHAR,         1),
                 'p_ativo'             =>array(tvl($p_ativo),                   B_VARCHAR,         1)
                );

    $sql = new db_exec; $par = $sql->normalize($params); extract($par,EXTR_OVERWRITE);

    if ($operacao=='I') {
      // Insere registro
      $SQL = "insert into pd_meio_transporte (sq_meio_transporte, cliente, nome, aereo, rodoviario, ferroviario,aquaviario, ativo)$crlf".
             "(select sq_meio_transporte.nextval, $p_cliente, $p_nome, $p_aereo, $p_rodoviario, $p_ferroviario, $p_aquaviario, $p_ativo from dual)$crlf";
    } elseif ($operacao=='A') {
      // Altera registro
      $SQL = "update pd_meio_transporte$crlf".
             "   set nome         = $p_nome,$crlf".
             "       aereo        = $p_aereo,$crlf".
             "       rodoviario   = $p_rodoviario,$crlf".
             "       ferroviario  = $p_ferroviario,$crlf".
             "       aquaviario   = $p_aquaviario,$crlf".
             "       ativo        = $p_ativo$crlf".
             "where sq_meio_transporte = $p_chave$crlf";
    } elseif ($operacao=='E') {
      // Exclui registro
      $SQL = "delete pd_meio_transporte where sq_meio_transporte = $p_chave$crlf";
    }
    $l_rs = $sql->getInstanceOf($dbms, $SQL, $params);
    return $l_rs;
  }
}
?>
