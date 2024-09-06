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

app.get('/toto', function(request, response) {
	response.setHeader("Content-type", "text/html");
	let text = "<h1>Hello World !</h1>";
	response.end(text);
});

app.get('/livres', function(request, response) {
	let text = " [\
		{\
			"titre": "cest la fete",\
			"auteur": "fungala"\
		},{\
			"titre": "Les Misérables",\
			"auteur": "Emiles Zola"\
		}\
	]";
	response.end(text);
});