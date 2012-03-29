<?php
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getCcData.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicSR.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicEtapa.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getOpiniao.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoRegiao.php');
include_once($w_dir_volta.'funcoes/selecaoEstado.php');
include_once($w_dir_volta.'funcoes/selecaoCidade.php');
include_once($w_dir_volta.'funcoes/selecaoPrioridade.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoEtapa.php');
include_once($w_dir_volta.'funcoes/selecaoServico.php');
include_once($w_dir_volta.'funcoes/selecaoOpiniao.php');

/* --
Adicionado César Martin em 04/06/2008
Funções que geram gráficos em flash:
*/
include_once($w_dir_volta.'funcoes/FusionCharts.php'); 
include_once($w_dir_volta.'funcoes/FC_Colors.php');
/* -- */

// =========================================================================
//  gr_demandaeventual.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerencia o módulo de demandas
// Mail     : alex@sbpi.com.br
// Criacao  : 15/10/2003 12:25
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
$P1         = $_REQUEST['P1'];
$P2         = $_REQUEST['P2'];
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);

$w_assinatura   = upper($_REQUEST['w_assinatura']);
$w_pagina       = 'gr_solic.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_sr/';
$w_troca        = upper($_REQUEST['w_troca']);

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON'] !='Sim') EncerraSessao();

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if ($O=='') $O='P';

switch ($O) {
  case 'V': $w_TP = $TP.' - Gráfico'; break;
  case 'P': $w_TP = $TP.' - Filtragem'; break;
  default : $w_TP = $TP.' - Listagem'; 
}

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = $P2;

$w_tipo          = upper($_REQUEST['w_tipo']);
$p_sq_menu       = upper($_REQUEST['p_sq_menu']);
$p_chave_pai     = upper($_REQUEST['p_chave_pai']);
$p_atividade     = upper($_REQUEST['p_atividade']);
$p_graf          = upper($_REQUEST['p_graf']);
$p_ativo         = upper($_REQUEST['p_ativo']);
$p_solicitante   = upper($_REQUEST['p_solicitante']);
$p_prioridade    = upper($_REQUEST['p_prioridade']);
$p_unidade       = upper($_REQUEST['p_unidade']);
$p_proponente    = upper($_REQUEST['p_proponente']);
$p_ordena        = lower($_REQUEST['p_ordena']);
$p_ini_i         = upper($_REQUEST['p_ini_i']);
$p_ini_f         = upper($_REQUEST['p_ini_f']);
$p_fim_i         = upper($_REQUEST['p_fim_i']);
$p_fim_f         = upper($_REQUEST['p_fim_f']);
$p_atraso        = upper($_REQUEST['p_atraso']);
$p_chave         = upper($_REQUEST['p_chave']);
$p_assunto       = upper($_REQUEST['p_assunto']);
$p_pais          = upper($_REQUEST['p_pais']);
$p_regiao        = upper($_REQUEST['p_regiao']);
$p_uf            = upper($_REQUEST['p_uf']);
$p_cidade        = upper($_REQUEST['p_cidade']);
$p_usu_resp      = upper($_REQUEST['p_usu_resp']);
$p_uorg_resp     = upper($_REQUEST['p_uorg_resp']);
$p_palavra       = upper($_REQUEST['p_palavra']);
$p_prazo         = upper($_REQUEST['p_prazo']);
$p_fase          = explodeArray($_REQUEST['p_fase']);
$p_sqcc          = upper($_REQUEST['p_sqcc']);
$p_agrega        = upper($_REQUEST['p_agrega']);
$p_tamanho       = upper($_REQUEST['p_tamanho']);
$p_sq_menu_relac = upper($_REQUEST['p_sq_menu_relac']);
$p_chave_pai     = upper($_REQUEST['p_chave_pai']);
$p_empenho       = lower($_REQUEST['p_empenho']);

// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$sql = new db_getLinkSubMenu; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu='Existe';
} else {
  $w_submenu='';
} 

