---
title: MIGRATION_1.4.2_TO_2.0.0.md
agent_username: wolfie
date_created: 2025-01-27
last_modified: 2025-01-27
status: published
onchannel: 1
agent_id: 008
channel_number: 001
version: 2.0.0
tags: [SYSTEM, DOCUMENTATION, MIGRATION]
collections: [WHAT, WHY, HOW, DO]
in_this_file_we_have: [OVERVIEW, BREAKING_CHANGES, STEP_BY_STEP, FIELD_MAPPING, EXAMPLES, VALIDATION, TROUBLESHOOTING]
superpositionally: ["FILEID_MIGRATION_1.4.2_TO_2.0.0"]
---

# Migration Guide: v1.4.2 to v2.0.0

## OVERVIEW

This guide provides step-by-step instructions for migrating WOLFIE Headers from v1.4.2 to v2.0.0 format. **This migration is required** for compatibility with LUPOPEDIA_PLATFORM 1.0.0.

**Migration Complexity**: Medium  
**Estimated Time**: 5-10 minutes per file  
**Breaking Changes**: Yes (v1.4.2 headers will not be valid in v2.0.0)

---

## BREAKING_CHANGES

### Required New Fields
- `agent_id`: Agent identifier (string, e.g., "008" for WOLFIE)
- `channel_number`: Channel number as zero-padded string (000-999)
- `version`: Header format version (must be "2.0.0")

### Collection Format Changes
- New collections available: `DO`, `HACK`, `OTHER`, `TAGS`
- All collections must be from the 10-section set: `WHO`, `WHAT`, `WHERE`, `WHEN`, `WHY`, `HOW`, `DO`, `HACK`, `OTHER`, `TAGS`
- Old collection names (if any) may be deprecated

### Validation Changes
- Stricter validation: Missing required fields will cause errors
- Channel number format: Must be zero-padded string (001, not 1)
- Agent ID must be specified

---

## STEP_BY_STEP

### Step 1: Add Required Fields

Add these three new fields to your YAML frontmatter:

```yaml
agent_id: 008          # Agent identifier (008 = WOLFIE)
channel_number: 001    # Channel number as zero-padded string (000-999)
version: 2.0.0         # Header format version
```

**Where to place them:**
- Place `agent_id` after `agent_username`
- Place `channel_number` after `agent_id`
- Place `version` after `channel_number`

**Example:**
```yaml
---
title: my_file.md
agent_username: wolfie
agent_id: 008          # NEW
channel_number: 001    # NEW
version: 2.0.0         # NEW
date_created: 2025-01-27
# ... rest of fields
---
```

### Step 2: Update Collections Field

Review your `collections` field and update it to use the 10-section format:

**v1.4.2 format (example):**
```yaml
collections: [WHAT, WHY, HOW]
```

**v2.0.0 format (same example, but now with new options available):**
```yaml
collections: [WHAT, WHY, HOW]  # Still valid, but you can now add DO, HACK, OTHER, TAGS
```

**If you have action items or tasks:**
```yaml
collections: [WHAT, WHY, HOW, DO]  # Add DO for action items
```

**If you have workarounds or temporary fixes:**
```yaml
collections: [WHAT, HOW, HACK]  # Add HACK for workarounds
```

**If you have miscellaneous content:**
```yaml
collections: [WHAT, OTHER]  # Add OTHER for misc content
```

**Available collections (10-section format):**
- `WHO` - People, agents, teams, organizations
- `WHAT` - Description of document/component
- `WHERE` - Locations, paths, environments
- `WHEN` - Timelines, dates, schedules
- `WHY` - Purpose, rationale, motivation
- `HOW` - Implementation, workflows, procedures
- `DO` - Action items, tasks, to-do lists (NEW)
- `HACK` - Workarounds, temporary solutions (NEW)
- `OTHER` - Miscellaneous content (NEW)
- `TAGS` - Categorization labels (NEW as collection)

**Note**: You don't need to include all 10 collections—only include the ones relevant to your document. See `docs/10_SECTION_FORMAT_GUIDE.md` for detailed definitions.

### Step 3: Verify Channel Number Format

Ensure `channel_number` matches `onchannel` but as a zero-padded string:

**v1.4.2:**
```yaml
onchannel: 1
```

**v2.0.0:**
```yaml
onchannel: 1
channel_number: 001    # Must be zero-padded string (000-999)
```

**Channel Number Rules:**
- Must be 3 digits, zero-padded (001, 002, 010, 099, 999)
- Must match `onchannel` value (as integer)
- Channel 1 = "001", Channel 2 = "002", etc.

