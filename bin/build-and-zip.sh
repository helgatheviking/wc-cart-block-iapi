#!/bin/bash
set -e

# Absolute path to this script
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Absolute path to plugin root
PLUGIN_DIR="$(dirname "$SCRIPT_DIR")"

# Plugin slug (directory name)
PLUGIN_SLUG="$(basename "$PLUGIN_DIR")"

# Distribution directory
DIST_DIR="$PLUGIN_DIR/dist"

echo "🧹 Cleaning up old dist..."
rm -rf "$DIST_DIR"
mkdir "$DIST_DIR"

cd "$PLUGIN_DIR"

echo "📦 Installing Composer dependencies (with dev)..."
composer install --no-interaction

echo "🛠 Running build scripts..."
npm install
npm run build
composer run makepot

echo "🧬 Copying plugin files to dist directory..."
rsync -a --delete \
  --include='build/***' \
  --include='includes/***' \
  --include='languages/***' \
  --include='vendor/***' \
  --include='readme.txt' \
  --include='composer.json' \
  --include="${PLUGIN_SLUG}.php" \
  --exclude='*' \
  ./ "$DIST_DIR"

echo "✂️ Installing production Composer dependencies only..."
(
	cd "$DIST_DIR"
	composer install --no-dev --optimize-autoloader --no-interaction
    # Remove composer.json when finished.
    rm -f composer.json composer.lock
)

echo "🗜 Zipping plugin from dist/ with top-level folder named $PLUGIN_SLUG..."

cd "$PLUGIN_DIR"

# Remove existing zip if present
rm -f "${PLUGIN_SLUG}.zip"

# Rename dist to plugin slug
mv dist "$PLUGIN_SLUG"

# Zip the renamed folder
zip -r "${PLUGIN_SLUG}.zip" "$PLUGIN_SLUG"

# Rename it back to dist
mv "$PLUGIN_SLUG" dist

echo "✅ Build complete. ZIP created for $PLUGIN_SLUG."
