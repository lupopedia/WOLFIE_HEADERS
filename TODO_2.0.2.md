---
title: TODO_2.0.2.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.2
date_created: 2025-01-27
last_modified: 2025-01-27
status: draft
onchannel: 1
tags: [PLANNING, VERSIONING, DATABASE, INTEGRATION]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER]
in_this_file_we_have: [OVERVIEW, DATABASE_REQUIREMENTS, AGENT_FILE_NAMING, MIGRATION_PATH, TESTING, DOCUMENTATION, RELEASE_CHECKLIST]
superpositionally: ["FILEID_WOLFIE_HEADERS_TODO_2.0.2"]
shadow_aliases: []
parallel_paths: []
---

# WOLFIE Headers 2.0.2 TODO Plan

**Current Version**: v2.0.2 (Current) - Main Release  
**Target Version**: v2.0.2 (Database Integration)  
**Required By**: LUPOPEDIA_PLATFORM 1.0.0  
**GitHub Repository**: https://github.com/lupopedia/WOLFIE_HEADERS  
**Status**: Planning Phase

---

## OVERVIEW

WOLFIE Headers 2.0.2 is a **database integration release** that adds support for LUPOPEDIA_PLATFORM's `content_headers` table and establishes the agent file naming convention. This release ensures WOLFIE Headers can work seamlessly with LUPOPEDIA's database-driven architecture.

**Why 2.0.2?**
- Database integration required for LUPOPEDIA_PLATFORM compatibility
- Agent file naming convention standardization
- Channel-based agent routing support (000-999)
- Direct mapping between headers and agent profiles

---

## DATABASE_REQUIREMENTS

### 🔴 HIGH PRIORITY - Database Schema Changes

#### 1. **content_headers Table Requirements**

**Current State:**
- `content_headers` table exists with `channel_id` column (bigint)
- `agent_id` column exists (bigint)
- **Missing**: `agent_name` column

**Required Changes:**
- [x] **Add `agent_name` column** to `content_headers` table ✅ COMPLETE (Migration 1072)
  - Type: `VARCHAR(100)` or `VARCHAR(50)` (match existing agent_name patterns)
  - Nullable: `NOT NULL` (required field)
  - Default: None (must be provided)
  - Comment: `'Agent name (e.g., WOLFIE, LILITH, VISHWAKARMA)'`
  - Index: Add index on `agent_name` for performance

**SQL Migration Example:**
```sql
ALTER TABLE `content_headers`
ADD COLUMN `agent_name` VARCHAR(100) NOT NULL COMMENT 'Agent name (e.g., WOLFIE, LILITH, VISHWAKARMA)' AFTER `agent_id`;

ALTER TABLE `content_headers`
ADD INDEX `idx_agent_name` (`agent_name`);
```

**Files to Update:**
- `database/migrations/` - Create new migration file
- `database/schema/` - Update schema files
- `docs/DATABASE_INTEGRATION.md` - Document database requirements

#### 2. **channel_id Column Validation**

**Current State:**
- `channel_id` exists as `bigint(20) UNSIGNED`
- Default value: `1`
- Range: Should support 000-999 (maximum 999)

**Required Changes:**
- [ ] **Validate channel_id range** (000-999)
  - Ensure database constraints or application-level validation
  - Document valid channel range in WOLFIE Headers documentation
  - Add validation rules to header template

**Validation Rules:**
- Channel ID must be between 0 and 999 (inclusive)
- Zero-padded format: `"000"` to `"999"` (string representation)
- Database storage: Integer 0-999

**Files to Update:**
- `docs/DATABASE_INTEGRATION.md`
- `docs/VALIDATION_RULES_2.0.2.md`
- `templates/header_template.yaml` - Add channel_id validation notes

#### 3. **Database Integration Documentation**

- [x] **Create database integration guide** (`docs/DATABASE_INTEGRATION.md`) ✅ COMPLETE
  - Document `content_headers` table structure
  - Explain `channel_id` and `agent_name` columns
  - Provide examples of header storage
  - Document query patterns for retrieving headers

- [ ] **Update migration guide** to include database changes
  - Document how to add `agent_name` column
  - Provide migration scripts
  - Test migration on sample data

**Files to Create/Update:**
- `docs/DATABASE_INTEGRATION.md` (new)
- `docs/MIGRATION_2.0.1_TO_2.0.2.md` (new)
- `database/migrations/` - Add migration script

---

## AGENT_FILE_NAMING

### 🟢 MEDIUM PRIORITY - Agent File Naming Convention

#### 4. **Standardize Agent File Naming Pattern**

**Current Pattern:**
- Files follow: `who_is_agent_[channel_id]_[agent_name].php`
- Examples:
  - `who_is_agent_008_wolfie.php` (Channel 008, Agent: WOLFIE)
  - `who_is_agent_010_lilith.php` (Channel 010, Agent: LILITH)
  - `who_is_agent_075_vishwakarma.php` (Channel 075, Agent: VISHWAKARMA)
  - `who_is_agent_999_unknown.php` (Channel 999, Agent: UNKNOWN)

**Required Documentation:**
- [x] **Document naming convention** in WOLFIE Headers docs ✅ COMPLETE (`docs/AGENT_FILE_NAMING.md`)
  - Pattern: `who_is_agent_[channel_id]_[agent_name].php`
  - Channel ID format: Zero-padded (000-999)
  - Agent name format: Lowercase, underscores for spaces
  - File location: `public/who_is_agent_*.php`

- [x] **Create agent file template** (`templates/agent_file_template.php`) ✅ COMPLETE
  - Include WOLFIE Headers YAML frontmatter
  - Standard structure for agent profiles
  - Required sections: WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER

