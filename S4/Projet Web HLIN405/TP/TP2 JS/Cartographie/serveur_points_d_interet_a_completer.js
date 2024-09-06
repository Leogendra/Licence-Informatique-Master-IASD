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

let pis = {}
let PI = JSON.parse(fs.readFileSync('OSM_Metropole_restauration_bar.json','utf8'));

// A COMPLETER ICI !

console.dir(pis)

app.listen(8888);

app.get('/', function(request, response) {
    console.log('/');
    let types = [];
    for (let type in pis) types.push(type);
    response.end(JSON.stringify(types));
});

app.get('/:type', function(request, response) {
    console.log("/"+request.params.type);
    response.end(JSON.stringify(pis[request.params.type]));
});

