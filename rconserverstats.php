<?php
/*
Plugin Name: RCON Server Stats
Description: Create by VXH.PL using api.vxh.pl
Version: 0.1 
Author: SkullMedia Artur Spychalski
*/
/* ----------------------------------------------- */

/* --------------------- WORDPRESS MENU  ----------------------------------------*/
class RCONserverstats
{
    private $RCON_server_stats_options;

    public function __construct()
    {
        add_action('admin_menu', array($this, 'RCON_server_stats_add_plugin_page'));
        add_action('admin_init', array($this, 'RCON_server_stats_page_init'));
    }

    public function RCON_server_stats_add_plugin_page()
    {
        add_menu_page(
            'RCON Server Stats', // page_title
            'RCON Server Stats', // menu_title
            'manage_options', // capability
            'RCON_server_stats', // menu_slug
            array($this, 'RCON_server_stats_create_admin_page'), // function
            'dashicons-nametag', // icon_url
            2 // position
        );
    }

    public function RCON_server_stats_create_admin_page()
    {

        $this->RCON_server_stats_options = get_option('RCON_server_stats_option_name');

?>
        <link rel="stylesheet" href="<?php echo plugins_url('css/style.css', __FILE__); ?>" type="text/css" />
        <div class="wrap RCON_server_stats_wrap_content">
            <div class="menu_RCON_server_stats_plugin_title">
                <h2>RCON_server_stats</h2>
                <span><a href="https://vxh.pl/">vxh.pl</a></span>
            </div>
            <?php settings_errors(); ?>
            <div class="RCON_server_stats_wrap_content_outer">
                <?php

                $default_tab = null;
                $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;

                ?>
                <h2 class="nav-tab-wrapper">
                    <a href="?page=RCON_server_stats&tab=Instrukcje" class="nav-tab <?php if ($tab === "Instrukcje" || $tab === null) : ?>nav-tab-active<?php endif; ?>">Instrukcje</a>
                    <a href="?page=RCON_server_stats&tab=Ustawienia" class="nav-tab <?php if ($tab === "Ustawienia") : ?>nav-tab-active<?php endif; ?>">Ustawienia</a>
                    <a href="?page=RCON_server_stats&tab=ServerInfo" class="nav-tab <?php if ($tab === "ServerInfo") : ?>nav-tab-active<?php endif; ?>">Server Info</a>
                    <a href="?page=RCON_server_stats&tab=PlayersInfo" class="nav-tab <?php if ($tab === "PlayersInfo") : ?>nav-tab-active<?php endif; ?>">Players Info</a>
                    <a href="?page=RCON_server_stats&tab=DEBUG" class="nav-tab <?php if ($tab === "DEBUG") : ?>nav-tab-active<?php endif; ?>">DEBUG</a>
                </h2>

                <div class="RCON_server_stats_wrap_content_inner">
                    <form method="post" action="options.php">
                        <?php settings_fields('RCON_server_stats_option_group'); ?>
                        <div class="tab-content hide <?php if ($tab === "Instrukcje" || $tab === null) : ?>content-tab-active<?php endif; ?>">
                            <h2>Ogólne informacje</h2>
                            <p>Plugin wykorzystuje api <a href="https://api.vxh.pl">api.vxh.pl</a> z funkcją get().</p>
                            <h2>Instrukcja</h2>
                            <p>1. Do wykorzystania pluginu musisz wgrać <a href="https://pl.wordpress.org/plugins/advanced-custom-fields/">ACF</a> oraz <a href="https://pl.wordpress.org/plugins/custom-post-type-ui/">CPT UI</a>.</p>
                            <ul style="list-style-type: circle;padding-left: 25px;">
                                <?php
                                // check if plugin acf or acf pro is active 
                                if (is_plugin_active('advanced-custom-fields/acf.php') || is_plugin_active('advanced-custom-fields-pro/acf.php')) {
                                    echo '<li style="color: green">Plugin ACF jest aktywny.</li>';
                                } else {
                                    echo '<li style="color: red">Plugin ACF nie jest aktywny.</li>';
                                }

                                // check if plugin cpt ui is active
                                if (is_plugin_active('custom-post-type-ui/custom-post-type-ui.php')) {
                                    echo '<li style="color: green">Plugin CPT UI jest aktywny.</li>';
                                } else {
                                    echo '<li style="color: red">Plugin CPT UI nie jest aktywny.</li>';
                                }
                                ?>
                            </ul>
                            <p>2. W pluginie CPT UI musisz utworzyć custom post type np. "serwery", które potem będą twoją listą serwerów.</p>
                            <p>3. W zakładce Ustawienia musisz podać nazwę z CPT UI custom post type</p>
                            <p>4. W pluginie ACF musisz utworzyć pola dla custom post type "serwery". Takie jak:</p>
                            <ul style="list-style-type: circle;padding-left: 25px;">
                                <li>hostname</li>
                                <li>ip_serwera</li>
                                <li>port_serwera</li>
                                <li>ilosc_graczy</li>
                                <li>max_ilosc_graczy</li>
                                <li>ilosc_botow</li>
                                <li>mapa</li>
                                <li>status</li>
                                <li>typ_gry</li>
                                <li>gametagi</li>
                                <li>connect_steam</li>
                                <li>lista_graczy</li>
                            </ul>
                            <p>5. Musisz przypiąć nazwy pól jakie utworzyłeś w zakładkach Server Info oraz Player Info do pól, które chcesz wykorzystać [puste pola nie będą aktualizowane]</p>
                            <p>6. Potem dodaj "rcon_server_stats_loaddata_from_api(false);" do swojego kodu w functions.php motywu.</p>
                            <p>7. Dodając serwer uzupełnij pole ip oraz port [tytuł posta nie ma znaczenia]</p>
                            <p>Dzięki temu pluginowi będziesz mógł zrobić cusotmowy wygląd postów np używając <a href="https://pl.wordpress.org/plugins/elementor/">Elementora</a> i <a href="https://wordpress.com/plugins/ele-custom-skin">Ele Custom Skins</a> do wyświetlania informacji o serwerach.</p>

                        </div>
                        <div class="tab-content hide <?php if ($tab === "Ustawienia") : ?>content-tab-active<?php endif; ?>">
                            <?php
                            do_settings_sections('RCON_server_stats-admin-ustawienia');
                            submit_button();
                            ?>
                        </div>
                        <div class="tab-content hide <?php if ($tab === "ServerInfo") : ?>content-tab-active<?php endif; ?>">
                            <?php
                            do_settings_sections('RCON_server_stats-admin-serverinfo');
                            submit_button();
                            ?>
                        </div>
                        <div class="tab-content hide<?php if ($tab === "PlayersInfo") : ?>content-tab-active<?php endif; ?>">
                            <h3>Wkrótce</h3>
                            <?php
                            // do_settings_sections('RCON_server_stats-admin-playersinfo');
                            // submit_button();
                            ?>
                        </div>
                        <div class="tab-content hide<?php if ($tab === "DEBUG") : ?>content-tab-active<?php endif; ?>">
                            <h3>Debug</h3>
                            <?php
                            //use function debug_log();
                            debug_log();
                            ?>
                        </div>
                    </form>
                </div>

            </div>
            <div class="menu_RCON_server_stats_plugin_footer">
                <p>Copyright © 2022 <a href="https://vxh.pl/">VXH.pl</a>. All rights reserved.</p>
            </div>
        </div>

    <?php }



