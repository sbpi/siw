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
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/db_getUorgResp.php');
include_once($w_dir_volta.'classes/sp/db_getBenef.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoGeral.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicInter.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicArquivo.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoInter.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoAssunto.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoReceb.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoConc.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoPessoaOrigem.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoEstado.php');
include_once($w_dir_volta.'funcoes/selecaoCidade.php');
include_once($w_dir_volta.'funcoes/selecaoFase.php');
include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');
include_once($w_dir_volta.'funcoes/selecaoNaturezaDocumento.php');
include_once($w_dir_volta.'funcoes/selecaoEspecieDocumento.php');
include_once($w_dir_volta.'funcoes/selecaoOrigem.php');
include_once($w_dir_volta.'funcoes/selecaoTipoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoAssunto.php');
include_once($w_dir_volta.'funcoes/selecaoAssuntoRadio.php');
include_once($w_dir_volta.'funcoes/selecaoTipoDespacho.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
include_once('visualdocumento.php');
include_once('visualGR.php');
// =========================================================================
//  /documento.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho 
// Descricao: Gerencia o módulo de protocolo e arquivos
// Mail     : celso@sbpi.com.br
// Criacao  : 09/08/2006 18:30
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
$w_pagina       = 'documento.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_pa/';
$w_troca        = $_REQUEST['w_troca'];
$p_ordena       = strtolower($_REQUEST['p_ordena']);
$w_SG           = strtoupper($_REQUEST['w_SG']);
if (strpos('PADOCANEXO,PAINTERESS,PADOCASS',$SG)!==false) {
  if ($O!='I' && $O!='E' && nvl($_REQUEST['w_chave_aux'],$_REQUEST['w_sq_pessoa'])=='') $O='L';
} elseif ($SG=='PADENVIO') {
  $O='V';
} elseif ($O=='') {
  // Se for acompanhamento, entra na filtragem
    if ($P1==3 || $SG=='PADTRAM') $O='P'; else $O='L';
} 
switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';    break;
  case 'A': $w_TP=$TP.' - Alteração';   break;
  case 'E': $w_TP=$TP.' - Exclusão';    break;
  case 'P': $w_TP=$TP.' - Filtragem';   break;
  case 'C': $w_TP=$TP.' - Cópia';       break;
  case 'V': $w_TP=$TP.' - Envio';       break;
  case 'R': $w_TP=$TP.' - Recebimento'; break;
  case 'H': $w_TP=$TP.' - Herança';     break;
  default:
    if ($par=='BUSCAPROC') $w_TP=$TP.' - Busca procedência'; else $w_TP=$TP.' - Listagem';
  break;
} 
$w_cliente  = RetornaCliente();
$w_usuario      = RetornaUsuario();
$w_menu         = RetornaMenu($w_cliente,$SG);
$w_ano          = RetornaAno();
$w_copia        = $_REQUEST['w_copia'];
$p_numero_doc   = strtoupper($_REQUEST['p_numero_doc']);
$p_atividade    = strtoupper($_REQUEST['p_atividade']);
$p_ativo        = strtoupper($_REQUEST['p_ativo']);
$p_solicitante  = strtoupper($_REQUEST['p_solicitante']);
$p_prioridade   = strtoupper($_REQUEST['p_prioridade']);
$p_unidade      = strtoupper($_REQUEST['p_unidade']);
$p_parcerias    = strtoupper($_REQUEST['p_parcerias']);
$p_ini_i        = strtoupper($_REQUEST['p_ini_i']);
$p_ini_f        = strtoupper($_REQUEST['p_ini_f']);
$p_fim_i        = strtoupper($_REQUEST['p_fim_i']);
$p_fim_f        = strtoupper($_REQUEST['p_fim_f']);
$p_atraso       = strtoupper($_REQUEST['p_atraso']);
$p_chave        = strtoupper($_REQUEST['p_chave']);
$p_assunto      = strtoupper($_REQUEST['p_assunto']);
$p_pais         = strtoupper($_REQUEST['p_pais']);
$p_regiao       = strtoupper($_REQUEST['p_regiao']);
$p_uf           = strtoupper($_REQUEST['p_uf']);
$p_cidade       = strtoupper($_REQUEST['p_cidade']);
$p_usu_resp     = strtoupper($_REQUEST['p_usu_resp']);
$p_uorg_resp    = strtoupper($_REQUEST['p_uorg_resp']);
$p_internas     = strtoupper($_REQUEST['p_internas']);
$p_prazo        = strtoupper($_REQUEST['p_prazo']);
$p_fase         = explodeArray($_REQUEST['p_fase']);
$p_numero_orig  = strtoupper($_REQUEST['p_numero_orig']);
$p_ativo        = strtoupper($_REQUEST['p_ativo']);
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

$RS_PAUnidade = db_getUnidade_PA::getInstanceOf($dbms,$w_cliente,$_SESSION['LOTACAO'],null,null);
foreach($RS_PAUnidade as $row) { $RS_PAUnidade = $row; break; }

