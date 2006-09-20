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
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putAgreeType.php');
include_once($w_dir_volta.'classes/sp/dml_putFormaPagamento.php');
include_once($w_dir_volta.'funcoes/selecaoTipoAcordo.php');

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
  case 'I': $w_TP=$TP.' - Inclusão';  break;
  case 'A': $w_TP=$TP.' - Alteração'; break;
  case 'E': $w_TP=$TP.' - Exclusão';  break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  case 'C': $w_TP=$TP.' - Cópia';     break;
  case 'V': $w_TP=$TP.' - Envio';     break;
  case 'H': $w_TP=$TP.' - Herança';   break;
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
if ($P2>0) {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,$P2);
} else {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,$w_menu);
}

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
  //  $w_troca          = $_REQUEST['w_troca'];
  $w_heranca        = $_REQUEST['w_heranca'];
  $w_sq_tipo_acordo = $_REQUEST['w_sq_tipo_acordo'];
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Tipos de Acordo</TITLE>');
  Estrutura_CSS($w_cliente);
  if ($O!='L') {
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
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad=document.Form.w_nome.focus();');
  } elseif ($O=='H') {
    BodyOpen('onLoad=document.Form.w_heranca.focus();');
  } elseif ($O=='L') {
    BodyOpen('onLoad=document.focus();');
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
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_tipo_acordo='.f($row,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo de acordos">Alterar</A>&nbsp');
        if (f($row,'ativo')=='S') {
          ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_tipo_acordo='.f($row,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo de acordos seja associado a novos registros">Desativar</A>&nbsp');
        } else {
          ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_tipo_acordo='.f($row,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo de acordos seja associado a novos registros">Ativar</A>&nbsp');
        } 
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_tipo_acordo='.f($row,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo de acordos">Excluir</A>&nbsp');
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
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_tipo_acordo='.f($row1,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo de acordos">Alterar</A>&nbsp');
            if (f($row1,'ativo')=='S') {
              ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_tipo_acordo='.f($row1,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo de acordos seja associado a novos registros">Desativar</A>&nbsp');
            } else {
              ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_tipo_acordo='.f($row1,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo de acordos seja associado a novos registros">Ativar</A>&nbsp');
            } 
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_tipo_acordo='.f($row1,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo de acordos">Excluir</A>&nbsp');
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
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_tipo_acordo='.f($row2,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo de acordos">Alterar</A>&nbsp');
                if (f($row2,'ativo')=='S') {
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_tipo_acordo='.f($row2,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo de acordos seja associado a novos registros">Desativar</A>&nbsp');
                } else {
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_tipo_acordo='.f($row2,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo de acordos seja associado a novos registros">Ativar</A>&nbsp');
                } 
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_tipo_acordo='.f($row2,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo de acordos">Excluir</A>&nbsp');
                ShowHTML('       </div></span>');
                ShowHTML('   <div style="position:relative; left:12;">');
                $RS3 = db_getAgreeType::getInstanceOf($dbms,f($row2,'sq_tipo_acordo'),null,$w_cliente,'FILHO');
                foreach($RS3 as $row3) {
                  $w_titulo=$w_titulo.' - '.f($row3,'nome');
                  ShowHTML('<A HREF=#"'.f($row3,'sq_tipo_acordo').'"></A>');
                  ShowHTML('    <img src="'.$w_imagem.'" border=0 align="center"> '.f($row3,'nome'));
                  if (f($row3,'ativo')=='S') $w_classe='HL'; else $w_classe='LH';
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_tipo_acordo='.f($row3,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo de acordos">Alterar</A>&nbsp');
                  if (f($row3,'ativo')=='S') {
                    ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_tipo_acordo='.f($row3,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo de acordos seja associado a novos registros">Desativar</A>&nbsp');
                  } else {
                    ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_tipo_acordo='.f($row3,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo de acordos seja associado a novos registros">Desativar</A>&nbsp');
                  } 
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_tipo_acordo='.f($row3,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo de acordos">Excluir</A>&nbsp');
                  ShowHTML('    <BR>');
                  $w_titulo=str_replace(' - '.f($row3,'nome'),'',$w_titulo);
                } 
                ShowHTML('   </div>');
              } else {
                ShowHTML('    <img src="'.$w_imagem.'" border=0 align="center"> '.f($row2,'nome'));
                if (f($row2,'ativo')=='S') $w_classe='HL'; else $w_classe='LH';
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_tipo_acordo='.f($row2,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo de acordos">Alterar</A>&nbsp');
                if (f($row2,'ativo')=='S') {
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_tipo_acordo='.f($row2,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo de acordos seja associado a novos registros">Desativar</A>&nbsp');
                 } else {
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_tipo_acordo='.f($row2,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo de acordos seja associado a novos registros">Ativar</A>&nbsp');
                } 
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_tipo_acordo='.f($row2,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo de acordos">Excluir</A>&nbsp');
                ShowHTML('    <BR>');
              } 
              $w_titulo=str_replace(' - '.f($row2,'nome'),'',$w_titulo);
            } 
            ShowHTML('   </div>');
          } else {
            ShowHTML('    <img src="'.$w_imagem.'" border=0 align="center"> '.f($row1,'nome'));
            if (f($row1,'ativo')=='S') $w_classe='HL'; else $w_classe='LH';
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_tipo_acordo='.f($row1,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo de acordos">Alterar</A>&nbsp');
            if (f($row1,'ativo')=='S') {
              ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_tipo_acordo='.f($row1,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo de acordos seja associado a novos registros">Desativar</A>&nbsp');
            } else {
              ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_tipo_acordo='.f($row1,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo de acordos seja associado a novos registros">Ativar</A>&nbsp');
            } 
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_tipo_acordo='.f($row1,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo de acordos">Excluir</A>&nbsp');
            ShowHTML('    <BR>');
          } 
          $w_titulo=str_replace(' - '.f($row1,'nome'),'',$w_titulo);
        } 
        ShowHTML('   </div>');
      } else {
        ShowHTML('    <img src="'.$w_imagem.'" border=0 align="center"> '.f($row,'nome'));
        if (f($row,'ativo')=='S') $w_classe='HL'; else $w_classe='LH';
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_tipo_acordo='.f($row,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo de acordos">Alterar</A>&nbsp');
        if (f($row,'ativo')=='S') {
          ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_tipo_acordo='.f($row,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo de acordos seja associado a novos registros">Desativar</A>&nbsp');
        } else {
          ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_tipo_acordo='.f($row,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo de acordos seja associado a novos registros">Ativar</A>&nbsp');
        } 
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_tipo_acordo='.f($row,'sq_tipo_acordo').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo de acordos">Excluir</A>&nbsp');
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
      ShowHTML('      <tr><td><a accesskey="H" class="SS" href="javascript:location.href=this.location.href;" onClick="window.open(\''.$w_pagina.$par.'&R='.$w_dir.$w_pagina.$par.'&O=H&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_sq_tipo_acordo='.$w_sq_tipo_acordo.'\',\'heranca\',\'top=70 left=100 width=500 height=200 toolbar=no status=yes\');"><u>H</u>erdar dados</a>&nbsp;');
      ShowHTML('      <tr><td height="1" bgcolor="#000000">');
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sq_tipo_acordo" value="'.$w_sq_tipo_acordo.'">');
    ShowHTML('      <tr><td valign="top"><table width="100%" border=0><tr valign="top">');
    ShowHTML('          <td><b><u>N</u>ome:<br><INPUT ACCESSKEY="D" TYPE="TEXT" class="sti" NAME="w_nome" SIZE=40 MAXLENGTH=60 VALUE="'.$w_nome.'" '.$w_Disabled.' TITLE="Nome do tipo de acordo."></td>');
    SelecaoTipoAcordo('<u>S</u>ubordinação:','S','Se esta opção estiver subordinada a outra já existente, informe qual.',$w_sq_tipo_acordo_pai,$w_sq_tipo_acordo,$w_cliente,'w_sq_tipo_acordo_pai','SUBORDINACAO',null);
    ShowHTML('          </table>');
    ShowHTML('      <tr><td valign="top"><table width="100%" border=0><tr valign="top">');
    ShowHTML('          <td valign="top"><b>S<u>i</u>gla:<br><INPUT ACCESSKEY="S" TYPE="TEXT" class="sti" NAME="w_sigla" SIZE=10 MAXLENGTH=10 VALUE="'.$w_sigla.'" '.$w_Disabled.' TITLE="Informe a sigla desejada para o tipo de acordo."></td>');
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
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td colspan="3"><input class="stb" type="submit" name="Botao" value="Gravar">&nbsp;');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';" name="Botao" value="Cancelar">');
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
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de tipos de documentos</TITLE>');
  if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="300; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">');
  Estrutura_CSS($w_cliente);
  if ($O=='') $O='L';
  if ($O=='L') {
    $RS = db_getFormaPagamento::getInstanceOf($dbms,$w_cliente,null,null,'REGISTRO',null);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {
      $RS = SortArray($RS,'nome','asc'); 
    }
  } elseif (!(strpos('AEV',$O)===false && $w_troca=='')) {
    $RS = db_getFormaPagamento::getInstanceOf($dbms,$w_cliente,$w_chave,null,'REGISTRO',$w_ativo);
    foreach ($RS as $row) {$RS=$row; break;}
    $w_chave    = f($RS,'chave');
    $w_nome     = f($RS,'nome');
    $w_sigla    = f($RS,'sigla');
    $w_ativo    = f($RS,'ativo');
  } 
  if (!(strpos('IAE',$O)===false)) {
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
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=document.Form.'.$w_troca.'.focus();');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad=document.Form.w_nome.focus();');
  } elseif ($O=='L') {
    BodyOpen('onLoad=document.focus();');
  } else {
    BodyOpen('onLoad=document.Form.w_assinatura.focus();');
  } 
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
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Nome">Alterar </A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.'">Excluir </A>&nbsp');
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
    ShowHTML('            <input class="stb" type="button" onClick="history.back(1);" name="Botao" value="Cancelar">');
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
  BodyOpen('onLoad=document.focus();');
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
        ShowHTML('  location.href=\''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'FORMAPAG':
      // Verifica se a Assinatura Eletrônica é válida
       if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {  

        dml_putFormaPagamento::getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''),$_REQUEST['w_cliente'],$_REQUEST['w_nome'],
             $_REQUEST['w_sigla'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        //ShowHTML('  location.href=\''.$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';');
        ShowHTML('  location.href=\''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
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
  case 'GRAVA':         Grava();             break;
  default:
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
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
