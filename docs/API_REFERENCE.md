---
title: API_REFERENCE.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.1.0
date_created: 2025-11-18
last_modified: 2025-11-18
status: published
onchannel: 1
tags: [DOCUMENTATION, API, REFERENCE]
collections: [WHAT, HOW]
in_this_file_we_have: [OVERVIEW, BASE_URL, AUTHENTICATION, RESPONSE_FORMAT, ERROR_FORMAT, ENDPOINTS, EXAMPLES]
superpositionally: ["FILEID_API_REFERENCE"]
shadow_aliases: []
parallel_paths: []
---

# WOLFIE Headers API Reference

**Version**: v2.1.0  
**Base URL**: `/api/wolfie/`  
**Format**: JSON  
**Last Updated**: 2025-11-18

## OVERVIEW

WOLFIE Headers provides a RESTful API for programmatic access to agent logs, channel information, and database change tracking.

**All endpoints return JSON** with standardized success/error formats (v2.1.0).

---

## BASE_URL

All API endpoints are prefixed with `/api/wolfie/`:

```
GET /api/wolfie/
GET /api/wolfie/agents
GET /api/wolfie/channels
GET /api/wolfie/logs
```

---

## AUTHENTICATION

**Current Version**: No authentication required (v2.1.0)

**Future**: Authentication may be added in future versions for production deployments.

---

## RESPONSE_FORMAT

### Success Response (v2.1.0 Standard)

```json
{
  "success": true,
  "data": { ... },
  "message": "Optional success message",
  "meta": {
    "total": 10,
    "limit": 50,
    "offset": 0
  },
  "timestamp": "2025-11-18T10:30:00Z"
}
```

### Error Response (v2.1.0 Standard)

```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "Invalid channel ID provided",
    "details": {
      "channel_id": "Must be between 0 and 999"
    },
    "suggestion": "Use a channel ID between 000 and 999",
    "timestamp": "2025-11-18T10:30:00Z"
  }
}
```

**HTTP Status Codes**:
- `200` - Success
- `400` - Bad Request (validation error)
- `404` - Not Found
- `405` - Method Not Allowed
- `500` - Server Error

---

## ERROR_CODES

**Common Error Codes** (v2.1.0):

- `VALIDATION_ERROR` - Input validation failed
- `NOT_FOUND` - Resource not found
- `UNAUTHORIZED` - Authentication required (future)
- `FORBIDDEN` - Access denied (future)
- `SERVER_ERROR` - Internal server error
- `BAD_REQUEST` - Invalid request format
- `METHOD_NOT_ALLOWED` - HTTP method not allowed

---

## ENDPOINTS

### Agent Endpoints

#### List All Agents

```
GET /api/wolfie/agents
```

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "name": "CAPTAIN",
      "agent_id": 7,
      "log_count": 3,
      "channels": [7],
      "last_log_date": "2025-11-18",
      "total_entries": 15
    }
  ],
  "meta": {
    "total_agents": 4,
    "total_logs": 12
  }
}
```

#### Get Specific Agent

```
GET /api/wolfie/agents/{agent_name}
```

**Parameters**:
- `agent_name` (path) - Agent name (alphanumeric, underscore, hyphen only)

**Response**:
```json
{
  "success": true,
  "data": {
    "name": "CAPTAIN",
    "agent_id": 7,
    "logs": [
      {
        "filename": "007_CAPTAIN_log.md",
        "channel": "007",
        "path": "/path/to/logs/007_CAPTAIN_log.md",
        "entry_count": 5,
        "last_modified": "2025-11-18T10:00:00Z"
      }
    ],
    "channels": [7],
    "statistics": {
      "total_logs": 1,
      "total_entries": 5,
      "first_log_date": "2025-11-18",
      "last_log_date": "2025-11-18"
    }
  }
}
```

**Error Example**:
```json
{
  "success": false,
  "error": {
    "code": "NOT_FOUND",
    "message": "Agent 'UNKNOWN' not found in logs directory",
    "details": {"agent_name": "UNKNOWN"},
    "suggestion": "Check available agents using GET /api/wolfie/agents"
  }
}
```

---

### Channel Endpoints

#### List All Channels

```
GET /api/wolfie/channels
```

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "channel_id": "007",
      "channel_number": 7,
      "log_count": 1,
      "agents": ["CAPTAIN"],
      "last_log_date": "2025-11-18"
    }
  ],
  "meta": {
    "total_channels": 4,
    "total_logs": 12
  }
}
```

#### Get Specific Channel

```
GET /api/wolfie/channels/{channel_id}
```

**Parameters**:
- `channel_id` (path) - Channel ID (0-999)

