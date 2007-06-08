<?
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getEspecieDocumento_PA.php');
include_once($w_dir_volta.'classes/sp/db_getUnidade_PA.php');
include_once($w_dir_volta.'classes/sp/db_getNaturezaDoc_PA.php');
include_once($w_dir_volta.'classes/sp/db_getParametro.php');
include_once($w_dir_volta.'classes/sp/db_getAssunto_PA.php');
include_once($w_dir_volta.'classes/sp/db_getProtocolo.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoAutua.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoTipoGuarda.php');
include_once($w_dir_volta.'funcoes/selecaoAssunto.php');
include_once('visualGR.php');

// =========================================================================
//  /processo.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papad�polis
// Descricao: Cont�m rotinas para opera��o com processos
// Mail     : alex@sbpi.com.br
// Criacao  : 28/02/2007, 15:18
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
//                   = P   : Pesquisa
//                   = D   : Detalhes
//                   = N   : Nova solicita��o de envio

// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declara��o de vari�veis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega vari�veis locais com os dados dos par�metros recebidos
$par        = strtoupper($_REQUEST['par']);
$P1         = nvl($_REQUEST['P1'],0);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = strtoupper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = strtoupper($_REQUEST['O']);
$w_assinatura = strtoupper($_REQUEST['w_assinatura']);
$w_pagina     = 'processo.php?par=';
$w_Disabled   = 'ENABLED';
$w_dir        = 'mod_pa/';
$w_troca      = $_REQUEST['w_troca'];
$p_ordena     = $_REQUEST['p_ordena'];

if ($O=='') $O='P';

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclus�o';        break;
  case 'A': $w_TP=$TP.' - Altera��o';       break;
  case 'E': $w_TP=$TP.' - Exclus�o';        break;
  case 'P': $w_TP=$TP.' - Filtragem';       break;
  case 'C': $w_TP=$TP.' - C�pia';           break;
  case 'V': $w_TP=$TP.' - Envio';           break;
  case 'M': $w_TP=$TP.' - Servi�os';        break;
  case 'H': $w_TP=$TP.' - Heran�a';         break;
  case 'T': $w_TP=$TP.' - Ativar';          break;
  case 'D': $w_TP=$TP.' - Desativar';       break;
  default:  $w_TP=$TP.' - Listagem';        break;
}

// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,'PADCAD');
$w_ano      = RetornaAno();

