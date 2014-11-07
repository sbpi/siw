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
include_once($w_dir_volta.'classes/sp/db_getSolicFN.php');
include_once($w_dir_volta.'classes/sp/db_getSolicRubrica.php');
include_once($w_dir_volta.'classes/sp/db_getBenef.php');
include_once($w_dir_volta.'classes/sp/db_getIndicador.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoOrdenaRel.php');

// =========================================================================
//  /rel_despesa.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papad�polis
// Descricao: Relat�rio de detalhamento de despesas de um projeto
// Mail     : alex@sbpi.com.br
// Criacao  : 06/04/2014 09:43
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
if ($_SESSION['LOGON'] != 'Sim') { EncerraSessao(); }

// Carrega vari�veis locais com os dados dos par�metros recebidos
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
$p_sintetico = upper(trim($_REQUEST['p_sintetico']));
$p_financeiro = upper(trim($_REQUEST['p_financeiro']));
$p_ordena = lower($_REQUEST['p_ordena']);

// Declara��o de vari�veis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if ($O == '') {
  if ($par == 'INICIAL') {
    $O = 'P';
  } else {
    $O = 'L';
  }
}
switch ($O) {
  case 'P': $w_TP = $TP . ' - Filtragem'; break;
  default: $w_TP = $TP . ' - Listagem';   break;
}
// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente = RetornaCliente();
$w_usuario = RetornaUsuario();
$w_menu = RetornaMenu($w_cliente, $SG);

