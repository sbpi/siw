<%

REM =========================================================================
REM Rotina de visualização dos dados da tarefa
REM -------------------------------------------------------------------------
Function VisualTarefaGer(w_chave, P4)

  Dim Rsquery, w_Erro
  Dim w_Imagem, w_html
  Dim w_ImagemPadrao
  
  w_html = ""

  ' Recupera os dados da tarefa
  DB_GetSolicData_IS RS, w_chave, "ISTAGERAL"

  ' Se for listagem ou envio, exibe os dados de identificação da tarefa
  w_html = w_html & VbCrLf & "<div align=center><center>"
  w_html = w_html & VbCrLf & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"

  w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
  If Not IsNull(RS("nm_projeto")) Then
    ' Recupera os dados da ação
    DB_GetSolicData_IS RS1, RS("sq_solic_pai"), "ISACGERAL"

   ' Se a ação no PPA for informada, exibe.
   If Not IsNull(RS1("cd_acao")) Then
      w_html = w_html & VbCrLf & "   <tr valign=""top""><td colspan=""3""><table border=0 width=""100%"" cellspacing=0>"
      w_html = w_html & VbCrLf & "      <tr bgcolor=""#D0D0D0""><td colspan=""3""><font size=""1"">Unidade:<br><b>" & RS1("cd_unidade") & " - " & RS1("ds_unidade") & " </b></td></tr>"
      w_html = w_html & VbCrLf & "      <tr bgcolor=""#D0D0D0""><td colspan=""3""><font size=""1"">Programa PPA:<br><b>" & RS1("cd_ppa_pai") & " - " & RS1("nm_ppa_pai") & "</b></td></tr>"
      w_html = w_html & VbCrLf & "      <tr bgcolor=""#D0D0D0""><td colspan=""3""><font size=""1"">Ação PPA:<br><b>" & RS1("cd_acao") & " - " & RS1("nm_ppa") & " </b></td>"
      w_html = w_html & VbCrLf & "   </table></td></tr>"
   End If
   ' Se o plano/projeto específico for informado, exibe.
   If Not IsNull(RS1("sq_isprojeto")) Then
      w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Plano/Projeto Específico:<b>" & RS1("nm_pri")
      If Not IsNull(RS1("cd_pri")) Then w_html = w_html & VbCrLf & " (" & RS1("cd_pri") & ")" End If
      w_html = w_html & VbCrLf & "          </b></td>"
      w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Ação: <b>" & RS("nm_projeto") & "</b></td>"
   End If
  
  End If
  w_html = w_html & VbCrLf & "      <tr><td><font size=1>Tarefa: <b>" & CRLF2BR(Nvl(RS("titulo"),"---")) & " </b></font></td></tr>"
  w_html = w_html & VbCrLf & "      <tr><td><font size=1>Descrição: <b>" & CRLF2BR(RS("assunto")) & " (" & w_chave & ")</b></font></td></tr>"
      
  ' Identificação da tarefa
  'w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Identificação</td>"

  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  'w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Responsável</td>"  
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Responsável pela tarefa:<br><b>" & Nvl(RS("nm_responsavel"),"---") & " </b></td>"
  If Not IsNull(RS("fn_responsavel")) Then
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<br><b>" & RS("fn_responsavel") & " </b></td>"
  End If
  If Not IsNull(RS("em_responsavel")) Then
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Email:<br><b><A class=""HL"" HREF=""mailto:" & RS("em_responsavel") & """>" & RS("em_responsavel") & "</a></b></td>"
  End If
  w_html = w_html & VbCrLf & "          </table>"
  
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Início previsto:<br><b>" & FormataDataEdicao(RS("inicio")) & " </b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Fim previsto:<br><b>" & FormataDataEdicao(RS("fim")) & " </b></td>"
     
  ' Informações adicionais
  'w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Informações adicionais</td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Resultados espearados:<br><b>" & Nvl(CRLF2BR(RS("descricao")),"---") & " </b></div></td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Observações:<br><b>" & Nvl(CRLF2BR(RS("justificativa")),"---") & " </b></div></td>"
  w_html = w_html & VbCrLf & "      </table>"
  w_html = w_html & VbCrLf & "      </table>"
  
  VisualTarefaGer = w_html

  Set w_erro                = Nothing 
  Set Rsquery               = Nothing
  Set w_ImagemPadrao        = Nothing
  Set w_Imagem              = Nothing

End Function
REM =========================================================================
REM Fim da visualização dos dados do cliente
REM -------------------------------------------------------------------------

%>

