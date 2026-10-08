import js from '@eslint/js';
import globals from 'globals';
import { defineConfig, globalIgnores } from 'eslint/config';
import eslintConfigPrettier from 'eslint-config-prettier/flat';

export default defineConfig([
    globalIgnores(['vendor/**', 'public/build/**', 'storage/**']),
    {
        files: ['**/*.{js,mjs,cjs}'],
        plugins: { js },
        extends: ['js/recommended'],
        languageOptions: { globals: globals.browser },
        rules: {
            'prefer-const': 'error',
        },
    },
    eslintConfigPrettier,
]);