    public function RCON_server_stats_page_init()
    {
        register_setting(
            'RCON_server_stats_option_group', // option_group
            'RCON_server_stats_option_name', // option_name
            array($this, 'RCON_server_stats_sanitize') // sanitize_callback
        );
        /* DEKLARACJA SEKCJI */

        /* ------------------------------------------------------------------------ */
        add_settings_section(
            'RCON_server_stats_setting_section', // id
            'Ustawienia', // title
            array($this, 'RCON_server_stats_section_ustawienia'), // callback
            'RCON_server_stats-admin-ustawienia' // page
        );

        /* Dodaj pola do ustawien */

        add_settings_field(
            'rcon_server_ustawienia_0', // id
            'Custom Post Type Name', // title
            array($this, 'rcon_server_ustawienia_cptui_name'), // callback
            'RCON_server_stats-admin-ustawienia', // page
            'RCON_server_stats_setting_section' // section
        );

        // add field to cpt ui group key
        add_settings_field(
            'rcon_server_ustawienia_1', // id
            'ACF Group Key', // title
            array($this, 'rcon_server_ustawienia_cptui_group_key'), // callback
            'RCON_server_stats-admin-ustawienia', // page
            'RCON_server_stats_setting_section' // section
        );

        // add section to server info
        add_settings_section(
            'RCON_server_stats_setting_section', // id
            'Server Info', // title
            array($this, 'RCON_server_stats_section_serverinfo'), // callback
            'RCON_server_stats-admin-serverinfo' // page
        );
        //add fields to server info
        add_settings_field(
            'rcon_server_serverinfo_0', // id
            'Server Hostname', // title
            array($this, 'rcon_server_serverinfo_hostname'), // callback
            'RCON_server_stats-admin-serverinfo', // page
            'RCON_server_stats_setting_section' // section
        );
        add_settings_field(
            'rcon_server_serverinfo_1', // id
            'Server IP', // title
            array($this, 'rcon_server_serverinfo_ip'), // callback
            'RCON_server_stats-admin-serverinfo', // page
            'RCON_server_stats_setting_section' // section
        );
        add_settings_field(
            'rcon_server_serverinfo_2', // id
            'Server Port', // title
            array($this, 'rcon_server_serverinfo_port'), // callback
            'RCON_server_stats-admin-serverinfo', // page
            'RCON_server_stats_setting_section' // section
        );
        add_settings_field(
            'rcon_server_serverinfo_3', // id
            'Server Players', // title
            array($this, 'rcon_server_serverinfo_players'), // callback
            'RCON_server_stats-admin-serverinfo', // page
            'RCON_server_stats_setting_section' // section
        );
        add_settings_field(
            'rcon_server_serverinfo_4', // id
            'Server Max Players', // title
            array($this, 'rcon_server_serverinfo_maxplayers'), // callback
            'RCON_server_stats-admin-serverinfo', // page
            'RCON_server_stats_setting_section' // section
        );
        add_settings_field(
            'rcon_server_serverinfo_5', // id
            'Ilosc botow', // title
            array($this, 'rcon_server_serverinfo_bots'), // callback
            'RCON_server_stats-admin-serverinfo', // page
            'RCON_server_stats_setting_section' // section
        );
        add_settings_field(
            'rcon_server_serverinfo_6', // id
            'Server Map', // title
            array($this, 'rcon_server_serverinfo_map'), // callback
            'RCON_server_stats-admin-serverinfo', // page
            'RCON_server_stats_setting_section' // section
        );
        add_settings_field(
            'rcon_server_serverinfo_7', // id
            'Server Status', // title
            array($this, 'rcon_server_serverinfo_status'), // callback
            'RCON_server_stats-admin-serverinfo', // page
            'RCON_server_stats_setting_section' // section
        );
        add_settings_field(
            'rcon_server_serverinfo_8', // id
            'Server Game Type', // title
            array($this, 'rcon_server_serverinfo_gametype'), // callback
            'RCON_server_stats-admin-serverinfo', // page
            'RCON_server_stats_setting_section' // section
        );
        add_settings_field(
            'rcon_server_serverinfo_9', // id
            'Server Game Tags', // title
            array($this, 'rcon_server_serverinfo_gametags'), // callback
            'RCON_server_stats-admin-serverinfo', // page
            'RCON_server_stats_setting_section' // section
        );
        add_settings_field(
            'rcon_server_serverinfo_11', // id
            'Connect Steam', // title
            array($this, 'rcon_server_serverinfo_connect_steam'), // callback
            'RCON_server_stats-admin-serverinfo', // page
            'RCON_server_stats_setting_section' // section
        );
        add_settings_field(
            'rcon_server_serverinfo_10', // id
            'Server Players List', // title
            array($this, 'rcon_server_serverinfo_playerslist'), // callback
            'RCON_server_stats-admin-serverinfo', // page
            'RCON_server_stats_setting_section' // section
        );
    }
    // ---------------- USTAWIENIA ---------------
    //callback for RCON_server_stats_section_ustawienia
    public function RCON_server_stats_section_ustawienia()
    {
        echo '<p>Uzupełnij wymagane pola według instrukcji</p>';
    }
    // callback for rcon_server_ustawienia_cptui_name
    public function rcon_server_ustawienia_cptui_name()
    {
        $options = get_option('RCON_server_stats_option_name');
        $cptui_name = $options['rcon_server_ustawienia_0'] ?? '';
        echo '<input type="text" id="rcon_server_ustawienia_0" name="RCON_server_stats_option_name[rcon_server_ustawienia_0]" value="' . $cptui_name . '" />';
    }
    // callback for rcon_server_ustawienia_cptui_group_key
    public function rcon_server_ustawienia_cptui_group_key()
    {
        $options = get_option('RCON_server_stats_option_name');
        $cptui_group_key = $options['rcon_server_ustawienia_1'] ?? '';
        echo '<input type="text" id="rcon_server_ustawienia_1" name="RCON_server_stats_option_name[rcon_server_ustawienia_1]" value="' . $cptui_group_key . '" />';
    }

