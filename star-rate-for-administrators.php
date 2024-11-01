<?php
/*
  Plugin Name: Star Rate for Administrators
  Description: Star Rate for Administrators is an open source solution built for Wordpress to display several star rates puts for the administrator.
  Version: 1.0.0
  Author: Javier Glez
  Author URI: http://javierglez.es/
  Plugin URI: http://javierglez.es/
 */

// Definición de Constantes
if ( ! defined( 'SRA_PLUGIN' ) )
	define( 'SRA_PLUGIN', plugin_basename(__FILE__));

if ( ! defined( 'SRA_STAR_URL' ) )
	define( 'SRA_STAR_URL', plugins_url( 'img/stars/' , __FILE__) );

//
$aStars = array();
require_once 'inc/stars.php';

function sraMenuPlugin(){
   add_options_page( __("Star Rate Settings", 'star-rate-for-admin'), __("Star Rate for Admin", 'star-rate-for-admin'), 10, "star_rate_settings", "sraShowSettings");
}

function sraShowSettings(){
    if (!current_user_can('manage_options')){
        wp_die( __('Small padawan ... you must use the force to enter here.', 'star-rate-for-admin') );
    }
    
    global $aStars;
 
    // Guardado de los datos
    if( isset($_POST[ "sra_submit" ])) { sraSave(); }
    
    // Inicialización de los Valores
    $opt_sra_range = get_option("sra_range", "5");
    $opt_sra_colored = get_option("sra_colored", "0");
    $opt_sra_rounded = get_option("sra_rounded", "0");
    $opt_sra_type = get_option("sra_type", "OS");
    
    // Visualización de la parte del Administrador
    ?>
        <div class="wrap">
            <div style="width: 70%; float: left;">
                <div class="icon32" id="icon-options-general"><br></div>
                <h2><?php _e( 'Star Rate for Administrators', 'star-rate-for-admin' ); ?></h2>
                <form name="sra_form" method="post" action="">
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row"><?php _e( 'Number of stars', 'star-rate-for-admin' ); ?></th>
                                <td>
                                    <fieldset>
                                        <legend class="screen-reader-text"><span><?php _e( 'Number of stars', 'star-rate-for-admin' ); ?></span></legend>
                                        <label title="<?php _e('From 0 to 5 stars', 'star-rate-for-admin' ); ?>"><input type="radio" name="sra_range" value="5" <?php if($opt_sra_range == "5"){ echo "checked "; }?>/> <span><?php _e('From 0 to 5 stars', 'star-rate-for-admin' ); ?></span></label><br>
                                        <label title="<?php _e('From 0 to 10 stars', 'star-rate-for-admin' ); ?>"><input type="radio" name="sra_range" value="10" <?php if($opt_sra_range == "10"){ echo "checked "; }?>/> <span><?php _e('From 0 to 10 stars', 'star-rate-for-admin' ); ?></span></label><br>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><label for="sra_colored"><?php _e( 'Show only colored', 'star-rate-for-admin' ); ?></label></th>
                                <td>
                                    <fieldset>
                                        <legend class="screen-reader-text"><span><?php _e( 'Show only colored', 'star-rate-for-admin' ); ?></span></legend>
                                        <label for="sra_colored"><input type="checkbox" value="1" name="sra_colored" id="sra_colored" <?php if($opt_sra_colored == "1") { echo "checked "; }?>/> <?php _e( 'Show only colored stars', 'star-rate-for-admin' ); ?></label>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><label for="sra_rounded"><?php _e( 'Rounded value', 'star-rate-for-admin' ); ?></label></th>
                                <td>
                                    <fieldset>
                                        <legend class="screen-reader-text"><span><?php _e( 'Rounded value', 'star-rate-for-admin' ); ?></span></legend>
                                        <label for="sra_rounded"><input type="checkbox" value="1" name="sra_rounded" id="sra_rounded" <?php if($opt_sra_rounded == "1") { echo "checked "; }?>/> <?php _e( 'Round the value for not showing half star', 'star-rate-for-admin' ); ?></label>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e('Type of stars', 'star-rate-for-admin' ); ?></th>
                                <td>
                                    <fieldset>
                                        <legend class="screen-reader-text"><span><?php _e('Type of stars', 'star-rate-for-admin'); ?></span></legend>
                                        <?php
                                            for($i=0; $i < count($aStars); $i++) {
                                              ?>
                                                <label title="<?php echo $aStars[$i]['name']; ?>">
                                                    <input type="radio" name="sra_type" value="<?php echo $aStars[$i]['key'] ?>" style="vertical-align: top;" <?php if($opt_sra_type == $aStars[$i]['key']){ echo "checked "; }?>/>
                                                    <div style="display: inline-block;">
                                                        <img src="<?php echo SRA_STAR_URL . $aStars[$i]['images']['f'] ?>" alt="<?php _e('Full star', 'star-rate-for-admin'); ?>" width="<?php echo $aStars[$i]["size"]["w"]; ?>" height="<?php echo $aStars[$i]["size"]["h"]; ?>" />
                                                        <img src="<?php echo SRA_STAR_URL . $aStars[$i]['images']['h'] ?>" alt="<?php _e('Half star', 'star-rate-for-admin'); ?>" width="<?php echo $aStars[$i]["size"]["w"]; ?>" height="<?php echo $aStars[$i]["size"]["h"]; ?>" />
                                                        <img src="<?php echo SRA_STAR_URL . $aStars[$i]['images']['e'] ?>" alt="<?php _e('Empty star', 'star-rate-for-admin'); ?>" width="<?php echo $aStars[$i]["size"]["w"]; ?>" height="<?php echo $aStars[$i]["size"]["h"]; ?>" />
                                                        <span class="description">(<?php echo __("Type: ", "star-rate-for-admin") . $aStars[$i]["key"]; ?>, <?php echo __("Size: ", "star-rate-for-admin") . $aStars[$i]["size"]["w"] . 'x' . $aStars[$i]["size"]["h"] . ' px'; ?>)</span>
                                                    </div>
                                                </label>
                                                <div style="margin-bottom: 5px;"></div>
                                                <?php
                                            }
                                        ?>
                                    </fieldset>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <p class="submit"><input type="submit" value="<?php esc_attr_e('Save Changes') ?>" class="button button-primary" id="submit" name="sra_submit"></p>
                </form>
            </div>

            <div id="poststuff" class="metabox-holder has-right-sidebar" style="float: right; width: 24%; margin: 38px 10px 0px 0px;"> 
                <div class="postbox" id="sm_pnres">
                    <h3 class="hndle"><span><?php _e('How to use', 'star-rate-for-admin' ); ?></span></h3>
                    <div class="inside">
                      <?php _e("<p align=\"justify\">To display the stars in the post, you have to write the following pattern directly on the content:</p><center><b style=\"font-size:17px;\">[SRA value=\"x.x\" OPTIONS]</b></center><p align=\"justify\">where \"x\" represents the numbers that lead to the number of stars which are visible or colored. For example: <b>[SRA value=\"5.7\"]</b>.</p>", "star-rate-for-admin"); ?>
                      <?php _e("<h6 style=\"font-size:1.2em; margin-bottom:0px; text-align:center;\">Options</h6><ul style=\"text-indent:10px; margin-top:5px;\"><li><b>type</b>: Represents a specific type of stars (e.g.: [SRA value=\"5.7\" type=\"GN\"]).</li><li><b>range</b>: Represents the number of stars. The value can be 5 or 10 (e.g.: [SRA value=\"5.7\" range=\"10\"]).</li><li><b>colored</b>: Represents whether it only shows the stars of colored or all the stars. The value can be 0 (all) or 1 (only colored). (e.g.: [SRA value=\"5.7\" colored=\"1\"]).</li><li><b>rounded</b>: Represents whether it rounds the value for not showing a half star. The value can be 0 (no) or 1 (yes). (e.g.: [SRA value=\"5.7\" rounded=\"1\"]).</li></ul>", "star-rate-for-admin"); ?>
                    </div>
                </div>
                <div class="postbox" id="sm_pnres">
                    <h3 class="hndle"><span><?php _e('About this plugin', 'star-rate-for-admin' ); ?></span></h3>
                    <div class="inside">
                        <a href="http://www.javierglez.es" class="sm_button sm_pluginHome"><?php _e('PlugIn Web Site', 'star-rate-for-admin' ); ?></a>
                        <br />
                        <a href="http://www.javierglez.es" class="sm_button sm_pluginHome"><?php _e('Request new features', 'star-rate-for-admin' ); ?></a>
                        <br />
                        <a href="http://www.javierglez.es" class="sm_button sm_pluginList"><?php _e('Report a error', 'star-rate-for-admin' ); ?></a>
                    </div>
                </div>
            </div>
        </div>
    <?
}

