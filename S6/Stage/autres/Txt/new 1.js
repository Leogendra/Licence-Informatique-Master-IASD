function switch_time() {
  let deb = $("#time_deb").val();
  let fin = $("#time_fin").val();

  if (fin && deb) { // swap si heures à l'envers
    if ((deb.split(":")[0] > fin.split(":")[0]) || ((deb.split(":")[0] == fin.split(":")[0]) && (deb.split(":")[1] > fin.split(":")[1]))) {
      $("#time_deb").val(fin);
      $("#time_fin").val(deb);
    }
  }
}


function switch_date() {
  let deb = $("#date_deb").val();
  let fin = $("#date_fin").val();
  if (deb && fin) {
    let date_deb = new Date(deb + " 00:00:00");
    let date_fin = new Date(fin + " 00:00:00");
    if (date_deb.getTime() > date_fin.getTime()) { // swap si dates à l'envers
      let date_tmp = $("#date_deb").val();
      $("#date_deb").val($("#date_fin").val());
      $("#date_fin").val(date_tmp);
    }
  }
}

$("#time_fin").change(function() { switch_time(); });

$("#time_deb").change(function() { switch_time(); });

$("#date_deb").change(function() { switch_date(); });

$("#date_fin").change(function() { switch_date(); });

$("#salaire").change(function() {
    let salaire_tmp = $("#salaire").val();
  if (salaire) {
  $("#salaire").val(Math.abs(salaire_tmp));
  }
});