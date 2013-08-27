<?php
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
include_once($w_dir_volta.'classes/sp/db_getLCModEnq.php');
include_once($w_dir_volta.'classes/sp/db_getParametro.php');
include_once($w_dir_volta.'classes/sp/db_getUnidade_CL.php');
include_once($w_dir_volta.'classes/sp/db_getPersonList.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putTipoMatServ.php');
include_once($w_dir_volta.'classes/sp/dml_putLCCriterio.php');
include_once($w_dir_volta.'classes/sp/db_getLCModalidade.php');
include_once($w_dir_volta.'classes/sp/dml_putLCSituacao.php');
include_once($w_dir_volta.'classes/sp/dml_putLCModEnq.php');
include_once($w_dir_volta.'classes/sp/dml_putCLParametro.php');
include_once($w_dir_volta.'classes/sp/dml_putUnidade_CL.php');
include_once($w_dir_volta.'classes/sp/dml_putCLUsuario.php');
include_once($w_dir_volta.'funcoes/selecaoTipoDespacho.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoTipoMatServSubord.php');
include_once($w_dir_volta.'funcoes/selecaoClasseMatServ.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoTelaExibicao.php');
include_once($w_dir_volta.'funcoes/selecaoLCSituacao.php');

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

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par            = upper($_REQUEST['par']);
$P1             = $_REQUEST['P1'];
$P2             = $_REQUEST['P2'];
$P3             = nvl($_REQUEST['P3'],1);
$P4             = nvl($_REQUEST['P4'],$conPageSize);
$TP             = $_REQUEST['TP'];
$SG             = upper($_REQUEST['SG']);
$R              = $_REQUEST['R'];
$O              = upper($_REQUEST['O']);
$w_assinatura   = $_REQUEST['w_assinatura'];
$w_pagina       = 'tabelas.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_cl/';
$w_troca        = $_REQUEST['w_troca'];
$w_copia        = $_REQUEST['w_copia'];
$p_ordena       = lower(trim($_REQUEST['p_ordena']));

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

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

if(nvl($w_menu,'')!=''){
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);
  $w_libera_edicao = f($RS_Menu,'libera_edicao');
  
  if ($w_libera_edicao=='N' && strpos('LP',$O)===false) {
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</head>');
    BodyOpen('onLoad=this.focus();');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><b>Operação não permitida!</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Rodape();
    exit();
  }
}

// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$sql = new db_getLinkSubMenu; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu='Existe';
} else {
  $w_submenu='';
} 

// Recupera a configuração do serviço
if ($P2>0)   { $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$P2); }
else         { $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu); }

