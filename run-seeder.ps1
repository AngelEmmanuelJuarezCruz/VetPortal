# PowerShell script to run the test appointment seeder

$projectPath = "c:\xampp\htdocs\example-app"
Set-Location $projectPath

Write-Host "Ejecutando seeder de prueba..." -ForegroundColor Cyan
Write-Host "================================" -ForegroundColor Cyan

& php artisan db:seed --class=TestAppointmentSeeder

Write-Host ""
Write-Host "================================" -ForegroundColor Cyan
Write-Host "Verificando archivo de logs..." -ForegroundColor Cyan
Write-Host "================================" -ForegroundColor Cyan

if (Test-Path "storage/logs/laravel.log") {
    Write-Host "`nÚltimas líneas del log:" -ForegroundColor Yellow
    Get-Content storage/logs/laravel.log -Tail 50
}
else {
    Write-Host "Archivo de logs no encontrado" -ForegroundColor Red
}
