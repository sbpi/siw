<?
header('Expires: '.-1500);
session_start();
include_once('../constants.inc');
include_once('../jscript.php');
include_once('../funcoes.php');
include_once('../classes/db/abreSessao.php');
include_once('../classes/db/DatabaseQueriesFactory.php');
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

if ($_SESSION["LOGON"] !="Sim") EncerraSessao();

// Declaração de variáveis
$dbms = abreSessao::getInstanceOf($_SESSION["DBMS"]);

// Carrega variáveis locais com os dados dos parâmetros recebidos

$w_dir          = "mod_sf/";
$w_dir_volta    = "../";
$w_pagina       = "sicof.php?par=";

$par        = strtoupper($_REQUEST['par']);
$P1         = $_REQUEST["P1"];
$P2         = $_REQUEST["P2"];
$P3         = $_REQUEST["P3"];
$P4         = $_REQUEST["P4"];
$TP         = $_REQUEST["TP"];
$SG         = strtoupper($_REQUEST["SG"]);
$R          = strtoupper($_REQUEST["R"]);
$O          = strtoupper($_REQUEST["O"]);

$p_cliente  = $_SESSION['P_CLIENTE'];
$sq_pessoa  = $_SESSION['SQ_PESSOA'];
$w_disabled = "ENABLED";

