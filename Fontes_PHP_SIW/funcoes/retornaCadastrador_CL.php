<?php
include_once($w_dir_volta.'classes/sp/db_getCadastrador_CL.php');
// =========================================================================
// Fun��o que retorna S/N indicando se o usu�rio informado pode cadastrar
// pedidos de compra para qualquer pessoa ou somente para ele mesmo
// -------------------------------------------------------------------------
function retornaCadastrador_CL($p_menu,$p_usuario) {
  extract($GLOBALS);

  $sql = new db_getCadastrador_CL; $l_acesso = $sql->getInstanceOf($dbms,$p_menu, $p_usuario);
  return $l_acesso;
} 
?>
