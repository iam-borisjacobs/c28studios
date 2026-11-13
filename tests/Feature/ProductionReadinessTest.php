<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProductionReadinessTest extends TestCase
{
    public function test_healthcheck_up_endpoint_returns_200_ok(): void
    {
        $response = $this->get('/up');

        $response->assertStatus(200);
    }

    public function test_dockerfile_and_docker_compose_enforce_multi_stage_and_non_root_standards(): void
    {
        $dockerfilePath = base_path('Dockerfile');
        $composePath = base_path('docker-compose.yml');
        $dockerignorePath = base_path('.dockerignore');

        $this->assertFileExists($dockerfilePath);
        $this->assertFileExists($composePath);
        $this->assertFileExists($dockerignorePath);

        $dockerfile = (string) file_get_contents($dockerfilePath);
        $this->assertStringContainsString('FROM node:20-alpine AS frontend-assets', $dockerfile);
        $this->assertStringContainsString('FROM composer:2 AS vendor-deps', $dockerfile);
        $this->assertStringContainsString('FROM php:8.2-fpm-alpine AS production-runtime', $dockerfile);
        $this->assertStringContainsString('c28user', $dockerfile);
        $this->assertStringContainsString('HEALTHCHECK', $dockerfile);
        $this->assertStringContainsString('http://127.0.0.1:8080/up', $dockerfile);

        $compose = (string) file_get_contents($composePath);
        $this->assertStringContainsString('mysql:8.0', $compose);
        $this->assertStringContainsString('redis:7-alpine', $compose);
    }

    public function test_nginx_and_entrypoint_configs_enforce_devsecops_security_headers_and_caching(): void
    {
        $nginxPath = base_path('docker/nginx/default.conf');
        $opcachePath = base_path('docker/php/opcache.ini');
        $supervisorPath = base_path('docker/supervisor/supervisord.conf');
        $entrypointPath = base_path('docker/entrypoint.sh');

        $this->assertFileExists($nginxPath);
        $this->assertFileExists($opcachePath);
        $this->assertFileExists($supervisorPath);
        $this->assertFileExists($entrypointPath);

        $nginx = (string) file_get_contents($nginxPath);
        $this->assertStringContainsString('listen 8080', $nginx);
        $this->assertStringContainsString('add_header X-Frame-Options "SAMEORIGIN" always;', $nginx);
        $this->assertStringContainsString('add_header X-Content-Type-Options "nosniff" always;', $nginx);
        $this->assertStringContainsString('add_header Referrer-Policy "strict-origin-when-cross-origin" always;', $nginx);
        $this->assertStringContainsString('add_header Permissions-Policy "camera=(), microphone=(), geolocation=()" always;', $nginx);
        $this->assertStringContainsString('location ^~ /build/', $nginx);
        $this->assertStringContainsString('location ~ /\.(?!well-known).*', $nginx);

        $entrypoint = (string) file_get_contents($entrypointPath);
        $this->assertStringContainsString('php artisan config:cache', $entrypoint);
        $this->assertStringContainsString('php artisan route:cache', $entrypoint);
        $this->assertStringContainsString('php artisan view:cache', $entrypoint);
        $this->assertStringContainsString('supervisord', $entrypoint);
    }

    public function test_github_actions_cicd_pipeline_and_production_env_template_exist_and_are_valid(): void
    {
        $workflowPath = base_path('.github/workflows/deploy.yml');
        $prodEnvPath = base_path('.env.production.example');

        $this->assertFileExists($workflowPath);
        $this->assertFileExists($prodEnvPath);

        $workflow = (string) file_get_contents($workflowPath);
        $this->assertStringContainsString('quality-and-tests:', $workflow);
        $this->assertStringContainsString('devsecops-container-scan:', $workflow);
        $this->assertStringContainsString('production-deploy-gate:', $workflow);
        $this->assertStringContainsString('aquasecurity/trivy-action', $workflow);
        $this->assertStringContainsString('php artisan test --ansi', $workflow);

        $prodEnv = (string) file_get_contents($prodEnvPath);
        $this->assertStringContainsString('APP_ENV=production', $prodEnv);
        $this->assertStringContainsString('APP_DEBUG=false', $prodEnv);
        $this->assertStringContainsString('SESSION_DRIVER=redis', $prodEnv);
        $this->assertStringContainsString('CACHE_STORE=redis', $prodEnv);
    }
}

