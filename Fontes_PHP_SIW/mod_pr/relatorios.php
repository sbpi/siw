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
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getRelProgresso.php');
include_once($w_dir_volta.'classes/sp/db_getSolicEtpRec.php');
include_once($w_dir_volta.'classes/sp/db_getEtapaDataParents.php');
include_once($w_dir_volta.'classes/sp/db_getSolicEtapa.php');
include_once($w_dir_volta.'classes/sp/db_getSolicRestricao.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
// =========================================================================
//  /relatorios.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Diversos tipos de relatórios para fazer o acompanhamento gerencial 
// Mail     : celso@sbpi.com.br
// Criacao  : 29/03/2007 14:00
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
$w_troca        = $_REQUEST['w_troca'];
$w_copia        = $_REQUEST['w_copia'];
$par        = strtoupper($_REQUEST['par']);
$P1         = nvl($_REQUEST['P1'],0);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = strtoupper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = strtoupper($_REQUEST['O']);
$p_ordena   = strtoupper($_REQUEST['p_ordena']);
$w_assinatura   = strtoupper($_REQUEST['w_assinatura']);
$w_pagina       = 'relatorios.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_pr/';
if ($O=='') $O='P';
switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';    break;
  case 'A': $w_TP=$TP.' - Alteração';   break;
  case 'E': $w_TP=$TP.' - Exclusão';    break;
  case 'P': $w_TP=$TP.' - Filtragem';   break;
  case 'C': $w_TP=$TP.' - Cópia';       break;
  case 'V': $w_TP=$TP.' - Envio';       break;
  case 'H': $w_TP=$TP.' - Herança';     break;
  default:  $w_TP=$TP.' - Listagem';    break;
} 
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_ano      = RetornaAno();
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
// Relatório de progresso do projeto
// -------------------------------------------------------------------------
function Rel_Progresso() {
  extract($GLOBALS);
  $p_projeto = $_REQUEST['p_projeto'];
  $p_inicio  = $_REQUEST['p_inicio'];
  $p_fim     = $_REQUEST['p_fim'];
  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'') {
      $w_logo='img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
    }
    if ($w_tipo=='WORD') {
      HeaderWord(null);
      CabecalhoWord($w_cliente,$w_TP,0);
    } else {
      Cabecalho();
    } 
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Relatorio de progresso do projeto</TITLE>');
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpenClean('onLoad=\'this.focus()\'; ');
    ShowHTML('<div align="center">');
    ShowHTML('<table width="95%" border="0" cellspacing="3">');
    ShowHTML('<tr><td colspan="2">');
    ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" SRC="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
    ShowHTML('RELATÓRIO DE PROGRESSO DO PROJETO');
    ShowHTML('</FONT><TR><TD ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
    if ($w_tipo!='WORD') {
      ShowHTML('&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      //ShowHTML('&nbsp;&nbsp;<a target="ProgWord" href="'.$w_dir.$w_pagina.'Rel_Progresso&R='.$w_pagina.$par.'&O=L&w_tipo=word&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4=1&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
    } 
    ShowHTML('</TD></TR>');
    ShowHTML('</FONT></B></TD></TR></TABLE>');
    $w_projeto_atual = 0;
    $RS = db_getRelProgresso::getInstanceOf($dbms,$w_cliente,$p_projeto,$p_inicio,$p_fim,'RELATORIO');
    $RS = SortArray($RS,'sq_projeto','asc'); 
    if (count($RS)==0) {
        
    } else {
      foreach ($RS as $row) {
        if($w_projeto_atual==0 || $w_projeto_atual<>f($row,'sq_projeto')) {
          ShowHTML('   <tr><td colspan="2"><br><hr NOSHADE color=#000000 size=4></td></tr>');
          ShowHTML('   <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>Título do projeto: '.f($row,'nm_projeto').'</b></div></td></tr>');
          ShowHTML('   <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><b>Execução prevista: '.FormataDataEdicao(f($row,'inicio_projeto')).' a '.FormataDataEdicao(f($row,'fim_projeto')).'</b></div></td></tr>');
          ShowHTML('   <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><b>Período de reporte: '.$p_inicio.' a '.$p_fim.'</b></div></td></tr>');
          // IDE
          ShowHTML('      <tr><td colspan="2"><br><font size="2"><b>Indicadores<hr NOSHADE color=#000000 SIZE=1></b></td></tr>');
          ShowHTML('   <tr><td width="30%"><b>'.VisualIndicador($w_dir_volta,$w_cliente,'IGC',$TP,'IGC').' em '.date("d/m/Y").':</b></td>');
          ShowHTML('       <td><div align="justify"><b>'.formatNumber(f($row,'igc')).'%</b></div></td></tr>');
          ShowHTML('   <tr><td><b>'.VisualIndicador($w_dir_volta,$w_cliente,'IDE',$TP,'IDE').' em '.FormataDataEdicao($p_fim).':</b></td>');
          ShowHTML('       <td><div align="justify"><b>'.formatNumber(f($row,'ide')).'%</b></div></td></tr>');
          //Progresso no periodo
          ShowHTML('      <tr><td colspan="2"><br><font size="2"><b>Progresso no período<hr NOSHADE color=#000000 SIZE=1></b></td></tr>');
          ShowHTML('      <tr><td align="center" colspan="2">');
          ShowHTML('          <table width=100%  border="1" bordercolor="#00000">');
          $RS1 = db_getRelProgresso::getInstanceOf($dbms,$w_cliente,f($row,'sq_projeto'),$p_inicio,$p_fim,'PROPREV');
          $RS1 = SortArray($RS1,'sq_projeto_etapa','asc');
          if(count($RS1)>0) {
            // Lista os registros selecionados para listagem            
            ShowHTML('          <tr><td bgColor="#f0f0f0" height="30" colspan="10"><div align="justify"><font size="2"><b>ENTREGAS PREVISTAS</b></font></div></td>');
            ShowHTML('          <tr>');
            ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Etapa</b></div></td>');
            ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Título</b></div></td>');
            ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Responsável</b></div></td>');
            ShowHTML('            <td colspan=2 bgColor="#f0f0f0"><div align="center"><b>Execução prevista</b></div></td>');
            ShowHTML('            <td colspan=2 bgColor="#f0f0f0"><div align="center"><b>Execução real</b></div></td>');
            ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Percentual de conclusão</b></div></td>');
            ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Situação</b></div></td>');
            ShowHTML('          </tr>');
            ShowHTML('          <tr>');
            ShowHTML('            <td bgColor="#f0f0f0"><div align="center"><b>De</b></div></td>');
            ShowHTML('            <td bgColor="#f0f0f0"><div align="center"><b>Até</b></div></td>');
            ShowHTML('            <td bgColor="#f0f0f0"><div align="center"><b>De</b></div></td>');
            ShowHTML('            <td bgColor="#f0f0f0"><div align="center"><b>Até</b></div></td>');
            ShowHTML('          </tr>');
            $w_sq_projeto_etapa = 0;
            foreach($RS1 as $row1) {
              if($w_sq_projeto_etapa==0 || $w_sq_projeto_etapa!=f($row1,'sq_projeto_etapa')) {
                ShowHTML('        <tr valign="top"><td nowrap>');
                if (f($row1,'fim_previsto') < addDays(time(),-1)&& f($row1,'perc_conclusao') < 100)  ShowHTML('           <img src="'.$conImgAtraso.'" border=0 width=15 height=15 align="center">');
                elseif (f($row1,'perc_conclusao') < 100)                ShowHTML('           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">');
                else                                  ShowHTML('           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">');
                ShowHTML(' '.ExibeEtapa('V',f($row1,'sq_projeto'),f($row1,'sq_projeto_etapa'),'Volta',10,MontaOrdemEtapa(f($row1,'sq_projeto_etapa')),$TP,$SG).'</td>');
                ShowHTML('        <td><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">'.str_repeat('<td width="3%"></td>',(null)).'<td>'.$l_destaque.exibeImagemRestricao(null).' '.f($row1,'nm_etapa').'</b></tr></table>');
                ShowHTML('        <td>'.ExibePessoa(null,$w_cliente,f($row1,'sq_pessoa'),$TP,f($row1,'nm_resp_etapa')).'</b>');
                ShowHTML('        <td align="center" nowrap>'.nvl(formataDataEdicao(f($row1,'inicio_previsto')),'---').'</td>');
                ShowHTML('        <td align="center" nowrap>'.nvl(formataDataEdicao(f($row1,'fim_previsto')),'---').'</td>');
                ShowHTML('        <td align="center" nowrap>'.nvl(formataDataEdicao(f($row1,'inicio_real_etapa')),'---').'</td>');
                ShowHTML('        <td align="center" nowrap>'.nvl(formataDataEdicao(f($row1,'fim_real_etapa')),'---').'</td>');
                ShowHTML('        <td nowrap align="right" >'.f($row1,'perc_conclusao').' %</td>');
                ShowHTML('        <td>'.nvl(CRLF2BR(f($row1,'situacao_atual')),'---').' </td>');
              }
              if(nvl(f($row1,'sq_tarefa'),'')>'') {
                ShowHTML('<tr valign="top">');
                ShowHTML('  <td>');
                ShowHTML('  <td nowrap>');
                if (f($row1,'concluida') == 'N') {
                  if (f($row1,'fim') < addDays(time(),-1))                                ShowHTML('   <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">');
                  elseif (f($row1,'aviso_prox_conc')=='S' && (f($row1,'aviso') <= addDays(time(),-1))) ShowHTML('   <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">');
                  else                                                                   ShowHTML('   <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">');
                } else {
                  if (f($row1,'sg_tramite')=='CA') ShowHTML('           <img src="'.$conImgCancel.'" border=0 width=15 height=15 align="center">');
                  elseif (f($row1,'fim') < Nvl(f($row1,'fim_real'),f($row1,'fim'))) ShowHTML('   <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">');
                  else                                                       ShowHTML('   <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">');
                } 
                ShowHTML('  <A class="HL" HREF="projetoativ.php?par=Visual&R=projetoativ.php?par=Visual&O=L&w_chave='.f($row1,'sq_tarefa').'&w_tipo=&P1='.$P1.'&P2='.f($row1,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="blank">'.f($row1,'sq_tarefa').'</a>');
                if (strlen(Nvl(f($row1,'nm_tarefa'),'-'))>50 && strtoupper($l_assunto)!='COMPLETO') ShowHTML(' - '.substr(Nvl(f($row1,'nm_tarefa'),'-'),0,50).'...');
                else                                                                                ShowHTML(' - '.Nvl(f($row1,'nm_tarefa'),'-'));
                ShowHTML('     <td>'.ExibePessoa(null,$w_cliente,f($row1,'solicitante'),$TP,f($row1,'nm_resp_tarefa')).'</td>');
                ShowHTML('     <td align="center">'.Nvl(FormataDataEdicao(f($row1,'inicio')),'-').'</td>');
                ShowHTML('     <td align="center">'.Nvl(FormataDataEdicao(f($row1,'fim')),'-').'</td>');
                ShowHTML('     <td align="center">'.Nvl(FormataDataEdicao(f($row1,'inicio_real')),'---').'</td>');
                ShowHTML('     <td align="center">'.Nvl(FormataDataEdicao(f($row1,'fim_real')),'---').'</td>');
                ShowHTML('     <td colspan=2 nowrap>'.f($row1,'nm_tramite').'</td>');
              } 
              $w_sq_projeto_etapa = f($row1,'sq_projeto_etapa');
            }
          }             
          $RS2 = db_getRelProgresso::getInstanceOf($dbms,$w_cliente,f($row,'sq_projeto'),$p_inicio,$p_fim,'PROREPORT');
          $RS2 = SortArray($RS2,'sq_projeto_etapa','asc');
          if(count($RS2)>0) {
            ShowHTML('          <tr><td bgColor="#f0f0f0" height="30" colspan="10"><div align="justify"><font size="2"><b>ENTREGAS REPORTADAS</b></font></div></td>');
            // Lista os registros selecionados para listagem
            ShowHTML('          <tr>');
            ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Etapa</b></div></td>');
            ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Título</b></div></td>');
            ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Responsável</b></div></td>');
            ShowHTML('            <td colspan=2 bgColor="#f0f0f0"><div align="center"><b>Execução prevista</b></div></td>');
            ShowHTML('            <td colspan=2 bgColor="#f0f0f0"><div align="center"><b>Execução real</b></div></td>');
            ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Percentual de conclusão</b></div></td>');
            ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Situação</b></div></td>');
            ShowHTML('          </tr>');
            ShowHTML('          <tr>');
            ShowHTML('            <td bgColor="#f0f0f0"><div align="center"><b>De</b></div></td>');
            ShowHTML('            <td bgColor="#f0f0f0"><div align="center"><b>Até</b></div></td>');
            ShowHTML('            <td bgColor="#f0f0f0"><div align="center"><b>De</b></div></td>');
            ShowHTML('            <td bgColor="#f0f0f0"><div align="center"><b>Até</b></div></td>');
            ShowHTML('          </tr>');
            $w_sq_projeto_etapa = 0;
            foreach($RS2 as $row2) {
              if($w_sq_projeto_etapa==0 || $w_sq_projeto_etapa!=f($row2,'sq_projeto_etapa')) {
                ShowHTML('        <tr valign="top"><td nowrap>');
                if (f($row2,'fim_previsto') < addDays(time(),-1)&& f($row2,'perc_conclusao') < 100)  ShowHTML('           <img src="'.$conImgAtraso.'" border=0 width=15 height=15 align="center">');
                elseif (f($row2,'perc_conclusao') < 100)                ShowHTML('           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">');
                else                                  ShowHTML('           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">');
                ShowHTML(' '.ExibeEtapa('V',f($row2,'sq_projeto'),f($row2,'sq_projeto_etapa'),'Volta',10,MontaOrdemEtapa(f($row2,'sq_projeto_etapa')),$TP,$SG).'</td>');
                ShowHTML('        <td><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">'.str_repeat('<td width="3%"></td>',(null)).'<td>'.$l_destaque.exibeImagemRestricao(null).' '.f($row2,'nm_etapa').'</b></tr></table>');
                ShowHTML('        <td>'.ExibePessoa(null,$w_cliente,f($row2,'sq_pessoa'),$TP,f($row2,'nm_resp_etapa')).'</b>');
                ShowHTML('        <td align="center" nowrap>'.nvl(formataDataEdicao(f($row2,'inicio_previsto')),'---').'</td>');
                ShowHTML('        <td align="center" nowrap>'.nvl(formataDataEdicao(f($row2,'fim_previsto')),'---').'</td>');
                ShowHTML('        <td align="center" nowrap>'.nvl(formataDataEdicao(f($row2,'inicio_real_etapa')),'---').'</td>');
                ShowHTML('        <td align="center" nowrap>'.nvl(formataDataEdicao(f($row2,'fim_real_etapa')),'---').'</td>');
                ShowHTML('        <td nowrap align="right" >'.f($row2,'perc_conclusao').' %</td>');
                ShowHTML('        <td>'.nvl(CRLF2BR(f($row2,'situacao_atual')),'---').' </td>');
              }
              if(nvl(f($row2,'sq_tarefa'),'')>'') {
                ShowHTML('<tr valign="top">');
                ShowHTML('  <td>');
                ShowHTML('  <td nowrap>');
                if (f($row2,'concluida') == 'N') {
                  if (f($row2,'fim') < addDays(time(),-1))                                ShowHTML('   <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">');
                  elseif (f($row2,'aviso_prox_conc')=='S' && (f($row2,'aviso') <= addDays(time(),-1))) ShowHTML('   <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">');
                  else                                                                   ShowHTML('   <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">');
                } else {
                  if (f($row2,'sg_tramite')=='CA') ShowHTML('           <img src="'.$conImgCancel.'" border=0 width=15 height=15 align="center">');
                  elseif (f($row2,'fim') < Nvl(f($row2,'fim_real'),f($row2,'fim'))) ShowHTML('   <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">');
                  else                                                       ShowHTML('   <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">');
                } 
                ShowHTML('  <A class="HL" HREF="projetoativ.php?par=Visual&R=projetoativ.php?par=Visual&O=L&w_chave='.f($row2,'sq_tarefa').'&w_tipo=&P1='.$P1.'&P2='.f($row2,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="blank">'.f($row2,'sq_tarefa').'</a>');
                if (strlen(Nvl(f($row2,'nm_tarefa'),'-'))>50 && strtoupper($l_assunto)!='COMPLETO') ShowHTML(' - '.substr(Nvl(f($row2,'nm_tarefa'),'-'),0,50).'...');
                else                                                                                ShowHTML(' - '.Nvl(f($row2,'nm_tarefa'),'-'));
                ShowHTML('     <td>'.ExibePessoa(null,$w_cliente,f($row2,'solicitante'),$TP,f($row2,'nm_resp_tarefa')).'</td>');
                ShowHTML('     <td align="center">'.Nvl(FormataDataEdicao(f($row2,'inicio')),'-').'</td>');
                ShowHTML('     <td align="center">'.Nvl(FormataDataEdicao(f($row2,'fim')),'-').'</td>');
                ShowHTML('     <td align="center">'.Nvl(FormataDataEdicao(f($row2,'inicio_real')),'---').'</td>');
                ShowHTML('     <td align="center">'.Nvl(FormataDataEdicao(f($row2,'fim_real')),'---').'</td>');
                ShowHTML('     <td colspan=2 nowrap>'.f($row2,'nm_tramite').'</td>');
              } 
              $w_sq_projeto_etapa = f($row2,'sq_projeto_etapa');
            }
          }
          $RS3 = db_getRelProgresso::getInstanceOf($dbms,$w_cliente,f($row,'sq_projeto'),$p_inicio,$p_fim,'PROENTR');
          $RS3 = SortArray($RS3,'sq_projeto_etapa','asc');
          if(count($RS3)>0) {
            ShowHTML('          <tr><td height="30" bgColor="#f0f0f0" colspan="10"><div align="justify"><font size="2"><b>ENTREGAS PARA O PRÓXIMO PERÍODO</b></font></div></td>');
            // Lista os registros selecionados para listagem
            ShowHTML('          <tr>');
            ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Etapa</b></div></td>');
            ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Título</b></div></td>');
            ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Responsável</b></div></td>');
            ShowHTML('            <td colspan=2 bgColor="#f0f0f0"><div align="center"><b>Execução prevista</b></div></td>');
            ShowHTML('            <td colspan=2 bgColor="#f0f0f0"><div align="center"><b>Execução real</b></div></td>');
            ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Percentual de conclusão</b></div></td>');
            ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Situação</b></div></td>');
            ShowHTML('          </tr>');
            ShowHTML('          <tr>');
            ShowHTML('            <td bgColor="#f0f0f0"><div align="center"><b>De</b></div></td>');
            ShowHTML('            <td bgColor="#f0f0f0"><div align="center"><b>Até</b></div></td>');
            ShowHTML('            <td bgColor="#f0f0f0"><div align="center"><b>De</b></div></td>');
            ShowHTML('            <td bgColor="#f0f0f0"><div align="center"><b>Até</b></div></td>');
            ShowHTML('          </tr>');
            $w_sq_projeto_etapa = 0;
            foreach($RS3 as $row3) {
              if($w_sq_projeto_etapa==0 || $w_sq_projeto_etapa!=f($row3,'sq_projeto_etapa')) {
                ShowHTML('        <tr valign="top"><td nowrap>');
                if (f($row3,'fim_previsto') < addDays(time(),-1)&& f($row3,'perc_conclusao') < 100)  ShowHTML('           <img src="'.$conImgAtraso.'" border=0 width=15 height=15 align="center">');
                elseif (f($row3,'perc_conclusao') < 100)                ShowHTML('           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">');
                else                                  ShowHTML('           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">');
                ShowHTML(' '.ExibeEtapa('V',f($row3,'sq_projeto'),f($row3,'sq_projeto_etapa'),'Volta',10,MontaOrdemEtapa(f($row3,'sq_projeto_etapa')),$TP,$SG).'</td>');
                ShowHTML('        <td><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">'.str_repeat('<td width="3%"></td>',(null)).'<td>'.$l_destaque.exibeImagemRestricao(null).' '.f($row3,'nm_etapa').'</b></tr></table>');
                ShowHTML('        <td>'.ExibePessoa(null,$w_cliente,f($row3,'sq_pessoa'),$TP,f($row3,'nm_resp_etapa')).'</b>');
                ShowHTML('        <td align="center" nowrap>'.nvl(formataDataEdicao(f($row3,'inicio_previsto')),'---').'</td>');
                ShowHTML('        <td align="center" nowrap>'.nvl(formataDataEdicao(f($row3,'fim_previsto')),'---').'</td>');
                ShowHTML('        <td align="center" nowrap>'.nvl(formataDataEdicao(f($row3,'inicio_real_etapa')),'---').'</td>');
                ShowHTML('        <td align="center" nowrap>'.nvl(formataDataEdicao(f($row3,'fim_real_etapa')),'---').'</td>');
                ShowHTML('        <td nowrap align="right" >'.f($row3,'perc_conclusao').' %</td>');
                ShowHTML('        <td>'.nvl(CRLF2BR(f($row3,'situacao_atual')),'---').' </td>');
              }
              if(nvl(f($row3,'sq_tarefa'),'')>'') {
                ShowHTML('<tr valign="top">');
                ShowHTML('  <td>');
                ShowHTML('  <td nowrap>');
                if (f($row3,'concluida') == 'N') {
                  if (f($row3,'fim') < addDays(time(),-1))                                ShowHTML('   <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">');
                  elseif (f($row3,'aviso_prox_conc')=='S' && (f($row3,'aviso') <= addDays(time(),-1))) ShowHTML('   <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">');
                  else                                                                   ShowHTML('   <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">');
                } else {
                  if (f($row3,'sg_tramite')=='CA') ShowHTML('           <img src="'.$conImgCancel.'" border=0 width=15 height=15 align="center">');
                  elseif (f($row3,'fim') < Nvl(f($row3,'fim_real'),f($row3,'fim'))) ShowHTML('   <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">');
                  else                                                       ShowHTML('   <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">');
                } 
                ShowHTML('  <A class="HL" HREF="projetoativ.php?par=Visual&R=projetoativ.php?par=Visual&O=L&w_chave='.f($row3,'sq_tarefa').'&w_tipo=&P1='.$P1.'&P2='.f($row3,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="blank">'.f($row3,'sq_tarefa').'</a>');
                if (strlen(Nvl(f($row3,'nm_tarefa'),'-'))>50 && strtoupper($l_assunto)!='COMPLETO') ShowHTML(' - '.substr(Nvl(f($row3,'nm_tarefa'),'-'),0,50).'...');
                else                                                                                ShowHTML(' - '.Nvl(f($row3,'nm_tarefa'),'-'));
                ShowHTML('     <td>'.ExibePessoa(null,$w_cliente,f($row3,'solicitante'),$TP,f($row3,'nm_resp_tarefa')).'</td>');
                ShowHTML('     <td align="center">'.Nvl(FormataDataEdicao(f($row3,'inicio')),'-').'</td>');
                ShowHTML('     <td align="center">'.Nvl(FormataDataEdicao(f($row3,'fim')),'-').'</td>');
                ShowHTML('     <td align="center">'.Nvl(FormataDataEdicao(f($row3,'inicio_real')),'---').'</td>');
                ShowHTML('     <td align="center">'.Nvl(FormataDataEdicao(f($row3,'fim_real')),'---').'</td>');
                ShowHTML('     <td colspan=2 nowrap>'.f($row3,'nm_tramite').'</td>');
              } 
              $w_sq_projeto_etapa = f($row3,'sq_projeto_etapa');
            }
          }
          ShowHTML('        </table></td></tr>');
          $RS1 = db_getSolicRestricao::getInstanceOf($dbms,f($row,'sq_projeto'), null, null, null,null,null,null);
          if (count($RS1)>0) {
            ShowHTML('      <tr><td colspan="2"><br><font size="2"><b>Questões<hr NOSHADE color=#000000 SIZE=1></b></td></tr>');
            ShowHTML('  <tr><td  colspan="2"><table width="100%" border="1">');
            ShowHTML('  <tr><td><b>'.count($RS1).' risco(s)/problema(s) associado(s)</b>');
            ShowHTML('  <tr><td align="center"><table width=100%  border="1" bordercolor="#00000">');
            ShowHTML('    <tr bgColor="#f0f0f0" align="center" valign="top">');
            ShowHTML('      <td><b>Tipo</b></td>');
            ShowHTML('      <td><b>Classificação</b></td>');
            ShowHTML('      <td><b>Descrição</b></td>');
            ShowHTML('      <td><b>Responsável</b></td>');                   
            ShowHTML('      <td><b>Estratégia</b></td>');
            ShowHTML('      <td><b>Ação de Resposta</b></td>');
            ShowHTML('      <td><b>Fase atual</b></td>');
            ShowHTML('    </tr>');
            $w_cor=$conTrBgColor;
            foreach($RS1 as $row1) {
              ShowHtml(QuestoesLinhaAtiv(f($row,'sq_projeto'), f($row1,'chave'),f($row1,'chave_aux'),f($row1,'risco'),f($row1,'fase_atual'),f($row1,'criticidade'),f($row1,'nm_tipo_restricao'),f($row1,'descricao'),f($row1,'sq_pessoa'),f($row1,'nm_resp'),f($row1,'nm_estrategia'),f($row1,'acao_resposta'),f($row1,'nm_fase_atual'),f($row1,'qt_ativ'),f($row1,'nm_tipo')));
            } 
            ShowHTML('  </table>');
            ShowHTML('</table>');
          }
        }
        $w_projeto_atual = f($row,'sq_projeto');
      }
      ShowHTML('   <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
    }
    if ($w_tipo!='WORD') Rodape();
  } elseif ($O=='P') {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Relatório de progresso do projeto</TITLE>');
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    ValidateOpen('Validacao');
    Validate('p_projeto','Projeto','SELECT','','1','18','1','1');
    Validate('p_inicio','Data inicial do período de reporte','DATA',1,10,10,'','0123456789/');
    Validate('p_fim','Data final do período de reporte','DATA',1,10,10,'','0123456789/');
    CompData('p_inicio','Data inicial do período de reporte','<=','p_fim','Data final do período de reporte');
    ValidateClose();
    ScriptClose();
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=\'document.Form.p_projeto.focus()\';');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center>');
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Relatorio',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr valign="top">');
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PJCAD');
    SelecaoProjeto('Pr<u>o</u>jeto:','O','Selecione o projeto na relação.',$p_projeto,$w_usuario,f($RS,'sq_menu'),'p_projeto','PJLIST',null);
    ShowHTML('          </table>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><u>P</u>eríodo de reporte:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="p_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_inicio.'" onKeyDown="FormataData(this,event);">'.ExibeCalendario('Form','w_inicio').' a ');
    ShowHTML('                                                 <input '.$w_Disabled.' accesskey="P" type="text" name="p_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim.'" onKeyDown="FormataData(this,event);">'.ExibeCalendario('Form','w_fim').'</td>');
    ShowHTML('          </table>');
    ShowHTML('    <table width="90%" border="0">');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir">');
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
  if ($w_tipo!='WORD') Rodape();
} 
// =========================================================================
// Gera uma linha de apresentação da tabela de etapas
// -------------------------------------------------------------------------
function EtapaLinhaAtiv($l_chave,$l_chave_aux,$l_titulo,$l_resp,$l_sq_resp,$l_inicio,$l_fim,$l_inicio_real,$l_fim_real,$l_perc,$l_situacao) {
  extract($GLOBALS);
  global $w_cor;
  $l_row     = 1;
  $l_col     = 1;

  // Recupera as atividades que o usuário pode ver
  $l_rs = db_getLinkData::getInstanceOf($dbms, $w_cliente,'GDPCAD');
  $RS_Ativ = db_getSolicList::getInstanceOf($dbms,f($l_rs,'sq_menu'),$w_usuario,'GDPCAD',5,
              null,null,null,null,null,null,
              null,null,null,null,
              null,null,null,null,null,null,null,
              null,null,null,null,null,null,$l_chave_aux,null,null);
  $l_row += count($RS_Ativ);
  $l_html .= chr(13).'        <tr valign="top"><td nowrap rowspan='.$l_row.'>';
  if ($l_fim < addDays(time(),-1)&& $l_perc < 100)  $l_html .= chr(13).'           <img src="'.$conImgAtraso.'" border=0 width=15 height=15 align="center">';
  elseif ($l_perc < 100)                $l_html .= chr(13).'           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">';
  else                                  $l_html .= chr(13).'           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">';
  $l_html .= chr(13).' '.ExibeEtapa('V',$l_chave,$l_chave_aux,'Volta',10,MontaOrdemEtapa($l_chave_aux),$TP,$SG).'</td>';
  $l_html .= chr(13).'        <td><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">'.str_repeat('<td width="3%"></td>',($l_nivel)).'<td>'.$l_destaque.exibeImagemRestricao($l_restricao).' '.$l_titulo.'</b></tr></table>';
  $l_html .= chr(13).'        <td>'.ExibePessoa(null,$w_cliente,$l_sq_resp,$TP,$l_resp).'</b>';
  $l_html .= chr(13).'        <td align="center" nowrap>'.nvl(formataDataEdicao($l_inicio),'---').'</td>';
  $l_html .= chr(13).'        <td align="center" nowrap>'.nvl(formataDataEdicao($l_fim),'---').'</td>';
  $l_html .= chr(13).'        <td align="center" nowrap>'.nvl(formataDataEdicao($l_inicio_real),'---').'</td>';
  $l_html .= chr(13).'        <td align="center" nowrap>'.nvl(formataDataEdicao($l_fim_real),'---').'</td>';
  $l_html .= chr(13).'        <td nowrap align="right" >'.$l_perc.' %</td>';
  $l_html .= chr(13).'        <td nowrap>'.nvl($l_situacao,'---').' </td>';
  //Listagem das tarefas da etapa  
  if (count($RS_Ativ)>0) {
    foreach ($RS_Ativ as $row) {
      $l_ativ .= chr(13).'<tr valign="top">';
      $l_ativ .= chr(13).'  <td>';
      if (f($row,'concluida') == 'N') {
        if (f($row,'fim') < addDays(time(),-1))                                $l_ativ .= chr(13).'   <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">';
        elseif (f($row,'aviso_prox_conc')=='S' && (f($row,'aviso') <= addDays(time(),-1))) $l_ativ .= chr(13).'   <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">';
        else                                                                   $l_ativ .= chr(13).'   <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">';
      } else {
        if (f($row,'sg_tramite')=='CA') {
          $l_ativ .= chr(13).'           <img src="'.$conImgCancel.'" border=0 width=15 height=15 align="center">';
        } elseif (f($row,'fim') < Nvl(f($row,'fim_real'),f($row,'fim'))) $l_ativ .= chr(13).'   <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">';
        else                                                       $l_ativ .= chr(13).'   <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">';
      } 
      $l_ativ .= chr(13).'  <A class="HL" HREF="projetoativ.php?par=Visual&R=projetoativ.php?par=Visual&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=&P1='.$P1.'&P2='.f($row,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="blank">'.f($row,'sq_siw_solicitacao').'</a>';
      if (strlen(Nvl(f($row,'assunto'),'-'))>50 && strtoupper($l_assunto)!='COMPLETO') $l_ativ .= ' - '.substr(Nvl(f($row,'assunto'),'-'),0,50).'...';
      else                                                                             $l_ativ .= ' - '.Nvl(f($row,'assunto'),'-');
      $l_ativ .= chr(13).'     <td>'.ExibePessoa(null,$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_resp')).'</td>';
      $l_ativ .= chr(13).'     <td align="center">'.Nvl(FormataDataEdicao(f($row,'inicio')),'-').'</td>';
      $l_ativ .= chr(13).'     <td align="center">'.Nvl(FormataDataEdicao(f($row,'fim')),'-').'</td>';
      $l_ativ .= chr(13).'     <td align="center">'.Nvl(FormataDataEdicao(f($row,'inicio_real')),'---').'</td>';
      $l_ativ .= chr(13).'     <td align="center">'.Nvl(FormataDataEdicao(f($row,'fim_real')),'---').'</td>';
      if (nvl($l_valor,'')!='') {
        $l_ativ .= chr(13).'     <td colspan=6 nowrap>'.f($row,'nm_tramite').'</td>';
      } else {
        $l_ativ .= chr(13).'     <td colspan=5 nowrap>'.f($row,'nm_tramite').'</td>';
      }
    }
  } 
  if (count($RS_Ativ) > 0) {
    $l_ativ    = $l_ativ.chr(13).'            </td></tr>';
  } 
  $l_html = $l_html.chr(13).'      </tr>';
  if ($l_ativ>'')      $l_html = $l_html.chr(13).str_replace('w_cor',$w_cor,$l_ativ);
  
  return $l_html;
}
// =========================================================================
// Gera uma linha de apresentação da tabela de questões
// -------------------------------------------------------------------------
function QuestoesLinhaAtiv($l_siw_solicitacao, $l_chave, $l_chave_aux, $l_risco, $l_fase_atual,$l_criticidade, 
    $l_tipo_restricao,$l_descricao,$l_sq_resp, $l_resp,$l_estrategia,$l_acao_resposta,$l_fase_atual, $l_qtd, $l_tipo ){
  extract($GLOBALS);
  global $w_cor;
  $l_ativ    = '';
  $l_row     = 1;
  $l_col     = 1;
  // Recupera as tarefas que o usuário pode ver
  $RS_Ativ = db_getSolicRestricao::getInstanceOf($dbms,$l_chave_aux, null, null, null, null, null, 'TAREFA');
  if(count($RS_Ativ)>0) $l_row += count($RS_Ativ)+1;

  $l_html .= chr(13).'      <tr valign="top">';
  $l_html .= chr(13).'        <td width="10%" nowrap rowspan='.$l_row.'>';
  if ($l_risco=='S') {
    if ($l_fase_atual<>'C') {
      if ($l_criticidade==1)       $l_html .= chr(13).'          <img title="Risco de baixa criticidade" src="'.$conRootSIW.$conImgRiskLow.'" border=0 align="middle">&nbsp';
        elseif ($l_criticidade==2) $l_html .= chr(13).'          <img title="Risco de média criticidade" src="'.$conRootSIW.$conImgRiskMed.'" border=0 align="middle">&nbsp';
        else                       $l_html .= chr(13).'          <img title="Risco de alta criticidade" src="'.$conRootSIW.$conImgRiskHig.'" border=0 align="middle">&nbsp';
      }
    } else {
      if ($l_fase_atual<>'C') {
      if ($l_criticidade==1)     $l_html .= chr(13).'          <img title="Problema de baixa criticidade" src="'.$conRootSIW.$conImgProblem.'" border=0 align="middle">&nbsp';
      elseif ($l_criticidade==2) $l_html .= chr(13).'          <img title="Problema de média criticidade" src="'.$conRootSIW.$conImgProblem.'" border=0 align="middle">&nbsp';
      else                       $l_html .= chr(13).'          <img title="Problema de alta criticidade" src="'.$conRootSIW.$conImgProblem.'" border=0 align="middle">&nbsp';
    }
  }
  $l_html .= chr(13).'    '.$l_tipo_restricao.'</td>';
  $l_html .= chr(13).'     <td>'.$l_tipo.'</td>';
  $l_html .= chr(13).'     <td>'.$l_descricao.'</td>';
  $l_html .= chr(13).'     <td>'.ExibePessoa(null,$w_cliente,$l_sq_resp,$TP,$l_resp).'</td>';
  $l_html .= chr(13).'     <td align="center">'.$l_estrategia.'</td>';  
  $l_html .= chr(13).'     <td>'.$l_acao_resposta.'</td>';
  $l_html .= chr(13).'     <td>'.$l_fase_atual.'</td>';
  $l_html .= chr(13).'   </tr>';

  //Listagem das tarefas da etapa  
  if (count($RS_Ativ)>0) {
    $l_ativ .= chr(13).'    <tr bgColor="#f0f0f0" align="center" valign="top">';
    $l_ativ .= chr(13).'      <td><b>Tarefa</b></td>';
    $l_ativ .= chr(13).'      <td><b>Descrição</b></td>';        
    $l_ativ .= chr(13).'      <td colspan="2"><b>Período</b></td>';
    $l_ativ .= chr(13).'      <td><b>Responsável</b></td>';
    $l_ativ .= chr(13).'      <td><b>Fase atual</b></td>';
    $l_ativ .= chr(13).'    </tr>';
    foreach ($RS_Ativ as $row) {
        $l_ativ .= chr(13).'      <tr><td>';
        if (f($row,'concluida')=='N'){
          if (f($row,'fim')<addDays(time(),-1))
            $l_ativ .= chr(13).'   <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">';
          elseif (f($row,'aviso_prox_conc')=='S' && (f($row,'aviso')<=addDays(time(),-1)))
            $l_ativ .= chr(13).'   <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">';
          else
            $l_ativ .= chr(13).'   <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">';
        } else {
          if (f($row,'sg_tramite')=='CA') {
            $l_ativ .= chr(13).'           <img src="'.$conImgCancel.'" border=0 width=15 height=15 align="center">'; 
          } elseif (f($row,'fim')<Nvl(f($row,'fim_real'),f($row,'fim')))
            $l_ativ .= chr(13).'   <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">';
          else
            $l_ativ .= chr(13).'   <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">';
        } 
        $l_ativ .= chr(13).'  <A class="HL" HREF="projetoativ.php?par=Visual&R=ProjetoAtiv.php?par=Visual&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=&P1='.$P1.'&P2='.f($row,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="blank">'.f($row,'sq_siw_solicitacao').'</a>';
        $l_ativ .= chr(13).'     <td>'.Nvl(f($row,'assunto'),'-');
        $l_ativ .= chr(13).'     <td align="center">'.formataDataEdicao(nvl(f($row,'inicio_real'),f($row,'inicio'))).'</td>';
        $l_ativ .= chr(13).'     <td align="center">'.formataDataEdicao(nvl(f($row,'fim_real'),f($row,'fim'))).'</td>';
        $l_ativ .= chr(13).'     <td>'.ExibePessoa(null,$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_resp_tarefa')).'</td>';
        $l_ativ .= chr(13).'     <td>'.f($row,'nm_tramite').'</td>';
      } 
     $l_ativ .= chr(13).'      </td></tr>';
  } 
  if ($l_qt_ativ > '') {
    $l_ativ    = $l_ativ.chr(13).'            </td></tr>';
  } 
  $l_html = $l_html.chr(13).'      </tr>';
  if ($l_ativ>'')      $l_html = $l_html.chr(13).str_replace('w_cor',$w_cor,$l_ativ);
  return $l_html;
} 
// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'REL_PROGRESSO': Rel_Progresso(); break;
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