require("@rushstack/eslint-patch/modern-module-resolution");

// Add your "extends" boilerplate here, for example:
module.exports = {
    extends: [
        'eslint:recommended',
        'eslint-config-prettier',
        'plugin:import/recommended',
        'plugin:vue/vue3-recommended',
        'prettier',
    ],
    settings: {
        vue: {
            // Tells eslint-plugin-react to automatically detect the version of React to use.
            version: 'detect',
        },
        // Tells eslint how to resolve imports
        'import/resolver': {
            node: {
                paths: ['src'],
                extensions: ['.js', '.vue', '.ts'],
            },
        },
    },
    rules: {
        // override/add rules settings here, such as:
        // 'vue/no-unused-vars': 'error'
    },
    parserOptions: { tsconfigRootDir: __dirname }
};