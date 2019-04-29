<?php

class UtrkaController extends ProtectedController
{

    function dodajSudionika()
    {

        echo Utrka::dodajSudionika(Request::post("utrka"),Request::post("sudionik"));

    }

    function obrisiSudionika()
    {

        echo Utrka::obrisiSudionika(Request::post("utrka"),Request::post("sudionik"));

    }

    function edit($id)
    {
        
        $_POST["sifra"]=$id;
        $kontrola = $this->kontrola();
        if($kontrola===true){
            Utrka::update($id);
            $this->index();
        }else{
            $view = new View();
            $view->render(
                'utrka/edit',
                [
                "poruka"=>$kontrola
                ]
            );
        }

    }

    function delete($id)
    {
            Utrka::delete($id);
            $this->index();
    }

    function kontrola()
    {
        if(Request::post("naziv")===""){
            return "Naziv obavezan";
        }

        if(strlen(Request::post("naziv"))>50){
            return "Naziv ne smije biti veći od 50 znakova";
        }

        if(Request::post("smjer")=="0"){
            return "Obavezan odabir događaja";
        }


        return true;
    }

    function add()
    {
        $this->prepareedit(Utrka::add());
    }

    function prepareedit($id)
    {
        $view = new View();
        $entitet = Utrka::find($id);
        $_POST["naziv"]=$entitet->naziv;
        $_POST["datum"]=$entitet->datum;
        $_POST["brojsudionika"]=$entitet->brojsudionika;
        $_POST["dogadaj"]=$entitet->dogadaj;
        $_POST["kotizacija"]=$entitet->kotizacija;
        $_POST["sifra"]=$entitet->sifra;

        $view->render(
            'utrke/edit',
            [
            "poruka"=>""
            ]
        );
    }


    function index(){
        $view = new View();
        $view->render(
            'utrke/index',
            [
            "utrke"=>Utrka
::read()
            ]
        );
    }
}