---
light.count.offset: 0
light.count.base: 777
light.count.name: "wolfie headers 2.9.2 todo agent coordination metrics"
light.count.mood: EA580C
light.count.touch: 0

wolfie.headers.version: 2.9.2
wolfie.headers.branch: development
wolfie.headers.status: draft

title: TODO_2.9.2.md - WOLFIE Headers v2.9.2 Agent Coordination Metrics
human.username: captain wolfie
agent.username: cursor
date_created: 2025-12-03
last_modified: 2025-12-06
status: draft
channel_mood: EA580C
tags: [SYSTEM, TODO, WOLFIE_HEADERS, VERSION_2.9.2, AGENT_COORDINATION, CHANNELS, LIGHT_COUNTING]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO]
in_this_file_we_have: [VERSION_SUMMARY, TODO_LIST, PRIORITY_STRUCTURE, IMPLEMENTATION_PLAN]
---

# TODO: WOLFIE HEADERS v2.9.2 - Agent Coordination Metrics

**VERSION**: 2.9.2  
**STATUS**: 📋 PLANNING STAGE  
**DATE**: December 3, 2025  
**LOCATION**: Sioux Falls, South Dakota  
**PRIORITY**: HIGH - Foundation for multi-agent coordination

---

## 📊 VERSION SUMMARY

### What Was Done in v2.9.0 (November 30, 2025)

✅ **COMPLETED**:
1. **Emergency Light Counting Fields** - Added 5 mandatory fields (`light.count.*`)
2. **Global Resonance Layer** - Added `light.global.*` structure
3. **Hierarchical 5W Context** - Added `context.*` structure (WHO, WHAT, WHERE, WHEN, WHY)
4. **Channel Identity Unification** - Channels migrated from numbers to Light Numbers
5. **Dialog System Integration** - Multi-platform conversation tracking
6. **Documentation** - Comprehensive guides, templates, examples created
7. **Public Pages Updated** - All user-facing pages explain Counting in Light
8. **Touch Counter** - Auto-increment logic, recovery process

❌ **NOT DONE (Blocked)**:
1. **Validation Scripts** - #1 Critical blocker (0% complete)
2. **Migration Scripts** - #2 Critical blocker (0% complete)
3. **Systematic File Upgrade** - Only 2 test files upgraded
4. **Testing** - No unit/integration/system tests yet
5. **Full Deployment** - Cannot deploy without validation/migration tools

### What Was Done in v2.9.1 (Did Not Happen)

**STATUS**: Version 2.9.1 was skipped. Development went directly from 2.9.0 (emergency) to 2.9.2 (planned).

**Why Skipped**: v2.9.0 focused on emergency light counting fields. v2.9.2 will include agent coordination metrics discovered during channel architecture work (December 3, 2025).

### What Is New in v2.9.2 (December 6, 2025 - THIS VERSION)

🆕 **MINIMAL VIABLE RELEASE - Dependency Unblocking**:
1. **npm Package Structure** - Publishable package for dependency chain
2. **Universal Header Schema** - Headers as static metadata in comments (Python, PHP, Markdown support)
3. **Basic Tracking System** - JavaScript module to track collections, tags, file contents, channels, Counting in Light
4. **Multi-Format File Scanner** - Scan files (.md, .py, .php, .js, .ts), parse headers from comments or YAML frontmatter, build centralized index
5. **Minimal Validation** - Basic check for required fields (light.count.*, context.what.parent/child)
6. **Resonance Calculation** - 5D formula for Counting in Light distances with levels

