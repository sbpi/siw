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
include_once($w_dir_volta.'classes/sp/db_getAfastamento.php');
include_once($w_dir_volta.'classes/sp/db_getViagemBenef.php');
include_once($w_dir_volta.'classes/sp/db_getGPTipoAfast.php');
include_once($w_dir_volta.'classes/sp/db_getGPColaborador.php');
include_once($w_dir_volta.'classes/sp/db_getGPFolhaPontoDiario.php');
include_once($w_dir_volta.'classes/sp/db_getGPContrato.php');
include_once($w_dir_volta.'classes/sp/db_getGpPensionista.php');
include_once($w_dir_volta.'classes/sp/db_getGpFamiliares.php');
include_once($w_dir_volta.'classes/sp/db_getBankData.php');
include_once($w_dir_volta.'classes/sp/db_getBenef.php');
include_once($w_dir_volta.'classes/sp/db_getGPParametro.php');
include_once($w_dir_volta.'classes/sp/db_getPersonList.php');
include_once($w_dir_volta.'classes/sp/db_getGpDesempenho.php');
include_once($w_dir_volta.'classes/sp/db_getGpAlteracaoSalario.php');
include_once($w_dir_volta.'classes/sp/db_getCV.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putGPColaborador.php');
include_once($w_dir_volta.'classes/sp/dml_putGPContrato.php');
include_once($w_dir_volta.'classes/sp/dml_putSiwUsuario.php');
include_once($w_dir_volta.'classes/sp/dml_putGpDesempenho.php');
include_once($w_dir_volta.'classes/sp/dml_putGpAlteracaoSalario.php');
include_once($w_dir_volta.'classes/sp/dml_PutGPPensionista.php');
include_once($w_dir_volta.'classes/sp/dml_putGpFamiliares.php');
include_once($w_dir_volta.'funcoes/exibeColaborador.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoColaborador.php');
include_once($w_dir_volta.'funcoes/selecaoModalidade.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoCargo.php');
include_once($w_dir_volta.'funcoes/selecaoLocalizacao.php');
include_once($w_dir_volta.'funcoes/selecaoVinculo.php');
include_once($w_dir_volta.'funcoes/selecaoSexo.php');
include_once($w_dir_volta.'funcoes/selecaoParentesco.php');
include_once($w_dir_volta.'funcoes/selecaoAgencia.php');
include_once($w_dir_volta.'funcoes/selecaoBanco.php');
include_once($w_dir_volta.'funcoes/selecaoTipoPensao.php');
include_once($w_dir_volta.'funcoes/selecaoPessoaOrigem.php');
include_once('visualFicha.php');
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
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

// Carrega vari�veis locais com os dados dos par�metros recebidos
$par            = upper($_REQUEST['par']);
$P1             = Nvl($_REQUEST['P1'],0);
$P2             = Nvl($_REQUEST['P2'],0);
$P3             = Nvl($_REQUEST['P3'],1);
$P4             = Nvl($_REQUEST['P4'],$conPageSize);
$TP             = $_REQUEST['TP'];
$SG             = upper($_REQUEST['SG']);
$R              = lower($_REQUEST['R']);
$O              = upper($_REQUEST['O']);
$p_ordena       = lower($_REQUEST['p_ordena']);
$w_troca        = lower($_REQUEST['w_troca']);
$w_assinatura   = upper($_REQUEST['w_assinatura']);
$w_pagina       = 'colaborador.php?par=';
$w_dir          = 'mod_rh/';
$w_dir_volta    = '../';
$w_Disabled     = 'ENABLED';

if ($O=='') {
  if ($par=='INICIAL') {
    $O='L';
  } else {
    $O='P';
  }
}

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
$w_cliente     = RetornaCliente();
$w_usuario     = RetornaUsuario();
$w_menu        = RetornaMenu($w_cliente,$SG);

// Recupera os par�metros do m�dulo de pessoal
$sql = new db_getGPParametro; $RS_Parametro = $sql->getInstanceOf($dbms,$w_cliente,null,null);
foreach ($RS_Parametro as $row) {$RS_Parametro = $row; break;}
if (nvl(f($RS_Parametro,'vinculacao_contrato'),'')!='') $w_exige_cc = true; else $w_exige_cc = false;


Main();

FechaSessao($dbms);
exit;

