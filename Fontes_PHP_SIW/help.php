<?
session_start();
include_once("constants.inc");
include_once("jscript.php");
include_once("funcoes.php");
include_once("classes/db/abreSessao.php");
include_once("classes/sp/db_getMenuCode.php");
include_once("classes/sp/db_getSiwCliModLis.php");
include_once("classes/sp/db_getLinkData.php");
include_once("classes/sp/db_getModData.php");
include_once("classes/sp/db_getCustomerData.php");
include_once("classes/sp/db_getSegModData.php");
include_once("classes/sp/db_getLinkDataHelp.php");
include_once("classes/sp/db_getTramiteList.php");
header('Expires: '.-1500);
// =========================================================================
//  /Help.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerencia o módulo de demandas
// Mail     : alex@sbpi.com.br
// Criacao  : 15/10/2003 12:25
// Versao   : 1.0.0.0
// Local    : Brasília - DF
// -------------------------------------------------------------------------
// 
// Parâmetros recebidos:
//    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
//    O (operação)   = L   : Listagem
//                   = P   : Filtragem
//                   = V   : Geração de gráfico
//                   = W   : Geração de documento no formato MS-Word (Office 2003)

// Verifica se o usuário está autenticado

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }


// Declaração de variáveis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par        = strtoupper($_REQUEST["par"]);
$P1         = $_REQUEST["P1"];
$P2         = $_REQUEST["P2"];
$P3         = $_REQUEST["P3"];
$P4         = $_REQUEST["P4"];
$TP         = $_REQUEST["TP"];
$SG         = strtoupper($_REQUEST["SG"]);
$R          = strtoupper($_REQUEST["R"]);
$O          = strtoupper($_REQUEST["O"]);
$w_troca    = strtoupper($_REQUEST["w_troca"]);

$w_Assinatura   = strtoupper(${"w_Assinatura"});
$w_pagina       = "help.php?par=";
$w_Disabled     = "ENABLED";

$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente, $SG);
$w_ano      = RetornaAno();

if ($O=='') $O='L';

