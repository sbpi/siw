<%
REM =========================================================================
REM Rotina de visualização dos dados do acordo
REM -------------------------------------------------------------------------
Function VisualAcordo(w_chave, O, w_usuario, P1, P4)

  Dim RS, Rsquery, w_Erro
  Dim w_Imagem, w_html, w_TrBgColor
  Dim w_ImagemPadrao, w_tramite
  Dim w_tipo_visao
  Dim w_total, w_valor_inicial, w_real, w_fim, w_sg_tramite
  Set RS = Server.CreateObject("ADODB.RecordSet")
  Set RSQuery = Server.CreateObject("ADODB.RecordSet")
  
  If P4 = 1 Then w_TrBgColor = "" Else w_TrBgColor = conTrBgColor End If

  w_html = ""

  ' Recupera os dados do acordo
  DB_GetSolicData RS, w_chave, Mid(SG,1,3) & "GERAL"
  w_tramite        = RS("sq_siw_tramite")
  w_valor_inicial  = cDbl(RS("valor_inicial"))
  w_fim            = cDate(RS("fim_real"))
  w_sg_tramite     = RS("sg_tramite")

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
     If SolicAcesso(w_chave, w_usuario) > 2 Then w_tipo_visao = 1 End If
  End If
  
  ' Se for listagem ou envio, exibe os dados de identificação do acordo
  If O = "L" or O = "V" Then ' Se for listagem dos dados
     w_html = w_html & VbCrLf & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
     w_html = w_html & VbCrLf & "<tr bgcolor=""" & w_TrBgColor & """><td>"

     w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
     If Not IsNull(RS("nm_projeto")) Then
        w_html = w_html & VbCrLf & "      <tr valign=""top""><td><font size=""1"">Projeto: <b>" & RS("nm_projeto") & "  (" & RS("sq_solic_pai") & ")</b></td>"
     End If
     ' Se a classificação foi informada, exibe.
     If Not IsNull(RS("sq_cc")) Then
        w_html = w_html & VbCrLf & "      <tr valign=""top""><td><font size=""1"">Classificação:<br><b>" & RS("nm_cc") & " </b>"
     End If

     If Not (P1 = 4 or P4 = 1) Then
        w_html = w_html & VbCrLf & "       <td align=""right""><font size=""1""><b><A class=""hl"" HREF=""" & w_dir & w_pagina & "visual&O=T&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=volta&P1=4&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG="&SG&""" title=""Exibe as informações do acordo."">Exibir todas as informações</a></td>"
     End If

     w_html = w_html & VbCrLf & "      <tr><td colspan=2><font size=1>Objeto: <b>" & RS("codigo_interno") & " (" & w_chave & ")<br>" & CRLF2BR(RS("objeto")) & "</b></font></td></tr>"
      
      ' Identificação do acordo
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Identificação</td>"
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><font size=""1"">Tipo:<br><b>" & RS("nm_tipo_acordo") & " </b></td>"
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Cidade de origem:<br><b>" & RS("nm_cidade") & " (" & RS("co_uf") & ")</b></td>"
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     If Not P4 = 1 Then
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Responsável monitoramento:<br><b>" & ExibePessoa(w_dir_volta, w_cliente, RS("solicitante"), TP, RS("nm_solic")) & "</b></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Unidade responsável monitoramento:<br><b>" & ExibeUnidade(w_dir_volta, w_cliente, RS("nm_unidade_resp"), RS("sq_unidade"), TP) & "</b></td>"
     Else
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Responsável monitoramento:<br><b>" & RS("nm_solic") & "</b></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Unidade responsável monitoramento:<br><b>" & RS("nm_unidade_resp") & "</b></td>"
     End If
     If w_tipo_visao = 0 Then ' Se for visão completa
        w_html = w_html & VbCrLf & "          <td valign=""top""><font size=""1"">Valor acordado:<br><b>" & FormatNumber(RS("valor"),2) & " </b></td>"
     End If
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Início vigência:<br><b>" & FormataDataEdicao(RS("inicio")) & " </b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Término vigência:<br><b>" & FormataDataEdicao(RS("fim")) & " </b></td>"
     w_html = w_html & VbCrLf & "          </table>"

     If w_tipo_visao = 0 or w_tipo_visao = 1 Then
        ' Informações adicionais
        If Nvl(RS("descricao"),"") > "" or Nvl(RS("justificativa"),"") > "" Then 
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Informações adicionais</td>"
           If Nvl(RS("descricao"),"") > "" Then w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><font size=""1"">Resultados esperados:<br><b>" & CRLF2BR(RS("descricao")) & " </b></td>" End If
           If w_tipo_visao = 0 and Nvl(RS("justificativa"),"") > "" Then ' Se for visão completa
              w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><font size=""1"">Observações:<br><b>" & CRLF2BR(RS("justificativa")) & " </b></td>"
           End If
        End If
     End If

     ' Dados da conclusão da demanda, se ela estiver nessa situação
     If Nvl(RS("conclusao"),"") > "" Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Dados do encerramento</td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
        w_html = w_html & VbCrLf & "          <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Início da vigência:<br><b>" & FormataDataEdicao(RS("inicio_real")) & " </b></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Término da vigência:<br><b>" & FormataDataEdicao(RS("fim_real")) & " </b></td>"
        If w_tipo_visao = 0 Then
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Valor realizado:<br><b>" & FormatNumber(RS("valor_atual"),2) & " </b></td>"
        End If
        w_html = w_html & VbCrLf & "          </table>"
        If w_tipo_visao = 0 Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Nota de conclusão:<br><b>" & CRLF2BR(RS("observacao")) & " </b></td>"
        End If
     End If

     If P1 = 4 Then ' Exibe ficha completa
        ' Termo de referência
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Termo de referência</b></td>"
        w_html = w_html & VbCrLf & "      <tr><td colspan=2><font size=""1"">Atividades a serem desenvolvidas:<b><br>" & CRLF2BR(RS("atividades")) & "</td>"
        w_html = w_html & VbCrLf & "      <tr><td colspan=2><font size=""1"">Produtos a serem entregues:<b><br>" & CRLF2BR(RS("produtos")) & "</td>"
        w_html = w_html & VbCrLf & "      <tr><td colspan=2><font size=""1"">Requisitos para contratação:<b><br>" & CRLF2BR(RS("requisitos")) & "</td>"
        w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Código do acordo para a outra parte:<b><br>" & Nvl(RS("codigo_externo"),"---") & "</td>"
        If Nvl(RS("cd_modalidade"),"") = "F" Then
           w_html = w_html & VbCrLf & "          <tr><td colspan=2><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Pemite vinculação de projetos?<b><br>" & RS("nm_vincula_projeto") & "</td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Pemite vinculação de demandas?<b><br>" & RS("nm_vincula_demanda") & "</td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Pemite vinculação de viagens?<b><br>" & RS("nm_vincula_viagem") & "</td>"
           w_html = w_html & VbCrLf & "          </table>"
        End If
     End If

     ' Outra parte
     DB_GetBenef RSQuery, w_cliente, Nvl(RS("outra_parte"),0), null, null, null, Nvl(RS("sq_tipo_pessoa"),0), null, null
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Outra parte</td>"
     If RSQuery.EOF Then
        w_html = w_html & VbCrLf & "      <tr><td colspan=2><font size=2><b>Outra parte não informada"
     Else
        w_html = w_html & VbCrLf & "      <tr><td colspan=2><font size=2><b>"
        w_html = w_html & VbCrLf & "          " & RSQuery("nm_pessoa") & " (" & RSQuery("nome_resumido") & ")"
        If cDbl(Nvl(RS("sq_tipo_pessoa"),0)) = 1 Then
           w_html = w_html & VbCrLf & "          - " & RSQuery("cpf")
        Else
           w_html = w_html & VbCrLf & "          - " & RSQuery("cnpj")
        End IF
        If P1 = 4 Then ' Exibe ficha completa
           If cDbl(RS("sq_tipo_pessoa")) = 1 Then
              w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
              w_html = w_html & VbCrLf & "          <tr valign=""top"">"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Sexo:<b><br>" & RSQuery("nm_sexo") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Data de nascimento:<b><br>" & FormataDataEdicao(RSQuery("nascimento")) & "</td>"
              w_html = w_html & VbCrLf & "          <tr valign=""top"">"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Identidade:<b><br>" & RSQuery("rg_numero") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Data de emissão:<b><br>" & Nvl(RSQuery("rg_emissao"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Órgão emissor:<b><br>" & RSQuery("rg_emissor") & "</td>"
              w_html = w_html & VbCrLf & "          <tr valign=""top"">"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Passaporte:<b><br>" & Nvl(RSQuery("passaporte_numero"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">País emissor:<b><br>" & Nvl(RSQuery("nm_pais_passaporte"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          </table>"
           Else
              w_html = w_html & VbCrLf & "      <tr><td colspan=2><font size=""1"">Inscrição estadual:<b><br>" & Nvl(RSQuery("inscricao_estadual"),"---") & "</td>"
           End If
           If cDbl(RS("sq_tipo_pessoa")) = 1 Then
              w_html = w_html & VbCrLf & "      <tr><td colspan=""2"" align=""center"" style=""border: 1px solid rgb(0,0,0);""><font size=""1""><b>Endereço comercial, Telefones e e-Mail</td>"
           Else
              w_html = w_html & VbCrLf & "      <tr><td colspan=""2"" align=""center"" style=""border: 1px solid rgb(0,0,0);""><font size=""1""><b>Endereço principal, Telefones e e-Mail</td>"
           End If
           w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
           w_html = w_html & VbCrLf & "          <tr valign=""top"">"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<b><br>(" & RSQuery("ddd") & ") " & RSQuery("nr_telefone") & "</td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Fax:<b><br>" & Nvl(RSQuery("nr_fax"),"---") & "</td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Celular:<b><br>" & Nvl(RSQuery("nr_celular"),"---") & "</td>"
           w_html = w_html & VbCrLf & "          <tr valign=""top"">"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Endereço:<b><br>" & RSQuery("logradouro") & "</td>"
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
           If Mid(SG,1,3) = "GCR" Then
              w_html = w_html & VbCrLf & "      <tr><td colspan=""2"" align=""center"" style=""border: 1px solid rgb(0,0,0);""><font size=""1""><b>Dados para recebimento</td>"
              w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><font size=""1"">Forma de recebimento:<b><br>" & RS("nm_forma_pagamento") & "</td>"
           ElseIf Mid(SG,1,3) = "GCD" Then
              w_html = w_html & VbCrLf & "      <tr><td colspan=""2"" align=""center"" style=""border: 1px solid rgb(0,0,0);""><font size=""1""><b>Dados para pagamento</td>"
              w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><font size=""1"">Forma de pagamento:<b><br>" & RS("nm_forma_pagamento") & "</td>"
           Else
              w_html = w_html & VbCrLf & "      <tr><td colspan=""2"" align=""center"" style=""border: 1px solid rgb(0,0,0);""><font size=""1""><b>Dados para pagamento/recebimento</td>"
              w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><font size=""1"">Forma de pagamento/recebimento:<b><br>" & RS("nm_forma_pagamento") & "</td>"
           End If
           If Mid(SG,1,3) <> "GCR" Then
              w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
              If Instr("CREDITO,DEPOSITO",RS("sg_forma_pagamento")) > 0 Then
                 w_html = w_html & VbCrLf & "          <tr valign=""top"">"
                 If Nvl(RS("cd_banco"),"") > "" Then
                    w_html = w_html & VbCrLf & "          <td><font size=""1"">Banco:<b><br>" & RS("cd_banco") & " - " & RS("nm_banco") & "</td>"
                    w_html = w_html & VbCrLf & "          <td><font size=""1"">Agência:<b><br>" & RS("cd_agencia") & " - " & RS("nm_agencia") & "</td>"
                    w_html = w_html & VbCrLf & "          <td><font size=""1"">Operação:<b><br>" & Nvl(RS("operacao_conta"),"---") & "</td>"
                    w_html = w_html & VbCrLf & "          <td><font size=""1"">Número da conta:<b><br>" & Nvl(RS("numero_conta"),"---") & "</td>"
                 Else
                    w_html = w_html & VbCrLf & "          <td><font size=""1"">Banco:<b><br>---</td>"
                    w_html = w_html & VbCrLf & "          <td><font size=""1"">Agência:<b><br>---</td>"
                    w_html = w_html & VbCrLf & "          <td><font size=""1"">Operação:<b><br>---</td>"
                    w_html = w_html & VbCrLf & "          <td><font size=""1"">Número da conta:<b><br>---</td>"
                 End If
              ElseIf RS("sg_forma_pagamento") = "ORDEM" Then
                 w_html = w_html & VbCrLf & "          <tr valign=""top"">"
                 If Nvl(RS("cd_banco"),"") > "" Then
                    w_html = w_html & VbCrLf & "          <td><font size=""1"">Banco:<b><br>" & RS("cd_banco") & " - " & RS("nm_banco") & "</td>"
                    w_html = w_html & VbCrLf & "          <td><font size=""1"">Agência:<b><br>" & RS("cd_agencia") & " - " & RS("nm_agencia") & "</td>"
                 Else
                    w_html = w_html & VbCrLf & "          <td><font size=""1"">Banco:<b><br>---</td>"
                    w_html = w_html & VbCrLf & "          <td><font size=""1"">Agência:<b><br>---</td>"
                 End If
              ElseIf RS("sg_forma_pagamento") = "EXTERIOR" Then
                 w_html = w_html & VbCrLf & "          <tr valign=""top"">"
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Banco:<b><br>" & RS("banco_estrang") & "</td>"
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">ABA Code:<b><br>" & Nvl(RS("aba_code"),"---") & "</td>"
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">SWIFT Code:<b><br>" & Nvl(RS("swift_code"),"---") & "</td>"
                 w_html = w_html & VbCrLf & "          <tr><td colspan=3><font size=""1"">Endereço da agência:<b><br>" & Nvl(RS("endereco_estrang"),"---") & "</td>"
                 w_html = w_html & VbCrLf & "          <tr valign=""top"">"
                 w_html = w_html & VbCrLf & "          <td colspan=2><font size=""1"">Agência:<b><br>" & Nvl(RS("agencia_estrang"),"---") & "</td>"
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Número da conta:<b><br>" & Nvl(RS("numero_conta"),"---") & "</td>"
                 w_html = w_html & VbCrLf & "          <tr valign=""top"">"
                 w_html = w_html & VbCrLf & "          <td colspan=2><font size=""1"">Cidade:<b><br>" & RS("nm_cidade") & "</td>"
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">País:<b><br>" & RS("nm_pais") & "</td>"
              End If
              w_html = w_html & VbCrLf & "          </table>"
           End If
        End If
     End If

     If cDbl(Nvl(RS("sq_tipo_pessoa"),0)) = 2 and P1 = 4 Then ' Se outra parte for pessoa jurídica
        ' Preposto
        DB_GetBenef RSQuery, w_cliente, Nvl(RS("preposto"),0), null, null, null, null, null, null
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Preposto</td>"
        If RSQuery.EOF Then
           w_html = w_html & VbCrLf & "      <tr><td colspan=2><font size=2><b>Preposto não informado"
        Else
           w_html = w_html & VbCrLf & "      <tr><td colspan=2><font size=2><b>"
           w_html = w_html & VbCrLf & "          " & RSQuery("nm_pessoa") & " (" & RSQuery("nome_resumido") & ")"
           w_html = w_html & VbCrLf & "          - " & RSQuery("cpf")
           If P1 = 4 Then ' Exibe ficha completa
              w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Sexo:<b><br>" & RSQuery("nm_sexo") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Identidade:<b><br>" & RSQuery("rg_numero") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Data de emissão:<b><br>" & Nvl(RSQuery("rg_emissao"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Órgão emissor:<b><br>" & RSQuery("rg_emissor") & "</td>"
              w_html = w_html & VbCrLf & "          </table>"
           End If
        End If

        ' Representantes
        DB_GetAcordoRep RSQuery, RS("sq_siw_solicitacao"), w_cliente, null, null
        RSQuery.Sort = "nm_pessoa"
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Representantes</td>"
        If RSQuery.EOF Then
           w_html = w_html & VbCrLf & "      <tr><td colspan=2><font size=2><b>Representantes não informados"
        Else
           w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
           w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           w_html = w_html & VbCrLf & "          <tr bgcolor=""" & w_TrBgColor & """ align=""center"">"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>CPF</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Nome</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>DDD</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Telefone</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Fax</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Celular</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>e-Mail</font></td>"
           w_html = w_html & VbCrLf & "          </tr>"    
           w_cor = w_TrBgColor
           While Not RSQuery.EOF
             If w_cor = w_TrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = w_TrBgColor End If
             w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
             w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & RSQuery("cpf") & "</td>"
             w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RSQuery("nome_resumido") & "</td>"
             w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & Nvl(RSQuery("ddd"),"---") & "</td>"
             w_html = w_html & VbCrLf & "        <td><font size=""1"">" & Nvl(RSQuery("nr_telefone"),"---") & "</td>"
             w_html = w_html & VbCrLf & "        <td><font size=""1"">" & Nvl(RSQuery("nr_fax"),"---") & "</td>"
             w_html = w_html & VbCrLf & "        <td><font size=""1"">" & Nvl(RSQuery("nr_celular"),"---") & "</td>"
             If Nvl(RSQuery("email"),"nulo") <> "nulo" Then
                If Not P4 = 1 Then
                   w_html = w_html & VbCrLf & "        <td><font size=""1""><a class=""hl"" href=""mailto:" & RSQuery("email") & """>" & RSQuery("email") & "</a></td>"
                Else
                   w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RSQuery("email") & "</td>"
                End If
             Else
                w_html = w_html & VbCrLf & "        <td><font size=""1"">---</td>"
             End If
             w_html = w_html & VbCrLf & "      </tr>"
             RSQuery.MoveNext
           wend
           w_html = w_html & VbCrLf & "         </table></td></tr>"
        End If
     End If
  End If
   
  ' Se for listagem, exibe os outros dados dependendo do tipo de visão  do usuário
  If w_tipo_visao <> 2 and (O = "L" or O = "T") and P1 = 4 Then
     If RS("aviso_prox_conc") = "S" Then
        ' Configuração dos alertas de proximidade da data limite para conclusão do acordo
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Alertas</td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
        w_html = w_html & VbCrLf & "          <td valign=""top""><font size=""1"">Emite aviso:<br><b>" & Replace(Replace(RS("aviso_prox_conc"),"S","Sim"),"N","Não") & " </b></td>"
        w_html = w_html & VbCrLf & "          <td valign=""top""><font size=""1"">Dias:<br><b>" & RS("dias_aviso") & " </b></td>"
        w_html = w_html & VbCrLf & "          </table>"
     End If

     ' Interessados na execução do acordo
     'DB_GetSolicInter RS, w_chave, null, "LISTA"
     'RS.Sort = "nome_resumido"
     'If Not Rs.EOF Then
     '   w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Interessados na execução</td>"
     '   w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
     '   w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     '   w_html = w_html & VbCrLf & "          <tr bgcolor=""" & w_TrBgColor & """ align=""center"">"
     '   w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Nome</font></td>"
     '   w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Tipo de visão</font></td>"
     '   w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Envia e-mail</font></td>"
     '   w_html = w_html & VbCrLf & "          </tr>"    
     '   w_cor = w_TrBgColor
     '   While Not Rs.EOF
     '     If w_cor = w_TrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = w_TrBgColor End If
     '     w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
     '     w_html = w_html & VbCrLf & "        <td><font size=""1"">" & ExibePessoa(w_dir_volta, w_cliente, RS("sq_pessoa"), TP, RS("nome") & " (" & RS("lotacao") & ")") & "</td>"
     '     w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RetornaTipoVisao(RS("tipo_visao")) & "</td>"
     '     w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & Replace(Replace(RS("envia_email"),"S","Sim"),"N","Não") & "</td>"
     '     w_html = w_html & VbCrLf & "      </tr>"
     '     Rs.MoveNext
     '   wend
     '   w_html = w_html & VbCrLf & "         </table></td></tr>"
     'End If
     'DesconectaBD
  End If

  ' Parcelas
  DB_GetAcordoParcela RS, w_chave, null, null, null, null, null, null, null, null
  RS.Sort = "ordem"
  If Not Rs.EOF Then
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Parcelas</td>"
     w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
     w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     w_html = w_html & VbCrLf & "          <tr bgcolor=""" & w_TrBgColor & """ align=""center"">"
     w_html = w_html & VbCrLf & "          <td rowspan=2><font size=""1""><b>Ordem</font></td>"
     w_html = w_html & VbCrLf & "          <td rowspan=2><font size=""1""><b>Vencimento</font></td>"
     w_html = w_html & VbCrLf & "          <td rowspan=2><font size=""1""><b>Valor</font></td>"
     w_html = w_html & VbCrLf & "          <td rowspan=2><font size=""1""><b>Observações</font></td>"
     w_html = w_html & VbCrLf & "          <td colspan=4><font size=""1""><b>Financeiro</font></td>"
     w_html = w_html & VbCrLf & "          </tr>"
     w_html = w_html & VbCrLf & "          <tr bgcolor=""" & w_TrBgColor & """ align=""center"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Lançamento</font></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Vencimento</font></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Valor</font></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Quitação</font></td>"
     w_html = w_html & VbCrLf & "          </tr>"
     w_cor = w_TrBgColor
     w_total = 0
     While Not Rs.EOF
       If w_cor = w_TrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = w_TrBgColor End If
       w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
       w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">"
       If Nvl(w_sg_tramite,"-") = "CR" and cDbl(w_fim-RS("vencimento")) < 0 Then
          w_html = w_html & VbCrLf & "           <img src=""" & conImgCancel & """ border=0 width=15 heigth=15 align=""center"" title=""Parcela cancelada!"">"
       ElseIf Nvl(RS("quitacao"),"nulo") = "nulo" Then
          If RS("vencimento") < Date() Then
             w_html = w_html & VbCrLf & "           <img src=""" & conImgAtraso & """ border=0 width=15 heigth=15 align=""center"">"
          ElseIf cDbl(RS("vencimento")-Date()) <= 5 Then
             w_html = w_html & VbCrLf & "           <img src=""" & conImgAviso & """ border=0 width=15 height=15 align=""center"">"
          Else
             w_html = w_html & VbCrLf & "           <img src=""" & conImgNormal & """ border=0 width=15 height=15 align=""center"">"
          End IF
       Else
          If RS("quitacao") > RS("vencimento") Then
             w_html = w_html & VbCrLf & "           <img src=""" & conImgOkAtraso & """ border=0 width=15 heigth=15 align=""center"">"
          Else
             w_html = w_html & VbCrLf & "           <img src=""" & conImgOkNormal & """ border=0 width=15 height=15 align=""center"">"
          End IF
       End If
       w_html = w_html & VbCrLf & "        " & RS("ordem") & "</td>"
       w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("vencimento")) & "</td>"
       w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber(RS("valor"),2) & "</td>"
       w_html = w_html & VbCrLf & "        <td><font size=""1"">" & Nvl(RS("observacao"),"---") & "</td>"
       If Nvl(RS("cd_lancamento"),"") > "" Then
          w_html = w_html & VbCrLf & "        <td align=""center"" nowrap><font size=""1""><A class=""hl"" HREF=""" & "mod_fn/Lancamento.asp?par=Visual&O=L&w_chave=" & RS("sq_lancamento") & "&w_tipo=&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=FN" & Mid(SG,3,1) & "CONT"" title=""Exibe as informações do lançamento."" target=""Lancamento"">" & RS("cd_lancamento") & "</a></td>"
          w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("dt_lancamento")) & "</td>"
          w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber(RS("vl_lancamento"),2) & "</td>"
          w_real = w_real + cDbl(RS("vl_lancamento"))
       Else
          w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">---</td>"
          w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">---</td>"
          w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">---</td>"
       End If
       w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS("quitacao")),"---") & "</td>"
       w_html = w_html & VbCrLf & "      </tr>"
       w_total = w_total + cDbl(RS("valor"))
       Rs.MoveNext
     wend
     If w_total > 0 or w_real > 0 Then
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        w_html = w_html & VbCrLf & "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        w_html = w_html & VbCrLf & "        <td align=""right"" colspan=2><font size=""1""><b>Previsto</b></td>"
        w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1""><b>" & FormatNumber(w_total,2) & "</b></td>"
        If cDbl(w_valor_inicial) <> cDbl(w_total) Then
           w_html = w_html & VbCrLf & "        <td colspan=2><font size=1><b>O valor das parcelas difere do valor contratado (" & FormatNumber(w_valor_inicial-w_total,2) & ")</b></td>"
        Else
           w_html = w_html & VbCrLf & "        <td colspan=2>&nbsp;</td>"
        End If
        w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1""><b>Realizado</b></td>"
        w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1""><b>" & FormatNumber(w_real,2) & "</b></td>"
        w_html = w_html & VbCrLf & "        <td>&nbsp;</td>"
        w_html = w_html & VbCrLf & "      </tr>"
     End If
     w_html = w_html & VbCrLf & "         </table></td></tr>"
  End If
  DesconectaBD

  If P1 = 4 and (O = "L" or O = "V" or O = "T") Then ' Se for listagem dos dados

     ' Arquivos vinculados
     DB_GetSolicAnexo RS, w_chave, null, w_cliente
     RS.Sort = "nome"
     If Not Rs.EOF Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Arquivos anexos</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
        w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        w_html = w_html & VbCrLf & "          <tr bgcolor=""" & w_TrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Título</font></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Descrição</font></td>"
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
  End If

  ' Se for envio, executa verificações nos dados da solicitação
  w_erro = ValidaAcordo(w_cliente, w_chave, Mid(SG,1,3)&"GERAL", null, null, null, Nvl(w_tramite,0))
  If w_erro > "" Then
     w_html = w_html & VbCrLf &  "<tr bgcolor=""" & w_TrBgColor & """><td colspan=2><font size=2>"
     w_html = w_html & VbCrLf &  "<HR>"
     If Mid(w_erro,1,1) = "0" Then
        w_html = w_html & VbCrLf &  "  <font color=""#BC3131""><b>ATENÇÃO:</b></font> Foram identificados os erros listados abaixo, não sendo possível seu encaminhamento para fases posteriores à atual."
     ElseIf Mid(w_erro,1,1) = "1" Then
        w_html = w_html & VbCrLf &  "  <font color=""#BC3131""><b>ATENÇÃO:</b></font> Foram identificados os erros listados abaixo. Seu encaminhamento para fases posteriores à atual só pode ser feito por um gestor do sistema ou do módulo de projetos."
     Else
        w_html = w_html & VbCrLf &  "  <font color=""#BC3131""><b>ATENÇÃO:</b></font> Foram identificados os alertas listados abaixo. Eles não impedem o encaminhamento para fases posteriores à atual, mas convém sua verificação."
     End If
     w_html = w_html & VbCrLf &  "  <ul>" & Mid(w_erro,2,1000) & "</ul>"
     w_html = w_html & VbCrLf &  "  </font></td></tr>"
  End If

  If P1 = 4 and (O = "L" or O = "V" or O = "T") Then ' Se for listagem dos dados
     ' Encaminhamentos
     DB_GetSolicLog RS, w_chave, null, "LISTA"
     RS.Sort = "data desc, sq_siw_solic_log desc"
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Ocorrências e Anotações</td>"
     w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
     w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     w_html = w_html & VbCrLf & "          <tr bgcolor=""" & w_TrBgColor & """ align=""center"">"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Data</font></td>"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Despacho/Observação</font></td>"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Responsável</font></td>"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Fase / Destinatário</font></td>"
     w_html = w_html & VbCrLf & "          </tr>"    
     If Rs.EOF Then
        w_html = w_html & VbCrLf & "      <tr bgcolor=""" & w_TrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados encaminhamentos.</b></td></tr>"
     Else
        w_html = w_html & VbCrLf & "      <tr bgcolor=""" & w_TrBgColor & """ valign=""top"">"
        w_html = w_html & VbCrLf & "        <td colspan=6><font size=""1"">Fase atual: <b>" & RS("fase") & "</b></td>"
        w_cor = w_TrBgColor
        While Not Rs.EOF
          If w_cor = w_TrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = w_TrBgColor End If
          w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
          w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & FormatDateTime(RS("data"),2) & ", " & FormatDateTime(RS("data"),4)& "</td>"
          If Nvl(RS("caminho"),"") > "" and (not P4 = 1) Then
             w_html = w_html & VbCrLf & "        <td><font size=""1"">" & CRLF2BR(Nvl(RS("despacho"),"---") & "<br>[" & LinkArquivo("HL", w_cliente, RS("sq_siw_arquivo"), "_blank", "Clique para exibir o arquivo em outra janela.", Anexo - " & RS("tipo") & " - " & Round(cDbl(RS("tamanho"))/1024,1) & " KB, null) & "]") & "</td>"
          Else
             w_html = w_html & VbCrLf & "        <td><font size=""1"">" & CRLF2BR(Nvl(RS("despacho"),"---")) & "</td>"
          End If
          If Not P4 = 1 Then
             w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & ExibePessoa(w_dir_volta, w_cliente, RS("sq_pessoa"), TP, RS("responsavel")) & "</td>"
          Else
             w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & RS("responsavel") & "</td>"
          End If
          If (Not IsNull(Tvl(RS("sq_acordo_log")))) and (Not IsNull(Tvl(RS("destinatario")))) Then
             If Not P4 = 1 Then
                w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & ExibePessoa(w_dir_volta, w_cliente, RS("sq_pessoa_destinatario"), TP, RS("destinatario")) & "</td>"
             Else
                w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & RS("destinatario") & "</td>"
             End If
          ElseIf (Not IsNull(Tvl(RS("sq_acordo_log")))) and IsNull(Tvl(RS("destinatario"))) Then
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

  End If
  w_html = w_html & VbCrLf & "    </table>"
  w_html = w_html & VbCrLf & "</table>"
  
  VisualAcordo = w_html

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

