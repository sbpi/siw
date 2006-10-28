<?
// =========================================================================
// Rotina de visualiza��o do curr�culo
// -------------------------------------------------------------------------
function visualCurriculo($p_cliente,$p_usuario,$O,$p_formato=0) {
  extract($GLOBALS);
  if ($O=='L') {
    // Se for listagem dos dados
    // Identifica��o pessoal
    $RS = db_getCV::getInstanceOf($dbms,$p_cliente,$p_usuario,'CVIDENT','DADOS');
    foreach ($RS as $row) {$RS=$row; break;}
    $w_nome = f($RS,'nome');
    $HTML='<div align=center><center>';
    $html.=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
    $html.=chr(13).'<tr bgcolor="'.$conTrBgColor.'"><td align="center">';
    $html.=chr(13).'    <table width="99%" border="0">';
    $html.=chr(13).'      <tr><td align="center" colspan="3"><font size=5><b>'.f($RS,'nome').'</b></font></td></tr>';
    $html.=chr(13).'      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Identifica��o</td>';
    $html.=chr(13).'      <tr valign="top">';
    $html.=chr(13).'          <td><font size="1">Nome:<br><b>'.f($RS,'nome').' </b></td>';
    $html.=chr(13).'          <td><font size="1">Nome resumido:<br><b>'.f($RS,'nome_resumido').' </b></td>';
    $html.=chr(13).'          <td><font size="1">Data nascimento:<br><b>'.FormataDataEdicao(f($RS,'nascimento')).' </b></td>';
    $html.=chr(13).'      <tr valign="top">';
    $html.=chr(13).'          <td><font size="1">Sexo:<br><b>'.f($RS,'nm_sexo').' </b></td>';
    $html.=chr(13).'          <td><font size="1">Estado civil:<br><b>'.f($RS,'nm_estado_civil').' </b></td>';
    if (nvl(f($RS,'sq_siw_arquivo'),'nulo')!='nulo' && $P2==0) {
      $html.=chr(13).'          <td rowspan=3><font size="1">'.LinkArquivo('HL',$w_cliente,f($RS,'sq_siw_arquivo'),'_blank',null,'<img title="clique para ver em tamanho original." border=1 width=100 length=80 src="'.LinkArquivo(null,$w_cliente,f($RS,'sq_siw_arquivo'),null,null,null,'EMBED').'">',null).'</td>';
    } else {
      $html.=chr(13).'          <td rowspan=3><font size="1"></td>';
    } 
    $html.=chr(13).'      <tr valign="top">';
    $html.=chr(13).'          <td><font size="1">Forma��o acad�mica:<br><b>'.f($RS,'nm_formacao').' </b></td>';
    $html.=chr(13).'          <td><font size="1">&nbsp;</td>';
    $html.=chr(13).'      <tr><td colspan=2><font size="1">&nbsp;</td>';
    $html.=chr(13).'      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Local de nascimento</td>';
    $html.=chr(13).'      <tr valign="top">';
    $html.=chr(13).'          <td><font size="1">Pa�s:<br><b>'.f($RS,'nm_pais_nascimento').' </b></td>';
    $html.=chr(13).'          <td><font size="1">Estado:<br><b>'.f($RS,'nm_uf_nascimento').' </b></td>';
    $html.=chr(13).'          <td><font size="1">Cidade:<br><b>'.f($RS,'nm_cidade_nascimento').' </b></td>';
    $html.=chr(13).'      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Documenta��o</td>';
    $html.=chr(13).'      <tr valign="top">';
    $html.=chr(13).'          <td><font size="1">Identidade:<br><b>'.f($RS,'rg_numero').' </b></td>';
    $html.=chr(13).'          <td><font size="1">Emissor:<br><b>'.f($RS,'rg_emissor').' </b></td>';
    $html.=chr(13).'          <td><font size="1">Data de emiss�o:<br><b>'.FormataDataEdicao(f($RS,'rg_emissao')).' </b></td>';
    $html.=chr(13).'      <tr valign="top">';
    $html.=chr(13).'          <td><font size="1">CPF:<br><b>'.f($RS,'cpf').'</b></td>';
    $html.=chr(13).'          <td><font size="1">Passaporte:<br><b>'.Nvl(f($RS,'passaporte_numero'),'---').' </b></td>';
    $html.=chr(13).'          <td valign="top"><font size="1">Pa�s emissor:<br><b>'.Nvl(f($RS,'nm_pais_passaporte'),'---').' </b></td>';
    $html.=chr(13).'          </table>';
    // Telefones
    $RS = db_getFoneList::getInstanceOf($dbms,$p_usuario,null,null,null);
    $RS = SortArray($RS,'tipo_telefone','asc','numero','asc');
    $html.=chr(13).'      <tr><td valign="top" colspan="3"><font size="1">&nbsp;</td>';
    $html.=chr(13).'      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Telefones</td>';
    $html.=chr(13).'<tr><td align="center" colspan=3>';
    $html.=chr(13).'    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
    $html.=chr(13).'          <td><font size="1"><b>Tipo</font></td>';
    $html.=chr(13).'          <td><font size="1"><b>DDD</font></td>';
    $html.=chr(13).'          <td><font size="1"><b>N�mero</font></td>';
    $html.=chr(13).'          <td><font size="1"><b>Padr�o</font></td>';
    $html.=chr(13).'        </tr>';
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      $html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><font size="1"><b>N�o foram encontrados registros.</b></td></tr>';
    } else {
      // Lista os registros selecionados para listagem
      $w_cor=$conTrBgColor;
      foreach ($RS as $row) { 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $html.=chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top">';
        $html.=chr(13).'        <td><font size="1">'.f($row,'tipo_telefone').'</td>';
        $html.=chr(13).'        <td align="center"><font size="1">'.f($row,'ddd').'</td>';
        $html.=chr(13).'        <td><font size="1">'.f($row,'numero').'</td>';
        $html.=chr(13).'        <td align="center"><font size="1">'.f($row,'padrao').'</td>';
        $html.=chr(13).'      </tr>';
      } 
    } 
    $html.=chr(13).'      </center>';
    $html.=chr(13).'    </table>';
    $html.=chr(13).'  </td>';
    $html.=chr(13).'</tr>';
    //Endere�os de e-mail e internet
    $RS = db_getAddressList::getInstanceOf($dbms,$p_usuario,null,'EMAILINTERNET',null);
    $RS = SortArray($RS,'tipo_endereco', 'endereco';
    $html.=chr(13).'      <tr><td valign="top" colspan="3"><font size="1">&nbsp;</td>';
    $html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Endere�os de e-Mail e Internet</td>';
    $html.=chr(13).'      <tr><td align="center" colspan="2">';
    $html.=chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $html.=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
    $html.=chr(13).'            <td><font size="1"><b>Endere�o</font></td>';
    $html.=chr(13).'            <td><font size="1"><b>Padr�o</font></td>';
    $html.=chr(13).'          </tr>';
    if (count($RS)<=0)  {
      $html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=2 align="center"><font size="1"><b>N�o foi informado nenhum endere�o de e-Mail ou Internet.</b></td></tr>';
    } else {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      foreach ($RS as $row) {
        $html.=chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top">';
        if (f($row,'email')=='S') {
          $html.=chr(13).'        <td><font size="1"><a href="mailto:'.f($row,'logradouro').'">'.f($row,'logradouro').'</a></td>';
        } else {
          $html.=chr(13).'        <td><font size="1"><a href="://'.str_replace('://','',f($row,'logradouro')).'" target="_blank">'.f($row,'logradouro').'</a></td>';
        } 
        $html.=chr(13).'        <td align="center"><font size="1">'.f($row,'padrao').'</td>';
        $html.=chr(13).'      </tr>';
      } 
    } 
    $html.=chr(13).'         </table></td></tr>';
    //Endere�os f�sicos
    $html.=chr(13).'      <tr><td valign="top" colspan="3"><font size="1">&nbsp;</td>';
    $RS = db_getAddressList::getInstanceOf($dbms,$p_usuario,null,'FISICO',null);
    $RS = SortArray($RS,'tipo_endereco','asc','endereco','asc');
    $html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Endere�os F�sicos</td>';
    if (count($RS)<=0) {
      $html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><font size="1"><b>N�o foi encontrado nenhum endere�o.</b></td></tr>';
    } else {
      $html.=chr(13).'      <tr><td align="center" colspan="2"><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">';
      foreach ($RS as $row) {
        $html.=chr(13).'          <tr><td colspan=4><font size="1"><b>'.f($row,'tipo_endereco').'</font></td>';
        $html.=chr(13).'          <tr><td width="5%"><td colspan=3><font size="1">Logradouro:<br><b>'.f($row,'logradouro').'</font></td></tr>';
        $html.=chr(13).'          <tr valign="top"><td>';
        $html.=chr(13).'              <td valign="top"><font size="1">Complemento:<br><b>'.Nvl(f($row,'complemento'),'---').' </b></td>';
        $html.=chr(13).'              <td valign="top"><font size="1">Bairro:<br><b>'.f($row,'bairro').' </b></td>';
        $html.=chr(13).'              <td valign="top"><font size="1">CEP:<br><b>'.f($row,'cep').' </b></td>';
        $html.=chr(13).'          <tr valign="top"><td>';
        $html.=chr(13).'              <td valign="top" colspan=2><font size="1">Cidade:<br><b>'.f($row,'cidade').' </b></td>';
        $html.=chr(13).'              <td valign="top"><font size="1">Pa�s:<br><b>'.f($row,'nm_pais').' </b></td>';
        $html.=chr(13).'          <tr><td><td colspan=3><font size="1">Padr�o?<br><b>'.f($row,'padrao').'</font></td></tr>';
        $html.=chr(13).'          <tr><td colspan="4"><hr>';
      } 
      $html.=chr(13).'          </table></td></tr>';
    } 
    // Escolaridade
    $RS = db_getCVAcadForm::getInstanceOf($dbms,$p_usuario,null,'ACADEMICA');
    $RS = SortArray($RS,'ordem','desc','inicio','desc');
    $html.=chr(13).'      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Escolaridade</td>';
    $html.=chr(13).'<tr><td align="center" colspan=3>';
    $html.=chr(13).'    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
    $html.=chr(13).'          <td><font size="1"><b>N�vel</font></td>';
    $html.=chr(13).'          <td><font size="1"><b>�rea</font></td>';
    $html.=chr(13).'          <td><font size="1"><b>Institui��o</font></td>';
    $html.=chr(13).'          <td><font size="1"><b>Curso</font></td>';
    $html.=chr(13).'          <td><font size="1"><b>In�cio</font></td>';
    $html.=chr(13).'          <td><font size="1"><b>T�rmino</font></td>';
    $html.=chr(13).'        </tr>';
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      $html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><font size="1"><b>N�o foram encontrados registros.</b></td></tr>';
    } else {
      // Lista os registros selecionados para listagem
      $w_cor = $conTrBgColor;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;  
        $html.=chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top">';
        $html.=chr(13).'        <td><font size="1">'.f($row,'nm_formacao').'</td>';
        $html.=chr(13).'        <td><font size="1">'.Nvl(f($row,'nm_area'),'---').'</td>';
        $html.=chr(13).'        <td><font size="1">'.Nvl(f($row,'instituicao'),'---').'</td>';
        $html.=chr(13).'        <td><font size="1">'.Nvl(f($row,'nome'),'---').'</td>';
        $html.=chr(13).'        <td align="center"><font size="1">'.f($row,'inicio').'</td>';
        $html.=chr(13).'        <td align="center"><font size="1">'.Nvl(f($row,'fim'),'---').'</td>';
        $html.=chr(13).'      </tr>';
      } 
    }   
    $html.=chr(13).'      </center>';
    $html.=chr(13).'    </table>';
    $html.=chr(13).'  </td>';
    $html.=chr(13).'</tr>'; 
    // Extens�o acad�mica     
    $RS = db_getCVAcadForm::getInstanceOf($dbms,$p_usuario,null,'CURSO');
    $RS = SortArray($RS,'ordem','desc','carga_horaria','desc');
    $html.=chr(13).'      <tr><td valign="top" colspan="3"><font size="1">&nbsp;</td>';
    $html.=chr(13).'      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Extens�o acad�mica</td>';
    $html.=chr(13).'<tr><td align="center" colspan=3>';
    $html.=chr(13).'    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
    $html.=chr(13).'          <td><font size="1"><b>N�vel</font></td>';
    $html.=chr(13).'          <td><font size="1"><b>�rea</font></td>';
    $html.=chr(13).'          <td><font size="1"><b>Institui��o</font></td>';
    $html.=chr(13).'          <td><font size="1"><b>Curso</font></td>';
    $html.=chr(13).'          <td><font size="1"><b>C.H.</font></td>';
    $html.=chr(13).'          <td><font size="1"><b>Conclus�o</font></td>';
    $html.=chr(13).'        </tr>';
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      $html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><font size="1"><b>N�o foram encontrados registros.</b></td></tr>';
    } else {
      // Lista os registros selecionados para listagem
      $w_cor=$conTrBgColor;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;  
        $html.=chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top">';
        $html.=chr(13).'        <td><font size="1">'.f($row,'nm_formacao').'</td>';
        $html.=chr(13).'        <td><font size="1">'.f($row,'nm_area').'</td>';
        $html.=chr(13).'        <td><font size="1">'.f($row,'instituicao').'</td>';
        $html.=chr(13).'        <td><font size="1">'.f($row,'nome').'</td>';
        $html.=chr(13).'        <td align="center"><font size="1">'.f($row,'carga_horaria').'</td>';
        $html.=chr(13).'        <td align="center"><font size="1">'.Nvl(FormataDataEdicao(f($row,'conclusao')),'---').'</td>';
        $html.=chr(13).'      </tr>';
      } 
    } 
    $html.=chr(13).'      </center>';
    $html.=chr(13).'    </table>';
    $html.=chr(13).'  </td>';
    $html.=chr(13).'</tr>';
    // Produ��o t�cnica
    $RS = db_getCVAcadForm::getInstanceOf($dbms,$p_usuario,null,'PRODUCAO');
    $RS = SortArray($RS,'ordem','desc','data','desc');
    $html.=chr(13).'      <tr><td valign="top" colspan="3"><font size="1">&nbsp;</td>';
    $html.=chr(13).'      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Produ��o t�cnica</td>';
    $html.=chr(13).'<tr><td align="center" colspan=3>';
    $html.=chr(13).'    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
    $html.=chr(13).'          <td><font size="1"><b>Tipo</font></td>';
    $html.=chr(13).'          <td><font size="1"><b>�rea</font></td>';
    $html.=chr(13).'          <td><font size="1"><b>Nome</font></td>';
    $html.=chr(13).'          <td><font size="1"><b>Meio</font></td>';
    $html.=chr(13).'          <td><font size="1"><b>Data</font></td>';
    $html.=chr(13).'        </tr>';
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem 
      $html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><font size="1"><b>N�o foram encontrados registros.</b></td></tr>';
    } else {
      // Lista os registros selecionados para listagem
      $w_cor=$conTrBgColor;
      foreach ($RS as $row)<=0) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $html.=chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top">';
        $html.=chr(13).'        <td><font size="1">'.f($row,'nm_formacao').'</td>';
        $html.=chr(13).'        <td><font size="1">'.f($row,'nm_area').'</td>';
        $html.=chr(13).'        <td><font size="1">'.f($row,'nome').'</td>';
        $html.=chr(13).'        <td><font size="1">'.f($row,'meio').'</td>';
        $html.=chr(13).'        <td align="center"><font size="1">'.f($row,'data').'</td>';
        $html.=chr(13).'      </tr>';
      } 
    } 
    $html.=chr(13).'      </center>';
    $html.=chr(13).'    </table>';
    $html.=chr(13).'  </td>';
    $html.=chr(13).'</tr>';
    // Idiomas
    $RS = db_getCVIdioma::getInstanceOf($dbms,$p_usuario,null);
    $RS = SortArray($RS,'nome';
    $html.=chr(13).'      <tr><td valign="top" colspan="3"><font size="1">&nbsp;</td>';
    $html.=chr(13).'      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Idiomas</td>';
    $html.=chr(13).'<tr><td align="center" colspan=3>';
    $html.=chr(13).'    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
    $html.=chr(13).'          <td><font size="1"><b>Idioma</font></td>';
    $html.=chr(13).'          <td><font size="1"><b>Leitura</font></td>';
    $html.=chr(13).'          <td><font size="1"><b>Escrita</font></td>';
    $html.=chr(13).'          <td><font size="1"><b>Conversa��o</font></td>';
    $html.=chr(13).'          <td><font size="1"><b>Compreens�o</font></td>';
    $html.=chr(13).'        </tr>';
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      $html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><font size="1"><b>N�o foram encontrados registros.</b></td></tr>';
    } else {
      // Lista os registros selecionados para listagem
      $w_cor=$conTrBgColor;
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $html.=chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top">';
        $html.=chr(13).'        <td><font size="1">'.f($row,'nome').'</td>';
        $html.=chr(13).'        <td align="center"><font size="1">'.f($row,'nm_leitura').'</td>';
        $html.=chr(13).'        <td align="center"><font size="1">'.f($row,'nm_escrita').'</td>';
        $html.=chr(13).'        <td align="center"><font size="1">'.f($row,'nm_conversacao').'</td>';
        $html.=chr(13).'        <td align="center"><font size="1">'.f($row,'nm_compreensao').'</td>';
        $html.=chr(13).'      </tr>';
      } 
    }  
    $html.=chr(13).'      </center>';
    $html.=chr(13).'    </table>';
    $html.=chr(13).'  </td>';
    $html.=chr(13).'</tr>';
    // Experiencia profissional
    $html.=chr(13).'      <tr><td valign="top" colspan="3"><font size="1">&nbsp;</td>';
    $html.=chr(13).'      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Experi�ncia Profissional</td>';
    $RS = db_getCVAcadForm::getInstanceOf($dbms,$p_usuario,null,'EXPERIENCIA');
    $RS = SortArray($RS,'entrada','desc');
    $html.=chr(13).'      <tr><td align="center" colspan="3">';
    $html.=chr(13).'        <TABLE WIDTH="99%" border="0">';
    if (count($RS)<=0) {
      $html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan="3" align="center"><font size="1"><b>N�o foi informada nenhuma experi�ncia profissional.</b></td></tr>';
    } else {
      foreach ($RS as $row) {
        $html.=chr(13).'          <tr> ';
        $html.=chr(13).'          <tr><td valign="top"><font size="1">Empregador:<br><b>'.f($row,'empregador').'</b></td>';
        $html.=chr(13).'              <td valign="top"><font size="1">�rea de conhecimento:<br><b>'.f($row,'nm_area').'</b></td></tr>';
        $html.=chr(13).'          <tr> ';
        $html.=chr(13).'          <tr><td valign="top"><font size="1">Entrada: <br><b>'.FormataDataEdicao(f($row,'entrada')).'</b></td>';
        $html.=chr(13).'              <td valign="top"><font size="1">Saida: <br><b>'.Nvl(FormataDataEdicao(f($row,'saida')),'---').'</b></td>';
        $html.=chr(13).'          <tr> ';
        $html.=chr(13).'          <tr><td valign="top"><font size="1">Motivo sa�da: <br><b>'.Nvl(f($row,'motivo_saida'),'---').'</b></td></tr>';
        $html.=chr(13).'          <tr> ';
        $html.=chr(13).'          <tr><td valign="top"><font size="1">Pa�s: <br><b>'.f($row,'nm_pais').'</b></td>';
        $html.=chr(13).'              <td valign="top"><font size="1">Estado: <br><b>'.f($row,'nm_estado').'</b></td>';
        $html.=chr(13).'              <td valign="top"><font size="1">Cidade: <br><b>'.f($row,'nm_cidade').'</b></td></tr>';
        $html.=chr(13).'          <tr> ';
        $html.=chr(13).'          <tr><td valign="top" colspan=3><font size="1">Principal atividade desempenhada: <br><b>'.f($row,'ds_tipo_posto').'</b></td></tr>';
        $html.=chr(13).'          <tr> ';
        $html.=chr(13).'          <tr><td valign="top" colspan=3><font size="1">Atividades desempenhadas: <br><b>'.f($row,'atividades').'</b></td></tr>';
        // Cargos da experi�ncia profissional
        $RS1 = db_getCVAcadForm::getInstanceOf($dbms,f($row,'sq_cvpesexp'),null,'CARGO');
        if (count($RS1)>0) {
          $html.=chr(13).'      <tr><td valign="top"><font size="1">Cargos:<br></td></tr>';
          $html.=chr(13).'      <tr><td align="center" colspan="3">';
          $html.=chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
          $html.=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
          $html.=chr(13).'            <td><font size="1"><b>�rea</font></td>';
          $html.=chr(13).'            <td><font size="1"><b>Especialidades</font></td>';
          $html.=chr(13).'            <td><font size="1"><b>In�cio</font></td>';
          $html.=chr(13).'            <td><font size="1"><b>Fim</font></td>';
          $html.=chr(13).'          </tr>';
          foreach ($RS1 as $row1) {
            $html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
            $html.=chr(13).'        <td><font size="1">'.f($row1,'nm_area').'</td>';
            $html.=chr(13).'        <td><font size="1">'.f($row1,'especialidades').'</td>';
            $html.=chr(13).'        <td align="center"><font size="1">'.FormataDataEdicao(f($row1,'inicio')).'</td>';
            $html.=chr(13).'        <td align="center"><font size="1">'.Nvl(FormataDataEdicao(f($row1,'fim')),'---').'</td>';
            $html.=chr(13).'      </tr>';
          } 
          $html.=chr(13).'         </table></td></tr>';
        } 
        $html.=chr(13).'          <tr><td colspan="3"><hr>';
      } 
    } 
    $html.=chr(13).'         </table></td></tr>';
    $html.=chr(13).'</table>';
  } else {
      ScriptOpen('JavaScript');
      $html.=chr(13).' alert(\'Op��o n�o dispon�vel\');';
      $html.=chr(13).' history.back(1);';
      ScriptClose();
  } 
  ShowHTML(''.$HTML);
  return $function_ret;
} 
?>
