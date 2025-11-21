# FAQs Theme Tools

Comprehensive toolkit for managing your FAQs website with 30+ tools.

## 🚀 Getting Started

### Accessing Tools

1. Login to WordPress admin
2. Go to **FAQs Tools** in the left sidebar
3. Browse tools by category
4. Click any tool card to open it

### System Requirements

- WordPress 5.0+
- PHP 7.4+
- MySQL 5.6+
- 128MB+ PHP memory limit (recommended)

## 📦 Available Tools

### ✅ Fully Functional Tools (4)

#### 1. Bulk Post Editor 🔧
**Location**: FAQs Tools → Bulk Editor

**Features**:
- Edit multiple posts at once
- Change status (publish, draft, pending)
- Add/remove categories in bulk
- Add tags in bulk
- Delete posts with confirmation
- Advanced filtering
- AJAX-powered updates

**Usage**:
1. Apply filters to find posts
2. Select posts using checkboxes
3. Choose bulk action
4. Apply changes

#### 2. Database Optimizer ⚡
**Location**: FAQs Tools → DB Optimizer

**Features**:
- Clean post revisions
- Remove auto-drafts
- Empty trash
- Delete spam comments
- Clear expired transients
- Optimize database tables
- Real-time statistics

**Usage**:
1. Review database statistics
2. Select optimization options
3. Click "Run Optimization"
4. Wait for completion

⚠️ **Important**: Always backup your database before optimization!

#### 3. SEO Checker 🔍
**Location**: FAQs Tools → SEO Checker

**Features**:
- 100-point SEO scoring
- Title length check (30-60 chars)
- Meta description validation (120-160 chars)
- Heading structure analysis
- Image and link counting
- Category and tag validation
- Featured image check
- Content length analysis
- Detailed recommendations

**Usage**:
- **Bulk Analysis**: View all posts with scores
- **Single Analysis**: Click "Analyze" on any post
- **In Editor**: Check SEO score in sidebar meta box

**Scoring**:
- 80-100: 🟢 Excellent
- 60-79: 🟡 Good
- 40-59: 🟠 Needs Work
- 0-39: 🔴 Poor

#### 4. Newsletter Manager 💌
**Location**: FAQs Tools → Newsletter

**Features**:
- Email subscription management
- Subscriber statistics (today/week/month)
- Export to CSV
- Copy all emails
- Add/delete subscribers manually
- Frontend subscription form
- AJAX form submission
- Duplicate prevention

**Usage**:
1. **Add Subscriber**: Use the form in admin
2. **View Subscribers**: See list with dates
3. **Export**: Click "Export to CSV"
4. **Frontend Form**: Use shortcode `[faqs_newsletter]`

**Shortcode Example**:
```php
[faqs_newsletter title="Subscribe Now" description="Get updates"]
```

### 🚧 Coming Soon Tools (26+)

The following tools are planned and will show a "Coming Soon" screen:

- Quick Duplicate
- Post Templates
- Broken Link Checker
- User Contributions
- Social Share Counter
- Advanced Search
- Trending Topics
- Content Calendar
- Editorial Workflow
- Version History
- AI Suggestions
- Auto-Tagging
- FAQ Generator
- Content Gap Analysis
- Image Compressor
- Cache Manager
- Comment Moderation
- Spam Filter
- Content Quality Checker
- Translation Manager
- Language Switcher
- Advanced Analytics
- A/B Testing
- Export Reports
- Ads Manager
- Affiliate Link Manager
- Premium Content
- Donation System
- REST API Extensions
- Webhook Manager
- Import/Export

## 🔧 Troubleshooting

### Tools Menu Not Showing

**Check**:
1. User has `manage_options` capability (Admin role)
2. Theme is properly activated
3. No PHP errors in debug.log

**Fix**:
```bash
# Enable WP_DEBUG in wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);

# Check wp-content/debug.log for errors
```

### Newsletter Form Not Working

**Check**:
1. Database table exists
2. jQuery is loaded
3. AJAX URL is correct

**Fix - Create Table Manually**:
```sql
CREATE TABLE IF NOT EXISTS wp_faqs_newsletter (
    id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    email varchar(100) NOT NULL,
    name varchar(100) DEFAULT '',
    status varchar(20) DEFAULT 'active',
    subscribed_date datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY email (email)
);
```

### Database Optimizer Shows Zero Stats

**Reason**: Database is already optimized or empty

**Solution**: This is normal if:
- No post revisions exist
- Trash is empty
- No spam comments

### SEO Checker Shows Low Scores

**Common Issues**:
- Title too short/long
- Missing meta description (excerpt)
- No headings (H2)
- No internal links
- Missing featured image
- Few/no tags

**Fix**: Follow the recommendations provided

## 🎨 Customization

### Adding New Tools

1. Create tool file in `/inc/tools/your-tool.php`
2. Add file to `load_tool_files()` in `tools-manager.php`
3. Register in `register_tools()` array
4. Add submenu in tool class

**Template**:
```php
<?php
class FAQs_Your_Tool {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_menu'));
    }

    public function add_menu() {
        add_submenu_page(
            'faqs-tools',
            __('Your Tool', 'faqs-theme'),
            __('Your Tool', 'faqs-theme'),
            'manage_options',
            'faqs-tool-your-tool',
            array($this, 'render_page')
        );
    }

    public function render_page() {
        // Tool UI here
    }
}

new FAQs_Your_Tool();
```

### Customizing UI

Edit `/assets/css/tools-admin.css` for styling.

### Customizing Behavior

Edit individual tool files in `/inc/tools/`.

## 📊 Database Tables

### Newsletter Subscribers
**Table**: `wp_faqs_newsletter`

**Columns**:
- `id`: Subscriber ID
- `email`: Email address (unique)
- `name`: Subscriber name
- `status`: active/inactive
- `subscribed_date`: Subscription timestamp

## 🔐 Security

All tools include:
- ✅ Nonce verification
- ✅ Capability checks
- ✅ SQL injection prevention
- ✅ XSS protection
- ✅ CSRF protection

## 📝 Changelog

### Version 1.0.0
- Initial release
- 4 fully functional tools
- 26+ placeholder tools
- Admin UI dashboard
- Documentation

## 🆘 Support

**Issues**: Report at theme's GitHub repository
**Documentation**: See theme documentation
**Community**: WordPress.org forums

## 📄 License

GPL v2 or later

## 🙏 Credits

Built with ❤️ for the FAQs Theme by Claude AI
