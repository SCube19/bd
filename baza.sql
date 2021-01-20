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
    opis varchar2(600),
    min_graczy number(2) not null,
    max_graczy number(2) not null
);

create table gracze(
    nick varchar2(20) primary key,
    haslo varchar2(40) not null,
    typ_gracza varchar2(20) not null references typy
);

create table hSzachy(
    id number(6) primary key,
    miejsce_1 varchar2(20) not null references gracze,
    miejsce_2 varchar2(20) not null references gracze,
    historia varchar2(600) not null
);

create table hWarcaby(
    id number(6) primary key,
    miejsce_1 varchar2(20) not null references gracze,
    miejsce_2 varchar2(20) not null references gracze,
    historia varchar2(600) not null
);

create table hBierki(
    id number(6) primary key,
    miejsce_1 varchar2(20) not null references gracze,
    miejsce_2 varchar2(20) not null references gracze,
    miejsce_3 varchar2(20) references gracze,
    miejsce_4 varchar2(20) references gracze,
    historia varchar2(600) not null
);

create table hPilka(
    id number(6) primary key,
    miejsce_1 varchar2(20) not null references gracze,
    miejsce_2 varchar2(20) not null references gracze,
    historia varchar2(600) not null
);

create table hChinczyk(
    id number(6) primary key,
    miejsce_1 varchar2(20) not null references gracze,
    miejsce_2 varchar2(20) not null references gracze,
    miejsce_3 varchar2(20) references gracze,
    miejsce_4 varchar2(20) references gracze,
    historia varchar2(600) not null
);

create table rozgrywki(
    id number(6) not null,
    gra varchar(20) not null references gry,
    nick_gracza varchar2(20) not null references gracze,
    constraint pk primary key(id, gra, nick_gracza),
    data timestamp not null
);

