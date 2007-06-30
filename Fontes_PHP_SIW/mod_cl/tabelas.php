<?
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getLCCriterio.php');
include_once($w_dir_volta.'classes/sp/db_getLCSituacao.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putLCCriterio.php');
include_once($w_dir_volta.'classes/sp/dml_putLCSituacao.php');

// =========================================================================
//  /tabelas.php
// ------------------------------------------------------------------------
// Nome     : Billy Jones Leal dos Santos
// Descricao: Gerenciar tabelas b�sicas do m�dulo  
// Mail     : billy@sbpi.com.br
// Criacao  : 23/01/2005 11:00
// Versao   : 1.0.0.0
// Local    : Bras�lia - DF
// -------------------------------------------------------------------------
// 
// Par�metros recebidos:
//    R (refer�ncia) = usado na rotina de grava��o, com conte�do igual ao par�metro T
//    O (opera��o)   = I   : Inclus�o
//                   = A   : Altera��o
//                   = C   : Cancelamento
//                   = E   : Exclus�o
//                   = L   : Listagem
//                   = T   : Vincula��o
//                   = P   : Pesquisa
//                   = D   : Detalhes
//                   = N   : Nova solicita��o de envio

// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declara��o de vari�veis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega vari�veis locais com os dados dos par�metros recebidos
$par            = strtoupper($_REQUEST['par']);
$P1             = $_REQUEST['P1'];
$P2             = $_REQUEST['P2'];
$P3             = nvl($_REQUEST['P3'],1);
$P4             = nvl($_REQUEST['P4'],$conPageSize);
$TP             = $_REQUEST['TP'];
$SG             = strtoupper($_REQUEST['SG']);
$R              = $_REQUEST['R'];
$O              = strtoupper($_REQUEST['O']);
$w_assinatura   = strtoupper($_REQUEST['w_assinatura']);
$w_pagina       = 'tabelas.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_co/';
$w_troca        = $_REQUEST['w_troca'];
$w_copia        = $_REQUEST['w_copia'];
$p_ordena       = strtolower(trim($_REQUEST['p_ordena']));

if ($O=='') $O = 'L';

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclus�o';    break;
  case 'A': $w_TP=$TP.' - Altera��o';   break;
  case 'E': $w_TP=$TP.' - Exclus�o';    break;
  case 'P': $w_TP=$TP.' - Filtragem';   break;
  case 'C': $w_TP=$TP.' - C�pia';       break;
  case 'V': $w_TP=$TP.' - Envio';       break;
  case 'H': $w_TP=$TP.' - Heran�a';     break;
  case 'T': $w_TP=$TP.' - Vincula��o';  break;  
  case 'T': $w_TP=$TP.' - Gerar ano';  break;
  default : $w_TP=$TP.' - Listagem'; 
}
// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);

// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$RS = db_getLinkSubMenu::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu='Existe';
} else {
  $w_submenu='';
} 

// Recupera a configura��o do servi�o
if ($P2>0)   $RS_Menu = db_getMenuData::getInstanceOf($dbms,$P2);
else         $RS_Menu = db_getMenuData::getInstanceOf($dbms,$w_menu);

