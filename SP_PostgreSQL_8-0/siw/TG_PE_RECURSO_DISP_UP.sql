create or replace trigger TG_PE_RECURSO_DISP_UP
  before update on pe_recurso_disponivel  
  for each row
declare
  cursor c_dados is
    select sq_perecurso_indisponivel, inicio, fim
      from pe_recurso_indisponivel a
     where (a.inicio between :old.inicio and :old.fim and a.inicio not between :new.inicio and :new.fim)
        or (a.fim    between :old.inicio and :old.fim and a.fim    not between :new.inicio and :new.fim);
BEGIN
  if :old.inicio <> :new.inicio or :old.fim <> :new.fim then
    for crec c_dados loop
      if (crec.inicio < :new.inicio and crec.fim < :new.inicio) or
         (crec.inicio > :new.fim    and crec.fim > :new.fim) then
         -- Se o registro está fora do novo período, exclui
         DELETE FROM pe_recurso_indisponivel where sq_perecurso_indisponivel = crec.sq_perecurso_indisponivel;
      else
         -- Se o início da indisponibilidade é anterior ao início do novo registro, ajusta o início
         if crec.inicio < :new.inicio then update pe_recurso_indisponivel set inicio = :new.inicio where sq_perecurso_indisponivel = crec.sq_perecurso_indisponivel; end if;

         -- Se o término da indisponibilidade é posterior ao término do novo registro, ajusta o término
         if crec.fim > :new.fim then update pe_recurso_indisponivel set fim = :new.fim where sq_perecurso_indisponivel = crec.sq_perecurso_indisponivel; end if;
      end if;
    end loop;
  end if;