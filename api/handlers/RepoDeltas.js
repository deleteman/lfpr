

module.exports = RepoDeltasHdlr;
function RepoDeltasHdlr(_model) { this.model = _model }
@endpoint (url: /repodeltas method: post)
RepoDeltasHdlr.prototype.newDelta = function(req, res, next) { var data = req.params.body
//...maybe do validation here?
this.model.create(data, function(err, obj) {
	if(err) return next(err)
	res.send(obj)
})
}

@endpoint (url: /repodeltas method: get)
RepoDeltasHdlr.prototype.getDeltas = function(req, res, next) { }

