<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_get10PercentDays.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgResp.php');
include_once($w_dir_volta.'classes/sp/db_getEtapaOrder.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta.'classes/sp/db_getOrPrioridadeList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicRecurso.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/db_getFinancAcaoPPA.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAreas.php');
include_once($w_dir_volta.'classes/sp/db_getSolicInter.php');
include_once($w_dir_volta.'classes/sp/db_getSolicEtapa.php');
include_once($w_dir_volta.'classes/sp/db_getOrImport.php');
include_once($w_dir_volta.'classes/sp/db_getSolicEtpRec.php');
include_once($w_dir_volta.'classes/sp/db_getEtapaMensal.php');
include_once($w_dir_volta.'classes/sp/db_getEtapaDataParents.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getEtapaDataParents.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoInfo.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoOutras.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoGeral.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoFinancAcao.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoEtapa.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoInter.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoAreas.php');
include_once($w_dir_volta.'classes/sp/dml_putRespAcao.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicArquivo.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putAtualizaEtapa.php');
include_once($w_dir_volta.'classes/sp/dml_putEtapaMensal.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoConc.php');
include_once($w_dir_volta.'funcoes/selecaoOrPrioridade.php');
include_once($w_dir_volta.'funcoes/selecaoAcaoPPA_OR.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoTipoVisao.php');
include_once($w_dir_volta.'funcoes/selecaoFase.php');
include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
include_once('visualprojeto.php');
// =========================================================================
//  /projeto.php
// ------------------------------------------------------------------------
// Nome     : Billy Jones Leal dos Santos
// Descricao: Gerencia o módulo de projetos
// Mail     : billy@sbpi.com.br
// Criacao  : 14/09/2006 12:25
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
$par                      = strtoupper($_REQUEST['par']);
$P1                       = Nvl($_REQUEST['P1'],0);
$P2                       = Nvl($_REQUEST['P2'],0);
$P3                       = Nvl($_REQUEST['P3'],1);
$P4                       = Nvl($_REQUEST['P4'],$conPageSize);
$TP                       = $_REQUEST['TP'];
$SG                       = strtoupper($_REQUEST['SG']);
$R                        = strtolower($_REQUEST['R']);
$O                        = strtoupper($_REQUEST['O']); 
$w_assinatura             = strtoupper($_REQUEST['w_assinatura']);
$w_pagina                 = 'projeto.php?par=';
$w_dir                    = 'mod_or_pub/';
$w_Disabled               = 'ENABLED';
$w_troca                  = $_REQUEST['w_troca'];
$w_dir_volta              = '../';
$w_SG                     = strtoupper($_REQUEST['w_SG']);
$p_ordena                 = strtolower($_REQUEST['p_ordena']); 
if ($SG=='ORRECURSO' || $SG=='ORETAPA' || $SG=='ORINTERESS' || $SG=='ORAREAS' || $SG=='ORRESP' || $SG=='ORANEXO') {
    if ($O!='I' && $O!='E' && $_REQUEST['w_chave_aux']=='') $O='L';
} elseif ($SG=='ORENVIO') {
  $O='V';
} elseif ($SG=='ORFINANC' && $P1==5) {
  $O='L';
  $P1='';
} elseif ($O=='') {
  // Se for acompanhamento, entra na filtragem
  if ($P1==3) $O='P'; else  $O='L';
} 
switch ($O){
  case 'I':       $w_TP=$TP.' - Inclusão';          break;
  case 'A':       $w_TP=$TP.' - Alteração';         break;
  case 'E':       $w_TP=$TP.' - Exclusão';          break;
  case 'P':       $w_TP=$TP.' - Filtragem';         break;
  case 'C':       $w_TP=$TP.' - Cópia';             break;
  case 'V':       $w_TP=$TP.' - Envio';             break;
  case 'H':       $w_TP=$TP.' - Herança';           break;
  default:        $w_TP=$TP.' - Listagem';          break;
}
// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado. 
$w_cliente                = RetornaCliente();
$w_usuario                = RetornaUsuario();
$w_menu                   = RetornaMenu($w_cliente,$SG);
$w_copia                  = $_REQUEST['w_copia'];
$p_projeto                = strtoupper($_REQUEST['p_projeto']);
$p_atividade              = strtoupper($_REQUEST['p_atividade']);
$p_ativo                  = strtoupper($_REQUEST['p_ativo']);
$p_solicitante            = strtoupper($_REQUEST['p_solicitante']);
$p_prioridade             = strtoupper($_REQUEST['p_prioridade']);
$p_unidade                = strtoupper($_REQUEST['p_unidade']);
$p_proponente             = strtoupper($_REQUEST['p_proponente']);
$p_ini_i                  = strtoupper($_REQUEST['p_ini_i']);
$p_ini_f                  = strtoupper($_REQUEST['p_ini_f']);
$p_fim_i                  = strtoupper($_REQUEST['p_fim_i']);
$p_fim_f                  = strtoupper($_REQUEST['p_fim_f']);
$p_atraso                 = strtoupper($_REQUEST['p_atraso']);
$p_chave                  = strtoupper($_REQUEST['p_chave']);
$p_assunto                = strtoupper($_REQUEST['p_assunto']);
$p_pais                   = strtoupper($_REQUEST['p_pais']);
$p_regiao                 = strtoupper($_REQUEST['p_regiao']);
$p_uf                     = strtoupper($_REQUEST['p_uf']);
$p_cidade                 = strtoupper($_REQUEST['p_cidade']);
$p_usu_resp               = strtoupper($_REQUEST['p_usu_resp']);
$p_uorg_resp              = strtoupper($_REQUEST['p_uorg_resp']);
$p_palavra                = strtoupper($_REQUEST['p_palavra']);
$p_prazo                  = strtoupper($_REQUEST['p_prazo']);
$p_fase                   = strtoupper($_REQUEST['p_fase']);
$p_sqcc                   = strtoupper($_REQUEST['p_sqcc']);
$p_sq_acao_ppa            = strtoupper($_REQUEST['p_sq_acao_ppa']);
$p_sq_orprioridade        = strtoupper($_REQUEST['p_sq_orprioridade']);

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
if (f($RS_Menu,'ultimo_nivel')=='S') {
  // Se for sub-menu, pega a configuração do pai
  $RS_Menu  = db_getMenuData::getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
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
    if (!(strpos(strtoupper($R),'GR_')===false)){
      $w_filtro='';
      if ($p_projeto>'') {
        $RS = db_getSolicData::getInstanceOf($dbms,$p_projeto,'ORGERAL');
        foreach ($RS as $row){$RS=$row; break;}
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Ação <td>[<b>'.f($RS,'titulo').'</b>]';
      } 
      if ($p_atividade>'') {
        $RS = db_getSolicEtapa::getInstanceOf($dbms,$p_projeto,$p_atividade,'REGISTRO',null);
        foreach ($RS as $row){$RS=$row; break;}
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Etapa <td>[<b>'.f($RS,'titulo').'</b>]';
      } 
      if ($p_sq_acao_ppa>'') {
        $RS = db_getAcaoPPA::getInstanceOf($dbms,$p_sq_acao_ppa,$w_cliente,null,null,null,null,null,null,null,null,null);
        foreach ($RS as $row){$RS=$row; break;}
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Ação PPA <td>[<b>'.f($RS,'nome').' ('.f($RS,'codigo').')'.'</b>]';
      } 
      if ($p_sq_orprioridade>'') {
        $RS = db_getOrPrioridade::getInstanceOf($dbms,null,$w_cliente,$p_sq_orprioridade,null,null,null);
        foreach ($RS as $row){$RS=$row; break;}
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Iniciativa Prioritária <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_sqcc>'') {
        $RS = db_getCCData::getInstanceOf($dbms,$p_sqcc);
        foreach ($RS as $row){$RS=$row; break;}
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Classificação <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_chave>'') $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Demanda nº <td>[<b>'.$p_chave.'</b>]';
      if ($p_prazo>'') $w_filtro = $w_filtro.' <tr valign="top"><td align="right">Prazo para conclusão até<td>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]';
      if ($p_solicitante>'') {
        $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Responsável <td>[<b>'.f($RS,'nome_resumido').'</b>]';
      } 
      if ($p_unidade>''){
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
      if ($p_pais>'') {
        $RS = db_getCountryData::getInstanceOf($dbms,$p_pais);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">País <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_regiao>'') {
        $RS = db_getRegionData::getInstanceOf($dbms,$p_regiao);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Região <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_uf>'') {
        $RS = db_getStateData::getInstanceOf($dbms,$p_pais,$p_uf);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Estado <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_cidade>'') {
        $RS = db_getCityData::getInstanceOf($dbms,$p_cidade);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Cidade <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      //If p_prioridade  > ''  Then w_filtro = w_filtro & '<tr valign=''top''><td align=''right''>Prioridade <td>[<b>' & RetornaPrioridade(p_prioridade) & '</b>]'   End If
      if ($p_proponente>'') $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Parcerias externas<td>[<b>'.$p_proponente.'</b>]';
      if ($p_assunto>'')    $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Assunto <td>[<b>'.$p_assunto.'</b>]';
      if ($p_palavra>'')    $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Parcerias internas <td>[<b>'.$p_palavra.'</b>]';
      if ($p_ini_i>'')      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Data recebimento <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
      if ($p_fim_i>'')      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Limite conclusão <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]';
      if ($p_atraso=='S')   $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Situação <td>[<b>Apenas atrasadas</b>]';
      if ($w_filtro>'')     $w_filtro = '<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>';
    }   
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ORCAD');
    if ($w_copia>'') {
      // Se for cópia, aplica o filtro sobre todas as demandas visíveis pelo usuário
      $RS = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,$SG,3,
              $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
              $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
              $p_chave,$p_assunto,$p_pais,$p_regiao,$p_uf,$p_cidade,$p_usu_resp,
              $p_uorg_resp,$p_palavra,$p_prazo,$p_fase,$p_sqcc,$p_projeto,$p_atividade,$p_sq_acao_ppa,$p_sq_orprioridade);
    } else { 
      $RS = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,$SG,$P1,
              $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
              $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
              $p_chave,$p_assunto,$p_pais,$p_regiao,$p_uf,$p_cidade,$p_usu_resp,
              $p_uorg_resp,$p_palavra,$p_prazo,$p_fase,$p_sqcc,$p_projeto,$p_atividade,$p_sq_acao_ppa,$p_sq_orprioridade);   
      switch ($_REQUEST['p_agrega']) {
        case 'GRPRRESPATU':     $RS->Filter='executor <> null';          break;
      } 
    } 
    if (Nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {
      $RS = SortArray($RS,'fim','asc','prioridade','asc');
    }
  }
  Cabecalho();
  ShowHTML('<HEAD>');
  if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de ações</TITLE>');
  ScriptOpen('Javascript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  ValidateOpen('Validacao');
  if (!(strpos('CP',$O)===false)) {
    if ($P1!=1 || $O=='C') {
      // Se não for cadastramento ou se for cópia
      Validate('p_chave','Número da ação','','','1','18','','0123456789');
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
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
  } elseif ($O=='I') {
    BodyOpen('onLoad=\'document.Form.w_smtp_server.focus();\'');
  } elseif ($O=='A') {
    BodyOpen('onLoad=\'document.Form.w_nome.focus();\'');
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
    BodyOpen('onLoad=this.focus();');
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
        $RS1 = db_getLinkSubMenu::getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);
        foreach ($RS1 as $row) {$RS1=$row; break;}
        ShowHTML('<tr><td>');
        ShowHTML('    <a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.f($RS1,'sigla').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
        ShowHTML('    <a accesskey="C" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>C</u>opiar</a>');
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
    ShowHTML('          <td rowspan=2><b>Nº</td>');
    ShowHTML('          <td rowspan=2><b>Responsável</td>');
    ShowHTML('          <td rowspan=2><b>Executor</td>');
    if ($P1==1 || $P1==2) {
      // Se for cadastramento ou mesa de trabalho
      ShowHTML('          <td rowspan=2><b>Título</td>');
      ShowHTML('          <td colspan=2><b>Execução</td>');
    } else {
      ShowHTML('          <td rowspan=2><b>Parcerias</td>');
      ShowHTML('          <td rowspan=2><b>Título</td>');
      ShowHTML('          <td colspan=2><b>Execução</td>');
      ShowHTML('          <td rowspan=2><b>Valor</td>');
      ShowHTML('          <td rowspan=2><b>Fase atual</td>');
    } 
    ShowHTML('          <td rowspan=2><b>Operações</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>De</td>');
    ShowHTML('          <td><b>Até</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      $w_parcial = 0;
      foreach($RS1 as $row){  
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
        ShowHTML('        <A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'sq_siw_solicitacao').'&nbsp;</a>');
        ShowHTML('        <td>'.ExibePessoa('../',$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_solic')).'</A></td>');
        if (Nvl(f($row,'nm_exec'),'---')>'---') {
          ShowHTML('        <td>'.ExibePessoa('../',$w_cliente,f($row,'executor'),$TP,f($row,'nm_exec')).'</td>');
        } else {
          ShowHTML('        <td>---</td>');
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
          if (strlen(Nvl(f($row,'titulo'),'-'))>50) $w_titulo=substr(Nvl(f($row,'titulo'),'-'),0,50).'...'; else $w_titulo=Nvl(f($row,'titulo'),'-');
          if (f($row,'sg_tramite')=='CA') {
             ShowHTML('        <td title="'.str_replace('\r\n','\n',str_replace('"','"',str_replace('','"',f($row,'titulo')))).'"><strike>'.$w_titulo.'</strike></td>');
          } else {
            ShowHTML('        <td title="'.str_replace('\r\n','\n',str_replace('"','"',str_replace('','"',f($row,'titulo')))).'">'.$w_titulo.'</td>');
          } 
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
        if ($P1!=3) {
          // Se não for acompanhamento
          if ($w_copia>'') {
            // Se for listagem para cópia
            $RS2 = db_getLinkSubMenu::getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);
            foreach($RS2 as $row2){$RS2=$row2; break;}
            ShowHTML('          <a accesskey="I" class="HL" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.f($RS2,'sigla').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&w_copia='.f($row,'sq_siw_solicitacao').MontaFiltro('GET').'">Copiar</a>&nbsp;');
          } elseif ($P1==1) {
            // Se for cadastramento
            if ($w_submenu>'') {
              ShowHTML('          <A class="HL" HREF="menu.php?par=ExibeDocs&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&R='.$w_pagina.$par.'&SG='.$SG.'&TP='.$TP.'&w_documento=Nr. '.f($row,'sq_siw_solicitacao').MontaFiltro('GET').'" title="Altera as informações cadastrais da ação" TARGET="menu">AL</a>&nbsp;');
            } else {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informações cadastrais da ação">AL</A>&nbsp');
            } 
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclusão da ação.">EX</A>&nbsp');
          } elseif ($P1==2 || $P1==6) {
            // Se for execução
            if ($w_usuario==f($row,'executor')) {
              if (Nvl(f($row,'solicitante'),0)==$w_usuario || 
                  Nvl(f($row,'titular'),0)    ==$w_usuario || 
                  Nvl(f($row,'substituto'),0) ==$w_usuario || 
                  Nvl(f($row,'tit_exec'),0)   ==$w_usuario || 
                  Nvl(f($row,'executor'),0)   ==$w_usuario || 
                  Nvl(f($row,'subst_exec'),0) ==$w_usuario){
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'AtualizaEtapa&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Atualiza as metas físicas da ação." target="Metas">Metas</A>&nbsp');
              } 
              // Coloca as operações dependendo do trâmite
              if (f($row,'sg_tramite')=='EA' || f($row,'sg_tramite')=='EE') {
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anotações para a ação, sem enviá-la.">AN</A>&nbsp');
              } 
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a ação para outro responsável.">EN</A>&nbsp');
              if (f($row,'sg_tramite')=='EE') {
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Conclui a execução da ação.">CO</A>&nbsp');
              } 
            } else  {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'AtualizaEtapa&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Atualiza as metas físicas da ação." target="Metas">Metas</A>&nbsp');
              if (RetornaGestor(f($row,'sq_siw_solicitacao'),$w_usuario)=='S') {
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a ação para outro responsável.">EN</A>&nbsp');
              } else {
                ShowHTML('          ---&nbsp');
              }
            } 
          } 
        } else {
          if (Nvl(f($row,'solicitante'),0)==$w_usuario || 
              Nvl(f($row,'titular'),0)    ==$w_usuario || 
              Nvl(f($row,'substituto'),0) ==$w_usuario || 
              Nvl(f($row,'resp_etapa'),0)>0 || 
              Nvl(f($row,'tit_exec'),0)   ==$w_usuario || 
              Nvl(f($row,'subst_exec'),0) ==$w_usuario) {
            // Se o usuário for responsável por uma ação, titular/substituto do setor responsável 
            // ou titular/substituto da unidade executora,
            // pode enviar.
            if (Nvl(f($row,'solicitante'),0)==$w_usuario || 
                Nvl(f($row,'titular'),0)    ==$w_usuario || 
                Nvl(f($row,'substituto'),0) ==$w_usuario || 
                Nvl(f($row,'tit_exec'),0)   ==$w_usuario || 
                Nvl(f($row,'subst_exec'),0) ==$w_usuario) {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a ação para outro responsável.">EN</A>&nbsp');
            } 
          } 
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'AtualizaEtapa&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Atualiza as metas físicas da ação." target="Metas">Metas</A>&nbsp');
        } 
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
      if ($P1!=1 && $P1!=2) {
        // Se não for cadastramento nem mesa de trabalho
        // Coloca o valor parcial apenas se a listagem ocupar mais de uma página
        if ($RS->PageCount>1) {
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
          ShowHTML('          <td colspan=7 align="right"><b>Total desta página&nbsp;</td>');
          ShowHTML('          <td align="right"><b>'.number_format($w_parcial,2,',','.').'&nbsp;</td>');
          ShowHTML('          <td colspan=2>&nbsp;</td>');
          ShowHTML('        </tr>');
        } 
        // Se for a última página da listagem, soma e exibe o valor total
        if ($P3==$RS->PageCount) {
          foreach ($RS as $row){
            if (f($row,'sg_tramite')=='AT')    $w_total = $w_total + f($row,'custo_real');
            else                               $w_total = $w_total + f($row,'valor');
             
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
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia,$RS->PageCount,$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia,$RS->PageCount,$P3,$P4,count($RS));
    } 
    ShowHTML('</tr>');
  } elseif (!(strpos('CP',$O)===false)) {
    if ($P1!=1) {
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
     }  elseif ($O=='C') {
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
      $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ORCAD');
      SelecaoProjeto('Pro<u>j</u>eto:','J','Selecione a ação da atividade na relação.',$p_projeto,$w_usuario,f($RS,'sq_menu'),null,null,null,'p_projeto','ORLIST',null);
      DesconectaBD();
      ShowHTML('      </tr>');
      if (f($RS_Menu,'solicita_cc')=='S') {
        ShowHTML('      <tr>');
        SelecaoCC('C<u>l</u>assificação:','L','Selecione um dos itens relacionados.',$p_sqcc,null,'p_sqcc','SIWSOLIC');
        ShowHTML('      </tr>');
      } 
      ShowHTML('          </table>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td valign="top"><b>Número da <U>d</U>emanda:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="p_chave" size="18" maxlength="18" value="'.$p_chave.'"></td>');
      ShowHTML('          <td valign="top"><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="STI" type="text" name="p_prazo" size="2" maxlength="2" value="'.$p_prazo.'"></td>');
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Respo<u>n</u>sável:','N','Selecione o responsável pela ação na relação.',$p_solicitante,null,'p_solicitante','USUARIOS');
      SelecaoUnidade('<U>S</U>etor responsável:','S',null,$p_unidade,null,'p_unidade',null,null);
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Responsável atua<u>l</u>:','L','Selecione o responsável atual pela ação na relação.',$p_usu_resp,null,'p_usu_resp','USUARIOS');
      SelecaoUnidade('<U>S</U>etor atual:','S','Selecione a unidade onde a ação se encontra na relação.',$p_uorg_resp,null,'p_uorg_resp',null,null);
      // ShowHTML('      <tr>');
      // SelecaoPais('<u>P</u>aís:','P',null,$p_pais,null,'p_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_regiao\'; document.Form.submit();"');
      // SelecaoRegiao('<u>R</u>egião:','R',null,$p_regiao,$p_pais,'p_regiao',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
      // ShowHTML('      <tr>');
      // SelecaoEstado('E<u>s</u>tado:','S',null,$p_uf,$p_pais,$p_regiao,'p_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_cidade\'; document.Form.submit();"');
      // SelecaoCidade('<u>C</u>idade:','C',null,$p_cidade,$p_pais,$p_uf,'p_cidade',null,null);
      ShowHTML('      <tr>');
      //SelecaoPrioridade('<u>P</u>rioridade:','P','Informe a prioridade desta ação.',$p_prioridade,null,'p_prioridade',null,null);
      ShowHTML('          <td valign="top"><b>Propo<U>n</U>ente externo:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="'.$p_proponente.'"></td>');
      ShowHTML('      <tr>');
      ShowHTML('          <td valign="top"><b>A<U>s</U>sunto:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_assunto" size="25" maxlength="90" value="'.$p_assunto.'"></td>');
      ShowHTML('          <td valign="top" colspan=2><b>Pala<U>v</U>ras-chave:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_palavra" size="25" maxlength="90" value="'.$p_palavra.'"></td>');
      ShowHTML('      <tr>');
      ShowHTML('          <td valign="top"><b>Data de re<u>c</u>ebimento entre:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      ShowHTML('          <td valign="top"><b>Limi<u>t</u>e para conclusão entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      if ($O!='C') {
        // Se não for cópia
        ShowHTML('      <tr>');
        ShowHTML('          <td valign="top"><b>Exibe somente ações em atraso?</b><br>');
        if ($p_atraso=='S') {
          ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S" checked> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N"> Não');
        } else {      
          ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N" checked> Não');
        } 
        SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase',null,null);
      } 
    } 
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="STS" name="p_ordena">');
    if ($p_ordena=='ASSUNTO')         ShowHTML('          <option value="assunto" SELECTED>Assunto<option value="inicio">Data de recebimento<option value="">Data limite para conclusão<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente">Proponente externo');
    elseif ($p_ordena=='INICIO')      ShowHTML('          <option value="assunto">Assunto<option value="inicio" SELECTED>Data de recebimento<option value="">Data limite para conclusão<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente">Proponente externo');
    elseif ($p_ordena=='NM_TRAMITE')  ShowHTML('          <option value="assunto">Assunto<option value="inicio">Data de recebimento<option value="">Data limite para conclusão<option value="nm_tramite" SELECTED>Fase atual<option value="prioridade">Prioridade<option value="proponente">Proponente externo');
    elseif ($p_ordena=='PRIORIDADE')  ShowHTML('          <option value="assunto">Assunto<option value="inicio">Data de recebimento<option value="">Data limite para conclusão<option value="nm_tramite">Fase atual<option value="prioridade" SELECTED>Prioridade<option value="proponente">Proponente externo');
    elseif ($p_ordena=='PROPONENTE')  ShowHTML('          <option value="assunto">Assunto<option value="inicio">Data de recebimento<option value="">Data limite para conclusão<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente" SELECTED>Proponente externo');
    else                              ShowHTML('          <option value="assunto">Assunto<option value="inicio">Data de recebimento<option value="" SELECTED>Data limite para conclusão<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente">Proponente externo');
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
    $w_sq_orprioridade  = $_REQUEST['w_sq_orprioridade'];
    $w_descricao        = $_REQUEST['w_descricao'];
    $w_justificativa    = $_REQUEST['w_justificativa'];
    if ($w_sq_acao_ppa>'') {
      $RS = db_getAcaoPPA::getInstanceOf($dbms,$w_sq_acao_ppa,$w_cliente,null,null,null,null,null,null,null,null,null);
      foreach($RS as $row){$RS=$row; break;}
      $w_selecionada_mpog       = f($RS,'selecionada_mpog');
      $w_selecionada_relevante  = f($RS,'selecionada_relevante');
      $w_titulo                 = f($RS,'nome');
    } elseif ($w_sq_orprioridade>'') {
      $RS = db_getOrPrioridade::getInstanceOf($dbms,null,$w_cliente,$w_sq_orprioridade,null,null,null);
      foreach($RS as $row){$RS=$row; break;}
      $w_titulo = f($RS,'nome');
    } 
  } else {
    if ((!(strpos('AEV',$O)===false)) || $w_copia>'') {
      // Recupera os dados da ação
      if ($w_copia>'') {
        $RS = db_getSolicData::getInstanceOf($dbms,$w_copia,$SG);
      } else {
        $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
      } 
      if (count($RS)>0) {
        $w_proponente            = f($RS,'proponente');
        $w_sq_unidade_resp       = f($RS,'sq_unidade_resp');
        $w_titulo                = f($RS,'titulo');
        $w_prioridade            = f($RS,'prioridade');
        $w_aviso                 = f($RS,'aviso_prox_conc');
        $w_dias                  = f($RS,'dias_aviso');
        $w_inicio_real           = f($RS,'inicio_real');
        $w_fim_real              = f($RS,'fim_real');
        $w_concluida             = f($RS,'concluida');
        $w_data_conclusao        = f($RS,'data_conclusao');
        $w_nota_conclusao        = f($RS,'nota_conclusao');
        $w_custo_real            = f($RS,'custo_real');
        $w_chave_pai             = f($RS,'sq_solic_pai');
        $w_chave_aux             = null;
        $w_sq_menu               = f($RS,'sq_menu');
        $w_sq_unidade            = f($RS,'sq_unidade');
        $w_sq_tramite            = f($RS,'sq_siw_tramite');
        $w_solicitante           = f($RS,'solicitante');
        $w_cadastrador           = f($RS,'cadastrador');
        $w_executor              = f($RS,'executor');
        $w_inicio                = FormataDataEdicao(f($RS,'inicio'));
        $w_fim                   = FormataDataEdicao(f($RS,'fim'));
        $w_inclusao              = f($RS,'inclusao');
        $w_ultima_alteracao      = f($RS,'ultima_alteracao');
        $w_conclusao             = f($RS,'conclusao');
        $w_valor                 = number_format(f($RS,'valor'),2,',','.');
        $w_opiniao               = f($RS,'opiniao');
        $w_data_hora             = f($RS,'data_hora');
        $w_sqcc                  = f($RS,'sq_cc');
        $w_sq_acao_ppa           = f($RS,'sq_acao_ppa');
        $w_sq_acao_ppa_bd        = f($RS,'sq_acao_ppa');
        $w_sq_orprioridade       = f($RS,'sq_orprioridade');
        $w_selecionada_mpog      = f($RS,'mpog_ppa');
        $w_selecionada_relevante = f($RS,'relev_ppa');
        $w_pais                  = f($RS,'sq_pais');
        $w_uf                    = f($RS,'co_uf');
        $w_cidade                = f($RS,'sq_cidade_origem');
        $w_palavra_chave         = f($RS,'palavra_chave');
        $w_descricao             = f($RS,'descricao');
        $w_justificativa         = f($RS,'justificativa');
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
    //Validate 'w_titulo', 'Ação', '1', 1, 5, 100, '1', '1'
    if (f($RS_Menu,'solicita_cc')=='S') {
      Validate('w_sqcc','Classificação','SELECT',1,1,18,'','0123456789');
    } 
    //Validate 'w_sq_orprioridade', 'Iniciativa prioritária', 'SELECT', '', 1, 18, '', '0123456789'
    //Validate 'w_sq_acao_ppa', 'Ação PPA', 'SELECT', '', 1, 18, '', '0123456789'
    ShowHTML('  if (theForm.w_sq_acao_ppa.selectedIndex==0 && theForm.w_sq_orprioridade.selectedIndex==0) {');
    ShowHTML('     alert(\'Informe a iniciativa prioritária e/ou a ação do PPA!\');');
    ShowHTML('     theForm.w_sq_orprioridade.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    Validate('w_solicitante','Responsável monitoramento','HIDDEN',1,1,18,'','0123456789');
    Validate('w_sq_unidade_resp','Setor responsável','HIDDEN',1,1,18,'','0123456789');
    switch (f($RS_Menu,'data_hora')) {
      case 1:   Validate('w_fim','Fim previsto','DATA',1,10,10,'','0123456789/');                break;
      case 2:   Validate('w_fim','Fim previsto','DATAHORA',1,17,17,'','0123456789/');            break;
      case 3:   Validate('w_inicio','Início previsto','DATA',1,10,10,'','0123456789/');
                Validate('w_fim','Fim previsto','DATA',1,10,10,'','0123456789/');
                CompData('w_inicio','Data de recebimento','<=','w_fim','Limite para conclusão'); break;
      case 4:   Validate('w_inicio','Início previsto','DATAHORA',1,17,17,'','0123456789/,: ');
                Validate('w_fim','Fim previsto','DATAHORA',1,17,17,'','0123456789/,: ');
                CompData('w_inicio','Início previsto','<=','w_fim','Limite para conclusão');     break;
    } 
    Validate('w_valor','Recurso programado','VALOR','1',4,18,'','0123456789.,');
    Validate('w_proponente','Parcerias externas','','',2,90,'1','1');
    Validate('w_palavra_chave','Parcerias internas','','',2,90,'1','1');
    //Validate 'w_pais', 'País', 'SELECT', 1, 1, 18, '', '0123456789'
    //Validate 'w_uf', 'Estado', 'SELECT', 1, 1, 3, '1', '1'
    //Validate 'w_cidade', 'Cidade', 'SELECT', 1, 1, 18, '', '0123456789'
    //Validate 'w_dias', 'Dias de alerta', '1', '', 1, 3, '', '0123456789'
    //ShowHTML '  if (theForm.w_aviso[0].checked) {'
    //ShowHTML '     if (theForm.w_dias.value == ") {'
    //ShowHTML '        alert('Informe a partir de quantos dias antes da data limite você deseja ser avisado de sua proximidade!');'
    //ShowHTML '        theForm.w_dias.focus();'
    //ShowHTML '        return false;'
    //ShowHTML '     }'
    //ShowHTML '  }'
    //ShowHTML '  else {'
    //ShowHTML '     theForm.w_dias.value = ";'
    //ShowHTML '  }'
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    if($O=='A') BodyOpen(null);
    else        BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('EV',$O)===false)) {
    BodyOpen(null);
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
    if ($w_sq_acao_ppa>'') {
      ShowHTML('      <tr><td valign="top"><b><u>A</u>ção:</b><br><INPUT READONLY ACCESSKEY="A" '.$w_Disabled.' class="STI" type="text" name="w_titulo" size="90" maxlength="100" value="'.$w_titulo.'" ></td>');
    } else {
      ShowHTML('      <tr><td valign="top"><b><u>A</u>ção:</b><br><INPUT ACCESSKEY="A" '.$w_Disabled.' class="STI" type="text" name="w_titulo" size="90" maxlength="100" value="'.$w_titulo.'" ></td>');
    } 
    if (f($RS_Menu,'solicita_cc')=='S') {
      ShowHTML('          <tr>');
      SelecaoCC('C<u>l</u>assificação:','L','Selecione um dos itens relacionados.',$w_sqcc,null,'w_sqcc','SIWSOLIC');
      ShowHTML('          </tr>');
    } 
    ShowHTML('          <tr>');
    SelecaoOrPrioridade('<u>I</u>niciativa prioritária:','I',null,$w_sq_orprioridade,null,'w_sq_orprioridade','VINCULACAO','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_acao_ppa\'; document.Form.submit();"');
    ShowHTML('          </tr>');
    ShowHTML('          <tr>');
    if ($O=='I' || $w_sq_acao_ppa_bd=='') {
      selecaoAcaoPPA_OR('Ação <u>P</u>PA:','P',null,$w_sq_acao_ppa,null,'w_sq_acao_ppa','IDENTIFICACAO','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_solicitante\'; document.Form.submit();"');
    } else {
      selecaoAcaoPPA_OR('Ação <u>P</u>PA:','P',null,$w_sq_acao_ppa,null,'w_sq_acao_ppa',null,'disabled');
      ShowHTML('<INPUT type="hidden" name="w_sq_acao_ppa" value="'.$w_sq_acao_ppa.'">');
    } 
    ShowHTML('          </tr>');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');    
    MontaRadioNS('<b>Selecionada pelo MP?</b>',$w_selecionada_mpog,'w_selecionada_mpog');
    MontaRadioNS('<b>SE/MS?</b>',$w_selecionada_relevante,'w_selecionada_relevante');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    SelecaoPessoa('Respo<u>n</u>sável monitoramento:','N','Selecione o responsável pelo monitoramento da ação na relação.',$w_solicitante,null,'w_solicitante','USUARIOS');
    SelecaoUnidade('<U>S</U>etor responsável monitoramento:','S','Selecione o setor responsável pelo monitoramento da execução da ação',$w_sq_unidade_resp,null,'w_sq_unidade_resp',null,null);
    //SelecaoPrioridade '<u>P</u>rioridade:', 'P', 'Informe a prioridade desta ação.', w_prioridade, null, 'w_prioridade', null, null
    ShowHTML('          </table>');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('          <tr>'); 
    switch (f($RS_Menu,'data_hora')) {
      case 1:  ShowHTML('              <td valign="top"><b><u>F</u>im previsto:</b><br><input '.$w_Disabled.' accesskey="F" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');      break;
      case 2:  ShowHTML('              <td valign="top"><b><u>F</u>im previsto:</b><br><input '.$w_Disabled.' accesskey="F" type="text" name="w_fim" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_fim.'" onKeyDown="FormataDataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);"></td>');  break;
      case 3:  ShowHTML('              <td valign="top"><b>Iní<u>c</u>io previsto:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_inicio,FormataDataEdicao(time())).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
               ShowHTML('              <td valign="top"><b><u>F</u>im previsto:</b><br><input '.$w_Disabled.' accesskey="F" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      break;
      case 4:  ShowHTML('              <td valign="top"><b>Iní<u>c</u>io previsto:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_inicio.'" onKeyDown="FormataDataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);"></td>');
               ShowHTML('              <td valign="top"><b><u>F</u>im previsto:</b><br><input '.$w_Disabled.' accesskey="F" type="text" name="w_fim" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_fim.'" onKeyDown="FormataDataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);"></td>');
      break;
    } 
    ShowHTML('              <td valign="top"><b><u>R</u>ecurso programado:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_valor" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);"></td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td valign="top"><b>Parc<u>e</u>rias externas:<br><INPUT ACCESSKEY="E" '.$w_Disabled.' class="STI" type="text" name="w_proponente" size="90" maxlength="90" value="'.$w_proponente.'" title="Parceria externa da ação. Preencha apenas se houver."></td>');
    ShowHTML('      <tr><td valign="top"><b><u>P</u>arcerias internas:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="STI" type="text" name="w_palavra_chave" size="90" maxlength="90" value="'.$w_palavra_chave.'" title="Se desejar, informe palavras-chave adicionais aos campos informados e que permitam a identificação desta ação."></td>');
    //ShowHTML '      <tr><td align=''center'' height=''2'' bgcolor=''#000000''></td></tr>'
    //ShowHTML '      <tr><td align=''center'' height=''1'' bgcolor=''#000000''></td></tr>'
    //ShowHTML '      <tr><td valign=''top'' align=''center'' bgcolor=''#D0D0D0''><b>Impacto geográfico</td></td></tr>'
    //ShowHTML '      <tr><td align=''center'' height=''1'' bgcolor=''#000000''></td></tr>'
    //ShowHTML '      <tr><td>Os dados deste bloco identificam o local onde a ação causará efeito. Se abrangência nacional, indique Brasília-DF. Se abrangência estadual, indique a capital do estado.</td></tr>'
    //ShowHTML '      <tr><td align=''center'' height=''1'' bgcolor=''#000000''></td></tr>'
    //ShowHTML '      <tr><td valign=''top'' colspan=''2''><table border=0 width=''100''' cellspacing=0>'
    //ShowHTML '      <tr>'
    // & w_dir & w_pagina & par & ''; document.Form.w_troca.value='w_uf'; document.Form.submit();'''
    // & w_dir & w_pagina & par & ''; document.Form.w_troca.value='w_cidade'; document.Form.submit();'''
    //SelecaoCidade '<u>C</u>idade:', 'C', null, w_cidade, w_pais, w_uf, 'w_cidade', null, null
    //ShowHTML '          </table>'
    //ShowHTML '      <tr><td align=''center'' height=''2'' bgcolor=''#000000''></td></tr>'
    //ShowHTML '      <tr><td align=''center'' height=''1'' bgcolor=''#000000''></td></tr>'
    //ShowHTML '      <tr><td valign=''top'' align=''center'' bgcolor=''#D0D0D0''><font size=''2''><b>Alerta de atraso</td></td></tr>'
    //ShowHTML '      <tr><td align=''center'' height=''1'' bgcolor=''#000000''></td></tr>'
    //ShowHTML '      <tr><td>Os dados abaixo indicam como deve ser tratada a proximidade da data limite para conclusão da ação.</td></tr>'
    //ShowHTML '      <tr><td align=''center'' height=''1'' bgcolor=''#000000''></td></tr>'
    //ShowHTML '      <tr><td><table border=''0'' width=''100'''>'
    //ShowHTML '          <tr>'
    //MontaRadioNS '<b>Emite alerta?</b>', w_aviso, 'w_aviso'
    //ShowHTML '              <td valign=''top''><b>Quantos <U>d</U>ias antes da data limite?<br><INPUT ACCESSKEY=''D'' ' & w_Disabled & ' class=''STI'' type=''text'' name=''w_dias'' size=''3'' maxlength=''3'' value=''' & w_dias & ''' title=''Número de dias para emissão do alerta de proximidade da data limite para conclusão da ação.''></td>'
    //ShowHTML '          </table>'
    //ShowHTML '      <tr><td align=''center'' colspan=''3'' height=''1'' bgcolor=''#000000''></TD></TR>'
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
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina das informações adicionais
// -------------------------------------------------------------------------
function InfoAdic(){
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave    = $_REQUEST['w_chave'];
  $w_readonly = '';
  $w_erro     =  '';
  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'') {
    // Se for recarga da página
    $w_chave         = $_REQUEST['w_chave'];
    $w_chave_pai     = $_REQUEST['w_chave_pai'];
    $w_chave_aux     = $_REQUEST['w_chave_aux'];
    $w_sq_menu       = $_REQUEST['w_sq_menu'];
    $w_sq_unidade    = $_REQUEST['w_sq_unidade'];
    $w_descricao     = $_REQUEST['w_descricao'];
    $w_justificativa = $_REQUEST['w_justificativa'];
    $w_ds_acao       = $_REQUEST['w_ds_acao'];
    $w_problema      = $_REQUEST['w_problema'];
    $w_publico_alvo  = $_REQUEST['w_publico_alvo'];
    $w_estrategia    = $_REQUEST['w_estrategia'];
    $w_indicadores   = $_REQUEST['w_indicadores'];
    $w_objetivo      = $_REQUEST['w_objetivo'];
  } else {
    if ((!(strpos('AEV',$O)===false))|| $w_copia>'') {
      // Recupera os dados da ação
      if ($w_copia>'') {
        $RS = db_getSolicData::getInstanceOf($dbms,$w_copia,$SG);
      } else {
        $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
      } 
      if (count($RS)>0) {
        $w_chave_pai     = f($RS,'sq_solic_pai');
        $w_chave_aux     = null;
        $w_sq_menu       = f($RS,'sq_menu');
        $w_sq_unidade    = f($RS,'sq_unidade');
        $w_descricao     = f($RS,'descricao');
        $w_justificativa = f($RS,'justificativa');
        $w_ds_acao       = f($RS,'ds_acao');
        $w_problema      = f($RS,'problema');
        $w_publico_alvo  = f($RS,'publico_alvo');
        $w_estrategia    = f($RS,'estrategia');
        $w_indicadores   = f($RS,'indicadores');
        $w_objetivo      = f($RS,'objetivo');
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
    Validate('w_problema','Situação problema','1','',5,2000,'1','1');
    Validate('w_objetivo','Objetivo da ação','1','',5,2000,'1','1');
    Validate('w_ds_acao','Descrição da ação','1','',5,2000,'1','1');
    Validate('w_publico_alvo','Publico alvo','1','',5,2000,'1','1');
    if (f($RS_Menu,'descricao')=='S') {
      Validate('w_descricao','Resultados da ação','1','',5,2000,'1','1');
    } 
    Validate('w_estrategia','Estratégia de implantação','1','',5,2000,'1','1');
    Validate('w_indicadores','Indicadores de desempenho','1','',5,2000,'1','1');
    if (f($RS_Menu,'justificativa')=='S') {
      Validate('w_justificativa','Observações','1','',5,2000,'1','1');
    } 
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.\'.$w_troca.\'.focus()\';');
  } elseif (!(strpos('EV',$O)===false)) {
    BodyOpen('onLoad=\'this.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_descricao.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled=' DISABLED ';
      if ($O=='V') {
        $w_erro = Validacao($w_sq_solicitacao,$sg);
      } 
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
    ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><font size="2"><b>Informações adicionais</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Os dados deste bloco visam orientar os executores da ação.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td valign="top"><b>Situação <u>p</u>roblema:</b><br><textarea '.$w_Disabled.' accesskey="P" name="w_problema" class="STI" ROWS=5 cols=75 title="Destacar os elementos essenciais que explicam a situação-problema (determinantes/causas).">'.$w_problema.'</TEXTAREA></td>');
    ShowHTML('      <tr><td valign="top"><b><u>O</u>bjetivo da ação:</b><br><textarea '.$w_Disabled.' accesskey="O" name="w_objetivo" class="STI" ROWS=5 cols=75 title="Descreva o objetivo a ser alcançado com a execução desta ação.">'.$w_objetivo.'</TEXTAREA></td>');
    ShowHTML('      <tr><td valign="top"><b><u>D</u>escrição da ação:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_ds_acao" class="STI" ROWS=5 cols=75 title="Destacar os elementos essenciais que compõem e explicam a ação (tarefas).">'.$w_ds_acao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td valign="top"><b>Pú<u>b</u>lico alvo :</b><br><textarea '.$w_Disabled.' accesskey="B" name="w_publico_alvo" class="STI" ROWS=5 cols=75 title="Especifique os segmentos da sociedade aos quais o programa se destina e que se beneficiam direta e legitimamente com sua execução. Exemplos: crianças desnutridas de 6 a 23 meses de idade; gestantes de risco nutricional; grupos vulnerávei e os obesos.">'.$w_publico_alvo.'</TEXTAREA></td>');
    if (f($RS_Menu,'descricao')=='S') {
      ShowHTML('      <tr><td valign="top"><b>Res<u>u</u>ltados da ação:</b><br><textarea '.$w_Disabled.' accesskey="U" name="w_descricao" class="STI" ROWS=5 cols=75 title="Indicar os principais resultados qeu se pretende alcançar nos sistemas de gestão e na saúde da população em consequência da execução da ação.">'.$w_descricao.'</TEXTAREA></td>');
    } 
    ShowHTML('      <tr><td valign="top"><b><u>E</u>strategia de implantação:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_estrategia" class="STI" ROWS=5 cols=75 title="Indicar os meios a empregar ou métodos a seguir com a finalidade de implementar a ação. Relacionar mecanismos e instrumentos disponíveis ou a serem constituídos e a forma de execução. Relacionar as parcerias e responsabilidades e os mecanismos utilizados no monitoramento.">'.$w_estrategia.'</TEXTAREA></td>');
    ShowHTML('      <tr><td valign="top"><b><u>I</u>ndicadores de desempenho:</b><br><textarea '.$w_Disabled.' accesskey="I" name="w_indicadores" class="STI" ROWS=5 cols=75 title="Indicar os parâmetros que medem a diferença entre a situação atual e a situação desejada. É geralmente apresentado como uma relação ou taxa entre variáveis relevantes para quantificar o processo ou os resultados alcançados com a execução da ação. Mede o trabalho realizado.">'.$w_indicadores.'</TEXTAREA></td>');
    if (f($RS_Menu,'justificativa')=='S') {
      ShowHTML('      <tr><td valign="top"><b>Obse<u>r</u>vações:</b><br><textarea '.$w_Disabled.' accesskey="R" name="w_justificativa" class="STI" ROWS=5 cols=75 title="Informar fatos ou situações que sejam relevantes para uma melhor compreensão da ação e/ou descrever situações que não tenham sido descritas em outros campos do formulário e que devam ser consideradas para a viabilidade da mesma. Indicar as fragilidades já identificadas.">'.$w_justificativa.'</TEXTAREA></td>');
    } 
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
    //ShowHTML ' history.back(1);'
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
  Global $w_Disabled;
  $w_chave    = $_REQUEST['w_chave'];
  $w_readonly = '';
  $w_erro     = '';
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
  if (count($RS)>0) {
    $w_chave_pai       = f($RS,'sq_solic_pai');
    $w_chave_aux       = null;
    $w_sq_menu         = f($RS,'sq_menu');
    $w_sq_unidade      = f($RS,'sq_unidade');
    $w_nm_ppa_pai      = f($RS,'nm_ppa_pai');
    $w_cd_ppa_pai      = f($RS,'cd_ppa_pai');
    $w_nm_ppa          = f($RS,'nm_ppa');
    $w_cd_ppa          = f($RS,'cd_ppa');
    $w_nm_pri          = f($RS,'nm_pri');
    $w_cd_pri          = f($RS,'cd_pri');
    $w_sq_orprioridade = f($RS,'sq_orprioridade');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  if (Nvl($w_sq_orprioridade,0)==0) {
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
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataDataHora();
  FormataValor();
  ValidateOpen('Validacao');
  ShowHTML('  theForm.Botao.disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=\'document.Form.focus()\';');
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
  if ($w_sq_orprioridade>'') {
    ShowHTML('      <tr><td valign="top"><b>Iniciativa prioritária: </b><br>'.$w_nm_pri.' </b>');
  } 
  $RS = db_getOrPrioridadeList::getInstanceOf($dbms,$w_chave,$w_cliente,$w_sq_orprioridade);
  ShowHTML('      <tr><td valign="top"><br>');
  ShowHTML('      <tr><td valign="top"><b>Selecione outras iniciativas prioritárias as quais a ação está relacionada:</b>');
  foreach($RS as $row) {
    if (Nvl(f($row,'Existe'),0)>0) {
      ShowHTML('      <tr><td valign="top">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="w_outras_iniciativas[]" value="'.f($row,'chave').'" checked>'.f($row,'nome').'</td>');
    } else {
      ShowHTML('      <tr><td valign="top">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="w_outras_iniciativas[]" value="'.f($row,'chave').'">'.f($row,'nome').'</td>');
    } 
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
  $w_chave     = $_REQUEST['w_chave'];
  $w_chave_aux = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_sq_acao_ppa = $_REQUEST['w_sq_acao_ppa'];
    $w_obs_financ  = $_REQUEST['w_obs_financ'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getFinancAcaoPPA::getInstanceOf($dbms,$w_chave,$w_cliente,null);
  } elseif ((!(strpos('AEV',$O)===false)) && $w_troca=='') {
    // Recupera os dados do financiamento
    $RS = db_getFinancAcaoPPA::getInstanceOf($dbms,$w_chave,$w_cliente,$_REQUEST['w_sq_acao_ppa']);
    foreach($RS as $row){$RS=$row; break;}
    $w_sq_acao_ppa = f($RS,'sq_acao_ppa');
    $w_obs_financ  = f($RS,'observacao');
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
      if($O=='I')Validate('w_sq_acao_ppa','Ação PPA','SELECT','1','1','10','','1');
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
    BodyOpen('onLoad=\'document.Form.\'.$w_troca.\'.focus()\';');
  } elseif ($O=='I') {
    BodyOpen('onLoad=\'document.Form.w_sq_acao_ppa.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
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
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_sq_acao_ppa='.f($row,'sq_acao_ppa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_sq_acao_ppa='.f($row,'sq_acao_ppa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
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
    $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    if (f($RS,'sq_acao_ppa')>''){
      ShowHTML('      <tr><td valign="top"><b>Programa PPA: </b><br>'.f($RS,'cd_ppa_pai').' - '.f($RS,'nm_ppa_pai').' </b>');
      ShowHTML('      <tr><td valign="top"><b>Ação PPA: </b><br>'.f($RS,'cd_ppa').' - '.f($RS,'nm_ppa').' </b>');
    } 
    if (f($RS,'sq_orprioridade')>'') {
      ShowHTML('      <tr><td valign="top"><b>Iniciativa prioritária: </b><br>'.f($RS,'nm_pri').' </b>');
    } 
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    if ($O=='I') {
      selecaoAcaoPPA_OR('Ação <u>P</u>PA:','P',null,$w_sq_acao_ppa,$w_chave,'w_sq_acao_ppa','FINANCIAMENTO',null);
    } else {
      selecaoAcaoPPA_OR('Ação <u>P</u>PA:','P',null,$w_sq_acao_ppa,$w_chave,'w_sq_acao_ppa',null,'disabled');
      ShowHTML('<INPUT type="hidden" name="w_sq_acao_ppa" value="'.$w_sq_acao_ppa.'">');
    } 
    ShowHTML('      <tr><td valign="top"><b>Obse<u>r</u>vações:</b><br><textarea '.$w_Disabled.' accesskey="R" name="w_obs_financ" class="STI" ROWS=5 cols=75 title="Informar fatos ou situações que sejam relevantes para uma melhor compreensão do financiamento da ação.">'.$w_obs_financ.'</TEXTAREA></td>');
    ShowHTML('      <tr><td align="center" colspan=4><hr>');
    if ($O=='E')        ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    elseif ($O=='I')    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Incluir">');
    elseif ($O=='A')    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Alterar">');
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
    //ShowHTML ' history.back(1);'
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
  $w_chave           = $_REQUEST['w_chave'];
  $w_chave_aux       = $_REQUEST['w_chave_aux'];
  $w_sq_acao_ppa     = $_REQUEST['w_sq_acao_ppa'];
  $w_sq_acao_ppa_pai = $_REQUEST['w_sq_acao_ppa_pai'];
  $w_sq_orprioridade = $_REQUEST['w_sq_orprioridade'];
  if ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
  } elseif (!(strpos('A',$O)===false)) {
    if ($w_sq_acao_ppa_pai>'') {
      $w_tipo=1;
      $RS = db_getAcaoPPA::getInstanceOf($dbms,$w_sq_acao_ppa_pai,$w_cliente,null,null,null,null,null,null,null,null,null);
      foreach ($RS as $row) {$RS=$row; break;} 
    } elseif ($w_sq_acao_ppa>'') {
      $w_tipo=2;
      $RS = db_getAcaoPPA::getInstanceOf($dbms,$w_sq_acao_ppa,$w_cliente,null,null,null,null,null,null,null,null,null);
      foreach ($RS as $row) {$RS=$row; break;}     
    } elseif ($w_sq_orprioridade>'') {
      $w_tipo=3;
      $RS = db_getOrPrioridade::getInstanceOf($dbms,null,$w_cliente,$w_sq_orprioridade,null,null,null);
      foreach ($RS as $row) {$RS=$row; break;}     
    } 
    //$RS = db_getSolicData RS, w_chave, SG
    if (count($RS)>0) {
      $w_responsavel  = f($RS,'responsavel');
      $w_telefone     = f($RS,'telefone');
      $w_email        = f($RS,'email');
      $w_nome         = f($RS,'nome');
      $w_codigo       = f($RS,'codigo');
      if ($w_tipo==2) {
        $w_nome_pai   = f($RS,'nm_acao_pai');
        $w_codigo_pai = f($RS,'cd_pai');
      } 
    } 
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('A',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    formatadata();
    SaltaCampo();
    FormataCEP();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('A',$O)===false)) {
      Validate('w_responsavel','Responsável','','1','3','60','1','1');
      Validate('w_telefone','Telenfone','1','','7','14','1','1');
      Validate('w_email','Email','','','3','60','1','1');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=\'this.focus()\';');
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
      if (nvl(f($RS,'sq_acao_ppa'),'')>'') {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>Programa PPA</td>');
        ShowHTML('        <td>'.f($RS,'nm_ppa_pai').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_sq_acao_ppa_pai='.f($RS,'sq_acao_ppa_pai').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_chave_aux='.f($RS,'sq_acao_ppa').'">Gerente Executivo</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>Ação PPA</td>');
        ShowHTML('        <td>'.f($RS,'nm_ppa').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_sq_acao_ppa='.f($RS,'sq_acao_ppa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_chave_aux='.f($RS,'sq_siw_solicitacao').'">Coordenador</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
      if (nvl(f($RS,'sq_orprioridade'),'')>'') {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>Iniciativa</td>');
        ShowHTML('        <td>'.f($RS,'nm_pri').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_sq_orprioridade='.f($RS,'sq_orprioridade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_chave_aux='.f($RS,'sq_orprioridade').'">Responsável</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
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
    if ($w_tipo==1) {
      $w_label      = 'Programa PPA';
      $w_chave_aux  = $w_sq_acao_ppa_pai;
    } elseif ($w_tipo==2) {
      $w_label      = 'Ação PPA';
      $w_chave_aux  = $w_sq_acao_ppa;
    } elseif ($w_tipo==3) {
      $w_label      = 'Iniciativa prioritária';
      $w_chave_aux  = $w_sq_orprioridade;
    } 
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    if ($w_tipo==2) {
      ShowHTML('      <tr><td valign="top"><b>Programa PPA: </b>'.$w_codigo_pai.' - '.$w_nome_pai.' </b>');
    } 
    ShowHTML('      <tr><td valign="top"><b>'.$w_label.': </b>');
    if (!$w_tipo==3) {
      ShowHTML(''.$w_codigo.' - ');
    } 
    ShowHTML(''.$w_nome.'</td>');
    if ($w_tipo==1)        ShowHTML('      <tr><td><b><u>G</u>erente executivo:</b><br><input '.$w_Disabled.' accesskey="G" type="text" name="w_responsavel" class="STI" SIZE="50" MAXLENGTH="60" VALUE="'.$w_responsavel.'" title="Informe um gerente executivo."></td>');
    elseif ($w_tipo==2)    ShowHTML('      <tr><td><b><u>C</u>oordenador:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_responsavel" class="STI" SIZE="50" MAXLENGTH="60" VALUE="'.$w_responsavel.'" title="Informe um coordenador."></td>');
    elseif ($w_tipo==3)    ShowHTML('      <tr><td><b>Res<u>p</u>onsável:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="w_responsavel" class="STI" SIZE="50" MAXLENGTH="60" VALUE="'.$w_responsavel.'" title="Informe um responsável."></td>'); 
    ShowHTML('      <tr><td valign="top"><b><u>T</u>elefone:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_telefone" class="STI" SIZE="15" MAXLENGTH="14" VALUE="'.$w_telefone.'"></td>');
    ShowHTML('      <tr><td><b>E<u>m</u>ail:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_email" class="STI" SIZE="50" MAXLENGTH="60" VALUE="'.$w_email.'" title="Informe o email do responsável."></td>');
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
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de etapas da ação
// -------------------------------------------------------------------------

function Etapas() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_pai  = $_REQUEST['w_chave_pai'];
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
    $w_sq_pessoa            = $_REQUEST['w_sq_pessoa'];
    $w_sq_unidade           = $_REQUEST['w_sq_unidade'];
    $w_vincula_atividade    = $_REQUEST['w_vincula_atividade'];
    $w_unidade_medida       = $_REQUEST['w_unidade_medida'];
    $w_quantidade           = $_REQUEST['w_quantidade'];
    $w_cumulativa           = $_REQUEST['w_cumulativa'];
    $w_programada           = $_REQUEST['w_programada'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getSolicEtapa::getInstanceOf($dbms,$w_chave,null,'LISTA',null);
    $RS = SortArray($RS,'ordem','asc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados do endereço informado    
    $RS = db_getSolicEtapa::getInstanceOf($dbms,$w_chave,$w_chave_aux,'REGISTRO',null);
    foreach ($RS as $row) {$RS=$row; break;}
    $w_chave_pai         = f($RS,'sq_etapa_pai');
    $w_titulo            = f($RS,'titulo');
    $w_ordem             = f($RS,'ordem');
    $w_descricao         = f($RS,'descricao');
    $w_inicio            = f($RS,'inicio_previsto');
    $w_fim               = f($RS,'fim_previsto');
    $w_inicio_real       = f($RS,'inicio_real');
    $w_fim_real          = f($RS,'fim_real');
    $w_perc_conclusao    = f($RS,'perc_conclusao');
    $w_orcamento         = f($RS,'orcamento');
    $w_sq_pessoa         = f($RS,'sq_pessoa');
    $w_sq_unidade        = f($RS,'sq_unidade');
    $w_vincula_atividade = f($RS,'vincula_atividade');
    $w_unidade_medida    = f($RS,'unidade_medida');
    $w_quantidade        = f($RS,'quantidade');
    $w_cumulativa        = f($RS,'cumulativa');
    $w_programada        = f($RS,'programada');
  } elseif (Nvl($w_sq_pessoa,'')=='') {
    // Se a etapa não tiver responsável atribuído, recupera o responsável pela ação
    $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'ORGERAL');
    $w_sq_pessoa  = f($RS,'solicitante');
    $w_sq_unidade = f($RS,'sq_unidade_resp');
  } 

  if (nvl($w_chave_pai,'nulo')!='nulo') {
    // Recupera o número de ordem das outras opções irmãs à selecionada
    $RS = db_getEtapaOrder::getInstanceOf($dbms,$w_chave,$w_chave_pai);
    $RS = SortArray($RS,'ordena','asc');
    if (!count($RS)<=0) {
      $w_texto = '<b>Dados da etapa superior e das etapas irmãs:</b>:<br>'.
                 '<table border=1 bgcolor="#FAEBD7">'.
                 '<tr valign="top" align=center><td><b>Ordem<td><b>Descrição<td><b>Início<td><b>Fim';
      foreach ($RS as $row) {
        $w_texto .= '<tr valign=top><td align=center>'.f($row,'ordem').'<td>'.f($row,'titulo').'<td>'.formataDataEdicao(f($row,'inicio_previsto')).'<td>'.formataDataEdicao(f($row,'fim_previsto'));
      } 
      $w_texto .= '</table>';
    } else {
      $w_texto='Não há outros números de ordem subordinados a esta etapa.';
    } 
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
    if (!(strpos('IA',$O)===false)){
      Validate('w_ordem','Tipo de visão','SELECT','1','1','10','','1');
      Validate('w_titulo','Título','','1','2','150','1','1');
      Validate('w_quantidade','Quantitativo programado','','1','2','18','','1');
      Validate('w_unidade_medida','Unidade de medida','','1','2','100','1','1');
      Validate('w_descricao','Descricao','','1','2','2000','1','1');
      Validate('w_ordem','Ordem','1','1','1','3','','0123456789');
      //Validate 'w_chave_pai', 'Subordinação', 'SELECT', '', '1', '10', '', '1'
      Validate('w_inicio','Início previsto','DATA','1','10','10','','0123456789/');
      Validate('w_fim','Fim previsto','DATA','1','10','10','','0123456789/');
      CompData('w_inicio','Início previsto','<=','w_fim','Fim previsto');
      //Validate 'w_orcamento', 'Recurso programado', 'VALOR', '1', '4', '18', '', '0123456789.,'
      //Validate 'w_perc_conclusao', 'Percentual de conclusão', '', '1', '1', '3', '', '0123456789'
      Validate('w_sq_pessoa','Responsável','SELECT','','1','10','','1');
      Validate('w_sq_unidade','Setor responsável','SELECT','','1','10','','1');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'')     BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  elseif ($O=='I')     BodyOpen('onLoad=\'document.Form.w_titulo.focus()\';');
  else                 BodyOpen('onLoad=\'this.focus()\';');  
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
    ShowHTML('          <td><b>Metas</td>');
    //ShowHTML '          <td><b>Produto</td>'
    //ShowHTML '          <td rowspan=2><b>Responsável</td>'
    //ShowHTML '          <td rowspan=2><b>Setor</td>'
    ShowHTML('          <td><b>Execução até</td>');
    ShowHTML('          <td><b>Conc.</td>');
    //ShowHTML '          <td rowspan=2><b>Ativ.</td>'
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    //ShowHTML '        <tr bgcolor=''' & conTrBgColor & ''' align=''center''>'
    //ShowHTML '          <td><b>De</td>'
    //ShowHTML '          <td><b>Até</td>'
    //ShowHTML '        </tr>'
    // Recupera as etapas principais
    $RS = db_getSolicEtapa::getInstanceOf($dbms,$w_chave,null,'LSTNULL',null);
    $RS = SortArray($RS,'ordem','asc');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=9 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      foreach ($RS as $row) {
        ShowHtml(EtapaLinha($w_chave,f($row,'sq_projeto_etapa'),f($row,'titulo'),f($row,'nm_resp'),f($row,'sg_setor'),f($row,'inicio_previsto'),f($row,'fim_previsto'),f($row,'perc_conclusao'),f($row,'qt_ativ'),'<b>','S','PROJETO'));
        // Recupera as etapas vinculadas ao nível acima
        $RS1 = db_getSolicEtapa::getInstanceOf($dbms,$w_chave,f($row,'sq_projeto_etapa'),'LSTNIVEL',null);
        $RS1 = SortArray($RS1,'ordem','asc');
        foreach($RS1 as $row1) {
          ShowHTML(EtapaLinha($w_chave,f($row1,'sq_projeto_etapa'),f($row1,'titulo'),f($row1,'nm_resp'),f($row1,'sg_setor'),f($row1,'inicio_previsto'),f($row1,'fim_previsto'),f($row1,'perc_conclusao'),f($row1,'qt_ativ'),null,'S','PROJETO'));
          // Recupera as etapas vinculadas ao nível acima
          $RS2 = db_getSolicEtapa::getInstanceOf($dbms,$w_chave,f($RS1,'sq_projeto_etapa'),'LSTNIVEL',null);
          $RS2 = SortArray($RS2,'ordem','asc');
          foreach ($RS2 as $row2) {
            ShowHTML(EtapaLinha($w_chave,f($row2,'sq_projeto_etapa'),f($row2,'titulo'),f($row2,'nm_resp'),f($row2,'sg_setor'),f($row2,'inicio_previsto'),f($row2,'fim_previsto'),f($row2,'perc_conclusao'),f($row2,'qt_ativ'),null,'S','PROJETO'));
            // Recupera as etapas vinculadas ao nível acima
            $RS3 = db_getSolicEtapa::getInstanceOf($dbms,$w_chave,f($row2,'sq_projeto_etapa'),'LSTNIVEL',null);
            $RS3 = SortArray($RS3,'ordem','asc');
            foreach ($RS3 as $row3) {
              ShowHTML(EtapaLinha($w_chave,f($row3,'sq_projeto_etapa'),f($row3,'titulo'),f($row3,'nm_resp'),f($row3,'sg_setor'),f($row3,'inicio_previsto'),f($row3,'fim_previsto'),f($row3,'perc_conclusao'),f($row3,'qt_ativ'),null,'S','PROJETO'));
              // Recupera as etapas vinculadas ao nível acima
              $RS4 = db_getSolicEtapa::getInstanceOf($dbms,$w_chave,f($row3,'sq_projeto_etapa'),'LSTNIVEL',null);
              $RS4 = SortArray($RS4,'ordem','asc');
              foreach($RS4 as $row4) {
                ShowHTML(EtapaLinha($w_chave,f($row4,'sq_projeto_etapa'),f($row4,'titulo'),f($row4,'nm_resp'),f($row4,'sg_setor'),f($row4,'inicio_previsto'),f($row4,'fim_previsto'),f($row4,'perc_conclusao'),f($row4,'qt_ativ'),null,'S','PROJETO'));
              } 
            } 
          } 
        } 
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)){
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled=' DISABLED ';
  } 
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_sq_pessoa" value="'.$w_sq_pessoa.'">');
  ShowHTML('<INPUT type="hidden" name="w_sq_unidade" value="'.$w_sq_unidade.'">');
  ShowHTML('<INPUT type="hidden" name="w_orcamento" value="0,00">');
  ShowHTML('<INPUT type="hidden" name="w_vincula_atividade" value="N">');
  ShowHTML('<INPUT type="hidden" name="w_perc_conclusao" value="0">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('    <table width="97%" border="0">');
  ShowHTML('      <tr><td><b>Prod<u>u</u>to:</b><br><input '.$w_Disabled.' accesskey="U" type="text" name="w_titulo" class="STI" SIZE="90" MAXLENGTH="150" VALUE="'.$w_titulo.'" title="Bem ou serviço que resulta da ação, destinado ao público-alvo ou o investimento para a produção deste bem ou serviço. Para cada ação deve haver um só produto. Em situações especiais, expressa a quantidade de beneficiários atendidos pela ação."></td>');
  ShowHTML('     <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  MontaRadioNS('<b>Meta LOA?</b>',$w_programada,'w_programada');
  MontaRadioNS('<b>Meta cumulativa?</b>',$w_cumulativa,'w_cumulativa');
  ShowHTML('         </table></td></tr>');
  ShowHTML('     <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  ShowHTML('         <tr><td align="left"><b><u>Q</u>uantitativo:<br><INPUT ACCESSKEY="Q" TYPE="TEXT" CLASS="STI" NAME="w_quantidade" SIZE=5 MAXLENGTH=18 VALUE="'.$w_quantidade.'" '.$w_Disabled.'></td>');
  ShowHTML('             <td align="left"><b><u>U</u>nidade de medida:<br><INPUT ACCESSKEY="U" TYPE="TEXT" CLASS="STI" NAME="w_unidade_medida" SIZE=15 MAXLENGTH=30 VALUE="'.$w_unidade_medida.'" '.$w_Disabled.'></td>');
  ShowHTML('         </table></td></tr>');
  ShowHTML('      <tr><td><b><u>E</u>specificação do produto:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_descricao" class="STI" ROWS=5 cols=75 title="Expresse as características do produto acabado visando sua melhor identificação.">'.$w_descricao.'</TEXTAREA></td>');
  //ShowHTML '      <tr>'
  //SelecaoEtapa 'Me<u>t</u>a superior:', 'T', 'Se necessário, indique a meta superior a esta.', w_chave_pai, w_chave, w_chave_aux, 'w_chave_pai', 'Pesquisa', null
  //ShowHTML '      </tr>'
  ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  ShowHTML('              <td align="left"><b><u>O</u>rdem:<br><INPUT ACCESSKEY="O" TYPE="TEXT" CLASS="STI" NAME="w_ordem" SIZE=3 MAXLENGTH=3 VALUE="'.$w_ordem.'" '.$w_Disabled.' title="'.str_replace($crlf,'<BR>',$w_texto).'"></td>');
  ShowHTML('              <td><b>Previsão iní<u>c</u>io:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.FormataDataEdicao(Nvl($w_inicio,time())).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data prevista para início da meta."></td>');
  ShowHTML('              <td><b>Previsão <u>t</u>érmino:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.FormataDataEdicao($w_fim).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data prevista para término da meta."></td>');
  //ShowHTML '          <tr valign=''top''>'
  //ShowHTML '              <td><b>Orça<u>m</u>ento previsto:</b><br><input ' & w_Disabled & ' accesskey=''M'' type=''text'' name=''w_orcamento'' class=''STI'' SIZE=''18'' MAXLENGTH=''18'' VALUE=''' & FormatNumber(w_orcamento,2) & ''' onKeyDown=''FormataValor(this,18,2,event);'' title=''Recurso programado para execução desta etapa.''></td>'
  //ShowHTML '              <td align=''left''><b>Percentual de co<u>n</u>clusão:<br><INPUT ACCESSKEY=''N'' TYPE=''TEXT'' CLASS=''STI'' NAME=''w_perc_conclusao'' SIZE=3 MAXLENGTH=3 VALUE=''' & nvl(w_perc_conclusao,0) & ''' ' & w_Disabled & ' title=''Informe o percentual de conclusão atual da meta.''></td>'
  //MontaRadioSN '<b>Permite vinculação de atividades?</b>', w_vincula_atividade, 'w_vincula_atividade'
  ShowHTML('          </table>');
  ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
  //SelecaoPessoa 'Respo<u>n</u>sável pela etapa:', 'N', 'Selecione o responsável pela etapa na relação.', w_sq_pessoa, null, 'w_sq_pessoa', 'USUARIOS'
  //SelecaoUnidade '<U>S</U>etor responsável pela etapa:', 'S', 'Selecione o setor responsável pela execução da etapa', w_sq_unidade, null, 'w_sq_unidade', null, null
  ShowHTML('          <tr>');
  ShowHTML('      <tr>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr><td align="center" colspan=4><hr>');
  if ($O=='E') {
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
  } else {
    if ($O=='I')      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Incluir">');
    else              ShowHTML('   <input class="STB" type="submit" name="Botao" value="Atualizar">'); 
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
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de atualização das etapas da ação
// -------------------------------------------------------------------------

function AtualizaEtapa() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_pai  = $_REQUEST['w_chave_pai'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_tipo       = strtoupper(trim($_REQUEST['w_tipo']));
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'ORGERAL');
  $w_cabecalho = f($RS,'titulo').' ('.$w_chave.')';
  // Configura uma variável para testar se as etapas podem ser atualizadas.
  // Ações concluídas ou canceladas não podem ter permitir a atualização.
  if (Nvl(f($RS,'sg_tramite'),'--')=='EE') $w_fase='S'; else $w_fase='N'; 
  if ($w_troca>'') {  
    // Se for recarga da página
    $w_ordem             = $_REQUEST['w_ordem'];
    $w_titulo            = $_REQUEST['w_titulo'];
    $w_descricao         = $_REQUEST['w_descricao'];
    $w_inicio            = $_REQUEST['w_inicio'];
    $w_fim               = $_REQUEST['w_fim'];
    $w_inicio_real       = $_REQUEST['w_inicio_real'];
    $w_fim_real          = $_REQUEST['w_fim_real'];
    $w_perc_conclusao    = $_REQUEST['w_perc_conclusao'];
    $w_orcamento         = $_REQUEST['w_orcamento'];
    $w_sq_pessoa         = $_REQUEST['w_sq_pessoa'];
    $w_sq_unidade        = $_REQUEST['w_sq_unidade'];
    $w_vincula_atividade = $_REQUEST['w_vincula_atividade'];
    $w_unidade_medida    = $_REQUEST['w_unidade_medida'];
    $w_quantidade        = $_REQUEST['w_quantidade'];
    $w_cumulativa        = $_REQUEST['w_cumulativa'];
    $w_programada        = $_REQUEST['w_programada'];
    for ($i=0; $i<=$i=12; $i=$i+1) {
      $w_execucao_fisica[i]     = $_REQUEST['w_execucao_fisica[i]'];
      $w_execucao_financeira[i] = $_REQUEST['w_execucao_financeira[i]'];
    } 
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getSolicEtapa::getInstanceOf($dbms,$w_chave,null,'LISTA',null);
    $RS = SortArray($RS,'ordem','asc');
    // Recupera o código da opção de menu  a ser usada para listar as atividades
    $w_p2='';
    if (count($RS)>0) {
      foreach ($RS as $row) {
        if (Nvl(f($row,'P2'),0)>0) {
            $w_p2 = f($row,'P2');
            break;
        } 
      } 
    } 
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados do endereço informado
    $RS = db_getSolicEtapa::getInstanceOf($dbms,$w_chave,$w_chave_aux,'REGISTRO',null);
    foreach ($RS as $row) {$RS=$row; break;}
    $w_chave_pai                = f($RS,'sq_etapa_pai');
    $w_titulo                   = f($RS,'titulo');
    $w_ordem                    = f($RS,'ordem');
    $w_descricao                = f($RS,'descricao');
    $w_inicio                   = f($RS,'inicio_previsto');
    $w_fim                      = f($RS,'fim_previsto');
    $w_inicio_real              = f($RS,'inicio_real');
    $w_fim_real                 = f($RS,'fim_real');
    $w_perc_conclusao           = f($RS,'perc_conclusao');
    $w_orcamento                = f($RS,'orcamento');
    $w_sq_pessoa                = f($RS,'sq_pessoa');
    $w_sq_unidade               = f($RS,'sq_unidade');
    $w_vincula_atividade        = f($RS,'vincula_atividade');
    $w_ultima_atualizacao       = f($RS,'ultima_atualizacao');
    $w_sq_pessoa_atualizacao    = f($RS,'sq_pessoa_atualizacao');
    $w_situacao_atual           = f($RS,'situacao_atual');
    $w_unidade_medida           = f($RS,'unidade_medida');
    $w_quantidade               = f($RS,'quantidade');
    $w_cumulativa               = f($RS,'cumulativa');
    $w_programada               = f($RS,'programada');
    $w_exequivel                = f($RS,'exequivel');
    $w_justificativa_inex       = f($RS,'justificativa_inexequivel');
    $w_outras_medidas           = f($RS,'outras_medidas');
    $w_nm_programada            = f($RS,'nm_programada');
    $w_nm_cumulativa            = f($RS,'nm_cumulativa');
    $RS = db_getEtapaMensal::getInstanceOf($dbms,$w_chave_aux);
    if (count($RS)>0) {
      foreach($RS as $row) {
        switch (toNumber(substr(FormataDataEdicao(f($row,'referencia')),3,2))) {
          case 1:  $w_quantitativo_1   = f($row,'execucao_fisica');   break;
          case 2:  $w_quantitativo_2   = f($row,'execucao_fisica');   break;
          case 3:  $w_quantitativo_3   = f($row,'execucao_fisica');   break;
          case 4:  $w_quantitativo_4   = f($row,'execucao_fisica');   break;
          case 5:  $w_quantitativo_5   = f($row,'execucao_fisica');   break;
          case 6:  $w_quantitativo_6   = f($row,'execucao_fisica');   break;
          case 7:  $w_quantitativo_7   = f($row,'execucao_fisica');   break;
          case 8:  $w_quantitativo_8   = f($row,'execucao_fisica');   break;
          case 9:  $w_quantitativo_9   = f($row,'execucao_fisica');   break;
          case 10: $w_quantitativo_10  = f($row,'execucao_fisica');   break;
          case 11: $w_quantitativo_11  = f($row,'execucao_fisica');   break;
          case 12: $w_quantitativo_12  = f($row,'execucao_fisica');   break;
        } 
      } 
    } 
  } elseif (Nvl($w_sq_pessoa,'')=='') {
    // Se a etapa não tiver responsável atribuído, recupera o responsável pela ação
    $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'ORGERAL');
    $w_sq_pessoa    = f($RS,'solicitante');
    $w_sq_unidade   = f($RS,'sq_unidade_resp');
  } 
  if ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
  } else {
    Cabecalho();
  } 
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Meta da ação</TITLE>');
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
  //ShowHTML '<B><FONT COLOR=''#000000''>' & Mid(w_TP,1, Instr(w_TP,'-')-1) & '- Metas' & '</FONT></B>'
  //ShowHTML '<HR>'
  ShowHTML('<div align=center><center>');
  ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
  ShowHTML('      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>PROJETO: '.$w_cabecalho.'</b></font></div></td></tr>');
  ShowHTML('      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');

  
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
    ShowHTML('          <td><b>Metas</td>');
    ShowHTML('          <td><b>Execução até</td>');
    ShowHTML('          <td><b>Conc.</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    // Recupera as etapas principais
    $RS = db_getSolicEtapa::getInstanceOf($dbms,$w_chave,$null,'LSTNULL',null);
    $RS = SortArray($RS,'ordem','asc');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('    <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><font size="2"><b>Não foi encontrado nenhum registro.</b></td></tr>');
    } else {
      foreach($RS as $row) {
        if (Nvl(f($row,'tit_exec'),0)    ==$w_usuario || 
            Nvl(f($row,'sub_exec'),0)    ==$w_usuario || 
            Nvl(f($row,'titular'),0)     ==$w_usuario || 
            Nvl(f($row,'substituto'),0)  ==$w_usuario || 
            Nvl(f($row,'executor'),0)    ==$w_usuario || 
            Nvl(f($row,'solicitante'),0) ==$w_usuario || 
            Nvl(f($row,'sq_pessoa'),0)   ==$w_usuario) {
          ShowHtml(EtapaLinha($w_chave,f($row,'sq_projeto_etapa'),f($row,'titulo'),f($row,'nm_resp'),f($row,'sg_setor'),f($row,'inicio_previsto'),f($row,'fim_previsto'),f($row,'perc_conclusao'),f($row,'qt_ativ'),'<b>',$w_fase,'ETAPA'));
        } else {
          ShowHtml(EtapaLinha($w_chave,f($row,'sq_projeto_etapa'),f($row,'titulo'),f($row,'nm_resp'),f($row,'sg_setor'),f($row,'inicio_previsto'),f($row,'fim_previsto'),f($row,'perc_conclusao'),f($row,'qt_ativ'),'<b>','N','ETAPA'));
        } 
        // Recupera as etapas vinculadas ao nível acima
        $RS1 = db_getSolicEtapa::getInstanceOf($dbms,$w_chavef,f($row,'sq_projeto_etapa'),'LSTNIVEL',null);
        $RS1 = SortArray($RS1,'ordem','asc');
        foreach($RS1 as $row1) {
          if (Nvl(f($row1,'titular'),0)==$w_usuario || Nvl(f($row1,'substituto'),0)==$w_usuario || Nvl(f($row1,'sq_pessoa'),0)==$w_usuario) {
            ShowHTML(EtapaLinha($w_chave,f($row1,'sq_projeto_etapa'),f($row1,'titulo'),f($row1,'nm_resp'),f($row1,'sg_setor'),f($row1,'inicio_previsto'),f($row1,'fim_previsto'),f($row1,'perc_conclusao'),f($row1,'qt_ativ'),'<b>',$w_fase,'ETAPA'));
          } else {
            ShowHTML(EtapaLinha($w_chave,f($row1,'sq_projeto_etapa'),f($row1,'titulo'),f($row1,'nm_resp'),f($row1,'sg_setor'),f($row1,'inicio_previsto'),f($row1,'fim_previsto'),f($row1,'perc_conclusao'),f($row1,'qt_ativ'),'<b>','N','ETAPA'));
          } 
          // Recupera as etapas vinculadas ao nível acima
          $RS2 = db_getSolicEtapa::getInstanceOf($dbms,$w_chave,f($row1,'sq_projeto_etapa'),'LSTNIVEL',null);
          $RS2 = SortArray($RS2,'ordem','asc');
          foreach($RS2 as $row2) {
            if (Nvl(f($row2,'titular'),0)==$w_usuario || Nvl(f($row2,'substituto'),0)==$w_usuario || Nvl(f($row2,'sq_pessoa'),0)==$w_usuario) {
              ShowHTML(EtapaLinha($w_chave,f($row2,'sq_projeto_etapa'),f($row2,'titulo'),f($row2,'nm_resp'),f($row2,'sg_setor'),f($row2,'inicio_previsto'),f($row2,'fim_previsto'),f($row2,'perc_conclusao'),f($row2,'qt_ativ'),'<b>',$w_fase,'ETAPA'));
            } else {
              ShowHTML(EtapaLinha($w_chave,f($row2,'sq_projeto_etapa'),f($row2,'titulo'),f($row2,'nm_resp'),f($row2,'sg_setor'),f($row2,'inicio_previsto'),f($row2,'fim_previsto'),f($row2,'perc_conclusao'),f($row2,'qt_ativ'),'<b>','N','ETAPA'));;
            } 
            // Recupera as etapas vinculadas ao nível acima
            $RS3 = db_getSolicEtapa::getInstanceOf($dbms,$w_chave,f($row2,'sq_projeto_etapa'),'LSTNIVEL',null);
            $RS3 = SortArray($RS3,'ordem','asc');
            foreach($RS3 as $row3) {
              if (Nvl(f($row3,'titular'),0)==$w_usuario || Nvl(f($row3,'substituto'),0)==$w_usuario || Nvl(f($row3,'sq_pessoa'),0)==$w_usuario) {
                ShowHTML(EtapaLinha($w_chave,f($row3,'sq_projeto_etapa'),f($row3,'titulo'),f($row3,'nm_resp'),f($row3,'sg_setor'),f($row3,'inicio_previsto'),f($row3,'fim_previsto'),f($row3,'perc_conclusao'),f($row3,'qt_ativ'),'<b>',$w_fase,'ETAPA'));;
              } else {
                ShowHTML(EtapaLinha($w_chave,f($row3,'sq_projeto_etapa'),f($row3,'titulo'),f($row3,'nm_resp'),f($row3,'sg_setor'),f($row3,'inicio_previsto'),f($row3,'fim_previsto'),f($row3,'perc_conclusao'),f($row3,'qt_ativ'),'<b>','N','ETAPA'));
              } 
              // Recupera as etapas vinculadas ao nível acima
              $RS4 = db_getSolicEtapa::getInstanceOf($dbms,$w_chave,f($row3,'sq_projeto_etapa'),'LSTNIVEL',null);
              $RS4 = SortArray($RS4,'ordem','asc');
              foreach($RS4 as $row4) {
                if (Nvl(f($row4,'titular'),0)==$w_usuario || Nvl(f($row4,'substituto'),0)==$w_usuario || Nvl(f($row4,'sq_pessoa'),0)==$w_usuario) {
                  ShowHTML(EtapaLinha($w_chave,f($row4,'sq_projeto_etapa'),f($row4,'titulo'),f($row4,'nm_resp'),f($row4,'sg_setor'),f($row4,'inicio_previsto'),f($row4,'fim_previsto'),f($row4,'perc_conclusao'),f($row4,'qt_ativ'),'<b>',$w_fase,'ETAPA'));
                } else {
                  ShowHTML(EtapaLinha($w_chave,f($row4,'sq_projeto_etapa'),f($row4,'titulo'),f($row4,'nm_resp'),f($row4,'sg_setor'),f($row4,'inicio_previsto'),f($row4,'fim_previsto'),f($row4,'perc_conclusao'),f($row4,'qt_ativ'),'<b>','N','ETAPA'));
                } 
              } 
            } 
          } 
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
    ShowHTML('            <tr><td colspan="2">Meta:<b><br><font size=2>'.MontaOrdemEtapa($w_chave_aux).'. '.$w_titulo.'</font></td></tr>');
    ShowHTML('            <tr><td colspan="2">Descrição:<b><br>'.$w_descricao.'</td></tr>');
    ShowHTML('            <tr><td valign="top" colspan="2">');
    ShowHTML('              <table border=0 width="100%" cellspacing=0><tr valign="top">');
    ShowHTML('                <td>Meta LOA?<b><br>'.$w_nm_programada.'</td>');
    ShowHTML('                <td>Meta cumulativa:<b><br>'.$w_nm_cumulativa.'</td></tr>');
    ShowHTML('              </table></td></tr>');
    ShowHTML('            <tr><td valign="top" colspan="2">');
    ShowHTML('              <table border=0 width="100%" cellspacing=0><tr valign="top">');
    ShowHTML('                <td>Quantitativo:<b><br>'.$w_quantidade.'</td>');
    ShowHTML('                <td>Unidade de medida:<b><br>'.Nvl($w_unidade_medida,'---').'</td></tr>');
    ShowHTML('              </table></td></tr>');
    ShowHTML('            <tr><td valign="top" colspan="2">');
    ShowHTML('              <table border=0 width="100%" cellspacing=0><tr valign="top">');
    ShowHTML('                <td>Previsão início:<b><br>'.FormataDataEdicao(Nvl($w_inicio,time())).'</td>');
    ShowHTML('                <td>Previsão término:<b><br>'.FormataDataEdicao($w_fim).'</td></tr>');
    ShowHTML('                <tr valign="top">');
    $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$w_sq_pessoa,null,null);
    ShowHTML('                  <td>Responsável pela meta:<b><br>'.f($RS,'nome_resumido').'</td>');
    $RS = db_getUorgData::getInstanceOf($dbms,$w_sq_unidade);
    ShowHTML('                  <td>Setor responsável pela meta:<b><br>'.f($RS,'nome').' ('.f($RS,'sigla').')</td></tr>');
    $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$w_sq_pessoa_atualizacao,null,null);
    ShowHTML('                <tr><td colspan="2">Criação/última atualização:<b><br>'.FormataDataEdicao($w_ultima_atualizacao).'</b>, feita por <b>'.f($RS,'nome_resumido').' ('.f($RS,'sigla').')</b></td></tr>');
    ShowHTML('              </table></td></tr>');
    ShowHTML('          </TABLE>');
    ShowHTML('      </table>');
    ShowHTML('    <tr bgcolor="'.$conTrBgColor.'"><td align="center" colspan="2">');
    ShowHTML('      <table width="100%" border="0">');
    if ($O=='V') {
      ShowHTML('     <tr><td valign="top">');
      ShowHTML('       <table border=0 width="100%" cellspacing=0><tr valign="top">');
      ShowHTML('         <tr><td>&nbsp<td><br><b>Quantitativo realizado</b></td>');
      ShowHTML('             <td>&nbsp<td><br><b>Quantitativo realizado</b></td>');
      ShowHTML('         <tr><td width="10%" align="right"><b>Janeiro:');
      ShowHTML('             <td width="30%">'.Nvl($w_quantitativo_1,'---').'</td>');
      ShowHTML('             <td width="20%" align="right"><b>Julho:');
      ShowHTML('             <td>'.Nvl($w_quantitativo_7,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Fevereiro:');
      ShowHTML('             <td>'.Nvl($w_quantitativo_2,'---').'</td>');
      ShowHTML('             <td align="right"><b>Agosto:');
      ShowHTML('             <td>'.Nvl($w_quantitativo_8,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Março:');
      ShowHTML('             <td>'.Nvl($w_quantitativo_3,'---').'</td>');
      ShowHTML('             <td align="right"><b>Setembro:');
      ShowHTML('             <td>'.Nvl($w_quantitativo_9,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Abril:');
      ShowHTML('             <td>'.Nvl($w_quantitativo_4,'---').'</td>');
      ShowHTML('             <td align="right"><b>Outubro:');
      ShowHTML('             <td>'.Nvl($w_quantitativo_10,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Maio:');
      ShowHTML('             <td>'.Nvl($w_quantitativo_5,'---').'</td>');
      ShowHTML('             <td align="right"><b>Novembro:');
      ShowHTML('             <td>'.Nvl($w_quantitativo_11,'---').'</td>');
      ShowHTML('         <tr><td align="right"><b>Junho:');
      ShowHTML('             <td>'.Nvl($w_quantitativo_6,'---').'</td>');
      ShowHTML('             <td align="right"><b>Dezembro:');
      ShowHTML('             <td>'.Nvl($w_quantitativo_12,'---').'</td>');
      ShowHTML('       </table>');
      ShowHTML('     <tr><td>Percentual de conlusão:<br><b>'.nvl($w_perc_conclusao,0).'%</b></td>');
      ShowHTML('     <tr><td valign="top">Situação atual da meta:<b><br>'.Nvl($w_situacao_atual,'---').'</td>');
      ShowHTML('     <tr><td valign="top">Justificar os motivos casso de não cumprimento da meta:<b><br>'.Nvl($w_justificativa_inex,'---').'</td>');
      ShowHTML('     <tr><td valign="top">Quais medidas necessárias para o cumprimento da meta:<b><br>'.Nvl($w_outras_medidas,'---').'</td>');
    } else {
      ShowHTML('     <tr><td>Percentual de conlusão:<br><b>'.nvl($w_perc_conclusao,0).'%</b></td>');
      ShowHTML('     <tr><td valign="top" colspan="1">');
      ShowHTML('       <table border=0 width="100%" cellspacing=0>');
      ShowHTML('         <tr><td>&nbsp<td><br><b>Quantitativo realizado</b></td>');
      ShowHTML('             <td>&nbsp<td><br><b>Quantitativo realizado</b></td>');
      ShowHTML('         <tr><td width="8%" align="right"><b>Janeiro:');
      ShowHTML('             <td width="15%"><INPUT TYPE="TEXT" CLASS="STI" NAME="w_quantitativo_1" SIZE=10 MAXLENGTH=18 VALUE="'.$w_quantitativo_1.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td width="5%" align="right"><b>Julho:');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_quantitativo_7" SIZE=10 MAXLENGTH=18 VALUE="'.$w_quantitativo_7.'" '.$w_Disabled.'></td>');
      ShowHTML('         <tr><td align="right"><b>Fevereiro:');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_quantitativo_2" SIZE=10 MAXLENGTH=18 VALUE="'.$w_quantitativo_2.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td align="right"><b>Agosto:');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_quantitativo_8" SIZE=10 MAXLENGTH=18 VALUE="'.$w_quantitativo_8.'" '.$w_Disabled.'></td>');
      ShowHTML('         <tr><td align="right"><b>Março:');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_quantitativo_3" SIZE=10 MAXLENGTH=18 VALUE="'.$w_quantitativo_3.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td align="right"><b>Setembro:');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_quantitativo_9" SIZE=10 MAXLENGTH=18 VALUE="'.$w_quantitativo_9.'" '.$w_Disabled.'></td>');
      ShowHTML('         <tr><td align="right"><b>Abril:');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_quantitativo_4" SIZE=10 MAXLENGTH=18 VALUE="'.$w_quantitativo_4.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td align="right"><b>Outubro:');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_quantitativo_10" SIZE=10 MAXLENGTH=18 VALUE="'.$w_quantitativo_10.'" '.$w_Disabled.'></td>');
      ShowHTML('         <tr><td align="right"><b>Maio:');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_quantitativo_5" SIZE=10 MAXLENGTH=18 VALUE="'.$w_quantitativo_5.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td align="right"><b>Novembro:');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_quantitativo_11" SIZE=10 MAXLENGTH=18 VALUE="'.$w_quantitativo_11.'" '.$w_Disabled.'></td>');
      ShowHTML('         <tr><td align="right"><b>Junho:');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_quantitativo_6" SIZE=10 MAXLENGTH=18 VALUE="'.$w_quantitativo_6.'" '.$w_Disabled.'></td>');
      ShowHTML('             <td align="right"><b>Dezembro:');
      ShowHTML('             <td><INPUT TYPE="TEXT" CLASS="STI" NAME="w_quantitativo_12" SIZE=10 MAXLENGTH=18 VALUE="'.$w_quantitativo_12.'" '.$w_Disabled.' ></td>');
      ShowHTML('       </table>');
      ShowHTML('     <tr><td valign="top"><b><u>S</u>ituação atual da meta:</b><br><textarea '.$w_Disabled.' accesskey="S" name="w_situacao_atual" class="STI" ROWS=5 cols=75 title="Descreva a situação em a etapa encontra-se.">'.$w_situacao_atual.'</TEXTAREA></td>');
      ShowHTML('     <tr valign="top">');
      MontaRadioSN('<b>A meta será cumprida?</b>',$w_exequivel,'w_exequivel');
      ShowHTML('     </tr>');
      ShowHTML('     <tr><td valign="top"><b><u>J</u>ustificar os motivos casso de não cumprimento da meta:</b><br><textarea '.$w_Disabled.' accesskey="J" name="w_justificativa_inex" class="STI" ROWS=5 cols=75>'.$w_justificativa_inex.'</TEXTAREA></td>');
      ShowHTML('     <tr><td valign="top"><b><u>Q</u>uais medidas necessárias para o cumprimento da meta?</b><br><textarea '.$w_Disabled.' accesskey="Q" name="w_outras_medidas" class="STI" ROWS=5 cols=75>'.$w_outras_medidas.'</TEXTAREA></td>');
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
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  if ($w_tipo!='WORD') Rodape();
  } 

// =========================================================================
// Rotina de recursos da ação
// -------------------------------------------------------------------------

function Recursos() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_pai  = $_REQUEST['w_chave_pai'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_nome         = $_REQUEST['w_nome'];
    $w_tipo         = $_REQUEST['w_tipo'];
    $w_descricao    = $_REQUEST['w_descricao'];
    $w_finalidade   = $_REQUEST['w_finalidade'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getSolicRecurso::getInstanceOf($dbms,$w_chave,null,'LISTA');
    $RS = SortArray($RS,'TIPO, NOME');
  }  elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
     // Recupera os dados do endereço informado
     $RS = db_getSolicRecurso::getInstanceOf($dbms,$w_chave,$w_chave_aux,'REGISTRO');
     foreach ($RS as $row) {$RS=$row; break;}
     $w_nome       = f($RS,'nome');
     $w_tipo       = f($RS,'tipo');
     $w_descricao  = f($RS,'descricao');
     $w_finalidade = f($RS,'finalidade');
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','','1','2','100','1','1');
      Validate('w_tipo','Tipo do recurso','SELECT','1','1','10','','1');
      Validate('w_descricao','Descricao','','','2','2000','1','1');
      Validate('w_finalidade','Finalidade','','','2','2000','1','1');
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
    BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
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
    ShowHTML('          <td><b>Tipo</td>');
    ShowHTML('          <td><b>Nome</td>');
    ShowHTML('          <td><b>Finalidade</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.RetornaTipoRecurso(f($row,'tipo')).'</td>');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td>'.CRLF2BR(Nvl(f($row,'finalidade'),'---')).'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_projeto_recurso').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_projeto_recurso').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled=' DISABLED ';
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td valign="top"><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="STI" SIZE="90" MAXLENGTH="100" VALUE="'.$w_nome.'" title="Informe o nome do recurso."></td>');
    ShowHTML('      <tr>');
    SelecaoTipoRecurso('<u>T</u>ipo:','T','Selecione o tipo deste recurso.',$w_tipo,null,'w_tipo',null,null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b><u>D</u>escrição:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_descricao" class="STI" ROWS=5 cols=75 title="Descreva, se necessário, características deste recurso (conhecimentos, habilidades, perfil, capacidade etc).">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b><u>F</u>inalidade:</b><br><textarea '.$w_Disabled.' accesskey="F" name="w_finalidade" class="STI" ROWS=5 cols=75 title="Descreva, se necessário, a finalidade deste recurso para a ação (funções desempenhadas, papel, objetivos etc).">'.$w_finalidade.'</TEXTAREA></td>');
    ShowHTML('      <tr>');
    ShowHTML('      <tr><td align="center" colspan=4><hr>');
    if ($O=='E') {
        ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
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
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de alteração dos recursos da etapa
// -------------------------------------------------------------------------

function EtapaRecursos() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_chave_pai  = $_REQUEST['w_chave_pai'];
  $RS = db_getSolicEtpRec::getInstanceOf($dbms,$w_chave_aux,null,null);
  $RS = SortArray($RS,'tipo','asc','nome','asc');
  Cabecalho();
  ShowHTML('<HEAD>');
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  //ShowHTML '  for (i = 0; i < theForm.w_recurso.length; i++) {'
  //ShowHTML '      if (theForm.w_recurso[i].checked) break;'
  //ShowHTML '      if (i == theForm.w_recurso.length-1) {'
  //ShowHTML '         alert('Você deve selecionar pelo menos um recurso!');'
  //ShowHTML '         return false;'
  //ShowHTML '      }'
  //ShowHTML '  }'
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=this.focus();');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr><td align="center" bgcolor="#FAEBD7"><table border=1 width="100%"><tr><td>');
  ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr valign="top">');
  ShowHTML('          <td>Etapa:<br><b>'.MontaOrdemEtapa($w_chave_aux).' - '.f($RS,'titulo').'</td>');
  ShowHTML('          <td>Início:<br> <b>'.FormataDataEdicao(f($RS,'inicio_previsto')).'</td>');
  ShowHTML('          <td>Término:<br><b>'.FormataDataEdicao(f($RS,'fim_previsto')).'</td>');
  ShowHTML('        <tr colspan=3><td>Descrição:<br><b>'.CRLF2BR(f($RS,'descricao')).'</td></tr>');
  ShowHTML('    </TABLE>');
  ShowHTML('</table>');
  ShowHTML('<tr><td align="right">&nbsp;');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'ETAPAREC',$R,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
  ShowHTML('<INPUT type="hidden" name="w_sg" value="'.$_REQUEST['w_sg'].'">');
  ShowHTML('<INPUT type="hidden" name="w_recurso" value="">');
  ShowHTML('<tr><td><ul><b>Informações:</b><li>Indique abaixo quais recursos estarão alocados a esta etapa da ação.<li>A princípio, uma etapa não tem nenhum recurso alocado.<li>Para remover um recurso, desmarque o quadrado ao seu lado.</ul>');
  ShowHTML('<tr><td align="center" colspan=3>');
  ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
  ShowHTML('          <td><b>&nbsp;</td>');
  ShowHTML('          <td><b>Tipo</td>');
  ShowHTML('          <td><b>Recurso</font></td>');
  ShowHTML('          <td><b>Finalidade</td>');
  ShowHTML('        </tr>');
  if (count($RS)<=0) {
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><font  size="2"><b>Não foram encontrados registros.</b></td></tr>');
  } else {
    foreach ($RS as $row) {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
      if (Nvl(f($row,'existe'),0)>0) {
        ShowHTML('        <td align="center"><input type="checkbox" name="w_recurso" value="'.f($row,'sq_projeto_recurso').'" checked></td>');
      } else {
        ShowHTML('        <td align="center"><input type="checkbox" name="w_recurso" value="'.f($row,'sq_projeto_recurso').'"></td>');
      } 
      ShowHTML('        <td align="left">'.RetornaTipoRecurso(f($row,'tipo')).'</td>');
      ShowHTML('        <td align="left">'.f($row,'nome').'</td>');
      ShowHTML('        <td align="left">'.CRLF2BR(Nvl(f($row,'finalidade'),'---')).'</td>');
      ShowHTML('      </tr>');
    } 
  } 
  ShowHTML('      </center>');
  ShowHTML('    </table>');
  ShowHTML('  </td>');
  ShowHTML('</tr>');
  ShowHTML('      <tr><td align="center">&nbsp;');
  ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000">');
  ShowHTML('      <tr><td align="center">');
  ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
  ShowHTML('            <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Cancelar">');
  ShowHTML('          </td>');
  ShowHTML('      </tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  ShowHTML('</FORM>');
  Rodape();
} 

// =========================================================================
// Rotina de interessados
// -------------------------------------------------------------------------

function Interessados() {
  extract($GLOBALS);
  Global $_Disabled;
  $w_chave         = $_REQUEST['w_chave'];
  $w_chave_aux     = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_tipo_visao  = $_REQUEST['w_tipo_visao'];
    $w_envia_email = $_REQUEST['w_envia_email'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getSolicInter::getInstanceOf($dbms,$w_chave,$null,'LISTA');
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
    BodyOpen('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('      <tr><td colspan=3>Usuários que terão acesso à visualização dos dados desta ação.</td></tr>');
    ShowHTML('      <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    if ($P1!=4) {
      ShowHTML('<tr><td><font size="2"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    } else {
      $RS1 = db_getSolicData::getInstanceOf($dbms,$w_chave,'ORVISUAL');
      ShowHTML('<tr><td colspan=3 align="center" bgcolor="#FAEBD7"><table border=1 width="100%"><tr><td>');
      ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('        <tr valign="top">');
      if (f($RS1,'sq_acao_ppa')>'') {
        ShowHTML('          <td><b>Ação PPA: </b><br>'.f($RS1,'nm_ppa').' ('.f($RS1,'cd_ppa').'.'.f($RS1,'cd_ppa_pai').')</b>');
      } 
      if (f($RS1,'sq_orprioridade')>'') {
        ShowHTML('        <td><b>Iniciativa prioritária: </b><br>'.f($RS1,'nm_pri').' </b>');
      } 
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
    ShowHTML('          <td><b>Visao</td>');
    ShowHTML('          <td><b>Envia e-mail</td>');
    if ($P1!=4) ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem   
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.ExibePessoa('../',$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'nome').' ('.f($row,'lotacao').')').'</td>');
        ShowHTML('        <td>'.RetornaTipoVisao(f($row,'tipo_visao')).'</td>');
        ShowHTML('        <td align="center">'.str_replace('N','Não',str_replace('S','Sim',f($row,'envia_email'))).'</td>');
        if ($P1!=4) {
          ShowHTML('        <td align="top" nowrap>');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_pessoa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
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
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled =' DISABLED ';
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    if ($O=='I') {
      SelecaoPessoa('<u>P</u>essoa:','N','Selecione o interessado na relação.',$w_chave_aux,$w_chave,'w_chave_aux','INTERES');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
      ShowHTML('      <tr><td valign="top"><b>Pessoa:</b><br>'.$w_nome.'</td>');
    } 
    SelecaoTipoVisao('<u>T</u>ipo de visão:','T','Selecione o tipo de visão que o interessado terá desta ação.',$w_tipo_visao,null,'w_tipo_visao',null,null);
    ShowHTML('          </table>');
    ShowHTML('      <tr>');
    MontaRadioNS('<b>Envia e-mail ao interessado quando houver encaminhamento?</b>',$w_envia_email,'w_envia_email');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td align="center" colspan=4><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
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
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de áreas envolvidas
// -------------------------------------------------------------------------
function Areas() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave     = $_REQUEST['w_chave'];
  $w_chave_aux = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_papel = $_REQUEST['w_papel'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getSolicAreas::getInstanceOf($dbms,$w_chave,null,'LISTA');
    $RS = SortArray($RS,'nome','asc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados do endereço informado
    $RS = db_getSolicAreas::getInstanceOf($dbms,$w_chave,$w_chave_aux,'REGISTRO');
    foreach($RS as $row) {$RS=$row; break;}
    $w_nome  = f($RS,'nome');
    $w_papel = f($RS,'papel');
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    $modulo;
    $checkbranco;
    formatadata();
    SaltaCampo();
    FormataCEP();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_chave_aux','Área/Instituição','HIDDEN','1','1','10','','1');
      Validate('w_papel','Papel desempenhado','','1','1','2000','1','1');
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
    BodyOpen('onLoad=\'this.focus()\';');
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
    ShowHTML('          <td><b>Área/Instituição</td>');
    ShowHTML('          <td><b>Papel</td>');
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
        ShowHTML('        <td>'.f($row,'papel').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A>&nbsp');
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
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    if ($O=='I') {
      SelecaoUnidade('<U>Á</U>rea/Instituição:','A',null,$w_chave_aux,null,'w_chave_aux',null,null);
    } else {
      ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
      ShowHTML('      <tr><td valign="top"><b>Área/Instituição:</b><br>'.$w_nome.'</td>');
    } 
    ShowHTML('      <tr><td valign="top"><b><u>P</u>apel desempenhado:</b><br><textarea '.$w_Disabled.' accesskey="P" name="w_papel" class="STI" ROWS=5 cols=75 title="Descreva o papel desempenhado pela área ou instituição na execução da ação.">'.$w_papel.'</TEXTAREA></td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" colspan=4><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
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
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de visualização
// -------------------------------------------------------------------------
function Visual() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];
  $w_tipo  = strtoupper(trim($_REQUEST['w_tipo']));
  // Recupera o logo do cliente a ser usado nas listagens
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  if (f($RS,'logo')>'') {
    $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
  } 
  if ($w_tipo=='WORD') {
    header('Content-type: '.'application/msword');
  } else {
    Cabecalho();
  } 
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Visualização de Ação</TITLE>');
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_tipo!='WORD') {
    BodyOpenClean('onLoad=\'this.focus()\'; ');
  } 
  ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
  if ($P1==1) {
    ShowHTML('Iniciativas Prioritárias do Governo <BR> Relatório Geral por Ação');
  } elseif ($P1==2) {
    ShowHTML('Plano Plurianual 2004 - 2007 <BR> Relatório Geral por Ação');
  } else {
    ShowHTML('Visualização de Ação');
  } 
  ShowHTML('</FONT><TR><TD ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
  if ($w_tipo!='WORD'){
    ShowHTML('&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
    ShowHTML('&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif" onClick="window.open(\''.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.$w_chave.'&w_tipo=word&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4=1&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\',\'VisualAcaoWord\',\'menubar=yes resizable=yes scrollbars=yes\');">');
  } 
  ShowHTML('</TD></TR>');
  ShowHTML('</FONT></B></TD></TR></TABLE>');
  //ShowHTML('<HR>');
  if ($w_tipo>'' && $w_tipo!='WORD') {
    ShowHTML('<center><B><font size=1>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></font></center>');
  } 
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML(VisualProjeto($w_chave,'L',$w_usuario,$P1,$P4));
  if ($w_tipo>'' && $w_tipo!='WORD') {
    ShowHTML('<center><B><font size=1>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></font></center>');
  } 
  if ($w_tipo!='WORD') {
    Rodape();
  } 
} 
// =========================================================================
// Rotina de exclusão
// -------------------------------------------------------------------------
function Excluir() {
  extract($GLOBALS);
  Global $w_Disabled;
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
  ShowHTML(VisualProjeto($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'ORGERAL',$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'ORGERAL');
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
// ------------------------------------------------------------------------- 
// Rotina de anexos 
// ------------------------------------------------------------------------- 

function Anexos() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave     = $_REQUEST['w_chave'];
  $w_chave_aux = $_REQUEST['w_chave_aux'];
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
    foreach ($RS as $row) {$RS=$row; break;}
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
      if ($O=='I') {
        Validate('w_caminho','Arquivo','','1','5','255','1','1');
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
      //ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b><font color="#BC3131">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de '.f($RS,'upload_maximo')/1024.' KBytes</b>.</font></td>');
      ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
    } 
    ShowHTML('      <tr><td><b><u>T</u>ítulo:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_nome" class="STI" SIZE="75" MAXLENGTH="255" VALUE="'.$w_nome.'" title="OBRIGATÓRIO. Informe um título para o arquivo."></td>');
    ShowHTML('      <tr><td><b><u>D</u>escrição:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_descricao" class="STI" ROWS=5 cols=65 title="OBRIGATÓRIO. Descreva a finalidade do arquivo.">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OBRIGATÓRIO. Clique no botão ao lado para localizar o arquivo. Ele será transferido automaticamente para o servidor.">');
    if ($w_caminho>'') {
      ShowHTML('              <b>'.LinkArquivo('SS',$w_cliente,$w_caminho,'_blank','Clique para exibir o arquivo atual.','Exibir',null).'</b>');
    } 
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
    //ShowHTML ' history.back(1);' 
    ScriptClose();
  } 
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
  $w_chave     = $_REQUEST['w_chave'];
  $w_chave_aux = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_tramite      = $_REQUEST['w_tramite'];
    $w_destinatario = $_REQUEST['w_destinatario'];
    $w_novo_tramite = $_REQUEST['w_novo_tramite'];
    $w_despacho     = $_REQUEST['w_despacho'];
  } else {
    $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'ORGERAL');
    $w_tramite      = f($RS,'sq_siw_tramite');
    $w_novo_tramite = f($RS,'sq_siw_tramite');
  } 
  // Recupera a sigla do trâmite desejado, para verificar a lista de possíveis destinatários.
  $RS = db_getTramiteData::getInstanceOf($dbms,$w_novo_tramite);
  $w_sg_tramite = f($RS,'sigla');
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if (!(strpos('V',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_destinatario','Destinatário','HIDDEN','1','1','10','','1');
    Validate('w_despacho','Despacho','','1','1','2000','1','1');
    Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    if (($P1!=1) || ($P1==1 && $w_tipo=='Volta')) {
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
    BodyOpen('onLoad=\'document.Form.w_destinatario.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML(VisualProjeto($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'ORENVIO',$w_pagina.$par,$O);
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
    SelecaoFase('<u>F</u>ase da ação:','F','Se deseja alterar a fase atual da ação, selecione a fase para a qual deseja enviá-la.',$w_novo_tramite,$w_menu,null,'w_novo_tramite',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
    // Se for envio para o cadastramento, exibe apenas as pessoas autorizadas a fazê-lo.
    if ($w_sg_tramite=='CI') {
      SelecaoSolicResp('<u>D</u>estinatário:','D','Selecione, na relação, um destinatário para a ação.',$w_destinatario,$w_chave,$w_novo_tramite,$w_novo_tramite,'w_destinatario','CADASTRAMENTO');
    } else {
      SelecaoPessoa('<u>D</u>estinatário:','D','Selecione, na relação, um destinatário para a ação.',$w_destinatario,null,'w_destinatario','USUARIOS');
    } 
  } else {
    SelecaoFase('<u>F</u>ase da ação:','F','Se deseja alterar a fase atual da ação, selecione a fase para a qual deseja enviá-la.',$w_novo_tramite,$w_menu,null,'w_novo_tramite',null,null);
    SelecaoPessoa('<u>D</u>estinatário:','D','Selecione, na relação, um destinatário para a ação.',$w_destinatario,null,'w_destinatario','USUARIOS');
  } 
  ShowHTML('    <tr><td valign="top" colspan=2><b>D<u>e</u>spacho:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Descreva o papel desempenhado pela área ou instituição na execução da ação.">'.$w_despacho.'</TEXTAREA></td>');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
  if ($P1!=1) {
    // Se não for cadastramento, volta para a listagem
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
  Global $w_Disabled;
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
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML(VisualProjeto($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'ORENVIO',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'ORGERAL');
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
    $w_inicio_real    = $_REQUEST['w_inicio_real'];
    $w_fim_real       = $_REQUEST['w_fim_real'];
    $w_concluida      = $_REQUEST['w_concluida'];
    $w_data_conclusao = $_REQUEST['w_data_conclusao'];
    $w_nota_conclusao = $_REQUEST['w_nota_conclusao'];
    $w_custo_real     = $_REQUEST['w_custo_real'];
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if (!(strpos('V',$O)===false)) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    FormataDataHora();
    FormataValor();
    ValidateOpen('Validacao');
    switch (f($RS_Menu,'data_hora')) {
      case 1: Validate('w_fim_real','Término da execução','DATA',1,10,10,'','0123456789/');            break;
      case 2: Validate('w_fim_real','Término da execução','DATAHORA',1,17,17,'','0123456789/');        break;
      case 3: Validate('w_inicio_real','Início da execução','DATA',1,10,10,'','0123456789/');
              Validate('w_fim_real','Término da execução','DATA',1,10,10,'','0123456789/');
              CompData('w_inicio_real','Início da execução','<=','w_fim_real','Término da execução');
              CompData('w_fim_real','Término da execução','<=',FormataDataEdicao(time()),'data atual'); break;
      case 4: Validate('w_inicio_real','Início da execução','DATAHORA',1,17,17,'','0123456789/,: ');
              Validate('w_fim_real','Término da execução','DATAHORA',1,17,17,'','0123456789/,: ');
              CompData('w_inicio_real','Início da execução','<=','w_fim_real','Término da execução');  break;
    } 
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
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML(VisualProjeto($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
  ShowHTML('          <tr>');
  // Verifica se a ação tem etapas em aberto e avisa o usuário caso isso ocorra.
  $RS = db_getSolicEtapa::getInstanceOf($dbms,$w_chave,null,'LISTA',null);
  $w_cont=0;
  foreach ($RS as $row){
    if (f($row,'perc_conclusao')<100) {
      $w_cont+=1;
    } 
  } 
  if ($w_cont>0) {
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'ATENÇÃO: das '.count($RS).' metas desta ação, '.$w_cont.' não têm 100% de conclusão!\n\nAinda assim você poderá concluir esta ação.\');');
    ScriptClose();
  } 
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'ORCONC',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_concluida" value="S">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'ORGERAL');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  switch (f($RS_Menu,'data_hora')) {
    case 1:  ShowHTML('              <td valign="top"><b><u>T</u>érmino da execução:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim_real.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de término da execução da ação."></td>');              break;
    case 2:  ShowHTML('              <td valign="top"><b><u>T</u>érmino da execução:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim_real" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_fim_real.'" onKeyDown="FormataDataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);" title="Informe a data/hora de término da execução da ação."></td>');     break;
    case 3:  ShowHTML('              <td valign="top"><b>Iní<u>c</u>io da execução:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio_real.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data/hora de início da execução da ação."></td>');
             ShowHTML('              <td valign="top"><b><u>T</u>érmino da execução:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim_real.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de término da execução da ação."></td>');               break;
    case 4:  ShowHTML('              <td valign="top"><b>Iní<u>c</u>io da execução:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio_real" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_inicio_real.'" onKeyDown="FormataDataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);" title="Informe a data/hora de início da execução da ação."></td>');
             ShowHTML('              <td valign="top"><b><u>T</u>érmino da execução:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim_real" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_fim_real.'" onKeyDown="FormataDataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);" title="Informe a data de término da execução da ação."></td>');           break;
  } 
    ShowHTML('              <td valign="top"><b><u>R</u>ecurso executado:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_custo_real" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_custo_real.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o recurso utilizado para execução da ação, ou zero se não for o caso."></td>');
    ShowHTML('          </table>');
    ShowHTML('    <tr><td valign="top"><b>Nota d<u>e</u> conclusão:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_nota_conclusao" class="STI" ROWS=5 cols=75 title="Descreva o quanto a ação atendeu aos resultados esperados.">'.$w_nota_conclusao.'</TEXTAREA></td>');
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
function EtapaLinha($l_chave,$l_chave_aux,$l_titulo,$l_resp,$l_setor,$l_inicio,$l_fim,$l_perc,$l_word,$l_destaque,$l_oper,$l_tipo) {
  extract($GLOBALS);
  Global $w_Disabled;
  $l_recurso = '';
  $RSQuery = db_getSolicEtpRec::getInstanceOf($dbms,$l_chave_aux,null,'EXISTE');
  if (count($RSQuery)>0) {
    $l_recurso=$l_recurso.chr(13).'      <tr bgcolor=w_cor valign="top"><td colspan=3><table border=0 width="100%"><tr><td>Recurso(s): ';
    foreach($RSQuery as $row) {
      $l_recurso=$l_recurso.chr(13).(f($row,'nome')).'; ';
    } 
    $l_recurso=$l_recurso.chr(13).'      </tr></td></table></td></tr>';
  } 
  if ($l_recurso>'') {
    $l_row='rowspan=2';
  } else {
    $l_row='';
  }
//  $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
  $l_html.=chr(13).'      <tr valign="top">';
  $l_html.=chr(13).'        <td nowrap '.$l_row.'>';
  if ($l_fim<time() && $l_perc<100) {
    $l_html.=chr(13).'           <img src="'.$conImgAtraso.'" border=0 width=15 height=15 align="center">';
  } elseif ($l_perc<100) {
    $l_html.=chr(13).'           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">';
  } else {
    $l_html.=chr(13).'           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">';
  } 
  if ($l_word==1) {
    $l_html.=chr(13).'        <td>'.$l_destaque.$l_titulo.'</b>';
  } else {
    $l_html.=chr(13).'<A class="HL" HREF="javascript:this.status.value;" onClick="window.open(\'projeto.php?par=AtualizaEtapa&O=V&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&w_tipo=Volta&P1=10&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\',\'Meta\',\'width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no\'); return false;" title="Clique para exibir os dados!">'.$l_destaque.$l_titulo.'</A>';
  } 
  $l_html.=chr(13).'        <td align="center" '.$l_row.'>'.FormataDataEdicao($l_fim).'</td>';
  $l_html.=chr(13).'        <td nowrap align="right" '.$l_row.'>'.$l_perc.' %</td>';
  if ($l_oper=='S') {
    $l_html.=chr(13).'        <td align="top" nowrap '.$l_row.'>';
    // Se for listagem de etapas no cadastramento da ação, exibe operações de alteração, exclusão e recursos
    if ($l_tipo=='PROJETO') {
      $l_html.=chr(13).'          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Alterar">Alt</A>&nbsp';
      $l_html.=chr(13).'          <A class="HL" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');" title="Excluir">Excl</A>&nbsp';
      // Caso contrário, é listagem de atualização de etapas. Neste caso, coloca apenas a opção de alteração
    } else {
      $l_html.=chr(13).'          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Atualiza dados da etapa">Atualizar</A>&nbsp';
    } 
    $l_html.=chr(13).'        </td>';
  } else {
    if ($l_tipo=='ETAPA') {
      $l_html.=chr(13).'        <td align="top" nowrap '.$l_row.'>';
      $l_html.=chr(13).'          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=V&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Atualiza dados da etapa">Exibir</A>&nbsp';
      $l_html.=chr(13).'        </td>';
    } 
  } 
  $l_html.=chr(13).'      </tr>';
  if ($l_recurso>'') {
    $l_html.=chr(13).str_replace('w_cor',$w_cor,$l_recurso);
  } 
  //EtapaLinha=$l_html;
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
  global $w_Disabled;
  //Verifica se o cliente está configurado para receber email na tramitaçao de solicitacao
  $RS = db_getCustomerData::getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
  // Recupera os dados da ação
  $RSM = db_getSolicData::getInstanceOf($dbms,$p_solic,'PJGERAL');
  if(f($RS,'envia_mail_tramite')=='S' && (f($RS_Menu,'envia_email')=='S') && (f($RSM,'envia_mail')=='S')) {
    $l_solic          = $p_solic;
    $w_destinatarios  = '';
    $w_resultado      = '';
    $w_html = '<HTML>'.$crlf;
    $w_html.=BodyOpenMail().$crlf;
    $w_html.='<table border="0" cellpadding="0" cellspacing="0" width="100%">'.$crlf;
    $w_html.='<tr><td align="center">'.$crlf;
    $w_html.='    <table width="97%" border="0">'.$crlf;
    if ($p_tipo==1)     $w_html.='      <tr valign="top"><td align="center"><b>INCLUSÃO DE AÇÃO</b><br><br><td></tr>'.$crlf;
    elseif ($p_tipo==2) $w_html.='      <tr valign="top"><td align="center"><b>TRAMITAÇÃO DE AÇÃO</b><br><br><td></tr>'.$crlf;
    elseif ($p_tipo==3) $w_html.='      <tr valign="top"><td align="center"><b>CONCLUSÃO DE AÇÃO</b><br><br><td></tr>'.$crlf;
    $w_html.='      <tr valign="top"><td><b><font color="#BC3131">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><td></tr>'.$crlf;

    $w_nome = 'Ação '.f($RSM,'titulo');
    $w_html.=$crlf.'<tr><td align="center">';
    $w_html.=$crlf.'    <table width="99%" border="0">';
    $w_html.=$crlf.'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    $w_html.=$crlf.'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>AÇÃO: '.f($RSM,'titulo').' ('.f($RSM,'sq_siw_solicitacao').')</b></font></div></td></tr>';
    $w_html.=$crlf.'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    //$w_html.=$crlf.'      <tr><td>Ação: <b>'.f($RSM,'titulo').'</b></td>';
    // Identificação da ação
    $w_html .= $crlf.'      <tr><td colspan="2"><br><font size="2"><b>EXTRATO DA AÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    // Se a classificação foi informada, exibe.
    if (nvl(f($RSM,'sq_cc'),'')>'') {  
      $w_html.=$crlf.'      <tr><td width="30%">Classificação:</b></td>';
      $w_html.=$crlf.'          <td>'.f($RSM,'cc_nome').' </td></tr>';
    }
    $w_html.=$crlf.'        <tr><td width="30%"><b>Responsável pelo monitoramento:</b></td>';
    $w_html.=$crlf.'          <td>'.f($RSM,'nm_sol').'</td></tr>';
    $w_html.=$crlf.'        <tr><td><b>Unidade responsável pelo monitoramento:</b></td>';
    $w_html.=$crlf.'          <td>'.f($RSM,'nm_unidade_resp').'</td></tr>';
    $w_html.=$crlf.'        <tr><td><b>Data de recebimento:</b></td>';
    $w_html.=$crlf.'          <td>'.FormataDataEdicao(f($RSM,'inicio')).' </td></tr>';
    $w_html.=$crlf.'        <tr><td><b>Limite para conclusão:</b></td>';
    $w_html.=$crlf.'          <td>'.FormataDataEdicao(f($RSM,'fim')).' </td></tr>';
    // Informações adicionais
    if (Nvl(f($RSM,'descricao'),'')>'') {
      $w_html.=$crlf.'      <tr><td><b>Resultados da ação:</b></td>';
      $w_html.=$crlf.'        <td>'.CRLF2BR(f($RSM,'descricao')).' </td></tr>';  
    }
    $w_html.=$crlf.'</tr>';
    // Dados da conclusão da ação, se ela estiver nessa situação
    if (f($RSM,'concluida')=='S' && Nvl(f($RSM,'data_conclusao'),'')>'') {
      $w_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONCLUSÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
      $w_html.=$crlf.'      <tr><td><b>Início da execução:</b></td>';
      $w_html.=$crlf.'        <td>'.FormataDataEdicao(f($RSM,'inicio_real')).' </td></tr>';
      $w_html.=$crlf.'      <tr><td><b>Término da execução:</b></td>';
      $w_html.=$crlf.'        <td>'.FormataDataEdicao(f($RSM,'fim_real')).' </td></tr>';
      $w_html.=$crlf.'      <tr><td><b>Nota de conclusão:</b></td>';
      $w_html.=$crlf.'        <td>'.CRLF2BR(f($RSM,'nota_conclusao')).' </td></tr>';
    } 
    if ($p_tipo==2) {
      // Se for tramitação
      $RS = db_getSolicLog::getInstanceOf($dbms,$p_solic,null,'LISTA');
      $RS = SortArray($RS,'phpdt_data','desc','despacho','desc');
      foreach ($RS as $row) { $RS = $row; if(nvl(f($row,'destinatario'),'')!='') break; }
      $w_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>ÚLTIMO ENCAMINHAMENTO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
      $w_html.=$crlf.'      <tr><td><b>De: </b></td>';
      $w_html.=$crlf.'        <td>'.f($RS,'responsavel').'</td></tr>'; 
      $w_html.=$crlf.'      <tr><td><b>Para: </b></td>';
      $w_html.=$crlf.'        <td>'.f($RS,'destinatario').'</td></tr>'; 
      $w_html.=$crlf.'       <tr valign="top"><td><b>Despacho:</b></td>';
      $w_html.=$crlf.'        <td>'.CRLF2BR(Nvl(f($RS,'despacho'),'---')).' </td></tr>'; 

      // Configura o destinatário da tramitação como destinatário da mensagem
      $RS1 = db_getPersonData::getInstanceOf($dbms,$w_cliente,nvl(f($RS,'sq_pessoa_destinatario'),0),null,null);
      $w_destinatarios = f($RS1,'email').'|'.f($RS1,'nome').'; ';
    } 
    $w_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>OUTRAS INFOMAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $RS = db_getCustomerSite::getInstanceOf($dbms,$w_cliente);
    $w_html.='      <tr valign="top"><td colspan="2">'.$crlf;
    $w_html.='         Para acessar o sistema use o endereço: <b><a class="SS" href="'.f($RS,'logradouro').'" target="_blank">'.f($RS,'Logradouro').'</a></b></li>'.$crlf;
    $w_html.='      </td></tr>'.$crlf;
    $w_html.='      <tr valign="top"><td colspan="2">'.$crlf;
    $w_html.='         Dados da ocorrência:<br>'.$crlf;
    $w_html.='         <ul>'.$crlf;
    $w_html.='         <li>Responsável: <b>'.$_SESSION['NOME'].'</b></li>'.$crlf;
    $w_html.='         <li>Data do servidor: <b>'.FormataDataEdicao(time(),3).'</b></li>'.$crlf;
    $w_html.='         <li>IP de origem: <b>'.$_SERVER['REMOTE_ADDR'].'</b></li>'.$crlf;
    $w_html.='         </ul>'.$crlf;
    $w_html.='      </td></tr>'.$crlf;
    $w_html.='    </table>'.$crlf;
    $w_html.='</td></tr>'.$crlf;
    $w_html.='</table>'.$crlf;
    $w_html.='</BODY>'.$crlf;
    $w_html.='</HTML>'.$crlf;
    if(f($RSM,'st_sol')=='S') { 
      // Recupera o e-mail do responsável
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,f($RSM,'solicitante'),null,null);
      $w_destinatarios = f($RS,'email').'|'.f($RS,'nome').'; ';
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
    // Recuperar o e-mail do titular e substituto das áreas envolvidas
    $RS = db_getSolicAreas::getInstanceOf($dbms,$p_solic,null,'LISTA');
    foreach($RS as $row) {
      $RS1 = db_getUorgResp::getInstanceOf($dbms,f($row,'sq_unidade'));
      foreach($RS1 as $row1){$RS1=$row1; break;}
      if(f($RS1,'st_titular')=='S')    $w_destinatarios .= f($RS1,'email_titular').'|'.f($RS1,'nm_titular').'; ';
      if(f($RS1,'st_substituto')=='S') $w_destinatarios .= f($RS1,'email_substituto').'|'.f($RS1,'nm_substituto').'; ';    
    }  
    // Prepara os dados necessários ao envio
    if ($p_tipo==1 || $p_tipo==3) {
      // Inclusão ou Conclusão
      if ($p_tipo==1) $w_assunto = 'Inclusão - '.$w_nome; else $w_assunto='Conclusão - '.$w_nome;
    } elseif ($p_tipo==2) {
      // Tramitação
      $w_assunto='Tramitação - '.$w_nome;   
    }   
    if ($w_destinatarios>'') {
      // Executa o envio do e-mail
      $w_resultado=EnviaMail($w_assunto,$w_html,$w_destinatarios,null);
    } 
    // Se ocorreu algum erro, avisa da impossibilidade de envio
    if ($w_resultado>'') {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'ATENÇÃO: não foi possível proceder o envio do e-mail."\"n'.$w_resultado.'\');');
      ScriptClose();
    } 
  }
}
// =========================================================================
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  $w_file       ='';
  $w_tamanho    ='';
  $w_tipo       ='';
  $w_nome       ='';
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen(null);
  switch ($SG) {
    case 'ORGERAL':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') { 
        // Se for operação de exclusão, verifica se é necessário excluir os arquivos físicos
        if ($O=='E') {
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
        } else {
          //Recupera 10  dos dias de prazo da tarefa, para emitir o alerta  
          $RS = db_get10PercentDays::getInstanceOf($dbms,$_REQUEST['w_inicio'],$_REQUEST['w_fim']);
          foreach($RS as $row){$RS=$row; break;}
          $w_dias = f($RS,'dias');
          if ($w_dias<1) $w_dias=1;
        } 
        //No caso de mudança da ação PPA, os regitros de outras iniciativas devem se apagadas. Caso a ação PPA seja
        //nula, deve-se apagar todas os registros e caso seja outra ação deve-se apagar aquela ação das outras iniciativas, caso exista.
        if ($_REQUEST['w_sq_orprioridade']=='') {
          dml_putProjetoOutras::getInstanceOf($dbms,'E',$_REQUEST['w_chave'],null);
        } else {
          dml_putProjetoOutras::getInstanceOf($dbms,'E',$_REQUEST['w_chave'],$_REQUEST['w_sq_prioridade']);
        } 
        dml_putProjetoGeral::getInstanceOf($dbms,$O,
            $_REQUEST['w_chave'],$_REQUEST['w_menu'],$_SESSION['LOTACAO'],$_REQUEST['w_solicitante'],$_REQUEST['w_proponente'],
            $_SESSION['SQ_PESSOA'],null,null,$_REQUEST['w_sqcc'],null,$_REQUEST['w_descricao'],$_REQUEST['w_justificativa'],$_REQUEST['w_inicio'],
            $_REQUEST['w_fim'],$_REQUEST['w_valor'],$_REQUEST['w_data_hora'],$_REQUEST['w_sq_unidade_resp'],$_REQUEST['w_titulo'],$_REQUEST['w_prioridade'],
            $_REQUEST['w_aviso'],$w_dias,$_REQUEST['w_aviso_pacote'],$_REQUEST['w_dias_pacote'],$_REQUEST['w_cidade'],$_REQUEST['w_palavra_chave'],null,null,$_REQUEST['w_sq_acao_ppa'],$_REQUEST['w_sq_orprioridade'],
            $_REQUEST['w_selecionada_mpog'],$_REQUEST['w_selecionada_relevante'],null,&$w_chave_nova,$w_copia);
        ScriptOpen('JavaScript');
        if ($O=='I') {
          // Recupera os dados para montagem correta do menu
          $RS1 = db_getMenuData::getInstanceOf($dbms,$w_menu);
          ShowHTML('  parent.menu.location=\''.montaURL_JS(null,$conRootSIW.'menu.php?par=ExibeDocs&O=A&w_chave='.$w_chave_nova.'&w_documento=Nr. '.$w_chave_nova.'&R='.$R.'&SG='.f($RS1,'sigla').'&TP='.$TP.MontaFiltro('GET')).'\';');
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
      break;  
    case 'ORINFO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') { 
        dml_putProjetoInfo::getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_descricao'],$_REQUEST['w_justificativa'],$_REQUEST['w_problema'],$_REQUEST['w_ds_acao'],$_REQUEST['w_publico_alvo'],$_REQUEST['w_estrategia'],$_REQUEST['w_indicadores'],$_REQUEST['w_objetivo']);
        ScriptOpen('JavaScript');
        if ($O=='I') {
          // Recupera os dados para montagem correta do menu
            $RS1 = db_getMenuData::getInstanceOf($dbms,$w_menu);
            ShowHTML('  parent.menu.location=\''.montaURL_JS(null,$conRootSIW.'menu.php?par=ExibeDocs&O=A&w_chave='.$w_chave_nova.'&w_documento=Nr. '.$w_chave_nova.'&R='.$R.'&SG='.f($RS1,'sigla').'&TP='.$TP.MontaFiltro('GET')).'\';');
          } elseif ($O=='E') {
            ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&R='.$R.'&SG=ORCAD&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';');
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
      break;
    case 'OROUTRAS':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') { 
        dml_putProjetoOutras::getInstanceOf($dbms,'E',$_REQUEST['w_chave'],null);
        for ($i=0; $i<=count($_POST['w_outras_iniciativas'])-1; $i=$i+1) {
          if ($_REQUEST['w_outras_iniciativas'][$i]>'') {
            dml_putProjetoOutras::getInstanceOf($dbms,'I',$_REQUEST['w_chave'],$_REQUEST['w_outras_iniciativas'][$i]);
          } 
        } 
        ScriptOpen('JavaScript');
        if ($O=='I') {
          // Recupera os dados para montagem correta do menu
          $RS1 = db_getMenuData::getInstanceOf($dbms,$w_menu);
          ShowHTML('  parent.menu.location=\''.montaURL_JS(null,$conRootSIW.'menu.php?par=ExibeDocs&O=A&w_chave='.$w_chave_nova.'&w_documento=Nr.'.$w_chave_nova.'&R='.$R.'&SG='.f($RS1,'sigla').'&TP='.$TP.$MontaFiltro('GET')).'\';');
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
      break;
    case 'ORFINANC':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {   
        dml_putProjetoFinancAcao::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_sq_acao_ppa'],$_REQUEST['w_obs_financ']);
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
      break;
    case 'ORRESP':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putRespAcao::getInstanceOf($dbms,$_REQUEST['w_chave_aux'],$_REQUEST['w_responsavel'],$_REQUEST['w_telefone'],$_REQUEST['w_email'],$_REQUEST['w_tipo']);
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
      break;
    case 'ORETAPA':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putProjetoEtapa::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_chave_pai'],
            $_REQUEST['w_titulo'],$_REQUEST['w_descricao'],$_REQUEST['w_ordem'],$_REQUEST['w_inicio'],
            $_REQUEST['w_fim'],$_REQUEST['w_perc_conclusao'],$_REQUEST['w_orcamento'],
            $_REQUEST['w_sq_pessoa'],$_REQUEST['w_sq_unidade'],$_REQUEST['w_vincula_atividade'],$w_usuario,
            $_REQUEST['w_programada'],$_REQUEST['w_cumulativa'],$_REQUEST['w_quantidade'],$_REQUEST['w_unidade_medida']);
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
      break;
    case 'ORCAD':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        // Verifica se a meta é cumulativa ou não para o calculo do percentual de conclusão
        if ($_REQUEST['w_cumulativa']=='S') {
          $i=1;
          // Faz a varredura do campos de quantidade e irá armazenar o percentual de conclusão do ultimo mês atualizazado
          while($i<13) {
            if (Nvl($_REQUEST['w_quantitativo_'.$i],0)>0) {
              if ($_REQUEST['w_quantitativo_'.$i]>=$_REQUEST['w_quantitativo_'.($i-1)]) {       
                $w_perc_conclusao=($_REQUEST['w_quantitativo_'.$i]*100)/$_REQUEST['w_quantidade'];             
              } else {
                 ScriptOpen('JavaScript');
                 ShowHTML('  alert(\'Metas cumulativas não permitem dedução de valor!\');');
                 ScriptClose(); 
                 retornaFormulario('w_quantitativo_'.$i);
              }
            } 
            $i+=1;
          } 
        } else {
          //Se não for cumulativa faz o percentual de conclusão com todos os valores do formulário
          $w_quantitativo_total = Nvl($_REQUEST['w_quantitativo_1'],0)+Nvl($_REQUEST['w_quantitativo_2'],0)+Nvl($_REQUEST['w_quantitativo_3'],0)+Nvl($_REQUEST['w_quantitativo_4'],0)+
          Nvl($_REQUEST['w_quantitativo_5'],0)+Nvl($_REQUEST['w_quantitativo_6'],0)+Nvl($_REQUEST['w_quantitativo_7'],0)+Nvl($_REQUEST['w_quantitativo_8'],0)+
          Nvl($_REQUEST['w_quantitativo_9'],0)+Nvl($_REQUEST['w_quantitativo_10'],0)+Nvl($_REQUEST['w_quantitativo_11'],0)+Nvl($_REQUEST['w_quantitativo_12'],0);
          if (Nvl($_REQUEST['w_quantidade'],0)>0) {
            $w_perc_conclusao=($w_quantitativo_total*100)/$_REQUEST['w_quantidade'];
          } 
        } 
        dml_putAtualizaEtapa::getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$w_usuario,Nvl($w_perc_conclusao,0),$_REQUEST['w_situacao_atual'],$_REQUEST['w_exequivel'],$_REQUEST['w_justificativa_inex'],$_REQUEST['w_outras_medidas']);
        $i=1;
        // Gravação da execução física e feita mês por mês
        dml_putEtapaMensal::getInstanceOf($dbms,'E',$_REQUEST['w_chave_aux'],$_REQUEST['w_quantitativo_'.$i.''],$_REQUEST['w_referencia_'.$i.'']);
        while($i<13) {
          if (Nvl($_REQUEST['w_quantitativo_'.$i.''],0)>0) {
            dml_putEtapaMensal::getInstanceOf($dbms,'I',$_REQUEST['w_chave_aux'],$_REQUEST['w_quantitativo_'.$i.''],$_REQUEST['w_referencia_'.$i.'']);
          } 
          $i+=1;
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
      break;
    case 'ORRECURSO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putProjetoRec::getInstaceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_nome'],$_REQUEST['w_tipo'],$_REQUEST['w_descricao'],$_REQUEST['w_finalidade']);
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
      break;
    case 'ETAPAREC':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        // Inicialmente, desativa a opção em todos os endereços
        dml_putSolicEtpRec::getInstanceOf($dbms,'E',$_REQUEST['w_chave_aux'],null);
        // Em seguida, ativa apenas para os endereços selecionados
        for ($i=0; $i<=count($_POST['w_recurso'])-1; $i=$i+1) {
          if ($_REQUEST['w_recurso'][$i]>'') {
            dml_putSolicEtpRec::getInstanceOf($dbms,'I',$_REQUEST['w_chave_aux'],$_REQUEST['w_recurso'][$i]);
          } 
        } 
        ScriptOpen('JavaScript');
        // Recupera a sigla do serviço pai, para fazer a chamada ao menu
        $RS = db_getLinkData::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$_REQUEST['w_sg']);
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS,'sigla')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'ORINTERESS':
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
      break;
    case 'ORAREAS':
      // Verifica se a Assinatura Eletrônica é válida 
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putProjetoAreas::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_papel']);
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
      break;
    case 'ORANEXO': 
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if (UPLOAD_ERR_OK==0) {
          $w_maximo = $_REQUEST['w_upload_maximo'];
          foreach ($_FILES as $Chv => $Field) {
            $w_tamanho = $Field['size'];            
            if ($Field['size'] > 0) {
              // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
              if ($Field['size'] > $w_maximo) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!\');');
                ScriptClose();
                retornaFormulario('w_observacao');
                exit();
              } 
              // Se já há um nome para o arquivo, mantém 
              if ($_REQUEST['w_atual']>'') {
                $RS = db_getSolicAnexo::getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],$w_cliente);
                foreach ($RS as $row) {
                  if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
                  if (!(strpos(f($row,'caminho'),'.')===false)) {
                    $w_file = substr(basename(f($row,'caminho')),0,(strpos(basename(f($row,'caminho')),'.') ? strpos(basename(f($row,'caminho')),'.')+1 : 0)-1).substr($Field['name'],(strrpos($Field['name'],'.') ? strrpos($Field['name'],'.')+1 : 0)-1,30);
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
              $w_tipo    = $Field['type'];
              $w_nome    = $Field['name'];
              if ($w_file>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
            }elseif(nvl($Field['name'],'')!=''){
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Atenção: o tamanho do arquivo deve ser maior que 0 KBytes!\');');
              ScriptClose();
              retornaFormulario('w_caminho');
              exit();
            } 
          } 
          // Se for exclusão e houver um arquivo físico, deve remover o arquivo do disco.  
          if ($O=='E' && $_REQUEST['w_atual']>'') {
            $RS = db_getSolicAnexo::getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],$w_cliente);
            foreach ($RS as $row) {
              if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
            }
          } 
          dml_putSolicArquivo::getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_nome'],$_REQUEST['w_descricao'],$w_file,$w_tamanho,$w_tipo,$w_nome);
          }
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
      break;
    case 'ORENVIO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {  
        $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],'ORGERAL');
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
     break;
   case 'ORCONC':
     // Verifica se a Assinatura Eletrônica é válida
     if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],'PJGERAL');
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
     break;
  default:
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'Bloco de dados não encontrado: '.$SG.'\');');
    ShowHTML('  history.back(1);');
    ScriptClose();
    break;
  } 
} 
// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------

function Main() {  
  extract($GLOBALS);
  switch ($par) {
    case 'INICIAL':       Inicial();           break;
    case 'GERAL':         Geral();             break;
    case 'INFOADIC':      InfoAdic();          break;
    case 'OUTRAS':        Iniciativas();       break;
    case 'FINANC':        Financiamento();     break;
    case 'RESP':          Responsaveis();      break;
    case 'ETAPA':         Etapas();            break;
    case 'RECURSO':       Recursos();          break;
    case 'ETAPARECURSO':  EtapaRecursos();     break;
    case 'INTERESS':      Interessados();      break;
    case 'AREAS':         Areas();             break;
    case 'VISUAL':        Visual();            break;
    case 'VISUALE':       VisualE();           break;
    case 'EXCLUIR':       Excluir();           break;
    case 'ENVIO':         Encaminhamento();    break;
    case 'ANEXO':         Anexos();            break;
    case 'ANOTACAO':      Anotar();            break;
    case 'CONCLUIR':      Concluir();          break;
    case 'ATUALIZAETAPA': AtualizaEtapa();     break;
    case 'GRAVA':         Grava();             break;
    default:
      Cabecalho();
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');
      BodyOpen('onLoad=this.focus();');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
      ShowHTML('<HR>');
      ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
      Rodape();
    break;
  } 
} 
?>