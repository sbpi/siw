<?
// =========================================================================
// Rotina de visualiza��o do curr�culo
// -------------------------------------------------------------------------
function visualCurriculo($p_cliente,$p_usuario,$O) {
  extract($GLOBALS);
  if ($O=='L') {
    // Se for listagem dos dados
    // Identifica��o pessoal
    $RS = db_getCV::getInstanceOf($dbms,$p_cliente,$p_usuario,'CVIDENT','DADOS');
    foreach ($RS as $row) {$RS=$row; break;}
    if (Nvl(f($RS,'inclusao'),'')=='') {
      $html = '<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Curriculum n�o informado.</b><br><br><br><br><br><br><br><br><br><br></center></div>';
    } else {
      $w_nome   = f($RS,'nome');
      $html = '<div align=center><center>';
      $html.= chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
      $html.= chr(13).'<tr bgcolor="'.$conTrBgColor.'"><td align="center">';
      $html.= chr(13).'    <table width="99%" border="0">';
      $html.= chr(13).'      <tr><td align="center" colspan="3"><font size=5><b>'.f($RS,'nome').'</b></font></td></tr>';
      $html.= chr(13).'      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Identifica��o</td>';
      $html.= chr(13).'      <tr valign="top">';
      $html.= chr(13).'          <td>Nome:<br><b>'.f($RS,'nome').' </b></td>';
      $html.= chr(13).'          <td>Nome resumido:<br><b>'.f($RS,'nome_resumido').' </b></td>';
      $html.= chr(13).'          <td>Data nascimento:<br><b>'.FormataDataEdicao(f($RS,'nascimento')).' </b></td>';
      $html.= chr(13).'      <tr valign="top">';
      $html.= chr(13).'          <td>Sexo:<br><b>'.f($RS,'nm_sexo').' </b></td>';
      $html.= chr(13).'          <td>Estado civil:<br><b>'.f($RS,'nm_estado_civil').' </b></td>';
      if (nvl(f($RS,'sq_siw_arquivo'),'nulo')!='nulo' && $P2==0) {
        $html.=chr(13).'          <td rowspan=3>'.LinkArquivo('HL',$w_cliente,f($RS,'sq_siw_arquivo'),'_blank',null,'<img title="clique para ver em tamanho original." border=1 width=100 length=80 src="'.LinkArquivo(null,$w_cliente,f($RS,'sq_siw_arquivo'),null,null,null,'EMBED').'">',null).'</td>';
      } else {
        $html.=chr(13).'          <td rowspan=3></td>';
      } 
      $html.=chr(13).'      <tr valign="top">';
      $html.=chr(13).'          <td>Forma��o acad�mica:<br><b>'.f($RS,'nm_formacao').' </b></td>';
      $html.=chr(13).'          <td>Etnia:<br><b>'.f($RS,'nm_etnia').' </b></td>';
      $html.=chr(13).'      <tr><td colspan=2>Defici�ncia:<br><b>'.Nvl(f($RS,'nm_deficiencia'),'---').' </b></td>';
      $html.=chr(13).'      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Local de nascimento</td>';
      $html.=chr(13).'      <tr valign="top">';
      $html.=chr(13).'          <td>Pa�s:<br><b>'.f($RS,'nm_pais_nascimento').' </b></td>';
      $html.=chr(13).'          <td>Estado:<br><b>'.f($RS,'nm_uf_nascimento').' </b></td>';
      $html.=chr(13).'          <td>Cidade:<br><b>'.f($RS,'nm_cidade_nascimento').' </b></td>';
      $html.=chr(13).'      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Documenta��o</td>';
      $html.=chr(13).'      <tr valign="top">';
      $html.=chr(13).'          <td>Identidade:<br><b>'.f($RS,'rg_numero').' </b></td>';
      $html.=chr(13).'          <td>Emissor:<br><b>'.f($RS,'rg_emissor').' </b></td>';
      $html.=chr(13).'          <td>Data de emiss�o:<br><b>'.FormataDataEdicao(f($RS,'rg_emissao')).' </b></td>';
      $html.=chr(13).'      <tr valign="top">';
      $html.=chr(13).'          <td>CPF:<br><b>'.f($RS,'cpf').'</b></td>';
      $html.=chr(13).'          <td>Passaporte:<br><b>'.Nvl(f($RS,'passaporte_numero'),'---').' </b></td>';
      $html.=chr(13).'          <td valign="top">Pa�s emissor:<br><b>'.Nvl(f($RS,'nm_pais_passaporte'),'---').' </b></td>';
      $html.=chr(13).'          </table>';
      // Hist�rico Pessoal
      $html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Hist�rico Pessoal</td>';
      $html.=chr(13).'      <tr><td valign="top" colspan="2"><TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $html.=chr(13).'      <tr><td valign="top" width="80%">Voc� j� fixou resid�ncia permanente legal em pa�s estrangeiro?</td><td valign="top"><b>'.f($RS,'nm_residencia').' </b></td>';
      $html.=chr(13).'      <tr><td valign="top">Voc� j� tomou alguma medida para mudar de nacionalidade?</td><td valign="top"><b>'.f($RS,'nm_mudanca');
      if (f($RS,'mudanca_nacionalidade')=='S') {
        $html.=chr(13).'                          , '.f($RS,'mudanca_nacionalidade_medida').'</b></td>';
      } 
      $html.=chr(13).'      <tr><td valign="top">Voc� aceitaria um emprego por menos de 6 meses?</td><td valign="top"><b>'.f($RS,'nm_emprego').' </b></td>';
      $html.=chr(13).'      <tr><td valign="top">Voc� possui algum impedimento para efetuar viagens a�reas?</td><td valign="top"><b>'.f($RS,'nm_impedimento').' </b></td>';
      $html.=chr(13).'      <tr><td valign="top">Voc� tem algum parente trabalhando nesta organiza��o?</td><td valign="top"><b>'.f($RS,'nm_familiar').' </b></td>';
      $html.=chr(13).'      <tr><td valign="top">Voc� tem alguma obje��o a fazer em rela��o � solicita��o de informa��es a seu respeito para seu �ltimo empregador?</td><td valign="top"><b>'.f($RS,'nm_objecao').' </b></td>';
      $html.=chr(13).'      <tr><td valign="top">Voc� alguma vez j� foi preso, acusado ou convocado pela Corte como r�u em algum processo criminal ou sentenciado, penalizado ou aprisionado por viola��o de alguma lei? (excluem-se viola��es menores de tr�nsito)</td><td valign="top"><b>'.f($RS,'nm_prisao');
      if (f($RS,'prisao_envolv_justica')=='S') {
        $html.=chr(13).'                          , '.f($RS,'motivo_prisao').'</b></td>';
      } 
      $html.=chr(13).'      <tr><td valign="top">Exponha algum outo fato relevante. Inclua informa��es relacionadas a qualquer resid�ncia fora do pa�s de origem:</td><td valign="top"><b>'.Nvl(f($RS,'fato_relevante_vida'),'---').' </b></td>';
      $html.=chr(13).'      <tr><td valign="top">Voc� � ou foi Funcion�rio P�blico?</td><td valign="top"><b>'.f($RS,'nm_servidor');
      if (f($RS,'servidor_publico')=='S') {
        $html.=chr(13).'                          , de '.FormataDataEdicao(f($RS,'servico_publico_inicio')).' a '.FormataDataEdicao(f($RS,'servico_publico_fim')).'</b></td>';
      } 
      $html.=chr(13).'      <tr><td valign="top">Sociedade profissional ou atividades ligadas a assuntos c�vicos, p�blicos ou internacionais das quais faz parte:</td><td valign="top"><b>'.Nvl(f($RS,'atividades_civicas'),'---').' </b></td>';
      $html.=chr(13).'          </table>';
      // Telefones
      $RS = db_getFoneList::getInstanceOf($dbms,$p_usuario,null,null,null);
      $RS = SortArray($RS,'tipo_telefone','asc','numero','asc');
      $html.=chr(13).'      <tr><td valign="top" colspan="3">&nbsp;</td>';
      $html.=chr(13).'      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Telefones</td>';
      $html.=chr(13).'<tr><td align="center" colspan=3>';
      $html.=chr(13).'    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $html.=chr(13).'          <td><b>Tipo</td>';
      $html.=chr(13).'          <td><b>DDD</td>';
      $html.=chr(13).'          <td><b>N�mero</td>';
      $html.=chr(13).'          <td><b>Padr�o</td>';
      $html.=chr(13).'        </tr>';
      if (count($RS)<=0) {
        // Se n�o foram selecionados registros, exibe mensagem
        $html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>N�o foram encontrados registros.</b></td></tr>';
      } else {
        // Lista os registros selecionados para listagem
        $w_cor=$conTrBgColor;
        foreach ($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor; 
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          $html.=chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top">';
          $html.=chr(13).'        <td>'.f($row,'tipo_telefone').'</td>';
          $html.=chr(13).'        <td align="center">'.f($row,'ddd').'</td>';
          $html.=chr(13).'        <td>'.f($row,'numero').'</td>';
          $html.=chr(13).'        <td align="center">'.f($row,'padrao').'</td>';
          $html.=chr(13).'      </tr>';
        } 
      } 
      $html.=chr(13).'      </center>';
      $html.=chr(13).'    </table>';
      $html.=chr(13).'  </td>';
      $html.=chr(13).'</tr>';
      //Endere�os de e-mail e internet
      $RS = db_getAddressList::getInstanceOf($dbms,$p_usuario,null,'EMAILINTERNET',null);
      $RS = SortArray($RS,'tipo_endereco','asc', 'endereco','asc');
      $html.=chr(13).'      <tr><td valign="top" colspan="3">&nbsp;</td>';
      $html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Endere�os de e-Mail e Internet</td>';
      $html.=chr(13).'      <tr><td align="center" colspan="2">';
      $html.=chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $html.=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $html.=chr(13).'            <td><b>Endere�o</td>';
      $html.=chr(13).'            <td><b>Padr�o</td>';
      $html.=chr(13).'          </tr>';
      if (count($RS)<=0) {
        $html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=2 align="center"><b>N�o foi informado nenhum endere�o de e-Mail ou Internet.</b></td></tr>';
      } else {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;  
        foreach ($RS as $row) {
          $html.=chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top">';
            if (f($RS,'email')=='S') {
              $html.=chr(13).'        <td><a href="mailto:'.f($RS,'logradouro').'">'.f($RS,'logradouro').'</a></td>';
            } else {
              $html.=chr(13).'        <td><a href="://'.str_replace('://','',f($RS,'logradouro')).'" target="_blank">'.f($RS,'logradouro').'</a></td>';
            } 
            $html.=chr(13).'        <td align="center">'.f($RS,'padrao').'</td>';
            $html.=chr(13).'      </tr>';
        } 
      } 
      $html.=chr(13).'         </table></td></tr>';
      //Endere�os f�sicos
      $html.=chr(13).'      <tr><td valign="top" colspan="3">&nbsp;</td>';
      $RS = db_getAddressList::getInstanceOf($dbms,$p_usuario,null,'FISICO',null);
      $RS = SortArray($RS,'endereco','asc');
      $html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Endere�os F�sicos</td>';
      if (count($RS)<=0) {
          $html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td valign="top" colspan="2" align="center"><b>N�o foi encontrado nenhum endere�o.</b></td></tr>';
      } else {
        $html.=chr(13).'      <tr><td align="center" colspan="2"><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">';
        foreach($RS as $row) {
          $html.=chr(13).'          <tr><td colspan=4><b>'.f($row,'tipo_endereco').'</td>';
          $html.=chr(13).'          <tr><td width="5%"><td colspan=3>Logradouro:<br><b>'.f($row,'logradouro').'</td></tr>';
          $html.=chr(13).'          <tr valign="top"><td>';
          $html.=chr(13).'              <td valign="top">Complemento:<br><b>'.Nvl(f($row,'complemento'),'---').' </b></td>';
          $html.=chr(13).'              <td valign="top">Bairro:<br><b>'.f($row,'bairro').' </b></td>';
          $html.=chr(13).'              <td valign="top">CEP:<br><b>'.f($row,'cep').' </b></td>';
          $html.=chr(13).'          <tr valign="top"><td>';
          $html.=chr(13).'              <td valign="top" colspan=2>Cidade:<br><b>'.f($row,'cidade').' </b></td>';
          $html.=chr(13).'              <td valign="top">Pa�s:<br><b>'.f($row,'nm_pais').' </b></td>';
          $html.=chr(13).'          <tr><td><td colspan=3>Padr�o?<br><b>'.f($row,'padrao').'</td></tr>';
          $html.=chr(13).'          <tr><td colspan="4"><hr>';
        } 
        $html.=chr(13).'          </table></td></tr>';
      } 
      // Escolaridade
      $RS = db_getCVAcadForm::getInstanceOf($dbms,$p_usuario,null,'ACADEMICA');
      $RS = SortArray($RS,'ordem','desc', 'inicio','desc');
      $html.=chr(13).'      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Escolaridade</td>';
      $html.=chr(13).'<tr><td align="center" colspan=3>';
      $html.=chr(13).'    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $html.=chr(13).'          <td><b>N�vel</td>';
      $html.=chr(13).'          <td><b>�rea</td>';
      $html.=chr(13).'          <td><b>Institui��o</td>';
      $html.=chr(13).'          <td><b>Curso</td>';
      $html.=chr(13).'          <td><b>In�cio</td>';
      $html.=chr(13).'          <td><b>T�rmino</td>';
      $html.=chr(13).'        </tr>';
      if (count($RS)<=0) {
        // Se n�o foram selecionados registros, exibe mensagem
        $html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>N�o foram encontrados registros.</b></td></tr>';
      } else {
        // Lista os registros selecionados para listagem
        $w_cor = $conTrBgColor;
        foreach($RS as $row) {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;  
          $html.=chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top">';
          $html.=chr(13).'        <td>'.f($row,'nm_formacao').'</td>';
          $html.=chr(13).'        <td>'.Nvl(f($row,'nm_area'),'---').'</td>';
          $html.=chr(13).'        <td>'.Nvl(f($row,'instituicao'),'---').'</td>';
          $html.=chr(13).'        <td>'.Nvl(f($row,'nome'),'---').'</td>';
          $html.=chr(13).'        <td align="center">'.f($row,'inicio').'</td>';
          $html.=chr(13).'        <td align="center">'.Nvl(f($row,'fim'),'---').'</td>';
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
      $html.=chr(13).'      <tr><td valign="top" colspan="3">&nbsp;</td>';
      $html.=chr(13).'      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Extens�o acad�mica</td>';
      $html.=chr(13).'<tr><td align="center" colspan=3>';
      $html.=chr(13).'    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $html.=chr(13).'          <td><b>N�vel</td>';
      $html.=chr(13).'          <td><b>�rea</td>';
      $html.=chr(13).'          <td><b>Institui��o</td>';
      $html.=chr(13).'          <td><b>Curso</td>';
      $html.=chr(13).'          <td><b>C.H.</td>';
      $html.=chr(13).'          <td><b>Conclus�o</td>';
      $html.=chr(13).'        </tr>';
      if (count($RS)<=0) {
        // Se n�o foram selecionados registros, exibe mensagem
        $html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>N�o foram encontrados registros.</b></td></tr>';
      } else {
        // Lista os registros selecionados para listagem
        $w_cor=$conTrBgColor;
        foreach ($RS as $row){
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor; 
          $html.=chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top">';
          $html.=chr(13).'        <td>'.f($row,'nm_formacao').'</td>';
          $html.=chr(13).'        <td>'.f($row,'nm_area').'</td>';
          $html.=chr(13).'        <td>'.f($row,'instituicao').'</td>';
          $html.=chr(13).'        <td>'.f($row,'nome').'</td>';
          $html.=chr(13).'        <td align="center">'.f($row,'carga_horaria').'</td>';
          $html.=chr(13).'        <td align="center">'.Nvl(FormataDataEdicao(f($row,'conclusao')),'---').'</td>';
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
      $html.=chr(13).'      <tr><td valign="top" colspan="3">&nbsp;</td>';
      $html.=chr(13).'      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Produ��o t�cnica</td>';
      $html.=chr(13).'<tr><td align="center" colspan=3>';
      $html.=chr(13).'    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $html.=chr(13).'          <td><b>Tipo</td>';
      $html.=chr(13).'          <td><b>�rea</td>';
      $html.=chr(13).'          <td><b>Nome</td>';
      $html.=chr(13).'          <td><b>Meio</td>';
      $html.=chr(13).'          <td><b>Data</td>';
      $html.=chr(13).'        </tr>';
      if (count($RS)<=0) {
        // Se n�o foram selecionados registros, exibe mensagem
        $html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>N�o foram encontrados registros.</b></td></tr>';
      } else {
        // Lista os registros selecionados para listagem
        $w_cor=$conTrBgColor;
        foreach($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor; 
          $html.=chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top">';
          $html.=chr(13).'        <td>'.f($row,'nm_formacao').'</td>';
          $html.=chr(13).'        <td>'.f($row,'nm_area').'</td>';
          $html.=chr(13).'        <td>'.f($row,'nome').'</td>';
          $html.=chr(13).'        <td>'.f($row,'meio').'</td>';
          $html.=chr(13).'        <td align="center">'.f($row,'data').'</td>';
          $html.=chr(13).'      </tr>';
        } 
      } 
      $html.=chr(13).'      </center>';
      $html.=chr(13).'    </table>';
      $html.=chr(13).'  </td>';
      $html.=chr(13).'</tr>';
      // Idiomas
      $RS = db_getCVIdioma::getInstanceOf($dbms,$p_usuario,null);
      $RS = SortArray($RS,'nome','acs');
      $html.=chr(13).'      <tr><td valign="top" colspan="3">&nbsp;</td>';
      $html.=chr(13).'      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Idiomas</td>';
      $html.=chr(13).'<tr><td align="center" colspan=3>';
      $html.=chr(13).'    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $html.=chr(13).'          <td><b>Idioma</td>';
      $html.=chr(13).'          <td><b>Leitura</td>';
      $html.=chr(13).'          <td><b>Escrita</td>';
      $html.=chr(13).'          <td><b>Conversa��o</td>';
      $html.=chr(13).'          <td><b>Compreens�o</td>';
      $html.=chr(13).'        </tr>';
      if (count($RS)<=0) {
        // Se n�o foram selecionados registros, exibe mensagem
        $html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>N�o foram encontrados registros.</b></td></tr>';
      } else {
        // Lista os registros selecionados para listagem
        $w_cor = $conTrBgColor;
        foreach($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;  
          $html.=chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top">';
          $html.=chr(13).'        <td>'.f($row,'nome').'</td>';
          $html.=chr(13).'        <td align="center">'.f($row,'nm_leitura').'</td>';
          $html.=chr(13).'        <td align="center">'.f($row,'nm_escrita').'</td>';
          $html.=chr(13).'        <td align="center">'.f($row,'nm_conversacao').'</td>';
          $html.=chr(13).'        <td align="center">'.f($row,'nm_compreensao').'</td>';
          $html.=chr(13).'      </tr>';
        } 
      } 
      $html.=chr(13).'      </center>';
      $html.=chr(13).'    </table>';
      $html.=chr(13).'  </td>';
      $html.=chr(13).'</tr>';
      // Contas banc�rias
      $RS = db_getContaBancoList::getInstanceOf($dbms,$p_usuario,null,null);
      $RS = SortArray($RS,'tipo_conta','asc','banco','asc','numero','asc');
      $html.=chr(13).'      <tr><td valign="top" colspan="3">&nbsp;</td>';
      $html.=chr(13).'      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Contas banc�rias</td>';
      $html.=chr(13).'<tr><td align="center" colspan=3>';
      $html.=chr(13).'    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $html.=chr(13).'          <td><b>Tipo</td>';
      $html.=chr(13).'          <td><b>Banco</td>';
      $html.=chr(13).'          <td><b>Ag�ncia</td>';
      $html.=chr(13).'          <td><b>Opera��o</td>';
      $html.=chr(13).'          <td><b>Conta</td>';
      $html.=chr(13).'          <td><b>Ativo</td>';
      $html.=chr(13).'          <td><b>Padr�o</td>';
      $html.=chr(13).'        </tr>';
      if (count($RS)<=0) {
        // Se n�o foram selecionados registros, exibe mensagem
        $html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>N�o foram encontrados registros.</b></td></tr>';
      } else {
        // Lista os registros selecionados para listagem
        $w_cor=$conTrBgColor;
        foreach ($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor; 
          $html.=chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top">';
          $html.=chr(13).'        <td>'.f($row,'tipo_conta').'</td>';
          $html.=chr(13).'        <td>'.f($row,'banco').'</td>';
          $html.=chr(13).'        <td>'.f($row,'agencia').'</td>';
          $html.=chr(13).'        <td align="center">'.Nvl(f($row,'operacao'),'---').'</td>';
          $html.=chr(13).'        <td>'.f($row,'numero').'</td>';
          $html.=chr(13).'        <td align="center">'.f($row,'ativo').'</td>';
          $html.=chr(13).'        <td align="center">'.f($row,'padrao').'</td>';
          $html.=chr(13).'      </tr>';
        } 
      }  
      $html.=chr(13).'      </center>';
      $html.=chr(13).'    </table>';
      $html.=chr(13).'  </td>';
      $html.=chr(13).'</tr>';
      // Experiencia profissional
      $html.=chr(13).'      <tr><td valign="top" colspan="3">&nbsp;</td>';
      $html.=chr(13).'      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Experi�ncia Profissional</td>';
      $RS = db_getCVAcadForm::getInstanceOf($dbms,$p_usuario,null,'EXPERIENCIA');
      $RS = SortArray($RS,'entrada','desc');
      $html.=chr(13).'      <tr><td align="center" colspan="3">';
      $html.=chr(13).'        <TABLE WIDTH="99%" border="0">';
      if (count($RS)<=0) {
        $html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan="3" align="center"><b>N�o foi informada nenhuma experi�ncia profissional.</b></td></tr>';
      } else {
        foreach ($RS as $row) {
          $html.=chr(13).'          <tr> ';
          $html.=chr(13).'          <tr><td valign="top">Empregador:<br><b>'.f($row,'empregador').'</b></td>';
          $html.=chr(13).'              <td valign="top">�rea de conhecimento:<br><b>'.f($row,'nm_area').'</b></td></tr>';
          $html.=chr(13).'          <tr> ';
          $html.=chr(13).'          <tr><td valign="top">Entrada: <br><b>'.FormataDataEdicao(f($row,'entrada')).'</b></td>';
          $html.=chr(13).'              <td valign="top">Saida: <br><b>'.Nvl(FormataDataEdicao(f($row,'saida')),'---').'</b></td>';
          $html.=chr(13).'              <td valign="top">�ltimo sal�rio mensal: <br><b>'.number_format(Nvl(f($row,'ultimo_salario'),0),2,',','.').'</b></td></tr>';
          $html.=chr(13).'          <tr> ';
          $html.=chr(13).'          <tr><td valign="top">Motivo sa�da: <br><b>'.Nvl(f($row,'motivo_saida'),'---').'</b></td></tr>';
          $html.=chr(13).'          <tr> ';
          $html.=chr(13).'          <tr><td valign="top">Pa�s: <br><b>'.f($row,'nm_pais').'</b></td>';
          $html.=chr(13).'              <td valign="top">Estado: <br><b>'.f($row,'nm_estado').'</b></td>';
          $html.=chr(13).'              <td valign="top">Cidade: <br><b>'.f($row,'nm_cidade').'</b></td></tr>';
          $html.=chr(13).'          <tr> ';
          $html.=chr(13).'          <tr><td valign="top" colspan=3>Principal atividade desempenhada: <br><b>'.f($row,'ds_tipo_posto').'</b></td></tr>';
          $html.=chr(13).'          <tr> ';
          $html.=chr(13).'          <tr><td valign="top" colspan=3>Atividades desempenhadas: <br><b>'.f($row,'atividades').'</b></td></tr>';
          // Cargos da experi�ncia profissional
          $RS = db_getCVAcadForm::getInstanceOf($dbms,$RS1,f($RS,'sq_cvpesexp'),null,'CARGO');
          if (!count($RS)<=0){
            $html.=chr(13).'      <tr><td valign="top">Cargos:<br></td></tr>';
            $html.=chr(13).'      <tr><td align="center" colspan="3">';
            $html.=chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
            $html.=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
            $html.=chr(13).'            <td><b>�rea</td>';
            $html.=chr(13).'            <td><b>Especialidades</td>';
            $html.=chr(13).'            <td><b>In�cio</td>';
            $html.=chr(13).'            <td><b>Fim</td>';
            $html.=chr(13).'          </tr>';
            foreach ($RS1 as $row) {
              $html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
              $html.=chr(13).'        <td>'.f($RS1,'nm_area').'</td>';
              $html.=chr(13).'        <td>'.f($RS1,'especialidades').'</td>';
              $html.=chr(13).'        <td align="center">'.FormataDataEdicao(f($RS1,'inicio')).'</td>';
              $html.=chr(13).'        <td align="center">'.Nvl(FormataDataEdicao(f($RS1,'fim')),'---').'</td>';
              $html.=chr(13).'      </tr>';
            } 
            $html.=chr(13).'         </table></td></tr>';
          } 
          $html.=chr(13).'          <tr><td colspan="3"><hr>';
        } 
      } 
      $html.=chr(13).'         </table></td></tr>';
      $html.=chr(13).'      <tr><td valign="top" colspan="3">&nbsp;</td>';
      $html.=chr(13).'      <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Declara��o</td>';
      $html.=chr(13).'      <tr><td valign="top" colspan="3"><font size="3"><blockquote><p align="justify"><br>Eu, <b>'.$w_nome.'</b>, declaro que as informa��es aqui constantes est�o atualizadas, s�o verdadeiras e pass�veis de comprova��o.</p><p><br></p><p align="center">'.FormataDataEdicao(time(),3).'</p></blockquote></td>';
      $html.=chr(13).'</table>';
    } 
  } else {
    ScriptOpen('JavaScript');
    $html.=chr(13).' alert(\'Op��o n�o dispon�vel\');';
    $html.=chr(13).' history.back(1);';
    ScriptClose();
  } 
  ShowHTML(''.$html);
} 
?>
