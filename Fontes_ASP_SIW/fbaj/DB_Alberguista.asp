<%
REM =========================================================================
REM Recupera as pessoas vinculadas a um cliente
REM -------------------------------------------------------------------------
Sub DB_GetAlberList(p_rs, p_carteira, p_nome, p_sexo, p_uf, p_conhece_albergue, p_visitas, _
                    p_classificacao, p_destino, p_motivo_viagem, p_forma_conhece)
  
  Dim l_carteira, l_nome, l_sexo, l_uf, l_conhece_albergue, l_visitas, l_classificacao
  Dim l_destino, l_motivo_viagem, l_forma_conhece
  
  Set l_carteira         = Server.CreateObject("ADODB.Parameter")
  Set l_nome             = Server.CreateObject("ADODB.Parameter")
  Set l_sexo             = Server.CreateObject("ADODB.Parameter")
  Set l_uf               = Server.CreateObject("ADODB.Parameter")
  Set l_conhece_albergue = Server.CreateObject("ADODB.Parameter")
  Set l_visitas          = Server.CreateObject("ADODB.Parameter")
  Set l_classificacao    = Server.CreateObject("ADODB.Parameter")
  Set l_destino          = Server.CreateObject("ADODB.Parameter")
  Set l_motivo_viagem    = Server.CreateObject("ADODB.Parameter")
  Set l_forma_conhece    = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_carteira             = .CreateParameter("l_carteira",           adVarchar, adParamInput, 20, Tvl(p_carteira))
     set l_nome                 = .CreateParameter("l_nome",               adVarChar, adParamInput, 20, Tvl(p_nome))
     set l_sexo                 = .CreateParameter("l_sexo",               adVarChar, adParamInput,  1, Tvl(p_sexo))
     set l_uf                   = .CreateParameter("l_uf",                 adVarChar, adParamInput,  2, Tvl(p_uf))
     set l_conhece_albergue     = .CreateParameter("l_conhece_albergue",   adVarChar, adParamInput,  1, Tvl(p_conhece_albergue))
     set l_visitas              = .CreateParameter("l_visitas",            adInteger, adParamInput,   , Tvl(p_visitas))
     set l_classificacao        = .CreateParameter("l_classificacao",      adVarChar, adParamInput,  1, Tvl(p_classificacao))
     set l_destino              = .CreateParameter("l_destino",            adVarChar, adParamInput,  1, Tvl(p_destino))
     set l_motivo_viagem        = .CreateParameter("l_motivo_viagem",      adVarChar, adParamInput,  1, Tvl(p_motivo_viagem))
     set l_forma_conhece        = .CreateParameter("l_forma_conhece",      adVarChar, adParamInput,  1, Tvl(p_forma_conhece))
     
     .parameters.Append         l_carteira
     .parameters.Append         l_nome
     .parameters.Append         l_sexo
     .parameters.Append         l_uf
     .parameters.Append         l_conhece_albergue
     .parameters.Append         l_visitas
     .parameters.Append         l_classificacao
     .parameters.Append         l_destino
     .parameters.Append         l_motivo_viagem
     .parameters.Append         l_forma_conhece
     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = "FBAJ.SP_GetAlberList"
     
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     
     .Parameters.Delete         "l_carteira"
     .Parameters.Delete         "l_nome"
     .Parameters.Delete         "l_sexo"
     .Parameters.Delete         "l_uf"
     .Parameters.Delete         "l_conhece_albergue"
     .Parameters.Delete         "l_visitas"
     .Parameters.Delete         "l_classificacao"
     .Parameters.Delete         "l_destino"
     .Parameters.Delete         "l_motivo_viagem"
     .Parameters.Delete         "l_forma_conhece"
     
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados de uma pessoa cadastrada no sistema
REM -------------------------------------------------------------------------
Sub DB_GetAlberData(p_rs, p_sq_alberguista, p_carteira)
  Dim l_sq_alberguista, l_carteira
  Set l_sq_alberguista  = Server.CreateObject("ADODB.Parameter")
  Set l_carteira        = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_sq_alberguista       = .CreateParameter("l_sq_alberguista",  adInteger, adParamInput,   , p_sq_alberguista)
     set l_carteira             = .CreateParameter("l_carteira",        adVarchar, adParamInput, 20, Tvl(p_carteira))
     .parameters.Append         l_sq_alberguista
     .parameters.Append         l_carteira
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = "FBAJ.SP_GetAlberData"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_alberguista"
     .Parameters.Delete         "l_carteira"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------


%>

