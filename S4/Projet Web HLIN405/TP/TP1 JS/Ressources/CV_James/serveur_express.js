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

app.get('/', function(request, response) {
    let text = fs.readFileSync('alcools.json','utf8');
    response.end(text);
});
