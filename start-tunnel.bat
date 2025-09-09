@echo off
echo ========================================
echo HTech Manhwa - Ngrok Tunnel Launcher
echo ========================================
echo.

echo 🚀 Starting Laravel server and ngrok tunnel...
echo.

REM Check if Laravel server is already running
echo 🔍 Checking for existing Laravel server...
netstat -an | find ":8000" > nul
if %errorlevel% == 0 (
    echo ✅ Laravel server is already running on port 8000
    echo.
    echo 🔗 Starting ngrok tunnel...
    npm run tunnel
) else (
    echo 📍 Laravel server not running. Starting both server and tunnel...
    echo.
    echo 💡 This will open two processes:
    echo    1. Laravel development server on http://localhost:8000
    echo    2. Ngrok tunnel for external access
    echo.
    
    REM Start both Laravel server and ngrok tunnel
    npm run serve
)

echo.
echo 🛑 Press Ctrl+C to stop both services
pause
