#!/usr/bin/env bash
set -Eeuo pipefail

# Monte Pterodactyl Theme installer
# Requires a Pterodactyl panel with Blueprint installed.

PANEL_DIR="${PANEL_DIR:-/var/www/pterodactyl}"
SCRIPT_DIR="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)"
STAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP_DIR="/root/monte-theme-backup-${STAMP}"

log(){ printf '\033[1;31m[MONTE]\033[0m %s\n' "$*"; }
fail(){ printf '\033[1;31m[MONTE][ERROR]\033[0m %s\n' "$*" >&2; exit 1; }

[[ $EUID -eq 0 ]] || fail "Run this installer as root."
[[ -d "$PANEL_DIR" && -f "$PANEL_DIR/artisan" ]] || fail "Pterodactyl was not found at $PANEL_DIR. Set PANEL_DIR=/path/to/panel."
command -v mysql >/dev/null || fail "mysql client is required."
command -v mysqldump >/dev/null || fail "mysqldump is required."
command -v blueprint >/dev/null || fail "Blueprint is not installed. Install Blueprint from https://blueprint.zip, then run this installer again."
[[ -f "$SCRIPT_DIR/nebula.blueprint" ]] || fail "nebula.blueprint is missing from this release."

set -a
source "$PANEL_DIR/.env"
set +a
APP_URL="${APP_URL:-http://localhost}"
APP_URL="${APP_URL%/}"
BG_URL="$APP_URL/nebula-assets/samurai.jpg"
LOGO_URL="$APP_URL/nebula-assets/monte-logo.jpg"
BG_VALUE="s:${#BG_URL}:\"${BG_URL}\";"
LOGO_VALUE="s:${#LOGO_URL}:\"${LOGO_URL}\";"

mkdir -p "$BACKUP_DIR"
log "Backing up the existing Nebula files and settings to $BACKUP_DIR"
mysqldump --single-transaction "$DB_DATABASE" settings > "$BACKUP_DIR/settings.sql"
tar -czf "$BACKUP_DIR/nebula-files.tgz" -C "$PANEL_DIR" \
  .blueprint/extensions/nebula public/nebula-assets resources/views/blueprint 2>/dev/null || true

log "Installing or updating the bundled Nebula Blueprint extension"
cd "$PANEL_DIR"
BUNDLE_NAME=".monte-nebula-${STAMP}.blueprint"
cp -f "$SCRIPT_DIR/nebula.blueprint" "$PANEL_DIR/$BUNDLE_NAME"
trap 'rm -f "$PANEL_DIR/$BUNDLE_NAME"' EXIT
TERM=xterm blueprint -i "./$BUNDLE_NAME"
rm -f "$PANEL_DIR/$BUNDLE_NAME"
trap - EXIT

log "Installing Monte assets and Blade overrides"
mkdir -p "$PANEL_DIR/public/nebula-assets/icons3d" \
  "$PANEL_DIR/.blueprint/extensions/nebula/views/wrapper/sidebar" \
  "$PANEL_DIR/.blueprint/extensions/nebula/wrappers"
cp -f "$SCRIPT_DIR/assets/monte-logo.jpg" "$PANEL_DIR/public/nebula-assets/monte-logo.jpg"
cp -f "$SCRIPT_DIR/assets/samurai.jpg" "$PANEL_DIR/public/nebula-assets/samurai.jpg"
cp -f "$SCRIPT_DIR/assets/icons3d/"*.svg "$PANEL_DIR/public/nebula-assets/icons3d/"
cp -f "$SCRIPT_DIR/overrides/dashboard.blade.php" "$PANEL_DIR/.blueprint/extensions/nebula/wrappers/dashboard.blade.php"
cp -f "$SCRIPT_DIR/overrides/sidebar-content.blade.php" "$PANEL_DIR/.blueprint/extensions/nebula/views/wrapper/sidebar/content.blade.php"
cp -f "$SCRIPT_DIR/overrides/sidebar-mobile.blade.php" "$PANEL_DIR/.blueprint/extensions/nebula/views/wrapper/sidebar/mobile.blade.php"

log "Applying Monte settings"
mysql -h "${DB_HOST:-127.0.0.1}" -P "${DB_PORT:-3306}" -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" <<SQL
INSERT INTO settings (\`key\`,\`value\`) VALUES ('nebula::background_image','$BG_VALUE') ON DUPLICATE KEY UPDATE \`value\`=VALUES(\`value\`);
INSERT INTO settings (\`key\`,\`value\`) VALUES ('nebula::background_appearance','s:1:"0";') ON DUPLICATE KEY UPDATE \`value\`=VALUES(\`value\`);
INSERT INTO settings (\`key\`,\`value\`) VALUES ('nebula::dashboard_transparency','s:1:"1";') ON DUPLICATE KEY UPDATE \`value\`=VALUES(\`value\`);
INSERT INTO settings (\`key\`,\`value\`) VALUES ('nebula::sidebar_background','s:7:"blurred";') ON DUPLICATE KEY UPDATE \`value\`=VALUES(\`value\`);
INSERT INTO settings (\`key\`,\`value\`) VALUES ('nebula::icon_fallback','s:6:"lucide";') ON DUPLICATE KEY UPDATE \`value\`=VALUES(\`value\`);
INSERT INTO settings (\`key\`,\`value\`) VALUES ('nebula::sidebar_full','s:1:"1";') ON DUPLICATE KEY UPDATE \`value\`=VALUES(\`value\`);
INSERT INTO settings (\`key\`,\`value\`) VALUES ('nebula::icon_scale','s:4:"1.00";') ON DUPLICATE KEY UPDATE \`value\`=VALUES(\`value\`);
INSERT INTO settings (\`key\`,\`value\`) VALUES ('nebula::sidebar_border_radius','s:2:"12";') ON DUPLICATE KEY UPDATE \`value\`=VALUES(\`value\`);
INSERT INTO settings (\`key\`,\`value\`) VALUES ('nebula::sidebar_customlogo','$LOGO_VALUE') ON DUPLICATE KEY UPDATE \`value\`=VALUES(\`value\`);
INSERT INTO settings (\`key\`,\`value\`) VALUES ('nebula::auth_customlogo','$LOGO_VALUE') ON DUPLICATE KEY UPDATE \`value\`=VALUES(\`value\`);
SQL

php artisan view:clear >/dev/null
php artisan cache:clear >/dev/null
chown -R www-data:www-data "$PANEL_DIR/storage" "$PANEL_DIR/bootstrap/cache" "$PANEL_DIR/public/nebula-assets"
find "$PANEL_DIR/public/nebula-assets" -type f -exec chmod 644 {} +
log "Monte theme installed successfully. Backup: $BACKUP_DIR"
