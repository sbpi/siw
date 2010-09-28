<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta     = '../';
include_once('../constants.inc');
include_once('../jscript.php');
include_once('../funcoes.php');
include_once('../classes/db/abreSessao.php');
include_once('../classes/sp/db_getMenuCode.php');
include_once('../classes/sp/db_getLinkSubMenu.php');
include_once('../classes/sp/db_getCustomerData.php');
include_once('../classes/sp/db_getMenuData.php');
include_once('../classes/sp/db_verificaAssinatura.php');
include_once('../classes/sp/db_getCall.php');
include_once('../classes/sp/db_getLinkData.php');
include_once('../classes/sp/db_getPersonData.php');
include_once('visuallistatel.php');
include_once('visualresumoligacaoparticular.php');

// =========================================================================
//  /Tabelas.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerenciar tabelas básicas do módulo  
// Mail     : alex@sbpi.com.br
// Criacao  : 01/06/2006 10:40
// Versao   : 1.0.0.0
// Local    : Brasília - DF
// -------------------------------------------------------------------------
// 
// Parâmetros recebidos:
//    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
//    O (operação)   = I   : Inclusão
//                   = A   : Alteração
//                   = C   : Cancelamento
//                   = E   : Exclusão
//                   = L   : Listagem
//                   = P   : Pesquisa
//                   = D   : Detalhes
//                   = N   : Nova solicitação de envio

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') EncerraSessao(); 

$w_troca         = $_REQUEST['w_troca'];
$w_copia         = $_REQUEST['w_copia'];
$O               = $_REQUEST['R'];

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

// Carrega variáveis locais com os dados dos parâmetros recebidos

$par       = upper($_REQUEST['par']);
$P1        = nvl($_REQUEST['P1'],0);
$P2        = nvl($_REQUEST['P2'],0);
$P3        = nvl($_REQUEST['P3'],1);
$P4        = nvl($_REQUEST['P4'],$conPageSize);
$TP        = $_REQUEST['TP'];
$SG        = upper($_REQUEST['SG']);
$R         = $_REQUEST['R'];
$O         = upper($_REQUEST['O']);

$w_assinatura    = upper($_REQUEST['w_assinatura']);
$w_pagina        = 'consulta.php?par=';
$w_dir           = 'mod_tt/';
$w_Disabled      = 'ENABLED';

$p_ordena  = lower($_REQUEST['p_ordena']);

if ($O=='') {
  if ($par=='PARTICULAR') $O='R';
  else                    $O='L';
} 
switch ($O){
case 'I': $w_TP=$TP.' - Inclusão';    break;
case 'A': $w_TP=$TP.' - Alteração';   break;
case 'E': $w_TP=$TP.' - Exclusão';    break;
case 'P': $w_TP=$TP.' - Filtragem';   break;
case 'C': $w_TP=$TP.' - Cópia';       break;
case 'F': $w_TP=$TP.' - Finalizar';   break;
case 'V': $w_TP=$TP.' - Envio';       break;
case 'H': $w_TP=$TP.' - Herança';     break;
case 'R': $w_TP=$TP.' - Resumo';      break;
default:  $w_TP=$TP.' - Listagem';    break;
} 

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_sq_usuario_central=RetornaUsuarioCentral();

if  ($SG!='TTUSUCTRL' && $SG!='TTTRONCO') {
  $w_menu=RetornaMenu($w_cliente,$SG);
} else {  
  $w_menu=RetornaMenu($w_cliente,$_REQUEST['w_SG']);
} 

// Verifica se o documento tem sub-menu. Se tiver, agrega no href uma chamada para montagem do mesmo.
if ($SG!='TTUSUCTRL' && $SG!='TTTRONCO') {
  $sql = new db_getLinkSubMenu; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
} else {
  $sql = new db_getLinkSubMenu; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$_REQUEST['w_SG']);
} 
if (count($RS)>0) {
  $w_submenu='Existe';
} else {
  $w_submenu='';
} 

