<%

REM =========================================================================
REM Rotina de envio de e-mail
REM -------------------------------------------------------------------------
Function EnviaMail2(w_para)

   Dim JMail
   Dim Recipients(500), i, j
   
   'Instancia o objeto de email
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

   JMail.Subject             = ul.Texts.Item("w_assunto")
   JMail.HtmlBody            = ul.Texts.Item("w_mensagem")
   
   JMail.AddAttachment (conFilePhysical & w_cliente & "\" & Field.FileName)
   JMail.ClearRecipients()
   i = 0
   If Instr(w_para,";") > 0 and Mid(w_para,Len(w_para),Len(w_para)) <> ";" Then
         w_para = w_para &";"
   End If
   Do While Instr(w_para,";") > 0
      If Len(w_para) > 2 Then Recipients(i) = Mid(w_para,1,Instr(w_para,";")-1) End If
      w_para = Mid(w_para,Instr(w_para,";")+1,Len(w_para))
      i = i+1
   Loop
   For j = 0 To i-1
      If j = 0 Then ' Se for o primeiro, coloca como destinatário principal. Caso contrário, coloca como CC.
         JMail.AddRecipient Recipients(j)
      Else
         JMail.AddRecipientCC Recipients(j)
      End If
   Next
   
   JMail.Send Session("smtp_server")
  
   If JMail.ErrorCode <> 0 Then
     EnviaMail2 = Replace("Erro: " & JMail.ErrorCode & "\nMensagem: " & JMail.ErrorMessage & "\nFonte: " & JMail.ErrorSource,VbCrLf,"\n")
   Else
     EnviaMail2 = ""
   End If
End Function
REM =========================================================================
REM Fim da rotina de envio de email
REM -------------------------------------------------------------------------
%>