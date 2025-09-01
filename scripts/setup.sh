#!/usr/bin/env bash
set -euo pipefail

# Project bootstrap for Laravel + Vite
# Safe to run multiple times.

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")"/.. && pwd)"
cd "$ROOT_DIR"

echo "[setup] Starting setup in $ROOT_DIR"

have() { command -v "$1" >/dev/null 2>&1; }

# 1) PHP deps
if have composer; then
  echo "[setup] Installing PHP dependencies (composer install)"
  composer install --no-interaction --prefer-dist --ansi
else
  echo "[setup] WARNING: composer not found; skipping PHP deps" >&2
fi

# 2) Node deps
if have npm; then
  if [[ -f package-lock.json ]]; then
    echo "[setup] Installing Node dependencies (npm ci)"
    npm ci --no-progress
  else
    echo "[setup] Installing Node dependencies (npm install)"
    npm install --no-progress
  fi
else
  echo "[setup] WARNING: npm not found; skipping Node deps" >&2
fi

# 3) Environment file
if [[ -f .env ]]; then
  echo "[setup] .env already exists"
elif [[ -f .env.example ]]; then
  echo "[setup] Creating .env from .env.example"
  cp .env.example .env
fi

# 4) SQLite database (default in config)
if [[ -f .env ]] && grep -q '^DB_CONNECTION=sqlite' .env; then
  mkdir -p database
  if [[ ! -f database/database.sqlite ]]; then
    echo "[setup] Creating SQLite database file"
    : > database/database.sqlite
  fi
fi

# 5) App key + migrations + links
if have php; then
  if [[ -f artisan ]]; then
    echo "[setup] Generating APP_KEY (if missing)"
    php artisan key:generate --ansi || true

    echo "[setup] Running migrations (graceful)"
    php artisan migrate --graceful --ansi || true

    echo "[setup] Creating storage symlink"
    php artisan storage:link --ansi || true

    echo "[setup] Optimizing caches"
    php artisan optimize --ansi || true
  fi
else
  echo "[setup] WARNING: php not found; skipping artisan commands" >&2
fi

echo "[setup] Done. Start dev with: composer run dev"

