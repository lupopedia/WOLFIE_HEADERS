---
title: BREAKING_CHANGES_2.0.0.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.0
date_created: 2025-01-27
last_modified: 2025-01-27
status: published
onchannel: 1
tags: [SYSTEM, DOCUMENTATION, BREAKING_CHANGES]
collections: [WHAT, WHY, HOW, DO]
in_this_file_we_have: [OVERVIEW, REQUIRED_FIELDS, COLLECTION_CHANGES, VALIDATION_CHANGES, MIGRATION_IMPACT, DEPRECATED_FEATURES]
superpositionally: ["FILEID_BREAKING_CHANGES_2.0.0"]
---

# Breaking Changes in WOLFIE Headers v2.0.0

## OVERVIEW

WOLFIE Headers v2.0.0 introduces **breaking changes** required by LUPOPEDIA_PLATFORM 1.0.0. **All v1.4.2 headers must be migrated** to v2.0.0 format. This document lists all breaking changes and their impact.

**Migration Required**: Yes  
**Backward Compatible**: No (hard break)  
**Migration Guide**: See `docs/MIGRATION_1.4.2_TO_2.0.0.md`

---

## REQUIRED_FIELDS

### New Required Fields

The following fields are **REQUIRED** in v2.0.0 and will cause validation errors if missing:

#### 1. `agent_id` (NEW REQUIRED)
- **Type**: String
- **Format**: Agent identifier (e.g., "008" for WOLFIE)
- **Impact**: Headers without `agent_id` will fail validation
- **Migration**: Add `agent_id: 008` (or appropriate agent ID) to all headers

#### 2. `channel_number` (NEW REQUIRED)
- **Type**: String (zero-padded)
- **Format**: "000" to "999" (3 digits, zero-padded)
- **Impact**: Headers without `channel_number` will fail validation
- **Migration**: Add `channel_number: "001"` (matching `onchannel` as zero-padded string)
- **Example**: If `onchannel: 1`, add `channel_number: "001"`

#### 3. `version` (NEW REQUIRED)
- **Type**: String
- **Format**: Must be "2.0.0"
- **Impact**: Headers without `version: 2.0.0` will fail validation
- **Migration**: Add `version: 2.0.0` to all headers

**Breaking Impact**: Any header missing these three fields will be rejected by v2.0.0 validation.

---

## COLLECTION_CHANGES

### New Collections Available

Three new collections are available in v2.0.0:

- **DO**: Action items, tasks, to-do lists
- **HACK**: Workarounds, temporary solutions, quick fixes
- **OTHER**: Miscellaneous content that doesn't fit other categories
- **TAGS**: Categorization labels (also exists as YAML field)

### Deprecated Collections

#### `HELP` (Deprecated)
- **Status**: Deprecated in v2.0.0
- **Replacement**: Use `OTHER` for support/help content, or `WHO` for contact information
- **Impact**: Headers using `HELP` collection will fail validation
- **Migration**: Replace `HELP` with `OTHER` or `WHO` depending on content

### Collection Validation

**Breaking Change**: All collections must be from the 10-section set:
- `WHO`, `WHAT`, `WHERE`, `WHEN`, `WHY`, `HOW`, `DO`, `HACK`, `OTHER`, `TAGS`

**Impact**: Any collection not in this set will cause validation errors.

---

## VALIDATION_CHANGES

### Stricter Validation Rules

v2.0.0 enforces stricter validation:

1. **Required Fields**: Missing `agent_id`, `channel_number`, or `version` causes **ERROR** (blocks acceptance)
2. **Collection Validation**: Collections must be from 10-section set (ERROR if invalid)
3. **Channel Number Format**: Must be zero-padded string "000"-"999" (ERROR if invalid format)
4. **Channel Consistency**: `channel_number` must match `onchannel` (ERROR if mismatch)
5. **Tag Validation**: Tags must exist in channel reference files (ERROR if not found)

**Breaking Impact**: Headers that passed v1.4.2 validation may fail v2.0.0 validation.

### Validation Error Severity

- **ERROR**: Blocks acceptance, must be fixed
- **WARNING**: Informational, doesn't block acceptance

