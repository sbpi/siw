<%
REM =========================================================================
REM Rotina de envio de e-mail
REM -------------------------------------------------------------------------
Function EnviaMail2(w_subject, w_mensagem, w_recipients, w_attachments)

  w_recipients = w_recipients & ";"
  Dim JMail
  Dim Recipients(500), i, j

  Set JMail = Server.CreateObject("JMail.Message")
  
  
  JMail.Silent   = True
  JMail.From     = Session("siw_email_conta")
  JMail.FromName = Session("siw_email_nome")
  If Session("siw_email_senha") > "" Then
     JMail.Logging            = True
     JMail.MailServerUserName = Session("siw_email_conta")
     JMail.MailServerPassWord = Session("siw_email_senha")
  Else
     JMail.Logging  = False
     JMail.MailServerUserName = Session("siw_email_conta")
  End If

  JMail.Subject       = w_subject
  JMail.HtmlBody      = w_mensagem
  'JMail.AddCustomAttachment(Projeto.doc, ajfhaksdjfh kjh))
  JMail.ClearRecipients()
  i = 0
  Do While Instr(w_recipients,";") > 0
     If Len(w_recipients) > 2 Then Recipients(i) = Mid(w_recipients,1,Instr(w_recipients,";")-1) End If
     w_recipients = Mid(w_recipients,Instr(w_recipients,";")+1,Len(w_recipients))
     i = i+1
  Loop
  For j = 0 To i-1
     If j = 0 Then ' Se for o primeiro, coloca como destinatário principal. Caso contrário, coloca como CC.
        JMail.AddRecipient Recipients(j)
     Else
        JMail.AddRecipientCC Recipients(j)
     End If
  Next

  On Error Resume Next

  JMail.Send Session("smtp_server")

  If JMail.ErrorCode <> 0 Then
    EnviaMail = Replace("Erro: " & JMail.ErrorCode & "\nMensagem: " & JMail.ErrorMessage & "\nFonte: " & JMail.ErrorSource,VbCrLf,"\n")
  Else
    EnviaMail = ""
  End If
  
End Function
REM =========================================================================
REM Fim da rotina de envio de email
REM -------------------------------------------------------------------------
%>

