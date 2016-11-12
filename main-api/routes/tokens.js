'use strict'

const router = require("express").Router(),
	jwt = require("jsonwebtoken"),
	logger = require("../lib/logger"),
	request = require("request"),
	config = require("config");

router.post('/', (req, res, next) => {
	let code = req.body.code;

	logger.info("Getting access token for code: " + code)
	let payload = {
		client_id: config.get('github.clientID'),
		client_secret: config.get('github.clientSecret'),
		code: code
	};

	request({
		url: config.get('github.access_token_url'), 
		method: 'POST',
		json: payload
	}, (err, httpResp, body) => {
		if(err) {
			return next(err);
		}
		if(body.error) {
			return next(body);
		}
		console.log(body)

		//TODO:
		/*
			con body.access_token obtener datos del usuario
			con datos del usuario, buscar o crear el usuario en base
			firmar el token usando esos datos y asociarlo al usuario
			devolver datos del usuario junto con el token en la respuesta
		*/
	})

	/*jwt.sign({code: code}, config.get('jwt.secret'), {expiresIn: config.get('jwt.expiration')}, (err, token) => {
		if(err) {
			return next(err);
		}
		res.json({
			token: token
		});
	});
	*/
});


module.exports = router;