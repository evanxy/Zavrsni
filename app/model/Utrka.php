<?php

class Utrka
{

    public static function dodajSudionika($utrka,$sudionik)
    {
        $db = Db::getInstance();
        $db->beginTransaction();

        $izraz = $db->prepare("
                 select count(*) from clan where utrka=:utrka and sudionik=:sudionik;
        ");
        $izraz->execute(["utrka"=>$utrka, "sudionik"=>$sudionik]);
        $ukupno = $izraz->fetchColumn();
        $vrati="";
        if($ukupno>0){
            $vrati= "Sudionik postoji na utrci, nije dodan";
        }else{
            $izraz = $db->prepare("
            insert into clan(utrka,sudionik) values (:utrka,:sudionik);
            ");
            $izraz->execute(["utrka"=>$utrka, "sudionik"=>$sudionik]);
            $vrati="OK";
        }

        
        $db->commit();
        return $vrati;
    }


    public static function obrisiSudionika($utrka,$sudionik)
    {
        $db = Db::getInstance();

        $izraz = $db->prepare("
                delete from clan where utrka=:utrka and sudionik=:sudionik;
        ");
        $izraz->execute(["utrka"=>$utrka, "sudionik"=>$sudionik]);
       
        return "OK";
    }

    public static function read()
    {
        $db = Db::getInstance();
        $izraz = $db->prepare("
        
                  select 
                  a.sifra,
                  a.naziv,
                  b.naziv as dogadaj,
                  a.datum,
                  count(e.sudionik) as ukupno
                  from utrka a 
                  left join dogadaj    b on a.smjer    =b.sifra
                  left join osoba      d on c.osoba    =d.sifra
                  left join clan       e on a.sifra    =e.utrka
                  group by a.sifra,a.naziv,b.naziv,a.datum

        ");
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function find($id)
    {
        $db = Db::getInstance();
        $izraz = $db->prepare("select * from utrka where sifra=:sifra");
        $izraz->execute(["sifra"=>$id]);
        return $izraz->fetch();
    }

    public static function add()
    {
        $db = Db::getInstance();
        $izraz = $db->prepare("insert into utrka (naziv,datum,brojsudionika,dogadaj,kotizacija) 
        values ('',now(),'','',null)");
        $izraz->execute();
        return $db->lastInsertId();
    }

    public static function update($id)
    {
        $db = Db::getInstance();
        $izraz = $db->prepare("update utrka set 
        naziv=:naziv,
        datum=:datum,
        brojsudionika=:brojsudionika,
        dogadaj=:dogadaj,
        kotizacija=:kotizacija
        where sifra=:sifra");
        
        $izraz->bindParam("naziv",Request::post("naziv"),PDO::PARAM_STR);
        $izraz->bindParam("dogadaj",Request::post("dogadaj"),PDO::PARAM_INT);

        if(Request::post("datum")==""){
            $izraz->bindValue("datum",null,PDO::PARAM_NULL);
        }else{
            $izraz->bindParam("datum",Request::post("datum"),PDO::PARAM_STR);
        }

        $izraz->bindParam("brojsudionika",Request::post("brojsudionika"),PDO::PARAM_INT);
        $izraz->bindParam("kotizacija",Request::post("kotizacija"),PDO::PARAM_INT);

        $izraz->bindParam("sifra",$id,PDO::PARAM_INT);


        $izraz->execute();
    }

    public static function delete($id)
    {
        $db = Db::getInstance();
        $izraz = $db->prepare("delete from utrka where sifra=:sifra");
        $podaci = [];
        $podaci["sifra"]=$id;
        $izraz->execute($podaci);
    }

  


}