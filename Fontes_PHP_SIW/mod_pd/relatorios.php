<?
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoEtapa.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');

// =========================================================================
//  /relatorios.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Diversos tipos de relatórios para fazer o acompanhamento gerencial 
// Mail     : celso@sbpi.com.br
// Criacao  : 21/04/2004 11:00
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
$P1         = $_REQUEST['P1'];
$P2         = $_REQUEST['P2'];
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = strtoupper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = strtoupper($_REQUEST['O']);

$w_assinatura   = strtoupper($_REQUEST['w_assinatura']);
$w_pagina       = 'relatorios.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_pd/';
$w_troca        = $_REQUEST['w_troca'];
$w_copia        = $_REQUEST['w_copia'];

if ($O=='') $O='P';  

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';  break;
  case 'A': $w_TP=$TP.' - Alteração'; break;
  case 'E': $w_TP=$TP.' - Exclusão';  break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  case 'C': $w_TP=$TP.' - Cópia';     break;
  case 'V': $w_TP=$TP.' - Envio';     break;
  case 'H': $w_TP=$TP.' - Herança';   break;
  default : $w_TP=$TP.' - Listagem'; 
}

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_ano      = RetornaAno();

$p_ordena       = strtolower($_REQUEST['p_ordena']);

// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$RS = db_getLinkSubMenu::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);

if (count($RS)>0) {
  $w_submenu='Existe';
} else {
  $w_submenu='';
} 

// Recupera a configuração do serviço
if ($P2>0) {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,$P2);
} else {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,$w_menu);
}

