const fs = require('fs');
const path = require('path');
const yaml = require('js-yaml');
const glob = require('glob');

const STANDARD_COLORS = ['000000', 'FFFFFF', 'FF0000', '00FF00', 'FFD700', '00BFFF', '4169E1', '9370DB', 'FFA500', 'DC143C'];

// Main class for tracking
class WolfieHeadersTracker {
  constructor(folderPath = '.') {
    if (!folderPath || !fs.existsSync(folderPath)) {
      throw new Error('Invalid folder path');
    }
    this.folderPath = folderPath;
    this.trackedData = { 
      collections: {}, 
      tags: {}, 
      files: {}, 
      channels: {}, 
      lightCounts: {},
      invalidFiles: []
    };
  }

  // Validate header against 2.9.2 requirements
  validateHeader(yamlData) {
    const errors = [];
    
    // Check version (warn mode for legacy files - don't fail on 2.9.0/2.8.x)
    const version = yamlData['wolfie.headers.version'] || yamlData['wolfie.headers']?.version;
    if (version && version !== '2.9.2') {
      // Warn but don't fail - legacy files may have older versions
      errors.push(`Version mismatch: expected 2.9.2, got ${version} (warning only)`);
    }
    
    // Check context.what.parent/child (required for 2.9.2, but warn mode for legacy)
    if (!yamlData.context?.what?.parent || !yamlData.context?.what?.child) {
      errors.push('Missing required context.what.parent or context.what.child (warning for legacy files)');
    }
    
    // Check light.count structure
    if (!yamlData.light?.count) {
      errors.push('Missing light.count structure');
    } else {
      const required = ['offset', 'base', 'name', 'mood', 'touch'];
      required.forEach(field => {
        if (yamlData.light.count[field] === undefined || yamlData.light.count[field] === null) {
          errors.push(`Missing light.count.${field}`);
        }
      });
      
      // Warn on non-standard mood colors
      if (yamlData.light.count.mood && !STANDARD_COLORS.includes(yamlData.light.count.mood)) {
        errors.push(`Non-standard mood color: ${yamlData.light.count.mood} (not in standard dictionary)`);
      }
    }
    
    return errors;
  }

