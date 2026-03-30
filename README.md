# KeyForge - Keyboard Switch Store (Scaffold)

A PHP starter scaffold for a keyboard switch e-commerce website.

## Project Structure

```
public/            # Web root - point your server here
  index.php        # Front controller (single entry point)
  css/main.css     # Base stylesheet
  js/main.js       # Base JavaScript
  assets/          # Images and other static assets
  .htaccess        # Apache rewrite rules (do not edit)
config/
  database.php     # PDO connection placeholder
database/
  schema.sql       # Table definitions (stubs)
  seed.sql         # Sample data (stubs)
src/
  controllers/     # HTTP request handlers (stubs)
  models/          # Database model classes (stubs)
  helpers/         # Utility functions (stubs)
views/
  layout/          # Shared header / footer partials
  pages/           # Individual page templates
router.php         # PHP built-in server router
.env.example       # Environment variable template
```

## Getting Started

1. Copy the environment file and fill in your database credentials:
   ```bash
   cp .env.example .env
   ```

2. Start the development server:
   ```bash
   php -S localhost:8000 -t public router.php
   ```

3. Open http://localhost:8000 in your browser.

> The index page works without a database configured.
> All other pages return 404 until you implement the routes.

## Features to Implement

- [ ] Product catalogue (listing, filtering, search)
- [ ] Product detail page
- [ ] User registration and login
- [ ] Shopping cart
- [ ] Checkout and orders
- [ ] Admin product management
