#!/usr/bin/env node

const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

console.log('🚀 Building WoomagOne Theme...\n');

// Ensure directories exist
const assetsDir = path.join(__dirname, 'assets');
const assetsCssDir = path.join(__dirname, 'assets/css');
const assetsJsDir = path.join(__dirname, 'assets/js');

if (!fs.existsSync(assetsDir)) {
    fs.mkdirSync(assetsDir);
    console.log('✅ Created assets directory');
}

if (!fs.existsSync(assetsCssDir)) {
    fs.mkdirSync(assetsCssDir);
    console.log('✅ Created assets/css directory');
}

if (!fs.existsSync(assetsJsDir)) {
    fs.mkdirSync(assetsJsDir);
    console.log('✅ Created assets/js directory');
}

try {
    // Run Webpack build
    console.log('📦 Running Webpack build...');
    execSync('npx webpack --mode production', { stdio: 'inherit' });

    // Verify files were created
    const cssFile = path.join(__dirname, 'assets/css/style.css');
    const jsFile = path.join(__dirname, 'assets/js/main.js');

    if (fs.existsSync(cssFile)) {
        const cssSize = fs.statSync(cssFile).size;
        console.log(`✅ CSS file created (${cssSize} bytes)`);

        // Check if CSS contains Tailwind classes
        const cssContent = fs.readFileSync(cssFile, 'utf8');
        if (cssContent.includes('tailwind') || cssContent.includes('prose') || cssSize > 10000) {
            console.log('✅ CSS appears to contain Tailwind styles');
        } else {
            console.log('⚠️  CSS file might not contain Tailwind styles');
        }
    } else {
        console.log('❌ CSS file was not created');
    }

    if (fs.existsSync(jsFile)) {
        const jsSize = fs.statSync(jsFile).size;
        console.log(`✅ JS file created (${jsSize} bytes)`);
    } else {
        console.log('❌ JS file was not created');
    }

    console.log('\n🎉 Build completed successfully!');
    console.log('👉 Now activate the theme in WordPress admin');

} catch (error) {
    console.error('❌ Build failed:', error.message);
    process.exit(1);
}