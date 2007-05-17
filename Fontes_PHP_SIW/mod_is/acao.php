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
include_once($w_dir_volta.'classes/sp/db_getSolicList_IS.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_getAcao_IS.php');
include_once($w_dir_volta.'classes/sp/db_get10PercentDays_IS.php');
include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData_IS.php');
include_once($w_dir_volta.'classes/sp/db_getSolicMeta_IS.php');
include_once($w_dir_volta.'classes/sp/db_getMetaMensal_IS.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getFinancAcaoPPA_IS.php');
include_once($w_dir_volta.'classes/sp/db_getPPADadoFinanc_IS.php');
include_once($w_dir_volta.'classes/sp/db_getRestricao_IS.php');
include_once($w_dir_volta.'classes/sp/db_getPPALocalizador_IS.php');
include_once($w_dir_volta.'classes/sp/db_getTPRestricao_IS.php');
include_once($w_dir_volta.'classes/sp/db_getSolicInter.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteData.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/db_getUorgResp.php');
include_once($w_dir_volta.'classes/sp/dml_putAcaoGeral_IS.php');
include_once($w_dir_volta.'classes/sp/dml_putAcaoMeta_IS.php');
include_once($w_dir_volta.'classes/sp/dml_putRespAcao_IS.php');
include_once($w_dir_volta.'classes/sp/dml_putProgQualitativa_IS.php');
include_once($w_dir_volta.'classes/sp/dml_putMetaMensal_IS.php');
include_once($w_dir_volta.'classes/sp/dml_putMetaMensalIni_IS.php');
include_once($w_dir_volta.'classes/sp/dml_putFinancAcaoPPA_IS.php');
include_once($w_dir_volta.'classes/sp/dml_putRestricao_IS.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoInter.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicArquivo.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putAtualizaMeta_IS.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoConc.php');
include_once($w_dir_volta.'funcoes/selecaoIsProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoAcaoPPA.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade_IS.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoTPRestricao_IS.php');
include_once($w_dir_volta.'funcoes/selecaoFase.php');
include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');
include_once($w_dir_volta.'funcoes/selecaoAcao.php');
include_once('visualacao.php');
// =========================================================================
// acao.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho 
// Descricao: Gerencia o módulo de ações
// Mail     : celso@sbpi.com.br
// Criacao  : 26/07/2006 14:30
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
$w_pagina       = 'acao.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_is/';
$w_troca        = $_REQUEST['w_troca'];
$w_SG           = strtoupper($_REQUEST['w_SG']);
$p_ordena       = strtolower($_REQUEST['p_ordena']);
if ($SG=='ISMETA' || $SG=='ISACINTERE' || $SG=='ISACRESP' || $SG=='ISACANEXO' || $SG=='ISACPROFIN' || $SG=='ISACRESTR') {
  if ($O!='I' && $O!='E' && $_REQUEST['w_chave_aux']=='') $O='L';
} elseif ($SG=='ISACENVIO') {
  $O='V';
} elseif ($O=='') {
  // Se for acompanhamento, entra na filtragem
  if ($P1==3) $O='P'; else $O='L';
} 
switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';        break;
  case 'A': $w_TP=$TP.' - Alteração';       break;
  case 'E': $w_TP=$TP.' - Exclusão';        break;
  case 'P': $w_TP=$TP.' - Filtragem';       break;
  case 'C': $w_TP=$TP.' - Cópia';           break;
  case 'V': $w_TP=$TP.' - Envio';           break;
  case 'H': $w_TP=$TP.' - Herança';         break;
  default:
    if ($par=='BUSCAACAO') $w_TP=$TP.' - Busca ação'; else $w_TP=$TP.' - Listagem';
    break;
} 
// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_ano      = RetornaAno();
$w_copia            = $_REQUEST['w_copia'];
$p_acao             = strtoupper($_REQUEST['p_acao']);
$p_atividade        = strtoupper($_REQUEST['p_atividade']);
$p_ativo            = strtoupper($_REQUEST['p_ativo']);
$p_solicitante      = strtoupper($_REQUEST['p_solicitante']);
$p_prioridade       = strtoupper($_REQUEST['p_prioridade']);
$p_unidade          = strtoupper($_REQUEST['p_unidade']);
$p_proponente       = strtoupper($_REQUEST['p_proponente']);
$p_ini_i            = strtoupper($_REQUEST['p_ini_i']);
$p_ini_f            = strtoupper($_REQUEST['p_ini_f']);
$p_fim_i            = strtoupper($_REQUEST['p_fim_i']);
$p_fim_f            = strtoupper($_REQUEST['p_fim_f']);
$p_atraso           = strtoupper($_REQUEST['p_atraso']);
$p_chave            = strtoupper($_REQUEST['p_chave']);
$p_assunto          = strtoupper($_REQUEST['p_assunto']);
$p_pais             = strtoupper($_REQUEST['p_pais']);
$p_regiao           = strtoupper($_REQUEST['p_regiao']);
$p_uf               = strtoupper($_REQUEST['p_uf']);
$p_cidade           = strtoupper($_REQUEST['p_cidade']);
$p_usu_resp         = strtoupper($_REQUEST['p_usu_resp']);
$p_uorg_resp        = strtoupper($_REQUEST['p_uorg_resp']);
$p_palavra          = strtoupper($_REQUEST['p_palavra']);
$p_prazo            = strtoupper($_REQUEST['p_prazo']);
$p_fase             = explodeArray($_REQUEST['p_fase']);
$p_sqcc             = strtoupper($_REQUEST['p_sqcc']);
$p_sq_acao_ppa      = strtoupper($_REQUEST['p_sq_acao_ppa']);
$p_sq_isprojeto     = strtoupper($_REQUEST['p_sq_isprojeto']);
$p_qtd_restricao    = strtoupper($_REQUEST['p_qtd_restricao']);
// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$RS = db_getLinkSubMenu::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu = 'Existe';
} else {
  $w_submenu = '';
}
// Recupera a configuração do serviço
if ($P2>0) {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,$P2);
} else {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,$w_menu);
}
// Se for sub-menu, pega a configuração do pai
if (f($RS_Menu,'ultimo_nivel')=='S') { 
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 
Main();
FechaSessao($dbms);
exit;
// =========================================================================
// Rotina de visualização resumida dos registros
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;
  if ($O=='L') {
    if (!(strpos(strtoupper($R),'GR_')===false)) {
      $w_filtro='';
      if ($p_acao>'' && $p_sq_acao_ppa=='') {
        $RS = db_getSolicData_IS::getInstanceOf($dbms,$p_acao,'ISACGERAL');
        foreach ($RS as $row){$RS=$row; break;}
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Ação <td>[<b>'.f($RS,'titulo').'</b>]';
      } 
      if ($p_sq_acao_ppa>'') {
        $RS = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,substr($p_sq_acao_ppa,0,4),substr($p_sq_acao_ppa,4,4),null,substr($p_sq_acao_ppa,12,17),null,null,null,null,null);
        foreach ($RS as $row){$RS=$row; break;}
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Ação <td>[<b>'.f($RS,'cd_unidade').'.'.f($RS,'cd_programa').'.'.f($RS,'cd_acao').' - '.f($RS,'descricao_acao').' ('.f($RS,'ds_unidade').')</b>]';
      } 
      if ($p_sq_isprojeto>'') {
        $RS = db_getProjeto_IS::getInstanceOf($dbms,$p_sq_isprojeto,$w_cliente,null,null,null,null,null,null,null,null,null,null,null,null);
        foreach ($RS as $row){$RS=$row; break;}
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Programa interno<td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_chave>'') $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Demanda nº <td>[<b>'.$p_chave.'</b>]';
      if ($p_prazo>'') $w_filtro=$w_filtro.' <tr valign="top"><td align="right">Prazo para conclusão até<td>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]';
      if ($p_solicitante>'') {
        $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Responsável <td>[<b>'.f($RS,'nome_resumido').'</b>]';
      } 
      if ($p_unidade>'') {
        $RS = db_getUorgData::getInstanceOf($dbms,$p_unidade);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Área planejamento <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_usu_resp>'') {
        $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_usu_resp,null,null);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Executor <td>[<b>'.f($RS,'nome_resumido').'</b>]';
      } 
      if ($p_uorg_resp>'') {
        $RS = db_getUorgData::getInstanceOf($dbms,$p_uorg_resp);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Unidade atual <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_proponente>'') $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Parcerias externas<td>[<b>'.$p_proponente.'</b>]';
      if ($p_assunto>'')    $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Assunto <td>[<b>'.$p_assunto.'</b>]';
      if ($p_palavra>'')    $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Parcerias internas <td>[<b>'.$p_palavra.'</b>]';
      if ($p_ini_i>'')      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Data recebimento <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
      if ($p_fim_i>'')      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Limite conclusão <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]';
      if ($p_atraso=='S')   $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Situação <td>[<b>Apenas atrasadas</b>]';
      if ($p_qtd_restricao=='S') $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Situação <td>[<b>Apenas ações com restrição</b>]';
      if ($w_filtro>'')     $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>';
    } 
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ISACAD');
    if ($w_copia>'') {
      // Se for cópia, aplica o filtro sobre todas as demandas visíveis pelo usuário
      $RS = db_getSolicList_IS::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
              $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
              $p_unidade,$p_prioridade,$p_qtd_restricao,$p_proponente,
              $p_chave,$p_assunto,$p_pais,$p_regiao,$p_uf,$p_cidade,$p_usu_resp,
              $p_uorg_resp,$p_palavra,$p_prazo,$p_fase,$p_projeto,$p_atividade,null,$p_sq_acao_ppa,$p_sq_isprojeto,null,$w_ano);
    } else {
      $RS = db_getSolicList_IS::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P1,
              $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
              $p_unidade,$p_prioridade,$p_qtd_restricao,$p_proponente,
              $p_chave,$p_assunto,$p_pais,$p_regiao,$p_uf,$p_cidade,$p_usu_resp,
              $p_uorg_resp,$p_palavra,$p_prazo,$p_fase,$p_acao,$p_atividade,null,substr($p_sq_acao_ppa,4,4),$p_sq_isprojeto,substr($p_sq_acao_ppa,8,4),$w_ano);
    } 
    if (Nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {
      //$RS = SortArray($RS,'fim','asc','prioridade','asc');
    }
  }
  Cabecalho();
  ShowHTML('<HEAD>');
  if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="300; URL=../'.MontaURL('MESA').'">');
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de ações</TITLE>');
  ScriptOpen('Javascript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  ValidateOpen('Validacao');
  if (!(strpos('CP',$O)===false)) {
    if ($P1!=1 || $O=='C') {
      // Se não for cadastramento ou se for cópia
      Validate('p_prazo','Dias para a data limite','','','1','2','','0123456789');
      Validate('p_proponente','Proponente externo','','','2','90','1','');
      Validate('p_palavra','Palavras-chave','','','2','90','1','1');
      Validate('p_ini_i','Recebimento inicial','DATA','','10','10','','0123456789/');
      Validate('p_ini_f','Recebimento final','DATA','','10','10','','0123456789/');
      ShowHTML('  if ((theForm.p_ini_i.value != \'\' && theForm.p_ini_f.value == \'\') || (theForm.p_ini_i.value == \'\' && theForm.p_ini_f.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas de recebimento ou nenhuma delas!\');');
      ShowHTML('     theForm.p_ini_i.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      CompData('p_ini_i','Recebimento inicial','<=','p_ini_f','Recebimento final');
      Validate('p_fim_i','Conclusão inicial','DATA','','10','10','','0123456789/');
      Validate('p_fim_f','Conclusão final','DATA','','10','10','','0123456789/');
      ShowHTML('  if ((theForm.p_fim_i.value != \'\' && theForm.p_fim_f.value == \'\') || (theForm.p_fim_i.value == \'\' && theForm.p_fim_f.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas de conclusão ou nenhuma delas!\');');
      ShowHTML('     theForm.p_fim_i.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      CompData('p_fim_i','Conclusão inicial','<=','p_fim_f','Conclusão final');
    } 
    Validate('P4','Linhas por página','1','1','1','4','','0123456789');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    // Se for recarga da página
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='I') {
    BodyOpen('onLoad=\'document.Form.w_smtp_server.focus()\';');
  } elseif ($O=='A') {
    BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  } elseif ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } elseif (!(strpos('CP',$O)===false)) {
    if ($P1!=1 || $O=='C') {
      // Se for cadastramento
      BodyOpen('onLoad=\'document.Form.p_sq_acao_ppa.focus()\';');
    } else {
      BodyOpen(null);
    } 
  } else {
    BodyOpen(null);
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  if ($w_filtro>'') ShowHTML($w_filtro);
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><font size="2">');
    if ($P1==1 && $w_copia=='') {
      // Se for cadastramento e não for resultado de busca para cópia
      if ($w_submenu>'') {
        $RS1 = db_getLinkSubMenu::getInstanceOf($dbms,$w_cliente,$SG);
        foreach ($RS1 as $row) {$RS1=$row; break;}
        ShowHTML('<tr><td>');
        ShowHTML('    <a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.f($RS1,'sigla').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
        //ShowHTML '    <a accesskey=''C'' class=''SS'' href=''' & w_dir & w_pagina & par & '&R=' & w_pagina & par & '&O=C&P1=' & P1 & '&P2=' & P2 & '&P3=1&P4=' & P4 & '&TP=' & TP & '&SG=' & SG & MontaFiltro('GET') & '''><u>C</u>opiar</a>'
      } else {
        ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
      } 
    } 
    if ((strpos(strtoupper($R),'GR_')===false)) {
      if ($w_copia>'') {
        // Se for cópia
        if (MontaFiltro('GET')>'') {
          ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        } else {
          ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
        } 
      } else {
        if (MontaFiltro('GET')>'') {
          ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        } else {
          ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
        } 
      } 
    } 
    ShowHTML('    <td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Ação','cd_acao_completa').'</td>');
    ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Responsável','nm_solic').'</td>');
    if ($P1!=2)ShowHTML('<td rowspan=2><b>'.LinkOrdena('Usuário atual','nm_exec').'</td>');
    if ($P1==1 || $P1==2) {
      // Se for cadastramento ou mesa de trabalho
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Título','titulo').'</td>');
      ShowHTML('          <td colspan=2><b>Execução</td>');
    } else {
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Título','titulo').'</td>');
      ShowHTML('          <td colspan=2><b>Execução</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Valor','valor').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Fase atual','nm_tramite').'</td>');
    } 
    ShowHTML('          <td rowspan=2><b>Operações</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('De','inicio').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Até','fim').'</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial=0;
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td nowrap>');
        if (f($row,'concluida')=='N') {
          if (f($row,'fim')<addDays(time(),-1)) {
            ShowHTML('           <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">');
          } elseif (f($row,'aviso_prox_conc')=='S' && (f($row,'aviso')<=addDays(time(),-1))) {
            ShowHTML('           <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">');
          } else {
            ShowHTML('           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">');
          } 
        } else {
          if (f($row,'fim')<Nvl(f($row,'fim_real'),f($row,'fim'))) {
           ShowHTML('           <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">');
          } else {
            ShowHTML('           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">');
          } 
        } 
        if (!count($row)<=0) {
          if (f($row,'cd_acao')>'')ShowHTML('        <A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'cd_acao_completa').'</a>');
          else                    ShowHTML('        <A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'sq_siw_solicitacao').'</a>');
        } else {
          ShowHTML('        <A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'cd_acao_completa').'</a>');
        } 
        ShowHTML('        <td>'.ExibePessoa('../',$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_solic')).'</A></td>');
        if ($P1!=2) {
          // Se for mesa de trabalho, não exibe o executor, pois já é o usuário logado
          if (Nvl(f($row,'nm_exec'),'---')>'---') ShowHTML('        <td>'.ExibePessoa('../',$w_cliente,f($row,'executor'),$TP,f($row,'nm_exec')).'</td>');
          else                                   ShowHTML('        <td>---</td>');
        } 
        if ($P1!=1 && $P1!=2) {
          // Se não for cadastramento nem mesa de trabalho
          //ShowHTML '        <td>' & Nvl(RS('proponente'),'---') & '</td>'
        } 
        // Verifica se foi enviado o parâmetro p_tamanho = N. Se chegou, o assunto deve ser exibido sem corte.
        // Este parâmetro é enviado pela tela de filtragem das páginas gerenciais
        if ($_REQUEST['p_tamanho']=='N') {
          ShowHTML('        <td>'.Nvl(f($row,'titulo'),'-').'</td>');
        } else {
          if (strlen(Nvl(f($row,'titulo'),'-'))>50) $w_titulo=substr(Nvl(f($row,'titulo'),'-'),0,50).'...'; else $w_titulo=Nvl(f($row,'titulo'),'-');
          if (f($row,'sg_tramite')=='CA') {
            ShowHTML('        <td title="'.htmlspecialchars(f($row,'assunto')).'"><strike>'.htmlspecialchars($w_titulo).'</strike></td>');
          } else {
            ShowHTML('        <td title="'.htmlspecialchars(f($row,'assunto')).'">'.htmlspecialchars($w_titulo).'</td>');
          } 
        }
        ShowHTML('        <td align="center">&nbsp;'.FormataDataEdicao(f($row,'inicio')).'</td>');
        ShowHTML('        <td align="center">&nbsp;'.FormataDataEdicao(f($row,'fim')).'</td>');
        if ($P1!=1 && $P1!=2) {
          // Se não for cadastramento nem mesa de trabalho
          if (f($row,'sg_tramite')=='AT') {
            ShowHTML('        <td align="right">'.number_format(f($row,'custo_real'),2,',','.').'&nbsp;</td>');
            $w_parcial+=f($row,'custo_real');
          } else {
            ShowHTML('        <td align="right">'.number_format(f($row,'valor'),2,',','.').'&nbsp;</td>');
            $w_parcial+=f($row,'valor');
          } 
          ShowHTML('        <td nowrap>'.f($row,'nm_tramite').'</td>');
        } 
        ShowHTML('        <td align="top" nowrap>');
        if ($P1!=3 && $P1!=5) {
          // Se não for acompanhamento
          if ($w_copia>'') {
             // Se for listagem para cópia
            $RS1 = db_getLinkSubMenu::getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);
            //ShowHTML '          <a accesskey=''I'' class=''HL'' href=''' & w_dir & w_pagina & 'Geral&R=' & w_pagina & par & '&O=I&SG=' & RS1('sigla') & '&w_menu=' & w_menu & '&P1=' & P1 & '&P2=' & P2 & '&P3=' & P3 & '&P4=' & P4 & '&TP=' & TP & '&w_copia=' & RS('sq_siw_solicitacao') & MontaFiltro('GET') & '''>Copiar</a>&nbsp;'
          } elseif ($P1==1) {
            // Se for cadastramento
            if ($w_submenu>'') ShowHTML('          <A class="HL" HREF="menu.php?par=ExibeDocs&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&R='.$w_pagina.$par.'&SG='.$SG.'&TP='.$TP.'&w_documento=Nr. '.f($row,'sq_siw_solicitacao').MontaFiltro('GET').'" title="Altera as informações cadastrais da ação" TARGET="menu">AL</a>&nbsp;');
            else               ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informações cadastrais da ação">AL</A>&nbsp');
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclusão da ação.">EX</A>&nbsp');
           ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Encaminhamento da ação.">EN</A>&nbsp');
          } elseif ($P1==2 || $P1==6) {
            // Se for execução
            if ($w_usuario==Nvl(f($row,'executor'),0)) {
              if (Nvl(f($row,'solicitante'),0)   == $w_usuario || 
                  Nvl(f($row,'titular'),0)       == $w_usuario || 
                  Nvl(f($row,'substituto'),0)    == $w_usuario || 
                  Nvl(f($row,'tit_exec'),0)      == $w_usuario || 
                  Nvl(f($row,'executor'),0)      == $w_usuario || 
                  Nvl(f($row,'subst_exec'),0)    == $w_usuario) {
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'AtualizaMeta&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Atualiza as metas físicas da ação." target="Metas">Metas</A>&nbsp');
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Restricao&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.'ISACRESTR'.MontaFiltro('GET').'" title="Atualiza as restricoes da ação." target="Restricoes">Rest</A>&nbsp');
              } 
              // Coloca as operações dependendo do trâmite
              if (f($row,'sg_tramite')=='EA') {
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anotações para a ação, sem enviá-la.">AN</A>&nbsp');
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a ação para outro responsável.">EN</A>&nbsp');
              } elseif (f($row,'sg_tramite')=='EE') {
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anotações para a ação, sem enviá-la.">AN</A>&nbsp');
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a ação para outro responsável.">EN</A>&nbsp');
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Conclui a execução da ação.">CO</A>&nbsp');
              } 
            } else {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'AtualizaMeta&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Atualiza as metas físicas da ação." target="Metas">Metas</A>&nbsp');
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Restricao&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.'ISACRESTR'.MontaFiltro('GET').'" title="Atualiza as restricoes da ação." target="Restricoes">Rest</A>&nbsp');
              if (RetornaGestor(f($row,'sq_siw_solicitacao'),$w_usuario)=='S') {
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a ação para outro responsável.">EN</A>&nbsp');
              } else {
                ShowHTML('          ---&nbsp');
              }
            }
          }
        } else {
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'AtualizaMeta&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Atualiza as metas físicas da ação." target="Metas">Metas</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Restricao&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.'ISACRESTR'.MontaFiltro('GET').'" title="Atualiza as restricoes da ação." target="Restricoes">Rest</A>&nbsp');
          if (Nvl(f($row,'solicitante'),0)   == $w_usuario || 
              Nvl(f($row,'titular'),0)       == $w_usuario || 
              Nvl(f($row,'substituto'),0)    == $w_usuario || 
              Nvl(f($row,'tit_exec'),0)      == $w_usuario || 
              Nvl(f($row,'subst_exec'),0)    == $w_usuario) {
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a ação para outro responsável.">EN</A>&nbsp');
          } 
        } 
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
      if ($P1!=1 && $P1!=2) {
        // Se não for cadastramento nem mesa de trabalho
        // Coloca o valor parcial apenas se a listagem ocupar mais de uma página
        if (ceil(count($RS)/$P4)>1) { 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
          ShowHTML('          <td colspan=6 align="right"><b>Total desta página&nbsp;</td>');
          ShowHTML('          <td align="right"><b>'.number_format($w_parcial,2,',','.').'&nbsp;</td>');
          ShowHTML('          <td colspan=2>&nbsp;</td>');
          ShowHTML('        </tr>');
        } 
        // Se for a última página da listagem, soma e exibe o valor total
        if ($P3==ceil(count($RS)/$P4)) {
          foreach($RS as $row) {
            if (f($row,'sg_tramite')=='AT') $w_total += f($row,'custo_real');
            else                            $w_total += f($row,'valor');
          } 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
          ShowHTML('          <td colspan=6 align="right"><b>Total da listagem&nbsp;</td>');
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
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } 
    ShowHTML('</tr>');
  } elseif (!(strpos('CP',$O)===false)) {
    if ($P1!=1) {
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    } elseif ($O=='C') {
      // Se for cópia
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Para selecionar a ação que deseja copiar, informe nos campos abaixo os critérios de seleção e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    } 
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O=='C') {
      // Se for cópia, cria parâmetro para facilitar a recuperação dos registros
      ShowHTML('<INPUT type="hidden" name="w_copia" value="OK">');
    } 
    if ($P1!=1 || $O=='C') {
      // Se não for cadastramento ou se for cópia
      // Recupera dados da opção das ações
      $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ISACAD');
      ShowHTML('      <tr valign="top"><td colspan="2"><table border=0 width="100%" cellspacing=0>');
      ShowHTML('      <tr valign="top">');
      SelecaoAcaoPPA('<u>A</u>ção PPA:','A',null,$w_cliente,$w_ano,null,null,$p_sq_acao_ppa,null,'p_sq_acao_ppa',null,null,null,$w_menu,null,null);
      ShowHTML('      </tr>');
      ShowHTML('      <tr valign="top">');
      SelecaoIsProjeto('<u>P</u>rograma interno:','P',null,$p_sq_isprojeto,null,'p_sq_isprojeto',null,null);
      ShowHTML('      </tr>');
      ShowHTML('      </table>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td valign="top"><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="STI" type="text" name="p_prazo" size="2" maxlength="2" value="'.$p_prazo.'"></td>');
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Respo<u>n</u>sável:','N','Selecione o responsável pela ação na relação.',$p_solicitante,null,'p_solicitante','USUARIOS');
      SelecaoUnidade('<U>Á</U>rea planejamento:','A',null,$p_unidade,null,'p_unidade',null,null);
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Responsável atua<u>l</u>:','L','Selecione o responsável atual pela ação na relação.',$p_usu_resp,null,'p_usu_resp','USUARIOS');
      SelecaoUnidade('<U>S</U>etor atual:','S','Selecione a unidade onde a ação se encontra na relação.',$p_uorg_resp,null,'p_uorg_resp',null,null);
      ShowHTML('      <tr>');
      ShowHTML('          <td valign="top"><b>Par<U>c</U>erias externas:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="'.$p_proponente.'"></td>');
      ShowHTML('          <td valign="top"><b>Par<U>c</U>erias internas:<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="STI" type="text" name="p_palavra" size="25" maxlength="90" value="'.$p_palavra.'"></td>');
      ShowHTML('      <tr>');
      ShowHTML('          <td valign="top"><b>Data de re<u>c</u>ebimento entre:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
      ShowHTML('          <td valign="top"><b>Limi<u>t</u>e para conclusão entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
      if ($O!='C') {
        // Se não for cópia
        ShowHTML('      <tr>');
        ShowHTML('          <td valign="top"><b>Exibe somente ações em atraso?</b><br>');
        if ($p_atraso=='S')
          ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S" checked> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N"> Não');
        else
          ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N" checked> Não');
        SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase[]',null,null);
      } 
    } 
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="STS" name="p_ordena" size="1">');
    if ($p_ordena=='INICIO')
      ShowHTML('          <option value="inicio" SELECTED>Data de recebimento<option value="">Data limite para conclusão<option value="nm_tramite">Fase atual');
    elseif ($p_ordena=='NM_TRAMITE')
      ShowHTML('          <option value="inicio">Data de recebimento<option value="">Data limite para conclusão<option value="nm_tramite" SELECTED>Fase atual');
    else
      ShowHTML('          <option value="inicio">Data de recebimento<option value="" SELECTED>Data limite para conclusão<option value="nm_tramite">Fase atual');
    ShowHTML('          </select></td>');
    ShowHTML('          <td valign="top"><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="2">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    if ($O=='C') {
      // Se for cópia
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Abandonar cópia">');
    } else {
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
    } 
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
  Rodape();
} 
// =========================================================================
// Rotina dos dados gerais
// -------------------------------------------------------------------------
function Geral() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      =$_REQUEST['w_chave'];
  $w_readonly   ='';
  $w_erro       ='';
  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'') {
    // Se for recarga da página
    $w_proponente       = $_REQUEST['w_proponente'];
    $w_sq_unidade_resp  = $_REQUEST['w_sq_unidade_resp'];
    $w_titulo           = $_REQUEST['w_titulo'];
    $w_prioridade       = $_REQUEST['w_prioridade'];
    $w_aviso            = $_REQUEST['w_aviso'];
    $w_dias             = $_REQUEST['w_dias'];
    $w_inicio_real      = $_REQUEST['w_inicio_real'];
    $w_fim_real         = $_REQUEST['w_fim_real'];
    $w_concluida        = $_REQUEST['w_concluida'];
    $w_data_conclusao   = $_REQUEST['w_data_conclusao'];
    $w_nota_conclusao   = $_REQUEST['w_nota_conclusao'];
    $w_custo_real       = $_REQUEST['w_custo_real'];
    $w_chave            = $_REQUEST['w_chave'];
    $w_chave_pai        = $_REQUEST['w_chave_pai'];
    $w_chave_aux        = $_REQUEST['w_chave_aux'];
    $w_sq_menu          = $_REQUEST['w_sq_menu'];
    $w_sq_unidade       = $_REQUEST['w_sq_unidade'];
    $w_sq_tramite       = $_REQUEST['w_sq_tramite'];
    $w_solicitante      = $_REQUEST['w_solicitante'];
    $w_cadastrador      = $_REQUEST['w_cadastrador'];
    $w_executor         = $_REQUEST['w_executor'];
    $w_inicio           = $_REQUEST['w_inicio'];
    $w_fim              = $_REQUEST['w_fim'];
    $w_inclusao         = $_REQUEST['w_inclusao'];
    $w_ultima_alteracao = $_REQUEST['w_ultima_alteracao'];
    $w_conclusao        = $_REQUEST['w_conclusao'];
    $w_valor            = $_REQUEST['w_valor'];
    $w_opiniao          = $_REQUEST['w_opiniao'];
    $w_data_hora        = $_REQUEST['w_data_hora'];
    $w_pais             = $_REQUEST['w_pais'];
    $w_uf               = $_REQUEST['w_uf'];
    $w_cidade           = $_REQUEST['w_cidade'];
    $w_palavra_chave    = $_REQUEST['w_palavra_chave'];
    $w_sqcc             = $_REQUEST['w_sqcc'];
    $w_sq_acao_ppa      = $_REQUEST['w_sq_acao_ppa'];
    $w_sq_isprojeto     = $_REQUEST['w_sq_isprojeto'];
    $w_descricao        = $_REQUEST['w_descricao'];
    $w_justificativa    = $_REQUEST['w_justificativa'];
    $w_selecao_mp       = $_REQUEST['w_selecao_mp'];
    $w_selecao_se       = $_REQUEST['w_selecao_se'];
    $w_sq_unidade_adm   = $_REQUEST['w_sq_unidade_adm'];
    if ($w_sq_acao_ppa>'') {
      $RS = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,substr($w_sq_acao_ppa,0,4),substr($w_sq_acao_ppa,4,4),null,substr($w_sq_acao_ppa,12,17),null,null,null,null,null);
      foreach($RS as $row){$RS=$row; break;}
      $w_titulo = substr(f($RS,'descricao_acao'),0,69).' - '.substr(f($RS,'ds_unidade'),0,28);
    } elseif ($w_sq_isprojeto>'') {
      $RS = db_getProjeto_IS::getInstanceOf($dbms,$w_sq_isprojeto,$w_cliente,null,null,null,null,null,null,null,null,null,null,null,null);
      foreach($RS as $row){$RS=$row; break;}
      $w_titulo = f($RS,'nome');
    } 
  } else {
    if (!(strpos('AEV',$O)===false) || $w_copia>'') {
      // Recupera os dados da ação
      if ($w_copia>'') {
        $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_copia,$SG);
        foreach ($RS as $row) {$RS=$row; break;}
      } else {
        $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,$SG);
        foreach ($RS as $row) {$RS=$row; break;}
      } 
      if (count($RS)>0) {
        $w_proponente         = f($RS,'proponente');
        $w_sq_unidade_resp    = f($RS,'sq_unidade_resp');
        $w_titulo             = f($RS,'titulo');
        $w_prioridade         = f($RS,'prioridade');
        $w_aviso              = f($RS,'aviso_prox_conc');
        $w_dias               = f($RS,'dias_aviso');
        $w_inicio_real        = f($RS,'inicio_real');
        $w_fim_real           = f($RS,'fim_real');
        $w_concluida          = f($RS,'concluida');
        $w_data_conclusao     = f($RS,'data_conclusao');
        $w_nota_conclusao     = f($RS,'nota_conclusao');
        $w_custo_real         = f($RS,'custo_real');
        $w_chave_pai          = f($RS,'sq_solic_pai');
        $w_chave_aux          = null;
        $w_sq_menu            = f($RS,'sq_menu');
        $w_sq_unidade         = f($RS,'sq_unidade');
        $w_sq_tramite         = f($RS,'sq_siw_tramite');
        $w_solicitante        = f($RS,'solicitante');
        $w_cadastrador        = f($RS,'cadastrador');
        $w_executor           = f($RS,'executor');
        $w_inicio             = FormataDataEdicao(f($RS,'inicio'));
        $w_fim                = FormataDataEdicao(f($RS,'fim'));
        $w_inclusao           = f($RS,'inclusao');
        $w_ultima_alteracao   = f($RS,'ultima_alteracao');
        $w_conclusao          = f($RS,'conclusao');
        $w_valor              = number_format(f($RS,'valor'),2,',','.');
        $w_opiniao            = f($RS,'opiniao');
        $w_data_hora          = f($RS,'data_hora');
        $w_sqcc               = f($RS,'sq_cc');
        $w_sq_acao_ppa        = f($RS,'cd_ppa_pai').f($RS,'cd_acao').f($RS,'cd_subacao').f($RS,'cd_unidade');
        $w_sq_isprojeto       = f($RS,'sq_isprojeto');
        $w_selecao_mp         = f($RS,'mpog_ppa');
        $w_selecao_se         = f($RS,'relev_ppa');
        $w_pais               = f($RS,'sq_pais');
        $w_uf                 = f($RS,'co_uf');
        $w_cidade             = f($RS,'sq_cidade_origem');
        $w_palavra_chave      = f($RS,'palavra_chave');
        $w_descricao          = f($RS,'descricao');
        $w_justificativa      = f($RS,'justificativa');
        $w_sq_unidade_adm     = f($RS,'sq_unidade_adm');
      } 
    } 
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataDataHora();
  FormataValor();
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.Botao.value == "Troca") { return true; }');
    Validate('w_titulo','Ação','1',1,5,100,'1','1');
    ShowHTML('  if (theForm.w_sq_acao_ppa.selectedIndex==0 && theForm.w_sq_isprojeto.selectedIndex==0) {');
    ShowHTML('     alert(\'Informe a iniciativa prioritária e/ou a ação do PPA!\');');
    ShowHTML('     theForm.w_sq_isprojeto.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    Validate('w_sq_unidade_adm','Unidade administrativa','HIDDEN',1,1,18,'','0123456789');
    Validate('w_solicitante','Responsável monitoramento','HIDDEN',1,1,18,'','0123456789');
    Validate('w_sq_unidade_resp','Área de planejamento','SELECT',1,1,18,'','0123456789');
    Validate('w_inicio','Início previsto','DATA',1,10,10,'','0123456789/');
    Validate('w_fim','Fim previsto','DATA',1,10,10,'','0123456789/');
    CompData('w_inicio','Data de recebimento','<=','w_fim','Limite para conclusão');
    Validate('w_proponente','Parcerias externas','','',2,90,'1','1');
    Validate('w_palavra_chave','Parcerias internas','','',2,90,'1','1');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('EV',$O)===false)) {
    BodyOpen('onLoad=\'this.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_titulo.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (!(strpos('IAEV',$O)===false)) {
    if ($w_pais=='') {
      // Carrega os valores padrão para país, estado e cidade
      $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
      $w_pais   = f($RS,'sq_pais');
      $w_uf     = f($RS,'co_uf');
      $w_cidade = f($RS,'sq_cidade_padrao');
    } 
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled=' DISABLED ';
      if ($O=='V') $w_erro = Validacao($w_sq_solicitacao,$SG);
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('<INPUT type="hidden" name="w_prioridade" value="">');
    ShowHTML('<INPUT type="hidden" name="w_aviso" value="S">');
    ShowHTML('<INPUT type="hidden" name="w_descricao" value="'.$w_descricao.'">');
    ShowHTML('<INPUT type="hidden" name="w_justificativa" value="'.$w_justificativa.'">');
    ShowHTML('<INPUT type="hidden" name="w_valor" value="0,00">');
    //Passagem da cidade padrão como brasília, pelo retidara do impacto geográfico da tela
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    ShowHTML('<INPUT type="hidden" name="w_cidade" value="'.f($RS,'sq_cidade_padrao').'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><font size="2"><b>Identificação</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Os dados deste bloco serão utilizados para identificação da ação, bem como para o controle de sua execução.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    if ($w_sq_acao_ppa>'') ShowHTML('      <tr><td valign="top"><b><u>A</u>ção:</b><br><INPUT READONLY ACCESSKEY="A" '.$w_Disabled.' class="STI" type="text" name="w_titulo" size="90" maxlength="100" value="'.$w_titulo.'" ></td>');
    else                   ShowHTML('      <tr><td valign="top"><b><u>A</u>ção:</b><br><INPUT ACCESSKEY="A" '.$w_Disabled.' class="STI" type="text" name="w_titulo" size="90" maxlength="100" value="'.$w_titulo.'" ></td>');
    ShowHTML('          <tr>');
    if ($O=='I' || $w_sq_acao_ppa=='') SelecaoIsProjeto('<u>P</u>rograma interno:','P',null,$w_sq_isprojeto,null,'w_sq_isprojeto','CADASTRAMENTO','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_acao_ppa\'; document.Form.submit();"');
    else                               SelecaoIsProjeto('<u>P</u>rograma interno:','P',null,$w_sq_isprojeto,null,'w_sq_isprojeto',null,'onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_unidade_adm\'; document.Form.submit();"');
    ShowHTML('          </tr>');
    ShowHTML('          <tr>');
    if ($O=='I' || $w_sq_acao_ppa=='') {
      SelecaoAcaoPPA('Ação <u>P</u>PA:','P',null,$w_cliente,$w_ano,substr($w_sq_acao_ppa,0,4),substr($w_sq_acao_ppa,4,4),substr($w_sq_acao_ppa,8,4),substr($w_sq_acao_ppa,12,5),'w_sq_acao_ppa','IDENTIFICACAO','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_acao_ppa\'; document.Form.submit();"',null,$w_menu,null,null);
    } else {
      SelecaoAcaoPPA('Ação <u>P</u>PA:','P',null,$w_cliente,$w_ano,substr($w_sq_acao_ppa,0,4),substr($w_sq_acao_ppa,4,4),substr($w_sq_acao_ppa,8,4),substr($w_sq_acao_ppa,12,5),'w_sq_acao_ppa',null,'disabled',null,$w_menu,null,null);
      ShowHTML('<INPUT type="hidden" name="w_sq_acao_ppa" value="'.$w_sq_acao_ppa.'">');
    } 
    ShowHTML('          </tr>');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    SelecaoUnidade_IS('<U>U</U>nidade administrativa:','u','Selecione a unidade administrativa responsável pela ação.',$w_sq_unidade_adm,null,'w_sq_unidade_adm',null,'ADMINISTRATIVA');
    ShowHTML('      <tr valign="top">');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    MontaRadioNS('<b>Selecionada pelo SPI/MP?</b>',$w_selecao_mp,'w_selecao_mp');
    MontaRadioNS('<b>Selecionada pelo SE/SEPPIR?</b>',$w_selecao_se,'w_selecao_se');
    ShowHTML('      </table></td></tr>');
    ShowHTML('      <tr valign="top">');
    SelecaoPessoa('Respo<u>n</u>sável monitoramento:','N','Selecione o nome da pessoa responsável pelas informações no SISPLAM.',$w_solicitante,null,'w_solicitante','USUARIOS');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade_IS('<U>Á</U>rea de planejamento:','A','Selecione a área da secretaria ou órgão responsável pela ação.',$w_sq_unidade_resp,null,'w_sq_unidade_resp',null,'PLANEJAMENTO');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('          <tr>');
    if ($w_sq_acao_ppa>'') {
      ShowHTML('              <td valign="top"><b>Iní<u>c</u>io previsto:</b><br><input readonly '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_inicio,'01/01/'.$w_ano).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
      ShowHTML('              <td valign="top"><b><u>F</u>im previsto:</b><br><input readonly '.$w_Disabled.' accesskey="F" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_fim,'31/12/'.$w_ano).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
    } else {
      ShowHTML('              <td valign="top"><b>Iní<u>c</u>io previsto:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_inicio,'01/01/'.$w_ano).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
      ShowHTML('              <td valign="top"><b><u>F</u>im previsto:</b><br><input '.$w_Disabled.' accesskey="F" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_fim,'31/12/'.$w_ano).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
    } 
    ShowHTML('          </table>');
    ShowHTML('      <tr><td valign="top"><b>Parc<u>e</u>rias externas:<br><INPUT ACCESSKEY="E" '.$w_Disabled.' class="STI" type="text" name="w_proponente" size="90" maxlength="90" value="'.$w_proponente.'" title="Informar quais são os parceiros externos na execução da ação (campo opcional)."></td>');
    ShowHTML('      <tr><td valign="top"><b><u>P</u>arcerias internas:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="STI" type="text" name="w_palavra_chave" size="90" maxlength="90" value="'.$w_palavra_chave.'" title="Informar quais são os parceiros internos na execução da ação (campo opcional)."></td>');
    // Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    if ($O=='I') {
      $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&w_copia='.$w_copia.'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    } 
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
  Rodape();
} 
// =========================================================================
// Rotina de cadastramento do recurso programado
// -------------------------------------------------------------------------
function RecursoProgramado() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_readonly   = '';
  $w_erro       = '';
  if (!(strpos('A',$O)===false)) {
    // Recupera os dados da ação
    $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,$SG);
    foreach($RS as $row){$RS=$row; break;}
    if (count($RS)>0) {
      $w_proponente           = f($RS,'proponente');
      $w_sq_unidade_resp      = f($RS,'sq_unidade_resp');
      $w_titulo               = f($RS,'titulo');
      $w_prioridade           = f($RS,'prioridade');
      $w_aviso                = f($RS,'aviso_prox_conc');
      $w_dias                 = f($RS,'dias_aviso');
      $w_inicio_real          = f($RS,'inicio_real');
      $w_fim_real             = f($RS,'fim_real');
      $w_concluida            = f($RS,'concluida');
      $w_data_conclusao       = f($RS,'data_conclusao');
      $w_nota_conclusao       = f($RS,'nota_conclusao');
      $w_custo_real           = f($RS,'custo_real');
      $w_chave_pai            = f($RS,'sq_solic_pai');
      $w_chave_aux            = null;
      $w_sq_menu              = f($RS,'sq_menu');
      $w_sq_unidade           = f($RS,'sq_unidade');
      $w_sq_tramite           = f($RS,'sq_siw_tramite');
      $w_solicitante          = f($RS,'solicitante');
      $w_cadastrador          = f($RS,'cadastrador');
      $w_executor             = f($RS,'executor');
      $w_inicio               = FormataDataEdicao(f($RS,'inicio'));
      $w_fim                  = FormataDataEdicao(f($RS,'fim'));
      $w_inclusao             = f($RS,'inclusao');
      $w_ultima_alteracao     = f($RS,'ultima_alteracao');
      $w_conclusao            = f($RS,'conclusao');
      $w_valor                = number_format(f($RS,'valor'),2,',','.');
      $w_opiniao              = f($RS,'opiniao');
      $w_data_hora            = f($RS,'data_hora');
      $w_sq_acao_ppa          = f($RS,'cd_ppa_pai').f($RS,'cd_acao').f($RS,'cd_subacao').f($RS,'cd_unidade');
      $w_sq_isprojeto         = f($RS,'sq_isprojeto');
      $w_selecao_mp           = f($RS,'mpog_ppa');
      $w_selecao_se           = f($RS,'relev_ppa');
      $w_pais                 = f($RS,'sq_pais');
      $w_uf                   = f($RS,'co_uf');
      $w_cidade               = f($RS,'sq_cidade_origem');
      $w_palavra_chave        = f($RS,'palavra_chave');
      $w_descricao            = f($RS,'descricao');
      $w_justificativa        = f($RS,'justificativa');
      $w_sq_unidade_adm       = f($RS,'sq_unidade_adm');
    } 
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataDataHora();
  FormataValor();
  ValidateOpen('Validacao');
  if ($O=='A') {
    Validate('w_valor','Recurso programado','VALOR','1',4,18,'','0123456789.,');
    CompValor('w_valor','Recurso programado','>','0,00','zero');
    Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
  } elseif ($O=='P') {
    Validate('w_chave','Ação PPA','SELECT','1',1,18,'','0123456789');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if (!(strpos('A',$O)===false)) {
    BodyOpen('onLoad=\'document.Form.w_valor.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_chave.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (!(strpos('A',$O)===false)) {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('<INPUT type="hidden" name="w_descricao" value="'.$w_descricao.'">');
    ShowHTML('<INPUT type="hidden" name="w_justificativa" value="'.$w_justificativa.'">');
    ShowHTML('<INPUT type="hidden" name="w_proponente" value="'.$w_proponente.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_unidade_resp" value="'.$w_sq_unidade_resp.'">');
    ShowHTML('<INPUT type="hidden" name="w_titulo" value="'.$w_titulo.'">');
    ShowHTML('<INPUT type="hidden" name="w_prioridade" value="'.$w_prioridade.'">');
    ShowHTML('<INPUT type="hidden" name="w_aviso" value="'.$w_aviso.'">');
    ShowHTML('<INPUT type="hidden" name="w_dias" value="'.$w_dias.'">');
    ShowHTML('<INPUT type="hidden" name="w_inicio_real" value="'.$w_inicio_real.'">');
    ShowHTML('<INPUT type="hidden" name="w_fim_real" value="'.$w_fim_real.'">');
    ShowHTML('<INPUT type="hidden" name="w_concluida" value="'.$w_concluida.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_conclusao" value="'.$w_data_conclusao.'">');
    ShowHTML('<INPUT type="hidden" name="w_nota_conclusao" value="'.$w_nota_conclusao.'">');
    ShowHTML('<INPUT type="hidden" name="w_custo_real" value="'.$w_custo_real.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_pai" value="'.$w_chave_pai.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_menu" value="'.$w_sq_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_unidade" value="'.$w_sq_unidade.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_tramite" value="'.$w_sq_tramite.'">');
    ShowHTML('<INPUT type="hidden" name="w_solicitante" value="'.$w_solicitante.'">');
    ShowHTML('<INPUT type="hidden" name="w_cadastrador" value="'.$w_cadastrador.'">');
    ShowHTML('<INPUT type="hidden" name="w_executor" value="'.$w_executor.'">');
    ShowHTML('<INPUT type="hidden" name="w_inicio" value="'.$w_inicio.'">');
    ShowHTML('<INPUT type="hidden" name="w_fim" value="'.$w_fim.'">');
    ShowHTML('<INPUT type="hidden" name="w_inclusao" value="'.$w_inclusao.'">');
    ShowHTML('<INPUT type="hidden" name="w_ultima_alteracao" value="'.$w_ultima_alteracao.'">');
    ShowHTML('<INPUT type="hidden" name="w_conclusao" value="'.$w_conclusao.'">');
    ShowHTML('<INPUT type="hidden" name="w_opiniao" value="'.$w_opiniao.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.$w_data_hora.'">');
    ShowHTML('<INPUT type="hidden" name="w_selecao_mp" value="'.$w_selecao_mp.'">');
    ShowHTML('<INPUT type="hidden" name="w_selecao_se" value="'.$w_selecao_se.'">');
    ShowHTML('<INPUT type="hidden" name="w_palavra_chave" value="'.$w_palavra_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_unidade_adm" value="'.$w_sq_unidade_adm.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_acao_ppa" value="'.$w_sq_acao_ppa.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_isprojeto" value="'.$w_sq_isprojeto.'">');
    //Passagem da cidade padrão como brasília, pelo retidara do impacto geográfico da tela
    $RS1 = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    ShowHTML('<INPUT type="hidden" name="w_cidade" value="'.f($RS1,'sq_cidade_padrao').'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="99%" border="0">');
    ShowHTML('      <tr valign="top"><td colspan="2"><font size=2>Ação: <b>'.f($RS,'titulo').'</b></font></td></tr>');
    // Identificação da ação
    ShowHTML('      <tr valign="top"><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Identificação</td>');
    // Se a ação no PPA for informada, exibe.
    if (Nvl(f($RS,'cd_acao'),'')>'') {
      ShowHTML('   <tr valign="top" bgcolor="#D0D0D0"><td colspan="2"><table border=0 width="100%" cellspacing=0>');
      ShowHTML('     <tr valign="top"><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
      ShowHTML('       <tr bgcolor="#D0D0D0"><td colspan="1" nowrap>Unidade:<br><b>'.f($RS,'cd_unidade').' - '.f($RS,'ds_unidade').' </b></td>');
      ShowHTML('        <td>Órgão:<br><b>'.f($RS,'cd_orgao').' - '.f($RS,'nm_orgao').' </b></td></tr>');
      ShowHTML('     </table></td></tr>');
      ShowHTML('      <tr bgcolor="#D0D0D0"><td colspan="2">Programa PPA:<br><b>'.f($RS,'cd_ppa_pai').' - '.f($RS,'nm_ppa_pai').'</b></td></tr>');
      ShowHTML('      <tr bgcolor="#D0D0D0"><td colspan="1">Ação PPA:<br><b>'.f($RS,'cd_acao').' - '.f($RS,'nm_ppa').' </b></td>');
      ShowHTML('        <td valign="top" nowrap>Recurso programado:<br><b>'.number_format(f($RS,'valor'),2,',','.').' </b></td>');
      ShowHTML('   </table>');
    } 
    // Se a programa interno for informado, exibe.
    if (Nvl(f($RS,'sq_isprojeto'),'')>'') {
      ShowHTML('      <tr><td valign="top" colspan="1">Programa interno:<br><b>'.f($RS,'nm_pri'));
      if (Nvl(f($RS,'cd_pri'),'')>'')  ShowHTML(' ('.f($RS,'cd_pri').')');
      if (Nvl(f($RS,'cd_acao'),'')>'') ShowHTML('          <td>Recurso programado:<br><b>'.number_format(f($RS,'valor'),2,',','.').' </b></td>');
    } 
    ShowHTML('  <tr><td colspan="2">Unidade Administrativa:<br><b>'.ExibeUnidade('../',$w_cliente,f($RS,'nm_unidade_adm'),f($RS,'sq_unidade_adm'),$TP).'</b></td>');
    ShowHTML('   <tr valign="top"><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('     <tr valign="top">');
    if (f($RS,'mpog_ppa')=='S') ShowHTML('    <td>Selecionada SPI/MP:<br><b>Sim</b></td>');
    else                        ShowHTML('    <td>Selecionada SPI/MP:<br><b>Não</b></td>');
    if (f($RS,'relev_ppa')=='S')ShowHTML('    <td>Selecionada SE/SEPPIR:<br><b>Sim</b></td>');
    else                        ShowHTML('    <td>Selecionada SE/SEPPIR:<br><b>Não</b></td>');
    ShowHTML('     <tr valign="top">');
    ShowHTML('    <td>Responsável monitoramento:<br><b>'.ExibePessoa('../',$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_sol')).'</b></td>');
    ShowHTML('    <td>Área planejamento:<br><b>'.ExibeUnidade('../',$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</b></td>');
    if (Nvl(f($RS,'cd_acao'),'')>'') {
      $RS1 = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,f($RS,'cd_ppa_pai'),f($RS,'cd_acao'),null,f($RS,'cd_unidade'),null,null,null,null,null);
      ShowHTML('     <tr valign="top">');
      ShowHTML('       <td>Função:<br><b>'.f($RS1,'ds_funcao').' </b></td>');
      ShowHTML('       <td>Subfunção:<br><b>'.f($RS1,'ds_subfuncao').' </b></td>');
      ShowHTML('     <tr valign="top">');
      ShowHTML('       <td>Esfera:<br><b>'.f($RS1,'ds_esfera').' </b></td>');
      ShowHTML('       <td>Tipo de ação:<br><b>'.f($RS1,'nm_tipo_acao').' </b></td>');
    } 
    ShowHTML('     <tr valign="top">');
    ShowHTML('       <td>Início previsto:<br><b>'.FormataDataEdicao(f($RS,'inicio')).' </b></td>');
    ShowHTML('       <td>Fim previsto:<br><b>'.FormataDataEdicao(f($RS,'fim')).' </b></td>');
    ShowHTML('     </table>');
    ShowHTML('     <tr valign="top"><td colspan="2">Parcerias externas:<br><b>'.CRLF2BR(Nvl(f($RS,'proponente'),'---')).' </b></td>');
    ShowHTML('     <tr valign="top"><td colspan="2">Parcerias internas:<br><b>'.CRLF2BR(Nvl(f($RS,'palavra_chave'),'---')).' </b></td>');
    // Responsaveis
    if (f($RS,'nm_gerente_programa')>'' || f($RS,'nm_gerente_executivo')>'' || f($RS,'nm_gerente_adjunto')>'' || f($RS,'resp_ppa')>'' || f($RS,'resp_pri')>'')
      ShowHTML('      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Responsáveis</td>');
    if (f($RS,'nm_gerente_programa')>'' || f($RS,'nm_gerente_executivo')>'' || f($RS,'nm_gerente_adjunto')>'') {
      ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
      if (Nvl(f($RS,'nm_gerente_programa'),'')>'') {
        ShowHTML('      <tr><td valign="top">Gerente do programa:<br><b>'.f($RS,'nm_gerente_programa').' </b></td>');
        if (Nvl(f($RS,'fn_gerente_programa'),'')>'') ShowHTML('          <td>Telefone:<br><b>'.f($RS,'fn_gerente_programa').' </b></td>');
        if (Nvl(f($RS,'em_gerente_programa'),'')>'') ShowHTML('          <td>Email:<br><b>'.f($RS,'em_gerente_programa').' </b></td>');
      } 
      if (Nvl(f($RS,'nm_gerente_executivo'),'')>'') {
        ShowHTML('      <tr><td valign="top">Gerente executivo do programa:<br><b>'.f($RS,'nm_gerente_executivo').' </b></td>');
        if (Nvl(f($RS,'fn_gerente_executivo'),'')>'') ShowHTML('          <td>Telefone:<br><b>'.f($RS,'fn_gerente_executivo').' </b></td>');
        if (Nvl(f($RS,'em_gerente_executivo'),'')>'') ShowHTML('          <td>Email:<br><b>'.f($RS,'em_gerente_executivo').' </b></td>');
      } 
      if (Nvl(f($RS,'nm_gerente_adjunto'),'')>'') {
        ShowHTML('      <tr><td valign="top">Gerente executivo adjunto:<br><b>'.f($RS,'nm_gerente_adjunto').' </b></td>');
        if (Nvl(f($RS,'fn_gerente_adjunto'),'')>'') ShowHTML('          <td>Telefone:<br><b>'.f($RS,'fn_gerente_adjunto').' </b></td>');
        if (Nvl(f($RS,'em_gerente_adjunto'),'')>'') ShowHTML('          <td>Email:<br><b>'.f($RS,'em_gerente_adjunto').' </b></td>');
      } 
      ShowHTML('          </table>');
    } 
    if (Nvl(f($RS,'resp_ppa'),'')>'') {
      ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
      ShowHTML('        <tr><td valign="top">Coordenador:<br><b>'.f($RS,'resp_ppa').' </b></td>');
      if (Nvl(f($RS,'fone_ppa'),'')>'') ShowHTML('          <td>Telefone:<br><b>'.f($RS,'fone_ppa').' </b></td>');
      if (Nvl(f($RS,'mail_ppa'),'')>'') ShowHTML('          <td>Email:<br><b>'.f($RS,'mail_ppa').' </b></td>');
      ShowHTML('          </table>');
    } 
    if (Nvl(f($RS,'resp_pri'),'')>'') {
      ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
      ShowHTML('        <tr><td valign="top">Responsável pela ação:<br><b>'.f($RS,'resp_pri').' </b></td>');
      if (Nvl(f($RS,'fone_pri'),'')>'') ShowHTML('         <td>Telefone:<br><b>'.f($RS,'fone_pri').' </b></td>');
      if (Nvl(f($RS,'mail_pri'),'')>'') ShowHTML('            <td>Email:<br><b>'.f($RS,'mail_pri').' </b></td>');
      ShowHTML('           </table>');
    } 
    //Programação Financeira
    if (Nvl(f($RS,'cd_acao'),'')>'') {
      ShowHTML('        <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Programação financeira</td>');
      if (f($RS,'cd_tipo_acao')!=3) {
        $RS1 = db_getPPADadoFinanc_IS::getInstanceOf($dbms,f($RS,'cd_acao'),f($RS,'cd_unidade'),$w_ano,$w_cliente,'VALORFONTEACAO');
        if (count($RS1)<=0) {
          ShowHTML('                      <tr><td valign="top" colspan="2"><DD><b>Nao existe nenhum valor para esta ação</b></DD></td>');
        } else {
          $w_cor='';
          ShowHTML('                      <tr><td valign="top" colspan="2">Fonte: SIGPLAN/MP - PPA 2004-2007</td>');
          if (f($RS,'cd_tipo_acao')==1) {
            ShowHTML('                   <tr><td valign="top" colspan="2">Realizado até 2004: <b>'.number_format(Nvl(f($RS,'valor_ano_anterior'),0),2,',','.').'</b></td>');
            ShowHTML('                   <tr><td valign="top" colspan="2">Justificativa da repercusão financeira sobre o custeio da União: <b>'.Nvl(f($RS,'reperc_financeira'),'---').'</b></td>');
            ShowHTML('                   <tr><td valign="top" colspan="2">Valor estimado da repercussão financeira por ano (R$ 1,00): <b>'.number_format(Nvl(f($RS,'valor_reperc_financeira'),0),2,',','.').'</b></td>');
          } 
          $i=0;
          foreach($RS1 as $row1) {
            if ($i==0) {
              ShowHTML('                      <tr><td valign="top" colspan="2"><b>Ação: </b>'.f($row1,'cd_unidade').'.'.f($RS,'cd_programa').'.'.f($row1,'cd_acao').' - '.f($row1,'descricao_acao').'</td>');
              ShowHTML('                      <tr><td valign="top" align="center">');
              ShowHTML('                        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
              ShowHTML('                          <tr bgcolor="'.$conTrBgColor.'" align="center">');
              ShowHTML('                            <td><b>Fonte</td>');
              ShowHTML('                            <td><b>2004*</td>');
              ShowHTML('                            <td><b>2005**</td>');
              ShowHTML('                            <td><b>2006</td>');
              ShowHTML('                            <td><b>2007</td>');
              ShowHTML('                            <td><b>2008</td>');
              ShowHTML('                            <td><b>Total 2004-2008</td>');
              ShowHTML('                          </tr>');
              $i+=1;
            }
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            ShowHTML('                       <tr bgcolor="'.$w_cor.'" valign="top">');
            ShowHTML('                         <td>'.f($row1,'nm_fonte').'</td>');
            ShowHTML('                         <td align="center">'.number_format(Nvl(f($row1,'valor_ano_1'),0),2,',','.').'</td>');
            ShowHTML('                         <td align="center">'.number_format(Nvl(f($row1,'valor_ano_2'),0),2,',','.').'</td>');
            ShowHTML('                         <td align="center">'.number_format(Nvl(f($row1,'valor_ano_3'),0),2,',','.').'</td>');
            ShowHTML('                         <td align="center">'.number_format(Nvl(f($row1,'valor_ano_4'),0),2,',','.').'</td>');
            ShowHTML('                         <td align="center">'.number_format(Nvl(f($row1,'valor_ano_5'),0),2,',','.').'</td>');
            ShowHTML('                         <td align="center">'.number_format(Nvl(f($row1,'valor_total'),0),2,',','.').'</td>');
            ShowHTML('                       </tr>');
          } 
          $RS1 = db_getPPADadoFinanc_IS::getInstanceOf($dbms,f($RS,'cd_acao'),f($RS,'cd_unidade'),$w_ano,$w_cliente,'VALORTOTALACAO');
          ShowHTML('      <tr><td valign="top" align="right"><b>Totais </td>');
          if (count($RS1)<=0) {
            ShowHTML('          <td valign="top" colspan="6"><DD><b>Nao existe nenhum valor para esta ação</b></DD></td>');
          } else {
            foreach ($RS1 as $row1) {$RS1=$row1; break;}
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            ShowHTML('         <td align="center"><b>'.number_format(Nvl(f($RS1,'valor_ano_1'),0),2,',','.').'</td>');
            ShowHTML('         <td align="center"><b>'.number_format(Nvl(f($RS1,'valor_ano_2'),0),2,',','.').'</td>');
            ShowHTML('         <td align="center"><b>'.number_format(Nvl(f($RS1,'valor_ano_3'),0),2,',','.').'</td>');
            ShowHTML('         <td align="center"><b>'.number_format(Nvl(f($RS1,'valor_ano_4'),0),2,',','.').'</td>');
            ShowHTML('         <td align="center"><b>'.number_format(Nvl(f($RS1,'valor_ano_5'),0),2,',','.').'</td>');
            ShowHTML('         <td align="center"><b>'.number_format(Nvl(f($RS1,'valor_total'),0),2,',','.').'</td>');
            ShowHTML('       </tr>');
            ShowHTML('       </table>');
          } 
        } 
        ShowHTML('<tr><td valign="top" colspan="2">* Valor Lei Orçamentária Anual - LOA 2004 + Créditos</td>');
        ShowHTML('<tr><td valign="top" colspan="2">** Valor do Projeto de Lei Orçamentária Anual - PLOA 2005</td>');
      } 
      // Recupera todos os registros para a listagem
      $RS1 = db_getFinancAcaoPPA_IS::getInstanceOf($dbms,$w_chave,$w_cliente,$w_ano,null,null,null);
      // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
      if (count($RS1)>0) {
        // Se não foram selecionados registros, exibe mensagem
        ShowHTML('<tr><td colspan="2" align="center">');
        ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('          <td><b>Código</td>');
        ShowHTML('          <td><b>Nome</td>');
        ShowHTML('        </tr>');
        $w_cor='';
        // Lista os registros selecionados para listagem
        foreach($RS1 as $row1) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('        <td>'.f($RS1,'cd_programa').'.'.f($RS1,'cd_acao').'.'.f($RS1,'cd_unidade').'</td>');
          ShowHTML('        <td>'.f($RS1,'descricao_acao').'</td>');
          ShowHTML('      </tr>');
        } 
        ShowHTML('          </table>');
      } 
    } else {
      // Recupera todos os registros para a listagem
      $RS1 = db_getFinancAcaoPPA_IS::getInstanceOf($dbms,$w_chave,$w_cliente,$w_ano,null,null,null);
      // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
      if (count($RS1)>0) {
        // Se não foram selecionados registros, exibe mensagem
        ShowHTML('        <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Programação financeira</td>');
        ShowHTML('<tr><td colspan="2" align="center">');
        ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('          <td><b>Código</td>');
        ShowHTML('          <td><b>Nome</td>');
        ShowHTML('        </tr>');
        $w_cor='';
        // Lista os registros selecionados para listagem
        foreach($RS1 as $row1) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('        <td>'.f($RS1,'cd_unidade').'.'.f($RS1,'cd_programa').'.'.f($RS1,'cd_acao').'</td>');
          ShowHTML('        <td>'.f($RS1,'descricao_acao').'</td>');
          ShowHTML('      </tr>');
        } 
        ShowHTML('          </table>');
      } 
    } 
    ShowHTML('      <tr><td valign="top"><b><u>R</u>ecurso programado:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_valor" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" onKeyDown="FormataValor(this,18,2,event);"></td>');
    ShowHTML('      <tr><td><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="2"><hr>');
    ShowHTML('      <tr><td align="center" colspan="2">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&O=P&SG='.$SG.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,'A');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ISACAD');
    SelecaoAcao('Açã<u>o</u>:','O','Selecione a ação na relação.',$w_cliente,$w_ano,null,null,null,null,'w_chave','ACAO',null,null);
    ShowHTML('      <tr><td align="center">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
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
  Rodape();
} 
// =========================================================================
// Rotina dos responsaveis
// -------------------------------------------------------------------------
function Responsaveis() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave          = $_REQUEST['w_chave'];
  $w_chave_aux      = $_REQUEST['w_chave_aux'];
  $w_programa       = $_REQUEST['w_programa'];
  $w_acao           = $_REQUEST['w_acao'];
  $w_unidade        = $_REQUEST['w_unidade'];
  $w_sq_isprojeto   = $_REQUEST['w_sq_isprojeto'];
  $w_nome_pai       = $_REQUEST['w_nome_pai'];
  $w_codigo_pai     = $_REQUEST['w_codigo_pai'];
  if ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,$SG);
    foreach ($RS as $row) {$RS=$row; break;}
  } elseif ($O=='A') {
    if ($w_programa>'' && $w_acao>'' && $w_unidade>'') {
      $w_tipo=1;
      $RS = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,$w_programa,$w_acao,null,$w_unidade,null,null,null,null,null);
      foreach ($RS as $row) {$RS=$row; break;}
    } elseif ($w_sq_isprojeto>'') {
      $w_tipo=2;
      $RS = db_getProjeto_IS::getInstanceOf($dbms,$w_sq_isprojeto,$w_cliente,null,null,null,null,null,null,null,null,null,null,null,null);
      foreach ($RS as $row) {$RS=$row; break;}
    } 
    if (count($RS)>0) {
      $w_responsavel    = f($RS,'responsavel');
      $w_telefone       = f($RS,'telefone');
      $w_email          = f($RS,'email');
      if ($w_tipo==1) {
        $w_nome         = f($RS,'descricao_acao').' - '.f($RS,'ds_unidade');
        $w_codigo       = f($RS,'cd_acao').'.'.f($RS,'cd_unidade');
        $w_nome_pai     = f($RS,'ds_programa');
        $w_codigo_pai   = f($RS,'cd_programa');
      } elseif ($w_tipo==2) {
        $w_nome     = f($RS,'nome');
        $w_codigo   = f($RS,'codigo');
      } 
    } 
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if ($O=='A') {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    formatadata();
    SaltaCampo();
    FormataCEP();
    FormataValor();
    ValidateOpen('Validacao');
    Validate('w_responsavel','Coordenador','','1','3','60','1','1');
    Validate('w_telefone','Telenfone','1','','7','20','1','1');
    Validate('w_email','Email','','','3','60','1','1');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($O=='A') {
    BodyOpen('onLoad=\'document.Form.w_responsavel.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td align="center" colspan=3>&nbsp;');
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Tipo</td>');
    ShowHTML('          <td><b>Nome</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      if ((Nvl(f($RS,'cd_ppa_pai'),'')>'' && Nvl(f($RS,'cd_acao'),'')>'' && Nvl(f($RS,'cd_unidade'),'')>'')) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>Ação PPA</td>');
        ShowHTML('        <td>'.f($RS,'cd_unidade').'.'.f($RS,'cd_programa').'.'.f($RS,'cd_acao').' - '.f($RS,'nm_ppa').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_programa='.f($RS,'sq_acao_ppa_pai').'&w_acao='.f($RS,'cd_acao').'&w_unidade='.f($RS,'cd_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_chave_aux='.f($RS,'sq_siw_solicitacao').'">Coordenador</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
      if (Nvl(f($RS,'sq_isprojeto'),'')>'') {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>Programa interno</td>');
        ShowHTML('        <td>'.f($RS,'nm_pri').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_sq_isprojeto='.f($RS,'sq_isprojeto').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_chave_aux='.f($RS,'sq_isprojeto').'">Responsável</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif ($O=='A') {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_tipo" value="'.$w_tipo.'">');
    if ($w_tipo==1) {
      $w_label      = 'Ação PPA';
      $w_chave_aux  = $w_chave;
    } elseif ($w_tipo==2) {
      $w_label      = 'Programa interno';
      $w_chave_aux  = $w_sq_isprojeto;
    } 
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    if ($w_tipo==1) ShowHTML('      <tr><td valign="top"><b>Programa PPA: </b>'.$w_codigo_pai.' - '.$w_nome_pai.' </b>');
    ShowHTML('      <tr><td valign="top"><b>'.$w_label.': </b>');
    if ($w_tipo!=2) ShowHTML(''.$w_codigo.' - ');
    ShowHTML(''.$w_nome.'</td>');
    if ($w_tipo==1)     ShowHTML('      <tr><td><b><u>C</u>oordenador:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_responsavel" class="STI" SIZE="50" MAXLENGTH="60" VALUE="'.$w_responsavel.'" title="Informe o nome do coordenador da ação."></td>');
    elseif ($w_tipo==2) ShowHTML('      <tr><td><b>Res<u>p</u>onsável:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="w_responsavel" class="STI" SIZE="50" MAXLENGTH="60" VALUE="'.$w_responsavel.'" title="Informe o nome do responsável da ação."></td>');
    ShowHTML('      <tr><td valign="top"><b><u>T</u>elefone:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_telefone" class="STI" SIZE="15" MAXLENGTH="14" VALUE="'.$w_telefone.'" title="Informe o telefone do coordenador da ação"></td>');
    ShowHTML('      <tr><td><b>E<u>m</u>ail:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_email" class="STI" SIZE="50" MAXLENGTH="60" VALUE="'.$w_email.'" title="Informe o e-mail do coordenador da ação."></td>');
    ShowHTML('      <tr><td align="center" colspan=4><hr>');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
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
  Rodape();
} 
// =========================================================================
// Rotina de cadastramento da programação qualitativa
// -------------------------------------------------------------------------
function ProgramacaoQualitativa() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_readonly   = '';
  $w_erro       = '';
  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'') {
    // Se for recarga da página
    $w_chave        = $_REQUEST['w_chave'];
    $w_sq_menu      = $_REQUEST['w_sq_menu'];
    $w_problema     = $_REQUEST['w_problema'];
    $w_objetivo     = $_REQUEST['w_objetivo'];
    $w_publico_alvo = $_REQUEST['w_publico_alvo'];
    $w_estrategia   = $_REQUEST['w_estrategia'];
    $w_sistematica  = $_REQUEST['w_sistematica'];
    $w_metodologia  = $_REQUEST['w_metodologia'];
    $w_observacao   = $_REQUEST['w_observacao'];
    $w_cd_acao      = $_REQUEST['w_cd_acao'];
  } else {
    if (!(strpos('AEV',$O)===false) || $w_copia>'') {
      $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,$SG);
      foreach ($RS as $row) {$RS=$row; break;}
      if (count($RS)>0) {
        $w_sq_menu      = f($RS,'sq_menu');
        $w_problema     = f($RS,'problema');
        $w_finalidade   = f($RS,'finalidade');
        $w_objetivo     = f($RS,'objetivo');
        $w_descricao_ppa= f($RS,'descricao_ppa');
        $w_publico_alvo = f($RS,'publico_alvo');
        $w_estrategia   = f($RS,'estrategia');
        $w_sistematica  = f($RS,'sistematica');
        $w_metodologia  = f($RS,'metodologia');
        if (f($RS,'justificativa')!='') $w_observacao = f($RS,'justificativa'); else $w_observacao = f($RS,'observacao_ppa');
        $w_cd_acao      = Nvl(f($RS,'cd_acao'),'');
        $w_ds_acao      = Nvl(f($RS,'nm_ppa'),'');
        $w_cd_programa  = Nvl(f($RS,'cd_programa'),'');
        $w_ds_programa  = Nvl(f($RS,'nm_ppa_pai'),'');
        $w_cd_unidade   = Nvl(f($RS,'cd_unidade'),'');
      } 
    } 
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataDataHora();
  FormataValor();
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.Botao.value == "Troca") { return true; }');
    Validate('w_problema','Justificativa','1','',5,2000,'1','1');
    Validate('w_objetivo','Objetivo específico','1','',5,2000,'1','1');
    Validate('w_publico_alvo','Público_alvo','1','',5,2000,'1','1');
    Validate('w_estrategia','Sistemáticas e estratégias','1','',5,2000,'1','1');
    Validate('w_sistematica','Sistemática a ser adotada','1','',5,2000,'1','1');
    Validate('w_observacao','Observações','1','',5,4000,'1','1');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('EV',$O)===false)) {
    BodyOpen('onLoad=\'this.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_problema.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled=' DISABLED ';
      if ($O=='V') $w_erro = Validacao($w_sq_solicitacao,$SG);
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><font size="2"><b>Programação qualitativa</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Os dados deste bloco visam orientar os executores do programa.</td></tr>');
    if ($w_cd_programa>'' && $w_cd_acao>'' && $w_cd_unidade>'') {
      ShowHTML('      <tr><td>Programa '.$w_cd_programa.' - '.$w_ds_programa.'</td>');
      ShowHTML('      <tr><td>Ação '.$w_cd_unidade.'.'.$w_cd_acao.' - '.$w_ds_acao.'</td>');
    } 
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    if ($w_cd_acao>'') ShowHTML('      <tr><td valign="top"><div align="justify">Objetivo:<br><b>'.Nvl($w_finalidade,'---').'</b></div></td>');
    ShowHTML('      <tr><td valign="top"><b><u>J</u>ustificativa:</b><br><textarea '.$w_Disabled.' accesskey="P" name="w_problema" class="STI" ROWS=5 cols=75 title="Descrição do problema que a ação tem por objetivo enfrentar, abordando o diagnóstico e as causas da situação-problema para a qual a ação foi proposta; alertando quanto às conseqüências da não implementação da ação; e informando a existência de condicionantes favoráveis ou desfavoráveis à ação.">'.$w_problema.'</TEXTAREA></td>');
    ShowHTML('      <tr><td valign="top"><b><u>O</u>bjetivo específico:</b><br><textarea '.$w_Disabled.' accesskey="O" name="w_objetivo" class="STI" ROWS=5 cols=75 title="Informar, de forma  detalhada e específica, o resultado que se quer alcançar, ou seja, a transformação ou mudança da realidade concreta a qual a ação se propôs modificar.">'.$w_objetivo.'</TEXTAREA></td>');
    if ($w_cd_acao>'') ShowHTML('      <tr><td><div align="justify">Descrição da ação:<br><b>'.Nvl($w_descricao_ppa,'---').'</b></div></td>');
    ShowHTML('      <tr><td valign="top"><b>P<u>ú</u>blico alvo:</b><br><textarea '.$w_Disabled.' accesskey="U" name="w_publico_alvo" class="STI" ROWS=5 cols=75 title="Especifique os segmentos da sociedade aos quais a ação se destina e que se beneficiam direta e legitimamente com sua execução. São os grupos de pessoas, comunidades, instituições ou setores que serão atingidos diretamente pelos resultados da ação.">'.$w_publico_alvo.'</TEXTAREA></td>');
    if ($w_cd_acao>'') {
      $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,$SG);
      foreach ($RS as $row) {$RS=$row; break;}
      ShowHTML('      <tr><td valign="top" colspan="2"><div align="justify">Origem da ação:<br><b>'.Nvl(f($RS,'nm_tipo_inclusao_acao'),'---').'</b></div></td>');
      ShowHTML('      <tr><td valign="top" colspan="2"><div align="justify">Base legal:<br><b>'.Nvl(f($RS,'base_legal'),'---').'</b></div></td>');
      ShowHTML('      <tr><td valign="top" colspan="2">Forma de implementação:<br><b>');
      if (f($RS,'cd_tipo_acao')==1 || f($RS,'cd_tipo_acao')==2) {
        if (f($RS,'direta') == 'S')           ShowHTML('       direta');
        if (f($RS,'descentralizada') == 'S')  ShowHTML('       descentralizada');
        if (f($RS,'linha_credito') == 'S')    ShowHTML('       linha de crédito');
      } elseif (f($RS,'cd_tipo_acao') == 4) {
        if (f($RS,'transf_obrigatoria') == 'S')   ShowHTML('       transferência obrigatória');
        if (f($RS,'transf_voluntaria') == 'S')    ShowHTML('       transferência voluntária');
        if (f($RS,'transf_outras') == 'S')        ShowHTML('        outras');
      } 
      ShowHTML('            </b></td>');
      ShowHTML('      <tr><td valign="top" colspan="2"><div align="justify">Detalhamento da implementação:<br><b>'.Nvl(f($RS,'detalhamento'),'---').'</b></div></td>');
    } 
    ShowHTML('      <tr><td valign="top"><b>Sistemática e <u>e</u>stratégias a serem adotadas para o monitoramento da ação:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_estrategia" class="STI" ROWS=5 cols=75 title="Descreva a sistemática e as estratégias que serão adotadas para o monitoramento da ação, informando, inclusive as ferramentas que serão utilizadas.">'.$w_estrategia.'</TEXTAREA></td>');
    ShowHTML('      <tr><td valign="top"><b><u>S</u>istemática e metodologias a serem adotadas para avaliação da ação:</b><br><textarea '.$w_Disabled.' accesskey="S" name="w_sistematica" class="STI" ROWS=5 cols=75 title="Descreva a sistemática e as metodologias que serão adotadas para a avaliação da ação, informando, inclusive as ferramentas que serão utilizadas.">'.$w_sistematica.'</TEXTAREA></td>');
    ShowHTML('      <tr><td valign="top"><b>O<u>b</u>servações:</b><br><textarea '.$w_Disabled.' accesskey="B" name="w_observacao" class="STI" ROWS=5 cols=75 title="Informe as observações pertinentes (campo não obrigatório).">'.$w_observacao.'</TEXTAREA></td>');
    // Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    if ($O=='I') {
      $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&w_copia='.$w_copia.'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    } 
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
  Rodape();
} 
// =========================================================================
// Rotina de metas da ação
// -------------------------------------------------------------------------
function Metas() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_ordem                = $_REQUEST['w_ordem'];
    $w_titulo               = $_REQUEST['w_titulo'];
    $w_descricao            = $_REQUEST['w_descricao'];
    $w_inicio               = $_REQUEST['w_inicio'];
    $w_fim                  = $_REQUEST['w_fim'];
    $w_inicio_real          = $_REQUEST['w_inicio_real'];
    $w_fim_real             = $_REQUEST['w_fim_real'];
    $w_perc_conclusao       = $_REQUEST['w_perc_conclusao'];
    $w_orcamento            = $_REQUEST['w_orcamento'];
    $w_unidade_medida       = $_REQUEST['w_unidade_medida'];
    $w_quantidade           = $_REQUEST['w_quantidade'];
    $w_cumulativa           = $_REQUEST['w_cumulativa'];
    $w_programada           = $_REQUEST['w_programada'];
    $w_cd_subacao           = $_REQUEST['w_cd_subacao'];
    $w_cron_ini_1           = $_REQUEST['w_cron_ini_1'];
    $w_cron_ini_2           = $_REQUEST['w_cron_ini_2'];
    $w_cron_ini_3           = $_REQUEST['w_cron_ini_3'];
    $w_cron_ini_4           = $_REQUEST['w_cron_ini_4'];
    $w_cron_ini_5           = $_REQUEST['w_cron_ini_5'];
    $w_cron_ini_6           = $_REQUEST['w_cron_ini_6'];
    $w_cron_ini_7           = $_REQUEST['w_cron_ini_7'];
    $w_cron_ini_8           = $_REQUEST['w_cron_ini_8'];
    $w_cron_ini_9           = $_REQUEST['w_cron_ini_9'];
    $w_cron_ini_10          = $_REQUEST['w_cron_ini_10'];
    $w_cron_ini_11          = $_REQUEST['w_cron_ini_11'];
    $w_cron_ini_12          = $_REQUEST['w_cron_ini_12'];
    $w_previsto_acao_1      = $_REQUEST['w_previsto_acao_1'];
    $w_previsto_acao_2      = $_REQUEST['w_previsto_acao_2'];
    $w_previsto_acao_3      = $_REQUEST['w_previsto_acao_3'];
    $w_previsto_acao_4      = $_REQUEST['w_previsto_acao_4'];
    $w_previsto_acao_5      = $_REQUEST['w_previsto_acao_5'];
    $w_previsto_acao_6      = $_REQUEST['w_previsto_acao_6'];
    $w_previsto_acao_7      = $_REQUEST['w_previsto_acao_7'];
    $w_previsto_acao_8      = $_REQUEST['w_previsto_acao_8'];
    $w_previsto_acao_9      = $_REQUEST['w_previsto_acao_9'];
    $w_previsto_acao_10     = $_REQUEST['w_previsto_acao_10'];
    $w_previsto_acao_11     = $_REQUEST['w_previsto_acao_11'];
    $w_previsto_acao_12     = $_REQUEST['w_previsto_acao_12'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getSolicMeta_IS::getInstanceOf($dbms,$w_cliente,$w_chave,null,'LISTA',null,null,null,null,null,null,null,null,null);
    $RS = SortArray($RS,'ordem','asc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados do endereço informado
    $RS = db_getSolicMeta_IS::getInstanceOf($dbms,$w_cliente,$w_chave,$w_chave_aux,'REGISTRO',null,null,null,null,null,null,null,null,null);
    foreach($RS as $row) {$RS=$row; break;}
    $w_titulo                   =f($RS,'titulo');
    $w_ordem                    =f($RS,'ordem');
    $w_descricao                =f($RS,'descricao');
    $w_inicio                   =f($RS,'inicio_previsto');
    $w_fim                      =f($RS,'fim_previsto');
    $w_inicio_real              =f($RS,'inicio_real');
    $w_fim_real                 =f($RS,'fim_real');
    $w_perc_conclusao           =f($RS,'perc_conclusao');
    $w_orcamento                =f($RS,'orcamento');
    $w_sq_pessoa                =f($RS,'sq_pessoa');
    $w_sq_unidade               =f($RS,'sq_unidade');
    $w_unidade_medida           =f($RS,'unidade_medida');
    $w_quantidade               =number_format(Nvl(f($RS,'quantidade'),0),2,',','.');
    $w_cumulativa               =f($RS,'cumulativa');
    $w_programada               =f($RS,'programada');
    $w_cd_subacao               =f($RS,'cd_subacao');
    $w_cron_ini_1               =Nvl(f($RS,'cron_ini_mes_1'),'');
    $w_cron_ini_2               =Nvl(f($RS,'cron_ini_mes_2'),'');
    $w_cron_ini_3               =Nvl(f($RS,'cron_ini_mes_3'),'');
    $w_cron_ini_4               =Nvl(f($RS,'cron_ini_mes_4'),'');
    $w_cron_ini_5               =Nvl(f($RS,'cron_ini_mes_5'),'');
    $w_cron_ini_6               =Nvl(f($RS,'cron_ini_mes_6'),'');
    $w_cron_ini_7               =Nvl(f($RS,'cron_ini_mes_7'),'');
    $w_cron_ini_8               =Nvl(f($RS,'cron_ini_mes_8'),'');
    $w_cron_ini_9               =Nvl(f($RS,'cron_ini_mes_9'),'');
    $w_cron_ini_10              =Nvl(f($RS,'cron_ini_mes_10'),'');
    $w_cron_ini_11              =Nvl(f($RS,'cron_ini_mes_11'),'');
    $w_cron_ini_12              =Nvl(f($RS,'cron_ini_mes_12'),'');
    $w_previsto_acao_1          =number_format(Nvl(f($RS,'valor_ini_1'),0),2,',','.');
    $w_previsto_acao_2          =number_format(Nvl(f($RS,'valor_ini_2'),0),2,',','.');
    $w_previsto_acao_3          =number_format(Nvl(f($RS,'valor_ini_3'),0),2,',','.');
    $w_previsto_acao_4          =number_format(Nvl(f($RS,'valor_ini_4'),0),2,',','.');
    $w_previsto_acao_5          =number_format(Nvl(f($RS,'valor_ini_5'),0),2,',','.');
    $w_previsto_acao_6          =number_format(Nvl(f($RS,'valor_ini_6'),0),2,',','.');
    $w_previsto_acao_7          =number_format(Nvl(f($RS,'valor_ini_7'),0),2,',','.');
    $w_previsto_acao_8          =number_format(Nvl(f($RS,'valor_ini_8'),0),2,',','.');
    $w_previsto_acao_9          =number_format(Nvl(f($RS,'valor_ini_9'),0),2,',','.');
    $w_previsto_acao_10         =number_format(Nvl(f($RS,'valor_ini_10'),0),2,',','.');
    $w_previsto_acao_11         =number_format(Nvl(f($RS,'valor_ini_11'),0),2,',','.');
    $w_previsto_acao_12         =number_format(Nvl(f($RS,'valor_ini_12'),0),2,',','.');
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    formatadata();
    SaltaCampo();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      if (Nvl($w_cd_subacao,'')=='') {
        Validate('w_titulo','Produto','','1','2','100','1','1');
        Validate('w_unidade_medida','Unidade de medida','','1','2','100','1','1');
        Validate('w_quantidade','Quantitativo programado','VALOR','1','1','18','','0123456789,.');
        CompValor('w_quantidade','Quantitativo programado','>','0,00','zero');
      } 
      Validate('w_descricao','Especificação do produto','1','1','2','2000','1','1');
      Validate('w_ordem','Ordem','1','1','1','3','','0123456789');
      Validate('w_inicio','Início previsto','DATA','1','10','10','','0123456789/');
      Validate('w_fim','Fim previsto','DATA','1','10','10','','0123456789/');
      CompData('w_inicio','Início previsto','<=','w_fim','Fim previsto');
      if (Nvl($w_cd_subacao,'')>'') {
        Validate('w_cron_ini_1','Quantitativo previsto de Janeiro','','','1','10','','0123456789');
        Validate('w_cron_ini_2','Quantitativo previsto de Fevereiro','','','1','10','','0123456789');
        Validate('w_cron_ini_3','Quantitativo previsto de Março','','','1','10','','0123456789');
        Validate('w_cron_ini_4','Quantitativo previsto de Abril','','','1','10','','0123456789');
        Validate('w_cron_ini_5','Quantitativo previsto de Maio','','','1','10','','0123456789');
        Validate('w_cron_ini_6','Quantitativo previsto de Junho','','','1','10','','0123456789');
        Validate('w_cron_ini_7','Quantitativo previsto de Julho','','','1','10','','0123456789');
        Validate('w_cron_ini_8','Quantitativo previsto de Agosto','','','1','10','','0123456789');
        Validate('w_cron_ini_9','Quantitativo previsto de Setembro','','','1','10','','0123456789');
        Validate('w_cron_ini_10','Quantitativo previsto de Outubro','','','1','10','','0123456789');
        Validate('w_cron_ini_11','Quantitativo previsto de Novembro','','','1','10','','0123456789');
        Validate('w_cron_ini_12','Quantitativo previsto de Dezembro','','','1','10','','0123456789');
      } 
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
    BodyOpen('onLoad=\'document.Form.w_titulo.focus()\';');
  } else {
    BodyOpen(null);
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    $RS1 = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,$SG);
    foreach ($RS1 as $row) {$RS1=$row; break;}
    if (Nvl(f($RS1,'cd_programa'),'')>'' && Nvl(f($RS1,'cd_acao'),'')>'' && Nvl(f($RS1,'cd_unidade'),'')>'') {
      ShowHTML('      <tr><td colspan="2">Programa '.f($RS1,'cd_programa').' - '.f($RS1,'nm_ppa_pai').'</td>');
      ShowHTML('      <tr><td colspan="2">Ação '.f($RS1,'cd_unidade').'.'.f($RS1,'cd_acao').' - '.f($RS1,'nm_ppa').'</td>');
    } 
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Produtos</td>');
    ShowHTML('          <td><b>PPA</td>');
    ShowHTML('          <td><b>Data conclusão</td>');
    ShowHTML('          <td><b>Executado</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=9 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      foreach($RS as $row) {
       ShowHTML(Metalinha($w_chave,f($row,'sq_meta'),f($row,'titulo'),f($row,'nm_resp'),f($row,'sg_setor'),f($row,'inicio_previsto'),f($row,'fim_previsto'),f($row,'perc_conclusao'),null,'<b>','S','PROJETO',f($row,'cd_subacao')));
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_orcamento" value="0,00">');
    ShowHTML('<INPUT type="hidden" name="w_perc_conclusao" value="0">');
    if ($w_cd_subacao>'') {
      ShowHTML('<INPUT type="hidden" name="w_titulo" value="'.$w_titulo.'">');
      ShowHTML('<INPUT type="hidden" name="w_unidade_medida" value="'.$w_unidade_medida.'">');
      ShowHTML('<INPUT type="hidden" name="w_quantidade" value="'.$w_quantidade.'">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_1" value="01/01/2004">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_2" value="01/02/2004">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_3" value="01/03/2004">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_4" value="01/04/2004">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_5" value="01/05/2004">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_6" value="01/06/2004">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_7" value="01/07/2004">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_8" value="01/08/2004">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_9" value="01/09/2004">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_10" value="01/10/2004">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_11" value="01/11/2004">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_12" value="01/12/2004">');
    } 
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    if ($w_cd_subacao>'') $w_Disabled='DISABLED';
    ShowHTML('      <tr><td><b>Prod<u>u</u>to:</b><br><input '.$w_Disabled.' accesskey="U" type="text" name="w_titulo" class="STI" SIZE="90" MAXLENGTH="90" VALUE="'.$w_titulo.'" title="Informe o bem ou serviço que resulta da ação, destinado ao público-alvo ou o investimento para a produção deste bem ou serviço. Em situações especiais, expressa a quantidade de beneficiários atendidos pela ação."></td>');
    if ($w_cd_subacao>'') $w_Disabled='';
    ShowHTML('     <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    MontaRadioNS('<b>Meta cumulativa?</b>',$w_cumulativa,'w_cumulativa');
    MontaRadioNS('<b>Meta do PNPIR?</b>',$w_programada,'w_programada');
    ShowHTML('         </table></td></tr>');
    if ($w_cd_subacao>'') $w_Disabled='DISABLED';
    ShowHTML('     <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    ShowHTML('         <tr><td align="left"><b><u>Q</u>uantitativo:<br><input accesskey="Q" type="text" name="w_quantidade" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_quantidade.'" '.$w_Disabled.' onKeyDown="FormataValor(this,18,2,event);" title="Indicar a quantidade da meta da ação programada para determinado período de tempo."></td>');
    ShowHTML('             <td align="left"><b><u>U</u>nidade de medida:<br><INPUT ACCESSKEY="U" TYPE="TEXT" CLASS="STI" NAME="w_unidade_medida" SIZE=15 MAXLENGTH=30 VALUE="'.$w_unidade_medida.'" '.$w_Disabled.' title="Informar o padrão escolhido para mensuração da relação adotada como meta."></td>');
    ShowHTML('         </table></td></tr>');
    ShowHTML('      <tr><td><b><u>E</u>specificação do produto:</b><br><textarea  accesskey="E" name="w_descricao" class="STI" ROWS=5 cols=75 title="Descrever as características do produto acabado visando sua melhor identificação.">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    ShowHTML('              <td align="left"><b><u>O</u>rdem:<br><INPUT ACCESSKEY="O" TYPE="TEXT" CLASS="STI" NAME="w_ordem" SIZE=3 MAXLENGTH=3 VALUE="'.$w_ordem.'"></td>');
    ShowHTML('              <td><b>Previsão iní<u>c</u>io:</b><br><input accesskey="C" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.FormataDataEdicao(Nvl($w_inicio,time())).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data prevista para início da meta." title="Usar formato dd/mm/aaaa"></td>');
    ShowHTML('              <td><b>Previsão <u>t</u>érmino:</b><br><input accesskey="T" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.FormataDataEdicao($w_fim).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data prevista para término da meta." title="Usar formato dd/mm/aaaa"></td>');
    ShowHTML('          </table>');
    if ($w_cd_subacao>'') {
      $w_Disabled='';
      ShowHTML('     <tr><td valign="top" colspan="1">');
      ShowHTML('       <table border=0 width="40%" cellspacing=0>');
      ShowHTML('         <tr><td>&nbsp<td title="Informe o meta programada mês a mês, nos campos abaixo."><br><b>Quantitativo programado</b></td>');
      ShowHTML('             <td><br><b>Financeiro programado</b></td>');
      ShowHTML('         <tr><td width="4%" align="right"><b>Janeiro:');
      ShowHTML('             <td width="8%"><INPUT TYPE="TEXT" CLASS="STI" NAME="w_cron_ini_1" SIZE=10 MAXLENGTH=18 VALUE="'.$w_cron_ini_1.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td width="5%" align="right">'.Nvl($w_previsto_acao_1,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Fevereiro:');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_cron_ini_2" SIZE=10 MAXLENGTH=18 VALUE="'.$w_cron_ini_2.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td align="right">'.Nvl($w_previsto_acao_2,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Março:');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_cron_ini_3" SIZE=10 MAXLENGTH=18 VALUE="'.$w_cron_ini_3.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td align="right">'.Nvl($w_previsto_acao_3,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Abril:');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_cron_ini_4" SIZE=10 MAXLENGTH=18 VALUE="'.$w_cron_ini_4.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td align="right">'.Nvl($w_previsto_acao_4,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Maio:');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_cron_ini_5" SIZE=10 MAXLENGTH=18 VALUE="'.$w_cron_ini_5.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td align="right">'.Nvl($w_previsto_acao_5,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Junho:');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_cron_ini_6" SIZE=10 MAXLENGTH=18 VALUE="'.$w_cron_ini_6.'" '.$w_Disabled.' ></td>');
      ShowHTML('             <td align="right">'.Nvl($w_previsto_acao_6,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Julho:');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_cron_ini_7" SIZE=10 MAXLENGTH=18 VALUE="'.$w_cron_ini_7.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td align="right">'.Nvl($w_previsto_acao_7,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Agosto:');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_cron_ini_8" SIZE=10 MAXLENGTH=18 VALUE="'.$w_cron_ini_8.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td align="right">'.Nvl($w_previsto_acao_8,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Setembro:');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_cron_ini_9" SIZE=10 MAXLENGTH=18 VALUE="'.$w_cron_ini_9.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td align="right">'.Nvl($w_previsto_acao_9,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Outubro:');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_cron_ini_10" SIZE=10 MAXLENGTH=18 VALUE="'.$w_cron_ini_10.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td align="right">'.Nvl($w_previsto_acao_10,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Novembro:');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_cron_ini_11" SIZE=10 MAXLENGTH=18 VALUE="'.$w_cron_ini_11.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td align="right">'.Nvl($w_previsto_acao_11,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Dezembro:');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_cron_ini_12" SIZE=10 MAXLENGTH=18 VALUE="'.$w_cron_ini_12.'" '.$w_Disabled.' ></td>');
      ShowHTML('             <td align="right">'.Nvl($w_previsto_acao_12,'---').'</td>');
      ShowHTML('       </table>');
    } 
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('          <tr>');
    ShowHTML('      <tr>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td align="center" colspan=4><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      else         ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
    } 
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
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
  Rodape();
} 
// =========================================================================
// Rotina de atualização das metas da ação
// -------------------------------------------------------------------------
function AtualizaMeta() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_tipo       = strtoupper(trim($_REQUEST['w_tipo']));
  $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,'ISACGERAL');
  foreach($RS as $row) {$RS=$row; break;}
  // Configura uma variável para testar se as metas podem ser atualizadas.
  // Ações concluídas ou canceladas não podem ter permitir a atualização.
  $w_desc_acao  = Nvl(f($RS,'titulo'),'');
  $w_cd_unidade = Nvl(f($RS,'cd_unidade'),'');
  $w_cd_acao = Nvl(f($RS,'cd_acao'),'');
  $w_cd_programa = Nvl(f($RS,'cd_programa'),'');
  if (Nvl(f($RS,'sg_tramite'),'--')=='EE') $w_fase='S';
  else                                     $w_fase='N';
  if ($w_troca>'') {
    // Se for recarga da página
    $w_ordem            = $_REQUEST['w_ordem'];
    $w_titulo           = $_REQUEST['w_titulo'];
    $w_descricao        = $_REQUEST['w_descricao'];
    $w_desc_subacao     = $_REQUEST['w_desc_subacao'];
    $w_desc_acao        = $_REQUEST['w_desc_acao'];
    $w_inicio           = $_REQUEST['w_inicio'];
    $w_fim              = $_REQUEST['w_fim'];
    $w_inicio_real      = $_REQUEST['w_inicio_real'];
    $w_fim_real         = $_REQUEST['w_fim_real'];
    $w_perc_conclusao   = $_REQUEST['w_perc_conclusao'];
    $w_orcamento        = $_REQUEST['w_orcamento'];
    $w_sq_pessoa        = $_REQUEST['w_sq_pessoa'];
    $w_sq_unidade       = $_REQUEST['w_sq_unidade'];
    $w_vincula_atividade= $_REQUEST['w_vincula_atividade'];
    $w_unidade_medida   = $_REQUEST['w_unidade_medida'];
    $w_quantidade       = $_REQUEST['w_quantidade'];
    $w_cumulativa       = $_REQUEST['w_cumulativa'];
    $w_programada       = $_REQUEST['w_programada'];
    $w_aprovado_acao    = $_REQUEST['w_aprovado_acao'];
    $w_autorizado_acao  = $_REQUEST['w_autorizado_acao'];
    $w_realizado_acao   = $_REQUEST['w_realizado_acao'];
    for ($i=0; $i<=$i=12; $i=$i+1) {
      $w_realizado[i]=$_REQUEST['w_realizado[i]'];
      $w_revisado[i]=$_REQUEST['w_revisado[i]'];
    } 
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    //$RS = db_getSolicMeta_IS::getInstanceOf($dbms,$w_cliente,$w_chave,null,'LISTA',null,null,null,null,null,null,null,null,null);
    //$RS = SortArray($RS,'ordem','asc');
    // Recupera as metas
    $RS = db_getSolicMeta_IS::getInstanceOf($dbms,$w_cliente,$w_chave,null,'LSTNULL',null,null,null,null,null,null,null,null,null);
    $RS = SortArray($RS,'ordem','asc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados do endereço informado
    $RS = db_getSolicMeta_IS::getInstanceOf($dbms,$w_cliente,$w_chave,$w_chave_aux,'REGISTRO',null,null,null,null,null,null,null,null,null);
    foreach ($RS as $row) {$RS=$row; break;}
    $w_titulo               = f($RS,'titulo');
    $w_ordem                = f($RS,'ordem');
    $w_descricao            = f($RS,'descricao');
    $w_desc_subacao         = f($RS,'descricao_subacao');
    $w_inicio               = f($RS,'inicio_previsto');
    $w_fim                  = f($RS,'fim_previsto');
    $w_inicio_real          = f($RS,'inicio_real');
    $w_fim_real             = f($RS,'fim_real');
    $w_perc_conclusao       = f($RS,'perc_conclusao');
    $w_orcamento            = f($RS,'orcamento');
    $w_sq_pessoa            = f($RS,'sq_pessoa');
    $w_sq_unidade           = f($RS,'sq_unidade');
    $w_ultima_atualizacao   = f($RS,'phpdt_ultima_atualizacao');
    $w_sq_pessoa_atualizacao= f($RS,'sq_pessoa_atualizacao');
    $w_situacao_atual       = f($RS,'situacao_atual');
    $w_unidade_medida       = f($RS,'unidade_medida');
    $w_quantidade           = number_format(Nvl(f($RS,'quantidade'),0),2,',','.');
    $w_cumulativa           = f($RS,'cumulativa');
    $w_programada           = f($RS,'programada');
    $w_exequivel            = f($RS,'exequivel');
    $w_justificativa_inex   = f($RS,'justificativa_inexequivel');
    $w_outras_medidas       = f($RS,'outras_medidas');
    $w_nm_programada        = f($RS,'nm_programada');
    $w_nm_cumulativa        = f($RS,'nm_cumulativa');
    $w_cd_subacao           = Nvl(f($RS,'cd_subacao'),'');
    $w_real_acao_1          = number_format(Nvl(f($RS,'real_mes_1'),0),2,',','.');
    $w_real_acao_2          = number_format(Nvl(f($RS,'real_mes_2'),0),2,',','.');
    $w_real_acao_3          = number_format(Nvl(f($RS,'real_mes_3'),0),2,',','.');
    $w_real_acao_4          = number_format(Nvl(f($RS,'real_mes_4'),0),2,',','.');
    $w_real_acao_5          = number_format(Nvl(f($RS,'real_mes_5'),0),2,',','.');
    $w_real_acao_6          = number_format(Nvl(f($RS,'real_mes_6'),0),2,',','.');
    $w_real_acao_7          = number_format(Nvl(f($RS,'real_mes_7'),0),2,',','.');
    $w_real_acao_8          = number_format(Nvl(f($RS,'real_mes_8'),0),2,',','.');
    $w_real_acao_9          = number_format(Nvl(f($RS,'real_mes_9'),0),2,',','.');
    $w_real_acao_10         = number_format(Nvl(f($RS,'real_mes_10'),0),2,',','.');
    $w_real_acao_11         = number_format(Nvl(f($RS,'real_mes_11'),0),2,',','.');
    $w_real_acao_12         = number_format(Nvl(f($RS,'real_mes_12'),0),2,',','.');
    $w_cron_ini_1           = f($RS,'cron_ini_mes_1');
    $w_cron_ini_2           = f($RS,'cron_ini_mes_2');
    $w_cron_ini_3           = f($RS,'cron_ini_mes_3');
    $w_cron_ini_4           = f($RS,'cron_ini_mes_4');
    $w_cron_ini_5           = f($RS,'cron_ini_mes_5');
    $w_cron_ini_6           = f($RS,'cron_ini_mes_6');
    $w_cron_ini_7           = f($RS,'cron_ini_mes_7');
    $w_cron_ini_8           = f($RS,'cron_ini_mes_8');
    $w_cron_ini_9           = f($RS,'cron_ini_mes_9');
    $w_cron_ini_10          = f($RS,'cron_ini_mes_10');
    $w_cron_ini_11          = f($RS,'cron_ini_mes_11');
    $w_cron_ini_12          = f($RS,'cron_ini_mes_12');
    $w_aprovado_acao        = number_format(Nvl(f($RS,'previsao_ano'),0),2,',','.');
    $w_autorizado_acao      = number_format(Nvl(f($RS,'atual_ano'),0),2,',','.');
    $w_realizado_acao       = number_format(Nvl(f($RS,'real_ano'),0),2,',','.');
    if ($w_cd_acao > '' and $w_cd_subacao > '') $w_cabecalho='      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font  size="2"><b>Ação: '.$w_desc_acao.' ('.$w_cd_unidade.'.'.$w_cd_programa.'.'.$w_cd_acao.'.'.$w_cd_subacao.')</td></tr>';
    elseif ($w_cd_acao > '')                    $w_cabecalho='      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font  size="2"><b>Ação: '.$w_desc_acao.' ('.$w_cd_unidade.'.'.$w_cd_programa.'.'.$w_cd_acao.')</td></tr>';
    else                                        $w_cabecalho='      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font  size="2"><b>Ação: '.$w_desc_acao.' ('.$w_chave.')</td></tr>';
    $RS = db_getMetaMensal_IS::getInstanceOf($dbms,$w_chave_aux);
    if (count($RS)>0) {
      foreach($RS as $row) {
        switch (toNumber(substr(FormataDataEdicao(f($row,'referencia')),3,2))) {
          case 1: $w_realizado_1 = f($row,'realizado');
                  $w_revisado_1  = f($row,'revisado');
          break;
          case 2: $w_realizado_2 = f($row,'realizado');
                  $w_revisado_2  = f($row,'revisado');
          break;
          case 3: $w_realizado_3 = f($row,'realizado');
                  $w_revisado_3  = f($row,'revisado');
          break;
          case 4: $w_realizado_4 = f($row,'realizado');
                  $w_revisado_4  = f($row,'revisado');
          break;
          case 5: $w_realizado_5 = f($row,'realizado');
                  $w_revisado_5  = f($row,'revisado');
          break;
          case 6: $w_realizado_6 = f($row,'realizado');
                  $w_revisado_6  = f($row,'revisado');
          break;
          case 7: $w_realizado_7 = f($row,'realizado');
                  $w_revisado_7  = f($row,'revisado');
          break;
          case 8: $w_realizado_8 = f($row,'realizado');
                  $w_revisado_8  = f($row,'revisado');
          break;
          case 9: $w_realizado_9 = f($row,'realizado');
                  $w_revisado_9  = f($row,'revisado');
          break;
          case 10:$w_realizado_10 = f($row,'realizado');
                  $w_revisado_10  = f($row,'revisado');
          break;
          case 11:$w_realizado_11 = f($row,'realizado');
                  $w_revisado_11  = f($row,'revisado');
          break;
          case 12:$w_realizado_12 = f($row,'realizado');
                  $w_revisado_12  = f($row,'revisado');
          break;
        }
      }
    }
  } elseif (Nvl($w_sq_pessoa,'')=='') {
    // Se a meta não tiver responsável atribuído, recupera o responsável pela ação
    $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,'ISACGERAL');
    foreach ($RS as $row) {$RS=$row; break;}
    $w_sq_pessoa    = f($RS,'solicitante');
    $w_sq_unidade   = f($RS,'sq_unidade_resp');
  } 
  if ($w_tipo=='WORD') HeaderWord();  else Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Meta da ação</TITLE>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_realizado_1','Quantitativo realizado de Janeiro','','','1','10','','0123456789');
      Validate('w_realizado_2','Quantitativo realizado de Fevereiro','','','1','10','','0123456789');
      Validate('w_realizado_3','Quantitativo realizado de Março','','','1','10','','0123456789');
      Validate('w_realizado_4','Quantitativo realizado de Abril','','','1','10','','0123456789');
      Validate('w_realizado_5','Quantitativo realizado de Maio','','','1','10','','0123456789');
      Validate('w_realizado_6','Quantitativo realizado de Junho','','','1','10','','0123456789');
      Validate('w_realizado_7','Quantitativo realizado de Julho','','','1','10','','0123456789');
      Validate('w_realizado_8','Quantitativo realizado de Agosto','','','1','10','','0123456789');
      Validate('w_realizado_9','Quantitativo realizado de Setembro','','','1','10','','0123456789');
      Validate('w_realizado_10','Quantitativo realizado de Outubro','','','1','10','','0123456789');
      Validate('w_realizado_11','Quantitativo realizado de Novembro','','','1','10','','0123456789');
      Validate('w_realizado_12','Quantitativo realizado de Dezembro','','','1','10','','0123456789');
      Validate('w_revisado_1','Quantitativo revisado de Janeiro','','','1','10','','0123456789');
      Validate('w_revisado_2','Quantitativo revisado de Fevereiro','','','1','10','','0123456789');
      Validate('w_revisado_3','Quantitativo revisado de Março','','','1','10', '','0123456789');
      Validate('w_revisado_4','Quantitativo revisado de Abril','','','1','10','','0123456789');
      Validate('w_revisado_5','Quantitativo revisado de Maio','','','1','10','','0123456789');
      Validate('w_revisado_6','Quantitativo revisado de Junho','','','1','10','','0123456789');
      Validate('w_revisado_7','Quantitativo revisado de Julho','','','1','10','','0123456789');
      Validate('w_revisado_8','Quantitativo revisado de Agosto','','','1','10','','0123456789');
      Validate('w_revisado_9','Quantitativo revisado de Setembro','','','1','10','','0123456789');
      Validate('w_revisado_10','Quantitativo revisado de Outubro','','','1','10','','0123456789');
      Validate('w_revisado_11','Quantitativo revisado de Novembro','','','1','10','','0123456789');
      Validate('w_revisado_12','Quantitativo revisado de Dezembro','','','1','10','','0123456789');
      Validate('w_situacao_atual','Situação atual','','','2','4000','1','1');
      ShowHTML('  if (theForm.w_exequivel[1].checked && theForm.w_justificativa_inex.value == \'\') {');
      ShowHTML('     alert (\'Justifique porque a meta não será cumprida!\');');
      ShowHTML('     theForm.w_justificativa_inex.focus();');
      ShowHTML('     return false;');
      ShowHTML('  } else { if (theForm.w_exequivel[0].checked) ');
      ShowHTML('     theForm.w_justificativa_inex.value = \'\';');
      ShowHTML('   }');
      ShowHTML('  if (theForm.w_exequivel[1].checked && theForm.w_outras_medidas.value == \'\') {');
      ShowHTML('     alert (\'Indique quais são as medidas necessárias para o cumprimento da meta!\');');
      ShowHTML('     theForm.w_outras_medidas.focus();');
      ShowHTML('     return false;');
      ShowHTML('  } else { if (theForm.w_exequivel[0].checked) ');
      ShowHTML('     theForm.w_outras_medidas.value = \'\';');
      ShowHTML('   }');
      Validate('w_justificativa_inex','Justificativa','', '','2','4000','1','1');
      Validate('w_outras_medidas','Medidas','','','2','4000','1','1');
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
  } elseif ($O=='I' || $O=='A') {
    BodyOpen(null);
  } else {
    BodyOpen(null);
  } 
  ShowHTML('<div align=center><center>');
  ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML($w_cabecalho);
  if ($w_tipo!='WORD' && $O=='V') {
    ShowHTML('<tr><td align="right"colspan="2">');
    ShowHTML('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
    ShowHTML('&nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&O=V&w_chave='.$w_chave.'&w_chave_aux='.$w_chave_aux.'&P1=10&P2='.$P2.'&P3='.$P3.'&TP='.$TP.'&SG='.$SG.'&w_tipo=WORD'.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
    ShowHTML('</td></tr>');
  } 
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('  <tr><td colspan="2"><font size="3"></td>');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS).'</td></tr>');
    ShowHTML('  <tr><td align="center" colspan="3">');
    ShowHTML('      <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Produtos</td>');
    ShowHTML('          <td><b>PPA</td>');
    ShowHTML('          <td><b>Data conclusão</td>');
    ShowHTML('          <td><b>Executado</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('    <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><font size="2"><b>Não foi encontrado nenhum registro.</b></td></tr>');
    } else {
      foreach($RS as $row) {
        if (Nvl(f($row,'tit_exec'),0)    == $w_usuario || 
            Nvl(f($row,'sub_exec'),0)    == $w_usuario || 
            Nvl(f($row,'titular'),0)     == $w_usuario || 
            Nvl(f($row,'substituto'),0)  == $w_usuario || 
            Nvl(f($row,'executor'),0)    == $w_usuario || 
            Nvl(f($row,'solicitante'),0) == $w_usuario) {
          ShowHTML(Metalinha($w_chave,f($row,'sq_meta'),f($row,'titulo'),f($row,'nm_resp'),f($row,'sg_setor'),f($row,'inicio_previsto'),f($row,'fim_previsto'),f($row,'perc_conclusao'),null,'<b>',$w_fase,'ETAPA',f($row,'cd_subacao')));
        } else {
          ShowHTML(Metalinha($w_chave,f($row,'sq_meta'),f($row,'titulo'),f($row,'nm_resp'),f($row,'sg_setor'),f($row,'inicio_previsto'),f($row,'fim_previsto'),f($row,'perc_conclusao'),null,'<b>','N','ETAPA',f($row,'cd_subacao')));
        }
      } 
      ShowHTML('      </FORM>');
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    if ($w_tipo!='WORD') {
      AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
      ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
      ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
      ShowHTML('<INPUT type="hidden" name="w_perc_ant" value="'.$w_perc_conclusao.'">');
      ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
      ShowHTML('<INPUT type="hidden" name="w_cumulativa" value="'.$w_cumulativa.'">');
      ShowHTML('<INPUT type="hidden" name="w_quantidade" value="'.$w_quantidade.'">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_1" value="01/01/2004">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_2" value="01/02/2004">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_3" value="01/03/2004">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_4" value="01/04/2004">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_5" value="01/05/2004">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_6" value="01/06/2004">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_7" value="01/07/2004">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_8" value="01/08/2004">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_9" value="01/09/2004">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_10" value="01/10/2004">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_11" value="01/11/2004">');
      ShowHTML('<INPUT type="hidden" name="w_referencia_12" value="01/12/2004">');
    } 
    ShowHTML('    <tr><td align="center" bgcolor="#FAEBD7" colspan="2">');
    ShowHTML('      <table border=1 width="100%">');
    ShowHTML('        <tr><td valign="top" colspan="2">');
    ShowHTML('          <TABLE border=0 WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    if (Nvl($w_titulo,'')>'')           ShowHTML('            <tr><td colspan="2">Meta:<b><br><font size=2>'.$w_titulo.'</font></td></tr>');
    if (Nvl($w_desc_subacao,'')>'')     ShowHTML('            <tr><td colspan="2">Subação:<b><br><font size=2>'.$w_desc_subacao.'</font></td></tr>');
    if (Nvl(trim($w_descricao),'')>'')  ShowHTML('            <tr><td colspan="2">Especificação do produto:<b><br>'.$w_descricao.'</td></tr>');
    ShowHTML('            <tr><td valign="top" colspan="2">');
    ShowHTML('              <table border=0 width="100%" cellspacing=0><tr valign="top">');
    if ($w_cd_subacao>'')   ShowHTML('                <td>Meta PPA:<b><br>Sim</td>');
    else                    ShowHTML('                <td>Meta PPA:<b><br>Não</td>');
    ShowHTML('                <td>Meta cumulativa:<b><br>'.$w_nm_cumulativa.'</td></tr>');
    ShowHTML('                <td>Meta do PNPIR:<b><br>'.$w_nm_programada.'</td></tr>');
    ShowHTML('              </table></td></tr>');
    ShowHTML('            <tr><td valign="top" colspan="2">');
    ShowHTML('              <table border=0 width="100%" cellspacing=0><tr valign="top">');
    ShowHTML('                <td>Quantitativo:<b><br>'.$w_quantidade.'</td>');
    ShowHTML('                <td>Unidade de medida:<b><br>'.Nvl($w_unidade_medida,'---').'</td></tr>');
    ShowHTML('              </table></td></tr>');
    ShowHTML('            <tr><td valign="top" colspan="2">');
    ShowHTML('              <table border=0 width="100%" cellspacing=0><tr valign="top">');
    ShowHTML('                <td>Valor aprovado da ação:<b><br>'.$w_aprovado_acao.'</td>');
    ShowHTML('                <td>Valor autorizado da ação:<b><br>'.$w_autorizado_acao.'</td>');
    ShowHTML('                <td>Valor realizado da ação:<b><br>'.$w_realizado_acao.'</td></tr>');
    ShowHTML('              </table></td></tr>');
    ShowHTML('            <tr><td valign="top" colspan="2">');
    ShowHTML('              <table border=0 width="100%" cellspacing=0><tr valign="top">');
    ShowHTML('                <td>Previsão início:<b><br>'.FormataDataEdicao(Nvl($w_inicio,time())).'</td>');
    ShowHTML('                <td>Previsão término:<b><br>'.FormataDataEdicao($w_fim).'</td></tr>');
    ShowHTML('                <tr valign="top">');
    $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$w_sq_pessoa,null,null);
    ShowHTML('                  <td>Responsável pela meta:<b><br>'.f($RS,'nome_resumido').'</td>');
    DesconectaBD();
    $RS = db_getUorgData::getInstanceOf($dbms,$w_sq_unidade);
    ShowHTML('                  <td>Setor responsável pela meta:<b><br>'.f($RS,'nome').' ('.f($RS,'sigla').')</td></tr>');  
    $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,Nvl($w_sq_pessoa_atualizacao,0),null,null);
    ShowHTML('      <tr><td colspan=3>Criação/última atualização:<b><br>'.FormataDataEdicao($w_ultima_atualizacao,3));//'</b>, feita por <b>'.f($RS,'nome_resumido').' ('.f($RS,'sigla').')</b></td>');
    ShowHTML('              </table></td></tr>');
    ShowHTML('          </TABLE>');
    ShowHTML('      </table>');
    ShowHTML('    <tr bgcolor="'.$conTrBgColor.'"><td align="center" colspan="2">');
    ShowHTML('      <table width="100%" border="0">');
    if ($O=='V') {
      ShowHTML('     <tr><td valign="top">');
      ShowHTML('       <table border=1 width="100%" cellspacing=0><tr valign="top">');
      ShowHTML('         <tr align="center" valign="middle">');
      ShowHTML('             <td rowspan=2><b>Mês</b>');
      ShowHTML('             <td colspan=3><b>Quantitativos</b>');
      ShowHTML('             <td rowspan=2><b>Financeiro realizado</b></td>');
      ShowHTML('         <tr align="center" valign="top">');
      ShowHTML('             <td><b>Inicial</b></td>');
      ShowHTML('             <td><b>Revisado</b></td>');
      ShowHTML('             <td><b>Realizado</b></td>');
      ShowHTML('         <tr><td width="12%" align="right"><b>Janeiro:');
      ShowHTML('             <td align="right" width="22%">'.Nvl($w_cron_ini_1,'---').'</td>');
      ShowHTML('             <td align="right" width="22%">'.Nvl($w_revisado_1,'---').'</td>');
      ShowHTML('             <td align="right" width="22%">'.Nvl($w_realizado_1,'---').'</td>');
      ShowHTML('             <td align="right" width="22%">'.Nvl($w_real_acao_1,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Fevereiro:');
      ShowHTML('             <td align="right">'.Nvl($w_cron_ini_2,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_revisado_2,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_realizado_2,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_real_acao_2,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Março:');
      ShowHTML('             <td align="right">'.Nvl($w_cron_ini_3,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_revisado_3,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_realizado_3,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_real_acao_3,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Abril:');
      ShowHTML('             <td align="right">'.Nvl($w_cron_ini_4,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_revisado_4,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_realizado_4,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_real_acao_4,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Maio:');
      ShowHTML('             <td align="right">'.Nvl($w_cron_ini_5,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_revisado_5,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_realizado_5,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_real_acao_5,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Junho:');
      ShowHTML('             <td align="right">'.Nvl($w_cron_ini_6,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_revisado_6,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_realizado_6,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_real_acao_6,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Julho:');
      ShowHTML('             <td align="right">'.Nvl($w_cron_ini_7,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_revisado_7,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_realizado_7,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_real_acao_7,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Agosto:');
      ShowHTML('             <td align="right">'.Nvl($w_cron_ini_8,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_revisado_8,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_realizado_8,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_real_acao_8,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Setembro:');
      ShowHTML('             <td align="right">'.Nvl($w_cron_ini_9,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_revisado_9,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_realizado_9,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_real_acao_9,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Outubro:');
      ShowHTML('             <td align="right">'.Nvl($w_cron_ini_10,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_revisado_10,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_realizado_10,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_real_acao_10,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Novembro:');
      ShowHTML('             <td align="right">'.Nvl($w_cron_ini_11,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_revisado_11,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_realizado_11,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_real_acao_11,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Dezembro:');
      ShowHTML('             <td align="right">'.Nvl($w_cron_ini_12,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_revisado_12,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_realizado_12,'---').'</td>');
      ShowHTML('             <td align="right">'.Nvl($w_real_acao_12,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Total:');
      ShowHTML('             <td align="right"><b>'.(Nvl($w_cron_ini_1,0) + Nvl($w_cron_ini_2,0)  + Nvl($w_cron_ini_3,0) + Nvl($w_cron_ini_4,0)  + Nvl($w_cron_ini_5,0)  + Nvl($w_cron_ini_6,0)  + Nvl($w_cron_ini_7,0)  + Nvl($w_cron_ini_8,0)  + Nvl($w_cron_ini_9,0)  + Nvl($w_cron_ini_10,0)  + Nvl($w_cron_ini_11,0)  + Nvl($w_cron_ini_12,0)).'</td>');
      ShowHTML('             <td align="right"><b>'.(Nvl($w_revisado_1,0) + Nvl($w_revisado_2,0)  + Nvl($w_revisado_3,0) + Nvl($w_revisado_4,0)  + Nvl($w_revisado_5,0)  + Nvl($w_revisado_6,0)  + Nvl($w_revisado_7,0)  + Nvl($w_revisado_8,0)  + Nvl($w_revisado_9,0)  + Nvl($w_revisado_10,0)  + Nvl($w_revisado_11,0)  + Nvl($w_revisado_12,0)).'</td>');
      ShowHTML('             <td align="right"><b>'.(Nvl($w_realizado_1,0)+ Nvl($w_realizado_2,0) + Nvl($w_realizado_3,0)+ Nvl($w_realizado_4,0) + Nvl($w_realizado_5,0) + Nvl($w_realizado_6,0) + Nvl($w_realizado_7,0) + Nvl($w_realizado_8,0) + Nvl($w_realizado_9,0) + Nvl($w_realizado_10,0) + Nvl($w_realizado_11,0) + Nvl($w_realizado_12,0)).'</td>');
      ShowHTML('             <td align="right"><b>'.number_format(Nvl($w_real_acao_1,0) + Nvl($w_real_acao_2,0) + Nvl($w_real_acao_3,0) + Nvl($w_real_acao_4,0) + Nvl($w_real_acao_5,0) + Nvl($w_real_acao_6,0) + Nvl($w_real_acao_7,0) + Nvl($w_real_acao_8,0) + Nvl($w_real_acao_9,0) + Nvl($w_real_acao_10,0) + Nvl($w_real_acao_11,0) + Nvl($w_real_acao_12,0),2,',','.').'</td>');
      ShowHTML('       </table>');
      ShowHTML('     <tr><td>Percentual de conlusão:<br><b>'.nvl($w_perc_conclusao,0).'%</b></td>');
      ShowHTML('     <tr><td valign="top">Situação atual da meta:<b><br>'.Nvl($w_situacao_atual,'---').'</td>');
      ShowHTML('     <tr><td valign="top">Justificar os motivos em caso de não cumprimento da meta:<b><br>'.Nvl($w_justificativa_inex,'---').'</td>');
      ShowHTML('     <tr><td valign="top">Quais medidas necessárias para o cumprimento da meta:<b><br>'.Nvl($w_outras_medidas,'---').'</td>');
    } else {
      ShowHTML('     <tr><td>Percentual de conlusão:<br><b>'.nvl($w_perc_conclusao,0).'%</b></td>');
      ShowHTML('     <tr><td valign="top" colspan="1">');
      ShowHTML('       <table border=0 width="40%" cellspacing=0>');
      ShowHTML('         <tr><td>&nbsp<td><br><b>Quantitativo inicial</b></td>');
      ShowHTML('             <td title="Em caso de revisão da meta programada, os novos valores devem ser informados, mês a mês, nestes campos."><br><b>Quantitativo revisado</b></td>');
      ShowHTML('             <td title="Em caso de revisão da meta programada, os novos valores devem ser informados, mês a mês, nestes campos."><br><b>Quantitativo realizado</b></td>');
      ShowHTML('             <td><br><b>Financeiro realizado</b></td>');
      ShowHTML('         <tr><td width="4%" align="right"><b>Janeiro:');
      ShowHTML('             <td width="5%" align="right">'.Nvl($w_cron_ini_1,'---').'</td>');
      ShowHTML('             <td width="8%"><INPUT TYPE="TEXT" CLASS="STI" NAME="w_revisado_1" SIZE=10 MAXLENGTH=18 VALUE="'.$w_revisado_1.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td width="8%"><INPUT TYPE="TEXT" CLASS="STI" NAME="w_realizado_1" SIZE=10 MAXLENGTH=18 VALUE="'.$w_realizado_1.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td width="5%" align="right">'.Nvl($w_real_acao_1,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Fevereiro:');
      ShowHTML('             <td align="right">'.Nvl($w_cron_ini_2,'---').'</td>');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_revisado_2" SIZE=10 MAXLENGTH=18 VALUE="'.$w_revisado_2.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_realizado_2" SIZE=10 MAXLENGTH=18 VALUE="'.$w_realizado_2.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td align="right">'.Nvl($w_real_acao_2,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Março:');
      ShowHTML('             <td align="right">'.Nvl($w_cron_ini_3,'---').'</td>');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_revisado_3" SIZE=10 MAXLENGTH=18 VALUE="'.$w_revisado_3.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_realizado_3" SIZE=10 MAXLENGTH=18 VALUE="'.$w_realizado_3.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td align="right">'.Nvl($w_real_acao_3,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Abril:');
      ShowHTML('             <td align="right">'.Nvl($w_cron_ini_4,'---').'</td>');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_revisado_4" SIZE=10 MAXLENGTH=18 VALUE="'.$w_revisado_4.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_realizado_4" SIZE=10 MAXLENGTH=18 VALUE="'.$w_realizado_4.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td align="right">'.Nvl($w_real_acao_4,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Maio:');
      ShowHTML('             <td align="right">'.Nvl($w_cron_ini_5,'---').'</td>');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_revisado_5" SIZE=10 MAXLENGTH=18 VALUE="'.$w_revisado_5.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_realizado_5" SIZE=10 MAXLENGTH=18 VALUE="'.$w_realizado_5.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td align="right">'.Nvl($w_real_acao_5,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Junho:');
      ShowHTML('             <td align="right">'.Nvl($w_cron_ini_6,'---').'</td>');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_revisado_6" SIZE=10 MAXLENGTH=18 VALUE="'.$w_revisado_6.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_realizado_6" SIZE=10 MAXLENGTH=18 VALUE="'.$w_realizado_6.'" '.$w_Disabled.' ></td>');
      ShowHTML('             <td align="right">'.Nvl($w_real_acao_6,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Julho:');
      ShowHTML('             <td align="right">'.Nvl($w_cron_ini_7,'---').'</td>');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_revisado_7" SIZE=10 MAXLENGTH=18 VALUE="'.$w_revisado_7.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_realizado_7" SIZE=10 MAXLENGTH=18 VALUE="'.$w_realizado_7.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td align="right">'.Nvl($w_real_acao_7,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Agosto:');
      ShowHTML('             <td align="right">'.Nvl($w_cron_ini_8,'---').'</td>');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_revisado_8" SIZE=10 MAXLENGTH=18 VALUE="'.$w_revisado_8.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_realizado_8" SIZE=10 MAXLENGTH=18 VALUE="'.$w_realizado_8.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td align="right">'.Nvl($w_real_acao_8,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Setembro:');
      ShowHTML('             <td align="right">'.Nvl($w_cron_ini_9,'---').'</td>');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_revisado_9" SIZE=10 MAXLENGTH=18 VALUE="'.$w_revisado_9.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_realizado_9" SIZE=10 MAXLENGTH=18 VALUE="'.$w_realizado_9.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td align="right">'.Nvl($w_real_acao_9,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Outubro:');
      ShowHTML('             <td align="right">'.Nvl($w_cron_ini_10,'---').'</td>');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_revisado_10" SIZE=10 MAXLENGTH=18 VALUE="'.$w_revisado_10.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_realizado_10" SIZE=10 MAXLENGTH=18 VALUE="'.$w_realizado_10.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td align="right">'.Nvl($w_real_acao_10,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Novembro:');
      ShowHTML('             <td align="right">'.Nvl($w_cron_ini_11,'---').'</td>');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_revisado_11" SIZE=10 MAXLENGTH=18 VALUE="'.$w_revisado_11.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_realizado_11" SIZE=10 MAXLENGTH=18 VALUE="'.$w_realizado_11.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td align="right">'.Nvl($w_real_acao_11,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Dezembro:');
      ShowHTML('             <td align="right">'.Nvl($w_cron_ini_12,'---').'</td>');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_revisado_12" SIZE=10 MAXLENGTH=18 VALUE="'.$w_revisado_12.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_realizado_12" SIZE=10 MAXLENGTH=18 VALUE="'.$w_realizado_12.'" '.$w_Disabled.' ></td>');
      ShowHTML('             <td align="right">'.Nvl($w_real_acao_12,'---').'</td>');
      ShowHTML('       </table>');
      ShowHTML('     <tr><td valign="top"><b><u>S</u>ituação atual da meta:</b><br><textarea '.$w_Disabled.' accesskey="S" name="w_situacao_atual" class="STI" ROWS=5 cols=75 title="Descreva, de maneria sucinta, qual é a situação atual da meta.">'.$w_situacao_atual.'</TEXTAREA></td>');
      ShowHTML('     <tr valign="top">');
      MontaRadioSN('<b>A meta será cumprida?</b>',$w_exequivel,'w_exequivel');
      ShowHTML('     </tr>');
      ShowHTML('     <tr><td valign="top"><b><u>J</u>ustificar os motivos em caso de não cumprimento da meta:</b><br><textarea '.$w_Disabled.' accesskey="J" name="w_justificativa_inex" class="STI" ROWS=5 cols=75 title="Informe os motivos que inviabilizam o cumprimento da meta.">'.$w_justificativa_inex.'</TEXTAREA></td>');
      ShowHTML('     <tr><td valign="top"><b><u>Q</u>uais medidas necessárias para o cumprimento da meta?</b><br><textarea '.$w_Disabled.' accesskey="Q" name="w_outras_medidas" class="STI" ROWS=5 cols=75 title="Descreva quais são as medidas que devem ser adotadas para  que a tendencia de não cumprimento da meta programada possa ser revertida.">'.$w_outras_medidas.'</TEXTAREA></td>');
    } 
    ShowHTML('        <tr><td align="center"><hr>');
    if ($w_tipo!='WORD') {
      if ($O=='A') ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      if ($P1==10) ShowHTML('            <input class="STB" type="button" onClick="window.close();" name="Botao" value="Fechar">');
      else         ShowHTML('            <input class="STB" type="button" onClick="history.back(-1);" name="Botao" value="Voltar">');
    } 
    ShowHTML('            </td>');
    ShowHTML('        </tr>');
    ShowHTML('      </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    if ($w_tipo!='WORD') ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  if ($w_tipo!='WORD') Rodape();
} 
// =========================================================================
// Rotina da programação orçamentária financeira
// -------------------------------------------------------------------------
function Financiamento() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da página  
    $w_obs_financ = $_REQUEST['w_obs_financ'];
  } elseif ($O=='L') {
    // Recupera os dados da ação para verificar o tipo de programação financeira da mesma.
    $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,$SG);
    foreach ($RS as $row) {$RS=$row; break;}
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados do financiamento
    $RS = db_getFinancAcaoPPA_IS::getInstanceOf($dbms,$w_chave,$w_cliente,$w_ano,substr($w_chave_aux,0,4),substr($w_chave_aux,4,4),substr($w_chave_aux,8,4));
    foreach ($RS as $row) {$RS=$row; break;}
    $w_obs_financ = f($RS,'observacao');
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      if ($O=='I') Validate('w_chave_aux','Ação PPA','SELECT','1','1','18','1','1');
      Validate('w_obs_financ','Observações','1','',5,2000,'1','1');
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
    BodyOpen('onLoad=\'document.Form.w_chave_aux.focus()\';');
  } else {
    BodyOpenClean(null);
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    //$RS1 = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,$SG);
    if (Nvl(f($RS,'cd_programa'),'')>'' && Nvl(f($RS,'cd_acao'),'')>'' && Nvl(f($RS,'cd_unidade'),'')>'') {
      ShowHTML('      <tr><td colspan="2">Programa '.f($RS,'cd_programa').' - '.f($RS,'nm_ppa_pai').'</td>');
      ShowHTML('      <tr><td colspan="2">Ação '.f($RS,'cd_unidade').'.'.f($RS,'cd_acao').' - '.f($RS,'nm_ppa').'</td>');
      ShowHTML('      <tr><td colspan="2">&nbsp</td>');
    } 
    if (Nvl(f($RS,'cd_acao'),'')>'') {
      if (f($RS,'cd_tipo_acao')!=3) {
        // Exibe os dados da programação financeira desssa ação
        $RS1 = db_getPPADadoFinanc_IS::getInstanceOf($dbms,f($RS,'cd_acao'),f($RS,'cd_unidade'),$w_ano,$w_cliente,'VALORFONTEACAO');
        if (count($RS1)<=0) {
          ShowHTML('      <tr><td valign="top"><DD><b>Nao existe nenhum valor para esta ação</b></DD></td>');
        } else {
          $i=0;
          foreach($RS1 as $row1) {
            if ($i==0) {
              $w_cor='';
              ShowHTML('      <tr><td valign="top">Fonte: SIGPLAN/MP - PPA 2004-2007</td>');
              ShowHTML('      <tr><td valign="top">Tipo de orçamento: <b>'.f($row1,'nm_orcamento').'</b></td>');
              if (f($RS,'cd_tipo_acao')==1) {
                ShowHTML('      <tr><td valign="top">Realizado até 2004: <b>'.number_format(Nvl(f($RS,'valor_ano_anterior'),0),2,',','.').'</b></td>');
                ShowHTML('      <tr><td valign="top">Justificativa da repercusão financeira sobre o custeio da União: <b>'.Nvl(f($RS,'reperc_financeira'),'---').'</b></td>');
                ShowHTML('      <tr><td valign="top">Valor estimado da repercussão financeira por ano (R$ 1,00): <b>'.number_format(Nvl(f($RS,'valor_reperc_financeira'),0),2,',','.').'</b></td>');
              } 
              ShowHTML('      <tr><td valign="top">Valor por fonte: </td>');
              ShowHTML('      <tr><td align="center" colspan="2">');
              ShowHTML('        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
              ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
              ShowHTML('            <td><b>Fonte</td>');
              ShowHTML('            <td><b>2004*</td>');
              ShowHTML('            <td><b>2005**</td>');
              ShowHTML('            <td><b>2006</td>');
              ShowHTML('            <td><b>2007</td>');
              ShowHTML('            <td><b>2008</td>');
              ShowHTML('            <td><b>Total</td>');
              ShowHTML('          </tr>');
              $i=1;
            }
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            ShowHTML('       <tr bgcolor="'.$w_cor.'" valign="top">');
            ShowHTML('         <td>'.f($row1,'nm_fonte').'</td>');
            ShowHTML('         <td align="center">'.number_format(Nvl(f($row1,'valor_ano_1'),0),2,',','.').'</td>');
            ShowHTML('         <td align="center">'.number_format(Nvl(f($row1,'valor_ano_2'),0),2,',','.').'</td>');
            ShowHTML('         <td align="center">'.number_format(Nvl(f($row1,'valor_ano_3'),0),2,',','.').'</td>');
            ShowHTML('         <td align="center">'.number_format(Nvl(f($row1,'valor_ano_4'),0),2,',','.').'</td>');
            ShowHTML('         <td align="center">'.number_format(Nvl(f($row1,'valor_ano_5'),0),2,',','.').'</td>');
            ShowHTML('         <td align="center">'.number_format(Nvl(f($row1,'valor_total'),0),2,',','.').'</td>');
            ShowHTML('       </tr>');
          } 
          ShowHTML('          </table>');
        } 
        $RS1 = db_getPPADadoFinanc_IS::getInstanceOf($dbms,f($RS,'cd_acao'),f($RS,'cd_unidade'),$w_ano,$w_cliente,'VALORTOTALACAO');
        foreach ($RS1 as $row) {$RS1 = $row; break;}
        ShowHTML('      <tr><td valign="top">Valor total: </td>');
        if (count($RS1)<=0) {
          ShowHTML('      <tr><td valign="top"><DD><b>Nao existe nenhum valor para esta ação</b></DD></td>');
        } else {
          $w_cor='';
          ShowHTML('      <tr><td align="center" colspan="2">');
          ShowHTML('        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
          ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
          ShowHTML('            <td><b>2004*</td>');
          ShowHTML('            <td><b>2005**</td>');
          ShowHTML('            <td><b>2006</td>');
          ShowHTML('            <td><b>2007</td>');
          ShowHTML('            <td><b>2008</td>');
          ShowHTML('            <td><b>Total</td>');
          ShowHTML('          </tr>');
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('       <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('         <td align="center">'.number_format(Nvl(f($RS1,'valor_ano_1'),0),2,',','.').'</td>');
          ShowHTML('         <td align="center">'.number_format(Nvl(f($RS1,'valor_ano_2'),0),2,',','.').'</td>');
          ShowHTML('         <td align="center">'.number_format(Nvl(f($RS1,'valor_ano_3'),0),2,',','.').'</td>');
          ShowHTML('         <td align="center">'.number_format(Nvl(f($RS1,'valor_ano_4'),0),2,',','.').'</td>');
          ShowHTML('         <td align="center">'.number_format(Nvl(f($RS1,'valor_ano_5'),0),2,',','.').'</td>');
          ShowHTML('         <td align="center">'.number_format(Nvl(f($RS1,'valor_total'),0),2,',','.').'</td>');
          ShowHTML('       </tr>');
          ShowHTML('       </table>');
          ShowHTML('          <tr><td valign="top" colspan="2">* Valor Lei Orçamentária Anual - LOA 2004 + Créditos</td>');
          ShowHTML('          <tr><td valign="top" colspan="2">** Valor do Projeto de Lei Orçamentária Anual - PLOA 2005</td>');
        } 
        // Recupera todos os registros para a listagem
        $RS = db_getFinancAcaoPPA_IS::getInstanceOf($dbms,$w_chave,$w_cliente,$w_ano,null,null,null);
        // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
        ShowHTML('<tr><td><font size="2"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
        ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
        ShowHTML('<tr><td align="center" colspan=3>');
        ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('          <td><b>Código</td>');
        ShowHTML('          <td><b>Nome</td>');
        ShowHTML('          <td><b>Operações</td>');
        if (count($RS)<=0) {
          // Se não foram selecionados registros, exibe mensagem
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
        } else {
          $w_cor='';
          // Lista os registros selecionados para listagem
          foreach($RS as $row) {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
            ShowHTML('        <td>'.f($row,'cd_programa').'.'.f($row,'cd_acao').'.'.f($row,'cd_unidade').'</td>');
            ShowHTML('        <td>'.f($row,'descricao_acao').'</td>');
            ShowHTML('        <td align="top" nowrap>');
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_acao_ppa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_acao_ppa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
            ShowHTML('        </td>');
            ShowHTML('      </tr>');
          } 
        } 
      } else {
        ShowHTML('      <tr><td valign="top"><DD><b>Nao existe progração financeira para esta açao, pois é uma ação nao orçamentária.</b></DD></td>');
      } 
    } else {
      // Recupera todos os registros para a listagem
      $RS = db_getFinancAcaoPPA_IS::getInstanceOf($dbms,$w_chave,$w_cliente,$w_ano,null,null,null);
      // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
      ShowHTML('<tr><td><font size="2"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
      ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
      ShowHTML('<tr><td align="center" colspan=3>');
      ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>Código</td>');
      ShowHTML('          <td><b>Nome</td>');
      ShowHTML('          <td><b>Operações</td>');
      ShowHTML('        </tr>');
      if (count($RS)<=0) {
        // Se não foram selecionados registros, exibe mensagem
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
      } else {
        $w_cor='';
        // Lista os registros selecionados para listagem
        foreach($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('        <td>'.f($row,'cd_unidade').'.'.f($row,'cd_programa').'.'.f($row,'cd_acao').'</td>');
          ShowHTML('        <td>'.f($row,'descricao_acao').'</td>');
          ShowHTML('        <td align="top" nowrap>');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_acao_ppa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_acao_ppa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
          ShowHTML('        </td>');
          ShowHTML('      </tr>');
        } 
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    $RS1 = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,$SG);
    foreach($RS1 as $row){$RS1=$row; break;}
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    if (Nvl(f($RS1,'cd_acao'),'')>'') {
      ShowHTML('      <tr><td valign="top"><b>Programa PPA: </b><br>'.f($RS1,'cd_ppa_pai').' - '.f($RS1,'nm_ppa_pai').' </b>');
      ShowHTML('      <tr><td valign="top"><b>Ação PPA: </b><br>('.f($RS1,'cd_unidade').'.'.f($RS1,'cd_acao').') - '.f($RS1,'nm_ppa').' </b>');
    } 
    if (Nvl(f($RS1,'sq_isprojeto'),'')>'') ShowHTML('      <tr><td valign="top"><b>Programa interno: </b><br>'.f($RS1,'nm_pri').' </b>');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    if ($O=='I') {
      SelecaoAcaoPPA('Ação <u>P</u>PA:','P','Selecionar, quando for o caso, outra ação do PPA que contribua para o financiamento da ação que está sendo cadastrada.',$w_cliente,$w_ano,f($RS1,'cd_programa'),f($RS1,'cd_acao'),null,f($RS1,'cd_unidade'),'w_chave_aux','FINANCIAMENTO',null,$w_chave,$w_menu,null,null);
    } else {
      SelecaoAcaoPPA('Ação <u>P</u>PA:','P','Selecionar, quando for o caso, outra ação do PPA que contribua para o financiamento da ação que está sendo cadastrada.',$w_cliente,$w_ano,substr($w_chave_aux,0,4),substr($w_chave_aux,4,4),substr($w_chave_aux,8,4),substr($w_chave_aux,12,5),'w_chave_aux',null,'disabled',null,$w_menu,null,null);
      ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    } 
    ShowHTML('      <tr><td valign="top"><b>Obse<u>r</u>vações:</b><br><textarea '.$w_Disabled.' accesskey="R" name="w_obs_financ" class="STI" ROWS=5 cols=75 title="Informar fatos ou situações que sejam relevantes para uma melhor compreensão do financiamento da ação.">'.$w_obs_financ.'</TEXTAREA></td>');
    ShowHTML('      <tr><td align="center" colspan=4><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } elseif ($O=='I') {
      ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
    } elseif ($O=='A') {
      ShowHTML('            <input class="STB" type="submit" name="Botao" value="Alterar">');
    }  
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
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
  Rodape();
} 
// =========================================================================
// Rotina de restrições da ação
// -------------------------------------------------------------------------
function Restricoes() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave          = $_REQUEST['w_chave'];
  $w_chave_aux      = $_REQUEST['w_chave_aux'];
  $w_sq_isprojeto   = $_REQUEST['w_sq_isprojeto'];
  $w_tipo           = $_REQUEST['w_tipo'];
  $w_cd_subacao     = $_REQUEST['w_cd_subacao'];
  $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,$SG);
  foreach($RS as $row){$RS=$row; break;}
  $w_cd_programa    = f($RS,'cd_programa');
  $w_cd_acao        = f($RS,'cd_acao');
  $w_cd_subacao     = f($RS,'cd_subacao');
  $w_cd_unidade     = f($RS,'cd_unidade');
  $w_sq_isprojeto   = f($RS,'sq_isprojeto');
  $w_ds_acao        = f($RS,'nm_ppa');
  $w_ds_programa    = f($RS,'nm_ppa_pai');
  if (Nvl(f($RS,'tit_exec'),0)      == $w_usuario || 
      Nvl(f($RS,'subst_exec'),0)    == $w_usuario || 
      Nvl(f($RS,'titular'),0)       == $w_usuario || 
      Nvl(f($RS,'substituto'),0)    == $w_usuario || 
      Nvl(f($RS,'executor'),0)      == $w_usuario || 
     (Nvl(f($RS,'cadastrador'),0)   == $w_usuario && $P1<2) || 
      Nvl(f($RS,'solicitante'),0)   == $w_usuario) {
    if (Nvl(f($RS,'inicio_real'),'')>'') $w_acesso=0; else $w_acesso=1;
  } else { $w_acesso=0; } 
  $w_cabecalho='      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font  size="2"><b>Ação: '.f($RS,'titulo').' ('.f($RS,'cd_unidade').'.'.f($RS,'cd_programa').'.'.f($RS,'cd_acao').')</td></tr>';
  if ($w_troca>'') {
    // Se for recarga da página
    $w_cd_tipo_restricao        = $_REQUEST['w_cd_tipo_restricao'];
    $w_cd_tipo_inclusao         = $_REQUEST['w_cd_tipo_inclusao'];
    $w_cd_competecia            = $_REQUEST['w_cd_competecia'];
    $w_inclusao                 = $_REQUEST['w_inclusao'];
    $w_descricao                = $_REQUEST['w_descricao'];
    $w_providencia              = $_REQUEST['w_providencia'];
    $w_superacao                = $_REQUEST['w_superacao'];
    $w_relatorio                = $_REQUEST['w_relatorio'];
    $w_tempo_habil              = $_REQUEST['w_tempo_habil'];
    $w_observacao_controle      = $_REQUEST['w_observacao_controle'];
    $w_observacao_monitor       = $_REQUEST['w_observacao_monitor'];
    $w_cd_programa              = $_REQUEST['w_cd_programa'];
    $w_cd_acao                  = $_REQUEST['w_cd_acao'];
    $w_cd_unidade               = $_REQUEST['w_cd_unidade'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getRestricao_IS::getInstanceOf($dbms,$SG,$w_chave,null);
    $RS = SortArray($RS,'inclusao','desc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    $RS = db_getRestricao_IS::getInstanceOf($dbms,$SG,$w_chave,$w_chave_aux);
    foreach ($RS as $row) {$RS=$row; break; }
    $w_cd_subacao           = f($RS,'cd_subacao');
    $w_cd_tipo_restricao    = f($RS,'cd_tipo_restricao');
    $w_cd_tipo_inclusao     = f($RS,'cd_tipo_inclusao');
    $w_cd_competencia       = f($RS,'cd_competencia');
    $w_inclusao             = FormataDataEdicao(f($RS,'inclusao'));
    $w_descricao            = f($RS,'descricao');
    $w_providencia          = f($RS,'providencia');
    $w_superacao            = FormataDataEdicao(f($RS,'superacao'));
    $w_relatorio            = f($RS,'relatorio');
    $w_tempo_habil          = f($RS,'tempo_habil');
    $w_observacao_controle  = f($RS,'observacao_controle');
    $w_observacao_monitor   = f($RS,'observacao_monitor');
    $w_nm_tipo_restricao    = f($RS,'nm_tp_restricao');
  } 
  if ($w_tipo=='WORD') HeaderWord(); else Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    formatadata();
    SaltaCampo();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      $RS = db_getPPALocalizador_IS::getInstanceOf($dbms,$w_cliente,$w_ano,$w_cd_programa,$w_cd_acao,$w_cd_unidade,null);
      if (count($RS)>1) Validate('w_cd_localizador','Localizador','SELECT','1','1','18','1','1'); 
      Validate('w_cd_tipo_restricao','Tipo da restrição','SELECT','1','1','18','','1');
      Validate('w_descricao','Descrição','','1','3','4000','1','1');
      Validate('w_providencia','Providência','','','3','4000','1','1');
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
    BodyOpen('onLoad=\'document.Form.w_cd_tipo_restricao.focus()\';');
  } else {
    BodyOpen(null);
  } 
  if ($O=='V') {
    ShowHTML('<div align=center><center>');
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
    ShowHTML($w_cabecalho);
  } else {
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center>');
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  } 
  if ($O=='L') {
    if (Nvl($w_cd_programa,'')>'' && Nvl($w_cd_acao,'')>'' && Nvl($w_cd_unidade,'')>'') {
      ShowHTML('      <tr><td colspan="2">Programa '.$w_cd_programa.' - '.$w_ds_programa.'</td>');
      ShowHTML('      <tr><td colspan="2">Ação '.$w_cd_unidade.'.'.$w_cd_acao.' - '.$w_ds_acao.'</td>');
    } 
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    if ($w_acesso==1) ShowHTML('<tr><td><font size="2"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    else              ShowHTML('<tr><td><font size="2">&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Descrição</td>');
    ShowHTML('          <td><b>Tipo restricao</td>');
    ShowHTML('          <td><b>Inclusão</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=9 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td><A class="HL" HREF="#" onClick="window.open(\''.montaURL_JS($w_dir,$w_pagina.$par.'&R='.$w_pagina.$par.'&O=V&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_restricao').'&P1=10&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\',\'Restricao\',\'width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no\'); return false;" title="Clique para exibir os dados!">'.f($row,'descricao').'</A></td>');
        ShowHTML('        <td>'.f($row,'nm_tp_restricao').'</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'inclusao')).'</td>');
        ShowHTML('        <td align="top" nowrap>');
        if ($w_acesso==1) {
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_restricao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_restricao').'&w_descricao='.f($row,'descricao').'&w_providencia='.f($row,'providencia').'&w_cd_tipo_restricao='.f($row,'cd_tipo_restricao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A>&nbsp');
        } else {
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=V&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_restricao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Exibir</A>&nbsp');
        } 
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAE',$O)===false)) {
    if (!(strpos('E',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('<INPUT type="hidden" name="w_relatorio" value="S">');
    ShowHTML('<INPUT type="hidden" name="w_tempo_habil" value="N">');
    ShowHTML('<INPUT type="hidden" name="w_sq_isprojeto" value="'.$w_sq_isprojeto.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('  <table width="97%" border="0">');
    $RS = db_getPPALocalizador_IS::getInstanceOf($dbms,$w_cliente,$w_ano,$w_cd_programa,$w_cd_acao,$w_cd_unidade,null);
    if (count($RS)>1) {
      ShowHTML('    <tr valign="top" >');
      SelecaoLocalizador_IS('<U>L</U>ocalizador:','L','Selecione o localizador da restrição',$w_cd_subacao,$w_cd_programa,$w_cd_acao,$w_cd_unidade,'w_cd_subacao',null,null);
    } else {
      ShowHTML('<INPUT type="hidden" name="w_cd_subacao" value="'.$w_cd_subacao.'">');
    } 
    ShowHTML('    <tr valign="top" >');
    SelecaoTPRestricao_IS('<U>T</U>ipo de restrição:','T','Selecione o tipo de restrição',$w_cd_tipo_restricao,'w_cd_tipo_restricao',null,null);
    ShowHTML('    <tr><td colspan=2><b><u>D</u>escrição:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_descricao" class="STI" ROWS=5 cols=75 title="Descreva os fatores que podem prejudicar o andamento da ação. As restrições podem ser administrativas, ambientais, de auditoria, de licitações, financeiras, institucuionais, políticas, tecnológicas, judiciais, etc. Cada tipo de restrição deve ser inserido separadamente.">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('    <tr><td colspan=2><b><u>P</u>rovidência:</b><br><textarea '.$w_Disabled.' accesskey="P" name="w_providencia" class="STI" ROWS=5 cols=75 title="Informe as providências que devem ser tomadas para a superação da restrição.">'.$w_providencia.'</TEXTAREA></td>');
    ShowHTML('    <tr><td align="center" colspan=2><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      else         ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
    } 
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif ($O=='V') {
    if ($w_tipo!='WORD' && $O=='V') {
      ShowHTML('<tr><td align="right"colspan="2">');
      ShowHTML('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      ShowHTML('&nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&O=V&w_chave='.$w_chave.'&w_chave_aux='.$w_chave_aux.'&w_cd_programa='.$w_cd_programa.'&w_tipo=WORD&P1=10&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
      ShowHTML('</td></tr>');
    } 
    ShowHTML('    <tr><td align="center" bgcolor="#FAEBD7" colspan="2">');
    ShowHTML('      <table border=1 width="100%">');
    ShowHTML('        <tr><td valign="top" colspan="2">');
    ShowHTML('          <TABLE border=0 WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    $RS = db_getPPALocalizador_IS::getInstanceOf($dbms,$w_cliente,$w_ano,$w_cd_programa,$w_cd_acao,$w_cd_unidade,null);
    if (count($RS)>1) {
      $RS = db_getPPALocalizador_IS::getInstanceOf($dbms,$w_cliente,$w_ano,$w_cd_programa,$w_cd_acao,$w_cd_unidade,$w_cd_subacao);
      ShowHTML('            <tr><td colspan="2">Localizador:<b><br><font size=2>'.Nvl(f($RS,'nome'),'---').'</font></td></tr>');
    } 
    ShowHTML('            <tr><td colspan="2">Descrição da restrição:<b><br><font size=2>'.Nvl($w_descricao,'---').'</font></td></tr>');
    ShowHTML('            <tr><td>Tipo de restrição<b><br>'.Nvl($w_nm_tipo_restricao,'---').'</td>');
    ShowHTML('            <tr><td>Data inclusão:<b><br>'.Nvl(FormataDataEdicao($w_inclusao),'---').'</td>');
    ShowHTML('          </TABLE>');
    ShowHTML('      </table>');
    ShowHTML('    <tr bgcolor="'.$conTrBgColor.'"><td align="center" colspan="2">');
    ShowHTML('      <table width="100%" border="0">');
    ShowHTML('     <tr><td valign="top"><b>Providência:</b><br>'.Nvl($w_providencia,'---').'</td>');
    ShowHTML('           </table></td></tr>');
    ShowHTML('        <tr><td align="center"><hr>');
    if ($w_tipo!='WORD') {
      if ($O=='A') ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      if ($P1==10)  ShowHTML('            <input class="STB" type="button" onClick="window.close();" name="Botao" value="Fechar">');
      else          ShowHTML('            <input class="STB" type="button" onClick="history.back(-1);" name="Botao" value="Voltar">');
    } 
    ShowHTML('            </td>');
    ShowHTML('        </tr>');
    ShowHTML('      </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de interessados
// -------------------------------------------------------------------------
function Interessados() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_tipo_visao   = $_REQUEST['w_tipo_visao'];
    $w_envia_email  = $_REQUEST['w_envia_email'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getSolicInter::getInstanceOf($dbms,$w_chave,null,'LISTA');
    $RS = SortArray($RS,'nome_resumido','asc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados do endereço informado
    $RS = db_getSolicInter::getInstanceOf($dbms,$w_chave,$w_chave_aux,'REGISTRO');
    foreach ($RS as $row) {$RS=$row; break;}
    $w_nome         = f($RS,'nome_resumido');
    $w_tipo_visao   = f($RS,'tipo_visao');
    $w_envia_email  = f($RS,'envia_email');
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    formatadata();
    SaltaCampo();
    FormataCEP();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_chave_aux','Pessoa','HIDDEN','1','1','10','','1');
      Validate('w_tipo_visao','Tipo de visão','SELECT','1','1','10','','1');
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
    BodyOpen('onLoad=\'document.Form.w_chave_aux.focus()\';');
  } else {
    BodyOpen(null);
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('      <tr><td colspan=3>Usuários que devem receber emails dos encaminhamentos desta ação.</td></tr>');
    ShowHTML('      <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    if ($P1!=4) {
      ShowHTML('<tr><td><font size="2"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    } else {
      $RS1 = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,'ISACVISUAL');
      foreach ($RS1 as $row1){$RS1=$row1; break;}
      ShowHTML('<tr><td colspan=3 align="center" bgcolor="#FAEBD7"><table border=1 width="100%"><tr><td>');
      ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('        <tr valign="top">');
      if (f($RS1,'cd_acao')>'') ShowHTML('          <td><b>Ação PPA: </b><br>'.f($RS1,'cd_unidade').'.'.f($RS1,'cd_ppa_pai').'.'.f($RS1,'cd_acao').' - '.f($RS1,'nm_ppa').'</b>');
      if (f($RS1,'sq_isprojeto')>'') ShowHTML('        <td><b>Programa interno: </b><br>'.f($RS1,'nm_pri').' </b>');
      ShowHTML('    </TABLE>');
      ShowHTML('</table>');
      ShowHTML('<tr><td colspan=3>&nbsp;');
      ShowHTML('<tr><td colspan=2><font size="2"><a accesskey="F" class="SS" href="javascript:window.close();"><u>F</u>echar</a>&nbsp;');
    } 
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Pessoa</td>');
    ShowHTML('          <td><b>Envia e-mail</td>');
    if ($P1!=4) ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.ExibePessoa('../',$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'nome').' ('.f($row,'lotacao').')').'</td>');
        ShowHTML('        <td align="center">'.str_replace('N','Não',str_replace('S','Sim',f($row,'envia_email'))).'</td>');
        if ($P1!=4) {
          ShowHTML('        <td align="top" nowrap>');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_pessoa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A>&nbsp');
          ShowHTML('        </td>');
        } 
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_tipo_visao" value="0">');
    ShowHTML('<INPUT type="hidden" name="w_envia_email" value="S">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    if ($O=='I') {
      SelecaoPessoa('<u>P</u>essoa:','N','Selecione a pessoa que deve receber e-mails com informações sobre a ação.',$w_chave_aux,$w_chave,'w_chave_aux','INTERES');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
      ShowHTML('      <tr><td valign="top"><b>Pessoa:</b><br>'.$w_nome.'</td>');
    } 
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" colspan=4><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      else         ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
    } 
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
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
  Rodape();
} 
// ------------------------------------------------------------------------- 
// Rotina de anexos 
// ------------------------------------------------------------------------- 
function Anexos() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da página 
    $w_nome         = $_REQUEST['w_nome'];
    $w_descricao    = $_REQUEST['w_descricao'];
    $w_caminho      = $_REQUEST['w_caminho'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem 
    $RS = db_getSolicAnexo::getInstanceOf($dbms,$w_chave,null,$w_cliente);
    $RS = SortArray($RS,'nome','asc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados do endereço informado 
    $RS = db_getSolicAnexo::getInstanceOf($dbms,$w_chave,$w_chave_aux,$w_cliente);
    foreach($RS as $row) {$RS=$row; break;}
    $w_nome         = f($RS,'nome');
    $w_descricao    = f($RS,'descricao');
    $w_caminho      = f($RS,'chave_aux');
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Título','1','1','1','255','1','1'); 
      Validate('w_descricao','Descrição','1','1','1','1000','1','1');
      if ($O=='I') Validate('w_caminho','Arquivo','','1','5','255','1','1'); 
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
   BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  } elseif ($O=='A') {
    BodyOpen('onLoad=\'document.Form.w_descricao.focus()\';');
  } else {
    BodyOpen(null);
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    $RS1 = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,$SG);
    if (Nvl(f($RS1,'cd_programa'),'')>'' && Nvl(f($RS1,'cd_acao'),'')>'' && Nvl(f($RS1,'cd_unidade'),'')>'') {
      ShowHTML('      <tr><td colspan="2">Programa '.f($RS1,'cd_programa').' - '.f($RS1,'nm_ppa_pai').'</td>');
      ShowHTML('      <tr><td colspan="2">Ação '.f($RS1,'cd_unidade').'.'.f($RS1,'cd_acao').' - '.f($RS1,'nm_ppa').'</td>');
    } 
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem 
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Título</td>');
    ShowHTML('          <td><b>Descrição</td>');
    ShowHTML('          <td><b>Tipo</td>');
    ShowHTML('          <td><b>KB</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem 
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem 
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>');
        ShowHTML('        <td>'.Nvl(f($row,'descricao'),'---').'</td>');
        ShowHTML('        <td>'.f($row,'tipo').'</td>');
        ShowHTML('        <td align="right">'.round((f($row,'tamanho')/1024),1).'&nbsp;</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave_aux').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave_aux').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG='.$SG.'&O='.$O.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
    ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
    ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
    ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
    ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
    ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
    ShowHTML('<INPUT type="hidden" name="R" value="'.$R.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_atual" value="'.$w_caminho.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    if ($O=='I' || $O=='A') {
      $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
      ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b><font color="#BC3131">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de '.(f($RS,'upload_maximo')/1024).' KBytes</b>.</font></td>');
      ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
    } 
    ShowHTML('      <tr><td><b><u>T</u>ítulo:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_nome" class="STI" SIZE="75" MAXLENGTH="255" VALUE="'.$w_nome.'" title="Informe o tíulo do arquivo."></td>');
    ShowHTML('      <tr><td><b><u>D</u>escrição:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_descricao" class="STI" ROWS=5 cols=65 title="Descreva o conteúdo do arquivo.">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OBRIGATÓRIO. Clique no botão ao lado para localizar o arquivo. Ele será transferido automaticamente para o servidor.">');
    if ($w_caminho>'') ShowHTML('              <b>'.LinkArquivo('SS',$w_cliente,$w_caminho,'_blank','Clique para exibir o arquivo atual.','Exibir',null).'</b>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir" onClick="return confirm(\'Confirma a exclusão do registro?\');">');
    } else {
      if ($O=='I') ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      else         ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
    } 
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
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
  Rodape();
} 
// =========================================================================
// Rotina de visualização
// -------------------------------------------------------------------------
function VisualNovo() {
  extract($GLOBALS);
  $w_chave  =$_REQUEST['w_chave'];
  $w_tipo   =strtoupper(trim($_REQUEST['w_tipo']));
  // Recupera o logo do cliente a ser usado nas listagens
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  if (f($RS,'logo')>'') $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
  if ($w_tipo=='WORD') HeaderWord(); else Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Visualização de Ação</TITLE>');
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_tipo!='WORD') BodyOpenClean(null);
  ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
  if ($P1==1) {
    ShowHTML('Iniciativas Prioritárias do Governo <BR> Relatório Geral por Ação');
  } elseif ($P1==2) {
    ShowHTML('Plano Plurianual 2004 - 2007 <BR> Relatório Geral por Ação');
  } else {
    ShowHTML('Visualização de Ação');
  } 
  ShowHTML('</FONT><TR><TD ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
  if ($w_tipo!='WORD') {
    ShowHTML('&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
    ShowHTML('&nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.$w_chave.'&w_tipo=word&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4=1&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');    
  } 
  ShowHTML('</TD></TR>');
  ShowHTML('</FONT></B></TD></TR></TABLE>');
  ShowHTML('<HR>');
  if ($w_tipo>'' && $w_tipo!='WORD') ShowHTML('<center><B>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></center>');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML(VisualAcao($w_chave,'L',$w_usuario,$P1,$P4));
  if ($w_tipo>'' && $w_tipo!='WORD') ShowHTML('<center><B>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></center>');
  if ($w_tipo!='WORD') Rodape();
} 
// =========================================================================
// Rotina de visualização do novo layout de relatórios
// -------------------------------------------------------------------------
function Visual() {
  extract($GLOBALS);
  $w_chave  = $_REQUEST['w_chave'];
  $w_tipo   = strtoupper(trim($_REQUEST['w_tipo']));
  // Recupera o logo do cliente a ser usado nas listagens
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  if (f($RS,'logo')>'') $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
  if ($w_tipo=='WORD') HeaderWord(); else Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Ações - Exercício '.$w_ano.'</TITLE>');
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_tipo!='WORD') BodyOpenClean(null);
  ShowHTML('<div align="center">');
  ShowHTML('<table width="95%" border="0" cellspacing="3">');
  ShowHTML('<tr><td colspan="2">');
  ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><DIV ALIGN="LEFT"><IMG src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"></DIV></TD>');
  ShowHTML('<TD><DIV ALIGN="RIGHT"><FONT SIZE=4 COLOR="#000000"><B>');
  if ($P1==1 || $P1==2) ShowHTML('Ficha Resumida da Ação <br> Exercício '.$w_ano);
  else                  ShowHTML('Ações PPA <br> Exercício '.$w_ano);
  ShowHTML('</B></FONT></DIV></TD></TR>');
  ShowHTML('</TABLE></TD></TR>');
  if ($w_tipo>'' && $w_tipo!='WORD') {
    ShowHTML('<tr><td colspan="2">');
    ShowHTML('<div align="center"><b>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></div>');
    ShowHTML('</td</tr>');
  }
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML(VisualAcao($w_chave,'L',$w_usuario,$P1,$P4,'sim','sim','sim','sim','sim','sim','sim','sim','sim','sim','sim','sim'));
  if ($w_tipo>'' && $w_tipo!='WORD') {
    ShowHTML('<tr><td colspan="2">');
    ShowHTML('<div align="center"><b>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></div>');
    ShowHTML('</td</tr>');    
  }
  ShowHTML('</DIV>');
  ShowHTML('</BODY>');
  ShowHTML('</HTML>');
} 
// =========================================================================
// Rotina de exclusão
// -------------------------------------------------------------------------
function Excluir() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_observacao=$_REQUEST['w_observacao'];
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="300; URL=../'.MontaURL('MESA').'">');
  if (!(strpos('E',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    if ($P1!=1) {
      // Se não for encaminhamento
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
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML(VisualAcao($w_chave,'V',$w_usuario,$P1,$P4,'sim','sim','sim','sim','sim','sim','sim','sim','sim','sim'));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'ISACGERAL',$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,'ISACGERAL');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Excluir">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
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
// Rotina de tramitação
// -------------------------------------------------------------------------
function Encaminhamento() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_tipo       = Nvl($_REQUEST['w_tipo'],'');
  if ($w_troca>'') {
    // Se for recarga da página
    $w_tramite      = $_REQUEST['w_tramite'];
    $w_destinatario = $_REQUEST['w_destinatario'];
    $w_novo_tramite = $_REQUEST['w_novo_tramite'];
    $w_despacho     = $_REQUEST['w_despacho'];
  } else {
    $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,'ISACGERAL');
    foreach ($RS as $row){$RS=$row; break;}
    $w_tramite      = f($RS,'sq_siw_tramite');
    $w_novo_tramite = f($RS,'sq_siw_tramite');
  } 
  // Recupera a sigla do trâmite desejado, para verificar a lista de possíveis destinatários.
  $RS = db_getTramiteData::getInstanceOf($dbms,$w_novo_tramite);
  $w_sg_tramite =   f($RS,'sigla');
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="300; URL=../'.MontaURL('MESA').'">');
  if (!(strpos('V',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_destinatario','Destinatário','HIDDEN','1','1','10','','1');
    Validate('w_despacho','Despacho','','1','1','2000','1','1');
    Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    if ($P1!=1 || ($P1==1 && $w_tipo=='Volta')) {
      // Se não for encaminhamento e nem o sub-menu do cadastramento
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
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align="center">');
  ShowHTML('<table width="95%" border="0" cellspacing="3">');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML(VisualAcao($w_chave,'V',$w_usuario,$P1,$P4,'','','','','','','','','','','',''));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'ISACENVIO',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite.'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  if ($P1!=1) {
    // Se não for cadastramento\
    SelecaoFase('<u>F</u>ase da ação:','F','Se deseja alterar a fase atual da ação, selecione a fase para a qual deseja enviá-la.',$w_novo_tramite,$w_menu,'w_novo_tramite',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
    // Se for envio para o cadastramento, exibe apenas as pessoas autorizadas a fazê-lo.
    if ($w_sg_tramite=='CI') SelecaoSolicResp('<u>D</u>estinatário:','D','Selecione, na relação, um destinatário para a ação.',$w_destinatario,$w_chave,$w_novo_tramite,$w_novo_tramite,'w_destinatario','CADASTRAMENTO');
    else                     SelecaoPessoa('<u>D</u>estinatário:','D','Selecione, na relação, um destinatário para a ação.',$w_destinatario,null,'w_destinatario','USUARIOS');
  } else {
    SelecaoFase('<u>F</u>ase da ação:','F','Se deseja alterar a fase atual da ação, selecione a fase para a qual deseja enviá-la.',$w_novo_tramite,$w_menu,'w_novo_tramite',null,null);
    SelecaoPessoa('<u>D</u>estinatário:','D','Selecione, na relação, um destinatário para a ação.',$w_destinatario,null,'w_destinatario','USUARIOS');
  } 
  ShowHTML('    <tr><td valign="top" colspan=2><b>D<u>e</u>spacho:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Informe o que o destinatário deve fazer quando receber a ação.">'.$w_despacho.'</TEXTAREA></td>');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
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
// Rotina de anotação
// -------------------------------------------------------------------------
function Anotar() {
  extract($GLOBALS);
  $w_chave=$_REQUEST['w_chave'];
  $w_chave_aux=$_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_observacao=$_REQUEST['w_observacao'];
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="300; URL=../'.MontaURL('MESA').'">');
  if (!(strpos('V',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_observacao','Anotação','','1','1','2000','1','1');
    Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    if ($P1!=1) {
      // Se não for encaminhamento
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
    BodyOpen('onLoad=\'document.Form.w_observacao.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align="center">');
  ShowHTML('<table width="95%" border="0" cellspacing="3">');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML(VisualAcao($w_chave,'V',$w_usuario,$P1,$P4,'','','','','','','','','','','',''));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'ISACENVIO',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,'ISACGERAL');
  foreach ($RS as $row) {$RS=$row; break;}
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  ShowHTML('    <tr><td valign="top"><b>A<u>n</u>otação:</b><br><textarea '.$w_Disabled.' accesskey="N" name="w_observacao" class="STI" ROWS=5 cols=75 title="Redija a anotação desejada.">'.$w_observacao.'</TEXTAREA></td>');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Gravar">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
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
// Rotina de conclusão
// -------------------------------------------------------------------------
function Concluir() {
  extract($GLOBALS);
  $w_chave=$_REQUEST['w_chave'];
  $w_chave_aux=$_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_inicio_real=$_REQUEST['w_inicio_real'];
    $w_fim_real=$_REQUEST['w_fim_real'];
    $w_concluida=$_REQUEST['w_concluida'];
    $w_data_conclusao=$_REQUEST['w_data_conclusao'];
    $w_nota_conclusao=$_REQUEST['w_nota_conclusao'];
    $w_custo_real=$_REQUEST['w_custo_real'];
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="300; URL=../'.MontaURL('MESA').'">');
  if (!(strpos('V',$O)===false)) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    FormataDataHora();
    FormataValor();
    ValidateOpen('Validacao');
    Validate('w_inicio_real','Início da execução','DATA',1,10,10,'','0123456789/');
    Validate('w_fim_real','Término da execução','DATA',1,10,10,'','0123456789/');
    CompData('w_inicio_real','Início da execução','<=','w_fim_real','Término da execução');
    CompData('w_fim_real','Término da execução','<=',FormataDataEdicao(time()),'data atual');
    Validate('w_custo_real','Recurso executado','VALOR','1',4,18,'','0123456789.,');
    Validate('w_nota_conclusao','Nota de conclusão','','1','1','2000','1','1');
    Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    if ($P1!=1) {
      // Se não for encaminhamento
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
    BodyOpen('onLoad=\'document.Form.w_inicio_real.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align="center">');
  ShowHTML('<table width="95%" border="0" cellspacing="3">');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML(VisualAcao($w_chave,'V',$w_usuario,$P1,$P4,'','','','','','','','','','','',''));
  ShowHTML('<HR>');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
  ShowHTML('          <tr>');
  // Verifica se a ação tem etapas em aberto e avisa o usuário caso isso ocorra.
  $RS = db_getSolicMeta_IS::getInstanceOf($dbms,$w_cliente,$w_chave,null,'LISTA',null,null,null,null,null,null,null,null,null);
  $w_cont_m = 0;
  foreach($RS as $row) {
    if (f($RS,'perc_conclusao')!=100) $w_cont_m=$w_cont_m+1;
  } 
  $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ISTCAD');
  $RS = db_getSolicList_IS::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,'ISTCAD',5,
          null,null,null,null,null,null,
          null,null,null,null,
          null,null,null,null,null,null,null,
          null,null,null,null,$w_chave,null,null,null,null,null,$w_ano);
  $RS = SortArray($RS,'ordem','asc','fim','asc','prioridade','asc');
  $w_cont_t=0;
  foreach($RS as $row) {
    if (f($RS,'concluida')!='S') $w_cont_t=$w_cont_t+1;
  } 
  if ($w_cont_m>0 || $w_cont_t>0) {
    ScriptOpen('JavaScript');
    $w_html=$w_html.'alert(\'';
    if ($w_cont_m>0) $w_html=$w_html.'ATENÇÃO: esta ação possui '.$w_cont_m.' meta(s) com percentual de conclusão abaixo de 100%!\n\n';
    if ($w_cont_t>0) $w_html=$w_html.'ATENÇÃO: esta ação possui '.$w_cont_t.' tarefa(s) não concluída(s)!\n\n';
    $w_html=$w_html.'Ainda assim você poderá concluir esta ação.\');';
    ShowHTML($w_html);
    ScriptClose();
  } 
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'ISACCONC',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_concluida" value="S">');
  $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,'ISACGERAL');
  foreach($RS as $row){$RS=$row;break;}
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  if (Nvl(f($RS,'cd_acao'),'')>'') {
    ShowHTML('              <td valign="top"><b>Iní<u>c</u>io da execução:</b><br><input readonly '.$w_Disabled.' accesskey="C" type="text" name="w_inicio_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_inicio_real,'01/01/'.$w_ano).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de início da execução da ação.(Usar formato dd/mm/aaaa)"></td>');
    ShowHTML('              <td valign="top"><b><u>T</u>érmino da execução:</b><br><input readonly '.$w_Disabled.' accesskey="T" type="text" name="w_fim_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_fim_real,'31/12/'.$w_ano).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de término da execução da ação.(Usar formato dd/mm/aaaa)"></td>');
  } else {
    ShowHTML('              <td valign="top"><b>Iní<u>c</u>io da execução:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_inicio_real,'01/01/'.$w_ano).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de início da execução da ação.(Usar formato dd/mm/aaaa)"></td>');
    ShowHTML('              <td valign="top"><b><u>T</u>érmino da execução:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_fim_real,'31/12/'.$w_ano).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de término da execução da ação.(Usar formato dd/mm/aaaa)"></td>');
  } 
  ShowHTML('              <td valign="top"><b><u>R</u>ecurso executado:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_custo_real" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_custo_real.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor que foi efetivamente gasto com a execução da ação."></td>');
  ShowHTML('          </table>');
  ShowHTML('    <tr><td valign="top"><b>Nota d<u>e</u> conclusão:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_nota_conclusao" class="STI" ROWS=5 cols=75 title="Insira informações relevantes sobre o encerramento do exercício.">'.$w_nota_conclusao.'</TEXTAREA></td>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Concluir">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
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
// Gera uma linha de apresentação da tabela de etapas
// -------------------------------------------------------------------------
function Metalinha($l_chave,$l_chave_aux,$l_titulo,$l_resp,$l_setor,$l_inicio,$l_fim,$l_perc,$l_word,$l_destaque,$l_oper,$l_tipo,$l_loa) {
  extract($GLOBALS);
  global $w_cor;
  if ($l_loa>'') $l_loa='Sim'; else $l_loa='Não';
  $l_row='';
  $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
  $l_html.= chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top">';
  $l_html.= chr(13).'        <td nowrap '.$l_row.'>';
  if ($l_fim < time() && $l_perc<100)
    $l_html.= chr(13).'           <img src="'.$conImgAtraso.'" border=0 width=15 height=15 align="center">';
  elseif ($l_perc<100)
    $l_html.= chr(13).'           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">';
  else
    $l_html.= chr(13).'           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">';
  if (Nvl($l_word,0)==1) $l_html.=chr(13).'        <td>'.$l_destaque.$l_titulo.'</b>';
  else                   $l_html.=chr(13).'<A class="HL" HREF="#" onClick="window.open(\''.montaURL_JS($w_dir,'acao.php?par=AtualizaMeta&O=V&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&w_tipo=Volta&P1=10&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\',\'Meta\',\'width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no\'); return false;" title="Clique para exibir os dados!">'.$l_destaque.$l_titulo.'</A>';
  $l_html.= chr(13).'        <td align="center" '.$l_row.'>'.$l_loa.'</td>';
  $l_html.= chr(13).'        <td align="center" '.$l_row.'>'.FormataDataEdicao($l_fim).'</td>';
  $l_html.= chr(13).'        <td nowrap align="right" '.$l_row.'>'.$l_perc.' %</td>';
  if ($l_oper=='S') {
    $l_html.= chr(13).'        <td align="top" nowrap '.$l_row.'>';
    // Se for listagem de metas no cadastramento da ação, exibe operações de alteração e exclusão
    if ($l_tipo=='PROJETO') {
      $l_html.= chr(13).'          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Alterar">Alt</A>&nbsp';
      if ($l_loa=='Não') $l_html.=chr(13).'          <A class="HL" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');" title="Excluir">Excl</A>&nbsp';
      // Caso contrário, é listagem de atualização de metas. Neste caso, coloca apenas a opção de alteração
    } else {
      $l_html.= chr(13).'          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Atualiza dados da meta">Atualizar</A>&nbsp';
    } 
    $l_html.= chr(13).'        </td>';
  } else {
    if ($l_tipo=='ETAPA') {
      $l_html.= chr(13).'        <td align="top" nowrap '.$l_row.'>';
      $l_html.= chr(13).'          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=V&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe os dados da meta">Exibir</A>&nbsp';
      $l_html.= chr(13).'        </td>';
    } 
  } 
  $l_html.= chr(13).'      </tr>';
  return $l_html;
} 
// =========================================================================
// Rotina de preparação para envio de e-mail relativo a projetos
// Finalidade: preparar os dados necessários ao envio automático de e-mail
// Parâmetro: p_solic: número de identificação da solicitação. 
//            p_tipo:  1 - Inclusão
//                     2 - Tramitação
//                     3 - Conclusão
// -------------------------------------------------------------------------
function SolicMail($p_solic,$p_tipo) {
  extract($GLOBALS);
  //Verifica se o cliente está configurado para receber email na tramitaçao de solicitacao
  $RS = db_getCustomerData::getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
  if(f($RS,'envia_mail_tramite')=='S') {
    $l_solic          = $p_solic;
    $w_destinatarios  = '';
    $w_resultado      = '';
    $w_html='<HTML>'.$crlf;
    $w_html .= BodyOpenMail(null).$crlf;
    $w_html .= '<table border="0" cellpadding="0" cellspacing="0" width="100%">'.$crlf;
    $w_html .= '<tr bgcolor="'.$conTrBgColor.'"><td align="center">'.$crlf;
    $w_html .= '    <table width="97%" border="0">'.$crlf;
    if ($p_tipo==1)       $w_html .= '      <tr valign="top"><td align="center"><font size=2><b>INCLUSÃO DE AÇÃO</b></font><br><br><td></tr>'.$crlf;
    elseif ($p_tipo==2)   $w_html .= '      <tr valign="top"><td align="center"><font size=2><b>TRAMITAÇÃO DE AÇÃO</b></font><br><br><td></tr>'.$crlf;
    elseif ($p_tipo==3)   $w_html .= '      <tr valign="top"><td align="center"><font size=2><b>CONCLUSÃO DE AÇÃO</b></font><br><br><td></tr>'.$crlf;
    $w_html .= '      <tr valign="top"><td><font size=2><b><font color="#BC3131">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><td></tr>'.$crlf;
    // Recupera os dados da ação
    $RSM = db_getSolicData_IS::getInstanceOf($dbms,$p_solic,'ISACGERAL');
    foreach($RSM as $row){$RSM=$row; break;}
    $w_nome = 'Ação '.f($RSM,'titulo');
    $w_html .= $crlf.'<tr bgcolor="'.$conTrBgColor.'"><td align="center">';
    $w_html .= $crlf.'    <table width="99%" border="0">';
    $w_html .= $crlf.'      <tr><td><font size=2>Ação: <b>'.f($RSM,'titulo').'</b></font></td>';
    // Identificação da ação
    $w_html .= $crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>EXTRATO DA AÇÃO</td>';
    // Se a classificação foi informada, exibe.
    if (Nvl(f($RSM,'sq_cc'),'')>'') $w_html .= $crlf.'      <tr><td valign="top">Classificação:<br><b>'.f($RSM,'cc_nome').' </b></td>';
    $w_html .= $crlf.'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    $w_html .= $crlf.'          <tr valign="top">';
    $w_html .= $crlf.'          <td>Responsável pelo monitoramento:<br><b>'.f($RSM,'nm_sol').'</b></td>';
    $w_html .= $crlf.'          <td>Unidade responsável pelo monitoramento:<br><b>'.f($RSM,'nm_unidade_resp').'</b></td>';
    $w_html .= $crlf.'          <tr valign="top">';
    $w_html .= $crlf.'          <td>Data de recebimento:<br><b>'.$FormataDataEdicao[f($RSM,'inicio')].' </b></td>';
    $w_html .= $crlf.'          <td>Limite para conclusão:<br><b>'.$FormataDataEdicao[f($RSM,'fim')].' </b></td>';
    $w_html .= $crlf.'          </table>';
    // Informações adicionais
    if (Nvl(f($RSM,'descricao'),'')>'') $w_html .= $crlf.'      <tr><td valign="top">Resultados da ação:<br><b>'.CRLF2BR(f($RSM,'descricao')).' </b></td>';
    $w_html .= $crlf.'    </table>';
    $w_html .= $crlf.'</tr>';
    // Dados da conclusão da ação, se ela estiver nessa situação
    if (f($RSM,'concluida')=='S' && Nvl(f($RSM,'data_conclusao'),'')>'') {
      $w_html .= $crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>DADOS DA CONCLUSÃO</td>';
      $w_html .= $crlf.'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html .= $crlf.'          <tr valign="top">';
      $w_html .= $crlf.'          <td>Início da execução:<br><b>'.$FormataDataEdicao[f($RSM,'inicio_real')].' </b></td>';
      $w_html .= $crlf.'          <td>Término da execução:<br><b>'.$FormataDataEdicao[f($RSM,'fim_real')].' </b></td>';
      $w_html .= $crlf.'          </table>';
      $w_html .= $crlf.'      <tr><td valign="top">Nota de conclusão:<br><b>'.CRLF2BR(f($RSM,'nota_conclusao')).' </b></td>';
    } 
    if ($p_tipo==2) {
      // Se for tramitação
      // Encaminhamentos
      $RS = db_getSolicLog::getInstanceOf($dbms,$p_solic,null,'LISTA');
      $RS = SortArray($RS,'phpdt_data','desc');
      foreach ($RS as $row) {$RS = $row; break;}
      $w_html .= $crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>ÚLTIMO ENCAMINHAMENTO</td>';
      $w_html .= $crlf.'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html .= $crlf.'          <tr valign="top">';
      $w_html .= $crlf.'          <td>De:<br><b>'.f($RS,'responsavel').'</b></td>';
      $w_html .= $crlf.'          <td>Para:<br><b>'.f($RS,'destinatario').'</b></td>';
      $w_html .= $crlf.'          <tr valign="top"><td colspan=2>Despacho:<br><b>'.CRLF2BR(Nvl(f($RS,'despacho'),'---')).' </b></td>';
      $w_html .= $crlf.'          </table>';
      // Configura o destinatário da tramitação como destinatário da mensagem
      $RS1 = db_getPersonData::getInstanceOf($dbms,$w_cliente,f($RS,'sq_pessoa_destinatario'),null,null);
      $w_destinatarios=f($RS1,'email').'; ';
    } 
    $w_html .= $crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>OUTRAS INFORMAÇÕES</td>';
    $RS = db_getCustomerSite::getInstanceOf($dbms,$w_cliente);;
    $w_html .= '      <tr valign="top"><td><font size=2>'.$crlf;
    $w_html .= '         Para acessar o sistema use o endereço: <b><a class="SS" href="'.f($RS,'logradouro').'" target="_blank">'.f($RS,'Logradouro').'</a></b></li>'.$crlf;
    $w_html .= '      </font></td></tr>'.$crlf;
    $w_html .= '      <tr valign="top"><td><font size=2>'.$crlf;
    $w_html .= '         Dados da ocorrência:<br>'.$crlf;
    $w_html .= '         <ul>'.$crlf;
    $w_html .= '         <li>Responsável: <b>'.$_SESSION['NOME'].'</b></li>'.$crlf;
    $w_html .= '         <li>Data do servidor: <b>'.date('d/m/Y, H:i:s',toDate(time())).'</b></li>'.$crlf;
    $w_html .= '         <li>IP de origem: <b>'.$_SERVER['REMOTE_ADDR'].'</b></li>'.$crlf;
    $w_html .= '         </ul>'.$crlf;
    $w_html .= '      </font></td></tr>'.$crlf;
    $w_html .= '    </table>'.$crlf;
    $w_html .= '</td></tr>'.$crlf;
    $w_html .= '</table>'.$crlf;
    $w_html .= '</BODY>'.$crlf;
    $w_html .= '</HTML>'.$crlf;
    if(f($RSM,'st_sol')=='S') {
      // Recupera o e-mail do responsável
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,f($RSM,'solicitante'),null,null);
      if ((strpos($w_destinatarios,f($RS,'email').'; ')===false)) $w_destinatarios = $w_destinatarios.f($RS,'email').'; ';
    }
    // Recupera o e-mail do titular e do substituto pelo setor responsável
    $RS = db_getUorgResp::getInstanceOf($dbms,f($RSM,'sq_unidade'));
    foreach($RS as $row){$RS=$row; break;}
    if(f($RS,'st_titular')=='S') {
      if ((strpos($w_destinatarios,f($RS,'email_titular').'; ')===false) && Nvl(f($RS,'email_titular'),'nulo')!='nulo')       $w_destinatarios = $w_destinatarios.f($RS,'email_titular').'; ';
    }
    if(f($RS,'st_subsituto')=='S') {
      if ((strpos($w_destinatarios,f($RS,'email_substituto').'; ')===false) && Nvl(f($RS,'email_substituto'),'nulo')!='nulo') $w_destinatarios = $w_destinatarios.f($RS,'email_substituto').'; ';
    }
    // Recuperar o e-mail dos interessados
    $RS = db_getSolicInter::getInstanceOf($dbms,$p_solic,null,'LISTA');
    foreach($RS as $row) {
      if(f($row,'ativo')=='S') {
        if ((strpos($w_destinatarios,f($row,'email').'; ')===false)    && Nvl(f($row,'email'),'nulo')!='nulo'  && f($row,'envia_email') =='S')    $w_destinatarios=$w_destinatarios.f($row,'email').'; ';
      }
    }
    // Prepara os dados necessários ao envio
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if ($p_tipo==1 || $p_tipo==3) {
      // Inclusão ou Conclusão
      if ($p_tipo==1) $w_assunto='Inclusão - '.$w_nome; else $w_assunto='Conclusão - '.$w_nome;
    } elseif ($p_tipo==2) {
      // Tramitação
      $w_assunto='Tramitação - '.$w_nome;
    } 
    if ($w_destinatarios>'') {
      // Executa o envio do e-mail
      $w_resultado = EnviaMail($w_assunto,$w_html,$w_destinatarios,null);
    } 
    // Se ocorreu algum erro, avisa da impossibilidade de envio
    if ($w_resultado>'') {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'ATENÇÃO: não foi possível proceder o envio do e-mail.\n'.$w_resultado.'\');');
      ScriptClose();
    } 
  }
} 
// =========================================================================
// Rotina de preparação para envio de e-mail relativo restrições
// Finalidade: preparar os dados necessários ao envio automático de e-mail
// Parâmetro: p_solic: número de identificação da solicitação. 
//            p_tipo:  I - Inclusão
//                     E - Exclusão
// -------------------------------------------------------------------------
function RestricaoMail($l_solic,$l_descricao,$l_tl_restricao,$l_providencia,$l_tipo) {
  extract($GLOBALS);
  $w_destinatarios  = '';
  $w_resultado      = '';
  $w_html='<HTML>'.$crlf;
  $w_html .= BodyOpenMail(null).$crlf;
  $w_html .= '<table border="0" cellpadding="0" cellspacing="0" width="100%">'.$crlf;
  $w_html .= '<tr bgcolor="'.$conTrBgColor.'"><td align="center">'.$crlf;
  $w_html .= '    <table width="97%" border="0">'.$crlf;
  if ($l_tipo=='I')     $w_html .= '      <tr valign="top"><td align="center"><font size=2><b>INCLUSÃO DE RESTRIÇÃO</b></font><br><br><td></tr>'.$crlf;
  elseif ($l_tipo=='E') $w_html .= '      <tr valign="top"><td align="center"><font size=2><b>EXCLUSÃO DE RESTRIÇÃO</b></font><br><br><td></tr>'.$crlf;
  $w_html .= '      <tr valign="top"><td><font size=2><b><font color="#BC3131">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><td></tr>'.$crlf;
  // Recupera os dados do programa
  $RSM = db_getSolicData_IS::getInstanceOf($dbms,$l_solic,'ISACGERAL');
  foreach($RSM as $row){$RSM=$row; break;}
  $w_html .= $crlf.'<tr bgcolor="'.$conTrBgColor.'"><td align="center">';
  $w_html .= $crlf.'    <table width="99%" border="0">';
  $w_html .= $crlf.'      <tr><td><font size=2>Ação: <b>'.f($RSM,'cd_unidade').'.'.f($RSM,'cd_programa').'.'.f($RSM,'cd_acao').' - '.f($RSM,'nm_ppa').'</b></font></td>';
  // Identificação da ação
  $w_html .= $crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>EXTRATO DA AÇÃO</td>';
  $w_html .= $crlf.'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
  $w_html .= $crlf.'          <tr valign="top">';
  $w_html .= $crlf.'          <td>Responsável pelo monitoramento:<br><b>'.f($RSM,'nm_sol').'</b></td>';
  $w_html .= $crlf.'          <td>Unidade responsável pelo monitoramento:<br><b>'.f($RSM,'nm_unidade_resp').'</b></td>';
  $w_html .= $crlf.'          <tr valign="top">';
  $w_html .= $crlf.'          <td>Data de recebimento:<br><b>'.FormataDataEdicao(f($RSM,'inicio')).' </b></td>';
  $w_html .= $crlf.'          <td>Limite para conclusão:<br><b>'.FormataDataEdicao(f($RSM,'fim')).' </b></td>';
  $w_html .= $crlf.'          </table>';
  // Recupera o e-mail do responsável
  $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,f($RSM,'solicitante'),null,null);
  if ((strpos($w_destinatarios,f($RS,'email').'; ')===false)) $w_destinatarios = $w_destinatarios.f($RS,'email').'; ';
  // Identificação da restrição
  $w_html .= $crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>EXTRATO DA RESTRIÇÃO</td>';
  $w_html .= $crlf.'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
  $w_html .= $crlf.'          <tr valign="top">';
  $w_html .= $crlf.'          <td>Descrição da restrição:<br><b>'.$l_descricao.'</b></td>';
  $RSM = db_getTPRestricao_IS::getInstanceOf($dbms,$l_tl_restricao,null);
  foreach($RSM as $row){$RSM=$row; break;}
  $w_html .= $crlf.'          <tr valign="top">';
  $w_html .= $crlf.'          <td>Tipo da restrição:<br><b>'.f($RSM,'nome').'</b></td>';
  $w_html .= $crlf.'          <tr valign="top">';
  $w_html .= $crlf.'          <td>Providência:<br><b>'.Nvl($l_providencia,'---').'</b></td>';
  $w_html .= $crlf.'          </table>';
  $w_html .= $crlf.'    </table>';
  $w_html .= $crlf.'</tr>';
  $w_html .= $crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>OUTRAS INFORMAÇÕES</td>';
  $RS = db_getCustomerSite::getInstanceOf($dbms,$w_cliente);
  $w_html .= '      <tr valign="top"><td><font size=2>'.$crlf;
  $w_html .= '         Para acessar o sistema use o endereço: <b><a class="SS" href="'.f($RS,'logradouro').'" target="_blank">'.f($RS,'Logradouro').'</a></b></li>'.$crlf;
  $w_html .= '      </font></td></tr>'.$crlf;
  $w_html .= '      <tr valign="top"><td><font size=2>'.$crlf;
  $w_html .= '         Dados da ocorrência:<br>'.$crlf;
  $w_html .= '         <ul>'.$crlf;
  $w_html .= '         <li>Responsável: <b>'.$_SESSION['NOME'].'</b></li>'.$crlf;
  $w_html .= '         <li>Data do servidor: <b>'.date('d/m/Y, H:i:s',toDate(time())).'</b></li>'.$crlf;
  $w_html .= '         <li>IP de origem: <b>'.$_SERVER['REMOTE_ADDR'].'</b></li>'.$crlf;
  $w_html .= '         </ul>'.$crlf;
  $w_html .= '      </font></td></tr>'.$crlf;
  $w_html .= '    </table>'.$crlf;
  $w_html .= '</td></tr>'.$crlf;
  $w_html .= '</table>'.$crlf;
  $w_html .= '</BODY>'.$crlf;
  $w_html .= '</HTML>'.$crlf;
  // Recupera o e-mail do usuário que está cadastrando a restrição
  $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$w_usuario,null,null);
  if ((strpos($w_destinatarios,f($RS,'email'),'; ')===false)) $w_destinatarios = $w_destinatarios.f($RS,'email').'; ';
  // Recupera o e-mail dos interessados
  $RSM = db_getSolicInter::getInstanceOf($dbms,$l_solic,null,'LISTA');
  if (count($RSM)>0) {
    foreach ($RSM as $row) {
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,f($row,'sq_pessoa'),null,null);
      if ((strpos($w_destinatarios,f($RS,'email').'; ')===false)) $w_destinatarios=$w_destinatarios.f($RS,'email').'; ';
    } 
  } 
  // Prepara os dados necessários ao envio
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  if ($l_tipo=='I') {
    // Inclusão 
    $w_assunto='Inclusão de restrição da ação';
  } elseif ($l_tipo=='E') {
    // Exclusão
    $w_assunto='Exclusão de restrição da ação';
  } 
  if ($w_destinatarios>'') {
    // Executa o envio do e-mail
    $w_resultado = EnviaMail($w_assunto,$w_html,$w_destinatarios,null);
  } 
  // Se ocorreu algum erro, avisa da impossibilidade de envio
  if ($w_resultado>'') {
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'ATENÇÃO: não foi possível proceder o envio do e-mail.\n'.$w_resultado.'\');');
    ScriptClose();
  } 
} 
// =========================================================================
// Rotina de busca das ações do PPA
// -------------------------------------------------------------------------
function BuscaAcao() {
  extract($GLOBALS);
  $w_nome       = strtoupper($_REQUEST['w_nome']);
  $w_programa   = $_REQUEST['w_programa'];
  $w_acao       = $_REQUEST['w_acao'];
  $w_unidade    = $_REQUEST['w_unidade'];
  $w_chave      = $_REQUEST['w_chave'];
  $ChaveAux     = $_REQUEST['ChaveAux'];
  $restricao    = $_REQUEST['restricao'];
  $campo        = $_REQUEST['campo'];
  if ($restricao=='FINANCIAMENTO') {
    $RS = db_getAcaoPPA_IS::getIntanceOf($dbms,$w_cliente,$w_ano,$w_programa,$ChaveAux,null,$w_unidade,$restricao,$w_chave,$w_nome,null,null);
    $RS = SortArray($RS,'descricao_acao','asc');
  } elseif ($restricao=='IDENTIFICACAO') {
    $RS = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,null,$ChaveAux,null,null,$restricao,null,$w_nome,null,null);
    $RS = SortArray($RS,'descricao_acao','asc');
  } else {
    $RS = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,$w_programa,$ChaveAux,null,$w_unidade,null,null,$w_nome,null,null);
    $RS = SortArray($RS,'descricao_acao','asc');
  } 
  Cabecalho();
  ShowHTML('<TITLE>Seleção de ações do PPA</TITLE>');
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  ScriptOpen('JavaScript');
  ShowHTML('  function volta(l_chave) {');
  ShowHTML('     opener.document.Form.'.$campo.'.value=l_chave;');
  ShowHTML('     opener.document.Form.'.$campo.'.focus();');
  ShowHTML('     window.close();');
  ShowHTML('     opener.focus();');
  ShowHTML('   }');
  ValidateOpen('Validacao');
  Validate('w_nome','Nome','1','','4','100','1','1');
  Validate('ChaveAux','Código','1','','4','4','1','1');
  ShowHTML('  if (theForm.w_nome.value == \'\' && theForm.ChaveAux.value == \'\') {');
  ShowHTML('     alert (\'Informe um valor para o nome ou para o código!\');');
  ShowHTML('     theForm.w_nome.focus();');
  ShowHTML('     return false;');
  ShowHTML('  }');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="100%" border="0">');
  AbreForm('Form',$w_dir.$w_pagina.'BuscaAcao','POST','return(Validacao(this))',null,$P1,$P2,$P3,$P4,$TP,$SG,null,null);
  ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
  ShowHTML('<INPUT type="hidden" name="w_ano" value="'.$w_ano.'">');
  ShowHTML('<INPUT type="hidden" name="w_programa" value="'.$w_programa.'">');
  ShowHTML('<INPUT type="hidden" name="w_unidade" value="'.$w_unidade.'">');
  ShowHTML('<INPUT type="hidden" name="w_acao" value="'.$w_acao.'">');
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="restricao" value="'.$restricao.'">');
  ShowHTML('<INPUT type="hidden" name="campo" value="'.$campo.'">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2><b><ul>Instruções</b>:<li>Informe parte do nome da ação ou o código da ação.<li>Quando a relação for exibida, selecione a ação desejada clicando sobre o link <i>Selecionar</i>.<li>Após informar o nome da ação ou o código da ação, clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Cancelar</i>, a procura é cancelada.</ul></div>');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="100%" border="0">');
  ShowHTML('      <tr><td valign="top"><b>Parte do <U>n</U>ome da ação:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="w_nome" size="50" maxlength="100" value="'.$w_nome.'">');
  ShowHTML('      <tr><td valign="top"><b><U>C</U>ódigo da ação:<br><INPUT ACCESSKEY="S" '.$w_Disabled.' class="sti" type="text" name="ChaveAux" size="5" maxlength="4" value="'.$ChaveAux.'">');
  ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
  ShowHTML('      <tr><td align="center" colspan="3">');
  ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
  ShowHTML('            <input class="stb" type="button" name="Botao" value="Cancelar" onClick="window.close(); opener.focus();">');
  ShowHTML('          </td>');
  ShowHTML('      </tr>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</form>');
  if ($w_nome>'' || $ChaveAux>'') {
    ShowHTML('<tr><td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td>');
    ShowHTML('    <TABLE WIDTH="100%" border=0>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td>');
      ShowHTML('        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('            <td><b>Código</td>');
      ShowHTML('            <td><b>Nome</td>');
      ShowHTML('            <td><b>Operações</td>');
      ShowHTML('          </tr>');
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('            <td align="center">'.f($row,'cd_unidade').'.'.f($row,'cd_programa').'.'.f($row,'cd_acao').'</td>');
        ShowHTML('            <td>'.f($row,'descricao_acao').'</td>');
        ShowHTML('            <td><a class="ss" href="#" onClick="javascript:volta(\''.f($row,'chave').'\');">Selecionar</a>');
      } 
      ShowHTML('        </table></tr>');
      ShowHTML('      </center>');
      ShowHTML('    </table>');
      ShowHTML('  </td>');
      ShowHTML('</tr>');
    } 
  } 
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
} 
// =========================================================================
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  $w_file   ='';
  $w_tamanho='';
  $w_tipo   ='';
  $w_nome   ='';
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=this.focus();');
  if ($SG=='ISACGERAL' || $SG=='VLRAGERAL') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if ($O=='I' && $_REQUEST['w_sq_acao_ppa']>'') {
        $RS = db_getAcao_IS::getInstanceOf($dbms,substr($_REQUEST['w_sq_acao_ppa'],0,4),substr($_REQUEST['w_sq_acao_ppa'],4,4),substr($_REQUEST['w_sq_acao_ppa'],12,5),$w_ano,$w_cliente,null,null);
        foreach ($RS as $row) {$RS = $row; break;}
        if (f($RS,'Existe')>0) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'Ação já cadastrada!\');');
          ShowHTML('  history.back(1);');
          ScriptClose();
        } 
      } elseif ($O=='E') {
        // Se for operação de exclusão, verifica se é necessário excluir os arquivos físicos
        $RS = db_getSolicLog::getInstanceOf($dbms,$_REQUEST['w_chave'],null,'LISTA');
        // Mais de um registro de log significa que deve ser cancelada, e não excluída.
        // Nessa situação, não é necessário excluir os arquivos.
        if (count($RS)<=1) {
          $RS = db_getSolicAnexo::getInstanceOf($dbms,$_REQUEST['w_chave'],null,$w_cliente);
          foreach($RS as $row) {
              if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
          } 
        } 
      } 
      //Recupera 10  dos dias de prazo da tarefa, para emitir o alerta 
      $RS = db_get10PercentDays_IS::getInstanceOf($dbms,$_REQUEST['w_inicio'],$_REQUEST['w_fim']);
      $w_dias = f($RS,'dias');
      if ($w_dias<1) $w_dias=1;
      dml_putAcaoGeral_IS::getInstanceOf($dbms,$O,
        $_REQUEST['w_chave'],$_REQUEST['w_menu'],$_SESSION['LOTACAO'],$_REQUEST['w_solicitante'],$_REQUEST['w_proponente'],
        $_SESSION['SQ_PESSOA'],null,$_REQUEST['w_descricao'],$_REQUEST['w_justificativa'],$_REQUEST['w_inicio'],$_REQUEST['w_fim'],$_REQUEST['w_valor'],
        $_REQUEST['w_data_hora'],$_REQUEST['w_sq_unidade_resp'],$_REQUEST['w_titulo'],$_REQUEST['w_prioridade'],$_REQUEST['w_aviso'],$w_dias,
        $_REQUEST['w_cidade'],$_REQUEST['w_palavra_chave'],
        null,null,null,null,null,null,null,
        $w_ano,$w_cliente,substr($_REQUEST['w_sq_acao_ppa'],0,4),substr($_REQUEST['w_sq_acao_ppa'],4,4),substr($_REQUEST['w_sq_acao_ppa'],8,4),substr($_REQUEST['w_sq_acao_ppa'],12,5),$_REQUEST['w_sq_isprojeto'],$_REQUEST['w_selecao_mp'],$_REQUEST['w_selecao_se'],
        null,null,&$w_chave_nova,$w_copia,$_REQUEST['w_sq_unidade_adm'],null);
      ScriptOpen('JavaScript');
      if ($O=='I') {
        // Exibe mensagem de gravação com sucesso
        if (Nvl($_REQUEST['w_sq_acao_ppa'],'')=='') ShowHTML('  alert(\'Ação '.$w_chave_nova.' cadastrada com sucesso!\');');
        else                                        ShowHTML('  alert(\'Ação '.substr($_REQUEST['w_sq_acao_ppa'],12,5).'.'.substr($_REQUEST['w_sq_acao_ppa'],0,4).'.'.substr($_REQUEST['w_sq_acao_ppa'],4,4).' cadastrada com sucesso!\');');
        // Recupera os dados para montagem correta do menu
        $RS1 = db_getMenuData::getInstanceOf($dbms,$w_menu);
        ShowHTML('  parent.menu.location=\''.montaURL_JS(null,$conRootSIW.'menu.php?par=ExibeDocs&O=A&w_chave='.$w_chave_nova.'&w_documento=Nr. '.$w_chave_nova.'&R='.$R.'&SG='.f($RS1,'sigla').'&TP='.$TP.MontaFiltro('GET')).'\';');
      } elseif ($O=='E') {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      } else {
        if ($SG=='VLRAGERAL') $O='P';
        // Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
        $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,$SG);
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS1,'link').'&O='.$O.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      } 
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($SG=='ISACRESP') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putRespAcao_IS::getInstanceOf($dbms,$_REQUEST['w_chave_aux'],$_REQUEST['w_responsavel'],$_REQUEST['w_telefone'],$_REQUEST['w_email'],$_REQUEST['w_tipo']);
      ScriptOpen('JavaScript');
      // Recupera a sigla do serviço pai, para fazer a chamada ao menu
      $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,$SG);
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($SG=='ISACPROQUA') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putProgQualitativa_IS::getInstanceOf($dbms,
          $_REQUEST['w_chave'],null,$_REQUEST['w_observacao'],null,$_REQUEST['w_problema'],
          $_REQUEST['w_objetivo'],$_REQUEST['w_publico_alvo'],$_REQUEST['w_estrategia'],
          $_REQUEST['w_sistematica'],$_REQUEST['w_metodologia'],$SG);
      ScriptOpen('JavaScript');
      if ($O=='I') {
        // Recupera os dados para montagem correta do menu
        $RS1 = db_getMenuData::getInsatnceOf($dbms,$w_menu);
        ShowHTML('  parent.menu.location=\''.montaURL_JS(null,$conRootSIW.'menu.php?par=ExibeDocs&O=A&w_chave='.$w_chave_nova.'&w_documento=Nr. '.$w_chave_nova.'&R='.$R.'&SG='.f($RS1,'sigla').'&TP='.$TP.MontaFiltro('GET')).'\';');
      } else {
        // Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
        $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,$SG);
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS1,'link').'&O='.$O.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      }
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($SG=='ISMETA') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putAcaoMeta_IS::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],
          $_REQUEST['w_titulo'],$_REQUEST['w_descricao'],$_REQUEST['w_ordem'],$_REQUEST['w_inicio'],
          $_REQUEST['w_fim'],$_REQUEST['w_perc_conclusao'],$_REQUEST['w_orcamento'],
          $_REQUEST['w_programada'],$_REQUEST['w_cumulativa'],$_REQUEST['w_quantidade'],$_REQUEST['w_unidade_medida']);
      If (($O!='E') && $_REQUEST['w_programada']=='S'){
        dml_putMetaMensalIni_IS::getInstanceOf($dbms,'W',$_REQUEST['w_chave_aux'],$w_cliente,
            trim($_REQUEST['w_cron_ini_1']),trim($_REQUEST['w_cron_ini_2']),trim($_REQUEST['w_cron_ini_3']),trim($_REQUEST['w_cron_ini_4']),
            trim($_REQUEST['w_cron_ini_5']),trim($_REQUEST['w_cron_ini_6']),trim($_REQUEST['w_cron_ini_7']),trim($_REQUEST['w_cron_ini_8']),
            trim($_REQUEST['w_cron_ini_9']),trim($_REQUEST['w_cron_ini_10']),trim($_REQUEST['w_cron_ini_11']),trim($_REQUEST['w_cron_ini_12']));
      }
      ScriptOpen('JavaScript');
      // Recupera a sigla do serviço pai, para fazer a chamada ao menu
      $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,$SG);
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($SG=='ISACPROFIN') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putFinancAcaoPPA_IS::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],substr($_REQUEST['w_chave_aux'],0,4),
        substr($_REQUEST['w_chave_aux'],4,4),substr($_REQUEST['w_chave_aux'],8,4),
        $w_cliente,$w_ano,$_REQUEST['w_obs_financ']);
        ScriptOpen('JavaScript');
        // Recupera a sigla do serviço pai, para fazer a chamada ao menu
        $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,$SG);
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
        ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($SG=='ISACAD') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      // Verifica se a meta é cumulativa ou não para o calculo do percentual de conclusão
      if ($_REQUEST['w_cumulativa']=='N') {
        if (Nvl($_REQUEST['w_quantidade'],0)==0) {
          $w_perc_conclusao=100;
        } else {
          $i=1;
          // Faz a varredura do campos de quantidade e irá armazenar o percentual de conclusão do ultimo mês atualizazado
          for ($i=12; $i>=1; $i=$i-1) {
            if (Nvl($_REQUEST['w_realizado_'.$i.''],0)>0) { 
              $w_perc_conclusao=($_REQUEST['w_realizado_'.$i.'']*100)/$_REQUEST['w_quantidade'];
              $i=1;
            } 
          } 
        } 
      } else {
        //Se não for cumulativa faz o percentual de conclusão com todos os valores do formulário
        $w_quantitativo_total = 0;
        for ($i=1; $i<=12; $i=$i+1) {
          $w_quantitativo_total=$w_quantitativo_total+Nvl($_REQUEST['w_realizado_'.$i],0);
        } 
        if (Nvl($_REQUEST['w_quantidade'],0)==0) $w_perc_conclusao = 100;
        else                                     $w_perc_conclusao = ($w_quantitativo_total*100)/$_REQUEST['w_quantidade'];
      } 
      dml_putAtualizaMeta_IS::getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],Nvl($w_perc_conclusao,0),$_REQUEST['w_situacao_atual'],
          $_REQUEST['w_exequivel'],$_REQUEST['w_justificativa_inex'],$_REQUEST['w_outras_medidas']);
      $i=1;
      // Gravação da execução física e feita mês por mês
      dml_putMetaMensal_IS::getInstanceOf($dbms,'E',$_REQUEST['w_chave_aux'],$_REQUEST['w_realizado_'.$i],$_REQUEST['w_revisado_'.$i],$_REQUEST['w_referencia_'.$i],$w_cliente);
      for ($i=1; $i<13; $i=$i+1) {
        dml_putMetaMensal_IS::getInstanceOf($dbms,'Z',$_REQUEST['w_chave_aux'],Nvl($_REQUEST['w_realizado_'.$i],0),Nvl($_REQUEST['w_revisado_'.$i],0),$_REQUEST['w_referencia_'.$i],$w_cliente);
        if (Nvl($_REQUEST['w_realizado_'.$i],0)>0 || Nvl($_REQUEST['w_revisado_'.$i],0)>0) {
          dml_putMetaMensal_IS::getInstanceOf($dbms,'I',$_REQUEST['w_chave_aux'],$_REQUEST['w_realizado_'.$i.''],$_REQUEST['w_revisado_'.$i],$_REQUEST['w_referencia_'.$i],$w_cliente);
        } 
      } 
      ScriptOpen('JavaScript');
      // Recupera a sigla do serviço pai, para fazer a chamada ao menu
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }  
  } elseif ($SG=='ISACRESTR') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putRestricao_IS::getInstanceOf($dbms,$O,$SG,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_cd_subacao'],$_REQUEST['w_sq_isprojeto'],
        $_REQUEST['w_cd_tipo_restricao'],
        $_REQUEST['w_cd_tipo_inclusao'],$_REQUEST['w_cd_competencia'],$_REQUEST['w_superacao'],
        $_REQUEST['w_relatorio'],$_REQUEST['w_tempo_habil'],$_REQUEST['w_descricao'],
        $_REQUEST['w_providencia'],$_REQUEST['w_observacao_controle'],$_REQUEST['w_observacao_monitor'],$w_ano, $w_cliente);
      if ($O=='I' || $O=='E') RestricaoMail($_REQUEST['w_chave'],$_REQUEST['w_descricao'],$_REQUEST['w_cd_tipo_restricao'],$_REQUEST['w_providencia'],$O);
      ScriptOpen('JavaScript');
      // Recupera a sigla do serviço pai, para fazer a chamada ao menu
      $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,$SG);
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($SG=='ISACINTERE') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putProjetoInter::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_tipo_visao'],$_REQUEST['w_envia_email']);
      ScriptOpen('JavaScript');
      // Recupera a sigla do serviço pai, para fazer a chamada ao menu
      $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,$SG);
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }
  } elseif ($SG=='ISACANEXO') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if (UPLOAD_ERR_OK==0) {
        $w_maximo = $_REQUEST['w_upload_maximo'];
        foreach ($_FILES as $Chv => $Field) {
          if (!($Field['error']==UPLOAD_ERR_OK || $Field['error']==UPLOAD_ERR_NO_FILE)) {
            // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!\');');
            ShowHTML('  history.go(-1);');
            ScriptClose();
            exit();
          }
          if ($Field['size'] > 0) {
            // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
            if ($Field['size'] > $w_maximo) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!\');');
              ShowHTML('  history.back(1);');
              ScriptClose();
              exit();
            } 
            // Se já há um nome para o arquivo, mantém 
            if ($_REQUEST['w_atual']>'') {
              $RS = db_getSolicAnexo::getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],$w_cliente);
              foreach ($RS as $row) {
                if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
                if (!(strpos(f($row,'caminho'),'.')===false)) {
                  $w_file = substr(basename(f($row,'caminho')),0,(strpos(basename(f($row,'caminho')),'.') ? strpos(basename(f($row,'caminho')),'.')+1 : 0)-1).substr($Field['name'],(strpos($Field['name'],'.') ? strpos($Field['name'],'.')+1 : 0)-1,30);
                } else {
                  $w_file = basename(f($row,'caminho'));
                }
              }
            } else {
              $w_file = str_replace('.tmp','',basename($Field['tmp_name']));
              if (!(strpos($Field['name'],'.')===false)) {
                $w_file = $w_file.substr($Field['name'],(strpos($Field['name'],'.') ? strpos($Field['name'],'.')+1 : 0)-1,10);
              }
            } 
            $w_tamanho = $Field['size'];
            $w_tipo    = $Field['type'];
            $w_nome    = $Field['name'];
            if ($w_file>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
          } 
        } 
        // Se for exclusão e houver um arquivo físico, deve remover o arquivo do disco.  
        if ($O=='E' && $_REQUEST['w_atual']>'') {
          $RS = db_getSolicAnexo::getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],$w_cliente);
          foreach ($RS as $row) {
            if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
          }
        } 
        dml_putSolicArquivo::getInstanceOf($dbms,$O,
          $w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_nome'],$_REQUEST['w_descricao'],
          $w_file,$w_tamanho,$w_tipo,$w_nome);
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
        ScriptClose();
        exit();
      } 
      ScriptOpen('JavaScript');
     // Recupera a sigla do serviço pai, para fazer a chamada ao menu 
      $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,$SG);
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($SG=='ISACENVIO') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $RS = db_getSolicData_IS::getInstanceOf($dbms,$_REQUEST['w_chave'],'ISACGERAL');
      foreach($RS as $row){$RS=$row; break;}
      if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATENÇÃO: Outro usuário já encaminhou esta ação para outra fase de execução!\');');
        ScriptClose();
      } else {
        dml_putProjetoEnvio::getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_novo_tramite'],'N',$_REQUEST['w_observacao'], $_REQUEST['w_destinatario'], $_REQUEST['w_despacho'],null,null,null,null);
        // Envia e-mail comunicando a tramitação
        if ($_REQUEST['w_novo_tramite']>'') SolicMail($_REQUEST['w_chave'],2);
        if ($P1==1) {
          // Se for envio da fase de cadastramento, remonta o menu principal
          // Recupera os dados para montagem correta do menu
          $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
          ScriptOpen('JavaScript');
          ShowHTML('  parent.menu.location=\''.montaURL_JS(null,$conRootSIW.'menu.php?par=ExibeDocs&O=L&R='.$R.'&SG='.f($RS,'sigla').'&TP='.RemoveTP(RemoveTP($TP)).MontaFiltro('GET')).'\';');
          ScriptClose();
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
          ScriptClose();
        } 
      } 
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }  
  } elseif ($SG=='ISACCONC') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $RS = db_getSolicData_IS::getInstanceOf($dbms,$_REQUEST['w_chave'],'ISACGERAL');
      foreach($RS as $row){$RS=$row; break;}
      if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATENÇÃO: Outro usuário já encaminhou esta ação para outra fase de execução!\');');
        ScriptClose();
      } else {
        dml_putProjetoConc::getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_inicio_real'],$_REQUEST['w_fim_real'],$_REQUEST['w_nota_conclusao'],$_REQUEST['w_custo_real']);
        // Envia e-mail comunicando a conclusão
        SolicMail($_REQUEST['w_chave'],3);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
        ScriptClose();
      } 
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'Bloco de dados não encontrado: '.$SG.'\');');
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
    case 'INICIAL':     Inicial();                  break;
    case 'GERAL':       Geral();                    break;
    case 'RESP':        Responsaveis();             break;
    case 'PROGQUAL':    ProgramacaoQualitativa();   break;
    case 'META':        Metas();                    break;
    case 'ATUALIZAMETA':AtualizaMeta();             break;
    case 'PROGFINAN':   Financiamento();            break;
    case 'RESTRICAO':   Restricoes();               break;
    case 'INTERESS':    Interessados();             break;
    case 'VISUAL':      Visual();                   break;
    case 'VISUALNOVO':  VisualNovo();               break;
    case 'VISUALE':     VisualE();                  break;
    case 'EXCLUIR':     Excluir();                  break;
    case 'ENVIO':       Encaminhamento();           break;
    case 'ANEXO':       Anexos();                   break;
    case 'ANOTACAO':    Anotar();                   break;
    case 'CONCLUIR':    Concluir();                 break;
    case 'OUTRAS':      Iniciativas();              break;
    case 'FINANC':      Financiamento();            break;
    case 'BUSCAACAO':   BuscaAcao();                break;
    case 'RECURSOPROGRAMADO': RecursoProgramado();  break;
    case 'GRAVA':       Grava();                    break;
    default:
      cabecalho();
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');      
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