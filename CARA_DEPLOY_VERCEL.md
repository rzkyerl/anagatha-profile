# 🚀 Cara Deploy ke Vercel - Panduan Cepat

## Langkah 1: Siapkan Environment Variables

Sebelum deploy, siapkan environment variables berikut di Vercel:

### Wajib:
- `APP_KEY` - Generate dengan: `php artisan key:generate`
- `APP_ENV` = `production`
- `APP_DEBUG` = `false`
- `APP_URL` = (akan otomatis terisi setelah deploy pertama)

### Untuk Contact Form (Email):
- `MAIL_MAILER` = `smtp`
- `MAIL_HOST` = (contoh: `smtp.gmail.com`)
- `MAIL_PORT` = `587`
- `MAIL_USERNAME` = (email Anda)
- `MAIL_PASSWORD` = (password email)
- `MAIL_ENCRYPTION` = `tls`
- `MAIL_FROM_ADDRESS` = (contoh: `info@anagataexecutive.com`)
- `MAIL_FROM_NAME` = `Anagata Executive`

## Langkah 2: Deploy via Vercel Dashboard

1. **Buka [vercel.com](https://vercel.com)** dan login
2. **Klik "Add New" → "Project"**
3. **Pilih repository** Anda (GitHub/GitLab/Bitbucket)
4. **Konfigurasi Project:**
   - Framework Preset: **Other**
   - Root Directory: **.** (titik)
   - Build Command: *(sudah ada di vercel.json)*
   - Output Directory: **public**
   - Install Command: `composer install --no-dev --optimize-autoloader`
5. **Tambahkan Environment Variables** (Langkah 1)
6. **Klik "Deploy"**

## Langkah 3: Deploy via CLI (Alternatif)

```bash
# Install Vercel CLI
npm install -g vercel

# Login
vercel login

# Deploy
vercel --prod
```

## ⚠️ Catatan Penting

### 1. Generate APP_KEY
Jika belum punya APP_KEY, jalankan di local:
```bash
php artisan key:generate
```
Copy hasilnya ke environment variable `APP_KEY` di Vercel.

### 2. Session & Cache
Untuk Vercel (serverless), gunakan database untuk session:
- Set `SESSION_DRIVER=database` di environment variables
- Atau gunakan `cache` driver

### 3. File Upload
Jangan gunakan local storage untuk upload file. Gunakan:
- AWS S3
- Cloudinary
- Vercel Blob

### 4. Test Setelah Deploy
- ✅ Test semua halaman (/, /about, /services, dll)
- ✅ Test contact form
- ✅ Pastikan CSS dan images ter-load

## 📁 File yang Sudah Dibuat

✅ `vercel.json` - Konfigurasi routing  
✅ `api/index.php` - Entry point untuk Vercel  
✅ `.vercelignore` - File yang di-exclude  

## 🆘 Troubleshooting

**Error: Storage tidak writable**
- Normal untuk Vercel, storage akan dibuat otomatis

**Email tidak terkirim**
- Cek konfigurasi SMTP di environment variables
- Pastikan password email benar

**Halaman error 500**
- Cek logs di Vercel Dashboard
- Pastikan semua environment variables sudah di-set

## 📞 Bantuan

- Vercel Docs: https://vercel.com/docs
- Cek logs: Vercel Dashboard → Project → Deployments → Logs

---

**Selamat deploy! 🎉**