// Configura o valor de O quando ele é nulo. Se for tela inicial de vinculação, chama filtragem
if ($O=="" && $par=="INICIAL") {
  $O="P";
} elseif ($O=="") {
  $O="L";
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
function Consulta() {
  extract($GLOBALS);

  Cabecalho();

  $w_sq_pessoa = $_SESSION['SQ_PESSOA'];

  if ($O=='L' && $_POST['p_documento']=='') {
    if ($_POST['p_sq_pessoa']>'')$SQL='select cgccpf, nome from corporativo.gn_pessoas@sicof where handle = '.$_POST['p_sq_pessoa'];
    if ($_POST['p_cnpj']>'')     $SQL='select cgccpf, nome from corporativo.gn_pessoas@sicof where cgccpf = \''.$_POST['p_cnpj'].'\'';
    if ($_POST['p_cpf']>'')      $SQL='select cgccpf, nome from corporativo.gn_pessoas@sicof where cgccpf = \''.$_POST['p_cpf'].'\'';
    $RS = DatabaseQueriesFactory::getInstanceOf($SQL, $dbms, null, DB_TYPE);
    if(!$RS->executeQuery()) { die("Cannot query"); }
    else $row = $RS->getResultArray();
  } 

  ShowHTML('<HEAD>');
  if ($O=='P') {
    ScriptOpen('JavaScript');
    Modulo();
    FormataCNPJ();
    FormataCPF();
    FormataData();
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
    BodyOpen('onLoad=\'document.focus();\'');
  } 


  if ($O=='L') {
    ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><TD ALIGN="RIGHT"><B><FONT SIZE=5 COLOR="#000000">');
    ShowHTML('CONSULTA AO SICOF - UNESCO');
    ShowHTML('</FONT><TR><TD ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">'.date('d/m/Y, H:i:s').'</B></TD></TR>');
    ShowHTML('</FONT></B></TD></TR></TABLE>');
  } else {
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  } 


  ShowHTML('<HR>');
  ShowHTML('<div align="center"><center>');
  if ($O=='L') {
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');

    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="99%" border="0">');
    ShowHTML('      <tr><td align="left" colspan="2"><font size="2">Critério(s) de busca:<ul>');
    if ($_POST['p_sq_pessoa']>"" || $_POST['p_cpf']>"" || $_POST['p_cnpj']>"") {
      ShowHTML('<li>Beneficiário: <b>'.f($row,'cgccpf').' - '.f($row,'nome').'</b>');
    }
    if ($_POST['p_ctcc']>"") {
      $SQL="select nome from corporativo.ct_cc@sicof where handle = ".$_POST['p_ctcc'];
      $RS = DatabaseQueriesFactory::getInstanceOf($SQL, $dbms, null, DB_TYPE);
      $RS->getResultArray();
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

    ShowHTML('</ul>');
    ShowHTML('      <tr><td align="center" colspan="2"><font size="2">Clique <a accesskey="F" class="SS" href="#" onClick="window.close(); opener.focus();">aqui</a> para fechar esta janela.</font>');

    //CONTRATOS
    if ($_POST["p_sq_pessoa"]>"" || $_POST["p_cpf"]>"" || $_POST["p_cnpj"] || ($_POST["p_documento"]=="" && $_POST["p_inicio"]>"") || ($_POST["p_documento"]>"" && substr(strtoupper($_POST["p_documento"]),0,3)=='SA-')) {
      $SQL="select a.automatico_sa Documento, to_char(c.duracaoinicio,'dd/mm/yyyy') inicio, c.duracaoinicio, "."\r\n".
        "       to_char(c.duracaofim,'dd/mm/yyyy') fim, "."\r\n".
        "       d.codigounesco projeto,  "."\r\n".
        "       decode(c.tipodepagamento,1,'Permanente',2,'Consultor',3,'Produto',4,'Financiamento de atividades')||' ('|| "."\r\n".
        "       decode(a.alteracao,1,'Contrato',2,'Emenda')||')' Modalidade, "."\r\n".
        "       seguranca.fcfaseatual@sicof(a.automatico_sa) fase_atual, e.nome, c.totcontratacao "."\r\n".
        "  from corporativo.un_solicitacaoadministrativa@sicof a, "."\r\n".
        "       corporativo.un_sol_adm_certifica@sicof         b, "."\r\n".
        "       corporativo.ct_cc@sicof                        d, "."\r\n".
        "       corporativo.un_termoreferenciapf@sicof         c, "."\r\n".
        "       corporativo.gn_pessoas@sicof                   e "."\r\n".
        " where a.handle     = b.numsolicitacao "."\r\n".
        "   and b.acordo     = d.handle "."\r\n".
        "   and a.handle     = c.numerosolicitacao "."\r\n".
        "   and a.contratado = e.handle "."\r\n";
      if ($_POST['p_sq_pessoa']>"") {
        $SQL=$SQL."  and e.handle = ".$_POST['p_sq_pessoa']."\r\n";
      }

      if ($_POST['p_ctcc']>"")       $SQL=$SQL."  and b.acordo = ".$_POST['p_ctcc']."\r\n";
      if ($_POST['p_cnpj']>"")       $SQL=$SQL."  and e.cgccpf = '".$_POST['p_cnpj']."'"."\r\n";
      if ($_POST['p_cpf']>"")        $SQL=$SQL."  and e.cgccpf = '".$_POST['p_cpf']."'"."\r\n";
      if ($_POST['p_documento']>"")  $SQL=$SQL."  and a.automatico_sa = '".strtoupper($_POST['p_documento'])."'"."\r\n";
      if ($_POST['p_inicio']>"") {
        $SQL=$SQL.
          "  and (c.duracaoinicio between to_date('".$_POST['p_inicio']."', 'dd/mm/yyyy') and to_date('".$_POST['p_fim']."', 'dd/mm/yyyy') or "."\r\n".
          "       c.duracaofim    between to_date('".$_POST['p_inicio']."', 'dd/mm/yyyy') and to_date('".$_POST['p_fim']."', 'dd/mm/yyyy') or "."\r\n".
          "       to_date('".$_POST['p_inicio']."', 'dd/mm/yyyy') between c.duracaoinicio and c.duracaofim or "."\r\n".
          "       to_date('".$_POST['p_fim']."', 'dd/mm/yyyy')    between c.duracaoinicio and c.duracaofim "."\r\n".
          "      ) "."\r\n";
      } 

      $SQL=$SQL.
        "UNION "."\r\n".
        "select a.automatico_sa Documento, to_char(c.duracaoinicio,'dd/mm/yyyy') inicio, c.duracaoinicio, "."\r\n".
        "       to_char(c.duracaofim,'dd/mm/yyyy') fim, "."\r\n".
        "       d.codigounesco projeto,  "."\r\n".
        "       decode(c.tipodepagamento,1,'Serviços',2,'Aquis.Mat/Bens',3,'Pub/Serv.Gráf.',4,'Promoção Eventos','Financiamento de atividades')||' ('|| "."\r\n".
        "       decode(a.alteracao,1,'Contrato',2,'Emenda')||')' Modalidade, "."\r\n".
        "       seguranca.fcfaseatual@sicof(a.automatico_sa) fase_atual, e.nome, c.totcontratacao "."\r\n".
        "  from corporativo.un_solicitacaoadministrativa@sicof a, "."\r\n".
        "       corporativo.un_sol_adm_certifica@sicof         b, "."\r\n".
        "       corporativo.ct_cc@sicof                        d, "."\r\n".
        "       corporativo.un_termoreferenciapj@sicof         c, "."\r\n".
        "       corporativo.gn_pessoas@sicof                   e "."\r\n".
        " where a.handle     = b.numsolicitacao "."\r\n".
        "   and b.acordo     = d.handle "."\r\n".
        "   and a.handle     = c.solicitacao "."\r\n".
        "   and a.contratado = e.handle "."\r\n";
      if ($_POST['p_sq_pessoa']>"")  $SQL=$SQL."  and e.handle = ".$_POST['p_sq_pessoa']."\r\n";
      if ($_POST['p_ctcc']>"")       $SQL=$SQL."  and b.acordo = ".$_POST['p_ctcc']."\r\n";
      if ($_POST['p_cnpj']>"")       $SQL=$SQL."  and e.cgccpf = '".$_POST['p_cnpj']."'"."\r\n";
      if ($_POST['p_cpf']>"")        $SQL=$SQL."  and e.cgccpf = '".$_POST['p_cpf']."'"."\r\n";
      if ($_POST['p_documento']>"")  $SQL=$SQL."  and a.automatico_sa = '".strtoupper($_POST['p_documento'])."'"."\r\n";
      if ($_POST['p_inicio']>"") {
        $SQL=$SQL.
          "  and (c.duracaoinicio between to_date('".$_POST['p_inicio']."', 'dd/mm/yyyy') and to_date('".$_POST['p_fim']."', 'dd/mm/yyyy') or "."\r\n".
          "       c.duracaofim    between to_date('".$_POST['p_inicio']."', 'dd/mm/yyyy') and to_date('".$_POST['p_fim']."', 'dd/mm/yyyy') or "."\r\n".
          "       to_date('".$_POST['p_inicio']."', 'dd/mm/yyyy') between c.duracaoinicio and c.duracaofim or "."\r\n".
          "       to_date('".$_POST['p_fim']."', 'dd/mm/yyyy')    between c.duracaoinicio and c.duracaofim "."\r\n".
          "      ) "."\r\n";
      } 
      $SQL=$SQL." order by duracaoinicio desc"."\r\n";

      ShowHTML('      <tr><td align="center" colspan="2" height="2" bgcolor="#000000">');
      ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
      ShowHTML('      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0"><font  size="2"><b>Contratos</td>');
      ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
      ShowHTML('      <tr><td align="center" colspan="2" height="2" bgcolor="#000000">');
      $RS = DatabaseQueriesFactory::getInstanceOf($SQL, $dbms, null, DB_TYPE);
      if(!$RS->executeQuery()) die("Cannot query"); else $RS = $RS->getResultData();
      if (count($RS) <= 0) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font  size="2"><b>Nenhum registro encontrado.</b></td></tr>');
      } else {
        ShowHTML('      <tr><td align="right" colspan="2"><font size="1"><b>Registros: '.count($RS));
        ShowHTML('      <tr><td align="center" colspan="2">');
        ShowHTML('        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('            <td rowspan=2><font size="1"><b>Documento</font></td>');
        ShowHTML('            <td rowspan=2><font size="1"><b>Beneficiário</font></td>');
        ShowHTML('            <td colspan=2><font size="1"><b>Vigência</font></td>');
        ShowHTML('            <td rowspan=2><font size="1"><b>Acordo</font></td>');
        ShowHTML('            <td rowspan=2><font size="1"><b>Valor</font></td>');
        ShowHTML('            <td rowspan=2><font size="1"><b>Modalidade</font></td>');
        ShowHTML('            <td rowspan=2><font size="1"><b>Fase atual</font></td>');
        ShowHTML('          </tr>');
        ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('            <td><font size="2"><b>Início</font></td>');
        ShowHTML('            <td><font size="2"><b>Término</font></td>');
        ShowHTML('          </tr>');

        $w_total=0;
        foreach ($RS as $row) {
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          ShowHTML('        <td nowrap><font size="1"><a class="HL" href="https://honda.unesco.org.br/pls/seguranca/Frm_SA.Visualizar?p_usuario=167&p_Documento=111800&p_Acesso=C&p_Nro_Doc='.f($row,'documento').'&P1=0&P2=0&P3=0&TP=Consultar&p_ValidaTempo=Nao">'.f($row,'documento').'</a>');
          ShowHTML('        <td><font size="1">'.f($row,'nome').'</td>');
          ShowHTML('        <td align="center"><font size="1">'.f($row,'inicio').'</td>');
          ShowHTML('        <td align="center"><font size="1">'.f($row,'fim').'</td>');
          ShowHTML('        <td align="center"><font size="1">'.f($row,'projeto').'</td>');
          ShowHTML('        <td align="right"><font size="1">'.FormatNumber(f($row,'totcontratacao'),2).'</td>');
          ShowHTML('        <td><font size="1">'.f($row,'modalidade').'</td>');
          ShowHTML('        <td><font size="1">'.f($row,'fase_atual').'</td>');
          ShowHTML('      </tr>');
          $w_total = $w_total + f($row,'totcontratacao');
        } 
      } 

      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
      ShowHTML('        <td colspan=5 align="right"><font size="1"><b>Total</b></font></td>');
      ShowHTML('        <td align="right"><font size="1"><b>'.FormatNumber($w_total,2).'</b></font></td>');
      ShowHTML('        <td colspan=2><font size="1">&nbsp;</td>');
      ShowHTML('      </tr>');
      ShowHTML('         </table></td></tr>');
      ShowHTML('      <tr><td align="right" colspan="2"><font size="1">&nbsp;');
    } 


    //PAGAMENTOS
    if (($_POST['p_comprovante']>"") || $_POST['p_sq_pessoa']>"" || $_POST['p_cpf']>"" || $_POST['p_cnpj']>"" || ($_POST['p_documento']=="" && $_POST['p_inicio']>"") || ($_POST['p_documento']>"" && substr(strtoupper($_POST['p_documento']),0,3)=="SP-")) {

      $SQL="select a.handle, a.automatico_sp documento, Decode(c.handle,null,a.proposito_pgto,c.ds_portugues) historico, "."\r\n".
        "       Nvl(to_char(a.dt_vcto,'dd/mm/yyyy'),'-') inicio,  "."\r\n".
        "       d.codigounesco projeto, "."\r\n".
        "       (Nvl(a.valornominal,0) - Nvl(a.abatimento,0)) Valor, "."\r\n".
        "       seguranca.fcfaseatual@sicof(a.automatico_sp) fase_atual, b.nome "."\r\n".
        "from corporativo.Un_Sol_Pgto@sicof a, "."\r\n".
        "    corporativo.Gn_Pessoas@sicof b, "."\r\n".
        "    corporativo.Un_HistoricoPadrao@sicof c, "."\r\n".
        "    corporativo.ct_cc@sicof d "."\r\n".
        "where a.Favorecido     = b.Handle "."\r\n".
        "  and a.historicopadrao= c.handle (+) "."\r\n".
        "  and a.acordo         = d.handle "."\r\n";
      if ($_POST['p_sq_pessoa']>"")  $SQL=$SQL."  and b.handle = ".$_POST['p_sq_pessoa']."\r\n";
      if ($_POST['p_ctcc']>"")       $SQL=$SQL."  and a.acordo = ".$_POST['p_ctcc']."\r\n";
      if ($_POST['p_cnpj']>"")       $SQL=$SQL."  and b.cgccpf = '".$_POST['p_cnpj']."'"."\r\n";
      if ($_POST['p_cpf']>"")        $SQL=$SQL."  and b.cgccpf = '".$_POST['p_cpf']."'"."\r\n";
      if ($_POST['p_documento']>"")  $SQL=$SQL."  and a.automatico_sp = '".strtoupper($_POST['p_documento']."'")."\r\n";
      if ($_POST['p_inicio']>"")     $SQL=$SQL."  and a.dt_vcto between to_date('".$_POST['p_inicio']."', 'dd/mm/yyyy') and to_date('".$_POST['p_fim']."', 'dd/mm/yyyy') "."\r\n";
      if ($_POST['p_comprovante']>"") {
        $SQL=$SQL.
          "  and a.handle in "."\r\n".
          "      (select a.automatico_sp "."\r\n".
          "         from corporativo.un_sol_pgto_doc_anexos@sicof a "."\r\n".
          "        where a.numerodoc like '%".$_POST['p_comprovante']."%' "."\r\n";
        if ($_POST['p_inicio_nf']>"") $SQL=$SQL."          and a.data between to_date('".$_POST['p_inicio_nf']."', 'dd/mm/yyyy') and to_date('".$_POST['p_fim_nf']."', 'dd/mm/yyyy') "."\r\n";
        $SQL=$SQL."      ) "."\r\n";
      } 
      $SQL=$SQL." order by a.dt_vcto desc "."\r\n";

      ShowHTML('      <tr><td align="center" colspan="2" height="2" bgcolor="#000000">');
      ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
      ShowHTML('      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0"><font  size="2"><b>Pagamentos</td>');
      ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
      ShowHTML('      <tr><td align="center" colspan="2" height="2" bgcolor="#000000">');
      $RS = DatabaseQueriesFactory::getInstanceOf($SQL, $dbms, null, DB_TYPE);
      if(!$RS->executeQuery()) die("Cannot query"); else $RS = $RS->getResultData();
      if (count($RS) <= 0) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font  size="2"><b>Nenhum registro encontrado.</b></td></tr>');
      } else {
        ShowHTML('      <tr><td align="right" colspan="2"><font size="1"><b>Registros: '.count($RS));
        ShowHTML('      <tr><td align="center" colspan="2">');
        ShowHTML('        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('            <td><font size="1"><b>Documento</font></td>');
        ShowHTML('            <td><font size="1"><b>Comprovante</font></td>');
        ShowHTML('            <td><font size="1"><b>Beneficiário</font></td>');
        ShowHTML('            <td><font size="1"><b>Vencimento</font></td>');
        ShowHTML('            <td><font size="1"><b>Acordo</font></td>');
        ShowHTML('            <td><font size="1"><b>Valor</font></td>');
        if ($_POST['p_documento']=="") ShowHTML('            <td><font size="1"><b>Histórico</font></td>');
        ShowHTML('            <td><font size="1"><b>Fase atual</font></td>');
        ShowHTML('          </tr>');

        $w_total=0;
        foreach ($RS as $row) {
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          ShowHTML('        <td nowrap><font size="1"><a class="HL" href="https://honda.unesco.org.br/pls/seguranca/Frm_SP.Visualizar?p_usuario=167&p_Documento=111800&p_Acesso=C&p_Nro_Doc='.f($row,'documento').'&P1=0&P2=0&P3=0&TP=Consultar&p_ValidaTempo=Nao">'.f($row,'documento').'</a>');
          ShowHTML('        <td nowrap><font size="1">');
          $SQL="select numerodoc from corporativo.un_sol_pgto_doc_anexos@sicof a where a.automatico_sp = '".f($row,'handle')."' order by a.numerodoc ";
          $RS1 = DatabaseQueriesFactory::getInstanceOf($SQL, $dbms, null, DB_TYPE);
          if(!$RS1->executeQuery()) die("Cannot query"); else $RS1 = $RS1->getResultData();
          if (count($RS1) <= 0) {
            print '---';
          } else {
            foreach ($RS1 as $row1) {
              print f($row1,'numerodoc').'&nbsp;<br>';
            } 
          } 
          ShowHTML('            </td>');
          ShowHTML('        <td><font size="1">'.f($row,'nome').'</td>');
          ShowHTML('        <td align="center" nowrap><font size="1">'.f($row,'inicio').'</td>');
          ShowHTML('        <td nowrap><font size="1">'.f($row,'projeto').'</td>');
          ShowHTML('        <td align="right" nowrap><font size="1">'.FormatNumber(f($row,'valor'),2).'</td>');
          if ($_POST['p_documento']=="") ShowHTML('        <td><font size="1">'.f($row,'historico').'</td>');
          ShowHTML('        <td><font size="1">'.f($row,'fase_atual').'</td>');
          ShowHTML('      </tr>');
          $w_total = $w_total + f($row,'valor');
        } 
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
        ShowHTML('        <td colspan=5 align="right"><font size="1"><b>Total</b></font></td>');
        ShowHTML('        <td align="right"><font size="1"><b>'.FormatNumber($w_total,2).'</b></font></td>');
        ShowHTML('        <td colspan=2><font size="1">&nbsp;</td>');
        ShowHTML('      </tr>');
      } 
      ShowHTML('         </table></td></tr>');
      ShowHTML('      <tr><td align="right" colspan="2"><font size="1">&nbsp;');
    } 


    //VIAGENS A SERVIÇO
    if ($_POST['p_sq_pessoa']>"" || $_POST['p_cpf']>"" || $_POST['p_cnpj']>"" || ($_POST['p_documento']=="" && $_POST['p_inicio']>"") || ($_POST['p_documento']>"" && substr(strtoupper($_POST['p_documento']),0,3)=="SPD")) {

      $SQL="select a.handle, a.automatico_spd documento, a.finalidade historico, "."\r\n".
        "       nvl(to_char(a.dt_inicio,'dd/mm/yyyy'),'-') inicio, "."\r\n".
        "       nvl(to_char(a.dt_fim,'dd/mm/yyyy'),'-') fim,  "."\r\n".
        "       d.codigounesco projeto, "."\r\n".
        "       seguranca.fValor@sicof(a.valortotal) Valor, "."\r\n".
        "       seguranca.fcfaseatual@sicof(a.automatico_spd) fase_atual, b.nome "."\r\n".
        "from corporativo.Un_SolicitacaoPD@sicof a, "."\r\n".
        "    corporativo.Gn_Pessoas@sicof b, "."\r\n".
        "    corporativo.ct_cc@sicof d "."\r\n".
        "where a.contratado     = b.Handle "."\r\n".
        "  and a.acordo         = d.handle "."\r\n";
      if ($_POST['p_sq_pessoa']>"")  $SQL=$SQL."  and b.handle = ".$_POST['p_sq_pessoa']."\r\n";
      if ($_POST['p_ctcc']>"")       $SQL=$SQL."  and a.acordo = ".$_POST['p_ctcc']."\r\n";
      if ($_POST['p_cnpj']>"")       $SQL=$SQL."  and b.cgccpf = '".$_POST['p_cnpj']."'"."\r\n";
      if ($_POST['p_cpf']>"")        $SQL=$SQL."  and b.cgccpf = '".$_POST['p_cpf']."'"."\r\n";
      if ($_POST['p_documento']>"")  $SQL=$SQL."  and a.automatico_spd = '".strtoupper($_POST['p_documento'])."'"."\r\n";
      if ($_POST['p_inicio']>"") {
        $SQL=$SQL.
          "  and (a.dt_inicio  between to_date('".$_POST['p_inicio']."', 'dd/mm/yyyy') and to_date('".$_POST['p_fim']."', 'dd/mm/yyyy') or "."\r\n".
          "       a.dt_fim     between to_date('".$_POST['p_inicio']."', 'dd/mm/yyyy') and to_date('".$_POST['p_fim']."', 'dd/mm/yyyy') "."\r\n".
          "      ) "."\r\n";
      } 
      $SQL=$SQL." order by a.dt_inicio desc"."\r\n";

      ShowHTML('      <tr><td align="center" colspan="2" height="2" bgcolor="#000000">');
      ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
      ShowHTML('      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0"><font  size="2"><b>Passagens e Diárias</td>');
      ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
      ShowHTML('      <tr><td align="center" colspan="2" height="2" bgcolor="#000000">');
      $RS = DatabaseQueriesFactory::getInstanceOf($SQL, $dbms, null, DB_TYPE);
      if(!$RS->executeQuery()) die("Cannot query"); else $RS = $RS->getResultData();
      if (count($RS) <= 0) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font  size="2"><b>Nenhum registro encontrado.</b></td></tr>');
      } else {
        ShowHTML('      <tr><td align="right" colspan="2"><font size="1"><b>Registros: '.count($RS));
        ShowHTML('      <tr><td align="center" colspan="2">');
        ShowHTML('        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('            <td rowspan=2><font size="1"><b>Documento</font></td>');
        ShowHTML('            <td rowspan=2><font size="1"><b>Beneficiário</font></td>');
        ShowHTML('            <td colspan=2><font size="1"><b>Missão</font></td>');
        ShowHTML('            <td rowspan=2><font size="1"><b>Acordo</font></td>');
        if ($_POST['p_documento']=="") ShowHTML('            <td rowspan=2><font size="1"><b>Histórico</font></td>');
        ShowHTML('            <td rowspan=2><font size="1"><b>Fase atual</font></td>');
        ShowHTML('          </tr>');
        ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('            <td><font size="2"><b>Início</font></td>');
        ShowHTML('            <td><font size="2"><b>Término</font></td>');
        ShowHTML('          </tr>');

        foreach ($RS as $row) {
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          ShowHTML('        <td nowrap><font size="1"><a class="HL" href="https://honda.unesco.org.br/pls/seguranca/Frm_SPD.Visualizar?p_usuario=167&p_Documento=111800&p_Acesso=C&p_Nro_Doc='.f($row,'documento').'&P1=0&P2=0&P3=0&TP=Consultar&p_ValidaTempo=Nao">'.f($row,'documento').'</a>');
          ShowHTML('        <td><font size="1">'.f($row,'nome').'</td>');
          ShowHTML('        <td align="center" nowrap><font size="1">'.f($row,'inicio').'</td>');
          ShowHTML('        <td align="center" nowrap><font size="1">'.f($row,'fim').'</td>');
          ShowHTML('        <td><font size="1">'.f($row,'projeto').'</td>');
          if ($_POST['p_documento']=="") ShowHTML('        <td><font size="1">'.f($row,'historico').'</td>');
          ShowHTML('        <td><font size="1">'.f($row,'fase_atual').'</td>');
          ShowHTML('      </tr>');
        } 
      } 
      ShowHTML('         </table></td></tr>');
    } 

    ShowHTML('      <tr><td align="center" colspan="2"><font size="2">Clique <a accesskey="F" class="SS" href="#" onClick="window.close(); opener.focus();">aqui</a> para fechar esta janela.</font>');
    ShowHTML('     </tr></tr></td></table>');

    ShowHTML('</table>');
    ShowHTML('</center>');
  } elseif ($O=='P') {

    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="left"><font size=2>Instruções:<ul>');
    ShowHTML('  <li>Informe um dos critérios apresentados abaixo e clique sobre o botão <i>Aplicar filtro</i>.');
    ShowHTML('  <li>A procura pelo nome do beneficiário é feita em duas partes. Primeiro, informe parte dele em <i>Procurar nome</i> e clique sobre o botão <i>Procura</i>. Em seguida, selecione o nome desejado na lista disponível em <i>Beneficiário</i> e clique no botão <i>Aplicar Filtro</i>;');
    ShowHTML('  <li>Você pode informar quantos critérios desejar.');
    ShowHTML('  <li>O resultado será apresentado em outra janela.');
    ShowHTML('  </ul></div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="70%" border="0">');
    ShowHTML('      <tr valign="top"><td valign="top"><font  size="1">');
    ShowHTML('            <b>Pr<U>o</U>curar nome:<br> <INPUT TYPE="TEXT" ACCESSKEY="O" class="STI" name="p_beneficiario" size=40 maxlength=40>');
    ShowHTML('            <input class="STB" type="button" name="Procura" value="Procura" onClick="procura()">');

    ShowHTML('      <tr valign="top"><td valign="top"><font  size="1"><b><U>B</U>eneficiário:<br> <SELECT ACCESSKEY="B" class="STS" name="p_sq_pessoa" size="1">');
    ShowHTML('          <OPTION VALUE="">---');

    if ($_POST['p_beneficiario']>"") {
      $SQL="select b.handle, b.nome from corporativo.gn_pessoas@sicof b where upper(b.nome) like '%".strtoupper(str_replace("'","''",$_POST['p_beneficiario']))."%' order by seguranca.acentos@sicof(nome)";
    } else {
      $SQL="select * from corporativo.gn_pessoas@sicof where handle < 0";
    } 
    $RS = DatabaseQueriesFactory::getInstanceOf($SQL, $dbms, null, DB_TYPE);
    if(!$RS->executeQuery()) die("Cannot query"); else $RS = $RS->getResultData();
    foreach ($RS as $row) {
      if (f($row,'handle')==$_POST['p_sq_pessoa']) {
        ShowHTML('          <OPTION VALUE='.f($row,'handle').' selected>'.f($row,'nome'));
      } else {
        ShowHTML('          <OPTION VALUE='.f($row,'handle').'>'.f($row,'nome'));
      } 
    } 
    ShowHTML('          </SELECT></td>');
    ShowHTML('      </tr>');

    ShowHTML('      <tr valign="top"><td valign="top"><font  size="1"><b>Pro<U>j</U>eto:<br> <SELECT ACCESSKEY="J" class="STS" name="p_ctcc" size="1">');
    ShowHTML('          <OPTION VALUE="">---');

    $SQL="select a.HANDLE, a.NOME, a.CODIGOUNESCO, a.INICIO, a.TERMINO from CORPORATIVO.CT_CC@sicof a where a.ultimonivel='S' order by a.nome";
    $RS = DatabaseQueriesFactory::getInstanceOf($SQL, $dbms, null, DB_TYPE);
    if(!$RS->executeQuery()) die("Cannot query"); else $RS = $RS->getResultData();
    foreach ($RS as $row) {
      if (f($row,'handle')==$_POST['p_ctcc']) {
        ShowHTML('          <OPTION VALUE='.f($row,'handle').' selected>'.f($row,'nome'));
      } else {
        ShowHTML('          <OPTION VALUE='.f($row,'handle').'>'.f($row,'nome'));
      } 
    } 
    ShowHTML('          </SELECT></td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr valign="top"><td valign="top"><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('          <td><font  size="1"><b><U>C</U>NPJ:<br> <INPUT TYPE="TEXT" ACCESSKEY="C" class="STI" name="p_cnpj" size=18 maxlength=18 onKeyPress="FormataCNPJ(this,event);"  value="'.$_POST['p_cnpj'].'"></td>');
    ShowHTML('          <td><font  size="1"><b>C<U>P</U>F:<br> <INPUT TYPE="TEXT" ACCESSKEY="C" class="STI" name="p_cpf" size=14 maxlength=14 onKeyPress="FormataCPF(this,event);" value="'.$_POST['p_cpf'].'"></td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr valign="top"><td valign="top"><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('          <td valign="top"><font  size="1"><b>SA/SP/SP<U>D</U>:</b> (identificação completa)<br> <INPUT TYPE="TEXT" ACCESSKEY="D" class="STI" name="p_documento" size=15 maxlength=15 value="'.$_POST['p_documento'].'"></td>');
    ShowHTML('          <td><font  size="1">Período: <b>D<U>e</U>: <INPUT TYPE="TEXT" ACCESSKEY="E" class="STI" name="p_inicio" size=10 maxlength=10 onKeyPress="FormataData(this,event);"  value="'.$_POST['p_inicio'].'">');
    ShowHTML('                                <U>a</U>té: <INPUT TYPE="TEXT" ACCESSKEY="A" class="STI" name="p_fim" size=10 maxlength=10 onKeyPress="FormataData(this,event);" value="'.$_POST['p_fim'].'"></td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr valign="top"><td valign="top"><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('          <td><font  size="1"><b>Co<U>m</U>provante (NF/Fatura/Recibo):<br><INPUT TYPE="TEXT" ACCESSKEY="M" class="STI" name="p_comprovante" size=10 maxlength=10 value="'.$_POST['p_comprovante'].'">');
    ShowHTML('          <td><font  size="1">Período: <b>D<U>e</U>: <INPUT TYPE="TEXT" ACCESSKEY="E" class="STI" name="p_inicio_nf" size=10 maxlength=10 onKeyPress="FormataData(this,event);"  value="'.$_POST['p_inicio_nf'].'">');
    ShowHTML('                                <b><U>a</U>té: <INPUT TYPE="TEXT" ACCESSKEY="A" class="STI" name="p_fim_nf" size=10 maxlength=10 onKeyPress="FormataData(this,event);" value="'.$_POST['p_fim_nf'].'"></td>');
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
  return $function_ret;
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);

  switch ($par) {
    case "CONSULTA": Consulta(); break;
    default:
      Cabecalho();
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');
      BodyOpen('onLoad=document.focus();');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
      ShowHTML('<HR>');
      ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
      Rodape();
      break;
  } 
  return $function_ret;
} 
?>


