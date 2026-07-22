# Contributing to Grocy Custom

Thank you for your interest in contributing to Grocy Custom! This document outlines guidelines and processes for contributors.

## Release Process

This project uses an automated release pipeline based on Git tags. Here's how it works:

### Creating a Release

1. **Update version.json** (optional - it will be auto-updated by the workflow)
   ```json
   {
     "Version": "1.2.0",
     "ReleaseDate": "2026-07-20"
   }
   ```

2. **Create and push a Git tag** in the format `v<version>`:
   ```bash
   git tag -a v1.2.0 -m "Release version 1.2.0"
   git push origin v1.2.0
   ```

3. **The GitHub Actions workflow will automatically**:
   - ✅ Update `version.json` with the current date
   - ✅ Build a ZIP package (`grocy-custom_1.2.0.zip`)
   - ✅ Create a GitHub Release as a **draft**
   - ✅ Attach the ZIP as an asset

4. **Finalize the release**:
   - Go to [Releases](https://github.com/Titanium177/grocy-custom/releases)
   - Edit the draft release
   - Add detailed release notes (changelog, improvements, etc.)
   - Click **"Publish release"** when ready

### Version Format

Use [Semantic Versioning](https://semver.org/):
- `v1.0.0` - Initial release
- `v1.1.0` - New features (minor version bump)
- `v1.0.1` - Bug fixes (patch version bump)
- `v2.0.0` - Breaking changes (major version bump)

### What's Included in the ZIP Package?

The automated build includes:
- All source code (PHP, JavaScript, Blade templates)
- Configuration files
- Dependencies from `vendor/` and `public/packages/`

The automated build **excludes**:
- `.git/` - Git repository
- `.github/` - GitHub workflows
- `docs/` - Documentation
- `test/` - Test files
- `data/` - User data and cache
- `composer.json`, `package.json` - Dev dependencies
- Font files and development assets

This ensures a clean, production-ready package.

## Development Guidelines

### Branch Strategy

- `master` - Development branch (bleeding edge)
- `release` - Stable release branch (always matches latest release tag)
- Feature branches - For new features (`feature/description`)
- Bugfix branches - For bug fixes (`bugfix/description`)

### Code Style

Follow the existing code style in the project:
- PHP: PSR-12 compliant
- JavaScript: Consistent with existing patterns
- Blade templates: Consistent indentation and structure

### Commits

- Write clear, descriptive commit messages
- Use present tense: "Add feature" not "Added feature"
- Reference issues where applicable: "Fix #123"

### Pull Requests

Before submitting a PR:
1. Ensure your code follows the project's style
2. Test your changes locally
3. Update documentation if needed
4. Provide a clear description of what changed and why

## Questions?

Feel free to open an issue or discussion for questions about the release process or contribution guidelines.