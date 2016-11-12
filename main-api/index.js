'use strict'

const express = require("express"),	
	logger = require("./lib/logger"),
	bodyParser = require("body-parser"),
	config = require("config");

const tokenRouter = require("./routes/tokens");


const app = express();

app.use(bodyParser.json());

app.use('/tokens', tokenRouter);

app.use((err, req, res, next) => {
	logger.error("Error catched!");
	res.json({
		error: true,  
		data: err
	});
})

app.listen(config.get('webserver.port'), () => logger.info("Server up and running!"))