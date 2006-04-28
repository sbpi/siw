<%
REM =========================================================================
REM Recupera a lista de clientes do SIW
REM -------------------------------------------------------------------------
Sub DB_GetSiwCliList(p_rs, p_pais, p_uf, p_cidade, p_ativo, p_nome)
  Dim l_pais, l_uf, l_cidade, l_ativo, l_nome
  Set l_pais         = Server.CreateObject("ADODB.Parameter")
  Set l_uf           = Server.CreateObject("ADODB.Parameter")
  Set l_cidade       = Server.CreateObject("ADODB.Parameter")
  Set l_ativo        = Server.CreateObject("ADODB.Parameter")
  Set l_nome         = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_pais         = .CreateParameter("l_pais",     adInteger, adParamInput,   , Tvl(p_pais))
     set l_uf           = .CreateParameter("l_uf",       adVarChar, adParamInput,  3, Tvl(p_uf))
     set l_cidade       = .CreateParameter("l_cidade",   adInteger, adParamInput,   , Tvl(p_cidade))
     set l_ativo        = .CreateParameter("l_ativo",    adVarChar, adParamInput,  1, Tvl(p_ativo))
     set l_nome         = .CreateParameter("l_nome",     adVarChar, adParamInput, 60, tvl(p_nome))
     .parameters.Append l_pais
     .parameters.Append l_uf
     .parameters.Append l_cidade
     .parameters.Append l_ativo
     .parameters.Append l_nome
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSiwCliList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_pais"
     .Parameters.Delete         "l_uf"
     .Parameters.Delete         "l_cidade"
     .Parameters.Delete         "l_ativo"
     .Parameters.Delete         "l_nome"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados de um cliente do SIW a partir de seu CNPJ
REM -------------------------------------------------------------------------
Sub DB_GetSiwCliData(p_rs, p_cnpj)
  Dim l_cnpj
  Set l_cnpj        = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cnpj                 = .CreateParameter("l_cnpj",  adVarchar, adParamInput, 18, p_cnpj)
     .parameters.Append         l_cnpj
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSiwCliData"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_cnpj"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

