<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicFN.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicClassif.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoRubrica.php');
include_once($w_dir_volta.'funcoes/selecaoSolicApoio.php');

// =========================================================================
//  /alteracao.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papad�polis
// Descricao: Ajuste das classifica��es de lan�amentos financeiros
// Mail     : alex@sbpi.com.br
// Criacao  : 22/01/2018 10:55
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
if ($_SESSION['LOGON'] != 'Sim') { EncerraSessao(); }

// Carrega vari�veis locais com os dados dos par�metros recebidos
$par = upper($_REQUEST['par']);
$P1 = nvl($_REQUEST['P1'], 0);
$P2 = nvl($_REQUEST['P2'], 0);
$P3 = nvl($_REQUEST['P3'], 1);
$P4 = nvl($_REQUEST['P4'], $conPageSize);
$TP = $_REQUEST['TP'];
$SG = upper($_REQUEST['SG']);
$R = $_REQUEST['R'];
$O = upper($_REQUEST['O']);
$w_assinatura = $_REQUEST['w_assinatura'];
$w_pagina = 'alteracao.php?par=';
$w_Disabled = 'ENABLED';
$w_dir = 'mod_fn/';
$w_troca = $_REQUEST['w_troca'];
$w_embed = '';

$p_projeto = $_REQUEST['p_projeto'];
$p_inicio = $_REQUEST['p_inicio'];
$p_fim = $_REQUEST['p_fim'];
$p_nome = upper(trim($_REQUEST['p_nome']));
$p_ordena = lower($_REQUEST['p_ordena']);

// Declara��o de vari�veis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if ($O == '') {
  if ($par == 'INICIAL') {
    $O = 'P';
  } else {
    $O = 'L';
  }
}
// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente, $SG);
$w_TP       = RetornaTitulo($TP, $O);

