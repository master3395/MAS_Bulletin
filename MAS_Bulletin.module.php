<?php
/**
 * MAS Bulletin: homepage Breaking and Live tickers (admin-managed, optional marquee scroll).
 *
 * @package MAS_Bulletin
 * @author master3395
 */
if (!defined('CMS_VERSION')) {
    exit;
}

class MAS_Bulletin extends CMSModule
{
    public function GetName()
    {
        return 'MAS_Bulletin';
    }

    public function GetFriendlyName()
    {
        return $this->Lang('friendlyname');
    }

    public function GetVersion()
    {
        static $cachedVersion = null;
        if ($cachedVersion !== null) {
            return $cachedVersion;
        }
        $ini = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'moduleinfo.ini';
        if (is_readable($ini)) {
            $data = @parse_ini_file($ini, true, INI_SCANNER_RAW);
            if (is_array($data) && isset($data['module']['version'])) {
                $v = trim((string) $data['module']['version'], " \t\n\r\0\x0B\"'");
                if ($v !== '') {
                    $cachedVersion = $v;
                    return $cachedVersion;
                }
            }
        }
        $cachedVersion = '1.1.3';

        return $cachedVersion;
    }

    public function GetHelp()
    {
        require_once dirname(__DIR__) . "/MAS_Common/lib/mas_admin_ui.php";
        return Mas_Admin_Ui::fetchTabbedHelp($this);
    }

    public function GetAbout()
    {
        require_once dirname(__DIR__) . "/MAS_Common/lib/mas_admin_ui.php";
        return Mas_Admin_Ui::fetchTabbedAbout($this);
    }

    public function GetAuthor()
    {
        return 'master3395';
    }

    public function GetAuthorEmail()
    {
        return 'info [at] newstargeted [dot] com';
    }

    public function GetAuthorUrl()
    {
        return 'https://newstargeted.com/contact/';
    }

    public function GetChangeLog()
    {
        $base = realpath($this->GetModulePath());
        $file = realpath($this->GetModulePath() . DIRECTORY_SEPARATOR . 'CHANGELOG.md');
        if (!$base || !$file || !is_file($file) || !is_readable($file)) {
            return $this->Lang('changelog');
        }
        if (strpos($file, $base) !== 0 || basename($file) !== 'CHANGELOG.md') {
            return $this->Lang('changelog');
        }
        $md = @file_get_contents($file);
        if ($md === false || $md === '') {
            return $this->Lang('changelog');
        }

        return '<pre class="mas_bl_changelog_pre">' . cms_htmlentities($md) . '</pre>';
    }

    public function IsPluginModule()
    {
        return true;
    }

    public function HasAdmin()
    {
        return true;
    }

    /**
     * @return list<string>
     */
    public function GetAllowedAdminSectionKeys()
    {
        return ['main', 'content', 'layout', 'usersgroups', 'extensions', 'siteadmin', 'myprefs'];
    }

    public function GetAdminSection()
    {
        $v = (string) $this->GetPreference('mas_bl_admin_section', 'extensions');
        $allowed = $this->GetAllowedAdminSectionKeys();

        return in_array($v, $allowed, true) ? $v : 'extensions';
    }

    public function GetAdminDescription()
    {
        return $this->Lang('moddescription');
    }

    public function VisibleToAdminUser()
    {
        return $this->CheckPermission('Manage MAS_Bulletin')
            || $this->CheckPermission('Modify Site Preferences')
            || $this->CheckPermission('Modify Modules');
    }

    public function GetDependencies()
    {
        return [];
    }

    public function MinimumCMSVersion()
    {
        return '2.2.10';
    }

    public function GetMinimumPHPVersion()
    {
        return '7.4.0';
    }

    public function InitializeFrontend()
    {
        $this->RegisterModulePlugin(true, false);
        $this->RestrictUnknownParams();
        $this->SetParameterType('action', CLEAN_STRING);
    }

    public function InitializeAdmin()
    {
        $this->CreateParameter('action', 'default', $this->Lang('help_action'));
    }

    public function InstallPostMessage()
    {
        return $this->Lang('postinstall');
    }

    public function UninstallPostMessage()
    {
        return $this->Lang('postuninstall');
    }

    public function UninstallPreMessage()
    {
        return $this->Lang('really_uninstall');
    }

    public function ShowDonationsTab()
    {
        return ($this->GetPreference('hidedonationstab') !== $this->GetVersion());
    }
}
