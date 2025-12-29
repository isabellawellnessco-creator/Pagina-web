# Homad WordPress Project

This repository contains the **custom code** for the Homad WordPress project. It is safe to version control because it only includes:

- The `homad-child` theme (child of WoodMart)
- The `homad-core` plugin (CPTs + shortcodes + templates)
- Scripts and documentation for installing third-party dependencies

Third-party code (WoodMart, ElementsKit, WordPress core) must stay **outside git**.

## What is versioned

- `/homad-wp/wp-content/themes/homad-child/`
- `/homad-wp/wp-content/plugins/homad-core/`
- `/homad-wp/scripts/` and `/homad-wp/third-party/`

## Third-party installation (WoodMart + ElementsKit)

1. Download the vendor zips locally (do not commit them):
   - `woodmart.8.2.7.zip`
   - `elementskit-4.1.0.zip`
2. Run the script and provide the folder path with the zips:

**Bash**
```bash
cd homad-wp/scripts
./install-thirdparty.sh
```

**PowerShell**
```powershell
cd homad-wp/scripts
./install-thirdparty.ps1
```

Optional flags:
- `--path <zip_folder>` (bash) or `-Path <zip_folder>` (PowerShell)
- `--force` or `-Force` to overwrite existing installs

## Activation order

1. Activate **WoodMart** (parent theme).
2. Activate **Homad Child** theme.
3. Install/activate **ElementsKit** plugin.
4. Activate **Homad Core** plugin.

## Notes

- This is a private repo, but still avoid mixing or committing any third-party code unless explicitly required.
- The project includes placeholders and skeleton templates only; add final content in WordPress or in future theme updates.
