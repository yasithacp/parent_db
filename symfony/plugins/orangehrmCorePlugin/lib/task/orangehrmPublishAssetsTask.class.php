<?php
/**
 * OrangeHRM Enterprise is a closed sourced comprehensive Human Resource Management (HRM)
 * System that captures all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM Inc is the owner of the patent, copyright, trade secrets, trademarks and any
 * other intellectual property rights which subsist in the Licensed Materials. OrangeHRM Inc
 * is the owner of the media / downloaded OrangeHRM Enterprise software files on which the
 * Licensed Materials are received. Title to the Licensed Materials and media shall remain
 * vested in OrangeHRM Inc. For the avoidance of doubt title and all intellectual property
 * rights to any design, new software, new protocol, new interface, enhancement, update,
 * derivative works, revised screen text or any other items that OrangeHRM Inc creates for
 * Customer shall remain vested in OrangeHRM Inc. Any rights not expressly granted herein are
 * reserved to OrangeHRM Inc.
 *
 * You should have received a copy of the OrangeHRM Enterprise  proprietary license file along
 * with this program; if not, write to the OrangeHRM Inc. 538 Teal Plaza, Secaucus , NJ 0709
 * to get the file.
 *
 */


class orangehrmPublishAssetsTask extends sfBaseTask {
    protected function configure() {
        $this->namespace = 'orangehrm';
        $this->name = 'publish-assets';

        $this->briefDescription = 'Publishes web assets of OrangeHRM plugins (copy -> no symlinks)';

        $this->detailedDescription = <<<EOF
The [plugin:publish-assets|INFO] Task will publish web assets of OrangeHRM plugins.

  [./symfony orangehrm:publish-assets|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        //only enabled plugins are here
        $plugins    = $this->configuration->getAllPluginPaths();

        //check for enabled plugins
        foreach ($this->configuration->getPlugins() as $plugin)
        if (stripos($plugin, 'orangehrm') !== FALSE) {
            $pluginPath = $plugins[$plugin];
            $this->logSection('plugin', 'Configuring plugin - ' . $pluginPath);
            $this->copyWebAssets($plugin, $pluginPath);
        }
    }

    private function copyWebAssets($plugin, $dir) {
        $webDir = $dir.DIRECTORY_SEPARATOR.'web';
        $filesystem = new sfFilesystem();
        
        if (is_dir($webDir)) {
            $finder = sfFinder::type('any');
            $this->dirctoryRecusiveDelete(sfConfig::get('sf_web_dir').DIRECTORY_SEPARATOR.$plugin);
            $filesystem->mirror($webDir, sfConfig::get('sf_web_dir').DIRECTORY_SEPARATOR.$plugin, $finder);
        }
        return;
    }

    private function dirctoryRecusiveDelete($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                 if (filetype($dir."/".$object) == "dir") $this->dirctoryRecusiveDelete($dir."/".$object); else unlink($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}
?>