// Se for sub-menu, pega a configura��o do pai
if ($RS_Menu['ultimo_nivel']=='S') {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 
Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Rotina de Crit�rios de Criterio
// -------------------------------------------------------------------------
function Criterio() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave           = $_REQUEST['w_chave'];
  $w_troca           = $_REQUEST['w_troca'];
  //Se for recarga da p�gina
  if ($w_troca > '' && $O!='E') {   
    $w_nome         = $_REQUEST['w_nome'];
    $w_descricao    = $_REQUEST['w_descricao'];
    $w_ativo        = $_REQUEST['w_ativo'];
    $w_padrao       = $_REQUEST['w_padrao'];
    $w_item         = $_REQUEST['w_item'];
  } elseif ($O=='L') {     
    // Recupera todos os registros para a listagem
    $RS = db_getLCCriterio::getInstanceOf($dbms, null, $w_cliente, null, null, null, null, null, null);
    $RS = SortArray($RS,'nome','asc'); 
  } elseif (strpos('AEV',$O)!==false) {
    //Recupera os dados do endere�o informado
    $RS = db_getLCCriterio::getInstanceOf($dbms, $w_chave, $w_cliente, null, null, null, null, null, null);
    foreach ($RS as $row) {
      $w_nome         = f($row,'nome');
      $w_descricao    = f($row,'descricao');
      $w_ativo        = f($row,'ativo');
      $w_padrao       = f($row,'padrao');
      $w_item         = f($row,'item');
    }
  }
  Cabecalho();
  ShowHTML( '<HEAD>' );
  If  (!(strpos('IAEP',$O)===false)) {
    ScriptOpen( 'JavaScript');
    ValidateOpen( 'Validacao');
     if (!(strpos('IA',$O)===false)) {    
       Validate('w_nome','Nome','1','1','2','60','1','1');
       Validate('w_descricao','Descri��o', '1', '', '5', '1000', '1', '1');
       Validate('w_assinatura','Assinatura Eletr�nica', '1', '1', '6', '30', '1', '1');
     } elseif ($O=='E') {
       Validate('w_assinatura', 'Assinatura Eletr�nica', '1', '1', '6', '30', '1', '1');
       ShowHTML('  if (confirm(\'Confirma a exclus�o deste registro?\')) ');
       ShowHTML('     { return (true); }; ');
       ShowHTML('     { return (false); }; ');
     }
     ShowHTML('  theForm.Botao[0].disabled=true;');
     ShowHTML('  theForm.Botao[1].disabled=true;');
     ValidateClose();
     ScriptClose();
  }
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  If ($w_troca> '') {
     BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('IA',$O)===false)) {
     BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  } elseif ($O=='E') {
     BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
     BodyOpen('onLoad=\'document.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  If ($O=='L') {
    //Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td><font size="1"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><font size="1"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>Nome</font></td>');
    ShowHTML('          <td><font size="1"><b>Descri��o</font></td>');
    ShowHTML('          <td><font size="1"><b>Vencedor por item</font></td>');
    ShowHTML('          <td><font size="1"><b>Padr�o</font></td>');
    ShowHTML('          <td><font size="1"><b>Ativo</font></td>');
    ShowHTML('          <td><font size="1"><b>Opera��es</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
    // Se n�o foram selecionados registros, exibe mensagem
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><font size="1"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td><font size="1">'.f($row,'nome').'</td>');
        ShowHTML('        <td><font size="1">'.nvl(f($row,'descricao'),'---').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_item').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_padrao').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td align="top" nowrap><font size="1">');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');       
      }
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  //Aqui come�a a manipula��o de registros
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form', $w_dir.$w_pagina.'Grava', 'POST', 'return(Validacao(this));', null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0 cellpadding=0>');
    ShowHTML('        <tr><td colspan=3><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="60" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
    ShowHTML('        <tr><td colspan=3><font size="1"><b><u>D</u>escri��o:</b><br><textarea '.$w_Disabled.'accesskey="D" name="w_descricao" class="sti" ROWS="3" COLS="75">'.$w_descricao.'</textarea></td>');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN( '<b>Vencedor por item?</b>', $w_item, 'w_item');
    MontaRadioSN( '<b>Ativo?</b>', $w_ativo, 'w_ativo');
    MontaRadioNS( '<b>Padr�o?</b>',$w_padrao, 'w_padrao');
    ShowHTML('           </table>');
    ShowHTML('      <tr><td align="LEFT"><font size="1"><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
       ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
       if ($O=='I') {
          ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
       } else {
          ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
       }
    }
    ShowHTML('            <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen( 'JavaScript');
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina de situa��es da licita��o
// -------------------------------------------------------------------------
function Situacao() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave           = $_REQUEST['w_chave'];
  $w_troca           = $_REQUEST['w_troca'];
  //Se for recarga da p�gina
  if ($w_troca > '') {   
    $w_nome         = $_REQUEST['w_nome'];
    $w_descricao    = $_REQUEST['w_descricao'];
    $w_ativo        = $_REQUEST['w_ativo'];
    $w_padrao       = $_REQUEST['w_padrao'];
    $w_publicar     = $_REQUEST['w_publicar'];
  } elseif ($O=='L') {     
    // Recupera todos os registros para a listagem
    $RS = db_getLCSituacao::getInstanceOf($dbms, null, $w_cliente, null, null, null, null, null, null);
    $RS = SortArray($RS,'nome','asc'); 
  } elseif (!(strpos('AEV',$O)===false)) {
    //Recupera os dados do endere�o informado
    $RS = db_getLCSituacao::getInstanceOf($dbms, $w_chave, $w_cliente, null, null, null, null, null, null);
    foreach ($RS as $row) {
      $w_nome                 = f($row,'nome');
      $w_descricao            = f($row,'descricao');
      $w_ativo                = f($row,'ativo');
      $w_padrao               = f($row,'padrao');
      $w_publicar             = f($row,'publicar');
      
    }
  }
  Cabecalho();
  ShowHTML( '<HEAD>' );
  If  (!(strpos('IAEP',$O)===false)) {
    ScriptOpen( 'JavaScript');
    ValidateOpen( 'Validacao');
     if (!(strpos('IA',$O)===false)) {    
       Validate('w_nome','Nome','1','1','2','60','1','1');
       Validate('w_descricao','Descri��o', '1', '', '5', '1000', '1', '1');
       Validate('w_assinatura','Assinatura Eletr�nica', '1', '1', '6', '30', '1', '1');
     } elseif ($O=='E') {
       Validate('w_assinatura', 'Assinatura Eletr�nica', '1', '1', '6', '30', '1', '1');
       ShowHTML('  if (confirm(\'Confirma a exclus�o deste registro?\')) ');
       ShowHTML('     { return (true); }; ');
       ShowHTML('     { return (false); }; ');
     }
     ShowHTML('  theForm.Botao[0].disabled=true;');
     ShowHTML('  theForm.Botao[1].disabled=true;');
     ValidateClose();
     ScriptClose();
  }
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  If ($w_troca> '') {
     BodyOpen('onLoad="document.Form.'.$w_troca.'.focus()";');
  } elseif (!(strpos('IA',$O)===false)) {
     BodyOpen('onLoad="document.Form.w_nome.focus()";');
  } elseif ($O=='E') {
     BodyOpen('onLoad="document.Form.w_assinatura.focus()";');
  } else {
     BodyOpen('onLoad="document.focus()";');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  If ($O=='L') {
    //Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td><font size="1"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><font size="1"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>Nome</font></td>');
    ShowHTML('          <td><font size="1"><b>Descri��o</font></td>');
    ShowHTML('          <td><font size="1"><b>Publicar no portal</font></td>');
    ShowHTML('          <td><font size="1"><b>Padr�o</font></td>');
    ShowHTML('          <td><font size="1"><b>Ativo</font></td>');
    ShowHTML('          <td><font size="1"><b>Opera��es</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
    // Se n�o foram selecionados registros, exibe mensagem
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><font size="1"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td><font size="1">'.f($row,'nome').'</td>');
        ShowHTML('        <td><font size="1">'.nvl(f($row,'descricao'),'---').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_publicar').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_padrao').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td align="top" nowrap><font size="1">');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');       
      }
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  //Aqui come�a a manipula��o de registros
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form', $w_dir.$w_pagina.'Grava', 'POST', 'return(Validacao(this));', null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0 cellpadding=0>');
    ShowHTML('        <tr><td colspan=3><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="60" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
    ShowHTML('        <tr><td colspan=3><font size="1"><b><u>D</u>escri��o:</b><br><textarea '.$w_Disabled.'accesskey="D" name="w_descricao" class="sti" ROWS="3" COLS="75">'.$w_descricao.'</textarea></td>');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN( '<b>Publica certames desta situa��o no portal?</b>', $w_publicar, 'w_publicar');
    MontaRadioSN( '<b>Ativo?</b>', $w_ativo, 'w_ativo');
    MontaRadioNS( '<b>Padr�o?</b>',$w_padrao, 'w_padrao');
    ShowHTML('           </table>');
    ShowHTML('      <tr><td align="LEFT"><font size="1"><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
       ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
       if ($O=='I') {
          ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
       } else {
          ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
       }
    }
    ShowHTML('            <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen( 'JavaScript');
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=this.focus();');
  switch ($SG) {
    case 'COCRITJULG':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
         if ($O=='I' || $O=='A') {
          // Testa a exist�ncia do nome
          $RS = db_getLCCriterio::getInstanceOf($dbms,$_REQUEST['w_chave'], $w_cliente, Nvl($_REQUEST['w_nome'],''), null, null, null, null, 'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'J� existe crit�rio de julgamento com este nome!\');');
            ScriptClose(); 
            retornaFormulario('w_nome');
            break;
          } 
        }  
        dml_putLCCriterio::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$w_cliente,
           $_REQUEST['w_nome'],$_REQUEST['w_descricao'],$_REQUEST['w_ativo'],
           $_REQUEST['w_padrao'],$_REQUEST['w_item']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
        ScriptClose();        
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'COSITCERT':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
         if ($O=='I' || $O=='A') {
          // Testa a exist�ncia do nome
          $RS = db_getLCSituacao::getInstanceOf($dbms,$_REQUEST['w_chave'], $w_cliente, Nvl($_REQUEST['w_nome'],''), null, null, null, null, 'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'J� existe crit�rio de julgamento com este nome!\');');
            ScriptClose(); 
            retornaFormulario('w_nome');
            break;
          } 
        }  
        dml_putLCSituacao::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$w_cliente,
           $_REQUEST['w_nome'],$_REQUEST['w_descricao'],$_REQUEST['w_ativo'],
           $_REQUEST['w_padrao'],$_REQUEST['w_publicar']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
        ScriptClose();        
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    default:
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Bloco de dados n�o encontrado: '.$SG.'\');');
      ShowHTML('  history.back(1);');
      ScriptClose();
      break;
    } 
} 
// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
  case 'CRITERIO':      Criterio();         break;
  case 'SITUACAO':      Situacao();         break;
  case 'GRAVA':         Grava();            break;
  default:
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=this.focus();');
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    Estrutura_Texto_Abre();
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Estrutura_Texto_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Rodape();
  } 
} 
?>
