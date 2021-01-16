--po zaladowaniu bazy na sql komenda @baza.sql
--trzeba sie zrelogowac aby baza dzialala na stronie
--(to samo sie ma do jakiejkolwiek modyfikacji bazy)
drop table typy cascade constraints;
drop table rankingAdvanced cascade constraints;
drop table rankingBasic cascade constraints;
drop table formuly cascade constraints;
drop table hSzachy cascade constraints;
drop table hBierki cascade constraints;
drop table hWarcaby cascade constraints;
drop table hChinczyk cascade constraints;
drop table hPilka cascade constraints;
drop table gracze cascade constraints;
drop table gry cascade constraints;
drop table sposobyObliczania cascade constraints;
drop table rozgrywki cascade constraints;

create table typy(
    typ varchar2(20) primary key
);

create table gry(
    nazwa varchar2(20) primary key,
    opis varchar2(200),
    min_graczy number(2) not null,
    max_graczy number(2) not null
);

create table gracze(
    nick varchar2(20) primary key,
    haslo varchar2(20) not null,
    typ_gracza varchar2(20) not null references typy
);

create table hSzachy(
    id number(6) primary key,
    miejsce_1 varchar2(20) not null references gracze,
    miejsce_2 varchar2(20) not null references gracze
);

create table hWarcaby(
    id number(6) primary key,
    miejsce_1 varchar2(20) not null references gracze,
    miejsce_2 varchar2(20) not null references gracze
);

create table hBierki(
    id number(6) primary key,
    miejsce_1 varchar2(20) not null references gracze,
    miejsce_2 varchar2(20) not null references gracze,
    miejsce_3 varchar2(20) references gracze,
    miejsce_4 varchar2(20) references gracze
);

create table hPilka(
    id number(6) primary key,
    miejsce_1 varchar2(20) not null references gracze,
    miejsce_2 varchar2(20) not null references gracze
);

create table hChinczyk(
    id number(6) primary key,
    miejsce_1 varchar2(20) not null references gracze,
    miejsce_2 varchar2(20) not null references gracze,
    miejsce_3 varchar2(20) references gracze,
    miejsce_4 varchar2(20) references gracze
);

create table rozgrywki(
    id number(6) not null,
    gra varchar(20) not null,
    constraint pk primary key(id, gra),
    data timestamp not null
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
    nazwa varchar2(20) not null,
    formula varchar2(100) not null
);

create table sposobyObliczania(
    id number(3) primary key,
    id_formuly number(2) not null references formuly,
    gra varchar2(20) not null references gry,
    wartosc_domyslna number(5) not null
);

create table rankingAdvanced(
    nick_gracza varchar2(20) not null references gracze,
    pkt_rankingowe number(10) not null,
    id_sposobu number(2) not null references sposobyObliczania
);

-----------------------TRIGGERY---------------------------

create or replace procedure dodaj_rankingi(nick varchar2) is
    cursor sposobyCur is (select wartosc_domyslna, id from sposobyObliczania);
    cursor graCur is (select nazwa from gry);
begin
    for game in graCur
    loop
        insert into rankingBasic values (nick, 0, 0, 0, game.nazwa);
    end loop;

    for sposob in sposobyCur
    loop
        insert into rankingAdvanced values (nick, sposob.wartosc_domyslna, sposob.id);
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

create or replace trigger usun_gracza
before delete on gracze
for each row
begin
   delete from rankingAdvanced where nick_gracza=:old.nick;
   delete from rankingBasic where nick_gracza=:old.nick;
end;
/

create or replace procedure dodanie_gry(nazwa varchar2) is
    cursor graczeCur is (select nick from gracze);
begin
    insert into sposobyObliczania values ((select nvl(max(id), 0) + 1 from sposobyObliczania), 0, nazwa, 1000);
    for gracz in graczeCur
    loop
        insert into rankingBasic values (gracz.nick, 0, 0, 0, nazwa);
    end loop;
end;
/

create or replace trigger dodaj_gre
after insert on gry
for each row
begin
    dodanie_gry(:new.nazwa);
end;
/

create or replace procedure dodanie_sposobu(id number, wrt number) is
    cursor graczeCur is (select nick from gracze);
begin
   for gracz in graczeCur
   loop
        insert into rankingAdvanced values (gracz.nick, wrt, id);
   end loop;
end;
/

create or replace trigger dodaj_sposob
after insert on sposobyObliczania
for each row
begin
   dodanie_sposobu(:new.id, :new.wartosc_domyslna);
end;
/

create or replace procedure dodaj_gre_historia(id_rozgrywki number, nazwa_gry varchar2) is
begin
    insert into rozgrywki values (id_rozgrywki, nazwa_gry, systimestamp);
end;
/

create or replace procedure update_rankingu(nazwa_gry varchar2, gracz varchar2, czy_wygral number) is
    cursor staty is (select ilosc_zagranych, ilosc_wygranych, ilosc_remisow from rankingBasic where nick_gracza = gracz AND gra = nazwa_gry);
