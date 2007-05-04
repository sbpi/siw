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
include_once($w_dir_volta.'classes/sp/db_getIndicador.php');
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
$w_troca    = $_REQUEST['w_troca'];
$w_copia    = $_REQUEST['w_copia'];
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
  $p_legenda   = $_REQUEST['p_legenda'];
  $p_projeto   = $_REQUEST['p_projeto'];
  $p_inicio    = $_REQUEST['p_inicio'];
  $p_fim       = $_REQUEST['p_fim'];
  $p_tipo      = $_REQUEST['p_tipo'];
  $p_indicador = $_REQUEST['p_indicador'];
  $p_prevista  = $_REQUEST['p_prevista'];
  $p_realizada = $_REQUEST['p_realizada'];
  $p_pendente  = $_REQUEST['p_pendente'];
  $p_proximo   = $_REQUEST['p_proximo'];
  $p_questoes  = $_REQUEST['p_questoes'];
  $p_tarefas   = $_REQUEST['p_tarefas'];
  $p_pacotes   = $_REQUEST['p_pacotes'];
  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'') {
      $w_logo='img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
    }
    if ($p_tipo=='WORD') {
      HeaderWord(null);
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');
      CabecalhoWord($w_cliente,'RELATÓRIO DE PROGRESSO DO PROJETO',$w_pag);
      $w_embed = 'WORD';
      //CabecalhoWord($w_cliente,$w_TP,0);
    } else {
      Cabecalho();
      $w_embed = 'EMBED';
      ShowHTML('<HEAD>');
      ShowHTML('<TITLE>Relatorio de progresso do projeto</TITLE>');
      ShowHTML('</HEAD>');
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');
      BodyOpenClean('onLoad=\'this.focus()\'; ');
      ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" SRC="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,$w_embed).'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
      ShowHTML('RELATÓRIO DE PROGRESSO DO PROJETO');
      ShowHTML('</FONT><TR><TD ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
      if ($p_tipo!='WORD') {
        ShowHTML('&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
        ShowHTML('&nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.'Rel_Progresso&R='.$w_pagina.$par.'&O=L&p_tipo=WORD&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4=1&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
      } 
      ShowHTML('</TD></TR>');
      ShowHTML('</FONT></B></TD></TR></TABLE>');
    }
    ShowHTML('<div align="center">');
    ShowHTML('<table width="95%" border="0" cellspacing="3">');
    ShowHTML('<tr><td colspan="2">');
    ShowHTML('   <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
    ShowHTML('   <tr><td colspan="2" align="center" bgcolor="#f0f0f0"><font size="2"><b>PERÍODO EM ANÁLISE: '.$p_inicio.' a '.$p_fim.'</b></td></tr>');
    ShowHTML('   <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
    $w_projeto_atual = 0;
    $RS = db_getRelProgresso::getInstanceOf($dbms,$w_cliente,$p_projeto,$p_inicio,$p_fim,'RELATORIO');
    $RS = SortArray($RS,'nm_projeto','asc'); 
    if (count($RS)==0) {
      ShowHTML('   <tr><td colspan="2"><br><hr NOSHADE color=#000000 size=4></td></tr>');
      ShowHTML('   <tr><td colspan="2" align="center" bgcolor="#f0f0f0"><font size="2"><b>Nenhum registro encontrado para os parâmetros informados</b></td></tr>');
      ShowHTML('   <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
    } else {
      // Legendas
      if($p_legenda=='S') {
        ShowHTML('      <tr><td colspan="2"><table border=0>');
        ShowHTML('        <tr valign="top"><td colspan=6><font size="2"><b>Legenda dos sinalizadores de etapas:</b>'.ExibeImagemSolic('ETAPA',null,null,null,null,null,null,null, null,true));
        ShowHTML('        <tr valign="top"><td colspan=6><br>');
        ShowHTML('        <tr valign="top"><td colspan=6><font size="2"><b>Legenda dos sinalizadores de tarefas:</b>'.ExibeImagemSolic('GD',null,null,null,null,null,null,null, null,true));
        ShowHTML('      </table>');
      }
      foreach ($RS as $row) {
        if($w_projeto_atual==0 || $w_projeto_atual<>f($row,'sq_projeto')) {
          if ($w_projeto_atual>0) {
            if ($p_tipo=='WORD') {
              ShowHTML('<br style="page-break-after:always">');
            } else {
              ShowHTML('    <tr><td colspan="2"><br style="page-break-after:always"></td></tr>');
            }
          }
          ShowHTML('   <tr><td colspan="2"><hr NOSHADE color=#000000 size=2></td></tr>');
          if (nvl(f($row,'nm_plano'),'')!='') ShowHTML('   <tr><td colspan="2" bgcolor="#f0f0f0"><div align=justify><font size="2"><b>Plano Estratégico: '.f($row,'nm_plano').'</b></font></div></td></tr>');
          if (nvl(f($row,'nm_objetivo'),'')!='') ShowHTML('   <tr><td colspan="2" bgcolor="#f0f0f0"><div align=justify><font size="2"><b>Ojetivo: '.f($row,'nm_objetivo').'</b></font></div></td></tr>');
          if (nvl(f($row,'nm_programa'),'')!='') ShowHTML('   <tr><td colspan="2" bgcolor="#f0f0f0"><div align=justify><font size="2"><b>Programa: '.f($row,'nm_programa').'</b></font></div></td></tr>');
          if (nvl(f($row,'nm_cc'),'')!='') ShowHTML('   <tr><td colspan="2" bgcolor="#f0f0f0"><div align=justify><font size="2"><b>Classificação: '.f($row,'nm_cc').'</b></font></div></td></tr>');
          ShowHTML('   <tr><td colspan="2" bgcolor="#f0f0f0"><div align=justify><font size="2"><b>Projeto: '.f($row,'sq_projeto').' - '.f($row,'nm_projeto').'</b></div></td></tr>');
          if ($p_tipo!='WORD') {
            ShowHTML('   <tr><td colspan="2" bgcolor="#f0f0f0"><div align=justify><b>Responsável: '.ExibePessoa(null,$w_cliente,f($row,'resp_projeto'),$TP,f($row,'nm_resp_projeto')).'</b></div></td></tr>');
            ShowHTML('   <tr><td colspan="2" bgcolor="#f0f0f0"><div align=justify><b>Unidade responsável: '.ExibeUnidade(null,$w_cliente,f($row,'nm_unidade'),f($row,'sq_unidade'),$TP).'</b></div></td></tr>');
          } else {
            ShowHTML('   <tr><td colspan="2" bgcolor="#f0f0f0"><div align=justify><b>Responsável: '.f($row,'nm_resp_projeto').'</b></div></td></tr>');
            ShowHTML('   <tr><td colspan="2" bgcolor="#f0f0f0"><div align=justify><b>Unidade responsável: '.f($row,'nm_unidade').'</b></div></td></tr>');
          } 
          ShowHTML('   <tr><td colspan="2" bgcolor="#f0f0f0"><div align=justify><b>Execução prevista: '.FormataDataEdicao(f($row,'inicio_projeto')).' a '.FormataDataEdicao(f($row,'fim_projeto')).'</b></div></td></tr>');
          
          // Recupera o próximo período
          // O tratamento abaixo deve estar compatível com o da stored procedure sp_getRelProgresso
          $w_inicio = addDays(toDate($p_fim),1);
          $w_dias = (toDate($p_fim)-toDate($p_inicio))/86400;
          $w_meses  = floor($w_dias/28);
          if (substr($p_inicio,0,2)=='01' && toDate($p_fim)==last_day(toDate($p_fim))) {
            $w_fim = last_day(addDays($w_inicio,($w_meses*28)));
          } elseif (substr($p_inicio,0,2)=='01' && substr($p_fim,0,2)=='15' && substr($p_inicio,3)==substr($p_fim,3)) {
            $w_fim = last_day($w_inicio);
          } else {
            $w_fim = addDays($w_inicio,$w_dias);
          }
          
          // Indicadores
          if($p_indicador=='S') {
            ShowHTML('      <tr><td colspan="2"><br><font size="2"><b>Indicadores de performance do projeto<hr NOSHADE color=#000000 SIZE=1></b></td></tr>');
            ShowHTML('      <tr><td colspan="2"><table cellpadding=0 cellspacing=0>');
            if ($p_tipo!='WORD') {
              ShowHTML('   <tr><td><b>'.VisualIndicador($w_dir_volta,$w_cliente,'IGE',$TP,'IGE').' em '.date("d/m/Y").':</b>&nbsp;&nbsp;&nbsp;</td>');
            } else  {
              ShowHTML('   <tr><td><b>IGE em '.date("d/m/Y").':&nbsp;&nbsp;&nbsp;</b></td>');
            }
            ShowHTML('       <td><td align="right"><b>'.formatNumber(f($row,'ige')).'%</b></td></tr>');
            if ($p_tipo!='WORD') {
              ShowHTML('   <tr><td><b>'.VisualIndicador($w_dir_volta,$w_cliente,'IDE',$TP,'IDE').' em '.FormataDataEdicao($p_fim).':&nbsp;&nbsp;&nbsp;</b></td>');
            } else  { 
              ShowHTML('   <tr><td><b>IDE em '.FormataDataEdicao($p_fim).':&nbsp;&nbsp;&nbsp;</b></td>');
            }
            ShowHTML('       <td align="right">'.ExibeSmile('IDE',f($row,'ide')).'&nbsp;');
            ShowHTML('       <td align="right"><b>'.formatNumber(f($row,'ide')).'%</b></td>');
            ShowHTML('       <td>&nbsp;&nbsp;<b>(O cálculo do IDE leva em conta apenas as etapas previstas e entregues no período, desprezando as tarefas).</b></td>');
            ShowHTML('          </table>');
          }

          //Progresso no periodo
          if ($p_prevista=='S' || $p_realizada=='S' || $p_pendente=='S' || $p_proximo=='S' ) {
            ShowHTML('      <tr><td colspan="2"><br><font size="2"><b>Progresso no período<hr NOSHADE color=#000000 SIZE=1></b></td></tr>');
          }
          ShowHTML('      <tr><td align="center" colspan="2">');
          ShowHTML('          <table width=100%  border="1" bordercolor="#00000">');
          for ($bloco=1;$bloco<=4;$bloco++) {
            switch ($bloco) {
              case 1 : $w_label = 'ENTREGAS PREVISTAS';                        $w_restricao = 'PROPREV';   $w_mensagem = 'entrega prevista para o período'; break;
              case 2 : $w_label = 'ENTREGAS REALIZADAS';                       $w_restricao = 'PROREPORT'; $w_mensagem = 'entrega realizada para o período'; break;
              case 3 : $w_label = 'ENTREGAS PENDENTES';                        $w_restricao = 'PROPEND';   $w_mensagem = 'entrega pendente para o período'; break;
              case 4 : $w_label = 'ENTREGAS PREVISTAS PARA O PRÓXIMO PERÍODO ('.formataDataEdicao($w_inicio).' a '.formataDataEdicao($w_fim).')'; $w_restricao = 'PROENTR';   $w_mensagem = 'entrega prevista para o próximo período'; break;
            } 
            if(($w_restricao=='PROPREV'&& $p_prevista=='S')||($w_restricao=='PROREPORT'&& $p_realizada=='S')||($w_restricao=='PROPEND'&& $p_pendente=='S')||($w_restricao=='PROENTR'&& $p_proximo=='S')) {
              ShowHTML('          <tr><td bgColor="#f0f0f0" height="30" colspan="10"><div align="justify"><font size="2"><b>'.$w_label.'</b></font></div></td>');
              $RS1 = db_getRelProgresso::getInstanceOf($dbms,$w_cliente,f($row,'sq_projeto'),$p_inicio,$p_fim,$w_restricao);
              $RS1 = SortArray($RS1,'cd_ordem','asc','fim_previsto','asc','sq_projeto_etapa','asc','fim','asc','nm_tarefa','asc');
              if(count($RS1)==0) {
                ShowHTML('          <tr><td colspan="10" height=30 align="center"><b>Nenhuma '.$w_mensagem.'.</b></font></td>');
              } else {
                ShowHTML('          <tr>');
                ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Etapa</b></div></td>');
                ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Título</b></div></td>');
                ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Responsável</b></div></td>');
                ShowHTML('            <td colspan=2 bgColor="#f0f0f0"><div align="center"><b>Execução prevista</b></div></td>');
                ShowHTML('            <td colspan=2 bgColor="#f0f0f0"><div align="center"><b>Execução real</b></div></td>');
                ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Conc.</b></div></td>');
                ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Peso</b></div></td>');
                ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Situação atual</b></div></td>');
                ShowHTML('          </tr>');
                ShowHTML('          <tr>');
                ShowHTML('            <td bgColor="#f0f0f0"><div align="center"><b>de</b></div></td>');
                ShowHTML('            <td bgColor="#f0f0f0"><div align="center"><b>até</b></div></td>');
                ShowHTML('            <td bgColor="#f0f0f0"><div align="center"><b>de</b></div></td>');
                ShowHTML('            <td bgColor="#f0f0f0"><div align="center"><b>até</b></div></td>');
                ShowHTML('          </tr>');
                $w_sq_projeto_etapa = 0;
                foreach($RS1 as $row1) {
                  if($w_sq_projeto_etapa==0 || $w_sq_projeto_etapa!=f($row1,'sq_projeto_etapa')) {
                    ShowHTML('        <tr valign="top"><td nowrap>');
                    ShowHTML(ExibeImagemSolic('ETAPA',f($row1,'inicio_previsto'),f($row1,'fim_previsto'),f($row1,'inicio_real_etapa'),f($row1,'fim_real_etapa'),null,null,null, f($row1,'perc_conclusao')));
                    if ($p_tipo!='WORD') {
                      ShowHTML(' '.ExibeEtapa('V',f($row1,'sq_projeto'),f($row1,'sq_projeto_etapa'),'Volta',10,f($row1,'cd_ordem'),$TP,$SG).'');
                    } else {
                      ShowHTML(' '.f($row1,'cd_ordem').'');
                    }
                    if (1==1 || f($row1,'pacote_trabalho')=='S' || substr(nvl(f($row1,'restricao'),'-'),0,1)=='S') {
                      ShowHTML(' '.exibeImagemRestricao(f($row1,'restricao')).'</td>');
                    }
                    ShowHTML('        <td><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">'.str_repeat('<td width="3%"></td>',(null)).'<td>'.f($row1,'nm_etapa').'</b></tr></table>');
                    if ($p_tipo!='WORD') {
                      ShowHTML('        <td>'.ExibePessoa(null,$w_cliente,f($row1,'sq_pessoa'),$TP,f($row1,'nm_resp_etapa')).'</b>');
                    } else {
                      ShowHTML('        <td>'.f($row1,'nm_resp_etapa').'</b>');
                    }  
                    ShowHTML('        <td align="center" nowrap>'.nvl(formataDataEdicao(f($row1,'inicio_previsto'),5),'---').'</td>');
                    ShowHTML('        <td align="center" nowrap>'.nvl(formataDataEdicao(f($row1,'fim_previsto'),5),'---').'</td>');
                    ShowHTML('        <td align="center" nowrap>'.nvl(formataDataEdicao(f($row1,'inicio_real_etapa'),5),'---').'</td>');
                    ShowHTML('        <td align="center" nowrap>'.nvl(formataDataEdicao(f($row1,'fim_real_etapa'),5),'---').'</td>');
                    ShowHTML('        <td nowrap align="right">'.f($row1,'perc_conclusao').' %</td>');
                    ShowHTML('        <td nowrap align="center">'.f($row1,'peso').'</td>');
                    ShowHTML('        <td>'.nvl(CRLF2BR(f($row1,'situacao_atual')),'---').' </td>');
                  }
                  if(nvl(f($row1,'sq_tarefa'),'')!='') {
                    ShowHTML('<tr valign="top">');
                    ShowHTML('  <td>');
                    ShowHTML('  <td>');
                    ShowHTML(ExibeImagemSolic('GD',f($row1,'inicio'),f($row1,'fim'),f($row1,'inicio_real'),f($row1,'fim_real'),f($row1,'aviso_prox_conc'),f($row1,'aviso'),f($row1,'sg_tramite'), null));
                    if ($p_tipo!='WORD') { 
                      ShowHTML('  <A class="HL" HREF="projetoativ.php?par=Visual&R=projetoativ.php?par=Visual&O=L&w_chave='.f($row1,'sq_tarefa').'&p_tipo=&P1='.$P1.'&P2='.f($row1,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="blank">'.f($row1,'sq_tarefa').'</a>');
                    } else { 
                      ShowHTML('  '.f($row1,'sq_tarefa').' ');
                    }
                    $l_assunto = 'COMPLETO';
                    if (strlen(Nvl(f($row1,'nm_tarefa'),'-'))>50 && strtoupper($l_assunto)!='COMPLETO') ShowHTML(' - '.substr(Nvl(f($row1,'nm_tarefa'),'-'),0,50).'...');
                    else                                                                                ShowHTML(' - '.Nvl(crlf2br(f($row1,'nm_tarefa')),'-'));
                    if ($p_tipo!='WORD') {
                      ShowHTML('     <td>'.ExibePessoa(null,$w_cliente,f($row1,'solicitante'),$TP,f($row1,'nm_resp_tarefa')).'</td>');
                    } else {
                      ShowHTML('     <td>'.f($row1,'nm_resp_tarefa').'</td>');
                    }
                    ShowHTML('     <td align="center">'.Nvl(FormataDataEdicao(f($row1,'inicio'),5),'-').'</td>');
                    ShowHTML('     <td align="center">'.Nvl(FormataDataEdicao(f($row1,'fim'),5),'-').'</td>');
                    ShowHTML('     <td align="center">'.Nvl(FormataDataEdicao(f($row1,'inicio_real'),5),'---').'</td>');
                    ShowHTML('     <td align="center">'.Nvl(FormataDataEdicao(f($row1,'fim_real'),5),'---').'</td>');
                    ShowHTML('     <td colspan=3 nowrap>'.f($row1,'nm_tramite').'</td>');
                  } 
                  $w_sq_projeto_etapa = f($row1,'sq_projeto_etapa');
                }
              }
            }  
          }
          ShowHTML('        </table></td></tr>');

          // Riscos
          if ($p_questoes=='S') {
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
              ShowHTML('      <td colspan=4><b>Ação de Resposta</b></td>');
              ShowHTML('      <td><b>Fase atual</b></td>');
              ShowHTML('    </tr>');
              $w_cor=$conTrBgColor;
              foreach($RS1 as $row1) {
                ShowHtml(QuestoesLinhaAtiv(f($row,'sq_projeto'), f($row1,'chave'),f($row1,'chave_aux'),f($row1,'risco'),f($row1,'fase_atual'),f($row1,'criticidade'),f($row1,'nm_tipo_restricao'),f($row1,'descricao'),f($row1,'sq_pessoa'),f($row1,'nm_resp'),f($row1,'nm_estrategia'),f($row1,'acao_resposta'),f($row1,'nm_fase_atual'),f($row1,'qt_ativ'),f($row1,'nm_tipo'), $p_tipo, $p_tarefas, $p_pacotes));
              }
              ShowHTML('  </table>');
              ShowHTML('</table>');
            }
          }
        }
        $w_projeto_atual = f($row,'sq_projeto');
      }
      ShowHTML('   <tr><td colspan="2"><hr NOSHADE color=#000000 size=2></td></tr>');
    }
    if ($p_tipo!='WORD') Rodape();
  } elseif ($O=='P') {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Relatório de progresso do projeto</TITLE>');
    ScriptOpen('JavaScript');
    ShowHTML('  function marcaQuestao() {');
    ShowHTML('    if (document.Form.p_questoes.checked) {');
    ShowHTML('      document.Form.p_tarefas.disabled=false;');
    ShowHTML('      document.Form.p_pacotes.disabled=false;');
    ShowHTML('    } else {');
    ShowHTML('      document.Form.p_tarefas.disabled=true;');
    ShowHTML('      document.Form.p_pacotes.disabled=true;');
    ShowHTML('    }');
    ShowHTML('  }');
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
    ShowHTML('      <tr><td colspan=2><table border=0 cellpadding=0 cellspacing=0><tr>');
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PJCAD');
    SelecaoProjeto('Pr<u>o</u>jeto:','O','Selecione o projeto na relação.',$p_projeto,$w_usuario,f($RS,'sq_menu'),'p_projeto','PJLIST',null);
    ShowHTML('      </table>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td colspan=2><b><u>P</u>eríodo de reporte:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="p_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_inicio.'" onKeyDown="FormataData(this,event);">'.ExibeCalendario('Form','p_inicio').' a ');
    ShowHTML('                                                 <input '.$w_Disabled.' accesskey="P" type="text" name="p_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim.'" onKeyDown="FormataData(this,event);">'.ExibeCalendario('Form','p_fim').'</td>');
    ShowHTML('      <tr><td colspan=2><b>Informações a serem exibidas:');
    ShowHTML('          <tr><td colspan=2><INPUT '.$w_Disabled.' type="CHECKBOX" name="p_legenda" value="S" checked> Legenda dos sinalizadores </td>');
    ShowHTML('          <tr><td colspan=2><INPUT '.$w_Disabled.' type="CHECKBOX" name="p_indicador" value="S" checked> Indicadores de performance do projeto </td>');
    ShowHTML('          <tr><td colspan=2><INPUT '.$w_Disabled.' type="CHECKBOX" name="p_prevista" value="S" checked> Entregas previstas para o período de reporte</td>');
    ShowHTML('          <tr><td colspan=2><INPUT '.$w_Disabled.' type="CHECKBOX" name="p_realizada" value="S" checked> Entregas realizadas no período de reporte</td>');
    ShowHTML('          <tr><td colspan=2><INPUT '.$w_Disabled.' type="CHECKBOX" name="p_pendente" value="S" checked> Entregas pendentes</td>');
    ShowHTML('          <tr><td colspan=2><INPUT '.$w_Disabled.' type="CHECKBOX" name="p_proximo" value="S" checked> Entregas previstas para o próximas período de reporte</td>');
    ShowHTML('          <tr><td colspan=2><INPUT '.$w_Disabled.' type="CHECKBOX" name="p_questoes" value="S" checked onclick="javascript:marcaQuestao();"> Questões</td>');
    ShowHTML('          <tr><td width="3%"><td><INPUT '.$w_Disabled.' type="CHECKBOX" name="p_tarefas" value="S" checked> Tarefas vinculadas à questão</td>');
    ShowHTML('          <tr><td width="3%"><td><INPUT '.$w_Disabled.' type="CHECKBOX" name="p_pacotes" value="S" checked> Pacotes impactados pela questão</td>');
    ShowHTML('      <tr><td align="center" colspan=2><hr>');
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
  if ($p_tipo!='WORD') Rodape();
} 
// =========================================================================
// Gera uma linha de apresentação da tabela de questões
// -------------------------------------------------------------------------
function QuestoesLinhaAtiv($l_siw_solicitacao, $l_chave, $l_chave_aux, $l_risco, $l_fase_atual,$l_criticidade, 
    $l_tipo_restricao,$l_descricao,$l_sq_resp, $l_resp,$l_estrategia,$l_acao_resposta,$l_fase_atual, $l_qtd, 
    $l_tipo_r, $l_tipo, $l_tarefas, $l_pacotes ){
  extract($GLOBALS);
  global $w_cor;
  $l_ativ    = '';
  $l_row     = 1;
  $l_col     = 1;

  if($l_tarefas=='S') {
    // Recupera as tarefas que o usuário pode ver
    $RS_Ativ = db_getSolicRestricao::getInstanceOf($dbms,$l_chave_aux, null, null, null, null, null, 'TAREFA');
    if(count($RS_Ativ)>0) $l_row += count($RS_Ativ)+1;
  }
  
  // Recupera as etapas que são pacotes de trabalho
  if($l_pacotes=='S') {
    $RS_Pacote = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,$l_chave_aux,'QUESTAO',null);
    if (count($RS_Pacote)>0) {
      $l_row += 2; 
      foreach ($RS_Pacote as $row1) {
        if (f($row1,'vinculado')>0) {
          $l_row += 1;
        }
      }
    }
  }

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
  $l_html .= chr(13).'     <td>'.$l_tipo_r.'</td>';
  $l_html .= chr(13).'     <td>'.CRLF2BR($l_descricao).'</td>';
  if ($l_tipo!='WORD') {
    $l_html .= chr(13).'     <td>'.ExibePessoa(null,$w_cliente,$l_sq_resp,$TP,$l_resp).'</td>';
  } else {
    $l_html .= chr(13).'     <td>'.$l_resp.'</td>';
  } 
  $l_html .= chr(13).'     <td align="center">'.$l_estrategia.'</td>';  
  $l_html .= chr(13).'     <td colspan=4>'.$l_acao_resposta.'</td>';
  $l_html .= chr(13).'     <td>'.$l_fase_atual.'</td>';
  $l_html .= chr(13).'   </tr>';

  if($l_tarefas=='S') {  
    //Listagem das tarefas da etapa  
    if (count($RS_Ativ)>0) {
      $l_ativ .= chr(13).'    <tr bgColor="#f0f0f0" align="center" valign="top">';
      $l_ativ .= chr(13).'      <td><b>Tarefas</b></td>';
      $l_ativ .= chr(13).'      <td><b>Descrição</b></td>';        
      $l_ativ .= chr(13).'      <td colspan="2"><b>Período</b></td>';
      $l_ativ .= chr(13).'      <td colspan=4><b>Responsável</b></td>';
      $l_ativ .= chr(13).'      <td><b>Fase atual</b></td>';
      $l_ativ .= chr(13).'    </tr>';
      foreach ($RS_Ativ as $row) {
        $l_ativ .= chr(13).'      <tr><td>';
        $l_ativ .= chr(13).ExibeImagemSolic(f($row,'sg_servico'),f($row,'inicio'),f($row,'fim'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null);
        if ($l_tipo!='WORD') {
          $l_ativ .= chr(13).'  <A class="HL" HREF="projetoativ.php?par=Visual&R=ProjetoAtiv.php?par=Visual&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&p_tipo=&P1='.$P1.'&P2='.f($row,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="blank">'.f($row,'sq_siw_solicitacao').'</a>';
        } else {
          $l_ativ .= chr(13).'  '.f($row,'sq_siw_solicitacao').'</a>';
        }
        $l_ativ .= chr(13).'     <td>'.CRLF2BR(Nvl(f($row,'assunto'),'---'));
        $l_ativ .= chr(13).'     <td align="center">'.formataDataEdicao(nvl(f($row,'inicio_real'),f($row,'inicio'))).'</td>';
        $l_ativ .= chr(13).'     <td align="center">'.formataDataEdicao(nvl(f($row,'fim_real'),f($row,'fim'))).'</td>';
        if ($l_tipo!='WORD') {
          $l_ativ .= chr(13).'     <td colspan=4>'.ExibePessoa(null,$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_resp_tarefa')).'</td>';
        } else {
          $l_ativ .= chr(13).'     <td colspan=4>'.f($row,'nm_resp_tarefa').'</td>';
        }
        $l_ativ .= chr(13).'     <td>'.f($row,'nm_tramite').'</td>';
      } 
      $l_ativ .= chr(13).'      </td></tr>';
    } 
    if ($l_qt_ativ > '') {
      $l_ativ    = $l_ativ.chr(13).'            </td></tr>';
    } 
  } 
  
  if($l_pacotes=='S') {  
    //Listagem dos pacotes impactados pela questão
    if (count($RS_Pacote)>0) {
      $l_pacote .= chr(13).'    <tr bgColor="#f0f0f0" align="center">';
      $l_pacote .= chr(13).'      <td rowspan=2><b>Pacotes<br>impactados</b></td>';
      $l_pacote .= chr(13).'      <td rowspan=2><b>Título</b></td>';
      $l_pacote .= chr(13).'      <td colspan=2><b>Execução prevista</b></td>';
      $l_pacote .= chr(13).'      <td colspan=2><b>Execução real</b></td>';
      $l_pacote .= chr(13).'      <td rowspan=2><b>Conc</b></td>';
      $l_pacote .= chr(13).'      <td rowspan=2><b>Peso</b></td>';
      $l_pacote .= chr(13).'      <td rowspan=2><b>Situação atual</b></td>';
      $l_pacote .= chr(13).'    </tr>';
      $l_pacote .= chr(13).'    <tr bgColor="#f0f0f0" align="center" valign="top">';
      $l_pacote .= chr(13).'      <td><b>de</b></td>';
      $l_pacote .= chr(13).'      <td><b>até</b></td>';
      $l_pacote .= chr(13).'      <td><b>de</b></td>';
      $l_pacote .= chr(13).'      <td><b>até</b></td>';
      $l_pacote .= chr(13).'    </tr>';
      foreach ($RS_Pacote as $row) {
        if (f($row,'vinculado')>0) {
          $l_pacote .= chr(13).'      <tr><td>';
          $l_pacote .= chr(13).ExibeImagemSolic('ETAPA',f($row,'inicio_previsto'),f($row,'fim_previsto'),f($row,'inicio_real'),f($row,'fim_real'),null,null,null, f($row,'perc_conclusao'));
          if ($l_tipo!='WORD') {
            $l_pacote .= chr(13).     ExibeEtapa('V',f($row,'sq_siw_solicitacao'),f($row,'sq_projeto_etapa'),'Volta',10,MontaOrdemEtapa(f($row,'sq_projeto_etapa')),$TP,$SG).'</td>';
          } else {
            $l_pacote .= chr(13).'  '.f($row,'sq_siw_solicitacao').'</td>';
          }
          $l_pacote .= chr(13).'        <td>'.f($row,'titulo').'</a>';
          $l_pacote .= chr(13).'        <td align="center">'.formataDataEdicao(f($row,'inicio_previsto'),5).'</td>';
          $l_pacote .= chr(13).'        <td align="center">'.formataDataEdicao(f($row,'fim_previsto'),5).'</td>';
          $l_pacote .= chr(13).'        <td align="center">'.nvl(formataDataEdicao(f($row,'inicio_real'),5),'---').'</td>';
          $l_pacote .= chr(13).'        <td align="center">'.nvl(formataDataEdicao(f($row,'fim_real'),5),'---').'</td>';
          $l_pacote .= chr(13).'        <td align="right" nowrap>'.f($row,'perc_conclusao').' %</td>';
          $l_pacote .= chr(13).'        <td align="center" nowrap>'.f($row,'peso').'</td>';
          $l_pacote .= chr(13).'        <td>'.nvl(CRLF2BR(f($row,'situacao_atual')),'---').'</td>';
        }  
      } 
      $l_pacote .= chr(13).'      </td></tr>';
    } 
    if ($l_qt_pacote > '') {
      $l_pacote    = $l_pacote.chr(13).'            </td></tr>';
    } 
  }

  $l_html = $l_html.chr(13).'      </tr>';
  if ($l_ativ>'')      $l_html = $l_html.chr(13).str_replace('w_cor',$w_cor,$l_ativ);
  if ($l_pacote>'')    $l_html = $l_html.chr(13).str_replace('w_cor',$w_cor,$l_pacote);
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