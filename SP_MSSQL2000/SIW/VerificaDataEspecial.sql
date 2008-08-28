alter function dbo.VerificaDataEspecial
   (@p_data    datetime,
    @p_cliente int        = null,
    @p_pais    int        = null,
    @p_uf      varchar(2) = null,
    @p_cidade  int        = null
   ) returns varchar as
/**********************************************************************************
* Finalidade: Verificar o expediente na data e no local informado
* Autor     : Alexandre Vinhadelli Papadópolis
* Data      :  28/07/2005, 15:30
*
* Parâmetros:
*    @p_data    : data a ser testada
*    @p_cliente : opcional, testa datas especiais para a organização informada. Neste
*                caso, pais, uf e cidade são desconsiderados
*    @p_pais    : opcional, testa datas especiais para o país informado
*    @p_uf      : opcional, testa datas especiais para o estado informado. Neste caso
*                é obrigatório informar também o país
*    @p_cidade  : opcional, testa datas especiais para a cidade informada. Neste caso
*                é obrigatório informar também o país e o estado
*
* Retorno:       N: sem expediente
*                S: expediente normal
*                M: expediente somente pela manhã
*                T: expediente somente à tarde
*
* Observações:
* 1. sábados e domingos nunca têm expediente.
* 2. se apenas a data for informada, serão tratados apenas as datas especiais com
*    abrangência internacional, nacional ou da organização.
***********************************************************************************/
begin
  Declare @Result    varchar(1);
  Declare @w_reg     numeric(18);
  Declare @w_cliente numeric(18);
  Declare @w_data    varchar(10);
  Declare @w_pais    numeric(18);
  Declare @w_uf      varchar(3);
  Declare @w_cidade  numeric(18);

  Set @Result = 'S';
  Set @w_data = dbo.to_char(@p_data,'dd/mm/yyyy');

  -- Se for sábado ou domingo, não há expediente e aborta a execução
  If datepart(dw,@p_data) in (1,7) return 'N';

  -- Define o cliente, usando a SBPI como padrão
  Set @w_cliente = coalesce(@p_cliente,2);
  
  -- Recupera o país, estado e cidade padrão da organização
  select @w_cidade = b.sq_cidade, @w_uf = b.co_uf, @w_pais = b.sq_pais
    from siw_cliente a 
         inner join co_cidade b on (a.sq_cidade_padrao = b.sq_cidade)
   where a.sq_pessoa = coalesce(@p_cliente,2);
   
  -- Se a função recebeu país, estado ou cidade, estes prevalecem sobre os dados padrão
  If coalesce(@p_pais, @w_pais) <> @w_pais Begin
    Set @w_pais   = @p_pais;
    Set @w_uf     = @p_uf;
    Set @w_cidade = @p_cidade;
  End Else If coalesce(@p_uf, @w_uf) <> @w_uf Begin
    Set @w_uf     = @p_uf;
    Set @w_cidade = @p_cidade;
  End Else If coalesce(@p_cidade, @w_cidade) <> @w_cidade Begin
    Set @w_cidade = @p_cidade;
  End

  -- Verifica se a data informada existe na tabela de datas especiais
  select @w_reg = count(*)
    from eo_data_especial a
   where a.cliente       = @w_cliente
     and a.expediente    <> 'S' 
     and ((a.abrangencia in ('I','O')) or
          (a.abrangencia = 'N' and a.sq_pais   = @w_pais) or
          (a.abrangencia = 'E' and a.sq_pais   = @w_pais and a.co_uf = @w_uf) or
          (a.abrangencia = 'M' and a.sq_cidade = @w_cidade)
         )
     and ((a.tipo        = 'I' and a.data_especial = substring(@w_data,1,5)) or
          (a.tipo        = 'E' and a.data_especial = @w_data) or
          (a.tipo        in ('S','C','Q','P','D','H') and
           a.sq_pais     = @w_pais and
           ((a.tipo      = 'S' and @p_data = dbo.VerificaDataMovel(year(@p_data),'S')) or
            (a.tipo      = 'C' and @p_data = dbo.VerificaDataMovel(year(@p_data),'C')) or
            (a.tipo      = 'Q' and @p_data = dbo.VerificaDataMovel(year(@p_data),'Q')) or
            (a.tipo      = 'P' and @p_data = dbo.VerificaDataMovel(year(@p_data),'P')) or
            (a.tipo      = 'D' and @p_data = dbo.VerificaDataMovel(year(@p_data),'D')) or
            (a.tipo      = 'H' and @p_data = dbo.VerificaDataMovel(year(@p_data),'H'))
           )
          )
         );
  If @w_reg > 0 Begin
     select @Result = a.expediente
    from eo_data_especial a
   where a.cliente       = @w_cliente
     and a.expediente    <> 'S' 
     and ((a.abrangencia in ('I','O')) or
          (a.abrangencia = 'N' and a.sq_pais   = @w_pais) or
          (a.abrangencia = 'E' and a.sq_pais   = @w_pais and a.co_uf = @w_uf) or
          (a.abrangencia = 'M' and a.sq_cidade = @w_cidade)
         )
     and ((a.tipo        = 'I' and a.data_especial = substring(@w_data,1,5)) or
          (a.tipo        = 'E' and a.data_especial = @w_data) or
          (a.tipo        in ('S','C','Q','P','D','H') and
           a.sq_pais     = @w_pais and
           ((a.tipo      = 'S' and @p_data = dbo.VerificaDataMovel(year(@p_data),'S')) or
            (a.tipo      = 'C' and @p_data = dbo.VerificaDataMovel(year(@p_data),'C')) or
            (a.tipo      = 'Q' and @p_data = dbo.VerificaDataMovel(year(@p_data),'Q')) or
            (a.tipo      = 'P' and @p_data = dbo.VerificaDataMovel(year(@p_data),'P')) or
            (a.tipo      = 'D' and @p_data = dbo.VerificaDataMovel(year(@p_data),'D')) or
            (a.tipo      = 'H' and @p_data = dbo.VerificaDataMovel(year(@p_data),'H'))
           )
          )
         );
  End

  return @Result;
end
