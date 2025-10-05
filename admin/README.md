# Jahongir Travel - Admin Panel

A lightweight, custom-built admin panel for managing Jahongir Travel website content.

## 🚀 Features

- **Tour Management**: Create, edit, and manage all tour pages
- **Blog Management**: Manage blog posts and travel guides
- **Media Library**: Upload and organize images with auto-WebP conversion
- **SEO Tools**: Edit meta tags, descriptions, and generate sitemaps
- **Security**: Bcrypt passwords, CSRF protection, session management
- **File-Based**: No database required - edits PHP files directly
- **Responsive**: Works on desktop, tablet, and mobile

## 📁 Directory Structure

```
admin/
├── config.php              # Configuration settings
├── index.php               # Login page
├── dashboard.php           # Main dashboard
├── logout.php              # Logout handler
├── includes/
│   ├── auth.php           # Authentication functions
│   ├── functions.php      # Helper functions
│   ├── file-parser.php    # PHP file parser/writer
│   └── layout.php         # Admin layout template
├── tours/
│   ├── list.php          # Tour list
│   ├── edit.php          # Edit tour
│   └── create.php        # Create new tour (TODO)
├── blog/
│   ├── list.php          # Blog posts list
│   ├── edit.php          # Edit blog post
│   └── create.php        # Create new post (TODO)
├── media/
│   └── library.php       # Media library & upload
├── settings/
│   ├── sitemap.php       # Sitemap generator
│   └── profile.php       # Change password
└── assets/
    ├── css/              # Custom styles
    └── js/               # Custom scripts
```

## 🔐 Default Credentials

**⚠️ CHANGE THESE IMMEDIATELY!**

- **Username**: `admin`
- **Password**: `jahongir2025`

## 🛠️ Installation

1. **Access the Admin Panel**
   ```
   https://your-domain.com/admin/
   ```

2. **Login**
   - Use default credentials above
   - You'll be redirected to the dashboard

3. **Change Password**
   - Go to Settings → Profile
   - Change the default password immediately

4. **Configure Settings**
   - Check `admin/config.php` and update:
     - `SITE_URL`
     - `SITE_NAME`
     - Other constants as needed

## 📖 Usage

### Managing Tours

1. **View All Tours**
   - Navigate to Tours → All Tours
   - Search and filter tours by category

2. **Edit a Tour**
   - Click "Edit" on any tour
   - Update SEO fields (title, meta description, keywords)
   - Update tour details (price, duration, tour code)
   - Changes are saved directly to the PHP file
   - Auto-backup is created before editing

3. **What Can Be Edited**
   - Page Title (SEO)
   - Meta Description
   - Meta Keywords
   - Canonical URL
   - Main Heading (H1)
   - Price
   - Duration
   - Tour Code
   - Open Graph settings

### Managing Blog Posts

1. **View All Posts**
   - Navigate to Blog → All Posts
   - Search posts by title or description

2. **Edit a Post**
   - Click "Edit" on any post
   - Update SEO fields and content metadata
   - Same editing capabilities as tours

### Media Library

1. **Upload Images**
   - Navigate to Media → Library
   - Click or drag-and-drop images
   - Supported formats: JPG, PNG, GIF, WebP
   - Max size: 5MB per file

2. **Auto-WebP Conversion**
   - Uploaded images are automatically converted to WebP
   - Original format is kept
   - Both versions available for use

3. **Using Images**
   - Click on any image to view details
   - Copy image URL to clipboard
   - Use in tour/blog content

### Sitemap Management

1. **Regenerate Sitemap**
   - Navigate to Settings → Sitemap
   - Click "Regenerate Now"
   - Automatically includes all tours and blog posts

2. **Submit to Search Engines**
   - After regeneration, submit to Google Search Console
   - Link provided in the sitemap page

## 🔒 Security Features

