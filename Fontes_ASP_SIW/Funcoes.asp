<%
Session.LCID = 1046

REM =========================================================================
REM Declaração inicial para páginas OLE com Word
REM -------------------------------------------------------------------------
Sub headerWord (p_orientation)
  Response.ContentType = "application/msword"
  ShowHTML "<html xmlns:o=""urn:schemas-microsoft-com:office:office"" "
  ShowHTML "xmlns:w=""urn:schemas-microsoft-com:office:word"" "
  ShowHTML "xmlns=""http://www.w3.org/TR/REC-html40""> "
  ShowHTML "<head> "
  ShowHTML "<meta http-equiv=Content-Type content=""text/html; charset=windows-1252""> "
  ShowHTML "<meta name=ProgId content=Word.Document> "
  ShowHTML "<!--[if gte mso 9]><xml> "
  ShowHTML " <w:WordDocument> "
  ShowHTML "  <w:View>Print</w:View> "
  ShowHTML "  <w:Zoom>BestFit</w:Zoom> "
  ShowHTML "  <w:SpellingState>Clean</w:SpellingState> "
  ShowHTML "  <w:GrammarState>Clean</w:GrammarState> "
  ShowHTML "  <w:HyphenationZone>21</w:HyphenationZone> "
  ShowHTML "  <w:Compatibility> "
  ShowHTML "   <w:BreakWrappedTables/> "
  ShowHTML "   <w:SnapToGridInCell/> "
  ShowHTML "   <w:WrapTextWithPunct/> "
  ShowHTML "   <w:UseAsianBreakRules/> "
  ShowHTML "  </w:Compatibility> "
  ShowHTML "  <w:BrowserLevel>MicrosoftInternetExplorer4</w:BrowserLevel> "
  ShowHTML " </w:WordDocument> "
  ShowHTML "</xml><![endif]--> "
  ShowHTML "<style> "
  ShowHTML "<!-- "
  ShowHTML " /* Style Definitions */ "
  ShowHTML "@page Section1 "
  If uCase(Nvl(p_orientation,"LANDSCAPE")) = "PORTRAIT" Then
     ShowHTML "    {mso-page-orientation:portrait; "
     ShowHTML "    margin:1.0cm 1.0cm 2.0cm 2.0cm; "
     ShowHTML "    mso-header-margin:35.4pt; "
     ShowHTML "    mso-footer-margin:35.4pt; "
     ShowHTML "    mso-paper-source:0;} "
  Else
     ShowHTML "    {size:11.0in 8.5in; "
     ShowHTML "    mso-page-orientation:landscape; "
     ShowHTML "    margin:60.85pt 1.0cm 60.85pt 2.0cm; "
     ShowHTML "    mso-header-margin:35.4pt; "
     ShowHTML "    mso-footer-margin:35.4pt; "
     ShowHTML "    mso-paper-source:0;} "
  End If
  ShowHTML "div.Section1 "
  ShowHTML "    {page:Section1;} "
  ShowHTML "--> "
  ShowHTML "</style> "
  ShowHTML "</head> "
  BodyOpenClean "onLoad=document.focus();"
  ShowHTML "<div class=Section1> "
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem do cabeçalho de documentos Word
REM -------------------------------------------------------------------------
Sub CabecalhoWord (p_cliente, p_titulo, p_pagina)
  Dim l_rs
  Set l_rs = Server.CreateObject("ADODB.RecordSet")
  
  DB_GetCustomerData l_RS, p_cliente
  ShowHTML "<TABLE WIDTH=""100%"" BORDER=0>"
  ShowHTML "  <TR>"
  ShowHTML "    <TD ROWSPAN=3><IMG ALIGN=""LEFT"" SRC=""" & conFileVirtual & w_cliente & "/img/" & l_RS("logo") & """ width=56 height=67>"
  ShowHTML "    <TD ALIGN=""RIGHT""><B><FONT SIZE=5 COLOR=""#000000"">" & p_titulo &  "</FONT>"
  ShowHTML "  </TR>"
  ShowHTML "  <TR><TD ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">" & DataHora() & "</B></TD></TR>"
  ShowHTML "  <TR><TD ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">Página: " & p_pagina & "</B></TD></TR>"
  ShowHTML "  <TR><TD colspan=2><HR></td></tr>"
  ShowHTML "</TABLE>"
  Set l_rs = Nothing
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem de link para ordenação, usada nos títulos de colunas
REM -------------------------------------------------------------------------
Function LinkOrdena (p_label, p_campo)
  Dim l_item, l_img, l_ordena, l_string
  For Each l_Item IN Request.Form
     If Request(l_Item) > "" and (uCase(Mid(l_item,1,2)) = "W_" or uCase(Mid(l_item,1,2)) = "P_") Then
        If uCase(l_item) = "P_ORDENA" Then
           l_ordena = uCase(Request(l_Item))
        Else
           l_string = l_string & "&" & l_Item & "=" & Request(l_Item)
        End If
     End If
  Next
  For Each l_Item IN Request.QueryString
     If Request(l_Item) > "" and (uCase(Mid(l_item,1,2)) = "W_" or uCase(Mid(l_item,1,2)) = "P_") Then
        If uCase(l_item) = "P_ORDENA" Then
           l_ordena = uCase(Request(l_Item))
        Else
           l_string = l_string & "&" & l_Item & "=" & Request(l_Item)
        End If
     End If
  Next
  If uCase(p_campo) = replace(replace(uCase(l_ordena)," ASC","")," DESC","") Then
     If InStr(uCase(l_ordena)," DESC") > 0 Then
        l_string = l_string & "&p_ordena=" & p_campo & " asc&"
        l_img    = "&nbsp;<img src=images/down.gif width=8 height=8 border=0 align=""absmiddle"">"
     Else
        l_string = l_string & "&p_ordena=" & p_campo & " desc&"
        l_img    = "&nbsp;<img src=images/up.gif width=8 height=8 border=0 align=""absmiddle"">"
     End If
  Else
     l_string = l_string & "&p_ordena=" & p_campo
  End If

  LinkOrdena = "<a class=""ss"" href="""&w_dir&w_pagina&par&"&R="&R&"&O="&O&"&P1="&P1&"&P2="&P2&"&P3=1"&"&P4="&P4&"&TP="&TP&"&SG="&SG&l_string&""" title=""Ordena a listagem por esta coluna."">" & p_label & "</a>" & l_img

  Set l_ordena = Nothing
  Set l_string = Nothing
  Set l_item   = Nothing
  Set l_img    = Nothing
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem do cabeçalho de relatórios
REM -------------------------------------------------------------------------
Sub CabecalhoRelatorio (p_cliente, p_titulo)
  Dim l_rs
  Set l_rs = Server.CreateObject("ADODB.RecordSet")
  DB_GetCustomerData l_RS, p_cliente
  ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" SRC=""" & "files\" & w_cliente & "\img\" & l_RS("logo") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
  ShowHTML p_titulo
  ShowHTML "</FONT><TR><TD ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">" & DataHora() & "</B></TD></TR>"
  ShowHTML "</FONT></B></TD></TR></TABLE>"
  ShowHTML "<HR>"
  Set l_rs = Nothing
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da barra de navegação de recordsets
REM -------------------------------------------------------------------------
Sub MontaBarra (p_link, p_PageCount, p_AbsolutePage, p_PageSize, p_RecordCount)

  ShowHTML "<SCRIPT LANGUAGE=""JAVASCRIPT"">"
  ShowHTML "  function pagina (pag) {"
  ShowHTML "    document.Barra.P3.value = pag;"
  ShowHTML "    document.Barra.submit();"
  ShowHTML "  }"
  ShowHTML "</SCRIPT>"
  ShowHTML "<FORM ACTION=""" & p_link &""" METHOD=""POST"" name=""Barra"">"
  ShowHTML "<input type=""Hidden"" name=""P4"" value=""" & p_pagesize & """>"
  ShowHTML "<input type=""Hidden"" name=""P3"" value="""">"
  ShowHTML MontaFiltro("POST")
  If p_PageSize < p_RecordCount Then
     If p_PageCount = p_AbsolutePage Then
        ShowHTML "<span class=""btm""><br>" & (p_RecordCount-((p_PageCount-1)*p_PageSize)) & " linhas apresentadas de " & p_RecordCount & " linhas"
     Else
        ShowHTML "<span class=""btm""><br>" & p_PageSize & " linhas apresentadas de " & p_RecordCount & " linhas"
     End If
     ShowHTML "<br>na página " & p_AbsolutePage & " de " & p_PageCount & " páginas"
     If p_AbsolutePage > 1 Then
        ShowHTML "<br>[<A class=""ss"" TITLE=""Primeira página"" HREF=""javascript:pagina(1)"" onMouseOver=""window.status='Primeira (1/" & p_PageCount& ")'; return true"" onMouseOut=""window.status=''; return true"">Primeira</A>]&nbsp;"
        ShowHTML "[<A class=""ss"" TITLE=""Página anterior"" HREF=""javascript:pagina(" & p_AbsolutePage-1 & ")"" onMouseOver=""window.status='Anterior (" & p_AbsolutePage-1 & "/" & p_PageCount& ")'; return true"" onMouseOut=""window.status=''; return true"">Anterior</A>]&nbsp;"
     Else
        ShowHTML "<br>[Primeira]&nbsp;"
        ShowHTML "[Anterior]&nbsp;"
     End If
     If p_PageCount = p_AbsolutePage Then
        ShowHTML "[Próxima]&nbsp;"
        ShowHTML "[Última]"
     Else
        ShowHTML "[<A class=""ss"" TITLE=""Página seguinte"" HREF=""javascript:pagina(" & p_AbsolutePage+1 & ")""  onMouseOver=""window.status='Próxima (" & p_AbsolutePage+1 & "/" & p_PageCount& ")'; return true"" onMouseOut=""window.status=''; return true"">Próxima</A>]&nbsp;"
        ShowHTML "[<A class=""ss"" TITLE=""Última página"" HREF=""javascript:pagina(" & p_PageCount & ")""  onMouseOver=""window.status='Última (" & p_PageCount & "/" & p_PageCount& ")'; return true"" onMouseOut=""window.status=''; return true"">Última</A>]"
     End If
     ShowHTML "</span>"
  End If
  ShowHtml "</FORM>"

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Retorna o nível de acesso que o usuário tem à solicitação informada
REM -------------------------------------------------------------------------
Function SolicAcesso (p_solicitacao, p_usuario)

  Dim l_acesso

  DB_GetSolicAcesso p_solicitacao, p_usuario, l_acesso
  SolicAcesso = l_acesso
  
  Set l_acesso = Nothing
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Gera um CNPJ ou um CPF para pessoas físicas ou jurídicas, que não os têm
REM -------------------------------------------------------------------------
Function GeraCpfEspecial (p_tipo)

  Dim l_valor

  DB_GetCNPJCPF p_tipo, l_valor
  GeraCpfEspecial = l_valor
  
  Set l_valor = Nothing
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Retorna uma parte qualquer de uma linha delimitada
REM -------------------------------------------------------------------------
Function Piece (p_line, p_delimiter, p_separator, p_position)

  Dim l_i, l_result, l_actual

  l_actual = p_line
  l_result = p_line
  If Nvl(p_separator,"") > "" Then
     For l_i = 1 TO p_position
        If Instr(l_actual,p_separator) > 0 Then
           l_result = Mid(l_actual, 1,Instr(l_actual,p_separator)-1)
           l_actual = Mid(l_actual, Instr(l_actual,p_separator)+1, len(l_actual))
           If l_i = p_position - 1 and Instr(l_actual,p_separator) = 0 Then l_actual = l_actual & ";" End If
        Else
           Piece = ""
           Exit For
        End If
     Next
  End If
  
  Piece = l_result
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da URL com os parâmetros de filtragem
REM -------------------------------------------------------------------------
Function MontaFiltro (p_method)
  Dim l_string, l_item
  If Instr("GET,POST",uCase(p_method)) > 0 Then
     l_string = ""
     If uCase(p_method) <> "UL" Then
        For Each l_Item IN Request.Form
           If Mid(l_item,1,2) = "p_" and Request(l_Item) > "" Then
              If uCase(p_method) = "GET" Then
                  l_string = l_string & "&" & l_Item & "=" & Request(l_Item)
              ElseIf uCase(p_method) = "POST" Then
                  l_string = l_string & "<INPUT TYPE=""HIDDEN"" NAME=""" & l_Item & """ VALUE=""" & Request(l_Item) & """>" & VbCrLf
              End If
           End If
        Next
        For Each l_Item IN Request.QueryString
           If Mid(l_item,1,2) = "p_" and Request(l_Item) > "" Then
              If uCase(p_method) = "GET" Then
                  l_string = l_string & "&" & l_Item & "=" & Request(l_Item)
              ElseIf uCase(p_method) = "POST" Then
                  l_string = l_string & "<INPUT TYPE=""HIDDEN"" NAME=""" & l_Item & """ VALUE=""" & Request(l_Item) & """>" & VbCrLf
              End If
           End If
        Next
     Else
        For Each l_Item IN ul.Form
           If Mid(l_item,1,2) = "p_" and ul.Form(l_Item) > "" Then
              If uCase(p_method) = "GET" Then
                  l_string = l_string & "&" & l_Item & "=" & ul.Form(l_Item)
              ElseIf uCase(p_method) = "POST" Then
                  l_string = l_string & "<INPUT TYPE=""HIDDEN"" NAME=""" & l_Item & """ VALUE=""" & ul.Form(l_Item) & """>" & VbCrLf
              End If
           End If
        Next
     End If
  End If
  
  MontaFiltro = l_string
  
  Set l_string = Nothing
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Exibe o conteúdo da querystring, do formulário e das variáveis de sessão
REM -------------------------------------------------------------------------
Sub ExibeVariaveis
  Dim w_item
  ShowHTML "<DT><font face=""Verdana"" size=1><b>Dados da querystring:</b></font>"
  For w_Item = 1 to Request.QueryString.Count
      If InStr(uCase(Request.QueryString.Key(w_item)),"W_ASSINATURA") = 0 Then
         ShowHTML "<DD><FONT FACE=""courier"" size=1>" & Request.QueryString.Key(w_item) & " => [" & Request.QueryString(w_item) & "]</font><br>"
      End If
  Next
  ShowHTML "</DT>"
  ShowHTML "<DT><font face=""Verdana"" size=1><b>Dados do formulário:</b></font>"
  ShowHTML "<DD><FONT FACE=""courier"" size=1>EncType => [" & Request.ServerVariables("http_content_type") & "]</font><br>"
  For w_Item = 1 to Request.Form.Count
      If InStr(uCase(Request.Form.Key(w_item)),"W_ASSINATURA") = 0 Then
         ShowHTML "<DD><FONT FACE=""courier"" size=1>" & Request.Form.Key(w_item) & " => [" & Request.Form(w_item) & "]</font><br>"
      End If
  Next
  ShowHTML "</DT>"
  ShowHTML "<DT><font face=""Verdana"" size=1><b>Variáveis de sessão</b></font>:"
  For w_Item = 1 TO Session.Contents.Count
      If InStr(uCase(w_item),"SENHA") = 0 Then
         ShowHTML "<DD><FONT FACE=""courier"" size=1>" & Session.Contents.Key(w_Item) & " => [" & Session.Contents(w_Item) & "]</font><br>"
      End If
  Next
  ShowHTML "</DT>"
  Set w_item = Nothing
  Response.End()
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da URL com os dados de uma pessoa
REM -------------------------------------------------------------------------
Function ExibePessoa (p_dir, p_cliente, p_pessoa, p_tp, p_nome)
  Dim l_string
  If Nvl(p_nome,"") = "" Then
     l_string="---"
  Else
     l_string = l_string & "<A class=""hl"" HREF=""#"" onClick=""window.open('" & p_dir & "Seguranca.asp?par=TELAUSUARIO&w_cliente=" & p_cliente & "&w_sq_pessoa=" & p_pessoa & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & p_TP & "&SG=" & "','Pessoa','width=780,height=300,top=10,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no'); return false;"" title=""Clique para exibir os dados desta pessoa!"">" & p_nome & "</A>"
  End If
  ExibePessoa = l_string
  
  Set l_string = Nothing
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da URL com os dados de uma pessoa
REM -------------------------------------------------------------------------
Function ExibeUnidade (p_dir, p_cliente, p_unidade, p_sq_unidade, p_tp)
  Dim l_string
  If Nvl(p_unidade,"") = "" Then
     l_string="---"
  Else
     l_string = l_string & "<A class=""hl"" HREF=""#"" onClick=""window.open('" & p_dir & "Seguranca.asp?par=TELAUNIDADE&w_cliente=" & p_cliente & "&w_sq_unidade=" & p_sq_unidade & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & p_TP & "&SG=" & "','Unidade','width=780,height=300,top=10,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no'); return false;"" title=""Clique para exibir os dados desta unidade!"">" & p_unidade & "</A>"
  End If
  ExibeUnidade = l_string
  
  Set l_string = Nothing
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da URL com os dados da etapa
REM -------------------------------------------------------------------------
Function ExibeEtapa (O, p_chave, p_chave_aux, p_tipo, p_P1, p_etapa, p_tp, p_sg)
  Dim l_string
  If Nvl(p_etapa,"") = "" Then
     l_string="---"
  Else 
     l_string = l_string & "<A class=""hl"" HREF=""#"" onClick=""window.open('Projeto.asp?par=AtualizaEtapa&w_chave=" & p_chave & "&O=" & O & "&w_chave_aux=" & p_chave_aux & "&w_tipo=" &p_tipo& "&P1=" & p_P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & p_TP & "&SG=" & p_sg & "','Etapa','width=780,height=350,top=50,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no'); return false;"" title=""Clique para exibir os dados!"">" & p_etapa & "</A>"
  End If
  ExibeEtapa = l_string
  
  Set l_string = Nothing
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da URL com os parâmetros de filtragem quando o for UPLOAD
REM -------------------------------------------------------------------------
Function MontaFiltroUpload (p_Form)
  Dim l_string, l_item
  l_string = ""
  For Each l_Item IN p_Form
     If Mid(l_item,1,2) = "p_" and l_item.value > "" Then
        l_string = l_string & "&" & l_Item & "=" & l_item.value
     End If
  Next
  
  MontaFiltroUpload = l_string
  
  Set l_string = Nothing
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Monta uma string para indicar a opção selecionada
REM -------------------------------------------------------------------------
Function OpcaoMenu(p_sq_menu)
   Dim l_texto, l_cont

   DB_GetMenuUpper RS, p_sq_menu
   l_texto = ""
   l_cont  = 0
   
   While NOT RS.EOF
      l_Cont = l_Cont + 1
      If l_Cont = 1 Then
         l_texto = "<font color=""#FF0000"">" & RS("nome") & "</font> -> " & l_texto
      Else
         l_texto = RS("nome") & " -> " & l_texto
      End If
      RS.MoveNext
   Wend
   
   OpcaoMenu = l_texto
   
   Set l_cont  = Nothing
   Set l_texto = Nothing
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina que monta string da opção selecionada
REM -------------------------------------------------------------------------
Function MontaStringOpcao(p_sq_menu)
  Dim RS1, w_contaux, w_texto
  DB_GetLinkDataParents RS1, p_sq_menu
  w_texto = ""
  w_Cont  = RS1.RecordCount
  While NOT RS1.EOF
     w_contaux = w_contaux + 1
     If w_contaux = 1 Then
        w_texto = "<font color=""#FF0000"">" & RS1("descricao") & "</font> -> " & w_texto
     Else
        w_texto = RS1("descricao") & " -> " & w_texto
     End If
     RS1.MoveNext
  Wend
  MontaStringOpcao = Mid(w_texto,1,Len(w_texto)-4)
  Set RS1 = Nothing
End Function
REM =========================================================================
REM Final da rotina 
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina que monta número de ordem da etapa do projeto
REM -------------------------------------------------------------------------
Function MontaOrdemEtapa(p_chave)
  Dim RSQuery, w_contaux, w_texto
  DB_GetEtapaDataParents RSQuery, p_chave
  w_texto = ""
  w_Cont  = RSQuery.RecordCount
  While NOT RSQuery.EOF
     w_contaux = w_contaux + 1
     If w_contaux = 1 Then
        w_texto = RSQuery("ordem") & "." & w_texto
     Else
        w_texto = RSQuery("ordem") & "." & w_texto
     End If
     RSQuery.MoveNext
  Wend
  MontaOrdemEtapa = Mid(w_texto,1,Len(w_texto)-1)
  Set RSQuery = Nothing
End Function
REM =========================================================================
REM Final da rotina 
REM -------------------------------------------------------------------------

REM =========================================================================
REM Converte CFLF para <BR>
REM -------------------------------------------------------------------------
Function CRLF2BR(expressao)
   If IsNull(expressao) or expressao = "" Then
      CRLF2BR = ""
   Else
      CRLF2BR = Replace(expressao, VbCrLf, "<BR>")
   End If
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Trata valores nulos
REM -------------------------------------------------------------------------
Function Nvl(expressao,valor)
   If IsNull(expressao) or expressao = "" Then
      Nvl = valor
   Else
      Nvl = expressao
   End If
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Retorna valores nulos se chegar cadeia vazia
REM -------------------------------------------------------------------------
Function Tvl(expressao)
   If IsNull(expressao) or expressao = "" Then
      Tvl = null
   Else
      Tvl = expressao
   End If
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------.

REM =========================================================================
REM Retorna valores nulos se chegar cadeia vazia
REM -------------------------------------------------------------------------
Function Cvl(expressao)
   If IsNull(expressao) or expressao = "" Then
      Cvl = 0
   Else
      Cvl = expressao
   End If
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Retorna o caminho físico para o diretório  do cliente informado
REM -------------------------------------------------------------------------
Function DiretorioCliente(p_Cliente)
   DiretorioCliente = Request.ServerVariables("APPL_PHYSICAL_PATH") & "files\" & p_cliente
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------.

REM =========================================================================
REM Montagem de URL a partir da sigla da opção do menu
REM -------------------------------------------------------------------------
Function MontaURL (p_sigla)
  Dim RS_montaUrl, l_imagem, l_imagemPadrao
  Set RS_montaUrl = Server.CreateObject("ADODB.RecordSet")

  DB_GetLinkData RS_montaUrl, Session("p_cliente"), p_sigla

  l_ImagemPadrao = "images/folder/SheetLittle.gif"
  
  If RS_montaUrl.EOF Then
     MontaURL = ""
  Else
     If RS_montaUrl("IMAGEM") > "" Then l_Imagem = RS_montaUrl("IMAGEM") Else l_Imagem = l_ImagemPadrao End If
     MontaURL = RS_montaUrl("LINK") & "&P1="&RS_montaUrl("P1")&"&P2="&RS_montaUrl("P2")&"&P3="&RS_montaUrl("P3")&"&P4="&RS_montaUrl("P4")&"&TP=<img src="&l_imagem&" BORDER=0>"&RS_montaUrl("NOME")&"&SG="&RS_montaUrl("SIGLA")
  End If
  
  Set RS_MontaURL = Nothing
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem de cabeçalho padrão de formulário
REM -------------------------------------------------------------------------
Sub AbreForm (p_Name,p_Action,p_Method,p_onSubmit,p_Target,p_P1,p_P2,p_P3,p_P4,p_TP,p_SG,p_R,p_O)
    If IsNull(p_Target) Then
       ShowHTML "<FORM action=""" & p_action & """ method=""" & p_Method & """ name=""" & p_Name & """ onSubmit=""" & p_onSubmit & """>"
    Else
       ShowHTML "<FORM action=""" & p_action & """ method=""" & p_Method & """ name=""" & p_Name & """ onSubmit=""" & p_onSubmit & """ target=""" & p_Target & """>"
    End If
    ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & p_P1 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & p_P2 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & p_P3 & """>"
    If Not IsNull(p_P4) Then
       ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & p_P4 & """>"
    End If
    ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & p_TP & """>"
    ShowHTML "<INPUT type=""hidden"" name=""SG"" value=""" & p_SG & """>"
    ShowHTML "<INPUT type=""hidden"" name=""R""  value=""" & p_R  & """>"
    ShowHTML "<INPUT type=""hidden"" name=""O""  value=""" & p_O  & """>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de sexo
REM -------------------------------------------------------------------------
Sub SelecaoSexo (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If Nvl(chave,"") = "M" Then
       ShowHTML "          <option value=""F"">Feminino"
       ShowHTML "          <option value=""M"" SELECTED>Masculino"
    ElseIf Nvl(chave,"") = "F" Then
       ShowHTML "          <option value=""F"" SELECTED>Feminino"
       ShowHTML "          <option value=""M"">Masculino"
    Else
       ShowHTML "          <option value=""F"">Feminino"
       ShowHTML "          <option value=""M"">Masculino"
    End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de formato
REM -------------------------------------------------------------------------
Sub SelecaoFormato (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If Nvl(chave,"") = "W" Then
       ShowHTML "          <option value=""A"">Arquivo"
       ShowHTML "          <option value=""W"" SELECTED>Web service"
    ElseIf Nvl(chave,"") = "A" Then
       ShowHTML "          <option value=""A"" SELECTED>Arquivo"
       ShowHTML "          <option value=""W"">Web service"
    Else
       ShowHTML "          <option value=""A"">Arquivo"
       ShowHTML "          <option value=""W"">Web service"
    End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem de campo do tipo radio com padrão Não
REM -------------------------------------------------------------------------
Sub MontaRadioNS (Label, Chave, Campo)
    ShowHTML "          <td><font size=""1"">"
    If Nvl(Label,"") > "" Then
       ShowHTML Label & "</b><br>"
    End If
    If chave = "S" or chave = "Sim" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""" & campo & """ value=""S"" checked> Sim <input " & w_Disabled & " type=""radio"" name=""" & campo & """ value=""N""> Não"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""" & campo & """ value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""" & campo & """ value=""N"" checked> Não"
    End If
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem de campo do tipo radio com padrão Sim
REM -------------------------------------------------------------------------
Sub MontaRadioSN (Label, Chave, Campo)
    ShowHTML "          <td><font size=""1"">"
    If Nvl(Label,"") > "" Then
       ShowHTML Label & "</b><br>"
    End If
    If chave = "N" or chave = "Não" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""" & campo & """ value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""" & campo & """ value=""N"" checked> Não"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""" & campo & """ value=""S"" checked> Sim <input " & w_Disabled & " type=""radio"" name=""" & campo & """ value=""N""> Não"
    End If
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de pessoas
REM -------------------------------------------------------------------------
Sub SelecaoPessoa1 (label, accesskey, hint, chave, chaveAux, campo, restricao)

    Dim w_nm_usuario
    ShowHTML "<INPUT type=""hidden"" name=""" & campo & """ value=""" & chave &""">"
    If cDbl(nvl(chave,0)) > 0 Then
       DB_GetPersonList RS, w_cliente, chave, restricao, null, null, null, null
       RS.Filter = "sq_pessoa = " & chave
       RS.Sort = "nome_resumido"
       w_nm_usuario = RS("nome_resumido") & " (" & RS("sg_unidade") & ")"
    End If
    If IsNull(hint) Then
       ShowHTML "      <td valign=""top""><font size=""1""><b>" & Label & "</b><br>"
       ShowHTML "          <input READONLY ACCESSKEY=""" & accesskey & """ CLASS=""sti"" type=""text"" name=""" & campo & "_nm" & """ SIZE=""20"" VALUE=""" & w_nm_usuario & """>"
    Else
       ShowHTML "      <td valign=""top""title=""" & hint & """><font size=""1""><b>" & Label & "</b><br>"
       ShowHTML "          <input READONLY ACCESSKEY=""" & accesskey & """ CLASS=""sti"" type=""text"" name=""" & campo & "_nm" & """ SIZE=""20"" VALUE=""" & w_nm_usuario & """>"
    End If
    ShowHTML "              <a class=""ss"" href=""#"" onClick=""window.open('" & w_dir_volta & "Seguranca.asp?par=BuscaUsuario&TP=" & TP & "&w_cliente=" &w_cliente& "&ChaveAux=" &ChaveAux& "&restricao=" &restricao& "&campo=" &campo& "','Usuário','top=10,left=10,width=780,height=400,toolbar=yes,status=yes,resizable=yes,scrollbars=yes'); return false;"" title=""Clique aqui para selecionar o usuário.""><img src=images/Folder/Explorer.gif border=0 height=15 width=15></a>"
    ShowHTML "              <a class=""ss"" href=""#"" onClick=""document.Form." & campo & "_nm" & ".value=''; document.Form." & campo & ".value=''; return false;"" title=""Clique aqui para apagar o valor deste campo.""><img src=images/Folder/Recyfull.gif border=0 height=15 width=15></a>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de pessoas
REM -------------------------------------------------------------------------
Sub SelecaoPessoa (label, accesskey, hint, chave, chaveAux, campo, restricao)
    DB_GetPersonList RS, w_cliente, ChaveAux, restricao, null, null, null, null
    RS.Sort = "nome_resumido"
    If restricao = "TTUSURAMAL" then
       RS.filter = "ativo='S'"
    End If
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_pessoa"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_pessoa") & """ SELECTED>" & RS("NOME_RESUMIDO") & " (" & RS("SG_UNIDADE") & ")"
       Else
          ShowHTML "          <option value=""" & RS("sq_pessoa") & """>" & RS("NOME_RESUMIDO") & " (" & RS("SG_UNIDADE") & ")"
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
    ShowHTML "              <a class=""ss"" href=""#"" onClick=""window.open('" & w_dir_volta & "Pessoa.asp?par=BuscaUsuario&TP=" & TP & "&restricao=" &restricao& "&campo=" &campo& "','Usuario','top=10,left=10,width=780,height=550,toolbar=yes,status=yes,resizable=yes,scrollbars=yes'); return false;"" title=""Clique aqui para selecionar uma pessoa.""><img src=images/Folder/Explorer.gif border=0 align=top height=15 width=15></a>"

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de responsáveis por solicitações
REM -------------------------------------------------------------------------
Sub SelecaoSolicResp (label, accesskey, hint, chave, chaveAux, tramite, chaveAux2, campo, restricao)
    DB_GetSolicResp RS, chaveAux, tramite, chaveAux2, restricao
    RS.Sort = "nome_resumido"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_pessoa"),0)) = cDbl(nvl(chave,0)) or (RS.RecordCount = 1) Then
          ShowHTML "          <option value=""" & RS("sq_pessoa") & """ SELECTED>" & RS("NOME_RESUMIDO") & " (" & RS("SG_UNIDADE") & ")"
       Else
          ShowHTML "          <option value=""" & RS("sq_pessoa") & """>" & RS("NOME_RESUMIDO") & " (" & RS("SG_UNIDADE") & ")"
       End If
       If RS.RecordCount = 1 Then
          chave = RS("sq_pessoa")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção do centro de custo
REM -------------------------------------------------------------------------
Sub SelecaoUsuUnid (label, accesskey, hint, chave, chaveAux, campo, restricao)
    DB_GetUserList RS, w_cliente, null, null, null, null, null, null, "S"
    RS.Filter = "contratado = 'S'"
    RS.Sort = "nome_indice"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_pessoa"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_pessoa") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_pessoa") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção dos tipos de vínculo
REM -------------------------------------------------------------------------
Sub SelecaoVinculo (label, accesskey, hint, chave, chaveAux, campo, restricao)
    DB_GetVincKindList RS, w_cliente
    If Nvl(restricao,"") > "" Then RS.Filter = restricao End If
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_tipo_vinculo"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_tipo_vinculo") & """ SELECTED>" & RS("nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_tipo_vinculo") & """>" & RS("nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção dos tipos de postos
REM -------------------------------------------------------------------------
Sub SelecaoTipoPosto (label, accesskey, hint, chave, chaveAux, campo, restricao)
    DB_GetTipoPostoList RS, w_cliente, null
    If Nvl(restricao,"") > "" Then RS.Filter = restricao End If
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br>"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br>"
    End If
    While Not RS.EOF
       If cDbl(nvl(RS("sq_eo_tipo_posto"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""" & campo & """ value=""" & RS("sq_eo_tipo_posto") & """ checked>"  & RS("descricao") & "<br>"
       Else
          ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""" & campo & """ value=""" & RS("sq_eo_tipo_posto") & """>"  & RS("descricao") & "<br>"
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção do grupo de deficiência
REM -------------------------------------------------------------------------
Sub SelecaoGrupoDef (label, accesskey, hint, chave, chaveAux, campo, restricao)
    DB_GetDeficGroupList RS
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("SQ_GRUPO_DEFIC"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("SQ_GRUPO_DEFIC") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("SQ_GRUPO_DEFIC") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção do tipo da pessoa
REM -------------------------------------------------------------------------
Sub SelecaoTipoPessoa (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetKindPersonList RS
    If restricao > "" Then
       RS.Filter = restricao
    End If
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_tipo_pessoa"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_tipo_pessoa") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_tipo_pessoa") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção da forma de pagamento
REM -------------------------------------------------------------------------
Sub SelecaoFormaPagamento (label, accesskey, hint, chave, chave_aux, campo, restricao)
    DB_GetFormaPagamento RS, w_cliente, null, chave_aux, restricao
    RS.Filter = "ativo = 'S'"
    RS.Sort = "nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_forma_pagamento"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_forma_pagamento") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_forma_pagamento") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de país
REM -------------------------------------------------------------------------
Sub SelecaoPais (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetCountryList RS
    If restricao > "" Then
       RS.Filter = restricao
    End If
    RS.Sort = "padrao desc, Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_pais"),0)) = cDbl(nvl(chave,0)) or (RS.RecordCount = 1) Then
          ShowHTML "          <option value=""" & RS("sq_pais") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_pais") & """>" & RS("Nome")
       End If
       If RS.RecordCount = 1 Then
          chave = RS("sq_pais")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção da região
REM -------------------------------------------------------------------------
Sub SelecaoRegiao (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetRegionList RS, chaveAux, null
    If restricao > "" Then
       RS.Filter = restricao
    End If
    RS.Sort = "Ordem"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_regiao"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_regiao") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_regiao") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de estado
REM -------------------------------------------------------------------------
Sub SelecaoEstado (label, accesskey, hint, chave, chaveAux, chaveAux2, campo, restricao, atributo)
    DB_GetStateList RS, cDbl(Nvl(chaveAux,0))
    If restricao > "" Then
       RS.Filter = restricao
    End If
    RS.Sort = "padrao desc, nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If nvl(RS("co_uf"),0) = nvl(chave,0) or RS.RecordCount = 1 Then
          ShowHTML "          <option value=""" & RS("co_uf") & """ SELECTED>" & RS("nome")
       Else
          ShowHTML "          <option value=""" & RS("co_uf") & """>" & RS("nome")
       End If
       If RS.RecordCount = 1 Then
          chave = RS("co_uf")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de cidade
REM -------------------------------------------------------------------------
Sub SelecaoCidade (label, accesskey, hint, chave, chaveAux, chaveAux2, campo, restricao, atributo)
    DB_GetCityList RS, cDbl(Nvl(chaveAux,0)), chaveAux2
    If restricao > "" Then
       RS.Filter = restricao
    End If
    RS.Sort = "capital desc, nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_cidade"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_cidade") & """ SELECTED>" & RS("nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_cidade") & """>" & RS("nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção dos endereços da organização
REM -------------------------------------------------------------------------
Sub SelecaoEndereco (label, accesskey, hint, chave, chaveAux, campo, restricao)
    DB_GetaddressList RS, w_cliente, ChaveAux, restricao
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_pessoa_endereco"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_pessoa_endereco") & """ SELECTED>" & RS("endereco")
       Else
          ShowHTML "          <option value=""" & RS("sq_pessoa_endereco") & """>" & RS("endereco")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção dos telefones de uma pessoa
REM -------------------------------------------------------------------------
Sub SelecaoTelefone (label, accesskey, hint, chave, chaveAux, campo, restricao)
    DB_GetFoneList RS, w_cliente, ChaveAux, restricao
    
    
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    
    ShowHTML "          <option value="""">---"
    
    While Not RS.EOF
       If cDbl(nvl(RS("sq_pessoa_telefone"),0)) = cDbl(nvl(chave,0)) Then
          
          ShowHTML "          <option value=""" & RS("sq_pessoa_telefone") & """ SELECTED>" & RS("numero")
       Else
          ShowHTML "          <option value=""" & RS("sq_pessoa_telefone") & """>" & RS("numero")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção dos módulos contratados pelo cliente
REM -------------------------------------------------------------------------
Sub SelecaoModulo (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetSiwCliModLis RS, chaveAux, restricao
    RS.Sort = "nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_modulo"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_modulo") & """ SELECTED>" & RS("nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_modulo") & """>" & RS("nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de opções do menu que são vinculadas a serviço
