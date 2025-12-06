const Tracker = require('./index');
const fs = require('fs');

const tracker = new Tracker('.');
tracker.scan();

// List duplicates/orphans
const readmeFiles = Object.keys(tracker.trackedData.files).filter(f => f.includes('README.md'));
const orphanTags = Object.entries(tracker.trackedData.tags).filter(([_, count]) => count === 1);

console.log('📊 Organization Report:');
console.log(`   - Duplicate READMEs: ${readmeFiles.length} found`);
readmeFiles.forEach(f => console.log(`     - ${f}`));

console.log(`\n   - Orphan Tags (used only once): ${orphanTags.length} found`);
orphanTags.slice(0, 10).forEach(([tag, count]) => console.log(`     - ${tag} (${count} use)`));

console.log('\n💡 Suggestions:');
console.log('   - Consolidate files with similar summaries');
console.log('   - Review orphan tags for consolidation opportunities');
console.log('   - Group related files by channel');

// Save report
fs.writeFileSync('organization_report.json', JSON.stringify({
  readmeFiles,
  orphanTags: orphanTags.map(([tag]) => tag),
  suggestions: [
    'Consolidate duplicate README.md files',
    'Review orphan tags for consolidation',
    'Group files by channel for better organization'
  ]
}, null, 2));

console.log('\n✅ Report saved to organization_report.json');

