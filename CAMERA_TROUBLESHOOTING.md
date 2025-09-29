# Camera Scanner Troubleshooting Guide

## Problem: "Camera streaming not supported by the browser"

### Kemungkinan Penyebab dan Solusi:

### 1. **Protocol HTTP vs HTTPS**
**Masalah:** Browser modern (Chrome, Firefox, Safari) memblokir akses kamera pada HTTP untuk alasan keamanan.

**Solusi:**
- Gunakan HTTPS
- Untuk development lokal, gunakan `localhost` atau `127.0.0.1` (biasanya diizinkan)
- Atau gunakan tools seperti ngrok untuk tunnel HTTPS

**Testing:**
```bash
# Test dengan HTTPS
https://yoursite.com/path/to/scanner

# Atau untuk local development
http://localhost/dev_tamu/...
http://127.0.0.1/dev_tamu/...
```

### 2. **Permission Kamera**
**Masalah:** User belum memberikan izin akses kamera.

**Solusi:**
- Klik "Allow" saat browser meminta izin kamera
- Check browser settings untuk camera permissions
- Reset site permissions jika perlu

### 3. **Browser Compatibility**
**Masalah:** Browser lama tidak mendukung `getUserMedia()` API.

**Browser yang didukung:**
- Chrome 53+
- Firefox 36+
- Safari 11+
- Edge 12+

### 4. **Kamera sedang digunakan aplikasi lain**
**Masalah:** Kamera sedang digunakan oleh aplikasi lain.

**Solusi:**
- Tutup aplikasi lain yang menggunakan kamera
- Restart browser
- Restart device jika perlu

### 5. **Device tidak memiliki kamera**
**Masalah:** Device tidak memiliki kamera atau driver bermasalah.

**Solusi:**
- Check Device Manager (Windows)
- Update camera drivers
- Test kamera dengan aplikasi lain

## Testing Tools

### 1. **Camera Test Page**
Akses: `http://yoursite.com/public/camera-test.html`

Page ini akan test:
- Browser support
- Available cameras
- Camera stream
- QR Scanner functionality

### 2. **Manual QR Input**
Jika scanner tidak bisa akses kamera, gunakan input manual di halaman scanner.

### 3. **Browser Developer Tools**
```javascript
// Test camera access di console
navigator.mediaDevices.getUserMedia({ video: true })
  .then(stream => console.log('Camera OK'))
  .catch(err => console.error('Camera Error:', err));
```

## Debugging Steps

### Step 1: Check Protocol
```javascript
console.log('Protocol:', window.location.protocol);
// Should be 'https:' for production
```

### Step 2: Check Camera Support
```javascript
console.log('getUserMedia support:', !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia));
```

### Step 3: Check Available Cameras
```javascript
navigator.mediaDevices.enumerateDevices()
  .then(devices => {
    const cameras = devices.filter(d => d.kind === 'videoinput');
    console.log('Available cameras:', cameras);
  });
```

### Step 4: Test Camera Access
```javascript
navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
  .then(stream => {
    console.log('Camera stream OK');
    stream.getTracks().forEach(track => track.stop()); // Stop stream
  })
  .catch(err => console.error('Camera error:', err));
```

## Common Error Messages & Solutions

| Error | Meaning | Solution |
|-------|---------|----------|
| `NotAllowedError` | Permission denied | Allow camera access in browser |
| `NotFoundError` | No camera found | Check camera hardware/drivers |
| `NotSupportedError` | Not supported | Use HTTPS or newer browser |
| `OverconstrainedError` | Camera constraints not met | Try different camera settings |
| `NotReadableError` | Camera hardware error | Check if camera is used by another app |

## Development vs Production

### Development (XAMPP/Local)
```
http://localhost/dev_tamu/...  ✅ Usually works
http://127.0.0.1/dev_tamu/...  ✅ Usually works
http://192.168.1.x/dev_tamu/... ❌ May not work
```

### Production
```
https://yoursite.com/...  ✅ Required for camera access
http://yoursite.com/...   ❌ Will not work
```

## Quick Fixes

1. **Use localhost for development**
2. **Add manual QR input as fallback**
3. **Show clear error messages to users**
4. **Provide camera troubleshooting instructions**
5. **Test on multiple browsers/devices**

## Mobile Considerations

- Mobile browsers may have different camera access behaviors
- iOS Safari requires user interaction before camera access
- Android Chrome works well with HTTPS
- Test on actual devices, not just browser dev tools

## Security Considerations

- HTTPS is mandatory for production
- Camera access requires user permission
- Don't store camera streams
- Inform users about camera usage
