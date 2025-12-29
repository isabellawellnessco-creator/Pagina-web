param(
    [string]$Path,
    [switch]$Force
)

function Write-Log {
    param([string]$Message)
    Write-Host $Message
}

if (-not $Path) {
    $Path = Read-Host "Ruta a carpeta con zips"
}

if (-not (Test-Path -Path $Path)) {
    Write-Log "[ERROR] Folder not found: $Path"
    exit 1
}

$RootDir = Resolve-Path (Join-Path $PSScriptRoot "..")
$ThemesDir = Join-Path $RootDir "wp-content/themes"
$PluginsDir = Join-Path $RootDir "wp-content/plugins"

function Install-Zip {
    param(
        [string]$Pattern,
        [string]$Target,
        [string]$Label
    )

    $zip = Get-ChildItem -Path $Path -Filter $Pattern | Select-Object -First 1

    if (-not $zip) {
        Write-Log "[WARN] No zip found for $Label ($Pattern)"
        return
    }

    if ((Test-Path -Path $Target) -and -not $Force) {
        Write-Log "[SKIP] $Label already exists at $Target (use -Force to overwrite)"
        return
    }

    if ((Test-Path -Path $Target) -and $Force) {
        Write-Log "[INFO] Removing existing $Label at $Target"
        Remove-Item -Recurse -Force $Target
    }

    Write-Log "[INFO] Installing $Label from $($zip.FullName)"
    Expand-Archive -Path $zip.FullName -DestinationPath $Target -Force
}

New-Item -ItemType Directory -Force -Path $ThemesDir | Out-Null
New-Item -ItemType Directory -Force -Path $PluginsDir | Out-Null

Install-Zip -Pattern "woodmart*.zip" -Target (Join-Path $ThemesDir "woodmart") -Label "WoodMart theme"
Install-Zip -Pattern "elementskit*.zip" -Target (Join-Path $PluginsDir "elementskit") -Label "ElementsKit plugin"

Write-Log "[DONE] Third-party installation completed."
