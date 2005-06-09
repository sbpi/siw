<%
REM =========================================================================
REM Rotina de visualização dos dados da ação
REM -------------------------------------------------------------------------
Function VisualContrato(w_chave_aux, w_chave, O, w_usuario)

  Dim w_Imagem, w_html
  Dim w_ImagemPadrao
  Dim p_contrato, p_licitacao
  Dim w_titulo, w_contador
  
  w_html = ""
  p_contrato = "T"
  w_contador = 0
  
  ' Recupera os dados do contrato
  DB_GetLcPortalCont RS, w_cliente, w_chave, w_chave_aux, null
  
  ' Verifica a pemissão de visualização do usuario
  ' Se for listagem ou envio, exibe os dados de identificação do contrato
  If O = "L" or O = "V" Then ' Se for listagem dos dados
     w_html = w_html & VbCrLf & "<div align=center><center>"
     w_html = w_html & VbCrLf & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
     w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"

     w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
     w_html = w_html & VbCrLf & "      <tr><td><font size=2>Contrato: <b>" & RS("numero") & " - " & RS("Objeto") & "</b></font></td></tr>"
      
     ' Dados gerais do contrato
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Dados gerais</td>"
        
     w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Objeto:<br><b>" & Nvl(RS("objeto"),"---")     
     If p_contrato = "C" or p_contrato = "T" Then
        w_html = w_html & VbCrLf & "    <tr><td valign=""top"" colspan=""2"">"
        w_html = w_html & VbCrLf & "      <table border=0 width=""100%"" cellspacing=0>"
        w_html = w_html & VbCrLf & "        <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Processo:<b><br>" & Nvl(RS("processo"),"---") & "</b></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Empenho:<b><br>" & Nvl(RS("empenho"),"---") & "</b></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Assinautra:<b><br>" & FormataDataEdicao(RS("assinatura")) & "</b></td>"
        w_html = w_html & VbCrLf & "        <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Início vigência:<b><br>" & FormataDataEdicao(RS("vigencia_inicio")) & "</b></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Fim vigência:<b><br>" & FormataDataEdicao(RS("vigencia_fim")) & "</b></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Publicação D.O.U.:<b><br>" & FormataDataEdicao(RS("publicacao")) & "</b></td>"
        w_html = w_html & VbCrLf & "        <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td colspan=3><font size=""1"">Valor do contrato:<b><br>" & FormatNumber(Nvl(RS("valor"),0),2) & "</b></td>"
        w_html = w_html & VbCrLf & "        <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td colspan=3><font size=""1"">Observações:<b><br>" & Nvl(RS("observacao"),"---") & "</b></td>"
        If RS("pessoa_juridica") = "S" Then
           w_html = w_html & VbCrLf & "     <tr valign=""top"">"
           w_html = w_html & VbCrLf & "       <td valign=""top""><font size=""1"">CNPJ contratado:<b><br>" & Nvl(RS("cnpj"),"---") & "</td>"
           w_html = w_html & VbCrLf & "       <td valign=""top""><font size=""1"">Nome contratado:<b><br>" & RS("nome") & "</td>"
           w_html = w_html & VbCrLf & "       <td valign=""top""><font size=""1"">Nome resumido:<b><br>" & RS("nome_resumido") & "</td>"
        Else
           w_html = w_html & VbCrLf & "     <tr valign=""top"">"
           w_html = w_html & VbCrLf & "       <td valign=""top""><font size=""1"">CPF contratado:<b><br>" & Nvl(RS("cpf"),"---") & "</td>"
           w_html = w_html & VbCrLf & "       <td valign=""top""><font size=""1"">Nome contratado:<b><br>" & RS("nome") & "</td>"
           w_html = w_html & VbCrLf & "       <td valign=""top""><font size=""1"">Nome resumido:<b><br>" & RS("nome_resumido") & "</td>"
           w_html = w_html & VbCrLf & "     <tr><td colspan=3><font size=""1"">Sexo:<b><br>" & RS("nm_sexo") & "</td></tr>"
        End If
        w_html = w_html & VbCrLf & "        <tr><td colspan=3><font size=""1"">Unidade contratante:<b><br>" & Nvl(RS("nm_unid"),"---") & " (" & RS("sg_unid")& ")</td></tr>"
        w_html = w_html & VbCrLf & "        <tr><td colspan=3><font size=""1"">Observações:<b><br>" & Nvl(RS("observacao"),"---") & "</td></tr>"
        w_html = w_html & VbCrLf & "        <tr><td><font size=""1"">Publica esta licitação no portal?<br><b>" & RS("nm_publicar") & "</b></td></tr>"
        w_html = w_html & VbCrLf & "      </table>"
        DesconectaBD
     End If 
     If p_contrato = "T" Then
        ' Itens do contrato
        DB_GetLcPortalContItem RS, w_cliente, w_chave, w_chave_aux, null, null
        RS.Sort = "ordem, nome"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Itens do contrato</td>"
        If RS.EOF Then
           w_html = w_html  & VbCrLf & "  <tr bgcolor=""" & conTrBgColor & """><font size=""1""><td align=""center""><b>Não foi encontrado nenhum item para este contrato.</b></td></tr>"
           w_html = w_html & VbCrLf & "       <tr><td colspan=""3""><hr>"
        Else
           While Not RS.EOF
              If cDbl(Nvl(RS("Existe"),0)) > 0 Then
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
                 w_html = w_html & VbCrLf & "              <td valign=""top""><font size=""1"">Valor total:<br><b>" & FormatNumber(Nvl(RS("valor_total"),0),2) & " </b></td>"
                 w_html = w_html & VbCrLf & "              </table>"
                 w_html = w_html & VbCrLf & "          <tr><td valign=""top""><table border=0 width=""100%"" cellspacing=0>"
                 w_html = w_html & VbCrLf & "              <td valign=""top""><font size=""1"">Observação:<br><b>" & Nvl(RS("situacao"),"---") & " </b></td>"
                 w_html = w_html & VbCrLf & "              </table>"
                 w_html = w_html & VbCrLf & "       </table>"
                 w_html = w_html & VbCrLf & "       <tr><td colspan=""3""><hr>"
                 w_contador = w_contador + 1
              End If
              RS.MoveNext
           wend
           If cDbl(w_contador) = 0 Then
              w_html = w_html  & VbCrLf & "  <tr bgcolor=""" & conTrBgColor & """><td align=""center""><font size=""1""><b>Não foi encontrado nenhum item para este contrato.</b></td></tr>"
              w_html = w_html & VbCrLf & "       <tr><td colspan=""3""><hr>"
           End If
        End If
        DesconectaBD
     End If
     DB_GetLcPortalLic RS, w_cliente, w_usuario, w_menu, w_chave, null, null, null, _
       null,null, null, null, null, null, null, null, null, null, null, null, null, null
     w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"      
     ' Dados gerais da licitação
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Dados da licitação</td>"
        
     w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Objeto:<br><b>" & Nvl(RS("objeto"),"---")     
     If p_contrato = "C" or p_contrato = "T" Then
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
     End If
     DesconectaBD
     w_html = w_html & VbCrLf & "<tr><td valign=""top"" colspan=""3"">"
     w_html = w_html & VbCrLf & "</table>"
  End If
  
  VisualContrato = w_html

  Set w_html                = Nothing
  Set p_licitacao           = Nothing
  Set p_contrato            = Nothing
  Set w_ImagemPadrao        = Nothing
  Set w_Imagem              = Nothing

End Function
REM =========================================================================
REM Fim da visualização dos dados do contrato
REM -------------------------------------------------------------------------

%>

