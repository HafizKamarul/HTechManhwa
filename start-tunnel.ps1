# HTech Manhwa - Ngrok Tunnel Launcher (PowerShell)
# Usage: .\start-tunnel.ps1

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "HTech Manhwa - Ngrok Tunnel Launcher" -ForegroundColor Cyan  
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "🚀 Starting Laravel server and ngrok tunnel..." -ForegroundColor Green
Write-Host ""

# Check if Laravel server is already running
Write-Host "🔍 Checking for existing Laravel server..." -ForegroundColor Yellow

$port8000 = Get-NetTCPConnection -LocalPort 8000 -ErrorAction SilentlyContinue

if ($port8000) {
    Write-Host "✅ Laravel server is already running on port 8000" -ForegroundColor Green
    Write-Host ""
    Write-Host "🔗 Starting ngrok tunnel..." -ForegroundColor Blue
    
    npm run tunnel
} else {
    Write-Host "📍 Laravel server not running. Starting both server and tunnel..." -ForegroundColor Yellow
    Write-Host ""
    Write-Host "💡 This will start two processes:" -ForegroundColor Blue
    Write-Host "   1. Laravel development server on http://localhost:8000" -ForegroundColor White
    Write-Host "   2. Ngrok tunnel for external access" -ForegroundColor White
    Write-Host ""
    
    # Check if npm and php are available
    try {
        $npmVersion = npm --version 2>$null
        $phpVersion = php --version 2>$null
        
        if (!$npmVersion) {
            Write-Host "❌ npm not found. Please install Node.js first." -ForegroundColor Red
            exit 1
        }
        
        if (!$phpVersion) {
            Write-Host "❌ PHP not found. Please ensure PHP is installed and in PATH." -ForegroundColor Red
            exit 1
        }
        
        Write-Host "✅ npm and PHP found. Starting services..." -ForegroundColor Green
        
        # Start both Laravel server and ngrok tunnel
        npm run serve
        
    } catch {
        Write-Host "❌ Error checking dependencies: $_" -ForegroundColor Red
        Write-Host ""
        Write-Host "💡 Make sure you have:" -ForegroundColor Yellow
        Write-Host "   • Node.js and npm installed" -ForegroundColor White
        Write-Host "   • PHP installed and in PATH" -ForegroundColor White
        Write-Host "   • Run 'npm install' in the project directory" -ForegroundColor White
    }
}

Write-Host ""
Write-Host "🛑 Press Ctrl+C to stop both services" -ForegroundColor Red
