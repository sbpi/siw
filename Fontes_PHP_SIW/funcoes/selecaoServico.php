<?php
include_once($w_dir_volta.'classes/sp/db_getMenuList.php');
include_once($w_dir_volta.'classes/sp/db_getMenuRelac.php');
// =========================================================================
// Montagem da seleção de opções do menu que são vinculadas a serviço
// -------------------------------------------------------------------------
function selecaoServico($label,$accesskey,$hint,$chave,$chaveAux,$modulo,$campo,$restricao,$atributo,$acordo,$acao,$viagem) {
  extract($GLOBALS);
  if(Nvl($restricao,'')=='MENURELAC') {
    $RS = db_getMenuRelac::getInstanceOf($dbms, $chaveAux, $acordo, $acao, $viagem, 'SERVICO');

    // Verifica se o cliente tem o módulo de planejamento estratégico
    $RS1 = db_getSiwCliModLis::getInstanceOf($dbms,$w_cliente,null,'PE');
    if (count($RS1)>0) $l_mod_pe='S'; else $l_mod_pe='N';

  } elseif(Nvl($restricao,'')=='NUMERADOR') {
    $RS = $RS = db_getMenuList::getInstanceOf($dbms, $w_cliente, $restricao, $chaveAux, $modulo);
  } else {
    if (Nvl($chaveAux,'')>'') $RS = db_getMenuList::getInstanceOf($dbms, $w_cliente, 'XVINC', $chaveAux, $modulo);
    else                      $RS = db_getMenuList::getInstanceOf($dbms, $w_cliente, 'X', $chaveAux, $modulo);
  }
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  if (f($RS_Menu,'solicita_cc')=='S') {
    ShowHTML('          <option value="CLASSIF" '.((nvl($chave,'')=='CLASSIF') ? 'SELECTED' : '').'>Classificação');
  }
  if ($l_mod_pe=='S') {
    ShowHTML('          <option value="PLANOEST" '.((nvl($chave,'')=='PLANOEST') ? 'SELECTED' : '').'>Plano Estratégico');
  }
  foreach($RS as $row) {
    if (nvl(f($row,'sq_menu'),0)==nvl($chave,0)) {
       ShowHTML('          <option value="'.f($row,'sq_menu').'" SELECTED>'.f($row,'nome'));
    } else {
       ShowHTML('          <option value="'.f($row,'sq_menu').'">'.f($row,'nome'));
    }
  }
  ShowHTML('          </select>');
}
?>
