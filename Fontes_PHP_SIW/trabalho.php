<?
session_start();
include_once("constants.inc");
include_once("jscript.php");
include_once("funcoes.php");
include_once("classes/db/abreSessao.php");
include_once("classes/sp/db_getSiwCliModLis.php");
include_once("classes/sp/db_getPersonData.php");
include_once("classes/sp/db_getDesktop_TT.php");
include_once("classes/sp/db_getDesktop.php");
// =========================================================================
//  /Trabalho.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerencia a atualização das tabelas do sistema
// Mail     : alex@sbpi.com.br
// Criacao  : 24/03/2003 16:55
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
if ($_SESSION["LOGON"]!='Sim') { EncerraSessao(); }


// Declaração de variáveis
$dbms = abreSessao::getInstanceOf($_SESSION["DBMS"]);

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

$w_Assinatura   = strtoupper(${"w_Assinatura"});
$w_Pagina       = "Trabalho.php?par=";
$w_Disabled     = "ENABLED";

$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_ano      = RetornaAno();

if ($O=="") $O="L";

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão'; break;
  case 'A': $w_TP=$TP.' - Alteração'; break;
  case 'E': $w_TP=$TP.' - Exclusão'; break;
  case 'V': $w_TP=$TP.' - Envio'; break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  default : $w_TP=$TP." - Listagem";; 
}

Main();

FechaSessao($dbms);

// =========================================================================
// Controle da mesa de trabalho
// -------------------------------------------------------------------------
function Mesa() {
  extract($GLOBALS);
  if ($O=="L") {
     // Verifica se o cliente tem o módulo de telefonia contratado
     $RS = db_getSiwCliModLis::getInstanceOf($dbms, $w_cliente, 'TELEFONIA');
     if(!$RS->executeQuery()) { die("Cannot query"); }
     while($row = $RS->getResultArray()) {
       $w_telefonia = f($row,'nome');
       next($RS);
     }
     DesconectaBD();

     // Verifica se o usuário tem acesso ao módulo de telefonia
     //$RS = db_getPersonData::getInstanceOf($dbms, $w_cliente, $w_usuario, null, null);
     //if (f($RS,'sq_usuario_central')=='') $w_telefonia="";
  }

  Cabecalho();
  ShowHTML("<HEAD>");
  ShowHTML("<meta http-equiv=\"Refresh\" content=\"300;\">");
  ScriptOpen("JavaScript");
  ScriptClose();
  ShowHTML("</HEAD>");
  BodyOpen("onLoad=document.focus();");
  ShowHTML("<B><FONT COLOR=\"#000000\">".$w_TP."</FONT></B>");
  ShowHTML("<HR>");
  ShowHTML("<div align=center><center>");
  ShowHTML("<table border=\"0\" width=\"100%\">");
  if ($O=="L") {

    ShowHTML("<tr><td align=\"center\" colspan=3>");
    ShowHTML("    <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">");
    ShowHTML("        <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">");
    ShowHTML("          <td><font size=\"1\"><b>Módulo</font></td>");
    ShowHTML("          <td><font size=\"1\"><b>Serviço</font></td>");
    ShowHTML("          <td><font size=\"1\"><b>Em aberto</font></td>");
    ShowHTML("          <td><font size=\"1\"><b>Operações</font></td>");
    ShowHTML("        </tr>");
    if (!($w_workflow.$w_telefonia.$w_demandas.$w_agenda=="")) {
      if ($w_telefonia>"") {
        $RS = db_getDeskTop_TT::getInstanceOf($dbms, $w_usuario);
        if(!$RS->executeQuery()) { die("Cannot query"); }
        while($row = $RS->getResultArray()) {
          $w_telefonia_qtd=f($row,'existe');
          next($RS);
        }
        DesconectaBD();
        if ($w_cor==$conTrBgColor || $w_cor=="") $w_cor=$conTrAlternateBgColor; else $w_cor=$conTrBgColor;
        if ($w_telefonia_qtd>0) $w_negrito="<b>"; else $w_negrito="";

        ShowHTML("      <tr bgcolor=\"".$w_cor."\">");
        ShowHTML("        <td><font size=\"1\">".$w_telefonia."</td>");
        ShowHTML("        <td><font size=\"1\">"."Ligações</td>");
        ShowHTML("        <td align=\"right\"><font size=\"1\">".$w_negrito.$w_telefonia_qtd."&nbsp;</td>");
        ShowHTML("        <td align=\"top\" nowrap><font size=\"1\">");
        ShowHTML("          <A class=\"HL\" HREF=\"Tarifacao.php?par=Informar&R=".$w_Pagina.$par."&O=L&P1=1&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."- Ligações&SG=LIGACAO\">Exibir</A> ");
        ShowHTML("        </td>");
        ShowHTML("      </tr>");
      }
    }

    // Monta a mesa de trabalho para os outros serviços do SIW
  
    $RS = db_getDeskTop::getInstanceOf($dbms, $w_cliente, $w_usuario, $w_ano);
    if(!$RS->executeQuery()) { die("Cannot query"); }
    $w_nm_modulo='-';
    while($row = $RS->getResultArray()) {
      if ($w_cor==$conTrBgColor || $w_cor=="") {
         $w_cor=$conTrAlternateBgColor; 
      } else {
         $w_cor=$conTrBgColor;
      }
      
      if (f($row,'qtd')>0) $w_negrito="<b>"; else $w_negrito=""; 
    
      ShowHTML("    <tr bgcolor=\"".$w_cor."\">");
      // Evita que o nome do  módulo seja repetido
      if ($w_nm_modulo!=f($row,'nm_modulo')) {
        ShowHTML("      <td><font size=\"1\">".f($row,'nm_modulo')."</td>");
        $w_nm_modulo=f($row,'nm_modulo');
      } else {
        ShowHTML("      <td><font size=\"1\">&nbsp;</td>");
      }

      ShowHTML("      <td><font size=\"1\">".f($row,'nm_servico')."</td>");
      ShowHTML("      <td align=\"right\"><font size=\"1\">".$w_negrito.f($row,'qtd')."&nbsp;</td>");
      ShowHTML("      <td align=\"top\" nowrap><font size=\"1\">");
      ShowHTML("        <A CLASS=\"HL\" HREF=\"".f($row,'link')."&P1=2&P2=".f($row,'p2')."&P3=".f($row,'p3')."&P4=".f($row,'p4')."&TP=".$TP." - ".f($row,'nm_servico')."&SG=".f($row,'sg_servico')."\">Exibir</A>");
      ShowHTML("      </td>");
      ShowHTML("    </tr>");
      next($RS);
    }

    ShowHTML("      </center>");
    ShowHTML("    </table>");
    ShowHTML("  </td>");
    ShowHTML("</tr>");
    DesConectaBD();
  } else {
    ScriptOpen("JavaScript");
    ShowHTML(" alert('Opção não disponível');");
    ShowHTML(" history.back(1);");
    ScriptClose();
  }

  ShowHTML("</table>");
  ShowHTML("</center>");
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
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Estrutura_Texto_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Rodape();
  }
}
?>

