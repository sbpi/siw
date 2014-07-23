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
include_once($w_dir_volta.'classes/sp/db_getCcData.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getCountryData.php');
include_once($w_dir_volta.'classes/sp/db_getRegionData.php');
include_once($w_dir_volta.'classes/sp/db_getStateData.php');
include_once($w_dir_volta.'classes/sp/db_getCityData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicCL.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicEtapa.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoEtapa.php');
include_once($w_dir_volta.'funcoes/selecaoTipoMatServSubord.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
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
//  gr_pedido.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Gerencia o módulo de passagens e diárias
// Mail     : celso@sbpi.com.br
// Criação  : 26/05/2006 10:00
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

$w_assinatura   = $_REQUEST['w_assinatura'];
$w_pagina       = 'gr_pedido.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'cl_unesco/';
$w_troca        = $_REQUEST['w_troca'];
$w_embed        = '';

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

// Verifica se o cliente tem o módulo de projetos
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PR');
if (count($RS)>0) $w_pr='S'; else $w_pr='N'; 

// Verifica se o cliente tem o módulo de protocolo e arquivo
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PA');
if (count($RS)>0) $w_pa='S'; else $w_pa='N'; 

// Recupera a configuração do serviço
$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);

// Variável para identificar a sigla do serviço
$sigla = 'GRCL';

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Pesquisa gerencial
// -------------------------------------------------------------------------
function Gerencial() {
  extract($GLOBALS);
  global $w_embed;
  
  $w_pag   = 1;
  $w_linha = 0;
  
  if ($O=='L' || $O=='V' || $p_tipo == 'WORD' || $p_tipo=='PDF') {
    $w_filtro='';
    if ($p_sqcc>'') {
      $w_linha++;
      $sql = new db_getCCData; $RS = $sql->getInstanceOf($dbms,$p_sqcc);
      $w_filtro .= '<tr valign="top"><td align="right">Classificação <td>[<b>'.f($RS,'nome').'</b>]';
    } 
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
    if ($p_assunto>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Código externo <td>[<b>'.$p_assunto.'</b>]'; }
    if ($p_unidade>'') {
      $w_linha++;
      $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_unidade);
      $w_filtro .= '<tr valign="top"><td align="right">Unidade solicitante<td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_proponente>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Material<td>[<b>'.$p_proponente.'</b>]'; }
    if ($p_palavra>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Número do certame<td>[<b>'.$p_palavra.'</b>]'; }
    if ($p_pais>'') {
      $w_linha++;
      $sql = new db_getTipoMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_pais,null,null,null,null,null,null,'REGISTROS');
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Tipo de material/serviço<td>[<b>'.f($RS,'nome_completo').'</b>]';
    } 
    if ($p_usu_resp>'') {
      $w_linha++;
      $sql = new db_getLCModalidade; $RS = $sql->getInstanceOf($dbms, $p_usu_resp, $w_cliente, null, null, null, null);
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Modalidade<td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_regiao>'' || $p_cidade>'') {
      $w_linha++;
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Protocolo <td>[<b>'.(($p_regiao>'') ? str_pad($p_regiao,6,'0',PAD_RIGHT) : '*').'/'.(($p_cidade>'') ? $p_cidade : '*').'</b>]';
    } 
    if ($p_ativo=='S') {
      $w_linha++;
      $w_filtro .= '<tr valign="top"><td align="right">Restrição<td>[<b>Apenas compras por decisão judicial</b>]';
    } 
    if ($p_ini_i>'')      $w_filtro.='<tr valign="top"><td align="right">Eventos do certame <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
    if ($p_fim_i>'')  { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Autorização <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]'; }
    if ($w_filtro>'') { $w_linha++; $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>'; }

    // Recupera os dados a partir do filtro
    $sql = new db_getSolicCL; $RS1 = $sql->getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,null,$p_ativo,$p_proponente,
        $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
        $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade,
        $p_acao_ppa, null, $p_empenho, null);

    switch ($p_agrega) {
      case $sigla.'ABERTURA':      $RS1 = SortArray($RS1,'data_abertura','asc');         break;
      case $sigla.'AUTORIZ':       $RS1 = SortArray($RS1,'data_autorizacao','asc');      break;
      case $sigla.'UNIDADE':       $RS1 = SortArray($RS1,'sg_unidade_resp','asc');       break;
      case $sigla.'CC':            $RS1 = SortArray($RS1,'nm_cc','asc');                 break;
      case $sigla.'PROJ':          $RS1 = SortArray($RS1,'dados_pai','asc');             break;
      case $sigla.'MODAL':         $RS1 = SortArray($RS1,'nm_lcmodalidade','asc');       break;
      case $sigla.'ENQ':           $RS1 = SortArray($RS1,'nm_enquadramento','asc');      break;
      case $sigla.'SITUACAO':      $RS1 = SortArray($RS1,'nm_lcsituacao','asc');         break;
    } 
  } 

  $w_linha_filtro = $w_linha;
  $w_linha_pag    = 0;
  headerGeral('P', $p_tipo, $w_chave, 'Consulta de '.f($RS_Menu,'nome'), $w_embed, null, null, $w_linha_pag,$w_filtro);

  if ($w_embed!='WORD') {
    $w_embed = 'HTML';
    Cabecalho();
    head();
    if ($O=='P') {
      ScriptOpen('Javascript');
      CheckBranco();
      FormataData();
      SaltaCampo();
      ValidateOpen('Validacao');
      Validate('p_empenho','Código','','','2','60','1','1');
      Validate('p_proponente','Material','','','2','60','1','');
      if ($SG==$sigla.'LIC') {
        Validate('p_palavra','Certame','','','2','14','1','1');
        Validate('p_regiao','Sequencial do protocolo','','','1','10','','0123456789');
        Validate('p_cidade','Ano do protocolo','','','4','4','','0123456789');
        Validate('p_ini_i','Início do período','DATA','','10','10','','0123456789/');
        Validate('p_ini_f','Término do período','DATA','','10','10','','0123456789/');
        ShowHTML('  if ((theForm.p_ini_i.value != \'\' && theForm.p_ini_f.value == \'\') || (theForm.p_ini_i.value == \'\' && theForm.p_ini_f.value != \'\')) {');
        ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
        ShowHTML('     theForm.p_ini_i.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        CompData('p_ini_i','Início do período','<=','p_ini_f','Término do período');
      }
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  for (i=0; i < theForm["p_fase[]"].length; i++) {');
      ShowHTML('    if (theForm["p_fase[]"][i].checked) {');
      ShowHTML('       w_erro=false; ');
      ShowHTML('       break; ');
      ShowHTML('    }');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert("Você deve selecionar pelo menos uma das opções do campo \"Recuperar fases\"!"); ');
      ShowHTML('    return false;');
      ShowHTML('  }');
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
    } elseif ($O=='P') {
      if ($P1==1) { // Se for cadastramento
        BodyOpen('onLoad=\'document.Form.p_ordena.focus()\';');
      } else {
        BodyOpen('onLoad=\'document.Form.p_agrega.focus()\';');
      } 
    } else {
      BodyOpenClean('onLoad=this.focus();');
    } 

    if ($O=='L') {
      CabecalhoRelatorio($w_cliente,'Consulta de '.f($RS_Menu,'nome'),4);
      ShowHTML('<HR>');
      if ($w_filtro>'') ShowHTML($w_filtro);
    } else {
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
      ShowHTML('<HR>');
    } 
  } 

  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L' || $w_embed == 'WORD') {
    if ($w_embed != 'WORD') {
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
          case $sigla.'ENQ':       ShowHTML('     document.Form.p_acao_ppa.value=filtro;'); break;
          case $sigla.'CIDADE':    ShowHTML('     document.Form.p_cidade.value=filtro;');   break;
          case $sigla.'UNIDADE':   ShowHTML('     document.Form.p_unidade.value=filtro;');  break;
          case $sigla.'PROJ':      ShowHTML('     document.Form.p_projeto.value=filtro;');  break;
          case $sigla.'CC':        ShowHTML('     document.Form.p_sqcc.value=filtro;');     break;
          case $sigla.'ABERTURA':  ShowHTML('     document.Form.p_ini_i.value=filtro;');    break;
          case $sigla.'AUTORIZ':
            ShowHTML('     document.Form.p_fim_i.value="01/"+filtro.substr(5,2)+"/"+filtro.substr(0,4);');
            break;
          case $sigla.'MODAL':     ShowHTML('     document.Form.p_usu_resp.value=filtro;'); break;
          case $sigla.'SITUACAO':  ShowHTML('     document.Form.p_uf.value=filtro;');       break;
        } 
        ShowHTML('    }');
        switch ($p_agrega) {
          case $sigla.'ENQ':       ShowHTML('    else document.Form.p_acao_ppa.value="'.$_REQUEST['p_acao_ppa'].'";'); break;
          case $sigla.'CIDADE':    ShowHTML('    else document.Form.p_cidade.value="'.$_REQUEST['p_cidade'].'";');     break;
          case $sigla.'UNIDADE':   ShowHTML('    else document.Form.p_unidade.value="'.$_REQUEST['p_unidade'].'";');   break;
          case $sigla.'PROJ':      ShowHTML('    else document.Form.p_projeto.value="'.$_REQUEST['p_projeto'].'";');   break;
          case $sigla.'CC':        ShowHTML('    else document.Form.p_sqcc.value=\''.$_REQUEST['p_sqcc'].'\';');       break;
          case $sigla.'ABERTURA':  ShowHTML('    else document.Form.p_ini_i.value="'.$_REQUEST['p_ini_i'].'";');       break;
          case $sigla.'AUTORIZ':   ShowHTML('    else document.Form.p_fim_i.value="'.$_REQUEST['p_fim_i'].'";');       break;
          case $sigla.'MODAL':     ShowHTML('    else document.Form.p_usu_resp.value="'.$_REQUEST['p_usu_resp'].'";'); break;
          case $sigla.'SITUACAO':  ShowHTML('    else document.Form.p_uf.value="'.$_REQUEST['p_uf'].'";');             break;
        } 
        $sql = new db_getTramiteList; $RS2 = $sql->getInstanceOf($dbms,$P2,null,null,null);
        $RS2 = SortArray($RS2,'ordem','asc');
        $w_fase_exec='';
        foreach($RS2 as $row) {
          if (f($row,'sigla')=='CI') {
            $w_fase_cad=f($row,'sq_siw_tramite');
          } elseif (f($row,'sigla')=='AT') {
            $w_fase_conc=f($row,'sq_siw_tramite');
          } elseif (f($row,'ativo')=='S') {
            $w_fase_exec=$w_fase_exec.','.f($row,'sq_siw_tramite');
          } 
        } 
        ShowHTML('    if (cad >= 0) document.Form.p_fase.value='.$w_fase_cad.';');
        ShowHTML('    if (exec >= 0) document.Form.p_fase.value="'.substr($w_fase_exec,1,100).'";');
        ShowHTML('    document.Form.p_prazo.value="";');
        ShowHTML('    if (conc >= 0) {document.Form.p_fase.value='.$w_fase_conc.'; document.Form.p_prazo.value=1;}');
        ShowHTML('    if (cad==-1 && exec==-1 && conc==-1) document.Form.p_fase.value="'.$p_fase.'";');
        ShowHTML('    if (atraso >= 0) document.Form.p_atraso.value="S"; else document.Form.p_atraso.value="'.$_REQUEST['p_atraso'].'"; ');
        ShowHTML('    document.Form.submit();');
        ShowHTML('  }');
        ShowHTML('</SCRIPT>');
        ShowHTML('<BASE HREF="'.$conRootSIW.'">');
        $sql = new db_getMenuData; $RS2 = $sql->getInstanceOf($dbms,$P2);
        AbreForm('Form',f($RS2,'link'),'POST','return(Validacao(this));','Lista',3,$P2,f($RS2,'P3'),null,$w_TP,f($RS2,'sigla'),$w_dir.$w_pagina.$par,'L');
        ShowHTML(MontaFiltro('POST'));
        ShowHTML('<input type="Hidden" name="p_atraso" value="N">');
        ShowHTML('<input type="Hidden" name="p_prazo" value="">');
        switch ($p_agrega) {
          case $sigla.'ENQ':       if ($_REQUEST['p_acao_ppa']=='') ShowHTML('<input type="Hidden" name="p_acao_ppa" value="">');  break;
          case $sigla.'CIDADE':    if ($_REQUEST['p_cidade']=='')   ShowHTML('<input type="Hidden" name="p_cidade" value="">');    break;
          case $sigla.'UNIDADE':   if ($_REQUEST['p_unidade']=='')  ShowHTML('<input type="Hidden" name="p_unidade" value="">');   break;
          case $sigla.'PROJ':      if ($_REQUEST['p_projeto']=='')  ShowHTML('<input type="Hidden" name="p_projeto" value="">');   break;
          case $sigla.'CC':        if ($_REQUEST['p_sqcc']=='')     ShowHTML('<input type="Hidden" name="p_sqcc" value="">');      break;
          case $sigla.'ABERTURA':  if ($_REQUEST['p_ini_i']=='')    ShowHTML('<input type="Hidden" name="p_ini_i" value="">');     break;
          case $sigla.'AUTORIZ':   if ($_REQUEST['p_fim_i']=='')    ShowHTML('<input type="Hidden" name="p_fim_i" value="">');     break;
          case $sigla.'MODAL':     if ($_REQUEST['p_usu_resp']=='') ShowHTML('<input type="Hidden" name="p_usu_resp" value="">');  break;
          case $sigla.'SITUACAO':  if ($_REQUEST['p_uf']=='')       ShowHTML('<input type="Hidden" name="p_uf" value="">');        break;
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
          case $sigla.'CC':
            if ($w_nm_quebra!=f($row,'nm_cc')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && ($w_linha+1)<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_cc'));
              } 
              $w_nm_quebra  = f($row,'nm_cc');
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
              $w_linha     += 1;
            } 
            break;
          case $sigla.'ABERTURA':
            if ($w_nm_quebra!=date('Y/m',f($row,'data_abertura'))) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.date('Y/m',f($row,'data_abertura')));
              } 
              $w_nm_quebra  = date('Y/m',f($row,'data_abertura'));
              $w_chave      = date('Y/m',f($row,'data_abertura'));
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
              $w_linha     += 1;
            } 
            break;
          case $sigla.'AUTORIZ':
            if ($w_nm_quebra!=date('Y/m',f($row,'data_autorizacao'))) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.date('Y/m',f($row,'data_autorizacao')));
              } 
              $w_nm_quebra  = date('Y/m',f($row,'data_autorizacao'));
              $w_chave      = date('Y/m',f($row,'data_autorizacao'));
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
              $w_linha     += 1;
            } 
            break;
          case $sigla.'CIDADE':
            if ($w_nm_quebra!=f($row,'nm_destino')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_destino'));
              } 
              $w_nm_quebra  = f($row,'nm_destino');
              $w_chave      = f($row,'destino');
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
              $w_linha     += 1;
            } 
            break;
          case $sigla.'UNIDADE':
            if ($w_nm_quebra!=f($row,'sg_unidade_resp')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td align="center"><b>'.f($row,'sg_unidade_resp'));
              } 
              $w_nm_quebra  = f($row,'sg_unidade_resp');
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
              $w_linha     += 1;
            } 
            break;
          case $sigla.'PROJ':
            if ($w_nm_quebra!=piece(f($row,'dados_pai'),null,'|@|',2)) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.piece(f($row,'dados_pai'),null,'|@|',2).' - '.piece(f($row,'dados_pai'),null,'|@|',3));
              } 
              $w_nm_quebra  = piece(f($row,'dados_pai'),null,'|@|',2);
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
              $w_linha     += 1;
            } 
            break;
          case $sigla.'ENQ':
            if (Nvl($w_nm_quebra,'')!=f($row,'nm_enquadramento')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_enquadramento'));
              } 
              $w_nm_quebra  = f($row,'nm_enquadramento');
              $w_chave      = f($row,'sq_modalidade_artigo');
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
              $w_linha     += 1;
            } 
            break;
          case $sigla.'MODAL':
            if (Nvl($w_nm_quebra,'')!=f($row,'nm_lcmodalidade')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_lcmodalidade'));
              } 
              $w_nm_quebra  = f($row,'nm_lcmodalidade');
              $w_chave      = f($row,'sq_lcmodalidade');
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
              $w_linha     += 1;
            } 
            break;
          case $sigla.'SITUACAO':
            if ($w_nm_quebra!=f($row,'nm_lcsituacao')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_lcsituacao'));
              } 
              $w_nm_quebra  = f($row,'nm_lcsituacao');
              $w_chave      = f($row,'sq_lcsituacao');
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
              $w_linha     += 1;
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
          if ($p_tipo=='PDF') ShowHTML('    <pd4ml:page.break>');
          else                ShowHTML('    <br style="page-break-after:always">');
          $w_linha=$w_linha_filtro;
          $w_pag    += 1;
          CabecalhoWord($w_cliente,$w_TP,$w_pag);
          if ($w_filtro>'') ShowHTML($w_filtro);
          ShowHTML('<div align=center><center>');
          ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
          ImprimeCabecalho();
          switch ($p_agrega) {
            case $sigla.'ENQ':         ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_enquadramento'));  break;
            case $sigla.'CIDADE':      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_destino'));        break;
            case $sigla.'UNIDADE':     ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'sg_unidade_resp'));   break;
            case $sigla.'PROJ':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.piece(f($row,'dados_pai'),null,'|@|',2)); break;
            case $sigla.'CC':          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row1,'nm_cc'));            break;
            case $sigla.'ABERTURA':    ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'data_abertura'));     break;
            case $sigla.'AUTORIZ':     ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'data_autorizacao'));  break;
            case $sigla.'MODAL':       ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_lcmodalidade'));   break;
            case $sigla.'SITUACAO':    ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_lcsituacao'));     break;
          } 
          $w_linha += 1;
        } 
        if (nvl(f($row,'conclusao'),'')=='') {
          if (f($row,'aviso_prox_conc') == 'S' && (f($row,'aviso') <= addDays(time(),-1))) {
            $t_aviso    = $t_aviso+1;
            $t_totaviso = $t_totaviso+1;
          }
          
          if (f($row,'or_tramite')==1) {
            $t_cad      += 1;
            $t_totcad   += 1;
          } else {
            $t_tram     += 1;
            $t_tottram  += 1;
          } 
        } else {
          // Para a UNESCO t_atraso significa licitações concluídas com situação "Licitação cancelada", 
          // enquanto que t_cont significa licitações concluídas com todas as outras situações.
          if (strpos(upper(f($row,'nm_lcsituacao')),'CANCELADA')!==false) {
            $t_atraso    = $t_atraso + 1;
            $t_totatraso = $t_totatraso + 1;
          } else {
            $t_conc=$t_conc+1;
            $t_totconc=$t_totconc+1;
            if (Nvl(f($row,'valor'),0)<Nvl(f($row,'custo_real'),0)) {
              $t_acima    += 1;
              $t_totacima += 1;
            } 
          }
        } 
        $t_solic        += 1;
        $t_valor        += Nvl(f($row,'valor'),0);
        $t_custo        += Nvl(f($row,'custo_real'),0);

        $t_totvalor     += Nvl(f($row,'valor'),0);
        $t_totcusto     += Nvl(f($row,'custo_real'),0);
        $t_totsolic     += 1;
        $w_qt_quebra    += 1;
      } 
      ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
      if ($p_agrega!=$sigla.'CIAVIAGEM' && $p_agrega!=$sigla.'CIDADE') {
        ShowHTML('      <tr bgcolor="#DCDCDC" valign="top" align="right">');
        ShowHTML('          <td><b>Totais</td>');
        ImprimeLinha($t_totsolic,$t_totcad,$t_tottram,$t_totconc,$t_totatraso,$t_totaviso,$t_totvalor,$t_totcusto,$t_totacima,-1,$p_agrega);
      } 
    } 
    if ($w_embed!='WORD') {
      ShowHTML('      </FORM>');
      ShowHTML('      </center>');
    }
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    if (count($RS1)>0 && $p_graf=='N') {
      include_once($w_dir_volta.'funcoes/geragraficogoogle.php');
      
      $w_legenda = array('Encerradas','Tramitando','Cadastramento','Total');
      ShowHTML('<tr><td align="center"><br>');
      if ($p_tipo=='PDF') ShowHTML('    <pd4ml:page.break>');
      else                ShowHTML('    <br style="page-break-after:always">');
      ShowHTML(geraGraficoGoogle(f($RS_Menu,'nome').' - Resumo',$SG,'bar',
                                 array($t_totsolic,$t_totcad,$t_tottram,$t_totconc),
                                 $w_legenda
                                )
              );
      /*
      if (($t_totcad+$t_tottram)>0) {
        ShowHTML('<tr><td align="center"><br>');
        ShowHTML(geraGraficoGoogle(f($RS_Menu,'nome').' em andamento',$SG,'pie',
                                   array(($t_tottram+$t_totcad-$t_totatraso-$t_totaviso)),
                                   array('Normal','Aviso','Atraso')
                                  )
                );
      }
      */
    }    
  } elseif ($O=='P') {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML(montaFiltro('POST',true));
    // Exibe parâmetros de apresentação
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    ShowHTML('         <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Parâmetros de Apresentação</td>');
    ShowHTML('         <tr valign="top"><td colspan=2><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('          <td><b><U>A</U>gregar por:<br><SELECT ACCESSKEY="A" '.$w_Disabled.' class="STS" name="p_agrega" size="1">');
    if (f($RS_Menu,'solicita_cc')=='S') {
      ShowHTML('          <option value="'.$sigla.'CC" '.($p_agrega==($sigla.'CC') ? 'selected' : '').'>Classificação');
    } 
    if (f($RS_Menu,'sigla')=='CLLCCAD') {
      ShowHTML(' <option value="'.$sigla.'ENQ"'.(($p_agrega==$sigla.'ENQ') ? ' SELECTED': '').'>Enquadramento');
      //ShowHTML(' <option value="'.$sigla.'CIDADE"'.(($p_agrega==$sigla.'CIDADE') ? ' SELECTED': '').'>Cidade destino');
      ShowHTML(' <option value="'.$sigla.'ABERTURA"'.(($p_agrega==$sigla.'ABERTURA') ? ' SELECTED': '').'>Mês de abertura');
    }
    ShowHTML(' <option value="'.$sigla.'AUTORIZ"'.(($p_agrega==$sigla.'AUTORIZ') ? ' SELECTED': '').'>Mês de autorização');
    if (f($RS_Menu,'sigla')=='CLLCCAD') {
      ShowHTML(' <option value="'.$sigla.'MODAL"'.(($p_agrega==$sigla.'MODAL') ? ' SELECTED': '').'>Modalidade');
    }
    if ($w_pr=='S') ShowHTML(' <option value="'.$sigla.'PROJ"'.(($p_agrega==$sigla.'PROJ') ? ' SELECTED': '').'>Projeto');
    if (f($RS_Menu,'sigla')=='CLLCCAD') {
      ShowHTML(' <option value="'.$sigla.'SITUACAO"'.(($p_agrega==$sigla.'SITUACAO') ? ' SELECTED': '').'>Situação do certame');
    }
    ShowHTML(' <option value="'.$sigla.'UNIDADE"'.(($p_agrega=='' || $p_agrega==$sigla.'UNIDADE') ? ' SELECTED': '').'>Unidade solicitante');
    ShowHTML('          </select></td>');
    ShowHTML('<INPUT type="hidden" name="p_graf" value="S">');
    MontaRadioSN('<b>Limita tamanho do detalhamento?</b>',$p_tamanho,'p_tamanho');
    ShowHTML('           </table>');
    ShowHTML('         </tr>');
    ShowHTML('         <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Critérios de Busca</td>');

    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    // Se o cliente tem o módulo de projetos, permite filtragem por projeto.
    ShowHTML('      <tr><td colspan=2><table border=0 width="90%" cellspacing=0><tr valign="top">');
    if ($w_pr=='S') {
      $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,'PJCAD');
      SelecaoProjeto('Pro<u>j</u>eto:','J','Selecione o projeto da atividade na relação.',$p_projeto,$w_usuario,f($RS,'sq_menu'),null,null,null,'p_projeto','PJLIST',null);
    }
    // Se a opção de menu permite classificação, exibe filtragem por classificação.
    if (f($RS_Menu,'solicita_cc')=='S') {
      SelecaoCC('C<u>l</u>assificação:','C','Selecione um dos itens relacionados.',$p_sqcc,null,'p_sqcc','SIWSOLIC',null,2);
    }
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    selecaoTipoMatServSubord('<u>T</u>ipo de material/serviço:','S','Selecione o grupo/subgrupo de material/serviço desejado.',null,$p_pais,'p_pais','SUBTODOS',null);
    ShowHTML('      </tr>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td><b><U>C</U>ódigo '.(($SG==$sigla.'LIC') ? ' da licitação': ' da solicitação').':<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="STI" type="text" name="p_empenho" size="20" maxlength="60" value="'.$p_empenho.'"></td>');
    if ($SG==$sigla.'LIC') {
      ShowHTML('     <td><b>Protocolo:<br><INPUT class="STI" type="text" name="p_regiao" style="text-align:right;" size="7" maxlength="6" value="'.$p_regiao.'">/<INPUT class="STI" type="text" name="p_cidade" size="4" maxlength="4" value="'.$p_cidade.'"></td>');
      ShowHTML('   <tr valign="top">');
    }
    SelecaoPessoa('<u>R</u>esponsável pela execução:','N','Selecione o responsável na relação.',$p_prioridade,null,'p_prioridade','USUARIOS');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td><b><U>M</U>aterial:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="60" value="'.$p_proponente.'"></td>');
    //SelecaoPessoa('Respo<u>n</u>sável:','N','Selecione o responsável pela PCD na relação.',$p_solicitante,null,'p_solicitante','USUARIOS');
    SelecaoUnidade('<U>U</U>nidade solicitante:','U','Selecione a unidade solicitante',$p_unidade,null,'p_unidade','CLCP',null);
    ShowHTML('   <tr valign="top">');
    if ($SG==$sigla.'LIC') {
      ShowHTML('     <td><b>Número d<u>o</u> certame:<br><INPUT ACCESSKEY="F" TYPE="text" class="sti" NAME="p_palavra" VALUE="'.$p_palavra.'" SIZE="14" MaxLength="14">');
      SelecaoLCModalidade('<u>M</u>odalidade:','M','Selecione na lista a modalidade do certame.',$p_usu_resp,null,'p_usu_resp',null,null);
      ShowHTML('<tr valign="top">');
      SelecaoLCSituacao('<u>S</u>ituação do certame:','S','Selecione a situação do certame.',$p_uf,null,'p_uf',null,null);
      ShowHTML('     <td><b><U>C</U>ódigo externo:<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="STI" type="text" name="p_assunto" size="25" maxlength="30" value="'.$p_assunto.'"></td>');
      //MontaRadioNS('<b>Apenas decisão judicial?</b>',$p_ativo,'p_ativo');
    }
    ShowHTML('   <tr valign="top">');
    if ($SG==$sigla.'LIC') {
      ShowHTML('     <td>');
      ShowHTML('       <b>E<u>v</u>entos do certame entre:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa">');
      ShowHTML('       <br><b>A<u>u</u>torização entre:</b><br><input '.$w_Disabled.' accesskey="U" type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa">');
    } else {
      ShowHTML('     <td><b>A<u>u</u>torização entre:</b><br><input '.$w_Disabled.' accesskey="U" type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa">');
    }
    SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase[]',null,null);
    ShowHTML('    </table>');
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
  if($p_tipo == 'PDF') RodapePdf();
  else                 Rodape();
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
    case $sigla.'ENQ':         ShowHTML('          <td><b>Enquadramento</td>');        break;
    case $sigla.'CIDADE':      ShowHTML('          <td><b>Cidade destino</td>');       break;
    case $sigla.'UNIDADE':     ShowHTML('          <td><b>Unidade solicitante</td>');  break;
    case $sigla.'PROJ':        ShowHTML('          <td><b>Projeto</td>');              break;
    case $sigla.'CC':          ShowHTML('          <td><b>Classificação</font></td>'); break;
    case $sigla.'ABERTURA':    ShowHTML('          <td><b>Mês de abertura</td>');      break;
    case $sigla.'AUTORIZ':     ShowHTML('          <td><b>Mês de autorizacao</td>');   break;
    case $sigla.'MODAL':       ShowHTML('          <td><b>Modalidade</td>');           break;
    case $sigla.'SITUACAO':    ShowHTML('          <td><b>Situação do certame</td>');  break;
  } 
  //ShowHTML('          <td><b>Cadastramento</td>');
  ShowHTML('          <td><b>Em andamento</td>');
  ShowHTML('          <td><b>Canceladas</td>');
  ShowHTML('          <td><b>Concluídas</td>');
  ShowHTML('          <td><b>Total</td>');
  //ShowHTML('          <td><b>Aviso</td>');
  ShowHTML('          <td><b>Valor</td>');
  //ShowHTML('          <td><b>$ Real</td>');
  //ShowHTML('          <td><b>Real > Previsto</td>');
  ShowHTML('        </tr>');
} 

