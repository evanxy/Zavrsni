<?php

class NadzornaPlocaController extends ProtectedController{
    function index()
    {

        $db = Db::getInstance();
        $izraz = $db->prepare("
        
                    select a.naziv, count(b.sudionik) as ukupno
                    from utrka a inner join clan b on a.sifra=b.utrka
                    group by a.naziv
                    order by 2 desc, 1 asc

        ");
        $izraz->execute();
        $rezultati = $izraz->fetchAll(PDO::FETCH_OBJ);
        $podaci=[];
        foreach($rezultati as $red){
            $p=new stdClass();
            $p->name=$red->naziv;
            $p->y=(int)$red->ukupno;
            $podaci[]=$p;
        }

        $view = new View();
        $view->render('nadzornaPloca',["podaci"=>json_encode($podaci,JSON_NUMERIC_CHECK)]);

    }

}