    // ---------------- SERVER INFO ---------------
    public function RCON_server_stats_section_serverinfo()
    {
        echo '<p>Uzupełnij wymagane pola według instrukcji</p>';
    }
    //add inputs fields to server info
    public function rcon_server_serverinfo_hostname()
    {
        $options = get_option('RCON_server_stats_option_name');
        $serverinfo_hostname = $options['rcon_server_serverinfo_0'] ?? '';
        echo '<input type="text" id="rcon_server_serverinfo_0" name="RCON_server_stats_option_name[rcon_server_serverinfo_0]" value="' . $serverinfo_hostname . '" />';
    }
    public function rcon_server_serverinfo_ip()
    {
        $options = get_option('RCON_server_stats_option_name');
        $serverinfo_ip = $options['rcon_server_serverinfo_1'] ?? '';
        echo '<input type="text" id="rcon_server_serverinfo_1" name="RCON_server_stats_option_name[rcon_server_serverinfo_1]" value="' . $serverinfo_ip . '" />';
    }
    public function rcon_server_serverinfo_port()
    {
        $options = get_option('RCON_server_stats_option_name');
        $serverinfo_port = $options['rcon_server_serverinfo_2'] ?? '';
        echo '<input type="text" id="rcon_server_serverinfo_2" name="RCON_server_stats_option_name[rcon_server_serverinfo_2]" value="' . $serverinfo_port . '" />';
    }
    public function rcon_server_serverinfo_players()
    {
        $options = get_option('RCON_server_stats_option_name');
        $serverinfo_players = $options['rcon_server_serverinfo_3'] ?? '';
        echo '<input type="text" id="rcon_server_serverinfo_3" name="RCON_server_stats_option_name[rcon_server_serverinfo_3]" value="' . $serverinfo_players . '" />';
    }
    public function rcon_server_serverinfo_maxplayers()
    {
        $options = get_option('RCON_server_stats_option_name');
        $serverinfo_maxplayers = $options['rcon_server_serverinfo_4'] ?? '';
        echo '<input type="text" id="rcon_server_serverinfo_4" name="RCON_server_stats_option_name[rcon_server_serverinfo_4]" value="' . $serverinfo_maxplayers . '" />';
    }
    public function rcon_server_serverinfo_bots()
    {
        $options = get_option('RCON_server_stats_option_name');
        $serverinfo_bots = $options['rcon_server_serverinfo_5'] ?? '';
        echo '<input type="text" id="rcon_server_serverinfo_5" name="RCON_server_stats_option_name[rcon_server_serverinfo_5]" value="' . $serverinfo_bots . '" />';
    }
    public function rcon_server_serverinfo_map()
    {
        $options = get_option('RCON_server_stats_option_name');
        $serverinfo_map = $options['rcon_server_serverinfo_6'] ?? '';
        echo '<input type="text" id="rcon_server_serverinfo_6" name="RCON_server_stats_option_name[rcon_server_serverinfo_6]" value="' . $serverinfo_map . '" />';
    }
    public function rcon_server_serverinfo_status()
    {
        $options = get_option('RCON_server_stats_option_name');
        $serverinfo_status = $options['rcon_server_serverinfo_7'] ?? '';
        echo '<input type="text" id="rcon_server_serverinfo_7" name="RCON_server_stats_option_name[rcon_server_serverinfo_7]" value="' . $serverinfo_status . '" />';
    }
    public function rcon_server_serverinfo_gametype()
    {
        $options = get_option('RCON_server_stats_option_name');
        $serverinfo_gametype = $options['rcon_server_serverinfo_8'] ?? '';
        echo '<input type="text" id="rcon_server_serverinfo_8" name="RCON_server_stats_option_name[rcon_server_serverinfo_8]" value="' . $serverinfo_gametype . '" />';
    }
    public function rcon_server_serverinfo_gametags()
    {
        $options = get_option('RCON_server_stats_option_name');
        $serverinfo_gametags = $options['rcon_server_serverinfo_9'] ?? '';
        echo '<input type="text" id="rcon_server_serverinfo_9" name="RCON_server_stats_option_name[rcon_server_serverinfo_9]" value="' . $serverinfo_gametags . '" />';
    }
    public function rcon_server_serverinfo_connect_steam()
    {
        $options = get_option('RCON_server_stats_option_name');
        $serverinfo_connect_steam = $options['rcon_server_serverinfo_11'] ?? '';
        echo '<input type="text" id="rcon_server_serverinfo_11" name="RCON_server_stats_option_name[rcon_server_serverinfo_11]" value="' . $serverinfo_connect_steam . '" />';
    }