// =========================================================================
// Rotina da tabela de colaborador
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  Global $w_Disabled;
  $p_contrato_colaborador   = upper($_REQUEST['p_contrato_colaborador']);
  $p_modalidade_contrato    = upper($_REQUEST['p_modalidade_contrato']);
  $p_unidade_lotacao        = upper($_REQUEST['p_unidade_lotacao']);
  $p_filhos_lotacao         = upper($_REQUEST['p_filhos_lotacao']);
  $p_ativo                  = nvl($_REQUEST['p_ativo'],'S');
  $p_unidade_exercicio      = upper($_REQUEST['p_unidade_exercicio']);
  $p_filhos_exercicio       = upper($_REQUEST['p_filhos_exercicio']);
  $p_afastamento            = explodeArray($_REQUEST['p_afastamento']);
  $p_dt_ini                 = upper($_REQUEST['p_dt_ini']);
  $p_dt_fim                 = upper($_REQUEST['p_dt_fim']);
  $p_ferias                 = upper($_REQUEST['p_ferias']);
  $p_viagem                 = upper($_REQUEST['p_viagem']);
  $w_sq_pessoa              = upper($_REQUEST['w_sq_pessoa']);
  $w_nome                   = upper($_REQUEST['w_nome']);
  $w_cpf                    = upper($_REQUEST['w_cpf']);
  $w_botao                  = upper($_REQUEST['w_botao']);
  if ($O=='L') {
    $sql = new db_getGPColaborador; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_contrato_colaborador,null,$p_ativo,$p_modalidade_contrato,$p_unidade_lotacao,$p_filhos_lotacao,$p_unidade_exercicio,$p_filhos_exercicio,$p_afastamento,$p_dt_ini,$p_dt_fim,$p_ferias,$p_viagem,null,'COLABORADOR');
    if ($p_ordena>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome_resumido_ind','asc');
    } else {
      $RS = SortArray($RS,'nome_resumido_ind','asc');
    }
  } elseif ($O=='E') {
    $sql = new db_getCV; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_sq_pessoa,'CVIDENT','DADOS');
    foreach($RS as $row){$RS=$row; break;}
    $sql = new db_getGPContrato; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,null,$w_sq_pessoa,null,null,null,null,null,null,null,null,null,null);
    foreach($RS1 as $row){$RS1=$row; break;}
    $w_erro = ValidaColaborador($w_cliente,$w_sq_pessoa,f($RS1,'chave'),null);
  }
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ScriptOpen('Javascript');
  modulo();
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataCPF();
  ValidateOpen('Validacao');
  if (!(strpos('P',$O)===false)) {
    ShowHTML('  var cont = 0;');
    ShowHTML('  for (i=0;i<theForm["p_afastamento[]"].length;i++) {');
    ShowHTML('    if (theForm["p_afastamento[]"][i].checked) {');
    ShowHTML('      cont = cont+1;');
    ShowHTML('    }');
    ShowHTML('  }');
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
    BodyOpen('onLoad=this.focus();');
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
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Matr�cula','matricula').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome_resumido_ind').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Modalidade','nm_modalidade_contrato').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Posto','nm_posto_completo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Exerc�cio','nm_exercicio').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ramal','ramal').'</td>');
    ShowHTML('          <td class="remover"><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $RS1 = array_slice($RS,(($P3-1)*$P4),$P4);
      foreach($RS1 as $row){
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center">'.Nvl(f($row,'matricula'),'---').'</td>');
        ShowHTML('        <td align="left">'.ExibeColaborador($w_dir,$w_cliente,f($row,'chave'),$TP,f($row,'nome_resumido')).'</td>');
        ShowHTML('        <td align="left">'.f($row,'nm_modalidade_contrato').'</td>');
        ShowHTML('        <td align="left">'.f($row,'nm_posto_completo').'</td>');
        ShowHTML('        <td align="left">'.ExibeUnidade('../',$w_cliente,f($row,'local'),f($row,'sq_unidade_exercicio'),$TP).'</td>');
        ShowHTML('        <td align="center">'.Nvl(f($row,'ramal'),'---').'</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="menu.php?par=ExibeDocs&O=A&w_usuario='.f($row,'chave').'&R='.$w_pagina.$par.'&SG='.$SG.'&TP='.$TP.'&w_documento='.f($row,'nome_resumido').MontaFiltro('GET').'" title="Altera as informa��es cadastrais do colaborador" TARGET="menu">AL</a>&nbsp;');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_cliente='.$w_cliente.'&w_sq_pessoa='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclui o colaborador do banco de dados">EX</A>&nbsp');
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
    ShowHTML('      <tr>');
    ShowHTML('          <td><b>Colaboradores:</b><br>');
    if (Nvl($p_ativo,'S')=='S') {
      ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="S" checked> Apenas ativos<br><input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="N"> Apenas inativos<br><input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value=""> Tanto faz');
    } elseif ($p_ativo=='N') {
      ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="S"> Apenas ativos<br><input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="N" checked> Apenas inativos<br><input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value=""> Tanto faz');
    } else {
      ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="S"> Apenas ativos<br><input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="N"> Apenas inativos<br><input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="" checked> Tanto faz');
    } 
    ShowHTML('      <tr><td><b>Afastado por:</b><br>');
    $sql = new db_getGPTipoAfast; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,null,null,null,'S',null,null);
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
    ShowHTML('      <tr><td><b><u>P</u>er�odo de busca:</b><br> De: <input accesskey="P" type="text" name="p_dt_ini" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_dt_ini.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_dt_ini').' a <input accesskey="P" type="text" name="p_dt_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_dt_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_dt_fim').'</td>');
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
  } elseif ($O=='I') {
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
      $sql = new db_getPersonList; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PESSOA',$w_nome,null,null,null);
      $RS = SortArray($RS,'nome','asc');
      ShowHTML('<tr><td colspan=3>');
      ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
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
// Rotina dos dados de desempenho do colaborador
// -------------------------------------------------------------------------
function Remuneracao() {
  extract($GLOBALS);
  $w_chave               = $_REQUEST['w_chave'];
  $w_chave_aux           = $_REQUEST['w_chave_aux'];
  //exibeVariaveis();
  //Recupera os dados do contrato
  $sql = new db_getGPContrato; $RSContrato = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,null,null,null,null,null,null,null);
  if ($w_troca> '' && $O!='E') {
    $w_data_alteracao = $_REQUEST['w_data_alteracao'];
    $w_novo_valor     = $_REQUEST['w_novo_valor'];
    $w_funcao         = $_REQUEST['w_funcao'];
    $w_motivo         = $_REQUEST['w_motivo'];
  }elseif ($O=='L') {
    $sql = new db_getGpAlteracaoSalario; $RS = $sql->getInstanceOf($dbms, $w_chave, null, null, null, null);
    $RS = SortArray($RS,'data_alteracao','desc');
  } elseif (!(strpos('AEV',$O)===false) || $w_troca > '') {
    $sql = new db_getGpAlteracaoSalario; $RS = $sql->getInstanceOf($dbms, $w_chave, $w_chave_aux, null, null, null);
    foreach ($RS as $row) {
      $w_data_alteracao       = formataDataEdicao(f($row,'data_alteracao'));
      $w_novo_valor           = formatNumber(f($row,'novo_valor'),2);
      $w_funcao               = f($row,'funcao');
      $w_motivo               = f($row,'motivo');
      if(f($row,'ultimo')!="S"){
        $w_Disabled = 'disabled="disabled"';
      }else{
        $w_Disabled = '';
      }
    }
  }

  Cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem dos percentuais de desempenho do colaborador</TITLE>');
  if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">');
  Estrutura_CSS($w_cliente);

  if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    formatadata();
    FormataValor();
    SaltaCampo();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_data_alteracao','Data de altera��o','DATA','1','10','10','','0123456789/');
      Validate('w_novo_valor','Novo valor da remunera��o','VALOR','1',4,18,'','0123456789.,');
      Validate('w_funcao','Fun��o','1','1','','90','1','1');
      Validate('w_motivo','Motivo da altera��o','TEXTAREA','1','','255','1','1');
      /*CompValor('w_percentual','Percentual','>=',0,'zero');
       CompValor('w_percentual','Percentual','<=',100,'100%');*/
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    } elseif ($O=='E' && $w_erro=='') {
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclus�o deste registro?\')) ');
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
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad=\'document.Form.w_data_alteracao.focus();\'');
  } else if ($O=='E'){
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus();\'');
  } else {
    BodyOpen('onLoad=\'this.focus();\'');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table width="100%" bgcolor="FAEBD7">');
  ShowHTML('  <tr>');
  ShowHTML('    <td>');
  ShowHTML('<table border=1 width="100%">');
  foreach ($RSContrato as $row) {$RSContrato = $row; break;}
  ShowHTML('<table border=1 width="100%"><td><table width="100%">');
  ShowHTML('      <tr><td colspan=2><b><font size="2">'.f($RSContrato,'nome').'</font></b><hr noshade size="1"/>');
  ShowHTML('      <tr valign="top"><td>Matr�cula: <b>'.f($RSContrato,'matricula').'</b>');
  ShowHTML('      <td>In�cio da vig�ncia do contrato: <b>'.formataDataEdicao(f($RSContrato,'inicio')).'</b></td>');
  ShowHTML('</table>');
  ShowHTML('</table>');
  ShowHTML('    </td>');
  ShowHTML('  </tr>');
  ShowHTML('</table>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&w_chave='.$w_chave.'&w_usuario='.$w_usuario.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    //ShowHTML '    <td><a accesskey=''E'' class=''ss'' href=''' & w_dir & w_Pagina & par & '&w_usuario=' & w_usuario & '&R=' & w_Pagina & par & '&O=E&P1=' & P1 & '&P2=' & P2 & '&P3=' & P3 & '&P4=' & P4 & '&TP=' & TP & '&SG=' & SG & '''><u>E</u>ncerrar</a>&nbsp;'
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td width="15%"><b>'.LinkOrdena('Data de altera��o','data_alteracao').'</td>');
    ShowHTML('          <td width="30%"><b>'.LinkOrdena('Fun��o','funcao').'</td>');
    ShowHTML('          <td width="15%"><b>'.LinkOrdena('Valor da remunera��o','novo_valor').'</td>');
    ShowHTML('          <td class="remover" width="5%"><b> Opera��es </td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan="8" align="center"><b>N�o foram encontrados registross.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach($RS1 as $row){
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center" align="left">'.formataDataEdicao(f($row,'data_alteracao')).'</td>');
        ShowHTML('        <td align="left" align="left">'.f($row,'funcao').'</td>');
        ShowHTML('        <td align="center" align="left">'.formatNumber(f($row,'novo_valor'),2).'</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&w_chave_aux='.f($row,'sq_alteracao_salario').'&w_ano='.f($row,'ano').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Alterar registro">AL</A>&nbsp');
        if(f($row,'ultimo')=="S"){
          ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&w_chave_aux='.f($row,'sq_alteracao_salario').'&O=E&w_ano='.f($row,'ano').'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.'COREM'.MontaFiltro('GET').'" Title="Encerrar contrato">EX</A>&nbsp');
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

    //die();
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'COREM',$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    //ShowHTML('<INPUT type="hidden" name="w_ano" value="'.$w_ano.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0"><tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td width="43%" valign="top"><b><u>D</u>ata da altera��o:</b><br><input '.$w_Disabled.' title="Data da altera��o salarial" accesskey="A" type="text" name="w_data_alteracao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data_alteracao.'" onKeyDown="FormataData(this,event);"></td>');
    ShowHTML('        <td><b><u>N</u>ovo valor:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_novo_valor" class="STI" SIZE="10" MAXLENGTH="10" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" VALUE="'.$w_novo_valor.'"></td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td colspan="2" valign="top"><b><u>F</u>un��o:</b><br><input title="Informe se a altera��o de fun��o causou a altera��o salarial.." accesskey="F" type="text" name="w_funcao" class="sti" SIZE="90" MAXLENGTH="90" VALUE="'.$w_funcao.'"></td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td colspan="2" valign="top"><b><u>M</u>otivo:</b><br><textarea title="Motivo da altera��o salarial." accesskey="M" type="text" name="w_motivo" class="sti" cols="51" rows="5" MAXLENGTH="255">'.$w_motivo.'</textarea></td>');
    ShowHTML('      </tr>');

    ShowHTML('      <tr><td colspan="2"><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td colspan="2" align="center"><hr>');
    if ($O=='I') {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Incluir">');
    } else {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Atualizar">');
    }
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_usuario='.$w_usuario.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif (!(strpos('E',$O)===false)) {
    $w_Disabled =' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'COREM',$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    //ShowHTML('<INPUT type="hidden" name="w_ano" value="'.$w_ano.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0"><tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td width="43%" valign="top"><b><u>D</u>ata da altera��o:</b><br><input '.$w_Disabled.' title="Data da altera��o salarial" accesskey="A" type="text" name="w_data_alteracao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data_alteracao.'" onKeyDown="FormataData(this,event);"></td>');
    ShowHTML('        <td><b><u>N</u>ovo valor:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_novo_valor" class="STI" SIZE="10" MAXLENGTH="10" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" VALUE="'.$w_novo_valor.'"></td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td colspan="2" valign="top"><b><u>F</u>un��o:</b><br><input '.$w_Disabled.' title="Informe se a altera��o de fun��o causou a altera��o salarial.." accesskey="F" type="text" name="w_funcao" class="sti" SIZE="90" MAXLENGTH="90" VALUE="'.$w_funcao.'"></td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td colspan="2" valign="top"><b><u>M</u>otivo:</b><br><textarea '.$w_Disabled.' title="Motivo da altera��o salarial." accesskey="M" type="text" name="w_motivo" class="sti" cols="51" rows="5" MAXLENGTH="255">'.$w_motivo.'</textarea></td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr valign="top"><td colspan="2"><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td colspan="2" align="center"><hr>');
    ShowHTML('          <input class="stb" type="submit" name="Botao" value="Excluir">');
    ShowHTML('          <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_usuario='.$w_usuario.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
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
// Rotina dos dados de desempenho do colaborador
// -------------------------------------------------------------------------
function Desempenho() {
  extract($GLOBALS);
  $w_chave               = $_REQUEST['w_chave'];

  //Recupera os dados do contrato
  $sql = new db_getGPContrato; $RSContrato = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,null,null,null,null,null,null,null);
  foreach ($RSContrato as $row) {$RSContrato = $row; break;}
  if ($w_troca> '' && $O!='E') {
    $w_ano                 = $_REQUEST['w_ano'];
    $w_percentual          = $_REQUEST['w_percentual'];
  }elseif ($O=='L') {
    $sql = new db_getGpDesempenho; $RS = $sql->getInstanceOf($dbms, $w_chave,null);
    $RS = SortArray($RS,'ano','desc');
  } elseif (!(strpos('AEV',$O)===false) || $w_troca>'') {
    $sql = new db_getGpDesempenho; $RS = $sql->getInstanceOf($dbms, $w_chave, $w_ano);
    foreach ($RS as $row) {
      $w_ano        = f($row,'ano');
      $w_percentual = f($row,'percentual');
    }
  }

  Cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem dos percentuais de desempenho do colaborador</TITLE>');
  if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">');
  Estrutura_CSS($w_cliente);

  if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    formatadata();
    FormataValor();
    SaltaCampo();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_ano','Ano','1','1','4','4','','0123456789/');
      CompValor('w_ano','Ano','>=',date('Y',f($RSContrato,'inicio')),'ano inicial da vig�ncia');
      Validate('w_percentual','Percentual','1','1','1','6','','0123456789,');
      CompValor('w_percentual','Percentual','>=',0,'zero');
      CompValor('w_percentual','Percentual','<=',100,'100%');
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    } elseif ($O=='E' && $w_erro=='') {
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclus�o deste registro?\')) ');
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
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad=\'document.Form.w_ano.focus();\'');
  } else if ($O=='E'){
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus();\'');
  } else {
    BodyOpen('onLoad=\'this.focus();\'');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table width="100%" bgcolor="FAEBD7">');
  ShowHTML('  <tr>');
  ShowHTML('    <td>');
  ShowHTML('<table border=1 width="100%">');
  ShowHTML('<table border=1 width="100%"><td><table width="100%">');
  ShowHTML('      <tr><td colspan=2><b><font size="2">'.f($RSContrato,'nome').'</font></b><hr noshade size="1"/>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>Matr�cula: <b>'.f($RSContrato,'matricula').'</b>');
  ShowHTML('        <td>In�cio da vig�ncia do contrato: <b>'.formataDataEdicao(f($RSContrato,'inicio')).'</b></td>');
  ShowHTML('</table>');
  ShowHTML('</table>');
  ShowHTML('    </td>');
  ShowHTML('  </tr>');
  ShowHTML('</table>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&w_chave='.$w_chave.'&w_usuario='.$w_usuario.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td width="10%"><b>'.LinkOrdena('Ano','ano').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Percentual de Desempenho','percentual').'</td>');
    ShowHTML('          <td class="remover" width="20%"><b> Opera��es </td>');
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
        ShowHTML('        <td align="center" align="left">'.f($row,'ano').'</td>');
        ShowHTML('        <td align="center" align="left">'.formatNumber(f($row,'percentual'),2).'</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&w_ano='.f($row,'ano').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Alterar registro">AL</A>&nbsp');
        if (Nvl(f($row,'fim'),'')=='') {
          ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&O=E&w_ano='.f($row,'ano').'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.'CODES'.MontaFiltro('GET').'" Title="Encerrar contrato">EX</A>&nbsp');
        }
        //ShowHTML('          <A class="hl" onClick=javascript:window.open("location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'\',\'Geo\',\'toolbar=no,resizable=yes,width=780,height=550,top=20,left=10,scrollbars=yes\')" title="Sele��o de coordenadas geogr�ficas."></A>&nbsp'));
        //ShowHTML('          <a class="SS" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS($w_dir,$w_pagina.'Desempenho'.'&O=L&w_chave='.f($row,'chave').'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET')).'\',\'CronogramaPrestacao\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Percentual de desempenho.">PD</a>&nbsp');
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
      $sql = new db_getGPContrato; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,$w_usuario,null,null,null,null,null,null,null,null,$w_chave,null);
      if (count($RS)>0) {
        foreach ($RS as $row) {
          if ((Nvl(f($row,'fim'),'')=='') && ($w_chave!=f($row,'chave'))) $w_ativo+=1;
        }
      }
    }
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'CODES',$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    //ShowHTML('<INPUT type="hidden" name="w_ano" value="'.$w_ano.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0"><tr>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('        <td colspan="3" valign="top"><table border="0" width="100%" cellpadding=0 cellspacing=0>');
    ShowHTML('          <tr>');
    if($O=='I'){
      ShowHTML('<td valign="top"><b><u>A</u>no:</b><br><input '.$w_Disabled.' accesskey="A" type="text" name="w_ano" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_ano.'"></td>');
    }elseif($O=='A'){
      ShowHTML('<td valign="top"><b><u>A</u>no:</b><br><input disabled type="text"class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_ano.'"></td>');
      ShowHTML('<INPUT type="hidden" name="w_ano" value="'.$w_ano.'">');
    }
    ShowHTML('              <td><b><u>P</u>ercentual de desempenho:</b><br><input accesskey="P" type="text" name="w_percentual" class="STI" SIZE="6" MAXLENGTH="6" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" VALUE="'.formatNumber($w_percentual,2).'">');
    ShowHTML('        </table></td></tr>');
    ShowHTML('      <tr><td colspan=5><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan=5><hr>');
    if ($O=='I') {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Incluir">');
    } else {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Atualizar">');
    }
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_usuario='.$w_usuario.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif (!(strpos('E',$O)===false)) {
    $w_Disabled =' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'CODES',$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_usuario" value="'.$w_usuario.'">');
    ShowHTML('<INPUT type="hidden" name="w_ano" value="'.$w_ano.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center"><div align="justify">Para efetivar a exclus�o do percentual de desempenho, forne�a a assinatura eletr�nica e clique no bot�o <i>Excluir</i>.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0"><tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><u>P</u>ercentual de desempenho:</b><br><input accesskey="P" '.$w_Disabled.' type="text" name="w_percentual" class="STI" SIZE="3" MAXLENGTH="3" VALUE="'.$w_percentual.'" ></td></tr>');
    ShowHTML('      <tr valign="top"><td><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('          <input class="stb" type="submit" name="Botao" value="Excluir">');
    ShowHTML('          <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_usuario='.$w_usuario.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
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
    $sql = new db_getGPColaborador; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
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
  head();
  // Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara.
  ScriptOpen('JavaScript');
  CheckBranco();
  Modulo();
  FormataData();
  SaltaCampo();
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
  Validate('w_ctps_numero','N�mero CTPS','1','1','2','20','1','1');
  Validate('w_ctps_serie','S�rie CTPS','1','1','2','5','1','1');
  Validate('w_ctps_emissor','Emissor CTPS','1','1','3','30','1','1');
  Validate('w_ctps_emissao','Emiss�o CTPS','DATA','1','10','10','','1');
  Validate('w_pispasep_numero','N�mero PIS/PASEP','1','1','2','20','1','1');
  Validate('w_pispasep_cadastr','Emiss�o PIS/PASEP','DATA','1','10','10','','1');
  Validate('w_te_numero','N�mero t�tulo eleitor','1','1','3','20','1','1');
  Validate('w_te_zona','Zona','1','1','1','3','1','1');
  Validate('w_te_secao','Se��o','1','1','1','4','1','1');
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
  ShowHTML('       <td valign="top"><b>E<u>m</u>iss�o CTPS:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_ctps_emissao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_ctps_emissao.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
  ShowHTML('     </tr>');
  ShowHTML('     <tr valign="top">');
  ShowHTML('       <td valign="top" colspan="2"><b>Optante pelo:</b><br>');
  if ($w_pis_pasep=='A') {
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_pis_pasep" value="I"> PIS <input '.$w_Disabled.' type="radio" name="w_pis_pasep" value="A" checked> PASEP');
  } else {
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_pis_pasep" value="I" checked> PIS <input '.$w_Disabled.' type="radio" name="w_pis_pasep" value="A"> PASEP');
  }
  ShowHTML('       <td valign="top"><b>N<u>�</u>mero PIS/PASEP:</b><br><input '.$w_Disabled.' accesskey="U" type="text" name="w_pispasep_numero" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_pispasep_numero.'"></td>');
  ShowHTML('       <td valign="top"><b>Em<u>i</u>ss�o PIS/PASEP:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_pispasep_cadastr" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_pispasep_cadastr.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
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
// Pensionistas do colaborador
// -------------------------------------------------------------------------
function Pensao(){
  extract($GLOBALS);
  global $w_Disabled;

  if ($O=='') $O='I';

  $w_erro            = '';
  $w_chave           = $_REQUEST['w_chave'];
  $w_chave_aux       = $_REQUEST['w_chave_aux'];
  $w_cpf             = $_REQUEST['w_cpf'];
  $w_cnpj            = $_REQUEST['w_cnpj'];
  $w_sq_pessoa       = $_REQUEST['w_sq_pessoa'];
  $w_forma_pagamento = 'CREDITO';
  $w_tipo_pessoa     = 1;

  if ($w_troca>'' && $O!='E') {
    // Se for recarga da p�gina
    $w_chave                = $_REQUEST['w_chave'];
    $w_chave_aux            = $_REQUEST['w_chave_aux'];
    $w_nome                 = $_REQUEST['w_nome'];
    $w_nome_resumido        = $_REQUEST['w_nome_resumido'];
    $w_sq_pessoa_pai        = $_REQUEST['w_sq_pessoa_pai'];
    $w_nm_tipo_pessoa       = $_REQUEST['w_nm_tipo_pessoa'];
    $w_sq_tipo_vinculo      = $_REQUEST['w_sq_tipo_vinculo'];
    $w_nm_tipo_vinculo      = $_REQUEST['w_nm_tipo_vinculo'];
    $w_sq_forma_pag         = $_REQUEST['w_sq_forma_pag'];
    $w_sq_banco             = $_REQUEST['w_sq_banco'];
    $w_sq_agencia           = $_REQUEST['w_sq_agencia'];
    $w_operacao             = $_REQUEST['w_operacao'];
    $w_nr_conta             = $_REQUEST['w_nr_conta'];
    $w_sq_pais_estrang      = $_REQUEST['w_sq_pais_estrang'];
    $w_aba_code             = $_REQUEST['w_aba_code'];
    $w_swift_code           = $_REQUEST['w_swift_code'];
    $w_endereco_estrang     = $_REQUEST['w_endereco_estrang'];
    $w_banco_estrang        = $_REQUEST['w_banco_estrang'];
    $w_agencia_estrang      = $_REQUEST['w_agencia_estrang'];
    $w_cidade_estrang       = $_REQUEST['w_cidade_estrang'];
    $w_informacoes          = $_REQUEST['w_informacoes'];
    $w_codigo_deposito      = $_REQUEST['w_codigo_deposito'];
    $w_interno              = $_REQUEST['w_interno'];
    $w_vinculo_ativo        = $_REQUEST['w_vinculo_ativo'];
    $w_sq_pessoa_telefone   = $_REQUEST['w_sq_pessoa_telefone'];
    $w_ddd                  = $_REQUEST['w_ddd'];
    $w_nr_telefone          = $_REQUEST['w_nr_telefone'];
    $w_sq_pessoa_celular    = $_REQUEST['w_sq_pessoa_celular'];
    $w_nr_celular           = $_REQUEST['w_nr_celular'];
    $w_sq_pessoa_fax        = $_REQUEST['w_sq_pessoa_fax'];
    $w_nr_fax               = $_REQUEST['w_nr_fax'];
    $w_email                = $_REQUEST['w_email'];
    $w_sq_pessoa_endereco   = $_REQUEST['w_sq_pessoa_endereco'];
    $w_logradouro           = $_REQUEST['w_logradouro'];
    $w_complemento          = $_REQUEST['w_complemento'];
    $w_bairro               = $_REQUEST['w_bairro'];
    $w_cep                  = $_REQUEST['w_cep'];
    $w_sq_cidade            = $_REQUEST['w_sq_cidade'];
    $w_co_uf                = $_REQUEST['w_co_uf'];
    $w_sq_pais              = $_REQUEST['w_sq_pais'];
    $w_pd_pais              = $_REQUEST['w_pd_pais'];
    $w_cpf                  = $_REQUEST['w_cpf'];
    $w_nascimento           = $_REQUEST['w_nascimento'];
    $w_rg_numero            = $_REQUEST['w_rg_numero'];
    $w_rg_emissor           = $_REQUEST['w_rg_emissor'];
    $w_rg_emissao           = $_REQUEST['w_rg_emissao'];
    $w_matricula            = $_REQUEST['w_matricula'];
    $w_sq_pais_passaporte   = $_REQUEST['w_sq_pais_passaporte'];
    $w_sexo                 = $_REQUEST['w_sexo'];
    $w_cnpj                 = $_REQUEST['w_cnpj'];
    $w_inscricao_estadual   = $_REQUEST['w_inscricao_estadual'];
    $w_tipo_pensao          = $_REQUEST['w_tipo_pensao'];
    $w_valor                = $_REQUEST['w_valor'];
    $w_dt_inicio            = $_REQUEST['w_dt_inicio'];
    $w_dt_fim               = $_REQUEST['w_dt_fim'];
    $w_observacao           = $_REQUEST['w_observacao'];
  } elseif (strpos('AE',$O)!==false) {
    // Recupera os dados da pens�o
    $sql = new db_getGpPensionista; $RS = $sql->getInstanceOf($dbms,$w_sq_pessoa,$w_cliente,$w_usuario);
    foreach($RS as $row) { $RS = $row; break; }
    $w_dados_pagamento = true;
    if (count($RS)>0) {
      $w_sq_pessoa        = f($RS,'sq_pessoa');
      $w_tipo_pensao      = f($RS,'tipo');
      $w_valor            = formatNumber(f($RS,'valor'),2);
      $w_dt_inicio        = formataDataEdicao(f($RS,'inicio'));
      $w_dt_fim           = formataDataEdicao(f($RS,'fim'));
      $w_observacao       = f($RS,'observacao');
    }  

    // Recupera os dados do pensionista em co_pessoa
    $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_sq_pessoa,null,$w_cpf,$w_cnpj,null,null,null,null,null,null,null,null,null, null, null, null, null);
    if (count($RS)>0) {
      foreach($RS as $row) { $RS = $row; break; }
      $w_sq_pessoa            = f($RS,'sq_pessoa');
      $w_nome                 = f($RS,'nm_pessoa');
      $w_nome_resumido        = f($RS,'nome_resumido');
      $w_sq_pessoa_pai        = f($RS,'sq_pessoa_pai');
      $w_nm_tipo_pessoa       = f($RS,'nm_tipo_pessoa');
      $w_interno              = f($RS,'interno');
      $w_sq_pessoa_telefone   = f($RS,'sq_pessoa_telefone');
      $w_ddd                  = f($RS,'ddd');
      $w_nr_telefone          = f($RS,'nr_telefone');
      $w_sq_pessoa_celular    = f($RS,'sq_pessoa_celular');
      $w_nr_celular           = f($RS,'nr_celular');
      $w_sq_pessoa_fax        = f($RS,'sq_pessoa_fax');
      $w_nr_fax               = f($RS,'nr_fax');
      $w_cpf                  = f($RS,'cpf');
      $w_rg_numero            = f($RS,'rg_numero');
      $w_rg_emissor           = f($RS,'rg_emissor');
      $w_rg_emissao           = FormataDataEdicao(f($RS,'rg_emissao'));
      $w_sexo                 = f($RS,'sexo');
    }
    
    // Recupera os dados banc�rios do pensionista
    $sql = new db_getContaBancoList; $RSConta = $sql->getInstanceOf($dbms,$w_sq_pessoa,null,null);
    if (count($RSConta)>0) {
      foreach($RSConta as $row) { $RSConta = $row; break; }
      $w_sq_banco   = f($RSConta,'sq_banco');
      $w_nr_conta   = f($RSConta,'numero');
      $w_sq_agencia = f($RSConta,'sq_agencia');
      $w_operacao   = f($RSConta,'operacao');
    }
  }
  // Recupera informa��o do campo opera��o do banco selecionado
  if (nvl($w_sq_banco,'')>'') {
    $sql = new db_getBankData; $RS_Banco = $sql->getInstanceOf($dbms, $w_sq_banco);
    $w_exige_operacao = f($RS_Banco,'exige_operacao');
  }

  $sql = new db_getGPContrato; $RSContrato = $sql->getInstanceOf($dbms,$w_cliente,null,$w_usuario,null,null,null,null,null,null,null,null,null,null);
  $RSContrato = SortArray($RSContrato,'inicio','desc');
  $w_doe = time();
  foreach($RSContrato as $row) {
    if (f($row,'inicio')<$w_doe) { 
      $w_doe = f($row,'inicio');
    }
  }
  
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  
  // Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara,
  // tratando as particularidades de cada servi�o
  ScriptOpen('JavaScript');
  Modulo();
  FormataCPF();
  FormataCNPJ();
  FormataCEP();
  CheckBranco();
  FormataData();
  FormataValor();
  SaltaCampo();
  ValidateOpen('Validacao');
  if ($w_cpf=='' && strpos('IAEO',$O)!==false) {
    // Se o benefici�rio ainda n�o foi selecionado
    ShowHTML('  if (theForm.Botao.value == "Procurar") {');
    Validate('w_nome','Nome','','1','4','20','1','');
    ShowHTML('  theForm.Botao.value = "Procurar";');
    ShowHTML('}');
    ShowHTML('else {');
    Validate('w_cpf','CPF','CPF','1','14','14','','0123456789-.');
    ShowHTML('  theForm.w_sq_pessoa.value = \'\';');
    ShowHTML('}');
  } elseif ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.Botao.value.indexOf(\'Alterar\') >= 0) { return true; }');
    Validate('w_nome','Nome','1',1,5,60,'1','1');
    Validate('w_nome_resumido','Nome resumido','1',1,2,21,'1','1');
    Validate('w_sexo','Sexo','SELECT',1,1,1,'MF','');
    Validate('w_rg_numero','Identidade','1','',2,30,'1','1');
    Validate('w_rg_emissao','Data de emiss�o','DATA','',10,10,'','0123456789/');
    Validate('w_rg_emissor','�rg�o expedidor','1','',2,30,'1','1');
    ShowHTML('  if ((theForm.w_rg_numero.value+theForm.w_rg_emissao.value+theForm.w_rg_emissor.value)!="" && (theForm.w_rg_numero.value=="" || theForm.w_rg_emissor.value=="")) {');
    ShowHTML('     alert("Os campos identidade, data de emiss�o e �rg�o emissor devem ser informados em conjunto!\\nDos tr�s, apenas a data de emiss�o � opcional.");');        ShowHTML('     theForm.w_rg_numero.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    Validate('w_ddd','DDD','1','',2,4,'','0123456789');
    Validate('w_nr_telefone','Telefone','1','',7,25,'1','1');      
    Validate('w_nr_fax','Fax','1','',7,25,'1','1');
    Validate('w_nr_celular','Celular','1','',7,25,'1','1');
    ShowHTML('  if ((theForm.w_nr_telefone.value+theForm.w_nr_fax.value+theForm.w_nr_celular.value)!="" && theForm.w_ddd.value=="") {');
    ShowHTML('     alert("O campo DDD � obrigat�rio quando informar telefone, fax ou celular!");');
    ShowHTML('     theForm.w_ddd.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    ShowHTML('  if (theForm.w_ddd.value!="" && theForm.w_nr_telefone.value=="") {');
    ShowHTML('     alert("Se informar o DDD, ent�o informe obrigatoriamente o telefone!\\nFax e celular s�o opcionais.");');
    ShowHTML('     theForm.w_nr_telefone.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    Validate('w_tipo_pensao','Tipo de pens�o','SELECT',1,1,10,'1','1');
    Validate('w_valor','Valor da pens�o','VALOR','1',4,18,'','0123456789.,');
    ShowHTML('  if (theForm.w_tipo_pensao[theForm.w_tipo_pensao.selectedIndex].value==2 || theForm.w_tipo_pensao[theForm.w_tipo_pensao.selectedIndex].value==3) {');
    CompValor('w_valor','Percentual','<=','100','100,00');
    ShowHTML('  }');
    Validate('w_dt_inicio','In�cio do per�odo da pens�o','DATA','1','10','10','','0123456789/');
    CompData('w_dt_inicio','In�cio','>=',''.formataDataEdicao($w_doe).'',''.formataDataEdicao($w_doe).'');
    CompData('w_dt_inicio','In�cio','<=',''.formataDataEdicao(time()).'','data atual');
    Validate('w_dt_fim','T�rmino do per�odo da pens�o','DATA','','10','10','','0123456789/');
    ShowHTML('  if (theForm.w_dt_fim.value!="") {');
    CompData('w_dt_inicio','In�cio','<','w_dt_fim','t�rmino');
    ShowHTML('  }');
    Validate('w_sq_banco','Banco','SELECT',1,1,10,'1','1');
    Validate('w_sq_agencia','Agencia','SELECT',1,1,10,'1','1');
    if ($w_exige_operacao=='S') Validate('w_operacao','Opera��o','1','1',1,6,'','0123456789');
    Validate('w_nr_conta','N�mero da conta','1','1',2,30,'ZXAzxa','0123456789-');
    Validate('w_assinatura','Assinatura eletr�nica','1','1','3','14','1','1');
    if ($w_cadgeral=='S') {
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    }
  } elseif ($O=='E') {
    Validate('w_assinatura','Assinatura eletr�nica','1','1','3','14','1','1');
  }
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (($w_cpf=='') || strpos($_REQUEST['Botao'],'Alterar')!==false || strpos($_REQUEST['Botao'],'Procurar')!==false) {
    // Se o benefici�rio ainda n�o foi selecionado
    if (strpos($_REQUEST['Botao'],'Procurar')!==false) {
      // Se est� sendo feita busca por nome
      BodyOpenClean('onLoad=\'this.focus()\';');
    } else {
      BodyOpenClean('onLoad=\'this.focus()\';');
    }
  } elseif ($O=='I' || $O=='A') {
    BodyOpenClean('onLoad=\'document.Form.w_nome.focus()\';');
  } elseif ($O=='E') {
    BodyOpenClean('onLoad=\'document.Form.w_assinatura.focus()\';');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  
  If($O=='L'){
    $sql = new db_getGpPensionista; $RS = $sql->getInstanceOf($dbms,$w_sq_pessoa,$w_cliente,$w_usuario);
    if ($p_ordena>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome','asc');
    } else {
      $RS = SortArray($RS,'nome','asc');
    }
    ShowHTML('<tr><td colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('<tr><td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&w_chave='.$w_chave.'&w_usuario='.$w_usuario.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('CPF','cpf').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome resumido','nome_resumido').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Tipo de pens�o','tipo_pensao').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Valor','valor').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('In�cio','inicio').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('T�rmino','fim').'</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><b>O colaborador n�o possui pensionistas cadastrados.</b></td></tr>');
    } else {
      foreach($RS as $row) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
        ShowHTML('        <td align="center">'.Nvl(f($row,'cpf'),'---').'</td>');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nome_resumido').'</td>');
        ShowHTML('        <td>'.f($row,'tipo_pensao').'</td>');
        ShowHTML('        <td align="center">'.formatNumber(f($row,'valor'),2).'</td>');
        ShowHTML('        <td align="center">'.formataDataEdicao(f($row,'inicio')).'</td>');
        ShowHTML('        <td align="center">'.Nvl(formataDataEdicao(f($row,'fim')),'-').'</td>');
        ShowHTML('        <td nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O=A&w_cpf='.f($row,'cpf').'&w_sq_pessoa='.f($row,'chave').'&w_chave='.$w_chave.'&w_usuario='.f($row,'colaborador').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O=E&w_cpf='.f($row,'cpf').'&w_sq_pessoa='.f($row,'chave').'&w_chave='.$w_chave.'&w_usuario='.f($row,'colaborador').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o pensionista do banco de dados">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');  
  } elseif (strpos('IAE',$O)!==false) {
    if ($O=='I' and nvl($_REQUEST['w_cpf'],'')!='') {
      // Verifica se j� existe pessoa f�sica com o CPF informado
      $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_pessoa,null,$_REQUEST['w_cpf'],null,null,$w_tipo_pessoa,null,null,null,null,null,null,null, null, null, null, null);
      if (count($RS)>0) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'J� existe pessoa cadastrada com o CPF informado!\\nVerifique os dados.\');');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=I&w_troca=w_cpf&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
          ScriptClose();
          exit;
      }
    }
    if($O=='E'){
      $w_Disabled = 'DISABLED';
    }
    if($w_cpf=='' || strpos($_REQUEST['Botao'],'Alterar')!==false || strpos($_REQUEST['Botao'],'Procurar')!==false) {
      // Se o benefici�rio ainda n�o foi selecionado
      ShowHTML('<FORM action="'.$w_dir.$w_pagina.$par.'" method="POST" name="Form" onSubmit="return(Validacao(this));">');
    } else {
      ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava" method="POST" name="Form" onSubmit="return(Validacao(this));">');
    }
    ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
    ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
    ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
    ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
    ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
    ShowHTML('<INPUT type="hidden" name="SG" value="'.$SG.'">');
    ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
    ShowHTML('<INPUT type="hidden" name="O" value="'.$O.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_pessoa" value="'.$w_sq_pessoa.'">');
    ShowHTML('<INPUT type="hidden" name="w_usuario" value="'.$w_usuario.'">');
    ShowHTML('<INPUT type="hidden" name="w_pessoa_atual" value="'.$w_pessoa_atual.'">');
    if ($w_cpf=='' || strpos($_REQUEST['Botao'],'Alterar')!==false || strpos($_REQUEST['Botao'],'Procurar')!==false) {
      $w_nome=$_REQUEST['w_nome'];
      if (strpos($_REQUEST['Botao'],'Alterar')!==false) {
        $w_cpf  = '';
        $w_cnpj = '';
        $w_nome = '';
      }
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
      ShowHTML('    <table border="0">');
      ShowHTML('        <tr><td colspan=4>Informe os dados abaixo e clique no bot�o "Selecionar" para continuar.</TD>');
      ShowHTML('        <tr><td colspan=4><b><u>C</u>PF:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cpf" VALUE="'.$w_cpf.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
      ShowHTML('            <INPUT class="stb" TYPE="submit" NAME="Botao" VALUE="Selecionar" onClick="Botao.value=this.value; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'">');
      ShowHTML('        <tr><td colspan=4><p>&nbsp</p>');
      ShowHTML('        <tr><td colspan=4 heigth=1 bgcolor="#000000">');
      ShowHTML('        <tr><td colspan=4>');
      ShowHTML('             <b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome SEM ACENTOS)<br><INPUT ACCESSKEY="P" TYPE="text" class="sti" NAME="w_nome" VALUE="'.$w_nome.'" SIZE="20" MaxLength="20">');
      ShowHTML('              <INPUT class="stb" TYPE="submit" NAME="Botao" VALUE="Procurar" onClick="Botao.value=this.value; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'">');
      ShowHTML('      </table>');
      if ($w_nome>'') {
        $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,null,null,null,$w_nome,1,null,null,null,null,null,null,null, null, null, null, null);
        ShowHTML('<tr><td colspan=3>');
        ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('          <td><b>Nome</td>');
        ShowHTML('          <td><b>Nome resumido</td>');
        ShowHTML('          <td><b>CPF</td>');
        ShowHTML('          <td><b>Opera��es</td>');
        ShowHTML('        </tr>');
        if (count($RS)<=0) {
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>N�o h� pessoas que contenham o texto informado.</b></td></tr>');
        } else {
          foreach($RS as $row) {
            ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
            ShowHTML('        <td>'.f($row,'nm_pessoa').'</td>');
            ShowHTML('        <td>'.f($row,'nome_resumido').'</td>');
            ShowHTML('        <td align="center">'.Nvl(f($row,'cpf'),'---').'</td>');
            ShowHTML('        <td nowrap>');
            ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O=A&w_usuario='.$w_usuario.'&w_cpf='.f($row,'cpf').'&w_sq_pessoa='.f($row,'sq_pessoa').'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&Botao=Selecionar">Selecionar</A>&nbsp');
            ShowHTML('        </td>');
            ShowHTML('      </tr>');
          }
        }
        ShowHTML('      </center>');
        ShowHTML('    </table>');
        ShowHTML('  </td>');
        ShowHTML('</tr>');
      }
    } else {
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
      ShowHTML('    <table width="97%" border="0">');
      ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Identifica��o</td></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2"><table border="0" width="100%">');
      ShowHTML('          <tr valign="top">');
      ShowHTML('          <td>CPF:<br><b><font size=2>'.$w_cpf);
      ShowHTML('              <INPUT type="hidden" name="w_cpf" value="'.$w_cpf.'">');
      ShowHTML('          <tr valign="top">');
      ShowHTML('             <td><b><u>N</u>ome completo:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="45" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
      ShowHTML('             <td><b><u>N</u>ome resumido:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome_resumido" class="sti" SIZE="15" MAXLENGTH="21" VALUE="'.$w_nome_resumido.'"></td>');
      SelecaoSexo('Se<u>x</u>o:','X',null,$w_sexo,null,'w_sexo',null,null);
      ShowHTML('          <tr valign="top">');
      ShowHTML('          <td><b><u>I</u>dentidade:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_rg_numero" class="sti" SIZE="14" MAXLENGTH="80" VALUE="'.$w_rg_numero.'"></td>');
      ShowHTML('          <td><b>Data de <u>e</u>miss�o:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_rg_emissao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_rg_emissao.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      ShowHTML('          <td><b>�r<u>g</u>�o emissor:</b><br><input '.$w_Disabled.' accesskey="G" type="text" name="w_rg_emissor" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_rg_emissor.'"></td>');
      ShowHTML('          </table>');
      ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Telefones</td></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
      ShowHTML('          <tr valign="top">');
      ShowHTML('          <td><b><u>D</u>DD:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_ddd" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_ddd.'"></td>');
      ShowHTML('          <td><b>Te<u>l</u>efone:</b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_nr_telefone" class="sti" SIZE="20" MAXLENGTH="40" VALUE="'.$w_nr_telefone.'"> '.consultaTelefone($w_cliente).'</td>');
      ShowHTML('          <td title="Se a outra parte informar um n�mero de fax, informe-o neste campo."><b>Fa<u>x</u>:</b><br><input '.$w_Disabled.' accesskey="X" type="text" name="w_nr_fax" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_nr_fax.'"></td>');
      ShowHTML('          <td title="Se a outra parte informar um celular institucional, informe-o neste campo."><b>C<u>e</u>lular:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_nr_celular" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_nr_celular.'"></td>');
      ShowHTML('          </table>');
      
      ShowHTML('      <tr valign="top">');
      ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Dados da pens�o</td></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
      ShowHTML('      <tr valign="top">');
      selecaoTipoPensao('<u>T</u>ipo de pens�o:','T','Selecione o tipo de pens�o.',$w_tipo_pensao,null,'w_tipo_pensao',null,null);
      ShowHTML('          <td title="Informe o valor da pens�o."><b><u>V</u>alor:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor" onKeyDown="FormataValor(this,18,2,event);" style="text-align:right;" class="sti" SIZE="7" MAXLENGTH="8" VALUE="'.$w_valor.'" ></td>');
      ShowHTML('          <td title="Informe a data de in�cio da pens�o."><b><u>I</u>n�cio:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_dt_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_dt_inicio.'" onKeyDown="FormataData(this,event);"></td>');
      ShowHTML('          <td title="Informe a data de t�rmino da pens�o."><b>T�<u>r</u>mino:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_dt_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_dt_fim.'" onKeyDown="FormataData(this,event);"></td>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('        <td colspan="5" valign="top"><b><u>O</u>bserva��es:</b><br><textarea title="Observa��es a respeito da pens�o." accesskey="O" type="text" name="w_observacao" class="sti" cols="51" rows="5" '.$w_Disabled.' MAXLENGTH="255">'.$w_observacao.'</textarea></td>');
      ShowHTML('          </table>');      
      ShowHTML('      <tr valign="top">');

      ShowHTML('      <tr valign="top">');
      ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Dados banc�rios</td></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
      ShowHTML('      <tr valign="top">');
      SelecaoBanco('<u>B</u>anco:','B','Selecione o banco onde dever�o ser feitos os pagamentos referentes ao acordo.',$w_sq_banco,null,'w_sq_banco',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_agencia\'; document.Form.submit();"');
      SelecaoAgencia('A<u>g</u>�ncia:','A','Selecione a ag�ncia onde dever�o ser feitos os pagamentos referentes ao acordo.',$w_sq_agencia,Nvl($w_sq_banco,-1),'w_sq_agencia',null,null);
      ShowHTML('      <tr valign="top">');
      if ($w_exige_operacao=='S') ShowHTML('          <td title="Alguns bancos trabalham com o campo "Opera��o", al�m do n�mero da conta. A Caixa Econ�mica Federal � um exemplo. Se for o caso,informe a opera��o neste campo; caso contr�rio, deixe-o em branco."><b>O<u>p</u>era��o:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_operacao" class="sti" SIZE="6" MAXLENGTH="6" VALUE="'.$w_operacao.'"></td>');
      ShowHTML('          <td title="Informe o n�mero da conta banc�ria, colocando o d�gito verificador, se existir, separado por um h�fen. Exemplo: 11214-3. Se o banco n�o trabalhar com d�gito verificador, informe apenas n�meros. Exemplo: 10845550."><b>N�mero da con<u>t</u>a:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_nr_conta" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_nr_conta.'"></td>');
      ShowHTML('          </table>');

      ShowHTML('     <tr><td align="LEFT" colspan="3"><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
      ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
      ShowHTML('      <tr><td align="center" colspan="3">');
      if ($O=='E') {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Excluir" onClick="Botao.value=this.value;">');
      } else {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar" onClick="Botao.value=this.value;">');
      }
      ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&R='.$R.'&O=L&w_cliente='.$w_cliente.'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
      if ($w_cadgeral=='S') {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Alterar benefici�rio" onClick="Botao.value=this.value; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.submit();">');
      }
      ShowHTML('          </td>');
      ShowHTML('      </tr>');
      ShowHTML('    </table>');
      ShowHTML('    </TD>');
      ShowHTML('</tr>');
    }
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
// Dependentes do colaborador
// -------------------------------------------------------------------------
function Familiares(){
  extract($GLOBALS);
  global $w_Disabled;

  if ($O=='') $O='I';

  $w_erro            = '';
  $w_chave           = $_REQUEST['w_chave'];
  $w_chave_aux       = $_REQUEST['w_chave_aux'];
  $w_cpf             = $_REQUEST['w_cpf'];
  $w_cnpj            = $_REQUEST['w_cnpj'];
  $w_sq_pessoa       = $_REQUEST['w_sq_pessoa'];
  $w_forma_pagamento = 'CREDITO';
  $w_tipo_pessoa     = 1;

  if ($w_troca>'' && $O!='E') {
    // Se for recarga da p�gina
    $w_chave            = $_REQUEST['w_chave'];
    $w_chave_aux        = $_REQUEST['w_chave_aux'];
    $w_nome             = $_REQUEST['w_nome'];
    $w_nome_resumido    = $_REQUEST['w_nome_resumido'];
    $w_nascimento       = $_REQUEST['w_nascimento'];
    $w_sq_pessoa_pai    = $_REQUEST['w_sq_pessoa_pai'];
    $w_parentesco       = $_REQUEST['w_parentesco'];
    $w_seguro_saude     = $_REQUEST['seguro_saude'];
    $w_seguro_odonto    = $_REQUEST['seguro_odonto'];
    $w_seguro_vida      = $_REQUEST['seguro_vida'];
    $w_imposto_renda    = $_REQUEST['imposto_renda'];

  } elseif (strpos('AE',$O)!==false) {
    // Recupera os dados da pens�o
    $sql = new db_getGpFamiliares; $RS = $sql->getInstanceOf($dbms,$w_sq_pessoa,$w_cliente,$w_usuario);
    foreach($RS as $row) { $RS = $row; break; }
    if (count($RS)>0) {
      $w_sq_pessoa      = f($RS,'chave');
      $w_nome           = f($RS,'nome');
      $w_nome_resumido  = f($RS,'nome_resumido');
      $w_cpf            = f($RS,'cpf');
      $w_nascimento     = formataDataEdicao(f($RS,'nascimento'));
      $w_sexo           = f($RS,'sexo');
      $w_parentesco     = f($RS,'tipo');
      $w_seguro_saude   = f($RS,'seguro_saude');
      $w_seguro_odonto  = f($RS,'seguro_odonto');
      $w_seguro_vida    = f($RS,'seguro_vida');
      $w_imposto_renda  = f($RS,'imposto_renda');
    }
  }

  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);

  // Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara,
  // tratando as particularidades de cada servi�o
  ScriptOpen('JavaScript');
  Modulo();
  FormataCPF();
  FormataCNPJ();
  FormataCEP();
  CheckBranco();
  FormataData();
  FormataValor();
  SaltaCampo();
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    Validate('w_nome','Nome','1',1,5,60,'1','1');
    Validate('w_nascimento','Data de Nascimento','DATA',1,10,10,'',1);
    Validate('w_nome_resumido','Nome resumido','1',1,2,21,'1','1');
    Validate('w_sexo','Sexo','SELECT',1,1,1,'1','');
    Validate('w_cpf','CPF','CPF','','14','14','','0123456789-.');
    Validate('w_parentesco','Grau de parentesco','SELECT',1,1,10,'1','1');
    Validate('w_assinatura','Assinatura eletr�nica','1','1','3','14','1','1');
  }elseif($O=='E'){
    Validate('w_assinatura','Assinatura eletr�nica','1','1','3','14','1','1');
  }

  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (($w_cpf=='') || strpos($_REQUEST['Botao'],'Alterar')!==false || strpos($_REQUEST['Botao'],'Procurar')!==false) {
    // Se o benefici�rio ainda n�o foi selecionado
    if (strpos($_REQUEST['Botao'],'Procurar')!==false) {
      // Se est� sendo feita busca por nome
      BodyOpenClean('onLoad=\'this.focus()\';');
    } else {
      BodyOpenClean('onLoad=\'this.focus()\';');
    }
  } elseif ($O=='I' || $O=='A') {
    BodyOpenClean('onLoad=\'this.focus()\';');
  } elseif ($O=='E') {
    BodyOpenClean('onLoad=\'document.Form.w_assinatura.focus()\';');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');

  If($O=='L'){
    $sql = new db_getGpFamiliares; $RS = $sql->getInstanceOf($dbms,$w_sq_pessoa,$w_cliente,$w_usuario);
    if ($p_ordena>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome','asc');
    } else {
      $RS = SortArray($RS,'nome','asc');
    }
    ShowHTML('<tr><td colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('<tr><td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&w_chave='.$w_chave.'&w_usuario='.$w_usuario.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('CPF','cpf').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome resumido','nome_resumido').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Parentesco','parentesco').'</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><b>O colaborador n�o possui familiares/segurados cadastrados.</b></td></tr>');
    } else {
      foreach($RS as $row) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
        ShowHTML('        <td align="center">'.Nvl(f($row,'cpf'),'---').'</td>');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nome_resumido').'</td>');
        ShowHTML('        <td align="center">'.f($row,'parentesco').'</td>');
        ShowHTML('        <td nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O=A&w_sq_pessoa='.f($row,'chave').'&w_chave='.$w_chave.'&w_usuario='.f($row,'colaborador').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O=E&w_sq_pessoa='.f($row,'chave').'&w_chave='.$w_chave.'&w_usuario='.f($row,'colaborador').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o benefici�rio do banco de dados">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (strpos('IAE',$O)!==false) {
    if($O=='E'){
      $w_Disabled = 'DISABLED';
    }
    ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava" method="POST" name="Form" onSubmit="return(Validacao(this));">');
    ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
    ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
    ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
    ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
    ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
    ShowHTML('<INPUT type="hidden" name="SG" value="'.$SG.'">');
    ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
    ShowHTML('<INPUT type="hidden" name="O" value="'.$O.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_pessoa" value="'.$w_sq_pessoa.'">');
    ShowHTML('<INPUT type="hidden" name="w_usuario" value="'.$w_usuario.'">');
    ShowHTML('<INPUT type="hidden" name="w_pessoa_atual" value="'.$w_pessoa_atual.'">');
    if ($O=='L') {
      $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,null,null,null,$w_nome,1,null,null,null,null,null,null,null, null, null, null, null);
      ShowHTML('<tr><td colspan=3>');
      ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>Nome</td>');
      ShowHTML('          <td><b>Nome resumido</td>');
      ShowHTML('          <td><b>CPF</td>');
      ShowHTML('          <td><b>Opera��es</td>');
      ShowHTML('        </tr>');
      if (count($RS)<=0) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>N�o h� pessoas que contenham o texto informado.</b></td></tr>');
      } else {
        foreach($RS as $row) {
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          ShowHTML('        <td>'.f($row,'nm_pessoa').'</td>');
          ShowHTML('        <td>'.f($row,'nome_resumido').'</td>');
          ShowHTML('        <td align="center">'.Nvl(f($row,'cpf'),'---').'</td>');
          ShowHTML('        <td nowrap>');
          ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O=A&w_usuario='.$w_usuario.'&w_cpf='.f($row,'cpf').'&w_sq_pessoa='.f($row,'sq_pessoa').'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&Botao=Selecionar">Selecionar</A>&nbsp');
          ShowHTML('        </td>');
          ShowHTML('      </tr>');
        }
      }
      ShowHTML('      </center>');
      ShowHTML('    </table>');
      ShowHTML('  </td>');
      ShowHTML('</tr>');
    }
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Identifica��o</td></td></tr>');
    ShowHTML('      <tr><td colspan="2"><table border="0" width="100%">');
    ShowHTML('          <tr valign="top">');
    ShowHTML('             <td><b><u>N</u>ome completo:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="45" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
    ShowHTML('          <td><b>Da<u>t</u>a de nascimento:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_nascimento" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_nascimento.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
    ShowHTML('          <tr valign="top">');
    ShowHTML('             <td><b><u>N</u>ome resumido:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome_resumido" class="sti" SIZE="15" MAXLENGTH="21" VALUE="'.$w_nome_resumido.'"></td>');
    ShowHTML('          <tr valign="top">');
    if($O=='A' || $O=='E'){
      ShowHTML('             <td><b><u>C</u>PF:<br><INPUT DISABLED ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cpf" VALUE="'.$w_cpf.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
      ShowHTML('<INPUT type="hidden" name="w_cpf" value="'.$w_cpf.'">');
    }else{
      ShowHTML('             <td><b><u>C</u>PF:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cpf" VALUE="'.$w_cpf.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
    }

    ShowHTML('          <tr valign="top">');
    SelecaoSexo('Se<u>x</u>o:','X',null,$w_sexo,null,'w_sexo',null,null);
    ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="3"><table border="0" width="100%">');
    ShowHTML('          </table>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Parentesco</td></td></tr>');
    ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('      <tr valign="top">');
    selecaoParentesco('Paren<u>t</u>esco:','T','Selecione o tipo de pens�o.',$w_parentesco,null,'w_parentesco',null,null);
    ShowHTML('          </table>');
    ShowHTML('      <tr valign="top">');

    ShowHTML('      <tr valign="top">');
    ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Benef�cios do familiar</td></td></tr>');
    ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('      <tr valign="top">');
    ShowHTML(MontaRadioNS('<br><b>A pessoa � dependente para efeito do plano de sa�de?</b>',$w_seguro_saude,'w_seguro_saude','Indica se a pessoa � dependente para efeito de seguro sa�de.',null));
    ShowHTML(MontaRadioNS('<br><b>A pessoa � dependente para efeito do plano odontol�gico?</b>',$w_seguro_odonto,'w_seguro_odonto','Indica se a pessoa � dependente para efeito de seguro odonto.',null));
    ShowHTML('      <tr valign="top">');
    ShowHTML(MontaRadioNS('<br><b>A pessoa � dependente para efeito de seguro de vida?</b>',$w_seguro_vida,'w_seguro_vida','Indica se a pessoa � dependente para efeito de seguro de vida.',null));
    ShowHTML(MontaRadioNS('<br><b>A pessoa � dependente para efeito do imposto de renda?</b>',$w_imposto_renda,'w_imposto_renda','Indica se a pessoa � dependente para efeito de imposto de renda..',null));
    ShowHTML('          </table>');
    ShowHTML('     <tr><td align="LEFT" colspan="3"><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
    ShowHTML('      <tr><td align="center" colspan="3">');
    if ($O=='E') {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Excluir" onClick="Botao.value=this.value;">');
    } else {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar" onClick="Botao.value=this.value;">');
    }
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&R='.$R.'&O=L&w_cliente='.$w_cliente.'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
    if ($w_cadgeral=='S') {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Alterar benefici�rio" onClick="Botao.value=this.value; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.submit();">');
    }
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
// Rotina de contratos do colaborador
// -------------------------------------------------------------------------
function Contrato() {
  extract($GLOBALS);
  $w_chave                 = $_REQUEST['w_chave'];
  $w_cc                    = $_REQUEST['w_cc'];
  $w_posto_trabalho        = $_REQUEST['w_posto_trabalho'];
  $w_modalidade_contrato   = $_REQUEST['w_modalidade_contrato'];
  $w_unidade_lotacao       = $_REQUEST['w_unidade_lotacao'];
  $w_unidade_exercicio     = $_REQUEST['w_unidade_exercicio'];
  $w_localizacao           = $_REQUEST['w_localizacao'];
  $w_matricula             = $_REQUEST['w_matricula'];
  $w_dt_ini                = $_REQUEST['w_dt_ini'];
  $w_dt_fim                = $_REQUEST['w_dt_fim'];
  $w_ativo                 = $_REQUEST['w_ativo'];
  $w_sq_tipo_vinculo       = $_REQUEST['w_sq_tipo_vinculo'];
  $w_username_pessoa       = $_REQUEST['w_username_pessoa'];
  $w_entrada_manha         = $_REQUEST['w_entrada_manha'];
  $w_saida_manha           = $_REQUEST['w_saida_manha'];
  $w_entrada_tarde         = $_REQUEST['w_entrada_tarde'];
  $w_saida_tarde           = $_REQUEST['w_saida_tarde'];
  $w_entrada_noite         = $_REQUEST['w_entrada_noite'];
  $w_saida_noite           = $_REQUEST['w_saida_noite'];
  $w_sabado                = $_REQUEST['w_sabado'];
  $w_domingo               = $_REQUEST['w_domingo'];
  $w_banco_horas_data      = $_REQUEST['w_banco_horas_data'];
  $w_banco_horas_saldo     = $_REQUEST['w_banco_horas_saldo'];
  $w_remuneracao_inicial   = $_REQUEST['w_remuneracao_inicial'];
  $w_seguro_saude          = $_REQUEST['w_seguro_saude'];
  $w_seguro_odonto         = $_REQUEST['w_seguro_odonto'];
  $w_seguro_vida           = $_REQUEST['w_seguro_vida'];
  $w_plano_saude           = $_REQUEST['w_plano_saude'];
  $w_plano_odonto          = $_REQUEST['w_plano_odonto'];
  $w_plano_vida            = $_REQUEST['w_plano_vida'];
  $w_vale_transporte       = $_REQUEST['w_vale_transporte'];
  $w_vale_refeicao         = $_REQUEST['w_vale_refeicao'];
  $w_data_atestado         = $_REQUEST['w_data_atestado'];
  $w_dias_experiencia      = $_REQUEST['w_dias_experiencia'];
  $w_observacao_beneficios = $_REQUEST['w_observacao_beneficios'];

  Cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem dos contratos do colaborador</TITLE>');
  if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">');
  Estrutura_CSS($w_cliente);
  if ($O=='L') {
    $sql = new db_getGPContrato; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,$w_usuario,null,null,null,null,null,null,null,null,null,null);
    $RS = SortArray($RS,'inicio','desc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    $sql = new db_getGPContrato; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,$w_usuario,null,null,null,null,null,null,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    if (count($RS)>0) {
      $w_chave               = f($RS,'chave');
      $w_cc                  = f($RS,'centro_custo');
      $w_posto_trabalho      = f($RS,'sq_posto_trabalho');
      $w_modalidade_contrato = f($RS,'sq_modalidade_contrato');
      $w_unidade_lotacao     = f($RS,'sq_unidade_lotacao');
      $w_unidade_exercicio   = f($RS,'sq_unidade_exercicio');
      $w_localizacao         = f($RS,'sq_localizacao');
      $w_matricula           = f($RS,'matricula');
      $w_dt_ini              = FormataDataEdicao(f($RS,'inicio'));
      $w_dt_fim              = FormataDataEdicao(f($RS,'fim'));
      $w_username            = f($RS,'trata_username');
      $w_ferias              = f($RS,'trata_ferias');
      $w_horas_extras        = f($RS,'trata_extras');
      $w_sq_tipo_vinculo     = f($RS,'sq_tipo_vinculo');
      $w_entrada_manha       = f($RS,'entrada_manha');
      $w_saida_manha         = f($RS,'saida_manha');
      $w_entrada_tarde       = f($RS,'entrada_tarde');
      $w_saida_tarde         = f($RS,'saida_tarde');
      $w_entrada_noite       = f($RS,'entrada_noite');
      $w_saida_noite         = f($RS,'saida_noite');
      $w_sabado              = f($RS,'sabado');
      $w_domingo             = f($RS,'domingo');
      $w_minutos_diarios     = f($RS,'minutos_diarios');
      $w_carga_diaria        = f($RS,'carga_diaria');
      $w_banco_horas_data    = FormataDataEdicao(f($RS,'banco_horas_data'));
      $w_banco_horas_saldo   = f($RS,'banco_horas_saldo');
      $w_remuneracao_inicial = formatNumber(f($RS,'remuneracao_inicial'));
      $w_seguro_saude        = f($RS,'seguro_saude');
      $w_seguro_odonto       = f($RS,'seguro_odonto');
      $w_seguro_vida         = f($RS,'seguro_vida');
      $w_plano_saude         = f($RS,'plano_saude');
      $w_plano_odonto        = f($RS,'plano_odonto');
      $w_plano_vida          = f($RS,'plano_vida');
      $vale_transporte       = f($RS,'vale_transporte');
      $w_vale_refeicao       = f($RS,'vale_refeicao');
      $w_data_atestado       = FormataDataEdicao(f($RS,'data_atestado'));
      $w_dias_experiencia    = f($RS,'dias_experiencia');
      $w_observacao_beneficios = f($RS,'observacao_beneficios');
    }
    $w_erro=ValidaColaborador($w_cliente,$w_usuario,$w_chave,null);
  }
  if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    formatadata();
    FormataHora();
    FormataValor();
    SaltaCampo();
    ShowHTML('function calculaDia(dia) {');
    ShowHTML('  var entrada1 = document.Form.w_entrada_manha.value;');
    ShowHTML('  var saida1 = document.Form.w_saida_manha.value;');
    ShowHTML('  var entrada2 = document.Form.w_entrada_tarde.value;');
    ShowHTML('  var saida2 = document.Form.w_saida_tarde.value;');
    ShowHTML('  var entrada3 = document.Form.w_entrada_noite.value;');
    ShowHTML('  var saida3 = document.Form.w_saida_noite.value;');
    ShowHTML('  var saldo1 = 0;');
    ShowHTML('  var saldo2 = 0;');
    ShowHTML('  var saldo3 = 0;');
    ShowHTML('  var saldo4 = 0;');
    ShowHTML('  var saldo = "00:00";');
    ShowHTML('  if (entrada1!="" && saida1!="") {');
    ShowHTML('    var minutos1 = parseInt(entrada1.substring(0,2)*60,10) + parseInt(entrada1.substring(3),10)');
    ShowHTML('    var minutos2 = parseInt(saida1.substring(0,2)*60,10) + parseInt(saida1.substring(3),10)');
    ShowHTML('    var saldo1 = minutos2 - minutos1;');
    ShowHTML('  }');
    ShowHTML('  if (entrada2!="" && saida2!="") {');
    ShowHTML('    var minutos1 = parseInt(entrada2.substring(0,2)*60,10) + parseInt(entrada2.substring(3),10)');
    ShowHTML('    var minutos2 = parseInt(saida2.substring(0,2)*60,10) + parseInt(saida2.substring(3),10)');
    ShowHTML('    var saldo2 = minutos2 - minutos1;');
    ShowHTML('  }');
    ShowHTML('  if (entrada3!="" && saida3!="") {');
    ShowHTML('    var minutos1 = parseInt(entrada3.substring(0,2)*60,10) + parseInt(entrada3.substring(3),10)');
    ShowHTML('    var minutos2 = parseInt(saida3.substring(0,2)*60,10) + parseInt(saida3.substring(3),10)');
    ShowHTML('    var saldo3 = minutos2 - minutos1;');
    ShowHTML('  }');

    ShowHTML('  if (saldo1!="") saldo4 = saldo4 + saldo1;');
    ShowHTML('  if (saldo2!="") saldo4 = saldo4 + saldo2;');
    ShowHTML('  if (saldo3!="") saldo4 = saldo4 + saldo3;');

    ShowHTML('  var horas   = parseInt(saldo4/60,10);');
    ShowHTML('  var minutos = saldo4 - parseInt(horas*60,10);');
    ShowHTML('  saldo = String(100+horas).substring(1) + ":" + String(100+minutos).substring(1);');
    ShowHTML('  ');
    ShowHTML('  document.Form.w_carga_diaria.value = saldo;');
    ShowHTML('}');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_posto_trabalho','Cargo','SELECT',1,1,18,'','0123456789');
      Validate('w_modalidade_contrato','Modalidade de contrata��o','SELECT',1,1,18,'','0123456789');
      if ($w_exige_cc) Validate('w_cc','Centro de custo','SELECT',1,1,18,1,1);
      Validate('w_unidade_lotacao','Unidade de lota��o','SELECT',1,1,18,'','0123456789');
      Validate('w_unidade_exercicio','Unidade de exerc�cio','SELECT',1,1,18,'','0123456789');
      Validate('w_localizacao','Localiza��o','SELECT',1,1,18,'','0123456789');
      Validate('w_sq_tipo_vinculo','V�nculo com a organiza��o','SELECT',1,1,10,'','1');
      Validate('w_remuneracao_inicial','Remunera��o inicial','VALOR','1',4,18,'','0123456789.,');
      Validate('w_matricula','Matr�cula','1','1','5','18','1','1');
      Validate('w_dt_ini','In�cio da vig�ncia','DATA','1','10','10','','0123456789/');
      Validate('w_data_atestado','Data do atestado m�dico ocupacional','DATA','1','10','10','','0123456789/');
      Validate('w_dias_experiencia','Quantidade de dias do per�odo de experi�ncia','1','1','1','6','','0123456789,');
      Validate('w_entrada_manha','Entrada manh�','HORA','','5','5','','0123456789:');
      Validate('w_saida_manha','Sa�da manh�','HORA','','5','5','','0123456789:');
      Validate('w_entrada_tarde','Entrada tarde','HORA','','5','5','','0123456789:');
      Validate('w_saida_tarde','Sa�da tarde','HORA','','5','5','','0123456789:');
      Validate('w_entrada_noite','Entrada noite','HORA','','5','5','','0123456789:');
      Validate('w_saida_noite','Sa�da noite','HORA','','5','5','','0123456789:');
      ShowHTML('  if ((theForm.w_entrada_manha.value!="" && theForm.w_saida_manha.value=="") || (theForm.w_entrada_manha.value=="" && theForm.w_saida_manha.value!="") || (theForm.w_entrada_tarde.value!="" && theForm.w_saida_tarde.value=="") || (theForm.w_entrada_tarde.value=="" && theForm.w_saida_tarde.value!="") || (theForm.w_entrada_noite.value!="" && theForm.w_saida_noite.value=="") || (theForm.w_entrada_noite.value=="" && theForm.w_saida_noite.value!="")) {');
      ShowHTML('    alert("Informe ambos os hor�rios de cada turno ou nenhum deles!"); ');
      ShowHTML('    return false;');
      ShowHTML('  }');
      ShowHTML('  if (theForm.w_entrada_manha.value!="") {');
      CompHora('w_entrada_manha','Entrada manh�','<','w_saida_manha','Sa�da manh�');
      ShowHTML('  }');
      ShowHTML('  if (theForm.w_entrada_tarde.value!="") {');
      CompHora('w_entrada_tarde','Entrada tarde','<','w_saida_tarde','Sa�da tarde');
      ShowHTML('    if (theForm.w_entrada_manha.value!="") {');
      CompHora('w_saida_manha','Sa�da manh�','<','w_entrada_tarde','Entrada tarde');
      ShowHTML('    }');
      ShowHTML('  }');
      ShowHTML('  if (theForm.w_entrada_noite.value!="") {');
      CompHora('w_entrada_noite','Entrada noite','<','w_saida_noite','Sa�da noite');
      ShowHTML('    if (theForm.w_entrada_tarde.value!="") {');
      CompHora('w_saida_tarde','Sa�da tarde','<','w_entrada_noite','Entrada noite');
      ShowHTML('    } else if (theForm.w_entrada_manha.value!="") {');
      CompHora('w_saida_manha','Sa�da manh�','<','w_entrada_noite','Entrada noite');
      ShowHTML('    }');
      ShowHTML('  }');
      CompData('w_data_atestado','Data do atestado m�dico ocupacional','<=','w_dt_ini','In�cio da vig�ncia');
      Validate('w_observacao_beneficios','Observa��es dos benef�cios','TEXTAREA','','','2000','1','1');
      if($w_seguro_saude=='S'){
        Validate('w_plano_saude','Tipo de plano de saude','','1','2','30','1','');
      }
      if($w_seguro_odonto=='S'){
        Validate('w_plano_odonto','Tipo de plano odontol�gico','','1','2','30','1','');
      }
      if($w_seguro_vida=='S'){
        Validate('w_plano_vida','Tipo de plano do seguro de vida','','1','2','30','1','');
      }


      if ($O=='A' && Nvl($w_dt_fim,'')>'') {
        Validate('w_dt_fim','Fim da vig�ncia','DATA','1','10','10','','0123456789/');
      } elseif ($O=='I') {
        Validate('w_dt_fim','Fim da vig�ncia','DATA','','10','10','','0123456789/');
      }
      if (!($O=='A' && Nvl($w_dt_fim,'')=='')) {
        CompData('w_dt_ini','In�cio da vig�ncia','<=','w_dt_fim','Fim da vig�ncia');
      }
//      Validate('w_banco_horas_saldo','Saldo inicial do banco de horas','HORAS','1','5','8','','0123456789-:');
//      Validate('w_banco_horas_data','Data do saldo inicial','DATA','1','10','10','','0123456789/');
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
    BodyOpen('onLoad=this.focus();');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&w_usuario='.$w_usuario.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Matr�cula','matricula').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome_resumido').'</td>');
    if ($w_exige_cc) ShowHTML('          <td><b>'.LinkOrdena('Centro de Custo','cd_centro_custo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Modalidade','nm_modalidade_contrato').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Exerc�cio','local').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ramal','ramal').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('In�cio','inicio').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Fim','fim').'</td>');
    ShowHTML('          <td class="remover"><b> Opera��es </td>');
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
        if ($w_exige_cc) ShowHTML('        <td>'.exibeSolic($w_dir,f($row,'centro_custo'),f($row,'dados_cc')).'</td>');
        ShowHTML('        <td align="left">'.f($row,'nm_modalidade_contrato').'</td>');
        ShowHTML('        <td align="left">'.ExibeUnidade('../',$w_cliente,f($row,'local'),f($row,'sq_unidade_exercicio'),$TP).'</td>');
        ShowHTML('        <td align="center">'.Nvl(f($row,'ramal'),'---').'</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'inicio')).'</td>');
        ShowHTML('        <td align="center">'.Nvl(FormataDataEdicao(f($row,'fim')),'---').'</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Alterar registro">AL</A>&nbsp');
        if (Nvl(f($row,'fim'),'')=='') {
          ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Encerrar contrato">CO</A>&nbsp');
        }
        //ShowHTML('          <A class="hl" onClick=javascript:window.open("location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'\',\'Geo\',\'toolbar=no,resizable=yes,width=780,height=550,top=20,left=10,scrollbars=yes\')" title="Sele��o de coordenadas geogr�ficas."></A>&nbsp'));
        $TP = 'Percentual de Desempenho';
        ShowHTML('          <a class="SS" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS($w_dir,$w_pagina.'Desempenho'.'&O=L&w_chave='.f($row,'chave').'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET')).'\',\'CronogramaPrestacao\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Percentual de desempenho.">PD</a>&nbsp');
        ShowHTML('          <a class="SS" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS($w_dir,$w_pagina.'Remuneracao'.'&O=L&w_chave='.f($row,'chave').'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET')).'\',\'CronogramaPrestacao\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Controle de remunera��o.">SL</a>&nbsp');
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
      $sql = new db_getGPContrato; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,$w_usuario,null,null,null,null,null,null,null,null,$w_chave,null);
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
    ShowHTML('<INPUT type="hidden" name="w_minutos_diarios" value="'.$w_minutos_diarios.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('<tr><td colspan="3"><br><br><fieldset class="rh_fieldset">');
    ShowHTML('    <table width="97%" border="0"><tr>');
    ShowHTML('        <tr valign="top">');
    SelecaoCargo('<u>C</u>argo:','C','Selecione o cargo.',$w_posto_trabalho,null,'w_posto_trabalho',null,null);
    SelecaoModalidade('M<u>o</u>dalidade de contrata��o:','O',null,$w_modalidade_contrato,null,'w_modalidade_contrato',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'&SG='.$SG.'&O='.$O.'\'; document.Form.w_troca.value=\'w_modalidade_contrato\'; document.Form.submit();"');
    if (Nvl($w_modalidade_contrato,'')>'') {
      $sql = new db_getGPModalidade; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_modalidade_contrato,null,null,null,null,null);
      foreach ($RS as $row){
        if (f($row,'username')=='P') {
          $w_username_pessoa = 'S';
        }
        if(Nvl(f($row,'ferias'),'') == 'S'){
          $ferias = 'S';
        }elseif(Nvl(f($row,'ferias'),'') == 'N'){
          $ferias = 'N';
        }else{
          $ferias = '';
        }
        if(trim(Nvl(f($row,'horas_extras'),'')) == 'S'){
          $extras = 'S';
        }elseif(trim(Nvl(f($row,'horas_extras'),'')) == 'N'){
          $extras = 'N';
        }else{
          $extras = '';
        }
        if(Nvl(f($row,'username'),'') == 'S'){
          $username = 'S';
        }elseif(Nvl(f($row,'username'),'') == 'N'){
          $username = 'N';
        }else{
          $username = '';
        }
      }
    }
    if ($w_exige_cc){
      ShowHTML('        <tr valign="top">');
      SelecaoProjeto('<u>C</u>entro de Custo:','C','Selecione o centro de custo do contrato na rela��o.',$w_cc,$_SESSION['SQ_PESSOA'] ,f($RS_Parametro,'vinculacao_contrato'),null,null,null,'w_cc','RHCC',null,2,2);
    }
    ShowHTML('        <tr valign="top">');
    SelecaoUnidade('Unidade de <U>l</U>ota��o:','L',null,$w_unidade_lotacao,null,'w_unidade_lotacao',null,null,3);
    ShowHTML('        <tr valign="top">');
    SelecaoUnidade('Unidade de <U>e</U>xerc�cio:','E',null,$w_unidade_exercicio,null,'w_unidade_exercicio',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'&SG='.$SG.'&O='.$O.'&w_usuario='.$w_usuario.'\'; document.Form.w_troca.value=\'w_localizacao\'; document.Form.submit();"',3);
    ShowHTML('        <tr valign="top">');
    SelecaoLocalizacao('Locali<u>z</u>a��o:','Z',null,$w_localizacao,Nvl($w_unidade_exercicio,0),'w_localizacao',null,null,3);
    ShowHTML('        <tr valign="top">');
    if (Nvl($w_dt_fim,'')>'') {
      ShowHTML('<INPUT type="hidden" name="w_sq_tipo_vinculo" value="'.$w_sq_tipo_vinculo.'">');
    } else {
      SelecaoVinculo('<u>T</u>ipo de v�nculo:','T',null,$w_sq_tipo_vinculo,null,'w_sq_tipo_vinculo','S','F�sica','S',null,null,3);
    }
    ShowHTML('        <tr valign="top">');
    ShowHTML('      <td><b><u>R</u>emunera��o inicial:</b><br>');
    ShowHTML('      <input accesskey="R" class="sti" type="text" name="w_remuneracao_inicial" size="10" maxlength="10" onKeyDown="FormataValor(this,18,2,event);" value="'.Nvl($w_remuneracao_inicial,'').'""/>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td><b><u>M</u>atr�cula:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_matricula" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_matricula.'"></td>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td><b>Data do atestado m�dico ocupacional:</b><br><input type="text" name="w_data_atestado" title="Data do atestado m�dico ocupacional na admiss�o do colaborador." class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data_atestado.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">');
    ShowHTML('          <td><b><u>I</u>n�cio da vig�ncia:</b><br><input accesskey="I" type="text" name="w_dt_ini" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_dt_ini.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td><b>N�mero de dias do contrato de experi�ncia:</b><br><input type="text" name="w_dias_experiencia" title="" class="STI" SIZE="3" MAXLENGTH="3" VALUE="'.$w_dias_experiencia.'">');
    if (!($O=='A' && Nvl($w_dt_fim,'')=='')) {
      ShowHTML('              <td><b><u>F</u>im da vig�ncia:</b><br><input accesskey="F" type="text" name="w_dt_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_dt_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">');
    }
    ShowHTML('</table></fieldset>');
    //Benef�cios do colaborador
    ShowHTML('<tr><td colspan="3"><br><br><fieldset class="rh_fieldset"><legend><big>Benef�cios</big></legend>');
    ShowHTML('  <table>');
    ShowHTML('    <tr>');
    ShowHTML('<td><b>Aux�lio Alimenta��o/Refei��o:</td>');
    ShowHTML('    <tr><td>');
    ShowHTML('      <input type="radio" '.($w_vale_refeicao=='A'?'checked':'').' name="w_vale_refeicao" value="A">Alimenta��o');
    ShowHTML('      <input type="radio" '.($w_vale_refeicao=='R'?'checked':'').' name="w_vale_refeicao" value="R">Refei��o');
    ShowHTML('      <input type="radio" '.($w_vale_refeicao=='N' || $w_vale_refeicao=='' ?'checked':'').' name="w_vale_refeicao" value="N">Nenhum');
    ShowHTML('</td>');
    ShowHTML('    </tr>');

    ShowHTML('    <tr>');
    ShowHTML(MontaRadioNS('<br><b>O colaborador � optante pelo vale transporte?</b>',$w_vale_transporte,'w_vale_transporte','O colaborador � optante pelo vale transporte?',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_vale_transporte\'; document.Form.submit();"'));
    ShowHTML('    <tr>');
    ShowHTML(MontaRadioNS('<br><b>O colaborador � optante pelo plano de sa�de?</b>',$w_seguro_saude,'w_seguro_saude','O colaborador � optante pelo plano de sa�de?',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_seguro_saude\'; document.Form.submit();"'));
    ShowHTML('    <tr><td><b>Tipo do plano:</b><br>');
    ShowHTML('       <input size="30" maxlength="30" class="STI" name="w_plano_saude" value="'.$w_plano_saude.'" type="text"/></td>');
    ShowHTML('    </tr>');
    ShowHTML('    <tr>');
    ShowHTML(MontaRadioNS('<br><b>O colaborador � optante pelo plano odontol�gico?</b>',$w_seguro_odonto,'w_seguro_odonto','O colaborador � optante pelo plano odontol�gico?',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_seguro_odonto\'; document.Form.submit();"'));
    ShowHTML('    <tr><td><b>Tipo do plano:</b><br>');
    ShowHTML('       <input size="30" maxlength="30" class="STI" name="w_plano_odonto" value="'.$w_plano_odonto.'" type="text"/></td>');
    ShowHTML('    </tr>');
    ShowHTML('    <tr>');
    ShowHTML(MontaRadioNS('<br><b>O colaborador � optante pelo seguro de vida?</b>',$w_seguro_vida,'w_seguro_vida','O colaborador � optante pelo seguro de vida?',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_seguro_vida\'; document.Form.submit();"'));
    ShowHTML('    <tr><td><b>Tipo do plano:</b><br>');
    ShowHTML('       <input size="30" maxlength="30" class="STI" name="w_plano_vida" value="'.$w_plano_vida.'" type="text"/></td>');
    ShowHTML('    </tr>');
    ShowHTML('    <tr>');
    ShowHTML('        <td valign="top"><b>Observa��es:</b><br><textarea title="Observa��o acerca dos benef�cios." type="text" name="w_observacao_beneficios" class="sti" cols="51" rows="5" MAXLENGTH="255">'.$w_observacao_beneficios.'</textarea></td>');
    ShowHTML('  </table>');
    ShowHTML('  </fieldset><br><br></td></tr>');


    //Informa��es da folha de ponto di�ria
    $sql = new db_getGPFolhaPontoDiario; //$RSFolha = $sql->getInstanceOf($dbms,$w_contrato,null,null);
    ShowHTML('<tr><td colspan="3"><fieldset class="rh_fieldset"><legend><big>Jornada de trabalho</big></legend><table width="100%">');
    ShowHTML('  <tr><td colspan="4"><br></td></tr>');
    ShowHTML('  <tr valign="middle" align="center">');
    ShowHTML('    <td width="24%"><fieldset class="rh_fieldset"><legend>Manh�</legend>');
    ShowHTML('      <table><tr>');
    ShowHTML('      <td width="60%"><b>Entrada:</b><br>');
    ShowHTML('      <input class="sti" type="text" id="w_entrada_manha" name="w_entrada_manha" size="5" maxlength="5" onKeyDown="FormataHora(this,event);" value="'.Nvl($w_entrada_manha,'').'" onBlur="calculaDia();"/>');
    ShowHTML('      <td><b>Sa�da:</b><br>');
    ShowHTML('    <input class="sti" type="text" size="5" '.$w_Disabled.' name="w_saida_manha" maxlength="5" value="'.Nvl($w_saida_manha,'').'" onKeyDown="FormataHora(this,event);"/ onBlur="calculaDia();">');
    ShowHTML('      </table></fieldset>');
    ShowHTML('    <td width="24%"><fieldset class="rh_fieldset"><legend>Tarde</legend>');
    ShowHTML('      <table><tr>');
    ShowHTML('      <td width="60%"><b>Entrada:</b><br>');
    ShowHTML('      <input id="w_entrada_tarde" name="w_entrada_tarde" class="sti" type="text" size="5" maxlength="5" value="'.Nvl($w_entrada_tarde,'').'" onKeyDown="FormataHora(this,event);" onBlur="calculaDia();"/>');
    ShowHTML('      <td><b>Sa�da:</b><br>');
    ShowHTML('      <input id="w_saida_tarde" name="w_saida_tarde" class="sti" type="text" size="5" maxlength="5" value="'.Nvl($w_saida_tarde,'').'" onKeyDown="FormataHora(this,event);" onBlur="calculaDia();"/>');
    ShowHTML('      </table></fieldset>');
    ShowHTML('    <td width="24%"><fieldset class="rh_fieldset"><legend>Noite</legend>');
    ShowHTML('      <table>');
    ShowHTML('      <td width="60%"><b>Entrada:</b><br>');
    ShowHTML('      <input id="w_entrada_noite" name="w_entrada_noite" class="sti" type="text" size="5" maxlength="5" value="'.Nvl($w_entrada_noite,'').'" onKeyDown="FormataHora(this,event);" onBlur="calculaDia();"/>');
    ShowHTML('      <td><b>Sa�da:</b><br>');
    ShowHTML('      <input id="w_saida_noite" name="w_saida_noite" class="sti" type="text" size="5" maxlength="5" value="'.Nvl($w_saida_noite,'').'" onKeyDown="FormataHora(this,event);" onBlur="calculaDia();"/>');
    ShowHTML('      </table></fieldset>');
    ShowHTML('    <td width="24%"><fieldset class="rh_fieldset"><legend>Carga hor�ria di�ria</legend>');
    ShowHTML('      <table><tr>');
    ShowHTML('      <td width="100%">&nbsp;<br><input readonly id="w_carga_diaria" name="w_carga_diaria" class="stih" type="text" size="8" maxlength="8" value="'.Nvl($w_carga_diaria,'00:00').'" style="text-align:center;" onKeyUp="SaltaCampo(this.form.name,this,5,event);" />');
    ShowHTML('      </table></fieldset>');
    ShowHTML('  </tr>');
    ShowHTML('  <tr><td valign="middle" colspan="4"><fieldset class="rh_fieldset"><legend>Fim de semana</legend>');
    ShowHTML('  <table width="100%"><tr valign="top">');
    ShowHTML(MontaRadioNS('<b>O expediente � extendido aos s�bados?</b>',$w_sabado,'w_sabado'));
    ShowHTML(MontaRadioNS('<b>O expediente � extendido aos domingos?</b>',$w_domingo,'w_domingo'));
    ShowHTML('  </table></fieldset>');
    ShowHTML('  </td></tr>');
    ShowHTML('  <tr><td valign="middle" colspan="4"><fieldset class="rh_fieldset"><legend>Banco de horas</legend>');
    ShowHTML('  <table width="100%"><tr valign="top">');
    ShowHTML('      <td width="50%"><b>Saldo inicial do banco de horas:</b><br><input id="w_banco_horas_saldo" name="w_banco_horas_saldo" class="sti" type="text" size="8" maxlength="8" value="'.Nvl($w_banco_horas_saldo,'').'" onKeyDown="FormataHora(this,event);" />');
    ShowHTML('      <td width="50%"><b>Data do saldo inicial:</b><br><input id="w_banco_horas_data" name="w_banco_horas_data" class="sti" type="text" size="10" maxlength="10" value="'.Nvl($w_banco_horas_data,'').'" onKeyDown="FormataData(this,event);" />');
    ShowHTML('  </table>');
    ShowHTML('  </td></tr>');
    //ShowHTML('</table></td></tr>');
    ShowHTML('  <tr valign="top">');
    if(Nvl($username,'')!='S' && Nvl($username,'')!='N'){
      ShowHTML('          '.MontaRadioSN('<b>Cria e bloqueia username na entrada e saida do colaborador?</b>',$w_username,'w_username'));
    }else{
      ShowHTML('<input type="hidden" name="w_username" value="'.$username.'"/>');
    }
    if(Nvl($ferias,'')!='S' && Nvl($ferias,'')!='N'){
      ShowHTML('          '.MontaRadioSN('<b>Esta modalidade permite gozo de f�rias?</b>',$w_ferias,'w_ferias'));
    }else{
      ShowHTML('<input type="hidden" name="w_ferias" value="'.$ferias.'"/>');
    }
    if(Nvl($extras,'')!='S' && Nvl($extras,'')!='N'){
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td>'.MontaRadioSN('<b>Esta modalidade permite o cumprimento de horas extras?</b>',$w_horas_extras,'w_horas_extras'));
    }else{
      ShowHTML('<input type="hidden" name="w_horas_extras" value="'.$extras.'"/>');
    }
    if ($w_username_pessoa=='S') {
      ShowHTML('        <tr valign="top">');
      ShowHTML('        <td colspan="3" valign="top"><input type="checkbox" name="w_username_pessoa" value="S"><b>Criar username para este colaborador?</b>');
    }
    ShowHTML('</table><br></td></tr>');
    ShowHTML('      <tr><td colspan=5><br><br><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
    ShowHTML('        <td><b><u>F</u>im da vig�ncia:</b><br><input accesskey="F" type="text" name="w_dt_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_dt_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td></tr>');
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
// Rotina de visualiza��o
// -------------------------------------------------------------------------
function Visual() {
  extract($GLOBALS);
  $w_chave  = nvl($_REQUEST['w_chave'],$_REQUEST['w_sq_pessoa']);
  $w_tipo   = upper(trim($_REQUEST['w_tipo']));
  if ($w_tipo=='PDF') {
    headerpdf('Ficha funcional',$w_pag);
    $w_embed = 'WORD';
  } elseif ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Ficha funcional',0);
    $w_embed = 'WORD';
  } else {
    Cabecalho();
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Ficha funcional'.'</TITLE>');
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpenClean('onLoad=\'this.focus();\'');
    if ($w_tipo!='WORD') CabecalhoRelatorio($w_cliente,'Ficha funcional',4,$w_chave);
    $w_embed="HTML";
  }
  if ($w_embed!='WORD') ShowHTML('<center><B><font size=1>Clique <span class="lk"><a class="hl" href="javascript:history.back(1);">aqui</a> para voltar � tela anterior</span></font></b></center>');
  // Chama a rotina de visualiza��o dos dados do projeto, na op��o 'Listagem'
  ShowHTML(VisualFicha($w_cliente,$w_chave,'L',$w_embed));
  if ($w_embed!='WORD') ShowHTML('<center><B><font size=1>Clique <span class="lk"><a class="hl" href="javascript:history.back(1);">aqui</a> para voltar � tela anterior</span></font></b></center>');
  ScriptOpen('JavaScript');
  ShowHTML('  var comando, texto;');
  ShowHTML('  if (window.name!="content") {');
  ShowHTML('    $(".lk").html(\'<a class="hl" href="javascript:window.close(); opener.focus();">aqui</a> fechar esta janela\');');
  ShowHTML('  }');
  ScriptClose();
  if     ($w_tipo=='PDF')  RodapePDF();
  elseif ($w_tipo!='WORD') Rodape();
}

// =========================================================================
// Rotina de tela de exibi��o do colaborador
// -------------------------------------------------------------------------
function Visual1() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_sq_pessoa  =  $_REQUEST['w_sq_pessoa'];
  $sql = new db_getGPColaborador; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_sq_pessoa,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
  foreach ($RS as $row) {$RS = $row; break;}
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<TITLE>Colaborador</TITLE>');
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=this.focus();');
  $TP = 'Dados coloborador';
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" width="100%">');
  ShowHTML('<tr><td>');
  ShowHTML('    <table width="99%" border="0">');
  ShowHTML('      <tr><td><b>Nome:</b></td>');
  ShowHTML('        <td><font size=2>'.f($RS,'nome').' </td></tr>');
  ShowHTML('      <tr><td><b>Nome resumido:</td>');
  ShowHTML('        <td><font size=2>'.f($RS,'nome_resumido').'</td></tr>');
  if (Nvl(f($RS,'email'),'')>'') {
    ShowHTML('    <tr><td><b>e-Mail:</b></td>');
    ShowHTML('      <td><A class="hl" HREF="mailto:'.f($RS,'email').'">'.f($RS,'email').'</a></td></tr>');
  } else {
    ShowHTML('    <tr><td><b>e-Mail:</b></td>');
    ShowHTML('      <td>---</td></tr>');
  }
  ShowHTML('      <tr><td colspan="2"><br><font size="2"><b>LOTA��O<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');

  ShowHTML('      <tr><td><b>Unidade:</b></td>');
  ShowHTML('        <td>'.f($RS,'unidade').' ('.f($RS,'sigla').')</td></tr>');
  if (Nvl(f($RS,'email_unidade'),'')>'') {
    ShowHTML('    <tr><td><b>e-Mail da unidade:</b></td>');
    ShowHTML('      <td><A class="hl" HREF="mailto:'.f($RS,'email_unidade').'">'.f($RS,'email_unidade').'</a></b></td>');
  } else {
    ShowHTML('    <tr><td><b>e-Mail da unidade:</b></td>');
    ShowHTML('      <td>---</td></tr>');
  }
  ShowHTML('      <tr><td><b>Localiza��o:</b></td>');
  ShowHTML('        <td>'.f($RS,'localizacao').' </td></tr>');
  ShowHTML('      <tr><td><b>Endere�o:</b></td>');
  ShowHTML('        <td>'.f($RS,'endereco').'</td></tr>');
  ShowHTML('      <tr><td><b>Cidade:</b></td>');
  ShowHTML('        <td>'.f($RS,'cidade').'</td></tr>');
  ShowHTML('      <tr><td><b>Telefone:</b></td>');
  ShowHTML('        <td>'.Nvl(f($RS,'telefone'),'---').' </td></tr>');
  ShowHTML('      <tr><td><b>Ramal:</b></td>');
  ShowHTML('        <td>'.Nvl(f($RS,'ramal'),'---').'</td></tr>');
  ShowHTML('      <tr><td><b>Telefone 2:</b></td>');
  ShowHTML('        <td>'.Nvl(f($RS,'telefone2'),'---').'</td></tr>');
  ShowHTML('      <tr><td><b>Fax:</b></td>');
  ShowHTML('        <td>'.Nvl(f($RS,'fax'),'---').'</td></tr>');
  ShowHTML('  </td>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  Estrutura_Texto_Fecha();
}

// =========================================================================
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=this.focus();');
  AbreSessao();
  switch ($SG) {
    case 'COINICIAL':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $SQL = new dml_putGPColaborador; $SQL->getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_sq_pessoa'],null,null,null,
        null,null,null,null,null,null,null,null,null,null,null,null,null);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=P&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'GPFAMILIA':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        // Executa a opera��o no banco de dados
        //exit('essa aqui');
        //exibeArray($_REQUEST);
        $SQL = new dml_putGPFamiliares; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_sq_pessoa'],$w_cliente,$_REQUEST['w_usuario'],
              $_REQUEST['w_cpf'],$_REQUEST['w_nome'],$_REQUEST['w_nome_resumido'],$_REQUEST['w_nascimento'],$_REQUEST['w_sexo'],
              $_REQUEST['w_parentesco'],$_REQUEST['w_seguro_saude'],$_REQUEST['w_seguro_odonto'],$_REQUEST['w_seguro_vida'],
              $_REQUEST['w_imposto_renda']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'GPPENSAO':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $SQL = new dml_PutGPPensionista; $SQL->getInstanceOf($dbms, $O, $w_restricao, $_REQUEST['w_sq_pessoa'], $w_cliente, $w_usuario, $w_sq_pessoa,
              $_REQUEST['w_cpf'], $_REQUEST['w_nome'], $_REQUEST['w_nome_resumido'], $_REQUEST['w_sexo'], $_REQUEST['w_rg_numero'], $_REQUEST['w_rg_emissao'], $_REQUEST['w_rg_emissor'],
              $_REQUEST['w_ddd'], $_REQUEST['w_nr_telefone'], $_REQUEST['w_nr_fax'], $_REQUEST['w_nr_celular'], $_REQUEST['w_sq_agencia'], $_REQUEST['w_operacao'], $_REQUEST['w_nr_conta'],
              $_REQUEST['w_tipo_pensao'], $_REQUEST['w_valor'], $_REQUEST['w_dt_inicio'], $_REQUEST['w_dt_fim'], $_REQUEST['w_observacao']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'CODOCUM':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $SQL = new dml_putGPColaborador; $SQL->getInstanceOf($dbms,$O,$w_cliente,$w_usuario,$_REQUEST['w_ctps_numero'],$_REQUEST['w_ctps_serie'],$_REQUEST['w_ctps_emissor'],
              $_REQUEST['w_ctps_emissao'],$_REQUEST['w_pis_pasep'],$_REQUEST['w_pispasep_numero'],$_REQUEST['w_pispasep_cadastr'],
              $_REQUEST['w_te_numero'],$_REQUEST['w_te_zona'],$_REQUEST['w_te_secao'],$_REQUEST['w_reservista_numero'],
              $_REQUEST['w_reservista_csm'],$_REQUEST['w_tipo_sangue'],$_REQUEST['w_doador_sangue'],$_REQUEST['w_doador_orgaos'],
              $_REQUEST['w_observacoes']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=P&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'COCONTR':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if (Nvl($_REQUEST['w_ativo'],0)>0 && Nvl($_REQUEST['w_dt_fim'],'')=='') {
          ScriptOpen('JavaScript');
          ShowHTML('alert(\'J� existe contrato ativo para este colaborador, n�o sendo poss�vel uma nova inclus�o\');');
          ScriptClose();
          retornaFormulario('w_dt_fim');
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
            $sql = new db_getGPContrato; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,$w_usuario,null,null,null,null,null,null,$_REQUEST['w_dt_ini'],$_REQUEST['w_dt_fim'],null,null);
            if(count($RS)>0) {
              ScriptOpen('JavaScript');
              ShowHTML('alert(\'J� existe contrato cadastrado para o per�odo informado!\');');
              ScriptClose();
              retornaFormulario('w_dt_ini');
              exit;
            }
          }
          $SQL = new dml_putGPContrato; $SQL->getInstanceOf($dbms,$O,
                $w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_cc'],$w_usuario,$_REQUEST['w_posto_trabalho'],$_REQUEST['w_modalidade_contrato'],
                $_REQUEST['w_unidade_lotacao'],$_REQUEST['w_unidade_exercicio'],$_REQUEST['w_localizacao'],$_REQUEST['w_matricula'],
                $_REQUEST['w_dt_ini'],$_REQUEST['w_dt_fim'],$_REQUEST['w_username'],$_REQUEST['w_ferias'],$_REQUEST['w_horas_extras'],
                $_REQUEST['w_sq_tipo_vinculo'],$_REQUEST['w_entrada_manha'],$_REQUEST['w_saida_manha'],
                $_REQUEST['w_entrada_tarde'],$_REQUEST['w_saida_tarde'],$_REQUEST['w_entrada_noite'],$_REQUEST['w_saida_noite'],
                $_REQUEST['w_sabado'],$_REQUEST['w_domingo'],
                $_REQUEST['w_banco_horas_saldo'],$_REQUEST['w_banco_horas_data'], $_REQUEST['w_remuneracao_inicial'], $_REQUEST['w_data_atestado'],$_REQUEST['w_dias_experiencia'],
                $_REQUEST['w_vale_refeicao'],$_REQUEST['w_vale_transporte'],
                $_REQUEST['w_seguro_saude'],$_REQUEST['w_seguro_odonto'],$_REQUEST['w_seguro_vida'],$_REQUEST['w_plano_saude'],$_REQUEST['w_plano_odonto'],$_REQUEST['w_plano_vida'],
                $_REQUEST['w_observacao_beneficios']);
          if (!(strpos('I',$O)===false)) {
            $sql = new db_getGPModalidade; $RS = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['w_modalidade_contrato'],null,null,null,null,null);
            if ((Nvl(f($RS,'username'),'')=='S') || (Nvl(f($RS,'username'),'')=='P' && $_REQUEST['w_username_pessoa']=='S')) {
              $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,null,null);
              $SQL = new dml_putSiwUsuario; $SQL->getInstanceOf($dbms,'I',$w_usuario,$w_cliente,f($RS,'nome'),f($RS,'nome_resumido'),$_REQUEST['w_cpf'],$_REQUEST['w_sexo'],
                    f($RS,'sq_tipo_vinculo'),'F�sica',$_REQUEST['w_unidade_lotacao'],$_REQUEST['w_localizacao'],
                    f($RS,'cpf'),f($RS,'email'),null,null,null);
              $SQL = new dml_putSiwUsuario; $SQL->getInstanceOf($dbms,'T',$w_usuario,null,null,null,
              null,null,null,null,null,null,null,null,null,null,null);
            }
          }
        }
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'CODES':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $sql = new db_getGPDesempenho; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_ano']);
        if(count($RS)>0 && $O=='I') {
          ScriptOpen('JavaScript');
          ShowHTML('alert(\'O percentual de desempenho para o ano de '.$_REQUEST['w_ano'].' j� foi informado.\');');
          ScriptClose();
          retornaFormulario('w_ano');
          exit;
        }
        $SQL = new dml_putGpDesempenho; $SQL->getInstanceOf($dbms, $_REQUEST['w_chave'], $_REQUEST['w_ano'], $_REQUEST['w_percentual'],$O);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$w_pagina.'Desempenho'.'&O=L&w_chave='.$_REQUEST['w_chave'].'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'COREM':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $sql = new db_getGPAlteracaoSalario; //$RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_ano']);
        /*if(count($RS)>0 && $O=='I') {
         ScriptOpen('JavaScript');
         ShowHTML('alert(\'O percentual de AlteracaoSalario para o ano de '.$_REQUEST['w_ano'].' j� foi informado.\');');
         ScriptClose();
         retornaFormulario('w_ano');
         exit;
         }*/
        $SQL = new dml_putGpAlteracaoSalario; $SQL->getInstanceOf($dbms, $O, $_REQUEST['w_chave'],$_REQUEST['w_chave_aux'], $_REQUEST['w_data_alteracao'], $_REQUEST['w_novo_valor'], $_REQUEST['w_funcao'], $_REQUEST['w_motivo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$w_pagina.'REMUNERACAO'.'&O=L&w_chave='.$_REQUEST['w_chave'].'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
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
    case 'DESEMPENHO':        Desempenho();       break;
    case 'REMUNERACAO':       Remuneracao();      break;
    case 'CONTRATO':          Contrato();         break;
    case 'FAMILIARES':        Familiares();       break;
    case 'PENSAO':            Pensao();           break;
    case 'VISUAL':            Visual();           break;
    case 'GRAVA':             Grava();            break;
    default:
      Cabecalho();
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');
      BodyOpen('onLoad=this.focus();');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
      ShowHTML('<HR>');
      ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
      Rodape();
      break;
  }
}
?>
