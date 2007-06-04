<?
// =========================================================================
// Rotina de conclusão
// -------------------------------------------------------------------------
  global $w_Disabled;
  include_once($w_dir_volta.'funcoes/selecaoVeiculo.php');
  $w_chave       = $_REQUEST['w_chave'];
  $w_chave_aux   = $_REQUEST['w_chave_aux'];
  $w_solicitante = $_REQUEST['w_solicitante'];  
  $w_troca       = $_REQUEST['w_troca'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_fim                = $_REQUEST['w_fim'];
    $w_sq_veiculo         = $_REQUEST['w_sq_veiculo'];
    $w_valor              = $_REQUEST['w_valor'];
    $w_executor           = $_REQUEST['w_executor'];
    $w_observacao         = $_REQUEST['w_observacao'];
    $w_hodometro_saida    = $_REQUEST['w_hodometro_saida'];
    $w_hodometro_chegada  = $_REQUEST['w_hodometro_chegada'];
    $w_horario_saida      = $_REQUEST['w_horario_saida'];
    $w_horario_chegada    = $_REQUEST['w_horario_chegada'];
    $w_solicitante        = $_REQUEST['w_solicitante'];
    $w_recebedor          = $_REQUEST['w_recebedor'];
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="300; URL=../'.MontaURL('MESA').'">');
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataDataHora();
  FormataValor();
  ValidateOpen('Validacao');
  Validate('w_executor','Motorista','SELECT',1,1,18,'','0123456789');
  Validate('w_sq_veiculo','Placa','SELECT',1,1,18,'','0123456789');        
  Validate('w_horario_saida','Data / hora saída','DATAHORA',1,17,17,'','0123456789/:, ');
  Validate('w_hodometro_saida','Hodometro saída',1,1,1,7,'','0123456789'); 
  Validate('w_horario_chegada','Data / hora retorno','DATAHORA',1,17,17,'','0123456789/:, ');
  CompData('w_horario_saida','Data / hora saida','<=','w_horario_chegada','Data / hora retorno');
  Validate('w_hodometro_chegada','Hodometro chegada',1,1,3,7,'1','1');     
  CompValor('w_hodometro_chegada','Hodometro chegada','>','w_hodometro_saida','Hodometro saída');
  CompData('w_horario_chegada','Data / hora retorno','<=',date('d/m/Y, H:i:s'),'data e hora atual');
  Validate('w_recebedor','Passageiro que foi transportado','SELECT',1,1,18,'','0123456789');
  Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da solicitação, na opção 'Listagem'
  ShowHTML(VisualGeral($w_chave,'V',$w_usuario,$SG,null));
  ShowHTML('<HR>');
  ShowHTML('<FORM name="Form" method="POST" enctype="multipart/form-data" onSubmit="return(Validacao(this));" action="'.$w_dir.$w_pagina.'Grava&O='.$O.'&w_menu='.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
  ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
  ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
  ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
  ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
  ShowHTML('<INPUT type="hidden" name="SG" value="'.$SG.'">');
  ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_concluida" value="S">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  ShowHTML('    <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONCLUSÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
  ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
  ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
  ShowHTML('          <tr valign="top">');
  SelecaoPessoa('<u>M</u>otorista:','M','Selecione o motorista responsável pelo atendimento.',$w_executor,null,'w_executor','USUARIOS');
  SelecaoVeiculo('<u>P</u>laca:','P','Selecione o veículo',$w_cliente,$w_sq_veiculo,null,'w_sq_veiculo',null);
  ShowHTML('          <tr valign="top">');
  ShowHTML('              <td valign="top"><b>Da<u>t</u>a / hora de saída:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_horario_saida" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_horario_saida.'" onKeyDown="FormataDataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);" title="Informe a data/hora de término da solicitação."></td>');
  ShowHTML('              <td valign="top"><b><u>H</u>odômetro da saída:</b><br><input '.$w_Disabled.' accesskey="H" type="text" name="w_hodometro_saida" class="STI" SIZE="17" MAXLENGTH="7" VALUE="'.$w_hodometro_saida.'" title="Informe a data/hora de término da solicitação."></td>');
  ShowHTML('          <tr valign="top">');
  ShowHTML('              <td valign="top"><b>Dat<u>a</u> / hora de retorno:</b><br><input '.$w_Disabled.' accesskey="A" type="text" name="w_horario_chegada" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_horario_chegada.'" onKeyDown="FormataDataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);" title="Informe a data/hora de término da solicitação."></td>');
  ShowHTML('              <td valign="top"><b>H<u>o</u>dômentro na chegada:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_hodometro_chegada" class="STI" SIZE="17" MAXLENGTH="7" VALUE="'.$w_hodometro_chegada.'" title="Informe a data/hora de término da solicitação."></td>');
  ShowHTML('          <tr valign="top">');  
  MontaRadioSN('<b>Parcial?</b>',$w_parcial,'w_parcial');
  ShowHTML('          <tr valign="top">');  
  SelecaoPessoa('Pa<u>s</u>sageiro:','S','Selecione o passageiro.',$w_recebedor,null,'w_recebedor','USUARIOS');
  ShowHTML('          </table>');
  ShowHTML('    <tr><td colspan="2"><br><font size="2"><b>ARQUIVO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
  ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATENÇÃO: o tamanho máximo aceito para o arquivo é de '.(f($RS,'upload_maximo')/1024).' KBytes</font></b>.</td>');
  ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor.">');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Concluir">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
?>
