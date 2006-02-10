<%
REM =========================================================================
REM Rotina de visualização dos dados da ação
REM -------------------------------------------------------------------------
Function VisualLicitacao(w_chave, O, w_usuario)

  Dim w_Imagem, w_html
  Dim w_ImagemPadrao
  Dim p_contrato, p_licitacao
  Dim w_titulo
  
  w_html = ""
  p_licitacao = "T"
  
  ' Recupera os dados da licitação
  DB_GetLcPortalLic RS, w_cliente, w_usuario, w_menu, w_chave, null, null, null, _
              null, null, null, null, null, null, null, null, null, null, null, null, null, null
  
  ' Verifica a pemissão de visualização do usuario
  ' Se for listagem ou envio, exibe os dados de identificação da licitação
  If O = "L" or O = "V" Then ' Se for listagem dos dados
     w_html = w_html & VbCrLf & "<div align=center><center>"
     w_html = w_html & VbCrLf & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
     w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"

     w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
     w_html = w_html & VbCrLf & "      <tr><td><font size=2>Licitação: <b>" & RS("nm_modalidade") & " " & RS("edital") & "</b></font></td></tr>"
      
     ' Dados gerais da licitação
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Dados gerais</td>"
        
     w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Objeto:<br><b>" & Nvl(RS("objeto"),"---")     
     If p_licitacao = "C" or p_licitacao = "T" Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
        w_html = w_html & VbCrLf & "      <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Processo:<b><br>" & Nvl(RS("processo"),"---") & "</b></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Empenho:<b><br>" & Nvl(RS("empenho"),"---") & "</b></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Abertura:<b><br>" & RS("abertura") & "</b></td>"
        w_html = w_html & VbCrLf & "      <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td colspan=3><font size=""1"">Observações:<b><br>" & Nvl(RS("observacao"),"---") & "</b></td>"
        w_html = w_html & VbCrLf & "      <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Fundamentação<br><b>" & Nvl(RS("fundamentacao"),"---") & "</b></td>"
        w_html = w_html & VbCrLf & "      <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Modalidade da licitação<br><b>" & RS("nm_modalidade") & "</b></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Fonte de recursos<br><b>" & RS("nm_fonte") & "</b></td>"
        w_html = w_html & VbCrLf & "      <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Finalidade da licitacao<br><b>" & RS("nm_finalidade") & "</b></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Critério de julgamento<br><b>" & RS("nm_criterio") & "</b></td>"
        w_html = w_html & VbCrLf & "      <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Situação da licitação<br><b>" & RS("nm_situacao") & "</b></td>"
        w_html = w_html & VbCrLf & "          <td colspan=2><font size=""1"">Unidade licitante<br><b>" & RS("nm_unid") & " (" & RS("sg_unid") & ")</b></td>"
        w_html = w_html & VbCrLf & "      <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Publica esta licitação no portal?<br><b>" & RS("nm_publicar") & "</b></td>"
        w_html = w_html & VbCrLf & "          </table>"
        DesconectaBD
     End If 
     If p_licitacao = "T" Then
        ' Itens da licitação
        DB_GetLcPortalLicItem RS, w_cliente, w_chave, null, null, null
        RS.Sort = "ordem, nome"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Itens</td>"
        If RS.EOF Then
           w_html = w_html  & VbCrLf & "      <tr bgcolor=""" & conTrBgColor & """><font size=""1""><b>Não foi encontrado nenhum item para esta licitação.</b></td></tr>"
        Else
           While Not RS.EOF
              w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2""><TABLE WIDTH=""100%"" BORDER=""0"" CELLSPACING=""0"" CELLPADDING=""0"">"
              w_html = w_html & VbCrLf & "          <tr><td colspan=3><font size=""1""><b>" & RS("ordem") & " - "& RS("nome") & "</font></td>"
              w_html = w_html & VbCrLf & "          <tr><td width=""5%"" rowspan=4><td valign=""top""><font size=""1"">Descrição:<br><b>" & Nvl(RS("descricao"),"---") & " </b></td>"
              w_html = w_html & VbCrLf & "          <tr><td valign=""top""><table border=0 width=""100%"" cellspacing=0>"
              w_html = w_html & VbCrLf & "              <td valign=""top""><font size=""1"">Quantidade:<br><b>" & FormatNumber(RS("quantidade"),1) & " </b></td>"
              w_html = w_html & VbCrLf & "              <td valign=""top""><font size=""1"">Unidade de fornecimento:<br><b>" & Nvl(RS("nm_unidade_fornec"),"---") 
              If Nvl(RS("nm_unidade_fornec"),"---") <> "---" Then
                 w_html = w_html & VbCrLf & " (" &RS("sg_unidade_fornec")& ")"
              End If
              w_html = w_html & VbCrLf & "              <td valign=""top""><font size=""1"">Cancelado:<br><b>" & RS("nm_cancelado") & " </b></td>"
              w_html = w_html & VbCrLf & "              </table>"
              w_html = w_html & VbCrLf & "          <tr><td valign=""top""><table border=0 width=""100%"" cellspacing=0>"
              w_html = w_html & VbCrLf & "              <td valign=""top""><font size=""1"">Valor unitário:<br><b>" & FormatNumber(Nvl(RS("valor_unitario"),0),2) & " </b></td>"
              w_html = w_html & VbCrLf & "              <td valign=""top""><font size=""1"">Valor total:<br><b>" & FormatNumber(Nvl(RS("valor_total"),2),0) & " </b></td>"
              w_html = w_html & VbCrLf & "              </table>"
              w_html = w_html & VbCrLf & "          <tr><td valign=""top""><table border=0 width=""100%"" cellspacing=0>"
              w_html = w_html & VbCrLf & "              <td valign=""top""><font size=""1"">Observação:<br><b>" & Nvl(RS("situacao"),"---") & " </b></td>"
              w_html = w_html & VbCrLf & "              </table>"
              w_html = w_html & VbCrLf & "              </table>"
              RS.MoveNext
              w_html = w_html & VbCrLf & "          <tr><td colspan=""3""><hr>"
           wend
        End If
        DesconectaBD
     End If
     If p_licitacao = "T" Then
        ' Arquivos vinculados
        DB_GetLcAnexo RS, w_chave, null, w_cliente
        RS.Sort = "nome"
        If Not RS.EOF Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Arquivos anexos</td>"
           w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
           w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
           w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Título</font></td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Descrição</font></td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Tipo</font></td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1""><b>KB</font></td>"
           w_html = w_html & VbCrLf & "          </tr>"
           w_cor = conTrBgColor
           While Not RS.EOF
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
              w_html = w_html & VbCrLf & "        <td><font size=""1"">" & LinkArquivo("HL", w_cliente, RS("chave_aux"), "_blank", "Clique para exibir o arquivo em outra janela.", RS("nome"), null) & "</td>"
              w_html = w_html & VbCrLf & "        <td><font size=""1"">" & Nvl(RS("descricao"),"---") & "</td>"
              w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS("tipo") & "</td>"
              w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & Round(cDbl(RS("tamanho"))/1024,1) & "&nbsp;</td>"
              w_html = w_html & VbCrLf & "      </tr>"
              RS.MoveNext
           wend
           w_html = w_html & VbCrLf & "         </table></td></tr>"
        End If
        DesconectaBD
        'Contratos da licitacao
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Contratos</td>"
        DB_GetLcPortalCont RS, w_cliente, w_chave, w_chave_aux, null
        ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=2>"
        w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Número</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Unidade</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Objeto</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Vigência</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Valor</font></td>"
        w_html = w_html & VbCrLf & "          </tr>"
        If RS.EOF Then
           w_html = w_html & VbCrLf & "       <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
        Else
           While Not RS.EOF
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              w_html = w_html & VbCrLf & "    <tr bgcolor=""" & w_cor & """ valign=""top"">"
              w_html = w_html & VbCrLf & "      <td><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_pagina & "VisualCont&R=" & w_pagina & par & "&O=L&w_chave_aux=" & RS("sq_portal_contrato") & "&w_chave=" & RS("chave") &   "&w_tipo=&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste contrato."" target=""blank"">" & RS("numero") & "&nbsp;</a>"
              w_html = w_html & VbCrLf & "      <td nowrap><font size=""1"">" & RS("sg_unid") & "</td>"
              ' Verifica se foi enviado o parâmetro p_tamanho = N. Se chegou, o assunto deve ser exibido sem corte.
              ' Este parâmetro é enviado pela tela de filtragem das páginas gerenciais
              If Request("p_tamanho") = "N" Then
                 w_html = w_html & VbCrLf & "   <td><font size=""1"">" & Nvl(RS("objeto"),"-") & "</td>"
              Else
                 If Len(Nvl(RS("objeto"),"-")) > 50 Then w_titulo = Mid(Nvl(RS("objeto"),"-"),1,50) & "..." Else w_titulo = Nvl(RS("objeto"),"-") End If
                 w_html = w_html & VbCrLf & "   <td><font size=""1"">" & w_titulo & "</td>"
              End If
              w_html = w_html & VbCrLf & "      <td nowrap align=""center""><font size=""1"">" & FormatDateTime(RS("vigencia_inicio"),2)&"-"&FormatDateTime(RS("vigencia_fim"),2) & "</td>"
              w_html = w_html & VbCrLf & "      <td nowrap align=""right""><font size=""1"">" & FormatNumber(RS("valor"),2) & "&nbsp;</td>"
              w_html = w_html & VbCrLf & "      </td>"
              w_html = w_html & VbCrLf & "    </tr>"
              RS.MoveNext
           wend
        End If
        w_html = w_html & VbCrLf & "        </table>"
        w_html = w_html & VbCrLf & "      </td>"
        w_html = w_html & VbCrLf & "    </tr>"
        DesconectaBD
     End If 
     w_html = w_html & VbCrLf & "<tr><td valign=""top"" colspan=""3"">"
     w_html = w_html & VbCrLf & "</table>"
  End If
  
  VisualLicitacao = w_html

  Set w_html                = Nothing
  Set p_licitacao           = Nothing
  Set p_contrato            = Nothing
  Set w_ImagemPadrao        = Nothing
  Set w_Imagem              = Nothing

End Function
REM =========================================================================
REM Fim da visualização dos dados do cliente
REM -------------------------------------------------------------------------

%>

