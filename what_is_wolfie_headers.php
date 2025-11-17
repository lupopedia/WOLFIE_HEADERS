<?php
/**
 * what_is_wolfie_headers.php
 * 
 * Information page about WOLFIE Headers system
 * Version: 2.0.0
 * Last Modified: 2025-01-27
 * 
 * Displays overview, features, and documentation links for WOLFIE Headers
 */

// Version information
$version = "2.0.0";
$version_previous = "1.4.2";
$release_date = "2025-01-27";
$github_url = "https://github.com/lupopedia/WOLFIE_HEADERS";
$license = "Dual GPL v3.0 + Apache 2.0";
$maintainer = "Captain WOLFIE (Eric Robin Gerdes)";

// Required by
$required_by = "LUPOPEDIA_PLATFORM 1.0.0";

// Breaking changes
$breaking_changes = [
    "10-Section Format: WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER, TAGS",
    "Required Fields: agent_id, channel_number (000-999), version: 2.0.0",
    "Agent System Integration: Enhanced integration with LUPOPEDIA agent system",
    "Channel Architecture: Support for 1000 channels (000-999)",
    "Deprecated: HELP collection (use OTHER or WHO instead)",
    "Stricter Validation: Missing required fields cause errors"
];

// New collections
$new_collections = [
    "DO" => "Action items, tasks, to-do lists",
    "HACK" => "Workarounds, temporary solutions, quick fixes",
    "OTHER" => "Miscellaneous content that doesn't fit other categories",
    "TAGS" => "Categorization labels (also exists as YAML field)"
];

