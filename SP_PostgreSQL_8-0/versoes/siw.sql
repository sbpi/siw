--
-- PostgreSQL database dump
--

-- Started on 2009-08-14 11:04:35

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- TOC entry 10 (class 2615 OID 16387)
-- Name: contab; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA contab;


ALTER SCHEMA contab OWNER TO postgres;

--
-- TOC entry 1925 (class 0 OID 0)
-- Dependencies: 10
-- Name: SCHEMA contab; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON SCHEMA contab IS 'Standard public schema';


--
-- TOC entry 6 (class 2615 OID 16388)
-- Name: folha; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA folha;


ALTER SCHEMA folha OWNER TO postgres;

--
-- TOC entry 8 (class 2615 OID 16389)
-- Name: orcam; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA orcam;


ALTER SCHEMA orcam OWNER TO postgres;

--
-- TOC entry 1927 (class 0 OID 0)
-- Dependencies: 8
-- Name: SCHEMA orcam; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON SCHEMA orcam IS 'Esquem de Orcamento';


--
-- TOC entry 9 (class 2615 OID 16390)
-- Name: siw; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA siw;


ALTER SCHEMA siw OWNER TO postgres;

--
-- TOC entry 370 (class 2612 OID 16393)
-- Name: plpgsql; Type: PROCEDURAL LANGUAGE; Schema: -; Owner: postgres
--

CREATE PROCEDURAL LANGUAGE plpgsql;


ALTER PROCEDURAL LANGUAGE plpgsql OWNER TO postgres;

SET search_path = public, pg_catalog;

--
-- TOC entry 35 (class 1255 OID 16652)
-- Dependencies: 370 3
-- Name: acentos(character varying, numeric); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION acentos(valor character varying, tipo numeric) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
/*
Tipo = 1 => Converte acentos formato Benner (Paradox Intl) para ASCII Ansi
Tipo diferente de 1 ou nulo => Retira caracteres acentuados e converte para minúsculas
                               para ordenação no SELECT
*/
DECLARE
   nome varchar(8000) := Valor;

BEGIN

   IF Tipo IS NULL OR Tipo <> 1 THEN
      nome := ltrim(upper(translate(lower((nome)),'ãâáàéêíõôóúüç','aaaaeeiooouuc')));
   ELSE
      nome := translate(nome,'¿ Æ¿¿¡ä¢£¿','âáãêéíõóúç');
   END IF;

   RETURN nome ;
END; $$;


ALTER FUNCTION public.acentos(valor character varying, tipo numeric) OWNER TO postgres;

