<?php

namespace Dino\Theme;

use Closure;
use Dino\Base\Facades\BaseHelper;
use Dino\Theme\Contracts\Theme as ThemeContract;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\View\Factory;
use Symfony\Component\HttpFoundation\Cookie;

class Theme implements ThemeContract
{
    public static string $namespace = 'theme';
    protected string|null $theme = null;
    protected array $themeConfig = [];
    protected string $layout;
    protected string $content;
    protected array $arguments = [];
    protected array $regions = [];
    protected ?Cookie $cookie = null;

    public function __construct(
        protected Repository $config,
        protected Factory $view,
        protected Asset $asset,
        protected Filesystem $files,
    ) {
        $this->uses($this->getThemeName())->layout(setting('layout', 'default'));
    }

    public function layout(string $layout): self
    {
        // If layout name is not set, so use default from config.
        if ($layout) {
            $this->layout = $layout;
        }

        return $this;
    }

    /**
     * Alias of theme method.
     */
    public function uses(string|null $theme = null): self
    {
        return $this->theme($theme);
    }

    public function theme(string|null $theme = null): self
    {
        // If theme name is not set, so use default from config.
        if ($theme) {
            $this->theme = $theme;
        }

        // Is theme ready?
        // if (! $this->exists($theme) && ! app()->runningInConsole()) {
        //     throw new UnknownThemeException('Theme [' . $theme . '] not found.');
        // }

        // Add location to look up view.
        $this->addPathLocation($this->path());

        // Fire event before set up a theme.
        // $this->fire('before', $this);

        // Before from a public theme config.
        // $this->fire('appendBefore', $this);

        $assetPath = $this->getThemeAssetsPath();

        // Add asset path to asset container.
        $this->asset->addPath($assetPath . '/' . $this->getConfig('containerDir.asset'));

        return $this;
    }

    protected function getThemeAssetsPath(): string
    {
        $publicThemeName = $this->getPublicThemeName();

        $currentTheme = $this->getThemeName();

        $assetPath = $this->path();

        if ($publicThemeName != $currentTheme) {
            $assetPath = substr($assetPath, 0, -strlen($currentTheme)) . $publicThemeName;
        }

        return $assetPath;
    }

    public function getPublicThemeName(): string
    {
        $theme = $this->getThemeName();

        $publicThemeName = $this->getConfig('public_theme_name');

        if ($publicThemeName && $publicThemeName != $theme) {
            return $publicThemeName;
        }

        return $theme;
    }

    public function exists(string|null $theme): bool
    {
        $path = cms_path($this->path($theme)) . '/';

        return File::isDirectory($path);
    }

    public function path(string|null $forceThemeName = null): string
    {
        $themeDir = $this->getConfig('themeDir');

        $theme = $forceThemeName ?: $this->theme;

        return $themeDir . '/' . $theme;
    }

    public function getConfig(string|null $key = null): mixed
    {
        // Main package config.
        if (!$this->themeConfig) {
            $this->themeConfig = $this->config->get('core.theme.general', []);
        }

        // Config inside a public theme.
        // This config having buffer by array object.
        if ($this->theme && !isset($this->themeConfig['themes'][$this->theme])) {
            $this->themeConfig['themes'][$this->theme] = [];

            // Require public theme config.
            $minorConfigPath = theme_path($this->theme . '/config.php');

            if ($this->files->exists($minorConfigPath)) {
                $this->themeConfig['themes'][$this->theme] = $this->files->getRequire($minorConfigPath);
            }
        }

        // Evaluate theme config.
        $this->themeConfig = $this->evaluateConfig($this->themeConfig);

        return empty($key) ? $this->themeConfig : Arr::get($this->themeConfig, $key);
    }
    /**
     * Evaluate config.
     *
     * Config minor is at public folder [theme]/config.php,
     * they can be overridden package config.
     */
    protected function evaluateConfig(array $config): array
    {
        if (!isset($config['themes'][$this->theme])) {
            return $config;
        }

        // Config inside a public theme.
        $minorConfig = $config['themes'][$this->theme];

        // Before event is special case, It's combination.
        if (isset($minorConfig['events']['before'])) {
            $minorConfig['events']['appendBefore'] = $minorConfig['events']['before'];
            unset($minorConfig['events']['before']);
        }

        // Merge two config into one.
        $config = array_replace_recursive($config, $minorConfig);

        // Reset theme config.
        $config['themes'][$this->theme] = [];

        return $config;
    }

    /**
     * Add location path to look up.
     */
    protected function addPathLocation(string $location): void
    {
        // First path is in the selected theme.
        $hints[] = cms_path($location);

        // This is nice feature to use inherit from another.
        if ($this->getConfig('inherit')) {
            // Inherit from theme name.
            $inherit = $this->getConfig('inherit');

            // Inherit theme path.
            $inheritPath = cms_path($this->path($inherit));

            if ($this->files->isDirectory($inheritPath)) {
                $hints[] = $inheritPath;
            }
        }

        // Add namespace with hinting paths.
        $this->view->addNamespace($this->getThemeNamespace(), $hints);
    }

