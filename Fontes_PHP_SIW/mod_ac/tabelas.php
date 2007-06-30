<?
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getAgreeType.php');
include_once($w_dir_volta.'classes/sp/db_getTipoDocumento.php');
include_once($w_dir_volta.'classes/sp/db_getFormaPagamento.php');
include_once($w_dir_volta.'classes/sp/db_getMenuList.php');
include_once($w_dir_volta.'classes/sp/db_getMenuFormaPag.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/db_getACParametro.php');
include_once($w_dir_volta.'classes/sp/db_getLCModalidade.php');
include_once($w_dir_volta.'classes/sp/db_getLCFonteRecurso.php');
include_once($w_dir_volta.'classes/sp/db_getCTEspecificacaoTree.php');
include_once($w_dir_volta.'classes/sp/db_getCTEspecificacao.php');
include_once($w_dir_volta.'classes/sp/dml_putACParametro.php');
include_once($w_dir_volta.'classes/sp/dml_putLCModalidade.php');
include_once($w_dir_volta.'classes/sp/dml_putAgreeType.php');
include_once($w_dir_volta.'classes/sp/dml_putFormaPagamento.php');
include_once($w_dir_volta.'classes/sp/dml_putFormaPagamentoVinc.php');
include_once($w_dir_volta.'classes/sp/dml_putLCFonteRecurso.php');
include_once($w_dir_volta.'classes/sp/dml_putCTEspecificacao.php');
include_once($w_dir_volta.'funcoes/selecaoTipoAcordo.php');
include_once($w_dir_volta.'funcoes/selecaoCTEspecificacao.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');
include_once($w_dir_volta.'funcoes/selecaoAno.php');

// =========================================================================
//  /tabelas.php
// ------------------------------------------------------------------------
// Nome     : Billy Jones Leal dos Santos
// Descricao: Gerenciar tabelas básicas do módulo  
// Mail     : billy@sbpi.com.br
// Criacao  : 23/01/2005 11:00
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
//                   = T   : Vinculação
//                   = P   : Pesquisa
//                   = D   : Detalhes
//                   = N   : Nova solicitação de envio

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par            = strtoupper($_REQUEST['par']);
$P1             = $_REQUEST['P1'];
$P2             = $_REQUEST['P2'];
$P3             = nvl($_REQUEST['P3'],1);
$P4             = nvl($_REQUEST['P4'],$conPageSize);
$TP             = $_REQUEST['TP'];
$SG             = strtoupper($_REQUEST['SG']);
$R              = $_REQUEST['R'];
$O              = strtoupper($_REQUEST['O']);
$w_assinatura   = strtoupper($_REQUEST['w_assinatura']);
$w_pagina       = 'tabelas.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_ac/';
$w_troca        = $_REQUEST['w_troca'];
$w_copia        = $_REQUEST['w_copia'];
$p_ordena       = strtolower(trim($_REQUEST['p_ordena']));

if ($O=='') $O = 'L';

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';    break;
  case 'A': $w_TP=$TP.' - Alteração';   break;
  case 'E': $w_TP=$TP.' - Exclusão';    break;
  case 'P': $w_TP=$TP.' - Filtragem';   break;
  case 'C': $w_TP=$TP.' - Cópia';       break;
  case 'V': $w_TP=$TP.' - Envio';       break;
  case 'H': $w_TP=$TP.' - Herança';     break;
  case 'T': $w_TP=$TP.' - Vinculação';  break;  
  case 'T': $w_TP=$TP.' - Gerar ano';  break;
  default : $w_TP=$TP.' - Listagem'; 
}
// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);

// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$RS = db_getLinkSubMenu::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu='Existe';
} else {
  $w_submenu='';
} 

// Recupera a configuração do serviço
if ($P2>0)   $RS_Menu = db_getMenuData::getInstanceOf($dbms,$P2);
else         $RS_Menu = db_getMenuData::getInstanceOf($dbms,$w_menu);

