<%

REM =========================================================================
REM Rotina de visualização dos dados da tarefa
REM -------------------------------------------------------------------------
Function VisualTarefa(w_chave, O, w_usuario)

  Dim Rsquery, w_Erro
  Dim w_Imagem, w_html
  Dim w_ImagemPadrao
  Dim w_tipo_visao
  
  w_html = ""

  ' Recupera os dados da tarefa
  DB_GetSolicData_IS RS, w_chave, "ISTAGERAL"

  w_tipo_visao = 0
   
  ' Se for listagem ou envio, exibe os dados de identificação da tarefa
  If O = "L" or O = "V" Then ' Se for listagem dos dados
     w_html = w_html & VbCrLf & "<div align=center><center>"
     w_html = w_html & VbCrLf & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
     w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"

     w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
     If Not IsNull(RS("nm_projeto")) Then
        ' Recupera os dados da ação
        DB_GetSolicData_IS RS1, RS("sq_solic_pai"), "ISACGERAL"

        ' Se a ação no PPA for informada, exibe.
        If Not IsNull(RS1("cd_acao")) Then
           w_html = w_html & VbCrLf & "   <tr valign=""top""><td colspan=""3""><table border=0 width=""100%"" cellspacing=0>"
           w_html = w_html & VbCrLf & "      <tr bgcolor=""#D0D0D0""><td colspan=""3""><font size=""1"">Unidade:<br><b>" & RS1("cd_unidade") & " - " & RS1("ds_unidade") & " </b></td></tr>"
           w_html = w_html & VbCrLf & "      <tr bgcolor=""#D0D0D0""><td colspan=""3""><font size=""1"">Programa PPA:<br><b>" & RS1("cd_ppa_pai") & " - " & RS1("nm_ppa_pai") & "</b></td></tr>"
           w_html = w_html & VbCrLf & "      <tr bgcolor=""#D0D0D0""><td colspan=""3""><font size=""1"">Ação PPA:<br><b>" & RS1("cd_acao") & " - " & RS1("nm_ppa") & " </b></td>"
           w_html = w_html & VbCrLf & "   </table></td></tr>"
        End If
        ' Se o plano/projeto específico for informado, exibe.
        If Not IsNull(RS1("sq_isprojeto")) Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Plano/Projeto Específico:<b>" & RS1("nm_pri")
           If Not IsNull(RS1("cd_pri")) Then w_html = w_html & VbCrLf & " (" & RS1("cd_pri") & ")" End If
           w_html = w_html & VbCrLf & "          </b></td>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Ação: <b>" & RS("nm_projeto") & "</b></td>"
        End If

     End If
     w_html = w_html & VbCrLf & "      <tr><td><font size=1>Tarefa: <b>" & CRLF2BR(Nvl(RS("titulo"),"---")) & " </b></font></td></tr>"
     w_html = w_html & VbCrLf & "      <tr><td><div align=""justify""><font size=1>Descrição: <b>" & CRLF2BR(RS("assunto")) & " (" & w_chave & ")</b></font></div></td></tr>"
      
      ' Identificação da tarefa
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Identificação</td>"

     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Responsável SISPLAM:<br><b>" & ExibePessoa("../", w_cliente, RS("solicitante"), TP, RS("nm_sol")) & "</A></b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Área planejamento:<br><b>" & ExibeUnidade("../", w_cliente, RS("nm_unidade_resp"), RS("sq_unidade_resp"), TP) & "</b></td>"
     If w_tipo_visao = 0 Then ' Se for visão completa
        w_html = w_html & VbCrLf & "          <td valign=""top""><font size=""1"">Recurso programado:<br><b>" & FormatNumber(RS("valor"),2) & " </b></td>"
     End If
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Início previsto:<br><b>" & FormataDataEdicao(RS("inicio")) & " </b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Fim previsto:<br><b>" & FormataDataEdicao(RS("fim")) & " </b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Prioridade:<br><b>" & RetornaPrioridade(RS("prioridade")) & " </b></td>"
     'w_html = w_html & VbCrLf & "          <tr>"
     'w_html = w_html & VbCrLf & "          <td colspan=2><font size=""1"">Responsável:<br><b>" & Nvl(RS("palavra_chave"),"---") & " </b></td>"
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <td colspan=3><font size=""1"">Parcerias externas:<br><b>" & Nvl(RS("proponente"),"---") & " </b></td>"
     w_html = w_html & VbCrLf & "          </table>"
     
     If w_tipo_visao = 0 or w_tipo_visao = 1 Then
        ' Informações adicionais
        If Nvl(RS("descricao"),"") > "" or Nvl(RS("justificativa"),"") > "" Then 
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Informações adicionais</td>"
           If Nvl(RS("descricao"),"") > "" Then w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Resultados esperados:<br><b>" & CRLF2BR(RS("descricao")) & " </b></div></td>" End If
           If w_tipo_visao = 0 and Nvl(RS("justificativa"),"") > "" Then ' Se for visão completa
              w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Observações:<br><b>" & CRLF2BR(RS("justificativa")) & " </b></div></td>"
           End If
        End If
     End If

     ' Dados da conclusão da tarefa, se ela estiver nessa situação
     If RS("concluida") = "S" and Nvl(RS("data_conclusao"),"") > "" Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Dados da conclusão</td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
        w_html = w_html & VbCrLf & "          <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Início da execução:<br><b>" & FormataDataEdicao(RS("inicio_real")) & " </b></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Término da execução:<br><b>" & FormataDataEdicao(RS("fim_real")) & " </b></td>"
        If w_tipo_visao = 0 Then
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Rercuso executado:<br><b>" & FormatNumber(RS("custo_real"),2) & " </b></td>"
        End If
        w_html = w_html & VbCrLf & "          </table>"
        If w_tipo_visao = 0 Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Nota de conclusão:<br><b>" & CRLF2BR(RS("nota_conclusao")) & " </b></td>"
        End If
     End If
  End If
  If RS("nm_responsavel") > "" Then
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Responsável</td>"  
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     If Not IsNull(RS("nm_responsavel")) Then           
        w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Responsável pela tarefa:<br><b>" & RS("nm_responsavel") & " </b></td>"
        If Not IsNull(RS("fn_responsavel")) Then
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<br><b>" & RS("fn_responsavel") & " </b></td>"
        End If
        If Not IsNull(RS("em_responsavel")) Then
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Email:<br><b><A class=""HL"" HREF=""mailto:" & RS("em_responsavel") & """>" & RS("em_responsavel") & "</a></b></td>"
        End If
     End If
     w_html = w_html & VbCrLf & "          </table>"
  End If
  ' Se for listagem, exibe os outros dados dependendo do tipo de visão  do usuário
  If O = "L" and w_tipo_visao <> 2 Then
     If RS("aviso_prox_conc") = "S" Then
        ' Configuração dos alertas de proximidade da data limite para conclusão da tarefa
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Alerta</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
        w_html = w_html & VbCrLf & "      <tr><td><font size=1>Será enviado aviso a partir de <b>" & RS("dias_aviso") & "</b> dias antes de <b>" & FormataDataEdicao(RS("fim")) & "</b></font></td></tr>"
     End If
  End If

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
             w_html = w_html & VbCrLf & "        <td><font size=""1""><a class=""HL"" href=""" & conFileVirtual & w_cliente & "/" & RS("caminho") & """ target=""_blank"" title=""Clique para exibir o arquivo em outra janela."">" & RS("nome") & "</a></td>"
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

     RS.Sort = "data desc, sq_siw_solic_log desc"
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
        w_cor = conTrBgColor
        While Not Rs.EOF
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
          w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & FormatDateTime(RS("data"),2) & ", " & FormatDateTime(RS("data"),4)& "</td>"
          If Nvl(RS("caminho"),"") > "" Then
             w_html = w_html & VbCrLf & "        <td><font size=""1"">" & CRLF2BR(Nvl(RS("despacho"),"---") & "<br>[<a class=""HL"" href=""" & conFileVirtual & w_cliente & "/" & RS("caminho") & """ target=""_blank"" title=""Clique para exibir o anexo em outra janela."">Anexo - " & RS("tipo") & " - " & Round(cDbl(RS("tamanho"))/1024,1) & " KB</a>]") & "</td>"
          Else
             w_html = w_html & VbCrLf & "        <td><font size=""1"">" & CRLF2BR(Nvl(RS("despacho"),"---")) & "</td>"
          End If
          w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & ExibePessoa("../", w_cliente, RS("sq_pessoa"), TP, RS("responsavel")) & "</td>"
          If (Not IsNull(Tvl(RS("sq_demanda_log")))) and (Not IsNull(Tvl(RS("destinatario")))) Then
             w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & Nvl(RS("destinatario"),"---") & "</td>"
          ElseIf (Not IsNull(Tvl(RS("sq_demanda_log")))) and IsNull(Tvl(RS("destinatario"))) Then
             w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">Anotação</td>"
          Else
             w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & Nvl(RS("tramite"),"---") & "</td>"
          End If
          w_html = w_html & VbCrLf & "      </tr>"
          Rs.MoveNext
        wend
     End If
     DesconectaBD
     w_html = w_html & VbCrLf & "         </table></td></tr>"

     w_html = w_html & VbCrLf & "</table>"
  End If
  
  VisualTarefa = w_html

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

