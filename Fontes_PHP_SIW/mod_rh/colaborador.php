<?
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
include_once($w_dir_volta.'classes/sp/db_getAfastamento.php');
include_once($w_dir_volta.'classes/sp/db_getViagemBenef.php');
include_once($w_dir_volta.'classes/sp/db_getGPTipoAfast.php');
include_once($w_dir_volta.'classes/sp/db_getGPColaborador.php');
include_once($w_dir_volta.'classes/sp/db_getGPContrato.php');
include_once($w_dir_volta.'classes/sp/db_getPersonList.php');
include_once($w_dir_volta.'classes/sp/db_getCV.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putGPColaborador.php');
include_once($w_dir_volta.'classes/sp/dml_putGPContrato.php');
include_once($w_dir_volta.'classes/sp/dml_putSiwUsuario.php');
include_once($w_dir_volta.'funcoes/exibeColaborador.php');
include_once($w_dir_volta.'funcoes/selecaoColaborador.php');
include_once($w_dir_volta.'funcoes/selecaoModalidade.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoCargo.php');
include_once($w_dir_volta.'funcoes/selecaoLocalizacao.php');
include_once($w_dir_volta.'funcoes/selecaoVinculo.php');
include_once('validacolaborador.php');
// =========================================================================
//  /Colaborador.php
// ------------------------------------------------------------------------
// Nome     : Billy Jones Leal dos Santos
// Descricao: Gerenciar o cadastramento de colaboradores
// Mail     : billy@sbpi.com.br
// Criacao  : 11/08/2006 10:00
// Versao   : 1.0.0.0
// Local    : Bras�lia - DF
// -------------------------------------------------------------------------
// 
// Par�metros recebidos:
//    R (refer�ncia) = usado na rotina de grava��o, com conte�do igual ao par�metro T
//    O (opera��o)   = I   : Inclus�o
//                   = A   : Altera��o
//                   = E   : Exclus�o
//                   = L   : Listagem

// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); } 

// Declara��o de vari�veis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega vari�veis locais com os dados dos par�metros recebidos
$par            = strtoupper($_REQUEST['par']);
$P1             = Nvl($_REQUEST['P1'],0);
$P2             = Nvl($_REQUEST['P2'],0);
$P3             = Nvl($_REQUEST['P3'],1);
$P4             = Nvl($_REQUEST['P4'],$conPageSize);
$TP             = $_REQUEST['TP'];
$SG             = strtoupper($_REQUEST['SG']);
$R              = strtolower($_REQUEST['R']);
$O              = strtoupper($_REQUEST['O']);
$p_ordena       = strtolower($_REQUEST['p_ordena']);
$w_troca        = strtolower($_REQUEST['w_troca']);
$w_assinatura = strtoupper($_REQUEST['w_assinatura']);
$w_pagina       = 'colaborador.php?par=';
$w_dir          = 'mod_rh/';
$w_dir_volta    = '../';
$w_Disabled     = 'ENABLED';

