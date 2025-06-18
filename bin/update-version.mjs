import { replaceInFile } from 'replace-in-file';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

// Get the current directory equivalent to __dirname in ES modules
const __dirname = path.dirname(fileURLToPath(import.meta.url));

// Read package.json
const pkg = JSON.parse(fs.readFileSync(path.resolve(__dirname, '../package.json'), 'utf8'));

async function updateVersion() {
  try {
    // Replace the version in the PHP file header
    const phpResults = await replaceInFile({
      files: path.resolve(__dirname, `../${pkg.name}.php`),
      from: /^\s*\*\s*Version:\s*.*$/m,
      to: ` * Version:           ${pkg.version}`,
    });

    // Replace the VERSION constant in the PHP file
    const versionResults = await replaceInFile({
      files: path.resolve(__dirname, `../${pkg.name}.php`),
      from: /const VERSION = '.*';/g,
      to: `const VERSION = '${pkg.version}';`,
    });

    // Read the readme.txt file to check the current Stable tag spacing
    const readmeContent = fs.readFileSync('readme.txt', 'utf8');
    const stableTagMatch = readmeContent.match(/(Stable tag\s*:\s*)([\d.\-a-z]+)/);

    if (stableTagMatch) {
      // Calculate the number of spaces between 'Stable tag' and the version number
      const labelLength = stableTagMatch[1].length; // Length of "Stable tag :"
      const version = pkg.version; // The new version number

      // Replace the Stable tag with the correct version while preserving the space
      const readmeResults = await replaceInFile({
        files: 'readme.txt',
        from: /Stable tag\s*:\s*[\d.\-a-z]+/g,
        to: `${stableTagMatch[1]}${' '.repeat(labelLength - stableTagMatch[1].length)}${version}`,
      });

      console.log(`Version updated to ${pkg.version} in ${phpResults.length} file(s).`);
      console.log(`VERSION constant updated to ${pkg.version} in ${versionResults.length} file(s).`);
      console.log(`Stable tag updated to ${pkg.version} in ${readmeResults.length} file(s).`);
    } else {
      console.error('No Stable tag found in readme.txt');
    }

    console.log(`Version updated to ${pkg.version}.`); 

  } catch (error) {
    console.error('Error occurred while updating version:', error);
    process.exit(1);
  }
}

updateVersion();