$RS_Menu = db_getMenuData::getInstanceOf($dbms,$w_menu);

Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Emite guias de tramita��o
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;

  // Recupera as vari�veis utilizadas na filtragem
  $p_protocolo    = $_REQUEST['p_protocolo'];
  $p_chave        = $_REQUEST['p_chave'];
  $p_chave_aux    = $_REQUEST['p_chave_aux'];
  $p_prefixo      = substr($p_protocolo,0,5);
  $p_numero       = substr($p_protocolo,6,6);
  $p_ano          = substr($p_protocolo,13,4);
  $p_unid_autua   = $_REQUEST['p_unid_autua'];
  $p_unid_posse   = $_REQUEST['p_unid_posse'];
  $p_nu_guia      = $_REQUEST['p_nu_guia'];
  $p_ano_guia     = $_REQUEST['p_ano_guia'];
  $p_ini          = $_REQUEST['p_ini'];
  $p_fim          = $_REQUEST['p_fim'];
  
  switch ($SG) {
    case 'PADAUTUA':    $w_nm_operacao = 'Autuar';     $w_rotina = 'Autuar';      break;
    case 'PADANEXA':    $w_nm_operacao = 'Anexar';     $w_rotina = 'Anexar';      break;
    case 'PADJUNTA':    $w_nm_operacao = 'Apensar';    $w_rotina = 'Apensar';     break;
    case 'PADTRANSF':   $w_nm_operacao = 'Arquivar';   $w_rotina = 'Arquivar';    break;
    case 'PADELIM':     $w_nm_operacao = 'Eliminar';   $w_rotina = 'Eliminar';    break;
    case 'PADEMPREST':  $w_nm_operacao = 'Emprestar';  $w_rotina = 'Emprestar';   break;
  } 
  

  if ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getProtocolo::getInstanceOf($dbms, $w_menu, $w_usuario, $SG, $p_chave, $p_chave_aux, 
        $p_prefixo, $p_numero, $p_ano, $p_unid_autua, $p_unid_posse, $p_nu_guia, $p_ano_guia, $p_ini, $p_fim, 2);
    if (Nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'sg_unidade','asc', 'ano_guia','desc','nu_guia','asc','protocolo','asc');
    } else {
      $RS = SortArray($RS,'sg_unidade','asc', 'ano_guia','desc','nu_guia','asc','protocolo','asc');
    }
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if ($O=='P') {
    ScriptOpen('JavaScript');
    FormataProtocolo();
    FormataData();
    SaltaCampo();
    CheckBranco();
    ValidateOpen('Validacao');
    Validate('p_protocolo','N�mero de protocolo','1','','20','20','','0123456789./-');
    Validate('p_ini','In�cio','DATA','','10','10','','0123456789/');
    Validate('p_fim','T�rmino','DATA','','10','10','','0123456789/');
    ShowHTML('  if ((theForm.p_ini.value != \'\' && theForm.p_fim.value == \'\') || (theForm.p_ini.value == \'\' && theForm.p_fim.value != \'\')) {');
    ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
    ShowHTML('     theForm.p_ini.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    CompData('p_ini','In�cio','<=','p_fim','T�rmino');
    ShowHTML('  theForm.Botao.disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='P') {
    BodyOpen('onLoad=\'document.Form.p_protocolo.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orienta��o:<ul>');
    ShowHTML('  <li>Selecione o documento desejado, clicando sobre a opera��o <i>'.$w_nm_operacao.'</i>.');
    ShowHTML('  </ul></b></font></td>');
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td>');
    if (MontaFiltro('GET')>'') {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
    } 
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan=2><b>'.linkOrdena('�ltimo despacho','nm_despacho').'</td>');
    ShowHTML('          <td rowspan=2><b>'.linkOrdena('Protocolo','protocolo').'</td>');
    ShowHTML('          <td rowspan=2><b>'.linkOrdena('Unidade de origem','nm_unid_origem').'</td>');
    ShowHTML('          <td colspan=4><b>Documento original</td>');
    ShowHTML('          <td rowspan=2><b>'.linkOrdena('Limite','fim').'</td>');
    ShowHTML('          <td rowspan=2><b>Opera��es</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.linkOrdena('Esp�cie','nm_especie').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('N�','numero_original').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Data','inicio').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Origem','nm_origem_doc').'</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) { 
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=9 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      $w_atual = '';
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nm_despacho').'</td>');
        ShowHTML('        <td align="center"><A class="HL" HREF="'.$w_dir.'documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" target="visualdoc" title="Exibe as informa��es deste registro.">'.f($row,'protocolo').'&nbsp;</a>');
        ShowHTML('        <td>'.f($row,'nm_unid_origem').'</td>');
        ShowHTML('        <td>'.f($row,'nm_especie').'</td>');
        ShowHTML('        <td>'.f($row,'numero_original').'</td>');
        ShowHTML('        <td align="center">'.date(d.'/'.m.'/'.y,f($row,'inicio')).'</td>');
        ShowHTML('        <td>'.f($row,'nm_origem_doc').'</td>');
        ShowHTML('        <td align="center">'.((nvl(f($row,'fim'),'')!='') ? date(d.'/'.m.'/'.y,f($row,'fim')) : '&nbsp;').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$w_rotina.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">'.$w_nm_operacao.'</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET'),ceil(count($RS)/$P4),$P3,$P4,count($RS));
    ShowHTML('</tr>');
  } elseif ($O=='P') {
    ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orienta��o:<ul>');
    ShowHTML('  <li>Informe quaisquer crit�rios de busca e clique sobre o bot�o <i>Aplicar filtro</i>.');
    ShowHTML('  <li>Para pesquisa por per�odo � obrigat�rio informar as datas de in�cio e t�rmino.');
    ShowHTML('  <li>Clicando sobre o botao <i>Aplicar filtro</i> sem informar nenhum crit�rio de busca, ser�o exibidas todas as guias que voc� tem acesso.');
    ShowHTML('  </ul></b></font></td>');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><b><u>P</u>rotocolo:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="p_protocolo" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$p_protocolo.'" onKeyDown="FormataProtocolo(this,event);"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade que det�m a posse do protocolo:','U','Selecione a unidade de posse.',$p_unid_posse,null,'p_unid_posse','MOD_PA',null);
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Per�o<u>d</u>o entre:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="p_ini" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina de visualiza��o da guia de tramita��o
// -------------------------------------------------------------------------
function EmitirGR () {
  extract($GLOBALS);
  $w_unidade   = nvl($w_unidade,$_REQUEST['w_unidade']);
  $w_nu_guia   = nvl($w_nu_guia,$_REQUEST['w_nu_guia']);
  $w_ano_guia  = nvl($w_ano_guia,$_REQUEST['w_ano_guia']);

  HeaderWord('PORTRAIT');
  ShowHTML(VisualGR($w_unidade, $w_nu_guia, $w_ano_guia));
  Rodape();
} 

// =========================================================================
// Rotina de autua��o de processos
// -------------------------------------------------------------------------
function Autuar() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];

  // Recupera os dados do documento
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
  if (count($RS)>0) $w_processo           = f($RS,'processo');

  // Verifica se o documento a ser autuado j� � um processo
  if ($w_processo=='S') {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Este documento j� foi autuado.\');');
    ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
    ScriptClose();
    exit();
  }
  
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
  // Se n�o for encaminhamento
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados da a��o, na op��o 'Listagem'
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_protocolo" value="'.f($RS,'protocolo').'">');
  ShowHTML('<tr><td bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
  ShowHTML('  Orienta��o:<ul>');
  ShowHTML('  <li>Verifique se realmente deseja autuar este documento, transformando-o em processo.');
  ShowHTML('  <li>Leia atentamente os dados que ser�o registrados para esta autua��o e clique no bot�o "Autuar" para confirmar a opera��o ou no bot�o "Abandonar" para voltar � tela anterior.');
  ShowHTML('  </ul></b></font></td>');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td colspan=2><b>DADOS DO DOCUMENTO</b></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('    <tr><td width="30%">'.f($RS,'nm_tipo').':<td><b>'.f($RS,'protocolo').'</b></td></tr>');
  if (f($RS,'interno')=='S') {
    ShowHTML('   <tr><td width="30%">Unidade:</td>');
    ShowHTML('       <td>'.ExibeUnidade('../',$w_cliente,f($RS,'nm_unid_origem'),f($RS,'sq_unidade'),$TP).'</td></tr>');
  } else {
    ShowHTML('   <tr><td>Pessoa:</td>');
    ShowHTML('       <td>'.f($RS,'nm_pessoa_origem').'</td></tr>');
    ShowHTML('   <tr><td>Interessado principal:</td>');
    ShowHTML('       <td>'.f($RS,'nm_pessoa_interes').'</td></tr>');
  }
  ShowHTML('   <tr><td>Cidade:</td>');
  ShowHTML('       <td>'.f($RS,'nm_cidade').'</td></tr>');
  ShowHTML('   <tr><td>Esp�cie documental:</td>');
  ShowHTML('       <td>'.f($RS,'nm_especie').'</td></tr>');
  ShowHTML('   <tr><td>N�mero:</td>');
  ShowHTML('       <td>'.f($RS,'numero_original').'</td></tr>');
  ShowHTML('   <tr><td>Data do documento:</td>');
  ShowHTML('       <td>'.formataDataEdicao(f($RS,'inicio')).'</td></tr>');

  ShowHTML('    <tr><td colspan=2>&nbsp;</td></tr>');
  ShowHTML('    <tr><td colspan=2><b>DADOS DA AUTUA��O</b></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('    <tr><td width="30%">Data da autua��o:<td><b>'.formataDataEdicao(time()).'</b></td></tr>');
  $RS_Unid = db_getUorgData::getInstanceOf($dbms,$_SESSION['LOTACAO']);
  ShowHTML('    <tr><td width="30%">Unidade autuadora:<td><b>'.f($RS_Unid,'nome').'</b></td></tr>');
  ShowHTML('    <tr><td width="30%">Usu�rio autuador:<td><b>'.$_SESSION['NOME'].'</b></td></tr>');

  ShowHTML('    <tr><td colspan=2>&nbsp;</td></tr>');
  ShowHTML('    <tr><td colspan=2><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center"><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Autuar">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de anexa��o de documentos e processos
// -------------------------------------------------------------------------
function Anexar() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];

  // Recupera os dados do documento
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  ScriptOpen('JavaScript');
  FormataProtocolo();
  FormataData();
  SaltaCampo();
  CheckBranco();
  ValidateOpen('Validacao');
  Validate('w_protocolo','Protocolo que receber� o anexo','1','','20','20','','0123456789./-');
  Validate('w_data','Data da anexa��o','DATA','1','10','10','','0123456789/');
  Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
  // Se n�o for encaminhamento
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_protocolo.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados da a��o, na op��o 'Listagem'
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<tr><td bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
  ShowHTML('  Orienta��o:<ul>');
  ShowHTML('  <li>ATEN��O: A anexa��o n�o pode ser desfeita. Assim, verifique se realmente deseja anexar este protocolo.');
  ShowHTML('  <li>Informe os dados solicitados e clique no bot�o "Anexar" para confirmar a opera��o ou no bot�o "Abandonar" para voltar � tela anterior.');
  ShowHTML('  </ul></b></font></td>');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td colspan=2><b>PROTOCOLO A SER ANEXADO</b></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('    <tr><td width="30%">'.f($RS,'nm_tipo').':<td><b>'.f($RS,'protocolo').'</b></td></tr>');
  if (f($RS,'interno')=='S') {
    ShowHTML('   <tr><td width="30%">Unidade:</td>');
    ShowHTML('       <td>'.ExibeUnidade('../',$w_cliente,f($RS,'nm_unid_origem'),f($RS,'sq_unidade'),$TP).'</td></tr>');
  } else {
    ShowHTML('   <tr><td>Pessoa:</td>');
    ShowHTML('       <td>'.f($RS,'nm_pessoa_origem').'</td></tr>');
    ShowHTML('   <tr><td>Interessado principal:</td>');
    ShowHTML('       <td>'.f($RS,'nm_pessoa_interes').'</td></tr>');
  }
  ShowHTML('   <tr><td>Cidade:</td>');
  ShowHTML('       <td>'.f($RS,'nm_cidade').'</td></tr>');
  ShowHTML('   <tr><td>Esp�cie documental:</td>');
  ShowHTML('       <td>'.f($RS,'nm_especie').'</td></tr>');
  ShowHTML('   <tr><td>N�mero:</td>');
  ShowHTML('       <td>'.f($RS,'numero_original').'</td></tr>');
  ShowHTML('   <tr><td>Data do documento:</td>');
  ShowHTML('       <td>'.formataDataEdicao(f($RS,'inicio')).'</td></tr>');

  ShowHTML('    <tr><td colspan=2>&nbsp;</td></tr>');
  ShowHTML('    <tr><td colspan=2><b>DADOS DA ANEXA��O</b></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('    <tr><td width="30%"><u>P</u>rotocolo que receber� o anexo:<td><input '.$w_Disabled.' accesskey="P" type="text" name="w_protocolo" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_protocolo.'" onKeyDown="FormataProtocolo(this,event);"></td></tr>');
  ShowHTML('    <tr><td width="30%"><u>D</u>ata da anexa��o:<td><input '.$w_Disabled.' accesskey="D" type="text" name="w_data" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td></tr>');

  ShowHTML('    <tr><td width="30%"><U>A</U>ssinatura Eletr�nica:<td> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center"><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Anexar">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de apensa��o de processos
// -------------------------------------------------------------------------
function Apensar() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];

  // Recupera os dados do documento
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
  if (count($RS)>0) $w_processo           = f($RS,'processo');

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  ScriptOpen('JavaScript');
  FormataProtocolo();
  FormataData();
  SaltaCampo();
  CheckBranco();
  ValidateOpen('Validacao');
  Validate('w_protocolo','Processo que receber� o apenso','1','','20','20','','0123456789./-');
  Validate('w_data','Data da apensa��o','DATA','','10','10','','0123456789/');
  Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
  // Se n�o for encaminhamento
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_protocolo.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados da a��o, na op��o 'Listagem'
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<tr><td bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
  ShowHTML('  Orienta��o:<ul>');
  ShowHTML('  <li>ATEN��O: A apensa��o pode ser desfeita. Ainda assim, verifique se realmente deseja apensar este processo.');
  ShowHTML('  <li>Informe os dados solicitados e clique no bot�o "Apensar" para confirmar a opera��o ou no bot�o "Abandonar" para voltar � tela anterior.');
  ShowHTML('  </ul></b></font></td>');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td colspan=2><b>PROTOCOLO A SER APENSADO</b></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('    <tr><td width="30%">'.f($RS,'nm_tipo').':<td><b>'.f($RS,'protocolo').'</b></td></tr>');
  if (f($RS,'interno')=='S') {
    ShowHTML('   <tr><td width="30%">Unidade:</td>');
    ShowHTML('       <td>'.ExibeUnidade('../',$w_cliente,f($RS,'nm_unid_origem'),f($RS,'sq_unidade'),$TP).'</td></tr>');
  } else {
    ShowHTML('   <tr><td>Pessoa:</td>');
    ShowHTML('       <td>'.f($RS,'nm_pessoa_origem').'</td></tr>');
    ShowHTML('   <tr><td>Interessado principal:</td>');
    ShowHTML('       <td>'.f($RS,'nm_pessoa_interes').'</td></tr>');
  }
  ShowHTML('   <tr><td>Cidade:</td>');
  ShowHTML('       <td>'.f($RS,'nm_cidade').'</td></tr>');
  ShowHTML('   <tr><td>Esp�cie documental:</td>');
  ShowHTML('       <td>'.f($RS,'nm_especie').'</td></tr>');
  ShowHTML('   <tr><td>N�mero:</td>');
  ShowHTML('       <td>'.f($RS,'numero_original').'</td></tr>');
  ShowHTML('   <tr><td>Data do documento:</td>');
  ShowHTML('       <td>'.formataDataEdicao(f($RS,'inicio')).'</td></tr>');

  ShowHTML('    <tr><td colspan=2>&nbsp;</td></tr>');
  ShowHTML('    <tr><td colspan=2><b>DADOS DA APENSA��O</b></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('    <tr><td width="30%"><u>P</u>rocesso que receber� o apenso:<td><input '.$w_Disabled.' accesskey="P" type="text" name="w_protocolo" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_protocolo.'" onKeyDown="FormataProtocolo(this,event);"></td></tr>');
  ShowHTML('    <tr><td width="30%"><u>D</u>ata da apensa��o:<td><input '.$w_Disabled.' accesskey="D" type="text" name="w_data" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td></tr>');

  ShowHTML('    <tr><td width="30%"><U>A</U>ssinatura Eletr�nica:<td> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center"><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Autuar">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de transfer�ncia de documentos para o arquivo
// -------------------------------------------------------------------------
function Arquivar() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];

  // Recupera os dados do documento
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
  if (count($RS)>0) $w_processo           = f($RS,'processo');

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
  // Se n�o for encaminhamento
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados da a��o, na op��o 'Listagem'
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_protocolo" value="'.f($RS,'protocolo').'">');
  ShowHTML('<tr><td bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
  ShowHTML('  Orienta��o:<ul>');
  ShowHTML('  <li>ATEN��O: Certifique-se de que realmente deseja arquivar este protocolo.');
  ShowHTML('  <li>Leia atentamente os dados que ser�o registrados para este arquivamento e clique no bot�o "Arquivar" para confirmar a opera��o ou no bot�o "Abandonar" para voltar � tela anterior.');
  ShowHTML('  </ul></b></font></td>');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td colspan=2><b>DADOS DO DOCUMENTO</b></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('    <tr><td width="30%">'.f($RS,'nm_tipo').':<td><b>'.f($RS,'protocolo').'</b></td></tr>');
  if (f($RS,'interno')=='S') {
    ShowHTML('   <tr><td width="30%">Unidade:</td>');
    ShowHTML('       <td>'.ExibeUnidade('../',$w_cliente,f($RS,'nm_unid_origem'),f($RS,'sq_unidade'),$TP).'</td></tr>');
  } else {
    ShowHTML('   <tr><td>Pessoa:</td>');
    ShowHTML('       <td>'.f($RS,'nm_pessoa_origem').'</td></tr>');
    ShowHTML('   <tr><td>Interessado principal:</td>');
    ShowHTML('       <td>'.f($RS,'nm_pessoa_interes').'</td></tr>');
  }
  ShowHTML('   <tr><td>Cidade:</td>');
  ShowHTML('       <td>'.f($RS,'nm_cidade').'</td></tr>');
  ShowHTML('   <tr><td>Esp�cie documental:</td>');
  ShowHTML('       <td>'.f($RS,'nm_especie').'</td></tr>');
  ShowHTML('   <tr><td>N�mero:</td>');
  ShowHTML('       <td>'.f($RS,'numero_original').'</td></tr>');
  ShowHTML('   <tr><td>Data do documento:</td>');
  ShowHTML('       <td>'.formataDataEdicao(f($RS,'inicio')).'</td></tr>');

  ShowHTML('    <tr><td colspan=2>&nbsp;</td></tr>');
  ShowHTML('    <tr><td colspan=2><b>DADOS DO ARQUIVAMENTO</b></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('    <tr><td width="30%">Data do arquivamento:<td><b>'.formataDataEdicao(time()).'</b></td></tr>');
  $RS_Unid = db_getUorgData::getInstanceOf($dbms,$_SESSION['LOTACAO']);
  ShowHTML('    <tr><td width="30%">Unidade arquivadora:<td><b>'.f($RS_Unid,'nome').'</b></td></tr>');
  ShowHTML('    <tr><td width="30%">Usu�rio arquivador:<td><b>'.$_SESSION['NOME'].'</b></td></tr>');

  ShowHTML('    <tr><td colspan=2>&nbsp;</td></tr>');
  ShowHTML('    <tr><td colspan=2><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center"><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Arquivar">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de registro da elimina��o de documentos
// -------------------------------------------------------------------------
function Eliminar() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];

  // Recupera os dados do documento
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
  if (count($RS)>0) $w_processo           = f($RS,'processo');

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
  // Se n�o for encaminhamento
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados da a��o, na op��o 'Listagem'
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_protocolo" value="'.f($RS,'protocolo').'">');
  ShowHTML('<tr><td bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
  ShowHTML('  Orienta��o:<ul>');
  ShowHTML('  <li>ATEN��O: Certifique-se de que realmente deseja eliminar este protocolo.');
  ShowHTML('  <li>Leia atentamente os dados que ser�o registrados para esta elimina��o e clique no bot�o "Eliminar" para confirmar a opera��o ou no bot�o "Abandonar" para voltar � tela anterior.');
  ShowHTML('  </ul></b></font></td>');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td colspan=2><b>DADOS DO DOCUMENTO</b></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('    <tr><td width="30%">'.f($RS,'nm_tipo').':<td><b>'.f($RS,'protocolo').'</b></td></tr>');
  if (f($RS,'interno')=='S') {
    ShowHTML('   <tr><td width="30%">Unidade:</td>');
    ShowHTML('       <td>'.ExibeUnidade('../',$w_cliente,f($RS,'nm_unid_origem'),f($RS,'sq_unidade'),$TP).'</td></tr>');
  } else {
    ShowHTML('   <tr><td>Pessoa:</td>');
    ShowHTML('       <td>'.f($RS,'nm_pessoa_origem').'</td></tr>');
    ShowHTML('   <tr><td>Interessado principal:</td>');
    ShowHTML('       <td>'.f($RS,'nm_pessoa_interes').'</td></tr>');
  }
  ShowHTML('   <tr><td>Cidade:</td>');
  ShowHTML('       <td>'.f($RS,'nm_cidade').'</td></tr>');
  ShowHTML('   <tr><td>Esp�cie documental:</td>');
  ShowHTML('       <td>'.f($RS,'nm_especie').'</td></tr>');
  ShowHTML('   <tr><td>N�mero:</td>');
  ShowHTML('       <td>'.f($RS,'numero_original').'</td></tr>');
  ShowHTML('   <tr><td>Data do documento:</td>');
  ShowHTML('       <td>'.formataDataEdicao(f($RS,'inicio')).'</td></tr>');

  ShowHTML('    <tr><td colspan=2>&nbsp;</td></tr>');
  ShowHTML('    <tr><td colspan=2><b>DADOS DA ELIMINA��O</b></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('    <tr><td width="30%">Data do arquivamento:<td><b>'.formataDataEdicao(time()).'</b></td></tr>');
  $RS_Unid = db_getUorgData::getInstanceOf($dbms,$_SESSION['LOTACAO']);
  ShowHTML('    <tr><td width="30%">Unidade respons�vel:<td><b>'.f($RS_Unid,'nome').'</b></td></tr>');
  ShowHTML('    <tr><td width="30%">Usu�rio respons�vel:<td><b>'.$_SESSION['NOME'].'</b></td></tr>');

  ShowHTML('    <tr><td colspan=2>&nbsp;</td></tr>');
  ShowHTML('    <tr><td colspan=2><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center"><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Eliminar">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de empr�stimo de documentos arquivados
// -------------------------------------------------------------------------
function Emprestar() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];

  // Recupera os dados do documento
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
  if (count($RS)>0) $w_processo           = f($RS,'processo');

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  ScriptOpen('JavaScript');
  FormataProtocolo();
  FormataData();
  SaltaCampo();
  CheckBranco();
  ValidateOpen('Validacao');
  Validate('w_sq_unidade','Unidade de destino','SELECT',1,1,18,'','0123456789');
  Validate('w_data','Data do empr�stimo','DATA','1','10','10','','0123456789/');
  Validate('w_retorno_limite','Data limite para retorno','DATA','',10,10,'','0123456789/');
  CompData('w_retorno_limite','Data limite para retorno','>=',FormataDataEdicao(time()),'data atual');
  Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
  // Se n�o for encaminhamento
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_sq_unidade.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados da a��o, na op��o 'Listagem'
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<tr><td bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
  ShowHTML('  Orienta��o:<ul>');
  ShowHTML('  <li>ATEN��O: Verifique se realmente deseja emprestar este protocolo.');
  ShowHTML('  <li>Informe os dados solicitados e clique no bot�o "Emprestar" para confirmar a opera��o ou no bot�o "Abandonar" para voltar � tela anterior.');
  ShowHTML('  </ul></b></font></td>');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td colspan=2><b>PROTOCOLO A SER EMPRESTADO</b></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('    <tr><td width="30%">'.f($RS,'nm_tipo').':<td><b>'.f($RS,'protocolo').'</b></td></tr>');
  if (f($RS,'interno')=='S') {
    ShowHTML('   <tr><td width="30%">Unidade:</td>');
    ShowHTML('       <td>'.ExibeUnidade('../',$w_cliente,f($RS,'nm_unid_origem'),f($RS,'sq_unidade'),$TP).'</td></tr>');
  } else {
    ShowHTML('   <tr><td>Pessoa:</td>');
    ShowHTML('       <td>'.f($RS,'nm_pessoa_origem').'</td></tr>');
    ShowHTML('   <tr><td>Interessado principal:</td>');
    ShowHTML('       <td>'.f($RS,'nm_pessoa_interes').'</td></tr>');
  }
  ShowHTML('   <tr><td>Cidade:</td>');
  ShowHTML('       <td>'.f($RS,'nm_cidade').'</td></tr>');
  ShowHTML('   <tr><td>Esp�cie documental:</td>');
  ShowHTML('       <td>'.f($RS,'nm_especie').'</td></tr>');
  ShowHTML('   <tr><td>N�mero:</td>');
  ShowHTML('       <td>'.f($RS,'numero_original').'</td></tr>');
  ShowHTML('   <tr><td>Data do documento:</td>');
  ShowHTML('       <td>'.formataDataEdicao(f($RS,'inicio')).'</td></tr>');

  ShowHTML('    <tr><td colspan=2>&nbsp;</td></tr>');
  ShowHTML('    <tr><td colspan=2><b>DADOS DO EMPR�STIMO</b></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('    <tr><td width="30%"><u>U</u>nidade a emprestar:');
  SelecaoUnidade(null,'U',null,$w_sq_unidade,null,'w_sq_unidade','MOD_PA',null);
  ShowHTML('    <tr><td width="30%"><u>D</u>ata do empr�stimo:<td><input '.$w_Disabled.' accesskey="D" type="text" name="w_data" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td></tr>');
  ShowHTML('    <tr><td width="30%">Data <u>l</u>imite para retorno:<td><input '.$w_Disabled.' accesskey="O" type="text" name="w_retorno_limite" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_retorno_limite.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_retorno_limite').'</td></tr>');

  ShowHTML('    <tr><td width="30%"><U>A</U>ssinatura Eletr�nica:<td> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center"><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Emprestar">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  $w_file       = '';
  $w_tamanho    = '';
  $w_tipo       = '';
  $w_nome       = '';
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen(null);
  if ($SG=='PADAUTUA') {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putDocumentoAutua::getInstanceOf($dbms,$_REQUEST['w_chave'],$_SESSION['LOTACAO'],$_SESSION['SQ_PESSOA']);

      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Autua��o realizada com sucesso!\\nImprima a etiqueta na pr�xima tela.\');');
      ShowHTML('  parent.menu.location=\''.montaURL_JS(null,$conRootSIW.'menu.php?par=ExibeDocs&O=P&R='.$R.'&SG=RELPAETIQ&TP='.RemoveTP(RemoveTP($TP)).'&p_protocolo='.$_REQUEST['w_protocolo']).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'Bloco de dados n�o encontrado: '.$SG.'\');');
    ScriptClose();
    exibevariaveis();
  } 
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  global $w_Disabled;
  switch ($par) {
    case 'INICIAL':         Inicial();          break;
    case 'AUTUAR':          Autuar();           break;
    case 'ANEXAR':          Anexar();           break;
    case 'APENSAR':         Apensar();          break;
    case 'ARQUIVAR':        Arquivar();         break;
    case 'ELIMINAR':        Eliminar();         break;
    case 'EMPRESTAR':       Emprestar();        break;
    case 'EMITIRGR':        EmitirGR();         break;
    case 'GRAVA':           Grava();            break;
    default:
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=this.focus();');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Rodape();
    exibevariaveis();
  break;
  } 
} 
?>