### Step 4: Verify Agent ID

Set `agent_id` based on the agent:

**Common Agent IDs:**
- `008` = WOLFIE (System Architect)
- `007` = VISH (Vision Agent)
- `009` = ROSE (Cultural Translation Agent)
- `010` = THALIA (Humor Agent)
- `011` = ERIS (Conflict Analysis Agent)
- `012` = METIS (Empathy Agent)
- `013` = AGAPE (Teaching Agent)

**If unsure**: Use `008` for WOLFIE (default system agent).

### Step 5: Update Document Sections (Optional)

If your document has sections that map to new collections (DO, HACK, OTHER), consider:
- Adding `## ACTION_ITEMS` or `## TODO` sections if you have tasks (map to DO)
- Adding `## WORKAROUNDS` or `## TEMPORARY_FIXES` sections if you have workarounds (map to HACK)
- Adding `## NOTES` or `## OTHER` sections for miscellaneous content (map to OTHER)

Update `in_this_file_we_have` to reflect any new sections.

### Step 6: Validate

Run validation checks:
1. YAML syntax is valid
2. All required fields present (`agent_id`, `channel_number`, `version`)
3. `channel_number` is zero-padded (001, not 1)
4. `channel_number` matches `onchannel` (as integer)
5. Collections are from the 10-section set
6. Tags exist in channel tag reference
7. Exactly one blank line between YAML and first heading

---

## FIELD_MAPPING

### v1.4.2 → v2.0.0 Field Mapping

| v1.4.2 Field | v2.0.0 Field | Change Type | Notes |
|--------------|-------------|-------------|-------|
| `title` | `title` | Unchanged | Same |
| `agent_username` | `agent_username` | Unchanged | Same |
| *(none)* | `agent_id` | **NEW REQUIRED** | Agent identifier (e.g., "008") |
| *(none)* | `channel_number` | **NEW REQUIRED** | Zero-padded string (000-999) |
| *(none)* | `version` | **NEW REQUIRED** | Must be "2.0.0" |
| `date_created` | `date_created` | Unchanged | Same |
| `last_modified` | `last_modified` | Unchanged | Same |
| `status` | `status` | Unchanged | Same |
| `onchannel` | `onchannel` | Unchanged | Must match `channel_number` |
| `tags` | `tags` | Unchanged | Same |
| `collections` | `collections` | **EXPANDED** | Now includes DO, HACK, OTHER, TAGS |
| `in_this_file_we_have` | `in_this_file_we_have` | Unchanged | Same |
| `superpositionally` | `superpositionally` | Unchanged | Same |

---

## EXAMPLES

### Example 1: Simple Documentation File

**v1.4.2:**
```yaml
---
title: README.md
agent_username: wolfie
date_created: 2025-01-27
last_modified: 2025-01-27
status: published
onchannel: 1
tags: [SYSTEM, DOCUMENTATION]
collections: [WHAT, WHY, HOW]
in_this_file_we_have: [OVERVIEW, PURPOSE, USAGE]
superpositionally: ["FILEID_README"]
---
```

**v2.0.0:**
```yaml
---
title: README.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.0
date_created: 2025-01-27
last_modified: 2025-01-27
status: published
onchannel: 1
tags: [SYSTEM, DOCUMENTATION]
collections: [WHAT, WHY, HOW]
in_this_file_we_have: [OVERVIEW, PURPOSE, USAGE]
superpositionally: ["FILEID_README"]
---
```

**Changes:**
- Added `agent_id: 008`
- Added `channel_number: 001`
- Added `version: 2.0.0`
- Collections unchanged (still valid)

### Example 2: Migration Guide with Action Items

**v1.4.2:**
```yaml
---
title: MIGRATION_GUIDE.md
agent_username: wolfie
date_created: 2025-01-27
last_modified: 2025-01-27
status: published
onchannel: 1
tags: [SYSTEM, DOCUMENTATION, MIGRATION]
collections: [WHAT, WHY, HOW]
in_this_file_we_have: [OVERVIEW, RATIONALE, STEPS]
superpositionally: ["FILEID_MIGRATION"]
---
```

**v2.0.0 (with DO collection for action items):**
```yaml
---
title: MIGRATION_GUIDE.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.0
date_created: 2025-01-27
last_modified: 2025-01-27
status: published
onchannel: 1
tags: [SYSTEM, DOCUMENTATION, MIGRATION]
collections: [WHAT, WHY, HOW, DO]
in_this_file_we_have: [OVERVIEW, RATIONALE, STEPS, ACTION_ITEMS]
superpositionally: ["FILEID_MIGRATION"]
---
```

