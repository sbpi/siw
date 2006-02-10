<%
REM =========================================================================
REM Rotina de visualização do currículo
REM -------------------------------------------------------------------------
Sub VisualCurriculo(p_cliente, p_usuario, O)

  Dim Rsquery, w_erro, w_nome
  Dim w_Imagem
  Dim w_ImagemPadrao
  Dim SQL1, RS1, SQL2, RS2, SQL3, RS3, SQLopcao
  Dim HTML

  If O = "L" Then ' Se for listagem dos dados
  
     ' Identificação pessoal
     DB_GetCV RS, p_cliente, p_usuario, "CVIDENT", "DADOS"
     
     w_nome        = RS("nome")
     HTML = "<div align=center><center>"
     HTML = VbCrLf & HTML &"<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
     HTML = VbCrLf & HTML &"<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
     HTML = VbCrLf & HTML &"    <table width=""99%"" border=""0"">"
     HTML = VbCrLf & HTML &"      <tr><td align=""center"" colspan=""3""><font size=5><b>" & RS("nome") & "</b></font></td></tr>"
     HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Identificação</td>"
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
     HTML = VbCrLf & HTML &"          <td><font size=""1"">Formação acadêmica:<br><b>" & RS("nm_formacao") & " </b></td>"
     HTML = VbCrLf & HTML &"          <td><font size=""1"">&nbsp;</td>"
     HTML = VbCrLf & HTML &"      <tr><td colspan=2><font size=""1"">&nbsp;</td>"
     
     HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Local de nascimento</td>"
     HTML = VbCrLf & HTML &"      <tr valign=""top"">"
     HTML = VbCrLf & HTML &"          <td><font size=""1"">País:<br><b>" & RS("nm_pais_nascimento") & " </b></td>"
     HTML = VbCrLf & HTML &"          <td><font size=""1"">Estado:<br><b>" & RS("nm_uf_nascimento") & " </b></td>"
     HTML = VbCrLf & HTML &"          <td><font size=""1"">Cidade:<br><b>" & RS("nm_cidade_nascimento") & " </b></td>"

     HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Documentação</td>"
     HTML = VbCrLf & HTML &"      <tr valign=""top"">"
     HTML = VbCrLf & HTML &"          <td><font size=""1"">Identidade:<br><b>" & RS("rg_numero") & " </b></td>"
     HTML = VbCrLf & HTML &"          <td><font size=""1"">Emissor:<br><b>" & RS("rg_emissor") & " </b></td>"
     HTML = VbCrLf & HTML &"          <td><font size=""1"">Data de emissão:<br><b>" & FormataDataEdicao(RS("rg_emissao")) & " </b></td>"
     HTML = VbCrLf & HTML &"      <tr valign=""top"">"
     HTML = VbCrLf & HTML &"          <td><font size=""1"">CPF:<br><b>" & RS("cpf")  & "</b></td>"
     HTML = VbCrLf & HTML &"          <td><font size=""1"">Passaporte:<br><b>" & Nvl(RS("passaporte_numero"),"---") & " </b></td>"
     HTML = VbCrLf & HTML &"          <td valign=""top""><font size=""1"">País emissor:<br><b>" & Nvl(RS("nm_pais_passaporte"),"---") & " </b></td>"
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
     HTML = VbCrLf & HTML &"          <td><font size=""1""><b>Número</font></td>"
     HTML = VbCrLf & HTML &"          <td><font size=""1""><b>Padrão</font></td>"
     HTML = VbCrLf & HTML & "        </tr>"
     If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        HTML = VbCrLf & HTML & "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
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
     
     'Endereços de e-mail e internet
     DB_GetAddressList RS, p_usuario, null, null
     RS.Sort = "tipo_endereco, endereco"
     RS.Filter = "email='S' or internet='S'"
     HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3""><font size=""1"">&nbsp;</td>"
     HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Endereços de e-Mail e Internet</td>"
     HTML = VbCrLf & HTML &"      <tr><td align=""center"" colspan=""2"">"
     HTML = VbCrLf & HTML &"        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     HTML = VbCrLf & HTML &"          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     HTML = VbCrLf & HTML &"            <td><font size=""1""><b>Endereço</font></td>"
     HTML = VbCrLf & HTML &"            <td><font size=""1""><b>Padrão</font></td>"
     HTML = VbCrLf & HTML &"          </tr>"    
     If RS.EOF Then
        HTML = VbCrLf & HTML &"      <tr bgcolor=""" & conTrBgColor & """><td colspan=2 align=""center""><font size=""1""><b>Não foi informado nenhum endereço de e-Mail ou Internet.</b></td></tr>"
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

     'Endereços físicos
     HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3""><font size=""1"">&nbsp;</td>"
     DB_GetAddressList RS, p_usuario, null, null
     RS.Sort = "tipo_endereco, endereco"
     RS.Filter = "email='N' and internet='N'"
     HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Endereços Físicos</td>"
     If RS.EOF Then
        HTML = VbCrLf & HTML &"      <tr bgcolor=""" & conTrBgColor & """><font size=""1""><b>Não foi encontrado nenhum endereço.</b></td></tr>"
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
          HTML = VbCrLf & HTML &"              <td valign=""top""><font size=""1"">País:<br><b>" & RS("nm_pais") & " </b></td>"
          HTML = VbCrLf & HTML &"          <tr><td><td colspan=3><font size=""1"">Padrão?<br><b>" & RS("padrao") & "</font></td></tr>"
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
     HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Nível</font></td>"
     HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Área</font></td>"
     HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Instituição</font></td>"
     HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Curso</font></td>"
     HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Início</font></td>"
     HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Término</font></td>"
     HTML = VbCrLf & HTML & "        </tr>"
     If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        HTML = VbCrLf & HTML & "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
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
     
     ' Extensão acadêmica
     DB_GetCVAcadForm RS, p_usuario, null, "CURSO"
     RS.Sort = "ordem desc, carga_horaria desc"
     HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3""><font size=""1"">&nbsp;</td>"
     HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Extensão acadêmica</td>"
     HTML = VbCrLf & HTML & "<tr><td align=""center"" colspan=3>"
     HTML = VbCrLf & HTML & "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     HTML = VbCrLf & HTML & "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Nível</font></td>"
     HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Área</font></td>"
     HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Instituição</font></td>"
     HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Curso</font></td>"
     HTML = VbCrLf & HTML & "          <td><font size=""1""><b>C.H.</font></td>"
     HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Conclusão</font></td>"
     HTML = VbCrLf & HTML & "        </tr>"
     If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        HTML = VbCrLf & HTML & "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
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
     
     ' Produção técnica
     DB_GetCVAcadForm RS, p_usuario, null, "PRODUCAO"
     RS.Sort = "ordem desc, data desc"
     HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3""><font size=""1"">&nbsp;</td>"
     HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Produção técnica</td>"
     HTML = VbCrLf & HTML & "<tr><td align=""center"" colspan=3>"
     HTML = VbCrLf & HTML & "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     HTML = VbCrLf & HTML & "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Tipo</font></td>"
     HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Área</font></td>"
     HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Nome</font></td>"
     HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Meio</font></td>"
     HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Data</font></td>"
     HTML = VbCrLf & HTML & "        </tr>"
     If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        HTML = VbCrLf & HTML & "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
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
     HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Conversação</font></td>"
     HTML = VbCrLf & HTML & "          <td><font size=""1""><b>Compreensão</font></td>"
     HTML = VbCrLf & HTML & "        </tr>"
     If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        HTML = VbCrLf & HTML & "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
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
     
     ' Experiencia profissional
     HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3""><font size=""1"">&nbsp;</td>"
     HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Experiência Profissional</td>"
     DB_GetCVAcadForm RS, p_usuario, null, "EXPERIENCIA"
     RS.Sort = "entrada desc"
     HTML = VbCrLf & HTML &"      <tr><td align=""center"" colspan=""3"">"
     HTML = VbCrLf & HTML &"        <TABLE WIDTH=""99%"" border=""0"">"
     If RS.EOF Then
        HTML = VbCrLf & HTML &"      <tr bgcolor=""" & conTrBgColor & """><td colspan=""3"" align=""center""><font size=""1""><b>Não foi informada nenhuma experiência profissional.</b></td></tr>"
     Else
        While Not RS.EOF
           HTML = VbCrLf & HTML &"          <tr> "
           HTML = VbCrLf & HTML &"          <tr><td valign=""top""><font size=""1"">Empregador:<br><b>" & RS("empregador")  & "</b></td>"
           HTML = VbCrLf & HTML &"              <td valign=""top""><font size=""1"">Área de conhecimento:<br><b>" & RS("nm_area")  & "</b></td></tr>"
           HTML = VbCrLf & HTML &"          <tr> "
           HTML = VbCrLf & HTML &"          <tr><td valign=""top""><font size=""1"">Entrada: <br><b>" & FormataDataEdicao(RS("entrada")) & "</b></td>"
           HTML = VbCrLf & HTML &"              <td valign=""top""><font size=""1"">Saida: <br><b>" & Nvl(FormataDataEdicao(RS("saida")),"---") & "</b></td>"
           HTML = VbCrLf & HTML &"          <tr> "
           HTML = VbCrLf & HTML &"          <tr><td valign=""top""><font size=""1"">Motivo saída: <br><b>" & Nvl(RS("motivo_saida"),"---") & "</b></td></tr>"
           HTML = VbCrLf & HTML &"          <tr> "
           HTML = VbCrLf & HTML &"          <tr><td valign=""top""><font size=""1"">País: <br><b>" & RS("nm_pais") & "</b></td>"
           HTML = VbCrLf & HTML &"              <td valign=""top""><font size=""1"">Estado: <br><b>" & RS("nm_estado") & "</b></td>"
           HTML = VbCrLf & HTML &"              <td valign=""top""><font size=""1"">Cidade: <br><b>" & RS("nm_cidade") & "</b></td></tr>"
           HTML = VbCrLf & HTML &"          <tr> "
           HTML = VbCrLf & HTML &"          <tr><td valign=""top"" colspan=3><font size=""1"">Principal atividade desempenhada: <br><b>" & RS("ds_tipo_posto") & "</b></td></tr>"
           HTML = VbCrLf & HTML &"          <tr> "
           HTML = VbCrLf & HTML &"          <tr><td valign=""top"" colspan=3><font size=""1"">Atividades desempenhadas: <br><b>" & RS("atividades") & "</b></td></tr>"
           ' Cargos da experiência profissional
           DB_GetCVAcadForm RS1, RS("sq_cvpesexp"), null, "CARGO"
           If Not RS1.EOF Then
              HTML = VbCrLf & HTML &"      <tr><td valign=""top""><font size=""1"">Cargos:<br></td></tr>"
              HTML = VbCrLf & HTML &"      <tr><td align=""center"" colspan=""3"">"
              HTML = VbCrLf & HTML &"        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
              HTML = VbCrLf & HTML &"          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
              HTML = VbCrLf & HTML &"            <td><font size=""1""><b>Área</font></td>"
              HTML = VbCrLf & HTML &"            <td><font size=""1""><b>Especialidades</font></td>"
              HTML = VbCrLf & HTML &"            <td><font size=""1""><b>Início</font></td>"
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
     DesconectaBD
     HTML = VbCrLf & HTML &"         </table></td></tr>"
     
     HTML = VbCrLf & HTML &"</table>"

  Else
    ScriptOpen "JavaScript"
    HTML = VbCrLf & HTML &" alert('Opção não disponível');"
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