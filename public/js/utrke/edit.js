
  $( "#uvjet" ).autocomplete({
    source: putanja + "sudionik/traziSudionik",
    minLength: 1,
    focus: function(event,ui){
      event.preventDefault();
    },
    select:function(event,ui){
      spremi(ui.item);
    }
  }).data("ui-autocomplete")._renderItem=function(ul,objekt){
      return $("<li>" + objekt.ime + " " + objekt.prezime + "</li>").appendTo(ul);
  };

  function spremi(sudionik){

    $.ajax({
      type: "POST",
      url: putanja + "utrka/dodajSudionika",
      data: "utrka=" + sifraUtrka +"&sudionik=" + sudionik.sifra,
      success: function(vratioServer){
        if(vratioServer==="OK"){
          $("#podaci").append(
            "<tr>" + 
              "<td>" + sudionik.ime + " " + sudionik.prezime + "</td>" +
              "<td><a class=\"obrisi\" id=\"p_" + sudionik.sifra + "\" href=\"#\">Obriši</a></td>" +
           "</tr>");
           definirajBrisanje();
        }else{
          alert(vratioServer);
        }
      }
    });

  }

  function definirajBrisanje(){
    $(".obrisi").click(function(){
      let a = $(this);
      $.ajax({
        type: "POST",
        url: putanja + "utrka/obrisiSudionik",
        data: "utrka=" + sifraUtrka +"&sudionik=" + a.attr("id").split("_")[1],
        success: function(vratioServer){
          if(vratioServer==="OK"){
           a.parent().parent().remove();
          }else{
            alert(vratioServer);
          }
        }
      });
  
  
    });  
  }
  definirajBrisanje();

  














