# Panduan Deploy Laravel ke Railway.app

Railway.app adalah platform hosting modern yang **GRATIS tanpa kartu kredit** untuk free tier. Perfect untuk deploy Laravel!

## Keuntungan Railway.app

✅ **Gratis tanpa kartu kredit** - $5 credit gratis per bulan  
✅ **Auto-deploy dari GitHub** - Setiap push otomatis deploy  
✅ **Support Docker** - Menggunakan Dockerfile yang sudah ada  
✅ **Environment Variables** - Mudah setup .env  
✅ **Database included** - Bisa tambah MySQL/PostgreSQL  
✅ **Custom domain** - Support custom domain gratis  

## Langkah Deploy

### 1. Daftar/Login ke Railway

1. Buka https://railway.app
2. Login dengan GitHub account
3. Klik "New Project"

### 2. Connect Repository

1. Pilih "Deploy from GitHub repo"
2. Pilih repository: `rzkyerl/anagatha-profile`
3. Railway akan otomatis detect Dockerfile

### 3. Konfigurasi Service

Railway akan otomatis:
- ✅ Detect Dockerfile
- ✅ Build Docker image
- ✅ Deploy aplikasi

**Tidak perlu konfigurasi tambahan!** Railway akan membaca:
- `Dockerfile` untuk build
- `railway.json` untuk konfigurasi (opsional)

### 4. Setup Environment Variables

Setelah service dibuat, buka tab **Variables** dan tambahkan:

```
APP_NAME=Anagatha Profile
APP_ENV=production
APP_KEY=base64:rgx4HBy8J5CMrbtdPAQ1pasOCluGAEHFcUdXVZBetpw=
APP_DEBUG=false
APP_URL=https://your-app.railway.app
```

**Untuk generate APP_KEY:**
```bash
php artisan key:generate --show
```

### 5. Setup Database (jika diperlukan)

1. Di project Railway, klik **"+ New"**
2. Pilih **"Database"** → **"Add MySQL"** atau **"Add PostgreSQL"**
3. Railway akan otomatis create database
4. Copy connection string yang diberikan
5. Tambahkan ke Environment Variables:

**Untuk MySQL:**
```
DB_CONNECTION=mysql
DB_HOST=containers-us-west-XXX.railway.app
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=your_password
```

**Untuk PostgreSQL:**
```
DB_CONNECTION=pgsql
DB_HOST=containers-us-west-XXX.railway.app
DB_PORT=5432
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### 6. Run Migrations (jika ada)

1. Buka tab **"Deployments"**
2. Klik pada deployment terbaru
3. Buka tab **"Logs"**
4. Atau gunakan Railway CLI:

```bash
# Install Railway CLI
npm i -g @railway/cli

# Login
railway login

# Link project
railway link

# Run migration
railway run php artisan migrate
```

### 7. Setup Custom Domain (Opsional)

1. Buka service → tab **"Settings"**
2. Scroll ke **"Domains"**
3. Klik **"Generate Domain"** untuk domain Railway gratis
4. Atau **"Custom Domain"** untuk domain sendiri
5. Update `APP_URL` di Environment Variables

## File Konfigurasi

### railway.json
File ini sudah dibuat dan berisi konfigurasi Railway:
- Builder: Dockerfile
- Start command: PHP artisan serve

### Dockerfile
Dockerfile sudah dikonfigurasi untuk:
- ✅ PHP 8.2 dengan extensions yang diperlukan
- ✅ Composer untuk dependencies
- ✅ Node.js untuk build assets
- ✅ Build assets dan hapus node_modules
- ✅ Cache Laravel config
- ✅ Menggunakan PORT dari Railway

## Environment Variables yang Diperlukan

### Wajib:
```
APP_ENV=production
APP_KEY=base64:your_generated_key
APP_DEBUG=false
APP_URL=https://your-app.railway.app
```

### Opsional (jika pakai database):
```
DB_CONNECTION=mysql
DB_HOST=...
DB_PORT=...
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
```

### Opsional (untuk email, dll):
```
MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=...
MAIL_USERNAME=...
MAIL_PASSWORD=...
```

## Troubleshooting

### Build Error: npm ci failed

**Solusi:** Dockerfile sudah diupdate untuk handle package-lock.json. Jika masih error, cek apakah `package-lock.json` ada di repository.

### Error: APP_KEY not set

**Solusi:**
1. Generate APP_KEY: `php artisan key:generate --show`
2. Tambahkan ke Environment Variables di Railway

### Error: Database connection failed

**Solusi:**
1. Pastikan database service sudah dibuat
2. Copy connection string dengan benar
3. Pastikan semua DB_* variables sudah di-set

### Error: Port already in use

**Solusi:** Railway otomatis set PORT variable. Dockerfile sudah dikonfigurasi untuk menggunakan `${PORT:-8000}`.

### Assets tidak muncul

**Solusi:**
1. Pastikan build berhasil (cek logs)
2. Pastikan `public/build/` folder ada
3. Cek `APP_URL` sudah benar

## Railway CLI (Opsional)

Install Railway CLI untuk manage dari terminal:

```bash
# Install
npm i -g @railway/cli

# Login
railway login

# Link project
railway link

# View logs
railway logs

# Run commands
railway run php artisan migrate
railway run php artisan cache:clear
```

## Auto-Deploy

Railway otomatis deploy setiap kali Anda push ke GitHub:
- ✅ Push ke `main` branch → Auto deploy
- ✅ Bisa setup branch lain di Settings → Source

## Monitoring

Railway menyediakan:
- **Metrics** - CPU, Memory usage
- **Logs** - Real-time application logs
- **Deployments** - History semua deployment

## Pricing

- **Free Tier**: $5 credit gratis per bulan
- **Hobby**: $5/month untuk lebih banyak resources
- **Pro**: $20/month untuk production apps

Untuk project kecil, free tier sudah cukup!

## Tips

1. **Optimize Build Time:**
   - Gunakan `.dockerignore` (sudah ada)
   - Cache dependencies dengan layer caching

2. **Environment Variables:**
   - Jangan commit `.env` ke Git (sudah di `.gitignore`)
   - Set semua variables di Railway dashboard

3. **Database:**
   - Railway database otomatis backup
   - Bisa export/import via Railway dashboard

4. **Logs:**
   - Cek logs di Railway dashboard jika ada error
   - Laravel logs ada di `storage/logs/laravel.log`

## Support

- Railway Docs: https://docs.railway.app
- Railway Discord: https://discord.gg/railway
- Laravel Docs: https://laravel.com/docs

---

**Selamat deploy! 🚀**