begin
    for row in staty loop
        if gracz is not null then
            if czy_wygral = 1 then
                update rankingBasic set ilosc_zagranych = ilosc_zagranych + 1, ilosc_wygranych = ilosc_wygranych + 1 where nick_gracza = gracz and gra = nazwa_gry;
            else
                update rankingBasic set ilosc_zagranych = ilosc_zagranych + 1 where nick_gracza = gracz and gra = nazwa_gry;
            end if;
        end if;
    end loop;
end;
/

create or replace trigger update_ranking_chinczyk
before insert on hChinczyk
for each row
declare
    nazwa varchar2(20) := 'chinczyk';
    nowe_id number(6);
begin
    update_rankingu(nazwa, :new.miejsce_1, 1);
    update_rankingu(nazwa, :new.miejsce_2, 0);
    update_rankingu(nazwa, :new.miejsce_3, 0);
    update_rankingu(nazwa, :new.miejsce_4, 0);

    select nvl(max(id), 0) + 1 into nowe_id from hChinczyk;
    dodaj_gre_historia(nowe_id, nazwa);
end;
/

create or replace trigger update_ranking_pilka
before insert on hPilka
for each row
declare
    nazwa varchar2(20) := 'pilka';
    nowe_id number(6);
begin
    update_rankingu(nazwa, :new.miejsce_1, 1);
    update_rankingu(nazwa, :new.miejsce_2, 0);

    select nvl(max(id), 0) + 1 into nowe_id from hPilka;
    dodaj_gre_historia(nowe_id, nazwa);
end;
/

create or replace trigger update_ranking_warcaby
before insert on hWarcaby
for each row
declare
    nazwa varchar2(20) := 'warcaby';
    nowe_id number(6);
begin
    update_rankingu(nazwa, :new.miejsce_1, 1);
    update_rankingu(nazwa, :new.miejsce_2, 0);

    select nvl(max(id), 0) + 1 into nowe_id from hWarcaby;
    dodaj_gre_historia(nowe_id, nazwa);
end;
/

create or replace trigger update_ranking_szachy
before insert on hSzachy
for each row
declare
    nazwa varchar2(20) := 'szachy';
    nowe_id number(6);
begin
    update_rankingu(nazwa, :new.miejsce_1, 1);
    update_rankingu(nazwa, :new.miejsce_2, 0);

    select nvl(max(id), 0) + 1 into nowe_id from hSzachy;
    dodaj_gre_historia(nowe_id, nazwa);
end;
/

create or replace trigger update_ranking_bierki
before insert on hBierki
for each row
declare
    nazwa varchar2(20) := 'bierki';
    nowe_id number(6);
begin
    update_rankingu(nazwa, :new.miejsce_1, 1);
    update_rankingu(nazwa, :new.miejsce_2, 0);
    update_rankingu(nazwa, :new.miejsce_3, 0);
    update_rankingu(nazwa, :new.miejsce_4, 0);

    select nvl(max(id), 0) + 1 into nowe_id from hBierki;
    dodaj_gre_historia(nowe_id, nazwa);
end;
/

------------------------------------------DANE POCZATKOWE-----------------------------------------------

--formula podstawowa ma id=0
insert into formuly values (0, 'elo', 'R 32 S 10 R 400 / ^ 10 R 400 / ^ 10 E 400 / ^ + / - * +');

insert into typy values('uzytkownik');
insert into typy values('admin');
insert into typy values('bot');

insert into gry values('szachy', 'Grasz pionkami. I wgl fajnie sie gra w szachy zagraj se w szachy', 2, 2);
insert into gry values('warcaby', 'Grasz pionkami, ale nie takimi fajnymi jak w szachach. I wgl srednio sie gra w warcaby zagraj se w warcaby', 2, 2);
insert into gry values('chinczyk', 'Znowu grasz pionkami co jest kruczi', 2, 4);
insert into gry values('pilka', 'Gralo sie w gimnazjum oj gralo', 2, 2);
insert into gry values('bierki', 'Patyki jakies ciongasz', 2, 4);

insert into gracze values('alphazero', 'oro', 'bot');
insert into gracze values('uzytkownik_slayer', 'xxx', 'bot');
insert into gracze values('mistrz', 'xxx', 'bot');
insert into gracze values('koxxx', 'xxx', 'bot');
insert into gracze values('asia', 'xxx', 'bot');
insert into gracze values('bot', 'xxx', 'bot');
insert into gracze values('da Vinki', 'xxx', 'bot');
insert into gracze values('Euleroo', 'xxx', 'bot');
insert into gracze values('senpai', 'xxx', 'bot');
insert into gracze values('admin', '123', 'admin');
insert into gracze values('bob', 'oro', 'admin');
insert into gracze values('abc', 'abc', 'uzytkownik');
insert into gracze values('marek', 'maro', 'uzytkownik');
insert into gracze values('scube420', '6969', 'uzytkownik');
insert into gracze values('darek68', 'hehe', 'uzytkownik');
insert into gracze values('kk418331', '$H00michek$', 'admin');

select * from gry;
select * from gracze;