Main();
FechaSessao($dbms);
exit;
// =========================================================================
// Rotina de visualização resumida dos registros
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  $w_tipo=$_REQUEST['w_tipo'];  
  if ($O=='L') {
    if (!(strpos(strtoupper($R),'GR_')===false)) {
      $w_filtro='';
      if ($p_numero_doc>'') {
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Nº do documento <td>[<b>'.$p_numero_doc.'</b>]';
      }
      if ($p_numero_orig>'') {
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Nº original do documento<td>[<b>'.$p_numero_orig.'</b>]';
      }
      if ($p_solicitante>'') {
        $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Responsável <td>[<b>'.f($RS,'nome_resumido').'</b>]';
      }
      if ($p_unidade>'') {
        $RS = db_getUorgData::getInstanceOf($dbms,$p_unidade);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Unidade de origem <td>[<b>'.f($RS,'nome').'</b>]';
      }
      if ($p_assunto>'') {
       $RS = db_getAssunto_PA::getInstanceOf($dbms,$w_cliente,$p_assunto,null,null,null,null,null,null,null,null,'REGISTROS');
       foreach($RS as $row){$RS=$row; break;}
       $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Assunto <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_ini_i>'')            $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Data recebimento entre <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
      if ($p_ini_i>'')            $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Data limite conclusão entre <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
      if ($w_filtro>'')           $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>';
    } 
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PADCAD');
    if ($w_copia>'') {
      // Se for cópia, aplica o filtro sobre todas as demandas visíveis pelo usuário
      $RS = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_parcerias,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_numero_doc, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, 
          null, null, $p_empenho, $p_numero_orig);
    } else {      
      $RS = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P1,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_parcerias,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_numero_doc, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, 
          null, null, $p_empenho, $p_numero_orig);
    } 
    if (Nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
     } else {
      $RS = SortArray($RS,'phpdt_fim','asc');
    }
  }
  if ($w_tipo=='WORD') {
    HeaderWord();
  } else {
    cabecalho();
    ShowHTML('<HEAD>');
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
        // Se não for cadastramento ou se for cópia
        Validate('p_numero_doc','Número de protocolo','1','','20','20','','0123456789./-');
        Validate('p_ini_i','Recebimento inicial','DATA','','10','10','','0123456789/');
        Validate('p_ini_f','Recebimento final','DATA','','10','10','','0123456789/');
        ShowHTML('  if ((theForm.p_ini_i.value != \'\' && theForm.p_ini_f.value == \'\') || (theForm.p_ini_i.value == \'\' && theForm.p_ini_f.value != \'\')) {');
        ShowHTML('     alert (\'Informe ambas as datas de início ou nenhuma delas!\');');
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
  }
  if ($w_troca>'') {
    // Se for recarga da página
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad=\'document.Form.w_tipo.focus()\';');
  } elseif ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen(null);
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  if ($w_filtro>'') ShowHTML($w_filtro);
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr>');
    if ($w_tipo!='WORD') {
      ShowHTML('<td><font size="2">');
      if ($P1==1 && $w_copia=='') {
        // Se for cadastramento e não for resultado de busca para cópia
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
      if ((strpos(strtoupper($R),'GR_')===false)) {
        if ($w_copia>'') {
          // Se for cópia
          if (MontaFiltro('GET')>'') ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
          else                       ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
        } else {
          if (MontaFiltro('GET')>'')  ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
          else                        ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
        } 
      } 
    }
    ShowHTML('    <td align="right">');
    if ($w_tipo!='WORD') {
      ShowHTML('     <IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      ShowHTML('     &nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&O=L&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.count($RS).'&TP='.$TP.'&SG='.$SG.'&w_tipo=WORD'.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
    } 
    ShowHTML('    <b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    if ($w_tipo!='WORD') {
      ShowHTML('          <td rowspan=2 width="1%" nowrap><b>'.LinkOrdena('Protocolo','protocolo').'</td>');
      ShowHTML('          <td colspan=4><b>Documento original</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Recebimento','data_recebimento').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Limite','fim').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Fase atual','nm_tramite').'</td>');
      ShowHTML('          <td rowspan=2><b>Operações</td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>'.LinkOrdena('Espécie','nm_especie').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Nº','numero_original').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Data','inicio').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Origem','nm_origem').'</td>');
    } else {
      ShowHTML('          <td rowspan=2 width="1%" nowrap><b>Protocolo</td>');
      ShowHTML('          <td colspan=4><b>Documento original</td>');
      ShowHTML('          <td rowspan=2><b>Recebimento</td>');
      ShowHTML('          <td rowspan=2><b>Limite</td>');
      ShowHTML('          <td rowspan=2><b>Fase atual</td>');
//      ShowHTML('          <td rowspan=2><b>Operações</td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>Espécie</td>');
      ShowHTML('          <td><b>Nº</td>');
      ShowHTML('          <td><b>Data</td>');
      ShowHTML('          <td><b>Origem</td>');
    }  
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial=0;
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);      
      foreach($RS1 as $row) {
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
        if ($w_tipo!='WORD') ShowHTML('        <A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'protocolo').'&nbsp;</a>');
        else                 ShowHTML('        '.f($row,'protocolo').'');
        ShowHTML('        <td>'.f($row,'nm_especie').'</td>');
        ShowHTML('        <td>'.f($row,'numero_original').'</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'inicio')).'</td>');
        ShowHTML('        <td>'.f($row,'nm_origem').'</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'data_recebimento')).'</td>');
        ShowHTML('        <td align="center">'.nvl(FormataDataEdicao(f($row,'fim')),'---').'</td>');
        ShowHTML('        <td nowrap>'.f($row,'nm_tramite').'</td>');
        if ($w_tipo!='WORD'){        
        ShowHTML('        <td align="top" nowrap>');
          if ($P1!=3 && $P1!=5) {
            // Se não for acompanhamento
            if ($w_copia>'') {
              // Se for listagem para cópia
              $RS1 = db_getLinkSubMenu::getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);
              //ShowHTML '          <a accesskey=''I'' class=''HL'' href=''' & w_dir & w_pagina & 'Geral&R=' & w_pagina & par & '&O=I&SG=' & RS1('sigla') & '&w_menu=' & w_menu & '&P1=' & P1 & '&P2=' & P2 & '&P3=' & P3 & '&P4=' & P4 & '&TP=' & TP & '&w_copia=' & RS('sq_siw_solicitacao') & MontaFiltro('GET') & '''>Copiar</a>&nbsp;'
            } elseif ($P1==1) {
              // Se for cadastramento
              ShowHTML('          <A class="HL" HREF="menu.php?par=ExibeDocs&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&R='.$w_pagina.$par.'&SG='.$SG.'&TP='.$TP.'&w_documento='.f($row,'protocolo').MontaFiltro('GET').'" title="Altera as informações cadastrais do documento" TARGET="menu">AL</a>&nbsp;');
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclusão do documento.">EX</A>&nbsp');
            } elseif ($P1==2 || $P1==6) {
              // Se for execução
              if ($w_usuario==f($row,'executor')) {
                // Coloca as operações dependendo do trâmite
                if (f($row,'sg_tramite')=='EA') {
                  ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anotações para a ação, sem enviá-la.">AN</A>&nbsp');
                } elseif (f($row,'sg_tramite')=='EE') {
                  ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anotações para a ação, sem enviá-la.">AN</A>&nbsp');
                } 
              } else {
                ShowHTML('          ---&nbsp');
              } 
            } 
          } else {
            if (Nvl(f($row,'solicitante'),0) == $w_usuario || 
                Nvl(f($row,'titular'),0)     == $w_usuario || 
                Nvl(f($row,'substituto'),0)  == $w_usuario || 
                Nvl(f($row,'tit_exec'),0)    == $w_usuario || 
                Nvl(f($row,'subst_exec'),0)  == $w_usuario) {
              // Se o usuário for responsável por uma ação, titular/substituto do setor responsável 
              // ou titular/substituto da unidade executora,
              // pode enviar.
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a ação para outro responsável.">EN</A>&nbsp');
            } 
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
    if ($w_tipo!='WORD'){    
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
      // Recupera dados da opçãa açãos
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td><b><u>P</u>rotocolo:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="p_numero_doc" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$p_numero_doc.'" onKeyDown="FormataProtocolo(this,event);"></td>');
      ShowHTML('          <td valign="top"><b>Nº original do documento:<br><INPUT '.$w_Disabled.' class="STI" type="text" name="p_numero_orig" size="40" maxlength="90" value="'.$p_numero_orig.'"></td>');
      ShowHTML('      <tr valign="top">');
      SelecaoAssuntoRadio('Assun<u>t</u>o:','T','Clique na lupa para selecionar o assunto do documento.',$p_assunto,null,'p_assunto','FOLHA',null);
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('<u>P</u>essoa de origem:','P','Selecione a pessoa de origem.',$p_solicitante,null,'p_solicitante','USUARIOS');
      SelecaoUnidade('<U>U</U>nidade de origem:','U',null,$p_unidade,null,'p_unidade',null,null);
      ShowHTML('      <tr>');
      ShowHTML('          <td valign="top"><b>Data de recebimento entre:</b><br><input '.$w_Disabled.' type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
      ShowHTML('          <td valign="top"><b>Data de limite para conclusão entre:</b><br><input '.$w_Disabled.' type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
      if ($O!='C') {
        // Se não for cópia
        ShowHTML('      <tr>');
        ShowHTML('          <td valign="top"><b>Exibe somente protocolos em atraso?</b><br>');
        if ($p_atraso=='S') ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S" checked> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N"> Não');
        else                ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N" checked> Não');
        SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase',null,null);
      } 
    }
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="STS" name="p_ordena" size="1">');
    if ($p_ordena=='ASSUNTO')       ShowHTML('          <option value="assunto" SELECTED>Assunto<option value="inicio">Data de início<option value="">Data de término<option value="nm_tramite">Fase atual<option value="proponente">Proponente externo');
    elseif ($p_ordena=='INICIO')    ShowHTML('          <option value="assunto">Assunto<option value="inicio" SELECTED>Data de início<option value="">Data de término<option value="nm_tramite">Fase atual<option value="proponente">Proponente externo');
    elseif ($p_ordena=='NM_TRAMITE')ShowHTML('          <option value="assunto">Assunto<option value="inicio">Data de início<option value="">Data de término<option value="nm_tramite" SELECTED>Fase atual<option value="proponente">Proponente externo');
    elseif ($p_ordena=='PRIORIDADE')ShowHTML('          <option value="assunto">Assunto<option value="inicio">Data de início<option value="">Data de término<option value="nm_tramite">Fase atual<option value="proponente">Proponente externo');
    elseif ($p_ordena=='PROPONENTE')ShowHTML('          <option value="assunto">Assunto<option value="inicio">Data de início<option value="">Data de término<option value="nm_tramite">Fase atual<option value="proponente" SELECTED>Proponente externo');
    else                            ShowHTML('          <option value="assunto">Assunto<option value="inicio">Data de início<option value="" SELECTED>Data de término<option value="nm_tramite">Fase atual<option value="prioridade">Proponente externo');
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
  $w_chave      = $_REQUEST['w_chave'];
  $w_processo   = nvl($_REQUEST['w_processo'],'N');
  $w_circular   = nvl($_REQUEST['w_circular'],'N');
  $w_readonly   = '';
  $w_erro       = '';

  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
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
    $w_nm_pessoa_origem   = $_REQUEST['w_nm_pessoa_origem'];
    $w_pessoa_origem      = $_REQUEST['w_pessoa_origem'];
    $w_sq_unidade         = $_REQUEST['w_sq_unidade'];
  } else {
    if (!(strpos('AEV',$O)===false) || $w_copia>'') {
      // Recupera os dados da ação
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
        $w_nm_pessoa_origem   = f($RS,'nm_pessoa_origem');
        $w_pessoa_origem      = f($RS,'pessoa_origem');
        $w_sq_unidade         = f($RS,'sq_unidade');
      } 
    } 
  } 

  // Configura variáveis de controle para processos e circulares
  if (nvl($w_especie_documento,'')!='') {
    $RS = db_getEspecieDocumento_PA::getInstanceOf($dbms,$w_especie_documento,$w_cliente,null,null,null,null);
    foreach ($RS as $row) { $RS = $row; break; }
    if (f($RS,'sigla')=='PROC') {
      $w_processo = 'S';
      $w_circular = 'N';
    } elseif (strpos(strtoupper(f($RS,'nome')),'CIRCULAR')!==false) {
      $w_processo = 'N';
      $w_circular = 'S';
    } else {
      $w_processo = 'N';
      $w_circular = 'N';
    }
  }
  
  if (nvl($w_assunto,'')=='') {
    // Só pode haver um registro para classificação provisória
    $RS_Assunto = db_getAssunto_PA::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null,null,null,null,'PROVISORIO');
    foreach($RS_Assunto as $row) { $RS_Assunto = $row; break; }
    if (count($RS_Assunto)>0) $w_assunto = f($RS_Assunto,'sq_assunto');
  }
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
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
    Validate('w_especie_documento','Espécie documental','SELECT',1,1,18,'','0123456789');
    Validate('w_doc_original','Nº do documento','1','1',1,30,'1','1');
    Validate('w_data_documento','Data do documento','DATA','1',10,10,'','0123456789/');
    Validate('w_interno','Origem','SELECT',1,1,1,'SN','');
    if ($w_interno=='N') {
      Validate('w_pais','País','SELECT',1,1,18,'','0123456789');
      Validate('w_uf','Estado','SELECT',1,1,3,'1','1');
      Validate('w_cidade','Cidade','SELECT',1,1,18,'','0123456789');
      Validate('w_pessoa_origem','Pessoa de origem','HIDDEN',1,1,18,'','0123456789');
      Validate('w_pessoa_interes','Interessado principal','HIDDEN',1,1,18,'','0123456789');
    } else {
      Validate('w_sq_unidade','Unidade de origem','SELECT',1,1,18,'','0123456789');
    }
    Validate('w_data_recebimento','Data de criação/recebimento','DATA','1',10,10,'','0123456789/');
    CompData('w_data_recebimento','Data de criação/recebimento','>=','w_data_documento','Data do documento');
    if ($w_processo=='S') {
      Validate('w_volumes','Nº de volumes','1','1',1,18,'','0123456789');
      CompValor('w_volumes','Nº de volumes','>',0,'zero');
    } elseif ($w_circular=='S') {
      Validate('w_copias','Nº de cópias','1','1',1,18,'','0123456789');
      CompValor('w_copias','Nº de cópias','>',2,'dois');
    }
    Validate('w_fim','Data limite para conclusão','DATA','',10,10,'','0123456789/');
    CompData('w_fim','Data limite para conclusão','>=','w_data_documento','Data do documento');
    Validate('w_assunto','Assunto principal','HIDDEN',1,1,18,'','0123456789');
    Validate('w_descricao','Complemento do assunto','1','1',1,2000,'1','1');
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
      // Carrega os valores padrão para país, estado e cidade
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
    ShowHTML('<INPUT type="hidden" name="w_circular" value="'.$w_circular.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 colspan=0 cellspan=0 width="100%">');
    ShowHTML('      <tr><td colspan=4 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=4><b>IDENTIFICAÇÃO</b></td></tr>');
    ShowHTML('      <tr valign="top">');
    selecaoEspecieDocumento('<u>E</u>spécie documental:','E','Selecione a espécie do documento.',$w_especie_documento,null,'w_especie_documento',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_doc_original\'; document.Form.submit();"');
    ShowHTML('           <td title="Informe o número do documento de origem."><b>Número:</b><br><INPUT '.$w_Disabled.' class="STI" type="text" name="w_doc_original" size="20" maxlength="30" value="'.$w_doc_original.'" ></td>');
    ShowHTML('           <td title="Informe a data do documento de origem."><b>D<u>a</u>ta:</b><br><input '.$w_Disabled.' accesskey="A" type="text" name="w_data_documento" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data_documento.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data original do documento.">'.ExibeCalendario('Form','w_data_documento').'</td>');
    selecaoOrigem('<u>O</u>rigem:','O','Indique se a origem é interna ou externa.',$w_interno,null,'w_interno',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_interno\'; document.Form.submit();"');
    ShowHTML('      <tr><td colspan=4>&nbsp;</td></tr>');
    ShowHTML('      <tr><td colspan=4 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=4><b>PROCEDÊNCIA</b></td></tr>');
    ShowHTML('        <tr valign="top">');
    if ($w_interno=='N') {
      ShowHTML('       <tr valign="top">');
      SelecaoPais('<u>P</u>aís:','P',"Selecione o país de procedência.",$w_pais,null,'w_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_uf\'; document.Form.submit();"');
      SelecaoEstado('E<u>s</u>tado:','S',"Selecione o estado de procedência.",$w_uf,$w_pais,null,'w_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_cidade\'; document.Form.submit();"');
      ShowHTML('          <td colspan=2><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
      SelecaoCidade('<u>C</u>idade:','C',"Selecione a cidade de procedência.",$w_cidade,$w_pais,$w_uf,'w_cidade',null,null);
      ShowHTML('           </table>');
      ShowHTML('      <tr><td colspan=4><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
      SelecaoPessoaOrigem('<u>P</u>essoa de origem:','P','Clique na lupa para selecionar a pessoa de origem.',$w_pessoa_origem,null,'w_pessoa_origem',null,null,null);
      ShowHTML('           </table>');
      ShowHTML('      <tr><td colspan=4><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
      SelecaoPessoaOrigem('<u>I</u>nteressado principal:','I','Clique na lupa para selecionar o interessado principal.',$w_pessoa_interes,null,'w_pessoa_interes',null,null,null);
      ShowHTML('           </table>');
    } else {
      ShowHTML('      <tr><td colspan=4><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
      SelecaoUnidade('<U>U</U>nidade de origem:','U','Selecione a unidade de origem.',nvl($w_sq_unidade,$_SESSION['LOTACAO']),null,'w_sq_unidade','MOD_PA',null);
      ShowHTML('           </table>');
    }
    ShowHTML('      <tr><td colspan=4>&nbsp;</td></tr>');
    ShowHTML('      <tr><td colspan=4 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=4><b>DADOS COMPLEMENTARES</b></td></tr>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('           <td valign="top" title="Informe a data de criação ou de recebimento."><b><u>D</u>ata de criação/recebimento:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data_recebimento" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data_recebimento.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_data_recebimento').'</td>');
    if ($w_processo=='S') {
      ShowHTML('           <td title="Informe quantos volumes compõem o processo."><b>Nº de volumes:</b><br><INPUT '.$w_Disabled.' class="STI" type="text" name="w_volumes" size="3" maxlength="3" value="'.$w_volumes.'" ></td>');
    } elseif ($w_circular=='S') {
      ShowHTML('           <td title="Informe o número de cópias da circular."><b>Nº de cópias:</b><br><INPUT '.$w_Disabled.' class="STI" type="text" name="w_copias" size="5" maxlength="18" value="'.$w_copias.'" ></td>');
    }
    selecaoNaturezaDocumento('<u>N</u>atureza:','N','Indique a natureza do documento.',$w_natureza_documento,null,'w_natureza_documento',null,null);
    ShowHTML('           <td title="OPCIONAL. Limite para término da tramitação do documento."><b>Data limite:</b><br><input '.$w_Disabled.' type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_fim').'</td>');
    ShowHTML('      <tr><td colspan=4><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
    SelecaoAssuntoRadio('Assun<u>t</u>o:','T','Clique na lupa para selecionar o assunto do documento.',$w_assunto,null,'w_assunto','FOLHA','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_descricao\'; document.Form.submit();"');
    ShowHTML('           </table>');
    if (nvl($w_assunto,'')!='') {
      $RS = db_getAssunto_PA::getInstanceOf($dbms,$w_cliente,$w_assunto,null,null,null,null,null,null,null,null,'REGISTROS');
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center">');
      ShowHTML('          <TABLE WIDTH="100%" BORDER="0">');
      ShowHTML('            <tr bgcolor="#DADADA">');
      ShowHTML('              <td><b>Código</td>');
      ShowHTML('              <td><b>Descrição</td>');
      ShowHTML('              <td><b>Detalhamento</td>');
      ShowHTML('              <td><b>Observação</td>');
      ShowHTML('            </tr>');
      foreach($RS as $row) {
        ShowHTML('            <tr valign="top" bgcolor="#DADADA">');
        ShowHTML('              <td width="1%" nowrap>'.f($row,'codigo').'</td>');
        ShowHTML('              <td>');
        ShowHTML('                '.f($row,'descricao'));
        if (nvl(f($row,'ds_assunto_pai'),'')!='') { 
          echo '<br>';
          if (nvl(f($row,'ds_assunto_bis'),'')!='') ShowHTML(strtolower(f($row,'ds_assunto_bis')).' &rarr; ');
          if (nvl(f($row,'ds_assunto_avo'),'')!='') ShowHTML(strtolower(f($row,'ds_assunto_avo')).' &rarr; ');
          if (nvl(f($row,'ds_assunto_pai'),'')!='') ShowHTML(strtolower(f($row,'ds_assunto_pai')));
        }
        ShowHTML('              <td>'.nvl(f($row,'detalhamento'),'---').'</td>');
        ShowHTML('              <td>'.nvl(f($row,'observacao'),'---').'</td>');
      } 
      ShowHTML('            </table></tr>');
    }
    ShowHTML('      <tr><td colspan="4" title="Descreva de forma objetiva o conteúdo do documento."><b>Compl<u>e</u>mento:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_descricao" class="STI" ROWS=5 cols=75>'.$w_descricao.'</TEXTAREA></td>');    
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
// Rotina de interessados
// -------------------------------------------------------------------------
function Interessados() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];

  if ($w_troca>'') {
    // Se for recarga da página
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
  ShowHTML('<HEAD>');
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
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Insira cada um dos interessados complementares, lembrando que o interessado principal já foi cadastrado na tela de identificação.');
    ShowHTML('  </ul></b></font></td>');
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Nome</td>');
    ShowHTML('          <td><b>CPF/CNPJ</td>');
    ShowHTML('          <td><b>RG/Inscrição estadual</td>');
    ShowHTML('          <td><b>Passaporte</td>');
    ShowHTML('          <td><b>Sexo</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
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
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave_aux').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma desvinculação do pessoa ao documento?\');">Desvincular</A>&nbsp');
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
    ShowHTML(' alert(\'Opção não disponível\');');
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
    // Se for recarga da página
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
  ShowHTML('<HEAD>');
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
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Se este documento tiver assuntos complementares, insira cada um deles.');
    ShowHTML('  <li>Para alterar o assunto principal do documento, use a tela de identificação.');
    ShowHTML('  </ul></b></font></td>');
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Código</td>');
    ShowHTML('          <td><b>Descrição</td>');
    ShowHTML('          <td><b>Detalhamento</td>');
    ShowHTML('          <td><b>Observação</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
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
          if (nvl(f($row,'ds_assunto_bis'),'')!='') ShowHTML(strtolower(f($row,'ds_assunto_bis')).' &rarr; ');
          if (nvl(f($row,'ds_assunto_avo'),'')!='') ShowHTML(strtolower(f($row,'ds_assunto_avo')).' &rarr; ');
          if (nvl(f($row,'ds_assunto_pai'),'')!='') ShowHTML(strtolower(f($row,'ds_assunto_pai')));
        }
        ShowHTML('        <td>'.nvl(strtolower(f($row,'detalhamento')),'---').'</td>');
        ShowHTML('        <td>'.nvl(strtolower(f($row,'observacao')),'---').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave_aux').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma desvinculação do assunto ao documento?\');">Desvincular</A>&nbsp');
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
      ShowHTML('              <td><b>Código</td>');
      ShowHTML('              <td><b>Descrição</td>');
      ShowHTML('              <td><b>Detalhamento</td>');
      ShowHTML('              <td><b>Observação</td>');
      ShowHTML('            </tr>');
      foreach($RS as $row) {
        ShowHTML('            <tr valign="top">');
        ShowHTML('              <td width="1%" nowrap>'.f($row,'codigo').'</td>');
        ShowHTML('              <td>');
        ShowHTML('                '.f($row,'descricao'));
        if (nvl(f($row,'ds_assunto_pai'),'')!='') { 
          echo '<br>';
          if (nvl(f($row,'ds_assunto_bis'),'')!='') ShowHTML(strtolower(f($row,'ds_assunto_bis')).' &rarr; ');
          if (nvl(f($row,'ds_assunto_avo'),'')!='') ShowHTML(strtolower(f($row,'ds_assunto_avo')).' &rarr; ');
          if (nvl(f($row,'ds_assunto_pai'),'')!='') ShowHTML(strtolower(f($row,'ds_assunto_pai')));
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
    $w_nome      = $_REQUEST['w_nome'];
    $w_descricao = $_REQUEST['w_descricao'];
    $w_caminho   = $_REQUEST['w_caminho'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem 
    $RS = db_getSolicAnexo::getInstanceOf($dbms,$w_chave,null,$w_cliente);
    $RS = SortArray($RS,'nome','asc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados do endereço informado 
    $RS = db_getSolicAnexo::getInstanceOf($dbms,$w_chave,$w_chave_aux,$w_cliente);
    foreach($RS as $row){$RS=$row; break;}
    $w_nome      = f($RS,'nome');
    $w_descricao = f($RS,'descricao');
    $w_caminho   = f($RS,'chave_aux');
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
    ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Se necessário, insira cada um dos arquivos vinculados ao documento.');
    ShowHTML('  <li>Os arquivos devem estar em seu computador e podem ser de qualquer formato.');
    ShowHTML('  </ul></b></font></td>');
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
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);'); 
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de visualização do novo layout de relatórios
// -------------------------------------------------------------------------
function Visual($w_chave=null,$w_o=null,$w_usuario=null,$w_p1=null,$w_tipo=null,$w_identificacao=null,$w_responsavel=null,
    $w_assunto_princ=null,$w_orcamentaria=null,$w_indicador=null,$w_recurso=null,$w_interessado=null,$w_anexo=null,
    $w_meta=null,$w_ocorrencia=null,$w_consulta=null) {
  extract($GLOBALS);
  $w_chave    = nvl($w_chave,$_REQUEST['w_chave']);
  $w_tipo     = nvl($w_tipo,strtoupper(trim($_REQUEST['w_tipo'])));
  $w_formato  = nvl($w_formato,strtoupper(trim($_REQUEST['w_formato'])));
  if ($O=='T') {
    $w_identificacao    = strtoupper(nvl($w_identificacao,'S'));
    $w_responsavel      = strtoupper(nvl($w_responsavel,'S'));
    $w_assunto_princ    = strtoupper(nvl($w_qualitativa,'S'));
    $w_orcamentaria     = strtoupper(nvl($w_orcamentaria,'S'));
    $w_indicador        = strtoupper(nvl($w_indicador,'S'));
    $w_recurso          = strtoupper(nvl($w_recurso,'S'));
    $w_interessado      = strtoupper(nvl($w_interessado,'S'));
    $w_anexo            = strtoupper(nvl($w_anexo,'S'));
    $w_meta             = strtoupper(nvl($w_meta,'S'));
    $w_ocorrencia       = strtoupper(nvl($w_ocorrencia,'S'));
    $w_consulta         = strtoupper(nvl($w_consulta,'N'));
  } else {
    $w_identificacao    = strtoupper(nvl($w_identificacao,'S'));
    $w_responsavel      = strtoupper(nvl($w_responsavel,'N'));
    $w_assunto_princ    = strtoupper(nvl($w_qualitativa,'S'));
    $w_orcamentaria     = strtoupper(nvl($w_orcamentaria,'N'));
    $w_indicador        = strtoupper(nvl($w_indicador,'N'));
    $w_recurso          = strtoupper(nvl($w_recurso,'N'));
    $w_interessado      = strtoupper(nvl($w_interessado,'N'));
    $w_anexo            = strtoupper(nvl($w_anexo,'N'));
    $w_meta             = strtoupper(nvl($w_meta,'N'));
    $w_ocorrencia       = strtoupper(nvl($w_ocorrencia,'S'));
    $w_consulta         = strtoupper(nvl($w_consulta,'N'));
  }
  // Recupera o logo do cliente a ser usado nas listagens
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  if ($w_o!='V') {
    if (f($RS,'logo')>'') $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
    if ($w_formato=='WORD') HeaderWord(null); else Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>'.$conSgSistema.' - Visualização de '.f($RS_Menu,'nome').'</TITLE>');
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($w_formato!='WORD') BodyOpenClean(null);
    ShowHTML('<div align="center">');
  }
  ShowHTML('<table width="95%" border="0" cellspacing="3">');
  if ($w_o!='V') {
    ShowHTML('<tr><td colspan="2">');
    ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><DIV ALIGN="LEFT"><IMG src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"></DIV></TD>');
    ShowHTML('<TD><DIV ALIGN="RIGHT"><FONT SIZE=4 COLOR="#000000"><B>');
    if ($P1==1 || $P1==2) ShowHTML('Ficha Resumida de '.f($RS_Menu,'nome'));
    else                  ShowHTML('Ficha de '.f($RS_Menu,'nome'));
    ShowHTML('</B></FONT></DIV></TD></TR>');
    ShowHTML('</TABLE></TD></TR>');
  }
  if (nvl($w_tipo,'')!='' && $w_formato!='WORD') ShowHTML('<tr><td colspan="2" align="center"><b>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></td></tr>');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML('<tr><td colspan="2" align="center">');
  ShowHTML(VisualDocumento($w_chave,$w_o,$w_usuario,$w_p1,$w_formato,$w_identificacao,$w_assunto_princ,$w_orcamentaria,$w_indicador,$w_recurso,$w_interessado,$w_anexo,$w_meta,$w_ocorrencia,$w_consulta));
  if (nvl($w_tipo,'')!='' && $w_formato!='WORD') ShowHTML('<tr><td colspan="2" align="center"><br><b>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></td></tr>');
  ShowHTML('</table>');
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
    $w_observacao = $_REQUEST['w_observacao'];
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
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
  $w_pede_unid  = false;
  
  if ($w_troca>'') {
    // Se for recarga da página
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
  }

  $RS_Solic = db_getSolicData::getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));
  
  if (strpos(strtoupper(f($RS_Solic,'nm_especie')),'CIRCULAR')!==false) {
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpenClean('onLoad=\'this.focus()\';');
    $Estrutura_Topo_Limpo;
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    $Estrutura_Texto_Abre;
    ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center"><font size=2 color="red">');
    ShowHTML('   A tramitação de circulares só pode ser feita através de opção específica no menu principal.');
    ShowHTML('</td></tr>');
    ShowHTML('</table>');
    Rodape();
    exit();
  }
  
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if (strpos('V',$O)!==false) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    Validate('w_retorno_limite','Prazo de resposta','DATA','',10,10,'','0123456789/');
    CompData('w_retorno_limite','Prazo de resposta','>=',FormataDataEdicao(time()),'data atual');
    Validate('w_dias','Dias para encaminhamento','1','',1,3,'','0123456789');
    ShowHTML('  if (theForm.w_aviso[0].checked) {');
    ShowHTML('     if (theForm.w_dias.value == \'\') {');
    ShowHTML('        alert(\'Informe a partir de quantos dias após o envio você deseja ser avisado!\');');
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
    Validate('w_despacho','Detalhamento do despacho','','1','1','2000','1','1');
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
    BodyOpen('onLoad=\'document.Form.w_retorno_limite.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align="center">');
  ShowHTML('<table width="95%" border="0" cellspacing="3">');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
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
  ShowHTML('      <tr><td colspan="3"  bgcolor="#f0f0f0" align=justify><font size="2"><b>NOVO TRÂMITE</b></font></td></tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('           <td><b>Data de recebimento:</b><br>'.formataDataEdicao(f($RS_Solic,'data_recebimento')).'</td>');
  ShowHTML('           <td colspan=2><b>Unidade remetente:</b><br>'.f($RS_Solic,'nm_unid_origem').'</td>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('           <td title="Informe a data limite para que o destinatário encaminhe o documento."><b>Praz<u>o</u> de resposta:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_retorno_limite" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_retorno_limite.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_retorno_limite').'</td>');
  MontaRadioNS('<b>Emite alerta de não encaminhamento?</b>',$w_aviso,'w_aviso');
  ShowHTML('           <td valign="top"><b>Quantos <U>d</U>ias após esta tramitação?<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="w_dias" size="3" maxlength="3" value="'.$w_dias.'" title="A partir de quantos dias após este encaminhamento o sistema deve emitir o alerta."></td>');
  ShowHTML('      <tr><td>&nbsp;</td></br>');
  ShowHTML('      <tr><td colspan="3"  bgcolor="#f0f0f0" align=justify><font size="2"><b>DESTINO</b></font></td></tr>');
  ShowHTML('      <tr valign="top">');
  selecaoOrigem('<u>T</u>ipo da unidade/pessoa:','T','Indique se a unidade ou pessoa é interna ou externa.',$w_interno,null,'w_interno',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_interno\'; document.Form.submit();"');
  if ($w_interno=='N') {
    SelecaoPessoaOrigem('<u>P</u>essoa de destino:','P','Clique na lupa para selecionar a pessoa de destino.',$w_pessoa_destino,null,'w_pessoa_destino',null,null,null);
    ShowHTML('        <td><b>U<U>n</U>idade externa: (preencher apenas para pessoas jurídicas)<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="w_unidade_externa" size="30" maxlength="60" value="'.$w_unidade_externa.'"></td>');
  } else {
    ShowHTML('          <td colspan=2><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade de destino:','U','Selecione a unidade de destino.',$w_sq_unidade,null,'w_sq_unidade','MOD_PA',null);
    ShowHTML('           </table>');
  }
  ShowHTML('      <tr><td colspan=3><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
  selecaoTipoDespacho('Des<u>p</u>acho:','P','Selecione o despacho desejado.',$w_cliente,$w_tipo_despacho,null,'w_tipo_despacho','SELECAO',null);
  ShowHTML('           </table>');
  ShowHTML('    <tr><td valign="top" colspan=3><b>Detalhamento do d<u>e</u>spacho:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Detalhe a ação a ser executada pelo destinatário.">'.$w_despacho.'</TEXTAREA></td>');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=3><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
// Rotina de anotação
// -------------------------------------------------------------------------
function Anotar() {
  extract($GLOBALS);
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_observacao = $_REQUEST['w_observacao'];
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
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
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_inicio_real      = $_REQUEST['w_inicio_real'];
    $w_fim_real         = $_REQUEST['w_fim_real'];
    $w_concluida        = $_REQUEST['w_concluida'];
    $w_data_conclusao   = $_REQUEST['w_data_conclusao'];
    $w_nota_conclusao   = $_REQUEST['w_nota_conclusao'];
    $w_custo_real       = $_REQUEST['w_custo_real'];
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if (!(strpos('V',$O)===false)) {
    ScriptOpen('JavaScript');
    checkbranco();
    FormataData();
    SaltaCampo();
    FormataDataHora();
    FormataValor();
    ValidateOpen('Validacao');
    Validate('w_inicio_real','Início da execução', 'DATA', 1, 10, 10, '', '0123456789/');
    Validate('w_fim_real','Término da execução', 'DATA', 1, 10, 10, '', '0123456789/');
    CompData('w_inicio_real','Início da execução','<=','w_fim_real','Término da execução');
    CompData('w_fim_real','Término da execução','<=',FormataDataEdicao(time()),'data atual');
    Validate('w_custo_real','Recurso executado', 'VALOR', '1', 4, 18, '', '0123456789.,');
    Validate('w_nota_conclusao','Nota de conclusão', '', '1', '1', '2000', '1', '1');
    Validate('w_assinatura','Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
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
    ShowHTML('              <td valign="top"><b>Iní<u>c</u>io da execução:</b><br><input readonly '.$w_Disabled.' accesskey="C" type="text" name="w_inicio_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_inicio_real,'01/01/'.$w_ano).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de início da execução do programa.(Usar formato dd/mm/aaaa)"></td>');
    ShowHTML('              <td valign="top"><b><u>T</u>érmino da execução:</b><br><input readonly '.$w_Disabled.' accesskey="T" type="text" name="w_fim_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_fim_real,'31/12/'.$w_ano).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de término da execução do programa.(Usar formato dd/mm/aaaa)"></td>');
  } else {
    ShowHTML('              <td valign="top"><b>Iní<u>c</u>io da execução:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="'.Nvl($w_inicio_real,'01/01/'.$w_ano).'" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio_real.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de início da execução do programa.(Usar formato dd/mm/aaaa)"></td>');
    ShowHTML('              <td valign="top"><b><u>T</u>érmino da execução:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="'.Nvl($w_fim_real,'31/12/'.$w_ano).'" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim_real.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de término da execução do programa.(Usar formato dd/mm/aaaa)"></td>');
  } 
  ShowHTML('              <td valign="top"><b><u>R</u>ecurso executado:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_custo_real" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_custo_real.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor que foi efetivamente gasto com a execução do programa."></td>');
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
// Rotina de preparação para envio de e-mail relativo a programas
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
    if ($p_tipo==1)       $w_html.='      <tr valign="top"><td align="center"><font size=2><b>INCLUSÃO DE PROGRAMA</b></font><br><br><td></tr>'.$crlf;
    elseif ($p_tipo==2)   $w_html.='      <tr valign="top"><td align="center"><font size=2><b>TRAMITAÇÃO DE PROGRAMA</b></font><br><br><td></tr>'.$crlf;
    elseif ($p_tipo==3)   $w_html.='      <tr valign="top"><td align="center"><font size=2><b>CONCLUSÃO DE PROGRAMA</b></font><br><br><td></tr>'.$crlf;
    $w_html.='      <tr valign="top"><td><font size=2><b><font color="#BC3131">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><td></tr>'.$crlf;
    // Recupera os dados da ação
    $w_nome = 'Programa '.f($RSM,'titulo');
    $w_html.=$crlf.'<tr bgcolor="'.$conTrBgColor.'"><td align="center">';
    $w_html.=$crlf.'    <table width="99%" border="0">';
    $w_html.=$crlf.'      <tr><td><font size=2>Programa: <b>'.f($RSM,'titulo').'</b></font></td>';
    // Identificação da ação
    $w_html.=$crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>EXTRATO DO PROGRAMA</td>';
    $w_html.=$crlf.'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    $w_html.=$crlf.'          <tr valign="top">';
    $w_html.=$crlf.'          <td>Responsável pelo monitoramento:<br><b>'.f($RSM,'nm_sol').'</b></td>';
    $w_html.=$crlf.'          <td>Área de planejamento:<br><b>'.f($RSM,'nm_unidade_resp').'</b></td>';
    $w_html.=$crlf.'          <tr valign="top">';
    $w_html.=$crlf.'          <td>Data de início:<br><b>'.$FormataDataEdicao[f($RSM,'inicio')].' </b></td>';
    $w_html.=$crlf.'          <td>Data de término:<br><b>'.$FormataDataEdicao[f($RSM,'fim')].' </b></td>';
    $w_html.=$crlf.'          </table>';
    // Informações adicionais
    if (Nvl(f($RSM,'descricao'),'')>'') $w_html.=$crlf.'      <tr><td valign="top">Resultados esperados:<br><b>'.CRLF2BR(f($RSM,'descricao')).' </b></td>';
    $w_html.=$crlf.'    </table>';
    $w_html.=$crlf.'</tr>';
    // Dados da conclusão do programa, se ele estiver nessa situação
    if (f($RSM,'concluida')=='S' && Nvl(f($RSM,'data_conclusao'),'')>'') {
      $w_html.=$crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>DADOS DA CONCLUSÃO</td>';
      $w_html.=$crlf.'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html.=$crlf.'          <tr valign="top">';
      $w_html.=$crlf.'          <td>Início da execução:<br><b>'.$FormataDataEdicao[f($RSM,'inicio_real')].' </b></td>';
      $w_html.=$crlf.'          <td>Término da execução:<br><b>'.$FormataDataEdicao[f($RSM,'fim_real')].' </b></td>';
      $w_html.=$crlf.'          </table>';
      $w_html.=$crlf.'      <tr><td valign="top">Nota de conclusão:<br><b>'.CRLF2BR(f($RSM,'nota_conclusao')).' </b></td>';
    } 
    if ($p_tipo==2) {
      // Se for tramitação
      // Encaminhamentos
      $RS = db_getSolicLog::getInstanceOf($dbms,$p_solic,null,'LISTA');
      $RS = SortArray($RS,'phpdt_data','desc','despacho','desc');
      foreach ($RS as $row) { $RS = $row; if(strpos(f($row,'despacho'),'*** Nova versão')===false) break; }
      $w_html.=$crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>ÚLTIMO ENCAMINHAMENTO</td>';
      $w_html.=$crlf.'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html.=$crlf.'          <tr valign="top">';
      $w_html.=$crlf.'          <td>De:<br><b>'.f($RS,'responsavel').'</b></td>';
      $w_html.=$crlf.'          <td>Para:<br><b>'.f($RS,'destinatario').'</b></td>';
      $w_html.=$crlf.'          <tr valign="top"><td colspan=2>Despacho:<br><b>'.CRLF2BR(Nvl(f($RS,'despacho'),'---')).' </b></td>';
      $w_html.=$crlf.'          </table>';
      // Configura o destinatário da tramitação como destinatário da mensagem
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,nvl(f($RS,'sq_pessoa_destinatario'),0),null,null);
      $w_destinatarios = f($RS,'email').'|'.f($RS,'nome').'; ';
    } 
    $w_html.=$crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>OUTRAS INFORMAÇÕES</td>';
    $RS = db_getCustomerSite::getInstanceOf($dbms,$w_cliente);
    $w_html.='      <tr valign="top"><td><font size=2>'.$crlf;
    $w_html.='         Para acessar o sistema use o endereço: <b><a class="SS" href="'.f($RS,'logradouro').'" target="_blank">'.f($RS,'Logradouro').'</a></b></li>'.$crlf;
    $w_html.='      </font></td></tr>'.$crlf;
    $w_html.='      <tr valign="top"><td><font size=2>'.$crlf;
    $w_html.='         Dados da ocorrência:<br>'.$crlf;
    $w_html.='         <ul>'.$crlf;
    $w_html.='         <li>Responsável: <b>'.$_SESSION['NOME'].'</b></li>'.$crlf;
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
      // Recupera o e-mail do responsável
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,f($RSM,'solicitante'),null,null);
      $w_destinatarios .= f($RS,'email').'|'.f($RS,'nome').'; ';
    }
    // Recupera o e-mail do titular e do substituto pelo setor responsável
    $RS = db_getUorgResp::getInstanceOf($dbms,f($RSM,'sq_unidade'));
    foreach($RS as $row){$RS=$row; break;}
    if(f($RS,'st_titular')=='S')    $w_destinatarios .= f($RS,'email_titular').'|'.f($RS,'nm_titular').'; ';
    if(f($RS,'st_substituto')=='S') $w_destinatarios .= f($RS,'email_substituto').'|'.f($RS,'nm_substituto').'; ';
    // Recuperar o e-mail dos interessados
    $RS = db_getSolicInter::getInstanceOf($dbms,$p_solic,null,'LISTA');
    foreach($RS as $row) {
      if(f($row,'ativo')=='S' && f($row,'envia_email') =='S') $w_destinatarios .= f($row,'email').'|'.f($row,'nome').'; ';
    }  
    // Prepara os dados necessários ao envio
    if ($p_tipo==1 || $p_tipo==3) {
      // Inclusão ou Conclusão
      if ($p_tipo==1) $w_assunto = 'Inclusão - '.$w_nome; else $w_assunto = 'Conclusão - '.$w_nome;
    } elseif ($p_tipo==2) {
      // Tramitação
      $w_assunto = 'Tramitação - '.$w_nome;
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
// Rotina de busca de assuntos
// -------------------------------------------------------------------------
function BuscaAssunto() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_ano        = $_REQUEST['w_ano'];
  $w_nome       = strtoupper($_REQUEST['w_nome']);
  $w_codigo     = strtoupper($_REQUEST['w_codigo']);
  $w_cliente    = $_REQUEST['w_cliente'];
  $chaveaux     = $_REQUEST['chaveaux'];
  $restricao    = $_REQUEST['restricao'];
  $campo        = $_REQUEST['campo'];

  $RS = db_getAssunto_PA::getInstanceOf($dbms,$w_cliente,$chave,null,$w_codigo,$w_nome,null,null,null,null,'S','BUSCA');
  $RS = SortArray($RS,'provisorio','desc', 'codigo','asc', 'descricao', 'asc');
  Cabecalho();
  ShowHTML('<TITLE>Seleção de assunto</TITLE>');
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  ScriptOpen('JavaScript');
  ShowHTML('  function volta(l_codigo, l_nome, l_chave) {');
  ShowHTML("     opener.document.Form.".$campo."_nm.value=l_codigo + ' - ' + l_nome;");
  ShowHTML('     opener.document.Form.'.$campo.'.value=l_chave;');
  ShowHTML('     opener.document.Form.'.$campo.'_nm.focus();');
  ShowHTML('     window.close();');
  ShowHTML('     opener.focus();');
  ShowHTML('   }');
  if (count($RS)>100 || ($w_nome>'' || $w_codigo>'')) {
    ValidateOpen('Validacao');
    Validate('w_nome','Nome','1','','4','30','1','1');
    Validate('w_codigo','codigo','1','','2','10','1','1');
    ShowHTML('  if (theForm.w_nome.value == \'\' && theForm.w_codigo.value == \'\') {');
    ShowHTML('     alert (\'Informe um valor para o nome ou para o código!\');');
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
  if (count($RS)>100 || ($w_nome>'' || $w_codigo>'')) {
    BodyOpen('onLoad=\'document.Form.w_nome.focus();\'');
  } else {
    BodyOpen('onLoad=this.focus();');
  } 
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="100%" border="0">');
  if (count($RS)>100 || ($w_nome>'' || $w_codigo>'')) {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this))',null,$P1,$P2,$P3,$P4,$TP,$SG,null,null);
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="chaveaux" value="'.$chaveaux.'">');
    ShowHTML('<INPUT type="hidden" name="restricao" value="'.$restricao.'">');
    ShowHTML('<INPUT type="hidden" name="campo" value="'.$campo.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><b><ul>Instruções</b>:<li>Informe parte do nome do assunto ou o código.<li>Quando a relação for exibida, selecione o assunto desejada clicando sobre a palavra <i>"Selecionar"</i> ao seu lado.<li>Após informar o nome da unidade, clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Cancelar</i>, a procura é cancelada.</ul></div>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td valign="top"><b>Parte da <U>d</U>escrição do assunto:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="w_nome" size="30" maxlength="30" value="'.$w_nome.'">');
    ShowHTML('      <tr><td valign="top"><b>Parte do <U>C</U>ódigo:<br><INPUT ACCESSKEY="S" '.$w_Disabled.' class="sti" type="text" name="w_codigo" size="10" maxlength="10" value="'.$w_codigo.'">');
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
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>Não foram encontrados registros.</b></td></tr>');
      } else {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td>');
        ShowHTML('        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('            <td><b>Código</td>');
        ShowHTML('            <td><b>Descrição</td>');
        ShowHTML('            <td><b>Detalhamento</td>');
        ShowHTML('            <td><b>Observação</td>');
        ShowHTML('            <td><b>Operações</td>');
        ShowHTML('          </tr>');
        foreach($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('            <td width="1%" nowrap>'.f($row,'codigo').'</td>');
          ShowHTML('            <td>');
          ShowHTML('                '.f($row,'descricao'));
          if (nvl(f($row,'ds_assunto_pai'),'')!='') { 
            echo '<br>';
            if (nvl(f($row,'ds_assunto_bis'),'')!='') ShowHTML(strtolower(f($row,'ds_assunto_bis')).' &rarr; ');
            if (nvl(f($row,'ds_assunto_avo'),'')!='') ShowHTML(strtolower(f($row,'ds_assunto_avo')).' &rarr; ');
            if (nvl(f($row,'ds_assunto_pai'),'')!='') ShowHTML(strtolower(f($row,'ds_assunto_pai')));
          }
          ShowHTML('            </td>');
          ShowHTML('            <td>'.nvl(strtolower(f($row,'detalhamento')),'---').'</td>');
          ShowHTML('            <td>'.nvl(f($row,'observacao'),'---').'</td>');
          ShowHTML('            <td><a class="ss" href="#" onClick="javascript:volta(\''.f($row,'codigo').'\', \''.f($row,'descricao').'\', '.f($row,'chave').');">Selecionar</a>');
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
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td>');
        ShowHTML('        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('            <td><b>Código</td>');
        ShowHTML('            <td><b>Descrição</td>');
        ShowHTML('            <td><b>Detalhamento</td>');
        ShowHTML('            <td><b>Observação</td>');
        ShowHTML('            <td><b>Operações</td>');
        ShowHTML('          </tr>');
        foreach($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('            <td width="1%" nowrap>'.f($row,'codigo').'</td>');
          ShowHTML('            <td>');
          ShowHTML('                '.f($row,'descricao'));
          if (nvl(f($row,'ds_assunto_pai'),'')!='') { 
            echo '<br>';
            if (nvl(f($row,'ds_assunto_bis'),'')!='') ShowHTML(strtolower(f($row,'ds_assunto_bis')).' &rarr; ');
            if (nvl(f($row,'ds_assunto_avo'),'')!='') ShowHTML(strtolower(f($row,'ds_assunto_avo')).' &rarr; ');
            if (nvl(f($row,'ds_assunto_pai'),'')!='') ShowHTML(strtolower(f($row,'ds_assunto_pai')));
          }
          ShowHTML('            </td>');
          ShowHTML('            <td>'.nvl(strtolower(f($row,'detalhamento')),'---').'</td>');
          ShowHTML('            <td>'.nvl(f($row,'observacao'),'---').'</td>');
          ShowHTML('            <td><a class="ss" href="#" onClick="javascript:volta(\''.f($row,'codigo').'\', \''.f($row,'descricao').'\', '.f($row,'chave').');">Selecionar</a>');
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
// Executa a tramitação de protocolos
// -------------------------------------------------------------------------
function Tramitacao() {
  extract($GLOBALS);
  global $w_Disabled;

  // Recupera as variáveis utilizadas na filtragem
  $p_protocolo    = $_REQUEST['p_protocolo'];
  $p_chave        = explodeArray($_REQUEST['p_chave']);
  $p_chave_aux    = $_REQUEST['p_chave_aux'];
  $p_prefixo      = substr($p_protocolo,0,5);
  $p_numero       = substr($p_protocolo,6,6);
  $p_ano          = substr($p_protocolo,13,4);
  $p_unid_autua   = $_REQUEST['p_unid_autua'];
  $p_unid_posse   = $_REQUEST['p_unid_posse'];
  $p_nu_guia      = $_REQUEST['p_nu_guia'];
  $p_ano_guia     = $_REQUEST['p_ano_guia'];
  $p_ini          = $_REQUEST['p_ini'];
  $p_fim          = $_REQUEST['p_fim'];

  if ($w_troca>'') {
    // Se for recarga da página
    $w_chave            = $_REQUEST['w_chave'];
    $w_retorno_limite   = $_REQUEST['w_retorno_limite'];
    $w_interno          = $_REQUEST['w_interno'];
    $w_sq_unidade       = $_REQUEST['w_sq_unidade'];
    $w_pessoa_destino   = $_REQUEST['w_pessoa_destino'];
    $w_unidade_externa  = $_REQUEST['w_unidade_externa'];
    $w_tipo_despacho    = $_REQUEST['w_tipo_despacho'];
    $w_despacho         = $_REQUEST['w_despacho'];
    $w_aviso            = $_REQUEST['w_aviso'];
    $w_dias             = $_REQUEST['w_dias'];
  }

  if ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getProtocolo::getInstanceOf($dbms, f($RS_Menu,'sq_menu'), $w_usuario, $SG, $p_chave, $p_chave_aux, 
        $p_prefixo, $p_numero, $p_ano, $p_unid_autua, $p_unid_posse, $p_nu_guia, $p_ano_guia, $p_ini, $p_fim, 2);
    $RS = SortArray($RS,'prefixo','asc', 'ano','desc','numero_documento','asc');
    
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
  ShowHTML('<HEAD>');
  if ($O=='P') {
    ScriptOpen('JavaScript');
    FormataProtocolo();
    FormataData();
    SaltaCampo();
    CheckBranco();
    ValidateOpen('Validacao');
    Validate('p_protocolo','Número de protocolo','1','','20','20','','0123456789./-'); 
    Validate('p_ini','Início','DATA','','10','10','','0123456789/');
    Validate('p_fim','Término','DATA','','10','10','','0123456789/');
    ShowHTML('  if ((theForm.p_ini.value != \'\' && theForm.p_fim.value == \'\') || (theForm.p_ini.value == \'\' && theForm.p_fim.value != \'\')) {');
    ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
    ShowHTML('     theForm.p_ini.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    CompData('p_ini','Início','<=','p_fim','Término');
    ShowHTML('  theForm.Botao.disabled=true;');
    ValidateClose();
    ScriptClose();
  } else {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    Validate('w_retorno_limite','Prazo de resposta','DATA','',10,10,'','0123456789/');
    CompData('w_retorno_limite','Prazo de resposta','>=',FormataDataEdicao(time()),'data atual');
    Validate('w_dias','Dias para encaminhamento','1','',1,3,'','0123456789');
    ShowHTML('  if (theForm.w_aviso[0].checked) {');
    ShowHTML('     if (theForm.w_dias.value == \'\') {');
    ShowHTML('        alert(\'Informe a partir de quantos dias após o envio você deseja ser avisado!\');');
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
    Validate('w_despacho','Detalhamento do despacho','','1','1','2000','1','1');
    ShowHTML('  var i; ');
    ShowHTML('  var w_erro=true; ');
    ShowHTML('  if (theForm["w_chave[]"].value==undefined) {');
    ShowHTML('     for (i=0; i < theForm["w_chave[]"].length; i++) {');
    ShowHTML('       if (theForm["w_chave[]"][i].checked) w_erro=false;');
    ShowHTML('     }');
    ShowHTML('  } else {');
    ShowHTML('     if (theForm["w_chave[]"].checked) w_erro=false;');
    ShowHTML('  }');
    ShowHTML('  if (w_erro) {');
    ShowHTML('    alert(\'Você deve informar pelo menos um protocolo!\'); ');
    ShowHTML('    return false;');
    ShowHTML('  }');
    Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    ShowHTML('  if (!confirm(\'Confirma a geração de guia de tramitação APENAS para os documentos selecionados?\')) return false;');
    ShowHTML('  theForm.Botao.disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='P') {
    BodyOpen('onLoad=\'document.Form.p_protocolo.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td colspan=3 bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">');
    ShowHTML('  ATENÇÃO:<ul>');
    ShowHTML('  <li>Preencha primeiro os dados do trâmite. Em seguida selecione os protocolos que devem fazer parte da guia de tramitação.');
    ShowHTML('  <li>Se o trâmite for para pessoa jurídica, não se esqueça de informar para qual unidade dessa entidade você está enviando.');
    ShowHTML('  <li>Informe sua assinatura eletrônica e clique sobre o botão <i>Gerar Guia de Tramitação</i>.');
    ShowHTML('  </ul></b></font></td>');
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
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
    ShowHTML('          <td rowspan=2><b>Tipo</td>');
    ShowHTML('          <td rowspan=2><b>Protocolo</td>');
    ShowHTML('          <td colspan=4><b>Documento original</td>');
    ShowHTML('          <td rowspan=2><b>Limite</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Espécie</td>');
    ShowHTML('          <td><b>Nº</td>');
    ShowHTML('          <td><b>Data</td>');
    ShowHTML('          <td><b>Origem</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) { 
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $w_atual = '';
      AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
      ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
      ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
      ShowHTML('<INPUT type="hidden" name="w_unidade_posse" value="'.f($RS_Solic,'unidade_int_posse').'">');
      ShowHTML('<INPUT type="hidden" name="w_pessoa_posse" value="'.f($RS_Solic,'pessoa_ext_posse').'">');
      $i = 0;
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'">');
        ShowHTML('        <td align="center">'); 
        ShowHTML('          <INPUT type="hidden" name="w_tramite['.f($row,'sq_siw_solicitacao').']" value="'.f($row,'sq_siw_tramite').'">'); 
        ShowHTML('          <INPUT type="hidden" name="w_unid_origem['.f($row,'sq_siw_solicitacao').']" value="'.f($row,'unidade_int_posse').'">'); 
        ShowHTML('          <INPUT type="hidden" name="w_unid_autua['.f($row,'sq_siw_solicitacao').']" value="'.f($row,'unidade_autuacao').'">'); 
        if (nvl($w_marcado[f($row,'sq_siw_solicitacao')],'')!='') {
          ShowHTML('          <input type="CHECKBOX" CHECKED name="w_chave[]" value="'.f($row,'sq_siw_solicitacao').'" ></td>'); 
        } else {
          ShowHTML('          <input type="CHECKBOX" name="w_chave[]" value="'.f($row,'sq_siw_solicitacao').'" ></td>'); 
        }
        ShowHTML('        </td>');
        ShowHTML('        <td align="center">'.f($row,'nm_tipo').'</td>');
        ShowHTML('        <td align="center"><A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" target="visualdoc" title="Exibe as informações deste registro.">'.f($row,'protocolo').'&nbsp;</a>');
        ShowHTML('        <td>'.f($row,'nm_especie').'</td>');
        ShowHTML('        <td>'.f($row,'numero_original').'</td>');
        ShowHTML('        <td align="center">'.date(d.'/'.m.'/'.y,f($row,'inicio')).'</td>');
        ShowHTML('        <td>'.f($row,'nm_origem_doc').'</td>');
        ShowHTML('        <td align="center">'.((nvl(f($row,'fim'),'')!='') ? date(d.'/'.m.'/'.y,f($row,'fim')) : '&nbsp;').'</td>');
        ShowHTML('      </tr>');
        $i += 1;
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('      <tr><td colspan="3">&nbsp;</td></tr>');
    ShowHTML('      <tr><td colspan="3"  bgcolor="#f0f0f0" align=justify><font size="2"><b>NOVO TRÂMITE</b></font></td></tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('           <td title="Informe a data limite para que o destinatário encaminhe o documento."><b>Praz<u>o</u> de resposta:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_retorno_limite" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_retorno_limite.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_retorno_limite').'</td>');
    MontaRadioNS('<b>Emite alerta de não encaminhamento?</b>',$w_aviso,'w_aviso');
    ShowHTML('           <td valign="top"><b>Quantos <U>d</U>ias após esta tramitação?<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="w_dias" size="3" maxlength="3" value="'.$w_dias.'" title="A partir de quantos dias após este encaminhamento o sistema deve emitir o alerta."></td>');
    ShowHTML('      <tr><td>&nbsp;</td></br>');
    ShowHTML('      <tr><td colspan="3"  bgcolor="#f0f0f0" align=justify><font size="2"><b>DESTINO</b></font></td></tr>');
    ShowHTML('      <tr valign="top">');
    selecaoOrigem('<u>T</u>ipo da unidade/pessoa:','T','Indique se a unidade ou pessoa é interna ou externa.',$w_interno,null,'w_interno',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_interno\'; document.Form.submit();"');
    if ($w_interno=='N') {
      SelecaoPessoaOrigem('<u>P</u>essoa de destino:','P','Clique na lupa para selecionar a pessoa de destino.',$w_pessoa_destino,null,'w_pessoa_destino',null,null,null);
      ShowHTML('        <td><b>U<U>n</U>idade externa: (preencher apenas para pessoas jurídicas)<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="w_unidade_externa" size="30" maxlength="60" value="'.$w_unidade_externa.'"></td>');
    } else {
      ShowHTML('          <td colspan=2><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
      SelecaoUnidade('<U>U</U>nidade de destino:','U','Selecione a unidade de destino.',$w_sq_unidade,null,'w_sq_unidade','MOD_PA',null);
      ShowHTML('           </table>');
    }
    ShowHTML('      <tr><td colspan=3><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
    selecaoTipoDespacho('Des<u>p</u>acho:','P','Selecione o despacho desejado.',$w_cliente,$w_tipo_despacho,null,'w_tipo_despacho','SELECAO',null);
    ShowHTML('           </table>');
    ShowHTML('    <tr><td valign="top" colspan=3><b>Detalhamento do d<u>e</u>spacho:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Detalhe a ação a ser executada pelo destinatário.">'.$w_despacho.'</TEXTAREA></td>');
    ShowHTML('    <tr><td colspan=3><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('   <tr><td align="center" colspan=3><hr>');
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Gerar Guia de Tramitação">');
    ShowHTML('</FORM>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif ($O=='P') {
    ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Informe quaisquer critérios de busca e clique sobre o botão <i>Aplicar filtro</i>.');
    ShowHTML('  <li>Para pesquisa por período é obrigatório informar as datas de início e término.');
    ShowHTML('  <li>Clicando sobre o botao <i>Aplicar filtro</i> sem informar nenhum critério de busca, serão exibidas todas as guias que você tem acesso.');
    ShowHTML('  </ul></b></font></td>');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><b><u>P</u>rotocolo:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="p_protocolo" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$p_protocolo.'" onKeyDown="FormataProtocolo(this,event);"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade que detém a posse do protocolo:','U','Selecione a unidade de posse.',$p_unid_posse,null,'p_unid_posse','MOD_PA',null);
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Perío<u>d</u>o entre:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="p_ini" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
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
    ShowHTML(' alert(\'Opção não disponível\');');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Acusa o recebimento de guias de tramitação
// -------------------------------------------------------------------------
function Recebimento() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_unid_autua   = $_REQUEST['w_unid_autua'];
  $w_nu_guia      = $_REQUEST['w_nu_guia'];
  $w_ano_guia     = $_REQUEST['w_ano_guia'];

  if ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getProtocolo::getInstanceOf($dbms, f($RS_Menu,'sq_menu'), $w_usuario, $SG, null, null, 
        null, null, null, null, null, null, null, null, null, null);
    $RS = SortArray($RS,'sg_unidade','asc', 'ano_guia','desc','nu_guia','asc','protocolo','asc');
  } elseif ($O=='R') {
    // Recupera os protocolos da guia
    $RS_Dados = db_getProtocolo::getInstanceOf($dbms, $w_menu, $w_usuario, $SG, null, null, 
        null, null, null, $w_unid_autua, null, $w_nu_guia, $w_ano_guia, null, null, 2);
  }

  Cabecalho();
  ShowHTML('<HEAD>');
  if ($O=='R') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='R') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Selecione a guia desejada para recebimento, clicando sobre a operação <i>Receber</i>.');
    ShowHTML('  </ul></b></font></td>');
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
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
    ShowHTML('          <td><b>Guia</td>');
    ShowHTML('          <td><b>Origem</td>');
    ShowHTML('          <td><b>Despacho</td>');
    ShowHTML('          <td><b>Protocolo</td>');
    ShowHTML('          <td><b>Envio</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) { 
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      $w_atual = '';
      foreach ($RS1 as $row) {
        if ($w_atual=='' || $w_atual!=f($row,'guia_tramite')) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('        <td>'.f($row,'guia_tramite').'</td>');
          ShowHTML('        <td>'.f($row,'nm_unid_origem').'</td>');
          ShowHTML('        <td>'.f($row,'nm_despacho').'</td>');
          ShowHTML('        <td align="center"><A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" target="visualdoc" title="Exibe as informações deste registro.">'.f($row,'protocolo').'&nbsp;</a>');
          ShowHTML('        <td align="center">'.formataDataEdicao(f($row,'phpdt_envio'),3).'</td>');
          ShowHTML('        <td align="top" nowrap>');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=R&w_unid_autua='.f($row,'unidade_autuacao').'&w_nu_guia='.f($row,'nu_guia').'&w_ano_guia='.f($row,'ano_guia').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Receber</A>&nbsp');
          ShowHTML('        </td>');
          ShowHTML('      </tr>');
        $w_atual = f($row,'guia_tramite');
        } else {
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('        <td align="center"><A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" target="visualdoc" title="Exibe as informações deste registro.">'.f($row,'protocolo').'&nbsp;</a>');
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
    // Chama a rotina de visualização dos protocolos da guia
    ShowHTML(VisualGR($w_unid_autua, $w_nu_guia, $w_ano_guia, f($RS_Menu,'sq_menu'), 'TELA'));
    ShowHTML('<HR>');
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_unid_autua" value="'.$w_unid_autua.'">');
    ShowHTML('<INPUT type="hidden" name="w_nu_guia" value="'.$w_nu_guia.'">');
    ShowHTML('<INPUT type="hidden" name="w_ano_guia" value="'.$w_ano_guia.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr><td colspan=3 bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">');
    ShowHTML('  ATENÇÃO:<ul>');
    ShowHTML('  <li>Verifique cada um dos protocolos antes de assinar o recebimento, pois não será possível reverter esta ação.');
    ShowHTML('  <li>O recebimento da guia implica no recebimento de todos os seus protocolos, não sendo possível o recebimento parcial.');
    ShowHTML('  </ul></b></font></td>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('  <table width="97%" border="0">');
    ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('    <tr><td align="center" colspan=4><hr>');
    ShowHTML('      <input class="STB" type="submit" name="Botao" value="Receber">');
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    ShowHTML('      </td>');
    ShowHTML('    </tr>');
    ShowHTML('  </table>');
    ShowHTML('  </TD>');
    ShowHTML('</tr>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Procedimento que executa as operações de BD
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
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if ($O=='E') {
        $RS = db_getSolicLog::getInstanceOf($dbms,$_REQUEST['w_chave'],null,'LISTA');
        // Mais de um registro de log significa que deve ser cancelada, e não excluída.
        // Nessa situação, não é necessário excluir os arquivos.
        if (count($RS)<=1) {
          $RS = db_getSolicAnexo::getInstanceOf($dbms,$_REQUEST['w_chave'],null,$w_cliente);
          foreach($RS as $row) { {
            if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));} 
          } 
        }
      } 
      dml_putDocumentoGeral::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$w_copia,$_REQUEST['w_menu'],
          nvl($_REQUEST['w_sq_unidade'],$_SESSION['LOTACAO']), $_SESSION['LOTACAO'],
          nvl($_REQUEST['w_pessoa_origem'],$_SESSION['SQ_PESSOA']),$_SESSION['SQ_PESSOA'],$_REQUEST['w_solic_pai'],
          $_REQUEST['w_codigo_interno'],$_REQUEST['w_processo'],$_REQUEST['w_circular'],$_REQUEST['w_especie_documento'],
          $_REQUEST['w_doc_original'],$_REQUEST['w_data_documento'],$_REQUEST['w_volumes'],$_REQUEST['w_copias'],
          $_REQUEST['w_natureza_documento'],$_REQUEST['w_fim'],$_REQUEST['w_data_recebimento'],$_REQUEST['w_interno'],
          $_REQUEST['w_pessoa_origem'],$_REQUEST['w_pessoa_interes'],$_REQUEST['w_cidade'],$_REQUEST['w_assunto'],
          $_REQUEST['w_descricao'],&$w_chave_nova, &$w_codigo);

      ScriptOpen('JavaScript');
      if ($O=='I' || $_REQUEST['w_codigo']!=$_REQUEST['w_codigo_atual']) {
        // Exibe mensagem de gravação com sucesso
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
  } elseif ($SG=='PADOCANEXO') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      // Se foi feito o upload de um arquivo  
      if (UPLOAD_ERR_OK==0) {
        $w_maximo = $_REQUEST['w_upload_maximo'];
        foreach ($_FILES as $Chv => $Field) {
          if (!($Field['error']==UPLOAD_ERR_OK || $Field['error']==UPLOAD_ERR_NO_FILE)) {
            // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!\');');
            ScriptClose();
            retornaFormulario('w_caminho');
            exit();
          }
          if ($Field['size'] > 0) {
            // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
            if ($Field['size'] > $w_maximo) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!\');');
              ScriptClose();
              retornaFormulario('w_caminho');
              exit();
            } 
            // Se já há um nome para o arquivo, mantém 
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
        retornaFormulario('w_caminho');
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
  } elseif ($SG=='PAINTERESS') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putDocumentoInter::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_principal']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }  
  } elseif ($SG=='PADOCASS') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putDocumentoAssunto::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_principal']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }  
  } elseif (strpos($SG,'ENVIO')!==false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      // Se o destino for pessoa jurídica, pede unidade da pessoa
      if (nvl($_REQUEST['w_pessoa_destino'],'')!='') {
        $RS_Destino = db_getBenef::getInstanceOf($dbms,$w_cliente,$_REQUEST['w_pessoa_destino'],null,null,null,null,null,null,null,null,null,null,null,null);
        foreach ($RS_Destino as $row) { $RS_Destino = $row; break; }
        if (strtoupper(f($RS_Destino,'nm_tipo_pessoa'))=='JURÍDICA' && nvl($_REQUEST['w_unidade_externa'],'')=='') {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: Unidade externa é obrigatória quando o destino é uma pessoa jurídica!\');');
          ScriptClose();
          retornaFormulario('w_unidade_externa');
          exit;
        }
      }

      $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],f($RS_Menu,'sigla'));
      if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite'] || nvl(f($RS,'unidade_int_posse'),'')!=nvl($_REQUEST['w_unidade_posse'],'') || nvl(f($RS,'pessoa_ext_posse'),'')!=nvl($_REQUEST['w_pessoa_posse'],'')) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATENÇÃO: Outro usuário já tramitou este documento!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } else {
        dml_putDocumentoEnvio::getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
            $_REQUEST['w_interno'],$_REQUEST['w_unidade_posse'],$_REQUEST['w_sq_unidade'],$_REQUEST['w_pessoa_destino'],
            $_REQUEST['w_tipo_despacho'],$_REQUEST['w_despacho'],$_REQUEST['w_aviso'],$_REQUEST['w_dias'],
            $_REQUEST['w_retorno_limite'],$_REQUEST['w_pessoa_destino_nm'],$_REQUEST['w_unidade_externa'],
            &$w_nu_guia, &$w_ano_guia, &$w_unidade_autuacao);
        // Envia e-mail comunicando a tramitação
        // SolicMail($_REQUEST['w_chave'],2);
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
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }  
  } elseif (strpos($SG,'PADTRAM')!==false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      // Se o destino for pessoa jurídica, pede unidade da pessoa
      if (nvl($_REQUEST['w_pessoa_destino'],'')!='') {
        $RS_Destino = db_getBenef::getInstanceOf($dbms,$w_cliente,$_REQUEST['w_pessoa_destino'],null,null,null,null,null,null,null,null,null,null,null,null);
        foreach ($RS_Destino as $row) { $RS_Destino = $row; break; }
        if (strtoupper(f($RS_Destino,'nm_tipo_pessoa'))=='JURÍDICA' && nvl($_REQUEST['w_unidade_externa'],'')=='') {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: Unidade externa é obrigatória quando o destino é uma pessoa jurídica!\');');
          ScriptClose();
          retornaFormulario('w_unidade_externa');
          exit;
        }
      }

      for ($i=0; $i<=count($_POST['w_chave'])-1; $i=$i+1) {
        if (Nvl($_POST['w_chave'][$i],'')>'') {
          dml_putDocumentoEnvio::getInstanceOf($dbms,f($RS_Menu,'sq_menu'),$_POST['w_chave'][$i],$w_usuario,
              $_POST['w_tramite'][$_POST['w_chave'][$i]], $_REQUEST['w_interno'],
              $_POST['w_unid_origem'][$_POST['w_chave'][$i]], $_REQUEST['w_sq_unidade'],$_REQUEST['w_pessoa_destino'],
              $_REQUEST['w_tipo_despacho'],$_REQUEST['w_despacho'],$_REQUEST['w_aviso'],$_REQUEST['w_dias'],
              $_REQUEST['w_retorno_limite'],$_REQUEST['w_pessoa_destino_nm'],$_REQUEST['w_unidade_externa'],
              &$w_nu_guia, &$w_ano_guia, &$w_unidade_autuacao);
        } 
      }
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Tramitação realizada com sucesso!\\nImprima a guia de tramitação na próxima tela.\');');
      ShowHTML('  parent.menu.location=\''.montaURL_JS(null,$conRootSIW.'menu.php?par=ExibeDocs&O=L&R='.$R.'&SG=RELPATRAM&TP='.RemoveTP(RemoveTP($TP)).'&p_unidade='.$w_unidade_autuacao.'&p_nu_guia='.$w_nu_guia.'&p_ano_guia='.$w_ano_guia).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (strpos($SG,'RECEB')!==false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $RS = db_getProtocolo::getInstanceOf($dbms, $w_menu, $w_usuario, 'RECEBIDO', null, null, null, null, null, 
                $_REQUEST['w_unid_autua'], null, $_REQUEST['w_nu_guia'], $_REQUEST['w_ano_guia'], null, null, 2);
      if (count($RS)>0) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATENÇÃO: Outro usuário já recebeu esta guia!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } else {
        dml_putDocumentoReceb::getInstanceOf($dbms,$w_usuario,
            $_REQUEST['w_unid_autua'],$_REQUEST['w_nu_guia'],$_REQUEST['w_ano_guia']);

        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Protocolos da guia recebidos com sucesso!\');');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } 
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }  
  } elseif (strpos($SG,'CONC')!==false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],f($RS_Menu,'sigla'));
      if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite'] || nvl(f($RS,'unidade_int_posse'),'')!=nvl($_REQUEST['w_unidade_posse'],'') || nvl(f($RS,'pessoa_ext_posse'),'')!=nvl($_REQUEST['w_pessoa_posse'],'')) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATENÇÃO: Outro usuário já tramitou este documento!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } else {
        dml_putDocumentoConc::getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_inicio_real'],$_REQUEST['w_fim_real'],$_REQUEST['w_nota_conclusao'],$_REQUEST['w_custo_real']);
        // Envia e-mail comunicando a conclusão
        //SolicMail($_REQUEST['w_chave'],3);
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
    case 'RECEB':               Recebimento();                  break;
    case 'GRAVA':               Grava();                        break;
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