// Recupera a configuração do serviço
$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);

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
  
  if ($O=='L' || $O=='V' || $w_tipo=='WORD' || $w_tipo=='PDF') {
    $w_filtro='';
    if ($p_sq_menu>'') {
      $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$p_sq_menu);
      $w_filtro .= '<tr valign="top"><td align="right">Serviço <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_chave>'') $w_filtro .= '<tr valign="top"><td align="right">Demanda nº <td>[<b>'.$p_chave.'</b>]';
    if ($p_prazo>'') $w_filtro .= ' <tr valign="top"><td align="right">Prazo para conclusão até<td>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]';
    if ($p_solicitante>'') {
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
      $w_filtro .= '<tr valign="top"><td align="right">Solicitante <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_unidade>'') {
      $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_unidade);
      $w_filtro .= '<tr valign="top"><td align="right">Setor solicitante <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_usu_resp>'') {
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_usu_resp,null,null);
      $w_filtro .= '<tr valign="top"><td align="right">Executor <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_uorg_resp>''){
      $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_uorg_resp);
      $w_filtro .= '<tr valign="top"><td align="right">Setor executor <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_pais>'') {
      $sql = new db_getCountryData; $RS = $sql->getInstanceOf($dbms,$p_pais);
      $w_filtro .= '<tr valign="top"><td align="right">País <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_regiao>'') {
      $sql = new db_getRegionData; $RS = $sql->getInstanceOf($dbms,$p_regiao);
      $w_filtro .= '<tr valign="top"><td align="right">Região <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_uf>'') {
      $sql = new db_getStateData; $RS = $sql->getInstanceOf($dbms,$p_pais,$p_uf);
      $w_filtro .= '<tr valign="top"><td align="right">Estado <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_cidade>'') {
      $sql = new db_getCityData; $RS = $sql->getInstanceOf($dbms,$p_cidade);
      $w_filtro .= '<tr valign="top"><td align="right">Cidade <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_prioridade>'') {
      $sql = new db_getOpiniao; $RS = $sql->getInstanceOf($dbms,null,$w_cliente,null,$p_prioridade, null);
      foreach ($RS as $row) { $RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Opinião <td>[<b>'.f($RS,'nome').'</b>]';
    }
    if ($p_proponente>'') $w_filtro .= '<tr valign="top"><td align="right">Proponente <td>[<b>'.$p_proponente.'</b>]';
    if ($p_assunto>'')    $w_filtro .= '<tr valign="top"><td align="right">Detalhamento <td>[<b>'.$p_assunto.'</b>]';
    if ($p_palavra>'')    $w_filtro .= '<tr valign="top"><td align="right">Palavras-chave <td>[<b>'.$p_palavra.'</b>]';
    if ($p_ini_i>'')      $w_filtro .= '<tr valign="top"><td align="right">Data programada <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
    if ($p_fim_i>'')      $w_filtro .= '<tr valign="top"><td align="right">Data conclusão <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]';
    if ($p_atraso=='S')   $w_filtro .= '<tr valign="top"><td align="right">Situação <td>[<b>Apenas atrasadas</b>]';
    if ($w_filtro>'')     $w_filtro  = '<div align="left"><table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table></div>';
    
    $sql = new db_getSolicSR; $RS1 = $sql->getInstanceOf($dbms, $w_cliente, nvl($p_sq_menu,0),
        $w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
        $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
        $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, null, null, null, null, null);
    
    switch ($p_agrega) {
      case 'GRSRUNID':
        $w_TP = $TP.' - Por setor solicitante';
        $RS1  = SortArray($RS1,'nm_unidade_resp','asc');
        break;
      case 'GRSRPROJ':
        $w_TP = $TP.' - Por projeto';
        $RS1 = SortArray($RS1,'nm_projeto','asc');
        break;
      case 'GRSRPROP':
        $w_TP = $TP.' - Por proponente';
        $RS1  = SortArray($RS1,'proponente','asc');
        break;
      case 'GRSRRESP':
        $w_TP = $TP.' - Por solicitante';
        $RS1  = SortArray($RS1,'nm_solic_ind','asc');
        break;
      case 'GRSRRESPATU':
        $w_TP = $TP.' - Por opinião';
        $RS1  = SortArray($RS1,'or_opiniao','asc');
        break;
      case 'GRSRCC':
        $w_TP = $TP.' - Por classificação';
        $RS1  = SortArray($RS1,'sg_cc','asc');
        break;
      case 'GRSRSETOR':
        $w_TP = $TP.' - Por setor executor';
        $RS1  = SortArray($RS1,'nm_unidade_exec','asc');
        break;
      case 'GRSRPRIO':
        $w_TP = $TP.' - Por serviço';
        $RS1  = SortArray($RS1,'nome','asc');
        break;
      case 'GRSRLOCAL':
        $w_TP = $TP.' - Por UF';
        $RS1  = SortArray($RS1,'co_uf','asc');
        break;
      case 'GRSREXEC':
        $w_TP = $TP.' - Por executor';
        $RS1  = SortArray($RS1,'nm_exec','asc');
        break;
    } 
  } 
  $w_linha_filtro = $w_linha;
  if ($w_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 45: 30);
    CabecalhoWord($w_cliente,$w_TP,$w_pag);
    $w_embed = 'WORD';
    if ($w_filtro>'') ShowHTML($w_filtro);
  }elseif($w_tipo == 'PDF'){
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 60: 35);
    $w_embed = 'WORD';
    HeaderPdf('Consulta de '.f($RS_Menu,'nome').$w_TP,$w_pag);
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
      Validate('p_chave','Número da solicitação','','','1','18','','0123456789');
      Validate('p_assunto','descricao','','','2','90','1','1');
      Validate('p_ini_i','Programação inicial','DATA','','10','10','','0123456789/');
      Validate('p_ini_f','Programação final','DATA','','10','10','','0123456789/');
      ShowHTML('  if ((theForm.p_ini_i.value != \'\' && theForm.p_ini_f.value == \'\') || (theForm.p_ini_i.value == \'\' && theForm.p_ini_f.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas do período ou nenhuma delas!\');');
      ShowHTML('     theForm.p_ini_i.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      CompData('p_ini_i','Início pedido','<=','p_ini_f','Fim pedido');
      Validate('p_fim_i','Conclusão inicial','DATA','','10','10','','0123456789/');
      Validate('p_fim_f','Conclusão final','DATA','','10','10','','0123456789/');
      ShowHTML('  if ((theForm.p_fim_i.value != \'\' && theForm.p_fim_f.value == \'\') || (theForm.p_fim_i.value == \'\' && theForm.p_fim_f.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas do período ou nenhuma delas!\');');
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
    if ($w_Troca>'') {
      // Se for recarga da página
      BodyOpen('onLoad=\'document.Form.'.$w_Troca.'.focus();\'');
    } elseif ($O=='P') {
      if ($P1==1) {
        // Se for cadastramento
        BodyOpen('onLoad=\'document.Form.p_ordena.focus()\';');
      } else {
        BodyOpen('onLoad=\'document.Form.p_agrega.focus()\';');
      } 
    } else {
      BodyOpenClean('onLoad=\'this.focus();\'');
    } 
    if ($O=='L') {
      CabecalhoRelatorio($w_cliente,upper(str_replace($TP.' -','CONSULTA',$w_TP)),4);
      ShowHTML('<HR>');
      if ($w_filtro>'') ShowHTML($w_filtro);
    } else {
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
      ShowHTML('<HR>');
    } 
  } 
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L' || $w_embed == 'WORD') {
    if ($O=='L' && $w_embed != 'WORD') {
      ShowHTML('<tr><td>');
      if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
        ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
      } else {
        ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
      } 
    } 
    ImprimeCabecalho();
    if (count($RS1)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      if ($O=='L' && $w_embed != 'WORD') {
        ShowHTML('<SCRIPT LANGUAGE="JAVASCRIPT">');
        ShowHTML('  function lista (filtro, cad, exec, conc, atraso) {');
        ShowHTML('    if (filtro != -1) {');
        switch ($p_agrega) {
          case 'GRSRUNID':      ShowHTML('      document.Form.p_unidade.value=filtro;');        break;
          case 'GRSRPROJ':      ShowHTML('      document.Form.p_chave_pai.value=filtro;');      break;
          case 'GRSRPROP':      ShowHTML('      document.Form.p_proponente.value=filtro;');     break;
          case 'GRSRRESP':      ShowHTML('      document.Form.p_solicitante.value=filtro;');    break;
          case 'GRSRRESPATU':   ShowHTML('      document.Form.p_prioridade.value=filtro;');     break;
          case 'GRSRCC':        ShowHTML('      document.Form.p_sqcc.value=filtro;');           break;
          case 'GRSRSETOR':     ShowHTML('      document.Form.p_uorg_resp.value=filtro;');      break;
          case 'GRSRPRIO':      ShowHTML('      document.Form.p_sq_menu.value=filtro;');        break;
          case 'GRSRLOCAL':     ShowHTML('      document.Form.p_uf.value=filtro;');             break;
          case 'GRSREXEC':      ShowHTML('      document.Form.p_usu_resp.value=filtro;');       break;
        } 
        ShowHTML('    }');
        switch ($p_agrega) {
          case 'GRSRUNID':      ShowHTML('    else document.Form.p_unidade.value=\''.$_REQUEST['p_unidade'].'\';');           break;
          case 'GRSRPROJ':      ShowHTML('    else document.Form.p_chave_pai.value=\''.$_REQUEST['p_chave_pai'].'\';');       break;
          case 'GRSRPROP':      ShowHTML('    else document.Form.p_proponente.value=\''.$_REQUEST['p_proponente'].'\';');     break;
          case 'GRSRRESP':      ShowHTML('    else document.Form.p_solicitante.value=\''.$_REQUEST['p_solicitante'].'\';');   break;
          case 'GRSRRESPATU':   ShowHTML('    else document.Form.p_prioridade.value=\''.$_REQUEST['p_prioridade'].'\';');     break;
          case 'GRSRCC':        ShowHTML('    else document.Form.p_sqcc.value=\''.$_REQUEST['p_sqcc'].'\';');                 break;
          case 'GRSRSETOR':     ShowHTML('    else document.Form.p_uorg_resp.value=\''.$_REQUEST['p_uorg_resp'].'\';');       break;
          case 'GRSRPRIO':      ShowHTML('    else document.Form.p_sq_menu.value=\''.$_REQUEST['p_sq_menu'].'\';');           break;
          case 'GRSRLOCAL':     ShowHTML('    else document.Form.p_uf.value=\''.$_REQUEST['p_uf'].'\';');                     break;
          case 'GRSREXEC':      ShowHTML('    else document.Form.p_usu_resp.value=\''.$_REQUEST['p_usu_resp'].'\';');         break;
        } 
        if ($p_sq_menu>'') {
          $sql = new db_getTramiteList; $RS2 = $sql->getInstanceOf($dbms,$p_sq_menu,null,null,null);
          $RS2  = SortArray($RS2,'ordem','asc');
          $w_fase_exec = '';
          foreach($RS2 as $row) {
            if (f($row,'sigla')=='CI') {
              $w_fase_cad = f($row,'sq_siw_tramite');
            } elseif (f($row,'sigla')=='AT') {
              $w_fase_conc = f($row,'sq_siw_tramite');
            } elseif (f($row,'ativo')=='S') {
              $w_fase_exec = $w_fase_exec.','.f($row,'sq_siw_tramite');
            } 
          } 
          ShowHTML('    if (cad >= 0) document.Form.p_fase.value='.$w_fase_cad.';');
          ShowHTML('    if (exec >= 0) document.Form.p_fase.value=\''.substr($w_fase_exec,1,100).'\';');
          ShowHTML('    if (conc >= 0) document.Form.p_fase.value='.$w_fase_conc.';');
          ShowHTML('    if (cad==-1 && exec==-1 && conc==-1) document.Form.p_fase.value=\''.$p_fase.'\'; ');
        }
        ShowHTML('    if (atraso >= 0) document.Form.p_atraso.value=\'S\'; else document.Form.p_atraso.value=\''.$_REQUEST['p_atraso'].'\'; ');
        ShowHTML('    document.Form.submit();');
        ShowHTML('  }');
        ShowHTML('</SCRIPT>');
        AbreForm('Form',$conRootSIW.'mod_sr/geral.php?par=Inicial','POST','return(Validacao(this));','Lista',3,$P2,$P3,null,$w_TP,$SG,$w_pagina.$par,'L');
        ShowHTML(MontaFiltro('POST'));
        switch ($p_agrega) {
          case 'GRSRUNID':     if ($_REQUEST['p_unidade']=='')      ShowHTML('<input type="Hidden" name="p_unidade" value="">');      break;
          case 'GRSRPROJ':      if ($_REQUEST['p_chave_pai']=='')   ShowHTML('<input type="Hidden" name="p_chave_pai" value="">');    break;
          case 'GRSRPROP':      if ($_REQUEST['p_proponente']=='')  ShowHTML('<input type="Hidden" name="p_proponente" value="">');   break;
          case 'GRSRRESP':      if ($_REQUEST['p_solicitante']=='') ShowHTML('<input type="Hidden" name="p_solicitante" value="">');  break;
          case 'GRSRRESPATU':   if ($_REQUEST['p_prioridade']=='')  ShowHTML('<input type="Hidden" name="p_prioridade" value="">');   break;
          case 'GRSRCC':        if ($_REQUEST['p_sqcc']=='')        ShowHTML('<input type="Hidden" name="p_sqcc" value="">');         break;
          case 'GRSRSETOR':     if ($_REQUEST['p_uorg_resp']=='')   ShowHTML('<input type="Hidden" name="p_uorg_resp" value="">');    break;
          case 'GRSRPRIO':      if ($_REQUEST['p_sq_menu']=='')     ShowHTML('<input type="Hidden" name="p_sq_menu" value="">');      break;
          case 'GRSRLOCAL':     if ($_REQUEST['p_uf']=='')          ShowHTML('<input type="Hidden" name="p_uf" value="">');           break;
          case 'GRSREXEC':      if ($_REQUEST['p_usu_resp']=='')    ShowHTML('<input type="Hidden" name="p_usu_resp" value="">');     break;
        } 
      } 
      $w_nm_quebra  = '';
      $w_qt_quebra  = 0;
      $t_solic      = 0;
      $t_cad        = 0;
      $t_tram       = 0;
      $t_conc       = 0;
      $t_atraso     = 0;
      $t_aviso      = 0;
      $t_valor      = 0;
      $t_acima      = 0;
      $t_custo      = 0;
      $t_totcusto   = 0;
      $t_totsolic   = 0;
      $t_totcad     = 0;
      $t_tottram    = 0;
      $t_totconc    = 0;
      $t_totatraso  = 0;
      $t_totaviso   = 0;
      $t_totvalor   = 0;
      $t_totacima   = 0;
      foreach($RS1 as $row) {
        switch ($p_agrega) {
          case 'GRSRUNID':
            if ($w_nm_quebra!=f($row,'nm_unidade_resp')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_unidade_resp'));
              } 
              $w_nm_quebra  = f($row,'nm_unidade_resp');
              $w_chave      = f($row,'sq_unidade');
              $w_qt_quebra  = 0;
              $t_solic      = 0;
              $t_cad        = 0;
              $t_tram       = 0;
              $t_conc       = 0;
              $t_atraso     = 0;
              $t_aviso      = 0;
              $t_valor      = 0;
              $t_acima      = 0;
              $t_custo      = 0;
            } 
            break;
          case 'GRSRPROJ':
            if ($w_nm_quebra!=f($row,'nm_projeto')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_projeto'));
              } 
              $w_nm_quebra  =  f($row,'nm_projeto');
              $w_chave      = f($row,'sq_solic_pai');
              $w_qt_quebra  = 0;
              $t_solic      = 0;
              $t_cad        = 0;
              $t_tram       = 0;
              $t_conc       = 0;
              $t_atraso     = 0;
              $t_aviso      = 0;
              $t_valor      = 0;
              $t_acima      = 0;
              $t_custo      = 0;
            } 
            break;
          case 'GRSRPROP':
            if ($w_nm_quebra!=f($row,'proponente')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'proponente'));
              } 
              $w_nm_quebra  = f($row,'proponente');
              $w_chave      = f($row,'proponente');
              $w_qt_quebra  = 0;
              $t_solic      = 0;
              $t_cad        = 0;
              $t_tram       = 0;
              $t_conc       = 0;
              $t_atraso     = 0;
              $t_aviso      = 0;
              $t_valor      = 0;
              $t_acima      = 0;
              $t_custo      = 0;
            } 
            break;
          case 'GRSRRESP':
            if ($w_nm_quebra!=f($row,'nm_solic')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_solic'));
              } 
              $w_nm_quebra  = f($row,'nm_solic');
              $w_chave      = f($row,'solicitante');
              $w_qt_quebra  = 0;
              $t_solic      = 0;
              $t_cad        = 0;
              $t_tram       = 0;
              $t_conc       = 0;
              $t_atraso     = 0;
              $t_aviso      = 0;
              $t_valor      = 0;
              $t_acima      = 0;
              $t_custo      = 0;
            } 
            break;
          case 'GRSRRESPATU': 
            if ($w_nm_quebra!=f($row,'or_opiniao')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_opiniao'));
              } 
              $w_nm_quebra  = f($row,'or_opiniao');
              $w_chave      = f($row,'sg_opiniao');
              $w_qt_quebra  = 0;
              $t_solic      = 0;
              $t_cad        = 0;
              $t_tram       = 0;
              $t_conc       = 0;
              $t_atraso     = 0;
              $t_aviso      = 0;
              $t_valor      = 0;
              $t_acima      = 0;
              $t_custo      = 0;
            } 
            break;
          case 'GRSRCC':
            if ($w_nm_quebra!=f($row,'sg_cc')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'sg_cc'));
              } 
              $w_nm_quebra  = f($row,'sg_cc');
              $w_chave      = f($row,'sq_cc');
              $w_qt_quebra  = 0;
              $t_solic      = 0;
              $t_cad        = 0;
              $t_tram       = 0;
              $t_conc       = 0;
              $t_atraso     = 0;
              $t_aviso      = 0;
              $t_valor      = 0;
              $t_acima      = 0;
              $t_custo      = 0;
            } 
            break;
          case 'GRSRSETOR':
            if ($w_nm_quebra!=f($row,'nm_unidade_exec')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_unidade_exec'));
              } 
              $w_nm_quebra  = f($row,'nm_unidade_exec');
              $w_chave      = f($row,'sq_unid_executora');
              $w_qt_quebra  = 0;
              $t_solic      = 0;
              $t_cad        = 0;
              $t_tram       = 0;
              $t_conc       = 0;
              $t_atraso     = 0;
              $t_aviso      = 0;
              $t_valor      = 0;
              $t_acima      = 0;
              $t_custo      = 0;
            } 
            break;
          case 'GRSRPRIO':
            if ($w_nm_quebra!=f($row,'nome')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nome'));
              } 
              $w_nm_quebra  = f($row,'nome');
              $w_chave      = f($row,'sq_menu');
              $w_qt_quebra  = 0;
              $t_solic      = 0;
              $t_cad        = 0;
              $t_tram       = 0;
              $t_conc       = 0;
              $t_atraso     = 0;
              $t_aviso      = 0;
              $t_valor      = 0;
              $t_acima      = 0;
              $t_custo      = 0;
            } 
            break;
          case 'GRSRLOCAL':
            if ($w_nm_quebra!=f($row,'co_uf')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'co_uf'));
              } 
              $w_nm_quebra  = f($row,'co_uf');
              $w_chave      = f($row,'co_uf');
              $w_qt_quebra  = 0;
              $t_solic      = 0;
              $t_cad        = 0;
              $t_tram       = 0;
              $t_conc       = 0;
              $t_atraso     = 0;
              $t_aviso      = 0;
              $t_valor      = 0;
              $t_acima      = 0;
              $t_custo      = 0;
            } 
            break;
          case 'GRSREXEC':
            if ($w_nm_quebra!=f($row,'nm_exec')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_exec'));
              } 
              $w_nm_quebra  = f($row,'nm_exec');
              $w_chave      = f($row,'executor');
              $w_qt_quebra  = 0;
              $t_solic      = 0;
              $t_cad        = 0;
              $t_tram       = 0;
              $t_conc       = 0;
              $t_atraso     = 0;
              $t_aviso      = 0;
              $t_valor      = 0;
              $t_acima      = 0;
              $t_custo      = 0;
            } 
            break;
        } 
        if ($w_embed == 'WORD' && $w_linha>$w_linha_pag) {
          // Se for geração de MS-Word, quebra a página
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
          ShowHTML('</table>');
          ShowHTML('</center></div>');
          if ($w_tipo=='PDF') ShowHTML('    <pd4ml:page.break>');
          else                ShowHTML('    <br style="page-break-after:always">');
          $w_linha=$w_linha_filtro;
          $w_pag=$w_pag + 1;
          CabecalhoWord($w_cliente,$w_TP,$w_pag);
          if ($w_filtro>'') ShowHTML($w_filtro);
          ShowHTML('<div align=center><center>');
          ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
          ImprimeCabecalho();
          switch ($p_agrega) {
            case 'GRSRUNID':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_unidade_resp'));   break;
            case 'GRSRPROJ':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_projeto'));        break;
            case 'GRSRPROP':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'proponente'));        break;
            case 'GRSRRESP':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_solic'));          break;
            case 'GRSRRESPATU':     ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_opiniao'));        break;
            case 'GRSRCC':          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'sg_cc'));             break;
            case 'GRSRSETOR':       ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_unidade_exec'));   break;
            case 'GRSRPRIO':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nome'));              break;
            case 'GRSRLOCAL':       ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'co_uf'));             break;
            case 'GRSREXEC':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_exec'));           break;
          } 
          $w_linha = $w_linha + 1;
        } 
        if (f($row,'sg_tramite')!='AT') {
          if (Nvl(f($row,'fim'),time()) < addDays(time(),-1)) {
            $t_atraso    = $t_atraso + 1;
            $t_totatraso = $t_totatraso + 1;
          } elseif (f($row,'aviso_prox_conc') == 'S' && (f($row,'aviso') <= addDays(time(),((f($row,'data_hora')==1) ? 0 : -1)))) {
            $t_aviso    = $t_aviso+1;
            $t_totaviso = $t_totaviso+1;
          }
          if (f($row,'or_tramite')==1) {
            $t_cad      = $t_cad + 1;
            $t_totcad   = $t_totcad + 1;
          } else {
            $t_tram     = $t_tram + 1;
            $t_tottram  = $t_tottram + 1;
          } 
        } else {
          $t_conc       = $t_conc + 1;
          $t_totconc    = $t_totconc + 1;
          if (nvl(f($row,'valor'),0)<nvl(f($row,'custo_real'),0)) {
            $t_acima    = $t_acima + 1;
            $t_totacima = $t_totacima + 1;
          } 
        } 
        $t_solic        = $t_solic + 1;
        $t_valor        = $t_valor + Nvl(f($row,'valor'),0);
        $t_custo        = $t_custo + Nvl(f($row,'custo_real'),0);
        $t_totvalor     = $t_totvalor + Nvl(f($row,'valor'),0);
        $t_totcusto     = $t_totcusto + Nvl(f($row,'custo_real'),0);
        $t_totsolic     = $t_totsolic + 1;
        $w_qt_quebra    = $w_qt_quebra + 1;
      } 
      ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
      ShowHTML('      <tr bgcolor="#DCDCDC" valign="top" align="right">');
      ShowHTML('          <td><b>Totais</td>');
      ImprimeLinha($t_totsolic,$t_totcad,$t_tottram,$t_totconc,$t_totatraso,$t_totaviso,$t_totvalor,$t_totcusto,$t_totacima,-1);
    } 
    if ($w_embed != 'WORD') ShowHTML('      </FORM>');
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    if (count($RS1)>0 && $p_graf=='N') {
        if($w_embed != 'WORD'){
      include_once($w_dir_volta.'funcoes/geragraficoflash.php');
          // Coloca o gráfico somente se o usuário desejar
          ShowHTML('<tr><td align="center" height=20>');
          //ShowHTML('<tr><td align="center"><IMG SRC="'.$conPHP4.'geragrafico.php?p_genero=F&p_objeto='.f($RS_Menu,'nome').'&p_graf='.$SG.'&p_grafico=Barra&p_tot='.$t_totsolic.'&p_cad='.$t_totcad.'&p_tram='.$t_tottram.'&p_conc='.$t_totconc.'&p_atraso='.$t_totatraso.'&p_aviso='.$t_totaviso.'&p_acima='.$t_totacima.'">');
          ShowHTML('<tr><td align="center" height=20>');
        barra_flash(array(genero => "F", "nome" =>  f($RS_Menu,'nome'), "total" => $t_totsolic, "cadastramento" => $t_totcad, "execucao" => $t_tottram, "concluidos" => $t_totconc, "atrasados" => $t_totatraso, "aviso" => $t_totaviso, "acima" => $t_totacima), "barra");
          if (($t_totcad + $t_tottram)>0) {
            //ShowHTML('<tr><td align="center"><IMG SRC="'.$conPHP4.'geragrafico.php?p_genero=F&p_objeto='.f($RS_Menu,'nome').'&p_graf='.$SG.'&p_grafico=Pizza&p_tot='.$t_totsolic.'&p_cad='.$t_totcad.'&p_tram='.$t_tottram.'&p_conc='.$t_totconc.'&p_atraso='.$t_totatraso.'&p_aviso='.$t_totaviso.'&p_acima='.$t_totacima.'">');
        pizza_flash(array(genero => "F", "nome" =>  f($RS_Menu,'nome'), "total" => $t_totsolic, "cadastramento" => nvl($t_totcad,0), "execucao" => nvl($t_tottram,0), "concluidos" => nvl($t_totconc,0), "atrasados" => nvl($t_totatraso,0), "aviso" => nvl($t_totaviso,0), "acima" => nvl($t_totacima,0)), "pizza");
          } 
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
    ShowHTML('         <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Parâmetros de Apresentação</td>');
    ShowHTML('         <tr valign="top"><td colspan=2><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('          <td><b><U>A</U>gregar por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="STS" name="p_agrega" size="1">');
    if (f($RS_Menu,'solicita_cc')=='S') {
      if ($p_agrega=='GRSRCC')      ShowHTML('          <option value="GRSRCC" selected>Classificação');              else ShowHTML('          <option value="GRSRCC">Classificação');
    } 
    if ($p_agrega=='GRSREXEC')      ShowHTML('          <option value="GRSREXEC" selected>Executor');                 else ShowHTML('          <option value="GRSREXEC">Executor');
    if ($p_agrega=='GRSRRESPATU')   ShowHTML('          <option value="GRSRRESPATU" selected>Opinião');               else ShowHTML('          <option value="GRSRRESPATU">Opinião');
    if ($p_agrega=='GRSRPRIO')      ShowHTML('          <option value="GRSRPRIO" selected>Serviço');                  else ShowHTML('          <option value="GRSRPRIO">Serviço');
    //if ($p_agrega=='GRSRPROP')      ShowHTML('          <option value="GRSRPROP" selected>Proponente');               else ShowHTML('          <option value="GRSRPROP">Proponente');
    if ($SG=='PROJETO') {
      if ($p_agrega=='GRSRPROJ')    ShowHTML('          <option value="GRSRPROJ" selected>Projeto');                  else ShowHTML('          <option value="GRSRPROJ">Projeto');
    } 
    if ($p_agrega=='GRSRSETOR')     ShowHTML('          <option value="GRSRSETOR" selected>Setor executor');          else ShowHTML('          <option value="GRSRSETOR">Setor executor');
    if ($p_agrega=='GRSRUNID')      ShowHTML('          <option value="GRSRUNID" selected>Setor solicitante');        else ShowHTML('          <option value="GRSRUNID">Setor solicitante');
    if (Nvl($p_agrega,'GRSRRESP')=='GRSRRESP') ShowHTML('          <option value="GRSRRESP" selected>Solicitante');   else ShowHTML('          <option value="GRSRRESP">Solicitante');
    //if ($p_agrega=='GRSRLOCAL')     ShowHTML('          <option value="GRSRLOCAL" selected>UF');                      else ShowHTML('          <option value="GRSRLOCAL">UF');
    ShowHTML('          </select></td>');
    MontaRadioNS('<b>Inibe exibição do gráfico?</b>',$p_graf,'p_graf');
    //MontaRadioSN('<b>Limita tamanho do detalhamento?</b>',$p_tamanho,'p_tamanho');
    ShowHTML('           </table>');
    ShowHTML('         </tr>');
    ShowHTML('         <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Critérios de Busca</td>');
    // Se a opção for ligada ao módulo de projetos, permite a seleção do projeto  e da etapa
    ShowHTML('      <tr valign="top">');
    selecaoServico('<U>S</U>erviço:', 'S', null, $p_sq_menu, null, 'SR', 'p_sq_menu', null, 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_sq_menu\'; document.Form.submit();"', null, null, null);
    ShowHTML('          <td valign="top"><b>Número <U>d</U>a solicitação:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="p_chave" size="18" maxlength="18" value="'.$p_chave.'"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoPessoa('E<u>x</u>ecutor:','X','Selecione o executor na relação.',$p_usu_resp,null,'p_usu_resp','EXECUTORSR');
    SelecaoUnidade('<U>S</U>etor executor:','S','Selecione a unidade executora do serviço na relação.',$p_uorg_resp,null,'p_uorg_resp',null,null);
    ShowHTML('      <tr valign="top">');
    SelecaoPessoa('Usuário solicita<u>n</u>te:','N','Selecione o solicitante na relação.',$p_solicitante,null,'p_solicitante','USUARIOS');
    SelecaoUnidade('<U>S</U>etor solicitante:','S',null,$p_unidade,null,'p_unidade',null,null);
    ShowHTML('      <tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td valign="top"><b>Deta<U>l</U>hamento:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="p_assunto" size="25" maxlength="90" value="'.$p_assunto.'"></td>');
    selecaoOpiniao('Exibir somente opiniões do tipo:',null,null,$p_prioridade,$w_cliente,'p_prioridade',null,'SELECT');
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b>Da<u>t</u>a programada entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_ini_i').' e <input '.$w_Disabled.' accesskey="T" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_ini_f').'</td>');
    ShowHTML('          <td valign="top"><b>Da<u>t</u>a da conclusão entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_i').' e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_f').'</td>');
    // Se não for cópia
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b>Exibe somente solicitações em atraso?</b><br>');
    if ($p_atraso=='S') {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S" checked> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N"> Não');
    } else {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N" checked> Não');
    } 

    SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$p_sq_menu,'p_fase[]',null,null);
    ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="2">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir resumo" onClick="document.Form.target=\'\'; javascript:document.Form.O.value=\'L\'; document.Form.target=\'\';">');
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
  if($w_tipo == 'PDF') RodapePdf();
  else Rodape();
  
} 