/**
 * Devuelve un array con el Tipo de Estrella Necesario
 * @param String $sType Tipo de Estrella elegido para que te devuelva
 * @return Array Array con los contenidos de ese tipo (Key, Nombre, Descripción, Imagenes)
*/
function sraStarSearch ($sType="OS") {
  global $aStars;
  $aReturn = array();
  
  foreach ($aStars as $sKey => $aItem) {
    if ($aItem['key'] === $sType) {
      $aReturn = $aItem;
      break;
    }
  }
  return $aReturn;
}

/**
 * Guarda las configuraciones Necesarias
*/
function sraSave () {
    $aError = array();

    if(isset($_POST["sra_range"]) && sraCheck("sra_range" , $_POST["sra_range"])) {
        update_option("sra_range", $_POST["sra_range"]);
    } else { $aError["sra_range"] = "Error al guardar el sra_range"; }
    
    $sAux = (!isset($_POST["sra_colored"])) ? "0" : $_POST["sra_colored"];
    if(isset($sAux) && sraCheck("sra_colored" , $sAux)) {
        update_option("sra_colored", $sAux);
    } else { $aError["sra_colored"] = "Error al guardar el sra_colored"; }
    
    $sAux = (!isset($_POST["sra_rounded"])) ? "0" : $_POST["sra_rounded"];
    if(isset($sAux) && sraCheck("sra_rounded" , $sAux)) {
        update_option("sra_rounded", $sAux);
    } else { $aError["sra_rounded"] = "Error al guardar el sra_rounded"; }
    
    if(isset($_POST["sra_type"]) && sraCheck("sra_type" , $_POST["sra_type"])) {
        update_option("sra_type", $_POST["sra_type"]);
    } else { $aError["sra_type"] = "Error al guardar el sra_type"; }

    if(empty($aError)) {
        ?><div class="updated"><p><strong><?php _e('Settings saved.', 'star-rate-for-admin' ); ?></strong></p></div><?php
    } else {
        ?><div class="error"><p><strong><?php _e('Settings NOT saved. There are error(s).', 'star-rate-for-admin' ); ?></strong></p></div><?php
    }
}

