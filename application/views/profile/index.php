<?php
if(!empty($error_msg)){
    echo '<p class="error">'.$error_msg.'</p>';    
}
else{
    echo validation_errors();
    echo form_open('profile/create');
?>
    <div class="container mt-2">
        <div class="text-center">
            <img class="br10" src="<?php echo $userData['picture_url']; ?>" alt=""/>
        </div>
        <div class="col-md-12 mt-3">
        <?php 
            if(!empty($success_message)){
                echo "<div class='text-center'><p class='text-success'>".$success_message."</div>";
            }
        ?>
            <h3>Personal Details</h3>
            <div class="row text-left mt-2">
                <div class="col-md-4 col-md-offset-2">
                    <label for="first_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder value="<?php  echo $userData["first_name"]; ?>" required />
                </div>

                <div class="col-md-4">
                    <label for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder value="<?php  echo $userData["last_name"]; ?>" required />
                </div>
            </div>

            <div class="row mt-2 text-left">
                <div class="col-md-4 col-md-offset-2">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder value="<?php  echo $userData["email"]; ?>" required />
                </div>
                <div class="col-md-4">
                    <label for="current_location">Location</label>
                    <input type="text" class="form-control" id="current_location" name="current_location" placeholder value="<?php  echo $userData["current_location"]; ?>" required />
                </div>
            </div>

            <h3>Experience</h3>
            <div class="row text-left mt-2">
                <div class="col-md-4 col-md-offset-2">
                    <label for="title">Job Title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder value="<?php  echo $position["title"]; ?>" required />
                </div>

                <div class="col-md-4">
                    <label for="company">Company</label>
                    <input type="text" class="form-control" id="company" name="company" placeholder value="<?php  echo $position["company"]; ?>" required />
                </div>
            </div>

            <div class="row mt-2 text-left">
                <div class="col-md-4 col-md-offset-2">
                    <label for="industry">Industry</label>
                    <input type="text" class="form-control" id="industry" name="industry" placeholder value="<?php  echo $userData["industry"]; ?>" required />
                </div>
                <div class="col-md-2">
                    <label for="start_date">From</label>
                    <input type="text" class="form-control" id="start_date" name="start_date" placeholder value="<?php  echo $position["start_date"]; ?>" required />
                </div>
                <div class="col-md-2">
                    <label for="end_date">To</label>
                    <input type="text" class="form-control" id="end_date" name="end_date" placeholder value="<?php  echo $position["end_date"]; ?>" />
                </div>
            </div>
            <input class="btn btn-lg btn-primary mb-3" type="submit" name="submit" value="Save" />
        </div>
    </div>
    
</form>
<?php
}
?>