  // Parse headers from various comment formats (Universal Header Schema)
  parseHeader(content) {
    // Flexible regex for different comment styles:
    // 1. Python: # ---\n# ...\n# ---
    // 2. PHP/JS: /*---\n...\n---*/
    // 3. Markdown: ---\n...\n---
    const headerMatch = content.match(/(?:^# ---(?:\n|# )([\s\S]*?)(?:\n|# )---)|(?:\/\*---\n([\s\S]*?)\n---\*\/)|(?:^---\n([\s\S]*?)\n---)/m);
    if (!headerMatch) return null;
    
    // Extract YAML text from whichever capture group matched
    const yamlText = headerMatch[1] || headerMatch[2] || headerMatch[3];
    if (!yamlText) return null;
    
    // Clean up comment markers if present (for Python/PHP)
    const cleanedYaml = yamlText.replace(/^# /gm, '').trim();
    
    try {
      return { yamlData: yaml.load(cleanedYaml), yamlText: yamlText };
    } catch (e) {
      return null; // Invalid YAML
    }
  }

  // Scan all files in folder and parse headers from comments or YAML frontmatter
  scan() {
    // Support multiple file types, not just .md
    const files = glob.sync('**/*.{md,py,php,js,ts}', {
      cwd: this.folderPath,
      ignore: ['node_modules/**', '.git/**', '**/test/**', '**/vendor/**']
    });
    
    files.forEach(file => {
      try {
        const fullPath = path.join(this.folderPath, file);
        const content = fs.readFileSync(fullPath, 'utf8');
        
        // Parse header using universal schema parser
        const headerResult = this.parseHeader(content);
        if (!headerResult) return; // Skip files without headers
        
        const yamlData = headerResult.yamlData;
        const yamlText = headerResult.yamlText;
        
        // Validate header (warn mode - don't skip tracking for legacy files)
        const errors = this.validateHeader(yamlData);
        if (errors.length > 0) {
          // Track invalid files but still process them (warn mode for legacy compatibility)
          this.trackedData.invalidFiles.push({ file, errors });
        }

        // Extract content after header block
        const headerEnd = content.indexOf('---', content.indexOf(yamlText) + yamlText.length);
        const fileContent = headerEnd > 0 ? content.slice(headerEnd + 3).trim() : content.slice(content.indexOf(yamlText) + yamlText.length).trim();

        // Track collections
        if (yamlData.collections) {
          yamlData.collections.forEach(col => {
            this.trackedData.collections[col] = (this.trackedData.collections[col] || 0) + 1;
          });
        }

        // Track tags
        if (yamlData.tags) {
          yamlData.tags.forEach(tag => {
            this.trackedData.tags[tag] = (this.trackedData.tags[tag] || 0) + 1;
          });
        }

        // Track file contents (summary via in_this_file_we_have or full text scan)
        const fileSummary = yamlData.in_this_file_we_have || fileContent.substring(0, 200) + '...'; // Fallback to snippet
        this.trackedData.files[file] = { summary: fileSummary, fullContent: fileContent };

        // Track channels
        const channel = yamlData.channel_number || yamlData.onchannel || yamlData.channel_mood;
        if (channel) {
          if (!this.trackedData.channels[channel]) {
            this.trackedData.channels[channel] = [];
          }
          this.trackedData.channels[channel].push(file);
        }

        // Track Counting in Light (with 5D resonance calculation from DIALOG.md)
        if (yamlData.light && yamlData.light.count) {
          const light = yamlData.light.count;
          // Default channel light (from docs: Purple System = 9370DB = R:147, G:112, B:219, real:777, imag:0)
          const defaultChannelLight = { R: 147, G: 112, B: 219, real: 777, imag: 0 };
          const resonance = this.calculateResonance(light, defaultChannelLight);
          
          // Resonance levels
          let resonanceLevel = 'Very Low';
          if (resonance < 50) resonanceLevel = 'High';
          else if (resonance < 100) resonanceLevel = 'Medium';
          else if (resonance < 200) resonanceLevel = 'Low';
          
          this.trackedData.lightCounts[file] = { ...light, resonance, resonanceLevel };
        }
      } catch (e) {
        // Handle malformed YAML gracefully
        console.warn(`ERROR processing ${file}: ${e.message}`);
        this.trackedData.invalidFiles.push({ file, errors: [`YAML parse error: ${e.message}`] });
      }
    });
    return this.trackedData;
  }

  // 5D resonance calculation (from DIALOG.md/TODO_2.9.0.md)
  calculateResonance(artifactLight, channelLight) {
    // Parse mood hex to RGB if available (assume hex like '9370DB')
    const hexToRgb = (hex) => {
      if (!hex || hex.length !== 6) return { R: 0, G: 0, B: 0 };
      return {
        R: parseInt(hex.substr(0, 2), 16),
        G: parseInt(hex.substr(2, 2), 16),
        B: parseInt(hex.substr(4, 2), 16)
      };
    };
    
    const artifactRgb = artifactLight.mood ? hexToRgb(artifactLight.mood) : { R: 0, G: 0, B: 0 };
    const channelRgb = channelLight ? { R: channelLight.R || 0, G: channelLight.G || 0, B: channelLight.B || 0 } : { R: 0, G: 0, B: 0 };
    
    // 5D formula: RGB distance + base/real distance + imag distance
    return Math.sqrt(
      Math.pow(artifactRgb.R - channelRgb.R, 2) +
      Math.pow(artifactRgb.G - channelRgb.G, 2) +
      Math.pow(artifactRgb.B - channelRgb.B, 2) +
      Math.pow(((artifactLight.base || 0) - (channelLight.real || 0)) / 1000, 2) +
      Math.pow((artifactLight.offset || 0) - (channelLight.imag || 0), 2) // Using offset as imag approximation
    );
  }

  // Export tracked data (e.g., for reports)
  getReport() {
    return JSON.stringify(this.trackedData, null, 2);
  }
}

module.exports = WolfieHeadersTracker;