// =========================================================================
// Rotina de impressao da linha resumo
// -------------------------------------------------------------------------
function ImprimeLinha($l_solic,$l_cad,$l_tram,$l_conc,$l_atraso,$l_aviso,$l_valor,$l_custo,$l_acima,$l_chave,$l_agrega) {
  extract($GLOBALS);

  //if ($l_cad>0 && $w_embed != 'WORD')      ShowHTML('          <td align="center"><a class="hl" href="javascript:lista(\''.$l_chave.'\', 0, -1, -1, -1);" onMouseOver="window.status=\'Exibe os registros.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_cad,0,',','.').'</a>&nbsp;</td>');                   else ShowHTML('          <td align="center">'.number_format($l_cad,0,',','.').'&nbsp;</td>');
  if ($l_tram>0 && $w_embed != 'WORD')     ShowHTML('          <td align="center"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, 0, -1, -1);" onMouseOver="window.status=\'Exibe os registros.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_tram,0,',','.').'</a>&nbsp;</td>');                  else ShowHTML('          <td align="center">'.number_format($l_tram,0,',','.').'&nbsp;</td>');
  if ($l_atraso>0 && $w_embed != 'WORD')   ShowHTML('          <td align="center"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, -1, 0);" onMouseOver="window.status=\'Exibe os registros.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_atraso,0,',','.').'</a>&nbsp;</td>');                else ShowHTML('          <td align="center">'.$l_atraso.'&nbsp;</td>');
  if ($l_conc>0 && $w_embed != 'WORD')     ShowHTML('          <td align="center"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, 0, -1);" onMouseOver="window.status=\'Exibe os registros.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_conc,0,',','.').'</a>&nbsp;</td>');                  else ShowHTML('          <td align="center">'.number_format($l_conc,0,',','.').'&nbsp;</td>');
  if ($w_embed != 'WORD')                  ShowHTML('          <td align="center"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, -1, -1);" onMouseOver="window.status=\'Exibe os registros.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_solic,0,',','.').'</a>&nbsp;</td>');         else ShowHTML('          <td align="center">'.number_format($l_solic,0,',','.').'&nbsp;</td>');
  ShowHTML('          <td align="right">'.number_format($l_valor,2,',','.').'&nbsp;</td>');
  //ShowHTML('          <td align="right">'.number_format($l_custo,2,',','.').'&nbsp;</td>');
  /*
  if ($l_aviso>0 && $O=='L') {
    ShowHTML('          <td align="right"><font color="red"><b>'.number_format($l_aviso,0,',','.').'&nbsp;</td>');
  } else {
    ShowHTML('          <td align="right"><b>'.$l_aviso.'&nbsp;</td>');
  } 
  if ($l_acima>0) {
    ShowHTML('          <td align="right"><font color="red"><b>'.number_format($l_acima,0,',','.').'&nbsp;</td>');
  } else {
    ShowHTML('          <td align="right"><b>'.$l_acima.'&nbsp;</td>');
  } 
  */
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
    break;
  } 
} 
?>
