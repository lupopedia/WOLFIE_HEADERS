---
light.count.offset: 0
light.count.base: 777
light.count.name: "sample_header"
light.count.mood: 808080
light.count.touch: 1

wolfie.headers.version: 2.9.2
wolfie.headers.branch: production
wolfie.headers.status: published

context.what.parent: "Counting in Light"
context.what.child: "Example"

human.username: captain wolfie
human.user_id: 1
human.location: "Sioux Falls, South Dakota"

agent.username: [GROK, CURSOR, DEEPSEEK, COPILOT, LILITH]
agent.status: compiled

title: sample_header.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.9.2
date.created: 2025-12-12
date.created_utc: 20251212233542
last.modified: 2025-12-12
last.modified_utc: 20251212233542
status: example
onchannel: 1
tags: [SYSTEM, DOCUMENTATION]
collections: [WHAT, HOW]
in_this_file_we_have: [INTRO, IMPLEMENTATION_NOTES]
superpositionally: ["FILEID_SAMPLE_HEADER"]
---

# Sample Document Title

This example shows how a Markdown file should look once the WOLFIE header v2.9.2 is in place with UTC timestamps and human/agent metadata.

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

**v2.9.2 Changes:**
- Added UTC timestamps: `date.created_utc` and `last.modified_utc` (YYYYMMDDHHMMSS format)
- Added human metadata: `human.username`, `human.user_id`, `human.location`
- Added agent metadata: `agent.username` (array), `agent.status`
- All timestamps now include both human-readable date (YYYY-MM-DD) and UTC BIGINT (YYYYMMDDHHMMSS)
- Collections can include: WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER, TAGS

---

**Migration**: If migrating from v1.4.2, see `docs/MIGRATION_1.4.2_TO_2.0.0.md`.  
**Format Guide**: See `docs/10_SECTION_FORMAT_GUIDE.md` for detailed definitions.  
**Template**: Check `templates/header_template.yaml` for a reusable snippet.