    public function rcon_server_serverinfo_playerslist()
    {
        $options = get_option('RCON_server_stats_option_name');
        $serverinfo_playerslist = $options['rcon_server_serverinfo_10'] ?? '';
        echo '<input type="text" id="rcon_server_serverinfo_10" name="RCON_server_stats_option_name[rcon_server_serverinfo_10]" value="' . $serverinfo_playerslist . '" />';
    }






    /* Główny panel */
    public function RCON_server_stats_sanitize($input)
    {
        $sanitary_values = array();
        //ustawienia
        if (isset($input['rcon_server_ustawienia_0'])) {
            $sanitary_values['rcon_server_ustawienia_0'] = sanitize_text_field($input['rcon_server_ustawienia_0']);
        }
        if (isset($input['rcon_server_ustawienia_1'])) {
            $sanitary_values['rcon_server_ustawienia_1'] = sanitize_text_field($input['rcon_server_ustawienia_1']);
        }
        //server info
        if (isset($input['rcon_server_serverinfo_0'])) {
            $sanitary_values['rcon_server_serverinfo_0'] = sanitize_text_field($input['rcon_server_serverinfo_0']);
        }
        if (isset($input['rcon_server_serverinfo_1'])) {
            $sanitary_values['rcon_server_serverinfo_1'] = sanitize_text_field($input['rcon_server_serverinfo_1']);
        }
        if (isset($input['rcon_server_serverinfo_2'])) {
            $sanitary_values['rcon_server_serverinfo_2'] = sanitize_text_field($input['rcon_server_serverinfo_2']);
        }
        if (isset($input['rcon_server_serverinfo_3'])) {
            $sanitary_values['rcon_server_serverinfo_3'] = sanitize_text_field($input['rcon_server_serverinfo_3']);
        }
        if (isset($input['rcon_server_serverinfo_4'])) {
            $sanitary_values['rcon_server_serverinfo_4'] = sanitize_text_field($input['rcon_server_serverinfo_4']);
        }
        if (isset($input['rcon_server_serverinfo_5'])) {
            $sanitary_values['rcon_server_serverinfo_5'] = sanitize_text_field($input['rcon_server_serverinfo_5']);
        }
        if (isset($input['rcon_server_serverinfo_6'])) {
            $sanitary_values['rcon_server_serverinfo_6'] = sanitize_text_field($input['rcon_server_serverinfo_6']);
        }
        if (isset($input['rcon_server_serverinfo_7'])) {
            $sanitary_values['rcon_server_serverinfo_7'] = sanitize_text_field($input['rcon_server_serverinfo_7']);
        }
        if (isset($input['rcon_server_serverinfo_8'])) {
            $sanitary_values['rcon_server_serverinfo_8'] = sanitize_text_field($input['rcon_server_serverinfo_8']);
        }
        if (isset($input['rcon_server_serverinfo_9'])) {
            $sanitary_values['rcon_server_serverinfo_9'] = sanitize_text_field($input['rcon_server_serverinfo_9']);
        }
        if (isset($input['rcon_server_serverinfo_10'])) {
            $sanitary_values['rcon_server_serverinfo_10'] = sanitize_text_field($input['rcon_server_serverinfo_10']);
        }
        if (isset($input['rcon_server_serverinfo_11'])) {
            $sanitary_values['rcon_server_serverinfo_11'] = sanitize_text_field($input['rcon_server_serverinfo_11']);
        }


        return $sanitary_values;
    }
}
if (is_admin())
    $RCON_server_stats = new RCONserverstats();

