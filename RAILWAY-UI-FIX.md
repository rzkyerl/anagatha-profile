# Fix UI Tampilan Aneh di Railway

## Masalah
CSS dan assets tidak ter-load dengan benar, menyebabkan tampilan UI aneh.

## Penyebab
`APP_URL` di Environment Variables tidak sesuai dengan URL Railway yang sebenarnya, sehingga helper `asset()` menghasilkan URL yang salah.

## Solusi

### 1. Update APP_URL di Railway

1. Buka Railway Dashboard
2. Pilih service Anda
3. Buka tab **"Variables"**
4. Cari variable `APP_URL`
5. Update dengan URL Railway yang benar:
   ```
   APP_URL=https://anagatha-profile-production.up.railway.app
   ```
   (Ganti dengan URL Railway Anda yang sebenarnya)

6. Klik **"Save"**

### 2. Pastikan URL menggunakan HTTPS

Pastikan `APP_URL` menggunakan `https://` bukan `http://`:
```
✅ BENAR: https://anagatha-profile-production.up.railway.app
❌ SALAH: http://anagatha-profile-production.up.railway.app
```

### 3. Redeploy Service

Setelah update `APP_URL`:
1. Buka tab **"Settings"**
2. Klik **"Redeploy"**
3. Atau tunggu auto-redeploy

### 4. Clear Browser Cache

Setelah redeploy:
1. Clear browser cache (Ctrl+Shift+Delete)
2. Atau buka dalam Incognito/Private mode
3. Refresh halaman

## Verifikasi

Setelah update, cek di browser:
1. Buka Developer Tools (F12)
2. Tab **"Network"**
3. Refresh halaman
4. Cek apakah file CSS ter-load:
   - `styles/style.css` → Status 200 ✅
   - `assets/hero-section.jpeg` → Status 200 ✅

Jika masih 404, berarti path salah atau file tidak ada.

## Alternative: Set ASSET_URL

Jika masih bermasalah, tambahkan variable `ASSET_URL`:

```
ASSET_URL=https://anagatha-profile-production.up.railway.app
```

Ini akan memaksa Laravel menggunakan URL ini untuk semua assets.

## Troubleshooting

### CSS masih tidak ter-load

1. **Cek file ada di server:**
   - Buka: `https://your-app.railway.app/styles/style.css`
   - Jika 404, file tidak ter-copy dengan benar

2. **Cek APP_URL:**
   - Pastikan sesuai dengan URL Railway
   - Pastikan menggunakan HTTPS

3. **Cek Deploy Logs:**
   - Pastikan build berhasil
   - Pastikan file CSS ter-copy ke `public/styles/`

### Assets 404

1. **Cek path di view:**
   ```php
   {{ asset('styles/style.css') }}
   ```
   Ini akan mencari di `public/styles/style.css`

2. **Pastikan file ada:**
   - File harus ada di `public/styles/style.css`
   - File harus ter-copy saat build

### Mixed Content Error

Jika ada error "Mixed Content" (HTTP di HTTPS):
- Pastikan `APP_URL` menggunakan `https://`
- Pastikan semua external resources menggunakan HTTPS

## Quick Fix

Jika masih tidak berhasil, coba:

1. **Update APP_URL:**
   ```
   APP_URL=https://anagatha-profile-production.up.railway.app
   ```

2. **Clear Laravel cache:**
   - Buka Railway CLI atau Deploy Logs
   - Run: `php artisan config:clear`
   - Run: `php artisan cache:clear`

3. **Redeploy**

## Catatan

- `APP_URL` harus sesuai dengan URL Railway yang sebenarnya
- Gunakan HTTPS untuk production
- Clear browser cache setelah update
- File assets harus ada di folder `public/`

