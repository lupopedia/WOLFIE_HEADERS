---
title: VALIDATION_RULES_2.0.0.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.0
date_created: 2025-01-27
last_modified: 2025-01-27
status: published
onchannel: 1
tags: [SYSTEM, DOCUMENTATION, REFERENCE]
collections: [WHAT, HOW]
in_this_file_we_have: [OVERVIEW, REQUIRED_FIELDS, FIELD_VALIDATION, COLLECTION_VALIDATION, TAG_VALIDATION, CHANNEL_VALIDATION, YAML_VALIDATION, CONTENT_VALIDATION, ERROR_MESSAGES, AUTOMATION]
superpositionally: ["FILEID_VALIDATION_RULES_2.0.0"]
---

# Validation Rules for WOLFIE Headers v2.0.0

## OVERVIEW

This document defines the validation rules for WOLFIE Headers v2.0.0 format. All headers must pass these validation checks before being accepted by LUPOPEDIA_PLATFORM 1.0.0.

**Validation Level**: Strict (errors block acceptance, warnings are informational)  
**Version**: v2.0.0  
**Status**: Required for all headers

---

## REQUIRED_FIELDS

The following fields are **REQUIRED** in v2.0.0 headers. Missing any of these fields will cause a validation error.

### Core Required Fields

| Field | Type | Format | Example | Notes |
|-------|------|--------|---------|-------|
| `title` | string | Any valid string | `"README.md"` | Document title |
| `agent_id` | string | Agent identifier | `"008"` | Agent ID (008 = WOLFIE) |
| `channel_number` | string | Zero-padded 000-999 | `"001"` | Must match `onchannel` |
| `version` | string | Must be "2.0.0" | `"2.0.0"` | Header format version |
| `date_created` | string | YYYY-MM-DD | `"2025-01-27"` | Creation date |
| `last_modified` | string | YYYY-MM-DD | `"2025-01-27"` | Last modification date |
| `status` | string | Valid status | `"published"` | Document status |
| `onchannel` | integer | 1-999 | `1` | Channel number (must match `channel_number`) |
| `tags` | array | Array of strings | `[SYSTEM, DOCUMENTATION]` | At least one tag required |
| `collections` | array | Array of strings | `[WHAT, WHY, HOW]` | At least one collection required |

### Optional Fields

| Field | Type | Format | Example | Notes |
|-------|------|--------|---------|-------|
| `agent_username` | string | Agent username | `"wolfie"` | Optional but recommended |
| `in_this_file_we_have` | array | Array of strings | `[OVERVIEW, PURPOSE]` | Recommended for TOC |
| `superpositionally` | array | Array of strings | `["FILEID_README"]` | File IDs |

---

## FIELD_VALIDATION

### agent_id Validation

**Rules:**
- Must be present (required)
- Must be a string
- Must be a valid agent identifier
- Common values: "008" (WOLFIE), "007" (VISH), "009" (ROSE), etc.

**Valid Examples:**
```yaml
agent_id: 008
agent_id: "008"
agent_id: 007
```

**Invalid Examples:**
```yaml
# Missing agent_id
# agent_id: (missing)

# Wrong type
agent_id: 8  # Should be string "008"

# Invalid agent
agent_id: 999  # Not a valid agent ID
```

**Error Message**: `"Missing required field: agent_id"` or `"Invalid agent_id: {value}"`

### channel_number Validation

**Rules:**
- Must be present (required)
- Must be a string
- Must be zero-padded (3 digits: 000-999)
- Must match `onchannel` value (as integer)

**Valid Examples:**
```yaml
channel_number: "001"  # Matches onchannel: 1
channel_number: "002"  # Matches onchannel: 2
channel_number: "010"  # Matches onchannel: 10
channel_number: "999"  # Matches onchannel: 999
```

**Invalid Examples:**
```yaml
# Missing channel_number
# channel_number: (missing)

# Not zero-padded
channel_number: "1"     # Should be "001"
channel_number: "10"    # Should be "010"

# Wrong type
channel_number: 1       # Should be string "001"

# Doesn't match onchannel
channel_number: "002"   # If onchannel: 1, should be "001"
```

**Error Message**: `"Missing required field: channel_number"` or `"channel_number must be zero-padded string (000-999)"` or `"channel_number does not match onchannel"`

### version Validation

**Rules:**
- Must be present (required)
- Must be string "2.0.0"
- No other versions accepted for v2.0.0 format

**Valid Examples:**
```yaml
version: 2.0.0
version: "2.0.0"
```

**Invalid Examples:**
```yaml
# Missing version
# version: (missing)

# Wrong version
version: 1.4.2  # Old version
version: 2.1.0  # Future version (not yet released)

# Wrong type
version: 2      # Should be string "2.0.0"
```

