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
include_once($w_dir_volta.'classes/sp/db_getPrograma_IS.php');
include_once($w_dir_volta.'classes/sp/db_get10PercentDays_IS.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData_IS.php');
include_once($w_dir_volta.'classes/sp/db_getSolicIndic_IS.php');
include_once($w_dir_volta.'classes/sp/db_getRestricao_IS.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicInter.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteData.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getAcaoPPA_IS.php');
include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta.'classes/sp/db_getPPADadoFinanc_IS.php');
include_once($w_dir_volta.'classes/sp/db_getUorgResp.php');
include_once($w_dir_volta.'classes/sp/dml_putAcaoGeral_IS.php');
include_once($w_dir_volta.'classes/sp/dml_putRespPrograma_IS.php');
include_once($w_dir_volta.'classes/sp/dml_putProgQualitativa_IS.php');
include_once($w_dir_volta.'classes/sp/dml_putIndicador_IS.php');
include_once($w_dir_volta.'classes/sp/dml_putRestricao_IS.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoInter.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicArquivo.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoConc.php');
include_once($w_dir_volta.'funcoes/selecaoProgramaPPA.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade_IS.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoTPRestricao_IS.php');
include_once($w_dir_volta.'funcoes/selecaoFase.php');
include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');
include_once($w_dir_volta.'funcoes/selecaoNatureza_IS.php');
include_once($w_dir_volta.'funcoes/selecaoHorizonte_IS.php');
include_once($w_dir_volta.'funcoes/selecaoUniMedida_IS.php');
include_once($w_dir_volta.'funcoes/montaTipoIndicador.php');
include_once($w_dir_volta.'funcoes/selecaoPeriodicidade_IS.php');
include_once($w_dir_volta.'funcoes/selecaoBaseGeografica_IS.php');
include_once($w_dir_volta.'funcoes/selecaoProgramaIS.php');
include_once('visualprograma.php');
// =========================================================================
//  /programa.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho 
// Descricao: Gerencia o módulo de programas
// Mail     : celso@sbpi.com.br
// Criacao  : 09/08/2006 10:30
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
$w_pagina       = 'programa.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_is/';
$w_troca        = $_REQUEST['w_troca'];
$p_ordena       = strtolower($_REQUEST['p_ordena']);
$w_SG           = strtoupper($_REQUEST['w_SG']);
if ($SG=='ISPRINTERE' || $SG=='ISPRRESP' || $SG=='ISPRANEXO' || $SG=='ISPRINDIC' || $SG=='ISPRRESTR') {
  if ($O!='I' && $O!='E' && $_REQUEST['w_chave_aux']=='') $O='L';
} elseif ($SG=='ISPRENVIO') {
  $O='V';
} elseif ($O=='') {
  // Se for acompanhamento, entra na filtragem
    if ($P1==3) $O='P'; else $O='L';
} 
switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';  break;
  case 'A': $w_TP=$TP.' - Alteração'; break;
  case 'E': $w_TP=$TP.' - Exclusão';  break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  case 'C': $w_TP=$TP.' - Cópia';     break;
  case 'V': $w_TP=$TP.' - Envio';     break;
  case 'H': $w_TP=$TP.' - Herança';   break;
  default:
    if ($par=='BUSCAPROGRAMA') $w_TP=$TP.' - Busca programa'; else $w_TP=$TP.' - Listagem';
  break;
} 
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_ano      = RetornaAno();
$w_copia        = $_REQUEST['w_copia'];
$p_programa     = strtoupper($_REQUEST['p_programa']);
$p_atividade    = strtoupper($_REQUEST['p_atividade']);
$p_ativo        = strtoupper($_REQUEST['p_ativo']);
$p_solicitante  = strtoupper($_REQUEST['p_solicitante']);
$p_prioridade   = strtoupper($_REQUEST['p_prioridade']);
$p_unidade      = strtoupper($_REQUEST['p_unidade']);
$p_proponente   = strtoupper($_REQUEST['p_proponente']);
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
$p_palavra      = strtoupper($_REQUEST['p_palavra']);
$p_prazo        = strtoupper($_REQUEST['p_prazo']);
$p_fase         = explodeArray($_REQUEST['p_fase']);
$p_cd_programa  = strtoupper($_REQUEST['p_cd_programa']);
$p_qtd_restricao = strtoupper($_REQUEST['p_qtd_restricao']);
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
  if ($O=='L') {
    if (!(strpos(strtoupper($R),'GR_')===false)) {
      $w_filtro='';
      if ($p_programa>'') {
        $RS = db_getSolicData_IS::getInstanceOf($dbms,$p_programa,'ISPRGERAL');
        foreach($RS as $row){$RS=$row; break;}
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Ação <td>[<b>'.f($RS,'titulo').'</b>]';
      }
      if ($p_cd_programa>'') {
        $RS = db_getProgramaPPA_IS::getInstanceOf($dbms,$p_cd_programa,$w_cliente,$w_ano,null,null,null,null);
        foreach($RS as $row){$RS=$row; break;}
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Programa PPA <td>[<b>'.f($RS,'ds_programa').' ('.f($RS,'cd_programa').')'.'</b>]';
      }
      if ($p_prazo>'') $w_filtro=$w_filtro.' <tr valign="top"><td align="right">Prazo para conclusão até<td>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]';
      if ($p_solicitante>'') {
        $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Responsável <td>[<b>'.f($RS,'nome_resumido').'</b>]';
      }
      if ($p_unidade>'') {
        $RS = db_getUorgData::getInstanceOf($dbms,$p_unidade);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Unidade responsável <td>[<b>'.f($RS,'nome').'</b>]';
      }
      if ($p_usu_resp>'') {
        $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_usu_resp,null,null);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Executor <td>[<b>'.f($RS,'nome_resumido').'</b>]';
      } 
      if ($p_uorg_resp>'') {
        $RS = db_getUorgData::getInstanceOf($dbms,$p_uorg_resp);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Unidade atual <td>[<b>'.f($RS,'nome').'</b>]';
      }
      if ($p_proponente>'')       $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Parcerias externas<td>[<b>'.$p_proponente.'</b>]';
      if ($p_assunto>'')          $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Assunto <td>[<b>'.$p_assunto.'</b>]';
      if ($p_palavra>'')          $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Parcerias internas <td>[<b>'.$p_palavra.'</b>]';
      if ($p_ini_i>'')            $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Data recebimento <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
      if ($p_fim_i>'')            $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Limite conclusão <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]';
      if ($p_atraso=='S')         $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Situação <td>[<b>Apenas atrasadas</b>]';
      if ($p_qtd_restricao=='S')  $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Situação <td>[<b>Apenas programas com restrição</b>]';
      if ($w_filtro>'')           $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>';
    } 
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ISPCAD');
    if ($w_copia>'') {
      // Se for cópia, aplica o filtro sobre todas as demandas visíveis pelo usuário
       $RS = db_getSolicList_IS::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
              $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
              $p_unidade,$p_prioridade,$p_qtd_restricao,$p_proponente,
              $p_chave,$p_assunto,$p_pais,$p_regiao,$p_uf,$p_cidade,$p_usu_resp,
              $p_uorg_resp,$p_palavra,$p_prazo,$p_fase,$p_programa,$p_atividade,null,$p_cd_programa,null,null,$w_ano);
    } else {
      $RS = db_getSolicList_IS::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P1,
              $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
              $p_unidade,$p_prioridade,$p_qtd_restricao,$p_proponente,
              $p_chave,$p_assunto,$p_pais,$p_regiao,$p_uf,$p_cidade,$p_usu_resp,
              $p_uorg_resp,$p_palavra,$p_prazo,$p_fase,$p_programa,$p_atividade,null,$p_cd_programa,null,null,$w_ano);            
    } 
    if (Nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
     } else {
      $RS = SortArray($RS,'phpdt_fim','asc','prioridade','asc');
    }
  }
  Cabecalho();
  ShowHTML('<HEAD>');
  if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="300; URL=../'.MontaURL('MESA').'">');
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de ações</TITLE>');
  ScriptOpen('Javascript');
  CheckBranco();
  FormataData();
  ValidateOpen('Validacao');
  if (!(strpos('CP',$O)===false)) {
    if ($P1!=1 || $O=='C') {
      // Se não for cadastramento ou se for cópia
      Validate('p_prazo','Dias para a data limite','','','1','2','','0123456789');
      Validate('p_proponente','Proponente externo','','','2','90','1','');
      Validate('p_assunto','Assunto','','','2','90','1','1');
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
      BodyOpen('onLoad=\'document.Form.p_chave.focus()\';');
    } else {
      BodyOpen('onLoad=\'document.Form.p_ordena.focus()\';');
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
    ShowHTML('    <td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Programa','cd_programa').'</td>');
    ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Responsável','nm_solic').'</td>');
    if ($P1!=2) ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Usuário atual','cd_exec').'</td>');
    if ($P1==1 || $P1==2) {
      // Se for cadastramento ou mesa de trabalho
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Título','titulo').'</td>');
      ShowHTML('          <td colspan=2><b>Execução</td>');
    } else {
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Parcerias','proponente').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Título','titulo').'</td>');
      ShowHTML('          <td colspan=2><b>Execução</td>');
      ShowHTML('          <td rowspan=2><b>Valor</td>');
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
        ShowHTML('        <A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'cd_programa').'&nbsp;</a>');
        ShowHTML('        <td>'.ExibePessoa('../',$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_solic')).'</A></td>');
        if ($P1!=2) {
          // Se for mesa de trabalho, não exibe o executor, pois já é o usuário logado
          if (Nvl(f($row,'nm_exec'),'---')>'---') ShowHTML('        <td>'.ExibePessoa('../',$w_cliente,f($row,'executor'),$TP,f($row,'nm_exec')).'</td>');
          else                                    ShowHTML('        <td>---</td>');
        } 
        if ($P1!=1 && $P1!=2) {
          // Se não for cadastramento nem mesa de trabalho
          ShowHTML('        <td>'.Nvl(f($row,'proponente'),'---').'</td>');
        }
        // Verifica se foi enviado o parâmetro p_tamanho = N. Se chegou, o assunto deve ser exibido sem corte.
        // Este parâmetro é enviado pela tela de filtragem das páginas gerenciais
        if ($_REQUEST['p_tamanho']=='N') {
          ShowHTML('        <td>'.Nvl(f($row,'titulo'),'-').'</td>');
        } else {
          if (strlen(Nvl(f($row,'titulo'),'-'))>50) $w_titulo=substr(Nvl(f($row,'titulo'),'-'),0,50).'...'; 
          else                                      $w_titulo=Nvl(f($row,'titulo'),'-');
          if (f($row,'sg_tramite')=='CA') ShowHTML('        <td title="'.str_replace('\r\n','\n',str_replace('""','\\\'',str_replace('\'','\\\'',f($row,'titulo')))).'"><strike>'.$w_titulo.'</strike></td>');
          else                            ShowHTML('        <td title="'.str_replace('\r\n','\n',str_replace('""','\\\'',str_replace('\'','\\\'',f($row,'titulo')))).'">'.$w_titulo.'</td>');
        } 
        ShowHTML('        <td align="center">&nbsp;'.FormataDataEdicao(f($row,'inicio')).'</td>');
        ShowHTML('        <td align="center">&nbsp;'.FormataDataEdicao(f($row,'fim')).'</td>');
        if ($P1!=1 && $P1!=2) {
          // Se não for cadastramento nem mesa de trabalho
          if (f($row,'sg_tramite')=='AT') {
            ShowHTML('        <td align="right">'.number_format(f($row,'custo_real'),2,',','.').'&nbsp;</td>');
            $w_parcial = $w_parcial + f($row,'custo_real');
          } else {
            ShowHTML('        <td align="right">'.number_format(f($row,'valor'),2,',','.').'&nbsp;</td>');
            $w_parcial = $w_parcial + f($row,'valor');
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
            if ($w_submenu>'') ShowHTML('          <A class="HL" HREF="menu.php?par=ExibeDocs&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&R='.$w_pagina.$par.'&SG='.$SG.'&TP='.$TP.'&w_documento=Nr. '.f($row,'sq_siw_solicitacao').MontaFiltro('GET').'" title="Altera as informações cadastrais do programa" TARGET="menu">Alterar</a>&nbsp;');
            else               ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informações cadastrais do programa">Alterar</A>&nbsp');
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclusão de programa.">Excluir</A>&nbsp');
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Encaminhamento do programa.">Enviar</A>&nbsp');
          } elseif ($P1==2 || $P1==6) {
            // Se for execução
            if ($w_usuario==f($row,'executor')) {
              if (Nvl(f($row,'solicitante'),0) == $w_usuario || 
                  Nvl(f($row,'titular'),0)     == $w_usuario || 
                  Nvl(f($row,'substituto'),0)  == $w_usuario || 
                  Nvl(f($row,'tit_exec'),0)    == $w_usuario || 
                  Nvl(f($row,'executor'),0)    == $w_usuario || 
                  Nvl(f($row,'subst_exec'),0)  == $w_usuario) {
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Indicador&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.'ISPRINDIC'.MontaFiltro('GET').'" title="Atualiza os indicadores do programa" target="Indicadores">Ind</A>&nbsp');
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Restricao&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.'ISPRRESTR'.MontaFiltro('GET').'" title="Atualiza as restricoes do programa" target="Restricoes">Rest</A>&nbsp');
              } 
              // Coloca as operações dependendo do trâmite
              if (f($row,'sg_tramite')=='EA') {
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anotações para a ação, sem enviá-la.">Anotar</A>&nbsp');
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a ação para outro responsável.">Enviar</A>&nbsp');
              } elseif (f($row,'sg_tramite')=='EE') {
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anotações para a ação, sem enviá-la.">Anotar</A>&nbsp');
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a ação para outro responsável.">Enviar</A>&nbsp');
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Conclui a execução da ação.">Concluir</A>&nbsp');
              } 
            } else {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Indicador&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.'ISPRINDIC'.MontaFiltro('GET').'" title="Indicadores do programa." target="Indicadores">Ind</A>&nbsp');
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Restricao&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.'ISPRRESTR'.MontaFiltro('GET').'" title="Restricoes do programa." target="Restricoes">Rest</A>&nbsp');
              if (RetornaGestor(f($row,'sq_siw_solicitacao'),$w_usuario)=='S') {
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a ação para outro responsável.">Enviar</A>&nbsp');
              } else {
                ShowHTML('          ---&nbsp');
              }
            } 
          } 
        } else {
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Indicador&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.'ISPRINDIC'.MontaFiltro('GET').'" title="Indicadores do programa" target="Indicadores">Ind</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Restricao&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.'ISPRRESTR'.MontaFiltro('GET').'" title="Restricoes do programa" target="Restricoes">Rest</A>&nbsp');
          if (Nvl(f($row,'solicitante'),0) == $w_usuario || 
              Nvl(f($row,'titular'),0)     == $w_usuario || 
              Nvl(f($row,'substituto'),0)  == $w_usuario || 
              Nvl(f($row,'resp_etapa'),0)  >  0 || 
              Nvl(f($row,'tit_exec'),0)    == $w_usuario || 
              Nvl(f($row,'subst_exec'),0)  == $w_usuario) {
            // Se o usuário for responsável por uma ação, titular/substituto do setor responsável 
            // ou titular/substituto da unidade executora,
            // pode enviar.
            if (Nvl(f($row,'solicitante'),0)   == $w_usuario || 
                Nvl(f($row,'titular'),0)       == $w_usuario || 
                Nvl(f($row,'substituto'),0)    == $w_usuario || 
                Nvl(f($row,'tit_exec'),0)      == $w_usuario || 
                Nvl(f($row,'subst_exec'),0)    == $w_usuario) {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a ação para outro responsável.">Enviar</A>&nbsp');
            } 
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
          ShowHTML('          <td colspan=7 align="right"><b>Total desta página&nbsp;</td>');
          ShowHTML('          <td align="right"><b>'.number_format($w_parcial,2,',','.').'&nbsp;</td>');
          ShowHTML('          <td colspan=2>&nbsp;</td>');
          ShowHTML('        </tr>');
        } 
        // Se for a última página da listagem, soma e exibe o valor total
        if ($P3==ceil(count($RS)/$P4)) {
          foreach($RS as $row) {
            if (f($RS,'sg_tramite')=='AT') $w_total = $w_total + f($RS,'custo_real');
            else                           $w_total = $w_total + f($RS,'valor');
          } 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
          ShowHTML('          <td colspan=7 align="right"><b>Total da listagem&nbsp;</td>');
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
      // Recupera dados da opçãa açãos
      ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
      ShowHTML('      <tr>');
      $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ISPCAD');
      SelecaoProgramaPPA('Programa <u>P</u>PA:','P',null,$w_cliente,$w_ano,$p_cd_programa,'p_cd_programa',null,null,$w_menu,null,null);
      ShowHTML('      </tr>');
      ShowHTML('          </table>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td valign="top"><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="STI" type="text" name="p_prazo" size="2" maxlength="2" value="'.$p_prazo.'"></td>');
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Respo<u>n</u>sável:','N','Selecione o responsável pelo programa na relação.',$p_solicitante,null,'p_solicitante','USUARIOS');
      SelecaoUnidade('<U>S</U>etor responsável:','S',null,$p_unidade,null,'p_unidade',null,null);
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Responsável atua<u>l</u>:','L','Selecione o responsável atual pela ação na relação.',$p_usu_resp,null,'p_usu_resp','USUARIOS');
      SelecaoUnidade('<U>S</U>etor atual:','S','Selecione a unidade onde a ação se encontra na relação.',$p_uorg_resp,null,'p_uorg_resp',null,null);
      ShowHTML('          <td valign="top"><b>Propo<U>n</U>ente externo:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="'.$p_proponente.'"></td>');
      ShowHTML('      <tr>');
      ShowHTML('          <td valign="top"><b>A<U>s</U>sunto:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_assunto" size="25" maxlength="90" value="'.$p_assunto.'"></td>');
      ShowHTML('          <td valign="top" colspan=2><b>Pala<U>v</U>ras-chave:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_palavra" size="25" maxlength="90" value="'.$p_palavra.'"></td>');
      ShowHTML('      <tr>');
      ShowHTML('          <td valign="top"><b>Data de re<u>c</u>ebimento entre:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" title="Usar formato dd/mm/aaaa"></td>');
      ShowHTML('          <td valign="top"><b>Limi<u>t</u>e para conclusão entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" title="Usar formato dd/mm/aaaa"></td>');
      if ($O!='C') {
        // Se não for cópia
        ShowHTML('      <tr>');
        ShowHTML('          <td valign="top"><b>Exibe somente ações em atraso?</b><br>');
        if ($p_atraso=='S') ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S" checked> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N"> Não');
        else                ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N" checked> Não');
        SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase',null,null);
      } 
    }
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="STS" name="p_ordena" size="1">');
    if ($p_ordena=='ASSUNTO')       ShowHTML('          <option value="assunto" SELECTED>Assunto<option value="inicio">Data de recebimento<option value="">Data limite para conclusão<option value="nm_tramite">Fase atual<option value="proponente">Proponente externo');
    elseif ($p_ordena=='INICIO')    ShowHTML('          <option value="assunto">Assunto<option value="inicio" SELECTED>Data de recebimento<option value="">Data limite para conclusão<option value="nm_tramite">Fase atual<option value="proponente">Proponente externo');
    elseif ($p_ordena=='NM_TRAMITE')ShowHTML('          <option value="assunto">Assunto<option value="inicio">Data de recebimento<option value="">Data limite para conclusão<option value="nm_tramite" SELECTED>Fase atual<option value="proponente">Proponente externo');
    elseif ($p_ordena=='PRIORIDADE')ShowHTML('          <option value="assunto">Assunto<option value="inicio">Data de recebimento<option value="">Data limite para conclusão<option value="nm_tramite">Fase atual<option value="proponente">Proponente externo');
    elseif ($p_ordena=='PROPONENTE')ShowHTML('          <option value="assunto">Assunto<option value="inicio">Data de recebimento<option value="">Data limite para conclusão<option value="nm_tramite">Fase atual<option value="proponente" SELECTED>Proponente externo');
    else                            ShowHTML('          <option value="assunto">Assunto<option value="inicio">Data de recebimento<option value="" SELECTED>Data limite para conclusão<option value="nm_tramite">Fase atual<option value="prioridade">Proponente externo');
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
  $w_readonly   = '';
  $w_erro       = '';
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
    $w_cd_programa      = $_REQUEST['w_cd_programa'];
    $w_descricao        = $_REQUEST['w_descricao'];
    $w_justificativa    = $_REQUEST['w_justificativa'];
    $w_selecao_mp       = $_REQUEST['w_selecao_mp'];
    $w_selecao_se       = $_REQUEST['w_selecao_se'];
    $w_sq_natureza      = $_REQUEST['w_sq_natureza'];
    $w_sq_horizonte     = $_REQUEST['w_sq_horizonte'];
    $w_sq_unidade_adm   = $_REQUEST['w_sq_unidade_adm'];
    $w_ln_programa      = $_REQUEST['w_ln_programa'];
    if ($w_cd_programa>'') {
      $RS = db_getProgramaPPA_IS::getInstanceOf($dbms,$w_cd_programa,$w_cliente,$w_ano,null,null,null,null);
      foreach($RS as $row){$RS=$row; break;}
      $w_titulo = f($RS,'cd_programa').' - '.substr(f($RS,'ds_programa'),0,60);
    } 
  } else {
    if (!(strpos('AEV',$O)===false) || $w_copia>'') {
      // Recupera os dados da ação
      if ($w_copia>'') {
        $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_copia,$SG);
      } else {
        $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,$SG);
      } 
      foreach($RS as $row){$RS=$row; break;}
      if (count($RS)>0) {
        $w_proponente       = f($RS,'proponente');
        $w_sq_unidade_resp  = f($RS,'sq_unidade_resp');
        $w_titulo           = f($RS,'titulo');
        $w_prioridade       = f($RS,'prioridade');
        $w_aviso            = f($RS,'aviso_prox_conc');
        $w_dias             = f($RS,'dias_aviso');
        $w_inicio_real      = f($RS,'inicio_real');
        $w_fim_real         = f($RS,'fim_real');
        $w_concluida        = f($RS,'concluida');
        $w_data_conclusao   = f($RS,'data_conclusao');
        $w_nota_conclusao   = f($RS,'nota_conclusao');
        $w_custo_real       = f($RS,'custo_real');
        $w_chave_pai        = f($RS,'sq_solic_pai');
        $w_chave_aux        = null;
        $w_sq_menu          = f($RS,'sq_menu');
        $w_sq_unidade       = f($RS,'sq_unidade');
        $w_sq_tramite       = f($RS,'sq_siw_tramite');
        $w_solicitante      = f($RS,'solicitante');
        $w_cadastrador      = f($RS,'cadastrador');
        $w_executor         = f($RS,'executor');
        $w_inicio           = FormataDataEdicao(f($RS,'inicio'));
        $w_fim              = FormataDataEdicao(f($RS,'fim'));
        $w_inclusao         = f($RS,'inclusao');
        $w_ultima_alteracao = f($RS,'ultima_alteracao');
        $w_conclusao        = f($RS,'conclusao');
        $w_valor            = number_format(Nvl(f($RS,'valor'),0),2,',','.');
        $w_opiniao          = f($RS,'opiniao');
        $w_data_hora        = f($RS,'data_hora');
        $w_cd_programa      = f($RS,'cd_programa');
        $w_selecao_mp       = f($RS,'mpog_ppa');
        $w_selecao_se       = f($RS,'relev_ppa');
        $w_sq_natureza      = f($RS,'sq_natureza');
        $w_sq_horizonte     = f($RS,'sq_horizonte');
        $w_palavra_chave    = f($RS,'palavra_chave');
        $w_descricao        = f($RS,'descricao');
        $w_justificativa    = f($RS,'justificativa');
        $w_sq_unidade_adm   = f($RS,'sq_unidade_adm');
        $w_ln_programa      = f($RS,'ln_programa');
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
  FormataDataHora();
  FormataValor();
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.Botao.value == "Troca") { return true; }');
    Validate('w_titulo','Programa','1',1,5,100,'1','1');
    if ($O=='I' && $w_cd_programa=='') Validate('w_cd_programa','Programa PPA','SELECT','1',1,90,'1','1');
    Validate('w_sq_unidade_adm','Unidade administrativa','HIDDEN',1,1,18,'','0123456789');
    Validate('w_solicitante','Responsável monitoramento','HIDDEN',1,1,18,'','0123456789');
    Validate('w_sq_unidade_resp','Área planejamento','SELECT',1,1,18,'','0123456789');
    Validate('w_sq_natureza','Natureza','SELECT',1,1,18,'','0123456789');
    Validate('w_sq_horizonte','Horizonte','SELECT',1,1,18,'','0123456789');
    Validate('w_inicio','Início previsto','DATA',1,10,10,'','0123456789/');
    Validate('w_fim','Fim previsto','DATA',1,10,10,'','0123456789/');
    CompData('w_inicio','Data de recebimento','<=','w_fim','Limite para conclusão');
    Validate('w_proponente','Parcerias externas','','',2,90,'1','1');
    Validate('w_palavra_chave','Parcerias internas','','',2,90,'1','1');
    Validate('w_ln_programa','Endereço na internet','','',11,120,'1','1');
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
      if ($O=='V') $w_Erro= Validacao($w_sq_solicitacao,$SG);
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
    ShowHTML('      <tr><td>Os dados deste bloco serão utilizados para identificação do programa, bem como para o controle de sua execução.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    if ($w_cd_programa>'')  ShowHTML('      <tr><td valign="top"><b>Programa:</b><br><INPUT READONLY '.$w_Disabled.' class="STI" type="text" name="w_titulo" size="90" maxlength="100" value="'.$w_titulo.'" ></td>');
    else                    ShowHTML('      <tr><td valign="top"><b>Programa:</b><br><INPUT ACCESSKEY="A" '.$w_Disabled.' class="STI" type="text" name="w_titulo" size="90" maxlength="100" value="'.$w_titulo.'" ></td>');
    ShowHTML('          <tr>');
    if ($O=='I' || $w_cd_programa=='') {
      SelecaoProgramaPPA('Programa <u>P</u>PA:','P',null,$w_cliente,$w_ano,$w_cd_programa,'w_cd_programa','IDENTIFICACAO','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_cd_programa\'; document.Form.submit();"',$w_menu,null,null);
    } else {
      SelecaoProgramaPPA('Programa <u>P</u>PA:','P',null,$w_cliente,$w_ano,$w_cd_programa,'w_cd_programa',null,'disabled',$w_menu,null,null);
      ShowHTML('<INPUT type="hidden" name="w_cd_programa" value="'.$w_cd_programa.'">');
    } 
    ShowHTML('          </tr>');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    SelecaoUnidade_IS('<U>U</U>nidade administrativa:','U','Selecione a unidade administratriva responsável pelo programa.',$w_sq_unidade_adm,null,'w_sq_unidade_adm',null,'ADMINISTRATIVA');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    MontaRadioNS('<b>Selecionado pelo SPI/MP?</b>',$w_selecao_mp,'w_selecao_mp');
    MontaRadioNS('<b>Selecionado pelo SE/SEPPIR?</b>',$w_selecao_se,'w_selecao_se');
    ShowHTML('      </table></td></tr>');
    ShowHTML('      <tr valign="top">');
    SelecaoPessoa('Respo<u>n</u>sável monitoramento:','N','Selecione o nome da pessoa responsável pelas informações no SISPLAM.',$w_solicitante,null,'w_solicitante','USUARIOS');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade_IS('<U>Á</U>rea planejamento:','S','Selecione a área da secretaria ou orgão responsável pelo programa.',$w_sq_unidade_resp,null,'w_sq_unidade_resp',null,'PLANEJAMENTO');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    SelecaoNatureza_IS('Na<u>t</u>ureza:','T','Indique qual a natureza do programa com relação às suas ações.',$w_cliente,$w_sq_natureza,'w_sq_natureza',null,null);
    SelecaoHorizonte_IS('<U>H</U>orizonte temporal:','H','Indique se o programa é contínuo ao longo do PPA ou se é apenas temporário.',$w_cliente,$w_sq_horizonte,'w_sq_horizonte',null,null);
    ShowHTML('      </table></td></tr>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('          <tr>');
    if ($w_cd_programa>'') {
      ShowHTML('              <td valign="top"><b>Iní<u>c</u>io previsto:</b><br><input readonly '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_inicio,'01/01/'.$w_ano).'" onKeyDown="FormataData(this,event);" title="Usar formato dd/mm/aaaa"></td>');
      ShowHTML('              <td valign="top"><b><u>F</u>im previsto:</b><br><input readonly '.$w_Disabled.' accesskey="F" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_fim,'31/12/'.$w_ano).'" onKeyDown="FormataData(this,event);" title="Usar formato dd/mm/aaaa"></td>');
    } else {
      ShowHTML('              <td valign="top"><b>Iní<u>c</u>io previsto:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_inicio,'01/01/'.$w_ano).'" onKeyDown="FormataData(this,event);" title="Usar formato dd/mm/aaaa"></td>');
      ShowHTML('              <td valign="top"><b><u>F</u>im previsto:</b><br><input '.$w_Disabled.' accesskey="F" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_fim,'31/12/'.$w_ano).'" onKeyDown="FormataData(this,event);" title="Usar formato dd/mm/aaaa"></td>');
    } 
    ShowHTML('          </table>');
    ShowHTML('      <tr><td valign="top"><b>Parc<u>e</u>rias externas:<br><INPUT ACCESSKEY="E" '.$w_Disabled.' class="STI" type="text" name="w_proponente" size="90" maxlength="90" value="'.$w_proponente.'" title="Informar quais são os parceiros externos na execução do programa (campo opcional)."></td>');
    ShowHTML('      <tr><td valign="top"><b><u>P</u>arcerias internas:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="STI" type="text" name="w_palavra_chave" size="90" maxlength="90" value="'.$w_palavra_chave.'" title="Informar quais são os parceiros internos na execução do programa (campo opcional)."></td>');
    ShowHTML('      <tr><td valign="top"><b>En<u>d</u>ereço internet:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="w_ln_programa" size="90" maxlength="120" value="'.$w_ln_programa.'" title="Se desejar, informe o link do programa na internet."></td>');
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
  if (!(strpos('A',$O)===false) || $w_copia>'') {
    // Recupera os dados da ação
    $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,$SG);
    foreach($RS as $row){$RS=$row; break;}
    if (count($RS)>0) {
      $w_proponente         =f($RS,'proponente');
      $w_sq_unidade_resp    =f($RS,'sq_unidade_resp');
      $w_titulo             =f($RS,'titulo');
      $w_prioridade         =f($RS,'prioridade');
      $w_aviso              =f($RS,'aviso_prox_conc');
      $w_dias               =f($RS,'dias_aviso');
      $w_inicio_real        =f($RS,'inicio_real');
      $w_fim_real           =f($RS,'fim_real');
      $w_concluida          =f($RS,'concluida');
      $w_data_conclusao     =f($RS,'data_conclusao');
      $w_nota_conclusao     =f($RS,'nota_conclusao');
      $w_custo_real         =f($RS,'custo_real');
      $w_chave_pai          =f($RS,'sq_solic_pai');
      $w_chave_aux          =null;
      $w_sq_menu            =f($RS,'sq_menu');
      $w_sq_unidade         =f($RS,'sq_unidade');
      $w_sq_tramite         =f($RS,'sq_siw_tramite');
      $w_solicitante        =f($RS,'solicitante');
      $w_cadastrador        =f($RS,'cadastrador');
      $w_executor           =f($RS,'executor');
      $w_inicio             =FormataDataEdicao(f($RS,'inicio'));
      $w_fim                =FormataDataEdicao(f($RS,'fim'));
      $w_inclusao           =f($RS,'inclusao');
      $w_ultima_alteracao   =f($RS,'ultima_alteracao');
      $w_conclusao          =f($RS,'conclusao');
      $w_valor              =number_format(Nvl(f($RS,'valor'),0),2,',','.');
      $w_opiniao            =f($RS,'opiniao');
      $w_data_hora          =f($RS,'data_hora');
      $w_cd_programa        =f($RS,'cd_programa');
      $w_selecao_mp         =f($RS,'mpog_ppa');
      $w_selecao_se         =f($RS,'relev_ppa');
      $w_sq_natureza        =f($RS,'sq_natureza');
      $w_sq_horizonte       =f($RS,'sq_horizonte');
      $w_palavra_chave      =f($RS,'palavra_chave');
      $w_descricao          =f($RS,'descricao');
      $w_justificativa      =f($RS,'justificativa');
      $w_sq_unidade_adm     =f($RS,'sq_unidade_adm');
      $w_ln_programa        =f($RS,'ln_programa');
    }
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  FormataDataHora();
  FormataValor();
  ValidateOpen('Validacao');
  if ($O=='A') {
    Validate('w_valor','Recurso programado','VALOR','1',4,18,'','0123456789.,');
    CompValor('w_valor','Recurso programado','>','0,00','zero');
    Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
  } elseif ($O=='P') {
    Validate('w_chave','Programa','SELECT','1',1,18,'','0123456789');
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
    ShowHTML('<INPUT type="hidden" name="w_cd_programa" value="'.$w_cd_programa.'">');
    ShowHTML('<INPUT type="hidden" name="w_selecao_mp" value="'.$w_selecao_mp.'">');
    ShowHTML('<INPUT type="hidden" name="w_selecao_se" value="'.$w_selecao_se.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_natureza" value="'.$w_sq_natureza.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_horizonte" value="'.$w_sq_horizonte.'">');
    ShowHTML('<INPUT type="hidden" name="w_palavra_chave" value="'.$w_palavra_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_unidade_adm" value="'.$w_sq_unidade_adm.'">');
    ShowHTML('<INPUT type="hidden" name="w_ln_programa" value="'.$w_ln_programa.'">');
    //Passagem da cidade padrão como brasília, pelo retidara do impacto geográfico da tela
    $RS1 = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    ShowHTML('<INPUT type="hidden" name="w_cidade" value="'.f($RS1,'sq_cidade_padrao').'">');
    ShowHTML('<tr><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
    ShowHTML('<tr><td align="center">');
    ShowHTML('    <table width="99%" border="0">');
    ShowHTML('      <tr><td><font size=2>Programa: <b>'.f($RS,'titulo').'</b></font></td></tr>');
    // Identificação da ação
    ShowHTML('      <tr><td colspan="2"><br><font size="2"><b>IDENTIFICAÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
    ShowHTML('      <tr><td valign="top"><b>Programa PPA:</b></td>');
    ShowHTML('        <td>'.f($RS,'ds_programa').' ('.f($RS,'cd_programa').')'.' </td></tr>');
    ShowHTML('      <tr><td valign="top"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('      <tr><td><b>Unidade Administrativa:</b></td>');
    ShowHTML('        <td>'.ExibeUnidade('../',$w_cliente,f($RS,'nm_unidade_adm'),f($RS,'sq_unidade_adm'),$TP).'</td></tr>');
    ShowHTML('      <tr><td><b>Unidade Orçamentária:</b></td>');
    ShowHTML('        <td>'.f($RS,'nm_orgao').' </td></tr>');
    if (f($RS,'mpog_ppa')=='S') {
      ShowHTML('    <tr><td><b>Selecionada SPI/MP:</b></td>');
      ShowHTML('      <td>Sim</td></tr>');
    } else {                         
      ShowHTML('    <tr><td><b>Selecionada SPI/MP:</b></td>');
      ShowHTML('      <td>Não</td></tr>');
    }
    if (f($RS,'relev_ppa')=='S') {
      ShowHTML('    <tr><td><b>Selecionada SE/SEPPIR:</b></td>');
      ShowHTML('      <td>Sim</td></tr>');
    } else {                        
      ShowHTML('    <tr><td><b>Selecionada SE/SEPPIR:</b></td>');
      ShowHTML('      <td>Não</td></tr>');
    }
    ShowHTML('      <tr><td><b>Responsável monitoramento:</b></td>');
    ShowHTML('        <td>'.ExibePessoa('../',$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_sol')).'</td></tr>');
    ShowHTML('      <tr><td><b>Área planejamento:</b></td>');
    ShowHTML('        <td>'.ExibeUnidade('../',$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</td></tr>');
    ShowHTML('      <tr><td><b>Natureza:</b></td>');
    ShowHTML('        <td>'.f($RS,'nm_natureza').' </td></tr>');
    ShowHTML('      <tr><td><b>Horizonte:</b></td>');
    ShowHTML('        <td>'.f($RS,'nm_horizonte').' </td></tr>');
    ShowHTML('      <tr><td><b>Tipo do programa:</b></td>');
    ShowHTML('        <td>'.f($RS,'nm_tipo_programa').' </td></tr>');
    if (Nvl(f($RS,'ln_programa'),'---')=='---'){
      ShowHTML('    <tr><td>Endereço na internet:</b></td>');
      ShowHTML('      <td>'.Nvl(f($RS,'ln_programa'),'---').'</td></tr>');
    } else {                                        
      ShowHTML('    <tr><td>Endereço na internet:</b></td>');
      ShowHTML('      <td><a href="'.Nvl(f($RS,'ln_programa'),'---').'" target="blank"><b>'.Nvl(f($RS,'ln_programa'),'---').'</a></td></tr>');
    }
    ShowHTML('          </table>');
    ShowHTML('        <tr><td valign="top" colspan="2">');
    ShowHTML('      <tr><td>Recurso programado:</b></td>');
    ShowHTML('        <td>'.number_format(f($RS,'valor'),2,',','.').' </td></tr>');
    ShowHTML('      <tr><td>Início previsto:</b></td>');
    ShowHTML('        <td>'.FormataDataEdicao(f($RS,'inicio')).' </td></tr>');
    ShowHTML('      <tr><td>Fim previsto:</b></td>');
    ShowHTML('        <td>'.FormataDataEdicao(f($RS,'fim')).' </td></tr>');
    ShowHTML('          <tr valign="top"><td>Parcerias externas:</b></td>');
    ShowHTML('            <td>'.CRLF2BR(Nvl(f($RS,'proponente'),'---')).' </td></tr>');
    ShowHTML('          <tr valign="top"><td>Parcerias internas:</b></td>');
    ShowHTML('            <td>'.CRLF2BR(Nvl(f($RS,'palavra_chave'),'---')).' </td></tr>');
    // Responsaveis
    if (f($RS,'nm_gerente_programa')>'' || f($RS,'nm_gerente_executivo')>'' || f($RS,'nm_gerente_adjunto')>'') {
      ShowHTML('      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Responsáveis</td>');
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
    ShowHTML('<tr><td colspan="2"><br><font size="2"><b>PROGRAMAÇÃO FINANCEIRA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
    $RS1 = db_getPPADadoFinanc_IS::getInstanceOf($dbms,$w_cd_programa, null,$w_ano,$w_cliente,'VALORFONTE');
    if (count($RS1)<=0) {
      ShowHTML('<tr><td valign="top"><DD><b>Não existe nenhum valor para este programa.</b></DD></td>');
    } else {
      $w_cor='';
      ShowHTML('                      <tr><td valign="top" colspan="2">Fonte: SIGPLAN/MP - PPA 2004-2007</td>');
      $i=0;
      foreach($RS1 as $row) {
        if ($i==0) {
          ShowHTML('<tr><td valign="top">Tipo de orçamento:<br><b>'.f($row,'nm_orcamento').'</b></td>');
          ShowHTML('<tr><td valign="top">Valor por fonte:</td>');
          ShowHTML('<tr><td align="center" colspan="2">');
//          ShowHTML('<TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
          ShowHTML('         <table width=100%  border="1" bordercolor="#00000">');
          ShowHTML('<tr align="center">');
          ShowHTML('<td bgColor="#f0f0f0"><div><b>Fonte</b></div></td>');
          ShowHTML('<td bgColor="#f0f0f0"><div><b>2004*</b></div></td>');
          ShowHTML('<td bgColor="#f0f0f0"><div><b>2005**</b></div></td>');
          ShowHTML('<td bgColor="#f0f0f0"><div><b>2006</b></div></td>');
          ShowHTML('<td bgColor="#f0f0f0"><div><b>2007</b></div></td>');
          ShowHTML('<td bgColor="#f0f0f0"><div><b>2008</b></div></td>');
          ShowHTML('<td bgColor="#f0f0f0"><div><b>>Total</b></div></td>');
          ShowHTML('</tr>');
        }
        ShowHTML('<tr "valign="top">');
        ShowHTML('<td>'.f($row,'nm_fonte').'</td>');
        ShowHTML('<td align=" center">'.number_format(Nvl(f($row,'valor_ano_1'),0.00),2,',','.').'</td>');
        ShowHTML('<td align=" center">'.number_format(Nvl(f($row,'valor_ano_2'),0.00),2,',','.').'</td>');
        ShowHTML('<td align=" center">'.number_format(Nvl(f($row,'valor_ano_3'),0.00),2,',','.').'</td>');
        ShowHTML('<td align=" center">'.number_format(Nvl(f($row,'valor_ano_4'),0.00),2,',','.').'</td>');
        ShowHTML('<td align=" center">'.number_format(Nvl(f($row,'valor_ano_5'),0.00),2,',','.').'</td>');
        ShowHTML('<td align=" center">'.number_format(Nvl(f($row,'valor_total'),0.00),2,',','.').'</td>');
        ShowHTML('</tr>');
      }
      $RS1 = db_getPPADadoFinanc_IS::getInstanceOf($dbms,$w_cd_programa,null,$w_ano,$w_cliente,'VALORTOTAL');
      foreach($RS1 as $row){$RS1=$row; break;}
      ShowHTML('<tr><td valign="top" align="right"><b>Totais</td>');
      if (count($RS1)<=0) {
        ShowHTML('<td valign="top" colspan=6><DD><b>Nao existe nenhum valor para este programa</b></DD></td>');
      } else {
        ShowHTML('<td align=" center"><b>'.number_format(Nvl(f($RS1,'valor_ano_1'),0.00),2,',','.').'</td>');
        ShowHTML('<td align=" center"><b>'.number_format(Nvl(f($RS1,'valor_ano_2'),0.00),2,',','.').'</td>');
        ShowHTML('<td align=" center"><b>'.number_format(Nvl(f($RS1,'valor_ano_3'),0.00),2,',','.').'</td>');
        ShowHTML('<td align=" center"><b>'.number_format(Nvl(f($RS1,'valor_ano_4'),0.00),2,',','.').'</td>');
        ShowHTML('<td align=" center"><b>'.number_format(Nvl(f($RS1,'valor_ano_5'),0.00),2,',','.').'</td>');
        ShowHTML('<td align=" center"><b>'.number_format(Nvl(f($RS1,'valor_total'),0.00),2,',','.').'</td>');
        ShowHTML('</tr>');
        ShowHTML('</table>');
      }
    }
    ShowHTML('<tr><td valign="top" colspan="2">*Valor Lei Orçamentária Anual - LOA2004 + Créditos</td>');
    ShowHTML('<tr><td valign="top" colspan="2">**Valor do Projeto de Lei Orçamentária  Anual - PLOA 2005</td>');
    ShowHTML('<tr><td valign="top"><b><u>R</u>ecurso programado</b><br><input '.$w_Disabled.'accesskey="O" type="text" name="w_valor" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" onKeyDown="FormataValor(this,18,2,event)"></td>');
    ShowHTML('<tr><td><b><U>A</U>ssinatura Eletrônica</b><br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('<tr><td align="center"><hr>');
    ShowHTML('<tr><td align="center">');
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
    ShowHTML('<tr><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    SelecaoProgramaIS('<u>P</u>rograma:','P',null,$w_cliente,$w_ano,$w_chave,'w_chave','CADASTRADOS',null);
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
  $w_chave                  = $_REQUEST['w_chave'];
  $w_chave_aux              = $_REQUEST['w_chave_aux'];
  $w_cd_programa            = $_REQUEST['w_cd_programa'];
  $w_ds_programa            = $_REQUEST['w_ds_programa'];
  $w_nm_gerente_programa    = $_REQUEST['w_nm_gerente_programa'];
  $w_fn_gerente_programa    = $_REQUEST['w_fn_gerente_programa'];
  $w_em_gerente_programa    = $_REQUEST['w_em_gerente_programa'];
  $w_nm_gerente_executivo   = $_REQUEST['w_nm_gerente_executivo'];
  $w_fn_gerente_executivo   = $_REQUEST['w_fn_gerente_executivo'];
  $w_em_gerente_executivo   = $_REQUEST['w_em_gerente_executivo'];
  $w_nm_gerente_adjunto     = $_REQUEST['w_nm_gerente_adjunto'];
  $w_fn_gerente_adjunto     = $_REQUEST['w_fn_gerente_adjunto'];
  $w_em_gerente_adjunto     = $_REQUEST['w_em_gerente_adjunto'];
  if ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,$SG);
    foreach($RS as $row){$RS=$row; break;}
  } elseif (!(strpos('A',$O)===false)) {
    $RS = db_getProgramaPPA_IS::getInstanceOf($dbms,$w_cd_programa,$w_cliente,$w_ano,null,null,null,null);
    foreach($RS as $row){$RS=$row; break;}
    if (count($RS)>0) {
      $w_nm_gerente_programa    = f($RS,'nm_gerente_programa');
      $w_fn_gerente_programa    = f($RS,'fn_gerente_programa');
      $w_em_gerente_programa    = f($RS,'em_gerente_programa');
      $w_nm_gerente_executivo   = f($RS,'nm_gerente_executivo');
      $w_fn_gerente_executivo   = f($RS,'fn_gerente_executivo');
      $w_em_gerente_executivo   = f($RS,'em_gerente_executivo');
      $w_nm_gerente_adjunto     = f($RS,'nm_gerente_adjunto');
      $w_fn_gerente_adjunto     = f($RS,'fn_gerente_adjunto');
      $w_em_gerente_adjunto     = f($RS,'em_gerente_adjunto');
      $w_ds_programa            = f($RS,'ds_programa');
    } 
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('A',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    formatadata();
    FormataCEP();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('A',$O)===false)) {
      Validate('w_nm_gerente_programa','Gerente do programa','','1','3','60','1','1');
      Validate('w_fn_gerente_programa','Telefone do gerente programa','1','','7','20','1','1');
      Validate('w_em_gerente_programa','Email do gerente programa','','','3','60','1','1');
      Validate('w_nm_gerente_executivo','Gerente do executivo','','1','3','60','1','1');
      Validate('w_fn_gerente_executivo','Telefone do gerente executivo','1','','7','20','1','1');
      Validate('w_em_gerente_executivo','Email do gerente executivo','','','3','60','1','1');
      Validate('w_nm_gerente_adjunto','Gerente adjunto','','','3','60','1','1');
      Validate('w_fn_gerente_adjunto','Telefone do gerente adjunto','1','','7','20','1','1');
      Validate('w_em_gerente_adjunto','Email do gerente adjunto','','','3','60','1','1');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($O=='A') {
    BodyOpen('onLoad=\'document.Form.w_nm_gerente_programa.focus()\';');
  } else {
    BodyOpen(null);
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
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
      ShowHTML('        <td>Programa PPA</td>');
      ShowHTML('        <td>'.f($RS,'cd_programa').' - '.f($RS,'ds_programa').'</td>');
      ShowHTML('        <td align="top" nowrap>');
      ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_cd_programa='.f($RS,'cd_programa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_chave_aux='.f($RS,'sq_siw_solicitacao').'">Responsáveis</A>&nbsp');
      ShowHTML('        </td>');
      ShowHTML('      </tr>');
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('A',$O)===false)) {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_tipo" value="'.$w_tipo.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td valign="top"><font size="2"><b>Programa PPA: </b>'.$w_cd_programa.' - '.$w_ds_programa.' </b>');
    ShowHTML('      <tr><td valign="top"><b>Gerente do programa: </b>');
    ShowHTML('      <tr><td><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nm_gerente_programa" class="STI" SIZE="50" MAXLENGTH="60" VALUE="'.$w_nm_gerente_programa.'" title="Informe o nome do gerente do programa."></td>');
    ShowHTML('      <tr><td valign="top"><b><u>T</u>elefone:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fn_gerente_programa" class="STI" SIZE="15" MAXLENGTH="14" VALUE="'.$w_fn_gerente_programa.'" title="Informe o telefone do gerente do programa."></td>');
    ShowHTML('      <tr><td><b><u>E</u>mail:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_em_gerente_programa" class="STI" SIZE="50" MAXLENGTH="60" VALUE="'.$w_em_gerente_programa.'" title="Informe o e-mail do gerente do programa."></td>');
    ShowHTML('      <tr><td valign="top"><b>Gerente Executivo do programa: </b>');
    ShowHTML('      <tr><td><b>N<u>o</u>me:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_nm_gerente_executivo" class="STI" SIZE="50" MAXLENGTH="60" VALUE="'.$w_nm_gerente_executivo.'" title="Informe o nome do gerente executivo do programa."></td>');
    ShowHTML('      <tr><td valign="top"><b>Te<u>l</u>efone:</b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_fn_gerente_executivo" class="STI" SIZE="15" MAXLENGTH="14" VALUE="'.$w_fn_gerente_executivo.'" title="Informe o telefone do gerente executivo do programa."></td>');
    ShowHTML('      <tr><td><b>Em<u>a</u>il:</b><br><input '.$w_Disabled.' accesskey="A" type="text" name="w_em_gerente_executivo" class="STI" SIZE="50" MAXLENGTH="60" VALUE="'.$w_em_gerente_executivo.'" title="Informe o e-mail do gerente executivo do programa."></td>');
    ShowHTML('      <tr><td valign="top"><b>Gerente Adjunto do programa: </b>');
    ShowHTML('      <tr><td><b>No<u>m</u>e:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_nm_gerente_adjunto" class="STI" SIZE="50" MAXLENGTH="60" VALUE="'.$w_nm_gerente_adjunto.'" title="No caso de programa multisetorial cuja gerência não pertença a SEPPIR, informe o nome do gerente executivo adjunto do programa."></td>');
    ShowHTML('      <tr><td valign="top"><b>Tele<u>f</u>one:</b><br><input '.$w_Disabled.' accesskey="F" type="text" name="w_fn_gerente_adjunto" class="STI" SIZE="15" MAXLENGTH="14" VALUE="'.$w_fn_gerente_adjunto.'" title="No caso de programa multisetorial cuja gerência não pertença a SEPPIR, informe o telefone do gerente executivo adjunto do programa."></td>');
    ShowHTML('      <tr><td><b>Ema<u>i</u>l:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_em_gerente_adjunto" class="STI" SIZE="50" MAXLENGTH="60" VALUE="'.$w_em_gerente_adjunto.'" title="No caso de programa multisetorial cuja gerência não pertença a SEPPIR, informe o e-mail do gerente executivo adjunto do programa."></td>');
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
    $w_chave=$_REQUEST['w_chave'];
    $w_sq_menu=$_REQUEST['w_sq_menu'];
    $w_resultados=$_REQUEST['w_resultados'];
    $w_potencialidades=$_REQUEST['w_potencialidades'];
    $w_contribuicao_objetivo=$_REQUEST['w_contribuicao_objetivo'];
    $w_diretriz=$_REQUEST['w_diretriz'];
    $w_estrategia_monit=$_REQUEST['w_estrategia_monit'];
    $w_metodologia_aval=$_REQUEST['w_metodologia_aval'];
  } else {
    $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,$SG);
    foreach($RS as $row){$RS=$row; break;}
    if (count($RS)>0) {
      $w_sq_menu                = f($RS,'sq_menu');
      $w_contexto               = f($RS,'contexto');
      $w_justificativa_sigplan  = f($RS,'justificativa_sigplan');
      $w_objetivo               = f($RS,'objetivo');
      $w_publico_alvo           = f($RS,'publico_alvo');
      $w_resultados             = f($RS,'descricao');
      $w_estrategia             = f($RS,'estrategia');
      $w_potencialidades        = f($RS,'potencialidades');
      if (f($RS,'justificativa')!='') $w_observacoes=f($RS,'justificativa');
      else                            $w_observacoes=f($RS,'observacoes_ppa');
      $w_contribuicao_objetivo  = f($RS,'contribuicao_objetivo');
      $w_diretriz               = f($RS,'diretriz');
      $w_estrategia_monit       = f($RS,'estrategia_monit');
      $w_metodologia_aval       = f($RS,'metodologia_aval');
      $w_cd_programa            = f($RS,'cd_programa');
      $w_ds_programa            = f($RS,'ds_programa');
    } 
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  FormataDataHora();
  FormataValor();
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.Botao.value == "Troca") { return true; }');
    Validate('w_contribuicao_objetivo','Explique como o programa contribui para que o objetivo setorial seja alcançado','1','',5,2000,'1','1');
    Validate('w_diretriz','Diretrizes do Plano Nacional de Políticas de Integração Racial','1','',5,2000,'1','1');
    Validate('w_resultados','Resultados esperados','1','',5,2000,'1','1');
    Validate('w_potencialidades','Potencialidades','1','',5,2000,'1','1');
    Validate('w_estrategia_monit','Sistemática e estratégias a serem adotadas para o monitoramento do programa','1','',5,2000,'1','1');
    Validate('w_metodologia_aval','Sistemática e metodologias a serem adotadas para avaliação do programa','1','',5,2000,'1','1');
    Validate('w_observacoes','Observações','1','',5,4000,'1','1');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('EV',$O)===false)) {
    BodyOpen(null);
  } else {
    BodyOpen('onLoad=\'document.Form.w_resultados.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled=' DISABLED ';
      if ($O=='V') $w_Erro=Validacao($w_sq_solicitacao,$SG);
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('      <tr><td>Programa '.$w_cd_programa.' - '.$w_ds_programa.'</td>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><font size="2"><b>Programação qualitativa</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Os dados deste bloco visam orientar os executores do programa.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td valign="top"><b><u>E</u>xplique como o programa contribui para que o objetivo setorial seja alcançado:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_contribuicao_objetivo" class="STI" ROWS=5 cols=75 title="Descreva de que forma a execução do programa vai contribuir para o alcance do objetivo setorial do governo ao qual o programa está relacionado.">'.$w_contribuicao_objetivo.'</TEXTAREA></td>');
    ShowHTML('      <tr><td valign="top"><b><u>D</u>iretrizes do Plano Nacional da Promoção da Igualdade Racial:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_diretriz" class="STI" ROWS=5 cols=75 title="Informe a qual(is) diretrize(s) do Programa Naciona de Políticas de Integração Racial - o programa está relacionado.">'.$w_diretriz.'</TEXTAREA></td>');
    ShowHTML('      <tr><td valign="top"><div align="justify">Objetivo:<br><b>'.Nvl($w_objetivo,'---').'</b></div></td>');
    ShowHTML('      <tr><td valign="top"><div align="justify">Justificativa:<br><b>'.Nvl($w_justificativa_sigplan,'---').'</b></div></td>');
    ShowHTML('      <tr><td valign="top"><div align="justify">Público-alvo:<br><b>'.Nvl($w_publico_alvo,'---').'</b></div></td>');
    ShowHTML('      <tr><td valign="top"><b><u>R</u>esultados esperados:</b><br><textarea '.$w_Disabled.' accesskey="R" name="w_resultados" class="STI" ROWS=5 cols=75 title="Descreva os principais resultados que se espera alcançar com a execução do programa.">'.$w_resultados.'</TEXTAREA></td>');
    ShowHTML('      <tr><td valign="top"><div align="justify">Estratégia implementação:<br><b>'.Nvl($w_estrategia,'---').'</b></div></td>');
    ShowHTML('      <tr><td valign="top"><b><u>P</u>otencialidades:</b><br><textarea '.$w_Disabled.' accesskey="P" name="w_potencialidades" class="STI" ROWS=5 cols=75 title="Descreva quais são os principais pontos fortes (internos) e as principais oportunidades (externas) do programa.">'.$w_potencialidades.'</TEXTAREA></td>');
    ShowHTML('      <tr><td valign="top"><b><u>S</u>istemática e estratégias a serem adotadas para o monitoramento do programa:</b><br><textarea '.$w_Disabled.' accesskey="S" name="w_estrategia_monit" class="STI" ROWS=5 cols=75 title="Descreva a sistemática e as estratégias que serão adotadas para o monitoramento do programa, informando, inclusive as ferramentas que serão utilizadas.">'.$w_estrategia_monit.'</TEXTAREA></td>');
    ShowHTML('      <tr><td valign="top"><b>Sistemática e <u>m</u>etodologias a serem adotadas para avalição do programa:</b><br><textarea '.$w_Disabled.' accesskey="M" name="w_metodologia_aval" class="STI" ROWS=5 cols=75 title="Descreva a sistemática e as metodologias que serão adotadas para a avaliação do programa, informando, inclusive as ferramentas que serão utilizadas.">'.$w_metodologia_aval.'</TEXTAREA></td>');
    ShowHTML('      <tr><td valign="top"><b><u>O</u>bservações:</b><br><textarea '.$w_Disabled.' accesskey="O" name="w_observacoes" class="STI" ROWS=5 cols=75 title="Informe as observações pertinentes (campo não obrigatório).">'.$w_observacoes.'</TEXTAREA></td>');
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
// Rotina de indicadores do programa
// -------------------------------------------------------------------------
function Indicadores() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave          = $_REQUEST['w_chave'];
  $w_chave_aux      = $_REQUEST['w_chave_aux'];

  $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,$SG);
  foreach($RS as $row){$RS=$row; break;}
  $w_cd_programa = f($RS,'cd_programa');
  $w_ds_programa = f($RS,'ds_programa');

  if ($w_troca>'') {
    // Se for recarga da página
    $w_cd_unidade_medida        = $_REQUEST['w_cd_unidade_medida'];
    $w_cd_programa              = $_REQUEST['w_cd_programa'];
    $w_cd_periodicidade         = $_REQUEST['w_cd_periodicidade'];
    $w_cd_base_geografica       = $_REQUEST['w_cd_base_geografica'];
    $w_categoria_analise        = $_REQUEST['w_categoria_analise'];
    $w_ordem                    = $_REQUEST['w_ordem'];
    $w_titulo                   = $_REQUEST['w_titulo'];
    $w_conceituacao             = $_REQUEST['w_conceituacao'];
    $w_interpretacao            = $_REQUEST['w_interpretacao'];
    $w_usos                     = $_REQUEST['w_usos'];
    $w_limitacoes               = $_REQUEST['w_limitacoes'];
    $w_comentarios              = $_REQUEST['w_comentarios'];
    $w_fonte                    = $_REQUEST['w_fonte'];
    $w_formula                  = $_REQUEST['w_formula'];
    $w_tipo_in                  = $_REQUEST['w_tipo_in'];
    $w_indice_ref               = $_REQUEST['w_indice_ref'];
    $w_apuracao_ref             = $_REQUEST['w_apuracao_ref'];
    $w_prev_ano_1               = $_REQUEST['w_prev_ano_1'];
    $w_prev_ano_2               = $_REQUEST['w_prev_ano_2'];
    $w_prev_ano_3               = $_REQUEST['w_prev_ano_3'];
    $w_prev_ano_4               = $_REQUEST['w_prev_ano_4'];
    $w_observacoes              = $_REQUEST['w_observacoes'];
    $w_cd_indicador             = $_REQUEST['w_cd_indicador'];
    $w_cumulativa               = $_REQUEST['w_cumulativa'];
    $w_quantidade               = $_REQUEST['w_quantidade'];
    $w_exequivel                = $_REQUEST['w_exequivel'];
    $w_situacao_atual           = $_REQUEST['w_situacao_atual'];
    $w_justificativa_inex       = $_REQUEST['w_justificativa_inex'];
    $w_outras_medidas           = $_REQUEST['w_outras_medidas'];
    $w_indice_apurado           = $_REQUEST['w_indice_apurado'];
    $w_apuracao_ind             = $_REQUEST['w_apuracao_ind'];
  } elseif ($O=='L') {
    if (Nvl(f($RS,'tit_exec'),0)    == $w_usuario || 
        Nvl(f($RS,'subst_exec'),0)  == $w_usuario || 
        Nvl(f($RS,'titular'),0)     == $w_usuario || 
        Nvl(f($RS,'substituto'),0)  == $w_usuario || 
        Nvl(f($RS,'executor'),0)    == $w_usuario || 
       (Nvl(f($RS,'cadastrador'),0)== $w_usuario && $P1<2) || 
        Nvl(f($RS,'solicitante'),0) == $w_usuario) {
      if (Nvl(f($RS,'inicio_real'),'')>'' || (Nvl(f($RS,'sg_tramite'),'--')!='EE' && $P1>1)) $w_acesso=0; else $w_acesso=1;
    } else {
      $w_acesso=0;
    } 
    // Recupera todos os registros para a listagem
    $RS = db_getSolicIndic_IS::getInstanceOf($dbms,$w_chave,null,'LISTA',null,null);
    $RS = SortArray($RS,'ordem','asc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados do endereço informado
    $RS = db_getSolicIndic_IS::getInstanceOf($dbms,$w_chave,$w_chave_aux,'REGISTRO',null,null);
    foreach($RS as $row){$RS=$row; break;}
    $w_cd_unidade_medida    = f($RS,'cd_unidade_medida');
    $w_cd_periodicidade     = f($RS,'cd_periodicidade');
    $w_cd_base_geografica   = f($RS,'cd_base_geografica');
    $w_categoria_analise    = f($RS,'categoria_analise');
    $w_titulo               = f($RS,'titulo');
    $w_ordem                = f($RS,'ordem');
    $w_conceituacao         = f($RS,'conceituacao');
    $w_interpretacao        = f($RS,'interpretacao');
    $w_usos                 = f($RS,'usos');
    $w_limitacoes           = f($RS,'limitacoes');
    $w_comentarios          = f($RS,'comentarios');
    $w_fonte                = f($RS,'fonte');
    $w_formula              = f($RS,'formula');
    $w_tipo_in              = f($RS,'tipo');
    $w_indice_ref           = number_format(Nvl(f($RS,'valor_referencia'),0),2,',','.');
    $w_apuracao_ref         = FormataDataEdicao(f($RS,'apuracao_referencia'));
    $w_observacoes          = f($RS,'observacao');
    $w_cd_indicador         = f($RS,'cd_indicador');
    $w_cumulativa           = f($RS,'cumulativa');
    $w_quantidade           = number_format(Nvl(f($RS,'quantidade'),0),2,',','.');
    $w_situacao_atual       = f($RS,'situacao_atual');
    $w_exequivel            = f($RS,'exequivel');
    $w_justificativa_inex   = f($RS,'justificativa_inexequivel');
    $w_outras_medidas       = f($RS,'outras_medidas');
    $w_prev_ano_1           = number_format(Nvl(f($RS,'previsao_ano_1'),0),2,',','.');
    $w_prev_ano_2           = number_format(Nvl(f($RS,'previsao_ano_2'),0),2,',','.');
    $w_prev_ano_3           = number_format(Nvl(f($RS,'previsao_ano_3'),0),2,',','.');
    $w_prev_ano_4           = number_format(Nvl(f($RS,'previsao_ano_4'),0),2,',','.');
    $w_indice_apurado       = number_format(Nvl(f($RS,'valor_apurado'),0),2,',','.');
    $w_apuracao_ind         = FormataDataEdicao(f($RS,'apuracao_indice'));
    $w_nm_unidade_medida    = f($RS,'nm_unidade_medida');
    $w_nm_cumulativa        = f($RS,'nm_cumulativa');
    $w_nm_periodicidade     = f($RS,'nm_periodicidade');
    $w_nm_base_geografica   = f($RS,'nm_base_geografica');
    if (f($RS,'cd_indicador')>'') $w_nm_programada='Sim'; else $w_nm_programada='Não';
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    formatadata();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      if (($P1!=2 && $P1!=3 && $P1!=5) || $O=='I') {
        if ($w_cd_indicador=='') {
          Validate('w_titulo','Título','','1','2','200','1','1');
          Validate('w_cd_unidade_medida','Unidade de medida','SELECT','1','1','18','1','1');
          Validate('w_cd_periodicidade','Periodicidade','SELECT','1','1','18','1','1');
          Validate('w_cd_base_geografica','Base geográfica','SELECT','1','1','18','1','1');
        } 
        Validate('w_quantidade','Índice programado','VALOR','1',4,18,'','0123456789.,');
        CompValor('w_quantidade','Índice programado','>','0','zero');
        if ($w_cd_indicador=='') {
          Validate('w_indice_ref','Índice referência','VALOR','',4,18,'','0123456789.,');
          Validate('w_apuracao_ref','Data de referência','DATA','',10,10,'','0123456789/');
        } 
        Validate('w_ordem','Ordem','1','1','1','3','','0123456789');
        if ($w_cd_indicador=='') {
          Validate('w_fonte','Fonte','','','3','200','1','1');
          Validate('w_prev_ano_1','Previsão ano 1','VALOR','',4,18,'','0123456789.,');
          Validate('w_prev_ano_2','Previsão ano 2','VALOR','',4,18,'','0123456789.,');
          Validate('w_prev_ano_3','Previsão ano 3','VALOR','',4,18,'','0123456789.,');
          Validate('w_prev_ano_4','Previsão ano 4','VALOR','',4,18,'','0123456789.,');
        } 
        Validate('w_conceituacao','Conceituação','','1','3','2000','1','1');
        Validate('w_interpretacao','Interpretacao','','','3','2000','1','1');
        Validate('w_usos','Usos','','','3','2000','1','1');
        Validate('w_limitacoes','Limitações','','','3','2000','1','1');
        Validate('w_categoria_analise','Categoria sugeridas para análise','','','3','2000','1','1');
        Validate('w_comentarios','Dados estatísticos e comentários','','','3','2000','1','1');
        if ($w_cd_indicador=='') Validate('w_formula','Fórmula de cáculo','','','3','4000','1','1');
        Validate('w_observacoes','Observações','','','3','4000','1','1');
      } else {
        Validate('w_indice_apurado','Índice apurado','VALOR','',4,18,'','0123456789.,');
        Validate('w_apuracao_ind','Data de apuração','DATA','',10,10,'','0123456789/');
        Validate('w_situacao_atual','Situação atual','','','2','4000','1','1');
        ShowHTML('  if (theForm.w_exequivel[1].checked && theForm.w_justificativa_inex.value == \'\') {');
        ShowHTML('     alert (\'Justifique porque o indicador não será cumprido!\');');
        ShowHTML('     theForm.w_justificativa_inex.focus();');
        ShowHTML('     return false;');
        ShowHTML('  } else { if (theForm.w_exequivel[0].checked) ');
        ShowHTML('     theForm.w_justificativa_inex.value = \'\';');
        ShowHTML('   }');
        ShowHTML('  if (theForm.w_exequivel[1].checked && theForm.w_outras_medidas.value == \'\') {');
        ShowHTML('     alert (\'Indique quais são as medidas necessárias para o cumprimento do indicador!\');');
        ShowHTML('     theForm.w_outras_medidas.focus();');
        ShowHTML('     return false;');
        ShowHTML('  } else { if (theForm.w_exequivel[0].checked) ');
        ShowHTML('     theForm.w_outras_medidas.value = \'\';');
        ShowHTML('   }');
        Validate('w_justificativa_inex','Justificativa','','','2','1000','1','1');
        Validate('w_outras_medidas','Medidas','','','2','1000','1','1');
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
  ShowHTML('     <tr><td>Programa '.$w_cd_programa.' - '.$w_ds_programa.'</td>');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    if ($w_acesso==1 && ($P1!=2 && $P1!=3 && $P1!=5)) ShowHTML('<tr><td><font size="2"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&w_cd_programa='.$w_cd_programa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    else                                              ShowHTML('<tr><td><font size="2">&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Indicador</td>');
    ShowHTML('          <td><b>PPA</td>');
    ShowHTML('          <td><b>Cumulativa</td>');
    ShowHTML('          <td><b>Tipo</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=9 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      foreach($RS as $row) {
        if (f($row,'cd_indicador')>'') $w_ppa='sim'; else $w_ppa='não';
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td><A class="HL" HREF="#" onClick="window.open(\''.montaURL_JS($w_dir,'programa.php?par=AtualizaIndicador&O=V&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_indicador').'&w_tipo=Volta&P1=10&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\',\'Indicador\',\'width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no\'); return false;" title="Clique para exibir os dados!">'.f($row,'titulo').'</A></td>');
        ShowHTML('        <td align="center">'.$w_ppa.'</td>');
        ShowHTML('        <td align="center">'.Nvl(f($row,'cumulativa'),'---').'</td>');
        ShowHTML('        <td align="center">'.Nvl(f($row,'nm_tipo'),'---').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        if ($w_acesso==1) {
          if ($P1==2 || $P1==3 || $P1==5) ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_indicador').'&w_cd_programa='.$w_cd_programa.'&w_ds_programa='.$w_ds_programa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Atualizar</A>&nbsp');
          else                            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_indicador').'&w_cd_programa='.$w_cd_programa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
          if ((Nvl(f($row,'cd_indicador'),'')=='') && ($P1!=2 && $P1!=3 && $P1!=5)) ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_indicador').'&w_cd_programa='.$w_cd_programa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');">Excluir</A>&nbsp');
        } else {
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'AtualizaIndicador'.'&R='.$w_pagina.$par.'&O=V&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_indicador').'&w_cd_programa='.$w_cd_programa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Exibir</A>&nbsp');
        }
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
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_cd_programa" value="'.$w_cd_programa.'">');
    ShowHTML('<INPUT type="hidden" name="w_ds_programa" value="'.$w_ds_programa.'">'); 
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    if (($P1!=2 && $P1!=3 && $P1!=5) || $O=='I'){
      if ($w_cd_indicador>'' && $O!='E' && $O!='V') {
        ShowHTML('<INPUT type="hidden" name="w_titulo" value="'.$w_titulo.'">');
        ShowHTML('<INPUT type="hidden" name="w_cd_unidade_medida" value="'.$w_cd_unidade_medida.'">');
        ShowHTML('<INPUT type="hidden" name="w_cd_periodicidade" value="'.$w_cd_periodicidade.'">');
        ShowHTML('<INPUT type="hidden" name="w_cd_base_geografica" value="'.$w_cd_base_geografica.'">');
        ShowHTML('<INPUT type="hidden" name="w_indice_ref" value="'.$w_indice_ref.'">');
        ShowHTML('<INPUT type="hidden" name="w_apuracao_ref" value="'.$w_apuracao_ref.'">');
        ShowHTML('<INPUT type="hidden" name="w_fonte" value="'.$w_fonte.'">');
        ShowHTML('<INPUT type="hidden" name="w_formula" value="'.$w_formula.'">');
        ShowHTML('<INPUT type="hidden" name="w_prev_ano_1" value="'.$w_prev_ano_1.'">');
        ShowHTML('<INPUT type="hidden" name="w_prev_ano_2" value="'.$w_prev_ano_2.'">');
        ShowHTML('<INPUT type="hidden" name="w_prev_ano_3" value="'.$w_prev_ano_3.'">');
        ShowHTML('<INPUT type="hidden" name="w_prev_ano_4" value="'.$w_prev_ano_4.'">');
      } 
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
      ShowHTML('  <table width="97%" border="0">');
      if ($w_cd_indicador>'' && $O!='E' && $O!='V') $w_Disabled = ' DISABLED ';
      ShowHTML('    <tr><td><b><u>I</u>ndicador:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_titulo" class="STI" SIZE="90" MAXLENGTH="100" VALUE="'.$w_titulo.'" title="Informe a denominação do indicador."></td>');
      ShowHTML('    <tr><td valign="top"><table border=0 width="100%" cellspacing=0><tr valign="top">');
      SelecaoUniMedida_IS('Unidade de <U>m</U>edida:','M','Selecione a unidade de medida do indicador',$w_cd_unidade_medida,'w_cd_unidade_medida',null,null);
      if ($w_cd_indicador>'' && $O!='E' && $O!='V')$w_Disabled = ' ';
      MontaTipoIndicador('<b>Tipo de indicador?</b>',$w_tipo_in,'w_tipo_in');
      ShowHTML('    <tr><td align="left"><b>Índice <u>p</u>rogramado:<br><input accesskey="P" type="text" name="w_quantidade" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_quantidade.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o índice que se deseja alcançar ao final do exercício."></td>');
      MontaRadioNS('<b>Indicador cumulativo?</b>',$w_cumulativa,'w_cumulativa');
      ShowHTML('         </table></td></tr>');
      if ($w_cd_indicador>'' && $O!='E' && $O!='V') $w_Disabled = ' DISABLED ';
      ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
      ShowHTML('    <tr><td valign="top"><b><u>Í</u>ndice referência:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_indice_ref" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_indice_ref.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o índice que será utilizado como linha de base."></td>');
      ShowHTML('              <td><b><u>D</u>ata de referência:</b><br><input '.$w_Disabled.' accesskey="A" type="text" name="w_apuracao_ref" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_apuracao_ref.'" onKeyDown="FormataData(this,event);" title="Informe a data em que foi apurado o índice de referência.(Usar formato dd/mm/aaaa)"></td>');
      if ($w_cd_indicador>'' && $O!='E' && $O!='V') $w_Disabled=' ';
      ShowHTML('              <td align="left"><b>O<u>r</u>dem:<br><INPUT ACCESSKEY="R" TYPE="TEXT" CLASS="STI" NAME="w_ordem" SIZE=3 MAXLENGTH=3 VALUE="'.$w_ordem.'" '.$w_Disabled.'></td>');
      ShowHTML('         </table></td></tr>');
      if ($w_cd_indicador>'' && $O!='E' && $O!='V') $w_Disabled=' DISABLED ';
      ShowHTML('    <tr><td><b>F<u>o</u>nte:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_fonte" class="STI" SIZE="90" MAXLENGTH="200" VALUE="'.$w_fonte.'" title="Fonte do indicador."></td>');
      ShowHTML('    <tr><td valign="top"><table border=0 width="100%" cellspacing=0><tr valign="top">');
      SelecaoPeriodicidade_IS('<U>P</U>eriodicidade:','P','Selecione a periodicidade do indicador',$w_cd_periodicidade,'w_cd_periodicidade',null,null);
      SelecaoBaseGeografica_IS('<U>B</U>ase geográfica:','B','Selecione a base geográfica do indicador',$w_cd_base_geografica,'w_cd_base_geografica',null,null);
      ShowHTML('         </table></td></tr>');
      ShowHTML('    <tr><td valign="top"><table border=0 width="100%" cellspacing=0><tr valign="top">');
      ShowHTML('    <tr valign="top"><td><b>Previsão 2004:</b><br><input '.$w_Disabled.' type="text" name="w_prev_ano_1" class="STI" SIZE="12" MAXLENGTH="18" VALUE="'.$w_prev_ano_1.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o índice previsto para o 1º ano  (campo não obrigatório)."></td>');
      ShowHTML('        <td><b>Previsão 2005:</b><br><input '.$w_Disabled.' type="text" name="w_prev_ano_2" class="STI" SIZE="12" MAXLENGTH="18" VALUE="'.$w_prev_ano_2.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o índice previsto para o 2º ano  (campo não obrigatório)."></td>');
      ShowHTML('        <td><b>Previsão 2006:</b><br><input '.$w_Disabled.' type="text" name="w_prev_ano_3" class="STI" SIZE="12" MAXLENGTH="18" VALUE="'.$w_prev_ano_3.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o índice previsto para o 3º ano  (campo não obrigatório)."></td>');
      ShowHTML('        <td><b>Previsão 2007:</b><br><input '.$w_Disabled.' type="text" name="w_prev_ano_4" class="STI" SIZE="12" MAXLENGTH="18" VALUE="'.$w_prev_ano_4.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o índice previsto para o 4º ano  (campo não obrigatório)."></td>');
      ShowHTML('    </table></td></tr>');
      if ($w_cd_indicador>'' && $O!='E' && $O!='V') $w_Disabled=' ';
      ShowHTML('    <tr><td><b><u>C</u>onceituação:</b><br><textarea '.$w_Disabled.' accesskey="C" name="w_conceituacao" class="STI" ROWS=5 cols=75 title="Descreva as características que definem o indicador e a forma como ele se expressa, se necessário agregando informações para a compreensão de seu conteúdo.">'.$w_conceituacao.'</TEXTAREA></td>');
      ShowHTML('    <tr><td><b>I<u>n</u>terpretação:</b><br><textarea '.$w_Disabled.' accesskey="N" name="w_interpretacao" class="STI" ROWS=5 cols=75 title="Explique, de maneira sucinta, o tipo de informação obtida com o indicador e o seu significado.">'.$w_interpretacao.'</TEXTAREA></td>');
      ShowHTML('    <tr><td><b><u>U</u>sos:</b><br><textarea '.$w_Disabled.' accesskey="U" name="w_usos" class="STI" ROWS=5 cols=75 title="Descreva as principais formas de utilização dos dados que devem ser consideradas para fins de análise.">'.$w_usos.'</TEXTAREA></td>');
      ShowHTML('    <tr><td><b><u>L</u>imitações:</b><br><textarea '.$w_Disabled.' accesskey="L" name="w_limitacoes" class="STI" ROWS=5 cols=75 title="Informe os fatores que restringem a interpretação do indicador, referentes tanto ao próprio conceito quanto à fonte utilizada.">'.$w_limitacoes.'</TEXTAREA></td>');
      ShowHTML('    <tr><td><b>C<u>a</u>tegorias sugeridas para análise:</b><br><textarea '.$w_Disabled.' accesskey="A" name="w_categoria_analise" class="STI" ROWS=5 cols=75 title="Informe os níveis de desagregação dos dados que podem contribuir para a interpretação da informação do indicador e que sejam efetivamente disponíveis, como, por exemplo, sexo e idade.">'.$w_categoria_analise.'</TEXTAREA></td>');
      ShowHTML('    <tr><td><b><u>D</u>ados estatísticos e comentários:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_comentarios" class="STI" ROWS=5 cols=75 title="Campo destinado à inserção de informações, resumidas e comentadas, que ilustram a aplicação do indicador com base na situação real observada. Sempre que possível os dados devem ser desagregados por grandes regiões e para anos selecionados da década seguinte.">'.$w_comentarios.'</TEXTAREA></td>');
      if ($w_cd_indicador>'' && $O!='E' && $O!='V') $w_Disabled = ' DISABLED ';
      ShowHTML('    <tr><td><b><u>F</u>órmula de cálculo:</b><br><textarea '.$w_Disabled.' accesskey="F" name="w_formula" class="STI" ROWS=5 cols=75 title="Demonstrar, de forma sucinta e por meio de expressões matemáticas, o algoritmo que permite calcular o valor do indicador.">'.$w_formula.'</TEXTAREA></td>');
      if ($w_cd_indicador>'' && $O!='E' && $O!='V') $w_Disabled = ' ';
      ShowHTML('    <tr><td><b>Ob<u>s</u>ervações:</b><br><textarea '.$w_Disabled.' accesskey="S" name="w_observacoes" class="STI" ROWS=5 cols=75 title="Informe as observações pertinentes (campo não obrigatório).">'.$w_observacoes.'</TEXTAREA></td>');
    } else {
      ShowHTML('    <tr><td align="center" bgcolor="#FAEBD7" colspan="2">');
      ShowHTML('      <table border=1 width="100%">');
      ShowHTML('        <tr><td valign="top" colspan="2">');
      ShowHTML('          <TABLE border=0 WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('            <tr><td colspan="2">Indicador:<b><br><font size=2>'.$w_titulo.'</font></td></tr>');
      ShowHTML('            <tr><td>Indicador PPA?<b><br>'.$w_nm_programada.'</td>');
      ShowHTML('                <td>Unidade de medida:<b><br>'.$w_nm_unidade_medida.'</td></tr>');
      if ($w_tipo_in=='R') ShowHTML('            <tr><td>Tipo do indicador:<b><br>Resultado</td>');
      else                 ShowHTML('            <tr><td>Tipo do indicador:<b><br>Processo</td>');
      ShowHTML('                <td>Índice programado:<b><br>'.$w_quantidade.'</td></tr>');
      ShowHTML('            <tr><td>Cumulativa?<b><br>'.$w_nm_cumulativa.'</td>');
      ShowHTML('                <td>Índice referência:<b><br>'.$w_indice_ref.'</td></tr>');
      ShowHTML('            <tr><td>Data apuração:<b><br>'.FormataDataEdicao($w_apuracao_ref).'</td>');
      ShowHTML('                <td>Fonte:<b><br>'.$w_fonte.'</td></tr>');
      ShowHTML('            <tr><td>Periodicidade:<b><br>'.$w_nm_periodicidade.'</td>');
      ShowHTML('                <td>Base geográfica:<b><br>'.$w_nm_base_geografica.'</td></tr>');
      ShowHTML('          </TABLE>');
      ShowHTML('      </table>');
      ShowHTML('    <tr bgcolor="'.$conTrBgColor.'"><td align="center" colspan="2">');
      ShowHTML('      <table width="100%" border="0">');
      ShowHTML('         <tr valign="top"><td><b>Previsão:</b><br></td>');
      ShowHTML('           <table border=1 width="100%" cellspacing=0><tr valign="top">');
      ShowHTML('             <tr><td align="center"><b>2004</b></td>');
      ShowHTML('                 <td align="center"><b>2005</b></td>');
      ShowHTML('                 <td align="center"><b>2006</b></td>');
      ShowHTML('                 <td align="center"><b>2007</b></td>');
      ShowHTML('             </tr>');
      ShowHTML('             <tr><td align="right">'.number_format(Nvl($w_prev_ano_1,0),2,',','.').'</td>');
      ShowHTML('                 <td align="right">'.number_format(Nvl($w_prev_ano_2,0),2,',','.').'</td>');
      ShowHTML('                 <td align="right">'.number_format(Nvl($w_prev_ano_3,0),2,',','.').'</td>');
      ShowHTML('                 <td align="right">'.number_format(Nvl($w_prev_ano_4,0),2,',','.').'</td>');
      ShowHTML('             </tr>');
      ShowHTML('           </table></td></tr>');
      ShowHTML('    <tr bgcolor="'.$conTrBgColor.'"><td align="center" colspan="2">');
      ShowHTML('      <table width="100%" border="0">');
      if ($w_conceituacao!='') {
        ShowHTML('     <tr><td valign="top"><b>Conceituação:</b><br>'.$w_conceituacao.'</td>');
        ShowHTML('     <tr><td valign="top">&nbsp;</td>');
      } 
      if ($w_interpretacao!='') {
        ShowHTML('     <tr><td valign="top"><b>Interpretação:</b><br>'.$w_interpretacao.'</td>');
        ShowHTML('     <tr><td valign="top">&nbsp;</td>');
      } 
      if ($w_usos!='') {
        ShowHTML('     <tr><td valign="top"><b>Usos:</b><br>'.$w_usos.'</td>');
        ShowHTML('     <tr><td valign="top">&nbsp;</td>');
      } 
      if ($w_limitacoes!='') {
        ShowHTML('     <tr><td valign="top"><b>Limitações:</b><br>'.$w_limitacoes.'</td>');
        ShowHTML('     <tr><td valign="top">&nbsp;</td>');
      } 
      if ($w_categoria_analise!='') {
        ShowHTML('     <tr><td valign="top"><b>Categorias sugeridas para análise:</b><br>'.$w_categoria_analise.'</td>');
        ShowHTML('     <tr><td valign="top">&nbsp;</td>');
      } 
      if ($w_comentarios!='') {
        ShowHTML('     <tr><td valign="top"><b>Dados estatísticos e comentários:</b><br>'.$w_comentarios.'</td>');
        ShowHTML('     <tr><td valign="top">&nbsp;</td>');
      } 
      if ($w_formula!='') {
        ShowHTML('     <tr><td valign="top"><b>Fórmula de cálculo:</b><br>'.$w_formula.'</td>');
        ShowHTML('     <tr><td valign="top">&nbsp;</td>');
      } 
      if ($w_observacoes!='') {
        ShowHTML('     <tr><td valign="top"><b>Observações:</b><br>'.$w_observacoes.'</td>');
        ShowHTML('     <tr><td valign="top">&nbsp;</td>');
      } 
      ShowHTML('    <tr valign="top"><td colspan="2"><table border=0 width="100%" cellspacing=0>');
      ShowHTML('    <tr valign="top"><td><b><u>Í</u>ndice apurado:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_indice_apurado" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_indice_apurado.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o índice que foi apurado."></td>');
      ShowHTML('              <td><b>Da<u>t</u>a de apuração:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_apuracao_ind" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_apuracao_ind.'" onKeyDown="FormataData(this,event);" title="Informe a data de apuração do índice.(Usar formato dd/mm/aaaa)"></td>');
      ShowHTML('         </table></td></tr>');
      ShowHTML('    <tr><td valign="top"><b><u>S</u>ituação atual do indicador:</b><br><textarea '.$w_Disabled.' accesskey="S" name="w_situacao_atual" class="STI" ROWS=5 cols=75 title="Descreva, de maneria sucinta, qual é a situação atual do indicador.">'.$w_situacao_atual.'</TEXTAREA></td>');
      ShowHTML('    <tr valign="top">');
      MontaRadioSN('<b>O índice programado será alcançado?</b>',$w_exequivel,'w_exequivel');
      ShowHTML('    </tr>');
      ShowHTML('    <tr><td valign="top"><b><u>I</u>nformar os motivos que impedem o alcance do índice programado:</b><br><textarea '.$w_Disabled.' accesskey="J" name="w_justificativa_inex" class="STI" ROWS=5 cols=75 title="Informe os motivos que inviabilizam que o índice seja alcançado.">'.$w_justificativa_inex.'</TEXTAREA></td>');
      ShowHTML('    <tr><td valign="top"><b><u>Q</u>uais as medidas necessárias para que o índice programado seja alcançado?</b><br><textarea '.$w_Disabled.' accesskey="Q" name="w_outras_medidas" class="STI" ROWS=5 cols=75 title="Descreva quais são as medidas que devem ser adotadas para  que a tendencia de não alcance do índice programado possa ser revertida.">'.$w_outras_medidas.'</TEXTAREA></td>');
    } 
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
// Rotina de atualização do indicador da programa
// -------------------------------------------------------------------------
function AtualizaIndicador() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_tipo       = strtoupper(trim($_REQUEST['w_tipo']));
  $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,'ISPRGERAL');
  foreach($RS as $row){$RS=$row; break;}
  $w_cabecalho='      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font  size="2"><b>Programa: '.f($RS,'titulo').'</td></tr>';
  // Configura uma variável para testar se as etapas podem ser atualizadas.
  // Ações concluídas ou canceladas não podem ter permitir a atualização.
  if (Nvl(f($RS,'sg_tramite'),'--')=='EE') $w_fase='S'; else $w_fase='N';
  if ($w_troca>'') {
    // Se for recarga da página
    $w_ordem        = $_REQUEST['w_ordem'];
    $w_titulo       = $_REQUEST['w_titulo'];
    $w_sq_pessoa    = $_REQUEST['w_sq_pessoa'];
    $w_sq_unidade   = $_REQUEST['w_sq_unidade'];
    $w_quantidade   = $_REQUEST['w_quantidade'];
    $w_cumulativa   = $_REQUEST['w_cumulativa'];
    $w_programada   = $_REQUEST['w_programada'];
    $w_conceituacao = $_REQUEST['w_conceituacao'];
    for ($i=0; $i<=$i=12; $i=$i+1) {
      $w_execucao_fisica[i]     = $_REQUEST['w_execucao_fisica[i]'];
      $w_execucao_financeira[i] = $_REQUEST['w_execucao_financeira[i]'];
    } 
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getSolicIndic_IS::getInstanceOf($dbms,$w_chave,null,'LISTA',null,null);
    $RS = SortArray($RS,'ordem','asc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados do endereço informado
    $RS = db_getSolicIndic_IS::getInstanceOf($dbms,$w_chave,$w_chave_aux,'REGISTRO',null,null);
    foreach($RS as $row){$RS=$row; break;}
    $w_titulo=f($RS,'titulo');
    $w_ordem                = f($RS,'ordem');
    $w_sq_pessoa            = f($RS,'sq_pessoa');
    $w_sq_unidade           = f($RS,'sq_unidade');
    $w_situacao_atual       = f($RS,'situacao_atual');
    $w_quantidade           = number_format(Nvl(f($RS,'quantidade'),0),2,',','.');
    $w_cumulativa           = f($RS,'cumulativa');
    $w_exequivel            = f($RS,'exequivel');
    $w_justificativa_inex   = f($RS,'justificativa_inexequivel');
    $w_outras_medidas       = f($RS,'outras_medidas');
    if (f($RS,'cd_indicador')>'')   $w_nm_programada='Sim';
    else                            $w_nm_programada='Não';
    $w_nm_cumulativa        = Nvl(f($RS,'nm_cumulativa'),'---');
    $w_nm_unidade_medida    = Nvl(f($RS,'nm_unidade_medida'),'---');
    $w_nm_periodicidade     = Nvl(f($RS,'nm_periodicidade'),'---');
    $w_nm_base_geografica   = Nvl(f($RS,'nm_base_geografica'),'---');
    $w_categoria_analise    = Nvl(f($RS,'categoria_analise'),'---');
    $w_conceituacao         = Nvl(f($RS,'conceituacao'),'---');
    $w_interpretacao        = Nvl(f($RS,'interpretacao'),'---');
    $w_usos                 = Nvl(f($RS,'usos'),'---');
    $w_limitacoes           = Nvl(f($RS,'limitacoes'),'---');
    $w_comentarios          = Nvl(f($RS,'comentarios'),'---');
    $w_fonte                = Nvl(f($RS,'fonte'),'---');
    $w_formula              = Nvl(f($RS,'formula'),'---');
    $w_tipo                 = Nvl(f($RS,'tipo'),'---');
    $w_indice_ref           = number_format(Nvl(f($RS,'valor_referencia'),0),2,',','.');
    $w_indice_apurado       = number_format(Nvl(f($RS,'valor_apurado'),0),2,',','.');
    $w_apuracao_ref         = Nvl(FormataDataEdicao(f($RS,'apuracao_referencia')),'---');
    $w_apuracao_ind         = Nvl(FormataDataEdicao(f($RS,'apuracao_indice')),'---');
    $w_prev_ano_1           = Nvl(f($RS,'previsao_ano_1'),0);
    $w_prev_ano_2           = Nvl(f($RS,'previsao_ano_2'),0);
    $w_prev_ano_3           = Nvl(f($RS,'previsao_ano_3'),0);
    $w_prev_ano_4           = Nvl(f($RS,'previsao_ano_4'),0);
    $w_quantitativo_1       = f($RS,'valor_mes_1');
    $w_quantitativo_2       = f($RS,'valor_mes_2');
    $w_quantitativo_3       = f($RS,'valor_mes_3');
    $w_quantitativo_4       = f($RS,'valor_mes_4');
    $w_quantitativo_5       = f($RS,'valor_mes_5');
    $w_quantitativo_6       = f($RS,'valor_mes_6');
    $w_quantitativo_7       = f($RS,'valor_mes_7');
    $w_quantitativo_8       = f($RS,'valor_mes_8');
    $w_quantitativo_9       = f($RS,'valor_mes_9');
    $w_quantitativo_10      = f($RS,'valor_mes_10');
    $w_quantitativo_11      = f($RS,'valor_mes_11');
    $w_quantitativo_12      = f($RS,'valor_mes_12');
    $w_observacao           = Nvl(f($RS,'observacao'),'---');
  } elseif (Nvl($w_sq_pessoa,'')=='') {
    // Se o indicador não tiver responsável atribuído, recupera o responsável pela ação
    $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,'ISPRGERAL');
    $w_sq_pessoa    = f($RS,'solicitante');
    $w_sq_unidade   = f($RS,'sq_unidade_resp');
  } 
  if ($w_tipo=='WORD') headerWord(null);
  else Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Indicador do programa</TITLE>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_quantitativo_1','Quantitativo de Janeiro','','','1','10','','0123456789');
      Validate('w_quantitativo_2','Quantitativo de Fevereiro','','','1','10','','0123456789');
      Validate('w_quantitativo_3','Quantitativo de Março','','','1','10','','0123456789');
      Validate('w_quantitativo_4','Quantitativo de Abril','','','1','10','','0123456789');
      Validate('w_quantitativo_5','Quantitativo de Maio','','','1','10','','0123456789');
      Validate('w_quantitativo_6','Quantitativo de Junho','','','1','10','','0123456789');
      Validate('w_quantitativo_7','Quantitativo de Julho','','','1','10','','0123456789');
      Validate('w_quantitativo_8','Quantitativo de Agosto','','','1','10','','0123456789');
      Validate('w_quantitativo_9','Quantitativo de Setembro','','','1','10','','0123456789');
      Validate('w_quantitativo_10','Quantitativo de Outubro','','','1','10','','0123456789');
      Validate('w_quantitativo_11','Quantitativo de Novembro','','','1','10','','0123456789');
      Validate('w_quantitativo_12','Quantitativo de Dezembro','','','1','10','','0123456789');
      Validate('w_situacao_atual','Situação atual', '', '', '2', '4000','1','1');
      ShowHTML('  if (theForm.w_exequivel[1].checked && theForm.w_justificativa_inex.value == \'\') {');
      ShowHTML('     alert (\'Justifique porque o indicador não será cumprido!\');');
      ShowHTML('     theForm.w_justificativa_inex.focus();');
      ShowHTML('     return false;');
      ShowHTML('  } else { if (theForm.w_exequivel[0].checked) ');
      ShowHTML('     theForm.w_justificativa_inex.value = \'\';');
      ShowHTML('   }');
      ShowHTML('  if (theForm.w_exequivel[1].checked && theForm.w_outras_medidas.value == \'\') {');
      ShowHTML('     alert (\'Indique quais são as medidas necessárias para o cumprimento do indicador!\');');
      ShowHTML('     theForm.w_outras_medidas.focus();');
      ShowHTML('     return false;');
      ShowHTML('  } else { if (theForm.w_exequivel[0].checked) ');
      ShowHTML('     theForm.w_outras_medidas.value = \'\';');
      ShowHTML('   }');
      Validate('w_justificativa_inex','Justificativa','','','2','4000','1','1');
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
    BodyOpen('onLoad=\'document.Form.focus()\';');
  } else {
    BodyOpen(null);
  } 
  ShowHTML('<div align=center><center>');
  ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML($w_cabecalho);
  if ($w_tipo!='WORD' && $O=='V') {
    ShowHTML('<tr><td align="right"colspan="2">');
    ShowHTML('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
    ShowHTML('&nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&O=V&w_chave='.$w_chave.'&w_chave_aux='.$w_chave_aux.'&w_tipo=WORD&P1=10&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
    ShowHTML('</td></tr>');
  } 
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('  <tr><td colspan="2"><font size="3"></td>');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS).'</td></tr>');
    ShowHTML('  <tr><td align="center" colspan="3">');
    ShowHTML('      <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Indicador</td>');
    ShowHTML('          <td><b>PPA</td>');
    ShowHTML('          <td><b>Data apuracao</td>');
    ShowHTML('          <td><b>Indice referência</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('    <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><font size="2"><b>Não foi encontrado nenhum registro.</b></td></tr>');
    } else {
      foreach($RS as $row) {
        if (Nvl(f($row,'tit_exec'),0)    == $w_usuario || 
            Nvl(f($row,'sub_exec'),0)    == $w_usuario || 
            Nvl(f($row,'titular'),0)     == $w_usuario || 
            Nvl(f($row,'substituto'),0)  == $w_usuario || 
            Nvl(f($row,'executor'),0)    == $w_usuario || 
            Nvl(f($row,'solicitante'),0) == $w_usuario || 
            Nvl(f($row,'sq_pessoa'),0)   == $w_usuario) {
          ShowHtml(Indicadorlinha($w_chave,f($row,'sq_indicador'),f($row,'titulo'),f($row,'apuracao_referencia'),f($row,'indice_referencia')));
        } else {
          ShowHtml(Indicadorlinha($w_chave,f($row,'sq_indicador'),f($row,'titulo'),f($row,'apuracao_referencia'),f($row,'indice_referencia')));
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
      ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
      ShowHTML('<INPUT type="hidden" name="w_cumulativa" value="'.$w_cumulativa.'">');
      ShowHTML('<INPUT type="hidden" name="w_quantidade" value="'.$w_quantidade.'">');
    } 
    ShowHTML('    <tr><td align="center" bgcolor="#FAEBD7" colspan="2">');
    ShowHTML('      <table border=1 width="100%">');
    ShowHTML('        <tr><td valign="top" colspan="2">');
    ShowHTML('          <TABLE border=0 WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('            <tr><td colspan="2">Indicador:<b><br><font size=2>'.$w_titulo.'</font></td></tr>');
    ShowHTML('            <tr><td>Indicador PPA?<b><br>'.$w_nm_programada.'</td>');
    ShowHTML('                <td>Unidade de medida:<b><br>'.$w_nm_unidade_medida.'</td></tr>');
    if ($w_tipo=='R') ShowHTML('            <tr><td>Tipo do indicador:<b><br>Resultado</td>');
    else              ShowHTML('            <tr><td>Tipo do indicador:<b><br>Processo</td>');
    ShowHTML('                <td>Índice programado:<b><br>'.$w_quantidade.'</td></tr>');
    ShowHTML('            <tr><td>Cumulativa?<b><br>'.$w_nm_cumulativa.'</td>');
    ShowHTML('                <td>Índice referência:<b><br>'.$w_indice_ref.'</td></tr>');
    ShowHTML('            <tr><td>Data apuração:<b><br>'.FormataDataEdicao($w_apuracao_ref).'</td>');
    ShowHTML('                <td>Fonte:<b><br>'.$w_fonte.'</td></tr>');
    ShowHTML('            <tr><td>Periodicidade:<b><br>'.$w_nm_periodicidade.'</td>');
    ShowHTML('                <td>Base geográfica:<b><br>'.$w_nm_base_geografica.'</td></tr>');
    ShowHTML('          </TABLE>');
    ShowHTML('      </table>');
    ShowHTML('    <tr bgcolor="'.$conTrBgColor.'"><td align="center" colspan="2">');
    ShowHTML('      <table width="100%" border="0">');
    ShowHTML('         <tr valign="top"><td><b>Previsão:</b><br></td>');
    ShowHTML('           <table border=1 width="100%" cellspacing=0><tr valign="top">');
    ShowHTML('             <tr><td align="center"><b>2004</b></td>');
    ShowHTML('                 <td align="center"><b>2005</b></td>');
    ShowHTML('                 <td align="center"><b>2006</b></td>');
    ShowHTML('                 <td align="center"><b>2007</b></td>');
    ShowHTML('             </tr>');
    ShowHTML('             <tr><td align="right">'.number_format($w_prev_ano_1,2,',','.').'</td>');
    ShowHTML('                 <td align="right">'.number_format($w_prev_ano_2,2,',','.').'</td>');
    ShowHTML('                 <td align="right">'.number_format($w_prev_ano_3,2,',','.').'</td>');
    ShowHTML('                 <td align="right">'.number_format($w_prev_ano_4,2,',','.').'</td>');
    ShowHTML('             </tr>');
    ShowHTML('           </table></td></tr>');
    ShowHTML('    <tr bgcolor="'.$conTrBgColor.'"><td align="center" colspan="2">');
    ShowHTML('      <table width="100%" border="0">');
    if ($w_conceituacao!='---') {
      ShowHTML('     <tr><td valign="top"><b>Conceituação:</b><br>'.$w_conceituacao.'</td>');
      ShowHTML('     <tr><td valign="top">&nbsp;</td>');
    } 
    if ($w_interpretacao!='---') {
      ShowHTML('     <tr><td valign="top"><b>Interpretação:</b><br>'.$w_interpretacao.'</td>');
      ShowHTML('     <tr><td valign="top">&nbsp;</td>');
    } 
    if ($w_usos!='---') {
      ShowHTML('     <tr><td valign="top"><b>Usos:</b><br>'.$w_usos.'</td>');
      ShowHTML('     <tr><td valign="top">&nbsp;</td>');
    } 
    if ($w_limitacoes!='---') {
      ShowHTML('     <tr><td valign="top"><b>Limitações:</b><br>'.$w_limitacoes.'</td>');
      ShowHTML('     <tr><td valign="top">&nbsp;</td>');
    } 
    if ($w_categoria_analise!='---') {
      ShowHTML('     <tr><td valign="top"><b>Categorias sugeridas para análise:</b><br>'.$w_categoria_analise.'</td>');
      ShowHTML('     <tr><td valign="top">&nbsp;</td>');
    } 
    if ($w_comentarios!='---') {
      ShowHTML('     <tr><td valign="top"><b>Dados estatísticos e comentários:</b><br>'.$w_comentarios.'</td>');
      ShowHTML('     <tr><td valign="top">&nbsp;</td>');
    } 
    if ($w_formula!='---') {
      ShowHTML('     <tr><td valign="top"><b>Fórmula de cálculo:</b><br>'.$w_formula.'</td>');
      ShowHTML('     <tr><td valign="top">&nbsp;</td>');
    } 
    if ($w_observacao!='---') {
      ShowHTML('     <tr><td valign="top"><b>Observações:</b><br>'.$w_observacao.'</td>');
      ShowHTML('     <tr><td valign="top">&nbsp;</td>');
    } 
    ShowHTML('     <tr><td valign="top"><table width="100%" border="0">');
    ShowHTML('     <tr><td valign="top"><b>Índice apurado:</b><br>'.$w_indice_apurado.'</td>');
    if ($w_apuracao_ind!='---') ShowHTML('      <td valign="top"><b>Data apuração:</b><br>'.$w_apuracao_ind.'</td>');
    ShowHTML('     </table>');
    ShowHTML('     <tr><td valign="top">&nbsp;</td>');
    if ($w_situacao_atual!='---') {
      ShowHTML('     <tr><td valign="top"><b>Situação atual do indicador:</b><br>'.Nvl($w_situacao_atual,'---').'</td>');
      ShowHTML('     <tr><td valign="top">&nbsp;</td>');
    } 
    if ($w_exequivel!='---') {
      ShowHTML('     <tr><td valign="top"><b>O índice programado será alcançado?</b><br>'.RetornaSimNao($w_exequivel).'</td>');
      ShowHTML('     <tr><td valign="top">&nbsp;</td>');
    } 
    if ($w_exequivel=='N') {
      ShowHTML('     <tr><td valign="top"><b>Infomar os motivos quem impedem o alcance do índice programado:</b><br>'.Nvl($w_justificativa_inex,'---').'</td>');
      ShowHTML('     <tr><td valign="top">&nbsp;</td>');
      ShowHTML('     <tr><td valign="top"><b>Quais medidas necessárias para que o índice programado seja alcançado?:</b><br>'.Nvl($w_outras_medidas,'---').'</td>');
    } 
    ShowHTML('           </table></td></tr>');
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
// Rotina de restrições do programa
// -------------------------------------------------------------------------
function Restricoes() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_tipo       = $_REQUEST['w_tipo'];
  $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,$SG);
  foreach($RS as $row){$RS=$row; break;}
  $w_cd_programa = f($RS,'cd_programa');
  $w_ds_programa = f($RS,'ds_programa');
  if (Nvl(f($RS,'tit_exec'),0)      == $w_usuario || 
      Nvl(f($RS,'subst_exec'),0)    == $w_usuario || 
      Nvl(f($RS,'titular'),0)       == $w_usuario || 
      Nvl(f($RS,'substituto'),0)    == $w_usuario || 
      Nvl(f($RS,'executor'),0)      == $w_usuario || 
     (Nvl(f($RS,'cadastrador'),0)   == $w_usuario && $P1<2) || 
      Nvl(f($RS,'solicitante'),0)   == $w_usuario) {
    if (Nvl(f($RS,'inicio_real'),'')>'') $w_acesso=0; else $w_acesso=1;
  } else {
    $w_acesso=0;
  } 
  $w_cabecalho='      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font  size="2"><b>Programa: '.f($RS,'titulo').'</td></tr>';
  if ($w_troca>'') {
    // Se for recarga da página
    $w_cd_tipo_restricao    = $_REQUEST['w_cd_tipo_restricao'];
    $w_cd_tipo_inclusao     = $_REQUEST['w_cd_tipo_inclusao'];
    $w_cd_competecia        = $_REQUEST['w_cd_competecia'];
    $w_inclusao             = $_REQUEST['w_inclusao'];
    $w_descricao            = $_REQUEST['w_descricao'];
    $w_providencia          = $_REQUEST['w_providencia'];
    $w_superacao            = $_REQUEST['w_superacao'];
    $w_relatorio            = $_REQUEST['w_relatorio'];
    $w_tempo_habil          = $_REQUEST['w_tempo_habil'];
    $w_observacao_controle  = $_REQUEST['w_observacao_controle'];
    $w_observacao_monitor   = $_REQUEST['w_observacao_monitor'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getRestricao_IS::getInstanceOf($dbms,$SG,$w_chave,null);
    $RS = SortArray($RS,'phpdt_inclusao','desc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    $RS = db_getRestricao_IS::getInstanceOf($dbms,$SG,$w_chave,$w_chave_aux);
    foreach($RS as $row){$RS=$row; break;}
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
    FormataData();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
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
  ShowHTML('     <tr><td>Programa '.$w_cd_programa.' - '.$w_ds_programa.'</td>');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    if ($w_acesso==1) ShowHTML('<tr><td><font size="2"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    else              ShowHTML('<tr><td><font size="2">&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Descrição</td>');
    ShowHTML('          <td><b>Tipo restricao</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=9 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor; 
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td><A class="HL" HREF="#" onClick="window.open(\''.montaURL_JS($w_dir,$w_pagina.$par.'&O=V&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_restricao').'&w_tipo=Volta&P1=10&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\',\'Restricao\',\'width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no\'); return false;" title="Clique para exibir os dados!">'.f($row,'descricao').'</A></td>');
        ShowHTML('        <td>'.f($row,'nm_tp_restricao').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        if ($w_acesso==1) {
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_restricao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_restricao').'&w_descricao='.f($row,'descricao').'&w_providencia='.f($row,'providencia').'&w_cd_tipo_restricao='.f($row,'cd_tipo_restricao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');">Excluir</A>&nbsp');
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
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('  <table width="97%" border="0">');
    ShowHTML('    <tr valign="top" >');
    SelecaoTPRestricao_IS('<U>T</U>ipo de restrição:','T','Selecione o tipo de restrição',$w_cd_tipo_restricao,'w_cd_tipo_restricao',null,null);
    ShowHTML('    <tr><td colspan=2><b><u>D</u>escrição:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_descricao" class="STI" ROWS=5 cols=75 title="Descreva os fatores que podem prejudicar o andamento do programa. As restrições podem ser administrativas, ambientais, de auditoria, de licitações, financeiras, institucuionais, políticas, tecnológicas, judiciais, etc. Cada tipo de restrição deve ser inserido separadamente.">'.$w_descricao.'</TEXTAREA></td>');
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
      ShowHTML('&nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&O=V&w_chave='.$w_chave.'&w_chave_aux='.$w_chave_aux.'&w_tipo=WORD&P1=10&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
      ShowHTML('</td></tr>');
    } 
    ShowHTML('    <tr><td align="center" bgcolor="#FAEBD7" colspan="2">');
    ShowHTML('      <table border=1 width="100%">');
    ShowHTML('        <tr><td valign="top" colspan="2">');
    ShowHTML('          <TABLE border=0 WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
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
      if ($P1==10) ShowHTML('            <input class="STB" type="button" onClick="window.close();" name="Botao" value="Fechar">');
      else         ShowHTML('            <input class="STB" type="button" onClick="history.back(-1);" name="Botao" value="Voltar">');
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
    foreach($RS as $row){$RS=$row; break;}
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
    FormataData();
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
    ShowHTML('      <tr><td colspan=3>Usuários que devem receber emails dos encaminhamentos deste programa.</td></tr>');
    ShowHTML('      <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    if ($P1!=4) {
      ShowHTML('<tr><td><font size="2"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    } else {
      $RS1 = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,'ISPRGERAL');
      foreach($RS1 as $row){$RS1=$row; break;}
      ShowHTML('<tr><td colspan=3 align="center" bgcolor="#FAEBD7"><table border=1 width="100%"><tr><td>');
      ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('        <tr valign="top">');
      if (f($RS1,'cd_programa')>'') ShowHTML('          <td><b>Programa '.f($RS1,'cd_programa').' - '.f($RS1,'ds_programa').' </b>');
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
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_pessoa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');">Excluir</A>&nbsp');
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
      SelecaoPessoa('<u>P</u>essoa:','N','Selecione a pessoa que deve receber e-mails com informações sobre o programa.',$w_chave_aux,$w_chave,'w_chave_aux','INTERES');
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
    $RS1 = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,$SG);
    foreach($RS1 as $row){$RS1=$row; break;}
    ShowHTML('     <tr><td>Programa '.f($RS1,'cd_programa').' - '.f($RS1,'ds_programa').'</td>');
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
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave_aux').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave_aux').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Excluir</A>&nbsp');
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
// Rotina das outras iniciativas
// -------------------------------------------------------------------------
function Iniciativas() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_readonly   = '';
  $w_erro       = '';
  $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,$SG);
  foreach($RS as $row){$RS=$row; break;}
  if (count($RS)>0) {
    $w_chave_pai    = f($RS,'sq_solic_pai');
    $w_chave_aux    = null;
    $w_sq_menu      = f($RS,'sq_menu');
    $w_sq_unidade   = f($RS,'sq_unidade');
    $w_nm_ppa_pai   = f($RS,'nm_ppa_pai');
    $w_cd_ppa_pai   = f($RS,'cd_ppa_pai');
    $w_nm_ppa       = f($RS,'nm_ppa');
    $w_cd_ppa       = f($RS,'cd_ppa');
    $w_nm_pri       = f($RS,'nm_pri');
    $w_cd_pri       = f($RS,'cd_pri');
    $w_sq_isprojeto = f($RS,'sq_isprojeto');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  if (Nvl($w_sq_isprojeto,0)==0) {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Para inserir outras iniciativas, cadastre a iniciativa prioritária primeiro!\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  checkbranco();
  FormataData();
  FormataDataHora();
  FormataValor();
  ValidateOpen('Validacao');
  ShowHTML('  theForm.Botao.disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen(null);
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('    <table width="97%" border="0">');
  ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><font size="2"><b>Outras iniciativas</td></td></tr>');
  ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td>Os dados deste bloco visa informar as outras iniciativas da ação.</td></tr>');
  ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
  if ($w_cd_ppa>'') {
    ShowHTML('      <tr><td valign="top"><b>Programa PPA: </b><br>'.$w_cd_ppa_pai.' - '.$w_nm_ppa_pai.' </b>');
    ShowHTML('      <tr><td valign="top"><b>Ação PPA: </b><br>'.$w_cd_ppa.' - '.$w_nm_ppa.' </b>');
  } 
  if ($w_sq_isprojeto>'') ShowHTML('      <tr><td valign="top"><b>Iniciativa prioritária: </b><br>'.$w_nm_pri.' </b>');
  $RS = db_getOrPrioridadeList::getInstanceOf($dbms,$w_chave,$w_cliente,$w_sq_isprojeto);
  ShowHTML('      <tr><td valign="top"><br>');
  ShowHTML('      <tr><td valign="top"><b>Selecione outras iniciativas prioritárias as quais a ação está relacionada:</b>');
  foreach($RS as $row) {
    if (Nvl(f($row,'Existe'),0)>0) ShowHTML('      <tr><td valign="top">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="w_outras_iniciativas" value="'.f($row,'chave').'" checked>'.f($row,'nome').'</td>');
    else                          ShowHTML('      <tr><td valign="top">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="w_outras_iniciativas" value="'.f($row,'chave').'">'.f($row,'nome').'</td>');
  } 
  ShowHTML('      <tr><td align="center" colspan="3">');
  ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
  ShowHTML('          </td>');
  ShowHTML('      </tr>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de financiamento
// -------------------------------------------------------------------------
function Financiamento() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_sq_acao_ppa  = $_REQUEST['w_sq_acao_ppa'];
    $w_obs_financ   = $_REQUEST['w_obs_financ'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getFinancAcaoPPA::getInstanceOf($dbms,$w_chave,$w_cliente,null);
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados do financiamento
    $RS = db_getFinancAcaoPPA::getInstanceOf($dbms,$w_chave,$w_cliente,$_REQUEST['w_sq_acao_ppa']);
    foreach($RS as $row){$RS=$row; break;}
    $w_sq_acao_ppa  = f($RS,'sq_acao_ppa');
    $w_obs_financ   = f($RS,'observacao');
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    FormataData();
    FormataCEP();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_sq_acao_ppa','Ação PPA','SELECT','1','1','10','','1');
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
    BodyOpen('onLoad=\'document.Form.w_sq_acao_ppa.focus()\';');
  } else {
    BodyOpen(null);
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
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
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'cd_ppa_pai').'.'.f($row,'cd_ppa').'</td>');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_sq_acao_ppa='.f($row,'sq_acao_ppa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_sq_acao_ppa='.f($row,'sq_acao_ppa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Excluir</A>&nbsp');
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
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,$SG);
    foreach($RS as $row){$RS=$row; break;}
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    if (f($RS,'sq_acao_ppa')>'') {
      ShowHTML('      <tr><td valign="top"><b>Programa PPA: </b><br>'.f($RS,'cd_ppa_pai').' - '.f($RS,'nm_ppa_pai').' </b>');
      ShowHTML('      <tr><td valign="top"><b>Ação PPA: </b><br>'.f($RS,'cd_ppa').' - '.f($RS,'nm_ppa').' </b>');
    } 
    if (f($RS,'sq_isprojeto')>'') ShowHTML('      <tr><td valign="top"><b>Iniciativa prioritária: </b><br>'.f($RS,'nm_pri').' </b>');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    if ($O=='I'){
      SelecaoAcaoPPA('Ação <u>P</u>PA:','P',null,$w_sq_acao_ppa,$w_chave,$w_ano,'w_sq_acao_ppa','FINANCIAMENTO',null,$w_menu,null,null);
    } else {
      ShowHTML('<INPUT type="hidden" name="w_sq_acao_ppa" value="'.$w_sq_acao_ppa.'">');
      SelecaoAcaoPPA('Ação <u>P</u>PA:','P',null,$w_sq_acao_ppa,$w_chave,$w_ano,'w_sq_acao_ppa',null,'disabled',$w_menu,null,null);
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
// Rotina de visualização
// -------------------------------------------------------------------------
function Visual1() {
  extract($GLOBALS);
  $w_chave  = $_REQUEST['w_chave'];
  $w_tipo   = strtoupper(trim($_REQUEST['w_tipo']));
  // Recupera o logo do cliente a ser usado nas listagens
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  if (f($RS,'logo')>'') $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
  if ($w_tipo=='WORD') HeaderWord(null); else Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Visualização de Ação</TITLE>');
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_tipo!='WORD') BodyOpenClean(null);
  ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
  if ($P1==1)       ShowHTML('Relatório Geral por Programa');
  elseif ($P1==2)   ShowHTML('Plano Plurianual 2004 - 2007 <BR> Relatório Geral por Programa');
  else              ShowHTML('Visualização do Programa');
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
  ShowHTML(VisualPrograma($w_chave,'L',$w_usuario,$P1,$P4));
  if ($w_tipo>'' && $w_tipo!='WORD') ShowHTML('<center><B>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></center>');
  if ($w_tipo!='WORD') Rodape();
} 
// =========================================================================
// Rotina de visualização do novo layout de relatórios
// -------------------------------------------------------------------------
function Visual() {
  extract($GLOBALS);
  $w_chave=$_REQUEST['w_chave'];
  $w_tipo=strtoupper(trim($_REQUEST['w_tipo']));
  // Recupera o logo do cliente a ser usado nas listagens
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  if (f($RS,'logo')>'') $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
  if ($w_tipo=='WORD') HeaderWord(null); else Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Visualização do Programa</TITLE>');
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_tipo!='WORD') BodyOpenClean(null);
  ShowHTML('<div align="center">');
  ShowHTML('<table width="95%" border="0" cellspacing="3">');
  ShowHTML('<tr><td colspan="2">');
  ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><DIV ALIGN="LEFT"><IMG src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"></DIV></TD>');
  ShowHTML('<TD><DIV ALIGN="RIGHT"><FONT SIZE=4 COLOR="#000000"><B>');
  if ($P1==1 || $P1==2) ShowHTML('Ficha Resumida do Programa <br> Exercício '.$w_ano);
  else                  ShowHTML('Programas <br> Exercício '.$w_ano);
  ShowHTML('</B></FONT></DIV></TD></TR>');
  ShowHTML('</TABLE></TD></TR>');
  if ($w_tipo>'' && $w_tipo!='WORD') ShowHTML('<tr><td colspan="2"><div align="center"><b>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></div></td></tr>');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML(VisualPrograma($w_chave,'L',$w_usuario,$P1,$P4,'sim','sim','sim','sim','sim','sim','sim','sim','sim','sim','sim'));
  if ($w_tipo>'' && $w_tipo!='WORD') ShowHTML('<tr><td colspan="2"><div align="center"><b>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></div></td></tr>');
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
    $w_observacao = $_REQUEST['w_observacao'];
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
  ShowHTML(VisualPrograma($w_chave,'V',$w_usuario,$P1,$P4,'','','','','','','','','',''));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'ISPRGERAL',$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,'ISPRGERAL');
  foreach($RS as $row){$RS=$row; break;}
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<INPUT type="hidden" name="w_cd_programa" value="'.f($RS,'cd_programa').'">');
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
    $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,'ISPRGERAL');
    foreach($RS as $row){$RS=$row; break;}
    $w_tramite      = f($RS,'sq_siw_tramite');
    $w_novo_tramite = f($RS,'sq_siw_tramite');
  } 
  // Recupera a sigla do trâmite desejado, para verificar a lista de possíveis destinatários.
  $RS = db_getTramiteData::getInstanceOf($dbms,$w_novo_tramite);
  foreach($RS as $row) {
    $w_sg_tramite = f($row,'sigla');
  }
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
  ShowHTML(VisualPrograma($w_chave,'V',$w_usuario,$P1,$P4,'','','','','','','','','','',''));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'ISPRENVIO',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite.'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  if ($P1!=1) {
    // Se não for cadastramento
    SelecaoFase('<u>F</u>ase do programa:','F','Se deseja alterar a fase atual do programa, selecione a fase para a qual deseja enviá-la.',$w_novo_tramite,$w_menu,'w_novo_tramite',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
    // Se for envio para o cadastramento, exibe apenas as pessoas autorizadas a fazê-lo.
    if ($w_sg_tramite=='CI') {
      SelecaoSolicResp('<u>D</u>estinatário:','D','Selecione, na relação, um destinatário para o programa.',$w_destinatario,$w_chave,$w_novo_tramite,$w_novo_tramite,'w_destinatario','CADASTRAMENTO');
    } else {
      SelecaoPessoa('<u>D</u>estinatário:','D','Selecione, na relação, um destinatário para o programa.',$w_destinatario,null,'w_destinatario','USUARIOS');
    } 
  } else {
    SelecaoFase('<u>F</u>ase do programa:','F','Se deseja alterar a fase atual do programa, selecione a fase para a qual deseja enviá-la.',$w_novo_tramite,$w_menu,'w_novo_tramite',null,null);
    SelecaoPessoa('<u>D</u>estinatário:','D','Selecione, na relação, um destinatário para o programa.',$w_destinatario,null,'w_destinatario', 'USUARIOS');
  } 
  ShowHTML('    <tr><td valign="top" colspan=2><b>D<u>e</u>spacho:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Informe o que o destinatário deve fazer quando receber o programa.">'.$w_despacho.'</TEXTAREA></td>');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
  if ($P1!=1) {
    // Se não for cadastramento, volta para a listagem
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  } elseif ($P1==1 && $w_tipo=='Volta') {
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
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
  ShowHTML(VisualPrograma($w_chave,'V',$w_usuario,$P1,$P4,'','','','','','','','','','','',''));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'ISPRENVIO',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,'ISPRGERAL');
  foreach($RS as $row){$RS=$row; break;}
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
  ShowHTML('<meta http-equiv="Refresh" content="300; URL=../'.MontaURL('MESA').'">');
  if (!(strpos('V',$O)===false)) {
    ScriptOpen('JavaScript');
    checkbranco();
    FormataData();
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
  ShowHTML(VisualPrograma($w_chave,'V',$w_usuario,$P1,$P4,'','','','','','','','','','',''));
  ShowHTML('<HR>');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
  ShowHTML('          <tr>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'ISPRCONC',$w_pagina.par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_concluida" value="S">');
  $RS = db_getSolicData_IS::getInstanceOf($dbms,$w_chave,'ISPRGERAL');
  foreach($RS as $row){$RS=$row; break;}
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  if (Nvl(f($RS,'cd_programa'),'')>'') {
    ShowHTML('              <td valign="top"><b>Iní<u>c</u>io da execução:</b><br><input readonly '.$w_Disabled.' accesskey="C" type="text" name="w_inicio_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_inicio_real,'01/01/'.$w_ano).'" onKeyDown="FormataData(this,event);" title="Informe a data de início da execução do programa.(Usar formato dd/mm/aaaa)"></td>');
    ShowHTML('              <td valign="top"><b><u>T</u>érmino da execução:</b><br><input readonly '.$w_Disabled.' accesskey="T" type="text" name="w_fim_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_fim_real,'31/12/'.$w_ano).'" onKeyDown="FormataData(this,event);" title="Informe a data de término da execução do programa.(Usar formato dd/mm/aaaa)"></td>');
  } else {
    ShowHTML('              <td valign="top"><b>Iní<u>c</u>io da execução:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="'.Nvl($w_inicio_real,'01/01/'.$w_ano).'" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio_real.'" onKeyDown="FormataData(this,event);" title="Informe a data de início da execução do programa.(Usar formato dd/mm/aaaa)"></td>');
    ShowHTML('              <td valign="top"><b><u>T</u>érmino da execução:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="'.Nvl($w_fim_real,'31/12/'.$w_ano).'" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim_real.'" onKeyDown="FormataData(this,event);" title="Informe a data de término da execução do programa.(Usar formato dd/mm/aaaa)"></td>');
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
// Gera uma linha de apresentação da tabela de etapas
// -------------------------------------------------------------------------
function Indicadorlinha($l_chave,$l_chave_aux,$l_titulo,$l_apuracao,$l_indice,$l_word,$l_destaque,$l_oper,$l_tipo,$l_loa) {
  extract($GLOBALS);
  global $w_Disabled;
  if ($l_loa=='S') $l_loa='Sim'; else $l_loa='Não';
  $l_row='';
  $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
  $l_html.=chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top">';
  $l_html.=chr(13).'        <td nowrap '.$l_row.'>';
  if (Nvl($l_word,0)==1) $l_html.=chr(13).'        <td>'.$l_destaque.$l_titulo.'</b>';
  else                          $l_html.=chr(13).'<A class="HL" HREF="#" onClick="window.open(\''.montaURL_JS($w_dir,'programa.php?par=AtualizaIndicador&O=V&w_chave='.f($RS,'sq_siw_solicitacao').'&w_chave_aux='.$l_chave_aux.'&w_tipo=Volta&P1=10&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\',\'Indicador\',\'width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no\'); return false;" title="Clique para exibir os dados!">'.$l_destaque.$l_titulo.'</A>';
  $l_html.=chr(13).'        <td align="center" '.$l_row.'>'.$l_loa.'</td>';
  $l_html.=chr(13).'        <td align="center" '.$l_row.'>'.Nvl($FormataDataEdicao[$l_apuracao],'---').'</td>';
  $l_html.=chr(13).'        <td nowrap align="right" '.$l_row.'>'.Nvl($l_indice,'---').' %</td>';
  if ($l_oper=='S') {
    $l_html.=chr(13).'        <td align="top" nowrap '.$l_row.'>';
    // Se for listagem de indicadores no cadastramento do programa, exibe operações de alteração e exclusão
    if ($l_tipo=='PROJETO') {
      $l_html.=chr(13).'          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Alterar">Alt</A>&nbsp';
      if ((strtoupper(substr($l_titulo,0,13))==strtoupper('NAO INFORMADO')) || (strtoupper(substr($l_titulo,0,13))!=strtoupper('NAO INFORMADO') && $l_loa=='Não')) $l_html.=chr(13).'          <A class="HL" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');" title="Excluir">Excl</A>&nbsp';
       // Caso contrário, é listagem de atualização do indicador. Neste caso, coloca apenas a opção de alteração
    } else {
      $l_html.=chr(13).'          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Atualiza dados do indicador">Atualizar</A>&nbsp';
    } 
    $l_html.=chr(13).'        </td>';
  } else {
    if ($l_tipo=='ETAPA') {
      $l_html.=chr(13).'        <td align="top" nowrap '.$l_row.'>';
      $l_html.=chr(13).'          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=V&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Atualiza dados do indicador">Exibir</A>&nbsp';
      $l_html.=chr(13).'        </td>';
    } 
  } 
  $l_html.=chr(13).'      </tr>';
  return $l_html;
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
  $RSM = db_getSolicData_IS::getInstanceOf($dbms,$p_solic,'ISPRGERAL');
  foreach($RSM as $row){$RSM=$row; break;}
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
  $w_html.=$crlf.'          <td>Data de recebimento:<br><b>'.$FormataDataEdicao[f($RSM,'inicio')].' </b></td>';
  $w_html.=$crlf.'          <td>Limite para conclusão:<br><b>'.$FormataDataEdicao[f($RSM,'fim')].' </b></td>';
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
    $RS = SortArray($RS,'data desc','asc');
    foreach($RS as $row){$RS=$row; break;}
    $w_html.=$crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>ÚLTIMO ENCAMINHAMENTO</td>';
    $w_html.=$crlf.'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    $w_html.=$crlf.'          <tr valign="top">';
    $w_html.=$crlf.'          <td>De:<br><b>'.f($RS,'responsavel').'</b></td>';
    $w_html.=$crlf.'          <td>Para:<br><b>'.f($RS,'destinatario').'</b></td>';
    $w_html.=$crlf.'          <tr valign="top"><td colspan=2>Despacho:<br><b>'.CRLF2BR(Nvl(f($RS,'despacho'),'---')).' </b></td>';
    $w_html.=$crlf.'          </table>';
    // Configura o destinatário da tramitação como destinatário da mensagem
    $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,f($RS,'sq_pessoa_destinatario'),null,null);
    $w_destinatarios = f($RS,'email').'; ';
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
  // Recupera o e-mail do responsável
  $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,f($RSM,'solicitante'),null,null);
  if ((strpos($w_destinatarios,f($RS,'email').'; ')===false)) $w_destinatarios=$w_destinatarios.f($RS,'email').'; ';
  // Recupera o e-mail do titular e do substituto pelo setor responsável
  $RS = db_getUorgResp::getInstanceOf($dbms,f($RSM,'sq_unidade'));
  foreach($RS as $row){$RS=$row; break;}
  if ((strpos($w_destinatarios,f($RS,'email_titular').'; ')===false) && Nvl(f($RS,'email_titular'),'nulo')!='nulo') $w_destinatarios=$w_destinatarios.f($RS,'email_titular').'; ';
  if ((strpos($w_destinatarios,f($RS,'email_substituto').'; ')===false) && Nvl(f($RS,'email_substituto'),'nulo')!='nulo') $w_destinatarios=$w_destinatarios.f($RS,'email_substituto').'; ';
  // Recuperar o e-mail dos interessados
  $RS = db_getSolicInter::getInstanceOf($dbms,$p_solic,null,'LISTA');
  foreach($RS as $row) {
    if ((strpos($w_destinatarios,f($row,'email').'; ')===false)    && Nvl(f($row,'email'),'nulo')!='nulo' && f($row,'envia_email') =='S')    $w_destinatarios=$w_destinatarios.f($row,'email').'; ';
  }  
  // Prepara os dados necessários ao envio
  $RS = db_getCustomerData::getInstanceOf($dbms,$_REQUEST['p_cliente'.'_session']);
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
// =========================================================================
// Rotina de preparação para envio de e-mail relativo restrições
// Finalidade: preparar os dados necessários ao envio automático de e-mail
// Parâmetro: p_solic: número de identificação da solicitação. 
//            p_tipo:  I - Inclusão
//                     E - Exclusão
// -------------------------------------------------------------------------
function RestricaoMail($l_solic,$l_descricao,$l_tp_restricao,$l_providencia,$l_tipo) {
  extract($GLOBALS);
  $w_destinatarios  ='';
  $w_resultado      ='';
  $w_html='<HTML>'.$crlf;
  $w_html.=$BodyOpenMail[null].$crlf;
  $w_html.='<table border="0" cellpadding="0" cellspacing="0" width="100%">'.$crlf;
  $w_html.='<tr bgcolor="'.$conTrBgColor.'"><td align="center">'.$crlf;
  $w_html.='    <table width="97%" border="0">'.$crlf;
  if ($l_tipo=='I')     $w_html.='      <tr valign="top"><td align="center"><font size=2><b>INCLUSÃO DE RESTRIÇÃO</b></font><br><br><td></tr>'.$crlf;
  elseif ($l_tipo=='E') $w_html.='      <tr valign="top"><td align="center"><font size=2><b>EXCLUSÃO DE RESTRIÇÃO</b></font><br><br><td></tr>'.$crlf;
  $w_html.='      <tr valign="top"><td><font size=2><b><font color="#BC3131">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><td></tr>'.$crlf;
  // Recupera os dados do programa
  $RSM = db_getSolicData_IS::getInstanceOf($dbms,$l_solic,'ISPRGERAL');
  foreach($RSM as $row){$RSM=$row; break;}
  $w_html.=$crlf.'<tr bgcolor="'.$conTrBgColor.'"><td align="center">';
  $w_html.=$crlf.'    <table width="99%" border="0">';
  $w_html.=$crlf.'      <tr><td><font size=2>Programa: <b>'.f($RSM,'titulo').'</b></font></td>';
  // Identificação do programa
  $w_html.=$crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>EXTRATO DO PROGRAMA</td>';
  $w_html.=$crlf.'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
  $w_html.=$crlf.'          <tr valign="top">';
  $w_html.=$crlf.'          <td>Responsável pelo monitoramento:<br><b>'.f($RSM,'nm_sol').'</b></td>';
  $w_html.=$crlf.'          <td>Área de planejamento:<br><b>'.f($RSM,'nm_unidade_resp').'</b></td>';
  $w_html.=$crlf.'          <tr valign="top">';
  $w_html.=$crlf.'          <td>Data de recebimento:<br><b>'.$FormataDataEdicao[f($RSM,'inicio')].' </b></td>';
  $w_html.=$crlf.'          <td>Limite para conclusão:<br><b>'.$FormataDataEdicao[f($RSM,'fim')].' </b></td>';
  $w_html.=$crlf.'          </table>';
  // Recupera o e-mail do responsável
  $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,f($RSM,'solicitante'),null,null);
  if ((strpos($w_destinatarios,f($RS,'email').'; ')===false)) $w_destinatarios=$w_destinatarios.f($RS,'email').'; ';
  // Identificação da restrição
  $w_html.=$crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>EXTRATO DA RESTRIÇÃO</td>';
  $w_html.=$crlf.'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
  $w_html.=$crlf.'          <tr valign="top">';
  $w_html.=$crlf.'          <td>Descrição da restrição:<br><b>'.$l_descricao.'</b></td>';
  $RSM = db_getTPRestricao_IS::getInstanceOf($dbms,$l_tp_restricao,null);
  foreach($RSM as $row){$RSM=$row; break;}
  $w_html.=$crlf.'          <tr valign="top">';
  $w_html.=$crlf.'          <td>Tipo da restrição:<br><b>'.f($RSM,'nome').'</b></td>';
  $w_html.=$crlf.'          <tr valign="top">';
  $w_html.=$crlf.'          <td>Providência:<br><b>'.Nvl($l_providencia,'---').'</b></td>';
  $w_html.=$crlf.'          </table>';
  $w_html.=$crlf.'    </table>';
  $w_html.=$crlf.'</tr>';
  $w_html.=$crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>OUTRAS INFORMAÇÕES</td>';
  $RS = db_getCustomerSite::getInstanceOf($dbms,$w_cliente);
  $w_html.='      <tr valign="top"><td><font size=2>'.$crlf;
  $w_html.='         Para acessar o sistema use o endereço: <b><a class="SS" href="'.f($RS,'logradouro').'" target="_blank">'.f($RS,'Logradouro').'</a></b></li>'.$crlf;
  $w_html.='      </font></td></tr>'.$crlf;
  $w_html.='      <tr valign="top"><td><font size=2>'.$crlf;
  $w_html.='         Dados da ocorrência:<br>'.$crlf;
  $w_html.='         <ul>'.$crlf;
  $w_html.='         <li>Responsável: <b>'.$_REQUEST['nome'.'_session'].'</b></li>'.$crlf;
  $w_html .= '         <li>Data: <b>'.date('d/m/Y, H:i:s',$w_data_encaminhamento).'</b></li>'.$crlf;
  $w_html.='         <li>IP de origem: <b>'.$_SERVER['REMOTE_ADDR'].'</b></li>'.$crlf;
  $w_html.='         </ul>'.$crlf;
  $w_html.='      </font></td></tr>'.$crlf;
  $w_html.='    </table>'.$crlf;
  $w_html.='</td></tr>'.$crlf;
  $w_html.='</table>'.$crlf;
  $w_html.='</BODY>'.$crlf;
  $w_html.='</HTML>'.$crlf;
  // Recupera o e-mail do usuário que está cadastrando a restrição
  $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$_REQUEST['sq_pessoa'.'_session'],null,null);
  if ((strpos($w_destinatarios,f($RS,'email').'; ')===false)) $w_destinatarios=$w_destinatarios.f($RS,'email').'; ';
  // Recupera o e-mail dos interessados
  $RSM = db_getSolicInter::getInstanceOf($dbms,$l_solic,null,'LISTA');
  if (count($RS)) {
   foreach ($RSM as $row) {
    $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,f($RSM,'sq_pessoa'),null,null);
    if ((strpos($w_destinatarios,f($RS,'email').'; ')===false)) $w_destinatarios=$w_destinatarios.f($RS,'email').'; ';
   } 
  } 
  // Prepara os dados necessários ao envio
  $RS = db_getCustomerData::getInstanceOf($dbms,$_REQUEST['p_cliente'.'_session']);
  if ($l_tipo=='I') {
    // Inclusão
    $w_assunto='Inclusão de restrição do programa';
  } elseif ($l_tipo=='E') {
    // Exclusão
    $w_assunto='Exclusão de restrição do programa';
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
// Rotina de busca dos programas do PPA
// -------------------------------------------------------------------------
function BuscaPrograma() {
  extract($GLOBALS);
  $w_nome       = strtoupper($_REQUEST['w_nome']);
  $w_ano        = $_REQUEST['w_ano'];
  $ChaveAux     = $_REQUEST['ChaveAux'];
  $restricao    = $_REQUEST['restricao'];
  $campo        = $_REQUEST['campo'];
  if($restricao=='RELATORIO') $restricao = '';
  $RS = db_getProgramaPPA_IS::getInstanceOf($dbms,$ChaveAux,$w_cliente,$w_ano,$restricao,$w_nome,null,null);
  $RS = SortArray($RS,'ds_programa','asc');
  Cabecalho();
  ShowHTML('<TITLE>Seleção de programas do PPA</TITLE>');
  ShowHTML('<HEAD>');
  Estrutura_CSS(w_cliente);
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
  AbreForm('Form',$w_dir.$w_pagina.'BuscaPrograma','POST','return(Validacao(this))',null,$P1,$P2,$P3,$P4,$TP,$SG,null,null);
  ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
  ShowHTML('<INPUT type="hidden" name="w_ano" value="'.$w_ano.'">');
  ShowHTML('<INPUT type="hidden" name="restricao" value="'.$restricao.'">');
  ShowHTML('<INPUT type="hidden" name="campo" value="'.$campo.'">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2><b><ul>Instruções</b>:<li>Informe parte do nome do programa ou o código do programa.<li>Quando a relação for exibida, selecione o programa desejado clicando sobre o link <i>Selecionar</i>.<li>Após informar o nome do programa ou o código do programa, clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Cancelar</i>, a procura é cancelada.</ul></div>');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="100%" border="0">');
  ShowHTML('      <tr><td valign="top"><b>Parte do <U>n</U>ome do programa:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="w_nome" size="50" maxlength="100" value="'.$w_nome.'">');
  ShowHTML('      <tr><td valign="top"><b><U>C</U>ódigo do programa:<br><INPUT ACCESSKEY="S" '.$w_Disabled.' class="sti" type="text" name="ChaveAux" size="5" maxlength="4" value="'.$ChaveAux.'">');
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
        ShowHTML('            <td align="center">'.f($row,'cd_programa').'</td>');
        ShowHTML('            <td>'.f($row,'ds_programa').'</td>');
        ShowHTML('            <td><a class="ss" href="#" onClick="javascript:volta(\''.f($row,'cd_programa').'\');">Selecionar</a>');
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
  $w_file       = '';
  $w_tamanho    = '';
  $w_tipo       = '';
  $w_nome       = '';
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen(null);
  if ($SG=='ISPRGERAL' || $SG=='VLRPGERAL') {
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
      } else {
        if ($O=='I') {
          $RS = db_getPrograma_IS::getInstanceOf($dbms,$_REQUEST['w_cd_programa'],$w_ano,$w_cliente,null);
          foreach ($RS as $row){$RS=$row; break;}
          if (f($RS,'Existe')>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Programa já cadastrado!\');');
            ShowHTML('  history.back(1);');
            ScriptClose();
            exit;
          } 
        } 
        //Recupera 10  dos dias de prazo da tarefa, para emitir o alerta  
        $RS = db_get10PercentDays_IS::getInstanceOf($dbms,$_REQUEST['w_inicio'],$_REQUEST['w_fim']);
        foreach ($RS as $row){$RS=$row; break;}
        $w_dias = f($RS,'dias');
        if ($w_dias<1) $w_dias=1;
      } 
      dml_putAcaoGeral_IS::getInstanceOf($dbms,$O,
          $_REQUEST['w_chave'],$_REQUEST['w_menu'],$_SESSION['LOTACAO'],$_REQUEST['w_solicitante'],$_REQUEST['w_proponente'],
          $w_usuario,$_REQUEST['w_executor'],$_REQUEST['w_descricao'],$_REQUEST['w_justificativa'],$_REQUEST['w_inicio'],$_REQUEST['w_fim'],$_REQUEST['w_valor'],
          $_REQUEST['w_data_hora'],$_REQUEST['w_sq_unidade_resp'],$_REQUEST['w_titulo'],$_REQUEST['w_prioridade'],$_REQUEST['w_aviso'],$w_dias,
          $_REQUEST['w_cidade'],$_REQUEST['w_palavra_chave'],
          null,null,null,null,null,null,null,
          $w_ano,$w_cliente,$_REQUEST['w_cd_programa'],null,null,null,null,$_REQUEST['w_selecao_mp'],$_REQUEST['w_selecao_se'],
          $_REQUEST['w_sq_natureza'],$_REQUEST['w_sq_horizonte'],&$w_chave_nova,$w_copia,$_REQUEST['w_sq_unidade_adm'],$_REQUEST['w_ln_programa']);
      ScriptOpen('JavaScript');
      if ($O=='I') {
        // Exibe mensagem de gravação com sucesso
        ShowHTML('  alert(\'Programa '.$_REQUEST['w_cd_programa'].' cadastrado com sucesso!\');');
        // Recupera os dados para montagem correta do menu
        $RS1 = db_getMenuData::getInstanceOf($dbms,$w_menu);
        ShowHTML('  parent.menu.location=\''.montaURL_JS(null,$conRootSIW.'menu.php?par=ExibeDocs&O=A&w_chave='.$w_chave_nova.'&w_documento=Nr. '.$w_chave_nova.'&R='.$R.'&SG='.f($RS1,'sigla').'&TP='.$TP.MontaFiltro('GET')).'\';');
      } elseif ($O=='E') {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      } else {
        // Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
        if ($SG=='VLRPGERAL') $O='P';
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
  } elseif ($SG=='ISPRRESP') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putRespPrograma_IS::getInstanceOf($dbms,$_REQUEST['w_chave'],
          $_REQUEST['w_nm_gerente_programa'],$_REQUEST['w_fn_gerente_programa'],$_REQUEST['w_em_gerente_programa'],
          $_REQUEST['w_nm_gerente_executivo'],$_REQUEST['w_fn_gerente_executivo'],$_REQUEST['w_em_gerente_executivo'],
          $_REQUEST['w_nm_gerente_adjunto'],$_REQUEST['w_fn_gerente_adjunto'],$_REQUEST['w_em_gerente_adjunto']);
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
  } elseif ($SG=='ISPRPROQUA') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putProgQualitativa_IS::getInstanceOf($dbms,
        $_REQUEST['w_chave'],$_REQUEST['w_resultados'],$_REQUEST['w_observacoes'],$_REQUEST['w_potencialidades'],null,
        $_REQUEST['w_contribuicao_objetivo'],null,$_REQUEST['w_estrategia_monit'],$_REQUEST['w_diretriz'],
        $_REQUEST['w_metodologia_aval'],$SG);
      ScriptOpen('JavaScript');
      if ($O=='I') {
        // Recupera os dados para montagem correta do menu
        $RS1 = db_getMenuData($dbms,$w_menu);
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
  } elseif ($SG=='ISPRINDIC') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putIndicador_IS::getInstanceOf($dbms,$O,
        $_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$w_ano,$w_cliente,$_REQUEST['w_cd_programa'],$_REQUEST['w_cd_unidade_medida'],
        $_REQUEST['w_cd_periodicidade'],$_REQUEST['w_cd_base_geografica'],$_REQUEST['w_categoria_analise'],$_REQUEST['w_ordem'],$_REQUEST['w_titulo'],
        $_REQUEST['w_conceituacao'],$_REQUEST['w_interpretacao'],$_REQUEST['w_usos'],$_REQUEST['w_limitacoes'],$_REQUEST['w_comentarios'],
        $_REQUEST['w_fonte'],$_REQUEST['w_formula'],$_REQUEST['w_tipo_in'],$_REQUEST['w_indice_ref'],$_REQUEST['w_indice_apurado'],$_REQUEST['w_apuracao_ref'],$_REQUEST['w_apuracao_ind'],
        $_REQUEST['w_observacoes'],$_REQUEST['w_cumulativa'],$_REQUEST['w_quantidade'],Nvl($_REQUEST['w_exequivel'],'S'),$_REQUEST['w_situacao_atual'],
        $_REQUEST['w_justificativa_inex'],$_REQUEST['w_outras_medidas'],$_REQUEST['w_prev_ano_1'],$_REQUEST['w_prev_ano_2'],$_REQUEST['w_prev_ano_3'],$_REQUEST['w_prev_ano_4'],$P1);
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
  } elseif ($SG=='ISPRRESTR') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putRestricao_IS::getInstanceOf($dbms,$O,$SG,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],null,null,
        $_REQUEST['w_cd_tipo_restricao'],
        $_REQUEST['w_cd_tipo_inclusao'],$_REQUEST['w_cd_competencia'],$_REQUEST['w_superacao'],
        $_REQUEST['w_relatorio'],$_REQUEST['w_tempo_habil'],$_REQUEST['w_descricao'],
        $_REQUEST['w_providencia'],$_REQUEST['w_observacao_controle'],$_REQUEST['w_observacao_monitor'],$w_ano,$w_cliente);
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
  } elseif ($SG=='ISPRINTERE') {
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
  } elseif ($SG=='ISPRANEXO') {
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
  } elseif ($SG=='ISPRENVIO') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $RS = db_getSolicData_IS::getInstanceOf($dbms,$_REQUEST['w_chave'],'ISPRGERAL');
      foreach($RS as $row){$RS=$row; break;}
      if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATENÇÃO: Outro usuário já encaminhou esta ação para outra fase de execução!\');');
        ScriptClose();
      } else {
        dml_putProjetoEnvio::getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_novo_tramite'],'N',$_REQUEST['w_observacao'],$_REQUEST['w_destinatario'],$_REQUEST['w_despacho'],null,null,null,null);
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
          // Volta para a listagem
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
  } elseif ($SG=='ISPRCONC') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $RS = db_getSolicData_IS::getInstanceOf($dbms,$_REQUEST['w_chave'],'ISPRGERAL');
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
    case 'INICIAL':             Inicial();                      break;
    case 'GERAL':               Geral();                        break;
    case 'RESP':                Responsaveis();                 break;
    case 'PROGQUAL':            ProgramacaoQualitativa();       break;
    case 'INDICADOR':           Indicadores();                  break;
    case 'ATUALIZAINDICADOR':   AtualizaIndicador();            break;
    case 'RESTRICAO':           Restricoes();                   break;
    case 'INTERESS':            Interessados();                 break;
    case 'VISUAL':              Visual();                       break;
    case 'VISUALE':             VisualE();                      break;
    case 'EXCLUIR':             Excluir();                      break;
    case 'ENVIO':               Encaminhamento();               break;
    case 'ANEXO':               Anexos();                       break;
    case 'ANOTACAO':            Anotar();                       break;
    case 'CONCLUIR':            Concluir();                     break;
    case 'BUSCAPROGRAMA':       BuscaPrograma();                break;
    case 'RECURSOPROGRAMADO':   RecursoProgramado();            break;
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