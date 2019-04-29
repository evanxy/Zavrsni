<?php

class AdminController
{
    function prijava()
    {
        $view = new View();
        $view->render('prijava',["poruka"=>""]);
    }

    function login()
    {
        $db=Db::getInstance();
        $izraz = $db->prepare("select ime,prezime,email,lozinka from operater where email=:email");
        $izraz->execute(["email"=>Request::post("email")]);
       
        $view = new View();

        if($izraz->rowCount()>0){
            $red=$izraz->fetch();
            if(password_verify(Request::post("password"),$red->lozinka)){
                $user = new stdClass();
                $user->ime=$red->ime;
                $user->prezime=$red->prezime;
                $user->email=$red->email;
                $user->imePrezime=$red->ime . " " . $red->prezime;

                Session::getInstance()->login($user);

               $npc = new NadzornaPlocaController();
               $npc->index();

            }else{
                $view->render('prijava',["poruka"=>"Unijeli ste netočnu lozinku"]);
            }
        }else{
            $view->render('prijava',["poruka"=>"Unijeli ste netočan email"]);
        }     
    }

    function odjava()
    {

        Session::getInstance()->odjava();
        $view = new View();
        $view->render('index',["poruka"=>""]);
    }

    function register(){
        $password = Request::post("password");
        $email = Request::post("email");
        $ime = Request::post("ime");
        $prezime = Request::post("prezime");

        if($password == null || $email == null || $ime == null || $prezime == null)
            return 0;

        $password = password_hash($password,PASSWORD_BCRYPT);

        $db=Db::getInstance();
        $izraz = $db->prepare("insert into operater (lozinka,ime,prezime,email)
         values ($password,$ime,$prezime,$email)");
        $izraz->execute();

    }
}