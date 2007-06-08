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
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getEspecieDocumento_PA.php');
include_once($w_dir_volta.'classes/sp/db_getUnidade_PA.php');
include_once($w_dir_volta.'classes/sp/db_getNaturezaDoc_PA.php');
include_once($w_dir_volta.'classes/sp/db_getParametro.php');
include_once($w_dir_volta.'classes/sp/db_getAssunto_PA.php');
include_once($w_dir_volta.'classes/sp/db_getProtocolo.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoTipoGuarda.php');
include_once($w_dir_volta.'funcoes/selecaoAssunto.php');
include_once('visualGR.php');

// =========================================================================
//  /relatorio.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papad�polis
// Descricao: Relat�rios do m�dulo de protocolo e arquivo
// Mail     : alex@sbpi.com.br
// Criacao  : 27/02/2007, 15:25
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
$w_pagina     = 'relatorio.php?par=';
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
function Tramitacao() {
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
    ShowHTML('  <li>Selecione a guia desejada para impress�o, clicando sobre a opera��o <i>Emitir</i>.');
    ShowHTML('  <li>A impress�o n�o ocorre diretamente. Ser� gerado um arquivo no formato Word, que voc� poder� enviar para a impressora.');
    ShowHTML('  <li>ATEN��O: recomenda-se salvar o arquivo gerado, ao inv�s de abri-lo diretamente.');
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
    ShowHTML('          <td><b>'.linkOrdena('Guia','guia_tramite').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Destino','nm_destino').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Despacho','nm_despacho').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Protocolo','protocolo').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Envio','phpdt_envio').'</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) { 
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      $w_atual = '';
      foreach ($RS1 as $row) {
        if ($w_atual=='' || $w_atual!=f($row,'guia_tramite')) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('        <td>'.f($row,'guia_tramite').'</td>');
          ShowHTML('        <td>'.f($row,'nm_destino').'</td>');
          ShowHTML('        <td>'.f($row,'nm_despacho').'</td>');
          ShowHTML('        <td align="center"><A class="HL" HREF="'.$w_dir.'documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" target="visualdoc" title="Exibe as informa��es deste registro.">'.f($row,'protocolo').'&nbsp;</a>');
          ShowHTML('        <td align="center">'.formataDataEdicao(f($row,'phpdt_envio'),3).'</td>');
          ShowHTML('        <td align="top" nowrap>');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'EmitirGR&R='.$w_pagina.$par.'&O=L&w_unidade='.f($row,'unidade_autuacao').'&w_nu_guia='.f($row,'nu_guia').'&w_ano_guia='.f($row,'ano_guia').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" target="GR">Emitir</A>&nbsp');
          ShowHTML('        </td>');
          ShowHTML('      </tr>');
        $w_atual = f($row,'guia_tramite');
        } else {
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('        <td align="center">'.f($row,'protocolo').'</td>');
          ShowHTML('        <td align="center">'.formataDataEdicao(f($row,'phpdt_envio'),3).'</td>');
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('      </tr>');
        }
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
// Emite etiqueta de processo
// -------------------------------------------------------------------------
function Etiqueta() {
  extract($GLOBALS);
  global $w_Disabled;

  // Recupera as vari�veis utilizadas na filtragem
  $p_posicao      = $_REQUEST['p_posicao'];
  $p_protocolo    = $_REQUEST['p_protocolo'];
  $p_chave        = $_REQUEST['p_chave'];
  $p_chave_aux    = $_REQUEST['p_chave_aux'];
  $p_prefixo      = substr($p_protocolo,0,5);
  $p_numero       = substr($p_protocolo,6,6);
  $p_ano          = substr($p_protocolo,13,4);

  if ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getProtocolo::getInstanceOf($dbms, $w_menu, $w_usuario, $SG, $p_chave, $p_chave_aux, 
        $p_prefixo, $p_numero, $p_ano, null, null, null, null, null, null, 2);
    $RS = SortArray($RS,'sg_unidade','asc', 'ano_guia','desc','nu_guia','asc','protocolo','asc');
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
    Validate('p_protocolo','N�mero de protocolo','1','1','20','20','','0123456789./-');
    ShowHTML('  var i; ');
    ShowHTML('  var w_erro=true; ');
    ShowHTML('  for (i=0; i < theForm["p_posicao"].length; i++) {');
    ShowHTML('    if (theForm["p_posicao"][i].checked) w_erro=false;');
    ShowHTML('  }');
    ShowHTML('  if (w_erro) {');
    ShowHTML('    alert(\'Indique a posi��o de impress�o da etiqueta!\'); ');
    ShowHTML('    return false;');
    ShowHTML('  }');
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
    ShowHTML('  <li>Verifique se este � realmente o documento que deseja imprimir a etiqueta e clique sobre o bot�o <i>Emitir</i>.');
    ShowHTML('  <li>A impress�o n�o ocorre diretamente. Ser� gerado um arquivo no formato Word, que voc� poder� enviar para a impressora.');
    ShowHTML('  <li>ATEN��O: recomenda-se salvar o arquivo gerado, ao inv�s de abri-lo diretamente.');
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
    ShowHTML('          <td rowspan=2><b>�ltimo despacho</td>');
    ShowHTML('          <td rowspan=2><b>Protocolo</td>');
    ShowHTML('          <td rowspan=2><b>Unidade de Origem</td>');
    ShowHTML('          <td colspan=4><b>Documento original</td>');
    ShowHTML('          <td rowspan=2><b>Limite</td>');
    ShowHTML('          <td rowspan=2><b>Opera��es</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Esp�cie</td>');
    ShowHTML('          <td><b>N�</td>');
    ShowHTML('          <td><b>Data</td>');
    ShowHTML('          <td><b>Origem</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) { 
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=9 align="center"><font size=3><b>O protocolo informado n�o foi encontrado ou n�o � um processo.</b></font></td></tr>');
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

        // Configura o texto que informa ao usu�rio a posi��o de impress�o da etiqueta
        if ($p_posicao=='S') $w_texto = 'Emitir na parte superior da folha';
        elseif ($p_posicao=='M') $w_texto = 'Emitir no meio da folha';
        else $w_texto = 'Emitir na parte inferior da folha';

        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'EmitirEtiqueta&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_posicao='.$p_posicao.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">'.$w_texto.'</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
        $w_atual = f($row,'guia_tramite');
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
    ShowHTML('  <li>Informe o n�mero do processo, a posi��o da folha em que deseja imprimir a etiqueta e clique sobre a opera��o <i>Aplicar filtro</i>.');
    ShowHTML('  <li>Ap�s verificar se o protocolo informado existe e que � um processo, o sistema permitir� a impress�o da etiqueta.');
    ShowHTML('  </ul></b></font></td>');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><b><u>P</u>rotocolo:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="p_protocolo" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$p_protocolo.'" onKeyDown="FormataProtocolo(this,event);"></td>');
    ShowHTML('      <tr><td><b>Posi��o da etiqueta');
    if ($p_posicao=='S') ShowHTML('<br><input checked type="radio" name="p_posicao" class="STR" VALUE="S"> Etiqueta localizada na parte superior da folha'); else ShowHTML('<br><input type="radio" name="p_posicao" class="STR" VALUE="S"> Etiqueta localizada na parte superior da folha');
    if ($p_posicao=='M') ShowHTML('<br><input checked type="radio" name="p_posicao" class="STR" VALUE="M"> Etiqueta localizada no meio da folha'); else ShowHTML('<br><input type="radio" name="p_posicao" class="STR" VALUE="M"> Etiqueta localizada no meio da folha');
    if ($p_posicao=='I') ShowHTML('<br><input checked type="radio" name="p_posicao" class="STR" VALUE="I"> Etiqueta localizada na parte inferior da folha'); else ShowHTML('<br><input type="radio" name="p_posicao" class="STR" VALUE="I"> Etiqueta localizada na parte inferior da folha');
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
// Rotina de visualiza��o da etiqueta
// -------------------------------------------------------------------------
function EmitirEtiqueta () {
  extract($GLOBALS);

//exibevariaveis();
  $w_chave   = $_REQUEST['w_chave'];
  $w_posicao = $_REQUEST['w_posicao'];
  
  $w_altura  = 250;
  $w_largura = 400;

  // Recupera os dados do cliente
  $RS_Cliente = db_getcustomerData::getInstanceOf($dbms,$_SESSION['P_CLIENTE']);

  // Recupera os dados do documento
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'PADGERAL');

  HeaderWord('PORTRAIT');
  ShowHTML('<table width="'.$w_largura.'" cellpadding=0 cellspacing=0 border=0>');
  if ($w_posicao=='M') {
    ShowHTML('<tr><td height="'.$w_altura.'">&nbsp;</td>');
  } elseif ($w_posicao=='I') {
    ShowHTML('<tr><td height="'.$w_altura.'">&nbsp;</td>');
    ShowHTML('<tr><td height="'.$w_altura.'">&nbsp;</td>');
  }
  ShowHTML('<tr><td height="'.$w_altura.'">');
  ShowHTML('  <table width="100%" cellpadding=5 cellspacing=0 border=0>');
  ShowHTML('    <tr><td><font size=4><b>'.f($RS_Cliente,'nome_resumido').'/'.f($RS,'sg_unidade_resp').'</b></font>');
  ShowHTML('    <tr><td><hr noshade>');
  ShowHTML('    <tr><td align="center"><font size=5><br><b>'.f($RS,'protocolo').'</b><br><br></font>');
  ShowHTML('    <tr><td><hr noshade>');
  ShowHTML('    <tr><td align="center"><font size=4><b>'.formataDataEdicao(f($RS,'data_autuacao')).'</b></font>');
  ShowHTML('  </table>');
  ShowHTML('</td></tr>');
  ShowHTML('</table>');
  ShowHTML('</body>');
  ShowHTML('</html>');
} 
// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  global $w_Disabled;
  switch ($par) {
    case 'ETIQUETA':        Etiqueta();        break;
    case 'EMITIRETIQUETA':  EmitirEtiqueta();  break;
    case 'TRAMITE':         Tramitacao();      break;
    case 'EMITIRGR':        EmitirGR();        break;
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