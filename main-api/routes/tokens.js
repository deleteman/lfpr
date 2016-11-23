'use strict'

const router = require("express").Router(),
	jwt = require("jsonwebtoken"),
	logger = require("../lib/logger"),
	request = require("request"),
	GitHub = require("github-api"),
	models = require("../models"),
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
		let access_token = body.access_token;

		let gh = new GitHub({
			token: access_token
		});
		let loggedUser = gh.getUser();
		loggedUser
			.getProfile()
			.then((prof) => {
				let prof_data = prof.data;
				prof_data.gh_id = prof_data.id;
				prof_data.login_token = jwt.sign({gid: prof_data.id}, config.get('jwt.secret'), {expiresIn: config.get('jwt.expiration')});
				delete prof_data.id;

				models
					.users
					.findOneAndUpdate({gh_id: prof_data.gh_id}, prof_data, {upsert: true, new: true, setDefaultsOnInsert: true})
					.exec((err, usr) => {
						if(err) return next(err);
						res.json(usr);
					});
			})
			.catch((err) => {
				console.log("ERROR: ", err)
				next(err);
			});
	});
});


module.exports = router;