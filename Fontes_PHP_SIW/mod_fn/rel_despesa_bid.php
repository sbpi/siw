<?php
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
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getMoedaCotacao.php');
include_once($w_dir_volta.'classes/sp/db_getSolicFN.php');
include_once($w_dir_volta.'classes/sp/db_getSolicRubrica.php');
include_once($w_dir_volta.'classes/sp/db_getBenef.php');
include_once($w_dir_volta.'classes/sp/db_getIndicador.php');
include_once($w_dir_volta.'classes/sp/dml_numeraLancamento.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoOrdenaRel.php');

// =========================================================================
//  /rel_despesa_bid.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Relatório de detalhamento de despesas de um projeto
// Mail     : alex@sbpi.com.br
// Criacao  : 18/07/2016 18:33
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
if ($_SESSION['LOGON'] != 'Sim') { EncerraSessao(); }

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par = upper($_REQUEST['par']);
$P1 = nvl($_REQUEST['P1'], 0);
$P2 = nvl($_REQUEST['P2'], 0);
$P3 = nvl($_REQUEST['P3'], 1);
$P4 = nvl($_REQUEST['P4'], $conPageSize);
$TP = $_REQUEST['TP'];
$SG = upper($_REQUEST['SG']);
$R = $_REQUEST['R'];
$O = upper($_REQUEST['O']);
$w_assinatura = $_REQUEST['w_assinatura'];
$w_pagina = 'rel_despesa_bid.php?par=';
$w_Disabled = 'ENABLED';
$w_dir = 'mod_fn/';
$w_troca = $_REQUEST['w_troca'];
$w_embed = '';

$p_projeto = $_REQUEST['p_projeto'];
$p_inicio = $_REQUEST['p_inicio'];
$p_fim = $_REQUEST['p_fim'];
$p_nome = upper(trim($_REQUEST['p_nome']));
$p_receita = upper(trim($_REQUEST['p_receita']));
$p_financeiro = upper(trim($_REQUEST['p_financeiro']));
$p_contabil = upper(trim($_REQUEST['p_contabil']));
$p_moedas = $_REQUEST['p_moedas'];
$p_ordena = lower($_REQUEST['p_ordena']);

$p_logo    = trim($_REQUEST['p_logo']);
$p_numero  = trim($_REQUEST['p_numero']);
$p_nome    = trim($_REQUEST['p_nome']);
$p_cargo   = trim($_REQUEST['p_cargo']);
$p_emissao = trim($_REQUEST['p_emissao']);

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if ($O == '') {
  if ($par == 'INICIAL') {
    $O = 'P';
  } else {
    $O = 'L';
  }
}
// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente, $SG);
$w_TP       = RetornaTitulo($TP, $O);

// Recupera a configuração do serviço
$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);

Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Relatórios de detalhamento das despesas de projeto.
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;
  global $w_embed;
  $w_tipo = $_REQUEST['w_tipo'];

  Cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Relatório</TITLE>');
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  ValidateOpen('Validacao');
  Validate('p_projeto', 'Projeto', 'SELECT', '1', '1', '18', '', '0123456789');
  Validate('p_inicio', 'Pagamento inicial', 'DATA', '', '10', '10', '', '0123456789/');
  Validate('p_fim', 'Pagamento final', 'DATA', '', '10', '10', '', '0123456789/');
  CompData('p_inicio', 'Pagamento inicial', '<=', 'p_fim', 'Pagamento final');
  Validate('p_numero', 'Número da solicitação', '', '', '1', '10', '1', '');
  Validate('p_nome', 'Nome completo', '', '', '1', '50', '1', '');
  Validate('p_cargo', 'Cargo/Função', '', '', '1', '50', '1', '');
  Validate('p_emissao', 'Data de emissão', 'DATA', '', '10', '10', '', '0123456789/');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</HEAD>');
  BodyOpen('onLoad="document.focus()";');
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align="center"><table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($SG=='FNRBIDCAT') $rel = 'detCat';
  else $rel = 'detDesp';
  AbreForm('Form', $w_dir.$w_pagina.$rel, 'POST', 'return(Validacao(this));', $rel, $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="p_moedas" value="N">');
  ShowHTML('<INPUT type="hidden" name="p_contabil" value="N">');
  ShowHTML('<INPUT type="hidden" name="p_receita" value="N">');
  ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td>');
  ShowHTML('    <table width="99%" border="0">');
  ShowHTML('      <tr>');
  $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,'PJCAD');
  SelecaoProjeto('Pro<u>j</u>eto:','J','Selecione o projeto do contrato na relação.',$p_projeto,$w_usuario,f($RS,'sq_menu'),null,null,null,'p_projeto','PJLIST',$w_atributo);
  ShowHTML('      </tr>');
  ShowHTML('      <tr><td><b><u>P</u>agamento entre:</b><br><input ' . $w_Disabled . ' accesskey="P" type="text" name="p_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_inicio . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_inicio') . ' e <input ' . $w_Disabled . ' type="text" name="p_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_fim') . '</td>');
  ShowHTML('      <tr><td><b>Número da <u>S</u>olicitação:</b><br><input ' . $w_Disabled . ' accesskey="S" type="text" name="p_numero" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_numero . '"></td>');
  ShowHTML('      <tr><td><b><u>N</u>ome completo:</b><br><input ' . $w_Disabled . ' accesskey="N" type="text" name="p_nome" class="sti" SIZE="35" MAXLENGTH="50" VALUE="' . $p_nome . '"></td>');
  ShowHTML('      <tr><td><b><u>C</u>argo/função:</b><br><input ' . $w_Disabled . ' accesskey="C" type="text" name="p_cargo" class="sti" SIZE="35" MAXLENGTH="50" VALUE="' . $p_cargo . '"></td>');
  ShowHTML('      <tr><td><b><u>D</u>ata do relatório:</b><br><input ' . $w_Disabled . ' accesskey="D" type="text" name="p_emissao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_emissao . '" onKeyDown="FormataData(this,event);"></td>');
  ShowHTML('      <tr>');
  MontaRadioNS('<b>Exibe logomarca do BID?</b>',$p_logo,'p_logo');
  ShowHTML('      <tr><td align="center"><hr>');
  ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir">');
  ShowHTML('            <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $w_pagina . $par . '&R=' . $R . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&O=P&SG=' . $SG) . '\';" name="Botao" value="Limpar campos">');
  ShowHTML('          </td>');
  ShowHTML('      </tr>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</div>');

  Rodape();
}

