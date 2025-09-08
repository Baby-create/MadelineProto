<?php
/**
 * MadelineProto Deployment Status Checker
 * 
 * This script provides comprehensive deployment status information
 * for the MadelineProto project.
 */

class DeploymentStatusChecker
{
    private $messages = [];
    private $status = 'success';

    public function __construct()
    {
        // Multi-language support
        $this->messages = [
            'en' => [
                'checking' => 'Checking deployment status...',
                'php_version' => 'PHP Version Check',
                'composer_check' => 'Composer Dependencies Check',
                'file_structure' => 'Project File Structure Check',
                'permissions' => 'Permissions Check',
                'success' => 'SUCCESS',
                'warning' => 'WARNING',
                'error' => 'ERROR',
                'status_ok' => 'Deployment Status: OK',
                'status_issues' => 'Deployment Status: Issues Found',
                'vendor_missing' => 'Vendor directory not found - dependencies need to be installed',
                'composer_install' => 'Run: composer install',
                'php_ok' => 'PHP version is compatible',
                'project_files' => 'Essential project files found',
                'ready_to_use' => 'Project is ready to use',
                'need_setup' => 'Project needs setup',
            ],
            'zh' => [
                'checking' => '正在检查部署状态...',
                'php_version' => 'PHP版本检查',
                'composer_check' => 'Composer依赖检查',
                'file_structure' => '项目文件结构检查',
                'permissions' => '权限检查',
                'success' => '成功',
                'warning' => '警告',
                'error' => '错误',
                'status_ok' => '部署状态：正常',
                'status_issues' => '部署状态：发现问题',
                'vendor_missing' => '未找到vendor目录 - 需要安装依赖',
                'composer_install' => '运行：composer install',
                'php_ok' => 'PHP版本兼容',
                'project_files' => '找到必要的项目文件',
                'ready_to_use' => '项目可以使用',
                'need_setup' => '项目需要设置',
            ]
        ];
    }

    public function check($lang = 'en')
    {
        if (!isset($this->messages[$lang])) {
            $lang = 'en';
        }
        
        $msgs = $this->messages[$lang];
        
        echo "\n" . str_repeat('=', 60) . "\n";
        echo "MadelineProto " . $msgs['checking'] . "\n";
        echo str_repeat('=', 60) . "\n\n";

        // Check PHP version
        $this->checkPhpVersion($msgs);
        
        // Check project structure
        $this->checkProjectStructure($msgs);
        
        // Check Composer dependencies
        $this->checkComposerDependencies($msgs);
        
        // Check permissions
        $this->checkPermissions($msgs);
        
        // Display final status
        $this->displayFinalStatus($msgs);
    }

    private function checkPhpVersion($msgs)
    {
        echo "📋 " . $msgs['php_version'] . "\n";
        echo str_repeat('-', 40) . "\n";
        
        $version = PHP_VERSION;
        $required = '8.2.0';
        
        if (version_compare($version, $required, '>=')) {
            echo "[✅ " . $msgs['success'] . "] " . $msgs['php_ok'] . " (v{$version})\n";
        } else {
            echo "[❌ " . $msgs['error'] . "] PHP {$required}+ required, found {$version}\n";
            $this->status = 'error';
        }
        
        // Check required extensions
        $required_extensions = ['json', 'mbstring', 'xml', 'fileinfo'];
        foreach ($required_extensions as $ext) {
            if (extension_loaded($ext)) {
                echo "[✅ " . $msgs['success'] . "] Extension: {$ext}\n";
            } else {
                echo "[⚠️  " . $msgs['warning'] . "] Missing extension: {$ext}\n";
                $this->status = 'warning';
            }
        }
        echo "\n";
    }

    private function checkProjectStructure($msgs)
    {
        echo "📁 " . $msgs['file_structure'] . "\n";
        echo str_repeat('-', 40) . "\n";
        
        $essential_files = [
            'composer.json' => 'Composer configuration',
            'src/' => 'Source directory', 
            'README.md' => 'Documentation',
            'examples/' => 'Example files'
        ];
        
        foreach ($essential_files as $file => $description) {
            if (file_exists($file) || is_dir($file)) {
                echo "[✅ " . $msgs['success'] . "] {$description}: {$file}\n";
            } else {
                echo "[❌ " . $msgs['error'] . "] Missing: {$file}\n";
                $this->status = 'error';
            }
        }
        echo "\n";
    }

