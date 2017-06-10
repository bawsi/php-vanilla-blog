<?php if ($msg->hasMessages()): ?>
	<!-- Error messages -->
	<br>
	<div class="container error-container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<?php $msg->display(); ?>
			</div>
		</div>
	</div>
<?php endif; ?>
