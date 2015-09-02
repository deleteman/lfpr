

module.exports = ReposHdlr;
function ReposHdlr(_model) { this.model = _model }

//@endpoint (url: /repos method: put)
ReposHdlr.prototype.publish = function(req, res, next) { }

//@endpoint (url: /repos method: put)
ReposHdlr.prototype.unpublish = function(req, res, next) { }

//@endpoint (url: /repos method: get)
ReposHdlr.prototype.list = function(req, res, next) { 
	var page = null, 
	size = null
	if(req.params.query.page || req.params.query.size) {
		page = req.params.query.page || 0
		size = req.params.query.size || 10
	}

	var query = {}

	var finder = this.model.find(query)

	if(page !== null && size !== null) {
		finder
		.skip(page * size)
		.limit(size)
	}

	finder.exec(function(err, list) {
		if(err) return next(err)
			res.send(list)
	})

}

//@endpoint (url: /repos method: get)
ReposHdlr.prototype.details = function(req, res, next) { }

//@endpoint (url: /repos method: get)
ReposHdlr.prototype.stats = function(req, res, next) { }

