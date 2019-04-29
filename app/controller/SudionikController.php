<?php

class SudionikController extends ProtectedController
{

    function traziSudionik(){
        echo json_encode(Sudionik::traziSudionik($_GET["term"]));
    }

    function delete($id)
    {
            Sudionik::delete($id);
            $this->index();
    }


    function edit($id)
    {
        $_POST["sifra"]=$id;
        $datoteka = APP::config("path") . "public/img/sudionici/" . $id . ".png"; 
        move_uploaded_file($_FILES["slika"]["tmp_name"],$datoteka);
      
        $kontrola = $this->kontrola();
        if($kontrola===true){
            Sudionik::update($id);
            $this->index();
        }else{
            $view = new View();
            $view->render(
                'sudionici/edit',
                [
                "poruka"=>$kontrola
                ]
            );
        }
        

    }

    function prepareedit($id){
        $view = new View();
        $sudionik = Sudionik::find($id);
        $_POST = (array)$sudionik;
        $view->render(
            'sudionici/edit',
            [
            "poruka"=>""
            ]
        );
    }

    function add(){
        header("location: " . App::config("url")."sudionik/prepareedit/".Sudionik::add());
    }


    function index($stranica=1){
        if($stranica<=0){
            $stranica=1;
        }
        if($stranica===1){
            $prethodna=1;
        }else{
            $prethodna=$stranica-1;
        }
        $sljedeca=$stranica+1;

        $view = new View();
        $view->render(
            'sudionici/index',
            [
            "sudionici"=>Sudionik::read($stranica),
            "prethodna"=>$prethodna,
            "sljedeca"=>$sljedeca
            ]
        );
    }


   public function __bulkinsert()
   {

    $db = Db::getInstance();

    $db->beginTransaction();
    for($i=1;$i<=2225;$i++){
        $izraz = $db->prepare("insert into osoba (sifra,ime,prezime,email) values
        (null,null,'sudionik','sudionik$i@gmail.com')");
        $izraz->execute();
        $zadnjaOsobaSifra = $db->lastInsertId();
        $izraz = $db->prepare("insert into sudionik(sifra,osoba,brojprijave) values 
        (null,$zadnjaOsobaSifra,null)");
        $izraz->execute();
        
    }
    $db->commit();
    echo "Sve OK";
   } 
}