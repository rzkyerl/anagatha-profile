# switch-env.ps1
param(
    [Parameter(Mandatory=$true)]
    [ValidateSet("production", "testing")]
    [string]$Environment
)

if ($Environment -eq "production") {
    if (Test-Path ".env.production.backup") {
        Copy-Item ".env.production.backup" ".env" -Force
        Write-Host "Switched to PRODUCTION environment" -ForegroundColor Green
    } else {
        Write-Host "Error: .env.production.backup not found!" -ForegroundColor Red
    }
} elseif ($Environment -eq "testing") {
    if (Test-Path ".env.testing") {
        Copy-Item ".env.testing" ".env" -Force
        Write-Host "Switched to TESTING environment" -ForegroundColor Green
    } else {
        Write-Host "Error: .env.testing not found!" -ForegroundColor Red
    }
}

php artisan config:clear
php artisan cache:clear
Write-Host "Cache cleared!" -ForegroundColor Yellow