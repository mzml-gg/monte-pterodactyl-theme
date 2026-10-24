#!/usr/bin/env bash
set -Eeuo pipefail

REPO="${MONTE_REPO:-https://github.com/mzml-gg/monte-pterodactyl-theme.git}"
WORK="$(mktemp -d /tmp/monte-bootstrap.XXXXXX)"
cleanup(){ rm -rf "$WORK"; }
trap cleanup EXIT

red=$'\033[1;31m'; green=$'\033[1;32m'; reset=$'\033[0m'
fail(){ printf '%sMONTE BOOTSTRAP FAILED ❌%s %s\n' "$red" "$reset" "$*" >&2; exit 1; }
[[ $EUID -eq 0 ]] || fail 'Paste this command as root or run it with sudo.'
command -v curl >/dev/null || fail 'curl is required.'
command -v tar >/dev/null || fail 'tar is required.'

if [[ -n "${GITHUB_TOKEN:-}" ]]; then
  curl -fsSL -H "Authorization: Bearer ${GITHUB_TOKEN}" \
    "https://api.github.com/repos/mzml-gg/monte-pterodactyl-theme/tarball/main" \
    -o "$WORK/theme.tgz" || fail 'Could not download the private Monte repository with GITHUB_TOKEN.'
  mkdir -p "$WORK/src"
  tar -xzf "$WORK/theme.tgz" -C "$WORK/src" --strip-components=1
else
  command -v git >/dev/null || fail 'git is required when GITHUB_TOKEN is not provided.'
  git clone --depth 1 "$REPO" "$WORK/src" || fail 'Repository access failed. Authenticate GitHub or provide GITHUB_TOKEN for this private repository.'
fi

chmod +x "$WORK/src/install.sh"
exec env PANEL_DIR="${PANEL_DIR:-/var/www/pterodactyl}" "$WORK/src/install.sh"
