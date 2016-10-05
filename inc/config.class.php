<?php
/*
 * @version $Id$
 LICENSE

  This file is part of the openvas plugin.

 Order plugin is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 openvas plugin is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; along with openvas. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 @package   openvas
 @author    Teclib'
 @copyright Copyright (c) 2016 Teclib'
 @license   GPLv2+
            http://www.gnu.org/licenses/gpl.txt
 @link      https://github.com/pluginsglpi/openvas
 @link      http://www.glpi-project.org/
 @link      http://www.teclib-edition.com/
 @since     2016
 ---------------------------------------------------------------------- */

if (!defined('GLPI_ROOT')){
   die("Sorry. You can't access directly to this file");
}

class PluginOpenvasConfig extends CommonDBTM {
   static $rightname = 'config';

   public static function getTypeName($nb = 0) {
      return __("GLPi openvas Connector", 'openvas');
   }

   public function showForm() {
      $this->getFromDB(1);

      echo "<div class='center'>";
      echo "<form name='form' method='post' action='" . $this->getFormURL() . "'>";

      echo "<input type='hidden' name='id' value='1'>";

      echo "<table class='tab_cadre_fixe'>";

      echo "<tr><th colspan='4'>" . __("Plugin configuration", "openvas") . "</th></tr>";

      echo "<tr class='tab_bg_1' align='center'>";
      echo "<td>" . __("Host", "openvas") . "</td>";
      echo "<td>";
      Html::autocompletionTextField($this, "openvas_host");
      echo "</td>";
      echo "<td>" . __("Port", "openvas") . "</td>";
      echo "<td>";
      Html::autocompletionTextField($this, "openvas_port");
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1' align='center'>";
      echo "<td>" . _n("User", "Users", 1) . "</td>";
      echo "<td>";
      Html::autocompletionTextField($this, "openvas_username");
      echo "</td>";
      echo "<td>" . __("Password") . "</td>";
      echo "<td><input type='password' name='openvas_password' value='".$this->fields['openvas_password']."'>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1' align='center'>";
      echo "<td>" . __("Path to omp", "openvas") . "</td>";
      echo "<td>";
      Html::autocompletionTextField($this, "openvas_omp_path");
      echo "</td>";
      echo "<td colspan='2'>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1' align='center'>";
      echo "<td colspan='4' align='center'>";
      echo "<input type='submit' name='update' value=\"" . _sx("button", "Post") . "\" class='submit' >";
      echo "&nbsp<input type='submit' name='test' value=\"" . _sx("button", "Test connexion") . "\" class='submit' >";
      echo"</td>";
      echo "</tr>";

      echo "</table>";
      Html::closeForm();
      echo "</div>";
   }


   //----------------- Install & uninstall -------------------//
   public static function install(Migration $migration) {
      global $DB;

      //This class is available since version 1.3.0
      if (!TableExists("glpi_plugin_openvas_configs")) {
         $migration->displayMessage("Install glpi_plugin_openvas_configs");

         $config = new self();

         //Install
         $query = "CREATE TABLE `glpi_plugin_openvas_configs` (
                     `id` int(11) NOT NULL auto_increment,
                     `openvas_host` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
                     `openvas_port` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
                     `openvas_username` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
                     `openvas_password` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
                     `openvas_omp_path` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
                     `retention_delay` int(11) NOT NULL DEFAULT '0',
                     PRIMARY KEY  (`id`),
                     KEY `openvas_host` (`openvas_host`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
         $DB->query($query) or die ($DB->error());

         $tmp = array('id'                  => 1,
                      'fusioninventory_url' => 'localhost',
                      'openvas_port'        => '9390',
                      'openvas_username'    => 'admin',
                      'openvas_password'    => '',
                      'openvas_omp_path'    => '/usr/bin/omp',
                      'retention_delay'     => 30);
         $config->add($tmp);
      }
   }

   public static function uninstall() {
      global $DB;
      $DB->query("DROP TABLE IF EXISTS `glpi_plugin_openvas_configs`");
   }
}
