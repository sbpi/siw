<%
REM =========================================================================
REM Rotina de visualização do currículo
REM -------------------------------------------------------------------------
Sub VisualListaTel(p_cliente)
  ' Lista Telefônica
  DB_GetRamalUsuarioAtivo RS, p_cliente
  RS.Sort = Request("p_ordena")
  ShowHTML " <tr><td align=""center"" colspan=2>"
  ShowHTML "     <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
  ShowHTML "         <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
  If P2 > 0 Then
    ShowHTML "           <td><font size=""1""><b>Usuário</font></td>"
    ShowHTML "           <td><font size=""1""><b>Ramal</font></td>"
  Else
    ShowHTML "           <td><font size=""1""><b><a class=""SS"" href=""" & w_dir & w_pagina & par & "&p_ordena=nm_usuario_completo,codigo&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Usuário</a></font></td>"
    ShowHTML "           <td><font size=""1""><b><a class=""SS"" href=""" & w_dir & w_pagina & par & "&p_ordena=codigo,nm_usuario_completo&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Ramal</a></font></td>"
  End If
  ShowHTML "         </tr>"
  If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
    ShowHTML "       <tr bgcolor=""" & conTrBgColor & """><td colspan=2 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
  Else
    ' Lista os registros selecionados para listagem
    w_cor = conTrBgColor
    While Not RS.EOF
      If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
      ShowHTML "       <tr bgcolor=""" & w_cor & """ valign=""top"">"
      ShowHTML "         <td><font size=""1"">"                  & RS("nm_usuario_completo") & "</td>"
      ShowHTML "         <td align=""center""><font size=""1"">" & RS("codigo")              & "</td>"
      ShowHTML "       </tr>"
      RS.MoveNext
    wend
  End If
  ShowHTML "       </center>"
  ShowHTML "     </table>"
  ShowHTML "   </td>"
  ShowHTML " </tr>"
  DesconectaBD
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------
%>