// Recupera a configuração do serviço
$RS_menu = new db_getMenuData; $RS_menu = $RS_menu->getInstanceOf($dbms,$w_menu);
// Se for sub-menu, pega a configuração do pai
if ($RS_menu['ultimo_nivel']=='S') {
  $RS_menu = new db_getMenuData; $RS_menu = $RS_menu->getInstanceOf($dbms,$RS_menu,'sq_menu_pai');
} 

Main();

FechaSessao($dbms);

exit;


// =========================================================================
// Rotina de informação de ligações
// -------------------------------------------------------------------------
function LigacaoParticular(){
  extract($GLOBALS);
  global $w_Disabled;

  $w_sq_ligacao          = upper($_REQUEST['w_sq_ligacao']);
  $w_nome_usuario        = upper($_REQUEST['w_nome_usuario']);
  $p_sq_cc               = upper($_REQUEST['p_sq_cc']);
  $p_outra_parte_contato = upper($_REQUEST['p_outra_parte_contato']);
  $p_ativo               = upper($_REQUEST['p_ativo']);
  $p_inicio              = upper($_REQUEST['p_inicio']);
  $p_fim                 = upper($_REQUEST['p_fim']);
  $p_numero              = upper($_REQUEST['p_numero']);
  if ($w_troca>'') {
    $w_sq_ligacao          = upper($_REQUEST['w_sq_ligacao']);
    $p_sq_cc               = upper($_REQUEST['p_sq_cc']);
    $w_sq_acordo           = upper($_REQUEST['w_sq_acordo']); 
    $w_assunto             = upper($_REQUEST['w_assunto']);
    $p_ativo               = upper($_REQUEST['p_ativo']);
    $w_imagem              = upper($_REQUEST['w_imagem']);
    $w_fax                 = upper($_REQUEST['w_fax']); 
    $w_trabalho            = upper($_REQUEST['w_trabalho']);
    $p_outra_parte_contato = upper($_REQUEST['p_outra_parte_contato']);
    if ($p_ordena==''){ 
      if ($P1==3) $RS = SortArray(f($row,'phpdt_ordem','desc')); else $RS = SortArray(f($row,'phpdt_ordem','asc')); 
    } else {
      $lista = explode(',',str_replace(' ',',',lower($_REQUEST['p_ordena'])));
      if ($P1==3) $RS = SortArray(f($RS,$lista[0],$lista[1],'phpdt_ordem','desc')); else $RS = SortArray(f($RS,$lista[0],$lista[1],'phpdt_ordem','asc')); 
    }
  }
  
  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.$w_dir_volta.MontaURL('MESA').'">');
  if (!(strpos('R',$O)===false)){
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if ($O=='R'){
      ShowHTML('  if (theForm.p_fim.value.length > 0 && theForm.p_inicio.value.length == 0) {');
      ShowHTML('     alert(\'Não é permitido informar apenas a data final!\');');
      ShowHTML('     theForm.p_fim.focus();');
      ShowHTML('     return false;');
      ShowHTML('   }');
      Validate('p_inicio','Data inicial','DATA','1','10','10','','0123456789/');
      Validate('p_fim','Data final','DATA','1','10','10','','0123456789/');
      CompData('p_inicio','Data inicial','<=','p_fim','Data final');
      ShowHTML('  var w_data, w_data1, w_data2;');
      ShowHTML('  w_data = theForm.p_inicio.value;');
      ShowHTML('  w_data = w_data.substr(3,2) + \'/\' + w_data.substr(0,2) + \'/\' + w_data.substr(6,4);');
      ShowHTML('  w_data1  = new Date(Date.parse(w_data));');
      ShowHTML('  w_data = theForm.p_fim.value;');
      ShowHTML('  w_data = w_data.substr(3,2) + \'/\' + w_data.substr(0,2) + \'/\' + w_data.substr(6,4);');
      ShowHTML('  w_data2= new Date(Date.parse(w_data));');
      ShowHTML('  var MinMilli = 1000 * 60;');
      ShowHTML('  var HrMilli = MinMilli * 60;');
      ShowHTML('  var DyMilli = HrMilli * 24;');
      ShowHTML('  var Days = Math.round(Math.abs((w_data2 - w_data1) / DyMilli));');
    } else {
      Validate('p_inicio','Data inicial','DATA','','10','10','','0123456789/');
      Validate('p_fim','Data final','DATA','','10','10','','0123456789/');
      CompData('p_inicio','Data inicial','<=','p_fim','Data final');
    } 
    if ($P1==3){
      // Se for arquivo
      ShowHTML('  if (theForm.p_fim.value == \'\' && theForm.p_inicio.value == \'\') {');
      ShowHTML('     alert("É necessário informar um critério de filtragem!");');
      ShowHTML('     return false;');
      ShowHTML('   }');
    } 
    Validate('P4','Linhas por página','1','1','1','4','','0123456789');
    if ($O=='R'){
      ShowHTML('  theForm.Botao.disabled=true;');
    } else {
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } 
    ValidateClose();
    ScriptClose();
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($w_troca>''){
      BodyOpen('onload="document.Form.'.$w_troca.'.focus()";');
    } elseif (!(strpos('R',$O)===false)){
      BodyOpen('onload="document.Form.p_inicio.focus()";');
    } else {
      BodyOpen('onLoad="this.focus()";');
    }  
    ShowHTML('<B><FONT color="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center>');
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
    if (!(strpos('R',$O)===false)){
      ShowHTML('<FORM action="'.$w_dir.$w_pagina.$par.'" method="POST" name="Form" onSubmit="return(Validacao(this));">');
      ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
      ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
      ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
      ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
      ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
      ShowHTML('<INPUT type="hidden" name="SG" value="'.$SG.'">');
      ShowHTML('<INPUT type="hidden" name="R" value="'.$R.'">');
      ShowHTML('<INPUT type="hidden" name="O" value="'.$O.'">');
      
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
      ShowHTML('    <table width="90%" border="0">');
      ShowHTML('      <tr align="left"><td><table width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
      ShowHTML('      </tr></table></td></tr>');
      ShowHTML('      <tr align="left"><td><table cellpadding=0 cellspacing=0><tr valign="center">');
      ShowHTML('          <td><font size="1"><b>Período</b>(formato DD/MM/AAAA):&nbsp;&nbsp;</td>');
      if ($p_inicio==''){                                                                                                                                                                        
        ShowHTML('          <td><font size="1"><b><U>D</U>e: <INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="p_inicio" size="10" maxlength="10" value="01/'.date('m/Y',time()).'" onKeyDown="FormataData(this,event)" onKeyUp="SaltaCampo(this.form.name,this,10,event);">&nbsp;</td>');
        ShowHTML('          <td><font size="1"><b>A<U>t</U>é: <INPUT ACCESSKEY="T" '.$w_Disabled.' class="STI" type="text" name="p_fim" size="10" maxlength="10" value="'.FormataDataEdicao(time()).'" onKeyDown="FormataData(this,event)" onKeyUp="SaltaCampo(this.form.name,this,10,event);">&nbsp;</td>');
      } else {
        ShowHTML('          <td><font size="1"><b><U>D</U>e: <INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="p_inicio" size="10" maxlength="10" value="'.$p_inicio.'" onKeyDown="FormataData(this,event)" onKeyUp="SaltaCampo(this.form.name,this,10,event);">&nbsp;</td>');
        ShowHTML('          <td><font size="1"><b>A<U>t</U>é: <INPUT ACCESSKEY="T" '.$w_Disabled.' class="STI" type="text" name="p_fim" size="10" maxlength="10" value="'.$p_fim.'" onKeyDown="FormataData(this,event)" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      } 
      ShowHTML('      </table>');
      ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
      ShowHTML('      <tr><td align="center" colspan="3">');
      ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir">');
      ShowHTML('          </td>');
      ShowHTML('      </tr>');
      ShowHTML('    </table>');
      ShowHTML('    </TD>');
      ShowHTML('</tr>');
      ShowHTML('</FORM>');
      if ($p_inicio>'') {
        ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><hr>');
        if ($P1!=3) {
          // Se não for arquivo              
          $sql = new db_getCall; $RS = $sql->getinstanceOf($dbms,$w_cliente,null,null,$P1,'PESSOAS', null, null, null, $p_inicio, $p_fim, $p_ativo);
          $RS = SortArray($RS,'dura_tot','desc');
          ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><font size=2><b>Resumo comparativo por ligações particulares</b>&nbsp;&nbsp;&nbsp;');
          ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
          ShowHTML('    <TABLE WIDTH="90%" align="center" BORDER=1 CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BordercolorDark="'.$conTableBordercolorDark.'" BordercolorLight="'.$conTableBordercolorLight.'">');
          ShowHTML('        <tr align="center">');
          ShowHTML('          <td rowspan=2><font size="1"><b>Pessoa     </font></td>');
          ShowHTML('          <td colspan=4><font size="1"><b>Quantidade </font></td>');
          ShowHTML('          <td colspan=4><font size="1"><b>Duração    </font></td>');
          ShowHTML('        <tr align="center">');
          ShowHTML('          <td><font size="1"><b>ORI</font></td>');
          ShowHTML('          <td><font size="1"><b>REC</font></td>');
          ShowHTML('          <td><font size="1"><b>NAT</font></td>');
          ShowHTML('          <td><font size="1"><b>TOT</font></td>');
          ShowHTML('          <td><font size="1"><b>ORI</font></td>');
          ShowHTML('          <td><font size="1"><b>REC</font></td>');
          ShowHTML('          <td><font size="1"><b>NAT</font></td>');
          ShowHTML('          <td><font size="1"><b>TOT</font></td>');
          if (count($RS) < 0){
            ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
          } else {
            $w_cor=$conTrAlternateBgColor;                       
            foreach($RS as $row) {
              if (f($row,'trabalho')=='Particular') {
                $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
                ShowHTML('      <tr bgcolor="'.$w_cor.'">');
                ShowHTML('      <td><font size="1">'.f($row,'nome_resumido').'</td>');
                ShowHTML('      <td align="right"><font size="1">'.f($row,'ori_qtd').'&nbsp;</td>');
                ShowHTML('      <td align="right"><font size="1">'.f($row,'rec_qtd').'&nbsp;</td>');
                ShowHTML('      <td align="right"><font size="1">'.f($row,'nat_qtd').'&nbsp;</td>');
                ShowHTML('      <td align="right"><font size="1">'.f($row,'qtd_tot').'&nbsp;</td>');
                ShowHTML('      <td align="right"><font size="1">'.FormataTempo(f($row,'ori_dura')).'&nbsp;</td>');
                ShowHTML('      <td align="right"><font size="1">'.FormataTempo(f($row,'rec_dura')).'&nbsp;</td>');
                ShowHTML('      <td align="right"><font size="1">'.FormataTempo(f($row,'nat_dura')).'&nbsp;</td>');
                ShowHTML('      <td align="top" nowrap><font size="1">');
                ShowHTML('      <a class="HL" href="'.$w_dir.$w_pagina.'RESUMPART&R='.$w_pagina.$par.'&O=L&w_sq_usuario='.f($row,'usuario').'&p_inicio='.$p_inicio.'&p_fim='.$p_fim.'&p_ativo=S&w_nome_usuario='.f($row,'nm_completo').'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" Target="_blank" Title="Exibe resumo detalhado ">'.FormataTempo(f($row,"dura_tot")).'</a>&nbsp');
                ShowHTML('      </td>');
                ShowHTML('      </tr>');
              } 
            } 
          } 
        } 
        ShowHTML('    </TABLE>');
        ShowHTML('    </TD>');
        ShowHTML('</TR>');
        ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><font size=2><br><br></td></tr>');
      } 
    } else {
    ScriptOpen('JavaScript');
    ShowHTML('    alert(\'Opção não disponível!\');');
    ShowHTML(' history.back(1);');
    ScriptClose;
    } 
    ShowHTML('</table>');
    ShowHTML('</center>');
    Rodape();
  }    
}    
  
// =========================================================================
// Rotina de visualização
// -------------------------------------------------------------------------
function listaTelefonica(){
  extract($GLOBALS);
  global $w_Disabled;

  if ($P2==1){
    HeaderWord($_REQUEST['orientacao']); 
    CabecalhoWord($w_cliente,'Lista Telefônica',0);
  } else {
    cabecalho();
  } 
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.$w_dir_volta.MontaURL('MESA').'">');
  ShowHTML('<TITLE>Lista Telefônica</TITLE>');
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($P2==0) {
    BodyOpen('onLoad=\'this.focus();\'');
  } 
  ShowHTML('<TABLE WIDTH="100%" BORDER=0><tr>');
  if ($P2==0){
    $RS = new db_getCustomerData; $RS = $RS->getInstanceOf($dbms,$w_cliente);
    ShowHTML('  <td rowspan=2><img align="left" src="'.LinkArquivo(null,$w_cliente,'img/logo'.substr((f($RS,'logo')),strpos(f($RS,'logo'),'.'),30),null,null,null,'EMBED').'">');
    ShowHTML('  <td align="right"><B><font size=5 color="#000000">');
    ShowHTML('Lista Telefônica');
    ShowHTML('</FONT><tr><td ALIGN="RIGHT"><B><font size=2 color="#000000">'.DataHora().'</B>');
    ShowHTML('     &nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&P2=1&SG='.$SG.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
  }
  ShowHTML('</td></tr>');
  ShowHTML('</FONT></B></td></tr></TABLE>');
  if ($P2==0){
    ShowHTML('<HR>');
  }
  // Chama a função de visualização dos dados do usuário, na opção 'Listagem'
  VisualListaTel($w_cliente);
  if ($P2==0) {
    Rodape();
  } 
  $w_erro=null;
  $w_logo=null;
}
// =========================================================================
// Rotina de visualização
// -------------------------------------------------------------------------
function ResumoLigacaoParticular(){
  extract($GLOBALS);
  global $w_Disabled;

  if ($P2==1){
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Resumo de Ligações Particulares',0);
  } else {
    cabecalho();
  } 
  head();
  ShowHTML('<TITLE>Lista Telefônica</TITLE>');
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($P2==0) {
    BodyOpen('onLoad=\'this.focus();\'');
  } 
  ShowHTML('<TABLE WIDTH="100%" BORDER=0><tr>');
  if ($P2==0) {
    $RS = new db_getCustomerData; $RS = $RS->getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'') {
      $w_logo='img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
      ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" SRC="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'">');
    } 
    ShowHTML('  <td align="right"><B><font size=5 color="#000000">');
    ShowHTML('Resumo de Ligações Particulares');
    ShowHTML('</FONT><tr><td align="right"><B><font size=2 color="#000000">'.DataHora().'</B>');
    ShowHTML('     &nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&P2=1&SG='.$SG.'&w_sq_usuario='.$_REQUEST['w_sq_usuario'].MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Emite autorização para desconto em folha" SRC="images/word.gif"></a>');
  } 
  ShowHTML('</td></tr>');
  ShowHTML('</FONT></B></td></tr></TABLE>');
  if ($P2==0){
    ShowHTML('<HR>');
  } 
  // Chama a função de visualização dos dados das ligações particulares efetuadas pelo usuário, na opção 'Listagem'
  ResumLigPart ($_REQUEST['w_sq_usuario'], $_REQUEST['p_inicio'], $_REQUEST['p_fim'], $_REQUEST['p_ativo'],$_REQUEST['O']);
  if ($P2==0) {
    Rodape();
  } 
  $w_erro=null;
  $w_logo=null;
}


// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main(){
  extract($GLOBALS);

  switch ($par){
  case 'LISTATEL':    ListaTelefonica();          break;
  case 'PARTICULAR':  LigacaoParticular();        break;
  case 'RESUMPART':   ResumoLigacaoParticular();  break;
  default: 
  Cabecalho();    
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=this.focus();');
  ShowHTML('<B><FONT color="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
  Rodape();
  break;
  } 
} 
?>