--
-- TOC entry 36 (class 1255 OID 16653)
-- Dependencies: 3 370
-- Name: acentos(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION acentos(valor character varying) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
DECLARE
   nome varchar(8000) := Valor;
BEGIN
   nome := ltrim(upper(translate(lower((nome)),'ãâáàéêíõôóúüç','aaaaeeiooouuc')));

   RETURN nome ;
END; $$;


ALTER FUNCTION public.acentos(valor character varying) OWNER TO postgres;

--
-- TOC entry 37 (class 1255 OID 16654)
-- Dependencies: 3 370
-- Name: criptografia(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION criptografia(textooriginal character varying) RETURNS character varying
    LANGUAGE plpgsql
    AS $_$
declare
  Result VARCHAR(4000) := Null; 
  w_Cifra1 VarChar(4000);
  w_Cifra2 VarChar(4000);
  w_Caracter VarChar(1);
  w_Asc VarChar(4000);
  w_Contador1 integer;
  w_Contador2 integer;
  w_ValorPar Varchar(1);
  w_Aux VarChar(4000) := TextoOriginal;
  w_Relacionamento Varchar(4000);
  w_Mod Numeric(10) := 555555;
  w_Pq Numeric(10);
  w_Resultado VarChar(4000);
begin
  
  w_Relacionamento := '|Z-020'||
                      '|Y-009'||
                      '|W-002'||
                      '|V-003'||
                      '|U-004'||
                      '|T-007'||
                      '|S-006'||
                      '|R-005'||
                      '|Q-008'||
                      '|P-001'||
                      '|O-010'||
                      '|N-011'||
                      '|M-012'||
                      '|L-013'||
                      '|K-014'||
                      '|J-015'||
                      '|I-016'||
                      '|H-017'||
                      '|G-018'||
                      '|F-019'||
                      '|E-099'||
                      '|D-021'||
                      '|C-022'||
                      '|B-023'||
                      '|A-024'||
                      '|X-025'||
                      '|z-309'||
                      '|y-309'||
                      '|w-302'||
                      '|v-303'||
                      '|u-304'||
                      '|t-307'||
                      '|s-306'||
                      '|r-305'||
                      '|q-308'||
                      '|p-301'||
                      '|o-310'||
                      '|n-311'||
                      '|m-312'||
                      '|l-313'||
                      '|k-314'||
                      '|j-315'||
                      '|y-316'||
                      '|h-317'||
                      '|g-318'||
                      '|f-319'||
                      '|e-399'||
                      '|d-321'||
                      '|c-322'||
                      '|b-323'||
                      '|a-324'||
                      '|x-325'||
                      '|0-109'||
                      '|1-108'||
                      '|2-107'||
                      '|3-106'||
                      '|4-105'||
                      '|5-104'||
                      '|6-103'||
                      '|7-102'||
                      '|8-101'||
                      '|9-100'||
                      '|--219'||
                      '|+-218'||
                      '|~-217'||
                      '|#-216'||
                      '|*-215'||
                      '|(-214'||
                      '|(-213'||
                      '|)-212'||
                      '|{-211'||
                      '|}-210'||
                      '|[-209'||
                      '|]-208'||
                      '||-207'||
                      E'|\-206'||
                      '|/-205'||
                      '|,-204'||
                      '|.-203'||
                      '|:-202'||
                      '|;-201'||
                      '|$-200'||
                      '|#-220'||
                      '|@-221'||
                      '|!-222'||
                      '|&-224'||
                      '|á-401'||
                      '|é-402'||
                      '|í-403'||
                      '|ú-404'||
                      '|ó-405'||
                      '|Á-406'||
                      '|É-407'||
                      '|Í-408'||
                      '|Ú-409'||
                      '|Ó-410'||
                      '|ç-411'||
                      '|Ç-412'||
                      '|ã-413'||
                      '|Ã-414'||
                      '|ê-417'||
                      '|Ê-418'||
                      '|õ-415'||
                      '|Õ-416';
               
  w_Cifra1 := '';
  
  for w_Contador1 in 1..length(TextoOriginal) loop
     w_Caracter := Substr(TextoOriginal,w_Contador1,1);
     if Instr(w_Relacionamento,'|'||w_Caracter||'-') > 0 Then
        w_Asc := substr(w_Relacionamento,Instr(w_Relacionamento,'|'||w_Caracter||'-')+3,3);
     else
        w_Asc := '999'; 
     end if;
     w_Cifra1 := w_Cifra1 || w_Asc;
  end loop;  


  w_Contador2 := 1;
  w_Cifra2 := '';
  while w_Contador2 < 1000 loop
      w_Pq := Nvl(Substr(w_Cifra1,w_Contador2,6),0); 

     if length(Substr(w_Cifra1,w_Contador2,6)) = 3 then
         w_Pq := Nvl(To_Number(Substr(w_Cifra1,w_Contador2,6))||'999',0);
     End If;
      w_Resultado := To_Char((w_Pq * w_Pq * w_Pq) % w_Mod,'00000000');
      w_Cifra2 := w_Cifra2 || Trim(w_Resultado);
      w_Contador2 := w_Contador2 + 6;
     
      if (w_Pq = 0) or (w_Contador2 >=  length(w_Cifra1)) then
         Exit;
      End If; 
  end Loop;
  Result := w_Cifra2;
  return(Result);

end;  $_$;


ALTER FUNCTION public.criptografia(textooriginal character varying) OWNER TO postgres;

--
-- TOC entry 24 (class 1255 OID 16641)
-- Dependencies: 370 3
-- Name: instr(character varying, numeric); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION instr(character varying, numeric) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
DECLARE
    pos integer;
BEGIN
    pos:= instr($1, $2, 1);
    RETURN pos;
END;
$_$;


ALTER FUNCTION public.instr(character varying, numeric) OWNER TO postgres;

--
-- TOC entry 25 (class 1255 OID 16642)
-- Dependencies: 370 3
-- Name: instr(character varying, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION instr(character varying, character varying) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
DECLARE
    pos integer;
BEGIN
    pos:= instr($1, $2, 1);
    RETURN pos;
END;
$_$;


ALTER FUNCTION public.instr(character varying, character varying) OWNER TO postgres;

--
-- TOC entry 26 (class 1255 OID 16643)
-- Dependencies: 370 3
-- Name: instr(character varying, character varying, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION instr(character varying, character varying, character varying) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
DECLARE
    string ALIAS FOR $1;
    string_to_search ALIAS FOR $2;
    beg_index ALIAS FOR $3;
    pos integer NOT NULL DEFAULT 0;
    temp_str varchar;
    beg integer;
    length integer;
    ss_length integer;
BEGIN
    IF beg_index > 0 THEN
        temp_str := substring(string FROM beg_index);
        pos := position(string_to_search IN temp_str);

        IF pos = 0 THEN
            RETURN 0;
        ELSE
            RETURN pos + beg_index - 1;
        END IF;
    ELSE
        ss_length := char_length(string_to_search);
        length := char_length(string);
        beg := length + beg_index - ss_length + 2;

        WHILE beg > 0 LOOP
            temp_str := substring(string FROM beg FOR ss_length);
            pos := position(string_to_search IN temp_str);

            IF pos > 0 THEN
                RETURN beg;
            END IF;

            beg := beg - 1;
        END LOOP;

        RETURN 0;
    END IF;
END;
$_$;


ALTER FUNCTION public.instr(character varying, character varying, character varying) OWNER TO postgres;

--
-- TOC entry 27 (class 1255 OID 16644)
-- Dependencies: 3 370
-- Name: instr(character varying, character varying, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION instr(character varying, character varying, integer) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
DECLARE
    string ALIAS FOR $1;
    string_to_search ALIAS FOR $2;
    beg_index ALIAS FOR $3;
    pos integer NOT NULL DEFAULT 0;
    temp_str varchar;
    beg integer;
    length integer;
    ss_length integer;
BEGIN
    IF beg_index > 0 THEN
        temp_str := substring(string FROM beg_index);
        pos := position(string_to_search IN temp_str);

        IF pos = 0 THEN
            RETURN 0;
        ELSE
            RETURN pos + beg_index - 1;
        END IF;
    ELSE
        ss_length := char_length(string_to_search);
        length := char_length(string);
        beg := length + beg_index - ss_length + 2;

        WHILE beg > 0 LOOP
            temp_str := substring(string FROM beg FOR ss_length);
            pos := position(string_to_search IN temp_str);

            IF pos > 0 THEN
                RETURN beg;
            END IF;

            beg := beg - 1;
        END LOOP;

        RETURN 0;
    END IF;
END;
$_$;


ALTER FUNCTION public.instr(character varying, character varying, integer) OWNER TO postgres;

--
-- TOC entry 28 (class 1255 OID 16645)
-- Dependencies: 370 3
-- Name: instr(character varying, character varying, integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION instr(character varying, character varying, integer, integer) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
DECLARE
    string ALIAS FOR $1;
    string_to_search ALIAS FOR $2;
    beg_index ALIAS FOR $3;
    occur_index ALIAS FOR $4;
    pos integer NOT NULL DEFAULT 0;
    occur_number integer NOT NULL DEFAULT 0;
    temp_str varchar;
    beg integer;
    i integer;
    length integer;
    ss_length integer;
BEGIN
    IF beg_index > 0 THEN
        beg := beg_index;
        temp_str := substring(string FROM beg_index);

        FOR i IN 1..occur_index LOOP
            pos := position(string_to_search IN temp_str);

            IF i = 1 THEN
                beg := beg + pos - 1;
            ELSE
                beg := beg + pos;
            END IF;

            temp_str := substring(string FROM beg + 1);
        END LOOP;

        IF pos = 0 THEN
            RETURN 0;
        ELSE
            RETURN beg;
        END IF;
    ELSE
        ss_length := char_length(string_to_search);
        length := char_length(string);
        beg := length + beg_index - ss_length + 2;

        WHILE beg > 0 LOOP
            temp_str := substring(string FROM beg FOR ss_length);
            pos := position(string_to_search IN temp_str);

            IF pos > 0 THEN
                occur_number := occur_number + 1;

                IF occur_number = occur_index THEN
                    RETURN beg;
                END IF;
            END IF;

            beg := beg - 1;
        END LOOP;

        RETURN 0;
    END IF;
END;
$_$;


ALTER FUNCTION public.instr(character varying, character varying, integer, integer) OWNER TO postgres;

--
-- TOC entry 29 (class 1255 OID 16646)
-- Dependencies: 3 370
-- Name: nvl(character varying, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION nvl(character varying, integer) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
DECLARE
  pCAMPO ALIAS FOR $1;
  pSUBST ALIAS FOR $2;
BEGIN
  if (pCAMPO isnull) then
    return pSUBST;
  else
    return pCAMPO;
  end if;
end; $_$;


ALTER FUNCTION public.nvl(character varying, integer) OWNER TO postgres;

--
-- TOC entry 30 (class 1255 OID 16647)
-- Dependencies: 370 3
-- Name: nvl(integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION nvl(integer, integer) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
DECLARE
  pCAMPO ALIAS FOR $1;
  pSUBST ALIAS FOR $2;
BEGIN
  if (pCAMPO isnull) then
    return pSUBST;
  else
    return pCAMPO;
  end if;
end; $_$;


ALTER FUNCTION public.nvl(integer, integer) OWNER TO postgres;

--
-- TOC entry 31 (class 1255 OID 16648)
-- Dependencies: 370 3
-- Name: nvl(numeric, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION nvl(numeric, integer) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
DECLARE
  pCAMPO ALIAS FOR $1;
  pSUBST ALIAS FOR $2;
BEGIN
  if (pCAMPO isnull) then
    return pSUBST;
  else
    return pCAMPO;
  end if;
end; $_$;


ALTER FUNCTION public.nvl(numeric, integer) OWNER TO postgres;

--
-- TOC entry 32 (class 1255 OID 16649)
-- Dependencies: 3 370
-- Name: nvl(numeric, numeric); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION nvl(numeric, numeric) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
DECLARE
  pCAMPO ALIAS FOR $1;
  pSUBST ALIAS FOR $2;
BEGIN
  if (pCAMPO isnull) then
    return pSUBST;
  else
    return pCAMPO;
  end if;
end; $_$;


ALTER FUNCTION public.nvl(numeric, numeric) OWNER TO postgres;

--
-- TOC entry 33 (class 1255 OID 16650)
-- Dependencies: 3 370
-- Name: nvl(character, character); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION nvl(character, character) RETURNS character
    LANGUAGE plpgsql
    AS $_$
DECLARE
  pCAMPO ALIAS FOR $1;
  pSUBST ALIAS FOR $2;
BEGIN
  if (pCAMPO isnull) then
    return pSUBST;
  else
    return pCAMPO;
  end if;
end; $_$;


ALTER FUNCTION public.nvl(character, character) OWNER TO postgres;

--
-- TOC entry 34 (class 1255 OID 16651)
-- Dependencies: 370 3
-- Name: nvl(date, date); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION nvl(date, date) RETURNS date
    LANGUAGE plpgsql
    AS $_$
DECLARE
  pCAMPO ALIAS FOR $1;
  pSUBST ALIAS FOR $2;
BEGIN
  if (pCAMPO isnull) then
    return pSUBST;
  else
    return pCAMPO;
  end if;
end; $_$;


ALTER FUNCTION public.nvl(date, date) OWNER TO postgres;

--
-- TOC entry 39 (class 1255 OID 16656)
-- Dependencies: 3 370
-- Name: to_char(numeric); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION to_char(p_valor numeric) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
BEGIN
 return p_valor;
end
$$;


ALTER FUNCTION public.to_char(p_valor numeric) OWNER TO postgres;

--
-- TOC entry 38 (class 1255 OID 16655)
-- Dependencies: 370 3
-- Name: to_number(character); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION to_number(character) RETURNS bigint
    LANGUAGE plpgsql
    AS $_$
 DECLARE
 pCAMPO ALIAS FOR $1;
 cCAMPO bpchar;
 vCAMPO int8;
BEGIN
 cCAMPO := trim(translate(upper(pCAMPO),'ÚÁÉÍÓÔÛÎÂÊÃÕÜÙÀÈÌÒ 
QWERTYUIOP[]ASDFGHJKL;ZXCVBNM,./<>?|{}:"-_=+)(*&[EMAIL PROTECTED]',''));
 if (cCAMPO='') then
   cCAMPO='0';
 end if;
 vCAMPO := CAST (cCAMPO as int8);
 --vCAMPO := cCAMPO;
 return vCAMPO;
end
$_$;


ALTER FUNCTION public.to_number(character) OWNER TO postgres;

--
-- TOC entry 40 (class 1255 OID 16657)
-- Dependencies: 3 370
-- Name: trunc(timestamp without time zone); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION trunc(p_datahora timestamp without time zone) RETURNS timestamp with time zone
    LANGUAGE plpgsql
    AS $$
begin
  return(to_timestamp(to_char(p_datahora,'dd/mm/yyyy'),'dd/mm/yyyy'));
end;  $$;


ALTER FUNCTION public.trunc(p_datahora timestamp without time zone) OWNER TO postgres;

--
-- TOC entry 41 (class 1255 OID 16658)
-- Dependencies: 370 3
-- Name: trunc(timestamp with time zone); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION trunc(p_datahora timestamp with time zone) RETURNS timestamp with time zone
    LANGUAGE plpgsql
    AS $$
begin
  return(to_timestamp(to_char(p_datahora,'dd/mm/yyyy'),'dd/mm/yyyy'));
end;  $$;


ALTER FUNCTION public.trunc(p_datahora timestamp with time zone) OWNER TO postgres;

SET search_path = contab, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 1542 (class 1259 OID 16395)
-- Dependencies: 10
-- Name: Centro_Custo; Type: TABLE; Schema: contab; Owner: postgres; Tablespace: 
--

CREATE TABLE "Centro_Custo" (
    "Cod_Centro_Custo" character(8) NOT NULL,
    "Cod_Projeto" character(10) NOT NULL,
    "Nome_Projeto" character varying(60) NOT NULL
);


ALTER TABLE contab."Centro_Custo" OWNER TO postgres;

--
-- TOC entry 1930 (class 0 OID 0)
-- Dependencies: 1542
-- Name: TABLE "Centro_Custo"; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON TABLE "Centro_Custo" IS 'Será substituída por uma "view" do módulo de projetos.';


--
-- TOC entry 1543 (class 1259 OID 16398)
-- Dependencies: 1838 1839 10
-- Name: calendario; Type: TABLE; Schema: contab; Owner: postgres; Tablespace: 
--

CREATE TABLE calendario (
    data_ca date DEFAULT ('now'::text)::date NOT NULL,
    feriado_ca boolean DEFAULT false NOT NULL
);


ALTER TABLE contab.calendario OWNER TO postgres;

--
-- TOC entry 1931 (class 0 OID 0)
-- Dependencies: 1543
-- Name: COLUMN calendario.data_ca; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON COLUMN calendario.data_ca IS 'É uma data, sobre a qual se deseja marcar como feriado ou não.';


--
-- TOC entry 1932 (class 0 OID 0)
-- Dependencies: 1543
-- Name: COLUMN calendario.feriado_ca; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON COLUMN calendario.feriado_ca IS 'True  = é feriado
False = não é feriado';


--
-- TOC entry 1544 (class 1259 OID 16403)
-- Dependencies: 10
-- Name: contas_lanc_padrao; Type: TABLE; Schema: contab; Owner: postgres; Tablespace: 
--

CREATE TABLE contas_lanc_padrao (
    codlanpadrao_lp bigint NOT NULL,
    codcontacontabil_pc character(15) NOT NULL,
    valorlancamento numeric(12,2),
    tipolancamento character(1) NOT NULL
);


ALTER TABLE contab.contas_lanc_padrao OWNER TO postgres;

--
-- TOC entry 1545 (class 1259 OID 16406)
-- Dependencies: 1840 10
-- Name: controle_mov_contabil; Type: TABLE; Schema: contab; Owner: postgres; Tablespace: 
--

CREATE TABLE controle_mov_contabil (
    anocontabil smallint NOT NULL,
    mescontabil smallint NOT NULL,
    statusmescontabil character(1) DEFAULT 'F'::bpchar NOT NULL
);


ALTER TABLE contab.controle_mov_contabil OWNER TO postgres;

--
-- TOC entry 1934 (class 0 OID 0)
-- Dependencies: 1545
-- Name: COLUMN controle_mov_contabil.anocontabil; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON COLUMN controle_mov_contabil.anocontabil IS 'Ano contábil no formato "9999".
Valores possíveis:

( AnoSistOperacional - 1 )  <=  AnoContábil';


--
-- TOC entry 1935 (class 0 OID 0)
-- Dependencies: 1545
-- Name: COLUMN controle_mov_contabil.mescontabil; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON COLUMN controle_mov_contabil.mescontabil IS 'Mês contábil no formato "99".
Valores possíveis:

0 < Mês < 13';


--
-- TOC entry 1936 (class 0 OID 0)
-- Dependencies: 1545
-- Name: COLUMN controle_mov_contabil.statusmescontabil; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON COLUMN controle_mov_contabil.statusmescontabil IS 'F = O mês está fechado e não aceita lançamentos contábeis.

A = O mês está aberto e aceita lançamentos contábeis.';


--
-- TOC entry 1546 (class 1259 OID 16410)
-- Dependencies: 10
-- Name: descr_padrao; Type: TABLE; Schema: contab; Owner: postgres; Tablespace: 
--

CREATE TABLE descr_padrao (
    coddescricao_dp smallint NOT NULL,
    textodescricaopadrao_dp character varying(200) NOT NULL
);


ALTER TABLE contab.descr_padrao OWNER TO postgres;

--
-- TOC entry 1547 (class 1259 OID 16413)
-- Dependencies: 1841 1842 10
-- Name: lanc_autom; Type: TABLE; Schema: contab; Owner: postgres; Tablespace: 
--

CREATE TABLE lanc_autom (
    numlancamentoautom numeric(5,0) NOT NULL,
    seqlancautom numeric(2,0) NOT NULL,
    centrocustolancautom numeric(10,0) NOT NULL,
    dtreferencialancautom date NOT NULL,
    dtmovimentolancautom date NOT NULL,
    tipoconta character(1) DEFAULT 'D'::bpchar NOT NULL,
    codconta character(15) NOT NULL,
    valorlancautom numeric(12,2) DEFAULT 0 NOT NULL,
    histlancautom character varying(40),
    ocorrencialancautom numeric(2,0) NOT NULL,
    fk_origemmovimento character(3) NOT NULL,
    fk_dtiniciomovinterface date NOT NULL
);


ALTER TABLE contab.lanc_autom OWNER TO postgres;

--
-- TOC entry 1548 (class 1259 OID 16418)
-- Dependencies: 10
-- Name: lanc_ocorr; Type: TABLE; Schema: contab; Owner: postgres; Tablespace: 
--

CREATE TABLE lanc_ocorr (
    fk_numlancamentoautom_ltocr numeric(5,0) NOT NULL,
    fk_seqlanautom_ltocr numeric(2,0) NOT NULL,
    fk_codocorrencialanautom_ltocr numeric(2,0) NOT NULL
);


ALTER TABLE contab.lanc_ocorr OWNER TO postgres;

--
-- TOC entry 1549 (class 1259 OID 16421)
-- Dependencies: 10
-- Name: lanc_padrao; Type: TABLE; Schema: contab; Owner: postgres; Tablespace: 
--

CREATE TABLE lanc_padrao (
    codlanpadrao_lp bigint NOT NULL,
    descrlancpadrao_lp character varying(200) NOT NULL
);


ALTER TABLE contab.lanc_padrao OWNER TO postgres;

--
-- TOC entry 1941 (class 0 OID 0)
-- Dependencies: 1549
-- Name: COLUMN lanc_padrao.descrlancpadrao_lp; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON COLUMN lanc_padrao.descrlancpadrao_lp IS 'campo da descriçao com 200 caracteres';


--
-- TOC entry 1550 (class 1259 OID 16424)
-- Dependencies: 1843 1844 1845 1846 10
-- Name: lancamentos; Type: TABLE; Schema: contab; Owner: postgres; Tablespace: 
--

CREATE TABLE lancamentos (
    dtmovimento_lan date DEFAULT ('now'::text)::date NOT NULL,
    numlancamentodia_lan numeric(5,0) NOT NULL,
    seqlancamento numeric(2,0) NOT NULL,
    dtreferencia_lan date DEFAULT ('now'::text)::date NOT NULL,
    tipolancamento character(1) DEFAULT 'D'::bpchar NOT NULL,
    valor_lan numeric(12,2) NOT NULL,
    descricaohist_lan character varying(40) NOT NULL,
    centrocusto_lan character varying(10) NOT NULL,
    fk_contacontabillancamento_lan character(15) NOT NULL,
    fk_numlancamentoautom numeric(5,0),
    fk_seqlancautom numeric(2,0),
    origemlancamento_lan character(3) DEFAULT 'MAN'::bpchar
);


ALTER TABLE contab.lancamentos OWNER TO postgres;

--
-- TOC entry 1943 (class 0 OID 0)
-- Dependencies: 1550
-- Name: COLUMN lancamentos.dtmovimento_lan; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON COLUMN lancamentos.dtmovimento_lan IS 'É a data que será considerada para efeito de "sensibilização" da contabilidade.';


--
-- TOC entry 1944 (class 0 OID 0)
-- Dependencies: 1550
-- Name: COLUMN lancamentos.numlancamentodia_lan; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON COLUMN lancamentos.numlancamentodia_lan IS 'Sequencial de lançamento manual.';


--
-- TOC entry 1945 (class 0 OID 0)
-- Dependencies: 1550
-- Name: COLUMN lancamentos.dtreferencia_lan; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON COLUMN lancamentos.dtreferencia_lan IS 'Data do registro do lançamento. Corresponde à data do sistema operacional do servidor de aplicação.';


--
-- TOC entry 1946 (class 0 OID 0)
-- Dependencies: 1550
-- Name: COLUMN lancamentos.valor_lan; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON COLUMN lancamentos.valor_lan IS 'Valor do lançamento.';


--
-- TOC entry 1947 (class 0 OID 0)
-- Dependencies: 1550
-- Name: COLUMN lancamentos.descricaohist_lan; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON COLUMN lancamentos.descricaohist_lan IS 'A descrição do lançamento não deve ter menos do que 4 caracteres.';


--
-- TOC entry 1948 (class 0 OID 0)
-- Dependencies: 1550
-- Name: COLUMN lancamentos.centrocusto_lan; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON COLUMN lancamentos.centrocusto_lan IS 'O Centro de Custo será validado na tabela Projeto.';


--
-- TOC entry 1949 (class 0 OID 0)
-- Dependencies: 1550
-- Name: COLUMN lancamentos.fk_contacontabillancamento_lan; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON COLUMN lancamentos.fk_contacontabillancamento_lan IS 'Aponta para a conta contábil usada para efetuar o lançamento.';


--
-- TOC entry 1950 (class 0 OID 0)
-- Dependencies: 1550
-- Name: COLUMN lancamentos.origemlancamento_lan; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON COLUMN lancamentos.origemlancamento_lan IS 'PAT - Patrimônio, FOL - Folha, FIN - Financeiro, MAN - manual, INE - Início de Exercício, TAR - Transferência de Apuração de Resultado.';


--
-- TOC entry 1551 (class 1259 OID 16431)
-- Dependencies: 1847 10
-- Name: lote; Type: TABLE; Schema: contab; Owner: postgres; Tablespace: 
--

CREATE TABLE lote (
    origemmovimento character(3) NOT NULL,
    dtiniciomovinterface date NOT NULL,
    dtfimmovinterface date NOT NULL,
    statusmovimento numeric(1,0) DEFAULT 0 NOT NULL,
    totalregistros numeric(5,0) NOT NULL
);


ALTER TABLE contab.lote OWNER TO postgres;

--
-- TOC entry 1552 (class 1259 OID 16435)
-- Dependencies: 10
-- Name: ocorr_lanc_autom; Type: TABLE; Schema: contab; Owner: postgres; Tablespace: 
--

CREATE TABLE ocorr_lanc_autom (
    codocorrencialanautom numeric(2,0) NOT NULL,
    descrocorrencialancautom character varying(50) NOT NULL
);


ALTER TABLE contab.ocorr_lanc_autom OWNER TO postgres;

--
-- TOC entry 1553 (class 1259 OID 16438)
-- Dependencies: 1848 1849 1850 1851 10
-- Name: plano_contas; Type: TABLE; Schema: contab; Owner: postgres; Tablespace: 
--

CREATE TABLE plano_contas (
    codcontacontabil_pc character(15) NOT NULL,
    nomecontacontabil_pc character varying(40) NOT NULL,
    descrcontacontabil_pc character varying(200) NOT NULL,
    naturezacontacontabil_pc character(1) DEFAULT 'D'::bpchar NOT NULL,
    redutoracontacontabil_pc boolean DEFAULT false NOT NULL,
    statusatividadecontacontabil_pc character(1) DEFAULT 'A'::bpchar NOT NULL,
    contadetalhecontacontabil_pc boolean DEFAULT true NOT NULL,
    fk_codcontacontabilsuperior_pc character(15),
    fk_codcontacontabildeficit_pc character(15),
    fk_codcontacontabilsuperavit_pc character(15)
);


ALTER TABLE contab.plano_contas OWNER TO postgres;

--
-- TOC entry 1954 (class 0 OID 0)
-- Dependencies: 1553
-- Name: TABLE plano_contas; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON TABLE plano_contas IS 'Plano de contas da contabilidade.';


--
-- TOC entry 1955 (class 0 OID 0)
-- Dependencies: 1553
-- Name: COLUMN plano_contas.codcontacontabil_pc; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON COLUMN plano_contas.codcontacontabil_pc IS 'Este atributo é chave candidata da conta contábil no formato de String, para facilitar a implementação.
Tem tamanho fixo de 15 caracteres, da seguinte forma:
"N              "
"N.N            "
"N.N.N          "
"N.N.N.N        "
"N.N.N.N.NN     "
"N.N.N.N.NN.NNNN"';


--
-- TOC entry 1956 (class 0 OID 0)
-- Dependencies: 1553
-- Name: COLUMN plano_contas.naturezacontacontabil_pc; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON COLUMN plano_contas.naturezacontacontabil_pc IS '"D" = Débito
"C" = Crédito';


--
-- TOC entry 1957 (class 0 OID 0)
-- Dependencies: 1553
-- Name: COLUMN plano_contas.redutoracontacontabil_pc; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON COLUMN plano_contas.redutoracontacontabil_pc IS 'True = a conta é do tipo redutora;
False = a conta não é do tipo redutora

Esta natureza somente pode ser aplicada às
contas detalhes. (analíticas).';


--
-- TOC entry 1958 (class 0 OID 0)
-- Dependencies: 1553
-- Name: COLUMN plano_contas.statusatividadecontacontabil_pc; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON COLUMN plano_contas.statusatividadecontacontabil_pc IS '"A" = a conta está Ativa
"I" = a conta está Inativa';


--
-- TOC entry 1959 (class 0 OID 0)
-- Dependencies: 1553
-- Name: COLUMN plano_contas.contadetalhecontacontabil_pc; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON COLUMN plano_contas.contadetalhecontacontabil_pc IS 'True  =  é uma conta detalhe
False =  não é uma conta detalhe';


--
-- TOC entry 1960 (class 0 OID 0)
-- Dependencies: 1553
-- Name: COLUMN plano_contas.fk_codcontacontabilsuperior_pc; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON COLUMN plano_contas.fk_codcontacontabilsuperior_pc IS 'Chave estrangeira.
Aponta para a conta "pai".';


--
-- TOC entry 1961 (class 0 OID 0)
-- Dependencies: 1553
-- Name: COLUMN plano_contas.fk_codcontacontabildeficit_pc; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON COLUMN plano_contas.fk_codcontacontabildeficit_pc IS 'Chave estrangeira para conta de deficit, as duas contas a que importa e a que exporta deverão ser detalhe.';


--
-- TOC entry 1962 (class 0 OID 0)
-- Dependencies: 1553
-- Name: COLUMN plano_contas.fk_codcontacontabilsuperavit_pc; Type: COMMENT; Schema: contab; Owner: postgres
--

COMMENT ON COLUMN plano_contas.fk_codcontacontabilsuperavit_pc IS 'Chave estrangeira para conta de superavit, as duas contas a que importa e a que exporta deverão ser detalhe.';


--
-- TOC entry 1554 (class 1259 OID 16445)
-- Dependencies: 1852 1853 1854 1855 10
-- Name: saldo_contas; Type: TABLE; Schema: contab; Owner: postgres; Tablespace: 
--

CREATE TABLE saldo_contas (
    fk_codcontacontabil_sc character(15) NOT NULL,
    fk_centrocusto_sc character varying(10) NOT NULL,
    fk_anocontacontabil_sc smallint NOT NULL,
    fk_mescontacontabil_sc smallint NOT NULL,
    saldoinicialcontacontabil_sc bigint DEFAULT 0 NOT NULL,
    saldofinalcontacontabil_sc bigint DEFAULT 0 NOT NULL,
    totaldebitocontacontabil_sc bigint DEFAULT 0 NOT NULL,
    totalcreditocontacontabil_sc bigint DEFAULT 0 NOT NULL
);


ALTER TABLE contab.saldo_contas OWNER TO postgres;

--
-- TOC entry 1555 (class 1259 OID 16452)
-- Dependencies: 10
-- Name: seq_lancamento; Type: SEQUENCE; Schema: contab; Owner: postgres
--

CREATE SEQUENCE seq_lancamento
    INCREMENT BY 1
    NO MAXVALUE
    MINVALUE 0
    CACHE 1;


ALTER TABLE contab.seq_lancamento OWNER TO postgres;

--
-- TOC entry 1556 (class 1259 OID 16454)
-- Dependencies: 1856 1857 10
-- Name: status; Type: TABLE; Schema: contab; Owner: postgres; Tablespace: 
--

CREATE TABLE status (
    codstatus numeric(1,0) DEFAULT 0 NOT NULL,
    descrstatus character(14) DEFAULT 'Gerado'::bpchar NOT NULL
);


ALTER TABLE contab.status OWNER TO postgres;

--
-- TOC entry 1557 (class 1259 OID 16459)
-- Dependencies: 10
-- Name: tb_teste_centro_custo; Type: TABLE; Schema: contab; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_teste_centro_custo (
    centro_custo character varying(8) NOT NULL,
    projeto character varying(10) NOT NULL,
    nome_projeto character varying(50) NOT NULL
);


ALTER TABLE contab.tb_teste_centro_custo OWNER TO postgres;

SET search_path = orcam, pg_catalog;

--
-- TOC entry 1558 (class 1259 OID 16462)
-- Dependencies: 8
-- Name: conta_tem_orcamento_no_ano; Type: TABLE; Schema: orcam; Owner: postgres; Tablespace: 
--

CREATE TABLE conta_tem_orcamento_no_ano (
    ano_orcamento integer NOT NULL,
    pk_codcontaorcamento_pco character(12) NOT NULL,
    primeirotrimestrevalor_or numeric(10,2),
    segundotrimestrevalor_or numeric(10,2),
    terceirotrimestrevalor_or numeric(10,2),
    quartotrimestrevalor_or numeric(10,2)
);


ALTER TABLE orcam.conta_tem_orcamento_no_ano OWNER TO postgres;

--
-- TOC entry 1559 (class 1259 OID 16465)
-- Dependencies: 1858 8
-- Name: controle_exercicio_orcamentario; Type: TABLE; Schema: orcam; Owner: postgres; Tablespace: 
--

CREATE TABLE controle_exercicio_orcamentario (
    ano_orcamento integer NOT NULL,
    status_ano character(1) NOT NULL,
    total_ano numeric(10,2) DEFAULT 0 NOT NULL
);


ALTER TABLE orcam.controle_exercicio_orcamentario OWNER TO postgres;

--
-- TOC entry 1560 (class 1259 OID 16469)
-- Dependencies: 8
-- Name: mes_trimestre_pk_mes_seq; Type: SEQUENCE; Schema: orcam; Owner: postgres
--

CREATE SEQUENCE mes_trimestre_pk_mes_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE orcam.mes_trimestre_pk_mes_seq OWNER TO postgres;

--
-- TOC entry 1561 (class 1259 OID 16471)
-- Dependencies: 1859 8
-- Name: mes_trimestre; Type: TABLE; Schema: orcam; Owner: postgres; Tablespace: 
--

CREATE TABLE mes_trimestre (
    pk_mes integer DEFAULT nextval('mes_trimestre_pk_mes_seq'::regclass) NOT NULL,
    mes character varying(10) NOT NULL,
    trimestre character(1) NOT NULL
);


ALTER TABLE orcam.mes_trimestre OWNER TO postgres;

--
-- TOC entry 1562 (class 1259 OID 16475)
-- Dependencies: 1860 1861 1862 8
-- Name: plano_contas_orcamento; Type: TABLE; Schema: orcam; Owner: postgres; Tablespace: 
--

CREATE TABLE plano_contas_orcamento (
    pk_codcontaorcamento_pco character(12) NOT NULL,
    nomecontaorcamento_pco character varying(30) NOT NULL,
    descrcontaorcamento_pco character varying(40),
    fk_centrocustoorcamento_pco character varying(8) NOT NULL,
    naturezacontaorcamento_pco character(1) NOT NULL,
    tipocontaorcamento_pco boolean DEFAULT true NOT NULL,
    statusatividadecontaorcamento_pco character(1) DEFAULT 'A'::bpchar NOT NULL,
    comprometimentoorcamento_pco boolean DEFAULT true NOT NULL,
    responsavelorcamento_pco character varying(40) NOT NULL,
    fk_contacontabil_pco character(15) NOT NULL
);


ALTER TABLE orcam.plano_contas_orcamento OWNER TO postgres;

--
-- TOC entry 1563 (class 1259 OID 16481)
-- Dependencies: 8
-- Name: processamento_pk_codigoprocessamento_seq; Type: SEQUENCE; Schema: orcam; Owner: postgres
--

CREATE SEQUENCE processamento_pk_codigoprocessamento_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE orcam.processamento_pk_codigoprocessamento_seq OWNER TO postgres;

--
-- TOC entry 1564 (class 1259 OID 16483)
-- Dependencies: 1863 8
-- Name: processamento; Type: TABLE; Schema: orcam; Owner: postgres; Tablespace: 
--

CREATE TABLE processamento (
    pk_codigoprocessamento integer DEFAULT nextval('processamento_pk_codigoprocessamento_seq'::regclass) NOT NULL,
    dataultimoprocessamento_so date NOT NULL
);


ALTER TABLE orcam.processamento OWNER TO postgres;

--
-- TOC entry 1565 (class 1259 OID 16487)
-- Dependencies: 8
-- Name: saldo_orcamento; Type: TABLE; Schema: orcam; Owner: postgres; Tablespace: 
--

CREATE TABLE saldo_orcamento (
    pk_codcontaorcamento_pco character(12) NOT NULL,
    ano_orcamento integer NOT NULL,
    pk_codigoprocessamento integer NOT NULL,
    statusmovimentocontabil_so character(1) NOT NULL,
    comprometidoprimeirotrimestre_so numeric(10,2) NOT NULL,
    realizadoprimeirotrimestre_so numeric(10,2) NOT NULL,
    comprometidosegundotrimestre_so numeric(10,2) NOT NULL,
    realizadosegundotrimestre_so numeric(10,2) NOT NULL,
    comprometidoterceirotrimestre_so numeric(10,2) NOT NULL,
    realizadoterceirotrimestre_so numeric(10,2) NOT NULL,
    comprometidoquartotrimestre_so numeric(10,2) NOT NULL,
    realizadoquartotrimestre_so numeric(10,2) NOT NULL
);


ALTER TABLE orcam.saldo_orcamento OWNER TO postgres;

SET search_path = siw, pg_catalog;

--
-- TOC entry 1566 (class 1259 OID 16490)
-- Dependencies: 9
-- Name: vw_acordo; Type: TABLE; Schema: siw; Owner: postgres; Tablespace: 
--

CREATE TABLE vw_acordo (
    cliente bigint NOT NULL,
    nm_menu character varying(40) NOT NULL,
    sq_menu bigint NOT NULL,
    cd_projeto character varying(60),
    cd_acordo character varying(60),
    inicio date NOT NULL,
    fim date
);


ALTER TABLE siw.vw_acordo OWNER TO postgres;

--
-- TOC entry 1567 (class 1259 OID 16493)
-- Dependencies: 9
-- Name: vw_acordo_parcela; Type: TABLE; Schema: siw; Owner: postgres; Tablespace: 
--

CREATE TABLE vw_acordo_parcela (
    cliente numeric(18,0) NOT NULL,
    nm_menu character varying(40) NOT NULL,
    sq_menu numeric(18,0) NOT NULL,
    cd_projeto character varying(60),
    cd_acordo character varying(60),
    inicio date NOT NULL,
    fim date,
    ordem numeric(4,0) NOT NULL,
    vencimento date NOT NULL,
    valor numeric(18,4) NOT NULL,
    quitacao date
);


ALTER TABLE siw.vw_acordo_parcela OWNER TO postgres;

--
-- TOC entry 1568 (class 1259 OID 16496)
-- Dependencies: 9
-- Name: vw_calendario; Type: TABLE; Schema: siw; Owner: postgres; Tablespace: 
--

CREATE TABLE vw_calendario (
    chave numeric(18,0) NOT NULL,
    cliente numeric(18,0) NOT NULL,
    ano character varying(4),
    data_formatada date,
    nome character varying(60) NOT NULL,
    expediente character varying(1) NOT NULL,
    nm_expediente character varying(15)
);


ALTER TABLE siw.vw_calendario OWNER TO postgres;

--
-- TOC entry 1569 (class 1259 OID 16499)
-- Dependencies: 9
-- Name: vw_gestores_modulo; Type: TABLE; Schema: siw; Owner: postgres; Tablespace: 
--

CREATE TABLE vw_gestores_modulo (
    cliente numeric(18,0) NOT NULL,
    sq_pessoa numeric(18,0) NOT NULL,
    nome character varying(63) NOT NULL,
    nome_resumido character varying(21),
    sq_modulo numeric(18,0) NOT NULL,
    sg_modulo character varying(3) NOT NULL,
    nm_modulo character varying(60) NOT NULL,
    sq_pessoa_endereco numeric(18,0) NOT NULL,
    logradouro character varying(65) NOT NULL
);


ALTER TABLE siw.vw_gestores_modulo OWNER TO postgres;

--
-- TOC entry 1570 (class 1259 OID 16502)
-- Dependencies: 9
-- Name: vw_projetos; Type: TABLE; Schema: siw; Owner: postgres; Tablespace: 
--

CREATE TABLE vw_projetos (
    cliente numeric(18,0) NOT NULL,
    nm_menu character varying(40) NOT NULL,
    sq_menu numeric(18,0) NOT NULL,
    sq_siw_solicitacao numeric(18,0) NOT NULL,
    titulo character varying(100),
    codigo_interno character varying(60),
    codigo_externo character varying(60),
    descricao character varying(2000)
);


ALTER TABLE siw.vw_projetos OWNER TO postgres;

--
-- TOC entry 1571 (class 1259 OID 16508)
-- Dependencies: 9
-- Name: vw_usuarios; Type: TABLE; Schema: siw; Owner: postgres; Tablespace: 
--

CREATE TABLE vw_usuarios (
    cliente numeric(18,0) NOT NULL,
    sq_pessoa numeric(18,0) NOT NULL,
    nome character varying(63) NOT NULL,
    nome_resumido character varying(21),
    tipo_autenticacao character varying(1) NOT NULL,
    username character varying(60) NOT NULL,
    senha character varying(255) NOT NULL,
    assinatura character varying(255),
    gestor_seguranca character varying(1) NOT NULL,
    gestor_sistema character varying(1) NOT NULL,
    sq_unidade numeric(10,0) NOT NULL,
    nm_unidade character varying(60) NOT NULL,
    sg_unidade character varying(20) NOT NULL,
    sq_localizacao numeric(10,0) NOT NULL,
    nm_local character varying(30) NOT NULL
);


ALTER TABLE siw.vw_usuarios OWNER TO postgres;

SET search_path = contab, pg_catalog;

--
-- TOC entry 1866 (class 2606 OID 16515)
-- Dependencies: 1542 1542
-- Name: Cod_Centro_Custo; Type: CONSTRAINT; Schema: contab; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "Centro_Custo"
    ADD CONSTRAINT "Cod_Centro_Custo" PRIMARY KEY ("Cod_Centro_Custo");


--
-- TOC entry 1868 (class 2606 OID 16517)
-- Dependencies: 1543 1543 1543
-- Name: calendario_pkey; Type: CONSTRAINT; Schema: contab; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY calendario
    ADD CONSTRAINT calendario_pkey PRIMARY KEY (data_ca, feriado_ca);


--
-- TOC entry 1893 (class 2606 OID 16519)
-- Dependencies: 1557 1557
-- Name: centro_custo_pkey; Type: CONSTRAINT; Schema: contab; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_teste_centro_custo
    ADD CONSTRAINT centro_custo_pkey PRIMARY KEY (centro_custo);


--
-- TOC entry 1870 (class 2606 OID 16521)
-- Dependencies: 1545 1545 1545
-- Name: controle_mov_contabil_pkey; Type: CONSTRAINT; Schema: contab; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY controle_mov_contabil
    ADD CONSTRAINT controle_mov_contabil_pkey PRIMARY KEY (anocontabil, mescontabil);


--
-- TOC entry 1872 (class 2606 OID 16523)
-- Dependencies: 1546 1546
-- Name: descr_padrao_pkey; Type: CONSTRAINT; Schema: contab; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY descr_padrao
    ADD CONSTRAINT descr_padrao_pkey PRIMARY KEY (coddescricao_dp);


--
-- TOC entry 1876 (class 2606 OID 16525)
-- Dependencies: 1548 1548 1548 1548
-- Name: lanc_ocorr_pkey; Type: CONSTRAINT; Schema: contab; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY lanc_ocorr
    ADD CONSTRAINT lanc_ocorr_pkey PRIMARY KEY (fk_numlancamentoautom_ltocr, fk_seqlanautom_ltocr, fk_codocorrencialanautom_ltocr);


--
-- TOC entry 1879 (class 2606 OID 16527)
-- Dependencies: 1549 1549
-- Name: lanc_padrao_pkey; Type: CONSTRAINT; Schema: contab; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY lanc_padrao
    ADD CONSTRAINT lanc_padrao_pkey PRIMARY KEY (codlanpadrao_lp);


--
-- TOC entry 1883 (class 2606 OID 16529)
-- Dependencies: 1551 1551 1551
-- Name: lote_pkey; Type: CONSTRAINT; Schema: contab; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY lote
    ADD CONSTRAINT lote_pkey PRIMARY KEY (origemmovimento, dtiniciomovinterface);


--
-- TOC entry 1885 (class 2606 OID 16531)
-- Dependencies: 1552 1552
-- Name: ocorr_lanc_autom_pkey; Type: CONSTRAINT; Schema: contab; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY ocorr_lanc_autom
    ADD CONSTRAINT ocorr_lanc_autom_pkey PRIMARY KEY (codocorrencialanautom);


--
-- TOC entry 1881 (class 2606 OID 16533)
-- Dependencies: 1550 1550 1550 1550
-- Name: pk_lanc; Type: CONSTRAINT; Schema: contab; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY lancamentos
    ADD CONSTRAINT pk_lanc PRIMARY KEY (dtmovimento_lan, numlancamentodia_lan, seqlancamento);


--
-- TOC entry 1874 (class 2606 OID 16535)
-- Dependencies: 1547 1547 1547
-- Name: pk_lancautom; Type: CONSTRAINT; Schema: contab; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY lanc_autom
    ADD CONSTRAINT pk_lancautom PRIMARY KEY (numlancamentoautom, seqlancautom);


--
-- TOC entry 1889 (class 2606 OID 16537)
-- Dependencies: 1554 1554 1554 1554 1554
-- Name: pk_saldocontas; Type: CONSTRAINT; Schema: contab; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY saldo_contas
    ADD CONSTRAINT pk_saldocontas PRIMARY KEY (fk_codcontacontabil_sc, fk_anocontacontabil_sc, fk_mescontacontabil_sc, fk_centrocusto_sc);


--
-- TOC entry 1887 (class 2606 OID 16539)
-- Dependencies: 1553 1553
-- Name: plano_contas_pkey; Type: CONSTRAINT; Schema: contab; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY plano_contas
    ADD CONSTRAINT plano_contas_pkey PRIMARY KEY (codcontacontabil_pc);


--
-- TOC entry 1891 (class 2606 OID 16541)
-- Dependencies: 1556 1556
-- Name: status_pkey; Type: CONSTRAINT; Schema: contab; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY status
    ADD CONSTRAINT status_pkey PRIMARY KEY (codstatus);


SET search_path = orcam, pg_catalog;

--
-- TOC entry 1895 (class 2606 OID 16543)
-- Dependencies: 1558 1558 1558
-- Name: conta_tem_orcamento_no_ano_pk; Type: CONSTRAINT; Schema: orcam; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY conta_tem_orcamento_no_ano
    ADD CONSTRAINT conta_tem_orcamento_no_ano_pk PRIMARY KEY (ano_orcamento, pk_codcontaorcamento_pco);


--
-- TOC entry 1897 (class 2606 OID 16545)
-- Dependencies: 1559 1559
-- Name: controle_exercicio_orcamentario_pk; Type: CONSTRAINT; Schema: orcam; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY controle_exercicio_orcamentario
    ADD CONSTRAINT controle_exercicio_orcamentario_pk PRIMARY KEY (ano_orcamento);


--
-- TOC entry 1899 (class 2606 OID 16547)
-- Dependencies: 1561 1561
-- Name: mes_trimestre_pk; Type: CONSTRAINT; Schema: orcam; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY mes_trimestre
    ADD CONSTRAINT mes_trimestre_pk PRIMARY KEY (pk_mes);


--
-- TOC entry 1901 (class 2606 OID 16549)
-- Dependencies: 1562 1562
-- Name: plano_contas_orcamento_pk; Type: CONSTRAINT; Schema: orcam; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY plano_contas_orcamento
    ADD CONSTRAINT plano_contas_orcamento_pk PRIMARY KEY (pk_codcontaorcamento_pco);


--
-- TOC entry 1903 (class 2606 OID 16551)
-- Dependencies: 1564 1564
-- Name: processamento_pk; Type: CONSTRAINT; Schema: orcam; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY processamento
    ADD CONSTRAINT processamento_pk PRIMARY KEY (pk_codigoprocessamento);


--
-- TOC entry 1905 (class 2606 OID 16553)
-- Dependencies: 1565 1565 1565
-- Name: saldo_orcamento_pk; Type: CONSTRAINT; Schema: orcam; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY saldo_orcamento
    ADD CONSTRAINT saldo_orcamento_pk PRIMARY KEY (pk_codcontaorcamento_pco, ano_orcamento);


SET search_path = contab, pg_catalog;

SET default_tablespace = tbs_indexes;

--
-- TOC entry 1864 (class 1259 OID 16554)
-- Dependencies: 1542
-- Name: Centro_Custo_index; Type: INDEX; Schema: contab; Owner: postgres; Tablespace: tbs_indexes
--

CREATE INDEX "Centro_Custo_index" ON "Centro_Custo" USING btree ("Cod_Centro_Custo");


--
-- TOC entry 1877 (class 1259 OID 16555)
-- Dependencies: 1549
-- Name: LancPadrao_index; Type: INDEX; Schema: contab; Owner: postgres; Tablespace: tbs_indexes
--

CREATE UNIQUE INDEX "LancPadrao_index" ON lanc_padrao USING btree (codlanpadrao_lp);


--
-- TOC entry 1913 (class 2606 OID 16556)
-- Dependencies: 1553 1886 1553
-- Name: contas_grupo; Type: FK CONSTRAINT; Schema: contab; Owner: postgres
--

ALTER TABLE ONLY plano_contas
    ADD CONSTRAINT contas_grupo FOREIGN KEY (fk_codcontacontabilsuperior_pc) REFERENCES plano_contas(codcontacontabil_pc);


--
-- TOC entry 1908 (class 2606 OID 16561)
-- Dependencies: 1547 1873 1548 1548 1547
-- Name: lan_tem_ocorr; Type: FK CONSTRAINT; Schema: contab; Owner: postgres
--

ALTER TABLE ONLY lanc_ocorr
    ADD CONSTRAINT lan_tem_ocorr FOREIGN KEY (fk_numlancamentoautom_ltocr, fk_seqlanautom_ltocr) REFERENCES lanc_autom(numlancamentoautom, seqlancautom) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1910 (class 2606 OID 16566)
-- Dependencies: 1550 1886 1553
-- Name: lancamento_pertence_ao_plano; Type: FK CONSTRAINT; Schema: contab; Owner: postgres
--

ALTER TABLE ONLY lancamentos
    ADD CONSTRAINT lancamento_pertence_ao_plano FOREIGN KEY (fk_contacontabillancamento_lan) REFERENCES plano_contas(codcontacontabil_pc);


--
-- TOC entry 1911 (class 2606 OID 16571)
-- Dependencies: 1550 1547 1547 1873 1550
-- Name: lancautom_virou_lanc; Type: FK CONSTRAINT; Schema: contab; Owner: postgres
--

ALTER TABLE ONLY lancamentos
    ADD CONSTRAINT lancautom_virou_lanc FOREIGN KEY (fk_numlancamentoautom, fk_seqlancautom) REFERENCES lanc_autom(numlancamentoautom, seqlancautom) ON UPDATE SET DEFAULT ON DELETE SET DEFAULT;


--
-- TOC entry 1907 (class 2606 OID 16576)
-- Dependencies: 1547 1882 1551 1551 1547
-- Name: lote_tem_lanc; Type: FK CONSTRAINT; Schema: contab; Owner: postgres
--

ALTER TABLE ONLY lanc_autom
    ADD CONSTRAINT lote_tem_lanc FOREIGN KEY (fk_origemmovimento, fk_dtiniciomovinterface) REFERENCES lote(origemmovimento, dtiniciomovinterface) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1909 (class 2606 OID 16581)
-- Dependencies: 1552 1548 1884
-- Name: ocorr_estah_em_lotes; Type: FK CONSTRAINT; Schema: contab; Owner: postgres
--

ALTER TABLE ONLY lanc_ocorr
    ADD CONSTRAINT ocorr_estah_em_lotes FOREIGN KEY (fk_codocorrencialanautom_ltocr) REFERENCES ocorr_lanc_autom(codocorrencialanautom) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1906 (class 2606 OID 16586)
-- Dependencies: 1553 1886 1544
-- Name: plano_contas_contas_lanc_padrao_fk; Type: FK CONSTRAINT; Schema: contab; Owner: postgres
--

ALTER TABLE ONLY contas_lanc_padrao
    ADD CONSTRAINT plano_contas_contas_lanc_padrao_fk FOREIGN KEY (codcontacontabil_pc) REFERENCES plano_contas(codcontacontabil_pc);


--
-- TOC entry 1914 (class 2606 OID 16591)
-- Dependencies: 1553 1886 1553
-- Name: plano_contas_plano_contas_fk; Type: FK CONSTRAINT; Schema: contab; Owner: postgres
--

ALTER TABLE ONLY plano_contas
    ADD CONSTRAINT plano_contas_plano_contas_fk FOREIGN KEY (fk_codcontacontabildeficit_pc) REFERENCES plano_contas(codcontacontabil_pc);


--
-- TOC entry 1915 (class 2606 OID 16596)
-- Dependencies: 1886 1553 1553
-- Name: plano_contas_plano_contas_superavit_fk; Type: FK CONSTRAINT; Schema: contab; Owner: postgres
--

ALTER TABLE ONLY plano_contas
    ADD CONSTRAINT plano_contas_plano_contas_superavit_fk FOREIGN KEY (fk_codcontacontabilsuperavit_pc) REFERENCES plano_contas(codcontacontabil_pc);


--
-- TOC entry 1916 (class 2606 OID 16601)
-- Dependencies: 1554 1886 1553
-- Name: saldo_eh_de_conta_contabil; Type: FK CONSTRAINT; Schema: contab; Owner: postgres
--

ALTER TABLE ONLY saldo_contas
    ADD CONSTRAINT saldo_eh_de_conta_contabil FOREIGN KEY (fk_codcontacontabil_sc) REFERENCES plano_contas(codcontacontabil_pc);


--
-- TOC entry 1912 (class 2606 OID 16606)
-- Dependencies: 1890 1551 1556
-- Name: tem_lote; Type: FK CONSTRAINT; Schema: contab; Owner: postgres
--

ALTER TABLE ONLY lote
    ADD CONSTRAINT tem_lote FOREIGN KEY (statusmovimento) REFERENCES status(codstatus) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 1917 (class 2606 OID 16611)
-- Dependencies: 1545 1869 1554 1554 1545
-- Name: tem_saldos; Type: FK CONSTRAINT; Schema: contab; Owner: postgres
--

ALTER TABLE ONLY saldo_contas
    ADD CONSTRAINT tem_saldos FOREIGN KEY (fk_anocontacontabil_sc, fk_mescontacontabil_sc) REFERENCES controle_mov_contabil(anocontabil, mescontabil);


SET search_path = orcam, pg_catalog;

--
-- TOC entry 1918 (class 2606 OID 16616)
-- Dependencies: 1559 1558 1896
-- Name: controle_exercicio_orcamentario_conta_tem_orcamento_no_ano_fk; Type: FK CONSTRAINT; Schema: orcam; Owner: postgres
--

ALTER TABLE ONLY conta_tem_orcamento_no_ano
    ADD CONSTRAINT controle_exercicio_orcamentario_conta_tem_orcamento_no_ano_fk FOREIGN KEY (ano_orcamento) REFERENCES controle_exercicio_orcamentario(ano_orcamento);


--
-- TOC entry 1919 (class 2606 OID 16621)
-- Dependencies: 1562 1558 1900
-- Name: plano_contas_orcamento_conta_tem_orcamento_no_ano_fk; Type: FK CONSTRAINT; Schema: orcam; Owner: postgres
--

ALTER TABLE ONLY conta_tem_orcamento_no_ano
    ADD CONSTRAINT plano_contas_orcamento_conta_tem_orcamento_no_ano_fk FOREIGN KEY (pk_codcontaorcamento_pco) REFERENCES plano_contas_orcamento(pk_codcontaorcamento_pco);


--
-- TOC entry 1920 (class 2606 OID 16626)
-- Dependencies: 1565 1900 1562
-- Name: plano_contas_orcamento_saldo_orcamento_fk; Type: FK CONSTRAINT; Schema: orcam; Owner: postgres
--

ALTER TABLE ONLY saldo_orcamento
    ADD CONSTRAINT plano_contas_orcamento_saldo_orcamento_fk FOREIGN KEY (pk_codcontaorcamento_pco) REFERENCES plano_contas_orcamento(pk_codcontaorcamento_pco);


--
-- TOC entry 1921 (class 2606 OID 16631)
-- Dependencies: 1902 1564 1565
-- Name: processamento_saldo_orcamento_fk; Type: FK CONSTRAINT; Schema: orcam; Owner: postgres
--

ALTER TABLE ONLY saldo_orcamento
    ADD CONSTRAINT processamento_saldo_orcamento_fk FOREIGN KEY (pk_codigoprocessamento) REFERENCES processamento(pk_codigoprocessamento);


--
-- TOC entry 1926 (class 0 OID 0)
-- Dependencies: 10
-- Name: contab; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA contab FROM PUBLIC;
REVOKE ALL ON SCHEMA contab FROM postgres;
GRANT ALL ON SCHEMA contab TO postgres;
GRANT ALL ON SCHEMA contab TO PUBLIC;


--
-- TOC entry 1929 (class 0 OID 0)
-- Dependencies: 3
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


SET search_path = contab, pg_catalog;

--
-- TOC entry 1933 (class 0 OID 0)
-- Dependencies: 1543
-- Name: calendario; Type: ACL; Schema: contab; Owner: postgres
--

REVOKE ALL ON TABLE calendario FROM PUBLIC;
REVOKE ALL ON TABLE calendario FROM postgres;
GRANT ALL ON TABLE calendario TO postgres;


--
-- TOC entry 1937 (class 0 OID 0)
-- Dependencies: 1545
-- Name: controle_mov_contabil; Type: ACL; Schema: contab; Owner: postgres
--

REVOKE ALL ON TABLE controle_mov_contabil FROM PUBLIC;
REVOKE ALL ON TABLE controle_mov_contabil FROM postgres;
GRANT ALL ON TABLE controle_mov_contabil TO postgres;


--
-- TOC entry 1938 (class 0 OID 0)
-- Dependencies: 1546
-- Name: descr_padrao; Type: ACL; Schema: contab; Owner: postgres
--

REVOKE ALL ON TABLE descr_padrao FROM PUBLIC;
REVOKE ALL ON TABLE descr_padrao FROM postgres;
GRANT ALL ON TABLE descr_padrao TO postgres;


--
-- TOC entry 1939 (class 0 OID 0)
-- Dependencies: 1547
-- Name: lanc_autom; Type: ACL; Schema: contab; Owner: postgres
--

REVOKE ALL ON TABLE lanc_autom FROM PUBLIC;
REVOKE ALL ON TABLE lanc_autom FROM postgres;
GRANT ALL ON TABLE lanc_autom TO postgres;


--
-- TOC entry 1940 (class 0 OID 0)
-- Dependencies: 1548
-- Name: lanc_ocorr; Type: ACL; Schema: contab; Owner: postgres
--

REVOKE ALL ON TABLE lanc_ocorr FROM PUBLIC;
REVOKE ALL ON TABLE lanc_ocorr FROM postgres;
GRANT ALL ON TABLE lanc_ocorr TO postgres;


--
-- TOC entry 1942 (class 0 OID 0)
-- Dependencies: 1549
-- Name: lanc_padrao; Type: ACL; Schema: contab; Owner: postgres
--

REVOKE ALL ON TABLE lanc_padrao FROM PUBLIC;
REVOKE ALL ON TABLE lanc_padrao FROM postgres;
GRANT ALL ON TABLE lanc_padrao TO postgres;


--
-- TOC entry 1951 (class 0 OID 0)
-- Dependencies: 1550
-- Name: lancamentos; Type: ACL; Schema: contab; Owner: postgres
--

REVOKE ALL ON TABLE lancamentos FROM PUBLIC;
REVOKE ALL ON TABLE lancamentos FROM postgres;
GRANT ALL ON TABLE lancamentos TO postgres;


--
-- TOC entry 1952 (class 0 OID 0)
-- Dependencies: 1551
-- Name: lote; Type: ACL; Schema: contab; Owner: postgres
--

REVOKE ALL ON TABLE lote FROM PUBLIC;
REVOKE ALL ON TABLE lote FROM postgres;
GRANT ALL ON TABLE lote TO postgres;


--
-- TOC entry 1953 (class 0 OID 0)
-- Dependencies: 1552
-- Name: ocorr_lanc_autom; Type: ACL; Schema: contab; Owner: postgres
--

REVOKE ALL ON TABLE ocorr_lanc_autom FROM PUBLIC;
REVOKE ALL ON TABLE ocorr_lanc_autom FROM postgres;
GRANT ALL ON TABLE ocorr_lanc_autom TO postgres;


--
-- TOC entry 1963 (class 0 OID 0)
-- Dependencies: 1553
-- Name: plano_contas; Type: ACL; Schema: contab; Owner: postgres
--

REVOKE ALL ON TABLE plano_contas FROM PUBLIC;
REVOKE ALL ON TABLE plano_contas FROM postgres;
GRANT ALL ON TABLE plano_contas TO postgres;


--
-- TOC entry 1964 (class 0 OID 0)
-- Dependencies: 1554
-- Name: saldo_contas; Type: ACL; Schema: contab; Owner: postgres
--

REVOKE ALL ON TABLE saldo_contas FROM PUBLIC;
REVOKE ALL ON TABLE saldo_contas FROM postgres;
GRANT ALL ON TABLE saldo_contas TO postgres;


--
-- TOC entry 1965 (class 0 OID 0)
-- Dependencies: 1556
-- Name: status; Type: ACL; Schema: contab; Owner: postgres
--

REVOKE ALL ON TABLE status FROM PUBLIC;
REVOKE ALL ON TABLE status FROM postgres;
GRANT ALL ON TABLE status TO postgres;


-- Completed on 2009-08-14 11:04:56

--
-- PostgreSQL database dump complete
--

