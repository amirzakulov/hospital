<div class="account-page">
    <div class="account-center">
        <div class="account-box">

            <div class="account-logo">
                <a href="javascript:(0);"><img src="<?= site_url("assets/admin/img/logo-dark.png") ?>" alt=""></a>
            </div>
            <span class="text-danger"><?php echo $this->session->flashdata('message');?></span>
            <?php echo form_open('',array('class'=>'form-signin'));?>
                <div class="form-group">
                    <?php echo form_label('Username','identity');?>
                    <?php echo form_error('identity');?>
                    <?php echo form_input($identity);?>
                </div>
                <div class="form-group">
                    <?php echo form_label('Password','password');?>
                    <?php echo form_error('password');?>
                    <?php echo form_password('password','','class="form-control"');?>
                </div>
                <div class="form-group text-right">
                    <a href="forgot-password.html">Forgot your password?</a>
                </div>
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary account-btn">Login</button>
                </div>
                <div class="text-center register-link">
                    Don’t have an account? <a href="register.html">Register Now</a>
                </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>