See `docs/VALIDATION_RULES_2.0.0.md` for complete validation rules.

---

## MIGRATION_IMPACT

### Files Requiring Updates

**All files with WOLFIE Headers must be updated:**

1. **Add Required Fields**: Every header needs `agent_id`, `channel_number`, `version`
2. **Update Collections**: Replace deprecated `HELP` with `OTHER` or `WHO`
3. **Verify Channel Numbers**: Ensure `channel_number` matches `onchannel` (zero-padded)
4. **Validate Tags**: Ensure all tags exist in channel reference files

### Migration Effort

- **Per File**: 2-5 minutes (add 3 fields, update collections if needed)
- **Bulk Migration**: Use migration scripts (see `TODO_2.0.0.md` for planned tooling)
- **Validation**: Run validation after migration to catch errors

### Automated Migration

Migration scripts can automate:
- Adding required fields (`agent_id`, `channel_number`, `version`)
- Replacing deprecated collections (`HELP` → `OTHER` or `WHO`)
- Validating channel number format

**Manual Review Required**: Verify `agent_id` is correct for each file (cannot be automated).

---

## DEPRECATED_FEATURES

### Deprecated in v2.0.0

1. **HELP Collection**: Deprecated, use `OTHER` or `WHO` instead
2. **v1.4.2 Format**: Old format without required fields is no longer valid
3. **Flexible Collections**: Collections must be from 10-section set only

### Removal Timeline

- **v2.0.0**: Deprecated features cause validation errors
- **No Grace Period**: Hard break - v1.4.2 headers are not accepted

---

## COMPATIBILITY

### Backward Compatibility

**v2.0.0 is NOT backward compatible with v1.4.2.**

- **Hard Break**: v1.4.2 headers will not work in v2.0.0
- **No Compatibility Layer**: v2.0.0 does not read v1.4.2 format
- **Migration Required**: All headers must be migrated

### Forward Compatibility

- **v2.0.0+**: Future versions (2.1.0, 2.2.0, etc.) will maintain compatibility with v2.0.0 format
- **Breaking Changes**: Future breaking changes will bump to v3.0.0

---

## EXAMPLES

### v1.4.2 Format (No Longer Valid)

```yaml
---
title: README.md
agent_username: wolfie
date_created: 2025-01-27
last_modified: 2025-01-27
status: published
onchannel: 1
tags: [SYSTEM, DOCUMENTATION]
collections: [WHAT, HELP]
in_this_file_we_have: [OVERVIEW]
superpositionally: ["FILEID_README"]
---
```

**Issues**:
- Missing `agent_id` (required)
- Missing `channel_number` (required)
- Missing `version` (required)
- Uses deprecated `HELP` collection

### v2.0.0 Format (Valid)

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
collections: [WHAT, OTHER]
in_this_file_we_have: [OVERVIEW]
superpositionally: ["FILEID_README"]
---
```

**Changes**:
- Added `agent_id: 008`
- Added `channel_number: "001"`
- Added `version: 2.0.0`
- Replaced `HELP` with `OTHER`

---

## SUMMARY

**Breaking Changes Summary**:

1. ✅ Three new required fields: `agent_id`, `channel_number`, `version`
2. ✅ `HELP` collection deprecated (use `OTHER` or `WHO`)
3. ✅ Collections must be from 10-section set only
4. ✅ Stricter validation rules (errors block acceptance)
5. ✅ No backward compatibility with v1.4.2

**Action Required**: Migrate all v1.4.2 headers to v2.0.0 format. See `docs/MIGRATION_1.4.2_TO_2.0.0.md` for step-by-step guide.

---

## REFERENCES

- **Migration Guide**: `docs/MIGRATION_1.4.2_TO_2.0.0.md`
- **Validation Rules**: `docs/VALIDATION_RULES_2.0.0.md`
- **10-Section Format**: `docs/10_SECTION_FORMAT_GUIDE.md`
- **Compatibility Matrix**: `docs/COMPATIBILITY_MATRIX.md`

---

© 2025 Eric Robin Gerdes / LUPOPEDIA LLC — Dual licensed under GPL v3.0 + Apache 2.0.

