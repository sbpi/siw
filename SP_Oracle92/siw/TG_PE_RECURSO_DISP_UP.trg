create or replace trigger TG_PE_RECURSO_DISP_UP
  before update on pe_recurso_disponivel  
  for each row
declare
  cursor c_dados is
    select sq_perecurso_indisponivel, inicio, fim
      from pe_recurso_indisponivel a
     where (a.inicio between :old.inicio and :old.fim and a.inicio not between :new.inicio and :new.fim)
        or (a.fim    between :old.inicio and :old.fim and a.fim    not between :new.inicio and :new.fim);
begin
  if :old.inicio <> :new.inicio or :old.fim <> :new.fim then
    for crec in c_dados loop
      if (crec.inicio < :new.inicio and crec.fim < :new.inicio) or
         (crec.inicio > :new.fim    and crec.fim > :new.fim) then
         -- Se o registro est� fora do novo per�odo, exclui
         delete pe_recurso_indisponivel where sq_perecurso_indisponivel = crec.sq_perecurso_indisponivel;
      else
         -- Se o in�cio da indisponibilidade � anterior ao in�cio do novo registro, ajusta o in�cio
         if crec.inicio < :new.inicio then update pe_recurso_indisponivel set inicio = :new.inicio where sq_perecurso_indisponivel = crec.sq_perecurso_indisponivel; end if;

         -- Se o t�rmino da indisponibilidade � posterior ao t�rmino do novo registro, ajusta o t�rmino
         if crec.fim > :new.fim then update pe_recurso_indisponivel set fim = :new.fim where sq_perecurso_indisponivel = crec.sq_perecurso_indisponivel; end if;
      end if;
    end loop;
  end if;
end TG_PE_RECURSO_DISP_UP;
/