// Recupera a configura��o do servi�o
$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);

Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Relat�rios de detalhamento das despesas de projeto.
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;
  global $w_embed;

  $w_sq_projeto_rubrica = $_REQUEST['w_sq_projeto_rubrica'];
  $w_solic_apoio        = $_REQUEST['w_solic_apoio'];
  $w_observacao         = $_REQUEST['w_observacao'];
  if (is_array($_REQUEST['w_chave'])) {
    $itens = $_REQUEST['w_chave'];
  } else {
    $itens = explode(',', $_REQUEST['w_chave']);
  }

  Cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - '.$TP.'</TITLE>');
  ScriptOpen('JavaScript');
  ShowHTML('$(document).ready(function() {');
  ShowHTML('  $("#marca_todos").click(function() {');
  ShowHTML('    var checked = this.checked;');
  ShowHTML('    $(".item").each(function() {');
  ShowHTML('      this.checked = checked;');
  ShowHTML('    });');
  ShowHTML('  });');
  ShowHTML('});');
  ShowHTML('function botao(texto) {');
  ShowHTML('  document.Form.w_botao.value = texto;');
  ShowHTML('}');
  CheckBranco();
  FormataData();
  SaltaCampo();
  ValidateOpen('Validacao');
  Validate('p_projeto', 'Projeto', 'SELECT', '1', '1', '18', '', '0123456789');
  Validate('p_inicio', 'Pagamento inicial', 'DATA', '', '10', '10', '', '0123456789/');
  Validate('p_fim', 'Pagamento final', 'DATA', '', '10', '10', '', '0123456789/');
  CompData('p_inicio', 'Pagamento inicial', '<=', 'p_fim', 'Pagamento final');
  Validate('p_ordena', 'Ordena��o', 'SELECT', '', '1', '20', '1', '1');
  if ($p_projeto) {
    Validate('w_sq_projeto_rubrica','Rubrica', 'SELECT', '', 1, 18, '', '0123456789');
    Validate('w_solic_apoio','Fonte de financiamento', 'SELECT', '', 1, 18, '', '0123456789');
    Validate('w_observacao','Observa��o', '', '', '3', '2000', '1', '1');
  }
  ShowHTML('  if (theForm.w_botao.value=="aplicar") {');
  ShowHTML('    if (theForm.w_sq_projeto_rubrica.selectedIndex==0 && theForm.w_solic_apoio.selectedIndex==0) {');
  ShowHTML('      alert("N�o h� o que alterar! Informe uma rubrica e/ou uma fonte de financiamento.");');
  ShowHTML('      theForm.w_sq_projeto_rubrica.focus();');
  ShowHTML('      return false;');
  ShowHTML('    } else if (theForm.w_observacao.value=="") {');
  ShowHTML('      alert("� obrigat�rio informar o motivo da altera��o.");');
  ShowHTML('      theForm.w_observacao.focus();');
  ShowHTML('      return false;');
  ShowHTML('    } else {');
  ShowHTML('      var i; ');
  ShowHTML('      var w_erro=true; ');
  ShowHTML('      for (i=1; i < theForm["w_chave[]"].length; i++) {');
  ShowHTML('        if (theForm["w_chave[]"][i].checked) {');
  ShowHTML('          w_erro=false; ');
  ShowHTML('          break; ');
  ShowHTML('        }');
  ShowHTML('      }');
  ShowHTML('      if (w_erro) {');
  ShowHTML('        alert("Voc� deve selecionar pelo menos um dos lan�amentos/itens listados!"); ');
  ShowHTML('        return false;');
  ShowHTML('      }');
  ShowHTML('      ');
  Validate('w_assinatura',$_SESSION['LABEL_ALERTA'], '1', '1', '3', '30', '1', '1');
  ShowHTML('    }');
  ShowHTML('      if (confirm("Confirma a altera��o dos lan�amentos/itens informados para a rubrica/fonte selecionadas?")) {');
  ShowHTML('        theForm.action="'.$w_dir.$w_pagina.'GRAVA";');
  ShowHTML('        theForm.Botao[0].disabled=true');
  ShowHTML('        theForm.Botao[1].disabled=true');
  ShowHTML('        return true;');
  ShowHTML('      } else {');
  ShowHTML('        return false;');
  ShowHTML('      }');
  ShowHTML('  }');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</HEAD>');
  if ($p_projeto) {
    BodyOpen('onLoad="document.focus(); document.forms[0].w_observacao.className=\'STIO\';"');
  } else {
    BodyOpen('onLoad="document.Form.p_projeto.focus()"');
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</font></B>');
  ShowHTML('<HR>');
  AbreForm('Form', $w_dir.$w_pagina.$par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina.$par, 'L');
  ShowHTML('<div align="center"><table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_botao" value="">');
  ShowHTML('<input type="hidden" name="w_chave[]" value=""></td>');
  ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td>');
  ShowHTML('    <table width="99%" border="0">');
  ShowHTML('      <tr>');
  $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,'PJCAD');
  SelecaoProjeto('Pro<u>j</u>eto:','J','Selecione o projeto a ter seus lan�amentos/itens ajustados.',$p_projeto,$w_usuario,f($RS,'sq_menu'),null,null,null,'p_projeto','PJLIST',$w_atributo);
  ShowHTML('      </tr>');
  ShowHTML('      <tr><td><b><u>P</u>agamento entre:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="p_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_inicio . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_inicio') . ' e <input '.$w_Disabled.' type="text" name="p_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_fim') . '</td>');
  ShowHTML('      <tr><td><b><u>O</u>rdenar listagem pela coluna:</b><br><select '.$w_Disabled.' accesskey="O" name="p_ordena" class="sti">');
  ShowHTML('        <option value="descricao"'.(($p_ordena=='descricao') ? ' SELECTED': '').'>Descri��o da despesa');
  ShowHTML('        <option value="nm_rubrica"'.(($p_ordena=='nm_rubrica') ? ' SELECTED': '').'>Nome da rubrica');
  ShowHTML('        <option value="cd_rubrica"'.((nvl($p_ordena,'cd_rubrica')=='cd_rubrica') ? ' SELECTED': '').'>C�digo da rubrica (padr�o)');
  ShowHTML('        <option value="nm_pessoa"'.(($p_ordena=='nm_pessoa') ? ' SELECTED': '').'>Benefici�rio');
  ShowHTML('        <option value="nm_tipo_documento"'.(($p_ordena=='nm_tipo_documento') ? ' SELECTED': '').'>Tipo do comprovante');
  ShowHTML('        <option value="numero"'.(($p_ordena=='numero') ? ' SELECTED': '').'>N�mero do comprovante');
  ShowHTML('        <option value="valor_doc"'.(($p_ordena=='valor_doc') ? ' SELECTED': '').'>Valor do comprovante');
  ShowHTML('        <option value="or_financeiro"'.(($p_ordena=='or_financeiro') ? ' SELECTED': '').'>C�digo do lan�amento');
  ShowHTML('        <option value="quitacao"'.(($p_ordena=='quitacao') ? ' SELECTED': '').'>Data de pagamento');
  ShowHTML('        <option value="valor"'.(($p_ordena=='valor') ? ' SELECTED': '').'>Valor do pagamento');
  ShowHTML('        </select>');
  ShowHTML('      <tr><td align="center"><hr>');
  ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir" onClick="botao(\'exibir\')">');
  ShowHTML('            <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $w_pagina . $par . '&R=' . $R . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&O=P&SG=' . $SG) . '\';" name="Botao" value="Limpar campos">');
  ShowHTML('          </td>');
  ShowHTML('      </tr>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</div>');
  
  if ($p_projeto) {
    $w_tipo = $_REQUEST['w_tipo'];

    // Recupera os dados do projeto selecionado
    $sql = new db_getSolicData; $RS_Projeto = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');

    // Recupera as rubricas do projeto
    $sql = new db_getSolicRubrica; $RSQuery = $sql->getInstanceOf($dbms,$p_projeto,null,'S',null,null,null,$p_inicio,$p_fim,'PJEXECLS');

    if ($p_ordena>'') { 
      $RSQuery = SortArray($RSQuery,$p_ordena,'asc','or_rubrica','asc','quitacao','asc','or_financeiro','asc','or_item','asc');
    } else {
      $RSQuery = SortArray($RSQuery,'or_rubrica','asc','quitacao','asc','or_financeiro','asc','or_item','asc');
    }

    $w_embed        = '';
    ShowHTML('<p></p><div align="center"><table border="0" cellpadding="0" cellspacing="0" width="100%">');

    ShowHTML('  <tr><td colspan=2><br><font size="2"><b>'.((!count($RSQuery)) ? 'NENHUM LAN�AMENTO ENCONTRADO' : ((count($RSQuery)==1) ? 'UM LAN�AMENTO ENCONTRADO' : count($RSQuery).' LAN�AMENTOS')).'<hr NOSHADE color=#000000 SIZE=1></b></font><br></td></tr>');

    if (count($RSQuery)) {
      ShowHTML('  <tr bgcolor="'.$conTrBgColor.'"><td colspan=2><div align="justify"><font size=2><b><ul>Instru��es</b>:<li>Informe pelo menos um dos campos abaixo.<li>Em seguida, selecione os lan�amentos/itens nos quais deseja aplicar a altera��o.<li>Ap�s terminar a sele��o, clique sobre o bot�o <i>Aplicar altera��es</i>.</ul><hr></div>');

      ShowHTML('      <tr><td><p>&nbsp;</p></td></tr>');
      // Trata a rubrica
      ShowHTML('      <tr>');
      SelecaoRubrica('Rubrica a ser aplicada aos lan�amentos/itens desejados: (se n�o indicar alguma, a rubrica atual ser� mantida)',null, null, $w_sq_projeto_rubrica,$p_projeto,null,'w_sq_projeto_rubrica','SELECAO',null,2);
      ShowHTML('      </tr>');
      ShowHTML('      <tr><td><p>&nbsp;</p></td></tr>');
      // Trata fonte de financiamento
      ShowHTML('      <tr>');
      SelecaoSolicApoio('Fonte de financiamento a ser aplicada aos lan�amentos/itens desejados: (se n�o indicar alguma, a fonte atual ser� mantida)',null,null,$w_solic_apoio,$p_projeto,'w_solic_apoio',null,null,2);
      ShowHTML('      </tr>');
      ShowHTML('      <tr><td><p>&nbsp;</p></td></tr>');
      ShowHTML('      <tr><td><b>Motivo da altera��o:</b><br><textarea '.$w_Disabled.' accesskey="N" name="w_observacao" class="sti" ROWS=5 cols=75 title="Redija a anota��o desejada.">'.$w_observacao.'</TEXTAREA></td>');
      ShowHTML('      <tr><td><p>&nbsp;</p></td></tr>');

      ShowHTML('      <tr><td ><b>Selecione abaixo os lan�amentos/itens que devem ter a rubrica e/ou fonte de financiamento alterada para os valores indicados acima:</b>');
      ShowHTML('      <tr><td align="center">');
      ShowHTML('    <table width=99%  border="1" bordercolor="#00000">');

      ShowHTML('      <tr align="center">');
      $cs=0;
      $l_html = '';
      $cs++; $l_html.=chr(13).'        <td rowspan="2" bgColor="#f0f0f0"><input type="checkbox" id="marca_todos" name="marca_todos" value="" />';
      $cs++; $l_html.=chr(13).'        <td rowspan="2" bgColor="#f0f0f0"><b>Descri��o da despesa</td>';
      $l_html.=chr(13).'        <td colspan="2" bgColor="#f0f0f0"><b>Rubrica</td>';
      $cs++; $l_html.=chr(13).'        <td rowspan="2" bgColor="#f0f0f0"><b>Fonte</td>';
      $cs++; $l_html.=chr(13).'        <td rowspan="2" bgColor="#f0f0f0"><b>Benefici�rio</td>';
      $l_html.=chr(13).'        <td colspan="3" bgColor="#f0f0f0"><b>Comprovante</td>';
      $l_html.=chr(13).'        <td colspan="3" bgColor="#f0f0f0"><b>Pagamento</td>';
      $l_html.=chr(13).'      </tr>';
      $l_html.=chr(13).'      <tr align="center" >';
      $cs++; $l_html.=chr(13).'        <td bgColor="#f0f0f0"><b>C�digo</td>';
      $cs++; $l_html.=chr(13).'        <td bgColor="#f0f0f0"><b>Descri��o</td>';
      $cs++; $l_html.=chr(13).'        <td bgColor="#f0f0f0"><b>Tipo</td>';
      $cs++; $l_html.=chr(13).'        <td bgColor="#f0f0f0"><b>N�mero</td>';
      $cs++; $l_html.=chr(13).'        <td bgColor="#f0f0f0"><b>Valor</td>';
      $cs++; $l_html.=chr(13).'        <td bgColor="#f0f0f0"><b>N�mero</td>';
      $cs++; $l_html.=chr(13).'        <td bgColor="#f0f0f0"><b>Data</td>';
      $l_html.=chr(13).'        <td bgColor="#f0f0f0"><b>Valor '.f($RS_Projeto,'sg_moeda').'</td>';
      $l_html.=chr(13).'      </tr>';
      $w_cor=$conTrBgColor;
      $i = 0;
      foreach ($RSQuery as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;

        $fn_valor = f($row,'fn_valor');
        if (strpos(f($row,'descricao'),'FCTS')!==false) $fn_valor = abs($fn_valor);

        $valor = f($row,'valor');
        if (strpos(f($row,'descricao'),'FCTS')!==false) $valor = abs($valor);

        $i++;
        $l_html.=chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
        $l_html.=chr(13).'        <td align="center"><input class="item" type="CHECKBOX" '.((in_array(f($row, 'sq_financeiro').'|'.f($row, 'sq_documento_item'), $itens)) ? 'CHECKED ' : '').'name="w_chave[]" value="' . f($row, 'sq_financeiro').'|'.f($row, 'sq_documento_item') . '"></td>';
        $l_html.=chr(13).'        <td>'.f($row,'descricao').'</td>';
        $l_html.=chr(13).'        <td align="center">'.f($row,'cd_rubrica').'</td>';
        $l_html.=chr(13).'        <td>'.f($row,'nm_rubrica').'</td>';
        $l_html.=chr(13).'        <td>'.f($row,'nm_fonte').'</td>';
        $l_html.=chr(13).'        <td>'.f($row,'nm_pessoa').'</td>';
        $l_html.=chr(13).'        <td title="'.f($row,'nm_tipo_documento').'">'.f($row,'sg_tipo_documento').'</td>';
        $l_html.=chr(13).'        <td>'.f($row,'numero').'</td>';
        $l_html.=chr(13).'        <td align="right" nowrap>'.f($row,'sb_fn_moeda').' '.formatNumber(f($row,'valor_doc')).'</td>';
        $l_html.=chr(13).'        <td nowrap>'.exibeSolic($w_dir,f($row,'sq_financeiro'),f($row,'cd_financeiro'),'N',$w_tipo);
        $l_html.=chr(13).'        <td align="right">'.nvl(FormataDataEdicao(f($row,'quitacao'),5),'&nbsp;').'</td>';
        $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor')).'</td>';
        $l_html.=chr(13).'      </tr>';
      } 
    }
    ShowHTML($l_html);
    ShowHTML('    </table><br></td></tr>');
    ShowHTML('    <tr><td><p><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></p><hr></td></tr>');
    ShowHTML('  </table></td></tr>');
    ShowHTML('  <tr><td align="center"><p>');
    ShowHTML('      <input class="STB" type="submit" name="Botao" value="Aplicar altera��es" onClick="botao(\'aplicar\')">');
    ShowHTML('    </td>');
    ShowHTML('  </tr>');
    ShowHTML('</table>');
    ShowHTML('</div>');
    
  }
  ShowHTML('</FORM>');

  Rodape();
}

