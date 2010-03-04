<?php
include_once($w_dir_volta.'classes/sp/db_getCadastrador_CL.php');
// =========================================================================
// Função que retorna S/N indicando se o usuário informado pode cadastrar
// pedidos de compra para qualquer pessoa ou somente para ele mesmo
// -------------------------------------------------------------------------
function retornaCadastrador_CL($p_menu,$p_usuario) {
  extract($GLOBALS);

  $l_acesso = db_getCadastrador_CL::getInstanceOf($dbms,$p_menu, $p_usuario);
  return $l_acesso;
} 
?>
