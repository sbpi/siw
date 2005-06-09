<%
' FunÁ„o para comparaÁ„o de datas
Function fCompData (Date1, DisplayName1, Operator, Date2, DisplayName2)
  Dim w_erro, data1, data2, d1, m1, a1, h1, d2, m2, a2, h2
  
  w_erro = ""
  
  If Len(data1) > 0 and Len(data2) > 0 Then
     If Len(data1) = 17 Then
        d1 = Mid(Date1,1,2)
        m1 = Mid(Date1,4,2)
        a1 = Mid(Date1,7,4)
        h1 = Mid(Date1,13,2) & Mid(Date1,16,2)
        d2 = Mid(Date2,1,2)
        m2 = Mid(Date2,4,2)
        a2 = Mid(Date2,7,4)
        h2 = Mid(Date2,13,2) & Mid(Date2,16,2)
        data1 = a1 & m1 & d1 & h1
        data2 = a2 & m2 & d2 & h2
     ElseIf Len(data1) = 10 Then
        d1 = Mid(Date1,1,2)
        m1 = Mid(Date1,4,2)
        a1 = Mid(Date1,7,4)
        d2 = Mid(Date2,1,2)
        m2 = Mid(Date2,4,2)
        a2 = Mid(Date2,7,4)
        data1 = a1 & m1 & d1
        data2 = a2 & m2 & d2
     ElseIf Len(data1) = 7 Then
        d1 = "01"
        m1 = Mid(Date1,1,2)
        a1 = Mid(Date1,4,4)
        d2 = "01"
        m2 = Mid(Date2,1,2)
        a2 = Mid(Date2,4,4)
        data1 = a1 & m1 & d1
        data2 = a2 & m2 & d2
     End If
     Select Case Operator 
        Case "=" 
           If Not (Data1 = Data2) Then w_erro = DisplayName1 & " deve ser igual a " & DisplayName2 End If
        Case "<>" 
           If Not (Data1 <> Data2) Then w_erro = DisplayName1 & " deve ser diferente de " & DisplayName2 End If
        Case ">"  
           If Not (Data1 > Data2) Then w_erro = DisplayName1 & " deve ser maior que " & DisplayName2 End If
        Case "<"  
           If Not (Data1 < Data2) Then w_erro = DisplayName1 & " deve ser menor que " & DisplayName2 End If
        Case ">=" 
           If Not (Data1 >= Data2) Then w_erro = DisplayName1 & " deve ser maior ou igual a " & DisplayName2 End If
        Case "=>" 
           If Not (Data1 >= Data2) Then w_erro = DisplayName1 & " deve ser maior ou igual a " & DisplayName2 End If
        Case "<=" 
           If Not (Data1 <= Data2) Then w_erro = DisplayName1 & " deve ser menor ou igual a " & DisplayName2 End If
        Case "=<" 
           If Not (Data1 <= Data2) Then w_erro = DisplayName1 & " deve ser menor ou igual a " & DisplayName2 End If
     End Select
  End IF

  fCompData = w_erro
  
  Set w_erro    = Nothing 
  Set data1     = Nothing 
  Set data2     = Nothing 
  Set d1        = Nothing 
  Set m1        = Nothing 
  Set a1        = Nothing 
  Set h1        = Nothing 
  Set d2        = Nothing 
  Set m2        = Nothing 
  Set a2        = Nothing 
  Set h2        = Nothing
End Function

' FunÁ„o auxiliar para verificaÁ„o de datas
Function fcheckbranco(elemento)
  Dim flagbranco, i
  
  flagbranco = True

  For i=1 to Len(elemento)
     If Mid(elemento,i) <> " " Then flagbranco = False End If
  Next
  
  fcheckbranco = flagbranco

  Set flagbranco = Nothing
  Set i          = Nothing
End Function

' FunÁ„o para c·lculo de mÛdulo
Function fModulo (dividendo, divisor)
  Dim quociente, ModN
  
  quociente = 0
  ModN      = 0
  
  quociente = Fix(dividendo/divisor)
  ModN = dividendo - (divisor*quociente)
  fModulo = divisor - ModN
  
  Set quociente = Nothing
  Set ModN      = Nothing

End Function

