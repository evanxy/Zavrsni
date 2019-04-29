drop database if exists projekt1;
create database projekt1 default character set utf8;
#c:\xampp\mysql\bin\mysql -uedunova -pedunova --default_character_set=utf8 < C:\xampp\htdocs\projekt1\skripta.sql

use projekt1;

create table operater(
sifra int not null primary key auto_increment,
ime varchar(50) not null,
prezime varchar(50) not null,
email varchar(100) not null,
lozinka char(60) not null,
uloga varchar(50) not null
);

insert into operater (ime,prezime,email,lozinka,uloga) values 
(
    'Lorna','Tokić',
    'tokiclorna31@gmail.com','$2y$10$0oeK5JKlHslw1ksWLcimZOV2ggnEh5vltZq3ckemw4eIH79GYpTwi',
    'admin'
);

create table dogadaj(
sifra int not null primary key auto_increment,
naziv varchar(50) not null
);

create table utrka(
sifra int not null primary key auto_increment,
naziv varchar(50) not null,
datum datetime,
brojsudionika int not null,
dogadaj int not null,
kotizacija decimal(18,2)
);

create table osoba(
sifra int not null primary key auto_increment,
ime varchar(50) not null,
prezime varchar(50) not null,
email varchar(100) not null,
lozinka char(60) not null
);

create table sudionik(
sifra int not null primary key auto_increment,
osoba int not null,
brojprijave char(4)
);

create table clan(
utrka int not null,
sudionik int not null
);

alter table utrka add foreign key (dogadaj) references dogadaj(sifra);

alter table sudionik add foreign key (osoba) references osoba(sifra);

alter table clan add foreign key (utrka) references utrka(sifra);
alter table clan add foreign key (sudionik) references sudionik(sifra);


insert into dogadaj (sifra, naziv) values
(null, 'Osječki Ferivi polumaraton'),
(null, 'Liga za djecu superjunake'),
(null, 'Jankovački polumaraton');

insert into utrka (sifra, naziv, datum, brojsudionika, dogadaj, cijena) values
(null, '10k', null, 100, 1, null),
(null, '5k', null, 100, 1, null),
(null, 'Štafeta Ž', null, 100, 1, null),
(null, '2.kolo', null, 100, 2, null),
(null, '10k', null, 100, 3, null),
(null, '17.kolo', null, 100, 2, null);

insert into osoba (sifra, ime, prezime, email) values
(null,'Tomislav','Jakopec','tjakopec@gmail.com'),
(null,'Josip','Dasović','josip.dasovic22@gmail.com'),
(null,'Robert','Zita','zitaa91@gmail.com'),
(null,'Darko','Klisurić','klisuric1995@gmail.com'),
(null,'Kristina','Terzić','kristina.terzic01@gmail.com'),
(null,'Željko','Livaja','zeljaos@gmail.com'),
(null,'Maja','Balaš','maja.balas@gmail.com'),
(null,'Leon','Mikić','leon.mikic93@gmail.com'),
(null,'Dino','Medić','dino.medic54@gmail.com'),
(null,'Ivan','Alošinac','alosinac111@gmail.com'),
(null,'Borna','Vrandečić','bornavrandecic@gmail.com'),
(null,'Filip','Jozić','filip.jozic@gmail.com'),
(null,'Tomislav','Glavaš','tomxjug2@gmail.com'),
(null,'Kristijan','Baro','baro.kristijan@gmail.com'),
(null,'Matej','Grgić','grgic.matej@gmail.com');

insert into sudionik (sifra,osoba,brojprijave) values
(null,1,null),(null,2,null),(null,3,null),(null,4,null),(null,5,null),
(null,6,null),(null,7,null),(null,8,null),(null,9,null);

insert into clan (utrka,sudionik) values
(2,2),(6,3),(4,4),(2,5),(2,9);


select 'OK' as poruka;