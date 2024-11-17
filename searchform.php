<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

?>
<form class="search" role="search" method="get" action="<?php echo home_url('/'); ?>">
    <input class="search-input" name="s" maxlength="50" type="text" value="" placeholder="SEARCH AND HIT ENTER..." />
    <input class="search-submit" type="submit" value="">
</form>