// Se for sub-menu, pega a configuração do pai
if ($RS_Menu['ultimo_nivel']=='S') {
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 

$sql = new db_getParametro; $RS_Param = $sql->getInstanceOf($dbms,$w_cliente,'CL',null);
foreach($RS_Param as $row){ $RS_Param = $row; }

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
    $sql = new db_getTipoMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,null,null,'REGISTROS');
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
    $sql = new db_getTipoMatServ; $RS_Tipo = $sql->getInstanceOf($dbms,$w_cliente,$w_chave_pai,null,null,null,null,null,null,'REGISTROS');
    foreach ($RS_Tipo as $row) { $RS_Tipo = $row; break; }
    $w_classe = f($RS_Tipo,'classe');
  }

  Cabecalho();
  head();
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
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
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
    if ($w_libera_edicao=='S') {
      ShowHTML('      <tr><td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    }
    ShowHTML('      <tr><td height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td><b>');
    $sql = new db_getTipoMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null,null,'IS NULL');
    $w_contOut = 0;
    foreach($RS as $row) {
      $w_nome  = f($row,'nome');
      $w_contOut = $w_contOut+1;
      if (f($row,'Filho')>0) {
        ShowHTML('<A HREF=#"'.f($row,'chave').'"></A>');
        ShowHTML('<span><div align="left"><img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row,'sigla').' - '.f($row,'nome').'');
        if ($w_libera_edicao=='S') {
          if (f($row,'ativo')=='S') $w_class='hl'; else $w_class='lh';
          ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo">AL</A>&nbsp');
          if (f($row,'ativo')=='S') {
            ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_chave='.f($row,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo seja associado a novos registros">Desativar</A>&nbsp');
          } else {
            ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_chave='.f($row,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo seja associado a novos registros">Ativar</A>&nbsp');
          } 
          ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo">EX</A>&nbsp');
          ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&w_copia='.f($row,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Insere um novo tipo a partir das informações deste registro">Copiar</A>&nbsp');
        }
        ShowHTML('       </div></span>');
        ShowHTML('   <div style="position:relative; left:12;">');
        $sql = new db_getTipoMatServ; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null,null,f($row,'chave'));
        foreach($RS1 as $row1) {
          $w_nome .= ' - '.f($row1,'nome');
          if (f($row1,'Filho')>0) {
            $w_contOut=$w_contOut+1;
            ShowHTML('<A HREF=#"'.f($row1,'chave').'"></A>');
            ShowHTML('<span><div align="left"><img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row1,'sigla').' - '.f($row1,'nome').'');
            if ($w_libera_edicao=='S') {
              if (f($row1,'ativo')=='S') $w_class='hl'; else $w_class='lh';
              ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row1,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo">AL</A>&nbsp');
              if (f($row1,'ativo')=='S') {
                ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_chave='.f($row1,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo seja associado a novos registros">Desativar</A>&nbsp');
              } else {
                ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_chave='.f($row1,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo seja associado a novos registros">Ativar</A>&nbsp');
              } 
              ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row1,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo">EX</A>&nbsp');
              ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&w_copia='.f($row1,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Insere um novo tipo a partir das informações deste registro">Copiar</A>&nbsp');
            }
            ShowHTML('       </div></span>');
            ShowHTML('   <div style="position:relative; left:12;">');
            $sql = new db_getTipoMatServ; $RS2 = $sql->getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null,null,f($row1,'chave'));
            foreach($RS2 as $row2) {
              $w_nome .= ' - '.f($row2,'nome');
              if (f($row2,'Filho')>0) {
                $w_contOut = $w_contOut+1;
                ShowHTML('<A HREF=#"'.f($row2,'chave').'"></A>');
                ShowHTML('<span><div align="left"><img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row2,'sigla').' - '.f($row2,'nome').'');
                if ($w_libera_edicao=='S') {
                  if (f($row2,'ativo')=='S') $w_class='hl'; else $w_class='lh';
                  ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row2,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo">AL</A>&nbsp');
                  if (f($row2,'ativo')=='S') {
                    ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_chave='.f($row2,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo seja associado a novos registros">Desativar</A>&nbsp');
                  } else {
                    ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_chave='.f($row2,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo seja associado a novos registros">Ativar</A>&nbsp');
                  } 
                  ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row2,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo">EX</A>&nbsp');
                  ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&w_copia='.f($row2,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Insere um novo tipo a partir das informações deste registro">Copiar</A>&nbsp');
                }
                ShowHTML('       </div></span>');
                ShowHTML('   <div style="position:relative; left:12;">');
                $sql = new db_getTipoMatServ; $RS3 = $sql->getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null,null,f($row2,'chave'));
                foreach($RS3 as $row3) {
                  $w_nome .= ' - '.f($row3,'nome');
                  ShowHTML('<A HREF=#"'.f($row3,'chave').'"></A>');
                  ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row3,'sigla').' - '.f($row3,'nome').' ('.f($row3,'qt_materiais').')');
                  if ($w_libera_edicao=='S') {
                    if (f($row3,'ativo')=='S') $w_class='hl'; else $w_class='lh';
                    ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row3,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo">AL</A>&nbsp');
                    if (f($row3,'ativo')=='S') {
                      ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_chave='.f($row3,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo seja associado a novos registros">Desativar</A>&nbsp');
                    } else {
                      ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_chave='.f($row3,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo seja associado a novos registros">Desativar</A>&nbsp');
                    } 
                    ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row3,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo">EX</A>&nbsp');
                    ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&w_copia='.f($row3,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Insere um novo tipo a partir das informações deste registro">Copiar</A>&nbsp');
                  }
                  ShowHTML('    <BR>');
                  $w_nome = str_replace(' - '.f($row3,'nome'),'',$w_nome);
                } 
                ShowHTML('   </div>');
              } else {
                $w_Imagem=$w_ImagemPadrao;
                ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row2,'sigla').' - '.f($row2,'nome').' ('.f($row2,'qt_materiais').')');
                if ($w_libera_edicao=='S') {
                  if (f($row2,'ativo')=='S') $w_class='hl'; else $w_class='lh';
                  ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row2,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo">AL</A>&nbsp');
                  if (f($row2,'ativo')=='S') {
                    ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_chave='.f($row2,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo seja associado a novos registros">Desativar</A>&nbsp');
                  } else {
                    ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_chave='.f($row2,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo seja associado a novos registros">Ativar</A>&nbsp');
                  } 
                  ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row2,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo">EX</A>&nbsp');
                  ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&w_copia='.f($row2,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Insere um novo tipo a partir das informações deste registro">Copiar</A>&nbsp');
                }
                ShowHTML('    <BR>');
              } 
              $w_nome=str_replace(' - '.f($row2,'nome'),'',$w_nome);
            } 
            ShowHTML('   </div>');
          } else {
            $w_Imagem=$w_ImagemPadrao;
            ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row1,'sigla').' - '.f($row1,'nome').' ('.f($row1,'qt_materiais').')');
            if ($w_libera_edicao=='S') {
              if (f($row1,'ativo')=='S') $w_class='hl'; else $w_class='lh';
              ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row1,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo">AL</A>&nbsp');
              if (f($row1,'ativo')=='S') {
                ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_chave='.f($row1,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo seja associado a novos registros">Desativar</A>&nbsp');
              } else {
                ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_chave='.f($row1,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo seja associado a novos registros">Ativar</A>&nbsp');
              } 
              ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row1,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo">EX</A>&nbsp');
              ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&w_copia='.f($row1,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Insere um novo tipo a partir das informações deste registro">Copiar</A>&nbsp');
            }
            ShowHTML('    <BR>');
          } 
          $w_nome=str_replace(' - '.f($row1,'nome'),'',$w_nome);
        } 
        ShowHTML('   </div>');
      } else {
        $w_Imagem=$w_ImagemPadrao;
        ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row,'sigla').' - '.f($row,'nome').' ('.f($row,'qt_materiais').')');
        if ($w_libera_edicao=='S') {
          if (f($row,'ativo')=='S') $w_class='hl'; else $w_class='lh';
          ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informações deste tipo">AL</A>&nbsp');
          if (f($row,'ativo')=='S') {
            ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_chave='.f($row,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Impede que este tipo seja associado a novos registros">Desativar</A>&nbsp');
          } else {
            ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_chave='.f($row,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Permite que este tipo seja associado a novos registros">Ativar</A>&nbsp');
          } 
          ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo">EX</A>&nbsp');
          ShowHTML('       <A class="'.$w_class.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&w_copia='.f($row,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Insere um novo tipo a partir das informações deste registro">Copiar</A>&nbsp');
        }
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
    ShowHTML('      <tr><td><b><U>D</U>escrição:<br><TEXTAREA ACCESSKEY="G" class="sti" name="w_descricao" rows=5 cols=80 title="Informe a descricao deste tipo." '.$w_Disabled.'>'.$w_descricao.'</textarea></td>');
    if ($O=='I' || $O=='C') {
      ShowHTML('      <tr align="left">');
      MontaRadioSN('Ativo?',$w_ativo,'w_ativo');
      ShowHTML('      </tr>');
    } 
    ShowHTML('      </td></tr>');
    ShowHTML('      <tr><td><b>'.$_SESSION['LABEL_CAMPO'].':<br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3"><input class="stb" type="submit" name="Botao" value="Gravar">&nbsp;');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
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
  $w_sq_lcjulgamento = $_REQUEST['w_sq_lcjulgamento'];
  $w_chave           = $_REQUEST['w_chave'];
  $w_troca           = $_REQUEST['w_troca'];
  $p_ordena          = $_REQUEST['p_ordena'];
  //Se for recarga da página
  if($w_troca > '' && $O!='E') {   
    $w_nome         = $_REQUEST['w_nome'];
    $w_descricao    = $_REQUEST['w_descricao'];
    $w_ativo        = $_REQUEST['w_ativo'];
    $w_padrao       = $_REQUEST['w_padrao'];
    $w_item         = $_REQUEST['w_item'];
  } elseif ($O=='L') {     
    // Recupera todos os registros para a listagem
    $sql = new db_getLCCriterio; $RS = $sql->getInstanceOf($dbms, null, $w_cliente, null, null, null, null, null, null);
    if(nvl($p_ordena,'')!=''){
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome_resumido','asc');
    }else{
      $RS = SortArray($RS,'nome','asc');
    } 
  } elseif (strpos('AEV',$O)!==false) {
    //Recupera os dados do endereço informado
    $sql = new db_getLCCriterio; $RS = $sql->getInstanceOf($dbms, $w_chave, $w_cliente, null, null, null, null, null, null);
    foreach ($RS as $row) {
      $w_nome         = f($row,'nome');
      $w_descricao    = f($row,'descricao');
      $w_ativo        = f($row,'ativo');
      $w_padrao       = f($row,'padrao');
      $w_item         = f($row,'item');
    }
  }
  Cabecalho();
  head();
  ShowHTML( '<HEAD>' );
  If  (!(strpos('IAEP',$O)===false)) {
    ScriptOpen( 'JavaScript');
    ValidateOpen( 'Validacao');
     if (!(strpos('IA',$O)===false)) {    
       Validate('w_nome','Nome','1','1','2','60','1','1');
       Validate('w_descricao','Descrição', '1', '', '5', '1000', '1', '1');
       Validate('w_assinatura',$_SESSION['LABEL_ALERTA'], '1', '1', '6', '30', '1', '1');
     } elseif ($O=='E') {
       Validate('w_assinatura', $_SESSION['LABEL_ALERTA'], '1', '1', '6', '30', '1', '1');
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
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus();"');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad="document.Form.w_nome.focus();"');
  } elseif ($O=='L') {
    BodyOpen('onLoad="this.focus();"');
  } else {
    BodyOpen('onLoad="document.Form.w_assinatura.focus();"');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  If ($O=='L') {
    //Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr>');
    if ($w_libera_edicao=='S') {
      ShowHTML('    <td><font size="1"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    }
    ShowHTML('    <td align="right"><font size="1">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Nome','nome').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Descrição','descricao').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Vencedor por item','nm_item').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Padrão','nm_padrao').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Ativo','nm_ativo').'</font></td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td class="remover"><font size="1"><b>Operações</font></td>');
    }
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
        if ($w_libera_edicao=='S') {
          ShowHTML('        <td class="remover" align="top" nowrap><font size="1">');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
          ShowHTML('        </td>');
        }
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
    ShowHTML('      <tr><td align="LEFT"><font size="1"><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
  ShowHTML('           <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen( 'JavaScript');
    ShowHTML(' alert("Opção não disponível");');
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
  $p_ordena          = $_REQUEST['p_ordena'];
  //Se for recarga da página
  if($w_troca > '' && $O!='E') {   
    $w_nome         = $_REQUEST['w_nome'];
    $w_descricao    = $_REQUEST['w_descricao'];
    $w_ativo        = $_REQUEST['w_ativo'];
    $w_padrao       = $_REQUEST['w_padrao'];
    $w_publicar     = $_REQUEST['w_publicar'];
    $w_conclusao    = $_REQUEST['w_conclusao'];
    $w_tela         = $_REQUEST['w_tela'];
  } elseif ($O=='L') {     
    // Recupera todos os registros para a listagem
    $sql = new db_getLCSituacao; $RS = $sql->getInstanceOf($dbms, null, $w_cliente, null, null, null, null, null, null);
    if(nvl($p_ordena,'')!=''){
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome','asc');
    }else{
      $RS = SortArray($RS,'nome','asc');
    } 
  } elseif (!(strpos('AEV',$O)===false)) {
    //Recupera os dados do endereço informado
    $sql = new db_getLCSituacao; $RS = $sql->getInstanceOf($dbms, $w_chave, $w_cliente, null, null, null, null, null, null);
    foreach ($RS as $row) {
      $w_nome                 = f($row,'nome');
      $w_descricao            = f($row,'descricao');
      $w_ativo                = f($row,'ativo');
      $w_padrao               = f($row,'padrao');
      $w_publicar             = f($row,'publicar');
      $w_conclusao            = f($row,'conclui_sem_proposta');
      $w_tela                 = f($row,'tela_exibicao');
      break;
    }
  }
  Cabecalho();
  head();
  ShowHTML( '<HEAD>' );
  If  (!(strpos('IAEP',$O)===false)) {
    ScriptOpen( 'JavaScript');
    ValidateOpen( 'Validacao');
     if (!(strpos('IA',$O)===false)) {
       Validate('w_nome','Nome','1','1','2','60','1','1');
       Validate('w_descricao','Descrição', '1', '', '5', '1000', '1', '1');
       Validate('w_tela','Tela de exibição','SELECT','1',1,1,'1','');
       Validate('w_assinatura',$_SESSION['LABEL_ALERTA'], '1', '1', '6', '30', '1', '1');
     } elseif ($O=='E') {
       Validate('w_assinatura', $_SESSION['LABEL_ALERTA'], '1', '1', '6', '30', '1', '1');
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
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus();"');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad="document.Form.w_nome.focus();"');
  } elseif ($O=='L') {
    BodyOpen('onLoad="this.focus();"');
  } else {
    BodyOpen('onLoad="document.Form.w_assinatura.focus();"');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  If ($O=='L') {
    //Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr>');
    if ($w_libera_edicao=='S') {
      ShowHTML('<td><font size="1"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    }
    ShowHTML('    <td align="right"><font size="1">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Nome','nome').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Descrição','descricao').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Publicar no portal','nm_publicar').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Conclui sem proposta','conclui_sem_proposta').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Tela exibição','tela_exibicao').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Padrão','nm_padrao').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Ativo','nm_ativo').'</font></td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td class="remover"><font size="1"><b>Operações</font></td>');
    }
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
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_conclui_sem_proposta').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_tela_exibicao').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_padrao').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_ativo').'</td>');
        if ($w_libera_edicao=='S') {
          ShowHTML('        <td class="remover" align="top" nowrap><font size="1">');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
          ShowHTML('        </td>');
        }
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
    MontaRadioSN( '<b>Permite conclusão sem proposta?</b>', $w_conclusao, 'w_conclusao');
    selecaoTelaExibicao('<U>T</U>ela de exibição:','T','Indique a tela onde esta situação pode ser exibida',$w_tela,null,'w_tela',null,null);
    ShowHTML('        <tr valign="top">');
    MontaRadioSN( '<b>Ativo?</b>', $w_ativo, 'w_ativo');
    MontaRadioNS( '<b>Padrão?</b>',$w_padrao, 'w_padrao');
    ShowHTML('           </table>');
    ShowHTML('      <tr><td align="LEFT"><font size="1"><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina de enquadramentos de modalidade
// -------------------------------------------------------------------------
function Enquadramento() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave           = $_REQUEST['w_chave'];
  $w_chave_aux       = $_REQUEST['w_chave_aux'];
  
  $sql = new db_getLCModalidade; $RS_Modal = $sql->getInstanceOf($dbms, $w_chave, $w_cliente, null, null, null, null);
  foreach($RS_Modal as $row) { $RS_Modal = $row; break; }
  
  $w_troca           = $_REQUEST['w_troca'];
  //Se for recarga da página
  if ($w_troca > '' && $O!='E') {   
    $w_sigla        = $_REQUEST['w_sigla'];
    $w_descricao    = $_REQUEST['w_descricao'];
    $w_ativo        = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {     
    // Recupera todos os registros para a listagem
    $sql = new db_getLCModEnq; $RS = $sql->getInstanceOf($dbms, $w_chave, null, null, null, null);
    $RS = SortArray($RS,'sigla','asc'); 
  } elseif (!(strpos('AEV',$O)===false)) {
    //Recupera os dados do endereço informado
    $sql = new db_getLCModEnq; $RS = $sql->getInstanceOf($dbms, $w_chave, $w_chave_aux, null, null, null);
    foreach ($RS as $row) {
      $w_sigla                = f($row,'sigla');
      $w_descricao            = f($row,'descricao');
      $w_ativo                = f($row,'ativo');
    }
  }
  Cabecalho();
  head();
  If  (!(strpos('IAEP',$O)===false)) {
    ScriptOpen( 'JavaScript');
    ValidateOpen( 'Validacao');
     if (!(strpos('IA',$O)===false)) {    
       Validate('w_chave','Modalidade','SELECT','1',1,18,'','0123456789');
       Validate('w_sigla','sigla','1','1','2','60','1','1');
       Validate('w_descricao','Descrição', '1', '', '5', '255', '1', '1');
       Validate('w_assinatura',$_SESSION['LABEL_ALERTA'], '1', '1', '6', '30', '1', '1');
     } elseif ($O=='E') {
       Validate('w_assinatura', $_SESSION['LABEL_ALERTA'], '1', '1', '6', '30', '1', '1');
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
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus();"');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad="document.Form.w_sigla.focus();"');
  } elseif ($O=='L') {
    BodyOpen('onLoad="this.focus();"');
  } else {
    BodyOpen('onLoad="document.Form.w_assinatura.focus();"');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Exibe os dados da modalidade
  ShowHTML('<tr><td align="center" bgcolor="#FAEBD7" colspan=3><table border=1 width="100%"><tr><td>');
  ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('      <tr><td><table border=0 width="100%">');
  ShowHTML('          <tr valign="top"><td align="center"><b>'.upper(f($RS_Modal,'nome')).'</b></td></tr>');
  ShowHTML('      </table>');
  ShowHTML('    </TABLE>');
  ShowHTML('</table>');
  If ($O=='L') {
    //Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><font size="1"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('        <a accesskey="F" class="ss" HREF="javascript:this.status.value;" onClick="window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right"><font size="1">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>Sigla</font></td>');
    ShowHTML('          <td><font size="1"><b>Descrição</font></td>');
    ShowHTML('          <td><font size="1"><b>Ativo</font></td>');
    ShowHTML('          <td class="remover"><font size="1"><b>Operações</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
    // Se não foram selecionados registros, exibe mensagem
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td><font size="1">'.f($row,'sigla').'</td>');
        ShowHTML('        <td><font size="1">'.nvl(f($row,'descricao'),'---').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td class="remover" align="top" nowrap><font size="1">');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
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
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0 cellpadding=0>');
    ShowHTML('        <tr><td colspan=3><b><u>S</u>igla:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sigla" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_sigla.'"></td>');
    ShowHTML('        <tr><td colspan=3><font size="1"><b><u>D</u>escrição:</b><br><textarea '.$w_Disabled.'accesskey="D" name="w_descricao" class="sti" ROWS="3" COLS="75">'.$w_descricao.'</textarea></td>');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN( '<b>Ativo?</b>', $w_ativo, 'w_ativo');
    ShowHTML('           </table>');
    ShowHTML('      <tr><td align="LEFT"><font size="1"><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}
// =========================================================================
// Rotina da tabela de parâmetros do módulo de Compras e Licitação
// -------------------------------------------------------------------------
function Parametro() {
  extract($GLOBALS);
  global $w_Disabled;
 
  $sql = new db_getParametro; $RS = $sql->getInstanceOf($dbms,$w_cliente,'CL',null);
  foreach($RS as $row){$RS=$row;}
  $w_ano_corrente            = f($RS,'ano_corrente');
  $w_dias_validade_pesquisa  = f($RS,'dias_validade_pesquisa');
  $w_dias_aviso_pesquisa     = f($RS,'dias_aviso_pesquisa');
  $w_percentual_acrescimo    = f($RS,'percentual_acrescimo');
  $w_compra_central          = f($RS,'compra_central');
  $w_pesquisa_central        = f($RS,'pesquisa_central');
  $w_contrato_central        = f($RS,'contrato_central');
  $w_banco_ata_central       = f($RS,'banco_ata_central');
  $w_banco_preco_central     = f($RS,'banco_preco_central');
  $w_codificacao_central     = f($RS,'codificacao_central');
  $w_cadastrador_geral       = f($RS,'cadastrador_geral');
  $w_pede_valor_pedido       = f($RS,'pede_valor_pedido');
  $w_automatico              = f($RS,'codificacao_automatica');
  $w_prefixo                 = f($RS,'prefixo');
  $w_sequencial              = f($RS,'sequencial');
  $w_sufixo                  = f($RS,'sufixo');
  Cabecalho();
  head();
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  Validate('w_dias_validade_pesquisa','Dias de validade da pesquisa','1','1','1','4','','0123456789');
  Validate('w_dias_aviso_pesquisa','Número de dias da pesquisa','1','1','1','4','','0123456789');
  Validate('w_percentual_acrescimo','Percentual','1','1','1','18','','0123456789');
  Validate('w_ano_corrente','Ano corrente','1','1','4','4','','1');
  Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','15','1','1');
  ShowHTML('  theForm.Botao.disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');  
  BodyOpen('onLoad=\'document.Form.w_dias_validade_pesquisa.focus()\';');
  ShowHTML('<B><FONT COLOR="#000000">'.str_replace('Listagem','Alteração',$w_TP).'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('    <table width="97%" border="0">');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td><b><U>D</U>ias de validade da pesquisa:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="w_dias_validade_pesquisa" size="4" maxlength="4" value="'.$w_dias_validade_pesquisa.'"></td>');
  ShowHTML('        <td><b><U>N</U>úmero de dias da pesquisa:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="w_dias_aviso_pesquisa" size="4" maxlength="2" value="'.$w_dias_aviso_pesquisa.'"></td>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td><b><U>P</U>ercentual:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="sti" type="text" name="w_percentual_acrescimo" size="4" maxlength="18" value="'.$w_percentual_acrescimo.'"></td>');
  MontaRadioSN('      <b>Atendimento centralizado dos pedidos de compra</b>',$w_compra_central,'w_compra_central');
  ShowHTML('      <tr valign="top">');
  MontaRadioSN('    <b>Pesquisa central</b>',$w_pesquisa_central,'w_pesquisa_central');
  MontaRadioSN('    <b>Contrato Central</b>',$w_contrato_central,'w_contrato_central');
  ShowHTML('      <tr valign="top">');
  MontaRadioSN('    <b>Banco ata central</b>',$w_banco_ata_central,'w_banco_ata_central');
  MontaRadioSN('    <b>Banco preço central</b>',$w_banco_preco_central,'w_banco_preco_central');
  ShowHTML('      <tr valign="top">');
  MontaRadioSN('    <b>Codificação Central</b>',$w_codificacao_central,'w_codificacao_central');
  MontaRadioNS('    <b>Pedido de compra solicita valor?</b>',$w_pede_valor_pedido,'w_pede_valor_pedido','Se sim, o usuário deve informar o valor estimado; caso contrário, será calculado a partir dos itens.');
  ShowHTML('      <tr valign="top">');
  MontaRadioSN('    <b>Codificação automática de materiais</b>',$w_automatico,'w_automatico');
  MontaRadioNS('    <b>Usuários são cadastradores gerais?</b>',$w_cadastrador_geral,'w_cadastrador_geral');
  ShowHTML('      <tr valign="top"><td colspan="2"><table width="97%" border="0">');
  ShowHTML('        <td><b>Ano <U>c</U>orrente:<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="sti" type="text" name="w_ano_corrente" size="4" maxlength="4" value="'.$w_ano_corrente.'"></td>');
  ShowHTML('     </table>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('      <tr valign="top"><td colspan="2"><b>'.$_SESSION['LABEL_CAMPO'].':<br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
  ShowHTML('      <tr><td align="center" colspan="2"><hr>');
  ShowHTML('      <tr><td align="center" colspan="2"><input class="stb" type="submit" name="Botao" value="Gravar"></td></tr>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}
// =========================================================================
// Rotina de unidade
// -------------------------------------------------------------------------

function Unidade() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave  = $_REQUEST['w_chave'];

  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_unidade_pai          = $_REQUEST['w_unidade_pai'];
    $w_nome                 = $_REQUEST['w_nome'];
    $w_sigla                = $_REQUEST['w_sigla'];
    $w_realiza_compra       = $_REQUEST['w_realiza_compra'];
    $w_solicita_compra      = $_REQUEST['w_solicita_compra'];
    $w_registra_pesquisa    = $_REQUEST['w_registra_pesquisa'];
    $w_registra_contrato    = $_REQUEST['w_registra_contrato'];
    $w_registra_judicial    = $_REQUEST['w_registra_judicial'];
    $w_controla_banco_ata   = $_REQUEST['w_controla_banco_ata'];
    $w_controla_banco_preco = $_REQUEST['w_controla_banco_preco'];
    $w_codifica_item        = $_REQUEST['w_codifica_item'];
    $w_codificacao_restrita = $_REQUEST['w_codificacao_restrita'];
    $w_padrao               = $_REQUEST['w_padrao'];
    $w_ativo                = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getUnidade_CL; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,null,null);
    $RS = SortArray($RS,'ordena','asc');
  } elseif (!(strpos('AEV',$O)===false)) {
    // Recupera os dados do endereço informado
    $sql = new db_getUnidade_CL; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_unidade_pai          = f($RS,'sq_unidade_pai');
    $w_nome                 = f($RS,'nome');
    $w_sigla                = f($RS,'sigla');
    $w_realiza_compra       = f($RS,'realiza_compra');
    $w_solicita_compra      = f($RS,'solicita_compra');
    $w_registra_pesquisa    = f($RS,'registra_pesquisa');
    $w_registra_contrato    = f($RS,'registra_contrato');
    $w_registra_judicial    = f($RS,'registra_judicial');
    $w_controla_banco_ata   = f($RS,'controla_banco_ata');
    $w_controla_banco_preco = f($RS,'controla_banco_preco');
    $w_codifica_item        = f($RS,'codifica_item');
    $w_codificacao_restrita = f($RS,'codificacao_restrita');
    $w_padrao               = f($RS,'unidade_padrao');
    $w_ativo                = f($RS,'ativo');
  } 
  Cabecalho();
  head();
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    FormataCNPJ();
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      if ($O=='I') {
        Validate('w_chave','Unidade','HIDDEN','1','1','18','','1');
      } 
      ShowHTML('  if (theForm.w_chave.value==theForm.w_unidade_pai[theForm.w_unidade_pai.selectedIndex].value) {');
      ShowHTML('     alert(\'Não é permitido subordinar uma unidade a si mesma!\'); ');
      ShowHTML('     theForm.w_unidade_pai.focus(); ');
      ShowHTML('     return false; ');
      ShowHTML('  }; ');
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
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
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (strpos('I',$O)!==false) {
    BodyOpen('onLoad=\'document.Form.w_unidade_pai.focus()\';');
  } elseif (strpos('A',$O)!==false) {
    BodyOpen('onLoad=\'document.Form.w_unidade_pai.focus()\';');
  } elseif ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpenClean(null);
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>'); 
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr>');
    if ($w_libera_edicao=='S') {
      ShowHTML('<td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    }
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td colspan=2 rowspan=2><b>Unidade</td>');
    ShowHTML('          <td colspan=2><b>Compras</td>');
    ShowHTML('          <td colspan=3><b>Registra</td>');
    ShowHTML('          <td colspan=2><b>Banco</td>');
    ShowHTML('          <td rowspan=2><b>Codifica Itens</td>');
    ShowHTML('          <td rowspan=2><b>Padrão</td>');
    ShowHTML('          <td rowspan=2><b>Ativo</td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td class="remover" rowspan=2><b>Operações</td>');
    }
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Solicita</td>');
    ShowHTML('          <td><b>Realiza</td>');
    ShowHTML('          <td><b>Pesquisa</td>');
    ShowHTML('          <td><b>Contrato</td>');
    ShowHTML('          <td><b>Judicial</td>');
    ShowHTML('          <td><b>Atas</td>');
    ShowHTML('          <td><b>Preços</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=15 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        if (nvl(f($row,'sq_unidade_pai'),'')=='') {
          ShowHTML('        <td colspan=2>'.f($row,'nome').' ('.f($row,'sigla').')</td>');
        } else {
          ShowHTML('        <td width="1%">&rarr;<td>'.f($row,'nome').' ('.f($row,'sigla').')</td>');
        }
        ShowHTML('        <td align="center">'.retornaSimNao(f($row,'solicita_compra'),'IMAGEM').'</td>');
        ShowHTML('        <td align="center">'.retornaSimNao(f($row,'realiza_compra'),'IMAGEM').'</td>');
        ShowHTML('        <td align="center">'.retornaSimNao(f($row,'registra_pesquisa'),'IMAGEM').'</td>');
        ShowHTML('        <td align="center">'.retornaSimNao(f($row,'registra_contrato'),'IMAGEM').'</td>');
        ShowHTML('        <td align="center">'.retornaSimNao(f($row,'registra_judicial'),'IMAGEM').'</td>');
        ShowHTML('        <td align="center">'.retornaSimNao(f($row,'controla_banco_ata'),'IMAGEM').'</td>');
        ShowHTML('        <td align="center">'.retornaSimNao(f($row,'controla_banco_preco'),'IMAGEM').'</td>');
        if(f($row,'codifica_item')=='S' && f($row,'codificacao_restrita')=='S') {
          ShowHTML('        <td align="center">Restrita</td>');
        } else {
          ShowHTML('        <td align="center">'.retornaSimNao(f($row,'codifica_item'),'IMAGEM').'</td>');
        }
        ShowHTML('        <td align="center">'.retornaSimNao(f($row,'unidade_padrao'),'IMAGEM').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        if ($w_libera_edicao=='S') {
          ShowHTML('        <td class="remover" align="top" nowrap>');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
          ShowHTML('        </td>');
        }
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET'),ceil(count($RS)/$P4),$P3,$P4,count($RS));
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled   = ' DISABLED ';
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_nome" value="'.$w_nome.'">');
    ShowHTML('<INPUT type="hidden" name="w_sigla" value="'.$w_sigla.'">');
    if ($O!='I') {
      ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    } 
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td colspan=3><table border="0" width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    if ($O=='I') {
      SelecaoUnidade('<U>U</U>nidade:','U',null,$w_chave,null,'w_chave',null,null);
    } else {
      ShowHTML('           <td>Unidade:<br><b>'.$w_nome.' ('.$w_sigla.')</b><br><br>');
    } 
    ShowHTML('           </table>');
    ShowHTML('      <tr><td colspan=3><table border="0" width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade pai:','U','Deixe em branco apenas se a unidade for numeradora.',$w_unidade_pai,null,'w_unidade_pai','MOD_CL_PAI','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_unidade_pai\'; document.Form.submit();"');
    ShowHTML('           </table>');
    ShowHTML('      <tr valign="top">');
    // Apenas unidades de nível zero (sem pai) podem ter controle automático de numeração
    if (nvl($w_unidade_pai,'')=='') {
      ShowHTML('<INPUT type="hidden" name="w_realiza_compra" value="S">');
      ShowHTML('<INPUT type="hidden" name="w_solicita_compra" value="S">');
      ShowHTML('<INPUT type="hidden" name="w_registra_pesquisa" value="S">');
      ShowHTML('<INPUT type="hidden" name="w_registra_contrato" value="S">');
      ShowHTML('<INPUT type="hidden" name="w_registra_judicial" value="S">');
      ShowHTML('<INPUT type="hidden" name="w_controla_banco_ata" value="S">');
      ShowHTML('<INPUT type="hidden" name="w_controla_banco_preco" value="S">');
      ShowHTML('<INPUT type="hidden" name="w_codifica_item" value="S">');
      ShowHTML('<INPUT type="hidden" name="w_codificacao_restrita" value="N">');
      ShowHTML('           <td><b>Realiza compras</b>?<br><b>Sim</b><br><br>');
      ShowHTML('           <td><b>Solicita compras</b>?<br><b>Sim</b><br><br>');
      ShowHTML('           <td><b>Registra pesquisa</b>?<br><b>Sim</b><br><br>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('           <td><b>Registra contrato</b>?<br><b>Sim</b><br><br>');
      ShowHTML('           <td><b>Registra judicial</b>?<br><b>Sim</b><br><br>');
      ShowHTML('           <td><b>Controla banco de atas</b>?<br><b>Sim</b><br><br>');
      ShowHTML('      <tr valign="top">');      
      ShowHTML('           <td><b>Controla bando de preços</b>?<br><b>Sim</b><br><br>');
      ShowHTML('           <td><b>Codifica item</b>?<br><b>Sim</b><br><br>');
      ShowHTML('           <td><b>Codificação restrita</b>?<br><b>Não</b><br><br>');
    } else {
      MontaRadioNS('<b>Realiza compras</b>?',$w_realiza_compra,'w_realiza_compra');
      MontaRadioNS('<b>Solicita compras</b>?',$w_solicita_compra,'w_solicita_compra');
      MontaRadioNS('<b>Registra pesquisa</b>?',$w_registra_pesquisa,'w_registra_pesquisa');
      ShowHTML('      <tr valign="top">');      
      MontaRadioNS('<b>Registra contrato</b>?',$w_registra_contrato,'w_registra_contrato');
      MontaRadioNS('<b>Registra judicial</b>?',$w_registra_judicial,'w_registra_judicial');
      MontaRadioNS('<b>Controla banco de atas</b>?',$w_controla_banco_ata,'w_controla_banco_ata');
      ShowHTML('      <tr valign="top">');      
      MontaRadioNS('<b>Controla banco de preços</b>?',$w_controla_banco_preco,'w_controla_banco_preco');
      MontaRadioNS('<b>Codifica item</b>?',$w_codifica_item,'w_codifica_item',null,null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_assinatura\'; document.Form.submit();"');
      if($w_codifica_item=='S')MontaRadioNS('<b>Codificação restrita</b>?',$w_codificacao_restrita,'w_codificacao_restrita');
      else                     ShowHTML('<INPUT type="hidden" name="w_codificacao_restrita" value="N">');

    }
    ShowHTML('      <tr valign="top">');
    MontaRadioSN('<b>Ativo</b>?',$w_ativo,'w_ativo');
    if (nvl($w_unidade_pai,'')=='') {
      MontaRadioNS('<b>Padrão</b>?',$w_padrao,'w_padrao');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_padrao" value="N">');
    }
    ShowHTML('      <tr><td align="LEFT"><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td colspan="3" align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}
// =========================================================================
// Rotina de usuário
// -------------------------------------------------------------------------
function Usuario() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave  = $_REQUEST['w_chave'];
  
  if ($O=='L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getPersonList; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,$SG,null,null,null,null);
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome_resumido','asc');
    } else {
      $RS = SortArray($RS,'nome_resumido','asc');
    }
  } 

  Cabecalho();
  head();
  if ($O=='I') {
    ScriptOpen('JavaScript');
    CheckBranco();
    ValidateOpen('Validacao');
    Validate('w_chave','Pessoa','HIDDEN','1','1','50','1','1');
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 

  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=\'this.focus()\';');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    if (f($RS_Param,'cadastrador_geral')=='S') {
      ShowHTML('  <tr><td colspan="3"><br><br><br></td></tr>');
      ShowHTML('  <tr><td colspan="3" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('  <tr><td colspan="3" valign="top" align="center" bgcolor="#D0D0D0"><b>Na tela de parâmetros foi indicado que todos os usuários são cadastradores gerais.</td></tr>');
      ShowHTML('  <tr><td colspan="3" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('  <tr><td colspan="3"><br><br><br></td></tr>');
    } else {
      // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
      ShowHTML('<tr><td><font size="1"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
      ShowHTML('    <td align="right"><font size="1">'.exportaOffice().'<b>Registros: '.count($RS));
      ShowHTML('<tr><td align="center" colspan=3>');
      ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Nome','nome_resumido').'</font></td>');
      ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Lotação','sg_unidade').'</font></td>');
      ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Ramal','ramal').'</font></td>');
      ShowHTML('          <td class="remover"><font size="1"><b>Operações</font></td>');
      ShowHTML('        </tr>');
      if (count($RS)<=0) {
        // Se não foram selecionados registros, exibe mensagem
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
      } else {
        // Lista os registros selecionados para listagem
        foreach($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('        <td><font size="1">'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'chave'),$TP,f($row,'nome_resumido')).'</td>');
          ShowHTML('        <td><font size="1">'.ExibeUnidade($w_dir_volta,$w_cliente,f($row,'nm_local'),f($row,'sq_unidade'),$TP).'</td>');
          ShowHTML('        <td align="center"><font size="1">'.Nvl(f($row,'ramal'),'---').'</td>');
          ShowHTML('        <td align="top" nowrap><font size="1">');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A>&nbsp');
          ShowHTML('        </td>');
          ShowHTML('      </tr>');
        } 
      } 
      ShowHTML('      </center>');
      ShowHTML('    </table>');
      ShowHTML('  </td>');
      ShowHTML('</tr>');
    }
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr valign="top">');
    SelecaoPessoa('<u>P</u>essoa:','p','Selecione a pessoa.',$w_chave,null,'w_chave','USUARIOS');
    ShowHTML('      <tr><td align="LEFT"><font size="1"><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
    ShowHTML('            <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
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
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        if ($O=='C' || $O=='I' || $O=='A') {
          // Testa a existência do nome
          $sql = new db_getTipoMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,Nvl($_REQUEST['w_chave'],''),null,Nvl($_REQUEST['w_nome'],''),null,null,null,null,'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Já existe tipo de material ou serviço com este nome!");');
            ScriptClose(); 
            retornaFormulario('w_nome');
            break;
          } 

          // Testa a existência do sigla
          $sql = new db_getTipoMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,Nvl($_REQUEST['w_chave'],''),Nvl($_REQUEST['w_chave_pai'],''),null,Nvl($_REQUEST['w_sigla'],''),null,null,null,'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Já existe tipo de material ou serviço com esta sigla!");');
            ScriptClose(); 
            retornaFormulario('w_sigla');
            break;
          } 
        } elseif ($O=='E') {
          $sql = new db_getTipoMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,Nvl($_REQUEST['w_chave'],''),null,null,null,null,null,null,'VINCULADO');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Não é possível excluir este tipo. Ele está ligado a algum material ou serviço!");');
            ScriptClose();
            break;
            retornaFormulario('w_assinatura');
          } 
        } 
        $SQL = new dml_putTipoMatServ; $SQL->getInstanceOf($dbms,$O,$w_cliente,Nvl($_REQUEST['w_chave'],''),Nvl($_REQUEST['w_chave_pai'],''),$_REQUEST['w_nome'],
                $_REQUEST['w_sigla'],$_REQUEST['w_classe'],$_REQUEST['w_gestora'],$_REQUEST['w_descricao'],$_REQUEST['w_codigo_externo'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
        } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'COCRITJULG':
      // Verifica se a Assinatura Eletrônica é válida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
         if ($O=='I' || $O=='A') {
          // Testa a existência do nome
          $sql = new db_getLCCriterio; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'], $w_cliente, Nvl($_REQUEST['w_nome'],''), null, null, null, null, 'EXISTE');
          if (count($RS)>0 && ($O=='I' || ($O=='A' && f($RS,'w_nome')==$_REQUEST['w_nome']))) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Já existe critério de '.(($w_cliente==6881) ? 'avaliação' : 'julgamento').' com este nome!");');
            ScriptClose(); 
            retornaFormulario('w_nome');
            break;
          } 
        }  
        $SQL = new dml_putLCCriterio; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$w_cliente,
           $_REQUEST['w_nome'],$_REQUEST['w_descricao'],$_REQUEST['w_ativo'],
           $_REQUEST['w_padrao'],$_REQUEST['w_item']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
        ScriptClose();        
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'COSITCERT':
      // Verifica se a Assinatura Eletrônica é válida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
         if ($O=='I' || $O=='A') {
          // Testa a existência do nome
          $sql = new db_getLCSituacao; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'], $w_cliente, Nvl($_REQUEST['w_nome'],''), null, null, null, null, 'EXISTE');
          if (count($RS)>0 && ($O=='I' || ($O=='A' && f($RS,'w_nome')==$_REQUEST['w_nome']))) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Já existe uma situação de certame com este nome!\');');
            ScriptClose(); 
            retornaFormulario('w_nome');
            break;
          } 
        }  
        $SQL = new dml_putLCSituacao; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$w_cliente,
           $_REQUEST['w_nome'],$_REQUEST['w_descricao'],$_REQUEST['w_ativo'],$_REQUEST['w_padrao'],
           $_REQUEST['w_publicar'],$_REQUEST['w_conclusao'],$_REQUEST['w_tela']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
        ScriptClose();        
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'MODART':
      // Verifica se a Assinatura Eletrônica é válida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
         if ($O=='I' || $O=='A') {
          // Testa a existência do nome
          $sql = new db_getLCModEnq; $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], $_REQUEST['w_chave_aux'], $_REQUEST['w_sigla'], null, 'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Já existe um artigo com esta sigla para esta modalidade!\');');
            ScriptClose(); 
            retornaFormulario('w_sigla');
            break;
          } 
        }  
        $SQL = new dml_putLCModEnq; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave'], $_REQUEST['w_chave_aux'],
           $_REQUEST['w_sigla'],$_REQUEST['w_descricao'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
        ScriptClose();        
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
      case 'CLPARAM':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_putCLParametro; $SQL->getInstanceOf($dbms,$w_cliente,$_REQUEST['w_ano_corrente'],$_REQUEST['w_dias_validade_pesquisa'],$_REQUEST['w_dias_aviso_pesquisa'],
            $_REQUEST['w_percentual_acrescimo'],$_REQUEST['w_compra_central'],$_REQUEST['w_pesquisa_central'],$_REQUEST['w_contrato_central'],
            $_REQUEST['w_banco_ata_central'],$_REQUEST['w_banco_preco_central'],$_REQUEST['w_codificacao_central'],
            $_REQUEST['w_pede_valor_pedido'],$_REQUEST['w_automatico'],$_REQUEST['w_prefixo'],
            $_REQUEST['w_sequencial'],$_REQUEST['w_sufixo'],$_REQUEST['w_cadastrador_geral']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;      
    case 'CLUNIDADE':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        if ($O=='I' || $O=='A') {
          if ($O=='I') {
            $sql = new db_getUnidade_CL; $RS = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['w_chave'],null,null);
            if (count($RS)>0) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Unidade já cadastrada!\');');
              ScriptClose();
              RetornaFormulario('w_chave');
              exit();
            }
          }
        }
        $SQL = new dml_putUnidade_CL; $SQL->getInstanceOf($dbms,$O,$w_cliente,Nvl($_REQUEST['w_chave'],''),$_REQUEST['w_unidade_pai'],
            $_REQUEST['w_realiza_compra'],$_REQUEST['w_solicita_compra'],$_REQUEST['w_registra_pesquisa'],
            $_REQUEST['w_registra_contrato'],$_REQUEST['w_registra_judicial'],$_REQUEST['w_controla_banco_ata'],
            $_REQUEST['w_controla_banco_preco'],$_REQUEST['w_codifica_item'],$_REQUEST['w_codificacao_restrita'],
            $_REQUEST['w_padrao'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'CLUSUARIO':      
      // Verifica se a Assinatura Eletrônica é válida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        if ($O=='I') {
          $sql = new db_getPersonList; $RS = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['w_chave'],$SG,null,null,null,null);
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Usuário já cadastrado!\');');
            ShowHTML('  history.back(1);');
            ScriptClose();
            RetornaFormulario('w_chave');
            exit;
          } 
        } 
        $SQL = new dml_putCLUsuario; $SQL->getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
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
  case 'ENQUADRAMENTO': Enquadramento();    break;
  case 'TIPOMATSERV':   TipoMatServ();      break;
  case 'CRITERIO':      Criterio();         break;
  case 'PARAMETRO':     Parametro();        break;  
  case 'SITUACAO':      Situacao();         break;
  case 'UNIDADE':       Unidade();          break;
  case 'USUARIO':       Usuario();          break;
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
