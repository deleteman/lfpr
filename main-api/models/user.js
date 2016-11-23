'use strict'

const mongoose = require("mongoose"),
	Schema = mongoose.Schema;

let userSchema = new Schema({
	login: String,
    gh_id: Number,
	avatar_url: String,
	gravatar_id: String,
	type: String,
	site_admin: Boolean,
	name: String,
	company: String,
	blog: String,
	location: String,
	email: String,
	hireable: Boolean,
	bio: String,
	public_repos: Number,
	public_gists: Number,
	followers: Number,
	following: Number,
	member_since: Date,
	login_token: String
});

module.exports = mongoose.model('User', userSchema);