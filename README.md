# C28 — Software & Cloud Engineering (`c28studios`)

**"We build digital products and help teams deploy, secure, and operate them reliably."**

C28 is a connected software and cloud engineering studio built for startup founders, small SaaS teams, and growing businesses. Instead of forcing teams to hire separate contractors for application code, Linux/Docker server configuration, CI/CD automation, and security, C28 delivers one cohesive engineering partnership from local codebase to hardened production operations.

---

## 1. Strict 4-Tier Service Hierarchy

1. **Primary — DevOps & Cloud Engineering (`01 • Primary Service`)**: Application deployment, Linux server configuration & hardening, CI/CD pipelines (GitHub Actions), Docker containerization, cloud infrastructure setup, Nginx reverse proxy & TLS/HTTPS, and operational monitoring.
2. **Secondary — DevSecOps Integration (`02 • Secondary Service`)**: Dependency vulnerability scanning, secret management & dev/production credential isolation, Docker container image scanning (Aqua Trivy), and automated CI/CD security gates.
3. **Supporting — Software Development (`03 • Supporting Service`)**: Multi-tenant SaaS applications, REST/GraphQL APIs, backend systems, business web applications, and custom internal dashboards engineered from day one for clean deployment.
4. **Supporting — Website Development (`04 • Supporting Service`)**: High-conversion business websites, landing pages, e-commerce platforms, website redesigns, performance optimization, and technical SEO.

---

## 2. Locked Tech Stack & Architecture

- **Backend Framework**: Laravel 11 (`PHP 8.2+`)
- **Database**: MySQL 8.0+ (`c28studios`) in production / SQLite support for instant local Herd preview & isolated `:memory:` automated testing
- **Admin CMS / Control Plane**: FilamentPHP v3 (`/admin`) with 7 reusable `C28BuilderBlocks` (`hero_terminal`, `architecture_step_list`, `code_artifact_viewer`, `metric_callout_grid`, `problem_solution_split`, `markdown_prose`, `cta_banner`) and 7 CMS resources (`ServiceResource`, `ProjectResource`, `PricingTierResource`, `PostResource`, `FaqResource`, `SiteSettingResource`, `InquiryResource`)
- **Public Frontend**: Laravel Blade + Alpine.js + Vanilla JS + Tailwind CSS v3.4 compiled via Vite (`resources/css/app.css`, `resources/js/app.js`)
- **Container & Cloud Runtime**: Multi-stage `Dockerfile` (`node:20-alpine` + `composer:2` + `php:8.2-fpm-alpine` + Nginx + Supervisor), `docker-compose.yml` (`app`, `mysql:8.0`, `redis:7-alpine`), and `.github/workflows/deploy.yml` (PHPUnit + Vite + Docker build + Trivy security gate)

---

## 3. Local Development Setup (Laravel Herd / Valet or Artisan)

```bash
# 1. Install PHP & frontend dependencies
composer install
npm install

# 2. Configure environment
cp .env.example .env
php artisan key:generate

# 3. Run migrations and seed realistic C28 studio data
php artisan migrate:fresh --seed

# 4. Compile frontend assets
npm run build
```

### Default `/admin` Control Plane Credentials (Local Seeder)
- **URL**: `https://c28studios.test/admin` (or `http://localhost:8000/admin`)
- **Email**: `admin@c28studios.com`
- **Password**: `password`

---

## 4. Containerized Production Deployment (Docker & Docker Compose)

The repository ships with a multi-stage `Dockerfile` and `docker-compose.yml` orchestrating the non-root PHP 8.2-FPM + Nginx application container (`:8080`), MySQL 8.0, and Redis 7:

```bash
# 1. Build and start the production stack
docker compose up -d --build

# 2. Run database migrations & seed initial studio content inside the container
docker compose exec app php artisan migrate --seed --force

# 3. Verify container healthcheck endpoint
curl -fsS http://127.0.0.1:8080/up
```

Key infrastructure configuration files:
- `Dockerfile` — 3-stage build (`frontend-assets`, `vendor-deps`, `production-runtime`) running with `c28user` ownership and `/up` healthcheck
- `docker/nginx/default.conf` — Listens on `:8080`, enforces strict DevSecOps HTTP security headers (`X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`, `Strict-Transport-Security`), immutable `/build/` caching, and dotfile denial
- `docker/php/opcache.ini` — Production OPcache & JIT tuning
- `docker/supervisor/supervisord.conf` & `docker/entrypoint.sh` — Caches config/routes/views on startup and supervises `php-fpm` and `nginx`
- `.env.production.example` — Hardened production environment template (`APP_ENV=production`, `APP_DEBUG=false`, Redis session/cache/queue)

---

## 5. Automated CI/CD & DevSecOps Pipeline (`.github/workflows/deploy.yml`)

Every `push` and `pull_request` to `main` executes three sequential gates:
1. **`quality-and-tests`**: Sets up PHP 8.2 & Node 20, installs dependencies, runs `npm run build`, and executes the full `php artisan test --ansi` suite.
2. **`devsecops-container-scan`**: Builds the multi-stage production Docker image (`c28studios:${{ github.sha }}`) and runs **Aqua Security Trivy** (`aquasecurity/trivy-action`) to block `CRITICAL` and `HIGH` OS/library vulnerabilities and leaked secrets.
3. **`production-deploy-gate`**: Verifies both upstream gates succeeded on `main` before authorizing production rollout.

---

## 6. Automated Test Suite

```bash
php artisan test --ansi
```

See [`AGENTS.md`](./AGENTS.md) for the authoritative project constitution and engineering phase documentation under [`docs/c28-web/`](./docs/c28-web/).
