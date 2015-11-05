<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
	<div class="container-fluid">
	  <div class="pull-right">
		<button type="submit" form="form-category" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
		<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
	  <h1><?php echo $heading_title; ?></h1>
	  <ul class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
		<?php } ?>
	  </ul>
	</div>
  </div>
  <div class="container-fluid">
	<?php if ($error_warning) { ?>
	<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
	  <button type="button" class="close" data-dismiss="alert">&times;</button>
	</div>
	<?php } ?>
	<div class="panel panel-default">
	  <div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_description; ?></h3>
	  </div>
	  <div class="panel-body">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-category" class="form-horizontal">
		  <ul class="nav nav-tabs">
			<li class="active"><a href="#tab-sending" data-toggle="tab"><?php echo $tab_sending; ?></a></li>
			<li><a href="#tab-notice" data-toggle="tab"><?php echo $tab_notice; ?></a></li>
			<li><a href="#tab-gate" data-toggle="tab"><?php echo $tab_gate; ?></a></li>
		  </ul>

		  <div class="tab-content">
			<div class="tab-pane active in" id="tab-sending">
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-to"><?php echo $entry_to; ?></label>
				<div class="col-sm-10">
				  <select name="input_to" id="input-to" class="form-control">
					<option value="0"><?php echo $text_all;?></option>
					<option value="1"><?php echo $text_all_group;?></option>
					<option value="2"><?php echo $text_newsletter_group;?></option>
				  </select>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-message"><?php echo $entry_message; ?></label>
				<div class="col-sm-10">
				  <textarea name="input-message" rows="5" placeholder="<?php echo $entry_message; ?>" id="input-message" class="form-control"></textarea>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-10">
				  <button id="send"><?php echo $button_send; ?></button>
				</div>
			  </div>
			</div>
			<div class="tab-pane fade" id="tab-notice">

			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-enabled"><?php echo $entry_enabled; ?></label>
				<div class="col-sm-10">
					<select name="input-enabled" class="form-control">
						<?php if ($enabled) { ?>
						<option value="<?php echo $enabled; ?>" selected="selected"><?php echo $text_enable; ?></option>
						<option value="<?php echo $enabled; ?>"><?php echo $text_disable; ?></option>
						<?php } else { ?>
						<option value="<?php echo $enabled; ?>"><?php echo $text_enable; ?></option>
						<option value="<?php echo $enabled; ?>" selected="selected"><?php echo $text_disable; ?></option>
						<?php } ?>
					</select>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $entry_to; ?></label>
				<div class="col-sm-10">
				  <div class="well well-sm" style="height: 150px; overflow: auto;">
					<div class="checkbox">
					  <label>
						<?php if ($new_order) { ?>
						<input type="checkbox" name="new_order" value="1" checked="checked" />
						<?php } else { ?>
						<input type="checkbox" name="new_order" value="0" />
						<?php } ?>
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<?php if ($order_change) { ?>
						<input type="checkbox" name="order_change" value="1" checked="checked" />
						<?php } else { ?>
						<input type="checkbox" name="order_change" value="0" />
						<?php } ?>
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<?php if ($text_owner) { ?>
						<input type="checkbox" name="text_owner" value="1" checked="checked" />
						<?php } else { ?>
						<input type="checkbox" name="text_owner" value="0" />
						<?php } ?>
					  </label>
					</div>
				  </div>
				</div>
			  </div>

			</div>

			<div class="tab-pane" id="tab-gate">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-apikey"><?php echo $entry_api_key; ?></label>
					<div class="col-sm-10">
					  <input name="input-apikey" type="text" placeholder="<?php echo $entry_api_key; ?>" id="input-apikey" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-phone"><?php echo $entry_phone; ?></label>
					<div class="col-sm-10">
					  <input name="input-phone" type="number" placeholder="<?php echo $entry_phone; ?>" id="input-phone" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-sender"><?php echo $entry_sender; ?></label>
					<div class="col-sm-10">
					  <input name="input-sender" type="text" placeholder="<?php echo $entry_sender; ?>" id="input-sender" class="form-control">
					</div>
				</div>

			</div>

		  </div>
		</form>
	  </div>
	</div>
  </div>

</div>
<?php echo $footer; ?>