- **Password Hashing**: Bcrypt with automatic salt
- **Session Management**: Secure sessions with timeout
- **CSRF Protection**: Tokens on all forms
- **File Backup**: Auto-backup before editing
- **Syntax Validation**: PHP syntax check before saving
- **Input Sanitization**: All inputs sanitized
- **XSS Prevention**: htmlspecialchars on all output

## ⚙️ Configuration

### Changing Admin Password

**Method 1: Via Admin Panel**
1. Login to admin panel
2. Go to Settings → Profile
3. Enter current password and new password
4. Submit - you'll be logged out

**Method 2: Via Code**
1. Open `admin/config.php`
2. Find line: `define('ADMIN_PASSWORD_HASH', ...)`
3. Replace with:
   ```php
   define('ADMIN_PASSWORD_HASH', password_hash('YOUR_NEW_PASSWORD', PASSWORD_BCRYPT));
   ```

### Session Timeout

Edit in `admin/config.php`:
```php
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds
```

### Upload Limits

Edit in `admin/config.php`:
```php
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
```

## 🐛 Troubleshooting

### Can't Login

1. Check username/password
2. Clear browser cookies
3. Check PHP session is enabled
4. Verify `session.save_path` is writable

### Can't Save Changes

1. Check file permissions (files must be writable)
2. Check PHP `open_basedir` restrictions
3. Look for syntax errors in PHP logs
4. Verify backup directory exists and is writable

### Images Won't Upload

1. Check `images/uploads/` directory exists
2. Verify directory is writable (chmod 755 or 775)
3. Check PHP `upload_max_filesize` setting
4. Verify GD library is installed for WebP

### Sitemap Won't Generate

1. Check `sitemap.xml` is writable
2. Verify root directory permissions
3. Check PHP has write access to root

## 📊 File Permissions

Recommended permissions:
```
admin/                    755
admin/config.php          644
admin/backups/            755 (writable)
images/uploads/           755 (writable)
sitemap.xml              644 (writable)
tour files (*.php)        644 (writable)
blog files (*.php)        644 (writable)
```

## 🔄 Backup & Restore

### Auto-Backups

- Created automatically before each edit
- Stored in `admin/backups/`
- Last 10 backups kept per file
- Filename format: `original-name.php.timestamp.backup`

### Manual Backup

Backup these directories regularly:
```
/uzbekistan-tours/
/tours-from-samarkand/
/tours-from-bukhara/
/tours-from-khiva/
/blog/
/images/
/admin/config.php
```

## 🚧 Future Enhancements

- [ ] Create new tour from template
- [ ] Create new blog post from template
- [ ] Rich text editor for content blocks
- [ ] Bulk image upload
- [ ] Image alt text editor
- [ ] Tour/blog deletion with confirmation
- [ ] Export/import functionality
- [ ] Activity log
- [ ] Multiple admin users
- [ ] Two-factor authentication

## 💡 Tips

1. **Before Editing**: Always preview the page first
2. **After Editing**: Clear browser cache to see changes
3. **SEO**: Keep meta descriptions under 160 characters
4. **Backups**: Backups are automatic, but manual backups recommended
5. **Images**: Use descriptive filenames for better SEO
6. **Sitemap**: Regenerate after adding new tours/posts

## 📞 Support

If you encounter issues:

1. Check PHP error logs
2. Verify file permissions
3. Clear browser cache
4. Review `admin/config.php` settings
5. Check `admin/backups/` for previous versions

## 🔗 Quick Links

- **Login**: `/admin/`
- **Dashboard**: `/admin/dashboard.php`
- **Tours**: `/admin/tours/list.php`
- **Blog**: `/admin/blog/list.php`
- **Media**: `/admin/media/library.php`
- **Sitemap**: `/admin/settings/sitemap.php`
- **Settings**: `/admin/settings/profile.php`

---

**Built with**: Plain PHP 8.2, Tailwind CSS 3.4, Alpine.js 3.x

**Version**: 1.0
**Last Updated**: January 2025
**License**: Proprietary - Jahongir Travel
