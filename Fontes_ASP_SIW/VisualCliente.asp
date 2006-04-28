<%

REM =========================================================================
REM Rotina de visualização dos dados do cliente
REM -------------------------------------------------------------------------
Sub VisualCliente(w_sq_cliente, O)

  Dim RS, Rsquery, w_Erro
  Dim w_Imagem
  Dim w_ImagemPadrao
  Dim RS1, RS2, RS3
  Set RSquery  = Server.CreateObject("ADODB.RecordSet")
  Set RS = Server.CreateObject("ADODB.RecordSet")
  Set RS1 = Server.CreateObject("ADODB.RecordSet")
  Set RS2 = Server.CreateObject("ADODB.RecordSet")
  Set RS3 = Server.CreateObject("ADODB.RecordSet")


  DB_GetCustomerData RS, w_sq_cliente

  If O = "L" Then ' Se for listagem dos dados
     ShowHTML "<div align=center><center>"
     ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"

     ShowHTML "    <table width=""99%"" border=""0"">"
     ShowHTML "      <tr><td align=""center"" colspan=""2""><font size=5><b>" & RS("nome_resumido") & " (" & RS("cnpj") & ")</b></font></td></tr>"
      
      ' Identificação civil e localização
     ShowHTML "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""2""><b>Identificação Civil e Localização</td>"
     ShowHTML "      <tr><td valign=""top""><font size=""2"">Razão Social:<br><b>" & RS("nome") & " </b></td>"
     ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     ShowHTML "          <td valign=""top""><font size=""2"">Código interno:<br><b>" & RS("sq_pessoa") & " </b></td>"
     ShowHTML "          <td valign=""top""><font size=""2"">Segmento:<br><b>" & RS("segmento") & " </b></td>"
     ShowHTML "          <tr>"
     ShowHTML "          <td valign=""top""><font size=""2"">Inscrição estadual:<br><b>" & Nvl(RS("inscricao_estadual"),"Não informada") & " </b></td>"
     ShowHTML "          <td valign=""top""><font size=""2"">Início das atividades:<br><b>" & FormataDataEdicao(RS("inicio_atividade")) & " </b></td>"
     ShowHTML "          <td valign=""top""><font size=""2"">Sede (Matriz)?<br><b>" & Replace(Replace(RS("sede"),"S","Sim"),"N","Não") & " </b></td>"
     ShowHTML "          </table>"

      ' Cidade e agência padrão
     ShowHTML "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""2""><b>Cidade e Agência Padrão</td>"
     ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     ShowHTML "          <td valign=""top""><font size=""2"">Cidade:<br><b>" & RS("cidade") & " </b></td>"
     ShowHTML "          <td valign=""top""><font size=""2"">Estado:<br><b>" & RS("co_uf") & " </b></td>"
     ShowHTML "          <td valign=""top""><font size=""2"">País:<br><b>" & RS("pais") & " </b></td>"
     ShowHTML "          </table>"
     ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     ShowHTML "          <td valign=""top""><font size=""2"">Banco:<br><b>" & RS("banco") & " </b></td>"
     ShowHTML "          <td valign=""top""><font size=""2"">Agência:<br><b>" & RS("codigo") & " - " & RS("agencia") & " </b></td>"
     ShowHTML "          </table>"

      ' Parâmetros de segurança
     ShowHTML "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""2""><b>Parâmetros de Segurança</td>"
     ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     ShowHTML "          <td valign=""top""><font size=""2"">Tamanho mínimo:<br><b>" & RS("TAMANHO_MIN_SENHA") & " </b></td>"
     ShowHTML "          <td valign=""top""><font size=""2"">Tamanho máximo:<br><b>" & RS("TAMANHO_MAX_SENHA") & " </b></td>"
     ShowHTML "          <td valign=""top""><font size=""2"">Máximo de tentativas:<br><b>" & RS("maximo_tentativas") & " </b></td>"
     ShowHTML "          <tr>"
     ShowHTML "          <td valign=""top""><font size=""2"">Limite da vigência:<br><b>" & RS("DIAS_VIG_SENHA") & " </b></td>"
     ShowHTML "          <td valign=""top""><font size=""2"">Dias para aviso:<br><b>" & RS("DIAS_AVISO_EXPIR") & " </b></td>"
     ShowHTML "          </table>"

     'Endereços de e-mail e internet
     DB_GetAddressList RS, w_sq_cliente, null, "EMAILINTERNET", null
     RS.Sort = "tipo_endereco, padrao desc, endereco"
     ShowHTML "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""2""><b>Endereços e-Mail e Internet</td>"
     ShowHTML "      <tr><td align=""center"" colspan=""2"">"
     ShowHTML "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     ShowHTML "            <td><font size=""2""><b>Endereço</font></td>"
     ShowHTML "            <td><font size=""2""><b>Padrão</font></td>"
     ShowHTML "          </tr>"    
     If Rs.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=2 align=""center""><font  size=""2""><b>Não informado.</b></td></tr>"
     Else
        While Not Rs.EOF
          ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
          If RS("email") = "S" Then
             ShowHTML "        <td><font size=""2""><a href=""mailto://" & RS("logradouro") & """>" & RS("logradouro") & "</a></td>"
          Else
             ShowHTML "        <td><font size=""2""><a href=""://" & replace(RS("logradouro"),"://","") & """ target=""_blank"">" & RS("logradouro") & "</a></td>"
          End If
          ShowHTML "        <td align=""center""><font size=""2"">" & Replace(Replace(RS("padrao"),"S","Sim"),"N","Não") & "</td>"
          ShowHTML "      </tr>"
          Rs.MoveNext
        wend
     End If
     DesconectaBD
     ShowHTML "         </table></td></tr>"

     'Endereços físicos
     DB_GetAddressList RS, w_sq_cliente, null, "FISICO", null
     RS.Sort = "padrao desc, logradouro"
     ShowHTML "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""2""><b>Endereços Físicos</td>"
     If Rs.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><font  size=""2""><b>Não informado.</b></td></tr>"
     Else
        While Not Rs.EOF
          ShowHTML "      <tr><td align=""center"" colspan=""2""><TABLE WIDTH=""100%"" BORDER=""0"" CELLSPACING=""0"" CELLPADDING=""0"">"
          ShowHTML "          <tr><td colspan=2><font size=""2""><b>" & RS("endereco") & "</font></td>"
          ShowHTML "          <tr><td width=""5%"" rowspan=4><td valign=""top""><font size=""2"">Logradouro:<br><b>" & RS("logradouro") & "</font></td></tr>"
          ShowHTML "          <tr><td valign=""top""><table border=0 width=""100%"" cellspacing=0>"
          ShowHTML "              <td valign=""top""><font size=""2"">Complemento:<br><b>" & Nvl(RS("complemento"),"---") & " </b></td>"
          ShowHTML "              <td valign=""top""><font size=""2"">Bairro:<br><b>" & Nvl(RS("bairro"),"---") & " </b></td>"
          ShowHTML "              <td valign=""top""><font size=""2"">CEP:<br><b>" & Nvl(RS("cep"),"---") & " </b></td>"
          ShowHTML "              </table>"
          ShowHTML "          <tr><td valign=""top""><table border=0 width=""100%"" cellspacing=0>"
          ShowHTML "              <td valign=""top""><font size=""2"">País:<br><b>" & RS("pais") & " </b></td>"
          ShowHTML "              <td><font size=""2"">Padrão?<br><b>" & Replace(Replace(RS("padrao"),"S","Sim"),"N","Não") & "</font></td>"
          ShowHTML "              </table>"
          ShowHTML "          </table></td></tr>"
          Rs.MoveNext
        wend
     End If
     DesconectaBD

     'Telefones
     DB_GetFoneList RS, w_sq_cliente, null, null, null
     RS.Sort = "tipo_telefone, cidade, padrao desc, numero"
     ShowHTML "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""2""><b>Telefones</td>"
     ShowHTML "      <tr><td align=""center"" colspan=""2"">"
     ShowHTML "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     ShowHTML "            <td><font size=""2""><b>Tipo</font></td>"
     ShowHTML "            <td><font size=""2""><b>DDD</font></td>"
     ShowHTML "            <td><font size=""2""><b>Número</font></td>"
     ShowHTML "            <td><font size=""2""><b>Cidade</font></td>"
     ShowHTML "            <td><font size=""2""><b>UF</font></td>"
     ShowHTML "            <td><font size=""2""><b>País</font></td>"
     ShowHTML "            <td><font size=""2""><b>Padrão</font></td>"
     ShowHTML "          </tr>"    
     If Rs.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font  size=""2""><b>Não informado.</b></td></tr>"
     Else
        While Not Rs.EOF
          ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
          ShowHTML "        <td><font size=""1"">" & RS("tipo_telefone") & "</td>"
          ShowHTML "        <td align=""center""><font size=""1"">" & RS("ddd") & "</td>"
          ShowHTML "        <td><font size=""1"">" & RS("numero") & "</td>"
          ShowHTML "        <td><font size=""1"">" & Nvl(RS("cidade"),"---") & "</td>"
          ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS("co_uf"),"---") & "</td>"
          ShowHTML "        <td><font size=""1"">" & Nvl(RS("pais"),"---") & "</td>"
          ShowHTML "        <td align=""center""><font size=""1"">" & Replace(Replace(RS("padrao"),"S","Sim"),"N","Não") & "</td>"
          ShowHTML "      </tr>"
          Rs.MoveNext
        wend
     End If
     DesconectaBD
     ShowHTML "         </table></td></tr>"

     'Contas bancárias
     DB_GetContaBancoList RS, w_sq_cliente, null, null
     RS.Sort = "tipo_conta, padrao desc, banco, numero"
     ShowHTML "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""2""><b>Contas Bancárias</td>"
     ShowHTML "      <tr><td align=""center"" colspan=""2"">"
     ShowHTML "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     ShowHTML "            <td><font size=""2""><b>Tipo</font></td>"
     ShowHTML "            <td><font size=""2""><b>Banco</font></td>"
     ShowHTML "            <td><font size=""2""><b>Agência</font></td>"
     ShowHTML "            <td><font size=""2""><b>Operação</font></td>"
     ShowHTML "            <td><font size=""2""><b>Número</font></td>"
     ShowHTML "            <td><font size=""2""><b>Ativo</font></td>"
     ShowHTML "            <td><font size=""2""><b>Padrão</font></td>"
     ShowHTML "          </tr>"    
     If Rs.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font  size=""2""><b>Não informado.</b></td></tr>"
     Else
        While Not Rs.EOF
          ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
          ShowHTML "        <td><font size=""1"">" & RS("tipo_conta") & "</td>"
          ShowHTML "        <td><font size=""1"">" & RS("banco") & "</td>"
          ShowHTML "        <td><font size=""1"">" & RS("agencia") & "</td>"
          ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS("operacao"),"---") & "</td>"
          ShowHTML "        <td><font size=""1"">" & RS("numero") & "</td>"
          ShowHTML "        <td align=""center""><font size=""1"">" & Replace(Replace(RS("ativo"),"S","Sim"),"N","Não") & "</td>"
          ShowHTML "        <td align=""center""><font size=""1"">" & Replace(Replace(RS("padrao"),"S","Sim"),"N","Não") & "</td>"
          ShowHTML "      </tr>"
          Rs.MoveNext
        wend
     End If
     DesconectaBD
     ShowHTML "         </table></td></tr>"

     'Módulos contratados
     DB_GetSiwCliModLis RS, w_sq_cliente, null, null
     ShowHTML "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""2""><b>Módulos Contratados</td>"
     ShowHTML "      <tr><td align=""center"" colspan=""2"">"
     ShowHTML "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     ShowHTML "             <td><font size=""2""><b>Módulo</font></td>"
     ShowHTML "             <td><font size=""2""><b>Sigla</font></td>"
     ShowHTML "             <td><font size=""2""><b>Objetivo geral</font></td>"
     ShowHTML "          </tr>"    
     If Rs.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font  size=""2""><b>Não informado.</b></td></tr>"
     Else
        While Not Rs.EOF
          ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
          ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
          ShowHTML "        <td align=""center""><font size=""1"">" & RS("sigla") & "</td>"
          ShowHTML "        <td><font size=""1"">" & RS("objetivo_geral") & "</td>"
          ShowHTML "      </tr>"
          Rs.MoveNext
        wend
     End If
     DesconectaBD
     ShowHTML "         </table></td></tr>"

     'Usuários cadastrados
     DB_GetUserList RS, w_sq_cliente, null, null, null, null, null, null, "S", null
     ShowHTML "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""2""><b>Usuários Cadastrados</td>"
     ShowHTML "      <tr><td align=""center"" colspan=""2"">"
     ShowHTML "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     ShowHTML "            <td><font size=""2""><b>Username</font></td>"
     ShowHTML "            <td><font size=""2""><b>Nome</font></td>"
     ShowHTML "            <td><font size=""2""><b>Lotação</font></td>"
     ShowHTML "            <td><font size=""2""><b>Ramal</font></td>"
     ShowHTML "            <td><font size=""2""><b>Vínculo</font></td>"
     ShowHTML "            <td><font size=""2""><b>Ativo</font></td>"
     ShowHTML "          </tr>"
     If Rs.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font  size=""2""><b>Não informado.</b></td></tr>"
     Else
        While Not Rs.EOF
          ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
          ShowHTML "        <td align=""center"" nowrap><font  size=""1"">" & RS("username") & "</td>"
          ShowHTML "        <td title=""" & RS("nome") & """><font  size=""1"">" & RS("nome_resumido") & "</td>"
          ShowHTML "        <td><font  size=""1"">" & RS("lotacao") & "&nbsp;(" & RS("localizacao") & ")</td>"
          ShowHTML "        <td align=""center""><font  size=""1"">&nbsp;" & Nvl(RS("ramal"),"---") & "</td>"
          ShowHTML "        <td><font  size=""1"">&nbsp;" & Nvl(RS("vinculo"),"---") & "</td>"
          ShowHTML "        <td align=""center""><font  size=""1"">&nbsp;" & Replace(Replace(RS("ativo"),"S","Sim"),"N","Não") & "</td>"
          ShowHTML "      </tr>"
          Rs.MoveNext
        wend
     End If
     DesconectaBD
     ShowHTML "         </table></td></tr>"

     'Configuração da aplicação
     DB_GetCustomerData RS, w_sq_cliente
     ShowHTML "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""2""><b>Configuração da Aplicação</td>"
     ShowHTML "      <tr><td align=""center"" colspan=""2"">"
     ShowHTML "        <TABLE WIDTH=""100%"">"
     ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
     ShowHTML "             <td><font size=""2"">Servidor SMTP:<br><b>" & RS("smtp_server") & "</b></font></td>"
     ShowHTML "             <td><font size=""2"">Nome do remetente:<br><b>" & RS("siw_email_nome") & "</b></font></td>"
     ShowHTML "             <td><font size=""2"">Conta do remetente:<br><b>" & RS("siw_email_conta") & "</b></font></td>"
     ShowHTML "          </tr>"    
     ShowHTML "         </table></td></tr>"
     ShowHTML "      <tr><td align=""center"" colspan=""2"">"
     ShowHTML "        <TABLE WIDTH=""100%"">"
     ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
     If RS("logo") > "" Then
        ShowHTML "             <td colspan=3><font size=""2"">Logomarca telas e relatórios:<br><b><img src=""" & LinkArquivo(null, w_sq_cliente, "img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30), null, null, null, "EMBED") & """ border=1></b></font></td>"
                                                                                                            
     Else
        ShowHTML "             <td colspan=3><font size=""2"">Não informado</font></td>"
     End If
     If RS("logo") > "" Then
        ShowHTML "             <td colspan=3><font size=""2"">Logomarca menu:<br><b><img src=""" & LinkArquivo(null, w_sq_cliente, "img\logo1" & Mid(RS("logo1"),Instr(RS("logo1"),"."),30), null, null, null, "EMBED") & """ border=1></b></font></td>"
     Else
        ShowHTML "             <td colspan=3><font size=""2"">Não informado</font></td>"
     End If
     ShowHTML "          </tr>"    
     ShowHTML "         </table></td></tr>"
     DesconectaBD

     'Funcionalidades
     w_ImagemPadrao         = "images/folder/SheetLittle.gif"
     ShowHTML "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""2""><b>Funcionalidades</td>"
     DB_GetLinkDataUser RS, w_sq_cliente, 0, "IS NULL"
     ShowHTML "      <tr><td align=""center"" colspan=""2"">"
     ShowHTML "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     ShowHTML "            <td><font size=""1""><b>Opção</font></td>"
     ShowHTML "            <td><font size=""1""><b>Link</font></td>"
     ShowHTML "            <td><font size=""1""><b>Sigla</font></td>"
     ShowHTML "            <td><font size=""1""><b>P1</font></td>"
     ShowHTML "            <td><font size=""1""><b>P2</font></td>"
     ShowHTML "            <td><font size=""1""><b>P3</font></td>"
     ShowHTML "            <td><font size=""1""><b>P4</font></td>"
     ShowHTML "            <td><font size=""1""><b>Target</font></td>"
     ShowHTML "            <td><font size=""1""><b>Sub-menu</font></td>"
     ShowHTML "            <td><font size=""1""><b>Ativo</font></td>"
     ShowHTML "          </tr>"
     If Rs.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=10 align=""center""><font  size=""2""><b>Não informado.</b></td></tr>"
     Else
        While Not RS.EOF
           If cDbl(RS("Filho")) > 0 Then
              ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
              ShowHTML "        <td colspan=10><font size=1><img src=""images/folder/FolderClose.gif"" border=0 align=""center""> <b>" & RS("nome")
              DB_GetLinkDataUser RS1, w_sq_cliente, 0, RS("sq_menu")
              While Not RS1.EOF
                 If cDbl(RS1("Filho")) > 0 Then
                    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
                    ShowHTML "        <td colspan=10 nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<font size=1><img src=""images/folder/FolderClose.gif"" border=0 align=""center""> " & RS1("nome")
                    DB_GetLinkDataUser RS2, w_sq_cliente, 0, RS1("sq_menu")
                    While Not RS2.EOF
                       If cDbl(RS2("Filho")) > 0 Then
                          ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
                          ShowHTML "        <td colspan=10 nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=1><img src=""images/folder/FolderClose.gif"" border=0 align=""center""> " & RS2("nome")
                          DB_GetLinkDataUser RS3, w_sq_cliente, 0, RS2("sq_menu")
                          While Not RS3.EOF
                             ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
                             ShowHTML "        <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=1><img src=""" & w_imagem & """ border=0 align=""center""> " & RS3("nome")
                             ShowHTML "        <td title=""" & RS3("link") & """><font size=1> " & Nvl(Mid(RS3("link"),1,30),"-")
                             ShowHTML "        <td><font size=1> " & Nvl(RS3("sigla"),"-")
                             ShowHTML "        <td align=""center""><font size=1> " & Nvl(RS3("p1"),"-")
                             ShowHTML "        <td align=""center""><font size=1> " & Nvl(RS3("p2"),"-")
                             ShowHTML "        <td align=""center""><font size=1> " & Nvl(RS3("p3"),"-")
                             ShowHTML "        <td align=""center""><font size=1> " & Nvl(RS3("p4"),"-")
                             ShowHTML "        <td align=""center""><font size=1> " & Nvl(RS3("target"),"-")
                             ShowHTML "        <td align=""center""><font size=1> " & Replace(Replace(RS3("ultimo_nivel"),"S","Sim"),"N","Não")
                             ShowHTML "        <td align=""center""><font size=1> " & Replace(Replace(RS3("ativo"),"S","Sim"),"N","Não")
                             RS3.MoveNext
                          Wend
                       Else
                          If RS2("IMAGEM") > "" Then
                             w_Imagem = RS2("IMAGEM")
                          Else
                             w_Imagem = w_ImagemPadrao
                          End If
                          ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
                          ShowHTML "        <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=1><img src=""" & w_imagem & """ border=0 align=""center""> " & RS2("nome")
                          ShowHTML "        <td title=""" & RS2("link") & """><font size=1> " & Nvl(Mid(RS2("link"),1,30),"-")
                          ShowHTML "        <td><font size=1> " & Nvl(RS2("sigla"),"-")
                          ShowHTML "        <td align=""center""><font size=1> " & Nvl(RS2("p1"),"-")
                          ShowHTML "        <td align=""center""><font size=1> " & Nvl(RS2("p2"),"-")
                          ShowHTML "        <td align=""center""><font size=1> " & Nvl(RS2("p3"),"-")
                          ShowHTML "        <td align=""center""><font size=1> " & Nvl(RS2("p4"),"-")
                          ShowHTML "        <td align=""center""><font size=1> " & Nvl(RS2("target"),"-")
                          ShowHTML "        <td align=""center""><font size=1> " & Replace(Replace(RS2("ultimo_nivel"),"S","Sim"),"N","Não")
                          ShowHTML "        <td align=""center""><font size=1> " & Replace(Replace(RS2("ativo"),"S","Sim"),"N","Não")
                       End If
                       RS2.MoveNext
                    Wend
                    ShowHTML "   </font></div>"
                 Else
                    If RS1("IMAGEM") > "" Then
                       w_Imagem = RS1("IMAGEM")
                    Else
                       w_Imagem = w_ImagemPadrao
                    End If
                    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
                    ShowHTML "        <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<font size=1><img src=""" & w_imagem & """ border=0 align=""center""> " & RS1("nome")
                    ShowHTML "        <td title=""" & RS1("link") & """><font size=1> " & Nvl(Mid(RS1("link"),1,30),"-")
                    ShowHTML "        <td><font size=1> " & Nvl(RS1("sigla"),"-")
                    ShowHTML "        <td align=""center""><font size=1> " & Nvl(RS1("p1"),"-")
                    ShowHTML "        <td align=""center""><font size=1> " & Nvl(RS1("p2"),"-")
                    ShowHTML "        <td align=""center""><font size=1> " & Nvl(RS1("p3"),"-")
                    ShowHTML "        <td align=""center""><font size=1> " & Nvl(RS1("p4"),"-")
                    ShowHTML "        <td align=""center""><font size=1> " & Nvl(RS1("target"),"-")
                    ShowHTML "        <td align=""center""><font size=1> " & Replace(Replace(RS1("ultimo_nivel"),"S","Sim"),"N","Não")
                    ShowHTML "        <td align=""center""><font size=1> " & Replace(Replace(RS1("ativo"),"S","Sim"),"N","Não")
                 End If
                 RS1.MoveNext
              Wend
              ShowHTML "   </font></div>"
           Else
              If RS("IMAGEM") > "" Then
                 w_Imagem = RS("IMAGEM")
              Else
                 w_Imagem = w_ImagemPadrao
              End If
              ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
              ShowHTML "        <td nowrap><font size=1><img src=""" & w_imagem & """ border=0 align=""center""><b> " & RS("nome")
              ShowHTML "        <td title=""" & RS("link") & """><font size=1> " & Nvl(Mid(RS("link"),1,30),"-")
              ShowHTML "        <td><font size=1> " & Nvl(RS("sigla"),"-")
              ShowHTML "        <td align=""center""><font size=1> " & Nvl(RS("p1"),"-")
              ShowHTML "        <td align=""center""><font size=1> " & Nvl(RS("p2"),"-")
              ShowHTML "        <td align=""center""><font size=1> " & Nvl(RS("p3"),"-")
              ShowHTML "        <td align=""center""><font size=1> " & Nvl(RS("p4"),"-")
              ShowHTML "        <td align=""center""><font size=1> " & Nvl(RS("target"),"-")
              ShowHTML "        <td align=""center""><font size=1> " & Replace(Replace(RS("ultimo_nivel"),"S","Sim"),"N","Não")
              ShowHTML "        <td align=""center""><font size=1> " & Replace(Replace(RS("ativo"),"S","Sim"),"N","Não")
           End If
           RS.MoveNext
        Wend
     End If
     DesconectaBD
     ShowHTML "         </table></td></tr>"
     ShowHTML "     </tr></tr></td></table>"

     ShowHTML "</table>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If

  Set w_erro                = Nothing 
  Set Rsquery               = Nothing
  Set RS1                   = Nothing
  Set RS2                   = Nothing
  Set RS3                   = Nothing
  Set w_ImagemPadrao        = Nothing
  Set w_Imagem              = Nothing

End Sub
REM =========================================================================
REM Fim da visualização dos dados do cliente
REM -------------------------------------------------------------------------

%>

