        <footer class="footer">
            <div class="container text-left">
                <div class="row">
                <span class="text-muted text-left col-md-2">&copy; 2018</span>
                <div class="text-center col-md-8">
                <?php if(!empty($userData)){ ?>
                    <a class="col-md-4" href="http://localhost/profile-import-share/index.php/auth/logout">Logout</a>
                    <a class="col-md-4" href="http://localhost/profile-import-share/index.php/profile/share">Share Profile</a>
                    <a class="col-md-4" href="<?php echo $userData['profile_url']; ?>" target="_blank">View Profile</a>
                    <div class="clear"> </div>
                <?php }?>
                </div>
                </div>
            </div>
        </footer>
    </body>
</html>