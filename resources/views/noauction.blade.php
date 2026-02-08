<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>No Auction Found</title>
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .no-auction-box {
            background: #ffffff;
            padding: 40px 50px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            max-width: 450px;
        }

        .icon {
            font-size: 70px;
            color: #ef4444;
            margin-bottom: 20px;
        }

        h2 {
            margin: 0;
            color: #1f2937;
            font-size: 26px;
        }

        p {
            margin-top: 10px;
            color: #6b7280;
            font-size: 15px;
        }

        .retry-btn {
            margin-top: 25px;
            padding: 10px 22px;
            font-size: 14px;
            border: none;
            background: #2563eb;
            color: #fff;
            border-radius: 6px;
            cursor: pointer;
        }

        .retry-btn:hover {
            background: #1e40af;
        }
    </style>
</head>
<body>

<div class="no-auction-box">
    <div class="icon">🚫</div>
    <h2>No Auction Found</h2>
    <p>
        There is currently no active auction available.<br>
        Please check back later or contact the administrator.
    </p>

    <button class="retry-btn" onclick="location.reload()">
        Refresh Page
    </button>
</div>

</body>
</html>
