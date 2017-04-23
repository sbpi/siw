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
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoOrdenaRel.php');

// =========================================================================
//  /rel_despesa.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Relatório de detalhamento de despesas de um projeto
// Mail     : alex@sbpi.com.br
// Criacao  : 06/04/2014 09:43
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
$w_pagina = 'rel_despesa.php?par=';
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
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</HEAD>');
  BodyOpen('onLoad="document.focus()";');
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align="center"><table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($SG=='FNRFONPER') $rel = 'fonPer';
  else $rel = 'detDesp';
  AbreForm('Form', $w_dir.$w_pagina.$rel, 'POST', 'return(Validacao(this));', 'Despesas', $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td>');
  ShowHTML('    <table width="99%" border="0">');
  ShowHTML('      <tr>');
  $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,'PJCAD');
  SelecaoProjeto('Pro<u>j</u>eto:','J','Selecione o projeto do contrato na relação.',$p_projeto,$w_usuario,f($RS,'sq_menu'),null,null,null,'p_projeto','PJLIST',$w_atributo);
  ShowHTML('      </tr>');
  ShowHTML('      <tr><td><b><u>P</u>agamento entre:</b><br><input ' . $w_Disabled . ' accesskey="P" type="text" name="p_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_inicio . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_inicio') . ' e <input ' . $w_Disabled . ' type="text" name="p_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_fim') . '</td>');
  ShowHTML('      <tr>');
  MontaRadioNS('<b>Exibe moeda do pagamento, além da moeda do projeto? <font color="red">("Sim" para exibir coluna com a moeda do pagamento. "Não" para omitir essa coluna)</font>.</b>',$p_moedas,'p_moedas');
  ShowHTML('      <tr>');
  MontaRadioNS('<b>Emite versão para contabilidade?</b>',$p_contabil,'p_contabil');
  ShowHTML('      </tr><tr>');
  MontaRadioNS('<b>Exibe também lançamentos de receita/devolução? </b>',$p_receita,'p_receita');
  ShowHTML('      </tr>');
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

  if ($w_tipo!='EXCEL') {
    $w_filtro = '';
    if ($p_inicio!='')     $w_filtro = $w_filtro . '<tr valign="top"><td align="right">Pagamento realizado de <td><b>' . $p_inicio . '</b> até <b>' . $p_fim . '</b>';
    if ($p_contabil=='S')  $w_filtro = $w_filtro . '<tr valign="top"><td align="right">Formato:<td><b>Versão para contabilidade</b>';
    //if ($p_sintetico=='S') $w_filtro = $w_filtro . '<tr valign="top"><td align="right"><b>Versão sintética (apenas rubricas de mais alto nível)</b>';
    ShowHTML('<tr><td align="left" colspan=2>');
    if ($w_filtro > '') ShowHTML('<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>' . $w_filtro . '</ul></tr></table>');

    $l_html .= chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="99%">';

    $l_html.=chr(13).'    <tr><td colspan="2"><table width="100%" border="0">';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    if (nvl(f($RS_Projeto,'sq_plano'),'')!='') {
      if ($w_embed=='WORD') $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0" align=justify><font size="2"><b>PLANO ESTRATÉGICO: '.upper(f($RS_Projeto,'nm_plano')).'</b></font></td></tr>';
      else                  $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0" align=justify><font size="2"><b>PLANO ESTRATÉGICO: '.ExibePlano('../',$w_cliente,f($RS_Projeto,'sq_plano'),$TP,upper(f($RS_Projeto,'nm_plano'))).'</b></font></td></tr>';
    }
    $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0" align=justify><font size="2"><b>PROJETO: '.f($RS_Projeto,'codigo_interno').' - '.f($RS_Projeto,'titulo').' ('.f($RS_Projeto,'sq_siw_solicitacao').')</b></font></td></tr>';

    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    $l_html .= chr(13).'    <tr><td colspan=2><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top" align="center">';
    if ($w_embed!='WORD') {
      $l_html .= chr(13).'        <td width="25%">'.VisualIndicador($w_dir_volta,$w_cliente,'IDE',$TP,'IDE').': '.ExibeSmile('IDE',$w_ide).' '.formatNumber(f($RS_Projeto,'ide'),2).'%</b></td>';
      $l_html .= chr(13).'        <td width="25%">'.VisualIndicador($w_dir_volta,$w_cliente,'IGE',$TP,'IGE').': '.ExibeSmile('IGE',$w_ige).' '.formatNumber(f($RS_Projeto,'ige'),2).'%</b></td>';
      $l_html .= chr(13).'        <td width="25%">'.VisualIndicador($w_dir_volta,$w_cliente,'IDC',$TP,'IDC').': '.ExibeSmile('IDC',$w_idc).' '.formatNumber(f($RS_Projeto,'idc'),2).'%</b></td>';
      $l_html .= chr(13).'        <td width="25%">'.VisualIndicador($w_dir_volta,$w_cliente,'IGC',$TP,'IGC').': '.ExibeSmile('IGC',$w_igc).' '.formatNumber(f($RS_Projeto,'igc'),2).'%</b></td>';
    } else {
      $l_html .= chr(13).'        <td width="25%">IDE: '.ExibeSmile('IDE',$w_ide).' '.formatNumber(f($RS_Projeto,'ide'),2).'%</b></td>';
      $l_html .= chr(13).'        <td width="25%">IGE: '.ExibeSmile('IGE',$w_ige).' '.formatNumber(f($RS_Projeto,'ige'),2).'%</b></td>';
      $l_html .= chr(13).'        <td width="25%">IDC: '.ExibeSmile('IDC',$w_idc).' '.formatNumber(f($RS_Projeto,'idc'),2).'%</b></td>';
      $l_html .= chr(13).'        <td width="25%">IGC: '.ExibeSmile('IGC',$w_igc).' '.formatNumber(f($RS_Projeto,'igc'),2).'%</b></td>';
    }
    $l_html .= chr(13).'      </table>';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=1></td></tr>';

    // Exibe a vinculação
    $l_html.=chr(13).'      <tr><td valign="top" width="30%"><b>Vinculação: </b></td>';
    if($w_embed!='WORD') $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS_Projeto,'sq_solic_pai'),f($RS_Projeto,'dados_pai'),'S').'</td></tr>';
    else                 $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS_Projeto,'sq_solic_pai'),f($RS_Projeto,'dados_pai'),'S','S').'</td></tr>';

    $l_html .= chr(13).'      <tr><td><b>Início previsto:</b></td>';
    $l_html .= chr(13).'        <td>'.FormataDataEdicao(f($RS_Projeto,'inicio')).' </td></tr>';
    $l_html .= chr(13).'      <tr><td><b>Término previsto:</b></td>';
    $l_html .= chr(13).'        <td>'.FormataDataEdicao(f($RS_Projeto,'fim')).' </td></tr>';
    $l_html .= chr(13).'      <tr><td><b>Moeda:</b></td>';
    $l_html .= chr(13).'        <td>'.FormataDataEdicao(f($RS_Projeto,'nm_moeda')).' </td></tr>';
    $l_html.=chr(13).'        <tr><td><b>Fase atual:</b></td>';
    $l_html.=chr(13).'          <td>'.Nvl(f($RS_Projeto,'nm_tramite'),'-').'</td></tr>';
    $l_html .= chr(13).'</table>';
    $l_html.=chr(13).'      <tr><td colspan=2><br><font size="2"><b>LANÇAMENTOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
  }
  $l_html.=chr(13).'      <tr><td align="center" colspan="2">';
  $l_html.=chr(13).'        <table class="tudo" width=99%  border="1" bordercolor="#00000">';
  $l_html.=chr(13).'          <tr align="center">';
  $cs=0;
  $cs++; $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>Número</td>';
  $cs++; $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>'.LinkOrdena('Descrição da despesa','descricao').'</td>';
  $cs++; $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>'.LinkOrdena('Produto ou serviço','nm_rubrica').'</td>';
  $cs++; $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>'.LinkOrdena('Categoria (Usos)','nm_rubrica_pai').'</td>';
  $cs++; $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>'.LinkOrdena('Item de Custo','cd_rubrica').'</td>';
  $l_html.=chr(13).'            <td colspan="2" bgColor="#f0f0f0"><b>Fornecedor</td>';
  $l_html.=chr(13).'            <td colspan="4" bgColor="#f0f0f0"><b>Comprovante de Pagamento</td>';
  $l_html.=chr(13).'            <td colspan="'.((count($Ordem)) ? (3+count($Ordem)) : 4).'" bgColor="#f0f0f0"><b>Pagamento</td>';
  if ($p_contabil=='S') $l_html.=chr(13).'            <td colspan="3" bgColor="#f0f0f0"><b>Contabilidade</td>';
  $l_html.=chr(13).'          </tr>';
  $l_html.=chr(13).'          <tr align="center" >';
  $cs++; $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>'.LinkOrdena('Nome','nm_pessoa').'</td>';
  $cs++; $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>'.LinkOrdena('CNPJ/CPF','cd_pessoa').'</td>';
  $cs++; $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>'.LinkOrdena('Tipo','nm_tipo_documento').'</td>';
  $cs++; $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>'.LinkOrdena('Número','numero').'</td>';
  $cs++; $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>'.LinkOrdena('Data de Emissão','dt_emissao').'</td>';
  $cs++; $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>'.LinkOrdena('Valor','valor_doc').'</td>';
  $cs++; $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>'.LinkOrdena('Forma','nm_forma_pagamento').'</td>';
  $cs++; $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>'.LinkOrdena('Número','or_financeiro').'</td>';
  $cs++; $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>'.LinkOrdena('Data','quitacao').'</td>';
  $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>'.LinkOrdena('Valor '.f($RS_Projeto,'sg_moeda'),'valor').'</td>';
  if ($p_moedas=='S') foreach($Ordem as $k=>$v) if ($k>0) $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Valor '.$v.'</td>';
  if ($p_contabil=='S') {
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Valor BRL</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Taxa</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Data Taxa</td>';
  }
  $l_html.=chr(13).'          </tr>';
  $w_cor=$conTrBgColor;
  $w_total_previsto  = 0;
  $w_total_contabil  = 0;
  $i = 0;
  foreach ($RSQuery as $row) {
    if (substr(f($row,'sg_menu'),0,3)!='FNR' || $p_receita=='S') {
      $w_valor_contabil = 0;
      
      $fn_valor = f($row,'fn_valor');
      if (strpos(f($row,'descricao'),'FCTS')!==false) $fn_valor = abs($fn_valor);
      
      $valor = f($row,'valor');
      if (strpos(f($row,'descricao'),'FCTS')!==false) $valor = abs($valor);
      
      if ($p_moedas=='S') {
        foreach($Ordem as $k => $v) {
          if ($v==f($row,'fn_sg_moeda')) {
            $Valor[$v] = $fn_valor; 
            $Total[$v]+=$Valor[f($row,'fn_sg_moeda')];
          } else {
            unset($Valor[$v]);
          }
        }
      }
      $i++;
      $l_html.=chr(13).'      <tr valign="top">';
      $l_html.=chr(13).'          <td align="center">'.$i.' </td>';
      $l_html.=chr(13).'          <td>'.f($row,'descricao').' </td>';
      $l_html.=chr(13).'          <td>'.f($row,'nm_rubrica').' </td>';
      $l_html.=chr(13).'          <td>'.nvl(f($row,'nm_rubrica_pai'),'&nbsp;').' </td>';
      $l_html.=chr(13).'          <td align="center">'.f($row,'cd_rubrica').' </td>';
      $l_html.=chr(13).'          <td>'.f($row,'nm_pessoa').' </td>';
      $l_html.=chr(13).'          <td nowrap align="center">'.f($row,'cd_pessoa').' </td>';
      $l_html.=chr(13).'          <td>'.f($row,'nm_tipo_documento').' </td>';
      $l_html.=chr(13).'          <td>'.f($row,'numero').' </td>';
      $l_html.=chr(13).'          <td align="right">'.  FormataDataEdicao(f($row,'dt_emissao'),5).' </td>';
      $l_html.=chr(13).'          <td align="right" nowrap>'.f($row,'sb_fn_moeda').' '.formatNumber(f($row,'valor_doc')).' </td>';
      $l_html.=chr(13).'          <td>'.f($row,'nm_forma_pagamento').' </td>';
      $l_html.=chr(13).'          <td nowrap>'.exibeSolic($w_dir,f($row,'sq_financeiro'),f($row,'cd_financeiro'),'N',$w_tipo);
      $l_html.=chr(13).'          <td align="right">'.nvl(FormataDataEdicao(f($row,'quitacao'),5),'&nbsp;').'</td>';
      $l_html.=chr(13).'          <td align="right">'.formatNumber(f($row,'valor')).' </td>';
      if ($p_moedas=='S') foreach($Ordem as $k => $v) if ($k>0) $l_html.=chr(13).'          <td align="right">'.formatNumber($Valor[$v]).'</td>';
      if ($p_contabil=='S') {
        $l_html.=chr(13).'          <td align="right">'.formatNumber(f($row,'brl_valor_compra')).' </td>';
        if (f($row,'exige_brl')=='N') {
          // Se já tem valor em BRL, não é necessário converter.
          // Apenas exibe a taxa de venda do BRL e compara com a informada na conclusão do lançamento.
          // Se for diferente, destaca na cor amarela.
          $w_cor_cell = '';
          if (nvl(f($row,'brl_taxa_venda'),0)==0) $w_cor_cell = ' bgcolor="'.$conTrBgColorLightYellow2.'"';
          elseif (abs(f($row,'brl_valor_compra')-(f($row,'brl_taxa_venda')*f($row,'valor')))>0.1 && (f($row,'brl_taxa_venda')!=f($row,'fator_conversao'))) $w_cor_cell = ' bgcolor="'.$conTrBgColorLightRed1.'"';
          $l_html.=chr(13).'          <td align="right"'.$w_cor_cell.'>'.nvl(formatNumber(f($row,'brl_taxa_venda'),4),'???').(($w_cor_cell) ? '<br>*'.f($row,'fator_conversao') : '').' </td>';
          $l_html.=chr(13).'          <td align="right"'.$w_cor_cell.'>'.nvl(formataDataEdicao(f($row,'brl_taxa_venda_data'),5),'???').' </td>';
        } else {
          $w_cor_cell = '';
          if (nvl(f($row,'brl_taxa_compra'),0)==0) $w_cor_cell = ' bgcolor="'.$conTrBgColorLightYellow2.'"';
          $l_html.=chr(13).'          <td align="right"'.$w_cor_cell.'>'.nvl(formatNumber(f($row,'brl_taxa_compra'),4),'???').' </td>';
          $l_html.=chr(13).'          <td align="right"'.$w_cor_cell.'>'.nvl(formataDataEdicao(f($row,'brl_taxa_compra_data'),5),'???').' </td>';
        }
      }
      $l_html.=chr(13).'      </tr>';
      $w_total_previsto += $valor;
      $w_total_contabil += f($row,'brl_valor_compra');
    }
  } 
  $l_html.=chr(13).'      <tr valign="top">';
  $l_html.=chr(13).'        <td colspan="'.$cs.'" align="right"><b>Total: </b></td>';
  $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total_previsto).' </b></td>';
  if ($p_moedas=='S') foreach($Ordem as $k => $v) if ($k>0) $l_html.=chr(13).'          <td align="right"><b>'.formatNumber($Total[$v]).'</b></td>';
  if ($p_contabil=='S') $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total_contabil).' </b></td><td colspan="2">&nbsp;</td>';
  $l_html.=chr(13).'      </tr>';
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
