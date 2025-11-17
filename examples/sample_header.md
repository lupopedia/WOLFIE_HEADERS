---
title: sample_header.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.0
date_created: 2025-01-27
last_modified: 2025-01-27
status: example
onchannel: 1
tags: [SYSTEM, DOCUMENTATION]
collections: [WHAT, HOW]
in_this_file_we_have: [INTRO, IMPLEMENTATION_NOTES]
superpositionally: ["FILEID_SAMPLE_HEADER"]
---

# Sample Document Title

This example shows how a Markdown file should look once the WOLFIE header v2.0.0 is in place.

## INTRO

- Summarize the document purpose here.  
- Keep the first paragraph short for quick context loads.
- This example uses v2.0.0 format with required fields: `agent_id`, `channel_number`, `version`.

## IMPLEMENTATION_NOTES

1. Replace the YAML values with your file metadata.  
2. **Required v2.0.0 fields**: Add `agent_id`, `channel_number` (zero-padded string), and `version: 2.0.0`.  
3. Update `tags` and `collections` to match your content (collections must be from 10-section set).  
4. List your sections under `in_this_file_we_have` using uppercase snake case.  
5. Keep exactly one blank line between the YAML block and the first heading.  
6. Save the file in the appropriate channel directory.

**v2.0.0 Changes:**
- Added `agent_id: 008` (WOLFIE's agent ID)
- Added `channel_number: 001` (zero-padded string matching `onchannel: 1`)
- Added `version: 2.0.0` (header format version)
- Collections can now include: WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER, TAGS

---

**Migration**: If migrating from v1.4.2, see `docs/MIGRATION_1.4.2_TO_2.0.0.md`.  
**Format Guide**: See `docs/10_SECTION_FORMAT_GUIDE.md` for detailed definitions.  
**Template**: Check `templates/header_template.yaml` for a reusable snippet.

