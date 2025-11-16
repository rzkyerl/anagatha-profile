# Railway Setup untuk Laravel Application

Dokumentasi ini menjelaskan cara setup aplikasi Laravel di Railway.

## Setup di Railway

### Service Web (Main Application)

1. Di Railway dashboard, buat atau gunakan service yang sudah ada
2. Set environment variables:
   - `SERVICE_TYPE=web` (atau tidak perlu di-set, default adalah web)
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - Dan environment variables lainnya (database, dll)

3. Service ini akan menjalankan web server

## Cara Setup di Railway Dashboard

### Langkah-langkah:

1. **Buka Railway Dashboard** → Pilih project Anda

2. **Untuk Service Web:**
   - Klik "New" → "GitHub Repo" (atau pilih repo yang sudah ada)
   - Railway akan auto-detect Dockerfile
   - Set environment variables:
     ```
     SERVICE_TYPE=web
     ```
   - Set environment variables lainnya (database, APP_KEY, dll)

3. **Deploy service**

## Verifikasi

Setelah deploy:

1. **Service Web** harus running dan bisa diakses

## Troubleshooting

### Error: Healthcheck Failed / Service Unavailable

**Masalah:** Build berhasil tapi healthcheck gagal, service tidak bisa diakses.

**Solusi:**

1. **Cek Runtime Logs (BUKAN Build Logs):**
   - Di Railway dashboard, buka service web Anda
   - Klik tab **"Logs"** (bukan "Deployments")
   - Scroll ke bawah untuk melihat log terbaru saat container start
   - Cari error seperti:
     - `SQLSTATE[HY000] [2002] Connection refused` → Database connection issue
     - `No application encryption key has been specified` → APP_KEY missing
     - `Port already in use` → Port conflict
     - `Starting PHP built-in server...` → Service start message (ini bagus)

2. **Pastikan Environment Variables Sudah Di-Set:**
   - Buka tab "Variables" di service web
   - Pastikan sudah ada:
     - Database variables (DB_HOST, DB_DATABASE, dll)
     - APP_KEY
     - SERVICE_TYPE=web (atau tidak perlu di-set, default adalah web)

3. **Cek Apakah Service Benar-Benar Running:**
   - Di Railway dashboard, lihat status service
   - Jika status "Crashing" atau "Failed", cek logs untuk error
   - Jika status "Building", tunggu sampai selesai

4. **Jika Masih Gagal:**
   - Cek apakah MySQL service sudah running
   - Pastikan semua reference variables menggunakan format yang benar: `${{ MySQL.MYSQLHOST }}`
   - Redeploy service setelah set environment variables

### Error: "Connection refused" atau "SQLSTATE[HY000] [2002] Connection refused"

**Masalah:** Service web tidak bisa connect ke database.

**Solusi:**

1. **Pastikan MySQL service sudah dibuat di Railway:**
   - Di Railway dashboard, klik "New" → "Database" → "Add MySQL"
   - Tunggu sampai MySQL service selesai dibuat

2. **Set Database Environment Variables di Web Service:**
   - Buka service web Anda di Railway dashboard
   - Klik tab "Variables"
   - Tambahkan/set environment variables berikut:
   
   **Cara 1: Set Manual (Paling Mudah - Direkomendasikan)**
   - Buka MySQL service → tab "Variables"
   - Copy nilai dari: `MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER`, `MYSQLPASSWORD`
   - Di service web, set:
     ```
     DB_CONNECTION=mysql
     DB_HOST=<paste nilai MYSQLHOST>
     DB_PORT=<paste nilai MYSQLPORT>
     DB_DATABASE=<paste nilai MYSQLDATABASE>
     DB_USERNAME=<paste nilai MYSQLUSER>
     DB_PASSWORD=<paste nilai MYSQLPASSWORD>
     ```
   
   **Cara 2: Reference Variables (Jika "No Suggestion", ketik manual)**
   - Ketik manual di service web:
     ```
     DB_CONNECTION=mysql
     DB_HOST=${{ MySQL.MYSQLHOST }}
     DB_PORT=${{ MySQL.MYSQLPORT }}
     DB_DATABASE=${{ MySQL.MYSQLDATABASE }}
     DB_USERNAME=${{ MySQL.MYSQLUSER }}
     DB_PASSWORD=${{ MySQL.MYSQLPASSWORD }}
     ```
   - **Catatan:** Ganti `MySQL` dengan nama service MySQL Anda (cek di dashboard)
   - Jika "Reference Variable" tidak muncul, gunakan Cara 1 (set manual)

3. **Link MySQL service ke Web service:**
   - Di service web, pastikan MySQL service sudah di-link
   - Railway biasanya auto-link jika menggunakan reference variables

4. **Deploy ulang service web**

### Error: "No application encryption key has been specified"

**Masalah:** `APP_KEY` belum di-set.

**Solusi:**

1. **Generate APP_KEY:**
   ```bash
   # Di local (atau via Railway CLI)
   php artisan key:generate --show
   ```
   Atau via Railway:
   ```bash
   railway run php artisan key:generate --show
   ```

2. **Set APP_KEY di Railway:**
   - Buka service web di Railway dashboard
   - Klik tab "Variables"
   - Tambahkan:
     ```
     APP_KEY=base64:... (paste hasil dari key:generate)
     ```

3. **Deploy ulang service**

### Migration error
- Migration akan dijalankan otomatis saat startup
- Pastikan database credentials sudah benar di environment variables
- Pastikan MySQL service sudah running dan ter-link ke web service

## Environment Variables yang Diperlukan

### Untuk Service Web:
```
SERVICE_TYPE=web (optional, default)
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
DB_HOST=...
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
APP_KEY=base64:...
```

