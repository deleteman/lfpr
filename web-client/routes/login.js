'use strict'

const express = require('express'),
	config = require("config"),
	router = express.Router();


router.get('/', (req, res, next) => {
  res.render('login', { title: 'Login with your Github account' });
});


router.get('/github', (req, res, next) => {
	let auth_url = config.get('github.auth_url');
	auth_url += "?client_id=" + config.get('github.clientID') + "&redirect_uri=" + config.get('github.callbackURL');
  res.redirect(auth_url);
});

router.get('/github_cb', (req, res, next) => {
	res.render('index', {title: 'You are logged in, this page should send the code to the main API and se the authentication right'});
});


module.exports = router;
