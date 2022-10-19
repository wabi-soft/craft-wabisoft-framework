<?php

namespace wabisoft\framework\services;
use Craft;
use craft\helpers\StringHelper;
use craft\web\View;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use yii\base\Exception;

class TemplateLoader
{
    public static function load($templates, $variables = []) {
        if(count($templates) == 0) {
            return false;
        }
        $clean = [];
        foreach($templates as $template) {
            $clean[] = self::cleanPath($template);
        }
        $templates = $clean;
        $isDev = Craft::$app->getConfig()->general->devMode;
        foreach($templates as $template) {
            $content = self::renderIfTemplate($template, $variables);
            if($content && !$isDev) {
                return $content;
            }
            if($content && $isDev) {
                return self::wrapInDev($content, $template, $templates);
            }
        }
        return false;
    }

    private static function renderIfTemplate($template, $variables) {
        try {
            if (Craft::$app->view->doesTemplateExist($template)) {
                return Craft::$app->view->renderTemplate($template, $variables);
            }
        } catch (LoaderError|RuntimeError|SyntaxError|Exception $e) {
            return false;
        }
        return false;
    }

    private static function cleanPath($path) {
        $path = StringHelper::trim($path);
        return str_replace('//', '/', $path);
    }

    private static function wrapInDev($content, $template, $templates) {
        $showPath = Craft::$app->request->getParam('show-template-path');
        $showHierarchy = Craft::$app->request->getParam('show-template-hierarchy');
        if(!isset($showPath) && !isset($showHierarchy)) {
            return $content;
        }
        if(isset($showPath)) {
             return Craft::$app->view->renderTemplate('wabisoft-framework/dev/template-path', ['template'=> $template, 'content' => $content], View::TEMPLATE_MODE_CP);
        }
        if(isset($showHierarchy)) {
            return Craft::$app->view->renderTemplate('wabisoft-framework/dev/template-hierarchy', ['templates'=> $templates, 'content' => $content], View::TEMPLATE_MODE_CP);
        }

    }
}