**Error Message**: `"Missing required field: version"` or `"version must be '2.0.0' for v2.0.0 format"`

### onchannel Validation

**Rules:**
- Must be present (required)
- Must be integer (1-999)
- Must match `channel_number` (as integer)

**Valid Examples:**
```yaml
onchannel: 1    # Matches channel_number: "001"
onchannel: 2    # Matches channel_number: "002"
onchannel: 999  # Matches channel_number: "999"
```

**Invalid Examples:**
```yaml
# Missing onchannel
# onchannel: (missing)

# Wrong type
onchannel: "1"  # Should be integer 1

# Out of range
onchannel: 0     # Must be 1-999
onchannel: 1000  # Must be 1-999

# Doesn't match channel_number
onchannel: 2     # If channel_number: "001", should be 1
```

**Error Message**: `"Missing required field: onchannel"` or `"onchannel must be integer 1-999"` or `"onchannel does not match channel_number"`

---

## COLLECTION_VALIDATION

### Collection Format Rules

**Rules:**
- Must be present (required)
- Must be an array
- At least one collection required
- All collections must be from 10-section set: `WHO`, `WHAT`, `WHERE`, `WHEN`, `WHY`, `HOW`, `DO`, `HACK`, `OTHER`, `TAGS`
- Collections are case-sensitive (uppercase)
- Duplicate collections are allowed but not recommended

**Valid Examples:**
```yaml
collections: [WHAT]
collections: [WHO, WHAT, WHY, HOW]
collections: [WHAT, HOW, DO]
collections: [WHAT, HACK, OTHER]
```

**Invalid Examples:**
```yaml
# Missing collections
# collections: (missing)

# Empty array
collections: []  # At least one required

# Invalid collection name
collections: [HELP]      # HELP is deprecated, use OTHER
collections: [what]      # Case-sensitive, must be WHAT
collections: [WHAT, HELP]  # HELP is not in 10-section set

# Wrong type
collections: WHAT        # Must be array [WHAT]
```

**Error Message**: `"Missing required field: collections"` or `"collections must include at least one item"` or `"Invalid collection: {name} (must be from 10-section set)"`

### 10-Section Set

Valid collections (case-sensitive):
- `WHO`
- `WHAT`
- `WHERE`
- `WHEN`
- `WHY`
- `HOW`
- `DO`
- `HACK`
- `OTHER`
- `TAGS`

**Deprecated Collections:**
- `HELP` (v1.4.2) - Use `OTHER` or `WHO` instead

---

## TAG_VALIDATION

### Tag Format Rules

**Rules:**
- Must be present (required)
- Must be an array
- At least one tag required
- Tags are case-sensitive
- Tags must exist in channel-specific tag reference files
- Duplicate tags are allowed but not recommended

**Valid Examples:**
```yaml
tags: [SYSTEM]
tags: [SYSTEM, DOCUMENTATION]
tags: [SYSTEM, DATABASE, MIGRATION]
```

**Invalid Examples:**
```yaml
# Missing tags
# tags: (missing)

# Empty array
tags: []  # At least one required

# Case-sensitive
tags: [system]  # Should be SYSTEM (uppercase)

# Tag doesn't exist in reference
tags: [INVALID_TAG]  # Must exist in channel tag reference

# Wrong type
tags: SYSTEM  # Must be array [SYSTEM]
```

**Error Message**: `"Missing required field: tags"` or `"tags must include at least one item"` or `"Tag not found in channel reference: {tag}"`

### Tag Reference Lookup

Tags are validated against channel-specific tag reference files:
1. `docs/channel_{onchannel}/1_wolfie_{agent}/TAGS.md` (agent-specific)
2. `docs/channel_{onchannel}/1_wolfie_wolfie/TAGS.md` (authoritative)
3. `docs/channel_{onchannel}/1_wolfie/TAGS.md` (legacy fallback)

If tag is not found in any reference file, validation fails.

---

## CHANNEL_VALIDATION

### Channel Number Consistency

**Rules:**
- `channel_number` (string) must match `onchannel` (integer)
- Channel directory must exist: `docs/channel_{onchannel}/`
- Channel number must be 000-999 (zero-padded)

**Valid Examples:**
```yaml
onchannel: 1
channel_number: "001"  # Matches
```

**Invalid Examples:**
```yaml
onchannel: 1
channel_number: "002"  # Doesn't match

onchannel: 999
channel_number: "1000"  # Out of range
```

**Error Message**: `"channel_number does not match onchannel"` or `"Channel directory not found: docs/channel_{onchannel}/"`

---

## YAML_VALIDATION

### YAML Syntax Rules

**Rules:**
- Valid YAML syntax required
- No trailing commas in arrays
- Proper indentation
- No duplicate keys
- Exactly one blank line between YAML frontmatter and first heading

**Valid Examples:**
```yaml
---
title: README.md
tags: [SYSTEM]
---
```

