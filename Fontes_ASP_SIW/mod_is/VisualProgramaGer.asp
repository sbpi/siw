<%

REM =========================================================================
REM Rotina de visualização dos dados do programa no relatório de plano gerencial
REM -------------------------------------------------------------------------
Function VisualProgramaGer(w_chave, P4)

  Dim Rsquery, w_Erro
  Dim w_Imagem, w_html
  Dim w_ImagemPadrao
  Dim RS1,RS2, RS3, RS4
  Dim w_p2, w_fases
  Dim w_titulo, w_ppa, w_cd_programa
  
  w_html = ""
  ' Recupera os dados do programa
  DB_GetSolicData_IS RS, w_chave, "ISPRGERAL"

  w_html = w_html & VbCrLf & "<div align=center><center>"
  w_html = w_html & VbCrLf & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"

  w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
  w_html = w_html & VbCrLf & "      <tr><td><font size=2>Programa: <b>" & RS("titulo") & "</b></font></td></tr>"
     
  ' Identificação da programa
  'w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Identificação</td>"  
        
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><font size=""1"">Código do programa:<br><b>" & RS("cd_programa") & " </b></td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  If P4 = 1 Then
     w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Unidade Administrativa:<br><b>" & RS("nm_unidade_adm") & " </b></td>"
  Else
     w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Unidade Administrativa:<br><b>" & ExibeUnidade("../", w_cliente, RS("nm_unidade_adm"), RS("sq_unidade_adm"), TP) & "</b></td>"
  End If
  w_html = w_html & VbCrLf & "        <tr valign=""top"">"
  w_html = w_html & VbCrLf & "        <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Natureza:<br><b>" & RS("nm_natureza") & " </b></td>"
  w_html = w_html & VbCrLf & "        <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Endereço na internet:<br><b>" & Nvl(RS("ln_programa"),"---") & "</b></td>"
  w_html = w_html & VbCrLf & "        </table>"
        
  ' Responsaveis
  'w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Responsáveis</td>"  
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Gerente do programa:<br><b>" & Nvl(RS("nm_gerente_programa"),"---") & " </b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<br><b>" & Nvl(RS("fn_gerente_programa"),"---") & " </b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Email:<br><b>" & Nvl(RS("em_gerente_programa"),"---") & " </b></td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Gerente executivo do programa:<br><b>" & RS("nm_gerente_executivo") & " </b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<br><b>" & RS("fn_gerente_executivo") & " </b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Email:<br><b>" & RS("em_gerente_executivo") & " </b></td>"
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
  
  ' Programação Qualitativa
  'w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Programação Qualitativa</td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"" color=""red"">Diretrizes e desafios do governo associados ao programa:<br><b>Falta definir qual o campo deve ser visualizado</b></div></td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"" color=""red"">Objetivo setorial associado:<br><b>Falta definir qual o campo deve ser visualizado</b></div></td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Explique como o programa contribui para que o objetivo setorial seja alcançado:<br><b>" & Nvl(RS("contribuicao_objetivo"),"---")& "</b></div></td>"           
  w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Diretrizes do Plano Nacional de Políticas de Integração Racial:<br><b>" & Nvl(RS("diretriz"),"---")& "</b></div></td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Justificativa:<br><b>" & Nvl(RS("justificativa_sigplan"),"---")& "</b></div></td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Objetivo:<br><b>" & Nvl(RS("objetivo"),"---")& "</b></div></td>"           
  w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Público-alvo:<br><b>" & Nvl(RS("publico_alvo"),"---")& "</b></div></td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Resultados esperados:<br><b>" & Nvl(RS("descricao"),"---")& "</b></div></td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Parcerias internas:<br><b>" & Nvl(RS("palavra_chave"),"---")& "</b></div></td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Parcerias externas:<br><b>" & Nvl(RS("proponente"),"---")& "</b></div></td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Potencialidades:<br><b>" & Nvl(RS("potencialidades"),"---")& "</b></div></td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Estratégia implementação:<br><b>" & Nvl(RS("estrategia"),"---")& "</b></div></td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Sistemática e estratégias a serem adotadas para o monitoramento programa:<br><b>" & Nvl(RS("estrategia_monit"),"---")& "</b></div></td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Sistemática e metodologias a serem adotadas para a avaliação do programa:<br><b>" & Nvl(RS("metodologia_aval"),"---")& "</b></div></td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Observações:<br><b>" & Nvl(RS("justificativa"),"---")& "</b></div></td>"
  
  ' Restricoes do programa
  ' Recupera todos os registros para a listagem
  DB_GetRestricao_IS RS1, "ISPRRESTR", w_chave, null
  RS1.Sort = "inclusao desc"
        
  If Not RS1.EOF Then ' Se não foram selecionados registros, exibe mensagem   
     'w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Restrições</td>"
     w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
     w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     w_html = w_html & VbCrLf & "<td><font size=""1""><b>Descrição</font></td>"
     w_html = w_html & VbCrLf & "<td><font size=""1""><b>Tipo restrição</font></td>"
     w_html = w_html & VbCrLf & "<td><font size=""1""><b>Providência</font></td>"
     w_html = w_html & VbCrLf & "          </tr>"
     While Not RS1.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        w_html = w_html & VbCrLf & "        <tr bgcolor=""" & w_cor & """ valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><A class=""HL"" HREF=""#"" onClick=""window.open('Programa.asp?par=Restricao&O=V&w_chave=" & w_chave & "&w_chave_aux=" & RS1("sq_restricao") & "&w_cd_programa=" & RS("cd_programa") & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=ISPRRESTR','Restricao','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" &RS1("descricao") & "</A></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">" & RS1("nm_tp_restricao")& "</td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">" & Nvl(RS1("providencia"),"---")& "</td>"
        w_html = w_html & VbCrLf & "        </tr>"
        RS1.MoveNext
     wend
     w_html = w_html & VbCrLf & "         </table></td></tr>"  
  End If
  RS1.Close

  ' Indicadores do programa
  ' Recupera todos os registros para a listagem
  DB_GetSolicIndic_IS RS1, w_chave, null, "LISTA"
  RS1.Sort = "ordem"
        
  If Not RS1.EOF Then ' Se não foram selecionados registros, exibe mensagem   
     'w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Indicadores</td>"
     w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
     w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     w_html = w_html & VbCrLf & "             <td><font size=""1""><b>Indicador</font></td>"
     w_html = w_html & VbCrLf & "             <td><font size=""1""><b>PPA</font></td>"
     w_html = w_html & VbCrLf & "             <td><font size=""1""><b>Unidade de medida</font></td>"
     w_html = w_html & VbCrLf & "             <td><font size=""1""><b>Tipo de indicador</font></td>"
     w_html = w_html & VbCrLf & "             <td><font size=""1""><b>Índice de referência</font></td>"
     w_html = w_html & VbCrLf & "             <td><font size=""1""><b>Data de apuração</font></td>"
     w_html = w_html & VbCrLf & "             <td><font size=""1""><b>Índice programado</font></td>"
     w_html = w_html & VbCrLf & "          </tr>"
     While Not RS1.EOF
        If RS1("cd_indicador") > "" Then
           w_ppa = "Sim"
        Else
           w_ppa = "Não"
        End If
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        w_html = w_html & VbCrLf & "        <tr bgcolor=""" & w_cor & """ valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><A class=""HL"" HREF=""#"" onClick=""window.open('Programa.asp?par=AtualizaIndicador&O=V&w_chave=" & w_chave & "&w_chave_aux=" & RS1("sq_indicador") & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Indicador','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" &RS1("titulo") & "</A></td>"
        w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & w_ppa & "</td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">" & Nvl(RS1("nm_unidade_medida"),"---")& "</td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">" & Nvl(RS1("nm_tipo"),"---")& "</td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_referencia"),0)),2)& "</td>"
        w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS1("apuracao_referencia")),"---")& "</td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">" & FormatNumber(Nvl(RS1("quantidade"),0),2)& "</td>"
        w_html = w_html & VbCrLf & "        </tr>"
        w_html = w_html & VbCrLf & "      <tr><td colspan=""7""><font size=""1""><DD>Usos: <b>" & Nvl(RS1("usos"),"---") & "</DD></font></td></tr>"
        w_html = w_html & VbCrLf & "      <tr><td colspan=""7""><font size=""1""><DD>Limitações: <b>" & Nvl(RS1("limitacoes"),"---") & "</DD></font></td></tr>"
        w_html = w_html & VbCrLf & "      <tr><td colspan=""7""><font size=""1""><DD>Fórmula de cálculo: <b>" & Nvl(RS1("formula"),"---") & "</DD></font></td></tr>"
        RS1.MoveNext
     wend
     w_html = w_html & VbCrLf & "         </table></td></tr>"  
  End If
  RS1.Close
        
  DB_GetAcaoPPA_IS RS1, w_cliente, w_ano, RS("cd_programa"), null, null, null, null, null, null
  RS1.Sort = "chave"    
     
  If Not RS1.EOF Then ' Se não foram selecionados registros, exibe mensagem   
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Ações</td>"
     w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""3"">"
     w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Cod.</font></td>"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Ação</font></td>"
     w_html = w_html & VbCrLf & "          </tr>"
     While Not RS1.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        w_html = w_html & VbCrLf & "        <tr bgcolor=""" & w_cor & """ valign=""top"">"
        If Nvl(RS1("sq_siw_solicitacao"),"") > "" and P4 <> 1 Then
           'w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & RS1("cd_acao")& "." & RS1("cd_unidade")& "</td>"
           w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1""><A class=""HL"" HREF=""" & w_dir & "Acao.asp?par=" & "Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS1("sq_siw_solicitacao") & "&w_tipo=Volta&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste registro."">" & RS1("cd_acao")& "." & RS1("cd_unidade")& "</a></td>"
        Else
           w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & RS1("cd_acao")& "." & RS1("cd_unidade")& "</td>"
        End If
        w_html = w_html & VbCrLf & "          <td><font size=""1"">" & RS1("descricao_acao") & "</td>"
        w_html = w_html & VbCrLf & "        </tr>"
        RS1.MoveNext
     wend
     w_html = w_html & VbCrLf &  "      </center>"
     w_html = w_html & VbCrLf & "         </table></td></tr>"  
  End If
  RS1.Close
  DesconectaBD
  w_html = w_html & VbCrLf & "         </table></td></tr>"
  
  VisualProgramaGer = w_html

  Set w_p2                  = Nothing 
  Set w_fases               = Nothing 
  Set RS2                   = Nothing 
  Set RS3                   = Nothing 
  Set RS4                   = Nothing 

  Set w_erro                = Nothing 
  Set Rsquery               = Nothing
  Set w_ImagemPadrao        = Nothing
  Set w_Imagem              = Nothing
  
  Set w_ppa                 = Nothing
  Set w_cd_programa         = Nothing

End Function
REM =========================================================================
REM Fim da visualização dos dados do cliente
REM -------------------------------------------------------------------------

%>

