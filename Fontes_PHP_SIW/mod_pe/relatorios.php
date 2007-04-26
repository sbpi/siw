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
include_once($w_dir_volta.'classes/sp/db_getPrograma.php');
include_once($w_dir_volta.'classes/sp/db_getSolicEtpRec.php');
include_once($w_dir_volta.'classes/sp/db_getEtapaDataParents.php');
include_once($w_dir_volta.'classes/sp/db_getSolicEtapa.php');
include_once($w_dir_volta.'classes/sp/db_getSolicRestricao.php');
include_once($w_dir_volta.'classes/sp/db_getIndicador.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoPrograma.php');
include_once($w_dir_volta.'funcoes/selecaoPlanoEstrategico.php');
// =========================================================================
//  /relatorios.php
// ------------------------------------------------------------------------
// Nome     : Billy Jones Leal dos Santos
// Descricao: Rrelatórios Executivo 
// Mail     : billy@sbpi.com.br
// Criacao  : 23/04/2007 15:00
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
$w_dir          = 'mod_pe/';
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
function Rel_Executivo() {
  extract($GLOBALS);
  $w_plano    = $_REQUEST['w_plano'];
  $w_programa = $_REQUEST['w_programa'];
  $w_tipo     = $_REQUEST['w_tipo'];  
  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'') {
      $w_logo='img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
    }
    if ($w_tipo=='WORD') {
      HeaderWord(null);
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');
      CabecalhoWord($w_cliente,'RELATÓRIO EXECUTIVO DE PROGRAMAS E PROJETOS',$w_pag);
      $w_embed = 'WORD';
      //CabecalhoWord($w_cliente,$w_TP,0);
    } else {
      Cabecalho();
      $w_embed = 'EMBED';
      ShowHTML('<HEAD>');
      ShowHTML('<TITLE>Relatório executivo de programas e projetos</TITLE>');
      ShowHTML('</HEAD>');
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');
      if (nvl($w_troca,'')!='') {
        BodyOpenClean('onLoad=\''.$w_troca.'.focus()\'; ');
      } else {
        BodyOpenClean('onLoad=\'this.focus()\'; ');
      }
      ShowHTML('<center>');
      ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" SRC="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,$w_embed).'"><TD ALIGN="RIGHT"><B><FONT SIZE=3 COLOR="#000000">');
      ShowHTML('RELATÓRIO EXECUTIVO DE PROGRAMAS E PROJETOS');
      ShowHTML('</FONT><TR><TD ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
      if ($w_tipo!='WORD') {
        ShowHTML('&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
        ShowHTML('&nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.'Rel_Executivo&R='.$w_pagina.$par.'&O=L&w_tipo=WORD&w_plano='.$w_plano.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4=1&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
      } 
      ShowHTML('</TD></TR>');
      ShowHTML('</FONT></B></TD></TR></TABLE>');
    }
    ShowHTML('');
    ShowHTML('<table width="95%" border="0" cellspacing="3">');
    ShowHTML('<tr><td colspan="2">');
    $w_projeto_atual = 0;
    $RS = db_getPlanoEstrategico::getInstanceOf($dbms,$w_cliente,$w_plano, null, null,$p_inicio,$p_fim, null, 'REGISTROS');
    $RS = SortArray($RS,'sq_projeto','asc'); 
    if (count($RS)==0) {
      ShowHTML('   <tr><td colspan="2"><br><hr NOSHADE color=#000000 size=4></td></tr>');
      ShowHTML('   <tr><td colspan="2" align="center" bgcolor="#f0f0f0"><font size="2"><b>Nenhum registro encontrado para os parâmetros informados</b></td></tr>');
      ShowHTML('   <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
    } else {
      foreach ($RS as $row) {  
        ShowHTML('   <tr><td colspan="2"><br><hr NOSHADE color=#000000 size=4></td></tr>');
        ShowHTML('   <tr><td colspan="2" align="center" bgcolor="#f0f0f0"><font size="2"><b>'.strtoupper(f($row,'titulo')).'</b></td></tr>');
        ShowHTML('   <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
        //$RS1 = db_getPrograma::getInstanceOf($dbms,f($row,'chave'),$w_cliente);     
        $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PEPROCAD');
        $RS1 = db_getSolicList::getInstanceOf($dbms, f($RS1,'sq_menu'), $w_usuario, f($RS1,'sigla'), 4, null, null, null, null, null, null, null, null, null, null, $w_programa, null, null, null, null, null, null, null, null, null, null, null, null, null, null, $w_plano);
        $RS1 = SortArray($RS1,'titulo','asc');
        if (count($RS1)==0) {
          ShowHTML('   <tr><td colspan="2" align="center"><font size="1"><b>Nenhum programa cadastrado.</b></td></tr>');
        } else {
          ShowHTML('      <tr><td align="center" colspan="2">');
          ShowHTML('        <table width=100%  border="1" bordercolor="#00000">');
          $w_proj = 0;
          foreach($RS1 as $row1) {
            //Programas
            ShowHTML('        <tr><td colspan="12" height=30 valign="center"><font size="2"><b>PROGRAMA: '.strtoupper(f($row1,'cd_programa')).' - '.strtoupper(f($row1,'titulo')).'</b></td></tr>');
            ShowHTML('          <tr align="center">');
            ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><b>N°</b></td>');
            ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><b>Projeto</b></td>');
            ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><b>Responsável</b></td>');
            ShowHTML('            <td colspan=2 bgColor="#f0f0f0"><b>Execução</b></td>');
            ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><b>Orçamento</b></td>');
            ShowHTML('            <td colspan=2 bgColor="#f0f0f0"><b>Execução real</b></td>');
            if ($w_tipo!='WORD') {
              ShowHTML('            <td rowspan=2 colspan=2 bgColor="#f0f0f0"><b>'.VisualIndicador($w_dir_volta,$w_cliente,'IDE',$TP,'IDE').'</b></td>');
              ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><b>'.VisualIndicador($w_dir_volta,$w_cliente,'IGE',$TP,'IGE').'</b></td>');
            } else {
              ShowHTML('            <td rowspan=2 colspan=2 bgColor="#f0f0f0"><b>IDE</b></td>');
              ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><b>IGE</b></td>');
            }
            ShowHTML('          </tr>');
            ShowHTML('          <tr align="center">');
            ShowHTML('            <td bgColor="#f0f0f0"><b>Início</b></td>');
            ShowHTML('            <td bgColor="#f0f0f0"><b>Fim</b></td>');
            ShowHTML('            <td bgColor="#f0f0f0"><b>Início</b></td>');
            ShowHTML('            <td bgColor="#f0f0f0"><b>Fim</b></td>');
            ShowHTML('          </tr>');
            $RS2 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PJCAD');
            $RS3 = db_getSolicList::getInstanceOf($dbms,f($RS2,'sq_menu'),$w_usuario,'PJCAD',4,
                $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
                $p_unidade,$p_prioridade,$p_ativo,$p_parcerias,
                $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
                $p_uorg_resp, $p_internas, $p_prazo, $p_fase, $p_sqcc, f($row1,'sq_siw_solicitacao'), $p_atividade, 
                null, null, $p_empenho, $p_processo);
            if (count($RS3)==0) {
              ShowHTML('          <tr><td colspan="12" align="center"><b>Nenhum projeto cadastrado neste programa</b></td></tr>');
            } else {
              $l_programa[$w_proj] = 0;
              foreach($RS3 as $row3) {
                ShowHTML('          <tr valign="top" align="center">');
                ShowHTML('            <td nowrap>');    
                ShowHTML(ExibeImagemSolic(f($row3,'sigla'),f($row3,'inicio'),f($row3,'fim'),f($row3,'inicio_real'),f($row3,'fim_real'),f($row3,'aviso_prox_conc'),f($row3,'aviso'),f($row3,'sg_tramite'), null));
                if ($w_tipo!='WORD') ShowHTML('            <A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.f($row3,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.f($row3,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="_blank">'.f($row3,'sq_siw_solicitacao').'&nbsp;</a>');
                else                 ShowHTML('            '.f($row3,'sq_siw_solicitacao').''); 
                ShowHTML('            <td align="left">'.f($row3,'titulo').'</td>');
                if ($w_tipo!='WORD') ShowHTML('            <td align="left">'.ExibePessoa(null,$w_cliente,f($row3,'solicitante'),$TP,f($row3,'nm_solic')).'</td>');
                else                 ShowHTML('            <td align="left">'.f($row3,'nm_solic').'</td>'); 
                ShowHTML('            <td>'.Nvl(FormataDataEdicao(f($row3,'inicio')),'-').'</td>');
                ShowHTML('            <td>'.Nvl(FormataDataEdicao(f($row3,'fim')),'-').'</td>');
                ShowHTML('            <td align="right">'.formatNumber(f($row3,'valor')).'</td>');
                ShowHTML('            <td>'.Nvl(FormataDataEdicao(f($row3,'inicio_real')),'---').'</td>');
                ShowHTML('            <td>'.Nvl(FormataDataEdicao(f($row3,'fim_real')),'---').'</td>');
                ShowHTML('            <td>'.ExibeSmile('IDE',f($row3,'ide')).'</td>');
                ShowHTML('            <td align="right">'.formatNumber(f($row3,'ide'),2).'%'.'</td>');
                ShowHTML('            <td align="right">'.formatNumber(f($row3,'ige'),2).'%'.'</td>');
                $l_programa[$w_proj] += f($row3,'valor');
              } 
              ShowHTML('<tr valign="top">');
              ShowHTML('     <td colspan=5 align="right"><b>Total&nbsp;');
              ShowHTML('     <td align="right"><b>'.formatNumber($l_programa[$w_proj]));
              ShowHTML('     <td colspan=6>&nbsp;');
              ShowHTML('</tr>');
              $w_proj += 1;
            }
          }
          ShowHTML('        </table></td></tr>');
          ShowHTML('      <tr><td align="center" colspan="2"><br><font size=2><b>QUADRO RESUMO</b></font></td></tr>');
          ShowHTML('      <tr><td align="center" colspan="2">');
          ShowHTML('        <table width="70%" border="1" bordercolor="#00000">');
          ShowHTML('          <tr align="center">');
          ShowHTML('            <td colspan=2 bgColor="#f0f0f0"><b>Programa</b></td>');
          ShowHTML('            <td colspan=3 bgColor="#f0f0f0"><b>Orçamento</b></td>');
          ShowHTML('          </tr>');
          ShowHTML('          <tr align="center">');
          ShowHTML('            <td bgColor="#f0f0f0"><b>Sigla</b></td>');
          ShowHTML('            <td bgColor="#f0f0f0"><b>Nome</b></td>');
          ShowHTML('            <td bgColor="#f0f0f0"><b>Programa</b></td>');
          ShowHTML('            <td bgColor="#f0f0f0"><b>Projetos</b></td>');
          ShowHTML('            <td bgColor="#f0f0f0"><b>Diferença</b></td>');
          ShowHTML('          </tr>');
          $w_cont = 0;
          $w_tot_programa;
          $w_tot_projetos;
          foreach($RS1 as $row1) {
            if ((f($row1,'valor')-nvl($l_programa[$w_cont],0))<0) $w_cor = '<font color="#FF0000">'; else $w_cor='';
            ShowHTML('          <tr valign="top">');
            ShowHTML('            <td>'.strtoupper(f($row1,'cd_programa')));
            ShowHTML('            <td>'.f($row1,'titulo').'</td>');
            //ShowHTML('            <td>'.formatNumber($w_proj).'</td>');
            ShowHTML('            <td align="right">'.formatNumber(f($row1,'valor')).'</td>');
            ShowHTML('            <td align="right">'.formatNumber(nvl($l_programa[$w_cont],0)).'</td>');
            ShowHTML('            <td align="right">'.$w_cor.formatNumber(f($row1,'valor')-nvl($l_programa[$w_cont],0)).'</td>');
            $w_tot_programa += f($row1,'valor');
            $w_tot_projetos += nvl($l_programa[$w_cont],0);
            $w_cont += 1;
          }
          ShowHTML('          <tr valign="top">');
          ShowHTML('            <td colspan=2 align="right"><b>Totais&nbsp;');
          ShowHTML('            <td align="right"><b>'.formatNumber($w_tot_programa).'</td>');
          ShowHTML('            <td align="right"><b>'.formatNumber($w_tot_projetos).'</td>');
          if (($w_tot_programa-$w_tot_projetos)<0) $w_cor = '<font color="#FF0000">'; else $w_cor='';
          ShowHTML('            <td align="right"><b>'.$w_cor.formatNumber($w_tot_programa-$w_tot_projetos).'</td>');
          ShowHTML('        </table></td></tr>');
        }
      }
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
    Validate('w_plano','Plano estratégico','SELECT','1','1','18','1','1');
    ValidateClose();
    ScriptClose();
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=\'document.Form.w_plano.focus()\';');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<HR>');
    ShowHTML('<center>');
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Relatorio',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<input type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr>');
    selecaoPlanoEstrategico('<u>P</u>lano estratégico:', 'P', 'Selecione o plano que deseja listar.', $w_plano, $w_chave, 'w_plano', 'ULTIMO', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.target=\'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_programa\'; document.Form.submit();"');
    ShowHTML('      <tr>');
    selecaoPrograma('P<u>r</u>ograma:', 'R', 'Se desejar, selecione um dos programas.', $w_programa, nvl($w_plano,0), null, 'w_programa', null, null);
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
  Rodape();
} 
// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'REL_EXECUTIVO': Rel_Executivo(); break;
    default:
      cabecalho();
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');      
      BodyOpen('onLoad=this.focus();');
      Estrutura_Topo_Limpo();
      Estrutura_Menu();
      Estrutura_Corpo_Abre();
      Estrutura_Texto_Abre();
      ShowHTML('<center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center>');
      Estrutura_Texto_Fecha();
      Estrutura_Fecha();
      Estrutura_Fecha();
      Estrutura_Fecha();
      Rodape();
  } 
}
?>