**Invalid Examples:**
```yaml
---
title: README.md
tags: [SYSTEM],  # Trailing comma
---

---
title: README.md
  tags: [SYSTEM]  # Wrong indentation
---

---
title: README.md
title: DUPLICATE  # Duplicate key
---
```

**Error Message**: `"YAML syntax error: {error}"` or `"Duplicate key: {key}" or `"Exactly one blank line required between YAML and first heading"`

---

## CONTENT_VALIDATION

### Section Heading Format

**Rules:**
- Section headings should use uppercase snake case (e.g., `## ACTION_ITEMS`)
- Sections should align with collections listed in frontmatter
- Sections don't need to match collections exactly, but should be related

**Valid Examples:**
```markdown
## OVERVIEW
## ACTION_ITEMS
## WORKAROUNDS
## IMPLEMENTATION_NOTES
```

**Invalid Examples:**
```markdown
## overview  # Should be uppercase
## Action Items  # Should be snake case
## action-items  # Should be uppercase snake case
```

**Warning Message**: `"Section heading format: Use uppercase snake case (e.g., ## ACTION_ITEMS)"`

### Content Alignment

**Rules:**
- Document sections should align with collections listed in frontmatter
- If `collections: [DO]` is specified, document should have action items section
- If `collections: [HACK]` is specified, document should have workarounds section

**Warning Message**: `"Collection {collection} specified but no matching section found"`

---

## ERROR_MESSAGES

### Error Severity Levels

**ERROR**: Blocks acceptance, must be fixed
- Missing required fields
- Invalid field formats
- Invalid collections/tags
- YAML syntax errors

**WARNING**: Informational, doesn't block acceptance
- Section heading format suggestions
- Content alignment suggestions
- Deprecated field usage

### Common Error Messages

| Error | Severity | Solution |
|-------|----------|----------|
| `"Missing required field: {field}"` | ERROR | Add the missing field |
| `"Invalid agent_id: {value}"` | ERROR | Use valid agent ID (e.g., "008") |
| `"channel_number must be zero-padded string (000-999)"` | ERROR | Use "001" instead of "1" |
| `"version must be '2.0.0' for v2.0.0 format"` | ERROR | Set `version: 2.0.0` |
| `"Invalid collection: {name}"` | ERROR | Use collections from 10-section set |
| `"Tag not found in channel reference: {tag}"` | ERROR | Use tags from channel tag reference |
| `"YAML syntax error: {error}"` | ERROR | Fix YAML syntax |
| `"Section heading format: Use uppercase snake case"` | WARNING | Update section headings |

---

## AUTOMATION

### Validation Script Requirements

Validation scripts should check:

1. **Required Fields**: All required fields present
2. **Field Formats**: All fields match required formats
3. **Collection Validation**: Collections are from 10-section set
4. **Tag Validation**: Tags exist in channel references
5. **Channel Consistency**: `channel_number` matches `onchannel`
6. **YAML Syntax**: Valid YAML syntax
7. **Content Format**: Section headings use uppercase snake case

### Example Validation Script Structure

```php
function validate_header_v2_0_0($yaml_frontmatter, $file_path) {
    $errors = [];
    $warnings = [];
    
    // Check required fields
    if (!isset($yaml_frontmatter['agent_id'])) {
        $errors[] = "Missing required field: agent_id";
    }
    
    // Check channel_number format
    if (!preg_match('/^\d{3}$/', $yaml_frontmatter['channel_number'])) {
        $errors[] = "channel_number must be zero-padded string (000-999)";
    }
    
    // Check collections
    $valid_collections = ['WHO', 'WHAT', 'WHERE', 'WHEN', 'WHY', 'HOW', 'DO', 'HACK', 'OTHER', 'TAGS'];
    foreach ($yaml_frontmatter['collections'] as $collection) {
        if (!in_array($collection, $valid_collections)) {
            $errors[] = "Invalid collection: {$collection}";
        }
    }
    
    // ... more validation ...
    
    return ['errors' => $errors, 'warnings' => $warnings];
}
```

### CI/CD Integration

Validation should run:
- On file save (editor integration)
- On commit (pre-commit hook)
- On pull request (CI pipeline)
- Before release (release checklist)

---

## REFERENCES

- **10-Section Format Guide**: `docs/10_SECTION_FORMAT_GUIDE.md`
- **Migration Guide**: `docs/MIGRATION_1.4.2_TO_2.0.0.md`
- **Collections Reference**: `docs/channel_1/1_wolfie_wolfie/COLLECTIONS.md`
- **Tags Reference**: `docs/channel_1/1_wolfie_wolfie/TAGS.md`

---

© 2025 Eric Robin Gerdes / LUPOPEDIA LLC — Dual licensed under GPL v3.0 + Apache 2.0.