// Documentation files
$docs = [
    "10-Section Format Guide" => "docs/10_SECTION_FORMAT_GUIDE.md",
    "Migration Guide" => "docs/MIGRATION_1.4.2_TO_2.0.0.md",
    "Breaking Changes" => "docs/BREAKING_CHANGES_2.0.0.md",
    "Compatibility Matrix" => "docs/COMPATIBILITY_MATRIX.md",
    "Validation Rules" => "docs/VALIDATION_RULES_2.0.0.md",
    "Quick Start Guide" => "docs/QUICK_START_GUIDE.md",
    "System Overview" => "docs/WOLFIE_HEADER_SYSTEM_OVERVIEW.md"
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>What is WOLFIE Headers? - Version <?php echo htmlspecialchars($version); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #0066cc;
            padding-bottom: 10px;
        }
        h2 {
            color: #0066cc;
            margin-top: 30px;
        }
        h3 {
            color: #555;
            margin-top: 20px;
        }
        .version-badge {
            display: inline-block;
            background-color: #0066cc;
            color: white;
            padding: 5px 15px;
            border-radius: 4px;
            font-weight: bold;
            margin-left: 10px;
        }
        .breaking {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 15px 0;
        }
        .info-box {
            background-color: #e7f3ff;
            border-left: 4px solid #0066cc;
            padding: 15px;
            margin: 15px 0;
        }
        ul {
            margin: 10px 0;
        }
        li {
            margin: 5px 0;
        }
        .collection-list {
            list-style: none;
            padding-left: 0;
        }
        .collection-list li {
            padding: 8px;
            margin: 5px 0;
            background-color: #f8f9fa;
            border-left: 3px solid #0066cc;
        }
        .collection-list strong {
            color: #0066cc;
        }
        .doc-links {
            list-style: none;
            padding-left: 0;
        }
        .doc-links li {
            padding: 8px;
            margin: 5px 0;
            background-color: #f8f9fa;
        }
        .doc-links a {
            color: #0066cc;
            text-decoration: none;
            font-weight: bold;
        }
        .doc-links a:hover {
            text-decoration: underline;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>What is WOLFIE Headers? <span class="version-badge">v<?php echo htmlspecialchars($version); ?></span></h1>
        
        <div class="info-box">
            <strong>Current Version:</strong> v<?php echo htmlspecialchars($version); ?> (Released <?php echo htmlspecialchars($release_date); ?>)<br>
            <strong>Previous Version:</strong> v<?php echo htmlspecialchars($version_previous); ?> (Legacy - compatible with LUPOPEDIA_PLATFORM v0.0.8 and earlier)<br>
            <strong>Required By:</strong> <?php echo htmlspecialchars($required_by); ?><br>
            <strong>License:</strong> <?php echo htmlspecialchars($license); ?><br>
            <strong>Maintainer:</strong> <?php echo htmlspecialchars($maintainer); ?><br>
            <strong>GitHub:</strong> <a href="<?php echo htmlspecialchars($github_url); ?>" target="_blank"><?php echo htmlspecialchars($github_url); ?></a>
        </div>

        <h2>Overview</h2>
        <p>
            WOLFIE Headers is the metadata system that powers LUPOPEDIA's documentation layer. It replaces bulky legacy headers with concise YAML frontmatter plus channel-aware ontology lookups so humans and AI agents read the same files with the right context.
        </p>

        <h2>Key Features</h2>
        <ul>
            <li><strong>YAML Frontmatter:</strong> Lightweight metadata at the top of each Markdown file</li>
            <li><strong>Channel Architecture:</strong> Documentation organized by channels (1_wolfie, 2_database, etc.)</li>
            <li><strong>Agent Overlays:</strong> Persona-specific vocab without copying files</li>
            <li><strong>Source-of-Truth References:</strong> Centralized tag/collection definitions</li>
            <li><strong>Fallback Philosophy:</strong> "Always works" design - graceful fallback to base definitions</li>
            <li><strong>10-Section Format:</strong> Standardized collections (WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER, TAGS)</li>
        </ul>

        <div class="breaking">
            <h3>⚠️ Breaking Changes in v<?php echo htmlspecialchars($version); ?></h3>
            <p><strong>v<?php echo htmlspecialchars($version); ?> is NOT backward compatible with v<?php echo htmlspecialchars($version_previous); ?>.</strong> All v<?php echo htmlspecialchars($version_previous); ?> headers must be migrated.</p>
            <ul>
                <?php foreach ($breaking_changes as $change): ?>
                    <li><?php echo htmlspecialchars($change); ?></li>
                <?php endforeach; ?>
            </ul>
            <p><strong>Migration Required:</strong> See <a href="docs/MIGRATION_1.4.2_TO_2.0.0.md">Migration Guide</a> for step-by-step instructions.</p>
        </div>

        <h2>New Collections in v<?php echo htmlspecialchars($version); ?></h2>
        <p>The following collections are new in v<?php echo htmlspecialchars($version); ?>:</p>
        <ul class="collection-list">
            <?php foreach ($new_collections as $collection => $description): ?>
                <li><strong><?php echo htmlspecialchars($collection); ?>:</strong> <?php echo htmlspecialchars($description); ?></li>
            <?php endforeach; ?>
        </ul>

        <h2>10-Section Format</h2>
        <p>All collections must be from the 10-section set:</p>
        <ul>
            <li><strong>WHO</strong> - People, agents, teams, organizations</li>
            <li><strong>WHAT</strong> - Description of the document/component</li>
            <li><strong>WHERE</strong> - Locations, paths, environments</li>
            <li><strong>WHEN</strong> - Timelines, dates, schedules</li>
            <li><strong>WHY</strong> - Purpose, rationale, motivation</li>
            <li><strong>HOW</strong> - Implementation, workflows, procedures</li>
            <li><strong>DO</strong> - Action items, tasks, to-do lists (NEW)</li>
            <li><strong>HACK</strong> - Workarounds, temporary solutions (NEW)</li>
            <li><strong>OTHER</strong> - Miscellaneous content (NEW)</li>
            <li><strong>TAGS</strong> - Categorization labels (NEW as collection)</li>
        </ul>

        <h2>Required Fields (v<?php echo htmlspecialchars($version); ?>)</h2>
        <p>The following fields are <strong>REQUIRED</strong> in v<?php echo htmlspecialchars($version); ?> headers:</p>
        <ul>
            <li><code>agent_id</code> - Agent identifier (e.g., "008" for WOLFIE)</li>
            <li><code>channel_number</code> - Channel number as zero-padded string (000-999)</li>
            <li><code>version</code> - Header format version (must be "<?php echo htmlspecialchars($version); ?>")</li>
            <li><code>title</code> - Document title</li>
            <li><code>date_created</code> - Creation date (YYYY-MM-DD)</li>
            <li><code>last_modified</code> - Last modification date (YYYY-MM-DD)</li>
            <li><code>status</code> - Document status</li>
            <li><code>onchannel</code> - Channel number (integer, must match channel_number)</li>
            <li><code>tags</code> - Array of tags (at least one required)</li>
            <li><code>collections</code> - Array of collections (at least one required, must be from 10-section set)</li>
        </ul>

        <h2>Documentation</h2>
        <p>Complete documentation is available in the following files:</p>
        <ul class="doc-links">
            <?php foreach ($docs as $title => $file): ?>
                <li><a href="<?php echo htmlspecialchars($file); ?>"><?php echo htmlspecialchars($title); ?></a></li>
            <?php endforeach; ?>
        </ul>

        <h2>Quick Start</h2>
        <ol>
            <li>Copy the template in <code>templates/header_template.yaml</code> to the top of any Markdown file</li>
            <li>Pick <code>tags</code> and <code>collections</code> from <code>docs/TAGS_REFERENCE.md</code> and <code>docs/CHANNELS_REFERENCE.md</code></li>
            <li><strong>v<?php echo htmlspecialchars($version); ?> Required:</strong> Add <code>agent_id</code>, <code>channel_number</code> (zero-padded), and <code>version: <?php echo htmlspecialchars($version); ?></code></li>
            <li>List the major sections in <code>in_this_file_we_have</code> so parsers can auto-build a table of contents</li>
            <li>Save the file inside the appropriate channel directory</li>
            <li>Optional: Run the validation checklist in <code>docs/QUICK_START_GUIDE.md</code></li>
        </ol>

        <h2>Dependency Chain</h2>
        <p>WOLFIE Headers is a required dependency for LUPOPEDIA_PLATFORM:</p>
        <pre style="background-color: #f8f9fa; padding: 15px; border-radius: 4px;">
Crafty Syntax Live Help 3.8.0 (Foundation)
    ↓
    └─> WOLFIE Headers <?php echo htmlspecialchars($version); ?> (REQUIRED - separate package)
        GitHub: <?php echo htmlspecialchars($github_url); ?>
        Current: v<?php echo htmlspecialchars($version); ?>
        ↓
        └─> LUPOPEDIA_PLATFORM 1.0.0 (Layer 1)
            Requires: WOLFIE Headers <?php echo htmlspecialchars($version); ?>
            ↓
            └─> Agent System (Layer 2)
                Channels: 000-999 (1000 channels)
        </pre>

        <div class="footer">
            <p><strong>WOLFIE Headers v<?php echo htmlspecialchars($version); ?></strong></p>
            <p>© <?php echo date('Y'); ?> Eric Robin Gerdes / LUPOPEDIA LLC — Dual licensed under GPL v3.0 + Apache 2.0.</p>
            <p>Last Updated: <?php echo htmlspecialchars($release_date); ?></p>
        </div>
    </div>
</body>
</html>

