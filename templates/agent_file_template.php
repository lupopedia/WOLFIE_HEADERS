<?php
/**
 * Agent Profile Template
 * 
 * WOLFIE Headers v2.0.2 Agent File Template
 * 
 * Usage:
 * 1. Copy this file to: public/who_is_agent_[channel_id]_[agent_name].php
 * 2. Replace [CHANNEL_ID] with zero-padded channel ID (000-999)
 * 3. Replace [AGENT_NAME] with lowercase agent name
 * 4. Replace [AGENT_ID] with channel ID (integer, not zero-padded)
 * 5. Fill in all sections (WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER)
 * 
 * File Naming Convention:
 * - Pattern: who_is_agent_[channel_id]_[agent_name].php
 * - Channel ID: Zero-padded 3 digits (e.g., "008", "010", "075")
 * - Agent Name: Lowercase (e.g., "wolfie", "lilith", "vishwakarma")
 * - Example: who_is_agent_008_wolfie.php
 */

// WOLFIE Headers v2.0.2 Frontmatter
// NOTE: This is a comment block - actual YAML frontmatter should be in the file
// but PHP files don't support YAML frontmatter directly, so metadata is in comments
// and should also be stored in content_headers table

/*
---
title: who_is_agent_[channel_id]_[agent_name].php
agent_username: [agent_name]
agent_id: [AGENT_ID]
channel_number: [channel_id_zero_padded]
version: 2.0.2
date_created: 2025-01-27
last_modified: 2025-01-27
status: published
onchannel: 1
tags: [AGENT, PROFILE]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER]
in_this_file_we_have: [AGENT_PROFILE, WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER]
superpositionally: ["FILEID_AGENT_[CHANNEL_ID]_[AGENT_NAME]"]
shadow_aliases: []
parallel_paths: []
---
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>[AGENT_NAME] - AI Agent Profile</title>
    <style>
        .agent-profile-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .agent-profile-content {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
        }

        .agent-profile-section {
            margin-bottom: 40px;
        }

        .agent-profile-section h2 {
            color: #dc2626;
            font-size: 2rem;
            margin-bottom: 20px;
            border-bottom: 3px solid #ef4444;
            padding-bottom: 10px;
        }

        .agent-profile-section h3 {
            color: #b91c1c;
            font-size: 1.5rem;
            margin-top: 30px;
            margin-bottom: 15px;
        }

        .agent-profile-section p,
        .agent-profile-section ul {
            line-height: 1.8;
            font-size: 1.05rem;
            color: #374151;
        }

        .agent-profile-section ul {
            margin-left: 20px;
        }

        .agent-profile-section li {
            margin-bottom: 10px;
        }

        .metadata-box {
            background: #fef2f2;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #dc2626;
            margin-bottom: 30px;
        }

        .metadata-box h3 {
            margin-top: 0;
            color: #7f1d1d;
        }

        .metadata-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .metadata-item {
            display: flex;
            flex-direction: column;
        }

        .metadata-label {
            font-weight: bold;
            color: #991b1b;
            margin-bottom: 5px;
        }

        .metadata-value {
            color: #374151;
        }
    </style>
</head>
<body>
    <div class="agent-profile-container">
        <div class="agent-profile-content">
            
            <!-- Agent Metadata -->
            <div class="metadata-box">
                <h3>Agent Metadata</h3>
                <div class="metadata-grid">
                    <div class="metadata-item">
                        <span class="metadata-label">Agent Name:</span>
                        <span class="metadata-value">[AGENT_NAME]</span>
                    </div>
                    <div class="metadata-item">
                        <span class="metadata-label">Agent ID:</span>
                        <span class="metadata-value">[AGENT_ID]</span>
                    </div>
                    <div class="metadata-item">
                        <span class="metadata-label">Channel ID:</span>
                        <span class="metadata-value">[channel_id_zero_padded]</span>
                    </div>
                    <div class="metadata-item">
                        <span class="metadata-label">File Name:</span>
                        <span class="metadata-value">who_is_agent_[channel_id]_[agent_name].php</span>
                    </div>
                </div>
            </div>

            <!-- WHO Section -->
            <div class="agent-profile-section">
                <h2>WHO</h2>
                <p><strong>Identity, Role, Archetype</strong></p>
                <p>Replace this with agent identity information:</p>
                <ul>
                    <li>Agent name and acronym (if applicable)</li>
                    <li>Role in the system</li>
                    <li>Archetype or mythological reference</li>
                    <li>Identity characteristics</li>
                </ul>
            </div>

            <!-- WHAT Section -->
            <div class="agent-profile-section">
                <h2>WHAT</h2>
                <p><strong>Function, Task, Domain</strong></p>
                <p>Replace this with agent function information:</p>
                <ul>
                    <li>Core function or specialization</li>
                    <li>Primary tasks and responsibilities</li>
                    <li>Domain of expertise</li>
                    <li>Key capabilities</li>
                </ul>
            </div>

            <!-- WHERE Section -->
            <div class="agent-profile-section">
                <h2>WHERE</h2>
                <p><strong>Location, Database, Context</strong></p>
                <p>Replace this with agent location information:</p>
                <ul>
                    <li>Database or server location</li>
                    <li>Computational context</li>
                    <li>Symbolic location</li>
                    <li>Channel assignment</li>
                </ul>
            </div>

            <!-- WHEN Section -->
            <div class="agent-profile-section">
                <h2>WHEN</h2>
                <p><strong>Temporal Rhythm, Cycle, Timestamp</strong></p>
                <p>Replace this with agent temporal information:</p>
                <ul>
                    <li>Temporal rhythm or cycle</li>
                    <li>Timestamp convention</li>
                    <li>When agent is active</li>
                    <li>Time-based behaviors</li>
                </ul>
            </div>

            <!-- WHY Section -->
            <div class="agent-profile-section">
                <h2>WHY</h2>
                <p><strong>Purpose, Motivation, Philosophy</strong></p>
                <p>Replace this with agent purpose information:</p>
                <ul>
                    <li>Purpose and motivation</li>
                    <li>Philosophy or principles</li>
                    <li>Why the agent exists</li>
                    <li>Core values</li>
                </ul>
            </div>

            <!-- HOW Section -->
            <div class="agent-profile-section">
                <h2>HOW</h2>
                <p><strong>Methods, Protocols, Workflows</strong></p>
                <p>Replace this with agent methods information:</p>
                <ul>
                    <li>Methods and protocols</li>
                    <li>Workflows and processes</li>
                    <li>How the agent operates</li>
                    <li>Technical implementation</li>
                </ul>
            </div>

            <!-- DO Section -->
            <div class="agent-profile-section">
                <h2>DO</h2>
                <p><strong>Active Behaviors, Actions, Rituals</strong></p>
                <p>Replace this with agent behaviors information:</p>
                <ul>
                    <li>Active behaviors</li>
                    <li>Actions and tasks</li>
                    <li>Rituals or routines</li>
                    <li>What the agent does</li>
                </ul>
            </div>

            <!-- HACK Section -->
            <div class="agent-profile-section">
                <h2>HACK</h2>
                <p><strong>Unconventional Tricks, Exploits, Heterodoxies</strong></p>
                <p>Replace this with agent hack information:</p>
                <ul>
                    <li>Unconventional tricks</li>
                    <li>Exploits or workarounds</li>
                    <li>Heterodox approaches</li>
                    <li>Creative solutions</li>
                </ul>
            </div>

            <!-- OTHER Section -->
            <div class="agent-profile-section">
                <h2>OTHER</h2>
                <p><strong>Mythic Notes, Cultural References, Extra Context</strong></p>
                <p>Replace this with agent other information:</p>
                <ul>
                    <li>Mythic or cultural references</li>
                    <li>Extra context or notes</li>
                    <li>Additional information</li>
                    <li>Miscellaneous details</li>
                </ul>
            </div>

        </div>
    </div>
</body>
</html>

