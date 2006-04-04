<%

REM =========================================================================
REM Verifica sele��o do per�odo letivo e da regional de ensino
REM -------------------------------------------------------------------------
Sub VerificaParametros
    w_ImagemPadrao = "images/folder/SheetLittle.gif"

    If not (Session("Periodo") = "" or Session("Regional") = "") Then
       ScriptOpen "Javascript"
       ShowHTML "  alert('Voc� deve selecionar o periodo letivo e a regional de ensino desejada!');"
       DB_GetLinkData RS, Session("p_cliente"), "MESA"
       If Not RS.EOF Then
          If RS("IMAGEM") > "" Then
             ShowHTML "location.href='http://" & Request.ServerVariables("server_name") & "/siw/" & RS("LINK") & "&P1="&RS("P1")&"&P2="&RS("P2")&"&P3="&RS("P3")&"&P4="&RS("P4")&"&TP=<img src="&RS("IMAGEM")&" BORDER=0>"&RS("nome")&"&SG="&RS("SIGLA")&"';"
          Else
             ShowHTML "location.href='http://" & Request.ServerVariables("server_name") & "/siw/" & RS("LINK") & "&P1="&RS("P1")&"&P2="&RS("P2")&"&P3="&RS("P3")&"&P4="&RS("P4")&"&TP=<img src="&w_ImagemPadrao&" BORDER=0>"&RS("nome")&"&SG="&RS("SIGLA")&"';"
          End If
       Else
       End If
       ScriptClose
       DesconectaBD
       Response.End()
    End If
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Visualiza��o dos par�metros selecionados
REM -------------------------------------------------------------------------
Sub ExibeParametros(p_cliente)
  Dim RS_temp

  DB_GetUorgList RS_temp, p_cliente, null, null

  If Session("regional") > "" Then
     RS_temp.Filter = "codigo = '" & Session("regional") & "'"
  Else
     RS_temp.Filter = "informal = 'N' and codigo = null"
  End If
  
  ShowHTML "<center><b>Per�odo letivo: [" & Mid(Session("periodo"),1,4) & "] "
  ShowHTML "Regional: [" & RS_temp("nome") & "]</b></center>"

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o da UF
REM -------------------------------------------------------------------------
Sub SelecaoUF (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If chave = "AC" Then ShowHTML "          <option value=""AC"" SELECTED>Acre"                Else ShowHTML "          <option value=""AC"">Acre"                 End If
    If chave = "AL" Then ShowHTML "          <option value=""AL"" SELECTED>Alagoas"             Else ShowHTML "          <option value=""AL"">Alagoas"              End If
    If chave = "AP" Then ShowHTML "          <option value=""AP"" SELECTED>Amap�"               Else ShowHTML "          <option value=""AP"">Amap�"                End If
    If chave = "AM" Then ShowHTML "          <option value=""AM"" SELECTED>Amazonas"            Else ShowHTML "          <option value=""AM"">Amazonas"             End If
    If chave = "BA" Then ShowHTML "          <option value=""BA"" SELECTED>Bahia"               Else ShowHTML "          <option value=""BA"">Bahia"                End If
    If chave = "CE" Then ShowHTML "          <option value=""CE"" SELECTED>Cear�"               Else ShowHTML "          <option value=""CE"">Cear�"                End If
    If chave = "DF" Then ShowHTML "          <option value=""DF"" SELECTED>Distrito Federal"    Else ShowHTML "          <option value=""DF"">Distrito Federal"     End If
    If chave = "ES" Then ShowHTML "          <option value=""ES"" SELECTED>Esp�rito Santo"      Else ShowHTML "          <option value=""ES"">Esp�rito Santo"       End If
    If chave = "GO" Then ShowHTML "          <option value=""GO"" SELECTED>Goi�s"               Else ShowHTML "          <option value=""GO"">Goi�s"                End If
    If chave = "MA" Then ShowHTML "          <option value=""MA"" SELECTED>Maranh�o"            Else ShowHTML "          <option value=""MA"">Maranh�o"             End If
    If chave = "MT" Then ShowHTML "          <option value=""MT"" SELECTED>Mato Grosso"         Else ShowHTML "          <option value=""MT"">Mato Grosso"          End If
    If chave = "MS" Then ShowHTML "          <option value=""MS"" SELECTED>Mato Grosso do Sul"  Else ShowHTML "          <option value=""MS"">Mato Grosso do Sul"   End If
    If chave = "MG" Then ShowHTML "          <option value=""MG"" SELECTED>Minas Gerais"        Else ShowHTML "          <option value=""MG"">Minas Gerais"         End If
    If chave = "PA" Then ShowHTML "          <option value=""PA"" SELECTED>Par�"                Else ShowHTML "          <option value=""PA"">Par�"                 End If
    If chave = "PB" Then ShowHTML "          <option value=""PB"" SELECTED>Para�ba"             Else ShowHTML "          <option value=""PB"">Para�ba"              End If
    If chave = "PR" Then ShowHTML "          <option value=""PR"" SELECTED>Paran�"              Else ShowHTML "          <option value=""PR"">Paran�"               End If
    If chave = "PE" Then ShowHTML "          <option value=""PE"" SELECTED>Pernambuco"          Else ShowHTML "          <option value=""PE"">Pernambuco"           End If
    If chave = "PI" Then ShowHTML "          <option value=""PI"" SELECTED>Piau�"               Else ShowHTML "          <option value=""PI"">Piau�"                End If
    If chave = "RJ" Then ShowHTML "          <option value=""RJ"" SELECTED>Rio de Janeiro"      Else ShowHTML "          <option value=""RJ"">Rio de Janeiro"       End If
    If chave = "RN" Then ShowHTML "          <option value=""RN"" SELECTED>Rio Grande do Norte" Else ShowHTML "          <option value=""RN"">Rio Grande do Norte"  End If
    If chave = "RS" Then ShowHTML "          <option value=""RS"" SELECTED>Rio Grande do Sul"   Else ShowHTML "          <option value=""RS"">Rio Grande do Sul"    End If
    If chave = "RO" Then ShowHTML "          <option value=""RO"" SELECTED>Rond�nia"            Else ShowHTML "          <option value=""RO"">Rond�nia"             End If
    If chave = "RR" Then ShowHTML "          <option value=""RR"" SELECTED>Roraima"             Else ShowHTML "          <option value=""RR"">Roraima"              End If
    If chave = "SC" Then ShowHTML "          <option value=""SC"" SELECTED>Santa Catarina"      Else ShowHTML "          <option value=""SC"">Santa Catarina"       End If
    If chave = "SP" Then ShowHTML "          <option value=""SP"" SELECTED>S�o Paulo"           Else ShowHTML "          <option value=""SP"">S�o Paulo"            End If
    If chave = "TO" Then ShowHTML "          <option value=""TO"" SELECTED>Tocantins"           Else ShowHTML "          <option value=""TO"">Tocantins"            End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o da op��o de conhecer albergues
REM -------------------------------------------------------------------------
Sub SelecaoConhece_Albergue (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If chave = "S" Then ShowHTML "          <option value=""S"" SELECTED>Sim" Else ShowHTML "          <option value=""S"">Sim" End If
    If chave = "N" Then ShowHTML "          <option value=""N"" SELECTED>N�o" Else ShowHTML "          <option value=""N"">N�o" End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o da op��o de quantidade de visitas
REM -------------------------------------------------------------------------
Sub SelecaoVisitas (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If chave = "0" Then ShowHTML "          <option value=""1"" SELECTED>Nenhuma vez"     Else ShowHTML "          <option value=""0"">Nenhuma vez"           End If
    If chave = "1" Then ShowHTML "          <option value=""1"" SELECTED>1 vez"           Else ShowHTML "          <option value=""1"">1 vez"           End If
    If chave = "2" Then ShowHTML "          <option value=""2"" SELECTED>2 vezes"         Else ShowHTML "          <option value=""2"">2 vezes"         End If
    If chave = "9" Then ShowHTML "          <option value=""9"" SELECTED>Mais de 2 vezes" Else ShowHTML "          <option value=""9"">Mais de 2 vezes" End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o da classificacao
REM -------------------------------------------------------------------------
Sub SelecaoClassificacao (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If chave = "1" Then ShowHTML "          <option value=""1"" SELECTED>Muito bom"       Else ShowHTML "          <option value=""1"">Muito bom"       End If
    If chave = "2" Then ShowHTML "          <option value=""2"" SELECTED>Bom"             Else ShowHTML "          <option value=""2"">Bom"             End If
    If chave = "3" Then ShowHTML "          <option value=""3"" SELECTED>Regular"         Else ShowHTML "          <option value=""3"">Regular"         End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o do destino
REM -------------------------------------------------------------------------
Sub SelecaoDestino (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If chave = "1" Then ShowHTML "          <option value=""1"" SELECTED>Brasil"    Else ShowHTML "          <option value=""1"">Brasil"    End If
    If chave = "2" Then ShowHTML "          <option value=""2"" SELECTED>Europa"    Else ShowHTML "          <option value=""2"">Europa"    End If
    If chave = "3" Then ShowHTML "          <option value=""3"" SELECTED>Am�rica"   Else ShowHTML "          <option value=""3"">Am�rica"   End If
    If chave = "4" Then ShowHTML "          <option value=""4"" SELECTED>�sia"      Else ShowHTML "          <option value=""4"">�sia"      End If
    If chave = "5" Then ShowHTML "          <option value=""5"" SELECTED>Outros"    Else ShowHTML "          <option value=""5"">Outros"    End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o do motivo da viagem
REM -------------------------------------------------------------------------
Sub SelecaoMotivo_Viagem (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If chave = "1" Then ShowHTML "          <option value=""1"" SELECTED>Estudo"    Else ShowHTML "          <option value=""1"">Estudo"    End If
    If chave = "2" Then ShowHTML "          <option value=""2"" SELECTED>Trabalho"  Else ShowHTML "          <option value=""2"">Trabalho"  End If
    If chave = "3" Then ShowHTML "          <option value=""3"" SELECTED>Turismo"   Else ShowHTML "          <option value=""3"">Turismo"   End If
    If chave = "4" Then ShowHTML "          <option value=""4"" SELECTED>Outros"    Else ShowHTML "          <option value=""4"">Outros"    End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o da forma que conheceu o movimento
REM -------------------------------------------------------------------------
Sub SelecaoForma_Conhece (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If chave = "1" Then ShowHTML "          <option value=""1"" SELECTED>Revista"   Else ShowHTML "          <option value=""1"">Revista"   End If
    If chave = "2" Then ShowHTML "          <option value=""2"" SELECTED>Jornal"    Else ShowHTML "          <option value=""2"">Jornal"    End If
    If chave = "3" Then ShowHTML "          <option value=""3"" SELECTED>R�dio"     Else ShowHTML "          <option value=""3"">R�dio"     End If
    If chave = "4" Then ShowHTML "          <option value=""4"" SELECTED>Conv�nio"  Else ShowHTML "          <option value=""4"">Conv�nio"  End If
    If chave = "5" Then ShowHTML "          <option value=""5"" SELECTED>Eventos"   Else ShowHTML "          <option value=""5"">Eventos"   End If
    If chave = "6" Then ShowHTML "          <option value=""6"" SELECTED>Amigos"    Else ShowHTML "          <option value=""6"">Amigos"    End If
    If chave = "7" Then ShowHTML "          <option value=""7"" SELECTED>Outros"    Else ShowHTML "          <option value=""7"">Outros"    End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

