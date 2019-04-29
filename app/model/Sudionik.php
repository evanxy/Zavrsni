<?php

class Sudionik
{

    public static function delete($id)
    {
        $db = Db::getInstance();
        $db->beginTransaction();
        
        $izraz = $db->prepare("select osoba from sudionik where sifra=:sifra");
        $izraz->execute(["sifra"=>$id]);
        $sifraosoba=$izraz->fetchColumn(0);
        $izraz = $db->prepare("delete from sudionik where sifra=:sifra");
        $izraz->execute(["sifra"=>$id]);
        $izraz = $db->prepare("delete from osoba where sifra=:sifra");
        $izraz->execute(["sifra"=>$sifraosoba]);
        $db->commit();
    }

    public static function update($id)
    {
        $db = Db::getInstance();
        $db->beginTransaction();
        
        $izraz = $db->prepare("update osoba set 
        ime=:ime,
        prezime=:prezime,
        email=:email 
        where sifra=:sifra");
        $izraz->execute([
            "sifra"=>$_POST["sifraosoba"],
            "ime"=>$_POST["ime"],
            "prezime"=>$_POST["prezime"],
            "email"=>$_POST["email"]
        ]);
        $izraz = $db->prepare("update sudionik set 
        brojprijave=:brojprijave
        where sifra=:sifra");
        $izraz->execute([
            "sifra"=>$_POST["sifra"],
            "brojprijave"=>$_POST["brojprijave"]
        ]);
        $db->commit();
    }

    public static function find($id)
    {
        $db = Db::getInstance();
        $izraz = $db->prepare("
                    select
                    b.sifra,
                    a.sifra as sifraosoba,
                    a.ime,
                    a.prezime,
                    a.email,
                    b.brojprijave
                    from osoba a inner join sudionik b
                    on a.sifra=b.osoba where b.sifra=:sifra;
        ");
        $izraz->execute(["sifra"=>$id]);
        return $izraz->fetch();
    }

    public static function add(){
        $db=Db::getInstance();
        $db->beginTransaction();
       
        $izraz=$db->prepare("insert into osoba (sifra,ime,prezime,email) values
        (null,'','','')");
        $izraz->execute();
        $sifra = $db->lastInsertId();

        $izraz=$db->prepare("insert into sudionik(sifra,osoba,brojprijave) values 
        (null,$sifra,null)");
        $izraz->execute();
        $id=$db->lastInsertId();
        $db->commit();
        return $id;
    }

    public static function read($stranica=1)
    {
        $poStranici=8;
        $db = Db::getInstance();
        $izraz = $db->prepare("
                                select 
                                b.sifra, 
                                a.ime,
                                a.prezime,
                                a.email,
                                b.brojprijave,
                                count(c.utrka) as brojutrka
                            from osoba a 
                            inner join sudionik b on a.sifra=b.osoba
                            left join clan c on b.sifra=c.sudionik
                            group by
                                b.sifra, 
                                a.ime,
                                a.prezime,
                                a.email,
                                b.brojprijave
                            limit " . (($stranica*$poStranici) - $poStranici)  . ",$poStranici
        ");
        $izraz->execute();
        return $izraz->fetchAll();
    }


    public static function trazisudionik($uvjet)
    {
        $db = Db::getInstance();
        $izraz = $db->prepare("
                                select 
                                b.sifra, 
                                a.ime,
                                a.prezime,
                                a.email,
                                b.brojprijave,
                                count(c.utrka) as brojutrka
                            from osoba a 
                            inner join sudionik b on a.sifra=b.osoba
                            left join clan c on b.sifra=c.sudionik
                            where concat(a.ime, ' ', a.prezime) like :uvjet
                            group by
                                b.sifra, 
                                a.ime,
                                a.prezime,
                                a.email,
                                b.brojprijave

         ");
        $izraz->execute(["uvjet"=>"%" . $uvjet . "%"]);
        return $izraz->fetchAll();
    }

    public static function readGroups($utrka)
    {
        $db = Db::getInstance();
        $izraz = $db->prepare("
                                select 
                                b.sifra, 
                                concat(a.ime, ' ', a.prezime) as sudionik,
                                a.email,
                                b.brojprijave
                            from osoba a 
                            inner join sudionik b on a.sifra=b.osoba
                            inner join clan c on b.sifra=c.sudionik
                            where c.utrka=:utrka
        ");
        $izraz->execute(["utrka"=>$utrka]);
        return $izraz->fetchAll();
    }
}