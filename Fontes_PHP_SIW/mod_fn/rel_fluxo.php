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
include_once($w_dir_volta.'classes/sp/db_getLancamento.php');
include_once($w_dir_volta.'funcoes/montaRadioMes.php');
// ========================================================================
//  /rel_fluxo.php
// ------------------------------------------------------------------------
// Nome     : Billy Jones Leal dos Santos
// Descricao: Diversos tipos de relatórios para fazer o acompanhamento gerencial 
// Mail     : billy@sbpi.com.br
// Criacao  : 25/07/2006 11:00
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
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par        = upper($_REQUEST['par']);
$P1         = nvl($_REQUEST['P1'],0);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);
$w_assinatura   = upper($_REQUEST['w_assinatura']);
$w_pagina       = 'rel_fluxo.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_fn/';
$w_troca        = $_REQUEST['w_troca'];
if ($O=='') {
  if ($par=='INICIAL') $O='P'; else $O='L';
} 
switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';        break;
  case 'A': $w_TP=$TP.' - Alteração';       break;
  case 'E': $w_TP=$TP.' - Exclusão';        break;
  case 'P': $w_TP=$TP.' - Filtragem';       break;
  case 'C': $w_TP=$TP.' - Cópia';           break;
  case 'V': $w_TP=$TP.' - Envio';           break;
  case 'H': $w_TP=$TP.' - Herança';         break;
  default:  $w_TP=$TP.' - Listagem';        break;
}
// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.

