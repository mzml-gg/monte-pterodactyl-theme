#!/usr/bin/env bash
set -Eeuo pipefail
export TERM="${TERM:-xterm}"

# Monte Pterodactyl Theme installer.
# Installs a clean Monte/Nebula theme layer and validates the panel afterwards.

PANEL_DIR="${PANEL_DIR:-/var/www/pterodactyl}"
SCRIPT_DIR="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)"
STAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP_DIR="/root/monte-theme-backup-${STAMP}"
FAILURES=0

red=$'\033[1;31m'; green=$'\033[1;32m'; yellow=$'\033[1;33m'; cyan=$'\033[1;36m'; reset=$'\033[0m'
log(){ printf '%s[MONTE]%s %s\n' "$red" "$reset" "$*"; }
ok(){ printf '%s%-18s 200 ✅%s\n' "$green" "$1" "$reset"; }
bad(){ printf '%s%-18s 503 ❌%s\n' "$red" "$1" "$reset"; FAILURES=1; }
fail(){ printf '%s[MONTE][ERROR]%s %s\n' "$red" "$reset" "$*" >&2; exit 1; }

[[ $EUID -eq 0 ]] || fail 'Run this installer as root.'
[[ -d "$PANEL_DIR" && -f "$PANEL_DIR/artisan" ]] || fail "Pterodactyl was not found at $PANEL_DIR. Set PANEL_DIR=/path/to/panel."
command -v mysql >/dev/null || fail 'mysql client is required.'
command -v mysqldump >/dev/null || fail 'mysqldump is required.'
command -v blueprint >/dev/null || fail 'Blueprint is not installed. Install Blueprint from https://blueprint.zip first.'
command -v unzip >/dev/null || fail 'unzip is required.'
command -v zip >/dev/null || fail 'zip is required.'
command -v curl >/dev/null || fail 'curl is required.'
[[ -f "$SCRIPT_DIR/nebula.blueprint" ]] || fail 'nebula.blueprint is missing from this release.'

set -a
source "$PANEL_DIR/.env"
set +a
APP_URL="${APP_URL:-http://localhost}"
APP_URL="${APP_URL%/}"
BG_URL="$APP_URL/nebula-assets/samurai.jpg"
LOGO_URL="$APP_URL/nebula-assets/monte-logo.jpg"
ICON_BASE="$APP_URL/nebula-assets/icons3d"

mkdir -p "$BACKUP_DIR"
log "Backing up the current theme and settings to $BACKUP_DIR"
mysqldump --single-transaction "$DB_DATABASE" settings > "$BACKUP_DIR/settings.sql"
tar -czf "$BACKUP_DIR/nebula-files.tgz" -C "$PANEL_DIR" \
  .blueprint/extensions/nebula public/nebula-assets resources/views/blueprint 2>/dev/null || true

cd "$PANEL_DIR"
if [[ -f .blueprint/extensions/blueprint/private/db/installed_extensions ]] \
  && grep -q '|nebula,' .blueprint/extensions/blueprint/private/db/installed_extensions; then
  log 'Removing the existing Nebula theme layer before a clean Monte install'
  printf 'y\n' | blueprint -r nebula || fail 'Could not remove the existing Nebula extension. Backup was created.'
fi

log 'Installing the bundled Nebula extension locally through Blueprint'
BUNDLE_NAME=".monte-nebula-${STAMP}.blueprint"
BUNDLE_WORK="$(mktemp -d)"
cleanup_bundle(){ rm -rf "$BUNDLE_WORK"; rm -f "$PANEL_DIR/$BUNDLE_NAME"; }
trap cleanup_bundle EXIT
unzip -q "$SCRIPT_DIR/nebula.blueprint" -d "$BUNDLE_WORK"
# The supplied Nebula notice contains two read prompts. Disable only those prompts
# so this installer remains safe for one-paste, unattended console execution.
if [[ -f "$BUNDLE_WORK/private/install.sh" ]]; then
  sed -i -e '/press enter to continue/s/^/# Monte unattended: /' \
         -e 's/read r/r=""/g' "$BUNDLE_WORK/private/install.sh"
