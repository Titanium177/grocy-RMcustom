# Release Documentation

This document provides comprehensive information about the release process for Grocy Custom, including how the automation works, what happens behind the scenes, and how to create and manage releases.

## Table of Contents

1. [Quick Start](#quick-start)
2. [How It Works](#how-it-works)
3. [Detailed Process](#detailed-process)
4. [GitHub Actions Workflow](#github-actions-workflow)
5. [Troubleshooting](#troubleshooting)
6. [Best Practices](#best-practices)

## Quick Start

To create a new release:

```bash
# Create and push a Git tag
git tag -a v1.2.0 -m "Release version 1.2.0"
git push origin v1.2.0
```

That's it! The rest happens automatically. See the **Actions** tab to monitor the build.

## How It Works

### The Release Pipeline

```
┌─────────────────┐
│  Git Tag Pushed │  (v1.2.0)
└────────┬────────┘
         │
         ▼
┌────────────────────────────────────────┐
│  GitHub Actions Workflow Triggered     │
│  File: .github/workflows/release.yml   │
└────────┬───────────────────────────────┘
         │
         ├─► Extract version from tag
         │   └─► v1.2.0 → 1.2.0
         │
         ├─► Update version.json
         │   └─► Set Version + current date
         │
         ├─► Build ZIP package
         │   ├─► Include: Source code, dependencies
         │   └─► Exclude: .git, docs, test, data, etc.
         │
         └─► Create GitHub Release (Draft)
             └─► Attach ZIP as asset
             
┌────────────────────────────────────────┐
│  Manual Step: Finalize Release         │
│  ✏️ Edit release notes                  │
│  ✅ Publish release                     │
└────────────────────────────────────────┘
```

### Why This Approach?

✅ **Automation** - No manual ZIP creation  
✅ **Consistency** - Same process every time  
✅ **Safety** - Draft releases let you review before publishing  
✅ **Traceability** - Git tags link to commits  
✅ **Distribution** - ZIP always matches Git commit exactly

## Detailed Process

### Step 1: Create a Git Tag

Git tags mark a specific point in your repository history. They're perfect for releases.

```bash
# Lightweight tag (simple marker)
git tag v1.2.0

# Annotated tag (recommended - includes metadata)
git tag -a v1.2.0 -m "Release version 1.2.0"

# With details
git tag -a v1.2.0 -m "Release version 1.2.0

Features:
- New stock management UI
- Performance improvements
- Bug fixes"
```

### Step 2: Push the Tag

```bash
# Push single tag
git push origin v1.2.0

# Push all tags
git push origin --tags
```

The moment you push a tag matching `v*`, the GitHub Actions workflow triggers automatically.

### Step 3: Monitor the Build

1. Go to your repo on GitHub
2. Click **Actions** tab
3. You'll see a "Create Release Package" workflow running
4. Each step shows:
   - ✅ Checkout code
   - ✅ Extract version
   - ✅ Update version.json
   - ✅ Install dependencies (7zip, jq)
   - ✅ Create ZIP package
   - ✅ Create GitHub Release (Draft)

Expected duration: **2-3 minutes**

### Step 4: Finalize the Release

Once the workflow completes:

1. Go to **Releases** on GitHub
2. Find the draft release
3. Click **Edit** (pencil icon)
4. Add release notes with:
   - Summary of changes
   - New features
   - Bug fixes
   - Breaking changes (if any)
   - Special notes
5. Click **Publish release**

## GitHub Actions Workflow

### File Location

```
.github/workflows/release.yml
```

### What It Does

#### 1. Extract Version from Tag
```yaml
- name: Extract version from tag
  run: |
    VERSION=${GITHUB_REF#refs/tags/v}
    echo "version=$VERSION" >> $GITHUB_OUTPUT
```
Converts `refs/tags/v1.2.0` → `1.2.0`

#### 2. Update version.json
```yaml
- name: Update version.json
  run: |
    DATE=$(date -u +%Y-%m-%d)
    jq --arg version "1.2.0" \
       --arg date "2026-07-20" \
       '.Version = $version | .ReleaseDate = $date' \
       version.json
```
Updates `version.json` with new version and build date.

#### 3. Create ZIP Package
```bash
7z a -r .release/grocy-custom_1.2.0.zip . \
  -xr!.git \
  -xr!.github \
  -xr!docs \
  -xr!test \
  # ... more exclusions
```

**Included** in ZIP:
- `public/` - Frontend assets
- `app/` - Application code
- `services/` - Service layer
- `vendor/` - PHP dependencies
- `config-dist.php` - Configuration template

**Excluded** from ZIP:
- `.git/` - Repository history (not needed for installation)
- `.github/` - CI/CD workflows (not needed)
- `data/` - User data (kept separate during updates)
- `docs/` - Development documentation
- `test/` - Test files
- `composer.json` - Dev dependencies list
- `package.json` - Node dependencies
- FontAwesome source files

#### 4. Create GitHub Release
```yaml
- name: Create Release
  uses: softprops/action-gh-release@v1
  with:
    files: .release/grocy-custom_1.2.0.zip
    draft: true
```

Creates a draft release with the ZIP attached as an asset.

## Troubleshooting

### Workflow Failed to Run

**Problem**: "Workflow did not trigger after pushing tag"

**Solution**:
1. Check tag format: Must be `v*` (e.g., `v1.2.0`)
2. Verify workflow file exists: `.github/workflows/release.yml`
3. Check Actions are enabled in repo settings
4. Try pushing again: `git push origin v1.2.0`

### ZIP File Not Created

**Problem**: "Release created but ZIP is missing"

**Solution**:
1. Check build logs in Actions tab
2. Look for "Create release package" step
3. Common issues:
   - 7zip not installed (workflow installs it automatically)
   - File permissions (workflow runs as github-actions user)
   - Not enough disk space (rare on GitHub runners)

### Version.json Not Updated

**Problem**: "version.json still shows old version"

**Solution**:
1. This is **not a problem** - the workflow updates it in the ZIP
2. The file in the repo stays unchanged
3. After installation, users have the new version

### Release Published But Can't Download ZIP

**Problem**: "Release exists but ZIP won't download"

**Solution**:
1. Wait 1-2 minutes (CDN caching)
2. Refresh the page
3. Check that workflow completed successfully
4. Try manual download from Actions artifacts

## Best Practices

### 1. Use Semantic Versioning

```
MAJOR.MINOR.PATCH
  1   .  2   .   0

MAJOR - Breaking changes
MINOR - New features (backward compatible)
PATCH - Bug fixes
```

Examples:
- `v1.0.0` - First release
- `v1.1.0` - Added stock API
- `v1.1.1` - Fixed date parsing bug
- `v2.0.0` - Rewrote database schema

### 2. Tag Commit Messages

Include context in the tag message:

```bash
git tag -a v1.2.0 -m "Release version 1.2.0

Features:
- New dashboard
- Improved search
- Dark mode

Fixes:
- #123 Date input bug
- #124 Performance issue

Breaking Changes:
- Requires PHP 8.5+"
```

### 3. Keep Releases Consistent

Always include in release notes:
- 📋 Summary
- ✨ New Features
- 🐛 Bug Fixes
- ⚠️ Breaking Changes
- 📦 Installation link

Example:
```
## Version 1.2.0 - 2026-07-20

### Summary
Performance improvements and new features focused on stock management.

### Features
- New dashboard overview
- Improved barcode scanning
- Dark mode support

### Fixes
- #123 Fixed date input bug
- #124 Optimized database queries

### Breaking Changes
- Requires PHP 8.5+ (was 8.0+)

### Installation
Download `grocy-custom_1.2.0.zip` and follow the [installation guide](../README.md#how-to-install).
```

### 4. Test Before Releasing

Before creating a tag:
```bash
# Test the current code
php -S localhost:8000 -t public/

# Run any automated tests
# ./vendor/bin/phpunit
```

### 5. One Release Per Tag

Create a new tag for each release. Don't reuse tags:

```bash
# ✅ Good
git tag v1.0.0   # Release 1.0.0
git tag v1.0.1   # Release 1.0.1
git tag v1.1.0   # Release 1.1.0

# ❌ Bad
git tag v1.0.0   # First attempt
git tag -d v1.0.0
git tag v1.0.0   # Reusing the tag
```

### 6. Publish Releases Promptly

Don't leave draft releases hanging:
- Review within 24 hours
- Publish or delete
- Keep changelog up to date

## Advanced Topics

### Viewing Tag Information

```bash
# List all tags
git tag -l

# List tags with descriptions
git tag -l -n

# Show specific tag details
git show v1.2.0

# See which commit a tag points to
git rev-parse v1.2.0
```

### Deleting Tags

```bash
# Delete local tag
git tag -d v1.2.0

# Delete remote tag
git push origin :v1.2.0
# or
git push --delete origin v1.2.0
```

### Re-releasing (If Needed)

If you need to fix something after creating a release:

```bash
# Undo the release (delete local tag)
git tag -d v1.2.0

# Make your fixes
git add .
git commit -m "Fix critical bug"

# Create tag again with fix
git tag -a v1.2.0-fixed -m "Fixed version of 1.2.0"
git push origin v1.2.0-fixed
```

## Questions?

See [CONTRIBUTING.md](.github/CONTRIBUTING.md) or open an issue on GitHub.