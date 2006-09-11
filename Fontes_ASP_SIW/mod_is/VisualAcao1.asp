<%

REM =========================================================================
REM Rotina de visualização dos dados da ação
REM -------------------------------------------------------------------------
Function VisualAcao(w_chave, O, w_usuario, P1, P4)

  Dim Rsquery, w_Erro
  Dim w_Imagem, w_html
  Dim w_ImagemPadrao
  Dim w_tipo_visao
  Dim w_p2, w_fases
  Dim w_titulo
  
  w_html = ""

  ' Recupera os dados da ação
  DB_GetSolicData_IS RS, w_chave, "ISACGERAL"
  w_tipo_visao = 0
  
  'Se for para exibir só a ficha resumo da ação.
  If P1 = 1 or P1 = 2 or P1 = 3 Then 
     w_html = w_html & VbCrLf & "<div align=center><center>"
     w_html = w_html & VbCrLf & "  <table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
     w_html = w_html & VbCrLf & "    <tr bgcolor=""" & conTrBgColor & """><td align=""center"" colspan=""2"">"
     w_html = w_html & VbCrLf & "      <table width=""100%"" border=""0"">"
     If Not P4 = 1 Then
        w_html = w_html & VbCrLf & "      <tr><td align=""right"" colspan=""3""><font size=""1""><b><A class=""HL"" HREF=""" & w_dir & "Acao.asp?par=Visual&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=volta&P1=&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exibe as informações da ação."">Exibir todas as informações</a></td></tr>"
     End If
     
     ' Se a projeto especificco for informada, exibe.
     If Not IsNull(RS("sq_isprojeto")) Then
        w_html = w_html & VbCrLf & " <tr><td valign=""top"" colspan=""3""><font size=""1"">Programa interno:<br><b>" & RS("nm_pri") & "</b></td></tr>"
     End If     
     
     ' Se a ação no PPA for informada, exibe.
     If Not IsNull(RS("cd_acao")) Then
        w_html = w_html & VbCrLf & "   <tr valign=""top""><td colspan=""3""><table border=0 width=""100%"" cellspacing=0>"
        w_html = w_html & VbCrLf & "      <tr bgcolor=""#D0D0D0""><td colspan=""3""><font size=""1"">Unidade:<br><b>" & RS("cd_unidade") & " - " & RS("ds_unidade") & " </b></td></tr>"
        w_html = w_html & VbCrLf & "      <tr bgcolor=""#D0D0D0""><td colspan=""3""><font size=""1"">Programa PPA:<br><b>" & RS("cd_ppa_pai") & " - " & RS("nm_ppa_pai") & "</b></td></tr>"
        w_html = w_html & VbCrLf & "      <tr bgcolor=""#D0D0D0""><td colspan=""2""><font size=""1"">Ação PPA:<br><b>" & RS("cd_acao") & " - " & RS("nm_ppa") & " </b></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Recurso programado:<br><b>" & FormatNumber(RS("valor"),2) & " </b></td></tr>"
        w_html = w_html & VbCrLf & "   </table></td></tr>"
     Else
        w_html = w_html & VbCrLf & "      <tr bgcolor=""#D0D0D0""><td colspan=""2""><font size=""1"">Ação:<br><b>" & RS("titulo") & "</b></td>"
        w_html = w_html & VbCrLf & "                              <td><font size=""1"">Recurso programado:<br><b>" & FormatNumber(RS("valor"),2) & " </b></td></tr>"
     End If
     DB_GetPersonData RS1, w_cliente, RS("Solicitante"), null, null  
     If P4 = 1 Then
        w_html = w_html & VbCrLf & "         <tr><td valign=""top""><font size=""1"">Responsável monitoramento:<br><b>" & RS("nm_sol") & "</b></td>"
        w_html = w_html & VbCrLf & "             <td valign=""top""><font size=""1"">E-mail:<br><b>" & RS1("email") & "</b></td></tr>"
     Else
        w_html = w_html & VbCrLf & "         <tr><td valign=""top""><font size=""1"">Responsável monitoramento:<br><b>" & ExibePessoa("../", w_cliente, RS("solicitante"), TP, RS("nm_sol")) & "</b></td>"
        w_html = w_html & VbCrLf & "             <td valign=""top""><font size=""1"">E-mail:<br><b><A class=""HL"" HREF=""mailto:" & RS1("email") & """>" & RS1("email") & "</a></b></td>"
     End If 
     w_html = w_html & VbCrLf & "                <td valign=""top""><font size=""1"">Telefone:<br><b>" & Nvl(RS("fone_pri"),"---") & " </b></td>"
     w_html = w_html & VbCrLf & "            </tr>"
     RS1.Close
     w_html = w_html & VbCrLf & "         </table></td></tr>"
     
     If w_tipo_visao = 0 or w_tipo_visao = 1 Then 
        ' Metas da ação
        ' Recupera todos os registros para a listagem     
        DB_GetSolicMeta_IS RS1, w_chave, null, "LSTNULL", null, null, null, null, null, null, null, null, null
        RS1.Sort = "ordem"
        If Not RS1.EOF Then ' Se não foram selecionados registros, exibe mensagem
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""left"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""2""><b>&nbsp;Metas Cadastradas</td></tr>"
           w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
            w_html = w_html & VbCrLf & "       <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           While Not RS1.EOF
              w_html = w_html & VbCrLf & "         <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
              w_html = w_html & VbCrLf & "           <td rowspan=""2""><font size=""1""><b>Produto</font></td>"
              w_html = w_html & VbCrLf & "           <td rowspan=""2""><font size=""1""><b>Unidade medida</font></td>"
              w_html = w_html & VbCrLf & "           <td rowspan=""2""><font size=""1""><b>PPA</font></td>"
              w_html = w_html & VbCrLf & "           <td rowspan=""2""><font size=""1""><b>Cumulativa</font></td>"
              w_html = w_html & VbCrLf & "           <td rowspan=""2""><font size=""1""><b>Será cumprida</font></td>"
              w_html = w_html & VbCrLf & "           <td rowspan=""1"" colspan=""3""><font size=""1""><b>Quantitativo</font></td>"
              w_html = w_html & VbCrLf & "         </tr>"
              w_html = w_html & VbCrLf & "         <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
              w_html = w_html & VbCrLf & "           <td><font size=""1""><b>Programado</font></td>"
              w_html = w_html & VbCrLf & "           <td><font size=""1""><b>Realizado</font></td>"
              w_html = w_html & VbCrLf & "           <td><font size=""1""><b>% Realizado</font></td>"
              w_html = w_html & VbCrLf & "         </tr>"
              w_html = w_html & VbCrLf & "         <tr bgcolor=""" & conTrAlternateBgColor & """ valign=""top"">"
              w_html = w_html & VbCrLf & "           <td nowrap><font size=""1"">"
              If (RS1("fim_previsto") < Date()) and (cDbl(Nvl(RS1("perc_conclusao"),0)) < 100) Then
                 w_html = w_html & VbCrLf & "           <img src=""" & conImgAtraso & """ border=0 width=15 height=15 align=""center"">"
              ElseIf cDbl(Nvl(RS1("perc_conclusao"),0)) < 100 Then
                 w_html = w_html & VbCrLf & "           <img src=""" & conImgNormal & """ border=0 width=15 height=15 align=""center"">"
              Else
                 w_html = w_html & VbCrLf & "           <img src=""" & conImgOkNormal & """ border=0 width=15 height=15 align=""center"">"
              End If
              If cDbl(P4) = 1 Then
                 w_html = w_html & VbCrLf & RS1("titulo") & "</td>"
              Else
                 w_html = w_html & VbCrLf & "<A class=""HL"" HREF=""#"" onClick=""window.open('Acao.asp?par=AtualizaMeta&O=V&w_chave=" & RS1("sq_siw_solicitacao") & "&w_chave_aux=" & RS1("sq_meta") & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Meta','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" &RS1("titulo") & "</A></td>"
              End If
              w_html = w_html & VbCrLf & "          <td><font size=""1"">" & Nvl(RS1("unidade_medida"),"---") & "</font></td>"
              If RS1("cd_subacao") > "" Then
                 w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">Sim</font></td>"
              Else
                 w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">Não</font></td>"
              End If
              w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & Nvl(RS1("nm_cumulativa"),"---") & "</font></td>"
              w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & Nvl(RS1("nm_exequivel"),"---")  & "</font></td>"
              w_html = w_html & VbCrLf & "          <td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("quantidade"),0)),2) & "</font></td>"
              w_html = w_html & VbCrLf & "          <td align=""right""><font size=""1"">"&FormatNumber((cDbl(Nvl(RS1("quantidade"),0)) * cDbl(Nvl(RS1("perc_conclusao"),0)))/100,2)&"</font></td>"
              w_html = w_html & VbCrLf & "          <td align=""right""><font size=""1"">"& cDbl(Nvl(RS1("perc_conclusao"),0))&"</font></td></tr>"
              w_html = w_html & VbCrLf & "      <tr><td colspan=""8""><font size=""1""><DD>Especifição do produto: <b>" & Nvl(RS1("descricao"),"---") & "</DD></font></td></tr>"
              w_html = w_html & VbCrLf & "      <tr><td colspan=""8""><font size=""1""><DD>Situação atual: <b>" & Nvl(RS1("situacao_atual"),"---") & "</DD></font></td></tr>"
              If RS1("exequivel") = "N" Then
                 w_html = w_html & VbCrLf & "      <tr><td colspan=""8""><font size=""1""><DD>Quais os motivos para o não cumprimento da meta? <b>"&Nvl(RS1("justificativa_inexequivel"),"---")&"</DD></font></td></tr>"
                 w_html = w_html & VbCrLf & "      <tr><td colspan=""8""><font size=""1""><DD>Quais as medidas necessárias para o cumprimento da meta? <b>"&Nvl(RS1("outras_medidas"),"---")&"</DD></font></td></tr>"
              End If
              RS1.MoveNext
           wend
           w_html = w_html & VbCrLf & "         </table></td></tr>"
        End If
        RS1.Close
     End If
     If w_tipo_visao = 0 Then 
        ' Listagem das tarefas na visualização da ação, rotina adquirida apartir da rotina exitente na ProjetoAtiv.asp para listagem das tarefas
        DB_GetLinkData RS, RetornaCliente(), "ISTCAD"
        DB_GetSolicList_IS RS, RS("sq_menu"), RetornaUsuario(), "ISTCAD", 5, _
           null, null, null, null, null, null, _
           null, null, null, null, _
           null, null, null, null, null, null, null, _
           null, null, null, null, w_chave, null, null, null, null, null, w_ano
        RS.sort = "ordem, fim, prioridade"
        If Not RS.EOF Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""left"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""2""><b>&nbsp;Tarefas Cadastradas</td></tr>"
           w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
           w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
           w_html = w_html & VbCrLf & "            <td nowrap><font size=""1""><b>Nº</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Detalhamento</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Responsável</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Parcerias</font></td>"
           w_html = w_html & VbCrLf & "            <td nowrap><font size=""1""><b>Fim<br>previsto</font></td>"
           w_html = w_html & VbCrLf & "            <td nowrap><font size=""1""><b>Programado<br>R$ 1,00</font></td>"
           w_html = w_html & VbCrLf & "            <td nowrap><font size=""1""><b>Executado<br>R$ 1,00</font></td>"
           w_html = w_html & VbCrLf & "            <td nowrap><font size=""1""><b>Fase atual</font></td>"
           w_html = w_html & VbCrLf & "            <td nowrap><font size=""1""><b>Prioridade</font></td>"
           w_html = w_html & VbCrLf & "          </tr>"
           While Not RS.EOF 
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
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
              If P4 = 1 Then
                 w_html = w_html & VbCrLf             & RS("sq_siw_solicitacao") & "</td>"
              Else
                 w_html = w_html & VbCrLf & "         <A class=""HL"" HREF=""" & w_dir & "Tarefas.asp?par=Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste registro."" target=""blank"">" & RS("sq_siw_solicitacao") & "&nbsp;</a></td>"
              End If
              'If Len(Nvl(RS("assunto"),"-")) > 80 Then w_titulo = Mid(Nvl(RS("assunto"),"-"),1,50) & "..." Else w_titulo = Nvl(RS("assunto"),"-") End If
              If RS("sg_tramite") = "CA" Then
                 w_html = w_html & VbCrLf & "      <td><font size=""1""><strike>" & Nvl(RS("assunto"),"-") & "</strike></td>"
              Else
                 w_html = w_html & VbCrLf & "      <td><font size=""1"">" &  Nvl(RS("assunto"),"-") & "</td>"
              End If
              w_html = w_html & VbCrLf & "         <td><font size=""1"">" & Nvl(RS("palavra_chave"),"---") & "</td>"
              w_html = w_html & VbCrLf & "         <td><font size=""1"">" & Nvl(RS("proponente"),"---") & "</td>"
              w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">&nbsp;" & Nvl(FormatDateTime(RS("fim"),2),"-") & "</td>"
              w_html = w_html & VbCrLf & "         <td align=""right"" nowrap><font size=""1"">" & FormatNumber(cDbl(Nvl(RS("valor"),0)),2) & "</td>"
              w_html = w_html & VbCrLf & "         <td align=""right"" nowrap><font size=""1"">" & FormatNumber(cDbl(Nvl(RS("custo_real"),0)),2) & "</td>"
              w_html = w_html & VbCrLf & "         <td nowrap><font size=""1"">" & RS("nm_tramite") & "</td>"
              w_html = w_html & VbCrLf & "         <td nowrap><font size=""1"">" & RetornaPrioridade(RS("prioridade")) & "</td></tr>"
              RS.MoveNext        
           wend
           w_html = w_html & VbCrLf & "         </table></td></tr>" 
        End If  
        DesconectaBD
     End If     
     w_html = w_html & VbCrLf & "</table>"
     w_html = w_html & VbCrLf & "</center>"
     w_html = w_html & VbCrLf & "</div>"
  Else
     If O = "L" or O = "V" Then ' Se for listagem dos dados
        w_html = w_html & VbCrLf & "<div align=center><center>"
        w_html = w_html & VbCrLf & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
        w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"

        w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
        w_html = w_html & VbCrLf & "      <tr valign=""top""><td colspan=""2""><font size=2>Ação: <b>" & RS("titulo") & "</b></font></td></tr>"
      
        ' Identificação da ação
        w_html = w_html & VbCrLf & "      <tr valign=""top""><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Identificação</td>"
     
        ' Se a ação no PPA for informada, exibe.
        If Not IsNull(RS("cd_acao")) Then
           w_html = w_html & VbCrLf & "   <tr valign=""top"" bgcolor=""#D0D0D0""><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
           w_html = w_html & VbCrLf & "     <tr valign=""top""><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
           w_html = w_html & VbCrLf & "       <tr bgcolor=""#D0D0D0""><td colspan=""1"" nowrap><font size=""1"">Unidade:<br><b>" & RS("cd_unidade") & " - " & RS("ds_unidade") & " </b></td>"
           w_html = w_html & VbCrLf & "        <td><font size=""1"">Órgão:<br><b>" & RS("cd_orgao") & " - " & RS("nm_orgao") & " </b></td></tr>"
           w_html = w_html & VbCrLf & "     </table></td></tr>"
           w_html = w_html & VbCrLf & "      <tr bgcolor=""#D0D0D0""><td colspan=""2""><font size=""1"">Programa PPA:<br><b>" & RS("cd_ppa_pai") & " - " & RS("nm_ppa_pai") & "</b></td></tr>"
           w_html = w_html & VbCrLf & "      <tr bgcolor=""#D0D0D0""><td colspan=""1""><font size=""1"">Ação PPA:<br><b>" & RS("cd_acao") & " - " & RS("nm_ppa") & " </b></td>"
           w_html = w_html & VbCrLf & "        <td valign=""top"" nowrap><font size=""1"">Recurso programado:<br><b>" & FormatNumber(RS("valor"),2) & " </b></td>"
           w_html = w_html & VbCrLf & "   </table>"
        End If        
        ' Se a programa interno for informada, exibe.
        If Not IsNull(RS("sq_isprojeto")) Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""1""><font size=""1"">Programa interno:<br><b>" & RS("nm_pri")
           If Not IsNull(RS("cd_pri")) Then 
              w_html = w_html & VbCrLf & " (" & RS("cd_pri") & ")" 
           End If
           If IsNull(RS("cd_acao")) Then
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Recurso programado:<br><b>" & FormatNumber(RS("valor"),2) & " </b></td>"
           End If
        End If
        If P4 = 1 Then
           w_html = w_html & VbCrLf & "  <tr><td colspan=""2""><font size=""1"">Unidade Administrativa:<br><b>" & RS("nm_unidade_adm") & " </b></td>"
        Else
           w_html = w_html & VbCrLf & "  <tr><td colspan=""2""><font size=""1"">Unidade Administrativa:<br><b>" & ExibeUnidade("../", w_cliente, RS("nm_unidade_adm"), RS("sq_unidade_adm"), TP) & "</b></td>"
        End If
        w_html = w_html & VbCrLf & "   <tr valign=""top""><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
        w_html = w_html & VbCrLf & "     <tr valign=""top"">"
        If RS("mpog_ppa") = "S" Then
           w_html = w_html & VbCrLf & "    <td><font size=""1"">Selecionada SPI/MP:<br><b>Sim</b></td>"
        Else
           w_html = w_html & VbCrLf & "    <td><font size=""1"">Selecionada SPI/MP:<br><b>Não</b></td>"
        End If
        If RS("relev_ppa") = "S" Then
           w_html = w_html & VbCrLf & "    <td><font size=""1"">Selecionada SE/SEPPIR:<br><b>Sim</b></td>"
        Else
           w_html = w_html & VbCrLf & "    <td><font size=""1"">Selecionada SE/SEPPIR:<br><b>Não</b></td>"
        End If
        w_html = w_html & VbCrLf & "     <tr valign=""top"">"
        If P4 = 1 Then
           w_html = w_html & VbCrLf & "    <td><font size=""1"">Responsável monitoramento:<br><b>" & RS("nm_sol") & "</b></td>"   
        Else
           w_html = w_html & VbCrLf & "    <td><font size=""1"">Responsável monitoramento:<br><b>" & ExibePessoa("../", w_cliente, RS("solicitante"), TP, RS("nm_sol")) & "</b></td>"
        End If
        If P4 = 1 Then
           w_html = w_html & VbCrLf & "    <td><font size=""1"">Área planejamento:<br><b>" & RS("nm_unidade_resp") & " </b></td>"
        Else
           w_html = w_html & VbCrLf & "    <td><font size=""1"">Área planejamento:<br><b>" & ExibeUnidade("../", w_cliente, RS("nm_unidade_resp"), RS("sq_unidade"), TP) & "</b></td>"
        End If
        If Not IsNull(RS("cd_acao")) Then
           DB_GetAcaoPPA_IS RS1, w_cliente, w_ano, RS("cd_ppa_pai"), RS("cd_acao"), null, RS("cd_unidade"), null, null, null
           
           w_html = w_html & VbCrLf & "     <tr valign=""top"">"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">Função:<br><b>" & RS1("ds_funcao") & " </b></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">Subfunção:<br><b>" & RS1("ds_subfuncao") & " </b></td>"
           w_html = w_html & VbCrLf & "     <tr valign=""top"">"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">Esfera:<br><b>" & RS1("ds_esfera") & " </b></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">Tipo de ação:<br><b>" & RS1("nm_tipo_acao") & " </b></td>"    
           RS1.Close
        End If
        'w_html = w_html & VbCrLf & "   </table>"
        'w_html = w_html & VbCrLf & "   <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
        w_html = w_html & VbCrLf & "     <tr valign=""top"">"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">Início previsto:<br><b>" & FormataDataEdicao(RS("inicio")) & " </b></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">Fim previsto:<br><b>" & FormataDataEdicao(RS("fim")) & " </b></td>"
        w_html = w_html & VbCrLf & "     </table>"
        w_html = w_html & VbCrLf & "     <tr valign=""top""><td colspan=""2""><font size=""1"">Parcerias externas:<br><b>" & CRLF2BR(Nvl(RS("proponente"),"---")) & " </b></td>"
        w_html = w_html & VbCrLf & "     <tr valign=""top""><td colspan=""2""><font size=""1"">Parcerias internas:<br><b>" & CRLF2BR(Nvl(RS("palavra_chave"),"---")) & " </b></td>"
     
        ' Responsaveis
        If RS("nm_gerente_programa") > "" or RS("nm_gerente_executivo") > "" or RS("nm_gerente_adjunto") > "" or RS("resp_ppa") > "" or RS("resp_pri") > ""  Then  
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Responsáveis</td>" 
        End If
        If RS("nm_gerente_programa") > "" or RS("nm_gerente_executivo") > "" or RS("nm_gerente_adjunto") > "" Then  
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
           If Not IsNull(RS("nm_gerente_programa")) Then           
              w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Gerente do programa:<br><b>" & RS("nm_gerente_programa") & " </b></td>"
              If Not IsNull(RS("fn_gerente_programa")) Then
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<br><b>" & RS("fn_gerente_programa") & " </b></td>"
              End If
              If Not IsNull(RS("em_gerente_programa")) Then
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Email:<br><b>" & RS("em_gerente_programa") & " </b></td>"
              End If
           End If
           If Not IsNull(RS("nm_gerente_executivo")) Then
              w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Gerente executivo do programa:<br><b>" & RS("nm_gerente_executivo") & " </b></td>"
              If Not IsNull(RS("fn_gerente_executivo")) Then
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<br><b>" & RS("fn_gerente_executivo") & " </b></td>"
              End If
              If Not IsNull(RS("em_gerente_executivo")) Then
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Email:<br><b>" & RS("em_gerente_executivo") & " </b></td>"
              End If
           End If
           If Not IsNull(RS("nm_gerente_adjunto")) Then
              w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Gerente executivo adjunto:<br><b>" & RS("nm_gerente_adjunto") & " </b></td>"
              If Not IsNull(RS("fn_gerente_adjunto")) Then
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<br><b>" & RS("fn_gerente_adjunto") & " </b></td>"
              End If
              If Not IsNull(RS("em_gerente_adjunto")) Then
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Email:<br><b>" & RS("em_gerente_adjunto") & " </b></td>"
              End If
           End If
           w_html = w_html & VbCrLf & "          </table>"
        End If
        If Not IsNull(RS("resp_ppa")) Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
           w_html = w_html & VbCrLf & "        <tr><td valign=""top""><font size=""1"">Coordenador:<br><b>" & RS("resp_ppa") & " </b></td>"
           If Not IsNull(RS("fone_ppa")) Then
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<br><b>" & RS("fone_ppa") & " </b></td>"
           End If
           If Not IsNull(RS("mail_ppa")) Then
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Email:<br><b>" & RS("mail_ppa") & " </b></td>"
           End If
           w_html = w_html & VbCrLf & "          </table>"
        End If
        If Not IsNull(RS("resp_pri")) Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
           w_html = w_html & VbCrLf & "        <tr><td valign=""top""><font size=""1"">Responsável pela ação:<br><b>" & RS("resp_pri") & " </b></td>"
           If Not IsNull(RS("fone_pri")) Then
              w_html = w_html & VbCrLf & "         <td><font size=""1"">Telefone:<br><b>" & RS("fone_pri") & " </b></td>"
           End If
           If Not IsNull(RS("mail_pri")) Then
              w_html = w_html & VbCrLf & "            <td><font size=""1"">Email:<br><b>" & RS("mail_pri") & " </b></td>"
           End If
           w_html = w_html & VbCrLf & "           </table>"
        End If
        
        ' Dados da conclusão da ação, se ela estiver nessa situação
        If RS("concluida") = "S" and Nvl(RS("data_conclusao"),"") > "" Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Dados da conclusão</td>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
           w_html = w_html & VbCrLf & "          <tr valign=""top"">"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Início da execução:<br><b>" & FormataDataEdicao(RS("inicio_real")) & " </b></td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Término da execução:<br><b>" & FormataDataEdicao(RS("fim_real")) & " </b></td>"
           If w_tipo_visao = 0 Then
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Recurso executado:<br><b>" & FormatNumber(RS("custo_real"),2) & " </b></td>"
           End If
           w_html = w_html & VbCrLf & "          </table>"
           If w_tipo_visao = 0 Then
              w_html = w_html & VbCrLf & "      <tr><td colspan=""2"" valign=""top""><font size=""1"">Nota de conclusão:<br><b>" & CRLF2BR(RS("nota_conclusao")) & " </b></td>"
           End If
        End If
        
        If w_tipo_visao = 0 or w_tipo_visao = 1 Then
           ' Programação Qualitativa
           
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Programação Qualitativa</td>"
           If Not IsNull(RS("cd_acao")) Then
              w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><div align=""justify""><font size=""1"">Objetivo:<br><b>" & Nvl(RS("finalidade"),"---")& "</b></div></td>"
           End if
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><div align=""justify""><font size=""1"">Justificativa:<br><b>" & Nvl(RS("problema"),"---")& "</b></div></td>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><div align=""justify""><font size=""1"">Objetivo específico:<br><b>" & Nvl(RS("objetivo"),"---")& "</b></div></td>"
           If Not IsNull(RS("cd_acao")) Then
              w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><div align=""justify""><font size=""1"">Descrição da ação:<br><b>" & Nvl(RS("descricao_ppa"),"---")& "</b></div></td>"
           End If
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><div align=""justify""><font size=""1"">Público-alvo:<br><b>" & Nvl(RS("publico_alvo"),"---")& "</b></div></td>"
           If Not IsNull(RS("cd_acao")) Then
              w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><div align=""justify""><font size=""1"">Origem da ação:<br><b>" & Nvl(RS("nm_tipo_inclusao_acao"),"---")& "</b></div></td>"
              w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><div align=""justify""><font size=""1"">Base legal:<br><b>" & Nvl(RS("base_legal"),"---")& "</b></div></td>"
              w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><font size=""1"">Forma de implementação:<br><b>" 
              If cDbl(RS("cd_tipo_acao")) = 1 or cDbl(RS("cd_tipo_acao")) = 2 Then 
                 If RS("direta") = "S" Then
                    w_html = w_html & VbCrLf & " direta"
                 End If
                 If RS("descentralizada") = "S" Then
                    w_html = w_html & VbCrLf & " descentralizada"
                 End If
                 If RS("linha_credito") = "S" Then
                    w_html = w_html & VbCrLf & " linha de crédito"
                 End If
              ElseIf cDbl(RS("cd_tipo_acao")) = 4 Then
                  If RS("transf_obrigatoria") = "S" Then
                    w_html = w_html & VbCrLf & " transferência obrigatória"
                 End If
                 If RS("transf_voluntaria") = "S" Then
                    w_html = w_html & VbCrLf & " transferência voluntária"
                 End If
                 If RS("transf_outras") = "S" Then
                    w_html = w_html & VbCrLf & " outras"
                 End If
              End If
              w_html = w_html & VbCrLf & "      </b></td>"
              w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><div align=""justify""><font size=""1"">Detalhamento da implementação:<br><b>" & Nvl(RS("detalhamento"),"---")& "</b></div></td>"
           End If
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><div align=""justify""><font size=""1"">Sistemática e estratégias a serem adotadas para o monitoramento da ação:<br><b>" & Nvl(RS("estrategia"),"---")& "</b></div></td>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><div align=""justify""><font size=""1"">Sistemática e metodologias a serem adotadas para avaliação da ação:<br><b>" & Nvl(RS("sistematica"),"---")& "</b></div></td>"
           'w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><font size=""1"">Metodologias de avaliação a serem utilizadas:<br><b>" & Nvl(RS("metodologia"),"---")& "</b></td>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><div align=""justify""><font size=""1"">Observações:<br><b>" & Nvl(RS("justificativa"),"---")& "</b></div></td>"
        End If
     End If
     
     ' Restricoes da ação
     ' Recupera todos os registros para a listagem
     'DB_GetRestricaoAcao_IS RS1, w_cliente, w_ano, RS("cd_programa"), RS("cd_acao"), RS("cd_subacao"), null, RS("cd_unidade")
     DB_GetRestricao_IS RS1, "ISACRESTR", w_chave, null
     RS1.Sort = "inclusao desc"
     If Not RS1.EOF Then ' Se não foram selecionados registros, exibe mensagem   
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Restrições</td></tr>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
        w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Descrição</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Tipo restrição</font></td>"
        'w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Tipo inclusão</font></td>"
        'w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Competência</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Inclusão</font></td>"
        'w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Superação</font></td>"
        w_html = w_html & VbCrLf & "          </tr>"
        While Not RS1.EOF
           If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
           w_html = w_html & VbCrLf & "       <tr bgcolor=""" & w_cor & """ valign=""top"">"
           w_html = w_html & VbCrLf & "         <td><font size=""1""><A class=""HL"" HREF=""#"" onClick=""window.open('Acao.asp?par=Restricao&O=V&w_chave=" & w_chave & "&w_chave_aux=" & RS1("sq_restricao") & "&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=ISACRESTR','Restricao','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" &RS1("descricao") & "</A></td>"
           w_html = w_html & VbCrLf & "         <td><font size=""1"">" & RS1("nm_tp_restricao")& "</td>"
           'w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">" & NVL(RS1("cd_tipo_inclusao"),"---") & "</td>"
           'w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">" & NVL(RS1("cd_competencia"),"---")& "</td>"
           w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">" & FormataDataEdicao(RS1("inclusao"))& "</td>"
           'w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">" & NVL(FormataDataEdicao(RS1("superacao")),"---")& "</td>"
           w_html = w_html & VbCrLf & "       </tr>"
           RS1.MoveNext
        wend
        w_html = w_html & VbCrLf & "        </table></td></tr>"  
     End If
     RS1.Close
     
     ' Programação financeira
     If Not IsNull(RS("cd_acao")) Then
        If cDbl(RS("cd_tipo_acao")) <> 3 Then
           w_html = w_html & VbCrLf & "        <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Programação financeira</td>"        
           DB_GetPPADadoFinanc_IS RS1, RS("cd_acao"), RS("cd_unidade"), w_ano, w_cliente, "VALORFONTEACAO"
           If RS1.EOF Then
              w_html = w_html & VbCrLf & "                      <tr><td valign=""top"" colspan=""2""><font size=""1""><DD><b>Nao existe nenhum valor para esta ação</b></DD></td>"
           Else
              w_cor = ""
              w_html = w_html & VbCrLf & "                      <tr><td valign=""top"" colspan=""2""><font size=""1"">Fonte: SIGPLAN/MP - PPA 2004-2007</td>"
              If cDbl(RS("cd_tipo_acao")) = 1 Then
                 w_html = w_html & VbCrLf & "                   <tr><td valign=""top"" colspan=""2""><font size=""1"">Realizado até 2004: <b>" & FormatNumber(Nvl(RS("valor_ano_anterior"),0),2) & "</b></td>"
                 w_html = w_html & VbCrLf & "                   <tr><td valign=""top"" colspan=""2""><font size=""1"">Justificativa da repercusão financeira sobre o custeio da União: <b>" & Nvl(RS("reperc_financeira"),"---") & "</b></td>"
                 w_html = w_html & VbCrLf & "                   <tr><td valign=""top"" colspan=""2""><font size=""1"">Valor estimado da repercussão financeira por ano (R$ 1,00): <b>" & FormatNumber(Nvl(RS("valor_reperc_financeira"),0),2) & "</b></td>"
              End If
              w_html = w_html & VbCrLf & "                      <tr><td valign=""top"" colspan=""2""><font size=""1""><b>Ação: </b>" & RS1("cd_unidade") & "." & RS("cd_programa") & "." & RS1("cd_acao") & " - " & RS1("descricao_acao") & "</td>"
              w_html = w_html & VbCrLf & "                      <tr><td valign=""top"" align=""center"">"
              w_html = w_html & VbCrLf & "                        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
              w_html = w_html & VbCrLf & "                          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
              w_html = w_html & VbCrLf & "                            <td><font size=""1""><b>Fonte</font></td>"
              w_html = w_html & VbCrLf & "                            <td><font size=""1""><b>2004</font></td>"
              w_html = w_html & VbCrLf & "                            <td><font size=""1""><b>2005</font></td>"
              w_html = w_html & VbCrLf & "                            <td><font size=""1""><b>2006</font></td>"
              w_html = w_html & VbCrLf & "                            <td><font size=""1""><b>2007</font></td>"
              w_html = w_html & VbCrLf & "                            <td><font size=""1""><b>2008</font></td>"
              w_html = w_html & VbCrLf & "                            <td><font size=""1""><b>Total 2004-2008</font></td>"
              w_html = w_html & VbCrLf & "                          </tr>"
              While Not RS1.EOF 
                 If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
                 w_html = w_html & VbCrLf & "                       <tr bgcolor=""" & w_cor & """ valign=""top"">"
                 w_html = w_html & VbCrLf & "                         <td><font size=""1"">" & RS1("nm_fonte")& "</td>"
                 w_html = w_html & VbCrLf & "                         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_1"),0.00)))& "</td>"
                 w_html = w_html & VbCrLf & "                         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_2"),0.00)))& "</td>"
                 w_html = w_html & VbCrLf & "                         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_3"),0.00)))& "</td>"
                 w_html = w_html & VbCrLf & "                         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_4"),0.00)))& "</td>"
                 w_html = w_html & VbCrLf & "                         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_5"),0.00)))& "</td>"
                 w_html = w_html & VbCrLf & "                         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_total"),0.00)))& "</td>"
                 w_html = w_html & VbCrLf & "                       </tr>"
                 RS1.MoveNext
              wend
              w_html = w_html & VbCrLf & "                        </table>"   
           End If   
           RS1.Close
           DB_GetPPADadoFinanc_IS RS1, RS("cd_acao"), RS("cd_unidade"), w_ano, w_cliente, "VALORTOTALACAO"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><font size=""1"">Valor total: </td>"
           If RS1.EOF Then
              w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><font size=""1""><DD><b>Nao existe nenhum valor para esta ação</b></DD></td>"
           Else
              w_cor = ""
              w_html = w_html & VbCrLf & "      <tr><td colspan=""2"" align=""center"">"
              w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
              w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
              w_html = w_html & VbCrLf & "            <td><font size=""1""><b>2004</font></td>"
              w_html = w_html & VbCrLf & "            <td><font size=""1""><b>2005</font></td>"
              w_html = w_html & VbCrLf & "            <td><font size=""1""><b>2006</font></td>"
              w_html = w_html & VbCrLf & "            <td><font size=""1""><b>2007</font></td>"
              w_html = w_html & VbCrLf & "            <td><font size=""1""><b>2008</font></td>"
              w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Total 2004-2008</font></td>"
              w_html = w_html & VbCrLf & "          </tr>"
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              w_html = w_html & VbCrLf & "       <tr bgcolor=""" & w_cor & """ valign=""top"">"
              w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_1"),0.00)))& "</td>"
              w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_2"),0.00)))& "</td>"
              w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_3"),0.00)))& "</td>"
              w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_4"),0.00)))& "</td>"
              w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_5"),0.00)))& "</td>"
              w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_total"),0.00)))& "</td>"
              w_html = w_html & VbCrLf & "       </tr>"
              w_html = w_html & VbCrLf & "       </table>"  
           End If
           RS1.Close
        End If
        ' Recupera todos os registros para a listagem
        DB_GetFinancAcaoPPA_IS RS1, w_chave, w_cliente, w_ano, null, null, null
        ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
        If Not RS1.EOF Then ' Se não foram selecionados registros, exibe mensagem
           w_html = w_html & VbCrLf & "<tr><td colspan=""2"" align=""center"">"
           w_html = w_html & VbCrLf & "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           w_html = w_html & VbCrLf & "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
           w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Código</font></td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Nome</font></td>"
           w_html = w_html & VbCrLf & "        </tr>"
           w_cor = ""
           ' Lista os registros selecionados para listagem
           While Not RS1.EOF
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              w_html = w_html & VbCrLf & "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
              w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS1("cd_programa")& "." & RS1("cd_acao") & "." & RS1("cd_unidade")& "</td>"
              w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS1("descricao_acao") & "</td>"
              w_html = w_html & VbCrLf & "      </tr>"
              RS1.MoveNext
           wend
           w_html = w_html & VbCrLf & "          </table>"   
        End If
     Else
        'w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><font size=""1""><DD><b>Nao existe progração financeira para esta açao, pois é uma ação não orçamentária.</b></DD></td>"
        ' Recupera todos os registros para a listagem
        DB_GetFinancAcaoPPA_IS RS1, w_chave, w_cliente, w_ano, null, null, null
        ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
        If Not RS1.EOF Then ' Se não foram selecionados registros, exibe mensagem
           w_html = w_html & VbCrLf & "        <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Programação financeira</td>"
           w_html = w_html & VbCrLf & "<tr><td colspan=""2"" align=""center"">"
           w_html = w_html & VbCrLf & "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           w_html = w_html & VbCrLf & "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
           w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Código</font></td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Nome</font></td>"
           w_html = w_html & VbCrLf & "        </tr>"
           w_cor = ""
           ' Lista os registros selecionados para listagem
           While Not RS1.EOF
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              w_html = w_html & VbCrLf & "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
              w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS1("cd_unidade") & "." & RS1("cd_programa")& "." & RS1("cd_acao") & "</td>"
              w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS1("descricao_acao") & "</td>"
              w_html = w_html & VbCrLf & "      </tr>"
              RS1.MoveNext
           wend
           w_html = w_html & VbCrLf & "          </table>"   
        End If
     End If

     ' Metas da ação
     DB_GetSolicMeta_IS RS1, w_chave, null, "LSTNULL", null, null, null, null, null, null, null, null, null
     RS1.Sort = "ordem"
     If Not RS1.EOF Then ' Se não foram selecionados registros, exibe mensagem        
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Metas físicas</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
        w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Metas</font></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><b>PPA</font></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Data conclusão</font></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Executado</font></td>"
        w_html = w_html & VbCrLf & "          </tr>"
        While Not RS1.EOF
           w_html = w_html & VbCrLf & MetaLinha(w_chave, RS1("sq_meta"), RS1("titulo"), RS1("nm_resp"), RS1("sg_setor"), RS1("inicio_previsto"), RS1("fim_previsto"), RS1("perc_conclusao"), null, "<b>", null, "PROJETO", RS1("cd_subacao"))
           RS1.MoveNext
        wend
        w_html = w_html & VbCrLf & "      </form>"
        w_html = w_html & VbCrLf &  "      </center>"
        w_html = w_html & VbCrLf & "         </table></td></tr>"
     End If
     RS1.Close
     
     ' Listagem das tarefas na visualização da ação, rotina adquirida apartir da rotina exitente na Tarefas.asp para listagem das tarefas
     DB_GetLinkData RS1, RetornaCliente(), "ISTCAD"
     DB_GetSolicList_IS RS1, RS1("sq_menu"), RetornaUsuario(), "ISTCAD", 5, _
           null, null, null, null, null, null, _
           null, null, null, null, _
           null, null, null, null, null, null, null, _
           null, null, null, null, null, w_chave, null, null, null, null, w_ano
     RS1.sort = "ordem, fim, prioridade"
     If Not RS1.EOF Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Tarefas</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
        w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Nº</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Responsável</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Detalhamento</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Fim previsto</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Fase atual</font></td>"
        w_html = w_html & VbCrLf & "          </tr>"
        While Not RS1.EOF 
           If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
           w_html = w_html & VbCrLf & "       <tr valign=""top"" bgcolor=""" & w_cor & """>"
           w_html = w_html & VbCrLf & "       <tr bgcolor=""" & w_cor & """ valign=""top"">"
           w_html = w_html & VbCrLf & "         <td nowrap><font size=""1"">"
           If RS1("concluida") = "N" Then
              If RS1("fim") < Date() Then
                 w_html = w_html & VbCrLf & "          <img src=""" & conImgAtraso & """ border=0 width=15 heigth=15 align=""center"">"
              ElseIf RS1("aviso_prox_conc") = "S" and (RS1("aviso") <= Date()) Then
                 w_html = w_html & VbCrLf & "          <img src=""" & conImgAviso & """ border=0 width=15 height=15 align=""center"">"
              Else
                 w_html = w_html & VbCrLf & "          <img src=""" & conImgNormal & """ border=0 width=15 height=15 align=""center"">"
              End IF
           Else
              If RS1("fim") < Nvl(RS1("fim_real"),RS1("fim")) Then
                 w_html = w_html & VbCrLf & "          <img src=""" & conImgOkAtraso & """ border=0 width=15 heigth=15 align=""center"">"
              Else
                 w_html = w_html & VbCrLf & "          <img src=""" & conImgOkNormal & """ border=0 width=15 height=15 align=""center"">"
              End If
           End If
           w_html = w_html & VbCrLf & "         <A class=""HL"" HREF=""" & w_dir & "Tarefas.asp?par=Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS1("sq_siw_solicitacao") & "&w_tipo=&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste registro."" target=""blank"">" & RS1("sq_siw_solicitacao") & "&nbsp;</a>"
           w_html = w_html & VbCrLf & "         <td><font size=""1"">" & ExibePessoa("../", w_cliente, RS1("solicitante"), TP, RS1("nm_solic")) & "</td>"
           If Len(Nvl(RS1("assunto"),"-")) > 50 Then w_titulo = Mid(Nvl(RS1("assunto"),"-"),1,50) & "..." Else w_titulo = Nvl(RS1("assunto"),"-") End If
           If RS1("sg_tramite") = "CA" Then
              w_html = w_html & VbCrLf & "      <td><font size=""1""><strike>" & w_titulo & "</strike></td>"
           Else
              w_html = w_html & VbCrLf & "      <td><font size=""1"">" & w_titulo & "</td>"
           End If
           w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">&nbsp;" & Nvl(FormatDateTime(RS1("fim"),2),"-") & "</td>"
           w_html = w_html & VbCrLf & "         <td nowrap><font size=""1"">" & RS1("nm_tramite") & "</td>"
           RS1.MoveNext        
        wend
        w_html = w_html & VbCrLf & "         </table></td></tr>" 
     End If  
     RS1.Close

     ' Se for listagem, exibe os outros dados dependendo do tipo de visão  do usuário
     If O = "L" and w_tipo_visao <> 2 Then
        If RS("aviso_prox_conc") = "S" Then
           ' Configuração dos alertas de proximidade da data limite para conclusão da demanda
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Alerta</td>"
           w_html = w_html & VbCrLf & "      <tr><td align=""center""  colspan=""2"" height=""1"" bgcolor=""#000000""></td></tr>"
           w_html = w_html & VbCrLf & "      <tr><td><font size=1>Será enviado aviso a partir de <b>" & RS("dias_aviso") & "</b> dias antes de <b>" & FormataDataEdicao(RS("fim")) & "</b></font></td></tr>"
        End If
     End If
     
     ' Interessados na execução da ação
     DB_GetSolicInter RS, w_chave, null, "LISTA"
     RS.Sort = "nome_resumido"
     If Not Rs.EOF Then
        TP = RemoveTP(TP)&" - Interessados"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Interessados na execução</b></center></td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center""><font size=""1""><b><center><B><font size=1>Clique <a class=""HL"" HREF=""" & w_dir & "Acao.asp?par=interess&R=" & w_Pagina & par & "&O=L&w_chave=" & w_chave & "&P1=4&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ target=""blank"">aqui</a> para visualizar os Interessados na execução</font></b></center></td>"
     End If
     DesconectaBD   

     If O = "L" or O = "V" Then ' Se for listagem dos dados
        If w_tipo_visao <> 2 Then
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
     End If
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
     If Rs.EOF Then
        w_html = w_html & VbCrLf & "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados encaminhamentos.</b></td></tr>"
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
     w_html = w_html & VbCrLf & "         </table></td></tr>"
     w_html = w_html & VbCrLf & "</table>"
  End If
  End If
  VisualAcao = w_html

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