fi
( cd "$BUNDLE_WORK" && zip -qr "$PANEL_DIR/$BUNDLE_NAME" . )
blueprint -i "$BUNDLE_NAME"
cleanup_bundle
trap - EXIT

log 'Cleaning and installing Monte assets and visual overrides'
rm -rf "$PANEL_DIR/public/nebula-assets"
mkdir -p "$PANEL_DIR/public/nebula-assets/icons3d" \
  "$PANEL_DIR/.blueprint/extensions/nebula/views/wrapper/sidebar" \
  "$PANEL_DIR/.blueprint/extensions/nebula/wrappers"
cp -f "$SCRIPT_DIR/assets/monte-logo.jpg" "$PANEL_DIR/public/nebula-assets/monte-logo.jpg"
cp -f "$SCRIPT_DIR/assets/samurai.jpg" "$PANEL_DIR/public/nebula-assets/samurai.jpg"
cp -f "$SCRIPT_DIR/assets/icons3d/"*.svg "$PANEL_DIR/public/nebula-assets/icons3d/"
cp -f "$SCRIPT_DIR/overrides/dashboard.blade.php" "$PANEL_DIR/.blueprint/extensions/nebula/wrappers/dashboard.blade.php"
cp -f "$SCRIPT_DIR/overrides/sidebar-content.blade.php" "$PANEL_DIR/.blueprint/extensions/nebula/views/wrapper/sidebar/content.blade.php"
cp -f "$SCRIPT_DIR/overrides/sidebar-mobile.blade.php" "$PANEL_DIR/.blueprint/extensions/nebula/views/wrapper/sidebar/mobile.blade.php"

ser(){ local v="$1"; printf 's:%s:"%s";' "${#v}" "$v"; }
BG_VALUE="$(ser "$BG_URL")"
AUTH_BG_VALUE="$(ser "$BG_URL")"
LOGO_VALUE="$(ser "$LOGO_URL")"

