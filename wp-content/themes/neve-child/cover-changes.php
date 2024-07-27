<?php

// Change the context of event-pages to page, such that event-pages get a cover-photo as well.
add_filter( 'neve_context_filter', function ($context) { return $context === 'event' ? 'page' : $context; }, 1, 1 );

// Add last updated time before page titles
add_action( 'neve_before_post_title', function() { echo '<div id="last-modified-text">' . pll__("Last updated:", "WordPress") . ' <span id="last-modified-date">' . get_the_modified_date() . '</span></div>'; }, 10, 0 );