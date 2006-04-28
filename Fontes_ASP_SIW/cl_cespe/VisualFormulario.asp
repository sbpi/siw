<%
REM =========================================================================
REM Rotina de visualiza��o dos dados do projeto
REM -------------------------------------------------------------------------
Function VisualFormulario(w_chave, O, w_usuario, w_sq_pessoa, P1, P4)

  Dim Rsquery, w_Erro
  Dim w_Imagem, w_html, w_TrBgColor
  Dim w_ImagemPadrao, w_tramite
  Dim w_tipo_visao
  Dim w_p2, w_fases
  Dim w_acordo
  Set RsQuery = Server.CreateObject("ADODB.RecordSet")
  
  If P4 = 1 Then w_TrBgColor = "" Else w_TrBgColor = conTrBgColor End If
  
  w_html = ""

  ' Verifica se o cliente tem o m�dulo de acordos contratado
  DB_GetSiwCliModLis RS, w_cliente, null, "AC"
  If Not RS.EOF Then w_acordo = "S" Else w_acordo = "N" End If
  DesconectaBD

  ' Recupera os dados do projeto
  DB_GetSolicData RS, w_chave, "PJGERAL"
  w_tramite = RS("sq_siw_tramite")

  ' Recupera o tipo de vis�o do usu�rio
  If cDbl(Nvl(RS("solicitante"),0))  = cDbl(w_usuario) or _
     cDbl(Nvl(RS("executor"),0))     = cDbl(w_usuario) or _
     cDbl(Nvl(RS("cadastrador"),0))  = cDbl(w_usuario) or _
     cDbl(Nvl(RS("titular"),0))      = cDbl(w_usuario) or _
     cDbl(Nvl(RS("substituto"),0))   = cDbl(w_usuario) or _
     cDbl(Nvl(RS("tit_exec"),0))     = cDbl(w_usuario) or _
     cDbl(Nvl(RS("subst_exec"),0))   = cDbl(w_usuario) or _
     SolicAcesso(w_chave, w_usuario) >= 8 Then
     ' Se for solicitante, executor ou cadastrador, tem vis�o completa
     w_tipo_visao = 0
  Else
     DB_GetSolicInter Rsquery, w_chave, w_usuario, "REGISTRO"
     If Not RSquery.EOF Then
        ' Se for interessado, verifica a vis�o cadastrada para ele.
        w_tipo_visao = cDbl(RSquery("tipo_visao"))
     Else
        DB_GetSolicAreas Rsquery, w_chave, Session("sq_lotacao"), "REGISTRO"
        If Not RSquery.EOF Then
           ' Se for de uma das unidades envolvidas, tem vis�o parcial
           w_tipo_visao = 1
        Else
           ' Caso contr�rio, tem vis�o resumida
           w_tipo_visao = 2
        End If

        If SolicAcesso(w_chave, w_usuario) > 2 Then w_tipo_visao = 1 End If
     End If
  End If
  DesconectaBD
  ' Se for listagem ou envio, exibe os dados de identifica��o do projeto
  If O = "L" or O = "V" or O = "T" Then ' Se for listagem dos dados
     DB_GetViagemBenef RS, w_chave, w_cliente, w_sq_pessoa, null, null, null, null, null, null
     w_html = w_html & VbCrLf & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
     w_html = w_html & VbCrLf & "<tr bgcolor=""" & w_TrBgColor & """>"

     w_html = w_html & VbCrLf & "       <tr>"
     w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1""><b>PROGRAMA DE DIFUS�O CULTURAL - CONCESS�O DE PASSAGENS<BR>FORMUL�RIO</td>"
     w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"" cellspacing=""0"">"
     w_html = w_html & VbCrLf & "      <tr valign=""bottom"">"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""4"" align=""center"" height=""10""></td></tr>"
     
     ' 1. Identifica��o do Benefici�rio
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""4"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>1. IDENTIFICA��O DO BENEFICI�RIO </b>(a ser preenchido pelo solicitante e, no cado de grupo, por cada componente)</td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""3"" align=""left"" valign=""top"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>Nome: do Benefici�rio </b><br>"& RS("nm_pessoa")&"</td>"
     w_html = w_html & VbCrLf & "          <td colspan=""1"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>Fun��o no grupo</b><br>&nbsp;</td>"
     DesconectaBD
     DB_GetSolicData RS, w_chave, "PJGERAL"
     DB_GetBenef RSQuery, w_cliente, Nvl(RS("outra_parte"),0), null, null, null, Nvl(RS("sq_tipo_pessoa"),0), null, null
     If cDbl(Nvl(RS("sq_tipo_pessoa"),0)) = 1 Then
     w_html = w_html & VbCrLf & "      <tr><td colspan=""3"" align=""left"" valign=""top"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>Nome do grupo </b></td>"
     w_html = w_html & VbCrLf & "          <td colspan=""1"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>CNPJ</b><br></td>"
     Else
     w_html = w_html & VbCrLf & "      <tr><td colspan=""3"" align=""left"" valign=""top"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>Nome do grupo </b><br>" & RSQuery("nm_pessoa") & " (" & RSQuery("nome_resumido") & ")</td>"
     w_html = w_html & VbCrLf & "          <td colspan=""1"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>CNPJ</b><br>" & RSQuery("cnpj") &"</td>"
     End IF

     DesconectaBD
     
     DB_GetViagemBenef RS, w_chave, w_cliente, w_sq_pessoa, null, null, null, null, null, null
     w_html = w_html & VbCrLf & "      <tr><td colspan=""1"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>Carteira de identidade</b><br> "& Nvl(RS("rg_numero"),"---")&"</td>"
     w_html = w_html & VbCrLf & "          <td colspan=""1"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>�rg�o expedidor</b><br> "& Nvl(RS("rg_emissor"),"---")&"</td>"
     w_html = w_html & VbCrLf & "          <td colspan=""1"" align=""left"" valign=""top"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>CPF <br></b>"& RS("cpf")&"</td>"
     w_html = w_html & VbCrLf & "          <td colspan=""1"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>Profiss�o</b><br>&nbsp;</td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""4"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>N�vel de instru��o: (&nbsp;&nbsp;&nbsp;&nbsp;)B�sico (&nbsp;&nbsp;&nbsp;&nbsp;)M�dio (&nbsp;&nbsp;&nbsp;&nbsp;)Superior (&nbsp;&nbsp;&nbsp;&nbsp;)Outros</b></td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""4"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>J� foi beneficiado anteriormente pelo programa? <br> SIM (&nbsp;&nbsp;&nbsp;&nbsp;)      Quando?</b> ____________________________________________________________ <b>N�O (&nbsp;&nbsp;&nbsp;&nbsp;)</b></td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""4"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>Endere�o </b><br>&nbsp;</td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>Cidade</b><br>&nbsp;</td>"
     w_html = w_html & VbCrLf & "          <td colspan=""1"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>UF</b><br>&nbsp;</td>"
     w_html = w_html & VbCrLf & "          <td colspan=""1"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>CEP</b><br>&nbsp;</td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""1"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>Telefone Residencial </b><br>("&Nvl(RS("ddd"),"---")&") "& RS("nr_telefone") &"</td>"
     w_html = w_html & VbCrLf & "          <td colspan=""1"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>Telefone Comercial </b><br>("&Nvl(RS("ddd"),"---")&")</td>"
     w_html = w_html & VbCrLf & "          <td colspan=""1"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>FAX </b><br>("&Nvl(RS("ddd"),"")&") "& Nvl(RS("nr_fax"),"&nbsp;&nbsp;&nbsp;&nbsp;") &"</td>"
     w_html = w_html & VbCrLf & "          <td colspan=""1"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>E-mail </b><br>&nbsp;</td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""4"" align=""center"" height=""20""></td></tr>"
     DesconectaBD
     ' Identifica��o do Evento
     DB_GetSolicData RS, w_chave, "PJGERAL"
     w_tramite = RS("sq_siw_tramite")
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""4"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>2. IDENTIFICA��O DO EVENTO</td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""4"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>Nome </b>"& RS("titulo")&"</td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""1"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>Cidade </b><br>&nbsp;</td>"
     w_html = w_html & VbCrLf & "          <td colspan=""1"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>Pa�s </b><br>&nbsp;</td>"
     w_html = w_html & VbCrLf & "          <td colspan=""1"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>Telefone </b><br>(&nbsp;&nbsp;&nbsp;&nbsp;)</td>"
     w_html = w_html & VbCrLf & "          <td colspan=""1"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>FAX </b><br>(&nbsp;&nbsp;&nbsp;&nbsp;)</td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""4"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>Entidade Realizadora </b>"& RS("nm_prop")&"</td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""3"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>Per�odo de participa��o no evento<br>De ____/____/____  �  ____/____/____ </b>&nbsp;</td>"
     w_html = w_html & VbCrLf & "          <td colspan=""1"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>Quantidade de passagens </b><br>1</td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""4"" align=""center"" height=""10""></td></tr>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""4"" valign=""top"" align=""left"" height=""100"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>2.1. DESCRI��O DO EVENTO (Descreva, sucintamenten, no que consiste o evento e a sua relev�ncia nacional e internacional) </b><br>"& RS("descricao")&"</td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""4"" align=""center"" height=""10""></td></tr>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""4"" valign=""top"" align=""left"" height=""100"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>2.2. ENTIDADE PROMOTORA (Informe sobre a import�ncia da entidade promotora do evento no cen�rio art�stico/cultural nacional e internacional) </b></td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""4"" align=""center"" height=""20""></td></tr>"
     
     ' JUSTIFICATIVA DA SOLICITA��O
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""4"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>3. JUSTIFICATIVA DA SOLICITA��O</td>"
     If Nvl(RS("justificativa"),"") = "" Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""4"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>3.1. De que forma sua participa��o no evento contribuir� para com a divulga��o da cultura brasileira? </b><br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br></td>"
     Else
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""4"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>3.1. De que forma sua participa��o no evento contribuir� para com a divulga��o da cultura brasileira? </b><br>"& RS("justificativa")&"&nbsp;</td>"
     End If
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""4"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>3.2. Descreva o trabalho a ser apresentado e suar perspectivas e repercuss�o. No caso de espet�culos, citar o n� de apresenta��es e os locais </b><br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br></td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""4"" align=""center"" height=""20""></td></tr>"
     DesconectaBD
     
     ' ROTEIRO DE VIAGEM
     DB_GetViagemBenef RS, w_chave, w_cliente, w_sq_pessoa, null, null, null, null, null, null
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""4"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>4. ROTEIRO DE VIAGEM</td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>Origem </b><br>"& RS("nm_cidade_origem")&"<br><br><b>Destino </b><br>"& RS("nm_cidade_destino")&"<br><br></td>"
     w_html = w_html & VbCrLf & "          <td colspan=""2"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>Data prevista de sa�da <br></b>"& FormataDataEdicao(RS("saida"))&"<br><br><b>Data prevista de retorno<br> </b>"& FormataDataEdicao(RS("retorno"))&"<br><br></td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""4"" align=""center"" height=""20""></td></tr>"
     DesconectaBD
     ' CONTRAPARTIDA
     w_html = w_html & VbCrLf & "    <tr><td valign=""top"" colspan=""4"" align=""left"" style=""border: 1px solid rgb(0,111,150);""><font size=""1""><b>5. CONTRAPARTIDA</td>"
     w_html = w_html & VbCrLf & "    <tr><td colspan=""4"" style=""border: 1px solid rgb(0,111,150);""><table width=""99%"" border=""0"">"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""1"" align=""left""><font size=""1""><b>(&nbsp;&nbsp;&nbsp;&nbsp;)  cat�logo </b><br></td>"
     w_html = w_html & VbCrLf & "          <td colspan=""3"" align=""left""><font size=""1""><b>(&nbsp;&nbsp;&nbsp;&nbsp;)  programa do evento </b><br></td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""1"" align=""left""><font size=""1""><b>(&nbsp;&nbsp;&nbsp;&nbsp;)  Seguro das obras/equipamentos </b><br></td>"
     w_html = w_html & VbCrLf & "          <td colspan=""3"" align=""left""><font size=""1""><b>(&nbsp;&nbsp;&nbsp;&nbsp;)  transporte das obras/equipamentos </b><br></td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""1"" align=""left""><font size=""1""><b>(&nbsp;&nbsp;&nbsp;&nbsp;)  convite </b><br></td>"
     w_html = w_html & VbCrLf & "          <td colspan=""3"" align=""left""><font size=""1""><b>(&nbsp;&nbsp;&nbsp;&nbsp;)  estada </b><br></td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""1"" align=""left""><font size=""1""><b>(&nbsp;&nbsp;&nbsp;&nbsp;)  alimenta��o </b><br></td>"
     w_html = w_html & VbCrLf & "          <td colspan=""3"" align=""left""><font size=""1""><b>(&nbsp;&nbsp;&nbsp;&nbsp;)  taxa de inscri��o no evento </b><br></td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""1"" align=""left""><font size=""1""><b>(&nbsp;&nbsp;&nbsp;&nbsp;)  passagens </b><br></td>"
     w_html = w_html & VbCrLf & "          <td colspan=""3"" align=""left""><font size=""1""><b>(&nbsp;X&nbsp;)  inser��o de logomarcas no material promocional <br></td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""4"" align=""left""><font size=""1""><b>(&nbsp;&nbsp;&nbsp;&nbsp;)  outros:____________________________________________________________________________ <br></td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""4"" align=""left""><font size=""1""><b>Institui��es participantes:___________________________________________________________________________________ <br></td>"
     w_html = w_html & VbCrLf & "          </table>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""4"" align=""center"" height=""20""></td></tr>"
     
     
     w_html = w_html & VbCrLf & "    <tr><td colspan=""4"" style=""border: 1px solid rgb(0,111,150);""><table width=""99%"" border=""0""><font size=""1""><b></td>"
     w_html = w_html & VbCrLf & "        <tr><td colspan=""2"" align=""center""><font size=""1""><b>TERMO DE RESPONSABILIDADE</b><br></td>"
     w_html = w_html & VbCrLf & "        <tr><td colspan=2><br></td>"
     w_html = w_html & VbCrLf & "        <tr><td colspan=""2""><font size=""1""><b>Declato estar ciente dos objetivos e condi��es do Programa, responsabiliando-me pela devolu��o dos canhotos das passagens (via do passageiro), cart�o de embarque, bem como pelo fornecimento do relat�rio sobre as atividades desenvolvidas no referido evento, no pprzo m�ximo de 30(trinta)dias a contar d data do regresso, e que a n�o apresenta��o destes documentos colocar-me-� na condi��o de inadimplente junto ao Minist�rio da Cultura, conforme o disposto na Instru��o Normativa STN n� 014.<br></td>"
     w_html = w_html & VbCrLf & "        <tr><td colspan=2><br></td>"
     w_html = w_html & VbCrLf & "        <tr><td><font size=""1""><b>Local: </b><br></td>"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Data: </b><br></td>"
     w_html = w_html & VbCrLf & "        <tr><td colspan=2><br></td>"
     w_html = w_html & VbCrLf & "        <tr><td colspan=""2""><font size=""1""><b>Assinatura: </b><br></td>"
     w_html = w_html & VbCrLf & "          </table>"
     w_html = w_html & VbCrLf & "     </table>"
  End If
  w_html = w_html & VbCrLf & "    </table>"
  w_html = w_html & VbCrLf & "</table>"
  
  VisualFormulario = w_html

  Set w_p2                  = Nothing 
  Set w_fases               = Nothing 

  Set w_tipo_visao          = Nothing 
  Set w_erro                = Nothing 
  Set Rsquery               = Nothing
  Set w_ImagemPadrao        = Nothing
  Set w_Imagem              = Nothing

End Function
REM =========================================================================
REM Fim da visualiza��o dos dados do cliente
REM -------------------------------------------------------------------------

%>