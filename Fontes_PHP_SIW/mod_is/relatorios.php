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
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList_IS.php');
include_once($w_dir_volta.'classes/sp/db_getSolicMeta_IS.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicIndic_IS.php');
include_once($w_dir_volta.'classes/sp/db_getOrImport.php');
include_once($w_dir_volta.'classes/sp/db_getMetaMensal_IS.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData_IS.php');
include_once($w_dir_volta.'classes/sp/db_getPPADadoFinanc_IS.php');
include_once($w_dir_volta.'classes/sp/db_getRestricao_IS.php');
include_once($w_dir_volta.'classes/sp/db_getSolicInter.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade_IS.php');
include_once($w_dir_volta.'funcoes/selecaoProgramaPPA.php');
include_once($w_dir_volta.'funcoes/selecaoAcaoPPA.php');
include_once($w_dir_volta.'funcoes/selecaoPrioridade.php');
include_once($w_dir_volta.'funcoes/selecaoIsProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoAcao.php');
include_once($w_dir_volta.'funcoes/selecaoOpcaoEstrat.php');
include_once($w_dir_volta.'funcoes/selecaoMacroObjetivo.php');
include_once('visualprograma.php');
include_once('visualacao.php');
include_once('visualtarefa.php');
// =========================================================================
//  /relatorios.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Diversos tipos de relatórios para fazer o acompanhamento gerencial 
// Mail     : celso@sbpi.com.br
// Criacao  : 24/08/2006 11:00
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
$p_ordena   = $_REQUEST['p_ordena'];
$w_assinatura   = strtoupper($_REQUEST['w_assinatura']);
$w_pagina       = 'relatorios.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_is/';
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
// Relatório gerencial do planejamento estratégico
// -------------------------------------------------------------------------
function Gerencial() {
  extract($GLOBALS);
  $w_imagem = $conRootSIW.'images/icone/GotoTop.gif';
  $w_img_link = '<img src="'.$conRootSIW.'images/Folder/hlp.gif" border=0 width=15 height=15>';
  $p_sq_unidade = strtoupper(trim($_REQUEST['p_sq_unidade']));
  $p_cd_programa= strtoupper(trim($_REQUEST['p_cd_programa']));
  $p_cd_acao    = strtoupper(trim($_REQUEST['p_acao']));
  $w_tipo_rel   = strtoupper(trim($_REQUEST['w_tipo_rel']));
  $p_preenchida = strtoupper(trim($_REQUEST['p_preenchida']));
  $p_meta_ppa   = strtoupper(trim($_REQUEST['p_meta_ppa']));
  $p_exequivel  = strtoupper(trim($_REQUEST['p_exequivel']));

  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'')   $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
    $RS = db_getSolicMeta_IS::getInstanceOf($dbms,$w_cliente,null,null,'GERENCIAL',$w_ano,$p_sq_unidade,$p_cd_programa,$p_cd_acao,$p_preenchida,$p_meta_ppa,$p_exequivel,null,null);
  } 

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>Planejamento Estratégico - Exercício '.$w_ano.'</TITLE>');
  if ($O=='L') {
    ShowHTML('  <!-- CSS FILE for my tree-view menu -->');
    ShowHTML('  <link rel="stylesheet" type="text/css" href="'.$conRootSIW.'classes/menu/xPandMenu.css">');
    ShowHTML('  <!-- JS FILE for my tree-view menu -->');
    ShowHTML('  <script src="'.$conRootSIW.'classes/menu/xPandMenu.js"></script>');
  } else {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('p_sq_unidade','Responsável','HIDDEN','','2','60','1','1');
    Validate('p_cd_programa','Programa','HIDDEN','','1','18','1','1');
    Validate('p_cd_acao','Ação','HIDDEN','','1','18','1','1');
    Validate('w_tipo_rel','Tipo de Relatório','SELECT','','1','30','1','1');
    Validate('p_preenchida','Situação Atual da Meta','SELECT','','1','1','1','1');
    Validate('p_meta_ppa','Tipo da Meta','SELECT','','1','1','1','1');
    Validate('p_exequivel','Execução da Meta','SELECT','','1','1','1','1');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($O=='L') {
    BodyOpenClean(null);
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('<div align="center">');
    ShowHTML('<table width="95%" border="0" cellspacing="3">');
    ShowHTML('<tr><td colspan="2">');
    ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"></TD><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
    ShowHTML('PLANEJAMENTO ESTRATÉGICO<br>Exercício '.$w_ano);
    ShowHTML('</FONT></B></TD></TR></TABLE>');
  } else {
    BodyOpen('onLoad=\'document.Form.p_cd_programa.focus()\';');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<div align=center><center>');
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
    ShowHTML('<HR>');
  } 

  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
    ShowHTML('<tr><td colspan="2"><div align="center">');
    ShowHTML('<table border="0" width="100%">');
    if ($p_sq_unidade>'') {
      $RS1 = db_getUorgData::getInstanceOf($dbms,$p_sq_unidade);
      ShowHTML('<tr><td width="15%"><b>Área de planejamento:</b></td><td>'.f($RS1,'nome').' - '.f($RS1,'sigla').'</td>');
    } else {
      ShowHTML('<tr><td width="15%"><b>Área de planejamento:</b></td><td>Todas</td>');
    } 
    if ($p_cd_programa>'') {
      $RS1 = db_getProgramaPPA_IS::getInstanceOf($dbms,$p_cd_programa,$w_cliente,$w_ano,null,null,null,null);
      foreach($RS1 as $row1){$RS1=$row1; break;}
      ShowHTML('    <td width="7%"><b>Programa:</b></td><td nowrap>'.$p_cd_programa.' - '.f($RS1,'ds_programa').'</td></tr>');
    } else {
      ShowHTML('    <td width="7%"><b>Programa:</b></td><td>Todos</td></tr>');
    } 
    if ($p_cd_acao>'') {
      $RS1 = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,$p_cd_programa,substr($p_cd_acao,4,4),null,null,null,null,null,null,null);
      foreach($RS1 as $row1){$RS1=$row1; break;}
      ShowHTML('<tr valign="top"><td><b>Ação:</b></td><td>'.substr($p_cd_acao,4,4).' - '.f($RS1,'descricao_acao').'</td>');
    } else {
      ShowHTML('<tr valign="top"><td><b>Ação:</b></td><td>Todas</td>');
    } 
    if ($p_preenchida>'') {
      if ($p_preenchida=='S') ShowHTML('    <td><b>Indicador será cumprido:</b></td><td>Sim</td></tr>');
      else                    ShowHTML('    <td><b>Indicador será cumprido:</b></td><td>Não</td></tr>');
    } else {
      ShowHTML('    <td><b>Indicador será cumprido:</b></td><td>Tanto faz</td></tr>');
    } 
    if ($p_exequivel>'') {
      if ($p_exequivel=='S') ShowHTML('    <td><b>Meta será cumprida:</b></td><td>Sim</td></tr>');
      else                   ShowHTML('    <td><b>Meta será cumprida:</b></td><td>Não</td></tr>');
    } else {
      ShowHTML('    <td><b>Meta será cumprida:</b></td><td>Tanto faz</td></tr>');
    } 
    if ($p_meta_ppa>'') {
      if ($p_meta_ppa=='S') ShowHTML('    <td><b>Exibir planejamento cadastrado:</b></td><td>Sim</td>');  
      else                  ShowHTML('    <td><b>Exibir planejamento cadastrado:</b></td><td>Não</td>');
    } else {
      ShowHTML('    <td><b>Exibir planejamento cadastrado:</b></td><td>Tanto faz</td>');
    }
    ShowHTML('</ul></td></tr></table>');
    ShowHTML('</div></td></tr>');
    ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
    ShowHTML('<tr><td colspan="2">');
  
    if (count($RS)<=0) {
      $w_linha += 1;
      ShowHTML('    <tr><td colspan="13"><div align="center"><b>Não foram encontrados registros</b></div></td></tr>');
    } else {
      // Inclusão do arquivo da classe
      include_once($w_dir_volta.'classes/menu/xPandMenu.php');

      $i    = 0;
      $j    = 0;
      $k    = 0;
      $l    = 0;
      $m    = 0;
      $n    = 0;
      $o    = 0;

      // Listagem do planejamento de acordo com o filtro selecionado na tela de filtragem
      $w_opcao_atual = '';
      $w_macro_atual = '';
      $w_prog_atual  = '';
      $w_ind_atual   = '';
      $w_acao_atual  = '';
      $w_meta_atual  = '';

      foreach($RS as $row) {
        
        if ($i==0) {
          // Instanciando a classe menu
          $root = new XMenu();
        }

        //Inicio da montagem da lista das ações e metas de acordo com o filtro
        if (f($row,'cd_opcao')!=$w_opcao_atual) {
          $i += 1;
          eval('$node'.i.' = &$root->addItem(new XNode(\'Estratégia \'.f($row,\'cd_opcao\').\' - \'.f($row,\'nm_opcao\'),false));');
          $w_opcao_atual = f($row,'cd_opcao');
          $j = 0;
          $k = 0;
          $l = 0;
          $m = 0;
          $n = 0;
          $o = 0;
          $w_macro_atual = '';
          $w_prog_atual  = '';
          $w_acao_atual  = '';
        } 
        if (f($row,'cd_macro')!=$w_macro_atual) {
          $j += 1;
          eval('$node'.i.'_'.j.' = &$node'.i.'->addItem(new XNode(\'Objetivo \'.f($row,\'cd_macro\').\' - \'.f($row,\'nm_macro\'),false));');
          $k = 0;
          $l = 0;
          $m = 0;
          $n = 0;
          $o = 0;
          $w_macro_atual = f($row,'cd_macro');
          $w_prog_atual  = '';
          $w_acao_atual  = '';
        }
        if (f($row,'cd_programa')!=$w_prog_atual) {
          $k += 1;
          if (nvl(f($row,'sq_programa'),'')=='') {
            $link = ''; 
            eval('$node'.i.'_'.j.'_'.k.' = &$node'.i.'_'.j.'->addItem(new XNode($link.\' Programa \'.f($row,\'cd_programa\').\' - \'.f($row,\'nm_programa\'),false));');
          } else {
            $link = '<A class="HL" HREF="'.$w_dir.'programa.php?par=Visual&O=L&w_chave='.f($row,'sq_programa').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" TARGET="VisualAcao" title="Exibe as informações do programa.">'.$w_img_link.'</a>';
            eval('$node'.i.'_'.j.'_'.k.' = &$node'.i.'_'.j.'->addItem(new XNode($link.\' Programa \'.f($row,\'cd_programa\').\' - \'.f($row,\'nm_programa\'),false,null,null));');
          }
          $l = 0;
          $m = 0;
          $n = 0;
          $o = 0;
          $w_prog_atual = f($row,'cd_programa');
          $w_acao_atual  = '';
          $RS1 = db_getSolicIndic_IS::getInstanceOf($dbms,f($row,'sq_programa'),null,'LISTA',null,null);
          $RS1 = SortArray($RS1,'ordem','asc');
          if (count($RS1)>0) {
            $w_indicador   = '        <table border=1 width="100%" cellpadding=0>'.$crlf.
                             '          <tr bgcolor="'.$conTrBgColor.'" align="center">'.$crlf.
                             '            <td width="45%"><b>Indicador</td>'.$crlf.
                             '            <td width="5%"><b>PPA</td>'.$crlf.
                             '            <td width="10%"><b>Índice<br>referência</td>'.$crlf.
                             '            <td width="10%"><b>Índice<br>programado</td>'.$crlf.
                             '            <td width="10%"><b>Índice<br>apurado</td>'.$crlf.
                             '            <td width="10%"><b>Data<br>apuracao</td>'.$crlf.
                             '            <td width="10%"><b>Unidade<br>medida</td>'.$crlf.
                             '          </tr>'.$crlf;
            foreach($RS1 as $row1) {
              $w_indicador .= Indicadorlinha(f($row1,'sq_siw_solicitacao'),f($row1,'sq_indicador'),f($row1,'titulo'),f($row1,'valor_referencia'),f($row1,'quantidade'),f($row1,'valor_apurado'),f($row1,'apuracao_indice'),f($row1,'nm_unidade_medida'),null,'<b>','N','PROJETO',f($row1,'cd_indicador'),f($row1,'exequivel'));
            } 
            $w_indicador   .= '        </table>'.$crlf;
            $l += 1;
            if (count($RS1)==1) {
              eval('$node'.i.'_'.j.'_'.k.'_'.l.' = &$node'.i.'_'.j.'_'.k.'->addItem(new XNode(\'Indicador\',false,$w_imagem,$w_imagem));');
            } else {
              eval('$node'.i.'_'.j.'_'.k.'_'.l.' = &$node'.i.'_'.j.'_'.k.'->addItem(new XNode(\'Indicadores\',false,$w_imagem,$w_imagem));');
            }
            eval('$node'.i.'_'.j.'_'.k.'_'.l.'_1 = &$node'.i.'_'.j.'_'.k.'_'.l.'->addItem(new XNode($w_indicador,false,null,null,null));');
          } 
        } 
        if (f($row,'cd_acao')!=$w_acao_atual) {
          $l += 1;
          $m = 0;
          $n = 0;
          $o = 0;
          if (nvl(f($row,'sq_acao'),'')=='') $link = ''; else $link = '<A class="HL" HREF="'.$w_dir.'acao.php?par=Visual&O=L&w_chave='.f($row,'sq_acao').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" TARGET="VisualAcao" title="Exibe as informações da ação.">'.$w_img_link.'</a>';
          eval('$node'.i.'_'.j.'_'.k.'_'.l.' = &$node'.i.'_'.j.'_'.k.'->addItem(new XNode($link.\' Ação \'.f($row,\'cd_acao\').\' - \'.f($row,\'nm_acao\'),false, null, null));');
          $w_acao_atual = f($row,'cd_acao');
          // Recupera as metas
          $RS3 = db_getSolicMeta_IS::getInstanceOf($dbms,$w_cliente,f($row,'sq_acao'),null,'LISTA',null,null,null,null,null,null,null,null,null);
          $RS3 = SortArray($RS3,'ordem','asc');
          if (count($RS3)>0) {
            $w_meta = '        <table border=1 width="100%" cellpadding=0>'.$crlf.
                      '          <tr bgcolor="'.$conTrBgColor.'" align="center">'.$crlf.
                      '            <td width="50%"><b>Produto</td>'.$crlf.
                      '            <td width="10%"><b>Meta PPA</td>'.$crlf.
                      '            <td width="10%"><b>Fim previsto</td>'.$crlf.
                      '            <td width="10%"><b>Unidade<br>medida</td>'.$crlf.
                      '            <td width="10%"><b>Quantitativo<br>programado</td>'.$crlf.
                      '            <td width="10%"><b>% Realizado</td>'.$crlf.
                      '          </tr>'.$crlf;
            $w_cor='';
            foreach($RS3 as $row3) {
              $w_meta .= MetaLinha(f($row,'sq_acao'),f($row3,'sq_meta'),f($row3,'titulo'),$w_tipo_rel,f($row3,'programada'),f($row3,'unidade_medida'),f($row3,'quantidade'),f($row3,'fim_previsto'),f($row3,'perc_conclusao'),'S','PROJETO',Nvl(f($row3,'cd_subacao'),''),f($row3,'exequivel'));
            } 
            $w_meta .= '        </table>'.$crlf;
            $m += 1;
            if (count($RS3)==1) {
              eval('$node'.i.'_'.j.'_'.k.'_'.l.'_'.m.' = &$node'.i.'_'.j.'_'.k.'_'.l.'->addItem(new XNode(\'Meta\',false,$w_imagem,$w_imagem));');
            } else {
              eval('$node'.i.'_'.j.'_'.k.'_'.l.'_'.m.' = &$node'.i.'_'.j.'_'.k.'_'.l.'->addItem(new XNode(\'Metas\',false,$w_imagem,$w_imagem));');
            }
            eval('$node'.i.'_'.j.'_'.k.'_'.l.'_'.m.'_1 = &$node'.i.'_'.j.'_'.k.'_'.l.'_'.m.'->addItem(new XNode($w_meta,false,null,null,null));');
          }

          // Recupera as tarefas
          $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ISTCAD');
          $RS3 = db_getSolicList_IS::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),4,
                    null,null,null,null,null,null,null,$p_prioridade,null,null,null,null,
                    null,null,null,null,null,null,null,null,null,nvl(f($row,'sq_acao'),0),null,
                    null,null,null,null,$w_ano);
          //$RS3 = SortArray($RS3,'phpdt_fim','asc','prioridade','asc');
          if (count($RS3)>0) {
            $w_meta = '        <table border=1 width="100%" cellpadding=0>'.$crlf.
                      '          <tr bgcolor="'.$conTrBgColor.'" align="center">'.$crlf.
                      '            <td><b>Código</td>'.$crlf.
                      '            <td><b>Tarefa</td>'.$crlf.
                      '            <td><b>Responsável</td>'.$crlf.
                      '            <td><b>Parcerias</td>'.$crlf.
                      '            <td><b>Fim previsto</td>'.$crlf.
                      '            <td><b>Programado</td>'.$crlf.
                      '            <td><b>Executado</td>'.$crlf.
                      '            <td><b>Fase atual</td>'.$crlf.
                      '            <td><b>Prioridade</td>'.$crlf;
                      '          </tr>'.$crlf;
            foreach($RS3 as $row3) {
              //If w_cor = conTrBgColor or w_cor = '' Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              $w_cor=$conTrBgColor;
              $w_meta .= '          <tr bgcolor="'.$w_cor.'" valign="top">'.$crlf.
                         '            <td nowrap>'.$crlf;
              if (f($row3,'concluida')=='N') {
                if (f($row3,'fim')<addDays(time(),-1)) $w_meta .= '           <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">';
                elseif (f($row3,'aviso_prox_conc')=='S' && (f($row3,'aviso')<=addDays(time(),-1))) $w_meta .= '           <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">';
                else                                   $w_meta .= '           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">';
              } else {
                if (f($row3,'fim')<Nvl(f($row3,'fim_real'),f($row3,'fim'))) $w_meta .= '           <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">';
                else                                                        $w_meta .= '           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">';
              } 
              $w_meta .= '        <A class="HL" HREF="'.$w_dir.'tarefas.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row3,'sq_siw_solicitacao').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" TARGET="VisualTarefa" title="Exibe as informações desta tarefa.">'.f($row3,'sq_siw_solicitacao').'&nbsp;</a>'.$crlf.
                         '        <td>'.f($row3,'titulo').'</td>'.$crlf.
                         '        <td>'.f($row3,'nm_solic').'</td>'.$crlf.
                         '        <td>'.Nvl(f($row3,'proponente'),'---').'</td>'.$crlf.
                         '        <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row3,'fim')),'-').'</td>'.$crlf.
                         '        <td align="right">'.number_format(f($row3,'valor'),2,',','.').'&nbsp;</td>'.$crlf.
                         '        <td align="right">'.number_format(f($row3,'custo_real'),2,',','.').'&nbsp;</td>'.$crlf.
                         '        <td nowrap>'.f($row3,'nm_tramite').'</td>'.$crlf.
                         '        <td nowrap>'.RetornaPrioridade(f($row3,'prioridade')).'</td>'.$crlf.
                         '        </td>'.$crlf.
                         '      </tr>'.$crlf;
            } 
            $w_meta .= '        </table>'.$crlf;
            $m += 1;
            if (count($RS3)==1) {
              eval('$node'.i.'_'.j.'_'.k.'_'.l.'_'.m.' = &$node'.i.'_'.j.'_'.k.'_'.l.'->addItem(new XNode(\'Tarefa\',false,$w_imagem,$w_imagem));');
            } else {
              eval('$node'.i.'_'.j.'_'.k.'_'.l.'_'.m.' = &$node'.i.'_'.j.'_'.k.'_'.l.'->addItem(new XNode(\'Tarefas\',false,$w_imagem,$w_imagem));');
            }
            eval('$node'.i.'_'.j.'_'.k.'_'.l.'_'.m.'_1 = &$node'.i.'_'.j.'_'.k.'_'.l.'_'.m.'->addItem(new XNode($w_meta,false,null,null,null));');
          } 

          // Recupera os projetos
          $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PJCAD');
          $RS3 = db_getSolicList::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,'PJCAD',4,
                  null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,
                  null,null,null,null,null,null,null,nvl(f($row,'sq_acao'),0), null);

          //$RS3 = SortArray($RS3,'phpdt_fim','asc','prioridade','asc');
          if (count($RS3)>0) {
            $w_meta = '        <table border=1 width="100%" cellpadding=0>'.$crlf.
                      '          <tr bgcolor="'.$conTrBgColor.'" align="center">'.$crlf.
                      '            <td><b>Código</td>'.$crlf.
                      '            <td><b>Projeto</td>'.$crlf.
                      '            <td><b>Responsável</td>'.$crlf.
                      '            <td><b>Conclusão</td>'.$crlf.
                      '            <td><b>$ Previsto</td>'.$crlf.
                      '            <td><b>$ Executado</td>'.$crlf.
                      '            <td><b>Prioridade</td>'.$crlf;
                      '          </tr>'.$crlf;
            foreach($RS3 as $row3) {
              //If w_cor = conTrBgColor or w_cor = '' Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              $w_cor=$conTrBgColor;
              $w_meta .= '          <tr bgcolor="'.$w_cor.'" valign="top">'.$crlf.
                         '            <td nowrap>'.$crlf;
              if (f($row3,'concluida')=='N') {
                if (f($row3,'fim')<addDays(time(),-1)) $w_meta .= '           <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">';
                elseif (f($row3,'aviso_prox_conc')=='S' && (f($row3,'aviso')<=addDays(time(),-1))) $w_meta .= '           <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">';
                else                                   $w_meta .= '           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">';
              } else {
                if (f($row3,'fim')<Nvl(f($row3,'fim_real'),f($row3,'fim'))) $w_meta .= '           <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">';
                else                                                        $w_meta .= '           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">';
              } 
              $w_meta .= '        <A class="HL" HREF="projeto.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row3,'sq_siw_solicitacao').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" TARGET="VisualTarefa" title="Exibe as informações desta tarefa.">'.f($row3,'sq_siw_solicitacao').'&nbsp;</a>'.$crlf.
                         '        <td>'.f($row3,'titulo').'</td>'.$crlf.
                         '        <td>'.f($row3,'nm_solic').'</td>'.$crlf.
                         '        <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row3,'fim')),'-').'</td>'.$crlf.
                         '        <td align="right">'.number_format(f($row3,'valor'),2,',','.').'&nbsp;</td>'.$crlf.
                         '        <td align="right">'.number_format(f($row3,'custo_real'),2,',','.').'&nbsp;</td>'.$crlf.
                         '        <td nowrap>'.RetornaPrioridade(f($row3,'prioridade')).'</td>'.$crlf.
                         '        </td>'.$crlf.
                         '      </tr>'.$crlf;
            } 
            $w_meta .= '        </table>'.$crlf;
            $m += 1;
            if (count($RS3)==1) {
              eval('$node'.i.'_'.j.'_'.k.'_'.l.'_'.m.' = &$node'.i.'_'.j.'_'.k.'_'.l.'->addItem(new XNode(\'Projeto\',false,$w_imagem,$w_imagem));');
            } else {
              eval('$node'.i.'_'.j.'_'.k.'_'.l.'_'.m.' = &$node'.i.'_'.j.'_'.k.'_'.l.'->addItem(new XNode(\'Projetos\',false,$w_imagem,$w_imagem));');
            }
            eval('$node'.i.'_'.j.'_'.k.'_'.l.'_'.m.'_1 = &$node'.i.'_'.j.'_'.k.'_'.l.'_'.m.'->addItem(new XNode($w_meta,false,null,null,null));');
          } 
        } 
      } 
    } 
    if ($i>0) $menu_html_code = $root->generateTree();
    ShowHTML($menu_html_code);
    ShowHTML('</tr>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Acao',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="p_opcao_ant" value="'.$p_opcao_ant.'">');    
    ShowHTML('<tr><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    SelecaoUnidade('Á<U>r</U>ea planejamento:','R',null,$p_sq_unidade,null,'p_sq_unidade',null,null);
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    SelecaoProgramaPPA('<u>P</u>rograma PPA:','P',null,$w_cliente,$w_ano,$p_cd_programa,'p_cd_programa','RELATORIO','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_cd_acao\'; document.Form.target=\'\'; document.Form.O.value=\'P\'; document.Form.submit();"',$w_menu,null,null);
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    SelecaoAcaoPPA('<u>A</u>ção PPA:','A',null,$w_cliente,$w_ano,$p_cd_programa,null,null,null,'p_cd_acao',null,null,null,$w_menu,null,null);
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('        <td><b><u>T</u>ipo de relatório:</b><br><SELECT ACCESSKEY="T" CLASS="STS" NAME="w_tipo_rel" '.$w_Disabled.'>');
    if (nvl($w_tipo_rel,'-')=='Word') {
      ShowHTML('          <option value="">Consulta na Tela');
      ShowHTML('          <option value="Word" SELECTED>Documento Word');
    } else {
      ShowHTML('          <option value="" SELECTED>Consulta na Tela');
      ShowHTML('          <option value="Word">Documento Word');
    } 
    ShowHTML('          </select></td><tr>');
    ShowHTML('    </table></td></tr>');
    ShowHTML('<tr><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr bgcolor="'.$conTrAlternateBgColor.'"><td colspan="2"><b>OPÇÕES DE CONSULTA</b></td></tr>');
    ShowHTML('      <tr><td width="25%" bgcolor="'.$conTrBgColor.'"><b>Execução do <u>i</u>ndicador:</b>');
    ShowHTML('          <td bgcolor="'.$conTrBgColor.'"><SELECT ACCESSKEY="I" CLASS="STS" NAME="p_preenchida">');
    if (nvl($p_preenchida,'-')!='S' || nvl($p_preenchida,'-')!='N') {
      ShowHTML('          <option value="" SELECTED>Todos');
      ShowHTML('          <option value="S">Indicadores que serão cumpridos');
      ShowHTML('          <option value="N">Indicadores que não serão cumpridos');
    } elseif (nvl($p_preenchida,'-')=='S') {
      ShowHTML('          <option value="">Todos');
      ShowHTML('          <option value="S" SELECTED>Indicadores que serão cumpridos');
      ShowHTML('          <option value="N">Indicadores que não serão cumpridos');
    } elseif (nvl($p_preenchida,'-')=='N') {
      ShowHTML('          <option value="">Todos');
      ShowHTML('          <option value="S">Indicadores que serão cumpridos');
      ShowHTML('          <option value="N" SELECTED>Indicadores que não serão cumpridos');
    } 
    ShowHTML('          </select></td></tr>');
    ShowHTML('      <tr><td bgcolor="'.$conTrBgColor.'"><b><u>E</u>xecução da Meta:</b>');
    ShowHTML('          <td bgcolor="'.$conTrBgColor.'"><SELECT ACCESSKEY="E" CLASS="STS" NAME="p_exequivel">');
    if (nvl($p_exequivel,'-')!='S' || nvl($p_exequivel,'-')!='N') {
      ShowHTML('          <option value="" SELECTED>Todas');
      ShowHTML('          <option value="S">Metas que serão cumpridas');
      ShowHTML('          <option value="N">Metas que não serão cumpridas');
    } elseif (nvl($p_exequivel,'-')=='S') {
      ShowHTML('          <option value="">Todas');
      ShowHTML('          <option value="S" SELECTED>Metas que serão cumpridas');
      ShowHTML('          <option value="N">Metas que não serão cumpridas');
    } elseif (nvl($p_exequivel,'-')=='N') {
      ShowHTML('          <option value="">Todas');
      ShowHTML('          <option value="S">Metas que serão cumpridas');
      ShowHTML('          <option value="N" SELECTED>Metas que não serão cumpridas');
    } 
    ShowHTML('          </select></td></tr>');
    ShowHTML('      <tr><td bgcolor="'.$conTrBgColor.'"><b>Exi<u>b</u>ir:</b>');
    ShowHTML('          <td bgcolor="'.$conTrBgColor.'"><SELECT ACCESSKEY="B" CLASS="STS" NAME="p_meta_ppa">');
    if (nvl($p_meta_ppa,'-')!='S' || nvl($p_meta_ppa,'-')!='N') {
      ShowHTML('          <option value="" SELECTED>Todo o planejamento');
      ShowHTML('          <option value="S">Apenas programas e ações cadastrados');
      ShowHTML('          <option value="N">Apenas programas e ações não cadastrados');
    } elseif (nvl($p_meta_ppa,'-')=='S') {
      ShowHTML('          <option value="">Todo o planejamento');
      ShowHTML('          <option value="S" SELECTED>Apenas programas e ações cadastrados');
      ShowHTML('          <option value="N">Apenas programas e ações não cadastrados');
    } elseif (nvl($p_meta_ppa,'-')=='N') {
      ShowHTML('          <option value="">Todo o planejamento');
      ShowHTML('          <option value="S">Apenas programas e ações cadastrados');
      ShowHTML('          <option value="N" SELECTED>Apenas programas e ações não cadastrados');
    } 
    ShowHTML('          </select></td></tr>');
    ShowHTML('    </table></td></tr>');
    ShowHTML('    <table width="100%" border="0">');
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
  ShowHTML('</DIV>');
  ShowHTML('</BODY>');
  ShowHTML('</HTML>');
} 

// =========================================================================
// Relatório da tabela do PPA
// -------------------------------------------------------------------------
function Rel_PPA() {
  extract($GLOBALS);
  $w_chave      = $_REQUEST['w_chave'];
  $w_tipo_rel   = strtoupper(trim($_REQUEST['w_tipo_rel']));
  $p_codigo     = strtoupper(trim($_REQUEST['p_codigo']));
  if ($_REQUEST['p_cd_programa']>'' && $p_codigo=='') $p_cd_programa = $_REQUEST['p_cd_programa'];
  else                                                $p_cd_programa = substr($p_codigo,0,4);
  $p_cd_acao            = substr($p_codigo,4,4);
  $p_sq_unidade_resp    = $_REQUEST['p_sq_unidade_resp'];
  $p_prioridade         = strtoupper(trim($_REQUEST['p_prioridade']));
  $p_selecionada_mp     = strtoupper(trim($_REQUEST['p_selecionada_mp']));
  $p_selecionada_se     = strtoupper(trim($_REQUEST['p_selecionada_se']));
  $p_tarefas_atraso     = strtoupper(trim($_REQUEST['p_tarefas_atraso']));
  if(is_array($_REQUEST['p_campos'])) $p_campos = explodeArray($_REQUEST['p_campos']);
  else                                $p_campos = $_REQUEST['p_campos'];
  $p_tarefas            = $_REQUEST['p_tarefas'];
  $p_metas              = $_REQUEST['p_metas'];
  $p_opcao              = $_REQUEST['p_opcao'];
  $p_opcao_ant          = $_REQUEST['p_opcao'];
  if($_REQUEST['p_opcao_ant']!=$_REQUEST['p_opcao']) {
    $p_macro     = '';
  } else {
    $p_macro              = $_REQUEST['p_macro'];
  }
  $p_ordena             = $_REQUEST['p_ordena'];
  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'') $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
    // Recupera todos os registros para a listagem
    if ($p_cd_programa>'' && $p_codigo=='') $RS = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,$p_cd_programa,null,null,null,null,null,null,$p_macro,$p_opcao);
    else                                    $RS = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,substr($p_codigo,0,4),substr($p_codigo,4,4),null,substr($p_codigo,12,17),null,null,null,$p_macro,$p_opcao);
    if (Nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {
      $RS = SortArray($RS,'descricao_acao','asc');
    } 
  } 
  if ($w_tipo_rel=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_pag=1;
    $w_linha=5;
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
    ShowHTML('Relatório Analítico - Ações PPA 2004 - 2007 Exercício '.$w_ano);
    ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
    ShowHTML('<TR><TD COLSPAN="2" ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Página: '.$w_pag.'</B></TD></TR>');
    ShowHTML('</TD></TR>');
    ShowHTML('</FONT></B></TD></TR></TABLE>');
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Relatório Analítico - Ações PPA 2004 - 2007 Exercício '.$w_ano.'</TITLE>');
    if (!(strpos('P',$O)===false)) {
      ScriptOpen('JavaScript');
      ValidateOpen('Validacao');
      Validate('p_cd_programa','Programa','SELECT','','1','18','','1');
      Validate('p_codigo','Ação','SELECT','','1','18','1','1');
      ShowHTML('  if (theForm.p_tarefas.checked == false) {');
      ShowHTML('     theForm.p_prioridade.value = \'\'');
      ShowHTML('  }');
      ShowHTML('  if (theForm.p_tarefas.checked == false && theForm.p_tarefas_atraso[0].checked == true) {');
      ShowHTML('      alert(\'Para exibir somente as tarefas em atraso,\n\n é preciso escolher a exibição da tarefa \');');
      ShowHTML('      return (false);');
      ShowHTML('  }');
      ValidateClose();
      ShowHTML('  function MarcaTodosCampos() {');
      ShowHTML('    if (document.Form.w_marca_campos.checked==true) ');
      ShowHTML('       for (i=0; i < 7; i++) {');
      ShowHTML('         document.Form["p_campos[]"][i].checked=true;');
      ShowHTML('    } else { ');
      ShowHTML('       for (i=0; i < 7; i++) {');
      ShowHTML('         document.Form["p_campos[]"][i].checked=false;');
      ShowHTML('       } ');
      ShowHTML('    } ');
      ShowHTML('  }');
      ShowHTML('  function MarcaTodosBloco() {');
      ShowHTML('    if (document.Form.w_marca_bloco.checked==true) {');
      ShowHTML('         document.Form.p_tarefas.checked=true;');
      ShowHTML('         document.Form.p_metas.checked=true;');
      ShowHTML('         document.Form.p_sq_unidade_resp.checked=true;');
      ShowHTML('    } else { ');
      ShowHTML('         document.Form.p_tarefas.checked=false;');
      ShowHTML('         document.Form.p_metas.checked=false;');
      ShowHTML('         document.Form.p_sq_unidade_resp.checked=false;');
      ShowHTML('    } ');
      ShowHTML('  }');
      ScriptClose();
    } 
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($O=='L') {
      BodyOpenClean(null);
      ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
      ShowHTML('Relatório Analítico - Ações PPA 2004 - 2007 Exercício '.$w_ano);
      ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
      ShowHTML('&nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&w_chave='.$w_chave.'&w_tipo_rel=word&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
      ShowHTML('&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      ShowHTML('</TD></TR>');
      ShowHTML('</FONT></B></TD></TR></TABLE>');
    } else {
      BodyOpen('onLoad=\'document.Form.p_cd_programa.focus()\';');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    } 
    ShowHTML('<HR>');
  }
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    $w_col      = 2;
    $w_col_word = 2;
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    $w_filtro='';
    if ($p_prioridade>'')       $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Prioridade<td>[<b>'.RetornaPrioridade($p_prioridade).'</b>]';
    if ($p_selecionada_mp>'')   $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Selecionada SPI/MP<td>[<b>'.$p_selecionada_mp.'</b>]';
    if ($p_selecionada_se>'')   $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Selecionada SE/SEPPIR<td>[<b>'.$p_selecionada_se.'</b>]';
    if ($p_tarefas_atraso>'')   $w_filtro=$w_filtro.'<td>Ações com tarefas em atraso&nbsp;[<b>'.$p_tarefas_atraso.'</b>]&nbsp;';
    ShowHTML('<tr><td align="left" colspan=2>');
    if ($w_filtro>'')   ShowHTML('<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>');
    ShowHTML('    <td align="right" valign="botton"><b>Registros listados: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    if ($w_tipo_rel=='WORD') ShowHTML('          <td><b>Código</td>');
    else                     ShowHTML('          <td><b>'.LinkOrdena('Código','codigo').'</td>');
    if ($w_tipo_rel=='WORD') ShowHTML('          <td><b>Nome</td>');
    else                     ShowHTML('          <td><b>'.LinkOrdena('Nome','descricao_acao').'</td>');
    if (!(strpos($p_campos,'responsavel')===false)) {
      if ($w_tipo_rel=='WORD') ShowHTML('          <td><b>Responsável</td>');
      else                     ShowHTML('          <td><b>'.LinkOrdena('Responsável','responsavel').'</td>');
      $w_col        += 1;
      $w_col_word   += 1;
    } 
    if (!(strpos($p_campos,'email')===false)) {
      if ($w_tipo_rel=='WORD') ShowHTML('          <td nowrap><b>e-Mail</td>');
      else                     ShowHTML('          <td nowrap><b>'.LinkOrdena('e-Mail','email').'</td>');
      $w_col        += 1;
      $w_col_word   += 1;
    }
    if (!(strpos($p_campos,'telefone')===false)) {
      if ($w_tipo_rel=='WORD')  ShowHTML('          <td><b>Telefone</td>');
      else                      ShowHTML('          <td><b>'.LinkOrdena('Telefone','telefone').'</td>');
      $w_col        += 1;
      $w_col_word   += 1;
    } 
    if (!(strpos($p_campos,'orgao')===false)) {
      if ($w_tipo_rel=='WORD')  ShowHTML('          <td><b>Órgão</td>');
      else                      ShowHTML('          <td><b>'.LinkOrdena('Órgão','ds_orgao').'</td>');
      $w_col        += 1;
      $w_col_word   += 1;
    }
    if (!(strpos($p_campos,'aprovado')===false)) {
      if ($w_tipo_rel=='WORD')  ShowHTML('          <td><b>Aprovado</td>');
      else                      ShowHTML('          <td><b>'.LinkOrdena('Aprovado','previsao_ano').'</td>');
      $w_col_word   += 1;
    }
    if (!(strpos($p_campos,'autorizado')===false)) {
      if ($w_tipo_rel=='WORD')  ShowHTML('          <td><b>Autorizado</td>');
      else                      ShowHTML('          <td><b>'.LinkOrdena('Autorizado','atual_ano').'</td>');
      $w_col_word   += 1;
    }
    if (!(strpos($p_campos,'saldo')===false)) {
      ShowHTML('          <td><b>Saldo</td>');
      $w_col_word   += 1;
    } 
    if (!(strpos($p_campos,'realizado')===false)) {
      if ($w_tipo_rel=='WORD')  ShowHTML('          <td><b>Realizado</td>');
      else                      ShowHTML('          <td><b>'.LinkOrdena('Realizado','real_ano').'</td>');
      $w_col_word   += 1;
    }
    if (!(strpos($p_campos,'liquidar')===false)) {
      ShowHTML('          <td><b>A liquidar</td>');
      $w_col_word   += 1;
    } 
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$w_col.' align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_acao_aprovado  = 0.00;
      $w_acao_saldo     = 0.00;
      $w_acao_empenhado = 0.00;
      $w_acao_liquidado = 0.00;
      $w_acao_liquidar  = 0.00;
      $w_tot_aprovado   = 0.00;
      $w_tot_saldo      = 0.00;
      $w_tot_empenhado  = 0.00;
      $w_tot_liquidado  = 0.00;
      $w_tot_liquidar   = 0.00;
      $w_atual='';
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        if ($w_linha>22 && $w_tipo_rel=='WORD') {
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
          ShowHTML('</table>');
          ShowHTML('</center></div>');
          ShowHTML('    <br style="page-break-after:always">');
          $w_linha=5;
          $w_pag=$w_pag+1;
          ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
          ShowHTML('Ações PPA');
          ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
          ShowHTML('<TR><TD COLSPAN="2" ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Página: '.$w_pag.'</B></TD></TR>');
          ShowHTML('</TD></TR>');
          ShowHTML('</FONT></B></TD></TR></TABLE>');
          ShowHTML('<div align=center><center>');
          ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
          // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
          if ($w_filtro>'') ShowHTML('<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>');
          ShowHTML('    <td align="right" valign="botton"><b>Registros listados: '.count($RS));
          ShowHTML('<tr><td align="center" colspan=3>');
          ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
          ShowHTML('          <td><b>Código</td>');
          ShowHTML('          <td><b>Nome</td>');
          if (!(strpos($p_campos,'responsavel')===false)) ShowHTML('          <td><b>Responsável</td>');
          if (!(strpos($p_campos,'email')===false))       ShowHTML('          <td><b>e-Mail</td>');
          if ((strpos($p_campos,'telefone')===false))     ShowHTML('          <td><b>Telefone</td>');
          if (!(strpos($p_campos,'orgao')===false))       ShowHTML('          <td><b>Órgão</td>');
          if (!(strpos($p_campos,'aprovado')===false))    ShowHTML('          <td><b>Aprovado</td>');
          if (!(strpos($p_campos,'autorizado')===false))  ShowHTML('          <td><b>Autorizado</td>');
          if (!(strpos($p_campos,'saldo')===false))       ShowHTML('          <td><b>Saldo</td>');
          if (!(strpos($p_campos,'realizado')===false))   ShowHTML('          <td><b>Realizado</td>');
          if ((strpos($p_campos,'liquidar')===false))     ShowHTML('          <td><b>A liquidar</td>');
          ShowHTML('        </tr>');
        } 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        if (Nvl(f($row,'cd_programa'),'')=='') {
          if ($w_atual>'') {
            if (!(strpos($p_campos,'aprovado')===false) || !(strpos($p_campos,'saldo')===false) || !(strpos($p_campos,'empenhado')===false) || !(strpos($p_campos,'liquidado')===false) || !(strpos($p_campos,'liquidar')===false)) {
              ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
              ShowHTML('        <td colspan='.$w_col.' align="right"><b>Totais do programa <b>'.$w_atual.'</b></td>');
              if (!(strpos($p_campos,'aprovado')===false))    ShowHTML('        <td align="right">'.number_format($w_acao_aprovado,2,',','.').'</td>');
              if (!(strpos($p_campos,'autorizado')===false))  ShowHTML('        <td align="right">'.number_format($w_acao_empenhado,2,',','.').'</td>');
              if (!(strpos($p_campos,'saldo')===false))       ShowHTML('        <td align="right">'.number_format($w_acao_saldo,2,',','.').'</td>');
              if (!(strpos($p_campos,'realizado')===false))   ShowHTML('        <td align="right">'.number_format($w_acao_liquidado,2,',','.').'</td>');
              if (!(strpos($p_campos,'liquidar')===false))    ShowHTML('        <td align="right">'.number_format($w_acao_liquidar,2,',','.').'</td>');
              ShowHTML('      </tr>');
              //ShowHTML '      <tr bgcolor=''' & conTrBgColor & ''' height=5><td colspan=10></td></tr>'
            } 
            $w_acao_aprovado  = 0.00;
            $w_acao_saldo     = 0.00;
            $w_acao_empenhado = 0.00;
            $w_acao_liquidado = 0.00;
            $w_acao_liquidar  = 0.00;
          } 
          ShowHTML('      <tr bgcolor="'.$conTrAlternateBgColor.'" valign="top">');
          ShowHTML('        <td nowrap><b>'.f($row,'cd_unidade').' . '.f($row,'cd_programa').' . '.f($row,'cd_acao').'</td>');
          ShowHTML('        <td><b>'.f($row,'descricao_acao').'</td>');
          if (!(strpos($p_campos,'responsavel')===false)) ShowHTML('        <td>'.Nvl(f($row,'responsavel'),'---').'</td>');
          if (!(strpos($p_campos,'email')===false))       ShowHTML('        <td>'.Nvl(f($row,'email'),'---').'</td>');
          if (!(strpos($p_campos,'telefone')===false))    ShowHTML('        <td>'.Nvl(f($row,'telefone'),'---').'</td>');
          if (!(strpos($p_campos,'orgao')===false))       ShowHTML('        <td>'.Nvl(f($row,'ds_orgao'),'---').'</td>');
          if (!(strpos($p_campos,'aprovado')===false) || !(strpos($p_campos,'saldo')===false) || !(strpos($p_campos,'autorizado')===false) || !(strpos($p_campos,'realizado')===false) || !(strpos($p_campos,'liquidar')===false)) ShowHTML('        <td colspan='.$w_col_word-$w_col.'>&nbsp;</td>');
          $w_atual    = f($row,'chave');
          $w_linha    = $w_linha + 1;
        } else {
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          ShowHTML('        <td nowrap>&nbsp;&nbsp;'.f($row,'cd_unidade').'.'.f($row,'cd_programa').'.'.f($row,'cd_acao').'</td>');
          ShowHTML('        <td>'.f($row,'descricao_acao').'</td>');
          if (!(strpos($p_campos,'responsavel')===false)) ShowHTML('        <td>'.Nvl(f($row,'responsavel'),'---').'</td>');
          if (!(strpos($p_campos,'email')===false))       ShowHTML('        <td>'.Nvl(f($row,'email'),'---').'</td>');
          if (!(strpos($p_campos,'telefone')===false))    ShowHTML('        <td>'.Nvl(f($row,'telefone'),'---').'</td>');
          if (!(strpos($p_campos,'orgao')===false))       ShowHTML('        <td>'.Nvl(f($row,'ds_orgao'),'---').'</td>');
          if (!(strpos($p_campos,'aprovado')===false))    ShowHTML('        <td align="right">'.number_format(Nvl(f($row,'previsao_ano'),0),2,',','.').'</td>');
          if (!(strpos($p_campos,'autorizado')===false))  ShowHTML('        <td align="right">'.number_format(Nvl(f($row,'atual_ano'),0),2,',','.').'</td>');
          if (!(strpos($p_campos,'saldo')===false))       ShowHTML('        <td align="right">'.number_format((f($row,'aprovado')-f($row,'empenhado')),2,',','.').'</td>');
          if (!(strpos($p_campos,'realizado')===false))   ShowHTML('        <td align="right">'.number_format(Nvl(f($row,'real_ano'),0),2,',','.').'</td>');
          if (!(strpos($p_campos,'liquidar')===false))    ShowHTML('        <td align="right">'.number_format((f($row,'empenhado')-f($row,'liquidado')),2,',','.').'</td>');
          $w_linha += 1;
          ShowHTML('</tr>');
          if ($p_metas>'') {
            ShowHTML('      <tr><td><td colspan='.$w_col_word.'><table border=1 width="100%">');
            $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ISACAD');
            $RS2 = db_getSolicList_IS::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),4,
                      null,null,null,null,null,null,null,null,null,null,null,null,
                      null,null,null,null,null,null,null,null,null,null,null,substr(f($row,'chave'),0,4),
                      f($row,'cd_acao'),null,substr(f($row,'chave'),8,4),$w_ano);
            $RS2 = SortArray($RS2,'phpdt_fim','asc','prioridade','asc');
            foreach($RS2 as $row2){$RS2=$row2; break;}
            if (count($RS2)<=0) {
              ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$w_col_word.' align="center"><b>Não foram encontrados registros(metas).</b></td></tr>');
              $w_linha += 1;
            } else {
              $RS3 = db_getSolicMeta_IS::getInstanceOf($dbms,$w_cliente,f($RS2,'sq_siw_solicitacao'),null,'LISTA',null,null,null,null,null,null,null,null,null);
              $RS3 = SortArray($RS3,'ordem','asc');
              if (count($RS3)<=0) {
                // Se não foram selecionados registros, exibe mensagem
                ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$w_col_word.' align="center"><b>Não foram encontrados registros(metas).</b></td></tr>');
                $w_linha = $w_linha + 1;
              } else {
                ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
                ShowHTML('          <td><b>Produto</td>');
                ShowHTML('          <td><b>Meta PPA</td>');
                ShowHTML('          <td><b>Fim previsto</td>');
                ShowHTML('          <td><b>Unidade<br>medida</td>');
                ShowHTML('          <td><b>Quantitativo<br>programado</td>');
                ShowHTML('          <td><b>% Realizado</td>');
                ShowHTML('        </tr>');
                $w_linha += 1;
                $w_cor='';
                foreach($RS3 as $row3) {
                  ShowHTML(MetaLinha(f($RS2,'sq_siw_solicitacao'),f($row3,'sq_meta'),f($row3,'titulo'),$w_tipo_rel,f($row3,'programada'),f($row3,'unidade_medida'),f($row3,'quantidade'),f($row3,'fim_previsto'),f($row3,'perc_conclusao'),'S','PROJETO',Nvl(f($row3,'cd_subacao'),'')));
                  $w_linha += 1;
                } 
              } 
            }
            ShowHTML('        </table>');
          } 
          if ($p_tarefas>'') {
            ShowHTML('      <tr><td><td colspan='.$w_col_word.'><table border=1 width="100%">');
            $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ISACAD');
            $RS2 = db_getSolicList_IS::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),4,
                      null,null,null,null,null,null,null,null,null,null,null,null,
                      null,null,null,null,null,null,null,null,null,null,null,substr(f($row,'chave'),0,4),
                      f($row,'cd_acao'),null,substr(f($row,'chave'),8,4),$w_ano);
            $RS2 = SortArray($RS2,'phpdt_fim','asc','prioridade','asc');
            foreach($RS2 as $row2){$RS2=$row2; break;}
            if (count($RS2)<=0) {
              ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$w_col_word.' align="center"><b>Não foram encontrados registros(tarefas).</b></td></tr>');
              $w_linha += 1;
            } else {       
              $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ISTCAD');
              $RS3 = db_getSolicList_IS::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),4,
                      null,null,null,null,null,null,null,$p_prioridade,null,null,null,null,
                      null,null,null,null,null,null,null,null,null,f($RS2,'sq_siw_solicitacao'),null,
                      null,null,null,null,$w_ano);
              //$RS3 = SortArray($RS3,'phpdt_fim','asc','prioridade','asc');
              if (count($RS3)<=0) {
                // Se não foram selecionados registros, exibe mensagem
                ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$w_col_word.' align="center"><b>Não foram encontrados registros(tarefas).</b></td></tr>');
                $w_linha += 1;
              } else {
                ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
                ShowHTML('          <td><b>Código</td>');
                ShowHTML('          <td><b>Tarefa</td>');
                ShowHTML('          <td><b>Responsável</td>');
                ShowHTML('          <td><b>Parcerias</td>');
                ShowHTML('          <td><b>Fim previsto</td>');
                ShowHTML('          <td><b>Programado</td>');
                ShowHTML('          <td><b>Executado</td>');
                ShowHTML('          <td><b>Fase atual</td>');
                if ($p_prioridade=='')    ShowHTML('<td><b>Prioridade</td>');
                ShowHTML('        </tr>');
                $w_linha += 1;
                foreach($RS3 as $row3) {
                  if (($p_tarefas_atraso>'' && f($row3,'fim')<addDays(time(),-1)) || ($p_tarefas_atraso=='')) {
                    //If w_cor = conTrBgColor or w_cor = '' Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
                    $w_cor=$conTrBgColor;
                    ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
                    ShowHTML('        <td nowrap>');
                    if (f($row3,'concluida')=='N') {
                      if (f($row3,'fim')<addDays(time(),-1)) ShowHTML('           <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">');
                      elseif (f($row3,'aviso_prox_conc')=='S' && (f($row3,'aviso')<=addDays(time(),-1)))    ShowHTML('           <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">');
                      else                                  ShowHTML('           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">');
                    } else {
                      if (f($row3,'fim')<Nvl(f($row3,'fim_real'),f($row3,'fim')))    ShowHTML('           <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">');
                      else                                                        ShowHTML('           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">');
                    } 
                    ShowHTML('        <A class="HL" HREF="'.$w_dir.'tarefas.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row3,'sq_siw_solicitacao').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" TARGET="VisualTarefa" title="Exibe as informações desta tarefa.">'.f($row3,'sq_siw_solicitacao').'&nbsp;</a>');
                    //If Len(Nvl(RS3('assunto'),'-')) > 50 Then w_titulo = Mid(Nvl(RS3('assunto'),'-'),1,50) & '...' Else w_titulo = Nvl(RS3('assunto'),'-') End If
                    ShowHTML('        <td>'.f($row3,'titulo').'</td>');
                    ShowHTML('        <td>'.f($row3,'nm_solic').'</td>');
                    ShowHTML('        <td>'.Nvl(f($row3,'proponente'),'---').'</td>');
                    ShowHTML('        <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row3,'fim')),'-').'</td>');
                    ShowHTML('        <td align="right">'.number_format(f($row3,'valor'),2,',','.').'&nbsp;</td>');
                    ShowHTML('        <td align="right">'.number_format(f($row3,'custo_real'),2,',','.').'&nbsp;</td>');
                    ShowHTML('        <td nowrap>'.f($row3,'nm_tramite').'</td>');
                    if ($p_prioridade=='') ShowHTML('<td nowrap>'.RetornaPrioridade(f($row3,'prioridade')).'</td>');
                    ShowHTML('        </td>');
                    ShowHTML('      </tr>');
                    $w_linha += 1;
                  }
                }
              }
            } 
            ShowHTML('        </table>');
          } 
          if ($p_sq_unidade_resp>'') {
            ShowHTML('      <tr><td><td colspan='.$w_col_word.'><table border=1 width="100%">');
            $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ISACAD');
            $RS2 = db_getSolicList_IS::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),4,
                      null,null,null,null,null,null,null,null,null,null,null,null,
                      null,null,null,null,null,null,null,null,null,null,null,substr(f($row,'chave'),0,4),
                      f($row,'acao'),null,substr(f($row,'chave'),8,4),$w_ano);
            $RS2 = SortArray($RS2,'phpdt_fim','asc','prioridade','asc');
            if (count($RS2)<=0) {
              $w_linha += 1;
              ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="left"><b>Não foi informado a área de planejamento.</b></td></tr>');
            } else {
              ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
              ShowHTML('          <td align="left"><b>Área planejamento</td>');
              ShowHTML('        </tr>');
              $w_linha=$w_linha+1;
              foreach($RS2 as $row2) {
                ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
                ShowHTML('          <td align="left"><b>'.f($row2,'nm_unidade_resp').'</td>');
                ShowHTML('        </tr>');
                $w_linha += 1;
              }
            } 
            ShowHTML('        </table>');
          } 
        } 
        ShowHTML('      </tr>');
        $w_acao_aprovado  = $w_acao_aprovado+Nvl(f($row,'previsao_ano'),0);
        $w_acao_saldo     = $w_acao_saldo+f($row,'aprovado')-f($row,'empenhado');
        $w_acao_empenhado = $w_acao_empenhado+Nvl(f($row,'atual_ano'),0);
        $w_acao_liquidado = $w_acao_liquidado+Nvl(f($row,'real_ano'),0);
        $w_acao_liquidar  = $w_acao_liquidar+f($row,'empenhado')-f($row,'liquidado');
        $w_tot_aprovado   = $w_tot_aprovado+Nvl(f($row,'previsao_ano'),0);
        $w_tot_saldo      = $w_tot_saldo+f($row,'aprovado')-f($row,'empenhado');
        $w_tot_empenhado  = $w_tot_empenhado+Nvl(f($row,'atual_ano'),0);
        $w_tot_liquidado  = $w_tot_liquidado+Nvl(f($row,'real_ano'),0);
        $w_tot_liquidar   = $w_tot_liquidar+f($row,'empenhado')-f($row,'liquidado');
      } 
      if (!(strpos($p_campos,'aprovado')===false) || !(strpos($p_campos,'saldo')===false) || !(strpos($p_campos,'autorizado')===false) || !(strpos($p_campos,'realizado')===false) || !(strpos($p_campos,'liquidar')===false)){
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" height=5><td colspan='.$w_col.'></td></tr>');
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="center" height=30>');
        ShowHTML('        <td colspan='.$w_col.' align="right"><font size="2"><b>Totais do relatório</font></td>');
        if (!(strpos($p_campos,'aprovado')===false))      ShowHTML('        <td align="right">'.number_format($w_tot_aprovado,2,',','.').'</td>');
        if (!(strpos($p_campos,'autorizado')===false))    ShowHTML('        <td align="right">'.number_format($w_tot_empenhado,2,',','.').'</td>');
        if (!(strpos($p_campos,'saldo')===false))         ShowHTML('        <td align="right">'.number_format($w_tot_saldo,2,',','.').'</td>');
        if (!(strpos($p_campos,'realizado')===false))     ShowHTML('        <td align="right">'.number_format($w_tot_liquidado,2,',','.').'</td>');
        if (!(strpos($p_campos,'liquidar')===false))      ShowHTML('        <td align="right">'.number_format($w_tot_liquidar,2,',','.').'</td>');
        ShowHTML('      </tr>');
        $w_linha += 1;
      }
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Acao',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="p_opcao_ant" value="'.$p_opcao_ant.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr>');
    SelecaoOpcaoEstrat('<u>O</u>pção estratégica:','O',null,$p_opcao,null,'p_opcao','ATIVO','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_macro\'; document.Form.target=\'\'; document.Form.O.value=\'P\'; document.Form.submit();"');
    ShowHTML('      <tr>');
    SelecaoMacroObjetivo('<u>M</u>acro objetivo:','M',null,$p_macro,$p_opcao,'p_macro','ATIVO','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_cd_programa\'; document.Form.target=\'\'; document.Form.O.value=\'P\'; document.Form.submit();"');
    ShowHTML('      <tr>');
    SelecaoProgramaPPA('<u>P</u>rograma PPA:','P',null,$w_cliente,$w_ano,$p_cd_programa,'p_cd_programa','RELATORIO','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_codigo\'; document.Form.target=\'\'; document.Form.O.value=\'P\'; document.Form.submit();"',$w_menu,$p_macro,$p_opcao);
    ShowHTML('      <tr>');
    SelecaoAcaoPPA('<u>A</u>ção PPA:','A',null,$w_cliente,$w_ano,$p_cd_programa,null,null,null,'p_codigo',null,null,null,$w_menu,$p_macro,$p_opcao);
    ShowHTML('      <tr><td colspan=3><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    SelecaoPrioridade('<u>P</u>rioridade das tarefas:','P','Informe a prioridade da tarefa.',$p_prioridade,null,'p_prioridade',null,null);
    ShowHTML('          <td><b>Exibir somente tarefas em atraso?</b><br><input '.$w_Disabled.' type="radio" name="p_tarefas_atraso" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_tarefas_atraso" value="" checked> Não');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td colspan=3><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('          <td><b>Selecionada MP?</b><br>');
    if ($p_selecionada_mp=='S')     ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecionada_mp" value="S" checked> Sim <input '.$w_Disabled.' type="radio" name="'.$p_selecionada_mp.'" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecionada_mp" value=""> Independe');
    elseif ($p_selecionada_mp=='N') ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecionada_mp" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecionada_mp" value="N" checked> Não <input '.$w_Disabled.' type="radio" name="p_selecionada_mp" value=""> Independe');
    else                            ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecionada_mp" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecionada_mp" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecionada_mp" value="" checked> Independe');
    ShowHTML('          <td><b>Selecionada SE/SEPPIR?</b><br>');
    if ($p_selecionada_se=='S')       ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecionada_se" value="S" checked> Sim <input '.$w_Disabled.' type="radio" name="'.$p_selecionada_se.'" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecionada_se" value=""> Independe');
    elseif ($p_selecionada_se=='N')   ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecionada_se" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecionada_se" value="N" checked> Não <input '.$w_Disabled.' type="radio" name="p_selecionada_se" value=""> Independe');
    else                              ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecionada_se" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecionada_se" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecionada_se" value="" checked> Independe');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td colspan=3><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('      <tr><td colspan=2><b>Campos a serem exibidos');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="p_campos[]" value="responsavel"> Responsável</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="p_campos[]" value="aprovado"> Aprovado</td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="p_campos[]" value="email"> e-Mail</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="p_campos[]" value="autorizado"> Autorizado</td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="p_campos[]" value="telefone"> Telefone</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="p_campos[]" value="realizado"> Realizado</td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="p_campos[]" value="orgao"> Órgão</td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_marca_campos" value="" onClick="javascript:MarcaTodosCampos();" TITLE="Marca todos os itens da relação"> Todos</td>');
    ShowHTML('      <tr><td colspan=2><b>Blocos adicionais');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="p_metas" value="metas"> Metas físicas</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="p_tarefas" value="tarefas"> Tarefas</td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="p_sq_unidade_resp" value="unidade"> Área planejamento</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_marca_bloco" value="" onClick="javascript:MarcaTodosBloco();" TITLE="Marca todos os itens da relação"> Todos</td>');
    ShowHTML('     </table>');
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
  if ($w_tipo_rel!='WORD') Rodape();
} 
// =========================================================================
// Relatório da tabela de planos/projetos especificos
// -------------------------------------------------------------------------
function Rel_Projeto(){
  extract($GLOBALS);
  $w_chave          = $_REQUEST['w_chave'];
  $w_tipo_rel       = strtoupper(trim($_REQUEST['w_tipo_rel']));
  $p_sq_isprojeto   = strtoupper(trim($_REQUEST['p_sq_isprojeto']));
  $p_prioridade     = strtoupper(trim($_REQUEST['p_prioridade']));
  $p_sq_unidade_resp= $_REQUEST['p_sq_unidade_resp'];
  $p_selecao_mp     = strtoupper(trim($_REQUEST['p_selecao_mp']));
  $p_selecao_se     = strtoupper(trim($_REQUEST['p_selecao_se']));
  $p_tarefas_atraso = strtoupper(trim($_REQUEST['p_tarefas_atraso']));
  if(is_array($_REQUEST['p_campos'])) $p_campos = explodeArray($_REQUEST['p_campos']);
  else                                $p_campos = $_REQUEST['p_campos'];
  $p_tarefas        = $_REQUEST['p_tarefas'];
  $p_metas          = $_REQUEST['p_metas'];
  $p_siw_solic      = $_REQUEST['p_siw_solic'];
  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'') $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
    // Recupera todos os registros para a listagem     
    $RS = db_getProjeto_IS::getInstanceOf($dbms,$p_sq_isprojeto,$w_cliente,null,null,null,null,null,null,null,null,$p_selecao_mp,$p_selecao_se,null,$p_siw_solic);
    if (Nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {
      $RS = SortArray($RS,'ordem','asc');
    }
  } 
  if ($w_tipo_rel=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_pag=1;
    $w_linha=5;
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
    ShowHTML('Relatório Analítico - Planos interno '.$w_ano);
    ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
    ShowHTML('<TR><TD COLSPAN="2" ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Página: '.$w_pag.'</B></TD></TR>');
    ShowHTML('</TD></TR>');
    ShowHTML('</FONT></B></TD></TR></TABLE>');
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Relatório Analítico Planos internos '.$w_ano.'</TITLE>');
    if (!(strpos('P',$O)===false)) {
      ScriptOpen('JavaScript');
      ValidateOpen('Validacao');
      Validate('p_sq_isprojeto','Programa interno','SELECT','','1','18','','1');
      Validate('p_siw_solic','Ações específicas','SELECT','','1','18','','1');
      ShowHTML('  if (theForm.p_tarefas.checked == false) {');
      ShowHTML('     theForm.p_prioridade.value = \'\';');
      ShowHTML('  }');
      ShowHTML('  if (theForm.p_tarefas.checked == false && theForm.p_tarefas_atraso[0].checked == true) {');
      ShowHTML('      alert(\'Para exibir somente as tarefas em atraso,\n\n é preciso escolher a exibição da tarefa\');');
      ShowHTML('      return (false);');
      ShowHTML('  }');
      ValidateClose();
      ShowHTML('  function MarcaTodosCampos() {');
      ShowHTML('    if (document.Form.w_marca_campos.checked==true) ');
      ShowHTML('       for (i=0; i < 3; i++) {');
      ShowHTML('          document.Form["p_campos[]"][i].checked=true;');
      ShowHTML('    } else { ');
      ShowHTML('       for (i=0; i < 3; i++) {');
      ShowHTML('          document.Form["p_campos[]"][i].checked=false;');
      ShowHTML('       } ');
      ShowHTML('    } ');
      ShowHTML('  }');
      ShowHTML('  function MarcaTodosBloco() {');
      ShowHTML('    if (document.Form.w_marca_bloco.checked==true) {');
      ShowHTML('         document.Form.p_tarefas.checked=true;');
      ShowHTML('         document.Form.p_metas.checked=true;');
      ShowHTML('         document.Form.p_sq_unidade_resp.checked=true;');
      ShowHTML('    } else { ');
      ShowHTML('         document.Form.p_tarefas.checked=false;');
      ShowHTML('         document.Form.p_metas.checked=false;');
      ShowHTML('         document.Form.p_sq_unidade_resp.checked=false;');
      ShowHTML('    } ');
      ShowHTML('  }');
      ScriptClose();
    } 
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($O=='L') {
      BodyOpenClean(null);
      ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
      ShowHTML('Relatório Analítico - Planos internos '.$w_ano);
      ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
      ShowHTML('&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      ShowHTML('&nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&w_chave='.$w_chave.'&w_tipo_rel=word&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
      ShowHTML('</TD></TR>');
      ShowHTML('</FONT></B></TD></TR></TABLE>');
    } else {
      BodyOpen('onLoad=\'document.Form.p_sq_isprojeto.focus()\';');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    } 
    ShowHTML('<HR>');
  } 
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    $w_col      = 1;
    $w_col_word = 1;
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    $w_filtro='';
    if ($p_prioridade>'')       $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Prioridade<td>[<b>'.RetornaPrioridade($p_prioridade).'</b>]';
    if ($p_selecao_mp>'')       $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Selecionada MP<td>[<b>'.RetornaSimNao($p_selecao_mp).'</b>]';
    if ($p_selecao_se>'')       $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Selecionada Relevante<td>[<b>'.RetornaSimNao($p_selecao_se).'</b>]';
    if ($p_tarefas_atraso>'')   $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Tarefas em atraso&nbsp;[<b>'.RetornaSimNao($p_tarefas_atraso).'</b>]&nbsp;';
    ShowHTML('<tr><td align="left" colspan=3>');
    if ($w_filtro>'') ShowHTML('<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></td></tr></table></td></tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" align="center">');
    if ($w_tipo_rel=='WORD') ShowHTML('        <td><b>Nome</td>');
    else                     ShowHTML('        <td><b>'.LinkOrdena('Nome','nome').'</td>');
    if (!(strpos($p_campos,'responsavel')===false)) {
      if ($w_tipo_rel=='WORD') ShowHTML('     <td><b>Responsável</td>');
      else                     ShowHTML('     <td><b>'.LinkOrdena('Responsável','responsavel').'</td>');
      $w_col      += 1;
      $w_col_word += 1;
    }
    if (!(strpos($p_campos,'email')===false)) {
      if ($w_tipo_rel=='WORD') ShowHTML('     <td><b>e-Mail</td>');
      else                     ShowHTML('     <td><b>'.LinkOrdena('e-Mail','email').'</td>');
      $w_col        += 1;
      $w_col_word   += 1;
    } 
    if (!(strpos($p_campos,'telefone')===false)) {
      if ($w_tipo_rel=='WORD') ShowHTML('     <td><b>Telefone</td>');
      else                     ShowHTML('     <td><b>'.LinkOrdena('Telefone','telefone').'</td>');
      $w_col      += 1;
      $w_col_word += 1;
    } 
    ShowHTML('      </tr>');
    $w_linha += 1;
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('  <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$w_col_word.' align="center"><b>Não foram encontrados registros.</b></td></tr>');
      $w_linha += 1;
    } else {
      $w_atual            = 0;
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        if ($w_linha>20 && $w_tipo_rel=='WORD') {
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
          ShowHTML('</table>');
          ShowHTML('</center></div>');
          ShowHTML('    <br style="page-break-after:always">');
          $w_linha=5;
          $w_pag+= 1;
          CabecalhoWordOR('Iniciativa Prioritária',$w_pag,$w_logo);
          ShowHTML('<div align=center><center>');
          ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
          ShowHTML('<tr><td align="center" colspan=3>');
          ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" align="center">');
          ShowHTML('        <td><b>Nome</td>');
          if (!(strpos($p_campos,'responsavel')===false))   ShowHTML('     <td><b>Responsável</td>');
          if (!(strpos($p_campos,'email')===false))         ShowHTML('     <td><b>e-Mail</td>');
          if (!(strpos($p_campos,'telefone')===false))      ShowHTML('     <td><b>Telefone</td>');
          ShowHTML('      </tr>');
          $w_linha+=1;
        } 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        if ($w_atual!=f($row,'chave')) {
          ShowHTML('      <tr bgcolor="'.$conTrAlternateBgColor.'" valign="top">');
          ShowHTML('        <td><b>Programa interno: '.f($row,'nome').'</td>');
          if (!(strpos($p_campos,'responsavel')===false)) ShowHTML('        <td>'.Nvl(f($row,'responsavel'),'---').'</td>');
          if (!(strpos($p_campos,'email')===false))       ShowHTML('        <td>'.Nvl(f($row,'email'),'---').'</td>');
          if (!(strpos($p_campos,'telefone')===false))    ShowHTML('        <td>'.Nvl(f($row,'telefone'),'---').'</td>');
          ShowHTML('         </tr>');
          $w_linha += 1;
        } 
        $w_sq_siw_solicitacao = Nvl(f($row,'sq_siw_solicitacao'),'');
        $w_atual              = f($row,'chave');
        if ($w_sq_siw_solicitacao>'') {
          ShowHTML('         <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          if ($w_tipo_rel=='WORD') ShowHTML('        <td colspan='.$w_col.'><b>Ação:</b>'.f($row,'titulo').'</td>');
          else                     ShowHTML('        <td colspan='.$w_col.'><b>Ação:</b> <A class="HL" HREF="'.$w_dir.'acao.php?par=Visual&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" TARGET="VisualAcao" title="Exibe as informações da ação.">'.f($row,'titulo').'</a></td>');
          $w_linha += 1;
          ShowHTML('         </tr>');
        }
        if ($w_sq_siw_solicitacao>'') {
          if ($p_metas>'') {
            ShowHTML('   <tr><td colspan='.$w_col_word.'>');
            ShowHTML('     <table border=1 width="100%">');
            $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ISACAD');
            $RS2 = db_getSolicList_IS::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),4,
                      null,null,null,null,null,null,null,null,null,null,$w_sq_siw_solicitacao,null,
                      null,null,null,null,null,null,null,null,null,null,null,null,
                      null,f($row,'chave'),null,$w_ano);
            $RS2 = SortArray($RS2,'phpdt_fim','asc','prioridade','asc');
            if (count($RS2)<=0) {
              ShowHTML('    <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$w_col_word.' align="center"><b>Não foram encontrados registros(metas).</b></td></tr>');
              $w_linha += 1;
            } else {
              foreach($RS2 as $row2){$RS2=$row2; break;}
              $RS3 = db_getSolicMeta_IS::getInstanceOf($dbms,$w_cliente,f($RS2,'sq_siw_solicitacao'),null,'LSTNULL',null,null,null,null,null,null,null,null,null);
              $RS3 = SortArray($RS3,'ordem','asc');
              if (count($RS3)<=0) {
                // Se não foram selecionados registros, exibe mensagem
                ShowHTML(' <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$w_col_word.' align="center"><b>Não foram encontrados registros(metas).</b></td></tr>');
                $w_linha += 1;
              } else {
                ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
                ShowHTML('          <td><b>Produto</td>');
                ShowHTML('          <td><b>Meta PPA</td>');
                ShowHTML('          <td><b>Fim previsto</td>');
                ShowHTML('          <td><b>Unidade<br>medida</td>');
                ShowHTML('          <td><b>Quantitativo<br>programado</td>');
                ShowHTML('          <td><b>% Realizado</td>');
                ShowHTML('        </tr>');
                $w_linha += 1;
                foreach($RS3 as $row3) {
                  ShowHTML(MetaLinha(f($RS2,'sq_siw_solicitacao'),f($row3,'sq_meta'),f($row3,'titulo'),$w_tipo_rel,f($row3,'programada'),f($row3,'unidade_medida'),f($row3,'quantidade'),f($row3,'fim_previsto'),f($row3,'perc_conclusao'),'S','PROJETO',Nvl(f($RS2,'cd_subacao'),'')));
                  $w_linha += 1;
                } 
              }
            }
            ShowHTML('     </table>');
          } 
          if ($p_tarefas>'') {
            ShowHTML('     <tr><td colspan='.$w_col_word.'>');
            ShowHTML('       <table border=1 width="100%">');
            $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ISACAD');
            $RS2 = db_getSolicList_IS::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),4,
                      null,null,null,null,null,null,null,null,null,null,$w_sq_siw_solicitacao,null,
                      null,null,null,null,null,null,null,null,null,null,null,null,
                      null,f($row,'chave'),null,$w_ano);
            $RS2 = SortArray($RS2,'phpdt_fim','asc','prioridade','asc');
            if (count($RS2)<=0) {
              ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$w_col_word.' align="center"><b>Não foram encontrados registros(tarefas).</b></td></tr>');
              $w_linha=$w_linha+1;
            } else {
              foreach($RS2 as $row2){$RS2=$row2; break;}
              $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ISTCAD');
              $RS3 = db_getSolicList_IS::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),4,
                      null,null,null,null,null,null,null,$p_prioridade,null,null,null,null,
                      null,null,null,null,null,null,null,null,null,f($RS2,'sq_siw_solicitacao'),null,
                      null,null,null,null,$w_ano);
              $RS3 = SortArray($RS3,'phpdt_fim','asc','prioridade','asc');
              if (count($RS3)<=0) {
                // Se não foram selecionados registros, exibe mensagem
                ShowHTML('   <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$w_col_word.' align="center"><b>Não foram encontrados registros(tarefas).</b></td></tr>');
                $w_linha=$w_linha+1;
              } else {
                ShowHTML('   <tr bgcolor="'.$conTrBgColor.'" align="center">');
                ShowHTML('     <td><b>Código</td>');
                ShowHTML('     <td><b>Tarefa</td>');
                ShowHTML('     <td><b>Responsável</td>');
                ShowHTML('     <td><b>Parcerias</td>');
                ShowHTML('     <td><b>Fim previsto</td>');
                ShowHTML('     <td><b>Programado</td>');
                ShowHTML('     <td><b>Executado</td>');
                ShowHTML('     <td><b>Fase atual</td>');
                if ($p_prioridade=='')    ShowHTML('<td><b>Prioridade</td>');
                ShowHTML('   </tr>');
                $w_linha += 1;
                foreach($RS3 as $row3) {
                  if (($p_tarefas_atraso>'' && f($row3,'fim')<addDays(time(),-1)) || ($p_atraso=='')) {
                    //If w_cor = conTrBgColor or w_cor = '' Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
                    $w_cor=$conTrBgColor;
                    ShowHTML(' <tr bgcolor="'.$w_cor.'" valign="top">');
                    ShowHTML('   <td nowrap>');
                    if (f($row3,'concluida')=='N') {
                      if (f($row3,'fim')<addDays(time(),-1)) 
                        ShowHTML('           <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">');
                      elseif (f($row3,'aviso_prox_conc')=='S' && (f($row3,'aviso')<=addDays(time(),-1))) 
                        ShowHTML('           <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">');
                      else
                        ShowHTML('           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">');
                    } else {
                      if (f($row3,'fim')<Nvl(f($row3,'fim_real'),f($row3,'fim')))
                        ShowHTML('           <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">');
                      else
                        ShowHTML('           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">');
                    } 
                    if ($w_tipo_rel=='WORD')  ShowHTML(''.f($row3,'sq_siw_solicitacao').'&nbsp;');
                    else                      ShowHTML('        <A class="HL" HREF="'.$w_dir.'tarefas.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row3,'sq_siw_solicitacao').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" TARGET="VisualTarefa" title="Exibe as informações desta tarefa.">'.f($row3,'sq_siw_solicitacao').'&nbsp;</a>');
                    ShowHTML('   </td>');
                    ShowHTML('   <td>'.Nvl(f($row3,'titulo'),'-').'</td>');
                    ShowHTML('   <td>'.f($row3,'nm_solic').'</td>');
                    ShowHTML('   <td>'.Nvl(f($row3,'proponente'),'---').'</td>');
                    ShowHTML('   <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row3,'fim')),'-').'</td>');
                    ShowHTML('   <td align="right">'.number_format(f($row3,'valor'),2,',','.').'&nbsp;</td>');
                    ShowHTML('   <td align="right">'.number_format(f($row3,'custo_real'),2,',','.').'&nbsp;</td>');
                    ShowHTML('   <td nowrap>'.f($row3,'nm_tramite').'</td>');
                    if ($p_prioridade=='') ShowHTML('<td nowrap>'.RetornaPrioridade(f($row3,'prioridade')).'</td>');
                    ShowHTML(' </tr>');
                    $w_linha += 1;
                  } 
                }
              } 
            }
            ShowHTML('       </table>');
          } 
          if ($p_sq_unidade_resp>'') {
            ShowHTML('     <tr><td colspan='.$w_col_word.'>');
            ShowHTML('       <table border=1 width="100%">');
            $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ISACAD');
            $RS2 = db_getSolicList_IS::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),4,
                      null,null,null,null,null,null,null,null,null,null,null,null,f($row,'sq_siw_solicitacao'),null,
                      null,null,null,null,null,null,null,null,null,null,null,null,null,null,
                      null,f($row,'chave'),null,$w_ano);
            $RS2 = SortArray($RS2,'phpdt_fim','asc','prioridade','asc');
            if (count($RS2)<=0) {
               ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="left"><b>Não foi informado a área planejamento.</b></td></tr>');
              $w_linha += 1;
            } else {
              ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" align="center">');
              ShowHTML('        <td align="left"><b>Área planejamento</td>');
              ShowHTML('      </tr>');
              $w_linha += 1;
              foreach($RS2 as $row2) {
                ShowHTML('   <tr bgcolor="'.$conTrBgColor.'" align="center">');
                ShowHTML('     <td align="left"><b>'.f($row2,'nm_unidade_resp').'</td>');
                ShowHTML('   </tr>');
                $w_linha += 1;
              } 
            } 
            ShowHTML('       </table>');
          } 
        } else {
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          ShowHTML('          <td colspan='.$w_col_word.' align="center"><b>Não foram encontrados registros.</td>');
          $w_linha += 1;
        } 
        ShowHTML('           </tr>');
      } 
    } 
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('    </table>');
    ShowHTML('</center></div>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Tabela Projetos',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');    
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('          <tr>');
    SelecaoIsProjeto('<u>P</u>rograma interno:','P',null,$p_sq_isprojeto,null,'p_sq_isprojeto',null,'onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_sq_isprojeto\'; document.Form.target=\'\'; document.Form.O.value=\'P\'; document.Form.submit();"');
    ShowHTML('          </tr>');
    ShowHTML('          <tr>');
    SelecaoAcao('<u>A</u>ção:','A',null,$w_cliente,$w_ano,null,null,null,null,'p_siw_solic','PROJETO',null,$p_sq_isprojeto);
    ShowHTML('          </tr>');
    ShowHTML('      <tr><td colspan=3><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    SelecaoPrioridade('<u>P</u>rioridade das tarefas:','P','Informe a prioridade da tarefa.',$p_prioridade,null,'p_prioridade',null,null);
    ShowHTML('          <td><b>Exibir somente tarefas em atraso?</b><br><input '.$w_Disabled.' type="radio" name="p_tarefas_atraso" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_tarefas_atraso" value="" checked> Não');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td colspan=3><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('          <td><b>Selecionada SPI/MP?</b><br>');
    if ($p_selecao_mp=='S')     ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value="S" checked> Sim <input '.$w_Disabled.' type="radio" name="'.$p_selecao_mp.'" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value=""> Independe');
    elseif ($p_selecao_mp=='N') ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value="N" checked> Não <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value=""> Independe');
    else                        ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value="" checked> Independe');
    ShowHTML('          <td><b>Selecionada SE/SEPPIR?</b><br>');
    if ($p_selecao_se=='S')     ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecao_se" value="S" checked> Sim <input '.$w_Disabled.' type="radio" name="'.$p_selecao_se.'" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecao_se" value=""> Independe');
    elseif ($p_selecao_se=='N') ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecao_se" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecao_se" value="N" checked> Não <input '.$w_Disabled.' type="radio" name="p_selecao_se" value=""> Independe');
    else                        ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecao_se" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecao_se" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecao_se" value="" checked> Independe');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td colspan=3><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('      <tr><td colspan=2><b>Campos a serem exibidos');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="p_campos[]" value="responsavel"> Responsável</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="p_campos[]" value="email"> e-Mail</td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="p_campos[]" value="telefone"> Telefone</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_marca_campos" value="" onClick="javascript:MarcaTodosCampos();" TITLE="Marca todos os itens da relação"> Todos</td>');
    ShowHTML('      <tr><td colspan=2><b>Blocos adicionais');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="p_metas" value="metas"> Metas físicas</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="p_tarefas" value="tarefas"> Tarefas</td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="p_sq_unidade_resp" value="unidade"> Área planejamento</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_marca_bloco" value="" onClick="javascript:MarcaTodosBloco();" TITLE="Marca todos os itens da relação"> Todos</td>');
    ShowHTML('     </table>');
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
  if ($w_tipo_rel!='WORD') Rodape();
} 
// =========================================================================
// Relatório dos programas PPA
// -------------------------------------------------------------------------
function Rel_Programa() {
  extract($GLOBALS);
  $w_chave          = $_REQUEST['w_chave'];
  $w_troca          = $_REQUEST['w_troca'];
  $w_tipo_rel       = $_REQUEST['w_tipo_rel'];
  $p_cd_programa    = $_REQUEST['p_cd_programa'];
  $p_sq_unidade_resp= $_REQUEST['p_sq_unidade_resp'];
  $p_selecao_mp     = $_REQUEST['p_selecao_mp'];
  $p_selecao_se     = $_REQUEST['p_selecao_se'];
  if(is_array($_REQUEST['p_campos'])) $p_campos = explodeArray($_REQUEST['p_campos']);
  else                                $p_campos = $_REQUEST['p_campos'];
  $p_indicador      = $_REQUEST['p_indicador'];
  $p_opcao          = $_REQUEST['p_opcao'];
  $p_opcao_ant      = $_REQUEST['p_opcao'];
  if($_REQUEST['p_opcao_ant']!=$_REQUEST['p_opcao']) {
    $p_macro        = '';
  } else {
    $p_macro        = $_REQUEST['p_macro'];
  }  
  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'') $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
    // Recupera todos os registros para a listagem
    $RS = db_getProgramaPPA_IS::getInstanceOf($dbms,$p_cd_programa,$w_cliente,$w_ano,null,null,$p_macro,$p_opcao);
    if (Nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {
      $RS = SortArray($RS,'ds_programa','asc');
    } 
  } 
  if ($w_tipo_rel=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_pag=1;
    $w_linha=5;
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
    ShowHTML('Relatório Analítico - Programas PPA 2004 - 2007 Exercício '.$w_ano);
    ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
    ShowHTML('<TR><TD COLSPAN="2" ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Página: '.$w_pag.'</B></TD></TR>');
    ShowHTML('</TD></TR>');
    ShowHTML('</FONT></B></TD></TR></TABLE>');
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Relatório Analítico - Programas PPA 2004 - 2007 Exercício '.$w_ano.'</TITLE>');
    if (!(strpos('P',$O)===false)) {
      ScriptOpen('JavaScript');
      ValidateOpen('Validacao');
      Validate('p_cd_programa','Programa','SELECT','','1','18','','1');
      ValidateClose();
      ShowHTML('  function MarcaTodosCampos() {');
      ShowHTML('    if (document.Form.w_marca_campos.checked==true) ');
      ShowHTML('       for (i=0; i < 4; i++) {');
      ShowHTML('          document.Form["p_campos[]"][i].checked=true;');
      ShowHTML('    } else { ');
      ShowHTML('       for (i=0; i < 4; i++) {');
      ShowHTML('          document.Form["p_campos[]"][i].checked=false;');
      ShowHTML('       } ');
      ShowHTML('    } ');
      ShowHTML('  }');
      ShowHTML('  function MarcaTodosBloco() {');
      ShowHTML('    if (document.Form.w_marca_bloco.checked==true) {');
      ShowHTML('         document.Form.p_indicador.checked=true;');
      ShowHTML('         document.Form.p_sq_unidade_resp.checked=true;');
      ShowHTML('    } else { ');
      ShowHTML('         document.Form.p_indicador.checked=false;');
      ShowHTML('         document.Form.p_sq_unidade_resp.checked=false;');
      ShowHTML('    } ');
      ShowHTML('  }');
      ScriptClose();
    } 
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($O=='L') {
      BodyOpenClean(null);
      ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
      ShowHTML('Relatório Analítico - Programas PPA 2004 - 2007 Exercício '.$w_ano);
      ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
      ShowHTML('&nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&w_chave='.$w_chave.'&w_tipo_rel=word&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
      ShowHTML('&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      ShowHTML('</TD></TR>');
      ShowHTML('</FONT></B></TD></TR></TABLE>');
    } else {
      BodyOpen('onLoad=\'document.Form.p_cd_programa.focus();\'');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    } 
    ShowHTML('<HR>');
  } 
  ShowHTML('<div align=center><center>'); 
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    $w_col      = 2;
    $w_col_word = 2;
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    $w_filtro='';
    if ($p_selecao_mp>'') $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Selecionada SPI/MP<td>[<b>'.$p_selecao_mp.'</b>]';
    if ($p_selecao_se>'') $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Selecionada SE/SEPPIR<td>[<b>'.$p_selecao_se.'</b>]';
    ShowHTML('<tr><td align="left" colspan=2>');
    if ($w_filtro>'') ShowHTML('<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>');
    ShowHTML('    <td align="right" valign="botton"><b>Registros listados: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    if ($w_tipo_rel=='WORD') ShowHTML('          <td><b>Código</td>');
    else                     ShowHTML('     <td><b>'.LinkOrdena('Código','cd_programa').'</td>');
    if ($w_tipo_rel=='WORD') ShowHTML('          <td><b>Nome</td>');
    else                     ShowHTML('     <td><b>'.LinkOrdena('Nome','ds_programa').'</td>');
    if (!(strpos($p_campos,'responsavel')===false)) {
      if ($w_tipo_rel=='WORD')  ShowHTML('          <td><b>Gerente programa</td>');
      else                      ShowHTML('     <td><b>'.LinkOrdena('Gerente programa','nm_gerente_programa').'</td>');
      $w_col        += 1;
      $w_col_word   += 1;
    }
    if (!(strpos($p_campos,'email')===false)) {
      if ($w_tipo_rel=='WORD')  ShowHTML('          <td><b>e-Mail</td>');
      else                      ShowHTML('     <td><b>'.LinkOrdena('e-Mail','em_gerente_programa').'</td>');
      $w_col        += 1;
      $w_col_word   += 1;
    } 
    if (!(strpos($p_campos,'telefone')===false)) {
      if ($w_tipo_rel=='WORD') ShowHTML('          <td><b>Telefone</td>');
      else                     ShowHTML('     <td><b>'.LinkOrdena('Telefone','fn_gerente_programa').'</td>');
      $w_col        += 1;
      $w_col_word   += 1;
    } 
    if (!(strpos($p_campos,'orgao')===false)) {
      if ($w_tipo_rel=='WORD') ShowHTML('          <td><b>Órgão</td>');
      else                     ShowHTML('     <td><b>'.LinkOrdena('Órgao','ds_orgao').'</td>');
      $w_col        += 1;
      $w_col_word   += 1;
    } 
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$w_col.' align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        if ($w_linha>22 && $w_tipo_rel=='WORD') {
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
          ShowHTML('</table>');
          ShowHTML('</center></div>');
          ShowHTML('    <br style="page-break-after:always">');
          $w_linha=5;
          $w_pag=$w_pag+1;
          ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
          ShowHTML('Ações PPA');
          ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
          ShowHTML('<TR><TD COLSPAN="2" ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Página: '.$w_pag.'</B></TD></TR>');
          ShowHTML('</TD></TR>');
          ShowHTML('</FONT></B></TD></TR></TABLE>');
          ShowHTML('<div align=center><center>');
          ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
          // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem    
          ShowHTML('<tr><td align="left" colspan=2>');
          if ($w_filtro>'') ShowHTML('<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>');
          ShowHTML('    <td align="right" valign="botton"><b>Registros listados: '.count($RS));
          ShowHTML('<tr><td align="center" colspan=3>');
          ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
          ShowHTML('          <td><b>Código</td>');
          ShowHTML('          <td><b>Nome</td>');
          if (!(strpos($p_campos,'responsavel')===false)) ShowHTML('          <td><b>Gerente programa</td>');
          if (!(strpos($p_campos,'email')===false))       ShowHTML('          <td><b>e-Mail</td>');
          if (!(strpos($p_campos,'telefone')===false))    ShowHTML('          <td><b>Telefone</td>'); 
          if (!(strpos($p_campos,'orgao')===false))       ShowHTML('          <td><b>Órgão</td>');
          ShowHTML('        </tr>');
        } 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
        ShowHTML('        <td nowrap>'.f($row,'cd_programa').'</td>');
        ShowHTML('        <td>'.f($row,'ds_programa').'</td>');
        if (!(strpos($p_campos,'responsavel')===false)) ShowHTML('        <td>'.Nvl(f($row,'nm_gerente_programa'),'---').'</td>');
        if (!(strpos($p_campos,'email')===false))       ShowHTML('        <td>'.Nvl(f($row,'em_gerente_programa'),'---').'</td>');
        if (!(strpos($p_campos,'telefone')===false))    ShowHTML('        <td>'.Nvl(f($row,'fn_gerente_programa'),'---').'</td>');
        if (!(strpos($p_campos,'orgao')===false))       ShowHTML('        <td>'.Nvl(f($row,'ds_orgao'),'---').'</td>');
        $w_linha += 1;
        ShowHTML('</tr>');
        if ($p_indicador>'') {
          ShowHTML('      <tr><td><td colspan='.$w_col_word.'><table border=1 width="100%">');
          $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ISPCAD');
          $RS2 = db_getSolicList_IS::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),4,
                    null,null,null,null,null,null,null,null,null,null,null,null,
                    null,null,null,null,null,null,null,null,null,null,null,null,
                    f($row,'cd_programa'),null,null,$w_ano);
          $RS2 = SortArray($RS2,'phpdt_fim','asc');
          if (count($RS2)<=0) {
            ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$w_col_word.' align="center"><b>Não foram encontrados registros(indicadores).</b></td></tr>');
            $w_linha += 1;
          } else {
            foreach($RS2 as $row2){$RS2=$row2; break;}
            $RS3 = db_getSolicIndic_IS::getInstanceOf($dbms,f($RS2,'sq_siw_solicitacao'),null,'LISTA',null,null);
            $RS3 = SortArray($RS3,'ordem','asc');
            if (count($RS3)<=0) {
              // Se não foram selecionados registros, exibe mensagem
              ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$w_col_word.' align="center"><b>Não foram encontrados registros(indicadores).</b></td></tr>');
              $w_linha += 1;
            } else {
              ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
              ShowHTML('          <td width="45%"><b>Indicador</td>');
              ShowHTML('          <td width="5%"><b>PPA</td>');
              ShowHTML('          <td width="10%"><b>Índice<br>referência</td>');
              ShowHTML('          <td width="10%"><b>Índice<br>programado</td>');
              ShowHTML('          <td width="10%"><b>Índice<br>apurado</td>');
              ShowHTML('          <td width="10%"><b>Data<br>apuracao</td>');
              ShowHTML('          <td width="10%"><b>Unidade<br>medida</td>');
              ShowHTML('        </tr>');
              $w_linha += 1;
              foreach($RS3 as $row3) {
                ShowHTML(Indicadorlinha(f($RS2,'sq_siw_solicitacao'),f($row3,'sq_indicador'),f($row3,'titulo'),f($row3,'valor_referencia'),f($row3,'quantidade'),f($row3,'valor_apurado'),f($row3,'apuracao_indice'),f($row3,'nm_unidade_medida'),null,'<b>','N','PROJETO',f($row3,'cd_indicador')));
                $w_linha += 1;
              } 
            } 
          } 
          ShowHTML('        </table>');
        } 
        if ($p_sq_unidade_resp>'') {
          ShowHTML('      <tr><td><td colspan='.$w_col_word.'><table border=1 width="100%">');
          $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ISPCAD');
          $RS2 = db_getSolicList_IS::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),4,
                    null,null,null,null,null,null,null,null,null,null,null,null,
                    null,null,null,null,null,null,null,null,null,null,null,null,
                    f($row,'cd_programa'),null,null,$w_ano);
          $RS2 = SortArray($RS2,'phpdt_fim','asc');
          if (count($RS2)<=0) {
            $w_linha += 1;
            ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="left"><b>Não foi informado a Área de planejamento.</b></td></tr>');
          } else {
            ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
            ShowHTML('          <td align="left"><b>Área planejamento<font></td>');
            ShowHTML('        </tr>');
            $w_linha += 1;
            foreach($RS2 as $row2) {
              ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
              ShowHTML('          <td align="left"><b>'.f($row2,'nm_unidade_resp').'</td>');
              ShowHTML('        </tr>');
              $w_linha += 1;
            } 
          } 
          ShowHTML('        </table>');
        } 
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Programa',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="p_opcao_ant" value="'.$p_opcao_ant.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr>');
    SelecaoOpcaoEstrat('<u>O</u>pção estratégica:','O',null,$p_opcao,null,'p_opcao','ATIVO','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_macro\'; document.Form.target=\'\'; document.Form.O.value=\'P\'; document.Form.submit();"');
    ShowHTML('      <tr>');
    SelecaoMacroObjetivo('<u>M</u>acro objetivo:','M',null,$p_macro,$p_opcao,'p_macro','ATIVO','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_cd_programa\'; document.Form.target=\'\'; document.Form.O.value=\'P\'; document.Form.submit();"');
    ShowHTML('      <tr>');
    SelecaoProgramaPPA('<u>P</u>rograma PPA:','P',null,$w_cliente,$w_ano,$p_cd_programa,'p_cd_programa','RELATORIO',null,$w_menu,$p_macro,$p_opcao);
    ShowHTML('      <tr><td colspan=3><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('          <td><b>Selecionada pela SPI/MP?</b><br>');
    if ($p_selecao_mp=='S')     ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value="S" checked> Sim <input '.$w_Disabled.' type="radio" name="'.$p_selecao_mp.'" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value=""> Independe');
    elseif ($p_selecao_mp=='N') ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value="N" checked> Não <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value=""> Independe');
    else                        ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value="" checked> Independe');
    ShowHTML('          <td><b>Selecionada pela SE/SEPPIR?</b><br>');
    if ($p_selecao_se=='S')     ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecao_se" value="S" checked> Sim <input '.$w_Disabled.' type="radio" name="'.$p_selecao_se.'" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecao_se" value=""> Independe');
    elseif ($p_selecao_se=='N') ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecao_se" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecao_se" value="N" checked> Não <input '.$w_Disabled.' type="radio" name="p_selecao_se" value=""> Independe');
    else                        ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecao_se" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecao_se" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecao_se" value="" checked> Independe');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td colspan=3><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('      <tr><td colspan=2><b>Campos a serem exibidos');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="p_campos[]" value="responsavel"> Gerente programa</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="p_campos[]" value="email"> e-Mail</td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="p_campos[]" value="telefone"> Telefone</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="p_campos[]" value="orgao"> Órgão</td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_marca_campos" value="" onClick="javascript:MarcaTodosCampos();" TITLE="Marca todos os itens da relação"> Todos</td>');
    ShowHTML('      <tr><td colspan=2><b>Blocos adicionais');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="p_indicador" value="Indicador"> Indicadores</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="p_sq_unidade_resp" value="unidade"> Área planejamento</td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_marca_bloco" value="" onClick="javascript:MarcaTodosBloco();" TITLE="Marca todos os itens da relação"> Todos</td>');
    ShowHTML('     </table>');
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
  if ($w_tipo_rel!='WORD') Rodape();
} 
// =========================================================================
// Relatório Sintético de Programas Internos
// -------------------------------------------------------------------------
function Rel_Sintetico_PR() {
  extract($GLOBALS);
  $w_chave          = $_REQUEST['w_chave'];
  $w_tipo_rel       = strtoupper(trim($_REQUEST['w_tipo_rel']));
  $p_sq_isprojeto   = $_REQUEST['p_sq_isprojeto'];
  $p_siw_solic      = $_REQUEST['p_siw_solic'];
  $p_prioridade     = strtoupper(trim($_REQUEST['p_prioridade']));
  $p_sq_unidade_resp= strtoupper(trim($_REQUEST['p_sq_unidade_resp']));
  $p_selecao_mp     = strtoupper(trim($_REQUEST['p_selecao_mp']));
  $p_selecao_se     = strtoupper(trim($_REQUEST['p_selecao_se']));
  $p_programada     = strtoupper(trim($_REQUEST['p_programada']));
  $p_exequivel      = strtoupper(trim($_REQUEST['p_exequivel']));
  $p_fim_previsto   = strtoupper(trim($_REQUEST['p_fim_previsto']));
  $p_atraso         = strtoupper(trim($_REQUEST['p_atraso']));
  $p_tarefas_atraso = strtoupper(trim($_REQUEST['p_tarefas_atraso']));
  $w_cont = 0;
  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'') $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
    // Recupera todos os registros para a listagem
    $RS = db_getProjeto_IS::getInstanceOf($dbms,$p_sq_isprojeto,$w_cliente,null,null,null,null,null,null,null,null,$p_selecao_mp,$p_selecao_se,null,$p_siw_solic);
    $RS = SortArray($RS,'ordem','asc');
  } 
  if ($w_tipo_rel=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_pag=1;
    $w_linha=8;
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
    ShowHTML('Relatório Sintético - Planos '.$w_ano);
    ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
    ShowHTML('<TR><TD COLSPAN="2" ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Página: '.$w_pag.'</B></TD></TR>');
    ShowHTML('</TD></TR>');
    ShowHTML('</FONT></B></TD></TR></TABLE>');
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Relatório Sintético - Planos '.$w_ano.'</TITLE>');
    if (!(strpos('P',$O)===false)) {
      ScriptOpen('JavaScript');
      ValidateOpen('Validacao');
      Validate('p_sq_isprojeto','Programa interno','SELECT','','1','18','','1');
      Validate('p_siw_solic','Ação','SELECT','','1','18','','1');
      ValidateClose();
      ScriptClose();
    } 
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($O=='L') {
      BodyOpenClean(null);
      ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
      ShowHTML('Relatório Sintético - Planos '.$w_ano);
      ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
      ShowHTML('&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      ShowHTML('&nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&w_chave='.$w_chave.'&w_tipo_rel=word&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
      ShowHTML('</TD></TR>');
      ShowHTML('</FONT></B></TD></TR></TABLE>');
    } else {
      BodyOpen('onLoad=\'document.Form.p_sq_isprojeto.focus()\';');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    } 
    ShowHTML('<HR>');
  } 
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    $w_filtro='<tr valign="top">';
    if ($p_prioridade>'')   $w_filtro=$w_filtro.'<td>Prioridade&nbsp;[<b>'.RetornaPrioridade($p_prioridade).'</b>]&nbsp;';
    if ($p_selecao_mp>'')   $w_filtro=$w_filtro.'<td>Selecionada MP&nbsp;[<b>'.$p_selecao_mp.'</b>]&nbsp;';
    if ($p_selecao_se>'')   $w_filtro=$w_filtro.'<td>Selecionada Relevante&nbsp;[<b>'.$p_selecao_se.'</b>]&nbsp;';
    if ($p_programada>'')   $w_filtro=$w_filtro.'<td>Meta PPA&nbsp;[<b>'.$p_programada.'</b>]&nbsp;';
    if ($p_exequivel>'')    $w_filtro=$w_filtro.'<td>Meta será cumprida&nbsp;[<b>'.$p_exequivel.'</b>]&nbsp;';
    if ($p_fim_previsto>'') $w_filtro=$w_filtro.'<td>Metas em atraso&nbsp;[<b>'.$p_fim_previsto.'</b>]&nbsp;';
    if ($p_atraso>'')       $w_filtro=$w_filtro.'<td>Ações em atraso&nbsp;[<b>'.$p_atraso.'</b>]&nbsp;';
    if ($p_tarefas_atraso>'')$w_filtro=$w_filtro.'<td>Ações com tarefas em atraso&nbsp;[<b>'.$p_tarefas_atraso.'</b>]&nbsp;';
    ShowHTML('<tr><td align="center">');
    if ($w_filtro>'')ShowHTML('<table border=0 width="100%"><tr><td width="25%"><b>Filtro:</b><td><ul>'.$w_filtro.'</ul></tr></table>');
    ShowHTML('<tr><td align="center" colspan="2">');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan="2"><b>Programa interno</td>');
    ShowHTML('          <td rowspan="2"><b>Ações Cadastradas</td>');
    ShowHTML('          <td rowspan="1" colspan="5"><b>Metas</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Produto</td>');
    ShowHTML('          <td><b>Unidade<br>medida</td>');
    ShowHTML('          <td><b>Quantitativo<br>programado</td>');
    ShowHTML('          <td><b>Quantitativo<br>realizado</td>');
    ShowHTML('          <td><b>% Realizado</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      $w_cont += 1;
      $w_linha += 1;
      ShowHTML('      <tr><td colspan="7" align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_atual = 0;
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        if ($w_linha>30 && $w_tipo_rel=='WORD') {
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
          ShowHTML('</table>');
          ShowHTML('</center></div>');
          ShowHTML('    <br style="page-break-after:always">');
          $w_linha=6;
          $w_pag=$w_pag+1;
          ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
          ShowHTML('Programa interno');
          ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
          ShowHTML('<TR><TD COLSPAN="2" ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Página: '.$w_pag.'</B></TD></TR>');
          ShowHTML('</TD></TR>');
          ShowHTML('</FONT></B></TD></TR></TABLE>');
          ShowHTML('<div align=center><center>');
          ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
          ShowHTML('<tr><td align="center">');
          if ($w_filtro>'') ShowHTML('<table border=0 width="100%"><tr><td width="25%"><b>Filtro:</b><td><ul>'.$w_filtro.'</ul></tr></table>');
          ShowHTML('    <td align="right" valign="botton"><b>Registros listados: '.count($RS));
          ShowHTML('<tr><td align="center" colspan="2">');
          ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
          ShowHTML('          <td rowspan="2"><b>Programa interno</td>');
          ShowHTML('          <td rowspan="2"><b>Ações Cadastradas</td>');
          ShowHTML('          <td rowspan="1" colspan="5"><b>Metas</td>');
          ShowHTML('        </tr>');
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
          ShowHTML('          <td><b>Produto</td>');
          ShowHTML('          <td><b>Unidade<br>medida</td>');
          ShowHTML('          <td><b>Quantitativo<br>programado</td>');
          ShowHTML('          <td><b>Quantitativo<br>realizado</td>');
          ShowHTML('          <td><b>% Realizado</td>');
          ShowHTML('        </tr>');
        }
        //Montagem da lista das ações
        $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ISACAD');
        $RS2 = db_getSolicList_IS::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),4,
                null,null,null,null,null,null,null,null,null,null,f($row,'sq_siw_solicitacao'),null,
                null,null,null,null,null,null,null,null,null,null,null,null,
                null,f($row,'chave'),null,$w_ano);
        $RS2 = SortArray($RS2,'phpdt_fim','asc','prioridade','asc');
        foreach($RS2 as $row2) {$RS2=$row2; break;}
        //Variarel para o teste de existencia de metas e açoes para visualização no relatorio
        $w_teste_metas = 0;
        $w_teste_acoes = 0;
        //Recuperação e verificação das metas das ações de acordo com a visão do usuário
        if (count($RS2)>0) {
          $w_teste_acoes=1;
          $w_visao=0;
          if ($w_visao<2) {
            $RS3 = db_getSolicMeta_IS::getInstanceOf($dbms,$w_cliente,f($RS2,'sq_siw_solicitacao'),null,'LSTNULL',null,null,null,null,null,null,$p_exequivel,$p_programada,$p_fim_previsto);
            $RS3 = SortArray($RS3,'ordem','asc');
            if (count($RS3)>0) {
              $w_teste_metas=1;
            } elseif ($p_programada=='' && $p_exequivel=='' && $p_fim_previsto=='') {
              $w_teste_metas=3;
            } 
          } else {
            $w_teste_metas=0;
          } 
        } else {
          if (f($row,'sq_siw_solicitacao')>'') {
            $w_teste_acoes=1;
            $w_teste_metas=1;
          } else {
            $w_teste_acoes=0;
          } 
        } 
        if ($w_teste_metas==1 || $w_teste_metas==3) {
          //Inicio da montagem da lista das ações e metas de acordo com o filtro
          $w_cont += 1;
          if (($w_atual!=f($row,'chave')) || $p_programada>'' || $p_exequivel>'' || $p_fim_previsto>'' || $p_atraso>'') {
            ShowHTML('      <tr valign="top">');
            ShowHTML('        <td><b>'.f($row,'nome').'</td>');
          } else {
            ShowHTML('      <tr valign="top">');
            ShowHTML('        <td><b>&nbsp;</td>');
          } 
          $w_linha += 1;
          $w_sq_siw_solicitacao = Nvl(f($row,'sq_siw_solicitacao'),'');
          if ($w_sq_siw_solicitacao>'') {
            if ($w_tipo_rel=='WORD')    ShowHTML('        <td><b>'.f($row,'titulo').'</td>');
            else                        ShowHTML('        <td><b><A class="HL" HREF="'.$w_dir.'acao.php?par=Visual&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=&P1=1&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" TARGET="VisualAcao" title="Exibe as informações da ação.">'.f($row,'titulo').'</a></td>');
            if (count($RS2)<=0) {
              ShowHTML('      <td colspan="5" align="center"><b>Não foram encontrados registros.<b></td>');
            } else {
              if (count($RS3)<=0) {
                ShowHTML('      <td colspan="5" align="center"><b>Não foram encontrados registros.</b></td></tr>');
              } else {
                $i=0;
                foreach($RS3 as $row3) {
                  if ($i==1) {
                    ShowHTML('      <tr><td colspan="2">&nbsp;');
                  }
                  $i=1;
                  if ($w_tipo_rel=='WORD')  ShowHTML('      <td>'.f($row3,'titulo').'</td>');
                  else                      ShowHTML('      <td><A class="HL" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS($w_dir,'acao.php?par=AtualizaMeta&O=V&w_chave='.f($RS2,'sq_siw_solicitacao').'&w_chave_aux='.f($row3,'sq_meta').'&w_tipo=Volta&P1=10&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\',\'Meta\',\'width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no\'); return false;" title="Clique para exibir os dados!">'.f($row3,'titulo').'</A></td>');
                  ShowHTML('      <td nowrap align="center">'.Nvl(f($row3,'unidade_medida'),'---').'</td>');
                  ShowHTML('      <td nowrap align="right" >'.number_format(Nvl(f($row3,'quantidade'),0),2,',','.').'</td>');
                  $RS4 = db_getMetaMensal_IS::getInstanceOf($dbms,f($row3,'sq_meta'));
                  $RS4 = SortArray($RS4,'phpdt_referencia','desc');
                  if (count($RS4)>0) {
                    if (f($row3,'cumulativa')=='S') {
                      foreach($RS4 as $row4) {$RS4=$row4; break;}
                      ShowHTML('      <td nowrap align="right" >'.number_format(Nvl(f($RS4,'execucao_fisica'),0),2,',','.').'</td>');
                    } else {
                      $w_quantitativo_total=0;
                      foreach($RS4 as $row4) {
                        $w_quantitativo_total=$w_quantitativo_total+Nvl(f($row4,'execucao_fisica'),0);
                      } 
                      ShowHTML('      <td nowrap align="right" >'.number_format(Nvl($w_quantitativo_total,0),2,',','.').'</td>');
                    }
                  } else {
                    ShowHTML('      <td nowrap align="right" >---</td>');
                  }
                  ShowHTML('           <td nowrap align="right" >'.f($row3,'perc_conclusao').'</td>');
                  ShowHTML('        </tr>');
                  $w_linha=$w_linha+1;
                } 
              }
            }
          } else {
            ShowHTML('        <td colspan="6" align="middle"><b>Não foram encontrados registros.</b></td>');
          } 
        } else {
          if ($p_programada=='' && $p_exequivel=='' && $p_fim_previsto=='' && $p_atraso=='') {
            $w_cont=$w_cont+1;
            if ($w_atual!=f($row,'chave')) {
              ShowHTML('      <tr valign="top">');
              ShowHTML('        <td><b>'.f($row,'nome').'</td>');
            } else {
              ShowHTML('      <tr valign="top">');
              ShowHTML('        <td><b>&nbsp;</td>');
            } 
            $w_linha += 1;
            if ($w_teste_acoes==1) {
              ShowHTML('        <td colspan="1"><b>'.f($row,'titulo').'</b></td>');
              ShowHTML('        <td colspan="5" align="center"><b>Não há permissão para visualização da ação<b></td>');
            } else {
              ShowHTML('        <td colspan="6" align="center"><b>Não foram encontrados registros.</b></td>');
            } 
          } 
        } 
        $w_atual=f($row,'chave');
      } 
    } 
    if ($w_cont==0) {
      ShowHTML('        <td colspan="7" align="center"><b>Não foram encontrados registros.</b></td>');
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Projeto',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('          <tr>');
    SelecaoIsProjeto('<u>P</u>rograma interno:','P',null,$p_sq_isprojeto,null,'p_sq_isprojeto',null,'onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_sq_isprojeto\'; document.Form.target=\'\'; document.Form.O.value=\'P\'; document.Form.submit();"');
    ShowHTML('          </tr>');
    ShowHTML('          <tr>');
    SelecaoAcao('<u>A</u>ção:','A',null,$w_cliente,$w_ano,null,null,null,null,'p_siw_solic','PROJETO',null,$p_sq_isprojeto);
    ShowHTML('          </tr>');
    ShowHTML('      <tr>');
    SelecaoPrioridade('<u>P</u>rioridade das tarefas:','P','Informe a prioridade da tarefa.',$p_prioridade,null,'p_prioridade',null,null);
    ShowHTML('      <tr><td colspan=3><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('          <td><b>Selecionada SPI/MP?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value="" checked> Independe');
    ShowHTML('          <td><b>Selecionada SE/SEPPIR?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecao_se" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecao_se" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecao_se" value="" checked> Independe');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Exibir somente metas do PPA?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_programada" value="S"> Sim <br><input '.$w_Disabled.' type="radio" name="p_programada" value="" checked> Não');
    ShowHTML('          <td><b>Exibir somente metas que não serão cumpridas?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_exequivel" value="N"> Sim <br><input '.$w_Disabled.' type="radio" name="p_exequivel" value="" checked> Não');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Exibir somente metas em atraso?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_fim_previsto" value="S"> Sim <br><input '.$w_Disabled.' type="radio" name="p_fim_previsto" value="" checked> Não');
    ShowHTML('          <td><b>Exibir somente ações em atraso?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' type="radio" name="p_atraso" value="" checked> Não');
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
  if ($w_tipo_rel!='WORD') Rodape();
} 
// =========================================================================
// Relatório Sintético das Ações do PPA
// -------------------------------------------------------------------------
function Rel_Sintetico_PPA() {
  extract($GLOBALS);
  $w_chave      = $_REQUEST['w_chave'];
  $w_troca      = $_REQUEST['w_troca'];
  $w_tipo_rel   = strtoupper(trim($_REQUEST['w_tipo_rel']));
  $p_codigo     = strtoupper(trim($_REQUEST['p_codigo']));
  if (strtoupper(trim($_REQUEST['p_cd_programa']))>'' && $p_codigo=='') $p_cd_programa = strtoupper(trim($_REQUEST['p_cd_programa']));
  else                                                                  $p_cd_programa = strtoupper(trim(substr($p_codigo,0,4)));
  $p_cd_acao        = strtoupper(trim(substr($p_codigo,4,4)));
  $p_sq_unidade_resp= strtoupper(trim($_REQUEST['p_sq_unidade_resp']));
  $p_prioridade     = strtoupper(trim($_REQUEST['p_prioridade']));
  $p_selecao_mp     = strtoupper(trim($_REQUEST['p_selecao_mp']));
  $p_selecao_se     = strtoupper(trim($_REQUEST['p_selecao_se']));
  $p_programada     = strtoupper(trim($_REQUEST['p_programada']));
  $p_exequivel      = strtoupper(trim($_REQUEST['p_exequivel']));
  $p_fim_previsto   = strtoupper(trim($_REQUEST['p_fim_previsto']));
  $p_atraso         = strtoupper(trim($_REQUEST['p_atraso']));
  $p_tarefas_atraso = strtoupper(trim($_REQUEST['p_tarefas_atraso']));
  $p_opcao          = $_REQUEST['p_opcao'];
  $p_opcao_ant      = $_REQUEST['p_opcao'];
  if($_REQUEST['p_opcao_ant']!=$_REQUEST['p_opcao']) {
    $p_macro        = '';
  } else {
    $p_macro        = $_REQUEST['p_macro'];
  }
  $w_cont=0;
  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'') $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
    if ($p_cd_programa>'' && $p_codigo=='') $RS = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,$p_cd_programa,null,null,null,null,null,null,$p_macro,$p_opcao);
    else                                    $RS = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,substr($p_codigo,0,4),substr($p_codigo,4,4),null,substr($p_codigo,12,17),null,null,null,$p_macro,$p_opcao);
    $RS = SortArray($RS,'cd_programa','asc','cd_acao','asc','cd_unidade','asc');
  } 
  if ($w_tipo_rel=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_pag=1;
    $w_linha=8;
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
    ShowHTML('Relatório Sintético - Ações PPA 2004 - 2007 Exercício '.$w_ano);
    ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
    ShowHTML('<TR><TD COLSPAN="2" ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Página: '.$w_pag.'</B></TD></TR>');
    ShowHTML('</TD></TR>');
    ShowHTML('</FONT></B></TD></TR></TABLE>');
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Relatório Sintético - Ações PPA 2004 - 2007 Exercício '.$w_ano.'</TITLE>');
    if (!(strpos('P',$O)===false)) {
      ScriptOpen('JavaScript');
      ValidateOpen('Validacao');
      Validate('p_cd_programa','Programa','SELECT','','1','18','1','1');
      Validate('p_codigo','Ação','SELECT','','1','18','1','1');
      ValidateClose();
      ScriptClose();
    } 
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($O=='L') {
      BodyOpenClean(null);
      ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
      ShowHTML('Relatório Sintético - Ações PPA 2004 - 2007 Exercício '.$w_ano);
      ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
      ShowHTML('&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      ShowHTML('&nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&w_chave='.$w_chave.'&w_tipo_rel=word&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
      ShowHTML('</TD></TR>');
      ShowHTML('</FONT></B></TD></TR></TABLE>');
    } else {
      BodyOpen('onLoad=\'document.Form.p_cd_programa.focus()\';');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    } 
    ShowHTML('<HR>');
  } 
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    $w_filtro='<tr valign="top">';
    if ($p_prioridade>'')       $w_filtro=$w_filtro.'<td>Prioridade&nbsp;[<b>'.RetornaPrioridade($p_prioridade).'</b>]&nbsp;';
    if ($p_selecao_mp>'')       $w_filtro=$w_filtro.'<td>Selecionada SPI/MP&nbsp;[<b>'.$p_selecao_mp.'</b>]&nbsp;';
    if ($p_selecao_se>'')       $w_filtro=$w_filtro.'<td>Selecionada SE/SEPPIR&nbsp;[<b>'.$p_selecao_se.'</b>]&nbsp;';
    if ($p_programada>'')       $w_filtro=$w_filtro.'<td>Meta PPA&nbsp;[<b>'.$p_programada.'</b>]&nbsp;';
    if ($p_exequivel>'')        $w_filtro=$w_filtro.'<td>Meta será cumprida&nbsp;[<b>'.$p_exequivel.'</b>]&nbsp;';
    if ($p_fim_previsto>'')     $w_filtro=$w_filtro.'<td>Metas em atraso&nbsp;[<b>'.$p_fim_previsto.'</b>]&nbsp;';
    if ($p_atraso>'')           $w_filtro=$w_filtro.'<td>Ações em atraso&nbsp;[<b>'.$p_atraso.'</b>]&nbsp;';
    if ($p_tarefas_atraso>'')   $w_filtro=$w_filtro.'<td>Ações com tarefas em atraso&nbsp;[<b>'.$p_tarefas_atraso.'</b>]&nbsp;';
    ShowHTML('<tr><td align="left">');
    if ($w_filtro>'')           ShowHTML('<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>');
    ShowHTML('<tr><td align="center" colspan="2">');
    ShowHTML('      <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan="1" colspan="2"><b>Programas</td>');
    ShowHTML('          <td rowspan="1" colspan="2"><b>Ações</td>');
    $RS1 = db_getOrImport::getInstanceOf($dbms,null,$w_cliente,null,null,null,null,null);
    $RS1 = SortArray($RS1,'phpdt_data_arquivo','desc');
    foreach($RS1 as $row1){$RS1=$row1; break;}
    if (count($RS1)>0) {
      ShowHTML('          <td rowspan="1" colspan="4"><b>Dados SIAFI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Atualização: '.Nvl(FormataDataEdicao(f($RS1,'data_arquivo')),'-').'</td>');
    } else {
      ShowHTML('          <td rowspan="1" colspan="4"><b>Dados SIAFI</td>');
    } 
    ShowHTML('          <td rowspan="1" colspan="7"><b>Metas</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Cód</td>');
    ShowHTML('          <td><b>Nome</td>');
    ShowHTML('          <td><b>Cód</td>');
    ShowHTML('          <td><b>Nome</td>');
    ShowHTML('          <td><b>Aprovado</td>');
    ShowHTML('          <td><b>Autorizado</td>');
    ShowHTML('          <td><b>Realizado</td>');
    ShowHTML('          <td><b>Importação</td>');
    ShowHTML('          <td><b>Produto</td>');
    ShowHTML('          <td><b>Unidade<br>medida</td>');
    ShowHTML('          <td><b>Quantitativo<br>programado</td>');
    ShowHTML('          <td><b>Quatintativo<br>realizado</td>');
    ShowHTML('          <td><b>% Realizado</td>');
    ShowHTML('          <td><b>Meta<br>PPA</td>');
    ShowHTML('          <td><b>Meta<br>PNPIR</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      $w_cont += 1;
      $w_linha += 1;
      ShowHTML('    <tr bgcolor="'.$conTrBgColor.'"><td colspan=16 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_atual=0;
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        if ($w_linha>19 && $w_tipo_rel=='WORD') {
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
          ShowHTML('</table>');
          ShowHTML('</center></div>');
          ShowHTML('    <br style="page-break-after:always">');
          $w_linha=6;
          $w_pag=$w_pag+1;
          ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
          ShowHTML('Ações do PPA');
          ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
          ShowHTML('<TR><TD COLSPAN="2" ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Página: '.$w_pag.'</B></TD></TR>');
          ShowHTML('</TD></TR>');
          ShowHTML('</FONT></B></TD></TR></TABLE>');
          ShowHTML('<div align=center><center>');
          ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
          ShowHTML('<tr><td align="left">');
          if ($w_filtro>'')   ShowHTML('<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>');
          ShowHTML('<tr><td align="center" colspan="2">');
          ShowHTML('      <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
          ShowHTML('          <td rowspan="1" colspan="2"><b>Programas</td>');
          ShowHTML('          <td rowspan="1" colspan="2"><b>Ações</td>');
          $RS1 = db_getOrImport::getInstanceOf($dbms,null,$w_cliente,null,null,null,null,null);
          $RS1 = SortArray($RS1,'phpdt_data_arquivo','desc');
          foreach($RS1 as $row1){$RS1=$row1; break;}
          if (count($RS1)>0)    ShowHTML('          <td rowspan="1" colspan="4"><b>Dados SIAFI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Atualização: '.Nvl(FormataDataEdicao(f($RS1,'data_arquivo')),'-').'</td>');
          else                  ShowHTML('          <td rowspan="1" colspan="4"><b>Dados SIAFI</td>');
          ShowHTML('          <td rowspan="1" colspan="7"><b>Metas</td>');
          ShowHTML('        </tr>');
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
          ShowHTML('          <td><b>Cód</td>');
          ShowHTML('          <td><b>Nome</td>');
          ShowHTML('          <td><b>Cód</td>');
          ShowHTML('          <td><b>Nome</td>');
          ShowHTML('          <td><b>Aprovado</td>');
          ShowHTML('          <td><b>Autorizado</td>');
          ShowHTML('          <td><b>Realizado</td>');
          ShowHTML('          <td><b>Importação</td>');
          ShowHTML('          <td><b>Produto</td>');
          ShowHTML('          <td><b>Unidade<br>medida</td>');
          ShowHTML('          <td><b>Quantitativo<br>programado</td>'); 
          ShowHTML('          <td><b>Quatintativo<br>realizado</td>');
          ShowHTML('          <td><b>% Realizado</td>');
          ShowHTML('          <td><b>Meta<br>PPA</td>');
          ShowHTML('          <td><b>Meta<br>PNPIR</td>');
          ShowHTML('        </tr>');
        } 
        //Montagem da lista das ações
        $RS1 = db_getLinkData::getInstanceOF($dbms,$w_cliente,'ISACAD');
        $RS2 = db_getSolicList_IS::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),4,
                  null,null,null,null,$p_atraso,null,null,null,null,null,null,null,
                  null,null,null,null,null,null,null,null,null,null,null,substr(f($row,'chave'),0,4),
                  f($row,'cd_acao'),null,substr(f($row,'chave'),8,4),$w_ano);
        foreach($RS2 as $row2){$RS2=$row2; break;}
        //Variarel para o teste de existencia de metas e açoes para visualização no relatorio
        $w_teste_metas=0;
        $w_teste_acoes=0;
        //Recuperação e verificação das metas das ações de acordo com a visão do usuário
        if (count($RS2)>0) {
          $w_teste_acoes=1;
          $w_visao=0;
          if ($w_visao<2) {
            $RS3 = db_getSolicMeta_IS::getInstanceOf($dbms,$w_cliente,f($RS2,'sq_siw_solicitacao'),null,'LSTNULL',null,null,null,null,null,$p_programada,$p_exequivel,null,$p_fim_previsto);
            $RS3 = SortArray($RS3,'ordem','asc');
            if (count($RS3)>0) {
              $w_teste_metas=1;
            } elseif ($p_programada=='' && $p_exequivel=='' && $p_fim_previsto=='') {
              $w_teste_metas=3;
            } 
          } else {
            $w_teste_metas=0;
          } 
        } else {
          if (f($row,'sq_siw_solicitacao')>'') {
            $w_teste_acoes=1;
            $w_teste_metas=0;
          } else {
            $w_teste_acoes=0;
          } 
        } 
        if ($w_teste_metas==1 || $w_teste_metas==3) {
          //Inicio da montagem da lista das ações e metas de acordo com o filtro
          $w_cont += 1;
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          if (f($row,'cd_programa')!=$w_teste_pai || $p_programada>'' || $p_exequivel>'' || $p_fim_previsto>'' || $p_atraso>'') {
            ShowHTML(' <tr bgcolor="'.$conTrAlternateBgColor.'" valign="top">');
            ShowHTML('   <td><b>'.f($row,'cd_programa').'</td>');
            ShowHTML('   <td><b>'.f($row,'ds_programa').'</td>');
            $w_atual=1;
          } else {
            ShowHTML(' <tr valign="top">');
            ShowHTML('   <td><b>&nbsp;</td>');
            ShowHTML('   <td><b>&nbsp;</td>');
          } 
          $w_linha += 1;
          ShowHTML('      <td nowrap><b>'.f($row,'cd_unidade').'.'.f($row,'cd_programa').'.'.f($row,'cd_acao').'</td>');
          if ($w_tipo_rel=='WORD' || f($row,'sq_siw_solicitacao')=='') ShowHTML('   <td><b>'.f($row,'descricao_acao').'</td>');
          else                                                         ShowHTML('   <td><b><A class="HL" HREF="'.$w_dir.'acao.php?par=Visual&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" TARGET="VisualAcao" title="Exibe as informações da ação.">'.f($row,'descricao_acao').'</a></td>');
          ShowHTML('      <td align="right">'.number_format(Nvl(f($row,'previsao_ano'),0),2,',','.').'</td>');
          ShowHTML('      <td align="right">'.number_format(Nvl(f($row,'atual_ano'),0),2,',','.').'</td>');
          ShowHTML('      <td align="right">'.number_format(Nvl(f($row,'real_ano'),0),2,',','.').'</td>');
          if (Nvl(f($row,'dt_carga_financ'),'')>'') ShowHTML('      <td align="center">'.FormataDataEdicao(f($RS,'dt_carga_financ')).'</td>');
          else                                     ShowHTML('      <td align="center">---</td>');
          if (count($RS2)<=0) {
            ShowHTML('   <td colspan="7" align="center"><b>Não foram encontrados registros.</b></td>');
          } else {
            if (count($RS3)<=0) {
              // Se não foram selecionados registros, exibe mensagem
              ShowHTML('<td colspan="7" align="center"><b>Não foram encontrados registros.</b></td></tr>');
            } else {
              $i=0;
              foreach($RS3 as $row3) {
                if($i==1) {
                  ShowHTML('<tr><td colspan="8">&nbsp;');
                }
                $i=1;
                if ($w_tipo_rel=='WORD')  ShowHTML('<td>'.f($row3,'titulo').'</td>');
                else                      ShowHTML('<td><A class="HL" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS($w_dir,'acao.php?par=AtualizaMeta&O=V&w_chave='.f($RS2,'sq_siw_solicitacao').'&w_chave_aux='.f($row3,'sq_meta').'&w_tipo=Volta&P1=10&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\',\'Meta\',\'width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no\'); return false;" title="Clique para exibir os dados!">'.f($row3,'titulo').'</A></td>');
                ShowHTML('      <td nowrap align="center">'.Nvl(f($row3,'unidade_medida'),'---').'</td>');
                ShowHTML('      <td nowrap align="right" >'.f($row3,'quantidade').'</td>');
                $RS4 = db_getMetaMensal_IS::getInstanceOf($dbms,f($row3,'sq_meta'));
                $RS4 = SortArray($RS4,'phpdt_referencia','desc');
                if (count($RS4)>0) {
                  if (f($row3,'cumulativa')=='S') {
                    foreach($RS4 as $row4){$RS4=$row4; break;}
                    ShowHTML('      <td nowrap align="right" >'.Nvl(f($RS4,'realizado'),0).'</td>');
                  } else {
                    $w_quantitativo_total=0;
                    foreach($RS4 as $row4) {
                      $w_quantitativo_total=$w_quantitativo_total+Nvl(f($row4,'realizado'),0);
                    } 
                    ShowHTML('      <td nowrap align="right" >'.$w_quantitativo_total.'</td>');
                  }
                } else {
                  ShowHTML('      <td nowrap align="center" >---</td>');
                } 
                ShowHTML('<td nowrap align="right" >'.f($row3,'perc_conclusao').'</td>');
                if (Nvl(f($row3,'cd_subacao'),'')>'')  ShowHTML('<td nowrap align="center" >Sim</td>');
                else                                  ShowHTML('<td nowrap align="center" >Não</td>');
                ShowHTML('<td nowrap align="center" >'.f($row3,'nm_programada').'</td>');
                $w_linha += 1;
              } 
            } 
          } 
        } else {
          if ($p_programada=='' && $p_exequivel=='' && $p_fim_previsto=='' && $p_atraso=='') {
            $w_cont += 1;
            if (f($row,'cd_programa')!=$w_teste_pai) {
              ShowHTML(' <tr bgcolor="'.$conTrAlternateBgColor.'" valign="top">');
              ShowHTML('   <td><b>'.f($row,'cd_programa').'</td>');
              ShowHTML('   <td><b>'.f($row,'ds_programa').'</td>');
              $w_atual=1;
            } else {
              ShowHTML(' <tr valign="top">');
              ShowHTML('   <td><b>&nbsp;</td>');
              ShowHTML('   <td><b>&nbsp;</td>');
            } 
            $w_linha += 1;
            if ($w_teste_acoes==1) {
              ShowHTML('        <td colspan="1" nowrap><b>'.f($row,'cd_unidade').'.'.f($row,'cd_programa').'.'.f($row,'cd_acao').'</b></td>');
              ShowHTML('        <td colspan="1"><b>'.f($row,'descricao_acao').'</b></td>');
              if ($w_teste_metas==3)  ShowHTML('        <td colspan="12" align="center"><b>Não foram encontrados registros.<b></td>');
              else                    ShowHTML('        <td colspan="12" align="center"><b>Não há permissão para visualização da ação<b></td>');
            } else {
              ShowHTML('        <td colspan="1" nowrap><b>'.f($row,'cd_unidade').'.'.f($row,'cd_programa').'.'.f($row,'cd_acao').'</b></td>');
              ShowHTML('        <td colspan="1"><b>'.f($row,'descricao_acao').'</b></td>');
              ShowHTML('        <td colspan="12" align="center"><b>Não foram encontrados registros.</b></td>');
            } 
          } 
        } 
        $w_teste_pai = f($row,'cd_programa');
      } 
    }
    if ($w_cont==0) ShowHTML('        <td colspan="18" align="center"><b>Não foram encontrados registros.</b></td>');
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>'); 
  } elseif ($O=='P') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Acao',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="p_opcao_ant" value="'.$p_opcao_ant.'">');    
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr>');
    SelecaoOpcaoEstrat('<u>O</u>pção estratégica:','O',null,$p_opcao,null,'p_opcao','ATIVO','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_macro\'; document.Form.target=\'\'; document.Form.O.value=\'P\'; document.Form.submit();"');
    ShowHTML('      <tr>');
    SelecaoMacroObjetivo('<u>M</u>acro objetivo:','M',null,$p_macro,$p_opcao,'p_macro','ATIVO','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_cd_programa\'; document.Form.target=\'\'; document.Form.O.value=\'P\'; document.Form.submit();"');
    ShowHTML('      <tr>');
    SelecaoProgramaPPA('<u>P</u>rograma PPA:','P',null,$w_cliente,$w_ano,$p_cd_programa,'p_cd_programa','RELATORIO','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_codigo\'; document.Form.target=\'\'; document.Form.O.value=\'P\'; document.Form.submit();"',$w_menu,$p_macro,$p_opcao);
    ShowHTML('      <tr>');
    SelecaoAcaoPPA('<u>A</u>ção PPA:','A',null,$w_cliente,$w_ano,$p_cd_programa,null,null,null,'p_codigo',null,null,null,$w_menu,$p_macro,$p_opcao);
    ShowHTML('      <tr>');
    SelecaoPrioridade('<u>P</u>rioridade das tarefas:','P','Informe a prioridade da tarefa.',$p_prioridade,null,'p_prioridade',null,null);
    ShowHTML('      <tr><td colspan=3><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('          <td><b>Selecionada SPI/MP?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value="" checked> Independe');
    ShowHTML('          <td><b>Selecionada SE/SEPPIR?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecao_se" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecao_se" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecao_se" value="" checked> Independe');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Exibir somente metas do PPA?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_programada" value="S"> Sim <br><input '.$w_Disabled.' type="radio" name="p_programada" value="" checked> Não');
    ShowHTML('          <td><b>Exibir somente metas que não serão cumpridas?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_exequivel" value="N"> Sim <br><input '.$w_Disabled.' type="radio" name="p_exequivel" value="" checked> Não');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Exibir somente metas em atraso?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_fim_previsto" value="S"> Sim <br><input '.$w_Disabled.' type="radio" name="p_fim_previsto" value="" checked> Não');
    ShowHTML('          <td><b>Exibir somente ações em atraso?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' type="radio" name="p_atraso" value="" checked> Não');
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
  if ($w_tipo_rel!='WORD')  Rodape();
} 
// =========================================================================
// Relatório Sintético dos programas PPA
// -------------------------------------------------------------------------
function Rel_Sintetico_Prog() {
  extract($GLOBALS);
  $w_chave          = $_REQUEST['w_chave'];
  $w_troca          = $_REQUEST['w_troca'];
  $w_tipo_rel       = strtoupper(trim($_REQUEST['w_tipo_rel']));
  $p_cd_programa    = strtoupper(trim($_REQUEST['p_cd_programa']));
  $p_sq_unidade_resp= strtoupper(trim($_REQUEST['p_sq_unidade_resp']));
  $p_prioridade     = strtoupper(trim($_REQUEST['p_prioridade']));
  $p_selecao_mp     = strtoupper(trim($_REQUEST['p_selecao_mp']));
  $p_selecao_se     = strtoupper(trim($_REQUEST['p_selecao_se']));
  $p_loa            = strtoupper(trim($_REQUEST['p_loa']));
  $p_exequivel      = strtoupper(trim($_REQUEST['p_exequivel']));
  $p_atraso         = strtoupper(trim($_REQUEST['p_atraso']));
  $p_opcao          = $_REQUEST['p_opcao'];
  $p_opcao_ant      = $_REQUEST['p_opcao'];
  if($_REQUEST['p_opcao_ant']!=$_REQUEST['p_opcao']) {
    $p_macro        = '';
  } else {
    $p_macro        = $_REQUEST['p_macro'];
  }  
  $w_cont=0;
  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'') $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
    $RS = db_getProgramaPPA_IS::getInstanceOf($dbms,$p_cd_programa,$w_cliente,$w_ano,null,null,$p_macro,$p_opcao);
    $RS = SortArray($RS,'ds_programa','asc');
  } 
  if ($w_tipo_rel=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_pag=1;
    $w_linha=8;
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
    ShowHTML('Relatório Sintético - Programas PPA 2004 - 2007 Exercício '.$w_ano);
    ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
    ShowHTML('<TR><TD COLSPAN="2" ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Página: '.$w_pag.'</B></TD></TR>');
    ShowHTML('</TD></TR>');
    ShowHTML('</FONT></B></TD></TR></TABLE>');
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Relatório Sintético - Programas PPA 2004 - 2007 Exercício '.$w_ano.'</TITLE>');
    if (!(strpos('P',$O)===false)) {
      ScriptOpen('JavaScript');
      ValidateOpen('Validacao');
      Validate('p_cd_programa','Programa','SELECT','','1','18','1','1');
      ValidateClose();
      ScriptClose();
    } 
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($O=='L') {
      BodyOpenClean(null);
      ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
      ShowHTML('Relatório Sintético - Programas PPA 2004 - 2007 Exercício '.$w_ano);
      ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
      ShowHTML('&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      ShowHTML('&nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&w_chave='.$w_chave.'&w_tipo_rel=word&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
      ShowHTML('</TD></TR>');
      ShowHTML('</FONT></B></TD></TR></TABLE>');
    } else {
      BodyOpen('onLoad=\'document.Form.p_cd_programa.focus()\';');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    } 
    ShowHTML('<HR>');
  } 
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    $w_filtro='<tr valign="top">';
    if ($p_selecao_mp>'')   $w_filtro=$w_filtro.'<td>Selecionada SPI/MP&nbsp;[<b>'.$p_selecao_mp.'</b>]&nbsp;';
    if ($p_selecao_se>'')   $w_filtro=$w_filtro.'<td>Selecionada SE/SEPPIR&nbsp;[<b>'.$p_selecao_se.'</b>]&nbsp;';
    if ($p_loa>'')          $w_filtro=$w_filtro.'<td>Indicador PPA&nbsp;[<b>'.$p_loa.'</b>]&nbsp;';
    if ($p_exequivel>'')    $w_filtro=$w_filtro.'<td>Indicador será cumprido&nbsp;[<b>'.$p_exequivel.'</b>]&nbsp;';
    if ($p_atraso>'')       $w_filtro=$w_filtro.'<td>Programas em atraso&nbsp;[<b>'.$p_atraso.'</b>]&nbsp;';
    ShowHTML('<tr><td align="left">');
    if ($w_filtro>'')       ShowHTML('<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>');
    ShowHTML('<tr><td align="center" colspan="2">');
    ShowHTML('      <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan="1" colspan="2"><b>Programas</td>');
    ShowHTML('          <td rowspan="1" colspan="7"><b>Indicadores</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Cód</td>');
    ShowHTML('          <td><b>Nome</td>');
    ShowHTML('          <td><b>Nome</td>');
    ShowHTML('          <td><b>Índice de<br>referência</td>');
    ShowHTML('          <td><b>Unidade de<br>medida</td>');
    ShowHTML('          <td><b>Índice<br>programado</td>');
    ShowHTML('          <td><b>Indicador<br>PPA</td>');
    ShowHTML('          <td><b>Índice<br>apurado</td>');
    ShowHTML('          <td><b>Data de<br>apuração</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      $w_cont   += 1;
      $w_linha  += 1;
      ShowHTML('    <tr bgcolor="'.$conTrBgColor.'"><td colspan=16 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_atual=0;
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        if ($w_linha>19 && $w_tipo_rel=='WORD') {
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
          ShowHTML('</table>');
          ShowHTML('</center></div>');
          ShowHTML('    <br style="page-break-after:always">');
          $w_linha=6;
          $w_pag=$w_pag+1;
          ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
          ShowHTML('Programas do PPA');
          ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
          ShowHTML('<TR><TD COLSPAN="2" ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Página: '.$w_pag.'</B></TD></TR>');
          ShowHTML('</TD></TR>');
          ShowHTML('</FONT></B></TD></TR></TABLE>');
          ShowHTML('<div align=center><center>');
          ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
          ShowHTML('<tr><td align="left">');
          if ($w_filtro>'') ShowHTML('<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>');
          ShowHTML('<tr><td align="center" colspan="2">');
          ShowHTML('      <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
          ShowHTML('          <td rowspan="1" colspan="2"><b>Programas</td>');
          ShowHTML('          <td rowspan="1" colspan="7"><b>Indicadores</td>');
          ShowHTML('        </tr>');
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
          ShowHTML('          <td><b>Cód</td>');
          ShowHTML('          <td><b>Nome</td>');
          ShowHTML('          <td><b>Nome</td>');
          ShowHTML('          <td><b>Índice de<br>referência</td>');
          ShowHTML('          <td><b>Unidade de<br>medida</td>');
          ShowHTML('          <td><b>Índice<br>programado</td>');
          ShowHTML('          <td><b>Indicador<br>PPA</td>');
          ShowHTML('          <td><b>Índice<br>apurado</td>');
          ShowHTML('          <td><b>Data de<br>apuração</td>');
          ShowHTML('        </tr>');
        } 
        //Montagem da lista de programa
        $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ISPCAD');
        $RS2 = db_getSolicList_IS::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),4,
                null,null,null,null,$p_atraso,null,null,null,null,null,null,null,
                null,null,null,null,null,null,null,null,null,null,null,null,
                f($row,'cd_programa'),null,null,$w_ano);
        foreach($RS2 as $row2){$RS2=$row2; break;}
        //Variarel para o teste de existencia de metas e açoes para visualização no relatorio
        $w_teste_indicador=0;
        $w_teste_programas=0;
        //Recuperação e verificação das metas das ações de acordo com a visão do usuário
        if (count($RS2)>0) {
          $w_teste_programas=1;
          $w_visao=0;
          if ($w_visao<2) {
            $RS3 = db_getSolicIndic_IS::getInstanceOf($dbms,f($RS2,'sq_siw_solicitacao'),null,'LISTA',$p_loa,$p_exequivel);
            $RS3 = SortArray($RS3,'ordem','asc');
            if (count($RS3)>0)                      $w_teste_indicador=1;
            elseif ($p_loa=='' && $p_exequivel=='') $w_teste_indicador=3;
          } else {
            $w_teste_indicador=0;
          } 
        } else {
          if (f($row,'sq_siw_solicitacao')>'') {
            $w_teste_programas=1;
            $w_teste_indicador=0;
          } else {
            $w_teste_programas=0;
          } 
        } 
        if ($w_teste_indicador==1 || $w_teste_indicador==3) {
          //Inicio da montagem da lista dos programas e indicadores de acordo com o filtro
          $w_cont += 1;
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML(' <tr bgcolor="'.$conTrAlternateBgColor.'" valign="top">');
          ShowHTML('      <td nowrap><b>'.f($row,'cd_programa').'</td>');
          if ($w_tipo_rel=='WORD' || f($row,'sq_siw_solicitacao')=='')  ShowHTML('   <td><b>'.f($row,'ds_programa').'</td>');
          else                                                          ShowHTML('   <td><b><A class="HL" HREF="'.$w_dir.'programa.php?par=Visual&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" TARGET="VisualAcao" title="Exibe as informações do programa.">'.f($row,'ds_programa').'</a></td>');
          if (count($RS2)<=0) {
            ShowHTML('   <td colspan="6" align="center"><b>Não foram encontrados registros.</b></td>');
          } else {
            if (count($RS3)<=0) {
              // Se não foram selecionados registros, exibe mensagem
              ShowHTML('<td colspan="7" align="center"><b>Não foram encontrados registros.</b></td></tr>');
            } else {
              $i=0;
              foreach($RS3 as $row3) {
                if($i==1) ShowHTML('<tr><td colspan="2">&nbsp;');
                $i=1;
                if ($w_tipo_rel=='WORD')  ShowHTML('<td>'.f($row3,'titulo').'</td>');
                else                      ShowHTML('<td><A class="HL" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS($w_dir,'programa.php?par=AtualizaIndicador&O=V&w_chave='.f($RS2,'sq_siw_solicitacao').'&w_chave_aux='.f($row3,'sq_indicador').'&w_tipo=Volta&P1=10&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\',\'Meta\',\'width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no\'); return false;" title="Clique para exibir os dados!">'.f($row3,'titulo').'</A></td>');
                ShowHTML('      <td nowrap align="right">'.number_format(Nvl(f($row3,'valor_referencia'),0),2,',','.').'</td>');
                ShowHTML('      <td nowrap align="left" >'.Nvl(f($row3,'nm_unidade_medida'),'---').'</td>');
                ShowHTML('      <td nowrap align="right" >'.number_format(Nvl(f($row3,'quantidade'),0),2,',','.').'</td>');
                if (f($row3,'cd_indicador')>'')    ShowHTML('<td nowrap align="center" >Sim</td>');
                else                              ShowHTML('<td nowrap align="center" >Não</td>');
                ShowHTML('<td nowrap align="right" >'.number_format(Nvl(f($row3,'valor_apurado'),0),2,',','.').'</td>');
                ShowHTML('<td nowrap align="center" >'.Nvl(FormataDataEdicao(f($row3,'apuracao_indice')),'---').'</td>');
              } 
            } 
          } 
        } else {
          if ($p_loa=='' && $p_exequivel=='' && $p_atraso=='') {
            $w_cont += 1;
            $w_linha += 1;
            ShowHTML(' <tr bgcolor="'.$conTrAlternateBgColor.'" valign="top">');
            if ($w_teste_programas==1) {
              ShowHTML('        <td colspan="1" nowrap><b>'.f($row,'cd_programa').'</b></td>');
              ShowHTML('        <td colspan="1"><b>'.f($row,'ds_programa').'</b></td>');
              if ($w_teste_indicador==3)  ShowHTML('        <td colspan="7" align="center"><b>Não foram encontrados registros.<b></td>');
              else                        ShowHTML('        <td colspan="7" align="center"><b>Não há permissão para visualização do programa<b></td>');
            } else {
              ShowHTML('        <td colspan="1" nowrap><b>'.f($row,'cd_programa').'</b></td>');
              ShowHTML('        <td colspan="1"><b>'.f($row,'ds_programa').'</b></td>');
              ShowHTML('        <td colspan="7" align="center"><b>Não foram encontrados registros.</b></td>');
            } 
          } 
        } 
      } 
    } 
    if ($w_cont==0) ShowHTML('        <td colspan="17" align="center"><b>Não foram encontrados registros.</b></td>');
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Programa',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="p_opcao_ant" value="'.$p_opcao_ant.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr>');
    SelecaoOpcaoEstrat('<u>O</u>pção estratégica:','O',null,$p_opcao,null,'p_opcao','ATIVO','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_macro\'; document.Form.target=\'\'; document.Form.O.value=\'P\'; document.Form.submit();"');
    ShowHTML('      <tr>');
    SelecaoMacroObjetivo('<u>M</u>acro objetivo:','M',null,$p_macro,$p_opcao,'p_macro','ATIVO','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_cd_programa\'; document.Form.target=\'\'; document.Form.O.value=\'P\'; document.Form.submit();"');
    ShowHTML('      <tr>');
    SelecaoProgramaPPA('<u>P</u>rograma PPA:','P',null,$w_cliente,$w_ano,$p_cd_programa,'p_cd_programa','RELATORIO',null,$w_menu,$p_macro,$p_opcao);
    ShowHTML('      <tr><td colspan=3><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('          <td><b>Selecionada SPI/MP?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecao_mp" value="" checked> Independe');
    ShowHTML('          <td><b>Selecionada SE/SEPPIR?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecao_se" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecao_se" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecao_se" value="" checked> Independe');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Exibir somente indicadores do PPA?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_loa" value="S"> Sim <br><input '.$w_Disabled.' type="radio" name="p_loa" value="" checked> Não');
    ShowHTML('          <td><b>Exibir somente indicadores que não serão cumpridos?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_exequivel" value="N"> Sim <br><input '.$w_Disabled.' type="radio" name="p_exequivel" value="" checked> Não');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Exibir somente programas em atraso?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' type="radio" name="p_atraso" value="" checked> Não');
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
  if ($w_tipo_rel!='WORD') Rodape();
} 
// =========================================================================
// Relatório do Plano Gerencial de Ações
// -------------------------------------------------------------------------
function Rel_Gerencial_Acao() {
  extract($GLOBALS);
  $p_codigo = $_REQUEST['p_codigo'];
  $w_tipo   = strtoupper(trim($_REQUEST['w_tipo']));
  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'') $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
    if ($w_tipo=='WORD') HeaderWord($_REQUEST['orientacao']);
    else                 Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Plano Gerencial - Ações PPA 2004 - 2007 Exercício '.$w_ano.'</TITLE>');
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($w_tipo!='WORD') BodyOpenClean(null);
    ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
    if ($P1==1)     ShowHTML('Relatório Geral por Ação');
    elseif ($P1==2) ShowHTML('Plano Plurianual 2004 - 2007 <BR> Relatório Geral por Ação');
    else            ShowHTML('Plano Gerencial - Ações PPA 2004 - 2007 Exercício '.$w_ano);
    ShowHTML('</FONT><TR><TD ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
    if ($w_tipo!='WORD') {
      ShowHTML('&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      ShowHTML('&nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.'Rel_Gerencial_Acao&R='.$w_pagina.$par.'&O=L&w_tipo=word&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4=1&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
    } 
    ShowHTML('</TD></TR>');
    ShowHTML('</FONT></B></TD></TR></TABLE>');
    $RS = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,substr($p_codigo,0,4),substr($p_codigo,4,4),null,substr($p_codigo,12,17),null,null,null,null,null);
    foreach ($RS as $row){$RS=$row; break;}
    if (Nvl(f($RS,'sq_siw_solicitacao'),'')=='') {
      ScriptOpen('JavaScript');
      ShowHTML('alert(\'Ação não cadastrada!\');');
      ShowHTML('window.close();');
      ScriptClose();
      exit;
    } else {
      $w_chave = f($RS,'sq_siw_solicitacao');
    } 
    // Chama a rotina de visualização dos dados da programa de acordo com o Plano Gerencial
    ShowHTML(VisualAcaoGer($w_chave,$P4));
    if ($w_tipo!='WORD') Rodape();
  } elseif ($O=='P') {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Ações - Relatório Gerencial '.$w_ano.'</TITLE>');
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('p_codigo','Ação PPA','SELECT','1','1','18','1','1');
    ValidateClose();
    ScriptClose();
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=\'document.Form.p_codigo.focus()\';');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center>');
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Acao',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr>');
    SelecaoAcaoPPA('<u>A</u>ção PPA:','A',null,$w_cliente,$w_ano,null,null,null,null,'p_codigo',null,null,null,$w_menu,null,null);
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
// Relatório do Plano Gerencial de Programas
// -------------------------------------------------------------------------
function Rel_Gerencial_Prog() {
  extract($GLOBALS);
  $p_cd_programa    = $_REQUEST['p_cd_programa'];
  $w_tipo           = strtoupper(trim($_REQUEST['w_tipo']));
  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'') $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
    if ($w_tipo=='WORD') HeaderWord($_REQUEST['orientacao']);
    else                 Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Plano Gerencial - Programas PPA 2004 - 2007 Exercício '.$w_ano.'</TITLE>');
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($w_tipo!='WORD') BodyOpenClean(null);
    ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
    if ($P1==1)     ShowHTML('Relatório Geral por Programa');
    elseif ($P1==2) ShowHTML('Plano Plurianual 2004 - 2007 <BR> Relatório Geral por Programa'); 
    else            ShowHTML('Plano Gerencial - Programas PPA 2004 - 2007 Exercício '.$w_ano);
    ShowHTML('</FONT><TR><TD ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
    if ($w_tipo!='WORD') {
      ShowHTML('&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      ShowHTML('&nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.'Rel_Gerencial_Prog&R='.$w_pagina.$par.'&O=L&w_tipo=word&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4=1&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
    } 
    ShowHTML('</TD></TR>');
    ShowHTML('</FONT></B></TD></TR></TABLE>');
    $RS = db_getProgramaPPA_IS::getInstanceOf($dbms,$p_cd_programa,$w_cliente,$w_ano,null,null,null,null);
    foreach ($RS as $row){$RS=$row; break;}
    if (Nvl(f($RS,'sq_siw_solicitacao'),'')=='') {
      ScriptOpen('JavaScript');
      ShowHTML('alert(\'Programa nao cadastrado!\');');
      ShowHTML('window.close();');
      ScriptClose();
      exit;
    } else {
      $w_chave = f($RS,'sq_siw_solicitacao');
    } 
    // Chama a rotina de visualização dos dados da programa de acordo com o Plano Gerencial
    ShowHTML(VisualProgramaGer($w_chave,$P4));
    if ($w_tipo!='WORD') Rodape();
  } elseif ($O=='P') {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Programas - Relatório Gerencial '.$w_ano.'</TITLE>');
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('p_cd_programa','Programa','SELECT','1','1','18','1','1');
    ValidateClose();
    ScriptClose();
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=\'document.Form.p_cd_programa.focus()\';');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center>');
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Programa',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr>');
    SelecaoProgramaPPA('<u>P</u>rograma PPA:','P',null,$w_cliente,$w_ano,$p_cd_programa,'p_cd_programa',null,null,$w_menu,null,null);
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
// Relatório do Plano Gerencial de Tarefas
// -------------------------------------------------------------------------
function Rel_Gerencial_Tarefa() {
  extract($GLOBALS);
  $p_acao   = $_REQUEST['p_acao'];
  $w_chave  = $_REQUEST['w_chave'];
  $w_tipo   = strtoupper(trim($_REQUEST['w_tipo']));
  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'') $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
    if ($w_tipo=='WORD') HeaderWord($_REQUEST['orientacao']);
    else                 Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>'.$conSgSistema.' - Visualização de Tarefa</TITLE>');
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($w_tipo!='WORD') BodyOpenClean(null);
    ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
    ShowHTML('Visualização de Tarefa');
    ShowHTML('</FONT><TR><TD ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
    if ($w_tipo!='WORD') {
      ShowHTML('&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      ShowHTML('&nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.'Rel_Gerencial_Tarefa&R='.$w_pagina.$par.'&O=L&w_tipo=word&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4=1&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
    } 
    ShowHTML('</TD></TR>');
    ShowHTML('</FONT></B></TD></TR></TABLE>');
    ShowHTML('<HR>');
    // Chama a rotina de visualização dos dados da programa de acordo com o Plano Gerencial
    ShowHTML(VisualTarefaGer($w_chave,$P4));
    //if ($w_tipo!='WORD') Rodape();
  } elseif ($O=='P') {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Tarefas - Relatório Gerencial '.$w_ano.'</TITLE>');
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('p_acao','Acao','SELECT','1','1','18','1','1');
    Validate('w_chave','Tarefa','SELECT','1','1','18','1','1');
    ValidateClose();
    ScriptClose();
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($w_troca>'')    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
    else                BodyOpen('onLoad=\'document.Form.p_acao.focus()\';');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center>');
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Tarefa',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="'.$w_troca.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr>');
    SelecaoAcao('Açã<u>o</u>:','O','Selecione a ação da tarefa na relação.',$w_cliente,$w_ano,null,null,null,null,'p_acao','ACAO','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\'P\'; document.Form.w_troca.value=\'w_chave\'; document.Form.target=\'\'; document.Form.submit();"',$p_acao);
    ShowHTML('      <tr>');
    SelecaoTarefa('<u>T</u>arefa:','T',null,$w_cliente,$w_ano,$w_chave,'w_chave',Nvl($p_acao,0),null);
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
// Relatório para acompanhamento das metas das Ações do PPA
// -------------------------------------------------------------------------
function Rel_Metas() {
  extract($GLOBALS);
  $p_sq_unidade = strtoupper(trim($_REQUEST['p_sq_unidade']));
  $p_cd_programa= strtoupper(trim($_REQUEST['p_cd_programa']));
  $p_cd_acao    = strtoupper(trim($_REQUEST['p_acao']));
  $w_tipo_rel   = strtoupper(trim($_REQUEST['w_tipo_rel']));
  $p_preenchida = strtoupper(trim($_REQUEST['p_preenchida']));
  $p_meta_ppa   = strtoupper(trim($_REQUEST['p_meta_ppa']));
  $p_exequivel  = strtoupper(trim($_REQUEST['p_exequivel']));
  $w_cont=0;
  $w_total_ppa=0;
  $w_total_sisplam=0;
  $w_preenchida_ppa=0;
  $w_preenchida_sisplam=0;
  $w_exequivel_ppa=0;
  $w_exequivel_sisplam=0;
  $w_conc_abaixo_ppa=0;
  $w_conc_superior_ppa=0;
  $w_conc_igual_ppa=0;
  $w_conc_abaixo_sisplam=0;
  $w_conc_superior_sisplam=0;
  $w_conc_igual_sisplam=0;
  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'')   $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
  } 
  $RS = db_getSolicMeta_IS::getInstanceOf($dbms,$w_cliente,null,null,'LSTNULL',$w_ano,$p_sq_unidade,$p_cd_programa,$p_cd_acao,$p_preenchida,$p_meta_ppa,$p_exequivel,null,null);
  $RS = SortArray($RS,'cd_programa','asc','cd_acao','asc','cd_unidade','asc','cd_subacao','asc');
  foreach($RS as $row){$RS=$row; break;}
  if ($w_tipo_rel=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_pag=1;
    $w_linha=8;
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('<div align="center">');
    ShowHTML('<table width="95%" border="0" cellspacing="3">');
    ShowHTML('<tr><td colspan="2">');
    ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"></TD><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
    ShowHTML('RELATÓRIO DE CONFERÊNCIA DAS METAS <br> Exercício '.$w_ano);
    ShowHTML('</FONT></TD></TR></TABLE>');
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Relatório Metas - Exercício '.$w_ano.'</TITLE>');
    if (!(strpos('P',$O)===false)) {
      ScriptOpen('JavaScript');
      ValidateOpen('Validacao');
      Validate('p_sq_unidade','Responsável','HIDDEN','','2','60','1','1');
      Validate('p_cd_programa','Programa','HIDDEN','','1','18','1','1');
      Validate('p_cd_acao','Ação','HIDDEN','','1','18','1','1');
      Validate('w_tipo_rel','Tipo de Relatório','SELECT','','1','30','1','1');
      Validate('p_preenchida','Situação Atual da Meta','SELECT','','1','1','1','1');
      Validate('p_meta_ppa','Tipo da Meta','SELECT','','1','1','1','1');
      Validate('p_exequivel','Execução da Meta','SELECT','','1','1','1','1');
      ValidateClose();
      ScriptClose();
    } 
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($O=='L') {
      BodyOpenClean(null);
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');
      ShowHTML('<div align="center">');
      ShowHTML('<table width="95%" border="0" cellspacing="3">');
      ShowHTML('<tr><td colspan="2">');
      ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"></TD><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
      ShowHTML('RELATÓRIO DE CONFERÊNCIA DAS METAS<br> Exercício '.$w_ano);
      ShowHTML('</FONT></B></TD></TR></TABLE>');
    } else {
      BodyOpen('onLoad=\'document.Form.p_cd_programa.focus()\';');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
      ShowHTML('<div align=center><center>');
      ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
      ShowHTML('<HR>');
    } 
  } 
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
    ShowHTML('<tr><td colspan="2"><div align="center">');
    ShowHTML('<table border="0" width="100%">');
    if ($p_sq_unidade>'') {
      $RS1 = db_getUorgData::getInstanceOf($dbms,$p_sq_unidade);
      ShowHTML('<tr><td width="15%"><b>Área de planejamento:</b></td><td>'.f($RS1,'nome').' - '.f($RS1,'sigla').'</td>');
    } else {
      ShowHTML('<tr><td width="15%"><b>Área de planejamento:</b></td><td>Todas</td>');
    } 
    if ($p_cd_programa>'') {
      $RS1 = db_getProgramaPPA_IS::getInstanceOf($dbms,$p_cd_programa,$w_cliente,$w_ano,null,null,null,null);
      foreach($RS1 as $row1){$RS1=$row1; break;}
      ShowHTML('    <td width="7%"><b>Programa:</b></td><td nowrap>'.$p_cd_programa.' - '.f($RS1,'ds_programa').'</td></tr>');
    } else {
      ShowHTML('    <td width="7%"><b>Programa:</b></td><td>Todos</td></tr>');
    } 
    if ($p_cd_acao>'') {
      $RS1 = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,$p_cd_programa,substr($p_cd_acao,4,4),null,null,null,null,null,null,null);
      foreach($RS1 as $row1){$RS1=$row1; break;}
      ShowHTML('<tr valign="top"><td><b>Ação:</b></td><td>'.substr($p_cd_acao,4,4).' - '.f($RS1,'descricao_acao').'</td>');
    } else {
      ShowHTML('<tr valign="top"><td><b>Ação:</b></td><td>Todas</td>');
    } 
    if ($p_preenchida>'') {
      if ($p_preenchida=='S') ShowHTML('    <td><b>Situação atual da meta:</b></td><td>Sim</td></tr>');
      else                    ShowHTML('    <td><b>Situação atual da meta:</b></td><td>Não</td></tr>');
    } else {
      ShowHTML('    <td><b>Situação atual da meta:</b></td><td>Todas</td></tr>');
    } 
    if ($p_meta_ppa>'') {
      if ($p_meta_ppa=='S') ShowHTML('    <td><b>Meta PPA:</b></td><td>Sim</td>');  
      else                  ShowHTML('    <td><b>Meta PPA:</b></td><td>Não</td>');
    } else {
      ShowHTML('    <td><b>Meta PPA:</b></td><td>Todas</td>');
    }
    if ($p_exequivel>'') {
      if ($p_exequivel=='S') ShowHTML('    <td><b>Meta será cumprida:</b></td><td>Sim</td></tr>');
      else                   ShowHTML('    <td><b>Meta será cumprida:</b></td><td>Não</td></tr>');
    } else {
      ShowHTML('    <td><b>Meta será cumprida:</b></td><td>Todas</td></tr>');
    } 
    ShowHTML('</ul></td></tr></table>');
    ShowHTML('</div></td></tr>');
    ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
    ShowHTML('<tr><td colspan="2"><div align="center"><font size="3"><b>RELATÓRIO DE CONFERÊNCIA</b></font></div></td></tr>');
    ShowHTML('<tr><td colspan="2">');
    ShowHTML('      <table border="1" bordercolor="#00000" width="100%">');
    ShowHTML('        <tr>');
    ShowHTML('          <td bgColor="#f0f0f0" rowspan="1" colspan="2"><b>Programas</b></td>');
    ShowHTML('          <td bgColor="#f0f0f0" rowspan="1" colspan="2"><b>Ações</b></td>');
    ShowHTML('          <td bgColor="#f0f0f0" rowspan="1" colspan="2"><b>Unidade</b></td>');
    ShowHTML('          <td bgColor="#f0f0f0" rowspan="1" colspan="7"><b>Metas</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr>');
    ShowHTML('          <td bgColor="#f0f0f0"><b>Cód</b></td>');
    ShowHTML('          <td bgColor="#f0f0f0"><b>Nome</b></td>');
    ShowHTML('          <td bgColor="#f0f0f0"><b>Cód</b></td>');
    ShowHTML('          <td bgColor="#f0f0f0"><b>Nome</b></td>');
    ShowHTML('          <td bgColor="#f0f0f0"><b>Cód</b></td>');
    ShowHTML('          <td bgColor="#f0f0f0"><b>Nome</b></td>');
    ShowHTML('          <td bgColor="#f0f0f0"><b>Meta</b></td>');
    ShowHTML('          <td bgColor="#f0f0f0"><b>PPA</td>');
    ShowHTML('          <td bgColor="#f0f0f0"><b>Será<br>cumprida</td>');
    ShowHTML('          <td bgColor="#f0f0f0"><b>% Executado</td>');
    ShowHTML('          <td bgColor="#f0f0f0"><b>Situação<br>Atual</td>');
    ShowHTML('          <td bgColor="#f0f0f0"><b>Realizar<br>Até</td>');
    ShowHTML('          <td bgColor="#f0f0f0"><b>Fase</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      $w_linha += 1;
      ShowHTML('    <tr><td colspan="13"><div align="center"><b>Não foram encontrados registros</b></div></td></tr>');
    } else {
      // Listagem das metas de acordo com o filtro selecionado na tela de filtragem
      $RS = db_getSolicMeta_IS::getInstanceOf($dbms,$w_cliente,null,null,'LSTNULL',$w_ano,$p_sq_unidade,$p_cd_programa,$p_cd_acao,$p_preenchida,$p_meta_ppa,$p_exequivel,null,null);
      $RS = SortArray($RS,'cd_programa','asc','cd_acao','asc','cd_unidade','asc','cd_subacao','asc');
      foreach($RS as $row) {
        if ($w_linha>19 && $w_tipo_rel=='WORD') {
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
          ShowHTML('</table>');
          ShowHTML('</center></div>');
          ShowHTML('    <br style="page-break-after:always">');
          $w_linha=6;
          $w_pag=$w_pag+1;
          ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
          ShowHTML('Ações do PPA');
          ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
          ShowHTML('<TR><TD COLSPAN="2" ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Página: '.$w_pag.'</B></TD></TR>');
          ShowHTML('</TD></TR>');
          ShowHTML('</FONT></B></TD></TR></TABLE>');
          ShowHTML('<div align=center><center>');
          ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
          ShowHTML('<tr><td colspan="2"><div align="center">');
          ShowHTML('<table border="0" width="100%">');
          if ($p_sq_unidade>'') {
            $RS1 = db_getUorgData::getInstanceOf($dbms,$p_sq_unidade);
            ShowHTML('<tr><td width="15%"><b>Área de planejamento:</b></td><td>'.f($RS1,'nome').' - '.f($RS1,'sigla').'</td>');
          } else {
            ShowHTML('<tr><td width="15%"><b>Área de planejamento:</b></td><td>Todas</td>');
          } 
          if ($p_cd_programa>'') {
            $RS1 = db_getProgramaPPA_IS::getInstanceOf($dbms,$p_cd_programa,$w_cliente,$w_ano,null,null,null,null);
            foreach($RS1 as $row1){$RS1=$row1; break;}
            ShowHTML('    <td width="7%"><b>Programa:</b></td><td nowrap>'.$p_cd_programa.' - '.f($RS1,'ds_programa').'</td></tr>');
          } else {
            ShowHTML('    <td width="7%"><b>Programa:</b></td><td>Todos</td></tr>');
          } 
          if ($p_cd_acao>'') {
            $RS1 = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,$p_cd_programa,substr($p_cd_acao,4,4),null,null,null,null,null,null,null);
            foreach($RS1 as $row1){$RS1=$row1; break;}
            ShowHTML('<tr valign="top"><td><b>Ação:</b></td><td>'.substr($p_cd_acao,4,4).' - '.f($RS1,'descricao_acao').'</td>');
          } else {
            ShowHTML('<tr valign="top"><td><b>Ação:</b></td><td>Todas</td>');
          } 
          if ($p_preenchida>'') {
            if ($p_preenchida=='S') ShowHTML('    <td><b>Situação atual da meta:</b></td><td>Sim</td></tr>');
            else                    ShowHTML('    <td><b>Situação atual da meta:</b></td><td>Não</td></tr>');
          } else {
            ShowHTML('    <td><b>Situação atual da meta:</b></td><td>Todas</td></tr>');
          }
          if ($p_meta_ppa>'') {
            if ($p_meta_ppa=='S') ShowHTML('    <td><b>Meta PPA:</b></td><td>Sim</td>');
            else                  ShowHTML('    <td><b>Meta PPA:</b></td><td>Não</td>');
          } else {
            ShowHTML('    <td><b>Meta PPA:</b></td><td>Todas</td>');
          }
          if ($p_exequivel>'') {
            if ($p_exequivel=='S')  ShowHTML('    <td><b>Meta será cumprida:</b></td><td>Sim</td></tr>');
            else                    ShowHTML('    <td><b>Meta será cumprida:</b></td><td>Não</td></tr>');
          } else {
            ShowHTML('    <td><b>Meta será cumprida:</b></td><td>Todas</td></tr>');
          }
          ShowHTML('</ul></td></tr></table>');
          ShowHTML('</div></td></tr>');
          ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
          ShowHTML('<tr><td align="center" colspan="2">');
          ShowHTML('      <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
          ShowHTML('          <td rowspan="1" colspan="2"><b>Programas</td>');
          ShowHTML('          <td rowspan="1" colspan="2"><b>Ações</td>');
          ShowHTML('          <td rowspan="1" colspan="2"><b>Unidade</td>');
          ShowHTML('          <td rowspan="1" colspan="7"><b>Metas</td>');
          ShowHTML('        </tr>');
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
          ShowHTML('          <td><b>Cód</td>');
          ShowHTML('          <td><b>Nome</td>');
          ShowHTML('          <td><b>Cód</td>');
          ShowHTML('          <td><b>Nome</td>');
          ShowHTML('          <td><b>Cód</td>');
          ShowHTML('          <td><b>Nome</td>');
          ShowHTML('          <td bgColor="#f0f0f0"><b>Meta</b></td>');
          ShowHTML('          <td bgColor="#f0f0f0"><b>PPA</td>');
          ShowHTML('          <td bgColor="#f0f0f0"><b>Será<br>cumprida</td>');
          ShowHTML('          <td bgColor="#f0f0f0"><b>% Executado</td>');
          ShowHTML('          <td bgColor="#f0f0f0"><b>Situação<br>Atual</td>');
          ShowHTML('          <td bgColor="#f0f0f0"><b>Realizar<br>Até</td>');
          ShowHTML('          <td bgColor="#f0f0f0"><b>Fase</td>');
          ShowHTML('        </tr>');
        } 
        //Inicio da montagem da lista das ações e metas de acordo com o filtro
        if (f($row,'cd_programa')!=$w_programa_atual) {
          ShowHTML(' <tr valign="top">');
          ShowHTML('   <td><b>'.f($row,'cd_programa').'</td>');
          ShowHTML('   <td><b>'.f($row,'descricao_programa').'</td>');
        } else {
          ShowHTML(' <tr>');
          ShowHTML('   <td><b>&nbsp;</td>');
          ShowHTML('   <td><b>&nbsp;</td>');
        } 
        $w_linha += 1;
        if (f($row,'cd_acao')!=$w_acao_atual) {
          ShowHTML('      <td><b>'.f($row,'cd_acao').'</td>');
          if ($w_tipo_rel=='WORD' || f($row,'sq_siw_solicitacao')=='')   ShowHTML('   <td><b>'.f($row,'descricao_acao').'</td>');
          else                                                          ShowHTML('   <td><b><A class="HL" HREF="'.$w_dir.'acao.php?par=Visual&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" TARGET="VisualAcao" title="Exibe as informações da ação.">'.f($row,'descricao_acao').'</a></td>');
        } else {
          ShowHTML('      <td><b>&nbsp;</td>');
          ShowHTML('      <td><b>&nbsp;</td>');
        } 
        if (f($row,'cd_unidade')!=$w_unidade_atual) {
          ShowHTML('      <td><b>'.f($row,'cd_unidade').'</td>');
          ShowHTML('      <td><b>'.f($row,'descricao_unidade').'</td>');
        } else {
          ShowHTML('      <td><b>&nbsp;</td>');
          ShowHTML('      <td><b>&nbsp;</td>');
        } 
        if ($w_tipo_rel=='WORD') ShowHTML('<td>'.f($row,'titulo').'</td>');
        else                     ShowHTML('<td><A class="HL" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS($w_dir,'acao.php?par=AtualizaMeta&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_chave_aux='.f($row,'sq_meta').'&w_tipo=Volta&P1=10&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\',\'Meta\',\'width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no\'); return false;" title="Clique para exibir os dados!">'.f($row,'titulo').'</A></td>');
        if (Nvl(f($row,'cd_subacao'),'')>'') {
          $w_total_ppa=$w_total_ppa+1;
          ShowHTML('   <td><div align="center">Sim</div></td>');
        } else {
          $w_total_sisplam += 1;
          ShowHTML('   <td><div align="center">Não</div></td>');
        } 
        if (Nvl(f($row,'exequivel'),'')=='S') {
          if (Nvl(f($row,'cd_subacao'),'')>'') $w_exequivel_ppa += 1;
          else                                $w_exequivel_sisplam += 1;
          ShowHTML('   <td><div align="center">Sim</div></td>');
        } else {
          ShowHTML('   <td><div align="center">Não</div></td>');
        }
        ShowHTML('   <td><div align="right">'.Nvl(f($row,'perc_conclusao'),0).'%</div></td>');
        if (Nvl(f($row,'perc_conclusao'),0)==100) {
          if (Nvl(f($row,'cd_subacao'),'')>'') $w_conc_igual_ppa += 1;
          else                                $w_conc_igual_sisplam += 1;
        } elseif (Nvl(f($row,'perc_conclusao'),0)>100) {
          if (Nvl(f($row,'cd_subacao'),'')>'')   $w_conc_superior_ppa += 1;
          else                                   $w_conc_superior_sisplam += 1;
        } else {
          if (Nvl(f($row,'cd_subacao'),'')>'') $w_conc_abaixo_ppa += 1;
          else                                $w_conc_abaixo_sisplam += 1;
        } 
        ShowHTML('   <td><div align="justify">'.Nvl(f($row,'situacao_atual'),'---').'</div></td>');
        ShowHTML('   <td><div align="center">'.FormataDataEdicao(f($row,'fim_previsto')).'</div></td>');
        ShowHTML('   <td>'.f($row,'descricao_tramite').'</td>');
        ShowHTML(' </tr>');
        if (Nvl(trim(f($row,'descricao')),'')>'') {
          if (Nvl(f($row,'cd_subacao'),'')>'') $w_preenchida_ppa += 1;
          else                                $w_preenchida_sisplam += 1;
        } 
        $w_programa_atual   = f($row,'cd_programa');
        $w_acao_atual       = f($row,'cd_acao');
        $w_unidade_atual    = f($row,'cd_unidade');
        $w_cont            += 1;
      } 
    } 
    ShowHTML('    </table>');
    ShowHTML('  </div></td>');
    ShowHTML('</tr>');
    ShowHTML('   <tr><td colspan="2"><br>');
    ShowHTML('  <tr><td colspan="2"><div align="center"><font size="3"><b>QUADRO DE RESUMO</b></font></div></td></tr>');
    ShowHTML('   <tr><td colspan="2"><div align="center">');
    ShowHTML('     <table width=100%  border="1" bordercolor="#00000">');
    ShowHTML('       <tr><td bgColor="#f0f0f0" colspan="2"><div align="center"><b>Situação</b></div></td>');
    ShowHTML('           <td width="12%" bgColor="#f0f0f0" colspan="2"><div align="center"><b>PPA</b></div></td>');
    ShowHTML('           <td width="13%" bgColor="#f0f0f0" colspan="2"><div align="center"><b>SISPLAM</b></div></td>');
    ShowHTML('           <td width="13%" bgColor="#f0f0f0" colspan="2"><div align="center"><b>TOTAL</b></div></td>');
    ShowHTML('       </tr>');
    ShowHTML('       <tr><td bgColor="#f0f0f0" colspan="2"><div align="center">&nbsp;</div></td>');
    ShowHTML('           <td bgColor="#f0f0f0"><div align="center"><b>QTD</b></div></td>');
    ShowHTML('           <td bgColor="#f0f0f0"><div align="center"><b>%</b></div></td>');
    ShowHTML('           <td bgColor="#f0f0f0"><div align="center"><b>QTD</b></div></td>');
    ShowHTML('           <td bgColor="#f0f0f0"><div align="center"><b>%</b></div></td>');
    ShowHTML('           <td bgColor="#f0f0f0"><div align="center"><b>QTD</b></div></td>');
    ShowHTML('           <td bgColor="#f0f0f0"><div align="center"><b>%</b></div></td>');
    ShowHTML('       </tr>');
    //Quantitativo executado
    ShowHTML('       <tr><td bgColor="#f0f0f0" rowspan="3" valign="top"><b>Quantitativo Executado</b></td>');
    ShowHTML('           <td align="right">Abaixo de 100%</td>');
    ShowHTML('         <td><div align="right">'.$w_conc_abaixo_ppa.'&nbsp;</div></td>');
    if ($w_total_ppa>0) ShowHTML('         <td><div align="right">'.round((($w_conc_abaixo_ppa/$w_total_ppa)*100),0).'%&nbsp;</td>');
    else                ShowHTML('         <td><div align="right">0%&nbsp;</td>');
    ShowHTML('         <td><div align="right">'.$w_conc_abaixo_sisplam.'&nbsp;</div></td>');
    if ($w_total_sisplam>0) ShowHTML('         <td><div align="right">'.round((($w_conc_abaixo_sisplam/$w_total_sisplam)*100),0).'%&nbsp;</td>');
    else                    ShowHTML('         <td><div align="right">0%&nbsp;</td>');
    ShowHTML('<td><div align="right">'.($w_conc_abaixo_sisplam + $w_conc_abaixo_ppa).'&nbsp;</div></td>');
    if ($w_cont>0) ShowHTML ('<td><div align="right">'.round(((($w_conc_abaixo_sisplam + $w_conc_abaixo_ppa) /$w_cont)*100),0).'%&nbsp;</td>');
    else           ShowHTML ('<td><div align="right">0%&nbsp;</td>');
    ShowHTML('<tr><td align="right">Acima de 100%</td>');
    ShowHTML('<td><div align="right">'.$w_conc_superior_ppa.'&nbsp;</div></td>');
    if ($w_total_ppa>0) ShowHTML('<td><div align="right">'.round((($w_conc_superior_ppa/$w_total_ppa)*100),0).'%&nbsp;</td>');
    else                     ShowHTML('<td><div align="right">0%&nbsp;</td>');
    ShowHTML('<td><div align="right">'.$w_conc_superior_sisplam.'&nbsp;</div></td>');
    if ($w_total_sisplam>0) ShowHTML('<td><div align="right">'.round((($w_conc_superior_sisplam/$w_total_sisplam)*100),0).'%&nbsp;</td>');
    else                    ShowHTML('<td><div align="right">0%&nbsp;</td>');
    ShowHTML('<td><div align="right">'.($w_conc_superior_sisplam + $w_conc_superior_ppa).'&nbsp;</div></td>');
    if ($w_cont>0)ShowHTML('<td><div align="right">'.round(((($w_conc_superior_sisplam + $w_conc_superior_ppa)/$w_cont)*100),0).'%&nbsp;</td>');
    else ShowHTML('<td><div align="right">0%&nbsp;</td>');
    ShowHTML('<tr><td align="right">Em 100%</td>');
    ShowHTML('<td><div align="right">'.$w_conc_igual_ppa.'&nbsp;</div></td>');
    if ($w_total_ppa>0)ShowHTML('<td><div align="right">'.round((($w_conc_igual_ppa/$w_total_ppa)*100),0).'%&nbsp;</td>');
    else               ShowHTML('<td><div align="right">0%&nbsp;</td>');
    ShowHTML('<td><div align="right">'.$w_conc_igual_sisplam.'&nbsp;</div></td>');
    if ($w_total_sisplam>0) ShowHTML('<td><div align="right">'.round((($w_conc_igual_sisplam/$w_total_sisplam)*100),0).'%&nbsp;</td>');
    else                    ShowHTML('<td><div align="right">0%&nbsp;</td>');
    ShowHTML('<td><div align="right">'.($w_conc_igual_sisplam + $w_conc_igual_ppa).'&nbsp;</div></td>');
    if ($w_cont>0) ShowHTML('<td><div align="right">'.round(((($w_conc_igual_sisplam + $w_conc_igual_ppa)/$w_cont)*100),0).'%&nbsp;</td>');
    else           ShowHTML('<td><div align="right">0%&nbsp;</td>');
    //Em Andamento  
    ShowHTML('<tr><td bgColor="#f0f0f0"rowspan="2"valign="top"><b>Em Andamento</b></td>');
    ShowHTML('<td align="right">Não será cumprida</td>');
    ShowHTML('<td><div align="right">'.($w_total_ppa - $w_exequivel_ppa).'&nbsp;</div></td>');
    if ($w_total_ppa>0) ShowHTML('<td><div align="right">'.round(((($w_total_ppa - $w_exequivel_ppa)/$w_total_ppa)*100),0).'%&nbsp;</td>');
    else                ShowHTML('<td><div align="right">0%&nbsp;</td>');
    ShowHTML('<td><div align="right"><fontsize="1">'.($w_total_sisplam - $w_exequivel_sisplam).'&nbsp;</div></td>');
    if ($w_total_sisplam>0) ShowHTML('<td><div align="right">'.round(((($w_total_sisplam - $w_exequivel_sisplam)/$w_total_sisplam)*100),0).'%&nbsp;</td>');
    else                    ShowHTML('<td><div align="right">0%&nbsp;</td>');
    ShowHTML('<td><div align="right">'.(($w_total_sisplam - $w_exequivel_sisplam) + ($w_total_ppa - $w_exequivel_ppa)).'&nbsp;</div></td>');
    if ($w_cont>0) ShowHTML('<td><div align="right">'.round((((($w_total_sisplam - $w_exequivel_sisplam) + ($w_total_ppa - $w_exequivel_ppa))/$w_cont)*100),0).'%&nbsp;</td>');
    else           ShowHTML('<td><div align="right">0%&nbsp;</td>');
    ShowHTML('<tr><td align="right">Será cumprida</td>');
    ShowHTML('<td><div align="right">'.$w_exequivel_ppa.'&nbsp;</div></td>');
    if ($w_total_ppa>0) ShowHTML('<td><div align="right">'.round((($w_exequivel_ppa/$w_total_ppa)*100),0).'%&nbsp;</td>');
    else                ShowHTML('<td><div align="right">0%&nbsp;</td>');
    ShowHTML('<td><div align="right">'.$w_exequivel_sisplam.'&nbsp;</div></td>');
    if ($w_total_sisplam>0) ShowHTML('<td><div align="right">'.round((($w_exequivel_sisplam/$w_total_sisplam)*100),0).'%&nbsp;</td>');
    else                    ShowHTML('<td><div align="right">0%&nbsp;</td>');
    ShowHTML('<td><div align="right">'.($w_exequivel_ppa + $w_exequivel_sisplam).'&nbsp;</div></td>');
    if ($w_cont>0)  ShowHTML('<td><div align="right">'.round(((($w_exequivel_sisplam + $w_exequivel_ppa)/$w_cont)*100),0).'%&nbsp;</td>');
    else            ShowHTML('<td><div align="right">0%&nbsp;</td>');
    //Preenchimento
    ShowHTML('<tr><td bgColor="#f0f0f0"rowspan="2"valign="top"><b>Preenchimento</b></td>');
    ShowHTML('<td align="right">Preenchida</td>');
    ShowHTML('<td><div align="right"><fontsize="1">'.$w_preenchida_ppa.'&nbsp;</div></td>');
    if ($w_total_ppa>0) ShowHTML('<td><div align="right">'.round((($w_preenchida_ppa/$w_total_ppa)*100),0).'%&nbsp;</td>');
    else                ShowHTML('<td><div align="right">0%&nbsp;</td>');
    ShowHTML('<td><div align="right">'.$w_preenchida_sisplam.'&nbsp;</div></td>');
    if ($w_total_sisplam>0) ShowHTML('<td><div align="right">'.round((($w_preenchida_sisplam/$w_total_sisplam)*100),0).'%&nbsp;</td>');
    else                    ShowHTML('<td><div align="right">0%&nbsp;</td>');
    ShowHTML('<td><div align="right">'.($w_preenchida_ppa + $w_preenchida_sisplam).'&nbsp;</div></td>');
    if ($w_cont>0)  ShowHTML('<td><div align="right">'.round(((($w_preenchida_sisplam + $w_preenchida_ppa)/$w_cont)*100),0).'%&nbsp;</td>');
    else            ShowHTML('<td><div align="right">0%&nbsp;</td>');
    ShowHTML('<tr><td align="right">Não preenchida</td>');
    ShowHTML('<td><div align="right">'.($w_total_ppa - $w_preenchida_ppa).'&nbsp;</div></td>');
        if ($w_total_ppa>0) ShowHTML('<td><div align="right">'.round(((($w_total_ppa - $w_preenchida_ppa)/$w_total_ppa)*100),0).'%&nbsp;</td>');
    else                ShowHTML('<td><div align="right">0%&nbsp;</td>');
    ShowHTML('<td><div align="right">'.($w_total_sisplam - $w_preenchida_sisplam).'&nbsp;</div></td>');
    if ($w_total_sisplam>0) ShowHTML('<td><div align="right">'.round(((($w_total_sisplam - $w_preenchida_sisplam)/$w_total_sisplam)*100),0).'%&nbsp;</td>');
    else                    ShowHTML('<td><div align="right">0%&nbsp;</td>');
    ShowHTML('<td><div align="right">'.(($w_total_ppa - $w_preenchida_ppa) + ($w_total_sisplam - $w_preenchida_sisplam)).'&nbsp;</div></td>');
    if ($w_cont>0) ShowHTML('<td><div align="right">'.round((((($w_total_sisplam - $w_preenchida_sisplam) + ($w_total_ppa - $w_preenchida_ppa)) /$w_cont)*100),0).'%&nbsp;</td>');
    else           ShowHTML('<td><div align="right">0%&nbsp;</td>');
    //Total
    ShowHTML('<tr><td bgColor="#f0f0f0"colspan="2"><div align="right"><fontsize="1"><b>Total</b></div></td>');
    ShowHTML('<td bgColor="#f0f0f0"><div align="right"><b>'.$w_total_ppa.'&nbsp;</b></div></td>');
    if (Nvl($w_cont,0)>0)ShowHTML('<td bgColor="#f0f0f0"><div align="right"><b>'.round(($w_total_ppa/$w_cont)*100,0).'%&nbsp;</b></div></td>');
    else                 ShowHTML('<td bgColor="#f0f0f0"><div align="right"><b>0%&nbsp;</b></div></td>');
    ShowHTML('<td bgColor="#f0f0f0"><div align="right"><b>'.$w_total_sisplam.'&nbsp;</b></div></td>');
    if (Nvl($w_cont,0)>0)ShowHTML('<td bgColor="#f0f0f0"><div align="right"><b>'.round(($w_total_sisplam/$w_cont)*100,0).'%&nbsp;</b></div></td>');
    else                 ShowHTML('<td bgColor="#f0f0f0"><div align="right"><b>0%&nbsp;</b></div></td>');
    ShowHTML('<td bgColor="#f0f0f0"><div align="right"><b>'.$w_cont.'&nbsp;</b></div></td>');
    if (Nvl($w_cont,0)>0) ShowHTML('<td bgColor="#f0f0f0"><div align="right"><b>'.round((($w_total_ppa+$w_total_sisplam)/$w_cont)*100,2).'%&nbsp;</b></div></td>');
    else                  ShowHTML('<td bgColor="#f0f0f0"><div align="right"><b>0%&nbsp;</b></div></td>');
    ShowHTML('</tr>');
    ShowHTML('</table></div></td></tr>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Acao',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="p_opcao_ant" value="'.$p_opcao_ant.'">');    
    ShowHTML('<tr><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    SelecaoUnidade('Á<U>r</U>ea planejamento:','R',null,$p_sq_unidade,null,'p_sq_unidade',null,null);
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    SelecaoProgramaPPA('<u>P</u>rograma PPA:','P',null,$w_cliente,$w_ano,$p_cd_programa,'p_cd_programa','RELATORIO','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_cd_acao\'; document.Form.target=\'\'; document.Form.O.value=\'P\'; document.Form.submit();"',$w_menu,null,null);
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    SelecaoAcaoPPA('<u>A</u>ção PPA:','A',null,$w_cliente,$w_ano,$p_cd_programa,null,null,null,'p_cd_acao',null,null,null,$w_menu,null,null);
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('        <td><b><u>T</u>ipo de relatório:</b><br><SELECT ACCESSKEY="T" CLASS="STS" NAME="w_tipo_rel" '.$w_Disabled.'>');
    if (nvl($w_tipo_rel,'-')=='Word') {
      ShowHTML('          <option value="">Consulta na Tela');
      ShowHTML('          <option value="Word" SELECTED>Documento Word');
    } else {
      ShowHTML('          <option value="" SELECTED>Consulta na Tela');
      ShowHTML('          <option value="Word">Documento Word');
    } 
    ShowHTML('          </select></td><tr>');
    ShowHTML('    </table></td></tr>');
    ShowHTML('<tr><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr bgcolor="'.$conTrAlternateBgColor.'"><td colspan="2"><b>OPÇÕES DE CONSULTA</b></td></tr>');
    ShowHTML('      <tr><td width="25%" bgcolor="'.$conTrBgColor.'"><b><u>S</u>ituação Atual da Meta:</b>');
    ShowHTML('          <td bgcolor="'.$conTrBgColor.'"><SELECT ACCESSKEY="S" CLASS="STS" NAME="p_preenchida">');
    if (nvl($p_preenchida,'-')!='S' || nvl($p_preenchida,'-')!='N') {
      ShowHTML('          <option value="" SELECTED>Todas');
      ShowHTML('          <option value="S">Preenchida');
      ShowHTML('          <option value="N">Não preenchida');
    } elseif (nvl($p_preenchida,'-')=='S') {
      ShowHTML('          <option value="">Todas');
      ShowHTML('          <option value="S" SELECTED>Preenchida');
      ShowHTML('          <option value="N">Não preenchida');
    } elseif (nvl($p_preenchida,'-')=='N') {
      ShowHTML('          <option value="">Todas');
      ShowHTML('          <option value="S">Preenchida');
      ShowHTML('          <option value="N" SELECTED>Não preenchida');
    } 
    ShowHTML('          </select></td></tr>');
    ShowHTML('      <tr><td bgcolor="'.$conTrBgColor.'"><b>T<u>i</u>po de Meta:</b>');
    ShowHTML('          <td bgcolor="'.$conTrBgColor.'"><SELECT ACCESSKEY="I" CLASS="STS" NAME="p_meta_ppa">');
    if (nvl($p_meta_ppa,'-')!='S' || nvl($p_meta_ppa,'-')!='N') {
      ShowHTML('          <option value="" SELECTED>Todas');
      ShowHTML('          <option value="S">Meta PPA');
      ShowHTML('          <option value="N">Meta não PPA');
    } elseif (nvl($p_meta_ppa,'-')=='S') {
      ShowHTML('          <option value="">Todas');
      ShowHTML('          <option value="S" SELECTED>Meta PPA');
      ShowHTML('          <option value="N">Meta não PPA');
    } elseif (nvl($p_meta_ppa,'-')=='N') {
      ShowHTML('          <option value="">Todas');
      ShowHTML('          <option value="S">Meta PPA');
      ShowHTML('          <option value="N" SELECTED>Meta não PPA');
    } 
    ShowHTML('          </select></td></tr>');
    ShowHTML('      <tr><td bgcolor="'.$conTrBgColor.'"><b><u>E</u>xecução da Meta:</b>');
    ShowHTML('          <td bgcolor="'.$conTrBgColor.'"><SELECT ACCESSKEY="E" CLASS="STS" NAME="p_exequivel">');
    if (nvl($p_exequivel,'-')!='S' || nvl($p_exequivel,'-')!='N') {
      ShowHTML('          <option value="" SELECTED>Todas');
      ShowHTML('          <option value="S">Metas que serão cumpridas');
      ShowHTML('          <option value="N">Metas que não serão cumpridas');
    } elseif (nvl($p_exequivel,'-')=='S') {
      ShowHTML('          <option value="">Todas');
      ShowHTML('          <option value="S" SELECTED>Metas que serão cumpridas');
      ShowHTML('          <option value="N">Metas que não serão cumpridas');
    } elseif (nvl($p_exequivel,'-')=='N') {
      ShowHTML('          <option value="">Todas');
      ShowHTML('          <option value="S">Metas que serão cumpridas');
      ShowHTML('          <option value="N" SELECTED>Metas que não serão cumpridas');
    } 
    ShowHTML('          </select></td></tr>');
    ShowHTML('    </table></td></tr>');
    ShowHTML('    <table width="100%" border="0">');
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
  ShowHTML('</DIV>');
  ShowHTML('</BODY>');
  ShowHTML('</HTML>');
} 

// =========================================================================
// Relatório detalhado das tarefas
// -------------------------------------------------------------------------
function Rel_Det_Tarefa() {
  extract($GLOBALS);
  $p_sq_unidade     = strtoupper(trim($_REQUEST['p_sq_unidade']));
  $p_cd_programa    = strtoupper(trim($_REQUEST['p_cd_programa']));
  $p_cd_acao        = strtoupper(trim($_REQUEST['p_cd_acao']));
  $w_tipo_rel       = strtoupper(trim($_REQUEST['w_tipo_rel']));
  $w_identificacao  = strtoupper(trim($_REQUEST['w_identificacao']));
  $w_responsavel    = strtoupper(trim($_REQUEST['w_responsavel']));
  $w_anexo          = strtoupper(trim($_REQUEST['w_anexo']));
  $w_ocorrencia     = strtoupper(trim($_REQUEST['w_ocorrencia']));
  $w_cont=0;
  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'') $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
  } 
  $RS1 = db_getLinkData::getInstanceOf($dbms,RetornaCliente(),'ISTCAD');
  $RS = db_getSolicList_IS::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,'ISTCAD',4,
          null,null,null,null,null,null,
          $p_sq_unidade,null,null,null,
          null,null,null,null,null,null,null,
          null,null,null,null,null,null,$p_cd_programa,substr($p_cd_acao,4,4),null,null,$w_ano);
  $RS = SortArray($RS,'ordem','asc','phpdt_fim','asc','prioridade','asc');
  if ($w_tipo_rel=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_pag=1;
    $w_linha=8;
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('<div align="center">');
    ShowHTML('<table width="95%" border="0" cellspacing="3">');
    ShowHTML('<tr><td colspan="2">');
    ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"></TD><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
    ShowHTML('RELATÓRIO DETALHADO DE TAREFAS <br> Exercício '.$w_ano);
    ShowHTML('</FONT></TD></TR></TABLE>');
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Relatório de Tarefas - Exercício '.$w_ano.'</TITLE>');
    if (!(strpos('P',$O)===false)) {
      ScriptOpen('JavaScript');
      ValidateOpen('Validacao');
      Validate('p_sq_unidade','Responsável','HIDDEN','','2','60','1','1');
      Validate('p_cd_programa','Programa','HIDDEN','','1','18','1','1');
      Validate('p_cd_acao','Ação','HIDDEN','','1','18','1','1');
      ValidateClose();
      ShowHTML('  function MarcaTodosBloco() {');
      ShowHTML('    if (document.Form.w_marca_bloco.checked==true) {');
      ShowHTML('         document.Form.w_identificacao.checked=true;');
      ShowHTML('         document.Form.w_responsavel.checked=true;');
      ShowHTML('         document.Form.w_anexo.checked=true;');
      ShowHTML('         document.Form.w_ocorrencia.checked=true;');
      ShowHTML('    } else { ');
      ShowHTML('         document.Form.w_identificacao.checked=false;');
      ShowHTML('         document.Form.w_responsavel.checked=false;');
      ShowHTML('         document.Form.w_anexo.checked=false;');
      ShowHTML('         document.Form.w_ocorrencia.checked=false;');
      ShowHTML('    } ');
      ShowHTML('  }');
      ScriptClose();
    } 
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($O=='L') {
      BodyOpenClean(null);
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');
      ShowHTML('<div align="center">');
      ShowHTML('<table width="95%" border="0" cellspacing="3">');
      ShowHTML('<tr><td colspan="2">');
      ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"></TD><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
      ShowHTML('RELATÓRIO DETALHADO DE TAREFAS <br> Exercício '.$w_ano);
      ShowHTML('</FONT></B></TD></TR></TABLE>');
    } else {
      BodyOpen('onLoad=\'document.Form.p_cd_programa.focus()\';');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
      ShowHTML('<div align=center><center>');
      ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
      ShowHTML('<HR>');
    }
  } 
  if ($O=='L') {
    ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
    ShowHTML('<tr><td colspan="2"><div align="center">');
    ShowHTML('<table border="0" width="100%">');
    if ($p_sq_unidade>'') {
      $RS1 = db_getUorgData::getInstanceOf($dbms,$p_sq_unidade);
      ShowHTML('<tr><td width="15%"><b>Área de planejamento:</b></td><td>'.f($RS1,'nome').' - '.f($RS1,'sigla').'</td>');
    } else {
      ShowHTML('<tr><td width="15%"><b>Área de planejamento:</b></td><td>Todas</td>');
    } 
    if ($p_cd_programa>'') {
      $RS1 = db_getProgramaPPA_IS::getInstanceOf($dbms,$p_cd_programa,$w_cliente,$w_ano,null,null,null,null);
      foreach($RS1 as $row1){$RS1=$row1; break;}
      ShowHTML('    <td width="7%"><b>Programa:</b></td><td nowrap>'.$p_cd_programa.' - '.f($RS1,'ds_programa').'</td></tr>');
    } else {
      ShowHTML('    <td width="7%"><b>Programa:</b></td><td>Todos</td></tr>');
    } 
    if ($p_cd_acao>'') {
      $RS1 = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,$p_cd_programa,substr($p_cd_acao,4,4),null,null,null,null,null,null,null);
      foreach($RS1 as $row1){$RS1=$row1; break;}
      ShowHTML('<tr valign="top"><td><b>Ação:</b></td><td>'.substr($p_cd_acao,4,4).' - '.f($RS1,'descricao_acao').'</td>');
    } else {
      ShowHTML('<tr valign="top"><td><b>Ação:</b></td><td>Todas</td>');
    } 
    ShowHTML('</ul></td></tr></table>');
    ShowHTML('</div></td></tr>');
    ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
    ShowHTML('<tr><td colspan="2"><div align="center"><font size="3"><b>RELATÓRIO DE TAREFAS</b></font></div></td></tr>');
    if (count($RS)<=0) {
      $w_linha += 1;
      ShowHTML('    <tr><td colspan="13"><div align="center"><font size="3" color="red"><b><br>Nenhuma tarefa encontrada</b></font></div></td></tr>');
    } else {
      foreach($RS as $row) {
        if ($w_linha>19 && $w_tipo_rel=='WORD') {
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
          ShowHTML('</table>');
          ShowHTML('</center></div>');
          ShowHTML('    <br style="page-break-after:always">');
          $w_linha=6;
          $w_pag=$w_pag+1;
          ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
          ShowHTML('Ações do PPA');
          ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
          ShowHTML('<TR><TD COLSPAN="2" ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Página: '.$w_pag.'</B></TD></TR>');
          ShowHTML('</TD></TR>');
          ShowHTML('</FONT></B></TD></TR></TABLE>');
          ShowHTML('<div align=center><center>');
          ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
          ShowHTML('<tr><td colspan="2"><div align="center">');
          ShowHTML('<table border="0" width="100%">');
          if ($p_sq_unidade>'')   ShowHTML('<tr><td width="20%"><b>Área de planejamento:</b></td><td>'.f($row,'sg_segor').'</td>');
          else                    ShowHTML('<tr><td width="20%"><b>Área de planejamento:</b></td><td>Todas</td>');
          if ($p_cd_programa>'')  ShowHTML('    <td width="20%"><b>Programa:</b></td><td>'.f($row,'descricao_programa').'</td></tr>');
          else                    ShowHTML('    <td width="20%"><b>Programa:</b></td><td>Todos</td></tr>');
          if ($p_cd_acao>'')      ShowHTML('<tr><td><b>Ação:</b></td><td>'.f($row,'descricao_acao').'</td>');
          else                    ShowHTML('<tr><td><b>Ação:</b></td><td>Todas</td>');
          ShowHTML('</ul></td></tr></table>');
          ShowHTML('</div></td></tr>');
        } 
        if ($w_tipo_rel=='WORD') {
          ShowHTML(VisualTarefa(f($row,'sq_siw_solicitacao'),'',$w_usuario,1,$w_identificacao,$w_identificacao,$w_responsavel,$w_anexo,$w_ocorrencia,'nao'));
          ShowHTML('<tr><td colspan="2"><div align="center"><BR></div></td></tr>');
        } else {
          ShowHTML(VisualTarefa(f($row,'sq_siw_solicitacao'),'',$w_usuario,0,$w_identificacao,$w_identificacao,$w_responsavel,$w_anexo,$w_ocorrencia,'nao'));
          ShowHTML('<tr><td colspan="2"><div align="center"><BR></div></td></tr>');
        } 
      } 
    } 
    ShowHTML('   <tr><td colspan="2"><font size="2"><b>DADOS DA CONSULTA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
    ShowHTML('   <tr><td><b>Consulta Realizada por:</b></td>');
    ShowHTML('       <td>'.$_SESSION['NOME_RESUMIDO'].'</td></tr>');
    ShowHTML('   <tr><td><b>Data da Consulta:</b></td>');
    ShowHTML('       <td>'.FormataDataEdicao(time(),3).'</td></tr>');
    ShowHTML('    </table>');
    ShowHTML('  </div></td>');
    ShowHTML('</tr>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Acao',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    SelecaoUnidade('Á<U>r</U>ea planejamento:','R',null,$p_sq_unidade,null,'p_sq_unidade',null,'onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_sq_unidade\'; document.Form.target=\'\'; document.Form.O.value=\'P\'; document.Form.submit();"');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    SelecaoProgramaPPA('<u>P</u>rograma PPA:','P',null,$w_cliente,$w_ano,$p_cd_programa,'p_cd_programa','RELATORIO','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_cd_programa\'; document.Form.target=\'\'; document.Form.O.value=\'P\'; document.Form.submit();"',$w_menu,null,null);
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    SelecaoAcaoPPA('<u>A</u>ção PPA:','A',null,$w_cliente,$w_ano,$p_cd_programa,null,null,null,'p_cd_acao',null,null,null,$w_menu,null,null);
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('        <td><b><u>T</u>ipo de relatório:</b><br><SELECT ACCESSKEY="T" CLASS="STS" NAME="w_tipo_rel" '.$w_Disabled.'>');
    if (nvl($w_tipo_rel,'-')=='Word') {
      ShowHTML('          <option value="">Consulta na Tela');
      ShowHTML('          <option value="Word" SELECTED>Documento Word');
    } else {
      ShowHTML('          <option value="" SELECTED>Consulta na Tela');
      ShowHTML('          <option value="Word">Documento Word');
    } 
    ShowHTML('          </select></td><tr>');
    ShowHTML('    </table></td></tr>');
    ShowHTML('<tr><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr bgcolor="'.$conTrAlternateBgColor.'"><td colspan="2"><b>ESCOLHA OS BLOCOS A SEREM VISUALIZADOS NO RELATÓRIO</b></td></tr>');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_identificacao" value="sim"> Identficação</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_anexo" value="sim"> Anexos</td>');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_responsavel" value="sim"> Responsáveis</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_ocorrencia" value="sim"> Ocorrências/Anotações</td>');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('          <td colspan="2"><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_marca_bloco" value="" onClick="javascript:MarcaTodosBloco();" TITLE="Marca todos os itens da relação"> Todos</td>');
    ShowHTML('    </table></td></tr>');
    ShowHTML('    <table width="100%" border="0">');
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
  ShowHTML('</DIV>');
  ShowHTML('</BODY>');
  ShowHTML('</HTML>');
} 
// =========================================================================
// Relatório detalhado das ações cadastradas
// -------------------------------------------------------------------------
function Rel_Det_Acao() {
  extract($GLOBALS);
  $p_sq_unidade     = $_REQUEST['p_sq_unidade'];
  $p_cd_programa    = $_REQUEST['p_cd_programa'];
  $p_cd_acao        = $_REQUEST['p_cd_acao'];
  $w_tipo_rel       = strtoupper(trim($_REQUEST['w_tipo_rel']));
  $w_identificacao  = strtoupper(trim($_REQUEST['w_identificacao']));
  $w_responsavel    = strtoupper(trim($_REQUEST['w_responsavel']));
  $w_qualitativa    = strtoupper(trim($_REQUEST['w_qualitativa']));
  $w_orcamentaria   = strtoupper(trim($_REQUEST['w_orcamentaria']));
  $w_meta           = strtoupper(trim($_REQUEST['w_meta']));
  $w_restricao      = strtoupper(trim($_REQUEST['w_restricao']));
  $w_tarefa         = strtoupper(trim($_REQUEST['w_tarefa']));
  $w_interessado    = strtoupper(trim($_REQUEST['w_interessado']));
  $w_anexo          = strtoupper(trim($_REQUEST['w_anexo']));
  $w_ocorrencia     = strtoupper(trim($_REQUEST['w_ocorrencia']));
  $w_cont=0;
  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'')   $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
  }
  $RS1 = db_getLinkData::getInstanceOf($dbms,RetornaCliente(),'ISACAD');
  $RS = db_getSolicList_IS::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,'ISACAD',4,
          null,null,null,null,null,null,
          $p_sq_unidade,null,null,null,
          null,null,null,null,null,null,null,
          null,null,null,null,null,null,$p_cd_programa,substr($p_cd_acao,4,4),null,null,$w_ano);
  $RS = SortArray($RS,'phpdt_fim','asc','prioridade','asc');
  if ($w_tipo_rel=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_pag=1;
    $w_linha=8;
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('<div align="center">');
    ShowHTML('<table width="95%" border="0" cellspacing="3">');
    ShowHTML('<tr><td colspan="2">');
    ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"></TD><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
    ShowHTML('RELATÓRIO DETALHADO DE AÇÕES <br> Exercício '.$w_ano);
    ShowHTML('</FONT></TD></TR></TABLE>');
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Relatório de Ações - Exercício '.$w_ano.'</TITLE>');
    if (!(strpos('P',$O)===false)) {
      ScriptOpen('JavaScript');
      ValidateOpen('Validacao');
      Validate('p_sq_unidade','Responsável','HIDDEN','','2','60','1','1');
      Validate('p_cd_programa','Programa','HIDDEN','','1','18','1','1');
      Validate('p_cd_acao','Ação','HIDDEN','','1','18','1','1');
      ValidateClose();
      ShowHTML('  function MarcaTodosBloco() {');
      ShowHTML('    if (document.Form.w_marca_bloco.checked==true) {');
      ShowHTML('         document.Form.w_identificacao.checked=true;');
      ShowHTML('         document.Form.w_responsavel.checked=true;');
      ShowHTML('         document.Form.w_qualitativa.checked=true;');
      ShowHTML('         document.Form.w_orcamentaria.checked=true;');
      ShowHTML('         document.Form.w_meta.checked=true;');
      ShowHTML('         document.Form.w_restricao.checked=true;');
      ShowHTML('         document.Form.w_tarefa.checked=true;');
      ShowHTML('         document.Form.w_interessado.checked=true;');
      ShowHTML('         document.Form.w_anexo.checked=true;');
      ShowHTML('         document.Form.w_ocorrencia.checked=true;');
      ShowHTML('    } else { ');
      ShowHTML('         document.Form.w_identificacao.checked=false;');
      ShowHTML('         document.Form.w_responsavel.checked=false;');
      ShowHTML('         document.Form.w_qualitativa.checked=false;');
      ShowHTML('         document.Form.w_orcamentaria.checked=false;');
      ShowHTML('         document.Form.w_meta.checked=false;');
      ShowHTML('         document.Form.w_restricao.checked=false;');
      ShowHTML('         document.Form.w_tarefa.checked=false;');
      ShowHTML('         document.Form.w_interessado.checked=false;');
      ShowHTML('         document.Form.w_anexo.checked=false;');
      ShowHTML('         document.Form.w_ocorrencia.checked=false;');
      ShowHTML('    } ');
      ShowHTML('  }');
      ScriptClose();
    } 
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($O=='L') {
      BodyOpenClean(null);
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');
      ShowHTML('<div align="center">');
      ShowHTML('<table width="95%" border="0" cellspacing="3">');
      ShowHTML('<tr><td colspan="2">');
      ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"></TD><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
      ShowHTML('RELATÓRIO DETALHADO DE AÇÕES <br> Exercício '.$w_ano);
      ShowHTML('</FONT></B></TD></TR></TABLE>');
    } else {
      BodyOpen('onLoad=\'document.Form.p_cd_programa.focus()\';');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
      ShowHTML('<div align=center><center>');
      ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
      ShowHTML('<HR>');
    } 
  } 
  if ($O=='L') {
    ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
    ShowHTML('<tr><td colspan="2"><div align="center">');
    ShowHTML('<table border="0" width="100%">');
    if ($p_sq_unidade>'') {
      $RS1 = db_getUorgData::getInstanceOf($dbms,$p_sq_unidade);
      ShowHTML('<tr><td width="15%"><b>Área de planejamento:</b></td><td>'.f($RS1,'nome').' - '.f($RS1,'sigla').'</td>');
    } else {
      ShowHTML('<tr><td width="15%"><b>Área de planejamento:</b></td><td>Todas</td>');
    } 
    if ($p_cd_programa>'') {
      $RS1 = db_getProgramaPPA_IS::getInstanceOf($dbms,$p_cd_programa,$w_cliente,$w_ano,null,null,null,null);
      foreach($RS1 as $row1){$RS1=$row1; break;}
      ShowHTML('    <td width="7%"><b>Programa:</b></td><td nowrap>'.$p_cd_programa.' - '.f($RS1,'ds_programa').'</td></tr>');
    } else {
      ShowHTML('    <td width="7%"><b>Programa:</b></td><td>Todos</td></tr>');
    }
    if ($p_cd_acao>'') {
      $RS1 = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,$p_cd_programa,substr($p_cd_acao,4,4),null,null,null,null,null,null,null);
      foreach($RS1 as $row1){$RS1=$row1; break;}
      ShowHTML('<tr valign="top"><td><b>Ação:</b></td><td>'.substr($p_cd_acao,4,4).' - '.f($RS1,'descricao_acao').'</td>');
    } else {
      ShowHTML('<tr valign="top"><td><b>Ação:</b></td><td>Todas</td>');
    } 
    ShowHTML('</ul></td></tr></table>');
    ShowHTML('</div></td></tr>');
    ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
    ShowHTML('<tr><td colspan="2"><div align="center"><font size="3"><b>RELATÓRIO DE AÇÕES</b></font></div></td></tr>');
    $w_linha=8;
    if (count($RS)<=0) {
      $w_linha += 1;
      ShowHTML('    <tr><td colspan="13"><div align="center"><font size="3" color="red"><b><br>Nenhuma ação encontrada</b></font></div></td></tr>');
    } else {
      foreach($RS as $row) {
        if ($w_linha>19 && $w_tipo_rel=='WORD') {
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
          ShowHTML('</table>');
          ShowHTML('</div>');
          ShowHTML('    <br style="page-break-after:always">');
          $w_linha=6;
          $w_pag=$w_pag+1;
          ShowHTML('<div align="center">');
          ShowHTML('<table width="95%" border="0" cellspacing="3">');
          ShowHTML('<tr><td colspan="2">');
          ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"></TD><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
          ShowHTML('RELATÓRIO DETALHADO DE AÇÕES <br> Exercício '.$w_ano);
          ShowHTML('</FONT></B></TD></TR></TABLE>');
          ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
          ShowHTML('<tr><td colspan="2"><div align="center">');
          ShowHTML('<table border="0" width="100%">');
          if ($p_sq_unidade>'') ShowHTML('<tr><td width="20%"><b>Área de planejamento:</b></td><td>'.f($row,'sg_segor').'</td>');
          else                  ShowHTML('<tr><td width="20%"><b>Área de planejamento:</b></td><td>Todas</td>');
          if ($p_cd_programa>'')ShowHTML('    <td width="20%"><b>Programa:</b></td><td>'.f($row,'descricao_programa').'</td></tr>');
          else                  ShowHTML('    <td width="20%"><b>Programa:</b></td><td>Todos</td></tr>');
          if ($p_cd_acao>'')    ShowHTML('<tr><td><b>Ação:</b></td><td>'.f($row,'descricao_acao').'</td>');
          else                  ShowHTML('<tr><td><b>Ação:</b></td><td>Todas</td>');
          ShowHTML('</ul></td></tr></table>');
          ShowHTML('</div></td></tr>');
          ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
          ShowHTML('<tr><td colspan="2"><div align="center"><font size="3"><b>RELATÓRIO DE AÇÕES</b></font></div></td></tr>');
        } 
        if ($w_tipo_rel=='WORD') ShowHTML(VisualAcao(f($row,'sq_siw_solicitacao'),'',$w_usuario,0,1,$w_identificacao,$w_responsavel,$w_qualitativa,$w_orcamentaria,$w_meta,$w_restricao,$w_tarefa,$w_interessado,$w_anexo,$w_ocorrencia,'nao',$w_identificacao));
        else                     ShowHTML(VisualAcao(f($row,'sq_siw_solicitacao'),'',$w_usuario,0,0,$w_identificacao,$w_responsavel,$w_qualitativa,$w_orcamentaria,$w_meta,$w_restricao,$w_tarefa,$w_interessado,$w_anexo,$w_ocorrencia,'nao',$w_identificacao));
        $w_linha += 30;
      } 
    } 
    ShowHTML('   <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONSULTA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
    ShowHTML('   <tr><td><b>Consulta Realizada por:</b></td>');
    ShowHTML('       <td>'.$_SESSION['NOME_RESUMIDO'].'</td></tr>');
    ShowHTML('   <tr><td><b>Data da Consulta:</b></td>');
    ShowHTML('       <td>'.FormataDataEdicao(time(),3).'</td></tr>');
    ShowHTML('    </table>');
    ShowHTML('  </div></td>');
    ShowHTML('</tr>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Acao',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    SelecaoUnidade('Á<U>r</U>ea planejamento:','R',null,$p_sq_unidade,null,'p_sq_unidade',null,'onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_sq_unidade\'; document.Form.target=\'\'; document.Form.O.value=\'P\'; document.Form.submit();"');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    SelecaoProgramaPPA('<u>P</u>rograma PPA:','P',null,$w_cliente,$w_ano,$p_cd_programa,'p_cd_programa','RELATORIO','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_cd_programa\'; document.Form.target=\'\'; document.Form.O.value=\'P\'; document.Form.submit();"',$w_menu,null,null);
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    SelecaoAcaoPPA('<u>A</u>ção PPA:','A',null,$w_cliente,$w_ano,$p_cd_programa,null,null,null,'p_cd_acao',null,null,null,$w_menu,null,null);
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('        <td><b><u>T</u>ipo de relatório:</b><br><SELECT ACCESSKEY="T" CLASS="STS" NAME="w_tipo_rel" '.$w_Disabled.'>');
    if (nvl($w_tipo_rel,'-')=='Word') {
      ShowHTML('          <option value="">Consulta na Tela');
      ShowHTML('          <option value="Word" SELECTED>Documento Word');
    } else {
      ShowHTML('          <option value="" SELECTED>Consulta na Tela');
      ShowHTML('          <option value="Word">Documento Word');
    } 
    ShowHTML('          </select></td><tr>');
    ShowHTML('    </table></td></tr>');
    ShowHTML('<tr><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr bgcolor="'.$conTrAlternateBgColor.'"><td colspan="2"><b>ESCOLHA OS BLOCOS A SEREM VISUALIZADOS NO RELATÓRIO</b></td></tr>');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_identificacao" value="sim"> Identficação</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_restricao" value="sim"> Restrições</td>');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_responsavel" value="sim"> Responsáveis</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_tarefa" value="sim"> Tarefas</td>');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_qualitativa" value="sim"> Programação Qualitativa</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_interessado" value="sim"> Interessados</td>');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_orcamentaria" value="sim"> Programação Orçamentária</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_anexo" value="sim"> Anexos</td>');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_meta" value="sim"> Metas Físicas</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_ocorrencia" value="sim"> Ocorrência/Anotações</td>');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('          <td colspan="2"><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_marca_bloco" value="" onClick="javascript:MarcaTodosBloco();" TITLE="Marca todos os itens da relação"> Todos</td>');
    ShowHTML('    </table></td></tr>');
    ShowHTML('    <table width="100%" border="0">');
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
  ShowHTML('</DIV>');
  ShowHTML('</BODY>');
  ShowHTML('</HTML>');
} 
// =========================================================================
// Relatório detalhado dos programas cadastradas
// -------------------------------------------------------------------------
function Rel_Det_Prog() {
  extract($GLOBALS);
  $p_sq_unidade     = strtoupper(trim($_REQUEST['p_sq_unidade']));
  $p_cd_programa    = strtoupper(trim($_REQUEST['p_cd_programa']));
  $w_tipo_rel       = strtoupper(trim($_REQUEST['w_tipo_rel']));
  $w_identificacao  = strtoupper(trim($_REQUEST['w_identificacao']));
  $w_responsavel    = strtoupper(trim($_REQUEST['w_responsavel']));
  $w_qualitativa    = strtoupper(trim($_REQUEST['w_qualitativa']));
  $w_orcamentaria   = strtoupper(trim($_REQUEST['w_orcamentaria']));
  $w_indicador      = strtoupper(trim($_REQUEST['w_indicador']));
  $w_restricao      = strtoupper(trim($_REQUEST['w_restricao']));
  $w_acao           = strtoupper(trim($_REQUEST['w_acao']));
  $w_interessado    = strtoupper(trim($_REQUEST['w_interessado']));
  $w_anexo          = strtoupper(trim($_REQUEST['w_anexo']));
  $w_ocorrencia     = strtoupper(trim($_REQUEST['w_ocorrencia']));
  $w_cont=0;
  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'')   $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
  } 
  $RS1 = db_getLinkData::getInstanceOf($dbms,RetornaCliente(),'ISPCAD');
  $RS4 = db_getSolicList_IS::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,'ISPCAD',4,
            null,null,null,null,null,null,
            $p_sq_unidade,null,null,null,
            null,null,null,null,null,null,null,
            null,null,null,null,null,null,null,$p_cd_programa,null,null,$w_ano);
  $RS4 = SortArray($RS4,'phpdt_fim','asc','prioridade','asc');
  if ($w_tipo_rel=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_pag=1;
    $w_linha=8;
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('<div align="center">');
    ShowHTML('<table width="95%" border="0" cellspacing="3">');
    ShowHTML('<tr><td colspan="2">');
    ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"></TD><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
    ShowHTML('RELATÓRIO DETALHADO DE PROGRAMAS <br> Exercício '.$w_ano);
    ShowHTML('</FONT></TD></TR></TABLE>');
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Relatório de Programas - Exercício '.$w_ano.'</TITLE>');
    if (!(strpos('P',$O)===false)) {
      ScriptOpen('JavaScript');
      ValidateOpen('Validacao');
      Validate('p_sq_unidade','Responsável','HIDDEN','','2','60','1','1');
      Validate('p_cd_programa','Programa','HIDDEN','','1','18','1','1');
      ValidateClose();
      ShowHTML('  function MarcaTodosBloco() {');
      ShowHTML('    if (document.Form.w_marca_bloco.checked==true) {');
      ShowHTML('         document.Form.w_identificacao.checked=true;');
      ShowHTML('         document.Form.w_responsavel.checked=true;');
      ShowHTML('         document.Form.w_qualitativa.checked=true;');
      ShowHTML('         document.Form.w_orcamentaria.checked=true;');
      ShowHTML('         document.Form.w_indicador.checked=true;');
      ShowHTML('         document.Form.w_restricao.checked=true;');
      ShowHTML('         document.Form.w_acao.checked=true;');
      ShowHTML('         document.Form.w_interessado.checked=true;');
      ShowHTML('         document.Form.w_anexo.checked=true;');
      ShowHTML('         document.Form.w_ocorrencia.checked=true;');
      ShowHTML('    } else { ');
      ShowHTML('         document.Form.w_identificacao.checked=false;');
      ShowHTML('         document.Form.w_responsavel.checked=false;');
      ShowHTML('         document.Form.w_qualitativa.checked=false;');
      ShowHTML('         document.Form.w_orcamentaria.checked=false;');
      ShowHTML('         document.Form.w_indicador.checked=false;');
      ShowHTML('         document.Form.w_restricao.checked=false;');
      ShowHTML('         document.Form.w_acao.checked=false;');
      ShowHTML('         document.Form.w_interessado.checked=false;');
      ShowHTML('         document.Form.w_anexo.checked=false;');
      ShowHTML('         document.Form.w_ocorrencia.checked=false;');
      ShowHTML('    } ');
      ShowHTML('  }');
      ScriptClose();
    } 
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($O=='L') {
      BodyOpenClean(null);
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');
      ShowHTML('<div align="center">');
      ShowHTML('<table width="95%" border="0" cellspacing="3">');
      ShowHTML('<tr><td colspan="2">');
      ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"></TD><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
      ShowHTML('RELATÓRIO DETALHADO DE PROGRAMAS <br> Exercício '.$w_ano);
      ShowHTML('</FONT></B></TD></TR></TABLE>');
    } else {
      BodyOpen('onLoad=\'document.Form.p_cd_programa.focus()\';');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
      ShowHTML('<div align=center><center>');
      ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
      ShowHTML('<HR>');
    } 
  } 
  if ($O=='L') {
    ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
    ShowHTML('<tr><td colspan="2"><div align="center">');
    ShowHTML('<table border="0" width="100%">');
    if ($p_sq_unidade>'') {
      $RS1 = db_getUorgData::getInstanceOf($dbms,$p_sq_unidade);
      ShowHTML('<tr><td width="15%"><b>Área de planejamento:</b></td><td>'.f($RS1,'nome').' - '.f($RS1,'sigla').'</td>');
    } else {
      ShowHTML('<tr><td width="15%"><b>Área de planejamento:</b></td><td>Todas</td>');
    } 
    if ($p_cd_programa>'') {
      $RS1 = db_getProgramaPPA_IS::getInstanceOf($dbms,$p_cd_programa,$w_cliente,$w_ano,null,null,null,null);
      foreach($RS1 as $row1){$RS1=$row1; break;}
      ShowHTML('    <td width="7%"><b>Programa:</b></td><td nowrap>'.$p_cd_programa.' - '.f($RS1,'ds_programa').'</td></tr>');
    } else {
      ShowHTML('    <td width="7%"><b>Programa:</b></td><td>Todos</td></tr>');
    } 
    ShowHTML('</ul></td></tr></table>');
    ShowHTML('</div></td></tr>');
    ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
    ShowHTML('<tr><td colspan="2"><div align="center"><font size="3"><b>RELATÓRIO DE PROGRAMAS</b></font></div></td></tr>');
    $w_linha=8;
    if (count($RS4)<=0) {
      $w_linha=$w_linha+1;
      ShowHTML('    <tr><td colspan="13"><div align="center"><font size="3" color="red"><b><br>Nenhum programa encontrado</b></font></div></td></tr>');
    } else {
      foreach ($RS4 as $row4) {
        if ($w_linha>19 && $w_tipo_rel=='WORD') {
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
          ShowHTML('</table>');
          ShowHTML('</div>');
          ShowHTML('    <br style="page-break-after:always">');
          $w_linha=6;
          $w_pag=$w_pag+1;
          ShowHTML('<div align="center">');
          ShowHTML('<table width="95%" border="0" cellspacing="3">');
          ShowHTML('<tr><td colspan="2">');
          ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"></TD><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
          ShowHTML('RELATÓRIO DETALHADO DE PROGRAMAS <br> Exercício '.$w_ano);
          ShowHTML('</FONT></B></TD></TR></TABLE>');
          ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
          ShowHTML('<tr><td colspan="2"><div align="center">');
          ShowHTML('<table border="0" width="100%">');
          if ($p_sq_unidade>'') {
            $RS1 = db_getUorgData::getInstanceOf($dbms,$p_sq_unidade);
            foreach($RS1 as $row1){$RS1=$row1; break;}
            ShowHTML('<tr><td width="20%"><b>Área de planejamento:</b></td><td>'.f($RS1,'nome').' - '.f($RS1,'sigla').'</td>');
          } else {
            ShowHTML('<tr><td width="20%"><b>Área de planejamento:</b></td><td>Todas</td>');
          } 
          if ($p_cd_programa>'') {
            $RS1 = db_getProgramaPPA_IS::getInstanceOf($dbms,$p_cd_programa,$w_cliente,$w_ano,null,null,null,null);
            foreach($RS1 as $row1){$RS1=$row1; break;}
            ShowHTML('    <td width="20%"><b>Programa:</b></td><td>'.$p_cd_programa.' - '.f($RS1,'ds_programa').'</td></tr>');
          } else {
            ShowHTML('    <td width="20%"><b>Programa:</b></td><td>Todos</td></tr>');
          } 
          ShowHTML('</ul></td></tr></table>');
          ShowHTML('</div></td></tr>');
          ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
          ShowHTML('<tr><td colspan="2"><div align="center"><font size="3"><b>RELATÓRIO DE PROGRAMAS</b></font></div></td></tr>');
        } 
        if ($w_tipo_rel=='WORD') {
          ShowHTML(VisualPrograma(f($row4,'sq_siw_solicitacao'),'',$w_usuario,0,1,$w_identificacao,$w_responsavel,$w_qualitativa,$w_orcamentaria,$w_indicador,$w_restricao,$w_interessado,$w_anexo,$w_acao,$w_ocorrencia,'nao'));
          ShowHTML('<tr><td colspan="2"><div align="center"><BR></div></td></tr>');
        } else {
          ShowHTML(VisualPrograma(f($row4,'sq_siw_solicitacao'),'',$w_usuario,0,0,$w_identificacao,$w_responsavel,$w_qualitativa,$w_orcamentaria,$w_indicador,$w_restricao,$w_interessado,$w_anexo,$w_acao,$w_ocorrencia,'nao'));
          ShowHTML('<tr><td colspan="2"><div align="center"><BR></div></td></tr>');
        } 
        $w_linha=$w_linha+30;
      } 
    } 
    ShowHTML('   <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONSULTA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
    ShowHTML('   <tr><td><b>Consulta Realizada por:</b></td>');
    ShowHTML('       <td>'.$_SESSION['NOME_RESUMIDO'].'</td></tr>');
    ShowHTML('   <tr><td><b>Data da Consulta:</b></td>');
    ShowHTML('       <td>'.FormataDataEdicao(time(),3).'</td></tr>');
    ShowHTML('    </table>');
    ShowHTML('  </div></td>');
    ShowHTML('</tr>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Programa',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    SelecaoUnidade('Á<U>r</U>ea planejamento:','R',null,$p_sq_unidade,null,'p_sq_unidade',null,'onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_sq_unidade\'; document.Form.target=\'\'; document.Form.O.value=\'P\'; document.Form.submit();"');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    SelecaoProgramaPPA('<u>P</u>rograma PPA:','P',null,$w_cliente,$w_ano,$p_cd_programa,'p_cd_programa','RELATORIO','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_cd_programa\'; document.Form.target=\'\'; document.Form.O.value=\'P\'; document.Form.submit();"',$w_menu,null,null);
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('        <td><b><u>T</u>ipo de relatório:</b><br><SELECT ACCESSKEY="T" CLASS="STS" NAME="w_tipo_rel" '.$w_Disabled.'>');
    if (nvl($w_tipo_rel,'-')=='Word') {
      ShowHTML('          <option value="">Consulta na Tela');
      ShowHTML('          <option value="Word" SELECTED>Documento Word');
    } else {
      ShowHTML('          <option value="" SELECTED>Consulta na Tela');
      ShowHTML('          <option value="Word">Documento Word');
    } 
    ShowHTML('          </select></td><tr>');
    ShowHTML('    </table></td></tr>');
    ShowHTML('<tr><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr bgcolor="'.$conTrAlternateBgColor.'"><td colspan="2"><b>ESCOLHA OS BLOCOS A SEREM VISUALIZADOS NO RELATÓRIO</b></td></tr>');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_identificacao" value="sim"> Identficação</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_restricao" value="sim"> Restrições</td>');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_responsavel" value="sim"> Responsáveis</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_interessado" value="sim"> Interessados</td>');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_qualitativa" value="sim"> Programação Qualitativa</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_anexo" value="sim"> Anexos</td>');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_orcamentaria" value="sim"> Programação Orçamentária</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_acao" value="sim"> Ações</td>');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_indicador" value="sim"> Indicadores</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_ocorrencia" value="sim"> Ocorrência/Anotações</td>');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('          <td colspan="2"><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_marca_bloco" value="" onClick="javascript:MarcaTodosBloco();" TITLE="Marca todos os itens da relação"> Todos</td>');
    ShowHTML('    </table></td></tr>');
    ShowHTML('    <table width="100%" border="0">');
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
  ShowHTML('</DIV>');
  ShowHTML('</BODY>');
  ShowHTML('</HTML>');
} 
// =========================================================================
// Relatório de limites das unidades
// -------------------------------------------------------------------------
function Rel_Limite() {
  extract($GLOBALS);
  $p_sq_unidade     = $_REQUEST['p_sq_unidade'];
  $p_cd_programa    = $_REQUEST['p_cd_programa'];
  $p_cd_acao        = $_REQUEST['p_cd_acao'];
  $w_tipo_rel       = strtoupper(trim($_REQUEST['w_tipo_rel']));
  $w_det_tarefa     = strtoupper(trim($_REQUEST['w_det_tarefa']));
  $w_cont           = 0;
  $w_utilizado      = 0;
  $w_tot_utilizado  = 0;
  $w_limite         = 0;
  $w_tot_limite     = 0;
  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'') $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
  } 
  $RS1 = db_getLinkData::getInstanceOf($dbms,RetornaCliente(),'ISTCAD');
  $RS = db_getSolicList_IS::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,'ISTCAD',4,
          null,null,null,null,null,null,
          $p_sq_unidade,null,null,null,
          null,null,null,null,null,null,null,
          null,null,null,null,null,null,$p_cd_programa,substr($p_cd_acao,4,4),null,null,$w_ano);
  $RS = SortArray($RS,'sq_unidade_resp','asc');
  if ($w_tipo_rel=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_pag=1;
    $w_linha=8;
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('<div align="center">');
    ShowHTML('<table width="95%" border="0" cellspacing="3">');
    ShowHTML('<tr><td colspan="2">');
    ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"></TD><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
    ShowHTML('RELATÓRIO DE LIMITES<br> Exercício '.$w_ano);
    ShowHTML('</FONT></TD></TR></TABLE>');
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Relatório de Limites - Exercício '.$w_ano.'</TITLE>');
    if (!(strpos('P',$O)===false)) {
      ScriptOpen('JavaScript');
      ValidateOpen('Validacao');
      Validate('p_sq_unidade','Responsável','HIDDEN','','2','60','1','1');
      Validate('p_cd_programa','Programa','HIDDEN','','1','18','1','1');
      Validate('p_cd_acao','Ação','HIDDEN','','1','18','1','1');
      ValidateClose();
      ScriptClose();
    } 
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($O=='L') {
      BodyOpenClean(null);
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');
      ShowHTML('<div align="center">');
      ShowHTML('<table width="95%" border="0" cellspacing="3">');
      ShowHTML('<tr><td colspan="2">');
      ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"></TD><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
      ShowHTML('RELATÓRIO DE LIMITES<br> Exercício '.$w_ano);
      ShowHTML('</FONT></B></TD></TR></TABLE>');
    } else {
      BodyOpen('onLoad=\'document.Form.p_cd_programa.focus()\';');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
      ShowHTML('<div align=center><center>');
      ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
      ShowHTML('<HR>');
    } 
  } 
  if ($O=='L') {
    ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
    ShowHTML('<tr><td colspan="2"><div align="center">');
    ShowHTML('<table border="0" width="100%">');
    if ($p_sq_unidade>'') {
      $RS1 = db_getUorgData::getInstanceOf($dbms,$p_sq_unidade);
      ShowHTML('<tr><td width="15%"><b>Área de planejamento:</b></td><td>'.f($RS1,'nome').' - '.f($RS1,'sigla').'</td>');
    } else {
      ShowHTML('<tr><td width="15%"><b>Área de planejamento:</b></td><td>Todas</td>');
    } 
    if ($p_cd_programa>'') {
      $RS1 = db_getProgramaPPA_IS::getInstanceOf($dbms,$p_cd_programa,$w_cliente,$w_ano,null,null,null,null);
      foreach($RS1 as $row1){$RS1=$row1; break;}
      ShowHTML('    <td width="7%"><b>Programa:</b></td><td nowrap>'.$p_cd_programa.' - '.f($RS1,'ds_programa').'</td></tr>');
    } else {
      ShowHTML('    <td width="7%"><b>Programa:</b></td><td>Todos</td></tr>');
    } 
    if ($p_cd_acao>'') {
      $RS1 = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,$p_cd_programa,substr($p_cd_acao,4,4),null,null,null,null,null,null,null);
      foreach($RS1 as $row1){$RS1=$row1; break;}
      ShowHTML('<tr valign="top"><td><b>Ação:</b></td><td>'.substr($p_cd_acao,4,4).' - '.f($RS1,'descricao_acao').'</td>');
    } else {
      ShowHTML('<tr valign="top"><td><b>Ação:</b></td><td>Todas</td>');
    } 
    ShowHTML('</ul></td></tr></table>');
    ShowHTML('</div></td></tr>');
    ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
    ShowHTML('<tr><td colspan="2"><div align="center"><font size="3"><b>RELATÓRIO DE LIMITES</b></font></div></td></tr>');
    $w_linha=9;
    if (count($RS)<=0) {
      $w_linha=$w_linha+1;
      ShowHTML('    <tr><td colspan="13"><div align="center"><font size="3" color="red"><b><br>Nenhum limite de unidade encontrado</b></font></div></td></tr>');
    } else {
      ShowHTML('   <tr><td colspan="2">');
      ShowHTML('     <table width=100%  border="1" bordercolor="#00000">');
      ShowHTML('       <tr bgcolor="'.$conTrAlternateBgColor.'"><td align="center"><b>Unidade</b></td>');
      ShowHTML('           <td align="center"><b>Limite (R$)</b></td>');
      ShowHTML('           <td align="center"><b>Utilizado (R$)</b></td>');
      ShowHTML('           <td align="center"><b>Saldo (R$)</b></td></tr>');
      $w_unidade_atual='';
      $i=1;
      foreach($RS as $row) {
        if ($w_linha>19 && $w_tipo_rel=='WORD') {
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
          ShowHTML('</table>');
          ShowHTML('</div>');
          ShowHTML('    <br style="page-break-after:always">');
          $w_linha=9;
          $w_pag=$w_pag+1;
          ShowHTML('<div align="center">');
          ShowHTML('<table width="95%" border="0" cellspacing="3">');
          ShowHTML('<tr><td colspan="2">');
          ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"></TD><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
          ShowHTML('RELATÓRIO DE LIMITES<br> Exercício '.$w_ano);
          ShowHTML('</FONT></TD></TR></TABLE>');
          ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
          ShowHTML('<tr><td colspan="2"><div align="center">');
          ShowHTML('<table border="0" width="100%">');
          if ($p_sq_unidade>'') {
            $RS1 = db_getUorgData::getInstanceOf($dbms,$p_sq_unidade);
            ShowHTML('<tr><td width="15%"><b>Área de planejamento:</b></td><td>'.f($RS1,'nome').' - '.f($RS1,'sigla').'</td>');
          } else {
            ShowHTML('<tr><td width="15%"><b>Área de planejamento:</b></td><td>Todas</td>');
          } 
          if ($p_cd_programa>'') {
            $RS1 = db_getProgramaPPA_IS::getInstanceOf($dbms,$p_cd_programa,$w_cliente,$w_ano,null,null,null,null);
            foreach($RS1 as $row1){$RS1=$row1; break;}
            ShowHTML('    <td width="7%"><b>Programa:</b></td><td nowrap>'.$p_cd_programa.' - '.f($RS1,'ds_programa').'</td></tr>');
          } else {
            ShowHTML('    <td width="7%"><b>Programa:</b></td><td>Todos</td></tr>');
          }
          if ($p_cd_acao>'') {
            $RS1 = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,$p_cd_programa,substr($p_cd_acao,4,4),null,null,null,null,null,null,null);
            foreach($RS1 as $row1){$RS1=$row1; break;}
            ShowHTML('<tr valign="top"><td><b>Ação:</b></td><td>'.substr($p_cd_acao,4,4).' - '.f($RS1,'descricao_acao').'</td>');
          } else {
            ShowHTML('<tr valign="top"><td><b>Ação:</b></td><td>Todas</td>');
          } 
          ShowHTML('</ul></td></tr></table>');
          ShowHTML('</div></td></tr>');
          ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
          ShowHTML('<tr><td colspan="2"><div align="center"><font size="3"><b>RELATÓRIO DE LIMITES</b></font></div></td></tr>');
        } 
        if (Nvl(f($row,'cd_acao'),'')>'') {
          if ((Nvl($w_unidade_atual,'')!='') && ($w_unidade_atual!=f($row,'nm_unidade_resp'))) {
            ShowHTML('    <tr><td>'.$w_unidade_atual.'</td>');
            ShowHTML('        <td align="right">'.number_format(Nvl($w_limite,0),2,',','.').'</td>');
            ShowHTML('        <td align="right">'.number_format(Nvl($w_utilizado,0),2,',','.').'</td>');
            ShowHTML('        <td align="right">'.number_format((Nvl($w_limite,0)-Nvl($w_utilizado,0)),2,',','.').'</td>');
            $w_tot_limite   += Nvl($w_limite,0);
            $w_valor[$i][1] = Nvl($w_limite,0);
            $w_valor[$i][2] = Nvl($w_utilizado,0);
            $w_utilizado = 0;
            $i += 1;
          } 
          $w_limite         = f($row,'limite_orcamento');
          $w_utilizado      += Nvl(f($row,'custo_real'),0);
          $w_tot_utilizado  += Nvl(f($row,'custo_real'),0);
          $w_unidade_atual  = f($row,'nm_unidade_resp');
        } 
      } 
      $w_linha += 8;
      ShowHTML('    <tr><td>'.$w_unidade_atual.'</td>');
      ShowHTML('        <td align="right">'.number_format(Nvl($w_limite,0),2,',','.').'</td>');
      ShowHTML('        <td align="right">'.number_format(Nvl($w_utilizado,0),2,',','.').'</td>');
      ShowHTML('        <td align="right">'.number_format((Nvl($w_limite,0)-Nvl($w_utilizado,0)),2,',','.').'</td>');
      $w_tot_limite     += Nvl($w_limite,0);
      $w_valor[$i][1]   = number_format(Nvl($w_limite,0),2,',','.');
      $w_valor[$i][2]   = number_format(Nvl($w_utilizado,0),2,',','.');
      ShowHTML('    <tr bgcolor="'.$conTrAlternateBgColor.'"><td align="right"><b>Totais</b></td>');
      ShowHTML('        <td align="right"><b>'.number_format(Nvl($w_tot_limite,0),2,',','.').'</b></td>');
      ShowHTML('        <td align="right"><b>'.number_format(Nvl($w_tot_utilizado,0),2,',','.').'</b></td>');
      ShowHTML('        <td align="right"><b>'.number_format((Nvl($w_tot_limite,0)-Nvl($w_tot_utilizado,0)),2,',','.').'</b></td>');
      ShowHTML('</table>');
      if (strtoupper($w_det_tarefa)==strtoupper('sim')) {
        ShowHTML('<br><br><br><tr><td colspan="2"><div align="center"><font size="3"><b>DETALHAMENTO DAS TAREFAS</b></font></div></td></tr>');
        $RS1 = db_getLinkData::getInstanceOf($dbms,RetornaCliente(),'ISTCAD');
        $RS = db_getSolicList_IS::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,'ISTCAD',4,
                null,null,null,null,null,null,
                $p_sq_unidade,null,null,null,
                null,null,null,null,null,null,null,
                null,null,null,null,null,null,$p_cd_programa,substr($p_cd_acao,4,4),null,null,$w_ano);
        $RS = SortArray($RS,'sq_unidade_resp','asc');
        $w_unidade_atual = '';
        $i = 1;
        $w_tot_limite = 0;
        ShowHTML('<tr><td colspan="2">');
        ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
        foreach($RS as $row) {
          if (Nvl(f($row,'cd_acao'),'')>'') {
            if ($w_linha>19 && $w_tipo_rel=='WORD') {
              ShowHTML('</table>');
              ShowHTML('</table>');
              ShowHTML('</table>');
              ShowHTML('</div>');
              ShowHTML('    <br style="page-break-after:always">');
              $w_linha=9;
              $w_pag=$w_pag+1;
              ShowHTML('<div align="center">');
              ShowHTML('<table width="95%" border="0" cellspacing="3">');
              ShowHTML('<tr><td colspan="2">');
              ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"></TD><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
              ShowHTML('RELATÓRIO DE LIMITES<br> Exercício '.$w_ano);
              ShowHTML('</FONT></TD></TR></TABLE>');
              ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
              ShowHTML('<tr><td colspan="2"><div align="center">');
              ShowHTML('<table border="0" width="100%">');
              if ($p_sq_unidade>'') {
                $RS1 = db_getUorgData::getInstanceOf($dbms,$p_sq_unidade);
                ShowHTML('<tr><td width="15%"><b>Área de planejamento:</b></td><td>'.f($RS1,'nome').' - '.f($RS1,'sigla').'</td>');
              } else {
                ShowHTML('<tr><td width="15%"><b>Área de planejamento:</b></td><td>Todas</td>');
              } 
              if ($p_cd_programa>'') {
                $RS1 = db_getProgramaPPA_IS::getInstanceOf($dbms,$p_cd_programa,$w_cliente,$w_ano,null,null,null,null);
                foreach($RS1 as $row1){$RS1=$row1; break;}
                ShowHTML('    <td width="7%"><b>Programa:</b></td><td nowrap>'.$p_cd_programa.' - '.f($RS1,'ds_programa').'</td></tr>');
              } else {
                ShowHTML('    <td width="7%"><b>Programa:</b></td><td>Todos</td></tr>');
              } 
              if ($p_cd_acao>'') {
                $RS1 = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,$p_cd_programa,substr($p_cd_acao,4,4),null,null,null,null,null,null,null);
                foreach($RS1 as $row1){$RS1=$row1; break;}
                ShowHTML('<tr valign="top"><td><b>Ação:</b></td><td>'.substr($p_cd_acao,4,4).' - '.f($RS1,'descricao_acao').'</td>');
              } else {
                ShowHTML('<tr valign="top"><td><b>Ação:</b></td><td>Todas</td>');
              } 
              ShowHTML('</ul></td></tr></table>');
              ShowHTML('</div></td></tr>');
              ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
              ShowHTML('<tr><td colspan="2"><div align="center"><font size="3"><b>RELATÓRIO DE LIMITES</b></font></div></td></tr>');
              ShowHTML('<tr><td colspan="2">');
              ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
            } 
            if ($w_unidade_atual=='' || $w_unidade_atual!=f($row,'nm_unidade_resp')) {
              if ($w_unidade_atual!='') {
                ShowHTML('       <tr bgcolor="'.$conTrAlternateBgColor.'"><td colspan="3" align="right"><b>Total</b></td>');
                ShowHTML('           <td align="right"><b>'.number_format(Nvl($w_tot_limite,0),2,',','.').'</b></td>');
                ShowHTML('</table>');
                $w_tot_limite=0;
              } 
              ShowHTML('<br><tr><td colspan="4"><hr NOSHADE color=#000000 size=4></td></tr>');
              ShowHTML('   <tr><td bgcolor="#f0f0f0"><font size="2"><b>UNIDADE: '.f($row,'nm_unidade_resp').'</b></font></td>');
              ShowHTML('       <td bgcolor="#f0f0f0"><b>Limite: '.number_format($w_valor[$i][1],2,',','.').'</b></td>');
              ShowHTML('       <td bgcolor="#f0f0f0"><b>Utilizado: '.number_format($w_valor[$i][2],2,',','.').'</b></td>');
              ShowHTML('       <td bgcolor="#f0f0f0"><b>Saldo: '.number_format(($w_valor[$i][1]-$w_valor[$i][2]),2,',','.').'</b></td>');
              ShowHTML('   <tr><td colspan="4"><hr NOSHADE color=#000000 size=4></td></tr>');
              $i += 1;
              ShowHTML('   <tr><td colspan="4">');
              ShowHTML('     <table width=100%  border="1" bordercolor="#00000">');
              ShowHTML('       <tr bgcolor="'.$conTrAlternateBgColor.'"><td align="center"><b>Ação</b></td>');
              ShowHTML('           <td colspan="2" align="center"><b>Tarefa</b></td>');
              ShowHTML('           <td align="center"><b>Limite orçamentário</b></td></tr>');
              $w_linha += 4;
            } 
            if ($w_tipo_rel=='WORD')    ShowHTML('       <tr><td align="center">'.f($row,'cd_unidade').'.'.f($row,'cd_programa').'.'.f($row,'cd_acao').'</td>');
            else                        ShowHTML('       <tr><td align="center"><A class="HL" HREF="'.$w_dir.'acao.php?par='.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_solic_acao').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'cd_unidade').'.'.f($row,'cd_programa').'.'.f($row,'cd_acao').'</a></td>');
            if ($w_tipo_rel=='WORD')    ShowHTML('           <td colspan="2">'.f($row,'titulo').'</td>');
            else                        ShowHTML('           <td colspan="2"><A class="HL" HREF="'.$w_dir.'tarefas.php?par='.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'titulo').'</a></td>');
            ShowHTML('              <td align="right">'.number_format(Nvl(f($row,'custo_real'),0),2,',','.').'</td>');
            $w_tot_limite       = $w_tot_limite + Nvl(f($row,'custo_real'),0);
            $w_unidade_atual    = f($row,'nm_unidade_resp');
            $w_linha += 1;
          } 
        }
        if ($i>1) {
          ShowHTML('       <tr bgcolor="'.$conTrAlternateBgColor.'"><td colspan="3" align="right"><b>Total</b></td>');
          ShowHTML('           <td align="right"><b>'.number_format(Nvl($w_tot_limite,0),2,',','.').'</b></td>');
        } else {
          ShowHTML('    <tr><td colspan="4"><div align="center"><font color="red"><b><br>Nenhum limite de unidade encontrado</b></font></div></td></tr>');
        }
        ShowHTML('</table>');
        ShowHTML('</table>');
      } 
    } 
    ShowHTML('   <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONSULTA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
    ShowHTML('   <tr><td><b>Consulta Realizada por:</b></td>');
    ShowHTML('       <td>'.$_SESSION['NOME_RESUMIDO'].'</td></tr>');
    ShowHTML('   <tr><td><b>Data da Consulta:</b></td>');
    ShowHTML('       <td>'.FormataDataEdicao(time(),3).'</td></tr>');
    ShowHTML('    </table>');
    ShowHTML('  </div></td>');
    ShowHTML('</tr>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Acao',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    SelecaoUnidade_IS('Á<U>r</U>ea planejamento:','R',null,$p_sq_unidade,null,'p_sq_unidade','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_sq_unidade\'; document.Form.target=\'\'; document.Form.O.value=\'P\'; document.Form.submit();"','PLANEJAMENTO');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    SelecaoProgramaPPA('<u>P</u>rograma PPA:','P',null,$w_cliente,$w_ano,$p_cd_programa,'p_cd_programa','RELATORIO','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_cd_programa\'; document.Form.target=\'\'; document.Form.O.value=\'P\'; document.Form.submit();"',$w_menu,null,null);
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    SelecaoAcaoPPA('<u>A</u>ção PPA:','A',null,$w_cliente,$w_ano,$p_cd_programa,null,null,null,'p_cd_acao',null,null,null,$w_menu,null,null);
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('        <td><b><u>T</u>ipo de relatório:</b><br><SELECT ACCESSKEY="T" CLASS="STS" NAME="w_tipo_rel" '.$w_Disabled.'>');
    if (nvl($w_tipo_rel,'-')=='Word') {
      ShowHTML('          <option value="">Consulta na Tela');
      ShowHTML('          <option value="Word" SELECTED>Documento Word');
    } else {
      ShowHTML('          <option value="" SELECTED>Consulta na Tela');
      ShowHTML('          <option value="Word">Documento Word');
    } 
    ShowHTML('          </select></td><tr>');
    ShowHTML('    </table></td></tr>');
    ShowHTML('<tr><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr bgcolor="'.$conTrAlternateBgColor.'"><td colspan="2"><b>ESCOLHA OS BLOCOS A SEREM VISUALIZADOS NO RELATÓRIO</b></td></tr>');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('          <td colspan="2"><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_det_tarefa" value="sim"> Detalhamento das tarefas</td>');
    ShowHTML('    </table></td></tr>');
    ShowHTML('    <table width="100%" border="0">');
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
  ShowHTML('</DIV>');
  ShowHTML('</BODY>');
  ShowHTML('</HTML>');
} 
// =========================================================================
// Gera uma linha de apresentação da tabela de metas
// -------------------------------------------------------------------------
function MetaLinha($l_chave,$l_chave_aux,$l_titulo,$l_word,$l_programada,$l_unidade_medida,$l_quantidade,$l_fim,$l_perc,$l_oper,$l_tipo,$l_loa,$l_exequivel='S') {
  extract($GLOBALS);
  $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
  $l_html .=chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top">';
  $l_html .=chr(13).'        <td nowrap '.$l_row.'>';
  if ($l_exequivel=='N' || ($l_fim<addDays(time(),-1) && $l_perc<100)) $l_html .=chr(13).'           <img src="'.$conImgAtraso.'" border=0 width=15 height=15 align="center">';
  elseif ($l_perc<100)                          $l_html .=chr(13).'           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">';
  else                                          $l_html .=chr(13).'           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">';
  if ($l_word!='WORD')  $l_html .=chr(13).'<A class="HL" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS($w_dir,'acao.php?par=AtualizaMeta&O=V&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&w_tipo=Volta&P1=10&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\',\'Meta\',\'width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no\'); return false;" title="Clique para exibir os dados!">'.$l_titulo.'</A>';
  else                  $l_html .=chr(13).'        '.$l_titulo.'</td>';
  if ($l_loa>'')    $l_html .=chr(13).'        <td align="center">Sim</b>';
  else              $l_html .=chr(13).'        <td align="center">Não</b>';
  $l_html .=chr(13).'        <td align="center" '.$l_row.'>'.FormataDataEdicao($l_fim).'</td>';
  $l_html .=chr(13).'        <td nowrap '.$l_row.'>'.Nvl($l_unidade_medida,'---').' </td>';
  $l_html .=chr(13).'        <td nowrap align="right" '.$l_row.'>'.number_format(Nvl($l_quantidade,0),2,',','.').'</td>';
  $l_html .=chr(13).'        <td nowrap align="right" '.$l_row.'>'.$l_perc.' %</td>';
  $l_html .=chr(13).'      </tr>';

  return $l_html;
} 
// =========================================================================
// Gera uma linha de apresentação da tabela de indicadores
// -------------------------------------------------------------------------
function Indicadorlinha($l_chave,$l_chave_aux,$l_titulo,$l_valor_ref,$l_valor_prog,$l_valor_apurado,$l_apuracao_ind,$l_unidade_medida,$l_word,$l_destaque,$l_oper,$l_tipo,$l_loa,$l_exequivel) {
  extract($GLOBALS);
  if ($l_loa>'')    $l_loa='Sim';
  else              $l_loa='Não';
  $l_row='';
  $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
  $l_html .=chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top">';
  $l_html .=chr(13).'        <td '.$l_row.'>';
  if ($l_exequivel=='S')    $l_html .=chr(13).'           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">';
  else                      $l_html .=chr(13).'           <img src="'.$conImgAtraso.'" border=0 width=15 height=15 align="center">';
  if (Nvl($l_word,0)==1)    $l_html .=chr(13).'        <td>'.$l_destaque.$l_titulo.'</b>';
  else                      $l_html .=chr(13).'<A class="HL" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS($w_dir,'programa.php?par=AtualizaIndicador&O=V&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&w_tipo=Volta&P1=10&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\',\'Indicador\',\'width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no\'); return false;" title="Clique para exibir os dados!">'.$l_destaque.$l_titulo.'</A>';
  $l_html .=chr(13).'        <td align="center" '.$l_row.'>'.$l_loa.'</td>';
  $l_html .=chr(13).'        <td nowrap align="right" '.$l_row.'>'.number_format(Nvl($l_valor_ref,0),2,',','.').' </td>';
  $l_html .=chr(13).'        <td nowrap align="right" '.$l_row.'>'.number_format(Nvl($l_valor_prog,0),2,',','.').' </td>';
  $l_html .=chr(13).'        <td nowrap align="right" '.$l_row.'>'.number_format(Nvl($l_valor_apurado,0),2,',','.').' </td>';
  $l_html .=chr(13).'        <td align="center" '.$l_row.'>'.Nvl(FormataDataEdicao($l_apuracao_Ind),'---').'</td>';
  $l_html .=chr(13).'        <td align="left" '.$l_row.'>'.$l_unidade_medida.'</td>';
  if ($l_oper=='S') {
    $l_html .=chr(13).'        <td align="top" nowrap '.$l_row.'>';
    // Se for listagem de indicadores no cadastramento do programa, exibe operações de alteração e exclusão
    if ($l_tipo=='PROJETO') {
      $l_html .=chr(13).'          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Alterar">Alt</A>&nbsp';
        if ((strtoupper(substr($l_titulo,0,13))==strtoupper('NAO INFORMADO')) || (strtoupper(substr($l_titulo,0,13))!=strtoupper('NAO INFORMADO') && $l_loa=='Não')) 
          $l_html .=chr(13).'          <A class="HL" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');" title="Excluir">Excl</A>&nbsp';
          // Caso contrário, é listagem de atualização do indicador. Neste caso, coloca apenas a opção de alteração
    } else {
        $l_html .=chr(13).'          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Atualiza dados do indicador">Atualizar</A>&nbsp';
    } 
    $l_html .=chr(13).'        </td>';
  } else {
    if ($l_tipo=='ETAPA') {
      $l_html .=chr(13).'        <td align="top" nowrap '.$l_row.'>';
      $l_html .=chr(13).'          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=V&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Atualiza dados do indicador">Exibir</A>&nbsp';
      $l_html .=chr(13).'        </td>';
    } 
  } 
  $l_html .=chr(13).'      </tr>';
  return $l_html;
} 
// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'GERENCIAL':               Gerencial();            break;
    case 'REL_PPA':                 Rel_PPA();              break;
    case 'REL_PROJETO':             Rel_projeto();          break;
    case 'REL_PROGRAMA':            Rel_Programa();         break;
    case 'REL_SINTETICO_PR':        Rel_Sintetico_PR();     break;
    case 'REL_SINTETICO_PPA':       Rel_Sintetico_PPA();    break;
    case 'REL_SINTETICO_PROG':      Rel_Sintetico_Prog();   break;
    case 'REL_GERENCIAL_PROG':      Rel_Gerencial_Prog();   break;
    case 'REL_GERENCIAL_ACAO':      Rel_Gerencial_Acao();   break;
    case 'REL_GERENCIAL_TAREFA':    Rel_Gerencial_Tarefa(); break;
    case 'REL_METAS':               Rel_Metas();            break;
    case 'REL_DET_TAREFA':          Rel_Det_Tarefa();       break;
    case 'REL_DET_ACAO':            Rel_Det_Acao();         break;
    case 'REL_DET_PROG':            Rel_Det_Prog();         break;
    case 'REL_LIMITE':              Rel_Limite();           break;
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