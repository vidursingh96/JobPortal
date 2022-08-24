<?php
get_header();
// submit candiate data
if (isset($_POST['submit']))
{
    $current = $_POST['current'];
    $Expected = $_POST['expected'];
    $resume = $_FILES['resume'];
    $user_ID = get_current_user_id();
    $postId = $_POST['postId'];
    if (!function_exists('wp_handle_upload'))
    {
        require_once (ABSPATH . 'wp-admin/includes/file.php');
    }
    $allowedExts = array(
        "pdf",
        "doc",
        "docx"
    );
    $extension = end(explode(".", $_FILES["resume"]["name"]));
    if (!(in_array($extension, $allowedExts)))
    {
        $error = "Please provide doc or pdf file";
    }
    else
    {
        if ($_FILES['resume']['name'] != '')
        {
            $uploadedfile = $_FILES['resume'];
            $upload_overrides = array(
                'test_form' => false
            );

            $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
            $imageurl = "";
            if ($movefile && !isset($movefile['error']))
            {
                $imageurl = $movefile['url'];
            }
            else
            {
                echo $movefile['error'];
            }
        }
    }
    if ($postId && $user_ID && $current && $Expected && @$imageurl)
    {
        add_user_meta($user_ID, 'post_id', $postId);
        $data = array(
            'current' => $current,
            'Expected' => $Expected,
            'userId' => $user_ID,
            'imageurl' => $imageurl,
            'postId' => $postId
        );
        add_post_meta($postId, 'applicant', $data);
    }
}
?>
<h1 class="text-center">JOB DESCRIPTION</h1>
<?php if (current_user_can('administrator') || current_user_can('employer'))
{ ?> 
<a href="<?php echo get_template_directory_uri(); ?>/view-applicant/" class="btn btn-success btn-lg view" target="_blank">View Applicants<a>
<?php
} ?>  
<h1 class="text-center"><?php echo $error; ?></h1><br>
<table class="table align-middle mb-0 bg-white">
  <thead class="bg-light">
    <tr>
      <th>Job Title</th>
      <th>Company</th>
      <th>Location</th>
      <th>Salary</th>
      <th>Details</th>
      <th>Apply</th>
    </tr>
  </thead>
  <?php
if (get_query_var('paged')) $paged = get_query_var('paged');
if (get_query_var('page')) $paged = get_query_var('page');
$role_ids = author_ids_by_role();
$query = new WP_Query(array(
    'post_type' => 'Create Jobs',
    "author__in" => $role_ids,
    'paged' => $paged
));

if ($query->have_posts()): ?>
  <tbody>
    <?php while ($query->have_posts()):
        $query->the_post(); ?>
     <?php $company = get_post_meta(get_the_ID() , 'Company_text'); ?>
     <?php $salary = get_post_meta(get_the_ID() , 'Salary_text'); ?>
     <?php $detail = get_post_meta(get_the_ID() , 'details_textarea'); ?>
    <tr>
      <td>
        <div class="d-flex align-items-center">
          <div class="ms-3">
            <p class="fw-bold text-uppercase mb-1"><?php the_title(); ?></p>
          </div>
        </div>
      </td>
      <td>
        <p class="fw-normal mb-1"><?php foreach ($company as $companies)
        {
            echo $companies;
        } ?></p>
      </td>
      <td>
        <span class="fw-normal mb-1"><?php the_content(); ?></span>
      </td>
      <td><?php foreach ($salary as $salaries)
        {
            echo $salaries;
        } ?></td>
      <td class="fw-normal mb-1"><?php foreach ($detail as $details)
        {
            echo $details;
        } ?></td>
      <td>   
        <button type="button" id="btnOpenForm" postID = "<?php echo get_the_ID(); ?>" class="btn btn-link btn-sm btn-rounded">
          Apply Now
        </button>
      </td>
    </tr>
    <?php
    endwhile;
    wp_reset_postdata(); ?>
<!-- show pagination here -->
<?php
else: ?>
<!-- show 404 error here -->
<?php
endif; ?>

<div class="form-popup-bg">
  <div class="form-container">
    <button id="btnCloseForm" class="close-button">X</button>
    <form action="" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="">Current CTC</label>
        <input type="text" name="current" class="form-control" />
      </div>
      <div class="form-group">
        <label for="">Expected CTC</label>
        <input class="form-control" name="expected" type="text" />
      </div>
      <div class="form-group">
        <label for="">Upload Resume</label>
        <input class="form-control" name="resume" type="file" />
      </div><br>
       <div class="form-group">
        <input type="hidden" class="postID" name="postId">
        <input class="form-control" type="submit" name="submit" />
      </div>
    </form>
  </div>
</div>
   
  </tbody>
</table>
<script type="text/javascript">
  function closeForm() {
  $('.form-popup-bg').removeClass('is-visible');
}

$(document).ready(function($) {
  /* Contact Form Interactions */
  $("body").on( "click", "#btnOpenForm", function() { 
   $('.postID').val($(this).attr('postID'));
    $('.form-popup-bg').addClass('is-visible');
  });
  
    //close popup when clicking x or off popup
  $('.form-popup-bg').on('click', function(event) {
    if ($(event.target).is('.form-popup-bg') || $(event.target).is('#btnCloseForm')) {
      event.preventDefault();
      $(this).removeClass('is-visible');
    }
  });
  
  });
</script>
<?php get_footer(); ?>
