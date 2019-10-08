<?php

namespace Ultraleet\WP;

class RequirementsChecker
{
    private $title = '';
    private $php = '7.2.0';
    private $wp = '4.9';
    private $file;

    /**
     * RequirementsChecker constructor.
     *
     * @param $args {
     *      @type string $title Title of your plugin.
     *      @type string $php Minimum required PHP version for your plugin.
     *      @type string $wp Minimum required WP version for your plugin.
     *      @type string $file Path to your plugin's main file.
     * }
     */
    public function __construct($args)
    {
        foreach (['title', 'php', 'wp', 'file'] as $setting) {
            if (isset($args[$setting])) {
                $this->$setting = $args[$setting];
            }
        }
    }

    /**
     * Check if all requirements are met.
     *
     * In case requirements are not met, displays admin notice(s) about mismatched version(s).
     * Check for the return value and only continue loading files that depend on given minimum versions
     * when this method returns true.
     *
     * @return bool
     */
    public function passes()
    {
        $passes = $this->phpPasses() && $this->wpPasses();
        if (!$passes) {
            add_action('admin_notices', [$this, 'deactivate']);
        }
        return $passes;
    }

    /**
     * Deactivate the plugin when requirements are not met.
     */
    public function deactivate()
    {
        deactivate_plugins(plugin_basename($this->file));
    }

    /**
     * Check for PHP version.
     *
     * @return bool
     */
    protected function phpPasses()
    {
        if (self::isVersionAtLeast(phpversion(), $this->php)) {
            return true;
        } else {
            add_action('admin_notices', [$this, 'phpVersionNotice']);
            return false;
        }
    }

    /**
     * Display notice when PHP version requirement is not met.
     */
    public function phpVersionNotice()
    {
        echo '<div class="error">';
        echo "<p>The &#8220;" . esc_html(
                $this->title
            ) . "&#8221; plugin cannot run on PHP versions older than " . $this->php . '. Please contact your host and ask them to upgrade.</p>';
        echo '</div>';
    }

    /**
     * Check for WordPress version.
     *
     * @return bool
     */
    private function wpPasses()
    {
        if (self::isVersionAtLeast(get_bloginfo('version'), $this->wp)) {
            return true;
        } else {
            add_action('admin_notices', [$this, 'wpVersionNotice']);
            return false;
        }
    }

    /**
     * Display notice when WordPress version requirement is not met.
     */
    public function wpVersionNotice()
    {
        echo '<div class="error">';
        echo "<p>The &#8220;" . esc_html(
                $this->title
            ) . "&#8221; plugin cannot run on WordPress versions older than " . $this->wp . '. Please update WordPress.</p>';
        echo '</div>';
    }

    /**
     * Check if current version is at least the required version.
     *
     * @param string $currentVersion
     * @param string $requiredVersion
     * @return mixed
     */
    public static function isVersionAtLeast($currentVersion, $requiredVersion)
    {
        return version_compare($currentVersion, $requiredVersion, '>=');
    }
}
