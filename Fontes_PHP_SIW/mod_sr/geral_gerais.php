<?
// =========================================================================
// Rotina dos dados gerais para solicitações comuns
// -------------------------------------------------------------------------
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_readonly   = '';
  $w_erro       = '';
  $RS = db_getUorgData::getInstanceOf($dbms,$_SESSION['LOTACAO']);
  $w_nm_unidade = f($RS,'nome');
  $w_cidade     = f($RS,'sq_cidade');

  $RS = db_getRecurso::getInstanceOf($dbms,$w_cliente,$w_usuario,$w_menu,null,null,null,null,null,'SERVICO');
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
  } else {
    if ((strpos('AEV',$O)!==false) || $w_copia>'') {
      // Recupera os dados da solicitação
      if ($w_copia>'') {
        $RS = db_getSolicData::getInstanceOf($dbms,$w_copia,$SG); 
      } else { 
        $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
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
        switch ($w_data_hora) {
          case 1: 
            $w_fim              = FormataDataEdicao(f($RS,'fim'));
            break;
          case 2: 
            $w_fim              = substr(f($RS,'phpdt_fim'),0,-3);
            break;
          case 3: 
            $w_inicio           = FormataDataEdicao(f($RS,'inicio'));
            $w_fim              = FormataDataEdicao(f($RS,'fim'));
            break;
        case 4:
            $w_inicio           = substr(f($RS,'phpdt_inicio'),0,-3);
            $w_fim              = substr(f($RS,'phpdt_fim'),0,-3);
            break;
        } 
        if ($w_exibe_recurso) {
          $RS = db_getSolicRecursos::getInstanceOf($dbms,$w_cliente,$w_usuario,$w_chave,null,null,null,null,null,null,null,null,null,null,null);
          foreach ($RS as $row) {$RS = $row; break;}
          $w_tipo_recurso    = f($RS,'sq_tipo_recurso');
          $w_recurso         = f($RS,'sq_recurso');
          $w_solic_recurso   = f($RS,'chave_aux');
        }
      } 
    } 
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
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
    switch (f($RS_Menu,'data_hora')) {
      case 1: 
        Validate('w_fim','Data programada','DATA',1,10,10,'','0123456789/');
        CompData('w_fim','Data programada','>=',date('d/m/Y'),'data atual');
        break;
      case 2: 
        Validate('w_fim','Data programada','DATAHORA',1,17,17,'','0123456789/:, ');
        CompData('w_fim','Data programada','>=',date('d/m/Y, H:i:s'),'data e hora atual');
        break;
      case 3: 
        Validate('w_inicio','Início','DATA',1,10,10,'','0123456789/');       
        Validate('w_fim','Término','DATA',1,10,10,'','0123456789/');
        CompData('w_inicio','Início','<=','w_fim','Término');
        CompData('w_inicio','Início','>=',date('d/m/Y'),'data atual');
        break;
      case 4:
        Validate('w_inicio','Início','DATAHORA',1,17,17,'','0123456789/,: ');
        Validate('w_fim','Término','DATAHORA',1,17,17,'','0123456789/,: ');
        CompData('w_inicio','Início','<=','w_fim','Término');
        CompData('w_inicio','Início','>=',date('d/m/Y, H:i:s'),'data e hora atual');
        break;
    } 
    if ($w_exibe_recurso) {
      Validate('w_tipo_recurso','Tipo do recurso','SELECT',1,1,18,'','1');
      Validate('w_recurso','Recurso','SELECT',1,1,18,'','1');
    }
    if (f($RS_Menu,'descricao')=='S') {
      Validate('w_descricao','Detalhamento','1',1,5,2000,'1','1');
    } 
    if (f($RS_Menu,'justificativa')=='S') {
      Validate('w_justificativa','Justificativa','1',1,5,2000,'1','1');
    } 
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
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
  if (!(strpos('IAEV',$O)===false)) {
    if ($w_cidade=='') {
      // Carrega o valores padrão para cidade
      $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
      $w_cidade   = f($RS,'sq_cidade_padrao');
    } 
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled=' DISABLED ';
      if ($O=='V') {
        $w_Erro = Validacao($w_sq_solicitacao,$SG);
      } 
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_solic_recurso" value="'.$w_solic_recurso.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('<INPUT type="hidden" name="w_solicitante" value="'.$_SESSION['SQ_PESSOA'].'">');
    ShowHTML('<INPUT type="hidden" name="w_cidade" value="'.$w_cidade.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td colspan=2 align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=2 valign="top" align="center" bgcolor="#D0D0D0"><b>Identificação</td></td></tr>');
    ShowHTML('      <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=2>Os dados deste bloco serão utilizados para identificação da solicitação, bem como para o controle de sua execução.</td></tr>');
    ShowHTML('      <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td>Solicitante:<br><b>'.$_SESSION['NOME'].'</td>');
    ShowHTML('        <td>Unidade:<br><b>'.$w_nm_unidade.'</td>');
    ShowHTML('      <tr valign="top">');
    switch (f($RS_Menu,'data_hora')) {
      case 1: ShowHTML('              <td valign="top"><b>Da<u>t</u>a programada:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data limite para que a execução da solicitação esteja concluída."></td>');           break;
      case 2: ShowHTML('              <td valign="top"><b>Da<u>t</u>a programada:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_fim.'" onKeyDown="FormataDataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);" title="Data/hora limite para que a execução da solicitação esteja concluída."></td>');  break;
      case 3: 
        ShowHTML('              <td valign="top"><b>Iní<u>c</u>io:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_inicio,FormataDataEdicao(time())).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Início desejado para a solicitação."></td>'); 
        ShowHTML('              <td valign="top"><b><u>T</u>érmino:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data limite para que a execução da solicitação esteja concluída."></td>');
        break;
      case 4:
        ShowHTML('              <td valign="top"><b>Iní<u>c</u>io:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_inicio.'" onKeyDown="FormataDataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);" title="Data/hora de início da solicitação."></td>');
        ShowHTML('              <td valign="top"><b><u>T</u>érmino:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_fim.'" onKeyDown="FormataDataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);" title="Data/hora limite para que a execução da solicitação esteja concluída."></td>');
        break;
    } 
    if ($w_exibe_recurso) {
      ShowHTML('      <tr valign="top">');
      selecaoTipoRecurso_PE('T<U>i</U>po do recurso:','I',null,$w_tipo_recurso,f($RS_Menu,'sq_menu'),'w_tipo_recurso','FOLHA','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_recurso\'; document.Form.submit();"');
      selecaoRecurso('<U>R</U>ecurso:','R',null,$w_recurso,nvl($w_tipo_recurso,0),f($RS_Menu,'sq_menu'),'w_recurso','VINCULACAO','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_recurso\'; document.Form.submit();"');
      if (nvl($w_recurso,'')!='') {
        $RS = db_getRecurso::getInstanceOf($dbms,$w_cliente,$w_usuario,$w_recurso,null,null,null,null,null,null);
        foreach ($RS as $row) {$RS = $row; break;}
        ShowHTML('      <tr><td colspan=2><b>Descrição do recurso</b>:<br>'.nvl(f($RS,'descricao'),'---'));
      }
    }
    if (f($RS_Menu,'descricao')=='S' || f($RS_Menu,'justificativa')=='S') {
      if (f($RS_Menu,'descricao')=='S') {
        ShowHTML('      <tr><td colspan=2 valign="top"><b><u>D</u>etalhamento da solicitação:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_descricao" class="STI" ROWS=5 cols=75 title="Descreva sua necessidade, permitindo aos executores seu pleno entendimento.">'.$w_descricao.'</TEXTAREA></td>');
      } 
      if (f($RS_Menu,'justificativa')=='S') {
        ShowHTML('      <tr><td colspan=2 valign="top"><b><u>J</u>ustificativa:</b><br><textarea '.$w_Disabled.' accesskey="J" name="w_justificativa" class="STI" ROWS=5 cols=75 title="Justifique a necessidade de atendimento da solicitação.">'.$w_justificativa.'</TEXTAREA></td>');
      } 
    } 
    ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000"></TD></TR>');
    // Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML('      <tr><td align="center" colspan="2">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&w_copia='.$w_copia.'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
?>