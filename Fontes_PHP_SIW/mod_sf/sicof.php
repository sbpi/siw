<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta    = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
include_once($w_dir_volta.'classes/sp/db_getSF.php');

// =========================================================================
//  sicof.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerencia o módulo de envio importação de imagens de comprovantes de pagamento
// Mail     : alex@sbpi.com.br
// Criacao  : 18/05/2002 19:12
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
//                   = V   : Envio
//                   = L   : Listagem
//                   = P   : Pesquisa
//                   = D   : Detalhes
//                   = N   : Nova solicitação de envio

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

// Carrega variáveis locais com os dados dos parâmetros recebidos

$w_dir          = 'mod_sf/';
$w_pagina       = 'sicof.php?par=';

$par        = upper($_REQUEST['par']);
$P1         = $_REQUEST['P1'];
$P2         = $_REQUEST['P2'];
$P3         = $_REQUEST['P3'];
$P4         = $_REQUEST['P4'];
$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = upper($_REQUEST['R']);
$O          = upper($_REQUEST['O']);

$p_cliente  = $_SESSION['P_CLIENTE'];
$sq_pessoa  = $_SESSION['SQ_PESSOA'];
$w_disabled = 'ENABLED';

// Configura o valor de O quando ele é nulo. Se for tela inicial de vinculação, chama filtragem
if ($O=='' && $par=='CONSULTA') {
  $O='P';
} elseif ($O=='') {
  $O='L';
} 

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão'; break;
  case 'A': $w_TP=$TP.' - Alteração'; break;
  case 'E': $w_TP=$TP.' - Exclusão'; break;
  case 'V': $w_TP=$TP.' - Envio'; break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  default: $w_TP=$TP; 
}

$w_cliente=RetornaCliente();

Main();

FechaSessao($dbms);

