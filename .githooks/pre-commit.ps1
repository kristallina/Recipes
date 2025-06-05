Write-Host "Running PHPStan checks..." -ForegroundColor Cyan

function Run-PHPStan {
    param($service)
    
    Write-Host "`nChecking $service..." -ForegroundColor Yellow
    
    # Проверка установки PHPStan
    $installed = docker-compose exec -T $service sh -c "[ -f /var/www/html/vendor/bin/phpstan ] && echo '1' || echo '0'"
    
    if ($installed -eq '0') {
        Write-Host "PHPStan not installed in $service! Installing..." -ForegroundColor Yellow
        docker-compose exec $service composer require --dev phpstan/phpstan --working-dir=/var/www/html
        if ($LASTEXITCODE -ne 0) {
            Write-Host "Failed to install PHPStan in $service!" -ForegroundColor Red
            return $false
        }
    }
    
    # Проверка наличия baseline
    $hasBaseline = docker-compose exec -T $service sh -c "[ -f /var/www/html/phpstan-baseline.neon ] && echo '1' || echo '0'"
    
    if ($hasBaseline -eq '0') {
        Write-Host "Generating baseline for $service..." -ForegroundColor Cyan
        docker-compose exec $service sh -c "cd /var/www/html && php vendor/bin/phpstan analyse --generate-baseline"
        if ($LASTEXITCODE -ne 0) {
            Write-Host "Failed to generate baseline for $service!" -ForegroundColor Red
            return $false
        }
    }
    
    # Основная проверка
    docker-compose exec -T $service sh -c "cd /var/www/html && php vendor/bin/phpstan analyse"
    
    return $LASTEXITCODE -eq 0
}

$ingredientPassed = Run-PHPStan "ingredient-service"
$recipePassed = Run-PHPStan "recipe-service"

if (-not ($ingredientPassed -and $recipePassed)) {
    Write-Host "`nPHPStan found issues!" -ForegroundColor Red
    Write-Host "To manually check:" -ForegroundColor Yellow
    Write-Host "  ingredient-service: docker-compose exec ingredient-service php vendor/bin/phpstan analyse"
    Write-Host "  recipe-service: docker-compose exec recipe-service php vendor/bin/phpstan analyse"
    exit 1
}

Write-Host "`nAll PHPStan checks passed!" -ForegroundColor Green
exit 0
