# Database Connection Fix - "Error Establishing a Database Connection"

## ✅ What Was Fixed

### 1. **render.yaml - Database Property Names**
```yaml
BEFORE: property: user        ❌
AFTER:  property: username    ✅
```

### 2. **start.sh - Wait for MySQL**
```bash
BEFORE: No wait loop        ❌
AFTER:  60-second timeout   ✅
```
WordPress now waits for MySQL to be ready before attempting connection.

### 3. **Dockerfile - Network Tools**
```dockerfile
BEFORE: No netcat/mysql tools          ❌
AFTER:  Added netcat + mysql-client    ✅
```
Container can now check MySQL connectivity.

### 4. **Debug Logging**
```bash
BEFORE: Silent failures     ❌
AFTER:  Full diagnostic output ✅
```
See exactly what credentials are being used.

---

## 🚀 Deploy Now

### 1. **Push Changes**
```bash
git add -A
git commit -m "Fix: Database connection setup with proper wait & credentials"
git push origin main
```

### 2. **Manual Deploy on Render**
```
Render Dashboard
  → Select "loconuts-wordpress" service
  → Click "Manual Deploy"
  → Wait 5-10 minutes
```

### 3. **Watch Logs**
```
Render Dashboard → Logs tab
Look for:
  ✓ "DB_HOST: xxx.onrender.com"
  ✓ "DB_USER: wordpress_user"
  ✓ "MySQL is ready!"
  ✓ "wp-config.php created successfully"
```

---

## 🔍 What Happens During Deploy

```
1. Git Clone
   ↓
2. Dockerfile Build
   • Download PHP 8.2 + MySQL tools
   • Download WordPress
   • Copy themes/plugins
   ↓
3. Docker Image Created
   ↓
4. Container Start (start.sh)
   • Wait for MySQL at DB_HOST:3306
   • Generate wp-config.php with env vars
   • Start Apache
   ↓
5. WordPress Running ✅
```

---

## 🧪 Test Connection

### Option A: Render Dashboard (Recommended)
```
Dashboard → Logs
```
You should see:
```
Starting WordPress application...
DB_HOST: wordpress-db-xxx.onrender.com
DB_NAME: wordpress
DB_USER: wordpress_user
Waiting for MySQL to become available...
✓ MySQL is ready!
Creating wp-config.php...
✓ wp-config.php created successfully
```

### Option B: SSH into Container
```bash
# If Render provides SSH access
cd /var/www/html
cat wp-config.php | grep DB_

# Should show:
define('DB_HOST', 'wordpress-db-xxx.onrender.com');
define('DB_USER', 'wordpress_user');
define('DB_PASSWORD', 'xxx');
define('DB_NAME', 'wordpress');
```

### Option C: curl test
```bash
curl https://your-app.onrender.com/wp-json/wp/v2/posts
```

If database connected: Returns JSON
If not connected: Returns "Error establishing a database connection"

---

## ❌ Still Not Working?

### Check 1: Logs
```
Look for error messages in Render Logs
Check if DB_HOST is being set (not empty)
Check if MySQL connection succeeded
```

### Check 2: Database Created?
```
Render Dashboard → MySQL Service
Verify database "wordpress" exists
Verify user "wordpress_user" exists
```

### Check 3: Credentials Match?
```
render.yaml:
  - databaseName: wordpress
  - user: wordpress_user

WordPress will use these exact names
```

### Check 4: Network Issue?
```
If Render logs show MySQL never responds:
  • MySQL service might not have started
  • Docker network might be misconfigured
  • Try "Manual Deploy" again
```

---

## 📝 Key Changes Made

| File | Change | Why |
|------|--------|-----|
| `render.yaml` | `property: username` (not `user`) | Render's correct property name |
| `start.sh` | Added 60-sec MySQL wait loop | MySQL needs time to start |
| `Dockerfile` | Added `netcat-openbsd` + `mysql-client` | Tools to check connectivity |
| `start.sh` | Added debug output | See exactly what's being configured |

---

## 🎯 Expected Result After Fix

```
✅ Container starts
✅ Waits for MySQL (max 60 seconds)
✅ Reads env vars from Render
✅ Generates wp-config.php with correct credentials
✅ WordPress connects to MySQL
✅ No "Error establishing a database connection"
```

---

## Next Steps

If deployment succeeds:
1. Visit `https://your-app.onrender.com`
2. Run WordPress installer (if first time)
3. Create admin account
4. Start building! 🎉