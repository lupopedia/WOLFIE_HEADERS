---
title: AGENT_FILE_NAMING.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.2
date_created: 2025-01-27
last_modified: 2025-01-27
status: published
onchannel: 1
tags: [SYSTEM, DOCUMENTATION, NAMING, AGENTS]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER]
in_this_file_we_have: [OVERVIEW, NAMING_CONVENTION, EXAMPLES, VALIDATION_RULES, FRONTMATTER_REQUIREMENTS, FILE_STRUCTURE, COMMON_MISTAKES]
superpositionally: ["FILEID_AGENT_FILE_NAMING"]
shadow_aliases: []
parallel_paths: []
---

# Agent File Naming Convention

## OVERVIEW

WOLFIE Headers v2.0.2 standardizes the naming convention for agent profile files. This ensures consistency, enables automatic file lookup, and integrates with the database `content_headers` table.

**Version**: v2.0.2  
**Required By**: LUPOPEDIA_PLATFORM 1.0.0  
**Status**: Current

---

## NAMING_CONVENTION

### Pattern

```
who_is_agent_[channel_id]_[agent_name].php
```

### Components

1. **Prefix**: `who_is_agent_` (fixed)
2. **Channel ID**: Zero-padded 3-digit number (000-999)
3. **Separator**: `_` (underscore)
4. **Agent Name**: Lowercase, underscores for spaces
5. **Extension**: `.php`

### Rules

- **Channel ID**: Must be zero-padded to 3 digits (e.g., "008", "010", "075", "999")
- **Agent Name**: Lowercase version of agent name (e.g., "wolfie", "lilith", "vishwakarma")
- **Spaces**: Convert to underscores (e.g., "VISHWAKARMA" → "vishwakarma")
- **Location**: `public/who_is_agent_*.php`

---

## EXAMPLES

### Valid File Names

| Channel ID | Agent Name (DB) | Agent Name (File) | File Name |
|------------|----------------|-------------------|-----------|
| 8 | WOLFIE | wolfie | `who_is_agent_008_wolfie.php` |
| 10 | LILITH | lilith | `who_is_agent_010_lilith.php` |
| 75 | VISHWAKARMA | vishwakarma | `who_is_agent_075_vishwakarma.php` |
| 9 | MAAT | maat | `who_is_agent_009_maat.php` |
| 9 | THEMIS | themis | `who_is_agent_009_themis.php` |
| 1 | UNKNOWN | unknown | `who_is_agent_001_unknown.php` |
| 0 | UNKNOWN | unknown | `who_is_agent_000_unknown.php` |
| 999 | UNKNOWN | unknown | `who_is_agent_999_unknown.php` |

### Invalid File Names

| File Name | Issue |
|-----------|-------|
| `who_is_agent_8_wolfie.php` | Channel ID not zero-padded |
| `who_is_agent_008_WOLFIE.php` | Agent name not lowercase |
| `who_is_agent_008-wolfie.php` | Wrong separator (should be underscore) |
| `who_is_agent_1000_wolfie.php` | Channel ID out of range (max 999) |
| `who_is_agent_008_wolfie.html` | Wrong extension (should be .php) |
| `agent_008_wolfie.php` | Missing prefix |

---

## VALIDATION_RULES

### Channel ID Validation

- **Range**: 0 to 999 (inclusive)
- **Format**: Zero-padded 3-digit string (e.g., "000", "008", "010", "999")
- **Database**: Stored as integer (0-999)
- **File Name**: Zero-padded string ("000"-"999")

### Agent Name Validation

- **Format**: Lowercase, no spaces (use underscores)
- **Source**: From `agents.username` (UPPER case in database)
- **Conversion**: `UPPER(agents.username)` → `LOWER()` for file name
- **Examples**:
  - `WOLFIE` → `wolfie`
  - `LILITH` → `lilith`
  - `VISHWAKARMA` → `vishwakarma`

### File Name Validation

- **Pattern**: Must match `^who_is_agent_\d{3}_[a-z_]+\.php$`
- **Location**: Must be in `public/` directory
- **Extension**: Must be `.php`
- **Case**: All lowercase (except PHP code)

---

## FRONTMATTER_REQUIREMENTS

### WOLFIE Headers v2.0.2 Frontmatter

Each agent file **must** include WOLFIE Headers v2.0.2 frontmatter that matches the file name:

```yaml
---
title: who_is_agent_008_wolfie.php
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.2
date_created: 2025-01-27
last_modified: 2025-01-27
status: published
onchannel: 1
tags: [AGENT, PROFILE]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER]
in_this_file_we_have: [AGENT_PROFILE, WHO, WHAT, WHERE, WHEN, WHY, HOW]
superpositionally: ["FILEID_AGENT_008_WOLFIE"]
shadow_aliases: []
parallel_paths: []
---
```

