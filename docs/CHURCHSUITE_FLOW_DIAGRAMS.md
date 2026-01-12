# ChurchSuite Integration Flow Diagram

## Automatic Sync Flow (CDC Course Completion)

```
┌─────────────────────────────────────────────────────────────────┐
│                    Member Completes CDC Course                   │
└───────────────────────────────┬─────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────┐
│     CourseEnrollment::markAsCompleted() is triggered             │
└───────────────────────────────┬─────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────┐
│   Check: Is this a CDC course?                                   │
│   (Title contains "Christian Development" or "CDC")              │
└───────────────────────────────┬─────────────────────────────────┘
                                │
                    ┌───────────┴───────────┐
                    │ Yes                   │ No
                    ▼                       ▼
┌─────────────────────────────┐   ┌────────────────┐
│ transferToChurchSuite       │   │  Stop (Exit)   │
│ IfEligible()                │   └────────────────┘
└──────────┬──────────────────┘
           │
           ▼
┌─────────────────────────────────────────────────────────────────┐
│   Check: Already synced?                                         │
└───────────────────────────────┬─────────────────────────────────┘
                                │
                    ┌───────────┴───────────┐
                    │ Yes                   │ No
                    ▼                       ▼
┌─────────────────────────────┐   ┌──────────────────────────────┐
│  Log "Already synced"       │   │ ChurchSuiteService::         │
│  Exit                       │   │ transferMember($member)      │
└─────────────────────────────┘   └──────────┬───────────────────┘
                                             │
                                             ▼
                            ┌────────────────────────────────────┐
                            │  POST to ChurchSuite API           │
                            │  /v1/contacts                      │
                            └──────────┬─────────────────────────┘
                                       │
                       ┌───────────────┴──────────────┐
                       │ Success                      │ Failed
                       ▼                              ▼
        ┌──────────────────────────┐    ┌────────────────────────┐
        │ Update Member:           │    │ Update Member:         │
        │ - churchsuite_id         │    │ - sync_status='failed' │
        │ - sync_status='synced'   │    │ - sync_error=message   │
        │ - synced_at=now()        │    │ Log error              │
        └──────────┬───────────────┘    └────────┬───────────────┘
                   │                              │
                   ▼                              ▼
        ┌──────────────────────────┐    ┌────────────────────────┐
        │ Flash success message    │    │ Log error message      │
        │ to user session          │    │                        │
        └──────────────────────────┘    └────────────────────────┘
```

## Manual Sync Flow (Admin Panel)

```
┌─────────────────────────────────────────────────────────────────┐
│              Admin clicks "Sync to ChurchSuite"                  │
│              (Single or Bulk Action)                             │
└───────────────────────────────┬─────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────┐
│   Check: Is ChurchSuite configured?                              │
│   (API URL, Account Name, API Key present)                       │
└───────────────────────────────┬─────────────────────────────────┘
                                │
                    ┌───────────┴───────────┐
                    │ Yes                   │ No
                    ▼                       ▼
┌─────────────────────────────┐   ┌──────────────────────────────┐
│ Proceed with sync           │   │ Show "Not Configured" error  │
└──────────┬──────────────────┘   │ Display setup instructions   │
           │                       └──────────────────────────────┘
           ▼
┌─────────────────────────────────────────────────────────────────┐
│            ChurchSuiteService::transferMember()                  │
└───────────────────────────────┬─────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────┐
│                  Prepare Member Data                             │
│  - Personal info (name, email, phone)                            │
│  - Address details                                               │
│  - Demographics (DOB, gender, marital status)                    │
│  - Church info (membership, dates)                               │
│  - Custom fields (membership_number, emergency contacts)         │
└───────────────────────────────┬─────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────┐
│           Send POST request to ChurchSuite API                   │
│           Headers: X-Account, X-Auth                             │
│           Endpoint: /v1/contacts                                 │
└───────────────────────────────┬─────────────────────────────────┘
                                │
                    ┌───────────┴───────────┐
                    │ HTTP 200              │ HTTP 4xx/5xx
                    ▼                       ▼
        ┌──────────────────────────┐    ┌────────────────────────┐
        │ Success Response         │    │ Error Response         │
        │ - Parse ChurchSuite ID   │    │ - Parse error message  │
        └──────────┬───────────────┘    └────────┬───────────────┘
                   │                              │
                   ▼                              ▼
        ┌──────────────────────────┐    ┌────────────────────────┐
        │ Update Database:         │    │ Update Database:       │
        │ - churchsuite_id         │    │ - sync_status='failed' │
        │ - sync_status='synced'   │    │ - sync_error           │
        │ - synced_at=now()        │    └────────┬───────────────┘
        └──────────┬───────────────┘             │
                   │                              │
                   ▼                              ▼
        ┌──────────────────────────┐    ┌────────────────────────┐
        │ Filament Notification:   │    │ Filament Notification: │
        │ "Successfully synced"    │    │ "Sync failed: [error]" │
        │ (Green success banner)   │    │ (Red error banner)     │
        └──────────────────────────┘    └────────────────────────┘
```

## Command Line Sync Flow