// =========================================================================
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  global $w_Disabled;
  Cabecalho();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=this.focus();');
  switch ($SG) {
    case 'CLASSFN':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {

        // Aplica as altera��es
        $SQL = new dml_putSolicClassif;
        for ($i=0; $i<=count($_POST['w_chave'])-1; $i=$i+1) {
          if (Nvl($_REQUEST['w_chave'][$i],'0')!='0') {
            // Recupera a chave prim�ria do lan�amento financeiro
            $l_solic = piece($_REQUEST['w_chave'][$i],null,'|',1);
            // Recupera a chave prim�ria do item
            $l_item  = piece($_REQUEST['w_chave'][$i],null,'|',2);
            // Executa a altera��o
            $SQL->getInstanceOf($dbms,$w_usuario, $_REQUEST['w_observacao'], 
                    $_REQUEST['w_sq_solic_rubrica'],$_REQUEST['w_solic_apoio'],$l_solic,$l_item);
          } 
        }
          
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.
                '&w_sq_projeto_rubrica='.$_REQUEST['w_sq_projeto_rubrica'].
                '&w_solic_apoio='.$_REQUEST['w_solic_apoio'].'&w_observacao='.
                $_REQUEST['w_observacao'].
                '&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    default:
      exibevariaveis();
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Bloco de dados n�o encontrado: '.$SG.'");');
      ScriptClose();
      break;
  } 
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'INICIAL': Inicial(); break;
    case 'GRAVA':   Grava();   break;
    default:
      cabecalho();
      ShowHTML('<BASE HREF="' . $conRootSIW . '">');
      BodyOpen('onLoad=this.focus();');
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