REM -------------------------------------------------------------------------
Sub SelecaoServico (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetMenuList RS, w_cliente, "I", null
    RS.Filter = "tramite='S'"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_menu"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_menu") & """ SELECTED>" & RS("nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_menu") & """>" & RS("nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de opções existentes no menu
REM -------------------------------------------------------------------------
Sub SelecaoMenu (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    Dim RST, RST1, RST2, RST3, RST4
    
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"

    DB_GetMenuOrder RST, w_cliente, null
    If restricao = "Pesquisa" Then RST.Filter = "ultimo_nivel = 'N' and sq_menu  <> " & Nvl(chaveAux,0) End If
    RST.Sort = "ordem"
    While Not RST.EOF
      If cDbl(nvl(RST("sq_menu"),0)) = cDbl(nvl(chave,0)) Then ShowHTML "          <option value=""" & RST("sq_menu") & """ SELECTED>" & RST("nome") Else ShowHTML "          <option value=""" & RST("sq_menu") & """>" & RST("nome") End If
      DB_GetMenuOrder RST1, w_cliente, RST("sq_menu")
      If restricao = "Pesquisa" Then RST1.Filter = "ultimo_nivel = 'N' and sq_menu  <> " & Nvl(chaveAux,0) End If
      RST1.Sort = "ordem"
      While Not RST1.EOF
        If cDbl(nvl(RST1("sq_menu"),0)) = cDbl(nvl(chave,0)) Then ShowHTML "          <option value=""" & RST1("sq_menu") & """ SELECTED>&nbsp;&nbsp;&nbsp;" & RST1("nome") Else ShowHTML "          <option value=""" & RST1("sq_menu") & """>&nbsp;&nbsp;&nbsp;" & RST1("nome") End If
        DB_GetMenuOrder RST2, w_cliente, RST1("sq_menu")
        If restricao = "Pesquisa" Then RST2.Filter = "ultimo_nivel = 'N' and sq_menu  <> " & Nvl(chaveAux,0) End If
        RST2.Sort = "ordem"
        While Not RST2.EOF
          If cDbl(nvl(RST2("sq_menu"),0)) = cDbl(nvl(chave,0)) Then ShowHTML "          <option value=""" & RST2("sq_menu") & """ SELECTED>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" & RST2("nome") Else ShowHTML "          <option value=""" & RST2("sq_menu") & """>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" & RST2("nome") End If
          DB_GetMenuOrder RST3, w_cliente, RST2("sq_menu")
          If restricao = "Pesquisa" Then RST3.Filter = "ultimo_nivel = 'N' and sq_menu  <> " & Nvl(chaveAux,0) End If
          RST3.Sort = "ordem"
          While Not RST3.EOF
            If cDbl(nvl(RST3("sq_menu"),0)) = cDbl(nvl(chave,0)) Then ShowHTML "          <option value=""" & RST3("sq_menu") & """ SELECTED>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" & RST3("nome") Else ShowHTML "          <option value=""" & RST3("sq_menu") & """>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" & RST3("nome") End If
            DB_GetMenuOrder RST4, w_cliente, RST3("sq_menu")
            If restricao = "Pesquisa" Then RST4.Filter = "ultimo_nivel = 'N' and sq_menu  <> " & Nvl(chaveAux,0) End If
            RST4.Sort = "ordem"
            While Not RST4.EOF
              If cDbl(nvl(RST4("sq_menu"),0)) = cDbl(nvl(chave,0)) Then ShowHTML "          <option value=""" & RST4("sq_menu") & """ SELECTED>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" & RST4("nome") Else ShowHTML "          <option value=""" & RST4("sq_menu") & """>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" & RST4("nome") End If
              RST4.MoveNext
            wend
            RST3.MoveNext
            RST4.Close
          wend
          RST2.MoveNext
          RST3.Close
        wend
        RST1.MoveNext
        RST2.Close
      wend
      RST.MoveNext
      RST1.Close
    wend
    ShowHTML "          </select>"
    RST.Close

    Set RST            = Nothing
    Set RST1           = Nothing
    Set RST2           = Nothing
    Set RST3           = Nothing
    Set RST4           = Nothing
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção da localização
REM -------------------------------------------------------------------------
Sub SelecaoLocalizacao (label, accesskey, hint, chave, chaveAux, campo, restricao)
    DB_GetLocalList RS, w_cliente, ChaveAux, restricao
    If Not IsNull(chaveAux) Then
       RS.Filter = "sq_unidade = " & chaveAux 
    End If
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_localizacao"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_localizacao") & """ SELECTED>" & RS("localizacao")
       Else
          ShowHTML "          <option value=""" & RS("sq_localizacao") & """>" & RS("localizacao")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção da localização
REM -------------------------------------------------------------------------
Sub SelecaoSegModulo (label, accesskey, hint, chave, chaveAux, campo, restricao)
    DB_GetSegModList RS, ChaveAux
    RS.Sort = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_modulo"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_modulo") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_modulo") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de segmentos de mercado
REM -------------------------------------------------------------------------
Sub SelecaoSegMercado (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetSegList RS
    RS.Filter = "ativo = 'S'"
    RS.Sort = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_segmento"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_segmento") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_segmento") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção das unidades organizacionais
REM -------------------------------------------------------------------------
Sub SelecaoUnidade (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    If isNull(restricao) or Instr(restricao,"=") = 0 Then
       DB_GetUorgList RS, w_cliente, ChaveAux, restricao, null, null
       RS.Filter = "ativo='S'"
    Else
       DB_GetUorgList RS, w_cliente, ChaveAux, null, null, null
       RS.Filter = "ativo='S' and " & restricao
    End If
    
    Dim w_nm_unidade, w_sigla
    ShowHTML "<INPUT type=""hidden"" name=""" & campo & """ value=""" & chave &""">"
    If chave > "" Then
       DB_GetUorgList RS, w_cliente, chave, null, null, null
       RS.Filter = "sq_unidade = " & chave
       If not RS.EOF Then
          w_nm_unidade = RS("nome")
          w_sigla      = RS("sigla")
       End If
    End If
    If IsNull(hint) Then
       ShowHTML "      <td valign=""top""><font size=""1""><b>" & Label & "</b><br>"
       ShowHTML "          <input READONLY ACCESSKEY=""" & accesskey & """ CLASS=""sti"" type=""text"" name=""" & campo & "_nm" & """ SIZE=""60"" VALUE=""" & w_nm_unidade & """ " & atributo & ">"
    Else
       ShowHTML "      <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br>"
       ShowHTML "          <input READONLY ACCESSKEY=""" & accesskey & """ CLASS=""sti"" type=""text"" name=""" & campo & "_nm" & """ SIZE=""60"" VALUE=""" & w_nm_unidade & """ " & atributo & ">"
    End If
    ShowHTML "              <a class=""ss"" href=""#"" onClick=""window.open('" & w_dir_volta & "EO.asp?par=BuscaUnidade&TP=" & TP & "&w_cliente=" &w_cliente& "&ChaveAux=" &ChaveAux& "&restricao=" &restricao& "&campo=" &campo& "','Unidade','top=10,left=10,width=780,height=550,toolbar=yes,status=yes,resizable=yes,scrollbars=yes'); return false;"" title=""Clique aqui para selecionar a unidade.""><img src=images/Folder/Explorer.gif border=0 align=top height=15 width=15></a>"
    ShowHTML "              <a class=""ss"" href=""#"" onClick=""document.Form." & campo & "_nm" & ".value=''; document.Form." & campo & ".value=''; return false;"" title=""Clique aqui para apagar o valor deste campo.""><img src=images/Folder/Recyfull.gif border=0 align=top height=15 width=15></a>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem do link para abrir o calendário
REM -------------------------------------------------------------------------
Function ExibeCalendario (form, campo)
    ExibeCalendario = "   <a class=""ss"" href=""#"" onClick=""window.open('"& w_dir_volta & "cp_calendar/ccalexa2.asp?nmForm=" & form & "&nmCampo=" & campo & "&vData='+document." & Form & "." & campo & ".value,'dp','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0,width=250,height=250,left=500,top=200'); return false;"" title=""Visualizar calendário""><img src=images/Icone/goToTop.gif border=0 align=top height=13 width=15></a>"
End Function
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção da unidade pai
REM -------------------------------------------------------------------------
Sub SelecaoUnidadePai (label, accesskey, hint, chave, Operacao, chaveAux, chaveAux2, campo, restricao)
    DB_GetEOUnitPaiList RS,Operacao, chaveAux, chaveAux2
    RS.Sort = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_unidade"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_unidade") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_unidade") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção das unidades gestoras
REM -------------------------------------------------------------------------
Sub SelecaoUnidadeGest (label, accesskey, hint, chave, chaveAux, campo, restricao)
    DB_GetUorgList RS, w_cliente, chaveAux, null, null, null
    w_filter = " unidade_gestora = 'S' and ativo = 'S'"
    If chaveAux > "" Then
       w_filter = w_filter & " and sq_unidade <> " & chaveAux 
    end If
    RS.Filter = w_filter
    RS.Sort = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_unidade"),0)) = cDbl(nvl(chave,0)) and cDbl(nvl(RS("sq_unidade"),0)) > 0 Then
          ShowHTML "          <option value=""" & RS("sq_unidade") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_unidade") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção das unidades pagadoras
REM -------------------------------------------------------------------------
Sub SelecaoUnidadePag (label, accesskey, hint, chave, chaveAux, campo, restricao)
    DB_GetUorgList RS, w_cliente, chaveAux, null, null, null
    w_filter = " unidade_pagadora = 'S' and ativo = 'S'"
    If chaveAux > "" Then
       w_filter = w_filter & " and sq_unidade <> " & chaveAux 
    end If
    RS.Filter = w_filter
    RS.Sort = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_unidade"),0)) = cDbl(nvl(chave,0)) and cDbl(nvl(RS("sq_unidade"),0)) > 0 Then
          ShowHTML "          <option value=""" & RS("sq_unidade") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_unidade") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção do centro de custo
REM -------------------------------------------------------------------------
Sub SelecaoCC (label, accesskey, hint, chave, chaveAux, campo, restricao)
    DB_GetCCList RS, w_cliente, ChaveAux, restricao
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    ShowHTML "          <OPTION VALUE="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_cc"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_cc") & """ SELECTED>" & RS("NOME")
       Else
          ShowHTML "          <option value=""" & RS("sq_cc") & """>" & RS("NOME")
       End If
       RS.MoveNext
    Wend
    DesconectaBD
    ShowHTML "          </SELECT></td>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção do centro de custo
REM -------------------------------------------------------------------------
Sub SelecaoCCSubordination (label, accesskey, hint, chave, pai, campo, restricao, condicao)
    DB_GetCCSubordination RS, w_cliente, chave, restricao
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    ShowHTML "          <OPTION VALUE="""">---"
    While Not RS.EOF
       If cDbl(RS("sq_cc")) = cDbl(nvl(pai,0)) Then
          ShowHTML "          <option value=""" & RS("sq_cc") & """ SELECTED>" & RS("nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_cc") & """>" & RS("nome")
       End If
       RS.MoveNext
    Wend
    DesconectaBD
    ShowHTML "          </SELECT></td>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção do banco
