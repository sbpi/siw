<?
include_once($w_dir_volta.'classes/sp/db_getCadastrador_PD.php');
// =========================================================================
// Função que retorna S/N indicando se o usuário informado pode cadastrar
// viagens para qualquer pessoa ou somente para ele mesmo
// -------------------------------------------------------------------------
function retornaCadastrador_PD($p_menu,$p_usuario) {
  extract($GLOBALS);

  $l_acesso = db_getCadastrador_PD::getInstanceOf($dbms,$p_menu, $p_usuario);
  return $l_acesso;
} 
?>
