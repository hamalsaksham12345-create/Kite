# Contributing to Kite Restaurant SaaS Platform

Thank you for your interest in contributing to Kite! This document provides guidelines and information for contributors.

## 🚀 Getting Started

### Prerequisites
- PHP 8.3+
- Composer
- Node.js 18+
- MySQL 8.0+
- Git

### Development Setup
1. Fork the repository
2. Clone your fork: `git clone https://github.com/YOUR_USERNAME/Kite.git`
3. Install dependencies: `composer install && npm install`
4. Copy environment file: `cp .env.example .env`
5. Generate app key: `php artisan key:generate`
6. Run migrations: `php artisan migrate --seed`
7. Build assets: `npm run build`

## 📋 Development Guidelines

### Code Style
- Follow PSR-12 coding standards
- Use Laravel Pint for code formatting: `./vendor/bin/pint`
- Write meaningful commit messages
- Add PHPDoc comments for all methods

### Testing
- Write tests for new features
- Ensure all tests pass: `php artisan test`
- Maintain test coverage above 80%

### Database Changes
- Create migrations for all database changes
- Include both `up()` and `down()` methods
- Add seeders for demo data when appropriate

## 🔄 Contribution Process

### 1. Create an Issue
Before starting work, create an issue describing:
- The problem you're solving
- Your proposed solution
- Any breaking changes

### 2. Create a Branch
```bash
git checkout -b feature/your-feature-name
# or
git checkout -b fix/your-bug-fix
```

### 3. Make Changes
- Write clean, documented code
- Follow existing patterns and conventions
- Add tests for new functionality
- Update documentation as needed

### 4. Test Your Changes
```bash
# Run tests
php artisan test

# Check code style
./vendor/bin/pint --test

# Build assets
npm run build
```

### 5. Commit Changes
```bash
git add .
git commit -m "feat: add new feature description"
```

Use conventional commit messages:
- `feat:` for new features
- `fix:` for bug fixes
- `docs:` for documentation changes
- `style:` for formatting changes
- `refactor:` for code refactoring
- `test:` for adding tests
- `chore:` for maintenance tasks

### 6. Push and Create PR
```bash
git push origin feature/your-feature-name
```

Then create a Pull Request with:
- Clear title and description
- Reference to related issues
- Screenshots for UI changes
- Test instructions

## 🏗️ Architecture Guidelines

### Multi-Tenancy
- Always use `restaurant_id` for tenant isolation
- Apply `RestaurantScope` to new models
- Test multi-tenant scenarios

### Security
- Validate all inputs
- Use middleware for authorization
- Follow OWASP guidelines
- Never expose sensitive data

### Performance
- Use database indexes appropriately
- Implement caching where beneficial
- Optimize N+1 queries
- Consider pagination for large datasets

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/     # Request handlers
│   └── Middleware/      # Request middleware
├── Models/              # Eloquent models
├── Scopes/             # Global query scopes
└── Services/           # Business logic

resources/
├── views/              # Blade templates
├── css/               # Stylesheets
└── js/                # JavaScript

database/
├── migrations/         # Database schema
├── seeders/           # Demo data
└── factories/         # Model factories
```

## 🎯 Areas for Contribution

### High Priority
- [ ] Restaurant Admin CMS
- [ ] Waiter POS System
- [ ] Chef Kitchen Display
- [ ] Real-time notifications
- [ ] Payment integration

### Medium Priority
- [ ] Reporting and analytics
- [ ] Multi-language support
- [ ] API endpoints
- [ ] Mobile applications
- [ ] Advanced theming

### Low Priority
- [ ] Performance optimizations
- [ ] Additional integrations
- [ ] Enhanced security features
- [ ] Documentation improvements

## 🐛 Bug Reports

When reporting bugs, include:
- Steps to reproduce
- Expected vs actual behavior
- Environment details (OS, PHP version, etc.)
- Error logs and screenshots
- Minimal code example if applicable

## 💡 Feature Requests

For new features, provide:
- Use case and business value
- Detailed requirements
- Mockups or wireframes (if UI-related)
- Implementation suggestions
- Potential breaking changes

## 📞 Getting Help

- Check existing [issues](../../issues)
- Read the [documentation](README.md)
- Ask questions in [discussions](../../discussions)
- Join our community chat (if available)

## 🏆 Recognition

Contributors will be:
- Listed in the README
- Mentioned in release notes
- Invited to join the core team (for significant contributions)

## 📄 License

By contributing, you agree that your contributions will be licensed under the MIT License.

---

Thank you for helping make Kite better! 🚀