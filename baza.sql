drop table gracze;
drop table typy;
drop table rankingAdvanced;
drop table rankingBasic;
drop table formuly;
drop table gry;
drop table statystyki;
drop table historie;

create table typy(
    typ varchar2(10) primary key
);

create table gry(
    nazwa varchar2(20) primary key,
    max_graczy number(2) not null
);

create table gracze(
    nick varchar2(20) primary key,
    haslo varchar2(20) not null,
    typ_gracza varchar2(10) not null references typy
);

create table statystyki(
    nick_gracza varchar2(20) references gracze,
    gra varchar2(20) not null references gry
);

create table rozgrywki(
    id number(6) primary key,
    nazwa varchar2(20) not null references gry
);

--tabele z historiami będą miały forme:
--create table (nazwa_gry)(id)(
--numer_ruchu number(4) not null unique,
--ruch_gracza1 varchar2(4) not null,
--....
--);
create table historie(
    nick_gracza varchar2(20) not null references gracze,
    id number(6) not null references rozgrywki
);

create table rankingBasic(
    nick_gracza varchar2(20) not null references gracze,
    ilosc_zagranych number(5) not null,
    ilosc_wygranych number(5) not null,
    ilosc_remisów number(5) not null,
    gra varchar2(20) not null references gry
);

create table formuly(
    id number(2) primary key,
    gra varchar2(20) not null references gry,
    formula varchar2(100) not null,
    wartosc_domyslna number(5) not null
);

create table rankingAdvanced(
    nick_gracza varchar2(20) not null references gracze,
    pkt_rankingowe varchar2(20) not null,
    id_formuly number(2) not null references formuly
);

-----------------------TRIGGERY---------------------------

create or replace procedure dodaj_statsy(nick varchar2) is
    cursor  graCur is (select  * from gry);
begin
    for row in graCur
    loop
        insert into statystyki values (nick, row.nazwa);
    end loop;
end;
/

create or replace procedure zsdr(nick varchar2, gra varchar2) is
    cursor formlyCur is (select * from formuly);
begin
    insert into rankingBasic values (nick, 0, 0, 0, gra);
    for formula in formlyCur
    loop
        insert into rankingAdvanced values (nick, formula.wartosc_domyslna, formula.id);
    end loop;
end;
/

create or replace trigger dodaj_statystyki
after insert on gracze
for each row
begin
   dodaj_statsy(:new.nick);
end;
/

create or replace trigger ze_statystyk_do_rankingu
after insert or update on statystyki
for each row
begin
    zsdr(:new.nick_gracza, :new.gra);
end;
/



