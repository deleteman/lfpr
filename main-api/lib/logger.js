var winston = require("winston"),
	config = require("config");


var logger = new (winston.Logger)( {
	transports: [
		new (winston.transports.Console)({
			level: config.get('logger.level'),
		    timestamp: () => {
		      return new Date();
		    },
		    formatter: (options) => {
		      return options.timestamp() +' ['+ options.level.toUpperCase() +'] '+ (undefined !== options.message ? options.message : '') +
		        (options.meta && Object.keys(options.meta).length ? '\n\t'+ JSON.stringify(options.meta) : '' );
		    }
	    }),
	]
})

module.exports = logger;