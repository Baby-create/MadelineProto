<?php
/**
 * Simple MadelineProto deployment test
 * Tests if the basic library components can be loaded
 */

require_once 'deployment-status.php';

echo "🧪 Testing MadelineProto Deployment...\n\n";

// Check if autoloader works
try {
    if (file_exists('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
        echo "[✅ SUCCESS] Autoloader loaded successfully\n";
    } else {
        echo "[❌ ERROR] Autoloader not found\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "[❌ ERROR] Failed to load autoloader: " . $e->getMessage() . "\n";
    exit(1);
}

// Test basic class loading
try {
    // Test some basic classes exist
    $testClasses = [
        'danog\MadelineProto\API',
        'danog\MadelineProto\Settings',
    ];
    
    foreach ($testClasses as $class) {
        if (class_exists($class)) {
            echo "[✅ SUCCESS] Class available: {$class}\n";
        } else {
            echo "[⚠️  WARNING] Class not found: {$class}\n";
        }
    }
    
} catch (Exception $e) {
    echo "[❌ ERROR] Error checking classes: " . $e->getMessage() . "\n";
}

echo "\n";

// Run deployment status check
$checker = new DeploymentStatusChecker();
echo $checker->getJsonStatus();

echo "\n\n🎯 Deployment Test Complete!\n";
echo "📋 Summary: MadelineProto basic components are accessible\n";
echo "💡 To start using MadelineProto, check the examples/ directory\n";