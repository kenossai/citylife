# ChurchSuite Integration - Testing & Deployment Checklist

## Pre-Deployment Checklist

### ✅ Configuration
- [ ] Add ChurchSuite credentials to `.env`
  ```env
  CHURCHSUITE_API_URL=https://api.churchsuite.com/v1
  CHURCHSUITE_ACCOUNT_NAME=your-account-name
  CHURCHSUITE_API_KEY=your-api-key
  ```
- [ ] Verify credentials are correct
- [ ] Test API connection: `php artisan churchsuite:sync --test`

### ✅ Database
- [ ] Run migration: `php artisan migrate`
- [ ] Verify new columns added to `members` table:
  - churchsuite_id
  - churchsuite_synced_at
  - churchsuite_sync_status
  - churchsuite_sync_error
- [ ] Check indexes are created

### ✅ Files Review
- [ ] `app/Services/ChurchSuiteService.php` exists
- [ ] `app/Console/Commands/ChurchSuiteSyncCommand.php` exists
- [ ] `app/Models/Member.php` updated with new fields
- [ ] `app/Models/CourseEnrollment.php` updated with auto-sync
- [ ] `app/Filament/Resources/MemberResource.php` has sync actions
- [ ] `config/services.php` has ChurchSuite config

## Testing Checklist

### 🧪 Connection Testing
- [ ] Run: `php artisan churchsuite:sync --test`
- [ ] Verify "Connected successfully" message
- [ ] If failed, check credentials and API status

### 🧪 Manual Sync Testing

**Test 1: Single Member Sync via Admin**
- [ ] Go to Admin → Members
- [ ] Create/select a test member with complete data:
  - First name, Last name
  - Email (required)
  - Phone
  - Address details
- [ ] Click "Sync to ChurchSuite" action
- [ ] Verify success notification appears
- [ ] Check ChurchSuite account for new contact
- [ ] Verify member's `churchsuite_sync_status` = 'synced'
- [ ] Verify `churchsuite_id` is populated
- [ ] Verify `churchsuite_synced_at` has timestamp

**Test 2: Bulk Member Sync**
- [ ] Select 3-5 test members
- [ ] Click Bulk Actions → "Sync to ChurchSuite"
- [ ] Verify summary shows successful count
- [ ] Check all members in ChurchSuite
- [ ] Verify all members have sync status updated

**Test 3: Command Line Sync**
- [ ] Run: `php artisan churchsuite:sync --member=1` (use real ID)
- [ ] Verify success message in console
- [ ] Check ChurchSuite for the member
- [ ] Run: `php artisan churchsuite:sync --cdc-graduates`
- [ ] Verify summary output

### 🧪 Automatic Sync Testing (CDC Course)

**Test 4: CDC Course Completion Auto-Sync**
- [ ] Create a test member account
- [ ] Enroll test member in CDC course
- [ ] Verify CDC course title contains "Christian Development" or "CDC"
- [ ] Complete all lessons for test member
- [ ] Mark course as completed
- [ ] Verify automatic sync triggered
- [ ] Check Laravel logs for sync confirmation
- [ ] Verify member appears in ChurchSuite
- [ ] Verify sync status updated automatically
- [ ] Check dashboard for success message

**Test 5: Non-CDC Course (Should NOT sync)**
- [ ] Enroll test member in a different course
- [ ] Complete the course
- [ ] Verify NO automatic sync occurred
- [ ] Verify sync status unchanged

### 🧪 Error Handling Testing

**Test 6: Invalid Credentials**
- [ ] Temporarily change API key to invalid value
- [ ] Try to sync a member
- [ ] Verify error notification appears
- [ ] Verify `churchsuite_sync_status` = 'failed'
- [ ] Verify error message stored in `churchsuite_sync_error`
- [ ] Check Laravel logs for error details
- [ ] Restore correct credentials

**Test 7: Missing Required Data**
- [ ] Create member with only first_name (no email)
- [ ] Try to sync
- [ ] Verify appropriate error message
- [ ] Fix member data and retry

**Test 8: Duplicate Prevention**
- [ ] Sync a member successfully
- [ ] Try to sync same member again
- [ ] Verify it skips or updates (not creates duplicate)
- [ ] Check ChurchSuite for no duplicates

### 🧪 UI/UX Testing

**Test 9: Admin Dashboard Display**
- [ ] Go to Admin → Members
- [ ] Verify "ChurchSuite" column visible
- [ ] Check sync status badges display correctly:
  - Green = synced
  - Red = failed
  - Yellow = pending
  - Gray = not synced
