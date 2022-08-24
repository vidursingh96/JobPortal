<?php
/*
Template Name: View Applicant
*/
get_header();
?>
<style type="text/css">
  
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
  <tbody>
    <h1 class="text-center">View Applicants</h1>
   <?php
$current_user = wp_get_current_user();
$query = new WP_Query(array(
    'post_type' => 'createjobs',
    'author' => $current_user->ID,
    'meta_query' => array(
        array(
            'key' => 'applicant',
        ) ,
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
get_footer(); ?>