$w_TP = $TP;

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Exibe visão geral do help
// -------------------------------------------------------------------------
function Help() {
  extract($GLOBALS);

  $w_sq_modulo = $_REQUEST['w_sq_modulo'];

  if ($w_sq_modulo == '') {
    $RS = db_getLinkData::getInstanceOf($dbms, $w_cliente, $SG);
    $w_modulo = f($RS,'sq_modulo');
  } else {
    $w_modulo = $w_sq_modulo;
  } 

  $RS = db_getModData::getInstanceOf($dbms, $w_modulo);
  $w_nome_modulo    = f($RS,'Nome');
  $w_objetivo_geral = f($RS,'objetivo_geral');

  $RS = db_getCustomerData::getInstanceOf($dbms, $w_cliente);
  $w_segmento       = f($RS,'sq_segmento');


  $w_objetivo_espec = 'Não informado';
  $RS = db_getSegModData::getInstanceOf($dbms, $w_segmento, $w_modulo);
  $w_objetivo_espec = f($RS,'objetivo_especif');

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$w_TP.'</TITLE>');
  ShowHTML('</HEAD>');
  BodyOpenClean('onLoad=document.focus();');
  if ($O=="L") {
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
    ShowHTML('<HR>');
  } 

  ShowHTML('<div align=center><center>');
  if ($w_sq_modulo>"") {
    ShowHTML('<center><B>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></center>');
  } 


  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');

  if ($O=="L") {
    ShowHTML('      <tr valign="top"><td colspan=2>');
    ShowHTML('         <P><font face="Arial" size="3"><b>Módulo: '.$w_nome_modulo.'</font></b></P>');
    ShowHTML('         <font size="2"><DL>');
    ShowHTML('         <DT><b>Objetivo geral:</b>');
    ShowHTML('           <DD>'.$w_objetivo_geral.'</DD>');
    ShowHTML('         </DT>');
    ShowHTML('         <DT><br><b>Objetivo(s) específico(s):</b>');
    ShowHTML('         <DD><UL><LI>'.str_replace("\r\n","<LI>",$w_objetivo_espec).'</UL>');
    ShowHTML('         </DT></DL>');
    ShowHTML('      <tr><td><BR>');
    ShowHTML('      <tr align="center" valign="top"><td><td bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b>Funcionalidades</td>');
    $RS = db_getLinkdataHelp::getInstanceOf($dbms, $w_cliente,$w_modulo,0,'IS NULL');
    ShowHTML('      <tr valign="top"><td colspan=2><font size=2><br>');
    if (count($RS) <= 0) {
       ShowHTML('      <b>Não há funcionalidades disponíveis.</b>');
    } else {
      $w_cont1 = 0;
      foreach ($RS as $row) {
        $w_nivel = 1;
        $w_cont1 = $w_cont1+1;
        $w_cont2 = 0;
        $w_cont3 = 0;
        $w_cont4 = 0;
        ShowHTML('         <DL><DT><b>'.$w_cont1.'. '.f($row,'nome').'</b>');
        ShowHTML('             <DD>Finalidade: '.ExibeTexto(f($row,'finalidade')));

        if (f($row,'tramite')=='S') ShowHTML('        <DD><BR>Como funciona: '.ExibeTexto(f($row,'como_funciona')));
        if (f($row,'Filho')>0) {
          $RS1 = db_getLinkdataHelp::getInstanceOf($dbms, $w_cliente,$w_modulo,0,f($row,'sq_menu'));
          foreach ($RS1 as $row1) {
            if ($w_cont2==0 && f($row1,'ultimo_nivel') == 'S') {
              $w_submenu='S';
              ShowHTML('             <DD><BR>Telas contidas: ');
              ShowHTML('             <blockquote>');
            } 
            $w_cont2 = $w_cont2+1;
            $w_cont3 = 0;
            $w_cont4 = 0;
            ShowHTML('             </DT>');
            ShowHTML('             <DT><BR><b>'.$w_cont1.'.'.$w_cont2.'. '.f($row1,'nome').'</b>');
            ShowHTML('             <DD>Finalidade: '.ExibeTexto(f($row1,'finalidade')));
            if (f($row1,'tramite')=='S') {
              ShowHTML('        <DD><BR>Como funciona: '.ExibeTexto(f($row1,'como_funciona')));

              // Verifica se têm trâmites e exibe
              $RS_Tramite = db_getTramiteList::getInstanceOf($dbms, f($row1,'sq_menu'), null);
              if (count($RS_Tramite) > 0) {
                ShowHTML('    <DD><BR>Fases:');
                ShowHTML('    <DD><TABLE bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
                ShowHTML('        <tr align="center" valign="top">');
                ShowHTML('          <td><b>Ordem</td>');
                ShowHTML('          <td><b>Nome</td>');
                ShowHTML('          <td><b>Descricao</td>');
                ShowHTML('          <td><b>Quem cumpre</td>');
                ShowHTML('        </tr>');
                foreach ($RS_Tramite as $row_tramite) {
                  ShowHTML('      <tr valign="top">');
                  ShowHTML('        <td align="center">'.f($row_tramite,'ordem').'</td>');
                  ShowHTML('        <td>'.f($row_tramite,'nome').'</td>');
                  ShowHTML('        <td>'.Nvl(f($row_tramite,'descricao'),"---").'</td>');
                  ShowHTML('        <td>'.Nvl(f($row_tramite,'nm_chefia'),"---").'</td>');
                  ShowHTML('        </td>');
                  ShowHTML('      </tr>');
                } 
                ShowHTML('    </table>');
              } 
            } 
  
            if (f($row1,'Filho')>0) {
              $RS2 = db_getLinkdataHelp::getInstanceOf($dbms, $w_cliente,$w_modulo,0,f($row1,'sq_menu'));
              foreach ($RS2 as $row2) {
                if ($w_cont3==0 && f($row2,'ultimo_nivel') == 'S') {
                  $w_submenu = 'S';
                  ShowHTML('             <DD><BR>Telas contidas: ');
                  ShowHTML('             <blockquote>');
                } 
                $w_cont3 = $w_cont3+1;
                $w_cont4 = 0;
                if ($w_submenu=='S' && $w_cont3==1) {
                  ShowHTML('             <DT><b>'.$w_cont1.'.'.$w_cont2.'.'.$w_cont3.'. '.f($row2,'nome').'</b>');
                } else {
                  ShowHTML('             <DT><BR><b>'.$w_cont1.'.'.$w_cont2.'.'.$w_cont3.'. '.f($row2,'nome').'</b>');
                } 
  
                ShowHTML('             <DD>Finalidade: '.ExibeTexto(f($row2,'finalidade')));
                if (f($row2,'tramite') == 'S') {
                  $w_submenu='S';
                  ShowHTML('        <DD><BR>Como funciona: '.ExibeTexto(f($row2,'como_funciona')));
                  if (f($row2,'ultimo_nivel')=='S' && $w_submenu=='N') {
  
                    // Verifica se têm trâmites e exibe
                    $RS_Tramite = db_getTramiteList::getInstanceOf($dbms, f($row2,'sq_menu'), null);
                    if (count($RS_Tramite) > 0) {
                      ShowHTML('    <DD><BR>Fases:');
                      ShowHTML('    <DD><TABLE WIDTH="70%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
                      ShowHTML('        <tr align="center" valign="top">');
                      ShowHTML('          <td><b>Ordem</td>');
                      ShowHTML('          <td><b>Nome</td>');
                      ShowHTML('          <td><b>Descricao</td>');
                      ShowHTML('          <td><b>Quem cumpre</td>');
                      ShowHTML('        </tr>');
                      foreach ($RS_Tramite as $row_tramite) {
                        ShowHTML('      <tr valign="top">');
                        ShowHTML('        <td align="center">'.f($row_tramite,'ordem').'</td>');
                        ShowHTML('        <td>'.f($row_tramite,'nome').'</td>');
                        ShowHTML('        <td>'.Nvl(f($row_tramite,'descricao'),"---").'</td>');
                        ShowHTML('        <td>'.Nvl(f($row_tramite,'nm_chefia'),"---").'</td>');
                        ShowHTML('        </td>');
                        ShowHTML('      </tr>');
                      } 
                      ShowHTML('    </table><br>');
                    } 
                  } 
                } 
  
                if (f($row2,'Filho')>0) {
                  $RS3 = db_getLinkdataHelp::getInstanceOf($dbms, $w_cliente,$w_modulo,0,f($row2,'sq_menu'));
                  foreach ($RS3 as $row3) {
                    if ($w_cont4==0 && f($row3,'ultimo_nivel') == 'S') {
                      $w_submenu='S';
                      ShowHTML('             <DD><BR>Telas contidas: ');
                      ShowHTML('             <blockquote>');
                    } 
                    $w_cont4 = $w_cont4+1;
                    ShowHTML('             <DT><BR><b>'.$w_cont1.'.'.$w_cont2.'.'.$w_cont3.'.'.$w_cont4.'. '.f($row1,'nome').'</b>');
                    ShowHTML('             <DD>Finalidade: '.ExibeTexto(f($row3,'finalidade')));

                    if (f($row3,'tramite') == 'S') ShowHTML('        <DD><BR>Como funciona: '.ExibeTexto(f($row3,'como_funciona')));
                    if (f($row3,'ultimo_nivel')=='S' && $w_submenu=='N') {
                      // Verifica se têm trâmites e exibe
                      $RS_Tramite = db_getTramiteList::getInstanceOf($dbms, f($row3,'sq_menu'), null);
                      if (count($RS_Tramite) > 0) {
                        ShowHTML('    <DD><BR>Fases:');
                        ShowHTML('    <DD><TABLE WIDTH="70%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
                        ShowHTML('        <tr align="center" valign="top">');
                        ShowHTML('          <td><b>Ordem</td>');
                        ShowHTML('          <td><b>Nome</td>');
                        ShowHTML('          <td><b>Descricao</td>');
                        ShowHTML('          <td><b>Quem cumpre</td>');
                        ShowHTML('        </tr>');
                        foreach ($RS_Tramite as $row_tramite) {
                          ShowHTML('      <tr valign="top">');
                          ShowHTML('        <td align="center">'.f($row_tramite,'ordem').'</td>');
                          ShowHTML('        <td>'.f($row_tramite,'nome').'</td>');
                          ShowHTML('        <td>'.Nvl(f($row_tramite,'descricao'),"---").'</td>');
                          ShowHTML('        <td>'.Nvl(f($row_tramite,'nm_chefia'),"---").'</td>');
                          ShowHTML('        </td>');
                          ShowHTML('      </tr>');
                        } 
                        ShowHTML('    </table><br>');
                      }
                    } 
                  } 
                  if ($w_submenu=='S') {
                    ShowHTML('       </blockquote>');
                    $w_submenu='N';
                  } 
                }
              }
              if ($w_submenu=='S') {
                ShowHTML('       </blockquote>');
                $w_submenu = 'N';
              } 
            }
          }
          if ($w_submenu == 'S') {
            ShowHTML('       </blockquote>');
            $w_submenu = 'N';
          }
        } 
        ShowHTML('         </DT></DL>');
      } 
      if ($w_submenu == 'S') {
        ShowHTML('       </blockquote>');
        $w_submenu = 'N';
      } 
    } 
    DesconectaBD();
    ShowHTML('         </table></td></tr>');
    ShowHTML('     </tr></tr></td></table>');
    ShowHTML('</table>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');

  if ($w_sq_modulo>'') {
    ShowHTML('<center><B>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></center>');
  } 

  ShowHTML('</center>');
  Rodape();

  return $function_ret;
} 

// =========================================================================
// Rotina de menu do help
// -------------------------------------------------------------------------
function Menu() {
  extract($GLOBALS);

  if ($O=='L') {
     // Recupera os módulos contratados pelo cliente
     $RS = db_getSiwCliModLis::getInstanceOf($dbms, $w_cliente, null);
  }
  
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$w_TP.'</TITLE>');
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=document.focus();');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" width="100%" cellpadding="0" cellspacing="0" >');
  if ($O=="L") {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor='.$conTableBgColor.' BORDER='.$conTableBorder.' CELLSPACING='.$conTableCellSpacing.' CELLPADDING='.$conTableCellPadding.' BorderColorDark='.$conTableBorderColorDark.' BorderColorLight='.$conTableBorderColorLight.'>');
    ShowHTML('        <tr bgcolor='.$conTrBgColor.' align="center">');
    ShowHTML('          <td><b>Módulo</td>');
    ShowHTML('          <td><b>Objetivo geral</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><font  size="2"><b>Nenhum registro encontrado.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        if ($w_cor==$conTrBgColor || $w_cor=='') $w_cor=$conTrAlternateBgColor; else $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td nowrap>'.f($row,'nome').'</td>');
        ShowHTML('        <td>'.f($row,'objetivo_geral').'</td>');
        ShowHTML('        <td nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_pagina.'Inicial&R='.$w_pagina.$par.'&O=L&w_sq_modulo='.f($row,'sq_modulo').'&P1=1&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Detalhar</A> ');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    DesConectaBD();
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
  case 'INICIAL':   Help(); break;
  case 'MENU':      Menu(); break;
  default:
    Cabecalho();
    BodyOpen('onLoad=document.focus();');
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
