<%

REM =========================================================================
REM Rotina de envio de e-mail
REM -------------------------------------------------------------------------
Function EnviaMail2

   Dim JMail, attachments
   Dim Recipients(500), i, j
   
   'Instancia o objeto de email
   Set JMail = Server.CreateObject("JMail.Message")
   'set attachments = JMail.Attachments
   'set attachment = attachments.New( "myAttachment.text", "text/plain", "this is my new text file" )
  
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

   JMail.Subject             = ul.Form("w_assunto")
   JMail.HtmlBody            = ul.Form("w_mensagem")
   JMail.AddAttachment (conFilePhysical & w_cliente & "\" & ul.GetFileName(ul.Files("w_caminho").OriginalPath))
  
   JMail.ClearRecipients()
   JMail.AddRecipient ul.Form("w_para")
  
   On Error Resume Next
  
   JMail.Send Session("smtp_server")
  
   If JMail.ErrorCode <> 0 Then
     EnviaMail = Replace("Erro: " & JMail.ErrorCode & "\nMensagem: " & JMail.ErrorMessage & "\nFonte: " & JMail.ErrorSource,VbCrLf,"\n")
   Else
     EnviaMail2 = ""
   End If
  
End Function
REM =========================================================================
REM Fim da rotina de envio de email
REM -------------------------------------------------------------------------
%>