# Enhanced Rota System - Ministry Schedule Generation

## Overview
The Rota system has been significantly enhanced to generate comprehensive ministry schedules that match your Excel format requirements. The system now creates detailed rotas covering all ministry departments with proper role categorization and member assignments.

## Key Enhancements Made

### 1. Enhanced Rota Generator Service
**File:** `app/Services/RotaGeneratorService.php`

**New Features:**
- **Multi-Department Support:** Now handles worship, technical, and preacher departments simultaneously
- **Ministry Role Structure:** Organized roles into logical categories matching your Excel format:
  - **Leadership:** Preaching, Leading
  - **Worship Team:** Worship Leader, Lead/Second Guitar, Bass Guitar, Acoustic Guitar, Piano 1, Piano 2, Drums, Singers Team
  - **Technical Team:** TL For The Day, Media(Kelham), PA(Kelham), Visual(Kelham), Training/Shadow

- **Smart Role Mapping:** Maps actual department roles to ministry structure roles
- **Member Rotation System:** Ensures fair distribution of responsibilities across team members
- **Multiple Member Assignment:** Supports multiple people for roles like "Singers Team"

### 2. Enhanced Excel Export
**File:** `app/Exports/RotaExport.php`

**New Features:**
- **Professional Styling:** Added borders, colors, and proper formatting
- **Category Organization:** Groups roles by ministry departments
- **Enhanced Headers:** Better date formatting and title presentation
- **Responsive Layout:** Automatic column sizing and proper alignment
- **Visual Hierarchy:** Different styling for categories, roles, and data

### 3. Database Enhancements
**New Seeder:** `database/seeders/PreacherDepartmentSeeder.php`
- Added comprehensive preacher department data
- Sample roles for different ministry contexts (Sunday Service, Youth Ministry, Bible Study, Special Events)

**Enhanced Existing Seeders:**
- Updated `RotaSeeder.php` with realistic sample data matching your Excel format
- Added to `DatabaseSeeder.php` for complete setup

## Ministry Structure Generated

The system now generates rotas with the following structure:

### Leadership Roles
- **Preaching:** Lead Pastor, Assistant Pastor, Bible Teacher, etc.
- **Leading:** Lead Vocalist, Worship Leader, etc.

### Worship Team Roles
- **Worship Leader:** Coordinates worship service
- **Lead/Second Guitar:** Primary and secondary guitarists
- **Bass Guitar:** Bass guitar players
- **Acoustic Guitar:** Acoustic guitar support
- **Piano 1 & 2:** Primary and secondary pianists/keyboardists
- **Drums:** Drum kit players
- **Singers Team:** Multiple vocalists (2-3 people assigned)

### Technical Team Roles
- **TL For The Day:** Technical leader for the service
- **Media(Kelham):** Media and projection operators
- **PA(Kelham):** Audio/PA system operators
- **Visual(Kelham):** Lighting and visual technicians
- **Training/Shadow:** Trainees and support staff

## How to Use

### 1. Creating a New Rota
1. Go to Admin Panel → Unit Management → Rotas
2. Click "Create" to add a new rota
3. Fill in the details:
   - **Title:** e.g., "November 2025 Ministry Rota - Kelham Island"
   - **Departments:** Select all relevant departments (worship, technical, preacher)
   - **Date Range:** Set start and end dates (system will find all Sundays in range)
   - **Description and Notes:** Optional additional information

### 2. Auto-Generating Assignments
1. After creating the rota, click "Auto Generate" button
2. The system will automatically assign members to all roles for all Sundays
3. Members are rotated fairly across the date range
4. Multiple people are assigned to roles like "Singers Team"

### 3. Exporting to Excel
1. Click "Export Excel" button on any rota
2. Downloads a professionally formatted Excel file with:
   - Ministry role categories
   - All Sunday dates as columns
   - Member assignments in a clear grid format
   - Professional styling and formatting

## Sample Output Structure

```
| Role               | Oct 6th | Oct 13th | Oct 20th | Oct 27th |
|--------------------|---------|----------|----------|----------|
| Leadership         |         |          |          |          |
| Preaching          | Jim     | Terence  | Jim      | James    |
| Leading            | JB      | Gail     | JC       | Sofia    |
|                    |         |          |          |          |
| Worship Team       |         |          |          |          |
| Worship Leader     | JB      | JC       | Sofia    | JB       |
| Lead/Second Guitar | JC      | Brian G  | JC       | Gail     |
| Bass Guitar        | Brian G | Brian G  | Brian G  | Brian G  |
| ...                | ...     | ...      | ...      | ...      |
```

## Benefits

1. **Matches Your Excel Format:** Output closely resembles your existing ministry rota spreadsheets
2. **Comprehensive Coverage:** Handles all ministry departments in one rota
3. **Fair Distribution:** Automatically rotates members to ensure everyone serves regularly
4. **Professional Output:** Excel exports are ready for printing and distribution
5. **Easy Management:** Simple interface for creating and managing ministry schedules
6. **Flexible:** Can handle different date ranges and department combinations

## Next Steps

1. **Test the System:** Create a new rota and generate assignments
2. **Export to Excel:** Download and review the formatted output
3. **Customize if Needed:** Adjust role mappings or add new roles as required
4. **Train Staff:** Show administrators how to use the new features

The enhanced rota system now provides a comprehensive solution for ministry scheduling that matches your workflow and produces professional output suitable for church operations.
