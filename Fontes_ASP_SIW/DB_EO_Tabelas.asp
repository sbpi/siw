<%
REM =========================================================================
REM Recupera os feriado a partir dos paramentros informados
REM -------------------------------------------------------------------------
Sub DB_GetFeriado(p_rs, p_cliente, p_cidade, p_chave, p_data, p_nome, p_tipo)
  Dim l_cliente, l_cidade, l_chave, l_data, l_nome, l_tipo
  Set l_cliente = Server.CreateObject("ADODB.Parameter")
  Set l_cidade  = Server.CreateObject("ADODB.Parameter")
  Set l_chave   = Server.CreateObject("ADODB.Parameter")
  Set l_data    = Server.CreateObject("ADODB.Parameter")
  Set l_nome    = Server.CreateObject("ADODB.Parameter")
  Set l_tipo    = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente        = .CreateParameter("l_cliente",   adInteger,  adParamInput,   , p_cliente)
     set l_cidade         = .CreateParameter("l_cidade",    adInteger,  adParamInput,   , Tvl(p_cidade))
     set l_chave          = .CreateParameter("l_chave",     adInteger,  adParamInput,   , Tvl(p_chave))
     set l_data           = .CreateParameter("l_data",      adDate,     adParamInput,   , Tvl(p_data))
     set l_nome           = .CreateParameter("l_nome",      adVarchar,  adParamInput, 60, Tvl(p_nome))
     set l_tipo           = .CreateParameter("l_tipo",      adVarchar,  adParamInput, 30, Tvl(p_tipo))
     .parameters.Append         l_cliente
     .parameters.Append         l_cidade
     .parameters.Append         l_chave
     .parameters.Append         l_data
     .parameters.Append         l_nome
     .parameters.Append         l_tipo
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetFeriado"
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_cidade"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_data"
     .Parameters.Delete         "l_nome"
     .Parameters.Delete         "l_tipo"
  end with
End Sub

REM =========================================================================
REM Recupera os dados do tipo da unidade
REM -------------------------------------------------------------------------
Sub DB_GetUnitTypeData(p_rs, p_sq_tipo_unidade)
  Dim l_sq_tipo_unidade
  Set l_sq_tipo_unidade = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_sq_tipo_unidade           = .CreateParameter("l_sq_tipo_unidade", adInteger, adParamInput, , p_sq_tipo_unidade)
     .parameters.Append         l_sq_tipo_unidade
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetUnitTypeData"
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_tipo_unidade"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados da сrea de atuaчуo
REM -------------------------------------------------------------------------
Sub DB_GetEOAAtuacData(p_rs, p_sq_area_atuacao)
  Dim l_sq_area_atuacao
  Set l_sq_area_atuacao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_sq_area_atuacao           = .CreateParameter("l_sq_area_atuacao", adInteger, adParamInput, , p_sq_area_atuacao)
     .parameters.Append         l_sq_area_atuacao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetEOAAtuacData"
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_area_atuacao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>