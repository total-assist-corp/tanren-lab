const archiver = require('archiver');
const fs = require('fs');
const packageJson = require('./package.json');

const version = packageJson.version;
const outputFileName = `tanren-lab-theme_${version}.zip`;

const output = fs.createWriteStream(outputFileName);
const archive = archiver('zip', {
  zlib: { level: 9 }
});

output.on('close', function () {
  console.log(archive.pointer() + ' total bytes');
  console.log('Archiver has been finalized and the output file descriptor has closed.');
});

archive.on('warning', function (err) {
  if (err.code === 'ENOENT') {
    console.warn(err);
  } else {
    throw err;
  }
});

archive.on('error', function (err) {
  throw err;
});

// 対象のフォルダやファイルをアーカイブに追加
const itemsToArchive = [
  'dist',
  'parts',
  'templates',
  'functions.php',
  'index.php',
  'LICENSE',
  'package.json',
  'screenshot.png',
  'style.css',
  'theme.json'
];

itemsToArchive.forEach(item => {
  const isDirectory = fs.statSync(item).isDirectory();
  if (isDirectory) {
    archive.directory(item, item);
  } else {
    archive.file(item, { name: item });
  }
});

// アーカイブを作成
archive.pipe(output);
archive.finalize();
