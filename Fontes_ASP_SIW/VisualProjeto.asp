<%

REM =========================================================================
REM Rotina de visualização dos dados do projeto
REM -------------------------------------------------------------------------
Function VisualProjeto(w_chave, O, w_usuario)

  Dim Rsquery, w_Erro
  Dim w_Imagem, w_html
  Dim w_ImagemPadrao
  Dim w_tipo_visao
  Dim RS2, RS3, RS4
  Dim w_p2, w_fases
  Dim w_acordo
  
  w_html = ""

  ' Verifica se o cliente tem o módulo de acordos contratado
  DB_GetSiwCliModLis RS, w_cliente, null
  RS.Filter = "sigla='AC'"
  If Not RS.EOF Then w_acordo = "S" Else w_acordo = "N" End If
  DesconectaBD

  ' Recupera os dados do projeto
  DB_GetSolicData RS, w_chave, "PJGERAL"

  ' Recupera o tipo de visão do usuário
  If cDbl(Nvl(RS("solicitante"),0))  = cDbl(w_usuario) or _
     cDbl(Nvl(RS("executor"),0))     = cDbl(w_usuario) or _
     cDbl(Nvl(RS("cadastrador"),0))  = cDbl(w_usuario) or _
     cDbl(Nvl(RS("titular"),0))      = cDbl(w_usuario) or _
     cDbl(Nvl(RS("substituto"),0))   = cDbl(w_usuario) or _
     cDbl(Nvl(RS("tit_exec"),0))     = cDbl(w_usuario) or _
     cDbl(Nvl(RS("subst_exec"),0))   = cDbl(w_usuario) or _
     SolicAcesso(w_chave, w_usuario) >= 8 Then
     ' Se for solicitante, executor ou cadastrador, tem visão completa
     w_tipo_visao = 0
  Else
     DB_GetSolicInter Rsquery, w_chave, w_usuario, "REGISTRO"
     If Not RSquery.EOF Then
        ' Se for interessado, verifica a visão cadastrada para ele.
        w_tipo_visao = cDbl(RSquery("tipo_visao"))
     Else
        DB_GetSolicAreas Rsquery, w_chave, Session("sq_lotacao"), "REGISTRO"
        If Not RSquery.EOF Then
           ' Se for de uma das unidades envolvidas, tem visão parcial
           w_tipo_visao = 1
        Else
           ' Caso contrário, tem visão resumida
           w_tipo_visao = 2
        End If

        If SolicAcesso(w_chave, w_usuario) > 2 Then w_tipo_visao = 1 End If
     End If
  End If
  
  ' Se for listagem ou envio, exibe os dados de identificação do projeto
  If O = "L" or O = "V" or O = "T" Then ' Se for listagem dos dados
     w_html = w_html & VbCrLf & "<div align=center><center>"
     w_html = w_html & VbCrLf & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
     w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"

     w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
     w_html = w_html & VbCrLf & "      <tr><td><font size=2>Projeto: <b>" & RS("titulo") & " (" & RS("sq_siw_solicitacao") & ")</b></font></td>"
     If O <> "T" and w_tipo_visao = 0 Then
        w_html = w_html & VbCrLf & "       <td align=""right""><font size=""1""><b><A class=""HL"" HREF=""Projeto.asp?par=Visual&O=T&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=volta&P1=&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=GDPCAD"" title=""Exibe as informações do projeto."">Exibir todas as informações</a></td></tr>"
     End If
      
      ' Identificação do projeto
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Identificação</td>"
     ' Se a classificação foi informada, exibe.
     If Not IsNull(RS("sq_cc")) Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Classificação:<br><b>" & RS("cc_nome") & " </b></td>"
     End If
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Cidade de origem:<br><b>" & RS("nm_cidade") & " (" & RS("co_uf") & ")</b></td>"
     w_html = w_html & VbCrLf & "          <td colspan=2><font size=""1"">Proponente externo:<br><b>" & RS("proponente") & " </b></td>"
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Responsável:<br><b>" & ExibePessoa(null, w_cliente, RS("solicitante"), TP, RS("nm_sol")) & "</b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Unidade responsável:<br><b>" & ExibeUnidade(null, w_cliente, RS("nm_unidade_resp"), RS("sq_unidade"), TP) & "</b></td>"
     If w_tipo_visao = 0 Then ' Se for visão completa
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Orçamento disponível:<br><b>" & FormatNumber(RS("valor"),2) & " </b></td>"
     End If
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Data de recebimento:<br><b>" & FormataDataEdicao(RS("inicio")) & " </b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Limite para conclusão:<br><b>" & FormataDataEdicao(RS("fim")) & " </b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Prioridade:<br><b>" & RetornaPrioridade(RS("prioridade")) & " </b></td>"

     If w_tipo_visao = 0 or w_tipo_visao = 1 Then
        ' Informações adicionais
        If Nvl(RS("descricao"),"") > "" or Nvl(RS("justificativa"),"") > "" or w_acordo = "S" Then 
           w_html = w_html & VbCrLf & "      <tr><td colspan=3 align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Informações adicionais</td>"
           If Nvl(RS("descricao"),"") > "" Then w_html = w_html & VbCrLf & "      <tr><td colspan=3><font size=""1"">Resultados do projeto:<br><b>" & CRLF2BR(RS("descricao")) & " </b></td>" End If
           If w_tipo_visao = 0 and Nvl(RS("justificativa"),"") > "" Then ' Se for visão completa
              w_html = w_html & VbCrLf & "      <tr><td colspan=3><font size=""1"">Recomendações superiores:<br><b>" & CRLF2BR(RS("justificativa")) & " </b></td>"
           End If
           If w_acordo = "S" Then
              w_html = w_html & VbCrLf & "          <tr valign=""top"">"
              If RS("vincula_contrato") = "S" Then w_html = w_html & VbCrLf & "<td><font size=""1"">Permite a vinculação de acordos:<br><b>Sim</b>" Else w_html = w_html & VbCrLf & "<td><font size=""1"">Permite a vinculação de acordos:<br><b>Não</b>" End If
              If RS("vincula_viagem")   = "S" Then w_html = w_html & VbCrLf & "<td><font size=""1"">Permite a vinculação de viagens:<br><b>Sim</b>" Else w_html = w_html & VbCrLf & "<td><font size=""1"">Permite a vinculação de viagens:<br><b>Não</b>" End If
           End If
        End If
     End If

     w_html = w_html & VbCrLf & "          </table>"

     ' Dados da conclusão do projeto, se ela estiver nessa situação
     If RS("concluida") = "S" and Nvl(RS("data_conclusao"),"") > "" Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Dados da conclusão</td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
        w_html = w_html & VbCrLf & "          <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Início da execução:<br><b>" & FormataDataEdicao(RS("inicio_real")) & " </b></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Término da execução:<br><b>" & FormataDataEdicao(RS("fim_real")) & " </b></td>"
        If w_tipo_visao = 0 Then
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Custo real:<br><b>" & FormatNumber(RS("custo_real"),2) & " </b></td>"
        End If
        w_html = w_html & VbCrLf & "          </table>"
        If w_tipo_visao = 0 Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Nota de conclusão:<br><b>" & CRLF2BR(RS("nota_conclusao")) & " </b></td>"
        End If
     End If
  End If
   
  ' Se for listagem, exibe os outros dados dependendo do tipo de visão  do usuário
  If (O = "L" and w_tipo_visao <> 2) or (O = "T" and w_tipo_visao <> 2) Then
     If RS("aviso_prox_conc") = "S" Then
        ' Configuração dos alertas de proximidade da data limite para conclusão da demanda
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Alertas</td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
        w_html = w_html & VbCrLf & "          <td valign=""top""><font size=""1"">Emite aviso:<br><b>" & Replace(Replace(RS("aviso_prox_conc"),"S","Sim"),"N","Não") & " </b></td>"
        w_html = w_html & VbCrLf & "          <td valign=""top""><font size=""1"">Dias:<br><b>" & RS("dias_aviso") & " </b></td>"
        w_html = w_html & VbCrLf & "          </table>"
     End If

     ' Interessados na execução do projeto
     DB_GetSolicInter RS, w_chave, null, "LISTA"
     RS.Sort = "nome_resumido"
     If Not Rs.EOF Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Interessados na execução</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
        w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Nome</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Tipo de visão</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Envia e-mail</font></td>"
        w_html = w_html & VbCrLf & "          </tr>"    
        w_cor = conTrBgColor
        While Not Rs.EOF
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & ExibePessoa(null, w_cliente, RS("sq_pessoa"), TP, RS("nome") & " (" & RS("lotacao") & ")") & "</td>"
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RetornaTipoVisao(RS("tipo_visao")) & "</td>"
          w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & Replace(Replace(RS("envia_email"),"S","Sim"),"N","Não") & "</td>"
          w_html = w_html & VbCrLf & "      </tr>"
          Rs.MoveNext
        wend
        w_html = w_html & VbCrLf & "         </table></td></tr>"
     End If
     DesconectaBD

     ' Áreas envolvidas na execução do projeto
     DB_GetSolicAreas RS, w_chave, null, "LISTA"
     RS.Sort = "nome"
     If Not Rs.EOF Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Áreas/Instituições envolvidas</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
        w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Nome</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Papel</font></td>"
        w_html = w_html & VbCrLf & "          </tr>"    
        w_cor = conTrBgColor
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
     
     'Lista das atividades que não são ligadas a nenhuma etapa
     If O = "T" Then
        DB_GetSolicList RS, w_menu, w_usuario, "GDPCAD", 3, _
        null, null, null, null, null, null, _
        null, null, null, null, _
        null, null, null, null, null, null, null, _
        null, null, null, null, null, w_chave, null, null, null
        RS.Filter = "sq_projeto_etapa = null"
        
        If Not RS.EOF Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Atividades não ligadas a etapas</td>"
           w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
           w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
           w_html = w_html & VbCrLf & "            <td rowspan=2><font size=""1""><b>Nº</font></td>"
           w_html = w_html & VbCrLf & "            <td rowspan=2><font size=""1""><b>Detalhamento</font></td>"
           w_html = w_html & VbCrLf & "            <td rowspan=2><font size=""1""><b>Responsável</font></td>"
           w_html = w_html & VbCrLf & "            <td rowspan=2><font size=""1""><b>Setor</font></td>"
           w_html = w_html & VbCrLf & "            <td colspan=2><font size=""1""><b>Execução</font></td>"
           w_html = w_html & VbCrLf & "            <td rowspan=2><font size=""1""><b>Conc.</font></td>"
           w_html = w_html & VbCrLf & "            <td rowspan=2><font size=""1""><b>Ativ.</font></td>"
           w_html = w_html & VbCrLf & "          </tr>"
           w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>De</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Até</font></td>"
           w_html = w_html & VbCrLf & "          </tr>"
           While not RS.EOF
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """><td><font size=""1"">"
              If RS("concluida") = "N" Then
                 If RS("fim") < Date() Then
                    w_html = w_html & VbCrLf & "   <img src=""" & conImgAtraso & """ border=0 width=15 heigth=15 align=""center"">"
                 ElseIf RS("aviso_prox_conc") = "S" and (RS("aviso") <= Date()) Then
                    w_html = w_html & VbCrLf & "   <img src=""" & conImgAviso & """ border=0 width=15 height=15 align=""center"">"
                 Else
                    w_html = w_html & VbCrLf & "   <img src=""" & conImgNormal & """ border=0 width=15 height=15 align=""center"">"
                 End If
              Else
                 If RS("fim") < Nvl(RS("fim_real"),RS("fim")) Then
                    w_html = w_html & VbCrLf & "   <img src=""" & conImgOkAtraso & """ border=0 width=15 heigth=15 align=""center"">"
                 Else
                    w_html = w_html & VbCrLf & "   <img src=""" & conImgOkNormal & """ border=0 width=15 height=15 align=""center"">"
                 End If
              End If
              w_html = w_html & VbCrLf & "  <A class=""HL"" HREF=""ProjetoAtiv.asp?par=Visual&R=ProjetoAtiv.asp?par=Visual&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste registro."" target=""blank"">" & RS("sq_siw_solicitacao") & "</a>"
              w_html = w_html & VbCrLf & "   <td><font size=""1"">" & Nvl(RS("assunto"),"-")
              w_html = w_html & VbCrLf & "     <td><font size=""1"">" & ExibePessoa(null, w_cliente, RS("solicitante"), TP, RS("nm_resp")) & "</td>"
              w_html = w_html & VbCrLf & "     <td><font size=""1"">" & RS("sg_unidade_resp") & "</td>"
              w_html = w_html & VbCrLf & "     <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS("inicio")),"-") & "</td>"
              w_html = w_html & VbCrLf & "     <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS("fim")),"-") & "</td>"
              w_html = w_html & VbCrLf & "     <td colspan=2 nowrap><font size=""1"">" & RS("nm_tramite") & "</td>"
              RS.MoveNext
           Wend
           w_html = w_html & VbCrLf & "      </td></tr></table>"
        End If         
        DesconectaBD
     End If
     
     ' Etapas do projeto
     ' Recupera todos os registros para a listagem
     DB_GetSolicEtapa RS, w_chave, null, "LISTA"
     RS.Sort = "ordem"

    ' Recupera o código da opção de menu  a ser usada para listar as atividades
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
     If Not RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        
        ' Monta função JAVASCRIPT para fazer a chamada para a lista de atividades
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
           w_html = w_html & VbCrLf & "<input type=""Hidden"" name=""p_projeto"" value="""">"
           w_html = w_html & VbCrLf & "<input type=""Hidden"" name=""p_atividade"" value="""">"
           w_html = w_html & VbCrLf & "<input type=""Hidden"" name=""p_agrega"" value="""">"
           w_html = w_html & VbCrLf & "<input type=""Hidden"" name=""p_fase"" value="""">"
        End If
        
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Etapas</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
        w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "          <td rowspan=2><font size=""1""><b>Etapa</font></td>"
        w_html = w_html & VbCrLf & "          <td rowspan=2><font size=""1""><b>Título</font></td>"
        w_html = w_html & VbCrLf & "          <td rowspan=2><font size=""1""><b>Responsável</font></td>"
        w_html = w_html & VbCrLf & "          <td rowspan=2><font size=""1""><b>Setor</font></td>"
        w_html = w_html & VbCrLf & "          <td colspan=2><font size=""1""><b>Execução</font></td>"
        w_html = w_html & VbCrLf & "          <td rowspan=2><font size=""1""><b>Conc.</font></td>"
        w_html = w_html & VbCrLf & "          <td rowspan=2><font size=""1""><b>Ativ.</font></td>"
        w_html = w_html & VbCrLf & "          </tr>"
        w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><b>De</font></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Até</font></td>"
        w_html = w_html & VbCrLf & "          </tr>"
        'Se for visualização normal, irá visualizar somente as etapas
        If O = "L" Then
           While Not RS.EOF
              w_html = w_html & VbCrLf & EtapaLinha(w_chave, Rs("sq_projeto_etapa"), Rs("titulo"), RS("nm_resp"), RS("sg_setor"), Rs("inicio_previsto"), Rs("fim_previsto"), Rs("perc_conclusao"), RS("qt_ativ"), "<b>", null, "PROJETO")
         
              ' Recupera as etapas vinculadas ao nível acima
              DB_GetSolicEtapa RS1, w_chave, RS("sq_projeto_etapa"), "LSTNIVEL"
              RS1.Sort = "ordem"
              While Not RS1.EOF
                 w_html = w_html & VbCrLf & EtapaLinha(w_chave, RS1("sq_projeto_etapa"), RS1("titulo"), RS1("nm_resp"), RS1("sg_setor"), RS1("inicio_previsto"), RS1("fim_previsto"), RS1("perc_conclusao"), RS1("qt_ativ"), null, null, "PROJETO")
 
                 ' Recupera as etapas vinculadas ao nível acima
                 DB_GetSolicEtapa RS2, w_chave, RS1("sq_projeto_etapa"), "LSTNIVEL"
                 RS2.Sort = "ordem"
                 While Not RS2.EOF
                    w_html = w_html & VbCrLf & EtapaLinha(w_chave, RS2("sq_projeto_etapa"), RS2("titulo"), RS2("nm_resp"), RS2("sg_setor"), RS2("inicio_previsto"), RS2("fim_previsto"), RS2("perc_conclusao"), RS2("qt_ativ"), null, null, "PROJETO")

                    ' Recupera as etapas vinculadas ao nível acima
                    DB_GetSolicEtapa RS3, w_chave, RS2("sq_projeto_etapa"), "LSTNIVEL"
                    RS3.Sort = "ordem"
                    While Not RS3.EOF
                       w_html = w_html & VbCrLf & EtapaLinha(w_chave, RS3("sq_projeto_etapa"), RS3("titulo"), RS3("nm_resp"), RS3("sg_setor"), RS3("inicio_previsto"), RS3("fim_previsto"), RS3("perc_conclusao"), RS3("qt_ativ"), null, null, "PROJETO")

                       ' Recupera as etapas vinculadas ao nível acima
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
        'Se for visualização total, ira visualizar as etapas e as atividades correspondentes
        ElseIf O = "T" Then
           While Not RS.EOF
              w_html = w_html & VbCrLf & EtapaLinhaAtiv(w_chave, Rs("sq_projeto_etapa"), Rs("titulo"), RS("nm_resp"), RS("sg_setor"), Rs("inicio_previsto"), Rs("fim_previsto"), Rs("perc_conclusao"), RS("qt_ativ"), "<b>", null, "PROJETO", "RESUMIDO")
              
              ' Recupera as etapas vinculadas ao nível acima
              DB_GetSolicEtapa RS1, w_chave, RS("sq_projeto_etapa"), "LSTNIVEL"
              RS1.Sort = "ordem"
              While Not RS1.EOF
                 w_html = w_html & VbCrLf & EtapaLinhaAtiv(w_chave, RS1("sq_projeto_etapa"), RS1("titulo"), RS1("nm_resp"), RS1("sg_setor"), RS1("inicio_previsto"), RS1("fim_previsto"), RS1("perc_conclusao"), RS1("qt_ativ"), null, null, "PROJETO", "RESUMIDO")
                 
                 ' Recupera as etapas vinculadas ao nível acima
                 DB_GetSolicEtapa RS2, w_chave, RS1("sq_projeto_etapa"), "LSTNIVEL"
                 RS2.Sort = "ordem"
                 While Not RS2.EOF
                    w_html = w_html & VbCrLf & EtapaLinhaAtiv(w_chave, RS2("sq_projeto_etapa"), RS2("titulo"), RS2("nm_resp"), RS2("sg_setor"), RS2("inicio_previsto"), RS2("fim_previsto"), RS2("perc_conclusao"), RS2("qt_ativ"), null, null, "PROJETO", "RESUMIDO")
                    
                    ' Recupera as etapas vinculadas ao nível acima
                    DB_GetSolicEtapa RS3, w_chave, RS2("sq_projeto_etapa"), "LSTNIVEL"
                    RS3.Sort = "ordem"
                    While Not RS3.EOF
                       w_html = w_html & VbCrLf & EtapaLinhaAtiv(w_chave, RS3("sq_projeto_etapa"), RS3("titulo"), RS3("nm_resp"), RS3("sg_setor"), RS3("inicio_previsto"), RS3("fim_previsto"), RS3("perc_conclusao"), RS3("qt_ativ"), null, null, "PROJETO", "RESUMIDO")
                       
                       ' Recupera as etapas vinculadas ao nível acima
                       DB_GetSolicEtapa RS4, w_chave, RS3("sq_projeto_etapa"), "LSTNIVEL"
                       RS4.Sort = "ordem"
                       While Not RS4.EOF
                          w_html = w_html & VbCrLf & EtapaLinhaAtiv(w_chave, RS4("sq_projeto_etapa"), RS4("titulo"), RS4("nm_resp"), RS4("sg_setor"), RS4("inicio_previsto"), RS4("fim_previsto"), RS4("perc_conclusao"), RS4("qt_ativ"), null, null, "PROJETO", "RESUMIDO")
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
        End If
        w_html = w_html & VbCrLf & "      </form>"
        w_html = w_html & VbCrLf &  "      </center>"
        w_html = w_html & VbCrLf & "         </table></td></tr>"
     End If
     DesconectaBD

     ' Recursos envolvidos na execução do projeto
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
        w_cor = conTrBgColor
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
  
  If O = "L" or O = "V" or O = "T" Then ' Se for listagem dos dados
     
     ' Arquivos vinculados
     DB_GetSolicAnexo RS, w_chave, null, w_cliente
     RS.Sort = "nome"
     If Not Rs.EOF Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Arquivos anexos</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
        w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Título</font></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Descrição</font></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Tipo</font></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><b>KB</font></td>"
        w_html = w_html & VbCrLf & "          </tr>"
        w_cor = conTrBgColor
        While Not Rs.EOF
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & LinkArquivo("HL", w_cliente, RS("chave_aux"), "_blank", "Clique para exibir o arquivo em outra janela.", RS("nome"), null) & "</td>"
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & Nvl(RS("descricao"),"---") & "</td>"
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS("tipo") & "</td>"
          w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & Round(cDbl(RS("tamanho"))/1024,1) & "&nbsp;</td>"
          w_html = w_html & VbCrLf & "      </tr>"
          Rs.MoveNext
        wend
        w_html = w_html & VbCrLf & "         </table></td></tr>"
     End If
     DesconectaBD
     
     ' Encaminhamentos
     DB_GetSolicLog RS, w_chave, null, "LISTA"
     RS.Sort = "data desc"
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Ocorrências e Anotações</td>"
     w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
     w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Data</font></td>"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Despacho/Observação</font></td>"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Responsável</font></td>"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Fase / Destinatário</font></td>"
     w_html = w_html & VbCrLf & "          </tr>"    
     If RS.EOF Then
        w_html = w_html & VbCrLf & "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados encaminhamentos.</b></td></tr>"
     Else
        w_html = w_html & VbCrLf & "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
        w_html = w_html & VbCrLf & "        <td colspan=6><font size=""1"">Fase atual: <b>" & RS("fase") & "</b></td>"
        w_cor = conTrBgColor
        While Not RS.EOF
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
          w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & FormatDateTime(RS("data"),2) & ", " & FormatDateTime(RS("data"),4)& "</td>"
          If Nvl(RS("caminho"),"") > "" Then
             w_html = w_html & VbCrLf & "        <td><font size=""1"">" & CRLF2BR(Nvl(RS("despacho"),"---") & "<br>" & LinkArquivo("HL", w_cliente, RS("sq_siw_arquivo"), "_blank", "Clique para exibir o anexo em outra janela.", "Anexo - " & RS("tipo") & " - " & Round(cDbl(RS("tamanho"))/1024,1) & " KB", null)) & "</td>"
          Else
             w_html = w_html & VbCrLf & "        <td><font size=""1"">" & CRLF2BR(Nvl(RS("despacho"),"---")) & "</td>"
          End If
          w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & ExibePessoa(null, w_cliente, RS("sq_pessoa"), TP, RS("responsavel")) & "</td>"
          If (Not IsNull(Tvl(RS("sq_projeto_log")))) and (Not IsNull(Tvl(RS("destinatario")))) Then
             w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & ExibePessoa(null, w_cliente, RS("sq_pessoa_destinatario"), TP, RS("destinatario")) & "</td>"
          ElseIf (Not IsNull(Tvl(RS("sq_projeto_log")))) and IsNull(Tvl(RS("destinatario"))) Then
             w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">Anotação</td>"
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
REM Fim da visualização dos dados do cliente
REM -------------------------------------------------------------------------

%>

