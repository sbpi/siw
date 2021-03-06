alter function dbo.Criptografia(@TextoOriginal Varchar(255)) 
returns varchar(4000) as
begin
  Declare @Result           VarChar(4000)
  Declare @w_Resultado      VarChar(4000)
  Declare @w_Cifra1         VarChar(4000)
  Declare @w_Cifra2         VarChar(4000)
  Declare @w_Caracter       VarChar(1)
  Declare @w_Asc            VarChar(4000)
  Declare @w_ValorPar       Varchar(1)
  Declare @w_Contador1      Int
  Declare @w_Contador2      Int
  Declare @w_Relacionamento Varchar(4000)
  Declare @w_Mod            Int
  Declare @w_Pq             BigInt
  
  Set @Result = Null
  Set @w_Mod  = 555555
  Set @w_Relacionamento  = '|Z-020'+'|Y-009'+'|W-002'+'|V-003'+'|U-004'+'|T-007'+'|S-006'+
                           '|R-005'+'|Q-008'+'|P-001'+'|O-010'+'|N-011'+'|M-012'+'|L-013'+
                           '|K-014'+'|J-015'+'|I-016'+'|H-017'+'|G-018'+'|F-019'+'|E-099'+
                           '|D-021'+'|C-022'+'|B-023'+'|A-024'+'|X-025'+'|z-309'+'|y-309'+
                           '|w-302'+'|v-303'+'|u-304'+'|t-307'+'|s-306'+'|r-305'+'|q-308'+
                           '|p-301'+'|o-310'+'|n-311'+'|m-312'+'|l-313'+'|k-314'+'|j-315'+
                           '|y-316'+'|h-317'+'|g-318'+'|f-319'+'|e-399'+'|d-321'+'|c-322'+
                           '|b-323'+'|a-324'+'|x-325'+'|0-109'+'|1-108'+'|2-107'+'|3-106'+
                           '|4-105'+'|5-104'+'|6-103'+'|7-102'+'|8-101'+'|9-100'+'|--219'+
                           '|+-218'+'|~-217'+'|#-216'+'|*-215'+'|(-214'+'|(-213'+'|)-212'+
                           '|{-211'+'|}-210'+'|[-209'+'|]-208'+'+-207'+'|\-206'+'|/-205'+
                           '|,-204'+'|.-203'+'|:-202'+'|;-201'+'|$-200'+'|#-220'+'|@-221'+
                           '|!-222'+'|&-224'+'|á-401'+'|é-402'+'|í-403'+'|ú-404'+'|ó-405'+
                           '|Á-406'+'|É-407'+'|Í-408'+'|Ú-409'+'|Ó-410'+'|ç-411'+'|Ç-412'+
                           '|ã-413'+'|Ã-414'+'|ê-417'+'|Ê-418'+'|õ-415'+'|Õ-416'
  Set @w_Cifra1    = ''
  Set @w_contador1 = 1

  While @w_contador1 <= Len(@TextoOriginal)
  Begin
     Set @w_Caracter = Substring(@TextoOriginal, @w_Contador1, 1)
     if CharIndex('|'+@w_Caracter+'-', @w_Relacionamento) > 0
        Set @w_Asc = substring(@w_Relacionamento, CharIndex('|'+@w_Caracter+'-', @w_Relacionamento)+3, 3)
     else
        Set @w_Asc = '999'

     Set @w_Cifra1    = @w_Cifra1 + @w_Asc
     Set @w_contador1 = @w_contador1 + 1
  End  

  Set @w_Contador2 = 1
  Set @w_Cifra2    = ''
  While @w_Contador2 < 1000
  Begin
     Set @w_Pq = IsNull(Substring(@w_Cifra1, @w_Contador2, 6),0)

     If len(Substring(@w_Cifra1, @w_Contador2, 6)) = 3
        Set @w_Pq = IsNull(Substring(@w_Cifra1, @w_Contador2, 6)+'999', 0)
     Set @w_Resultado = Replace(Str((@w_Pq * @w_Pq * @w_Pq) % @w_Mod, 8, 0), ' ','0')
     Set @w_Cifra2    = @w_Cifra2 + RTrim(LTrim(@w_Resultado))
     Set @w_Contador2 = @w_Contador2 + 6
     
     If (@w_Pq = 0) or (@w_Contador2 >=  len(@w_Cifra1)) Break
  End
  Set @Result = @w_Cifra2

  return(@Result)

end
