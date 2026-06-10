require('dotenv').config();
const express = require('express');
const nodemailer = require('nodemailer');
const cors = require('cors');
const path = require('path');
const fs = require('fs');
const { GoogleGenerativeAI } = require('@google/generative-ai');

// Read config.txt fallback to handle Windows hidden dot-file issues
let txtConfig = {};
try {
    const configPath = path.join(__dirname, 'config.txt');
    if (fs.existsSync(configPath)) {
        const fileContent = fs.readFileSync(configPath, 'utf8');
        const lines = fileContent.split(/\r?\n/);
        lines.forEach(line => {
            const cleanLine = line.trim();
            if (cleanLine && !cleanLine.startsWith('#')) {
                const parts = cleanLine.split('=');
                if (parts.length >= 2) {
                    const key = parts[0].trim();
                    const value = parts.slice(1).join('=').trim();
                    txtConfig[key] = value;
                }
            }
        });
    }
} catch (err) {
    console.error('Error reading config.txt:', err);
}

const EMAIL_USER = process.env.EMAIL_USER || txtConfig.EMAIL_USER;
const EMAIL_PASS = process.env.EMAIL_PASS || txtConfig.EMAIL_PASS;
const GEMINI_API_KEY = process.env.GEMINI_API_KEY || txtConfig.GEMINI_API_KEY;


const app = express();
const PORT = process.env.PORT || 3000;

// Enable CORS and JSON parsing
app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Serve static frontend files
app.use(express.static(__dirname));

// Serve contact page on main route
app.get('/', (req, res) => {
    res.redirect('/contact');
});

app.get('/contact', (req, res) => {
    res.sendFile(path.join(__dirname, 'contact.html'));
});

// Post endpoint for form submissions
app.post('/api/contact', async (req, res) => {
    const { firstName, lastName, email, message } = req.body;

    if (!firstName || !lastName || !email || !message) {
        return res.status(400).json({ success: false, error: 'All fields are required' });
    }

    try {
        let transporter;
        let isRealEmail = false;

        // Check if environment variables or config.txt values are provided for sending actual emails
        if (EMAIL_USER && EMAIL_USER !== 'your-email@gmail.com' && EMAIL_PASS) {
            isRealEmail = true;
            transporter = nodemailer.createTransport({
                service: 'gmail',
                auth: {
                    user: EMAIL_USER,
                    pass: EMAIL_PASS
                }
            });
            console.log('Using Gmail SMTP server to send real confirmation emails.');
        } else {
            // Create standard test SMTP service account from ethereal.email
            let testAccount = await nodemailer.createTestAccount();
            transporter = nodemailer.createTransport({
                host: 'smtp.ethereal.email',
                port: 587,
                secure: false,
                auth: {
                    user: testAccount.user,
                    pass: testAccount.pass
                }
            });
            console.log('Using Ethereal SMTP server to send test mock emails.');
        }

        // Email templates
        const mailOptions = {
            from: isRealEmail ? `"Support Team" <${EMAIL_USER}>` : `"Support Team" <support@yourdomain.com>`,
            to: email, // send directly to the user's input email
            subject: 'Confirmation: We have received your inquiry!',
            html: `
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e4e7ec; border-radius: 12px; background-color: #ffffff; color: #111111;">
                    <div style="text-align: center; margin-bottom: 24px;">
                        <h2 style="color: #000000; margin: 0; font-size: 24px;">Support Team</h2>
                        <p style="color: #666666; margin: 5px 0 0 0; font-size: 14px;">Creative Digital Solutions</p>
                    </div>
                    <hr style="border: 0; border-top: 1px solid #e4e7ec; margin: 20px 0;">
                    <div style="margin-bottom: 20px;">
                        <p style="font-size: 16px; line-height: 1.5;">Hi <strong>${firstName} ${lastName}</strong>,</p>
                        <p style="font-size: 15px; line-height: 1.5; color: #333333;">
                            Thank you for reaching out to us! We have successfully received your inquiry and our support team is already reviewing it.
                        </p>
                        <p style="font-size: 15px; line-height: 1.5; color: #333333;">
                            You can expect a detailed response from one of our specialists within <strong>24 business hours</strong>.
                        </p>
                    </div>
                    
                    <div style="background-color: #f6f7f9; border-radius: 8px; padding: 16px; margin: 24px 0;">
                        <h4 style="margin: 0 0 10px 0; font-size: 14px; color: #666666; text-transform: uppercase;">Your Message Details:</h4>
                        <p style="margin: 0; font-size: 14px; line-height: 1.5; font-style: italic; color: #555555;">
                            "${message}"
                        </p>
                    </div>
                    
                    <div style="margin-top: 30px; font-size: 14px; color: #666666; text-align: center;">
                        <p style="margin: 0 0 8px 0;">If you need urgent assistance, you can call us at +1 234 567 78.</p>
                        <p style="margin: 0; font-weight: bold; color: #000000;">&copy; 2026 Support Team. All rights reserved.</p>
                    </div>
                </div>
            `
        };

        // Send mail with defined transport object (Confirmation to client)
        let info = await transporter.sendMail(mailOptions);
        console.log('Confirmation message sent to client: %s', info.messageId);

        // Also notify the website administrator/owner with form inputs
        if (isRealEmail) {
            try {
                let adminInfo = await transporter.sendMail({
                    from: `"Support Admin" <${EMAIL_USER}>`,
                    to: EMAIL_USER, // sends to the owner
                    subject: `New Inquiry: ${firstName} ${lastName}`,
                    html: `
                        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e4e7ec; border-radius: 12px; background-color: #ffffff; color: #111111;">
                            <h2 style="color: #4f46e5; border-bottom: 2px solid #f6f7f9; padding-bottom: 12px;">New Website Contact Inquiry</h2>
                            <p style="font-size: 15px;"><strong>Name:</strong> ${firstName} ${lastName}</p>
                            <p style="font-size: 15px;"><strong>Email:</strong> ${email}</p>
                            <div style="background-color: #f6f7f9; padding: 15px; border-radius: 8px; font-size: 14px; margin-top: 15px;">
                                <h4 style="margin: 0 0 8px 0; color: #666666;">MESSAGE:</h4>
                                <p style="margin: 0; line-height: 1.5;">${message}</p>
                            </div>
                        </div>
                    `
                });
                console.log('Inquiry details forwarded to admin: %s', adminInfo.messageId);
            } catch (adminErr) {
                console.error('Failed to forward email to admin:', adminErr);
            }
        }
        
        let previewUrl = null;
        if (!isRealEmail) {
            // Only generate ethereal preview link for mock testing
            previewUrl = nodemailer.getTestMessageUrl(info);
            console.log('Mock Preview URL: %s', previewUrl);
        }

        res.status(200).json({ 
            success: true, 
            message: isRealEmail ? 'Real confirmation email sent successfully!' : 'Mock confirmation email sent successfully!',
            previewUrl: previewUrl // If real email, previewUrl will be null
        });

    } catch (error) {
        console.error('Error sending confirmation email:', error);
        res.status(500).json({ success: false, error: 'Failed to send confirmation email' });
    }
});