// Se for sub-menu, pega a configuração do pai
if ($RS_Menu['ultimo_nivel']=='S') {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Relatório de limites das unidades
// -------------------------------------------------------------------------
function Rel_Limite() {
  extract($GLOBALS);

  $p_sq_unidade = strtoupper(trim($_REQUEST['p_sq_unidade']));
  $p_projeto    = strtoupper(trim($_REQUEST['p_projeto']));
  $p_atividade  = strtoupper(trim($_REQUEST['p_atividade']));
  $p_sqcc       = strtoupper(trim($_REQUEST['p_sqcc']));
  $w_tipo_rel   = strtoupper(trim($_REQUEST['w_tipo_rel']));
  $w_det_pcd    = strtoupper(trim($_REQUEST['w_det_pcd']));

  $w_cont                   = 0;
  $w_diaria_limite          = 0;
  $w_tot_diaria_limite      = 0;
  $w_trecho_limite          = 0;
  $w_tot_trecho_limite      = 0;
  $w_diaria_utilizado       = 0;
  $w_tot_diaria_utilizado   = 0;
  $w_trecho_utilizado       = 0;
  $w_tot_trecho_utilizado   = 0;



  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'') {
      $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
    } 
    $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PDINICIAL');
    $RS = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,'GRPDUNIDADE',4,
            null, null,null,null,null,null,null,$p_sq_unidade,null,null,null,null, null, null, null, null, null, null,
            null, null, null, null, $p_sqcc, $p_projeto, $p_atividade, null, $w_ano);
    $RS = SortArray($RS,'sq_unidade_resp','asc');
  }
  
  if ($w_tipo_rel=='WORD') {
    HeaderWord(null);
    $w_pag   = 1;
    $w_linha = 8;
    ShowHTML('<font size=0 color="'.$conBodyBgColor.'">.</font><BASE HREF="'.$conRootSIW.'">');
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
    if ($O=='P') {
      ScriptOpen('JavaScript');
      ValidateOpen('Validacao');
      Validate('p_sq_unidade','Responsável','HIDDEN','','2','60','1','1');
      Validate('p_projeto','Programa','HIDDEN','','1','18','1','1');
      Validate('p_atividade','Ação','HIDDEN','','1','18','1','1');
      ValidateClose();
      ScriptClose();
    } 

    ShowHTML('</HEAD>');
    ShowHTML('<font size=0 color="'.$conBodyBgColor.'">.</font><BASE HREF="'.$conRootSIW.'">');
    if ($O=='L') {
      BodyOpenClean('onLoad=\'this.focus()\';');
      ShowHTML('<font size=0 color="'.$conBodyBgColor.'">.</font><BASE HREF="'.$conRootSIW.'">');
      ShowHTML('<div align="center">');
      ShowHTML('<table width="95%" border="0" cellspacing="3">');
      ShowHTML('<tr><td colspan="2">');
      ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"></TD><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
      ShowHTML('RELATÓRIO DE LIMITES<br> Exercício '.$w_ano);
      ShowHTML('</FONT></B></TD></TR></TABLE>');
    } else {
      BodyOpen('onLoad=\'document.Form.p_projeto.focus()\';');
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
      ShowHTML('<tr><td width="15%"><font size="1"><b>Área de planejamento:</b></font></td><td><font size="1">'.f($RS1,'nome').' - '.f($RS1,'sigla').'</font></td>');
    } else {
      ShowHTML('<tr><td width="15%"><font size="1"><b>Área de planejamento:</b></font></td><td><font size="1">Todas</font></td>');
    } 

    if ($p_projeto>'') {
      $RS1 = db_getProgramaPPA_IS::getInstanceOf($dbms,$p_projeto,$w_cliente,$w_ano,null,null,null,null);
      foreach($RS1 as $row1) { $RS1 = $row1; break; }
      ShowHTML('    <td width="7%"><font size="1"><b>Programa:</b></font></td><td nowrap><font size="1">'.$p_projeto.' - '.f($RS1,'ds_programa').'</font></td></tr>');
    } else {
      ShowHTML('    <td width="7%"><font size="1"><b>Programa:</b></font></td><td><font size="1">Todos</font></td></tr>');
    } 

    if ($p_atividade>'') {
      $RS1 = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,$p_projeto,substr($p_atividade,4,4),null,null,null,null,null,null,null);
      foreach($RS1 as $row1) { $RS1 = $row1; break; }
      ShowHTML('<tr valign="top"><td><font size="1"><b>Ação:</b></font></td><td><font size="1">'.substr($p_atividade,4,4).' - '.f($RS1,'descricao_acao').'</font></td>');
    } else {
      ShowHTML('<tr valign="top"><td><font size="1"><b>Ação:</b></font></td><td><font size="1">Todas</font></td>');
    } 

    ShowHTML('</ul></td></tr></table>');
    ShowHTML('</div></td></tr>');
    ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
    ShowHTML('<tr><td colspan="2"><div align="center"><font size="3"><b>RELATÓRIO DE LIMITES</b></font></div></td></tr>');
    $w_linha = 9;
    if (count($RS)<=0) {
      $w_linha += 1;
      ShowHTML('    <tr><td colspan="13"><div align="center"><font size="3" color="red"><b><br>Nenhuma PCD encontrada</b></div></td></tr>');
    } else {
      ShowHTML('   <tr><td colspan="2">');
      ShowHTML('     <table width=100%  border="1" bordercolor="#00000">');
      ShowHTML('       <tr bgcolor="'.$conTrAlternateBgColor.'"><td align="center" rowspan="2"><b>Unidade</b></td>');
      ShowHTML('           <td colspan="3" align="center"><b>Passagem</b></td>');
      ShowHTML('           <td colspan="3" align="center"><b>Diária</b></td>');
      ShowHTML('       </tr>');
      ShowHTML('       <tr bgcolor="'.$conTrAlternateBgColor.'">');
      ShowHTML('           <td align="center"><b>Limite(R$)</b></td>');
      ShowHTML('           <td align="center"><b>Utilizado(R$)</b></td>');
      ShowHTML('           <td align="center"><b>Saldo(R$)</b></td>');
      ShowHTML('           <td align="center"><b>Limite(R$)</b></td>');
      ShowHTML('           <td align="center"><b>Utilizado(R$)</b></td>');
      ShowHTML('           <td align="center"><b>Saldo(R$)</b></td></tr>');
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
          $w_linha   = 9;
          $w_pag    += 1;
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
            ShowHTML('<tr><td width="15%"><font size="1"><b>Área de planejamento:</b></font></td><td><font size="1">'.f($RS1,'nome').' - '.f($RS1,'sigla').'</font></td>');
          } else {
            ShowHTML('<tr><td width="15%"><font size="1"><b>Área de planejamento:</b></font></td><td><font size="1">Todas</font></td>');
          } 

          if ($p_projeto>'') {
            $RS1 = db_getProgramaPPA_IS::getInstanceOf($dbms,$p_projeto,$w_cliente,$w_ano,null,null,null,null);
            ShowHTML('    <td width="7%"><font size="1"><b>Programa:</b></font></td><td nowrap><font size="1">'.$p_projeto.' - '.f($RS1,'ds_programa').'</font></td></tr>');
          } else {
            ShowHTML('    <td width="7%"><font size="1"><b>Programa:</b></font></td><td><font size="1">Todos</font></td></tr>');
          } 

          if ($p_atividade>'') {
            $RS1 = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,$p_projeto,substr($p_atividade,4,4),null,null,null,null,null,null,null);
            ShowHTML('<tr valign="top"><td><font size="1"><b>Ação:</b></font></td><td><font size="1">'.substr($p_atividade,4,4).' - '.f($RS1,'descricao_acao').'</font></td>');
          } else {
            ShowHTML('<tr valign="top"><td><font size="1"><b>Ação:</b></font></td><td><font size="1">Todas</font></td>');
          } 

          ShowHTML('</ul></td></tr></table>');
          ShowHTML('</div></td></tr>');
          ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
          ShowHTML('<tr><td colspan="2"><div align="center"><font size="3"><b>RELATÓRIO DE LIMITES</b></font></div></td></tr>');
        } 

        if ($w_unidade_atual!='' && ($w_unidade_atual!=f($row,'nm_unidade_resp'))) {
          ShowHTML('    <tr><td>'.$w_unidade_atual.'</td>');
          ShowHTML('        <td align="right">'.number_format(Nvl($w_trecho_limite,0),2,',','.').'</td>');
          ShowHTML('        <td align="right">'.number_format(Nvl($w_trecho_utilizado,0),2,',','.').'</td>');
          ShowHTML('        <td align="right">'.number_format((Nvl($w_trecho_limite,0)-Nvl($w_trecho_utilizado,0)),2,',','.').'</td>');
          ShowHTML('        <td align="right">'.number_format(Nvl($w_diaria_limite,0),2,',','.').'</td>');
          ShowHTML('        <td align="right">'.number_format(Nvl($w_diaria_utilizado,0),2,',','.').'</td>');
          ShowHTML('        <td align="right">'.number_format((Nvl($w_diaria_limite,0)-Nvl($w_diaria_utilizado,0)),2,',','.').'</td>');

          $w_tot_trecho_limite += Nvl($w_trecho_limite,0);
          $w_valor[$i][1]       = Nvl($w_trecho_limite,0);
          $w_valor[$i][2]       = Nvl($w_trecho_utilizado,0);
          $w_trecho_utilizado   = 0;

          $w_tot_diaria_limite += Nvl($w_diaria_limite,0);
          $w_valor[$i][3]       = Nvl($w_diaria_limite,0);
          $w_valor[$i][4]       = Nvl($w_diaria_utilizado,0);
          $w_diaria_utilizado   = 0;

          $i += 1;
        } 
        $w_trecho_limite        = f($row,'limite_passagem');
        $w_trecho_utilizado    += Nvl(f($row,'valor_trecho'),0);
        $w_tot_trecho_utilizado+= Nvl(f($row,'valor_trecho'),0);

        $w_diaria_limite        = f($row,'limite_diaria');
        $w_diaria_utilizado    += Nvl(f($row,'valor_diaria'),0)+Nvl(f($row,'valor_adicional'),0)-Nvl(f($row,'desconto_alimentacao'),0)-Nvl(f($row,'desconto_transporte'),0);
        $w_tot_diaria_utilizado+= Nvl(f($row,'valor_diaria'),0)+Nvl(f($row,'valor_adicional'),0)-Nvl(f($row,'desconto_alimentacao'),0)-Nvl(f($row,'desconto_transporte'),0);

        $w_unidade_atual        = f($row,'nm_unidade_resp');
      } 
      $w_linha += 8;
      ShowHTML('    <tr><td>'.$w_unidade_atual.'</td>');
      ShowHTML('        <td align="right">'.number_format(Nvl($w_trecho_limite,0),2,',','.').'</td>');
      ShowHTML('        <td align="right">'.number_format(Nvl($w_trecho_utilizado,0),2,',','.').'</td>');
      ShowHTML('        <td align="right">'.number_format((Nvl($w_trecho_limite,0)-Nvl($w_trecho_utilizado,0)),2,',','.').'</td>');
      ShowHTML('        <td align="right">'.number_format(Nvl($w_diaria_limite,0),2,',','.').'</td>');
      ShowHTML('        <td align="right">'.number_format(Nvl($w_diaria_utilizado,0),2,',','.').'</td>');
      ShowHTML('        <td align="right">'.number_format((Nvl($w_diaria_limite,0)-Nvl($w_diaria_utilizado,0)),2,',','.').'</td>');

      $w_tot_trecho_limite  += Nvl($w_trecho_limite,0);
      $w_valor[$i][1]        = Nvl($w_trecho_limite,0);
      $w_valor[$i][2]        = Nvl($w_trecho_utilizado,0);

      $w_tot_diaria_limite  += Nvl($w_diaria_limite,0);
      $w_valor[$i][3]        = Nvl($w_diaria_limite,0);
      $w_valor[$i][4]        = Nvl($w_diaria_utilizado,0);


      ShowHTML('    <tr bgcolor="'.$conTrAlternateBgColor.'"><td align="right"><b>Totais</b></td>');
      ShowHTML('        <td align="right"><b>'.number_format(Nvl($w_tot_trecho_limite,0),2,',','.').'</b></td>');
      ShowHTML('        <td align="right"><b>'.number_format(Nvl($w_tot_trecho_utilizado,0),2,',','.').'</b></td>');
      ShowHTML('        <td align="right"><b>'.number_format((Nvl($w_tot_trecho_limite,0)-Nvl($w_tot_trecho_utilizado,0)),2,',','.').'</b></td>');
      ShowHTML('        <td align="right"><b>'.number_format(Nvl($w_tot_diaria_limite,0),2,',','.').'</b></td>');
      ShowHTML('        <td align="right"><b>'.number_format(Nvl($w_tot_diaria_utilizado,0),2,',','.').'</b></td>');
      ShowHTML('        <td align="right"><b>'.number_format((Nvl($w_tot_diaria_limite,0)-Nvl($w_tot_diaria_utilizado,0)),2,',','.').'</b></td>');
      ShowHTML('</table>');
      if (strtoupper($w_det_pcd)==strtoupper('sim')) {
        ShowHTML('<br><br><br><tr><td colspan="2"><div align="center"><font size="3"><b>DETALHAMENTO DAS PCD\'S</b></font></div></td></tr>');
        Reset($RS);
        $w_unidade_atual        = '';
        $i                      = 1;
        $w_tot_trecho_limite    = 0;
        $w_tot_diaria_limite    = 0;
        ShowHTML('<tr><td colspan="2">');
        ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
        foreach($RS as $row) {
          if ($w_linha>19 && $w_tipo_rel=='WORD') {
            ShowHTML('</table>');
            ShowHTML('</table>');
            ShowHTML('</table>');
            ShowHTML('</div>');
            ShowHTML('    <br style="page-break-after:always">');
            $w_linha  = 9;
            $w_pag   += 1;
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
              ShowHTML('<tr><td width="15%"><font size="1"><b>Área de planejamento:</b></font></td><td><font size="1">'.f($RS1,'nome').' - '.f($RS1,'sigla').'</font></td>');
            } else {
              ShowHTML('<tr><td width="15%"><font size="1"><b>Área de planejamento:</b></font></td><td><font size="1">Todas</font></td>');
            } 

            if ($p_projeto>'') {
              $RS1 = db_getProgramaPPA_IS::getInstanceOf($dbms,$p_projeto,$w_cliente,$w_ano,null,null,null,null);
              foreach($RS1 as $row1) { $RS1 = $row1; break; }
              ShowHTML('    <td width="7%"><font size="1"><b>Programa:</b></font></td><td nowrap><font size="1">'.$p_projeto.' - '.f($RS1,'ds_programa').'</font></td></tr>');
            } else {
              ShowHTML('    <td width="7%"><font size="1"><b>Programa:</b></font></td><td><font size="1">Todos</font></td></tr>');
            } 

            if ($p_atividade>'') {
              $RS1 = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,$p_projeto,substr($p_atividade,4,4),null,null,null,null,null,null,null);
              foreach($RS1 as $row1) { $RS1 = $row1; break; }
              ShowHTML('<tr valign="top"><td><font size="1"><b>Ação:</b></font></td><td><font size="1">'.substr($p_atividade,4,4).' - '.f($RS1,'descricao_acao').'</font></td>');
            } else {
              ShowHTML('<tr valign="top"><td><font size="1"><b>Ação:</b></font></td><td><font size="1">Todas</font></td>');
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
              ShowHTML('       <tr bgcolor="'.$conTrAlternateBgColor.'"><td colspan="2" align="right"><b>Totais</b></td>');
              ShowHTML('           <td align="right"><b>'.number_format(Nvl($w_tot_trecho_limite,0),2,',','.').'</b></td>');
              ShowHTML('           <td align="right"><b>'.number_format(Nvl($w_tot_diaria_limite,0),2,',','.').'</b></td>');
              ShowHTML('</table>');
              $w_tot_trecho_limite = 0;
              $w_tot_diaria_limite = 0;
            } 
            ShowHTML('<br><tr><td colspan="7"><hr NOSHADE color=#000000 size=4></td></tr>');
            ShowHTML('  <tr valign="top" bgcolor="#f0f0f0"><td width="40%"><font size="2"><b>'.f($row,'nm_unidade_resp').'</b></td>');
            ShowHTML('      <td colspan="6">');
            ShowHTML('    <table width=100%  border="1">');
            ShowHTML('      <tr><td>');
            ShowHTML('          <td align="center"><b>Limite(R$)</b></td>');
            ShowHTML('          <td align="center"><b>Utilizado(R$)</b></td>');
            ShowHTML('          <td align="center"><b>Saldo(R$)</b></td></tr>');
            ShowHTML('      <tr><td><b>Passagem</b></td>');
            ShowHTML('          <td align="right">'.number_format($w_valor[$i][1],2,',','.').'</td>');
            ShowHTML('          <td align="right">'.number_format($w_valor[$i][2],2,',','.').'</td>');
            ShowHTML('          <td align="right">'.number_format(($w_valor[$i][1]-$w_valor[$i][2]),2,',','.').'</td></tr>');
            ShowHTML('      <tr><td><b>Diária</b></td>');
            ShowHTML('          <td align="right">'.number_format($w_valor[$i][3],2,',','.').'</td>');
            ShowHTML('          <td align="right">'.number_format($w_valor[$i][4],2,',','.').'</td>');
            ShowHTML('          <td align="right">'.number_format(($w_valor[$i][3]-$w_valor[$i][4]),2,',','.').'</td><tr>');
            ShowHTML('    </table>');
            ShowHTML('   <tr><td colspan="7"><hr NOSHADE color=#000000 size=4></td></tr>');
            $i += 1;
            ShowHTML('   <tr><td colspan="7">');
            ShowHTML('     <table width=100%  border="1" bordercolor="#00000">');
            ShowHTML('       <tr bgcolor="'.$conTrAlternateBgColor.'"><td align="center"><b>PCD</b></td>');
            ShowHTML('           <td align="center"><b>Proposto</b></td>');
            ShowHTML('           <td align="center"><b>Utilizado passagem</b></td>');
            ShowHTML('           <td align="center"><b>Utilizado diária</b></td></tr>');
            $w_linha += 4;
          } 

          if ($w_tipo_rel=='WORD') {
            ShowHTML('       <tr><td>'.f($row,'codigo_interno').'</td>');
          } else {
            ShowHTML('       <tr><td><A class="HL" HREF="'.$w_dir.'viagem.php?par='.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'codigo_interno').'</a></td>');
          } 

          ShowHTML('              <td>'.f($row,'nm_prop').'</td>');
          ShowHTML('              <td align="right">'.number_format(Nvl(f($row,'valor_trecho'),0),2,',','.').'</td>');
          ShowHTML('              <td align="right">'.number_format((Nvl(f($row,'valor_diaria'),0)-Nvl(f($row,'desconto_alimentacao'),0)+Nvl(f($row,'valor_adicional'),0)-Nvl(f($row,'desconto_transporte'),0)),2,',','.').'</td>');
          $w_tot_trecho_limite  += Nvl(f($row,'valor_trecho'),0);
          $w_tot_diaria_limite  += Nvl(f($row,'valor_diaria'),0)+Nvl(f($row,'valor_adicional'),0)-Nvl(f($row,'desconto_alimentacao'),0)-Nvl(f($row,'desconto_transporte'),0);
          $w_unidade_atual       = f($row,'nm_unidade_resp');
          $w_linha              += 1;
        } 
        ShowHTML('       <tr bgcolor="'.$conTrAlternateBgColor.'"><td colspan="2" align="right"><b>Total</b></td>');
        ShowHTML('           <td align="right"><b>'.number_format(Nvl($w_tot_trecho_limite,0),2,',','.').'</b></td>');
        ShowHTML('           <td align="right"><b>'.number_format(Nvl($w_tot_diaria_limite,0),2,',','.').'</b></td>');
        ShowHTML('</table>');
        ShowHTML('</table>');
      } 
    } 
    ShowHTML('   <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONSULTA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
    ShowHTML('   <tr><td><font size="1"><b>Consulta Realizada por:</b></font></td>');
    ShowHTML('       <td><font size="1">'.$_SESSION['NOME_RESUMIDO'].'</font></td></tr>');
    ShowHTML('   <tr><td><font size="1"><b>Data da Consulta:</b></font></td>');
    ShowHTML('       <td><font size="1">'.DataHora().'</font></td></tr>');
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
    SelecaoUnidade('Á<U>r</U>ea planejamento:','R',null,$p_sq_unidade,null,'p_sq_unidade','VIAGEM','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_sq_unidade\'; document.Form.target=\'\'; document.Form.O.value=\'P\'; document.Form.submit();"');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PJCAD');
    SelecaoProjeto('Pr<u>o</u>jeto:','O','Selecione o projeto desejado na relação.',$p_projeto,$w_usuario,f($RS,'sq_menu'),'p_projeto','PJLIST','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.target=\'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_atividade\'; document.Form.submit();"');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    SelecaoEtapa('Eta<u>p</u>a:','P','Selecione a etapa desejada na relação.',$p_atividade,$p_projeto,null,'p_atividade',null,null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    SelecaoCC('C<u>l</u>assificação:','L','Selecione a classificação desejada.',$p_sqcc,null,'p_sqcc','SIWSOLIC');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('        <td><font size="1"><b><u>T</u>ipo de relatório:</b><br><SELECT ACCESSKEY="T" CLASS="STS" NAME="w_tipo_rel" '.$w_Disabled.'>');
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
    ShowHTML('      <tr bgcolor="'.$conTrAlternateBgColor.'"><td colspan="2"><font size="1"><b>ESCOLHA OS BLOCOS A SEREM VISUALIZADOS NO RELATÓRIO</b></font></td></tr>');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('          <td colspan="2"><font size="1"><INPUT '.$w_Disabled.' class="STC" type="CHECKBOX" name="w_det_pcd" value="sim"> Detalhamento das PCD\'s</td>');
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
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);

  switch ($par) {
  case 'REL_LIMITE':    Rel_Limite();   break;
  default:
    Cabecalho();
    ShowHTML('<font size=0 color="'.$conBodyBgColor.'">.</font><BASE HREF="'.$conRootSIW.'">');
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
