<?php
// =========================================================================
// Rotina de visualiza��o dos dados do cliente
// -------------------------------------------------------------------------
function visualCliente($w_sq_cliente,$O) {
  extract($GLOBALS);

  $sql = new db_getCustomerData; $RS_Cliente = $sql->getInstanceOf($dbms,$w_sq_cliente);

  if ($O=='L') {
    // Se for listagem dos dados
    ShowHTML('<div align=center><center>');
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="99%" border="0">');
    ShowHTML('      <tr><td align="center" colspan="2"><font size=3><b>'.f($RS_Cliente,'nome_resumido').' ('.f($RS_Cliente,'cnpj').')</font></b></td></tr>');

    // Identifica��o civil e localiza��o
    ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Identifica��o Civil e Localiza��o</td>');
    ShowHTML('      <tr><td colspan="2">Raz�o Social:<br><b>'.f($RS_Cliente,'nome').' </b></td>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td>C�digo interno:<br><b>'.f($RS_Cliente,'sq_pessoa').' </b></td>');
    ShowHTML('          <td>Segmento:<br><b>'.f($RS_Cliente,'segmento').' </b></td>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td>Inscri��o estadual:<br><b>'.Nvl(f($RS_Cliente,'inscricao_estadual'),'N�o informada').' </b></td>');
    ShowHTML('          <td>In�cio das atividades:<br><b>'.FormataDataEdicao(f($RS_Cliente,'inicio_atividade')).' </b></td>');
    ShowHTML('          <td>Sede (Matriz)?<br><b>'.str_replace('N','N�o',str_replace('S','Sim',f($RS_Cliente,'sede'))).' </b></td>');
    ShowHTML('        </table>');

    // Cidade e ag�ncia padr�o
    ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Cidade e Ag�ncia Padr�o</td>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td>Cidade:<br><b>'.f($RS_Cliente,'cidade').' </b></td>');
    ShowHTML('          <td>Estado:<br><b>'.f($RS_Cliente,'co_uf').' </b></td>');
    ShowHTML('          <td>Pa�s:<br><b>'.f($RS_Cliente,'pais').' </b></td>');
    ShowHTML('        </table>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td>Banco:<br><b>'.f($RS_Cliente,'banco').' </b></td>');
    ShowHTML('          <td>Ag�ncia:<br><b>'.f($RS_Cliente,'codigo').' - '.f($RS_Cliente,'agencia').' </b></td>');
    ShowHTML('        </table>');

    // Par�metros de seguran�a
    ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Par�metros de Seguran�a</td>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td>Tamanho m�nimo:<br><b>'.f($RS_Cliente,'TAMANHO_MIN_SENHA').' </b></td>');
    ShowHTML('          <td>Tamanho m�ximo:<br><b>'.f($RS_Cliente,'TAMANHO_MAX_SENHA').' </b></td>');
    ShowHTML('          <td>M�ximo de tentativas:<br><b>'.f($RS_Cliente,'maximo_tentativas').' </b></td>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td>Limite da vig�ncia:<br><b>'.f($RS_Cliente,'DIAS_VIG_SENHA').' </b></td>');
    ShowHTML('          <td>Dias para aviso:<br><b>'.f($RS_Cliente,'DIAS_AVISO_EXPIR').' </b></td>');
    ShowHTML('          <td>Tipo de autentica��o:<br><b>Senha de acesso e assinatura eletr�nica '.((f($RS_Cliente,'tipo_autenticacao')==1) ? 'separadas' : 'integradas').' </b></td>');
    ShowHTML('        </table>');

    //Endere�os de e-mail e internet
    $SQL = new db_getAddressList; $RS = $SQL->getInstanceOf($dbms,$w_sq_cliente,null,'EMAILINTERNET',null);
    $RS = SortArray($RS,'tipo_endereco','asc','padrao','desc','endereco','asc');
    ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Endere�os e-Mail e Internet ('.count($RS).')</td>');
    ShowHTML('      <tr><td align="center" colspan="2">');
    ShowHTML('        <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('            <td><b>Endere�o</td>');
    ShowHTML('            <td><b>Padr�o</td>');
    ShowHTML('          </tr>');
    if (count($RS)==0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=2 align="center"><b>N�o informado.</b></td></tr>');
    } else {
      foreach ($RS as $row) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
        if (f($row,'email')=='S') {
          ShowHTML('        <td><a href="mailto:'.f($row,'logradouro').'">'.f($row,'logradouro').'</a></td>');
        } else {
          ShowHTML('        <td><a href="'.f($row,'logradouro').'" target="_blank">'.f($row,'logradouro').'</a></td>');
        } 
        ShowHTML('        <td align="center">'.str_replace('N','N�o',str_replace('S','Sim',f($row,'padrao'))).'</td>');
        ShowHTML('      </tr>');
      }
    } 
    ShowHTML('         </table></td></tr>');
  
    //Endere�os f�sicos
    $SQL = new db_getAddressList; $RS = $SQL->getInstanceOf($dbms,$w_sq_cliente,null,'FISICO',null);
    $RS = SortArray($RS,'padrao','desc','logradouro','asc');
    ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Endere�os F�sicos ('.count($RS).')</td>');
    if (count($RS)==0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><b>N�o informado.</b></td></tr>');
    } else {
      foreach ($RS as $row) {
        ShowHTML('      <tr><td align="center" colspan="2"><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">');
        ShowHTML('          <tr><td colspan=2><b>'.f($row,'endereco').'</td>');
        ShowHTML('          <tr><td width="5%" rowspan=4><td>Logradouro:<br><b>'.f($row,'logradouro').'</td></tr>');
        ShowHTML('          <tr><td><table border=0 width="100%" cellspacing=0>');
        ShowHTML('            <tr valign="top">');
        ShowHTML('              <td>Complemento:<br><b>'.Nvl(f($row,'complemento'),'---').' </b></td>');
        ShowHTML('              <td>Bairro:<br><b>'.Nvl(f($row,'bairro'),'---').' </b></td>');
        ShowHTML('              <td>CEP:<br><b>'.Nvl(f($row,'cep'),'---').' </b></td>');
        ShowHTML('            <tr valign="top">');
        ShowHTML('              <td>Pa�s:<br><b>'.f($row,'nm_pais').' </b></td>');
        ShowHTML('              <td>Padr�o?<br><b>'.str_replace('N','N�o',str_replace('S','Sim',f($row,'padrao'))).'</td>');
        ShowHTML('            </table>');
        ShowHTML('          </table></td></tr>');
      } 
    } 

    //Telefones
    $SQL = new db_getFoneList; $RS = $SQL->getInstanceOf($dbms,$w_sq_cliente,null,null,null);
    $RS = SortArray($RS,'tipo_telefone','asc','cidade','asc','padrao','desc','numero','asc');
    ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Telefones ('.count($RS).')</td>');
    ShowHTML('      <tr><td align="center" colspan="2">');
    ShowHTML('        <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('            <td><b>Tipo</td>');
    ShowHTML('            <td><b>DDD</td>');
    ShowHTML('            <td><b>N�mero</td>');
    ShowHTML('            <td><b>Cidade</td>');
    ShowHTML('            <td><b>UF</td>');
    ShowHTML('            <td><b>Pa�s</td>');
    ShowHTML('            <td><b>Padr�o</td>');
    ShowHTML('          </tr>');
    if (count($RS)==0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>N�o informado.</b></td></tr>');
    } else {
      foreach ($RS as $row) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'tipo_telefone').'</td>');
        ShowHTML('        <td align="center">'.f($row,'ddd').'</td>');
        ShowHTML('        <td>'.f($row,'numero').'</td>');
        ShowHTML('        <td>'.Nvl(f($row,'cidade'),'---').'</td>');
        ShowHTML('        <td align="center">'.Nvl(f($row,'co_uf'),'---').'</td>');
        ShowHTML('        <td>'.Nvl(f($row,'pais'),'---').'</td>');
        ShowHTML('        <td align="center">'.str_replace('N','N�o',str_replace('S','Sim',f($row,'padrao'))).'</td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('         </table></td></tr>');

    //Contas banc�rias
    $SQL = new db_getContaBancoList; $RS = $SQL->getInstanceOf($dbms,$w_sq_cliente,null,null);
    $RS = SortArray($RS,'tipo_conta','asc','padrao','desc','banco','asc','numero','asc');
    $cs = 0;
    ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Contas Banc�rias ('.count($RS).')</td>');
    ShowHTML('      <tr><td align="center" colspan="2">');
    ShowHTML('        <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
    $cs++; ShowHTML('            <td><b>Tipo</td>');
    $cs++; ShowHTML('            <td><b>Banco</td>');
    $cs++; ShowHTML('            <td><b>Ag�ncia</td>');
    $cs++; ShowHTML('            <td><b>Opera��o</td>');
    $cs++; ShowHTML('            <td><b>N�mero</td>');
    if (nvl(f($RS_Cliente,'sg_segmento'),'-')=='OI') { $cs++; ShowHTML('            <td><b>Moeda</td>'); }
    $cs++; ShowHTML('            <td><b>Ativo</td>');
    $cs++; ShowHTML('            <td><b>Padr�o</td>');
    ShowHTML('          </tr>');
    if (count($RS)==0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$cs.' align="center"><b>N�o informado.</b></td></tr>');
    } else {
      foreach ($RS as $row) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'tipo_conta').'</td>');
        ShowHTML('        <td>'.f($row,'banco').'</td>');
        ShowHTML('        <td>'.f($row,'agencia').'</td>');
        ShowHTML('        <td align="center">'.Nvl(f($row,'operacao'),'---').'</td>');
        ShowHTML('        <td>'.f($row,'numero').'</td>');
        if (nvl(f($RS_Cliente,'sg_segmento'),'-')=='OI') ShowHTML('        <td align="center">'.f($row,'sb_moeda').'</td>');
        ShowHTML('        <td align="center">'.str_replace('N','N�o',str_replace('S','Sim',f($row,'ativo'))).'</td>');
        ShowHTML('        <td align="center">'.str_replace('N','N�o',str_replace('S','Sim',f($row,'padrao'))).'</td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('         </table></td></tr>');

    //M�dulos contratados
    $sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_sq_cliente,null,null);
    ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>M�dulos Contratados ('.count($RS).')</td>');
    ShowHTML('      <tr><td align="center" colspan="2">');
    ShowHTML('        <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('             <td><b>M�dulo</td>');
    ShowHTML('             <td><b>Sigla</td>');
    ShowHTML('             <td><b>Objetivo geral</td>');
    ShowHTML('          </tr>');
    if (count($RS)==0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>N�o informado.</b></td></tr>');
    } else {
      foreach ($RS as $row) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'sigla').'</td>');
        ShowHTML('        <td>'.f($row,'objetivo_geral').'</td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('         </table></td></tr>');

    //Usu�rios cadastrados
    $SQL = new db_getUserList; $RS = $SQL->getInstanceOf($dbms,$w_sq_cliente,null,null,null,null,null,null,null,null,null,'S',null,null,null,null,null);
    $RS = SortArray($RS,'nome_resumido_ind','asc');
    ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Usu�rios Cadastrados ('.count($RS).')</td>');
    ShowHTML('      <tr><td align="center" colspan="2">');
    ShowHTML('        <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('            <td><b>Aut.</td>');
    ShowHTML('            <td><b>Username</td>');
    ShowHTML('            <td><b>Nome</td>');
    ShowHTML('            <td><b>Lota��o</td>');
    ShowHTML('            <td><b>Ramal</td>');
    ShowHTML('            <td><b>V�nculo</td>');
    ShowHTML('            <td><b>Ativo</td>');
    ShowHTML('          </tr>');
    if (count($RS)==0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>N�o informado.</b></td></tr>');
    } else {
      foreach ($RS as $row) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
        ShowHTML('        <td align="center" nowrap>'.f($row,'nm_tipo_autenticacao').'</td>');
        ShowHTML('        <td align="center" nowrap>'.f($row,'username').'</td>');
        ShowHTML('        <td title="'.f($row,'nome').'">'.f($row,'nome_resumido').'</td>');
        ShowHTML('        <td>'.f($row,'lotacao').'&nbsp;('.f($row,'localizacao').')</td>');
        ShowHTML('        <td align="center">&nbsp;'.Nvl(f($row,'ramal'),'---').'</td>');
        ShowHTML('        <td>&nbsp;'.Nvl(f($row,'vinculo'),'---').'</td>');
        ShowHTML('        <td align="center">&nbsp;'.str_replace('N','N�o',str_replace('S','Sim',f($row,'ativo'))).'</td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('         </table></td></tr>');

    //Configura��o da aplica��o
    ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Configura��o da Aplica��o</td>');
    ShowHTML('      <tr><td align="center" colspan="2">');
    ShowHTML('        <TABLE WIDTH="100%">');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" valign="top">');
    ShowHTML('             <td>Servidor SMTP:<br><b>'.f($RS_Cliente,'smtp_server').'</b></td>');
    ShowHTML('             <td>Nome do remetente:<br><b>'.f($RS_Cliente,'siw_email_nome').'</b></td>');
    ShowHTML('             <td>Conta do remetente:<br><b>'.f($RS_Cliente,'siw_email_conta').'</b></td>');
    ShowHTML('          </tr>');
    ShowHTML('         </table></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="2">');
    ShowHTML('        <TABLE WIDTH="100%">');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" valign="top">');
    if (f($RS_Cliente,'logo')>'') {
      ShowHTML('             <td colspan=3>Logomarca telas e relat�rios:<br><b><img src="'.LinkArquivo(null,$w_sq_cliente,'img/logo'.substr(f($RS_Cliente,'logo'),(strpos(f($RS_Cliente,'logo'),'.') ? strpos(f($RS_Cliente,'logo'),'.')+1 : 0)-1,30),null,null,null,'EMBED').'" border=1></b></td>');
    } else {
      ShowHTML('             <td colspan=3>N�o informado</td>');
    } 
    if (f($RS_Cliente,'logo')>'') {
      ShowHTML('             <td colspan=3>Logomarca menu:<br><b><img src="'.LinkArquivo(null,$w_sq_cliente,'img/logo1'.substr(f($RS_Cliente,'logo1'),(strpos(f($RS_Cliente,'logo1'),'.') ? strpos(f($RS_Cliente,'logo1'),'.')+1 : 0)-1,30),null,null,null,'EMBED').'" border=1></b></td>');
    } else {
      ShowHTML('             <td colspan=3>N�o informado</td>');
    } 
    ShowHTML('          </tr>');
    ShowHTML('         </table></td></tr>');

    //Funcionalidades
    $w_imagemPadrao='images/Folder/SheetLittle.gif';
    ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Funcionalidades</td>');
    $sql = new db_getLinkDataUser; $RS = $sql->getInstanceOf($dbms,$w_sq_cliente,0,'IS NULL');
    $cs = 0;
    ShowHTML('      <tr><td align="center" colspan="2">');
    ShowHTML('        <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('            <td><b>Chave</td>');
    $cs++; ShowHTML('            <td><b>Op��o</td>');
    $cs++; ShowHTML('            <td><b>Link</td>');
    $cs++; ShowHTML('            <td><b>Sigla</td>');
    $cs++; ShowHTML('            <td><b>P1</td>');
    $cs++; ShowHTML('            <td><b>P2</td>');
    $cs++; ShowHTML('            <td><b>P3</td>');
    $cs++; ShowHTML('            <td><b>P4</td>');
    $cs++; ShowHTML('            <td><b>Target</td>');
    $cs++; ShowHTML('            <td><b>Sub-menu</td>');
    $cs++; ShowHTML('            <td><b>Ativo</td>');
    ShowHTML('          </tr>');
    if (count($RS)==0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$cs.' align="center"><b>N�o informado.</b></td></tr>');
    } else {
      foreach ($RS as $row) {
        if (f($row,'Filho')>0) {
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          ShowHTML('        <td align="center"> '.f($row,'sq_menu'));
          ShowHTML('        <td colspan='.$cs.'><img src="images/Folder/FolderClose.gif" border=0 align="center"> <b>'.f($row,'nome'));
          $sql = new db_getLinkDataUser; $RS1 = $sql->getInstanceOf($dbms,$w_sq_cliente,0,f($row,'sq_menu'));
          foreach ($RS1 as $row1) {
            if (f($row1,'Filho')>0) {
              ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
              ShowHTML('        <td align="center"> '.f($row1,'sq_menu'));
              ShowHTML('        <td colspan='.$cs.' nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row1,'nome'));
              $sql = new db_getLinkDataUser; $RS2 = $sql->getInstanceOf($dbms,$w_sq_cliente,0,f($row1,'sq_menu'));
              foreach ($RS2 as $row2) {
                if (f($row2,'Filho')>0) {
                  ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
                  ShowHTML('        <td align="center"> '.f($row2,'sq_menu'));
                  ShowHTML('        <td colspan='.$cs.' nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row2,'nome'));
                  $sql = new db_getLinkDataUser; $RS3 = $sql->getInstanceOf($dbms,$w_sq_cliente,0,f($row2,'sq_menu'));
                  foreach ($RS3 as $row3) {
                    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
                    ShowHTML('        <td align="center"> '.f($row3,'sq_menu'));
                    ShowHTML('        <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="'.$w_imagem.'" border=0 align="center"> '.f($row3,'nome'));
                    ShowHTML('        <td title="'.f($row3,'link').'"> '.Nvl(substr(f($row3,'link'),0,30),'-'));
                    ShowHTML('        <td> '.Nvl(f($row3,'sigla'),'-'));
                    ShowHTML('        <td align="center"> '.Nvl(f($row3,'p1'),'-'));
                    ShowHTML('        <td align="center"> '.Nvl(f($row3,'p2'),'-'));
                    ShowHTML('        <td align="center"> '.Nvl(f($row3,'p3'),'-'));
                    ShowHTML('        <td align="center"> '.Nvl(f($row3,'p4'),'-'));
                    ShowHTML('        <td align="center"> '.Nvl(f($row3,'target'),'-'));
                    ShowHTML('        <td align="center"> '.str_replace('N','N�o',str_replace('S','Sim',f($row3,'ultimo_nivel'))));
                    ShowHTML('        <td align="center"> '.str_replace('N','N�o',str_replace('S','Sim',f($row3,'ativo'))));
                  } 
                } else {
                  if (f($row2,'IMAGEM')>'') {
                    $w_imagem=f($row2,'IMAGEM');
                  } else {
                    $w_imagem=$w_imagemPadrao;
                  } 
                  ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
                  ShowHTML('        <td align="center"> '.f($row2,'sq_menu'));
                  ShowHTML('        <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="'.$w_imagem.'" border=0 align="center"> '.f($row2,'nome'));
                  ShowHTML('        <td title="'.f($row2,'link').'"> '.Nvl(substr(f($row2,'link'),0,30),'-'));
                  ShowHTML('        <td> '.Nvl(f($row2,'sigla'),'-'));
                  ShowHTML('        <td align="center"> '.Nvl(f($row2,'p1'),'-'));
                  ShowHTML('        <td align="center"> '.Nvl(f($row2,'p2'),'-'));
                  ShowHTML('        <td align="center"> '.Nvl(f($row2,'p3'),'-'));
                  ShowHTML('        <td align="center"> '.Nvl(f($row2,'p4'),'-'));
                  ShowHTML('        <td align="center"> '.Nvl(f($row2,'target'),'-'));
                  ShowHTML('        <td align="center"> '.str_replace('N','N�o',str_replace('S','Sim',f($row2,'ultimo_nivel'))));
                  ShowHTML('        <td align="center"> '.str_replace('N','N�o',str_replace('S','Sim',f($row2,'ativo'))));
                } 
              } 
              ShowHTML('   </div>');
            } else {
              if (f($row1,'IMAGEM')>'') {
                $w_imagem=f($row1,'IMAGEM');
              } else {
                $w_imagem=$w_imagemPadrao;
              } 
              ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
              ShowHTML('        <td align="center"> '.f($row1,'sq_menu'));
              ShowHTML('        <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<img src="'.$w_imagem.'" border=0 align="center"> '.f($row1,'nome'));
              ShowHTML('        <td title="'.f($row1,'link').'"> '.Nvl(substr(f($row1,'link'),0,30),'-'));
              ShowHTML('        <td> '.Nvl(f($row1,'sigla'),'-'));
              ShowHTML('        <td align="center"> '.Nvl(f($row1,'p1'),'-'));
              ShowHTML('        <td align="center"> '.Nvl(f($row1,'p2'),'-'));
              ShowHTML('        <td align="center"> '.Nvl(f($row1,'p3'),'-'));
              ShowHTML('        <td align="center"> '.Nvl(f($row1,'p4'),'-'));
              ShowHTML('        <td align="center"> '.Nvl(f($row1,'target'),'-'));
              ShowHTML('        <td align="center"> '.str_replace('N','N�o',str_replace('S','Sim',f($row1,'ultimo_nivel'))));
              ShowHTML('        <td align="center"> '.str_replace('N','N�o',str_replace('S','Sim',f($row1,'ativo'))));
            }  
          } 
          ShowHTML('   </div>');
        } else {
          if (f($row,'IMAGEM')>'') {
            $w_imagem=f($row,'IMAGEM');
          } else {
            $w_imagem=$w_imagemPadrao;
          } 
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          ShowHTML('        <td align="center"> '.f($row,'sq_menu'));
          ShowHTML('        <td nowrap><img src="'.$w_imagem.'" border=0 align="center"><b> '.f($row,'nome'));
          ShowHTML('        <td title="'.f($row,'link').'"> '.Nvl(substr(f($row,'link'),0,30),'-'));
          ShowHTML('        <td> '.Nvl(f($row,'sigla'),'-'));
          ShowHTML('        <td align="center"> '.Nvl(f($row,'p1'),'-'));
          ShowHTML('        <td align="center"> '.Nvl(f($row,'p2'),'-'));
          ShowHTML('        <td align="center"> '.Nvl(f($row,'p3'),'-'));
          ShowHTML('        <td align="center"> '.Nvl(f($row,'p4'),'-'));
          ShowHTML('        <td align="center"> '.Nvl(f($row,'target'),'-'));
          ShowHTML('        <td align="center"> '.str_replace('N','N�o',str_replace('S','Sim',f($row,'ultimo_nivel'))));
          ShowHTML('        <td align="center"> '.str_replace('N','N�o',str_replace('S','Sim',f($row,'ativo'))));
        } 
      } 
    } 
    ShowHTML('         </table></td></tr>');
    ShowHTML('     </tr></tr></td></table>');
    ShowHTML('</table>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
}
?>