// Recupera a configura��o do servi�o
$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);

Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Relat�rio de detalhamento  das despesas de projeto.
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;
  global $w_embed;
  $w_tipo = $_REQUEST['w_tipo'];
  $w_sq_pessoa = upper(trim($_REQUEST['w_sq_pessoa']));

  if ($O == 'L') {
    // Recupera os dados do projeto selecionado
    $sql = new db_getSolicData; $RS_Projeto = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');
    
    // Recupera as rubricas do projeto
    $sql = new db_getSolicRubrica; $RSQuery = $sql->getInstanceOf($dbms,$p_projeto,null,'S',null,null,(($p_financeiro=='N') ? null : 'N'),$p_inicio,$p_fim,'PJEXECLS');
    if ($p_ordena>'') { 
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RSQuery = SortArray($RSQuery,$lista[0],$lista[1],'or_rubrica','asc','or_financeiro','asc');
    } else {
      $RSQuery = SortArray($RSQuery,'or_rubrica','asc','or_financeiro','asc');
    }

  }

  headerGeral('P', $w_tipo, $w_chave, 'Consulta de '.f($RS_Menu,'nome'), $w_embed, null, null, $w_linha_pag,$w_filtro);
  if ($w_embed!='WORD') {
    Cabecalho();
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Relat�rio</TITLE>');
    if ($O == 'P') {
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
    }
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    ShowHTML('</HEAD>');
    if ($O == 'L') {
      BodyOpenClean('onLoad="this.focus()";');
      CabecalhoRelatorio($w_cliente, f($RS_Menu,'nome'), 4, $w_chave);
    } else {
      BodyOpen('onLoad="document.focus()";');
      ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</font></B>');
    }
    ShowHTML('<HR>');
  }
  ShowHTML('<div align="center"><table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    $w_filtro = '';
    if ($p_inicio!='')     $w_filtro = $w_filtro . '<tr valign="top"><td align="right">Pagamento realizado de <td><b>' . $p_inicio . '</b> at� <b>' . $p_fim . '</b>';
    //if ($p_financeiro=='S') $w_filtro = $w_filtro . '<tr valign="top"><td align="right"><b>Rubricas de aplica��o financeira omitidas</b>';
    //if ($p_sintetico=='S') $w_filtro = $w_filtro . '<tr valign="top"><td align="right"><b>Vers�o sint�tica (apenas rubricas de mais alto n�vel)</b>';
    ShowHTML('<tr><td align="left" colspan=2>');
    if ($w_filtro > '') ShowHTML('<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>' . $w_filtro . '</ul></tr></table>');

    $l_html = '';

    $l_html .= chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="99%">';
    
    $l_html.=chr(13).'    <tr><td colspan="2"><table width="100%" border="0">';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    if (nvl(f($RS_Projeto,'sq_plano'),'')!='') {
      if ($w_embed=='WORD') $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0" align=justify><font size="2"><b>PLANO ESTRAT�GICO: '.upper(f($RS_Projeto,'nm_plano')).'</b></font></td></tr>';
      else                  $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0" align=justify><font size="2"><b>PLANO ESTRAT�GICO: '.ExibePlano('../',$w_cliente,f($RS_Projeto,'sq_plano'),$TP,upper(f($RS_Projeto,'nm_plano'))).'</b></font></td></tr>';
    }
    $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0" align=justify><font size="2"><b>PROJETO: '.f($RS_Projeto,'codigo_interno').' - '.f($RS_Projeto,'titulo').' ('.f($RS_Projeto,'sq_siw_solicitacao').')</b></font></td></tr>';
    if ($w_tipo!='EXCEL') {
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
     
      // Exibe a vincula��o
      $l_html.=chr(13).'      <tr><td valign="top" width="30%"><b>Vincula��o: </b></td>';
      if($w_embed!='WORD') $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS_Projeto,'sq_solic_pai'),f($RS_Projeto,'dados_pai'),'S').'</td></tr>';
      else                 $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS_Projeto,'sq_solic_pai'),f($RS_Projeto,'dados_pai'),'S','S').'</td></tr>';

      $l_html .= chr(13).'      <tr><td><b>In�cio previsto:</b></td>';
      $l_html .= chr(13).'        <td>'.FormataDataEdicao(f($RS_Projeto,'inicio')).' </td></tr>';
      $l_html .= chr(13).'      <tr><td><b>T�rmino previsto:</b></td>';
      $l_html .= chr(13).'        <td>'.FormataDataEdicao(f($RS_Projeto,'fim')).' </td></tr>';
      $l_html.=chr(13).'        <tr><td><b>Fase atual:</b></td>';
      $l_html.=chr(13).'          <td>'.Nvl(f($RS_Projeto,'nm_tramite'),'-').'</td></tr>';
    }
    $l_html .= chr(13).'</table>';
    $l_html.=chr(13).'      <tr><td colspan=2><br><font size="2"><b>LAN�AMENTOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'      <tr><td align="center" colspan="2">';
    $l_html.=chr(13).'        <table class="tudo" width=99%  border="1" bordercolor="#00000">';
    $l_html.=chr(13).'          <tr align="center">';
    $cs++; $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>N�mero</td>';
    $cs++; $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>Descri��o da despesa</td>';
    $cs++; $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>Produto ou servi�o</td>';
    $cs++; $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>Categoria (Usos)</td>';
    $cs++; $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>Item de Custo</td>';
    $cs++; $l_html.=chr(13).'            <td colspan="4" bgColor="#f0f0f0"><b>Comprovante de Pagamento</td>';
    $cs++; $l_html.=chr(13).'            <td colspan="4" bgColor="#f0f0f0"><b>Pagamento ('.f($RS_Projeto,'sb_moeda').')</td>';
    $cs++; $l_html.=chr(13).'            <td colspan="2" bgColor="#f0f0f0"><b>Fornecedor</td>';
    $l_html.=chr(13).'          </tr>';
    $l_html.=chr(13).'          <tr align="center" >';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Tipo</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>N�mero</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Valor</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Data de Emiss�o</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Forma</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>N�mero</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Valor</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Data</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Nome</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>CNPJ/CPF</td>';
    $l_html.=chr(13).'          </tr>';
    $w_cor=$conTrBgColor;
    $w_total_previsto  = 0;
    $i = 0;
    foreach ($RSQuery as $row) {
      $i++;
      $l_html.=chr(13).'      <tr valign="top"'.$w_folha.'>';
      $l_html.=chr(13).'          <td align="center">'.$i.' </td>';
      $l_html.=chr(13).'          <td>'.f($row,'descricao').' </td>';
      $l_html.=chr(13).'          <td>'.f($row,'nm_rubrica').' </td>';
      $l_html.=chr(13).'          <td>'.f($row,'nm_rubrica_pai').' </td>';
      $l_html.=chr(13).'          <td align="center">'.f($row,'cd_rubrica').' </td>';
      $l_html.=chr(13).'          <td>'.f($row,'nm_tipo_documento').' </td>';
      $l_html.=chr(13).'          <td>'.f($row,'numero').' </td>';
      $l_html.=chr(13).'          <td align="right" nowrap>'.f($row,'sb_fn_moeda').' '.formatNumber(f($row,'valor_doc')).' </td>';
      $l_html.=chr(13).'          <td align="right">'.  FormataDataEdicao(f($row,'dt_emissao'),5).' </td>';
      $l_html.=chr(13).'          <td>'.f($row,'nm_forma_pagamento').' </td>';
      $l_html.=chr(13).'          <td nowrap>'.exibeSolic($w_dir,f($row,'sq_financeiro'),f($row,'cd_financeiro'),'N',$w_tipo);
      $l_html.=chr(13).'          <td align="right">'.formatNumber(f($row,'valor')).' </td>';
      $l_html.=chr(13).'          <td align="right">'.nvl(FormataDataEdicao(f($row,'quitacao'),5),'&nbsp;').'</td>';
      $l_html.=chr(13).'          <td>'.f($row,'nm_pessoa').' </td>';
      $l_html.=chr(13).'          <td nowrap align="center">'.f($row,'cd_pessoa').' </td>';
      $l_html.=chr(13).'      </tr>';
      $w_total_previsto += f($row,'valor');
    } 
    $l_html.=chr(13).'      <tr valign="top"'.$w_folha.'><td colspan=11 align="right"><b>Total: </b></td><td align="right"><b>'.formatNumber($w_total_previsto).' </b></td><td colspan=3>&nbsp;</td>';
    $l_html.=chr(13).'        </table></td></tr>';

    ShowHTML($l_html);
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif ($O == 'P') {
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', 'Contas', $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td>');
    ShowHTML('    <table width="99%" border="0">');
    ShowHTML('      <tr>');
    $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,'PJCAD');
    SelecaoProjeto('Pro<u>j</u>eto:','J','Selecione o projeto do contrato na rela��o.',$p_projeto,$w_usuario,f($RS,'sq_menu'),null,null,null,'p_projeto','PJLIST',$w_atributo);
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td><b><u>P</u>agamento entre:</b><br><input ' . $w_Disabled . ' accesskey="P" type="text" name="p_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_inicio . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_inicio') . ' e <input ' . $w_Disabled . ' type="text" name="p_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_fim') . '</td>');
    //ShowHTML('      <tr>');
    //MontaRadioNS('<b>Omite rubricas de aplica��o financeira?</b>',$p_financeiro,'p_financeiro');
    //ShowHTML('      </tr><tr>');
    //MontaRadioNS('<b>Exibe apenas a vers�o sint�tica do relat�rio? (apenas rubricas de mais alto n�vel)</b>',$p_sintetico,'p_sintetico');
    //ShowHTML('      </tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $w_pagina . $par . '&R=' . $R . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&O=P&SG=' . $SG) . '\';" name="Botao" value="Limpar campos">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
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
    default:
      cabecalho();
      ShowHTML('<BASE HREF="' . $conRootSIW . '">');
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
