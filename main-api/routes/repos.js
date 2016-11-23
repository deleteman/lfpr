'use strict'

const router = require("express").Router(),
	jwt = require("jsonwebtoken"),
	logger = require("../lib/logger"),
	request = require("request"),
	GitHub = require("github-api"),
	models = require("../models"),
	config = require("config");



/**
This endpoint inserts the current user into a queue
that queue will be processed by repo-workers, which will 
take the users credentials and pulll all repos.
*/
router.post('/', (req, res, next) => {

});

module.exports = router;