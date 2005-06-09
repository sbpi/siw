<%

REM =========================================================================
REM Rotina de visualiza��o dos dados da tarefa
REM -------------------------------------------------------------------------
Function VisualDemanda(w_chave, O, w_usuario)

  Dim Rsquery, w_Erro
  Dim w_Imagem, w_html
  Dim w_ImagemPadrao
  Dim w_tipo_visao
  
  w_html = ""

  ' Recupera os dados da tarefa
  DB_GetSolicData RS, w_chave, "GDGERAL"

  ' O c�digo abaixo foi comentado em 23/11/2004, devido � mudan�a na regra definida pelo usu�rio,
  ' que agora permite vis�o geral para todos os usu�rios
  
  ' Recupera o tipo de vis�o do usu�rio
  'If cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario) or _
  '   cDbl(Nvl(RS("executor"),0))    = cDbl(w_usuario) or _
  '   cDbl(Nvl(RS("cadastrador"),0)) = cDbl(w_usuario) or _
  '   cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _
  '   cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) or _
  '   cDbl(Nvl(RS("tit_exec"),0))    = cDbl(w_usuario) or _
  '   cDbl(Nvl(RS("subst_exec"),0))  = cDbl(w_usuario) Then
  '   ' Se for solicitante, executor ou cadastrador, tem vis�o completa
  '   w_tipo_visao = 0
  'Else
  '   DB_GetSolicInter Rsquery, w_chave, w_usuario, "REGISTRO"
  '   If Not RSquery.EOF Then
  '      ' Se for interessado, verifica a vis�o cadastrada para ele.
  '      w_tipo_visao = cDbl(RSquery("tipo_visao"))
  '   Else
  '      DB_GetSolicAreas Rsquery, w_chave, Session("sq_lotacao"), "REGISTRO"
  '      If Not RSquery.EOF Then
  '         ' Se for de uma das unidades envolvidas, tem vis�o parcial
  '         w_tipo_visao = 1
  '      Else
  '         ' Caso contr�rio, tem vis�o resumida
  '         w_tipo_visao = 2
  '      End If
  '   End If
  'End If
  
  w_tipo_visao = 0
   
  ' Se for listagem ou envio, exibe os dados de identifica��o da tarefa
  If O = "L" or O = "V" Then ' Se for listagem dos dados
     w_html = w_html & VbCrLf & "<div align=center><center>"
     w_html = w_html & VbCrLf & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
     w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"

     w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
     If Not IsNull(RS("nm_projeto")) Then
        ' Recupera os dados da a��o
        DB_GetSolicData RS1, RS("sq_solic_pai"), "PJGERAL"

        ' Se a a��o no PPA for informada, exibe.
        If Not IsNull(RS1("sq_acao_ppa")) Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Programa PPA:<b>" & RS1("nm_ppa_pai") & " (" & RS1("cd_ppa_pai") & ")" & " </b></td>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">A��o PPA:<b>" & RS1("nm_ppa") & " (" & RS1("cd_ppa") & ")" & " </b></td>"
        End If
        ' Se a iniciativa priorit�ria for informada, exibe.
        If Not IsNull(RS1("sq_orprioridade")) Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Iniciativa priorit�ria:<b>" & RS1("nm_pri")
           If Not IsNull(RS1("cd_pri")) Then w_html = w_html & VbCrLf & " (" & RS1("cd_pri") & ")" End If
           w_html = w_html & VbCrLf & "          </b></td>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">A��o: <b>" & RS("nm_projeto") & "</b></td>"
        End If

     End If
     'If Not IsNull(RS("nm_etapa")) Then
     '   w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">: <b>" & MontaOrdemEtapa(RS("sq_projeto_etapa")) & ". " & RS("nm_etapa") & " </b></td>"
     'End If
     
     w_html = w_html & VbCrLf & "      <tr><td><font size=1>Detalhamento: <b>" & CRLF2BR(RS("assunto")) & " (" & w_chave & ") </b></font></td></tr>"
      
      ' Identifica��o da tarefa
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Identifica��o</td>"
     ' Se a classifica��o foi informada, exibe.
     If Not IsNull(RS("sq_cc")) Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Classifica��o:<br><b>" & RS("cc_nome") & " </b></td>"
     End If
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Respons�vel:<br><b>" & ExibePessoa("../", w_cliente, RS("solicitante"), TP, RS("nm_sol")) & "</A></b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Unidade respons�vel:<br><b>" & RS("nm_unidade_resp") & " </b></td>"
     If w_tipo_visao = 0 Then ' Se for vis�o completa
        w_html = w_html & VbCrLf & "          <td valign=""top""><font size=""1"">Recurso programado:<br><b>" & FormatNumber(RS("valor"),2) & " </b></td>"
     End If
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">In�cio previsto:<br><b>" & FormataDataEdicao(RS("inicio")) & " </b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Fim previsto:<br><b>" & FormataDataEdicao(RS("fim")) & " </b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Prioridade:<br><b>" & RetornaPrioridade(RS("prioridade")) & " </b></td>"
     w_html = w_html & VbCrLf & "          <tr>"
     w_html = w_html & VbCrLf & "          <td colspan=2><font size=""1"">Respons�vel:<br><b>" & Nvl(RS("palavra_chave"),"---") & " </b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Ordem:<br><b>" & RS("ordem") & " </b></td>"
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <td colspan=3><font size=""1"">Parcerias externas:<br><b>" & Nvl(RS("proponente"),"---") & " </b></td>"
     'w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     'w_html = w_html & VbCrLf & "          <td colspan=3><font size=""1"">Abrang�ncia da a��o:(Quando Bras�lia-DF, impacto nacional. Quando a capital de um estado, impacto estadual.):<br><b>" & RS("nm_cidade") & " (" & RS("co_uf") & ")</b></td>"
     w_html = w_html & VbCrLf & "          </table>"

     If w_tipo_visao = 0 or w_tipo_visao = 1 Then
        ' Informa��es adicionais
        If Nvl(RS("descricao"),"") > "" or Nvl(RS("justificativa"),"") > "" Then 
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Informa��es adicionais</td>"
           If Nvl(RS("descricao"),"") > "" Then w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Resultados espearados:<br><b>" & CRLF2BR(RS("descricao")) & " </b></td>" End If
           If w_tipo_visao = 0 and Nvl(RS("justificativa"),"") > "" Then ' Se for vis�o completa
              w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Observa��es:<br><b>" & CRLF2BR(RS("justificativa")) & " </b></td>"
           End If
        End If
     End If

     ' Dados da conclus�o da tarefa, se ela estiver nessa situa��o
     If RS("concluida") = "S" and Nvl(RS("data_conclusao"),"") > "" Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Dados da conclus�o</td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
        w_html = w_html & VbCrLf & "          <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">In�cio da execu��o:<br><b>" & FormataDataEdicao(RS("inicio_real")) & " </b></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">T�rmino da execu��o:<br><b>" & FormataDataEdicao(RS("fim_real")) & " </b></td>"
        If w_tipo_visao = 0 Then
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Rercuso executado:<br><b>" & FormatNumber(RS("custo_real"),2) & " </b></td>"
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
        ' Configura��o dos alertas de proximidade da data limite para conclus�o da tarefa
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Alerta</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
        w_html = w_html & VbCrLf & "      <tr><td><font size=1>Ser� enviado aviso a partir de <b>" & RS("dias_aviso") & "</b> dias antes de <b>" & FormataDataEdicao(RS("fim")) & "</b></font></td></tr>"
        'w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
        'w_html = w_html & VbCrLf & "          <td valign=""top""><font size=""1"">Emite aviso:<br><b>" & Replace(Replace(RS("aviso_prox_conc"),"S","Sim"),"N","N�o") & " </b></td>"
        'w_html = w_html & VbCrLf & "          <td valign=""top""><font size=""1"">Dias:<br><b>" & RS("dias_aviso") & " </b></td>"
        'w_html = w_html & VbCrLf & "          </table>"
     End If

     ' Interessados na execu��o da tarefa
     DB_GetSolicInter RS, w_chave, null, "LISTA"
     RS.Sort = "nome_resumido"
     If Not Rs.EOF Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Interessados na execu��o</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
        w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Nome</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Tipo de vis�o</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Envia e-mail</font></td>"
        w_html = w_html & VbCrLf & "          </tr>"    
        w_cor = conTrBgColor
        While Not Rs.EOF
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS("nome_resumido") & "</td>"
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RetornaTipoVisao(RS("tipo_visao")) & "</td>"
          w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & Replace(Replace(RS("envia_email"),"S","Sim"),"N","N�o") & "</td>"
          w_html = w_html & VbCrLf & "      </tr>"
          Rs.MoveNext
        wend
        w_html = w_html & VbCrLf & "         </table></td></tr>"
     End If
     DesconectaBD

     ' �reas envolvidas na execu��o da tarefa
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
           w_html = w_html & VbCrLf & "          <td><font size=""1""><b>T�tulo</font></td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Descri��o</font></td>"
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
             w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">Anota��o</td>"
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
  
  VisualDemanda = w_html

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

