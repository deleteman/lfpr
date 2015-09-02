

module.exports = DevelopersHdlr;

function DevelopersHdlr(_model) { this.model = _model }

//@endpoint (url: /developers method: post)
DevelopersHdlr.prototype.newUser = function(req, res, next) { 
	var data = req.params.body
//...maybe do validation here?
	this.model.create(data, function(err, obj) {
		if(err) return next(err)
		res.send(obj)
	})
}

//@endpoint (url: /developers method: put)
DevelopersHdlr.prototype.update = function(req, res, next) { 
	var id = req.params.query.id || req.params.url.id || req.params.body.id
	var data = req.params.body


	this.model.update({_id: id}, {$set: data}, function(err, affected) {
		if(err) return next(err)
		res.send({success: true, affected_documents: affected})
	)
}

//@endpoint (url: /developers method: get)
DevelopersHdlr.prototype.getDetails = function(req, res, next) { }

