<?php
if(!empty($error_msg)){
    echo '<p class="error">'.$error_msg.'</p>';    
}

if(!empty($userData)){ ?>
    <div class="login-form">
        <div class="head">
            <img src="<?php echo $userData['picture_url']; ?>" alt=""/>
        </div>
        <div class="content">

            <p>Name : <?php echo $userData['first_name'].' '.$userData['last_name']; ?></p>

            <p>Email : <?php echo $userData['email']; ?></p>


            <p>Location : <?php echo $userData['current_location']; ?></p>

            <p>Industry : <?php echo $userData['industry']; ?></p>

            <p>Summary : <?php echo $userData['summary']; ?></p>

            <p>Specialties : <?php echo $userData['specialties']; ?></p>

            <p>Title : <?php echo $position['title']; ?></p>

            <p>Company : <?php echo $position['company']; ?></p>

            <p>Role : <?php echo $position['summary']; ?></p>

            <p>Start Date : <?php echo $position['start_date']; ?></p>

            <p>End Date : <?php echo $position['end_date']; ?></p>

        <div class="foot">
            <a href="<?php echo 'http://localhost/profile-import-share/index.php/'.'auth/logout'; ?>">Logout</a>
            <a href="<?php echo $userData['profile_url']; ?>" target="_blank">View Profile</a>
            <div class="clear"> </div>
        </div>
        </div>
    </div>
<?php
}else{
    echo '<div class="linkedin_btn"><a href="'.$oauthURL.'"><img src="'.base_url().'assets/images/sign-in-with-linkedin.png" /></a></div>';
}
?>