SQL_FILE="$(mktemp)"
trap 'rm -f "$SQL_FILE"; cleanup_bundle' EXIT
cat > "$SQL_FILE" <<SQL
INSERT INTO settings (\`key\`,\`value\`) VALUES ('nebula::background_image','$BG_VALUE') ON DUPLICATE KEY UPDATE \`value\`=VALUES(\`value\`);
INSERT INTO settings (\`key\`,\`value\`) VALUES ('nebula::auth_background_image','$AUTH_BG_VALUE') ON DUPLICATE KEY UPDATE \`value\`=VALUES(\`value\`);
INSERT INTO settings (\`key\`,\`value\`) VALUES ('nebula::background_appearance','s:1:"0";') ON DUPLICATE KEY UPDATE \`value\`=VALUES(\`value\`);
INSERT INTO settings (\`key\`,\`value\`) VALUES ('nebula::auth_background_appearance','s:1:"0";') ON DUPLICATE KEY UPDATE \`value\`=VALUES(\`value\`);
INSERT INTO settings (\`key\`,\`value\`) VALUES ('nebula::dashboard_transparency','s:1:"1";') ON DUPLICATE KEY UPDATE \`value\`=VALUES(\`value\`);
INSERT INTO settings (\`key\`,\`value\`) VALUES ('nebula::sidebar_background','s:7:"blurred";') ON DUPLICATE KEY UPDATE \`value\`=VALUES(\`value\`);
INSERT INTO settings (\`key\`,\`value\`) VALUES ('nebula::sidebar_full','s:1:"1";') ON DUPLICATE KEY UPDATE \`value\`=VALUES(\`value\`);
INSERT INTO settings (\`key\`,\`value\`) VALUES ('nebula::sidebar_border_radius','s:2:"12";') ON DUPLICATE KEY UPDATE \`value\`=VALUES(\`value\`);
INSERT INTO settings (\`key\`,\`value\`) VALUES ('nebula::icon_fallback','s:6:"lucide";') ON DUPLICATE KEY UPDATE \`value\`=VALUES(\`value\`);
INSERT INTO settings (\`key\`,\`value\`) VALUES ('nebula::icon_scale','s:3:"1.0";') ON DUPLICATE KEY UPDATE \`value\`=VALUES(\`value\`);
INSERT INTO settings (\`key\`,\`value\`) VALUES ('nebula::sidebar_customlogo','$LOGO_VALUE') ON DUPLICATE KEY UPDATE \`value\`=VALUES(\`value\`);
INSERT INTO settings (\`key\`,\`value\`) VALUES ('nebula::auth_customlogo','$LOGO_VALUE') ON DUPLICATE KEY UPDATE \`value\`=VALUES(\`value\`);
SQL

add_icon(){
  local key="$1" file="$2" value
  value="$(ser "$ICON_BASE/$file.svg")"
  printf "INSERT INTO settings (\`key\`,\`value\`) VALUES ('nebula::%s','%s') ON DUPLICATE KEY UPDATE \`value\`=VALUES(\`value\`);\n" "$key" "$value" >> "$SQL_FILE"
}
add_icon sidebar_home home
add_icon sidebar_server_terminal terminal
add_icon sidebar_server_files files
add_icon sidebar_server_databases databases
add_icon sidebar_server_schedules schedules
add_icon sidebar_server_users users
add_icon sidebar_server_backups backups
add_icon sidebar_server_network network
add_icon sidebar_server_startup startup
add_icon sidebar_server_settings settings
add_icon sidebar_server_activity activity
add_icon sidebar_admin admin
add_icon sidebar_account_account account
add_icon sidebar_account_activity activity
add_icon sidebar_logout logout

log 'Applying Monte background, transparency, logo, and icon settings'
mysql -h "${DB_HOST:-127.0.0.1}" -P "${DB_PORT:-3306}" -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" < "$SQL_FILE"
rm -f "$SQL_FILE"
php artisan optimize:clear >/dev/null
chown -R www-data:www-data "$PANEL_DIR/storage" "$PANEL_DIR/bootstrap/cache" "$PANEL_DIR/public/nebula-assets"
find "$PANEL_DIR/public/nebula-assets" -type f -exec chmod 644 {} +

printf '\n%s╔══════════════════════════════════════╗%s\n' "$cyan" "$reset"
printf '%s║        MONTE INSTALLATION CHECK      ║%s\n' "$cyan" "$reset"
printf '%s╚══════════════════════════════════════╝%s\n' "$cyan" "$reset"
for item in 'docker:docker' 'wings:wings' 'nginx:nginx' 'php-fpm:php8.3-fpm' 'redis:redis-server' 'mysql:mysql'; do
  label="${item%%:*}"; service="${item#*:}"
  if systemctl is-active --quiet "$service"; then ok "${label^^}"; else bad "${label^^}"; fi
done
for pair in "PANEL:$APP_URL/" "LOGO:$LOGO_URL" "BACKGROUND:$BG_URL" "HOME_ICON:$ICON_BASE/home.svg"; do
  label="${pair%%:*}"; url="${pair#*:}"
  code="$(curl -ksS -o /dev/null -w '%{http_code}' "$url" || true)"
  if [[ "$code" == 2* ]]; then ok "$label"; else bad "$label ($code)"; fi
done

if [[ "$FAILURES" -eq 0 ]]; then
  printf '\n%s╔══════════════════════════════════════╗%s\n' "$green" "$reset"
  printf '%s║              DONE ✅                 ║%s\n' "$green" "$reset"
  printf '%s╚══════════════════════════════════════╝%s\n' "$green" "$reset"
  printf '%sBackup: %s%s\n' "$yellow" "$BACKUP_DIR" "$reset"
else
  printf '\n%sDONE WITH ERRORS ❌%s\n' "$red" "$reset"
  printf '%sBackup: %s%s\n' "$yellow" "$BACKUP_DIR" "$reset"
  exit 1
fi
