<%
REM =========================================================================
REM Rotina de visualização do Perfil
REM -------------------------------------------------------------------------

Sub VisualPerfil(p_numero)

DIM w_atual


  ' Exibição da parte inicial da página
  ShowHTML "<table width=""100%"" border=""0"" bgcolor=""#F7F7F7"" cellspacing=""2"" cellpadding=""0"">"

  ' Exibição da tela de dados gerais
  SQL = "select a.nome,a.codigo, a.co_grupo_atividade,a.co_faixa,a.handle_projeto, d.nome nm_projeto, " & VbCrLf & _
        "       seguranca.fvalor(a.remuneracao_base) b_base, " & VbCrLf & _
        "       seguranca.fvalor(a.remuneracao_minima) b_minima, " & VbCrLf & _
        "       seguranca.fvalor(a.remuneracao_maxima) b_maxima, " & VbCrLf & _
        "       a.simulacao,seguranca.fvalor(a.pontuacao_minima,1) b_pontuacao_minima, " & VbCrLf & _
        "       c.nome nm_modalidade, v.nome nm_grupo, g.nome nm_faixa " & VbCrLf & _
        " from rh_perfil                           a " & VbCrLf & _
        "      inner      join rh_grupo_atividade  v on (a.sq_tipo_vinculo    = v.sq_tipo_vinculo and " & VbCrLf & _
        "                                                a.co_grupo_atividade = v.co_grupo_atividade) " & VbCrLf & _
        "      inner      join corporativo.ct_cc   b on (a.handle_projeto     = b.handle) " & VbCrLf & _
        "      inner      join sg_tipo_vinculo     c on (a.sq_tipo_vinculo    = c.sq_tipo_vinculo) " & VbCrLf & _
        "      inner      join corporativo.ct_cc   d on (a.handle_projeto     = d.handle) " & VbCrLf & _
        "      inner      join rh_grupo_faixa      g on (a.sq_tipo_vinculo    = g.sq_tipo_vinculo and " & VbCrLf & _
        "                                                a.co_grupo_atividade = g.co_grupo_atividade and " & VbCrLf & _
        "                                                a.co_faixa           = g.co_faixa) " & VbCrLf & _
        " where a.sq_perfil = " & p_numero & " " & VbCrLf
  ' Conecta com o banco de dados e Imprime o Resultado
  ConectaBD
  ShowHTML "      <tr><td colspan=""4"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""1""><b>Dados gerais</b></font></td></tr>"
  ShowHTML "      <tr><td><font size=""1"">Código do processo seletivo:<br><b>" & RS("codigo") & "</b></font></td>"
  ShowHTML "          <td colspan=""2""><font size=""1"">Nome do posto:<br><b>" & RS("nome") & "</b></font></td></tr>"
  ShowHTML "      <tr><td colspan=""2""><font size=""1"">Projeto<br><b>" & RS("nm_projeto") & "</b></font></td>"
  ShowHTML "      <tr><td><font size=""1"">Grupo:<br><b>" & RS("nm_grupo") & "</b></font></td>"
  ShowHTML "          <td><font size=""1"">Faixa:<br><b>" & RS("nm_faixa") & "</b></font></td>"
  ShowHTML "          <td><font size=""1"">Pontuação mínima:<br><b>" & RS("b_pontuacao_minima") & "</b></font></td>" 
  DesconectaBD

  ' Exibição da tela de dados adicionais
  'ShowHTML "      <table width=""100%"" border=""0"">"
  ShowHTML "      <tr><td colspan=""4"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""1""><b>Dados adicionais</b></font></td></tr>"

  SQL = "select a.atribuicoes, a.habilidades, b.sexo, b.obrigatorio, " & VbCrLf & _
        "       a.exige_conta_bancaria, a.exige_beneficios, a.exige_dependentes, a.exige_historico, " & VbCrLf & _
        "       c.minimo, c.maximo, c.obrigatorio obriga_faixa " & VbCrLf & _
        " from rh_perfil                              a " & VbCrLf & _
        "      left outer join rh_perfil_sexo         b on (a.sq_perfil = b.sq_perfil) " & VbCrLf & _
        "      left outer join rh_perfil_faixa_etaria c on (a.sq_perfil = b.sq_perfil) " & VbCrLf & _
        " where a.sq_perfil          = " & p_numero
        
  ConectaBD
  ShowHTML "      <tr><td colspan=""4""><font size=""1"">Atribuições:<br><b>" & RS("atribuicoes") & "</b></font></td></tr>"
  ShowHTML "      <tr><td colspan=""4""><font size=""1"">Habilidades:<br><b>" & RS("habilidades") & "</b></font></td></tr>"
  ShowHTML "      <tr><td colspan=""4"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""1""><b>Restrições quanto à idade, sexo e estado civil</b></font></td>"
  ShowHTML "      <tr><td colspan=""4""><font size=""1"">"
  If RS("minimo") > "" and RS("obriga_faixa")="S" Then
     ShowHTML "          <li>Faixa etária:<b>" & RS("minimo") & " à " & RS("maximo") & " anos</b>"
  Else
     ShowHTML "          <li>Faixa etária:<b>sem restrição</b>"
  End If 
  
  If RS("obrigatorio")= "S" Then
     If RS("sexo") = "F" Then
        ShowHTML "          <li>Sexo: <b>somente feminino</b>"
     Else
        ShowHTML "          <li>Sexo: <b>somente masculino</b>"
     End If
  Else
     ShowHTML "          <li>Sexo:<b>sem restrição</b>"
  End If

  ' Exibição da tela de estados civis
  SQL = "select e.nome,decode(c.obrigatorio,'S','SIM','NÃO') obriga_civil, c.sq_estado_civil,p.sq_perfil " & VbCrLf & _
        "  from rh_perfil_estado_civil     c " & VbCrLf & _
        "       inner join co_estado_civil e on (e.sq_estado_civil = c.sq_estado_civil) " & VbCrLf & _
        "       inner join rh_perfil       p on (p.sq_perfil       = c.sq_perfil)" & VbCrLf & _
        " where c.obrigatorio = 'S' " & VbCrLf & _
        "   and p.sq_perfil = " & p_numero & " " & VbCrLf & _
        "order by c.sq_estado_civil " & VbCrLf
  ConectaBD
  ShowHTML "          <li>Estado(s) civil(is):"
  If RS.EOF Then
     ShowHTML " <b>Sem restrição</b>"
  Else
     While Not RS.EOF
        Select case RS("sq_estado_civil")
           Case 1 ShowHTML " <b>Solteiro</b>/&nbsp;"
           Case 2 ShowHTML " <b>Viúvo</b>/&nbsp;"
           Case 3 ShowHTML " <b>Casado</b>/&nbsp;"
           Case 4 ShowHTML " <b>Separado judicialmente</b>/&nbsp;"
           Case 5 ShowHTML " <b>Divorciado</b>/&nbsp;"
        End Select
        RS.MoveNext
     Wend 
  End If
  DesconectaBD
  ShowHTML" </td>"

  ' Exibição da tela de pontuação
  ShowHTML "      <tr><td colspan=""4"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""1""><b>Pontuação</b></font></td></tr>"
  
  SQL = "select a.co_grupo_categoria, a.co_categoria, a.nome nm_categoria, " & VbCrLf & _
        "       c.sq_tipo_vinculo, c.co_grupo_atividade, seguranca.fvalor(c.pontuacao_minima,1) pt_min, " & VbCrLf & _
        "       d.requisito_basico, " & VbCrLf & _
        "       p.sq_perfil_pontuacao,p.sq_perfil,p.sq_requisito, " & VbCrLf & _
        "       p.co_grupo_atividade,p.co_faixa,nvl(seguranca.fvalor(p.pontuacao_perfil,1),0) b_perfil, " & VbCrLf & _
        "       nvl(seguranca.fvalor(p.pontuacao_final,1),0) b_final,p.obrigatorio, " & VbCrLf & _
        "       r.nome, r.curso_tecnico, r.experiencia, r.tempo_minimo_anos, r.idioma, r.escolaridade, r.conhecimento_especifico " & VbCrLf & _
        "    from rh_perfil_pontuacao              p " & VbCrLf & _
        "         inner   join rh_requisito        r on (p.sq_requisito = r.sq_requisito) " & VbCrLf & _
        "           inner join rh_categoria        a on (r.co_grupo_categoria = a.co_grupo_categoria and " & VbCrLf & _
        "                                                r.co_categoria       = a.co_categoria) " & VbCrLf & _
        "           inner join rh_perfil           b on (p.sq_perfil          = b.sq_perfil) " & VbCrLf & _
        "           inner join rh_grupo_faixa      c on (b.sq_tipo_vinculo    = c.sq_tipo_vinculo and " & VbCrLf & _
        "                                                b.co_grupo_atividade = c.co_grupo_atividade and " & VbCrLf & _
        "                                                b.co_faixa           = c.co_faixa) " & VbCrLf & _
        "           inner join rh_requisito_pontos d on (r.sq_requisito       = d.sq_requisito and " & VbCrLf & _
        "                                                b.sq_tipo_vinculo    = d.sq_tipo_vinculo and " & VbCrLf & _
        "                                                b.co_grupo_atividade = d.co_grupo_atividade and " & VbCrLf & _
        "                                                b.co_faixa           = d.co_faixa) " & VbCrLf & _
        " where p.sq_perfil = " & p_numero & " " & VbCrLf & _
        "order by a.co_grupo_categoria, a.co_categoria, a.nome, r.nome" & VbCrLf

  ConectaBD
  ShowHTML "<tr><td align=""center"" colspan=""4"">"
  ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""#F7F7F7"" BORDER=""0"" CELLSPACING=""0"" CELLPADDING=""0"">"
  ShowHTML "        <tr bgcolor="""" align=""center"">"    
  ShowHTML "          <td><font size=""1""><b>Requisito</b></font></td>"
  ShowHTML "          <td colspan=""3""><font size=""1""><b>Pontuação<br>Requisito</b></font></td>"
  ShowHTML "        </tr>"
  If RS.EOF Then
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=4 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></font></td></tr>"
  Else
    w_atual = ""
    While Not RS.EOF
      If w_atual <> RS("co_categoria") Then
         ShowHTML "      <tr bgcolor=""#D0D0D0""><td colspan=4><font size=""1""><b>" & RS("nm_categoria") & "</b></font></td>"
         w_atual = RS("co_categoria")
      End If
      ShowHTML "      <tr bgcolor=""#FFFFFF"">"
      ShowHTML "        <td><font size=""1"">" & RS("nome") & "</font></td>"
      ShowHTML "        <td align=""center"" colspan=""3"" ><font size=""1"">" & RS("b_final")
      If RS("requisito_basico") = "S" or RS("obrigatorio") = "S" Then ShowHTML "*</font>" End If
      ShowHTML "        </td>"
      ShowHTML "      </tr>"
      RS.MoveNext
    wend
  End If
  ShowHTML "    </table>"
  ShowHTML "  </td>"
  ShowHTML "</tr>"
  ShowHTML "      <tr><td colspan=""4""><font size=""1""><b>(*) Requisito obrigatório - o candidato deve cumpri-lo para ser classificado.</b></font></td>"
  ShowHTML "      </tr></table>"
  DesconectaBD   
End Sub
REM =========================================================================
REM Fim da tela de inclusão de solicitações
REM -------------------------------------------------------------------------
%>