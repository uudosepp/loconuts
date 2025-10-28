# 🚀 Quick Start - Render.com Deployment

## 5-Minute Setup

### Step 1: Push to GitHub (1 min)
```bash
cd /path/to/project
git add -A
git commit -m "Deploy to Render"
git push origin main
```

### Step 2: Create Render Blueprint (2 min)
1. Go to https://render.com
2. Click **"New +"** → **"Blueprint"**
3. Connect GitHub & select your repository
4. Click **"Apply"**

### Step 3: Wait for Deployment (2 min)
- Docker builds (~1-2 min)
- MySQL creates
- WordPress starts
- Shows URL: `https://your-app.onrender.com`

---

## 🔧 After Deployment - Configure Database (Important!)

1. **Go to Render Dashboard**
2. **Click on WordPress DB service** (MySQL database)
3. **Copy these values:**
   - Hostname (example: `dpg-xxx.render.internal`)
   - Port (usually 3306)
   - Default Password

4. **Go back to Web Service**
5. **Update Environment Variables:**
   ```
   DB_HOST = [paste Hostname]
   DB_PASSWORD = [paste Password]
   ```
6. **Click "Manual Deploy"**

---

## ✅ Verify It Works

### Test 1: Check API
```bash
curl https://your-app.onrender.com/wp-json/
# Should return WordPress API info
```

### Test 2: Admin Panel
- Visit: `https://your-app.onrender.com/wp-admin`
- Setup admin account (first time only)

### Test 3: Get Posts
```bash
curl https://your-app.onrender.com/wp-json/wp/v2/posts
# Should return [] or existing posts
```

---

## 🎨 Next: Frontend on Vercel

### Option A: Quick React App
```bash
npx create-react-app loconuts-frontend
cd loconuts-frontend
```

Create `src/api.js`:
```javascript
export async function getPosts() {
  const res = await fetch('https://your-app.onrender.com/wp-json/wp/v2/posts');
  return res.json();
}
```

Use in component:
```javascript
import { getPosts } from './api';

export default function Posts() {
  const [posts, setPosts] = React.useState([]);
  
  React.useEffect(() => {
    getPosts().then(setPosts);
  }, []);
  
  return (
    <div>
      {posts.map(post => (
        <div key={post.id}>{post.title.rendered}</div>
      ))}
    </div>
  );
}
```

### Option B: Next.js (Recommended)
```bash
npx create-next-app@latest loconuts-frontend
```

---

## 🆘 Troubleshooting

| Problem | Fix |
|---------|-----|
| 502 Error | Set DB_HOST and DB_PASSWORD in Render |
| Blank Page | Check logs: Render → Web Service → Logs |
| API Not Working | `curl https://your-app.onrender.com/wp-json/` |
| Can't Login | Clear browser cache & cookies |

---

## 📚 Full Documentation

- 📖 **Full Guide:** See `DEPLOY_GUIDE.md`
- 📋 **Changes Made:** See `DEPLOYMENT_SUMMARY.md`
- 📝 **Architecture:** See `README.md`

---

**You're all set! 🎉**

Your WordPress API is now running on Render.com and ready to power your frontend on Vercel.
