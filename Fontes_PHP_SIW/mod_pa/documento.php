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
include_once($w_dir_volta.'classes/sp/dml_putSolicInter.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicArquivo.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoConc.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoFase.php');
include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');
include_once($w_dir_volta.'funcoes/selecaoNaturezaDocumento.php');
include_once($w_dir_volta.'funcoes/selecaoEspecieDocumento.php');
include_once($w_dir_volta.'funcoes/selecaoOrigem.php');
include_once($w_dir_volta.'funcoes/selecaoTipoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoAssunto.php');
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
if ($SG=='PADANEXO') {
  if ($O!='I' && $O!='E' && nvl($_REQUEST['w_chave_aux'],$_REQUEST['w_sq_pessoa'])=='') $O='L';
} elseif ($SG=='PADENVIO') {
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
    if ($par=='BUSCAPROC') $w_TP=$TP.' - Busca procedência'; else $w_TP=$TP.' - Listagem';
  break;
} 
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_ano      = RetornaAno();
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
      if ($p_numero_doc>'') {
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Nº do documento <td>[<b>'.$p_numero_doc.'</b>]';
      }
      if ($p_numero_orig>'') {
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Nº original do documento<td>[<b>'.$p_numero_orig.'</b>]';
      }
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
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PADCAD');
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
      $RS = SortArray($RS,'phpdt_fim','asc');
    }
  }
  Cabecalho();
  ShowHTML('<HEAD>');
  if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="300; URL=../'.MontaURL('MESA').'">');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de processos e documentos</TITLE>');
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
    ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Nº documento','numero_documento').'</td>');
    ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Responsável','nm_solic').'</td>');
    if ($P1!=2) ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Usuário atual','cd_exec').'</td>');
    if ($P1==1 || $P1==2) {
      // Se for cadastramento ou mesa de trabalho
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Assunto','nm_assunto').'</td>');
      ShowHTML('          <td colspan=2><b>Execução</td>');
    } else {
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Assunto','nm_assunto').'</td>');
      ShowHTML('          <td colspan=2><b>Execução</td>');
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
        ShowHTML('        <A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'numero_documento').'&nbsp;</a>');
        ShowHTML('        <td>'.ExibePessoa('../',$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_solic')).'</A></td>');
        if ($P1!=2) {
          // Se for mesa de trabalho, não exibe o executor, pois já é o usuário logado
          if (Nvl(f($row,'nm_exec'),'---')>'---') ShowHTML('        <td>'.ExibePessoa('../',$w_cliente,f($row,'executor'),$TP,f($row,'nm_exec')).'</td>');
          else                                    ShowHTML('        <td>---</td>');
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
        ShowHTML('        <td align="top" nowrap>');
        if ($P1!=3 && $P1!=5) {
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
        } else {
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
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
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
    $w_processo           = $_REQUEST['w_processo'];
    $w_data_autuacao      = $_REQUEST['w_data_autuacao'];
    $w_numero_original    = $_REQUEST['w_numero_original'];
    $w_especie_documento  = $_REQUEST['w_especie_documento'];
    $w_natureza_documento = $_REQUEST['w_natureza_documento'];
    $w_data_documento     = $_REQUEST['w_data_documento'];
    $w_interno            = $_REQUEST['w_interno'];
    $w_tipo_pessoa        = $_REQUEST['w_tipo_pessoa'];
    $w_pais               = $_REQUEST['w_pais'];
    $w_uf                 = $_REQUEST['w_uf'];
    $w_cidade             = $_REQUEST['w_cidade'];
    $w_data_recebimento   = $_REQUEST['w_data_recebimento'];
    $w_nm_assunto         = $_REQUEST['w_nm_assunto'];
    $w_assunto            = $_REQUEST['w_assunto'];
    $w_descricao          = $_REQUEST['w_descricao'];
    $w_total_interessado  = $_REQUEST['w_total_interessado'];
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
        $w_data_autuacao      = f($RS,'data_autuacao');
        $w_numero_original    = f($RS,'numero_original');
        $w_especie_documento  = f($RS,'sq_especie_documento');
        $w_natureza_documento = f($RS,'sq_natureza_documento');
        $w_data_documento     = f($RS,'data_documento');
        $w_interno            = f($RS,'interno');
        $w_tipo_pessoa        = f($RS,'tipo_pessoa');
        $w_pais               = f($RS,'sq_pais');
        $w_uf                 = f($RS,'co_uf');
        $w_cidade             = f($RS,'sq_cidade');
        $w_data_recebimento   = f($RS,'data_recebimento');
        $w_assunto            = f($RS,'sq_assunto');
        $w_descricao          = f($RS,'descricao');
        $w_total_interessado  = f($RS,'total_interessado');
        $w_pessoa_origem      = f($RS,'pessoa_origem');
        $w_sq_unidade         = f($RS,'sq_unidade');
      } 
    } 
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
  ShowHTML('function procura() {');
  ShowHTML('  if (document.Form.p_nm_pessoa_origem.value.length < 3) {');
  ShowHTML('    alert(\'Informe o nome a ser procurado com, pelo menos, três letras!\');');
  ShowHTML('    document.Form.p_nm_pessoa_origem.focus();');
  ShowHTML('    return false;');
  ShowHTML('  } else {');
  ShowHTML('    document.Form.O.value=\''.$O.'\';');
  ShowHTML('    document.Form.target=\'content\';');
  ShowHTML('    document.Form.submit();');
  ShowHTML('  }');
  ShowHTML('}');
  ShowHTML('function procuraassunto() {');
  ShowHTML('  if (document.Form.p_nm_assunto.value.length < 3) {');
  ShowHTML('    alert(\'Informe o assunto a ser procurado com, pelo menos, três letras!\');');
  ShowHTML('    document.Form.p_nm_assunto.focus();');
  ShowHTML('    return false;');
  ShowHTML('  } else {');
  ShowHTML('    document.Form.O.value=\''.$O.'\';');
  ShowHTML('    document.Form.target=\'content\';');
  ShowHTML('    document.Form.submit();');
  ShowHTML('  }');
  ShowHTML('}');  
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.Botao.value == "Troca") { return true; }');
    Validate('w_data_autuacao','Data de autuação','DATA','',10,10,'','0123456789/');
    Validate('w_numero_original','Nº do documento','1','',2,10,'1','1');
    Validate('w_especie_documento','Espécie do documento','SELECT',1,1,18,'','0123456789');
    Validate('w_natureza_documento','Natureza do documento','SELECT',1,1,18,'','0123456789');
    Validate('w_data_documento','Data do documento','DATA','',10,10,'','0123456789/');
    if($w_interno=='S') {
      Validate('w_sq_unidade','Unidade de origem','SELECT',1,1,18,'','0123456789');
    } else {
      Validate('w_pessoa_origem','Pessoa de origem','SELECT',1,1,18,'','0123456789');
    }
    Validate('w_pais','País','SELECT',1,1,18,'','0123456789');
    Validate('w_uf','Estado','SELECT',1,1,3,'1','1');
    Validate('w_cidade','Cidade','SELECT',1,1,18,'','0123456789');
    switch (f($RS_Menu,'data_hora')) {
      case 1: Validate('w_data_recebimento','Data de recebimento','DATA',1,10,10,'','0123456789/'); break;
      case 2: Validate('w_data_recebimento','Data de recebimento','DATAHORA',1,17,17,'','0123456789/'); break;
      case 3: Validate('w_inicio','Início previsto','DATA',1,10,10,'','0123456789/');
              Validate('w_fim','Término previsto','DATA',1,10,10,'','0123456789/');
              CompData('w_inicio','Início previsto','<=','w_fim','Término previsto'); break;
      case 4: Validate('w_inicio','Início previsto','DATAHORA',1,17,17,'','0123456789/,: ');
              Validate('w_fim','Término previsto','DATAHORA',1,17,17,'','0123456789/,: ');
              CompData('w_inicio','Início previsto','<=','w_fim','Término previsto'); break;
    }
    Validate('w_total_interessado','Total de interesssados','1','1',1,18,'','1');    
    Validate('w_assunto','Assunto','SELECT',1,1,18,'','0123456789');
    Validate('w_descricao','Descricao','1','',1,2000,'1','1');
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
    ShowHTML('      <tr><td>Os dados deste bloco serão utilizados para identificação do documento, bem como para o controle de sua execução.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('          <tr><td><table border=0 colspan=0 cellspan=0 width="100%">');
    ShowHTML('      <tr valign="top"><td>');
    ShowHTML('<b>Tipo:</b><br>');
    if ($w_processo=='S') {
       ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_processo" value="S" checked> Processo <input '.$w_Disabled.' type="radio" name="w_processo" value="N"> Documento');
    } else {
       ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_processo" value="S"> Processo <input '.$w_Disabled.' type="radio" name="w_processo" value="N" checked> Documento');
    }
    ShowHTML('           <td><b><u>D</u>ata de autuação:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data_autuacao" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data_autuacao.'" onKeyDown="FormataData(this,event);" title="Data de autuação do processo.">'.ExibeCalendario('Form','w_data_autuacao').'</td>');
    ShowHTML('           <td><b>Nº do documento:</b><br><INPUT '.$w_Disabled.' class="STI" type="text" name="w_numero_documento" size="10" maxlength="10" value="'.$w_numero_documento.'" ></td>');
    ShowHTML('       <tr valign="top">');
    selecaoEspecieDocumento('<u>E</u>spécie do documento:','E','Selecione a espécie do documento.',$w_especie_documento,null,'w_especie_documento',null,null);
    selecaoNaturezaDocumento('<u>N</u>tureza do documento:','N','Selecione a natureza do documento.',$w_natureza_documento,null,'w_natureza_documento',null,null);
    ShowHTML('           <td><b>D<u>a</u>ta do documento:</b><br><input '.$w_Disabled.' accesskey="A" type="text" name="w_data_documento" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data_documento.'" onKeyDown="FormataData(this,event);" title="Data original do documento.">'.ExibeCalendario('Form','w_data_documento').'</td>');
    selecaoOrigem('<u>O</u>rigem:','O','Selecione a origem do documento.',$w_interno,null,'w_interno',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_interno\'; document.Form.submit();"');
    ShowHTML('        <tr valign="top">');
    if($w_interno=='S') {
      SelecaoUnidade('<U>U</U>nidade de origem:','U','Selecione a unidade de origem do documento.',$w_sq_unidade,null,'w_sq_unidade','MOD_PA',null);
    } else {
      SelecaoTipoPessoa('<u>T</u>ipo da pessoa:','T','Selecione na lista o tipo de pessoa que será indicada como pessoa de origem do documento.',$w_tipo_pessoa,$w_cliente,'w_tipo_pessoa',null,null);
      ShowHTML('      <tr><td>');
      ShowHTML('            <b>Pr<U>o</U>curar nome:<br> <INPUT TYPE="TEXT" ACCESSKEY="O" class="STI" name="w_nm_pessoa_origem" size=40 maxlength=40>');
      ShowHTML('            <input class="STB" type="button" name="Procura" value="Procura" onClick="procura()">');
      SelecaoPessoa('<u>P</u>essoa de origem:','P','Selecione o nome da pessoa de origem do documento.',$w_pessoa_origem,null,'w_pessoa_origem',$w_nm_pessoa_origem,$w_tipo_pessoa,'TIPOPESSOA');
    }
    ShowHTML('       <tr valign="top">');
    SelecaoPais('<u>P</u>aís:','P',null,$w_pais,null,'w_pais',null,'onChange="document.Form.action=\''.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_uf\'; document.Form.submit();"');
    SelecaoEstado('E<u>s</u>tado:','S',null,$w_uf,$w_pais,null,'w_uf',null,'onChange="document.Form.action=\''.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_cidade\'; document.Form.submit();"');
    SelecaoCidade('<u>C</u>idade:','C',null,$w_cidade,$w_pais,$w_uf,'w_cidade',null,null);
    ShowHTML('       <tr valign="top">');    
    switch (f($RS_Menu,'data_hora')) {
      case 1: ShowHTML('              <td valign="top"><b><u>D</u>ata de recebimento:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data_recebimento" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data_recebimento.'" onKeyDown="FormataData(this,event);" title="Data de recebimento do documento.">'.ExibeCalendario('Form','w_fim').'</td>'); break;
      case 2: ShowHTML('              <td valign="top"><b><u>D</u>ata de recebimento:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data_recebimento" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_data_recebimento.'" onKeyDown="FormataDataHora(this,event);" title="Data/hora de recebimento do documento."></td>'); break;
      case 3: ShowHTML('              <td valign="top"><b>Iní<u>c</u>io previsto:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_inicio,FormataDataEdicao(time())).'" onKeyDown="FormataData(this,event);" title="Início previsto da solicitação.">'.ExibeCalendario('Form','w_inicio').'</td>');
              ShowHTML('              <td valign="top"><b><u>T</u>érmino previsto:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" title="Data limite para que a execução do projeto esteja concluído.">'.ExibeCalendario('Form','w_fim').'</td>'); break;
      case 4: ShowHTML('              <td valign="top"><b>Iní<u>c</u>io previsto:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_inicio.'" onKeyDown="FormataDataHora(this,event);" title="Data/hora de início previsto do projeto."></td>');
              ShowHTML('              <td valign="top"><b><u>T</u>érmino previsto:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_fim.'" onKeyDown="FormataDataHora(this,event);" title="Data/hora limite para que a execução do projeto esteja concluído."></td>'); break;
    } 
    ShowHTML('           <td><b>Total de interessados:</b><br><INPUT '.$w_Disabled.' class="STI" type="text" name="w_total_interessado" size="10" maxlength="18" value="'.$w_total_interessado.'" ></td>');
    ShowHTML('       <tr valign="top">');
    ShowHTML('           <td><b>P<U>r</U>ocurar assunto:<br> <INPUT TYPE="TEXT" ACCESSKEY="R" class="STI" name="w_nm_assunto" size=40 maxlength=40>');
    ShowHTML('            <input class="STB" type="button" name="Procura" value="Procura" onClick="procuraassunto()">');
    SelecaoAssunto('Assun<u>t</u>o:','T',null,$w_assunto,null,'w_assunto',$w_nm_assunto,'FOLHA',null);
    ShowHTML('       <tr valign="top">');
    ShowHTML('           <td colspan="3"><b>D<u>e</u>scricao:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_descricao" class="STI" ROWS=5 cols=75>'.$w_descricao.'</TEXTAREA></td>');    
//    ShowHTML('          </table>');
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
    $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'PEPRGERAL');
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
// Rotina de indicadores do programa
// -------------------------------------------------------------------------
function Indicadores() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave          = $_REQUEST['w_chave'];
  $w_chave_aux      = $_REQUEST['w_chave_aux'];

  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
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
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'PEPRGERAL');
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
    $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'PEPRGERAL');
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
    ShowHTML('&nbsp;&nbsp;<a target="MetaWord" href="'.$w_dir.$w_pagina.$par.'&O=V&w_chave='.$w_chave.'&w_chave_aux='.$w_chave_aux.'&w_tipo=WORD&P1=10&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
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
      else         ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Voltar">');
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
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
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
      ShowHTML('&nbsp;&nbsp;<a target="MetaWord" href="'.$w_dir.$w_pagina.$par.'&O=V&w_chave='.$w_chave.'&w_chave_aux='.$w_chave_aux.'&w_tipo=WORD&P1=10&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
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
  $w_sq_pessoa        = $_REQUEST['w_sq_pessoa'];
  $w_sq_tipo_interes  = $_REQUEST['w_sq_tipo_interes'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_sq_pessoa        = $_REQUEST['w_sq_pessoa'];
    $w_sq_tipo_interes  = $_REQUEST['w_sq_tipo_interes'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getSolicInter::getInstanceOf($dbms,$w_chave,null,'LISTA');
    $RS = SortArray($RS,'nome_resumido','asc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados do endereço informado
    $RS = db_getSolicInter::getInstanceOf($dbms,$w_chave,$w_sq_pessoa,'REGISTRO');
    foreach($RS as $row){$RS=$row; break;}
    $w_nome                 = f($RS,'nome_resumido');
    $w_sq_tipo_interessado  = f($RS,'sq_tipo_interessado');
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_sq_pessoa','Pessoa','SELECT','1','1','18','','1');
      Validate('w_sq_tipo_interessado','Tipo de interessado','SELECT','1','1','18','','1');
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
      $RS1 = db_getSolicData::getInstanceOf($dbms,$w_chave,'PEPRGERAL');
      foreach($RS1 as $row){$RS1=$row; break;}
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
    $RS1 = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
    foreach($RS1 as $row){$RS1=$row; break;}
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
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
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
    $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
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
  if ($P1==1 || $P1==2) ShowHTML('Ficha Resumida de Programa');
  else                  ShowHTML('Ficha de Programa');
  ShowHTML('</B></FONT></DIV></TD></TR>');
  ShowHTML('</TABLE></TD></TR>');
  if ($w_tipo>'' && $w_tipo!='WORD') ShowHTML('<tr><td colspan="2"><div align="center"><font size="1"><b>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></div></td></tr>');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML(VisualPrograma($w_chave,'L',$w_usuario,$P1,$P4,'sim','sim','sim','sim','sim','sim','sim','sim','sim','sim','sim'));
  if ($w_tipo>'' && $w_tipo!='WORD') ShowHTML('<tr><td colspan="2" ><div align="center"><font size="1"><b>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></div></td></tr>');
  Rodape();
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
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'PEPRGERAL',$R,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'PEPRGERAL');
  foreach($RS as $row){$RS=$row; break;}
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<INPUT type="hidden" name="w_cd_programa" value="'.f($RS,'cd_programa').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Excluir">');
  ShowHTML('      <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Abandonar">');
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
    $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'PEPRGERAL');
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
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'ISPRENVIO',$R,$O);
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
    // Se não for cadastramento
    // Volta para a listagem
    $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
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
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'ISPRENVIO',$R,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'PEPRGERAL');
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
  ShowHTML('      <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Abandonar">');
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
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'ISPRCONC',$R,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_concluida" value="S">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'PEPRGERAL');
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
  if ($P1!=1) {
    // Se não for cadastramento
    ShowHTML('      <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Abandonar">');
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
  $RSM = db_getSolicData::getInstanceOf($dbms,$p_solic,'PEPRGERAL');
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
  $RSM = db_getSolicData::getInstanceOf($dbms,$l_solic,'PEPRGERAL');
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
  $w_html.=$crlf.'          <td>Data de início:<br><b>'.$FormataDataEdicao[f($RSM,'inicio')].' </b></td>';
  $w_html.=$crlf.'          <td>Data de término:<br><b>'.$FormataDataEdicao[f($RSM,'fim')].' </b></td>';
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
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&R='.$R.'&SG=PEPROCAD&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';');
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
  } elseif ($SG=='PEPRRESP') {
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
      $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],'PEPRGERAL');
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
          $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.RemoveTP($TP).'&SG='.f($RS,'sigla').MontaFiltro('GET')).'\';');
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
      $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],'PEPRGERAL');
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
        // Volta para a listagem
        $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS,'sigla').MontaFiltro('GET')).'\';');
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
    case 'INDICADOR':           Indicadores();                  break;
    case 'ATUALIZAINDICADOR':   AtualizaIndicador();            break;
    case 'RESTRICAO':           Restricoes();                   break;
    case 'RESP':                Interessados();                 break;
    case 'VISUAL':              Visual();                       break;
    case 'VISUALE':             VisualE();                      break;
    case 'EXCLUIR':             Excluir();                      break;
    case 'ENVIO':               Encaminhamento();               break;
    case 'ANEXO':               Anexos();                       break;
    case 'ANOTACAO':            Anotar();                       break;
    case 'CONCLUIR':            Concluir();                     break;
    case 'BUSCAPROGRAMA':       BuscaPrograma();                break;
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