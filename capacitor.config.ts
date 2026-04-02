import type { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'com.Vitrine Helice.app',
  appName: 'Vitrine Helice',
  webDir: 'public',
  server: {
    url: 'https://Vitrine Helicetestes.infotech.app.br',
    cleartext: true
  },
  plugins: {
    CapacitorAssets: {
      icon: "assets/icon.png",
      splash: "assets/splash.png"
    }
  }
};

export default config;
