create or replace function VerificaDataEspecial 
   (p_data    in Date,
    p_cliente in number   default null,
    p_pais    in number   default null,
    p_uf      in varchar2 default null,
    p_cidade  in number   default null
   ) return varchar2 is
/**********************************************************************************
* Finalidade: Verificar o expediente na data e no local informado
* Autor     : Alexandre Vinhadelli Papad�polis
* Data      :  28/07/2005, 15:30
*
* Par�metros:
*    p_data    : data a ser testada
*    p_cliente : opcional, testa datas especiais para a organiza��o informada. Neste
*                caso, pais, uf e cidade s�o desconsiderados
*    p_pais    : opcional, testa datas especiais para o pa�s informado
*    p_uf      : opcional, testa datas especiais para o estado informado. Neste caso
*                � obrigat�rio informar tamb�m o pa�s
*    p_cidade  : opcional, testa datas especiais para a cidade informada. Neste caso
*                � obrigat�rio informar tamb�m o pa�s e o estado
*
* Retorno:       N: sem expediente
*                S: expediente normal
*                M: expediente somente pela manh�
*                T: expediente somente � tarde
*
* Observa��es:
* 1. s�bados e domingos nunca t�m expediente.
* 2. se apenas a data for informada, ser�o tratados apenas as datas especiais com 
*    abrang�ncia internacional, nacional ou da organiza��o.
***********************************************************************************/
  
  Result varchar2(1) := 'S';
  w_reg  number(18);
  w_data varchar2(10) := to_char(p_data,'dd/mm/yyyy');
begin
  -- Se for s�bado ou domingo, n�o h� expediente e aborta a execu��o
  If to_char(p_data,'d') in (1,7) Then return 'N'; End If;
  
  -- Verifica se a data informada existe na tabela de datas especiais e � 
  -- de abrang�ncia internacional, ou da organiza��o, ou ainda se � data m�vel
  select count(*) into w_reg 
    from eo_data_especial a 
   where a.cliente      = p_cliente
     and ((a.abrangencia in ('N','I','O') and 
           a.expediente  <> 'S' and
           ((a.tipo      = 'I' and a.data_especial = substr(w_data,1,5)) or
            (a.tipo      = 'E' and a.data_especial = w_data)
           )
          ) or
          (a.tipo    in ('S','C','Q','P','D','H') and
           a.sq_pais = nvl(p_pais,2) and
           ((a.tipo = 'S' and p_data = VerificaDataMovel(to_char(p_data,'yyyy'),'S')) or
            (a.tipo = 'C' and p_data = VerificaDataMovel(to_char(p_data,'yyyy'),'C')) or
            (a.tipo = 'Q' and p_data = VerificaDataMovel(to_char(p_data,'yyyy'),'Q')) or
            (a.tipo = 'P' and p_data = VerificaDataMovel(to_char(p_data,'yyyy'),'P')) or
            (a.tipo = 'D' and p_data = VerificaDataMovel(to_char(p_data,'yyyy'),'D')) or
            (a.tipo = 'H' and p_data = VerificaDataMovel(to_char(p_data,'yyyy'),'H'))
           )
          )
         );
  If w_reg > 0 Then
     select a.expediente into Result 
       from eo_data_especial a 
      where a.cliente      = p_cliente
        and ((a.abrangencia in ('N','I','O') and 
              a.expediente  <> 'S' and
              ((a.tipo      = 'I' and a.data_especial = substr(w_data,1,5)) or
               (a.tipo      = 'E' and a.data_especial = w_data)
              )
             ) or
             (a.tipo    in ('S','C','Q','P','D','H') and
              a.sq_pais = nvl(p_pais,2) and
              ((a.tipo = 'S' and p_data = VerificaDataMovel(to_char(p_data,'yyyy'),'S')) or
               (a.tipo = 'C' and p_data = VerificaDataMovel(to_char(p_data,'yyyy'),'C')) or
               (a.tipo = 'Q' and p_data = VerificaDataMovel(to_char(p_data,'yyyy'),'Q')) or
               (a.tipo = 'P' and p_data = VerificaDataMovel(to_char(p_data,'yyyy'),'P')) or
               (a.tipo = 'D' and p_data = VerificaDataMovel(to_char(p_data,'yyyy'),'D')) or
               (a.tipo = 'H' and p_data = VerificaDataMovel(to_char(p_data,'yyyy'),'H'))
              )
             )
            );
  End If;       

  return Result;
end VerificaDataEspecial;
/
