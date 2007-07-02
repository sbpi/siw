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
include_once($w_dir_volta.'classes/sp/db_getTipoMatServ.php');
include_once($w_dir_volta.'classes/sp/db_getLCCriterio.php');
include_once($w_dir_volta.'classes/sp/db_getLCSituacao.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putTipoMatServ.php');
include_once($w_dir_volta.'classes/sp/dml_putLCCriterio.php');
include_once($w_dir_volta.'classes/sp/dml_putLCSituacao.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoTipoMatServSubord.php');
include_once($w_dir_volta.'funcoes/selecaoClasseMatServ.php');

// =========================================================================
//  /tabelas.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerenciar tabelas básicas do módulo  
// Mail     : alex@sbpi.com.br
// Criacao  : 30/06/2007 11:00
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
$w_dir          = 'mod_cl/';
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
// Rotina de tipos de materiais e serviços
// -------------------------------------------------------------------------
function TipoMatServ() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_ImagemPadrao = 'images/Folder/SheetLittle.gif';
  $w_troca        = $_REQUEST['w_troca'];
  $w_copia        = $_REQUEST['w_copia'];
  $w_chave        = $_REQUEST['w_chave'];

  if ($w_troca>'' && $O!='E' && $O!='D' && $O!='T') {
    $w_cliente        = $_REQUEST['w_cliente'];
    $w_chave_pai      = $_REQUEST['w_chave_pai'];
    $w_nome           = $_REQUEST['w_nome'];
    $w_sigla          = $_REQUEST['w_sigla'];
    $w_classe         = $_REQUEST['w_classe'];
    $w_gestora        = $_REQUEST['w_gestora'];
    $w_descricao      = $_REQUEST['w_descricao'];
    $w_codigo_externo = $_REQUEST['w_codigo_externo'];
    $w_ativo          = $_REQUEST['w_ativo'];
  } elseif ($O != 'L' && $O != 'I') {
    // Se for herança, atribui a chave da opção selecionada para w_chave
    if ($w_copia>'') $w_chave = $w_copia;
    $RS = db_getTipoMatServ::getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,null,null,'REGISTROS');
    foreach ($RS as $row) { $RS = $row; break; }
    $w_chave_pai      = f($RS,'sq_tipo_pai');
    $w_nome           = f($RS,'nome');
    $w_sigla          = f($RS,'sigla');
    $w_classe         = f($RS,'classe');
    $w_gestora        = f($RS,'unidade_gestora');
    $w_descricao      = f($RS,'descricao');
    $w_codigo_externo = f($RS,'codigo_externo');
    $w_ativo          = f($RS,'ativo');
  }  

  // Recupera informações sobre o tipo pai para determinar sua classe
  if (nvl($w_chave_pai,'')!='') {
    $RS_Tipo = db_getTipoMatServ::getInstanceOf($dbms,$w_cliente,$w_chave_pai,null,null,null,null,null,null,'REGISTROS');
    foreach ($RS_Tipo as $row) { $RS_Tipo = $row; break; }
    $w_classe = f($RS_Tipo,'classe');
  }

  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);

  if ($O!='L') {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if ($O!='P') {
      if (strpos('CIA',$O)!==false) {
        Validate('w_nome','Nome','1','1','2','30','1','1');
        Validate('w_sigla','Sigla','1','1','1','10','1','1');
        if (nvl($w_chave_pai,'')=='') Validate('w_classe','Classe','SELECT','1','1','10','1','1');
        Validate('w_gestora','Unidade gestora','1','1','1','10','1','1');
        Validate('w_codigo_externo','Código externo','1','','1','30','1','1');
        Validate('w_descricao','Descrição','1','1','5','2000','1','1');
     } 
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
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
  } elseif ($O=='C' || $O=='I' || $O=='A') {
    BodyOpen('onLoad=document.Form.w_chave_pai.focus();');
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
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="99%" border="0">');
  if ($O=='L') {
    ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">Orientação:<ul><li>Os números entre parênteses indicam a quantidade de itens vinculados ao tipo.</ul></b></font></td>');
    ShowHTML('      <tr><td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('      <tr><td height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td><b>');
    $RS = db_getTipoMatServ::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null,null,'IS NULL');
    $w_contOut = 0;
    foreach($RS as $row) {
      $w_nome  = f($row,'nome');
      $w_contOut = $w_contOut+1;
      if (f($row,'Filho')>0) {
        ShowHTML('<A HREF=#"'.f($row,'chave').'"></A>');
        ShowHTML('<span><div align="left"><img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row,'nome').'');
        if (f($row,'ativo')=='S') $w_class='hl'; else $w_class='lh';
        ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo">AL</A>&nbsp');
        if (f($row,'ativo')=='S') {
          ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_chave='.f($row,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo seja associado a novos registros">Desativar</A>&nbsp');
        } else {
          ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_chave='.f($row,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo seja associado a novos registros">Ativar</A>&nbsp');
        } 
        ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo">EX</A>&nbsp');
        ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&w_copia='.f($row,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Insere um novo tipo a partir das informações deste registro">Copiar</A>&nbsp');
        ShowHTML('       </div></span>');
        ShowHTML('   <div style="position:relative; left:12;">');
        $RS1 = db_getTipoMatServ::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null,null,f($row,'chave'));
        foreach($RS1 as $row1) {
          $w_nome .= ' - '.f($row1,'nome');
          if (f($row1,'Filho')>0) {
            $w_contOut=$w_contOut+1;
            ShowHTML('<A HREF=#"'.f($row1,'chave').'"></A>');
            ShowHTML('<span><div align="left"><img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row1,'nome').'');
            if (f($row1,'ativo')=='S') $w_class='hl'; else $w_class='lh';
            ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row1,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo">AL</A>&nbsp');
            if (f($row1,'ativo')=='S') {
              ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_chave='.f($row1,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo seja associado a novos registros">Desativar</A>&nbsp');
            } else {
              ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_chave='.f($row1,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo seja associado a novos registros">Ativar</A>&nbsp');
            } 
            ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row1,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo">EX</A>&nbsp');
            ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&w_copia='.f($row1,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Insere um novo tipo a partir das informações deste registro">Copiar</A>&nbsp');
            ShowHTML('       </div></span>');
            ShowHTML('   <div style="position:relative; left:12;">');
            $RS2 = db_getTipoMatServ::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null,null,f($row1,'chave'));
            foreach($RS2 as $row2) {
              $w_nome .= ' - '.f($row2,'nome');
              if (f($row2,'Filho')>0) {
                $w_contOut = $w_contOut+1;
                ShowHTML('<A HREF=#"'.f($row2,'chave').'"></A>');
                ShowHTML('<span><div align="left"><img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row2,'nome').'');
                if (f($row2,'ativo')=='S') $w_class='hl'; else $w_class='lh';
                ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row2,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo">AL</A>&nbsp');
                if (f($row2,'ativo')=='S') {
                  ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_chave='.f($row2,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo seja associado a novos registros">Desativar</A>&nbsp');
                } else {
                  ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_chave='.f($row2,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo seja associado a novos registros">Ativar</A>&nbsp');
                } 
                ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row2,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo">EX</A>&nbsp');
                ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&w_copia='.f($row2,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Insere um novo tipo a partir das informações deste registro">Copiar</A>&nbsp');
                ShowHTML('       </div></span>');
                ShowHTML('   <div style="position:relative; left:12;">');
                $RS3 = db_getTipoMatServ::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null,null,f($row2,'chave'));
                foreach($RS3 as $row3) {
                  $w_nome .= ' - '.f($row3,'nome');
                  ShowHTML('<A HREF=#"'.f($row3,'chave').'"></A>');
                  ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row3,'nome').' ('.f($row3,'qt_materiais').')');
                  if (f($row3,'ativo')=='S') $w_class='hl'; else $w_class='lh';
                  ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row3,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo">AL</A>&nbsp');
                  if (f($row3,'ativo')=='S') {
                    ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_chave='.f($row3,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo seja associado a novos registros">Desativar</A>&nbsp');
                  } else {
                    ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_chave='.f($row3,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo seja associado a novos registros">Desativar</A>&nbsp');
                  } 
                  ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row3,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo">EX</A>&nbsp');
                  ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&w_copia='.f($row3,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Insere um novo tipo a partir das informações deste registro">Copiar</A>&nbsp');
                  ShowHTML('    <BR>');
                  $w_nome = str_replace(' - '.f($row3,'nome'),'',$w_nome);
                } 
                ShowHTML('   </div>');
              } else {
                $w_Imagem=$w_ImagemPadrao;
                ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row2,'nome').' ('.f($row2,'qt_materiais').')');
                if (f($row2,'ativo')=='S') $w_class='hl'; else $w_class='lh';
                ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row2,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo">AL</A>&nbsp');
                if (f($row2,'ativo')=='S') {
                  ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_chave='.f($row2,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo seja associado a novos registros">Desativar</A>&nbsp');
                } else {
                  ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_chave='.f($row2,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo seja associado a novos registros">Ativar</A>&nbsp');
                } 
                ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row2,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo">EX</A>&nbsp');
                ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&w_copia='.f($row2,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Insere um novo tipo a partir das informações deste registro">Copiar</A>&nbsp');
                ShowHTML('    <BR>');
              } 
              $w_nome=str_replace(' - '.f($row2,'nome'),'',$w_nome);
            } 
            ShowHTML('   </div>');
          } else {
            $w_Imagem=$w_ImagemPadrao;
            ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row1,'nome').' ('.f($row1,'qt_materiais').')');
            if (f($row1,'ativo')=='S') $w_class='hl'; else $w_class='lh';
            ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row1,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo">AL</A>&nbsp');
            if (f($row1,'ativo')=='S') {
              ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_chave='.f($row1,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo seja associado a novos registros">Desativar</A>&nbsp');
            } else {
              ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_chave='.f($row1,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo seja associado a novos registros">Ativar</A>&nbsp');
            } 
            ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row1,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo">EX</A>&nbsp');
            ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&w_copia='.f($row1,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Insere um novo tipo a partir das informações deste registro">Copiar</A>&nbsp');
            ShowHTML('    <BR>');
          } 
          $w_nome=str_replace(' - '.f($row1,'nome'),'',$w_nome);
        } 
        ShowHTML('   </div>');
      } else {
        $w_Imagem=$w_ImagemPadrao;
        ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row,'nome').' ('.f($row,'qt_materiais').')');
        if (f($row,'ativo')=='S') $w_class='hl'; else $w_class='lh';
        ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo">AL</A>&nbsp');
        if (f($row,'ativo')=='S') {
          ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_chave='.f($row,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo seja associado a novos registros">Desativar</A>&nbsp');
        } else {
          ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_chave='.f($row,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo seja associado a novos registros">Ativar</A>&nbsp');
        } 
        ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo">EX</A>&nbsp');
        ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&w_copia='.f($row,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Insere um novo tipo a partir das informações deste registro">Copiar</A>&nbsp');
        ShowHTML('    <BR>');
      } 
    } 
    if ($w_contOut==0) {
      // Se não achou registros
      ShowHTML('Não foram encontrados registros.');
    } 
  } elseif (strpos('CIAEDT',$O)!==false) {
    if ($O == 'C' || $O=='I' || $O=='A') {
      ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">Orientação:<ul><li>Não é permitido subordinar um tipo de material/serviço a outro que já tenha itens vinculados.</ul></b></font></td>');
      if ($O=='C') ShowHTML('      <tr><td colspan=3 align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATENÇÃO: Dados importados de outro registro. Altere os dados necessários antes de executar a inclusão.</b></font>.</td>');
    } 
    if ($O != 'C' && $O!='I' && $O!='A') $w_Disabled='disabled';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O!='C') ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('      <tr valign="top">');
    if ($O!='I' && $O!='C') {
      // Se for alteração, não deixa vincular a opção a ela mesma, nem a seus filhos
      selecaoTipoMatServSubord('<u>S</u>ubordinação:','S','Se esta opção estiver subordinada a outra já existente, informe qual.',$w_chave,$w_chave_pai,'w_chave_pai','SUBPARTE','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_nome\'; document.Form.submit();"');
    } else {
      selecaoTipoMatServSubord('<u>S</u>ubordinação:','S','Se esta opção estiver subordinada a outra já existente, informe qual.',$w_chave,$w_chave_pai,'w_chave_pai','SUBTODOS','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_nome\'; document.Form.submit();"');
    } 
    ShowHTML('      <tr><td><table border=0 width="100%" cellpadding=0 cellspacing=0>');
    ShowHTML('          <tr valign="top">');
    ShowHTML('            <td><b><u>N</u>ome:<br><INPUT ACCESSKEY="N" TYPE="TEXT" CLASS="sti" NAME="w_nome" SIZE=30 MAXLENGTH=30 VALUE="'.$w_nome.'" '.$w_Disabled.' title="Nome do tipo."></td>');
    ShowHTML('            <td><b>S<u>i</u>gla:<br><INPUT ACCESSKEY="I" TYPE="TEXT" CLASS="sti" NAME="w_sigla" SIZE=10 MAXLENGTH=10 VALUE="'.$w_sigla.'" '.$w_Disabled.' title="Sigla do tipo."></td>');
    if (nvl($w_chave_pai,'')=='') { 
      selecaoClasseMatServ('C<u>l</u>asse:','L','Indique a classe ao qual o grupo pertence.',$w_classe,null,'w_classe',null,null);
    } else {
      ShowHTML('<INPUT type="hidden" name="w_classe" value="'.$w_classe.'">');
    }
    ShowHTML('          <tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade gestora:','U','Indique a unidade responsável pela gestão deste tipo de material ou serviço',$w_gestora,null,'w_gestora',null,null);
    ShowHTML('            <td><b><u>C</u>ódigo externo:<br><INPUT ACCESSKEY="C" TYPE="TEXT" CLASS="sti" NAME="w_codigo_externo" SIZE=30 MAXLENGTH=30 VALUE="'.$w_codigo_externo.'" '.$w_Disabled.' title="Código do tipo em um sistema externo."></td>');
    ShowHTML('        </table>');
    ShowHTML('      <tr><td><b><U>D</U>escricao:<br><TEXTAREA ACCESSKEY="G" class="sti" name="w_descricao" rows=5 cols=80 title="Informe a descricao deste tipo." '.$w_Disabled.'>'.$w_descricao.'</textarea></td>');
    if ($O=='I' || $O=='C') {
      ShowHTML('      <tr align="left">');
      MontaRadioSN('Ativo?',$w_ativo,'w_ativo');
      ShowHTML('      </tr>');
    } 
    ShowHTML('      </td></tr>');
    ShowHTML('      <tr><td><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3"><input class="stb" type="submit" name="Botao" value="Gravar">&nbsp;');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
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
// Rotina de Critérios de Criterio
// -------------------------------------------------------------------------
function Criterio() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave           = $_REQUEST['w_chave'];
  $w_troca           = $_REQUEST['w_troca'];
  //Se for recarga da página
  if ($w_troca > '' && $O!='E') {   
    $w_nome         = $_REQUEST['w_nome'];
    $w_descricao    = $_REQUEST['w_descricao'];
    $w_ativo        = $_REQUEST['w_ativo'];
    $w_padrao       = $_REQUEST['w_padrao'];
    $w_item         = $_REQUEST['w_item'];
  } elseif ($O=='L') {     
    // Recupera todos os registros para a listagem
    $RS = db_getLCCriterio::getInstanceOf($dbms, null, $w_cliente, null, null, null, null, null, null);
    $RS = SortArray($RS,'nome','asc'); 
  } elseif (strpos('AEV',$O)!==false) {
    //Recupera os dados do endereço informado
    $RS = db_getLCCriterio::getInstanceOf($dbms, $w_chave, $w_cliente, null, null, null, null, null, null);
    foreach ($RS as $row) {
      $w_nome         = f($row,'nome');
      $w_descricao    = f($row,'descricao');
      $w_ativo        = f($row,'ativo');
      $w_padrao       = f($row,'padrao');
      $w_item         = f($row,'item');
    }
  }
  Cabecalho();
  ShowHTML( '<HEAD>' );
  If  (!(strpos('IAEP',$O)===false)) {
    ScriptOpen( 'JavaScript');
    ValidateOpen( 'Validacao');
     if (!(strpos('IA',$O)===false)) {    
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
    ShowHTML('          <td><font size="1"><b>Nome</font></td>');
    ShowHTML('          <td><font size="1"><b>Descrição</font></td>');
    ShowHTML('          <td><font size="1"><b>Vencedor por item</font></td>');
    ShowHTML('          <td><font size="1"><b>Padrão</font></td>');
    ShowHTML('          <td><font size="1"><b>Ativo</font></td>');
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
        ShowHTML('        <td><font size="1">'.f($row,'nome').'</td>');
        ShowHTML('        <td><font size="1">'.nvl(f($row,'descricao'),'---').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_item').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_padrao').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_ativo').'</td>');
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
    ShowHTML('        <tr><td colspan=3><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="60" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
    ShowHTML('        <tr><td colspan=3><font size="1"><b><u>D</u>escrição:</b><br><textarea '.$w_Disabled.'accesskey="D" name="w_descricao" class="sti" ROWS="3" COLS="75">'.$w_descricao.'</textarea></td>');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN( '<b>Vencedor por item?</b>', $w_item, 'w_item');
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
// Rotina de situações da licitação
// -------------------------------------------------------------------------
function Situacao() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave           = $_REQUEST['w_chave'];
  $w_troca           = $_REQUEST['w_troca'];
  //Se for recarga da página
  if ($w_troca > '') {   
    $w_nome         = $_REQUEST['w_nome'];
    $w_descricao    = $_REQUEST['w_descricao'];
    $w_ativo        = $_REQUEST['w_ativo'];
    $w_padrao       = $_REQUEST['w_padrao'];
    $w_publicar     = $_REQUEST['w_publicar'];
  } elseif ($O=='L') {     
    // Recupera todos os registros para a listagem
    $RS = db_getLCSituacao::getInstanceOf($dbms, null, $w_cliente, null, null, null, null, null, null);
    $RS = SortArray($RS,'nome','asc'); 
  } elseif (!(strpos('AEV',$O)===false)) {
    //Recupera os dados do endereço informado
    $RS = db_getLCSituacao::getInstanceOf($dbms, $w_chave, $w_cliente, null, null, null, null, null, null);
    foreach ($RS as $row) {
      $w_nome                 = f($row,'nome');
      $w_descricao            = f($row,'descricao');
      $w_ativo                = f($row,'ativo');
      $w_padrao               = f($row,'padrao');
      $w_publicar             = f($row,'publicar');
      
    }
  }
  Cabecalho();
  ShowHTML( '<HEAD>' );
  If  (!(strpos('IAEP',$O)===false)) {
    ScriptOpen( 'JavaScript');
    ValidateOpen( 'Validacao');
     if (!(strpos('IA',$O)===false)) {    
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
     BodyOpen('onLoad="document.Form.'.$w_troca.'.focus()";');
  } elseif (!(strpos('IA',$O)===false)) {
     BodyOpen('onLoad="document.Form.w_nome.focus()";');
  } elseif ($O=='E') {
     BodyOpen('onLoad="document.Form.w_assinatura.focus()";');
  } else {
     BodyOpen('onLoad="document.focus()";');
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
    ShowHTML('          <td><font size="1"><b>Nome</font></td>');
    ShowHTML('          <td><font size="1"><b>Descrição</font></td>');
    ShowHTML('          <td><font size="1"><b>Publicar no portal</font></td>');
    ShowHTML('          <td><font size="1"><b>Padrão</font></td>');
    ShowHTML('          <td><font size="1"><b>Ativo</font></td>');
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
        ShowHTML('        <td><font size="1">'.f($row,'nome').'</td>');
        ShowHTML('        <td><font size="1">'.nvl(f($row,'descricao'),'---').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_publicar').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_padrao').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_ativo').'</td>');
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
    ShowHTML('        <tr><td colspan=3><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="60" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
    ShowHTML('        <tr><td colspan=3><font size="1"><b><u>D</u>escrição:</b><br><textarea '.$w_Disabled.'accesskey="D" name="w_descricao" class="sti" ROWS="3" COLS="75">'.$w_descricao.'</textarea></td>');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN( '<b>Publica certames desta situação no portal?</b>', $w_publicar, 'w_publicar');
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
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=this.focus();');
  switch ($SG) {
    case 'CLTIPMATSE':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='C' || $O=='I' || $O=='A') {
          // Testa a existência do nome
          $RS = db_getTipoMatServ::getInstanceOf($dbms,$w_cliente,Nvl($_REQUEST['w_chave'],''),null,Nvl($_REQUEST['w_nome'],''),null,null,null,null,'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Já existe tipo de material ou serviço com este nome!\');');
            ScriptClose(); 
            retornaFormulario('w_nome');
            break;
          } 

          // Testa a existência do sigla
          $RS = db_getTipoMatServ::getInstanceOf($dbms,$w_cliente,Nvl($_REQUEST['w_chave'],''),null,null,Nvl($_REQUEST['w_sigla'],''),null,null,null,'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Já existe tipo de material ou serviço com esta sigla!\');');
            ScriptClose(); 
            retornaFormulario('w_sigla');
            break;
          } 
        } elseif ($O=='E') {
          $RS = db_getTipoMatServ::getInstanceOf($dbms,$w_cliente,Nvl($_REQUEST['w_chave'],''),null,null,null,null,null,null,'VINCULADO');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Não é possível excluir este tipo. Ele está ligado a algum material ou serviço!\');');
            ScriptClose();
            break;
            retornaFormulario('w_assinatura');
          } 
        } 
        dml_putTipoMatServ::getInstanceOf($dbms,$O,$w_cliente,Nvl($_REQUEST['w_chave'],''),Nvl($_REQUEST['w_chave_pai'],''),$_REQUEST['w_nome'],
                $_REQUEST['w_sigla'],$_REQUEST['w_classe'],$_REQUEST['w_gestora'],$_REQUEST['w_descricao'],$_REQUEST['w_codigo_externo'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
        } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'COCRITJULG':
      // Verifica se a Assinatura Eletrônica é válida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
         if ($O=='I' || $O=='A') {
          // Testa a existência do nome
          $RS = db_getLCCriterio::getInstanceOf($dbms,$_REQUEST['w_chave'], $w_cliente, Nvl($_REQUEST['w_nome'],''), null, null, null, null, 'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Já existe critério de julgamento com este nome!\');');
            ScriptClose(); 
            retornaFormulario('w_nome');
            break;
          } 
        }  
        dml_putLCCriterio::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$w_cliente,
           $_REQUEST['w_nome'],$_REQUEST['w_descricao'],$_REQUEST['w_ativo'],
           $_REQUEST['w_padrao'],$_REQUEST['w_item']);
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
    case 'COSITCERT':
      // Verifica se a Assinatura Eletrônica é válida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
         if ($O=='I' || $O=='A') {
          // Testa a existência do nome
          $RS = db_getLCSituacao::getInstanceOf($dbms,$_REQUEST['w_chave'], $w_cliente, Nvl($_REQUEST['w_nome'],''), null, null, null, null, 'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Já existe critério de julgamento com este nome!\');');
            ScriptClose(); 
            retornaFormulario('w_nome');
            break;
          } 
        }  
        dml_putLCSituacao::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$w_cliente,
           $_REQUEST['w_nome'],$_REQUEST['w_descricao'],$_REQUEST['w_ativo'],
           $_REQUEST['w_padrao'],$_REQUEST['w_publicar']);
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
  case 'TIPOMATSERV':   TipoMatServ();      break;
  case 'CRITERIO':      Criterio();         break;
  case 'SITUACAO':      Situacao();         break;
  case 'GRAVA':         Grava();            break;
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
