<%
REM =========================================================================
REM Rotina de visualização dos dados do projeto
REM -------------------------------------------------------------------------
Function VisualTabelaWord(w_chave)

   Dim Rsquery, w_html, w_TrBgColor
   Dim w_count
   Set Rsquery = Server.CreateObject("ADODB.RecordSet")
     
   w_html  = ""
   w_count = 1

   DB_GetViagemBenef RSQuery, w_chave, w_cliente, null, null, null, null
   w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Ocorrências e Anotações</td>"
   w_html = w_html & VbCrLf & "      <tr><td colspan=""3"">"
   w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
   w_html = w_html & VbCrLf & "          <tr bgcolor=""" & w_TrBgColor & """ align=""center"">"
   w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Nº</font></td>"
   w_html = w_html & VbCrLf & "            <td><font size=""1""><b>PAX</font></td>"
   w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Loc</font></td>"
   w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Ida</font></td>"
   w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Class</font></td>"
   w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Base tarifária</font></td>"
   w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Trecho</font></td>"
   w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Tarifa USD</font></td>"
   w_html = w_html & VbCrLf & "            <td><font size=""1""><b>C/ Desconto</font></td>"
   w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Tarifa BRL</font></td>"
   w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Taxa de Embarque</font></td>"
   w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Total</font></td>"
   w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Dólar do Dia</font></td>"
   w_html = w_html & VbCrLf & "          </tr>"
   If RSQuery.EOF Then
      w_html = w_html & VbCrLf & "      <tr bgcolor=""" & w_TrBgColor & """><td colspan=13 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
   Else
      w_cor = w_TrBgColor
      w_count = 1
      While Not RSQuery.EOF
         If w_cor = w_TrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = w_TrBgColor End If
         w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
         w_html = w_html & VbCrLf & "        <td><font size=""1"">" & w_count & "</td>"
         w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RSQuery("nm_pessoa") & "</td>"
         w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">&nbsp;</td>"
         w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & FormataDataEdicao(RSQuery("saida")) & "</td>"
         w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">&nbsp;</td>"
         w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">&nbsp;</td>"
         w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & Nvl(RSQuery("trechos"),"---") & "</td>"
         w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">&nbsp;</td>"
         w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">&nbsp;</td>"
         w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">&nbsp;</td>"
         w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">&nbsp;</td>"
         w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">&nbsp;</td>"
         w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">&nbsp;</td>"
         w_html = w_html & VbCrLf & "      </tr>"
         RSQuery.MoveNext
         w_count = w_count + 1
       wend
       w_html = w_html & VbCrLf & "         </table></td></tr>"
   End If
   RSQuery.Close

   VisualTabelaWord = w_html

   Set Rsquery     = Nothing
   Set w_html      = Nothing
   Set w_TrBgColor = Nothing
   Set w_count     = Nothing
End Function
REM =========================================================================
REM Fim da visualização dos dados do cliente
REM -------------------------------------------------------------------------

%>