Function fValidate(Tipo, Value, DisplayName, DataType, ValueRequired, MinimumLength, MaximumLength, AllowLetters, AllowDigits)
  Dim checkOK, w_erro, i, ch, D1, D2, soma, checkStr, igual, allValid
  Dim err, dia, mes, ano, barra1, barra2, psj
  
  ' Tipo = 0 -> retorna o erro assim que encontr·-lo. O retorno conter· o primeiro erro encontrado.
  ' Tipo = 1 -> retorna o erro somente no final da rotina. O retorno conter· todos os erros encontrados.

  w_erro = ""

  If ValueRequired > "" Then
    If Nvl(Value,"nulo") = "nulo" Then w_erro = "; campo obrigatÛrio deve conter valor" End If
  End If
  If w_erro > "" and Tipo = 0 Then 
     fValidate = replace(w_erro,"; ","")
     return
  End If

  IF MinimumLength > "" Then
    If Len(Value) < MinimumLength and Value > "" Then w_erro = w_erro & "; tamanho mÌnimo È de " & MinimumLength & " posiÁıes" End If
  End If
  If w_erro > "" and Tipo = 0 Then 
     fValidate = replace(w_erro,"; ","")
     return
  End If

  IF MaximumLength > "" Then
    If Len(Value) > MaximumLength and Value > "" Then w_erro = w_erro & "; tamanho m·ximo È de " & MaximumLength & " posiÁıes" End If
  End If
  If w_erro > "" and Tipo = 0 Then 
     fValidate = replace(w_erro,"; ","")
     return
  End If

  If AllowLetters > "" OR AllowDigits > "" Then
    checkOK = ""
    If AllowLetters > "" Then
      If AllowLetters = "1" Then
        checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz¿¡¬√«»… ÃÕŒ“”‘’Ÿ⁄€‹‡·‚„ÁÈÍÌÓÛÙı˙˚0123456789-,.()-:;[]{}*&%$#@!/∫™?<>|+=_\""\' "
      Else
        checkOK = checkOK & AllowLetters
      End If   
    End If
    If AllowDigits > "" Then
      If AllowDigits = "1" Then checkOK = checkOK & "0123456789-.,/: " Else checkOK = checkOK & AllowDigits End If   
    End If
    For i = 1 to Len(Value)
        ch = Mid(Value,i,1)
        If Instr(checkOK, ch) = 0 and asc(ch) <> 13 and asc(ch) <> 10 Then 
           w_erro = w_erro & "; caracteres inv·lidos no campo" 
           Exit For
        End If
    Next
  End If
  If w_erro > "" and Tipo = 0 Then 
     fValidate = replace(w_erro,"; ","")
     return
  End If
  
  If uCase(DataType) = "CGC" or uCase(DataType) = "CNPJ" Then
    checkOK = ""
    allValid = true
    D1       = 0
    D2       = 0
    checkStr = replace(replace(replace(Value,".",""),"/",""),"-","")
    
    soma = 0
    For i = 2 to 13
       If i <= 5 Then soma = soma + (Mid(checkStr,i-1,1) * (7-i)) Else soma = soma + (Mid(checkStr,i-1,1) * (15-i)) End If
    Next
    D1 = fmodulo(soma,11)
    If (D1 > 9) Then D1 = 0 End If
    
    soma = 0
    For i = 2 to 14
       If i <= 6 Then soma = soma + (Mid(checkStr,i-1,1) * (8-i)) Else soma = soma + (Mid(checkStr,i-1,1) * (16-i)) End If
    Next
    D2 = fmodulo(soma,11)
    If (D2 > 9) Then D2 = 0 End If
    If cDbl(D1) <> cDbl(Mid(checkStr,13,1)) or cDbl(D2) <> cDbl(Mid(checkStr,14,1)) Then w_erro = w_erro & "; dÌgito verificador inv·lido" End If
    If w_erro > "" and Tipo = 0 Then 
       fValidate = replace(w_erro,"; ","")
       return
    End If
  ElseIf uCase(DataType) = "CPF" Then
    checkOK = ""
    allValid = true
    D1       = 0
    D2       = 0
    checkStr = replace(replace(Value,".",""),"-","")
    
    soma  = 0
    igual = 0
    For i = 2 to 10
       soma = soma + (Mid(checkStr,i-1,1) * (12-i)) 
       If Mid(checkStr,i,1) <> Mid(checkStr,i-1,1) Then igual = 1 End If
    Next
    D1 = fModulo(soma,11)
    If (D1 > 9) Then D1 = 0 End If
    If igual = 0 Then w_erro = w_erro & "; CPF inv·lido" End If
    If w_erro > "" and Tipo = 0 Then 
       fValidate = replace(w_erro,"; ","")
       return
    End If
    
    soma = 0
    For i = 3 to 11
       soma = soma + (Mid(checkStr,i-1,1) * (13-i)) 
       If Mid(checkStr,i,1) <> Mid(checkStr,i-1,1) Then igual = 1 End If
    Next
    D2 = fModulo(soma,11)
    If (D2 > 9) Then D1 = 0 End If
    
    If cDbl(D1) <> cDbl(Mid(checkStr,10,1)) or cDbl(D2) <> cDbl(Mid(checkStr,11,1)) Then w_erro = w_erro & "; dÌgito verificador inv·lido" End If
    If w_erro > "" and Tipo = 0 Then 
       fValidate = replace(w_erro,"; ","")
       return
    End If
  ElseIf uCase(DataType) = "DATA" Then
	err=0
	psj=0
	If Len(Value) > 0 Then
	   If Not fcheckbranco(Value) Then
	      If Len(Value) <> 10 Then
	         w_erro = 1
	      Else
	         dia    = Mid(Value,1,2)
	         barra1 = Mid(Value,3,1)
	         mes    = Mid(Value,4,2)
	         barra2 = Mid(Value,6,1)
	         ano    = Mid(Value,7,4)
	         ' verificaÁıes b·sicas
	         If (mes < 1 or mes > 12) or _
	            (barra1 <> "/") or _
	            (dia < 1 or dia > 31) or _
	            (barra2 <> "/") or _
	            (ano < 1900 or ano > 2900) _
	         Then
	            err = 1
	         End If
	         ' verificaÁıes avanÁadas
	         ' mÍs com 30 dias
	         If (mes =4 or mes=6 or mes=9 or mes=11) and (dia = 31) Then err = 1 End If
	         ' fevereiro e ano bissexto
	         If (mes=2) Then
	            If Int(ano/4) = (ano/4) Then
	               If (dia > 29) Then err = 1 End If
	            Else
	               If (dia > 28) Then err = 1 End If
	            End If
	         End If
	      End If
	   Else
	      err = 1
	   End IF
	End If
    If err = 1 Then w_erro = w_erro & "; data inv·lida" End If
    If w_erro > "" and Tipo = 0 Then 
       fValidate = replace(w_erro,"; ","")
       return
    End If
  ElseIf uCase(DataType) = "DATADM" Then
	err=0
	psj=0
	If Len(Value) > 0 Then
	   If Not fcheckbranco(Value) Then
	      If Len(Value) <> 5 Then
	         w_erro = 1
	      Else
	         dia    = Mid(Value,1,2)
	         barra1 = Mid(Value,3,1)
	         mes    = Mid(Value,4,2)
	         ' verificaÁıes b·sicas
	         If (mes < 1 or mes > 12) or _
	            (barra1 <> "/") or _
	            (dia < 1 or dia > 31) _
	         Then
	            err = 1
	         End If
	         ' verificaÁıes avanÁadas
	         ' mÍs com 30 dias
	         If (mes =4 or mes=6 or mes=9 or mes=11) and (dia = 31) Then err = 1 End If
	         ' fevereiro - como o ano n„o È informado, fevereiro sÛ pode ter 28 dias
	         If (mes=2 and dia > 28) Then err = 1 End If
	      End If
	   Else
	      err = 1
	   End IF
	End If
    If err = 1 Then w_erro = w_erro & "; data inv·lida" End If
    If w_erro > "" and Tipo = 0 Then 
       fValidate = replace(w_erro,"; ","")
       return
    End If
  ElseIf uCase(DataType) = "DATAMA" Then
	err=0
	psj=0
	If Len(Value) > 0 Then
	   If Not fcheckbranco(Value) Then
	      If Len(Value) <> 7 Then
	         w_erro = 1
	      Else
	         mes    = Mid(Value,1,2)
	         barra1 = Mid(Value,3,1)
	         ano    = Mid(Value,4,4)
	         ' verificaÁıes b·sicas
	         If (mes < 1 or mes > 12) or _
	            (barra1 <> "/") or _
	            (ano < 1900 or ano > 2900) _
	         Then
	            err = 1
	         End If
	      End If
	   Else
	      err = 1
	   End IF
	End If
    If err = 1 Then w_erro = w_erro & "; data inv·lida" End If
    If w_erro > "" and Tipo = 0 Then 
       fValidate = replace(w_erro,"; ","")
       return
    End If
  End If
  
  fValidate = Mid(w_erro,3, Len(w_erro))

  Set allValid  = Nothing 
  Set checkOK   = Nothing 
  Set w_erro    = Nothing 
  Set i         = Nothing 
  Set ch        = Nothing 
  Set D1        = Nothing 
  Set D2        = Nothing 
  Set soma      = Nothing 
  Set checkStr  = Nothing 
  Set igual     = Nothing
  Set err       = Nothing 
  Set dia       = Nothing 
  Set mes       = Nothing 
  Set ano       = Nothing 
  Set barra1    = Nothing 
  Set barra2    = Nothing 
  Set psj       = Nothing
End Function
%>