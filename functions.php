<?php
// Register Custom Post Type
function CreateJob()
{

    $labels = array(
        'name' => _x('Create Jobs', 'JobPortal') ,
        'singular_name' => _x('Create Job', 'JobPortal') ,
        'menu_name' => __('Create Jobs', 'JobPortal') ,
        'name_admin_bar' => __('Create Jobs', 'JobPortal') ,
        'archives' => __('Job Archives', 'JobPortal') ,
        'attributes' => __('Job Attributes', 'JobPortal') ,
        'parent_item_colon' => __('Parent Job:', 'JobPortal') ,
        'all_items' => __('All Jobs', 'JobPortal') ,
        'add_new_item' => __('Add New Job', 'JobPortal') ,
        'add_new' => __('Add New', 'JobPortal') ,
        'new_item' => __('New Job', 'JobPortal') ,
        'edit_item' => __('Edit Job', 'JobPortal') ,
        'update_item' => __('Update Job', 'JobPortal') ,
        'view_item' => __('View Job', 'JobPortal') ,
        'view_items' => __('View Job', 'JobPortal') ,
        'search_items' => __('Search Job', 'JobPortal') ,
        'not_found' => __('Not found', 'JobPortal') ,
        'not_found_in_trash' => __('Not found in Trash', 'JobPortal') ,
        'featured_image' => __('Featured Image', 'JobPortal') ,
        'set_featured_image' => __('Set featured image', 'JobPortal') ,
        'remove_featured_image' => __('Remove featured image', 'JobPortal') ,
        'use_featured_image' => __('Use as featured image', 'JobPortal') ,
        'insert_into_item' => __('Insert into item', 'JobPortal') ,
        'uploaded_to_this_item' => __('Uploaded to this item', 'JobPortal') ,
        'items_list' => __('Job list', 'JobPortal') ,
        'items_list_navigation' => __('Job list navigation', 'JobPortal') ,
        'filter_items_list' => __('Filter Job list', 'JobPortal') ,
    );
    $args = array(
        'label' => __('Create Job', 'JobPortal') ,
        'description' => __('Post Type Description', 'JobPortal') ,
        'labels' => $labels,
        'supports' => array(
            'title',
            'editor'
        ) ,
        'taxonomies' => array(
            'category',
            'post_tag'
        ) ,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'page',
    );

    register_post_type('Create Jobs', $args);

}
add_action('init', 'CreateJob', 0);

//Remove Roles
remove_role('subscriber');
remove_role('editor');
remove_role('contributor');
remove_role('author');

// Add candidate Role
add_role('candidate', __('Candidate') , array(
    'read' => true, // Allows a user to read
    
));

// Add employee Role
add_role('employer', __('Employer') , array(
    'read' => true, // Allows a user to read
    'create_posts' => true, // Allows user to create new posts
    'edit_posts' => true, // Allows user to edit their own posts
    'edit_others_posts' => true, // Allows user to edit others posts too
    'publish_posts' => true, // Allows the user to publish posts
    'manage_categories' => true, // Allows user to manage post categories
    
));

// $subs->add_cap('createjobs');
// add_submenu_page('some-parent-slug', 'Events', 'Colloqui', 'createjobs', 'events', 'ww_events');
function author_ids_by_role()
{
    global $current_user;

    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);

    $ids = get_users(array(
        'role' => $user_role,
        'fields' => 'ID'
    ));

    return $ids;
}

add_action('admin_head-edit.php', 'job_view_button');

/**
 * Adds "job_view_button" button on module list page
 */
function job_view_button()
{
    global $current_screen;

    // Not our post type, exit earlier
    // You can remove this if condition if you don't have any specific post type to restrict to.
    if ('createjobs' != $current_screen->post_type)
    {
        return;
    }

?>
        <script type="text/javascript">
            jQuery(document).ready( function($)
            {
               
            jQuery(function () { 
             jQuery('hr.wp-header-end').before("<a id='doc_popup' class='add-new-h2'>View Applicants</a>");
             jQuery("a#doc_popup").attr('href','/job-portal/view-applicant'); 
             jQuery("a#doc_popup").attr('target','_blank');                     
         });                 
      });
        </script>
    <?php
}

/**
 * Meta Box details
 */
require get_template_directory() . '/inc/meta-box.php';
?>
