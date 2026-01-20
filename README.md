# Knowledge Commons Theme 2026

A WordPress block theme for [Knowledge Commons](https://hcommons.org), the open-access scholarly network. Built on top of the Full Site Editing (FSE) architecture introduced in WordPress 5.9+.

## Requirements

- WordPress 6.4 or higher
- PHP 8.0 or higher
- BuddyPress (optional, but the theme includes extensive template overrides for it)

## Structure

```
hcommons-mpe-theme/
├── assets/
│   ├── css/
│   │   ├── theme.css          # Main stylesheet
│   │   └── buddypress.css     # BuddyPress-specific styles
│   ├── images/
│   └── js/
│       └── theme.js
├── buddypress/                 # BuddyPress template overrides
│   ├── activity/
│   ├── blogs/
│   ├── forums/
│   ├── groups/
│   └── members/
├── parts/                      # Block template parts
│   ├── header.html
│   └── footer.html
├── patterns/                   # Block patterns
│   ├── hero.php
│   ├── features.php
│   ├── partners.php
│   ├── community.php
│   ├── blog-section.php
│   └── cta.php
├── templates/                  # Block templates
│   ├── front-page.html
│   ├── index.html
│   ├── single.html
│   ├── page.html
│   ├── archive.html
│   └── 404.html
├── functions.php
├── style.css
└── theme.json
```

## Design system

The theme uses a constrained colour palette and typography system defined in `theme.json`. This keeps the block editor options focused and consistent with the Knowledge Commons brand and the WCAG color contrast guidelines.

### Colours

- **Teal** (`#1a5f4e`) - Primary brand colour, used for headings and body text
- **Green** (`#2e7d32`) - Accent colour for links and buttons
- **Gold** (`#B34233`) - Secondary accent
- **Cream** (`#faf8f5`) - Background colour

### Typography

The theme uses [Atkinson Hyperlegible](https://brailleinstitute.org/freefont), a typeface designed for readability and accessibility. It's loaded via Google Fonts.

## Block patterns

The theme ships with several patterns for the front page:

- **Hero** - Main landing section with headline and CTAs
- **Features** - Grid of feature cards
- **Partners** - Logo grid for partner institutions
- **Community** - Statistics and community highlights
- **Blog section** - Latest posts (pulls from the "about" site in multisite)
- **CTA** - Call to action banner

These are registered under the "Knowledge Commons" pattern category in the block editor.

## BuddyPress integration

The theme includes a full set of BuddyPress template overrides in the `buddypress/` directory. These cover:

- Activity streams
- Member profiles and directories
- Group pages (headers, members, invitations, admin)
- Messages and notifications
- Forums and blogs directories

The BuddyPress styles are loaded conditionally only when BuddyPress is active.

## Multisite considerations

The theme is designed for the Knowledge Commons multisite network. A few things to note:

- The blog section pattern fetches posts from a separate "about" site, with environment detection for local/staging/production URLs
- The `hcommons_get_about_blog_id()` function handles the lookup
- Uses `switch_to_blog()` safely at render time rather than during theme setup

## Development

The CSS is plain CSS - no preprocessor. The theme avoids build tooling to keep things simple. Just edit the files directly.

For local development, the theme expects a Lando-based environment at `*.lndo.site` domains. The environment detection in `functions.php` will adjust URLs accordingly.

## Shortcodes

- `[hcommons_header_account]` - Renders the logged-in user's avatar, notifications bell, and account dropdown (or login/register links when logged out)
- `[hcommons_about_blog_posts count="3"]` - Renders recent posts from the about site

## Licence

MIT License.
