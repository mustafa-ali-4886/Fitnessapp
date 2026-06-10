# ⚡ BMI Calculator - Quick Start Cheat Sheet

## 🏃 5-Minute Setup

### 1️⃣ **Folder Setup**
```bash
mkdir bmi-calculator
cd bmi-calculator
```

### 2️⃣ **Copy 3 Files**
- `bmi-calculator-updated.html`
- `server.js`
- `package.json`

### 3️⃣ **Install Dependencies**
```bash
npm install
```

### 4️⃣ **Get Gmail Password**
1. Gmail account → Settings → Security
2. App passwords → Select Mail + Windows Computer
3. Copy 16-character password

### 5️⃣ **Set Email & Password**

**Windows (PowerShell):**
```powershell
$env:EMAIL_USER="your-email@gmail.com"
$env:EMAIL_PASSWORD="xxxx xxxx xxxx xxxx"
```

**Mac/Linux:**
```bash
export EMAIL_USER="your-email@gmail.com"
export EMAIL_PASSWORD="xxxx xxxx xxxx xxxx"
```

### 6️⃣ **Start Server**
```bash
npm start
```

Expected output:
```
✅ BMI Report Server running on http://localhost:3000
📧 Email configured with: your-email@gmail.com
```

### 7️⃣ **Open Browser**
- Double-click on `bmi-calculator-updated.html`
- Or drag it to Chrome/Firefox

### 8️⃣ **Test It!**
1. Enter weight & height
2. Click "Calculate BMI"
3. Enter email address
4. Click "Send me Report"
5. Check your email! 📧

---

## 🎯 File Purposes

| File | Purpose |
|------|---------|
| `bmi-calculator-updated.html` | Frontend (open in browser) |
| `server.js` | Backend (handles PDF + email) |
| `package.json` | Dependencies list |
| `SETUP_GUIDE.md` | Full documentation |

---

## ⚠️ Common Issues & Fixes

| Issue | Fix |
|-------|-----|
| "Connection error" | Run `npm start` in terminal |
| "Email not received" | Check spam folder / verify Gmail app password |
| "Port 3000 in use" | Run `npm start` in different terminal |
| Server won't start | Delete `node_modules`, run `npm install` again |

---

## 🔑 Key Features

✅ Dark theme with neon green  
✅ Beautiful slot machine animation  
✅ Email validation  
✅ PDF generation  
✅ Loading animations  
✅ Success/Error messages  
✅ Mobile responsive  
✅ Professional design  

---

## 📱 Features in Browser

After clicking "Calculate BMI":
- BMI displays in animated slot machine
- Category badge appears (Underweight/Normal/Overweight/Obese)
- Health tip shows up
- Scale bar with marker
- **"Send me Report" section appears** ← Email input here

---

## 🔒 Gmail Setup (Most Important!)

If you skip this, email won't work:

1. **Enable 2FA:**
   - Google Account → Security
   - Enable "2-Step Verification"

2. **Get App Password:**
   - Google Account → Security → App passwords
   - Phone will verify
   - Select "Mail" + "Windows Computer"
   - Copy the 16-character password

3. **Set Environment Variables:**
   - Paste email and password
   - Restart server

---

## 🚀 Commands Reference

```bash
# Install dependencies
npm install

# Start server
npm start

# Stop server
Ctrl + C

# Check if Node.js installed
node --version

# Check npm installed
npm --version
```

---

## 🌐 URLs

- **Frontend:** Double-click HTML file or drag to browser
- **Backend Health Check:** http://localhost:3000/health
- **API Endpoint:** http://localhost:3000/send-report (POST)

---

## 📋 What Gets Sent in Email

📄 **PDF Report includes:**
- User's BMI value (large, highlighted)
- Category (Underweight/Normal/Overweight/Obese)
- Weight & Height used
- Health insight message
- BMI reference chart
- Professional formatting

📧 **Email includes:**
- Nice HTML template
- Quick summary in email body
- PDF attachment with full report

---

## 💡 Pro Tips

1. **Test locally first** before going live
2. **Keep email password private** in env files
3. **Check spam folder** for test emails
4. **Keep server running** while using the app
5. **Use incognito mode** for clean testing

---

## 🎨 Customization

Want to change email/colors?

**In server.js:**
```javascript
// Change sender email
from: process.env.EMAIL_USER,

// Change email template (HTML part)
html: `Your custom template here`
```

**In HTML:**
```javascript
// Change API URL
fetch('http://localhost:3000/send-report', {
  // Change to your server URL
})
```

---

## 🆘 Still Not Working?

1. **Terminal open** (check server running)
2. **Browser console** (F12 → Console tab)
3. **Network tab** (F12 → Network tab)
4. **Screenshot error** messages
5. **Check SETUP_GUIDE.md** for detailed troubleshooting

---

## ✨ What You Get

✅ Professional BMI calculator  
✅ Automatic PDF generation  
✅ Email delivery system  
✅ Beautiful UI with animations  
✅ Mobile responsive design  
✅ Error handling & validation  
✅ Production-ready code  
✅ Full documentation  

---

**Ready? Let's go! Start with Step 1️⃣** 🚀

---

*Last Updated: 2024 | Version 1.0.0*