if ($O=='') $O='P'; 
switch ($O) {
  case 'I':    $w_TP=$TP.' - Inclus�o';     break;
  case 'P':    $w_TP=$TP.' - Filtragem';    break;
  case 'A':    $w_TP=$TP.' - Altera��o';    break;
  case 'E':
    if ($par=='CONTRATO') $w_TP=$TP.' - Encerramento';
    else                  $w_TP=$TP.' - Exclus�o';
    break;
  default:    $w_TP=$TP.' - Listagem';        break;
} 
// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente=RetornaCliente();
$w_usuario=RetornaUsuario();
$w_menu=RetornaMenu($w_cliente,$SG);
Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Rotina da tabela de colaborador
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  Global $w_Disabled;
  $p_contrato_colaborador   = strtoupper($_REQUEST['p_contrato_colaborador']);
  $p_modalidade_contrato    = strtoupper($_REQUEST['p_modalidade_contrato']);
  $p_unidade_lotacao        = strtoupper($_REQUEST['p_unidade_lotacao']);
  $p_filhos_lotacao         = strtoupper($_REQUEST['p_filhos_lotacao']);
  $p_unidade_exercicio      = strtoupper($_REQUEST['p_unidade_exercicio']);
  $p_filhos_exercicio       = strtoupper($_REQUEST['p_filhos_exercicio']);
  $p_afastamento            = explodeArray($_REQUEST['p_afastamento']);
  $p_dt_ini                 = strtoupper($_REQUEST['p_dt_ini']);
  $p_dt_fim                 = strtoupper($_REQUEST['p_dt_fim']);
  $p_ferias                 = strtoupper($_REQUEST['p_ferias']);
  $p_viagem                 = strtoupper($_REQUEST['p_viagem']);
  $w_sq_pessoa              = strtoupper($_REQUEST['w_sq_pessoa']);
  $w_nome                   = strtoupper($_REQUEST['w_nome']);
  $w_cpf                    = strtoupper($_REQUEST['w_cpf']);
  $w_botao                  = strtoupper($_REQUEST['w_botao']);
  if ($O=='L') {
    $RS = db_getGPColaborador::getInstanceOf($dbms,$w_cliente,$p_contrato_colaborador,null,null,$p_modalidade_contrato,$p_unidade_lotacao,$p_filhos_lotacao,$p_unidade_exercicio,$p_filhos_exercicio,$p_afastamento,$p_dt_ini,$p_dt_fim,$p_ferias,$p_viagem,null,'COLABORADOR');
    if ($p_ordena>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome_resumido_ind','asc');
    } else {
      $RS = SortArray($RS,'nome_resumido_ind','asc');
    } 
  } elseif ($O=='E') {
    $RS = db_getCV::getInstanceOf($dbms,$w_cliente,$w_sq_pessoa,'CVIDENT','DADOS');
    foreach($RS as $row){$RS=$row; break;}
    $RS1 = db_getGPContrato::getInstanceOf($dbms,$w_cliente,null,$w_sq_pessoa,null,null,null,null,null,null,null,null,null,null);
    foreach($RS1 as $row){$RS1=$row; break;}
    $w_erro = ValidaColaborador($w_cliente,$w_sq_pessoa,f($RS1,'chave'),null);
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  ScriptOpen('Javascript');
  modulo();
  CheckBranco();
  FormataData();
  FormataCPF();
  ValidateOpen('Validacao');
  if (!(strpos('P',$O)===false)) {
    ShowHTML('  var cont = 0;');
    ShowHTML('  for (i=0;i<theForm["p_afastamento[]"].length;i++) {');
    ShowHTML('    if (theForm["p_afastamento[]"][i].checked) {');
    ShowHTML('      cont = cont+1;');
    ShowHTML('    }');
    ShowHTML('  }');
    ShowHTML('if (theForm.p_contrato_colaborador.value == \'\' && theForm.p_modalidade_contrato.value == \'\' && theForm.p_unidade_lotacao.value == \'\' && theForm.p_unidade_exercicio.value == \'\' && theForm.p_ferias.checked == false && theForm.p_viagem.checked == false) { ');
    ShowHTML('  if (cont == 0) { ');
    ShowHTML('    alert(\'Pelo menos um crit�rio de filtragem deve ser informado!\');');
    ShowHTML('    return false;');
    ShowHTML('  }');
    ShowHTML('}');
    ShowHTML('if (theForm.p_filhos_lotacao.checked && theForm.p_unidade_lotacao.value == \'\') {');
    ShowHTML('  alert(\'Os campos ""Exibir colaboradores das unidades subordinadas"" somente podem ser marcados se os respectivos campos de unidade forem selecionados!\');');
    ShowHTML('  return false;');
    ShowHTML('}');
    ShowHTML('if (theForm.p_filhos_exercicio.checked && theForm.p_unidade_exercicio.value == \'\') {');
    ShowHTML('  alert(\'Os campos ""Exibir colaboradores das unidades subordinadas"" somente podem ser marcados se os respectivos campos de unidade forem selecionados!\');');
    ShowHTML('  return false;');
    ShowHTML('}');
    ShowHTML('if (cont == 0 && theForm.p_dt_ini.value != \'\' && theForm.p_ferias.checked == false && theForm.p_viagem.checked == false) { ');
    ShowHTML('  alert(\'Se nenhum dos itens indicados no campo ""Afastado por"" for selecionado, ent�o o per�odo de busca n�o pode ser informado!\');');
    ShowHTML('  return false;');
    ShowHTML('} else { ');
    ShowHTML('  if ((cont > 0 || theForm.p_ferias.checked == true || theForm.p_viagem.checked == true)) {');
    Validate('p_dt_ini','Periodo de busca','DATA','1','10','10','','0123456789/');
    Validate('p_dt_fim','Periodo de busca','DATA','1','10','10','','0123456789/');
    CompData('p_dt_ini','In�cio','<=','p_dt_fim','T�rmino');
    ShowHTML('  } else { ');
    ShowHTML('  }');
    ShowHTML('}');
  } elseif (!(strpos('I',$O)===false)) {
    ShowHTML('  if (theForm.Botao.value == "Procurar") {');
    Validate('w_nome','Nome','','1','4','20','1','');
    ShowHTML('  theForm.Botao.value = "Procurar";');
    ShowHTML('}');
    ShowHTML('else if (theForm.Botao.value == "Selecionar") {');
    Validate('w_cpf','CPF','CPF','1','10','14','','0123456789-.');
    ShowHTML('  theForm.w_sq_pessoa.value = \'\';');
    ShowHTML('}');
    ShowHTML('else { theForm.w_cpf.value = \'GERAR\'; }');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ShowHTML('  theForm.Botao[2].disabled=true;');
    ShowHTML('  theForm.Botao[3].disabled=true;');
  } elseif ((!(strpos('E',$O)===false)) && $w_erro=='') {
      Validate('w_assinatura','Assinatura eletr�nica','1','1','3','14','1','1');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    // Se for recarga da p�gina
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
  } elseif (!(strpos('P',$O)===false)) {
    BodyOpen('onLoad=\'document.Form.p_contrato_colaborador.focus()\';');
  } elseif (!(strpos('I',$O)===false)) {
    BodyOpen('onLoad=\'document.Form.w_cpf.focus()\';');
  } else {
    BodyOpen('onLoad=document.focus();');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td>');
    ShowHTML('                         <a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('                         <a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar</a>');
    ShowHTML('    <td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Matricula','matricula').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome_resumido_ind').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Modalidade','nm_modalidade_contrato').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Exerc�cio','nm_exercicio').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ramal','ramal').'</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $RS1 = array_slice($RS,(($P3-1)*$P4),$P4);
      foreach($RS1 as $row){   
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center">'.Nvl(f($row,'matricula'),'---').'</td>');
        ShowHTML('        <td align="left">'.ExibeColaborador('',$w_cliente,f($row,'chave'),$TP,f($row,'nome_resumido')).'</td>');
        ShowHTML('        <td align="left">'.f($row,'nm_modalidade_contrato').'</td>');
        ShowHTML('        <td align="left">'.ExibeUnidade('../',$w_cliente,f($row,'local'),f($row,'sq_unidade_exercicio'),$TP).'</td>');
        ShowHTML('        <td align="center">'.Nvl(f($row,'ramal'),'---').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="menu.php?par=ExibeDocs&O=A&w_usuario='.f($row,'chave').'&R='.$w_pagina.$par.'&SG='.$SG.'&TP='.$TP.'&w_documento='.f($row,'nome_resumido').MontaFiltro('GET').'" title="Altera as informa��es cadastrais do colaborador" TARGET="menu">Alterar</a>&nbsp;');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_cliente='.$w_cliente.'&w_sq_pessoa='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclui o colaborador do banco de dados">Excluir</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG,$RS->PageCount,$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG,$RS->PageCount,$P3,$P4,count($RS));
    } 
    ShowHTML('</tr>');
  } elseif (!(strpos('P',$O)===false)) {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,1,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center"><div align="justify">Informe nos campos abaixo os crit�rios que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Limpar campos</i>, o filtro existente ser� apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr>');
    SelecaoColaborador('<u>C</u>olaborador:','C',null,$p_contrato_colaborador,null,'p_contrato_colaborador','COLABORADOR',null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    SelecaoModalidade('<u>M</u>odalidade de contrata��o:','C',null,$p_modalidade_contrato,null,'p_modalidade_contrato',null,null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    SelecaoUnidade('Unidade de <U>l</U>ota��o:','L',null,$p_unidade_lotacao,null,'p_unidade_lotacao',null,null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    if ($p_filhos_lotacao > '') ShowHTML('        <td><input type="checkbox" name="p_filhos_lotacao" value="S" checked>Exibir colaboradores das unidades subordinadas</td>');
    else                        ShowHTML('        <td><input type="checkbox" name="p_filhos_lotacao" value="S">Exibir colaboradores das unidades subordinadas</td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    SelecaoUnidade('Unidade de <U>e</U>xerc�cio:','E',null,$p_unidade_exercicio,null,'p_unidade_exercicio',null,null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    if ($p_filhos_exercicio > '') ShowHTML('        <td><input type="checkbox" name="p_filhos_exercicio" value="S" checked>Exibir colaboradores das unidades subordinadas</td>');
    else                          ShowHTML('        <td><input type="checkbox" name="p_filhos_exercicio" value="S">Exibir colaboradores das unidades subordinadas</td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td><b>Afastado por:</b><br>');
    $RS1 = db_getGPTipoAfast::getInstanceOf($dbms,$w_cliente,null,null,null,'S',null,null);
    $RS1 = SortArray($RS1,'nome','asc');
    ShowHTML('      <tr><td><table width="100%" border="0">');
    ShowHTML('        <tr>');
    if($p_ferias > '') ShowHTML('          <td><input type="checkbox" name="p_ferias" value="S" checked>F�rias');
    else               ShowHTML('          <td><input type="checkbox" name="p_ferias" value="S">F�rias');
    if($p_viagem > '') ShowHTML('          <td><input type="checkbox" name="p_viagem" value="S" checked>Viagem a servi�o');
    else               ShowHTML('          <td><input type="checkbox" name="p_viagem" value="S">Viagem a servi�o');
    if (count($RS1)>0) {
      $i = 2;
      foreach ($RS1 as $row) {
        if (!($i%2)) ShowHTML('        <tr>');
        $l_marcado = 'N';
        $l_chave   = $p_afastamento.',';
        while (!(strpos($l_chave,',')===false)) {
          $l_item  = trim(substr($l_chave,0,strpos($l_chave,',')));
          $l_chave = trim(substr($l_chave,(strpos($l_chave,',')+1),100));
          if ($l_item > '') {if (f($row,'chave')==$l_item) $l_marcado = 'S'; }
        }
        if ($l_marcado=='S')  ShowHTML('          <td><input type="checkbox" name="p_afastamento[]" value="'.f($row,'chave').'" checked>'.f($row,'nome').'<br>');
        else                  ShowHTML('          <td><input type="checkbox" name="p_afastamento[]" value="'.f($row,'chave').'">'.f($row,'nome').'<br>');
        $i += 1;
      } 
    } 
    ShowHTML('       </table></td></tr>');
    ShowHTML('      <tr><td><b><u>P</u>er�odo de busca:</b><br> De: <input accesskey="P" type="text" name="p_dt_ini" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_dt_ini.'" onKeyDown="FormataData(this,event);"> a <input accesskey="P" type="text" name="p_dt_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_dt_fim.'" onKeyDown="FormataData(this,event);"></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Incluir">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Limpar campos">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif (!(strpos('I',$O)===false)) {
    ShowHTML('<FORM action="'.$w_dir.$w_pagina.$par.'" method="POST" name="Form" onSubmit="return(Validacao(this));">');
    ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
    ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
    ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
    ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
    ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
    ShowHTML('<INPUT type="hidden" name="SG" value="'.$SG.'">');
    ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
    ShowHTML('<INPUT type="hidden" name="O" value="'.$O.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sq_pessoa" value="'.$w_sq_pessoa.'">');
    ShowHTML('<INPUT type="hidden" name="w_botao" value="'.$w_botao.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table border="0">');
    ShowHTML('        <tr><td colspan=4><font size=2>Informe o CPF e clique no bot�o "Selecionar" para continuar.</font></TD>');
    ShowHTML('        <tr><td colspan=4><b><u>C</u>PF:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cpf" VALUE="'.$w_cpf.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
    ShowHTML('            <INPUT class="stb" TYPE="submit" NAME="Botao" VALUE="Selecionar" onClick="Botao.value=this.value; w_botao.value=Botao.value;document.Form.action=\''.$w_dir.'cv.php?par=Identificacao\';document.Form.SG.value=\'CVIDENT\';document.Form.P1.value=\'1\';">');
    ShowHTML('            <INPUT class="stb" TYPE="button" NAME="Botao" VALUE="Cancelar" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';">');
    ShowHTML('        <tr><td colspan=4><font size=2>Se a pessoa n�o tem CPF e o sistema ainda n�o gerou um c�digo para ela, clique no bot�o abaixo. Menores, ind�genas e estrangeiros sem CPF, que ainda n�o tenham seu c�digo gerado pelo sistema enquadram-se nesta situa��o. Se o sistema j� gerou um c�digo para a pessoa, informe-o no campo CPF, acima.</font></TD>');
    ShowHTML('        <tr><td colspan=4><INPUT class="stb" TYPE="submit" NAME="Botao" VALUE="Pessoa sem CPF nem c�digo gerado pelo sistema" onClick="Botao.value=this.value; w_botao.value=Botao.value;document.Form.action=\''.$w_dir.'cv.php?par=Identificacao\';document.Form.SG.value=\'CVIDENT\';document.Form.P1.value=\'1\';">');
    ShowHTML('        <tr><td colspan=4><p>&nbsp</p>');
    ShowHTML('        <tr><td colspan=4 heigth=1 bgcolor="#000000">');
    ShowHTML('        <tr><td colspan=4>');
    ShowHTML('             <b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome SEM ACENTOS)<br><INPUT ACCESSKEY="P" TYPE="text" class="sti" NAME="w_nome" VALUE="'.$w_nome.'" SIZE="20" MaxLength="20">');
    ShowHTML('              <INPUT class="stb" TYPE="submit" NAME="Botao" VALUE="Procurar" onClick="Botao.value=this.value; w_botao.value=Botao.value; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'">');
    ShowHTML('      </table>');
    if ($w_nome>'') {
      $RS = db_getPersonList::getInstanceOf($dbms,$w_cliente,null,'PESSOA',$w_nome,null,null,null);
      $RS = SortArray($RS,'nome','asc');
      ShowHTML('<tr><td colspan=3>');
      ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>Nome</td>');
      ShowHTML('          <td><b>Nome resumido</td>');
      ShowHTML('          <td><b>CPF</td>');
      ShowHTML('          <td><b>Opera��es</td>');
      ShowHTML('        </tr>');
      if (count($RS)<=0) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>N�o h� pessoas que contenham o texto informado.</b></td></tr>');
      } else {
        foreach ($RS as $row) {
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          ShowHTML('        <td>'.f($row,'nome').'</td>');
          ShowHTML('        <td>'.f($row,'nome_resumido').'</td>');
          ShowHTML('        <td align="center">'.Nvl(f($row,'cpf'),'---').'</td>');
          ShowHTML('        <td nowrap>');
          ShowHTML('          <A class="hl" HREF="'.$w_dir.'cv.php?par=Identificacao&R='.$R.'&O=I&w_cpf='.f($row,'cpf').'&w_sq_pessoa='.f($row,'sq_pessoa').'&P1=1&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CVIDENT">Selecionar</A>&nbsp');
          ShowHTML('        </td>');
          ShowHTML('      </tr>');
        } 
      } 
      ShowHTML('      </center>');
      ShowHTML('    </table>');
      ShowHTML('  </td>');
      ShowHTML('</tr>');
    } 
    ShowHTML('</FORM>');
  } elseif (!(strpos('E',$O)===false)) {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_sq_pessoa" value="'.$w_sq_pessoa.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="99%" border="0">');
    ShowHTML('      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Identifica��o</td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td>Nome:<br><b>'.f($RS,'nome').' </b></td>');
    ShowHTML('          <td>Nome resumido:<br><b>'.f($RS,'nome_resumido').' </b></td>');
    ShowHTML('          <td>Data nascimento:<br><b>'.FormataDataEdicao(f($RS,'nascimento')).' </b></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td>Sexo:<br><b>'.f($RS,'nm_sexo').' </b></td>');
    ShowHTML('          <td>Estado civil:<br><b>'.f($RS,'nm_estado_civil').' </b></td>');
    if (nvl(f($RS,'sq_siw_arquivo'),'nulo')!='nulo' && $P2==0) {
      ShowHTML('          <td rowspan=3>'.LinkArquivo(null,$w_cliente,f($RS,'sq_siw_arquivo'),'_blank',null,'<img title="clique para ver em tamanho original." border=1 width=100 length=80 src="'.LinkArquivo(null,$w_cliente,f($RS,'sq_siw_arquivo'),null,null,null,'EMBED').'">',null).'</td>');
    } else {
      ShowHTML('          <td rowspan=3></td>');
    } 
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td>Forma��o acad�mica:<br><b>'.f($RS,'nm_formacao').' </b></td>');
    ShowHTML('          <td>Etnia:<br><b>'.f($RS,'nm_etnia').' </b></td>');
    ShowHTML('      <tr><td>Defici�ncia:<br><b>'.Nvl(f($RS,'nm_deficiencia'),'---').' </b></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td>Identidade:<br><b>'.f($RS,'rg_numero').' </b></td>');
    ShowHTML('          <td>Emissor:<br><b>'.f($RS,'rg_emissor').' </b></td>');
    ShowHTML('          <td>Data de emiss�o:<br><b>'.FormataDataEdicao(f($RS,'rg_emissao')).' </b></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td>CPF:<br><b>'.f($RS,'cpf').'</b></td>');
    ShowHTML('          <td>Passaporte:<br><b>'.Nvl(f($RS,'passaporte_numero'),'---').' </b></td>');
    ShowHTML('      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Local de nascimento</td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td>Pa�s:<br><b>'.f($RS,'nm_pais_nascimento').' </b></td>');
    ShowHTML('          <td>Estado:<br><b>'.f($RS,'nm_uf_nascimento').' </b></td>');
    ShowHTML('          <td>Cidade:<br><b>'.f($RS,'nm_cidade_nascimento').' </b></td>');
    ShowHTML('      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Dados do contrato</td>');
    ShowHTML('<INPUT type="hidden" name="w_sq_contrato_colaborador" value="'.f($RS1,'chave').'">');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td>Cargo:<br><b>'.f($RS1,'nm_posto_trabalho').'</b></td>');
    ShowHTML('          <td>Modalidade de contrata��o:<br><b>'.Nvl(f($RS1,'nm_modalidade_contrato'),'---').' </b></td>');
    ShowHTML('     </tr>');
    ShowHTML('     <tr valign="top">');
    ShowHTML('        <td valign="top">Unidade de lota��o:<br><b>'.f($RS1,'nm_unidade_lotacao').'('.f($RS1,'sg_unidade_lotacao').')</b></td>');
    ShowHTML('        <td valign="top">Unidade de exerc�cio:<br><b>'.f($RS1,'nm_unidade_exercicio').'('.f($RS1,'sg_unidade_exercicio').')</b></td>');
    ShowHTML('        <td valign="top">Localiza��o:<br><b>'.f($RS1,'local').'</b></td>');
    ShowHTML('     </tr>');
    ShowHTML('     <tr valign="top">');
    ShowHTML('        <td><b>Matr�cula:</b><br>'.Nvl(f($RS1,'matricula'),'---').'</td>');
    ShowHTML('        <td><b>In�cio da vig�ncia:</b><br>'.FormataDataEdicao(f($RS1,'inicio')));
    ShowHTML('        <td><b>Fim da vig�ncia:</b><br>'.Nvl(FormataDataEdicao(f($RS1,'fim')),'---'));
    if ($w_erro>'') {
      ShowHTML('<tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td colspan="3">');
      ShowHTML('<font color="#BC3131"><b>ATEN��O:</b> Foram identificados os erros listados abaixo, n�o sendo poss�vel a conclus�o da opera��o.</font>');
      ShowHTML('<UL>'.$w_erro.'</UL>');
      ShowHTML('</td></tr>');
    } if ($w_erro=='') {
        ShowHTML('     <tr><td align="LEFT" colspan="3"><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    } 
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    if ($w_erro=='') {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Excluir">');
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 

// =========================================================================
// Rotina dos dados de documenta��o do colaborador
// -------------------------------------------------------------------------
function Documentacao() {
  extract($GLOBALS);
  Global $w_Disabled;
  // Verifica se h� necessidade de recarregar os dados da tela a partir
  // da pr�pria tela (se for recarga da tela) ou do banco de dados (se n�o for inclus�o)
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_ctps_numero        = $_REQUEST['w_ctps_numero'];
    $w_ctps_serie         = $_REQUEST['w_ctps_serie'];
    $w_ctps_emissor       = $_REQUEST['w_ctps_emissor'];
    $w_ctps_emissao       = $_REQUEST['w_ctps_emissao'];
    $w_pis_pasep          = $_REQUEST['w_pis_pasep'];
    $w_pispasep_numero    = $_REQUEST['w_pispasep_numero'];
    $w_pispasep_cadastr   = $_REQUEST['w_pispasep_cadastr'];
    $w_te_numero          = $_REQUEST['w_te_numero'];
    $w_te_zona            = $_REQUEST['w_te_zona'];
    $w_te_secao           = $_REQUEST['w_te_secao'];
    $w_reservista_numero  = $_REQUEST['w_reservista_numero'];
    $w_reservista_csm     = $_REQUEST['w_reservista_csm'];
    $w_tipo_sangue        = $_REQUEST['w_tipo_sangue'];
    $w_doador_sangue      = $_REQUEST['w_doador_sangue'];
    $w_doador_orgaos      = $_REQUEST['w_doador_orgao'];
    $w_observacoes        = $_REQUEST['w_observacoes'];
  } else {
    // Recupera os dados do colaborador a partir do c�digo da pessoa
    $RS = db_getGPColaborador::getInstanceOf($dbms,$w_cliente,$w_usuario,null,null,null,null,null,null,null,null,null,null,null,null,null,null);                     
    foreach ($RS as $row) {$RS = $row; break;}               
    if (count($RS)>0) {
      $w_ctps_numero       = f($RS,'ctps_numero');
      $w_ctps_serie        = f($RS,'ctps_serie');
      $w_ctps_emissor      = f($RS,'ctps_emissor');
      $w_ctps_emissao      = FormataDataEdicao(f($RS,'ctps_emissao_data'));
      $w_pis_pasep         = f($RS,'pis_pasep');
      $w_pispasep_numero   = f($RS,'pispasep_numero');
      $w_pispasep_cadastr  = FormataDataEdicao(f($RS,'pispasep_cadastr'));
      $w_te_numero         = f($RS,'te_numero');
      $w_te_zona           = f($RS,'te_zona');
      $w_te_secao          = f($RS,'te_secao');
      $w_reservista_numero = f($RS,'reservista_numero');
      $w_reservista_csm    = f($RS,'reservista_csm');
      $w_tipo_sangue       = f($RS,'tipo_sangue');
      $w_doador_sangue     = f($RS,'doador_sangue');
      $w_doador_orgaos     = f($RS,'doador_orgaos');
      $w_observacoes       = f($RS,'observacoes');
    } 
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  // Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara.
  ScriptOpen('JavaScript');
  CheckBranco();
  Modulo();
  FormataData();
  ValidateOpen('Validacao');
  ShowHTML('if ((theForm.w_ctps_numero.value != \'\') && (theForm.w_ctps_serie.value == \'\' || theForm.w_ctps_emissor.value == \'\' || theForm.w_ctps_emissao.value == \'\')) {');
  ShowHTML('  alert (\'Se o n�mero da CTPS for informado, todos os campos relativos a CTPS s�o obrigat�rios!\');');
  ShowHTML('  return false;');
  ShowHTML('} else { ');
  ShowHTML('  if ((theForm.w_ctps_numero.value == \'\') && (theForm.w_ctps_serie.value != \'\' || theForm.w_ctps_emissor.value != \'\' || theForm.w_ctps_emissao.value != \'\')) {');
  ShowHTML('    alert (\'Se o n�mero da CTPS n�o for informado, todos os campos relativos a CTPS devem estar em branco!\');');
  ShowHTML('    return false;');
  ShowHTML('  }');
  ShowHTML('}');
  ShowHTML('if ((theForm.w_pispasep_numero.value != \'\') && (theForm.w_pispasep_cadastr.value == \'\' )) {');
  ShowHTML('  alert (\'Se o n�mero do PIS/PASEP for informado, a data de emiss�o PIS/PASEP � obrigat�rio!\');');
  ShowHTML('  return false;');
  ShowHTML('} else { ');
  ShowHTML('  if ((theForm.w_pispasep_numero.value == \'\') && (theForm.w_pispasep_cadastr.value != \'\' )) {');
  ShowHTML('    alert (\'Se o n�mero do PIS/PASEP n�o for informado, a data de emiss�o PIS/PASEP deve estar em branco!\');');
  ShowHTML('    return false;');
  ShowHTML('  }');
  ShowHTML('}');
  ShowHTML('if ((theForm.w_te_numero.value != \'\') && (theForm.w_te_zona.value == \'\' || theForm.w_te_secao.value == \'\')) {');
  ShowHTML('  alert (\'Se o n�mero do t�tulo de eleitor for informado, todos os campos relativos ao t�tulo s�o obrigat�rios!\');');
  ShowHTML('  return false;');
  ShowHTML('} else { ');
  ShowHTML('  if ((theForm.w_te_numero.value == \'\') && (theForm.w_te_zona.value != \'\' || theForm.w_te_secao.value != \'\')) {');
  ShowHTML('    alert (\'Se o n�mero do t�tulo de eleitor n�o for informado, todos os campos relativos ao t�tulo devem estar em branco!\');');
  ShowHTML('    return false;');
  ShowHTML('  }');
  ShowHTML('}');
  Validate('w_ctps_numero','N�mero CTPS','1','','2','20','1','1');
  Validate('w_ctps_serie','S�rie CTPS','1','','2','5','1','1');
  Validate('w_ctps_emissor','Emissor CTPS','1','','3','30','1','1');
  Validate('w_ctps_emissao','Emiss�o CTPS','DATA','','10','10','','1');
  Validate('w_pispasep_numero','N�mero PIS/PASEP','1','','2','20','1','1');
  Validate('w_pispasep_cadastr','Emiss�o PIS/PASEP','DATA','','10','10','','1');
  Validate('w_te_numero','N�mero t�tulo eleitor','1','','3','20','1','1');
  Validate('w_te_zona','Zona','1','','1','3','1','1');
  Validate('w_te_secao','Se��o','1','','1','4','1','1');
  Validate('w_reservista_numero','Certificado reservista','1','','2','15','1','1');
  Validate('w_reservista_csm','CSM','1','','1','4','1','1');
  Validate('w_observacoes','Observa��es','1','','3','2000','1','1');
  Validate('w_assinatura','Assinatura eletr�nica','1','1','3','14','1','1');
  ShowHTML('  theForm.Botao.disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_ctps_numero.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,'A');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_usuario" value="'.$w_usuario.'">');
  ShowHTML('  <tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('   <table width="97%" border="0">');
  ShowHTML('     <tr valign="top">');
  ShowHTML('       <td valign="top"><b><u>N</u>�mero CTPS:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_ctps_numero" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_ctps_numero.'"></td>');
  ShowHTML('       <td valign="top"><b><u>S</u>�rie:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_ctps_serie" class="sti" SIZE="5" MAXLENGTH="5" VALUE="'.$w_ctps_serie.'"></td>');
  ShowHTML('       <td valign="top"><b><u>E</u>missor:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_ctps_emissor" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_ctps_emissor.'"></td>');
  ShowHTML('       <td valign="top"><b>E<u>m</u>iss�o CTPS:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_ctps_emissao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_ctps_emissao.'" onKeyDown="FormataData(this,event);"></td>');
  ShowHTML('     </tr>');
  ShowHTML('     <tr valign="top">');
  ShowHTML('       <td valign="top" colspan="2"><b>Optante pelo:</b><br>');
  if ($w_pis_pasep=='A') {
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_pis_pasep" value="I"> PIS <input '.$w_Disabled.' type="radio" name="w_pis_pasep" value="A" checked> PASEP');
  } else {
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_pis_pasep" value="I" checked> PIS <input '.$w_Disabled.' type="radio" name="w_pis_pasep" value="A"> PASEP');
  } 
  ShowHTML('       <td valign="top"><b>N<u>�</u>mero PIS/PASEP:</b><br><input '.$w_Disabled.' accesskey="U" type="text" name="w_pispasep_numero" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_pispasep_numero.'"></td>');
  ShowHTML('       <td valign="top"><b>Em<u>i</u>ss�o PIS/PASEP:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_pispasep_cadastr" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_pispasep_cadastr.'" onKeyDown="FormataData(this,event);"></td>');
  ShowHTML('     </tr>');
  ShowHTML('     <tr valign="top">');
  ShowHTML('       <td valign="top"><b>N�mero <u>t</u>�tulo eleitor:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_te_numero" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_te_numero.'"></td>');
  ShowHTML('       <td valign="top"><b><u>Z</u>ona:</b><br><input '.$w_Disabled.' accesskey="Z" type="text" name="w_te_zona" class="sti" SIZE="3" MAXLENGTH="3" VALUE="'.$w_te_zona.'"></td>');
  ShowHTML('       <td valign="top"><b>Se�a<u>o</u>:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_te_secao" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_te_secao.'"></td>');
  ShowHTML('     </tr>');
  ShowHTML('     <tr valign="top">');
  ShowHTML('       <td valign="top"><b>Certificado <u>r</u>eservista:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_reservista_numero" class="sti" SIZE="15" MAXLENGTH="15" VALUE="'.$w_reservista_numero.'"></td>');
  ShowHTML('       <td valign="top"><b><u>C</u>SM:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_reservista_csm" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_reservista_csm.'"></td>');
  ShowHTML('     </tr>');
  ShowHTML('     <tr valign="top">');
  ShowHTML('       <td valign="top"><b>Ti<u>p</u>agem sang��nea:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="w_tipo_sangue" class="sti" SIZE="5" MAXLENGTH="5" VALUE="'.$w_tipo_sangue.'"></td>');
  MontaRadioNS('<b>Doador de sangue?</b>',$w_doador_sangue,'w_doador_sangue');
  MontaRadioNS('<b>Doador de �rg�os?</b>',$w_doador_orgaos,'w_doador_orgaos');
  ShowHTML('     </tr>');
  ShowHTML('     <tr valign="top">');
  ShowHTML('       <td colspan="4"><b>O<U>b</U>serva��es:<br><TEXTAREA ACCESSKEY="B" '.$w_Disabled.' class="sti" name="w_observacoes" rows="5" cols=75>'.$w_observacoes.'</textarea></td>');
  ShowHTML('     </tr>');
  ShowHTML('     <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('      <tr><td align="center" colspan="4" height="1" bgcolor="#000000"></TD></TR>');
  ShowHTML('      <tr><td align="center" colspan="4">');
  ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
  ShowHTML('          </td>');
  ShowHTML('      </tr>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de contratos do colaborador
// -------------------------------------------------------------------------
function Contrato() {
  extract($GLOBALS);
  $w_chave               = $_REQUEST['w_chave'];
  $w_posto_trabalho      = $_REQUEST['w_posto_trabalho'];
  $w_modalidade_contrato = $_REQUEST['w_modalidade_contrato'];
  $w_unidade_lotacao     = $_REQUEST['w_unidade_lotacao'];
  $w_unidade_exercicio   = $_REQUEST['w_unidade_exercicio'];
  $w_localizacao         = $_REQUEST['w_localizacao'];
  $w_matricula           = $_REQUEST['w_matricula'];
  $w_dt_ini              = $_REQUEST['w_dt_ini'];
  $w_dt_fim              = $_REQUEST['w_dt_fim'];
  $w_ativo               = $_REQUEST['w_ativo'];
  $w_sq_tipo_vinculo     = $_REQUEST['w_sq_tipo_vinculo'];
  $w_username_pessoa     = $_REQUEST['w_username_pessoa'];
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem dos contratos do colaborador</TITLE>');
  if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="300; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">');
  Estrutura_CSS($w_cliente);
  if ($O=='L') {
    $RS = db_getGPContrato::getInstanceOf($dbms,$w_cliente,null,$w_usuario,null,null,null,null,null,null,null,null,null,null);
    $RS = SortArray($RS,'fim','asc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    $RS = db_getGPContrato::getInstanceOf($dbms,$w_cliente,$w_chave,$w_usuario,null,null,null,null,null,null,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    if (count($RS)>0) {
      $w_chave               = f($RS,'chave');
      $w_posto_trabalho      = f($RS,'sq_posto_trabalho');
      $w_modalidade_contrato = f($RS,'sq_modalidade_contrato');
      $w_unidade_lotacao     = f($RS,'sq_unidade_lotacao');
      $w_unidade_exercicio   = f($RS,'sq_unidade_exercicio');
      $w_localizacao         = f($RS,'sq_localizacao');
      $w_matricula           = f($RS,'matricula');
      $w_dt_ini              = FormataDataEdicao(f($RS,'inicio'));
      $w_dt_fim              = FormataDataEdicao(f($RS,'fim'));
      $w_sq_tipo_vinculo     = f($RS,'sq_tipo_vinculo');
    } 
    $w_erro=ValidaColaborador($w_cliente,$w_usuario,$w_chave,null);
  } 
  if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    formatadata();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_posto_trabalho','Cargo','SELECT',1,1,18,'','0123456789');
      Validate('w_modalidade_contrato','Modalidade de contrata��o','SELECT',1,1,18,'','0123456789');
      Validate('w_unidade_lotacao','Unidade de lota��o','SELECT',1,1,18,'','0123456789');
      Validate('w_unidade_exercicio','Unidade de exerc�cio','SELECT',1,1,18,'','0123456789');
      Validate('w_localizacao','Localiza��o','SELECT',1,1,18,'','0123456789');
      Validate('w_sq_tipo_vinculo','V�nculo com a organiza��o','SELECT',1,1,10,'','1');
      Validate('w_matricula','Matr�cula','1','1','5','18','1','1');
      Validate('w_dt_ini','In�cio da vig�ncia','DATA','1','10','10','','0123456789/');
      if ($O=='A' && Nvl($w_dt_fim,'')>'') {
        Validate('w_dt_fim','Fim da vig�ncia','DATA','1','10','10','','0123456789/');
      } elseif ($O=='I') {
        Validate('w_dt_fim','Fim da vig�ncia','DATA','','10','10','','0123456789/');
      } 
        if (!($O=='A' && Nvl($w_dt_fim,'')=='')) {
          CompData('w_dt_ini','In�cio da vig�ncia','<=','w_dt_fim','Fim da vig�ncia');
      } 
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    } elseif ($O=='E' && $w_erro=='') {
      Validate('w_dt_fim','Fim da vig�ncia','DATA','','10','10','','0123456789/');
      CompData('w_dt_fim','Fim da vig�ncia','>=','w_dt_ini','In�cio da vig�ncia');
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
      ShowHTML('  if (confirm(\'Confirma o encerramento deste contrato?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=document.Form.'.$w_troca.'.focus();');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad=document.Form.w_posto_trabalho.focus();');
  } else if ($O=='E'){
    BodyOpen('onLoad=document.Form.w_dt_fim.focus();');
  } else {
    BodyOpen('onLoad=document.focus();');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&w_usuario='.$w_usuario.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    //ShowHTML '    <td><a accesskey=''E'' class=''ss'' href=''' & w_dir & w_Pagina & par & '&w_usuario=' & w_usuario & '&R=' & w_Pagina & par & '&O=E&P1=' & P1 & '&P2=' & P2 & '&P3=' & P3 & '&P4=' & P4 & '&TP=' & TP & '&SG=' & SG & '''><u>E</u>ncerrar</a>&nbsp;'
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Matr�cula','matricula').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome_resumido').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Modalidade','nm_modalidade_contrato').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Exerc�cio','local').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ramal','ramal').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('In�cio','inicio').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Fim','fim').'</td>');
    ShowHTML('          <td><b> Opera��es </td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan="8" align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach($RS1 as $row){
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center">'.Nvl(f($row,'matricula'),'---').'</td>');
        ShowHTML('        <td align="left">'.ExibeColaborador('',$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'nome_resumido')).'</td>');
        ShowHTML('        <td align="left">'.f($row,'nm_modalidade_contrato').'</td>');
        ShowHTML('        <td align="left">'.ExibeUnidade('../',$w_cliente,f($row,'local'),f($row,'sq_unidade_exercicio'),$TP).'</td>');
        ShowHTML('        <td align="center">'.Nvl(f($row,'ramal'),'---').'</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'inicio')).'</td>');
        ShowHTML('        <td align="center">'.Nvl(FormataDataEdicao(f($row,'fim')),'---').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R= '.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Alterar registro">Alterar</A>&nbsp');
        if (Nvl(f($row,'fim'),'')=='') {
          ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R= '.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Encerrar contrato">Encerrar</A>&nbsp');
        } 
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,$RS->PageCount,$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,$RS->PageCount,$P3,$P4,count($RS));
    } 
    ShowHTML('</tr>');
    //Aqui come�a a manipula��o de registros
  } elseif (!(strpos('IAV',$O)===false)) {
    if (!(strpos('V',$O)===false)) {
      $w_Disabled =' DISABLED ';
    } elseif (!(strpos('IA',$O)===false)) {
      $w_ativo = 0;
      $RS = db_getGPContrato::getInstanceOf($dbms,$w_cliente,null,$w_usuario,null,null,null,null,null,null,null,null,$w_chave,null);
      if (count($RS)>0) {
        foreach ($RS as $row) {
          if ((Nvl(f($row,'fim'),'')=='') && ($w_chave!=f($row,'chave'))) $w_ativo+=1;
        } 
      } 
      if ($w_ativo>0 && $O=='I' && $w_troca=='') {
        ScriptOpen('JavaScript');
        ShowHTML('alert(\'J� existe contrato ativo para este colaborador, n�o sendo poss�vel inclus�o de outro contrato ativo!\');');
        ScriptClose();
      } 
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_usuario" value="'.$w_usuario.'">');
    ShowHTML('<INPUT type="hidden" name="w_ativo" value="'.$w_ativo.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0"><tr>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('        <td colspan="3" valign="top"><table border="0" width="100%" cellpadding=0 cellspacing=0><tr>');
    SelecaoCargo('<u>C</u>argo:','C','Selecione o cargo.',$w_posto_trabalho,null,'w_posto_trabalho',null,null);
    SelecaoModalidade('M<u>o</u>dalidade de contrata��o:','O',null,$w_modalidade_contrato,null,'w_modalidade_contrato',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'&SG='.$SG.'&O='.$O.'\'; document.Form.w_troca.value=\'w_modalidade_contrato\'; document.Form.submit();"');
    if (Nvl($w_modalidade_contrato,'')>'') {
      $RS = db_getGPModalidade::getInstanceOf($dbms,$w_cliente,$w_modalidade_contrato,null,null,null,null,null);
      foreach ($RS as $row){
        if (f($row,'username')=='P') {
          $w_username_pessoa = 'S';
        }
      } 
    }
    ShowHTML('        </table></td></tr>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('        <td colspan="3" valign="top"><table border="0" width="100%" cellpadding=0 cellspacing=0><tr>');
    SelecaoUnidade('Unidade de <U>l</U>ota��o:','L',null,$w_unidade_lotacao,null,'w_unidade_lotacao',null,null);
    ShowHTML('        </table></td></tr>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('        <td colspan="3" valign="top"><table border="0" width="100%" cellpadding=0 cellspacing=0><tr>');
    SelecaoUnidade('Unidade de <U>e</U>xerc�cio:','E',null,$w_unidade_exercicio,null,'w_unidade_exercicio',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'&SG='.$SG.'&O='.$O.'&w_usuario='.$w_usuario.'\'; document.Form.w_troca.value=\'w_localizacao\'; document.Form.submit();"');
    ShowHTML('        </table></td></tr>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('        <td colspan="3" valign="top"><table border="0" width="100%" cellpadding=0 cellspacing=0><tr>');
    SelecaoLocalizacao('Locali<u>z</u>a��o:','Z',null,$w_localizacao,Nvl($w_unidade_exercicio,0),'w_localizacao',null);
    ShowHTML('        </table></td></tr>');
    if (Nvl($w_dt_fim,'')>'') {
      ShowHTML('<INPUT type="hidden" name="w_sq_tipo_vinculo" value="'.$w_sq_tipo_vinculo.'">');
    } else {
      ShowHTML('        <td colspan="3" valign="top"><table border="0" width="100%" cellpadding=0 cellspacing=0><tr>');
      SelecaoVinculo('<u>T</u>ipo de v�nculo:','T',null,$w_sq_tipo_vinculo,null,'w_sq_tipo_vinculo','S','F�sica','S');
      ShowHTML('        </table></td></tr>');
    } 
    ShowHTML('        <tr valign="top">');
    ShowHTML('        <td colspan="3" valign="top"><table border="0" width="100%" cellpadding=0 cellspacing=0>');
    ShowHTML('          <tr><td valign="top"><b><u>M</u>atr�cula:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_matricula" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_matricula.'"></td>');
    ShowHTML('              <td><b><u>I</u>n�cio da vig�ncia:</b><br><input accesskey="I" type="text" name="w_dt_ini" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_dt_ini.'" onKeyDown="FormataData(this,event);">');
    if (!($O=='A' && Nvl($w_dt_fim,'')=='')) {
      ShowHTML('              <td><b><u>F</u>im da vig�ncia:</b><br><input accesskey="F" type="text" name="w_dt_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_dt_fim.'" onKeyDown="FormataData(this,event);">');
    } 
    ShowHTML('        </table></td></tr>');
    if ($w_username_pessoa=='S') {
      ShowHTML('        <tr valign="top">');
      ShowHTML('        <td colspan="3" valign="top"><input type="checkbox" name="w_username_pessoa" value="S"><b>Criar username para este colaborador?</b>');
    } 
    ShowHTML('      <tr><td colspan=5><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan=5><hr>');
    if ($O=='I') {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Incluir">');
    } else {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Atualizar">');
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif (!(strpos('E',$O)===false)) {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_usuario" value="'.$w_usuario.'">');
    ShowHTML('<INPUT type="hidden" name="w_dt_ini" value="'.$w_dt_ini.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center"><div align="justify">para efetivar o encerramento do contrato, informe os dados abaixo e clique no bot�o <i>Encerrar contrato</i>. ATEN��O: a reativa��o de um contrato s� � poss�vel se n�o houve nenhum outro contrato ativo.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0"><tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><u>F</u>im da vig�ncia:</b><br><input accesskey="F" type="text" name="w_dt_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_dt_fim.'" onKeyDown="FormataData(this,event);"></td></tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><input type="checkbox" name="w_envio_email" value="S"><b>Enviar e-mail comunicando o encerramento do contrato.</b></td>');
    ShowHTML('      <tr valign="top"><td><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('          <input class="stb" type="submit" name="Botao" value="Encerrar contrato">');
    ShowHTML('          <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('        </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('  </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 
// =========================================================================
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  Cabecalho();
  ShowHTML('</HEAD>');  
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=document.focus();');
  AbreSessao();
  switch ($SG) {
    case 'COINICIAL':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putGPColaborador::getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_sq_pessoa'],null,null,null,
            null,null,null,null,null,null,null,null,null,null,null,null,null);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=P&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'CODOCUM':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putGPColaborador::getInstanceOf($dbms,$O,$w_cliente,$w_usuario,$_REQUEST['w_ctps_numero'],$_REQUEST['w_ctps_serie'],$_REQUEST['w_ctps_emissor'],
        $_REQUEST['w_ctps_emissao'],$_REQUEST['w_pis_pasep'],$_REQUEST['w_pispasep_numero'],$_REQUEST['w_pispasep_cadastr'],
        $_REQUEST['w_te_numero'],$_REQUEST['w_te_zona'],$_REQUEST['w_te_secao'],$_REQUEST['w_reservista_numero'],
        $_REQUEST['w_reservista_csm'],$_REQUEST['w_tipo_sangue'],$_REQUEST['w_doador_sangue'],$_REQUEST['w_doador_orgaos'],
        $_REQUEST['w_observacoes']);                               
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$montaURL_JS($w_dir,R.'&O=P&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'COCONTR':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if (Nvl($_REQUEST['w_ativo'],0)>0 && Nvl($_REQUEST['w_dt_fim'],'')=='') {
          ScriptOpen('JavaScript');
          ShowHTML('alert(\'J� existe contrato ativo para este colaborador, n�o sendo poss�vel uma nova inclus�o\');');
          ShowHTML('history.back(1);');
          ScriptClose();
          exit;
        } else {
          if ($O=='E') {
            $w_erro = ValidaColaborador($w_cliente,$w_usuario,$_REQUEST['w_chave'],$_REQUEST['w_dt_fim']);
            if ($w_erro>'') {
              ShowHTML('<HR>');
              ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
              ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
              ShowHTML('<font color="#BC3131"><b>ATEN��O:</b> Foram identificados os erros listados abaixo, n�o sendo poss�vel a conclus�o da opera��o.</font>');
              ShowHTML('<UL>'.$w_erro.'</UL>');
              ShowHTML('</td></tr></table>');
              ShowHTML('<center><B>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar � tela anterior</b></center>');
              Rodape();  
           } 
          } else {
            $RS = db_getGPContrato::getInstanceOf($dbms,$w_cliente,null,$w_usuario,null,null,null,null,null,null,$_REQUEST['w_dt_ini'],$_REQUEST['w_dt_fim'],null,null);            
            if(count($RS)>0) {
              ScriptOpen('JavaScript');
              ShowHTML('alert(\'J� existe contrato cadastrado para o per�odo informado!\');');
              ShowHTML('history.back(1);');
              ScriptClose();
              exit;
            }
          }
          dml_putGPContrato::getInstanceOf($dbms,$O,
          $w_cliente,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_posto_trabalho'],$_REQUEST['w_modalidade_contrato'],
          $_REQUEST['w_unidade_lotacao'],$_REQUEST['w_unidade_exercicio'],$_REQUEST['w_localizacao'],$_REQUEST['w_matricula'],
          $_REQUEST['w_dt_ini'],$_REQUEST['w_dt_fim'],$_REQUEST['w_sq_tipo_vinculo']);                
          if (!(strpos('I',$O)===false)) {
            $RS = db_getGPModalidade::getInstanceOf($dbms,$w_cliente,$_REQUEST['w_modalidade_contrato'],null,null,null,null,null);
            if ((Nvl(f($RS,'username'),'')=='S') || (Nvl(f($RS,'username'),'')=='P' && $_REQUEST['w_username_pessoa']=='S')) {
              $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$w_usuario,null,null);
              dml_putSiwUsuario::getInstanceOf($dbms,'I',$w_usuario,$w_cliente,f($RS,'nome'),f($RS,'nome_resumido'),
                f($RS,'sq_tipo_vinculo'),'F�sica',$_REQUEST['w_unidade_lotacao'],$_REQUEST['w_localizacao'],
                f($RS,'cpf'),f($RS,'email'),null,null);
              dml_putSiwUsuario::getInstanceOf($dbms,'T',$w_usuario,null,null,null,
              null,null,null,null,null,null,null,null);
            } 
          } 
        } 
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    default:
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Bloco de dados n�o encontrado: '.$SG.'\');');
      ShowHTML('  history.back(1);');
      ScriptClose();
    break;
  } 
} 
// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------

function Main() {
  extract($GLOBALS);
  switch ($par)  {
    case 'INICIAL':           Inicial();          break;
    case 'DOCUMENTACAO':      Documentacao();     break;
    case 'CONTRATO':          Contrato();         break;
    case 'GRAVA':             Grava();            break;
  default:
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=document.focus();');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Rodape();
  break;
  } 
} 
?>
