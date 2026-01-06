<?php
namespace console\controllers;

use yii\console\Controller;
use ReflectionClass;
use ReflectionMethod;
use Yii;

class RouteController extends Controller
{
    /**
     * List frontend-only routes
     */
    public function actionFrontend()
    {
        $this->stdout("=== Frontend Routes ===\n\n");

        $frontendPath = Yii::getAlias('@frontend');

        // 1. Base controllers
        $this->scanControllers($frontendPath . '/controllers', '', 'frontend\controllers');

        // 2. Frontend modules
        $modulesPath = $frontendPath . '/modules';
        if (is_dir($modulesPath)) {
            $this->scanModules($modulesPath, '');
        }
    }

    private function scanModules($path, $prefix)
    {
        $dirs = glob($path . '/*', GLOB_ONLYDIR);
        foreach ($dirs as $dir) {
            $moduleName = basename($dir);
            $controllerPath = $dir . '/controllers';
            $namespace = 'frontend\modules\\' . $moduleName . '\controllers';

            $newPrefix = $prefix ? $prefix . '/' . $moduleName : $moduleName;
            $this->scanControllers($controllerPath, $newPrefix, $namespace);

            // Recurse into nested modules
            if (is_dir($dir . '/modules')) {
                $this->scanModules($dir . '/modules', $newPrefix);
            }
        }
    }

    private function scanControllers($path, $prefix, $namespace)
    {
        if (!is_dir($path)) return;

        $files = glob($path . '/*Controller.php');
        foreach ($files as $file) {
            $className = basename($file, '.php');
            $fullClass = $namespace . '\\' . $className;

            if (!class_exists($fullClass)) continue;

            $this->scanControllerActions($fullClass, $prefix);
        }
    }

    private function scanControllerActions($controllerClass, $prefix)
    {
        $reflection = new ReflectionClass($controllerClass);
        $controllerId = strtolower(preg_replace('/Controller$/', '', $reflection->getShortName()));

        // Public action methods
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if (strpos($method->name, 'action') === 0) {
                $actionId = strtolower(substr($method->name, 6));
                $route = $prefix ? $prefix . '/' . $controllerId . '/' . $actionId
                                 : $controllerId . '/' . $actionId;
                $this->stdout($route . "\n");
            }
        }

        // Inline actions
        if (method_exists($controllerClass, 'actions')) {
            $controllerInstance = new $controllerClass($controllerClass, Yii::$app);
            foreach ($controllerInstance->actions() as $id => $config) {
                if (!is_string($id) || empty($id)) continue;
                $route = $prefix ? $prefix . '/' . $controllerId . '/' . $id
                                 : $controllerId . '/' . $id;
                $this->stdout($route . "\n");
            }
        }
    }
}
