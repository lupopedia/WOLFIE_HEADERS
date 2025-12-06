const Tracker = require('./index');
const fs = require('fs');
const path = require('path');

// Check if docs folder exists, otherwise use current directory
const testPath = fs.existsSync('./docs') ? './docs' : '.';
const tracker = new Tracker(testPath);
const data = tracker.scan();

// Access tracked data
console.log('📊 Tracking Results:');
console.log('   Collections:', Object.keys(data.collections).length, 'unique');
console.log('   Tags:', Object.keys(data.tags).length, 'unique');
console.log('   Files:', Object.keys(data.files).length, 'files tracked');
console.log('   Channels:', Object.keys(data.channels).length, 'channels found');
console.log('   Light Counts:', Object.keys(data.lightCounts).length, 'files with Counting in Light');
console.log('   Invalid Files:', data.invalidFiles.length, 'files with validation errors');

// Show resonance levels
const resonanceLevels = {};
Object.values(data.lightCounts).forEach(light => {
  const level = light.resonanceLevel || 'Unknown';
  resonanceLevels[level] = (resonanceLevels[level] || 0) + 1;
});
if (Object.keys(resonanceLevels).length > 0) {
  console.log('\n   Resonance Levels:', resonanceLevels);
}

// Export report
const report = tracker.getReport();
fs.writeFileSync('tracked_data.json', report);
console.log('\n✅ Report saved to tracked_data.json');

// Show sample invalid files (if any)
if (data.invalidFiles.length > 0) {
  console.log('\n⚠️  Sample Invalid Files:');
  data.invalidFiles.slice(0, 5).forEach(({ file, errors }) => {
    console.log(`   ${file}: ${errors[0]}`);
  });
}

// Show top collections and tags
if (Object.keys(data.collections).length > 0) {
  console.log('\n📋 Top Collections:');
  Object.entries(data.collections)
    .sort((a, b) => b[1] - a[1])
    .slice(0, 5)
    .forEach(([col, count]) => console.log(`   - ${col}: ${count}`));
}

if (Object.keys(data.tags).length > 0) {
  console.log('\n🏷️  Top Tags:');
  Object.entries(data.tags)
    .sort((a, b) => b[1] - a[1])
    .slice(0, 5)
    .forEach(([tag, count]) => console.log(`   - ${tag}: ${count}`));
}

