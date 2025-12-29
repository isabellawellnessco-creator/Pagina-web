#!/usr/bin/env bash
set -euo pipefail

FORCE=false
ZIP_DIR="${ZIP_DIR:-}"

usage() {
  echo "Usage: $0 [--path <zip_folder>] [--force]"
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --force)
      FORCE=true
      shift
      ;;
    --path)
      ZIP_DIR="$2"
      shift 2
      ;;
    -h|--help)
      usage
      exit 0
      ;;
    *)
      echo "Unknown option: $1"
      usage
      exit 1
      ;;
  esac
done

if [[ -z "$ZIP_DIR" ]]; then
  read -r -p "Ruta a carpeta con zips: " ZIP_DIR
fi

if [[ ! -d "$ZIP_DIR" ]]; then
  echo "[ERROR] Folder not found: $ZIP_DIR"
  exit 1
fi

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
THEMES_DIR="$ROOT_DIR/wp-content/themes"
PLUGINS_DIR="$ROOT_DIR/wp-content/plugins"

extract_zip() {
  local pattern="$1"
  local target="$2"
  local label="$3"

  shopt -s nullglob
  local matches=("$ZIP_DIR"/$pattern)
  shopt -u nullglob

  if [[ ${#matches[@]} -eq 0 ]]; then
    echo "[WARN] No zip found for $label ($pattern)"
    return 0
  fi

  local zip_file="${matches[0]}"

  if [[ -d "$target" && "$FORCE" != "true" ]]; then
    echo "[SKIP] $label already exists at $target (use --force to overwrite)"
    return 0
  fi

  if [[ -d "$target" && "$FORCE" == "true" ]]; then
    echo "[INFO] Removing existing $label at $target"
    rm -rf "$target"
  fi

  echo "[INFO] Installing $label from $zip_file"
  unzip -q "$zip_file" -d "$target"
}

mkdir -p "$THEMES_DIR" "$PLUGINS_DIR"

extract_zip "woodmart*.zip" "$THEMES_DIR/woodmart" "WoodMart theme"
extract_zip "elementskit*.zip" "$PLUGINS_DIR/elementskit" "ElementsKit plugin"

echo "[DONE] Third-party installation completed."
