drop table typy cascade constraints;
drop table rankingAdvanced cascade constraints;
drop table rankingBasic cascade constraints;
drop table formuly cascade constraints;
drop table hSzachy cascade constraints;
drop table hBierki cascade constraints;
drop table hWarcaby cascade constraints;
drop table hPoker cascade constraints;
drop table hPilka cascade constraints;
drop table gracze cascade constraints;
drop table gry cascade constraints;
drop table rozgrywki cascade constraints;

create table typy(
    typ varchar2(20) primary key
);

create table gry(
    nazwa varchar2(20) primary key,
    max_graczy number(2) not null
);

create table gracze(
    nick varchar2(20) primary key,
    haslo varchar2(20) not null,
    typ_gracza varchar2(20) not null references typy
);

create table rozgrywki(
    id number(6) primary key,
    nazwa varchar2(20) not null references gry
);

create table hSzachy(
    id number(6) not null references rozgrywki,
    gracz_1 varchar2(20) not null references gracze,
    gracz_2 varchar2(20) not null references gracze,
    zwyciezca varchar2(20) not null references gracze
);

create table hWarcaby(
    id number(6) not null references rozgrywki,
    gracz_1 varchar2(20) not null references gracze,
    gracz_2 varchar2(20) not null references gracze,
    zwyciezca varchar2(20) not null references gracze
);

create table hBierki(
    id number(6) not null references rozgrywki,
    gracz_1 varchar2(20) not null references gracze,
    gracz_2 varchar2(20) not null references gracze,
    gracz_3 varchar2(20) not null references gracze,
    gracz_4 varchar2(20) not null references gracze,
    zwyciezca varchar2(20) not null references gracze
);

create table hPilka(
    id number(6) not null references rozgrywki,
    gracz_1 varchar2(20) not null references gracze,
    gracz_2 varchar2(20) not null references gracze,
    zwyciezca varchar2(20) not null references gracze
);

create table hPoker(
    id number(6) not null references rozgrywki,
    gracz_1 varchar2(20) not null references gracze,
    gracz_2 varchar2(20) not null references gracze,
    gracz_3 varchar2(20) not null references gracze,
    gracz_4 varchar2(20) not null references gracze,
    gracz_5 varchar2(20) not null references gracze,
    gracz_6 varchar2(20) not null references gracze,
    zwyciezca varchar2(20) not null references gracze
);

create table rankingBasic(
    nick_gracza varchar2(20) not null references gracze,
    ilosc_zagranych number(5) not null,
    ilosc_wygranych number(5) not null,
    ilosc_remisow number(5) not null,
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

create or replace procedure dodaj_rankingi(nick varchar2) is
    cursor formulyCur is (select wartosc_domyslna, id from formuly);
    cursor graCur is (select nazwa from gry);
begin
    for gra in graCur
    loop
    insert into rankingBasic values (nick, 0, 0, 0, gra.nazwa);
    end loop;

    for formula in formulyCur
    loop
        insert into rankingAdvanced values (nick, formula.wartosc_domyslna, formula.id);
    end loop;
end;
/

create or replace trigger dodaj_rank
after insert on gracze
for each row
begin
   dodaj_rankingi(:new.nick);
end;
/


insert into typy(typ) values('uzytkownik');
insert into typy(typ) values('admin');
insert into typy(typ) values('bot');

insert into gry(nazwa, max_graczy) values('szachy', 2);
insert into gry(nazwa, max_graczy) values('warcaby', 2);
insert into gry(nazwa, max_graczy) values('bierki', 4);
insert into gry(nazwa, max_graczy) values('poker', 6);
insert into gry(nazwa, max_graczy) values('pilka', 2);

insert into gracze(nick, haslo, typ_gracza) values('alphazero', 'oro', 'bot');
insert into gracze(nick, haslo, typ_gracza) values('admin', '123', 'admin');
insert into gracze(nick, haslo, typ_gracza) values('bob', 'oro', 'admin');
insert into gracze(nick, haslo, typ_gracza) values('abc', 'abc', 'uzytkownik');
insert into gracze(nick, haslo, typ_gracza) values('marek', 'maro', 'uzytkownik');
insert into gracze(nick, haslo, typ_gracza) values('scube420', '6969', 'uzytkownik');
insert into gracze(nick, haslo, typ_gracza) values('darek68', 'hehe', 'uzytkownik');

-- "SELECT (3*(w-p)+(z-w-p)) from (SELECT ilosc_zagranych z, ilosc_wygranych w, ilosc_przegranych p FROM rankingBasic WHERE nick_gracza='".$nick."' ')
-- insert into formuly values(1, 'szachy', "", 0);

select * from gry;
select * from gracze;
