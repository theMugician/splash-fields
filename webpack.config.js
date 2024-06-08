const path = require('path');

module.exports = {
    entry: './src/js/plugin-sidebar.js', // Entry point of your JavaScript
    output: {
        path: path.resolve(__dirname, 'assets/js'), // Output directory
        filename: 'plugin-sidebar.js' // Output filename
    },
    module: {
        rules: [
            {
                test: /\.js$/, // Apply this rule to JavaScript files
                exclude: /node_modules/, // Exclude node_modules directory
                use: {
                    loader: 'babel-loader', // Use Babel loader
                    options: {
                        presets: ['@babel/preset-env', '@babel/preset-react', '@wordpress/babel-preset-default'] // Presets for Babel
                    }
                }
            },
            {
                test: /\.css$/, // Apply this rule to CSS files
                use: ['style-loader', 'css-loader'] // Use style-loader and css-loader
            }
        ]
    },
    resolve: {
        alias: {
            wp: path.resolve(__dirname, 'path-to-wordpress'), // Replace with the actual path to WordPress
        },
    },
    externals: {
        'react': 'React',
        'react-dom': 'ReactDOM',
        '@wordpress/blocks': ['wp', 'blocks'],
        '@wordpress/i18n': ['wp', 'i18n'],
        '@wordpress/element': ['wp', 'element'],
        '@wordpress/components': ['wp', 'components'],
        '@wordpress/data': ['wp', 'data'],
        '@wordpress/plugins': ['wp', 'plugins'],
        '@wordpress/edit-post': ['wp', 'editPost'],
    }
};