REM -------------------------------------------------------------------------
Sub SelecaoBanco (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetBankList RS
    RS.Filter = "ativo='S'"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
      If cDbl(Nvl(chave,-1)) = cDbl(Nvl(RS("sq_banco"),-1)) Then
         ShowHTML "          <OPTION VALUE=""" & RS("sq_banco") & """ SELECTED>" & RS("descricao")
      Else
         ShowHTML "          <OPTION VALUE=""" & RS("sq_banco") & """>" & RS("descricao")
      End If
      RS.MoveNext
    Wend
    DesconectaBD
    ShowHTML "          </SELECT></td>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de estado
REM -------------------------------------------------------------------------
Sub SelecaoAgencia (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetBankHouseList RS, chaveAux, null, "padrao desc, codigo"
    If restricao > "" Then
       RS.Filter = restricao
    End If
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_agencia"),-1)) = cDbl(nvl(chave,-1)) Then
          ShowHTML "          <option value=""" & RS("sq_agencia") & """ SELECTED>" & RS("codigo") & " - " & RS("nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_agencia") & """>" & RS("codigo") & " - " & RS("nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção do tipo de unidade
REM -------------------------------------------------------------------------
Sub SelecaoTipoUnidade (label, accesskey, hint, chave, chaveAux, campo, restricao)
    DB_GetUnitTypeList RS, chaveAux
    RS.Filter = "ativo = 'S'"
    RS.Sort = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_tipo_unidade"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_tipo_unidade") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_tipo_unidade") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção do tipo de endereco
