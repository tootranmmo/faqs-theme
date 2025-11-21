# FAQs Theme

A modern, high-performance WordPress theme designed specifically for FAQ websites. Built with Tailwind CSS 3.4+, featuring Dark Mode, 5-Star Rating System, Enterprise-Level SEO, and full accessibility compliance (WCAG 2.1 AA).

![WordPress](https://img.shields.io/badge/WordPress-6.0+-blue.svg)
![PHP](https://img.shields.io/badge/PHP-7.4+-purple.svg)
![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-3.4+-38bdf8.svg)
![License](https://img.shields.io/badge/License-GPL%20v2+-green.svg)

## 🌟 Features

### Modern Frontend Stack
- **Tailwind CSS 3.4+** - Utility-first CSS framework for rapid UI development
- **Vanilla JavaScript** - No jQuery dependency, pure modern JavaScript
- **WCAG 2.1 AA Accessibility** - Full keyboard navigation, ARIA labels, screen reader support
- **Mobile-first Responsive Design** - Optimized for all devices and screen sizes

### Enterprise-Level SEO
- **Schema.org Markup (7 types)**:
  - Organization
  - WebSite
  - Article
  - FAQPage
  - BreadcrumbList
  - Person
  - CollectionPage
- **Open Graph** - Facebook, LinkedIn integration
- **Twitter Cards** - Enhanced Twitter sharing
- **XML Sitemaps** - With image support
- **Canonical URLs** - Prevent duplicate content
- **Meta Robots** - Fine-grained search engine control

### Performance Optimizations
- **WebP Image Support** - 28% smaller file sizes
- **Lazy Loading** - Images load on demand
- **Critical CSS Inline** - Faster first paint
- **Resource Hints** - DNS prefetch, preconnect
- **Deferred JavaScript** - Non-blocking script loading
- **Gzip Compression** - Reduced bandwidth usage
- **Optimized Assets** - Minified CSS and JavaScript

### Modern UX Features
- 🌙 **Dark Mode** - Persistent preference with localStorage
- ⭐ **5-Star Rating System** - User engagement and feedback
- 🔍 **AJAX Live Search** - Instant search results
- 📱 **Mobile-first Design** - Touch-friendly interface
- ♿ **Full Accessibility** - Keyboard navigation, screen readers
- 🚀 **Smooth Animations** - CSS transitions and transforms
- 📊 **Statistics Dashboard** - Visual metrics on homepage

## 📋 Requirements

- WordPress 6.0 or higher
- PHP 7.4 or higher
- Node.js 14+ (for development)
- npm or yarn (for development)

## 🚀 Installation

### Method 1: Direct Upload

1. Download the theme zip file
2. Go to WordPress Admin → Appearance → Themes
3. Click "Add New" → "Upload Theme"
4. Choose the downloaded zip file
5. Click "Install Now"
6. Activate the theme

### Method 2: Manual Installation

1. Clone this repository:
   ```bash
   git clone https://github.com/tootranmmo/faqs-theme.git
   ```

2. Navigate to the theme directory:
   ```bash
   cd faqs-theme
   ```

3. Install dependencies:
   ```bash
   npm install
   ```

4. Build CSS:
   ```bash
   npm run build
   ```

5. Upload the theme folder to `wp-content/themes/`

6. Activate the theme in WordPress Admin

## 🛠️ Development

### Setup Development Environment

1. Clone the repository
2. Install dependencies:
   ```bash
   npm install
   ```

3. Start development mode (watch mode):
   ```bash
   npm run dev
   ```

4. Build for production:
   ```bash
   npm run build
   ```

### File Structure

```
faqs-theme/
├── assets/
│   ├── css/
│   │   └── style.css (compiled)
│   └── js/
│       └── main.js
├── inc/
│   ├── ajax-search.php
│   ├── dark-mode.php
│   ├── performance.php
│   ├── rating.php
│   ├── schema.php
│   ├── seo.php
│   └── sitemap.php
├── src/
│   └── style.css (source)
├── 404.php
├── footer.php
├── front-page.php
├── functions.php
├── header.php
├── index.php
├── search.php
├── searchform.php
├── single.php
├── style.css
├── package.json
├── tailwind.config.js
└── README.md
```

## 📝 Configuration

### Customizer Options

Go to **Appearance → Customize** to configure:

#### Dark Mode
- Enable/Disable dark mode toggle
- Set default mode (Auto/Light/Dark)

#### Hero Section
- Hero title
- Hero subtitle

#### Call to Action
- CTA title
- CTA subtitle
- Primary button text & URL
- Secondary button text & URL

#### Social Media
- Twitter username (for Twitter Cards)

### Menu Locations

The theme supports two menu locations:
- **Primary Menu** - Header navigation
- **Footer Menu** - Footer navigation

Configure in **Appearance → Menus**

### Widget Areas

The theme includes 4 widget areas:
- **Sidebar** - Main sidebar
- **Footer 1** - First footer column
- **Footer 2** - Second footer column
- **Footer 3** - Third footer column

Configure in **Appearance → Widgets**

## 🎨 Customization

### Colors

Edit `tailwind.config.js` to customize colors:

```javascript
theme: {
  extend: {
    colors: {
      primary: {
        // Your custom color palette
      },
    },
  },
}
```

### Typography

The theme uses the Inter font family by default. To change:

1. Edit `tailwind.config.js`:
   ```javascript
   fontFamily: {
     sans: ['Your Font', 'sans-serif'],
   }
   ```

2. Import your font in `src/style.css`

### Custom CSS

Add custom CSS in `src/style.css` and rebuild:

```bash
npm run build
```

## ⭐ Rating System

The theme includes a built-in 5-star rating system:

- Users can rate each FAQ post
- Ratings are saved with cookies to prevent duplicate votes
- Average rating and vote count are displayed
- Ratings are included in Schema.org markup for SEO

### Usage in Templates

```php
// Display rating
$rating = get_post_meta(get_the_ID(), '_faqs_rating', true);
echo faqs_theme_display_rating($rating);

// Display rating input form
echo faqs_theme_rating_input();
```

## 🔍 Search Functionality

### Live AJAX Search

The theme includes real-time search with autocomplete:

- Searches posts as you type
- Displays instant results
- Shows post title, excerpt, category, and rating
- Accessible with keyboard navigation

### Search Modal

Press the search icon or use the keyboard shortcut to open the search modal with instant results.

## ♿ Accessibility Features

The theme is fully accessible and compliant with WCAG 2.1 AA:

- Semantic HTML5 markup
- ARIA labels and roles
- Keyboard navigation support
- Focus indicators
- Screen reader optimized
- Skip to content link
- Alt text for images
- Proper heading hierarchy
- Form labels and descriptions

### Keyboard Shortcuts

- `Tab` - Navigate through interactive elements
- `Enter` - Activate buttons and links
- `Esc` - Close modals
- `Space` - Toggle checkboxes and buttons

## 🌐 Multilingual Support

The theme is translation-ready:

1. Use a plugin like WPML, Polylang, or Loco Translate
2. Text domain: `faqs-theme`
3. All strings are wrapped with translation functions

## 📊 Performance Tips

1. **Use WebP images** - Convert images to WebP format for better compression
2. **Enable caching** - Use a caching plugin like WP Super Cache
3. **Use a CDN** - Distribute static assets globally
4. **Optimize database** - Clean up post revisions and spam
5. **Lazy load images** - Already built-in, no plugin needed
6. **Minify assets** - Run `npm run build` for production

## 🔧 Troubleshooting

### CSS not loading

1. Make sure you ran `npm install` and `npm run build`
2. Check file permissions on `assets/css/style.css`
3. Clear WordPress cache and browser cache

### Dark mode not working

1. Check browser console for JavaScript errors
2. Ensure `assets/js/main.js` is loaded
3. Clear localStorage: `localStorage.removeItem('darkMode')`

### Search not working

1. Verify AJAX URL is correct in browser console
2. Check WordPress permalinks settings
3. Ensure nonce verification is passing

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a new branch: `git checkout -b feature/your-feature`
3. Make your changes
4. Test thoroughly
5. Commit: `git commit -m 'Add your feature'`
6. Push: `git push origin feature/your-feature`
7. Open a Pull Request

## 📄 License

This theme is licensed under the GNU General Public License v2 or later.

See [LICENSE](https://www.gnu.org/licenses/gpl-2.0.html) for more information.

## 👏 Credits

- **Tailwind CSS** - [https://tailwindcss.com](https://tailwindcss.com)
- **WordPress** - [https://wordpress.org](https://wordpress.org)
- **Heroicons** - [https://heroicons.com](https://heroicons.com)

## 📧 Support

For support, please:
- Open an issue on GitHub
- Check the documentation
- Visit WordPress support forums

## 🗺️ Roadmap

- [ ] Gutenberg block patterns
- [ ] WooCommerce compatibility
- [ ] Additional Schema types
- [ ] Advanced search filters
- [ ] FAQ voting system
- [ ] Related FAQs widget
- [ ] Table of contents for long posts
- [ ] Print-friendly styles

## 📸 Screenshots

### Homepage
![Homepage](screenshot.png)

### Dark Mode
Auto-switching dark mode with localStorage persistence

### Search
Live AJAX search with instant results

### Single Post
FAQ post with rating, schema markup, and sharing

## 🎯 Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## 📚 Documentation

For detailed documentation, visit [GitHub Wiki](https://github.com/tootranmmo/faqs-theme/wiki)

---

**Made with ❤️ by Claude AI**

**Version:** 1.0.0
