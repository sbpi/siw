<%

REM =========================================================================
REM Rotina de visualização dos dados da ação de acordo com o Plano Gerencial
REM -------------------------------------------------------------------------
Function VisualAcaoGer(w_chave, P4)

  Dim Rsquery, w_Erro
  Dim w_Imagem, w_html
  Dim w_ImagemPadrao
  Dim RS1,RS2, RS3, RS4
  Dim w_p2, w_fases
  Dim w_titulo
  
  w_html = ""

  ' Recupera os dados da ação
  DB_GetSolicData_IS RS, w_chave, "ISACGERAL"
  
  w_html = w_html & VbCrLf & "<div align=center><center>"
  w_html = w_html & VbCrLf & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  
  w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
  w_html = w_html & VbCrLf & "      <tr valign=""top""><td colspan=""2""><font size=2>Ação: <b>" & RS("titulo") & "</b></font></td></tr>"
      
  ' Identificação da ação
  'w_html = w_html & VbCrLf & "      <tr valign=""top""><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Identificação</td>"
     
  ' Se a ação no PPA for informada, exibe.
  If Not IsNull(RS("cd_acao")) Then
     w_html = w_html & VbCrLf & "   <tr valign=""top"" bgcolor=""#D0D0D0""><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     w_html = w_html & VbCrLf & "      <tr bgcolor=""#D0D0D0""><td colspan=""2""><font size=""1"">Ação PPA:<br><b>" & RS("nm_ppa") & " </b></td>"     
     w_html = w_html & VbCrLf & "      <tr bgcolor=""#D0D0D0""><td colspan=""2""><font size=""1"">Código da ação:<br><b>" & RS("cd_unidade") & "." & RS("cd_acao") & "</b></td>"     
     w_html = w_html & VbCrLf & "      <tr bgcolor=""#D0D0D0""><td colspan=""2""><font size=""1"">Programa PPA:<br><b>" & RS("nm_ppa_pai") & "</b></td></tr>"
     w_html = w_html & VbCrLf & "      <tr bgcolor=""#D0D0D0""><td colspan=""2""><font size=""1"">Código programa:<br><b>" & RS("cd_programa") & " </b></td>"     
     w_html = w_html & VbCrLf & "   </table>"
  End If        
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><font size=""1"">Plano/Projeto Específico:<br><b>" & Nvl(RS("nm_pri"),"---")
  If Not IsNull(RS("cd_pri")) Then 
     w_html = w_html & VbCrLf & " (" & RS("cd_pri") & ")" 
  End If
  w_html = w_html & VbCrLf & "   <tr valign=""top""><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  If Not IsNull(RS("cd_acao")) Then
     DB_GetAcaoPPA_IS RS1, w_cliente, w_ano, RS("cd_ppa_pai"), RS("cd_acao"), null, RS("cd_unidade"), null, null, null
     w_html = w_html & VbCrLf & "     <tr valign=""top"">"
     w_html = w_html & VbCrLf & "       <td><font size=""1"">Tipo de ação:<br><b>" & RS1("nm_tipo_acao") & " </b></td>"       
     RS1.Close
  End If
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  w_html = w_html & VbCrLf & "        <tr><td valign=""top""><font size=""1"">Coordenador:<br><b>" & RS("resp_ppa") & " </b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<br><b>" & RS("fone_ppa") & " </b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Email:<br><b>" & RS("mail_ppa") & " </b></td>"
  w_html = w_html & VbCrLf & "          </table>"
  If Not IsNull(RS("resp_pri")) Then
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     w_html = w_html & VbCrLf & "        <tr><td valign=""top""><font size=""1"">Responsável plano/projeto específico:<br><b>" & RS("resp_pri") & " </b></td>"
     If Not IsNull(RS("fone_pri")) Then
        w_html = w_html & VbCrLf & "         <td><font size=""1"">Telefone:<br><b>" & RS("fone_pri") & " </b></td>"
     End If
     If Not IsNull(RS("mail_ppa_pai")) Then
        w_html = w_html & VbCrLf & "            <td><font size=""1"">Email:<br><b>" & RS("mail_pri") & " </b></td>"
     End If
     w_html = w_html & VbCrLf & "           </table>"
  End If
        
  ' Programação Qualitativa         
  'w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Programação Qualitativa</td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><div align=""justify""><font size=""1"">Justificativa:<br><b>" & Nvl(RS("problema"),"---")& "</b></div></td>"
  If Not IsNull(RS("cd_acao")) Then
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><div align=""justify""><font size=""1"">Objetivo:<br><b>" & Nvl(RS("finalidade"),"---")& "</b></div></td>"
  End if          
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
  End If
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><div align=""justify""><font size=""1"">Sistemática e estratégias a serem adotadas para o monitoramento da ação:<br><b>" & Nvl(RS("estrategia"),"---")& "</b></div></td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><div align=""justify""><font size=""1"">Sistemática e metodologias a serem adotadas para avaliação da ação:<br><b>" & Nvl(RS("sistematica"),"---")& "</b></div></td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><div align=""justify""><font size=""1"">Valor programado:<br><b>" & FormatNumber(cDbl(Nvl(RS("valor"),0)),2)& "</b></div></td>"
     
  ' Restricoes da ação
  ' Recupera todos os registros para a listagem
  DB_GetRestricao_IS RS1, "ISACRESTR", w_chave, null
  RS1.Sort = "inclusao desc"
  If Not RS1.EOF Then ' Se não foram selecionados registros, exibe mensagem   
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Restrições</td></tr>"
     w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
     w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     w_html = w_html & VbCrLf & "           <td><font size=""1""><b>Descrição</font></td>"
     w_html = w_html & VbCrLf & "           <td><font size=""1""><b>Tipo restrição</font></td>"
     w_html = w_html & VbCrLf & "           <td><font size=""1""><b>Providência</font></td>"
     w_html = w_html & VbCrLf & "          </tr>"
     While Not RS1.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        w_html = w_html & VbCrLf & "       <tr bgcolor=""" & w_cor & """ valign=""top"">"
        If P4 = 1 Then
           w_html = w_html & VbCrLf & "         <td><font size=""1"">" &RS1("descricao") & "</A></td>"
        Else
           w_html = w_html & VbCrLf & "         <td><font size=""1""><A class=""HL"" HREF=""#"" onClick=""window.open('Acao.asp?par=Restricao&O=V&w_chave=" & w_chave & "&w_chave_aux=" & RS1("sq_restricao") & "&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=ISACRESTR','Restricao','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" &RS1("descricao") & "</A></td>"
        End If
        w_html = w_html & VbCrLf & "         <td><font size=""1"">" & RS1("nm_tp_restricao")& "</td>"
        w_html = w_html & VbCrLf & "         <td><font size=""1"">" & Nvl(RS1("providencia"),"---")& "</td>"
        w_html = w_html & VbCrLf & "       </tr>"
        RS1.MoveNext
     wend
     w_html = w_html & VbCrLf & "        </table></td></tr>"  
  End If
  RS1.Close

  ' Metas da ação
  ' Recupera todos os registros para a listagem     
  DB_GetSolicMeta_IS RS1, w_chave, null, "LSTNULL"
  RS1.Sort = "ordem"
  If Not RS1.EOF Then ' Se não foram selecionados registros, exibe mensagem
    w_html = w_html & VbCrLf & "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Metas físicas</td></tr>"
     w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
     w_html = w_html & VbCrLf & "       <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     While Not RS1.EOF
        w_html = w_html & VbCrLf & "         <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "           <td rowspan=""2""><font size=""1""><b>Produto</font></td>"
        w_html = w_html & VbCrLf & "           <td rowspan=""2""><font size=""1""><b>Unidade medida</font></td>"
        w_html = w_html & VbCrLf & "           <td rowspan=""2""><font size=""1""><b>PPA</font></td>"
        w_html = w_html & VbCrLf & "           <td rowspan=""2""><font size=""1""><b>PNS</font></td>"
        w_html = w_html & VbCrLf & "           <td rowspan=""2""><font size=""1""><b>Cumulativa</font></td>"
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
        w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & RetornaSimNao(RS1("programada")) & "</font></td>"
        w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & Nvl(RS1("nm_cumulativa"),"---") & "</font></td>"
        w_html = w_html & VbCrLf & "          <td align=""right""><font size=""1"">" & cDbl(Nvl(RS1("quantidade"),0)) & "</font></td>"
        w_html = w_html & VbCrLf & "          <td align=""right""><font size=""1"">"&(cDbl(Nvl(RS1("quantidade"),0)) * cDbl(Nvl(RS1("perc_conclusao"),0)))/100&"</font></td>"
        w_html = w_html & VbCrLf & "          <td align=""right""><font size=""1"">"& cDbl(Nvl(RS1("perc_conclusao"),0))&"</font></td></tr>"
        w_html = w_html & VbCrLf & "      <tr><td colspan=""8""><font size=""1""><DD>Especifição do produto: <b>" & Nvl(RS1("descricao"),"---") & "</DD></font></td></tr>"
        RS1.MoveNext
     wend
     w_html = w_html & VbCrLf & "         </table></td></tr>"
  End If
  RS1.Close
     
  ' Listagem das tarefas na visualização da ação, rotina adquirida apartir da rotina exitente na Tarefas.asp para listagem das tarefas
  DB_GetLinkData RS1, RetornaCliente(), "ISTCAD"
  DB_GetSolicList_IS RS1, RS1("sq_menu"), RetornaUsuario(), "ISTCAD", 5, _
    null, null, null, null, null, null, _
    null, null, null, null, _
    null, null, null, null, null, null, null, _
    null, null, null, null, null, w_chave, null, null, null, null
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

  w_html = w_html & VbCrLf & "         </table></td></tr>"

  
  VisualAcaoGer = w_html

  Set w_p2                  = Nothing 
  Set w_fases               = Nothing 
  Set RS2                   = Nothing 
  Set RS3                   = Nothing 
  Set RS4                   = Nothing 

  Set w_erro                = Nothing 
  Set Rsquery               = Nothing
  Set w_ImagemPadrao        = Nothing
  Set w_Imagem              = Nothing

End Function
REM =========================================================================
REM Fim da visualização dos dados do cliente
REM -------------------------------------------------------------------------

%>