    public function getThemeNamespace(string $path = ''): string
    {
        // Namespace relate with the theme name.
        $namespace = static::$namespace . '.' . $this->getThemeName();

        if ($path) {
            return $namespace . '::' . $path;
        }

        return $namespace;
    }
    public function getThemeName(): string
    {
        if ($this->theme) {
            return $this->theme;
        }

        $theme = setting('theme');

        if ($theme) {
            return $theme;
        }

        return Arr::first(BaseHelper::scanFolder(theme_path()));
    }

    public function setThemeName(string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function routes(): void
    {
        require cms_path('core/theme/routes/public.php');
    }

    /**
     * Container view.
     *
     * Using a container module view inside a theme, this is
     * useful when you separate a view inside a theme.
     */
    public function scope(string $view, array $args = [], $default = null)
    {
        $viewDir = $this->getConfig('containerDir.view');

        // Add namespace to find in a theme path.
        $path = $this->getThemeNamespace($viewDir . '.' . $view);

        if ($this->view->exists($path)) {
            return $this->setUpContent($path, $args);
        }

        if (!empty($default)) {
            return $this->of($default, $args);
        }

        $this->handleViewNotFound($path);
    }

    /**
     * Set up a content to template.
     */
    public function of(string $view, array $args = []): self
    {
        // $this->fireEventGlobalAssets();

        // Keeping arguments.
        $this->arguments = $args;

        $content = $this->view->make($view, $args)->render();

        // View path of content.
        $this->content = $view;

        // Set up a content regional.
        $this->regions['content'] = $content;

        return $this;
    }

    /**
     * Set up a content to template.
     */
    public function setUpContent(string $view, array $args = []): self
    {
        // $this->fireEventGlobalAssets();

        // Keeping arguments.
        $this->arguments = $args;

        $content = $this->view->make($view, $args)->render();

        // View path of content.
        $this->content = $view;

        // Set up a content regional.
        $this->regions['content'] = $content;

        return $this;
    }

    protected function handleViewNotFound(string $path): void
    {
        if (app()->isLocal() && app()->hasDebugModeEnabled()) {
            $path = str_replace($this->getThemeNamespace(), $this->getThemeName(), $path);
            $file = str_replace('::', '/', str_replace('.', '/', $path));
            dd(
                'This theme has not supported this view, please create file "' . theme_path(
                    $file
                ) . '.blade.php" to render this page!'
            );
        }

        abort(404);
    }

    /**
     * Fire event to config listener.
     */
    public function fire(string $event, string|array|callable|null|object $args): void
    {
        $onEvent = $this->getConfig('events.' . $event);

        if ($onEvent instanceof Closure) {
            $onEvent($args);
        }
    }

    /**
     * Return a template with content.
     */
    public function render(int $statusCode = 200): Response
    {
        // Fire the event before render.
        $this->fire('after', $this);

        // Flush asset that need to serve.
        $this->asset->flush();

        // Layout directory.
        $layoutDir = $this->getConfig('containerDir.layout');

        $path = $this->getThemeNamespace($layoutDir . '.' . $this->layout);

        if (!$this->view->exists($path)) {
            $this->handleViewNotFound($path);
        }

        $content = $this->view->make($path)->render();

        // Append status code to view.
        $content = new Response($content, $statusCode);

        // Having cookie set.
        if ($this->cookie) {
            $content->withCookie($this->cookie);
        }

        $content->withHeaders([
            'CMS-Version' => get_core_version()
        ]);

        return $content;
    }

    /**
     * Set up a partial.
     */
    public function partial(string $view, array $args = []): string|null
    {
        $partialDir = $this->getThemeNamespace($this->getConfig('containerDir.partial'));

        return $this->loadPartial($view, $partialDir, $args);
    }

    /**
     * Load a partial
     */
    public function loadPartial(string $view, string $partialDir, array $args): string|null
    {
        $path = $partialDir . '.' . $view;

        // if (!$this->view->exists($path)) {
        //     throw new UnknownPartialFileException('Partial view [' . $view . '] not found.');
        // }

        $partial = $this->view->make($path, $args)->render();
        $this->regions[$view] = $partial;

        return $this->regions[$view];
    }

    /**
     * Place content in sub-view.
     */
    public function content(): string|null
    {
        return $this->regions['content'];
    }

    public function getThemeScreenshot(string $theme): string
    {
        $publicThemeName = Theme::getPublicThemeName();

        $themeName = Theme::getThemeName() == $theme && $publicThemeName ? $publicThemeName : $theme;

        $screenshot = public_path($this->getConfig('themeDir') . '/' . $themeName . '/screenshot.png');

        if (!File::exists($screenshot)) {
            $screenshot = theme_path($theme . '/screenshot.png');
        }

        if (!File::exists($screenshot)) {
            $screenshot = public_path('cms/images/theme-screenshot.png');
        }

        return 'data:image/png;base64,' . base64_encode(File::get($screenshot));
    }
}
