#!/usr/bin/env node

/**
 * Ngrok Setup Script for HTech Manhwa Platform
 * 
 * This script helps configure ngrok for secure tunneling
 * Run with: npm run tunnel:setup
 */

import ngrok from 'ngrok';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

console.log('🚀 HTech Manhwa - Ngrok Setup Script');
console.log('=====================================');

// Configuration
const config = {
    port: 8000,
    subdomain: null, // Will be set based on user preference
    authtoken: null, // User needs to provide this
    region: 'us' // Default to US, can be changed
};

async function setupNgrok() {
    try {
        console.log('\n📋 Ngrok Configuration:');
        console.log(`   Port: ${config.port}`);
        console.log(`   Region: ${config.region}`);
        
        // Check if auth token is configured
        const ngrokConfigPath = path.join(process.env.USERPROFILE || process.env.HOME, '.ngrok2', 'ngrok.yml');
        
        if (!fs.existsSync(ngrokConfigPath)) {
            console.log('\n⚠️  Ngrok auth token not found!');
            console.log('\n📝 To get started:');
            console.log('   1. Sign up at: https://dashboard.ngrok.com/signup');
            console.log('   2. Get your auth token: https://dashboard.ngrok.com/get-started/your-authtoken');
            console.log('   3. Run: npx ngrok authtoken YOUR_TOKEN_HERE');
            console.log('\n   Then run this script again!');
            return;
        }
        
        console.log('\n✅ Ngrok configuration found!');
        
        // Test connection
        console.log('\n🔗 Testing ngrok connection...');
        
        const url = await ngrok.connect({
            port: config.port,
            region: config.region,
            onStatusChange: status => {
                console.log(`   Status: ${status}`);
            },
            onLogEvent: data => {
                console.log(`   ${data}`);
            }
        });
        
        console.log('\n🎉 Success! Your HTech Manhwa platform is now accessible at:');
        console.log(`   ${url}`);
        console.log('\n📱 Share this URL to allow others to access your manhwa platform!');
        
        // Save the URL to a file for reference
        const urlFile = path.join(__dirname, '..', '.ngrok-url.txt');
        fs.writeFileSync(urlFile, `${url}\nGenerated: ${new Date().toISOString()}`);
        console.log(`\n💾 URL saved to: ${urlFile}`);
        
        console.log('\n🛑 Press Ctrl+C to stop the tunnel when done');
        
        // Keep the process running
        process.on('SIGINT', async () => {
            console.log('\n\n🔒 Closing ngrok tunnel...');
            await ngrok.kill();
            console.log('✅ Tunnel closed successfully!');
            process.exit(0);
        });
        
        // Keep alive
        setInterval(() => {
            // Just keep the process running
        }, 1000);
        
    } catch (error) {
        console.error('\n❌ Error setting up ngrok:', error.message);
        
        if (error.message.includes('authtoken')) {
            console.log('\n💡 Solution:');
            console.log('   1. Get your auth token: https://dashboard.ngrok.com/get-started/your-authtoken');
            console.log('   2. Run: npx ngrok authtoken YOUR_TOKEN_HERE');
        }
        
        process.exit(1);
    }
}

// Handle different modes
const args = process.argv.slice(2);
const mode = args[0] || 'setup';

switch (mode) {
    case 'setup':
    case 'start':
        setupNgrok();
        break;
    case 'status':
        console.log('📊 Checking ngrok status...');
        ngrok.getApi().then(api => {
            return api.listTunnels();
        }).then(tunnels => {
            if (tunnels.tunnels.length > 0) {
                console.log('\n🔗 Active tunnels:');
                tunnels.tunnels.forEach(tunnel => {
                    console.log(`   ${tunnel.public_url} -> ${tunnel.config.addr}`);
                });
            } else {
                console.log('\n💤 No active tunnels');
            }
        }).catch(err => {
            console.log('\n❌ Could not get tunnel status:', err.message);
        });
        break;
    case 'stop':
        console.log('🛑 Stopping all ngrok tunnels...');
        ngrok.kill().then(() => {
            console.log('✅ All tunnels stopped!');
        }).catch(err => {
            console.error('❌ Error stopping tunnels:', err.message);
        });
        break;
    default:
        console.log('\n📖 Usage:');
        console.log('   npm run tunnel:setup          # Setup and start tunnel');
        console.log('   node scripts/ngrok-setup.js status   # Check tunnel status');
        console.log('   node scripts/ngrok-setup.js stop     # Stop all tunnels');
}
