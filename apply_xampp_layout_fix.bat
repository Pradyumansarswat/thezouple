@echo off
copy /Y "C:\Users\HP\Desktop\thezouple\default_layout.fixed.blade.php" "C:\xampp\htdocs\thezouple\resources\views\front\layout\default_layout.blade.php"
cd /D "C:\xampp\htdocs\thezouple"
php artisan view:clear
pause