// =========================================================================
// Relatório de detalhamento das despesas.
// -------------------------------------------------------------------------
function detalhamentoDespesa() {
  extract($GLOBALS);
  $w_tipo = $_REQUEST['w_tipo'];
  
  // Recupera os dados do projeto selecionado
  $sql = new db_getSolicData; $RS_Projeto = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');

  // Executa procedure para numerar os lançamentos
  $SQL = new dml_numeraLancamento; $SQL->getInstanceOf($dbms,'I', $p_projeto);
      
  // Recupera as rubricas do projeto
  $sql = new db_getSolicRubrica; $RSQuery = $sql->getInstanceOf($dbms,$p_projeto,null,'S',null,null,(($p_financeiro=='N') ? null : 'N'),$p_inicio,$p_fim,'PJEXECLS');

  if ($p_ordena>'') { 
    $lista = explode(',',str_replace(' ',',',$p_ordena));
    $RSQuery = SortArray($RSQuery,$lista[0],$lista[1],'cd_financeiro_externo','asc','or_item','asc');
  } else {
    $RSQuery = SortArray($RSQuery,'cd_financeiro_externo','asc','or_item','asc');
  }

  $w_embed        = '';
  headerGeral('P', $w_tipo, $w_chave, $conSgSistema.' - Detalhamento de Despesas - BID', $w_embed, null, null, $w_linha_pag,$w_filtro);
  if ($w_embed!='WORD') {
    Cabecalho();
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Detalhamento de Despesas - BID</TITLE>');
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    ShowHTML('</HEAD>');
    BodyOpenClean('onLoad="this.focus()";');
    CabecalhoRelatorio($w_cliente, f($RS_Menu,'nome'), 4, $w_chave, 'S', 'S', (($p_logo=='S') ? 'img/logo-bid.png' : ''));
    ShowHTML('<HR>');
  }
  ShowHTML('<div align="center"><table border="0" cellpadding="0" cellspacing="0" width="100%">');

  $l_html = '';

  if ($w_tipo!='EXCEL') {

    $l_html .= chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="99%">';

    $l_html.=chr(13).'    <tr><td colspan="2"><table width="100%" border="0">';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0" align=justify><font size="2"><b>PROJETO: '.nvl(f($RS_Projeto,'codigo_externo'),f($RS_Projeto,'codigo_interno')).' - '.f($RS_Projeto,'titulo').' ('.f($RS_Projeto,'sq_siw_solicitacao').')</b></font></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    $l_html .= chr(13).'</table>';
  }
  $l_html.=chr(13).'      <tr><td colspan=2><br><font size="2"><b>Pagamentos por Fontes x Períodos - Solicitação nº '.$p_numero.'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan=2>Período: <b>' . nvl($p_inicio,FormataDataEdicao(f($RS_Projeto,'inicio'))) . '</b> a <b>' . nvl($p_fim,FormataDataEdicao(f($RS_Projeto,'fim'))) . '</b>';
  $l_html.=chr(13).'      <tr><td align="center" colspan="2">';
  $l_html.=chr(13).'        <table class="tudo" width=99%  border="1" cellpadding=2 cellspacing=0 bordercolor="#00000">';
  $l_html.=chr(13).'          <tr align="center">';
  $cs=0;
  $cs++; $l_html.=chr(13).'            <td bgColor="#f0f0f0" nowrap><b>Nº do DOC</td>';
  $cs++; $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Rubrica</td>';
  $cs++; $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Fornecedor</td>';
  $cs++; $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Nº Fatura</td>';
  $cs++; $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Data Pagamento</td>';
  $cs++; $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>DESCRIÇÃO DO PAGAMENTO</td>';
  $l_html.=chr(13).'            <td bgColor="#f0f0f0" nowrap><b>BID - '.f($RS_Projeto,'sb_moeda').'</td>';
  $l_html.=chr(13).'            <td bgColor="#f0f0f0" nowrap><b>LOCAL - '.f($RS_Projeto,'sb_moeda').'</td>';
  $l_html.=chr(13).'            <td bgColor="#f0f0f0" nowrap><b>TOTAL - '.f($RS_Projeto,'sb_moeda').'</td>';
  $l_html.=chr(13).'          </tr>';
  $w_cor=$conTrBgColor;
  $w_total_previsto  = 0;
  $w_total_contabil  = 0;
  $i = 0;
  foreach ($RSQuery as $row) {
    if (substr(f($row,'sg_menu'),0,3)!='FNR' || $p_receita=='S') {
      $i++;
      $l_html.=chr(13).'      <tr valign="top">';
      if (nvl(f($row,'cd_financeiro_externo'),'')!='') {
        if (strpos(f($row,'cd_financeiro_externo'),'.')>0) {
          $w_codigo = f($row,'cd_financeiro_externo') . f($row,'or_item');
        } else {
          $w_codigo = f($row,'cd_financeiro_externo');
        }
      } else {
        $w_codigo = f($row,'cd_financeiro');
      }
      $l_html.=chr(13).'          <td nowrap>&nbsp;'.exibeSolic($w_dir,(($w_tipo=='') ? f($row,'sq_financeiro') : ''),$w_codigo,'N',$w_tipo);
      $l_html.=chr(13).'          <td align="center">'.f($row,'cd_rubrica').' </td>';
      $l_html.=chr(13).'          <td>'.f($row,'nm_pessoa').' </td>';
      $l_html.=chr(13).'          <td align="center">&nbsp;'.f($row,'numero').' </td>';
      $l_html.=chr(13).'          <td align="center">'.nvl(FormataDataEdicao(f($row,'quitacao')),'&nbsp;').'</td>';
      $l_html.=chr(13).'          <td>'.f($row,'descricao').' </td>';
      $l_html.=chr(13).'          <td align="right">'.formatNumber(f($row,'valor')).' </td>';
      $l_html.=chr(13).'          <td>&nbsp;</td>';
      $l_html.=chr(13).'          <td align="right">'.formatNumber(f($row,'valor')).' </td>';
      $l_html.=chr(13).'      </tr>';
      $w_total_previsto += f($row,'valor');
      $w_total_contabil += f($row,'brl_valor_compra');
    }
  } 
  /*
  $l_html.=chr(13).'      <tr valign="top">';
  $l_html.=chr(13).'        <td colspan="'.$cs.'" align="right"><b>Total: </b></td>';
  $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total_previsto).' </b></td>';
  $l_html.=chr(13).'        <td>&nbsp;</td>';
  $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total_previsto).' </b></td>';
  $l_html.=chr(13).'      </tr>';
   * 
   */

  $l_html.=chr(13).'          <tr class="folha">';
  $l_html.=chr(13).'            <td colspan="9" align="center"><table border=0 width=40%>';
  $l_html.=chr(13).'              <tr style="height: 25"><td>&nbsp;</td></tr>';
  $l_html.=chr(13).'              <tr><td><font size="2"><b>Brasília, '.$p_emissao.'.</b></font></td></tr>';
  $l_html.=chr(13).'              <tr style="height: 25"><td>&nbsp;</td></tr>';
  $l_html.=chr(13).'              <tr><td align="center">_____________________________</td></tr>';
  $l_html.=chr(13).'              <tr><td align="center"><font size="2"><b>'.$p_nome.'</b></font></td></tr>';
  $l_html.=chr(13).'              <tr><td align="center"><font size="2"><b>'.$p_cargo.'</b></font></td></tr>';
  $l_html.=chr(13).'              <tr style="height: 25"><td>&nbsp;</td></tr>';
  $l_html.=chr(13).'            </table>';
  $l_html.=chr(13).'          </tr>';
  $l_html.=chr(13).'      </table></td></tr>';

  ShowHTML($l_html);
  ShowHTML('    </table>');
  ShowHTML('  </td>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</div>');

  if($w_tipo=='PDF') RodapePdf();
  else               Rodape();
}

