<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getCountryData.php');
include_once($w_dir_volta.'classes/sp/db_getRegionData.php');
include_once($w_dir_volta.'classes/sp/db_getStateData.php');
include_once($w_dir_volta.'classes/sp/db_getCityData.php');
include_once($w_dir_volta.'classes/sp/db_getAlmoxarifado.php');
include_once($w_dir_volta.'classes/sp/db_getSolicMT.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicEtapa.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'funcoes/selecaoAlmoxarifado.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoEtapa.php');
include_once($w_dir_volta.'funcoes/selecaoTipoMatServSubord.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
include_once($w_dir_volta.'funcoes/selecaoClasseCheck.php');
include_once($w_dir_volta.'funcoes/selecaoLCModalidade.php');
include_once($w_dir_volta.'funcoes/selecaoTipoReajuste.php');
include_once($w_dir_volta.'funcoes/selecaoIndicador.php');
include_once($w_dir_volta.'funcoes/selecaoLCFonteRecurso.php');
include_once($w_dir_volta.'funcoes/selecaoCTEspecificacao.php');
include_once($w_dir_volta.'funcoes/selecaoLCJulgamento.php');
include_once($w_dir_volta.'funcoes/selecaoLCSituacao.php');
include_once($w_dir_volta.'funcoes/FusionCharts.php'); 
include_once($w_dir_volta.'funcoes/FC_Colors.php');
/**/
// =========================================================================
//  relatorio.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Relatórios de almoxarifado
// Mail     : alex@sbpi.com.br
// Criação  : 01/04/2011 10:00
// Versao   : 1.0.0.0
// Local    : Brasília - DF
// -------------------------------------------------------------------------
// 
// Parâmetros recebidos:
//    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
//    O (operação)   = L   : Listagem
//                   = P   : Filtragem
//                   = V   : Geração de gráfico
//                   = W   : Geração de documento no formato MS-Word (Office 2003)

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
$w_pagina       = 'relatorio.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_mt/';
$w_troca        = $_REQUEST['w_troca'];

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if ($O=='') $O='P';

switch ($O) {
  case 'P': $w_TP = $TP . ' - Filtragem';   break;
  case 'V': $w_TP = $TP . ' - Gráfico';     break;
  default:  $w_TP = $TP . ' - Listagem';    break;
} 

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = $P2;
$w_ano      = RetornaAno();

$p_tipo         = upper($_REQUEST['w_tipo']);
$p_projeto      = upper($_REQUEST['p_projeto']);
$p_atividade    = upper($_REQUEST['p_atividade']);
$p_graf         = upper($_REQUEST['p_graf']);
$p_ativo        = upper($_REQUEST['p_ativo']);
$p_solicitante  = upper($_REQUEST['p_solicitante']);
$p_prioridade   = upper($_REQUEST['p_prioridade']);
$p_unidade      = upper($_REQUEST['p_unidade']);
$p_proponente   = upper($_REQUEST['p_proponente']);
$p_usu_resp     = upper($_REQUEST['p_usu_resp']);
$p_ordena       = lower($_REQUEST['p_ordena']);
$p_ini_i        = upper($_REQUEST['p_ini_i']);
$p_ini_f        = upper($_REQUEST['p_ini_f']);
$p_fim_i        = upper($_REQUEST['p_fim_i']);
$p_fim_f        = upper($_REQUEST['p_fim_f']);
$p_atraso       = upper($_REQUEST['p_atraso']);
$p_acao_ppa     = upper($_REQUEST['p_acao_ppa']);
$p_empenho      = upper($_REQUEST['p_empenho']);
$p_chave        = upper($_REQUEST['p_chave']);
$p_assunto      = upper($_REQUEST['p_assunto']);
$p_pais         = upper($_REQUEST['p_pais']);
$p_regiao       = upper($_REQUEST['p_regiao']);
$p_uf           = upper($_REQUEST['p_uf']);
$p_cidade       = upper($_REQUEST['p_cidade']);
$p_uorg_resp    = upper($_REQUEST['p_uorg_resp']);
$p_palavra      = upper($_REQUEST['p_palavra']);
$p_prazo        = upper($_REQUEST['p_prazo']);
$p_fase         = explodeArray($_REQUEST['p_fase']);
$p_sqcc         = upper($_REQUEST['p_sqcc']);
$p_agrega       = upper($_REQUEST['p_agrega']);
$p_tamanho      = upper($_REQUEST['p_tamanho']);

// Verifica se o cliente tem o módulo de protocolo e arquivo
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PA');
if (count($RS)>0) $w_pa='S'; else $w_pa='N'; 

// Recupera a configuração do serviço
$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Inventário de estoque
// -------------------------------------------------------------------------
function Inventario() {
  extract($GLOBALS);
  
  $w_pag   = 1;
  $w_linha = 0;
  
  if ($O=='L' || $O=='V' || $p_tipo == 'WORD' || $p_tipo=='PDF') {
    $w_filtro='';
    if ($p_chave>'') {
      $w_linha++;
      $sql = new db_getAlmoxarifado; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_chave,null,null,null,null,'OUTROS');
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Almoxarifado<td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_pais>'') {
      $w_linha++;
      $sql = new db_getTipoMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_pais,null,null,null,null,null,null,'REGISTROS');
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Tipo de material/serviço<td>[<b>'.f($RS,'nome_completo').'</b>]';
    } 
    if ($p_proponente>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Material<td>[<b>'.$p_proponente.'</b>]'; }
    /*
    if ($p_projeto>'') {
      $w_linha++;
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');
      $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS,'sigla').'" title="Exibe as informações do projeto.">'.f($RS,'titulo').'</a></b>]';
    } 
    if ($p_atividade>'') {
      $w_linha++;
      $sql = new db_getSolicEtapa; $RS = $sql->getInstanceOf($dbms,$p_projeto,$p_atividade,'REGISTRO',null);
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Etapa <td>[<b>'.f($RS,'titulo').'</b>]';
    } 
    if ($p_empenho>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Código <td>[<b>'.$p_empenho.'</b>]'; }
    if ($p_assunto>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Descrição <td>[<b>'.$p_assunto.'</b>]'; }
    if ($p_solicitante>'') {
      $w_linha++;
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
      $w_filtro .= '<tr valign="top"><td align="right">Responsável <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_uf>'') {
      $w_linha++;
      $sql = new db_getLCSituacao; $RS = $sql->getInstanceOf($dbms, $p_uf, $w_cliente, null, null, null, null, null, null);
      foreach ($RS as $row) {
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Situação do certame <td>[<b>'.f($row,'nome').'</b>]';
        break;
      }
    } 
    if ($p_unidade>'') {
      $w_linha++;
      $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_unidade);
      $w_filtro .= '<tr valign="top"><td align="right">Unidade solicitante <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_palavra>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Número do certame <td>[<b>'.$p_palavra.'</b>]'; }
    if ($p_regiao>'' || $p_cidade>'') {
      $w_linha++;
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Protocolo <td>[<b>'.(($p_regiao>'') ? str_pad($p_regiao,6,'0',PAD_RIGHT) : '*').'/'.(($p_cidade>'') ? $p_cidade : '*').'</b>]';
    } 
    */
    if ($p_ativo=='S') {
      $w_linha++;
      $w_filtro .= '<tr valign="top"><td align="right">Restrição<td>[<b>Apenas materiais disponíveis para pedidos internos</b>]';
    } 
    /*
    if ($p_ini_i>'')      $w_filtro.='<tr valign="top"><td align="right">Abertura de propostas <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
    if ($p_fim_i>'')  { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Autorização <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]'; }
    */
    if ($w_filtro>'') { $w_linha++; $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>'; }

    $sql = new db_getSolicMT; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,'ALINV',3,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,null,$p_ativo,$p_proponente,
        $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
        $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade,
        $p_acao_ppa, null, $p_empenho, null);
    $RS1 = SortArray($RS1,'nm_tipo_completo','asc','nm_material','asc');
  }
  $w_linha_filtro = $w_linha;

  if ($p_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 45: 30);
    CabecalhoWord($w_cliente,substr($TP,strrpos($TP,'-')+1),$w_pag);
    $w_embed = 'WORD';
    if ($w_filtro>'') ShowHTML($w_filtro);
  } elseif($p_tipo == 'PDF'){
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 60: 35);
    $w_embed = 'WORD';
    HeaderPdf(substr($TP,strrpos($TP,'-')+1),$w_pag);
    if ($w_filtro>'') ShowHTML($w_filtro);
  } elseif ($p_tipo=='EXCEL') {
    $w_embed = 'WORD';
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 60: 35);
    HeaderExcel($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,substr($TP,strrpos($TP,'-')+1),$w_pag);
    if ($w_filtro>'') ShowHTML($w_filtro);
  } else {
    $w_embed = 'HTML';
    Cabecalho();
    head();
    if ($O=='P') {
      ScriptOpen('Javascript');
      CheckBranco();
      FormataData();
      SaltaCampo();
      ValidateOpen('Validacao');
      Validate('p_chave','Almoxarifado','SELECT','1','1','18','','1');
      Validate('p_proponente','Material','','','2','60','1','');
      ValidateClose();
      ScriptClose();
    } else {
      ShowHTML('<TITLE>'.$w_TP.'</TITLE>');
    } 
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</HEAD>');
    if ($w_Troca>'') {
      // Se for recarga da página
      BodyOpen('onLoad=\'document.Form.'.$w_Troca.'.focus();\'');
    } else {
      BodyOpenClean('onLoad=this.focus();');
    } 

    if ($O=='L') {
      CabecalhoRelatorio($w_cliente,substr($TP,strrpos($TP,'-')+1),4);
      if ($w_filtro>'') ShowHTML($w_filtro);
    } else {
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
      ShowHTML('<HR>');
    } 
  } 

  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L' || $w_embed == 'WORD') {
    if ((strpos(upper($R),'GR_'))===false && $P1!=6 && $w_embed!='WORD') {
      ShowHTML('<tr><td><a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">'.((strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) ? '<font color="#BC5100"><u>F</u>iltrar (Ativo)</font>' : '<u>F</u>iltrar (Inativo)').'</a>');
    }
    $i    = 0;
    $tipo = false;
    if (count($RS1)==0) {
      ShowHTML('<tr><td align="center"><hr/><b>Não foram encontrados registros</b></td></tr>');
    } else {
      foreach ($RS1 as $row) {
        if ($i==0) {
          if (nvl($p_pais,0)!=f($row,'sq_tipo_material')) $tipo = true;
          ShowHTML('<tr><td align="center">');
          ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
          ShowHTML('        <tr bgcolor="#DCDCDC" align="center">');
          if ($tipo) ShowHTML('          <td><b>Tipo de material</b></td>');
          ShowHTML('          <td><b>End. Estoque</b></td>');
          ShowHTML('          <td><b>Material</b></td>');
          ShowHTML('          <td><b>U.M.</b></td>');
          ShowHTML('          <td><b>Qtd.</b></td>');
          ShowHTML('          <td><b>Preço médio</b></td>');
          ShowHTML('          <td><b>Valor em Estoque</b></td>');
          ShowHTML('          <td><b>Disponível</b></td>');
          ShowHTML('        </tr>');
          $i++;
        }
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('        <tr bgcolor="'.$w_cor.'" valign="top">');
        if ($tipo) ShowHTML('          <td>'.f($row,'nm_tipo_completo').'</td>');
        ShowHTML('          <td>'.f($row,'nm_almoxarifado_local').'</td>');
        ShowHTML('          <td>'.(($w_embed=='WORD') ? f($row,'nm_material') : ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nm_material'),f($row,'sq_material'),$TP,null)).'</td>');
        ShowHTML('          <td align="center">'.f($row,'nm_unidade_medida').'</td>');
        ShowHTML('          <td align="center">'.formatNumber(f($row,'saldo_atual'),0).'</td>');
        ShowHTML('          <td align="right">'.formatNumber(f($row,'preco_medio'),5).'</td>');
        ShowHTML('          <td align="right">'.formatNumber(f($row,'saldo_atual')*f($row,'preco_medio'),2).'</td>');
        ShowHTML('          <td align="center">'.retornaSimNao(f($row,'disponivel')).'</td>');
        ShowHTML('        </tr>');
      }
    }
  } elseif ($O=='P') {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML(montaFiltro('POST',true));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    // Exibe parâmetros de apresentação
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    ShowHTML('         <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Critérios de Busca</td>');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('      <tr>');
    SelecaoAlmoxarifado('Al<u>m</u>oxarifado:','M', 'Selecione o almoxarifado onde o material será armazenado.', &$p_chave,'p_chave',null,'onChange="document.Form.O.value=\'P\'; document.Form.w_troca.value=\'p_pais\'; document.Form.submit();"',2);
    ShowHTML('      <tr>');
    selecaoTipoMatServSubord('<u>T</u>ipo de material:','S','Selecione o grupo/subgrupo de material/serviço desejado.',$p_chave,$p_pais,'p_pais','ALMOXARIFADO',null,2);
    ShowHTML('      </tr>');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td><b><U>M</U>aterial:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="60" value="'.$p_proponente.'"></td>');
    SelecaoClasseCheck('Recuperar classes:','S',null,$p_fase,$P2,'p_fase[]','CONSUMO',null);
    ShowHTML('   <tr valign="top">');
    MontaRadioNS('<b>Apenas disponíveis para pedidos internos?</b>',$p_ativo,'p_ativo');
    ShowHTML('    <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('    <tr><td align="center" colspan="3">');
    ShowHTML('          <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
    ShowHTML('        </td>');
    ShowHTML('    </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
    ShowHTML('</table>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 

  ShowHTML('</table>');
  if($p_tipo == 'PDF')  RodapePdf();
  else                  Rodape();
}

// =========================================================================
// Análise de estoque
// -------------------------------------------------------------------------
function Analise() {
  extract($GLOBALS);
  
  $w_pag   = 1;
  $w_linha = 0;
  
  if ($O=='L' || $O=='V' || $p_tipo == 'WORD' || $p_tipo=='PDF') {
    $w_filtro='';
    if ($p_chave>'') {
      $w_linha++;
      $sql = new db_getAlmoxarifado; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_chave,null,null,null,null,'OUTROS');
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Almoxarifado<td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_pais>'') {
      $w_linha++;
      $sql = new db_getTipoMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_pais,null,null,null,null,null,null,'REGISTROS');
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Tipo de material/serviço<td>[<b>'.f($RS,'nome_completo').'</b>]';
    } 
    if ($p_proponente>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Material<td>[<b>'.$p_proponente.'</b>]'; }
    /*
    if ($p_projeto>'') {
      $w_linha++;
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');
      $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS,'sigla').'" title="Exibe as informações do projeto.">'.f($RS,'titulo').'</a></b>]';
    } 
    if ($p_atividade>'') {
      $w_linha++;
      $sql = new db_getSolicEtapa; $RS = $sql->getInstanceOf($dbms,$p_projeto,$p_atividade,'REGISTRO',null);
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Etapa <td>[<b>'.f($RS,'titulo').'</b>]';
    } 
    if ($p_empenho>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Código <td>[<b>'.$p_empenho.'</b>]'; }
    if ($p_assunto>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Descrição <td>[<b>'.$p_assunto.'</b>]'; }
    if ($p_solicitante>'') {
      $w_linha++;
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
      $w_filtro .= '<tr valign="top"><td align="right">Responsável <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_uf>'') {
      $w_linha++;
      $sql = new db_getLCSituacao; $RS = $sql->getInstanceOf($dbms, $p_uf, $w_cliente, null, null, null, null, null, null);
      foreach ($RS as $row) {
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Situação do certame <td>[<b>'.f($row,'nome').'</b>]';
        break;
      }
    } 
    if ($p_unidade>'') {
      $w_linha++;
      $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_unidade);
      $w_filtro .= '<tr valign="top"><td align="right">Unidade solicitante <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_palavra>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Número do certame <td>[<b>'.$p_palavra.'</b>]'; }
    if ($p_regiao>'' || $p_cidade>'') {
      $w_linha++;
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Protocolo <td>[<b>'.(($p_regiao>'') ? str_pad($p_regiao,6,'0',PAD_RIGHT) : '*').'/'.(($p_cidade>'') ? $p_cidade : '*').'</b>]';
    } 
    */
    if ($p_ativo=='S') {
      $w_linha++;
      $w_filtro .= '<tr valign="top"><td align="right">Restrição<td>[<b>Apenas materiais disponíveis para pedidos internos</b>]';
    } 
    /*
    if ($p_ini_i>'')      $w_filtro.='<tr valign="top"><td align="right">Abertura de propostas <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
    if ($p_fim_i>'')  { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Autorização <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]'; }
    */
    if ($w_filtro>'') { $w_linha++; $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>'; }

    $sql = new db_getSolicMT; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,'ALINV',3,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,null,$p_ativo,$p_proponente,
        $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
        $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade,
        $p_acao_ppa, null, $p_empenho, null);
    $RS1 = SortArray($RS1,'nm_tipo_completo','asc','nm_material','asc');
  }
  $w_linha_filtro = $w_linha;

  if ($p_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 45: 30);
    CabecalhoWord($w_cliente,substr($TP,strrpos($TP,'-')+1),$w_pag);
    $w_embed = 'WORD';
    if ($w_filtro>'') ShowHTML($w_filtro);
  } elseif($p_tipo == 'PDF'){
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 60: 35);
    $w_embed = 'WORD';
    HeaderPdf(substr($TP,strrpos($TP,'-')+1),$w_pag);
    if ($w_filtro>'') ShowHTML($w_filtro);
  } elseif ($p_tipo=='EXCEL') {
    $w_embed = 'WORD';
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 60: 35);
    HeaderExcel($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,substr($TP,strrpos($TP,'-')+1),$w_pag);
    if ($w_filtro>'') ShowHTML($w_filtro);
  } else {
    $w_embed = 'HTML';
    Cabecalho();
    head();
    if ($O=='P') {
      ScriptOpen('Javascript');
      CheckBranco();
      FormataData();
      SaltaCampo();
      ValidateOpen('Validacao');
      Validate('p_chave','Almoxarifado','SELECT','1','1','18','','1');
      Validate('p_proponente','Material','','','2','60','1','');
      ValidateClose();
      ScriptClose();
    } else {
      ShowHTML('<TITLE>'.$w_TP.'</TITLE>');
    } 
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</HEAD>');
    if ($w_Troca>'') {
      // Se for recarga da página
      BodyOpen('onLoad=\'document.Form.'.$w_Troca.'.focus();\'');
    } else {
      BodyOpenClean('onLoad=this.focus();');
    } 

    if ($O=='L') {
      CabecalhoRelatorio($w_cliente,substr($TP,strrpos($TP,'-')+1),4);
      if ($w_filtro>'') ShowHTML($w_filtro);
    } else {
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
      ShowHTML('<HR>');
    } 
  } 

  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L' || $w_embed == 'WORD') {
    if ((strpos(upper($R),'GR_'))===false && $P1!=6 && $w_embed!='WORD') {
      ShowHTML('<tr><td><a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">'.((strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) ? '<font color="#BC5100"><u>F</u>iltrar (Ativo)</font>' : '<u>F</u>iltrar (Inativo)').'</a>');
    }
    $i    = 0;
    $tipo = false;
    if (count($RS1)==0) {
      ShowHTML('<tr><td align="center"><hr/><b>Não foram encontrados registros</b></td></tr>');
    } else {
      foreach ($RS1 as $row) {
        if ($i==0) {
          if (nvl($p_pais,0)!=f($row,'sq_tipo_material')) $tipo = true;
          ShowHTML('<tr><td align="center">');
          ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
          ShowHTML('        <tr bgcolor="#DCDCDC" align="center">');
          if ($tipo) ShowHTML('          <td rowspan="2"><b>Tipo de material</b></td>');
          ShowHTML('          <td rowspan="2"><b>Material</b></td>');
          ShowHTML('          <td rowspan="2"><b>U.M.</b></td>');
          ShowHTML('          <td colspan="3"><b>Posição de Estoque</b></td>');
          ShowHTML('          <td colspan="3"><b>Última Ocorrência</b></td>');
          ShowHTML('          <td colspan="4"><b>Gestão</b></td>');
          ShowHTML('          <td rowspan="2"><b>Disponível</b></td>');
          ShowHTML('        </tr>');
          ShowHTML('        <tr bgcolor="#DCDCDC" align="center">');
          ShowHTML('          <td><b>Qtd.</b></td>');
          ShowHTML('          <td><b>P.M.</b></td>');
          ShowHTML('          <td><b>V.E.</b></td>');
          ShowHTML('          <td><b>Entrada</b></td>');
          ShowHTML('          <td><b>Preço</b></td>');
          ShowHTML('          <td><b>Saída</b></td>');
          ShowHTML('          <td><b>Est.Min.</b></td>');
          ShowHTML('          <td><b>C.M.M.</b></td>');
          ShowHTML('          <td><b>P.R.</b></td>');
          ShowHTML('          <td><b>Ciclo</b></td>');
          ShowHTML('        </tr>');
          $i++;
        }
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('        <tr bgcolor="'.$w_cor.'" valign="top">');
        if ($tipo) ShowHTML('          <td>'.f($row,'nm_tipo_completo').'</td>');
        ShowHTML('          <td>'.(($w_embed=='WORD') ? f($row,'nm_material') : ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nm_material'),f($row,'sq_material'),$TP,null)).'</td>');
        ShowHTML('          <td align="center">'.f($row,'sg_unidade_medida').'</td>');
        ShowHTML('          <td align="center">'.formatNumber(f($row,'saldo_atual'),0).'</td>');
        ShowHTML('          <td align="right">'.formatNumber(f($row,'preco_medio'),5).'</td>');
        ShowHTML('          <td align="right">'.formatNumber(f($row,'saldo_atual')*f($row,'preco_medio'),2).'</td>');
        ShowHTML('          <td align="center">'.formataDataEdicao(f($row,'ultima_entrada'),5).'</td>');
        ShowHTML('          <td align="right">'.formatNumber(f($row,'ultimo_preco_compra')).'</td>');
        ShowHTML('          <td align="center">'.formataDataEdicao(f($row,'ultima_saida'),5).'</td>');
        ShowHTML('          <td align="center">'.formatNumber(f($row,'estoque_minimo'),0).'</td>');
        ShowHTML('          <td align="center">'.formatNumber(f($row,'consumo_medio_mensal'),0).'</td>');
        ShowHTML('          <td align="center">'.formatNumber(f($row,'ponto_ressuprimento'),0).'</td>');
        ShowHTML('          <td align="center">'.formatNumber(f($row,'ciclo_compra'),0).'</td>');
        ShowHTML('          <td align="center">'.retornaSimNao(f($row,'disponivel')).'</td>');
        ShowHTML('        </tr>');
      }
    }
    ShowHTML('</table>');
    ShowHTML('<tr><td><b>Legenda:</b><table>');
    ShowHTML('<tr><td><li><b>U.M.</b><td colspan="12">Unidade de medida');
    ShowHTML('<tr><td><li><b>Qtd</b><td colspan="12">Quantidade atual em estoque no almoxarifado');
    ShowHTML('<tr><td><li><b>P.M.</b><td colspan="12">Preço médio do material no almoxarifado');
    ShowHTML('<tr><td><li><b>V.E.</b><td colspan="12">Valor do material no almoxarifado');
    ShowHTML('<tr><td><li><b>Entrada</b><td colspan="12">Data da última entrada do material no almoxarifado');
    ShowHTML('<tr><td><li><b>Preço</b><td colspan="12">Último preço de compra do material');
    ShowHTML('<tr><td><li><b>Saída</b><td colspan="12">Data da última saída do material no almoxarifado');
    ShowHTML('<tr><td><li><b>Est.Min.</b><td colspan="12">Estoque mínimo do material a ser mantido no almoxarifado');
    ShowHTML('<tr><td><li><b>C.M.M.</b><td colspan="12">Consumo médio mensal do material');
    ShowHTML('<tr><td><li><b>P.R.</b><td colspan="12">Ponto de ressuprimento. Quantidade que, quando atingida, deve ser iniciada nova compra');
    ShowHTML('<tr><td><li><b>Ciclo</b><td colspan="12">Ciclo de compra. Quantidade de dias corridos entre a emissão da solicitação de compra e a chegada do material no almoxarifado');
    ShowHTML('<tr><td><li><b>Disponível</b><td colspan="12">Indica a disponibilidade do material para pedidos internos de material');
    ShowHTML('</table></tr>');
  } elseif ($O=='P') {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML(montaFiltro('POST',true));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    // Exibe parâmetros de apresentação
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    ShowHTML('         <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Critérios de Busca</td>');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('      <tr>');
    SelecaoAlmoxarifado('Al<u>m</u>oxarifado:','M', 'Selecione o almoxarifado onde o material será armazenado.', &$p_chave,'p_chave',null,'onChange="document.Form.O.value=\'P\'; document.Form.w_troca.value=\'p_pais\'; document.Form.submit();"',2);
    ShowHTML('      <tr>');
    selecaoTipoMatServSubord('<u>T</u>ipo de material:','S','Selecione o grupo/subgrupo de material/serviço desejado.',$p_chave,$p_pais,'p_pais','ALMOXARIFADO',null,2);
    ShowHTML('      </tr>');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td><b><U>M</U>aterial:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="60" value="'.$p_proponente.'"></td>');
    SelecaoClasseCheck('Recuperar classes:','S',null,$p_fase,$P2,'p_fase[]','CONSUMO',null);
    ShowHTML('   <tr valign="top">');
    MontaRadioNS('<b>Apenas disponíveis para pedidos internos?</b>',$p_ativo,'p_ativo');
    ShowHTML('    <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('    <tr><td align="center" colspan="3">');
    ShowHTML('          <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
    ShowHTML('        </td>');
    ShowHTML('    </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
    ShowHTML('</table>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 

  ShowHTML('</table>');
  if($p_tipo == 'PDF')  RodapePdf();
  else                  Rodape();
}

// =========================================================================
// Mapa de entradas
// -------------------------------------------------------------------------
function Entrada() {
  extract($GLOBALS);
  
  $w_pag   = 1;
  $w_linha = 0;
  
  if ($O=='L' || $O=='V' || $p_tipo == 'WORD' || $p_tipo=='PDF') {
    $w_filtro='';
    if ($p_chave>'') {
      $w_linha++;
      $sql = new db_getAlmoxarifado; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_chave,null,null,null,null,'OUTROS');
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Almoxarifado<td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_ini_i>'')      $w_filtro.='<tr valign="top"><td align="right">Período <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
    if ($p_pais>'') {
      $w_linha++;
      $sql = new db_getTipoMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_pais,null,null,null,null,null,null,'REGISTROS');
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Tipo de material/serviço<td>[<b>'.f($RS,'nome_completo').'</b>]';
    } 
    if ($p_proponente>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Material<td>[<b>'.$p_proponente.'</b>]'; }
    /*
    if ($p_projeto>'') {
      $w_linha++;
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');
      $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS,'sigla').'" title="Exibe as informações do projeto.">'.f($RS,'titulo').'</a></b>]';
    } 
    if ($p_atividade>'') {
      $w_linha++;
      $sql = new db_getSolicEtapa; $RS = $sql->getInstanceOf($dbms,$p_projeto,$p_atividade,'REGISTRO',null);
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Etapa <td>[<b>'.f($RS,'titulo').'</b>]';
    } 
    if ($p_empenho>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Código <td>[<b>'.$p_empenho.'</b>]'; }
    if ($p_assunto>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Descrição <td>[<b>'.$p_assunto.'</b>]'; }
    if ($p_solicitante>'') {
      $w_linha++;
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
      $w_filtro .= '<tr valign="top"><td align="right">Responsável <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_uf>'') {
      $w_linha++;
      $sql = new db_getLCSituacao; $RS = $sql->getInstanceOf($dbms, $p_uf, $w_cliente, null, null, null, null, null, null);
      foreach ($RS as $row) {
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Situação do certame <td>[<b>'.f($row,'nome').'</b>]';
        break;
      }
    } 
    if ($p_unidade>'') {
      $w_linha++;
      $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_unidade);
      $w_filtro .= '<tr valign="top"><td align="right">Unidade solicitante <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_palavra>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Número do certame <td>[<b>'.$p_palavra.'</b>]'; }
    if ($p_regiao>'' || $p_cidade>'') {
      $w_linha++;
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Protocolo <td>[<b>'.(($p_regiao>'') ? str_pad($p_regiao,6,'0',PAD_RIGHT) : '*').'/'.(($p_cidade>'') ? $p_cidade : '*').'</b>]';
    } 
    */
    if ($p_ativo=='S') {
      $w_linha++;
      $w_filtro .= '<tr valign="top"><td align="right">Restrição<td>[<b>Apenas materiais disponíveis para pedidos internos</b>]';
    } 
    /*
    if ($p_fim_i>'')  { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Autorização <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]'; }
    */
    if ($w_filtro>'') { $w_linha++; $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>'; }

    $sql = new db_getSolicMT; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,'ALENTRADA',3,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,null,$p_ativo,$p_proponente,
        $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
        $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade,
        $p_acao_ppa, null, $p_empenho, null);
  }
  $w_linha_filtro = $w_linha;

  if ($p_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 45: 30);
    CabecalhoWord($w_cliente,substr($TP,strrpos($TP,'-')+1),$w_pag);
    $w_embed = 'WORD';
    if ($w_filtro>'') ShowHTML($w_filtro);
  } elseif($p_tipo == 'PDF'){
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 60: 35);
    $w_embed = 'WORD';
    HeaderPdf(substr($TP,strrpos($TP,'-')+1),$w_pag);
    if ($w_filtro>'') ShowHTML($w_filtro);
  } elseif ($p_tipo=='EXCEL') {
    $w_embed = 'WORD';
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 60: 35);
    HeaderExcel($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,substr($TP,strrpos($TP,'-')+1),$w_pag);
    if ($w_filtro>'') ShowHTML($w_filtro);
  } else {
    $w_embed = 'HTML';
    Cabecalho();
    head();
    if ($O=='P') {
      ScriptOpen('Javascript');
      CheckBranco();
      FormataData();
      SaltaCampo();
      ValidateOpen('Validacao');
      Validate('p_chave','Almoxarifado','SELECT','1','1','18','','1');
      Validate('p_ini_i','Início','DATA','1','10','10','','0123456789/');
      Validate('p_ini_f','Fim','DATA','1','10','10','','0123456789/');
      CompData('p_ini_i','Início','<=','p_ini_f','Fim');
      Validate('p_proponente','Material','','','2','60','1','');
      ValidateClose();
      ScriptClose();
    } else {
      ShowHTML('<TITLE>'.$w_TP.'</TITLE>');
    } 
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</HEAD>');
    if ($w_Troca>'') {
      // Se for recarga da página
      BodyOpen('onLoad=\'document.Form.'.$w_Troca.'.focus();\'');
    } else {
      BodyOpenClean('onLoad=this.focus();');
    } 

    if ($O=='L') {
      CabecalhoRelatorio($w_cliente,substr($TP,strrpos($TP,'-')+1),4);
      if ($w_filtro>'') ShowHTML($w_filtro);
    } else {
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
      ShowHTML('<HR>');
    } 
  } 

  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L' || $w_embed == 'WORD') {
    if ((strpos(upper($R),'GR_'))===false && $P1!=6 && $w_embed!='WORD') {
      ShowHTML('<tr><td><a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">'.((strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) ? '<font color="#BC5100"><u>F</u>iltrar (Ativo)</font>' : '<u>F</u>iltrar (Inativo)').'</a>');
    }
    $i    = 0;
    $tipo = false;
    if (count($RS1)==0) {
      ShowHTML('<tr><td align="center"><hr/><b>Não foram encontrados registros</b></td></tr>');
    } else {
      $w_total = 0;
      $colspan = 8;
      foreach ($RS1 as $row) {
        if ($i==0) {
          if (nvl($p_pais,0)!=f($row,'sq_tipo_material')) $tipo = true;
          ShowHTML('<tr><td align="center">');
          ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
          ShowHTML('        <tr bgcolor="#DCDCDC" align="center">');
          if ($tipo) {
            $colspan++;
            ShowHTML('          <td><b>Tipo de material</b></td>');
          }
          ShowHTML('          <td><b>Material</b></td>');
          ShowHTML('          <td><b>U.M.</b></td>');
          ShowHTML('          <td><b>Fornecedor</b></td>');
          ShowHTML('          <td><b>Documento</b></td>');
          ShowHTML('          <td><b>Armazen.</b></td>');
          ShowHTML('          <td><b>Valid.</b></td>');
          ShowHTML('          <td><b>Qtd.</b></td>');
          ShowHTML('          <td><b>$ Unitário</b></td>');
          ShowHTML('          <td><b>$ Entrada</b></td>');
          ShowHTML('        </tr>');
          $i++;
        }
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('        <tr bgcolor="'.$w_cor.'" valign="top">');
        if ($tipo) ShowHTML('          <td>'.f($row,'nm_tipo_completo').'</td>');
        ShowHTML('          <td>'.(($w_embed=='WORD') ? f($row,'nm_material') : ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nm_material'),f($row,'sq_material'),$TP,null)).'</td>');
        ShowHTML('          <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>');
        ShowHTML('          <td>'.(($w_embed=='WORD') ? f($row,'nm_fornecedor') : ExibePessoa('../',$w_cliente,f($row,'fornecedor'),$TP,f($row,'nm_fornecedor'))).'</td>');
        ShowHTML('          <td>'.(($w_embed=='WORD') ? f($row,'sg_tip_doc').' '.f($row,'nr_doc') : '<a class="HL" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,f($row,'link_menu').'visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_mtentrada').'&SG='.f($row,'sg_menu').'&w_menu='.f($row,'sq_menu').'&P1='.$P1.'&P2='.$P2.'&P3='.f($row,'p3').'&P4='.f($row,'p4').'&TP='.f($row,'nm_menu')).'\',\'TelaEntrada\',\'width=785,height=570,top=10,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\'); return false;" title="Exibe os dados da entrada.">'.f($row,'sg_tip_doc').' '.f($row,'nr_doc').'</a>').'</td>');
        ShowHTML('          <td align="center" title="Data de armazenamento">'.formataDataEdicao(f($row,'armazenamento'),5).'</td>');
        ShowHTML('          <td align="center" title="Data de término da validade">'.formataDataEdicao(f($row,'validade'),5).'</td>');
        ShowHTML('          <td align="center">'.formatNumber(f($row,'qt_entrada'),0).'</td>');
        ShowHTML('          <td align="right">'.formatNumber(f($row,'vl_entrada'),5).'</td>');
        ShowHTML('          <td align="right">'.formatNumber(f($row,'tot_entrada'),2).'</td>');
        ShowHTML('        </tr>');
        $w_total += f($row,'tot_entrada');
      }
      if (count($RS1)>1) {
        ShowHTML('        <tr bgcolor="#DCDCDC" align="right">');
        ShowHTML('          <td colspan='.$colspan.'><b>Total</b>&nbsp;</td>');
        ShowHTML('          <td><b>'.formatNumber($w_total).'</b></td>');
      }
    }
  } elseif ($O=='P') {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML(montaFiltro('POST',true));
    // Exibe parâmetros de apresentação
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    ShowHTML('         <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Critérios de Busca</td>');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('      <tr>');
    SelecaoAlmoxarifado('Al<u>m</u>oxarifado:','M', 'Selecione o almoxarifado onde o material será armazenado.', &$p_chave,'p_chave',null,'onChange="document.Form.O.value=\'P\'; document.Form.w_troca.value=\'p_pais\'; document.Form.submit();"',1);
    ShowHTML('     <td><b><u>P</u>eríodo de análise:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="D" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
    ShowHTML('      <tr>');
    selecaoTipoMatServSubord('<u>T</u>ipo de material:','S','Selecione o grupo/subgrupo de material/serviço desejado.',$p_chave,$p_pais,'p_pais','ALMOXARIFADO',null,2);
    ShowHTML('      </tr>');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td><b><U>M</U>aterial:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="60" value="'.$p_proponente.'"></td>');
    SelecaoClasseCheck('Recuperar classes:','S',null,$p_fase,$P2,'p_fase[]','CONSUMO',null);
    ShowHTML('   <tr valign="top">');
    MontaRadioNS('<b>Apenas disponíveis para pedidos internos?</b>',$p_ativo,'p_ativo');
    ShowHTML('    <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('    <tr><td align="center" colspan="3">');
    ShowHTML('          <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
    ShowHTML('        </td>');
    ShowHTML('    </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
    ShowHTML('</table>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 

  ShowHTML('</table>');
  if($p_tipo == 'PDF')  RodapePdf();
  else                  Rodape();
}

// =========================================================================
// Mapa de saídas
// -------------------------------------------------------------------------
function Saida() {
  extract($GLOBALS);
  
  $w_pag   = 1;
  $w_linha = 0;
  
  if ($O=='L' || $O=='V' || $p_tipo == 'WORD' || $p_tipo=='PDF') {
    $w_filtro='';
    if ($p_chave>'') {
      $w_linha++;
      $sql = new db_getAlmoxarifado; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_chave,null,null,null,null,'OUTROS');
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Almoxarifado<td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_ini_i>'')      $w_filtro.='<tr valign="top"><td align="right">Período <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
    if ($p_pais>'') {
      $w_linha++;
      $sql = new db_getTipoMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_pais,null,null,null,null,null,null,'REGISTROS');
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Tipo de material/serviço<td>[<b>'.f($RS,'nome_completo').'</b>]';
    } 
    if ($p_proponente>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Material<td>[<b>'.$p_proponente.'</b>]'; }
    /*
    if ($p_projeto>'') {
      $w_linha++;
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');
      $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS,'sigla').'" title="Exibe as informações do projeto.">'.f($RS,'titulo').'</a></b>]';
    } 
    if ($p_atividade>'') {
      $w_linha++;
      $sql = new db_getSolicEtapa; $RS = $sql->getInstanceOf($dbms,$p_projeto,$p_atividade,'REGISTRO',null);
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Etapa <td>[<b>'.f($RS,'titulo').'</b>]';
    } 
    if ($p_empenho>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Código <td>[<b>'.$p_empenho.'</b>]'; }
    if ($p_assunto>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Descrição <td>[<b>'.$p_assunto.'</b>]'; }
    if ($p_solicitante>'') {
      $w_linha++;
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
      $w_filtro .= '<tr valign="top"><td align="right">Responsável <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_uf>'') {
      $w_linha++;
      $sql = new db_getLCSituacao; $RS = $sql->getInstanceOf($dbms, $p_uf, $w_cliente, null, null, null, null, null, null);
      foreach ($RS as $row) {
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Situação do certame <td>[<b>'.f($row,'nome').'</b>]';
        break;
      }
    } 
    if ($p_unidade>'') {
      $w_linha++;
      $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_unidade);
      $w_filtro .= '<tr valign="top"><td align="right">Unidade solicitante <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_palavra>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Número do certame <td>[<b>'.$p_palavra.'</b>]'; }
    if ($p_regiao>'' || $p_cidade>'') {
      $w_linha++;
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Protocolo <td>[<b>'.(($p_regiao>'') ? str_pad($p_regiao,6,'0',PAD_RIGHT) : '*').'/'.(($p_cidade>'') ? $p_cidade : '*').'</b>]';
    }
    */
    if ($p_ativo=='S') {
      $w_linha++;
      $w_filtro .= '<tr valign="top"><td align="right">Restrição<td>[<b>Apenas materiais disponíveis para pedidos internos</b>]';
    } 
    /*
    if ($p_fim_i>'')  { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Autorização <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]'; }
    */
    if ($w_filtro>'') { $w_linha++; $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>'; }

    $sql = new db_getSolicMT; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,'ALSAIDA',3,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,null,$p_ativo,$p_proponente,
        $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
        $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade,
        $p_acao_ppa, null, $p_empenho, null);
  }
  $w_linha_filtro = $w_linha;

  if ($p_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 45: 30);
    CabecalhoWord($w_cliente,substr($TP,strrpos($TP,'-')+1),$w_pag);
    $w_embed = 'WORD';
    if ($w_filtro>'') ShowHTML($w_filtro);
  } elseif($p_tipo == 'PDF'){
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 60: 35);
    $w_embed = 'WORD';
    HeaderPdf(substr($TP,strrpos($TP,'-')+1),$w_pag);
    if ($w_filtro>'') ShowHTML($w_filtro);
  } elseif ($p_tipo=='EXCEL') {
    $w_embed = 'WORD';
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 60: 35);
    HeaderExcel($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,substr($TP,strrpos($TP,'-')+1),$w_pag);
    if ($w_filtro>'') ShowHTML($w_filtro);
  } else {
    $w_embed = 'HTML';
    Cabecalho();
    head();
    if ($O=='P') {
      ScriptOpen('Javascript');
      CheckBranco();
      FormataData();
      SaltaCampo();
      ValidateOpen('Validacao');
      Validate('p_chave','Almoxarifado','SELECT','1','1','18','','1');
      Validate('p_ini_i','Início','DATA','1','10','10','','0123456789/');
      Validate('p_ini_f','Fim','DATA','1','10','10','','0123456789/');
      CompData('p_ini_i','Início','<=','p_ini_f','Fim');
      Validate('p_proponente','Material','','','2','60','1','');
      ValidateClose();
      ScriptClose();
    } else {
      ShowHTML('<TITLE>'.$w_TP.'</TITLE>');
    } 
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</HEAD>');
    if ($w_Troca>'') {
      // Se for recarga da página
      BodyOpen('onLoad=\'document.Form.'.$w_Troca.'.focus();\'');
    } else {
      BodyOpenClean('onLoad=this.focus();');
    } 

    if ($O=='L') {
      CabecalhoRelatorio($w_cliente,substr($TP,strrpos($TP,'-')+1),4);
      if ($w_filtro>'') ShowHTML($w_filtro);
    } else {
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
      ShowHTML('<HR>');
    } 
  } 

  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L' || $w_embed == 'WORD') {
    if ((strpos(upper($R),'GR_'))===false && $P1!=6 && $w_embed!='WORD') {
      ShowHTML('<tr><td><a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">'.((strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) ? '<font color="#BC5100"><u>F</u>iltrar (Ativo)</font>' : '<u>F</u>iltrar (Inativo)').'</a>');
    }
    $i    = 0;
    $tipo = false;
    if (count($RS1)==0) {
      ShowHTML('<tr><td align="center"><hr/><b>Não foram encontrados registros</b></td></tr>');
    } else {
      $w_total = 0;
      $colspan = 8;
      foreach ($RS1 as $row) {
        if ($i==0) {
          if (nvl($p_pais,0)!=f($row,'sq_tipo_material')) $tipo = true;
          ShowHTML('<tr><td align="center">');
          ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
          ShowHTML('        <tr bgcolor="#DCDCDC" align="center">');
          if ($tipo) {
            $colspan++;
            ShowHTML('          <td rowspan=2><b>Tipo de material</b></td>');
          }
          ShowHTML('          <td rowspan=2><b>Material</b></td>');
          ShowHTML('          <td rowspan=2><b>U.M.</b></td>');
          ShowHTML('          <td rowspan=2><b>Fator Embalag.</b></td>');
          ShowHTML('          <td rowspan=2><b>Destino</b></td>');
          ShowHTML('          <td rowspan=2><b>Documento</b></td>');
          ShowHTML('          <td colspan=2><b>Entrega</b></td>');
          ShowHTML('          <td rowspan=2><b>Qtd.</b></td>');
          ShowHTML('          <td rowspan=2><b>Valor</b></td>');
          ShowHTML('        </tr>');
          ShowHTML('        <tr bgcolor="#DCDCDC" align="center">');
          ShowHTML('          <td><b>Agendada</b></td>');
          ShowHTML('          <td><b>Efetiva</b></td>');
          ShowHTML('        </tr>');
          $i++;
        }
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('        <tr bgcolor="'.$w_cor.'" valign="top">');
        if ($tipo) ShowHTML('          <td>'.f($row,'nm_tipo_completo').'</td>');
        ShowHTML('          <td>'.(($w_embed=='WORD') ? f($row,'nm_material') : ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nm_material'),f($row,'sq_material'),$TP,null)).'</td>');
        ShowHTML('          <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>');
        ShowHTML('          <td align="center">'.formatNumber(f($row,'fator_embalagem'),0).'</td>');
        if (f($row,'tp_destino')=='I') {
          ShowHTML('          <td>'.(($w_embed=='WORD') ? f($row,'nm_destino') : ExibeUnidade('../',$w_cliente,f($row,'nm_destino'),f($row,'sq_destino'),$TP)).'</td>');
        } else {
          ShowHTML('          <td>'.(($w_embed=='WORD') ? f($row,'nm_destino') : ExibePessoa('../',$w_cliente,f($row,'sq_destino'),$TP,f($row,'nm_destino'))).'</td>');
        }
        ShowHTML('          <td width="1%" nowrap>');
        if ($w_embed!='WORD') ShowHTML(ExibeImagemSolic(f($row,'sg_menu'),f($row,'inicio'),f($row,'fim'),null,null,'S','1',f($row,'sg_tramite'), null));
        ShowHTML('          '.ExibeSolic($w_dir,f($row,'sq_siw_solicitacao'),f($row,'dados_solic'),'N',$w_embed).'&nbsp;</a>');
        ShowHTML('          <td align="center" title="Data agendada para entrega">'.formataDataEdicao(f($row,'fim'),5).'</td>');
        ShowHTML('          <td align="center" title="Data efetiva de entrega">'.formataDataEdicao(f($row,'data_efetivacao'),5).'</td>');
        ShowHTML('          <td align="center">'.formatNumber(f($row,'quantidade_entregue'),0).'</td>');
        ShowHTML('          <td align="right">'.formatNumber(f($row,'vl_saida')).'</td>');
        ShowHTML('        </tr>');
        $w_total += f($row,'vl_saida');
      }
      if (count($RS1)>1) {
        ShowHTML('        <tr bgcolor="#DCDCDC" align="right">');
        ShowHTML('          <td colspan='.$colspan.'><b>Total</b>&nbsp;</td>');
        ShowHTML('          <td><b>'.formatNumber($w_total).'</b></td>');
      }
    }
  } elseif ($O=='P') {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML(montaFiltro('POST',true));
    // Exibe parâmetros de apresentação
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    ShowHTML('         <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Critérios de Busca</td>');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('      <tr>');
    SelecaoAlmoxarifado('Al<u>m</u>oxarifado:','M', 'Selecione o almoxarifado onde o material será armazenado.', &$p_chave,'p_chave',null,'onChange="document.Form.O.value=\'P\'; document.Form.w_troca.value=\'p_pais\'; document.Form.submit();"',1);
    ShowHTML('     <td><b><u>P</u>eríodo de análise:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="D" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
    ShowHTML('      <tr>');
    selecaoTipoMatServSubord('<u>T</u>ipo de material:','S','Selecione o grupo/subgrupo de material/serviço desejado.',$p_chave,$p_pais,'p_pais','ALMOXARIFADO',null,2);
    ShowHTML('      </tr>');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td><b><U>M</U>aterial:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="60" value="'.$p_proponente.'"></td>');
    SelecaoClasseCheck('Recuperar classes:','S',null,$p_fase,$P2,'p_fase[]','CONSUMO',null);
    ShowHTML('   <tr valign="top">');
    MontaRadioNS('<b>Apenas disponíveis para pedidos internos?</b>',$p_ativo,'p_ativo');
    ShowHTML('    <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('    <tr><td align="center" colspan="3">');
    ShowHTML('          <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
    ShowHTML('        </td>');
    ShowHTML('    </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
    ShowHTML('</table>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 

  ShowHTML('</table>');
  if($p_tipo == 'PDF')  RodapePdf();
  else                  Rodape();
}

// =========================================================================
// Mapa de entradas e saídas
// -------------------------------------------------------------------------
function Mapa() {
  extract($GLOBALS);
  
  $w_pag   = 1;
  $w_linha = 0;
  
  if ($O=='L' || $O=='V' || $p_tipo == 'WORD' || $p_tipo=='PDF') {
    $w_filtro='';
    if ($p_chave>'') {
      $w_linha++;
      $sql = new db_getAlmoxarifado; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_chave,null,null,null,null,'OUTROS');
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Almoxarifado<td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_ini_i>'')      $w_filtro.='<tr valign="top"><td align="right">Período <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
    if ($p_pais>'') {
      $w_linha++;
      $sql = new db_getTipoMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_pais,null,null,null,null,null,null,'REGISTROS');
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Tipo de material/serviço<td>[<b>'.f($RS,'nome_completo').'</b>]';
    } 
    if ($p_proponente>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Material<td>[<b>'.$p_proponente.'</b>]'; }
    /*
    if ($p_projeto>'') {
      $w_linha++;
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');
      $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS,'sigla').'" title="Exibe as informações do projeto.">'.f($RS,'titulo').'</a></b>]';
    } 
    if ($p_atividade>'') {
      $w_linha++;
      $sql = new db_getSolicEtapa; $RS = $sql->getInstanceOf($dbms,$p_projeto,$p_atividade,'REGISTRO',null);
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Etapa <td>[<b>'.f($RS,'titulo').'</b>]';
    } 
    if ($p_empenho>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Código <td>[<b>'.$p_empenho.'</b>]'; }
    if ($p_assunto>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Descrição <td>[<b>'.$p_assunto.'</b>]'; }
    if ($p_solicitante>'') {
      $w_linha++;
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
      $w_filtro .= '<tr valign="top"><td align="right">Responsável <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_uf>'') {
      $w_linha++;
      $sql = new db_getLCSituacao; $RS = $sql->getInstanceOf($dbms, $p_uf, $w_cliente, null, null, null, null, null, null);
      foreach ($RS as $row) {
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Situação do certame <td>[<b>'.f($row,'nome').'</b>]';
        break;
      }
    } 
    if ($p_unidade>'') {
      $w_linha++;
      $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_unidade);
      $w_filtro .= '<tr valign="top"><td align="right">Unidade solicitante <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_palavra>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Número do certame <td>[<b>'.$p_palavra.'</b>]'; }
    if ($p_regiao>'' || $p_cidade>'') {
      $w_linha++;
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Protocolo <td>[<b>'.(($p_regiao>'') ? str_pad($p_regiao,6,'0',PAD_RIGHT) : '*').'/'.(($p_cidade>'') ? $p_cidade : '*').'</b>]';
    } 
    */
    if ($p_ativo=='S') {
      $w_linha++;
      $w_filtro .= '<tr valign="top"><td align="right">Restrição<td>[<b>Apenas materiais disponíveis para pedidos internos</b>]';
    } 
    /*
    if ($p_fim_i>'')  { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Autorização <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]'; }
    */
    if ($w_filtro>'') { $w_linha++; $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>'; }

    $sql = new db_getSolicMT; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,'ALMAPA',3,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,null,$p_ativo,$p_proponente,
        $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
        $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade,
        $p_acao_ppa, null, $p_empenho, null);
  }
  $w_linha_filtro = $w_linha;

  if ($p_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 45: 30);
    CabecalhoWord($w_cliente,substr($TP,strrpos($TP,'-')+1),$w_pag);
    $w_embed = 'WORD';
    if ($w_filtro>'') ShowHTML($w_filtro);
  } elseif($p_tipo == 'PDF'){
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 60: 35);
    $w_embed = 'WORD';
    HeaderPdf(substr($TP,strrpos($TP,'-')+1),$w_pag);
    if ($w_filtro>'') ShowHTML($w_filtro);
  } elseif ($p_tipo=='EXCEL') {
    $w_embed = 'WORD';
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 60: 35);
    HeaderExcel($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,substr($TP,strrpos($TP,'-')+1),$w_pag);
    if ($w_filtro>'') ShowHTML($w_filtro);
  } else {
    $w_embed = 'HTML';
    Cabecalho();
    head();
    if ($O=='P') {
      ScriptOpen('Javascript');
      CheckBranco();
      FormataData();
      SaltaCampo();
      ValidateOpen('Validacao');
      Validate('p_chave','Almoxarifado','SELECT','1','1','18','','1');
      Validate('p_ini_i','Início','DATA','1','10','10','','0123456789/');
      Validate('p_ini_f','Fim','DATA','1','10','10','','0123456789/');
      CompData('p_ini_i','Início','<=','p_ini_f','Fim');
      Validate('p_proponente','Material','','','2','60','1','');
      ValidateClose();
      ScriptClose();
    } else {
      ShowHTML('<TITLE>'.$w_TP.'</TITLE>');
    } 
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</HEAD>');
    if ($w_Troca>'') {
      // Se for recarga da página
      BodyOpen('onLoad=\'document.Form.'.$w_Troca.'.focus();\'');
    } else {
      BodyOpenClean('onLoad=this.focus();');
    } 

    if ($O=='L') {
      CabecalhoRelatorio($w_cliente,substr($TP,strrpos($TP,'-')+1),4);
      if ($w_filtro>'') ShowHTML($w_filtro);
    } else {
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
      ShowHTML('<HR>');
    } 
  } 

  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L' || $w_embed == 'WORD') {
    if ((strpos(upper($R),'GR_'))===false && $P1!=6 && $w_embed!='WORD') {
      ShowHTML('<tr><td><a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">'.((strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) ? '<font color="#BC5100"><u>F</u>iltrar (Ativo)</font>' : '<u>F</u>iltrar (Inativo)').'</a>');
    }
    $i    = 0;
    $tipo = false;
    if (count($RS1)==0) {
      ShowHTML('<tr><td align="center"><hr/><b>Não foram encontrados registros</b></td></tr>');
    } else {
      $w_tot_ent  = 0;
      $w_tot_sai  = 0;
      $colspan    = 8;
      $w_atual    = '';
      $w_atual_qe = 0;
      $w_atual_qs = 0;
      $w_atual_ve = 0;
      $w_atual_vs = 0;
      foreach ($RS1 as $row) {
        if ($i==0) {
          $w_cor = $conTableBgColor;
          if (nvl($p_pais,0)!=f($row,'sq_tipo_material')) $tipo = true;
          ShowHTML('<tr><td align="center">');
          ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
          ShowHTML('        <tr bgcolor="#DCDCDC" align="center">');
          if ($tipo) {
            $colspan++;
            ShowHTML('          <td><b>Tipo de material</b></td>');
          }
          ShowHTML('          <td><b>Material</b></td>');
          ShowHTML('          <td><b>U.M.</b></td>');
          ShowHTML('          <td><b>Movimentação</b></td>');
          ShowHTML('          <td><b>Fornecedor / Solicitante</b></td>');
          ShowHTML('          <td><b>Documento / Pedido</b></td>');
          ShowHTML('          <td><b>Armazen. / Agendam.</b></td>');
          ShowHTML('          <td><b>Valid. / Entrega</b></td>');
          ShowHTML('          <td><b>Qtd.</b></td>');
          //ShowHTML('          <td><b>$ Unitário</b></td>');
          ShowHTML('          <td><b>$ Movimentação</b></td>');
          ShowHTML('        </tr>');
          $i++;
        }
        if ($w_atual<>f($row,'nm_material')) {
          if ($w_atual_qe || $w_atual_qs) {
            ShowHTML('        <tr bgcolor="'.$w_cor.'" align="right"><td height="1" colspan='.(($tipo) ? '3' : '2').'></td><td height="1" colspan=7><hr style="margin:0px;" NOSHADE color=#000000 size=1 /></td></tr>');
            ShowHTML('        <tr bgcolor="'.$w_cor.'" align="right">');
            ShowHTML('          <td colspan='.($colspan-1).'><b>Total entradas</b>&nbsp;</td>');
            ShowHTML('          <td align="center"><b>'.formatNumber($w_atual_qe,0).'</b></td>');
            ShowHTML('          <td><b>'.formatNumber($w_atual_ve).'</b></td>');
            ShowHTML('        </tr>');
            ShowHTML('        <tr bgcolor="'.$w_cor.'" align="right">');
            ShowHTML('          <td colspan='.($colspan-1).'><b>Total saídas</b>&nbsp;</td>');
            ShowHTML('          <td align="center"><b>'.formatNumber($w_atual_qs,0).'</b></td>');
            ShowHTML('          <td><b>'.formatNumber($w_atual_vs).'</b></td>');
            ShowHTML('        </tr>');
            ShowHTML('        <tr bgcolor="'.$w_cor.'" align="right">');
            ShowHTML('          <td colspan='.($colspan-1).'><b>Saldo no período</b>&nbsp;</td>');
            ShowHTML('          <td align="center"><b>'.formatNumber($w_atual_qe-$w_atual_qs,0).'</b></td>');
            ShowHTML('          <td><b>'.formatNumber($w_atual_ve-$w_atual_vs).'</b></td>');
            ShowHTML('        </tr>');
            $w_atual_qe = 0;
            $w_atual_qs = 0;
            $w_atual_ve = 0;
            $w_atual_vs = 0;
          }
          ShowHTML('        <tr bgcolor="'.$w_cor.'" align="right"><td height="1" colspan='.($colspan+1).'><hr style="margin:0px;" NOSHADE color=#000000 size=1 /></td></tr>');
          ShowHTML('        <tr bgcolor="'.$w_cor.'" valign="top">');
          if ($tipo) ShowHTML('          <td>'.f($row,'nm_tipo_completo').'</td>');
          ShowHTML('          <td>'.(($w_embed=='WORD') ? f($row,'nm_material') : ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nm_material'),f($row,'sq_material'),$TP,null)).'</td>');
          ShowHTML('          <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>');
          $w_atual = f($row,'nm_material');
        } else {
          ShowHTML('        <tr bgcolor="'.$w_cor.'"><td colspan='.(($tipo) ? '3' : '2').'>&nbsp;</td>');
        }
        ShowHTML('          <td>'.f($row,'nm_tipo_movimentacao').'</td>');
        if (nvl(f($row,'quantidade_pedida'),0)==0) {
          ShowHTML('          <td>'.(($w_embed=='WORD') ? f($row,'nm_fornecedor') : ExibePessoa('../',$w_cliente,f($row,'fornecedor'),$TP,f($row,'nm_fornecedor'))).'</td>');
          ShowHTML('          <td>'.(($w_embed=='WORD') ? f($row,'sg_tip_doc').' '.f($row,'nr_doc') : '<a class="HL" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,f($row,'link_menu').'visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_mtentrada').'&SG='.f($row,'sg_menu').'&w_menu='.f($row,'sq_menu').'&P1='.$P1.'&P2='.$P2.'&P3='.f($row,'p3').'&P4='.f($row,'p4').'&TP='.f($row,'nm_menu')).'\',\'TelaEntrada\',\'width=785,height=570,top=10,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\'); return false;" title="Exibe os dados da entrada.">'.f($row,'sg_tip_doc').' '.f($row,'nr_doc').'</a>').'</td>');
          ShowHTML('          <td align="center" title="Data de armazenamento">'.formataDataEdicao(f($row,'armazenamento'),5).'</td>');
          ShowHTML('          <td align="center" title="Data de término da validade">'.formataDataEdicao(f($row,'validade'),5).'</td>');
          ShowHTML('          <td align="center">+'.formatNumber(f($row,'qt_entrada'),0).'</td>');
          //ShowHTML('          <td align="right">'.formatNumber(f($row,'vl_entrada'),5).'</td>');
          ShowHTML('          <td align="right">+'.formatNumber(f($row,'tot_entrada'),2).'</td>');
          $w_tot_ent += f($row,'tot_entrada');
          $w_atual_qe += f($row,'qt_entrada');
          $w_atual_ve += f($row,'tot_entrada');
        } else {
          if (f($row,'tp_destino')=='I') {
            ShowHTML('          <td>'.(($w_embed=='WORD') ? f($row,'nm_destino') : ExibeUnidade('../',$w_cliente,f($row,'nm_destino'),f($row,'sq_destino'),$TP)).'</td>');
          } else {
            ShowHTML('          <td>'.(($w_embed=='WORD') ? f($row,'nm_destino') : ExibePessoa('../',$w_cliente,f($row,'sq_destino'),$TP,f($row,'nm_destino'))).'</td>');
          }
          ShowHTML('          <td width="1%" nowrap>');
          if ($w_embed!='WORD') ShowHTML(ExibeImagemSolic(f($row,'sg_menu'),f($row,'inicio'),f($row,'fim'),null,null,'S','1',f($row,'sg_tramite'), null));
          ShowHTML('          '.ExibeSolic($w_dir,f($row,'sq_siw_solicitacao'),f($row,'dados_solic'),'N',$w_embed).'&nbsp;</a>');
          ShowHTML('          <td align="center" title="Data agendada para entrega">'.formataDataEdicao(f($row,'fim'),5).'</td>');
          ShowHTML('          <td align="center" title="Data efetiva de entrega">'.formataDataEdicao(f($row,'data_efetivacao'),5).'</td>');
          ShowHTML('          <td align="center">-'.formatNumber(f($row,'quantidade_entregue'),0).'</td>');
          //ShowHTML('          <td align="right">'.formatNumber(f($row,'preco_medio')).'</td>');
          ShowHTML('          <td align="right">-'.formatNumber(f($row,'vl_saida')).'</td>');
          $w_tot_sai += f($row,'vl_saida');
          $w_atual_qs += f($row,'quantidade_entregue');
          $w_atual_vs += f($row,'vl_saida');
        }
        ShowHTML('        </tr>');
      }
      if (count($RS1)>1) {
        if ($w_atual_qe || $w_atual_qs) {
          // Trata o último registro
          ShowHTML('        <tr bgcolor="'.$w_cor.'" align="right"><td height="1" colspan='.(($tipo) ? '3' : '2').'></td><td height="1" colspan=7><hr style="margin:0px;" NOSHADE color=#000000 size=1 /></td></tr>');
          ShowHTML('        <tr bgcolor="'.$w_cor.'" align="right">');
          ShowHTML('          <td colspan='.($colspan-1).'><b>Total entradas</b>&nbsp;</td>');
          ShowHTML('          <td align="center"><b>'.formatNumber($w_atual_qe,0).'</b></td>');
          ShowHTML('          <td><b>'.formatNumber($w_atual_ve,0).'</b></td>');
          ShowHTML('        </tr>');
          ShowHTML('        <tr bgcolor="'.$w_cor.'" align="right">');
          ShowHTML('          <td colspan='.($colspan-1).'><b>Total saídas</b>&nbsp;</td>');
          ShowHTML('          <td align="center"><b>'.formatNumber($w_atual_qs,0).'</b></td>');
          ShowHTML('          <td><b>'.formatNumber($w_atual_vs).'</b></td>');
          ShowHTML('        </tr>');
          ShowHTML('        <tr bgcolor="'.$w_cor.'" align="right">');
          ShowHTML('          <td colspan='.($colspan-1).'><b>Saldo no período</b>&nbsp;</td>');
          ShowHTML('          <td align="center"><b>'.formatNumber($w_atual_qe-$w_atual_qs,0).'</b></td>');
          ShowHTML('          <td><b>'.formatNumber($w_atual_ve-$w_atual_vs).'</b></td>');
          ShowHTML('        </tr>');
        }
        ShowHTML('        <tr bgcolor="#DCDCDC" align="right"><td height="1" colspan='.($colspan+1).'><hr style="margin:0px;" NOSHADE color=#000000 size=1 /></td></tr>');
        ShowHTML('        <tr bgcolor="#DCDCDC" align="right">');
        ShowHTML('          <td rowspan=3 colspan='.($colspan-1).' align="center"><b>TOTAIS NO PERÍODO DE '.$p_ini_i.' a '.$p_ini_f.'</b>&nbsp;</td>');
        ShowHTML('          <td><b>ENTRADAS</b>&nbsp;</td>');
        ShowHTML('          <td><b>'.formatNumber($w_tot_ent).'</b></td>');
        ShowHTML('        </tr>');
        ShowHTML('        <tr bgcolor="#DCDCDC" align="right">');
        ShowHTML('          <td><b>SAÍDAS</b>&nbsp;</td>');
        ShowHTML('          <td><b>'.formatNumber($w_tot_sai).'</b></td>');
        ShowHTML('        </tr>');
        ShowHTML('        <tr bgcolor="#DCDCDC" align="right">');
        ShowHTML('          <td><b>SALDO</b>&nbsp;</td>');
        ShowHTML('          <td><b>'.formatNumber($w_tot_ent - $w_tot_sai).'</b></td>');
        ShowHTML('        </tr>');
      }
    }
  } elseif ($O=='P') {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML(montaFiltro('POST',true));
    // Exibe parâmetros de apresentação
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    ShowHTML('         <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Critérios de Busca</td>');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('      <tr>');
    SelecaoAlmoxarifado('Al<u>m</u>oxarifado:','M', 'Selecione o almoxarifado onde o material será armazenado.', &$p_chave,'p_chave',null,'onChange="document.Form.O.value=\'P\'; document.Form.w_troca.value=\'p_pais\'; document.Form.submit();"',1);
    ShowHTML('     <td><b><u>P</u>eríodo de análise:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="D" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
    ShowHTML('      <tr>');
    selecaoTipoMatServSubord('<u>T</u>ipo de material:','S','Selecione o grupo/subgrupo de material/serviço desejado.',$p_chave,$p_pais,'p_pais','ALMOXARIFADO',null,2);
    ShowHTML('      </tr>');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td><b><U>M</U>aterial:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="60" value="'.$p_proponente.'"></td>');
    SelecaoClasseCheck('Recuperar classes:','S',null,$p_fase,$P2,'p_fase[]','CONSUMO',null);
    ShowHTML('   <tr valign="top">');
    MontaRadioNS('<b>Apenas disponíveis para pedidos internos?</b>',$p_ativo,'p_ativo');
    ShowHTML('    <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('    <tr><td align="center" colspan="3">');
    ShowHTML('          <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
    ShowHTML('        </td>');
    ShowHTML('    </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
    ShowHTML('</table>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 

  ShowHTML('</table>');
  if($p_tipo == 'PDF')  RodapePdf();
  else                  Rodape();
}

// =========================================================================
// Mapa sintético de entradas e saídas
// -------------------------------------------------------------------------
function MapaSint() {
  extract($GLOBALS);
  
  $w_pag   = 1;
  $w_linha = 0;
  
  if ($O=='L' || $O=='V' || $p_tipo == 'WORD' || $p_tipo=='PDF') {
    $w_filtro='';
    if ($p_chave>'') {
      $w_linha++;
      $sql = new db_getAlmoxarifado; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_chave,null,null,null,null,'OUTROS');
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Almoxarifado<td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_ini_i>'')      $w_filtro.='<tr valign="top"><td align="right">Período <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
    if ($p_pais>'') {
      $w_linha++;
      $sql = new db_getTipoMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_pais,null,null,null,null,null,null,'REGISTROS');
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Tipo de material/serviço<td>[<b>'.f($RS,'nome_completo').'</b>]';
    } 
    if ($p_proponente>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Material<td>[<b>'.$p_proponente.'</b>]'; }
    /*
    if ($p_projeto>'') {
      $w_linha++;
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');
      $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS,'sigla').'" title="Exibe as informações do projeto.">'.f($RS,'titulo').'</a></b>]';
    } 
    if ($p_atividade>'') {
      $w_linha++;
      $sql = new db_getSolicEtapa; $RS = $sql->getInstanceOf($dbms,$p_projeto,$p_atividade,'REGISTRO',null);
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Etapa <td>[<b>'.f($RS,'titulo').'</b>]';
    } 
    if ($p_empenho>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Código <td>[<b>'.$p_empenho.'</b>]'; }
    if ($p_assunto>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Descrição <td>[<b>'.$p_assunto.'</b>]'; }
    if ($p_solicitante>'') {
      $w_linha++;
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
      $w_filtro .= '<tr valign="top"><td align="right">Responsável <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_uf>'') {
      $w_linha++;
      $sql = new db_getLCSituacao; $RS = $sql->getInstanceOf($dbms, $p_uf, $w_cliente, null, null, null, null, null, null);
      foreach ($RS as $row) {
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Situação do certame <td>[<b>'.f($row,'nome').'</b>]';
        break;
      }
    } 
    if ($p_unidade>'') {
      $w_linha++;
      $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_unidade);
      $w_filtro .= '<tr valign="top"><td align="right">Unidade solicitante <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_palavra>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Número do certame <td>[<b>'.$p_palavra.'</b>]'; }
    if ($p_regiao>'' || $p_cidade>'') {
      $w_linha++;
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Protocolo <td>[<b>'.(($p_regiao>'') ? str_pad($p_regiao,6,'0',PAD_RIGHT) : '*').'/'.(($p_cidade>'') ? $p_cidade : '*').'</b>]';
    } 
    */
    if ($p_ativo=='S') {
      $w_linha++;
      $w_filtro .= '<tr valign="top"><td align="right">Restrição<td>[<b>Apenas materiais disponíveis para pedidos internos</b>]';
    } 
    /*
    if ($p_fim_i>'')  { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Autorização <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]'; }
    */
    if ($w_filtro>'') { $w_linha++; $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>'; }

    $sql = new db_getSolicMT; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,'ALMAPA',3,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,null,$p_ativo,$p_proponente,
        $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
        $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade,
        $p_acao_ppa, null, $p_empenho, null);
  }
  $w_linha_filtro = $w_linha;

  if ($p_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 45: 30);
    CabecalhoWord($w_cliente,substr($TP,strrpos($TP,'-')+1),$w_pag);
    $w_embed = 'WORD';
    if ($w_filtro>'') ShowHTML($w_filtro);
  } elseif($p_tipo == 'PDF'){
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 60: 35);
    $w_embed = 'WORD';
    HeaderPdf(substr($TP,strrpos($TP,'-')+1),$w_pag);
    if ($w_filtro>'') ShowHTML($w_filtro);
  } elseif ($p_tipo=='EXCEL') {
    $w_embed = 'WORD';
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 60: 35);
    HeaderExcel($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,substr($TP,strrpos($TP,'-')+1),$w_pag);
    if ($w_filtro>'') ShowHTML($w_filtro);
  } else {
    $w_embed = 'HTML';
    Cabecalho();
    head();
    if ($O=='P') {
      ScriptOpen('Javascript');
      CheckBranco();
      FormataData();
      SaltaCampo();
      ValidateOpen('Validacao');
      Validate('p_chave','Almoxarifado','SELECT','1','1','18','','1');
      Validate('p_ini_i','Início','DATA','1','10','10','','0123456789/');
      Validate('p_ini_f','Fim','DATA','1','10','10','','0123456789/');
      CompData('p_ini_i','Início','<=','p_ini_f','Fim');
      Validate('p_proponente','Material','','','2','60','1','');
      ValidateClose();
      ScriptClose();
    } else {
      ShowHTML('<TITLE>'.$w_TP.'</TITLE>');
    } 
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</HEAD>');
    if ($w_Troca>'') {
      // Se for recarga da página
      BodyOpen('onLoad=\'document.Form.'.$w_Troca.'.focus();\'');
    } else {
      BodyOpenClean('onLoad=this.focus();');
    } 

    if ($O=='L') {
      CabecalhoRelatorio($w_cliente,substr($TP,strrpos($TP,'-')+1),4);
      if ($w_filtro>'') ShowHTML($w_filtro);
    } else {
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
      ShowHTML('<HR>');
    } 
  } 

  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L' || $w_embed == 'WORD') {
    if ((strpos(upper($R),'GR_'))===false && $P1!=6 && $w_embed!='WORD') {
      ShowHTML('<tr><td><a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">'.((strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) ? '<font color="#BC5100"><u>F</u>iltrar (Ativo)</font>' : '<u>F</u>iltrar (Inativo)').'</a>');
    }
    $i    = 0;
    $tipo = false;
    if (count($RS1)==0) {
      ShowHTML('<tr><td align="center"><hr/><b>Não foram encontrados registros</b></td></tr>');
    } else {
      $w_tot_ent  = 0;
      $w_tot_sai  = 0;
      $colspan    = 6;
      $w_atual    = '';
      $w_atual_qe = 0;
      $w_atual_qs = 0;
      $w_atual_ve = 0;
      $w_atual_vs = 0;
      foreach ($RS1 as $row) {
        if ($i==0) {
          $w_cor = $conTableBgColor;
          if (nvl($p_pais,0)!=f($row,'sq_tipo_material')) $tipo = true;
          ShowHTML('<tr><td align="center">');
          ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
          ShowHTML('        <tr bgcolor="#DCDCDC" align="center">');
          if ($tipo) {
            $colspan++;
            ShowHTML('          <td rowspan="2"><b>Tipo de material</b></td>');
          }
          ShowHTML('          <td rowspan="2"><b>Material</b></td>');
          ShowHTML('          <td rowspan="2"><b>U.M.</b></td>');
          ShowHTML('          <td colspan="3"><b>Quantitativo</b></td>');
          ShowHTML('          <td colspan="3"><b>Financeiro</b></td>');
          ShowHTML('        </tr>');
          ShowHTML('        <tr bgcolor="#DCDCDC" align="center">');
          ShowHTML('          <td><b>Entradas</b></td>');
          ShowHTML('          <td><b>Saídas</b></td>');
          ShowHTML('          <td><b>Saldo</b></td>');
          ShowHTML('          <td><b>Entradas</b></td>');
          ShowHTML('          <td><b>Saídas</b></td>');
          ShowHTML('          <td><b>Saldo</b></td>');
          ShowHTML('        </tr>');
          $i++;
        }
        if ($w_atual<>f($row,'nm_material')) {
          if ($w_atual_qe || $w_atual_qs) {
            ShowHTML('          <td align="center">'.formatNumber($w_atual_qe,0).'</td>');
            ShowHTML('          <td align="center">'.formatNumber($w_atual_qs,0).'</td>');
            ShowHTML('          <td align="center">'.formatNumber($w_atual_qe-$w_atual_qs,0).'</td>');
            ShowHTML('          <td align="right">'.formatNumber($w_atual_ve).'</td>');
            ShowHTML('          <td align="right">'.formatNumber($w_atual_vs).'</td>');
            ShowHTML('          <td align="right">'.formatNumber($w_atual_ve-$w_atual_vs).'</td>');
            ShowHTML('        </tr>');
            $w_atual_qe = 0;
            $w_atual_qs = 0;
            $w_atual_ve = 0;
            $w_atual_vs = 0;
          }
          ShowHTML('        <tr bgcolor="'.$w_cor.'" valign="top">');
          if ($tipo) ShowHTML('          <td>'.f($row,'nm_tipo_completo').'</td>');
          ShowHTML('          <td>'.(($w_embed=='WORD') ? f($row,'nm_material') : ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nm_material'),f($row,'sq_material'),$TP,null)).'</td>');
          ShowHTML('          <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>');
          $w_atual = f($row,'nm_material');
        }
        if (nvl(f($row,'quantidade_pedida'),0)==0) {
          $w_tot_ent += f($row,'tot_entrada');
          $w_atual_qe += f($row,'qt_entrada');
          $w_atual_ve += f($row,'tot_entrada');
        } else {
          $w_tot_sai += f($row,'vl_saida');
          $w_atual_qs += f($row,'quantidade_entregue');
          $w_atual_vs += f($row,'vl_saida');
        }
      }
      if (count($RS1)>1) {
        if ($w_atual_qe || $w_atual_qs) {
          // Trata o último registro
          ShowHTML('          <td align="center">'.formatNumber($w_atual_qe,0).'</td>');
          ShowHTML('          <td align="center">'.formatNumber($w_atual_qs,0).'</td>');
          ShowHTML('          <td align="center">'.formatNumber($w_atual_qe-$w_atual_qs,0).'</td>');
          ShowHTML('          <td align="right">'.formatNumber($w_atual_ve,0).'</td>');
          ShowHTML('          <td align="right">'.formatNumber($w_atual_vs).'</td>');
          ShowHTML('          <td align="right">'.formatNumber($w_atual_ve-$w_atual_vs).'</td>');
          ShowHTML('        </tr>');
        }
        ShowHTML('        <tr bgcolor="#DCDCDC" align="right">');
        ShowHTML('          <td colspan='.($colspan-1).' align="center"><b>TOTAIS NO PERÍODO DE '.$p_ini_i.' a '.$p_ini_f.'</b>&nbsp;</td>');
        ShowHTML('          <td><b>'.formatNumber($w_tot_ent).'</b></td>');
        ShowHTML('          <td><b>'.formatNumber($w_tot_sai).'</b></td>');
        ShowHTML('          <td><b>'.formatNumber($w_tot_ent - $w_tot_sai).'</b></td>');
        ShowHTML('        </tr>');
      }
    }
  } elseif ($O=='P') {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML(montaFiltro('POST',true));
    // Exibe parâmetros de apresentação
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    ShowHTML('         <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Critérios de Busca</td>');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('      <tr>');
    SelecaoAlmoxarifado('Al<u>m</u>oxarifado:','M', 'Selecione o almoxarifado onde o material será armazenado.', &$p_chave,'p_chave',null,'onChange="document.Form.O.value=\'P\'; document.Form.w_troca.value=\'p_pais\'; document.Form.submit();"',1);
    ShowHTML('     <td><b><u>P</u>eríodo de análise:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="D" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
    ShowHTML('      <tr>');
    selecaoTipoMatServSubord('<u>T</u>ipo de material:','S','Selecione o grupo/subgrupo de material/serviço desejado.',$p_chave,$p_pais,'p_pais','ALMOXARIFADO',null,2);
    ShowHTML('      </tr>');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td><b><U>M</U>aterial:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="60" value="'.$p_proponente.'"></td>');
    SelecaoClasseCheck('Recuperar classes:','S',null,$p_fase,$P2,'p_fase[]','CONSUMO',null);
    ShowHTML('   <tr valign="top">');
    MontaRadioNS('<b>Apenas disponíveis para pedidos internos?</b>',$p_ativo,'p_ativo');
    ShowHTML('    <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('    <tr><td align="center" colspan="3">');
    ShowHTML('          <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
    ShowHTML('        </td>');
    ShowHTML('    </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
    ShowHTML('</table>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 

  ShowHTML('</table>');
  if($p_tipo == 'PDF')  RodapePdf();
  else                  Rodape();
}

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);

  switch ($par) {
  case 'INVENTARIO': Inventario();  break;
  case 'ANALISE':    Analise();  break;
  case 'ENTRADA':    Entrada();     break;
  case 'SAIDA':      Saida();       break;
  case 'MAPA':       Mapa();        break;
  case 'MAPASINT':   MapaSint();    break;
  default:
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=this.focus();');
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    Estrutura_Texto_Abre();
    ShowHTML('<div align=center><br><br><br><br><br><br><br><br><br><br><b>Sem dados de fechamento mensal para processamento desta opção.</b><br><br><br><br><br><br><br><br><br><br></div>');
    Estrutura_Texto_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Rodape();
    break;
  } 
} 
?>
