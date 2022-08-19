<?php
// enqueue style sheet
function my_scripts() {

    wp_enqueue_style( 'styles', get_template_directory_uri() . '/style.css');
}
add_action( 'wp_enqueue_scripts', 'my_scripts' );

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

// Hook
add_action('admin_menu', 'add_job_cpt_submenu_example');

//admin_menu callback function
function add_job_cpt_submenu_example()
{

    add_submenu_page('edit.php?post_type=createjobs', //$parent_slug
    'View Applicants', //$page_title
    'View Applicants', //$menu_title
    'manage_options', //$capability
    'view_applicants', //$menu_slug
    'job_subpage_render_page'
    //$function
    );

}

//add_submenu_page callback function
function job_subpage_render_page()
{

    echo '<h1 style="text-align:center;">View Applicants</h1>

<style>
  table {
  border: 1px solid #ccc;
  border-collapse: collapse;
  margin: 0;
  padding: 0;
  width: 100%;
  table-layout: fixed;
}

table caption {
  font-size: 1.5em;
  margin: .5em 0 .75em;
}

table tr {
  background-color: #f8f8f8;
  border: 1px solid #ddd;
  padding: .35em;
}

table th,
table td {
  padding: .625em;
  text-align: center;
}

table th {
  font-size: .85em;
  letter-spacing: .1em;
  text-transform: uppercase;
}
</style>
<table>
  <thead>
    <tr>
      <th scope="col">Job Title</th>
      <th scope="col">Candidate Name</th>
      <th scope="col">Email</th>
      <th scope="col">Current Salary</th>
      <th scope="col">Expected Salary</th>
      <th scope="col">Resume</th>
    </tr>
  </thead>
  <tbody>';
?>
   <?php
    $query = new WP_Query(array(
        'post_type' => 'createjobs',
        'meta_query' => array(
            array(
                'key' => 'applicant',
            )
        ) ,
    ));
    if ($query->have_posts())
    {
        while ($query->have_posts())
        {
            $query->the_post();
            $data = get_post_meta(get_the_ID() , 'applicant');
            foreach ($data as $datas)
            {
?>
     <tr>
     <?php
                $displayname = get_user_by('id', $datas['userId'])->display_name;
                $email = get_user_by('id', $datas['userId'])->user_email;
?>
      <td data-label="Job Title"><?php echo get_the_title(get_the_ID()); ?></td>
      <td data-label="Candidate Name"><?php echo $displayname; ?></td>
      <td data-label="Candidate Name"><?php echo $email; ?></td>
      <td data-label="Current Salary"><?php echo $datas['current']; ?></td>
      <td data-label="Expected Salary"><?php echo $datas['Expected']; ?></td>
      <td data-label="Resume"><a href="<?php echo $datas['imageurl']; ?>" target="_blank">Resume</a></td>
      </tr>
   <?php
            }
        }
        wp_reset_postdata();
    }
    echo '</tbody>
</table>';
}

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

function author_ids_by_role() {
        global $current_user;

        $user_roles = $current_user->roles;
        $user_role = array_shift($user_roles);

        $ids = get_users(array('role' => $user_role ,'fields' => 'ID'));

        return $ids;
}

/**
 * Meta Box details
 */
require get_template_directory() . '/inc/meta-box.php';
?>
