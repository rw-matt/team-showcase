<?php
/**
 * Plugin Name: RW Team Showcase
 * Plugin URI: https://recruiterswebsites.com/rw-team-showcase/
 * Description: A WordPress plugin to showcase team members with custom profiles, roles, and social media links.
 * Version: 1.0.0
 * Author: Recruiters Websites
 * Author URI: https://recruiterswebsites.com/
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: rw-team-showcase
 * Domain Path: /languages
 *
 * @package RWTeamShowcase
 */


// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Main Team Showcase Plugin Class
 */
class Team_Showcase_Plugin {

    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        add_action( 'init', array( $this, 'register_team_member_post_type' ) );
        add_action( 'init', array( $this, 'register_team_category_taxonomy' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_team_member_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_team_member_meta_fields' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'init', array( $this, 'register_shortcodes' ) );
    }

    /**
     * Register Team Member Custom Post Type
     */
    public function register_team_member_post_type() {
        
        // Define labels for the Team Member CPT
        $labels = array(
            'name'                  => _x( 'Team Members', 'Post type general name', 'team-showcase' ),
            'singular_name'         => _x( 'Team Member', 'Post type singular name', 'team-showcase' ),
            'menu_name'             => _x( 'Team Members', 'Admin Menu text', 'team-showcase' ),
            'name_admin_bar'        => _x( 'Team Member', 'Add New on Toolbar', 'team-showcase' ),
            'add_new'               => __( 'Add New', 'team-showcase' ),
            'add_new_item'          => __( 'Add New Team Member', 'team-showcase' ),
            'new_item'              => __( 'New Team Member', 'team-showcase' ),
            'edit_item'             => __( 'Edit Team Member', 'team-showcase' ),
            'view_item'             => __( 'View Team Member', 'team-showcase' ),
            'all_items'             => __( 'All Team Members', 'team-showcase' ),
            'search_items'          => __( 'Search Team Members', 'team-showcase' ),
            'parent_item_colon'     => __( 'Parent Team Members:', 'team-showcase' ),
            'not_found'             => __( 'No team members found.', 'team-showcase' ),
            'not_found_in_trash'    => __( 'No team members found in Trash.', 'team-showcase' ),
            'featured_image'        => _x( 'Profile Image', 'Overrides the "Featured Image" phrase', 'team-showcase' ),
            'set_featured_image'    => _x( 'Set profile image', 'Overrides the "Set featured image" phrase', 'team-showcase' ),
            'remove_featured_image' => _x( 'Remove profile image', 'Overrides the "Remove featured image" phrase', 'team-showcase' ),
            'use_featured_image'    => _x( 'Use as profile image', 'Overrides the "Use as featured image" phrase', 'team-showcase' ),
            'archives'              => _x( 'Team Member archives', 'The post type archive label', 'team-showcase' ),
            'insert_into_item'      => _x( 'Insert into team member', 'Overrides the "Insert into post" phrase', 'team-showcase' ),
            'uploaded_to_this_item' => _x( 'Uploaded to this team member', 'Overrides the "Uploaded to this post" phrase', 'team-showcase' ),
            'filter_items_list'     => _x( 'Filter team members list', 'Screen reader text for the filter links', 'team-showcase' ),
            'items_list_navigation' => _x( 'Team members list navigation', 'Screen reader text for the pagination', 'team-showcase' ),
            'items_list'            => _x( 'Team members list', 'Screen reader text for the items list', 'team-showcase' ),
        );

        // Define arguments for the Team Member CPT
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'team-showcase-plugin', // Nest under main plugin menu
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'team-member' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'supports'           => array( 'title', 'thumbnail' ),
            'show_in_rest'       => true, // Enable Gutenberg editor support
        );

        // Register the Team Member custom post type
        register_post_type( 'team_member', $args );
    }

    /**
     * Register Team Category Custom Taxonomy
     */
    public function register_team_category_taxonomy() {
        
        // Define labels for the Team Category taxonomy
        $labels = array(
            'name'              => _x( 'Team Categories', 'taxonomy general name', 'team-showcase' ),
            'singular_name'     => _x( 'Team Category', 'taxonomy singular name', 'team-showcase' ),
            'search_items'      => __( 'Search Team Categories', 'team-showcase' ),
            'all_items'         => __( 'All Team Categories', 'team-showcase' ),
            'parent_item'       => __( 'Parent Team Category', 'team-showcase' ),
            'parent_item_colon' => __( 'Parent Team Category:', 'team-showcase' ),
            'edit_item'         => __( 'Edit Team Category', 'team-showcase' ),
            'update_item'       => __( 'Update Team Category', 'team-showcase' ),
            'add_new_item'      => __( 'Add New Team Category', 'team-showcase' ),
            'new_item_name'     => __( 'New Team Category Name', 'team-showcase' ),
            'menu_name'         => __( 'Team Categories', 'team-showcase' ),
            'not_found'         => __( 'No team categories found.', 'team-showcase' ),
            'no_terms'          => __( 'No team categories', 'team-showcase' ),
            'items_list'        => __( 'Team categories list', 'team-showcase' ),
            'items_list_navigation' => __( 'Team categories list navigation', 'team-showcase' ),
        );

        // Define arguments for the Team Category taxonomy
        $args = array(
            'labels'            => $labels,
            'hierarchical'      => true,  // Like categories (can have parent/child)
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,  // Show column in admin post list
            'show_in_nav_menus' => true,
            'show_tagcloud'     => true,
            'show_in_rest'      => true,  // Enable for REST API and Gutenberg
            'rewrite'           => array( 'slug' => 'team-category' ),
            'capabilities'      => array(
                'manage_terms' => 'manage_categories',
                'edit_terms'   => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'edit_posts',
            ),
        );

        // Register the Team Category taxonomy and associate it with team_member post type
        register_taxonomy( 'team_category', array( 'team_member' ), $args );
    }

    /**
     * Add meta boxes for Team Member custom fields
     */
    public function add_team_member_meta_boxes() {
        add_meta_box(
            'team_member_details',
            __( 'Team Member Details', 'team-showcase' ),
            array( $this, 'render_team_member_meta_box' ),
            'team_member',
            'normal',
            'high'
        );
    }