// =========================================================================
// Rotina de impressao do cabecalho
// -------------------------------------------------------------------------
function ImprimeCabecalho() {
  extract($GLOBALS);

  ShowHTML('<tr><td align="center">');
  ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr bgcolor="#DCDCDC" align="center">');
  switch ($p_agrega) {
    case 'GRSRUNID':    ShowHTML('          <td><b>Setor solicitante</td>');    break;
    case 'GRSRPROJ':    ShowHTML('          <td><b>Projeto</td>');              break;
    case 'GRSRPROP':    ShowHTML('          <td><b>Proponente</td>');           break;
    case 'GRSRRESP':    ShowHTML('          <td><b>Solicitante</td>');          break;
    case 'GRSRRESPATU': ShowHTML('          <td><b>Opinião</td>');              break;
    case 'GRSRCC':      ShowHTML('          <td><b>Classificação</td>');        break;
    case 'GRSRSETOR':   ShowHTML('          <td><b>Setor executor</td>');       break;
    case 'GRSRPRIO':    ShowHTML('          <td><b>Serviço</td>');              break;
    case 'GRSRLOCAL':   ShowHTML('          <td><b>UF</td>');                   break;
    case 'GRSREXEC':    ShowHTML('          <td><b>Executor</td>');             break;
  } 
  ShowHTML('          <td><b>Total</td>');
  ShowHTML('          <td><b>Cad.</td>');
  ShowHTML('          <td><b>Exec.</td>');
  ShowHTML('          <td><b>Conc.</td>');
  ShowHTML('          <td><b>Atraso</td>');
  ShowHTML('          <td><b>Aviso</td>');
  if ($_SESSION['INTERNO']=='S') {
    ShowHTML('          <td><b>Valor</td>');
    //ShowHTML('          <td><b>$ Real</td>');
    //ShowHTML('          <td><b>Real > Previsto</td>');
  } 
  ShowHTML('        </tr>');
} 

