<%

REM =========================================================================
REM Rotina de visualiza��o dos dados da licita��o
REM -------------------------------------------------------------------------
Function VisualPortalLicit(w_chave, O, w_usuario)

  Dim Rsquery, w_Erro
  Dim w_Imagem, w_html
  Dim w_ImagemPadrao
  Dim w_tipo_visao
  Dim RS1,RS2, RS3, RS4
  Dim w_p2, w_fases
  
  w_html = ""

  ' Recupera os dados da a��o
  DB_GetSolicData RS, w_chave, "PJGERAL"

  ' Recupera o tipo de vis�o do usu�rio
  If cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario) or _
     cDbl(Nvl(RS("executor"),0)) = cDbl(w_usuario) or _
     cDbl(Nvl(RS("cadastrador"),0)) = cDbl(w_usuario) Then
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
     End If
  End If
  
  ' Se for listagem ou envio, exibe os dados de identifica��o da a��o
  If O = "L" or O = "V" Then ' Se for listagem dos dados
     w_html = w_html & VbCrLf & "<div align=center><center>"
     w_html = w_html & VbCrLf & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
     w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"

     w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
     w_html = w_html & VbCrLf & "      <tr><td><font size=2>A��o: <b>" & RS("titulo") & " (" & RS("sq_siw_solicitacao") & ")</b></font></td></tr>"
      
      ' Identifica��o da a��o
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Identifica��o</td>"
     ' Se a classifica��o foi informada, exibe.
     If Not IsNull(RS("sq_cc")) Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Classifica��o:<br><b>" & RS("cc_nome") & " </b></td>"
     End If
     ' Se a iniciativa priorit�ria for informada, exibe.
     If Not IsNull(RS("sq_orprioridade")) Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Iniciativa priorit�ria:<br><b>" & RS("nm_pri")
        If Not IsNull(RS("cd_pri")) Then w_html = w_html & VbCrLf & " (" & RS("cd_pri") & ")" End If
        If Not IsNull(RS("resp_pri")) Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Respons�vel iniciativa priorit�ria:<br><b>" & RS("resp_pri") & " </b></td>"
           If Not IsNull(RS("fone_pri")) Then
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<br><b>" & RS("fone_pri") & " </b></td>"
           End If
           If Not IsNull(RS("mail_ppa_pai")) Then
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Email:<br><b>" & RS("mail_pri") & " </b></td>"
           End If
           w_html = w_html & VbCrLf & "          </table>"
        End If
        w_html = w_html & VbCrLf & "          </b></td>"
     End If     
     ' Se a a��o no PPA for informada, exibe.
     If Not IsNull(RS("sq_acao_ppa")) Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Programa PPA:<br><b>" & RS("nm_ppa_pai") & " (" & RS("cd_ppa_pai") & ")" & " </b></td>"
        If Not IsNull(RS("resp_ppa_pai")) Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Respons�vel programa PPA:<br><b>" & RS("resp_ppa_pai") & " </b></td>"
           If Not IsNull(RS("fone_ppa_pai")) Then
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<br><b>" & RS("fone_ppa_pai") & " </b></td>"
           End If
           If Not IsNull(RS("mail_ppa_pai")) Then
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Email:<br><b>" & RS("mail_ppa_pai") & " </b></td>"
           End If
           w_html = w_html & VbCrLf & "          </table>"
        End If
        w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">A��o PPA:<br><b>" & RS("nm_ppa") & " (" & RS("cd_ppa") & ")" & " </b></td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
        w_html = w_html & VbCrLf & "          <tr valign=""top"">"
        If RS("mpog_ppa") = "S" Then
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Selecionada MP:<br><b>Sim</b></td>"
        Else
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Selecionada MP:<br><b>N�o</b></td>"
        End If
        If RS("relev_ppa") = "S" Then
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Selecionada SE/MS:<br><b>Sim</b></td>"
        Else
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Selecionada SE/MS:<br><b>N�o</b></td>"
        End If
        w_html = w_html & VbCrLf & "          </table>"
        If Not IsNull(RS("resp_ppa")) Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Respons�vel a��o PPA:<br><b>" & RS("resp_ppa") & " </b></td>"
           If Not IsNull(RS("fone_ppa")) Then
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<br><b>" & RS("fone_ppa") & " </b></td>"
           End If
           If Not IsNull(RS("mail_ppa")) Then
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Email:<br><b>" & RS("mail_ppa") & " </b></td>"
           End If
           w_html = w_html & VbCrLf & "          </table>"
        End If
     End If
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     'w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     'w_html = w_html & VbCrLf & "          <td colspan=3><font size=""1"">Abrang�ncia da a��o:(Quando Bras�lia-DF, impacto nacional. Quando a capital de um estado, impacto estadual.)<br><b>" & RS("nm_cidade") & " (" & RS("co_uf") & ")</b></td>"
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Respons�vel monitoramento:<br><b>" & ExibePessoa("../", w_cliente, RS("solicitante"), TP, RS("nm_sol")) & "</b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Setor respons�vel monitoramento:<br><b>" & RS("nm_unidade_resp") & " </b></td>"
     If w_tipo_visao = 0 Then ' Se for vis�o completa
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Recurso programado:<br><b>" & FormatNumber(RS("valor"),2) & " </b></td>"
     End If
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">In�cio previsto:<br><b>" & FormataDataEdicao(RS("inicio")) & " </b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Fim previsto:<br><b>" & FormataDataEdicao(RS("fim")) & " </b></td>"
     'w_html = w_html & VbCrLf & "          <td><font size=""1"">Prioridade:<br><b>" & RetornaPrioridade(RS("prioridade")) & " </b></td>"
     w_html = w_html & VbCrLf & "          <tr valign=""top""><td><font size=""1"">Parcerias externas:<br><b>" & CRLF2BR(Nvl(RS("proponente"),"---")) & " </b></td>"
     w_html = w_html & VbCrLf & "          <tr valign=""top""><td><font size=""1"">Parcerias internas:<br><b>" & CRLF2BR(Nvl(RS("palavra_chave"),"---")) & " </b></td>"  
     w_html = w_html & VbCrLf & "          </table>"

     If w_tipo_visao = 0 or w_tipo_visao = 1 Then
        ' Informa��es adicionais
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Informa��es adicionais</td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Situa��o problema:<br><b>" & CRLF2BR(Nvl(RS("problema"),"---")) & " </b></td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Objetivo da a��o:<br><b>" & CRLF2BR(Nvl(RS("objetivo"),"---")) & " </b></td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Descri��o da a��o:<br><b>" & CRLF2BR(Nvl(RS("ds_acao"),"---")) & " </b></td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">P�blico alvo:<br><b>" & CRLF2BR(Nvl(RS("publico_alvo"),"---")) & " </b></td>"
        If Nvl(RS("descricao"),"") > "" Then 
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Resultados da a��o:<br><b>" & CRLF2BR(RS("descricao")) & " </b></td>" 
        End If
        w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Estrat�gia de implanta��o:<br><b>" & CRLF2BR(Nvl(RS("estrategia"),"---")) & " </b></td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Indicadores de desempenho:<br><b>" & CRLF2BR(Nvl(RS("indicadores"),"---")) & " </b></td>"
        If w_tipo_visao = 0 and Nvl(RS("justificativa"),"") > "" Then ' Se for vis�o completa
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Observa��es:<br><b>" & CRLF2BR(RS("justificativa")) & " </b></td>"
        End If
     End If
     
     ' Outras iniciativas
     DB_GetOrPrioridadeList RS1, w_chave, RetornaCliente(), null
     RS1.Sort = "Existe desc"
     If Not RS1.EOF Then
        If cDbl(Nvl(RS1("Existe"),0)) > 0 Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Outras iniciativas</td>"
           w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
           w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Nome</font></td>"
           w_html = w_html & VbCrLf & "          </tr>"    
           While Not RS1.EOF
              If cDbl(Nvl(RS1("Existe"),0)) > 0 Then
                 If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
                 w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
                 w_html = w_html & VbCrLf & "        <td><font size=""1""><ul><li>" & RS1("nome") & "</td>"
                 w_html = w_html & VbCrLf & "      </tr>"
              End If
              RS1.MoveNext
           wend
           w_html = w_html & VbCrLf & "         </table></td></tr>"
        End If
     End If
     RS1.close
     
     'Financiamento
     DB_GetFinancAcaoPPA RS1, w_chave, RetornaCliente()
     If RS("cd_ppa") > "" Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Financiamento</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
        w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>C�digo</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Nome</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Dota��o autorizada</font></td>"
        w_html = w_html & VbCrLf & "          </tr>" 
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
        w_html = w_html & VbCrLf & "        <td><font size=""1"">" &RS("cd_ppa_pai") & "." & RS("cd_ppa") & "</td>"
        w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS("nm_ppa") & "</td>"
        w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber(RS("aprovado"),2) & "</td>"
        w_html = w_html & VbCrLf & "      </tr>"   
        If Not RS1.EOF Then
           While Not RS1.EOF
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
              w_html = w_html & VbCrLf & "        <td><font size=""1"">" &RS1("cd_ppa_pai") & "." & RS1("cd_ppa") & "</td>"
              w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS1("nome") & "</td>"
              w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber(RS1("aprovado"),2) & "</td>"
              w_html = w_html & VbCrLf & "      </tr>"
              RS1.MoveNext
           wend
        End If
        w_html = w_html & VbCrLf & "         </table></td></tr>"
     ElseIf Not RS1.EOF Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Financiamento</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
        w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>C�digo</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Nome</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Dota��o autorizada</font></td>"
        w_html = w_html & VbCrLf & "          </tr>"    
        While Not RS1.EOF
           If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
           w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
           w_html = w_html & VbCrLf & "        <td><font size=""1"">" &RS1("cd_ppa_pai") & "." & RS1("cd_ppa") & "</td>"
           w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS1("nome") & "</td>"
           w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber(RS1("aprovado"),2) & "</td>"
           w_html = w_html & VbCrLf & "      </tr>"
           RS1.MoveNext
        wend
        RS1.close
        w_html = w_html & VbCrLf & "         </table></td></tr>"
     End If
 
     ' Dados da conclus�o da a��o, se ela estiver nessa situa��o
     If RS("concluida") = "S" and Nvl(RS("data_conclusao"),"") > "" Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Dados da conclus�o</td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
        w_html = w_html & VbCrLf & "          <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">In�cio da execu��o:<br><b>" & FormataDataEdicao(RS("inicio_real")) & " </b></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">T�rmino da execu��o:<br><b>" & FormataDataEdicao(RS("fim_real")) & " </b></td>"
        If w_tipo_visao = 0 Then
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Recurso executado:<br><b>" & FormatNumber(RS("custo_real"),2) & " </b></td>"
        End If
        w_html = w_html & VbCrLf & "          </table>"
        If w_tipo_visao = 0 Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Nota de conclus�o:<br><b>" & CRLF2BR(RS("nota_conclusao")) & " </b></td>"
        End If
     End If
  End If
   
  ' Se for listagem, exibe os outros dados dependendo do tipo de vis�o  do usu�rio
  If O = "L" and w_tipo_visao <> 2 Then
     If RS("aviso_prox_conc") = "S" Then
        ' Configura��o dos alertas de proximidade da data limite para conclus�o da demanda
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Alerta</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
        w_html = w_html & VbCrLf & "      <tr><td><font size=1>Ser� enviado aviso a partir de <b>" & RS("dias_aviso") & "</b> dias antes de <b>" & FormataDataEdicao(RS("fim")) & "</b></font></td></tr>"
        'w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
        'w_html = w_html & VbCrLf & "          <td valign=""top""><font size=""1"">Emite aviso:<br><b>" & Replace(Replace(RS("aviso_prox_conc"),"S","Sim"),"N","N�o") & " </b></td>"
        'w_html = w_html & VbCrLf & "          <td valign=""top""><font size=""1"">Dias:<br><b>" & RS("dias_aviso") & " </b></td>"
        'w_html = w_html & VbCrLf & "          </table>"
     End If

     ' Interessados na execu��o da a��o
     DB_GetSolicInter RS, w_chave, null, "LISTA"
     RS.Sort = "nome_resumido"
     If Not Rs.EOF Then
        TP = RemoveTP(TP)&" - Interessados"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Interessados na execu��o</b></center></td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center""><font size=""1""><b><center><B><font size=1>Clique <a class=""HL"" HREF=""" & w_dir & "Projeto.asp?par=interess&R=" & w_Pagina & par & "&O=L&w_chave=" & w_chave & "&P1=4&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ target=""blank"">aqui</a> para visualizar os Interessados na execu��o</font></b></center></td>"
        'w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
        'w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        'w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        'w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Nome</font></td>"
        'w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Tipo de vis�o</font></td>"
        'w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Envia e-mail</font></td>"
        'w_html = w_html & VbCrLf & "          </tr>"    
        'While Not Rs.EOF
        '  If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        '  w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
        '  w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS("nome_resumido") & "</td>"
        '  w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RetornaTipoVisao(RS("tipo_visao")) & "</td>"
        '  w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & Replace(Replace(RS("envia_email"),"S","Sim"),"N","N�o") & "</td>"
        '  w_html = w_html & VbCrLf & "      </tr>"
        '  Rs.MoveNext
        'wend
        'w_html = w_html & VbCrLf & "         </table></td></tr>"
     End If
     DesconectaBD

     ' �reas envolvidas na execu��o da a��o
     DB_GetSolicAreas RS, w_chave, null, "LISTA"
     RS.Sort = "nome"
     If Not Rs.EOF Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>�reas/Institui��es envolvidas</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
        w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Nome</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Papel</font></td>"
        w_html = w_html & VbCrLf & "          </tr>"    
        While Not Rs.EOF
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS("nome") & "</td>"
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS("papel") & "</td>"
          w_html = w_html & VbCrLf & "      </tr>"
          Rs.MoveNext
        wend
        w_html = w_html & VbCrLf & "         </table></td></tr>"
     End If
     DesconectaBD

     ' Etapas da a��o
     ' Recupera todos os registros para a listagem
     DB_GetSolicEtapa RS, w_chave, null, "LISTA"
     RS.Sort = "ordem"

    ' Recupera o c�digo da op��o de menu  a ser usada para listar as atividades
     w_p2 = ""
     While Not RS.EOF
        If cDbl(Nvl(RS("P2"),0)) > cDbl(0) Then
           w_p2 = RS("P2")
           RS.MoveLast
        End If
        RS.MoveNext
     Wend
     DesconectaBD

     DB_GetSolicEtapa RS, w_chave, null, "LSTNULL"
     RS.Sort = "ordem"
     If Not RS.EOF Then ' Se n�o foram selecionados registros, exibe mensagem
        
        ' Monta fun��o JAVASCRIPT para fazer a chamada para a lista de atividades
        If w_p2 > "" Then
           w_html = w_html & VbCrLf & "<SCRIPT LANGUAGE=""JAVASCRIPT"">"
           w_html = w_html & VbCrLf & "  function lista (projeto, etapa) {"
           w_html = w_html & VbCrLf & "    document.Form.p_projeto.value=projeto;"
           w_html = w_html & VbCrLf & "    document.Form.p_atividade.value=etapa;"
           w_html = w_html & VbCrLf & "    document.Form.p_agrega.value='GRDMETAPA';"
           DB_GetTramiteList RS1, w_P2, null
           RS1.Sort = "ordem"
           w_html = w_html & VbCrLf & "    document.Form.p_fase.value='';"
           w_fases = ""
           While Not RS1.EOF
              If RS1("sigla") <> "CA" Then
                 w_fases = w_fases & "," & RS1("sq_siw_tramite")
              End If
              RS1.MoveNext
           Wend
           w_html = w_html & VbCrLf & "    document.Form.p_fase.value='" & Mid(w_fases,2,100) & "';"
           w_html = w_html & VbCrLf & "    document.Form.submit();"
           w_html = w_html & VbCrLf & "  }"
           w_html = w_html & VbCrLf & "</SCRIPT>"
           DB_GetMenuData RS1, w_p2
           AbreForm "Form", RS1("link"), "POST", "return(Validacao(this));", "Atividades",3,w_P2,1,null,w_TP,RS1("sigla"),w_pagina & par,"L"
           w_html = w_html & VbCrLf & MontaFiltro("POST")
           w_html = w_html & VbCrLf & "<input type=""Hidden"" name=""p_projeto"" value="""">"
           w_html = w_html & VbCrLf & "<input type=""Hidden"" name=""p_atividade"" value="""">"
           w_html = w_html & VbCrLf & "<input type=""Hidden"" name=""p_agrega"" value="""">"
           w_html = w_html & VbCrLf & "<input type=""Hidden"" name=""p_fase"" value="""">"
        End If
        
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Metas f�sicas</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
        w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Metas</font></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Produto</font></td>"
        'w_html = w_html & VbCrLf & "          <td rowspan=2><font size=""1""><b>Respons�vel</font></td>"
        'w_html = w_html & VbCrLf & "          <td rowspan=2><font size=""1""><b>Setor</font></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Execu��o at�</font></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Conc.</font></td>"
        'w_html = w_html & VbCrLf & "          <td rowspan=2><font size=""1""><b>Ativ.</font></td>"
        w_html = w_html & VbCrLf & "          </tr>"
        'w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        'w_html = w_html & VbCrLf & "          <td><font size=""1""><b>De</font></td>"
        'w_html = w_html & VbCrLf & "          <td><font size=""1""><b>At�</font></td>"
        'w_html = w_html & VbCrLf & "          </tr>"
        While Not RS.EOF
           w_html = w_html & VbCrLf & EtapaLinha(w_chave, Rs("sq_projeto_etapa"), Rs("titulo"), RS("nm_resp"), RS("sg_setor"), Rs("inicio_previsto"), Rs("fim_previsto"), Rs("perc_conclusao"), RS("qt_ativ"), "<b>", null, "PROJETO")
         
           ' Recupera as etapas vinculadas ao n�vel acima
           DB_GetSolicEtapa RS1, w_chave, RS("sq_projeto_etapa"), "LSTNIVEL"
           RS1.Sort = "ordem"
           While Not RS1.EOF
             w_html = w_html & VbCrLf & EtapaLinha(w_chave, RS1("sq_projeto_etapa"), RS1("titulo"), RS1("nm_resp"), RS1("sg_setor"), RS1("inicio_previsto"), RS1("fim_previsto"), RS1("perc_conclusao"), RS1("qt_ativ"), null, null, "PROJETO")
 
             ' Recupera as etapas vinculadas ao n�vel acima
             DB_GetSolicEtapa RS2, w_chave, RS1("sq_projeto_etapa"), "LSTNIVEL"
             RS2.Sort = "ordem"
             While Not RS2.EOF
               w_html = w_html & VbCrLf & EtapaLinha(w_chave, RS2("sq_projeto_etapa"), RS2("titulo"), RS2("nm_resp"), RS2("sg_setor"), RS2("inicio_previsto"), RS2("fim_previsto"), RS2("perc_conclusao"), RS2("qt_ativ"), null, null, "PROJETO")

               ' Recupera as etapas vinculadas ao n�vel acima
               DB_GetSolicEtapa RS3, w_chave, RS2("sq_projeto_etapa"), "LSTNIVEL"
               RS3.Sort = "ordem"
               While Not RS3.EOF
                 w_html = w_html & VbCrLf & EtapaLinha(w_chave, RS3("sq_projeto_etapa"), RS3("titulo"), RS3("nm_resp"), RS3("sg_setor"), RS3("inicio_previsto"), RS3("fim_previsto"), RS3("perc_conclusao"), RS3("qt_ativ"), null, null, "PROJETO")

                 ' Recupera as etapas vinculadas ao n�vel acima
                 DB_GetSolicEtapa RS4, w_chave, RS3("sq_projeto_etapa"), "LSTNIVEL"
                 RS4.Sort = "ordem"
                 While Not RS4.EOF
                   w_html = w_html & VbCrLf & EtapaLinha(w_chave, RS4("sq_projeto_etapa"), RS4("titulo"), RS4("nm_resp"), RS4("sg_setor"), RS4("inicio_previsto"), RS4("fim_previsto"), RS4("perc_conclusao"), RS4("qt_ativ"), null, null, "PROJETO")
                   RS4.MoveNext
                 wend

                 RS3.MoveNext
               wend

               RS2.MoveNext
             wend

             RS1.MoveNext
           wend
        
           RS.MoveNext
        wend
        w_html = w_html & VbCrLf & "      </form>"
        w_html = w_html & VbCrLf &  "      </center>"
        w_html = w_html & VbCrLf & "         </table></td></tr>"
     End If
     DesconectaBD
     
     ' Listagem das tarefas na visualiza��o da a��o, rotina adquirida apartir da rotina exitente na ProjetoAtiv.asp para listagem das tarefas
     DB_GetLinkData RS, RetornaCliente(), "ORPCAD"
      DB_GetSolicList rs, RS("sq_menu"), RetornaUsuario(), "ORPCAD", 4, _
           null, null, null, null, null, null, _
           null, null, null, null, _
           null, null, null, null, null, null, null, _
           null, null, null, null, null, w_chave, null, null, null
     If Not RS.EOF Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Tarefas</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
        w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>N�</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Respons�vel</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Detalhamento</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Fim previsto</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Fase atual</font></td>"
        w_html = w_html & VbCrLf & "          </tr>"
           While Not RS.EOF 
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              w_html = w_html & VbCrLf & "       <tr valign=""top"" bgcolor=""" & w_cor & """>"
              w_html = w_html & VbCrLf & "       <tr bgcolor=""" & w_cor & """ valign=""top"">"
              w_html = w_html & VbCrLf & "         <td nowrap><font size=""1"">"
              If RS("concluida") = "N" Then
                 If RS("fim") < Date() Then
                    w_html = w_html & VbCrLf & "          <img src=""" & conImgAtraso & """ border=0 width=15 heigth=15 align=""center"">"
                 ElseIf RS("aviso_prox_conc") = "S" and (RS("aviso") <= Date()) Then
                    w_html = w_html & VbCrLf & "          <img src=""" & conImgAviso & """ border=0 width=15 height=15 align=""center"">"
                 Else
                    w_html = w_html & VbCrLf & "          <img src=""" & conImgNormal & """ border=0 width=15 height=15 align=""center"">"
                 End IF
              Else
                 If RS("fim") < Nvl(RS("fim_real"),RS("fim")) Then
                    w_html = w_html & VbCrLf & "          <img src=""" & conImgOkAtraso & """ border=0 width=15 heigth=15 align=""center"">"
                 Else
                    w_html = w_html & VbCrLf & "          <img src=""" & conImgOkNormal & """ border=0 width=15 height=15 align=""center"">"
                 End If
              End If
              w_html = w_html & VbCrLf & "         <A class=""HL"" HREF=""" & w_dir & "Projetoativ.asp?par=Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informa��es deste registro."" target=""blank"">" & RS("sq_siw_solicitacao") & "&nbsp;</a>"
              w_html = w_html & VbCrLf & "         <td><font size=""1"">" & ExibePessoa("../", w_cliente, RS("solicitante"), TP, RS("nm_solic")) & "</td>"
              Dim w_titulo
              If Len(Nvl(RS("assunto"),"-")) > 50 Then w_titulo = Mid(Nvl(RS("assunto"),"-"),1,50) & "..." Else w_titulo = Nvl(RS("assunto"),"-") End If
              If RS("sg_tramite") = "CA" Then
                 w_html = w_html & VbCrLf & "      <td><font size=""1""><strike>" & w_titulo & "</strike></td>"
              Else
                 w_html = w_html & VbCrLf & "      <td><font size=""1"">" & w_titulo & "</td>"
              End If
              w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">&nbsp;" & Nvl(FormatDateTime(RS("fim"),2),"-") & "</td>"
              w_html = w_html & VbCrLf & "         <td nowrap><font size=""1"">" & RS("nm_tramite") & "</td>"
              RS.MoveNext        
           wend
        w_html = w_html & VbCrLf & "         </table></td></tr>" 
     End If  
     DesconectaBD
     
     ' Recursos envolvidos na execu��o da a��o
     DB_GetSolicRecurso RS, w_chave, null, "LISTA"
     RS.Sort = "tipo, nome"
     If Not Rs.EOF Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Recursos</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
        w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Tipo</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Nome</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Finalidade</font></td>"
        w_html = w_html & VbCrLf & "          </tr>"    
        While Not Rs.EOF
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RetornaTipoRecurso(RS("tipo")) & "</td>"
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS("nome") & "</td>"
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & CRLF2BR(Nvl(RS("finalidade"),"---")) & "</td>"
          w_html = w_html & VbCrLf & "      </tr>"
          Rs.MoveNext
        wend
     End If
     DesconectaBD
     w_html = w_html & VbCrLf & "         </table></td></tr>"

  End If

  If O = "L" or O = "V" Then ' Se for listagem dos dados
     ' Encaminhamentos
     DB_GetSolicLog RS, w_chave, null, "LISTA"
     RS.Sort = "data desc"
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Ocorr�ncias e Anota��es</td>"
     w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
     w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Data</font></td>"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Despacho/Observa��o</font></td>"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Respons�vel</font></td>"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Fase / Destinat�rio</font></td>"
     w_html = w_html & VbCrLf & "          </tr>"    
     If Rs.EOF Then
        w_html = w_html & VbCrLf & "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>N�o foram encontrados encaminhamentos.</b></td></tr>"
     Else
        w_html = w_html & VbCrLf & "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
        w_html = w_html & VbCrLf & "        <td colspan=6><font size=""1"">Fase atual: <b>" & RS("fase") & "</b></td>"
        While Not Rs.EOF
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
          w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & FormatDateTime(RS("data"),2) & ", " & FormatDateTime(RS("data"),4)& "</td>"
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & CRLF2BR(Nvl(RS("despacho"),"---")) & "</td>"
          w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & ExibePessoa("../", w_cliente, RS("sq_pessoa"), TP, RS("responsavel")) & "</td>"
          If (Not IsNull(Tvl(RS("sq_projeto_log")))) and (Not IsNull(Tvl(RS("destinatario")))) Then
             w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & ExibePessoa("../", w_cliente, RS("sq_pessoa_destinatario"), TP, RS("destinatario")) & "</td>"
          ElseIf (Not IsNull(Tvl(RS("sq_projeto_log")))) and IsNull(Tvl(RS("destinatario"))) Then
             w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">Anota��o</td>"
          Else
             w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & Nvl(RS("tramite"),"---") & "</td>"
          End If
          w_html = w_html & VbCrLf & "      </tr>"
          Rs.MoveNext
        wend
        w_html = w_html & VbCrLf & "         </table></td></tr>"
     End If
     DesconectaBD

     w_html = w_html & VbCrLf & "</table>"
  End If
  
  VisualProjeto = w_html

  Set w_p2                  = Nothing 
  Set w_fases               = Nothing 
  Set RS2                   = Nothing 
  Set RS3                   = Nothing 
  Set RS4                   = Nothing 

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