// =========================================================================
// Rotina de consulta ao SICOF
// -------------------------------------------------------------------------
function ConsultaDoc() {
  extract($GLOBALS);

  Cabecalho();

  $w_sq_pessoa = $_SESSION['SQ_PESSOA'];

  if ($O=='L' && $_POST['p_documento']=='') {
    $sql = new db_getSF; $RS = $sql->getInstanceOf($dbms, 'NM_PESSOA', null, $_POST['p_sq_pessoa'], $_POST['p_cpf'], $_POST['p_cnpj'], null, null, null, null, null, null, null);
    foreach ($RS as $row) { $RS = $row; break; }
  } 

  head();
  if ($O=='P') {
    ScriptOpen('JavaScript');
    Modulo();
    FormataCNPJ();
    FormataCPF();
    FormataData();
    SaltaCampo();
    CheckBranco();
    ShowHTML('function procura() {');
    ShowHTML('  if (document.Form.p_beneficiario.value.length < 3) {');
    ShowHTML('    alert(\'Informe o nome a ser procurado com, pelo menos, três letras!\');');
    ShowHTML('    document.Form.p_beneficiario.focus();');
    ShowHTML('    return false;');
    ShowHTML('  } else {');
    ShowHTML('    document.Form.O.value=\'P\';');
    ShowHTML('    document.Form.target=\'content\';');
    ShowHTML('    document.Form.submit();');
    ShowHTML('  }');
    ShowHTML('}');
    ValidateOpen('Validacao');
    if ($_SESSION['GESTOR_SISTEMA'] =="S" || $_SESSION['GESTOR_SEGURANCA']=="S" || $P1==2) {
      Validate('p_sq_pessoa', 'Beneficiário', 'SELECT', '', '1', '10', '', '1');
      Validate('p_cnpj', 'CNPJ do beneficiário', 'CNPJ', '', '18', '18', '', '1');
      Validate('p_cpf', 'CPF do beneficiário', 'CPF', '', '14', '14', '', '1');
      Validate('p_documento', 'Nº do documento', '', '', '9', '15', '1', '1');
      Validate('p_inicio', 'Data início', 'DATA', '', '10', '10', '', '0123456789/');
      Validate('p_fim', 'Data fim', 'DATA', '', '10', '10', '', '0123456789/');
      ShowHTML('  if ((theForm.p_inicio.value != \'\' && theForm.p_fim.value == \'\') || (theForm.p_inicio.value == \'\' && theForm.p_fim.value != \'\')) { ');
      ShowHTML('     alert(\'Informe o período completo ou nenhuma das datas!\');');
      ShowHTML('     theForm.p_inicio.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      CompData('p_inicio', 'Data início', '<=', 'p_fim', 'Data fim');
      Validate('p_comprovante', 'Comprovante', '', '', '1', '10', '1', '1');
      Validate('p_inicio_nf', 'Data início', 'DATA', '', '10', '10', '', '0123456789/');
      Validate('p_fim_nf', 'Data fim', 'DATA', '', '10', '10', '', '0123456789/');
      ShowHTML('  if ((theForm.p_inicio_nf.value != \'\' && theForm.p_fim_nf.value == \'\') || (theForm.p_inicio_nf.value == \'\' && theForm.p_fim_nf.value != \'\')) { ');
      ShowHTML('     alert(\'Informe o período completo ou nenhuma das datas!\');');
      ShowHTML('     theForm.p_inicio_nf.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      ShowHTML('  if (theForm.p_inicio_nf.value != \'\' && theForm.p_comprovante.value == \'\') { ');
      ShowHTML('     alert(\'Informe o comprovante a ser pesquisado no período selecionado!\');');
      ShowHTML('     theForm.p_comprovante.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      CompData('p_inicio_nf', 'Data início', '<=', 'p_fim_nf', 'Data fim');
      ShowHTML('  var w_string = theForm.p_sq_pessoa.selectedIndex + theForm.p_cnpj.value.length + theForm.p_cpf.value.length + theForm.p_documento.value.length + theForm.p_inicio.value.length + + theForm.p_fim.value.length + + theForm.p_comprovante.value.length + + theForm.p_inicio_nf.value.length + + theForm.p_fim_nf.value.length;');
      ShowHTML('  if (w_string == 0) {');
      ShowHTML('     alert(\'Você deve informar um dos parâmetros!\');');
      ShowHTML('     eval(\'theForm.p_beneficiario.focus()\');');
      ShowHTML('     return false;');
      ShowHTML('  }');
    } else {
      Validate('p_documento', 'Nº do documento', '', '1', '9', '15', '1', '1');
    } 

    ShowHTML('  theForm.target=\'docs\';');
    ValidateClose();
    ScriptClose();
  } 


  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($O=="P") {
    BodyOpen('onLoad=\'document.Form.p_beneficiario.focus();\'');
  } else {
    BodyOpen('onLoad=\'this.focus();\'');
  } 


  if ($O=='L') {
    ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><TD ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">CONSULTA AO SICOF - UNESCO</font>');
    ShowHTML('<TR><TD ALIGN="RIGHT"><B><FONT COLOR="#000000">'.date('d/m/Y, H:i:s').'</font></B></TD></TR>');
    ShowHTML('</B></TD></TR></TABLE>');
  } else {
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  } 


  ShowHTML('<HR>');
  ShowHTML('<div align="center"><center>');
  if ($O=='L') {
    ShowHTML('<table border="0" width="100%">');

    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="99%" border="0">');
    ShowHTML('      <tr><td align="left" colspan="2">Critério(s) de busca:<ul>');
    if ($_POST['p_sq_pessoa']>"" || $_POST['p_cpf']>"" || $_POST['p_cnpj']>"") {
      ShowHTML('<li>Beneficiário: <b>'.f($RS,'cgccpf').' - '.f($RS,'nome').'</b>');
    }
    if ($_POST['p_ctcc']>"") {
      $sql = new db_getSF; $RS = $sql->getInstanceOf($dbms, 'NM_PROJETO', $_POST['p_ctcc'], null, null, null, null, null, null, null, null, null, null);
      foreach ($RS as $row) { $RS = $row; break; }
      ShowHTML('<li>Projeto: <b>'.f($RS,'nome').'</b>');
      DesconectaBD();
    } 

    if ($_POST['p_documento']>"") {
      ShowHTML('<li>Documento: <b>'.$_POST['p_documento'].'</b>');
    }

    if ($_POST['p_inicio']>"") {
      ShowHTML('<li>Documentos com vigência (SA), vencimento (SP) ou missão (SPD) entre <b>'.$_POST["p_inicio"].'</b> e <b>'.$_POST["p_fim"].'</b>');
    } 

    if ($_POST['p_comprovante']>"") {
      ShowHTML('<li>Comprovantes com contém: <b>'.$_POST["p_comprovante"].'</b>');
      if ($_POST["p_inicio_nf"]>"") {
        ShowHTML(' com data entre <b>'.$_POST["p_inicio_nf"].'</b> e <b>'.$_POST["p_fim_nf"].'</b>');
      } 
    } 

    ShowHTML('    </ul>');
    ShowHTML('    </table>');
    ShowHTML('      <tr><td align="center" colspan="2">Clique <a accesskey="F" class="SS" HREF="javascript:this.status.value;" onClick="window.close(); opener.focus();">aqui</a> para fechar esta janela.');

    //CONTRATOS
    if ($_POST["p_sq_pessoa"]>"" || $_POST["p_cpf"]>"" || $_POST["p_cnpj"] || ($_POST["p_documento"]=="" && $_POST["p_inicio"]>"") || ($_POST["p_documento"]>"" && substr(upper($_POST["p_documento"]),0,3)=='SA-')) {
      ShowHTML('      <tr><td align="center" colspan="2" height="2" bgcolor="#000000">');
      ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
      ShowHTML('      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0"><b>Contratos</td>');
      ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
      ShowHTML('      <tr><td align="center" colspan="2" height="2" bgcolor="#000000">');

      $sql = new db_getSF; $RS = $sql->getInstanceOf($dbms, 'CONTRATOS', $_POST['p_ctcc'], $_POST['p_sq_pessoa'], $_POST['p_cpf'], $_POST['p_cnpj'], null, $_POST["p_documento"], $_POST['p_inicio'], $_POST['p_fim'], null, null, null);
      if (count($RS) <= 0) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=2 align="center"><b>Nenhum registro encontrado.</b></td></tr>');
      } else {
        ShowHTML('      <tr><td align="right" colspan="2"><b>Registros: '.count($RS));
        ShowHTML('      <tr><td align="center" colspan="2">');
        ShowHTML('        <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('            <td rowspan=2><b>Documento</td>');
        ShowHTML('            <td rowspan=2><b>Beneficiário</td>');
        ShowHTML('            <td colspan=2><b>Vigência</td>');
        ShowHTML('            <td rowspan=2><b>Acordo</td>');
        ShowHTML('            <td rowspan=2><b>Valor</td>');
        ShowHTML('            <td rowspan=2><b>Modalidade</td>');
        ShowHTML('            <td rowspan=2><b>Fase atual</td>');
        ShowHTML('          </tr>');
        ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('            <td><b>Início</td>');
        ShowHTML('            <td><b>Término</td>');
        ShowHTML('          </tr>');

        $w_total=0;
        foreach ($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('        <td nowrap><a class="HL" href="https://honda.unesco.org.br/pls/seguranca/Frm_SA.Visualizar?p_usuario=167&p_Documento=111800&p_Acesso=C&p_Nro_Doc='.f($row,'documento').'&P1=0&P2=0&P3=0&TP=Consultar&p_ValidaTempo=Nao">'.f($row,'documento').'</a>');
          ShowHTML('        <td>'.f($row,'nome').'</td>');
          ShowHTML('        <td align="center">'.f($row,'inicio').'</td>');
          ShowHTML('        <td align="center">'.f($row,'fim').'</td>');
          ShowHTML('        <td align="center">'.f($row,'projeto').'</td>');
          ShowHTML('        <td align="right">'.FormatNumber(f($row,'totcontratacao'),2).'</td>');
          ShowHTML('        <td>'.f($row,'modalidade').'</td>');
          ShowHTML('        <td>'.str_replace('&quot;','"',str_replace('www1.unesco.org.br','200.130.8.24',f($row,'fase_atual'))).'</td>');
          ShowHTML('      </tr>');
          $w_total += f($row,'totcontratacao');
        } 

        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
        ShowHTML('        <td colspan=5 align="right"><b>Total</b></td>');
        ShowHTML('        <td align="right"><b>'.FormatNumber($w_total,2).'</b></td>');
        ShowHTML('        <td colspan=2>&nbsp;</td>');
        ShowHTML('      </tr>');
        ShowHTML('         </table></td></tr>');
      } 
      ShowHTML('      <tr><td align="right" colspan="2">&nbsp;');
    } 


    //PAGAMENTOS
    if (($_POST['p_comprovante']>"") || $_POST['p_sq_pessoa']>"" || $_POST['p_cpf']>"" || $_POST['p_cnpj']>"" || ($_POST['p_documento']=="" && $_POST['p_inicio']>"") || ($_POST['p_documento']>"" && substr(upper($_POST['p_documento']),0,3)=="SP-")) {
      ShowHTML('      <tr><td align="center" colspan="2" height="2" bgcolor="#000000">');
      ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
      ShowHTML('      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0"><b>Pagamentos</td>');
      ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
      ShowHTML('      <tr><td align="center" colspan="2" height="2" bgcolor="#000000">');
      $sql = new db_getSF; $RS = $sql->getInstanceOf($dbms, 'PAGAMENTOS', $_POST['p_ctcc'], $_POST['p_sq_pessoa'], $_POST['p_cpf'], $_POST['p_cnpj'], null, $_POST["p_documento"], $_POST['p_inicio'], $_POST['p_fim'], $_POST['p_comprovante'], $_POST['p_inicio_nf'], $_POST['p_fim_nf']);
      if (count($RS) <= 0) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=2 align="center"><b>Nenhum registro encontrado.</b></td></tr>');
      } else {
        ShowHTML('      <tr><td align="right" colspan="2"><b>Registros: '.count($RS));
        ShowHTML('      <tr><td align="center" colspan="2">');
        ShowHTML('        <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('            <td><b>Documento</td>');
        ShowHTML('            <td><b>Beneficiário</td>');
        ShowHTML('            <td><b>NF</td>');
        ShowHTML('            <td><b>Vencimento</td>');
        ShowHTML('            <td><b>Acordo</td>');
        ShowHTML('            <td><b>Valor</td>');
        if ($_POST['p_documento']=="") ShowHTML('            <td><b>Histórico</td>');
        ShowHTML('            <td><b>Fase atual</td>');
        ShowHTML('          </tr>');

        $w_total=0;
        foreach ($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('        <td nowrap><a class="HL" href="https://honda.unesco.org.br/pls/seguranca/Frm_SP.Visualizar?p_usuario=167&p_Documento=111800&p_Acesso=C&p_Nro_Doc='.f($row,'documento').'&P1=0&P2=0&P3=0&TP=Consultar&p_ValidaTempo=Nao">'.f($row,'documento').'</a>');
          ShowHTML('        <td>'.f($row,'nome').'</td>');
          ShowHTML('        <td nowrap>');
          $sql = new db_getSF; $RS1 = $sql->getInstanceOf($dbms, 'NR_COMPROVANTE', null, null, null, null, null, null, null, null, f($row,'handle'), null, null);
          if (count($RS1) <= 0) {
            print '---';
          } else {
            foreach ($RS1 as $row1) {
              print f($row1,'numerodoc').'&nbsp;<br>';
            } 
          } 
          ShowHTML('            </td>');
          ShowHTML('        <td align="center" nowrap>'.f($row,'inicio').'</td>');
          ShowHTML('        <td nowrap>'.f($row,'projeto').'</td>');
          ShowHTML('        <td align="right" nowrap>'.FormatNumber(f($row,'valor'),2).'</td>');
          if ($_POST['p_documento']=='') ShowHTML('        <td>'.substr(f($row,'historico'),0,69).'</td>');
          ShowHTML('        <td>'.str_replace('&quot;','"',str_replace('www1.unesco.org.br','200.130.8.24',f($row,'fase_atual'))).'</td>');
          ShowHTML('      </tr>');
          $w_total += f($row,'valor');
        } 
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
        ShowHTML('        <td colspan=5 align="right"><b>Total</b></td>');
        ShowHTML('        <td align="right"><b>'.FormatNumber($w_total,2).'</b></td>');
        ShowHTML('        <td colspan=2>&nbsp;</td>');
        ShowHTML('      </tr>');
        ShowHTML('         </table></td></tr>');
      } 
      ShowHTML('      <tr><td align="right" colspan="2">&nbsp;');
    } 

    //VIAGENS A SERVIÇO
    if ($_POST['p_sq_pessoa']>"" || $_POST['p_cpf']>"" || $_POST['p_cnpj']>"" || ($_POST['p_documento']=="" && $_POST['p_inicio']>"") || ($_POST['p_documento']>"" && substr(upper($_POST['p_documento']),0,3)=="SPD")) {
      ShowHTML('      <tr><td align="center" colspan="2" height="2" bgcolor="#000000">');
      ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
      ShowHTML('      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0"><b>Passagens e Diárias</td>');
      ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
      ShowHTML('      <tr><td align="center" colspan="2" height="2" bgcolor="#000000">');
      $sql = new db_getSF; $RS = $sql->getInstanceOf($dbms, 'VIAGENS', $_POST['p_ctcc'], $_POST['p_sq_pessoa'], $_POST['p_cpf'], $_POST['p_cnpj'], null, $_POST["p_documento"], $_POST['p_inicio'], $_POST['p_fim'], null, null, null);
      if (count($RS) <= 0) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=2 align="center"><b>Nenhum registro encontrado.</b></td></tr>');
      } else {
        ShowHTML('      <tr><td align="right" colspan="2"><b>Registros: '.count($RS));
        ShowHTML('      <tr><td align="center" colspan="2">');
        ShowHTML('        <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('            <td rowspan=2><b>Documento</td>');
        ShowHTML('            <td rowspan=2><b>Beneficiário</td>');
        ShowHTML('            <td colspan=2><b>Missão</td>');
        ShowHTML('            <td rowspan=2><b>Acordo</td>');
        if ($_POST['p_documento']=="") ShowHTML('            <td rowspan=2><b>Histórico</td>');
        ShowHTML('            <td rowspan=2><b>Fase atual</td>');
        ShowHTML('          </tr>');
        ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('            <td><b>Início</td>');
        ShowHTML('            <td><b>Término</td>');
        ShowHTML('          </tr>');

        foreach ($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('        <td nowrap><a class="HL" href="https://honda.unesco.org.br/pls/seguranca/Frm_SPD.Visualizar?p_usuario=167&p_Documento=111800&p_Acesso=C&p_Nro_Doc='.f($row,'documento').'&P1=0&P2=0&P3=0&TP=Consultar&p_ValidaTempo=Nao">'.f($row,'documento').'</a>');
          ShowHTML('        <td>'.f($row,'nome').'</td>');
          ShowHTML('        <td align="center" nowrap>'.f($row,'inicio').'</td>');
          ShowHTML('        <td align="center" nowrap>'.f($row,'fim').'</td>');
          ShowHTML('        <td>'.f($row,'projeto').'</td>');
          if ($_POST['p_documento']=="") ShowHTML('        <td>'.f($row,'historico').'</td>');
          ShowHTML('        <td>'.str_replace('&quot;','"',str_replace('www1.unesco.org.br','200.130.8.24',f($row,'fase_atual'))).'</td>');
          ShowHTML('      </tr>');
        } 
        ShowHTML('         </table></td></tr>');
      } 
    } 

    ShowHTML('      <tr><td align="center" colspan="2"><br>Clique <a accesskey="F" class="SS" HREF="javascript:this.status.value;" onClick="window.close(); opener.focus();">aqui</a> para fechar esta janela.');
    ShowHTML('     </tr></tr></td></table>');

    ShowHTML('</table>');
    ShowHTML('</center>');
  } elseif ($O=='P') {

    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="left">Instruções:<ul>');
    ShowHTML('  <li>Informe um dos critérios apresentados abaixo e clique sobre o botão <i>Aplicar filtro</i>.');
    ShowHTML('  <li>A procura pelo nome do beneficiário é feita em duas partes. Primeiro, informe parte dele em <i>Procurar nome</i> e clique sobre o botão <i>Procura</i>. Em seguida, selecione o nome desejado na lista disponível em <i>Beneficiário</i> e clique no botão <i>Aplicar Filtro</i>;');
    ShowHTML('  <li>Você pode informar quantos critérios desejar.');
    ShowHTML('  <li>O resultado será apresentado em outra janela.');
    ShowHTML('  </ul></div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="90%" border="0">');
    ShowHTML('      <tr><td>');
    ShowHTML('            <b>Pr<U>o</U>curar nome:<br> <INPUT TYPE="TEXT" ACCESSKEY="O" class="STI" name="p_beneficiario" size=40 maxlength=40>');
    ShowHTML('            <input class="STB" type="button" name="Procura" value="Procura" onClick="procura()">');

    $sql = new db_getSF; $RS = $sql->getInstanceOf($dbms, 'DOLAR', null, null, null, null, null, null, null, null, null, null, null);
    ShowHTML('          <td rowspan=6><center><b>Dólar ONU</b>');
    ShowHTML('              <table border=0>');
    ShowHTML('              <tr valign="top" align="center">');
    ShowHTML('                <td><b>Mês');;
    ShowHTML('                <td><b>R$');;
    ShowHTML('              </tr>');
    $i = 0;
    foreach($RS as $row) {
      ShowHTML('              <tr valign="top">');
      if ($i==0) {
        ShowHTML('                <td align="center"><b>'.f($row,mes));;
        ShowHTML('                <td align="rignt"><b>'.formatNumber(f($row,valor),2));;
        $i = 1;
      } else {
        ShowHTML('                <td align="center">'.f($row,mes));;
        ShowHTML('                <td align="rignt">'.formatNumber(f($row,valor),2));;
      }
      ShowHTML('              </tr>');
    }
    ShowHTML('              </table>');
    ShowHTML('      <tr><td><b><U>B</U>eneficiário:<br> <SELECT ACCESSKEY="B" class="STS" name="p_sq_pessoa" size="1">');
    ShowHTML('          <OPTION VALUE="">---');

    if (nvl($_POST['p_beneficiario'],'')!='') {
      $sql = new db_getSF; $RS = $sql->getInstanceOf($dbms, 'NOME', null, null, null, null, $_POST["p_beneficiario"], null, null, null, null, null, null);
      foreach ($RS as $row) {
        if (f($row,'handle')==$_POST['p_sq_pessoa']) {
          ShowHTML('          <OPTION VALUE='.f($row,'handle').' selected>'.f($row,'nome').' ('.f($row,'cgccpf').')');
        } else {
          ShowHTML('          <OPTION VALUE='.f($row,'handle').'>'.f($row,'nome').' ('.f($row,'cgccpf').')');
        } 
      } 
    }
    ShowHTML('          </SELECT></td>');
    ShowHTML('      </tr>');

    ShowHTML('      <tr><td><b>Pro<U>j</U>eto:<br> <SELECT ACCESSKEY="J" class="STS" name="p_ctcc" size="1">');
    ShowHTML('          <OPTION VALUE="">---');

    $sql = new db_getSF; $RS = $sql->getInstanceOf($dbms, 'PROJETOS', null, null, null, null, null, null, null, null, null, null, null);
    foreach ($RS as $row) {
      if (f($row,'handle')==$_POST['p_ctcc']) {
        ShowHTML('          <OPTION VALUE='.f($row,'handle').' selected>'.f($row,'nome'));
      } else {
        ShowHTML('          <OPTION VALUE='.f($row,'handle').'>'.f($row,'nome'));
      } 
    } 
    ShowHTML('          </SELECT></td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('          <td><b><U>C</U>NPJ:<br> <INPUT TYPE="TEXT" ACCESSKEY="C" class="STI" name="p_cnpj" size=18 maxlength=18 onKeyDown="FormataCNPJ(this,event);"  value="'.$_POST['p_cnpj'].'"></td>');
    ShowHTML('          <td><b>C<U>P</U>F:<br> <INPUT TYPE="TEXT" ACCESSKEY="C" class="STI" name="p_cpf" size=14 maxlength=14 onKeyDown="FormataCPF(this,event);" value="'.$_POST['p_cpf'].'"></td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('          <td><b>SA/SP/SP<U>D</U>:</b> (identificação completa)<br> <INPUT TYPE="TEXT" ACCESSKEY="D" class="STI" name="p_documento" size=15 maxlength=15 value="'.$_POST['p_documento'].'"></td>');
    ShowHTML('          <td>Período: <b>D<U>e</U>: <INPUT TYPE="TEXT" ACCESSKEY="E" class="STI" name="p_inicio" size=10 maxlength=10 onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" value="'.nvl($_POST['p_inicio'],str_replace('/'.date(Y,time()),'/'.(date(Y,time())-1),formataDataEdicao(time()))).'">');
    ShowHTML('                                <U>a</U>té: <INPUT TYPE="TEXT" ACCESSKEY="A" class="STI" name="p_fim" size=10 maxlength=10 onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" value="'.nvl($_POST['p_fim'],formataDataEdicao(time())).'"></td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('          <td><b>Co<U>m</U>provante (NF/Fatura/Recibo):<br><INPUT TYPE="TEXT" ACCESSKEY="M" class="STI" name="p_comprovante" size=10 maxlength=10 value="'.$_POST['p_comprovante'].'">');
    ShowHTML('          <td>Período: <b>D<U>e</U>: <INPUT TYPE="TEXT" ACCESSKEY="E" class="STI" name="p_inicio_nf" size=10 maxlength=10 onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" value="'.$_POST['p_inicio_nf'].'">');
    ShowHTML('                                <b><U>a</U>té: <INPUT TYPE="TEXT" ACCESSKEY="A" class="STI" name="p_fim_nf" size=10 maxlength=10 onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" value="'.$_POST['p_fim_nf'].'"></td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
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

  Rodape();
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);

  switch ($par) {
    case "CONSULTA": ConsultaDoc(); break;
    default:
      Cabecalho();
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');
      BodyOpen('onLoad=this.focus();');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
      ShowHTML('<HR>');
      ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
      Rodape();
      break;
  } 
} 
?>


