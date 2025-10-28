# 🛠️ Local Development Guide

## Option 1: Docker Compose (Recommended)

### Prerequisites
- Docker Desktop installed
- No MySQL running on localhost:3306

### Start Development Environment
```bash
# Build and start containers
docker-compose up -d

# View logs
docker-compose logs -f wordpress

# Stop containers
docker-compose down

# Stop and remove data
docker-compose down -v
```

### Access WordPress
- **Frontend:** http://localhost:8080
- **Admin:** http://localhost:8080/wp-admin
- **API:** http://localhost:8080/wp-json/wp/v2/posts

### Initial Setup
On first run, WordPress will show setup page:
1. Site Title: "Loconuts"
2. Admin Username: admin
3. Admin Email: admin@loconuts.local
4. Password: Choose secure password

### Database Connection
- **Host:** mysql (Docker DNS)
- **Database:** wordpress
- **User:** wordpress_user
- **Password:** wordpress_password

---

## Option 2: LocalWP (If prefer GUI)

### Setup
1. Download LocalWP: https://localwp.com/
2. Create new site: "loconuts"
3. Use PHP 8.2 + MySQL 8.0

### Edit Theme
```
~/Local Sites/loconuts/app/public/wp-content/themes/loconuts proov/
```

### Access
- **Frontend:** https://loconuts.local
- **Admin:** https://loconuts.local/wp-admin
- **API:** https://loconuts.local/wp-json/

---

## Option 3: Manual Docker Build

### Build Image
```bash
docker build -t loconuts-wordpress .
```

### Run Container
```bash
docker run -d \
  --name loconuts-wp \
  -p 8080:80 \
  -e DB_HOST=host.docker.internal \
  -e DB_NAME=wordpress \
  -e DB_USER=wordpress_user \
  -e DB_PASSWORD=password \
  loconuts-wordpress
```

### Stop Container
```bash
docker stop loconuts-wp
docker rm loconuts-wp
```

---

## Development Workflow

### 1. Modify Theme Files
```
wp-content/themes/loconuts proov/
├── functions.php
├── style.css
├── templates/
└── parts/
```

### 2. Reload in Browser
- **For CSS/JS:** Ctrl+F5 (hard refresh)
- **For PHP:** Page refresh (Apache reloads automatically)

### 3. Enable WordPress Debugging (Optional)
Edit wp-config.php (if exists):
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Check logs:
```bash
tail -f wp-content/debug.log
```

### 4. Check Theme Issues
```bash
# SSH into running container
docker exec -it loconuts-wp bash

# Check theme files
ls -la wp-content/themes/

# Check error logs
tail -f /var/log/apache2/error.log
```

---

## Testing REST API Locally

### Get All Posts
```bash
curl http://localhost:8080/wp-json/wp/v2/posts
```

### Create Test Post
```bash
curl -X POST http://localhost:8080/wp-json/wp/v2/posts \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Test Post",
    "content": "Test content",
    "status": "draft"
  }'
```

### Test from Frontend
```javascript
// Test API locally
fetch('http://localhost:8080/wp-json/wp/v2/posts')
  .then(res => res.json())
  .then(data => console.log(data));
```

---

## Useful Commands

### Container Management
```bash
# List containers
docker ps

# View logs
docker-compose logs wordpress

# Execute command in container
docker-compose exec wordpress bash

# Restart service
docker-compose restart wordpress
```

### Database Operations
```bash
# Access MySQL CLI
docker-compose exec mysql mysql -uwordpress_user -pwordpress_password wordpress

# View database
mysql> SHOW TABLES;
mysql> SELECT * FROM wp_posts;
mysql> exit;
```

### Clean Up
```bash
# Remove all stopped containers
docker-compose down -v

# Rebuild image
docker-compose down
docker-compose up -d --build
```

---

## Debugging

### WordPress shows error
1. Check PHP logs:
   ```bash
   docker-compose exec wordpress tail -f /var/log/apache2/error.log
   ```

2. Enable WP_DEBUG temporarily:
   ```bash
   docker-compose exec wordpress wp config set WP_DEBUG true --raw
   ```

### Database connection fails
1. Check MySQL is running:
   ```bash
   docker-compose ps
   ```

2. Test connection:
   ```bash
   docker-compose exec mysql mysqladmin ping
   ```

3. Check environment variables:
   ```bash
   docker-compose exec wordpress env | grep DB_
   ```

### Container won't start
1. Check logs:
   ```bash
   docker-compose logs wordpress
   ```

2. Check port conflicts:
   ```bash
   lsof -i :8080  # macOS/Linux
   netstat -ano | findstr :8080  # Windows
   ```

---

## Performance Tips

- **Cache theme assets:** Use browser cache headers
- **Optimize images:** Use image compression plugins
- **Enable gzip compression:** Apache already configured
- **Monitor queries:** Use Query Monitor plugin (locally)

---

## Database Backup/Restore

### Backup
```bash
# Export database
docker-compose exec mysql mysqldump -uwordpress_user -pwordpress_password wordpress > backup.sql
```

### Restore
```bash
# Import database
docker-compose exec -T mysql mysql -uwordpress_user -pwordpress_password wordpress < backup.sql
```

---

## Environment Variables Reference

| Variable | Default | Description |
|----------|---------|-------------|
| DB_NAME | wordpress | Database name |
| DB_USER | wordpress_user | Database user |
| DB_PASSWORD | wordpress_password | Database password |
| DB_HOST | localhost | Database host |
| WORDPRESS_ENV | production | Environment type |
| WP_DEBUG | false | Debug mode |

---

## Troubleshooting Summary

| Issue | Solution |
|-------|----------|
| Port 8080 in use | Change to different port in docker-compose.yml |
| MySQL won't start | Check port 3306 is free, or change MYSQL_PORT |
| wp-config.php errors | Delete file, restart container, it will regenerate |
| Blank page | Check WP_DEBUG logs in container |
| API returns 404 | Verify REST API enabled in wp-config.php |

---

## Next Steps

1. ✅ Setup local environment (this guide)
2. ✅ Test API endpoints
3. ✅ Create test content
4. ⬜ Build frontend (Vercel)
5. ⬜ Deploy to Render
6. ⬜ Connect frontend to production API

---

**Happy Developing! 🚀**