$w_cliente=RetornaCliente();
$w_usuario=RetornaUsuario();
$w_menu=RetornaMenu($w_cliente,$SG);
$w_ano=2005;
Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Relatório de contas a pagar e contas a receber
// -------------------------------------------------------------------------
function Inicial(){
  extract($GLOBALS);
  global $w_Disabled;
  $w_tipo_rel   = upper(trim($_REQUEST['w_tipo_rel']));
  $p_mes_fluxo  = upper(trim($_REQUEST['p_mes_fluxo']));
  $p_ano_fluxo  = upper(trim($_REQUEST['p_ano_fluxo']));
  if ($O=='L') {
    $p_dt_ini = First_Day(toDate('01/'.$p_mes_fluxo.'/'.$p_ano_fluxo));
    $p_dt_fim = Last_Day(toDate('27/'.$p_mes_fluxo.'/'.$p_ano_fluxo));
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'') {
      $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
    } 
    // Recupera todos os registros para a listagem
    $RS = db_getLancamento::getInstanceOf($dbms,$w_cliente,$SG,FormataDataEdicao($p_dt_ini),FormataDataEdicao($p_dt_fim),null,'EE,ER');
    $RS = SortArray($RS,'vencimento','asc','tipo','asc');
  } 
  if ($w_tipo_rel=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_pag=1;
    $w_linha=5;
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if (!(strpos($SG,'FLUXOPR')===false)) {
      CabecalhoWord($w_cliente,'Fluxo de Caixa Previsto',$w_pag);
    } elseif (!(strpos($SG,'FLUXORE')===false)) {
      CabecalhoWord($w_cliente,'Fluxo de Caixa Realizado',$w_pag);
    } 
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Relatório do Fluxo de Caixa</TITLE>');
    if ($O=='P') {
      ScriptOpen('JavaScript');
      CheckBranco();
      FormataData();
      SaltaCampo();
      ValidateOpen('Validacao');
      Validate('p_ano_fluxo','Ano','1','1','4','4','','0123456789');
      CompValor('p_ano_fluxo','Ano','>','1754','1754');
      ValidateClose();
      ScriptClose();
    } 
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($O=='L') {
      BodyOpenClean('onLoad=\'this.focus()\';');
      if (!(strpos($SG,'FLUXOPR'))===false) {
        CabecalhoRelatorio($w_cliente,'Fluxo de Caixa Previsto',4,$w_chave);
        ShowHTML('Fluxo de Caixa Previsto');
      } elseif (!(strpos($SG,'FLUXORE'))===false) {
        CabecalhoRelatorio($w_cliente,'Fluxo de Caixa Realizado',4,$w_chave);;
      } 
    } else {
      BodyOpen('onLoad=\'document.Form.Botao.focus()\';');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    } 
    ShowHTML('<HR>');
  } 
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem 
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan=2><b>Dia</td>');
    ShowHTML('          <td colspan=2><font size="2"><b>'.substr(FormataDataEdicao(addDays($p_dt_ini,-50)),3,7).'</font></td>');
    ShowHTML('          <td colspan=2><font size="2"><b>'.substr(FormataDataEdicao(addDays($p_dt_ini,-1)),3,7).'</font></td>');
    ShowHTML('          <td colspan=2><font size="2"><b>'.substr(FormataDataEdicao($p_dt_ini),3,7).'</font></td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>A pagar</td>');
    ShowHTML('          <td><b>A receber</td>');
    ShowHTML('          <td><b>A pagar</td>');
    ShowHTML('          <td><b>A receber</td>');
    ShowHTML('          <td><b>A pagar</td>');
    ShowHTML('          <td><b>A receber</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
    // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_valor      = 0.00;
      $w_valor_total= 0.00;
      $w_atual      = '';
      $w_tipo       = 0;
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        if ($w_atual=='' || $w_atual!=FormataDataEdicao(f($row,'vencimento')) || ($w_tipo==1 && f($row,'tipo')=='R') || ($w_tipo==2 && f($row,'tipo')=='D')) {
          if ($w_atual>'') {
            $w_celula[substr($w_atual,0,2)][$w_mes][$w_tipo]     = $w_valor;
            $w_coluna[$w_mes][$w_tipo]                          += $w_valor;
            $w_total[$w_tipo]                                   += $w_valor;
            $w_valor_total                                      += $w_valor;
          } 
          $w_atual = FormataDataEdicao(f($row,'vencimento'));
          // Configura o 2º indice do array
          if (substr(FormataDataEdicao(f($row,'vencimento')),3,2)==substr(FormataDataEdicao($p_dt_fim),3,2))       $w_mes=3;
          elseif (substr(FormataDataEdicao(f($row,'vencimento')),3,2)==substr(FormataDataEdicao($p_dt_fim),3,2)-1) $w_mes=2;
          else $w_mes=1;
          // Configura o 3º indice do array
          if (f($row,'tipo')=='D') $w_tipo=1; else $w_tipo=2;
          $w_valor = 0;
        } 
        $w_valor += f($row,'valor');
      } 
      // Configura o 2º indice do array
      if (substr($w_atual,3,2)==substr($p_dt_fim,3,2))        $w_mes=3;
      elseif (substr($w_atual,3,2)==substr($p_dt_fim,3,2)-1)  $w_mes=2;
      else                                                    $w_mes=1;
      $w_celula[substr($w_atual,0,2)][$w_mes][$w_tipo]   = $w_valor;
      $w_coluna[$w_mes][$w_tipo]                        += $w_valor;
      $w_total[$w_tipo]                                 += $w_valor;
      $w_valor_total                                    += $w_valor;
      for ($i=1; $i<=31; $i=$i+1) {
        if ($w_cor==$conTrBgColor || $w_cor=='') $w_cor=$conTrAlternateBgColor; else $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center">'.substr(100+$i,1,2));
        for ($j=1; $j<=3; $j=$j+1) {
          for ($k=1; $k<=2; $k=$k+1) {
            if ($w_cor==$conTrAlternateBgColor) {
              if ($k==1) {
                $w_cor1=$w_cor;
              } else {
                $w_cor1='#D5D5D5';
              }
            } else {
              $w_cor1=$w_cor;
            }
            if ($w_celula[$i][$j][$k]!=0) {
              ShowHTML('        <td bgcolor="'.$w_cor1.'" align="right">'.Nvl(number_format(abs($w_celula[$i][$j][$k]),2,',','.'),'&nbsp;').'</td>');
            } else {
              ShowHTML('        <td bgcolor="'.$w_cor1.'" align="right">&nbsp;</td>');
            }             
          } 
        } 
      }  
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
      ShowHTML('        <td align="center"><b>Totais</b>');
      for ($j=1; $j<=3; $j=$j+1) {
        for ($k=1; $k<=2; $k=$k+1) {
          ShowHTML('        <td align="right">'.Nvl(number_format(abs($w_coluna[$j][$k]),2,',','.'),'&nbsp;').'</td>');
        } 
      } 
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
      ShowHTML('        <td colspan=6 align="right" height=18><b>Total a pagar no período </td>');
      ShowHTML('        <td align="right"><b>'.number_format(abs($w_total[1]),2,',','.').'</b></td>');
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
      ShowHTML('        <td colspan=6 align="right" height=18><b>Total a receber no período </td>');
      ShowHTML('        <td align="right"><b>'.number_format(abs($w_total[2]),2,',','.').'</b></td>');
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
      ShowHTML('        <td colspan=6 align="right" height=18><b>Saldo do período</td>');
      ShowHTML('        <td align="right"><b>'.number_format(abs($w_valor_total),2,',','.').'</b></td>');
      ShowHTML('      </tr>'); 
      ShowHTML('      </center>');
    }  
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    if ($w_tipo_rel=='WORD') {
      ShowHTML('    <br style="page-break-after:always">');
    } 
  } elseif ($O=='P') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Fluxo',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>'); 
    ShowHTML('    <table border="0">');
    ShowHTML('      <tr>');
    MontaRadioMes('<b>Mês de referência:</b>',Nvl($p_mes_fluxo,substr(FormataDataEdicao(time()),3,2)),'p_mes_fluxo');
    ShowHTML('      <td valign="top"><b><u>A</u>no:</b><br><input accesskey="A" type="text" name="p_ano_fluxo" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.Nvl($p_ano_fluxo,substr(FormataDataEdicao(time()),6,4)).'"></td>');
    ShowHTML('      </table>');
    ShowHTML('    <table width="99%" border="0">');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  if ($w_tipo_rel!='WORD') Rodape(); else ShowHTML('</div>');
} 
// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'INICIAL':     Inicial();      break;
    default:
      Cabecalho();
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');
      BodyOpen('onLoad=this.focus();');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
      ShowHTML('<HR>');
      ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
      Rodape();
    break;
  } 
} 
// =========================================================================
// Fim da rotina principal
// -------------------------------------------------------------------------
?>