- [ ] Toggle "Last Synced" column visibility
- [ ] Verify timestamps display correctly

**Test 10: Sync Action Visibility**
- [ ] Find synced member
- [ ] Verify "Sync to ChurchSuite" action is hidden
- [ ] Find unsynced member
- [ ] Verify "Sync to ChurchSuite" action is visible
- [ ] Click action and verify confirmation modal appears

## Production Deployment Checklist

### 🚀 Pre-Deploy
- [ ] All tests passed
- [ ] ChurchSuite production credentials ready
- [ ] Backup database before migration
- [ ] Review all code changes
- [ ] Update `.env.example` with new variables

### 🚀 Deploy Steps
1. [ ] Push code to production
2. [ ] Run migration: `php artisan migrate --force`
3. [ ] Update `.env` with production ChurchSuite credentials
4. [ ] Clear cache: `php artisan config:clear`
5. [ ] Clear cache: `php artisan cache:clear`
6. [ ] Test connection: `php artisan churchsuite:sync --test`

### 🚀 Post-Deploy
- [ ] Test manual sync with one real member
- [ ] Verify member appears in ChurchSuite
- [ ] Monitor Laravel logs for errors
- [ ] Test CDC course completion flow
- [ ] Notify admins of new feature
- [ ] Provide admin training if needed

## Monitoring Checklist

### 📊 Daily Monitoring (First Week)
- [ ] Check Laravel logs for sync errors
- [ ] Review failed syncs in admin panel
- [ ] Verify CDC graduates syncing automatically
- [ ] Check ChurchSuite for data accuracy

### 📊 Weekly Monitoring
- [ ] Review sync success rate
- [ ] Check for duplicate contacts in ChurchSuite
- [ ] Verify all CDC graduates synced
- [ ] Monitor API rate limits

### 📊 Monthly Review
- [ ] Analyze sync patterns
- [ ] Review and clean up failed syncs
- [ ] Update documentation if needed
- [ ] Plan any enhancements

## Troubleshooting Quick Reference

### Problem: "ChurchSuite Not Configured"
**Solution:**
1. Check `.env` has all three credentials
2. Run `php artisan config:clear`
3. Retry sync

### Problem: "Connection Failed"
**Solution:**
1. Verify API credentials are correct
2. Check ChurchSuite API status
3. Verify network/firewall allows HTTPS
4. Run `php artisan churchsuite:sync --test`

### Problem: Member Synced but Not in ChurchSuite
**Solution:**
1. Check Laravel logs: `storage/logs/laravel.log`
2. Look for error messages
3. Verify member has required fields (email)
4. Check ChurchSuite API key permissions

### Problem: Duplicate Members in ChurchSuite
**Solution:**
1. Manually merge in ChurchSuite
2. Update Laravel member with `churchsuite_id`
3. Set `churchsuite_sync_status` = 'synced'

### Problem: Automatic Sync Not Working
**Solution:**
1. Verify CDC course title contains "Christian Development" or "CDC"
2. Check Laravel logs when course is completed
3. Verify ChurchSuite credentials configured
4. Test manual sync works

## Rollback Plan

If critical issues occur:

1. **Stop automatic syncs:**
   - Comment out auto-sync in `CourseEnrollment.php`:
   ```php
   // $this->transferToChurchSuiteIfEligible();
   ```

2. **Disable manual sync:**
   - Remove sync actions from `MemberResource.php` temporarily

3. **Database rollback:**
   ```bash
   php artisan migrate:rollback
   ```

4. **Investigate and fix:**
   - Review logs
   - Fix issues
   - Test in staging
   - Re-deploy

## Success Criteria

The integration is successful when:
- ✅ CDC course graduates automatically sync to ChurchSuite
- ✅ Manual sync works from admin panel
- ✅ Bulk sync processes multiple members
- ✅ Sync status accurately tracked
- ✅ Error handling works properly
- ✅ No duplicate members created
- ✅ All required data transfers correctly
- ✅ Performance acceptable (< 5 sec per member)
- ✅ Admin team trained and comfortable using feature

## Documentation Locations

- **Full Documentation**: `docs/CHURCHSUITE_INTEGRATION.md`
- **Quick Setup**: `docs/CHURCHSUITE_SETUP.md`
- **Flow Diagrams**: `docs/CHURCHSUITE_FLOW_DIAGRAMS.md`
- **Implementation Summary**: `CHURCHSUITE_IMPLEMENTATION.md`
- **This Checklist**: `docs/CHURCHSUITE_TESTING_CHECKLIST.md`

---

**Last Updated**: January 12, 2026  
**Version**: 1.0  
**Status**: Ready for Testing
