create or replace trigger TG_SIW_SOLIC_IN
  before insert on siw_solicitacao  
  for each row
declare
  w_chave   varchar(60) := null;
  w_modulo  siw_modulo.sigla%type;
  w_cliente siw_menu.sq_pessoa%type;
BEGIN
  If INSERTING Then
     If :new.codigo_interno is null Then
        select b.sigla, a.sq_pessoa into w_modulo, w_cliente 
          from siw_menu              a 
               inner join siw_modulo b on (a.sq_modulo = b.sq_modulo) 
         where a.sq_menu = :new.sq_menu;
        
        If    w_modulo = 'AC' Then AC_CriaParametro(w_cliente, :new.inicio, w_chave);
        Elsif w_modulo = 'FN' Then FN_CriaParametro(w_cliente, :new.fim,    w_chave);
        Elsif w_modulo = 'PD' Then PD_CriaParametro(w_cliente, :new.inicio, w_chave);
        End If;
        :new.codigo_interno := w_chave;
     End If;
  End If;