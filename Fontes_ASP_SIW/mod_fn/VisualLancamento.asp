<%
REM =========================================================================
REM Rotina de visualiza��o dos dados do lan�amento
REM -------------------------------------------------------------------------
Function VisualLancamento(w_chave, O, w_usuario, P1, P4)

  Dim Rsquery, w_Erro
  Dim w_Imagem, w_html, w_TrBgColor
  Dim w_ImagemPadrao, w_tramite
  Dim w_tipo_visao, w_SG
  Dim w_valor
  Dim w_vl_total, w_vl_retencao, w_vl_normal
  Dim w_al_total, w_al_retencao, w_al_normal
  
  If P4 = 1 Then w_TrBgColor = "" Else w_TrBgColor = conTrBgColor End If

  w_html = ""

  ' Recupera os dados do lan�amento
  DB_GetSolicData RS, w_chave, Mid(SG,1,3) & "GERAL"
  w_tramite = RS("sq_siw_tramite")
  w_SG      = RS("sigla")

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
     If SolicAcesso(w_chave, w_usuario) > 2 Then w_tipo_visao = 1 End If
  End If
  
  ' Se for listagem ou envio, exibe os dados de identifica��o do lan�amento
  If O = "L" or O = "V" Then ' Se for listagem dos dados
     w_html = w_html & VbCrLf & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
     w_html = w_html & VbCrLf & "<tr bgcolor=""" & w_TrBgColor & """><td>"

     w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
     w_html = w_html & VbCrLf & "      <tr><td><font size=""2""><b>" & uCase(RS("nome")) & " " & RS("codigo_interno") & " (" & w_chave & ")</b></td>"

     w_html = w_html & VbCrLf & "      <tr><td align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Identifica��o</td>"
      ' Identifica��o do lan�amento
     If Nvl(RS("cd_acordo"),"") > "" Then
        If Not (P1 = 4 or P4 = 1) Then
           w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Contrato: <b><A class=""hl"" HREF=""" & "mod_ac/Contratos.asp?par=Visual&O=L&w_chave=" & RS("sq_solic_pai") & "&w_tipo=&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=GC" & Mid(SG,3,1) & "CAD"" title=""Exibe as informa��es do contrato."" target=""Contrato"">" & RS("cd_acordo") & " (" & RS("sq_solic_pai") & ")</a> </b></td>"
        Else
           w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Contrato: <b>" & RS("cd_acordo") & " (" & RS("sq_solic_pai") & ") </b></td>"
        End If
     End If
     If Not IsNull(RS("nm_projeto")) Then
        If Not (P1 = 4 or P4 = 1) Then
           w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Projeto: <b><A class=""hl"" HREF=""" & "Projeto.asp?par=Visual&O=L&w_chave=" & RS("sq_solic_pai") & "&w_tipo=Volta&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exibe as informa��es do projeto."" target=""Projeto"">" & RS("nm_projeto") & "</a></b></td>"
        Else
           w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Projeto: <b>" & RS("nm_projeto") & "  (" & RS("sq_solic_pai") & ")</b></td>"
        End If
     End If
     ' Se a classifica��o foi informada, exibe.
     If Not IsNull(RS("sq_cc")) Then
        w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Classifica��o: <b>" & RS("nm_cc") & " </b>"
     End If
     w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Tipo de lan�amento: <b>" & RS("nm_tipo_lancamento") & " </b></td>"
     w_html = w_html & VbCrLf & "      <tr><td><font size=1>Finalidade: <b>" & CRLF2BR(RS("descricao")) & "</b></font></td></tr>"
     If Not (P1 = 4 or P4 = 1) Then
        w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Unidade respons�vel: <b>" & ExibeUnidade(w_dir_volta, w_cliente, RS("nm_unidade_resp"), RS("sq_unidade"), TP) & "</b></td>"
     Else
        w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Unidade respons�vel: <b>" & RS("nm_unidade_resp") & "</b></td>"
     End If
     w_html = w_html & VbCrLf & "      <tr><td><table border=0 width=""100%"" cellspacing=0>"
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Forma de pagamento:<br><b>" & FormataDataEdicao(RS("nm_forma_pagamento")) & " </b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Vencimento:<br><b>" & FormataDataEdicao(RS("vencimento")) & " </b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Valor:<br><b>" & FormatNumber(Nvl(RS("valor"),0),2) & " </b></td>"
     w_html = w_html & VbCrLf & "          </table>"

     ' Dados da conclus�o do projeto, se ela estiver nessa situa��o
     If Nvl(RS("conclusao"),"") > "" and Nvl(RS("quitacao"),"") > "" Then
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Dados da liquida��o</td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
        w_html = w_html & VbCrLf & "          <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Data:<br><b>" & FormataDataEdicao(RS("quitacao")) & " </b></td>"
        If Nvl(RS("codigo_deposito"),"") > "" Then
           w_html = w_html & VbCrLf & "          <td><font size=""1"">C�digo do dep�sito:<br><b>" & RS("codigo_deposito") & " </b></td>"
        End If
        w_html = w_html & VbCrLf & "          </table>"
        w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Observa��o:<br><b>" & CRLF2BR(Nvl(RS("observacao"),"---")) & " </b></td>"
     End If
     
     ' Outra parte
     DB_GetBenef RSQuery, w_cliente, Nvl(RS("pessoa"),0), null, null, null, Nvl(RS("sq_tipo_pessoa"),0), null, null
     w_html = w_html & VbCrLf & "      <tr><td align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Outra parte</td>"
     If RSQuery.EOF Then
        w_html = w_html & VbCrLf & "      <tr><td colspan=2><font size=2><b>Outra parte n�o informada"
     Else
        w_html = w_html & VbCrLf & "      <tr><td colspan=2><font size=2><b>"
        w_html = w_html & VbCrLf & "          " & RSQuery("nm_pessoa") & " (" & RSQuery("nome_resumido") & ")"
        If cDbl(Nvl(RS("sq_tipo_pessoa"),0)) = 1 Then
           w_html = w_html & VbCrLf & "          - " & RSQuery("cpf")
        Else
           w_html = w_html & VbCrLf & "          - " & RSQuery("cnpj")
        End IF
        If cDbl(RS("sq_tipo_pessoa")) = 1 Then
           w_html = w_html & VbCrLf & "      <tr><td><table border=0 width=""100%"" cellspacing=0>"
           w_html = w_html & VbCrLf & "          <tr valign=""top"">"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Sexo:<b><br>" & RSQuery("nm_sexo") & "</td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Data de nascimento:<b><br>" & FormataDataEdicao(RSQuery("nascimento")) & "</td>"
           w_html = w_html & VbCrLf & "          <tr valign=""top"">"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Identidade:<b><br>" & RSQuery("rg_numero") & "</td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Data de emiss�o:<b><br>" & Nvl(RSQuery("rg_emissao"),"---") & "</td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">�rg�o emissor:<b><br>" & RSQuery("rg_emissor") & "</td>"
           w_html = w_html & VbCrLf & "          <tr valign=""top"">"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Passaporte:<b><br>" & Nvl(RSQuery("passaporte_numero"),"---") & "</td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Pa�s emissor:<b><br>" & Nvl(RSQuery("nm_pais_passaporte"),"---") & "</td>"
           w_html = w_html & VbCrLf & "          </table>"
        Else
           w_html = w_html & VbCrLf & "      <tr><td colspan=2><font size=""1"">Inscri��o estadual:<b><br>" & Nvl(RSQuery("inscricao_estadual"),"---") & "</td>"
        End If
        If cDbl(RS("sq_tipo_pessoa")) = 1 Then
           w_html = w_html & VbCrLf & "      <tr><td align=""center"" style=""border: 1px solid rgb(0,0,0);""><font size=""1""><b>Endere�o comercial, Telefones e e-Mail</td>"
        Else
           w_html = w_html & VbCrLf & "      <tr><td align=""center"" style=""border: 1px solid rgb(0,0,0);""><font size=""1""><b>Endere�o principal, Telefones e e-Mail</td>"
        End If
        w_html = w_html & VbCrLf & "      <tr><td><table border=0 width=""100%"" cellspacing=0>"
        w_html = w_html & VbCrLf & "          <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<b><br>(" & RSQuery("ddd") & ") " & RSQuery("nr_telefone") & "</td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Fax:<b><br>" & Nvl(RSQuery("nr_fax"),"---") & "</td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Celular:<b><br>" & Nvl(RSQuery("nr_celular"),"---") & "</td>"
        w_html = w_html & VbCrLf & "          <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Endere�o:<b><br>" & RSQuery("logradouro") & "</td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Complemento:<b><br>" & Nvl(RSQuery("complemento"),"---") & "</td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Bairro:<b><br>" & Nvl(RSQuery("bairro"),"---") & "</td>"
        w_html = w_html & VbCrLf & "          <tr valign=""top"">"
        If RSQuery("pd_pais") = "S" Then
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Cidade:<b><br>" & RSQuery("nm_cidade") & "-" & RSQuery("co_uf") & "</td>"
        Else
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Cidade:<b><br>" & RSQuery("nm_cidade") & "-" & RSQuery("nm_pais") & "</td>"
        End If
        w_html = w_html & VbCrLf & "          <td><font size=""1"">CEP:<b><br>" & RSQuery("cep") & "</td>"
        If Nvl(RSQuery("email"),"nulo") <> "nulo" Then
           If Not P4 = 1 Then
              w_html = w_html & VbCrLf & "              <td><font size=""1"">e-Mail:<b><br><a class=""hl"" href=""mailto:" & RSQuery("email") & """>" & RSQuery("email") & "</a></td>"
           Else
              w_html = w_html & VbCrLf & "              <td><font size=""1"">e-Mail:<b><br>" & RSQuery("email") & "</td>"
           End If
        Else
           w_html = w_html & VbCrLf & "              <td><font size=""1"">e-Mail:<b><br>---</td>"
        End If
        w_html = w_html & VbCrLf & "          </table>"
        If Mid(w_SG,1,3) = "FNR" Then
           w_html = w_html & VbCrLf & "      <tr><td align=""center"" style=""border: 1px solid rgb(0,0,0);""><font size=""1""><b>Dados para recebimento</td>"
           w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Forma de recebimento:<b><br>" & RS("nm_forma_pagamento") & "</td>"
        ElseIf Mid(w_SG,1,3) = "FND" Then
           w_html = w_html & VbCrLf & "      <tr><td align=""center"" style=""border: 1px solid rgb(0,0,0);""><font size=""1""><b>Dados para pagamento</td>"
           w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Forma de pagamento:<b><br>" & RS("nm_forma_pagamento") & "</td>"
        Else
           w_html = w_html & VbCrLf & "      <tr><td align=""center"" style=""border: 1px solid rgb(0,0,0);""><font size=""1""><b>Dados para pagamento/recebimento</td>"
           w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Forma de pagamento/recebimento:<b><br>" & RS("nm_forma_pagamento") & "</td>"
        End If
        If Mid(w_SG,1,3) <> "FNR" Then
           w_html = w_html & VbCrLf & "      <tr><td><table border=0 width=""100%"" cellspacing=0>"
           If Instr("CREDITO,DEPOSITO",RS("sg_forma_pagamento")) > 0 Then
              w_html = w_html & VbCrLf & "          <tr valign=""top"">"
              If Nvl(RS("cd_banco"),"") > "" Then
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Banco:<b><br>" & RS("cd_banco") & " - " & RS("nm_banco") & "</td>"
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Ag�ncia:<b><br>" & RS("cd_agencia") & " - " & RS("nm_agencia") & "</td>"
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Opera��o:<b><br>" & Nvl(RS("operacao_conta"),"---") & "</td>"
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">N�mero da conta:<b><br>" & Nvl(RS("numero_conta"),"---") & "</td>"
              Else
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Banco:<b><br>---</td>"
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Ag�ncia:<b><br>---</td>"
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Opera��o:<b><br>---</td>"
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">N�mero da conta:<b><br>---</td>"
              End If
           ElseIf RS("sg_forma_pagamento") = "ORDEM" Then
              w_html = w_html & VbCrLf & "          <tr valign=""top"">"
              If Nvl(RS("cd_banco"),"") > "" Then
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Banco:<b><br>" & RS("cd_banco") & " - " & RS("nm_banco") & "</td>"
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Ag�ncia:<b><br>" & RS("cd_agencia") & " - " & RS("nm_agencia") & "</td>"
              Else
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Banco:<b><br>---</td>"
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Ag�ncia:<b><br>---</td>"
              End If
           ElseIf RS("sg_forma_pagamento") = "EXTERIOR" Then
              w_html = w_html & VbCrLf & "          <tr valign=""top"">"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Banco:<b><br>" & RS("banco_estrang") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">ABA Code:<b><br>" & Nvl(RS("aba_code"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">SWIFT Code:<b><br>" & Nvl(RS("swift_code"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <tr><td colspan=3><font size=""1"">Endere�o da ag�ncia:<b><br>" & Nvl(RS("endereco_estrang"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <tr valign=""top"">"
              w_html = w_html & VbCrLf & "          <td colspan=2><font size=""1"">Ag�ncia:<b><br>" & Nvl(RS("agencia_estrang"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">N�mero da conta:<b><br>" & Nvl(RS("numero_conta"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <tr valign=""top"">"
              w_html = w_html & VbCrLf & "          <td colspan=2><font size=""1"">Cidade:<b><br>" & RS("nm_cidade") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Pa�s:<b><br>" & RS("nm_pais") & "</td>"
           End If
           w_html = w_html & VbCrLf & "          </table>"
        End If
     End If

  End If
   
  Dim w_total, w_valor_inicial
  w_vl_retencao = Nvl(RS("valor_retencao"),0)
  w_vl_normal   = Nvl(RS("valor_imposto"),0)
  w_vl_total    = Nvl(RS("valor_total"),0)
  w_valor       = Nvl(RS("valor_liquido"),0)
  ' Documentos
  DB_GetLancamentoDoc RS, w_chave, null, "LISTA"
  RS.Sort = "data"
  If Not Rs.EOF Then
     w_html = w_html & VbCrLf & "      <tr><td align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Documentos</td>"
     If cDbl(w_vl_retencao) <> 0 or cDbl(w_vl_normal) <> 0 Then
        w_html = w_html & VbCrLf & "          <tr valign=""top""><td align=""center"" style=""border: 1px solid rgb(0,0,0);"">"
        w_html = w_html & VbCrLf & "            <table border=0 width=""100%"">"
        w_html = w_html & VbCrLf & "              <tr><td colspan=4><font size=1><b>Resumo da tributa��o sobre os documentos</b></font></td></tr>"
        w_html = w_html & VbCrLf & "              <tr valign=""top"">"
        w_html = w_html & VbCrLf & "              <td width=""25%""><font size=""1"">Valor Bruto:<br><b>" & FormatNumber(w_vl_total,2) & " </b></td>"
        w_html = w_html & VbCrLf & "              <td width=""25%""><font size=""1"">Reten��o:<br><b>" & FormatNumber(w_vl_retencao,2) & " </b></td>"
        w_html = w_html & VbCrLf & "              <td width=""25%""><font size=""1"">Impostos:<br><b>" & FormatNumber(w_vl_normal,2) & " </b></td>"
        w_html = w_html & VbCrLf & "              <td width=""25%""><font size=""1"">Valor l�quido:<br><b>" & FormatNumber(Nvl(w_valor,0),2) & " </b></td>"
        w_html = w_html & VbCrLf & "            </table>"
     End If
     w_html = w_html & VbCrLf & "      <tr><td align=""center"">"
     w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     w_html = w_html & VbCrLf & "          <tr bgcolor=""" & w_TrBgColor & """ align=""center"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Tipo</font></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1""><b>N�mero</font></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Data</font></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1""><b>S�rie</font></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Valor</font></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Patrim�nio</font></td>"
     w_html = w_html & VbCrLf & "          </tr>"
     w_cor = w_TrBgColor
     w_total = 0
     While Not Rs.EOF
       If w_cor = w_TrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = w_TrBgColor End If
       w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
       DB_GetImpostoDoc RS2, w_cliente, w_chave, RS("sq_lancamento_doc"), w_SG
       RS2.Sort = "calculo, esfera, nm_imposto"
       If RS2.EOF Then
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS("nm_tipo_documento") & "</td>"
          w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & RS("numero") & "</td>"
          w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("data")) & "</td>"
          w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & Nvl(RS("serie"),"---") & "</td>"
          w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber(RS("valor"),2) & "&nbsp;&nbsp;</td>"
          w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & RS("nm_patrimonio") & "</td>"
          w_html = w_html & VbCrLf & "      </tr>"
       Else
          w_html = w_html & VbCrLf & "        <td rowspan=2><font size=""1"">" & RS("nm_tipo_documento") & "</td>"
          w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & RS("numero") & "</td>"
          w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("data")) & "</td>"
          w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & Nvl(RS("serie"),"---") & "</td>"
          w_html = w_html & VbCrLf & "        <td rowspan=2 align=""right""><font size=""1"">" & FormatNumber(RS("valor"),2) & "&nbsp;&nbsp;</td>"
          w_html = w_html & VbCrLf & "        <td rowspan=2 align=""center""><font size=""1"">" & RS("nm_patrimonio") & "</td>"
          w_html = w_html & VbCrLf & "      </tr>"
          w_html = w_html & VbCrLf & "      <tr bgcolor=""" & w_cor & """ align=""center""><td colspan=3 align=""center"">"
          w_html = w_html & VbCrLf & "          <table border=1 width=""100%"">"
          w_html = w_html & VbCrLf & "          <tr valign=""top"" align=""center"" bgcolor=""" & w_cor & """ >"
          w_html = w_html & VbCrLf & "          <td rowspan=2><font size=""1""><b>Tributo</td>"
          w_html = w_html & VbCrLf & "          <td colspan=2><font size=""1""><b>Reten��o</td>"
          w_html = w_html & VbCrLf & "          <td colspan=2><font size=""1""><b>Normal</td>"
          w_html = w_html & VbCrLf & "          <td colspan=2><font size=""1""><b>Total</td>"
          w_html = w_html & VbCrLf & "          <tr bgcolor=""" & w_cor & """ align=""center"">"
          w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Valor</td>"
          w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Al�quota</td>"
          w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Valor</td>"
          w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Al�quota</td>"
          w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Valor</td>"
          w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Al�quota</td>"
          w_al_total       = 0
          w_al_retencao    = 0
          w_al_normal      = 0
          w_vl_total       = 0
          w_vl_retencao    = 0
          w_vl_normal      = 0
          While Not RS2.EOF
            w_html = w_html & VbCrLf & "          <tr bgcolor=""" & w_cor & """ valign=""top"">"
            w_html = w_html & VbCrLf & "          <td nowrap align=""right""><font size=""1"">" & RS2("nm_imposto") & "</td>"
            w_html = w_html & VbCrLf & "          <td align=""right""><font size=""1"">R$ " & FormatNumber(RS2("vl_retencao"),2) & "</td>"
            w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & FormatNumber(RS2("al_retencao"),2) & "%</td>"
            w_html = w_html & VbCrLf & "          <td align=""right""><font size=""1"">R$ " & FormatNumber(RS2("vl_normal"),2) & "</td>"
            w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & FormatNumber(RS2("al_normal"),2) & "%</td>"
            w_html = w_html & VbCrLf & "          <td align=""right""><font size=""1"">R$ " & FormatNumber(RS2("vl_total"),2) & "</td>"
            w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & FormatNumber(RS2("al_total"),2) & "%</td>"
            w_vl_total       = w_vl_total + cDbl(RS2("vl_total"))
            w_vl_retencao    = w_vl_retencao + cDbl(RS2("vl_retencao"))
            w_vl_normal      = w_vl_normal + cDbl(RS2("vl_normal"))
            RS2.MoveNext
          Wend
          If cDbl(Nvl(RS("valor"),0)) = 0 Then w_valor = 1 Else w_valor = cDbl(Nvl(RS("valor"),0)) End If
          w_al_total       = 100 - ((w_valor - (w_vl_normal + w_vl_retencao))*100/w_valor)
          w_al_retencao    = 100 - ((w_valor - w_vl_retencao)*100/w_valor)
          w_al_normal      = 100 - ((w_valor - w_vl_normal)*100/w_valor)
          w_html = w_html & VbCrLf & "          <tr bgcolor=""" & w_cor & """ valign=""top"">"
          w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1""><b>Totais</td>"
          w_html = w_html & VbCrLf & "          <td align=""right""><font size=1><b><font size=1>R$ " & FormatNumber(w_vl_retencao,2) & "<td align=""center""><b><font size=1> " & FormatNumber(w_al_retencao,2) & "%"
          w_html = w_html & VbCrLf & "          <td align=""right""><font size=1><b><font size=1>R$ " & FormatNumber(w_vl_normal,2) & "<td align=""center""><b><font size=1> " & FormatNumber(w_al_normal,2) & "%"
          w_html = w_html & VbCrLf & "          <td align=""right""><font size=1><b><font size=1>R$ " & FormatNumber(w_vl_total,2) & "<td align=""center""><b><font size=1> " & FormatNumber(w_al_total,2) & "%"
          w_html = w_html & VbCrLf & "          <tr bgcolor=""" & w_cor & """ valign=""top"">"
          w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1""><b>L�quido</td>"
          w_html = w_html & VbCrLf & "          <td colspan=2 align=""center""><font size=1><b><font size=1>R$ " & FormatNumber(w_valor - w_vl_retencao,2)
          w_html = w_html & VbCrLf & "          <td colspan=2 align=""center""><font size=1><b><font size=1>R$ " & FormatNumber(w_valor - w_vl_retencao - w_vl_normal,2)
          w_html = w_html & VbCrLf & "          <td colspan=2><font size=1>&nbsp;"
          w_html = w_html & VbCrLf & "          </table>"
       End If
       RS2.Close
       w_total = w_total + cDbl(RS("valor"))
       Rs.MoveNext
     wend
     If w_total > 0 Then
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        w_html = w_html & VbCrLf & "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        w_html = w_html & VbCrLf & "        <td align=""center"" colspan=4><font size=""1""><b>Total</b></td>"
        w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1""><b>" & FormatNumber(w_total,2) & "</b>&nbsp;&nbsp;</td>"
        w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">&nbsp;</td>"
        w_html = w_html & VbCrLf & "      </tr>"
     End If
     w_html = w_html & VbCrLf & "         </table></td></tr>"
  End If
  DesconectaBD

  ' Arquivos vinculados
  DB_GetSolicAnexo RS, w_chave, null, w_cliente
  RS.Sort = "nome"
  If Not Rs.EOF Then
     w_html = w_html & VbCrLf & "      <tr><td align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Arquivos anexos</td>"
     w_html = w_html & VbCrLf & "      <tr><td align=""center"">"
     w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     w_html = w_html & VbCrLf & "          <tr bgcolor=""" & w_TrBgColor & """ align=""center"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1""><b>T�tulo</font></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Descri��o</font></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Tipo</font></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1""><b>KB</font></td>"
     w_html = w_html & VbCrLf & "          </tr>"
     w_cor = w_TrBgColor
     While Not Rs.EOF
       If w_cor = w_TrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = w_TrBgColor End If
       w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
       If Not P4 = 1 Then
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & LinkArquivo("HL", w_cliente, RS("chave_aux"), "_blank", "Clique para exibir o arquivo em outra janela.", RS("nome"), null) & "</td>"
       Else
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS("nome") & "</td>"
       End If
       w_html = w_html & VbCrLf & "        <td><font size=""1"">" & Nvl(RS("descricao"),"---") & "</td>"
       w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS("tipo") & "</td>"
       w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & Round(cDbl(RS("tamanho"))/1024,1) & "&nbsp;</td>"
       w_html = w_html & VbCrLf & "      </tr>"
       Rs.MoveNext
     wend
     w_html = w_html & VbCrLf & "         </table></td></tr>"
  End If
  DesconectaBD

  ' Se for envio, executa verifica��es nos dados da solicita��o
  w_erro = ValidaLancamento(w_cliente, w_chave, Mid(w_SG,1,3)&"GERAL", null, null, null, Nvl(w_tramite,0))
  If w_erro > "" Then
     w_html = w_html & VbCrLf &  "<tr bgcolor=""" & w_TrBgColor & """><td colspan=2><font size=2>"
     w_html = w_html & VbCrLf &  "<HR>"
     If Mid(w_erro,1,1) = "0" Then
        w_html = w_html & VbCrLf &  "  <font color=""#BC3131""><b>ATEN��O:</b></font> Foram identificados os erros listados abaixo, n�o sendo poss�vel seu encaminhamento para fases posteriores � atual, nem sua liquida��o."
     ElseIf Mid(w_erro,1,1) = "1" Then
        w_html = w_html & VbCrLf &  "  <font color=""#BC3131""><b>ATEN��O:</b></font> Foram identificados os erros listados abaixo. Seu encaminhamento para fases posteriores � atual s� pode ser feito por um gestor do sistema ou do m�dulo de projetos."
     Else
        w_html = w_html & VbCrLf &  "  <font color=""#BC3131""><b>ATEN��O:</b></font> Foram identificados os alertas listados abaixo. Eles n�o impedem o encaminhamento para fases posteriores � atual, mas conv�m sua verifica��o."
     End If
     w_html = w_html & VbCrLf &  "  <ul>" & Mid(w_erro,2,1000) & "</ul>"
     w_html = w_html & VbCrLf &  "  </font></td></tr>"
  End If

  ' Encaminhamentos
  DB_GetSolicLog RS, w_chave, null, "LISTA"
  RS.Sort = "data desc, sq_siw_solic_log desc"
  w_html = w_html & VbCrLf & "      <tr><td align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Ocorr�ncias e Anota��es</td>"
  w_html = w_html & VbCrLf & "      <tr><td align=""center"">"
  w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
  w_html = w_html & VbCrLf & "          <tr bgcolor=""" & w_TrBgColor & """ align=""center"">"
  w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Data</font></td>"
  w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Despacho/Observa��o</font></td>"
  w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Respons�vel</font></td>"
  w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Fase / Destinat�rio</font></td>"
  w_html = w_html & VbCrLf & "          </tr>"    
  If Rs.EOF Then
     w_html = w_html & VbCrLf & "      <tr bgcolor=""" & w_TrBgColor & """><td colspan=4 align=""center""><font size=""1""><b>N�o foram encontrados encaminhamentos.</b></td></tr>"
  Else
     w_html = w_html & VbCrLf & "      <tr bgcolor=""" & w_TrBgColor & """ valign=""top"">"
     w_html = w_html & VbCrLf & "        <td colspan=4><font size=""1"">Fase atual: <b>" & RS("fase") & "</b></td>"
     w_cor = w_TrBgColor
     While Not Rs.EOF
       If w_cor = w_TrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = w_TrBgColor End If
       w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
       w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & FormatDateTime(RS("data"),2) & ", " & FormatDateTime(RS("data"),4)& "</td>"
       If Nvl(RS("caminho"),"") > "" and (not P4 = 1) Then
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & CRLF2BR(Nvl(RS("despacho"),"---") & "<br>[" & LinkArquivo("HL", w_cliente, RS("sq_siw_arquivo"), "_blank", "Clique para exibir o arquivo em outra janela.", "Anexo - " & RS("tipo") & " - " & Round(cDbl(RS("tamanho"))/1024,1) & " KB", null) & "]") & "</td>"
       Else
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & CRLF2BR(Nvl(RS("despacho"),"---")) & "</td>"
       End If
       If Not P4 = 1 Then
          w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & ExibePessoa(w_dir_volta, w_cliente, RS("sq_pessoa"), TP, RS("responsavel")) & "</td>"
       Else
          w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & RS("responsavel") & "</td>"
       End If
       If (Not IsNull(Tvl(RS("sq_lancamento_log")))) and (Not IsNull(Tvl(RS("destinatario")))) Then
          If Not P4 = 1 Then
             w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & ExibePessoa(w_dir_volta, w_cliente, RS("sq_pessoa_destinatario"), TP, RS("destinatario")) & "</td>"
          Else
             w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & RS("destinatario") & "</td>"
          End If
       ElseIf (Not IsNull(Tvl(RS("sq_lancamento_log")))) and IsNull(Tvl(RS("destinatario"))) Then
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

  w_html = w_html & VbCrLf & "    </table>"
  w_html = w_html & VbCrLf & "</table>"
  
  VisualLancamento = w_html

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