**Changes:**
- Added `agent_id: 008`
- Added `channel_number: 001`
- Added `version: 2.0.0`
- Added `DO` to collections (for action items section)
- Added `ACTION_ITEMS` to `in_this_file_we_have`

### Example 3: Troubleshooting Guide with Workarounds

**v1.4.2:**
```yaml
---
title: TROUBLESHOOTING.md
agent_username: wolfie
date_created: 2025-01-27
last_modified: 2025-01-27
status: published
onchannel: 1
tags: [SYSTEM, HOWTO]
collections: [WHAT, HOW]
in_this_file_we_have: [PROBLEMS, SOLUTIONS]
superpositionally: ["FILEID_TROUBLESHOOTING"]
---
```

**v2.0.0 (with HACK collection for workarounds):**
```yaml
---
title: TROUBLESHOOTING.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.0
date_created: 2025-01-27
last_modified: 2025-01-27
status: published
onchannel: 1
tags: [SYSTEM, HOWTO]
collections: [WHAT, HOW, HACK]
in_this_file_we_have: [PROBLEMS, SOLUTIONS, WORKAROUNDS]
superpositionally: ["FILEID_TROUBLESHOOTING"]
---
```

**Changes:**
- Added `agent_id: 008`
- Added `channel_number: 001`
- Added `version: 2.0.0`
- Added `HACK` to collections (for workarounds section)
- Added `WORKAROUNDS` to `in_this_file_we_have`

---

## VALIDATION

After migration, validate your headers using these checks:

### Automated Validation Checklist

- [ ] YAML syntax is valid (no parsing errors)
- [ ] `agent_id` is present and valid
- [ ] `channel_number` is present and zero-padded (001, not 1)
- [ ] `version` is present and equals "2.0.0"
- [ ] `channel_number` matches `onchannel` (as integer: "001" = 1)
- [ ] All collections are from 10-section set: WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER, TAGS
- [ ] At least one collection is specified
- [ ] All tags exist in channel tag reference
- [ ] Exactly one blank line between YAML and first heading
- [ ] No trailing whitespace in YAML keys

### Manual Validation Checklist

- [ ] Document sections align with collections listed
- [ ] `in_this_file_we_have` accurately reflects document structure
- [ ] Agent ID matches the intended agent
- [ ] Channel number is correct for the document's context

---

## TROUBLESHOOTING

### Common Issues

**Issue**: "Missing required field: agent_id"  
**Solution**: Add `agent_id: 008` (or appropriate agent ID) to frontmatter

**Issue**: "channel_number must be zero-padded string"  
**Solution**: Use "001" instead of "1", "002" instead of "2", etc.

**Issue**: "channel_number does not match onchannel"  
**Solution**: Ensure `channel_number: 001` matches `onchannel: 1` (as integer)

**Issue**: "Invalid collection: HELP"  
**Solution**: HELP is not in the 10-section format. Use OTHER for support/help content, or remove if not needed.

**Issue**: "Collection not in 10-section set"  
**Solution**: All collections must be from: WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER, TAGS

**Issue**: "YAML parsing error"  
**Solution**: Check YAML syntax, ensure proper indentation, no trailing commas in arrays

### Migration Tools

If migrating many files, consider:
1. **Bulk find/replace**: Use text editor to add required fields to all files
2. **Script automation**: Create a migration script (PHP/Python) to automate conversion
3. **Validation script**: Run validation after migration to catch errors

See `TODO_2.0.0.md` for planned migration tooling.

---

## NEXT_STEPS

After migration:
1. **Validate**: Run validation checks on all migrated files
2. **Test**: Ensure files parse correctly in LUPOPEDIA_PLATFORM
3. **Update**: Update any tooling that reads headers to support v2.0.0
4. **Document**: Update project documentation to reference v2.0.0 format

---

## REFERENCES

- **10-Section Format Guide**: `docs/10_SECTION_FORMAT_GUIDE.md`
- **Header Template**: `templates/header_template.yaml`
- **Validation Rules**: See validation section in `docs/10_SECTION_FORMAT_GUIDE.md`
- **TODO Plan**: `TODO_2.0.0.md`

---

© 2025 Eric Robin Gerdes / LUPOPEDIA LLC — Dual licensed under GPL v3.0 + Apache 2.0.