/**
 * Checkea los campos venidos por POST, para ver si estan dentro de los parámetros
 * @param String $sField Nombre del campo que tiene que checkear
 * @param String $sValue Valor del campo que tiene que checkear
*/
function sraCheck ($sField, $sValue) {
    $bResult = true;
    
    switch ($sField):
        case "sra_range":
            if($sValue != "5" && $sValue != "10") { $bResult = false; }
            break;
        case "sra_colored":
            if($sValue != "0" && $sValue != "1") { $bResult = false; }
            break;
        case "sra_rounded":
            if($sValue != "0" && $sValue != "1") { $bResult = false; }
            break;
        case "sra_type":
            $aux = sraStarSearch($sValue);
            if(empty($aux)) { $bResult = false; }
            break;
        
        default :
            break;
    endswitch;
    
    return $bResult;
}

function sraShortcode ($atts, $sContent=NULL) {
    $aOptSRA = array('type'=>'OS', 'range'=>'5', 'colored'=>'0', 'rounded'=>'0');
    $sValue = '0';
    
    // Recuperar las opciones Guardadas
    $aOptSRA['type'] = get_option("sra_type", $aOptSRA['type']);
    $aOptSRA['range'] = get_option("sra_range", $aOptSRA['range']);
    $aOptSRA['colored'] = get_option("sra_colored", $aOptSRA['colored']);
    $aOptSRA['rounded'] = get_option("sra_rounded", $aOptSRA['rounded']);
    
    // Recuperar datos del ShorCode (Valor, Tipo, etc)
    $aShortCodeValues = shortcode_atts( array(
        'value' => $sValue,
        'type' => $aOptSRA['type'],
        'range' => $aOptSRA['range'],
        'colored' => $aOptSRA['colored'],
        'rounded' => $aOptSRA['rounded'],
        ), $atts);
    $aOptSRA['type'] = (sraCheck("sra_type" , $aShortCodeValues['type'])) ? $aShortCodeValues['type'] : $aOptSRA['type'];
    $aType = sraStarSearch($aOptSRA['type']);
    $aOptSRA['range'] = (sraCheck("sra_range" , $aShortCodeValues['range'])) ? $aShortCodeValues['range'] : $aOptSRA['range'];
    $aOptSRA['colored'] = (sraCheck("sra_colored" , $aShortCodeValues['colored'])) ? $aShortCodeValues['colored'] : $aOptSRA['colored'];
    $aOptSRA['rounded'] = (sraCheck("sra_rounded" , $aShortCodeValues['rounded'])) ? $aShortCodeValues['rounded'] : $aOptSRA['rounded'];
    $sValue = (!is_numeric($aShortCodeValues['value']) || $aShortCodeValues['value'] > $aOptSRA['range']) ? $aOptSRA['range'] : $aShortCodeValues['value'];
    $sValue = ($sValue < 0) ? 0 : $sValue;
    
    // Componer las Estrellas
    if (!empty($aOptSRA['rounded'])) { $sValue = round($sValue, 0, PHP_ROUND_HALF_DOWN); }

    $sStars = "";
    for($i=0; $i < $aOptSRA['range']; $i++) {
        if ($i+1 <= $sValue){ // Full
            $sStars .= '<img src="' . SRA_STAR_URL . $aType['images']['f'] . '" width="' . $aType["size"]["w"] . '" height="' . $aType["size"]["h"] . '" />';
        } elseif ($i < $sValue){ // Half
            $sStars .= '<img src="' . SRA_STAR_URL . $aType['images']['h'] . '" width="' . $aType["size"]["w"] . '" height="' . $aType["size"]["h"] . '" />';
        } elseif(empty($aOptSRA['colored'])) { // Empty
            $sStars .= '<img src="' . SRA_STAR_URL . $aType['images']['e'] . '" width="' . $aType["size"]["w"] . '" height="' . $aType["size"]["h"] . '" />';
        }
    }

    // Componer el Contenido y Devolverlo
    $sContent .= '<label title="' . sprintf(__('The score is: %s over %d', 'star-rate-for-admin'), $sValue, $opt_sra_range) . '">' . $sStars . '</label>';
    return $sContent;
}

// Añadir el enlace de "Ajustes" al panel de administración de los PlugIns
function sraSettingsLink($links) { 
    $settings_link = '<a href="options-general.php?page=star_rate_settings">' . __('Settings') . '</a>'; 
    array_push($links, $settings_link); 
    return $links; 
}

// Cargar funciones Iniciales
function sraInit() {
    // Carga los idiomas
    load_plugin_textdomain( 'star-rate-for-admin', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
}
 
// Inicializar en Admin
add_action( 'init', 'sraInit' );

// Para añadir el Menu
add_action("admin_menu","sraMenuPlugin");

// Para añadir un manejador de Codigos Cortos (del estilo a "[SRA x.x]")
add_shortcode('SRA', 'sraShortcode');

// Añadir el enlace de "Ajustes" al panel de administración de los PlugIns
add_filter("plugin_action_links_".SRA_PLUGIN, 'sraSettingsLink' );