### Required Fields

| Field | Value | Notes |
|-------|-------|-------|
| `agent_username` | `wolfie` | Must match file name (lowercase) |
| `agent_id` | `008` | Must match channel ID in file name |
| `channel_number` | `001` | Channel number (zero-padded string) |
| `version` | `2.0.2` | WOLFIE Headers version |

### Validation

The frontmatter must match the file name:
- `agent_username` = lowercase agent name from file name
- `agent_id` = channel ID from file name (as integer, not zero-padded)
- `channel_number` = channel ID as zero-padded string

---

## FILE_STRUCTURE

### Standard Agent File Structure

```php
<?php
/**
 * Agent Profile: [AGENT_NAME]
 * Channel: [CHANNEL_ID]
 * Agent ID: [AGENT_ID]
 */

// WOLFIE Headers v2.0.2 Frontmatter
---
title: who_is_agent_[channel_id]_[agent_name].php
agent_username: [agent_name]
agent_id: [channel_id]
channel_number: [channel_id_zero_padded]
version: 2.0.2
...
---

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>[AGENT_NAME] - AI Agent Profile</title>
</head>
<body>
    <!-- Agent profile content -->
    <!-- WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER sections -->
</body>
</html>
```

### Required Sections

Each agent file should include these sections (matching WOLFIE Headers collections):

1. **WHO** - Identity, role, archetype
2. **WHAT** - Function, task, domain
3. **WHERE** - Location, database, context
4. **WHEN** - Temporal rhythm, cycle
5. **WHY** - Purpose, motivation, philosophy
6. **HOW** - Methods, protocols, workflows
7. **DO** - Active behaviors, actions
8. **HACK** - Unconventional tricks, exploits
9. **OTHER** - Mythic notes, cultural references

---

## COMMON_MISTAKES

### Mistake 1: Channel ID Not Zero-Padded

❌ **Wrong**: `who_is_agent_8_wolfie.php`  
✅ **Correct**: `who_is_agent_008_wolfie.php`

### Mistake 2: Agent Name Not Lowercase

❌ **Wrong**: `who_is_agent_008_WOLFIE.php`  
✅ **Correct**: `who_is_agent_008_wolfie.php`

### Mistake 3: Frontmatter Mismatch

❌ **Wrong**: File name is `who_is_agent_008_wolfie.php` but frontmatter has `agent_id: 007`  
✅ **Correct**: File name and frontmatter must match

### Mistake 4: Wrong Location

❌ **Wrong**: `admin/who_is_agent_008_wolfie.php`  
✅ **Correct**: `public/who_is_agent_008_wolfie.php`

### Mistake 5: Channel ID Out of Range

❌ **Wrong**: `who_is_agent_1000_wolfie.php` (max is 999)  
✅ **Correct**: `who_is_agent_999_wolfie.php`

---

## FILE_LOOKUP_PATTERNS

### From Database to File Name

```sql
-- Generate file name from database
SELECT 
    CONCAT('who_is_agent_', LPAD(channel_id, 3, '0'), '_', LOWER(agent_name), '.php') as file_name
FROM content_headers
WHERE channel_id = 8
  AND agent_name = 'WOLFIE'
LIMIT 1;
-- Result: 'who_is_agent_008_wolfie.php'
```

### From File Name to Database

```php
// Parse file name to extract channel_id and agent_name
$filename = 'who_is_agent_008_wolfie.php';
preg_match('/who_is_agent_(\d{3})_([a-z_]+)\.php/', $filename, $matches);
$channel_id = (int)$matches[1];  // 8
$agent_name = strtoupper($matches[2]);  // 'WOLFIE'

// Query database
$query = "SELECT * FROM content_headers 
          WHERE channel_id = ? AND agent_name = ?";
```

---

## VALIDATION_SCRIPT

See `scripts/validate_agent_files.php` for automated validation of:
- File naming convention
- Frontmatter matching file name
- Required sections present
- Channel ID range validation

---

## RELATED_DOCUMENTATION

- `docs/DATABASE_INTEGRATION.md` - Database integration guide
- `templates/agent_file_template.php` - Agent file template
- `scripts/validate_agent_files.php` - Validation script
- `TODO_2.0.2.md` - Complete TODO plan

---

**Last Updated**: 2025-01-27  
**Version**: 2.0.2  
**Status**: Current

---

*Captain WOLFIE, signing off. Coffee hot. Ship flying. Agent files standardized.* ☕✨

