---------------------------------------------
-- Export file for user ECW                --
-- Created by alexvp on 06/11/03, 08:58:45 --
---------------------------------------------

spool ecw_er.log

prompt
prompt Creating table S_UF
prompt ===================
prompt
create table S_UF
(
  SG_UF CHAR(2) not null,
  DS_UF VARCHAR2(30)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_UF
  add constraint PKS_UF primary key (SG_UF)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_CIDADE
prompt =======================
prompt
create table S_CIDADE
(
  CO_SEQ_CIDADE      NUMBER(10) not null,
  SG_UF              CHAR(2),
  NO_CIDADE          VARCHAR2(60),
  CATEGORIA          VARCHAR2(30),
  FONTE              VARCHAR2(30),
  LATITUDE           NUMBER,
  LONGITUDE          NUMBER,
  ALTITUDE           NUMBER,
  AREA               NUMBER(8,2),
  ANO_INSTAURACAO    NUMBER,
  MUNICIPIO_PERTENCE VARCHAR2(60),
  CO_UF              NUMBER,
  CO_CIDADE          NUMBER,
  DV                 NUMBER,
  CO_DISTRITO        NUMBER
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CIDADE
  add constraint PKS_CIDADE primary key (CO_SEQ_CIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CIDADE
  add constraint R132_552 foreign key (SG_UF)
  references S_UF (SG_UF) on delete cascade;

prompt
prompt Creating table S_ESCOLA
prompt =======================
prompt
create table S_ESCOLA
(
  CO_UNIDADE   CHAR(5) not null,
  DS_ESCOLA    CHAR(60),
  CO_SIGRE     CHAR(15),
  DS_ENDERECO  CHAR(90),
  DS_BAIRRO    CHAR(50),
  NU_CEP       CHAR(9),
  DS_CIDADE    CHAR(50),
  DS_UF_CIDADE CHAR(2),
  DS_GRE       CHAR(70)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ESCOLA
  add constraint U102_3 primary key (CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
create index IDX_SIGRE_ESCOLA on S_ESCOLA (CO_SIGRE,DS_ESCOLA)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_UNIDADE
prompt ========================
prompt
create table S_UNIDADE
(
  CO_UNIDADE        CHAR(5) not null,
  DS_UNIDADE        CHAR(60),
  CO_SEQ_CIDADE     NUMBER(10),
  TP_ESCOLA         CHAR(1),
  DS_NOME_RELATORIO CHAR(60),
  DS_VINHETA        CHAR(90),
  DS_ENDERECO       CHAR(90),
  DS_BAIRRO         VARCHAR2(20),
  NU_CEP            CHAR(9),
  DS_CIDADE         VARCHAR2(30),
  DS_UF_CIDADE      CHAR(2),
  NU_TELEFONE_1     VARCHAR2(14),
  NU_TELEFONE_2     VARCHAR2(14),
  NU_FAX            VARCHAR2(14),
  DS_E_MAIL         VARCHAR2(90),
  DS_PAGINA_WEB     VARCHAR2(90),
  DS_ATO            VARCHAR2(10),
  DS_NUMERO         VARCHAR2(6),
  DT_DATA           DATE,
  DS_ORGAO          VARCHAR2(30),
  DS_GRADE_CURRIC   VARCHAR2(10),
  NU_CGC_ESCOLA     VARCHAR2(18),
  NU_INSCR_ESCOLA   VARCHAR2(30),
  DS_DIRETOR        VARCHAR2(40),
  DS_SECRETARIO     VARCHAR2(40),
  TP_HISTORICO      VARCHAR2(7),
  FO_SIMBOLO        BLOB,
  DS_GRE            VARCHAR2(70),
  DS_RURAL          CHAR(1),
  TP_INCLUSIVA      CHAR(1),
  DT_ATUALIZACAO    DATE,
  NU_REMESSA        VARCHAR2(6),
  NU_ALUNOSATIVOS   NUMBER(10),
  NU_ATIVOS         NUMBER(10),
  NU_MATRICULADOS   NUMBER(10),
  NU_ALUNOSEJA1     NUMBER(10),
  NU_ALUNOSEJA2     NUMBER(10),
  NU_EJA1_ESCOLA    NUMBER(10),
  NU_EJA2_ESCOLA    NUMBER(10),
  NU_MAT_ESCOLA     NUMBER(10),
  NU_SEMTURMA       NUMBER(10)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_UNIDADE
  add constraint U179_91 primary key (CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_UNIDADE
  add constraint R180_605 foreign key (CO_SEQ_CIDADE)
  references S_CIDADE (CO_SEQ_CIDADE) on delete cascade;
alter table S_UNIDADE
  add constraint R180_606 foreign key (CO_UNIDADE)
  references S_ESCOLA (CO_UNIDADE) on delete cascade;

prompt
prompt Creating table AGE_CATEGORIA
prompt ============================
prompt
create table AGE_CATEGORIA
(
  CAT_SEQUENCIAL NUMBER(10) not null,
  CAT_DESCRICAO  CHAR(30),
  CO_UNIDADE     CHAR(5) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table AGE_CATEGORIA
  add constraint U103_4 primary key (CAT_SEQUENCIAL,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table AGE_CATEGORIA
  add constraint R_199 foreign key (CO_UNIDADE)
  references S_UNIDADE (CO_UNIDADE) on delete cascade;

prompt
prompt Creating table AGENDA
prompt =====================
prompt
create table AGENDA
(
  DS_USUARIO     CHAR(30),
  AGE_SEQUENCIAL NUMBER(10) not null,
  CO_UNIDADE     CHAR(5) not null,
  AGE_NOME       CHAR(50),
  AGE_ENDERECO   CHAR(40),
  AGE_BAIRRO     CHAR(20),
  AGE_CIDADE     CHAR(20),
  AGE_ESTADO     CHAR(2),
  AGE_CEP        CHAR(9),
  AGE_ENDERECOC  CHAR(40),
  AGE_BAIRROC    CHAR(20),
  AGE_CIDADEC    CHAR(20),
  AGE_ESTADOC    CHAR(2),
  AGE_CEPC       CHAR(9),
  AGE_EMAIL      CHAR(50),
  AGE_OBSERVACAO BLOB
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table AGENDA
  add constraint U104_5 primary key (AGE_SEQUENCIAL,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table TIPO_CONSULTA
prompt ============================
prompt
create table TIPO_CONSULTA
(
  TC_CODIGO    NUMBER(10) not null,
  TC_DESCRICAO VARCHAR2(40)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table TIPO_CONSULTA
  add constraint U105_6 primary key (TC_CODIGO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table CAMPOS_TP_CONSULTA
prompt =================================
prompt
create table CAMPOS_TP_CONSULTA
(
  TC_CODIGO     NUMBER(10),
  CC_CODIGO     NUMBER(10) not null,
  CC_CAMPO      VARCHAR2(40),
  CC_DESC_CAMPO VARCHAR2(40)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table CAMPOS_TP_CONSULTA
  add constraint U106_7 primary key (CC_CODIGO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table CAMPOS_TP_CONSULTA
  add constraint R102_510 foreign key (TC_CODIGO)
  references TIPO_CONSULTA (TC_CODIGO) on delete cascade;

prompt
prompt Creating table COMPROMISSO
prompt ==========================
prompt
create table COMPROMISSO
(
  DS_USUARIO     CHAR(30),
  COM_SEQUENCIAL NUMBER(10) not null,
  CO_UNIDADE     CHAR(5) not null,
  COM_DATA       DATE,
  COM_DESCRICAO  CHAR(80),
  COM_HORA       CHAR(5),
  COM_CONTATO    CHAR(30),
  COM_AVISO      DATE,
  COM_OBSERVACAO BLOB,
  COM_CONFIRMADO CHAR(3)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table COMPROMISSO
  add constraint U107_8 primary key (COM_SEQUENCIAL,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table COMPROMISSO
  add constraint R_202 foreign key (CO_UNIDADE)
  references S_UNIDADE (CO_UNIDADE) on delete cascade;

prompt
prompt Creating table DM_PARAMETROS
prompt ============================
prompt
create table DM_PARAMETROS
(
  DATAINICIOSEM DATE not null,
  DATAFIMSEM    DATE not null,
  ANO_SEM       CHAR(5) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table FONE
prompt ===================
prompt
create table FONE
(
  FONE_SEQUENCIAL NUMBER(10) not null,
  AGE_SEQUENCIAL  NUMBER(10) not null,
  CO_UNIDADE      CHAR(5) not null,
  CLI_CODIGO      NUMBER(10),
  FON_DESCR       CHAR(20),
  FON_TIPO        CHAR(1)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table FONE
  add constraint U108_9 primary key (FONE_SEQUENCIAL,CO_UNIDADE,AGE_SEQUENCIAL)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table FONE
  add constraint R_74 foreign key (AGE_SEQUENCIAL,CO_UNIDADE)
  references AGENDA (AGE_SEQUENCIAL,CO_UNIDADE) on delete cascade;

prompt
prompt Creating table LEMBRETE
prompt =======================
prompt
create table LEMBRETE
(
  DS_USUARIO      CHAR(30),
  LEMB_SEQUENCIAL NUMBER(10) not null,
  CO_UNIDADE      CHAR(5) not null,
  LEMB_DATA       DATE,
  LEMB_MEMO       BLOB,
  INDICADOR_LIDO  CHAR(1)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table LEMBRETE
  add constraint U109_10 primary key (LEMB_SEQUENCIAL,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table LEMBRETE
  add constraint R_201 foreign key (CO_UNIDADE)
  references S_UNIDADE (CO_UNIDADE) on delete cascade;

prompt
prompt Creating table PLAN_TABLE
prompt =========================
prompt
create table PLAN_TABLE
(
  STATEMENT_ID    VARCHAR2(30),
  TIMESTAMP       DATE,
  REMARKS         VARCHAR2(80),
  OPERATION       VARCHAR2(30),
  OPTIONS         VARCHAR2(30),
  OBJECT_NODE     VARCHAR2(128),
  OBJECT_OWNER    VARCHAR2(30),
  OBJECT_NAME     VARCHAR2(30),
  OBJECT_INSTANCE NUMBER,
  OBJECT_TYPE     VARCHAR2(30),
  OPTIMIZER       VARCHAR2(255),
  SEARCH_COLUMNS  NUMBER,
  ID              NUMBER,
  PARENT_ID       NUMBER,
  POSITION        NUMBER,
  COST            NUMBER,
  CARDINALITY     NUMBER,
  BYTES           NUMBER,
  OTHER_TAG       VARCHAR2(255),
  PARTITION_START VARCHAR2(255),
  PARTITION_STOP  VARCHAR2(255),
  PARTITION_ID    NUMBER,
  OTHER           LONG,
  DISTRIBUTION    VARCHAR2(30),
  CPU_COST        NUMBER,
  IO_COST         NUMBER,
  TEMP_SPACE      NUMBER
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table RECADO
prompt =====================
prompt
create table RECADO
(
  REC_SEQUENCIAL  NUMBER(10) not null,
  REC_DATAENVIO   DATE,
  CO_UNIDADE      CHAR(5) not null,
  REC_DATALEITURA DATE,
  REC_MEMO        BLOB,
  REC_DE          CHAR(30),
  REC_PARA        CHAR(30)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table RECADO
  add constraint U110_11 primary key (REC_SEQUENCIAL,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table RECADO
  add constraint R_200 foreign key (CO_UNIDADE)
  references S_UNIDADE (CO_UNIDADE) on delete cascade;

prompt
prompt Creating table RELSCFG
prompt ======================
prompt
create table RELSCFG
(
  REL_CODIGO    CHAR(5) not null,
  REL_DESCRICAO CHAR(40),
  CO_UNIDADE    CHAR(5) not null,
  REL_TIPO      CHAR(30)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table RELSCFG
  add constraint U111_12 primary key (REL_CODIGO,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table RELSCFG
  add constraint R_206 foreign key (CO_UNIDADE)
  references S_UNIDADE (CO_UNIDADE) on delete cascade;

prompt
prompt Creating table RELSCPO
prompt ======================
prompt
create table RELSCPO
(
  REL_CODIGO       CHAR(5) not null,
  REL_COMPTAG      NUMBER(10) not null,
  REL_COMPCONTEUDO BLOB,
  CO_UNIDADE       CHAR(5) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table RELSCPO
  add constraint U112_13 primary key (REL_CODIGO,REL_COMPTAG,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table RELSCPO
  add constraint R_14 foreign key (REL_CODIGO,CO_UNIDADE)
  references RELSCFG (REL_CODIGO,CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_AGENDA_CATEGORIA
prompt =================================
prompt
create table S_AGENDA_CATEGORIA
(
  CO_UNIDADE     CHAR(5) not null,
  AGE_SEQUENCIAL NUMBER(10) not null,
  CAT_SEQUENCIAL NUMBER(10) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_AGENDA_CATEGORIA
  add constraint U113_14 primary key (CO_UNIDADE,AGE_SEQUENCIAL,CAT_SEQUENCIAL)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_AGENDA_CATEGORIA
  add constraint R109_518 foreign key (CAT_SEQUENCIAL,CO_UNIDADE)
  references AGE_CATEGORIA (CAT_SEQUENCIAL,CO_UNIDADE) on delete cascade;
alter table S_AGENDA_CATEGORIA
  add constraint R_196 foreign key (AGE_SEQUENCIAL,CO_UNIDADE)
  references AGENDA (AGE_SEQUENCIAL,CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_ORIGEM_ESCOLA
prompt ==============================
prompt
create table S_ORIGEM_ESCOLA
(
  CO_ORIGEM_ESCOLA NUMBER(10) not null,
  DS_ORIGEM_ESCOLA VARCHAR2(30)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ORIGEM_ESCOLA
  add constraint PKS_ORIGEM_ESCOLA primary key (CO_ORIGEM_ESCOLA)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_ALUNO
prompt ======================
prompt
create table S_ALUNO
(
  CO_ALUNO           CHAR(12) not null,
  DS_ALUNO           VARCHAR2(40) not null,
  CO_SEQ_CIDADE      NUMBER(10),
  DT_NASCIMENTO      DATE,
  DS_ALUNO_ORDEM     VARCHAR2(40),
  TP_SEXO_ALUNO      CHAR(1),
  DS_NATURALIDADE    VARCHAR2(30),
  DS_UF_NASCIMENTO   CHAR(2),
  DS_NACIONALIDADE   VARCHAR2(20),
  DS_ENDERECO        VARCHAR2(40),
  DS_BAIRRO          VARCHAR2(20),
  NU_CEP             CHAR(9),
  DS_CIDADE          VARCHAR2(20),
  DS_UF_CIDADE       CHAR(2),
  DS_E_MAIL          VARCHAR2(100),
  TP_ESTADO_CIVIL    VARCHAR2(12),
  DS_CONJUGE         VARCHAR2(40),
  NU_RG              VARCHAR2(15),
  DS_ORGAO_EMISSOR   VARCHAR2(30),
  DT_EMISSAO         DATE,
  NU_CPF             VARCHAR2(14),
  DS_FICHA_MEDICA    BLOB,
  TP_ESCOLA_ORIGEM   VARCHAR2(10),
  DT_INGRESSO        DATE,
  NU_TEMPO_ESCOLAR   CHAR(2),
  DS_CERTIDAO        VARCHAR2(10),
  NU_CERTIDAO        VARCHAR2(10),
  NU_LIVRO           VARCHAR2(5),
  NU_FOLHA           VARCHAR2(5),
  DS_CARTORIO        VARCHAR2(15),
  DS_CIDADE_CERTIDAO VARCHAR2(20),
  DS_FOTO            BLOB,
  DS_UF_CERTIDAO     CHAR(2),
  NU_RESERVISTA      VARCHAR2(12),
  NU_TITULO_ELEITOR  VARCHAR2(12),
  DS_ZONA            CHAR(3),
  DS_SECAO           CHAR(3),
  DS_UF_SECAO        CHAR(2),
  DS_PAI             VARCHAR2(40),
  DS_MAE             VARCHAR2(40),
  CO_ORIGEM_ESCOLA   NUMBER(10),
  DS_WEB             VARCHAR2(150),
  ID_ATIVO_PASSIVO   CHAR(1),
  CO_UNIDADE         CHAR(5),
  CO_ALUNO_ANTIGO    CHAR(12),
  DS_CATEGORIA       VARCHAR2(4),
  TP_ANEE            VARCHAR2(4),
  DS_ENDERECO_PAI    VARCHAR2(40),
  DS_ENDERECO_MAE    VARCHAR2(40),
  DS_TELEFONE_PAI    VARCHAR2(13),
  DS_TELEFONE_MAE    VARCHAR2(13),
  DS_PROBSAUDE       VARCHAR2(40),
  DS_ACOMPANHAMENTO  VARCHAR2(40),
  TP_VISAO           CHAR(1),
  DS_ALERGIA_ALIMENT VARCHAR2(60),
  DS_ALERGIA_MEDICAM VARCHAR2(60),
  DS_REMEDIOS        VARCHAR2(60),
  TP_ESCOLA_PARQUE   CHAR(3),
  TP_EJA             CHAR(1),
  TP_NEURO           CHAR(1),
  TP_PSICO           CHAR(1),
  TP_CARDIO          CHAR(1),
  TP_AUDICAO         CHAR(1),
  CO_TIPO_CURSO      NUMBER(10),
  SG_SERIE           CHAR(5),
  CO_TURNO           CHAR(2),
  TP_CIL             CHAR(3)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO
  add constraint U196_155 primary key (CO_ALUNO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO
  add constraint R110_519 foreign key (CO_SEQ_CIDADE)
  references S_CIDADE (CO_SEQ_CIDADE) on delete cascade;
alter table S_ALUNO
  add constraint R_112 foreign key (CO_ORIGEM_ESCOLA)
  references S_ORIGEM_ESCOLA (CO_ORIGEM_ESCOLA) on delete cascade;
alter table S_ALUNO
  add constraint R_142 foreign key (CO_UNIDADE)
  references S_UNIDADE (CO_UNIDADE) on delete cascade;
create index ALUNO_MAE on S_ALUNO (DS_MAE)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
create index ALUNO_NASC on S_ALUNO (DT_NASCIMENTO)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
create index ALUNO_NOME on S_ALUNO (DS_ALUNO)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
create index ALUNO_ORDEM on S_ALUNO (DS_ALUNO_ORDEM)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
create index IDX_DUPLICIDADE on S_ALUNO (DS_ALUNO,DS_MAE,DT_NASCIMENTO)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
create index IDX_IDATPASS on S_ALUNO (ID_ATIVO_PASSIVO)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
create index IDX_TPANEE on S_ALUNO (TP_ANEE)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_PERIODOUNIDADE
prompt ===============================
prompt
create table S_PERIODOUNIDADE
(
  ANO_SEM       CHAR(5) not null,
  CO_UNIDADE    CHAR(5) not null,
  TP_ANO_LETIVO CHAR(1)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_PERIODOUNIDADE
  add constraint U115_16 primary key (ANO_SEM,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_PERIODOUNIDADE
  add constraint R164_590 foreign key (CO_UNIDADE)
  references S_UNIDADE (CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_ALUNO_PER_UNID
prompt ===============================
prompt
create table S_ALUNO_PER_UNID
(
  ANO_SEM           CHAR(5) not null,
  CO_ALUNO          CHAR(12) not null,
  CO_UNIDADE        CHAR(5) not null,
  NU_ALTURA         VARCHAR2(4),
  NU_PESO           VARCHAR2(4),
  TP_APTO_ED_FISICA VARCHAR2(3),
  ST_ENS_RELIGIOSO  VARCHAR2(3),
  DS_SITUACAO_ALUNO VARCHAR2(12),
  DT_MATRICULA      DATE,
  TP_BOLSA_ESCOLA   CHAR(3),
  NU_BOLSA_ESCOLA   CHAR(10),
  DS_PROJINTD       VARCHAR2(40),
  NU_PE             NUMBER(10),
  NU_UNIFORME       VARCHAR2(3),
  DT_ATUALIZA_ALUNO DATE
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_PER_UNID
  add constraint U118_19 primary key (ANO_SEM,CO_ALUNO,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_PER_UNID
  add constraint FK_ALUPERUN_ALU foreign key (CO_ALUNO)
  references S_ALUNO (CO_ALUNO) on delete cascade;
alter table S_ALUNO_PER_UNID
  add constraint R121_537 foreign key (ANO_SEM,CO_UNIDADE)
  references S_PERIODOUNIDADE (ANO_SEM,CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_DISCIPLINA
prompt ===========================
prompt
create table S_DISCIPLINA
(
  CO_DISCIPLINA      CHAR(4) not null,
  DS_DISCIPLINA      VARCHAR2(60),
  ANO_SEM            CHAR(5) not null,
  DS_ORDEM_IMP       NUMBER(10),
  CO_UNIDADE         CHAR(5) not null,
  NU_DISC_CREDITO    NUMBER(10),
  TP_DISCIPLINA      CHAR(30),
  CO_DISC_FEDF       CHAR(15),
  CO_TIPO_DISCIPLINA NUMBER(10)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_DISCIPLINA
  add constraint U116_17 primary key (CO_DISCIPLINA,ANO_SEM,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_DISCIPLINA
  add constraint R142_566 foreign key (ANO_SEM,CO_UNIDADE)
  references S_PERIODOUNIDADE (ANO_SEM,CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_TIPO_CURSO
prompt ===========================
prompt
create table S_TIPO_CURSO
(
  CO_TIPO_CURSO NUMBER(10) not null,
  SG_TIPO_CURSO CHAR(3) not null,
  DS_TIPO_CURSO VARCHAR2(50) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TIPO_CURSO
  add constraint PKS_TIPO_CURSO primary key (CO_TIPO_CURSO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_SERIE
prompt ======================
prompt
create table S_SERIE
(
  SG_SERIE      VARCHAR2(5) not null,
  CO_TIPO_CURSO NUMBER(10),
  DESCR_SERIE   VARCHAR2(50)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_SERIE
  add constraint XPKS_SERIE primary key (SG_SERIE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_SERIE
  add constraint FK_TIP_CURSO_SERIE foreign key (CO_TIPO_CURSO)
  references S_TIPO_CURSO (CO_TIPO_CURSO) on delete cascade;

prompt
prompt Creating table S_ALUNO_ADAPTACAO
prompt ================================
prompt
create table S_ALUNO_ADAPTACAO
(
  CO_UNIDADE     CHAR(5) not null,
  ANO_SEM        CHAR(5) not null,
  CO_DISCIPLINA  CHAR(4) not null,
  NU_NOTA        CHAR(5),
  NU_AULAS_DADAS CHAR(3),
  SG_SERIE       VARCHAR2(5) not null,
  CO_ALUNO       CHAR(12) not null,
  NU_FALTAS      NUMBER(10)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_ADAPTACAO
  add constraint U119_20 primary key (CO_UNIDADE,ANO_SEM,CO_DISCIPLINA,CO_ALUNO,SG_SERIE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_ADAPTACAO
  add constraint R111_520 foreign key (ANO_SEM,CO_ALUNO,CO_UNIDADE)
  references S_ALUNO_PER_UNID (ANO_SEM,CO_ALUNO,CO_UNIDADE) on delete cascade;
alter table S_ALUNO_ADAPTACAO
  add constraint R_120 foreign key (SG_SERIE)
  references S_SERIE (SG_SERIE) on delete cascade;
alter table S_ALUNO_ADAPTACAO
  add constraint R_143 foreign key (CO_DISCIPLINA,ANO_SEM,CO_UNIDADE)
  references S_DISCIPLINA (CO_DISCIPLINA,ANO_SEM,CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_ALUNO_APROVEIT
prompt ===============================
prompt
create table S_ALUNO_APROVEIT
(
  CO_UNIDADE     CHAR(5) not null,
  ANO_SEM        CHAR(5) not null,
  CO_DISCIPLINA  CHAR(4) not null,
  SG_SERIE       VARCHAR2(5) not null,
  NU_NOTA        VARCHAR2(10),
  NU_AULAS_DADAS VARCHAR2(4),
  CO_ALUNO       CHAR(12) not null,
  NU_FALTAS      NUMBER(10),
  ID_EXAME       CHAR(1),
  DT_CONCLUSAO   DATE,
  DS_ESTRATEGIA  VARCHAR2(40)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_APROVEIT
  add constraint U186_104 primary key (CO_UNIDADE,ANO_SEM,CO_DISCIPLINA,CO_ALUNO,SG_SERIE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_APROVEIT
  add constraint R112_521 foreign key (ANO_SEM,CO_ALUNO,CO_UNIDADE)
  references S_ALUNO_PER_UNID (ANO_SEM,CO_ALUNO,CO_UNIDADE) on delete cascade;
alter table S_ALUNO_APROVEIT
  add constraint R112_522 foreign key (CO_DISCIPLINA,ANO_SEM,CO_UNIDADE)
  references S_DISCIPLINA (CO_DISCIPLINA,ANO_SEM,CO_UNIDADE) on delete cascade;
alter table S_ALUNO_APROVEIT
  add constraint R_119 foreign key (SG_SERIE)
  references S_SERIE (SG_SERIE) on delete cascade;

prompt
prompt Creating table S_ALUNO_AVANCOESTU
prompt =================================
prompt
create table S_ALUNO_AVANCOESTU
(
  SG_SERIE        VARCHAR2(5) not null,
  ANO_SEM         CHAR(5) not null,
  CO_ALUNO        CHAR(12) not null,
  CO_UNIDADE      CHAR(5) not null,
  DT_AVANCOESTUDO DATE
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_AVANCOESTU
  add constraint U209_214 primary key (SG_SERIE,ANO_SEM,CO_ALUNO,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_AVANCOESTU
  add constraint R209_219 foreign key (ANO_SEM,CO_ALUNO,CO_UNIDADE)
  references S_ALUNO_PER_UNID (ANO_SEM,CO_ALUNO,CO_UNIDADE) on delete cascade;
alter table S_ALUNO_AVANCOESTU
  add constraint R209_220 foreign key (SG_SERIE)
  references S_SERIE (SG_SERIE) on delete cascade;

prompt
prompt Creating table S_CURSO
prompt ======================
prompt
create table S_CURSO
(
  CO_CURSO           NUMBER(10) not null,
  DS_CURSO           VARCHAR2(60),
  CO_UNIDADE         CHAR(5) not null,
  TP_RECUPERACAO     VARCHAR2(10),
  CO_GRADE_CURRIC    CHAR(10),
  CO_TIPO_CURSO      NUMBER(10),
  DS_FORMULA_1_BIM   VARCHAR2(100),
  ANO                NUMBER(10),
  TURNO              CHAR(2),
  DS_FORMULA_2_BIM   VARCHAR2(100),
  DS_FORMULA_3_BIM   VARCHAR2(100),
  DS_FORMULA_4_BIM   VARCHAR2(100),
  DS_FORMULA_1_SEM   VARCHAR2(100),
  DS_FORMULA_2_SEM   VARCHAR2(100),
  DS_FORM_MD_ANUAL   VARCHAR2(100),
  DS_FORM_NOTA_FINAL VARCHAR2(100),
  ST_ANO_LETIVO_ENC  CHAR(3),
  NU_MEDIA_NOTA      CHAR(5),
  DS_NOTA_ACUMULADA  VARCHAR2(12),
  TP_RECALC_MD_SEM   CHAR(3),
  ST_ARRED_NOTA_SEM  CHAR(3),
  NU_MD_MINIMA_2_SEM NUMBER(10),
  NU_MAT_REC_FINAL   NUMBER(10),
  NU_FREQ_MINIMA_OBR NUMBER(10),
  TP_FREQUENCIA_MIN  VARCHAR2(25),
  NU_MD_MIN_FREQ_MEN NUMBER(10),
  ST_AULA_SABADO     CHAR(3),
  DS_PARECER_LEGAL   VARCHAR2(50),
  NU_DIAS_LETIVOS_B1 VARCHAR2(2),
  NU_DIAS_LETIVOS_B2 VARCHAR2(2),
  NU_DIAS_LETIVOS_B3 CHAR(2),
  NU_DIAS_LETIVOS_B4 CHAR(2),
  NU_MD_MIN_REC_ESP  CHAR(5),
  DS_FORMULA_APOS_S1 VARCHAR2(100),
  DS_FORMULA_APOS_S2 VARCHAR2(100),
  ANO_SEM            CHAR(5) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CURSO
  add constraint U121_22 primary key (CO_CURSO,ANO_SEM,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CURSO
  add constraint R135_555 foreign key (ANO_SEM,CO_UNIDADE)
  references S_PERIODOUNIDADE (ANO_SEM,CO_UNIDADE) on delete cascade;
alter table S_CURSO
  add constraint R135_556 foreign key (CO_TIPO_CURSO)
  references S_TIPO_CURSO (CO_TIPO_CURSO) on delete cascade;

prompt
prompt Creating table S_CURSO_SERIE
prompt ============================
prompt
create table S_CURSO_SERIE
(
  CO_CURSO     NUMBER(10) not null,
  ANO_SEM      CHAR(5) not null,
  CO_SEQ_SERIE NUMBER(10) not null,
  CO_UNIDADE   CHAR(5) not null,
  SG_SERIE     VARCHAR2(5)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CURSO_SERIE
  add constraint U126_27 primary key (ANO_SEM,CO_CURSO,CO_SEQ_SERIE,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CURSO_SERIE
  add constraint FK_SERIE_CURSO_SER foreign key (SG_SERIE)
  references S_SERIE (SG_SERIE) on delete cascade;
alter table S_CURSO_SERIE
  add constraint R139_561 foreign key (CO_CURSO,ANO_SEM,CO_UNIDADE)
  references S_CURSO (CO_CURSO,ANO_SEM,CO_UNIDADE) on delete cascade;
create index FOREIGN131 on S_CURSO_SERIE (CO_CURSO)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_ALUNO_CLASS
prompt ============================
prompt
create table S_ALUNO_CLASS
(
  ANO_SEM        CHAR(5) not null,
  CO_ALUNO       CHAR(12) not null,
  CO_UNIDADE     CHAR(5) not null,
  CO_SEQ_SERIE   NUMBER(10),
  CO_CURSO       NUMBER(10),
  SO_EXAME       VARCHAR2(15),
  DT_EXAME       DATE,
  APTO_CURSAR    VARCHAR2(5),
  DT_SOLICITACAO DATE
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_CLASS
  add constraint PK_ALUNOCLASS primary key (ANO_SEM,CO_ALUNO,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_CLASS
  add constraint FK_ALUCLAS_CURSER foreign key (ANO_SEM,CO_CURSO,CO_SEQ_SERIE,CO_UNIDADE)
  references S_CURSO_SERIE (ANO_SEM,CO_CURSO,CO_SEQ_SERIE,CO_UNIDADE) on delete cascade;
alter table S_ALUNO_CLASS
  add constraint R210_225 foreign key (ANO_SEM,CO_ALUNO,CO_UNIDADE)
  references S_ALUNO_PER_UNID (ANO_SEM,CO_ALUNO,CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_ALUNO_CLASSIFICA
prompt =================================
prompt
create table S_ALUNO_CLASSIFICA
(
  CO_ALUNO      CHAR(12) not null,
  ANO_SEM       CHAR(5) not null,
  CO_UNIDADE    CHAR(5) not null,
  NU_SOMA_NOTA  FLOAT,
  NU_CLASSIFICA NUMBER(10)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_CLASSIFICA
  add constraint PKS_ALUNO_CLASSIFI primary key (CO_ALUNO,ANO_SEM,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_CLASSIFICA
  add constraint FK_ALUCLAS_ALU foreign key (CO_ALUNO)
  references S_ALUNO (CO_ALUNO) on delete cascade;
alter table S_ALUNO_CLASSIFICA
  add constraint R113_523 foreign key (ANO_SEM,CO_UNIDADE)
  references S_PERIODOUNIDADE (ANO_SEM,CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_ALUNO_DEPENDENC
prompt ================================
prompt
create table S_ALUNO_DEPENDENC
(
  CO_UNIDADE     CHAR(5) not null,
  ANO_SEM        CHAR(5) not null,
  CO_DISCIPLINA  CHAR(4) not null,
  CO_ALUNO       CHAR(12) not null,
  NU_NOTA        CHAR(5),
  SG_SERIE       VARCHAR2(5) not null,
  NU_AULAS_DADAS CHAR(3),
  NU_FALTAS      NUMBER(10),
  DP_SERIE       VARCHAR2(40),
  DS_OPCAO       VARCHAR2(40),
  DT_OPCAO       DATE,
  DS_RESULTADO   VARCHAR2(40)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_DEPENDENC
  add constraint U189_119 primary key (CO_UNIDADE,ANO_SEM,CO_DISCIPLINA,CO_ALUNO,SG_SERIE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_DEPENDENC
  add constraint R114_525 foreign key (ANO_SEM,CO_ALUNO,CO_UNIDADE)
  references S_ALUNO_PER_UNID (ANO_SEM,CO_ALUNO,CO_UNIDADE) on delete cascade;
alter table S_ALUNO_DEPENDENC
  add constraint R114_526 foreign key (CO_DISCIPLINA,ANO_SEM,CO_UNIDADE)
  references S_DISCIPLINA (CO_DISCIPLINA,ANO_SEM,CO_UNIDADE) on delete cascade;
alter table S_ALUNO_DEPENDENC
  add constraint R_121 foreign key (SG_SERIE)
  references S_SERIE (SG_SERIE) on delete cascade;

prompt
prompt Creating table S_PERIODO_EJA
prompt ============================
prompt
create table S_PERIODO_EJA
(
  CO_UNIDADE CHAR(5) not null,
  SEM_EJA    CHAR(5) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_PERIODO_EJA
  add constraint PK_S_PERIODO_EJA primary key (CO_UNIDADE,SEM_EJA)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_ALUNO_EJA
prompt ==========================
prompt
create table S_ALUNO_EJA
(
  CO_ALUNO          CHAR(12) not null,
  CO_UNIDADE        CHAR(5) not null,
  SEM_EJA           CHAR(5) not null,
  DS_SITUACAO_ALUNO VARCHAR2(12),
  NU_PESO           VARCHAR2(4),
  NU_ALTURA         VARCHAR2(4),
  DT_MATRICULA      DATE,
  TP_APTO_ED_FISICA VARCHAR2(3),
  ST_ENS_RELIGIOSO  VARCHAR2(3),
  TP_BOLSA_ESCOLA   VARCHAR2(3),
  NU_BOLSA_ESCOLA   VARCHAR2(10)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_EJA
  add constraint PK_S_ALUNO_EJA primary key (CO_ALUNO,CO_UNIDADE,SEM_EJA)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_EJA
  add constraint FK_ALUEJA_ALU foreign key (CO_ALUNO)
  references S_ALUNO (CO_ALUNO) on delete cascade;
alter table S_ALUNO_EJA
  add constraint FK_SALUEJA_SPEREJA foreign key (CO_UNIDADE,SEM_EJA)
  references S_PERIODO_EJA (CO_UNIDADE,SEM_EJA) on delete cascade;
alter table S_ALUNO_EJA
  add constraint R200_244 foreign key (CO_UNIDADE)
  references S_UNIDADE (CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_DOCUMENTO
prompt ==========================
prompt
create table S_DOCUMENTO
(
  CO_DOCUMENTO  NUMBER(10) not null,
  DS_DOCUMENTO  VARCHAR2(170) not null,
  CO_TIPO_CURSO NUMBER(10) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_DOCUMENTO
  add constraint U194_145 primary key (CO_DOCUMENTO,CO_TIPO_CURSO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_DOCUMENTO
  add constraint R144_567 foreign key (CO_TIPO_CURSO)
  references S_TIPO_CURSO (CO_TIPO_CURSO) on delete cascade;

prompt
prompt Creating table S_ALUNO_DOCUM_EJA
prompt ================================
prompt
create table S_ALUNO_DOCUM_EJA
(
  CO_ALUNO      CHAR(12) not null,
  CO_UNIDADE    CHAR(5) not null,
  CO_DOCUMENTO  NUMBER(10) not null,
  CO_TIPO_CURSO NUMBER(10) not null,
  SEM_EJA       CHAR(5) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_DOCUM_EJA
  add constraint PK_S_ALUNO_DOCUM_E primary key (CO_ALUNO,CO_UNIDADE,CO_DOCUMENTO,CO_TIPO_CURSO,SEM_EJA)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_DOCUM_EJA
  add constraint FK_SALDOCEJA_SALEJ foreign key (CO_ALUNO,CO_UNIDADE,SEM_EJA)
  references S_ALUNO_EJA (CO_ALUNO,CO_UNIDADE,SEM_EJA) on delete cascade;
alter table S_ALUNO_DOCUM_EJA
  add constraint R199_242 foreign key (CO_DOCUMENTO,CO_TIPO_CURSO)
  references S_DOCUMENTO (CO_DOCUMENTO,CO_TIPO_CURSO) on delete cascade;

prompt
prompt Creating table S_CURSO_DOCUMENTO
prompt ================================
prompt
create table S_CURSO_DOCUMENTO
(
  CO_CURSO      NUMBER(10) not null,
  CO_UNIDADE    CHAR(5) not null,
  CO_TIPO_CURSO NUMBER(10) not null,
  ANO_SEM       CHAR(5) not null,
  CO_DOCUMENTO  NUMBER(10) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CURSO_DOCUMENTO
  add constraint U191_132 primary key (ANO_SEM,CO_UNIDADE,CO_CURSO,CO_DOCUMENTO,CO_TIPO_CURSO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CURSO_DOCUMENTO
  add constraint R137_558 foreign key (CO_DOCUMENTO,CO_TIPO_CURSO)
  references S_DOCUMENTO (CO_DOCUMENTO,CO_TIPO_CURSO) on delete cascade;
alter table S_CURSO_DOCUMENTO
  add constraint R137_559 foreign key (CO_CURSO,ANO_SEM,CO_UNIDADE)
  references S_CURSO (CO_CURSO,ANO_SEM,CO_UNIDADE) on delete cascade;
create index FOREIGN130 on S_CURSO_DOCUMENTO (CO_CURSO,CO_UNIDADE)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_ALUNO_DOCUMENTO
prompt ================================
prompt
create table S_ALUNO_DOCUMENTO
(
  CO_UNIDADE    CHAR(5) not null,
  ANO_SEM       CHAR(5) not null,
  CO_ALUNO      CHAR(12) not null,
  CO_CURSO      NUMBER(10) not null,
  CO_DOCUMENTO  NUMBER(10) not null,
  CO_TIPO_CURSO NUMBER(10) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_DOCUMENTO
  add constraint U190_125 primary key (ANO_SEM,CO_UNIDADE,CO_ALUNO,CO_CURSO,CO_DOCUMENTO,CO_TIPO_CURSO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_DOCUMENTO
  add constraint R115_528 foreign key (ANO_SEM,CO_ALUNO,CO_UNIDADE)
  references S_ALUNO_PER_UNID (ANO_SEM,CO_ALUNO,CO_UNIDADE) on delete cascade;
alter table S_ALUNO_DOCUMENTO
  add constraint R190_367 foreign key (ANO_SEM,CO_UNIDADE,CO_CURSO,CO_DOCUMENTO,CO_TIPO_CURSO)
  references S_CURSO_DOCUMENTO (ANO_SEM,CO_UNIDADE,CO_CURSO,CO_DOCUMENTO,CO_TIPO_CURSO) on delete cascade;
create index FOREIGN111 on S_ALUNO_DOCUMENTO (CO_UNIDADE,CO_CURSO,CO_DOCUMENTO)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_TIPO_OCORRENCIA
prompt ================================
prompt
create table S_TIPO_OCORRENCIA
(
  CO_TIPO_OCORRENCIA NUMBER(10) not null,
  DS_TIPO_OCORRENCIA CHAR(50),
  CO_UNIDADE         CHAR(5) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TIPO_OCORRENCIA
  add constraint U123_24 primary key (CO_TIPO_OCORRENCIA,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TIPO_OCORRENCIA
  add constraint R173_599 foreign key (CO_UNIDADE)
  references S_UNIDADE (CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_ALUNO_OCORRENCIA
prompt =================================
prompt
create table S_ALUNO_OCORRENCIA
(
  CO_UNIDADE         CHAR(5) not null,
  CO_OCORRENCIA      NUMBER(10) not null,
  DS_OCORRENCIA      BLOB,
  ANO_SEM            CHAR(5) not null,
  CO_ALUNO           CHAR(12) not null,
  HO_OCORRENCIA      CHAR(5),
  CO_TIPO_OCORRENCIA NUMBER(10),
  ST_RECADO          CHAR(30),
  ST_RECADO_DADO     CHAR(30),
  DS_USUARIO_RECADO  CHAR(20),
  DT_OCORRENCIA      DATE
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_OCORRENCIA
  add constraint U124_25 primary key (CO_UNIDADE,CO_OCORRENCIA,ANO_SEM,CO_ALUNO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_OCORRENCIA
  add constraint R117_530 foreign key (CO_TIPO_OCORRENCIA,CO_UNIDADE)
  references S_TIPO_OCORRENCIA (CO_TIPO_OCORRENCIA,CO_UNIDADE) on delete cascade;
alter table S_ALUNO_OCORRENCIA
  add constraint R117_531 foreign key (ANO_SEM,CO_ALUNO,CO_UNIDADE)
  references S_ALUNO_PER_UNID (ANO_SEM,CO_ALUNO,CO_UNIDADE) on delete cascade;
create index FOREIGN112 on S_ALUNO_OCORRENCIA (CO_TIPO_OCORRENCIA)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_ALUNO_TELEFONE
prompt ===============================
prompt
create table S_ALUNO_TELEFONE
(
  CO_ALUNO        CHAR(12) not null,
  NU_SEQ_TELEFONE NUMBER(10) not null,
  DS_TELEFONE     VARCHAR2(20),
  CO_UNIDADE      CHAR(5)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_TELEFONE
  add constraint PKS_ALUNO_TELEFONE primary key (NU_SEQ_TELEFONE,CO_ALUNO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_TELEFONE
  add constraint FK_ALUTEL_ALU foreign key (CO_ALUNO)
  references S_ALUNO (CO_ALUNO) on delete cascade;

prompt
prompt Creating table S_TURNO
prompt ======================
prompt
create table S_TURNO
(
  CO_TURNO CHAR(2) not null,
  DS_TURNO CHAR(30)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TURNO
  add constraint U130_35 primary key (CO_TURNO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_AMBIENTE
prompt =========================
prompt
create table S_AMBIENTE
(
  CO_SEQ_AMBIENTE NUMBER(10) not null,
  DS_AMBIENTE     VARCHAR2(30)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_AMBIENTE
  add constraint XPKS_AMBIENTE primary key (CO_SEQ_AMBIENTE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_TIPO_SALA
prompt ==========================
prompt
create table S_TIPO_SALA
(
  CO_TIPO_SALA NUMBER(10) not null,
  DS_TIPO_SALA CHAR(30)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TIPO_SALA
  add constraint U128_33 primary key (CO_TIPO_SALA)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_SALA
prompt =====================
prompt
create table S_SALA
(
  CO_BLOCO        CHAR(2) not null,
  CO_UNIDADE      CHAR(5) not null,
  CO_SALA         CHAR(3) not null,
  DS_SALA         CHAR(30),
  CO_SEQ_AMBIENTE NUMBER(10),
  NU_ALUNOS_SALA  NUMBER(10),
  NU_METRAGEM     NUMBER(10),
  CO_TIPO_SALA    NUMBER(10),
  CO_SEQ_SALA     NUMBER(10)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_SALA
  add constraint U129_34 primary key (CO_UNIDADE,CO_BLOCO,CO_SALA)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_SALA
  add constraint R_122 foreign key (CO_SEQ_AMBIENTE)
  references S_AMBIENTE (CO_SEQ_AMBIENTE) on delete cascade;
alter table S_SALA
  add constraint R168_595 foreign key (CO_UNIDADE)
  references S_UNIDADE (CO_UNIDADE) on delete cascade;
alter table S_SALA
  add constraint R168_596 foreign key (CO_TIPO_SALA)
  references S_TIPO_SALA (CO_TIPO_SALA) on delete cascade;
create index FOREIGN152 on S_SALA (CO_TIPO_SALA,CO_UNIDADE)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_TURMA
prompt ======================
prompt
create table S_TURMA
(
  CO_UNIDADE         CHAR(5) not null,
  CO_TURMA           NUMBER(10) not null,
  ANO_SEM            CHAR(5) not null,
  CO_CURSO           NUMBER(10) not null,
  CO_GRAU            CHAR(1),
  CO_TURNO           CHAR(2),
  CO_SEQ_SERIE       NUMBER(10) not null,
  CO_LETRA_TURMA     CHAR(3),
  CO_BLOCO           CHAR(2),
  DS_TURMA           CHAR(30),
  ST_TURMA_DEFINITIV CHAR(3),
  NU_MAXIMO_ALUNO    CHAR(3),
  CO_TIPO_HORARIO    NUMBER(10),
  CO_TURMA_PROCURA   CHAR(6),
  ST_LABORATORIO     CHAR(1),
  DISC_ORIGEM        CHAR(4),
  FO_TURMA           BLOB,
  CO_SALA            CHAR(3)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TURMA
  add constraint U131_36 primary key (CO_TURMA,CO_UNIDADE,ANO_SEM,CO_CURSO,CO_SEQ_SERIE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TURMA
  add constraint R_123 foreign key (ANO_SEM,CO_CURSO,CO_SEQ_SERIE,CO_UNIDADE)
  references S_CURSO_SERIE (ANO_SEM,CO_CURSO,CO_SEQ_SERIE,CO_UNIDADE) on delete cascade;
alter table S_TURMA
  add constraint R176_601 foreign key (CO_UNIDADE,CO_BLOCO,CO_SALA)
  references S_SALA (CO_UNIDADE,CO_BLOCO,CO_SALA) on delete cascade;
alter table S_TURMA
  add constraint R176_602 foreign key (CO_TURNO)
  references S_TURNO (CO_TURNO) on delete cascade;
create index FOREIGN156 on S_TURMA (CO_UNIDADE)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
create index FOREIGN157 on S_TURMA (CO_TURNO,CO_UNIDADE)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
create index FOREIGN206 on S_TURMA (CO_BLOCO,CO_SALA,CO_UNIDADE)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_ALUNO_TURMA
prompt ============================
prompt
create table S_ALUNO_TURMA
(
  CO_UNIDADE        CHAR(5) not null,
  CO_TURMA          NUMBER(10) not null,
  ANO_SEM           CHAR(5) not null,
  CO_ALUNO          CHAR(12) not null,
  CO_CURSO          NUMBER(10) not null,
  DT_MOVIMENTACAO   DATE,
  CO_SEQ_SERIE      NUMBER(10) not null,
  ST_MOVIMENTACAO   CHAR(25),
  DS_MOVIMENTACAO   CHAR(245),
  VL_DESCONTO       NUMBER(10),
  ST_DESC_APOS_VENC CHAR(3),
  NU_PRIMEIRA_PARC  CHAR(5),
  DT_VENCIMENTO_1   DATE,
  DT_VENCIMENTO_2   DATE,
  NU_DIA_VENCIMENTO CHAR(2),
  ST_PRINCIPAL      CHAR(1),
  CO_PLANO          NUMBER(10),
  CO_ALUNO_TURMA    NUMBER(10) not null,
  NU_CHAMADA        CHAR(3)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_TURMA
  add constraint PK_S_ALUNO_TURMA primary key (CO_UNIDADE,CO_TURMA,ANO_SEM,CO_ALUNO,CO_CURSO,CO_SEQ_SERIE,CO_ALUNO_TURMA)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_TURMA
  add constraint R119_533 foreign key (CO_TURMA,CO_UNIDADE,ANO_SEM,CO_CURSO,CO_SEQ_SERIE)
  references S_TURMA (CO_TURMA,CO_UNIDADE,ANO_SEM,CO_CURSO,CO_SEQ_SERIE) on delete cascade;
alter table S_ALUNO_TURMA
  add constraint R119_534 foreign key (ANO_SEM,CO_ALUNO,CO_UNIDADE)
  references S_ALUNO_PER_UNID (ANO_SEM,CO_ALUNO,CO_UNIDADE) on delete cascade;
create index FOREIGN116 on S_ALUNO_TURMA (CO_UNIDADE)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
create index FOREIGN117 on S_ALUNO_TURMA (CO_TURMA,CO_UNIDADE,ANO_SEM)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
create index FOREIGN172 on S_ALUNO_TURMA (CO_ALUNO,CO_UNIDADE,ANO_SEM)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
create index IDX_MOVIM on S_ALUNO_TURMA (ST_MOVIMENTACAO)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_TURMA_DISCIPLINA
prompt =================================
prompt
create table S_TURMA_DISCIPLINA
(
  CO_UNIDADE    CHAR(5) not null,
  ANO_SEM       CHAR(5) not null,
  CO_TURMA      NUMBER(10) not null,
  CO_DISCIPLINA CHAR(4) not null,
  CO_CURSO      NUMBER(10) not null,
  CO_SEQ_SERIE  NUMBER(10) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TURMA_DISCIPLINA
  add constraint U133_44 primary key (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_CURSO,CO_SEQ_SERIE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TURMA_DISCIPLINA
  add constraint R177_603 foreign key (CO_TURMA,CO_UNIDADE,ANO_SEM,CO_CURSO,CO_SEQ_SERIE)
  references S_TURMA (CO_TURMA,CO_UNIDADE,ANO_SEM,CO_CURSO,CO_SEQ_SERIE) on delete cascade;
alter table S_TURMA_DISCIPLINA
  add constraint R177_604 foreign key (CO_DISCIPLINA,ANO_SEM,CO_UNIDADE)
  references S_DISCIPLINA (CO_DISCIPLINA,ANO_SEM,CO_UNIDADE) on delete cascade;
create index FOREIGN158 on S_TURMA_DISCIPLINA (CO_TURMA,CO_UNIDADE,ANO_SEM)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
create index FOREIGN159 on S_TURMA_DISCIPLINA (CO_DISCIPLINA,CO_UNIDADE,ANO_SEM)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_ALUNO_TURMA_DISC
prompt =================================
prompt
create table S_ALUNO_TURMA_DISC
(
  CO_UNIDADE    CHAR(5) not null,
  ANO_SEM       CHAR(5) not null,
  CO_TURMA      NUMBER(10) not null,
  CO_DISCIPLINA CHAR(4) not null,
  CO_ALUNO      CHAR(12) not null,
  CO_CURSO      NUMBER(10) not null,
  CO_SEQ_SERIE  NUMBER(10) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_TURMA_DISC
  add constraint U134_45 primary key (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_ALUNO,CO_CURSO,CO_SEQ_SERIE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_TURMA_DISC
  add constraint R120_535 foreign key (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_CURSO,CO_SEQ_SERIE)
  references S_TURMA_DISCIPLINA (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_CURSO,CO_SEQ_SERIE) on delete cascade;
alter table S_ALUNO_TURMA_DISC
  add constraint R120_536 foreign key (ANO_SEM,CO_ALUNO,CO_UNIDADE)
  references S_ALUNO_PER_UNID (ANO_SEM,CO_ALUNO,CO_UNIDADE) on delete cascade;
create index FOREIGN118 on S_ALUNO_TURMA_DISC (CO_TURMA,CO_DISCIPLINA,CO_UNIDADE,ANO_SEM)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
create index FOREIGN119 on S_ALUNO_TURMA_DISC (CO_ALUNO,CO_UNIDADE,ANO_SEM)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_TURMA_EJA
prompt ==========================
prompt
create table S_TURMA_EJA
(
  CO_TURMA           NUMBER(10) not null,
  CO_UNIDADE         CHAR(5) not null,
  SEM_EJA            CHAR(5) not null,
  CO_TIPO_DISCIPLINA NUMBER(10) not null,
  CO_TURNO           CHAR(2) not null,
  CO_SALA            CHAR(3) not null,
  DS_TURMA           VARCHAR2(40),
  CO_BLOCO           CHAR(2) not null,
  CO_LETRA_TURMA     CHAR(3) not null,
  CO_SEGMENTO        CHAR(1) not null,
  CO_SEMESTRE        CHAR(1) not null,
  DS_AULA_1          CHAR(10),
  DS_AULA_2          CHAR(10),
  DS_HORARIO_1       CHAR(5),
  DS_HORARIO_2       CHAR(5),
  DS_PROJETO         VARCHAR2(50),
  NU_CHAMADA         CHAR(3),
  CO_FUNCIONARIO_TMP VARCHAR2(10),
  CO_FUNCIONARIO     VARCHAR2(10),
  NU_MAX_ALUNO       NUMBER(10),
  NU_GRUPO           NUMBER(10),
  CO_GRADE           CHAR(5),
  NU_CARGA_HORARIA   CHAR(4)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TURMA_EJA
  add constraint PK_S_TURMA_EJA primary key (CO_TURMA,CO_UNIDADE,SEM_EJA)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TURMA_EJA
  add constraint FK_STUREJA_SPEREJA foreign key (CO_UNIDADE,SEM_EJA)
  references S_PERIODO_EJA (CO_UNIDADE,SEM_EJA) on delete cascade;
alter table S_TURMA_EJA
  add constraint R203_247 foreign key (CO_TURNO)
  references S_TURNO (CO_TURNO) on delete cascade;
alter table S_TURMA_EJA
  add constraint R203_248 foreign key (CO_UNIDADE,CO_BLOCO,CO_SALA)
  references S_SALA (CO_UNIDADE,CO_BLOCO,CO_SALA) on delete cascade;
alter table S_TURMA_EJA
  add constraint R203_249 foreign key (CO_UNIDADE)
  references S_UNIDADE (CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_GRUPO_EJA
prompt ==========================
prompt
create table S_GRUPO_EJA
(
  CO_GRUPO   CHAR(1) not null,
  CO_TURMA   NUMBER(10) not null,
  CO_UNIDADE CHAR(5) not null,
  SEM_EJA    CHAR(5) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_GRUPO_EJA
  add constraint PK_S_GRUPO_EJA primary key (CO_GRUPO,CO_TURMA,CO_UNIDADE,SEM_EJA)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_GRUPO_EJA
  add constraint FK_SGREJA_STUREJA foreign key (CO_TURMA,CO_UNIDADE,SEM_EJA)
  references S_TURMA_EJA (CO_TURMA,CO_UNIDADE,SEM_EJA) on delete cascade;

prompt
prompt Creating table S_ALUNO_TURMA_EJA
prompt ================================
prompt
create table S_ALUNO_TURMA_EJA
(
  CO_GRUPO     CHAR(1) not null,
  CO_TURMA     NUMBER(10) not null,
  CO_UNIDADE   CHAR(5) not null,
  CO_ALUNO     CHAR(12) not null,
  SEM_EJA      CHAR(5) not null,
  DT_INICIO    DATE,
  DT_FIM       DATE,
  DS_SITUACAO  CHAR(15) not null,
  NU_FALTAS    NUMBER(10),
  TP_CONCLUIDO CHAR(1),
  DS_PROJETO   VARCHAR2(60),
  NU_CHAMADA   VARCHAR2(3)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_TURMA_EJA
  add constraint PK_S_ALUNO_TURMA_E primary key (CO_UNIDADE,CO_GRUPO,CO_TURMA,CO_ALUNO,SEM_EJA)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_ALUNO_TURMA_EJA
  add constraint FK_SALUTUREJA_SALE foreign key (CO_ALUNO,CO_UNIDADE,SEM_EJA)
  references S_ALUNO_EJA (CO_ALUNO,CO_UNIDADE,SEM_EJA) on delete cascade;
alter table S_ALUNO_TURMA_EJA
  add constraint FK_SALUTUREJA_SGRE foreign key (CO_GRUPO,CO_TURMA,CO_UNIDADE,SEM_EJA)
  references S_GRUPO_EJA (CO_GRUPO,CO_TURMA,CO_UNIDADE,SEM_EJA) on delete cascade;

prompt
prompt Creating table S_AREA_ATUACAO
prompt =============================
prompt
create table S_AREA_ATUACAO
(
  CO_AREA_ATUACAO NUMBER(10) not null,
  DS_AREA_ATUACAO VARCHAR2(52)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_AREA_ATUACAO
  add constraint U135_46 primary key (CO_AREA_ATUACAO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_AULA_DADA
prompt ==========================
prompt
create table S_AULA_DADA
(
  CO_TURMA           NUMBER(10) not null,
  CO_CURS_SERIE_DISC NUMBER(10) not null,
  NU_AULAS_DADAS_B1  CHAR(4),
  CO_UNIDADE         CHAR(5) not null,
  NU_AULAS_PREV_B1   CHAR(4),
  ANO_SEM            CHAR(5) not null,
  CO_CURSO           NUMBER(10) not null,
  CO_SEQ_SERIE       NUMBER(10) not null,
  NU_AULAS_DADAS_B2  CHAR(4),
  NU_AULAS_DADAS_B3  CHAR(4),
  NU_AULAS_PREV_B3   CHAR(4),
  NU_AULAS_DADAS_B4  CHAR(4),
  NU_AULAS_PREV_B4   CHAR(4),
  CO_DISCIPLINA      CHAR(4) not null,
  NU_AULAS_PREV_B2   CHAR(4)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_AULA_DADA
  add constraint U136_47 primary key (CO_TURMA,CO_CURS_SERIE_DISC,CO_DISCIPLINA,CO_UNIDADE,ANO_SEM,CO_CURSO,CO_SEQ_SERIE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_AULA_DADA
  add constraint R123_539 foreign key (CO_TURMA,CO_UNIDADE,ANO_SEM,CO_CURSO,CO_SEQ_SERIE)
  references S_TURMA (CO_TURMA,CO_UNIDADE,ANO_SEM,CO_CURSO,CO_SEQ_SERIE) on delete cascade;
create index FOREIGN121 on S_AULA_DADA (CO_TURMA,CO_UNIDADE)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_FUNCIONARIO
prompt ============================
prompt
create table S_FUNCIONARIO
(
  CO_FUNCIONARIO   CHAR(10) not null,
  NU_MATRICULA_MEC CHAR(8),
  CO_SEQ_CIDADE    NUMBER(10),
  DS_FUNCIONARIO   CHAR(40),
  DS_APELIDO       CHAR(20),
  FO_FUNCIONARIO   BLOB,
  TP_SEXO          CHAR(1),
  DS_NATURALIDADE  CHAR(30),
  DS_UF_NASCIMENTO CHAR(2),
  DT_NASCIMENTO    DATE,
  DS_ENDERECO      CHAR(40),
  NU_CEP           CHAR(9),
  DS_CIDADE        CHAR(20),
  DS_UF_CIDADE     CHAR(2),
  DS_BAIRRO        CHAR(20),
  NU_TELEFONE      CHAR(14),
  NU_CELULAR       CHAR(14),
  DS_E_MAIL        CHAR(100),
  TP_ESTADO_CIVIL  VARCHAR2(12),
  DS_CONJUGE       CHAR(50),
  NU_RG            CHAR(15),
  DS_ORGAO_EMISSOR CHAR(30),
  DT_EMISSAO       DATE,
  NU_CPF           CHAR(14),
  DS_PAI           CHAR(40),
  DS_MAE           CHAR(40),
  NU_REGISTRO      CHAR(15),
  DS_INSTRUCAO     CHAR(40),
  CO_UNIDADE       CHAR(5),
  LOTACAO_PRINC    VARCHAR2(15),
  LOTACAO_SECUN    VARCHAR2(15)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_FUNCIONARIO
  add constraint U137_39 primary key (CO_FUNCIONARIO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_FUNCIONARIO
  add constraint R146_568 foreign key (CO_SEQ_CIDADE)
  references S_CIDADE (CO_SEQ_CIDADE) on delete cascade;
create index FUNC_NOME on S_FUNCIONARIO (DS_FUNCIONARIO)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_AVALIACAO
prompt ==========================
prompt
create table S_AVALIACAO
(
  AV_SEQUENCIAL      NUMBER(10) not null,
  CO_UNIDADE         CHAR(5) not null,
  ANO_SEM            CHAR(5),
  CO_CURSO           NUMBER(10),
  CO_TURMA           NUMBER(10),
  CO_SEQ_SERIE       NUMBER(10),
  CO_DISCIPLINA      CHAR(4),
  CO_CURS_SERIE_DISC NUMBER(10),
  CO_FUNCIONARIO     CHAR(10)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_AVALIACAO
  add constraint PKS_AVALIACAO primary key (AV_SEQUENCIAL,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_AVALIACAO
  add constraint FK_ATD_AVALIACAO foreign key (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_CURSO,CO_SEQ_SERIE)
  references S_TURMA_DISCIPLINA (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_CURSO,CO_SEQ_SERIE) on delete cascade;
alter table S_AVALIACAO
  add constraint FK_F_AVALIACAO foreign key (CO_FUNCIONARIO)
  references S_FUNCIONARIO (CO_FUNCIONARIO) on delete cascade;
create index FOREIGN199 on S_AVALIACAO (CO_TURMA,CO_DISCIPLINA,CO_UNIDADE,ANO_SEM)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_TIPO_AVALIACAO
prompt ===============================
prompt
create table S_TIPO_AVALIACAO
(
  CO_TIPO_AVALIACAO NUMBER(10) not null,
  DS_TIPO_AVALIACAO VARCHAR2(20)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TIPO_AVALIACAO
  add constraint U138_49 primary key (CO_TIPO_AVALIACAO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_AVALIACAO_TURMA
prompt ================================
prompt
create table S_AVALIACAO_TURMA
(
  AV_SEQUENCIAL     NUMBER(10) not null,
  CO_UNIDADE        CHAR(5) not null,
  DT_AVALIACAO      DATE not null,
  CO_AVALIACAO      NUMBER(10) not null,
  OBS_AVALIACAO     BLOB,
  CO_TIPO_AVALIACAO NUMBER(10) not null,
  AVT_MAX_PONTOS    CHAR(6),
  DS_HABILIDADE     BLOB,
  AVT_BATERIA       NUMBER(10)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_AVALIACAO_TURMA
  add constraint PKS_AVALIACAO_TURM primary key (CO_UNIDADE,AV_SEQUENCIAL,DT_AVALIACAO,CO_AVALIACAO,CO_TIPO_AVALIACAO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_AVALIACAO_TURMA
  add constraint FK_A_AVALIACAO_TUR foreign key (AV_SEQUENCIAL,CO_UNIDADE)
  references S_AVALIACAO (AV_SEQUENCIAL,CO_UNIDADE) on delete cascade;
alter table S_AVALIACAO_TURMA
  add constraint R126_543 foreign key (CO_TIPO_AVALIACAO)
  references S_TIPO_AVALIACAO (CO_TIPO_AVALIACAO) on delete cascade;
create index FOREIGN201 on S_AVALIACAO_TURMA (AV_SEQUENCIAL)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_AVALIACAO_NOTAS
prompt ================================
prompt
create table S_AVALIACAO_NOTAS
(
  AV_SEQUENCIAL     NUMBER(10) not null,
  CO_UNIDADE        CHAR(5) not null,
  DT_AVALIACAO      DATE not null,
  CO_AVALIACAO      NUMBER(10) not null,
  CO_ALUNO          CHAR(12) not null,
  AVT_NOTA          VARCHAR2(6),
  CO_TIPO_AVALIACAO NUMBER(10) not null,
  DS_INFORMACAO     BLOB
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_AVALIACAO_NOTAS
  add constraint U140_51 primary key (CO_UNIDADE,AV_SEQUENCIAL,DT_AVALIACAO,CO_AVALIACAO,CO_ALUNO,CO_TIPO_AVALIACAO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_AVALIACAO_NOTAS
  add constraint FK_AT_AVALIACAO_NO foreign key (CO_UNIDADE,AV_SEQUENCIAL,DT_AVALIACAO,CO_AVALIACAO,CO_TIPO_AVALIACAO)
  references S_AVALIACAO_TURMA (CO_UNIDADE,AV_SEQUENCIAL,DT_AVALIACAO,CO_AVALIACAO,CO_TIPO_AVALIACAO) on delete cascade;
create index FOREIGN204 on S_AVALIACAO_NOTAS (AV_SEQUENCIAL,DT_AVALIACAO,CO_AVALIACAO)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_CALEND_TITULO
prompt ==============================
prompt
create table S_CALEND_TITULO
(
  CO_CALENDARIO NUMBER(10) not null,
  CO_UNIDADE    CHAR(5) not null,
  DS_CALENDARIO VARCHAR2(60) not null,
  DS_NUMERO     VARCHAR2(10),
  ST_OFICIAL    CHAR(1) not null,
  ANO           CHAR(4)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CALEND_TITULO
  add constraint PK_S_CALEND_TIT primary key (CO_CALENDARIO,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CALEND_TITULO
  add constraint FK_CALTIT_UNID foreign key (CO_UNIDADE)
  references S_UNIDADE (CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_DIA_CALENDARIO
prompt ===============================
prompt
create table S_DIA_CALENDARIO
(
  CO_DIA_CALENDARIO NUMBER(10) not null,
  CO_UNIDADE        CHAR(5) not null,
  DS_DIA_CALENDARIO CHAR(30),
  DS_COR_CALENDARIO CHAR(30),
  NU_IMAGEM         NUMBER(10),
  ST_LETIVO         CHAR(1),
  ST_FERIADO        CHAR(1)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_DIA_CALENDARIO
  add constraint PK_S_DIA_CALEND primary key (CO_DIA_CALENDARIO,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_DIA_CALENDARIO
  add constraint FK_DIACAL_UNID foreign key (CO_UNIDADE)
  references S_UNIDADE (CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_CALENDARIO
prompt ===========================
prompt
create table S_CALENDARIO
(
  CO_UNIDADE        CHAR(5) not null,
  DT_CALENDARIO     DATE not null,
  CO_CALENDARIO     NUMBER(10) not null,
  CO_DIA_CALENDARIO NUMBER(10) not null,
  ST_ALTERA         CHAR(1)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CALENDARIO
  add constraint PK_S_CALENDARIO primary key (CO_UNIDADE,DT_CALENDARIO,CO_CALENDARIO,CO_DIA_CALENDARIO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CALENDARIO
  add constraint FK_CALEND_CALTIT foreign key (CO_CALENDARIO,CO_UNIDADE)
  references S_CALEND_TITULO (CO_CALENDARIO,CO_UNIDADE) on delete cascade;
alter table S_CALENDARIO
  add constraint FK_CALEND_DIACAL foreign key (CO_DIA_CALENDARIO,CO_UNIDADE)
  references S_DIA_CALENDARIO (CO_DIA_CALENDARIO,CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_CARGO
prompt ======================
prompt
create table S_CARGO
(
  CO_CARGO VARCHAR2(17) not null,
  DS_CARGO CHAR(30)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CARGO
  add constraint U141_52 primary key (CO_CARGO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_CARTA
prompt ======================
prompt
create table S_CARTA
(
  CO_CARTA      NUMBER(10) not null,
  DS_NOME_CARTA CHAR(30),
  CO_UNIDADE    CHAR(5) not null,
  TC_CODIGO     NUMBER(10),
  MODELO        BLOB,
  ORIGEM        BLOB
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CARTA
  add constraint U142_53 primary key (CO_CARTA,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CARTA
  add constraint CARTAS_TP_CONSULTA foreign key (TC_CODIGO)
  references TIPO_CONSULTA (TC_CODIGO) on delete cascade;
alter table S_CARTA
  add constraint R129_547 foreign key (CO_UNIDADE)
  references S_UNIDADE (CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_CEP
prompt ====================
prompt
create table S_CEP
(
  BAIRRO   VARCHAR2(40) not null,
  ENDERECO VARCHAR2(60) not null,
  CEP      VARCHAR2(8) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CEP
  add constraint U198_161 primary key (CEP)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
create index IX_ENDERECO on S_CEP (ENDERECO)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_FUNCIONARIO_DISC
prompt =================================
prompt
create table S_FUNCIONARIO_DISC
(
  CO_UNIDADE     CHAR(5) not null,
  ST_HABILITADO  CHAR(3),
  CO_FUNCIONARIO CHAR(10) not null,
  ANO_SEM        CHAR(5) not null,
  CO_DISCIPLINA  CHAR(4) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_FUNCIONARIO_DISC
  add constraint U143_54 primary key (CO_FUNCIONARIO,CO_DISCIPLINA,ANO_SEM,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_FUNCIONARIO_DISC
  add constraint FK_F_DISCIPLINA foreign key (CO_DISCIPLINA,ANO_SEM,CO_UNIDADE)
  references S_DISCIPLINA (CO_DISCIPLINA,ANO_SEM,CO_UNIDADE) on delete cascade;
alter table S_FUNCIONARIO_DISC
  add constraint R147_570 foreign key (CO_FUNCIONARIO)
  references S_FUNCIONARIO (CO_FUNCIONARIO) on delete cascade;

prompt
prompt Creating table S_CHAMADA
prompt ========================
prompt
create table S_CHAMADA
(
  CO_SEQ_CHAMADA NUMBER(10) not null,
  CO_UNIDADE     CHAR(5) not null,
  ANO_SEM        CHAR(5),
  CO_FUNCIONARIO CHAR(10),
  CO_CURSO       NUMBER(10),
  CO_DISCIPLINA  CHAR(4),
  CO_SEQ_SERIE   NUMBER(10),
  CO_TURMA       NUMBER(10)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CHAMADA
  add constraint PKS_CHAMADA primary key (CO_SEQ_CHAMADA,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CHAMADA
  add constraint R130_548 foreign key (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_CURSO,CO_SEQ_SERIE)
  references S_TURMA_DISCIPLINA (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_CURSO,CO_SEQ_SERIE) on delete cascade;
alter table S_CHAMADA
  add constraint R130_549 foreign key (CO_FUNCIONARIO,CO_DISCIPLINA,ANO_SEM,CO_UNIDADE)
  references S_FUNCIONARIO_DISC (CO_FUNCIONARIO,CO_DISCIPLINA,ANO_SEM,CO_UNIDADE) on delete cascade;
create index XIF158S_CHAMADA on S_CHAMADA (CO_TURMA,CO_DISCIPLINA,CO_UNIDADE,ANO_SEM)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_TIPO_NOTA
prompt ==========================
prompt
create table S_TIPO_NOTA
(
  CO_CONTROLE        NUMBER(10) not null,
  CO_CURSO           NUMBER(10) not null,
  CO_UNIDADE         CHAR(5) not null,
  DS_CONTROLE        CHAR(30),
  ANO_SEM            CHAR(5) not null,
  ABV_MOSTRA         CHAR(15),
  ABV_FORMULA        CHAR(5),
  ABV_FORMULA_MOSTRA CHAR(5)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TIPO_NOTA
  add constraint U145_56 primary key (CO_CONTROLE,CO_CURSO,ANO_SEM,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TIPO_NOTA
  add constraint R172_598 foreign key (CO_CURSO,ANO_SEM,CO_UNIDADE)
  references S_CURSO (CO_CURSO,ANO_SEM,CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_CHAMADA_TURMA
prompt ==============================
prompt
create table S_CHAMADA_TURMA
(
  CO_UNIDADE       CHAR(5) not null,
  CO_SEQ_CHAMADA   NUMBER(10) not null,
  CO_CHAMADA_TURMA NUMBER(10) not null,
  DATA_CHAMADA     DATE,
  CO_CONTROLE      NUMBER(10),
  CO_CURSO         NUMBER(10),
  ANO_SEM          CHAR(5),
  AULA             CHAR(2)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CHAMADA_TURMA
  add constraint PKS_CHAMADA_TURMA primary key (CO_UNIDADE,CO_CHAMADA_TURMA,CO_SEQ_CHAMADA)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CHAMADA_TURMA
  add constraint R_124 foreign key (CO_SEQ_CHAMADA,CO_UNIDADE)
  references S_CHAMADA (CO_SEQ_CHAMADA,CO_UNIDADE) on delete cascade;
alter table S_CHAMADA_TURMA
  add constraint R132_536 foreign key (CO_CONTROLE,CO_CURSO,ANO_SEM,CO_UNIDADE)
  references S_TIPO_NOTA (CO_CONTROLE,CO_CURSO,ANO_SEM,CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_CHAMADA_FALTA
prompt ==============================
prompt
create table S_CHAMADA_FALTA
(
  CO_UNIDADE       CHAR(5) not null,
  CO_CHAMADA_TURMA NUMBER(10) not null,
  ANO_SEM          CHAR(5),
  CO_SEQ_CHAMADA   NUMBER(10) not null,
  CO_TURMA         NUMBER(10),
  CO_DISCIPLINA    CHAR(4),
  CO_CURSO         NUMBER(10),
  CO_SEQ_SERIE     NUMBER(10),
  CO_ALUNO         CHAR(12) not null,
  CHAMADA          CHAR(1),
  JUSTIFICATIVA    VARCHAR2(20)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CHAMADA_FALTA
  add constraint PKS_CHAMADA_FALTA primary key (CO_UNIDADE,CO_ALUNO,CO_CHAMADA_TURMA,CO_SEQ_CHAMADA)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CHAMADA_FALTA
  add constraint R_125 foreign key (CO_UNIDADE,CO_CHAMADA_TURMA,CO_SEQ_CHAMADA)
  references S_CHAMADA_TURMA (CO_UNIDADE,CO_CHAMADA_TURMA,CO_SEQ_CHAMADA) on delete cascade;
alter table S_CHAMADA_FALTA
  add constraint R131_551 foreign key (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_ALUNO,CO_CURSO,CO_SEQ_SERIE)
  references S_ALUNO_TURMA_DISC (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_ALUNO,CO_CURSO,CO_SEQ_SERIE) on delete cascade;

prompt
prompt Creating table S_COMUNICACAO
prompt ============================
prompt
create table S_COMUNICACAO
(
  DS_ARQUIVO     VARCHAR2(50) not null,
  NU_REGIONAL    VARCHAR2(2),
  DS_USUARIO     VARCHAR2(40),
  DT_RECEBIMENTO DATE,
  DT_PROCESS     DATE,
  CO_ESCOLA      CHAR(5) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_CONCEITO
prompt =========================
prompt
create table S_CONCEITO
(
  CO_CONCEITO     CHAR(5) not null,
  DS_CONCEITO     CHAR(30),
  CO_CURSO        NUMBER(10) not null,
  ANO_SEM         CHAR(5) not null,
  NU_NOTA_CORRESP FLOAT,
  CO_UNIDADE      CHAR(5) not null,
  NU_NOTA_INICIO  FLOAT,
  NU_NOTA_FIM     FLOAT
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CONCEITO
  add constraint U122_23 primary key (CO_CONCEITO,CO_CURSO,ANO_SEM,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CONCEITO
  add constraint R133_553 foreign key (CO_CURSO,ANO_SEM,CO_UNIDADE)
  references S_CURSO (CO_CURSO,ANO_SEM,CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_TIPO_CONT_EDUC
prompt ===============================
prompt
create table S_TIPO_CONT_EDUC
(
  TP_CONTEUDO_EDUC  NUMBER(10) not null,
  ANO_SEM           CHAR(5) not null,
  CO_CURSO          NUMBER(10) not null,
  CO_SEQ_SERIE      NUMBER(10) not null,
  CO_UNIDADE        CHAR(5) not null,
  DS_CONTEUDO_EDUC  VARCHAR2(70),
  CO_ORDEM_CONTEUDO NUMBER(10)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TIPO_CONT_EDUC
  add constraint XPKS_TIPO_CONT_EDU primary key (TP_CONTEUDO_EDUC,ANO_SEM,CO_CURSO,CO_SEQ_SERIE,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TIPO_CONT_EDUC
  add constraint R_135 foreign key (ANO_SEM,CO_CURSO,CO_SEQ_SERIE,CO_UNIDADE)
  references S_CURSO_SERIE (ANO_SEM,CO_CURSO,CO_SEQ_SERIE,CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_CONT_EDUC_OBS_AL
prompt =================================
prompt
create table S_CONT_EDUC_OBS_AL
(
  CO_UNIDADE         CHAR(5) not null,
  ANO_SEM            CHAR(5) not null,
  CO_TURMA           NUMBER(10) not null,
  CO_DISCIPLINA      CHAR(4) not null,
  CO_ALUNO           CHAR(12) not null,
  CO_CURSO           NUMBER(10) not null,
  CO_SEQ_SERIE       NUMBER(10) not null,
  TP_CONTEUDO_EDUC   NUMBER(10) not null,
  DS_CONT_EDU_OBS_AL VARCHAR2(200),
  DT_CONT_EDUCATIVO  DATE
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CONT_EDUC_OBS_AL
  add constraint XPKS_CONT_EDUC_OBS primary key (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_ALUNO,CO_CURSO,CO_SEQ_SERIE,TP_CONTEUDO_EDUC)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CONT_EDUC_OBS_AL
  add constraint R_140 foreign key (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_ALUNO,CO_CURSO,CO_SEQ_SERIE)
  references S_ALUNO_TURMA_DISC (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_ALUNO,CO_CURSO,CO_SEQ_SERIE) on delete cascade;
alter table S_CONT_EDUC_OBS_AL
  add constraint R_141 foreign key (TP_CONTEUDO_EDUC,ANO_SEM,CO_CURSO,CO_SEQ_SERIE,CO_UNIDADE)
  references S_TIPO_CONT_EDUC (TP_CONTEUDO_EDUC,ANO_SEM,CO_CURSO,CO_SEQ_SERIE,CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_CURSO_SERIE_DISC
prompt =================================
prompt
create table S_CURSO_SERIE_DISC
(
  CO_CURS_SERIE_DISC NUMBER(10) not null,
  CO_UNIDADE         CHAR(5) not null,
  CO_DISCIPLINA      CHAR(4) not null,
  ANO_SEM            CHAR(5) not null,
  CO_CURSO           NUMBER(10),
  NU_AULAS_SEMANAL   NUMBER(10),
  CO_SEQ_SERIE       NUMBER(10),
  NU_MINUTO_AULA     NUMBER(10),
  NU_CARGA_HOR_ANUAL NUMBER(10),
  TP_AVALIACAO       CHAR(20),
  TP_DIGITACAO       CHAR(8),
  TP_IMPRESSAO       CHAR(8),
  ST_REPROVA         CHAR(3),
  TP_DISCIPLINA      CHAR(30)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CURSO_SERIE_DISC
  add constraint U147_58 primary key (CO_CURS_SERIE_DISC,CO_DISCIPLINA,CO_UNIDADE,ANO_SEM)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CURSO_SERIE_DISC
  add constraint R_126 foreign key (ANO_SEM,CO_CURSO,CO_SEQ_SERIE,CO_UNIDADE)
  references S_CURSO_SERIE (ANO_SEM,CO_CURSO,CO_SEQ_SERIE,CO_UNIDADE) on delete cascade;
alter table S_CURSO_SERIE_DISC
  add constraint R140_563 foreign key (CO_DISCIPLINA,ANO_SEM,CO_UNIDADE)
  references S_DISCIPLINA (CO_DISCIPLINA,ANO_SEM,CO_UNIDADE) on delete cascade;
create index FOREIGN178 on S_CURSO_SERIE_DISC (CO_UNIDADE)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_CONTEUDO_PROGRAM
prompt =================================
prompt
create table S_CONTEUDO_PROGRAM
(
  CO_CONTEUDO        NUMBER(10) not null,
  CO_CURS_SERIE_DISC NUMBER(10) not null,
  CO_UNIDADE         CHAR(5) not null,
  ANO_SEM            CHAR(5) not null,
  CO_DISCIPLINA      CHAR(4) not null,
  NU_FRENTE          NUMBER(10),
  NU_AULA            NUMBER(10),
  DS_CONTEUDO        CHAR(80)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CONTEUDO_PROGRAM
  add constraint U148_59 primary key (CO_CONTEUDO,CO_UNIDADE,CO_CURS_SERIE_DISC,ANO_SEM,CO_DISCIPLINA)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CONTEUDO_PROGRAM
  add constraint FK_CONT_PROGRAM foreign key (CO_CURS_SERIE_DISC,CO_DISCIPLINA,CO_UNIDADE,ANO_SEM)
  references S_CURSO_SERIE_DISC (CO_CURS_SERIE_DISC,CO_DISCIPLINA,CO_UNIDADE,ANO_SEM) on delete cascade;

prompt
prompt Creating table S_CURSO_ARRED_NOTA
prompt =================================
prompt
create table S_CURSO_ARRED_NOTA
(
  CO_CURSO           NUMBER(10) not null,
  ANO_SEM            CHAR(5) not null,
  CO_ARREDONDA       NUMBER(10) not null,
  NU_NOTA_INICIO     CHAR(5),
  CO_UNIDADE         CHAR(5) not null,
  NU_NOTA_FIM        CHAR(5),
  NU_NOTA_ARREDONDAD CHAR(5)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CURSO_ARRED_NOTA
  add constraint U149_60 primary key (ANO_SEM,CO_ARREDONDA,CO_CURSO,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CURSO_ARRED_NOTA
  add constraint R136_557 foreign key (CO_CURSO,ANO_SEM,CO_UNIDADE)
  references S_CURSO (CO_CURSO,ANO_SEM,CO_UNIDADE) on delete cascade;
create index FOREIGN128 on S_CURSO_ARRED_NOTA (CO_CURSO)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_CURSO_HORA_AULA
prompt ================================
prompt
create table S_CURSO_HORA_AULA
(
  ANO_SEM      CHAR(5) not null,
  CO_TURNO     CHAR(2) not null,
  CO_UNIDADE   CHAR(5) not null,
  CO_CURSO     NUMBER(10) not null,
  NU_HORA_AULA NUMBER(10)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CURSO_HORA_AULA
  add constraint U150_61 primary key (ANO_SEM,CO_CURSO,CO_TURNO,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_CURSO_HORA_AULA
  add constraint FK_S_CURSO foreign key (CO_CURSO,ANO_SEM,CO_UNIDADE)
  references S_CURSO (CO_CURSO,ANO_SEM,CO_UNIDADE) on delete cascade;
create index FOREIGN241 on S_CURSO_HORA_AULA (CO_CURSO)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_TIPO_DISCIPLINA
prompt ================================
prompt
create table S_TIPO_DISCIPLINA
(
  CO_TIPO_DISCIPLINA NUMBER(10) not null,
  DS_TIPO_DISCIPLINA VARCHAR2(60),
  SG_DISCIPLINA      CHAR(4)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TIPO_DISCIPLINA
  add constraint PKS_TIPO_DISC primary key (CO_TIPO_DISCIPLINA)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_DISCIPLINA_PER
prompt ===============================
prompt
create table S_DISCIPLINA_PER
(
  SG_SERIE           VARCHAR2(5) not null,
  CO_TIPO_DISCIPLINA NUMBER(10) not null,
  CO_GRADE_CURRIC    NUMBER(10) not null,
  CO_TIPO_CURSO      NUMBER(10) not null,
  ANO                NUMBER(10) not null,
  TURNO              CHAR(2) not null,
  CARGA_HORARIA_SEM  VARCHAR2(18),
  TP_DISCIPLINA      CHAR(30),
  CO_DISCIPLINA      CHAR(4),
  DS_DISCIPLINA      CHAR(60),
  NU_ORDEM_IMP       NUMBER(10),
  TP_AVALIACAO       VARCHAR2(8),
  TP_DIGITACAO       VARCHAR2(8),
  TP_IMPRESSAO       VARCHAR2(8),
  ST_REPROVA         VARCHAR2(3),
  CO_HELPID          NUMBER(10)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_DISCIPLINA_PER
  add constraint PK_DISCIPLINA_PER primary key (TURNO,ANO,CO_GRADE_CURRIC,CO_TIPO_CURSO,SG_SERIE,CO_TIPO_DISCIPLINA)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_DISCIPLINA_PER
  add constraint R_148 foreign key (CO_TIPO_DISCIPLINA)
  references S_TIPO_DISCIPLINA (CO_TIPO_DISCIPLINA) on delete cascade;
create index XIF120S_DISC_PER on S_DISCIPLINA_PER (CO_GRADE_CURRIC)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_DUPLICIDADE
prompt ============================
prompt
create table S_DUPLICIDADE
(
  CO_DUPLICIDADE NUMBER(10) not null,
  CO_UNIDADE     VARCHAR2(5) not null,
  ANO_SEM        VARCHAR2(5) not null,
  DS_ARQUIVO     VARCHAR2(60) not null,
  TP_STATUS      CHAR(1)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_DUPLICIDADE
  add constraint U241_427 primary key (CO_DUPLICIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_DUPLICIDADE_ITEM
prompt =================================
prompt
create table S_DUPLICIDADE_ITEM
(
  CO_DUPLICIDADE NUMBER(10) not null,
  CO_ALUNO       CHAR(12) not null,
  DS_ACAO        VARCHAR2(40) not null,
  CO_ALUNO_MUDA  VARCHAR2(12)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_DUPLICIDADE_ITEM
  add constraint U242_432 primary key (CO_DUPLICIDADE,CO_ALUNO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_DUPLICIDADE_ITEM
  add constraint R242_433 foreign key (CO_DUPLICIDADE)
  references S_DUPLICIDADE (CO_DUPLICIDADE) on delete cascade;

prompt
prompt Creating table S_EXCESSO_CARENCIA
prompt =================================
prompt
create table S_EXCESSO_CARENCIA
(
  CO_TIPO_CURSO      NUMBER(10) not null,
  SG_SERIE           VARCHAR2(5) not null,
  CO_TURNO           CHAR(1) not null,
  CO_TIPO_DISCIPLINA NUMBER(10) not null,
  NR_TURMAS          NUMBER(10) not null,
  NR_PROFISSIONAIS   NUMBER(10),
  QTDE_40H           NUMBER(10),
  QTDE_20H           NUMBER(10),
  QTDE_RESIDUO       NUMBER(10)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_EXCESSO_CARENCIA
  add constraint U183_95 primary key (CO_TIPO_CURSO,SG_SERIE,CO_TURNO,CO_TIPO_DISCIPLINA,NR_TURMAS)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_EXCESSO_CARENCIA
  add constraint FK_DISC_EXCESSO foreign key (CO_TIPO_DISCIPLINA)
  references S_TIPO_DISCIPLINA (CO_TIPO_DISCIPLINA) on delete cascade;
alter table S_EXCESSO_CARENCIA
  add constraint FK_SERIE_EXCESSO foreign key (SG_SERIE)
  references S_SERIE (SG_SERIE) on delete cascade;
alter table S_EXCESSO_CARENCIA
  add constraint FK_TIPO_CURSO_EXCE foreign key (CO_TIPO_CURSO)
  references S_TIPO_CURSO (CO_TIPO_CURSO) on delete cascade;

prompt
prompt Creating table S_UNIDADEFUNC
prompt ============================
prompt
create table S_UNIDADEFUNC
(
  CO_FUNCIONARIO    CHAR(10) not null,
  ANO_SEM           CHAR(5) not null,
  CO_UNIDADE        CHAR(5) not null,
  CO_CARGO          VARCHAR2(17),
  NU_CARGA_CONTRATO NUMBER(10),
  NU_HORA_ENTRADA   CHAR(5),
  NU_HORA_INI_ALMOC CHAR(5),
  NU_HORA_FIM_ALMOC CHAR(5),
  NU_HORA_SAIDA     CHAR(5),
  ST_ALTERA_NOTAS   CHAR(1),
  DS_FICHA_PESSOAL  BLOB,
  DS_SENHA          CHAR(6),
  NIVEL_SALARIAL    CHAR(3),
  ID_PROFESSOR      CHAR(1),
  CO_AREA_ATUACAO   NUMBER(10),
  ST_CANCELADO      CHAR(1),
  DT_ADMISSAO       DATE,
  DT_ATUALIZA_FUNC  DATE
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_UNIDADEFUNC
  add constraint U152_63 primary key (CO_FUNCIONARIO,ANO_SEM,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_UNIDADEFUNC
  add constraint R181_607 foreign key (CO_CARGO)
  references S_CARGO (CO_CARGO) on delete cascade;
alter table S_UNIDADEFUNC
  add constraint R181_608 foreign key (CO_FUNCIONARIO)
  references S_FUNCIONARIO (CO_FUNCIONARIO) on delete cascade;
alter table S_UNIDADEFUNC
  add constraint R181_609 foreign key (ANO_SEM,CO_UNIDADE)
  references S_PERIODOUNIDADE (ANO_SEM,CO_UNIDADE) on delete cascade;
alter table S_UNIDADEFUNC
  add constraint R181_610 foreign key (CO_AREA_ATUACAO)
  references S_AREA_ATUACAO (CO_AREA_ATUACAO) on delete cascade;
create index FOREIGN162 on S_UNIDADEFUNC (CO_CARGO,CO_UNIDADE)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
create index FOREIGN165 on S_UNIDADEFUNC (CO_AREA_ATUACAO,CO_UNIDADE,CO_FUNCIONARIO)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_FUNCIONARIO_OCOR
prompt =================================
prompt
create table S_FUNCIONARIO_OCOR
(
  CO_UNIDADE         CHAR(5) not null,
  CO_OCORRENCIA      NUMBER(10) not null,
  CO_FUNCIONARIO     CHAR(10) not null,
  ANO_SEM            CHAR(5) not null,
  CO_TIPO_OCORRENCIA NUMBER(10),
  DT_OCORRENCIA      DATE,
  DS_OCORRENCIA      BLOB,
  HO_OCORRENCIA      CHAR(5),
  ST_RECADO          CHAR(30),
  ST_RECADO_DADO     CHAR(30),
  DS_USUARIO_RECADO  CHAR(20)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_FUNCIONARIO_OCOR
  add constraint U153_64 primary key (CO_UNIDADE,CO_OCORRENCIA,CO_FUNCIONARIO,ANO_SEM)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_FUNCIONARIO_OCOR
  add constraint S_FUNC_OC_STO_FK foreign key (CO_TIPO_OCORRENCIA,CO_UNIDADE)
  references S_TIPO_OCORRENCIA (CO_TIPO_OCORRENCIA,CO_UNIDADE) on delete cascade;
alter table S_FUNCIONARIO_OCOR
  add constraint S_FUNC_OC_SUF_FK foreign key (CO_FUNCIONARIO,ANO_SEM,CO_UNIDADE)
  references S_UNIDADEFUNC (CO_FUNCIONARIO,ANO_SEM,CO_UNIDADE) on delete cascade;
create index FOREIGN169 on S_FUNCIONARIO_OCOR (CO_TIPO_OCORRENCIA)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_FUNCIONARIO_TURM
prompt =================================
prompt
create table S_FUNCIONARIO_TURM
(
  CO_UNIDADE     CHAR(5) not null,
  ANO_SEM        CHAR(5) not null,
  CO_TURMA       NUMBER(10) not null,
  CO_DISCIPLINA  CHAR(4) not null,
  CO_FUNCIONARIO CHAR(10) not null,
  PROF_RESP      CHAR(1),
  CO_CURSO       NUMBER(10) not null,
  CO_SEQ_SERIE   NUMBER(10) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_FUNCIONARIO_TURM
  add constraint U154_65 primary key (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_FUNCIONARIO,CO_CURSO,CO_SEQ_SERIE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_FUNCIONARIO_TURM
  add constraint R149_573 foreign key (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_CURSO,CO_SEQ_SERIE)
  references S_TURMA_DISCIPLINA (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_CURSO,CO_SEQ_SERIE) on delete cascade;
alter table S_FUNCIONARIO_TURM
  add constraint R149_574 foreign key (CO_FUNCIONARIO,ANO_SEM,CO_UNIDADE)
  references S_UNIDADEFUNC (CO_FUNCIONARIO,ANO_SEM,CO_UNIDADE) on delete cascade;
create index FOREIGN136 on S_FUNCIONARIO_TURM (CO_TURMA,CO_DISCIPLINA,CO_UNIDADE,ANO_SEM)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
create index FOREIGN137 on S_FUNCIONARIO_TURM (CO_FUNCIONARIO,CO_UNIDADE,ANO_SEM)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_GRADE_CURRIC
prompt =============================
prompt
create table S_GRADE_CURRIC
(
  CO_GRADE_CURRIC NUMBER(10) not null,
  CO_TIPO_CURSO   NUMBER(10) not null,
  ANO             NUMBER(10) not null,
  DT_GRADE        DATE,
  TURNO           CHAR(2) not null,
  NU_GRADE        CHAR(15),
  DS_GRADE        CHAR(40),
  NU_SEMANAS      NUMBER(10)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_GRADE_CURRIC
  add constraint PK_GRADE_CURRIC primary key (CO_GRADE_CURRIC,CO_TIPO_CURSO,ANO,TURNO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_GRADE_CURRIC
  add constraint R_146 foreign key (CO_TIPO_CURSO)
  references S_TIPO_CURSO (CO_TIPO_CURSO) on delete cascade;

prompt
prompt Creating table S_GRAU_SERIE
prompt ===========================
prompt
create table S_GRAU_SERIE
(
  DS_CORRESP_GRAU  CHAR(4),
  CO_CURSO         NUMBER(10) not null,
  CO_UNIDADE       CHAR(5) not null,
  DS_CORRESP_SERIE CHAR(5),
  ANO_SEM          CHAR(5) not null,
  DS_CORRESP_OCOR  CHAR(15),
  DS_CORRESP_APROV CHAR(15),
  DS_CORRESP_REPR  CHAR(15),
  DS_CORRESP_ADAPT CHAR(20),
  DS_CORRESP_FUNC  CHAR(20),
  DS_CORRESP_CURSO CHAR(10),
  DS_ABREV_CURSO   CHAR(5)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_GRAU_SERIE
  add constraint U155_66 primary key (CO_CURSO,ANO_SEM,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_GRAU_SERIE
  add constraint R151_576 foreign key (CO_CURSO,ANO_SEM,CO_UNIDADE)
  references S_CURSO (CO_CURSO,ANO_SEM,CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_HISTORICO
prompt ==========================
prompt
create table S_HISTORICO
(
  CO_ALUNO      CHAR(12) not null,
  DS_OBSERVACAO BLOB,
  CO_UNIDADE    CHAR(5) not null,
  DS_APTO_GRAU  CHAR(3),
  DS_APTO_SERIE CHAR(3)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_HISTORICO
  add constraint U156_67 primary key (CO_ALUNO,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_HISTORICO
  add constraint FK_HIST_ALU foreign key (CO_ALUNO)
  references S_ALUNO (CO_ALUNO) on delete cascade;

prompt
prompt Creating table S_HISTORICO_FASE
prompt ===============================
prompt
create table S_HISTORICO_FASE
(
  CO_ALUNO         CHAR(12) not null,
  FASE             VARCHAR2(10),
  CO_UNIDADE       CHAR(5) not null,
  ANO              CHAR(4),
  IDADE            NUMBER,
  NU_DIAS_LETIVOS  NUMBER,
  CARGA_HORARIA    VARCHAR2(10),
  FALTAS           VARCHAR2(10),
  RESULTADO        VARCHAR2(20),
  NO_ESTAB_ENSINO  VARCHAR2(60),
  NO_CIDADE_ENSINO VARCHAR2(40),
  SG_UF_ENSINO     CHAR(2)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_HISTORICO_FASE
  add constraint PKS_HISTORICO_FASE primary key (CO_ALUNO,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_HISTORICO_FASE
  add constraint R_114 foreign key (CO_ALUNO,CO_UNIDADE)
  references S_HISTORICO (CO_ALUNO,CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_HISTORICO_SERIE
prompt ================================
prompt
create table S_HISTORICO_SERIE
(
  CO_SERIE           VARCHAR2(60) not null,
  CO_ALUNO           CHAR(12) not null,
  CO_ANO_SEM         CHAR(5) not null,
  CO_UNIDADE         CHAR(5) not null,
  DS_CURSO           VARCHAR2(50),
  DS_NOME_COLEGIO    CHAR(60),
  TP_PERIODO         CHAR(1),
  DS_CIDADE          CHAR(30),
  DS_UF_CIDADE       CHAR(2),
  DS_RESULTADO_FINAL CHAR(15),
  NU_AULAS_DADAS     VARCHAR2(6),
  NU_DIAS_LETIVOS    CHAR(3),
  NU_FALTAS          CHAR(3),
  DS_SERIE           CHAR(30),
  CTR_IMPORT         CHAR(1),
  NU_FALTAS_HA       CHAR(12),
  NU_FALTAS_DL       CHAR(12)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_HISTORICO_SERIE
  add constraint U157_68 primary key (CO_SERIE,CO_ANO_SEM,CO_ALUNO,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_HISTORICO_SERIE
  add constraint R_130 foreign key (CO_ALUNO,CO_UNIDADE)
  references S_HISTORICO (CO_ALUNO,CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_HISTORICO_NOTA
prompt ===============================
prompt
create table S_HISTORICO_NOTA
(
  CO_SERIE          VARCHAR2(60) not null,
  CO_ANO_SEM        CHAR(5) not null,
  CO_ALUNO          CHAR(12) not null,
  CO_UNIDADE        CHAR(5) not null,
  CO_HISTORICO_NOTA NUMBER(10) not null,
  CO_DISCIPLINA     CHAR(4),
  NU_NOTA_01        CHAR(5),
  DS_DISCIPLINA     VARCHAR2(60),
  NU_FALTAS         CHAR(3),
  NU_NOTA_02        VARCHAR2(12),
  NU_CARGA_HORARIA  VARCHAR2(7),
  NU_AULAS_DADAS    VARCHAR2(6),
  NU_NOTA_03        VARCHAR2(12),
  TP_DISCIPLINA     VARCHAR2(30),
  NU_CREDITO        FLOAT,
  DS_APROV          CHAR(30),
  CTR_IMPORT        CHAR(1),
  NU_ORDEM          NUMBER(10),
  TP_OBRIGATORIA    CHAR(1)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_HISTORICO_NOTA
  add constraint U158_69 primary key (CO_HISTORICO_NOTA,CO_SERIE,CO_ANO_SEM,CO_ALUNO,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_HISTORICO_NOTA
  add constraint R_145 foreign key (CO_SERIE,CO_ANO_SEM,CO_ALUNO,CO_UNIDADE)
  references S_HISTORICO_SERIE (CO_SERIE,CO_ANO_SEM,CO_ALUNO,CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_TIPO_HORARIO
prompt =============================
prompt
create table S_TIPO_HORARIO
(
  CO_TIPO_HORARIO NUMBER(10) not null,
  DS_TIPO_HORARIO VARCHAR2(30),
  CO_CURSO        NUMBER(10) not null,
  ANO_SEM         CHAR(5) not null,
  CO_UNIDADE      CHAR(5) not null,
  NU_ORDEM        NUMBER(10)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TIPO_HORARIO
  add constraint PK_TIPO_HORARIO primary key (CO_TIPO_HORARIO,CO_CURSO,CO_UNIDADE,ANO_SEM)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TIPO_HORARIO
  add constraint R_131 foreign key (CO_CURSO,ANO_SEM,CO_UNIDADE)
  references S_CURSO (CO_CURSO,ANO_SEM,CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_HORARIO_ALUNO
prompt ==============================
prompt
create table S_HORARIO_ALUNO
(
  CO_UNIDADE      CHAR(5) not null,
  CO_TIPO_HORARIO NUMBER(10) not null,
  ANO_SEM         CHAR(5) not null,
  NU_DIA          NUMBER(10) not null,
  NU_TEMPO        NUMBER(10) not null,
  CO_DISCIPLINA   CHAR(4) not null,
  CO_TURMA        NUMBER(10) not null,
  CO_ALUNO        CHAR(12) not null,
  CO_CURSO        NUMBER(10) not null,
  CO_SEQ_SERIE    NUMBER(10) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_HORARIO_ALUNO
  add constraint PK_S_HORARIO_ALUNO primary key (CO_UNIDADE,ANO_SEM,NU_DIA,NU_TEMPO,CO_DISCIPLINA,CO_TURMA,CO_ALUNO,CO_TIPO_HORARIO,CO_CURSO,CO_SEQ_SERIE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_HORARIO_ALUNO
  add constraint R_132 foreign key (CO_TIPO_HORARIO,CO_CURSO,CO_UNIDADE,ANO_SEM)
  references S_TIPO_HORARIO (CO_TIPO_HORARIO,CO_CURSO,CO_UNIDADE,ANO_SEM) on delete cascade;
alter table S_HORARIO_ALUNO
  add constraint R155_581 foreign key (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_ALUNO,CO_CURSO,CO_SEQ_SERIE)
  references S_ALUNO_TURMA_DISC (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_ALUNO,CO_CURSO,CO_SEQ_SERIE) on delete cascade;
create index FOREIGN139 on S_HORARIO_ALUNO (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_ALUNO)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_HORARIO_EJA
prompt ============================
prompt
create table S_HORARIO_EJA
(
  CO_HORARIO NUMBER(10) not null,
  CO_TURMA   NUMBER(10) not null,
  CO_UNIDADE CHAR(5) not null,
  SEM_EJA    CHAR(5) not null,
  CO_DIA     NUMBER(10) not null,
  CO_AULA    NUMBER(10) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_HORARIO_EJA
  add constraint PK_S_HORARIO_EJA primary key (CO_HORARIO,CO_TURMA,CO_UNIDADE,SEM_EJA)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_HORARIO_EJA
  add constraint FK_SHOREJA_STUREJA foreign key (CO_TURMA,CO_UNIDADE,SEM_EJA)
  references S_TURMA_EJA (CO_TURMA,CO_UNIDADE,SEM_EJA) on delete cascade;

prompt
prompt Creating table S_HORARIO_TURMA
prompt ==============================
prompt
create table S_HORARIO_TURMA
(
  CO_TIPO_HORARIO NUMBER(10) not null,
  NU_DIA_SEMANA   NUMBER(10) not null,
  CO_CURSO        NUMBER(10) not null,
  NU_TEMPO        NUMBER(10) not null,
  CO_UNIDADE      CHAR(5) not null,
  ANO_SEM         CHAR(5) not null,
  CO_FUNCIONARIO  CHAR(10) not null,
  CO_TURMA        NUMBER(10) not null,
  CO_DISCIPLINA   CHAR(4) not null,
  CO_SEQ_SERIE    NUMBER(10) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_HORARIO_TURMA
  add constraint PK_S_HORARIO_TURMA primary key (NU_DIA_SEMANA,NU_TEMPO,CO_FUNCIONARIO,CO_TIPO_HORARIO,CO_CURSO,CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_SEQ_SERIE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_HORARIO_TURMA
  add constraint R_133 foreign key (CO_TIPO_HORARIO,CO_CURSO,CO_UNIDADE,ANO_SEM)
  references S_TIPO_HORARIO (CO_TIPO_HORARIO,CO_CURSO,CO_UNIDADE,ANO_SEM) on delete cascade;
alter table S_HORARIO_TURMA
  add constraint R_144 foreign key (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_FUNCIONARIO,CO_CURSO,CO_SEQ_SERIE)
  references S_FUNCIONARIO_TURM (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_FUNCIONARIO,CO_CURSO,CO_SEQ_SERIE) on delete cascade;
alter table S_HORARIO_TURMA
  add constraint R161_329 foreign key (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_CURSO,CO_SEQ_SERIE)
  references S_TURMA_DISCIPLINA (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_CURSO,CO_SEQ_SERIE) on delete cascade;
create index FOREIGN140 on S_HORARIO_TURMA (CO_UNIDADE,ANO_SEM)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_MENSAGEM
prompt =========================
prompt
create table S_MENSAGEM
(
  ANO_SEM      CHAR(5) not null,
  DS_RELATORIO CHAR(20) not null,
  DS_MENSAGEM  CHAR(255),
  CO_ALUNO     CHAR(12) not null,
  CO_UNIDADE   CHAR(5) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_MENSAGEM
  add constraint U162_73 primary key (DS_RELATORIO,ANO_SEM,CO_ALUNO,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_MENSAGEM
  add constraint R157_583 foreign key (ANO_SEM,CO_ALUNO,CO_UNIDADE)
  references S_ALUNO_PER_UNID (ANO_SEM,CO_ALUNO,CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_MENSAO
prompt =======================
prompt
create table S_MENSAO
(
  CO_MENSAO       CHAR(5) not null,
  DS_MENSAO       CHAR(30),
  CO_CURSO        NUMBER(10) not null,
  ANO_SEM         CHAR(5) not null,
  NU_NOTA_CORRESP FLOAT,
  CO_UNIDADE      CHAR(5) not null,
  NU_NOTA_INICIO  FLOAT,
  NU_NOTA_FIM     FLOAT
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_MENSAO
  add constraint U163_74 primary key (CO_MENSAO,CO_CURSO,ANO_SEM,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_MENSAO
  add constraint R158_584 foreign key (CO_CURSO,ANO_SEM,CO_UNIDADE)
  references S_CURSO (CO_CURSO,ANO_SEM,CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_NOTA
prompt =====================
prompt
create table S_NOTA
(
  CO_UNIDADE        CHAR(5) not null,
  ANO_SEM           CHAR(5) not null,
  CO_TURMA          NUMBER(10) not null,
  CO_ALUNO          CHAR(12) not null,
  NU_AULAS_DADAS_B1 CHAR(4),
  CO_CURSO          NUMBER(10) not null,
  CO_SEQ_SERIE      NUMBER(10) not null,
  NU_NOTA_B1        VARCHAR2(7),
  CO_DISCIPLINA     CHAR(4) not null,
  NU_NOTA_RECUP_1   VARCHAR2(7),
  NU_FALTAS_B1      VARCHAR2(7),
  NU_AULAS_DADAS_B2 CHAR(4),
  NU_NOTA_B2        VARCHAR2(7),
  NU_NOTA_RECUP_2   VARCHAR2(7),
  NU_FALTAS_B2      VARCHAR2(7),
  NU_AULAS_DADAS_B3 CHAR(4),
  NU_NOTA_B3        VARCHAR2(7),
  NU_NOTA_RECUP_3   VARCHAR2(7),
  NU_FALTAS_B3      VARCHAR2(7),
  NU_AULAS_DADAS_B4 CHAR(4),
  NU_NOTA_B4        VARCHAR2(7),
  NU_NOTA_RECUP_4   VARCHAR2(7),
  NU_FALTAS_B4      VARCHAR2(7),
  NU_MEDIA_ANUAL    CHAR(5),
  NU_RECUP_ESPECIAL VARCHAR2(7),
  NU_RECUP_FINAL    VARCHAR2(7),
  NU_MEDIA_FINAL    VARCHAR2(12),
  NU_MEDIA_S1       VARCHAR2(12),
  NU_MEDIA_S2       VARCHAR2(12),
  NU_MEDIA_APOS_S1  VARCHAR2(12),
  NU_MEDIA_APOS_S2  VARCHAR2(12),
  NU_MAXPONTOS_B1   CHAR(5),
  NU_MAXPONTOS_B2   CHAR(5),
  NU_MAXPONTOS_B3   CHAR(5),
  NU_MAXPONTOS_B4   CHAR(5),
  NU_NOTA_SM1       VARCHAR2(7),
  NU_NOTA_SM2       VARCHAR2(7),
  NU_NOTA_SM3       VARCHAR2(7),
  NU_NOTA_SM4       VARCHAR2(7),
  NU_NOTA_SM5       VARCHAR2(7),
  NU_NOTA_SM6       VARCHAR2(7),
  NU_NOTA_SM7       VARCHAR2(7),
  NU_NOTA_SM8       VARCHAR2(7),
  NU_NOTA_SM9       VARCHAR2(7),
  ST_CONSELHO       CHAR(1),
  NU_ORDEM_1        FLOAT,
  NU_ORDEM_2        FLOAT,
  CO_ALUNO_TURMA    NUMBER(10) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_NOTA
  add constraint PK_S_NOTA primary key (CO_UNIDADE,CO_TURMA,ANO_SEM,CO_ALUNO,CO_CURSO,CO_SEQ_SERIE,CO_ALUNO_TURMA,CO_DISCIPLINA)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_NOTA
  add constraint FK_NOTA_ALUTURM foreign key (CO_UNIDADE,CO_TURMA,ANO_SEM,CO_ALUNO,CO_CURSO,CO_SEQ_SERIE,CO_ALUNO_TURMA)
  references S_ALUNO_TURMA (CO_UNIDADE,CO_TURMA,ANO_SEM,CO_ALUNO,CO_CURSO,CO_SEQ_SERIE,CO_ALUNO_TURMA) on delete cascade;
alter table S_NOTA
  add constraint R159_585 foreign key (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_ALUNO,CO_CURSO,CO_SEQ_SERIE)
  references S_ALUNO_TURMA_DISC (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_ALUNO,CO_CURSO,CO_SEQ_SERIE) on delete cascade;
create index FOREIGN145 on S_NOTA (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_ALUNO)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
create index FOREIGN146 on S_NOTA (CO_UNIDADE,CO_TURMA,ANO_SEM,CO_ALUNO)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_OBSERVACOES
prompt ============================
prompt
create table S_OBSERVACOES
(
  CO_OBSERVACAO   NUMBER(10) not null,
  NOME_OBSERVACAO CHAR(50),
  CO_UNIDADE      CHAR(5) not null,
  DS_OBSERVACAO   BLOB
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_OBSERVACOES
  add constraint U165_76 primary key (CO_OBSERVACAO,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_OBSERVACOES
  add constraint R160_587 foreign key (CO_UNIDADE)
  references S_UNIDADE (CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_PASSIVO
prompt ========================
prompt
create table S_PASSIVO
(
  CO_PASSIVO NUMBER(10) not null,
  CO_ALUNO   CHAR(12),
  NU_PASSIVO VARCHAR2(12),
  DS_CAIXA   VARCHAR2(20),
  DT_PASSIVO DATE default SYSDATE
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_PASSIVO
  add constraint PKS_PASSIVO primary key (CO_PASSIVO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_PASSIVO
  add constraint FK_PASS_ALU foreign key (CO_ALUNO)
  references S_ALUNO (CO_ALUNO) on delete cascade;

prompt
prompt Creating table S_PERIODO
prompt ========================
prompt
create table S_PERIODO
(
  TURNO           CHAR(2) not null,
  CO_GRADE_CURRIC NUMBER(10) not null,
  ANO             NUMBER(10) not null,
  CO_TIPO_CURSO   NUMBER(10) not null,
  SG_SERIE        VARCHAR2(5) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_PERIODO
  add constraint PK_PERIODO primary key (TURNO,ANO,CO_GRADE_CURRIC,CO_TIPO_CURSO,SG_SERIE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_PERIODO
  add constraint R_147 foreign key (SG_SERIE)
  references S_SERIE (SG_SERIE) on delete cascade;

prompt
prompt Creating table S_PREREQUISITO
prompt =============================
prompt
create table S_PREREQUISITO
(
  TURNO              CHAR(2) not null,
  ANO                NUMBER(10) not null,
  CO_GRADE_CURRIC    CHAR(10) not null,
  CO_DISCIPLINA_REQ  CHAR(4) not null,
  CO_TIPO_CURSO      NUMBER(10) not null,
  SG_SERIE           VARCHAR2(5) not null,
  CO_TIPO_DISCIPLINA NUMBER(10) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_PREREQUISITO
  add constraint PK_PREREQUISITO primary key (TURNO,ANO,CO_GRADE_CURRIC,CO_DISCIPLINA_REQ,CO_TIPO_CURSO,SG_SERIE,CO_TIPO_DISCIPLINA)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
create index XIF121S_PREREQUISI on S_PREREQUISITO (CO_GRADE_CURRIC)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_TIPO_RESPONSAVEL
prompt =================================
prompt
create table S_TIPO_RESPONSAVEL
(
  CO_TIP_RESPONSAVEL NUMBER(10) not null,
  DS_TIP_RESPONSAVEL CHAR(30)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TIPO_RESPONSAVEL
  add constraint U167_78 primary key (CO_TIP_RESPONSAVEL)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_RESPONSAVEL
prompt ============================
prompt
create table S_RESPONSAVEL
(
  CO_RESPONSAVEL     VARCHAR2(20) not null,
  DS_RESPONSAVEL     CHAR(40),
  CO_UNIDADE         CHAR(5) not null,
  TP_SEXO            CHAR(1),
  CO_TIP_RESPONSAVEL NUMBER(10),
  CO_SEQ_CIDADE      NUMBER(10),
  DS_RESP_ORDEM      CHAR(40),
  DS_NATURALIDADE    CHAR(20),
  DS_UF_NASCIMENTO   CHAR(2),
  DT_NASCIMENTO      DATE,
  DS_ENDERECO        CHAR(40),
  DS_BAIRRO          CHAR(20),
  NU_CEP             CHAR(9),
  DS_CIDADE          CHAR(20),
  DS_UF_CIDADE       CHAR(2),
  NU_TELEFONE        CHAR(14),
  NU_CELULAR         CHAR(14),
  DS_PROFISSAO       CHAR(30),
  DS_LOCAL_TRAB      CHAR(40),
  DS_ENDERECO_TRAB   CHAR(40),
  DS_BAIRRO_TRAB     CHAR(20),
  NU_CEP_TRAB        CHAR(9),
  DS_CIDADE_TRAB     CHAR(20),
  DS_UF_CIDADE_TRAB  CHAR(2),
  NU_TELEFONE_TRAB   CHAR(14),
  NU_RAMAL_TRAB      CHAR(6),
  DS_E_MAIL          CHAR(100),
  DS_INSTRUCAO       CHAR(40),
  NU_RG              CHAR(15),
  DS_ORGAO_EMISSOR   CHAR(30),
  DT_EMISSAO         DATE,
  NU_CPF             CHAR(14),
  VL_RENDA_FAMILIAR  NUMBER(10),
  NU_DEPENDENTES     CHAR(2),
  DT_ATUALIZACAO_END DATE,
  DT_ATUALIZA_END    DATE
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_RESPONSAVEL
  add constraint U168_79 primary key (CO_RESPONSAVEL,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_RESPONSAVEL
  add constraint R166_591 foreign key (CO_TIP_RESPONSAVEL)
  references S_TIPO_RESPONSAVEL (CO_TIP_RESPONSAVEL) on delete cascade;
alter table S_RESPONSAVEL
  add constraint R166_592 foreign key (CO_SEQ_CIDADE)
  references S_CIDADE (CO_SEQ_CIDADE) on delete cascade;
alter table S_RESPONSAVEL
  add constraint R168_336 foreign key (CO_UNIDADE)
  references S_UNIDADE (CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_RESPONS_ALUNO
prompt ==============================
prompt
create table S_RESPONS_ALUNO
(
  CO_RESPONSAVEL   VARCHAR2(20),
  CO_RESPONS_ALUNO NUMBER(10) not null,
  CO_ALUNO         CHAR(12),
  CO_UNIDADE       CHAR(5) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_RESPONS_ALUNO
  add constraint PKS_RESPONS_ALUNO primary key (CO_RESPONS_ALUNO,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_RESPONS_ALUNO
  add constraint FK_RESPALU_ALU foreign key (CO_ALUNO)
  references S_ALUNO (CO_ALUNO) on delete cascade;
alter table S_RESPONS_ALUNO
  add constraint R167_593 foreign key (CO_RESPONSAVEL,CO_UNIDADE)
  references S_RESPONSAVEL (CO_RESPONSAVEL,CO_UNIDADE) on delete cascade;
create index IN_SRESALU_COALUNO on S_RESPONS_ALUNO (CO_ALUNO,CO_RESPONSAVEL,CO_UNIDADE)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_TIPO_SUBCONT_EDU
prompt =================================
prompt
create table S_TIPO_SUBCONT_EDU
(
  TP_SUBCONT_EDUC    CHAR(18) not null,
  TP_CONTEUDO_EDUC   NUMBER(10) not null,
  ANO_SEM            CHAR(5) not null,
  CO_CURSO           NUMBER(10) not null,
  CO_SEQ_SERIE       NUMBER(10) not null,
  CO_UNIDADE         CHAR(5) not null,
  DS_SUB_CONT_EDUC   BLOB,
  CO_ORD_SUB_CONT_ED NUMBER(10)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TIPO_SUBCONT_EDU
  add constraint XPKS_TIPO_SUBCONT_ primary key (TP_SUBCONT_EDUC,TP_CONTEUDO_EDUC,ANO_SEM,CO_CURSO,CO_SEQ_SERIE,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TIPO_SUBCONT_EDU
  add constraint R_136 foreign key (TP_CONTEUDO_EDUC,ANO_SEM,CO_CURSO,CO_SEQ_SERIE,CO_UNIDADE)
  references S_TIPO_CONT_EDUC (TP_CONTEUDO_EDUC,ANO_SEM,CO_CURSO,CO_SEQ_SERIE,CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_SUBCONT_EDUC_NOT
prompt =================================
prompt
create table S_SUBCONT_EDUC_NOT
(
  TP_SUBCONT_EDUC   CHAR(18) not null,
  TP_CONTEUDO_EDUC  NUMBER(10) not null,
  ANO_SEM           CHAR(5) not null,
  CO_CURSO          NUMBER(10) not null,
  CO_SEQ_SERIE      NUMBER(10) not null,
  CO_UNIDADE        CHAR(5) not null,
  CO_TURMA          NUMBER(10) not null,
  CO_DISCIPLINA     CHAR(4) not null,
  CO_ALUNO          CHAR(12) not null,
  NOTA_SUBCONT_EDUC CHAR(1)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_SUBCONT_EDUC_NOT
  add constraint XPKS_SUBCONT_EDUC_ primary key (TP_SUBCONT_EDUC,TP_CONTEUDO_EDUC,ANO_SEM,CO_CURSO,CO_SEQ_SERIE,CO_UNIDADE,CO_TURMA,CO_DISCIPLINA,CO_ALUNO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_SUBCONT_EDUC_NOT
  add constraint R_137 foreign key (TP_SUBCONT_EDUC,TP_CONTEUDO_EDUC,ANO_SEM,CO_CURSO,CO_SEQ_SERIE,CO_UNIDADE)
  references S_TIPO_SUBCONT_EDU (TP_SUBCONT_EDUC,TP_CONTEUDO_EDUC,ANO_SEM,CO_CURSO,CO_SEQ_SERIE,CO_UNIDADE) on delete cascade;
alter table S_SUBCONT_EDUC_NOT
  add constraint R_138 foreign key (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_ALUNO,CO_CURSO,CO_SEQ_SERIE)
  references S_ALUNO_TURMA_DISC (CO_UNIDADE,ANO_SEM,CO_TURMA,CO_DISCIPLINA,CO_ALUNO,CO_CURSO,CO_SEQ_SERIE) on delete cascade;

prompt
prompt Creating table S_TURMA_HORARIO
prompt ==============================
prompt
create table S_TURMA_HORARIO
(
  CO_TURMA       NUMBER(10) not null,
  CO_SEQ_SERIE   NUMBER(10) not null,
  ANO_SEM        CHAR(5) not null,
  CO_CURSO       NUMBER(10) not null,
  CO_UNIDADE     CHAR(5) not null,
  DS_AULA_1      VARCHAR2(10),
  DS_AULA_2      VARCHAR2(10),
  DS_HORARIO_1   VARCHAR2(5),
  DS_HORARIO_2   VARCHAR2(5),
  DS_EDFISICA_1  VARCHAR2(10),
  DS_EDFISICA_2  VARCHAR2(10),
  DS_HORARIOEF_1 VARCHAR2(5),
  DS_HORARIOEF_2 VARCHAR2(5)
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TURMA_HORARIO
  add constraint U195_149 primary key (CO_TURMA,CO_SEQ_SERIE,ANO_SEM,CO_CURSO,CO_UNIDADE)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_TURMA_HORARIO
  add constraint R195_377 foreign key (CO_TURMA,CO_UNIDADE,ANO_SEM,CO_CURSO,CO_SEQ_SERIE)
  references S_TURMA (CO_TURMA,CO_UNIDADE,ANO_SEM,CO_CURSO,CO_SEQ_SERIE) on delete cascade;

prompt
prompt Creating table S_UNIDADE_CURSO
prompt ==============================
prompt
create table S_UNIDADE_CURSO
(
  CO_UNIDADE    CHAR(5) not null,
  CO_TIPO_CURSO NUMBER(10) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_UNIDADE_CURSO
  add constraint PK_UNIDCURSO primary key (CO_UNIDADE,CO_TIPO_CURSO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_UNIDADE_CURSO
  add constraint FK_UNIDCURS_CURS foreign key (CO_TIPO_CURSO)
  references S_TIPO_CURSO (CO_TIPO_CURSO) on delete cascade;
alter table S_UNIDADE_CURSO
  add constraint FK_UNIDCURS_UNID foreign key (CO_UNIDADE)
  references S_UNIDADE (CO_UNIDADE) on delete cascade;

prompt
prompt Creating table S_USUARIO
prompt ========================
prompt
create table S_USUARIO
(
  DS_USUARIO    CHAR(30) not null,
  DS_SENHA      CHAR(30),
  ST_CONSULTA   CHAR(1),
  TP_SUPERVISOR CHAR(1),
  DS_LOG        CLOB
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_USUARIO
  add constraint U205_199 primary key (DS_USUARIO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
create index SENHA_USUARIO on S_USUARIO (DS_SENHA)
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table S_USUARIO_ACESSO
prompt ===============================
prompt
create table S_USUARIO_ACESSO
(
  DS_USUARIO CHAR(30) not null,
  DS_ACESSO  CHAR(100) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_USUARIO_ACESSO
  add constraint U170_81 primary key (DS_USUARIO,DS_ACESSO)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_USUARIO_ACESSO
  add constraint R183_612 foreign key (DS_USUARIO)
  references S_USUARIO (DS_USUARIO) on delete cascade;

prompt
prompt Creating table S_VERSAO
prompt =======================
prompt
create table S_VERSAO
(
  CO_UNIDADE    CHAR(5) not null,
  DS_VERSAO     VARCHAR2(15),
  NU_SEQUENCIAL NUMBER(10) not null
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table S_VERSAO
  add constraint PK_VERSAO primary key (CO_UNIDADE,NU_SEQUENCIAL)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table TAB_LOG_ATUALIZA
prompt ===============================
prompt
create table TAB_LOG_ATUALIZA
(
  SEQUENCIAL         NUMBER(18) not null,
  USUARIO            VARCHAR2(20),
  CO_UNIDADE         VARCHAR2(5),
  TABELA             VARCHAR2(255),
  OPERACAO           VARCHAR2(10),
  SQL                VARCHAR2(4000),
  INDICADOR_EXTRAIDO VARCHAR2(1),
  DATA_HORA          DATE,
  BLOB1              BLOB,
  BLOB2              BLOB
)
tablespace SGE
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );
alter table TAB_LOG_ATUALIZA
  add constraint PK_TAB_LOG_ATUALIZA primary key (SEQUENCIAL)
  using index 
  tablespace SGE
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 66K
    minextents 1
    maxextents unlimited
  );


spool off
