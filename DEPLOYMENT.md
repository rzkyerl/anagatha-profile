# Panduan Deploy Laravel ke Vercel

## Prasyarat

1. Akun Vercel (gratis di [vercel.com](https://vercel.com))
2. Git repository (GitHub, GitLab, atau Bitbucket)
3. Vercel CLI (opsional, untuk deploy via command line)

## Langkah-langkah Deployment

### 1. Install Vercel CLI (Opsional)

Jika ingin deploy via command line:

```bash
npm install -g vercel
```

### 2. Setup Environment Variables

Sebelum deploy, pastikan Anda sudah menyiapkan environment variables di Vercel:

**Environment Variables yang Diperlukan:**

- `APP_KEY` - Laravel application key (generate dengan `php artisan key:generate`)
- `APP_ENV` - Set ke `production`
- `APP_DEBUG` - Set ke `false` untuk production
- `APP_URL` - URL aplikasi Anda di Vercel (akan otomatis terisi setelah deploy pertama)

**Konfigurasi Mail (untuk Contact Form):**

- `MAIL_MAILER` - Gunakan `smtp` atau `sendmail`
- `MAIL_HOST` - SMTP host (contoh: `smtp.gmail.com`)
- `MAIL_PORT` - SMTP port (contoh: `587`)
- `MAIL_USERNAME` - Email pengirim
- `MAIL_PASSWORD` - Password email
- `MAIL_ENCRYPTION` - `tls` atau `ssl`
- `MAIL_FROM_ADDRESS` - Email pengirim (contoh: `info@anagataexecutive.com`)
- `MAIL_FROM_NAME` - Nama pengirim

**Konfigurasi Database (jika diperlukan):**

Jika aplikasi menggunakan database, gunakan database eksternal seperti:
- PlanetScale
- Supabase
- Railway
- atau database hosting lainnya

### 3. Deploy via Vercel Dashboard

1. **Login ke Vercel Dashboard**
   - Kunjungi [vercel.com](https://vercel.com)
   - Login dengan GitHub/GitLab/Bitbucket

2. **Import Project**
   - Klik "Add New" → "Project"
   - Pilih repository Anda
   - Vercel akan otomatis mendeteksi konfigurasi

3. **Configure Project**
   - **Framework Preset**: Pilih "Other" atau biarkan auto-detect
   - **Root Directory**: `.` (root project)
   - **Build Command**: `composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache`
   - **Output Directory**: `public` (tidak digunakan untuk Laravel, tapi tetap isi)
   - **Install Command**: `composer install --no-dev --optimize-autoloader`

4. **Add Environment Variables**
   - Tambahkan semua environment variables yang diperlukan
   - Pastikan `APP_KEY` sudah di-generate

5. **Deploy**
   - Klik "Deploy"
   - Tunggu proses build selesai

### 4. Deploy via Vercel CLI (Alternatif)

```bash
# Login ke Vercel
vercel login

# Deploy ke preview
vercel

# Deploy ke production
vercel --prod
```

## Catatan Penting

### Storage & Cache

Vercel menggunakan serverless functions, jadi:

1. **File Storage**: 
   - Jangan gunakan local file storage untuk upload
   - Gunakan cloud storage seperti AWS S3, Cloudinary, atau Vercel Blob

2. **Sessions**:
   - Gunakan database atau cache driver untuk sessions
   - Update `config/session.php`:
     ```php
     'driver' => env('SESSION_DRIVER', 'database'), // atau 'cache'
     ```

3. **Cache**:
   - Gunakan Redis atau database untuk cache
   - Update `config/cache.php`:
     ```php
     'default' => env('CACHE_DRIVER', 'database'), // atau 'redis'
     ```

### Build Optimization

Untuk performa yang lebih baik, tambahkan build command:

```bash
composer install --no-dev --optimize-autoloader && \
php artisan config:cache && \
php artisan route:cache && \
php artisan view:cache
```

### Troubleshooting

**Error: Storage directory tidak writable**
- Pastikan storage directories sudah ada
- Vercel akan membuat directories secara otomatis

**Error: Mail tidak terkirim**
- Pastikan konfigurasi SMTP sudah benar
- Gunakan service seperti SendGrid, Mailgun, atau AWS SES untuk production

**Error: Session tidak bekerja**
- Pastikan menggunakan database atau cache untuk sessions
- Jangan gunakan `file` driver untuk sessions

## File yang Sudah Dibuat

1. `vercel.json` - Konfigurasi routing Vercel
2. `api/index.php` - Entry point untuk serverless function
3. `.vercelignore` - File yang di-exclude dari deployment

## Setelah Deploy

1. Test semua halaman
2. Test contact form
3. Pastikan semua assets (CSS, images) ter-load dengan benar
4. Check error logs di Vercel dashboard jika ada masalah

## Support

Jika ada masalah, cek:
- Vercel Dashboard → Project → Deployments → Logs
- Laravel logs (jika ada)
- Vercel documentation: https://vercel.com/docs

