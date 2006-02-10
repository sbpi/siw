<%
REM =========================================================================
REM Rotina de visualiza��o do curr�culo
REM -------------------------------------------------------------------------
Sub VisualCurriculo(p_cliente, p_usuario, O)

  Dim Rsquery, w_erro, w_nome
  Dim w_Imagem
  Dim w_ImagemPadrao
  Dim SQL1, RS1, SQL2, RS2, SQL3, RS3, SQLopcao
  Dim HTML

  If O = "L" Then ' Se for listagem dos dados
  
     ' Identifica��o pessoal
     DB_GetCV RS, p_cliente, p_usuario, "CVIDENT", "DADOS"
     If Nvl(RS("inclusao"),"") = "" Then
       HTML = "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Curriculum n�o informado.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
     Else
        w_nome        = RS("nome")
        HTML = "<div align=center><center>"
        HTML = VbCrLf & HTML &"<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
        HTML = VbCrLf & HTML &"<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
        HTML = VbCrLf & HTML &"    <table width=""99%"" border=""0"">"
        HTML = VbCrLf & HTML &"      <tr><td align=""center"" colspan=""3""><font size=5><b>" & RS("nome") & "</b></font></td></tr>"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Identifica��o</td>"
        HTML = VbCrLf & HTML &"      <tr valign=""top"">"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">Nome:<br><b>" & RS("nome") & " </b></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">Nome resumido:<br><b>" & RS("nome_resumido") & " </b></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">Data nascimento:<br><b>" & FormataDataEdicao(RS("nascimento")) & " </b></td>"
        HTML = VbCrLf & HTML &"      <tr valign=""top"">"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">Sexo:<br><b>" & RS("nm_sexo") & " </b></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">Estado civil:<br><b>" & RS("nm_estado_civil") & " </b></td>"
        If nvl(RS("sq_siw_arquivo"),"nulo") <> "nulo" and P2 = 0 Then
           HTML = VbCrLf & HTML &"          <td rowspan=3><font size=""1"">" & LinkArquivo("HL", w_cliente, RS("sq_siw_arquivo"), "_blank", null, "<img title=""clique para ver em tamanho original."" border=1 width=100 length=80 src=""" & LinkArquivo(null, w_cliente, RS("sq_siw_arquivo"), null, null, null, "EMBED")& """>", null)& "</td>"
        Else
           HTML = VbCrLf & HTML &"          <td rowspan=3><font size=""1""></td>"
        End If
        HTML = VbCrLf & HTML &"      <tr valign=""top"">"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">Forma��o acad�mica:<br><b>" & RS("nm_formacao") & " </b></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">Etnia:<br><b>" & RS("nm_etnia") & " </b></td>"
        HTML = VbCrLf & HTML &"      <tr><td colspan=2><font size=""1"">Defici�ncia:<br><b>" & Nvl(RS("nm_deficiencia"),"---") & " </b></td>"
        
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Local de nascimento</td>"
        HTML = VbCrLf & HTML &"      <tr valign=""top"">"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">Pa�s:<br><b>" & RS("nm_pais_nascimento") & " </b></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">Estado:<br><b>" & RS("nm_uf_nascimento") & " </b></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">Cidade:<br><b>" & RS("nm_cidade_nascimento") & " </b></td>"
        
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Documenta��o</td>"
        HTML = VbCrLf & HTML &"      <tr valign=""top"">"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">Identidade:<br><b>" & RS("rg_numero") & " </b></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">Emissor:<br><b>" & RS("rg_emissor") & " </b></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">Data de emiss�o:<br><b>" & FormataDataEdicao(RS("rg_emissao")) & " </b></td>"
        HTML = VbCrLf & HTML &"      <tr valign=""top"">"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">CPF:<br><b>" & RS("cpf")  & "</b></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">Passaporte:<br><b>" & Nvl(RS("passaporte_numero"),"---") & " </b></td>"
        HTML = VbCrLf & HTML &"          <td valign=""top""><font size=""1"">Pa�s emissor:<br><b>" & Nvl(RS("nm_pais_passaporte"),"---") & " </b></td>"
        HTML = VbCrLf & HTML &"          </table>"

        ' Hist�rico Pessoal
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Hist�rico Pessoal</td>"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""2""><TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" width=""80%""><font size=""1"">Voc� j� fixou resid�ncia permanente legal em pa�s estrangeiro?</td><td valign=""top""><font size=""1""><b>" & RS("nm_residencia") & " </b></td>"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top""><font size=""1"">Voc� j� tomou alguma medida para mudar de nacionalidade?</td><td valign=""top""><font size=""1""><b>" & RS("nm_mudanca")
        If RS("mudanca_nacionalidade") = "S" Then
           HTML = VbCrLf & HTML &"                          , " & RS("mudanca_nacionalidade_medida") & "</b></td>" 
        End If
        HTML = VbCrLf & HTML &"      <tr><td valign=""top""><font size=""1"">Voc� aceitaria um emprego por menos de 6 meses?</td><td valign=""top""><font size=""1""><b>" & RS("nm_emprego") & " </b></td>"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top""><font size=""1"">Voc� possui algum impedimento para efetuar viagens a�reas?</td><td valign=""top""><font size=""1""><b>" & RS("nm_impedimento") & " </b></td>"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top""><font size=""1"">Voc� tem algum parente trabalhando nesta organiza��o?</td><td valign=""top""><font size=""1""><b>" & RS("nm_familiar") & " </b></td>"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top""><font size=""1"">Voc� tem alguma obje��o a fazer em rela��o � solicita��o de informa��es a seu respeito para seu �ltimo empregador?</td><td valign=""top""><font size=""1""><b>" & RS("nm_objecao") & " </b></td>"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top""><font size=""1"">Voc� alguma vez j� foi preso, acusado ou convocado pela Corte como r�u em algum processo criminal ou sentenciado, penalizado ou aprisionado por viola��o de alguma lei? (excluem-se viola��es menores de tr�nsito)</td><td valign=""top""><font size=""1""><b>" & RS("nm_prisao")
        If RS("prisao_envolv_justica") = "S" Then
           HTML = VbCrLf & HTML &"                          , " & RS("motivo_prisao") & "</b></td>" 
        End If
        HTML = VbCrLf & HTML &"      <tr><td valign=""top""><font size=""1"">Exponha algum outo fato relevante. Inclua informa��es relacionadas a qualquer resid�ncia fora do pa�s de origem:</td><td valign=""top""><font size=""1""><b>" & Nvl(RS("fato_relevante_vida"),"---") & " </b></td>"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top""><font size=""1"">Voc� � ou foi Funcion�rio P�blico?</td><td valign=""top""><font size=""1""><b>" & RS("nm_servidor")
        If RS("servidor_publico") = "S" Then
           HTML = VbCrLf & HTML &"                          , de " & FormataDataEdicao(RS("servico_publico_inicio")) & " a " & FormataDataEdicao(RS("servico_publico_fim")) & "</b></td>" 
        End If
        HTML = VbCrLf & HTML &"      <tr><td valign=""top""><font size=""1"">Sociedade profissional ou atividades ligadas a assuntos c�vicos, p�blicos ou internacionais das quais faz parte:</td><td valign=""top""><font size=""1""><b>" & Nvl(RS("atividades_civicas"),"---") & " </b></td>"
        HTML = VbCrLf & HTML &"          </table>"
        DesconectaBD
        
        ' Telefones
        DB_GetFoneList RS, p_usuario, null, null
        RS.Sort = "tipo_telefone, numero"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3""><font size=""1"">&nbsp;</td>"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Telefones</td>"
        HTML = VbCrLf & HTML & "<tr><td align=""center"" colspan=3>"
        HTML = VbCrLf & HTML & "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        HTML = VbCrLf & HTML & "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        HTML = VbCrLf & HTML &"          <td><font size=""1""><b>Tipo</font></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1""><b>DDD</font></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1""><b>N�mero</font></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1""><b>Padr�o</font></td>"
        HTML = VbCrLf & HTML & "        </tr>"
        If RS.EOF Then ' Se n�o foram selecionados registros, exibe mensagem
           HTML = VbCrLf & HTML & "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>N�o foram encontrados registros.</b></td></tr>"
        Else
           ' Lista os registros selecionados para listagem
           w_cor = conTrBgColor
           While Not RS.EOF
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              HTML = VbCrLf & HTML & "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
              HTML = VbCrLf & HTML &"        <td><font size=""1"">" & RS("tipo_telefone") & "</td>"
              HTML = VbCrLf & HTML &"        <td align=""center""><font size=""1"">" & RS("ddd") & "</td>"
              HTML = VbCrLf & HTML &"        <td><font size=""1"">" & RS("numero") & "</td>"
              HTML = VbCrLf & HTML &"        <td align=""center""><font size=""1"">" & RS("padrao") & "</td>"
              HTML = VbCrLf & HTML & "      </tr>"
              RS.MoveNext
           wend
        End If
        HTML = VbCrLf & HTML & "      </center>"
        HTML = VbCrLf & HTML & "    </table>"
        HTML = VbCrLf & HTML & "  </td>"
        HTML = VbCrLf & HTML & "</tr>"
        DesconectaBD
        
        'Endere�os de e-mail e internet
        DB_GetAddressList RS, p_usuario, null, null
        RS.Sort = "tipo_endereco, endereco"
        RS.Filter = "email='S' or internet='S'"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3""><font size=""1"">&nbsp;</td>"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Endere�os de e-Mail e Internet</td>"
        HTML = VbCrLf & HTML &"      <tr><td align=""center"" colspan=""2"">"
        HTML = VbCrLf & HTML &"        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        HTML = VbCrLf & HTML &"          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        HTML = VbCrLf & HTML &"            <td><font size=""1""><b>Endere�o</font></td>"
        HTML = VbCrLf & HTML &"            <td><font size=""1""><b>Padr�o</font></td>"
        HTML = VbCrLf & HTML &"          </tr>"    
        If RS.EOF Then
           HTML = VbCrLf & HTML &"      <tr bgcolor=""" & conTrBgColor & """><td colspan=2 align=""center""><font size=""1""><b>N�o foi informado nenhum endere�o de e-Mail ou Internet.</b></td></tr>"
        Else
           w_cor = conTrBgColor
           While Not RS.EOF
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              HTML = VbCrLf & HTML & "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
             If RS("email") = "S" Then
                HTML = VbCrLf & HTML &"        <td><font size=""1""><a href=""mailto:" & RS("logradouro") & """>" & RS("logradouro") & "</a></td>"
             Else
                HTML = VbCrLf & HTML &"        <td><font size=""1""><a href=""://" & replace(RS("logradouro"),"://","") & """ target=""_blank"">" & RS("logradouro") & "</a></td>"
             End If
             HTML = VbCrLf & HTML &"        <td align=""center""><font size=""1"">" & RS("padrao") & "</td>"
             HTML = VbCrLf & HTML &"      </tr>"
             Rs.MoveNext
           wend
        End If
        DesconectaBD
        HTML = VbCrLf & HTML &"         </table></td></tr>"
        
        'Endere�os f�sicos
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3""><font size=""1"">&nbsp;</td>"
        DB_GetAddressList RS, p_usuario, null, null
        RS.Sort = "tipo_endereco, endereco"
        RS.Filter = "email='N' and internet='N'"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Endere�os F�sicos</td>"
        If RS.EOF Then
           HTML = VbCrLf & HTML &"      <tr bgcolor=""" & conTrBgColor & """><td valign=""top"" colspan=""2"" align=""center""><font size=""1""><b>N�o foi encontrado nenhum endere�o.</b></td></tr>"
        Else
           HTML = VbCrLf & HTML &"      <tr><td align=""center"" colspan=""2""><TABLE WIDTH=""100%"" BORDER=""0"" CELLSPACING=""0"" CELLPADDING=""0"">"
           While Not Rs.EOF
              HTML = VbCrLf & HTML &"          <tr><td colspan=4><font size=""1""><b>" & RS("tipo_endereco") & "</font></td>"
              HTML = VbCrLf & HTML &"          <tr><td width=""5%""><td colspan=3><font size=""1"">Logradouro:<br><b>" & RS("logradouro") & "</font></td></tr>"
              HTML = VbCrLf & HTML &"          <tr valign=""top""><td>"
              HTML = VbCrLf & HTML &"              <td valign=""top""><font size=""1"">Complemento:<br><b>" & Nvl(RS("complemento"),"---") & " </b></td>"
              HTML = VbCrLf & HTML &"              <td valign=""top""><font size=""1"">Bairro:<br><b>" & RS("bairro") & " </b></td>"
              HTML = VbCrLf & HTML &"              <td valign=""top""><font size=""1"">CEP:<br><b>" & RS("cep") & " </b></td>"
              HTML = VbCrLf & HTML &"          <tr valign=""top""><td>"
              HTML = VbCrLf & HTML &"              <td valign=""top"" colspan=2><font size=""1"">Cidade:<br><b>" & RS("cidade") & " </b></td>"
              HTML = VbCrLf & HTML &"              <td valign=""top""><font size=""1"">Pa�s:<br><b>" & RS("nm_pais") & " </b></td>"
              HTML = VbCrLf & HTML &"          <tr><td><td colspan=3><font size=""1"">Padr�o?<br><b>" & RS("padrao") & "</font></td></tr>"
              RS.MoveNext
              HTML = VbCrLf & HTML &"          <tr><td colspan=""4""><hr>"
           wend
           HTML = VbCrLf & HTML &"          </table></td></tr>"
        End If
        DesconectaBD
     
        ' Escolaridade
        DB_GetCVAcadForm RS, p_usuario, null, "ACADEMICA"
        RS.Sort = "ordem desc, inicio desc"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Escolaridade</td>"
        HTML = VbCrLf & HTML & "<tr><td align=""center"" colspan=3>"
        HTML = VbCrLf & HTML & "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        HTML = VbCrLf & HTML & "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        HTML = VbCrLf & HTML & "          <td><font size=""1""><b>N�vel</font></td>"
        HTML = VbCrLf & HTML & "          <td><font size=""1""><b>�rea</font></td>"
        HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Institui��o</font></td>"
        HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Curso</font></td>"
        HTML = VbCrLf & HTML & "          <td><font size=""1""><b>In�cio</font></td>"
        HTML = VbCrLf & HTML & "          <td><font size=""1""><b>T�rmino</font></td>"
        HTML = VbCrLf & HTML & "        </tr>"
        If RS.EOF Then ' Se n�o foram selecionados registros, exibe mensagem
           HTML = VbCrLf & HTML & "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>N�o foram encontrados registros.</b></td></tr>"
        Else
           ' Lista os registros selecionados para listagem
           w_cor = conTrBgColor
           While Not RS.EOF
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              HTML = VbCrLf & HTML & "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
              HTML = VbCrLf & HTML & "        <td><font size=""1"">" & RS("nm_formacao") & "</td>"
              HTML = VbCrLf & HTML & "        <td><font size=""1"">" & Nvl(RS("nm_area"),"---") & "</td>"
              HTML = VbCrLf & HTML & "        <td><font size=""1"">" & Nvl(RS("instituicao"),"---") & "</td>"
              HTML = VbCrLf & HTML & "        <td><font size=""1"">" & Nvl(RS("nome"),"---") & "</td>"
              HTML = VbCrLf & HTML & "        <td align=""center""><font size=""1"">" & RS("inicio") & "</td>"
              HTML = VbCrLf & HTML & "        <td align=""center""><font size=""1"">" & Nvl(RS("fim"),"---") & "</td>"
              HTML = VbCrLf & HTML & "      </tr>"
              RS.MoveNext
           wend
        End If
        HTML = VbCrLf & HTML & "      </center>"
        HTML = VbCrLf & HTML & "    </table>"
        HTML = VbCrLf & HTML & "  </td>"
        HTML = VbCrLf & HTML & "</tr>"
        DesconectaBD
     
        ' Extens�o acad�mica
        DB_GetCVAcadForm RS, p_usuario, null, "CURSO"
        RS.Sort = "ordem desc, carga_horaria desc"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3""><font size=""1"">&nbsp;</td>"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Extens�o acad�mica</td>"
        HTML = VbCrLf & HTML & "<tr><td align=""center"" colspan=3>"
        HTML = VbCrLf & HTML & "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        HTML = VbCrLf & HTML & "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        HTML = VbCrLf & HTML & "          <td><font size=""1""><b>N�vel</font></td>"
        HTML = VbCrLf & HTML & "          <td><font size=""1""><b>�rea</font></td>"
        HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Institui��o</font></td>"
        HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Curso</font></td>"
        HTML = VbCrLf & HTML & "          <td><font size=""1""><b>C.H.</font></td>"
        HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Conclus�o</font></td>"
        HTML = VbCrLf & HTML & "        </tr>"
        If RS.EOF Then ' Se n�o foram selecionados registros, exibe mensagem
           HTML = VbCrLf & HTML & "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>N�o foram encontrados registros.</b></td></tr>"
        Else
           ' Lista os registros selecionados para listagem
           w_cor = conTrBgColor
           While Not RS.EOF
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              HTML = VbCrLf & HTML & "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
              HTML = VbCrLf & HTML & "        <td><font size=""1"">" & RS("nm_formacao") & "</td>"
              HTML = VbCrLf & HTML & "        <td><font size=""1"">" & RS("nm_area") & "</td>"
              HTML = VbCrLf & HTML & "        <td><font size=""1"">" & RS("instituicao") & "</td>"
              HTML = VbCrLf & HTML & "        <td><font size=""1"">" & RS("nome") & "</td>"
              HTML = VbCrLf & HTML & "        <td align=""center""><font size=""1"">" & RS("carga_horaria") & "</td>"
              HTML = VbCrLf & HTML & "        <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS("conclusao")),"---") & "</td>"
              HTML = VbCrLf & HTML & "      </tr>"
              RS.MoveNext
           wend
        End If
        HTML = VbCrLf & HTML & "      </center>"
        HTML = VbCrLf & HTML & "    </table>"
        HTML = VbCrLf & HTML & "  </td>"
        HTML = VbCrLf & HTML & "</tr>"
        DesconectaBD
        
        ' Produ��o t�cnica
        DB_GetCVAcadForm RS, p_usuario, null, "PRODUCAO"
        RS.Sort = "ordem desc, data desc"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3""><font size=""1"">&nbsp;</td>"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Produ��o t�cnica</td>"
        HTML = VbCrLf & HTML & "<tr><td align=""center"" colspan=3>"
        HTML = VbCrLf & HTML & "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        HTML = VbCrLf & HTML & "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Tipo</font></td>"
        HTML = VbCrLf & HTML & "          <td><font size=""1""><b>�rea</font></td>"
        HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Nome</font></td>"
        HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Meio</font></td>"
        HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Data</font></td>"
        HTML = VbCrLf & HTML & "        </tr>"
        If RS.EOF Then ' Se n�o foram selecionados registros, exibe mensagem
           HTML = VbCrLf & HTML & "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>N�o foram encontrados registros.</b></td></tr>"
        Else
           ' Lista os registros selecionados para listagem
           w_cor = conTrBgColor
           While Not RS.EOF
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              HTML = VbCrLf & HTML & "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
              HTML = VbCrLf & HTML & "        <td><font size=""1"">" & RS("nm_formacao") & "</td>"
              HTML = VbCrLf & HTML & "        <td><font size=""1"">" & RS("nm_area") & "</td>"
              HTML = VbCrLf & HTML & "        <td><font size=""1"">" & RS("nome") & "</td>"
              HTML = VbCrLf & HTML & "        <td><font size=""1"">" & RS("meio") & "</td>"
              HTML = VbCrLf & HTML & "        <td align=""center""><font size=""1"">" & RS("data") & "</td>"
              HTML = VbCrLf & HTML & "      </tr>"
              RS.MoveNext
           wend
        End If
        HTML = VbCrLf & HTML & "      </center>"
        HTML = VbCrLf & HTML & "    </table>"
        HTML = VbCrLf & HTML & "  </td>"
        HTML = VbCrLf & HTML & "</tr>"
        DesconectaBD
        
        ' Idiomas
        DB_GetCVIdioma RS, p_usuario, null
        RS.Sort = "nome"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3""><font size=""1"">&nbsp;</td>"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Idiomas</td>"
        HTML = VbCrLf & HTML & "<tr><td align=""center"" colspan=3>"
        HTML = VbCrLf & HTML & "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        HTML = VbCrLf & HTML & "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Idioma</font></td>"
        HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Leitura</font></td>"
        HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Escrita</font></td>"
        HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Conversa��o</font></td>"
        HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Compreens�o</font></td>"
        HTML = VbCrLf & HTML & "        </tr>"
        If RS.EOF Then ' Se n�o foram selecionados registros, exibe mensagem
           HTML = VbCrLf & HTML & "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>N�o foram encontrados registros.</b></td></tr>"
        Else
           ' Lista os registros selecionados para listagem
           w_cor = conTrBgColor
           While Not RS.EOF
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              HTML = VbCrLf & HTML & "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
              HTML = VbCrLf & HTML & "        <td><font size=""1"">" & RS("nome") & "</td>"
              HTML = VbCrLf & HTML & "        <td align=""center""><font size=""1"">" & RS("nm_leitura") & "</td>"
              HTML = VbCrLf & HTML & "        <td align=""center""><font size=""1"">" & RS("nm_escrita") & "</td>"
              HTML = VbCrLf & HTML & "        <td align=""center""><font size=""1"">" & RS("nm_conversacao") & "</td>"
              HTML = VbCrLf & HTML & "        <td align=""center""><font size=""1"">" & RS("nm_compreensao") & "</td>"
              HTML = VbCrLf & HTML & "      </tr>"
              RS.MoveNext
           wend
        End If
        HTML = VbCrLf & HTML & "      </center>"
        HTML = VbCrLf & HTML & "    </table>"
        HTML = VbCrLf & HTML & "  </td>"
        HTML = VbCrLf & HTML & "</tr>"
        DesconectaBD
         
        ' Contas banc�rias
        DB_GetContaBancoList RS, p_usuario, null, null
        RS.Sort = "tipo_conta, banco, numero"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3""><font size=""1"">&nbsp;</td>"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Contas banc�rias</td>"
        HTML = VbCrLf & HTML & "<tr><td align=""center"" colspan=3>"
        HTML = VbCrLf & HTML & "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        HTML = VbCrLf & HTML & "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        HTML = VbCrLf & HTML &"          <td><font size=""1""><b>Tipo</font></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1""><b>Banco</font></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1""><b>Ag�ncia</font></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1""><b>Opera��o</font></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1""><b>Conta</font></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1""><b>Ativo</font></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1""><b>Padr�o</font></td>"
        HTML = VbCrLf & HTML & "        </tr>"
        If RS.EOF Then ' Se n�o foram selecionados registros, exibe mensagem
           HTML = VbCrLf & HTML & "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""1""><b>N�o foram encontrados registros.</b></td></tr>"
        Else
           ' Lista os registros selecionados para listagem
           w_cor = conTrBgColor
           While Not RS.EOF
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              HTML = VbCrLf & HTML & "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
              HTML = VbCrLf & HTML &"        <td><font size=""1"">" & RS("tipo_conta") & "</td>"
              HTML = VbCrLf & HTML &"        <td><font size=""1"">" & RS("banco") & "</td>"
              HTML = VbCrLf & HTML &"        <td><font size=""1"">" & RS("agencia") & "</td>"
              HTML = VbCrLf & HTML &"        <td align=""center""><font size=""1"">" & Nvl(RS("operacao"),"---") & "</td>"
              HTML = VbCrLf & HTML &"        <td><font size=""1"">" & RS("numero") & "</td>"
              HTML = VbCrLf & HTML &"        <td align=""center""><font size=""1"">" & RS("ativo") & "</td>"
              HTML = VbCrLf & HTML &"        <td align=""center""><font size=""1"">" & RS("padrao") & "</td>"
              HTML = VbCrLf & HTML & "      </tr>"
              RS.MoveNext
           wend
        End If
        HTML = VbCrLf & HTML & "      </center>"
        HTML = VbCrLf & HTML & "    </table>"
        HTML = VbCrLf & HTML & "  </td>"
        HTML = VbCrLf & HTML & "</tr>"
        DesconectaBD
     
        ' Experiencia profissional
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3""><font size=""1"">&nbsp;</td>"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Experi�ncia Profissional</td>"
        DB_GetCVAcadForm RS, p_usuario, null, "EXPERIENCIA"
        RS.Sort = "entrada desc"
        HTML = VbCrLf & HTML &"      <tr><td align=""center"" colspan=""3"">"
        HTML = VbCrLf & HTML &"        <TABLE WIDTH=""99%"" border=""0"">"
        If RS.EOF Then
           HTML = VbCrLf & HTML &"      <tr bgcolor=""" & conTrBgColor & """><td colspan=""3"" align=""center""><font size=""1""><b>N�o foi informada nenhuma experi�ncia profissional.</b></td></tr>"
        Else
           While Not RS.EOF
              HTML = VbCrLf & HTML &"          <tr> "
              HTML = VbCrLf & HTML &"          <tr><td valign=""top""><font size=""1"">Empregador:<br><b>" & RS("empregador")  & "</b></td>"
              HTML = VbCrLf & HTML &"              <td valign=""top""><font size=""1"">�rea de conhecimento:<br><b>" & RS("nm_area")  & "</b></td></tr>"
              HTML = VbCrLf & HTML &"          <tr> "
              HTML = VbCrLf & HTML &"          <tr><td valign=""top""><font size=""1"">Entrada: <br><b>" & FormataDataEdicao(RS("entrada")) & "</b></td>"
              HTML = VbCrLf & HTML &"              <td valign=""top""><font size=""1"">Saida: <br><b>" & Nvl(FormataDataEdicao(RS("saida")),"---") & "</b></td>"
              HTML = VbCrLf & HTML &"              <td valign=""top""><font size=""1"">�ltimo sal�rio mensal: <br><b>" & FormatNumber(Nvl(RS("ultimo_salario"),0),2) & "</b></td></tr>"
              HTML = VbCrLf & HTML &"          <tr> "
              HTML = VbCrLf & HTML &"          <tr><td valign=""top""><font size=""1"">Motivo sa�da: <br><b>" & Nvl(RS("motivo_saida"),"---") & "</b></td></tr>"
              HTML = VbCrLf & HTML &"          <tr> "
              HTML = VbCrLf & HTML &"          <tr><td valign=""top""><font size=""1"">Pa�s: <br><b>" & RS("nm_pais") & "</b></td>"
              HTML = VbCrLf & HTML &"              <td valign=""top""><font size=""1"">Estado: <br><b>" & RS("nm_estado") & "</b></td>"
              HTML = VbCrLf & HTML &"              <td valign=""top""><font size=""1"">Cidade: <br><b>" & RS("nm_cidade") & "</b></td></tr>"
              HTML = VbCrLf & HTML &"          <tr> "
              HTML = VbCrLf & HTML &"          <tr><td valign=""top"" colspan=3><font size=""1"">Principal atividade desempenhada: <br><b>" & RS("ds_tipo_posto") & "</b></td></tr>"
              HTML = VbCrLf & HTML &"          <tr> "
              HTML = VbCrLf & HTML &"          <tr><td valign=""top"" colspan=3><font size=""1"">Atividades desempenhadas: <br><b>" & RS("atividades") & "</b></td></tr>"
              ' Cargos da experi�ncia profissional
              DB_GetCVAcadForm RS1, RS("sq_cvpesexp"), null, "CARGO"
              If Not RS1.EOF Then
                 HTML = VbCrLf & HTML &"      <tr><td valign=""top""><font size=""1"">Cargos:<br></td></tr>"
                 HTML = VbCrLf & HTML &"      <tr><td align=""center"" colspan=""3"">"
                 HTML = VbCrLf & HTML &"        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
                 HTML = VbCrLf & HTML &"          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                 HTML = VbCrLf & HTML &"            <td><font size=""1""><b>�rea</font></td>"
                 HTML = VbCrLf & HTML &"            <td><font size=""1""><b>Especialidades</font></td>"
                 HTML = VbCrLf & HTML &"            <td><font size=""1""><b>In�cio</font></td>"
                 HTML = VbCrLf & HTML &"            <td><font size=""1""><b>Fim</font></td>"
                 HTML = VbCrLf & HTML &"          </tr>"    
                 While Not RS1.EOF
                    HTML = VbCrLf & HTML &"      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
                    HTML = VbCrLf & HTML &"        <td><font size=""1"">" & RS1("nm_area") & "</td>"
                    HTML = VbCrLf & HTML &"        <td><font size=""1"">" & RS1("especialidades") & "</td>"
                    HTML = VbCrLf & HTML &"        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS1("inicio")) & "</td>"
                    HTML = VbCrLf & HTML &"        <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS1("fim")),"---") & "</td>"
                    HTML = VbCrLf & HTML &"      </tr>"
                    RS1.MoveNext
                 wend
                 HTML = VbCrLf & HTML &"         </table></td></tr>"
              End If
              RS1.Close
              RS.MoveNext
              HTML = VbCrLf & HTML &"          <tr><td colspan=""3""><hr>"
           wend
        End If
        HTML = VbCrLf & HTML &"         </table></td></tr>"
        
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3""><font size=""1"">&nbsp;</td>"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Declara��o</td>"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3""><font size=""3""><blockquote><p align=""justify""><br>Eu, <b>" & w_nome & "</b>, declaro que as informa��es aqui constantes est�o atualizadas, s�o verdadeiras e pass�veis de comprova��o.</p><p><br></p><p align=""center"">" & FormatDateTime(Date(),1) & "</p></blockquote></td>"
        HTML = VbCrLf & HTML &"</table>"
     End If
     DesconectaBD
  Else
    ScriptOpen "JavaScript"
    HTML = VbCrLf & HTML &" alert('Op��o n�o dispon�vel');"
    HTML = VbCrLf & HTML &" history.back(1);"
    ScriptClose
  End If
  
  ShowHTML "" & HTML
  
  Set w_nome                = Nothing
  Set HTML                  = Nothing
  Set w_erro                = Nothing 
  Set Rsquery               = Nothing
  Set RS1                   = Nothing
  Set SQL1                  = Nothing
  Set RS2                   = Nothing
  Set SQL2                  = Nothing
  Set RS3                   = Nothing
  Set SQL3                  = Nothing
  Set SQLopcao              = Nothing
  Set w_ImagemPadrao        = Nothing
  Set w_Imagem              = Nothing

End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

%>