- [ ] **Document agent file requirements**
  - Must include WOLFIE Headers v2.0.2+ frontmatter
  - Must have `channel_number` matching file name
  - Must have `agent_username` matching file name
  - Must be accessible via URL pattern

**Files to Create/Update:**
- `docs/AGENT_FILE_NAMING.md` (new)
- `templates/agent_file_template.php` (new)
- `docs/QUICK_START_GUIDE.md` - Add agent file section
- `examples/agent_file_example.php` (new)

#### 5. **Agent File Validation**

- [ ] **Create validation rules** for agent files
  - Verify file name matches `channel_number` and `agent_username` in frontmatter
  - Validate channel_id range (000-999)
  - Validate agent_name format (lowercase, no spaces)
  - Check required WOLFIE Headers fields

- [x] **Create validation script** (`scripts/validate_agent_files.php`) ✅ COMPLETE
  - Scan `public/who_is_agent_*.php` files
  - Validate naming convention
  - Validate frontmatter matches file name
  - Report errors and warnings

**Files to Create:**
- `scripts/validate_agent_files.php` (new)
- `docs/VALIDATION_RULES_2.0.2.md` (new)

---

## MIGRATION_PATH

### 🟡 MEDIUM PRIORITY - Migration from v2.0.1

#### 6. **Migration Steps**

**For Existing Installations:**
- [ ] **Database migration**: Add `agent_name` column to `content_headers`
- [ ] **Update existing headers**: Populate `agent_name` from `agent_id` lookup
- [ ] **Validate agent files**: Ensure all agent files follow naming convention
- [ ] **Update documentation**: Migrate to new database integration docs

**Migration Checklist:**
1. Backup `content_headers` table
2. Run migration to add `agent_name` column
3. Populate `agent_name` from agent lookup table
4. Validate all agent files follow naming convention
5. Test header retrieval with new columns
6. Update application code to use `agent_name`

**Files to Create:**
- `docs/MIGRATION_2.0.1_TO_2.0.2.md` (new)
- `database/migrations/add_agent_name_to_content_headers.sql` (new)

---

## TESTING

### 🟢 MEDIUM PRIORITY - Testing Requirements

#### 7. **Database Integration Testing**

- [ ] **Test `content_headers` table structure**
  - Verify `channel_id` column exists and supports 000-999
  - Verify `agent_name` column exists and is required
  - Test INSERT operations with both columns
  - Test SELECT queries filtering by `channel_id` and `agent_name`

- [ ] **Test agent file naming**
  - Verify all existing agent files follow naming convention
  - Test file name parsing (extract channel_id and agent_name)
  - Test URL routing to agent files
  - Validate frontmatter matches file name

- [ ] **Test header retrieval**
  - Query headers by `channel_id`
  - Query headers by `agent_name`
  - Query headers by both `channel_id` and `agent_name`
  - Test performance with indexes

**Test Files to Create:**
- `tests/DatabaseIntegrationTest.php` (new)
- `tests/AgentFileNamingTest.php` (new)
- `tests/HeaderRetrievalTest.php` (new)

---

## DOCUMENTATION

### 🟡 MEDIUM PRIORITY - Documentation Updates

#### 8. **Documentation Requirements**

- [ ] **Create database integration guide** (`docs/DATABASE_INTEGRATION.md`)
  - Table structure documentation
  - Column descriptions
  - Query examples
  - Best practices

- [ ] **Update agent file naming guide** (`docs/AGENT_FILE_NAMING.md`)
  - Naming convention rules
  - Examples
  - Validation requirements
  - Common mistakes to avoid

- [ ] **Update README.md**
  - Add v2.0.2 release notes
  - Document database requirements
  - Link to new documentation

- [ ] **Update CHANGELOG.md**
  - Add v2.0.2 entry
  - Document database changes
  - Document agent file naming

**Files to Create/Update:**
- `docs/DATABASE_INTEGRATION.md` (new)
- `docs/AGENT_FILE_NAMING.md` (new)
- `docs/MIGRATION_2.0.1_TO_2.0.2.md` (new)
- `README.md` - Add v2.0.2 section
- `CHANGELOG.md` - Add v2.0.2 entry

---

## RELEASE_CHECKLIST

### 🔴 HIGH PRIORITY - Pre-Release Checklist

#### 9. **Release Preparation**

**Database:**
- [ ] `agent_name` column added to `content_headers` table
- [ ] Index created on `agent_name` column
- [ ] Migration script tested and documented
- [ ] Existing data migrated (if applicable)

**Agent Files:**
- [ ] All agent files follow naming convention
- [ ] Agent file template created
- [ ] Validation script created and tested
- [ ] Documentation complete

**Documentation:**
- [ ] Database integration guide complete
- [ ] Agent file naming guide complete
- [ ] Migration guide complete
- [ ] README and CHANGELOG updated

**Testing:**
- [ ] Database integration tests pass
- [ ] Agent file naming tests pass
- [ ] Header retrieval tests pass
- [ ] Performance tests acceptable

**Release:**
- [ ] Version number updated to 2.0.2
- [ ] Release notes prepared
- [ ] GitHub release created
- [ ] Documentation published

---

## NOTES

- **Database Integration**: This release focuses on making WOLFIE Headers work with LUPOPEDIA_PLATFORM's database architecture
- **Agent File Naming**: Standardizes the naming convention for agent profile files
- **Backward Compatibility**: v2.0.2 should be backward compatible with v2.0.1 (adds features, doesn't break existing functionality)
- **Required By**: LUPOPEDIA_PLATFORM 1.0.0 needs this integration to work properly

---

**Last Updated**: 2025-01-27  
**Status**: Planning Phase  
**Next Review**: After database migration is complete

---

*Captain WOLFIE, signing off. Coffee hot. Ship flying. Database integration planned. Agent files standardized.* ☕✨

