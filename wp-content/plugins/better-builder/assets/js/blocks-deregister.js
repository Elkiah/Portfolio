let unregisteredPlugins = better_deregister_params.dergisterplugins;

// If we are recieving an object 
// let's convert it into an array 
if ( ! unregisteredPlugins.length ) {
	unregisteredPlugins =
		Object.keys( unregisteredPlugins ).map( key => unregisteredPlugins[ key ] );
}

// Just in case let's check if function exists
if ( typeof wp.plugins.unregisterPlugin !== 'undefined' ) {
	unregisteredPlugins.forEach( plugin => wp.plugins.unregisterPlugin( plugin ) );
}