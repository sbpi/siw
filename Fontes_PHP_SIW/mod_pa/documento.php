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
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicInter.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta.'classes/sp/db_getUnidade_PA.php');
include_once($w_dir_volta.'classes/sp/db_getDocumentoInter.php');
include_once($w_dir_volta.'classes/sp/db_getDocumentoAssunto.php');
include_once($w_dir_volta.'classes/sp/db_getProtocolo.php');
include_once($w_dir_volta.'classes/sp/db_getParametro.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/db_getUorgList.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgResp.php');
include_once($w_dir_volta.'classes/sp/db_getBenef.php');
include_once($w_dir_volta.'classes/sp/db_getCaixa.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoGeral.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicInter.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicArquivo.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoInter.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoAssunto.php');
include_once($w_dir_volta.'classes/sp/dml_putCaixa.php');
include_once($w_dir_volta.'classes/sp/dml_putCaixaEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoReceb.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoCaixa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoPessoaOrigem.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoEstado.php');
include_once($w_dir_volta.'funcoes/selecaoCidade.php');
include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');
include_once($w_dir_volta.'funcoes/selecaoNaturezaDocumento.php');
include_once($w_dir_volta.'funcoes/selecaoEspecieDocumento.php');
include_once($w_dir_volta.'funcoes/selecaoOrigem.php');
include_once($w_dir_volta.'funcoes/selecaoTipoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoAssunto.php');
include_once($w_dir_volta.'funcoes/selecaoProtocolo.php');
include_once($w_dir_volta.'funcoes/selecaoAssuntoRadio.php');
include_once($w_dir_volta.'funcoes/selecaoTipoDespacho.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
include_once($w_dir_volta.'funcoes/selecaoCaixa.php');
include_once('validadocumento.php');
include_once('visualdocumento.php');
include_once('visualGR.php');
include_once('visualGT.php');
// =========================================================================
//  /documento.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho 
// Descricao: Gerencia o m�dulo de protocolo e arquivos
// Mail     : celso@sbpi.com.br
// Criacao  : 09/08/2006 18:30
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
$w_pagina       = 'documento.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_pa/';
$w_troca        = $_REQUEST['w_troca'];
$p_ordena       = lower($_REQUEST['p_ordena']);
$w_SG           = upper($_REQUEST['w_SG']);
if (strpos('PADOCANEXO,PAINTERESS,PADOCASS',$SG)!==false) {
  if ($O!='I' && $O!='E' && nvl($_REQUEST['w_chave_aux'],$_REQUEST['w_sq_pessoa'])=='') $O='L';
} elseif ($SG=='PADENVIO') {
  $O='V';
} elseif ($O=='') {
  // Se for acompanhamento, entra na filtragem
    if ($P1==3 || $SG=='PADTRAM') $O='P'; else $O='L';
} 
switch ($O) {
  case 'I': $w_TP=$TP.' - Inclus�o';    break;
  case 'A': $w_TP=$TP.' - Altera��o';   break;
  case 'E': $w_TP=$TP.' - Exclus�o';    break;
  case 'P': $w_TP=$TP.' - Filtragem';   break;
  case 'C': $w_TP=$TP.' - C�pia';       break;
  case 'V': $w_TP=$TP.' - Envio';       break;
  case 'R': $w_TP=$TP.' - Recebimento'; break;
  case 'H': $w_TP=$TP.' - Heran�a';     break;
  default:
    if ($par=='BUSCAPROC') $w_TP=$TP.' - Busca proced�ncia'; else $w_TP=$TP.' - Listagem';
  break;
} 
$w_cliente  = RetornaCliente();
$w_usuario      = RetornaUsuario();
$w_menu         = RetornaMenu($w_cliente,$SG);
$w_ano          = RetornaAno();
$w_copia        = $_REQUEST['w_copia'];
$p_numero_doc   = upper($_REQUEST['p_numero_doc']);
$p_atividade    = upper($_REQUEST['p_atividade']);
$p_ativo        = upper($_REQUEST['p_ativo']);
$p_solicitante  = upper($_REQUEST['p_solicitante']);
$p_prioridade   = upper($_REQUEST['p_prioridade']);
$p_unidade      = upper($_REQUEST['p_unidade']);
$p_proponente   = upper($_REQUEST['p_proponente']);
$p_ini_i        = upper($_REQUEST['p_ini_i']);
$p_ini_f        = upper($_REQUEST['p_ini_f']);
$p_fim_i        = upper($_REQUEST['p_fim_i']);
$p_fim_f        = upper($_REQUEST['p_fim_f']);
$p_atraso       = upper($_REQUEST['p_atraso']);
$p_chave        = upper($_REQUEST['p_chave']);
$p_assunto      = upper($_REQUEST['p_assunto']);
$p_pais         = upper($_REQUEST['p_pais']);
$p_regiao       = upper($_REQUEST['p_regiao']);
$p_uf           = upper($_REQUEST['p_uf']);
$p_tipo         = upper($_REQUEST['p_tipo']);
$p_cidade       = upper($_REQUEST['p_cidade']);
$p_usu_resp     = upper($_REQUEST['p_usu_resp']);
$p_uorg_resp    = upper($_REQUEST['p_uorg_resp']);
$p_internas     = upper($_REQUEST['p_internas']);
$p_palavra      = upper($_REQUEST['p_palavra']);
$p_prazo        = upper($_REQUEST['p_prazo']);
$p_fase         = explodeArray($_REQUEST['p_fase']);
$p_processo     = upper($_REQUEST['p_processo']);
$p_empenho      = upper($_REQUEST['p_empenho']);
$p_ativo        = upper($_REQUEST['p_ativo']);
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

$RS_Parametro = db_getParametro::getInstanceOf($dbms,$w_cliente,'PA',null);
foreach($RS_Parametro as $row){$RS_Parametro=$row; break;}

$RS_PAUnidade = db_getUnidade_PA::getInstanceOf($dbms,$w_cliente,$_SESSION['LOTACAO'],null,null);
foreach($RS_PAUnidade as $row) { $RS_PAUnidade = $row; break; }

Main();
FechaSessao($dbms);
exit;
// =========================================================================
// Rotina de visualiza��o resumida dos registros
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $p_uf;
  $w_tipo=$_REQUEST['w_tipo'];
  if ($O=='L') {
    if (!(strpos(upper($R),'GR_')===false)) {
      $w_filtro='';
      if ($p_uf>'' || $p_tipo>'') {
        if ($p_tipo>'') $p_uf = (($p_tipo=='P') ? 'S' : 'N');
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Busca por <td>[<b>'.(($p_uf=='S') ? 'Processos' : 'Documentos').'</b>]';
      } 
      if ($p_numero_doc>'') {
        $w_filtro .= '<tr valign="top"><td align="right">N� do documento <td>[<b>'.$p_numero_doc.'</b>]';
      }
      if ($p_processo>'')    { $w_filtro .= '<tr valign="top"><td align="right">Interessado <td>[<b>'.$p_processo.'</b>]'; }
      if ($p_solicitante>'') {
        $RS = db_getEspecieDocumento_PA::getInstanceOf($dbms,$p_solicitante,$w_cliente,null,null,null,null);
        foreach ($RS as $row) {$RS = $row; break;}
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Esp�cie documental <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_prioridade>''){
        $RS = db_getTipoDespacho_PA::getInstanceOf($dbms,$p_prioridade,$w_cliente,null,null,null,null);
        foreach ($RS as $row) {$RS = $row; break;}
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">�ltimo despacho<td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_uorg_resp>''){
        $RS = db_getUorgData::getInstanceOf($dbms,$p_uorg_resp);
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Unidade de posse<td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_pais>'' || $p_regiao>'' || $p_cidade>'') {
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Protocolo <td>[<b>'.(($p_pais>'') ? $p_pais : '*').'.'.(($p_regiao>'') ? str_pad($p_regiao,6,'0',PAD_RIGHT) : '*').'/'.(($p_cidade>'') ? $p_cidade : '*').'</b>]';
      } 
      if ($p_proponente>'') {
        if (is_numeric($p_proponente)) {
          $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_proponente,null,null);
          $w_filtro .= '<tr valign="top"><td align="right">Proced�ncia externa <td>[<b>'.f($RS,'nome_resumido').'</b>]';
        } else {
          $w_filtro .= '<tr valign="top"><td align="right">Proced�ncia externa <td>[<b>'.$p_proponente.'</b>]';
        }
      }
      if ($p_palavra>'')    { $w_filtro .= '<tr valign="top"><td align="right">Interessado <td>[<b>'.$p_palavra.'</b>]'; }
      if ($p_unidade>'') {
        $RS = db_getUorgData::getInstanceOf($dbms,$p_unidade);
        $w_filtro .= '<tr valign="top"><td align="right">Origem interna <td>[<b>'.f($RS,'nome').'</b>]';
      }
      if ($p_assunto>'')    { $w_linha++; $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Assunto <td>[<b>'.$p_assunto.'</b>]'; }
      if ($p_ini_i>'')            $w_filtro .= '<tr valign="top"><td align="right">Data cria��o/recebimento entre <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
      if ($p_fim_i>'')            $w_filtro .= '<tr valign="top"><td align="right">Limite da tramita��o entre <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]';
      if ($p_atraso=='S')   { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Situa��o <td>[<b>Apenas atrasados</b>]'; }
      if ($w_filtro>'')           $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>';
    } 
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PADCAD');
    if ($w_copia>'') {
      // Se for c�pia, aplica o filtro sobre todas as demandas vis�veis pelo usu�rio
      $RS = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_numero_doc, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, 
          null, null, $p_empenho, $p_processo);
    } else {      
      $RS = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P1,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_numero_doc, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, 
          null, null, $p_empenho, $p_processo);
    } 
    if (Nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'ano','asc','numero','asc','protocolo','asc' );
     } else {
      $RS = SortArray($RS,'ano','asc','numero','asc','protocolo','asc');
    }
  }
  if ($w_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 45: 30);
    CabecalhoWord($w_cliente,'Consulta de '.f($RS_Menu,'nome'),0);
    $w_embed = 'WORD';
    if ($w_filtro>'') ShowHTML($w_filtro);
  }elseif($w_tipo == 'PDF'){
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 60: 35);
    $w_embed = 'WORD';
    HeaderPdf('Consulta de '.f($RS_Menu,'nome'),$w_pag);
    if ($w_filtro>'') ShowHTML($w_filtro);
  } else {
    $w_embed = 'HTML';
    cabecalho();
    head();
    if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de processos e documentos</TITLE>');
    ScriptOpen('Javascript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    FormataProtocolo();
    ValidateOpen('Validacao');
    if (!(strpos('CP',$O)===false)) {
      if ($P1!=1 || $O=='C') {
        // Se n�o for cadastramento ou se for c�pia
        Validate('p_numero_doc','N�mero de protocolo','1','','20','20','','0123456789./-');
        Validate('p_ini_i','Recebimento inicial','DATA','','10','10','','0123456789/');
        Validate('p_ini_f','Recebimento final','DATA','','10','10','','0123456789/');
        ShowHTML('  if ((theForm.p_ini_i.value != \'\' && theForm.p_ini_f.value == \'\') || (theForm.p_ini_i.value == \'\' && theForm.p_ini_f.value != \'\')) {');
        ShowHTML('     alert (\'Informe ambas as datas de in�cio ou nenhuma delas!\');');
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
    if ($w_troca>'') {
      // Se for recarga da p�gina
      BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
    } elseif ($O=='I' || $O=='A') {
      BodyOpen('onLoad=\'document.Form.w_tipo.focus()\';');
    } elseif ($O=='E') {
      BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
    } else {
      BodyOpen(null);
    } 
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    if($w_embed!='WORD') {
      if ((strpos(upper($R),'GR_'))===false) {
        Estrutura_Texto_Abre();
      } else {
        CabecalhoRelatorio($w_cliente,'Consulta de '.f($RS_Menu,'nome'),4);
      }
    }
    if ($w_filtro > '') ShowHTML($w_filtro);
  }
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr>');
    if ($w_embed!='WORD') {
      ShowHTML('<td><font size="2">');
      if ($P1==1 && $w_copia=='') {
        // Se for cadastramento e n�o for resultado de busca para c�pia
        if ($w_submenu>'') {
          $RS1 = db_getLinkSubMenu::getInstanceOf($dbms,$w_cliente,$SG);
          foreach($RS1 as $row){$RS1=$row; break;}
          ShowHTML('<tr><td>');
          ShowHTML('    <a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.f($RS1,'sigla').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
          //ShowHTML '    <a accesskey=''C'' class=''SS'' href=''' & w_dir & w_pagina & par & '&R=' & w_pagina & par & '&O=C&P1=' & P1 & '&P2=' & P2 & '&P3=1&P4=' & P4 & '&TP=' & TP & '&SG=' & SG & MontaFiltro('GET') & '''><u>C</u>opiar</a>'
        } else {
          ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
        } 
      } 
      if ((strpos(upper($R),'GR_')===false)) {
        if ($w_copia>'') {
          // Se for c�pia
          if (MontaFiltro('GET')>'') ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
          else                       ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
        } else {
          if (MontaFiltro('GET')>'')  ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
          else                        ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
        } 
      } 
    }
    ShowHTML('    <td align="right">');
    if ($w_embed!='WORD' && strpos(upper($R),'GR_')===false) {
      ShowHTML('     <IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      ShowHTML('     &nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&O=L&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.count($RS).'&TP='.$TP.'&SG='.$SG.'&w_tipo=WORD'.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
    } 
    ShowHTML('    <b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.(($w_embed=='WORD') ? 1 : $conTableBorder).'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    if ($w_embed!='WORD') {
      ShowHTML('          <td rowspan=2 width="1%" nowrap><b>'.LinkOrdena('Protocolo','protocolo').'</td>');
      ShowHTML('          <td rowspan=2 width="50"><b>'.LinkOrdena('Tipo','nm_tipo_protocolo').'</td>');
      ShowHTML('          <td rowspan=2 nowrap><b>'.LinkOrdena('Posse','sg_unidade_posse').'</td>');
      ShowHTML('          <td colspan=4><b>Documento original</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Assunto','cd_assunto').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Resumo','ds_assunto').'</td>');
      ShowHTML('          <td rowspan=2><b>'.linkOrdena('Guarda','prazo_guarda').'</b></td>');
      ShowHTML('          <td rowspan=2><b>'.linkOrdena('Destina��o final','nm_final').'</b></td>');
      //ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Recebimento','data_recebimento').'</td>');
      //ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Limite','fim').'</td>');
      //ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Fase atual','nm_tramite').'</td>');
      if ($P1==1) ShowHTML('          <td rowspan=2><b>Opera��es</td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>'.LinkOrdena('Esp�cie','nm_especie').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('N�','numero_original').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Data','inicio').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Proced�ncia','nm_origem').'</td>');
    } else {
      ShowHTML('          <td rowspan=2 width="1%" nowrap><b>Protocolo</td>');
      ShowHTML('          <td rowspan=2 width="1%" nowrap><b>Tipo</td>');
      ShowHTML('          <td rowspan=2 width="1%" nowrap><b>Posse</td>');
      ShowHTML('          <td colspan=4><b>Documento original</td>');
      ShowHTML('          <td rowspan=2><b>Assunto</td>');
      ShowHTML('          <td rowspan=2><b>Resumo</td>');
      ShowHTML('          <td rowspan=2><b>Guarda</b></td>');
      ShowHTML('          <td rowspan=2><b>Destina��o final</b></td>');
      //ShowHTML('          <td rowspan=2><b>Recebimento</td>');
      //ShowHTML('          <td rowspan=2><b>Limite</td>');
      //ShowHTML('          <td rowspan=2><b>Fase atual</td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>Esp�cie</td>');
      ShowHTML('          <td><b>N�</td>');
      ShowHTML('          <td><b>Data</td>');
      ShowHTML('          <td><b>Proced�ncia</td>');
    }  
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=12 align="center"><font size="2"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial=0;
      if ($w_embed!='WORD') $RS1 = array_slice($RS, (($P3-1)*$P4), $P4); else $RS1 = $RS;
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td width="1%" nowrap>');
        if (f($row,'sg_tramite')=='CI') {
          if (Nvl(f($row,'fim'),time()) < addDays(time(),-1)) {
            ShowHTML('           <img src="'.$conImgAtraso.'" title="Em cadastramento" border=0 width=10 height=10 align="center">');
          } else {
            ShowHTML('           <img src="'.$conImgNormal.'" title="Em cadastramento" border=0 width=10 height=10 align="center">');
          }
        } elseif (f($row,'sg_tramite')=='CA') {
          ShowHTML('           <img src="'.$conImgCancel.'" title="Cancelado" border=0 width=10 height=10 align="center">');
        } elseif (f($row,'sg_tramite')=='EL') {
          ShowHTML('           <img src="'.$conImgCancel.'" title="Eliminado" border=0 width=10 height=10 align="center">');
        } elseif (f($row,'sg_tramite')=='AS') {
          ShowHTML('           <img src="'.$conImgOkAcima.'" title="Arquivado Setorial" border=0 width=10 height=10 align="center">');
        } elseif (f($row,'sg_tramite')=='DE') {
          ShowHTML('           <img src="'.$conImgOkNormal.'" title="Enviado para destino externo" border=0 width=10 height=10 align="center">');
        } elseif (f($row,'sg_tramite')=='AT') {
          ShowHTML('           <img src="'.$conImgOkNormal.'" title="Arquivado Central" border=0 width=10 height=10 align="center">');
        } else {
          if (Nvl(f($row,'fim'),time()) < addDays(time(),-1)) {
            ShowHTML('           <img src="'.$conImgStAtraso.'" title="Tramitando. Data limite excedida." border=0 width=10 height=10 align="center">');
          } else {
            ShowHTML('           <img src="'.$conImgStNormal.'" title="Tramitando" border=0 width=10 height=10 align="center">');
          }
        } 

        if ($w_embed!='WORD') ShowHTML('        <A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informa��es deste registro.">'.f($row,'protocolo').'&nbsp;</a>');
        else                 ShowHTML('        '.f($row,'protocolo').'');
        ShowHTML('        </td>');
        ShowHTML('        <td width="10">&nbsp;'.f($row,'nm_tipo_protocolo').'</td>');
        if ($w_embed!='WORD') ShowHTML('        <td width="1%" nowrap>&nbsp;'.ExibeUnidade('../',$w_cliente,f($row,'sg_unidade_posse'),f($row,'unidade_int_posse'),$TP).'</td>');
        else                 ShowHTML('        <td width="1%" nowrap>&nbsp;'.f($row,'sg_unidade_posse').'');
        
        ShowHTML('        <td>'.f($row,'nm_especie').'</td>');
        ShowHTML('        <td width="1%" nowrap>&nbsp;'.f($row,'numero_original').'</td>');
        ShowHTML('        <td align="center" width="1%" nowrap>&nbsp;'.FormataDataEdicao(f($row,'inicio'),5).'&nbsp;</td>');
        if (f($row,'interno')=='S') {
          if ($w_embed!='WORD') ShowHTML('        <td width="1%" nowrap>&nbsp;'.ExibeUnidade('../',$w_cliente,f($row,'nm_origem_resumido'),f($row,'sq_origem'),$TP).'</td>');
          else                 ShowHTML('        <td width="1%" nowrap>&nbsp;'.f($row,'nm_origem_resumido').'');
        } else {
          if ($w_embed!='WORD') ShowHTML('        <td>'.ExibePessoa(null,$w_cliente,f($row,'sq_origem'),$TP,f($row,'nm_origem_resumido')).'</td>');
          else                 ShowHTML('        <td width="1%" nowrap>&nbsp;'.f($row,'nm_origem_resumido').'');
        }
        if ($w_embed!='WORD') ShowHTML('        <td width="50" title="'.f($row,'ds_assunto').'">&nbsp;'.ExibeAssunto('../',$w_cliente,f($row,'cd_assunto'),f($row,'sq_assunto'),$TP).'</td>');
        else                 ShowHTML('        <td width="50" title="'.f($row,'ds_assunto').'">&nbsp;'.f($row,'cd_assunto').'</td>');
        if ($_REQUEST['p_tamanho']=='N') {
          ShowHTML('        <td>'.Nvl(f($row,'descricao'),'-').'</td>');
        } else {
          if (strlen(Nvl(f($row,'descricao'),'-'))>50) $w_titulo=substr(Nvl(f($row,'descricao'),'-'),0,50).'...'; else $w_titulo=Nvl(f($row,'descricao'),'-');
          if (f($row,'sg_tramite')=='CA') ShowHTML('        <td title="'.htmlspecialchars(f($row,'descricao')).'"><strike>'.htmlspecialchars($w_titulo).'</strike></td>');
          else                            ShowHTML('        <td title="'.htmlspecialchars(f($row,'descricao')).'">'.htmlspecialchars($w_titulo).'</td>');
        }
        ShowHTML('        <td align="center">'.f($row,'prazo_guarda').'</font></td>');
        ShowHTML('        <td align="center" '.((f($row,'provisorio')=='S') ? '' : 'title="'.f($row,'nm_final').'"').'>'.((f($row,'provisorio')=='S') ? '&nbsp;' : f($row,'sg_final')).'</font></td>');
        //ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'data_recebimento')).'</td>');
        //ShowHTML('        <td align="center">'.nvl(FormataDataEdicao(f($row,'fim')),'---').'</td>');
        //ShowHTML('        <td nowrap>'.f($row,'nm_tramite').'</td>');
        if ($w_embed!='WORD' && $P1==1){
          ShowHTML('        <td align="top" nowrap>');
          // Se n�o for acompanhamento
          if ($w_copia>'') {
            // Se for listagem para c�pia
            $RS1 = db_getLinkSubMenu::getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);
          } elseif ($P1==1) {
            // Se for cadastramento
            ShowHTML('          <A class="HL" HREF="menu.php?par=ExibeDocs&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&R='.$w_pagina.$par.'&SG='.$SG.'&TP='.$TP.'&w_documento='.f($row,'protocolo').MontaFiltro('GET').'" title="Altera as informa��es cadastrais do documento" TARGET="menu">AL</a>&nbsp;');
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclus�o do documento.">EX</A>&nbsp');
          } 
          ShowHTML('        </td>');
        }
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    if ($w_embed!='WORD'){    
    ShowHTML('<tr><td align="center" colspan=3>');    
      if ($R>'') {
        MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia,ceil(count($RS)/$P4),$P3,$P4,count($RS));
      } else {
        MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia,ceil(count($RS)/$P4),$P3,$P4,count($RS));
      } 
    }
    ShowHTML('</tr>');
  } elseif (!(strpos('CP',$O)===false)) {
    if ($P1!=1) {
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    } elseif ($O=='C') {
      // Se for c�pia
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Para selecionar a a��o que deseja copiar, informe nos campos abaixo os crit�rios de sele��o e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    } 
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O=='C') {
      // Se for c�pia, cria par�metro para facilitar a recupera��o dos registros
      ShowHTML('<INPUT type="hidden" name="w_copia" value="OK">');
    }
    if ($P1!=1 || $O=='C') {
      // Se n�o for cadastramento ou se for c�pia
      // Recupera dados da op��a a��os
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td><b><u>P</u>rotocolo:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="p_numero_doc" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$p_numero_doc.'" onKeyDown="FormataProtocolo(this,event);"></td>');
      ShowHTML('          <td valign="top"><b>N� original do documento:<br><INPUT '.$w_Disabled.' class="STI" type="text" name="p_empenho" size="40" maxlength="90" value="'.$p_empenho.'"></td>');
      ShowHTML('      <tr valign="top">');
      SelecaoAssuntoRadio('Assun<u>t</u>o:','T','Clique na lupa para selecionar o assunto do documento.',$p_assunto,null,'p_assunto','FOLHA',null);
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('<u>P</u>essoa de origem:','P','Selecione a pessoa de origem.',$p_solicitante,null,'p_solicitante','USUARIOS');
      SelecaoUnidade('<U>U</U>nidade de origem:','U',null,$p_unidade,null,'p_unidade',null,null);
      ShowHTML('      <tr>');
      ShowHTML('          <td valign="top"><b>Data de cria��o/recebimento entre:</b><br><input '.$w_Disabled.' type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
      ShowHTML('          <td valign="top"><b>Limite da tramita��o entre:</b><br><input '.$w_Disabled.' type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
      if ($O!='C') {
        // Se n�o for c�pia
        ShowHTML('      <tr>');
        ShowHTML('          <td valign="top"><b>Exibe somente protocolos em atraso?</b><br>');
        if ($p_atraso=='S') ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S" checked> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N"> N�o');
        else                ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N" checked> N�o');
        SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase',null,null);
      } 
    }
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b><U>O</U>rdena��o por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="STS" name="p_ordena" size="1">');
    if ($p_ordena=='ASSUNTO')       ShowHTML('          <option value="assunto" SELECTED>Assunto<option value="inicio">Data de in�cio<option value="">Data de t�rmino<option value="nm_tramite">Fase atual<option value="proponente">Proponente externo');
    elseif ($p_ordena=='INICIO')    ShowHTML('          <option value="assunto">Assunto<option value="inicio" SELECTED>Data de in�cio<option value="">Data de t�rmino<option value="nm_tramite">Fase atual<option value="proponente">Proponente externo');
    elseif ($p_ordena=='NM_TRAMITE')ShowHTML('          <option value="assunto">Assunto<option value="inicio">Data de in�cio<option value="">Data de t�rmino<option value="nm_tramite" SELECTED>Fase atual<option value="proponente">Proponente externo');
    elseif ($p_ordena=='PRIORIDADE')ShowHTML('          <option value="assunto">Assunto<option value="inicio">Data de in�cio<option value="">Data de t�rmino<option value="nm_tramite">Fase atual<option value="proponente">Proponente externo');
    elseif ($p_ordena=='PROPONENTE')ShowHTML('          <option value="assunto">Assunto<option value="inicio">Data de in�cio<option value="">Data de t�rmino<option value="nm_tramite">Fase atual<option value="proponente" SELECTED>Proponente externo');
    else                            ShowHTML('          <option value="assunto">Assunto<option value="inicio">Data de in�cio<option value="" SELECTED>Data de t�rmino<option value="nm_tramite">Fase atual<option value="prioridade">Proponente externo');
    ShowHTML('          </select></td>');
    ShowHTML('          <td valign="top"><b><U>L</U>inhas por p�gina:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="2">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    if ($O=='C') {
      // Se for c�pia
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Abandonar c�pia">');
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
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  if($w_tipo == 'PDF') RodapePdf();
  else Rodape();
} 
// =========================================================================
// Rotina dos dados gerais
// -------------------------------------------------------------------------
function Geral() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_processo   = nvl($_REQUEST['w_processo'],'N');
  $w_circular   = nvl($_REQUEST['w_circular'],'N');
  $w_readonly   = '';
  $w_erro       = '';

  // Verifica se a unidade de lota��o do usu�rio est� cadastrada na rela��o de unidades do m�dulo
  $RS = db_getUorgList::getInstanceOf($dbms,$w_cliente,$_SESSION['LOTACAO'],'PAUNID',null,null,$w_ano);
  if (count($RS)==0) {
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'ATEN��O: Sua lota��o n�o est� cadastrada na tabelas de unidades do m�dulo de protocolo e arquivo.\nEntre em contato com os gestores do sistema!\');');
    ShowHTML('  history.back(1);');
    ScriptClose();
    exit;
  }
  
  // Verifica se h� necessidade de recarregar os dados da tela a partir
  // da pr�pria tela (se for recarga da tela) ou do banco de dados (se n�o for inclus�o)
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da p�gina
    $w_doc_original       = $_REQUEST['w_doc_original'];
    $w_especie_documento  = $_REQUEST['w_especie_documento'];
    $w_natureza_documento = $_REQUEST['w_natureza_documento'];
    $w_data_documento     = $_REQUEST['w_data_documento'];
    $w_fim                = $_REQUEST['w_fim'];
    $w_interno            = $_REQUEST['w_interno'];
    $w_tipo_pessoa        = $_REQUEST['w_tipo_pessoa'];
    $w_pais               = $_REQUEST['w_pais'];
    $w_uf                 = $_REQUEST['w_uf'];
    $w_cidade             = $_REQUEST['w_cidade'];
    $w_data_recebimento   = $_REQUEST['w_data_recebimento'];
    $w_nm_assunto         = $_REQUEST['w_nm_assunto'];
    $w_pessoa_interes     = $_REQUEST['w_pessoa_interes'];
    $w_assunto            = $_REQUEST['w_assunto'];
    $w_descricao          = $_REQUEST['w_descricao'];
    $w_copias             = $_REQUEST['w_copias'];
    $w_volumes            = $_REQUEST['w_volumes'];
    $w_un_autuacao        = $_REQUEST['w_un_autuacao'];
    $w_dt_autuacao        = $_REQUEST['w_dt_autuacao'];
    $w_nm_pessoa_origem   = $_REQUEST['w_nm_pessoa_origem'];
    $w_pessoa_origem      = $_REQUEST['w_pessoa_origem'];
    $w_sq_unidade         = $_REQUEST['w_sq_unidade'];
  } else {
    if (!(strpos('AEV',$O)===false) || $w_copia>'') {
      // Recupera os dados da a��o
      if ($w_copia>'') {
        $RS = db_getSolicData::getInstanceOf($dbms,$w_copia,$SG);
      } else {
        $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
      } 
      if (count($RS)>0) {
        $w_processo           = f($RS,'processo');
        $w_doc_original       = f($RS,'numero_original');
        $w_especie_documento  = f($RS,'sq_especie_documento');
        $w_natureza_documento = f($RS,'sq_natureza_documento');
        $w_data_documento     = formataDataEdicao(f($RS,'inicio'));
        $w_fim                = formataDataEdicao(f($RS,'fim'));
        $w_interno            = f($RS,'interno');
        $w_tipo_pessoa        = f($RS,'sq_tipo_pessoa');
        $w_pais               = f($RS,'sq_pais');
        $w_uf                 = f($RS,'co_uf');
        $w_cidade             = f($RS,'sq_cidade_origem');
        $w_data_recebimento   = formataDataEdicao(f($RS,'data_recebimento'));
        $w_pessoa_interes     = f($RS,'pessoa_interes');
        $w_assunto            = f($RS,'sq_assunto');
        $w_descricao          = f($RS,'descricao');
        $w_copias             = f($RS,'copias');
        $w_volumes            = f($RS,'volumes');
        $w_un_autuacao        = f($RS,'unidade_autuacao');
        $w_dt_autuacao        = formataDataEdicao(f($RS,'data_autuacao'));
        $w_nm_pessoa_origem   = f($RS,'nm_pessoa_origem');
        $w_pessoa_origem      = f($RS,'pessoa_origem');
        $w_sq_unidade         = f($RS,'sq_unidade');
      } 
    } 
  } 

  // Configura vari�veis de controle para processos e circulares
  if (nvl($w_especie_documento,'')!='') {
    $RS = db_getEspecieDocumento_PA::getInstanceOf($dbms,$w_especie_documento,$w_cliente,null,null,null,null);
    foreach ($RS as $row) { $RS = $row; break; }
    if (f($RS,'sigla')=='PROC') {
      $w_processo = 'S';
      $w_circular = 'N';
    } elseif (strpos(upper(f($RS,'nome')),'CIRCULAR')!==false) {
      $w_processo = 'N';
      $w_circular = 'S';
    } else {
      $w_processo = 'N';
      $w_circular = 'N';
    }
    // Carrega assunto padr�o do documento
    $w_assunto = f($RS,'sq_assunto');
  }
  
  if (nvl($w_assunto,'')=='') {
    // S� pode haver um registro para classifica��o provis�ria
    $RS_Assunto = db_getAssunto_PA::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null,null,null,null,'PROVISORIO');
    foreach($RS_Assunto as $row) { $RS_Assunto = $row; break; }
    if (count($RS_Assunto)>0) $w_assunto = f($RS_Assunto,'sq_assunto');
  }
  Cabecalho();
  head();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  // Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara,
  // tratando as particularidades de cada servi�o
  ScriptOpen('JavaScript');
  ShowHTML('function telaDocumento(tipo) {');
  ShowHTML('  document.Form.w_troca.value=\'w_especie_documento\';');
  ShowHTML('  document.Form.action=\''.$w_dir.$w_pagina.$par.'\';');
  ShowHTML('  document.Form.O.value=\''.$O.'\';');
  ShowHTML('  document.Form.submit();');
  ShowHTML('}');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataDataHora();
  FormataValor();
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    Validate('w_especie_documento','Esp�cie documental','SELECT',1,1,18,'','0123456789');
    Validate('w_doc_original','N� do documento','1','1',1,30,'1','1');
    Validate('w_data_documento','Data do documento','DATA','1',10,10,'','0123456789/');
    CompData('w_data_documento','Data do documento','<=',FormataDataEdicao(time()),'data atual');
    Validate('w_interno','Proced�ncia','SELECT',1,1,1,'SN','');
    if ($w_interno=='N') {
      Validate('w_pais','Pa�s','SELECT',1,1,18,'','0123456789');
      Validate('w_uf','Estado','SELECT',1,1,3,'1','1');
      Validate('w_cidade','Cidade','SELECT',1,1,18,'','0123456789');
      Validate('w_pessoa_origem','Pessoa de origem','HIDDEN',1,1,18,'','0123456789');
      Validate('w_pessoa_interes','Interessado principal','HIDDEN',1,1,18,'','0123456789');
    } else {
      Validate('w_sq_unidade','Unidade de origem','SELECT',1,1,18,'','0123456789');
    }
    Validate('w_data_recebimento','Data de cria��o/recebimento','DATA','1',10,10,'','0123456789/');
    CompData('w_data_recebimento','Data de cria��o/recebimento','>=','w_data_documento','Data do documento');
    CompData('w_data_recebimento','Data de cria��o/recebimento','<=',FormataDataEdicao(time()),'data atual');
    if ($w_processo=='S') {
      Validate('w_dt_autuacao','Data de autua��o','DATA','1',10,10,'','0123456789/');
      Validate('w_volumes','N� de volumes','1','1',1,18,'','0123456789');
      CompValor('w_volumes','N� de volumes','>',0,'zero');
    } elseif ($w_circular=='S') {
      Validate('w_copias','N� de c�pias','1','1',1,18,'','0123456789');
      CompValor('w_copias','N� de c�pias','>',2,'dois');
    }
    Validate('w_fim','Data limite para conclus�o','DATA','',10,10,'','0123456789/');
    CompData('w_fim','Data limite para conclus�o','>=','w_data_documento','Data do documento');
    Validate('w_assunto','Classifica��o','HIDDEN',1,1,18,'','0123456789');
    Validate('w_descricao','Detalhamento do assunto','1','1',1,2000,'1','1');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('EV',$O)===false)) {
    BodyOpen('onLoad=\'this.focus()\';');
  }  else {
    BodyOpen('onLoad=\'document.Form.w_especie_documento.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (!(strpos('IAEV',$O)===false)) {
    if ($w_pais=='') {
      // Carrega os valores padr�o para pa�s, estado e cidade
      $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
      $w_pais   = f($RS,'sq_pais');
      $w_uf     = f($RS,'co_uf');
      $w_cidade = f($RS,'sq_cidade_padrao');
    } 
    if ($O=='E') $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('<INPUT type="hidden" name="w_processo" value="'.$w_processo.'">');
    if ($w_interno=='S' and $w_processo=='S') ShowHTML('<INPUT type="hidden" name="w_un_autuacao" value="'.f($RS_Menu,'sq_unid_executora').'">');
    ShowHTML('<INPUT type="hidden" name="w_circular" value="'.$w_circular.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 colspan=0 cellspan=0 width="100%">');
    ShowHTML('      <tr><td colspan=5 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=5><b>IDENTIFICA��O</b></td></tr>');
    ShowHTML('      <tr valign="top">');
    selecaoEspecieDocumento('<u>E</u>sp�cie documental:','E','Selecione a esp�cie do documento.',$w_especie_documento,null,'w_especie_documento',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_doc_original\'; document.Form.submit();"');
    ShowHTML('           <td title="Informe o n�mero do documento de origem."><b>N�mero:</b><br><INPUT '.$w_Disabled.' class="STI" type="text" name="w_doc_original" size="20" maxlength="30" value="'.$w_doc_original.'" ></td>');
    ShowHTML('           <td title="Informe a data do documento de origem."><b>D<u>a</u>ta:</b><br><input '.$w_Disabled.' accesskey="A" type="text" name="w_data_documento" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data_documento.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data original do documento.">'.ExibeCalendario('Form','w_data_documento').'</td>');
    selecaoOrigem('<u>O</u>rigem:','O','Indique se a origem � interna ou externa.',$w_interno,null,'w_interno',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_interno\'; document.Form.submit();"');
    ShowHTML('      <tr><td colspan=5>&nbsp;</td></tr>');
    ShowHTML('      <tr><td colspan=5 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=4><b>PROCED�NCIA</b></td></tr>');
    ShowHTML('        <tr valign="top">');
    if ($w_interno=='N') {
      ShowHTML('       <tr valign="top">');
      SelecaoPais('<u>P</u>a�s:','P',"Selecione o pa�s de proced�ncia.",$w_pais,null,'w_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_uf\'; document.Form.submit();"');
      SelecaoEstado('E<u>s</u>tado:','S',"Selecione o estado de proced�ncia.",$w_uf,$w_pais,null,'w_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_cidade\'; document.Form.submit();"');
      ShowHTML('          <td colspan=2><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
      SelecaoCidade('<u>C</u>idade:','C',"Selecione a cidade de proced�ncia.",$w_cidade,$w_pais,$w_uf,'w_cidade',null,null);
      ShowHTML('           </table>');
      ShowHTML('      <tr><td colspan=5><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
      SelecaoPessoaOrigem('<u>P</u>essoa de origem:','P','Clique na lupa para selecionar a pessoa de origem.',$w_pessoa_origem,null,'w_pessoa_origem',null,null,null);
      ShowHTML('           </table>');
      ShowHTML('      <tr><td colspan=5><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
      SelecaoPessoaOrigem('<u>I</u>nteressado principal:','I','Clique na lupa para selecionar o interessado principal.',$w_pessoa_interes,null,'w_pessoa_interes',null,null,null);
      ShowHTML('           </table>');
    } else {
      ShowHTML('      <tr><td colspan=5><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
      SelecaoUnidade('<U>U</U>nidade de origem:','U','Selecione a unidade de origem.',nvl($w_sq_unidade,$_SESSION['LOTACAO']),null,'w_sq_unidade','MOD_PA',null);
      ShowHTML('           </table>');
    }
    ShowHTML('      <tr><td colspan=5>&nbsp;</td></tr>');
    ShowHTML('      <tr><td colspan=5 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=5><b>DADOS COMPLEMENTARES</b></td></tr>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('           <td valign="top" title="Informe a data de cria��o ou de recebimento."><b><u>D</u>ata de cria��o/recebimento:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data_recebimento" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.nvl($w_data_recebimento,$w_data_documento).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_data_recebimento').'</td>');
    if ($w_processo=='S') {
      ShowHTML('           <td title="Data de autua��o do processo."><b>Data de autua��o:</b><br><input '.$w_Disabled.' type="text" name="w_dt_autuacao" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_dt_autuacao.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_fim').'</td>');
      ShowHTML('           <td title="Informe quantos volumes comp�em o processo."><b>N� de volumes:</b><br><INPUT '.$w_Disabled.' class="STI" type="text" name="w_volumes" size="3" maxlength="3" value="'.$w_volumes.'" ></td>');
    } elseif ($w_circular=='S') {
      ShowHTML('           <td title="Informe o n�mero de c�pias da circular."><b>N� de c�pias:</b><br><INPUT '.$w_Disabled.' class="STI" type="text" name="w_copias" size="5" maxlength="18" value="'.$w_copias.'" ></td>');
    }
    selecaoNaturezaDocumento('<u>N</u>atureza:','N','Indique a natureza do documento.',$w_natureza_documento,null,'w_natureza_documento',null,null);
    ShowHTML('           <td title="OPCIONAL. Limite para t�rmino da tramita��o do documento."><b>Data limite:</b><br><input '.$w_Disabled.' type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_fim').'</td>');
    ShowHTML('      <tr><td colspan=5><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
    SelecaoAssuntoRadio('C<u>l</u>assifica��o:','L','Clique na lupa para selecionar a classifica��o do documento.',$w_assunto,null,'w_assunto','FOLHA','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_descricao\'; document.Form.submit();"');
    ShowHTML('           </table>');
    if (nvl($w_assunto,'')!='') {
      $RS = db_getAssunto_PA::getInstanceOf($dbms,$w_cliente,$w_assunto,null,null,null,null,null,null,null,null,'REGISTROS');
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center">');
      ShowHTML('          <TABLE WIDTH="100%" BORDER="0">');
      ShowHTML('            <tr bgcolor="#DADADA">');
      ShowHTML('              <td><b>C�digo</td>');
      ShowHTML('              <td><b>Descri��o</td>');
      ShowHTML('              <td><b>Detalhamento</td>');
      ShowHTML('              <td><b>Observa��o</td>');
      ShowHTML('            </tr>');
      foreach($RS as $row) {
        ShowHTML('            <tr valign="top" bgcolor="#DADADA">');
        ShowHTML('              <td width="1%" nowrap>'.f($row,'codigo').'</td>');
        ShowHTML('              <td>');
        ShowHTML('                '.f($row,'descricao'));
        if (nvl(f($row,'ds_assunto_pai'),'')!='') { 
          echo '<br>';
          if (nvl(f($row,'ds_assunto_bis'),'')!='') ShowHTML(lower(f($row,'ds_assunto_bis')).' &rarr; ');
          if (nvl(f($row,'ds_assunto_avo'),'')!='') ShowHTML(lower(f($row,'ds_assunto_avo')).' &rarr; ');
          if (nvl(f($row,'ds_assunto_pai'),'')!='') ShowHTML(lower(f($row,'ds_assunto_pai')));
        }
        ShowHTML('              <td>'.nvl(f($row,'detalhamento'),'---').'</td>');
        ShowHTML('              <td>'.nvl(f($row,'observacao'),'---').'</td>');
      } 
      ShowHTML('            </table></tr>');
    }
    ShowHTML('      <tr><td colspan="5" title="Descreva de forma objetiva o conte�do do documento."><b>D<u>e</u>talhamento:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_descricao" class="STI" ROWS=5 cols=75>'.$w_descricao.'</TEXTAREA></td>');    
    ShowHTML('      <tr><td align="center" colspan="5">');
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
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
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
    // Se for recarga da p�gina
    $w_principal        = $_REQUEST['w_principal'];
    $w_nm_pessoa        = $_REQUEST['w_nm_pessoa'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getDocumentoInter::getInstanceOf($dbms,$w_chave,null,'N',null);
    $RS = SortArray($RS,'principal','desc','nome','asc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados do registro informado
    $RS = db_getDocumentoInter::getInstanceOf($dbms,$w_chave,$w_chave_aux,'N',null);
    foreach($RS as $row){$RS=$row; break;}
    $w_nm_pessoa   = f($RS,'nome_resumido');
    $w_principal   = f($RS,'principal');
  } 

  Cabecalho();
  head();
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_chave_aux','Interessado','HIDDEN','1','1','18','','1');
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
  } else {
    BodyOpen(null);
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orienta��o:<ul>');
    ShowHTML('  <li>Insira cada um dos interessados complementares, lembrando que o interessado principal j� foi cadastrado na tela de identifica��o.');
    ShowHTML('  </ul></b></font></td>');
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Nome</td>');
    ShowHTML('          <td><b>CPF/CNPJ</td>');
    ShowHTML('          <td><b>RG/Inscri��o estadual</td>');
    ShowHTML('          <td><b>Passaporte</td>');
    ShowHTML('          <td><b>Sexo</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font size="2"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.nvl(f($row,'identificador_principal'),'---').'</td>');
        ShowHTML('        <td>'.nvl(f($row,'identificador_secundario'),'---').'</td>');
        ShowHTML('        <td>'.nvl(f($row,'nr_passaporte'),'---').'</td>');
        ShowHTML('        <td align="center">'.nvl(f($row,'nm_sexo'),'---').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave_aux').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma desvincula��o do pessoa ao documento?\');">Desvincular</A>&nbsp');
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
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_principal" value="N">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr>');
    SelecaoPessoaOrigem('<u>I</u>nteressado:','I','Clique na lupa para selecionar o interessado.',$w_chave_aux,null,'w_chave_aux',null,null,null);
    ShowHTML('      <tr><td align="center"><hr>');
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
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de assuntos
// -------------------------------------------------------------------------
function Assuntos() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_chave_aux        = $_REQUEST['w_chave_aux'];
    $w_principal        = $_REQUEST['w_principal'];
    $w_nm_assunto       = $_REQUEST['w_nm_assunto'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getDocumentoAssunto::getInstanceOf($dbms,$w_chave,null,'N',null);
    $RS = SortArray($RS,'descricao','asc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados do assunto informado
    $RS = db_getDocumentoAssunto::getInstanceOf($dbms,$w_chave,$w_chave_aux,'N',null);
    foreach($RS as $row){$RS=$row; break;}
    $w_principal            = f($RS,'principal');
  } 
  Cabecalho();
  head();
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) Validate('w_chave_aux','Assunto','1','1','1','100','1','1');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen(null);
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orienta��o:<ul>');
    ShowHTML('  <li>Se este documento tiver assuntos complementares, insira cada um deles.');
    ShowHTML('  <li>Para alterar o assunto principal do documento, use a tela de identifica��o.');
    ShowHTML('  </ul></b></font></td>');
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>C�digo</td>');
    ShowHTML('          <td><b>Descri��o</td>');
    ShowHTML('          <td><b>Detalhamento</td>');
    ShowHTML('          <td><b>Observa��o</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font size="2"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td width="1%" nowrap>'.f($row,'codigo').'</td>');
        ShowHTML('        <td>');
        ShowHTML('                '.f($row,'descricao'));
        if (nvl(f($row,'ds_assunto_pai'),'')!='') { 
          echo '<br>';
          if (nvl(f($row,'ds_assunto_bis'),'')!='') ShowHTML(lower(f($row,'ds_assunto_bis')).' &rarr; ');
          if (nvl(f($row,'ds_assunto_avo'),'')!='') ShowHTML(lower(f($row,'ds_assunto_avo')).' &rarr; ');
          if (nvl(f($row,'ds_assunto_pai'),'')!='') ShowHTML(lower(f($row,'ds_assunto_pai')));
        }
        ShowHTML('        <td>'.nvl(lower(f($row,'detalhamento')),'---').'</td>');
        ShowHTML('        <td>'.nvl(lower(f($row,'observacao')),'---').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave_aux').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma desvincula��o do assunto ao documento?\');">Desvincular</A>&nbsp');
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
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_principal" value="N">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    SelecaoAssuntoRadio('Assun<u>t</u>o:','T',null,$w_chave_aux,null,'w_chave_aux','FOLHA',null);
    if (nvl($w_chave_aux,'')!='') {
      $RS = db_getAssunto_PA::getInstanceOf($dbms,$w_cliente,$w_chave_aux,null,null,null,null,null,null,null,null,'REGISTROS');
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td>');
      ShowHTML('          <TABLE WIDTH="100%" bgcolor="'.$conTrAlternateBgColor.'" BORDER="1" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('            <tr align="center">');
      ShowHTML('              <td><b>C�digo</td>');
      ShowHTML('              <td><b>Descri��o</td>');
      ShowHTML('              <td><b>Detalhamento</td>');
      ShowHTML('              <td><b>Observa��o</td>');
      ShowHTML('            </tr>');
      foreach($RS as $row) {
        ShowHTML('            <tr valign="top">');
        ShowHTML('              <td width="1%" nowrap>'.f($row,'codigo').'</td>');
        ShowHTML('              <td>');
        ShowHTML('                '.f($row,'descricao'));
        if (nvl(f($row,'ds_assunto_pai'),'')!='') { 
          echo '<br>';
          if (nvl(f($row,'ds_assunto_bis'),'')!='') ShowHTML(lower(f($row,'ds_assunto_bis')).' &rarr; ');
          if (nvl(f($row,'ds_assunto_avo'),'')!='') ShowHTML(lower(f($row,'ds_assunto_avo')).' &rarr; ');
          if (nvl(f($row,'ds_assunto_pai'),'')!='') ShowHTML(lower(f($row,'ds_assunto_pai')));
        }
        ShowHTML('              <td>'.nvl(f($row,'detalhamento'),'---').'</td>');
        ShowHTML('              <td>'.nvl(f($row,'observacao'),'---').'</td>');
      } 
      ShowHTML('            </table></tr>');
    }
    ShowHTML('           </table>');
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
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
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
    // Se for recarga da p�gina 
    $w_nome      = $_REQUEST['w_nome'];
    $w_descricao = $_REQUEST['w_descricao'];
    $w_caminho   = $_REQUEST['w_caminho'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem 
    $RS = db_getSolicAnexo::getInstanceOf($dbms,$w_chave,null,$w_cliente);
    $RS = SortArray($RS,'nome','asc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados do endere�o informado 
    $RS = db_getSolicAnexo::getInstanceOf($dbms,$w_chave,$w_chave_aux,$w_cliente);
    foreach($RS as $row){$RS=$row; break;}
    $w_nome      = f($RS,'nome');
    $w_descricao = f($RS,'descricao');
    $w_caminho   = f($RS,'chave_aux');
  } 
  Cabecalho();
  head();
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','T�tulo','1','1','1','255','1','1'); 
      Validate('w_descricao','Descri��o','1','1','1','1000','1','1'); 
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
    ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orienta��o:<ul>');
    ShowHTML('  <li>Se necess�rio, insira cada um dos arquivos vinculados ao documento.');
    ShowHTML('  <li>Os arquivos devem estar em seu computador e podem ser de qualquer formato.');
    ShowHTML('  </ul></b></font></td>');
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem 
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>T�tulo</td>');
    ShowHTML('          <td><b>Descri��o</td>');
    ShowHTML('          <td><b>Tipo</td>');
    ShowHTML('          <td><b>KB</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem 
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem 
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>');
        ShowHTML('        <td>'.Nvl(f($row,'descricao'),'---').'</td>');
        ShowHTML('        <td>'.f($row,'tipo').'</td>');
        ShowHTML('        <td align="right">'.round(f($row,'tamanho')/1024,1).'&nbsp;</td>');
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
    ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG='.$SG.'&O='.$O.'&UploadID='.$UploadID.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
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
      ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b><font color="#BC3131">ATEN��O</font>: o tamanho m�ximo aceito para o arquivo � de '.(f($RS,'upload_maximo')/1024).' KBytes</b>.</font></td>');
      ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
    } 
    ShowHTML('      <tr><td><b><u>T</u>�tulo:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_nome" class="STI" SIZE="75" MAXLENGTH="255" VALUE="'.$w_nome.'" title="Informe o t�ulo do arquivo."></td>');
    ShowHTML('      <tr><td><b><u>D</u>escri��o:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_descricao" class="STI" ROWS=5 cols=65 title="Descreva o conte�do do arquivo.">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OBRIGAT�RIO. Clique no bot�o ao lado para localizar o arquivo. Ele ser� transferido automaticamente para o servidor.">');
    if ($w_caminho>'') ShowHTML('              <b>'.LinkArquivo('SS',$w_cliente,$w_caminho,'_blank','Clique para exibir o arquivo atual.','Exibir',null).'</b>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir" onClick="return confirm(\'Confirma a exclus�o do registro?\');">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
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
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    ShowHTML(' history.back(1);'); 
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de visualiza��o do novo layout de relat�rios
// -------------------------------------------------------------------------
function Visual($w_chave=null,$w_o=null,$w_usuario=null,$w_p1=null,$w_tipo=null,$w_identificacao=null,$w_responsavel=null,
    $w_assunto_princ=null,$w_orcamentaria=null,$w_indicador=null,$w_recurso=null,$w_interessado=null,$w_anexo=null,
    $w_meta=null,$w_ocorrencia=null,$w_consulta=null) {
  extract($GLOBALS);
  $w_chave    = nvl($w_chave,$_REQUEST['w_chave']);
  $w_tipo     = nvl($w_tipo,upper(trim($_REQUEST['w_tipo'])));
  $w_formato  = nvl($w_formato,upper(trim($_REQUEST['w_formato'])));
  if ($O=='T') {
    $w_identificacao    = upper(nvl($w_identificacao,'S'));
    $w_responsavel      = upper(nvl($w_responsavel,'S'));
    $w_assunto_princ    = upper(nvl($w_qualitativa,'S'));
    $w_orcamentaria     = upper(nvl($w_orcamentaria,'S'));
    $w_indicador        = upper(nvl($w_indicador,'S'));
    $w_recurso          = upper(nvl($w_recurso,'S'));
    $w_interessado      = upper(nvl($w_interessado,'S'));
    $w_anexo            = upper(nvl($w_anexo,'S'));
    $w_meta             = upper(nvl($w_meta,'S'));
    $w_ocorrencia       = upper(nvl($w_ocorrencia,'S'));
    $w_consulta         = upper(nvl($w_consulta,'N'));
  } else {
    $w_identificacao    = upper(nvl($w_identificacao,'S'));
    $w_responsavel      = upper(nvl($w_responsavel,'N'));
    $w_assunto_princ    = upper(nvl($w_qualitativa,'S'));
    $w_orcamentaria     = upper(nvl($w_orcamentaria,'N'));
    $w_indicador        = upper(nvl($w_indicador,'N'));
    $w_recurso          = upper(nvl($w_recurso,'N'));
    $w_interessado      = upper(nvl($w_interessado,'N'));
    $w_anexo            = upper(nvl($w_anexo,'N'));
    $w_meta             = upper(nvl($w_meta,'N'));
    $w_ocorrencia       = upper(nvl($w_ocorrencia,'S'));
    $w_consulta         = upper(nvl($w_consulta,'N'));
  }
  // Recupera o logo do cliente a ser usado nas listagens
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  if (f($RS,'logo')>'') $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
  if ($w_tipo=='PDF') {
    headerpdf(f($RS_Menu,'nome'),$w_pag);
    $w_embed = 'WORD';
  } elseif ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,f($RS_Menu,'nome'),0);
    $w_embed = 'WORD';
  } else {
    $RS_Cab = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PADCAD');
    Cabecalho();
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - '.f($RS_Cab,'nome').'</TITLE>');
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpenClean('onLoad=\'this.focus()\'; ');
    if ($w_embed!='WORD') CabecalhoRelatorio($w_cliente,f($RS_Cab,'nome'),4,$w_chave);
    $w_embed = 'HTML';
  }
  if ($w_embed!='WORD') ShowHTML('<center><B><font size=1>Clique <a class="hl" href="javascript:history.back(1);">aqui</a> para voltar � tela anterior</b></center>');
  // Chama a rotina de visualiza��o dos dados da a��o, na op��o 'Listagem'
  ShowHTML('<tr><td colspan="2" align="center">');
  ShowHTML(VisualDocumento($w_chave,$w_o,$w_usuario,$w_p1,$w_embed,$w_identificacao,$w_assunto_princ,$w_orcamentaria,$w_indicador,$w_recurso,$w_interessado,$w_anexo,$w_meta,$w_ocorrencia,$w_consulta));
  if ($w_embed!='WORD') ShowHTML('<center><B><font size=1>Clique <a class="hl" href="javascript:history.back(1);">aqui</a> para voltar � tela anterior</b></center>');
  if     ($w_tipo=='PDF')  RodapePDF();
  elseif ($w_tipo!='WORD') Rodape();
} 

// =========================================================================
// Rotina de exclus�o
// -------------------------------------------------------------------------
function Excluir() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_observacao = $_REQUEST['w_observacao'];
  } 
  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if (!(strpos('E',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
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
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados da a��o, na op��o 'Listagem'
  ShowHTML(VisualDocumento($w_chave,'V',$w_usuario,$w_p1,$w_formato,'S','N','N','N','N','N','N','N','N','N'));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'PADGERAL',$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<INPUT type="hidden" name="w_unidade_posse" value="'.f($RS,'unidade_int_posse').'">');
  ShowHTML('<INPUT type="hidden" name="w_pessoa_posse" value="'.f($RS,'pessoa_ext_posse').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
// Rotina de tramita��o
// -------------------------------------------------------------------------
function Encaminhamento() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_tipo       = Nvl($_REQUEST['w_tipo'],'');
  $w_pede_unid  = false;
  
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_retorno_limite   = $_REQUEST['w_retorno_limite'];
    $w_interno          = $_REQUEST['w_interno'];
    $w_sq_unidade       = $_REQUEST['w_sq_unidade'];
    $w_pessoa_destino   = $_REQUEST['w_pessoa_destino'];
    $w_unidade_externa  = $_REQUEST['w_unidade_externa'];
    $w_tramite          = $_REQUEST['w_tramite'];
    $w_novo_tramite     = $_REQUEST['w_novo_tramite'];
    $w_tipo_despacho    = $_REQUEST['w_tipo_despacho'];
    $w_despacho         = $_REQUEST['w_despacho'];
    $w_aviso            = $_REQUEST['w_aviso'];
    $w_dias             = $_REQUEST['w_dias'];
  	$w_protocolo        = $_REQUEST['w_protocolo'];
  }

  $RS_Solic = db_getSolicData::getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));
  
  if (strpos(upper(f($RS_Solic,'nm_especie')),'CIRCULAR')!==false) {
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpenClean('onLoad=\'this.focus()\';');
    $Estrutura_Topo_Limpo;
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    $Estrutura_Texto_Abre;
    ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center"><font size=2 color="red">');
    ShowHTML('   A tramita��o de circulares s� pode ser feita atrav�s de op��o espec�fica no menu principal.');
    ShowHTML('</td></tr>');
    ShowHTML('</table>');
    Rodape();
    exit();
  }
  
  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if (strpos('V',$O)!==false) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    FormataProtocolo();
    ValidateOpen('Validacao');
    Validate('w_retorno_limite','Prazo de resposta','DATA','',10,10,'','0123456789/');
    CompData('w_retorno_limite','Prazo de resposta','>=',FormataDataEdicao(time()),'data atual');
    Validate('w_dias','Dias para encaminhamento','1','',1,3,'','0123456789');
    ShowHTML('  if (theForm.w_aviso[0].checked) {');
    ShowHTML('     if (theForm.w_dias.value == \'\') {');
    ShowHTML('        alert(\'Informe a partir de quantos dias ap�s o envio voc� deseja ser avisado!\');');
    ShowHTML('        theForm.w_dias.focus();');
    ShowHTML('        return false;');
    ShowHTML('     }');
    ShowHTML('  }');
    ShowHTML('  else {');
    ShowHTML('     theForm.w_dias.value = \'\';');
    ShowHTML('  }');
    Validate('w_interno','Tipo da unidade/pessoa','SELECT',1,1,1,'SN','');
    if ($w_interno=='N') {
      Validate('w_pessoa_destino','Pessoa de destino','HIDDEN',1,1,18,'','0123456789');
      Validate('w_unidade_externa','Unidade externa','','',2,60,'1','1');
    } else {
      Validate('w_sq_unidade','Unidade de destino','SELECT',1,1,18,'','0123456789');
    }
    Validate('w_tipo_despacho','Despacho','SELECT',1,1,18,'','0123456789');

	if ($w_tipo_despacho==f($RS_Parametro,'despacho_apensar') || $w_tipo_despacho==f($RS_Parametro,'despacho_anexar')) {
      Validate('w_protocolo_nm','ao processo','hidden','1','20','20','','0123456789./-'); 
    }

    Validate('w_despacho','Detalhamento do despacho','','','1','2000','1','1');
    Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    if ($P1!=1 || ($P1==1 && $w_tipo=='Volta')) {
      // Se n�o for encaminhamento e nem o sub-menu do cadastramento
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
    BodyOpen('onLoad=\'document.Form.w_retorno_limite.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align="center">');
  ShowHTML('<table width="95%" border="0" cellspacing="3">');
  // Chama a rotina de visualiza��o dos dados da a��o, na op��o 'Listagem'
  ShowHTML(VisualDocumento($w_chave,'V',$w_usuario,$w_p1,$w_formato,'S','N','N','N','N','N','N','N','N','N'));
  ShowHTML('<HR>');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%"><tr valign="top">');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'PADENVIO',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_unidade_posse" value="'.f($RS_Solic,'unidade_int_posse').'">');
  ShowHTML('<INPUT type="hidden" name="w_pessoa_posse" value="'.f($RS_Solic,'pessoa_ext_posse').'">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS_Solic,'sq_siw_tramite').'">');
  ShowHTML('      <tr><td colspan="3"  bgcolor="#f0f0f0" align=justify><font size="2"><b>NOVO TR�MITE</b></font></td></tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('           <td><b>Data de recebimento:</b><br>'.formataDataEdicao(f($RS_Solic,'data_recebimento')).'</td>');
  ShowHTML('           <td colspan=2><b>Unidade remetente:</b><br>'.f($RS_Solic,'nm_unid_origem').'</td>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('           <td title="Informe a data limite para que o destinat�rio encaminhe o documento."><b>Praz<u>o</u> de resposta:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_retorno_limite" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_retorno_limite.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_retorno_limite').'</td>');
  MontaRadioNS('<b>Emite alerta de n�o encaminhamento?</b>',$w_aviso,'w_aviso');
  ShowHTML('           <td valign="top"><b>Quantos <U>d</U>ias ap�s esta tramita��o?<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="w_dias" size="3" maxlength="3" value="'.$w_dias.'" title="A partir de quantos dias ap�s este encaminhamento o sistema deve emitir o alerta."></td>');
  ShowHTML('      <tr><td>&nbsp;</td></br>');
  ShowHTML('      <tr><td colspan="3"  bgcolor="#f0f0f0" align=justify><font size="2"><b>DESTINO</b></font></td></tr>');
  ShowHTML('      <tr valign="top">');
  selecaoOrigem('<u>T</u>ipo da unidade/pessoa:','T','Indique se a unidade ou pessoa � interna ou externa.',$w_interno,null,'w_interno',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_interno\'; document.Form.submit();"');
  if ($w_interno=='N') {
    SelecaoPessoaOrigem('<u>P</u>essoa de destino:','P','Clique na lupa para selecionar a pessoa de destino.',$w_pessoa_destino,null,'w_pessoa_destino',null,null,null);
    ShowHTML('        <td><b>U<U>n</U>idade externa: (preencher apenas para pessoas jur�dicas)<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="w_unidade_externa" size="30" maxlength="60" value="'.$w_unidade_externa.'"></td>');
  } else {
    ShowHTML('          <td colspan=2><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade de destino:','U','Selecione a unidade de destino.',$w_sq_unidade,null,'w_sq_unidade','MOD_PA',null);
    ShowHTML('           </table>');
  }
  ShowHTML('     <tr valign="top">');
  selecaoTipoDespacho('Des<u>p</u>acho:','P','Selecione o despacho desejado.',$w_cliente,$w_tipo_despacho,null,'w_tipo_despacho','SELECAO','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_tipo_despacho\'; document.Form.submit();"');
  if ($w_tipo_despacho==f($RS_Parametro,'despacho_apensar') || $w_tipo_despacho==f($RS_Parametro,'despacho_anexar')) {
    //ShowHTML('        <td><b>ao <u>p</u>rocesso:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="w_protocolo" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_protocolo.'" onKeyDown="FormataProtocolo(this,event);"></td>');
    SelecaoProtocolo('ao <U>p</U>rocesso:','U','Selecione o processo ao qual o protocolo ser� juntado.',$w_protocolo,null,'w_protocolo','JUNTADA',null);
  }
  ShowHTML('           </table>');
  ShowHTML('    <tr><td valign="top" colspan=3><b>Detalhamento do d<u>e</u>spacho:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Detalhe a a��o a ser executada pelo destinat�rio.">'.$w_despacho.'</TEXTAREA></td>');

  ShowHTML('      <tr><td align="LEFT" colspan=3><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=3><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
  if ($P1!=1 || ($P1==1 && $w_tipo=='Volta')) {
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  } 
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
// Rotina de anota��o
// -------------------------------------------------------------------------
function Anotar() {
  extract($GLOBALS);
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_observacao = $_REQUEST['w_observacao'];
  } 
  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if (!(strpos('V',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_observacao','Anota��o','','1','1','2000','1','1');
    Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
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
    BodyOpen('onLoad=\'document.Form.w_observacao.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align="center">');
  ShowHTML('<table width="95%" border="0" cellspacing="3">');
  // Chama a rotina de visualiza��o dos dados da a��o, na op��o 'Listagem'
  ShowHTML(VisualDocumento($w_chave,'V',$w_usuario,$w_p1,$w_formato,'S','N','N','N','N','N','N','N','N','N'));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'PADENVIO',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<INPUT type="hidden" name="w_unidade_posse" value="'.f($RS,'unidade_int_posse').'">');
  ShowHTML('<INPUT type="hidden" name="w_pessoa_posse" value="'.f($RS,'pessoa_ext_posse').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  ShowHTML('    <tr><td valign="top"><b>A<u>n</u>ota��o:</b><br><textarea '.$w_Disabled.' accesskey="N" name="w_observacao" class="STI" ROWS=5 cols=75 title="Redija a anota��o desejada.">'.$w_observacao.'</TEXTAREA></td>');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
// Rotina de conclus�o
// -------------------------------------------------------------------------
function Concluir() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_inicio_real      = $_REQUEST['w_inicio_real'];
    $w_fim_real         = $_REQUEST['w_fim_real'];
    $w_concluida        = $_REQUEST['w_concluida'];
    $w_data_conclusao   = $_REQUEST['w_data_conclusao'];
    $w_nota_conclusao   = $_REQUEST['w_nota_conclusao'];
    $w_custo_real       = $_REQUEST['w_custo_real'];
  } 
  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if (!(strpos('V',$O)===false)) {
    ScriptOpen('JavaScript');
    checkbranco();
    FormataData();
    SaltaCampo();
    FormataDataHora();
    FormataValor();
    ValidateOpen('Validacao');
    Validate('w_inicio_real','In�cio da execu��o', 'DATA', 1, 10, 10, '', '0123456789/');
    Validate('w_fim_real','T�rmino da execu��o', 'DATA', 1, 10, 10, '', '0123456789/');
    CompData('w_inicio_real','In�cio da execu��o','<=','w_fim_real','T�rmino da execu��o');
    CompData('w_fim_real','T�rmino da execu��o','<=',FormataDataEdicao(time()),'data atual');
    Validate('w_custo_real','Recurso executado', 'VALOR', '1', 4, 18, '', '0123456789.,');
    Validate('w_nota_conclusao','Nota de conclus�o', '', '1', '1', '2000', '1', '1');
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
    BodyOpen('onLoad=\'document.Form.w_inicio_real.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align="center">');
  ShowHTML('<table width="95%" border="0" cellspacing="3">');
  // Chama a rotina de visualiza��o dos dados da a��o, na op��o 'Listagem'
  ShowHTML(VisualDocumento($w_chave,'V',$w_usuario,$w_p1,$w_formato,'S','N','N','N','N','N','N','N','N','N'));
  ShowHTML('<HR>');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
  ShowHTML('          <tr>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'PADCONC',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_concluida" value="S">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<INPUT type="hidden" name="w_unidade_posse" value="'.f($RS,'unidade_int_posse').'">');
  ShowHTML('<INPUT type="hidden" name="w_pessoa_posse" value="'.f($RS,'pessoa_ext_posse').'">');
  if (Nvl(f($RS,'cd_programa'),'')>'') {
    ShowHTML('              <td valign="top"><b>In�<u>c</u>io da execu��o:</b><br><input readonly '.$w_Disabled.' accesskey="C" type="text" name="w_inicio_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_inicio_real,'01/01/'.$w_ano).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de in�cio da execu��o do programa.(Usar formato dd/mm/aaaa)"></td>');
    ShowHTML('              <td valign="top"><b><u>T</u>�rmino da execu��o:</b><br><input readonly '.$w_Disabled.' accesskey="T" type="text" name="w_fim_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_fim_real,'31/12/'.$w_ano).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de t�rmino da execu��o do programa.(Usar formato dd/mm/aaaa)"></td>');
  } else {
    ShowHTML('              <td valign="top"><b>In�<u>c</u>io da execu��o:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="'.Nvl($w_inicio_real,'01/01/'.$w_ano).'" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio_real.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de in�cio da execu��o do programa.(Usar formato dd/mm/aaaa)"></td>');
    ShowHTML('              <td valign="top"><b><u>T</u>�rmino da execu��o:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="'.Nvl($w_fim_real,'31/12/'.$w_ano).'" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim_real.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de t�rmino da execu��o do programa.(Usar formato dd/mm/aaaa)"></td>');
  } 
  ShowHTML('              <td valign="top"><b><u>R</u>ecurso executado:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_custo_real" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_custo_real.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor que foi efetivamente gasto com a execu��o do programa."></td>');
  ShowHTML('          </table>');
  ShowHTML('    <tr><td valign="top"><b>Nota d<u>e</u> conclus�o:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_nota_conclusao" class="STI" ROWS=5 cols=75 title="Insira informa��es relevantes sobre o encerramento do exerc�cio.">'.$w_nota_conclusao.'</TEXTAREA></td>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
// Rotina de prepara��o para envio de e-mail relativo a programas
// Finalidade: preparar os dados necess�rios ao envio autom�tico de e-mail
// Par�metro: p_solic: n�mero de identifica��o da solicita��o. 
//            p_tipo:  1 - Inclus�o
//                     2 - Tramita��o
//                     3 - Conclus�o
// -------------------------------------------------------------------------
function SolicMail($p_solic,$p_tipo) {
  extract($GLOBALS);
  //Verifica se o cliente est� configurado para receber email na tramita�ao de solicitacao
  $RS = db_getCustomerData::getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
  $RSM = db_getSolicData::getInstanceOf($dbms,$p_solic,f($RS_Menu,'sigla'));
  if(f($RS,'envia_mail_tramite')=='S' && (f($RS_Menu,'envia_email')=='S') && (f($RSM,'envia_mail')=='S')) {
    $l_solic          = $p_solic;
    $w_destinatarios  = '';
    $w_resultado      = '';
    $w_html='<HTML>'.$crlf;
    $w_html.=$BodyOpenMail[null].$crlf;
    $w_html.='<table border="0" cellpadding="0" cellspacing="0" width="100%">'.$crlf;
    $w_html.='<tr bgcolor="'.$conTrBgColor.'"><td align="center">'.$crlf;
    $w_html.='    <table width="97%" border="0">'.$crlf;
    if ($p_tipo==1)       $w_html.='      <tr valign="top"><td align="center"><font size=2><b>INCLUS�O DE PROGRAMA</b></font><br><br><td></tr>'.$crlf;
    elseif ($p_tipo==2)   $w_html.='      <tr valign="top"><td align="center"><font size=2><b>TRAMITA��O DE PROGRAMA</b></font><br><br><td></tr>'.$crlf;
    elseif ($p_tipo==3)   $w_html.='      <tr valign="top"><td align="center"><font size=2><b>CONCLUS�O DE PROGRAMA</b></font><br><br><td></tr>'.$crlf;
    $w_html.='      <tr valign="top"><td><font size=2><b><font color="#BC3131">ATEN��O</font>: Esta � uma mensagem de envio autom�tico. N�o responda esta mensagem.</b></font><br><br><td></tr>'.$crlf;
    // Recupera os dados da a��o
    $w_nome = 'Programa '.f($RSM,'titulo');
    $w_html.=$crlf.'<tr bgcolor="'.$conTrBgColor.'"><td align="center">';
    $w_html.=$crlf.'    <table width="99%" border="0">';
    $w_html.=$crlf.'      <tr><td><font size=2>Programa: <b>'.f($RSM,'titulo').'</b></font></td>';
    // Identifica��o da a��o
    $w_html.=$crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>EXTRATO DO PROGRAMA</td>';
    $w_html.=$crlf.'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    $w_html.=$crlf.'          <tr valign="top">';
    $w_html.=$crlf.'          <td>Respons�vel pelo monitoramento:<br><b>'.f($RSM,'nm_sol').'</b></td>';
    $w_html.=$crlf.'          <td>�rea de planejamento:<br><b>'.f($RSM,'nm_unidade_resp').'</b></td>';
    $w_html.=$crlf.'          <tr valign="top">';
    $w_html.=$crlf.'          <td>Data de in�cio:<br><b>'.$FormataDataEdicao[f($RSM,'inicio')].' </b></td>';
    $w_html.=$crlf.'          <td>Data de t�rmino:<br><b>'.$FormataDataEdicao[f($RSM,'fim')].' </b></td>';
    $w_html.=$crlf.'          </table>';
    // Informa��es adicionais
    if (Nvl(f($RSM,'descricao'),'')>'') $w_html.=$crlf.'      <tr><td valign="top">Resultados esperados:<br><b>'.CRLF2BR(f($RSM,'descricao')).' </b></td>';
    $w_html.=$crlf.'    </table>';
    $w_html.=$crlf.'</tr>';
    // Dados da conclus�o do programa, se ele estiver nessa situa��o
    if (f($RSM,'concluida')=='S' && Nvl(f($RSM,'data_conclusao'),'')>'') {
      $w_html.=$crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>DADOS DA CONCLUS�O</td>';
      $w_html.=$crlf.'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html.=$crlf.'          <tr valign="top">';
      $w_html.=$crlf.'          <td>In�cio da execu��o:<br><b>'.$FormataDataEdicao[f($RSM,'inicio_real')].' </b></td>';
      $w_html.=$crlf.'          <td>T�rmino da execu��o:<br><b>'.$FormataDataEdicao[f($RSM,'fim_real')].' </b></td>';
      $w_html.=$crlf.'          </table>';
      $w_html.=$crlf.'      <tr><td valign="top">Nota de conclus�o:<br><b>'.CRLF2BR(f($RSM,'nota_conclusao')).' </b></td>';
    } 
    if ($p_tipo==2) {
      // Se for tramita��o
      // Encaminhamentos
      $RS = db_getSolicLog::getInstanceOf($dbms,$p_solic,null,'LISTA');
      $RS = SortArray($RS,'phpdt_data','desc','despacho','desc');
      foreach ($RS as $row) { $RS = $row; if(strpos(f($row,'despacho'),'*** Nova vers�o')===false) break; }
      $w_html.=$crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>�LTIMO ENCAMINHAMENTO</td>';
      $w_html.=$crlf.'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html.=$crlf.'          <tr valign="top">';
      $w_html.=$crlf.'          <td>De:<br><b>'.f($RS,'responsavel').'</b></td>';
      $w_html.=$crlf.'          <td>Para:<br><b>'.f($RS,'destinatario').'</b></td>';
      $w_html.=$crlf.'          <tr valign="top"><td colspan=2>Despacho:<br><b>'.CRLF2BR(Nvl(f($RS,'despacho'),'---')).' </b></td>';
      $w_html.=$crlf.'          </table>';
      // Configura o destinat�rio da tramita��o como destinat�rio da mensagem
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,nvl(f($RS,'sq_pessoa_destinatario'),0),null,null);
      $w_destinatarios = f($RS,'email').'|'.f($RS,'nome').'; ';
    } 
    $w_html.=$crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>OUTRAS INFORMA��ES</td>';
    $RS = db_getCustomerSite::getInstanceOf($dbms,$w_cliente);
    $w_html.='      <tr valign="top"><td><font size=2>'.$crlf;
    $w_html.='         Para acessar o sistema use o endere�o: <b><a class="SS" href="'.f($RS,'logradouro').'" target="_blank">'.f($RS,'Logradouro').'</a></b></li>'.$crlf;
    $w_html.='      </font></td></tr>'.$crlf;
    $w_html.='      <tr valign="top"><td><font size=2>'.$crlf;
    $w_html.='         Dados da ocorr�ncia:<br>'.$crlf;
    $w_html.='         <ul>'.$crlf;
    $w_html.='         <li>Respons�vel: <b>'.$_SESSION['NOME'].'</b></li>'.$crlf;
    $w_html .= '         <li>Data: <b>'.date('d/m/Y, H:i:s',$w_data_encaminhamento).'</b></li>'.$crlf;
    $w_html.='         <li>IP de origem: <b>'.$_SERVER['REMOTE_ADDR'].'</b></li>'.$crlf;
    $w_html.='         </ul>'.$crlf;
    $w_html.='      </font></td></tr>'.$crlf;
    $w_html.='    </table>'.$crlf;
    $w_html.='</td></tr>'.$crlf;
    $w_html.='</table>'.$crlf;
    $w_html.='</BODY>'.$crlf;
    $w_html.='</HTML>'.$crlf;
    if(f($RSM,'st_sol')=='S') {
      // Recupera o e-mail do respons�vel
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,f($RSM,'solicitante'),null,null);
      $w_destinatarios .= f($RS,'email').'|'.f($RS,'nome').'; ';
    }
    // Recupera o e-mail do titular e do substituto pelo setor respons�vel
    $RS = db_getUorgResp::getInstanceOf($dbms,f($RSM,'sq_unidade'));
    foreach($RS as $row){$RS=$row; break;}
    if(f($RS,'st_titular')=='S')    $w_destinatarios .= f($RS,'email_titular').'|'.f($RS,'nm_titular').'; ';
    if(f($RS,'st_substituto')=='S') $w_destinatarios .= f($RS,'email_substituto').'|'.f($RS,'nm_substituto').'; ';
    // Recuperar o e-mail dos interessados
    $RS = db_getSolicInter::getInstanceOf($dbms,$p_solic,null,'LISTA');
    foreach($RS as $row) {
      if(f($row,'ativo')=='S' && f($row,'envia_email') =='S') $w_destinatarios .= f($row,'email').'|'.f($row,'nome').'; ';
    }  
    // Prepara os dados necess�rios ao envio
    if ($p_tipo==1 || $p_tipo==3) {
      // Inclus�o ou Conclus�o
      if ($p_tipo==1) $w_assunto = 'Inclus�o - '.$w_nome; else $w_assunto = 'Conclus�o - '.$w_nome;
    } elseif ($p_tipo==2) {
      // Tramita��o
      $w_assunto = 'Tramita��o - '.$w_nome;
    } 
    if ($w_destinatarios>'') {
      // Executa o envio do e-mail
      $w_resultado = EnviaMail($w_assunto,$w_html,$w_destinatarios,null);
    } 
    // Se ocorreu algum erro, avisa da impossibilidade de envio
    if ($w_resultado>'') {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'ATEN��O: n�o foi poss�vel proceder o envio do e-mail.\n'.$w_resultado.'\');');
      ScriptClose();
    } 
  }
} 
// =========================================================================
// Rotina de busca de assuntos
// -------------------------------------------------------------------------
function BuscaAssunto() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_ano        = $_REQUEST['w_ano'];
  $w_nome       = upper($_REQUEST['w_nome']);
  $w_codigo     = upper($_REQUEST['w_codigo']);
  $w_cliente    = $_REQUEST['w_cliente'];
  $chaveaux     = $_REQUEST['chaveaux'];
  $restricao    = $_REQUEST['restricao'];
  $campo        = $_REQUEST['campo'];

  $RS = db_getAssunto_PA::getInstanceOf($dbms,$w_cliente,$chave,null,$w_codigo,$w_nome,null,null,null,null,'S','BUSCA');
  $RS = SortArray($RS,'provisorio','desc', 'codigo','asc', 'descricao', 'asc');
  Cabecalho();
  ShowHTML('<TITLE>Sele��o de assunto</TITLE>');
  head();
  Estrutura_CSS($w_cliente);
  ScriptOpen('JavaScript');
  ShowHTML('  function volta(l_codigo, l_nome, l_chave) {');
  ShowHTML("     opener.document.Form.".$campo."_nm.value=l_codigo + ' - ' + l_nome;");
  ShowHTML('     opener.document.Form.'.$campo.'.value=l_chave;');
  ShowHTML('     opener.document.Form.'.$campo.'_nm.focus();');
  ShowHTML('     window.close();');
  ShowHTML('     opener.focus();');
  ShowHTML('   }');
  if (count($RS)>200 || ($w_nome>'' || $w_codigo>'')) {
    ValidateOpen('Validacao');
    Validate('w_nome','Nome','1','','4','30','1','1');
    Validate('w_codigo','codigo','1','','2','10','1','1');
    ShowHTML('  if (theForm.w_nome.value == \'\' && theForm.w_codigo.value == \'\') {');
    ShowHTML('     alert (\'Informe um valor para o nome ou para o c�digo!\');');
    ShowHTML('     theForm.w_nome.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
  } 
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  if (count($RS)>200 || ($w_nome>'' || $w_codigo>'')) {
    BodyOpen('onLoad=\'document.Form.w_nome.focus();\'');
  } else {
    BodyOpen('onLoad=this.focus();');
  } 
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="100%" border="0">');
  if (count($RS)>200 || ($w_nome>'' || $w_codigo>'')) {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this))',null,$P1,$P2,$P3,$P4,$TP,$SG,null,null);
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="chaveaux" value="'.$chaveaux.'">');
    ShowHTML('<INPUT type="hidden" name="restricao" value="'.$restricao.'">');
    ShowHTML('<INPUT type="hidden" name="campo" value="'.$campo.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><b><ul>Instru��es</b>:<li>Informe parte do nome do assunto ou o c�digo.<li>Quando a rela��o for exibida, selecione o assunto desejada clicando sobre a palavra <i>"Selecionar"</i> ao seu lado.<li>Ap�s informar o nome da unidade, clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Cancelar</i>, a procura � cancelada.</ul></div>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td valign="top"><b>Parte da <U>d</U>escri��o do assunto:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="w_nome" size="30" maxlength="30" value="'.$w_nome.'">');
    ShowHTML('      <tr><td valign="top"><b>Parte do <U>C</U>�digo:<br><INPUT ACCESSKEY="S" '.$w_Disabled.' class="sti" type="text" name="w_codigo" size="10" maxlength="10" value="'.$w_codigo.'">');
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
    if ($w_nome>'' || $w_codigo>'') {
      ShowHTML('<tr><td align="right"><b>Registros: '.count($RS));
      ShowHTML('<tr><td>');
      ShowHTML('    <TABLE WIDTH="100%" border=0>');
      if (count($RS)<=0) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
      } else {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td>');
        ShowHTML('        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('            <td><b>C�digo</td>');
        ShowHTML('            <td><b>Descri��o</td>');
        ShowHTML('            <td><b>Detalhamento</td>');
        ShowHTML('            <td><b>Observa��o</td>');
        ShowHTML('            <td><b>Opera��es</td>');
        ShowHTML('          </tr>');
        foreach($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('            <td width="1%" nowrap>'.f($row,'codigo').'</td>');
          ShowHTML('            <td>');
          ShowHTML('                '.f($row,'descricao'));
          if (nvl(f($row,'ds_assunto_pai'),'')!='') { 
            echo '<br>';
            if (nvl(f($row,'ds_assunto_bis'),'')!='') ShowHTML(lower(f($row,'ds_assunto_bis')).' &rarr; ');
            if (nvl(f($row,'ds_assunto_avo'),'')!='') ShowHTML(lower(f($row,'ds_assunto_avo')).' &rarr; ');
            if (nvl(f($row,'ds_assunto_pai'),'')!='') ShowHTML(lower(f($row,'ds_assunto_pai')));
          }
          ShowHTML('            </td>');
          ShowHTML('            <td>'.nvl(lower(f($row,'detalhamento')),'---').'</td>');
          ShowHTML('            <td>'.nvl(f($row,'observacao'),'---').'</td>');
          ShowHTML('            <td><a class="ss" HREF="javascript:this.status.value;" onClick="javascript:volta(\''.f($row,'codigo').'\', \''.f($row,'descricao').'\', '.f($row,'chave').');">Selecionar</a>');
        } 
        ShowHTML('        </table></tr>');
        ShowHTML('      </center>');
        ShowHTML('    </table>');
        ShowHTML('  </td>');
        ShowHTML('</tr>');
      } 
    } 
  } else {
    ShowHTML('<tr><td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td colspan=6>');
    ShowHTML('    <TABLE WIDTH="100%" border=0>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td>');
        ShowHTML('        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('            <td rowspan=2><b>C�digo</td>');
        ShowHTML('            <td rowspan=2><b>Descri��o</td>');
        ShowHTML('            <td rowspan=2><b>Detalhamento</td>');
        ShowHTML('            <td rowspan=2><b>Observa��o</td>');
        ShowHTML('            <td colspan=2><b>Prazos de Guarda</td>');
        ShowHTML('            <td rowspan=2><b>Destina��o Final</td>');
        ShowHTML('          </tr>');
        ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('            <td><b>Corrente</td>');
        ShowHTML('            <td><b>Intermedi�ria</td>');
        ShowHTML('          </tr>');
        $w_atual = '';
        foreach($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          if ($w_atual!=nvl(f($row,'ds_assunto_pai'),'')) { 
            $w_cor = $w_cor=$conTrAlternateBgColor;
            ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
            ShowHTML('            <td><b>'.f($row,'cd_assunto_pai').'</b></td>');
            ShowHTML('            <td colspan="7"><b>');
            if (nvl(f($row,'ds_assunto_bis'),'')!='') ShowHTML(f($row,'ds_assunto_bis').' &rarr; ');
            if (nvl(f($row,'ds_assunto_avo'),'')!='') ShowHTML(f($row,'ds_assunto_avo').' &rarr; ');
            if (nvl(f($row,'ds_assunto_pai'),'')!='') ShowHTML(f($row,'ds_assunto_pai'));
            ShowHTML('            </b></td>');
            ShowHTML('      </tr>');
            $w_atual = f($row,'ds_assunto_pai');
          }
          $w_cor = $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('            <td><a class="ss" HREF="javascript:this.status.value;" onClick="javascript:volta(\''.f($row,'codigo').'\', \''.f($row,'descricao').'\', '.f($row,'chave').');">'.f($row,'codigo').'</a></td>');
          ShowHTML('            <td>'.f($row,'descricao'));
          ShowHTML('            </td>');
          ShowHTML('            <td>'.nvl(lower(f($row,'detalhamento')),'---').'</td>');
          ShowHTML('            <td>'.nvl(f($row,'observacao'),'---').'</td>');
          if (f($row,'sg_corrente_guarda')=='NAPL') ShowHTML('            <td align="center">---</td>'); else ShowHTML('            <td align="center" '.((f($row,'sg_corrente_guarda')!='ANOS') ? 'title="'.f($row,'ds_corrente_guarda').'"' : '').'>'.f($row,'guarda_corrente').'</td>');
          if (f($row,'sg_intermed_guarda')=='NAPL') ShowHTML('            <td align="center">---</td>'); else ShowHTML('            <td align="center" '.((f($row,'sg_intermed_guarda')!='ANOS') ? 'title="'.f($row,'ds_intermed_guarda').'"' : '').'>'.f($row,'guarda_intermed').'</td>');
          if (f($row,'sg_final_guarda')=='NAPL') ShowHTML('            <td align="center">---</td>'); else ShowHTML('            <td align="center" title="'.f($row,'ds_destinacao_final').'">'.f($row,'sg_destinacao_final').'</td>');
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
  rodape();
} 

// =========================================================================
// Executa a tramita��o de protocolos
// -------------------------------------------------------------------------
function Tramitacao() {
  extract($GLOBALS);
  global $w_Disabled;
  if(is_array($_REQUEST['w_chave'])){
    $itens = $_REQUEST['w_chave'];
  }else{
    $itens = explode(',',$_REQUEST['w_chave']);
  }

  
  // Recupera as vari�veis utilizadas na filtragem
  $p_protocolo      = $_REQUEST['p_protocolo'];
  $p_chave          = explodeArray($_REQUEST['p_chave']);
  $p_chave_aux      = $_REQUEST['p_chave_aux'];
  $p_prefixo        = $_REQUEST['p_prefixo'];
  $p_numero         = $_REQUEST['p_numero'];
  $p_ano            = $_REQUEST['p_ano'];
  $p_unid_autua     = $_REQUEST['p_unid_autua'];
  $p_unid_posse     = $_REQUEST['p_unid_posse'];
  $p_nu_guia        = $_REQUEST['p_nu_guia'];
  $p_ano_guia       = $_REQUEST['p_ano_guia'];
  $p_ini            = $_REQUEST['p_ini'];
  $p_fim            = $_REQUEST['p_fim'];
  $p_tipo_despacho  = nvl($_REQUEST['w_tipo_despacho'],$_REQUEST['p_tipo_despacho']);

  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_retorno_limite   = $_REQUEST['w_retorno_limite'];
    $w_interno          = $_REQUEST['w_interno'];
    $w_sq_unidade       = $_REQUEST['w_sq_unidade'];
    $w_pessoa_destino   = $_REQUEST['w_pessoa_destino'];
    $w_unidade_externa  = $_REQUEST['w_unidade_externa'];
    $w_despacho         = $_REQUEST['w_despacho'];
    $w_aviso            = $_REQUEST['w_aviso'];
    $w_dias             = $_REQUEST['w_dias'];
    $w_protocolo        = $_REQUEST['w_protocolo'];
  }
  
  if ($p_tipo_despacho>'') {
    $RS = db_getTipoDespacho_PA::getInstanceOf($dbms,$p_tipo_despacho,$w_cliente,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_nm_tipo_despacho = f($RS,'nome');
  }

  // Recupera os dados da unidade central de protocolo
  $RS_Prot = db_getUorgList::getInstanceOf($dbms,$w_cliente,null,'MOD_PA_PROT',null,null,$w_ano);
  foreach($RS_Prot as $row) { $RS_Prot = $row; break; }

  // Recupera os dados da unidade central de arquivo
  $RS_Arq = $RS = db_getUorgData::getInstanceOf($dbms,f($RS_Parametro,'arquivo_central'));
  
  if ($p_tipo_despacho==f($RS_Parametro,'despacho_autuar') || 
      $p_tipo_despacho==f($RS_Parametro,'despacho_arqcentral') ||  
      $p_tipo_despacho==f($RS_Parametro,'despacho_eliminar') 
     ) $w_envia_protocolo = 'S'; else $w_envia_protocolo = 'N';  

  if ($p_tipo_despacho==f($RS_Parametro,'despacho_arqcentral')) $w_envia_arquivo = 'S'; else $w_envia_arquivo = 'N';  

   if ($p_tipo_despacho==f($RS_Parametro,'despacho_arqcentral') ||
      $p_tipo_despacho==f($RS_Parametro,'despacho_arqsetorial')
     ) $w_interno = 'S';  
     
  if ($p_tipo_despacho==f($RS_Parametro,'despacho_desmembrar')) $w_desmembrar = 'S'; else $w_desmembrar = 'N';  
  
  if ($O=='L') {
    if ($p_tipo_despacho==f($RS_Parametro,'despacho_arqcentral')) {
      // Recupera caixas para transfer�ncia
      $RS = db_getCaixa::getInstanceOf($dbms, $p_chave, $w_cliente, nvl($w_sq_unidade,$_SESSION['LOTACAO']), null, null, null, null,null, null, null, 'TRAMITE');
      if (Nvl($p_ordena,'')>'') {
        $lista = explode(',',str_replace(' ',',',$p_ordena));
        $RS = SortArray($RS,$lista[0],$lista[1],'numero','asc');
       } else {
        $RS = SortArray($RS,'numero','asc');
      }
      $w_existe = count($RS);
      
      if (count($w_chave) > 0) {
        $i = 0;
        foreach($w_chave as $k => $v) {
          foreach ($RS as $row) {
            if ($w_chave[$i]==f($row,'sq_caixa')) {
              $w_marcado[f($row,'sq_caixa')] = 'ok';
              break;
            }
          }
          $i += 1;
        }
        reset($RS);
      }
    } else {
      // Recupera todos os registros para a listagem
      $RS = db_getProtocolo::getInstanceOf($dbms, f($RS_Menu,'sq_menu'), $w_usuario, $SG, $p_chave, $p_chave_aux, 
          $p_prefixo, $p_numero, $p_ano, $p_unid_autua, $p_unid_posse, $p_nu_guia, $p_ano_guia, $p_ini, $p_fim, 2, 
          $p_tipo_despacho);
      if (Nvl($p_ordena,'')>'') {
        $lista = explode(',',str_replace(' ',',',$p_ordena));
        $RS = SortArray($RS,$lista[0],$lista[1],'prefixo','asc', 'ano','desc','numero_documento','asc');
       } else {
        $RS = SortArray($RS,'prefixo','asc', 'ano','desc','numero_documento','asc');
      }
      $w_existe = count($RS);
      
      if (count($w_chave) > 0) {
        $i = 0;
        foreach($w_chave as $k => $v) {
          foreach ($RS as $row) {
            if ($w_chave[$i]==f($row,'sq_siw_solicitacao')) {
              $w_marcado[f($row,'sq_siw_solicitacao')] = 'ok';
              break;
            }
          }
          $i += 1;
        }
        reset($RS);
      }
    }
  } 
  Cabecalho();
  head();
  if ($O=='P') {
    ScriptOpen('JavaScript');
    FormataProtocolo();
    FormataData();
    SaltaCampo();
    CheckBranco();
    ValidateOpen('Validacao');
    Validate('p_tipo_despacho','Despacho','SELECT',1,1,18,'','0123456789');
    Validate('p_prefixo','Prefixo','1','','5','5','','0123456789'); 
    Validate('p_numero','N�mero','1','','1','6','','0123456789'); 
    Validate('p_ano','Ano','1','','4','4','','0123456789'); 
    ShowHTML('  if ((theForm.p_numero.value!="" && theForm.p_ano.value=="") || (theForm.p_numero.value=="" && theForm.p_ano.value!="")) {');
    ShowHTML('     alert ("Para pesquisa pelo protocolo informe pelo menos o n�mero e o ano!");');
    ShowHTML('     theForm.p_ano.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    Validate('p_ini','In�cio','DATA','','10','10','','0123456789/');
    Validate('p_fim','T�rmino','DATA','','10','10','','0123456789/');
    ShowHTML('  if ((theForm.p_ini.value != \'\' && theForm.p_fim.value == \'\') || (theForm.p_ini.value == \'\' && theForm.p_fim.value != \'\')) {');
    ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
    ShowHTML('     theForm.p_ini.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    CompData('p_ini','In�cio','<=','p_fim','T�rmino');
    ShowHTML('  theForm.Botao.disabled=true;');
    ValidateClose();
    ScriptClose();
  } elseif ($O=='L') {
    ScriptOpen('JavaScript');
    FormataProtocolo();
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    ShowHTML('  var i; ');
    ShowHTML('  var w_erro=true; ');
    ShowHTML('  for (i=1; i < theForm["w_chave[]"].length; i++) {');
    ShowHTML('    if (theForm["w_chave[]"][i].checked) {');
    ShowHTML('       w_erro=false; ');
    ShowHTML('    }');
    ShowHTML('  }');
    ShowHTML('  if (w_erro) {');
    if ($p_tipo_despacho==f($RS_Parametro,'despacho_arqcentral')) {
      ShowHTML('    alert(\'Voc� deve informar pelo menos uma caixa!\'); ');
    } else {
      ShowHTML('    alert(\'Voc� deve informar pelo menos um protocolo!\'); ');
    }
    ShowHTML('    return false;');
    ShowHTML('  }');
    if ($p_tipo_despacho==f($RS_Parametro,'despacho_apensar') || $p_tipo_despacho==f($RS_Parametro,'despacho_anexar')) {
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=false; ');
      ShowHTML('  for (i=1; i < theForm["w_chave[]"].length; i++) {');
      ShowHTML('    if (theForm.w_protocolo_nm.value == theForm["w_lista[]"][i].value && theForm["w_chave[]"][i].checked) {');
      ShowHTML('       w_erro=true; ');
      ShowHTML('    }');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert(\'N�o � poss�vel juntar um protocolo a ele mesmo!\'); ');
      ShowHTML('    return false;');
      ShowHTML('  }');    
    }
    if ($w_envia_protocolo=='N') {
      Validate('w_retorno_limite','Prazo de resposta','DATA','',10,10,'','0123456789/');
      CompData('w_retorno_limite','Prazo de resposta','>=',FormataDataEdicao(time()),'data atual');
      Validate('w_dias','Dias para encaminhamento','1','',1,3,'','0123456789');
      ShowHTML('  if (theForm.w_aviso[0].checked) {');
      ShowHTML('     if (theForm.w_dias.value == \'\') {');
      ShowHTML('        alert(\'Informe a partir de quantos dias ap�s o envio voc� deseja ser avisado!\');');
      ShowHTML('        theForm.w_dias.focus();');
      ShowHTML('        return false;');
      ShowHTML('     }');
      ShowHTML('  }');
      ShowHTML('  else {');
      ShowHTML('     theForm.w_dias.value = \'\';');
      ShowHTML('  }');
      Validate('w_interno','Tipo da unidade/pessoa','SELECT',1,1,1,'SN','');
      if ($w_interno=='N') {
        Validate('w_pessoa_destino','Pessoa de destino','HIDDEN',1,1,18,'','0123456789');
        Validate('w_unidade_externa','Unidade externa','','',2,60,'1','1');
      } else {
        if ($p_tipo_despacho==f($RS_Parametro,'despacho_arqsetorial')) {
          Validate('w_sq_unidade','Arquivo setorial','SELECT',1,1,18,'','0123456789');
        } else {
          Validate('w_sq_unidade','Unidade de destino','SELECT',1,1,18,'','0123456789');
        }
      }
    }
    if ($p_tipo_despacho==f($RS_Parametro,'despacho_apensar') || $p_tipo_despacho==f($RS_Parametro,'despacho_anexar')) {
      Validate('w_protocolo_nm','ao processo','hidden','1','20','20','','0123456789./-'); 
    }
    if ($w_desmembrar=='S') {
      Validate('w_despacho','Protocolos a serem desmembrados','','1','1','2000','1','1');
    } else {
      Validate('w_despacho','Detalhamento do despacho','','1','1','2000','1','1');
    }
    Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    ShowHTML('  if (!confirm(\'Confirma a gera��o de guia de tramita��o APENAS para '.(($p_tipo_despacho==f($RS_Parametro,'despacho_arqcentral')) ? 'as caixas selecionadas' : 'os documentos selecionados').'?\')) return false;');
    ShowHTML('  theForm.Botao.disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='P') {
    BodyOpen('onLoad=\'document.Form.p_tipo_despacho.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td colspan=3 bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">');
    ShowHTML('  ATEN��O:<ul>');
    ShowHTML('  <li>PROTOCOLOS JUNTADOS N�O PODEM SER ENVIADOS.');
    ShowHTML('  <li>Se o tr�mite for para pessoa jur�dica, n�o se esque�a de informar para qual unidade dessa entidade voc� est� enviando.');
    ShowHTML('  <li>Informe sua assinatura eletr�nica e clique sobre o bot�o <i>Gerar Guia de Tramita��o</i>.');
    ShowHTML('  </ul></b></font></td>');
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td colspan=2>');
    if (MontaFiltro('GET')>'') {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
    } 
    ShowHTML('    <td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    if ($p_tipo_despacho==f($RS_Parametro,'despacho_arqcentral')) {
      ShowHTML('          <td><b>&nbsp;</td>');
      ShowHTML('          <td width="1%" nowrap><b>Caixa</td>');
      ShowHTML('          <td width="1%" nowrap><b>Unidade</td>');
      ShowHTML('          <td><b>Assunto</td>');
      ShowHTML('          <td><b>Data Limite</td>');
      ShowHTML('          <td><b>Intermedi�rio</td>');
      ShowHTML('          <td><b>Destina��o final</td>');
      ShowHTML('        </tr>');
    } else {
      ShowHTML('          <td rowspan=2><b>&nbsp;</td>');
      ShowHTML('          <td rowspan=2><b>'.linkOrdena('Posse','sg_unidade_posse','Form').'</td>');
      ShowHTML('          <td rowspan=2 width="1%" nowrap><b>Protocolo</td>');
      ShowHTML('          <td rowspan=2 width="1%" nowrap><b>'.linkOrdena('Tipo','nm_tipo','Form').'</td>');
      ShowHTML('          <td colspan=4><b>Documento original</td>');
      ShowHTML('          <td rowspan=2><b>'.linkOrdena('Resumo','descricao','Form').'</td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>'.linkOrdena('Esp�cie','nm_especie','Form').'</td>');
      ShowHTML('          <td><b>'.linkOrdena('N�','numero_original','Form').'</td>');
      ShowHTML('          <td><b>'.linkOrdena('Data','inicio','Form').'</td>');
      ShowHTML('          <td><b>'.linkOrdena('Proced�ncia','nm_origem_doc','Form').'</td>');
      ShowHTML('        </tr>');
    }
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<input type="hidden" name="w_chave[]" value=""></td>');
    ShowHTML('<input type="hidden" name="w_lista[]" value=""></td>'); 
    ShowHTML('<INPUT type="hidden" name="w_unidade_posse" value="'.f($RS_Solic,'unidade_int_posse').'">');
    ShowHTML('<INPUT type="hidden" name="w_pessoa_posse" value="'.f($RS_Solic,'pessoa_ext_posse').'">');
    ShowHTML('<INPUT type="hidden" name="w_tipo_despacho" value="'.$p_tipo_despacho.'">');
    if (nvl($_REQUEST['p_ordena'],'')=='') ShowHTML('<INPUT type="hidden" name="p_ordena" value="">');
    ShowHTML(MontaFiltro('POST'));
    if ($p_tipo_despacho==f($RS_Parametro,'despacho_arqcentral')) {
      ShowHTML('<INPUT type="hidden" name="w_arq_central" value="S">');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_arq_central" value="N">');
    }
    if (count($RS)<=0) { 
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan="9" align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $w_atual = '';
      $i = 0;
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'">');
        if ($p_tipo_despacho==f($RS_Parametro,'despacho_arqcentral')) {
          ShowHTML('        <td align="center" width="1%" nowrap>'); 
          ShowHTML('          <INPUT type="hidden" name="w_unid_origem['.f($row,'sq_caixa').']" value="'.f($row,'sq_unidade').'">'); 
          ShowHTML('          <input type="CHECKBOX" '.((nvl($w_marcado[f($row,'sq_caixa')],'')!='') ? 'CHECKED' : '').' name="w_chave[]" value="'.f($row,'sq_caixa').'"></td>'); 
          ShowHTML('        </td>');
          ShowHTML('        <td align="center" width="1%" nowrap>&nbsp;<A onclick="window.open (\''.montaURL_JS($w_dir,'relatorio.php?par=ConteudoCaixa'.'&R='.$w_pagina.'IMPRIMIR'.'&O=L&w_chave='.f($row,'sq_caixa').'&w_formato=WORD&orientacao=PORTRAIT&&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\',\'Imprimir\',\'width=700,height=450, status=1,toolbar=yes,scrollbars=yes,resizable=yes\');" class="HL"  HREF="javascript:this.status.value;" title="Imprime a lista de protocolos arquivados na caixa.">'.f($row,'numero').'/'.f($row,'sg_unidade').'</a>&nbsp;');
          ShowHTML('        <td>'.f($row,'nm_unidade').'</td>');
          ShowHTML('        <td>'.f($row,'assunto').'</td>');
          ShowHTML('        <td align="center">'.formataDataEdicao(f($row,'data_limite')).'</td>');
          ShowHTML('        <td align="center">'.f($row,'intermediario').'</td>');
          ShowHTML('        <td>'.f($row,'destinacao_final').'</td>');
        } else {
	        // Valida cada documento
          $w_erro = ValidaDocumento($w_cliente,f($row,'sq_siw_solicitacao'),'PADCAD',null,null,null,f($row,'sg_tramite'));
	        if (nvl($w_erro,'')!='') {
	          $w_msg  = substr($w_erro,1);
	          $w_tipo = substr($w_erro,0,1);
	        } else {
	          $w_msg  = '';
	          $w_tipo = '';
	        }
          ShowHTML('        <td align="center" width="1%" nowrap>');
          if ($w_tipo=='') { 
            ShowHTML('          <INPUT type="hidden" name="w_tramite['.f($row,'sq_siw_solicitacao').']" value="'.f($row,'sq_siw_tramite').'">'); 
            ShowHTML('          <INPUT type="hidden" name="w_unid_origem['.f($row,'sq_siw_solicitacao').']" value="'.f($row,'unidade_int_posse').'">'); 
            ShowHTML('          <INPUT type="hidden" name="w_unid_autua['.f($row,'sq_siw_solicitacao').']" value="'.f($row,'unidade_autuacao').'">');
            ShowHTML('          <INPUT type="hidden" name="w_lista[]" value="'.f($row,'protocolo').'">'); 
            if (nvl($w_marcado[f($row,'sq_siw_solicitacao')],'')!='') {
              ShowHTML('          <input type="CHECKBOX" CHECKED name="w_chave[]" value="'.f($row,'sq_siw_solicitacao').'"></td>'); 
            } else {
              if(in_array(f($row,'sq_siw_solicitacao'),$itens)){
                ShowHTML('          <input type="CHECKBOX" CHECKED  name="w_chave[]" value="'.f($row,'sq_siw_solicitacao').'"></td>');
              }else{
                ShowHTML('          <input type="CHECKBOX"  name="w_chave[]" value="'.f($row,'sq_siw_solicitacao').'"></td>');
              }               
            }
          }
          ShowHTML('        </td>');
          ShowHTML('        <td width="1%" nowrap>&nbsp;'.ExibeUnidade('../',$w_cliente,f($row,'sg_unidade_posse'),f($row,'unidade_int_posse'),$TP).'</td>');
          ShowHTML('        <td align="center" width="1%" nowrap><A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" target="visualdoc" title="Exibe as informa��es deste registro.">'.f($row,'protocolo').'&nbsp;</a>');
          ShowHTML('        <td width="10">&nbsp;'.f($row,'nm_tipo').'</td>');
          ShowHTML('        <td width="1%" nowrap>&nbsp;'.f($row,'nm_especie').'</td>');
          ShowHTML('        <td width="1%" nowrap>&nbsp;'.f($row,'numero_original').'</td>');
          ShowHTML('        <td width="1%" nowrap>&nbsp;'.formataDataEdicao(f($row,'inicio'),5).'&nbsp;</td>');
          ShowHTML('        <td width="1%" nowrap>&nbsp;'.f($row,'nm_origem_doc').'</td>');
          if ($w_tipo=='') {
            if (strlen(Nvl(f($row,'descricao'),'-'))>50) $w_titulo=substr(Nvl(f($row,'descricao'),'-'),0,50).'...'; else $w_titulo=Nvl(f($row,'descricao'),'-');
            if (f($row,'sg_tramite')=='CA') ShowHTML('        <td width="50%" title="'.htmlspecialchars(f($row,'descricao')).'"><strike>'.htmlspecialchars($w_titulo).'</strike></td>');
            else                            ShowHTML('        <td width="50%" title="'.htmlspecialchars(f($row,'descricao')).'">'.htmlspecialchars($w_titulo).'</td>');
          } else {
            ShowHTML('        <td width="50%"><font color="#BC3131"><b>'.$w_msg.'</b></font></td>');
          }
        }
        ShowHTML('      </tr>');
        $i += 1;
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('      <tr><td colspan="3">&nbsp;</td></tr>');
    if ($w_envia_protocolo=='N') {
      ShowHTML('      <tr><td colspan="3"  bgcolor="#f0f0f0" align=justify><font size="2"><b>NOVO TR�MITE</b></font></td></tr>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('           <td title="Informe a data limite para que o destinat�rio encaminhe o documento."><b>Praz<u>o</u> de resposta:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_retorno_limite" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_retorno_limite.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_retorno_limite').'</td>');
      MontaRadioNS('<b>Emite alerta de n�o encaminhamento?</b>',$w_aviso,'w_aviso');
      ShowHTML('           <td valign="top"><b>Quantos <U>d</U>ias ap�s esta tramita��o?<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="w_dias" size="3" maxlength="3" value="'.$w_dias.'" title="A partir de quantos dias ap�s este encaminhamento o sistema deve emitir o alerta."></td>');
      ShowHTML('      <tr><td>&nbsp;</td></br>');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_aviso" value="N">');
      ShowHTML('<INPUT type="hidden" name="w_dias" value="0">');
    }
    if ($w_envia_arquivo=='S') {
      ShowHTML('      <tr><td colspan="3"  bgcolor="#f0f0f0" align=justify><font size="2"><b>DESTINO: '.upper(f($RS_Arq,'nome')).'</b></font></td></tr>');
      ShowHTML('<INPUT type="hidden" name="w_sq_unidade" value="'.f($RS_Arq,'sq_unidade').'">');
      ShowHTML('<INPUT type="hidden" name="w_interno" value="S">');
    } elseif ($w_envia_protocolo=='S') {
      ShowHTML('      <tr><td colspan="3"  bgcolor="#f0f0f0" align=justify><font size="2"><b>DESTINO: '.upper(f($RS_Prot,'nome')).'</b></font></td></tr>');
      ShowHTML('<INPUT type="hidden" name="w_sq_unidade" value="'.f($RS_Prot,'sq_unidade').'">');
      ShowHTML('<INPUT type="hidden" name="w_interno" value="S">');
    } else {
      ShowHTML('      <tr><td colspan="3"  bgcolor="#f0f0f0" align=justify><font size="2"><b>DESTINO</b></font></td></tr>');
      ShowHTML('      <tr valign="top">');
      if ($p_tipo_despacho==f($RS_Parametro,'despacho_arqsetorial')) {
        ShowHTML('<INPUT type="hidden" name="w_interno" value="'.$w_interno.'">');
      } else {
        selecaoOrigem('<u>T</u>ipo da unidade/pessoa:','T','Indique se a unidade ou pessoa � interna ou externa.',$w_interno,null,'w_interno',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_interno\'; document.Form.submit();"');
      }
      if ($w_interno=='N') {
        SelecaoPessoaOrigem('<u>P</u>essoa de destino:','P','Clique na lupa para selecionar a pessoa de destino.',$w_pessoa_destino,null,'w_pessoa_destino',null,null,null,2);
        ShowHTML('    <tr><td><td colspan="2"><b>U<U>n</U>idade externa: (Informe apenas para pessoas jur�dicas)<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="w_unidade_externa" size="30" maxlength="60" value="'.$w_unidade_externa.'"></td>');
      } else {
        if ($p_tipo_despacho==f($RS_Parametro,'despacho_arqsetorial')) {
          SelecaoUnidade('Ar<U>q</U>uivo setorial:','Q','Selecione o arquivo setorial.',$w_sq_unidade,null,'w_sq_unidade','MOD_PA_SET',null);
        } else {
          SelecaoUnidade('<U>U</U>nidade de destino:','U','Selecione a unidade de destino.',$w_sq_unidade,null,'w_sq_unidade','MOD_PA',null);
          ShowHTML('      <tr><td><td colspan="2"><font color="#BC3131"><b>Se unidade de destino igual � de origem, n�o h� emiss�o de guia de remessa e o recebimento � autom�tico.</b></font></td></tr>');
        }
      }
    }
    ShowHTML('      <tr><td colspan="3">&nbsp;</td></tr>');
    ShowHTML('      <tr><td colspan="3"  bgcolor="#f0f0f0" align=justify><font size="2"><b>DESPACHO: '.$w_nm_tipo_despacho.'</b></font></td></tr>');
    if ($p_tipo_despacho==f($RS_Parametro,'despacho_apensar') || $p_tipo_despacho==f($RS_Parametro,'despacho_anexar')) {
      SelecaoProtocolo('ao <U>p</U>rocesso:','U','Selecione o processo ao qual o protocolo ser� juntado.',$w_protocolo,null,'w_protocolo','JUNTADA',null);
      //ShowHTML('        <td><b>ao <u>p</u>rocesso:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="w_protocolo" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_protocolo.'" onKeyDown="FormataProtocolo(this,event);"></td>');
    }

    if ($w_desmembrar=='S') {
      ShowHTML('    <tr><td valign="top" colspan=3><b>Protocolos a serem d<u>e</u>smembrados:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Relacione o(s) protocolo(s) a ser(em) desmembrado(s).">'.$w_despacho.'</TEXTAREA></td>');
    } else {
      ShowHTML('    <tr><td valign="top" colspan=3><b>Detalhamento do d<u>e</u>spacho:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Detalhe a a��o a ser executada pelo destinat�rio.">'.nvl($w_despacho,(($p_tipo_despacho==f($RS_Parametro,'despacho_arqcentral')) ? 'Arquivar no Arquivo Central.' : '')).'</TEXTAREA></td>');
    }
    ShowHTML('    <tr><td colspan=3><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('   <tr><td align="center" colspan=3><hr>');
    if ($p_tipo_despacho==f($RS_Parametro,'despacho_arqcentral')) {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Gerar Guia de Transfer�ncia">');
    } else {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Gerar Guia de Tramita��o">');
    }
    ShowHTML('</FORM>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('<tr><td bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  <B>ORIENTA��O:</B><ul>');
    ShowHTML('  <li><b>INFORME O DESPACHO A SER UTILIZADO PARA ESTE ENVIO DE PROTOCOLO(S).</b>');
    ShowHTML('  <li>Informe quaisquer crit�rios de busca e clique sobre o bot�o <i>Aplicar filtro</i>.');
    ShowHTML('  <li>Para pesquisa por per�odo � obrigat�rio informar as datas de in�cio e t�rmino.');
    ShowHTML('  <li>Clicando sobre o botao <i>Aplicar filtro</i> sem informar nenhum crit�rio de busca, ser�o exibidas todos os protocolos que voc� tem acesso.');
    ShowHTML('  </ul></b></font></td>');
    ShowHTML('<tr valign="top">');
    selecaoTipoDespacho('Des<u>p</u>acho a ser usado para o envio:','P','Selecione o despacho a ser utilizado para os documentos relacionados na pr�xima tela.',$w_cliente,$p_tipo_despacho,null,'p_tipo_despacho','SELECAO',null);
    ShowHTML('<tr><td><br><font size="2"><b>FILTRAGEM<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
    ShowHTML('      <tr><td><b>Protocolo:<br><INPUT class="STI" type="text" name="p_prefixo" size="6" maxlength="5" value="'.$p_prefixo.'">.<INPUT class="STI" type="text" name="p_numero" style="text-align:right;" size="7" maxlength="6" value="'.$p_numero.'">/<INPUT class="STI" type="text" name="p_ano" size="4" maxlength="4" value="'.$p_ano.'"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade que det�m a posse do protocolo:','U','Selecione a unidade de posse.',$p_unid_posse,null,'p_unid_posse','MOD_PA',null);
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Per�o<u>d</u>o entre:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="p_ini" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
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
// Prepara transfer�ncia de protocolos para o arquivo central
// -------------------------------------------------------------------------
function TramitCentral() {
  extract($GLOBALS);
  global $w_Disabled;
  // Recupera as vari�veis utilizadas na filtragem
  $p_protocolo      = $_REQUEST['p_protocolo'];
  $p_chave          = explodeArray($_REQUEST['p_chave']);
  $p_chave_aux      = $_REQUEST['p_chave_aux'];
  $p_prefixo        = $_REQUEST['p_prefixo'];
  $p_numero         = $_REQUEST['p_numero'];
  $p_ano            = $_REQUEST['p_ano'];
  $p_unid_autua     = $_REQUEST['p_unid_autua'];
  $p_unid_posse     = $_REQUEST['p_unid_posse'];
  $p_nu_guia        = $_REQUEST['p_nu_guia'];
  $p_ano_guia       = $_REQUEST['p_ano_guia'];
  $p_ini            = $_REQUEST['p_ini'];
  $p_fim            = $_REQUEST['p_fim'];
  
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_chave            = $_REQUEST['w_chave'];
    $w_retorno_limite   = $_REQUEST['w_retorno_limite'];
    $w_interno          = $_REQUEST['w_interno'];
    $w_sq_unidade       = $_REQUEST['w_sq_unidade'];
    $w_pessoa_destino   = $_REQUEST['w_pessoa_destino'];
    $w_unidade_externa  = $_REQUEST['w_unidade_externa'];
    $w_aviso            = $_REQUEST['w_aviso'];
    $w_dias             = $_REQUEST['w_dias'];
    $w_protocolo        = $_REQUEST['w_protocolo'];
  }
  
  // Verifica se a unidade de lota��o do usu�rio est� cadastrada na rela��o de unidades do m�dulo
  $RS_Prot = db_getUorgList::getInstanceOf($dbms,$w_cliente,null,'MOD_PA_PROT',null,null,$w_ano);
  foreach($RS_Prot as $row) { $RS_Prot = $row; break; }
  
  if ($O=='L') {
    $RS = db_getProtocolo::getInstanceOf($dbms, f($RS_Menu,'sq_menu'), $w_usuario, $SG, $p_chave, $p_chave_aux, 
        $p_prefixo, $p_numero, $p_ano, $p_unid_autua, nvl($p_unid_posse,$_SESSION['LOTACAO']), $p_nu_guia, $p_ano_guia, $p_ini, $p_fim, 2, 
        $p_tipo_despacho);
    $RS = SortArray($RS,'prefixo','asc', 'ano','desc','numero_documento','asc');
    $w_existe = count($RS);
    
    if (count($w_chave) > 0) {
      $i = 0;
      foreach($w_chave as $k => $v) {
        foreach ($RS as $row) {
          if ($w_chave[$i]==f($row,'sq_siw_solicitacao')) {
            $w_marcado[f($row,'sq_siw_solicitacao')] = 'ok';
            break;
          }
        }
        $i += 1;
      }
      reset($RS);
    }
  } 
  Cabecalho();
  head();
  if ($O=='P') {
    ScriptOpen('JavaScript');
    FormataProtocolo();
    FormataData();
    SaltaCampo();
    CheckBranco();
    ValidateOpen('Validacao');
    Validate('p_prefixo','Prefixo','1','','5','5','','0123456789'); 
    Validate('p_numero','N�mero','1','','1','6','','0123456789'); 
    Validate('p_ano','Ano','1','','4','4','','0123456789'); 
    Validate('p_ini','In�cio','DATA','','10','10','','0123456789/');
    Validate('p_fim','T�rmino','DATA','','10','10','','0123456789/');
    ShowHTML('  if ((theForm.p_ini.value != \'\' && theForm.p_fim.value == \'\') || (theForm.p_ini.value == \'\' && theForm.p_fim.value != \'\')) {');
    ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
    ShowHTML('     theForm.p_ini.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    CompData('p_ini','In�cio','<=','p_fim','T�rmino');
    ShowHTML('  theForm.Botao.disabled=true;');
    ValidateClose();
    ScriptClose();
  } elseif ($w_existe) {
    ScriptOpen('JavaScript');
    FormataProtocolo();
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    ShowHTML('  var i; ');
    ShowHTML('  var w_erro=true; ');
    ShowHTML('  for (i=1; i < theForm["w_chave[]"].length; i++) {');
    ShowHTML('    if (theForm["w_chave[]"][i].checked) {');
    ShowHTML('       w_erro=false; ');
    ShowHTML('    }');
    ShowHTML('  }');
    ShowHTML('  if (w_erro) {');
    ShowHTML('    alert(\'Voc� deve informar pelo menos um protocolo!\'); ');
    ShowHTML('    return false;');
    ShowHTML('  }');
    Validate('w_caixa','Caixa para arquivamento','SELECT',1,1,18,'','0123456789');
    Validate('w_pasta','Pasta','',1,1,20,'1','1');
    Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    ShowHTML('  if (!confirm(\'Confirma o acondicionamento na caixa e pasta informadas?\')) return false;');
    ShowHTML('  theForm.Botao.disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='P') {
    BodyOpen('onLoad=\'document.Form.p_prefixo.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td colspan=3 bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">');
    ShowHTML('  ATEN��O:<ul>');
    ShowHTML('  <li>MARQUE APENAS OS PROTOCOLOS A SEREM ACONDICIONADOS NA MESMA CAIXA E PASTA.');
    ShowHTML('  <li>A qualquer momento voc� poder� alterar os dados informados, desde que ainda n�o tenha feito o envio para o Arquivo.');
    ShowHTML('  <li>Informe sua assinatura eletr�nica e clique sobre o bot�o <i>Acondicionar</i>.');
    ShowHTML('  </ul></b></font></td>');
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td colspan=2>');
    if (MontaFiltro('GET')>'') {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
    } 
    ShowHTML('    <td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan=2><b>&nbsp;</td>');
    ShowHTML('          <td rowspan=2 width="1%" nowrap><b>Protocolo</td>');
    ShowHTML('          <td rowspan=2 width="1%" nowrap><b>Tipo</td>');
    ShowHTML('          <td rowspan=2 width="1%" nowrap><b>Assunto</td>');
    ShowHTML('          <td colspan=4><b>Documento original</td>');
    ShowHTML('          <td colspan=2><b>Arq. setorial</td>');
    ShowHTML('          <td rowspan=2><b>Prazo Guarda</td>');
    ShowHTML('          <td colspan=2><b>Acondicionamento</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Esp�cie</td>');
    ShowHTML('          <td><b>N�</td>');
    ShowHTML('          <td><b>Data</td>');
    ShowHTML('          <td><b>Proced�ncia</td>');
    ShowHTML('          <td><b>Observa��o</td>');
    ShowHTML('          <td><b>Arquivamento</td>');
    ShowHTML('          <td><b>Caixa</td>');
    ShowHTML('          <td><b>Pasta</td>');
    ShowHTML('        </tr>');
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<input type="hidden" name="w_chave[]" value=""></td>'); 
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    if (count($RS)<=0) { 
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=13 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $w_atual = '';
      $i = 0;
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $w_unidade = f($row,'sq_unidade_posse');
        ShowHTML('      <tr bgcolor="'.$w_cor.'">');
        ShowHTML('        <td align="center" width="1%" nowrap>'); 
        if (nvl($w_marcado[f($row,'sq_siw_solicitacao')],'')!='') {
          ShowHTML('          <input type="CHECKBOX" CHECKED name="w_chave[]" value="'.f($row,'sq_siw_solicitacao').'"></td>'); 
        } else {
          ShowHTML('          <input type="CHECKBOX" name="w_chave[]" value="'.f($row,'sq_siw_solicitacao').'"></td>'); 
        }
        ShowHTML('        </td>');
        ShowHTML('        <td align="center" width="1%" nowrap><A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" target="visualdoc" title="Exibe as informa��es deste registro.">'.f($row,'protocolo').'&nbsp;</a>');
        ShowHTML('        <td width="10">&nbsp;'.f($row,'nm_tipo').'</td>');
        ShowHTML('        <td width="1%" nowrap>&nbsp;'.f($row,'cd_assunto').'</td>');
        ShowHTML('        <td width="1%" nowrap>&nbsp;'.f($row,'nm_especie').'</td>');
        ShowHTML('        <td width="1%" nowrap>&nbsp;'.f($row,'numero_original').'</td>');
        ShowHTML('        <td width="1%" nowrap>&nbsp;'.formataDataEdicao(f($row,'inicio'),5).'&nbsp;</td>');
        ShowHTML('        <td width="1%" nowrap>&nbsp;'.f($row,'nm_origem_doc').'</td>');
        if (strlen(Nvl(f($row,'observacao_setorial'),'-'))>50) $w_titulo=substr(Nvl(f($row,'observacao_setorial'),'-'),0,50).'...'; else $w_titulo=Nvl(f($row,'observacao_setorial'),'-');
        ShowHTML('        <td width="50%" title="'.htmlspecialchars(f($row,'observacao_setorial')).'">'.htmlspecialchars($w_titulo).'</td>');
        ShowHTML('        <td width="1%" nowrap align="center">&nbsp;'.formataDataEdicao(f($row,'data_setorial'),5).'</td>');
        ShowHTML('        <td width="1%" nowrap align="center">&nbsp;'.f($row,'data_limite_doc').'</td>');
        if (nvl(f($row,'nr_caixa'),'')!='') {
          ShowHTML('        <td align="center" width="1%" nowrap>&nbsp;<A onclick="window.open (\''.montaURL_JS($w_dir,'relatorio.php?par=ConteudoCaixa'.'&R='.$w_pagina.'IMPRIMIR'.'&O=L&w_chave='.f($row,'sq_caixa').'&w_espelho=s&w_formato=WORD&orientacao=PORTRAIT&&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\',\'Imprimir\',\'width=700,height=450, status=1,toolbar=yes,scrollbars=yes,resizable=yes\');" class="HL"  HREF="javascript:this.status.value;" title="Imprime a lista de protocolos arquivados na caixa.">'.f($row,'nr_caixa').'/'.f($row,'sg_unid_caixa').'</a>&nbsp;');
        } else {
          ShowHTML('        <td width="1%" nowrap align="center">&nbsp;</td>');
        }
        ShowHTML('        <td width="1%" nowrap align="center">&nbsp;'.nvl(f($row,'pasta'),'---').'</td>');
        ShowHTML('      </tr>');
        $i += 1;
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('      <tr><td colspan="3">&nbsp;</td></tr>');
    ShowHTML('      <tr><td colspan="3"  bgcolor="#f0f0f0" align=justify><font size="2"><b>ACONDICIONAMENTO</b></font></td></tr>');
    SelecaoCaixa('<u>C</u>aixa:','C',"Selecione a caixa para arquivamento.",$w_caixa,$w_cliente,$_SESSION['LOTACAO'],'w_caixa','PREPARA',null);
    ShowHTML('      <td><b><U>P</U>asta:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="STI" type="text" name="w_pasta" size="10" maxlength="20" value="'.$w_pasta.'"></td>');
    ShowHTML('    <tr><td colspan=3><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('   <tr><td align="center" colspan=3><hr>');
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Acondicionar">');
    ShowHTML('</FORM>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('<tr><td><br><font size="2"><b>FILTRAGEM<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
    ShowHTML('      <tr><td><b>Protocolo:<br><INPUT class="STI" type="text" name="p_prefixo" size="6" maxlength="5" value="'.$p_prefixo.'">.<INPUT class="STI" type="text" name="p_numero" style="text-align:right;" size="7" maxlength="6" value="'.$p_numero.'">/<INPUT class="STI" type="text" name="p_ano" size="4" maxlength="4" value="'.$p_ano.'"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade que det�m a posse do protocolo:','U','Selecione a unidade de posse.',$p_unid_posse,null,'p_unid_posse','MOD_PA',null);
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Per�o<u>d</u>o entre:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="p_ini" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
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
// Classifica protocolos
// -------------------------------------------------------------------------
function Classificacao() {
  extract($GLOBALS);
  global $w_Disabled;
  // Recupera as vari�veis utilizadas na filtragem
  $p_protocolo      = $_REQUEST['p_protocolo'];
  $p_chave          = explodeArray($_REQUEST['p_chave']);
  $p_chave_aux      = $_REQUEST['p_chave_aux'];
  $p_prefixo        = $_REQUEST['p_prefixo'];
  $p_numero         = $_REQUEST['p_numero'];
  $p_ano            = $_REQUEST['p_ano'];
  $p_unid_autua     = $_REQUEST['p_unid_autua'];
  $p_unid_posse     = $_REQUEST['p_unid_posse'];
  $p_nu_guia        = $_REQUEST['p_nu_guia'];
  $p_ano_guia       = $_REQUEST['p_ano_guia'];
  $p_ini            = $_REQUEST['p_ini'];
  $p_fim            = $_REQUEST['p_fim'];
  
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_chave            = $_REQUEST['w_chave'];
    $w_retorno_limite   = $_REQUEST['w_retorno_limite'];
    $w_interno          = $_REQUEST['w_interno'];
    $w_sq_unidade       = $_REQUEST['w_sq_unidade'];
    $w_pessoa_destino   = $_REQUEST['w_pessoa_destino'];
    $w_unidade_externa  = $_REQUEST['w_unidade_externa'];
    $w_aviso            = $_REQUEST['w_aviso'];
    $w_dias             = $_REQUEST['w_dias'];
    $w_protocolo        = $_REQUEST['w_protocolo'];
  }
  
  // Verifica se a unidade de lota��o do usu�rio est� cadastrada na rela��o de unidades do m�dulo
  $RS_Prot = db_getUorgList::getInstanceOf($dbms,$w_cliente,null,'MOD_PA_PROT',null,null,$w_ano);
  foreach($RS_Prot as $row) { $RS_Prot = $row; break; }
  
  if ($O=='L') {
    $RS = db_getProtocolo::getInstanceOf($dbms, f($RS_Menu,'sq_menu'), $w_usuario, $SG, $p_chave, $p_chave_aux, 
        $p_prefixo, $p_numero, $p_ano, $p_unid_autua, $p_unid_posse, $p_nu_guia, $p_ano_guia, $p_ini, $p_fim, 2, 
        $p_tipo_despacho);
    if (Nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'inicio','asc','ano','asc', 'prefixo','asc','protocolo','asc');
     } else {
      $RS = SortArray($RS,'inicio','asc','ano','asc', 'prefixo','asc','protocolo','asc');
    }
    $w_existe = count($RS);
    
    if (count($w_chave) > 0) {
      $i = 0;
      foreach($w_chave as $k => $v) {
        foreach ($RS as $row) {
          if ($w_chave[$i]==f($row,'sq_siw_solicitacao')) {
            $w_marcado[f($row,'sq_siw_solicitacao')] = 'ok';
            break;
          }
        }
        $i += 1;
      }
      reset($RS);
    }
  } 
  Cabecalho();
  head();
  if ($O=='P') {
    ScriptOpen('JavaScript');
    FormataProtocolo();
    FormataData();
    SaltaCampo();
    CheckBranco();
    ValidateOpen('Validacao');
    Validate('p_prefixo','Prefixo','1','','5','5','','0123456789'); 
    Validate('p_numero','N�mero','1','','1','6','','0123456789'); 
    Validate('p_ano','Ano','1','','4','4','','0123456789'); 
    Validate('p_ini','In�cio','DATA','','10','10','','0123456789/');
    Validate('p_fim','T�rmino','DATA','','10','10','','0123456789/');
    ShowHTML('  if ((theForm.p_ini.value != \'\' && theForm.p_fim.value == \'\') || (theForm.p_ini.value == \'\' && theForm.p_fim.value != \'\')) {');
    ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
    ShowHTML('     theForm.p_ini.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    CompData('p_ini','In�cio','<=','p_fim','T�rmino');
    ShowHTML('  theForm.Botao.disabled=true;');
    ValidateClose();
    ScriptClose();
  } elseif ($w_existe) {
    ScriptOpen('JavaScript');
    FormataProtocolo();
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    ShowHTML('  var i; ');
    ShowHTML('  var w_erro=true; ');
    ShowHTML('  for (i=1; i < theForm["w_chave[]"].length; i++) {');
    ShowHTML('    if (theForm["w_chave[]"][i].checked) {');
    ShowHTML('       w_erro=false; ');
    ShowHTML('    }');
    ShowHTML('  }');
    ShowHTML('  if (w_erro) {');
    ShowHTML('    alert(\'Voc� deve informar pelo menos um protocolo!\'); ');
    ShowHTML('    return false;');
    ShowHTML('  }');
    Validate('w_assunto','Classifica��o','HIDDEN',1,1,18,'','0123456789');
    Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    ShowHTML('  theForm.Botao.disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='P') {
    BodyOpen('onLoad=\'document.Form.p_prefixo.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td colspan=3 bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">');
    ShowHTML('  ATEN��O:<ul>');
    ShowHTML('  <li>INFORME O ASSUNTO SOMENTE DOS PROTOCOLOS DESEJADOS.');
    ShowHTML('  <li>A qualquer momento voc� poder� alterar o assunto informado para um protocolo, informando seu n�mero na tela de filtragem.');
    ShowHTML('  <li>Informe sua assinatura eletr�nica e clique sobre o bot�o <i>Classificar</i>.');
    ShowHTML('  </ul></b></font></td>');
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td colspan=2>');
    if (MontaFiltro('GET')>'') {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
    } 
    ShowHTML('    <td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan=2 width="1%" nowrap><b>&nbsp;</td>');
    ShowHTML('          <td rowspan=2><b>'.linkOrdena('Resumo','descricao').'</td>');
    ShowHTML('          <td rowspan=2 width="1%" nowrap><b>'.linkOrdena('Tipo','nm_tipo').'</td>');
    ShowHTML('          <td colspan=4><b>Documento original</td>');
    ShowHTML('          <td rowspan=2 width="1%" nowrap><b>'.linkOrdena('Protocolo','protocolo').'</td>');
    ShowHTML('          <td rowspan=2 width="1%" nowrap><b>'.linkOrdena('Assunto','cd_assunto').'</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.linkOrdena('Esp�cie','nm_especie').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('N�','numero_original').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Data','inicio').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Proced�ncia','nm_origem_doc').'</td>');
    ShowHTML('        </tr>');
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<input type="hidden" name="w_chave[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    if (count($RS)<=0) { 
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=12 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $w_atual = '';
      $i = 0;
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $w_unidade = f($row,'sq_unidade_posse');
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center" width="1%" nowrap>'); 
        if (nvl($w_marcado[f($row,'sq_siw_solicitacao')],'')!='') {
          ShowHTML('          <input type="CHECKBOX" CHECKED name="w_chave[]" value="'.f($row,'sq_siw_solicitacao').'">'); 
        } else {
          ShowHTML('          <input type="CHECKBOX" name="w_chave[]" value="'.f($row,'sq_siw_solicitacao').'">'); 
        }
        ShowHTML('        </td>');
        if (strlen(Nvl(f($row,'descricao'),'-'))>500) $w_titulo=substr(Nvl(f($row,'descricao'),'-'),0,500).'...'; else $w_titulo=Nvl(f($row,'descricao'),'-');
        if (f($row,'sg_tramite')=='CA') ShowHTML('        <td title="'.((strlen(Nvl(f($row,'descricao'),'-'))>500) ? htmlspecialchars(f($row,'descricao')) : '').'"><strike>'.htmlspecialchars($w_titulo).'</strike></td>');
        else                            ShowHTML('        <td title="'.((strlen(Nvl(f($row,'descricao'),'-'))>500) ? htmlspecialchars(f($row,'descricao')) : '').'">'.htmlspecialchars($w_titulo).'</td>');
        ShowHTML('        <td>'.f($row,'nm_tipo').'</td>');
        ShowHTML('        <td>'.f($row,'nm_especie').'</td>');
        ShowHTML('        <td>'.f($row,'numero_original').'</td>');
        ShowHTML('        <td>'.formataDataEdicao(f($row,'inicio'),5).'</td>');
        ShowHTML('        <td>'.f($row,'nm_origem_doc').'</td>');
        ShowHTML('        <td align="center" width="1%" nowrap><A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" target="visualdoc" title="Exibe as informa��es deste registro.">'.f($row,'protocolo').'&nbsp;</a>');
        ShowHTML('        <td>'.f($row,'cd_assunto').'</td>');
        ShowHTML('      </tr>');
        $i += 1;
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('      <tr><td colspan="3">&nbsp;</td></tr>');
    ShowHTML('      <tr><td colspan="3"  bgcolor="#f0f0f0" align=justify><font size="2"><b>ACONDICIONAMENTO</b></font></td></tr>');
    SelecaoAssuntoRadio('C<u>l</u>assifica��o:','L','Clique na lupa para selecionar a classifica��o do documento.',$w_assunto,null,'w_assunto','FOLHA',null);
    ShowHTML('    <tr><td colspan=3><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('   <tr><td align="center" colspan=3><hr>');
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Classificar">');
    ShowHTML('</FORM>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('<tr><td><br><font size="2"><b>FILTRAGEM<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
    ShowHTML('      <tr><td><b>Protocolo:<br><INPUT class="STI" type="text" name="p_prefixo" size="6" maxlength="5" value="'.$p_prefixo.'">.<INPUT class="STI" type="text" name="p_numero" style="text-align:right;" size="7" maxlength="6" value="'.$p_numero.'">/<INPUT class="STI" type="text" name="p_ano" size="4" maxlength="4" value="'.$p_ano.'"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade que det�m a posse do protocolo:','U','Selecione a unidade de posse.',$p_unid_posse,null,'p_unid_posse','MOD_PA',null);
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Per�o<u>d</u>o entre:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="p_ini" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
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
// Acusa o recebimento de guias de tramita��o
// -------------------------------------------------------------------------
function Recebimento() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_unid_autua   = $_REQUEST['w_unid_autua'];
  $w_nu_guia      = $_REQUEST['w_nu_guia'];
  $w_ano_guia     = $_REQUEST['w_ano_guia'];

  if ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getProtocolo::getInstanceOf($dbms, $P2, $w_usuario, $SG, null, null, 
        null, null, null, $w_unid_autua, null, $w_nu_guia, $w_ano_guia, null, null, null, null);
    $RS = SortArray($RS,'sg_unid_dest','asc', 'sg_unid_origem','asc', 'ano_guia','desc','nu_guia','asc','protocolo','asc');
  } elseif ($O=='R') {
    // Recupera os protocolos da guia
    $RS_Dados = db_getProtocolo::getInstanceOf($dbms, $P2, $w_usuario, $SG, null, null, 
        null, null, null, $w_unid_autua, null, $w_nu_guia, $w_ano_guia, null, null, 2, null);
    
    foreach($RS_Dados as $row) { $RS_Dados = $row; break; }
    $w_interno = f($RS_Dados,'interno');
  }

  Cabecalho();
  head();
  if ($O=='R' || $O=='T' || $O=='P') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if ($O=='R' || $O=='T') {
      if ($w_interno=='N') Validate('w_observacao','Observa��es sobre o envio externo','1','1','5','2000','1','1');
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      Validate('w_nu_guia','N�mero da guia','1','','1','10','0','0123456789');
      Validate('w_ano_guia','Ano da guia','1','','4','4','0','0123456789');
      ShowHTML('  if ((theForm.w_nu_guia.value!="" && theForm.w_ano_guia.value=="") || (theForm.w_nu_guia.value=="" && theForm.w_ano_guia.value!="")) {');
      ShowHTML('     alert ("Se desejar informar a quia, informe o n�mero e o ano!");');
      ShowHTML('     theForm.w_nu_guia.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      ShowHTML('  theForm.Botao.disabled=true;');
    }
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='R' || $O=='T') {
    if ($w_interno=='N') BodyOpen('onLoad=\'document.Form.w_observacao.focus()\';');
    else BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } elseif ($O=='P') {
    BodyOpen('onLoad=\'document.Form.w_nu_guia.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orienta��o:<ul>');
    ShowHTML('  <li>Selecione a guia desejada para recebimento, clicando sobre a opera��o <i>Receber</i>.');
    ShowHTML('  </ul></b></font></td>');
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td>');
    if (MontaFiltro('GET')>'') {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
    } 
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Destino</td>');
    ShowHTML('          <td><b>�ltima Proced�ncia</td>');
    ShowHTML('          <td><b>Guia</td>');
    ShowHTML('          <td><b>Despacho</td>');
    ShowHTML('          <td><b>Protocolo</td>');
    ShowHTML('          <td><b>Envio</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) { 
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan="7" align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      $w_atual = '';
      foreach ($RS1 as $row) {
        if ($w_atual=='' || $w_atual!=f($row,'guia_tramite')) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('        <td title="'.f($row,'nm_unid_dest').'">'.f($row,'sg_unid_dest').'</td>');
          ShowHTML('        <td title="'.f($row,'nm_unid_origem').'">'.f($row,'sg_unid_origem').'</td>');
          ShowHTML('        <td>'.f($row,'guia_tramite').'</td>');
          ShowHTML('        <td>'.f($row,'nm_despacho').'</td>');
          ShowHTML('        <td align="center"><A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" target="visualdoc" title="Exibe as informa��es deste registro.">'.f($row,'protocolo').'&nbsp;</a>');
          ShowHTML('        <td align="center">'.formataDataEdicao(f($row,'phpdt_envio'),3).'</td>');
          ShowHTML('        <td align="top" nowrap>');
          if (nvl(f($row,'despacho_arqcentral'),'')=='') {
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=R&w_unid_autua='.f($row,'unidade_autuacao').'&w_nu_guia='.f($row,'nu_guia').'&w_ano_guia='.f($row,'ano_guia').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Receber</A>&nbsp');
          } else {
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_unid_autua='.f($row,'unidade_autuacao').'&w_nu_guia='.f($row,'nu_guia').'&w_ano_guia='.f($row,'ano_guia').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Receber</A>&nbsp');
          }
          ShowHTML('        </td>');
          ShowHTML('      </tr>');
          $w_atual = f($row,'guia_tramite');
        } else {
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('        <td align="center"><A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" target="visualdoc" title="Exibe as informa��es deste registro.">'.f($row,'protocolo').'&nbsp;</a>');
          ShowHTML('        <td align="center">'.formataDataEdicao(f($row,'phpdt_envio'),3).'</td>');
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('      </tr>');
        }
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET'),ceil(count($RS)/$P4),$P3,$P4,count($RS));
    ShowHTML('</tr>');
  } elseif ($O=='R') {
    ShowHTML('<tr><td align="center" colspan=3>');
    // Chama a rotina de visualiza��o dos protocolos da guia
    ShowHTML(VisualGR($w_unid_autua, $w_nu_guia, $w_ano_guia, f($RS_Menu,'sq_menu'), 'TELA'));

    ShowHTML('<HR>');
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_unid_autua" value="'.$w_unid_autua.'">');
    ShowHTML('<INPUT type="hidden" name="w_nu_guia" value="'.$w_nu_guia.'">');
    ShowHTML('<INPUT type="hidden" name="w_ano_guia" value="'.$w_ano_guia.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr><td colspan=3 bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">');
    ShowHTML('  ATEN��O:<ul>');
    ShowHTML('  <li>Verifique cada um dos protocolos antes de assinar o recebimento, pois n�o ser� poss�vel reverter esta a��o.');
    ShowHTML('  <li>O recebimento da guia implica no recebimento de todos os seus protocolos, n�o sendo poss�vel o recebimento parcial.');
    ShowHTML('  </ul></b></font></td>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('  <table width="97%" border="0">');
    if ($w_interno=='N') ShowHTML('      <tr><td colspan="4" title="Informe os dados do envio do protocolo."><b><u>O</u>bserva��o sobre o envio externo:</b><br><textarea '.$w_Disabled.' accesskey="O" name="w_observacao" class="STI" ROWS=5 cols=75>'.$w_observacao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('    <tr><td align="center" colspan=4><hr>');
    ShowHTML('      <input class="STB" type="submit" name="Botao" value="Receber">');
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    ShowHTML('      </td>');
    ShowHTML('    </tr>');
    ShowHTML('  </table>');
    ShowHTML('  </TD>');
    ShowHTML('</tr>');
  } elseif ($O=='T') {
    ShowHTML('<tr><td align="center" colspan=3>');
    // Chama a rotina de visualiza��o dos protocolos da guia
    ShowHTML(VisualGT($w_unid_autua, $w_nu_guia, $w_ano_guia, f($RS_Menu,'sq_menu'), 'TELA'));

    ShowHTML('<HR>');
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_unid_autua" value="'.$w_unid_autua.'">');
    ShowHTML('<INPUT type="hidden" name="w_nu_guia" value="'.$w_nu_guia.'">');
    ShowHTML('<INPUT type="hidden" name="w_ano_guia" value="'.$w_ano_guia.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr><td colspan=3 bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">');
    ShowHTML('  ATEN��O:<ul>');
    ShowHTML('  <li>Verifique cada uma das caixas, pastas e protocolos antes de assinar o recebimento, pois n�o ser� poss�vel reverter esta a��o.');
    ShowHTML('  <li>O recebimento da guia implica no recebimento de todas as suas caixas, pastas e protocolos, n�o sendo poss�vel o recebimento parcial.');
    ShowHTML('  </ul></b></font></td>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('  <table width="97%" border="0">');
    ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('    <tr><td align="center" colspan=4><hr>');
    ShowHTML('      <input class="STB" type="submit" name="Botao" value="Receber">');
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    ShowHTML('      </td>');
    ShowHTML('    </tr>');
    ShowHTML('  </table>');
    ShowHTML('  </TD>');
    ShowHTML('</tr>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('<tr><td bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  <B>ORIENTA��O:</B><ul>');
    ShowHTML('  <li>Informe quaisquer crit�rios de busca e clique sobre o bot�o <i>Aplicar filtro</i>.');
    ShowHTML('  <li>Clicando sobre o botao <i>Aplicar filtro</i> sem informar nenhum crit�rio de busca, ser�o exibidas apenas as guias que voc� tem acesso.');
    ShowHTML('  </ul></b></font></td>');
    ShowHTML('<tr><td><br><font size="2"><b>FILTRAGEM<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
    ShowHTML('      <tr><td><b>N�mero e ano da guia de remessa:<br><INPUT class="STI" type="text" name="w_nu_guia" size="10" maxlength="10" style="text-align:right;" value="'.$w_nu_guia.'">/<INPUT class="STI" type="text" name="w_ano_guia" size="4" maxlength="4" value="'.$w_ano_guia.'"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade emissora da guia:','U','Selecione a unidade emissora da guia.',$w_unid_autua,null,'w_unid_autua','MOD_PA_PROT',null);
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
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
// Rotina de busca de protocolos
// -------------------------------------------------------------------------
function BuscaProtocolo() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_ano        = $_REQUEST['w_ano'];
  $w_cliente    = $_REQUEST['w_cliente'];
  $chaveaux     = $_REQUEST['chaveaux'];
  $restricao    = $_REQUEST['restricao'];
  $campo        = $_REQUEST['campo'];

  $l_exibe         = upper($_REQUEST['exibe']);
  $l_tipo          = upper($_REQUEST['l_tipo']);
  $l_chave_pai     = upper($_REQUEST['l_chave_pai']);
  $l_atividade     = upper($_REQUEST['l_atividade']);
  $l_graf          = upper($_REQUEST['l_graf']);
  $l_ativo         = upper($_REQUEST['l_ativo']);
  $l_solicitante   = upper($_REQUEST['l_solicitante']);
  $l_prioridade    = upper($_REQUEST['l_prioridade']);
  $l_unidade       = upper($_REQUEST['l_unidade']);
  $l_proponente    = upper($_REQUEST['l_proponente']);
  $l_ordena        = lower($_REQUEST['l_ordena']);
  $l_ini_i         = upper($_REQUEST['l_ini_i']);
  $l_ini_f         = upper($_REQUEST['l_ini_f']);
  $l_fim_i         = upper($_REQUEST['l_fim_i']);
  $l_fim_f         = upper($_REQUEST['l_fim_f']);
  $l_atraso        = upper($_REQUEST['l_atraso']);
  $l_chave         = upper($_REQUEST['l_chave']);
  $l_assunto       = upper($_REQUEST['l_assunto']);
  $l_pais          = upper($_REQUEST['l_pais']);
  $l_regiao        = upper($_REQUEST['l_regiao']);
  $l_uf            = upper($_REQUEST['l_uf']);
  $l_cidade        = upper($_REQUEST['l_cidade']);
  $l_usu_resp      = upper($_REQUEST['l_usu_resp']);
  $l_uorg_resp     = upper($_REQUEST['l_uorg_resp']);
  $l_processo       = upper($_REQUEST['l_processo']);
  $l_prazo         = upper($_REQUEST['l_prazo']);
  $l_fase          = explodeArray($_REQUEST['l_fase']);
  $l_sqcc          = upper($_REQUEST['l_sqcc']);
  $l_agrega        = upper($_REQUEST['l_agrega']);
  $l_tamanho       = upper($_REQUEST['l_tamanho']);
  $l_sq_menu_relac = upper($_REQUEST['l_sq_menu_relac']);
  $l_chave_pai     = upper($_REQUEST['l_chave_pai']);
  $l_empenho       = lower($_REQUEST['l_empenho']);
  
  // Se juntada, busca somente processos
  if ($restricao=='JUNTADA') $l_uf = 'S';
  
  $RS = db_getSolicList::getInstanceOf($dbms,f($RS_Menu,'sq_menu'),$w_usuario,f($RS_Menu,'sigla'),5,
      $l_ini_i,$l_ini_f,$l_fim_i,$l_fim_f,$l_atraso,$l_solicitante,
      $l_unidade,$l_prioridade,$l_ativo,$l_proponente,
      $l_chave, $l_assunto, $l_pais, $l_regiao, $l_uf, $l_cidade, $l_usu_resp,
      $l_uorg_resp, $l_palavra, $l_prazo, $l_fase, $l_sqcc, $l_chave_pai, $l_atividade, null, null, 
      $l_empenho, $l_processo);
  $RS = SortArray($RS,'protocolo','asc');
      
  Cabecalho();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('<TITLE>Sele��o de protocolo</TITLE>');
  head();
  Estrutura_CSS($w_cliente);
  ScriptOpen('JavaScript');
  ShowHTML('  function volta(l_protocolo, l_chave) {');
  ShowHTML("     opener.document.Form.".$campo."_nm.value=l_protocolo.replace('\'','\"');");
  ShowHTML('     opener.document.Form.'.$campo.'.value=l_chave;');
  ShowHTML('     opener.document.Form.'.$campo.'_nm.focus();');
  ShowHTML('     window.close();');
  ShowHTML('     opener.focus();');
  ShowHTML('   }');
  if (count($RS)>50 || $l_exibe>'') {
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    Validate('l_proponente','Origem externa','','','2','90','1','');
    Validate('l_assunto','Detalhamento','','','2','90','1','1');
    Validate('l_processo','Palavras-chave','','','2','90','1','1');
    Validate('l_ini_i','Recebimento inicial','DATA','','10','10','','0123456789/');
    Validate('l_ini_f','Recebimento final','DATA','','10','10','','0123456789/');
    ShowHTML('  if ((theForm.l_ini_i.value != \'\' && theForm.l_ini_f.value == \'\') || (theForm.l_ini_i.value == \'\' && theForm.l_ini_f.value != \'\')) {');
    ShowHTML('     alert (\'Informe ambas as datas de recebimento ou nenhuma delas!\');');
    ShowHTML('     theForm.l_ini_i.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    ShowHTML('  var i; ');
    ShowHTML('  var w_erro=true; ');
    ShowHTML('  for (i=0; i < theForm["l_fase[]"].length; i++) {');
    ShowHTML('    if (theForm["l_fase[]"][i].checked) w_erro=false;');
    ShowHTML('  }');
    ShowHTML('  if (w_erro) {');
    ShowHTML('    alert(\'Voc� deve informar pelo menos uma fase!\'); ');
    ShowHTML('    return false;');
    ShowHTML('  }');      
    CompData('l_ini_i','Recebimento inicial','<=','l_ini_f','Recebimento final');
    Validate('l_fim_i','Conclus�o inicial','DATA','','10','10','','0123456789/');
    Validate('l_fim_f','Conclus�o final','DATA','','10','10','','0123456789/');
    ShowHTML('  if ((theForm.l_fim_i.value != \'\' && theForm.l_fim_f.value == \'\') || (theForm.l_fim_i.value == \'\' && theForm.l_fim_f.value != \'\')) {');
    ShowHTML('     alert (\'Informe ambas as datas de conclus�o ou nenhuma delas!\');');
    ShowHTML('     theForm.l_fim_i.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    CompData('l_fim_i','Conclus�o inicial','<=','l_fim_f','Conclus�o final');
    ShowHTML('  theForm.exibe.value=true;');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
  } 
  ScriptClose();
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=this.focus();');
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="100%" border="0">');
  if (count($RS)>50 || $l_exibe>'') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this))',null,$P1,$P2,$P3,$P4,$TP,$SG,null,null);
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="chaveaux" value="'.$chaveaux.'">');
    ShowHTML('<INPUT type="hidden" name="restricao" value="'.$restricao.'">');
    ShowHTML('<INPUT type="hidden" name="campo" value="'.$campo.'">');
    ShowHTML('<INPUT type="hidden" name="exibe" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td colspan=2><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Protocolo:<br><INPUT class="STI" type="text" name="l_pais" size="6" maxlength="5" value="'.$l_pais.'">.<INPUT class="STI" type="text" name="l_regiao" style="text-align:right;" size="7" maxlength="6" value="'.$l_regiao.'">/<INPUT class="STI" type="text" name="l_cidade" size="4" maxlength="4" value="'.$l_cidade.'"></td>');
    ShowHTML('          <td><b>Buscar por?</b><br>');
    if ($l_uf=='S') {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="l_uf" value="S" checked> Processo <input '.$w_Disabled.' class="STR" class="STR" type="radio" name="l_uf" value="N"> Documento <input '.$w_Disabled.' class="STR" class="STR" type="radio" name="l_uf" value=""> Ambos');
    } elseif ($l_uf=='N') {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="l_uf" value="S"> Processo <input '.$w_Disabled.' class="STR" class="STR" type="radio" name="l_uf" value="N" checked> Documento <input '.$w_Disabled.' class="STR" class="STR" type="radio" name="l_uf" value=""> Ambos');
    } else {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="l_uf" value="S"> Processo <input '.$w_Disabled.' class="STR" class="STR" type="radio" name="l_uf" value="N"> Documento <input '.$w_Disabled.' class="STR" class="STR" type="radio" name="l_uf" value="" checked> Ambos');
    }
    
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade de posse:','U','Selecione a Unidade de posse do protocolo na rela��o.',$l_uorg_resp,null,'l_uorg_resp',null,null);
    selecaoTipoDespacho('�ltimo des<u>p</u>acho:','P','Selecione o despacho desejado.',$w_cliente,$l_prioridade,null,'l_prioridade','SELECAO',null);
    
    ShowHTML('      <tr valign="top"><td colspan="2"><b>Documento original:</b><table width="100%" cellpadding=0 cellspacing=3 style="border: 1px solid rgb(0,0,0);"><tr><td width="50%"><td></tr><tr valign="top">');
    ShowHTML('          <td><b>N�mero:<br><INPUT class="STI" type="text" name="l_empenho" size="10" maxlength="30" value="'.$l_empenho.'">');
    selecaoEspecieDocumento('<u>E</u>sp�cie documental:','E','Selecione a esp�cie do documento.',$l_solicitante,null,'l_solicitante',null,null);
    ShowHTML('      <tr>');
    ShowHTML('          <td><b><u>C</u>riado/Recebido entre:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="l_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$l_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','l_ini_i').' e <input '.$w_Disabled.' accesskey="C" type="text" name="l_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$l_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','l_ini_f').'</td>');
    ShowHTML('          <td><b>Limi<u>t</u>e para tramita��o entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="l_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$l_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','l_fim_i').' e <input '.$w_Disabled.' accesskey="T" type="text" name="l_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$l_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','l_fim_f').'</td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>O</U>rigem interna:','O',null,$l_unidade,null,'l_unidade',null,null);
    ShowHTML('          <td><b>Orig<U>e</U>m externa:<br><INPUT ACCESSKEY="E" '.$w_Disabled.' class="STI" type="text" name="l_proponente" size="25" maxlength="90" value="'.$l_proponente.'"></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><U>A</U>ssunto:<br><INPUT ACCESSKEY="A" '.$w_Disabled.' class="STI" type="text" name="l_assunto" size="40" maxlength="30" value="'.$l_assunto.'"></td>');
    ShowHTML('          <td><b><U>I</U>nteressado:<br><INPUT ACCESSKEY="I" '.$w_Disabled.' class="STI" type="text" name="l_processo" size="30" maxlength="30" value="'.$l_processo.'"></td>');
    ShowHTML('        </tr></table>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Apenas protocolos com data limite excedida?</b><br>');
    if ($l_atraso=='S') {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="l_atraso" value="S" checked> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="l_atraso" value="N"> N�o');
    } else {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="l_atraso" value="S"> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="l_atraso" value="N" checked> N�o');
    } 
    SelecaoFaseCheck('Recuperar fases:','S',null,$l_fase,$w_menu,'l_fase[]',null,null);
    ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="stb" type="button" name="Botao" value="Cancelar" onClick="window.close(); opener.focus();">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
    if ($l_exibe>'') {
      ShowHTML('<tr><td align="right"><b>Registros: '.count($RS));
      ShowHTML('<tr><td>');
      ShowHTML('    <TABLE WIDTH="100%" border=0>');
      if (count($RS)<=0) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
      } else {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td>');
        ShowHTML('        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('            <td rowspan=2 width="1%" nowrap><b>Protocolo</td>');
        ShowHTML('            <td rowspan=2 width="1%" nowrap><b>Tipo</td>');
        ShowHTML('            <td rowspan=2 width="1%" nowrap><b>Posse</td>');
        ShowHTML('            <td colspan=4><b>Documento original</td>');
        ShowHTML('            <td rowspan=2><b>Opera��es</td>');
        ShowHTML('          </tr>');
        ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('            <td><b>Esp�cie</td>');
        ShowHTML('            <td><b>N�</td>');
        ShowHTML('            <td><b>Data</td>');
        ShowHTML('            <td><b>Proced�ncia</td>');
        ShowHTML('          </tr>');
        foreach($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('        <td nowrap>');
          if (nvl(f($row,'conclusao'),'nulo')=='nulo') {
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
          if ($w_tipo!='WORD') ShowHTML('        <A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informa��es deste registro.">'.f($row,'protocolo').'&nbsp;</a>');
          else                 ShowHTML('        '.f($row,'protocolo').'');
          ShowHTML('        <td width="1%" nowrap>'.f($row,'nm_tipo_protocolo').'</td>');
          ShowHTML('        <td width="1%" nowrap>'.ExibeUnidade('../',$w_cliente,f($row,'sg_unidade_posse'),f($row,'unidade_int_posse'),$TP).'</td>');
          ShowHTML('        <td>'.f($row,'nm_especie').'</td>');
          ShowHTML('        <td>'.f($row,'numero_original').'</td>');
          ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'inicio')).'</td>');
          ShowHTML('        <td>'.f($row,'nm_origem').'</td>');
          ShowHTML('        <td><a class="ss" HREF="javascript:this.status.value;" onClick="javascript:volta(\''.f($row,'protocolo').'\', \''.f($row,'protocolo').'\');">Selecionar</a>');
        } 
        ShowHTML('        </table></tr>');
        ShowHTML('      </center>');
        ShowHTML('    </table>');
        ShowHTML('  </td>');
        ShowHTML('</tr>');
      } 
    } 
  } else {
    ShowHTML('<tr><td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td colspan=6>');
    ShowHTML('    <TABLE WIDTH="100%" border=0>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td>');
      ShowHTML('        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('            <td rowspan=2 width="1%" nowrap><b>Protocolo</td>');
      ShowHTML('            <td rowspan=2 width="1%" nowrap><b>Tipo</td>');
      ShowHTML('            <td rowspan=2 width="1%" nowrap><b>Posse</td>');
      ShowHTML('            <td colspan=4><b>Documento original</td>');
      ShowHTML('            <td rowspan=2><b>Opera��es</td>');
      ShowHTML('          </tr>');
      ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('            <td><b>Esp�cie</td>');
      ShowHTML('            <td><b>N�</td>');
      ShowHTML('            <td><b>Data</td>');
      ShowHTML('            <td><b>Proced�ncia</td>');
      ShowHTML('          </tr>');
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td nowrap>');
        if (nvl(f($row,'conclusao'),'nulo')=='nulo') {
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
        if ($w_tipo!='WORD') ShowHTML('        <A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informa��es deste registro.">'.f($row,'protocolo').'&nbsp;</a>');
        else                 ShowHTML('        '.f($row,'protocolo').'');
        ShowHTML('        <td width="1%" nowrap>'.f($row,'nm_tipo_protocolo').'</td>');
        ShowHTML('        <td width="1%" nowrap>'.ExibeUnidade('../',$w_cliente,f($row,'sg_unidade_posse'),f($row,'unidade_int_posse'),$TP).'</td>');
        ShowHTML('        <td>'.f($row,'nm_especie').'</td>');
        ShowHTML('        <td>'.f($row,'numero_original').'</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'inicio')).'</td>');
        ShowHTML('        <td>'.f($row,'nm_origem').'</td>');
        ShowHTML('        <td><a class="ss" HREF="javascript:this.status.value;" onClick="javascript:volta(\''.f($row,'protocolo').'\', \''.f($row,'protocolo').'\');">Selecionar</a>');
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
  BodyOpen(null);
  if ($SG=='PADGERAL') {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if ($O=='E') {
        $RS = db_getSolicLog::getInstanceOf($dbms,$_REQUEST['w_chave'],null,'LISTA');
        // Mais de um registro de log significa que deve ser cancelada, e n�o exclu�da.
        // Nessa situa��o, n�o � necess�rio excluir os arquivos.
        if (count($RS)<=1) {
          $RS = db_getSolicAnexo::getInstanceOf($dbms,$_REQUEST['w_chave'],null,$w_cliente);
          foreach($RS as $row) { {
            if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));} 
          } 
        }
      } 
      dml_putDocumentoGeral::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$w_copia,$_REQUEST['w_menu'],
          nvl($_REQUEST['w_sq_unidade'],$_SESSION['LOTACAO']), nvl($_REQUEST['w_un_autuacao'],$_SESSION['LOTACAO']),
          nvl($_REQUEST['w_pessoa_origem'],$_SESSION['SQ_PESSOA']),$_SESSION['SQ_PESSOA'],$_REQUEST['w_solic_pai'],
          $_REQUEST['w_codigo_interno'],$_REQUEST['w_processo'],$_REQUEST['w_circular'],$_REQUEST['w_especie_documento'],
          $_REQUEST['w_doc_original'],$_REQUEST['w_data_documento'],$_REQUEST['w_volumes'],$_REQUEST['w_dt_autuacao'],
          $_REQUEST['w_copias'],$_REQUEST['w_natureza_documento'],$_REQUEST['w_fim'],$_REQUEST['w_data_recebimento'],
          $_REQUEST['w_interno'],$_REQUEST['w_pessoa_origem'],$_REQUEST['w_pessoa_interes'],$_REQUEST['w_cidade'],
          $_REQUEST['w_assunto'],$_REQUEST['w_descricao'],&$w_chave_nova, &$w_codigo);

      ScriptOpen('JavaScript');
      if ($O=='I' || $_REQUEST['w_codigo']!=$_REQUEST['w_codigo_atual']) {
        // Exibe mensagem de grava��o com sucesso
        if ($_REQUEST['w_codigo_atual']=='') {
          ShowHTML('  alert(\'Documento cadastrado com sucesso!\');');
        } else {
          $TP = removeTP($TP);
        }

	      // Recupera os dados para montagem correta do menu
        $RS1 = db_getMenuData::getInstanceOf($dbms,$w_menu);
        ShowHTML('  parent.menu.location=\''.montaURL_JS('','menu.php?par=ExibeDocs&O=A&w_chave='.$w_chave_nova.'&w_documento='.$w_codigo.'&R='.$R.'&SG='.f($RS1,'sigla').'&TP='.$TP.MontaFiltro('GET')).'\';');
      } elseif ($O=='E') {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      } else {
        // Aqui deve ser usada a vari�vel de sess�o para evitar erro na recupera��o do link
        $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,$SG);
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS1,'link').'&O='.$O.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      } 
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($SG=='PADOCANEXO') {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      // Se foi feito o upload de um arquivo  
      if (UPLOAD_ERR_OK==0) {
        $w_maximo = $_REQUEST['w_upload_maximo'];
        foreach ($_FILES as $Chv => $Field) {
          if (!($Field['error']==UPLOAD_ERR_OK || $Field['error']==UPLOAD_ERR_NO_FILE)) {
            // Verifica se o tamanho das fotos est� compat�vel com  o limite de 100KB. 
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Aten��o: o tamanho m�ximo do arquivo n�o pode exceder '.($w_maximo/1024).' KBytes!\');');
            ScriptClose();
            retornaFormulario('w_caminho');
            exit();
          }
          if ($Field['size'] > 0) {
            // Verifica se o tamanho das fotos est� compat�vel com  o limite de 100KB. 
            if ($Field['size'] > $w_maximo) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Aten��o: o tamanho m�ximo do arquivo n�o pode exceder '.($w_maximo/1024).' KBytes!\');');
              ScriptClose();
              retornaFormulario('w_caminho');
              exit();
            } 
            // Se j� h� um nome para o arquivo, mant�m 
            if ($_REQUEST['w_atual']>'') {
              $RS = db_getSolicAnexo::getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],$w_cliente);
              foreach ($RS as $row) {
                if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
                if (!(strpos(f($row,'caminho'),'.')===false)) {
                  $w_file = substr(basename(f($row,'caminho')),0,(strpos(basename(f($row,'caminho')),'.') ? strrpos(basename(f($row,'caminho')),'.')+1 : 0)-1).substr($Field['name'],(strrpos($Field['name'],'.') ? strrpos($Field['name'],'.')+1 : 0)-1,30);
                } else {
                  $w_file = basename(f($row,'caminho'));
                }
              }
            } else {
              $w_file = str_replace('.tmp','',basename($Field['tmp_name']));
              if (!(strpos($Field['name'],'.')===false)) {
                $w_file = $w_file.substr($Field['name'],(strrpos($Field['name'],'.') ? strrpos($Field['name'],'.')+1 : 0)-1,10);
              }
            } 
            $w_tamanho = $Field['size'];
            $w_tipo    = $Field['type'];
            $w_nome    = $Field['name'];
            if ($w_file>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
          }elseif(nvl($Field['name'],'')!=''){
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Aten��o: o tamanho do arquivo deve ser maior que 0 KBytes!\');');
            ScriptClose();
            retornaFormulario('w_caminho');
            exit();
          } 
        } 
        // Se for exclus�o e houver um arquivo f�sico, deve remover o arquivo do disco.  
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
        ShowHTML('  alert(\'ATEN��O: ocorreu um erro na transfer�ncia do arquivo. Tente novamente!\');');
        ScriptClose();
        retornaFormulario('w_caminho');
        exit();
      } 
      ScriptOpen('JavaScript');
      // Recupera a sigla do servi�o pai, para fazer a chamada ao menu 
      $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,$SG);
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($SG=='PAINTERESS') {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putDocumentoInter::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_principal']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }  
  } elseif ($SG=='PADOCASS') {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putDocumentoAssunto::getInstanceOf($dbms,$O,null,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_principal']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }  
  } elseif (strpos($SG,'ENVIO')!==false) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      // Se o destino for pessoa jur�dica, pede unidade da pessoa
      if (nvl($_REQUEST['w_pessoa_destino'],'')!='') {
        $RS_Destino = db_getBenef::getInstanceOf($dbms,$w_cliente,$_REQUEST['w_pessoa_destino'],null,null,null,null,null,null,null,null,null,null,null,null);
        foreach ($RS_Destino as $row) { $RS_Destino = $row; break; }
        if (upper(f($RS_Destino,'nm_tipo_pessoa'))=='JUR�DICA' && nvl($_REQUEST['w_unidade_externa'],'')=='') {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATEN��O: Unidade externa � obrigat�ria quando o destino � uma pessoa jur�dica!\');');
          ScriptClose();
          retornaFormulario('w_unidade_externa');
          exit;
        }
      }

      $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],f($RS_Menu,'sigla'));
      if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite'] || nvl(f($RS,'unidade_int_posse'),'')!=nvl($_REQUEST['w_unidade_posse'],'') || nvl(f($RS,'pessoa_ext_posse'),'')!=nvl($_REQUEST['w_pessoa_posse'],'')) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATEN��O: Outro usu�rio j� tramitou este documento!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } else {
			  // Se for informado um processo, verifica se ele existe
				if (nvl($_REQUEST['w_protocolo'],'')!='') {
					$p_prefixo      = substr($_REQUEST['w_protocolo'],0,5);
					$p_numero       = substr($_REQUEST['w_protocolo'],6,6);
					$p_ano          = substr($_REQUEST['w_protocolo'],13,4);
					$RS = db_getProtocolo::getInstanceOf($dbms, f($RS_Menu,'sq_menu'), $w_usuario, 'EXISTE', $p_chave, $p_chave_aux, 
						$p_prefixo, $p_numero, $p_ano, $p_unid_autua, $p_unid_posse, $p_nu_guia, $p_ano_guia, $p_ini, $p_fim, 2, null);
					$w_existe = 0;
					foreach($RS as $row) {
					  if (f($row,'processo')=='S') {
						$w_existe = 1;
						break;
					  }
					}
		
					if ($w_existe==0) {
					  ScriptOpen('JavaScript');
					  ShowHTML('  alert(\'ATEN��O: O processo informado n�o existe!\');');
					  ScriptClose();
					  retornaFormulario('w_protocolo');
					  exit;
					}
				}
	      dml_putDocumentoEnvio::getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
            $_REQUEST['w_interno'],$_REQUEST['w_unidade_posse'],$_REQUEST['w_sq_unidade'],$_REQUEST['w_pessoa_destino'],
            $_REQUEST['w_tipo_despacho'],$p_prefixo, $p_numero, $p_ano,$_REQUEST['w_despacho'],$_REQUEST['w_aviso'],$_REQUEST['w_dias'],
            $_REQUEST['w_retorno_limite'],$_REQUEST['w_pessoa_destino_nm'],$_REQUEST['w_unidade_externa'],
            &$w_nu_guia, &$w_ano_guia, &$w_unidade_autuacao);
        // Envia e-mail comunicando a tramita��o
        // SolicMail($_REQUEST['w_chave'],2);
        // Grava baseline
        $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],'PADCAD');
        if (f($RS,'sg_tramite')=='CI') {
          $w_html = VisualDocumento($_REQUEST['w_chave'],'T',$_SESSION['SQ_PESSOA'],$P1,'WORD','S','S','S','S','S','S','S','S','S','N');
          CriaBaseLine($_REQUEST['w_chave'],$w_html,f($RS_Menu,'nome'),f($RS,'sq_siw_tramite'));
        }
        ScriptOpen('JavaScript');
        if ($P1==1) {
          // Se for envio da fase de cadastramento, remonta o menu principal
          ShowHTML('  parent.menu.location=\''.montaURL_JS(null,$conRootSIW.'menu.php?par=ExibeDocs&O=L&R='.$R.'&SG=RELPATRAM&TP='.RemoveTP(RemoveTP($TP)).'&p_unidade='.$w_unidade_autuacao.'&p_nu_guia='.$w_nu_guia.'&p_ano_guia='.$w_ano_guia).'\';');
        } else {
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
        } 
        ScriptClose();
      } 
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }  
  } elseif (strpos($SG,'PADTRAM')!==false) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if (nvl($_REQUEST['w_arq_central'],'')=='S') {
        for ($i=1; $i<count($_POST['w_chave']); $i++) {
          if (Nvl($_POST['w_chave'][$i],'')>'') {
            dml_putCaixaEnvio::getInstanceOf($dbms,f($RS_Menu,'sq_menu'),$_POST['w_chave'][$i],$w_usuario,
                $_REQUEST['w_interno'], $_POST['w_unid_origem'][$_POST['w_chave'][$i]], $_REQUEST['w_sq_unidade'],
                $_REQUEST['w_tipo_despacho'],$_REQUEST['w_despacho'],
                &$w_nu_guia, &$w_ano_guia, &$w_unidade_autuacao);
          } 
        }
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Transfer�ncia realizada com sucesso!\\nImprima a guia de transfer�ncia na pr�xima tela.\');');
        ShowHTML('  parent.menu.location=\''.montaURL_JS(null,$conRootSIW.'menu.php?par=ExibeDocs&O=L&R='.$R.'&SG=RELPATRANS&TP='.RemoveTP(RemoveTP($TP)).'&p_unidade='.$w_unidade_autuacao.'&p_nu_guia='.$w_nu_guia.'&p_ano_guia='.$w_ano_guia).'\';');
        ScriptClose();
      } else {
        // Se o destino for pessoa jur�dica, pede unidade da pessoa
        if (nvl($_REQUEST['w_pessoa_destino'],'')!='') {
          $RS_Destino = db_getBenef::getInstanceOf($dbms,$w_cliente,$_REQUEST['w_pessoa_destino'],null,null,null,null,null,null,null,null,null,null,null,null);
          foreach ($RS_Destino as $row) { $RS_Destino = $row; break; }
          if (upper(f($RS_Destino,'nm_tipo_pessoa'))=='JUR�DICA' && nvl($_REQUEST['w_unidade_externa'],'')=='') {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'ATEN��O: Unidade externa � obrigat�ria quando o destino � uma pessoa jur�dica!\');');
            ScriptClose();
            retornaFormulario('w_unidade_externa');
            exit;
          }
        }
  
        // Se for informado um processo, verifica se ele existe
        if (nvl($_REQUEST['w_protocolo'],'')!='') {
          $p_prefixo      = substr($_REQUEST['w_protocolo'],0,5);
          $p_numero       = substr($_REQUEST['w_protocolo'],6,6);
          $p_ano          = substr($_REQUEST['w_protocolo'],13,4);
          $RS = db_getProtocolo::getInstanceOf($dbms, f($RS_Menu,'sq_menu'), $w_usuario, 'EXISTE', $p_chave, $p_chave_aux, 
              $p_prefixo, $p_numero, $p_ano, $p_unid_autua, $p_unid_posse, $p_nu_guia, $p_ano_guia, $p_ini, $p_fim, 2, null);
          $w_existe = 0;
          foreach($RS as $row) {
            if (f($row,'processo')=='S') {
              $w_existe = 1;
              break;
            }
          }
  
          if ($w_existe==0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'ATEN��O: O processo informado n�o existe!\');');
            ScriptClose();
            retornaFormulario('w_protocolo');
            exit;
          }
        }
        for ($i=1; $i<count($_POST['w_chave']); $i++) {
          if (Nvl($_POST['w_chave'][$i],'')>'') {
            dml_putDocumentoEnvio::getInstanceOf($dbms,f($RS_Menu,'sq_menu'),$_POST['w_chave'][$i],$w_usuario,
                $_POST['w_tramite'][$_POST['w_chave'][$i]], $_REQUEST['w_interno'],
                $_POST['w_unid_origem'][$_POST['w_chave'][$i]], $_REQUEST['w_sq_unidade'],$_REQUEST['w_pessoa_destino'],
                $_REQUEST['w_tipo_despacho'],$p_prefixo, $p_numero, $p_ano,$_REQUEST['w_despacho'],$_REQUEST['w_aviso'],
                $_REQUEST['w_dias'],$_REQUEST['w_retorno_limite'],$_REQUEST['w_pessoa_destino_nm'],
                $_REQUEST['w_unidade_externa'],&$w_nu_guia, &$w_ano_guia, &$w_unidade_autuacao);
          } 
        }
        ScriptOpen('JavaScript');
        if (nvl($w_nu_guia,'')=='') {
          ShowHTML('  alert(\'O protocolo j� est� dispon�vel na sua unidade.\\nSe unidade de origem e de destino s�o iguais, o recebimento autom�tico!\');');
		      // Aqui deve ser usada a vari�vel de sess�o para evitar erro na recupera��o do link
		      $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,$SG);
		      ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS1,'link').'&O=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
        } else {
          ShowHTML('  alert(\'Tramita��o realizada com sucesso!\\nImprima a guia de tramita��o na pr�xima tela.\');');
          ShowHTML('  parent.menu.location=\''.montaURL_JS(null,$conRootSIW.'menu.php?par=ExibeDocs&O=L&R='.$R.'&SG=RELPATRAM&TP='.RemoveTP(RemoveTP($TP)).'&p_unidade='.$w_unidade_autuacao.'&p_nu_guia='.$w_nu_guia.'&p_ano_guia='.$w_ano_guia).'\';');
        }
        ScriptClose();
      }
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($SG=='PAENVCEN') {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      // Verifica se � necess�rio criar uma nova caixa
      $w_caixa = $_REQUEST['w_caixa'];
      if ($w_caixa==0) {
        dml_putCaixa::getInstanceOf($dbms,'I',$w_cliente,null,$_SESSION['LOTACAO'],null,null,null,
              null,null,null,null,null,null,null,null,null,null,&$w_chave_nova);
      }

      for ($i=1; $i<count($_POST['w_chave']); $i++) {
        if (Nvl($_POST['w_chave'][$i],'')>'') {
          dml_putDocumentoCaixa::getInstanceOf($dbms,f($RS_Menu,'sq_menu'),$_POST['w_chave'][$i],$w_usuario,
              (($_REQUEST['w_caixa']==0) ? $w_chave_nova : $_REQUEST['w_caixa']),$_REQUEST['w_pasta']);
        } 
      }

      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$w_pagina.'TramitCentral&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
      exit;
    } 
  } elseif ($SG=='PACLASSIF') {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      for ($i=1; $i<count($_POST['w_chave']); $i++) {
        if (Nvl($_POST['w_chave'][$i],'')>'') {
          $RS_Assunto = db_getSolicData::getInstanceOf($dbms,$_POST['w_chave'][$i],'PADCAD');
          dml_putDocumentoAssunto::getInstanceOf($dbms,'E',$_SESSION['SQ_PESSOA'],$_POST['w_chave'][$i],f($RS_Assunto,'sq_assunto'),'S');
          dml_putDocumentoAssunto::getInstanceOf($dbms,'I',$_SESSION['SQ_PESSOA'],$_POST['w_chave'][$i],$_REQUEST['w_assunto'],'S');
        } 
      }

      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$w_pagina.'Classif&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
      exit;
    } 
  } elseif (strpos($SG,'RECEB')!==false) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $RS = db_getProtocolo::getInstanceOf($dbms, $w_menu, $w_usuario, 'RECEBIDO', null, null, null, null, null, 
                $_REQUEST['w_unid_autua'], null, $_REQUEST['w_nu_guia'], $_REQUEST['w_ano_guia'], null, null, 2, null);
      if (count($RS)==0) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATEN��O: Outro usu�rio j� recebeu esta guia!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } else {
        dml_putDocumentoReceb::getInstanceOf($dbms,$w_usuario,
            $_REQUEST['w_unid_autua'],$_REQUEST['w_nu_guia'],$_REQUEST['w_ano_guia'],$_REQUEST['w_observacao']);

        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Protocolos da guia recebidos com sucesso!\');');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } 
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }  
  } elseif (strpos($SG,'CONC')!==false) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],f($RS_Menu,'sigla'));
      if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite'] || nvl(f($RS,'unidade_int_posse'),'')!=nvl($_REQUEST['w_unidade_posse'],'') || nvl(f($RS,'pessoa_ext_posse'),'')!=nvl($_REQUEST['w_pessoa_posse'],'')) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATEN��O: Outro usu�rio j� tramitou este documento!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } else {
        dml_putDocumentoConc::getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_inicio_real'],$_REQUEST['w_fim_real'],$_REQUEST['w_nota_conclusao'],$_REQUEST['w_custo_real']);
        // Envia e-mail comunicando a conclus�o
        //SolicMail($_REQUEST['w_chave'],3);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
        ScriptClose();
      } 
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'Bloco de dados n�o encontrado: '.$SG.'\');');
    ScriptClose();
    exibevariaveis();
  } 
} 
// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'INICIAL':             Inicial();                      break;
    case 'GERAL':               Geral();                        break;
    case 'INTERESS':            Interessados();                 break;
    case 'ASSUNTOS':            Assuntos();                     break;
    case 'VISUAL':              Visual();                       break;
    case 'VISUALE':             VisualE();                      break;
    case 'EXCLUIR':             Excluir();                      break;
    case 'ENVIO':               Encaminhamento();               break;
    case 'ANEXO':               Anexos();                       break;
    case 'ANOTACAO':            Anotar();                       break;
    case 'CONCLUIR':            Concluir();                     break;
    case 'BUSCAASSUNTO':        BuscaAssunto();                 break;
    case 'TRAMIT':              Tramitacao();                   break;
    case 'TRAMITCENTRAL':       TramitCentral();                break;
    case 'CLASSIF':             Classificacao();                break;
    case 'RECEB':               Recebimento();                  break;
    case 'BUSCAPROTOCOLO':      BuscaProtocolo();               break;
    case 'GRAVA':               Grava();                        break;
    default:
      cabecalho();
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');      
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