    private function checkComposerDependencies($msgs)
    {
        echo "📦 " . $msgs['composer_check'] . "\n";
        echo str_repeat('-', 40) . "\n";
        
        if (is_dir('vendor/')) {
            echo "[✅ " . $msgs['success'] . "] Dependencies installed\n";
            
            // Check if autoloader exists
            if (file_exists('vendor/autoload.php')) {
                echo "[✅ " . $msgs['success'] . "] Autoloader available\n";
            } else {
                echo "[⚠️  " . $msgs['warning'] . "] Autoloader missing\n";
                $this->status = 'warning';
            }
            
            // Check some key dependencies
            $key_packages = [
                'vendor/amphp/',
                'vendor/danog/',
                'vendor/revolt/'
            ];
            
            foreach ($key_packages as $pkg) {
                if (is_dir($pkg)) {
                    echo "[✅ " . $msgs['success'] . "] Package: " . basename($pkg) . "\n";
                } else {
                    echo "[⚠️  " . $msgs['warning'] . "] Missing package: " . basename($pkg) . "\n";
                    $this->status = 'warning';
                }
            }
            
        } else {
            echo "[❌ " . $msgs['error'] . "] " . $msgs['vendor_missing'] . "\n";
            echo "   💡 " . $msgs['composer_install'] . "\n";
            $this->status = 'error';
        }
        echo "\n";
    }

    private function checkPermissions($msgs)
    {
        echo "🔒 " . $msgs['permissions'] . "\n";
        echo str_repeat('-', 40) . "\n";
        
        $paths_to_check = ['.', 'src/', 'examples/'];
        
        foreach ($paths_to_check as $path) {
            if (is_readable($path)) {
                echo "[✅ " . $msgs['success'] . "] Readable: {$path}\n";
            } else {
                echo "[❌ " . $msgs['error'] . "] Not readable: {$path}\n";
                $this->status = 'error';
            }
        }
        echo "\n";
    }

    private function displayFinalStatus($msgs)
    {
        echo str_repeat('=', 60) . "\n";
        
        switch ($this->status) {
            case 'success':
                echo "🎉 " . $msgs['status_ok'] . "\n";
                echo "✨ " . $msgs['ready_to_use'] . "\n";
                break;
            case 'warning':
                echo "⚠️  " . $msgs['status_issues'] . "\n";  
                echo "🔧 " . $msgs['ready_to_use'] . " (with minor issues)\n";
                break;
            case 'error':
                echo "❌ " . $msgs['status_issues'] . "\n";
                echo "🛠️  " . $msgs['need_setup'] . "\n";
                break;
        }
        
        echo str_repeat('=', 60) . "\n";
        
        // Additional info
        echo "\n📝 Additional Information:\n";
        echo "   • Project: MadelineProto - PHP MTProto Telegram Client\n";
        echo "   • Author: Daniil Gentili\n";
        echo "   • Documentation: https://docs.madelineproto.xyz\n";
        echo "   • GitHub: https://github.com/danog/MadelineProto\n\n";
    }

    public function getJsonStatus()
    {
        $vendor_exists = is_dir('vendor/');
        $autoload_exists = file_exists('vendor/autoload.php');
        
        return json_encode([
            'deployment_status' => $this->status,
            'php_version' => PHP_VERSION,
            'php_compatible' => version_compare(PHP_VERSION, '8.2.0', '>='),
            'dependencies_installed' => $vendor_exists,
            'autoloader_available' => $autoload_exists,
            'project_ready' => $vendor_exists && $autoload_exists,
            'timestamp' => date('Y-m-d H:i:s'),
            'checks_passed' => $this->status === 'success'
        ], JSON_PRETTY_PRINT);
    }
}

// CLI usage
if (php_sapi_name() === 'cli') {
    $checker = new DeploymentStatusChecker();
    
    // Get language from command line argument
    $lang = isset($argv[1]) ? $argv[1] : 'en';
    
    // Support for Chinese input
    if (in_array($lang, ['zh', 'cn', 'chinese', '中文'])) {
        $lang = 'zh';
    }
    
    if (isset($argv[1]) && $argv[1] === 'json') {
        echo $checker->getJsonStatus();
    } else {
        $checker->check($lang);
    }
}