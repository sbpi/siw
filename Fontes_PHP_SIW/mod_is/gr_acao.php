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
include_once($w_dir_volta.'classes/sp/db_getSolicList_IS.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'funcoes/selecaoAcaoPPA.php');
include_once($w_dir_volta.'funcoes/selecaoIsProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade_IS.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
// =========================================================================
//  /gr_acao.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Gerencia o módulo de ações
// Mail     : celso@sbpi.com.br
// Criacao  : 07/08/2006 12:00
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
// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }
// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);
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
$w_pagina       = 'gr_acao.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_is/';
$w_troca        = $_REQUEST['w_troca'];
$w_SG           = upper($_REQUEST['w_SG']);
$p_ordena       = lower($_REQUEST['p_ordena']);
if ($O=='') $O='P';
switch ($O) {
  case 'V': $w_TP=$TP.' - Gráfico';     break;
  case 'P': $w_TP=$TP.' - Filtragem';   break;
  default:  $w_TP=$TP.' - Listagem';    break;
} 
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = $P2;
$w_ano      = RetornaAno();
// Declaração de variáveis
$p_tipo             = upper($_REQUEST['p_tipo']);
$p_ativo            = upper($_REQUEST['p_ativo']);
$p_solicitante      = upper($_REQUEST['p_solicitante']);
$p_prioridade       = upper($_REQUEST['p_prioridade']);
$p_unidade          = upper($_REQUEST['p_unidade']);
$p_proponente       = upper($_REQUEST['p_proponente']);
$p_ini_i            = upper($_REQUEST['p_ini_i']);
$p_ini_f            = upper($_REQUEST['p_ini_f']);
$p_fim_i            = upper($_REQUEST['p_fim_i']);
$p_fim_f            = upper($_REQUEST['p_fim_f']);
$p_atraso           = upper($_REQUEST['p_atraso']);
$p_chave            = upper($_REQUEST['p_chave']);
$p_assunto          = upper($_REQUEST['p_assunto']);
$p_pais             = upper($_REQUEST['p_pais']);
$p_regiao           = upper($_REQUEST['p_regiao']);
$p_uf               = upper($_REQUEST['p_uf']);
$p_cidade           = upper($_REQUEST['p_cidade']);
$p_usu_resp         = upper($_REQUEST['p_usu_resp']);
$p_uorg_resp        = upper($_REQUEST['p_uorg_resp']);
$p_palavra          = upper($_REQUEST['p_palavra']);
$p_prazo            = upper($_REQUEST['p_prazo']);
$p_fase             = explodeArray($_REQUEST['p_fase']);
$p_sq_acao_ppa      = upper($_REQUEST['p_sq_acao_ppa']);
$p_sq_isprojeto     = upper($_REQUEST['p_sq_isprojeto']);
$p_qtd_restricao    = upper($_REQUEST['p_qtd_restricao']);
$p_agrega           = upper($_REQUEST['p_agrega']);
$p_selecao_mp       = upper($_REQUEST['p_selecao_mp']);
$p_selecao_se       = upper($_REQUEST['p_selecao_se']);
$p_tamanho          = upper($_REQUEST['p_tamanho']);
// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$RS = db_getLinkSubMenu::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu = 'Existe';
} else {
  $w_submenu = '';
}
// Recupera a configuração do serviço
$RS_Menu = new db_getMenuData; $RS_Menu = $RS_Menu->getInstanceOf($dbms,$w_menu);
Main();
FechaSessao($dbms);
exit;
// =========================================================================
// Pesquisa gerencial
// -------------------------------------------------------------------------
function Gerencial() {
  extract($GLOBALS);
  
  $w_pag   = 1;
  $w_linha = 0;
  
  global $w_Disabled;
  if ($O=='L' || $O=='V' || $O=='W' || $p_tipo=='WORD' || $p_tipo=='PDF') {
    $w_filtro='';
    if ($p_sq_acao_ppa>'') {
      $w_linha++;
      $RS = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,substr($p_sq_acao_ppa,0,4),substr($p_sq_acao_ppa,4,4),null,substr($p_sq_acao_ppa,12,17),null,null,null,null,null);
      foreach($RS as $row){$RS=$row; break;}
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Ação <td>[<b>'.f($RS,'cd_unidade').'.'.f($RS,'cd_programa').'.'.f($RS,'cd_acao').' - '.f($RS,'descricao_acao').' ('.f($RS,'ds_unidade').')</b>]';
    } 
    if ($p_sq_isprojeto>'') {
      $w_linha++;
      $RS = db_getProjeto_IS::getInstanceOf($dbms,$p_sq_isprojeto,$w_cliente,null,null,null,null,null,null,null,null,null,null,null,null);
      foreach($RS as $row){$RS=$row; break;}
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Programa interno<td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_chave>'') { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Ação nº <td>[<b>'.$p_chave.'</b>]'; }
    if ($p_prazo>'') { $w_linha++; $w_filtro=$w_filtro.' <tr valign="top"><td align="right">Prazo para conclusão até<td>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]'; }
    if ($p_solicitante>'') {
      $w_linha++;
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Responsável <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_unidade>'') {
      $w_linha++;
      $RS = db_getUorgData::getInstanceOf($dbms,$p_unidade);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Unidade responsável <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_usu_resp>'') {
      $w_linha++;
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_usu_resp,null,null);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Executor <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_uorg_resp>'') {
      $w_linha++;
      $RS = db_getUorgData::getInstanceOf($dbms,$p_uorg_resp);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Unidade atual <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_selecao_mp>'')      { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Selecionada SPI/MP <td>[<b>'.$p_selecao_se.'</b>]'; }
    if ($p_selecao_se>'')      { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Selecionada SE/MS <td>[<b>'.$p_selecao_mp.'</b>]'; }
    if ($p_proponente>'')      { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Parcerias externas<td>[<b>'.$p_proponente.'</b>]'; }
    if ($p_assunto>'')         { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Ação <td>[<b>'.$p_assunto.'</b>]'; }
    if ($p_palavra>'')         { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Parcerias internas<td>[<b>'.$p_palavra.'</b>]'; }
    if ($p_ini_i>'')           { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Data recebimento <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]'; }
    if ($p_fim_i>'')           { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Limite conclusão <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]'; }
    if ($p_atraso=='S')        { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Situação <td><b>Apenas atrasadas</b>]'; }
    if ($p_qtd_restricao=='S') { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Situação <td>[<b>Apenas ações com restrição</b>]'; }
    if ($w_filtro>'')          { $w_linha++; $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>'; }
    $RS1 = db_getSolicList_IS::getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
            $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
            $p_unidade,null,$p_qtd_restricao,$p_proponente,$p_chave,$p_assunto,
            null,null,null,null,$p_usu_resp,$p_uorg_resp,$p_palavra,
            $p_prazo,$p_fase,null,null,substr($p_sq_acao_ppa,0,4),substr($p_sq_acao_ppa,4,4),$p_sq_isprojeto,substr($p_sq_acao_ppa,8,4),$w_ano);
    switch ($p_agrega) {
      case 'GRISAACAO':
        $w_TP=$TP.' - Por ação'; 
        $RS1 = SortArray($RS1,'titulo','asc');
      break;
      case 'GRISAPROP':
        $w_TP=$TP.' - Por parcerias externas';
        $RS1 = SortArray($RS1,'proponente','asc');
      break;
      case 'GRISARESP':
        $w_TP=$TP.' - Por responsável';
        $RS1 = SortArray($RS1,'nm_solic','asc');
      break;
      case 'GRISARESPATU':
        $w_TP=$TP.' - Por executor';
        $RS1 = SortArray($RS1,'nm_exec','asc');
        break;
      case 'GRISASETOR':
        $w_TP=$TP.' - Por setor área de planejamento';
        $RS1 = SortArray($RS1,'nm_unidade_resp','asc');
      break;
    } 
  } 
  $w_linha_filtro = $w_linha;
  if ($p_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 45: 30);
    CabecalhoWord($w_cliente,$w_TP,$w_pag);
    $w_embed = 'WORD';
    if ($w_filtro>'') ShowHTML($w_filtro);
  }elseif($p_tipo == 'PDF'){
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 60: 35);
    $w_embed = 'WORD';
    HeaderPdf('Consulta de '.f($RS_Menu,'nome'),$w_pag);
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
      Validate('p_prazo','Dias para a data limite','','','1','2','','0123456789');
      Validate('p_proponente','Parcerias externas','','','2','90','1','');
      Validate('p_palavra','Parcerias internas','','','2','90','1','1');
      Validate('p_ini_i','Recebimento inicial','DATA','','10','10','','0123456789/');
      Validate('p_ini_f','Recebimento final','DATA','','10','10','','0123456789/');
      ShowHTML('  if ((theForm.p_ini_i.value != \'\' && theForm.p_ini_f.value == \'\') || (theForm.p_ini_i.value == \'\' && theForm.p_ini_f.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas de recebimento ou nenhuma delas!\');');
      ShowHTML('     theForm.p_ini_i.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  for (i=0; i < theForm["p_fase[]"].length; i++) {');
      ShowHTML('    if (theForm["p_fase[]"][i].checked) w_erro=false;');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert(\'Você deve informar pelo menos uma fase!\'); ');
      ShowHTML('    return false;');
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
      ValidateClose();
      ScriptClose();
    } else {
      ShowHTML('<TITLE>'.$w_TP.'</TITLE>');
    } 
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($w_troca>'') {
      // Se for recarga da página
      BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
    } elseif (!(strpos('P',$O)===false)) {
      if ($P1==1) {
        // Se for cadastramento
        BodyOpen('onLoad=\'document.Form.p_ordena.focus()\';');
      } else {
        BodyOpen('onLoad=\'document.Form.p_agrega.focus()\';');
      }
    } else {
      BodyOpenClean(null);
    } 
    if ($O=='L') {
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
      ShowHTML('<HR>');
      if ($w_filtro>'') ShowHTML($w_filtro);
    } else {
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
      ShowHTML('<HR>');
    } 
  } 
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L' || $O=='W') {
    if ($O=='L') {
      ShowHTML('<tr><td>');
      if (MontaFiltro('GET')>'') ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
      else                       ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
    } 
    ImprimeCabecalho();
    if (count($RS1)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      if ($O=='L') {
        ShowHTML('<SCRIPT LANGUAGE="JAVASCRIPT">');
        ShowHTML('  function lista (filtro, cad, exec, conc, atraso) {');
        ShowHTML('    if (filtro != -1) {');
        switch ($p_agrega) {
          case 'GRISAACAO':     ShowHTML('      document.Form.p_acao.value=filtro;');       break;
          case 'GRISAPROP':     ShowHTML('      document.Form.p_proponente.value=filtro;'); break;
          case 'GRISARESP':     ShowHTML('      document.Form.p_solicitante.value=filtro;');break;
          case 'GRISARESPATU':  ShowHTML('      document.Form.p_usu_resp.value=filtro;');   break;
          case 'GRISASETOR':    ShowHTML('      document.Form.p_unidade.value=filtro;');    break;
        } 
        ShowHTML('    }');
        switch ($p_agrega) {
          case 'GRISAACAO':     ShowHTML('    else document.Form.p_acao.value=\''.$_REQUEST['p_acao'].'\';');                 break;
          case 'GRISAPROP':     ShowHTML('    else document.Form.p_proponente.value=\''.$_REQUEST['p_proponente'].'\';');     break;
          case 'GRISARESP':     ShowHTML('    else document.Form.p_solicitante.value=\''.$_REQUEST['p_solicitante'].'\';');   break;
          case 'GRISARESPATU':  ShowHTML('    else document.Form.p_usu_resp.value=\''.$_REQUEST['p_usu_resp'].'\';');         break;
          case 'GRISASETOR':    ShowHTML('    else document.Form.p_unidade.value=\''.$_REQUEST['p_unidade'].'\';');           break;
        } 
        $RS2 = db_getTramiteList::getInstanceOf($dbms,$P2,null,null,null);
        $RS2 = SortArray($RS2,'ordem','asc');
        $w_fase_exec = '';
        foreach ($RS2 as $row2) {
          if (f($row2,'sigla')=='CI')    $w_fase_cad  = f($row2,'sq_siw_tramite');
          elseif (f($row2,'sigla')=='AT')$w_fase_conc = f($row2,'sq_siw_tramite');
          elseif (f($row2,'ativo')=='S') $w_fase_exec = $w_fase_exec.','.f($row2,'sq_siw_tramite');
        } 
        ShowHTML('    if (cad >= 0) document.Form.p_fase.value='.$w_fase_cad.';');
        ShowHTML('    if (exec >= 0) document.Form.p_fase.value=\''.substr($w_fase_exec,1,100).'\';');
        ShowHTML('    if (conc >= 0) document.Form.p_fase.value='.$w_fase_conc.';');
        ShowHTML('    if (cad==-1 && exec==-1 && conc==-1) document.Form.p_fase.value=\''.$p_fase.'\';');
        ShowHTML('    if (atraso >= 0) document.Form.p_atraso.value=\'S\'; else document.Form.p_atraso.value=\''.$_REQUEST['p_atraso'].'\';');
        ShowHTML('    document.Form.submit();');
        ShowHTML('  }');
        ShowHTML('</SCRIPT>');
        ShowHTML('<BASE HREF="'.$conRootSIW.'">');
        $RS2 = new db_getMenuData; $RS2 = $RS2->getInstanceOf($dbms,$P2);
        AbreForm('Form',f($RS2,'link'),'POST','return(Validacao(this));','Lista',3,$P2,f($RS2,'P3'),null,$w_TP,f($RS2,'sigla'),$w_dir.$w_pagina.$par,'L');
        ShowHTML(MontaFiltro('POST'));
        switch ($p_agrega) {
          case 'GRISAACAO':     if ($_REQUEST['p_acao']=='')        ShowHTML('<input type="Hidden" name="p_acao" value="">');       break;
          case 'GRISAPROP':     if ($_REQUEST['p_proponente']=='')  ShowHTML('<input type="Hidden" name="p_proponente" value="">'); break;
          case 'GRISARESP':     if ($_REQUEST['p_solicitante']=='') ShowHTML('<input type="Hidden" name="p_solicitante" value="">');break;
          case 'GRISARESPATU':  if ($_REQUEST['p_usu_resp']=='')    ShowHTML('<input type="Hidden" name="p_usu_resp" value="">');   break;
          case 'GRISASETOR':    if ($_REQUEST['p_unidade']=='')     ShowHTML('<input type="Hidden" name="p_unidade" value="">');    break;
        } 
      } 
      $w_nm_quebra='';
      $w_qt_quebra=0.00;
      $t_solic=0.00;
      $t_cad=0.00;
      $t_tram=0.00;
      $t_conc=0.00;
      $t_atraso=0.00;
      $t_aviso=0.00;
      $t_valor=0.00;
      $t_acima=0.00;
      $t_custo=0.00;
      $t_totcusto=0.00;
      $t_totsolic=0.00;
      $t_totcad=0.00;
      $t_tottram=0.00;
      $t_totconc=0.00;
      $t_totatraso=0.00;
      $t_totaviso=0.00;
      $t_totvalor=0.00;
      $t_totacima=0.00;
      foreach($RS1 as $row) {
        switch ($p_agrega) {
          case 'GRISAACAO':
            if ($w_nm_quebra!=f($row,'titulo')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
              }
              if ($O!='W' || ($O=='W' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'titulo'));
              }
              $w_nm_quebra=f($row,'titulo');
              $w_chave=f($row,'sq_siw_solicitacao');
              $w_qt_quebra=0.00;
              $t_solic=0.00;
              $t_cad=0.00;
              $t_tram=0.00;
              $t_conc=0.00;
              $t_atraso=0.00;
              $t_aviso=0.00;
              $t_valor=0.00;
              $t_acima=0.00;
              $t_custo=0.00;
              $w_linha=$w_linha+1;
            }
          break;
          case 'GRISAPROP':
            if ($w_nm_quebra!=f($row,'proponente')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
              }
              if ($O!='W' || ($O=='W' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'proponente'));
              }
              $w_nm_quebra  = f($row,'proponente');
              $w_chave      = f($row,'proponente');
              $w_qt_quebra=0.00;
              $t_solic=0.00;
              $t_cad=0.00;
              $t_tram=0.00;
              $t_conc=0.00;
              $t_atraso=0.00;
              $t_aviso=0.00;
              $t_valor=0.00;
              $t_acima=0.00;
              $t_custo=0.00;
              $w_linha=$w_linha+1;
            }
          break;
          case 'GRISARESP':
            if ($w_nm_quebra!=f($row,'nm_solic')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
              }
              if ($O!='W' || ($O=='W' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_solic'));
              }
              $w_nm_quebra=f($row,'nm_solic');
              $w_chave=f($row,'solicitante');
              $w_qt_quebra=0.00;
              $t_solic=0.00;
              $t_cad=0.00;
              $t_tram=0.00;
              $t_conc=0.00;
              $t_atraso=0.00;
              $t_aviso=0.00;
              $t_valor=0.00;
              $t_acima=0.00;
              $t_custo=0.00;
              $w_linha=$w_linha+1;
            }
          break;
          case 'GRISARESPATU':
            if ($w_nm_quebra!=f($row,'nm_exec')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_exec'));
              }
              $w_nm_quebra=f($row,'nm_exec');
              $w_chave=f($row,'executor');
              $w_qt_quebra=0.00;
              $t_solic=0.00;
              $t_cad=0.00;
              $t_tram=0.00;
              $t_conc=0.00;
              $t_atraso=0.00;
              $t_aviso=0.00;
              $t_valor=0.00;
              $t_acima=0.00;
              $t_custo=0.00;
              $w_linha=$w_linha+1;
            }
          break;
          case 'GRISASETOR':
            if ($w_nm_quebra!=f($row,'nm_unidade_resp')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
              }
              if ($O!='W' || ($O=='W' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_unidade_resp'));
              }
              $w_nm_quebra=f($row,'nm_unidade_resp');
              $w_chave=f($row,'sq_unidade_resp');
              $w_qt_quebra=0.00;
              $t_solic=0.00;
              $t_cad=0.00;
              $t_tram=0.00;
              $t_conc=0.00;
              $t_atraso=0.00;
              $t_aviso=0.00;
              $t_valor=0.00;
              $t_acima=0.00;
              $t_custo=0.00;
              $w_linha=$w_linha+1;
            }
          break;
        } 
        if ($O=='W' && $w_linha>$w_linha_pag) {
          // Se for geração de MS-Word, quebra a página
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
          ShowHTML('</table>');
          ShowHTML('</center></div>');
          if ($p_tipo=='PDF') ShowHTML('    <pd4ml:page.break>');
          else                ShowHTML('    <br style="page-break-after:always">');
          $w_linha=$w_linha_filtro;
          $w_pag=$w_pag+1;
          CabecalhoWord($w_cliente,$w_TP,$w_pag);
          if ($w_filtro>'') ShowHTML($w_filtro);
          ShowHTML('<div align=center><center>');
          ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
          ImprimeCabecalho();
          switch ($p_agrega) {
            case 'GRISAACAO':   ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'titulo'));          break;
            case 'GRISAPROP':   ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'proponente'));      break;
            case 'GRISARESP':   ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_solic'));        break;
            case 'GRISARESPATU':ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_exec'));         break;
            case 'GRISASETOR':  ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_unidade_resp')); break;
          } 
          $w_linha = $w_linha + 1;
        }
        if (f($row,'concluida')=='N') {
          if (f($row,'fim') < addDays(time(),-1)) {
            $t_atraso    = $t_atraso + 1;
            $t_totatraso = $t_totatraso + 1;
          } elseif (f($row,'aviso_prox_conc') == 'S' && (f($row,'aviso') <= addDays(time(),-1))) {
            $t_aviso    = $t_aviso+1;
            $t_totaviso = $t_totaviso+1;
          }
          if (f($row,'or_tramite')==1) {
            $t_cad      = $t_cad+1;
            $t_totcad   = $t_totcad+1;
          } else {
            $t_tram     = $t_tram+1;
            $t_tottram  = $t_tottram+1;
          } 
        } else {
          $t_conc       =   $t_conc+1;
          $t_totconc    =   $t_totconc+1;
          if (Nvl(f($row,'valor'),0)<Nvl(f($row,'custo_real'),0)) {
            $t_acima=$t_acima+1;
            $t_totacima=$t_totacima+1;
          }
        } 
        $t_solic    = $t_solic + 1;
        $t_valor    = $t_valor + Nvl(f($row,'valor'),0);
        $t_custo    = $t_custo + Nvl(f($row,'custo_real'),0);
        $t_totvalor = $t_totvalor + Nvl(f($row,'valor'),0);
        $t_totcusto = $t_totcusto + Nvl(f($row,'custo_real'),0);
        $t_totsolic = $t_totsolic + 1;
        $w_qt_quebra= $w_qt_quebra + 1;
      }
      ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
      ShowHTML('      <tr bgcolor="#DCDCDC" valign="top" align="right">');
      ShowHTML('          <td><b>Totais</td>');
      ImprimeLinha($t_totsolic,$t_totcad,$t_tottram,$t_totconc,$t_totatraso,$t_totaviso,$t_totvalor,$t_totcusto,$t_totacima,-1);
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    if (count($RS1)>0 && $p_tipo=='N') {
      // Coloca o gráfico somente se o usuário desejar
      ShowHTML('<tr><td align="center" height=20>');
      ShowHTML('<tr><td align="center"><IMG SRC="'.$conPHP4.$w_dir.'geragrafico.php?p_genero=F&p_objeto='.f($RS_Menu,'nome').'&p_tipo='.$SG.'&p_grafico=Barra&p_tot='.$t_totsolic.'&p_cad='.$t_totcad.'&p_tram='.$t_tottram.'&p_conc='.$t_totconc.'&p_atraso='.$t_totatraso.'&p_aviso='.$t_totaviso.'&p_acima='.$t_totacima.'">');
      ShowHTML('<tr><td align="center" height=20>');
      if (($t_totcad+$t_tottram)>0) ShowHTML('<tr><td align="center"><IMG SRC="'.$conPHP4.$w_dir.'geragrafico.php?p_genero=F&p_objeto='.f($RS_Menu,'nome').'&p_tipo='.$SG.'&p_grafico=Pizza&p_tot='.$t_totsolic.'&p_cad='.$t_totcad.'&p_tram='.$t_tottram.'&p_conc='.$t_totconc.'&p_atraso='.$t_totatraso.'&p_aviso='.$t_totaviso.'&p_acima='.$t_totacima.'">');
    }
  } elseif (!(strpos('P',$O)===false)) {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    // Exibe parâmetros de apresentação
    ShowHTML('         <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Parâmetros de Apresentação</td>');
    ShowHTML('         <tr valign="top"><td colspan=2><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('          <td><b><U>A</U>gregar por:<br><SELECT ACCESSKEY="A" '.$w_Disabled.' class="STS" name="p_agrega" size="1">');
    switch ($p_agrega) {
      case 'GRISAACAO':     ShowHTML('          <option value="GRISARESPATU">Usuário atual<option value="GRISAACAO" selected>Ação<option value="GRISAPROP">Parcerias externas<option value="GRISARESP">Responsável monitoramento<option value="GRISASETOR">Área planejamento');     break;
      case 'GRISAPROP':     ShowHTML('          <option value="GRISARESPATU">Usuário atual<option value="GRISAACAO">Ação<option value="GRISAPROP" selected>Parcerias externas<option value="GRISARESP">Responsável monitoramento<option value="GRISASETOR">Área planejamento');     break;
      case 'GRISARESPATU':  ShowHTML('          <option value="GRISARESPATU" selected>Usuário atual<option value="GRISAACAO">Ação<option value="GRISAPROP">Parcerias externas<option value="GRISARESP">Responsável monitoramento<option value="GRISASETOR">Área planejamento');     break;
      case 'GRISASETOR':    ShowHTML('          <option value="GRISARESPATU">Usuário atual<option value="GRISAACAO">Ação<option value="GRISAPROP">Parcerias externas<option value="GRISARESP">Responsável monitoramento<option value="GRISASETOR" selected>Área planejamento');     break;
      default:              ShowHTML('          <option value="GRISARESPATU">Usuário atual<option value="GRISAACAO">Ação<option value="GRISAPROP">Parcerias externas<option value="GRISARESP" selected>Responsável monitoramento<option value="GRISASETOR">Área planejamento');     break;
    } 
    ShowHTML('          </select></td>');
    MontaRadioNS('<b>Inibe exibição do gráfico?</b>',$p_tipo,'p_tipo');
    MontaRadioSN('<b>Limita tamanho do assunto?</b>',$p_tamanho,'p_tamanho');
    ShowHTML('           </table>');
    ShowHTML('         </tr>');
    ShowHTML('         <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Critérios de Busca</td>');
    ShowHTML('      <tr><td colspan=2><table border=0 width="90%" cellspacing=0><tr valign="top">');
    $p_sq_acao_ppa = '';
    SelecaoAcaoPPA('<u>A</u>ção PPA:','A',null,$w_cliente,$w_ano,null,null,null,null,'p_sq_acao_ppa','CONSULTA',null,null,$w_menu,null,null);
    ShowHTML('          </table>');
    ShowHTML('      <tr><td colspan=2><table border=0 width="90%" cellspacing=0><tr valign="top">');
    SelecaoIsProjeto('<u>P</u>rograma interno:','P',null,$p_sq_isprojeto,null,'p_sq_isprojeto',null,null);
    ShowHTML('          <td valign="top"><b>Exibe somente ações com restrição?</b><br>');
    if ($p_qtd_restricao=='S') ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_qtd_restricao" value="S" checked> Sim <input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_qtd_restricao" value="N"> Não');
    else                       ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_qtd_restricao" value="S"> Sim <input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_qtd_restricao" value="N" checked> Não');
    ShowHTML('          </table>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td valign="top"><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="STI" type="text" name="p_prazo" size="2" maxlength="2" value="'.$p_prazo.'"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoPessoa('Respo<u>n</u>sável monitoramento:','N','Selecione o responsável pelo monitoramento da ação na relação.',$p_solicitante,null,'p_solicitante','USUARIOS');
    SelecaoUnidade_IS('Área planejamento:',null,'Selecione a unidade responsável pela ação.',$p_unidade,null,'p_unidade',null,'PLANEJAMENTO');
    ShowHTML('      <tr valign="top">');
    SelecaoPessoa('E<u>x</u>ecutor:','X','Selecione o executor da ação na relação.',$p_usu_resp,null,'p_usu_resp','USUARIOS');
    SelecaoUnidade('Setor atual:','Y','Selecione a unidade onde a ação se encontra na relação.',$p_uorg_resp,null,'p_uorg_resp',null,null);
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b>Parc<U>e</U>rias externas:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="'.$p_proponente.'"></td>');
    ShowHTML('          <td valign="top" colspan=2><b>Par<U>c</U>erias internas:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_palavra" size="25" maxlength="90" value="'.$p_palavra.'"></td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b>Data de re<u>c</u>ebimento entre:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
    ShowHTML('          <td valign="top"><b>Limi<u>t</u>e para conclusão entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b>Exibe somente ações em atraso?</b><br>');
    if ($p_atraso=='S') ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S" checked> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N"> Não');
    else                ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N" checked> Não');
    SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase[]',null,null);
    ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="2">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir" onClick="document.Form.target=\'\'; javascript:document.Form.O.value=\'L\';">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gerar Word" onClick="javascript:document.Form.O.value=\'W\'; document.Form.target=\'Word\'">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
    ShowHTML('</table>');
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
// Rotina de impressao do cabecalho
// -------------------------------------------------------------------------
function ImprimeCabecalho() {
  extract($GLOBALS);
  ShowHTML('<tr><td align="center">');
  ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr bgcolor="#DCDCDC" align="center">');
  switch ($p_agrega) {
    case 'GRISAACAO':   ShowHTML('          <td><b>Ação</td>');               break;
    case 'GRISAPROP':   ShowHTML('          <td><b>Parceria externa</td>');   break;
    case 'GRISARESP':   ShowHTML('          <td><b>Responsável</td>');        break;
    case 'GRISARESPATU':ShowHTML('          <td><b>Executor</td>');           break;
    case 'GRISASETOR':  ShowHTML('          <td><b>Área planejamento</td>');  break;
  } 
  ShowHTML('          <td><b>Total</td>');
  ShowHTML('          <td><b>Prog.</td>');
  ShowHTML('          <td><b>Mon.</td>');
  ShowHTML('          <td><b>Conc.</td>');
  ShowHTML('          <td><b>Atraso</td>');
  ShowHTML('          <td><b>Aviso</td>');
  ShowHTML('          <td><b>$ Prev.</td>');
  ShowHTML('          <td><b>$ Real</td>');
  ShowHTML('          <td><b>Real > Previsto</td>');
  ShowHTML('        </tr>');
} 
// =========================================================================
// Rotina de impressao da linha resumo
// -------------------------------------------------------------------------
function ImprimeLinha($l_solic,$l_cad,$l_tram,$l_conc,$l_atraso,$l_aviso,$l_valor,$l_custo,$l_acima,$l_chave) {
  extract($GLOBALS);
  if ($O=='L') ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, -1, -1);" onMouseOver="window.status=\'Exibe as ações.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_solic,0,',','.').'</a>&nbsp;</td>');                 else    ShowHTML('          <td align="right">'.number_format($l_solic,0,',','.').'&nbsp;</td>');
  if ($l_cad>0 && $O=='L') ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', 0, -1, -1, -1);" onMouseOver="window.status=\'Exibe as ações.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_cad,0,',','.').'</a>&nbsp;</td>');        else    ShowHTML('          <td align="right">'.number_format($l_cad,0,',','.').'&nbsp;</td>');
  if ($l_tram>0 && $O=='L')ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, 0, -1, -1);" onMouseOver="window.status=\'Exibe as ações.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_tram,0,',','.').'</a>&nbsp;</td>');       else    ShowHTML('          <td align="right">'.number_format($l_tram,0,',','.').'&nbsp;</td>');
  if ($l_conc>0 && $O=='L')ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, 0, -1);" onMouseOver="window.status=\'Exibe as ações.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_conc,0,',','.').'</a>&nbsp;</td>');       else    ShowHTML('          <td align="right">'.number_format($l_conc,0,',','.').'&nbsp;</td>');
  if ($l_atraso>0 && $O=='L') ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, -1, 0);" onMouseOver="window.status=\'Exibe as ações.\'; return true" onMouseOut="window.status=\'\'; return true"><font color="red"><b>'.number_format($l_atraso,0,',','.').'</a>&nbsp;</font></td>');   else    ShowHTML('          <td align="right"><b>'.$l_atraso.'&nbsp;</font></td>');
  if ($l_aviso>0 && $O=='L') ShowHTML('          <td align="right"><font color="red"><b>'.number_format($l_aviso,0,',','.').'&nbsp;</font></td>'); else ShowHTML('          <td align="right"><b>'.$l_aviso.'&nbsp;</td>');
  ShowHTML('          <td align="right">'.number_format($l_valor,2,',','.').'&nbsp;</td>');
  ShowHTML('          <td align="right">'.number_format($l_custo,2,',','.').'&nbsp;</td>');
  if ($l_acima>0)   ShowHTML('          <td align="right"><font color="red"><b>'.number_format($l_acima,0,',','.').'&nbsp;</font></td>');
  else              ShowHTML('          <td align="right"><b>'.$l_acima.'&nbsp;</td>');
  ShowHTML('        </tr>');
} 
// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  // Verifica se o usuário tem lotação e localização
  switch ($par) {
    case 'GERENCIAL':   Gerencial();    break;
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