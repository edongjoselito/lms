<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your LMS School Admin Account</title>
</head>
<body style="margin:0;padding:0;font-family:'Segoe UI',Arial,sans-serif;background-color:#f4f4f4;">
    <div style="max-width:600px;margin:40px auto;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 6px rgba(0,0,0,0.1);">
        <div style="background:linear-gradient(135deg,#6366f1,#8b5cf6);padding:30px;text-align:center;">
            <h1 style="color:#ffffff;margin:0;font-size:24px;font-weight:700;">LMS Portal</h1>
            <p style="color:#e0e7ff;margin:10px 0 0 0;font-size:16px;">Learning Management System</p>
        </div>
        <div style="padding:40px 30px;">
            <h2 style="color:#1e293b;margin:0 0 20px 0;font-size:22px;font-weight:600;">Your School Admin Account</h2>
            <p style="color:#475569;margin:0 0 20px 0;line-height:1.6;">
                A school admin account has been created for <strong><?= htmlspecialchars($school_name) ?></strong> in the LMS Portal.
            </p>
            <div style="background:#f8fafc;border-left:4px solid #6366f1;padding:20px;margin:30px 0;border-radius:0 8px 8px 0;">
                <p style="color:#334155;margin:0 0 10px 0;font-weight:600;font-size:14px;">Login Details:</p>
                <p style="color:#334155;margin:5px 0;font-size:14px;">
                    <strong>Email:</strong> <?= htmlspecialchars($email) ?>
                </p>
                <p style="color:#334155;margin:5px 0;font-size:14px;">
                    <strong>Password:</strong> <span style="background:#e0e7ff;padding:4px 8px;border-radius:4px;font-family:monospace;font-weight:600;"><?= htmlspecialchars($password) ?></span>
                </p>
            </div>
            <p style="color:#dc2626;margin:0 0 20px 0;line-height:1.6;font-size:14px;">
                <strong>Important:</strong> Please change your password after your first login for security purposes.
            </p>
            <div style="text-align:center;margin:30px 0;">
                <a href="<?= base_url() ?>" style="display:inline-block;background:#6366f1;color:#ffffff;padding:14px 32px;text-decoration:none;border-radius:8px;font-weight:600;font-size:16px;">Login to LMS Portal</a>
            </div>
            <p style="color:#94a3b8;margin:30px 0 0 0;font-size:13px;line-height:1.6;">
                If you did not request this account creation, please ignore this email or contact your system administrator.
            </p>
        </div>
        <div style="background:#f8fafc;padding:20px;text-align:center;border-top:1px solid #e2e8f0;">
            <p style="color:#64748b;margin:0;font-size:13px;">&copy; <?= date('Y') ?> LMS Portal. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