// Post endpoint for BMI Report
app.post('/send-report', async (req, res) => {
    const { email, bmi, category, weight, height, gender, tip } = req.body;

    if (!email || !bmi || !category) {
        return res.status(400).json({ success: false, error: 'Required fields are missing' });
    }

    try {
        let transporter;
        let isRealEmail = false;

        if (EMAIL_USER && EMAIL_USER !== 'your-email@gmail.com' && EMAIL_PASS) {
            isRealEmail = true;
            transporter = nodemailer.createTransport({
                service: 'gmail',
                auth: {
                    user: EMAIL_USER,
                    pass: EMAIL_PASS
                }
            });
        } else {
            let testAccount = await nodemailer.createTestAccount();
            transporter = nodemailer.createTransport({
                host: 'smtp.ethereal.email',
                port: 587,
                secure: false,
                auth: {
                    user: testAccount.user,
                    pass: testAccount.pass
                }
            });
        }

        const mailOptions = {
            from: isRealEmail ? `"Fitness Team" <${EMAIL_USER}>` : `"Fitness Team" <support@yourdomain.com>`,
            to: email,
            subject: 'Your Personal BMI Report',
            html: `
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e4e7ec; border-radius: 12px;">
                    <h2 style="color: #57E201; text-align: center; background-color: #141514; padding: 10px; border-radius: 8px;">Your BMI Report</h2>
                    <p>Hello,</p>
                    <p>Here are your BMI calculator results:</p>
                    <div style="background-color: #f6f7f9; padding: 15px; border-radius: 8px;">
                        <p><strong>BMI:</strong> ${bmi}</p>
                        <p><strong>Category:</strong> ${category}</p>
                        <p><strong>Weight:</strong> ${weight}</p>
                        <p><strong>Height:</strong> ${height}</p>
                        <p><strong>Gender:</strong> ${gender}</p>
                    </div>
                    <div style="margin-top: 15px;">
                        <p><strong>Health Tip:</strong></p>
                        <p style="font-style: italic; color: #555;">${tip}</p>
                    </div>
                </div>
            `
        };

        let info = await transporter.sendMail(mailOptions);
        
        res.status(200).json({ 
            success: true, 
            message: 'Report sent successfully'
        });

    } catch (error) {
        console.error('Error sending BMI report:', error);
        res.status(500).json({ success: false, error: 'Failed to send report email' });
    }
});

app.get('/bmi', (req, res) => {
    res.sendFile(path.join(__dirname, 'bmi-calculator-updated.html'));
});

// Chatbot endpoint
app.post('/api/chat', async (req, res) => {
    const { message, history } = req.body;

    if (!GEMINI_API_KEY || GEMINI_API_KEY === 'your-gemini-api-key-here') {
        return res.status(400).json({ success: false, error: 'Gemini API key is not configured. Please add it to .env or config.txt.' });
    }

    try {
        const genAI = new GoogleGenerativeAI(GEMINI_API_KEY);
        const model = genAI.getGenerativeModel({ 
            model: "gemini-3.5-flash",
            systemInstruction: "You are a helpful AI fitness assistant embedded in a gym website. Your goal is to answer questions about fitness, gym workouts, diet, healthy lifestyle tips, and provide encouraging support. Keep answers concise, friendly, and formatted nicely without markdown (use plain text for easy parsing). Do not provide medical diagnosis. If a user shares their BMI or fitness goals, help them understand what it means."
        });

        const chat = model.startChat({
            history: history || []
        });

        const result = await chat.sendMessage(message);
        const response = await result.response;
        const text = response.text();

        res.json({ success: true, text });
    } catch (error) {
        console.error('Error generating chat response:', error);
        res.status(500).json({ success: false, error: 'Failed to generate response.' });
    }
});

// Start Server
app.listen(PORT, () => {
    console.log(`==================================================`);
    console.log(`  Backend Email Server is running on port ${PORT}`);
    console.log(`  Access the website: http://localhost:${PORT}/contact`);
    console.log(`==================================================`);
});
