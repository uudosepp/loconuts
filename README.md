# Loconuts WordPress

Kohandatud WordPress lehekülg Loconuts projektile.

## Struktuur

- `/wp-content/themes/loconuts/` - Kohandatud teema
- `/wp-content/plugins/` - Pluginad (lisatakse deploy-i ajal)
- `/wp-admin/` - WordPress admin (genereeritakse deploy-i ajal)
- `/wp-includes/` - WordPress tuumik (genereeritakse deploy-i ajal)

## Deploy Render.com-ile

1. GitHub'iga ühendamine
2. Render.com-il uue web service loomine
3. Andmebaasi (PostgreSQL või MySQL) loomine
4. Environment muutujate seadistamine

### Vajalikud environment muutujad

```
DB_NAME=wordpress
DB_USER=wordpress_user
DB_PASSWORD=your_secure_password
DB_HOST=database_host
WORDPRESS_ENV=production
```

## Lokaalne arendus

```bash
# Koodi muudatused lokaalsel
# Tee push GitHub'i
git push origin main
# Deploy käivitatakse automaatselt Render.com-is
```

## Tehnoloogiad

- PHP 8.2
- MySQL/PostgreSQL
- WordPress 6.x