/* -------------- END OF WORDPRESS MENU ------------------------- */

// [rcon_server_stats]
// get post type name from setting sanitary_values rcon_server_ustawienia_0 and check if it is not empty

function rcon_server_stats_posttype_name() //CUSTOM POST TYPE NAME
{
    $options = get_option('RCON_server_stats_option_name');
    $rcon_server_ustawienia_0 = $options['rcon_server_ustawienia_0'] ?? '';
    $rcon_server_stats_ctpname = 'asdfghjklwqrtuiopzxcvbnm';
    if (!empty($rcon_server_ustawienia_0)) {
        $rcon_server_stats_ctpname = $rcon_server_ustawienia_0;
    }
    return $rcon_server_stats_ctpname;
}
function rcon_server_stats_posttype_key() //CUSTOM POST TYPE ACF GROUP KEY
{
    $options = get_option('RCON_server_stats_option_name');
    $rcon_server_ustawienia_1 = $options['rcon_server_ustawienia_1'] ?? '';
    $rcon_server_stats_ctpkey = 'asdfghjklwqrtuiopzxcvbnm';
    if (!empty($rcon_server_ustawienia_1)) {
        $rcon_server_stats_ctpkey = $rcon_server_ustawienia_1;
    }
    return $rcon_server_stats_ctpkey;
}
function rcon_server_stats_acf_hostname() //ACF HOSTNAME FIELD NAME
{
    $options = get_option('RCON_server_stats_option_name');
    $rcon_server_serverinfo_0 = $options['rcon_server_serverinfo_0'] ?? '';
    $rcon_server_stats_acf_hostname = 'asdfghjklwqrtuiopzxcvbnm';
    if (!empty($rcon_server_serverinfo_0)) {
        $rcon_server_stats_acf_hostname = $rcon_server_serverinfo_0;
    }
    return $rcon_server_stats_acf_hostname;
}
function rcon_server_stats_acf_ip() //ACF IP FIELD NAME
{
    $options = get_option('RCON_server_stats_option_name');
    $rcon_server_serverinfo_1 = $options['rcon_server_serverinfo_1'] ?? '';
    $rcon_server_stats_acf_ip = 'asdfghjklwqrtuiopzxcvbnm';
    if (!empty($rcon_server_serverinfo_1)) {
        $rcon_server_stats_acf_ip = $rcon_server_serverinfo_1;
    }
    return $rcon_server_stats_acf_ip;
}
function rcon_server_stats_acf_port() //ACF PORT FIELD NAME
{
    $options = get_option('RCON_server_stats_option_name');
    $rcon_server_serverinfo_2 = $options['rcon_server_serverinfo_2'] ?? '';
    $rcon_server_stats_acf_port = 'asdfghjklwqrtuiopzxcvbnm';
    if (!empty($rcon_server_serverinfo_2)) {
        $rcon_server_stats_acf_port = $rcon_server_serverinfo_2;
    }
    return $rcon_server_stats_acf_port;
}
function rcon_server_stats_acf_players() //ACF PLAYERS FIELD NAME
{
    $options = get_option('RCON_server_stats_option_name');
    $rcon_server_serverinfo_3 = $options['rcon_server_serverinfo_3'] ?? '';
    $rcon_server_stats_acf_players = 'asdfghjklwqrtuiopzxcvbnm';
    if (!empty($rcon_server_serverinfo_3)) {
        $rcon_server_stats_acf_players = $rcon_server_serverinfo_3;
    }
    return $rcon_server_stats_acf_players;
}
function rcon_server_stats_acf_maxplayers() //ACF MAXPLAYERS FIELD NAME
{
    $options = get_option('RCON_server_stats_option_name');
    $rcon_server_serverinfo_4 = $options['rcon_server_serverinfo_4'] ?? '';
    $rcon_server_stats_acf_maxplayers = 'asdfghjklwqrtuiopzxcvbnm';
    if (!empty($rcon_server_serverinfo_4)) {
        $rcon_server_stats_acf_maxplayers = $rcon_server_serverinfo_4;
    }
    return $rcon_server_stats_acf_maxplayers;
}
function rcon_server_stats_acf_bots() //ACF BOTS FIELD NAME
{
    $options = get_option('RCON_server_stats_option_name');
    $rcon_server_serverinfo_5 = $options['rcon_server_serverinfo_5'] ?? '';
    $rcon_server_stats_acf_bots = 'asdfghjklwqrtuiopzxcvbnm';
    if (!empty($rcon_server_serverinfo_5)) {
        $rcon_server_stats_acf_bots = $rcon_server_serverinfo_5;
    }
    return $rcon_server_stats_acf_bots;
}
function rcon_server_stats_acf_mapname() //ACF MAPNAME FIELD NAME
{
    $options = get_option('RCON_server_stats_option_name');
    $rcon_server_serverinfo_6 = $options['rcon_server_serverinfo_6'] ?? '';
    $rcon_server_stats_acf_mapname = 'asdfghjklwqrtuiopzxcvbnm';
    if (!empty($rcon_server_serverinfo_6)) {
        $rcon_server_stats_acf_mapname = $rcon_server_serverinfo_6;
    }
    return $rcon_server_stats_acf_mapname;
}
function rcon_server_stats_acf_status() //ACF STATUS FIELD NAME
{
    $options = get_option('RCON_server_stats_option_name');
    $rcon_server_serverinfo_7 = $options['rcon_server_serverinfo_7'] ?? '';
    $rcon_server_stats_acf_status = 'asdfghjklwqrtuiopzxcvbnm';
    if (!empty($rcon_server_serverinfo_7)) {
        $rcon_server_stats_acf_status = $rcon_server_serverinfo_7;
    }
    return $rcon_server_stats_acf_status;
}
function rcon_server_stats_acf_gametype() //ACF GAMETYPE FIELD NAME
{
    $options = get_option('RCON_server_stats_option_name');
    $rcon_server_serverinfo_8 = $options['rcon_server_serverinfo_8'] ?? '';
    $rcon_server_stats_acf_gametype = 'asdfghjklwqrtuiopzxcvbnm';
    if (!empty($rcon_server_serverinfo_8)) {
        $rcon_server_stats_acf_gametype = $rcon_server_serverinfo_8;
    }
    return $rcon_server_stats_acf_gametype;
}
function rcon_server_stats_acf_gametags() //ACF GAMETAGS FIELD NAME
{
    $options = get_option('RCON_server_stats_option_name');
    $rcon_server_serverinfo_9 = $options['rcon_server_serverinfo_9'] ?? '';
    $rcon_server_stats_acf_gametags = 'asdfghjklwqrtuiopzxcvbnm';
    if (!empty($rcon_server_serverinfo_9)) {
        $rcon_server_stats_acf_gametags = $rcon_server_serverinfo_9;
    }
    return $rcon_server_stats_acf_gametags;
}
function rcon_server_stats_acf_playerlist() //ACF PLAYERLIST FIELD NAME
{
    $options = get_option('RCON_server_stats_option_name');
    $rcon_server_serverinfo_10 = $options['rcon_server_serverinfo_10'] ?? '';
    $rcon_server_stats_acf_playerlist = 'asdfghjklwqrtuiopzxcvbnm';
    if (!empty($rcon_server_serverinfo_10)) {
        $rcon_server_stats_acf_playerlist = $rcon_server_serverinfo_10;
    }
    return $rcon_server_stats_acf_playerlist;
}
function rcon_server_stats_acf_connect_steam() //ACF CONNECT STEAM FIELD NAME
{
    $options = get_option('RCON_server_stats_option_name');
    $rcon_server_serverinfo_11 = $options['rcon_server_serverinfo_11'] ?? '';
    $rcon_server_stats_acf_connect_steam = 'asdfghjklwqrtuiopzxcvbnm';
    if (!empty($rcon_server_serverinfo_11)) {
        $rcon_server_stats_acf_connect_steam = $rcon_server_serverinfo_11;
    }
    return $rcon_server_stats_acf_connect_steam;
}
function rcon_server_stats_postlist()
{
    $args = array(
        'post_type' => rcon_server_stats_posttype_name(),
        'posts_per_page' => -1,
    );
    $the_query = new WP_Query($args);
    // The Loop
    if ($the_query->have_posts()) {
        echo '<ul>';
        while ($the_query->have_posts()) {
            $the_query->the_post();
            //show id of post and title and all acf fields + link to post
            // sample echo '<li>ID: "' . get_the_ID() . '" | NAME: "' . get_the_title() . '"</li>';
            echo '<li>ID: "' . get_the_ID() . '" | NAME: "' . get_the_title() . '" | LINK: <a href="' . get_permalink() . '">ZOBACZ POST</a> | Hostname: "' . get_field(rcon_server_stats_acf_hostname()) . '" | IP: "' . get_field(rcon_server_stats_acf_ip()) . '" | PORT: "' . get_field(rcon_server_stats_acf_port()) . '" | MAXPLAYERS: "' . get_field(rcon_server_stats_acf_maxplayers()) . '" | BOTS: "' . get_field(rcon_server_stats_acf_bots()) . '" | MAPNAME: "' . get_field(rcon_server_stats_acf_mapname()) . '" | STATUS: "' . get_field(rcon_server_stats_acf_status()) . '" | GAMETYPE: "' . get_field(rcon_server_stats_acf_gametype()) . '" | GAMETAGS: "' . get_field(rcon_server_stats_acf_gametags()) . '" | PLAYERLIST: "' . get_field(rcon_server_stats_acf_playerlist()) . '"</li>';
        }
        echo '</ul>';
    } else {
        // no posts found
        //show massage if no posts
        echo '<p style="color: red">No posts found</p>';
    }
    /* Restore original Post Data */
    wp_reset_postdata();
}
function rcon_server_stats_loaddata_from_api($echo)
{
    $url = 'https://api.vxh.pl/';

    //get list of posts
    $args = array(
        'post_type' => rcon_server_stats_posttype_name(),
        'posts_per_page' => -1,
    );
    $the_query = new WP_Query($args);
    if ($the_query->have_posts()) {
        if ($echo == true) {
            echo '<ul>';
        }
        while ($the_query->have_posts()) {
            $the_query->the_post();
            //get id of post
            $post_id = get_the_ID();
            //get ip of post
            $post_ip = get_field(rcon_server_stats_acf_ip());
            //get port of post
            $post_port = get_field(rcon_server_stats_acf_port());
            //get data from https://api.vxh.pl/ with parameters ip and port in url
            //example https://api.vxh.pl/?ip=ip&port=port
            $data = file_get_contents($url . '?ip=' . $post_ip . '&port=' . $post_port);
            //decode json data
            $data = json_decode($data, true);
            //get data from json

            $data_hostname = $data['serverInfo']['HostName'] ?? 'error';
            $data_players = $data['serverInfo']['Players'] ?? 'error';
            $data_maxplayers = $data['serverInfo']['MaxPlayers'] ?? 'error';
            $data_bots = $data['serverInfo']['Bots'] ?? 'error';
            $data_mapname = $data['serverInfo']['Map'] ?? 'error';
            $data_status = $data['status'] ?? 'error';
            $data_gametype = $data['serverInfo']['GameID'] ?? 'error';
            $data_gametags = $data['serverInfo']['GameTags'] ?? 'error';
            $data_playerlist = array();
            if (isset($data['playersInfo'])) {
                foreach ($data['playersInfo'] as $player) {
                    $data_playerlist[] = $player['Name'];
                }
            }
            $data_connect = $post_ip . ':' . $post_port;
            $data_connect_steam = 'steam://connect/' . $post_ip . ':' . $post_port . '/';
            //show data
            if ($echo == true) {
                echo '<li>ID: "' . $post_id . '" | NAME: "' . get_the_title() . '" | LINK: <a href="' . get_permalink() . '">ZOBACZ POST</a> | Hostname: "' . $data_hostname . '" | IP: "' . $post_ip . '" | PORT: "' . $post_port . '" | PLAYERS: "' . $data_players . '"  | MAXPLAYERS: "' . $data_maxplayers . '" | BOTS: "' . $data_bots . '" | MAPNAME: "' . $data_mapname . '" | STATUS: "' . $data_status . '" | GAMETYPE: "' . $data_gametype . '" | GAMETAGS: "' . $data_gametags . '" | PLAYERLIST: "' . implode(', ', $data_playerlist) . '"</li>';
            }


            //if status = online then update acf fields
            if ($data_status == 'online') {
                //update acf fields
                update_field(rcon_server_stats_acf_hostname(), $data_hostname, $post_id);
                update_field(rcon_server_stats_acf_players(), $data_players, $post_id);
                update_field(rcon_server_stats_acf_maxplayers(), $data_maxplayers, $post_id);
                update_field(rcon_server_stats_acf_bots(), $data_bots, $post_id);
                update_field(rcon_server_stats_acf_mapname(), $data_mapname, $post_id);
                update_field(rcon_server_stats_acf_status(), $data_status, $post_id);
                update_field(rcon_server_stats_acf_gametype(), $data_gametype, $post_id);
                update_field(rcon_server_stats_acf_gametags(), $data_gametags, $post_id);
                //array playerlist to string with "," as separator
                $data_playerlist = implode(', ', $data_playerlist);
                update_field(rcon_server_stats_acf_playerlist(), $data_playerlist, $post_id);
                //update connect_steam
                update_field(rcon_server_stats_acf_connect_steam(), $data_connect_steam, $post_id);
            } else {
                //update status to offline
                update_field(rcon_server_stats_acf_status(), 'offline', $post_id);
                //update other fields to 0
                update_field(rcon_server_stats_acf_players(), '', $post_id);
                update_field(rcon_server_stats_acf_maxplayers(), '', $post_id);
                update_field(rcon_server_stats_acf_bots(), '', $post_id);
                update_field(rcon_server_stats_acf_mapname(), '', $post_id);
                update_field(rcon_server_stats_acf_gametype(), '', $post_id);
                update_field(rcon_server_stats_acf_gametags(), '', $post_id);
                update_field(rcon_server_stats_acf_playerlist(), '', $post_id);
            }



            //stop while loop for testing purpose
            // break;
        }
        if ($echo == true) {
            echo '</ul>';
        }
    } else {
        // no posts found
        //show massage if no posts
        if ($echo == true) {
            echo '<p style="color: red">No posts found</p>';
        }
    }





    //$test = "test";
    //return $test;
}


