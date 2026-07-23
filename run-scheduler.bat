@echo off
REM ============================================================
REM  Script: run-scheduler.bat
REM  Fungsi: Jalankan Laravel Scheduler setiap 1 menit
REM  Cara pakai: Daftarkan ke Windows Task Scheduler dengan
REM              trigger "Every 1 minute, repeat indefinitely"
REM ============================================================

cd /d "d:\laravel12\api-smartschool"
php artisan schedule:run >> "storage\logs\scheduler.log" 2>&1
