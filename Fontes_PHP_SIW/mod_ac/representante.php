<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getBenef.php');
include_once($w_dir_volta.'classes/sp/db_getConvOutraParte.php');
include_once($w_dir_volta.'classes/sp/db_getConvOutroRep.php');
include_once($w_dir_volta.'classes/sp/dml_putAcordoPreposto.php');
include_once($w_dir_volta.'funcoes/selecaoPessoaOrigem.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoSexo.php');

// =========================================================================
//  /representante.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerencia o cadastramento de representantes legais e prepostos
// Mail     : alex@sbpi.com.br
// Criacao  : 06/02/2015 11:01
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

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par        = upper($_REQUEST['par']);
$P1         = nvl($_REQUEST['P1'],0);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);

$w_assinatura   = $_REQUEST['w_assinatura'];
$w_pagina       = 'representante.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_ac/';
$w_troca        = $_REQUEST['w_troca'];

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_TP       = RetornaTitulo($TP, $O);

// Recupera a configuração do serviço
$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,(($P2>0) ? $P2 : $w_menu));

// Se for sub-menu, pega a configuração do pai
if (f($RS_Menu,'ultimo_nivel')=='S') { 
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 

Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;
  if ($O=='') $O='L';
  $w_chave                  = $_REQUEST['w_chave'];
  $w_chave_aux              = $_REQUEST['w_chave_aux'];
  $w_cpf                    = $_REQUEST['w_cpf'];
  $w_sq_pessoa              = $_REQUEST['w_sq_pessoa'];
  $w_sq_pessoa_nm           = $_REQUEST['w_sq_pessoa_nm'];
  $w_sq_acordo_outra_parte  = $_REQUEST['w_sq_acordo_outra_parte'];   
  $w_outra_parte            = $_REQUEST['w_outra_parte'];
  $w_tipo                   = $_REQUEST['w_tipo'];
  $w_tipo_pessoa            = $_REQUEST['w_tipo_pessoa'];

  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'') {
    // Se for recarga da página
    $w_chave                   = $_REQUEST['w_chave'];
    $w_chave_aux               = $_REQUEST['w_chave_aux'];
    $w_nome                    = $_REQUEST['w_nome'];
    $w_nome_resumido           = $_REQUEST['w_nome_resumido'];
    $w_sexo                    = $_REQUEST['w_sexo'];
    $w_sq_pessoa_pai           = $_REQUEST['w_sq_pessoa_pai'];
    $w_nm_tipo_pessoa          = $_REQUEST['w_nm_tipo_pessoa'];
    $w_sq_tipo_vinculo         = $_REQUEST['w_sq_tipo_vinculo'];
    $w_nm_tipo_vinculo         = $_REQUEST['w_nm_tipo_vinculo'];
    $w_interno                 = $_REQUEST['w_interno'];
    $w_vinculo_ativo           = $_REQUEST['w_vinculo_ativo'];
    $w_rg_numero               = $_REQUEST['w_rg_numero'];
    $w_rg_emissor              = $_REQUEST['w_rg_emissor'];
    $w_rg_emissao              = $_REQUEST['w_rg_emissao'];
    $w_passaporte_numero       = $_REQUEST['w_passaporte_numero'];
    $w_sq_pais_passaporte      = $_REQUEST['w_sq_pais_passaporte'];
    $w_sq_pessoa_telefone      = $_REQUEST['w_sq_pessoa_telefone'];
    $w_ddd                     = $_REQUEST['w_ddd'];
    $w_nr_telefone             = $_REQUEST['w_nr_telefone'];
    $w_sq_pessoa_celular       = $_REQUEST['w_sq_pessoa_celular'];
    $w_nr_celular              = $_REQUEST['w_nr_celular'];
    $w_sq_pessoa_fax           = $_REQUEST['w_sq_pessoa_fax'];
    $w_nr_fax                  = $_REQUEST['w_nr_fax'];
    $w_email                   = $_REQUEST['w_email'];
    $w_sq_acordo_outra_parte   = $_REQUEST['w_sq_acordo_outra_parte'];
    $w_cargo                   = $_REQUEST['w_cargo'];   
  } else {
    if ($O=='L') {
      $sql = new db_getConvOutroRep; $RS1 = $sql->getInstanceOf($dbms, $w_chave, null, $w_sq_acordo_outra_parte, $w_tipo);
      $RS1 = SortArray($RS1, 'nome_resumido_ind', 'asc');
    } elseif ($O=='A' || $w_sq_pessoa>'' || $w_cpf>'') {
      // Recupera os dados da pessoa
      $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_sq_pessoa,null,$w_cpf,null,null,null,null,null,null,null,null,null,null, null, null, null, null);
      if (count($RS)) {
        foreach($RS as $row) {
          $w_sq_pessoa            = f($row,'sq_pessoa');
          $w_nome                 = f($row,'nm_pessoa');
          $w_nome_resumido        = f($row,'nome_resumido');
          $w_sexo                 = f($row,'sexo');
          $w_sq_pessoa_pai        = f($row,'sq_pessoa_pai');
          $w_tipo_pessoa          = f($row,'sq_tipo_pessoa');
          $w_nm_tipo_pessoa       = f($row,'nm_tipo_pessoa');
          $w_sq_tipo_vinculo      = f($row,'sq_tipo_vinculo');
          $w_nm_tipo_vinculo      = f($row,'nm_tipo_vinculo');
          $w_interno              = f($row,'interno');
          $w_vinculo_ativo        = f($row,'vinculo_ativo');
          $w_cpf                  = f($row,'cpf');
          $w_rg_numero            = f($row,'rg_numero');
          $w_rg_emissor           = f($row,'rg_emissor');
          $w_rg_emissao           = FormataDataEdicao(f($row,'rg_emissao'));
          $w_passaporte_numero    = f($row,'passaporte_numero');
          $w_sq_pais_passaporte   = f($row,'sq_pais_passaporte');
          $w_sq_pessoa_telefone   = f($row,'sq_pessoa_telefone');
          $w_ddd                  = f($row,'ddd');
          $w_nr_telefone          = f($row,'nr_telefone');
          $w_sq_pessoa_celular    = f($row,'sq_pessoa_celular');
          $w_nr_celular           = f($row,'nr_celular');
          $w_sq_pessoa_fax        = f($row,'sq_pessoa_fax');
          $w_nr_fax               = f($row,'nr_fax');
          $w_email                = f($row,'email');
          $sql = new db_getConvOutroRep;$RS1 = $sql->getInstanceOf($dbms, $w_chave, $w_sq_pessoa, $w_sq_acordo_outra_parte, $w_tipo);
          $RS1 = $RS1[0];
          $w_cargo = f($RS1, 'cargo');
        }
      } 
    } 
  } 
  Cabecalho();
  head();
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  if ($O!='L') {
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - '.(($w_tipo==1) ? 'Representantes legais': 'Contatos').'</TITLE>');
    ScriptOpen('JavaScript');
    Modulo();
    FormataCPF();
    checkBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if ($w_cpf=='') {
      // Se o beneficiário ainda não foi selecionado
      Validate('w_sq_pessoa_nm', 'Pessoa:', '', 1, 5, 100, '1', '1');
    } elseif ($O=='I' || $O=='A') {
      Validate('w_nome','Nome','1',1,5,60,'1','1');
      Validate('w_nome_resumido','Nome resumido','1',1,2,21,'1','1');
      Validate('w_sexo','Sexo','SELECT',1,1,1,'MF','');
      Validate('w_rg_numero','Identidade','1',(($w_tipo_pessoa==1) ? '1' : ''),2,30,'1','1');
      Validate('w_rg_emissor','Órgão expedidor','1',(($w_tipo_pessoa==1) ? '1' : ''),2,30,'1','1');      
      Validate('w_rg_emissao','Data de emissão','DATA','',10,10,'','0123456789/');
      ShowHTML('  if ((theForm.w_rg_numero.value+theForm.w_rg_emissao.value+theForm.w_rg_emissor.value)!="" && (theForm.w_rg_numero.value=="" || theForm.w_rg_emissor.value=="")) {');
      ShowHTML('     alert("Os campos identidade, data de emissão e órgão emissor devem ser informados em conjunto!");');
      ShowHTML('     theForm.w_rg_numero.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      Validate('w_passaporte_numero','Passaporte','1',(($w_tipo_pessoa==1) ? '' : '1'),1,20,'1','1');
      Validate('w_sq_pais_passaporte','País emissor','SELECT',(($w_tipo_pessoa==1) ? '' : '1'),1,10,'1','1');
      ShowHTML('  if ((theForm.w_passaporte_numero.value+theForm.w_sq_pais_passaporte[theForm.w_sq_pais_passaporte.selectedIndex].value)!="" && (theForm.w_passaporte_numero.value=="" || theForm.w_sq_pais_passaporte.selectedIndex==0)) {');
      ShowHTML('     alert("Os campos passaporte e país emissor devem ser informados em conjunto!");');
      ShowHTML('     theForm.w_passaporte_numero.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      Validate('w_ddd','DDD','1','1',2,4,'','0123456789');
      Validate('w_nr_telefone','Telefone','1',1,7,25,'1','1');
      Validate('w_nr_fax','Fax','1','',7,25,'1','1');
      Validate('w_nr_celular','Celular','1','',7,25,'1','1');
      Validate('w_email','E-Mail','1','1',4,60,'1','1');
      Validate('w_cargo','Cargo','1','',2,40,'1','1');
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } 
    ValidateClose();
    ScriptClose();
    ShowHTML('</HEAD>');
  } 
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (strpos('IA',$O)!==false && $w_cpf!='') {
    BodyOpenClean('onLoad=\'document.Form.w_nome.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'this.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">'); 
  if ($O=='L') { 
    $sql = new db_getConvOutraParte; $RS = $sql->getInstanceOf($dbms,$w_sq_acordo_outra_parte,$w_chave,$w_outra_parte,null);
    foreach($RS as $row) {
      ShowHTML('    <table width="100%" border="0">');        
      ShowHTML(' <tr><td>Outra parte: <b>'.f($row,'nm_pessoa').' </b><br><br>');
    }
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&w_tipo='.$w_tipo.'&w_sq_acordo_outra_parte='.$w_sq_acordo_outra_parte.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('        <a accesskey="F" class="ss" href="javascript:window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');       
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS1));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>CPF</font></td>');
    ShowHTML('          <td><b>Nome</font></td>');
    ShowHTML('          <td><b>Sexo</font></td>');
    ShowHTML('          <td><b>DDD</font></td>');
    ShowHTML('          <td><b>Telefone</font></td>');
    ShowHTML('          <td><b>Fax</font></td>');
    ShowHTML('          <td><b>Celular</font></td>');
    ShowHTML('          <td><b>e-Mail</font></td>');
    ShowHTML('          <td><b>Cargo</font></td>');
    ShowHTML('          <td class="remover"><b>Operações</font></td>');
    ShowHTML('        </tr>');
    if (!count($RS1)) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center">'.f($row,'cpf').'</td>');
        ShowHTML('        <td>'.f($row,'nome_resumido').'</td>');
        ShowHTML('        <td>'.f($row,'nm_sexo').'</td>');
        ShowHTML('        <td align="center">'.nvl(f($row,'ddd'),'---').'</td>');
        ShowHTML('        <td>'.nvl(f($row,'nr_telefone'),'---').'</td>');
        ShowHTML('        <td>'.nvl(f($row,'nr_fax'),'---').'</td>');
        ShowHTML('        <td>'.nvl(f($row,'nr_celular'),'---').'</td>');
        ShowHTML('        <td>'.nvl(f($row,'email'),'---').'</td>');
        ShowHTML('        <td>'.nvl(f($row,'cargo'),'---').'</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_tipo='.$w_tipo.'&w_outra_parte='.$w_outra_parte.'&w_sq_acordo_outra_parte='.$w_sq_acordo_outra_parte.'&w_chave_aux='.$w_cliente.'&w_sq_pessoa='.f($row,'sq_pessoa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_tipo='.$w_tipo.'&w_outra_parte='.$w_outra_parte.'&w_sq_acordo_outra_parte='.$w_sq_acordo_outra_parte.'&w_chave_aux='.$w_cliente.'&w_sq_pessoa='.f($row,'sq_pessoa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    }
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (strpos('IA',$O)!==false) {
    if ($w_cpf=='') {
      // Se o beneficiário ainda não foi selecionado
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
    ShowHTML('<INPUT type="hidden" name="w_sq_acordo_outra_parte" value="'.$w_sq_acordo_outra_parte.'">');
    ShowHTML('<INPUT type="hidden" name="w_tipo" value="'.$w_tipo.'">');
    ShowHTML('<INPUT type="hidden" name="w_tipo_pessoa" value="'.$w_tipo_pessoa.'">');
    
    if ($w_cpf=='') {
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
      ShowHTML('    <table border="0">');
      ShowHTML('        <tr><td colspan=2>Informe os dados abaixo e clique no botão "Selecionar" para continuar.</TD>');
      ShowHTML('        <tr valign="top">');
      SelecaoPessoaOrigem('<u>P</u>essoa:', 'P', 'Clique na lupa para selecionar a pessoa.', $w_sq_pessoa, null, 'w_sq_pessoa', null, null, 'onFocus="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_cpf\'; document.Form.submit();"', 1, 'w_identificador');
      if (nvl($w_sq_pessoa_nm,'')!='') {
        $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_sq_pessoa, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
        if (count($RS)) $RS_Benef = $RS[0];
        ShowHTML('        <td><b>'.((f($RS_Benef,'sq_tipo_pessoa')==1) ? 'CPF' : 'Cód. Estrangeiro').':</b><br><INPUT type="text" READONLY class="sti" name="w_cpf" SIZE=14 value="'.f($RS_Benef,'cpf').'">');
        ShowHTML('        <tr><td colspan=2>');
        ShowHTML('            <INPUT class="stb" TYPE="submit" NAME="Botao" VALUE="Selecionar" onClick="Botao.value=this.value; document.Form.action=\''.$w_dir.$w_pagina.$par.'&w_sq_acordo_outra_parte='.$w_sq_acordo_outra_parte.'\'">');
      }
      ShowHTML('      </table>');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_sq_pessoa" value="'.$w_sq_pessoa.'">');
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
      ShowHTML('    <table width="97%" border="0">');
      ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Identificação</td></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2"><table border="0" width="100%">');
      ShowHTML('          <tr valign="top">');
      ShowHTML('          <td>CPF:</font><br><b><font size=2>'.$w_cpf);
      ShowHTML('              <INPUT type="hidden" name="w_cpf" value="'.$w_cpf.'">');
      ShowHTML('          <tr valign="top">');
      ShowHTML('             <td><b><u>N</u>ome completo:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="40" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
      ShowHTML('             <td><b><u>N</u>ome resumido:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome_resumido" class="sti" SIZE="15" MAXLENGTH="21" VALUE="'.$w_nome_resumido.'"></td>');
      SelecaoSexo('Se<u>x</u>o:','X', null, $w_sexo, null, 'w_sexo', null, null);
      ShowHTML('          <tr valign="top">');
      ShowHTML('          <td><b><u>I</u>dentidade:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_rg_numero" class="sti" SIZE="14" MAXLENGTH="80" VALUE="'.$w_rg_numero.'"></td>');
      ShowHTML('          <td><b>Ór<u>g</u>ão emissor:</b><br><input '.$w_Disabled.' accesskey="G" type="text" name="w_rg_emissor" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_rg_emissor.'"></td>');
      ShowHTML('          <td><b>Data de <u>e</u>missão:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_rg_emissao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_rg_emissao.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      ShowHTML('          <tr valign="top">');
      ShowHTML('            <td><b>Passapo<u>r</u>te:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_passaporte_numero" class="sti" SIZE="15" MAXLENGTH="15" VALUE="'.$w_passaporte_numero.'"></td>');
      SelecaoPais('<u>P</u>aís emissor do passaporte:','P',null,$w_sq_pais_passaporte,null,'w_sq_pais_passaporte',null,null);
      ShowHTML('          </table>');
      ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Telefones e e-Mail</td></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
      ShowHTML('          <td><b><u>D</u>DD:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_ddd" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_ddd.'"></td>');
      ShowHTML('          <td><b>Te<u>l</u>efone:</b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_nr_telefone" class="sti" SIZE="20" MAXLENGTH="40" VALUE="'.$w_nr_telefone.'"> '.consultaTelefone($w_cliente).'</td>');
      ShowHTML('          <td title="Se o representante informar um número de fax, informe-o neste campo."><b>Fa<u>x</u>:</b><br><input '.$w_Disabled.' accesskey="X" type="text" name="w_nr_fax" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_nr_fax.'"></td>');
      ShowHTML('          <td title="Se o representante informar um celular institucional, informe-o neste campo."><b>C<u>e</u>lular:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_nr_celular" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_nr_celular.'"></td>');
      ShowHTML('          <tr><td colspan=4><b>e-<u>M</u>ail:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_email" class="sti" SIZE="40" MAXLENGTH="50" VALUE="'.$w_email.'"></td>');
      ShowHTML('          </table>');
      ShowHTML('          <tr valign="top">');
      ShowHTML('          <td><b><u>C</u>argo:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_cargo" class="sti" SIZE="40" MAXLENGTH="40" VALUE="'.$w_cargo.'"></td>');      
      ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
      ShowHTML('      <tr><td align="center" colspan="3">');
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
      ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&O=L&w_sq_acordo_outra_parte='.$w_sq_acordo_outra_parte.'&w_tipo='.$w_tipo.'&w_outra_parte='.$w_outra_parte.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
      ShowHTML('          </td>');
      ShowHTML('      </tr>');
      ShowHTML('    </table>');
      ShowHTML('    </TD>');
      ShowHTML('</tr>');
    } 
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
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
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);

  Cabecalho();
  head();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  BodyOpenClean('onLoad=this.focus();');
  if (strpos(substr($SG,3),'PREP')!==false || strpos(substr($SG,3),'REPRES')!==false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      if ($O=='I'){
        $sql = new db_getConvOutroRep; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_sq_pessoa'],$_REQUEST['w_sq_acordo_outra_parte'],$_REQUEST['w_tipo']);
        $RS=$RS[0];
        if (f($RS,'sq_pessoa')==$_REQUEST['w_sq_pessoa'] &&  Nvl($_REQUEST['w_sq_pessoa'],'')!='') {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: '.(($_REQUEST['w_tipo']==2) ? 'Representante' : 'Preposto').' já cadastrado!");');
          ShowHTML('  location.href="'.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&w_sq_acordo_outra_parte='.$_REQUEST['w_sq_acordo_outra_parte'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'";');
          ScriptClose();
          exit();
        }
      }
      $SQL = new dml_putAcordoPreposto; $SQL->getInstanceOf($dbms,$_REQUEST['O'],$_REQUEST['w_tipo'],$_REQUEST['w_chave'],
              $_REQUEST['w_sq_acordo_outra_parte'],$_REQUEST['w_sq_pessoa'],$_REQUEST['w_chave_aux'],$_REQUEST['w_cargo'],
              $_REQUEST['w_nome'],$_REQUEST['w_nome_resumido'],$_REQUEST['w_sexo'],
              $_REQUEST['w_rg_numero'],$_REQUEST['w_rg_emissao'],$_REQUEST['w_rg_emissor'],
              $_REQUEST['w_passaporte_numero'],$_REQUEST['w_sq_pais_passaporte'],
              $_REQUEST['w_ddd'],$_REQUEST['w_nr_telefone'],$_REQUEST['w_nr_fax'],$_REQUEST['w_nr_celular'],
              $_REQUEST['w_email']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&w_tipo='.$_REQUEST['w_tipo'].'&w_sq_acordo_outra_parte='.$_REQUEST['w_sq_acordo_outra_parte'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('  alert("Bloco de dados não encontrado: '.$SG.'");');
    ShowHTML('  history.back(1);');
    ScriptClose();
  } 
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
  case 'INICIAL':           Inicial();          break;
  case 'GRAVA':             Grava();            break;
  default:
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
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
    break;
  } 
} 
?>