<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://helloasso.com
 * @since      1.0.0
 *
 * @package    Hello_Asso
 * @subpackage Hello_Asso/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Hello_Asso
 * @subpackage Hello_Asso/admin
 * @author     HelloAsso
 */
class Hello_Asso_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 * @since    1.0.0
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hello_Asso_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hello_Asso_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, esc_url(plugin_dir_url(__FILE__)) . 'css/hello-asso-admin.css', array(), $this->version, 'all');
	}

	public function add_menu()
	{
		$urlIcon = esc_url(plugin_dir_url(__FILE__)) . 'img/icon-28x28.svg';
		add_menu_page('HelloAsso', 'HelloAsso', 'manage_options', 'hello-asso', 'content_dashboard', $urlIcon, 10);

		add_submenu_page(
			'hello-asso',
			'Synchronisation', //page title
			'Synchronisation', //menu title
			'manage_options', //capability,
			'hello-asso', //menu slug
			'content_dashboard' //callback function
		);

		function content_dashboard()
		{
			require('view/dashboard.php');
		}

		if (get_option('ha-slug') != '') {
			$campaign = get_option('ha-campaign');
			$nbCampaign = 0;

			foreach ($campaign as $key => $campain) :

				$endDate = $campain['endDate'] ?? '';
				if (strlen($endDate) > 4) {
					if (time() > strtotime($endDate)) {
						$incrementArray = 0;
					} else {
						$incrementArray = 1;
					}
				} else {
					$incrementArray = 1;
				}

				if ($incrementArray == 1) {
					$nbCampaign++;
				}

			endforeach;


			if (($nbCampaign == 0 or $campaign == '') && get_option('ha-error') == 0) {
				add_submenu_page(
					'hello-asso',
					'Mes campagnes', //page title
					'Mes campagnes', //menu title
					'manage_options', //capability,
					'ha-no-sync', //menu slug
					'error_2' //callback function
				);

				add_submenu_page(
					'',
					'Mes campagnes', //page title
					'Mes campagnes', //menu title
					'manage_options', //capability,
					'ha-no-campaign', //menu slug
					'error_2' //callback function
				);

				add_submenu_page(
					'',
					'Mes campagnes', //page title
					'Mes campagnes', //menu title
					'manage_options', //capability,
					'ha-campaign', //menu slug
					'error_2' //callback function
				);
			} elseif (get_option('ha-error') != 0) {
				add_submenu_page(
					'hello-asso',
					'Mes campagnes', //page title
					'Mes campagnes', //menu title
					'manage_options', //capability,
					'ha-no-sync', //menu slug
					'error_1' //callback function
				);

				add_submenu_page(
					'',
					'Mes campagnes', //page title
					'Mes campagnes', //menu title
					'manage_options', //capability,
					'ha-no-campaign', //menu slug
					'error_1' //callback function
				);

				add_submenu_page(
					'',
					'Mes campagnes', //page title
					'Mes campagnes', //menu title
					'manage_options', //capability,
					'ha-campaign', //menu slug
					'error_1' //callback function
				);
			} else {
				add_submenu_page(
					'hello-asso',
					'Mes campagnes', //page title
					'Mes campagnes', //menu title
					'manage_options', //capability,
					'ha-campaign', //menu slug
					'content_campaigns' //callback function
				);

				add_submenu_page(
					'',
					'Mes campagnes', //page title
					'Mes campagnes', //menu title
					'manage_options', //capability,
					'ha-no-campaign', //menu slug
					'content_campaigns' //callback function
				);

				add_submenu_page(
					'',
					'Mes campagnes', //page title
					'Mes campagnes', //menu title
					'manage_options', //capability,
					'ha-no-sync', //menu slug
					'content_campaigns' //callback function
				);
			}
		} else {
			add_submenu_page(
				'hello-asso',
				'Mes campagnes', //page title
				'Mes campagnes', //menu title
				'manage_options', //capability,
				'ha-no-sync', //menu slug
				'error_1' //callback function
			);

			add_submenu_page(
				'',
				'Mes campagnes', //page title
				'Mes campagnes', //menu title
				'manage_options', //capability,
				'ha-no-campaign', //menu slug
				'error_1' //callback function
			);

			add_submenu_page(
				'',
				'Mes campagnes', //page title
				'Mes campagnes', //menu title
				'manage_options', //capability,
				'ha-campaign', //menu slug
				'error_1' //callback function
			);
		}

		function content_campaigns()
		{
			require('view/campaign.php');
		}

		function error_1()
		{
			require('view/error_1.php');
		}

		function error_2()
		{
			require('view/error_2.php');
		}
	}

	public function add_to_gutenberg()
	{

		function loadGutenbergBlock()
		{
			wp_enqueue_script(
				'ha-gutenberg',
				esc_url(plugin_dir_url(__FILE__)) . 'js/ha-gutenberg.js',
				array('wp-blocks', 'wp-editor'),
				true
			);
		}

		add_action('enqueue_block_editor_assets', 'loadGutenbergBlock');
	}


	public function add_button_tinymce()
	{
		function wporg_add_custom_box()
		{
			$screens = ['post', 'page'];
			foreach ($screens as $screen) {
				add_meta_box(
					'hello_asso_metabox',           // Unique ID
					'Custom Meta Box Title',  // Box title
					'wporg_custom_box_html',  // Content callback, must be of type callable
					$screen                   // Post type
				);
			}
		}

		add_action('add_meta_boxes', 'wporg_add_custom_box');

		function wporg_custom_box_html($post)
		{
?>
			<div id="ha-popup" class="ha-overlay">
				<div class="ha-popup">
					<main>
						<a class="close" href="#">&times;</a>
						<button type="button" class="ha-btn ha-btn-secondary ha-return" style="display: none;"
							onclick="haReturn()">
							<img src="<?= esc_url(plugin_dir_url(__FILE__)); ?>view/icons/back.svg" /> Retour
						</button>
						<section class="content-tab" id="content1"></section>
				</div>
			</div>
			<?php
			if (get_option('ha-slug') == '') {
				$pageWidget = "ha-no-sync";
				$type = "error_1";
			} else {
				$campaign = get_option('ha-campaign') ?? [];
				$nbCampaign = is_array($campaign) ? count($campaign) : 0;
				if (($nbCampaign == 0 or $campaign == '') && get_option('ha-error') == 0) {
					$pageWidget = "ha-no-campaign";
					$type = "error_2";
				} elseif (get_option('ha-error') != 0) {
					$pageWidget = "ha-no-sync";
					$type = "error_1";
				} else {
					$pageWidget = "ha-campaign";
					$type = "ok";
				}
			}
			?>
			<a href="#ha-popup" id="ha-popup-open"
				onclick="loadViewCampaign('<?= esc_url(admin_url()); ?>admin.php?page=<?= esc_html($pageWidget); ?>&from=tinymce', '<?= esc_html($type); ?>')">Charger
				mes campagnes</a>

		<?php
		}


		add_action('media_buttons', function ($editor_id) { ?>
			<span style="display: inline-block">
				<div class="ha-dropdown">
					<a href="#" onclick="ha_dropdown()" class="ha-dropbtn ha-open-dropdown">
						<span class="ha-open-dropdown" style="margin-right: 8px;">Code court</span>
						<svg xmlns="http://www.w3.org/2000/svg" class="ha-open-dropdown" viewBox="0 0 200 43.5"
							style="width: 80px;">
							<style>
								.st38 {
									fill: #fff;
								}
							</style>
							<path d="M71.1 19.3v13.3h-6.6v-12c0-1.4-.4-1.8-1-1.8-.7 0-1.5.6-2.2 1.8v12h-6.6v-25l6.6-.7v9.4c1.5-1.6 3-2.3 5-2.3 3-.1 4.8 1.9 4.8 5.3zM90.3 25.5H79.6c.4 2.6 1.6 3 3.6 3 1.3 0 2.5-.5 4-1.6l2.7 3.7c-2 1.7-4.7 2.7-7.3 2.7-6.5 0-9.6-4.1-9.6-9.6 0-5.3 3-9.7 8.9-9.7 5.2 0 8.7 3.4 8.7 9.4-.1.5-.2 1.4-.3 2.1zm-6.3-4c0-1.8-.4-3.3-2.1-3.3-1.4 0-2.1.8-2.4 3.6H84v-.3zM92.1 27.4V7.5l6.6-.7v20.3c0 .6.3.9.8.9.2 0 .5 0 .7-.2l1.2 4.7c-1.2.4-2.4.6-3.6.6-3.7.2-5.7-2-5.7-5.7zM102.1 27.4V7.5l6.6-.7v20.3c0 .6.3.9.8.9.2 0 .5 0 .7-.2l1.2 4.7c-1.2.4-2.4.6-3.6.6-3.7.2-5.7-2-5.7-5.7zM129.4 23.6c0 5.9-3.5 9.6-9.2 9.6-5.6 0-9.2-3.4-9.2-9.7 0-5.9 3.5-9.6 9.2-9.6 5.6 0 9.2 3.5 9.2 9.7zm-11.5 0c0 3.6.7 4.9 2.4 4.9 1.6 0 2.4-1.4 2.4-4.9 0-3.6-.7-4.9-2.4-4.9s-2.5 1.5-2.4 4.9zM147.8 28.9l-1.3 4.3c-2.3-.2-3.8-.8-4.8-2.5-1.3 2-3.3 2.6-5.4 2.6-3.6 0-5.9-2.4-5.9-5.7 0-4 3-6.2 8.6-6.2h1.3v-.5c0-1.8-.6-2.3-2.6-2.3-1.6.1-3.1.4-4.6.9l-1.4-4.2c2.2-.9 4.6-1.4 7-1.4 5.7 0 8 2.2 8 6.6v6.2c0 1.3.3 1.9 1.1 2.2zm-7.5-1.4v-2.7h-.7c-1.9 0-2.7.6-2.7 2 0 1 .6 1.7 1.5 1.7.7.1 1.5-.3 1.9-1zM163.6 16.3l-2.3 3.6c-1.3-.8-2.7-1.3-4.2-1.3-1.1 0-1.5.3-1.5.8 0 .6.2.9 3.6 1.9 3.4 1.1 5.2 2.5 5.2 5.8 0 3.7-3.5 6.2-8.4 6.2-3.1 0-6-1.1-7.8-2.9l3.1-3.5c1.3 1 2.9 1.8 4.5 1.8 1.2 0 1.9-.4 1.9-1.1 0-.9-.4-1.1-3.4-2-3.3-1-5.2-2.9-5.2-5.8 0-3.2 2.8-5.8 7.7-5.8 2.6-.1 5.2.8 6.8 2.3zM180.1 16.3l-2.3 3.6c-1.3-.8-2.7-1.3-4.2-1.3-1.1 0-1.5.3-1.5.8 0 .6.2.9 3.6 1.9 3.4 1.1 5.2 2.5 5.2 5.8 0 3.7-3.5 6.2-8.4 6.2-3.1 0-6-1.1-7.8-2.9l3.1-3.5c1.3 1 2.9 1.8 4.5 1.8 1.2 0 1.9-.4 1.9-1.1 0-.9-.4-1.1-3.4-2-3.3-1-5.2-2.9-5.2-5.8 0-3.2 2.8-5.8 7.7-5.8 2.6-.1 5.1.8 6.8 2.3zM200 23.6c0 5.9-3.5 9.6-9.2 9.6-5.6 0-9.2-3.4-9.2-9.7 0-5.9 3.5-9.6 9.2-9.6 5.6 0 9.2 3.5 9.2 9.7zm-11.5 0c0 3.6.7 4.9 2.4 4.9 1.6 0 2.4-1.4 2.4-4.9 0-3.6-.7-4.9-2.4-4.9s-2.5 1.5-2.4 4.9z"
								class="st38" />
							<linearGradient id="SVGID_1_" x1="4.322" x2="24.268" y1="33.651" y2="-.503"
								gradientTransform="matrix(1 0 0 -1 0 44.736)"
								gradientUnits="userSpaceOnUse">
								<stop offset="0" stop-color="#498a63" />
								<stop offset=".25" stop-color="#61b984" />
							</linearGradient>
							<path fill="url(#SVGID_1_)"
								d="M12.9 34.9c-6.6-7.6-2.2-26.8.6-26.8C8.1 7.9-1.1 11.5.2 24.4c1.5 12 12.3 20.4 24.1 18.9 3.8-.5 7.3-2 10.3-4.3-10.4 7.5-17.4.8-21.7-4.1z" />
							<linearGradient id="SVGID_2_" x1="19.889" x2="40.524" y1="3.627" y2="36.697"
								gradientTransform="matrix(1 0 0 -1 0 44.736)"
								gradientUnits="userSpaceOnUse">
								<stop offset="0" stop-color="#89356d" />
								<stop offset=".21" stop-color="#b94794" />
							</linearGradient>
							<path fill="url(#SVGID_2_)"
								d="M37.2 21.9C31.7 33 14.8 37.7 12.9 34.8c3.3 4.9 11.5 11.6 21.8 4 9.4-7.3 11.1-21 3.8-30.5-2.3-3-5.4-5.3-8.9-6.8 11.7 5.3 10.5 14.6 7.6 20.4z" />
							<linearGradient id="SVGID_3_" x1="3.242" x2="37.689" y1="35.782" y2="23.384"
								gradientTransform="matrix(1 0 0 -1 0 44.736)"
								gradientUnits="userSpaceOnUse">
								<stop offset=".6" stop-color="#f59c1c" />
								<stop offset="1" stop-color="#c7702b" />
							</linearGradient>
							<path fill="url(#SVGID_3_)"
								d="M13.5 8.1c11.9-1.3 25.4 11 23.7 13.9 3.3-5.8 4.1-15.1-7.5-20.4C18.6-2.9 6 2.5 1.6 13.7.2 17.2-.3 21 .2 24.7-.6 11.9 9.1 8.5 13.5 8.1z" />
						</svg>
					</a>
					<div id="ha-dropdown" class="ha-dropdown-content">
						<a href="<?= esc_url(admin_url()); ?>admin.php?page=hello-asso">Synchronisation</a>
						<?php
						$campaign = get_option('ha-campaign') ?? [];
						$nbCampaign = is_array($campaign) ? count($campaign) : 0;

						if (get_option('ha-slug') == '') {
							$pageWidget = "ha-no-sync";
							$type = "error_1";
						} else {
							if (($nbCampaign == 0 or $campaign == '') && get_option('ha-error') == 0) {
								$pageWidget = "ha-no-campaign";
								$type = "error_2";
							} elseif (get_option('ha-error') != 0) {
								$pageWidget = "ha-no-sync";
								$type = "error_1";
							} else {
								$pageWidget = "ha-campaign";
								$type = "ok";
							}
						}
						?>
						<a href="#ha-popup"
							onclick="loadViewCampaign('<?= esc_url(admin_url()); ?>admin.php?page=<?= esc_html($pageWidget); ?>&from=tinymce', '<?= esc_html($type); ?>')">Charger mes campagnes</a>
					</div>
				</div>
			</span>
			<div id="ha-popup" class="ha-overlay">
				<div class="ha-popup">
					<main>
						<a class="close" href="#">&times;</a>
						<button type="button" class="ha-btn ha-btn-secondary ha-return" style="display: none;"
							onclick="haReturn()">
							<img src="<?= esc_url(plugin_dir_url(__FILE__)); ?>view/icons/back.svg" /> Retour
						</button>
						<section class="content-tab" id="content1"></section>
				</div>
			</div>
<?php
		});
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Trest_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Trest_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script('ha-admin-script', esc_url(plugin_dir_url(__FILE__)) . 'js/hello-asso-admin.js', array('jquery'), $this->version, false);


		wp_localize_script('ha-admin-script', 'adminAjax', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'ajax_nonce' => wp_create_nonce('helloassosecuritytoken11')
		));


		add_action('wp_ajax_ha_ajax', 'ha_ajax');
		add_action('wp_ajax_nopriv_ha_ajax', 'ha_ajax');
	}


	public function loadAjax()
	{
		add_action('wp_ajax_ha_ajax', 'ha_ajax');
		add_action('wp_ajax_nopriv_ha_ajax', 'ha_ajax');
		add_action('wp_ajax_ha_search_campaign', 'ha_search_campaign');
		add_action('wp_ajax_nopriv_ha_search_campaign', 'ha_search_campaign');

		function sanitizeArray($data = array())
		{
			if (!is_array($data) || !count($data)) {
				return array();
			}
			foreach ($data as $k => $v) {
				if (!is_array($v) && !is_object($v)) {
					$data[$k] = htmlspecialchars(trim($v));
				}
				if (is_array($v)) {
					$data[$k] = sanitizeArray($v);
				}
			}
			return $data;
		}

		function ha_search_campaign()
		{
			check_ajax_referer('helloassosecuritytoken11', 'security');

			if (!is_user_logged_in() || !current_user_can('manage_options')) {
				wp_die('Vous n\'avez pas les droits nécessaires pour exécuter cette action.');
			}

			$value = sanitize_text_field($_POST['value']);

			if (empty($value)) {
				wp_send_json_error('Le champ est vide.');
				return;
			}

			$url = parse_url($value);
			$sandbox = false;
			$nameAsso = '';

			if ($url !== false) {
				$domain = $url['host'];

				if ($domain == 'helloasso-sandbox.com' || $domain == 'www.helloasso-sandbox.com') {
					$sandbox = true;
				}

				if ($domain != 'helloasso.com' && $domain != 'www.helloasso.com' && $domain != 'helloasso-sandbox.com' && $domain != 'www.helloasso-sandbox.com') {
					$nameAsso = '';
				} else {
					$slug = explode('/', $value);
					$nameAsso = isset($slug[4]) ? $slug[4] : '';
				}
			} else {
				$nameAsso = sanitize_title_with_dashes($value);
			}

			if (empty($nameAsso)) {
				wp_send_json_error('URL ou nom d\'association invalide.');
				return;
			}

			$apiUrl = $sandbox ? 'https://api.helloasso-sandbox.com' : 'https://api.helloasso.com';
			$body = array(
				'grant_type' => 'client_credentials',
				'client_id' => $sandbox ? '3732d11d-e73a-40a2-aa28-a54fa1aa76be' : '049A416C-5820-45FE-B645-1D06FB4AA622',
				'client_secret' => $sandbox ? 'vOsIvf7T496A5/LGeTG6Uq7CNdFydh8s' : 'I+YF/JjLrcE1+iPEFul+BBJDWIil+1g5'
			);

			$token_response = ha_curl_post($apiUrl . '/oauth2/token', $body);

			if ($token_response === false) {
				wp_send_json_error('Erreur de connexion à l\'API HelloAsso.');
				return;
			}

			$token_data = json_decode($token_response, true);

			if (!isset($token_data['access_token'])) {
				wp_send_json_error('Erreur d\'authentification avec l\'API HelloAsso.');
				return;
			}

			$bearer_token = $token_data['access_token'];

			$org_response = ha_curl_get($apiUrl . '/v5/organizations/' . $nameAsso, $bearer_token);

			if ($org_response === false) {
				wp_send_json_error('Erreur lors de la récupération des informations de l\'organisation.');
				return;
			}

			$org_data = json_decode($org_response, true);

			if (!isset($org_data['name'])) {
				wp_send_json_error('Organisation non trouvée.');
				return;
			}

			$asso_name = $org_data['name'];
			$all_campaigns = array();
			$total_count = 0;

			for ($i = 1; $i <= 5; $i++) {
				$campaign_response = ha_curl_get($apiUrl . '/v5/organizations/' . $nameAsso . '/forms?pageSize=20&pageIndex=' . $i, $bearer_token);

				if ($campaign_response === false) {
					continue;
				}

				$campaign_data = json_decode($campaign_response, true);

				if (isset($campaign_data['data']) && is_array($campaign_data['data'])) {
					$count = count($campaign_data['data']);
					$total_count += $count;
					$all_campaigns = array_merge($all_campaigns, $campaign_data['data']);

					if (isset($campaign_data['pagination']['totalCount']) && $total_count >= $campaign_data['pagination']['totalCount']) {
						break;
					}
				}

				usleep(1250000);
			}

			if (empty($all_campaigns)) {
				wp_send_json_error('Aucune campagne trouvée pour cette organisation.');
				return;
			}

			$result = array(
				'success' => true,
				'asso_name' => $asso_name,
				'campaigns' => $all_campaigns,
				'total_count' => $total_count,
				'slug' => $nameAsso
			);

			wp_send_json($result);
		}

		function ha_curl_post($url, $data)
		{
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/x-www-form-urlencoded'
			));

			$response = curl_exec($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$error = curl_error($ch);

			curl_close($ch);

			if ($error || $http_code !== 200) {
				return false;
			}

			return $response;
		}

		function ha_curl_get($url, $bearer_token = null)
		{
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

			$headers = array();
			if ($bearer_token) {
				$headers[] = 'Authorization: Bearer ' . $bearer_token;
			}

			if (!empty($headers)) {
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			}

			$response = curl_exec($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$error = curl_error($ch);

			curl_close($ch);

			if ($error || $http_code !== 200) {
				return false;
			}

			return $response;
		}

		function ha_ajax()
		{
			check_ajax_referer('helloassosecuritytoken11', 'security');

			if (! is_user_logged_in() || ! current_user_can('manage_options')) {
				wp_die('Vous n\'avez pas les droits nécessaires pour exécuter cette action.');
			}

			if (!isset($_POST['campaign']) or $_POST['campaign'] == '') {
				$campaign = array();
			} else {
				$campaign = sanitizeArray($_POST['campaign']);
			}

			if (isset($_POST['slug']) && is_numeric($_POST['error']) && is_array($campaign) && isset($_POST['increase']) && $_POST['increase'] == 1) {
				$error = intval($_POST['error']);
				$name = sanitize_text_field($_POST['name']);
				$slug = sanitize_title_with_dashes($_POST['slug']);

				delete_option('ha-campaign');
				delete_option('ha-slug');
				delete_option('ha-sync');
				delete_option('ha-error');
				delete_option('ha-name');

				add_option('ha-campaign', $campaign);
				add_option('ha-slug', $slug, '', 'yes');
				add_option('ha-sync', current_time('timestamp'), '', 'yes');
				add_option('ha-error', $error, '', 'yes');
				add_option('ha-name', $name, '', 'yes');
			} elseif (isset($_POST['slug']) && is_numeric($_POST['error']) && is_array($campaign) && isset($_POST['increase']) && $_POST['increase'] > 1) {
				$currentCampain = get_option('ha-campaign');
				$campaign = array_merge($currentCampain, $campaign);
				update_option('ha-campaign', $campaign);
			} else {
				echo 'Erreur de format des données';
			}
		}
	}
}