**Response**:
```json
{
  "success": true,
  "data": {
    "channel_id": "007",
    "channel_number": 7,
    "logs": [
      {
        "filename": "007_CAPTAIN_log.md",
        "agent": "CAPTAIN",
        "path": "/path/to/logs/007_CAPTAIN_log.md",
        "entry_count": 5,
        "last_modified": "2025-11-18T10:00:00Z"
      }
    ],
    "agents": ["CAPTAIN"],
    "statistics": {
      "total_logs": 1,
      "total_entries": 5,
      "first_log_date": "2025-11-18",
      "last_log_date": "2025-11-18"
    }
  }
}
```

---

### Log File Endpoints

#### List All Log Files

```
GET /api/wolfie/logs
```

**Query Parameters**:
- `agent` (optional) - Filter by agent name
- `channel` (optional) - Filter by channel ID
- `sort` (optional) - Sort field (default: `filename`)
- `limit` (optional) - Results per page (default: 50, max: 1000)
- `offset` (optional) - Pagination offset (default: 0)

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "filename": "007_CAPTAIN_log.md",
      "channel": "007",
      "agent": "CAPTAIN",
      "path": "/path/to/logs/007_CAPTAIN_log.md",
      "entry_count": 5,
      "file_size": 1024,
      "last_modified": "2025-11-18T10:00:00Z",
      "metadata": {
        "log_entry_count": 5,
        "last_log_date": "2025-11-18"
      }
    }
  ],
  "meta": {
    "total": 12,
    "limit": 50,
    "offset": 0,
    "has_more": false
  }
}
```

#### Get Logs by Agent

```
GET /api/wolfie/logs/agents/{agent_name}
```

**Parameters**:
- `agent_name` (path) - Agent name

**Response**: Same format as "List All Log Files" but filtered by agent.

#### Get Logs by Channel

```
GET /api/wolfie/logs/channels/{channel_id}
```

**Parameters**:
- `channel_id` (path) - Channel ID (0-999)

**Response**: Same format as "List All Log Files" but filtered by channel.

#### Get Specific Log File

```
GET /api/wolfie/logs/{channel_id}/{agent_name}
```

**Parameters**:
- `channel_id` (path) - Channel ID (0-999)
- `agent_name` (path) - Agent name

**Response**:
```json
{
  "success": true,
  "data": {
    "filename": "007_CAPTAIN_log.md",
    "channel": "007",
    "agent": "CAPTAIN",
    "content": "# Log content here...",
    "metadata": {
      "agent_id": 7,
      "channel_id": 7,
      "version": "2.1.0",
      "entry_count": 5,
      "last_log_date": "2025-11-18"
    },
    "yaml_frontmatter": { ... },
    "file_info": {
      "size": 1024,
      "last_modified": "2025-11-18T10:00:00Z",
      "created_at": "2025-11-18T09:00:00Z"
    }
  }
}
```

---

### Database `_logs` Table Endpoints (v2.0.7)

#### Discover `_logs` Tables

```
GET /api/wolfie/logs/tables
```

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "table_name": "content_logs",
      "parent_table": "content",
      "valid": true,
      "row_count": 150
    }
  ]
}
```

#### List Change Logs for Table

```
GET /api/wolfie/logs/tables/{table_name}
```

**Parameters**:
- `table_name` (path) - Table name (e.g., `content_logs`)

**Query Parameters**:
- `limit` (optional) - Results per page (default: 50)
- `offset` (optional) - Pagination offset (default: 0)

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "content_id": 123,
      "agent_id": 8,
      "agent_name": "WOLFIE",
      "channel_id": 8,
      "metadata": {
        "change_type": "update",
        "old_values": {"title": "Old Title"},
        "new_values": {"title": "New Title"}
      },
      "created_at": "2025-11-18T10:00:00Z"
    }
  ],
  "meta": {
    "total": 150,
    "limit": 50,
    "offset": 0
  }
}
```

#### Get Change Logs for Specific Row

```
GET /api/wolfie/logs/tables/{table_name}/{row_id}
```

**Parameters**:
- `table_name` (path) - Table name
- `row_id` (path) - Row ID

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "content_id": 123,
      "agent_id": 8,
      "agent_name": "WOLFIE",
      "channel_id": 8,
      "metadata": {
        "change_type": "update",
        "changed_fields": ["title"],
        "old_values": {"title": "Old Title"},
        "new_values": {"title": "New Title"}
      },
      "created_at": "2025-11-18T10:00:00Z"
    }
  ],
  "meta": {
    "total": 3,
    "row_id": 123,
    "table_name": "content_logs"
  }
}
```

#### Write Change Log Entry

```
POST /api/wolfie/logs/tables/{table_name}/{row_id}
```

**Parameters**:
- `table_name` (path) - Table name
- `row_id` (path) - Row ID

**Request Body**:
```json
{
  "agent_id": 8,
  "agent_name": "WOLFIE",
  "channel_id": 8,
  "change_data": {
    "change_type": "update",
    "changed_fields": ["title"],
    "old_values": {"title": "Old Title"},
    "new_values": {"title": "New Title"}
  },
  "metadata": {
    "change_reason": "Updated for clarity"
  }
}
```

