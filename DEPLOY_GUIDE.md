# Render.com Deployment Guide

## Loconuts WordPress Headless API Backend

---

## Step 1: Prepare GitHub Repository

```bash
# Ensure all changes are pushed to GitHub
git add -A
git commit -m "Setup for Render.com deployment"
git push origin main
```

---

## Step 2: Create Render.com Account & Connect GitHub

1. Visit [render.com](https://render.com)
2. Sign up / Login
3. Connect GitHub repository:
   - Click "New +"
   - Select "Blueprint"
   - Authorize GitHub
   - Select repository

---

## Step 3: Deploy via render.yaml (Automatic)

1. **Render reads `render.yaml`** and creates:
   - ✅ Docker Web Service
   - ✅ MySQL 8.0 Database
   - ✅ Auto environment variables

2. **Wait for deployment** (~5-10 minutes)

---

## Step 4: Configure Database Connection

After deployment completes, **update environment variables**:

### In Render Dashboard:

1. Go to **Environment** tab of web service
2. Add/Update these variables:

   ```
   DB_NAME = wordpress (already set)
   DB_USER = wordpress_user (already set)
   DB_PASSWORD = [Copy from MySQL service dashboard]
   DB_HOST = [Copy from MySQL service endpoint]
   ```

3. **How to find DB_PASSWORD and DB_HOST:**
   - Click on **WordPress DB** service in Render dashboard
   - Connection info is under "Info" section
   - Copy: `Hostname`, `Port`, `Default Database`, `Default User`, `Default Password`

4. Update web service envVars and **Deploy** again

---

## Step 5: Verify WordPress Installation

### Wait for First Deployment
After MySQL variables are set, Render will auto-redeploy. Wait 2-3 minutes.

### Access WordPress Admin
1. Visit: `https://your-app.onrender.com/wp-admin`
2. Setup WordPress (if first time):
   - Site Title: "Loconuts"
   - Admin Username: your choice
   - Admin Email: your@email.com
   - Password: strong password

### Test REST API
```bash
# Test API endpoint
curl https://your-app.onrender.com/wp-json/wp/v2/posts

# Should return: [] or post data if posts exist
```

---

## Step 6: Frontend Setup (Vercel)

### Option A: Next.js Frontend

Create new Vercel project:

```bash
npm create next-app@latest loconuts-frontend -- --typescript
cd loconuts-frontend
```

### `.env.local` (Vercel):
```
NEXT_PUBLIC_API_URL=https://your-app.onrender.com
```

### Example `lib/api.ts`:
```typescript
const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:3000';

export async function getPosts() {
  const res = await fetch(`${API_URL}/wp-json/wp/v2/posts`);
  return res.json();
}

export async function getPost(id: number) {
  const res = await fetch(`${API_URL}/wp-json/wp/v2/posts/${id}`);
  return res.json();
}
```

### Deploy to Vercel:
```bash
git push origin main
# Vercel auto-deploys from GitHub
```

---

## Troubleshooting

### 502 Bad Gateway
**Problem:** Database not connected
**Solution:** 
1. Check DB_HOST and DB_PASSWORD are set
2. Verify MySQL service is running (check Render dashboard)
3. Check logs: Render Dashboard → Logs tab

### WordPress blank page
**Problem:** wp-config.php not generated
**Solution:**
1. SSH into container: Render Dashboard → Shell
2. Check: `cat wp-config.php` exists
3. Check: `php wp-config.php` has no errors

### API not working
**Problem:** REST API disabled or theme issue
**Solution:**
1. Test: `curl https://your-app.onrender.com/wp-json/`
2. Should return WordPress API info
3. If not: Check `start.sh` sets `WP_REST_ENABLED`

---

## Production Checklist

- [ ] MySQL credentials set in Render
- [ ] WordPress admin access works
- [ ] REST API returns data
- [ ] HTTPS certificate auto-generated
- [ ] Docker logs show no errors
- [ ] Frontend connects to API
- [ ] Images/media upload tested
- [ ] Backups configured (Render PostgreSQL plans)

---

## Useful Render Commands

### View Logs
```
Render Dashboard → Logs tab
```

### SSH into Container
```
Render Dashboard → Shell tab
```

### Manual Restart
```
Render Dashboard → Settings → Restart Instance
```

### Database Backups
```
Render Dashboard → MySQL Service → Backups tab
```

---

## Architecture

```
GitHub Repository
        ↓
    render.yaml
        ↓
┌─────────────────────────────────────┐
│     Render.com                      │
├─────────────────────────────────────┤
│  Web Service (Docker + PHP 8.2)     │  ← WordPress REST API
│      ↓                              │
│  MySQL 8.0 Database                 │
└─────────────────────────────────────┘
        ↑
        │ API calls (https)
        │
┌─────────────────────────────────────┐
│     Vercel                          │
├─────────────────────────────────────┤
│  Frontend (Next.js/React)           │
│  - Fetches posts from API           │
│  - Displays content                 │
│  - Auto-deploys on push             │
└─────────────────────────────────────┘
```

---

## Support

- Render.com Docs: https://render.com/docs
- WordPress REST API: https://developer.wordpress.org/rest-api/
- Issue? Check Render Logs tab

---

**Last Updated:** 2024
**Environment:** Production (Render.com + MySQL 8.0)