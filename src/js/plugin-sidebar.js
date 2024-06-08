( function( wp ) {
    console.log( 'Hello World' );
	const { registerPlugin } = wp.plugins
	const { PluginSidebar } = wp.editPost

	registerPlugin('spf-sidebar', {
		render: function() {
			return (
				<PluginSidebar name="spf-sidebar" title="SEO">
					hello world
				</PluginSidebar>
			)
		}
	} )

})(window.wp)