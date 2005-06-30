<%
REM =========================================================================
REM Rotina de visualização do currículo
REM -------------------------------------------------------------------------
Sub ResumLigPart (w_sq_usuario, inicio, fim, ativo, O)
  Dim w_cor_fonte, w_negrito, w_soma
  If O = "L" Then
    DB_GetCall RS, null, w_sq_usuario, "1", null, null, null, null, inicio, fim, "N"
    ShowHTML "<tr><td><font size=""2"">"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Tipo          </font></td>"
    ShowHTML "          <td><font size=""1""><b>Data          </font></td>"
    ShowHTML "          <td><font size=""1""><b>Número        </font></td>"
    ShowHTML "          <td><font size=""1""><b>Duração       </font></td>"
    ShowHTML "          <td><font size=""1""><b>RM            </font></td>"
    ShowHTML "          <td><font size=""1""><b>Local         </font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome          </font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        w_cor_fonte = ""
        If IsNull(RS("trabalho")) and RS("sq_usuario_central") > "" Then 
          w_negrito = "<b>"
          If cDbl(Nvl(RS("sq_usuario_central"),0)) <> cDbl(Nvl(w_sq_usuario_central,0)) Then w_cor_fonte = "color=""#0011FF""" End If
        Else 
          w_negrito = "" 
        End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """>"
        ShowHTML "        <td align=""center""><font size=""1"" " & w_cor_fonte & ">" & RS("tipo") & "</td>"
        ShowHTML "        <td nowrap align=""center""><font size=""1"" " & w_cor_fonte & ">" & w_negrito & RS("data") & "</td>"
        ShowHTML "        <td><font size=""1"" " & w_cor_fonte & ">" & RS("numero") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"" " & w_cor_fonte & ">" & FormataTempo(cDbl(RS("duracao"))) & "&nbsp;</td>"
        ShowHTML "        <td align=""center""><font size=""1"" " & w_cor_fonte & ">" & RS("sq_ramal") & "</td>"
        ShowHTML "        <td><font size=""1"" " & w_cor_fonte & ">" & RS("localidade") & "</td>"
        ShowHTML "        <td><font size=""1"" " & w_cor_fonte & ">" & RS("d_nome") & "</td>"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        w_soma  = w_soma + Int(cDbl(RS("duracao")))
        RS.MoveNext
      wend
      If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
      ShowHTML "      <tr bgcolor=""" & w_cor & """>"
      ShowHTML "        <td align=""right"" colspan=3><font size=""1""><b>Duração total:</td>"
      ShowHTML "        <td align=""center""><font size=""1""><b>" & FormataTempo(w_soma) & "&nbsp;</td>"
      ShowHTML "        <td colspan=7><font size=""1"">&nbsp;</td>"
      ShowHTML "      </tr>"
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "      <tr><td valign=""top"" colspan=""3""><font  size=""3""><blockquote><p align=""justify"">Eu, <b> " & Request("w_nome_usuario") & "</b>, declaro que as informações aqui constantes estão atualizadas, são verdadeiras e passíveis de comprovação.</p></blockquote></td>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "</tr>"    
    DesconectaBD 
  End If
  ShowHTML "      </center>"
  ShowHTML "    </table>"
  ShowHTML "  </td>"
  ShowHTML "</tr>"
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------
%>