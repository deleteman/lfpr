
module.exports = function(mongoose) {
	var Schema = mongoose.Schema


	var SchemaObj = new Schema({
		name: String,
		avatar_url: String,
		github_url: String,
		role: String
	})

	return mongoose.model('Developers', SchemaObj)
}