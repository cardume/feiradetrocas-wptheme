<form role="search" method="get" id="searchform" action="<?php echo home_url('/'); ?>">
    <div>
    	<input type="text" name="s" id="s" placeholder="<?php _e('Search and hit enter', 'mappress'); ?>" value="<?php if(isset($_GET['s'])) echo $_GET['s']; ?>" />
    </div>
</form>