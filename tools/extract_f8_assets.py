import os
import re
import shutil
import hashlib
import json
from bs4 import BeautifulSoup
from urllib.parse import urlparse, unquote

# Configuration
REFS_DIR = 'refs/f8'
THEME_ASSETS_DIR = 'skincare-theme/assets/f8'
REPORT_FILE = 'tools/f8_asset_map.json'

# Mappings
ASSET_TYPES = {
    'image': ['.jpg', '.jpeg', '.png', '.gif', '.svg', '.webp', '.ico'],
    'font': ['.woff', '.woff2', '.ttf', '.eot', '.otf'],
    'css': ['.css'],
    'js': ['.js', '.download'] # .download is often JS in saved pages
}

def ensure_dir(path):
    if not os.path.exists(path):
        os.makedirs(path)

def get_file_hash(filepath):
    hasher = hashlib.md5()
    with open(filepath, 'rb') as f:
        buf = f.read()
        hasher.update(buf)
    return hasher.hexdigest()

def normalize_path(url):
    parsed = urlparse(url)
    path = unquote(parsed.path)
    return path.split('/')[-1]

def main():
    print(f"Scanning {REFS_DIR}...")

    asset_map = {}
    html_files = [f for f in os.listdir(REFS_DIR) if f.endswith('.html')]

    # Ensure base dirs exist
    ensure_dir(THEME_ASSETS_DIR)
    for subdir in ['images', 'fonts', 'css', 'js', 'misc']:
        ensure_dir(os.path.join(THEME_ASSETS_DIR, subdir))

    for html_file in html_files:
        filepath = os.path.join(REFS_DIR, html_file)
        with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
            soup = BeautifulSoup(f, 'html.parser')

        print(f"Processing {html_file}...")

        # Tags to search
        tags = [
            ('img', 'src'),
            ('link', 'href'),
            ('script', 'src'),
            ('source', 'srcset')
        ]

        for tag_name, attr in tags:
            for element in soup.find_all(tag_name):
                url = element.get(attr)
                if not url or url.startswith('data:') or url.startswith('http'):
                    continue

                clean_url = url.split('?')[0]

                # Check for possible paths
                possible_path = os.path.normpath(os.path.join(REFS_DIR, clean_url))

                if os.path.exists(possible_path) and os.path.isfile(possible_path):
                    ext = os.path.splitext(possible_path)[1].lower()

                    asset_type = 'misc'
                    for type_name, extensions in ASSET_TYPES.items():
                        if ext in extensions:
                            asset_type = type_name
                            break

                    filename = os.path.basename(possible_path)

                    # Target subdir
                    target_subdir = asset_type + 's' if asset_type != 'misc' else 'misc'

                    final_dest_dir = os.path.join(THEME_ASSETS_DIR, target_subdir)
                    ensure_dir(final_dest_dir) # Double check creation

                    final_dest_path = os.path.join(final_dest_dir, filename)

                    # Copy file
                    if not os.path.exists(final_dest_path):
                        shutil.copy2(possible_path, final_dest_path)

                    # Map
                    asset_map[url] = f"assets/f8/{target_subdir}/{filename}"

    with open(REPORT_FILE, 'w') as f:
        json.dump(asset_map, f, indent=2)

    print(f"Extraction complete. Map saved to {REPORT_FILE}")

if __name__ == '__main__':
    main()