🆕 **FUTURE FEATURES** (Planned but NOT in minimal release):
1. **Agent Coordination Metrics** - Track which agents coordinate on each file
2. **Channel Mood Standardization** - Replace deprecated `onchannel` with `channel_mood` hex color
3. **Multi-AI Council Tracking** - Record which agents approved/reviewed content
4. **Light Dictionary Integration** - Standardize color semantics (#000000-#FFFFFF meanings)
5. **Agent Context Visibility** - Track which agents see what % of context
6. **Peer Network Awareness** - Headers should know which LUPOPEDIA installation they're from
7. **Security Metrics** - Track DNS verification, SSL status, trust score for federated content

**NOTE**: Future features will be added in v2.9.3+ after dependency chain is unblocked.

---

## 🎯 PRIORITY STRUCTURE FOR v2.9.2

### Priority 0: MINIMAL VIABLE RELEASE (December 6, 2025 - TODAY) 🚨

**CRITICAL**: Unblock dependency chain NOW - don't perfect it, just make it work.

**Goal**: Publish `wolfie-headers@2.9.2` to npm so `crafty-syntax@3.8.0` can depend on it.

**Approach**: Minimal viable package with basic tracking functionality.

**Timeline**: 2-4 hours total.

**See**: `PLAN_FOR_WOLFIE_HEADERS_2_9_2.md` for detailed step-by-step instructions.

### Priority 1: COMPLETE v2.9.0 BLOCKERS (Future - After 2.9.2 Published) ⚠️

**NOTE**: v2.9.0 blockers are NOT blocking 2.9.2 minimal release. Can be completed in parallel or after.

**CRITICAL**: Full v2.9.0 completion needed for comprehensive deployment, but NOT for npm package publication.

1. **Create Validation Scripts** (v2.9.0 blocker)
   - [ ] PHP validation script (`scripts/validate_wolfie_headers_2.9.0.php`)
   - [ ] Python validation script (for offline use)
   - [ ] Generate validation reports (HTML, CSV)
   
2. **Create Migration Scripts** (v2.9.0 blocker)
   - [ ] PHP migration script (`scripts/migrate_to_2.9.0.php`)
   - [ ] Python migration script (for automation)
   - [ ] Backup/rollback system
   
3. **Systematic File Upgrade** (v2.9.0 blocker)
   - [ ] Priority 1: Critical system files
   - [ ] Priority 2: Platform files
   - [ ] Priority 3: Content files
   - [ ] Priority 4: Legacy files
   
4. **Full System Testing** (v2.9.0 blocker)
   - [ ] Unit tests (header parser, validators)
   - [ ] Integration tests (Counting in Light, database ops)
   - [ ] System tests (full system, performance)
   - [ ] Regression tests (backward compatibility)

**RULE**: DO NOT proceed to Priority 2 until Priority 1 is 100% complete.

### Priority 2: Agent Coordination Metrics (NEW in v2.9.2)

**AFTER** v2.9.0 is complete, add agent coordination fields:

1. **Agent Coordination Header Fields**
   - [ ] `agent.coordination.primary` - Primary agent responsible for this file
   - [ ] `agent.coordination.contributors` - Array of agents who contributed
   - [ ] `agent.coordination.reviewers` - Array of agents who reviewed/approved
   - [ ] `agent.coordination.council_vote` - Record of multi-AI council decisions
   - [ ] `agent.coordination.consensus_level` - % agreement (0.00-1.00)
   - [ ] `agent.coordination.light_mood_by_agent` - Which agent proposed which color
   
2. **Context Visibility Tracking**
   - [ ] `agent.coordination.context_visibility` - % of conversation each agent saw
   - [ ] `agent.coordination.coordination_method` - manual/automated/hybrid
   - [ ] `agent.coordination.specialization` - Array of each agent's focus areas
   - [ ] `agent.coordination.utilization_count` - How many times each agent engaged

**Example**:
```yaml
agent.coordination:
  primary: CURSOR
  contributors: ["GROK", "DEEPSEEK", "CLAUDE", "GEMINI", "LILITH", "MAAT", "THOTH", "COPILOT"]
  reviewers: ["MAAT", "COPILOT"]
  council_vote: "8/8 unanimous"
  consensus_level: 1.00
  context_visibility:
    CURSOR: 0.70
    GROK: 0.60
    DEEPSEEK: 0.40
    CLAUDE: 0.50
    GEMINI: 0.45
    LILITH: 0.80
    MAAT: 0.80
    THOTH: 0.80
    COPILOT: 0.35
  coordination_method: manual
```

### Priority 3: Channel Mood Standardization (NEW in v2.9.2)

**BREAKING CHANGE**: Replace deprecated `onchannel` with `channel_mood`.

1. **Deprecated Field Removal**
   - [ ] Mark `onchannel` as DEPRECATED in validation
   - [ ] Create migration: `onchannel: 1` → `channel_mood: [hex_color]`
   - [ ] Update all documentation to use `channel_mood`
   - [ ] Add validation warning for files still using `onchannel`
   
2. **Channel Mood Hex Color Standard**
   - [ ] `channel_mood` must be 6-character hex (no #): `RRGGBB`
   - [ ] Default: `000000` (black/void) for channel 000000
   - [ ] Values: `000000`-`FFFFFF` (full RGB spectrum)
   - [ ] Maps to channel's `light_mood` from channels table

**Migration Rule**:
```yaml
# OLD (v2.9.0 and earlier)
onchannel: 1

# NEW (v2.9.2+)
channel_mood: 9370DB  # Purple for WOLFIE system channel
```

**Context**: `onchannel` was AMBIGUOUS:
- Originally meant: "Is this file on a channel? (0/1)"
- Later meant: "Which channel ID?" (1, 2, 3... 999)
- Now: "What is the channel's hex color mood?"

**Why Change**: 
- Counting in Light system needs hex colors, not IDs
- `channel_mood` is unambiguous (color, not ID or boolean)
- Enables visual channel biome detection
- Aligns with `channels.light_mood` database column

### Priority 4: Light Dictionary Integration (NEW in v2.9.2)

**PURPOSE**: Standardize color meanings across all WOLFIE installations.

1. **Light Dictionary Reference Fields**
   - [ ] `light.dictionary.mood_name` - Human name for this color (e.g., "Purple System")
   - [ ] `light.dictionary.color_family` - Color family (Red, Blue, Green, Gold, Purple, etc.)
   - [ ] `light.dictionary.emotional_signature` - What this color represents
   - [ ] `light.dictionary.recommended_use` - Context (channel, peer, migration, agent, content)
   - [ ] `light.dictionary.allow_context_override` - Can meaning change by context? (true/false)

**10 Core Standard Colors** (from `light_counting_dictionary` table):
- `000000` = The Void (null state, coordination base)
- `FFFFFF` = Pure Light (THOTH truth, complete clarity)
- `FF0000` = Critical Red (errors OR passion - context-dependent!)
- `00FF00` = Seamless Green (harmony, success, no errors)
- `FFD700` = Gold Synthesis (MAAT balance, wisdom achieved)
- `00BFFF` = Sky Blue Insight (GROK patterns, standard operation)
- `4169E1` = Royal Blue Technical (DEEPSEEK precision, database work)
- `9370DB` = Purple System (WOLFIE architecture, structural decisions)
- `FFA500` = Orange Warning (partial execution, degraded performance)
- `DC143C` = Crimson Danger (critical failure, immediate intervention)

**Example**:
```yaml
light.dictionary:
  mood_name: "Purple System"
  color_family: "Purple"
  emotional_signature: "System architecture, WOLFIE base frequency, structural decisions"
  recommended_use: "channel, dialog, system"
  allow_context_override: false  # Meaning is fixed
```

2. **Validation Against Dictionary**
   - [ ] Validate `light.count.mood` against `light_counting_dictionary` table
   - [ ] WARNING if color not in standard dictionary
   - [ ] INFO suggesting closest standard color
   - [ ] Allow custom colors (fuzzy truth) but recommend standards

### Priority 5: Peer Network Awareness (NEW in v2.9.2)

**PURPOSE**: Headers should know which LUPOPEDIA installation they're from (federated architecture).

1. **Peer Network Header Fields**
   - [ ] `peer.installation_domain` - Which LUPOPEDIA installation created this (e.g., "lupopedia.com")
   - [ ] `peer.installation_alias` - Human-readable name (e.g., "Main Platform")
   - [ ] `peer.trust_score` - Trust score of originating installation (0-100)
   - [ ] `peer.dns_verified` - DNS TXT record verified? (true/false)
   - [ ] `peer.ssl_verified` - SSL certificate verified? (true/false)
   - [ ] `peer.api_version` - API version of originating installation
   
2. **Federated Content Tracking**
   - [ ] `peer.sync_status` - Is this synced across installations? (local/synced/pending)
   - [ ] `peer.sync_timestamp` - When was this last synced? (YYYYMMDDHHIISS)
   - [ ] `peer.sync_conflicts` - Any sync conflicts? (array of conflict descriptions)

**Example**:
```yaml
peer:
  installation_domain: "lupopedia.com"
  installation_alias: "Main Platform"
  trust_score: 85
  dns_verified: true
  ssl_verified: true
  api_version: "4.0.6"
  sync_status: "synced"
  sync_timestamp: 20251203204500
  sync_conflicts: []
```

**Why This Matters**:
- Each LUPOPEDIA installation IS its own domain
- Content can be federated via REST API
- Headers need to track where content originated
- Trust metrics prevent DNS poisoning, MITM attacks

### Priority 6: Security Metrics (NEW in v2.9.2)

**PURPOSE**: Track security status for federated content.

1. **Security Header Fields**
   - [ ] `security.content_hash` - SHA256 hash of file content (verify integrity)
   - [ ] `security.signature` - Digital signature (verify authenticity)
   - [ ] `security.signed_by` - Agent/human who signed (e.g., "captain wolfie")
   - [ ] `security.signed_timestamp` - When was this signed? (YYYYMMDDHHIISS)
   - [ ] `security.verified` - Has signature been verified? (true/false)
   - [ ] `security.rate_limit_status` - Is this content rate-limited? (within_limit/exceeded)

**Example**:
```yaml
security:
  content_hash: "a7f3d2c1b5e9f8a4d6c2e1b3f7a9d5c8e2b4f6a8d1c3e5b7f9a2d4c6e8b1f3a5"
  signature: "[digital_signature_base64]"
  signed_by: "captain wolfie"
  signed_timestamp: 20251203204500
  verified: true
  rate_limit_status: "within_limit"
```

---

## 📋 COMPLETE TODO LIST FOR v2.9.2

### PHASE 0: MINIMAL VIABLE RELEASE (TODAY - December 6, 2025) 🚨

**GOAL**: Unblock dependency chain - publish npm package with basic tracking

**Timeline**: 2-4 hours

- [ ] **STEP 1**: Navigate to repository (2 min)
  - [ ] `cd C:\WOLFIE_Ontology\GITHUB_LUPOPEDIA\WOLFIE_HEADERS`
  - [ ] `git status` - ensure no uncommitted changes

- [ ] **STEP 2**: Check current state (5 min)
  - [ ] Search for scattered files mentioning tracking elements
  - [ ] Count references to collections/tags/channels/light.count
  - [ ] Document current disorganization

- [ ] **STEP 3**: Create/Update package.json (10 min)
  - [ ] Create `package.json` if missing
  - [ ] Set version to `2.9.2`
  - [ ] Add dependencies: `js-yaml@^4.1.0`, `glob@^10.3.10`
  - [ ] Add scripts: `test`, `validate`
  - [ ] Run `npm install` to add dependencies

- [ ] **STEP 4**: Create Entry Point (index.js) with Tracking Features (20 min)
  - [ ] Create `index.js` with `WolfieHeadersTracker` class
  - [ ] Implement `scan()` method to parse multiple file types (.md, .py, .php, .js, .ts)
  - [ ] **Parse headers from comment blocks** (Python `# ---`, PHP `/*---*/`, Markdown `---`)
  - [ ] Extract YAML from comment-delimited blocks (flexible regex)
  - [ ] Track collections (count usage, uniqueness)
  - [ ] Track tags (count usage, identify orphans)
  - [ ] Track file contents (via `in_this_file_we_have` or text snippet)
  - [ ] Track channels (group files by channel_number/onchannel/channel_mood)
  - [ ] Track Counting in Light fields (light.count.*, light.global.*)
  - [ ] Implement 5D resonance calculation (from DIALOG.md formula)
  - [ ] Add `getReport()` method to export tracked data as JSON

- [ ] **STEP 5**: Add Minimal Validation Script (15 min)
  - [ ] Create `validate.js` script
  - [ ] Check for required fields (light.count.*)
  - [ ] Output errors for missing fields
  - [ ] Exit code 0/1 for CI/CD compatibility

- [ ] **STEP 6**: Test Locally (10 min)
  - [ ] Run `npm pack` to create .tgz file
  - [ ] Test install in clean directory
  - [ ] Test tracker: `require('wolfie-headers').scan()`
  - [ ] Test validation: `node validate.js ./docs`

- [ ] **STEP 7**: Choose Package Registry (5 min)
  - [ ] Option A: Public npm (recommended for now)
  - [ ] Option B: GitHub Packages (if private needed)
  - [ ] Login to chosen registry

- [ ] **STEP 8**: Publish to npm (5 min)
  - [ ] Run `npm publish --access public` (or GitHub Packages)
  - [ ] Verify: `npm view wolfie-headers version` shows `2.9.2`

- [ ] **STEP 9**: Commit and Tag in Git (5 min)
  - [ ] `git add .`
  - [ ] `git commit -m "Finalize 2.9.2 – unblocks crafty_syntax 3.8.0"`
  - [ ] `git tag -a v2.9.2 -m "WOLFIE Headers 2.9.2 - Minimal viable release"`
  - [ ] `git push origin main --tags`

- [ ] **STEP 10**: Verify Publication (5 min)
  - [ ] Test install: `npm install wolfie-headers@2.9.2` in clean project
  - [ ] Verify package contents
  - [ ] Confirm dependency chain unblocked

**SUCCESS CRITERIA**:
- ✅ Package published to npm
- ✅ Installable via `npm install wolfie-headers@2.9.2`
- ✅ Git tagged as `v2.9.2` and pushed
- ✅ crafty_syntax can add dependency

**POST-PUBLICATION**:
- [ ] Run tracker on whole repo → Save to `tracked_data.json`
- [ ] Identify orphans (unused tags/collections)
- [ ] Document tracking capabilities for downstream projects

### PHASE 0.5: Universal Header Schema Documentation (TODAY - December 6, 2025)

**CRITICAL**: Must be included in 2.9.2 minimal release

1. **Document Universal Header Schema**
   - [ ] Create `docs/UNIVERSAL_HEADER_SCHEMA.md` with full specification
   - [ ] Document Python format (`# ---` comment blocks)
   - [ ] Document PHP format (`/*---*/` comment blocks)
   - [ ] Document Markdown format (YAML frontmatter `---`)
   - [ ] Document JavaScript/TypeScript format (`/*---*/` comment blocks)
   - [ ] Add examples for each file type
   - [ ] Document parser flexibility requirements
   - [ ] Add to README.md as core requirement

2. **Update Tracker Implementation**
   - [ ] Update `index.js` to parse comment blocks (not just YAML frontmatter)
   - [ ] Add regex patterns for Python, PHP, Markdown, JS/TS
   - [ ] Test with sample files of each type
   - [ ] Document parser behavior in code comments

### PHASE 0.5: Universal Header Schema Documentation (TODAY - December 6, 2025)

**CRITICAL**: Must be included in 2.9.2 minimal release

1. **Document Universal Header Schema**
   - [ ] Create `docs/UNIVERSAL_HEADER_SCHEMA.md` with full specification
   - [ ] Document Python format (`# ---` comment blocks)
   - [ ] Document PHP format (`/*---*/` comment blocks)
   - [ ] Document Markdown format (YAML frontmatter `---`)
   - [ ] Document JavaScript/TypeScript format (`/*---*/` comment blocks)
   - [ ] Add examples for each file type
   - [ ] Document parser flexibility requirements
   - [ ] Add to README.md as core requirement

2. **Update Tracker Implementation**
   - [ ] Update `index.js` to parse comment blocks (not just YAML frontmatter)
   - [ ] Add regex patterns for Python, PHP, Markdown, JS/TS
   - [ ] Test with sample files of each type
   - [ ] Document parser behavior in code comments

### PHASE 1: Specification & Documentation (Future - After 2.9.2 Published)

1. **Update WOLFIE Headers Specification**
   - [ ] Add agent coordination fields to specification
   - [ ] Add peer network fields to specification
   - [ ] Add security fields to specification
   - [ ] Add channel_mood standardization
   - [ ] Add light dictionary integration
   - [ ] Document all new fields with examples
   
2. **Update Header Template**
   - [ ] Add `agent.coordination.*` structure to template
   - [ ] Add `peer.*` structure to template
   - [ ] Add `security.*` structure to template
   - [ ] Add `light.dictionary.*` structure to template
   - [ ] Replace `onchannel` with `channel_mood` in template
   - [ ] Add comments explaining each field
   - [ ] **Add Universal Header Schema examples** (Python, PHP, Markdown formats)
   
3. **Create Migration Guide**
   - [ ] Document v2.9.0 → v2.9.2 migration path
   - [ ] Document deprecated `onchannel` → `channel_mood` migration
   - [ ] Document agent coordination field additions
   - [ ] Document peer network field additions
   - [ ] **Document Universal Header Schema migration** (from YAML-only to comment-based)
   - [ ] Provide PHP/Python migration examples

### PHASE 2: Validation System Update

1. **Update Validation Scripts**
   - [ ] Add validation for `agent.coordination.*` fields
   - [ ] Add validation for `peer.*` fields
   - [ ] Add validation for `security.*` fields
   - [ ] Add validation for `light.dictionary.*` fields
   - [ ] Add validation for `channel_mood` (hex format check)
   - [ ] Add DEPRECATION WARNING for `onchannel`
   - [ ] Add validation against `light_counting_dictionary` table
   
2. **Update Validation Reports**
   - [ ] Report files using deprecated `onchannel`
   - [ ] Report files missing agent coordination fields
   - [ ] Report files with non-standard light moods (INFO level)
   - [ ] Report files without peer network info (federated installs only)

### PHASE 3: Migration System Update

1. **Create v2.9.2 Migration Script**
   - [ ] Migrate `onchannel` → `channel_mood` (lookup from channels table)
   - [ ] Add default `agent.coordination.*` fields
   - [ ] Add default `peer.*` fields (if federated)
   - [ ] Add default `light.dictionary.*` fields (lookup from dictionary)
   - [ ] Add default `security.*` fields
   - [ ] Update `wolfie.headers.version` to 2.9.2
   - [ ] Increment `light.count.touch` and `light.global.touch`
   
2. **Create Rollback Capability**
   - [ ] v2.9.2 → v2.9.0 rollback script (if needed)
   - [ ] Backup system verification
   - [ ] Test rollback on sample files

### PHASE 4: Database Integration

1. **Integrate with `light_counting_dictionary` Table**
   - [ ] Query dictionary for color meanings
   - [ ] Auto-populate `light.dictionary.*` fields from database
   - [ ] Cache dictionary lookups for performance
   
2. **Integrate with `agents_active` Table**
   - [ ] Query agents table for agent metadata
   - [ ] Auto-populate `agent.coordination.context_visibility` from database
   - [ ] Auto-populate `agent.coordination.specialization` from database
   
3. **Integrate with `peer_network` Table**
   - [ ] Query peer network for installation metadata
   - [ ] Auto-populate `peer.*` fields from database
   - [ ] Update peer trust scores based on header security metrics
   
4. **Integrate with `channels` Table**
   - [ ] Lookup channel `light_mood` for `channel_mood` migration
   - [ ] Verify channel exists before setting `channel_mood`
   - [ ] Default to `000000` if channel not found

### PHASE 5: Incremental File Upgrade

**AFTER** v2.9.0 is 100% complete:

1. **Priority 1: Recent Files (December 2025)**
   - [ ] Files created/modified during channel architecture work
   - [ ] Migration files (1115-1124)
   - [ ] DATABASE.md
   - [ ] DIALOG.md
   - [ ] Channel architecture documentation
   
2. **Priority 2: Agent Files**
   - [ ] VISHWAKARMA agent files (already updated to 2.9.0)
   - [ ] Other agent repository files
   - [ ] Agent profile pages
   
3. **Priority 3: System Files**
   - [ ] Core LUPOPEDIA files
   - [ ] WOLFIE Headers repository files
   - [ ] Configuration files
   
4. **Priority 4: Content Files**
   - [ ] Patreon entries
   - [ ] Blog posts
   - [ ] Documentation

### PHASE 6: Testing

1. **Unit Tests**
   - [ ] Test agent coordination field parsing
   - [ ] Test peer network field parsing
   - [ ] Test security field parsing
   - [ ] Test light dictionary integration
   - [ ] Test `onchannel` → `channel_mood` migration
   
2. **Integration Tests**
   - [ ] Test with `light_counting_dictionary` table
   - [ ] Test with `agents_active` table
   - [ ] Test with `peer_network` table
   - [ ] Test with `channels` table
   - [ ] Test multi-agent council tracking
   
3. **System Tests**
   - [ ] Test full system with v2.9.2 headers
   - [ ] Test federated content sync
   - [ ] Test security verification
   - [ ] Test backward compatibility (v2.9.0 files still work)

### PHASE 7: Documentation

1. **Create v2.9.2 Documentation**
   - [ ] CHANGELOG.md entry for v2.9.2
   - [ ] VERSION_2.9.2_STATUS_SUMMARY.md
   - [ ] Migration guide (v2.9.0 → v2.9.2)
   - [ ] Agent coordination guide
   - [ ] Light dictionary integration guide
   - [ ] Peer network federation guide
   
2. **Update Public Pages**
   - [ ] Update `what_are_wolfie_headers.php` with v2.9.2 features
   - [ ] Update examples to show agent coordination
   - [ ] Add peer network awareness examples
   - [ ] Add light dictionary examples

### PHASE 8: Deployment

1. **Pre-Deployment Checklist**
   - [ ] All v2.9.0 blockers resolved
   - [ ] All v2.9.2 files upgraded
   - [ ] All tests passing
   - [ ] Documentation complete
   - [ ] Migration scripts tested
   - [ ] Rollback plan ready
   
2. **Deployment**
   - [ ] Deploy validation scripts
   - [ ] Deploy migration scripts
   - [ ] Upgrade all files to v2.9.2
   - [ ] Verify database integrations working
   - [ ] Monitor for issues
   
3. **Post-Deployment**
   - [ ] Announce v2.9.2 release
   - [ ] Update GitHub release notes
   - [ ] Update all documentation references
   - [ ] Mark v2.9.2 as "Stable"

---

## 🔍 DETAILED FIELD SPECIFICATIONS

### 1. Agent Coordination Fields

**Purpose**: Track multi-agent collaboration on each file.

```yaml
agent.coordination:
  primary: [agent_name]  # Primary agent responsible (e.g., "CURSOR", "GROK")
  contributors: [array]  # All agents who contributed (e.g., ["GROK", "DEEPSEEK", "CLAUDE"])
  reviewers: [array]  # Agents who reviewed/approved (e.g., ["MAAT", "COPILOT"])
  council_vote: [string]  # Vote result (e.g., "8/8 unanimous", "6/8 approved")
  consensus_level: [float]  # Agreement level 0.00-1.00 (e.g., 1.00 = unanimous, 0.75 = 75% agreement)
  light_mood_by_agent: [object]  # Which agent proposed which color (e.g., {GROK: "00BFFF", DEEPSEEK: "4169E1"})
  context_visibility: [object]  # % context each agent saw (e.g., {CURSOR: 0.70, DEEPSEEK: 0.40})
  coordination_method: [string]  # manual/automated/hybrid
  specialization: [object]  # Each agent's focus (e.g., {DEEPSEEK: "security,technical"})
  utilization_count: [object]  # Engagement count (e.g., {GROK: 5, DEEPSEEK: 3})
```

**Validation Rules**:
- `primary`: Must be valid agent name (check against `agents_active` table)
- `contributors`: Array of valid agent names
- `reviewers`: Array of valid agent names
- `council_vote`: Format "[approved]/[total] [result]" (e.g., "8/8 unanimous")
- `consensus_level`: Float 0.00-1.00
- `context_visibility`: Object with agent names as keys, floats 0.00-1.00 as values
- `coordination_method`: Enum (manual/automated/hybrid)

### 2. Channel Mood Field

**Purpose**: Replace deprecated `onchannel` with unambiguous hex color.

```yaml
# OLD (DEPRECATED)
onchannel: 1  # AMBIGUOUS: boolean? channel ID? 

# NEW (v2.9.2+)
channel_mood: 9370DB  # UNAMBIGUOUS: hex color (6 chars, no #)
```

**Validation Rules**:
- Format: `RRGGBB` (6 hex characters, no #)
- Range: `000000`-`FFFFFF`
- Default: `000000` (black/void) for undefined channels
- Must match `channels.light_mood` from database (if channel exists)

**Migration Logic**:
```php
// If onchannel exists, migrate to channel_mood
if (isset($header['onchannel'])) {
    $channel_id = $header['onchannel'];
    // Lookup channel from database
    $channel = $db->query("SELECT light_mood FROM channels WHERE id = ?", [$channel_id]);
    $header['channel_mood'] = $channel['light_mood'] ?? '000000';  // Default to void
    // WARN: onchannel is deprecated
    $warnings[] = "onchannel is DEPRECATED, migrated to channel_mood";
}
```

### 3. Light Dictionary Fields

**Purpose**: Standardize color meanings across installations.

```yaml
light.dictionary:
  mood_name: [string]  # Human name (e.g., "Purple System")
  color_family: [string]  # Family (e.g., "Purple", "Blue", "Red")
  emotional_signature: [string]  # What it represents (e.g., "System architecture")
  recommended_use: [string]  # Context (e.g., "channel, dialog, system")
  allow_context_override: [boolean]  # Can meaning change? (true/false)
```

**Validation Rules**:
- All fields optional (auto-populated from `light_counting_dictionary` table)
- If `light.count.mood` is in dictionary, auto-populate these fields
- If `light.count.mood` is NOT in dictionary, leave empty and log INFO message
- `allow_context_override`: Boolean (true = context can change meaning, false = fixed)

**Auto-Population Logic**:
```php
// Lookup color in dictionary
$mood = $header['light']['count']['mood'] ?? null;
if ($mood) {
    $dict = $db->query("SELECT * FROM light_counting_dictionary WHERE hex_color = ?", [$mood]);
    if ($dict) {
        $header['light']['dictionary'] = [
            'mood_name' => $dict['mood_name'],
            'color_family' => $dict['color_family'],
            'emotional_signature' => $dict['emotional_signature'],
            'recommended_use' => $dict['recommended_use'],
            'allow_context_override' => (bool)$dict['allow_context_override']
        ];
    } else {
        // Color not in standard dictionary
        $info[] = "Color {$mood} not in standard dictionary. Consider using standard colors.";
    }
}
```

### 4. Peer Network Fields

**Purpose**: Track federated content origin and trust.

```yaml
peer:
  installation_domain: [string]  # Origin domain (e.g., "lupopedia.com")
  installation_alias: [string]  # Human name (e.g., "Main Platform")
  trust_score: [integer]  # 0-100 trust score
  dns_verified: [boolean]  # DNS TXT record verified?
  ssl_verified: [boolean]  # SSL certificate verified?
  api_version: [string]  # API version (e.g., "4.0.6")
  sync_status: [string]  # local/synced/pending
  sync_timestamp: [integer]  # YYYYMMDDHHIISS
  sync_conflicts: [array]  # Conflict descriptions
```

**Validation Rules**:
- `installation_domain`: Valid domain format (e.g., "example.com")
- `trust_score`: Integer 0-100
- `dns_verified`: Boolean
- `ssl_verified`: Boolean
- `api_version`: Semver format (e.g., "4.0.6")
- `sync_status`: Enum (local/synced/pending)
- `sync_timestamp`: YYYYMMDDHHIISS format
- `sync_conflicts`: Array of strings

**Auto-Population Logic** (for federated installs):
```php
// Get current installation info from config
$local_domain = getConfig('installation_domain');  // e.g., "lupopedia.com"
$local_alias = getConfig('installation_alias');    // e.g., "Main Platform"

// Populate peer fields
$header['peer'] = [
    'installation_domain' => $local_domain,
    'installation_alias' => $local_alias,
    'trust_score' => 100,  // Self-trust = 100
    'dns_verified' => true,
    'ssl_verified' => true,
    'api_version' => LUPOPEDIA_VERSION,  // e.g., "4.0.6"
    'sync_status' => 'local',  // Created locally, not synced
    'sync_timestamp' => date('YmdHis'),
    'sync_conflicts' => []
];
```

### 5. Security Fields

**Purpose**: Verify content integrity and authenticity.

```yaml
security:
  content_hash: [string]  # SHA256 hash of content
  signature: [string]  # Digital signature (base64)
  signed_by: [string]  # Who signed (e.g., "captain wolfie")
  signed_timestamp: [integer]  # When signed (YYYYMMDDHHIISS)
  verified: [boolean]  # Signature verified?
  rate_limit_status: [string]  # within_limit/exceeded
```

**Validation Rules**:
- `content_hash`: 64-character hex string (SHA256)
- `signature`: Base64 string
- `signed_by`: Valid username (human or agent)
- `signed_timestamp`: YYYYMMDDHHIISS format
- `verified`: Boolean
- `rate_limit_status`: Enum (within_limit/exceeded)

**Auto-Population Logic**:
```php
// Calculate content hash (excluding security section to avoid circular dependency)
$content = getFileContentWithoutSecuritySection($file_path);
$hash = hash('sha256', $content);

// Sign content (if private key available)
$signature = null;
if ($private_key = getPrivateKey()) {
    openssl_sign($content, $signature, $private_key, OPENSSL_ALGO_SHA256);
    $signature = base64_encode($signature);
}

$header['security'] = [
    'content_hash' => $hash,
    'signature' => $signature,
    'signed_by' => getCurrentUser(),  // e.g., "captain wolfie" or "CURSOR"
    'signed_timestamp' => date('YmdHis'),
    'verified' => false,  // Will be verified by receiver
    'rate_limit_status' => 'within_limit'
];
```

---

## 📊 MIGRATION PATH SUMMARY

### From v2.9.0 to v2.9.2

**Step 1: Complete v2.9.0** ⚠️ REQUIRED FIRST
- Finish all v2.9.0 validation/migration/testing/deployment
- Mark v2.9.0 as "Stable"

**Step 2: Add New Fields**
- Add `agent.coordination.*` structure
- Add `peer.*` structure (federated installs only)
- Add `security.*` structure
- Add `light.dictionary.*` structure (auto-populated)
- Migrate `onchannel` → `channel_mood`

**Step 3: Update Version**
- `wolfie.headers.version: 2.9.2`
- Increment `light.count.touch` and `light.global.touch`
- Update `last.modified` timestamp

**Step 4: Validate**
- Run v2.9.2 validation scripts
- Check for deprecated `onchannel` usage
- Verify all new fields present
- Verify database integrations working

**Step 5: Test**
- Unit tests for new fields
- Integration tests with database tables
- System tests for full functionality
- Backward compatibility tests (v2.9.0 files still work)

---

## 🎯 SUCCESS CRITERIA

v2.9.2 is considered **COMPLETE** when:

1. ✅ v2.9.0 is 100% complete and stable
2. ✅ All new field specifications documented
3. ✅ Validation scripts updated and tested
4. ✅ Migration scripts updated and tested
5. ✅ Database integrations working
6. ✅ All files upgraded to v2.9.2
7. ✅ All tests passing (unit, integration, system)
8. ✅ Documentation complete
9. ✅ No deprecated `onchannel` fields remain
10. ✅ Peer network awareness functional (federated installs)
11. ✅ Agent coordination tracking functional
12. ✅ Light dictionary integration functional
13. ✅ Security verification functional

---

## 📚 RELATED FILES

- `TODO_2.9.0.md` - v2.9.0 implementation plan (must complete first)
- `VERSION_2.9.0_STATUS_SUMMARY.md` - v2.9.0 status (check completion)
- `CHANGELOG.md` - Version history (will add v2.9.2 entry)
- `templates/header_template.yaml` - Header template (will update for v2.9.2)
- `README.md` - Main README (will update for v2.9.2)

**Database Tables Referenced**:
- `light_counting_dictionary` - Standard color semantics
- `agents_active` - Agent metadata for coordination tracking
- `peer_network` - Federated installation metadata
- `channels` - Channel light_mood for channel_mood migration

**Migration Files Referenced**:
- `1115_add_counting_in_light_to_channels.sql` - Added light_mood to channels
- `1102_create_agents_active_tracking.sql` - Tracks 14+ AI agents
- `1121_create_peer_network_table.sql` - Federated peer discovery
- `1123_secure_peer_network.sql` - Security hardening
- `1124_create_light_counting_dictionary.sql` - Color semantics standard

---

**Last Updated**: 2025-12-06  
**Status**: 🚨 CRITICAL - Minimal viable release in progress  
**Next Milestone**: Publish npm package TODAY to unblock dependency chain

---

## 📑 UNIVERSAL HEADER REQUIREMENT (2.9.2)

To ensure portability, resilience, and language-agnostic consistency, **all WOLFIE Headers MUST be embedded as static metadata blocks at the top of files**. These headers are **non-executable** and serve as a universal schema that can be parsed by any tool (JavaScript, Python, PHP, or manual inspection).

### Core Rules

- **Placement**: Always at the beginning of the file, before any code or content.

- **Format**:
  - For Markdown and documentation files → YAML block (`--- ... ---`).
  - For code files (Python, PHP, JS, etc.) → YAML-style fields inside comment blocks.

- **Content**: Must include required fields for versioning, context, and Counting in Light.

- **Portability**: Headers must remain valid even if no parser is available; they are human-readable metadata first, machine-parsable second.

- **Validation**: The npm package (2.9.2) provides a parser/validator, but headers themselves are **independent of execution environments**.

### Example in Python

```python
# ---
# wolfie.headers.version: 2.9.2
# wolfie.headers.branch: production
# context.what.parent: "Counting in Light"
# context.what.child: "Validation Protocols"
# light.global.base: "#00BFFF"
# light.global.real: 777
# light.global.imag: 0
# light.global.touch: 1
# light.count.base: 777
# light.count.mood: 00BFFF
# light.count.touch: 1
# title: example.py
# human.username: captain wolfie
# agent.username: cursor
# tags: [SYSTEM, PLAN, WOLFIE_HEADERS]
# collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO]
# in_this_file_we_have: [EXECUTIVE_SUMMARY, IMPLEMENTATION_STEPS]
# ---
```

### Example in Markdown

```markdown
---
wolfie.headers.version: 2.9.2
wolfie.headers.branch: production
context.what.parent: "Counting in Light"
context.what.child: "Validation Protocols"
light.global.base: "#00BFFF"
light.global.real: 777
light.global.imag: 0
light.global.touch: 1
light.count.base: 777
light.count.mood: 00BFFF
light.count.touch: 1
title: example.md
human.username: captain wolfie
agent.username: cursor
tags: [SYSTEM, PLAN, WOLFIE_HEADERS]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO]
in_this_file_we_have: [EXECUTIVE_SUMMARY, IMPLEMENTATION_STEPS]
---
```

### Example in PHP

```php
<?php
/*
---
wolfie.headers.version: 2.9.2
wolfie.headers.branch: production
context.what.parent: "Counting in Light"
context.what.child: "Validation Protocols"
light.global.base: "#00BFFF"
light.global.real: 777
light.global.imag: 0
light.global.touch: 1
light.count.base: 777
light.count.mood: 00BFFF
light.count.touch: 1
title: example.php
human.username: captain wolfie
agent.username: cursor
tags: [SYSTEM, PLAN, WOLFIE_HEADERS]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO]
in_this_file_we_have: [EXECUTIVE_SUMMARY, IMPLEMENTATION_STEPS]
---
*/
?>
```

### Verification

- ✅ Headers exist in every file, in comment/YAML format.
- ✅ Required fields (`wolfie.headers.version`, `context.what.parent/child`, `light.global.*`, `light.count.*`) are present.
- ✅ Headers are human-readable and machine-parsable.
- ✅ JS tracker (2.9.2) can parse them, but headers remain valid without JS execution.

### Developer Checklist

When adding headers to new files, verify:

- [ ] **Placement**: Header block is at the very top of the file (before any code/content)
- [ ] **Format**: 
  - [ ] Markdown: Uses `---` YAML frontmatter (no comments)
  - [ ] Python: Uses `# ---` comment block
  - [ ] PHP: Uses `/*---*/` comment block
  - [ ] JavaScript/TypeScript: Uses `/*---*/` comment block
- [ ] **Required Fields Present**:
  - [ ] `wolfie.headers.version: 2.9.2`
  - [ ] `context.what.parent: "..."` (required for 2.9.2)
  - [ ] `context.what.child: "..."` (required for 2.9.2)
  - [ ] `light.global.base: "#..."` (hex color)
  - [ ] `light.global.real: 777` (or appropriate value)
  - [ ] `light.global.imag: 0` (or appropriate value)
  - [ ] `light.global.touch: 1` (increment on edits)
  - [ ] `light.count.offset: 0` (or appropriate value)
  - [ ] `light.count.base: 777` (or appropriate value)
  - [ ] `light.count.name: "..."` (quoted string)
  - [ ] `light.count.mood: "..."` (6-char hex, no #)
  - [ ] `light.count.touch: 1` (increment on edits)
- [ ] **Optional but Recommended**:
  - [ ] `title: "..."`
  - [ ] `human.username: "captain wolfie"` (or appropriate)
  - [ ] `agent.username: "..."` (if applicable)
  - [ ] `tags: [...]` (array)
  - [ ] `collections: [...]` (array)
  - [ ] `in_this_file_we_have: [...]` (array)
- [ ] **Human-Readable**: Can be read and understood without any parser
- [ ] **Machine-Parsable**: JS tracker can extract and validate the header
- [ ] **Touch Counters**: `light.global.touch` and `light.count.touch` incremented on every edit

**Quick Test**: Open the file in a plain text editor. If you can read and understand the header without running any code, it's compliant.

---

## 📦 TRACKING FUNCTIONALITY (New in v2.9.2)

### Tracking Requirements

**Problem**: Files are scattered across `docs/`, `public/`, `templates/`, and root. Tracking is implicit in YAML frontmatter but not centralized or queryable.

**Solution**: JavaScript module (`WolfieHeadersTracker`) that:
- Scans multiple file types (.md, .py, .php, .js, .ts) in a folder
- Parses headers from comment blocks (Python `# ---`, PHP `/*---*/`) or YAML frontmatter (Markdown `---`)
- Builds centralized index of tracked elements
- Provides queryable data structure

### Tracked Elements

1. **Collections** (`collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO]`)
   - Count usage frequency
   - Track uniqueness
   - Identify cross-file consistency

2. **Tags** (`tags: [SYSTEM, TODO, WOLFIE_HEADERS]`)
   - Count usage frequency
   - Identify orphans (unused tags)
   - Track channel-specific variants

3. **File Contents** (`in_this_file_we_have: [...]` or full text)
   - Parse post-YAML MD content
   - Summarize via `in_this_file_we_have` or text snippet
   - Track sections, summaries

4. **Channels** (`channel_number`, `onchannel`, `channel_mood`)
   - Group files by channel
   - Track channel mappings
   - Calculate resonance (via Counting in Light distances)

5. **Counting in Light** (`light.count.*`, `light.global.*`)
   - Track resonance coherence
   - Monitor touch increments
   - Calculate distances (basic formula from DIALOG.md)

### Implementation Code (index.js)

**Full implementation for STEP 4:**

```javascript
const fs = require('fs');
const path = require('path');
const yaml = require('js-yaml');
const glob = require('glob');

// Main class for tracking
class WolfieHeadersTracker {
  constructor(folderPath = '.') {
    this.folderPath = folderPath;
    this.trackedData = { 
      collections: {}, 
      tags: {}, 
      files: {}, 
      channels: {}, 
      lightCounts: {} 
    };
  }

  // Parse headers from various comment formats
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

  // Scan multiple file types and parse headers from comments or YAML
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
```

### Usage Example

**Create `test.js` for quick testing:**

```javascript
const Tracker = require('./index');

const tracker = new Tracker('./docs'); // Scan docs/ folder
const data = tracker.scan();

// Access tracked data
console.log('Collections:', data.collections);  // { WHO: 45, WHAT: 38, ... }
console.log('Tags:', data.tags);               // { SYSTEM: 120, TODO: 67, ... }
console.log('Files:', Object.keys(data.files).length, 'files tracked');
console.log('Channels:', Object.keys(data.channels).length, 'channels found');
console.log('Light Counts:', Object.keys(data.lightCounts).length, 'files with Counting in Light');

// Export report
const report = tracker.getReport();  // JSON string
fs.writeFileSync('tracked_data.json', report);
```

**Run**: `node test.js`

### Validation Script (validate.js)

**Full implementation for STEP 5:**

```javascript
const Tracker = require('./index');

const tracker = new Tracker(process.argv[2] || '.');
tracker.scan();

// Basic validation: Check for missing required fields
let valid = true;
Object.keys(tracker.trackedData.lightCounts).forEach(file => {
  const light = tracker.trackedData.lightCounts[file];
  if (!light.offset || !light.base || !light.name || !light.mood || !light.touch) {
    console.error(`Validation failed in ${file}: Missing light.count fields`);
    valid = false;
  }
});

process.exit(valid ? 0 : 1); // For CI/CD
```

**Run**: `node validate.js ./docs` → Outputs errors if files lack fields.

### Basic Resonance Calculation

Placeholder formula (expand in v2.9.3):

```javascript
calculateResonance(light) {
  return Math.sqrt(
    Math.pow(light.offset || 0, 2) + 
    Math.pow(light.base || 0, 2)
    // Add more dimensions as needed (R/G/B, real/imag)
  );
}
```

**Future Enhancement**: Full resonance formula from DIALOG.md with channel/global dimensions.

### Post-Publication Quick Wins

After 2.9.2 is published:

1. **Centralize Tracking**: Run tracker on whole repo → Save to `tracked_data.json`
2. **Fix Scattered Files**: Use report to identify orphans and consolidate duplicates
3. **Future Releases**: v2.9.3 can add full validation, migration, and advanced tracking

---

**THE RULE**: Minimal viable release FIRST - unblock dependency chain. Perfect later. 🚀