// =========================================================================
// Relatório de detalhamento das despesas por rubrica.
// -------------------------------------------------------------------------
function detalhamentoRubrica() {
  extract($GLOBALS);
  $w_tipo = $_REQUEST['w_tipo'];
  
  // Recupera os dados do projeto selecionado
  $sql = new db_getSolicData; $RS_Projeto = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');

  // Executa procedure para numerar os lançamentos
  $SQL = new dml_numeraLancamento; $SQL->getInstanceOf($dbms,'I', $p_projeto);
      
  // Recupera as rubricas do projeto
  $sql = new db_getSolicRubrica; $RSQuery = $sql->getInstanceOf($dbms,$p_projeto,null,'S',null,null,(($p_financeiro=='N') ? null : 'N'),$p_inicio,$p_fim,'PJEXECLS');
  $RSQuery = SortArray($RSQuery,'cd_rubrica','asc','cd_financeiro_externo','asc','or_item','asc');

  $w_embed        = '';
  headerGeral('P', $w_tipo, $w_chave, $conSgSistema.' - Despesas por Categoria/Subcategoria - BID', $w_embed, null, null, $w_linha_pag,$w_filtro);
  if ($w_embed!='WORD') {
    Cabecalho();
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Despesas por Categoria/Subcategoria - BID</TITLE>');
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    ShowHTML('</HEAD>');
    BodyOpenClean('onLoad="this.focus()";');
    CabecalhoRelatorio($w_cliente, f($RS_Menu,'nome'), 4, $w_chave, 'S', 'S', (($p_logo=='S') ? 'img/logo-bid.png' : ''));
    ShowHTML('<HR>');
  }
  ShowHTML('<div align="center"><table border="0" cellpadding="0" cellspacing="0" width="100%">');

  $l_html  = '';
  $w_pai   = 0;
  $w_filho = 0;

  $l_html.=chr(13).'      <tr><td align="center" colspan="2">';
  $w_cor=$conTrBgColor;
  $w_lin_brl  = 0;
  $w_lin_usd  = 0;
  $w_lin_bid  = 0;
  $w_lin_out  = 0;
  $w_pag_brl  = 0;
  $w_pag_usd  = 0;
  $w_pag_bid  = 0;
  $w_pag_out  = 0;
  $w_filho_brl  = 0;
  $w_filho_usd  = 0;
  $w_filho_bid  = 0;
  $w_filho_out  = 0;
  $w_pai_brl  = 0;
  $w_pai_usd  = 0;
  $w_pai_bid  = 0;
  $w_pai_out  = 0;
  $w_tot_brl  = 0;
  $w_tot_usd  = 0;
  $w_tot_bid  = 0;
  $w_tot_out  = 0;
  $w_total_contabil  = 0;
  $i = 0;
  $linha = 0;
  $limite = 18;
  foreach ($RSQuery as $row) {
    if ($w_filho==0 || $linha>=$limite || $w_filho!=f($row,'cd_rubrica')) {
      if ($w_filho>0 || $linha >0) {
        $l_html.=chr(13).'          <tr valign="top">';
        $l_html.=chr(13).'            <td colspan="2" rowspan="5">&nbsp;</td>';
        $l_html.=chr(13).'            <td colspan="4">&nbsp;</td>';
        $l_html.=chr(13).'            <td>&nbsp;</td>';
        $l_html.=chr(13).'            <td>&nbsp;</td>';
        $l_html.=chr(13).'            <td colspan="2">&nbsp;</td>';
        $l_html.=chr(13).'            <td>&nbsp;</td>';
        $l_html.=chr(13).'          </tr>';
        if ($w_pai!=$w_filho || $linha>=$limite) {
          $l_html.=chr(13).'          <tr valign="top">';
          $l_html.=chr(13).'            <td colspan="3" align="right"><b>Total desta página </b></td>';
          $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_lin_brl).' </b></td>';
          $l_html.=chr(13).'            <td>&nbsp;</td>';
          $l_html.=chr(13).'            <td>&nbsp;</td>';
          $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_lin_usd).' </b></td>';
          $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_lin_bid).' </b></td>';
          $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_lin_out).' </b></td>';
          $l_html.=chr(13).'          </tr>';
          $l_html.=chr(13).'          <tr valign="top">';
          $l_html.=chr(13).'            <td colspan="3" align="right"><b>'.(($w_filho!=f($row,'cd_rubrica')) ? 'Total' : 'Subtotal').' das páginas anteriores</b></td>';
          $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_pag_brl).' </b></td>';
          $l_html.=chr(13).'            <td>&nbsp;</td>';
          $l_html.=chr(13).'            <td>&nbsp;</td>';
          $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_pag_usd).' </b></td>';
          $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_pag_bid).' </b></td>';
          $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_pag_out).' </b></td>';
          $l_html.=chr(13).'          </tr>';
          $l_html.=chr(13).'          <tr valign="top">';
          $l_html.=chr(13).'            <td colspan="3" align="right"><b>'.(($w_filho!=f($row,'cd_rubrica')) ? 'Total' : 'Subtotal').' da Subcategoria '.$w_filho.'</b></td>';
          $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_filho_brl).' </b></td>';
          $l_html.=chr(13).'            <td>&nbsp;</td>';
          $l_html.=chr(13).'            <td>&nbsp;</td>';
          $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_filho_usd).' </b></td>';
          $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_filho_bid).' </b></td>';
          $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_filho_out).' </b></td>';
          $l_html.=chr(13).'          </tr>';
          if ($linha>=$limite) {
            $w_pag_brl    += $w_lin_brl;
            $w_pag_usd    += $w_lin_usd;
            $w_pag_bid    += $w_lin_bid;
            $w_pag_out    += $w_lin_out;
          }
          $w_lin_brl    = 0;
          $w_lin_usd    = 0;
          $w_lin_bid    = 0;
          $w_lin_out    = 0;
        }
        $l_html.=chr(13).'          <tr valign="top">';
        $l_html.=chr(13).'            <td colspan="3" align="right"><b>'.(($w_pai!=f($row,'cd_rubrica_pai')) ? 'Total' : 'Subtotal').' da Categoria '.$w_pai.'</b></td>';
        $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_pai_brl).' </b></td>';
        $l_html.=chr(13).'            <td>&nbsp;</td>';
        $l_html.=chr(13).'            <td>&nbsp;</td>';
        $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_pai_usd).' </b></td>';
        $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_pai_bid).' </b></td>';
        $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_pai_out).' </b></td>';
        $l_html.=chr(13).'          </tr>';
        $l_html.=chr(13).'        </table> ';
        if ($w_tipo=='EXCEL') {
          $l_html.=chr(13).'        <table class="tudo" border="0">';
          $l_html.=chr(13).'          <tr><td>&nbsp;</td>';
          $l_html.=chr(13).'          <tr><td>&nbsp;</td>';
          $l_html.=chr(13).'        </table>';
        }
        $l_html.=chr(13).'        <div style="height: 10px;"></div> ';
        if ($w_pai==0 || $w_pai!=f($row,'cd_rubrica_pai')) {
          $w_pai_brl  = 0;
          $w_pai_usd  = 0;
          $w_pai_bid  = 0;
          $w_pai_out  = 0;
        }
        $linha = 0;
      }
      if ($w_filho!=f($row,'cd_rubrica')) {
        $w_pag_brl    = 0;
        $w_pag_usd    = 0;
        $w_pag_bid    = 0;
        $w_pag_out    = 0;
        $w_filho_brl  = 0;
        $w_filho_usd  = 0;
        $w_filho_bid  = 0;
        $w_filho_out  = 0;
      }
      $w_filho    = f($row,'cd_rubrica');
      $w_nm_filho = f($row,'nm_rubrica');
      if (f($row,'cd_rubrica_pai')!='') {
        $w_pai    = f($row,'cd_rubrica_pai');
        $w_nm_pai = f($row,'nm_rubrica_pai');
      } else {
        $w_pai    = f($row,'cd_rubrica');
        $w_nm_pai = f($row,'nm_rubrica');
      }
      $l_html.=chr(13).'        <table style="page-break-after: always;" class="tudo" width=99%  border="1" cellpadding=2 cellspacing=0 bordercolor="#00000">';
      $l_html.=chr(13).'          <tr align="center">';
      $l_html.=chr(13).'            <td rowspan="2" colspan="6" bgColor="#f0f0f0" align="left"><b>Executor:<br> '.f($RS_Projeto,'titulo').'</td>';
      $l_html.=chr(13).'            <td rowspan="2" colspan="3" bgColor="#f0f0f0" align="left"><b>Número da Operação:<br> '.nvl(f($RS_Projeto,'codigo_externo'),f($RS_Projeto,'codigo_interno')).'</td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Pedido:</td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>' . nvl($p_inicio,FormataDataEdicao(f($RS_Projeto,'inicio'),5)) . '</td>';
      $l_html.=chr(13).'          </tr>';
      $l_html.=chr(13).'          <tr align="center">';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>'.$p_numero.'</td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>' . nvl($p_fim,FormataDataEdicao(f($RS_Projeto,'fim'),5)) . '</td>';
      $l_html.=chr(13).'          </tr>';
      $l_html.=chr(13).'          <tr align="center">';
      $l_html.=chr(13).'            <td rowspan="2" colspan="4" bgColor="#f0f0f0" align="left"><b>Número e Título do Componente:<br> '.$w_pai.' '.$w_nm_pai.'</td>';
      $l_html.=chr(13).'            <td rowspan="2" colspan="5" bgColor="#f0f0f0" align="left"><b>Número e Título da Subcategoria:<br> '.$w_filho.' '.$w_nm_filho.'</td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Moeda:</td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>&nbsp;</td>';
      $l_html.=chr(13).'          </tr>';
      $l_html.=chr(13).'          <tr align="center">';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>'.f($RS_Projeto,'sg_moeda').'</td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>&nbsp;</td>';
      $l_html.=chr(13).'          </tr>';
      $l_html.=chr(13).'          <tr align="center">';
      $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>Nº do Item</td>';
      $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>Nome e endereço do Contratista, o fornecedor, etc., o contrato/ordem de compra, número de referência</td>';
      $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>Nº de referência da fatura</td>';
      $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>País de origem</td>';
      $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>Data Pagamento</td>';
      $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>Montante na moeda de pagamento (R$)</td>';
      $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>Taxa de câmbio</td>';
      $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0" width="8%"><b>Banco</td>';
      $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>Equivalente na moeda da operação ('.f($RS_Projeto,'sb_moeda').')</td>';
      $l_html.=chr(13).'            <td colspan="2" bgColor="#f0f0f0"><b>Financiamento</td>';
      $l_html.=chr(13).'          </tr>';
      $l_html.=chr(13).'          <tr align="center">';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0" nowrap><b>BID</td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0" nowrap><b>Outras fontes</td>';
      $l_html.=chr(13).'          </tr>';
      $w_total_previsto  = 0;
      $w_total_contabil  = 0;
      $w_filho = f($row,'cd_rubrica');
    }

    if (substr(f($row,'sg_menu'),0,3)!='FNR' || $p_receita=='S') {
      $i++;
      $l_html.=chr(13).'      <tr valign="top">';
      if (nvl(f($row,'cd_financeiro_externo'),'')!='') {
        if (strpos(f($row,'cd_financeiro_externo'),'.')>0) {
          $w_codigo = f($row,'cd_financeiro_externo') . f($row,'or_item');
        } else {
          $w_codigo = f($row,'cd_financeiro_externo');
        }
      } else {
        $w_codigo = f($row,'cd_financeiro');
      }
      $l_html.=chr(13).'          <td nowrap>&nbsp;'.exibeSolic($w_dir,(($w_tipo=='') ? f($row,'sq_financeiro') : ''),$w_codigo,'N',$w_tipo);
      $l_html.=chr(13).'          <td>'.f($row,'nm_pessoa').' </td>';
      $l_html.=chr(13).'          <td align="center">&nbsp;'.f($row,'numero').' </td>';
      $l_html.=chr(13).'          <td>&nbsp;'.f($row,'nm_pais').'</td>';
      $l_html.=chr(13).'          <td align="center">'.nvl(FormataDataEdicao(f($row,'quitacao')),'&nbsp;').'</td>';
      $l_html.=chr(13).'          <td align="right">'.formatNumber(f($row,'brl_valor_compra')).' </td>';
      if (f($row,'exige_brl')=='N') {
        $l_html.=chr(13).'          <td align="right">'.formatNumber(f($row,'fator_conversao'),4).' </td>';
      } else {
        $l_html.=chr(13).'          <td align="right"'.$w_cor_cell.'>'.nvl(formatNumber(f($row,'brl_taxa_compra'),4),'???').' </td>';
      }
      
      $l_html.=chr(13).'          <td align="center">'.f($row,'nm_banco').'</td>';
      $l_html.=chr(13).'          <td align="right">'.formatNumber(f($row,'valor')).' </td>';
      $l_html.=chr(13).'          <td align="right">'.formatNumber(f($row,'valor')).' </td>';
      $l_html.=chr(13).'          <td align="right">0,00 </td>';
      $l_html.=chr(13).'      </tr>';
      $w_lin_brl    += f($row,'brl_valor_compra');
      $w_lin_usd    += f($row,'valor');
      $w_lin_bid    += f($row,'valor');
      $w_lin_out    = 0;
      $w_filho_brl  += f($row,'brl_valor_compra');
      $w_filho_usd  += f($row,'valor');
      $w_filho_bid  += f($row,'valor');
      $w_filho_out  = 0;
      $w_pai_brl  += f($row,'brl_valor_compra');
      $w_pai_usd  += f($row,'valor');
      $w_pai_bid  += f($row,'valor');
      $w_pai_out  = 0;
      $w_tot_brl  += f($row,'brl_valor_compra');
      $w_tot_usd  += f($row,'valor');
      $w_tot_bid  += f($row,'valor');
      $w_tot_out  = 0;
      $linha++;
    }
  }
  if ($w_filho>0) {
    $l_html.=chr(13).'          <tr valign="top">';
    $l_html.=chr(13).'            <td colspan="2" rowspan="6">&nbsp;</td>';
    $l_html.=chr(13).'            <td colspan="4">&nbsp;</td>';
    $l_html.=chr(13).'            <td>&nbsp;</td>';
    $l_html.=chr(13).'            <td>&nbsp;</td>';
    $l_html.=chr(13).'            <td colspan="2">&nbsp;</td>';
    $l_html.=chr(13).'            <td>&nbsp;</td>';
    $l_html.=chr(13).'          </tr>';
    $l_html.=chr(13).'          <tr valign="top">';
    $l_html.=chr(13).'            <td colspan="3" align="right"><b>Total desta página </b></td>';
    $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_lin_brl).' </b></td>';
    $l_html.=chr(13).'            <td>&nbsp;</td>';
    $l_html.=chr(13).'            <td>&nbsp;</td>';
    $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_lin_usd).' </b></td>';
    $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_lin_bid).' </b></td>';
    $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_lin_out).' </b></td>';
    $l_html.=chr(13).'          </tr>';
    $l_html.=chr(13).'          <tr valign="top">';
    $l_html.=chr(13).'            <td colspan="3" align="right"><b>Total das páginas anteriores</b></td>';
    $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_pag_brl).' </b></td>';
    $l_html.=chr(13).'            <td>&nbsp;</td>';
    $l_html.=chr(13).'            <td>&nbsp;</td>';
    $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_pag_usd).' </b></td>';
    $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_pag_bid).' </b></td>';
    $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_pag_out).' </b></td>';
    $l_html.=chr(13).'          </tr>';
    if ($w_pai!=$w_filho || 1==1) {
      $l_html.=chr(13).'          <tr valign="top">';
      $l_html.=chr(13).'            <td colspan="3" align="right"><b>Total da Subcategoria '.$w_filho.'</b></td>';
      $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_filho_brl).' </b></td>';
      $l_html.=chr(13).'            <td>&nbsp;</td>';
      $l_html.=chr(13).'            <td>&nbsp;</td>';
      $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_filho_usd).' </b></td>';
      $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_filho_bid).' </b></td>';
      $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_filho_out).' </b></td>';
      $l_html.=chr(13).'          </tr>';
    }
    $l_html.=chr(13).'          <tr valign="top">';
    $l_html.=chr(13).'            <td colspan="3" align="right"><b>Total da Categoria '.$w_pai.'</b></td>';
    $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_pai_brl).' </b></td>';
    $l_html.=chr(13).'            <td>&nbsp;</td>';
    $l_html.=chr(13).'            <td>&nbsp;</td>';
    $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_pai_usd).' </b></td>';
    $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_pai_bid).' </b></td>';
    $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_pai_out).' </b></td>';
    $l_html.=chr(13).'          </tr>';
    $l_html.=chr(13).'          <tr valign="top">';
    $l_html.=chr(13).'            <td colspan="3" align="right"><b>Total geral</b></td>';
    $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_tot_brl).' </b></td>';
    $l_html.=chr(13).'            <td>&nbsp;</td>';
    $l_html.=chr(13).'            <td>&nbsp;</td>';
    $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_tot_usd).' </b></td>';
    $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_tot_bid).' </b></td>';
    $l_html.=chr(13).'            <td align="right"><b>'.formatNumber($w_tot_out).' </b></td>';
    $l_html.=chr(13).'          </tr>';
    $l_html.=chr(13).'        </table></td></tr>';
    $l_html.=chr(13).'        </table>';

    $l_html.=chr(13).'      <tr><td align="center" colspan="11" align="center"><table border=0 width=40%>';
    $l_html.=chr(13).'        <tr style="height: 25"><td>&nbsp;</td></tr>';
    $l_html.=chr(13).'        <tr><td><font size="2"><b>Brasília, '.$p_emissao.'.</b></font></td></tr>';
    $l_html.=chr(13).'        <tr style="height: 25"><td>&nbsp;</td></tr>';
    $l_html.=chr(13).'        <tr><td align="center">_____________________________</td></tr>';
    $l_html.=chr(13).'        <tr><td align="center"><font size="2"><b>'.$p_nome.'</b></font></td></tr>';
    $l_html.=chr(13).'        <tr><td align="center"><font size="2"><b>'.$p_cargo.'</b></font></td></tr>';
    $l_html.=chr(13).'        <tr style="height: 25"><td>&nbsp;</td></tr>';
    $l_html.=chr(13).'        </table>';
    $l_html.=chr(13).'      </tr>';
  }

  ShowHTML($l_html);
  ShowHTML('    </table>');
  ShowHTML('  </td>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</div>');

  if($w_tipo=='PDF') RodapePdf();
  else               Rodape();
}