// Se for sub-menu, pega a configuração do pai
if ($RS_Menu['ultimo_nivel']=='S') {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 
Main();
FechaSessao($dbms);
exit;
// =========================================================================
// Rotina de tipos de acordo
// -------------------------------------------------------------------------
function TipoAcordo() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_imagem         = 'images/Folder/SheetLittle.gif';
  $w_heranca        = $_REQUEST['w_heranca'];
  $w_sq_tipo_acordo = $_REQUEST['w_sq_tipo_acordo'];
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Tipos de Acordo</TITLE>');
  Estrutura_CSS($w_cliente);
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_sq_tipo_acordo_pai   = $_REQUEST['w_sq_tipo_acordo_pai'];
    $w_nome                 = $_REQUEST['w_nome'];
    $w_pessoa_fisica        = $_REQUEST['w_pessoa_fisica'];
    $w_cliente              = $_REQUEST['w_cliente'];
    $w_sigla                = $_REQUEST['w_sigla'];
    $w_ativo                = $_REQUEST['w_ativo'];
    $w_pessoa_fisica        = $_REQUEST['w_pessoa_fisica'];
    $w_prazo_indeterminado  = $_REQUEST['w_prazo_indeterminado'];
    $w_modalidade           = $_REQUEST['w_modalidade'];
  } elseif ($O!='L') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if ($O!='P' && $O!='H') {
      if ($w_heranca>'' || ($O!='I' && $w_troca=='')) {
        // Se for herança, atribui a chave da opção selecionada para w_sq_tipo_acordo
        if ($w_heranca>'') $w_sq_tipo_acordo=$w_heranca;
        $RS = db_getAgreeType::getInstanceOf($dbms,$w_sq_tipo_acordo,null,$w_cliente,'ALTERA');
        foreach($RS as $row) {
          $w_sq_tipo_acordo_pai   = f($row,'sq_tipo_acordo_pai');
          $w_nome                 = f($row,'nome');
          $w_sigla                = f($row,'sigla');
          $w_ativo                = f($row,'ativo');
          $w_pessoa_juridica      = f($row,'pessoa_juridica');
          $w_pessoa_fisica        = f($row,'pessoa_fisica');
          $w_prazo_indeterminado  = f($row,'prazo_indeterm');
          $w_modalidade           = f($row,'modalidade');
        }
      } elseif ($O=='A') {
        $RS = db_getAgreeType::getInstanceOf($dbms,$w_sq_tipo_acordo,null,$w_cliente,'ALTERA');
        foreach($RS as $row) {
          $w_sq_tipo_acordo_pai   = f($row,'sq_tipo_acordo_pai');
          $w_nome                 = f($row,'nome');
          $w_sigla                = f($row,'sigla');
          $w_ativo                = f($row,'ativo');
          $w_pessoa_juridica      = f($row,'pessoa_juridica');
          $w_pessoa_fisica        = f($row,'pessoa_fisica');
          $w_prazo_indeterminado  = f($row,'prazo_indeterm');
          $w_modalidade           = f($row,'modalidade');
        }
      } elseif ($w_troca>'') {
        $w_sq_tipo_acordo_pai   = $_REQUEST['w_sq_tipo_acordo_pai'];
        $w_nome                 = $_REQUEST['w_nome'];
        $w_pessoa_fisica        = $_REQUEST['w_pessoa_fisica'];
        $w_cliente              = $_REQUEST['w_cliente'];
        $w_sigla                = $_REQUEST['w_sigla'];
        $w_ativo                = $_REQUEST['w_ativo'];
        $w_pessoa_fisica        = $_REQUEST['w_pessoa_fisica'];
        $w_prazo_indeterminado  = $_REQUEST['w_prazo_indeterminado'];
        $w_modalidade           = $_REQUEST['w_modalidade'];
      } 
      if ($O=='I' || $O=='A') {
        Validate('w_nome','Nome','1','1','5','60','1','1');
        Validate('w_sigla','Sigla','1','1','2','10','1','1');
        ShowHTML('if (theForm.w_prazo_indeterminado[0].checked) {');
        ShowHTML('  alert(\'Opção escolhida no campo prazo indetermindo é inválido!\')');
        ShowHTML('  return false;');
        ShowHTML('}');
      } 
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } elseif ($O=='H') {
      Validate('w_heranca','Origem dos dados','SELECT','1','1','10','','1');
      ShowHTML('  if (confirm(\'Confirma herança dos dados da opção selecionada?\')) {');
      ShowHTML('     window.close(); ');
      ShowHTML('     opener.focus(); ');
      ShowHTML('     return true; ');
      ShowHTML('  } ');
      ShowHTML('  else { return false; } ');
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
  } elseif ($O=='I') {
    BodyOpen(null);
  } elseif ($O=='A') {
    BodyOpen('onLoad=document.Form.w_nome.focus();');
  } elseif ($O=='H') {
    BodyOpen('onLoad=document.Form.w_heranca.focus();');
  } elseif ($O=='L') {
    BodyOpen('onLoad=this.focus();');
  } else {
    BodyOpen('onLoad=document.Form.w_assinatura.focus();');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('      <tr><td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('      <tr><td height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td><b>');
    $RS = db_getAgreeType::getInstanceOf($dbms,null,null,$w_cliente,'PAI');
    $w_ContOut=0;
    foreach($RS as $row) {
      $w_titulo  = f($row,'sigla');
      $w_ContOut = $w_ContOut+1;
      if (f($row,'Filho')>0) {
        ShowHTML('<A HREF=#"'.f($row,'sq_tipo_acordo').'"></A>');
        ShowHTML('<span><div align="left"><img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row,'nome'));
        if (f($row,'ativo')=='S') $w_classe='HL'; else $w_classe='LH';
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_tipo_acordo='.f($row,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo de acordos">AL</A>&nbsp');
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_tipo_acordo='.f($row,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo de acordos">EX</A>&nbsp');
        if (f($row,'ativo')=='S') {
          ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_tipo_acordo='.f($row,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo de acordos seja associado a novos registros">Desativar</A>&nbsp');
        } else {
          ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_tipo_acordo='.f($row,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo de acordos seja associado a novos registros">Ativar</A>&nbsp');
        } 
        ShowHTML('       </div></span>');
        ShowHTML('   <div style="position:relative; left:12;">');
        $RS1 = db_getAgreeType::getInstanceOf($dbms,f($row,'sq_tipo_acordo'),null,$w_cliente,'FILHO');
        foreach($RS1 as $row1) {
          $w_titulo = $w_titulo.' - '.f($row1,'nome');
          if (f($row1,'Filho')>0) {
            $w_ContOut = $w_ContOut+1;
            ShowHTML('<A HREF=#"'.f($row1,'sq_tipo_acordo').'"></A>');
            ShowHTML('<span><div align="left"><img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row1,'nome'));
            if (f($row1,'ativo')=='S') $w_classe='HL'; else $w_classe='LH';
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_tipo_acordo='.f($row1,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo de acordos">AL</A>&nbsp');
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_tipo_acordo='.f($row1,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo de acordos">EX</A>&nbsp');
            if (f($row1,'ativo')=='S') {
              ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_tipo_acordo='.f($row1,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo de acordos seja associado a novos registros">Desativar</A>&nbsp');
            } else {
              ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_tipo_acordo='.f($row1,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo de acordos seja associado a novos registros">Ativar</A>&nbsp');
            } 
            ShowHTML('       </div></span>');
            ShowHTML('   <div style="position:relative; left:12;">');
            $RS2 = db_getAgreeType::getInstanceOf($dbms,f($row1,'sq_tipo_acordo'),null,$w_cliente,'FILHO');
            foreach($RS2 as $row2) {
              $w_titulo = $w_titulo.' - '.f($row2,'nome');
              if (f($row2,'Filho')>0) {
                $w_ContOut=$w_ContOut+1;
                ShowHTML('<A HREF=#"'.f($row2,'sq_tipo_acordo').'"></A>');
                ShowHTML('<span><div align="left"><img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row2,'nome'));
                if (f($row2,'ativo')=='S') $w_classe='HL'; else $w_classe='LH';
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_tipo_acordo='.f($row2,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo de acordos">AL</A>&nbsp');
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_tipo_acordo='.f($row2,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo de acordos">EX</A>&nbsp');
                if (f($row2,'ativo')=='S') {
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_tipo_acordo='.f($row2,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo de acordos seja associado a novos registros">Desativar</A>&nbsp');
                } else {
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_tipo_acordo='.f($row2,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo de acordos seja associado a novos registros">Ativar</A>&nbsp');
                } 
                ShowHTML('       </div></span>');
                ShowHTML('   <div style="position:relative; left:12;">');
                $RS3 = db_getAgreeType::getInstanceOf($dbms,f($row2,'sq_tipo_acordo'),null,$w_cliente,'FILHO');
                foreach($RS3 as $row3) {
                  $w_titulo=$w_titulo.' - '.f($row3,'nome');
                  ShowHTML('<A HREF=#"'.f($row3,'sq_tipo_acordo').'"></A>');
                  ShowHTML('    <img src="'.$w_imagem.'" border=0 align="center"> '.f($row3,'nome'));
                  if (f($row3,'ativo')=='S') $w_classe='HL'; else $w_classe='LH';
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_tipo_acordo='.f($row3,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo de acordos">AL</A>&nbsp');
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_tipo_acordo='.f($row3,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo de acordos">EX</A>&nbsp');
                  if (f($row3,'ativo')=='S') {
                    ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_tipo_acordo='.f($row3,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo de acordos seja associado a novos registros">Desativar</A>&nbsp');
                  } else {
                    ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_tipo_acordo='.f($row3,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo de acordos seja associado a novos registros">Desativar</A>&nbsp');
                  } 
                  ShowHTML('    <BR>');
                  $w_titulo=str_replace(' - '.f($row3,'nome'),'',$w_titulo);
                } 
                ShowHTML('   </div>');
              } else {
                ShowHTML('    <img src="'.$w_imagem.'" border=0 align="center"> '.f($row2,'nome'));
                if (f($row2,'ativo')=='S') $w_classe='HL'; else $w_classe='LH';
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_tipo_acordo='.f($row2,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo de acordos">AL</A>&nbsp');
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_tipo_acordo='.f($row2,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo de acordos">EX</A>&nbsp');
                if (f($row2,'ativo')=='S') {
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_tipo_acordo='.f($row2,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo de acordos seja associado a novos registros">Desativar</A>&nbsp');
                 } else {
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_tipo_acordo='.f($row2,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo de acordos seja associado a novos registros">Ativar</A>&nbsp');
                } 
                ShowHTML('    <BR>');
              } 
              $w_titulo=str_replace(' - '.f($row2,'nome'),'',$w_titulo);
            } 
            ShowHTML('   </div>');
          } else {
            ShowHTML('    <img src="'.$w_imagem.'" border=0 align="center"> '.f($row1,'nome'));
            if (f($row1,'ativo')=='S') $w_classe='HL'; else $w_classe='LH';
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_tipo_acordo='.f($row1,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo de acordos">AL</A>&nbsp');
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_tipo_acordo='.f($row1,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo de acordos">EX</A>&nbsp');
            if (f($row1,'ativo')=='S') {
              ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_tipo_acordo='.f($row1,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo de acordos seja associado a novos registros">Desativar</A>&nbsp');
            } else {
              ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_tipo_acordo='.f($row1,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo de acordos seja associado a novos registros">Ativar</A>&nbsp');
            } 
            ShowHTML('    <BR>');
          } 
          $w_titulo=str_replace(' - '.f($row1,'nome'),'',$w_titulo);
        } 
        ShowHTML('   </div>');
      } else {
        ShowHTML('    <img src="'.$w_imagem.'" border=0 align="center"> '.f($row,'nome'));
        if (f($row,'ativo')=='S') $w_classe='HL'; else $w_classe='LH';
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_tipo_acordo='.f($row,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo de acordos">AL</A>&nbsp');
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_tipo_acordo='.f($row,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo de acordos">EX</A>&nbsp');
        if (f($row,'ativo')=='S') {
          ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_tipo_acordo='.f($row,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo de acordos seja associado a novos registros">Desativar</A>&nbsp');
        } else {
          ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_tipo_acordo='.f($row,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo de acordos seja associado a novos registros">Ativar</A>&nbsp');
        } 
        ShowHTML('    <BR>');
      } 
    } 
    if ($w_ContOut==0) {
      // Se não achou registros
      ShowHTML('Não foram encontrados registros.');
    } 
  } elseif ($O!='H') {
    if ($O!='I' && $O!='A') $w_Disabled='disabled';
    // Se for inclusão de nova opção, permite a herança dos dados de outra, já existente.
    if ($O=='I') {
      ShowHTML('      <tr><td><a accesskey="H" class="SS" href="javascript:location.href=this.location.href;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.$par.'&R='.$w_dir.$w_pagina.$par.'&O=H&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_sq_tipo_acordo='.$w_sq_tipo_acordo).'\',\'heranca'.'\',\'top=70,left=100,width=500,height=200,toolbar=no,status=no\');"><u>H</u>erdar dados</a>&nbsp;');
      ShowHTML('      <tr><td height="1" bgcolor="#000000"></td></tr>');
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sq_tipo_acordo" value="'.$w_sq_tipo_acordo.'">');
    ShowHTML('      <tr><td valign="top"><table width="100%" border=0><tr valign="top">');
    ShowHTML('          <td><b><u>N</u>ome:<br><INPUT ACCESSKEY="D" TYPE="TEXT" class="sti" NAME="w_nome" SIZE=40 MAXLENGTH=60 VALUE="'.$w_nome.'" '.$w_Disabled.' TITLE="Nome do tipo de acordo."></td>');
    SelecaoTipoAcordo('<u>S</u>ubordinação:','S','Se esta opção estiver subordinada a outra já existente, informe qual.',$w_sq_tipo_acordo_pai,$w_sq_tipo_acordo,$w_cliente,'w_sq_tipo_acordo_pai','SUBORDINACAO',null);
    ShowHTML('      <tr><td valign="top"><b>S<u>i</u>gla:<br><INPUT ACCESSKEY="S" TYPE="TEXT" class="sti" NAME="w_sigla" SIZE=10 MAXLENGTH=10 VALUE="'.$w_sigla.'" '.$w_Disabled.' TITLE="Informe a sigla desejada para o tipo de acordo."></td>');
    ShowHTML('          <td valign="top" TITLE="Informe "Sim" se este tipo de acordo aplicar-se a pessoas fisicas."><b>Pessoa física?</b><br>');
    if ($w_pessoa_fisica=='S' || $w_pessoa_fisica=='') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_pessoa_fisica" value="S" checked> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_pessoa_fisica" value="N"> Não');
    } else {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_pessoa_fisica" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_pessoa_fisica" value="N" checked> Não');
    } 
    ShowHTML('          <td valign="top" TITLE="Informe "Sim" se este tipo de acordo aplicar-se a pessoas jurídicas."><b>Pessoa jurídica?</b><br>');
    if ($w_pessoa_juridica=='S' || $w_pessoa_juridica=='') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_pessoa_juridica" value="S" checked> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_pessoa_juridica" value="N"> Não');
    } else {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_pessoa_juridica" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_pessoa_juridica" value="N" checked> Não');
    } 
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td valign="top" TITLE="Selecione a modalidade deste tipo de acordo dentre as apresentadas."><b>Modalidade:</b><br>');
    if ($w_modalidade=='Q' || $w_modalidade=='') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="Q" checked> Aquisição<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="A"> Arrendamento<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="E"> Emprego<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="F"> Fornecimento<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="I"> Parceria institucional<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="P"> Permissão');
    } elseif ($w_modalidade=='A') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="Q"> Aquisição<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="A" checked> Arrendamento<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="E"> Emprego<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="F"> Fornecimento<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="I"> Parceria institucional<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="P"> Permissão');
    } elseif ($w_modalidade=='E') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="Q"> Aquisição<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="A"> Arrendamento<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="E" checked> Emprego<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="F"> Fornecimento<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="I"> Parceria institucional<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="P"> Permissão');
    } elseif ($w_modalidade=='F') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="Q"> Aquisição<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="A"> Arrendamento<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="E"> Emprego<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="F" checked> Fornecimento<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="I"> Parceria institucional<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="P"> Permissão');
    } elseif ($w_modalidade=='P') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="Q"> Aquisição<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="A"> Arrendamento<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="E"> Emprego<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="F"> Fornecimento<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="I"> Parceria institucional<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="P" checked> Permissão');
    } else {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="Q"> Aquisição<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="A"> Arrendamento<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="E"> Emprego<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="F"> Fornecimento<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="I" checked> Parceria institucional<br><input '.$w_Disabled.' class="str" type="radio" name="w_modalidade" value="P"> Permissão');
    } 
    ShowHTML('          <td valign="top" TITLE="Informe "Sim" se este tipo de acordo tiver prazo indeterminado."><b>Prazo indeterminado?</b><br>');
    if ($w_prazo_indeterminado=='S' || $w_prazo_indeterminado=='') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_prazo_indeterminado" value="S" checked> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_prazo_indeterminado" value="N"> Não');
    } else {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_prazo_indeterminado" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_prazo_indeterminado" value="N" checked> Não');
    } 
    if ($O=='I') {
      ShowHTML('          <tr><td height="30"><b>Ativo?</b><br>');
      if ($w_ativo=='S') {
        ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="w_ativo" value="S" checked> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_ativo" value="N"> Não');
      } else {
        ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="w_ativo" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_ativo" value="N" checked> Não');
      } 
    } 
    ShowHTML('      </td></tr>');
    ShowHTML('      <tr><td><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center"  colspan="3"><input class="stb" type="submit" name="Botao" value="Gravar">&nbsp;');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
    ShowHTML('</FORM>');
    ShowHTML('  </table>');
  } elseif ($O=='H') {
    AbreForm('Form',$R,'POST','return(Validacao(this));','content',$P1,$P2,$P3,$P4,$TP,$SG,$R,'I');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_tipo_acordo" value="'.$w_sq_tipo_acordo.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Selecione, na relação, a opção a ser utilizada como origem de dados.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td width="100%" align="center">');
    ShowHTML('    <table align="center" border="0">');
    ShowHTML('      <tr><td valign="top"><table border=0 cellspacing=0 cellpadding=0>');
    ShowHTML('      <tr>');
    SelecaoTipoAcordo('<u>O</u>rigem:','O','Selecione na lista o tipo de acordo que deseja herdar.',$w_heranca,null,$w_cliente,'w_heranca','HERANCA',null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td align="center">&nbsp;');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Herdar">');
    ShowHTML('            <input class="stb" type="button" onClick="window.close(); opener.focus();" name="Botao" value="Cancelar">');
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
// Rotina de tipos de documentos
// -------------------------------------------------------------------------
function FormaPagamento(){
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave   = $_REQUEST['w_chave'];
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem das formas de pagamentos</TITLE>');
  Estrutura_CSS($w_cliente);
  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_chave    = $_REQUEST['w_chave'];
    $w_nome     = $_REQUEST['w_nome'];
    $w_sigla    = $_REQUEST['w_sigla'];
    $w_ativo    = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    $RS = db_getFormaPagamento::getInstanceOf($dbms,$w_cliente,null,null,'REGISTRO',null,null);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {
      $RS = SortArray($RS,'nome','asc'); 
    }
  } elseif (!(strpos('AEVT',$O)===false && $w_troca=='')) {
    $RS = db_getFormaPagamento::getInstanceOf($dbms,$w_cliente,$w_chave,null,'REGISTRO',$w_ativo,null);
    foreach ($RS as $row) {$RS=$row; break;}
    $w_chave    = f($RS,'chave');
    $w_nome     = f($RS,'nome');
    $w_sigla    = f($RS,'sigla');
    $w_ativo    = f($RS,'ativo');
  } 
  if (!(strpos('IAET',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    ValidateOpen('Validacao');  
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','3','30','1','1');
      Validate('w_sigla','Sigla','1','1','2','10','1','1');
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    }
    if ($O=='T') Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1'); 
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'')                BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
  elseif ($O=='I' || $O=='A')     BodyOpen('onLoad=\'document.Form.w_nome.focus();\'');
  elseif ($O=='L')                BodyOpen(null);
  else                            BodyOpen('onLoad=\'document.Form.w_assinatura.focus();\'');
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Sigla','sigla').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','nm_ativo').'</td>');
    ShowHTML('          <td><b> Operações </td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="left">'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'sigla').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" Title="Nome">AL </A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX </A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&TP='.$TP.'&SG='.$SG.'" onClick="window.open(\''.montaURL_JS($w_dir,'tabelas.php?par=FORMAPAG&R='.$w_pagina.$par.'&O=T&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=FORMAPAG'.MontaFiltro('GET').'\',\'Vinculacoes').'\',\'width=730,height=500,top=30,left=30,status=yes,resizable=yes,scrollbars=yes,toolbar=yes\');">Vinculações</A>&nbsp'); 
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    else       MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    ShowHTML('</tr>');
    //Aqui começa a manipulação de registros
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0"><tr>');
    ShowHTML('      <tr><td><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_nome.'"></td>');
    ShowHTML('      <tr><td><b><u>S</u>igla:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sigla" class="sti" SIZE="15" MAXLENGTH="15" VALUE="'.$w_sigla.'"></td>');
    ShowHTML('      <tr>');
    MontaRadioSN('<b>Ativo</b>?',$w_ativo,'w_ativo');
    ShowHTML('      <tr><td align="LEFT" colspan=2><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan=2><hr>'); 
    if ($O=='E') {
      ShowHTML('   <input class="stb" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>'); 
  } elseif (!(strpos('T',$O)===false)){
    $RS = DB_GetMenuFormaPag::getInstanceOf($dbms, $w_chave, null);
    $i=0;
    foreach($RS as $row) {
      if ($i==0) $w_vinculo = f($row,'sq_menu');
      else       $w_vinculo .= ','.f($row,'sq_menu');
      $i=1;
    }
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
    ShowHTML('<tr><td colspan=3 bgcolor="#FAEBD7"><table border=1 width="100%"><tr><td>');
    ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('      <tr><td>Nome:<br><b>'.$w_nome.'</b></td>');
    ShowHTML('          <td>Sigla:<br><b>'.$w_sigla.'</b></td>');    
    ShowHTML('    </TABLE>');
    ShowHTML('</TABLE>');
    ShowHTML('  <tr><td>&nbsp;');
    ShowHTML('      <tr><td><b>Vincular:</b><br>');
    $RS1 = db_getMenuList::getInstanceOf($dbms, $w_cliente, 'X', $chaveAux,null);
    $RS1 = SortArray($RS1,'nome','asc');
    ShowHTML('      <tr><td><table width="100%" border="0">');
    ShowHTML('        <tr>');
    if (count($RS1)>0) {
      $i = 2;
      foreach ($RS1 as $row) {
        if (!($i%2)) ShowHTML('        <tr>');
        $l_marcado = 'N';
        $l_chave   = $w_vinculo.',';
        while (!(strpos($l_chave,',')===false)) {
          $l_item  = trim(substr($l_chave,0,strpos($l_chave,',')));
          $l_chave = trim(substr($l_chave,(strpos($l_chave,',')+1),100));
          if ($l_item > '') {if (f($row,'sq_menu')==$l_item) $l_marcado = 'S'; }
        }
        if ($l_marcado=='S')  ShowHTML('          <td><input type="checkbox" name="w_vinculo[]" value="'.f($row,'sq_menu').'" checked>'.f($row,'nome').'<br>');
        else                  ShowHTML('          <td><input type="checkbox" name="w_vinculo[]" value="'.f($row,'sq_menu').'">'.f($row,'nome').'<br>');
        $i += 1;
      } 
    }
    ShowHTML('      <tr><td align="LEFT" colspan=2><br><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan=2><hr>');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    ShowHTML('            <input class="stb" type="button" onClick="window.close();" name="Botao" value="Fechar">');
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
// Rotina dos parâmetros
// -------------------------------------------------------------------------
function Parametros() {
  extract($GLOBALS);
  global $w_Disabled;

  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'' && $O=='E') {
    // Se for recarga da página
    $w_sequencial        = $_REQUEST['w_sequencial'];
    $w_sequencial_atual  = $_REQUEST['w_sequencial_atual'];
    $w_ano_corrente      = $_REQUEST['w_ano_corrente'];
    $w_prefixo           = $_REQUEST['w_prefixo'];
    $w_sufixo            = $_REQUEST['w_sufixo'];
    $w_numeracao         = $_REQUEST['w_numeracao'];
  } else {
    // Recupera os dados do parâmetro
    $RS = db_getACParametro::getInstanceOf($dbms,$w_cliente,null,null);
    if (count($RS)>0) {
      foreach($RS as $row) { $RS = $row; break; }
      $w_sequencial         = f($RS,'sequencial');
      $w_sequencial_atual   = f($RS,'sequencial');
      $w_ano_corrente       = f($RS,'ano_corrente');
      $w_prefixo            = f($RS,'prefixo');
      $w_sufixo             = f($RS,'sufixo');
      $w_numeracao          = f($RS,'numeracao_automatica');
    } 
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  Validate('w_sequencial','Sequencial','1',1,1,18,'','0123456789');
  CompValor('w_sequencial','Sequencial','>=',$w_sequencial_atual,$w_sequencial_atual);
  Validate('w_ano_corrente', 'Ano corrente', '1', 1, 4, 4, '', '0123456789');
  Validate('w_prefixo','Prefixo','1','',1,10,'1','1');
  Validate('w_sufixo','Sufixo','1','',1,10,'1','1');
  ShowHTML('  theForm.Botao.disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_sequencial.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');

  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="100%" border="0"><tr><td>');
  ShowHTML('      <table width="100%" border="0">');
  ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><font size="1"><b>Parâmetros</td></td></tr>');
  ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
  //ShowHTML '      <tr><td><font size=1>Falta definir a explicação.</font></td></tr>'
  //ShowHTML '      <tr><td align=''center'' height=''1'' bgcolor=''#000000''></td></tr>'
  ShowHTML('      </table>');
  ShowHTML('      <table width="100%" border="0">');
  ShowHTML('      <tr><td><font size="1"><b><u>S</u>equencial:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sequencial" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_sequencial.'"></td>');
  ShowHTML('      <td><b>Ano <U>c</U>orrente:<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="sti" type="text" name="w_ano_corrente" size="4" maxlength="4" value="'.$w_ano_corrente.'"></td>');
  ShowHTML('      <tr><td><font size="1"><b><u>P</u>refixo:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="w_prefixo" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_prefixo.'"></td>');
  ShowHTML('          <td><font size="1"><b><u>S</u>ufixo:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sufixo" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_sufixo.'"></td>');
  ShowHTML('      </table>');
  ShowHTML('      <tr>');
  MontaRadioNS('<b>Numeração automática?</b>',$w_numeracao,'w_numeracao');
  ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
  // Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
  ShowHTML('      <tr><td align="center" colspan="3">');
  ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
  ShowHTML('          </td>');
  ShowHTML('      </tr>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 

// =========================================================================
// Rotina dos modalidade
// -------------------------------------------------------------------------
function Modalidades() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave           = $_REQUEST['w_chave'];
  $w_troca           = $_REQUEST['w_troca'];
  //Se for recarga da página
  if ($w_troca > '' && $O=='E') {   
    $w_nome                 = $_REQUEST['w_nome'];
    $w_sigla                = $_REQUEST['w_sigla'];
    $w_descricao            = $_REQUEST['w_descricao'];
    $w_fundamentacao        = $_REQUEST['w_fundamentacao'];
    $w_ativo                = $_REQUEST['w_ativo'];
    $w_padrao               = $_REQUEST['w_padrao'];
  } elseif ($O=='L') {     
    // Recupera todos os registros para a listagem
    $RS = db_getLCModalidade::getInstanceOf($dbms, null, $w_cliente, null, null, null, null);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {
      $RS = SortArray($RS,'nome','asc'); 
    }
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    //Recupera os dados do endereço informado
    $RS = db_getLCModalidade::getInstanceOf($dbms, $w_chave, $w_cliente, null, null, null, null);
    foreach ($RS as $row) {
      $w_nome                 = f($row,'nome');
      $w_sigla                = f($row,'sigla');
      $w_descricao            = f($row,'descricao');
      $w_fundamentacao        = f($row,'fundamentacao');
      $w_ativo                = f($row,'ativo');
      $w_padrao               = f($row,'padrao');
    }
  }
  Cabecalho();
  ShowHTML( '<HEAD>' );
  If  (!(strpos('IAEP',$O)===false)) {
    ScriptOpen( 'JavaScript');
    ValidateOpen( 'Validacao');
     if (!(strpos('IA',$O)===false)) {    
        Validate('w_nome','Nome','1','1','2','80','1','1');
        Validate('w_sigla','Sigla','1','1','2','3','1','1');
        Validate('w_descricao','Descrição', '1', '', '5', '1000', '1', '1');
        Validate('w_fundamentacao','Fundamentação', '1', '', '5', '250', '1', '1');
        Validate('w_assinatura','Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
     } elseif ($O=='E') {
        Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
        ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
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
  If ($w_troca> '') {
     BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('IA',$O)===false)) {
     BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  } elseif ($O=='E') {
     BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
     BodyOpen('onLoad=\'document.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  If ($O=='L') {
    //Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><font size="1"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><font size="1"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>Sigla</font></td>');
    ShowHTML('          <td><font size="1"><b>Nome</font></td>');
    ShowHTML('          <td><font size="1"><b>Ativo</font></td>');
    ShowHTML('          <td><font size="1"><b>Padrão</font></td>');
    ShowHTML('          <td><font size="1"><b>Operações</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
    // Se não foram selecionados registros, exibe mensagem
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'sigla').'</td>');
        ShowHTML('        <td><font size="1">'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_padrao').'</td>');
        ShowHTML('        <td align="top" nowrap><font size="1">');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');       
      }
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  //Aqui começa a manipulação de registros
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form', $w_dir.$w_pagina.'Grava', 'POST', 'return(Validacao(this));', null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('           <td><font size="1"><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="60" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
    ShowHTML('           <td><font size="1"><b><u>S</u>igla:</b><br><input '.$w_Disabled.'accesskey="S" type="text" name="w_sigla" class="sti" SIZE="3" MAXLENGTH="3" VALUE="'.$w_sigla.'"></td>');
    ShowHTML('        <tr><td colspan=2><font size="1"><b><u>D</u>escrição:</b><br><textarea '.$w_Disabled.'accesskey="D" name="w_descricao" class="sti" ROWS="3" COLS="75">'.$w_descricao.'</textarea></td>');
    ShowHTML('        <tr><td colspan=2><font size="1"><b><u>F</u>undamentação:</b><br><textarea '.$w_Disabled.' accesskey="F" name="w_fundamentacao" class="sti" ROWS="3" COLS="75">'.$w_fundamentacao.'</textarea></td>');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN( '<b>Ativo?</b>', $w_ativo, 'w_ativo');
    MontaRadioNS( '<b>Padrão?</b>',$w_padrao, 'w_padrao');
    ShowHTML('           </table>');
    ShowHTML('      <tr><td align="LEFT"><font size="1"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
       ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
       if ($O=='I') {
          ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
       } else {
          ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
       }
    }
    ShowHTML('            <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen( 'JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina de fontes de recursos
// -------------------------------------------------------------------------
function FonteRecurso() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave           = $_REQUEST['w_chave'];
  $w_troca           = $_REQUEST['w_troca'];
  //Se for recarga da página
  if ($w_troca > '' && $O=='E') {   
    $w_codigo               = $_REQUEST['w_codigo'];
    $w_nome                 = $_REQUEST['w_nome'];
    $w_descricao            = $_REQUEST['w_descricao'];
    $w_ativo                = $_REQUEST['w_ativo'];
    $w_padrao               = $_REQUEST['w_padrao'];
    $w_orcamentario         = $_REQUEST['w_orcamentario'];
  } elseif ($O=='L') {     
    // Recupera todos os registros para a listagem
    $RS = db_getLCFonteRecurso::getInstanceOf($dbms, null, $w_cliente, null, null, null, null, null, null);
    $RS = SortArray($RS,'nome','asc'); 
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    //Recupera os dados do endereço informado
    $RS = db_getLCFonteRecurso::getInstanceOf($dbms, $w_chave, $w_cliente, null, null, null, null, null, null);
    foreach ($RS as $row) {
      $w_codigo               = f($row,'codigo');
      $w_nome                 = f($row,'nome');
      $w_descricao            = f($row,'descricao');
      $w_ativo                = f($row,'ativo');
      $w_padrao               = f($row,'padrao');
      $w_orcamentario         = f($row,'orcamentario');
      
    }
  }
  Cabecalho();
  ShowHTML( '<HEAD>' );
  If  (!(strpos('IAEP',$O)===false)) {
    ScriptOpen( 'JavaScript');
    ValidateOpen( 'Validacao');
     if (!(strpos('IA',$O)===false)) {    
       Validate('w_codigo','Código','1','1','1','10','1','1');
       Validate('w_nome','Nome','1','1','2','60','1','1');
       Validate('w_descricao','Descrição', '1', '', '5', '1000', '1', '1');
       Validate('w_assinatura','Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
     } elseif ($O=='E') {
       Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
       ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
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
  If ($w_troca> '') {
     BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('IA',$O)===false)) {
     BodyOpen('onLoad=\'document.Form.w_codigo.focus()\';');
  } elseif ($O=='E') {
     BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
     BodyOpen('onLoad=\'document.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  If ($O=='L') {
    //Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><font size="1"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><font size="1"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>Código</font></td>');
    ShowHTML('          <td><font size="1"><b>Nome</font></td>');
    ShowHTML('          <td><font size="1"><b>Ativo</font></td>');
    ShowHTML('          <td><font size="1"><b>Padrão</font></td>');
    ShowHTML('          <td><font size="1"><b>Orcamentário</font></td>');
    ShowHTML('          <td><font size="1"><b>Operações</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
    // Se não foram selecionados registros, exibe mensagem
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'codigo').'</td>');
        ShowHTML('        <td><font size="1">'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_padrao').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_orcamentario').'</td>');
        ShowHTML('        <td align="top" nowrap><font size="1">');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');       
      }
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  //Aqui começa a manipulação de registros
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form', $w_dir.$w_pagina.'Grava', 'POST', 'return(Validacao(this));', null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0 cellpadding=0>');
    ShowHTML('        <tr><td colspan=3><b><u>C</u>ódigo:</b><br><input '.$w_Disabled.'accesskey="C" type="text" name="w_codigo" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_codigo.'"></td>');
    ShowHTML('        <tr><td colspan=3><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="60" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
    ShowHTML('        <tr><td colspan=3><font size="1"><b><u>D</u>escrição:</b><br><textarea '.$w_Disabled.'accesskey="D" name="w_descricao" class="sti" ROWS="3" COLS="75">'.$w_descricao.'</textarea></td>');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN( '<b>Fonte Orçamentaria?</b>', $w_orcamentario, 'w_orcamentario');
    MontaRadioSN( '<b>Ativo?</b>', $w_ativo, 'w_ativo');
    MontaRadioNS( '<b>Padrão?</b>',$w_padrao, 'w_padrao');
    ShowHTML('           </table>');
    ShowHTML('      <tr><td align="LEFT"><font size="1"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
       ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
       if ($O=='I') {
          ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
       } else {
          ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
       }
    }
    ShowHTML('            <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen( 'JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina de especificacao de despesas
// -------------------------------------------------------------------------
function EspecDespesa() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_ImagemPadrao   = 'images/Folder/SheetLittle.gif';
  $w_heranca        = $_REQUEST['w_heranca'];
  $w_chave          = $_REQUEST['w_chave'];
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Especificação de despesa</TITLE>');
  Estrutura_CSS($w_cliente);
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_cliente       = $_REQUEST['w_cliente'];
    $w_chave_pai    = $_REQUEST['w_chave_pai'];
    $w_sq_cc        = $_REQUEST['w_sq_cc'];
    $w_ano          = $_REQUEST['w_ano'];
    $w_codigo       = $_REQUEST['w_codigo'];
    $w_nome         = $_REQUEST['w_nome'];
    $w_valor        = $_REQUEST['w_valor'];
    $w_ultimo_nivel = $_REQUEST['w_ultimo_nivel'];
    $w_ativo        = $_REQUEST['w_ativo'];
  } elseif ($O!='L') {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataValor();
    ValidateOpen('Validacao');
    if ($O!='P' && $O!='H' && $O!='G') {
      if ($w_heranca>'' || ($O!='I' && $w_troca=='')) {
        // Se for herança, atribui a chave da opção selecionada para w_sq_tipo_acordo
        if ($w_heranca>'') $w_sq_tipo_acordo=$w_heranca;
        $RS = db_getCTEspecificacao::getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,null,'ALTERA');
        foreach($RS as $row) {
          $w_chave_pai    = f($row,'chave_pai');
          $w_sq_cc        = f($row,'sq_cc');
          $w_ano          = f($row,'ano');
          $w_codigo       = f($row,'codigo');
          $w_nome         = f($row,'nome');
          $w_valor        = formatNumber(f($row,'valor'));
          $w_ultimo_nivel = f($row,'ultimo_nivel');
          $w_ativo        = f($row,'ativo');
        }
      } elseif ($O=='A') {
        $RS = db_getCTEspecificacao::getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,null,'ALTERA');
        foreach($RS as $row) {
          $w_chave_pai    = f($row,'chave_pai');
          $w_sq_cc        = f($row,'sq_cc');
          $w_ano          = f($row,'ano');
          $w_codigo       = f($row,'codigo');
          $w_nome         = f($row,'nome');
          $w_valor        = formatNumber(f($row,'valor'));
          $w_ultimo_nivel = f($row,'ultimo_nivel');
          $w_ativo        = f($row,'ativo');
        }
      } elseif ($w_troca>'') {
        $w_chave_pai    = $_REQUEST['w_chave_pai'];
        $w_sq_cc        = $_REQUEST['w_sq_cc'];
        $w_ano          = $_REQUEST['w_ano'];
        $w_codigo       = $_REQUEST['w_codigo'];
        $w_nome         = $_REQUEST['w_nome'];
        $w_valor        = $_REQUEST['w_valor'];
        $w_ultimo_nivel = $_REQUEST['w_ultimo_nivel'];
        $w_ativo        = $_REQUEST['w_ativo'];
      } 
      if ($O=='I' || $O=='A') {
        Validate('w_chave_pai','Especificação pai','SELECT','',1,18,'','0123456789');
        if(nvl($w_chave_pai,'')=='') Validate('w_sq_cc','Classificação','SELECT',1,1,18,'','0123456789');
        Validate('w_ano','Ano','1','1','4','4','','1');
        Validate('w_codigo','Código','1','1','1','10','1','1');
        Validate('w_nome','Nome','1','1','3','70','1','1');
        Validate('w_valor','Valor','VALOR','1',4,18,'','0123456789.,');
      } 
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } elseif ($O=='H') {
      Validate('w_heranca','Origem dos dados','SELECT','1','1','10','','1');
      ShowHTML('  if (confirm(\'Confirma herança dos dados da opção selecionada?\')) {');
      ShowHTML('     window.close(); ');
      ShowHTML('     opener.focus(); ');
      ShowHTML('     return true; ');
      ShowHTML('  } ');
      ShowHTML('  else { return false; } ');
    } elseif ($O=='G') {
      Validate('w_ano_origem','Ano de origem','SELECT','1','1','10','','1');
      Validate('w_ano_geracao','Ano de geração','SELECT','1','1','10','','1');
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
  } elseif ($O=='I') {
    BodyOpen(null);
  } elseif ($O=='A') {
    BodyOpen('onLoad=document.Form.w_chave_pai.focus();');
  } elseif ($O=='H') {
    BodyOpen('onLoad=document.Form.w_heranca.focus();');
  } elseif ($O=='G') {
    BodyOpen('onLoad=document.Form.w_heranca.focus();');
  } elseif ($O=='L') {
    BodyOpen('onLoad=this.focus();');
  } else {
    BodyOpen('onLoad=document.Form.w_assinatura.focus();');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('      <tr valing="top"><td>');
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="95%">');
    ShowHTML('      <tr><td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('              <a accesskey="G" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=G&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>G</u>erar ano</a>&nbsp;');
    ShowHTML('          <td align="right"><b>Exercício: '.$_SESSION['ANO'].'</b>');
    ShowHTML('</table>');
    ShowHTML('      <tr><td height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td><b>');
    $RS = db_getCTEspecificacao::getInstanceOf($dbms,$w_cliente,$w_chave,null,$_SESSION['ANO'],null,null,null,'PAI');
    $RS = SortArray($RS,'nm_ct_cc','asc'); 
    $w_ContOut = 0;
    $w_atual   = 0;
    foreach($RS as $row) {
      $w_titulo  = f($row,'nome');
      $w_contOut = $w_contOut+1;
      if (f($row,'Filho')>0) {
        ShowHTML('<A HREF=#"'.f($row,'chave').'"></A>');
        if($w_atual==0 || $w_atual!=f($row,'sq_cc')) {
          ShowHTML('<span><div align="left"><img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row,'nm_ct_cc').'');
          ShowHTML('       </div></span>');
        }
        ShowHTML('   <div style="position:relative; left:12;">');
        ShowHTML('<span><div align="left"><img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row,'nome').'');
        if (f($row,'ativo')=='S') $w_classe='hl'; else $w_classe='lh';
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Altera as informações desta especificação de despesa">AL</A>&nbsp');
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Exclui a especificação de despesa">EX</A>&nbsp');
        if (f($row,'ativo')=='S') {
          ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Impede que esta especificacao seja associado a novos registros">Desativar</A>&nbsp');
        } else {
          ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Permite que esta especificação de despesa seja associado a novos registros">Ativar</A>&nbsp');
        } 
        ShowHTML('       </div></span>');
        ShowHTML('   <div style="position:relative; left:12;">');
        $RS1 = db_getCTEspecificacao::getInstanceOf($dbms,$w_cliente,f($row,'chave'),null,$_SESSION['ANO'],null,null,null,'FILHO');
        foreach($RS1 as $row1) {
          $w_titulo = $w_titulo.' - '.f($row1,'nome');
          if (f($row1,'Filho')>0) {
            $w_contOut=$w_contOut+1;
            ShowHTML('<A HREF=#"'.f($row1,'chave').'"></A>');
            ShowHTML('<span><div align="left"><img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row1,'nome').'');
            if (f($row1,'ativo')=='S') $w_classe='hl'; else $w_classe='lh';
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row1,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Altera as informações desta especificação de despesa">AL</A>&nbsp');
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row1,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Exclui a especificação de despesa">EX</A>&nbsp');
            if (f($row1,'ativo')=='S') {
              ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_chabe='.f($row1,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Impede que esta especificação de despesa seja associado a novos registros">Desativar</A>&nbsp');
            } else {
              ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_chave='.f($row1,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Permite que esta especificação de despesa seja associado a novos registros">Ativar</A>&nbsp');
            } 
            ShowHTML('       </div></span>');
            ShowHTML('   <div style="position:relative; left:12;">');
            $RS2 = db_getCTEspecificacao::getInstanceOf($dbms,$w_cliente,f($row1,'chave'),null,$_SESSION['ANO'],null,null,null,'FILHO');
            foreach($RS2 as $row2) {
              $w_titulo = $w_titulo.' - '.f($row2,'nome');
              if (f($row2,'Filho')>0) {
                $w_contOut = $w_contOut+1;
                ShowHTML('<A HREF=#"'.f($row2,'chave').'"></A>');
                ShowHTML('<span><div align="left"><img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row2,'nome').'');
                if (f($row2,'ativo')=='S') $w_classe='hl'; else $w_classe='lh';
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row2,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Altera as informações desta especificação de despesa">AL</A>&nbsp');
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row2,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Exclui a especificação de despesa">EX</A>&nbsp');
                if (f($row2,'ativo')=='S') {
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_chave='.f($row2,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Impede que esta especificação de despesa seja associado a novos registros">Desativar</A>&nbsp');
                } else {
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_chave='.f($row2,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Permite que esta especificação de despesa seja associado a novos registros">Ativar</A>&nbsp');
                } 
                ShowHTML('       </div></span>');
                ShowHTML('   <div style="position:relative; left:12;">');
                $RS3 = db_getCTEspecificacao::getInstanceOf($dbms,$w_cliente,f($row2,'chave'),null,$_SESSION['ANO'],null,null,null,'FILHO');
                foreach($RS3 as $row3) {
                  $w_titulo = $w_titulo.' - '.f($row3,'nome');
                  ShowHTML('<A HREF=#"'.f($row3,'chave').'"></A>');
                  ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row3,'nome'));
                  if (f($row3,'ativo')=='S') $w_classe='hl'; else $w_classe='lh';
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row3,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Altera as informações desta especificação de despesa">AL</A>&nbsp');
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row3,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Exclui a especificação de despesa">EX</A>&nbsp');
                  if (f($row3,'ativo')=='S') {
                    ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_chave='.f($row3,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Impede que esta especificação de despesa seja associado a novos registros">Desativar</A>&nbsp');
                  } else {
                    ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_chave='.f($row3,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Permite que esta especificação de despesa seja associado a novos registros">Desativar</A>&nbsp');
                  } 
                  ShowHTML('    <BR>');
                  $w_titulo = str_replace(' - '.f($row3,'nome'),'',$w_titulo);
                } 
                ShowHTML('   </div>');
              } else {
                ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row2,'nome'));
                if (f($row2,'ativo')=='S') $w_classe='hl'; else $w_classe='lh';
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row2,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Altera as informações desta especificação de despesa">AL</A>&nbsp');
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row2,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Exclui a especificação de despesa">EX</A>&nbsp');
                if (f($row2,'ativo')=='S') {
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_chave='.f($row2,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Impede que esta especificação de despesa seja associado a novos registros">Desativar</A>&nbsp');
                } else {
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_chave='.f($row2,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Permite que esta especificação de despesa seja associado a novos registros">Ativar</A>&nbsp');
                } 
                ShowHTML('    <BR>');
              } 
              $w_titulo=str_replace(' - '.f($row2,'nome'),'',$w_titulo);
            } 
            ShowHTML('   </div>');
          } else {
            $w_Imagem=$w_ImagemPadrao;
            ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row1,'nome'));
            if (f($row1,'ativo')=='S') $w_classe='hl'; else $w_classe='lh';
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row1,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Altera as informações desta especificação de despesa">AL</A>&nbsp');
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row1,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Exclui a especificação de despesa">EX</A>&nbsp');
            if (f($row1,'ativo')=='S') {
              ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_chave='.f($row1,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Impede que esta especificação de despesa seja associado a novos registros">Desativar</A>&nbsp');
            } else {
              ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_chave='.f($row1,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Permite que esta especificação de despesa seja associado a novos registros">Ativar</A>&nbsp');
            } 
            ShowHTML('    <BR>');
          } 
          $w_titulo=str_replace(' - '.f($row1,'nome'),'',$w_titulo);
        } 
        ShowHTML('   </div>');
      } else {
        if($w_atual==0 || $w_atual!=f($row,'sq_cc')) {
          ShowHTML('<span><div align="left"><img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row,'nm_ct_cc').'');
          ShowHTML('       </div></span>');      
        }
        ShowHTML('   <div style="position:relative; left:12;">');  
        $w_Imagem=$w_ImagemPadrao;
        ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row,'nome').'');
        if (f($row,'ativo')=='S') $w_classe='hl'; else $w_classe='lh';
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Altera as informações desta especificação de despesa">AL</A>&nbsp');
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Exclui a especificação de despesa">EX</A>&nbsp');
        if (f($row,'ativo')=='S') {
          ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Impede que esta especificação de despesa seja associado a novos registros">Desativar</A>&nbsp');
        } else {
          ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_ESPEC" title="Permite que esta especificação de despesa seja associado a novos registros">Ativar</A>&nbsp');
        } 
        ShowHTML('    <BR>');
      }
      $w_atual = f($row,'sq_cc');
      ShowHTML('   </div>');
    } 
    if ($w_contOut==0) {
      // Se não achou registros
      ShowHTML('Não foram encontrados registros.');
    } 
  } elseif ($O!='H' && $O!='G') {
    if ($O!='I' && $O!='A') $w_Disabled='disabled';
    // Se for inclusão de nova opção, permite a herança dos dados de outra, já existente.
    if ($O=='I') {
      ShowHTML('      <tr><td><a accesskey="H" class="SS" href="javascript:location.href=this.location.href;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.$par.'&R='.$w_dir.$w_pagina.$par.'&O=H&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_sq_tipo_acordo='.$w_sq_tipo_acordo).'\',\'heranca'.'\',\'top=70,left=100,width=500,height=200,toolbar=no,status=no\');"><u>H</u>erdar dados</a>&nbsp;');
      ShowHTML('      <tr><td height="1" bgcolor="#000000"></td></tr>');
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('      <tr><td valign="top"><table width="100%" border=0><tr valign="top">');
    // Recupera a lista de opções
    selecaoCTEspecificacao('<u>S</u>ubordinação:','S','Se esta opção estiver subordinada a outra já existente, informe qual.',$w_chave,$w_chave_pai,null,$_SESSION['ANO'],'w_chave_pai','N','SUBORDINACAO','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_codigo\'; document.Form.submit();"');
    if(nvl($w_chave_pai,'')=='') {
      SelecaoCC('C<u>l</u>assificação:','L','Selecione a classificação desejada.',$w_sq_cc,null,'w_sq_cc','SIWSOLIC');
    } else {
      $RS = db_getCTEspecificacao::getInstanceOf($dbms, $w_cliente, $w_chave_pai, null,null,null,null,null,null);
      foreach($RS as $row){$RS=$row; break;}
      ShowHTML('<INPUT type="hidden" name="w_sq_cc" value="'.f($row,'sq_cc').'">');
    }
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td><b><u>A</u>no:<br><INPUT ACCESSKEY="A" TYPE="TEXT" CLASS="sti" NAME="w_ano" SIZE=4 MAXLENGTH=4 VALUE="'.nvl($w_ano,$_SESSION['ANO']).'" '.$w_Disabled.' title="Ano a que se refere a especificação de despesa."></td>');
    ShowHTML('          <td><b><u>C</u>ódigo:<br><INPUT ACCESSKEY="C" TYPE="TEXT" CLASS="sti" NAME="w_codigo" SIZE=10 MAXLENGTH=10 VALUE="'.$w_codigo.'" '.$w_Disabled.' title="Código da especificação da despesa."></td>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td><b><u>N</u>ome:<br><INPUT ACCESSKEY="N" TYPE="TEXT" CLASS="sti" NAME="w_nome" SIZE=40 MAXLENGTH=70 VALUE="'.$w_nome.'" '.$w_Disabled.' title="Nome da especificação de despesa."></td>');
    ShowHTML('           <td><b>Valo<u>r</u>:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_valor" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor total real ou estimado."></td>');
    ShowHTML('        <tr valign="top">');
    MontaRadioNS('<b>Último nível</b>?',$w_ultimo_nivel,'w_ultimo_nivel');
    if ($O=='I') {
      ShowHTML('      <tr align="left">');
      MontaRadioSN('<b>Ativo</b>?',$w_ativo,'w_ativo');
      ShowHTML('      </tr>');
    } elseif ($O=='A') {
       ShowHTML('<INPUT type="hidden" name="w_ativo" value="'.$w_ativo.'">');
    } 
    ShowHTML('          </table>');
    ShowHTML('      </td></tr>');
    ShowHTML('      <tr><td valign="top"><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3"><input class="stb" type="submit" name="Botao" value="Gravar">&nbsp;');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
    ShowHTML('</FORM>');
    ShowHTML('  </table>');
  } elseif ($O=='H') {
    AbreForm('Form',$R,'POST','return(Validacao(this));','content',$P1,$P2,$P3,$P4,$TP,$SG,$R,'I');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_tipo_acordo" value="'.$w_sq_tipo_acordo.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Selecione, na relação, a opção a ser utilizada como origem de dados.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td width="100%" align="center">');
    ShowHTML('    <table align="center" border="0">');
    ShowHTML('      <tr><td valign="top"><table border=0 cellspacing=0 cellpadding=0>');
    ShowHTML('      <tr>');
    selecaoCTEspecificacao('<u>O</u>rigem:','O','Selecione na lista a especificacao de despesa a ser usado como origem de dados.',$w_heranca,null,null,null,'w_heranca',null,'HERANCA',null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td align="center">&nbsp;');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Herdar">');
    ShowHTML('            <input class="stb" type="button" onClick="window.close(); opener.focus();" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif ($O=='G') {
		AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_tipo_acordo" value="'.$w_sq_tipo_acordo.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td width="100%" align="center">');
    ShowHTML('    <table align="center" border="0" width="100%" >');
    ShowHTML('      <tr>');
    SelecaoAno('<U>A</U>no de origem:','A',null,$w_ano,null,'w_ano_origem','ESPEC2',null);
    SelecaoAno('<U>A</U>no da geração:','A',null,$w_ano,null,'w_ano_geracao','ESPEC',null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td align="center" colspan="2">&nbsp;');
    ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="2">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gerar">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
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
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=this.focus();');
  switch ($SG) {
    case 'TIPOACORDO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {    
        dml_putAgreeType::getInstanceOf($dbms, $O,
            $_REQUEST['w_sq_tipo_acordo'],$_REQUEST['w_sq_tipo_acordo_pai'],$_REQUEST['w_cliente'],
            $_REQUEST['w_nome'],$_REQUEST['w_sigla'],$_REQUEST['w_modalidade'],
            $_REQUEST['w_prazo_indeterminado'],$_REQUEST['w_pessoa_juridica'],$_REQUEST['w_pessoa_fisica'],
            $_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'FORMAPAG':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {  
        if (!(strpos('IAE',$O)===false))  {
          dml_putFormaPagamento::getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''),$_REQUEST['w_cliente'],$_REQUEST['w_nome'],
               $_REQUEST['w_sigla'],$_REQUEST['w_ativo']);
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
          ScriptClose();
        } else {                  
           // Elimina todas as permissões existentes para depois incluir
           dml_PutFormaPagamentoVinc::getInstanceOf($dbms, 'E',$_REQUEST['w_chave'],null);
           for ($i=0; $i<=count($_POST['w_vinculo'])-1; $i=$i+1)   {
             dml_PutFormaPagamentoVinc::getInstanceOf($dbms, 'I', $_REQUEST['w_chave'], $_POST['w_vinculo'][$i]);
           }
           ScriptOpen('JavaScript');
           ShowHTML('  window.close() ;'); 
           ScriptClose();
        }
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'ACPARAM':
      // Verifica se a Assinatura Eletrônica é válida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putACParametro::getInstanceOf($dbms,$w_cliente,
           $_REQUEST['w_sequencial'],$_REQUEST['w_ano_corrente'],$_REQUEST['w_prefixo'],
           $_REQUEST['w_sufixo'],$_REQUEST['w_numeracao']);     
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'ACMODAL':
      // Verifica se a Assinatura Eletrônica é válida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
         if ($O=='I' || $O=='A') {
          // Testa a existência do nome
          $RS = db_getLCModalidade::getInstanceOf($dbms,$_REQUEST['w_chave'], $w_cliente, Nvl($_REQUEST['w_nome'],''), null, null, 'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Já existe tipo de modalidade com este nome!\');');
            ScriptClose(); 
            retornaFormulario('w_nome');
            break;
          } 
          // Testa a existência do sigla
          $RS = db_getLCModalidade::getInstanceOf($dbms,$_REQUEST['w_chave'], $w_cliente, null ,strtoupper(Nvl($_REQUEST['w_sigla'],'')),null, 'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Já existe tipo de modalidade com esta sigla!\');');
            ScriptClose(); 
            retornaFormulario('w_sigla');
            break;
          } 
        }  
        dml_putLCModalidade::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$w_cliente,
           $_REQUEST['w_nome'],strtoupper($_REQUEST['w_sigla']),$_REQUEST['w_descricao'],
           $_REQUEST['w_fundamentacao'],$_REQUEST['w_ativo'],$_REQUEST['w_padrao']);       
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
        ScriptClose();        
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'ACFONTE':
      // Verifica se a Assinatura Eletrônica é válida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
         if ($O=='I' || $O=='A') {
          // Testa a existência do nome
          $RS = db_getLCFonteRecurso::getInstanceOf($dbms,$_REQUEST['w_chave'], $w_cliente, Nvl($_REQUEST['w_nome'],''), null, null, null, null, 'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Já existe fonte de recurso com este nome!\');');
            ScriptClose(); 
            retornaFormulario('w_nome');
            break;
          } 
        }  
        dml_putLCFonteRecurso::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$w_cliente,
           $_REQUEST['w_nome'],$_REQUEST['w_descricao'],$_REQUEST['w_ativo'],
           $_REQUEST['w_padrao'],$_REQUEST['w_orcamentario'],$_REQUEST['w_codigo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
        ScriptClose();        
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'CT_ESPEC':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {    
        dml_putCTEspecificacao::getInstanceOf($dbms, $O, $_REQUEST['w_cliente'],
            $_REQUEST['w_chave'],$_REQUEST['w_chave_pai'],$_REQUEST['w_sq_cc'],$_REQUEST['w_ano'],
            $_REQUEST['w_codigo'],$_REQUEST['w_nome'],$_REQUEST['w_valor'],
            $_REQUEST['w_ultimo_nivel'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;                   
    default:
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Bloco de dados não encontrado: '.$SG.'\');');
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
  switch ($par) {
  case 'TIPOACORDO':    TipoAcordo();        break;
  case 'FORMAPAG':      FormaPagamento();    break;
  case 'PARAMETROS':    Parametros();        break;
  case 'MODALIDADES':   Modalidades();       break;
  case 'FONTERECURSO':  FonteRecurso();      break;
  case 'ESPECDESPESA':  EspecDespesa();      break;  
  case 'GRAVA':         Grava();             break;
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
  } 
} 
?>
