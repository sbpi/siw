<?php
include_once($w_dir_volta.'classes/sp/db_getMenuList.php');
include_once($w_dir_volta.'classes/sp/db_getMenuRelac.php');
// =========================================================================
// Montagem da seleção de opções do menu que são vinculadas a serviço
// -------------------------------------------------------------------------
function selecaoServico($label,$accesskey,$hint,&$chave,$chaveAux,$modulo,$campo,$restricao,$atributo,$acordo,$acao,$viagem) {
  extract($GLOBALS);
  if(Nvl($restricao,'')=='MENURELAC') {
    $sql = new db_getMenuRelac; $RS = $sql->getInstanceOf($dbms, $chaveAux, $acordo, $acao, $viagem, 'SERVICO');
    // Verifica se deve ser indicada opção para vinculação a plano estratégico
    $l_mod_pe='N';
    if (f($RS_Menu,'sg_modulo')!='PE') {    
      $sql = new db_getPlanoEstrategico; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null,'REGISTROS');
      foreach ($RS1 as $row1) {
        $sql = new db_getPlanoEstrategico; $RS2 = $sql->getInstanceOf($dbms,$w_cliente,f($row1,'chave'),null,null,null,null,null,'MENU');
        foreach($RS2 as $row2){
          if(f($row2,'sq_menu')==$chaveAux && nvl(f($row2,'sq_plano'),'')!='') {
            $l_mod_pe='S';
          }
        }
      }
    }
  } elseif (Nvl($restricao,'')=='LISTA') {
    $sql = new db_getMenuList; $RS = $sql->getInstanceOf($dbms, $w_cliente, $restricao, $chaveAux, $modulo);
  } elseif (Nvl($restricao,'')=='NUMERADOR') {
    $sql = new db_getMenuList; $RS = $sql->getInstanceOf($dbms, $w_cliente, $restricao, $chaveAux, $modulo);
  } else {
    if (Nvl($chaveAux,'')>'') { $sql = new db_getMenuList; $RS = $sql->getInstanceOf($dbms, $w_cliente, 'XVINC', $chaveAux, $modulo); }
    else                      { $sql = new db_getMenuList; $RS = $sql->getInstanceOf($dbms, $w_cliente, 'X', $chaveAux, $modulo); }
  }
  $l_opcoes = count($RS);
  ShowHTML('          <td'.((isset($hint)) ? ' title="'.$hint.'"' : '').'>'.((isset($label)) ? '<b>'.$label.'</b><br>' : '').'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  if (Nvl($restricao,'')!='LISTA') {
    if (f($RS_Menu,'solicita_cc')=='S') {
      $w_selected = '';
      $l_opcoes++;
      if (nvl($chave,'')=='CLASSIF') { $w_selected = ' SELECTED '; $chave = 'CLASSIF'; }
      ShowHTML('          <option value="CLASSIF"'.$w_selected.'/>Classificação');
    }
    if ($l_mod_pe=='S') {
      $w_selected = '';
      $l_opcoes++;
      if (nvl($chave,'')=='PLANOEST') { $w_selected = ' SELECTED '; $chave = 'PLANOEST'; }
      ShowHTML('          <option value="PLANOEST"'.$w_selected.'/>Plano Estratégico');
    }
  }
  foreach($RS as $row) {
    if (Nvl($restricao,'')!='LISTA' || 
        (Nvl($restricao,'')=='LISTA' && nvl($P2,f($row,'sq_menu'))==f($row,'sq_menu')) // Se LISTA e P2 informado => somente SQ_MENU = P2 pode ser exibido
       ) {
      $w_selected = '';
      if (nvl(f($row,'sq_menu'),0)==nvl($chave,0) || $l_opcoes==1 || (Nvl($restricao,'')=='LISTA' && nvl($P2,f($row,'sq_menu'))==f($row,'sq_menu'))) { 
        $w_selected = ' SELECTED '; $chave = f($row,'sq_menu'); 
      }
      ShowHTML('          <option value="'.f($row,'sq_menu').'"'.$w_selected.'/>'.f($row,'nome'));
    }
  }
  ShowHTML('          </select>');
}
?>