create table rankingBasic(
    nick_gracza varchar2(20) not null references gracze,
    ilosc_zagranych number(5) not null,
    ilosc_wygranych number(5) not null,
    ilosc_przegranych number(5) not null,
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

create or replace procedure dodaj_gre_historia(id_rozgrywki number, nazwa_gry varchar2, nick varchar2) is
begin
    if nick is not null then
        insert into rozgrywki values (id_rozgrywki, nazwa_gry, nick, systimestamp);
    end if;
end;
/

create or replace procedure update_rankingu(nazwa_gry varchar2, gracz varchar2, czy_wygral number) is
begin
    if gracz is not null then
        if czy_wygral = 1 then
            update rankingBasic set ilosc_zagranych = ilosc_zagranych + 1, ilosc_wygranych = ilosc_wygranych + 1 where nick_gracza = gracz and gra = nazwa_gry;
        else
            update rankingBasic set ilosc_zagranych = ilosc_zagranych + 1, ilosc_przegranych = ilosc_przegranych + 1 where nick_gracza = gracz and gra = nazwa_gry;
        end if;
    end if;
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
    dodaj_gre_historia(nowe_id, nazwa, :new.miejsce_1);
    dodaj_gre_historia(nowe_id, nazwa, :new.miejsce_2);
    dodaj_gre_historia(nowe_id, nazwa, :new.miejsce_3);
    dodaj_gre_historia(nowe_id, nazwa, :new.miejsce_4);
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
    dodaj_gre_historia(nowe_id, nazwa, :new.miejsce_1);
    dodaj_gre_historia(nowe_id, nazwa, :new.miejsce_2);
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
    dodaj_gre_historia(nowe_id, nazwa, :new.miejsce_1);
    dodaj_gre_historia(nowe_id, nazwa, :new.miejsce_2);
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
    dodaj_gre_historia(nowe_id, nazwa, :new.miejsce_1);
    dodaj_gre_historia(nowe_id, nazwa, :new.miejsce_2);
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
    dodaj_gre_historia(nowe_id, nazwa, :new.miejsce_1);
    dodaj_gre_historia(nowe_id, nazwa, :new.miejsce_2);
    dodaj_gre_historia(nowe_id, nazwa, :new.miejsce_3);
    dodaj_gre_historia(nowe_id, nazwa, :new.miejsce_4);
end;
/

------------------------------------------DANE POCZATKOWE-----------------------------------------------

--formula podstawowa ma id=0
insert into formuly values (0, 'elo', 'R 32 S 10 R 400 / ^ 10 R 400 / ^ 10 E 400 / ^ + / - * +');
insert into formuly values (1, 'bsc_scr', 'R E 15 / +');

insert into typy values('uzytkownik');
insert into typy values('admin');
insert into typy values('bot');

insert into gry values('szachy', 'Gracze dysponuja bierkami w sklad ktorych wchodzi szesnascie bierek:
krol, hetman, dwa gonce, dwa skoczki, dwie wieze oraz osiem pionow. Gre zawsze rozpoczynaja biale.
Gracze na zmiane wykonuja posuniecia swoimi bierkami zgodnie z
zasadami ruchu dla danej bierki i jesli wejdzie ona na pole zajmowane przez przeciwnika, zbija jego bierke. Szach jest grozba zbicia krola.
Mat, czyli postawienie krola przeciwnika w szachu, przed ktorym nie ma obrony, konczy partie i oznacza zwyciestwo gracza, ktorego bierka matuje krola przeciwnika
', 2, 2);

insert into gry values('warcaby', 'Warcaby rozgrywane sa na planszy o rozmiarze 8x8 pol pokolorowanych na przemian na kolor jasny i ciemny. Kazdy
gracz rozpoczyna gre z dwunastoma pionami ustawionymi na ciemniejszych polach planszy, po ktorych sie poruszaja.
Jako pierwszy ruch wykonuje grajacy pionami bialymi, po czym gracze wykonuja na zmiane kolejne ruchy.
Celem gry jest zbicie wszystkich pionow przeciwnika albo zablokowanie wszystkich,
ktore pozostaja na planszy, pozbawiajac przeciwnika mozliwosci wykonania ruchu. Piony moga poruszac sie o
jedno pole do przodu po przekatnej na wolne pola.', 2, 2);

insert into gry values('chinczyk', 'Na poczatku pionki kazdego z graczy sa w schowku.
Gracze rzucaja kostka po trzy razy, az ktorys z graczy wyrzuci 6 - wtedy ustawia jeden ze
swoich pionkow na polu startowym i rzuca jeszcze raz, nastepnie przesuwa pionek w kierunku
zgodnym z ruchem wskazowek zegara. Jezeli ktorys gracz wyrzuci 6, moze rzucic jeszcze raz. Jesli podczas gry pionek jednego gracza stanie na polu zajmowanym przez drugiego,
pionek stojacy tutaj poprzednio zostaje zbity i wraca do swojego schowka. Kiedy gracz obejdzie pionkiem cala plansze dookola, wprowadza swoj pionek do domku.', 2, 4);

insert into gry values('pilka', 'Gra rozgrywa sie na boisku o wymiarach 10x8 kratek, z bramkami o szerokosci 2 kratek.
Celem jest umieszczenie w bramce przeciwnika pilki, ktora na poczatku jest na srodku.
Koniec gry nastepuje tez gdy ktorys z graczy nie moze wykonac zadnego ruchu. W jednym ruchu pilka moze byc przemieszczona poziomo,
pionowo lub po ukosie. Trasa pilki jest oznaczona linia. Pilka nie moze przemieszczac sie po brzegu boiska ani liniach, ale moze sie od nich sie odbijac -
wtedy gracz wykonuje kolejny ruch.', 2, 2);

insert into gry values('bierki', 'Gra polega na zbieraniu bierek tak aby nie poruszyc innych.
Bierki zostaja na poczatku rozsypane. Gracze kolejno zbieraja bierki w taki sposob aby nie poruszyc pozostalych bierek.
Dozwolone jest pomaganie sobie, wczesniej zebranymi bierkami. Jezeli jakakolwiek z lezacych bierek drgnie kolejka przechodzi na
nastepnego gracza. Gra konczy sie gdy wszystkie bierki zostana zebrane. Kaada z zebranych bierek ma okreslona wartosc punktowa.
Wygrywa gracz ktory zbierze najwiecej punktow.', 2, 4);

insert into sposobyObliczania (select nvl(max(id), 0) + 1, 1, 'bierki', 0 from sposobyObliczania);
insert into sposobyObliczania (select nvl(max(id), 0) + 1, 1, 'chinczyk', 0 from sposobyObliczania);

insert into gracze values('alphazero', 'xxx', 'bot');
insert into gracze values('uzytkownik_slayer', 'xxx', 'bot');
insert into gracze values('mistrz', 'xxx', 'bot');
insert into gracze values('koxxx', 'xxx', 'bot');
insert into gracze values('asia', 'xxx', 'bot');
insert into gracze values('bot', 'xxx', 'bot');
insert into gracze values('da Vinki', 'xxx', 'bot');
insert into gracze values('euler', 'xxx', 'bot');
insert into gracze values('senpai', 'xxx', 'bot');
insert into gracze values('joe_mama', 'xxx', 'bot');
insert into gracze values('xXx_jason2005_xXx', 'xxx', 'bot');
insert into gracze values('username', 'xxx', 'bot');
insert into gracze values('Pewdiepie', 'xxx', 'bot');
insert into gracze values('hide on bush', 'xxx', 'bot');
insert into gracze values('faker', 'xxx', 'bot');
insert into gracze values('Cauchy', 'xxx', 'bot');
insert into gracze values('zero-two', 'xxx', 'bot');
insert into gracze values('Rem', 'xxx', 'bot');
insert into gracze values('Ram', 'xxx', 'bot');
insert into gracze values('abc', 'xxx', 'bot');
insert into gracze values('marek', 'xxx', 'bot');
insert into gracze values('scube420', 'xxx', 'bot');
insert into gracze values('quebonafide', 'xxx', 'bot');
insert into gracze values('kk418331', 'a5e0467f5f947628892806b645f7641ecacd179e', 'admin');
insert into gracze values('kj418271', 'fb19f56cfa357eb991b3253907c6e422d88fb513', 'admin');

commit;

select * from gracze;