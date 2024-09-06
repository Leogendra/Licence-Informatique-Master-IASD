var largeurImage;
var hauteurImage;

function zoom(image) {
    largeurImage = image.style.width;
    hauteurImage = image.style.height;
    image.style.width = "auto";
    image.style.height = "auto";    
}

function dezoom(image) {
    image.style.width = largeurImage;
    image.style.height = hauteurImage;
}

function afficherAlcools() {
    $.getJSON("http://localhost:8888/", function(data) {
       let html = "";
       for (let objet of data) {
           html += "<li>"+objet.nom+"</li>";
       };
       $("#listeAlcools").append(html);
   });
}
