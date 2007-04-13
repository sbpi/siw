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
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/db_getUorgResp.php');
include_once($w_dir_volta.'classes/sp/dml_putProgramaGeral.php');
include_once($w_dir_volta.'classes/sp/dml_putProgramaQualit.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicInter.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicArquivo.php');
include_once($w_dir_volta.'classes/sp/dml_putProgramaEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putProgramaConc.php');
include_once($w_dir_volta.'funcoes/selecaoPlanoEstrategico.php');
include_once($w_dir_volta.'funcoes/selecaoObjetivoEstrategico.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoFase.php');
include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');
include_once($w_dir_volta.'funcoes/selecaoNatureza.php');
include_once($w_dir_volta.'funcoes/selecaoHorizonte.php');
include_once($w_dir_volta.'funcoes/selecaoTipoInteressado.php');
include_once($w_dir_volta.'funcoes/montaTipoIndicador.php');
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
$w_dir          = 'mod_pe/';
$w_troca        = $_REQUEST['w_troca'];
$p_ordena       = strtolower($_REQUEST['p_ordena']);
$w_SG           = strtoupper($_REQUEST['w_SG']);
if (strpos($SG,'RESP')!==false || strpos($SG,'ANEXO')!==false) {
  if ($O!='I' && $O!='E' && nvl($_REQUEST['w_chave_aux'],$_REQUEST['w_sq_pessoa'])=='') $O='L';
} elseif (strpos($SG,'ENVIO')!==false) {
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
    $w_TP=$TP.' - Listagem';
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
$p_parcerias   = strtoupper($_REQUEST['p_parcerias']);
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
$p_internas      = strtoupper($_REQUEST['p_internas']);
$p_prazo        = strtoupper($_REQUEST['p_prazo']);
$p_fase         = explodeArray($_REQUEST['p_fase']);
$p_cd_programa  = strtoupper($_REQUEST['p_cd_programa']);
$p_ativo = strtoupper($_REQUEST['p_ativo']);
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
  $w_tipo=$_REQUEST['w_tipo'];
  if ($O=='L') {
    if (!(strpos(strtoupper($R),'GR_')===false)) {
      $w_filtro='';
      if ($p_prazo>'') $w_filtro=$w_filtro.' <tr valign="top"><td align="right">Data de término<td>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]';
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
      if ($p_parcerias>'')       $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Parcerias externas<td>[<b>'.$p_parcerias.'</b>]';
      if ($p_assunto>'')          $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Assunto <td>[<b>'.$p_assunto.'</b>]';
      if ($p_internas>'')          $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Parcerias internas <td>[<b>'.$p_internas.'</b>]';
      if ($p_ini_i>'')            $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Data recebimento <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
      if ($p_fim_i>'')            $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Limite conclusão <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]';
      if ($p_atraso=='S')         $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Situação <td>[<b>Apenas atrasadas</b>]';
      if ($p_ativo=='S')  $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Situação <td>[<b>Apenas programas com restrição</b>]';
      if ($w_filtro>'')           $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>';
    } 
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PEPROCAD');
    if ($w_copia>'') {
      // Se for cópia, aplica o filtro sobre todas as demandas visíveis pelo usuário
      $RS = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_parcerias,
          $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_internas, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, 
          null, null, $p_empenho, $p_processo);
    } else {      
      $RS = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P1,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_parcerias,
          $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_internas, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, 
          null, null, $p_empenho, $p_processo);
    } 
    if (Nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
     } else {
      $RS = SortArray($RS,'phpdt_fim','asc','prioridade','asc');
    }
  }
  if ($w_tipo=='WORD') {
    HeaderWord();
  } else {
    cabecalho();
    ShowHTML('<HEAD>');
    if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="300; URL=../'.MontaURL('MESA').'">');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de ações</TITLE>');
    ScriptOpen('Javascript');
    CheckBranco();
    FormataData();
    ValidateOpen('Validacao');
    if (!(strpos('CP',$O)===false)) {
      if ($P1!=1 || $O=='C') {
        // Se não for cadastramento ou se for cópia
        Validate('p_prazo','Dias para a data limite','','','1','2','','0123456789');
        Validate('p_parcerias','Proponente externo','','','2','90','1','');
        Validate('p_assunto','Assunto','','','2','90','1','1');
        Validate('p_internas','Palavras-chave','','','2','90','1','1');
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
        ShowHTML('<tr>');
        if ($w_tipo!='WORD') ShowHTML('    <td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.f($RS1,'sigla').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
        //ShowHTML '    <a accesskey=''C'' class=''SS'' href=''' & w_dir & w_pagina & par & '&R=' & w_pagina & par & '&O=C&P1=' & P1 & '&P2=' & P2 & '&P3=1&P4=' & P4 & '&TP=' & TP & '&SG=' & SG & MontaFiltro('GET') & '''><u>C</u>opiar</a>'
      } else {
        if ($w_tipo!='WORD') ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
      } 
    } 
    if ((strpos(strtoupper($R),'GR_')===false)) {
      if ($w_tipo!='WORD'){
        if ($w_copia>'') {
          // Se for cópia
          if (MontaFiltro('GET')>'') ShowHTML('                         <td><a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
          else                       ShowHTML('                         <td><a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
        } else {
          if (MontaFiltro('GET')>'')  ShowHTML('                         <td><a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
          else                        ShowHTML('                         <td><a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
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
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Programa','cd_programa').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Responsável','nm_solic').'</td>');
      if ($P1!=2) ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Usuário atual','cd_exec').'</td>');
      if ($P1==1 || $P1==2) {
        // Se for cadastramento ou mesa de trabalho
        ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Título','titulo').'</td>');
        ShowHTML('          <td colspan=2><b>Execução</td>');
        } else {
        ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Título','titulo').'</td>');
        ShowHTML('          <td colspan=2><b>Execução</td>');
        ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Fase atual','nm_tramite').'</td>');
      } 
      ShowHTML('          <td rowspan=2><b>Operações</td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>'.LinkOrdena('De','inicio').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Até','fim').'</td>');
      ShowHTML('        </tr>');
    } else {
      ShowHTML('          <td rowspan=2><b>Programa</td>');
      ShowHTML('          <td rowspan=2><b>Responsável</td>');
      if ($P1!=2) ShowHTML('          <td rowspan=2><b>Usuário atual</td>');
      if ($P1==1 || $P1==2) {
        // Se for cadastramento ou mesa de trabalho
        ShowHTML('          <td rowspan=2><b>Título</td>');
        ShowHTML('          <td colspan=2><b>Execução</td>');
      } else {
        ShowHTML('          <td rowspan=2><b>Título</td>');
        ShowHTML('          <td colspan=2><b>Execução</td>');
        ShowHTML('          <td rowspan=2><b>Fase atual</td>');
      } 
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>De</td>');
      ShowHTML('          <td><b>Até</td>');
      ShowHTML('        </tr>');
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
        ShowHTML(ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
        if ($w_tipo!='WORD') {
          ShowHTML('        <A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'cd_programa').'&nbsp;</a>');
          ShowHTML('        <td>'.ExibePessoa('../',$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_solic')).'</A></td>');
        } else {
          ShowHTML('        '.f($row,'cd_programa').'');
          ShowHTML('        <td>'.f($row,'nm_solic').'</td>');
        } 
        if ($P1!=2) {
          // Se for mesa de trabalho, não exibe o executor, pois já é o usuário logado
          if (Nvl(f($row,'nm_exec'),'---')>'---') {
            if ($w_tipo!='WORD') ShowHTML('        <td>'.ExibePessoa('../',$w_cliente,f($row,'executor'),$TP,f($row,'nm_exec')).'</td>');
            else                 ShowHTML('        <td>'.f($row,'nm_exec').'</td>');
          } else                                    ShowHTML('        <td>---</td>');
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
          ShowHTML('        <td nowrap>'.f($row,'nm_tramite').'</td>');
        } 
        if ($P1!=3 && $P1!=5) {
          if ($w_tipo!='WORD') {
            ShowHTML('        <td align="top" nowrap>');
            // Se não for acompanhamento
            if ($w_copia>'') {
              // Se for listagem para cópia
              $RS1 = db_getLinkSubMenu::getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);
              //ShowHTML '          <a accesskey=''I'' class=''HL'' href=''' & w_dir & w_pagina & 'Geral&R=' & w_pagina & par & '&O=I&SG=' & RS1('sigla') & '&w_menu=' & w_menu & '&P1=' & P1 & '&P2=' & P2 & '&P3=' & P3 & '&P4=' & P4 & '&TP=' & TP & '&w_copia=' & RS('sq_siw_solicitacao') & MontaFiltro('GET') & '''>Copiar</a>&nbsp;'
            } elseif ($P1==1) {
              // Se for cadastramento
              if ($w_submenu>'') ShowHTML('          <A class="HL" HREF="menu.php?par=ExibeDocs&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&R='.$w_pagina.$par.'&SG='.$SG.'&TP='.$TP.'&w_documento=Programa '.f($row,'cd_programa').MontaFiltro('GET').'" title="Altera as informações cadastrais do programa" TARGET="menu">Alterar</a>&nbsp;');
              else               ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informações cadastrais do programa">Alterar</A>&nbsp');
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclusão de programa.">Excluir</A>&nbsp');
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Encaminhamento do programa.">Enviar</A>&nbsp');
            } elseif ($P1==2 || $P1==6) {
              // Se for execução
              if ($w_usuario==f($row,'executor')) {
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
                if (RetornaGestor(f($row,'sq_siw_solicitacao'),$w_usuario)=='S') {
                  ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a ação para outro responsável.">Enviar</A>&nbsp');
                } else {
                  ShowHTML('          ---&nbsp');
                }
              } 
            } 
          }
        } else {
          if ($w_tipo!='WORD'){
            if (Nvl(f($row,'solicitante'),0) == $w_usuario || 
                Nvl(f($row,'titular'),0)     == $w_usuario || 
                Nvl(f($row,'substituto'),0)  == $w_usuario || 
                Nvl(f($row,'tit_exec'),0)    == $w_usuario || 
                Nvl(f($row,'subst_exec'),0)  == $w_usuario) {
              // Se o usuário for responsável por uma ação, titular/substituto do setor responsável 
              // ou titular/substituto da unidade executora,
              // pode enviar.
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a ação para outro responsável.">Enviar</A>&nbsp');
            } 
          }
        } 
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($w_tipo!='WORD'){
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
      ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
      ShowHTML('      <tr>');
      $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PEPROCAD');
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
      ShowHTML('          <td valign="top"><b>Propo<U>n</U>ente externo:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_parcerias" size="25" maxlength="90" value="'.$p_parcerias.'"></td>');
      ShowHTML('      <tr>');
      ShowHTML('          <td valign="top"><b>A<U>s</U>sunto:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_assunto" size="25" maxlength="90" value="'.$p_assunto.'"></td>');
      ShowHTML('          <td valign="top" colspan=2><b>Pala<U>v</U>ras-chave:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_internas" size="25" maxlength="90" value="'.$p_internas.'"></td>');
      ShowHTML('      <tr>');
      ShowHTML('          <td valign="top"><b>Iní<u>c</u>io entre:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" title="Usar formato dd/mm/aaaa"></td>');
      ShowHTML('          <td valign="top"><b><u>T</u>érmino entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" title="Usar formato dd/mm/aaaa"></td>');
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
  $w_readonly   = '';
  $w_erro       = '';
  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_plano            = $_REQUEST['w_plano'];
    $w_objetivo         = $_REQUEST['w_objetivo'];
    $w_codigo           = $_REQUEST['w_codigo'];
    $w_codigo_atual     = $_REQUEST['w_codigo_atual'];
    $w_titulo           = $_REQUEST['w_titulo'];
    $w_sq_unidade       = $_REQUEST['w_sq_unidade'];
    $w_solicitante      = $_REQUEST['w_solicitante'];
    $w_unid_resp        = $_REQUEST['w_unid_resp'];
    $w_natureza         = $_REQUEST['w_natureza'];
    $w_horizonte        = $_REQUEST['w_horizonte'];
    $w_inicio           = $_REQUEST['w_inicio'];
    $w_fim              = $_REQUEST['w_fim'];
    $w_valor            = $_REQUEST['w_valor'];
    $w_parcerias        = $_REQUEST['w_parcerias'];
    $w_ln_programa      = $_REQUEST['w_ln_programa'];
    $w_aviso            = $_REQUEST['w_aviso'];
    $w_dias             = $_REQUEST['w_dias'];
  } else {
    if (!(strpos('AEV',$O)===false) || $w_copia>'') {
      // Recupera os dados da ação
      if ($w_copia>'') {
        $RS = db_getSolicData::getInstanceOf($dbms,$w_copia,$SG);
      } else {
        $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
      } 
      if (count($RS)>0) {
        $w_plano            = f($RS,'sq_plano');
        $w_objetivo         = f($RS,'sq_peobjetivo');
        $w_codigo           = f($RS,'cd_programa');
        $w_codigo_atual     = f($RS,'cd_programa');
        $w_titulo           = f($RS,'titulo');
        $w_sq_unidade       = f($RS,'sq_unidade');
        $w_solicitante      = f($RS,'solicitante');
        $w_unid_resp        = f($RS,'sq_unidade_resp');
        $w_natureza         = f($RS,'sq_penatureza');
        $w_horizonte        = f($RS,'sq_pehorizonte');
        $w_inicio           = formataDataEdicao(f($RS,'inicio'));
        $w_fim              = formataDataEdicao(f($RS,'fim'));
        $w_valor            = formatNumber(f($RS,'valor'));
        $w_parcerias        = f($RS,'palavra_chave');
        $w_ln_programa      = f($RS,'ln_programa');
        $w_aviso            = f($RS,'aviso_prox_conc');
        $w_dias             = f($RS,'dias_aviso');
      } 
    } 
  } 
  
  if (nvl($w_plano,'nulo')!='nulo') {
     $RS_Plano = db_getPlanoEstrategico::getInstanceOf($dbms,$w_cliente,$w_plano,null,null,null,null,null,'REGISTROS');
     foreach ($RS_Plano as $row) {$RS_Plano = $row; break;}
  }
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
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
    Validate('w_plano','Plano estratégico','SELECT',1,1,18,'','0123456789');
    Validate('w_objetivo','Objetivo estratégico','SELECT',1,1,18,'','0123456789');
    Validate('w_codigo','Código do programa','1',1,1,20,'1','1');
    Validate('w_titulo','Programa','1',1,5,100,'1','1');
    Validate('w_sq_unidade','Unidade executora','SELECT',1,1,18,'','0123456789');
    Validate('w_solicitante','Responsável monitoramento','SELECT',1,1,18,'','0123456789');
    Validate('w_unid_resp','Área monitoramento','SELECT',1,1,18,'','0123456789');
    Validate('w_natureza','Natureza','SELECT',1,1,18,'','0123456789');
    Validate('w_horizonte','Horizonte','SELECT',1,1,18,'','0123456789');
    Validate('w_inicio','Início previsto','DATA',1,10,10,'','0123456789/');
    Validate('w_fim','Fim previsto','DATA',1,10,10,'','0123456789/');
    CompData('w_inicio','Data de início','<=','w_fim','Data de término');
    if (nvl($w_plano,'nulo')!='nulo') {
      CompData('w_inicio','Data de início','>=',formataDataEdicao(f($RS_Plano,'inicio')),'início do plano ao qual está vinculado\r\n'.formataDataEdicao(f($RS_Plano,'inicio')).' a '.formataDataEdicao(f($RS_Plano,'fim')));
      CompData('w_fim','Data de término','<=',formataDataEdicao(f($RS_Plano,'fim')),'término do plano ao qual está vinculado\r\n'.formataDataEdicao(f($RS_Plano,'inicio')).' a '.formataDataEdicao(f($RS_Plano,'fim')));
    }
    Validate('w_valor','Valor previsto','VALOR','1',4,18,'','0123456789.,');
    Validate('w_parcerias','Parcerias externas','','',2,90,'1','1');
    Validate('w_ln_programa','Endereço na internet','','',11,120,'1','1');
    Validate('w_dias','Dias de alerta','1','',1,3,'','0123456789');
    ShowHTML('  if (theForm.w_aviso[0].checked) {');
    ShowHTML('     if (theForm.w_dias.value == \'\') {');
    ShowHTML('        alert(\'Informe a partir de quantos dias antes da data limite você deseja ser avisado de sua proximidade!\');');
    ShowHTML('        theForm.w_dias.focus();');
    ShowHTML('        return false;');
    ShowHTML('     }');
    ShowHTML('  }');
    ShowHTML('  else {');
    ShowHTML('     theForm.w_dias.value = \'\';');
    ShowHTML('  }');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('EV',$O)===false)) {
    BodyOpen('onLoad=\'this.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_codigo.focus()\';');
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
    ShowHTML('<INPUT type="hidden" name="w_codigo_atual" value="'.$w_codigo_atual.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
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
    ShowHTML('      <tr>');
    selecaoPlanoEstrategico('<u>P</u>lano estratégico:', 'P', 'Selecione o plano ao qual o programa está vinculado.', $w_plano, $w_chave, 'w_plano', 'ULTIMO', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_objetivo\'; document.Form.submit();"');
    ShowHTML('      <tr>');
    selecaoObjetivoEstrategico('<u>O</u>bjetivo estratégico:', 'P', 'Selecione o objetivo estratégico ao qual o programa está vinculado.', $w_objetivo, $w_plano, 'w_objetivo', 'ULTIMO', null);
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td><b>Código:</b><br><INPUT '.$w_Disabled.' class="STI" type="text" name="w_codigo" size="20" maxlength="20" value="'.$w_codigo.'" ></td>');
    ShowHTML('          <td colspan=2><b>Programa:</b><br><INPUT '.$w_Disabled.' class="STI" type="text" name="w_titulo" size="70" maxlength="100" value="'.$w_titulo.'" ></td>');
    ShowHTML('        <tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade executora:','U','Selecione a unidade administratriva responsável pela execução do programa.',$w_sq_unidade,null,'w_sq_unidade','EXECUCAO',null);
    ShowHTML('      <tr valign="top">');
    SelecaoPessoa('Respo<u>n</u>sável monitoramento:','N','Selecione o nome da pessoa responsável pelas informações no SISPLAM.',$w_solicitante,null,'w_solicitante','USUARIOS');
    SelecaoUnidade('<U>Á</U>rea monitoramento:','A','Selecione a unidade responsável pelo monitoramento deste programa.',$w_unid_resp,null,'w_unid_resp','PLANEJAMENTO',null);
    ShowHTML('        <tr valign="top">');
    SelecaoNatureza('Na<u>t</u>ureza:','T','Indique qual a natureza do programa com relação às suas ações.',$w_cliente,$w_natureza,'w_natureza',null,null);
    SelecaoHorizonte('<U>H</U>orizonte temporal:','H','Indique se o programa é contínuo ao longo do PPA ou se é apenas temporário.',$w_cliente,$w_horizonte,'w_horizonte',null,null);
    ShowHTML('        <tr valign="top">');
    ShowHTML('              <td valign="top"><b>Iní<u>c</u>io previsto:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio.'" onKeyDown="FormataData(this,event);" title="Usar formato dd/mm/aaaa">'.ExibeCalendario('Form','w_inicio').'</td>');
    ShowHTML('              <td valign="top"><b><u>F</u>im previsto:</b><br><input '.$w_Disabled.' accesskey="F" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" title="Usar formato dd/mm/aaaa">'.ExibeCalendario('Form','w_fim').'</td>');
    ShowHTML('              <td><b>Valo<u>r</u> previsto:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_valor" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor previsto para execução do programa, ou zero se não for o caso."></td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td><b>Parc<u>e</u>rias:<br><INPUT ACCESSKEY="E" '.$w_Disabled.' class="STI" type="text" name="w_parcerias" size="90" maxlength="90" value="'.$w_parcerias.'" title="Informar quais são os parceiros externos na execução do programa (campo opcional)."></td>');
    ShowHTML('      <tr><td><b>En<u>d</u>ereço internet:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="w_ln_programa" size="90" maxlength="120" value="'.$w_ln_programa.'" title="Se desejar, informe o link do programa na internet."></td>');
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><b>Alerta de atraso</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Os dados abaixo indicam como deve ser tratada a proximidade da data de término do programa.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td><table border="0" width="100%">');
    ShowHTML('          <tr valign="top">');
    MontaRadioNS('<b>Emite alerta?</b>',$w_aviso,'w_aviso');
    ShowHTML('              <td><b>Quantos <U>d</U>ias antes da data limite?<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="w_dias" size="3" maxlength="3" value="'.$w_dias.'" title="Número de dias para emissão do alerta de proximidade da data de término do programa."></td>');
    ShowHTML('          </table>');
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
    $w_chave            = $_REQUEST['w_chave'];
    $w_sq_menu          = $_REQUEST['w_sq_menu'];
    $w_descricao        = $_REQUEST['w_descricao'];
    $w_justificativa    = $_REQUEST['w_justificativa'];
    $w_publico_alvo     = $_REQUEST['w_publico_alvo'];
    $w_estrategia       = $_REQUEST['w_estrategia'];
    $w_observacao       = $_REQUEST['w_observacao'];
  } else {
    $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));
    if (count($RS)>0) {
      $w_sq_menu        = f($RS,'sq_menu');
      $w_descricao      = f($RS,'descricao');
      $w_justificativa  = f($RS,'justificativa');
      $w_publico_alvo   = f($RS,'publico_alvo');
      $w_estrategia     = f($RS,'estrategia');
      $w_observacao     = f($RS,'observacao');
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
    Validate('w_descricao','Objetivo','1','',5,2000,'1','1');
    Validate('w_justificativa','Justificativa','1','',5,2000,'1','1');
    Validate('w_publico_alvo','Publico alvo','1','',5,2000,'1','1');
    Validate('w_estrategia','Estratégia de implementação','1','',5,2000,'1','1');
    Validate('w_observacao','Observações','1','',5,2000,'1','1');
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
    BodyOpen('onLoad=\'document.Form.w_descricao.focus()\';');
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
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><font size="2"><b>Programação qualitativa</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Os dados deste bloco visam orientar os executores do programa.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td valign="top"><b><u>O</u>bjetivos:</b><br><textarea '.$w_Disabled.' accesskey="O" name="w_descricao" class="STI" ROWS=5 cols=75 title="Descreva de que forma a execução do programa vai contribuir para o alcance dos objetivos do programa.">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td valign="top"><b><u>J</u>ustificativa:</b><br><textarea '.$w_Disabled.' accesskey="J" name="w_justificativa" class="STI" ROWS=5 cols=75 title="Descreva quais são os principais pontos fortes (internos) e as principais oportunidades (externas) do programa.">'.$w_justificativa.'</TEXTAREA></td>');
    ShowHTML('      <tr><td valign="top"><b><u>P</u>úblico alvo:</b><br><textarea '.$w_Disabled.' accesskey="P" name="w_publico_alvo" class="STI" ROWS=5 cols=75 title="Descreva os atores que serão impactados pelo programa.">'.$w_publico_alvo.'</TEXTAREA></td>');
    ShowHTML('      <tr><td valign="top"><b><u>E</u>stratégia de implementação:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_estrategia" class="STI" ROWS=5 cols=75 title="Descreva a sistemática e as estratégias que serão adotadas para o monitoramento do programa, informando, inclusive as ferramentas que serão utilizadas.">'.$w_estrategia.'</TEXTAREA></td>');
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
// Rotina de interessados
// -------------------------------------------------------------------------
function Interessados() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_sq_pessoa        = $_REQUEST['w_sq_pessoa'];
  $w_sq_tipo_interes  = $_REQUEST['w_sq_tipo_interes'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_sq_pessoa        = $_REQUEST['w_sq_pessoa'];
    $w_sq_tipo_interes  = $_REQUEST['w_sq_tipo_interes'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getSolicInter::getInstanceOf($dbms,$w_chave,null,'LISTA');
    $RS = SortArray($RS,'nome','asc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados do endereço informado
    $RS = db_getSolicInter::getInstanceOf($dbms,$w_chave,$w_sq_pessoa,'REGISTRO');
    foreach($RS as $row){$RS=$row; break;}
    $w_nome                 = f($RS,'nome');
    $w_sq_tipo_interessado  = f($RS,'sq_tipo_interessado');
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_sq_pessoa','Pessoa','SELECT','1','1','18','','1');
      Validate('w_sq_tipo_interessado','Tipo de envolvimento','SELECT','1','1','18','','1');
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
    BodyOpen('onLoad=\'document.Form.w_sq_pessoa.focus()\';');
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
      ShowHTML('<tr><td colspan=3 align="center" bgcolor="#FAEBD7"><table border=1 width="100%"><tr><td>');
      ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('        <tr valign="top">');
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
    ShowHTML('          <td><b>Tipo de envolvimento</td>');
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
        ShowHTML('        <td>'.f($row,'nm_tipo_interessado').'</td>');
        if ($P1!=4) {
          ShowHTML('        <td align="top" nowrap>');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_sq_pessoa='.f($row,'sq_pessoa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_sq_pessoa='.f($row,'sq_pessoa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');">Excluir</A>&nbsp');
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
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    if ($O=='I') {
      SelecaoPessoa('<u>P</u>essoa:','N','Selecione a pessoa que está envolvida na execução do programa.',$w_sq_pessoa,$w_chave,'w_sq_pessoa','INTERES');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_sq_pessoa" value="'.$w_sq_pessoa.'">');
      ShowHTML('      <tr><td valign="top"><b>Pessoa:</b><br>'.$w_nome.'</td>');
    } 
    SelecaoTipoInteressado('<u>T</u>ipo de envolvimento:','T','Selecione o tipo de envolvimento.',$w_sq_tipo_interessado,f($RS_Menu,'sq_menu'),'w_sq_tipo_interessado',null);
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
// Rotina de visualização do novo layout de relatórios
// -------------------------------------------------------------------------
function Visual($w_chave=null,$w_o=null,$w_usuario=null,$w_p1=null,$w_tipo=null,$w_identificacao=null,$w_responsavel=null,
    $w_qualitativa=null,$w_orcamentaria=null,$w_indicador=null,$w_recurso=null,$w_interessado=null,$w_anexo=null,
    $w_meta=null,$w_ocorrencia=null,$w_consulta=null) {
  extract($GLOBALS);
  $w_chave    = nvl($w_chave,$_REQUEST['w_chave']);
  $w_tipo     = nvl($w_tipo,strtoupper(trim($_REQUEST['w_tipo'])));
  $w_formato  = nvl($w_formato,strtoupper(trim($_REQUEST['w_formato'])));
  if ($O=='T') {
    $w_identificacao    = strtoupper(nvl($w_identificacao,'S'));
    $w_responsavel      = strtoupper(nvl($w_responsavel,'S'));
    $w_qualitativa      = strtoupper(nvl($w_qualitativa,'S'));
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
    $w_qualitativa      = strtoupper(nvl($w_qualitativa,'N'));
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
  if (nvl($w_tipo,'')!='' && $w_formato!='WORD') ShowHTML('<tr><td colspan="2" ><div align="center"><font size="1"><b>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></div></td></tr>');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML(VisualPrograma($w_chave,$w_o,$w_usuario,$w_p1,$w_formato,$w_identificacao,$w_responsavel,$w_qualitativa,$w_orcamentaria,$w_indicador,$w_recurso,$w_interessado,$w_anexo,$w_meta,$w_ocorrencia,$w_consulta));
  ShowHTML('</table>');
  if (nvl($w_tipo,'')!='' && $w_formato!='WORD') ShowHTML('<tr><td colspan="2" ><div align="center"><font size="1"><b>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></div></td></tr>');
} 
// =========================================================================
// Rotina de exclusão
// -------------------------------------------------------------------------
function Excluir() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  // Se for recarga da página
  if ($w_troca>'') $w_observacao = $_REQUEST['w_observacao'];
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
  if ($w_troca > '')  BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  else               BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML(VisualPrograma($w_chave,'V',$w_usuario,$P1,$P4,'S','N','N','N','N','N','N','N','N','N','N'));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'PEPRGERAL',$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));
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
    $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));
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
  ShowHTML(VisualPrograma($w_chave,'V',$w_usuario,$P1,$P4,'S','N','N','N','N','N','N','N','N','N','N'));
  ShowHTML('</table>');
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'PEPRENVIO',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite.'">');
  ShowHTML('<INPUT type="hidden" name="w_tipo" value="'.$w_tipo.'">');
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
  // Se a tela foi chamada a partir da listagem
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
  ShowHTML(VisualPrograma($w_chave,'V',$w_usuario,$P1,$P4,'S','N','N','N','N','N','N','N','N','N','N'));
  ShowHTML('</table>');
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'PEPRENVIO',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));
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
  ShowHTML(VisualPrograma($w_chave,'V',$w_usuario,$P1,$P4,'S','N','N','N','N','N','N','N','N','N','N'));
  ShowHTML('</table>');
  ShowHTML('<HR>');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
  ShowHTML('          <tr>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'PEPRCONC',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_concluida" value="S">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('              <td valign="top"><b>Iní<u>c</u>io da execução:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio_real" class="STI" SIZE="10" MAXLENGTH="10" onKeyDown="FormataData(this,event);" value="'.Nvl($w_inicio_real,formataDataEdicao(f($RS,'inicio'))).'" title="Informe a data de início da execução do programa.(Usar formato dd/mm/aaaa)">'.ExibeCalendario('Form','w_inicio_real').'</td>');
  ShowHTML('              <td valign="top"><b><u>T</u>érmino da execução:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim_real" class="STI" SIZE="10" MAXLENGTH="10" onKeyDown="FormataData(this,event);" value="'.Nvl($w_fim_real,formataDataEdicao(f($RS,'fim'))).'" title="Informe a data de término da execução do programa.(Usar formato dd/mm/aaaa)">'.ExibeCalendario('Form','w_fim_real').'</td>');
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
  else                   $l_html.=chr(13).'<A class="HL" HREF="#" onClick="window.open(\''.montaURL_JS($w_dir,'programa.php?par=AtualizaIndicador&O=V&w_chave='.f($RS,'sq_siw_solicitacao').'&w_chave_aux='.$l_chave_aux.'&w_tipo=Volta&P1=10&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\',\'Indicador\',\'width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no\'); return false;" title="Clique para exibir os dados!">'.$l_destaque.$l_titulo.'</A>';
  $l_html.=chr(13).'        <td align="center" '.$l_row.'>'.$l_loa.'</td>';
  $l_html.=chr(13).'        <td align="center" '.$l_row.'>'.Nvl(formataDataEdicao($l_apuracao),'---').'</td>';
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
  $w_html.=$crlf.'<table width="95%" border="0" cellspacing="3">';
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  $w_html.=$crlf. VisualPrograma($w_chave,'V',$w_usuario,$P1,$P4,'S','N','N','N','N','N','N','N','N','N','N');
  $w_html.=$crlf.'</table>';
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
  if ($SG=='PEPRGERAL') {
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
      dml_putProgramaGeral::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$w_copia,$_REQUEST['w_menu'],
          $_REQUEST['w_objetivo'],$_REQUEST['w_codigo'],$_REQUEST['w_titulo'],$_REQUEST['w_sq_unidade'],
          $_REQUEST['w_solicitante'],$_REQUEST['w_unid_resp'],$_REQUEST['w_horizonte'],$_REQUEST['w_natureza'],
          $_REQUEST['w_inicio'],$_REQUEST['w_fim'],$_REQUEST['w_parcerias'],$_REQUEST['w_ln_programa'],
          $_SESSION['SQ_PESSOA'],null,$_REQUEST['w_solic_pai'],$_REQUEST['w_valor'],$_REQUEST['w_data_hora'],
          $_REQUEST['w_aviso'],$_REQUEST['w_dias'],&$w_chave_nova);

      ScriptOpen('JavaScript');
      if ($O=='I' || $_REQUEST['w_codigo']!=$_REQUEST['w_codigo_atual']) {
        // Exibe mensagem de gravação com sucesso
        if ($_REQUEST['w_codigo_atual']=='') {
          ShowHTML('  alert(\'Programa '.$_REQUEST['w_codigo'].' cadastrado com sucesso!\');');
        } else {
          $TP = removeTP($TP);
        }
        // Recupera os dados para montagem correta do menu
        $RS1 = db_getMenuData::getInstanceOf($dbms,$w_menu);
        ShowHTML('  parent.menu.location=\''.montaURL_JS(null,$conRootSIW.'menu.php?par=ExibeDocs&O=A&w_chave='.$w_chave_nova.'&w_documento=Programa '.$_REQUEST['w_codigo'].'&R='.$R.'&SG='.f($RS1,'sigla').'&TP='.$TP.MontaFiltro('GET')).'\';');
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
  } elseif ($SG=='PEQUALIT') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putProgramaQualit::getInstanceOf($dbms,
        $_REQUEST['w_chave'],$_REQUEST['w_descricao'],$_REQUEST['w_justificativa'], $_REQUEST['w_publico_alvo'],
        $_REQUEST['w_estrategia'],$_REQUEST['w_observacao']);
      ScriptOpen('JavaScript');
      // Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
      $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,$SG);
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS1,'link').'&O='.$O.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (strpos($SG,'RESP')!==false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putSolicInter::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_sq_pessoa'],$_REQUEST['w_sq_tipo_interessado']);
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
  } elseif ($SG=='PEPRANEXO') {
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
  } elseif ($SG=='PEPRENVIO') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],f($RS_Menu,'sigla'));
      if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATENÇÃO: Outro usuário já encaminhou esta ação para outra fase de execução!\');');
        ScriptClose();
      } else {
        dml_putProgramaEnvio::getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_novo_tramite'],'N',$_REQUEST['w_observacao'],$_REQUEST['w_destinatario'],$_REQUEST['w_despacho'],null,null,null,null);
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
  } elseif ($SG=='PEPRCONC') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],f($RS_Menu,'sigla'));
      if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATENÇÃO: Outro usuário já encaminhou esta ação para outra fase de execução!\');');
        ScriptClose();
      } else {
        dml_putProgramaConc::getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_inicio_real'],$_REQUEST['w_fim_real'],$_REQUEST['w_nota_conclusao'],$_REQUEST['w_custo_real']);
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
    case 'QUALIT':              ProgramacaoQualitativa();       break;
    case 'RESP':                Interessados();                 break;
    case 'VISUAL':              Visual();                       break;
    case 'VISUALE':             VisualE();                      break;
    case 'EXCLUIR':             Excluir();                      break;
    case 'ENVIO':               Encaminhamento();               break;
    case 'ANEXO':               Anexos();                       break;
    case 'ANOTACAO':            Anotar();                       break;
    case 'CONCLUIR':            Concluir();                     break;
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