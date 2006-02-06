<%
REM =========================================================================
REM Rotina de visualização do alberguista
REM -------------------------------------------------------------------------
Sub VisualAlberguista(p_chave, O)

  Dim HTML

  If O = "L" Then ' Se for listagem dos dados
  
     ' Identificação pessoal
     DB_GetAlberData RS, p_chave, null
     If RS.EOF Then
       HTML = "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Alberguista não encontrado.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
     Else
        HTML = "<div align=center><center>"
        HTML = VbCrLf & HTML &"<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
        HTML = VbCrLf & HTML &"<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
        HTML = VbCrLf & HTML &"    <table width=""99%"" border=""0"">"
        HTML = VbCrLf & HTML &"      <tr><td align=""center"" colspan=""3""><font size=5><b>" & RS("nome") & "</b></font></td></tr>"
        HTML = VbCrLf & HTML &"      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Identificação</td>"
        HTML = VbCrLf & HTML &"      <tr valign=""top"">"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">Cateira:<br><b>" & Nvl(RS("carteira"),"---") & " </b></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">Emissão:<br><b>" & Nvl(FormataDataEdicao(RS("carteira_emissao")),"---") & " </b></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">Validade:<br><b>" & Nvl(FormataDataEdicao(RS("carteira_validade")),"---") & " </b></td>"
        HTML = VbCrLf & HTML &"      <tr valign=""top"">"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">Nome:<br><b>" & Nvl(RS("nome"),"---") & " </b></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">CPF:<br><b>" & Nvl(RS("cpf"),"---") & " </b></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">Data nascimento:<br><b>" & Nvl(FormataDataEdicao(RS("nascimento")),"---") & " </b></td>"
        HTML = VbCrLf & HTML &"      <tr valign=""top"">"
        If Nvl(RS("sexo"),"") = "M" Then
           HTML = VbCrLf & HTML &"          <td><font size=""1"">Sexo:<br><b>Masculino</b></td>"
        ElseIf Nvl(RS("sexo"),"") = "F" Then
           HTML = VbCrLf & HTML &"          <td><font size=""1"">Sexo:<br><b>Feminino</b></td>"
        Else
           HTML = VbCrLf & HTML &"          <td><font size=""1"">Sexo:<br><b>" & RS("nm_sexo") & " </b></td>"
        End If
        HTML = VbCrLf & HTML &"          <td><font size=""1"">RG número:<br><b>" & Nvl(RS("rg_numero"),"---") & " </b></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">RG emissor:<br><b>" & Nvl(RS("rg_emissor"),"---") & " </b></td>"
        HTML = VbCrLf & HTML &"      <tr valign=""top"">"
        HTML = VbCrLf & HTML &"          <td colspan=""3""><font size=""1"">Endereço:<br><b>" & Nvl(RS("endereco"),"---") & " </b></td>"
        HTML = VbCrLf & HTML &"      <tr valign=""top"">"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">Bairro:<br><b>" & Nvl(RS("bairro"),"---") & " </b></td>"
        If Nvl(RS("cidade"),"") > "" Then 
           HTML = VbCrLf & HTML &"          <td><font size=""1"">Cidade - UF:<br><b>" & Nvl(RS("cidade")& " - " &RS("uf"),"---") & " </b></td>"
        Else
           HTML = VbCrLf & HTML &"          <td><font size=""1"">Cidade-UF:<br><b>---</b></td>"
        End If
        HTML = VbCrLf & HTML &"          <td><font size=""1"">CEP:<br><b>" & Nvl(RS("cep"),"---") & " </b></td>"
        HTML = VbCrLf & HTML &"      <tr valign=""top"">"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">DDD:<br><b>" & Nvl(RS("ddd"),"---") & " </b></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">Telefone:<br><b>" & Nvl(RS("fone"),"---") & " </b></td>"
        HTML = VbCrLf & HTML &"      <tr valign=""top"">"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">e-Mail pessoal:<br><b>" & Nvl(RS("email"),"---") & " </b></td>"
        HTML = VbCrLf & HTML &"          <td><font size=""1"">e-Mail trabalho:<br><b>" & Nvl(RS("email_trabalho"),"---") & " </b></td>"        
        HTML = VbCrLf & HTML &"      <tr valign=""top"">"
        If Nvl(RS("conhece_albergue"),"") > "" Then
           HTML = VbCrLf & HTML &"          <td><font size=""1"">Conhece albergue da juventude?<br><b>" & RetornaSimNao(RS("conhece_albergue")) & " </b></td>"
        Else
           HTML = VbCrLf & HTML &"          <td><font size=""1"">Conhece albergue da juventude?<br><b>---</b></td>"
        End If
        If Nvl(RS("visitas"),"") > "" Then
           HTML = VbCrLf & HTML &"          <td><font size=""1"">Visitas como alberguista?<br><b>" & RetornaSimNao(RS("visitas")) & " </b></td>"
        Else
           HTML = VbCrLf & HTML &"          <td><font size=""1"">Visitas como alberguista?<br><b>---</b></td>"
        End If
        If Nvl(RS("classificacao"),"") > "" Then
           HTML = VbCrLf & HTML &"          <td><font size=""1"">Como classifica?<br><b>" & RetornaSimNao(RS("classificacao")) & " </b></td>"
        Else
           HTML = VbCrLf & HTML &"          <td><font size=""1"">Como classifica?<br><b>---</b></td>"
        End If        
        If Nvl(RS("destino"),"") > "" Then
           HTML = VbCrLf & HTML &"      <tr valign=""top"">"
           If cDbl(RS("destino")) = 1 Then
              HTML = VbCrLf & HTML &"          <td><font size=""1"">Destino da viagem:<br><b>Brasil</b></td>"
           ElseIf cDbl(RS("destino")) = 2 Then
              HTML = VbCrLf & HTML &"          <td><font size=""1"">Destino da viagem:<br><b>Europa</b></td>"
           ElseIf cDbl(RS("destino")) = 3 Then
              HTML = VbCrLf & HTML &"          <td><font size=""1"">Destino da viagem:<br><b>América</b></td>"
           ElseIf cDbl(RS("destino")) = 4 Then
              HTML = VbCrLf & HTML &"          <td><font size=""1"">Destino da viagem:<br><b>Ásia</b></td>"
           ElseIf cDbl(RS("destino")) = 5 Then
              HTML = VbCrLf & HTML &"          <td><font size=""1"">Destino da viagem:<br><b>Outros</b></td>"
              HTML = VbCrLf & HTML &"          <td><font size=""1"">Outro destino:<br><b>" & Nvl(RS("destino_outros"),"---") & " </b></td>"                
           End If
        End If
        If Nvl(RS("motivo_viagem"),"") > "" Then
           HTML = VbCrLf & HTML &"      <tr valign=""top"">"
           If cDbl(RS("motivo_viagem")) = 1 Then
              HTML = VbCrLf & HTML &"          <td><font size=""1"">Motivo da viagem:<br><b>Estudo</b></td>"
           ElseIf cDbl(RS("motivo_viagem")) = 2 Then
              HTML = VbCrLf & HTML &"          <td><font size=""1"">Motivo da viagem:<br><b>Trabalho</b></td>"
           ElseIf cDbl(RS("motivo_viagem")) = 3 Then
              HTML = VbCrLf & HTML &"          <td><font size=""1"">Motivo da viagem:<br><b>Turismo</b></td>"
           ElseIf cDbl(RS("motivo_viagem")) = 4 Then
              HTML = VbCrLf & HTML &"          <td><font size=""1"">Motivo da viagem:<br><b>Outros</b></td>"
              HTML = VbCrLf & HTML &"          <td><font size=""1"">Outro motivo:<br><b>" & Nvl(RS("motivo_outros"),"---") & " </b></td>"                              
           End If
        End If        
        If Nvl(RS("forma_conhece"),"") > "" Then
           HTML = VbCrLf & HTML &"      <tr valign=""top"">"
           If cDbl(RS("forma_conhece")) = 1 Then
              HTML = VbCrLf & HTML &"          <td><font size=""1"">Como conheceu?<br><b>Revista</b></td>"
           ElseIf cDbl(RS("forma_conhece")) = 2 Then
              HTML = VbCrLf & HTML &"          <td><font size=""1"">Como conheceu?<br><b>Jornal</b></td>"
           ElseIf cDbl(RS("forma_conhece")) = 3 Then
              HTML = VbCrLf & HTML &"          <td><font size=""1"">Como conheceu?<br><b>Rádio</b></td>"
           ElseIf cDbl(RS("forma_conhece")) = 4 Then
              HTML = VbCrLf & HTML &"          <td><font size=""1"">Como conheceu?<br><b>Convênio</b></td>"
           ElseIf cDbl(RS("forma_conhece")) = 5 Then
              HTML = VbCrLf & HTML &"          <td><font size=""1"">Como conheceu?<br><b>Eventos</b></td>"              
           ElseIf cDbl(RS("forma_conhece")) = 6 Then
              HTML = VbCrLf & HTML &"          <td><font size=""1"">Como conheceu?<br><b>Amigos</b></td>"
           ElseIf cDbl(RS("forma_conhece")) = 7 Then
              HTML = VbCrLf & HTML &"          <td><font size=""1"">Como conheceu?<br><b>Outros</b></td>"
              HTML = VbCrLf & HTML &"          <td><font size=""1"">Outro modo:<br><b>" & Nvl(RS("forma_outros"),"---") & " </b></td>"                
           End If
        End If
        HTML = VbCrLf & HTML &"</table>"
     End If
     DesconectaBD
  Else
    ScriptOpen "JavaScript"
    HTML = VbCrLf & HTML &" alert('Opção não disponível');"
    HTML = VbCrLf & HTML &" history.back(1);"
    ScriptClose
  End If
  
  ShowHTML "" & HTML
  
  Set HTML = Nothing
  
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

%>