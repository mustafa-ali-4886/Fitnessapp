const express = require('express');
const nodemailer = require('nodemailer');
const PDFDocument = require('pdfkit');
const cors = require('cors');
const { Readable } = require('stream');

const app = express();
const PORT = 3000;

// Middleware
app.use(cors());
app.use(express.json());

// Configure your email service
// Using Gmail - you'll need to create an App Password
const transporter = nodemailer.createTransport({
  service: 'gmail',
  auth: {
    user: process.env.EMAIL_USER || 'your-email@gmail.com',
    pass: process.env.EMAIL_PASSWORD || 'your-app-password'
  }
});

// Function to generate PDF
function generateBMIPDF(data) {
  return new Promise((resolve, reject) => {
    try {
      const doc = new PDFDocument({ bufferPages: true });
      const buffers = [];

      doc.on('data', (chunk) => buffers.push(chunk));
      doc.on('end', () => resolve(Buffer.concat(buffers)));
      doc.on('error', reject);

      // Title
      doc.fontSize(28).font('Helvetica-Bold').text('BMI REPORT', { align: 'center' });
      doc.moveDown(0.5);

      // Date
      doc.fontSize(10).font('Helvetica').fillColor('#888888').text(`Generated on: ${new Date().toLocaleDateString()}`, { align: 'center' });
      doc.moveDown(1);

      // BMI Result - Highlighted
      doc.fontSize(16).font('Helvetica-Bold').fillColor('#000000').text('Your Body Mass Index', { align: 'left' });
      doc.moveDown(0.3);

      const bmiBoxY = doc.y;
      doc.rect(50, bmiBoxY, 300, 100).fillAndStroke('#f0f0f0', '#cccccc');
      
      doc.fontSize(48).font('Helvetica-Bold').fillColor('#57E201').text(data.bmi.toString(), {
        x: 50,
        y: bmiBoxY + 10,
        width: 300,
        align: 'center'
      });

      doc.fontSize(14).font('Helvetica').fillColor('#000000').text(data.category, {
        x: 50,
        y: bmiBoxY + 65,
        width: 300,
        align: 'center'
      });

      doc.moveDown(6);

      // Personal Information
      doc.fontSize(12).font('Helvetica-Bold').fillColor('#000000').text('Your Information');
      doc.moveDown(0.3);
      doc.fontSize(11).font('Helvetica').fillColor('#333333');
      
      const infoX = 50;
      let currentY = doc.y;
      const lineHeight = 22;

      doc.text(`Gender: ${data.gender}`, infoX, currentY);
      currentY += lineHeight;
      
      doc.text(`Weight: ${data.weight} kg`, infoX, currentY);
      currentY += lineHeight;
      
      doc.text(`Height: ${data.height} cm`, infoX, currentY);
      currentY += lineHeight;

      doc.moveDown(2);

      // Health Insight
      doc.fontSize(12).font('Helvetica-Bold').fillColor('#000000').text('Health Insight');
      doc.moveDown(0.3);
      doc.fontSize(11).font('Helvetica').fillColor('#555555');
      
      // Determine color for the insight
      let insightColor = '#57E201'; // neon green for normal
      if (data.category === 'Underweight') {
        insightColor = '#4a90d9'; // blue
      } else if (data.category === 'Overweight') {
        insightColor = '#f0c040'; // yellow
      } else if (data.category === 'Obese') {
        insightColor = '#e84040'; // red
      }

      doc.fillColor(insightColor);
      doc.text(data.tip);

      doc.moveDown(2);

      // BMI Categories Reference
      doc.fontSize(11).font('Helvetica-Bold').fillColor('#000000').text('BMI Categories Reference');
      doc.moveDown(0.3);

      const categories = [
        { range: 'Below 18.5', label: 'Underweight', color: '#4a90d9' },
        { range: '18.5 - 24.9', label: 'Normal Weight', color: '#57E201' },
        { range: '25.0 - 29.9', label: 'Overweight', color: '#f0c040' },
        { range: '30.0+', label: 'Obese', color: '#e84040' }
      ];

      doc.fontSize(10).font('Helvetica');
      
      categories.forEach((cat, idx) => {
        const catY = doc.y;
        // Color indicator
        doc.rect(50, catY, 12, 12).fill(cat.color);
        
        doc.fillColor('#000000');
        doc.text(`${cat.label}: ${cat.range}`, 75, catY + 2);
        doc.moveDown(1);
      });

      doc.moveDown(1);

      // Disclaimer
      doc.fontSize(9).fillColor('#999999').text(
        'Disclaimer: This BMI report is for informational purposes only. Please consult a healthcare professional for personalized medical advice.',
        { align: 'center' }
      );

      doc.end();
    } catch (error) {
      reject(error);
    }
  });
}

// API Endpoint
app.post('/send-report', async (req, res) => {
  try {
    const { email, bmi, category, weight, height, gender, tip } = req.body;

    // Validation
    if (!email || !bmi || !category) {
      return res.status(400).json({ message: 'Missing required fields' });
    }

    // Generate PDF
    const pdfBuffer = await generateBMIPDF({
      bmi, category, weight, height, gender, tip
    });

    // Send Email
    const mailOptions = {
      from: process.env.EMAIL_USER || 'your-email@gmail.com',
      to: email,
      subject: '📊 Your BMI Report',
      html: `
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
          <h2 style="color: #57E201;">Your BMI Report</h2>
          <p>Hello,</p>
          <p>Thank you for using our BMI Calculator! Your personalized BMI report is attached to this email.</p>
          
          <div style="background-color: #f5f5f5; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3 style="margin: 0; color: #333;">Your BMI: <span style="color: #57E201; font-size: 28px;">${bmi}</span></h3>
            <p style="margin: 10px 0; color: #666;"><strong>Category:</strong> ${category}</p>
            <p style="margin: 10px 0; color: #666;"><strong>Weight:</strong> ${weight} kg</p>
            <p style="margin: 10px 0; color: #666;"><strong>Height:</strong> ${height} cm</p>
          </div>

          <p style="color: #555; line-height: 1.6;">${tip}</p>

          <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">

          <p style="font-size: 12px; color: #999;">
            This email contains your BMI report. Please consult a healthcare professional for personalized medical advice.
          </p>

          <p style="font-size: 12px; color: #999; margin-top: 20px;">
            Best regards,<br>
            <strong>BMI Calculator Team</strong>
          </p>
        </div>
      `,
      attachments: [
        {
          filename: `BMI_Report_${new Date().toISOString().split('T')[0]}.pdf`,
          content: pdfBuffer,
          contentType: 'application/pdf'
        }
      ]
    };

    await transporter.sendMail(mailOptions);

    res.status(200).json({ 
      success: true,
      message: 'Report sent successfully!' 
    });

  } catch (error) {
    console.error('Error:', error);
    res.status(500).json({ 
      success: false,
      message: 'Failed to send report. Please check server logs.' 
    });
  }
});

// Health check endpoint
app.get('/health', (req, res) => {
  res.json({ status: 'Server is running' });
});

// Start server
app.listen(PORT, () => {
  console.log(`✅ BMI Report Server running on http://localhost:${PORT}`);
  console.log('📧 Email configured with:', process.env.EMAIL_USER || 'your-email@gmail.com');
  console.log('\n⚠️  IMPORTANT: Set environment variables:');
  console.log('   EMAIL_USER=your-email@gmail.com');
  console.log('   EMAIL_PASSWORD=your-app-password');
});

// Error handling
process.on('uncaughtException', (error) => {
  console.error('Uncaught Exception:', error);
});
