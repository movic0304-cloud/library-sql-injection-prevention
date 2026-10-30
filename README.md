# Library SQL Injection Prevention — Reconstructed Project

**Tech:** PHP 8+, PDO, SQLite, SQL

## Background and provenance

This repository is a **2026 reconstruction** inspired by an academic library-management SQL injection prevention project originally completed in **Jan–Apr 2023**. The original source code was unavailable. This implementation was written anew; it is **not** represented as the original 2023 submission. Features and test counts below describe the reconstruction only.

## Features

- Add, search, and retrieve book records
- Add members and record loans through the backend
- Parameterized queries for five database operations (`addBook`, `searchBooks`, `findBook`, `addMember`, `borrowBook`) plus loan lookup
- Safe output escaping in the demo UI
- 15 automated checks covering normal operations, SQL injection strings, wildcard escaping, and database relationships

## Requirements

PHP 8.0+ with `pdo_sqlite` enabled. No Composer dependencies.

## Run locally

```bash
php -S localhost:8000 -t public
```

Open http://localhost:8000. A `library.sqlite` file will be created automatically (ignored by Git).

## Run tests

```bash
php tests/run.php
```

Tests use an in-memory SQLite database and do not alter the application database.

## Security notes

- All user-controlled SQL values are bound using PDO prepared statements; SQL identifiers are not dynamically interpolated.
- Search escapes `%` and `_` so users cannot unintentionally broaden LIKE queries.
- The browser UI escapes output with `htmlspecialchars`.
- This is an educational demo, **not a production-ready system**: it does not implement authentication, authorization, CSRF protection, or deployment hardening.
- No intentionally vulnerable endpoint is included.

## Repository structure

- `schema.sql` — database tables
- `src/bootstrap.php` — database connection and schema initialization
- `src/Library.php` — prepared-statement data access methods
- `public/index.php` — simple search/add interface
- `tests/run.php` — 15 automated checks

## Resume accuracy

If linking this reconstruction from a resume, describe its test counts as **2026 reconstruction results**, not as verified historical measurements from the original 2023 project.