    /**
     * Render the Team Member meta box HTML
     *
     * @param WP_Post $post The current post object
     */
    public function render_team_member_meta_box( $post ) {
        // Add nonce field for security
        wp_nonce_field( 'team_member_meta_box_nonce', 'team_member_meta_box_nonce' );

        // Get current values
        $position = get_post_meta( $post->ID, '_team_member_position', true );
        $email = get_post_meta( $post->ID, '_team_member_email', true );
        $phone = get_post_meta( $post->ID, '_team_member_phone', true );
        $linkedin_url = get_post_meta( $post->ID, '_team_member_linkedin_url', true );
        $profile_image_id = get_post_meta( $post->ID, '_team_member_profile_image_id', true );
        $bio = get_post_meta( $post->ID, '_team_member_bio', true );
        
        // Get profile image URL if ID exists
        $profile_image_url = '';
        if ( $profile_image_id ) {
            $profile_image_url = wp_get_attachment_url( $profile_image_id );
        }
        ?>
        
        <!-- CSS for Tabbed Interface -->
        <style>
        .team-member-tabs {
            display: flex;
            min-height: 400px;
        }
        
        .team-member-tab-nav {
            width: 20%;
            background: #f9f9f9;
            border-right: 1px solid #ddd;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        
        .team-member-tab-nav li {
            margin: 0;
            border-bottom: 1px solid #ddd;
        }
        
        .team-member-tab-nav li:last-child {
            border-bottom: none;
        }
        
        .team-member-tab-nav a {
            display: block;
            padding: 15px 20px;
            text-decoration: none;
            color: #555;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        
        .team-member-tab-nav a:hover {
            background: #eee;
            color: #333;
        }
        
        .team-member-tab-nav li.active a {
            background: #0073aa;
            color: #fff;
        }
        
        .team-member-tab-content {
            width: 80%;
            padding: 20px;
            background: #fff;
        }
        
        .team-member-tab-pane {
            display: none;
        }
        
        .team-member-tab-pane.active {
            display: block;
        }
        
        .team-member-tab-pane .form-table {
            margin-top: 0;
        }
        
        .team-member-tab-pane .form-table th {
            width: 150px;
            padding-left: 0;
        }
        </style>
        
        <!-- Tabbed Interface HTML -->
        <div class="team-member-tabs">
            <!-- Tab Navigation -->
            <ul class="team-member-tab-nav">
                <li class="active">
                    <a href="#" data-tab="general-info"><?php _e( 'General Info', 'team-showcase' ); ?></a>
                </li>
                <li>
                    <a href="#" data-tab="biography"><?php _e( 'Biography', 'team-showcase' ); ?></a>
                </li>
                <li>
                    <a href="#" data-tab="contact-social"><?php _e( 'Contact & Social', 'team-showcase' ); ?></a>
                </li>
                <li>
                    <a href="#" data-tab="profile-visuals"><?php _e( 'Profile Visuals', 'team-showcase' ); ?></a>
                </li>
            </ul>
            
            <!-- Tab Content -->
            <div class="team-member-tab-content">
                
                <!-- General Info Tab -->
                <div id="general-info" class="team-member-tab-pane active">
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="team_member_position"><?php _e( 'Position/Title', 'team-showcase' ); ?></label>
                            </th>
                            <td>
                                <input type="text" 
                                       id="team_member_position" 
                                       name="team_member_position" 
                                       value="<?php echo esc_attr( $position ); ?>" 
                                       class="regular-text" 
                                       placeholder="<?php esc_attr_e( 'e.g., Senior Developer', 'team-showcase' ); ?>" />
                                <p class="description"><?php _e( 'Enter the team member\'s job title or position.', 'team-showcase' ); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Biography Tab -->
                <div id="biography" class="team-member-tab-pane">
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="team_member_bio_editor"><?php _e( 'Bio', 'team-showcase' ); ?></label>
                            </th>
                            <td>
                                <div id="team-member-bio-container">
                                    <?php 
                                    // Configure wp_editor settings
                                    $editor_settings = array(
                                        'textarea_name' => 'team_member_bio',
                                        'media_buttons' => false,
                                        'textarea_rows' => 8,
                                        'teeny'         => true,
                                        'tinymce'       => array(
                                            'toolbar1' => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink,undo,redo',
                                            'toolbar2' => '',
                                            'toolbar3' => ''
                                        ),
                                        'quicktags'     => array(
                                            'buttons' => 'strong,em,link,ul,ol,li'
                                        )
                                    );
                                    
                                    // Display the wp_editor
                                    wp_editor( $bio, 'team_member_bio_editor', $editor_settings );
                                    ?>
                                </div>
                                <p class="description"><?php _e( 'Enter a detailed biography for the team member. You can use basic formatting like bold, italic, and links.', 'team-showcase' ); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Contact & Social Tab -->
                <div id="contact-social" class="team-member-tab-pane">
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="team_member_email"><?php _e( 'Email Address', 'team-showcase' ); ?></label>
                            </th>
                            <td>
                                <input type="email" 
                                       id="team_member_email" 
                                       name="team_member_email" 
                                       value="<?php echo esc_attr( $email ); ?>" 
                                       class="regular-text" 
                                       placeholder="<?php esc_attr_e( 'john.doe@example.com', 'team-showcase' ); ?>" />
                                <p class="description"><?php _e( 'Enter the team member\'s email address.', 'team-showcase' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="team_member_phone"><?php _e( 'Phone Number', 'team-showcase' ); ?></label>
                            </th>
                            <td>
                                <input type="text" 
                                       id="team_member_phone" 
                                       name="team_member_phone" 
                                       value="<?php echo esc_attr( $phone ); ?>" 
                                       class="regular-text" 
                                       placeholder="<?php esc_attr_e( '+1 (555) 123-4567', 'team-showcase' ); ?>" />
                                <p class="description"><?php _e( 'Enter the team member\'s phone number.', 'team-showcase' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="team_member_linkedin_url"><?php _e( 'LinkedIn URL', 'team-showcase' ); ?></label>
                            </th>
                            <td>
                                <input type="url" 
                                       id="team_member_linkedin_url" 
                                       name="team_member_linkedin_url" 
                                       value="<?php echo esc_attr( $linkedin_url ); ?>" 
                                       class="regular-text" 
                                       placeholder="<?php esc_attr_e( 'https://linkedin.com/in/username', 'team-showcase' ); ?>" />
                                <p class="description"><?php _e( 'Enter the team member\'s LinkedIn profile URL.', 'team-showcase' ); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Profile Visuals Tab -->
                <div id="profile-visuals" class="team-member-tab-pane">
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="team_member_profile_image"><?php _e( 'Profile Image', 'team-showcase' ); ?></label>
                            </th>
                            <td>
                                <div class="team-member-profile-image-container">
                                    <input type="hidden" 
                                           id="team_member_profile_image_id" 
                                           name="team_member_profile_image_id" 
                                           value="<?php echo esc_attr( $profile_image_id ); ?>" />
                                    
                                    <div class="team-member-profile-image-preview">
                                        <?php if ( $profile_image_url ) : ?>
                                            <img src="<?php echo esc_url( $profile_image_url ); ?>" 
                                                 alt="<?php esc_attr_e( 'Profile Image Preview', 'team-showcase' ); ?>" 
                                                 style="max-width: 150px; height: auto; display: block; margin-bottom: 10px;" />
                                        <?php else : ?>
                                            <p class="no-image-text"><?php _e( 'No image selected', 'team-showcase' ); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <button type="button" 
                                            class="button team-member-upload-image-btn">
                                        <?php _e( 'Select Profile Image', 'team-showcase' ); ?>
                                    </button>
                                    
                                    <button type="button" 
                                            class="button team-member-remove-image-btn" 
                                            style="<?php echo $profile_image_id ? '' : 'display: none;'; ?>">
                                        <?php _e( 'Remove Image', 'team-showcase' ); ?>
                                    </button>
                                </div>
                                <p class="description"><?php _e( 'Select a profile image for the team member.', 'team-showcase' ); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
                
            </div>
        </div>
        
        <!-- JavaScript for Tab Functionality -->
        <script>
        jQuery(document).ready(function($) {
            // Tab switching functionality
            $('.team-member-tab-nav a').click(function(e) {
                e.preventDefault();
                
                var tabId = $(this).data('tab');
                
                // Remove active class from all tabs and content
                $('.team-member-tab-nav li').removeClass('active');
                $('.team-member-tab-pane').removeClass('active');
                
                // Add active class to clicked tab and corresponding content
                $(this).parent().addClass('active');
                $('#' + tabId).addClass('active');
                
                // Handle wp_editor when Biography tab becomes active
                if (tabId === 'biography') {
                    // Reinitialize TinyMCE if it exists
                    if (typeof tinyMCE !== 'undefined') {
                        var editorId = 'team_member_bio_editor';
                        if (tinyMCE.get(editorId)) {
                            tinyMCE.get(editorId).remove();
                        }
                        setTimeout(function() {
                            tinyMCE.init({
                                selector: '#' + editorId,
                                plugins: 'link',
                                toolbar: 'bold italic underline | alignleft aligncenter alignright | link unlink | undo redo',
                                menubar: false,
                                statusbar: false,
                                height: 200,
                                setup: function(editor) {
                                    editor.on('change', function() {
                                        editor.save();
                                    });
                                }
                            });
                        }, 100);
                    }
                }
            });
            
            // Ensure first tab is active on page load
            $('.team-member-tab-nav li:first-child').addClass('active');
            $('.team-member-tab-pane:first-child').addClass('active');
        });
        </script>
        
        <?php
    }

    /**
     * Save Team Member custom meta fields
     *
     * @param int $post_id The post ID
     */
    public function save_team_member_meta_fields( $post_id ) {
        // Check if this is the team_member post type
        if ( get_post_type( $post_id ) !== 'team_member' ) {
            return;
        }

        // Check if this is an autosave
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Verify nonce
        if ( ! isset( $_POST['team_member_meta_box_nonce'] ) || 
             ! wp_verify_nonce( $_POST['team_member_meta_box_nonce'], 'team_member_meta_box_nonce' ) ) {
            return;
        }

        // Check user permissions
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Define fields to save
        $fields = array(
            'team_member_position' => '_team_member_position',
            'team_member_bio' => '_team_member_bio',
            'team_member_email' => '_team_member_email',
            'team_member_phone' => '_team_member_phone',
            'team_member_linkedin_url' => '_team_member_linkedin_url',
            'team_member_profile_image_id' => '_team_member_profile_image_id'
        );

        // Save each field
        foreach ( $fields as $field_name => $meta_key ) {
            if ( isset( $_POST[ $field_name ] ) ) {
                $value = $_POST[ $field_name ];
                
                // Sanitize based on field type
                switch ( $field_name ) {
                    case 'team_member_bio':
                        $value = wp_kses_post( $value );
                        break;
                    case 'team_member_email':
                        $value = sanitize_email( $value );
                        break;
                    case 'team_member_linkedin_url':
                        $value = esc_url_raw( $value );
                        break;
                    case 'team_member_profile_image_id':
                        $value = absint( $value );
                        break;
                    default:
                        $value = sanitize_text_field( $value );
                        break;
                }
                
                // Update or delete meta
                if ( ! empty( $value ) ) {
                    update_post_meta( $post_id, $meta_key, $value );
                } else {
                    delete_post_meta( $post_id, $meta_key );
                }
            }
        }
    }

    /**
     * Enqueue admin scripts and styles
     *
     * @param string $hook The current admin page hook
     */
    public function enqueue_admin_scripts( $hook ) {
        // Only load on team_member edit screens
        global $post_type;
        if ( ( 'post.php' === $hook || 'post-new.php' === $hook ) && 'team_member' === $post_type ) {
            
            // Enqueue WordPress media uploader scripts
            wp_enqueue_media();
            
            // Enqueue additional media dependencies to ensure everything loads
            wp_enqueue_script( 'media-upload' );
            wp_enqueue_script( 'media-editor' );
            
            // Add our custom media uploader script with proper dependencies
            wp_add_inline_script( 'media-editor', $this->get_media_uploader_script() );
            
            // Add some custom CSS for better tab functionality
            wp_add_inline_style( 'admin-menu', $this->get_admin_styles() );
        }
    }

    /**
     * Get the JavaScript for media uploader functionality
     *
     * @return string The JavaScript code
     */
    private function get_media_uploader_script() {
        return "
        jQuery(document).ready(function($) {
            var teamMediaUploader;
            
            // Function to initialize/reinitialize media uploader
            function initTeamMediaUploader() {
                if (teamMediaUploader) {
                    return teamMediaUploader;
                }
                
                teamMediaUploader = wp.media({
                    title: 'Select Profile Image',
                    button: {
                        text: 'Use this image'
                    },
                    multiple: false,
                    library: {
                        type: 'image'
                    }
                });
                
                // When image is selected
                teamMediaUploader.on('select', function() {
                    var attachment = teamMediaUploader.state().get('selection').first().toJSON();
                    
                    // Set the image ID
                    $('#team_member_profile_image_id').val(attachment.id);
                    
                    // Update preview with proper styling
                    var previewContainer = $('.team-member-profile-image-preview');
                    var imgHtml = '<img src=\"' + attachment.url + '\" alt=\"Profile Image Preview\" style=\"max-width: 150px; height: auto; display: block; margin-bottom: 10px;\" />';
                    previewContainer.html(imgHtml);
                    
                    // Show remove button
                    $('.team-member-remove-image-btn').show();
                });
                
                return teamMediaUploader;
            }
            
            // Upload image button click - using event delegation for tabbed interface
            $(document).on('click', '.team-member-upload-image-btn', function(e) {
                e.preventDefault();
                
                // Initialize media uploader
                var uploader = initTeamMediaUploader();
                
                // Open media uploader
                uploader.open();
            });
            
            // Remove image button click - using event delegation for tabbed interface
            $(document).on('click', '.team-member-remove-image-btn', function(e) {
                e.preventDefault();
                
                // Clear the image ID
                $('#team_member_profile_image_id').val('');
                
                // Update preview
                $('.team-member-profile-image-preview').html('<p class=\"no-image-text\">No image selected</p>');
                
                // Hide remove button
                $(this).hide();
            });
            
            // Reinitialize media uploader when Profile Visuals tab becomes active
            $('.team-member-tab-nav a[data-tab=\"profile-visuals\"]').on('click', function() {
                // Small delay to ensure tab content is visible
                setTimeout(function() {
                    // Reset media uploader to ensure it works in the now-visible tab
                    if (typeof wp !== 'undefined' && wp.media) {
                        // Force media scripts to reinitialize if needed
                        if ($('#team_member_profile_image_id').length > 0) {
                            console.log('Profile Visuals tab activated - media uploader ready');
                        }
                    }
                }, 100);
            });
            
            // Debug logging to help troubleshoot
            if (typeof wp === 'undefined' || !wp.media) {
                console.error('WordPress media scripts not loaded properly');
            } else {
                console.log('Team Showcase media uploader initialized successfully');
            }
        });
        ";
    }

    /**
     * Add admin menu for plugin settings
     */
    public function add_admin_menu() {
        // Add top-level menu page
        add_menu_page(
            __( 'Team Showcase', 'team-showcase' ),           // Page title
            __( 'Team Showcase', 'team-showcase' ),           // Menu title
            'manage_options',                                  // Capability
            'team-showcase-plugin',                           // Menu slug (matches CPT show_in_menu)
            array( $this, 'render_main_page' ),              // Callback function
            'dashicons-groups',                               // Icon
            30                                                // Position
        );

        // Add settings submenu page
        add_submenu_page(
            'team-showcase-plugin',                           // Parent slug (updated to match main menu)
            __( 'Team Showcase Settings', 'team-showcase' ), // Page title
            __( 'Settings', 'team-showcase' ),               // Menu title
            'manage_options',                                 // Capability
            'team-showcase-settings',                         // Menu slug
            array( $this, 'render_settings_page' )          // Callback function
        );
    }

    /**
     * Register plugin settings using WordPress Settings API
     */
    public function register_settings() {
        // Register the main options group
        register_setting(
            'team_showcase_settings_group',
            'team_showcase_options',
            array( $this, 'sanitize_options' )
        );

        // Add settings section
        add_settings_section(
            'team_showcase_general_section',
            __( 'General Display Options', 'team-showcase' ),
            array( $this, 'general_section_callback' ),
            'team-showcase-settings'
        );

        // Display Mode field
        add_settings_field(
            'display_mode',
            __( 'Display Mode', 'team-showcase' ),
            array( $this, 'display_mode_callback' ),
            'team-showcase-settings',
            'team_showcase_general_section'
        );

        // Frontend Template field
        add_settings_field(
            'frontend_template',
            __( 'Frontend Template', 'team-showcase' ),
            array( $this, 'frontend_template_callback' ),
            'team-showcase-settings',
            'team_showcase_general_section'
        );

        // Card Background Color field
        add_settings_field(
            'card_bg_color',
            __( 'Card Background Color', 'team-showcase' ),
            array( $this, 'card_bg_color_callback' ),
            'team-showcase-settings',
            'team_showcase_general_section'
        );

        // Card Text Color field
        add_settings_field(
            'card_text_color',
            __( 'Card Text Color', 'team-showcase' ),
            array( $this, 'card_text_color_callback' ),
            'team-showcase-settings',
            'team_showcase_general_section'
        );

        // Card Border Color field
        add_settings_field(
            'card_border_color',
            __( 'Card Border Color', 'team-showcase' ),
            array( $this, 'card_border_color_callback' ),
            'team-showcase-settings',
            'team_showcase_general_section'
        );

        // Fields to Display section
        add_settings_section(
            'team_showcase_fields_section',
            __( 'Fields to Display', 'team-showcase' ),
            array( $this, 'fields_section_callback' ),
            'team-showcase-settings'
        );

        // Show Position checkbox
        add_settings_field(
            'show_position',
            __( 'Show Position/Title', 'team-showcase' ),
            array( $this, 'show_position_callback' ),
            'team-showcase-settings',
            'team_showcase_fields_section'
        );

        // Show Email checkbox
        add_settings_field(
            'show_email',
            __( 'Show Email Address', 'team-showcase' ),
            array( $this, 'show_email_callback' ),
            'team-showcase-settings',
            'team_showcase_fields_section'
        );

        // Show Phone checkbox
        add_settings_field(
            'show_phone',
            __( 'Show Phone Number', 'team-showcase' ),
            array( $this, 'show_phone_callback' ),
            'team-showcase-settings',
            'team_showcase_fields_section'
        );

        // Show LinkedIn checkbox
        add_settings_field(
            'show_linkedin',
            __( 'Show LinkedIn URL', 'team-showcase' ),
            array( $this, 'show_linkedin_callback' ),
            'team-showcase-settings',
            'team_showcase_fields_section'
        );

        // Show Bio checkbox
        add_settings_field(
            'show_bio',
            __( 'Show Bio', 'team-showcase' ),
            array( $this, 'show_bio_callback' ),
            'team-showcase-settings',
            'team_showcase_fields_section'
        );

        // Show Profile Image checkbox
        add_settings_field(
            'show_profile_image',
            __( 'Show Profile Image', 'team-showcase' ),
            array( $this, 'show_profile_image_callback' ),
            'team-showcase-settings',
            'team_showcase_fields_section'
        );
    }

    /**
     * Sanitize and validate options before saving
     *
     * @param array $input The input options to sanitize
     * @return array The sanitized options
     */
    public function sanitize_options( $input ) {
        $sanitized = array();

        // Sanitize display mode
        if ( isset( $input['display_mode'] ) ) {
            $sanitized['display_mode'] = in_array( $input['display_mode'], array( 'grid', 'list' ) ) ? $input['display_mode'] : 'grid';
        }

        // Sanitize frontend template
        if ( isset( $input['frontend_template'] ) ) {
            $sanitized['frontend_template'] = in_array( $input['frontend_template'], array( 'template_a', 'template_b', 'template_c' ) ) ? $input['frontend_template'] : 'template_a';
        }

        // Sanitize colors (allow hex colors and color names)
        $color_fields = array( 'card_bg_color', 'card_text_color', 'card_border_color' );
        foreach ( $color_fields as $field ) {
            if ( isset( $input[ $field ] ) ) {
                $sanitized[ $field ] = sanitize_text_field( $input[ $field ] );
            }
        }

        // Sanitize checkboxes (fields to display)
        $checkbox_fields = array( 'show_position', 'show_email', 'show_phone', 'show_linkedin', 'show_bio', 'show_profile_image' );
        foreach ( $checkbox_fields as $field ) {
            $sanitized[ $field ] = isset( $input[ $field ] ) ? 1 : 0;
        }

        return $sanitized;
    }

    /**
     * General section callback
     */
    public function general_section_callback() {
        echo '<p>' . __( 'Configure how team members are displayed on the frontend.', 'team-showcase' ) . '</p>';
    }

    /**
     * Fields section callback
     */
    public function fields_section_callback() {
        echo '<p>' . __( 'Choose which team member fields to display on the frontend.', 'team-showcase' ) . '</p>';
    }

    /**
     * Display Mode field callback
     */
    public function display_mode_callback() {
        $options = $this->get_plugin_options();
        $display_mode = isset( $options['display_mode'] ) ? $options['display_mode'] : 'grid';
        ?>
        <fieldset>
            <label>
                <input type="radio" name="team_showcase_options[display_mode]" value="grid" <?php checked( $display_mode, 'grid' ); ?> />
                <?php _e( 'Grid', 'team-showcase' ); ?>
            </label><br>
            <label>
                <input type="radio" name="team_showcase_options[display_mode]" value="list" <?php checked( $display_mode, 'list' ); ?> />
                <?php _e( 'List', 'team-showcase' ); ?>
            </label>
        </fieldset>
        <p class="description"><?php _e( 'Choose how team members are arranged on the page.', 'team-showcase' ); ?></p>
        <?php
    }

    /**
     * Frontend Template field callback
     */
    public function frontend_template_callback() {
        $options = $this->get_plugin_options();
        $frontend_template = isset( $options['frontend_template'] ) ? $options['frontend_template'] : 'template_a';
        ?>
        <fieldset>
            <label>
                <input type="radio" name="team_showcase_options[frontend_template]" value="template_a" <?php checked( $frontend_template, 'template_a' ); ?> />
                <?php _e( 'Template A (Minimal)', 'team-showcase' ); ?>
            </label><br>
            <label>
                <input type="radio" name="team_showcase_options[frontend_template]" value="template_b" <?php checked( $frontend_template, 'template_b' ); ?> />
                <?php _e( 'Template B (Detailed)', 'team-showcase' ); ?>
            </label><br>
            <label>
                <input type="radio" name="team_showcase_options[frontend_template]" value="template_c" <?php checked( $frontend_template, 'template_c' ); ?> />
                <?php _e( 'Template C (Card Style)', 'team-showcase' ); ?>
            </label>
        </fieldset>
        <p class="description"><?php _e( 'Select the visual template for displaying team members.', 'team-showcase' ); ?></p>
        <?php
    }

    /**
     * Card Background Color field callback
     */
    public function card_bg_color_callback() {
        $options = $this->get_plugin_options();
        $card_bg_color = isset( $options['card_bg_color'] ) ? $options['card_bg_color'] : '#ffffff';
        ?>
        <input type="text" 
               name="team_showcase_options[card_bg_color]" 
               value="<?php echo esc_attr( $card_bg_color ); ?>" 
               class="regular-text" 
               placeholder="<?php esc_attr_e( '#ffffff', 'team-showcase' ); ?>" />
        <p class="description"><?php _e( 'Enter a hex color code or color name for card backgrounds.', 'team-showcase' ); ?></p>
        <?php
    }

    /**
     * Card Text Color field callback
     */
    public function card_text_color_callback() {
        $options = $this->get_plugin_options();
        $card_text_color = isset( $options['card_text_color'] ) ? $options['card_text_color'] : '#333333';
        ?>
        <input type="text" 
               name="team_showcase_options[card_text_color]" 
               value="<?php echo esc_attr( $card_text_color ); ?>" 
               class="regular-text" 
               placeholder="<?php esc_attr_e( '#333333', 'team-showcase' ); ?>" />
        <p class="description"><?php _e( 'Enter a hex color code or color name for card text.', 'team-showcase' ); ?></p>
        <?php
    }

    /**
     * Card Border Color field callback
     */
    public function card_border_color_callback() {
        $options = $this->get_plugin_options();
        $card_border_color = isset( $options['card_border_color'] ) ? $options['card_border_color'] : '#dddddd';
        ?>
        <input type="text" 
               name="team_showcase_options[card_border_color]" 
               value="<?php echo esc_attr( $card_border_color ); ?>" 
               class="regular-text" 
               placeholder="<?php esc_attr_e( '#dddddd', 'team-showcase' ); ?>" />
        <p class="description"><?php _e( 'Enter a hex color code or color name for card borders.', 'team-showcase' ); ?></p>
        <?php
    }

    /**
     * Show Position field callback
     */
    public function show_position_callback() {
        $options = $this->get_plugin_options();
        $show_position = isset( $options['show_position'] ) ? $options['show_position'] : 1;
        ?>
        <input type="checkbox" 
               name="team_showcase_options[show_position]" 
               value="1" 
               <?php checked( $show_position, 1 ); ?> />
        <p class="description"><?php _e( 'Display team member job titles/positions.', 'team-showcase' ); ?></p>
        <?php
    }

    /**
     * Show Email field callback
     */
    public function show_email_callback() {
        $options = $this->get_plugin_options();
        $show_email = isset( $options['show_email'] ) ? $options['show_email'] : 1;
        ?>
        <input type="checkbox" 
               name="team_showcase_options[show_email]" 
               value="1" 
               <?php checked( $show_email, 1 ); ?> />
        <p class="description"><?php _e( 'Display team member email addresses.', 'team-showcase' ); ?></p>
        <?php
    }

    /**
     * Show Phone field callback
     */
    public function show_phone_callback() {
        $options = $this->get_plugin_options();
        $show_phone = isset( $options['show_phone'] ) ? $options['show_phone'] : 1;
        ?>
        <input type="checkbox" 
               name="team_showcase_options[show_phone]" 
               value="1" 
               <?php checked( $show_phone, 1 ); ?> />
        <p class="description"><?php _e( 'Display team member phone numbers.', 'team-showcase' ); ?></p>
        <?php
    }

    /**
     * Show LinkedIn field callback
     */
    public function show_linkedin_callback() {
        $options = $this->get_plugin_options();
        $show_linkedin = isset( $options['show_linkedin'] ) ? $options['show_linkedin'] : 1;
        ?>
        <input type="checkbox" 
               name="team_showcase_options[show_linkedin]" 
               value="1" 
               <?php checked( $show_linkedin, 1 ); ?> />
        <p class="description"><?php _e( 'Display team member LinkedIn profile links.', 'team-showcase' ); ?></p>
        <?php
    }

    /**
     * Show Bio field callback
     */
    public function show_bio_callback() {
        $options = $this->get_plugin_options();
        $show_bio = isset( $options['show_bio'] ) ? $options['show_bio'] : 1;
        ?>
        <input type="checkbox" 
               name="team_showcase_options[show_bio]" 
               value="1" 
               <?php checked( $show_bio, 1 ); ?> />
        <p class="description"><?php _e( 'Display team member biographies.', 'team-showcase' ); ?></p>
        <?php
    }

    /**
     * Show Profile Image field callback
     */
    public function show_profile_image_callback() {
        $options = $this->get_plugin_options();
        $show_profile_image = isset( $options['show_profile_image'] ) ? $options['show_profile_image'] : 1;
        ?>
        <input type="checkbox" 
               name="team_showcase_options[show_profile_image]" 
               value="1" 
               <?php checked( $show_profile_image, 1 ); ?> />
        <p class="description"><?php _e( 'Display team member profile images.', 'team-showcase' ); ?></p>
        <?php
    }

    /**
     * Render the main plugin page (top-level menu)
     */
    public function render_main_page() {
        // Check if we should redirect to team members list (optional - can be enabled if desired)
        // wp_redirect( admin_url( 'edit.php?post_type=team_member' ) );
        // exit;
        
        // Get team member count for dashboard stats
        $team_members_count = wp_count_posts( 'team_member' );
        $published_count = isset( $team_members_count->publish ) ? $team_members_count->publish : 0;
        $draft_count = isset( $team_members_count->draft ) ? $team_members_count->draft : 0;
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            
            <!-- Primary Action Banner -->
            <div class="team-showcase-primary-action" style="background: linear-gradient(135deg, #0073aa 0%, #005a87 100%); color: #fff; padding: 25px; border-radius: 8px; margin: 20px 0; text-align: center;">
                <h2 style="color: #fff; margin: 0 0 15px 0;"><?php _e( 'Manage Your Team Members', 'team-showcase' ); ?></h2>
                <p style="margin: 0 0 20px 0; opacity: 0.9;"><?php _e( 'Create, edit, and organize your team member profiles', 'team-showcase' ); ?></p>
                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=team_member' ) ); ?>" class="button button-primary button-hero" style="background: #fff; color: #0073aa; border: none; font-weight: bold;">
                    <?php _e( 'View All Team Members', 'team-showcase' ); ?>
                </a>
                <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=team_member' ) ); ?>" class="button button-hero" style="background: rgba(255,255,255,0.2); color: #fff; border: 1px solid rgba(255,255,255,0.3); margin-left: 10px;">
                    <?php _e( 'Add New Team Member', 'team-showcase' ); ?>
                </a>
            </div>
            
            <div class="team-showcase-dashboard" style="display: flex; gap: 20px; margin-top: 20px;">
                
                <!-- Stats Overview -->
                <div class="team-showcase-stats" style="flex: 1; background: #fff; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,0.04);">
                    <h2><?php _e( 'Team Overview', 'team-showcase' ); ?></h2>
                    <div style="display: flex; gap: 20px; margin-top: 15px;">
                        <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=team_member&post_status=publish' ) ); ?>" style="text-decoration: none; flex: 1;">
                            <div style="text-align: center; padding: 15px; background: #f0f6fc; border-radius: 4px; transition: background 0.2s ease;">
                                <div style="font-size: 24px; font-weight: bold; color: #0073aa;"><?php echo esc_html( $published_count ); ?></div>
                                <div style="font-size: 12px; color: #646970; text-transform: uppercase;"><?php _e( 'Published', 'team-showcase' ); ?></div>
                            </div>
                        </a>
                        <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=team_member&post_status=draft' ) ); ?>" style="text-decoration: none; flex: 1;">
                            <div style="text-align: center; padding: 15px; background: #f6f7f7; border-radius: 4px; transition: background 0.2s ease;">
                                <div style="font-size: 24px; font-weight: bold; color: #646970;"><?php echo esc_html( $draft_count ); ?></div>
                                <div style="font-size: 12px; color: #646970; text-transform: uppercase;"><?php _e( 'Drafts', 'team-showcase' ); ?></div>
                            </div>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="team-showcase-actions" style="flex: 1; background: #fff; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,0.04);">
                    <h2><?php _e( 'Quick Actions', 'team-showcase' ); ?></h2>
                    <div style="margin-top: 15px;">
                        <p>
                            <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=team_member' ) ); ?>" class="button button-primary">
                                <?php _e( 'All Team Members', 'team-showcase' ); ?>
                            </a>
                        </p>
                        <p>
                            <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=team_member' ) ); ?>" class="button">
                                <?php _e( 'Add New Team Member', 'team-showcase' ); ?>
                            </a>
                        </p>
                        <p>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=team-showcase-settings' ) ); ?>" class="button">
                                <?php _e( 'Plugin Settings', 'team-showcase' ); ?>
                            </a>
                        </p>
                    </div>
                </div>
                
            </div>
            
            <!-- Usage Information -->
            <div class="team-showcase-info" style="margin-top: 30px; padding: 20px; background: #f9f9f9; border-left: 4px solid #0073aa;">
                <h3><?php _e( 'How to Display Your Team', 'team-showcase' ); ?></h3>
                <p><?php _e( 'Your team members can be displayed on the frontend in several ways:', 'team-showcase' ); ?></p>
                <ul>
                    <li><strong><?php _e( 'Shortcode:', 'team-showcase' ); ?></strong> <?php _e( 'Use', 'team-showcase' ); ?> <code>[team_members]</code> <?php _e( 'in any post, page, or widget', 'team-showcase' ); ?></li>
                    <li><strong><?php _e( 'Archive Page:', 'team-showcase' ); ?></strong> <?php printf( __( 'Visit %s to see all team members', 'team-showcase' ), '<code>' . esc_url( home_url( '/team-member/' ) ) . '</code>' ); ?></li>
                    <li><strong><?php _e( 'Individual Pages:', 'team-showcase' ); ?></strong> <?php _e( 'Each team member has their own dedicated page', 'team-showcase' ); ?></li>
                </ul>
                
                <h4><?php _e( 'Need Help?', 'team-showcase' ); ?></h4>
                <p><?php _e( 'Configure display settings, colors, and which fields to show in the Settings page. All team member information is managed through the standard WordPress post interface with custom fields in an organized tabbed layout.', 'team-showcase' ); ?></p>
            </div>
        </div>
        <?php
    }

    /**
     * Render the main settings page
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            
            <form action="options.php" method="post">
                <?php
                settings_fields( 'team_showcase_settings_group' );
                do_settings_sections( 'team-showcase-settings' );
                submit_button( __( 'Save Settings', 'team-showcase' ) );
                ?>
            </form>
            
            <div class="team-showcase-usage-info" style="margin-top: 30px; padding: 20px; background: #f9f9f9; border-left: 4px solid #0073aa;">
                <h3><?php _e( 'Usage Instructions', 'team-showcase' ); ?></h3>
                <p><?php _e( 'To display your team members on the frontend, you can:', 'team-showcase' ); ?></p>
                <ul>
                    <li><strong><?php _e( 'Shortcode:', 'team-showcase' ); ?></strong> <?php _e( 'Use', 'team-showcase' ); ?> <code>[team_members]</code> <?php _e( 'in any post, page, or widget', 'team-showcase' ); ?></li>
                    <li><strong><?php _e( 'Template functions:', 'team-showcase' ); ?></strong> <?php _e( 'Use', 'team-showcase' ); ?> <code>do_shortcode('[team_members]')</code> <?php _e( 'in your theme files', 'team-showcase' ); ?></li>
                    <li><strong><?php _e( 'Archive page:', 'team-showcase' ); ?></strong> <?php _e( 'Visit the team member archive page at /team-member/', 'team-showcase' ); ?></li>
                </ul>
                
                <h4><?php _e( 'How to Retrieve Settings in Code:', 'team-showcase' ); ?></h4>
                <pre><code>// Get all plugin options
$options = get_option( 'team_showcase_options', array() );

// Get specific option with default
$display_mode = isset( $options['display_mode'] ) ? $options['display_mode'] : 'grid';
$show_email = isset( $options['show_email'] ) ? $options['show_email'] : 1;

// Or use the helper method (if you extend this class)
$options = $this->get_plugin_options();</code></pre>
            </div>
        </div>
        <?php
    }

    /**
     * Get admin-specific CSS styles
     *
     * @return string CSS styles for admin
     */
    private function get_admin_styles() {
        return "
        /* Team Showcase Admin Styles */
        .team-member-profile-image-container {
            padding: 10px 0;
        }
        
        .team-member-profile-image-preview {
            margin-bottom: 10px;
            min-height: 40px;
            border: 2px dashed #ddd;
            border-radius: 4px;
            padding: 10px;
            text-align: center;
            background: #fafafa;
        }
        
        .team-member-profile-image-preview img {
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .team-member-profile-image-preview .no-image-text {
            color: #666;
            font-style: italic;
            margin: 20px 0;
        }
        
        .team-member-upload-image-btn,
        .team-member-remove-image-btn {
            margin-right: 10px !important;
        }
        
        /* Ensure tab content is properly visible */
        .team-member-tab-pane {
            opacity: 1;
            transition: opacity 0.2s ease;
        }
        
        .team-member-tab-pane:not(.active) {
            opacity: 0;
            pointer-events: none;
        }
        
        /* Fix for media modal within tabs */
        .media-modal {
            z-index: 999999 !important;
        }
        
        /* Debug styles */
        .team-member-debug {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        ";
    }

    /**
     * Register shortcodes
     */
    public function register_shortcodes() {
        add_shortcode( 'team_members', array( $this, 'team_members_shortcode' ) );
    }

    /**
     * Team Members shortcode callback
     *
     * @param array $atts Shortcode attributes
     * @return string HTML output
     */
    public function team_members_shortcode( $atts ) {
        // Get plugin options
        $options = $this->get_plugin_options();
        
        // Query team members
        $team_members = new WP_Query( array(
            'post_type'      => 'team_member',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ) );

        if ( ! $team_members->have_posts() ) {
            return '<p>' . __( 'No team members found.', 'team-showcase' ) . '</p>';
        }

        // Start building HTML output
        $output = '';
        
        // Add CSS styles
        $output .= $this->get_frontend_styles( $options );
        
        // Determine container class based on display mode
        $container_class = ( $options['display_mode'] === 'grid' ) ? 'team-showcase-grid' : 'team-showcase-list';
        
        // Start container
        $output .= '<div class="team-showcase-container ' . esc_attr( $container_class ) . '">';
        
        // Loop through team members
        while ( $team_members->have_posts() ) {
            $team_members->the_post();
            $post_id = get_the_ID();
            
            // Get team member meta data
            $position = get_post_meta( $post_id, '_team_member_position', true );
            $email = get_post_meta( $post_id, '_team_member_email', true );
            $phone = get_post_meta( $post_id, '_team_member_phone', true );
            $linkedin_url = get_post_meta( $post_id, '_team_member_linkedin_url', true );
            $bio = get_post_meta( $post_id, '_team_member_bio', true );
            $profile_image_id = get_post_meta( $post_id, '_team_member_profile_image_id', true );
            
            // Render team member based on template
            $output .= $this->render_team_member( $post_id, $options, array(
                'position'         => $position,
                'email'            => $email,
                'phone'            => $phone,
                'linkedin_url'     => $linkedin_url,
                'bio'              => $bio,
                'profile_image_id' => $profile_image_id,
            ) );
        }
        
        // End container
        $output .= '</div>';
        
        // Reset post data
        wp_reset_postdata();
        
        return $output;
    }

    /**
     * Render individual team member HTML
     *
     * @param int   $post_id Team member post ID
     * @param array $options Plugin options
     * @param array $meta    Team member meta data
     * @return string HTML output for single team member
     */
    private function render_team_member( $post_id, $options, $meta ) {
        $output = '';
        $name = get_the_title( $post_id );
        
        // Card inline styles
        $card_styles = sprintf(
            'background-color: %s; color: %s; border: 1px solid %s;',
            esc_attr( $options['card_bg_color'] ),
            esc_attr( $options['card_text_color'] ),
            esc_attr( $options['card_border_color'] )
        );
        
        // Start team member card
        $output .= '<div class="team-member-card" style="' . $card_styles . '">';
        
        // Profile Image
        if ( $options['show_profile_image'] && $meta['profile_image_id'] ) {
            $output .= '<div class="team-member-image">';
            $output .= wp_get_attachment_image( 
                $meta['profile_image_id'], 
                'medium', 
                false, 
                array( 'alt' => esc_attr( $name ) )
            );
            $output .= '</div>';
        }
        
        // Content wrapper
        $output .= '<div class="team-member-content">';
        
        // Name (always shown)
        $output .= '<h3 class="team-member-name">' . esc_html( $name ) . '</h3>';
        
        // Position
        if ( $options['show_position'] && $meta['position'] ) {
            $output .= '<div class="team-member-position">' . esc_html( $meta['position'] ) . '</div>';
        }
        
        // Template-specific content
        switch ( $options['frontend_template'] ) {
            case 'template_a':
                // Minimal - Name and Position only (already shown above)
                break;
                
            case 'template_b':
                // Detailed - Add Bio and Contact Info
                if ( $options['show_bio'] && $meta['bio'] ) {
                    $output .= '<div class="team-member-bio">' . wp_kses_post( $meta['bio'] ) . '</div>';
                }
                
                // Contact info
                $output .= $this->render_contact_info( $options, $meta );
                break;
                
            case 'template_c':
                // Card Style - Bio first, then contact in a structured way
                if ( $options['show_bio'] && $meta['bio'] ) {
                    $output .= '<div class="team-member-bio">' . wp_kses_post( $meta['bio'] ) . '</div>';
                }
                
                $contact_info = $this->render_contact_info( $options, $meta );
                if ( $contact_info ) {
                    $output .= '<div class="team-member-contact">' . $contact_info . '</div>';
                }
                break;
        }
        
        // End content wrapper and card
        $output .= '</div>'; // .team-member-content
        $output .= '</div>'; // .team-member-card
        
        return $output;
    }

    /**
     * Render contact information based on settings
     *
     * @param array $options Plugin options
     * @param array $meta    Team member meta data
     * @return string HTML output for contact info
     */
    private function render_contact_info( $options, $meta ) {
        $output = '';
        
        // Email
        if ( $options['show_email'] && $meta['email'] ) {
            $output .= '<div class="team-member-email">';
            $output .= '<a href="mailto:' . esc_attr( $meta['email'] ) . '">' . esc_html( $meta['email'] ) . '</a>';
            $output .= '</div>';
        }
        
        // Phone
        if ( $options['show_phone'] && $meta['phone'] ) {
            $output .= '<div class="team-member-phone">';
            $output .= '<a href="tel:' . esc_attr( $meta['phone'] ) . '">' . esc_html( $meta['phone'] ) . '</a>';
            $output .= '</div>';
        }
        
        // LinkedIn
        if ( $options['show_linkedin'] && $meta['linkedin_url'] ) {
            $output .= '<div class="team-member-linkedin">';
            $output .= '<a href="' . esc_url( $meta['linkedin_url'] ) . '" target="_blank" rel="noopener noreferrer">';
            $output .= __( 'LinkedIn Profile', 'team-showcase' );
            $output .= '</a>';
            $output .= '</div>';
        }
        
        return $output;
    }

    /**
     * Get frontend CSS styles
     *
     * @param array $options Plugin options
     * @return string CSS styles
     */
    private function get_frontend_styles( $options ) {
        $styles = '<style type="text/css">
        .team-showcase-container {
            margin: 20px 0;
        }
        
        /* Grid Layout */
        .team-showcase-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 0;
        }
        
        /* List Layout */
        .team-showcase-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .team-showcase-list .team-member-card {
            display: flex;
            align-items: flex-start;
            gap: 20px;
        }
        
        .team-showcase-list .team-member-image {
            flex-shrink: 0;
            width: 120px;
        }
        
        .team-showcase-list .team-member-content {
            flex: 1;
        }
        
        /* Common Card Styles */
        .team-member-card {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .team-member-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .team-member-image {
            text-align: center;
            margin-bottom: 15px;
        }
        
        .team-member-image img {
            max-width: 100%;
            height: auto;
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
        }
        
        .team-showcase-list .team-member-image img {
            width: 120px;
            height: 120px;
        }
        
        .team-member-name {
            margin: 0 0 8px 0;
            font-size: 1.4em;
            font-weight: bold;
        }
        
        .team-member-position {
            font-style: italic;
            margin-bottom: 12px;
            opacity: 0.8;
        }
        
        .team-member-bio {
            margin: 15px 0;
            line-height: 1.5;
        }
        
        .team-member-bio p:last-child {
            margin-bottom: 0;
        }
        
        .team-member-contact {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(0,0,0,0.1);
        }
        
        .team-member-email,
        .team-member-phone,
        .team-member-linkedin {
            margin: 5px 0;
        }
        
        .team-member-email a,
        .team-member-phone a,
        .team-member-linkedin a {
            text-decoration: none;
            color: inherit;
        }
        
        .team-member-email a:hover,
        .team-member-phone a:hover,
        .team-member-linkedin a:hover {
            text-decoration: underline;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .team-showcase-grid {
                grid-template-columns: 1fr;
            }
            
            .team-showcase-list .team-member-card {
                flex-direction: column;
                text-align: center;
            }
            
            .team-showcase-list .team-member-image {
                width: 100%;
            }
        }
        </style>';
        
        return $styles;
    }

    /**
     * Get plugin options with defaults
     *
     * @return array The plugin options with default values
     */
    public function get_plugin_options() {
        $defaults = array(
            'display_mode'        => 'grid',
            'frontend_template'   => 'template_a',
            'card_bg_color'       => '#ffffff',
            'card_text_color'     => '#333333',
            'card_border_color'   => '#dddddd',
            'show_position'       => 1,
            'show_email'          => 1,
            'show_phone'          => 1,
            'show_linkedin'       => 1,
            'show_bio'            => 1,
            'show_profile_image'  => 1,
        );

        $options = get_option( 'team_showcase_options', array() );
        return wp_parse_args( $options, $defaults );
    }
}

// Initialize the plugin
new Team_Showcase_Plugin(); 