// =========================================================================
// Rotina de impressao da linha resumo
// -------------------------------------------------------------------------
function ImprimeLinha($l_solic,$l_cad,$l_tram,$l_conc,$l_atraso,$l_aviso,$l_valor,$l_custo,$l_acima,$l_chave) {
  extract($GLOBALS);
  if($w_tipo == 'PDF' || $w_tipo == 'WORD'){
    $w_embed = 'WORD';  
  }

  if ($w_embed != 'WORD')                  ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, -1, -1);" onMouseOver="window.status=\'Exibe as demandas.\'; return true" onMouseOut="window.status=\'\'; return true">'.formatNumber($l_solic,0).'</a>&nbsp;</td>');                  else ShowHTML('          <td align="right">'.formatNumber($l_solic,0).'&nbsp;</td>');
  if ($l_cad>0 && $w_embed != 'WORD' && $p_sq_menu>'')      ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', 0, -1, -1, -1);" onMouseOver="window.status=\'Exibe as demandas.\'; return true" onMouseOut="window.status=\'\'; return true">'.formatNumber($l_cad,0).'</a>&nbsp;</td>');                     else ShowHTML('          <td align="right">'.formatNumber($l_cad,0).'&nbsp;</td>');
  if ($l_tram>0 && $w_embed != 'WORD' && $p_sq_menu>'')     ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, 0, -1, -1);" onMouseOver="window.status=\'Exibe as demandas.\'; return true" onMouseOut="window.status=\'\'; return true">'.formatNumber($l_tram,0).'</a>&nbsp;</td>');                    else ShowHTML('          <td align="right">'.formatNumber($l_tram,0).'&nbsp;</td>');
  if ($l_conc>0 && $w_embed != 'WORD' && $p_sq_menu>'')     ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, 0, -1);" onMouseOver="window.status=\'Exibe as demandas.\'; return true" onMouseOut="window.status=\'\'; return true">'.formatNumber($l_conc,0).'</a>&nbsp;</td>');                    else ShowHTML('          <td align="right">'.formatNumber($l_conc,0).'&nbsp;</td>');
  if ($l_atraso>0 && $w_embed != 'WORD')   ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, -1, 0);" onMouseOver="window.status=\'Exibe as demandas.\'; return true" onMouseOut="window.status=\'\'; return true"><font color="red"><b>'.formatNumber($l_atraso,0).'</a>&nbsp;</td>'); else ShowHTML('          <td align="right"><b>'.$l_atraso.'&nbsp;</td>');
  if ($l_aviso>0 && $w_embed != 'WORD')    ShowHTML('          <td align="right"><font color="red"><b>'.formatNumber($l_aviso,0).'&nbsp;</td>');                                                                                                                                                                                             else ShowHTML('          <td align="right"><b>'.$l_aviso.'&nbsp;</td>');
  if ($_SESSION['INTERNO']=='S') {
    ShowHTML('          <td align="right">'.formatNumber($l_valor,2).'&nbsp;</td>');
    //ShowHTML('          <td align="right">'.formatNumber($l_custo,2).'&nbsp;</td>');
    //if ($l_acima>0) ShowHTML('          <td align="right"><font color="red"><b>'.formatNumber($l_acima,0).'&nbsp;</td>'); else ShowHTML('          <td align="right"><b>'.$l_acima.'&nbsp;</td>');
  } 
  ShowHTML('        </tr>');
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);

  switch ($par) {
    case 'GERENCIAL': Gerencial(); break;
    default:
    Cabecalho();
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


