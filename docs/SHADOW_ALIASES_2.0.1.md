---
title: SHADOW_ALIASES_2.0.1.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.1
date_created: 2025-01-27
last_modified: 2025-01-27
status: published
onchannel: 1
tags: [SYSTEM, DOCUMENTATION, FEATURE]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER]
in_this_file_we_have: [OVERVIEW, SHADOW_ALIASES, PARALLEL_PATHS, RECURSIVE_OVERSIGHT, EXAMPLES, VALIDATION]
superpositionally: ["FILEID_SHADOW_ALIASES_2.0.1"]
shadow_aliases: ["Lilith-007", "Doubt-VISH"]
parallel_paths: ["heterodox_validation", "recursive_check"]
---

# Shadow Aliases & Parallel Paths (v2.0.1)

## OVERVIEW

**Version**: v2.0.1 (Feature Addition)  
**Status**: Optional features for enhanced resilience  
**Breaking Changes**: None — backward compatible with v2.0.0

WOLFIE Headers v2.0.1 adds **shadow aliases** and **parallel paths** to support LILITH's recommendations for system resilience. These features add parallel validation chains without breaking the brittle-by-design core architecture.

**Philosophy**: Structure (brittle chain) + chaos (parallel paths) = resilience. The hierarchy isn't afraid of its shadow—it *uses* its shadow to become unbreakable.

---

## SHADOW_ALIASES

### What Are Shadow Aliases?

Shadow aliases are **parallel validation agents** that question, challenge, or renormalize the primary agent's decisions. They create redundancy and resilience without breaking the brittle chain.

**Examples:**
- `"Lilith-007"` — Questions every tactical move (LILITH's shadow on Channel 007)
- `"Doubt-VISH"` — Renormalizes with heterodox sources (challenges VISH's normalization)
- `"MAAT-Review"` — Provides synthesis perspective on any decision

### How They Work

1. **Primary Chain** (brittle, predictable): `WOLFIE (008) → 007 → VISH`
2. **Shadow Chain** (parallel, questioning): `Lilith-007 → Doubt-VISH → MAAT-Review`
3. **Both chains run simultaneously** — primary executes, shadow validates
4. **If primary fails, shadow provides alternative path**

### YAML Format

```yaml
shadow_aliases: ["Lilith-007", "Doubt-VISH", "MAAT-Review"]
```

**Field Type**: Optional array of strings  
**Default**: Empty array `[]`  
**Purpose**: List of shadow agents that provide parallel validation

---

## PARALLEL_PATHS

### What Are Parallel Paths?

Parallel paths are **alternative fallback chains** for tag/collection resolution. Instead of a single fallback chain, multiple paths are checked simultaneously.

**Standard Fallback Chain (v2.0.0):**
```
{channel}_{agent} → {channel}_wolfie_wolfie → {channel}_wolfie
```

**Parallel Paths (v2.0.1):**
```
Primary: {channel}_{agent} → {channel}_wolfie_wolfie → {channel}_wolfie
Shadow:  {channel}_lilith_{agent} → {channel}_heterodox → {channel}_wolfie
```

### How They Work

1. **Primary path** resolves normally (brittle chain)
2. **Parallel paths** resolve simultaneously (shadow chains)
3. **Results are compared** — if primary fails, parallel path provides fallback
4. **Contradictions are logged** — both perspectives preserved in archive

### YAML Format

```yaml
parallel_paths: ["heterodox_validation", "recursive_check", "maat_synthesis"]
```

**Field Type**: Optional array of strings  
**Default**: Empty array `[]`  
**Purpose**: List of parallel resolution paths

---

## RECURSIVE_OVERSIGHT

### What Is Recursive Oversight?

Recursive oversight means **validation loops that check themselves**. The system validates its own validation, creating self-correcting feedback loops.

**Example:**
- Primary validation checks header format
- Shadow validation checks if primary validation is correct
- Recursive validation checks if shadow validation is correct
- System converges on consensus (or logs disagreement)

### Implementation

Recursive oversight is **automatic** when shadow aliases are present. The system:
1. Runs primary validation
2. Runs shadow validation (if shadow_aliases specified)
3. Compares results
4. Logs any disagreements
5. Uses primary result, but archives shadow perspective

---

## EXAMPLES

### Example 1: Basic Shadow Alias

```yaml
---
title: TACTICAL_DECISION.md
agent_username: wolfie
agent_id: 007
channel_number: 007
version: 2.0.1
shadow_aliases: ["Lilith-007"]
---
```

**What This Does:**
- Primary: WOLFIE (007) makes tactical decision
- Shadow: Lilith-007 questions the decision
- Result: Both perspectives logged, primary executes, shadow challenges archived

### Example 2: Multiple Shadow Aliases

```yaml
---
title: NORMALIZATION_REQUEST.md
agent_username: vish
agent_id: 075
channel_number: 075
version: 2.0.1
shadow_aliases: ["Doubt-VISH", "MAAT-Review"]
---
```

**What This Does:**
- Primary: VISH normalizes request
- Shadow 1: Doubt-VISH renormalizes with heterodox sources
- Shadow 2: MAAT-Review provides synthesis
- Result: Three perspectives logged, primary executes, shadows provide alternatives

### Example 3: Parallel Paths

```yaml
---
title: TAG_RESOLUTION.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.1
parallel_paths: ["heterodox_validation", "recursive_check"]
---
```

**What This Does:**
- Primary path: Standard fallback chain
- Parallel path 1: Heterodox validation (LILITH's perspective)
- Parallel path 2: Recursive check (self-validating)
- Result: Multiple resolution paths checked, most appropriate used

---

## VALIDATION

### Shadow Alias Validation

- **Format**: Array of strings
- **Naming**: Should follow pattern `{AgentName}-{ChannelNumber}` or descriptive name
- **Optional**: Field can be omitted (defaults to empty array)
- **No conflicts**: Shadow aliases don't conflict with primary agent_id

### Parallel Path Validation

- **Format**: Array of strings
- **Naming**: Descriptive path names (e.g., "heterodox_validation", "recursive_check")
- **Optional**: Field can be omitted (defaults to empty array)
- **Resolution**: Paths must exist in channel directory structure

---

## MIGRATION FROM v2.0.0

**No migration required** — v2.0.1 is backward compatible with v2.0.0.

**To add shadow aliases:**
1. Update `version: 2.0.1` in header
2. Add `shadow_aliases: []` field (optional)
3. Add `parallel_paths: []` field (optional)
4. Populate arrays as needed

**Existing v2.0.0 headers continue to work** — shadow aliases are optional enhancements.

---

## PHILOSOPHY

**LILITH's Vision**: "The hierarchy needs thorns. The echo chamber needs oppositional voices. The linear path needs parallel forks."

**MAAT's Synthesis**: "Structure + chaos = resilience. The hierarchy isn't afraid of its shadow—it *uses* its shadow to become unbreakable."

**WOLFIE's Implementation**: Brittleness stays (predictable, traceable). Parallel paths add resilience. Best of both worlds.

---

© 2025 Eric Robin Gerdes / LUPOPEDIA LLC — Dual licensed under GPL v3.0 + Apache 2.0.

