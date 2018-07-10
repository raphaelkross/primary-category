var path = require('path');

module.exports = {
    // Change to your "entry-point".
    entry: './assets/js/admin/admin',
    output: {
        path: path.resolve(__dirname, 'dist/js/'),
        filename: 'admin.bundle.js'
    },
    resolve: {
        extensions: ['.ts', '.tsx', '.js', '.json']
    },
    module: {
        rules: [{
            // Include ts, tsx, and js files.
            test: /\.(tsx?)|(js)$/,
            exclude: /node_modules/,
            loader: 'awesome-typescript-loader',
        }],
    }
};
