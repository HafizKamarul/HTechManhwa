#!/usr/bin/env node

/**
 * Enhanced Ngrok Tunnel Script for HTech Manhwa
 * Usage: npm run tunnel
 */

import ngrok from 'ngrok';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Load environment variables from .env file
function loadEnvFile() {
    const envPath = path.join(__dirname, '..', '.env');
    const env = {};
    
    if (fs.existsSync(envPath)) {
        const content = fs.readFileSync(envPath, 'utf8');
        content.split('\n').forEach(line => {
            const match = line.match(/^([^#][^=]+)=(.*)$/);
            if (match) {
                const key = match[1].trim();
                const value = match[2].trim().replace(/^["']|["']$/g, '');
                env[key] = value;
            }
        });
    }
    
    return env;
}

(async function() {
    try {
        const env = loadEnvFile();
        
        console.log('🚀 HTech Manhwa - Ngrok Tunnel');
        console.log('===============================');
        console.log('📍 Starting tunnel for localhost:' + (env.NGROK_PORT || '8000'));
        
        // Build ngrok options
        const options = {
            port: parseInt(env.NGROK_PORT) || 8000,
            region: env.NGROK_REGION || 'us'
        };
        
        // Add subdomain if specified (requires paid plan)
        if (env.NGROK_SUBDOMAIN) {
            options.subdomain = env.NGROK_SUBDOMAIN;
            console.log('🏷️  Using subdomain: ' + env.NGROK_SUBDOMAIN);
        }
        
        // Add auth token if specified
        if (env.NGROK_AUTH_TOKEN) {
            options.authtoken = env.NGROK_AUTH_TOKEN;
        }
        
        console.log('🌍 Region: ' + options.region);
        console.log('🔗 Connecting...');
        
        const url = await ngrok.connect(options);
        
        console.log('\n🎉 Success! Your HTech Manhwa platform is now live!');
        console.log('=' .repeat(60));
        console.log(`🔗 Public URL: ${url}`);
        console.log(`📱 Local URL:  http://localhost:${options.port}`);
        console.log('=' .repeat(60));
        
        // Save URL to file
        const urlFile = path.join(__dirname, '..', '.ngrok-url.txt');
        const urlInfo = {
            public_url: url,
            local_url: `http://localhost:${options.port}`,
            generated_at: new Date().toISOString(),
            region: options.region
        };
        
        fs.writeFileSync(urlFile, JSON.stringify(urlInfo, null, 2));
        console.log(`💾 URL info saved to: .ngrok-url.txt`);
        
        console.log('\n📋 Usage Instructions:');
        console.log('   • Share the Public URL with others to access your platform');
        console.log('   • Make sure your Laravel server is running on port ' + options.port);
        console.log('   • Press Ctrl+C to stop the tunnel');
        console.log('\n🛡️  Security: Only share this URL with trusted users');
        console.log('⏱️  Tunnel will remain active until stopped\n');
        
        // Keep the tunnel alive
        process.on('SIGINT', async () => {
            console.log('\n\n🔒 Closing ngrok tunnel...');
            await ngrok.kill();
            
            // Clean up URL file
            if (fs.existsSync(urlFile)) {
                fs.unlinkSync(urlFile);
            }
            
            console.log('✅ Tunnel closed successfully!');
            process.exit(0);
        });
        
        // Keep process alive and show periodic status
        let statusCount = 0;
        setInterval(() => {
            statusCount++;
            if (statusCount % 60 === 0) { // Every minute
                console.log(`⏰ Tunnel active for ${statusCount} seconds - ${url}`);
            }
        }, 1000);
        
    } catch (error) {
        console.error('\n❌ Error starting tunnel:', error.message);
        
        if (error.message.includes('authtoken') || error.message.includes('authentication')) {
            console.log('\n💡 Authentication Required:');
            console.log('   1. Sign up: https://dashboard.ngrok.com/signup');
            console.log('   2. Get your auth token: https://dashboard.ngrok.com/get-started/your-authtoken');
            console.log('   3. Add to .env file: NGROK_AUTH_TOKEN=your_token_here');
            console.log('   4. Or run: npx ngrok authtoken YOUR_TOKEN_HERE');
        } else if (error.message.includes('connection refused')) {
            console.log('\n💡 Laravel Server Not Running:');
            console.log('   Start the server first: php artisan serve --host=0.0.0.0 --port=8000');
            console.log('   Or use: npm run serve:local');
        } else if (error.message.includes('subdomain')) {
            console.log('\n💡 Subdomain Issue:');
            console.log('   Custom subdomains require a paid ngrok plan');
            console.log('   Remove NGROK_SUBDOMAIN from .env to use random subdomain');
        }
        
        process.exit(1);
    }
})();
