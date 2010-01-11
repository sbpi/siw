<?php
session_start();
$w_dir_volta     = '../';
include_once('../constants.inc');
include_once('../jscript.php');
include_once('../funcoes.php');
include_once('../classes/sp/db_getRamalUsuarioAtivo.php');

// =========================================================================
// Rotina de visualização de Lista Telefônica
// -------------------------------------------------------------------------
function VisualListaTel($p_cliente){
  extract($GLOBALS);
  global $w_Disabled;
  global $w_cor;
  
  // Lista Telefônica
  $RS = db_getRamalUsuarioAtivo::getInstanceOf($dbms,$p_cliente);
  if ($p_ordena==''){ 
    $RS = SortArray($RS,'nm_usuario_completo','asc');
  } else {
    $lista = explode(',',str_replace(' ',',',lower($_REQUEST['p_ordena'])));
    $RS = SortArray($RS,$lista[0],$lista[1],'nm_usuario_completo','asc');
  }
  ShowHTML(' <tr><td align="center" colspan="2">');
  ShowHTML('    <table width="100%" bgcolor="'.$conTableBgColor.'" border="'.$conTableBorder.'" cellspacing="'.$conTableCellSpacing.'" cellspacing="'.$conTableCellPadding.'" BordercolorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('         <tr bgcolor="'.$conTrBgColor.'" align="center">');
  if ($P2>0) {
    ShowHTML('           <td><font size="1" ><b>Usuário</font></td>');
    ShowHTML('           <td><font size="1" ><b>Ramal</font></td>');
  } else {
    ShowHTML('           <td><font size="1" ><b>'.LinkOrdena('Usuário','nm_usuario_completo').'</font></td>');
    ShowHTML('           <td><font size="1" ><b>'.LinkOrdena('Ramal','codigo').'</font></td>');
  } 
  ShowHTML('         </tr>');
  if (count($RS)<0){
  // Se não foram selecionados registros, exibe mensagem
  ShowHTML('       <tr bgcolor="'.$conTrBgColor.'" ><td colspan=2 align="center" ><font size="1" ><b>Não foram encontrados registros.</b></td></tr>');
  } else {
    // Lista os registros selecionados para listagem
    $w_cor=$conTrBgColor; 
    foreach($RS as $row) {
      if ($w_cor==$conTrBgColor || $w_cor=='') $w_cor=$conTrAlternateBgColor; else $w_cor=$conTrBgColor;
      ShowHTML('       <tr bgcolor="'.$w_cor.'" valign="top">');
      ShowHTML('         <td><font size="1">'.f($row,'nm_usuario_completo').'</td>');
      ShowHTML('         <td align="center"><font size="1">'.f($row,'codigo').'</td>');
      ShowHTML('       </tr>');
    }           
  } 
  ShowHTML('       </center>');
  ShowHTML('     </table>');
  ShowHTML('   </td>');
  ShowHTML(' </tr>');
} 
?>