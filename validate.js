const Tracker = require('./index');

const tracker = new Tracker(process.argv[2] || '.');
const data = tracker.scan();

let valid = true;

// Check for invalid files (caught by validateHeader)
if (data.invalidFiles.length > 0) {
  console.error('\n🚨 VALIDATION ISSUES FOUND:');
  data.invalidFiles.forEach(({ file, errors }) => {
    console.error(` ${file}:`);
    errors.forEach(err => {
      // Only fail on critical errors (not warnings for legacy files)
      if (!err.includes('warning only') && !err.includes('warning for legacy')) {
        valid = false;
        console.error(`   ❌ ${err}`);
      } else {
        console.warn(`   ⚠️  ${err}`);
      }
    });
  });
}

// Check for files missing Counting in Light fields
const totalFiles = Object.keys(data.files).length;
const filesWithLight = Object.keys(data.lightCounts).length;
if (filesWithLight < totalFiles) {
  console.warn(`\n⚠️  ${totalFiles - filesWithLight} files missing Counting in Light fields`);
  // List them
  const missingLight = Object.keys(data.files).filter(f => !data.lightCounts[f]);
  if (missingLight.length <= 10) {
    missingLight.forEach(f => console.warn(`   - ${f}`));
  } else {
    missingLight.slice(0, 10).forEach(f => console.warn(`   - ${f}`));
    console.warn(`   ... and ${missingLight.length - 10} more`);
  }
}

// Summary
if (valid) {
  console.log('\n✅ VALIDATION PASSED');
  console.log(`   - ${filesWithLight} files with valid Counting in Light fields`);
  console.log(`   - ${Object.keys(data.collections).length} unique collections tracked`);
  console.log(`   - ${Object.keys(data.tags).length} unique tags tracked`);
  console.log(`   - ${Object.keys(data.channels).length} channels found`);
  if (data.invalidFiles.length > 0) {
    console.log(`   - ${data.invalidFiles.length} files with warnings (legacy files OK)`);
  }
} else {
  console.error(`\n❌ VALIDATION FAILED: ${data.invalidFiles.length} invalid file(s) found`);
}

process.exit(valid ? 0 : 1); // For CI/CD

