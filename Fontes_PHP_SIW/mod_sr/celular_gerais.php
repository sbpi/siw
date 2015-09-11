<?php
// =========================================================================
// Rotina dos dados gerais para solicitações comuns
// -------------------------------------------------------------------------
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_readonly   = '';
  $w_erro       = '';
  $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$_SESSION['LOTACAO']);
  $w_nm_unidade = f($RS,'nome');
  $w_cidade     = f($RS,'sq_cidade');

  $sql = new db_getRecurso; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$w_menu,null,null,null,null,null,'SERVICO');
  if (count($RS)) $w_exibe_recurso = true; else $w_exibe_recurso = false;

  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'') {
    // Se for recarga da página
    $w_chave            = $_REQUEST['w_chave'];
    $w_chave_pai        = $_REQUEST['w_chave_pai'];
    $w_chave_aux        = $_REQUEST['w_chave_aux'];
    $w_solic_recurso    = $_REQUEST['w_solic_recurso'];
    $w_sq_menu          = $_REQUEST['w_sq_menu'];
    $w_sq_unidade       = $_REQUEST['w_sq_unidade'];
    $w_solicitante      = $_REQUEST['w_solicitante'];
    $w_descricao        = $_REQUEST['w_descricao'];
    $w_justificativa    = $_REQUEST['w_justificativa'];
    $w_data_hora        = $_REQUEST['w_data_hora'];
    $w_inicio           = $_REQUEST['w_inicio'];
    $w_fim              = $_REQUEST['w_fim'];
    $w_tipo_recurso     = $_REQUEST['w_tipo_recurso'];
    $w_recurso          = $_REQUEST['w_recurso'];
    $w_solic_recurso    = $_REQUEST['w_solic_recurso'];
    $w_envio            = $_REQUEST['w_envio'];
    $w_tramite          = $_REQUEST['w_tramite'];
  } else {
    if ((strpos('AEV',$O)!==false) || $w_copia>'') {
      // Recupera os dados da solicitação
      if ($w_copia>'') {
        $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_copia,$SG); 
      } else { 
        $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
      }
      if (count($RS)>0) {
        $w_chave_pai        = f($RS,'sq_solic_pai');
        $w_chave_aux        = null;
        $w_sq_menu          = f($RS,'sq_menu');
        $w_sq_unidade       = f($RS,'sq_unidade');
        $w_solicitante      = f($RS,'solicitante');
        $w_descricao        = f($RS,'descricao');
        $w_justificativa    = f($RS,'justificativa');
        $w_data_hora        = f($RS,'data_hora');
        $w_tramite          = f($RS,'sq_siw_tramite');
        $w_inicio           = FormataDataEdicao(f($RS,'inicio')); 
        $w_fim              = FormataDataEdicao(f($RS,'fim'));
        if ($w_exibe_recurso) {
          $sql = new db_getSolicRecursos; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$w_chave,null,null,null,null,null,null,null,null,null,null,null);
          foreach ($RS as $row) {$RS = $row; break;}
          $w_tipo_recurso    = f($RS,'sq_tipo_recurso');
          $w_recurso         = f($RS,'sq_recurso');
          $w_solic_recurso   = f($RS,'chave_aux');
        }
      } 
    } 
  } 
  
  // Recupera a unidade solicitante, que sempre é igual à unidade de lotação atual do beneficiário da solicitação
  if ($O=='I' || nvl($w_solicitante,'')!='') {
    $sql = new db_getBenef; $RS_Benef = $sql->getInstanceOf($dbms,$w_cliente,nvl($w_solicitante,$w_usuario),null,null,null,null,null,null,null,null,null,null,null,null, null, null, null, null);
    foreach ($RS_Benef as $row) {$RS_Benef=$row; break;}
    $w_sq_unidade = f($RS_Benef,'sq_unidade_benef');
    $w_nm_unidade = f($RS_Benef,'nm_unidade_benef');
  }

  Cabecalho();
  head();
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataDataHora();
  FormataValor();
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.Botao.value == "Troca") { return true; }');
    Validate('w_solicitante','Beneficiário','SELECT',1,1,18,'','1');
    Validate('w_sq_unidade','Setor solicitante','SELECT',1,1,18,'','1');
    Validate('w_inicio','Início','DATA',1,10,10,'','0123456789/');       
    Validate('w_fim','Término','DATA',1,10,10,'','0123456789/');
    CompData('w_inicio','Início','<=','w_fim','Término');
    CompData('w_inicio','Início','>=',date('d/m/Y'),'data atual');
    Validate('w_pais','País de destino','SELECT','1','1','18','','1');
    Validate('w_justificativa','Justificativa','1',1,5,2000,'1','1');
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','','3','30','1','1');
    ShowHTML('  if (theForm.w_envio.value=="S" && theForm.w_assinatura.value=="") {');
    ShowHTML('     alert("Para envio automático a assinatura eletrônica deve ser informada!");');
    ShowHTML('     theForm.w_assinatura.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
  }
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  if ($w_envio_inclusao=='S')  ShowHTML('  theForm.btEnvio.disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (strpos('EV',$O)!==false) {
    BodyOpenClean('onLoad=\'document.Form.$w_assinatura.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  if ($P1==1) ShowHTML('<div align="left"><table border=0><tr valign="top"><td><b>Finalidade:</b><td nowrap>'.f($RS_Menu,'finalidade').'</tr></table></div>');  
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (strpos('IAEV',$O)!==false) {
    if ($w_cidade=='') {
      // Carrega o valores padrão para cidade
      $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
      $w_cidade   = f($RS,'sq_cidade_padrao');
    } 
    if (strpos('EV',$O)!==false) {
      $w_Disabled=' DISABLED ';
      if ($O=='V') {
        $w_Erro = Validacao($w_sq_solicitacao,$SG);
      } 
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_envio" value="N">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_solic_recurso" value="'.$w_solic_recurso.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('<INPUT type="hidden" name="w_cidade" value="'.$w_cidade.'">');
    ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_unidade" value="'.$w_sq_unidade.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td colspan=2 align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=2 valign="top" align="center" bgcolor="#D0D0D0"><b>Identificação</td></td></tr>');
    ShowHTML('      <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=2>Os dados deste bloco serão utilizados para identificação da solicitação, bem como para o controle de sua execução.</td></tr>');
    ShowHTML('      <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr valign="top">');
    SelecaoPessoa('Be<u>n</u>eficiário:','N','Selecione o beneficiário da solicitação.',nvl($w_solicitante,$w_usuario),null,'w_solicitante','USUARIOS','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_solicitante\'; document.Form.submit();"');
    ShowHTML('        <td>Unidade:<br><b>'.$w_nm_unidade.'</td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td valign="top"><b><u>R</u>etirada:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_inicio,FormataDataEdicao(time())).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data desejada para a retirada."></td>'); 
    ShowHTML('        <td valign="top"><b><u>D</u>evolução:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data desejada para devolução."></td>');

    // Recupera os dados do cliente
    $sql = new db_getCustomerData; $RS_Cliente = $sql->getInstanceOf($dbms,$w_cliente );
    $w_pais_padrao=f($RS_Cliente,'sq_pais');
    ShowHTML('      <tr valign="top">');
    SelecaoPais('Pa<u>í</u>s de destino:', 'I', null, nvl($w_pais,$w_pais_padrao), null, 'w_pais', null, null);
    
    ShowHTML('      <tr><td colspan=2 valign="top"><b><u>J</u>ustificativa:</b><br><textarea '.$w_Disabled.' accesskey="J" name="w_justificativa" class="STI" ROWS=5 cols=75 title="Justifique a necessidade de atendimento da solicitação.">'.$w_justificativa.'</TEXTAREA></td>');
    ShowHTML('      <tr><td colspan=2><b>'.$_SESSION['LABEL_CAMPO'].' (obrigatório informar se desejar envio automático):<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000"></TD></TR>');
    // Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML('      <tr><td align="center" colspan="2">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar" onClick="document.Form.w_envio.value=\'N\';">');
    ShowHTML('            <input class="STB" type="submit" name="btEnvio" value="Gravar e Enviar" onClick="document.Form.w_envio.value=\'S\'; confirm(\'Confirma gravação e envio automático da solicitação para a próxima fase?\nSe sim, a assinatura eletrônica deve ser informada.\');">');
    $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$w_menu);
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&w_copia='.$w_copia.'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
?>