function debug_log()
{
    ?>
    <div>
        <ul style="list-style-type: circle;padding-left: 25px;">
            <?php
            //if rcon_server_stats_posttype_name() is not asdfghjklwqrtuiopzxcvbnm then show post type name
            if (rcon_server_stats_posttype_name() != 'asdfghjklwqrtuiopzxcvbnm') {
                echo '<li style="color: green">Post type name: ' . rcon_server_stats_posttype_name() . '</li>';
            } else {
                echo '<li style="color: red">Post type name: None</li>';
            }
            //if rcon_server_stats_posttype_key() is not asdfghjklwqrtuiopzxcvbnm then show post type key
            if (rcon_server_stats_posttype_key() != 'asdfghjklwqrtuiopzxcvbnm') {
                echo '<li style="color: green">Post type key: ' . rcon_server_stats_posttype_key() . '</li>';
            } else {
                echo '<li style="color: red">Post type key: None</li>';
            }
            //if rcon_server_stats_acf_hostname() is not asdfghjklwqrtuiopzxcvbnm then show acf hostname
            if (rcon_server_stats_acf_hostname() != 'asdfghjklwqrtuiopzxcvbnm') {
                echo '<li style="color: green">ACF hostname: ' . rcon_server_stats_acf_hostname() . '</li>';
            } else {
                echo '<li style="color: red">ACF hostname: None</li>';
            }
            //if rcon_server_stats_acf_ip() is not asdfghjklwqrtuiopzxcvbnm then show acf ip
            if (rcon_server_stats_acf_ip() != 'asdfghjklwqrtuiopzxcvbnm') {
                echo '<li style="color: green">ACF ip: ' . rcon_server_stats_acf_ip() . '</li>';
            } else {
                echo '<li style="color: red">ACF ip: None</li>';
            }
            //if rcon_server_stats_acf_port() is not asdfghjklwqrtuiopzxcvbnm then show acf port
            if (rcon_server_stats_acf_port() != 'asdfghjklwqrtuiopzxcvbnm') {
                echo '<li style="color: green">ACF port: ' . rcon_server_stats_acf_port() . '</li>';
            } else {
                echo '<li style="color: red">ACF port: None</li>';
            }
            //if rcon_server_stats_acf_mapname() is not asdfghjklwqrtuiopzxcvbnm then show acf mapname
            if (rcon_server_stats_acf_mapname() != 'asdfghjklwqrtuiopzxcvbnm') {
                echo '<li style="color: green">ACF mapname: ' . rcon_server_stats_acf_mapname() . '</li>';
            } else {
                echo '<li style="color: red">ACF mapname: None</li>';
            }
            //if rcon_server_stats_acf_status() is not asdfghjklwqrtuiopzxcvbnm then show acf status
            if (rcon_server_stats_acf_status() != 'asdfghjklwqrtuiopzxcvbnm') {
                echo '<li style="color: green">ACF status: ' . rcon_server_stats_acf_status() . '</li>';
            } else {
                echo '<li style="color: red">ACF status: None</li>';
            }
            //if rcon_server_stats_acf_gametype() is not asdfghjklwqrtuiopzxcvbnm then show acf gametype
            if (rcon_server_stats_acf_gametype() != 'asdfghjklwqrtuiopzxcvbnm') {
                echo '<li style="color: green">ACF gametype: ' . rcon_server_stats_acf_gametype() . '</li>';
            } else {
                echo '<li style="color: red">ACF gametype: None</li>';
            }
            //if rcon_server_stats_acf_gametags() is not asdfghjklwqrtuiopzxcvbnm then show acf gametags
            if (rcon_server_stats_acf_gametags() != 'asdfghjklwqrtuiopzxcvbnm') {
                echo '<li style="color: green">ACF gametags: ' . rcon_server_stats_acf_gametags() . '</li>';
            } else {
                echo '<li style="color: red">ACF gametags: None</li>';
            }
            //if rcon_server_stats_acf_connect() is not asdfghjklwqrtuiopzxcvbnm then show acf connect
            if (rcon_server_stats_acf_connect_steam() != 'asdfghjklwqrtuiopzxcvbnm') {
                echo '<li style="color: green">ACF connect: ' . rcon_server_stats_acf_connect_steam() . '</li>';
            } else {
                echo '<li style="color: red">ACF connect: None</li>';
            }
            //if rcon_server_stats_acf_playerlist() is not asdfghjklwqrtuiopzxcvbnm then show acf playerlist
            if (rcon_server_stats_acf_playerlist() != 'asdfghjklwqrtuiopzxcvbnm') {
                echo '<li style="color: green">ACF playerlist: ' . rcon_server_stats_acf_playerlist() . '</li>';
            } else {
                echo '<li style="color: red">ACF playerlist: None</li>';
            }

            ?>

        </ul>
        <h3>Lista postów znaleziona pod twoim cpt</h3>
        <ul>
            <?php
            echo rcon_server_stats_postlist();
            ?>
        </ul>
        <h3>Dane o serverach w api</h3>
        <?php
        rcon_server_stats_loaddata_from_api(true);
        ?>
    </div>
<?php
}
