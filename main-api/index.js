'use strict'

const express = require("express"),	
	logger = require("./lib/logger"),
	bodyParser = require("body-parser"),
	mongoose = require("mongoose"),
	jwt = require("jsonwebtoken"),
	config = require("config");

const routers = require("./routes/");


const app = express();

app.use(bodyParser.json());

app.use('/tokens', routers.tokens);
app.use('/repos', routers.repos);

app.use((req, res, next) => {
	let token = req.get('access_token');
	if(token) {
		jwt.verify(token, config.get('jwt.secret'), (err, decoded) => {
			if(err) {
				return next({
					error: true,
					code: "INVALID_ACCESS_TOKEN",
					msg: "Auth token invalid, please login"
				});
			} else {
				next();
			}
		});
	} else {
		next({
			error: true,
			code: "NO_ACCESS_TOKEN_PROVIDED",
			msg: "The access token was not found on the request, please login first."
		});
	}

});

app.use((err, req, res, next) => {
	logger.error("Error catched!");
	logger.error(err);
	res.json({
		error: true,  
		data: err
	});
})

mongoose.connect(config.get('mongo.connection_string'));
let  db = mongoose.connection;

db.on('open',  ()=> {
	logger.info("MongoDB Connection ready!");
	app.listen(config.get('webserver.port'), () => logger.info("Server up and running!"))
});
