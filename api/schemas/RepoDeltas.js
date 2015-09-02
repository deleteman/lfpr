
module.exports = function(mongoose) {
	var Schema = mongoose.Schema


	var SchemaObj = new Schema({
		sample_date: Date,
		stars: Number,
		delta_stars: Number,
		forks: Number,
		delta_forks: Number,
		commits_count: Number,
		new_pulls: Number,
		closed_pulls: Number,
		merged_pulls: Number,
		open_issues: Number,
		closed_issues: Number,
		repo: { type: Schema.Types.ObjectId, ref: 'Repos' }
	})

	return mongoose.model('RepoDeltas', SchemaObj)
}