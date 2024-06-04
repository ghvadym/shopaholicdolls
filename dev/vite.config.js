import {defineConfig} from 'vite';
import fs from 'fs';
import path from 'path';


/**
 * Generates the input paths for the given styles/scripts directory.
 *
 * @param {string} stylesDir - The directory containing the styles files.
 * @return {Object} An object containing the input paths for each base name.
 */

function generateInputPaths(stylesDir) {
    const inputPaths = [];
    const files = fs.readdirSync(stylesDir);

    files.forEach((file) => {
        // Ignore component files
        if (file.startsWith('_')) {
            return;
        }

        const filePath = path.join(stylesDir, file);
        const ext = path.extname(file);
        const baseName = path.basename(file, ext);

        if (ext === '.scss' || ext === '.sass' || ext === '.js') {
            const existingPath = inputPaths.find((item) => item.name === baseName);

            if (existingPath) {
                existingPath.paths.push(filePath);
            } else {
                inputPaths.push( filePath );
            }
        }
    });

    return inputPaths;
}

let scssFiles = generateInputPaths('../assets/scss');

let jsFiles = generateInputPaths('../assets/js');

let inputs = [
    ...scssFiles,
    ...jsFiles
];

export default defineConfig({
    build: {
        outDir: '../dest',
        rollupOptions: {
            input: inputs,
            output: {
                assetFileNames: 'css/[name][extname]', //for styles
                entryFileNames: 'js/[name].js', //for scripts
                //chunkFileNames: 'js/chunks/[name].js', //for common modules that are used in 2 or more js files
            },
        },
    },
    css: {
        preprocessorOptions: {
            scss: {
                additionalData: '',
            },
        },
    },
    plugins: [],
});
