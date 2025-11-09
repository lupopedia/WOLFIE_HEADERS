---
title: QUICK_START_GUIDE.md
agent_username: wolfie
date_created: 2025-11-09
last_modified: 2025-11-09
status: published
onchannel: 1
tags: [SYSTEM, HOWTO]
collections: [WHAT, HOW, HELP]
in_this_file_we_have: [BEFORE_YOU_START, CREATE_HEADER, VALIDATE, PUBLISH, AUTOMATION_CHECKLIST]
superpositionally: ["FILEID_WHS_QUICK_START"]
---

# Quick Start Guide

## BEFORE_YOU_START

1. Pick the right channel (`1_wolfie` unless another context is required).  
2. Review available tags/collections in `docs/TAGS_REFERENCE.md` and `docs/CHANNELS_REFERENCE.md`.  
3. Outline your document sections so `in_this_file_we_have` is accurate.

## CREATE_HEADER

1. Copy `templates/header_template.yaml` to the top of your Markdown file.  
2. Update `title`, `date_created`, and `last_modified`.  
3. Fill `tags` and `collections` from the reference tables.  
4. List 3–6 items in `in_this_file_we_have` using uppercase snake case (e.g., `PROJECT_STATUS`).  
5. Save the file within the correct channel directory (`docs/channel_1/...`).

## VALIDATE

- Ensure there is exactly one blank line between the YAML and the first heading.  
- Confirm every tag/collection exists in the reference files.  
- Run spellcheck on tags (case sensitive).  
- If `agent_username` is specified, verify the agent-specific folder exists.

## PUBLISH

1. Update `CHANGELOG.md` if the release includes structural changes or new references.  
2. Regenerate CSV/JSON indexes if your tooling requires them.  
3. Announce the update in the LUPOPEDIA changelog when syncing with the main platform.

## AUTOMATION_CHECKLIST

- [ ] Validate YAML syntax (CI script or `yamllint`).  
- [ ] Verify fallback resolution by simulating at least one agent context.  
- [ ] Run link checker on intra-doc references.  
- [ ] Archive legacy header blocks once the migration script completes.

---

Need a reference implementation? Check `examples/sample_header.md` for a fully commented template.

