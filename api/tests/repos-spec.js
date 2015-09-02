var should = require("should");


describe("#Repos", function () {
	
	describe("publish", function () {
		it("should change the published status of a repo to true")
	})
	describe("unpublish", function () {
		it("should change the published status of a repo to false")
	})
	describe("list", function  () {
		context("no filter", function () {
			it("should return the list of repos")
		})

		context("filtering", function () {
			it("should filter by language")
			it("should filter by owner")
		})

		context("sorting", function () {
			it("should sort results by language")
			it("should sort results by stars")
			it("should sort results by forks")
			it("should sort results by open issues")
		})

		context("searching", function () {
			//http://docs.mongodb.org/manual/reference/operator/query/text/#op._S_text
			it("should search using full text search")
		})
	})

	describe("details", function () {
		it("should return a valid json with the data of a repo")
	})

	describe("stats", function () {
		it("should return a valid json with stats from a repo")
	})

})