**Response**:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "table_name": "content_logs",
    "row_id": 123,
    "created_at": "2025-11-18T10:00:00Z"
  },
  "message": "Change log entry created successfully"
}
```

---

### Search Endpoint

#### Search Log Content

```
POST /api/wolfie/search
```

**Request Body**:
```json
{
  "query": "agent created",
  "filters": {
    "agent": "CAPTAIN",
    "channel": "007",
    "date_from": "2025-11-01",
    "date_to": "2025-11-18"
  },
  "options": {
    "limit": 50,
    "offset": 0,
    "highlight": true
  }
}
```

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "filename": "007_CAPTAIN_log.md",
      "channel": "007",
      "agent": "CAPTAIN",
      "matches": [
        {
          "line": 42,
          "text": "...agent created successfully...",
          "highlighted": "...<mark>agent created</mark> successfully..."
        }
      ],
      "relevance_score": 0.95
    }
  ],
  "meta": {
    "query": "agent created",
    "total_results": 5,
    "has_more": false,
    "query_time_ms": 12.5
  }
}
```

---

### Validation Endpoints

#### Validate Log File

```
POST /api/wolfie/validate/log/{filename}
```

**Parameters**:
- `filename` (path) - Log filename

**Response**:
```json
{
  "success": true,
  "data": {
    "filename": "007_CAPTAIN_log.md",
    "valid": true,
    "errors": []
  }
}
```

**Error Response**:
```json
{
  "success": true,
  "data": {
    "filename": "007_CAPTAIN_log.md",
    "valid": false,
    "errors": [
      {
        "error_type": "MISSING_REQUIRED_FIELD",
        "field": "agent_id",
        "message": "Required field 'agent_id' is missing",
        "suggestion": "Add 'agent_id: 7' to YAML frontmatter",
        "severity": "error"
      }
    ]
  }
}
```

#### Validate Entire Directory

```
POST /api/wolfie/validate/directory
```

**Response**:
```json
{
  "success": true,
  "data": {
    "total_files": 12,
    "valid_files": 11,
    "errors": 1,
    "validation_errors": [
      {
        "filename": "007_CAPTAIN_log.md",
        "errors": [ ... ]
      }
    ]
  }
}
```

---

## EXAMPLES

### JavaScript Example

```javascript
// List all agents
fetch('/api/wolfie/agents')
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      console.log('Agents:', data.data);
    } else {
      console.error('Error:', data.error.message);
    }
  });

// Get specific log file
fetch('/api/wolfie/logs/007/CAPTAIN')
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      console.log('Log content:', data.data.content);
    } else {
      console.error('Error:', data.error.message);
      console.log('Suggestion:', data.error.suggestion);
    }
  });

// Search logs
fetch('/api/wolfie/search', {
  method: 'POST',
  headers: {'Content-Type': 'application/json'},
  body: JSON.stringify({
    query: 'agent created',
    filters: {agent: 'CAPTAIN'}
  })
})
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      console.log('Results:', data.data);
    }
  });
```

### PHP Example

```php
<?php
// List all agents
$response = file_get_contents('http://example.com/api/wolfie/agents');
$data = json_decode($response, true);

if ($data['success']) {
    foreach ($data['data'] as $agent) {
        echo "Agent: {$agent['name']} (ID: {$agent['agent_id']})\n";
    }
} else {
    echo "Error: {$data['error']['message']}\n";
    echo "Suggestion: {$data['error']['suggestion']}\n";
}
```

### cURL Example

```bash
# List all agents
curl http://example.com/api/wolfie/agents

# Get specific log file
curl http://example.com/api/wolfie/logs/007/CAPTAIN

# Search logs
curl -X POST http://example.com/api/wolfie/search \
  -H "Content-Type: application/json" \
  -d '{"query": "agent created", "filters": {"agent": "CAPTAIN"}}'
```

---

## RATE_LIMITING

**Current Version**: No rate limiting (v2.1.0)

**Future**: Rate limiting may be added in future versions for production deployments.

---

## VERSIONING

**Current API Version**: v2.1.0

**Backward Compatibility**: All endpoints maintain backward compatibility with v2.0.6+.

**Future Versions**: API versioning strategy will be implemented in future releases.

---

## SUPPORT

**Documentation**:
- `docs/QUICK_START_CHOOSE_YOUR_PATH.md` - Getting started guide
- `docs/TROUBLESHOOTING_GUIDE.md` - Common issues and solutions
- `docs/WOLFIE_HEADER_SYSTEM_OVERVIEW.md` - System overview

**Examples**:
- `public/examples/example_api_usage.html` - Complete API examples

---

**Created**: 2025-11-18  
**Version**: v2.1.0  
**Author**: Captain WOLFIE (Agent 008) with LILITH's API design review