// =========================================================================
// Relatório de detalhamento das despesas.
// -------------------------------------------------------------------------
function fontePeriodo() {
  extract($GLOBALS);
  $w_tipo = $_REQUEST['w_tipo'];
  
  // Recupera os dados do projeto selecionado
  $sql = new db_getSolicData; $RS_Projeto = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');

  // Recupera as rubricas do projeto
  $sql = new db_getSolicRubrica; $RSQuery = $sql->getInstanceOf($dbms,$p_projeto,null,'S',null,null,(($p_financeiro=='N') ? null : 'N'),$p_inicio,$p_fim,'PJEXECLS');

  if ($p_moedas=='S') {
    foreach($RSQuery as $row)  {
      if (f($row,'sg_fn_moeda')!='0') { $Moeda[f($row,'sg_fn_moeda')]='1';  $Total[f($row,'sg_fn_moeda')] = 0; }
      if (nvl(f($row,'fn_sg_moeda'),'')!='')  { $Moeda[f($row,'fn_sg_moeda')]='1'; $Total[f($row,'fn_sg_moeda')] = 0; }
      // Se o relatório tem três moedas diferentes, aborta pois esse é o número atual de moedas ativas
      if (count($Moeda)==3) break;
    }
    // Decide a ordem de exibição das moedas no relatório
    $i = 0;
    switch (f($RS_Projeto,'sg_moeda')) {
      case 'USD': $Moeda['USD']='1'; $Ordem[$i]='USD';
                  if (nvl($Moeda['BRL'],'')!='') $Ordem[++$i]='BRL';
                  if (nvl($Moeda['EUR'],'')!='') $Ordem[++$i]='EUR';
                  break;
      case 'BRL': $Moeda['BRL']='1'; $Ordem[$i]='BRL';
                  if (nvl($Moeda['USD'],'')!='') $Ordem[++$i]='USD';
                  if (nvl($Moeda['EUR'],'')!='') $Ordem[++$i]='EUR';
                  break;
      case 'EUR': $Moeda['EUR']='1'; $Ordem[$i]='EUR';
                  if (nvl($Moeda['BRL'],'')!='') $Ordem[++$i]='BRL';
                  if (nvl($Moeda['USD'],'')!='') $Ordem[++$i]='USD';
                  break;
    }
  }

  if ($p_ordena>'') { 
    $lista = explode(',',str_replace(' ',',',$p_ordena));
    $RSQuery = SortArray($RSQuery,$lista[0],$lista[1],'or_rubrica','asc','quitacao','asc','or_financeiro','asc','or_item','asc');
  } else {
    $RSQuery = SortArray($RSQuery,'or_rubrica','asc','quitacao','asc','or_financeiro','asc','or_item','asc');
  }

  $w_embed        = '';
  headerGeral('P', $w_tipo, $w_chave, $conSgSistema.' - Detalhamento de Despesas', $w_embed, null, null, $w_linha_pag,$w_filtro);
  if ($w_embed!='WORD') {
    Cabecalho();
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Detalhamento de Despesas</TITLE>');
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    ShowHTML('</HEAD>');
    BodyOpenClean('onLoad="this.focus()";');
    CabecalhoRelatorio($w_cliente, f($RS_Menu,'nome'), 4, $w_chave);
    ShowHTML('<HR>');
  }
  ShowHTML('<div align="center"><table border="0" cellpadding="0" cellspacing="0" width="100%">');

  $l_html = '';

  $l_html.=chr(13).'      <tr><td align="center" colspan="2">';
  $l_html.=chr(13).'        <table class="tudo" width=99%  border="1" bordercolor="#00000">';
  $l_html.=chr(13).'          <tr align="center">';
  $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Nº do DOC</td>';
  $cs=0;
  $cs++; $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Rubrica</td>';
  $cs++; $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Fornecedor</td>';
  $cs++; $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Nº Fatura</td>';
  $cs++; $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Dt Pgto</td>';
  $cs++; $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Descrição da despesa'.'</td>';
  $cs++; $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Parceiro '.f($RS_Projeto,'sg_moeda').'</td>';
  $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Local '.f($RS_Projeto,'sg_moeda').'</td>';
  if ($p_moedas=='S') foreach($Ordem as $k=>$v) if ($k>0) $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Valor '.$v.'</td>';
  $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Total '.f($RS_Projeto,'sg_moeda').'</td>';
  $l_html.=chr(13).'          </tr>';
  $w_cor=$conTrBgColor;
  $w_total_previsto  = 0;
  $w_total_contabil  = 0;
  $i = 0;
  foreach ($RSQuery as $row) {
    if (substr(f($row,'sg_menu'),0,3)!='FNR' || $p_receita=='S') {
      $w_valor_contabil = 0;
      if ($p_moedas=='S') {
        foreach($Ordem as $k => $v) {
          if ($v==f($row,'fn_sg_moeda')) {
            $Valor[$v] = f($row,'fn_valor'); 
            $Total[$v]+=$Valor[f($row,'fn_sg_moeda')];
          } else {
            unset($Valor[$v]);
          }
        }
      }
      $i++;
      $l_html.=chr(13).'      <tr valign="top">';
      $l_html.=chr(13).'          <td nowrap align="center">'.exibeSolic($w_dir,f($row,'sq_financeiro'),f($row,'codigo_externo'),'N',$w_tipo);
      $l_html.=chr(13).'          <td align="center">'.f($row,'cd_rubrica').' </td>';
      $l_html.=chr(13).'          <td>'.f($row,'nm_pessoa').' </td>';
      $l_html.=chr(13).'          <td>'.f($row,'numero').' </td>';
      $l_html.=chr(13).'          <td align="right">'.nvl(FormataDataEdicao(f($row,'quitacao'),5),'&nbsp;').'</td>';
      $l_html.=chr(13).'          <td>'.f($row,'descricao').' </td>';
      $l_html.=chr(13).'          <td align="right" width="5%">'.formatNumber(f($row,'valor')).' </td>';
      $l_html.=chr(13).'          <td align="right" width="5%">&nbsp;</td>';
      if ($p_moedas=='S') foreach($Ordem as $k => $v) if ($k>0) $l_html.=chr(13).'          <td align="right">'.formatNumber($Valor[$v]).'</td>';
      $l_html.=chr(13).'          <td align="right" width="5%">'.formatNumber(f($row,'valor')).' </td>';
      $l_html.=chr(13).'      </tr>';
      $w_total_previsto += f($row,'valor');
      $w_total_contabil += f($row,'brl_valor_compra');
    }
  }
  /*
  $l_html.=chr(13).'      <tr valign="top">';
  $l_html.=chr(13).'        <td colspan="'.$cs.'" align="right"><b>Total: </b></td>';
  $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total_previsto).' </b></td>';
  if ($p_moedas=='S') foreach($Ordem as $k => $v) if ($k>0) $l_html.=chr(13).'          <td align="right"><b>'.formatNumber($Total[$v]).'</b></td>';
  if ($p_contabil=='S') $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total_contabil).' </b></td><td colspan="2">&nbsp;</td>';
  $l_html.=chr(13).'      </tr>';
   */
  $l_html.=chr(13).'      </table></td></tr>';

  ShowHTML($l_html);
  ShowHTML('    </table>');
  ShowHTML('  </td>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</div>');

  if($w_tipo=='PDF') RodapePdf();
  else               Rodape();
}

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'INICIAL': Inicial(); break;
    case 'DETDESP': detalhamentoDespesa(); break;
    case 'DETCAT': detalhamentoRubrica(); break;
    case 'FONPER':  fontePeriodo(); break;
    default:
      cabecalho();
      ShowHTML('<BASE HREF="' . $conRootSIW . '">');
      BodyOpen('onLoad=this.focus();');
      Estrutura_Topo_Limpo();
      Estrutura_Menu();
      Estrutura_Corpo_Abre();
      Estrutura_Texto_Abre();
      ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
      Estrutura_Texto_Fecha();
      Estrutura_Fecha();
      Estrutura_Fecha();
      Estrutura_Fecha();
      Rodape();
  }
}
?>
