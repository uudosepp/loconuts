# Loconuts WordPress - Headless API Backend

Kohandatud WordPress lehekülg Loconuts projektile, seadistatud headless REST API backendina.

## Struktuur

- `/wp-content/themes/loconuts/` - Kohandatud teema
- `/wp-content/plugins/` - Pluginad
- `/wp-admin/` - WordPress admin (genereeritakse deploy-i ajal)
- `/wp-includes/` - WordPress tuumik (genereeritakse deploy-i ajal)
- `Dockerfile` - Docker image konfiguratsioon
- `render.yaml` - Render.com deployment konfiguratsioon
- `start.sh` - Startup script wp-config.php genereerimisega

## Deploy Render.com-ile (Automatic)

### 1. GitHub Repository Ühendamine
1. Push kood GitHub'i
2. Minge render.com ja logige sisse
3. "New +" → "Blueprint"
4. Valige repo ja authorize

### 2. Automatic Deployment
- `render.yaml` faili abil käivitatakse:
  - ✅ Docker container build
  - ✅ MySQL 8.0 database loomine
  - ✅ Environment variables seadmine
  - ✅ WordPress startimine

### 3. Environment Muutujad (automaatsed)
Render.com seadistab automaatselt `render.yaml` kaudu:
```
DB_NAME = wordpress (automaatne)
DB_USER = wordpress_user (automaatne)
DB_PASSWORD = (automaatne, turvaline)
DB_HOST = (MySQL endpoint, automaatne)
WORDPRESS_ENV = production
WP_DEBUG = false
```

## REST API Endpoints (Headless)

Kui deployment valmis:

```bash
# Artiklid
GET https://your-app.onrender.com/wp-json/wp/v2/posts

# Lehed
GET https://your-app.onrender.com/wp-json/wp/v2/pages

# Kategooriad
GET https://your-app.onrender.com/wp-json/wp/v2/categories

# Sildid
GET https://your-app.onrender.com/wp-json/wp/v2/tags

# Media
GET https://your-app.onrender.com/wp-json/wp/v2/media
```

## Frontend Ühendamine (Vercel)

Näide `fetch()` kasutamisega:

```javascript
const API_URL = 'https://your-app.onrender.com';

// Kõik artiklid
fetch(`${API_URL}/wp-json/wp/v2/posts`)
  .then(res => res.json())
  .then(data => console.log(data));

// Spetsiifiline artikkel
fetch(`${API_URL}/wp-json/wp/v2/posts/123`)
  .then(res => res.json())
  .then(post => console.log(post));
```

## Lokaalne Arendus

### Docker-iga lokaalselt:
```bash
# Build
docker build -t loconuts-wordpress .

# Run (mysql-i peab külge pöörama)
docker run -p 8080:80 \
  -e DB_HOST=host.docker.internal \
  -e DB_NAME=wordpress \
  -e DB_USER=wordpress \
  -e DB_PASSWORD=password \
  loconuts-wordpress
```

### LocalWP-ga:
- Tavaline WordPress local development
- `wp-content/themes/loconuts/` muuta

## Technoloogiad

- PHP 8.2
- MySQL 8.0
- WordPress 6.x
- Docker
- Render.com deployment

## Security

- ✅ Unique salts genereeritakse automaatselt (`start.sh`)
- ✅ WP_DEBUG = false (production)
- ✅ Environment variables turvaliselt Render.com-il
- ✅ HTTPS automaatne Render.com-il