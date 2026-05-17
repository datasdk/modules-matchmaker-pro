<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jobdetaljer')</title>
    <style>
        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: #2c3e50;
            line-height: 1.6;
            padding: 20px;
        }

        .pdf-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* Header Section */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
        }

        .main-title {
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 10px;
            position: relative;
            letter-spacing: -0.5px;
        }

        .subtitle {
            font-size: 18px;
            opacity: 0.9;
            font-weight: 400;
        }

        /* Content Sections */
        .content {
            padding: 40px;
        }

        .section {
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            padding-bottom: 12px;
            margin-bottom: 25px;
            border-bottom: 3px solid #667eea;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 60px;
            height: 3px;
            background: #764ba2;
        }

        /* Grid Layout */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        /* Info Cards */
        .info-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            border-left: 4px solid #667eea;
        }

        .info-card h3 {
            font-size: 16px;
            color: #667eea;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .info-card p {
            font-size: 18px;
            color: #2c3e50;
            font-weight: 500;
        }

        /* Price Highlight */
        .price-highlight {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 22px;
            font-weight: 700;
            margin: 10px 0;
        }

        /* Tags Styling */
        .tags-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .tag {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            color: #1976d2;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .tag::before {
            content: '✓';
            font-weight: bold;
        }

        /* Contact Info */
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 25px;
            border-radius: 12px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .contact-icon {
            width: 45px;
            height: 45px;
            background: #667eea;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .stat-number {
            font-size: 36px;
            font-weight: 800;
            color: #667eea;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Description Box */
        .description-box {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 30px;
            border-radius: 12px;
            margin: 25px 0;
            border-left: 5px solid #667eea;
        }

        .description-box p {
            font-size: 16px;
            line-height: 1.8;
            color: #495057;
        }

        /* Timeline for Period */
        .timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .timeline-icon {
            width: 40px;
            height: 40px;
            background: #667eea;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        /* Address Card */
        .address-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 25px;
            border-radius: 12px;
            border-left: 4px solid #4CAF50;
        }

        /* Footer */
        .footer {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 2px solid #e9ecef;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }

        .footer-logo {
            font-size: 20px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 10px;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .header {
                padding: 30px 20px;
            }
            
            .main-title {
                font-size: 32px;
            }
            
            .content {
                padding: 20px;
            }
            
            .grid {
                grid-template-columns: 1fr;
            }
        }

        /* Print Optimizations */
        @media print {
            body {
                background: white !important;
                padding: 0;
            }
            
            .pdf-container {
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <div class="pdf-container">
        @yield('header')
        
        <div class="content">
            @yield('content')
        </div>

        @hasSection('footer')
        <div class="footer">
            @yield('footer')
        </div>
        @endif
    </div>
</body>
</html>