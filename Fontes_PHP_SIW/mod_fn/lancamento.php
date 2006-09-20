<?
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
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'classes/sp/db_getBenef.php');
include_once($w_dir_volta.'classes/sp/db_getLancamentoDoc.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAcesso.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteData.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/db_getImpostoIncid.php');
include_once($w_dir_volta.'classes/sp/db_getImpostoDoc.php');
include_once($w_dir_volta.'classes/sp/db_getCountryData.php');
include_once($w_dir_volta.'classes/sp/db_getRegionData.php');
include_once($w_dir_volta.'classes/sp/db_getStateData.php');
include_once($w_dir_volta.'classes/sp/db_getCityData.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/dml_putFinanceiroGeral.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoOutra.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoDoc.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putFinanceiroConc.php');
include_once($w_dir_volta.'funcoes/selecaoTipoLancamento.php');
include_once($w_dir_volta.'funcoes/selecaoFormaPagamento.php');
include_once($w_dir_volta.'funcoes/selecaoAcordoParcela.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoAcordo.php');
include_once($w_dir_volta.'funcoes/selecaoTipoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');
include_once($w_dir_volta.'funcoes/selecaoFase.php');
include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');
include_once($w_dir_volta.'funcoes/selecaoTipoDocumento.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoRegiao.php');
include_once($w_dir_volta.'funcoes/selecaoEstado.php');
include_once($w_dir_volta.'funcoes/selecaoCidade.php');
include_once($w_dir_volta.'funcoes/selecaoBanco.php');
include_once($w_dir_volta.'funcoes/selecaoAgencia.php');
include_once($w_dir_volta.'funcoes/selecaoSexo.php');
include_once('visuallancamento.php');
include_once('validalancamento.php');
// =========================================================================
//  /lancamento.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Gerencia as rotinas relativas ao controle de lan�amentos financeiros
// Mail     : celso@sbpi.com.br
// Criacao  : 14/07/2006 13:30
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
$w_assinatura   = strtoupper($_REQUEST['w_assinatura']);
$w_pagina       = 'lancamento.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_fn/';
$w_troca        = $_REQUEST['w_troca'];
if (!(strpos($SG,'ANEXO')===false) || !(strpos($SG,'PARC')===false) || !(strpos($SG,'REPR')===false)) {
  if ((strpos('IG',$O)===false) && $_REQUEST['w_chave_aux']=='') $O='L';
} elseif (!(strpos($SG,'ENVIO')===false)) {
    $O='V';
} elseif ($O=='') {
  // Se for acompanhamento, entra na filtragem
  if ($P1==3) $O='P'; else $O='L';
} 
switch ($O) {
  case 'I': $w_TP=$TP.' - Inclus�o';    break;
  case 'A': $w_TP=$TP.' - Altera��o';   break;
  case 'E': $w_TP=$TP.' - Exclus�o';    break;
  case 'G': $w_TP=$TP.' - Gerar';       break;
  case 'P': $w_TP=$TP.' - Filtragem';   break;
  case 'C': $w_TP=$TP.' - C�pia';       break;
  case 'V': $w_TP=$TP.' - Envio';       break;
  case 'H': $w_TP=$TP.' - Heran�a';     break;
  default:  $w_TP=$TP.' - Listagem';    break;
} 
// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_copia        = $_REQUEST['w_copia'];
$p_projeto      = strtoupper($_REQUEST['p_projeto']);
$p_ativo        = strtoupper($_REQUEST['p_ativo']);
$p_solicitante  = strtoupper($_REQUEST['p_solicitante']);
$p_prioridade   = strtoupper($_REQUEST['p_prioridade']);
$p_unidade      = strtoupper($_REQUEST['p_unidade']);
$p_proponente   = strtoupper($_REQUEST['p_proponente']);
$p_ordena       = strtolower($_REQUEST['p_ordena']);
$p_ini_i        = strtoupper($_REQUEST['p_ini_i']);
$p_ini_f        = strtoupper($_REQUEST['p_ini_f']);
$p_fim_i        = strtoupper($_REQUEST['p_fim_i']);
$p_fim_f        = strtoupper($_REQUEST['p_fim_f']);
$p_atraso       = strtoupper($_REQUEST['p_atraso']);
$p_chave        = strtoupper($_REQUEST['p_chave']);
$p_objeto       = strtoupper($_REQUEST['p_objeto']);
$p_pais         = strtoupper($_REQUEST['p_pais']);
$p_regiao       = strtoupper($_REQUEST['p_regiao']);
$p_uf           = strtoupper($_REQUEST['p_uf']);
$p_cidade       = strtoupper($_REQUEST['p_cidade']);
$p_usu_resp     = strtoupper($_REQUEST['p_usu_resp']);
$p_uorg_resp    = strtoupper($_REQUEST['p_uorg_resp']);
$p_palavra      = strtoupper($_REQUEST['p_palavra']);
$p_prazo        = strtoupper($_REQUEST['p_prazo']);
$p_fase         = explodeArray($_REQUEST['p_fase']);
$p_sqcc         = strtoupper($_REQUEST['p_sqcc']);
// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$RS = db_getLinkSubMenu::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu = 'Existe';
} else {
  $w_submenu = '';
}
// Recupera a configura��o do servi�o
if ($P2>0) {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,$P2);
} else {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,$w_menu);
}
// Se for sub-menu, pega a configura��o do pai
if (f($RS_Menu,'ultimo_nivel')=='S') { 
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 
Main();
FechaSessao($dbms);
exit;
// =========================================================================
// Rotina de visualiza��o resumida dos registros
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_tipo = $_REQUEST['w_tipo'];
  if ($O=='L') {
    if ((!(strpos(strtoupper($R),'GR_')===false)) || (!(strpos(strtoupper($R),'PROJETO')===false)) || ($w_tipo=='WORD')) {
      $w_filtro='';
      if ($p_projeto>'') {
        $RS = db_getSolicData::getInstanceOf($dbms,$p_projeto,'PJGERAL');
        if ($w_tipo=='WORD') {
          $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Projeto <td>[<b>'.f($RS,'titulo').'</b>]';
        } else {
          $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Projeto <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informa��es do projeto." target="_blank">'.f($RS,'titulo').'</a></b>]';
        }
      } 
    }
    if ($p_sqcc>'') {
      $RS = db_getCCData::getInstanceOf($dbms,$p_sqcc);
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Classifica��o <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_chave>'') $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Atividade n� <td>[<b>'.$p_chave.'</b>]';
    if ($p_prazo>'') $w_filtro = $w_filtro.' <tr valign="top"><td align="right">Prazo para conclus�o at�<td>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]';
    if ($p_solicitante>'') {
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Respons�vel <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_unidade>'') {
      $RS = db_getUorgData::getInstanceOf($dbms,$p_unidade);
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Unidade respons�vel <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_usu_resp>'') {
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_usu_resp,null,null);
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Executor <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_uorg_resp>'') {
      $RS = db_getUorgData::getInstanceOf($dbms,$p_uorg_resp);
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Unidade atual <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_pais>'') {
      $RS = db_getCountryData::getInstanceOf($dbms,$p_pais);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Pa�s <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_regiao>'') {
      $RS = db_getRegionData::getInstanceOf($dbms,$p_regiao);
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Regi�o <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_uf>'') {
      $RS = db_getStateData::getInstanceOf($dbms,$p_pais,$p_uf);
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Estado <td>[<b>'.f($RS,'nome').'</b>]';
    }
    if ($p_cidade>'') {
      $RS = db_getCityData::getInstanceOf($dbms,$p_cidade);
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Cidade <td>[<b>'.f($RS,'nome').'</b>]';
    }
    if ($p_prioridade>'') $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Prioridade <td>[<b>'.RetornaPrioridade($p_prioridade).'</b>]';
    if ($p_proponente>'') $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Parceria externa <td>[<b>'.$p_proponente.'</b>]';
    if ($p_objeto>'')     $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Detalhamento <td>[<b>'.$p_objeto.'</b>]';
    if ($p_palavra>'')    $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Respons�vel <td>[<b>'.$p_palavra.'</b>]';
    if ($p_ini_i>'')      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Data recebimento <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
    if ($p_fim_i>'')      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Limite conclus�o <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]';
    if ($p_atraso=='S')   $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Situa��o <td>[<b>Apenas atrasadas</b>]';
    if ($w_filtro>'')     $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>';
  }
  if ($w_copia>'') {
    // Se for c�pia, aplica o filtro sobre todas as demandas vis�veis pelo usu�rio
    $RS = db_getSolicList::getInstanceOf($dbms,f($RS_Menu,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, null, null, null);
  } else {
    $RS = db_getSolicList::getInstanceOf($dbms,f($RS_Menu,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P1,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, null, null, null);
  }
  if ($p_ordena>'') {
    $lista = explode(',',str_replace(' ',',',$p_ordena));
    $RS = SortArray($RS,$lista[0],$lista[1],'vencimento','asc');
  } else {
    $RS = SortArray($RS,'nm_pessoa','asc','vencimento','desc');
  } 
  if ($w_tipo=='WORD') {
    HeaderWord(); 
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    Estrutura_CSS($w_cliente);
    if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="300; URL=../'.MontaURL('MESA').'">');
    ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de atividades</TITLE>');
    ScriptOpen('Javascript');
    CheckBranco();
    FormataData();
    ValidateOpen('Validacao');
    if (!(strpos('CP',$O)===false)) {
      if ($P1!=1 || $O=='C') {
        // Se n�o for cadastramento ou se for c�pia
        Validate('p_chave','N�mero do lan�amento','','','1','18','','0123456789');
        Validate('p_prazo','Dias para a data limite','','','1','2','','0123456789');
        Validate('p_proponente','Parcerias externas','','','2','90','1','');
        Validate('p_objeto','Assunto','','','2','90','1','1');
        Validate('p_palavra','Palavras-chave','','','2','90','1','1');
        Validate('p_ini_i','Recebimento inicial','DATA','','10','10','','0123456789/');
        Validate('p_ini_f','Recebimento final','DATA','','10','10','','0123456789/');
        ShowHTML('  if ((theForm.p_ini_i.value != \'\' && theForm.p_ini_f.value == \'\') || (theForm.p_ini_i.value == \'\' && theForm.p_ini_f.value != \'\')) {');
        ShowHTML('     alert (\'Informe ambas as datas de recebimento ou nenhuma delas!\');');
        ShowHTML('     theForm.p_ini_i.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        CompData('p_ini_i','Recebimento inicial','<=','p_ini_f','Recebimento final');
        Validate('p_fim_i','Conclus�o inicial','DATA','','10','10','','0123456789/');
        Validate('p_fim_f','Conclus�o final','DATA','','10','10','','0123456789/');
        ShowHTML('  if ((theForm.p_fim_i.value != \'\' && theForm.p_fim_f.value == \'\') || (theForm.p_fim_i.value == \'\' && theForm.p_fim_f.value != \'\')) {');
        ShowHTML('     alert (\'Informe ambas as datas de conclus�o ou nenhuma delas!\');');
        ShowHTML('     theForm.p_fim_i.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        CompData('p_fim_i','Conclus�o inicial','<=','p_fim_f','Conclus�o final');
      } 
      Validate('P4','Linhas por p�gina','1','1','1','4','','0123456789');
    } 
    ValidateClose();
    ScriptClose();
    ShowHTML('</HEAD>');
  }
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
  elseif ($O=='I') BodyOpen('onLoad=\'document.Form.w_smtp_server.focus();\'');
  elseif ($O=='A') BodyOpen('onLoad=\'document.Form.w_nome.focus();\'');
  elseif ($O=='E') BodyOpen('onLoad=\'document.Form.w_assinatura.focus();\'');
  elseif (!(strpos('CP',$O)===false)) BodyOpen('onLoad=\'document.Form.p_projeto.focus();\'');
  else             BodyOpenClean(null);
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  if ($w_filtro>'') ShowHTML($w_filtro);
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td>');
    if ($P1==1) {
      // Se for cadastramento e n�o for resultado de busca para c�pia
      ShowHTML('<tr><td>');
      if (!(strpos($SG,'CONT')===false)) ShowHTML('    <a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.'Buscaparcela&R='.$w_pagina.$par.'&O=P&SG='.$SG.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
      else                               ShowHTML('    <a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.$SG.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    } 
    if ((strpos(strtoupper($R),'GR_')===false) && (strpos(strtoupper($R),'LANCAMENTO')===false) && (Nvl($R,'')>'')) {
      if ($w_copia>'') {
        // Se for c�pia
        if (MontaFiltro('GET')>'') ShowHTML('                         <a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        else                       ShowHTML('                         <a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
      } else {
        if (MontaFiltro('GET')>'') ShowHTML('                         <a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        else                       ShowHTML('                         <a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
      } 
    } 
    ShowHTML('    <td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('C�digo','codigo_interno').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Pessoa','nm_pessoa_resumido').'</td>');
    if (!(strpos($SG,'CONT')===false))  ShowHTML('          <td><b>'.LinkOrdena('Contrato','cd_acordo').'</td>');
    else                                ShowHTML('          <td><b>'.'Classif./Projeto'.'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Vencimento','vencimento').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Valor','valor').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Fase','nm_tramite').'</td>');
    if ($_SESSION['INTERNO']=='S') ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial=0;
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td nowrap>');
        if (Nvl(f($row,'conclusao'),'nulo')=='nulo') {
          if (f($row,'fim')<addDays(time(),-1)) {
            ShowHTML('           <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">');
          } elseif (f($row,'aviso_prox_conc')=='S' && (f($row,'aviso')<=addDays(time(),-1))) {
            ShowHTML('           <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">');
          } else {
            ShowHTML('           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">');
          } 
        } else {
          if (f($row,'vencimento') < Nvl(f($row,'quitacao'),f($row,'vencimento')))
            ShowHTML('           <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">');
          else
            ShowHTML('           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">');
        } 
        ShowHTML('        <A class="hl" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="'.f($row,'obj_acordo').' ::> '.f($row,'descricao').'">'.f($row,'codigo_interno').'&nbsp;</a>');
        if (Nvl(f($row,'pessoa'),'nulo')!='nulo') {
          ShowHTML('        <td>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'pessoa'),$TP,f($row,'nm_pessoa_resumido')).'</td>');
        } else {
          ShowHTML('        <td align="center">---</td>');
        }
        if (!(strpos($SG,'CONT')===false)) {    
          ShowHTML('        <td><A class="hl" HREF="'.'mod_ac/contratos.php?par=Visual&O=L&w_chave='.f($row,'sq_solic_pai').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=GC'.substr($SG,2,1).'CAD" title="Exibe as informa��es do projeto." target="_blank">'.f($row,'cd_acordo').' ('.f($row,'or_parcela').')</a></td>');
        } else {
          if (Nvl(f($row,'sq_solic_pai'),0)==0) ShowHTML('        <td>'.f($row,'nm_cc').'</td>');
          else                                  ShowHTML('        <td><A class="hl" HREF="'.'projeto.php?par=Visual&O=L&w_chave='.f($row,'sq_solic_pai').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informa��es do projeto." target="_blank">'.f($row,'nm_projeto').'</a></td>');
        } 
        ShowHTML('        <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row,'vencimento')),'-').'</td>');
        ShowHTML('        <td align="right">'.number_format(f($row,'valor'),2,',','.').'&nbsp;</td>');
        $w_parcial = $w_parcial + Nvl(f($row,'valor'),0);
        ShowHTML('        <td nowrap>'.f($row,'nm_tramite').'</td>');
        if ($_SESSION['INTERNO']=='S') {
          ShowHTML('        <td align="top" nowrap>');
          if ($P1!=3) {
            // Se n�o for acompanhamento
            if ($P1==1) {
              // Se for cadastramento
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informa��es cadastrais do lan�amento">Alterar</A>&nbsp');
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclus�o do lan�amento.">Excluir</A>&nbsp');
              ShowHTML('          <A class="hl" HREF="javascript:location.href=this.location.href;" onClick="window.open(\''.$w_pagina.'OutraParte&R='.$w_pagina.$par.'&O=A&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Pessoa'.'&SG='.substr($SG,0,3).'OUTRAP\',\'Pessoa\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Informa dados da pessoa associada ao lan�amento.">Pessoa</A>&nbsp');
              ShowHTML('          <A class="hl" HREF="javascript:location.href=this.location.href;" onClick="window.open(\''.$w_pagina.'Documento&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Pessoa'.'&SG=DOCUMENTO\',\'Pessoa\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Informa documentos e comprovantes associados ao lan�amento.">Docs</A>&nbsp');
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o lan�amento para outro respons�vel.">Enviar</A>&nbsp');
            } elseif ($P1==2) {
               // Se for execu��o
              $l_rs_solic   = db_getSolicData::getInstanceOf($dbms,f($row,'sq_siw_solicitacao'),$SG);
              $l_rs_tramite = db_getTramiteData::getInstanceOf($dbms,f($l_rs_solic,'sq_siw_tramite'));
              if ($w_usuario == f($row,'executor')) {
                ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anota��es para o lan�amento, sem envi�-la.">Anotar</A>&nbsp');
                if (Nvl(f($l_rs_tramite,'sigla'),'---')=='EE') {
                  ShowHTML('          <A class="hl" HREF="javascript:location.href=this.location.href;" onClick="window.open(\''.$w_pagina.'OutraParte&R='.$w_pagina.$par.'&O=A&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Pessoa'.'&SG='.substr($SG,0,3).'OUTRAP\',\'Pessoa\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Informa dados da pessoa associada ao lan�amento.">Pessoa</A>&nbsp');
                  ShowHTML('          <A class="hl" HREF="javascript:location.href=this.location.href;" onClick="window.open(\''.$w_pagina.'Documento&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Pessoa'.'&SG=DOCUMENTO\',\'Pessoa\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Informa documentos e comprovantes associados ao lan�amento.">Docs</A>&nbsp');
                } 
                ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o lan�amento para outro respons�vel.">Enviar</A>&nbsp');
                if (Nvl(f($l_rs_tramite,'sigla'),'---')=='EE')
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Liquida��o do lan�amento.">Liquidar</A>&nbsp');
              } else {
                ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o lan�amento para outro respons�vel.">Enviar</A>&nbsp');
              } 
            } 
          } else {
            if (Nvl(f($row,'solicitante'),0)    == $w_usuario || 
                Nvl(f($row,'titular'),0)        == $w_usuario || 
                Nvl(f($row,'substituto'),0)     == $w_usuario || 
                Nvl(f($row,'tit_exec'),0)       == $w_usuario || 
                Nvl(f($row,'subst_exec'),0)     == $w_usuario)
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o lan�amento para outro respons�vel.">Enviar</A>&nbsp');
            else
              ShowHTML('          ---&nbsp');
          } 
          ShowHTML('        </td>');
        } 
        ShowHTML('      </tr>');
      } 
      if ($P1!=1 && $P1!=2) {
        // Se n�o for cadastramento nem mesa de trabalho
        // Coloca o valor parcial apenas se a listagem ocupar mais de uma p�gina
        if (ceil(count($RS)/$P4)>1) {
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
          ShowHTML('          <td colspan=5 align="right"><b>Total desta p�gina&nbsp;</td>');
          ShowHTML('          <td align="right"><b>'.number_format($w_parcial,2,',','.').'&nbsp;</td>');
          ShowHTML('          <td colspan=2>&nbsp;</td>');
          ShowHTML('        </tr>');
        } 
        // Se for a �ltima p�gina da listagem, soma e exibe o valor total
        if ($P3==ceil(count($RS)/$P4)) {
          foreach($RS as $row) {
            if (f($row,'sg_tramite')=='AT') $w_total = $w_total+f($row,'custo_real');
            else                            $w_total = $w_total+f($row,'valor');
          } 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
          ShowHTML('          <td colspan=5 align="right"><b>Total da listagem&nbsp;</td>');
          ShowHTML('          <td align="right"><b>'.number_format($w_total,2,',','.').'&nbsp;</td>');
          ShowHTML('          <td colspan=2>&nbsp;</td>');
          ShowHTML('        </tr>');
        } 
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($w_tipo!='WORD') {
      ShowHTML('<tr><td align="center" colspan=3>');
      if ($R>'') {
        MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia,ceil(count($RS)/$P4),$P3,$P4,count($RS));
      } else {
        MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia,ceil(count($RS)/$P4),$P3,$P4,count($RS));
      } 
      ShowHTML('</tr>');
    } 
  } elseif(!(strpos('CP',$O)===false)) {
    if ($O=='C') {
      // Se for c�pia
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Para selecionar o lan�amento que deseja copiar, informe nos campos abaixo os crit�rios de sele��o e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    } else {
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    } 
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td valign="top"><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O=='C') {
      // Se for c�pia, cria par�metro para facilitar a recupera��o dos registros
      ShowHTML('<INPUT type="hidden" name="w_copia" value="OK">');
    } 
    // Recupera dados da op��o Projetos
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('      <tr>');
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PJCAD');
    SelecaoProjeto('Pr<u>o</u>jeto:','O','Selecione o projeto do lan�amento na rela��o.',$p_projeto,$w_usuario,f($RS,'sq_menu'),'p_projeto',$w_menu,null);
    ShowHTML('      </tr>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    if ($P1!=1 || $O=='C') {
      // Se n�o for cadastramento ou se for c�pia
      if (f($RS_Menu,'solicita_cc')=='S') {
        ShowHTML('      <tr>');
        SelecaoCC('C<u>l</u>assifica��o:','L','Selecione a classifica��o desejada.',$p_sqcc,null,'p_sqcc','SIWSOLIC');
        ShowHTML('      </tr>');
      } 
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td><b>N�mero da <U>d</U>emanda:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="p_chave" size="18" maxlength="18" value="'.$p_chave.'"></td>');
      ShowHTML('          <td><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="sti" type="text" name="p_prazo" size="2" maxlength="2" value="'.$p_prazo.'"></td>');
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Respo<u>n</u>s�vel:','N','Selecione o respons�vel pelo monitoramento do lan�amento na rela��o.',$p_solicitante,null,'p_solicitante','USUARIOS');
      SelecaoUnidade('<U>S</U>etor respons�vel:','S',null,$p_unidade,null,'p_unidade',null,null);
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Respons�vel atua<u>l</u>:','L','Selecione o respons�vel atual pelo lan�amento na rela��o.',$p_usu_resp,null,'p_usu_resp','USUARIOS');
      SelecaoUnidade('<U>S</U>etor atual:','S','Selecione a unidade onde o lan�amento se encontra na rela��o.',$p_uorg_resp,null,'p_uorg_resp',null,null);
      ShowHTML('      <tr>');
      SelecaoPais('<u>P</u>a�s:','P',null,$p_pais,null,'p_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_regiao\'; document.Form.submit();"');
      SelecaoRegiao('<u>R</u>egi�o:','R',null,$p_regiao,$p_pais,'p_regiao',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
      ShowHTML('      <tr>');
      SelecaoEstado('E<u>s</u>tado:','S',null,$p_uf,$p_pais,'N','p_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_cidade\'; document.Form.submit();"');
      SelecaoCidade('<u>C</u>idade:','C',null,$p_cidade,$p_pais,$p_uf,'p_cidade',null,null);
      ShowHTML('      <tr>');
      ShowHTML('          <td><b>O<U>b</U>jeto:<br><INPUT ACCESSKEY="B" '.$w_Disabled.' class="sti" type="text" name="p_objeto" size="25" maxlength="90" value="'.$p_objeto.'"></td>');
      ShowHTML('      <tr>');
      ShowHTML('          <td><b>In�<u>c</u>io vig�ncia entre:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);"></td>');
      ShowHTML('          <td><b>Fi<u>m</u> vig�ncia entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);"></td>');
      if ($O!='C') {
        // Se n�o for c�pia
        ShowHTML('      <tr>');
        ShowHTML('          <td><b>Exibe lan�amentos vencidos?</b><br>');
        if ($p_atraso=='S') ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_atraso" value="S" checked> Sim <br><input '.$w_Disabled.' class="str" class="str" type="radio" name="p_atraso" value="N"> N�o');
        else                ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' class="str" class="str" type="radio" name="p_atraso" value="N" checked> N�o');
        SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase',null,null);
      } 
    } 
    ShowHTML('      <tr>');
    ShowHTML('          <td><b><U>O</U>rdena��o por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="sts" name="p_ordena" size="1">');
    if     ($p_ordena=='ASSUNTO')       ShowHTML('          <option value="assunto" SELECTED>Objeto<option value="vencimento">In�cio vig�ncia<option value="">T�rmino vig�ncia<option value="nm_tramite">Fase atual<option value="pessoa">Pessoa<option value="proponente">Projeto');
    elseif ($p_ordena=='vencimento')    ShowHTML('          <option value="assunto">Objeto<option value="vencimento" SELECTED>In�cio vig�ncia<option value="">T�rmino vig�ncia<option value="nm_tramite">Fase atual<option value="pessoa">Pessoa<option value="proponente">Projeto');
    elseif ($p_ordena=='FIM')           ShowHTML('          <option value="assunto">Objeto<option value="vencimento">In�cio vig�ncia<option value="">T�rmino vig�ncia<option value="nm_tramite" SELECTED>Fase atual<option value="pessoa">Pessoa<option value="proponente">Projeto');
    elseif ($p_ordena=='NM_pessoa')     ShowHTML('          <option value="assunto">Objeto<option value="vencimento">In�cio vig�ncia<option value="">T�rmino vig�ncia<option value="nm_tramite">Fase atual<option value="pessoa" SELECTED>Pessoa<option value="proponente">Projeto');
    elseif ($p_ordena=='NM_PROJETO')    ShowHTML('          <option value="assunto">Objeto<option value="vencimento">In�cio vig�ncia<option value="">T�rmino vig�ncia<option value="nm_tramite">Fase atual<option value="pessoa">Pessoa<option value="proponente" SELECTED>Projeto');
    else                                ShowHTML('          <option value="assunto">Objeto<option value="vencimento">In�cio vig�ncia<option value="" SELECTED>T�rmino vig�ncia<option value="nm_tramite">Fase atual<option value="pessoa">Pessoa<option value="proponente">Projeto');
    ShowHTML('          </select></td>');
    ShowHTML('          <td><b><U>L</U>inhas por p�gina:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="sti" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    if ($O=='C') {
      // Se for c�pia
      ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';" name="Botao" value="Abandonar c�pia">');
    } else {
      ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';" name="Botao" value="Remover filtro">');
    }
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 
// =========================================================================
// Rotina dos dados gerais
// -------------------------------------------------------------------------
function Geral() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave              = $_REQUEST['w_chave'];
  $w_sq_tipo_lancamento = $_REQUEST['w_sq_tipo_lancamento'];
  $w_readonly           = '';
  $w_erro               = '';
  // Verifica se h� necessidade de recarregar os dados da tela a partir
  // da pr�pria tela (se for recarga da tela) ou do banco de dados (se n�o for inclus�o)
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_pessoa               = $_REQUEST['w_pessoa'];
    $w_tipo_pessoa          = $_REQUEST['w_tipo_pessoa'];
    $w_nm_tipo_pessoa       = $_REQUEST['w_nm_tipo_pessoa'];
    $w_sq_acordo_parcela    = $_REQUEST['w_sq_acordo_parcela'];
    $w_sq_forma_pagamento   = $_REQUEST['w_sq_forma_pagamento'];
    $w_forma_atual          = $_REQUEST['w_forma_atual'];
    $w_vencimento_atual     = $_REQUEST['w_vencimento_atual'];
    $w_sq_tipo_lancamento   = $_REQUEST['w_sq_tipo_lancamento'];
    $w_observacao           = $_REQUEST['w_observacao'];
    $w_aviso                = $_REQUEST['w_aviso'];
    $w_dias                 = $_REQUEST['w_dias'];
    $w_codigo_interno       = $_REQUEST['w_codigo_interno'];
    $w_chave                = $_REQUEST['w_chave'];
    $w_chave_pai            = $_REQUEST['w_chave_pai'];
    $w_chave_aux            = $_REQUEST['w_chave_aux'];
    $w_sq_menu              = $_REQUEST['w_sq_menu'];
    $w_sq_unidade           = $_REQUEST['w_sq_unidade'];
    $w_sq_tramite           = $_REQUEST['w_sq_tramite'];
    $w_solicitante          = $_REQUEST['w_solicitante'];
    $w_cadastrador          = $_REQUEST['w_cadastrador'];
    $w_executor             = $_REQUEST['w_executor'];
    $w_descricao            = $_REQUEST['w_descricao'];
    $w_justificativa        = $_REQUEST['w_justificativa'];
    $w_emissao              = $_REQUEST['w_emissao'];
    $w_vencimento           = $_REQUEST['w_vencimento'];
    $w_inclusao             = $_REQUEST['w_inclusao'];
    $w_ultima_alteracao     = $_REQUEST['w_ultima_alteracao'];
    $w_conclusao            = $_REQUEST['w_conclusao'];
    $w_valor                = $_REQUEST['w_valor'];
    $w_opiniao              = $_REQUEST['w_opiniao'];
    $w_data_hora            = $_REQUEST['w_data_hora'];
    $w_pais                 = $_REQUEST['w_pais'];
    $w_uf                   = $_REQUEST['w_uf'];
    $w_cidade               = $_REQUEST['w_cidade'];
    $w_palavra_chave        = $_REQUEST['w_palavra_chave'];
    $w_sqcc                 = $_REQUEST['w_sqcc'];
  } else {
    if(!(strpos('AEV',$O)===false) || $w_copia>'') {
      // Recupera os dados do lan�amento
      if ($w_copia>'') $RS = db_getSolicData::getInstanceOf($dbms,$w_copia,$SG);
      else             $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
      if (count($RS)>0) {
        $w_sq_unidade           = f($RS,'sq_unidade');
        $w_observacao           = f($RS,'observacao');
        $w_aviso                = f($RS,'aviso_prox_conc');
        $w_dias                 = f($RS,'dias_aviso');
        $w_sq_acordo_parcela    = f($RS,'sq_acordo_parcela');
        $w_sq_tipo_lancamento   = f($RS,'sq_tipo_lancamento');
        $w_pessoa               = f($RS,'pessoa');
        $w_tipo_pessoa          = f($RS,'sq_tipo_pessoa');
        $w_nm_tipo_pessoa       = f($RS,'nm_tipo_pessoa');
        $w_sq_forma_pagamento   = f($RS,'sq_forma_pagamento');
        $w_forma_atual          = f($RS,'sq_forma_pagamento');
        $w_codigo_interno       = f($RS,'codigo_interno');
        $w_chave_pai            =f($RS,'sq_solic_pai');
        $w_chave_aux            =null;
        $w_sq_menu              =f($RS,'sq_menu');
        $w_sq_unidade           =f($RS,'sq_unidade');
        $w_sq_tramite           =f($RS,'sq_siw_tramite');
        $w_solicitante          =f($RS,'solicitante');
        $w_cadastrador          =f($RS,'cadastrador');
        $w_executor             =f($RS,'executor');
        $w_descricao            =f($RS,'descricao');
        $w_justificativa        =f($RS,'justificativa');
        $w_vencimento           =FormataDataEdicao(f($RS,'fim'));
        $w_vencimento_atual     =FormataDataEdicao(f($RS,'fim'));
        $w_inclusao             =f($RS,'inclusao');
        $w_ultima_alteracao     =f($RS,'ultima_alteracao');
        $w_conclusao            =f($RS,'conclusao');
        $w_opiniao              =f($RS,'opiniao');
        $w_data_hora            =f($RS,'data_hora');
        $w_sqcc                 =f($RS,'sq_cc');
        $w_pais                 =f($RS,'sq_pais');
        $w_uf                   =f($RS,'co_uf');
        $w_cidade               =f($RS,'sq_cidade_origem');
        $w_palavra_chave        =f($RS,'palavra_chave');
        $w_valor                =number_format(f($RS,'valor'),2,',','.');
      } 
    } 
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  // Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara,
  // tratando as particularidades de cada servi�o
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  FormataDataHora();
  FormataValor();
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    Validate('w_sq_tipo_lancamento','Tipo do lan�amento','SELECT',1,1,18,'','0123456789');
    Validate('w_descricao','Finalidade','1',1,5,2000,'1','1');
    Validate('w_vencimento','Vencimento','DATA',1,10,10,'','0123456789/');
    Validate('w_valor','Valor total do documento','VALOR','1',4,18,'','0123456789.,');
    Validate('w_chave_pai','Projeto','SELECT','',1,18,'','0123456789');
    Validate('w_sqcc','Classifica��o','SELECT','',1,18,'','0123456789');
    ShowHTML('  if (theForm.w_chave_pai.selectedIndex > 0 && theForm.w_sqcc.selectedIndex > 0) {');
    ShowHTML('     alert(\'Informe um projeto ou uma classifica��o. Voc� n�o pode escolher ambos!\');');
    ShowHTML('     theForm.w_chave_pai.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    ShowHTML('  if (theForm.w_chave_pai.selectedIndex == 0 && theForm.w_sqcc.selectedIndex == 0) {');
    ShowHTML('     alert(\'Informe um projeto ou uma classifica��o!\');');
    ShowHTML('     theForm.w_chave_pai.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'')                    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  elseif (!(strpos('EV',$O)===false)) BodyOpen('onLoad=\'document.focus()\';');
  else                                BodyOpen('onLoad=\'document.Form.w_sq_tipo_lancamento.focus()\';');
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($w_chave>'') ShowHTML('      <tr><td><font size="2"><b>'.$w_codigo_interno.' ('.$w_chave.')</b></td>');
  if (!(strpos('IAEV',$O)===false)) {
    if (Nvl($w_pais,'')=='') {
      // Carrega os valores padr�o para pa�s, estado e cidade
      $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
      $w_pais   = f($RS,'sq_pais');
      $w_uf     = f($RS,'co_uf');
      $w_cidade = Nvl(f($RS_Menu,'sq_cidade'),f($RS,'sq_cidade_padrao'));
    } 
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled=' DISABLED ';
      if ($O=='V') $w_Erro = Validacao($w_sq_solicitacao,$SG);
    }  
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('<INPUT type="hidden" name="w_cidade" value="'.$w_cidade.'">');
    ShowHTML('<INPUT type="hidden" name="w_solicitante" value="'.$_SESSION['SQ_PESSOA'].'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_unidade" value="'.f($RS_Menu,'sq_unid_executora').'">');
    ShowHTML('<INPUT type="hidden" name="w_forma_atual" value="'.$w_forma_atual.'">');
    ShowHTML('<INPUT type="hidden" name="w_vencimento_atual" value="'.$w_vencimento_atual.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_acordo_parcela" value="'.$w_sq_acordo_parcela.'">');
    ShowHTML('<INPUT type="hidden" name="w_aviso" value="S">');
    ShowHTML('<INPUT type="hidden" name="w_dias" value="3">');
    if (!(strpos('CONT',substr($SG,3))===false)) {
      ShowHTML('<INPUT type="hidden" name="w_descricao" value="'.$w_descricao.'">');
      ShowHTML('<INPUT type="hidden" name="w_tipo_pessoa" value="'.$w_tipo_pessoa.'">');
      ShowHTML('<INPUT type="hidden" name="w_chave_pai" value="'.$w_chave_pai.'">');
      ShowHTML('<INPUT type="hidden" name="w_sqcc" value="'.$w_sqcc.'">');
    } 
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><b>Identifica��o</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Os dados deste bloco ser�o utilizados para identifica��o do lan�amento, bem como para o controle de sua execu��o.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr>');
    SelecaoTipoLancamento('<u>T</u>ipo de lancamento:','T','Selecione na lista o tipo de lan�amento adequado.',$w_sq_tipo_lancamento,$w_cliente,'w_sq_tipo_lancamento',$SG,null);
    ShowHTML('      </tr>');
    if (!(strpos('CONT',substr($SG,3))===false)) ShowHTML('      <tr><td colspan=2>Finalidade:<br><b>'.$w_descricao.'</b></td>');
    else                               ShowHTML('      <tr><td colspan=2><b><u>F</u>inalidade:</b><br><textarea '.$w_Disabled.' accesskey="F" name="w_descricao" class="sti" ROWS=3 cols=75 title="Finalidade do lan�amento.">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('        <tr valign="top">');
    if ((strpos('CONT',substr($SG,3))===false)) SelecaoTipoPessoa('Lan�amento para pessoa:','T','Selecione na lista o tipo de pessoa associada a este lan�amento.',$w_tipo_pessoa,$w_cliente,'w_tipo_pessoa',null,null);
    if (substr($SG,0,3)=='FNR')     SelecaoFormaPagamento('<u>F</u>orma de recebimento:','F','Selecione na lista a forma de recebimento para este lan�amento.',$w_sq_forma_pagamento,$SG,'w_sq_forma_pagamento',null);
    elseif (substr($SG,0,3)=='FND') SelecaoFormaPagamento('<u>F</u>orma de pagamento:','F','Selecione na lista a forma de pagamento para este lan�amento.',$w_sq_forma_pagamento,$SG,'w_sq_forma_pagamento',null);
    ShowHTML('          <td><b><u>V</u>alor:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor total do documento."></td>');
    ShowHTML('              <td><b>Ven<u>c</u>imento:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_vencimento" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_vencimento,FormataDataEdicao(time())).'" onKeyDown="FormataData(this,event);"></td>');
    ShowHTML('          </table>');
    if ((strpos('CONT',substr($SG,3))===false)) {
      ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><b>Vincula��o</td></td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td>Selecione um projeto ou uma classifica��o para o lan�amento. Voc� deve escolher uma, e apenas uma das duas.</td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      // Recupera dados da op��o Projetos
      ShowHTML('      <tr>');
      $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PJCAD');
      SelecaoProjeto('Pr<u>o</u>jeto:','P','Selecione o projeto ao qual o lan�amento est� vinculado.',$w_chave_pai,$w_usuario,f($RS,'sq_menu'),'w_chave_pai','PJLISTCAD',null);
      ShowHTML('      </tr>');
      if (f($RS_Menu,'solicita_cc')=='S') {
        ShowHTML('          <tr>');
        SelecaoCC('C<u>l</u>assifica��o:','L','Selecione um dos itens relacionados.',$w_sqcc,null,'w_sqcc','SIWSOLIC');
        ShowHTML('          </tr>');
      } 
    } 
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
    // Verifica se poder� ser feito o envio da solicita��o, a partir do resultado da valida��o
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    //if ($O=='I') {
      $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
      ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$R.'&w_copia='.$w_copia.'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'\';" name="Botao" value="Cancelar">');
    //} 
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 
// =========================================================================
// Rotina de cadastramento da outra parte
// -------------------------------------------------------------------------
function OutraParte() {
  extract($GLOBALS);
  global $w_Disabled;
  if ($O=='') $O='P';
  $w_erro='';
  $w_chave          = $_REQUEST['w_chave'];
  $w_chave_aux      = $_REQUEST['w_chave_aux'];
  $w_cpf            = $_REQUEST['w_cpf'];
  $w_cnpj           = $_REQUEST['w_cnpj'];
  $w_sq_pessoa      = $_REQUEST['w_sq_pessoa'];
  $w_pessoa_atual   = $_REQUEST['w_pessoa_atual'];
  $RS1 = db_getSolicData::getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));
  if ($w_sq_pessoa=='' && (strpos($_REQUEST['botao'],'Selecionar')===false)) {
    $w_sq_pessoa    =f($RS1,'pessoa');
    $w_pessoa_atual =f($RS1,'pessoa');
  } elseif (strpos($_REQUEST['botao'],'Selecionar')===false) {
    $w_sq_banco         = f($RS1,'sq_banco');
    $w_sq_agencia       = f($RS1,'sq_agencia');
    $w_operacao         = f($RS1,'operacao_conta');
    $w_nr_conta         = f($RS1,'numero_conta');
    $w_sq_pais_estrang  = f($RS1,'sq_pais_estrang');
    $w_aba_code         = f($RS1,'aba_code');
    $w_swift_code       = f($RS1,'swift_code');
    $w_endereco_estrang = f($RS1,'endereco_estrang');
    $w_banco_estrang    = f($RS1,'banco_estrang');
    $w_agencia_estrang  = f($RS1,'agencia_estrang');
    $w_cidade_estrang   = f($RS1,'cidade_estrang');
    $w_informacoes      = f($RS1,'informacoes');
    $w_codigo_deposito  = f($RS1,'codigo_deposito');
  } 
  $w_forma_pagamento    = f($RS1,'sg_forma_pagamento');
  $w_tipo_pessoa        = f($RS1,'sq_tipo_pessoa');
  if (Nvl($w_sq_pessoa,0)==0)  $O='I'; else $O='A';
  // Verifica se h� necessidade de recarregar os dados da tela a partir
  // da pr�pria tela (se for recarga da tela) ou do banco de dados (se n�o for inclus�o)
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_chave                = $_REQUEST['w_chave'];
    $w_chave_aux            = $_REQUEST['w_chave_aux'];
    $w_nome                 = $_REQUEST['w_nome'];
    $w_nome_resumido        = $_REQUEST['w_nome_resumido'];
    $w_sq_pessoa_pai        = $_REQUEST['w_sq_pessoa_pai'];
    $w_nm_tipo_pessoa       = $_REQUEST['w_nm_tipo_pessoa'];
    $w_sq_tipo_vinculo      = $_REQUEST['w_sq_tipo_vinculo'];
    $w_nm_tipo_vinculo      = $_REQUEST['w_nm_tipo_vinculo'];
    $w_sq_banco             = $_REQUEST['w_sq_banco'];
    $w_sq_agencia           = $_REQUEST['w_sq_agencia'];
    $w_operacao             = $_REQUEST['w_operacao'];
    $w_nr_conta             = $_REQUEST['w_nr_conta'];
    $w_sq_pais_estrang      = $_REQUEST['w_sq_pais_estrang'];
    $w_aba_code             = $_REQUEST['w_aba_code'];
    $w_swift_code           = $_REQUEST['w_swift_code'];
    $w_endereco_estrang     = $_REQUEST['w_endereco_estrang'];
    $w_banco_estrang        = $_REQUEST['w_banco_estrang'];
    $w_agencia_estrang      = $_REQUEST['w_agencia_estrang'];
    $w_cidade_estrang       = $_REQUEST['w_cidade_estrang'];
    $w_informacoes          = $_REQUEST['w_informacoes'];
    $w_codigo_deposito      = $_REQUEST['w_codigo_deposito'];
    $w_interno              = $_REQUEST['w_interno'];
    $w_vinculo_ativo        = $_REQUEST['w_vinculo_ativo'];
    $w_sq_pessoa_telefone   = $_REQUEST['w_sq_pessoa_telefone'];
    $w_ddd                  = $_REQUEST['w_ddd'];
    $w_nr_telefone          = $_REQUEST['w_nr_telefone'];
    $w_sq_pessoa_celular    = $_REQUEST['w_sq_pessoa_celular'];
    $w_nr_celular           = $_REQUEST['w_nr_celular'];
    $w_sq_pessoa_fax        = $_REQUEST['w_sq_pessoa_fax'];
    $w_nr_fax               = $_REQUEST['w_nr_fax'];
    $w_email                = $_REQUEST['w_email'];
    $w_sq_pessoa_endereco   = $_REQUEST['w_sq_pessoa_endereco'];
    $w_logradouro           = $_REQUEST['w_logradouro'];
    $w_complemento          = $_REQUEST['w_complemento'];
    $w_bairro               = $_REQUEST['w_bairro'];
    $w_cep                  = $_REQUEST['w_cep'];
    $w_sq_cidade            = $_REQUEST['w_sq_cidade'];
    $w_co_uf                = $_REQUEST['w_co_uf'];
    $w_sq_pais              = $_REQUEST['w_sq_pais'];
    $w_pd_pais              = $_REQUEST['w_pd_pais'];
    $w_cpf                  = $_REQUEST['w_cpf'];
    $w_nascimento           = $_REQUEST['w_nascimento'];
    $w_rg_numero            = $_REQUEST['w_rg_numero'];
    $w_rg_emissor           = $_REQUEST['w_rg_emissor'];
    $w_rg_emissao           = $_REQUEST['w_rg_emissao'];
    $w_passaporte_numero    = $_REQUEST['w_passaporte_numero'];
    $w_sq_pais_passaporte   = $_REQUEST['w_sq_pais_passaporte'];
    $w_sexo                 = $_REQUEST['w_sexo'];
    $w_cnpj                 = $_REQUEST['w_cnpj'];
    $w_inscricao_estadual   = $_REQUEST['w_inscricao_estadual'];
  } else {
    if ((strpos($_REQUEST['botao'],'Alterar')===false) && (strpos($_REQUEST['botao'],'Procurar')===false) && ($O=='A' || $w_sq_pessoa>'' || $w_cpf>'' || $w_cnpj>'')) {
      // Recupera os dados do benefici�rio em co_pessoa
      $RS = db_getBenef::getInstanceOf($dbms,$w_cliente,$w_sq_pessoa,$w_cpf,$w_cnpj,null,null,null,null);
      foreach ($RS as $row) {$RS=$row; break;}
      if (count($RS) > 0) {
        $w_sq_pessoa            = f($RS,'sq_pessoa');
        $w_nome                 = f($RS,'nm_pessoa');
        $w_nome_resumido        = f($RS,'nome_resumido');
        $w_sq_pessoa_pai        = f($RS,'sq_pessoa_pai');
        $w_nm_tipo_pessoa       = f($RS,'nm_tipo_pessoa');
        $w_sq_tipo_vinculo      = f($RS,'sq_tipo_vinculo');
        $w_nm_tipo_vinculo      = f($RS,'nm_tipo_vinculo');
        $w_interno              = f($RS,'interno');
        $w_vinculo_ativo        = f($RS,'vinculo_ativo');
        $w_sq_pessoa_telefone   = f($RS,'sq_pessoa_telefone');
        $w_ddd                  = f($RS,'ddd');
        $w_nr_telefone          = f($RS,'nr_telefone');
        $w_sq_pessoa_celular    = f($RS,'sq_pessoa_celular');
        $w_nr_celular           = f($RS,'nr_celular');
        $w_sq_pessoa_fax        = f($RS,'sq_pessoa_fax');
        $w_nr_fax               = f($RS,'nr_fax');
        $w_email                = f($RS,'email');
        $w_sq_pessoa_endereco   = f($RS,'sq_pessoa_endereco');
        $w_logradouro           = f($RS,'logradouro');
        $w_complemento          = f($RS,'complemento');
        $w_bairro               = f($RS,'bairro');
        $w_cep                  = f($RS,'cep');
        $w_sq_cidade            = f($RS,'sq_cidade');
        $w_co_uf                = f($RS,'co_uf');
        $w_sq_pais              = f($RS,'sq_pais');
        $w_pd_pais              = f($RS,'pd_pais');
        $w_cpf                  = f($RS,'cpf');
        $w_nascimento           = FormataDataEdicao(f($RS,'nascimento'));
        $w_rg_numero            = f($RS,'rg_numero');
        $w_rg_emissor           = f($RS,'rg_emissor');
        $w_rg_emissao           = FormataDataEdicao(f($RS,'rg_emissao'));
        $w_passaporte_numero    = f($RS,'passaporte_numero');
        $w_sq_pais_passaporte   = f($RS,'sq_pais_passaporte');
        $w_sexo                 = f($RS,'sexo');
        $w_cnpj                 = f($RS,'cnpj');
        $w_inscricao_estadual   = f($RS,'inscricao_estadual');
        if (!(strpos('CREDITO,DEPOSITO',$w_forma_pagamento)===false)) {
          if (Nvl($w_nr_conta,'')=='') {
            $w_sq_banco     = f($RS,'sq_banco');
            $w_sq_agencia   = f($RS,'sq_agencia');
            $w_operacao     = f($RS,'operacao');
            $w_nr_conta     = f($RS,'nr_conta');
          } 
        } 
      } 
    } 
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  // Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara,
  // tratando as particularidades de cada servi�o
  ScriptOpen('JavaScript');
  Modulo();
  FormataCPF();
  FormataCNPJ();
  FormataCEP();
  CheckBranco();
  FormataData();
  ValidateOpen('Validacao');
  if (($w_cpf=='' && $w_cnpj=='') || (!(strpos($_REQUEST['botao'],'Procurar')===false)) || (!(strpos($_REQUEST['botao'],'Alterar')===false))) {
    // Se o benefici�rio ainda n�o foi selecionado
    ShowHTML('  if (theForm.Botao.value == "Procurar") {');
    Validate('w_nome','Nome','','1','4','20','1','');
    ShowHTML('  theForm.Botao.value = "Procurar";');
    ShowHTML('}');
    ShowHTML('else {');
    if ($w_tipo_pessoa==1) Validate('w_cpf','CPF','CPF','1','14','14','','0123456789-.');
    else                   Validate('w_cnpj','CNPJ','CNPJ','1','18','18','','0123456789/-.');
    ShowHTML('  theForm.w_sq_pessoa.value = \'\';');
    ShowHTML('}');
  } elseif ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.Botao.value.indexOf(\'Alterar\') >= 0) { return true; }');
    if (Nvl($w_sq_pessoa,'')=='') {
      Validate('w_nome','Nome','1',1,5,60,'1','1');
      Validate('w_nome_resumido','Nome resumido','1',1,2,15,'1','1');
    } 
    if ($w_tipo_pessoa==1) {
      Validate('w_sexo','Sexo','SELECT',1,1,1,'MF','');
      Validate('w_rg_numero','Identidade','1',1,2,30,'1','1');
      Validate('w_rg_emissor','�rg�o expedidor','1',1,2,30,'1','1');
    } else {
      Validate('w_inscricao_estadual','Inscri��o estadual','1','',2,20,'1','1');
    } 
    Validate('w_ddd','DDD','1','1',3,4,'','0123456789');
    Validate('w_nr_telefone','Telefone','1',1,7,25,'1','1');
    Validate('w_nr_fax','Fax','1','',7,25,'1','1');
    Validate('w_nr_celular','Celular','1','',7,25,'1','1');
    Validate('w_logradouro','Logradouro','1',1,4,60,'1','1');
    Validate('w_complemento','Complemento','1','',2,20,'1','1');
    Validate('w_bairro','Bairro','1','',2,30,'1','1');
    Validate('w_sq_pais','Pa�s','SELECT',1,1,10,'1','1');
    Validate('w_co_uf','UF','SELECT',1,1,10,'1','1');
    Validate('w_sq_cidade','Cidade','SELECT',1,1,10,'','1');
    if (Nvl($w_pd_pais,'S')=='S') Validate('w_cep','CEP','1','',9,9,'','0123456789-');
    else                          Validate('w_cep','CEP','1',1,5,9,'','0123456789');
    Validate('w_email','E-Mail','1','',4,60,'1','1');
    if (substr(f($RS1,'sigla'),0,3)=='FND') {
      if (!(strpos('CREDITO,DEPOSITO',$w_forma_pagamento)===false)) {
        Validate('w_sq_banco','Banco','SELECT',1,1,10,'1','1');
        Validate('w_sq_agencia','Agencia','SELECT',1,1,10,'1','1');
        Validate('w_operacao','Opera��o','1','',1,6,'','0123456789');
        Validate('w_nr_conta','N�mero da conta','1','1',2,30,'ZXAzxa','0123456789-');
      } elseif ($w_forma_pagamento=='ORDEM') {
        Validate('w_sq_banco','Banco','SELECT',1,1,10,'1','1');
        Validate('w_sq_agencia','Agencia','SELECT',1,1,10,'1','1');
      } elseif ($w_forma_pagamento=='EXTERIOR') {
        Validate('w_banco_estrang','Banco de destino','1','1',1,60,1,1);
        Validate('w_aba_code','C�digo ABA','1','',1,12,1,1);
        Validate('w_swift_code','C�digo SWIFT','1','',1,30,'',1);
        Validate('w_endereco_estrang','Endere�o da ag�ncia destino','1','',3,100,1,1);
        ShowHTML('  if (theForm.w_aba_code.value == \'\' && theForm.w_swift_code.value == \'\' && theForm.w_endereco_estrang.value == \'\') {');
        ShowHTML('     alert(\'Informe c�digo ABA, c�digo SWIFT ou endere�o da ag�ncia!\');');
        ShowHTML('     document.Form.w_aba_code.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        Validate('w_agencia_estrang','Nome da ag�ncia destino','1','1',1,60,1,1);
        Validate('w_nr_conta','N�mero da conta','1',1,1,10,1,1);
        Validate('w_cidade_estrang','Cidade da ag�ncia','1','1',1,60,1,1);
        Validate('w_sq_pais_estrang','Pa�s da ag�ncia','SELECT','1',1,18,1,1);
        Validate('w_informacoes','Informa��es adicionais','1','',5,200,1,1);
      }
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    if (!(strpos('CONT',f($RS_Menu,'sigla'))===false)) {
      // Se n�o for lan�amento para parcela de contrato
      ShowHTML('  theForm.Botao[2].disabled=true;');
    } 
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if (($w_cpf=='' && $w_cnpj=='') || !(strpos($_REQUEST['botao'],'Alterar')===false) || !(strpos($_REQUEST['botao'],'Procurar')===false)) {
    // Se o benefici�rio ainda n�o foi selecionado
    if (!(strpos($_REQUEST['botao'],'Procurar')===false)) {
      // Se est� sendo feita busca por nome
      BodyOpen('onLoad=\'document.focus()\';');
    } else {
      if ($w_tipo_pessoa==1) BodyOpen('onLoad=\'document.Form.w_cpf.focus()\';');
      else                   BodyOpen('onLoad=\'document.Form.w_cnpj.focus()\';');
    } 
  } elseif ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    if (Nvl($w_sq_pessoa,'')>'') {
      if ($w_tipo_pessoa==1) {
        BodyOpen('onLoad=\'document.Form.w_sexo.focus()\';');
      } else {
        BodyOpen('onLoad=\'document.Form.w_inscricao_estadual.focus()\';');
      } 
    } else {
      BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
    } 
  } 
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr><td colspan=3 bgcolor="#FAEBD7"><table border=1 width="100%"><tr><td>');
  ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr><td colspan=3><b>'.strtoupper(f($RS1,'nome')).' '.f($RS1,'codigo_interno').' ('.$w_chave.')</b></td>');
  ShowHTML('      <tr><td colspan="3">Finalidade: <b>'.CRLF2BR(f($RS1,'descricao')).'</b></td></tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('          <td>Forma de pagamento:<br><b>'.f($RS1,'nm_forma_pagamento').' </b></td>');
  ShowHTML('          <td>Vencimento:<br><b>'.FormataDataEdicao(f($RS1,'vencimento')).' </b></td>');
  ShowHTML('          <td>Valor:<br><b>'.number_format(Nvl(f($RS1,'valor'),0),2,',','.').' </b></td>');
  ShowHTML('    </TABLE>');
  ShowHTML('</TABLE>');
  ShowHTML('  <tr><td>&nbsp;');
  if (!(strpos('IA',$O)===false)) {
    if (($w_cpf=='' && $w_cnpj=='') || !(strpos($_REQUEST['botao'],'Alterar')===false) || !(strpos($_REQUEST['botao'],'Procurar')===false)) {
      // Se o benefici�rio ainda n�o foi selecionado
      ShowHTML('<FORM action="'.$w_dir.$w_pagina.$par.'" method="POST" name="Form" onSubmit="return(Validacao(this));">');
    } else {
      ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava" method="POST" name="Form" onSubmit="return(Validacao(this));">');
      if (Nvl($w_sq_pessoa,'')>'') {
        ShowHTML('<INPUT type="hidden" name="w_nome" value="'.$w_nome.'">');
        ShowHTML('<INPUT type="hidden" name="w_nome_resumido" value="'.$w_nome_resumido.'">');
      } 
    } 
    ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
    ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
    ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
    ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
    ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
    ShowHTML('<INPUT type="hidden" name="SG" value="'.$SG.'">');
    ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
    ShowHTML('<INPUT type="hidden" name="O" value="'.$O.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_pessoa" value="'.$w_sq_pessoa.'">');
    ShowHTML('<INPUT type="hidden" name="w_pessoa_atual" value="'.$w_pessoa_atual.'">');
    if (($w_cpf=='' && $w_cnpj=='') || !(strpos($_REQUEST['botao'],'Alterar')===false) || !(strpos($_REQUEST['botao'],'Procurar')===false)) {
      $w_nome=$_REQUEST['w_nome'];
      if (!(strpos($_REQUEST['botao'],'Alterar')===false)) {
        $w_cpf='';
        $w_cnpj='';
        $w_nome='';
      } 
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
      ShowHTML('    <table border="0">');
      ShowHTML('        <tr><td colspan=4><font size=2>Informe os dados abaixo e clique no bot�o "Selecionar" para continuar.</TD>');
      if ($w_tipo_pessoa==1) ShowHTML('        <tr><td colspan=4><b><u>C</u>PF:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cpf" VALUE="'.$w_cpf.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
      else                   ShowHTML('        <tr><td colspan=4><b><u>C</u>NPJ:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cnpj" VALUE="'.$w_cnpj.'" SIZE="18" MaxLength="18" onKeyDown="FormataCNPJ(this, event);">');
      ShowHTML('            <INPUT class="stb" TYPE="submit" NAME="Botao" VALUE="Selecionar">');
      ShowHTML('            <INPUT class="stb" type="button" onClick="window.close(); opener.focus();" name="Botao" value="Cancelar">');
      ShowHTML('        <tr><td colspan=4><p>&nbsp</p>');
      ShowHTML('        <tr><td colspan=4 heigth=1 bgcolor="#000000">');
      ShowHTML('        <tr><td colspan=4>');
      ShowHTML('             <b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome SEM ACENTOS)<br><INPUT ACCESSKEY="P" TYPE="text" class="sti" NAME="w_nome" VALUE="'.$w_nome.'" SIZE="20" MaxLength="20">');
      ShowHTML('              <INPUT class="stb" TYPE="submit" NAME="Botao" VALUE="Procurar" onClick="Botao.value=this.value; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'">');
      ShowHTML('      </table>');
      if ($w_nome>'') {
        $RS = db_getBenef::getInstanceOf($dbms,$w_cliente,null,null,null,$w_nome,$w_tipo_pessoa,null,null);
        $RS = SortArray($RS,'nm_pessoa','asc');
        ShowHTML('<tr><td colspan=3>');
        ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('          <td><b>Nome</td>');
        ShowHTML('          <td><b>Nome resumido</td>');
        if ($w_tipo_pessoa==1)  ShowHTML('          <td><b>CPF</td>');
        else                    ShowHTML('          <td><b>CNPJ</td>');
        ShowHTML('          <td><b>Opera��es</td>');
        ShowHTML('        </tr>');
        if (count($RS)<=0) {
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>N�o h� pessoas que contenham o texto informado.</b></td></tr>');
        } else {
          foreach($RS as $row) {
            ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
            ShowHTML('        <td>'.f($row,'nm_pessoa').'</td>');
            ShowHTML('        <td>'.f($row,'nome_resumido').'</td>');
            if ($w_tipo_pessoa==1) ShowHTML('        <td align="center">'.Nvl(f($row,'cpf'),'---').'</td>');
            else                   ShowHTML('        <td align="center">'.Nvl(f($row,'cnpj'),'---').'</td>');
            ShowHTML('        <td nowrap>');
            if ($w_tipo_pessoa==1) ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O=A&w_cpf='.f($row,'cpf').'&w_menu='.$w_menu.'&w_sq_pessoa='.f($row,'sq_pessoa').'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&Botao=Selecionar">Selecionar</A>&nbsp');
            else                   ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O=A&w_cnpj='.f($row,'cnpj').'&w_menu='.$w_menu.'&w_sq_pessoa='.f($row,'sq_pessoa').'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&Botao=Selecionar">Selecionar</A>&nbsp');
            ShowHTML('        </td>');
            ShowHTML('      </tr>');
          } 
        } 
        ShowHTML('      </center>');
        ShowHTML('    </table>');
        ShowHTML('  </td>');
        ShowHTML('</tr>');
      } 
    } else {
      if (Nvl($w_sq_pais,'')=='') {
        // Carrega os valores padr�o para pa�s, estado e cidade
        $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
        $w_sq_pais    = f($RS,'sq_pais');
        $w_co_uf      = f($RS,'co_uf');
        $w_sq_cidade  = f($RS,'sq_cidade_padrao');
      } 
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
      ShowHTML('    <table width="97%" border="0">');
      ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Identifica��o</td></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2"><table border="0" width="100%">');
      ShowHTML('          <tr valign="top">');
      if ($w_tipo_pessoa==1) {
        ShowHTML('          <td>CPF:<br><b><font size=2>'.$w_cpf);
        ShowHTML('              <INPUT type="hidden" name="w_cpf" value="'.$w_cpf.'">');
      } else {
        ShowHTML('          <td>CNPJ:<br><b><font size=2>'.$w_cnpj);
        ShowHTML('              <INPUT type="hidden" name="w_cnpj" value="'.$w_cnpj.'">');
      } 
      if (Nvl($w_sq_pessoa,'')>'') {
        ShowHTML('             <td>Nome completo:<b><br>'.$w_nome.'</td>');
        ShowHTML('             <td>Nome resumido:<b><br>'.$w_nome_resumido.'</td>');
      } else {
        ShowHTML('          <tr valign="top">');
        ShowHTML('             <td><b><u>N</u>ome completo:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="45" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
        ShowHTML('             <td><b><u>N</u>ome resumido:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome_resumido" class="sti" SIZE="15" MAXLENGTH="15" VALUE="'.$w_nome_resumido.'"></td>');
      } 
      if ($w_tipo_pessoa==1) {
        ShowHTML('          <tr valign="top">');
        SelecaoSexo('Se<u>x</u>o:','X',null,$w_sexo,null,'w_sexo',null,null);
        ShowHTML('          <td><b><u>I</u>dentidade:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_rg_numero" class="sti" SIZE="14" MAXLENGTH="80" VALUE="'.$w_rg_numero.'"></td>');
        ShowHTML('          <td><b>�r<u>g</u>�o emissor:</b><br><input '.$w_Disabled.' accesskey="G" type="text" name="w_rg_emissor" class="sti" SIZE="10" MAXLENGTH="30" VALUE="'.$w_rg_emissor.'"></td>');
      } else {
        ShowHTML('      <tr><td colspan="3"><b><u>I</u>nscri��o estadual:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_inscricao_estadual" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_inscricao_estadual.'"></td>');
      } 
      ShowHTML('          </table>');
      ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      if ($w_tipo_pessoa==1) ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Endere�o comercial, Telefones e e-Mail</td></td></tr>');
      else                   ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Endere�o principal, Telefones e e-Mail</td></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
      ShowHTML('          <tr valign="top">');
      ShowHTML('          <td><b><u>D</u>DD:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_ddd" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_ddd.'"></td>');
      ShowHTML('          <td><b>Te<u>l</u>efone:</b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_nr_telefone" class="sti" SIZE="20" MAXLENGTH="40" VALUE="'.$w_nr_telefone.'"></td>');
      ShowHTML('          <td title="Se a pessoa informar um n�mero de fax, informe-o neste campo."><b>Fa<u>x</u>:</b><br><input '.$w_Disabled.' accesskey="X" type="text" name="w_nr_fax" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_nr_fax.'"></td>');
      ShowHTML('          <td title="Se a pessoa informar um celular institucional, informe-o neste campo."><b>C<u>e</u>lular:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_nr_celular" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_nr_celular.'"></td>');
      ShowHTML('          <tr valign="top">');
      ShowHTML('          <td colspan=2><b>En<u>d</u>ere�o:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_logradouro" class="sti" SIZE="50" MAXLENGTH="50" VALUE="'.$w_logradouro.'"></td>');
      ShowHTML('          <td><b>C<u>o</u>mplemento:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_complemento" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_complemento.'"></td>');
      ShowHTML('          <td><b><u>B</u>airro:</b><br><input '.$w_Disabled.' accesskey="B" type="text" name="w_bairro" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_bairro.'"></td>');
      ShowHTML('          <tr valign="top">');
      SelecaoPais('<u>P</u>a�s:','P',null,$w_sq_pais,null,'w_sq_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_co_uf\'; document.Form.submit();"');
      ShowHTML('          <td>');
      SelecaoEstado('E<u>s</u>tado:','S',null,$w_co_uf,$w_sq_pais,'N','w_co_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_cidade\'; document.Form.submit();"');
      SelecaoCidade('<u>C</u>idade:','C',null,$w_sq_cidade,$w_sq_pais,$w_co_uf,'w_sq_cidade',null,null);
      ShowHTML('          <tr valign="top">');
      if (Nvl($w_pd_pais,'S')=='S') {
        ShowHTML('              <td><b>C<u>E</u>P:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_cep" class="sti" SIZE="9" MAXLENGTH="9" VALUE="'.$w_cep.'" onKeyDown="FormataCEP(this,event);"></td>');
      } else {
        ShowHTML('              <td><b>C<u>E</u>P:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_cep" class="sti" SIZE="9" MAXLENGTH="9" VALUE="'.$w_cep.'"></td>');
      } 
      ShowHTML('              <td colspan=3 title="Se a pessoa informar um e-mail institucional, informe-o neste campo."><b>e-<u>M</u>ail:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_email" class="sti" SIZE="50" MAXLENGTH="60" VALUE="'.$w_email.'"></td>');
      ShowHTML('          </table>');
      if (substr(f($RS_Menu,'sigla'),0,3)!='FNR') {
        // Se n�o for lan�amento de receita
        if (!(strpos('CREDITO,DEPOSITO',$w_forma_pagamento)===false)) {
          ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Dados banc�rios</td></td></tr>');
          ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
          ShowHTML('      <tr valign="top">');
          SelecaoBanco('<u>B</u>anco:','B','Selecione o banco onde dever�o ser feitos os pagamentos referentes ao lan�amento.',$w_sq_banco,null,'w_sq_banco',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_agencia\'; document.Form.submit();"');
          SelecaoAgencia('A<u>g</u>�ncia:','A','Selecione a ag�ncia onde dever�o ser feitos os pagamentos referentes ao lan�amento.',$w_sq_agencia,Nvl($w_sq_banco,-1),'w_sq_agencia',null,null);
          ShowHTML('      <tr valign="top">');
          ShowHTML('          <td title="Alguns bancos trabalham com o campo "Opera��o", al�m do n�mero da conta. A Caixa Econ�mica Federal � um exemplo. Se for o caso,informe a opera��o neste campo; caso contr�rio, deixe-o em branco."><b>O<u>p</u>era��o:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_operacao" class="sti" SIZE="6" MAXLENGTH="6" VALUE="'.$w_operacao.'"></td>');
          ShowHTML('          <td title="Informe o n�mero da conta banc�ria, colocando o d�gito verificador, se existir, separado por um h�fen. Exemplo: 11214-3. Se o banco n�o trabalhar com d�gito verificador, informe apenas n�meros. Exemplo: 10845550."><b>N�mero da con<u>t</u>a:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_nr_conta" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_nr_conta.'"></td>');
          ShowHTML('          </table>');
        } elseif ($w_forma_pagamento=='ORDEM') {
          ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Dados para Ordem Banc�ria</td></td></tr>');
          ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
          ShowHTML('      <tr valign="top">');
          SelecaoBanco('<u>B</u>anco:','B','Selecione o banco onde dever�o ser feitos os pagamentos referentes ao lan�amento.',$w_sq_banco,null,'w_sq_banco',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_agencia\'; document.Form.submit();"');
          SelecaoAgencia('A<u>g</u>�ncia:','A','Selecione a ag�ncia onde dever�o ser feitos os pagamentos referentes ao lan�amento.',$w_sq_agencia,Nvl($w_sq_banco,-1),'w_sq_agencia',null,null);
        } elseif ($w_forma_pagamento=='EXTERIOR') {
          ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Dados da conta no exterior</td></td></tr>');
          ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td colspan="2"><b><font color="#BC3131">ATEN��O:</font></b> � obrigat�rio o preenchimento de um destes campos: Swift Code, ABA Code ou Endere�o da Ag�ncia.</td></tr>');
          ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
          ShowHTML('      <tr valign="top">');
          ShowHTML('          <td title="Banco onde o cr�dito deve ser efetuado."><b><u>B</u>anco de cr�dito:</b><br><input '.$w_Disabled.' accesskey="B" type="text" name="w_banco_estrang" class="sti" SIZE="40" MAXLENGTH="60" VALUE="'.$w_banco_estrang.'"></td>');
          ShowHTML('          <td title="C�digo ABA da ag�ncia destino."><b>A<u>B</u>A code:</b><br><input '.$w_Disabled.' accesskey="B" type="text" name="w_aba_code" class="sti" SIZE="12" MAXLENGTH="12" VALUE="'.$w_aba_code.'"></td>');
          ShowHTML('          <td title="C�digo SWIFT da ag�ncia destino."><b>S<u>W</u>IFT code:</b><br><input '.$w_Disabled.' accesskey="W" type="text" name="w_swift_code" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_swift_code.'"></td>');
          ShowHTML('      <tr><td colspan=3 title="Endere�o da ag�ncia."><b>E<u>n</u>dere�o da ag�ncia:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_endereco_estrang" class="sti" SIZE="80" MAXLENGTH="100" VALUE="'.$w_endereco_estrang.'"></td>');
          ShowHTML('      <tr valign="top">');
          ShowHTML('          <td colspan=2 title="Nome da ag�ncia destino."><b>Nome da a<u>g</u>�ncia:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_agencia_estrang" class="sti" SIZE="40" MAXLENGTH="60" VALUE="'.$w_agencia_estrang.'"></td>');
          ShowHTML('          <td title="N�mero da conta destino."><b>N�mero da con<u>t</u>a:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_nr_conta" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_nr_conta.'"></td>');
          ShowHTML('      <tr valign="top">');
          ShowHTML('          <td colspan=2 title="Cidade da ag�ncia destino."><b><u>C</u>idade:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_cidade_estrang" class="sti" SIZE="40" MAXLENGTH="60" VALUE="'.$w_cidade_estrang.'"></td>');
          SelecaoPais('<u>P</u>a�s:','P','Selecione o pa�s de destino',$w_sq_pais_estrang,null,'w_sq_pais_estrang',null,null);
          ShowHTML('          </table>');
          ShowHTML('      <tr><td colspan=2 title="Se necess�rio, escreva informa��es adicionais relevantes para o pagamento."><b>Info<u>r</u>ma��es adicionais:</b><br><textarea '.$w_Disabled.' accesskey="R" name="w_informacoes" class="sti" ROWS=3 cols=75 >'.$w_informacoes.'</TEXTAREA></td>');
        } 
      } 
      ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
      ShowHTML('      <tr><td align="center" colspan="3">');
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar" onClick="Botao.value=this.value;">');
      if (!(strpos('CONT',f($RS_Menu,'sigla'))===false)) {
        // Se n�o for lan�amento para parcela de contrato
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Alterar pessoa" onClick="Botao.value=this.value; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.submit();">');
      } 
      ShowHTML('            <input class="stb" type="button" onClick="window.close(); opener.focus();" name="Botao" value="Cancelar">');
      ShowHTML('          </td>');
      ShowHTML('      </tr>');
      ShowHTML('    </table>');
      ShowHTML('    </TD>');
      ShowHTML('</tr>');
    } 
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
} 
// =========================================================================
// Rotina de documentos
// -------------------------------------------------------------------------
function Documentos() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  // Recupera os dados do lan�amento
  $RS1 = db_getSolicData::getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_sq_tipo_documento    = $_REQUEST['w_sq_tipo_documento'];
    $w_numero               = $_REQUEST['w_numero'];
    $w_data                 = $_REQUEST['w_data'];
    $w_serie                = $_REQUEST['w_serie'];
    $w_valor                = $_REQUEST['w_valor'];
    $w_patrimonio           = $_REQUEST['w_patrimonio'];
    $w_tipo                 = $_REQUEST['w_tipo'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getLancamentoDoc::getInstanceOf($dbms,$w_chave,null,'LISTA');
    $RS = SortArray($RS,'data','asc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados do endere�o informado
    $RS = db_getLancamentoDoc::getInstanceOf($dbms,$w_chave,$w_chave_aux,'REGISTRO');
    foreach ($RS as $row) {$RS=$row; break;}
    $w_sq_tipo_documento    = f($RS,'sq_tipo_documento');
    $w_numero               = f($RS,'numero');
    $w_data                 = FormataDataEdicao(f($RS,'data'));
    $w_serie                = f($RS,'serie');
    $w_valor                = number_format(f($RS,'valor'),2,',','.');
    $w_patrimonio           = f($RS,'patrimonio');
    $w_tributo              = f($RS,'calcula_tributo');
    $w_retencao             = f($RS,'calcula_retencao');
  } 
  // Recupera a sigla do tipo do documento para tratar a Nota Fiscal e
  // verifica se o tipo de documento tem incid�ncia de tributos e reten��o.
  if ($w_sq_tipo_documento>'') {
    $RS2 = db_getImpostoIncid::getInstanceOf($dbms,$w_cliente,$w_chave,$w_sq_tipo_documento,null,'INCIDENCIA');
    if (count($RS2)<=0) {
      $w_incid_tributo='N';
      $w_incid_retencao='N';
    } else {
      foreach ($RS2 as $row) {
        $w_incid_tributo  = f($row,'calcula_tributo');
        $w_incid_retencao = f($row,'calcula_retencao');
        break;
      }
    } 
    $RS2 = db_getTipoDocumento::getInstanceOf($dbms,$w_sq_tipo_documento,$w_cliente);
    foreach ($RS2 as $row) {
      $w_tipo = f($row,'sigla');
    }
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  if (!(strpos('IAEGCP',$O)===false)) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_sq_tipo_documento','Tipo do documento', '1', '1', '1', '18', '', '0123456789');
      Validate('w_numero','N�mero do documento', '1', '1', '1', '30', '1', '1');
      Validate('w_data','Data do documento', 'DATA', '1', '10', '10', '', '0123456789/');
      if (Nvl($w_tipo,'-')=='NF') {
        Validate('w_serie','S�rie do documento', '1', '1', 1, 10, '1', '1');
      } 
      Validate('w_valor','Valor total do documento', 'VALOR', '1', 4, 18, '', '0123456789.,');
    }
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='I') {
    BodyOpen('onLoad=\'document.Form.w_sq_tipo_documento.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.focus()\';');
  } 
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr><td colspan=3 bgcolor="#FAEBD7"><table border=1 width="100%"><tr><td>');
  ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr><td colspan=3><b>'.strtoupper(f($RS1,'nome')).' '.f($RS1,'codigo_interno').' ('.$w_chave.')</b></td>');
  ShowHTML('      <tr><td colspan="3">Finalidade: <b>'.CRLF2BR(f($RS1,'descricao')).'</b></td></tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('          <td>Forma de pagamento:<br><b>'.f($RS1,'nm_forma_pagamento').' </b></td>');
  ShowHTML('          <td>Vencimento:<br><b>'.FormataDataEdicao(f($RS1,'vencimento')).' </b></td>');
  ShowHTML('          <td>Valor:<br><b>'.number_format(Nvl(f($RS1,'valor'),0),2,',','.').' </b></td>');
  ShowHTML('    </TABLE>');
  ShowHTML('</TABLE>');
  ShowHTML('  <tr><td>&nbsp;');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('                         <a accesskey="F" class="ss" href="javascript:window.close(); opener.location.reload(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Tipo</td>');
    ShowHTML('          <td><b>N�mero</td>');
    ShowHTML('          <td><b>Data</td>');
    ShowHTML('          <td><b>Serie</td>');
    ShowHTML('          <td><b>Valor</td>');
    ShowHTML('          <td><b>Patrim�nio</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font size="2"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $w_total=0;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nm_tipo_documento').'</td>');
        ShowHTML('        <td align="center">'.f($row,'numero').'</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'data')).'</td>');
        ShowHTML('        <td align="center">'.Nvl(f($row,'serie'),'---').'</td>');
        ShowHTML('        <td align="right">'.number_format(f($row,'valor'),2,',','.').'&nbsp;&nbsp;</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_patrimonio').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_lancamento_doc').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_lancamento_doc').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclus�o do registro?\');">Excluir</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
        $w_total=$w_total+f($row,'valor');
      } 
    }
    if ($w_total>0) {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
      ShowHTML('        <td align="center" colspan=4><b>Total</b></td>');
      ShowHTML('        <td align="right"><b>'.number_format($w_total,2,',','.').'</b>&nbsp;&nbsp;</td>');
      ShowHTML('        <td colspan="2">&nbsp;</td>');
      ShowHTML('      </tr>');
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0><tr valign="top">');
    SelecaoTipoDocumento('<u>T</u>ipo:','T', 'Selecione o tipo de documento.', $w_sq_tipo_documento,$w_cliente,'w_sq_tipo_documento',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_numero\'; document.Form.submit();"');
    ShowHTML('          <td><b><u>N</u>�mero:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_numero" class="sti" SIZE="15" MAXLENGTH="30" VALUE="'.$w_numero.'" title="Informe o n�mero do documento."></td>');
    ShowHTML('          <td><b><u>D</u>ata:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data.'" onKeyDown="FormataData(this,event);" title="Informe a data do documento."></td>');
    if (Nvl($w_tipo,'-')=='NF') {
      ShowHTML('          <td><b><u>S</u>�rie:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_serie" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_serie.'" title="Informado apenas se o documento for NOTA FISCAL. Informe a s�rie ou, se n�o tiver, digite �NICA."></td>');
    } 
    ShowHTML('          <td><b><u>V</u>alor:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor total do documento."></td>');
    MontaRadioNS('<b>Patrim�nio?</b>',$w_patrimonio,'w_patrimonio');
    ShowHTML('          </table>');
    if ($w_incid_tributo=='N' && $w_incid_retencao=='N' && substr(f($RS_Menu,'sigla'),2,1)=='D') {
      ShowHTML('<INPUT type="hidden" name="w_tributo" value="'.$w_incid_tributo.'">');
      ShowHTML('<INPUT type="hidden" name="w_retencao" value="'.$w_incid_retencao.'">');
    } else {
      if ($w_incid_retencao=='S' || substr(f($RS_Menu,'sigla'),2,1)=='R') {
        ShowHTML('      <tr>');
        MontaRadioSN('<b>Haver� reten��o de tributos para este documento?',Nvl($w_retencao,$w_incid_retencao),'w_retencao');
      } else {
        ShowHTML('<INPUT type="hidden" name="w_retencao" value="'.$w_incid_retencao.'">');
      } 
      if ($w_incid_tributo=='S' || substr(f($RS_Menu,'sigla'),2,1)=='R') {
        ShowHTML('      <tr>');
        MontaRadioSN('<b>Haver� pagamento de tributos para este documento?',Nvl($w_tributo,$w_incid_tributo),'w_tributo');
      } else {
        ShowHTML('<INPUT type="hidden" name="w_tributo" value="N">');
      } 
    } 
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="stb" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
} 
// =========================================================================
// Rotina de inclus�o de lancamentos para as parcelas
// -------------------------------------------------------------------------
function BuscaParcela() {
  extract($GLOBALS);
  $p_sq_acordo_parcela  = $_REQUEST['p_sq_acordo_parcela'];
  $p_sq_acordo          = $_REQUEST['p_sq_acordo'];
  $p_outra_parte        = $_REQUEST['p_outra_parte'];
  $p_inicio             = $_REQUEST['p_inicio'];
  $p_fim                = $_REQUEST['p_fim'];

  if ($w_troca>'') {
    // Se for recarga da p�gina)
    $w_sq_acordo_parcela    = $_REQUEST['w_sq_acordo_parcela'];
    $w_sq_tipo_lancamento   = $_REQUEST['w_sq_tipo_lancamento'];
    $w_valor                = $_REQUEST['w_valor'];
  } elseif ($O=='L') {
    // Recupera todos os ws_url para a listagem
    //$RS = db_getAcordoParcela RS, null, w_sq_esquema, null
    //RS.Sort = 'ordem, ordem'
  } elseif ($O=='I') {
    if (Nvl($w_pais,'')=='') {
      // Carrega os valores padr�o para pa�s, estado e cidade
      $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
      $w_pais   = f($RS,'sq_pais');
      $w_uf     = f($RS,'co_uf');
      $w_cidade = Nvl(f($RS_Menu,'sq_cidade'),f($RS,'sq_cidade_padrao'));
    } 
    $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'GC'.substr($SG,2,1).'CAD');
    $RS = db_getAcordoParcela::getInstanceOf($dbms,$p_sq_acordo,$p_sq_acordo_parcela,'CADASTRO',$p_outra_parte,$p_inicio,$p_fim,$w_usuario,"'EE', 'ER'",f($RS1,'sq_menu'));
    $RS = SortArray($RS,'vencimento','asc','nome_resumido','asc');
  } elseif ($O=='A') {
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAP',$O)===false)) {
    ScriptOpen('JavaScript');
    if ($O=='I') {
      ShowHTML('  function valor(p_indice) {');
      ShowHTML('    if (document.Form["w_sq_acordo_parcela[]"][p_indice].checked) { ');
      ShowHTML('       document.Form["w_valor[]"][p_indice].disabled=false; ');
      ShowHTML('       document.Form["w_sq_tipo_lancamento[]"][p_indice].disabled=false; ');
      ShowHTML('       document.Form["w_sq_tipo_lancamento[]"][p_indice].focus(); ');
      ShowHTML('       document.Form["w_solicitante[]"][p_indice].disabled=false; ');
      ShowHTML('       document.Form["w_sqcc[]"][p_indice].disabled=false; ');
      ShowHTML('       document.Form["w_descricao[]"][p_indice].disabled=false; ');
      ShowHTML('       document.Form["w_vencimento[]"][p_indice].disabled=false; ');
      ShowHTML('       document.Form["w_chave_pai[]"][p_indice].disabled=false; ');
      ShowHTML('       document.Form["w_sq_forma_pagamento[]"][p_indice].disabled=false; ');
      ShowHTML('       document.Form["w_tipo_pessoa[]"][p_indice].disabled=false; ');
      ShowHTML('       document.Form["w_forma_atual[]"][p_indice].disabled=false; ');
      ShowHTML('       document.Form["w_vencimento_atual[]"][p_indice].disabled=false; ');
      ShowHTML('       document.Form["w_outra_parte[]"][p_indice].disabled=false; ');
      ShowHTML('    } else {');
      ShowHTML('       document.Form["w_valor[]"][p_indice].disabled=true; ');
      ShowHTML('       document.Form["w_sq_tipo_lancamento[]"][p_indice].disabled=true; ');
      ShowHTML('       document.Form["w_solicitante[]"][p_indice].disabled=true; ');
      ShowHTML('       document.Form["w_sqcc[]"][p_indice].disabled=true; ');
      ShowHTML('       document.Form["w_descricao[]"][p_indice].disabled=true; ');
      ShowHTML('       document.Form["w_vencimento[]"][p_indice].disabled=true; ');
      ShowHTML('       document.Form["w_chave_pai[]"][p_indice].disabled=true; ');
      ShowHTML('       document.Form["w_sq_forma_pagamento[]"][p_indice].disabled=true; ');
      ShowHTML('       document.Form["w_tipo_pessoa[]"][p_indice].disabled=true; ');
      ShowHTML('       document.Form["w_forma_atual[]"][p_indice].disabled=true; ');
      ShowHTML('       document.Form["w_vencimento_atual[]"][p_indice].disabled=true; ');
      ShowHTML('       document.Form["w_outra_parte[]"][p_indice].disabled=true; ');
      ShowHTML('    }');
      ShowHTML('  }');
      ShowHTML('  function MarcaTodos() {');
      ShowHTML('    if (document.Form["w_sq_acordo_parcela[]"].value==undefined) ');
      ShowHTML('       for (i=0; i < document.Form["w_sq_acordo_parcela[]"].length; i++) {');
      ShowHTML('         document.Form["w_sq_acordo_parcela[]"][i].checked=true;');
      ShowHTML('         document.Form["w_valor[]"][i].disabled=false;');
      ShowHTML('         document.Form["w_sq_tipo_lancamento[]"][i].disabled=false;');
      ShowHTML('         document.Form["w_solicitante[]"][i].disabled=false; ');
      ShowHTML('         document.Form["w_sqcc[]"][i].disabled=false; ');
      ShowHTML('         document.Form["w_descricao[]"][i].disabled=false; ');
      ShowHTML('         document.Form["w_vencimento[]"][i].disabled=false; ');
      ShowHTML('         document.Form["w_chave_pai[]"][i].disabled=false; ');
      ShowHTML('         document.Form["w_sq_forma_pagamento[]"][i].disabled=false; ');
      ShowHTML('         document.Form["w_tipo_pessoa[]"][i].disabled=false; ');
      ShowHTML('         document.Form["w_forma_atual[]"][i].disabled=false; ');
      ShowHTML('         document.Form["w_vencimento_atual[]"][i].disabled=false; ');
      ShowHTML('         document.Form["w_outra_parte[]"][i].disabled=false; ');
      ShowHTML('       } ');
      ShowHTML('    else document.Form["w_sq_acordo_parcela[]"].checked=true;');
      ShowHTML('  }');
      ShowHTML('  function DesmarcaTodos() {');
      ShowHTML('    if (document.Form["w_sq_acordo_parcela[]"].value==undefined) ');
      ShowHTML('       for (i=0; i < document.Form["w_sq_acordo_parcela[]"].length; i++) {');
      ShowHTML('         document.Form["w_sq_acordo_parcela[]"][i].checked=false;');
      ShowHTML('         document.Form["w_valor[]"][i].disabled=true;');
      ShowHTML('         document.Form["w_sq_tipo_lancamento[]"][i].disabled=true;');
      ShowHTML('         document.Form["w_solicitante[]"][i].disabled=true; ');
      ShowHTML('         document.Form["w_sqcc[]"][i].disabled=true; ');
      ShowHTML('         document.Form["w_descricao[]"][i].disabled=true; ');
      ShowHTML('         document.Form["w_vencimento[]"][i].disabled=true; ');
      ShowHTML('         document.Form["w_chave_pai[]"][i].disabled=true; ');
      ShowHTML('         document.Form["w_sq_forma_pagamento[]"][i].disabled=true; ');
      ShowHTML('         document.Form["w_tipo_pessoa[]"][i].disabled=true; ');
      ShowHTML('         document.Form["w_forma_atual[]"][i].disabled=true; ');
      ShowHTML('         document.Form["w_vencimento_atual[]"][i].disabled=true; ');
      ShowHTML('         document.Form["w_outra_parte[]"][i].disabled=true; ');
      ShowHTML('       } ');
      ShowHTML('    ');
      ShowHTML('    else document.Form["w_sq_acordo_parcela[]"].checked=false;');
      ShowHTML('  }');
    } 
    CheckBranco();
    FormataData();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IAP',$O)===false)) {
      if ($O=='P') {
        ShowHTML('  if (theForm.p_sq_acordo.selectedIndex==0 && theForm.p_sq_acordo_parcela.selectedIndex==0 && theForm.p_outra_parte.value==\'\' && theForm.p_inicio.value==\'\') {');
        ShowHTML('     alert(\'Voc� deve escolher pelo menos um crit�rio de filtragem!\');');
        ShowHTML('     return false;');
        ShowHTML('  }');
        Validate('p_sq_acordo','Acordo', 'SELECT', '', 1, 10, '1', '1');
        Validate('p_sq_acordo_parcela','Parcela', 'SELECT', '', 1, 10, '1', '1');
        Validate('p_outra_parte','Outra parte', '1', '', 3, 60, '1', '1');
        Validate('p_inicio','Vecimento inicial', 'DATA', '', '10', '10', '', '0123456789/');
        Validate('p_fim','Vencimento final', 'DATA', '', '10', '10', '', '0123456789/');
        ShowHTML('  if ((theForm.p_inicio.value != \'\' && theForm.p_fim.value == \'\') || (theForm.p_inicio.value == \'\' && theForm.p_fim.value != \'\')) {');
        ShowHTML('     alert (\'Informe ambas as datas de vencimento ou nenhuma delas!\');');
        ShowHTML('     theForm.p_inicio.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        CompData('p_inicio','Vencimento inicial','<=','p_fim','Vencimento final');
        Validate('P4','Linhas por p�gina', '1', '1', '1', '4', '', '0123456789');
      } elseif ($O=='I') {
        ShowHTML('  var i; ');
        ShowHTML('  var w_erro=true; ');
        ShowHTML('  if (theForm["w_sq_acordo_parcela[]"].value==undefined) {');
        ShowHTML('     for (i=0; i < theForm["w_sq_acordo_parcela[]"].length; i++) {');
        ShowHTML('       if (theForm["w_sq_acordo_parcela[]"][i].checked) w_erro=false;');
        ShowHTML('     }');
        ShowHTML('  }');
        ShowHTML('  else {');
        ShowHTML('     if (theForm["w_sq_acordo_parcela[]"].checked) w_erro=false;');
        ShowHTML('  }');
        ShowHTML('  if (w_erro) {');
        ShowHTML('    alert(\'Voc� deve informar pelo menos uma parcela!\'); ');
        ShowHTML('    return false;');
        ShowHTML('  }');
        ShowHTML('  for (i=1; i < theForm["w_sq_acordo_parcela[]"].length; i++) {');
        ShowHTML('    if((theForm["w_sq_acordo_parcela[]"][i].checked)&&(theForm["w_sq_tipo_lancamento[]"][i].selectedIndex==0)){');
        ShowHTML('      alert(\'Para todas as parcelas selecionadas voc� deve informar o tipo de lan�amento!\'); ');
        ShowHTML('      return false;');
        ShowHTML('    }');
        ShowHTML('  }');
        ShowHTML('  for (i=1; i < theForm["w_sq_acordo_parcela[]"].length; i++) {');
        ShowHTML('    if((theForm["w_sq_acordo_parcela[]"][i].checked)&&(theForm["w_valor[]"][i].value==\'\')){');
        ShowHTML('      alert(\'Para todas as parcelas selecionadas voc� deve informar o valor da mesma!\'); ');
        ShowHTML('      return false;');
        ShowHTML('    }');
        ShowHTML('  }');
      } elseif ($O=='A') {
      } 
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    if ($O=='P') ShowHTML('  theForm.Botao[2].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='P') {
      BodyOpen('onLoad=\'document.Form.p_sq_acordo.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='P') {
    //Filtro para inclus�o de um tabela no esquema
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'I');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('  <tr bgcolor="'.$conTrBgColor.'"><td colspan=2><div align="justify"><font size=2><b><ul>Instru��es</b>:<li>Informe os par�metros desejados para recuperar a lista de parcelas.<li>Quando a rela��o de parcelas for exibida, selecione as parcelas desejadas clicando sobre a caixa ao lado do codigo do acordo.<li>Voc� pode informar o nome da outra parte do acordo , selecionar as parcelas de um acordo. <li>Ap�s informar os par�metros desejados, clique sobre o bot�o <i>Aplicar filtro</i>.</ul><hr><b>Filtro</b></div>');
    ShowHTML('  <tr bgcolor="'.$conTrBgColor.'"><td colspan=2>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr>');
    // Recupera os dados da op��o "Contratos"
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'GC'.substr($SG,2,1).'CAD');
    SelecaoAcordo('<u>A</u>cordo:','A', null, $w_cliente, $p_sq_acordo, f($RS,'sq_menu'),'p_sq_acordo',f($RS_Menu,'sq_menu'),'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_sq_acordo_parcela\'; document.Form.submit();"');
    SelecaoAcordoParcela('<u>P</u>arcela:','P', null, $w_cliente, $p_sq_acordo_parcela, Nvl($p_sq_acordo,0), 'p_sq_acordo_parcela', 'CADASTRO', null);
    ShowHTML('      <tr><td valign="top"><b><u>O</u>utra parte:</b><br><input '.$w_disabled.' accesskey="O" type="text" name="p_outra_parte" class="sti" SIZE="30" MAXLENGTH="60" VALUE="'.$p_outra_parte.'"></td>');
    ShowHTML('      <tr><td valign="top"><b>Parcelas com <u>v</u>encimento entre:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="p_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_inicio.'" onKeyDown="FormataData(this,event);"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim.'" onKeyDown="FormataData(this,event);"></td>');
    ShowHTML('          <td valign="top"><b><U>L</U>inhas por p�gina:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="sti" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&R='.$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&O=P&SG='.$SG.'\';" name="Botao" value="Limpar campos">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$R.'&R='.$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</form>');
  } elseif ($O=='I') {
    //Rotina de escolha e grava��o das parcelas para o lan�amento
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_sq_unidade" value="'.f($RS_Menu,'sq_unid_executora').'">');
    ShowHTML('<INPUT type="hidden" name="w_cidade" value="'.$w_cidade.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_aviso" value="S">');
    ShowHTML('<INPUT type="hidden" name="w_dias" value="3">');
    ShowHTML('<INPUT type="hidden" name="w_sq_acordo_parcela[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sq_tipo_lancamento[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_valor[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_solicitante[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sqcc[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_descricao[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_vencimento[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave_pai[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sq_forma_pagamento[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_tipo_pessoa[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_forma_atual[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_vencimento_atual[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_outra_parte[]" value="">');
    ShowHTML('<tr><td>');
    ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O=P&P1='.$P1.'&P2='.$P2.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a></font>');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center" valign="top">');
    ShowHTML('            <td NOWRAP rowspan="2"><font size="2"><U ID="INICIO" STYLE="cursor:hand;" CLASS="hl" onClick="javascript:MarcaTodos();" TITLE="Marca todos os itens da rela��o"><IMG SRC="images/NavButton/BookmarkAndPageActivecolor.gif" BORDER="1" width="15" height="15"></U>&nbsp;');
    ShowHTML('                                      <U STYLE="cursor:hand;" CLASS="hl" onClick="javascript:DesmarcaTodos();" TITLE="Desmarca todos os itens da rela��o"><IMG SRC="images/NavButton/BookmarkAndPageInactive.gif" BORDER="1" width="15" height="15"></U>');
    ShowHTML('            <td rowspan="2"><b>Acordo</b></td>');
    ShowHTML('            <td rowspan="2"><b>Outra parte</b></td>');
    ShowHTML('            <td rowspan="1" colspan="4"><b>Parcela</b></td>');
    ShowHTML('          </tr>');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center" valign="top">');
    ShowHTML('            <td rowspan="1"><b>N�</b></td>');
    ShowHTML('            <td rowspan="1"><b>Venc.</b></td>');
    ShowHTML('            <td rowspan="1"><b>Tipo lan�am.</b></td>');
    ShowHTML('            <td rowspan="1"><b>Valor</b></td>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      $w_cont=0;
      foreach($RS1 as $row) {
        $w_cont+= 1;
        ShowHTML('<INPUT disabled type="hidden" name="w_solicitante[]" value="'.f($row,'solicitante').'">');
        ShowHTML('<INPUT disabled type="hidden" name="w_sqcc[]" value="'.f($row,'sq_cc').'">');
        ShowHTML('<INPUT disabled type="hidden" name="w_descricao[]" value="Pagamento da parcela '.substr(1000+f($row,'ordem'),1,3).', contrato '.f($row,'cd_acordo').' ('.f($row,'sq_siw_solicitacao').').">');
        ShowHTML('<INPUT disabled type="hidden" name="w_vencimento[]" value="'.FormataDataEdicao(f($row,'vencimento')).'">');
        ShowHTML('<INPUT disabled type="hidden" name="w_chave_pai[]" value="'.f($row,'sq_siw_solicitacao').'">');
        ShowHTML('<INPUT disabled type="hidden" name="w_sq_forma_pagamento[]" value="'.f($row,'sq_forma_pagamento').'">');
        ShowHTML('<INPUT disabled type="hidden" name="w_tipo_pessoa[]" value="'.f($row,'sq_tipo_pessoa').'">');
        ShowHTML('<INPUT disabled type="hidden" name="w_forma_atual[]" value="'.f($row,'sq_forma_pagamento').'">');
        ShowHTML('<INPUT disabled type="hidden" name="w_vencimento_atual[]" value="'.FormataDataEdicao(f($row,'vencimento')).'">');
        ShowHTML('<INPUT disabled type="hidden" name="w_outra_parte[]" value="'.f($row,'outra_parte').'">');
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;        
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="center">');
        ShowHTML('        <td align="center"><input type="checkbox" name="w_sq_acordo_parcela[]" value="'.f($row,'sq_acordo_parcela').'" onClick="valor('.$w_cont.');">');
        ShowHTML('        <td title="'.f($row,'objeto').'"><A class="hl" HREF="'.'mod_ac/contratos.php?par=Visual&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=GC'.substr($SG,2,1).'CAD" target="_blank">'.f($row,'cd_acordo').'</a></td>');
        ShowHTML('        <td>'.f($row,'nome_resumido').'</td>');
        ShowHTML('        <td>'.substr(1000+f($row,'ordem'),1,3).'</td>');
        ShowHTML('        <td>'.FormataDataEdicao(f($row,'vencimento')).'</td>');
        SelecaoTipoLancamento('','T', 'Selecione na lista o tipo de lan�amento adequado.', f($row,'sq_tipo_lancamento'), $w_cliente, 'w_sq_tipo_lancamento[]', $SG, 'disabled');
        ShowHTML('        <td><input type="text" disabled name="w_valor[]" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.number_format(Nvl(f($row,'valor'),0),2,',','.').'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor da parcela."></td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('<tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$R.'&R='.$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.'&O=L\';" name="Botao" value="Cancelar">');
    ShowHTML('  </td>');
    ShowHTML('</FORM>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } 
    ShowHTML('</tr>');    
  } elseif ($O=='A') {
    //Rotina para altera��o do dados da tabela de um esquema
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_sq_esquema" value="'.$w_sq_esquema.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_esquema_tabela" value="'.$w_sq_esquema_tabela.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('  <tr bgcolor="'.$conTrBgColor.'"><td colspan=2>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&w_sq_esquema='.$w_sq_esquema.'&R='.$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.'&O=L\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</form>');
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
// Rotina de visualiza��o
// -------------------------------------------------------------------------
function Visual() {
  extract($GLOBALS);
  $w_chave  = $_REQUEST['w_chave'];
  $w_tipo   = strtoupper(trim($_REQUEST['w_tipo']));
  if ($w_tipo=='WORD') {
    HeaderWord();
    if (substr($SG,0,4)=='FNRE') {
      CabecalhoWord($w_cliente,'Visualiza��o de Receita Eventual',$w_pag);
    } elseif (substr($SG,0,4)=='FNRC') {
      CabecalhoWord($w_cliente,'Visualiza��o de Receita de Contrato',$w_pag);
    } elseif (substr($SG,0,4)=='FNDE') {
      CabecalhoWord($w_cliente,'Visualiza��o de Despesa Eventual',$w_pag);
    } else {
      CabecalhoWord($w_cliente,'Visualiza��o de Despesa de Contrato',$w_pag);
    } 
  } else {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'') $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
    Cabecalho();
    ShowHTML('<HEAD>');
    if (substr($SG,0,4)=='FNRE') {
       ShowHTML('<TITLE>'.$conSgSistema.' - Visualiza��o de Receita Eventual</TITLE>');
    } elseif (substr($SG,0,4)=='FNRC') {
      ShowHTML('<TITLE>'.$conSgSistema.' - Visualiza��o de Receita de Contrato</TITLE>');
    } elseif (substr($SG,0,4)=='FNDE') {
      ShowHTML('<TITLE>'.$conSgSistema.' - Visualiza��o de Despesa Eventual</TITLE>');
    } else {
      ShowHTML('<TITLE>'.$conSgSistema.' - Visualiza��o de Despesa de Contrato</TITLE>');
    } 
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpenClean('onLoad=\'document.focus()\';'); 
    ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
    if (substr($SG,0,4)=='FNRE') {
      ShowHTML('Visualiza��o de Receita Eventual');
    } elseif (substr($SG,0,4)=='FNRC') {
      ShowHTML('Visualiza��o de Receita de Contrato');
    } elseif (substr($SG,0,4)=='FNDE') {
      ShowHTML('Visualiza��o de Despesa Eventual');
    } else {
      ShowHTML('Visualiza��o de Despesa de Contrato');
    } 
    ShowHTML('</FONT><TR valign="bottom"><TD ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
    if ($w_tipo!='WORD') {
      ShowHTML('&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      ShowHTML('&nbsp;&nbsp;<a target="MetaWord" href="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.$w_chave.'&w_tipo=word&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4=1&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
    } 
    ShowHTML('</FONT></B></TD></TR></TABLE>');
    ShowHTML('<HR>');
  }
  if ($w_tipo>'' && $w_tipo!='WORD') ShowHTML('<center><B>Clique <a class="hl" href="javascript:history.back(1);">aqui</a> para voltar � tela anterior</b></center>');
  // Chama a rotina de visualiza��o dos dados do lan�amento, na op��o 'Listagem'
  ShowHTML(VisualLancamento($w_chave,'L',$w_usuario,$P1,$P4));
  if ($w_tipo>'' && $w_tipo!='WORD') ShowHTML('<center><B>Clique <a class="hl" href="javascript:history.back(1);">aqui</a> para voltar � tela anterior</b></center>');
  if ($w_tipo!='WORD') {
    ShowHTML('</body>');
    ShowHTML('</html>');
  } 
} 
// =========================================================================
// Rotina de exclus�o
// -------------------------------------------------------------------------
function Excluir() {
  extract($GLOBALS);
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_observacao = $_REQUEST['w_observacao'];
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="300; URL=../'.MontaURL('MESA').'">');
  if ($O=='E') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_assinatura','Assinatura Eletr�nica', '1', '1', '6', '30', '1', '1');
    if ($P1!=1) {
      // Se n�o for encaminhamento
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    } 
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados do lan�amento, na op��o 'Listagem'
  ShowHTML(VisualLancamento($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="stb" type="submit" name="Botao" value="Excluir">');
  ShowHTML('      <input class="stb" type="button" onClick="history.back(1);" name="Botao" value="Abandonar">');
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 
// =========================================================================
// Rotina de tramita��o
// -------------------------------------------------------------------------
function Encaminhamento() {
  extract($GLOBALS);
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_erro       = '';
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_tramite=$_REQUEST['w_tramite'];
    $w_destinatario=$_REQUEST['w_destinatario'];
    $w_novo_tramite=$_REQUEST['w_novo_tramite'];
    $w_despacho=$_REQUEST['w_despacho'];
  } else {
    $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
    $w_tramite      = f($RS,'sq_siw_tramite');
    $w_novo_tramite = f($RS,'sq_siw_tramite');
  } 
  // Recupera a sigla do tr�mite desejado, para verificar a lista de poss�veis destinat�rios.
  $RS = db_getTramiteData::getInstanceOf($dbms,$w_novo_tramite);
  $w_sg_tramite = f($RS,'sigla');
  $w_ativo      = f($RS,'ativo');
  if ($w_ativo == 'N') {
    $RS = db_getTramiteList::getInstanceOf($dbms, $w_menu, null,'S');
    $RS = SortArray($RS,'ordem','asc');
    foreach ($RS as $row) {
      $w_novo_tramite = f($row,'sq_siw_tramite');
      $w_sg_tramite   = f($row,'sigla');
      break;
    }   
  }
  // Se for envio, executa verifica��es nos dados da solicita��o
  if ($O=='V') $w_erro = ValidaLancamento($w_cliente,$w_chave,$SG,null,null,null,$w_tramite);
  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="300; URL=../'.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_destinatario','Destinat�rio', 'HIDDEN', '1', '1', '10', '', '1');
    Validate('w_despacho','Despacho', '', '1', '1', '2000', '1', '1');
    Validate('w_assinatura','Assinatura Eletr�nica', '1', '1', '6', '30', '1', '1');
    if ($P1!=1) {
      // Se n�o for encaminhamento
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    } 
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_destinatario.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td colspan=2>');
  // Chama a rotina de visualiza��o dos dados do projeto, na op��o 'Listagem'
  ShowHTML(VisualLancamento($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,substr($SG,0,3).'ENVIO',$R,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite.'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td colspan=2>');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  if ($P1!=1) {
    // Se n�o for cadastramento
    if (Nvl($w_erro,'')=='' || (Nvl($w_erro,'')>'' && substr($w_erro,0,1)!='0' && RetornaGestor($w_chave,$w_usuario)=='S'))
      SelecaoFase('<u>F</u>ase do lan�amento:','F','Se deseja alterar a fase atual do lan�amento, selecione a fase para a qual deseja envi�-la.', $w_novo_tramite, $w_menu, 'w_novo_tramite', null, 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
    else
      SelecaoFase('<u>F</u>ase do lan�amento:','F','Se deseja alterar a fase atual do lan�amento, selecione a fase para a qual deseja envi�-la.', $w_novo_tramite, $w_tramite, 'w_novo_tramite', 'ERRO', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
    // Se for envio para o cadastramento, exibe apenas as pessoas autorizadas a faz�-lo.
    if ($w_sg_tramite=='CI')
      SelecaoSolicResp('<u>D</u>estinat�rio:','D','Selecione, na rela��o, um destinat�rio para o lan�amento.', $w_destinatario, $w_chave, $w_novo_tramite, $w_novo_tramite, 'w_destinatario', 'CADASTRAMENTO');
    else
      SelecaoSolicResp('<u>D</u>estinat�rio:','D','Selecione um destinat�rio para o lan�amento na rela��o.', $w_destinatario, $w_chave, $w_novo_tramite, $w_novo_tramite, 'w_destinatario', 'USUARIOS');
  } else {
    if (Nvl($w_erro,'')=='' || (Nvl($w_erro,'')>'' && substr($w_erro,0,1)!='0' && RetornaGestor($w_chave,$w_usuario)=='S'))
      SelecaoFase('<u>F</u>ase do lan�amento:','F','Se deseja alterar a fase atual do lan�amento, selecione a fase para a qual deseja envi�-la.', $w_novo_tramite, $w_menu, 'w_novo_tramite', null, 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
    else
      SelecaoFase('<u>F</u>ase do lan�amento:','F','Se deseja alterar a fase atual do lan�amento, selecione a fase para a qual deseja envi�-la.', $w_novo_tramite, $w_tramite, 'w_novo_tramite', 'ERRO', null);
    SelecaoSolicResp('<u>D</u>estinat�rio:','D','Selecione um destinat�rio para o lan�amento na rela��o.', $w_destinatario, $w_chave, $w_novo_tramite, $w_novo_tramite, 'w_destinatario', 'USUARIOS');
  } 
  ShowHTML('    <tr><td colspan=2><b>D<u>e</u>spacho:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="sti" ROWS=5 cols=75 title="Descreva a a��o esperada pelo destinat�rio na execu��o do lan�amento.">'.$w_despacho.'</TEXTAREA></td>');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="stb" type="submit" name="Botao" value="Enviar">');
  //if ($P1!=1) {
    // Se n�o for cadastramento
    // Volta para a listagem
    $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
    ShowHTML('      <input class="stb" type="button" onClick="location.href=\''.str_replace($w_dir,'',f($RS,'link')).'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS,'sigla').MontaFiltro('GET').'\';" name="Botao" value="Abandonar">');
  //} 
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 
// =========================================================================
// Rotina de anota��o
// -------------------------------------------------------------------------
function Anotar() {
  extract($GLOBALS);
  $w_chave=$_REQUEST['w_chave'];
  $w_chave_aux=$_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_observacao = $_REQUEST['w_observacao'];
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="300; URL=../'.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_observacao','Anota��o', '', '1', '1', '2000', '1', '1');
    Validate('w_caminho','Arquivo', '', '', '5', '255', '1', '1');
    Validate('w_assinatura','Assinatura Eletr�nica', '1', '1', '6', '30', '1', '1');
    if ($P1!=1) {
      // Se n�o for encaminhamento
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    } 
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'')
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  else
    BodyOpen('onLoad=\'document.Form.w_observacao.focus()\';');
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados do lan�amento, na op��o 'Listagem'
  ShowHTML(VisualLancamento($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG='.substr($SG,0,3).'ENVIO&O='.$O.'&w_menu='.$w_menu.'&UploadID='.$UploadID.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
  ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
  ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
  ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
  ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
  ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
  ShowHTML('<INPUT type="hidden" name="R" value="'.$R.'">');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b><font color="#BC3131">ATEN��O</font>: o tamanho m�ximo aceito para o arquivo � de '.f($RS,'upload_maximo').'/1024. KBytes</b>.</font></td>');
  ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
  ShowHTML('      <tr><td><b>A<u>n</u>ota��o:</b><br><textarea '.$w_Disabled.' accesskey="N" name="w_observacao" class="sti" ROWS=5 cols=75 title="Redija a anota��o desejada.">'.$w_observacao.'</TEXTAREA></td>');
  ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="sti" SIZE="80" MAXLENGTH="100" VALUE="" title="OPCIONAL. Se desejar anexar um arquivo, clique no bot�o ao lado para localiz�-lo. Ele ser� transferido automaticamente para o servidor.">');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="stb" type="submit" name="Botao" value="Gravar">');
  ShowHTML('      <input class="stb" type="button" onClick="history.back(1);" name="Botao" value="Abandonar">');
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 
// =========================================================================
// Rotina de conclus�o
// -------------------------------------------------------------------------
function Concluir() {
  extract($GLOBALS);
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_quitacao         = $_REQUEST['w_quitacao'];
    $w_valor_real       = $_REQUEST['w_valor_real'];
    $w_codigo_deposito  = $_REQUEST['w_codigo_deposito'];
    $w_observacao       = $_REQUEST['w_observacao'];
  } 
  // Recupera a sigla do tr�mite desejado, para verificar a lista de poss�veis destinat�rios.
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
  $w_tramite            = f($RS,'sq_siw_tramite');
  $w_valor_real         = number_format(f($RS,'valor'),2,',','.');
  $w_sg_forma_pagamento = f($RS,'sg_forma_pagamento');
  // Se for envio, executa verifica��es nos dados da solicita��o
  $w_erro = ValidaLancamento($w_cliente,$w_chave,$SG,null,null,null,$w_tramite);
  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="300; URL=../'.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    FormataDataHora();
    FormataValor();
    ValidateOpen('Validacao');
    Validate('w_quitacao','Data da liquida��o', 'DATA', 1, 10, 10, '', '0123456789/');
    CompData('w_quitacao','Data da liquida��o','<=',FormataDataEdicao(time()),'data atual');
    Validate('w_valor_real','Valor real','VALOR','1', 4, 18, '', '0123456789.,');
    if (w_sg_forma_pagamento=='DEPOSITO') Validate('w_codigo_deposito','C�digo do dep�sito', '1', '1', 1, 50, '1', '1');
    Validate('w_observacao','Observa��o', '', '', '1', '500', '1', '1');
    Validate('w_assinatura','Assinatura Eletr�nica', '1', '1', '6', '30', '1', '1');
    if ($P1!=1) {
       // Se n�o for encaminhamento
       ShowHTML('  theForm.Botao[0].disabled=true;');
       ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
       ShowHTML('  theForm.Botao.disabled=true;');
    } 
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($w_erro>'' && substr(Nvl($w_erro,'-'),0,1)=='0') {
    BodyOpen('onLoad=\'document.Form.Botao.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_quitacao.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados do lan�amento, na op��o 'Listagem'
  ShowHTML(VisualLancamento($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG='.substr($SG,0,3).'CONC&O='.$O.'&w_menu='.$w_menu.'&UploadID='.$UploadID.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
  ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
  ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
  ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
  ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
  ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
  ShowHTML('<INPUT type="hidden" name="R" value="'.$R.'">');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_concluida" value="S">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  if ($w_erro>'' && substr(Nvl($w_erro,'-'),0,1)=='0') {
     ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b>N�o � poss�vel liquidar o lan�amento enquanto as corre��es listadas n�o forem feitas.</b></font></td>');
     ShowHTML('    <tr><td align="center" colspan=4><hr>');
      ShowHTML('      <input class="stb" type="button" onClick="history.back(1);" name="Botao" value="Abandonar">');
  } else {
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b><font color="#BC3131">ATEN��O</font>: o tamanho m�ximo aceito para o arquivo � de '.f($RS,'upload_maximo').'/1024. KBytes</b>.</font></td>');
    ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('          <tr>');
    ShowHTML('              <td><b><u>D</u>ata da liquida��o:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_quitacao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_quitacao,FormataDataEdicao(time())).'" onKeyDown="FormataData(this,event);" title="Informe a data de liquida��o deste lan�amento."></td>');
    ShowHTML('              <td><b>Valo<u>r</u> real:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_valor_real" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor_real.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor real do lan�amento."></td>');
    if ($w_sg_forma_pagamento=='DEPOSITO')
      ShowHTML('              <td><b><u>C</u>�digo do dep�sito:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_codigo_deposito" class="sti" SIZE="20" MAXLENGTH="50" VALUE="'.$w_codigo_deposito.'" title="Informe o c�digo do dep�sito identificado."></td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td><b>Obs<u>e</u>rva��o:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_observacao" class="sti" ROWS=5 cols=75 title="Descreva o quanto a demanda atendeu aos resultados esperados.">'.$w_observacao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="sti" SIZE="80" MAXLENGTH="100" VALUE="" title="OPCIONAL. Se desejar anexar um arquivo, clique no bot�o ao lado para localiz�-lo. Ele ser� transferido automaticamente para o servidor.">');
    ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('    <tr><td align="center" colspan=4><hr>');
    ShowHTML('      <input class="stb" type="submit" name="Botao" value="Liquidar">');
    if ($P1!=1) {
      // Se n�o for cadastramento
      ShowHTML('      <input class="stb" type="button" onClick="history.back(1);" name="Botao" value="Abandonar">');
    } 
  } 
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 
// =========================================================================
// Rotina de prepara��o para envio de e-mail relativo a lan�amentos
// Finalidade: preparar os dados necess�rios ao envio autom�tico de e-mail
// Par�metro: p_solic: n�mero de identifica��o da solicita��o. 
//            p_tipo:  1 - Inclus�o
//                     2 - Tramita��o
//                     3 - Conclus�o
// -------------------------------------------------------------------------
function SolicMail($p_solic,$p_tipo) {
  extract($GLOBALS);
  global $w_Disabled;
  $l_solic          = $p_solic;
  $w_destinatarios  = '';
  $w_resultado      = '';
  // Recupera os dados da tarefa
  $RSM = db_getSolicData::getInstanceOf($dbms,$p_solic,substr($SG,0,3).'GERAL');
  $w_html='<HTML>'.chr(13);
  $w_html.=BodyOpenMail(null).chr(13);
  $w_html.='<table border="0" cellpadding="0" cellspacing="0" width="100%">'.chr(13);
  $w_html.='<tr bgcolor="'.$conTrBgColor.'"><td align="center">'.chr(13);
  $w_html.='    <table width="97%" border="0">'.chr(13);
  if ($p_tipo==1) {
    $w_html.='      <tr valign="top"><td align="center"><font size=2><b>INCLUS�O DE '.strtoupper(f($RSM,'nome')).'</b></font><br><br><td></tr>'.chr(13);
  } elseif ($p_tipo==2) {
    $w_html.='      <tr valign="top"><td align="center"><font size=2><b>TRAMITA��O DE '.strtoupper(f($RSM,'nome')).'</b></font><br><br><td></tr>'.chr(13);
  } elseif ($p_tipo==3) {
   $w_html.='      <tr valign="top"><td align="center"><font size=2><b>CONCLUS�O DE '.strtoupper(f($RSM,'nome')).'</b></font><br><br><td></tr>'.chr(13);
  } 
  $w_html.='      <tr valign="top"><td><font size=2><b><font color="#BC3131">ATEN��O</font>: Esta � uma mensagem de envio autom�tico. N�o responda esta mensagem.</b></font><br><br><td></td>'.chr(13);
  $w_nome=f($RSM,'nome').' '.f($RSM,'codigo_interno').' ('.f($RSM,'sq_siw_solicitacao').')';
  $w_html.=chr(13).'<tr bgcolor="'.$conTrBgColor.'"><td align="center">';
  $w_html.=chr(13).'    <table width="99%" border="0">';
  $w_html.=chr(13).'      <tr><td>Tipo de lan�amento: <b>'.f($RSM,'nm_tipo_lancamento').' </b></td>';
  $w_html.=chr(13).'      <tr><td>Finalidade: <b>'.f($RSM,'codigo_interno').' ('.f($RSM,'sq_siw_solicitacao').')<br>'.CRLF2BR(f($RSM,'descricao')).'</b></td></tr>';
  // Identifica��o do contrato
  $w_html.=chr(13).'      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>EXTRATO DO LAN�AMENTO</td>';
  if (Nvl(f($RSM,'nm_projeto'),'')>'') $w_html.=chr(13).'      <tr><td>Projeto: <br><b>'.f($RSM,'nm_projeto').'  ('.f($RSM,'sq_solic_pai').')</b></td>';
  // Se a classifica��o foi informada, exibe.
  if (Nvl(f($RSM,'sq_cc'),'')>'') {
    $w_html.=chr(13).'      <tr><td>Classifica��o:<br><b>'.f($RSM,'nm_cc').' </b></td>';
  } 
  $w_html.=chr(13).'      <tr><td><table border=0 width="100%" cellspacing=0>';
  $w_html.=chr(13).'          <tr valign="top">';
  $w_html.=chr(13).'          <td>Forma de pagamento:<br><b>'.f($RSM,'nm_forma_pagamento').' </b></td>';
  $w_html.=chr(13).'          <td>Vencimento:<br><b>'.FormataDataEdicao(f($RSM,'vencimento')).' </b></td>';
  $w_html.=chr(13).'          <td>Valor:<br><b>'.number_format(Nvl(f($RSM,'valor'),0),2,',','.').' </b></td>';
  $w_html.=chr(13).'          </table>';
  // Outra parte
  $RSM1 = db_getBenef::getInstanceOf($dbms,$w_cliente,Nvl(f($RSM,'pessoa'),0),null,null,null,Nvl(f($RSM,'sq_tipo_pessoa'),0),null,null);
  if (count($RSM1) > 0) {
    foreach ($RSM1 as $row)
    $w_html.=chr(13).'      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>OUTRA PARTE</td>';
    $w_html.=chr(13).'      <tr><td><b>';
    $w_html.=chr(13).'          '.f($row,'nm_pessoa').' ('.f($row,'nome_resumido').')';
    if (Nvl(f($RSM,'sq_tipo_pessoa'),0)==1) {
      $w_html.=chr(13).'          - '.f($row,'cpf'); 
    } else {
      $w_html.=chr(13).'          - '.f($row,'cnpj');
    } 
  } 
  if ($p_tipo==3) {
    // Se for conclus�o
    // Dados da conclus�o do lan�amento, se ela estiver nessa situa��o
    if (Nvl(f($RSM,'conclusao'),'')>'' && Nvl(f($RSM,'quitacao'),'')>'') {
      $w_html.=chr(13).'      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>DADOS DA LIQUIDA��O</td>';
      $w_html.=chr(13).'      <tr><td><table border=0 width="100%" cellspacing=0>';
      $w_html.=chr(13).'          <tr valign="top">';
      $w_html.=chr(13).'          <td>Data:<br><b>'.FormataDataEdicao(f($RSM,'quitacao')).' </b></td>';
      if (Nvl(f($RSM,'codigo_deposito'),'')>'') $w_html.=chr(13).'          <td>C�digo do dep�sito:<br><b>'.f($RSM,'codigo_deposito').' </b></td>';
      $w_html.=chr(13).'          </table>';
      $w_html.=chr(13).'      <tr><td>Observa��o:<br><b>'.CRLF2BR(Nvl(f($RSM,'observacao'),'---')).' </b></td>';
    } 
    if (Nvl(f($RSM,'nm_cc'),'')>'') {
      // Se for vinculado a classifica��o, envia aos que participaram da tramita��o
      $RS = db_getSolicLog::getInstanceOf($dbms,$p_solic,null,'LISTA');
      $RS = SortArray($RS,'data desc','asc');
      foreach($RS as $row) {
        if (f($row,'sq_pessoa_destinatario')>'') {
          // Configura os destinat�rios da mensagem
          $RS1 = db_getPersonData::getInstanceOf($dbms,$w_cliente,f($row,'sq_pessoa_destinatario'),null,null);
          if (!(strpos($w_destinatarios,f($RS1,'email').'; ')===false) && Nvl(f($RS1,'email'),'nulo')!='nulo')
            $w_destinatarios = $w_destinatarios.f($RS1,'email').'; ';
        } 
      } 
    } else {
      // Caso contr�rio envia para o respons�vel pelo projeto 
      $RS = db_getUorgResp::getInstanceOf($dbms,f($RSM,'sq_unidade'));
      if (!(strpos($w_destinatarios,f($RS,'email_titular').'; ')===false) && Nvl(f($RS,'email_titular'),'nulo')!='nulo')
        $w_destinatarios=$w_destinatarios.f($RS,'email_titular').';    ';
      if (!(strpos($w_destinatarios,f($RS,'email_substituto').'; ')===false) && Nvl(f($RS,'email_substituto'),'nulo')!='nulo')
        $w_destinatarios=$w_destinatarios.f($RS,'email_substituto').'; ';
    }
  }
  //Recupera o �ltimo log
  $RS = db_getSolicLog::getInstanceOf($dbms,$p_solic,null,'LISTA');
  $RS = SortArray($RS,'phpdt_data','desc');
  foreach ($RS as $row) { $RS = $row; break; }
  $w_data_encaminhamento = f($RS,'phpdt_data');
  if ($p_tipo==2) {
    $w_html.=chr(13).'      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>�LTIMO ENCAMINHAMENTO</td>';
    $w_html.=chr(13).'      <tr><td><table border=0 width="100%" cellspacing=0>';
    $w_html.=chr(13).'          <tr valign="top">';
    $w_html.=chr(13).'          <td>De:<br><b>'.f($RS,'responsavel').'</b></td>';
    $w_html.=chr(13).'          <td>Para:<br><b>'.f($RS,'destinatario').'</b></td>';
    $w_html.=chr(13).'          <tr valign="top"><td colspan=2>Despacho:<br><b>'.CRLF2BR(Nvl(f($RS,'despacho'),'---')).' </b></td>';
    $w_html.=chr(13).'          </table>';
    // Configura o destinat�rio da tramita��o como destinat�rio da mensagem
    $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,f($RS,'sq_pessoa_destinatario'),null,null);
    $w_destinatarios = f($RS,'email').'; ';      
  } 
  $w_html.=chr(13).'      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>OUTRAS INFORMA��ES</td>';
  $RS = db_getCustomerSite::getInstanceOf($dbms,$w_cliente);
  $w_html.='      <tr valign="top"><td>'.chr(13);
  $w_html.='         Para acessar o sistema use o endere�o: <b><a class="ss" href="'.f($RS,'logradouro').'" target="_blank">'.f($RS,'Logradouro').'</a></b></li>'.chr(13);
  $w_html.='      </td></tr>'.chr(13);
  $w_html.='      <tr valign="top"><td>'.chr(13);
  $w_html.='         Dados da ocorr�ncia:<br>'.chr(13);
  $w_html.='         <ul>'.chr(13);
  $w_html.='         <li>Respons�vel: <b>'.$_SESSION['NOME'].'</b></li>'.chr(13);
  $w_html.='         <li>Data<b> '.date('d/m/Y, H:i:s',$w_data_encaminhamento).'</b></li>'.chr(13);
  $w_html.='         <li>IP de origem: <b>'.$_SERVER['REMOTE_ADDR'].'</b></li>'.chr(13);
  $w_html.='         </ul>'.chr(13);
  $w_html.='      </td></tr>'.chr(13);
  $w_html.='    </table>'.chr(13);
  $w_html.='</td></tr>'.chr(13);
  $w_html.='</table>'.chr(13);
  $w_html.='</table>'.chr(13);
  $w_html.='</BODY>'.chr(13);
  $w_html.='</HTML>'.chr(13);
  // Prepara os dados necess�rios ao envio
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  if ($p_tipo==1 || $p_tipo==3) {
    // Inclus�o ou Conclus�o
    if ($p_tipo==1) {
      $w_assunto = 'Inclus�o - '.$w_nome;
    } else {
      $w_assunto = 'Conclus�o - '.$w_nome;
    }
  } elseif ($p_tipo==2) {
    // Tramita��o
    $w_assunto = ' Tramita��o - '.$w_nome;
  } 
  if ($w_destinatarios>'') {
    // Executa o envio do e-mail
    $w_resultado = EnviaMail($w_assunto,$w_html,$w_destinatarios,null);
  } 
  // Se ocorreu algum erro, avisa da impossibilidade de envio
  if (Nvl($w_resultado,'')>'') {
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'ATEN��O: n�o foi poss�vel proceder o envio do e-mail.\n'.$w_resultado.'\');');
    ScriptClose();
  } 
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
  BodyOpen('onLoad=document.focus();');
  if (!(strpos($SG,'EVENT')===false)) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putFinanceiroGeral::getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_menu'],
          $_REQUEST['w_sq_unidade'],$_REQUEST['w_solicitante'],$_SESSION['SQ_PESSOA'],$_REQUEST['w_sqcc'],
          $_REQUEST['w_descricao'],$_REQUEST['w_vencimento'],Nvl($_REQUEST['w_valor'],0),$_REQUEST['w_data_hora'],
          $_REQUEST['w_aviso'],$_REQUEST['w_dias'],$_REQUEST['w_cidade'],$_REQUEST['w_chave_pai'],
          $_REQUEST['w_sq_acordo_parcela'],$_REQUEST['w_observacao'],Nvl($_REQUEST['w_sq_tipo_lancamento'],''),
          Nvl($_REQUEST['w_sq_forma_pagamento'],''),$_REQUEST['w_tipo_pessoa'],$_REQUEST['w_forma_atual'],
          $_REQUEST['w_vencimento_atual'],&$w_chave_nova,&$w_codigo);
      if ($O=='I') {
        //Envia e-mail comunicando a inclus�o
        SolicMail(Nvl($_REQUEST['w_chave'],$w_chave_nova),1);
      }
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.$R.'&O=L&R='.$R.'&SG='.$SG.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ShowHTML('  history.back(1);');
      ScriptClose();
    } 
  } elseif (!(strpos($SG,'OUTRAP')===false)) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putLancamentoOutra::getInstanceOf($dbms,$O,$SG,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_sq_pessoa'],
          $_REQUEST['w_cpf'],$_REQUEST['w_cnpj'],$_REQUEST['w_nome'],$_REQUEST['w_nome_resumido'],$_REQUEST['w_sexo'],
          $_REQUEST['w_nascimento'],$_REQUEST['w_rg_numero'],$_REQUEST['w_rg_emissao'],$_REQUEST['w_rg_emissor'],
          $_REQUEST['w_passaporte_numero'],$_REQUEST['w_sq_pais_passaporte'],$_REQUEST['w_inscricao_estadual'],
          $_REQUEST['w_logradouro'],$_REQUEST['w_complemento'],$_REQUEST['w_bairro'],$_REQUEST['w_sq_cidade'],$_REQUEST['w_cep'],
          $_REQUEST['w_ddd'],$_REQUEST['w_nr_telefone'],$_REQUEST['w_nr_fax'],$_REQUEST['w_nr_celular'],$_REQUEST['w_email'],
          $_REQUEST['w_sq_agencia'],$_REQUEST['w_operacao'],$_REQUEST['w_nr_conta'],$_REQUEST['w_sq_pais_estrang'],
          $_REQUEST['w_aba_code'],$_REQUEST['w_swift_code'],$_REQUEST['w_endereco_estrang'],$_REQUEST['w_banco_estrang'],
          $_REQUEST['w_agencia_estrang'],$_REQUEST['w_cidade_estrang'],$_REQUEST['w_informacoes'],$_REQUEST['w_codigo_deposito'],
          $_REQUEST['w_tipo_pessoa_atual']);
      ScriptOpen('JavaScript');
      ShowHTML('  window.close();');
      ShowHTML('  opener.location.reload();');
      ShowHTML('  opener.focus();');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ShowHTML('  history.back(1);');
      ScriptClose();
    } 
    // Inclus�o, Altera��o e exclus�o de documentos relativos a um lan�amento.
  } elseif ($SG=='DOCUMENTO') {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putLancamentoDoc::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_sq_tipo_documento'],
        $_REQUEST['w_numero'],$_REQUEST['w_data'],$_REQUEST['w_serie'],$_REQUEST['w_valor'],$_REQUEST['w_patrimonio'],$_REQUEST['w_retencao'],
        $_REQUEST['w_tributo']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.$R.'&O=L&w_menu='.$_REQUEST['w_menu'].'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ShowHTML('  history.back(1);');
      ScriptClose();
    } 
    // Envio de lan�amentos.
  } elseif (!(strpos($SG,'ENVIO')===false)) {
    // Verifica se a Assinatura Eletr�nica � v�lida 
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      // Trata o recebimento de upload ou dados 
      if ((false!==(strpos(strtoupper($_SERVER['HTTP_CONTENT_TYPE']),'MULTIPART/FORM-DATA'))) || (false!==(strpos(strtoupper($_SERVER['CONTENT_TYPE']),'MULTIPART/FORM-DATA')))) {
        // Se foi feito o upload de um arquivo 
        if (UPLOAD_ERR_OK==0) {
          $w_maximo = $_REQUEST['w_upload_maximo'];
          foreach ($_FILES as $Chv => $Field) {
            if ($Field['size'] > 0) {
              // Verifica se o tamanho das fotos est� compat�vel com  o limite de 100KB. 
              if ($Field['size'] > $w_maximo) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'Aten��o: o tamanho m�ximo do arquivo n�o pode exceder '.($w_maximo/1024).' KBytes!\');');
                ShowHTML('  history.go(-1);');
                ScriptClose();
                exit();
              } 
              // Se j� h� um nome para o arquivo, mant�m 
              $w_file = basename($Field['tmp_name']);
              if (!(strpos($Field['name'],'.')===false)) {
                $w_file = $w_file.'.'.substr($Field['name'],(strpos($Field['name'],'.') ? strpos($Field['name'],'.')+1 : 0)-1,10);
              }
              $w_tamanho = $Field['size'];
              $w_tipo    = $Field['type'];
              $w_nome    = $Field['name'];
              if ($w_file>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
            } 
          } 
          dml_putLancamentoEnvio::getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
              $_REQUEST['w_novo_tramite'],'N',$_REQUEST['w_observacao'],$_REQUEST['w_destinatario'],$_REQUEST['w_despacho'],
              $w_file,$w_tamanho,$w_tipo,$w_nome);
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATEN��O: ocorreu um erro na transfer�ncia do arquivo. Tente novamente!\');');
          ScriptClose();
        } 
        ScriptOpen('JavaScript');
        // Volta para a listagem
        $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
        ShowHTML('  location.href=\''.str_replace($w_dir,'',f($RS,'link')).'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.RemoveTP($TP).'&SG='.f($RS,'sigla').MontaFiltro('GET').'\';');
        ScriptClose();
      } else {
        dml_putLancamentoEnvio::getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
          $_REQUEST['w_novo_tramite'],'N',$_REQUEST['w_observacao'],$_REQUEST['w_destinatario'],$_REQUEST['w_despacho'],
          null,null,null,null);
        // Envia e-mail comunicando de tramita��o
        SolicMail($_REQUEST['w_chave'],2);
        ScriptOpen('JavaScript');
        // Volta para a listagem 
        $RS = db_getMenuData::getInstanceOf($dbms,$_REQUEST['w_menu']);
        ShowHTML('  location.href=\''.str_replace($w_dir,'',f($RS,'link')).'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.RemoveTP($TP).'&SG='.f($RS,'sigla').MontaFiltro('GET').'\';');
        ScriptClose();
      }
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ShowHTML('  history.back(1);');
      ScriptClose();
    } 
  } elseif (!(strpos($SG,'CONC')===false)) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],$SG);
      if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATEN��O: Outro usu�rio j� encaminhou este contrato para outra fase!\');');
        ScriptClose();
      } else {
        // Se foi feito o upload de um arquivo 
        if (UPLOAD_ERR_OK==0) {
          $w_maximo = $_REQUEST['w_upload_maximo'];
          foreach ($_FILES as $Chv => $Field) {
            if ($Field['size'] > 0) {
              // Verifica se o tamanho das fotos est� compat�vel com  o limite de 100KB. 
              if ($Field['size'] > $w_maximo) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'Aten��o: o tamanho m�ximo do arquivo n�o pode exceder '.($w_maximo/1024).' KBytes!\');');
                ShowHTML('  history.go(-1);');
                ScriptClose();
                exit();
              } 
              // Se j� h� um nome para o arquivo, mant�m 
              $w_file = basename($Field['tmp_name']);
              if (!(strpos($Field['name'],'.')===false)) {
                $w_file = $w_file.'.'.substr($Field['name'],(strpos($Field['name'],'.') ? strpos($Field['name'],'.')+1 : 0)-1,10);
              }
              $w_tamanho = $Field['size'];
              $w_tipo    = $Field['type'];
              $w_nome    = $Field['name'];
              if ($w_file>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
            } 
          } 
          dml_putFinanceiroConc::getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_quitacao'],
            $_REQUEST['w_valor_real'],$_REQUEST['w_codigo_deposito'],$_REQUEST['w_observacao'],$w_file,$w_tamanho,$w_tipo,$w_nome);
          // Envia e-mail comunicando a conclus�o
          SolicMail($_REQUEST['w_chave'],3);
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATEN��O: ocorreu um erro na transfer�ncia do arquivo. Tente novamente!\');');
          ScriptClose();
        } 
      } 
      // Volta para a listagem
      ScriptOpen('JavaScript');
      $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
      ShowHTML('  location.href=\''.str_replace($w_dir,'',f($RS,'link')).'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS,'sigla').MontaFiltro('GET').'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ShowHTML('  history.back(1);');
      ScriptClose();
    } 
  } elseif ($SG=='FNDCONT' || $SG=='FNRCONT') {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if ($O=='I') {
        for ($i=0; $i<=count($_POST['w_sq_acordo_parcela'])-1; $i=$i+1) {
          if ($_REQUEST['w_sq_acordo_parcela'][$i]>'') {
            dml_putFinanceiroGeral::getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave'],$w_menu,$_REQUEST['w_sq_unidade'],
                $_REQUEST['w_solicitante'][$i],$w_usuario,$_REQUEST['w_sqcc'][$i],$_REQUEST['w_descricao'][$i],$_REQUEST['w_vencimento'][$i],
                Nvl($_REQUEST['w_valor'][$i],0),$_REQUEST['w_data_hora'],$_REQUEST['w_aviso'],$_REQUEST['w_dias'],$_REQUEST['w_cidade'],
                $_REQUEST['w_chave_pai'][$i],$_REQUEST['w_sq_acordo_parcela'][$i],$_REQUEST['w_observacao'],$_REQUEST['w_sq_tipo_lancamento'][$i],
                $_REQUEST['w_sq_forma_pagamento'][$i],$_REQUEST['w_tipo_pessoa'][$i],$_REQUEST['w_forma_atual'][$i],$_REQUEST['w_vencimento_atual'][$i],
                &$w_chave_nova,&$w_codigo);
            //Recupera os dados da pessoa associada ao lan�amento
            $RS = db_getBenef::getInstanceOf($dbms,$w_cliente,$_REQUEST['w_outra_parte'][$i],null,null,null,null,null,null);
            foreach ($RS as $row) {$RS=$row; break;}
            //Grava os dados da pessoa
            dml_putLancamentoOutra::getInstanceOf($dbms,$O,$SG,$w_chave_nova,$w_cliente,$_REQUEST['w_outra_parte'][$i],f($RS,'cpf'),f($RS,'cnpj'),
                null,null,null,null,null,null,null,null,null,null,f($RS,'logradouro'),f($RS,'complemento'),f($RS,'bairro'),f($RS,'sq_cidade'),
                f($RS,'cep'),f($RS,'ddd'),f($RS,'nr_telefone'),f($RS,'nr_fax'),f($RS,'nr_celular'),f($RS,'email'),null,null,null,null,null,null,
                null,null,null,null,null,null,null);
          }
        } 
      } else {
        dml_putFinanceiroGeral::getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_menu'],$_REQUEST['w_sq_unidade'],$_REQUEST['w_solicitante'],$_SESSION['SQ_PESSOA'],
            $_REQUEST['w_sqcc'],$_REQUEST['w_descricao'],$_REQUEST['w_vencimento'],Nvl($_REQUEST['w_valor'],0),$_REQUEST['w_data_hora'],$_REQUEST['w_aviso'],$_REQUEST['w_dias'],
            $_REQUEST['w_cidade'],$_REQUEST['w_chave_pai'],$_REQUEST['w_sq_acordo_parcela'],$_REQUEST['w_observacao'],Nvl($_REQUEST['w_sq_tipo_lancamento'],''),Nvl($_REQUEST['w_sq_forma_pagamento'],''),
            $_REQUEST['w_tipo_pessoa'],$_REQUEST['w_forma_atual'],$_REQUEST['w_vencimento_atual'],&$w_chave_nova,&$w_codigo);
      } 
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.$R.'&O=L&R='.$R.'&SG='.$SG.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ShowHTML('  history.back(1);');
      ScriptClose();
    } 
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'Bloco de dados n�o encontrado: '.$SG.'\');');
    ShowHTML('  history.back(1);');
    ScriptClose();
  }
} 
// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'INICIAL':         Inicial();          break;
    case 'GERAL':           Geral();            break;
    case 'OUTRAPARTE':      OutraParte();       break;
    case 'DOCUMENTO':       Documentos();       break;
    case 'BUSCAPARCELA':    BuscaParcela();     break;
    case 'VISUAL':          Visual();           break;
    case 'EXCLUIR':         Excluir();          break;
    case 'ENVIO':           Encaminhamento();   break;
    case 'TRAMITE':         Tramitacao();       break;
    case 'ANOTACAO':        Anotar();           break;
    case 'CONCLUIR':        Concluir();         break;
    case 'GRAVA':           Grava();            break;
    default:
      cabecalho();
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');      
      BodyOpen('onLoad=document.focus();');
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