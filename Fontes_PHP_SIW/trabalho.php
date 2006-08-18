<?
header('Expires: '.-1500);
session_start();
include_once('constants.inc');
include_once('jscript.php');
include_once('funcoes.php');
include_once('classes/db/abreSessao.php');
include_once('classes/sp/db_getSiwCliModLis.php');
include_once('classes/sp/db_getPersonData.php');
include_once('classes/sp/db_getDeskTop_TT.php');
include_once('classes/sp/db_getDeskTop.php');
// =========================================================================
//  trabalho.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papad�polis
// Descricao: Gerencia a atualiza��o das tabelas do sistema
// Mail     : alex@sbpi.com.br
// Criacao  : 24/03/2003 16:55
// Versao   : 1.0.0.0
// Local    : Bras�lia - DF
// -------------------------------------------------------------------------
//
// Par�metros recebidos:
//    R (refer�ncia) = usado na rotina de grava��o, com conte�do igual ao par�metro T
//    O (opera��o)   = I   : Inclus�o
//                   = A   : Altera��o
//                   = C   : Cancelamento
//                   = E   : Exclus�o
//                   = L   : Listagem
//                   = P   : Pesquisa
//                   = D   : Detalhes
//                   = N   : Nova solicita��o de envio
// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }


// Declara��o de vari�veis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega vari�veis locais com os dados dos par�metros recebidos
$par        = strtoupper($_REQUEST["par"]);
$P1         = $_REQUEST["P1"];
$P2         = $_REQUEST["P2"];
$P3         = $_REQUEST["P3"];
$P4         = $_REQUEST["P4"];
$TP         = $_REQUEST["TP"];
$SG         = strtoupper($_REQUEST["SG"]);
$R          = $_REQUEST["R"];
$O          = strtoupper($_REQUEST["O"]);

$w_Assinatura   = strtoupper(${"w_Assinatura"});
$w_pagina       = "trabalho.php?par=";
$w_Disabled     = "ENABLED";

$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_ano      = RetornaAno();

if ($O=='') $O='L';

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclus�o'; break;
  case 'A': $w_TP=$TP.' - Altera��o'; break;
  case 'E': $w_TP=$TP.' - Exclus�o'; break;
  case 'V': $w_TP=$TP.' - Envio'; break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  default : $w_TP=$TP.' - Listagem'; 
}

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Controle da mesa de trabalho
// -------------------------------------------------------------------------
function Mesa() {
  extract($GLOBALS);
  
  if ($O=="L") {
     // Verifica se o cliente tem o m�dulo de telefonia contratado
     $RS = db_getSiwCliModLis::getInstanceOf($dbms, $w_cliente, null, 'TT');
     foreach ($RS as $row) {
       $w_telefonia = f($row,'nome');
     }
     DesconectaBD();

     // Verifica se o usu�rio tem acesso ao m�dulo de telefonia
     //$RS = db_getPersonData::getInstanceOf($dbms, $w_cliente, $w_usuario, null, null);
     //if (f($RS,'sq_usuario_central')=='') $w_telefonia='';
  }

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="300;">');
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=document.focus();');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" width="100%">');
  if ($O=="L") {

    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor='.$conTableBgColor.' BORDER='.$conTableBorder.' CELLSPACING='.$conTableCellSpacing.' CELLPADDING='.$conTableCellPadding.' BorderColorDark='.$conTableBorderColorDark.' BorderColorLight='.$conTableBorderColorLight.'>');
    ShowHTML('        <tr bgcolor='.$conTrBgColor.' align="center">');
    ShowHTML('          <td><b>M�dulo</td>');
    ShowHTML('          <td><b>Servi�o</td>');
    ShowHTML('          <td><b>Em aberto</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (!($w_workflow.$w_telefonia.$w_demandas.$w_agenda=='')) {
      if ($w_telefonia>'') {
        $RS = db_getDeskTop_TT::getInstanceOf($dbms, $w_usuario);
        foreach ($RS as $row) {
          $w_telefonia_qtd=f($row,'existe');
        }
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        if ($w_telefonia_qtd>0) $w_negrito='<b>'; else $w_negrito='';
        ShowHTML('      <tr bgcolor="'.$w_cor.'">');
        ShowHTML('        <td>'.$w_telefonia.'</td>');
        ShowHTML('        <td>Liga��es</td>');
        ShowHTML('        <td align="right">'.$w_negrito.$w_telefonia_qtd.'&nbsp;</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="tarifacao.php?par=Informar&R='.$w_pagina.$par.'&O=L&P1=1&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'- Liga��es&SG=LIGACAO">Exibir</A> ');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    }

    // Monta a mesa de trabalho para os outros servi�os do SIW
    $RS = db_getDeskTop::getInstanceOf($dbms, $w_cliente, $w_usuario, $w_ano);
    $w_nm_modulo='-';
    foreach ($RS as $row) {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      
      if (f($row,'qtd')>0) $w_negrito="<b>"; else $w_negrito=''; 
    
      ShowHTML('    <tr bgcolor='.$w_cor.'>');
      // Evita que o nome do  m�dulo seja repetido
      if ($w_nm_modulo!=f($row,'nm_modulo')) {
        ShowHTML('      <td>'.f($row,'nm_modulo').'</td>');
        $w_nm_modulo=f($row,'nm_modulo');
      } else {
        ShowHTML('      <td>&nbsp;</td>');
      }

      ShowHTML('      <td>'.f($row,'nm_servico').'</td>');
      ShowHTML('      <td align="right">'.$w_negrito.f($row,'qtd').'&nbsp;</td>');
      ShowHTML('      <td align="top" nowrap>');
      ShowHTML('        <A CLASS="HL" HREF="'.strtolower(f($row,'link')).'&P1=2&P2='.f($row,'p2').'&P3='.f($row,'p3').'&P4='.f($row,'p4').'&TP='.$TP.' - '.f($row,'nm_servico').'&SG='.f($row,'sg_servico').'">Exibir</A>');
      ShowHTML('      </td>');
      ShowHTML('    </tr>');
    }

    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    DesConectaBD();
  } else {
    ScriptOpen("JavaScript");
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
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
  case 'MESA': Mesa(); break;
  default:
    Cabecalho();
    BodyOpen('onLoad=document.focus();');
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    Estrutura_Texto_Abre();
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Estrutura_Texto_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Rodape();
  }
}
?>

