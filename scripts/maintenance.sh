#!/usr/bin/env bash
set -euo pipefail

# Refresh dependencies and app caches

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")"/.. && pwd)"
cd "$ROOT_DIR"

have() { command -v "$1" >/dev/null 2>&1; }

if have composer; then
  echo "[maintenance] composer install"
  composer install --no-interaction --prefer-dist --ansi
fi

if have npm; then
  if [[ -f package-lock.json ]]; then
    echo "[maintenance] npm ci"
    npm ci --no-progress
  else
    echo "[maintenance] npm install"
    npm install --no-progress
  fi
fi

if have php && [[ -f artisan ]]; then
  echo "[maintenance] migrate (graceful)"
  php artisan migrate --graceful --ansi || true

  echo "[maintenance] optimize"
  php artisan optimize --ansi || true
fi

echo "[maintenance] Done"

