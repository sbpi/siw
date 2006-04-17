<? 
// =========================================================================
// Rotina de manipulação do menu
// -------------------------------------------------------------------------
function Menu() {
  extract($GLOBALS);

  $p_sq_endereco_unidade = $_REQUEST['p_sq_endereco_unidade'];
  $p_modulo              = $_REQUEST['p_modulo'];
  $p_menu                = $_REQUEST['p_menu'];

  $w_ImagemPadrao        = 'images/folder/SheetLittle.gif');
  $w_troca               = $_REQUEST['w_troca'];
  $w_heranca             = $_REQUEST['w_heranca'];

  $w_sq_menu             = $_REQUEST['w_sq_menu'];

  $Cabecalho;
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);

  if ($O!='L') {

    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if ($O!='P' && $O!='H') {

      if ($w_heranca>'' || ($O!='I' && $w_troca=='')) {


        // Se for herança, atribui a chave da opção selecionada para w_sq_menu
        if ($w_heranca>'') $w_sq_menu=$w_heranca;

        db_getMenuData$RS        $w_sq_menu;
        $w_sq_menu_pai          = $RS['sq_menu_pai'];
        $w_descricao            = $RS['nome'];
        $w_link                 = $RS['link'];
        $w_imagem               = $RS['imagem'];
        $w_tramite              = $RS['tramite'];
        $w_ordem                = $RS['ordem'];
        $w_ultimo_nivel         = $RS['ultimo_nivel'];
        $w_p1                   = $RS['p1'];
        $w_p2                   = $RS['p2'];
        $w_p3                   = $RS['p3'];
        $w_p4                   = $RS['p4'];
        $w_ativo                = $RS['ativo'];
        $w_envio                = $RS['destinatario'];
        $w_acesso_geral         = $RS['acesso_geral'];
        $w_modulo               = $RS['sq_modulo'];
        $w_descentralizado      = $RS['descentralizado'];
        $w_externo              = $RS['externo'];
        $w_target               = $RS['target'];
        $w_finalidade           = $RS['finalidade'];
        $w_emite_os             = $RS['emite_os'];
        $w_consulta_opiniao     = $RS['consulta_opiniao'];
        $w_acompanha_fases      = $RS['acompanha_fases'];
        $w_envia_email          = $RS['envia_email'];
        $w_exibe_relatorio      = $RS['exibe_relatorio'];
        $w_como_funciona        = $RS['como_funciona'];
        $w_controla_ano         = $RS['controla_ano'];
        $w_libera_edicao        = $RS['libera_edicao'];
        $w_arquivo_procedimentos= $RS['arquivo_proced'];
        $w_sq_unidade_executora = $RS['sq_unid_executora'];
        $w_vinculacao           = $RS['vinculacao'];
        $w_envia_dia_util       = $RS['envia_dia_util'];
        $w_data_hora            = $RS['data_hora'];
        $w_pede_descricao       = $RS['descricao'];
        $w_pede_justificativa   = $RS['justificativa'];
        $w_sigla                = $RS['sigla'];
      } else if ($w_troca>'') {
        $w_sq_menu_pai          = $_REQUEST['w_sq_menu_pai'];
        $w_sq_servico           = $_REQUEST['w_sq_servico'];
        $w_descricao            = $_REQUEST['w_descricao'];
        $w_link                 = $_REQUEST['w_link'];
        $w_imagem               = $_REQUEST['w_imagem'];
        $w_tramite              = $_REQUEST['w_tramite'];
        $w_ordem                = $_REQUEST['w_ordem'];
        $w_ultimo_nivel         = $_REQUEST['w_ultimo_nivel'];
        $w_cliente              = $_REQUEST['w_cliente'];
        $w_p1                   = $_REQUEST['w_p1'];
        $w_p2                   = $_REQUEST['w_p2'];
        $w_p3                   = $_REQUEST['w_p3'];
        $w_p4                   = $_REQUEST['w_p4'];
        $w_sigla                = $_REQUEST['w_sigla'];
        $w_ativo                = $_REQUEST['w_ativo'];
        $w_envio                = $_REQUEST['w_envio'];
        $w_acesso_geral         = $_REQUEST['w_acesso_geral'];
        $w_modulo               = $_REQUEST['w_modulo'];
        $w_descentralizado      = $_REQUEST['w_descentralizado'];
        $w_externo              = $_REQUEST['w_externo'];
        $w_target               = $_REQUEST['w_target'];
        $w_finalidade           = $_REQUEST['w_finalidade'];
        $w_emite_os             = $_REQUEST['w_emite_os'];
        $w_consulta_opiniao     = $_REQUEST['w_consulta_opiniao'];
        $w_acompanha_fases      = $_REQUEST['w_acompanha_fases'];
        $w_envia_email          = $_REQUEST['w_envia_email'];
        $w_exibe_relatorio      = $_REQUEST['w_exibe_relatorio'];
        $w_como_funciona        = $_REQUEST['w_como_funciona'];
        $w_controla_ano         = $_REQUEST['w_controla_ano'];
        $w_libera_edicao        = $_REQUEST['w_libera_edicao'];
        $w_arquivo_procedimentos= $_REQUEST['w_arquivo_procedimentos'];
        $w_sq_unidade_executora = $_REQUEST['w_sq_unidade_executora'];
        $w_vinculacao           = $_REQUEST['w_vinculacao'];
        $w_data_hora            = $_REQUEST['w_data_hora'];
        $w_envia_dia_util       = $_REQUEST['w_envia_dia_util'];
        $w_pede_descricao       = $_REQUEST['w_pede_descricao'];
        $w_pede_justificativa   = $_REQUEST['w_pede_justificativa'];
      } 

      if ($O=='I' || $O=='A') {
        Validate('w_descricao', 'Descrição', '1', '1', '2', '40', '1', '1');
        ShowHTML('  if (theForm.w_externo[0].checked && theForm.w_tramite[0].checked) { ');
        ShowHTML('     alert(\'Opções que apontem para links externos não podem ter vinculação a serviço.\nVerifique os campos "Link externo" e "Vinculada a serviço"!\'); ');
        ShowHTML('     return false; ');
        ShowHTML('  }');
        Validate('w_link', 'Link', '1', '', '5', '60', '1', '1');
        Validate('w_target', 'Target', '1', '', '1', '15', '1', '1');
        Validate('w_imagem', 'Imagem', '1', '', '5', '60', '1', '1');
        Validate('w_ordem', 'Ordem', '1', '1', '1', '6', '', '0123456789');
        Validate('w_finalidade', 'Finalidade', '1', '1', '4', '200', '1', '1');
        Validate('w_modulo', 'Módulo', 'SELECT', '1', '1', '10', '', '0123456789');
        ShowHTML('  if (theForm.w_tramite[0].checked && theForm.w_sigla.value == \'\') { ');
        ShowHTML('     alert(\'Opções vinculadas a serviço devem ter, obrigatoriamente, sigla informada.\nVerifique os campos "Sigla" e "Vinculada a serviço"!\'); ');
        ShowHTML('     theForm.w_sigla.focus(); ');
        ShowHTML('     return false; ');
        ShowHTML('  }');
        Validate('w_sigla', 'Sigla', '1', '', '4', '10', '1', '1');
        Validate('w_p1', 'P1', '1', '', '1', '18', '', '0123456789');
        Validate('w_p2', 'P2', '1', '', '1', '18', '', '0123456789');
        Validate('w_p3', 'P3', '1', '', '1', '18', '', '0123456789');
        Validate('w_p4', 'P4', '1', '', '1', '18', '', '0123456789');
        ShowHTML('  if (theForm.w_tramite[0].checked) { ');
        Validate('w_sq_unidade_executora', 'Unidade executora', 'HIDDEN', '1', '1', '10', '', '0123456789');
        Validate('w_como_funciona', 'Como funciona', '', '1', '10', '1000', '1', '1');
        ShowHTML('  }');
      } 

      Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
    } elseif ($O=='H') {

      Validate('w_heranca', 'Origem dos dados', 'SELECT', '1', '1', '10', '', '1');
      ShowHTML('  if (confirm(\'Confirma herança dos dados da opção selecionada?\')) {');
      ShowHTML('     window.close(); ');
      ShowHTML('     opener.focus(); ');
      ShowHTML('     return true; ');
      ShowHTML('  } ');
      ShowHTML('  else { return false; } ');
    } 

    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose;
    ShowHTML('function servico() {');
    ShowHTML('  if (document.Form.w_tramite[1].checked) {');
    ShowHTML('     document.Form.w_sq_unidade_executora.selectedIndex=0;');
    ShowHTML('     document.Form.w_emite_os[0].checked=false;');
    ShowHTML('     document.Form.w_emite_os[1].checked=false;');
    ShowHTML('     document.Form.w_envio[0].checked=false;');
    ShowHTML('     document.Form.w_envio[1].checked=false;');
    ShowHTML('     document.Form.w_consulta_opiniao[0].checked=false;');
    ShowHTML('     document.Form.w_consulta_opiniao[1].checked=false;');
    ShowHTML('     document.Form.w_envia_email[0].checked=false;');
    ShowHTML('     document.Form.w_envia_email[1].checked=false;');
    ShowHTML('     document.Form.w_exibe_relatorio[0].checked=false;');
    ShowHTML('     document.Form.w_exibe_relatorio[1].checked=false;');
    ShowHTML('     document.Form.w_vinculacao[0].checked=false;');
    ShowHTML('     document.Form.w_vinculacao[1].checked=false;');
    ShowHTML('     document.Form.w_data_hora[0].checked=false;');
    ShowHTML('     document.Form.w_data_hora[1].checked=false;');
    ShowHTML('     document.Form.w_data_hora[2].checked=false;');
    ShowHTML('     document.Form.w_data_hora[3].checked=false;');
    ShowHTML('     document.Form.w_data_hora[4].checked=false;');
    ShowHTML('     document.Form.w_envia_dia_util[0].checked=false;');
    ShowHTML('     document.Form.w_envia_dia_util[1].checked=false;');
    ShowHTML('     document.Form.w_pede_descricao[0].checked=false;');
    ShowHTML('     document.Form.w_pede_descricao[1].checked=false;');
    ShowHTML('     document.Form.w_pede_justificativa[0].checked=false;');
    ShowHTML('     document.Form.w_pede_justificativa[1].checked=false;');
    ShowHTML('     document.Form.w_como_funciona.value='';');
    ShowHTML('     document.Form.w_controla_ano[0].checked=false;');
    ShowHTML('     document.Form.w_controla_ano[1].checked=false;');
    ShowHTML('     document.Form.w_sq_unidade_executora.disabled=true;');
    ShowHTML('     document.Form.w_emite_os[0].disabled=true;');
    ShowHTML('     document.Form.w_emite_os[1].disabled=true;');
    ShowHTML('     document.Form.w_envio[0].disabled=true;');
    ShowHTML('     document.Form.w_envio[1].disabled=true;');
    ShowHTML('     document.Form.w_consulta_opiniao[0].disabled=true;');
    ShowHTML('     document.Form.w_consulta_opiniao[1].disabled=true;');
    ShowHTML('     document.Form.w_envia_email[0].disabled=true;');
    ShowHTML('     document.Form.w_envia_email[1].disabled=true;');
    ShowHTML('     document.Form.w_exibe_relatorio[0].disabled=true;');
    ShowHTML('     document.Form.w_exibe_relatorio[1].disabled=true;');
    ShowHTML('     document.Form.w_vinculacao[0].disabled=true;');
    ShowHTML('     document.Form.w_vinculacao[1].disabled=true;');
    ShowHTML('     document.Form.w_data_hora[0].disabled=true;');
    ShowHTML('     document.Form.w_data_hora[1].disabled=true;');
    ShowHTML('     document.Form.w_data_hora[2].disabled=true;');
    ShowHTML('     document.Form.w_data_hora[3].disabled=true;');
    ShowHTML('     document.Form.w_data_hora[4].disabled=true;');
    ShowHTML('     document.Form.w_envia_dia_util[0].disabled=true;');
    ShowHTML('     document.Form.w_envia_dia_util[1].disabled=true;');
    ShowHTML('     document.Form.w_pede_descricao[0].disabled=true;');
    ShowHTML('     document.Form.w_pede_descricao[1].disabled=true;');
    ShowHTML('     document.Form.w_pede_justificativa[0].disabled=true;');
    ShowHTML('     document.Form.w_pede_justificativa[1].disabled=true;');
    ShowHTML('     document.Form.w_controla_ano[0].disabled=true;');
    ShowHTML('     document.Form.w_controla_ano[1].disabled=true;');
    ShowHTML('     document.Form.w_como_funciona.disabled=true;');
    ShowHTML('  }');
    ShowHTML('  else if (document.Form.w_tramite[0].checked && document.Form.w_emite_os[0].disabled) {');
    ShowHTML('     document.Form.w_sq_unidade_executora.disabled=false;');
    ShowHTML('     document.Form.w_emite_os[0].disabled=false;');
    ShowHTML('     document.Form.w_emite_os[1].disabled=false;');
    ShowHTML('     document.Form.w_envio[0].disabled=false;');
    ShowHTML('     document.Form.w_envio[1].disabled=false;');
    ShowHTML('     document.Form.w_consulta_opiniao[0].disabled=false;');
    ShowHTML('     document.Form.w_consulta_opiniao[1].disabled=false;');
    ShowHTML('     document.Form.w_envia_email[0].disabled=false;');
    ShowHTML('     document.Form.w_envia_email[1].disabled=false;');
    ShowHTML('     document.Form.w_exibe_relatorio[0].disabled=false;');
    ShowHTML('     document.Form.w_exibe_relatorio[1].disabled=false;');
    ShowHTML('     document.Form.w_vinculacao[0].disabled=false;');
    ShowHTML('     document.Form.w_vinculacao[1].disabled=false;');
    ShowHTML('     document.Form.w_data_hora[0].disabled=false;');
    ShowHTML('     document.Form.w_data_hora[1].disabled=false;');
    ShowHTML('     document.Form.w_data_hora[2].disabled=false;');
    ShowHTML('     document.Form.w_data_hora[3].disabled=false;');
    ShowHTML('     document.Form.w_data_hora[4].disabled=false;');
    ShowHTML('     document.Form.w_envia_dia_util[0].disabled=false;');
    ShowHTML('     document.Form.w_envia_dia_util[1].disabled=false;');
    ShowHTML('     document.Form.w_pede_descricao[0].disabled=false;');
    ShowHTML('     document.Form.w_pede_descricao[1].disabled=false;');
    ShowHTML('     document.Form.w_pede_justificativa[0].disabled=false;');
    ShowHTML('     document.Form.w_pede_justificativa[1].disabled=false;');
    ShowHTML('     document.Form.w_como_funciona.disabled=false;');
    ShowHTML('     document.Form.w_controla_ano[0].disabled=false;');
    ShowHTML('     document.Form.w_controla_ano[1].disabled=false;');
    ShowHTML('     document.Form.w_sq_unidade_executora.selectedIndex=0;');
    ShowHTML('     document.Form.w_emite_os[1].checked=true;');
    ShowHTML('     document.Form.w_envio[0].checked=true;');
    ShowHTML('     document.Form.w_consulta_opiniao[1].checked=true;');
    ShowHTML('     document.Form.w_envia_email[1].checked=true;');
    ShowHTML('     document.Form.w_exibe_relatorio[1].checked=true;');
    ShowHTML('     document.Form.w_vinculacao[1].checked=true;');
    ShowHTML('     document.Form.w_data_hora[2].checked=true;');
    ShowHTML('     document.Form.w_envia_dia_util[0].checked=true;');
    ShowHTML('     document.Form.w_pede_descricao[0].checked=true;');
    ShowHTML('     document.Form.w_pede_justificativa[0].checked=true;');
    ShowHTML('     document.Form.w_como_funciona.value='';');
    ShowHTML('     document.Form.w_controla_ano[1].checked=true;');
    ShowHTML('  }');
    $ShowHTML']');
    ScriptClose;
  } 

  ShowHTML('<style> ');
  ShowHTML(' .lh {text-decoration:none;font:Arial;color="#FF0000"}');
  ShowHTML(' .lh:HOVER {text-decoration: underline;} ');
  ShowHTML('</style> ');
  ShowHTML('</HEAD>');

  if ($w_Troca>'')            BodyOpen('onLoad=document.Form.'.$w_troca.'.focus();');
  elseif ($O=='I' || $O=='A') BodyOpen('onLoad=document.Form.w_descricao.focus();');
  elseif ($O=='H')            BodyOpen('onLoad=document.Form.w_heranca.focus();');
  elseif ($O=='P')            BodyOpen('onLoad=document.Form.p_sq_endereco_unidade.focus();');
  elseif ($O=='L')            BodyOpen('onLoad=document.focus();');
  else                        BodyOpen('onLoad=document.Form.w_assinatura.focus();');

  if ($O!='H') {
    $Estrutura_Topo_Limpo;
    $Estrutura_Menu;
    $Estrutura_Corpo_Abre;
  } 

  $Estrutura_Texto_Abre;
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="99%" border="0">');

  if ($O=='L') {
    ShowHTML('      <tr><td><font size="2"><a accesskey="I" class="ss" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'"><u>I</u>ncluir</a>&nbsp;');
    // Trata a cor e o texto da string Filtrar, dependendo do filtro estar ativo ou não
    if ($p_sq_endereco_unidade.$p_modulo.$p_menu>'') {
      ShowHTML('                         <a accesskey="F" class="ss" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'"><font color="#BC5100"><u>F</u>iltrar (Ativo)</font></a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="ss" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'"><u>F</u>iltrar (Inativo)</a>');
    } 

    ShowHTML('      <tr><td height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td><font size=2><b>');
    if ($p_menu>'') {
      $RS = db_getMenuLink::getInstanceof($dbms, $w_cliente, $p_sq_endereco_unidade, $p_menu);
    } else {
      $RS = db_getMenuLink::getInstanceof($dbms, $w_cliente, $p_sq_endereco_unidade, 'IS NULL');
    } 

    $w_filter='';
    if ($p_modulo>'') $w_filter = '(sq_modulo='.$p_modulo.') ';

    if ($p_menu>'') {
      if ($w_filter>'') $w_filter = $w_filter.' and ';
      $w_filter = $w_filter.'(sq_menu_pai='.$p_menu.' or sq_menu='.$p_menu.')';
    } 

    if ($w_filter>'') $RS->Filter=$w_filter;

    $w_ContOut=0;
    while(!$RS->EOF) {

      $w_Titulo  = $RS['nome'];
      $w_ContOut = $w_ContOut+1;
      if ($RS['Filho']>0) {

        ShowHTML('<A HREF="#'.$RS['sq_menu'].'"></A>');
        ShowHTML('<font size=2><span><div align="left"><img src="images/folder/FolderClose.gif" border=0 align="center"> '.$RS['nome'].'<font size=1>');
        if ($RS['ativo']=='S') $w_classe='hl'); else $w_classe='lh');
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_menu='.$RS['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Altera as informações desta opção do menu">Alterar</A>&nbsp');
        // A configuração de endereços e serviço/acessos não estão disponíveis para sub-menus

        if ($RS['ultimo_nivel']!='S') {
          ShowHTML('       <A class="'.$w_classe.'" HREF="#'.$RS['sq_menu'].'" onClick="window.open('seguranca1.asp?par=Endereco&R='.$w_pagina.$par.'&O=L&w_sq_menu='.$RS['sq_menu'].'&TP='.$TP.' - Endereços'.'&SG=ENDERECO','endereco','top=10,left=10,width=780,height=500,toolbar=no,status=no,scrollbars=yes,resizable=yes');" title="Indica quais endereços terão esta opção no menu. A princípio, todas as opções do menu aparecem para os usuários de todos os endereços.">Endereços</A>&nbsp');
          if ($RS['tramite']=='S') {
            ShowHTML('       <A class="'.$w_classe.'" HREF="#'.$RS['sq_menu'].'" onClick="window.open('seguranca1.asp?par=Tramite&R='.$w_pagina.$par.'&O=L&w_sq_menu='.$RS['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Trâmites'.'&SG=SIWTRAMITE'.$MontaFiltro['GET'].'','Tramite','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');" title="Configura os trâmites vinculados a esta opção.">Trâmites</A>&nbsp');
          } else {
            ShowHTML('       <A class="'.$w_classe.'" HREF="#'.$RS['sq_menu'].'" onClick="window.open('seguranca1.asp?par=AcessoMenu&R='.$w_pagina.$par.'&O=L&w_cliente='.$w_cliente.'&w_sq_menu='.$RS['sq_menu'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Acessos'.'&SG=ACESSOMENU','AcessoMenu','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');" title="Configura as permissões de acesso.">Acessos</A>&nbsp');
          } 
        } 

        if ($RS['ativo']=='S') {
          ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_menu='.$RS['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Impede que esta opção apareça no menu">Desativar</A>&nbsp');
        } else {
          ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_menu='.$RS['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Impede que esta opção apareça no menu">Ativar</A>&nbsp');
        } 

        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_menu='.$RS['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Exclui o link do menu">Excluir</A>&nbsp');
        ShowHTML('       </div></span></font></font>');
        ShowHTML('   <div style="position:relative; left:12;"><font size=1>');
        db_getMenuLink$RS1      $w_cliente      $p_sq_endereco_unidade      $RS['sq_menu'];
        while(!$RS1->EOF) {
          $w_Titulo=$w_Titulo.' - '.$RS1['nome'];
          if ($RS1['Filho']>0) {
            $w_ContOut=$w_ContOut+1;
            ShowHTML('<A HREF=#"'.$RS1['sq_menu'].'"></A>');
            ShowHTML('<font size=1><span><div align="left"><img src="images/folder/FolderClose.gif" border=0 align="center"> '.$RS1['nome'].'<font size=1>');
            if ($RS1['ativo']=='S') $w_classe='hl'); else $w_classe='lh');
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_menu='.$RS1['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Altera as informações desta opção do menu">Alterar</A>&nbsp');
            // A configuração de endereços e serviço/acessos não estão disponíveis para sub-menus

            if ($RS1['ultimo_nivel']!='S') {
              ShowHTML('       <A class="'.$w_classe.'" HREF="#'.$RS1['sq_menu'].'" onClick="window.open('seguranca1.asp?par=Endereco&R='.$w_pagina.$par.'&O=L&w_sq_menu='.$RS1['sq_menu'].'&TP='.$TP.' - Endereços'.'&SG=ENDERECO','endereco','top=10,left=10,width=780,height=500,toolbar=no,status=no,scrollbars=yes,resizable=yes');" title="Indica quais endereços terão esta opção no menu. A princípio, todas as opções do menu aparecem para os usuários de todos os endereços.">Endereços</A>&nbsp');
              if ($RS1['tramite']=='S') {
                ShowHTML('       <A class="'.$w_classe.'" HREF="#'.$RS1['sq_menu'].'" onClick="window.open('seguranca1.asp?par=Tramite&R='.$w_pagina.$par.'&O=L&w_sq_menu='.$RS1['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Trâmites'.'&SG=SIWTRAMITE'.$MontaFiltro['GET'].'','Tramite','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');" title="Configura os trâmites vinculados a esta opção.">Trâmites</A>&nbsp');
              } else {
                ShowHTML('       <A class="'.$w_classe.'" HREF="#'.$RS1['sq_menu'].'" onClick="window.open('seguranca1.asp?par=AcessoMenu&R='.$w_pagina.$par.'&O=L&w_sq_menu='.$RS1['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Acessos'.'&SG=ACESSOMENU','AcessoMenu','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');" title="Configura as permissões de acesso.">Acessos</A>&nbsp');
              } 
            } 

            if ($RS1['ativo']=='S') {
              ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_menu='.$RS1['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Impede que esta opção apareça no menu">Desativar</A>&nbsp');
            } else {
              ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_menu='.$RS1['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Impede que esta opção apareça no menu">Ativar</A>&nbsp');
            } 

            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_menu='.$RS1['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Exclui o link do menu">Excluir</A>&nbsp');
            ShowHTML('       </div></span></font></font>');
            ShowHTML('   <div style="position:relative; left:12;"><font size=1>');
            db_getMenuLink$RS2        $w_cliente        $p_sq_endereco_unidade        $RS1['sq_menu'];
            while(!$RS2->EOF) {

              $w_Titulo=$w_Titulo.' - '.$RS2['nome'];
              if ($RS2['Filho']>0) {
 
                $w_ContOut=$w_ContOut+1;
                ShowHTML('<A HREF=#"'.$RS2['sq_menu'].'"></A>');
                ShowHTML('<font size=1><span><div align="left"><img src="images/folder/FolderClose.gif" border=0 align="center"> '.$RS2['nome'].'<font size=1>');
                if ($RS2['ativo']=='S') $w_classe='hl'); else $w_classe='lh');
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_menu='.$RS2['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Altera as informações desta opção do menu">Alterar</A>&nbsp');
                // A configuração de endereços e serviço/acessos não estão disponíveis para sub-menus
 
                if ($RS2['ultimo_nivel']!='S') {
                  ShowHTML('       <A class="'.$w_classe.'" HREF="#'.$RS2['sq_menu'].'" onClick="window.open('seguranca1.asp?par=Endereco&R='.$w_pagina.$par.'&O=L&w_sq_menu='.$RS2['sq_menu'].'&TP='.$TP.' - Endereços'.'&SG=ENDERECO','endereco','top=10,left=10,width=780,height=500,toolbar=no,status=no,scrollbars=yes,resizable=yes');" title="Indica quais endereços terão esta opção no menu. A princípio, todas as opções do menu aparecem para os usuários de todos os endereços.">Endereços</A>&nbsp');
                  if ($RS2['tramite']=='S') {
                    ShowHTML('       <A class="'.$w_classe.'" HREF="#'.$RS2['sq_menu'].'" onClick="window.open('seguranca1.asp?par=Tramite&R='.$w_pagina.$par.'&O=L&w_sq_menu='.$RS2['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Trâmites'.'&SG=SIWTRAMITE'.$MontaFiltro['GET'].'','Tramite','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');" title="Configura os trâmites vinculados a esta opção.">Trâmites</A>&nbsp');
                  } else {
                    ShowHTML('       <A class="'.$w_classe.'" HREF="#'.$RS2['sq_menu'].'" onClick="window.open('seguranca1.asp?par=AcessoMenu&R='.$w_pagina.$par.'&O=L&w_sq_menu='.$RS2['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Acessos'.'&SG=ACESSOMENU','AcessoMenu','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');" title="Configura as permissões de acesso.">Acessos</A>&nbsp');
                  } 
                } 

                if ($RS2['ativo']=='S') {
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_menu='.$RS2['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Impede que esta opção apareça no menu">Desativar</A>&nbsp');
                } else {
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_menu='.$RS2['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Impede que esta opção apareça no menu">Ativar</A>&nbsp');
                } 

                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_menu='.$RS2['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Exclui o link do menu">Excluir</A>&nbsp');
                ShowHTML('       </div></span></font></font>');
                ShowHTML('   <div style="position:relative; left:12;"><font size=1>');
                db_getMenuLink$RS3          $w_cliente          $p_sq_endereco_unidade          $RS2['sq_menu'];
                while(!$RS3->EOF) {
                  $w_Titulo=$w_Titulo.' - '.$RS3['nome'];
                  if ($RS3['IMAGEM']>'') $w_Imagem=$RS3['IMAGEM']; else $w_Imagem=$w_ImagemPadrao;
  
                  ShowHTML('<A HREF=#"'.$RS3['sq_menu'].'"></A>');
                  ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.$RS3['nome'];
                  if ($RS3['ativo']=='S') $w_classe='hl'); else $w_classe='lh');
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_menu='.$RS3['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Altera as informações desta opção do menu">Alterar</A>&nbsp');
                  // A configuração de endereços e serviço/acessos não estão disponíveis para sub-menus
 
                  if ($RS3['ultimo_nivel']!='S') {

                    ShowHTML('       <A class="'.$w_classe.'" HREF="#'.$RS3['sq_menu'].'" onClick="window.open('seguranca1.asp?par=Endereco&R='.$w_pagina.$par.'&O=L&w_sq_menu='.$RS3['sq_menu'].'&TP='.$TP.' - Endereços'.'&SG=ENDERECO','endereco','top=10,left=10,width=780,height=500,toolbar=no,status=no,scrollbars=yes,resizable=yes');" title="Indica quais endereços terão esta opção no menu. A princípio, todas as opções do menu aparecem para os usuários de todos os endereços.">Endereços</A>&nbsp');
                    if ($RS3['tramite']=='S') {
                      ShowHTML('       <A class="'.$w_classe.'" HREF="#'.$RS3['sq_menu'].'" onClick="window.open('seguranca1.asp?par=Tramite&R='.$w_pagina.$par.'&O=L&w_sq_menu='.$RS3['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Trâmites'.'&SG=SIWTRAMITE'.$MontaFiltro['GET'].'','Tramite','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');" title="Configura os trâmites vinculados a esta opção.">Trâmites</A>&nbsp');
                    } else {
                      ShowHTML('       <A class="'.$w_classe.'" HREF="#'.$RS3['sq_menu'].'" onClick="window.open('seguranca1.asp?par=AcessoMenu&R='.$w_pagina.$par.'&O=L&w_sq_menu='.$RS3['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Acessos'.'&SG=ACESSOMENU','AcessoMenu','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');" title="Configura as permissões de acesso.">Acessos</A>&nbsp');
                    } 

                  } 

                  if ($RS3['ativo']=='S') {
                    ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_menu='.$RS3['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Impede que esta opção apareça no menu">Desativar</A>&nbsp');
                  } else {
                    ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_menu='.$RS3['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Impede que esta opção apareça no menu">Desativar</A>&nbsp');
                  } 
  
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_menu='.$RS3['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Exclui o link do menu">Excluir</A>&nbsp');
                  ShowHTML('    <BR>');
                  $w_Titulo=str_replace(" - '.$RS3['nome'],'',$w_Titulo);
                  $RS3->MoveNext;
                } 
                ShowHTML('   </font></div>');
              } else {
                if ($RS2['IMAGEM']>'') $w_Imagem=$RS2['IMAGEM']; else $w_Imagem=$w_ImagemPadrao;
                ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.$RS2['nome'];
                if ($RS2['ativo']=='S') $w_classe='hl'); else $w_classe='lh');
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_menu='.$RS2['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Altera as informações desta opção do menu">Alterar</A>&nbsp');
                // A configuração de endereços e serviço/acessos não estão disponíveis para sub-menus
                if ($RS2['ultimo_nivel']!='S') {
                  ShowHTML('       <A class="'.$w_classe.'" HREF="#'.$RS2['sq_menu'].'" onClick="window.open('seguranca1.asp?par=Endereco&R='.$w_pagina.$par.'&O=L&w_sq_menu='.$RS2['sq_menu'].'&TP='.$TP.' - Endereços'.'&SG=ENDERECO','endereco','top=10,left=10,width=780,height=500,toolbar=no,status=no,scrollbars=yes,resizable=yes');" title="Indica quais endereços terão esta opção no menu. A princípio, todas as opções do menu aparecem para os usuários de todos os endereços.">Endereços</A>&nbsp');
                  if ($RS2['tramite']=='S') {
                    ShowHTML('       <A class="'.$w_classe.'" HREF="#'.$RS2['sq_menu'].'" onClick="window.open('seguranca1.asp?par=Tramite&R='.$w_pagina.$par.'&O=L&w_sq_menu='.$RS2['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Trâmites'.'&SG=SIWTRAMITE'.$MontaFiltro['GET'].'','Tramite','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');" title="Configura os trâmites vinculados a esta opção.">Trâmites</A>&nbsp');
                  } else {
                    ShowHTML('       <A class="'.$w_classe.'" HREF="#'.$RS2['sq_menu'].'" onClick="window.open('seguranca1.asp?par=AcessoMenu&R='.$w_pagina.$par.'&O=L&w_sq_menu='.$RS2['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Acessos'.'&SG=ACESSOMENU','AcessoMenu','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');" title="Configura as permissões de acesso.">Acessos</A>&nbsp');
                  } 
                } 
                if ($RS2['ativo']=='S') {
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_menu='.$RS2['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Impede que esta opção apareça no menu">Desativar</A>&nbsp');
                } else {
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_menu='.$RS2['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Impede que esta opção apareça no menu">Ativar</A>&nbsp');
                } 
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_menu='.$RS2['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Exclui o link do menu">Excluir</A>&nbsp');
                ShowHTML('    <BR>');
              } 

              $w_Titulo=str_replace(" - '.$RS2['nome'],'',$w_Titulo);
              $RS2->MoveNext;
            } 
            ShowHTML('   </font></div>');
          } else {
            if ($RS1['IMAGEM']>'') $w_Imagem=$RS1['IMAGEM']; else $w_Imagem=$w_ImagemPadrao;
            ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.$RS1['nome'];
            if ($RS1['ativo']=='S') $w_classe='hl'); else $w_classe='lh');
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_menu='.$RS1['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Altera as informações desta opção do menu">Alterar</A>&nbsp');
            // A configuração de endereços e serviço/acessos não estão disponíveis para sub-menus
            if ($RS1['ultimo_nivel']!='S') {
              ShowHTML('       <A class="'.$w_classe.'" HREF="#'.$RS1['sq_menu'].'" onClick="window.open('seguranca1.asp?par=Endereco&R='.$w_pagina.$par.'&O=L&w_sq_menu='.$RS1['sq_menu'].'&TP='.$TP.' - Endereços'.'&SG=ENDERECO','endereco','top=10,left=10,width=780,height=500,toolbar=no,status=no,scrollbars=yes,resizable=yes');" title="Indica quais endereços terão esta opção no menu. A princípio, todas as opções do menu aparecem para os usuários de todos os endereços.">Endereços</A>&nbsp');
              if ($RS1['tramite']=='S') {
                ShowHTML('       <A class="'.$w_classe.'" HREF="#'.$RS1['sq_menu'].'" onClick="window.open('seguranca1.asp?par=Tramite&R='.$w_pagina.$par.'&O=L&w_sq_menu='.$RS1['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Trâmites'.'&SG=SIWTRAMITE'.$MontaFiltro['GET'].'','Tramite','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');" title="Configura os trâmites vinculados a esta opção.">Trâmites</A>&nbsp');
              } else {
                ShowHTML('       <A class="'.$w_classe.'" HREF="#'.$RS1['sq_menu'].'" onClick="window.open('seguranca1.asp?par=AcessoMenu&R='.$w_pagina.$par.'&O=L&w_sq_menu='.$RS1['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Acessos'.'&SG=ACESSOMENU','AcessoMenu','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');" title="Configura as permissões de acesso.">Acessos</A>&nbsp');
              } 
            } 
            if ($RS1['ativo']=='S') {
              ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_menu='.$RS1['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Impede que esta opção apareça no menu">Desativar</A>&nbsp');
            } else {
              ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_menu='.$RS1['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Impede que esta opção apareça no menu">Ativar</A>&nbsp');
            } 
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_menu='.$RS1['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Exclui o link do menu">Excluir</A>&nbsp');
            ShowHTML('    <BR>');
          } 
          $w_Titulo=str_replace(" - '.$RS1['nome'],'',$w_Titulo);
          $RS1->MoveNext;
        } 
        ShowHTML('   </font></div>');
      } else {
        if ($RS['IMAGEM']>'') $w_Imagem=$RS['IMAGEM']; else $w_Imagem=$w_ImagemPadrao;
        ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"><font size=2> '.$RS['nome'].'<font size=1>');
        if ($RS['ativo']=='S') $w_classe='hl'); else $w_classe='lh');
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_menu='.$RS['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Altera as informações desta opção do menu">Alterar</A>&nbsp');
        // A configuração de endereços e serviço/acessos não estão disponíveis para sub-menus
        if ($RS['ultimo_nivel']!='S') {
            ShowHTML('       <A class="'.$w_classe.'" HREF="#'.$RS['sq_menu'].'" onClick="window.open('seguranca1.asp?par=Endereco&R='.$w_pagina.$par.'&O=L&w_sq_menu='.$RS['sq_menu'].'&TP='.$TP.' - Endereços'.'&SG=ENDERECO','endereco','top=10,left=10,width=780,height=500,toolbar=no,status=no,scrollbars=yes,resizable=yes');" title="Indica quais endereços terão esta opção no menu. A princípio, todas as opções do menu aparecem para os usuários de todos os endereços.">Endereços</A>&nbsp');
            if ($RS['tramite']=='S') {
              ShowHTML('       <A class="'.$w_classe.'" HREF="#'.$RS['sq_menu'].'" onClick="window.open('seguranca1.asp?par=Tramite&R='.$w_pagina.$par.'&O=L&w_sq_menu='.$RS['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Trâmites'.'&SG=SIWTRAMITE'.$MontaFiltro['GET'].'','Tramite','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');" title="Configura os trâmites vinculados a esta opção.">Trâmites</A>&nbsp');
            } else {
              ShowHTML('       <A class="'.$w_classe.'" HREF="#'.$RS['sq_menu'].'" onClick="window.open('seguranca1.asp?par=AcessoMenu&R='.$w_pagina.$par.'&O=L&w_sq_menu='.$RS['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Acessos'.'&SG=ACESSOMENU','AcessoMenu','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');" title="Configura as permissões de acesso.">Acessos</A>&nbsp');
            } 
          } 
        if ($RS['ativo']=='S') {
          ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_menu='.$RS['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Impede que esta opção apareça no menu">Desativar</A>&nbsp');
        } else {
          ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_menu='.$RS['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Impede que esta opção apareça no menu">Ativar</A>&nbsp');
        } 
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_menu='.$RS['sq_menu'].'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MENU'.$MontaFiltro['GET'].'" title="Exclui o link do menu">Excluir</A>&nbsp');
        ShowHTML('    <BR>');
      } 
      $RS->MoveNext;
    } 
    if ($w_contOut==0) {
      // Se não achou registros
      ShowHTML('<font size=2>Não foram encontrados registros.';
    } 
  } elseif ($O!='P' && $O!='H') {
    if ($O!='I' && $O!='A') $w_Disabled="disabled');
    // Se for inclusão de nova opção, permite a herança dos dados de outra, já existente.

    if ($O=='I') {
      ShowHTML('      <tr><td><font size="2"><a accesskey="H" class="ss" href="#" onClick="window.open(''.$w_pagina.$par.'&R='.$w_pagina.'MENU&O=H&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_sq_menu='.$w_sq_menu.''.$MontaFiltro['GET'].'','heranca','top=70,left=10,width=780,height=200,toolbar=no,status=no,scrollbars=no');"><u>H</u>erdar dados de outra opção</a>&nbsp;');
      ShowHTML('      <tr><td height="1" bgcolor="#000000">');
    } 

    $AbreForm"Form"$w_pagina.'Grava"//POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina & par,O
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value=''>');
    ShowHTML('<INPUT type="hidden" name="w_sq_menu" value="'.$w_sq_menu.'">');
    $ShowHTML$MontaFiltro['POST'];
    ShowHTML('      <tr><td><table width="100%" border=0>');
    ShowHTML('          <tr><td colspan=4 height="30"><font size="2"><b>Identificação</td>');
    ShowHTML('          <tr><td width="5%">');
    ShowHTML('              <td align="left"><font size="1"><b><u>D</u>escrição:<br><INPUT ACCESSKEY="D" TYPE="TEXT" CLASS="sti" NAME="w_descricao" SIZE=40 MAXLENGTH=40 VALUE="'.$w_descricao.'" '.$w_Disabled.' title="Nome a ser apresentado no menu."></td>');
    $SelecaoMenu"<u>S</u>ubordinação:"//S", "Se esta opção estiver subordinada a outra já existente, informe qual.', w_sq_menu_pai, w_sq_menu, "w_sq_menu_pai", "Pesquisa", "onChange=''document.Form.action='" & w_pagina & par & "'; document.Form.w_troca.value='w_link'; document.Form.submit();''"
    ShowHTML('              <td title="Existem formulários com várias telas. Neste caso você pode criar sub-menus. Informe \'Sim\' se for o caso desta opção."><font size="1"><b>Sub-menu?</b><br>');
    if ($w_ultimo_nivel=='S') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_ultimo_nivel" value="S" checked> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_ultimo_nivel" value="N"> Não');
    } else {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_ultimo_nivel" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_ultimo_nivel" value="N" checked> Não');
    } 

    ShowHTML('          <tr><td width="5%">');
    ShowHTML('              <td><font size="1"><b><u>L</u>ink:<br><INPUT ACCESSKEY="L" TYPE="TEXT" CLASS="sti" NAME="w_link" SIZE=40 MAXLENGTH=60 VALUE="'.$w_link.'" '.$w_Disabled.' title="Informe o link a ser chamado quando esta opção for clicada. Se esta opção tiver opções subordinadas, não informe este campo."></td>');
    ShowHTML('              <td><font size="1"><b><u>T</u>arget:<br><INPUT ACCESSKEY="T" TYPE="TEXT" CLASS="sti" NAME="w_target" SIZE=15 MAXLENGTH=15 VALUE="'.$w_target.'" '.$w_Disabled.' title="Se desejar que a opção seja aberta em outra janela, diferente do padrão, informe \'_blank\' ou o nome da janela desejada."></td>');
    ShowHTML('              <td title="Informe \'Sim\' para opções que chamarão links externos ao SIW. Links para sites de busca, de bancos etc são exemplos onde este campo deve ter valor \'Sim\'."><font size="1"><b>Link externo?</b><br>');
    if ($w_externo=='S') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_externo" value="S" checked> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_externo" value="N"> Não');
    } else {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_externo" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_externo" value="N" checked> Não');
    } 

    ShowHTML('          <tr><td width="5%">');
    ShowHTML('              <td align="left" colspan="2"><font size="1"><b><u>I</u>magem:<br><INPUT ACCESSKEY="I" TYPE="TEXT" CLASS="sti" NAME="w_imagem" SIZE=60 MAXLENGTH=60 VALUE="'.$w_imagem.'" '.$w_Disabled.' title="O SIW apresenta ícones padrão na montagem do menu. Se desejar outro ícone, informe o caminho onde está localizado."></td>');
    // Recupera o número de ordem das outras opções irmãs à selecionada

    db_getMenuOrder$RS$w_cliente$w_sq_menu_pai;
    if (!$RS->EOF)  {

      $w_texto="<b>Nºs de ordem em uso para esta subordinação:</b>:<br>'.
               "<table border=1 width=100% cellpadding=0 cellspacing=0>'.
               "<tr><td align=center><b><font size=1>Ordem'.
               "    <td><b><font size=1>Descrição');
      while(!$RS->EOF) {

        $w_texto=$w_texto.'<tr><td valign=top align=center><font size=1>'.$RS['ordem'].'<td valign=top><font size=1>'.$RS['nome'];
        $RS->MoveNext;
      } 
      $w_texto=$w_texto.'</table>');
    } else {

      $w_texto="Não há outros números de ordem vinculados à subordinação desta opção');
    } 

    ShowHTML('              <td align="left"><font size="1"><b><u>O</u>rdem:<br><INPUT ACCESSKEY="O" TYPE="TEXT" CLASS="sti" NAME="w_ordem" SIZE=4 MAXLENGTH=4 VALUE="'.$w_ordem.'" '.$w_Disabled.' TITLE="'.str_replace(chr(13).chr(10),"<BR>",$w_texto).'"></td>');
    ShowHTML('          <tr><td width="5%">');
    ShowHTML('              <td colspan=3><font size="1"><b><U>F</U>inalidade:<br><TEXTAREA ACCESSKEY="F" class="sti" name="w_finalidade" rows=3 cols=80 title="Descreva sucintamente a finalidade desta opção. Esta informação será apresentada quando o usuário passar o mouse em cima da opção, no menu.">'.$w_finalidade.'</textarea></td>');

    ShowHTML('          <tr><td colspan=4 height="30"><font size="2"><b>Parâmetros de acesso</td>');
    ShowHTML('          <tr><td width="5%"><td colspan="3" valign="top"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr>');
    $SelecaoModulo"<u>M</u>ódulo:"//M", "Informe a que módulo do SIW esta opção está vinculada. Caso não esteja vinculado a nenhum, selecione \'Opções gerais\'.', w_modulo, w_cliente, "w_modulo", null, null

    ShowHTML('              <td title="Opções de acesso geral aparecem para qualquer usuário, sem nenhuma restrição. \'Troca senha\' e \'Troca assinatura\' são exemplos onde este campo tem valor \'Sim\'."><font size="1"><b>Acesso geral?</b><br>');
    if ($w_acesso_geral=='S') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_acesso_geral" value="S" checked> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_acesso_geral" value="N"> Não');
    } else {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_acesso_geral" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_acesso_geral" value="N" checked> Não');
    } 

    ShowHTML('              <td title="Existem opções que estarão disponíveis para apenas alguns endereços da organização. Neste caso informe \'Sim\'."><font size="1"><b>Acesso descentralizado?</b><br>');
    if ($w_descentralizado=='S') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_descentralizado" value="S" checked> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_descentralizado" value="N"> Não');
    } else {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_descentralizado" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_descentralizado" value="N" checked> Não');
    } 

    ShowHTML('              <td title="Existem opções que não permitirão a inclusão, alteração e exclusão de registros. Neste caso informe \'Não\'."><font size="1"><b>Libera edição?</b><br>');
    if ($w_libera_edicao=='S') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_libera_edicao" value="S" checked> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_libera_edicao" value="N"> Não');
    } else {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_libera_edicao" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_libera_edicao" value="N" checked> Não');
    } 

    ShowHTML('          </table>');

    ShowHTML('          <tr><td colspan=4 height="30"><font size="2"><b>Parâmetros de programação</td>');
    ShowHTML('          <tr><td width="5%"><td colspan="3" valign="top"><table border="0" cellpadding="0" cellspacing="0"><tr>');
    ShowHTML('              <td width="10%"><font size="1"><b>Si<u>g</u>la:<br><INPUT ACCESSKEY="G" TYPE="TEXT" CLASS="sti" NAME="w_sigla" SIZE=10 MAXLENGTH=10 VALUE="'.$w_sigla.'" '.$w_Disabled.' title="Este campo é usado para implementar particularidades da opção no código-fonte. Não é possível informar a mesma sigla para duas opcões.">&nbsp;</td>');
    ShowHTML('              <td width="5%"><font size="1"><b>P<u>1</u>:<br><INPUT ACCESSKEY="1" TYPE="TEXT" CLASS="sti" NAME="w_p1" SIZE=6 MAXLENGTH=18 VALUE="'.$w_p1.'" '.$w_Disabled.' title="Parâmetro de uso geral, usado para implementar particularidades da opção no código-fonte. Pode ser repetido em outras opções.">&nbsp;</td>');
    ShowHTML('              <td width="5%"><font size="1"><b>P<u>2</u>:<br><INPUT ACCESSKEY="2" TYPE="TEXT" CLASS="sti" NAME="w_p2" SIZE=6 MAXLENGTH=18 VALUE="'.$w_p2.'" '.$w_Disabled.' title="Parâmetro de uso geral, usado para implementar particularidades da opção no código-fonte. Pode ser repetido em outras opções.">&nbsp;</td>');
    ShowHTML('              <td width="5%"><font size="1"><b>P<u>3</u>:<br><INPUT ACCESSKEY="3" TYPE="TEXT" CLASS="sti" NAME="w_p3" SIZE=6 MAXLENGTH=18 VALUE="'.$w_p3.'" '.$w_Disabled.' title="Parâmetro de uso geral, usado para implementar particularidades da opção no código-fonte. Pode ser repetido em outras opções.">&nbsp;</td>');
    ShowHTML('              <td width="5%"><font size="1"><b>P<u>4</u>:<br><INPUT ACCESSKEY="4" TYPE="TEXT" CLASS="sti" NAME="w_p4" SIZE=6 MAXLENGTH=18 VALUE="'.$w_p4.'" '.$w_Disabled.' title="Parâmetro de uso geral, usado para implementar particularidades da opção no código-fonte. Pode ser repetido em outras opções.">&nbsp;</td>');
    ShowHTML('              <td width="20%" title="Se uma opção tem controle de tramitação (work-flow), informe \'Sim\' e preencha os dados referentes à \'Configuração do serviço\'. Caso contrário, informe \'Não\'."><font size="1"><b>Vinculada a serviço?</b><br>');
    if ($w_tramite=='S') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_tramite" value="S" checked onClick="servico();"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_tramite" value="N" onClick="servico();"> Não');
    }  else {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_tramite" value="S" onClick="servico();"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_tramite" value="N" checked onClick="servico();"> Não');
    } 

    ShowHTML('          </table>');
    //ShowHTML "          <tr><td width=''5''"><td colspan=''4''><font size=''1''><b><u>P</u>róximo link:<br><INPUT ACCESSKEY="'P'" TYPE=''TEXT'' CLASS=''sti'' NAME=''w_proximo_link'' SIZE=60 MAXLENGTH=60 VALUE=''" & w_proximo_link & ''" " & w_Disabled & ">&nbsp;</td>"

    //ShowHTML "          <tr><td width=''5''"><td colspan=''4''><font size=''1''><b>A<u>n</u>terior link:<br><INPUT ACCESSKEY="'N'" TYPE=''TEXT'' CLASS=''sti'' NAME=''w_anterior_link'' SIZE=60 MAXLENGTH=60 VALUE=''" & w_anterior_link & ''" " & w_Disabled & ">&nbsp;</td>"

    ShowHTML('          <tr><td colspan=4 height="30"><font size="2"><b>Configuração do serviço<br></font><font size=1 color="#FF0000">(informe os campos abaixo apenas se o campo "Vinculada a serviço" for igual a "Sim")</font></td>');
    ShowHTML('          <tr><td width="5%"><td colspan="3" valign="top"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr>');
    // Recupera a lista de unidades ativas

    $SelecaoUnidade"<u>U</u>nidade responsável pela execução do serviço:"//U", "Informe a unidade organizacional responsável pela execução deste serviço. Se a organização tiver mais de um endereço e o serviço for descentralizado, informe a unidade responsável pela execução na sede.', w_sq_unidade_executora, null, "w_sq_unidade_executora", null, null
    ShowHTML('          </table>');
    ShowHTML('          <tr><td width="5%"><td colspan="3" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr align="left">');
    ShowHTML('              <td title="Existem serviços que necessitam de uma Ordem de Serviço. Informe \'Sim\' se for o caso desta opção."><font size="1"><b>Emite OS?</b><br>');
    if ($w_emite_os=='S') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_emite_os" value="S" checked> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_emite_os" value="N"> Não');
    } elseif ($w_emite_os=='N') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_emite_os" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_emite_os" value="N" checked> Não');
    } else {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_emite_os" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_emite_os" value="N" > Não');
    } 

    ShowHTML('              <td title="Existem serviços que deseja-se a opinião do solicitante com relação ao atendimento. Informe \'Sim\' se for o caso desta opção."><font size="1"><b>Consulta opinião?</b><br>');
    if ($w_consulta_opiniao=='S') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_consulta_opiniao" value="S" checked> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_consulta_opiniao" value="N"> Não');
    } elseif ($w_consulta_opiniao=='N') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_consulta_opiniao" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_consulta_opiniao" value="N" checked> Não');
    } else {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_consulta_opiniao" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_consulta_opiniao" value="N" > Não');
    } 

    ShowHTML('              <td title="Existem serviços que deseja-se o envio de e-mail a cada tramitação do atendimento. Informe \'Sim\' se for o caso desta opção."><font size="1"><b>Envia e-mail?</b><br>');
    if ($w_envia_email=='S') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_envia_email" value="S" checked> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_envia_email" value="N"> Não');
    } elseif ($w_envia_email=='N') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_envia_email" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_envia_email" value="N" checked> Não');
    } else {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_envia_email" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_envia_email" value="N" > Não');
    } 

    ShowHTML('          <tr align="left">');
    ShowHTML('              <td title="Existem serviços que deseja-se um resumo quantitativo periódico (atendimentos, opiniões, custos etc). Informe \'Sim\' se for o caso desta opção."><font size="1"><b>Consta do relatório gerencial?</b><br>');
    if ($w_exibe_relatorio=='S') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_exibe_relatorio" value="S" checked> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_exibe_relatorio" value="N"> Não');
    } elseif ($w_exibe_relatorio=='N') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_exibe_relatorio" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_exibe_relatorio" value="N" checked> Não');
    } else {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_exibe_relatorio" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_exibe_relatorio" value="N" > Não');
    } 

    ShowHTML('              <td title="Existem serviços que são vinculados à unidade (eletricista, transporte etc) e outros que são vinculados ao solicitante (adiantamentos salariais, férias etc). Se a vinculação for à unidade, usuários lotados na unidade do solicitante podem ver as solicitações; caso contrário, apenas o solicitante. Indique o tipo de vinculação deste serviço."><font size="1"><b>Tipo de vinculação:</b><br>');
    if ($w_vinculacao=='P') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_vinculacao" value="P" checked> Solicitante <input '.$w_Disabled.' class="str" type="radio" name="w_vinculacao" value="U"> Unidade');
    } elseif ($w_vinculacao=='U') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_vinculacao" value="P"> Solicitante <input '.$w_Disabled.' class="str" type="radio" name="w_vinculacao" value="U" checked> Unidade');
    } else {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_vinculacao" value="P"> Solicitante <input '.$w_Disabled.' class="str" type="radio" name="w_vinculacao" value="U" > Unidade');
    } 

    ShowHTML('              <td title="Alguns serviços necessitam da indicação do destinatário e outros não. Se a indicação do destinatário for necessária, uma caixa com o nome das pessoas que podem receber a solicitação será apresentada sempre que for feito um encaminhamento."><font size="1"><b>Indica destinatário?</b><br>');
    if ($w_envio=='S') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_envio" value="S" checked> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_envio" value="N"> Não');
    } elseif ($w_envio=='N') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_envio" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_envio" value="N" checked> Não');
    } else {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_envio" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_envio" value="N" > Não');
    } 

    ShowHTML('          <tr><td colspan=3 title="Existem serviços que exigem um controle de solicitações por ano. Informe \'Sim\' se for o caso desta opção."><font size="1"><b>Controla solicitações por ano?</b><br>');
    if ($w_controla_ano=='S') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_controla_ano" value="S" checked> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_controla_ano" value="N"> Não');
    } elseif ($w_controla_ano=='N') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_controla_ano" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_controla_ano" value="N" checked> Não');
    } else {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_controla_ano" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_controla_ano" value="N" > Não');
    } 

    ShowHTML('          <tr align="left">');
    ShowHTML('              <td colspan=3 title="Informe se esta opção pede data limite de atendimento e, se pedir, como a data deve ser informada."><font size="1"><b>Pede data limite?</b><br>');
    if ($w_data_hora=="0") {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="0" checked> Não<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="1"> Sim, apenas uma data (dd/mm/aaaa)<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="2"> Sim, apenas uma data/hora (dd/mm/aaaa, hh:mi)<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="3"> Sim, período de datas (dd/mm/aaaa a dd/mm/aaaa)<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="4"> Sim, período de datas/horas (dd/mm/aaaa, hh:mi a dd/mm/aaaa, hh:mi)');
    } elseif ($w_data_hora=="1") {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="0"> Não<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="1" checked> Sim, apenas uma data (dd/mm/aaaa)<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="2"> Sim, apenas uma data/hora (dd/mm/aaaa, hh:mi)<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="3"> Sim, período de datas (dd/mm/aaaa a dd/mm/aaaa)<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="4"> Sim, período de datas/horas (dd/mm/aaaa, hh:mi a dd/mm/aaaa, hh:mi)');
    } elseif ($w_data_hora=="2") {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="0"> Não<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="1"> Sim, apenas uma data (dd/mm/aaaa)<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="2" checked> Sim, apenas uma data/hora (dd/mm/aaaa, hh:mi)<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="3"> Sim, período de datas (dd/mm/aaaa a dd/mm/aaaa)<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="4"> Sim, período de datas/horas (dd/mm/aaaa, hh:mi a dd/mm/aaaa, hh:mi)');
    } elseif ($w_data_hora=="3") {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="0"> Não<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="1"> Sim, apenas uma data (dd/mm/aaaa)<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="2"> Sim, apenas uma data/hora (dd/mm/aaaa, hh:mi)<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="3" checked> Sim, período de datas (dd/mm/aaaa a dd/mm/aaaa)<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="4"> Sim, período de datas/horas (dd/mm/aaaa, hh:mi a dd/mm/aaaa, hh:mi)');
    } elseif ($w_data_hora=="4") {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="0"> Não<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="1"> Sim, apenas uma data (dd/mm/aaaa)<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="2"> Sim, apenas uma data/hora (dd/mm/aaaa, hh:mi)<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="3"> Sim, período de datas (dd/mm/aaaa a dd/mm/aaaa)<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="4" checked> Sim, período de datas/horas (dd/mm/aaaa, hh:mi a dd/mm/aaaa, hh:mi)');
    } else {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="0"> Não<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="1"> Sim, apenas uma data (dd/mm/aaaa)<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="2"> Sim, apenas uma data/hora (dd/mm/aaaa, hh:mi)<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="3"> Sim, período de datas (dd/mm/aaaa a dd/mm/aaaa)<br><input '.$w_Disabled.' class="str" type="radio" name="w_data_hora" value="4" > Sim, período de datas/horas (dd/mm/aaaa, hh:mi a dd/mm/aaaa, hh:mi)');
    } 

    ShowHTML('          <tr align="left">');
    ShowHTML('              <td title="Existem serviços que não podem ser atendidos aos sábados, domingos e feriados. Informe \'Sim\' se for o caso desta opção."><font size="1"><b>Apenas dias úteis?</b><br>');
    if ($w_envia_dia_util=='S') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_envia_dia_util" value="S" checked> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_envia_dia_util" value="N"> Não');
    } elseif ($w_envia_dia_util=='N') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_envia_dia_util" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_envia_dia_util" value="N" checked> Não');
    } else {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_envia_dia_util" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_envia_dia_util" value="N" > Não');
    } 

    ShowHTML('              <td title="Existem serviços em que deseja-se uma descrição da solicitação. Informe \'Sim\' se for o caso desta opção."><font size="1"><b>Pede descrição da solicitação?</b><br>');
    if ($w_pede_descricao=='S') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_pede_descricao" value="S" checked> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_pede_descricao" value="N"> Não');
    } elseif ($w_pede_descricao=='N') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_pede_descricao" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_pede_descricao" value="N" checked> Não');
    } else {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_pede_descricao" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_pede_descricao" value="N" > Não');
    } 

    ShowHTML('              <td title="Existem serviços que exigem uma justificativa da solicitação. Informe \'Sim\' se for o caso desta opção."><font size="1"><b>Pede justificativa da solicitação?</b><br>');
    if ($w_pede_justificativa=='S') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_pede_justificativa" value="S" checked> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_pede_justificativa" value="N"> Não');
    } elseif ($w_pede_justificativa=='N') {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_pede_justificativa" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_pede_justificativa" value="N" checked> Não');
    } else {
      ShowHTML('                 <input '.$w_Disabled.' class="str" type="radio" name="w_pede_justificativa" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_pede_justificativa" value="N" > Não');
    } 

    ShowHTML('          <tr><td colspan=3><font size="1"><b><U>C</U>omo funciona:<br><TEXTAREA ACCESSKEY="C" class="sti" name="w_como_funciona" rows=5 cols=80 title="Descreva sucintamente o funcionamento do serviço. Você pode entrar com as regras mais evidentes. Esta informação será apresentada em todas as solicitações deste serviço.">'.$w_como_funciona.'</textarea></td>');
    ScriptOpen('JavaScript');
    ShowHTML('  servico();');
    ScriptClose;
    ShowHTML('          </table>');

    if ($O=='I') {
      ShowHTML('          <tr><td colspan=4 height="30"><font size="1"><b>Ativo?</b><br>');
      if ($w_ativo=='S') {
        ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="w_ativo" value="S" checked> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_ativo" value="N"> Não');
      } else {
        ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="w_ativo" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="w_ativo" value="N" checked> Não');
      } 
    } 

    ShowHTML('      </table>');
    ShowHTML('      </td></tr>');
    ShowHTML('      <tr><td><font size="1"><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=''></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3"><input class="stb" type="submit" name="Botao" value="Gravar">&nbsp;');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=''.$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.$MontaFiltro['GET'].'';" name="Botao" value="Cancelar">');
    ShowHTML('</FORM>');
    
  } elseif ($O=='H') {

    $AbreForm"Form"$R//POST", "return(Validacao(this));", "content",P1,P2,P3,P4,TP,SG,R,'I'
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_menu" value="'.$w_sq_menu.'">');
    ShowHTML('<INPUT type="hidden" name="p_sq_endereco_unidade" value="'.$p_sq_endereco_unidade.'">');
    ShowHTML('<INPUT type="hidden" name="p_modulo" value="'.$p_modulo.'">');

    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center"><div align="justify"><font size=2>Selecione, na relação, a opção a ser utilizada como origem de dados.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td width="100%" align="left">');
    ShowHTML('    <table align="center" border="0">');
    ShowHTML('      <tr><td><table border=0 cellspacing=0 cellpadding=0>');
    ShowHTML('      <tr valign="top"><td><font size="1"><b><U>O</U>rigem:<br> <SELECT READONLY ACCESSKEY="O" class="sts" name="w_heranca" size="1">');
    ShowHTML('          <OPTION VALUE="">---');
    // Recupera as opções existentes

    db_getMenuList$RS$w_cliente$O$null;
    while(!$RS->EOF) {
      ShowHTML('          <OPTION VALUE='.$RS['sq_menu'].'>'.$RS['nome'];
      $RS->MoveNext;
    } 
    $DesconectaBD;
    ShowHTML('          </SELECT></td>');
    ShowHTML('      <tr><td align="center"><font size=1>&nbsp;');
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
  } elseif ($O=='P') {

    $AbreForm"Form"$w_pagina.$par//POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,'L'
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');

    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="70%">');
    ShowHTML('      <tr><td align="left"><table width="100%" border=0 cellspacing=0 cellpadding=0>');
    ShowHTML('      <tr valign="top">');
    $SelecaoEndereco"<U>E</U>ndereço:"//E", null, p_sq_endereco_unidade, null, "p_sq_endereco_unidade", "FISICO"
    ShowHTML('      </tr>');
    ShowHTML('      <tr valign="top">');
    $SelecaoModulo"<u>M</u>ódulo:"//M", null, p_modulo, w_cliente, "p_modulo", null, null
    ShowHTML('      </tr>');
    ShowHTML('      <tr valign="top">');
    $SelecaoMenu"<u>O</u>pção do menu principal:"//O", null, p_menu, null, "p_menu", "Pesquisa", null
    ShowHTML('      </tr>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td align="center" colspan="3"><font size=1>&nbsp;');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=''.$w_pagina.$par.'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'';" name="Botao" value="Remover filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('      </table>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {

    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose;
  } 

  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  $Estrutura_Texto_Fecha;
  if ($O!='H') {
    $Estrutura_Fecha;
    $Estrutura_Fecha;
    $Estrutura_Fecha;
    $Rodape;
  } 

  return $function_ret;
} 
?>


