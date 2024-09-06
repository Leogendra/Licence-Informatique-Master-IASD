var express = require("express");
var fs = require('fs');
var app = express();
app.use(function (request, response, next) {
    response.setHeader("Content-type", "application/json");    
    response.setHeader('Access-Control-Allow-Origin', '*');
    response.setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
    response.setHeader('Access-Control-Allow-Headers', '*');
    next();
});

app.listen(8888);

app.get('/chanteurs', function(request, response) {
    console.log('/chanteurs');
    let text = fs.readFileSync('JSON/chanteurs.json','utf8');
    response.end(text);
});

app.get('/albums/:nomChanteur', function(request, response) {
    console.log("/albums/"+request.params.nomChanteur);
    let text = fs.readFileSync('JSON/albums-'+request.params.nomChanteur+'.json','utf8');
    response.end(text);
});

app.get('/album/:nomAlbum', function(request, response) {
    console.log("/albums/"+request.params.nomAlbum);    
    let text = fs.readFileSync('JSON/album-'+request.params.nomAlbum+'.json','utf8');
    response.end(text);
});
