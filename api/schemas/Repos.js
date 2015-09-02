
module.exports = function(mongoose) {
	var Schema = mongoose.Schema


	var SchemaObj = new Schema({
		name: String,
		url: String,
		description: String,
		owner: { type: Schema.Types.ObjectId, ref: 'Developers' },
		stars: Number,
		forks: Number,
		last_update: Date,
		language: String,
		published: Boolean,
		open_issues: Number,
		closed_issues: Number,
		readme: String,
		pr_acceptance_rate: Number
	})

	return mongoose.model('Repos', SchemaObj)
}