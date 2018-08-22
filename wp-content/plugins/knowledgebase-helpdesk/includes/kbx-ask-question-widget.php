<?php

global $kbx_options;

if( !is_admin() ){

	if( isset($kbx_options['enable_question_widget']) && $kbx_options['enable_question_widget'] == '1' )
	{
		add_action('wp_footer', 'kbx_enable_question_widget_func');
	}

}

function kbx_enable_question_widget_func()
{
	?>

	  <!-- Modal HTML embedded directly into document -->
	  <div id="kbx-aq-modal" style="display:none;">
	    <div class="kbx-aq-form">
	    	<form action="">
	    		<div class="kbx-form-group">
	    			<label for="">Question Title</label>
	    			<input type="text" name="kbx-aq-title" id="kbx-aq-title" class="kbx-form-control">
	    		</div>
	    		<div class="kbx-form-group">
	    			<button type="submit" class="kbx-btn kbx-btn-default">Submit</button>
	    		</div>
	    	</form>
	    </div>
	  </div>

	  <!-- Link to open the modal -->
	  <p>
	  	<a href="#kbx-aq-modal">Ask Question</a>
	  </p>

	<?php
}