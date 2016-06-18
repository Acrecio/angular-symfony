"use strict";

var express = require('express');
var app = express();

var PORT = 3000;

app.use('/', express.static(__dirname + '/dist'));

app.listen(PORT)
console.log("Running on port "+PORT);