REM -------------------------------------------------------------------------
Sub SelecaoTipoEndereco (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetAdressTypeList RS
    If restricao > "" Then
       RS.Filter = restricao
    End If
    RS.Sort = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_tipo_endereco"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_tipo_endereco") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_tipo_endereco") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção do tipo de endereco
REM -------------------------------------------------------------------------
Sub SelecaoTipoFone (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetFoneTypeList RS
    If restricao > "" Then
       RS.Filter = restricao
    End If
    RS.Sort = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_tipo_telefone"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_tipo_telefone") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_tipo_telefone") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção do tipo de unidade
REM -------------------------------------------------------------------------
Sub SelecaoEOAreaAtuacao (label, accesskey, hint, chave, chaveAux, campo, restricao)
    DB_GetEOAAtuac RS, chaveAux
    RS.Filter = "ativo = 'S'"
    RS.Sort = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_area_atuacao"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_area_atuacao") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_area_atuacao") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção da fase de uma solicitação
REM -------------------------------------------------------------------------
Sub SelecaoFase (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetTramiteList RS, chaveAux, restricao
    RS.Filter = "ativo = 'S'"
    RS.Sort = "Ordem"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    While Not RS.EOF
       If cDbl(RS("sq_siw_tramite")) = cDbl(chave) or (RS.RecordCount = 1) Then
          ShowHTML "          <option value=""" & RS("sq_siw_tramite") & """ SELECTED>" & RS("ordem") & " - " & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_siw_tramite") & """>" & RS("ordem") & " - " & RS("Nome")
       End If
       If RS.RecordCount = 1 Then
          chave = RS("sq_siw_tramite")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção da fase de uma solicitação
REM -------------------------------------------------------------------------
Sub SelecaoFaseCheck (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    Dim l_i, l_marcado
    Dim l_chave, l_item
    
    DB_GetTramiteList RS, chaveAux, null
    'RS.Filter = "sigla <> 'CA'"
    RS.Sort = "Ordem"
    ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b>"
    While Not RS.EOF
       If Nvl(chave,"") = "" Then
          If  Nvl(RS("sigla"),"-") <> "CA" Then
             ShowHTML "          <BR><input type=""CHECKBOX"" name=""" & campo & """ value=""" & RS("sq_siw_tramite") & """ CHECKED>" & RS("Nome")
          Else
             ShowHTML "          <BR><input type=""CHECKBOX"" name=""" & campo & """ value=""" & RS("sq_siw_tramite") & """>" & RS("Nome")
          End If
       Else
          l_marcado = "N"
          l_chave = chave & ","
          While Instr(l_chave,",") > 0
              l_item  = Trim(Mid(l_chave,1,Instr(l_chave,",")-1))
              l_chave = Mid(l_chave,Instr(l_chave,",")+1,100)
              If l_item > "" Then
                 If cDbl(RS("sq_siw_tramite")) = cDbl(l_item) Then l_marcado = "S" End If
              End If
          Wend
          
          If l_marcado = "S" Then
             ShowHTML "          <BR><input type=""CHECKBOX"" name=""" & campo & """ value=""" & RS("sq_siw_tramite") & """ CHECKED>" & RS("Nome")
          Else
             ShowHTML "          <BR><input type=""CHECKBOX"" name=""" & campo & """ value=""" & RS("sq_siw_tramite") & """ >" & RS("Nome")
          End If
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
    
    Set l_item      = Nothing
    Set l_chave     = Nothing
    Set l_marcado   = Nothing
    Set l_i         = Nothing
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de projetos
REM -------------------------------------------------------------------------
Sub SelecaoProjeto (label, accesskey, hint, chave, chaveAux, chaveAux2, campo, restricao, atributo)
    DB_GetSolicList RS, chaveAux2, chaveAux, restricao, null, null, null, null, null, null, null, null, null, null, null, _
        null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null
    RS.Sort = "titulo"

    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_siw_solicitacao"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_siw_solicitacao") & """ SELECTED>" & RS("titulo")
       Else
          ShowHTML "          <option value=""" & RS("sq_siw_solicitacao") & """>" & RS("titulo")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de tipo de recurso
REM -------------------------------------------------------------------------
Sub SelecaoTipoRecurso (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If cDbl(nvl(chave,-1)) = 0 Then ShowHTML "          <option value=""0"" SELECTED>Financeiro"    Else ShowHTML "          <option value=""0"">Financeiro"    End If
    If cDbl(nvl(chave,-1)) = 1 Then ShowHTML "          <option value=""1"" SELECTED>Humano"        Else ShowHTML "          <option value=""1"">Humano"        End If
    If cDbl(nvl(chave,-1)) = 2 Then ShowHTML "          <option value=""2"" SELECTED>Material"      Else ShowHTML "          <option value=""2"">Material"      End If
    If cDbl(nvl(chave,-1)) = 3 Then ShowHTML "          <option value=""3"" SELECTED>Metodológico"  Else ShowHTML "          <option value=""3"">Metodológico"  End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção dos tipos de apoio
REM -------------------------------------------------------------------------
Sub SelecaoTipoApoio (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetTipoApoioList RS, w_cliente, null, null, null, null
    If Nvl(restricao,"") > "" Then RS.Filter = restricao End If
    RS.Sort = "nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_tipo_apoio"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_tipo_apoio") & """ SELECTED>" & RS("nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_tipo_apoio") & """>" & RS("nome")
       End If
       RS.MoveNext
    Wend
    DesconectaBD
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Retorna o tipo de recurso a partir do código
REM -------------------------------------------------------------------------
Function RetornaTipoRecurso (p_chave)
    Select Case cDbl(p_Chave)
       Case 0 RetornaTipoRecurso = "Financeiro"
       Case 1 RetornaTipoRecurso = "Humano"
       Case 2 RetornaTipoRecurso = "Material"
       Case 3 RetornaTipoRecurso = "Metodológico"
       Case Else RetornaTipoRecurso = "Erro"
    End Select
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de prioridade
REM -------------------------------------------------------------------------
Sub SelecaoPrioridade (label, accesskey, hint, chave, cliente, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If cDbl(nvl(chave,-1)) = 0 Then ShowHTML "          <option value=""0"" SELECTED>Alta"   Else ShowHTML "          <option value=""0"">Alta"   End If
    If cDbl(nvl(chave,-1)) = 1 Then ShowHTML "          <option value=""1"" SELECTED>Média"  Else ShowHTML "          <option value=""1"">Média"  End If
    If cDbl(nvl(chave,-1)) = 2 Then ShowHTML "          <option value=""2"" SELECTED>Normal" Else ShowHTML "          <option value=""2"">Normal" End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de prioridade
REM -------------------------------------------------------------------------
Sub SelecaoTipoVisao (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If cDbl(nvl(chave,-1)) = 0 Then ShowHTML "          <option value=""0"" SELECTED>Completa"  Else ShowHTML "          <option value=""0"">Completa" End If
    If cDbl(nvl(chave,-1)) = 1 Then ShowHTML "          <option value=""1"" SELECTED>Parcial"   Else ShowHTML "          <option value=""1"">Parcial"  End If
    If cDbl(nvl(chave,-1)) = 2 Then ShowHTML "          <option value=""2"" SELECTED>Resumida"  Else ShowHTML "          <option value=""2"">Resumida" End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de etapas do projeto
REM -------------------------------------------------------------------------
Sub SelecaoEtapa (label, accesskey, hint, chave, chaveAux, chaveAux2, campo, restricao, atributo)
    
    DIM RST, RST1, RST2, RST3, RST4
    
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"

    DB_GetSolicEtapa RST, chaveAux, chaveAux2, "LSTNULL"
    If restricao = "Pesquisa" Then RST.Filter = "sq_projeto_etapa <> " & Nvl(chaveAux2,0) End If
    RST.Sort = "ordem"
    While Not RST.EOF
      If restricao = "Grupo" and (RST("vincula_atividade") = "N" or cDbl(RST("perc_conclusao")) >= 100) Then
         ShowHTML "          <option value="""">" & RST("ordem") & ". " & RST("titulo")
      Else
         If cDbl(nvl(RST("sq_projeto_etapa"),0)) = cDbl(nvl(chave,0)) Then ShowHTML "          <option value=""" & RST("sq_projeto_etapa") & """ SELECTED>" & RST("ordem") & ". " & RST("titulo") Else ShowHTML "          <option value=""" & RST("sq_projeto_etapa") & """>" & RST("ordem") & ". " & RST("titulo") End If
      End if
      DB_GetSolicEtapa RST1, chaveAux, RST("sq_projeto_etapa"), "LSTNIVEL"
      If restricao = "Pesquisa" Then RST1.Filter = "sq_projeto_etapa <> " & Nvl(chaveAux2,0) End If
      RST1.Sort = "ordem"
      While Not RST1.EOF
        If restricao = "Grupo" and (RST1("vincula_atividade") = "N" or cDbl(RST1("perc_conclusao")) >= 100) Then
           ShowHTML "          <option value="""">" & RST("ordem") & "." & RST1("ordem") & ". " & RST1("titulo")
        Else
           If cDbl(nvl(RST1("sq_projeto_etapa"),0)) = cDbl(nvl(chave,0)) Then ShowHTML "          <option value=""" & RST1("sq_projeto_etapa") & """ SELECTED>" & RST1("ordem") & ". " & RST1("titulo") Else ShowHTML "          <option value=""" & RST1("sq_projeto_etapa") & """>" & RST("ordem") & "." & RST1("ordem") & ". " & RST1("titulo") End If
        End if
        DB_GetSolicEtapa RST2, chaveAux, RST1("sq_projeto_etapa"), "LSTNIVEL"
        RST2.Sort = "ordem"
        While Not RST2.EOF
          If restricao = "Grupo" and (RST2("vincula_atividade") = "N" or cDbl(RST2("perc_conclusao")) >= 100) Then
             ShowHTML "          <option value="""">" & RST("ordem") & "." & RST1("ordem") & "." & RST2("ordem") & ". " & RST2("titulo")
          Else
             If cDbl(nvl(RST2("sq_projeto_etapa"),0)) = cDbl(nvl(chave,0)) Then ShowHTML "          <option value=""" & RST2("sq_projeto_etapa") & """ SELECTED>" & RST2("ordem") & ". " & RST2("titulo") Else ShowHTML "          <option value=""" & RST2("sq_projeto_etapa") & """>" & RST("ordem") & "." & RST1("ordem") & "." & RST2("ordem") & ". " & RST2("titulo") End If
          End if
          DB_GetSolicEtapa RST3, chaveAux, RST2("sq_projeto_etapa"), "LSTNIVEL"
          RST3.Sort = "ordem"
          While Not RST3.EOF
            If restricao = "Grupo" and (RST3("vincula_atividade") = "N" or cDbl(RST3("perc_conclusao")) >= 100) Then
               ShowHTML "          <option value="""">" & RST("ordem") & "." & RST1("ordem") & "." & RST2("ordem") & "." & RST3("ordem") & ". " & RST3("titulo")
            Else
               If cDbl(nvl(RST3("sq_projeto_etapa"),0)) = cDbl(nvl(chave,0)) Then ShowHTML "          <option value=""" & RST3("sq_projeto_etapa") & """ SELECTED>" & RST3("ordem") & ". " & RST3("titulo") Else ShowHTML "          <option value=""" & RST3("sq_projeto_etapa") & """>" & RST("ordem") & "." & RST1("ordem") & "." & RST2("ordem") & "." & RST3("ordem") & ". " & RST3("titulo") End If
            End if
            DB_GetSolicEtapa RST4, chaveAux, RST3("sq_projeto_etapa"), "LSTNIVEL"
            RST4.Sort = "ordem"
            While Not RST4.EOF
              If restricao = "Grupo" and (RST4("vincula_atividade") = "N" or cDbl(RST4("perc_conclusao")) >= 100) Then
                 ShowHTML "          <option value="""">" & RST("ordem") & "." & RST1("ordem") & "." & RST2("ordem") & "." & RST3("ordem") & "." & RST4("ordem") & ". " & RST4("titulo")
              Else
                 If cDbl(nvl(RST4("sq_projeto_etapa"),0)) = cDbl(nvl(chave,0)) Then ShowHTML "          <option value=""" & RST4("sq_projeto_etapa") & """ SELECTED>" & RST4("ordem") & ". " & RST4("titulo") Else ShowHTML "          <option value=""" & RST4("sq_projeto_etapa") & """>" & RST("ordem") & "." & RST1("ordem") & "." & RST2("ordem") & "." & RST3("ordem") & "." & RST4("titulo") End If
              End if
              RST4.MoveNext
            wend
            RST3.MoveNext
            RST4.Close
          wend
          RST2.MoveNext
          RST3.Close
        wend
        RST1.MoveNext
        RST2.Close
      wend
      RST.MoveNext
      RST1.Close
    wend
    ShowHTML "          </select>"
    RST.Close

    Set RST            = Nothing
    Set RST1           = Nothing
    Set RST2           = Nothing
    Set RST3           = Nothing
    Set RST4           = Nothing

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Retorna a prioridade a partir do código
REM -------------------------------------------------------------------------
Function RetornaPrioridade (p_chave)
    Select Case cDbl(Nvl(p_Chave,999))
       Case 0 RetornaPrioridade = "Alta"
       Case 1 RetornaPrioridade = "Média"
       Case 2 RetornaPrioridade = "Normal"
       Case Else RetornaPrioridade = "---"
    End Select
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Retorna o tipo de visao a partir do código
REM -------------------------------------------------------------------------
Function RetornaTipoVisao (p_chave)
    Select Case cDbl(p_Chave)
       Case 0 RetornaTipoVisao = "Completa"
       Case 1 RetornaTipoVisao = "Parcial"
       Case 2 RetornaTipoVisao = "Resumida"
       Case Else RetornaTipoVisao = "Erro"
    End Select
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Função que formata dias, horas, minutos e segundos a partir dos segundos
REM -------------------------------------------------------------------------
Function FormataTempo(p_segundos)
  Dim l_dias, l_horas, l_minutos, l_segundos,  l_tempo

  l_horas    = Int(p_segundos/3600)
  l_minutos  = Int((p_segundos - (l_horas*3600))/60)
  l_segundos = p_segundos - (l_horas*3600) - (l_minutos*60)
  FormataTempo = Mid(1000+l_horas,2,3) & ":" & Mid(100+l_minutos,2,2) & ":" & Mid(100+l_segundos,2,2)

  Set l_tempo       = Nothing
  Set l_dias        = Nothing
  Set l_horas       = Nothing
  Set l_minutos     = Nothing
  Set l_segundos    = Nothing
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Função que retorna o código de tarifação telefônica do usuário logado
REM -------------------------------------------------------------------------
Function RetornaUsuarioCentral()
  DIM l_RS
  Set l_RS = Server.CreateObject("ADODB.RecordSet")
  
  ' Se receber o código do usuario do SIW, o usuário será determinado por parâmetro;
  ' caso contrário, retornará o código do usuário logado.
  If Request("w_sq_usuario_central") > "" Then
     RetornausuarioCentral = Request("w_sq_usuario_central")
  Else
     DB_GetPersonData l_RS, w_cliente, w_usuario, null, null
     
     RetornaUsuarioCentral = l_RS("sq_usuario_central")
     DesconectaBD
  End If
  Set l_RS = Nothing
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Função que retorna o código do usuário logado
REM -------------------------------------------------------------------------
Function RetornaUsuario()
  ' Se receber o código do usuario do SIW, o usuário será determinado por parâmetro;
  ' caso contrário, retornará o código do usuário logado.
  If Request("w_usuario") > "" Then
     Retornausuario = Request("w_usuario")
  Else
     RetornaUsuario = Session("sq_pessoa")
  End If
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Função que retorna o código do menu
REM -------------------------------------------------------------------------
Function RetornaMenu(p_cliente, p_sigla)
  ' Se receber o código do menu do SIW, o código será determinado por parâmetro;
  ' caso contrário, retornará o código retornado a partir da sigla.
  If Request("w_menu") > "" Then
     RetornaMenu = Request("w_menu")
  Else
     DB_GetMenuCode RS, p_cliente, p_sigla
     RetornaMenu = RS("sq_menu")
  End If
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Função que retorna o código do cliente
REM -------------------------------------------------------------------------
Function RetornaCliente()
  ' Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
  ' caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
  If Request("w_cgccpf") > "" and Len(Request("w_cgccpf")) > 11 Then
     DB_GetCompanyData RS, Session("p_cliente"), Request("w_cgccpf")
     If Not RS.EOF Then
        RetornaCliente = RS("sq_pessoa")
     Else
        RetornaCliente = Session("p_cliente")
     End If
     DesconectaBD
  ElseIf Request("w_cliente") > "" Then
     RetornaCliente = Request("w_cliente")
  Else
     RetornaCliente = Session("p_cliente")
  End If
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Função que retorna S/N indicando se o usuário informado é gestor do sistema
REM ou do módulo ao qual a solicitação pertence
REM -------------------------------------------------------------------------
Function RetornaGestor(p_solicitacao, p_usuario)
  Dim l_acesso

  l_acesso = ""
  
  DB_GetGestor p_solicitacao, p_usuario, l_acesso
  RetornaGestor = l_acesso
  
  Set l_acesso = Nothing
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina que encerra a sessão e fecha a janela do SIW
REM -------------------------------------------------------------------------
Sub EncerraSessao
  ScriptOpen "JavaScript"
  ShowHTML " alert('Tempo máximo de inatividade atingido! Autentique-se novamente.'); "
  ShowHTML " top.location.href='" & conDefaultPath & "'; "
  ScriptClose
  Response.End()
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Função que formata um texto para exibição em HTML
REM -------------------------------------------------------------------------
Function ExibeTexto(p_texto)
    ExibeTexto = Replace(Replace(p_texto,VbCrLf, "<br>"),"  ","&nbsp;&nbsp;")
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Função que retorna a data/hora do banco
REM -------------------------------------------------------------------------
Function DataHora()
    DataHora = FormatDateTime(Date(),1) & ", " & FormatDateTime(Time(),3)
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina que monta a máscara do beneficiário
REM -------------------------------------------------------------------------
Function MascaraBeneficiario(cgccpf)
  ' Se o campo tiver máscara, retira
  If Instr(cgccpf,".") > 0 Then
     MascaraBeneficiario = Replace(Replace(Replace(cgccpf,".",""),"-",""),"/","")
  ' Caso contrário, aplica a máscara, dependendo do tamanho do parâmetro
  ElseIf Len(cgccpf) = 11 Then
     MascaraBeneficiario = Mid(cgccpf,1,3) & "." & Mid(cgccpf,4,3) & "." & Mid(cgccpf,7,3) & "-" & Mid(cgccpf,10,2)
  ElseIf Len(cgccpf) = 14 Then
     MascaraBeneficiario = Mid(cgccpf,1,2) & "." & Mid(cgccpf,3,3) & "." & Mid(cgccpf,6,3) & "/" & Mid(cgccpf,9,4) & "-" & Mid(cgccpf,13,2)
  End If
End Function
REM =========================================================================
REM Final da rotina 
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de envio de e-mail
REM -------------------------------------------------------------------------
Function EnviaMail(w_subject, w_mensagem, w_recipients)

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

  JMail.Subject  = w_subject
  JMail.HtmlBody = w_mensagem
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
    'EnviaMail = Replace("Erro: " & JMail.ErrorCode & "\nServidor: " & Session("smtp_server") & "\nConta: " & Session("siw_email_conta") & "\nSenha: " & Nvl(Session("siw_email_senha"),"---") & "\nMensagem: " & JMail.ErrorMessage & "\nFonte: " & JMail.ErrorSource,VbCrLf,"\n")
    EnviaMail = Replace("Erro: " & JMail.ErrorCode & "\nMensagem: " & JMail.ErrorMessage & "\nFonte: " & JMail.ErrorSource,VbCrLf,"\n")
  Else
    EnviaMail = ""
  End If
  
End Function
REM =========================================================================
REM Fim da rotina de envio de email
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de envio de e-mail
REM -------------------------------------------------------------------------
Function EnviaMailSender(w_subject, w_mensagem, w_recipients, w_from, w_from_name)

  w_recipients = w_recipients & ";"
  Dim JMail
  Dim Recipients(500), i, j

  Set JMail = Server.CreateObject("JMail.Message")
 
  JMail.Silent   = True
  JMail.From     = w_from
  JMail.FromName = w_from_name
  If Session("siw_email_senha") > "" Then
     JMail.Logging            = True
     JMail.MailServerUserName = Session("siw_email_conta")
     JMail.MailServerPassWord = Session("siw_email_senha")
  Else
     JMail.Logging  = False
     JMail.MailServerUserName = Session("siw_email_conta")
  End If

  JMail.Subject  = w_subject
  JMail.HtmlBody = w_mensagem
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

  If JMail.ErrorCode > 0 Then
    EnviaMail = Replace("Erro: " & JMail.ErrorCode & "\nMensagem: " & JMail.ErrorMessage & "\nFonte: " & JMail.ErrorSource,VbCrLf,"\n")
  Else
    EnviaMail = ""
  End If
  
End Function
REM =========================================================================
REM Fim da rotina de envio de email
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina que extrai a última parte da variável TP
REM -------------------------------------------------------------------------
Function RemoveTP(TP)
  Dim w_TP
  w_TP = TP
  While InStr(w_TP,"-") > 0
     w_TP = Mid(w_TP,InStr(w_TP,"-")+1,Len(w_TP))
  Wend
  RemoveTP = replace(TP," -"&w_TP,"")
End Function
REM =========================================================================
REM Final da rotina 
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina que extrai o nome de um arquivo, removendo o caminho
REM -------------------------------------------------------------------------
Function ExtractFileName(arquivo)
  Dim fsa
  fsa = arquivo
  While InStr(fsa,"\") > 0
     fsa = Mid(fsa,InStr(fsa,"\")+1,Len(fsa))
  Wend
  ExtractFileName = fsa
End Function
REM =========================================================================
REM Final da rotina 
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de deleção de arquivos em disco
REM -------------------------------------------------------------------------
Sub DeleteAFile(filespec)
  Dim fso
  Set fso = CreateObject("Scripting.FileSystemObject")
  fso.DeleteFile(filespec)
End Sub
REM =========================================================================
REM Final da rotina de deleção de arquivos em disco
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de tratamento de erros
REM -------------------------------------------------------------------------
Sub TrataErro
  Dim w_resultado
  
  If instr(Err.description,"ORA-02292") > 0 Then ' REGISTRO TEM FILHOS
    ScriptOpen "JavaScript"
    ShowHTML " alert('Existem registros vinculados ao que você está excluindo. Exclua-os primeiro.\n\n" & Mid(Err.Description,1,Instr(Err.Description,Chr(10))-1) & "');"
    Err.clear
    ShowHTML " history.back(1);"
    ScriptClose
  Elseif instr(Err.description,"ORA-02291") > 0 Then ' REGISTRO NÃO ENCONTRADO
    Err.clear
    ScriptOpen "JavaScript"
    ShowHTML " alert('Registro não encontrado.');"
    ShowHTML " history.back(1);"
    ScriptClose
  Elseif instr(Err.description,"ORA-00001") > 0 Then ' REGISTRO JÁ EXISTENTE
    ScriptOpen "JavaScript"
    ShowHTML " alert('Um dos campos digitados já existe no banco de dados e é único.\n\n" & Mid(Err.Description,1,Instr(Err.Description,Chr(10))-1) & "');"
    Err.clear
    ShowHTML " history.back(1);"
    ScriptClose
  Elseif instr(Err.description,"ORA-03113") > 0 _
      or instr(Err.description,"ORA-03114") > 0 _
      or instr(Err.description,"ORA-12224") > 0 _
      or instr(Err.description,"ORA-12514") > 0 _
      or instr(Err.description,"ORA-12541") > 0 _
      or instr(Err.description,"ORA-12545") > 0 _
    Then
    ScriptOpen "JavaScript"
    ShowHTML " alert('Banco de dados fora do ar. Aguarde alguns instantes e tente novamente!');"'\n\n" & Mid(Err.Description,1,Instr(Err.Description,Chr(10))-1) & "');"
    Err.clear
    ShowHTML " history.back(1);"
    ScriptClose
  Else
    Dim w_item, w_html
    w_html =  "<html><BASEFONT FACE=""Arial""><body BGCOLOR=""#FF5555"" TEXT=""#FFFFFF"">"
    w_html = w_html & "<CENTER><H2>ATENÇÃO</H2></CENTER>"
    w_html = w_html & "<BLOCKQUOTE>"
    w_html = w_html & "<P ALIGN=""JUSTIFY"">Erro não previsto. <b>Uma cópia desta tela foi enviada por e-mail para os responsáveis pela correção. Favor tentar novamente mais tarde.</P>"
    w_html = w_html & "<TABLE BORDER=""2"" BGCOLOR=""#FFCCCC"" CELLPADDING=""5""><TR><TD><FONT COLOR=""#000000"">" 
    w_html = w_html & "<DL><DT>Data e hora da ocorrência: <FONT FACE=""courier"">" & Mid(100+Day(date()),2,2) & "/" & Mid(100+Month(date()),2,2) & "/" & Year(date()) & ", " & time() & "<br><br></font></DT>" 
    w_html = w_html & "<DT>Número do erro: <FONT FACE=""courier"">" & Err.Number & "<br><br></font></DT>"
    w_html = w_html & "<DT>Descrição do erro:<DD><FONT FACE=""courier"">" & replace(Err.description,"SQL execution error, ","") & "<br><br></font>"
    w_html = w_html & "<DT>Identificado por:<DD><FONT FACE=""courier"">" & Err.Source & "<br><br></font>"

    w_html = w_html & "<DT>Objeto em execução: <FONT FACE=""courier"">" & sp.CommandText & "<br><br></font></DT>"
    w_html = w_html & "<DT>Parâmetros do objeto:<DD><FONT FACE=""courier"" size=1>"
    For Each w_Item IN sp.Parameters
        w_html = w_html & w_Item.Name & " => [" & w_Item.Value & "]<br>"
    Next
    w_html = w_html & "   <br><br></font>"

    w_html = w_html & "<DT>Dados da querystring:"
    For w_Item = 1 to Request.QueryString.Count
        w_html = w_html & "<DD><FONT FACE=""courier"" size=1>" & Request.QueryString.Key(w_item) & " => [" & Request.QueryString(w_item) & "]<br>"
    Next
    w_html = w_html & "</DT>"
    w_html = w_html & "<DT>Dados do formulário:"
    For w_Item = 1 to Request.Form.Count
        w_html = w_html & "<DD><FONT FACE=""courier"" size=1>" & Request.Form.Key(w_item) & " => [" & Request.Form(w_item) & "]<br>"
    Next
    w_html = w_html & "</DT>"
    w_html = w_html & "   <br><br></font>"
    w_html = w_html & "</DT>"
    w_html = w_html & "<DT>Variáveis de sessão:<DD><FONT FACE=""courier"" size=1>"
    For Each w_Item IN Session.Contents
        If InStr(uCase(w_item),"SENHA") = 0 Then
           w_html = w_html & w_Item & " => [" & Session(w_Item) & "]<br>"
        End If
    Next
    w_html = w_html & "</DT>"
    w_html = w_html & "   <br><br></font>"
    w_html = w_html & "<DT>Variáveis de servidor:<DD><FONT FACE=""courier"" size=1>"
    w_html = w_html & " SCRIPT_NAME => [" & Request.ServerVariables("SCRIPT_NAME") & "]<br>" 
    w_html = w_html & " SERVER_NAME => [" & Request.ServerVariables("SERVER_NAME") & "]<br>" 
    w_html = w_html & " SERVER_PORT => [" & Request.ServerVariables("SERVER_PORT") & "]<br>" 
    w_html = w_html & " SERVER_PROTOCOL => [" & Request.ServerVariables("SERVER_PROTOCOL") & "]<br>" 
    w_html = w_html & " HTTP_ACCEPT_LANGUAGE => [" & Request.ServerVariables("HTTP_ACCEPT_LANGUAGE") & "]<br>" 
    w_html = w_html & " HTTP_USER_AGENT => [" & Request.ServerVariables("HTTP_USER_AGENT") & "]<br>" 
    'For Each w_Item IN Request.ServerVariables
    '    If Instr("ALL_HTTP, ALL_RAW, QUERY_STRING", w_Item) = 0 Then 
    '       w_html = w_html & w_Item & " => [" & Request(w_Item) & "]<br>" 
    '    End If
    'Next
    w_html = w_html & "</DT>"
    w_html = w_html & "   <br><br></font>"
    w_html = w_html & "</FONT></TD></TR></TABLE><BLOCKQUOTE>"
    If Session("dbms") = 1 Then
       w_resultado = EnviaMail("ERRO SIW", w_html, "alex@sbpi.com.br; celso@sbpi.com.br; beto@sbpi.com.br")
       If w_resultado > "" Then
          w_html = w_html & "<SCRIPTOPEN ""JAVASCRIPT"">"
          w_html = w_html & "   alert('Não foi possível enviar o e-mail comunicando sobre o erro. Favor copiar esta página e enviá-la por e-mail aos gestores do sistema.');"
          w_html = w_html & "<SCRIPTCLOSE>"
       End If
    End If
    w_html = w_html & "</body></html>"
    ShowHTML w_HTML
    Err.clear
  end if
  Response.End()
  
  Set w_resultado = Nothing
End Sub
REM =========================================================================
REM Fim da rotina de tratamento de erros
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de cabeçalho
REM -------------------------------------------------------------------------
Sub Cabecalho
   If Session("p_cliente") = 6761 Then
      ShowHTML "<!DOCTYPE HTML PUBLIC ""-//W3C//DTD HTML 4.01 Transitional//EN"" ""http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd"">"
      ShowHTML "<HTML xmlns=""http://www.w3.org/1999/xhtml"">"
   Else
      ShowHTML "<HTML>"
   End If
end sub
REM -------------------------------------------------------------------------
REM Final da rotina de cabeçalho
REM =========================================================================

REM =========================================================================
REM Rotina de rodapé
REM -------------------------------------------------------------------------
Sub Rodape
   If Session("p_cliente") = 6761 Then
      ShowHTML "<center>"
      ShowHTML "<DIV id=rodape>"
      ShowHTML "  <DIV id=endereco>"
      ShowHTML "    <P>Setor Comercial Sul, Ed. Denasa - Salas 901/902 - Brasília-DF <BR>Tel : (61) 225 6302 (61) 321 8938 | Fax (61) 225 7599| email: <A href=""mailto:pbf@cespe.unb.br"">bresil2005@minc.gov.br</A>"
      ShowHTML "    </P>"
      ShowHTML "  </DIV>"
      ShowHTML "</DIV>"
      ShowHTML "</center>"
   Else
      ShowHTML "<HR>"
   End If
   ShowHTML "</BODY>"
   ShowHTML "</HTML>"
end sub
REM -------------------------------------------------------------------------
REM Final da rotina de rodapé
REM =========================================================================

REM =========================================================================
REM Montagem da estrutura do documento
REM -------------------------------------------------------------------------
Sub Estrutura_Topo
   If Session("p_cliente") = 6761 Then
      ShowHTML "<DIV id=container>"
      ShowHTML "  <DIV id=cab>"
   End If
end sub
REM -------------------------------------------------------------------------
REM Final da rotina
REM =========================================================================

REM =========================================================================
REM Definição dos arquivos de CSS
REM -------------------------------------------------------------------------
Sub Estrutura_CSS (l_cliente)
   If cDbl(l_cliente) = 6761 Then
      ShowHTML "<LINK  media=screen href=""/siw/files/" & l_cliente & "/css/estilo.css"" type=text/css rel=stylesheet>"
      ShowHTML "<LINK media=print href=""/siw/files/" & l_cliente & "/css/print.css"" type=text/css rel=stylesheet>"
      ShowHTML "<SCRIPT language=javascript src=""/siw/files/" & l_cliente & "/js/scripts.js"" type=text/javascript> "
      ShowHTML "</SCRIPT>"
   End If
end sub
REM -------------------------------------------------------------------------
REM Final da rotina
REM =========================================================================

REM =========================================================================
REM Montagem da estrutura do documento
REM -------------------------------------------------------------------------
Sub Estrutura_Topo_Limpo
   If Session("p_cliente") = 6761 Then
      ShowHTML "<center>"
      ShowHTML "<DIV id=container_limpo>"
      ShowHTML "  <DIV id=cab>"
   End If
end sub
REM -------------------------------------------------------------------------
REM Final da rotina
REM =========================================================================

REM =========================================================================
REM Montagem do corpo do documento
REM -------------------------------------------------------------------------
Sub Estrutura_Fecha
   If Session("p_cliente") = 6761 Then
      ShowHTML "  </DIV>"
   End If
end sub
REM -------------------------------------------------------------------------
REM Final da rotina
REM =========================================================================

REM =========================================================================
REM Montagem do corpo do documento
REM -------------------------------------------------------------------------
Sub Estrutura_Corpo_Abre
   If Session("p_cliente") = 6761 Then
      ShowHTML "  <DIV id=corpo>"
   End IF
end sub
REM -------------------------------------------------------------------------
REM Final da rotina
REM =========================================================================

REM =========================================================================
REM Montagem do texto do corpo
REM -------------------------------------------------------------------------
Sub Estrutura_Texto_Abre
   If Session("p_cliente") = 6761 Then
      Dim l_rs, l_rs1, l_titulo, l_sigla, l_colunas, l_ultimo_nivel
      Set l_rs = Server.CreateObject("ADODB.RecordSet")
      Set l_rs1 = Server.CreateObject("ADODB.RecordSet")
      
      DB_GetLinkData l_rs1, w_cliente, SG
      If Not l_rs1.eof then
         If l_rs1("ultimo_nivel") = "S" Then
            l_ultimo_nivel = "S"
            l_sigla = l_rs1("sg_pai")
         Else
            l_ultimo_nivel = "N"
            l_sigla = SG
         End If
      Else
         l_ultimo_nivel = "N"
         l_sigla = SG
      End If
      ShowHTML "    <DIV id=texto>"
      If (O = "A" and w_submenu > "") or l_ultimo_nivel = "S" Then
         DB_GetLinkSubMenu l_rs, Session("p_cliente"), l_sigla
         If Not l_rs.EOF Then
            l_colunas = l_rs.RecordCount
            If l_rs1("ultimo_nivel") = "N" Then
               ShowHTML "        <DIV class=retranca>" & TP & " - " & l_rs("NOME") & "</DIV>"
            Else
               ShowHTML "        <DIV class=retranca>" & TP & " - " & l_rs1("NOME") & "</DIV>"
            End If
            ShowHTML "        <table border=0 width=""100%"" bgColor=""#BCFFBC"" style=""border: 1px solid rgb(0,0,0);"">"
            ShowHTML "          <tr valign=""top"">"
            While Not l_rs.EOF
               l_titulo = Request("TP") & " - " & l_rs("NOME")
               If Request("w_cgccpf") > "" Then
                  ShowHTML "    <td><A CLASS=""ss"" HREF=""" & replace(l_rs("LINK"),"mod_ac/",w_dir) & "&P1="&l_rs("P1")&"&P2="&l_rs("P2")&"&P3="&l_rs("P3")&"&P4="&l_rs("P4")&"&TP="&TP&"&SG="&l_rs("SIGLA")&"&O=L&w_cgccpf="&Request("w_cgccpf")& MontaFiltro("GET") & """>" & l_rs("NOME") & "</A><BR>"
               ElseIf Request("w_sq_acordo") > "" Then
                  ShowHTML "    <td><A CLASS=""ss"" HREF=""" & replace(l_rs("LINK"),"mod_ac/",w_dir) & "&P1="&l_rs("P1")&"&P2="&l_rs("P2")&"&P3="&l_rs("P3")&"&P4="&l_rs("P4")&"&TP="&TP&"&SG="&l_rs("SIGLA")&"&O=L&w_sq_acordo="&Request("w_sq_acordo")&"&w_menu="&l_rs("menu_pai")&""">" & l_rs("NOME") & "</A><BR>"
               Else
                  ShowHTML "    <td><A CLASS=""ss"" HREF=""" & replace(l_rs("LINK"),"mod_ac/",w_dir) & "&P1="&l_rs("P1")&"&P2="&l_rs("P2")&"&P3="&l_rs("P3")&"&P4="&l_rs("P4")&"&TP="&TP&"&SG="&l_rs("SIGLA")&"&O="&Request("O")&"&w_chave="&Request("w_chave")&"&w_menu="&l_rs("menu_pai")& MontaFiltro("GET") & """>" & l_rs("NOME") & "</A><BR>"
               End If
               l_rs.MoveNext 
            Wend
            ShowHTML "        </table>"
         End If
         Set l_colunas = Nothing
         Set l_sigla   = Nothing
         Set l_titulo  = Nothing
         Set l_rs      = Nothing
         Set l_rs1     = Nothing
      Else
         ShowHTML "        <DIV class=retranca>" & TP & "</DIV>"
      End If
   Else
     ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
     ShowHTML "<HR>"
   End If
end sub
REM -------------------------------------------------------------------------
REM Final da rotina
REM =========================================================================

REM =========================================================================
REM Encerramento do texto do corpo
REM -------------------------------------------------------------------------
Sub Estrutura_Texto_Fecha
   If Session("p_cliente") = 6761 Then
      ShowHTML "    </center>"
      ShowHTML "    </DIV>"
   End IF
end sub
REM -------------------------------------------------------------------------
REM Final da rotina
REM =========================================================================

REM =========================================================================
REM Montagem da estrutura do menu à esquerda
REM -------------------------------------------------------------------------
Sub Estrutura_Menu_Esquerda
   If Session("p_cliente") = 6761 Then
      ShowHTML "    <DIV id=menuesq>"
   End IF
end sub
REM -------------------------------------------------------------------------
REM Final da rotina
REM =========================================================================

REM =========================================================================
REM Montagem da estrutura do menu à esquerda
REM -------------------------------------------------------------------------
Sub Estrutura_Menu_Direita
   If Session("p_cliente") = 6761 Then
      ShowHTML "    <DIV id=menudir>"
   End IF
end sub
REM -------------------------------------------------------------------------
REM Final da rotina
REM =========================================================================

REM =========================================================================
REM Montagem do menu à esquerda
REM -------------------------------------------------------------------------
Sub Estrutura_Menu_Separador
   If Session("p_cliente") = 6761 Then
      ShowHTML "      <DIV id=menusep><HR></DIV>"
   End IF
end sub
REM -------------------------------------------------------------------------
REM Final da rotina
REM =========================================================================

REM =========================================================================
REM Montagem do menu à esquerda
REM -------------------------------------------------------------------------
Sub Estrutura_Menu_Gov_Abre
   If Session("p_cliente") = 6761 Then
      Estrutura_Menu_Separador
      ShowHTML "      <UL id=menugov>"
   End IF
end sub
REM -------------------------------------------------------------------------
REM Final da rotina
REM =========================================================================

REM =========================================================================
REM Montagem do menu à esquerda
REM -------------------------------------------------------------------------
Sub Estrutura_Menu_Nav_Abre
   If Session("p_cliente") = 6761 Then
      Estrutura_Menu_Separador
      ShowHTML "      <DIV id=menunav>"
      ShowHTML "        <UL>"
   End IF
end sub
REM -------------------------------------------------------------------------
REM Final da rotina
REM =========================================================================

REM =========================================================================
REM Montagem do menu à esquerda
REM -------------------------------------------------------------------------
Sub Estrutura_Menu_Fecha
   If Session("p_cliente") = 6761 Then
      ShowHTML "      </UL>"
   End IF
end sub
REM -------------------------------------------------------------------------
REM Final da rotina
REM =========================================================================

REM =========================================================================
REM Montagem do sub-menu à esquerda alternativo
REM -------------------------------------------------------------------------
Sub Estrutura_Corpo_Menu_Esquerda
   If Session("p_cliente") = 6761 Then
      ShowHTML "    <DIV id=menuesq>"
      ShowHTML "      <DIV id=logomenuesq><H3>BresilBresils</H3></DIV>"
   End IF
end sub
REM -------------------------------------------------------------------------
REM Final da rotina
REM =========================================================================

REM =========================================================================
REM Montagem da estrutura do documento
REM -------------------------------------------------------------------------
Sub Estrutura_Menu

   If Session("p_cliente") = 6761 Then
      Dim l_titulo, l_cont, l_cont1, l_cont2, l_cont3, l_cont4
      Dim l_RS, l_RS1, l_RS2, l_RS3
      Set l_rs = Server.CreateObject("ADODB.RecordSet")
      Set l_rs1 = Server.CreateObject("ADODB.RecordSet")
      Set l_rs2 = Server.CreateObject("ADODB.RecordSet")
      Set l_rs3 = Server.CreateObject("ADODB.RecordSet")
    
      ShowHTML "    <DIV id=cabtopo>"
      ShowHTML "      <DIV id=logoesq>"
      ShowHTML "        <H1>Ministério da Cultura</H1>"
      ShowHTML "        <br>"
      ShowHTML "        <select name=""opcoes"" onChange=""if(options[selectedIndex].value) window.location.href= (options[selectedIndex].value)"" class=""pr"">"
      ShowHTML "          <option>Destaques do governo</option>"
      ShowHTML "          <option value=""javascript:nova_jan('http://www.brasil.gov.br')"">Portal do Governo Federal</option>"
      ShowHTML "          <option value=""javascript:nova_jan('http://www.e.gov.br')"">Portal de Servi&ccedil;os do Governo</option>"
      ShowHTML "          <option value=""javascript:nova_jan('http://www.radiobras.gov.br')"">Portal da Ag&ecirc;ncia de Not&iacute;cias</option>"
      ShowHTML "          <option value=""javascript:nova_jan('http://www.brasil.gov.br/emquestao')"">Em Questão</option>"
      ShowHTML "          <option value=""javascript:nova_jan('http://www.fomezero.gov.br')"">Programa Fome Zero</option>"
      ShowHTML "        </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
      ShowHTML "      </DIV>"
      ShowHTML "      <DIV id=logodir><H2>Projeto Ano do Brasil na França</H2></DIV>"
      ShowHTML "    </DIV>"
      ShowHTML ""
      ShowHTML "    <DIV id=menutxt>"
      ShowHTML "      <SCRIPT src=""/siw/files/" & w_cliente & "/js/newcssmenu.js"" type=text/javascript></SCRIPT>"
      ShowHTML "      "
      ShowHTML "      <DIV id=menutexto>"
      ShowHTML "        <DIV id=mainMenu>"
      ShowHTML "          <UL id=menuList>"
      l_cont = 0
      'If Request("SG") = "" or (Request("SG") > "" and O = "L") Then
         DB_GetLinkDataUser l_RS, Session("p_cliente"), Session("sq_pessoa"), "IS NULL"
         l_cont = 0
         While Not l_RS.EOF
            l_titulo = l_RS("nome")
            If cDbl(l_RS("Filho")) > 0 Then
               l_cont = l_cont + 1
               ShowHTML "            <LI class=menubar>::<A class=starter href=""javascript:location.href=this.location.href;""> " & l_RS("nome") & "</A>"
               ShowHTML "            <UL class=menu id=menu" & l_cont & ">"
               l_cont1 = 0
               DB_GetLinkDataUser l_RS1, Session("p_cliente"), Session("sq_pessoa"), l_RS("sq_menu")
               While Not l_RS1.EOF
                  l_titulo = l_titulo & " - " & l_RS1("NOME")
                  If cDbl(l_RS1("Filho")) > 0 Then
                     l_cont1 = l_cont1 + 1
                     ShowHTML "              <LI><A href=""javascript:location.href=this.location.href;""><IMG height=12 alt="">"" src=""/siw/files/" & w_cliente & "/img/arrows.gif"" width=8> " & l_RS1("nome") & "</A> "
                     ShowHTML "              <UL class=menu id=menu" & l_cont & "_" & l_cont1 & ">"
                     l_cont2 = 0
                     DB_GetLinkDataUser l_RS2, Session("p_cliente"), Session("sq_pessoa"), l_RS1("sq_menu")
                     While Not l_RS2.EOF
                        l_titulo = l_titulo & " - " & l_RS2("NOME")
                        If cDbl(l_RS2("Filho")) > 0 Then
                           l_cont2 = l_cont2 + 1
                           ShowHTML "                <LI><A href=""javascript:location.href=this.location.href;""><IMG height=12 alt="">"" src=""/siw/files/" & w_cliente & "/img/arrows.gif"" width=8> " & l_RS2("nome") & "</A> "
                           ShowHTML "                <UL class=menu id=menu" & l_cont & "_" & l_cont1 & "_" & l_cont2 & ">"
                           DB_GetLinkDataUser l_RS3, Session("p_cliente"), Session("sq_pessoa"), l_RS2("sq_menu")
                           While Not l_RS3.EOF
                              l_titulo = l_titulo & " - " & l_RS3("NOME")
                              If l_RS3("externo") = "S" Then
                                 ShowHTML "                  <LI><A href=""" & replace(l_RS3("LINK"),"@files",conFileVirtual & Session("p_cliente")) & """ TARGET=""" & l_RS3("target") & """>" & l_RS3("nome") & "</A> "
                              Else
                                 ShowHTML "                  <LI><A href=""" & l_RS3("LINK") & "&P1="&l_RS3("P1")&"&P2="&l_RS3("P2")&"&P3="&l_RS3("P3")&"&P4="&l_RS3("P4")&"&TP="&l_titulo&"&SG="&l_RS3("SIGLA") & """>" & l_RS3("nome") & "</A> "
                              End If
                              l_titulo = Replace(l_titulo, " - "&l_RS3("NOME"), "")
                              l_RS3.MoveNext
                           Wend
                           ShowHTML "            </UL>"
                           l_RS3.Close
                        Else
                           If l_RS2("externo") = "S" Then
                              ShowHTML "                <LI><A href=""" & replace(l_RS2("LINK"),"@files",conFileVirtual & Session("p_cliente")) & """ TARGET=""" & l_RS2("target") & """>" & l_RS2("nome") & "</A> "
                           Else
                              ShowHTML "                <LI><A href=""" & l_RS2("LINK") & "&P1="&l_RS2("P1")&"&P2="&l_RS2("P2")&"&P3="&l_RS2("P3")&"&P4="&l_RS2("P4")&"&TP="&l_titulo&"&SG="&l_RS2("SIGLA") & """>" & l_RS2("nome") & "</A> "
                           End If
                        End If
                        l_titulo = Replace(l_titulo, " - "&l_RS2("NOME"), "")
                        l_RS2.MoveNext
                     Wend
                     ShowHTML "            </UL>"
                     l_RS2.Close
                  Else
                     If l_RS1("externo") = "S" Then
                        If l_RS1("LINK") > "" Then
                           ShowHTML "              <LI><A href=""" & replace(l_RS1("LINK"),"@files",conFileVirtual & Session("p_cliente")) & """ TARGET=""" & l_RS1("target") & """>" & l_RS1("nome") & "</A> "
                        Else
                           ShowHTML "              <LI>" & l_RS1("nome") & " "
                        End IF
                     Else
                        ShowHTML "              <LI><A href=""" & l_RS1("LINK") & "&P1="&l_RS1("P1")&"&P2="&l_RS1("P2")&"&P3="&l_RS1("P3")&"&P4="&l_RS1("P4")&"&TP="&l_titulo&"&SG="&l_RS1("SIGLA") & """>" & l_RS1("nome") & "</A> "
                     End If
                  End If
                  l_titulo = Replace(l_titulo, " - "&l_RS1("NOME"), "")
                  l_RS1.MoveNext
               Wend
               ShowHTML "            </UL>"
               l_RS1.Close
            Else
               If l_RS("externo") = "S" Then
                  ShowHTML "            <LI class=menubar>::<A class=starter href=""" & replace(l_RS("LINK"),"@files",conFileVirtual & Session("p_cliente")) & """ TARGET=""" & l_RS("target") & """> " & l_RS("nome") & "</A>"
               Else
                  ShowHTML "            <LI class=menubar>::<A class=starter href=""" & l_RS("LINK") & "&P1="&l_RS("P1")&"&P2="&l_RS("P2")&"&P3="&l_RS("P3")&"&P4="&l_RS("P4")&"&TP="&l_titulo&"&SG="&l_RS("SIGLA") & """> " & l_RS("nome") & "</A>"
               End If
            End If
            l_RS.MoveNext
         Wend
         l_RS.Close
      'End If
      ShowHTML "            <LI class=menubar>::<A class=starter href=""" & w_dir & "Menu.asp?par=Sair"" & "" onClick=""return(confirm('Confirma saída do sistema?'));""> Sair</A>"

      ShowHTML "          </UL>"
      ShowHTML "        </DIV>"
      ShowHTML "      </DIV>"
      ShowHTML "    </DIV>"
    
      Set l_titulo = Nothing
      Set l_cont   = Nothing
      Set l_cont1  = Nothing
      Set l_cont2  = Nothing
      Set l_cont3  = Nothing
      Set l_cont4  = Nothing
      Set l_RS     = Nothing
      Set l_RS1    = Nothing
      Set l_RS2    = Nothing
      Set l_RS3    = Nothing
   End IF
end sub
REM -------------------------------------------------------------------------
REM Final da rotina
REM =========================================================================

REM =========================================================================
REM Abre conexão com o banco de dados
REM -------------------------------------------------------------------------
Sub AbreSessao
   Set dbms = Server.CreateObject("ADODB.Connection")
   Set SP   = Server.CreateObject("ADODB.Command")
   with dbms
      select case Session("dbms")
         case 4 ' PgSQL
            .ConnectionString = strconn4
            sp.CommandType    = adCmdStoredProc
            Session("schema")    = strschema
         case 3 ' Oracle 8.1.7
            .ConnectionString = strconn3
            sp.CommandType    = adCmdStoredProc
            Session("schema")    = ""
         case 2 ' MS SQL Server 2000
            .ConnectionString = strconn2
            sp.CommandType    = adCmdStoredProc
            Session("schema")    = strschema
         case else ' Oracle 9.2
            .ConnectionString = strconn
            sp.CommandType    = adCmdStoredProc
            Session("schema")    = strschema
      End Select
      .open
      .CursorLocation = adUseClient
   end with
   sp.ActiveConnection  = dbms
   Session("schema_is") = strschema_is
end sub
REM -------------------------------------------------------------------------
REM Final do procedimento que obtém sessão do pool de conexões
REM =========================================================================

REM =========================================================================
REM Fecha conexão com o banco de dados
REM -------------------------------------------------------------------------
Sub FechaSessao
   dbms.close
end sub
REM -------------------------------------------------------------------------
REM Final do procedimento que obtém sessão do pool de conexões
REM =========================================================================

REM =========================================================================
REM Cria objeto gráfico
REM -------------------------------------------------------------------------
Sub CriaGrafico
   Set graph = Server.CreateObject("GraphLib.Graph")
end sub
REM -------------------------------------------------------------------------
REM Final da rotina
REM =========================================================================

REM =========================================================================
REM Cria parâmetro apenas para OLE/DB da Oracle
REM -------------------------------------------------------------------------
Sub SetProperty (state)
    sp.Properties("PLSQLRSet")= state
end sub
REM -------------------------------------------------------------------------
REM Final do procedimento que obtém sessão do pool de conexões
REM =========================================================================

REM =========================================================================
REM Rotina de execução de queries
REM -------------------------------------------------------------------------
Sub ExecutaSQL(p_SQL)
   On Error Resume Next
   dbms.Execute(p_sql)
   If Err.Description > "" Then 
      TrataErro 
   End If
end sub
REM -------------------------------------------------------------------------
REM Final da rotina de execução de queries 
REM =========================================================================

REM =========================================================================
REM Rotina de execução de queries sem indicação de erro
REM -------------------------------------------------------------------------
Sub ExecutaSQL_Resume(p_SQL)
   On Error Resume Next
   dbms.Execute(p_sql)
end sub
REM -------------------------------------------------------------------------
REM Final da rotina de execução de queries sem indicação de erro
REM =========================================================================

REM =========================================================================
REM Rotina de Abertura do BD para execução de queries
REM -------------------------------------------------------------------------
Sub ConectaBD(p_Query)
   On Error Resume Next
   Set RS = dbms.Execute(p_Query)
   If Err.number > 0 Then
      TrataErro
      Err.Clear
   End If
end sub
REM -------------------------------------------------------------------------
REM Final do procedimento de abertura do BD
REM =========================================================================

REM =========================================================================
REM Rotina de Abertura do BD para execução de queries
REM -------------------------------------------------------------------------
Sub AbreRS(p_rs, p_query)
   'On Error Resume Next
   If p_rs.state <> 0 Then
      p_rs.close
   End If
   p_rs.Filter = ""
   p_rs.Sort   = ""
   p_rs.Open p_query, dbms, adOpenStatic
   If Err.number > 0 Then
      TrataErro
      Err.Clear
   End If
end sub
REM -------------------------------------------------------------------------
REM Final do procedimento de abertura do BD
REM =========================================================================

REM =========================================================================
REM Rotina de Fechamento do BD
REM -------------------------------------------------------------------------
Sub DesConectaBD
   If rs.state <> 0 Then 
      RS.Close
   End if
end sub
REM -------------------------------------------------------------------------
REM Final do procedimento de fechamento do BD
REM =========================================================================

REM -------------------------------------------------------------------------
REM Verifica se a Assinatura Eletronica do usuário está correta
REM =========================================================================
Function VerificaAssinaturaEletronica(Usuario,Senha)

   If Senha > "" Then
      If DB_VerificaAssinatura(Session("p_cliente"), Usuario, Senha) = 0 Then
         VerificaAssinaturaEletronica = True
      Else
         VerificaAssinaturaEletronica = False
      End If
   Else
      VerificaAssinaturaEletronica = True
   End If

End Function
REM -------------------------------------------------------------------------
REM Fim da verificação se a Assinatura Eletronica do usuário está correta
REM =========================================================================

REM -------------------------------------------------------------------------
REM Verifica se a senha de acesso do usuário está correta
REM =========================================================================
Function VerificaSenhaAcesso(Usuario,Senha)

   If DB_VerificaSenha(Session("p_cliente"), Usuario, Senha) = 0 Then
      VerificaSenhaAcesso = True
   Else
      VerificaSenhaAcesso = False
   End If

End Function
REM -------------------------------------------------------------------------
REM Fim da verificação se a senha de acesso do usuário está correta
REM =========================================================================

REM =========================================================================
REM Função que formata dias, horas, minutos e segundos a partir dos segundos
REM -------------------------------------------------------------------------
Function FormataDataEdicao(w_dt_grade)
  Dim l_dt_grade
  l_dt_grade = Nvl(w_dt_grade,"")
  If l_dt_grade > "" Then
     If Len(l_dt_grade) < 10 Then
        If Right(Mid(l_dt_grade,1,2),1) = "/" Then
           l_dt_grade = "0"&l_dt_grade
        End If
        If Len(l_dt_grade) < 10 and Right(Mid(l_dt_grade,4,2),1) = "/" Then
           l_dt_grade = Left(l_dt_grade,3)&"0"&Right(l_dt_grade,6)
        End If 
     End If
  Else
     l_dt_grade = ""
  End If

  FormataDataEdicao = l_dt_grade

  Set l_dt_grade       = Nothing
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Função que retorna o último dia da data informada
REM -------------------------------------------------------------------------
Function Last_Day(w_valor)
  Dim l_valor, l_dia, l_mes, l_ano, l_cont, l_result
  l_valor = FormataDataEdicao(w_valor)
  l_dia   = Mid(l_valor,1,2)
  l_mes   = Mid(l_valor,4,2)
  l_ano   = Mid(l_valor,7,4)
  
  For l_cont = 31 to 28 step -1
     If IsDate(l_cont & Mid(l_valor,3,1) & l_mes & Mid(l_valor,3,1) & l_ano) Then
        l_result = l_cont & Mid(l_valor,3,1) & l_mes & Mid(l_valor,3,1) & l_ano
        l_cont   = 28
     End If
  Next

  Last_Day = l_result

  Set l_valor  = Nothing
  Set l_dia    = Nothing 
  Set l_mes    = Nothing 
  Set l_ano    = Nothing 
  Set l_cont   = Nothing 
  Set l_result = Nothing
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Função que retorna o primeiro dia da data informada
REM -------------------------------------------------------------------------
Function First_Day(w_valor)
  Dim l_valor, l_mes, l_ano
  l_valor = FormataDataEdicao(w_valor)
  l_mes   = Mid(l_valor,4,2)
  l_ano   = Mid(l_valor,7,4)
  
  First_Day = "01" & Mid(l_valor,3,1) & l_mes & Mid(l_valor,3,1) & l_ano

  Set l_valor  = Nothing
  Set l_mes    = Nothing 
  Set l_ano    = Nothing 
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

'Limpa Mascara para gravar os dados no banco de dados
Function LimpaMascara(Campo)
   LimpaMascara = replace(replace(replace(replace(replace(replace(campo,",",""),";",""),".",""),"-",""),"/",""),"\","")
End Function

' Cria a tag Body
Sub BodyOpen(cProperties)
   If Session("p_cliente") = 6761 Then
      ShowHTML "<body Text=""" & conBodyText & """ " & cProperties & "> " 
   Else
      ShowHTML "<BASEFONT FACE=""Verdana"" SIZE=""2""> "
      ShowHTML "<style> "
      ShowHTML " .ss{text-decoration:none;font:bold 8pt} "
      ShowHTML " .ss:HOVER{text-decoration: underline;} "
      ShowHTML " .hl{text-decoration:none;font:Arial;color=""#0000FF""} "
      ShowHTML " .hl:HOVER{text-decoration: underline;} "
      ShowHTML " .ttm{font: 10pt Arial}"
      ShowHTML " .btm{font: 8pt Verdana}"
      ShowHTML " .xtm{font: 12pt Verdana}"
      ShowHTML " .sti {font-size: 8pt; border: 1px solid #000000; background-color: #F5F5F5}"
      ShowHTML " .stb {font-size: 8pt; color: #000000; border: 1pt solid #000000; background-color: #C0C0C0; }"
      ShowHTML " .sts {font-size: 8pt; border-top: 1px solid #000000; background-color: #F5F5F5}"
      ShowHTML " .str {font-size: 8pt; border-top: 0px}"
      ShowHTML " .stc {font-size: 8pt; border-top: 0px}"
      ShowHTML "</style> "
      ShowHTML "<STYLE TYPE=""text/css"">"
      ShowHTML "<!--"
      ShowHTML "BODY {OVERFLOW:scroll;OVERFLOW-X:hidden}"
      ShowHTML ".DEK {POSITION:absolute;VISIBILITY:hidden;Z-INDEX:200;}"
      ShowHTML "//-->"
      ShowHTML "</STYLE>"
      ShowHTML "<body Text=""" & conBodyText & """ Link=""" & conBodyLink & """ Alink=""" & conBodyALink & """ " & _
            "Vlink=""" & conBodyVLink & """ Bgcolor=""" & conBodyBgcolor & """ Background=""" & conBodyBackground & """ " & _
            "Bgproperties=""" & conBodyBgproperties & """ Topmargin=""" & conBodyTopmargin & """ " & _
            "Leftmargin=""" & conBodyLeftmargin & """ " & cProperties & "> " 
      ShowHTML "<DIV ID=""dek"" CLASS=""dek""></DIV>"
      ShowHTML "<SCRIPT TYPE=""text/javascript"">"
      ShowHTML "<!--"
      ShowHTML "Xoffset=-100;    // modify these values to ..."
      ShowHTML "Yoffset= 20;    // change the popup position."
      ShowHTML "var nav,old,iex=(document.all),yyy=-1000;"
      ShowHTML "if(navigator.appName==""Netscape""){(document.layers)?nav=true:old=true;}"
      ShowHTML "if(!old){"
      ShowHTML "var skn=(nav)?document.dek:dek.style;"
      ShowHTML "if(nav)document.captureEvents(Event.MOUSEMOVE);"
      ShowHTML "document.onmousemove=get_mouse;"
      ShowHTML "}"
      ShowHTML "function popup(msg,bak){"
      ShowHTML "var content='<TABLE  WIDTH=200 BORDER=1 BORDERCOLOR=black CELLPADDING=2 CELLSPACING=0 BGCOLOR='+bak+'><TD><DIV ALIGN=""JUSTIFY""><FONT COLOR=black SIZE=1>'+msg+'</FONT></DIV></TD></TABLE>';"
      ShowHTML "if(old){alert(msg);return;} "
      ShowHTML "else{yyy=Yoffset;"
      ShowHTML " if(nav){skn.document.write(content);skn.document.close();skn.visibility=""visible""}"
      ShowHTML " if(iex){document.all(""dek"").innerHTML=content;skn.visibility=""visible""}"
      ShowHTML " }"
      ShowHTML "}"
      ShowHTML "function popup1(msg,bak){"
      ShowHTML "var content='<TABLE  WIDTH=450 BORDER=1 BORDERCOLOR=black CELLPADDING=2 CELLSPACING=0 BGCOLOR='+bak+'><TD><DIV ALIGN=""JUSTIFY""><FONT COLOR=black SIZE=1>'+msg+'</FONT></DIV></TD></TABLE>';"
      ShowHTML "if(old){alert(msg);return;} "
      ShowHTML "else{yyy=Yoffset;"
      ShowHTML " if(nav){skn.document.write(content);skn.document.close();skn.visibility=""visible""}"
      ShowHTML " if(iex){document.all(""dek"").innerHTML=content;skn.visibility=""visible""}"
      ShowHTML " }"
      ShowHTML "}"
      ShowHTML "function get_mouse(e){"
      ShowHTML "var x=(nav)?e.pageX:event.x+document.body.scrollLeft;skn.left=x+Xoffset;"
      ShowHTML "var y=(nav)?e.pageY:event.y+document.body.scrollTop;skn.top=y+yyy;"
      ShowHTML "}"
      ShowHTML "function kill(){"
      ShowHTML "if(!old){yyy=-1000;skn.visibility=""hidden"";}"
      ShowHTML "}"
      ShowHTML "//-->"
      ShowHTML "</SCRIPT>"
   End IF
End Sub

' Cria a tag Body
Sub BodyOpenClean(cProperties)
ShowHTML "<BASEFONT FACE=""Verdana"" SIZE=""2""> "
ShowHTML "<style> "
ShowHTML " .ss{text-decoration:none;font:bold 8pt} "
ShowHTML " .ss:HOVER{text-decoration: underline;} "
ShowHTML " .hl{text-decoration:none;font:Arial;color=""#0000FF""} "
ShowHTML " .hl:HOVER{text-decoration: underline;} "
ShowHTML " .ttm{font: 10pt Arial}"
ShowHTML " .btm{font: 8pt Verdana}"
ShowHTML " .xtm{font: 12pt Verdana}"
ShowHTML " .sti {font-size: 8pt; border: 1px solid #000000; background-color: #F5F5F5}"
ShowHTML " .stb {font-size: 8pt; color: #000000; border: 1pt solid #000000; background-color: #C0C0C0; }"
ShowHTML " .sts {font-size: 8pt; border-top: 1px solid #000000; background-color: #F5F5F5}"
ShowHTML " .str {font-size: 8pt; border-top: 0px}"
ShowHTML " .stc {font-size: 8pt; border-top: 0px}"
ShowHTML "</style> "
ShowHTML "<body Text=""" & conBodyText & """ Link=""" & conBodyLink & """ Alink=""" & conBodyALink & """ " & _
    "Vlink=""" & conBodyVLink & """ Bgcolor=""" & conBodyBgcolor & """ Background=""" & conBodyBackground & """ " & _
    "Bgproperties=""" & conBodyBgproperties & """ Topmargin=""" & conBodyTopmargin & """ " & _
    "Leftmargin=""" & conBodyLeftmargin & """ " & cProperties & "> " 
End Sub

' Cria a tag Body
Function BodyOpenMail(cProperties)
Dim l_html
l_html = "<BASEFONT FACE=""Verdana"" SIZE=""2""> " & VbCrLf
l_html = l_html & "<style> " & VbCrLf
l_html = l_html & " .ss{text-decoration:none;font:bold 8pt} " & VbCrLf
l_html = l_html & " .ss:HOVER{text-decoration: underline;} " & VbCrLf
l_html = l_html & " .hl{text-decoration:none;font:Arial;color=""#0000FF""} " & VbCrLf
l_html = l_html & " .hl:HOVER{text-decoration: underline;} " & VbCrLf
l_html = l_html & " .ttm{font: 10pt Arial}" & VbCrLf
l_html = l_html & " .btm{font: 8pt Verdana}" & VbCrLf
l_html = l_html & " .xtm{font: 12pt Verdana}" & VbCrLf
l_html = l_html & " .sti {font-size: 8pt; border: 1px solid #000000; background-color: #F5F5F5}"  & VbCrLf
l_html = l_html & " .stb {font-size: 8pt; color: #000000; border: 1pt solid #000000; background-color: #C0C0C0; }"  & VbCrLf
l_html = l_html & " .sts {font-size: 8pt; border-top: 1px solid #000000; background-color: #F5F5F5}"  & VbCrLf
l_html = l_html & " .str {font-size: 8pt; border-top: 0px}"  & VbCrLf
l_html = l_html & " .stc {font-size: 8pt; border-top: 0px}"  & VbCrLf
l_html = l_html &  "</style> " & VbCrLf
l_html = l_html &  "<body Text=""" & conBodyText & """ Link=""" & conBodyLink & """ Alink=""" & conBodyALink & """ " & _
    "Vlink=""" & conBodyVLink & """ Bgcolor=""" & conBodyBgcolor & """ Background=""" & conBodyBackground & """ " & _
    "Bgproperties=""" & conBodyBgproperties & """ Topmargin=""" & conBodyTopmargin & """ " & _
    "Leftmargin=""" & conBodyLeftmargin & """ " & cProperties & "> " & VbCrLf
BodyOpenMail = l_html
Set l_html = Nothing
End Function

Sub BodyOpenImage(cProperties, cImage, cFixed)
ShowHTML "<BASEFONT FACE=""Verdana"" SIZE=""2""> "
ShowHTML "<style> "
ShowHTML " .ss{text-decoration:none;font:bold 8pt} "
ShowHTML " .ss:HOVER{text-decoration: underline;} "
ShowHTML " .hl{text-decoration:none;font:Arial;color=""#0000FF""} "
ShowHTML " .hl:HOVER{text-decoration: underline;} "
ShowHTML " .ttm{font: 10pt Arial}"
ShowHTML " .btm{font: 8pt Verdana}"
ShowHTML " .xtm{font: 12pt Verdana}"
ShowHtml " .sti {font-size: 8pt; border: 1px solid #000000; background-color: #F5F5F5}"  & VbCrLf
ShowHtml " .stb {font-size: 8pt; color: #000000; border: 1pt solid #000000; background-color: #C0C0C0; }"  & VbCrLf
ShowHtml " .sts {font-size: 8pt; border-top: 1px solid #000000; background-color: #F5F5F5}"  & VbCrLf
ShowHtml " .str {font-size: 8pt; border-top: 0px}"  & VbCrLf
ShowHtml " .stc {font-size: 8pt; border-top: 0px}"  & VbCrLf
ShowHTML "</style> "
ShowHTML "<STYLE TYPE=""text/css"">"
ShowHTML "<!--"
ShowHTML "BODY {OVERFLOW:scroll;OVERFLOW-X:hidden}"
ShowHTML ".DEK {POSITION:absolute;VISIBILITY:hidden;Z-INDEX:200;}"
ShowHTML "//-->"
ShowHTML "</STYLE>"
ShowHTML "<body Text=""" & conBodyText & """ Link=""" & conBodyLink & """ Alink=""" & conBodyALink & """ " & _
    "Vlink=""" & conBodyVLink & """ Bgcolor=""" & conBodyBgcolor & """ Background=""" & cImage & """ " & _
    "Bgproperties=""" & cFixed & """ Topmargin=""" & conBodyTopmargin & """ " & _
    "Leftmargin=""" & conBodyLeftmargin & """ " & cProperties & "> " 
ShowHTML "<DIV ID=""dek"" CLASS=""dek""></DIV>"
ShowHTML "<SCRIPT TYPE=""text/javascript"">"
ShowHTML "<!--"
ShowHTML "Xoffset=-100;    // modify these values to ..."
ShowHTML "Yoffset= 20;    // change the popup position."
ShowHTML "var nav,old,iex=(document.all),yyy=-1000;"
ShowHTML "if(navigator.appName==""Netscape""){(document.layers)?nav=true:old=true;}"
ShowHTML "if(!old){"
ShowHTML "var skn=(nav)?document.dek:dek.style;"
ShowHTML "if(nav)document.captureEvents(Event.MOUSEMOVE);"
ShowHTML "document.onmousemove=get_mouse;"
ShowHTML "}"
ShowHTML "function popup(msg,bak){"
ShowHTML "var content='<TABLE  WIDTH=200 BORDER=1 BORDERCOLOR=black CELLPADDING=2 CELLSPACING=0 BGCOLOR='+bak+'><TD><DIV ALIGN=""JUSTIFY""><FONT COLOR=black SIZE=1>'+msg+'</FONT></DIV></TD></TABLE>';"
ShowHTML "if(old){alert(msg);return;} "
ShowHTML "else{yyy=Yoffset;"
ShowHTML " if(nav){skn.document.write(content);skn.document.close();skn.visibility=""visible""}"
ShowHTML " if(iex){document.all(""dek"").innerHTML=content;skn.visibility=""visible""}"
ShowHTML " }"
ShowHTML "}"
ShowHTML "function popup1(msg,bak){"
ShowHTML "var content='<TABLE  WIDTH=450 BORDER=1 BORDERCOLOR=black CELLPADDING=2 CELLSPACING=0 BGCOLOR='+bak+'><TD><DIV ALIGN=""JUSTIFY""><FONT COLOR=black SIZE=1>'+msg+'</FONT></DIV></TD></TABLE>';"
ShowHTML "if(old){alert(msg);return;} "
ShowHTML "else{yyy=Yoffset;"
ShowHTML " if(nav){skn.document.write(content);skn.document.close();skn.visibility=""visible""}"
ShowHTML " if(iex){document.all(""dek"").innerHTML=content;skn.visibility=""visible""}"
ShowHTML " }"
ShowHTML "}"
ShowHTML "function get_mouse(e){"
ShowHTML "var x=(nav)?e.pageX:event.x+document.body.scrollLeft;skn.left=x+Xoffset;"
ShowHTML "var y=(nav)?e.pageY:event.y+document.body.scrollTop;skn.top=y+yyy;"
ShowHTML "}"
ShowHTML "function kill(){"
ShowHTML "if(!old){yyy=-1000;skn.visibility=""hidden"";}"
ShowHTML "}"
ShowHTML "//-->"
ShowHTML "</SCRIPT>"

End Sub

' Imprime uma linha HTML
Sub ShowHtml(Line)
  Response.Write Line & CHR(13) & CHR(10)
End Sub
%>