```
┌─────────────────────────────────────────────────────────────────┐
│        php artisan churchsuite:sync [options]                    │
└───────────────────────────────┬─────────────────────────────────┘
                                │
                                ▼
                        Option provided?
                                │
    ┌───────────────────────────┼────────────────────────────┐
    │                           │                            │
    ▼                           ▼                            ▼
┌────────┐              ┌──────────────┐           ┌─────────────┐
│ --test │              │ --member=ID  │           │ --cdc-graduates│
└────┬───┘              └──────┬───────┘           └─────┬───────┘
     │                         │                         │
     ▼                         ▼                         ▼
┌─────────────┐      ┌──────────────────┐     ┌──────────────────┐
│Test         │      │Sync specific     │     │Bulk sync CDC     │
│Connection   │      │member(s)         │     │graduates         │
└─────────────┘      └──────────────────┘     └──────────────────┘
                              │                         │
                              ▼                         ▼
                     ┌─────────────────────────────────────┐
                     │  ChurchSuiteService methods         │
                     │  - transferMember()                 │
                     │  - bulkTransferCDCGraduates()       │
                     └─────────────┬───────────────────────┘
                                   │
                                   ▼
                     ┌─────────────────────────────────────┐
                     │  Display Progress Bar               │
                     │  Show Success/Error Messages        │
                     │  Return Summary Stats               │
                     └─────────────────────────────────────┘
```

## Data Flow Diagram

```
┌──────────────────────────────────────────────────────────────────┐
│                    Your Laravel Application                       │
│                                                                   │
│  ┌────────────────┐      ┌──────────────────┐                   │
│  │ Member Model   │      │ CourseEnrollment │                   │
│  │                │      │ Model            │                   │
│  │ - first_name   │      │                  │                   │
│  │ - last_name    │      │ - status         │                   │
│  │ - email        │      │ - completion_date│                   │
│  │ - phone        │      └────────┬─────────┘                   │
│  │ - address      │               │                              │
│  │ - churchsuite_*│◄──────────────┘                              │
│  └────────┬───────┘                                              │
│           │                                                       │
│           ▼                                                       │
│  ┌─────────────────────────────────────────────────┐            │
│  │     ChurchSuiteService                          │            │
│  │                                                  │            │
│  │  - transferMember()                             │            │
│  │  - updateMember()                               │            │
│  │  - prepareMemberData()                          │            │
│  │  - bulkTransferCDCGraduates()                   │            │
│  └────────────────┬────────────────────────────────┘            │
│                   │                                              │
└───────────────────┼──────────────────────────────────────────────┘
                    │
                    │ HTTPS POST Request
                    │ Headers: X-Account, X-Auth
                    │ JSON Payload
                    │
                    ▼
┌──────────────────────────────────────────────────────────────────┐
│                     ChurchSuite API                               │
│                  (api.churchsuite.com)                            │
│                                                                   │
│  POST /v1/contacts                                                │
│  {                                                                │
│    "first_name": "John",                                          │
│    "last_name": "Doe",                                            │
│    "email": "john@example.com",                                   │
│    ...                                                            │
│  }                                                                │
│                                                                   │
│                   ▼                                               │
│           Process & Store                                         │
│                   │                                               │
│                   ▼                                               │
│  ┌──────────────────────────────────────┐                        │
│  │      ChurchSuite Database             │                        │
│  │                                       │                        │
│  │  New Contact Created                 │                        │
│  │  - ID: 12345                         │                        │
│  │  - Name: John Doe                    │                        │
│  │  - Email: john@example.com           │                        │
│  └──────────────────────────────────────┘                        │
│                   │                                               │
└───────────────────┼───────────────────────────────────────────────┘
                    │
                    │ JSON Response
                    │ { "id": 12345, ... }
                    │
                    ▼
┌──────────────────────────────────────────────────────────────────┐
│                 Your Laravel Application                          │
│                                                                   │
│  Store ChurchSuite ID in Member record                           │
│  Update sync status and timestamp                                │
│  Log the transaction                                              │
└──────────────────────────────────────────────────────────────────┘
```

## Database Schema Changes

```
┌─────────────────────────────────────────────────────────────────┐
│                      Members Table                               │
├──────────────────────────┬──────────────────────────────────────┤
│ Existing Fields          │ New ChurchSuite Fields               │
├──────────────────────────┼──────────────────────────────────────┤
│ id                       │ churchsuite_id (string, nullable)    │
│ membership_number        │ churchsuite_synced_at (timestamp)    │
│ first_name               │ churchsuite_sync_status (enum)       │
│ last_name                │   - pending                          │
│ email                    │   - synced                           │
│ phone                    │   - failed                           │
│ address                  │ churchsuite_sync_error (text)        │
│ ...                      │                                      │
└──────────────────────────┴──────────────────────────────────────┘
                                      │
                                      │ Indexed for fast queries
                                      ▼
                    ┌──────────────────────────────────┐
                    │  Indexes Added:                  │
                    │  - churchsuite_id                │
                    │  - churchsuite_sync_status       │
                    └──────────────────────────────────┘
```
