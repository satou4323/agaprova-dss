Write-Host "========================================" -ForegroundColor Cyan
Write-Host " DIAGNOSTICO DSS AGAPROVA" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# 1. Revisar config
Write-Host "[1] CONFIGURACION ACTUAL (config/config.php):" -ForegroundColor Yellow
$configPath = "$PSScriptRoot\config\config.php"
if (Test-Path $configPath) {
    Select-String -Path $configPath -Pattern "define\('(BASE_URL|DB_HOST|DB_USER|DB_PASS|DB_NAME|DB_PORT)"
} else {
    Write-Host "  NO ENCONTRADO" -ForegroundColor Red
}
Write-Host ""

# 2. Revisar .htaccess
Write-Host "[2] REWRITEBASE (.htaccess):" -ForegroundColor Yellow
$htaccessPath = "$PSScriptRoot\.htaccess"
if (Test-Path $htaccessPath) {
    Select-String -Path $htaccessPath -Pattern "RewriteBase"
} else {
    Write-Host "  NO ENCONTRADO" -ForegroundColor Red
}
Write-Host ""

# 3. Probar conexion MySQL
Write-Host "[3] TEST CONEXION MYSQL:" -ForegroundColor Yellow
try {
    $dbHost = "localhost"
    $dbPort = 3306
    $dbUser = "root"
    $dbPass = ""
    $dbName = "dss_agaprova"
    
    # Intentar diferentes combinaciones
    $tests = @(
        @{Host="localhost"; Port=3306; User="root"; Pass=""; DB="dss_agaprova"},
        @{Host="localhost"; Port=3307; User="root"; Pass=""; DB="dss_agaprova"},
        @{Host="localhost"; Port=3306; User="root"; Pass=""; DB="dss_agaprova"},
        @{Host="localhost"; Port=3306; User="root"; Pass="root"; DB="dss_agaprova"},
        @{Host="127.0.0.1"; Port=3306; User="root"; Pass=""; DB="dss_agaprova"}
    )
    
    $connected = $false
    foreach ($t in $tests) {
        try {
            $connStr = "Server=$($t.Host);Port=$($t.Port);Uid=$($t.User);Pwd=$($t.Pass);"
            $conn = New-Object MySql.Data.MySqlClient.MySqlConnection($connStr)
            $conn.Open()
            Write-Host "  OK - Host:$($t.Host) Puerto:$($t.Port) User:$($t.User)" -ForegroundColor Green
            $conn.Close()
            $connected = $true
            break
        } catch {
            Write-Host "  FAIL - Host:$($t.Host) Puerto:$($t.Port) User:$($t.User) -> $($_.Exception.Message)" -ForegroundColor Red
        }
    }
    
    if (-not $connected) {
        Write-Host "  => NO SE PUDO CONECTAR A MYSQL" -ForegroundColor Red
    }
} catch {
    Write-Host "  ERROR: No se pudo probar conexion" -ForegroundColor Red
    Write-Host "  Instala MySQL .NET Connector o usa: mysql -u root -p" -ForegroundColor Yellow
}
Write-Host ""

# 4. Verificar si existe el archivo de logs
Write-Host "[4] LOG DE ERRORES:" -ForegroundColor Yellow
$logPath = "$PSScriptRoot\logs\errors.log"
if (Test-Path $logPath) {
    $lines = Get-Content $logPath -Tail 10
    Write-Host "  Ultimas 10 lineas:" -ForegroundColor Yellow
    foreach ($line in $lines) { Write-Host "  $line" }
} else {
    Write-Host "  NO HAY LOG AUN" -ForegroundColor Gray
}
Write-Host ""

Write-Host "========================================" -ForegroundColor Cyan
Write-Host " COPIA TODO ESTO Y MANDALO" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
