<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
	<div class="container-fluid">
	  <div class="pull-right">
		<button type="submit" form="form-smsnot" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
	  	<div class="row">
	  		<div class="col-sm-6">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_description; ?></h3>
	  		</div>
	  		<div class="col-sm-6 text-right">
	  			<div class="btn-group" role="group" aria-label="...">
	  				<button type="button" id="balance" class="btn btn-default" title="<?php echo $text_refresh; ?>"><?php echo $entry_balance; ?> <?php echo $balance; ?></button>
	  				<a href="http://callme.sms.ru/pay.php" target="_blank" class="btn btn-success" title="<?php echo $text_money_add; ?>">+</a>
	  			</div>
	  		</div>
	  	</div>
	  </div>
	  <div class="panel-body">
	  	<?php
	  	foreach ($customer_groups as $value) {
	  		$option_all='<option value="2'.$value['customer_group_id'].'">'.$text_all_group.' '.$value['name'].'</option>';
	  		$option_news='<option value="3'.$value['customer_group_id'].'">'.$text_newsletter_group.' '.$value['name'].'</option>';
	  	}
	  	?>
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-smsnot" class="form-horizontal">
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
					<option value="0"><?php echo $text_all; ?></option>
					<option value="1"><?php echo $text_newsletter; ?></option>
					<?php echo $option_all.$option_news; ?>
				  </select>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-message"><?php echo $entry_message; ?></label>
				<div class="col-sm-10">
					<div class="progress">
					  <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
					</div>
					<div><?php echo $entry_characters; ?> <span id="count">0</span></div>
					<div>SMS: <span id="countSMS">1</span></div>
					<textarea name="input-message" rows="5" placeholder="<?php echo $entry_message; ?>" id="input-message" class="form-control"></textarea>
				</div>
			  </div>
			  <div class="form-group">
			      <div class="col-sm-offset-2 col-sm-10">
			        <button class="btn btn-default" id="send"><?php echo $button_send; ?></button>
			      </div>
			    </div>
			</div>
			<div class="tab-pane fade" id="tab-notice">

			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-enabled"><?php echo $entry_enabled; ?></label>
				<div class="col-sm-10">
					<select name="smsnot-enabled" class="form-control">
						<?php if ($data['smsnot-enabled']) { ?>
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
			  	<label class="col-sm-2 control-label" for="input-message-template"><?php echo $entry_message_template; ?></label>
			  	<div class="col-sm-10">
			  		<textarea name="smsnot-message-template" rows="5" placeholder="<?php echo $entry_message_template; ?>" id="input-message-template" class="form-control"><?php echo $data['smsnot-message-template']; ?></textarea>
			  	</div>
			</div>
			  <div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $entry_to; ?></label>
				<div class="col-sm-10">
				  <div class="well well-sm" style="height: 150px; overflow: auto;">
					<div class="checkbox">
					  <label>
						<?php if ($data['smsnot-new-order']) { ?>
						<input type="checkbox" name="smsnot-new-order" checked="checked" />
						<?php } else { ?>
						<input type="checkbox" name="smsnot-new-order" />
						<?php } ?>
						<?php echo $text_new_order; ?>
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<?php if ($data['smsnot-order-change']) { ?>
						<input type="checkbox" name="smsnot-order-change" checked="checked" />
						<?php } else { ?>
						<input type="checkbox" name="smsnot-order-change" />
						<?php } ?>
						<?php echo $text_order_change; ?>
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<?php if ($data['smsnot-owner']) { ?>
						<input type="checkbox" name="smsnot-owner" checked="checked" />
						<?php } else { ?>
						<input type="checkbox" name="smsnot-owner" />
						<?php } ?>
						<?php echo $text_owner; ?>
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
					  <input name="smsnot-apikey" type="text" placeholder="<?php echo $entry_api_key; ?>" id="input-apikey" class="form-control" value="<?php echo $data['smsnot-apikey']; ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-phone"><?php echo $entry_phone; ?></label>
					<div class="col-sm-10">
					  <input name="smsnot-phone" type="text" placeholder="<?php echo $entry_phone; ?>" id="input-phone" class="form-control digitOnly" value="<?php echo $data['smsnot-phone']; ?>" maxlength="11">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-sender"><?php echo $entry_sender; ?></label>
					<div class="col-sm-10">
					  <input name="smsnot-sender" type="text" placeholder="<?php echo $entry_sender; ?>" id="input-sender" class="form-control" value="<?php echo $data['smsnot-sender']; ?>" maxlength="12">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-6">
						
					</div>
					<div class="col-sm-6">
						<button class="btn btn-default" type="button" id="test_send"><?php echo $button_test; ?></button>
					</div>
				</div>
				<div id="result"></div>

			</div>

		  </div>
		</form>
	  </div>
	</div>
  </div>
<script type="text/javascript">
$( document ).ready(function() {
	$("#input-message").keyup(function() {
		if (/[а-я]/i.test($("#input-message").val()))
			max=70;
		else
			max=140;
		smsc=Math.ceil($("#input-message").val().length/max);
		sm=max*(smsc-1);
		var box=$(this).val();
		var main = (box.length-sm) *100;
		var value= (main / max);
		$('#count').html(box.length);
		$('.progress-bar').animate(
		{
			"width": value+'%',
		}, 1);
		$('#countSMS').html(smsc);
		return false;
	});
	$(".digitOnly").keyup(function (){
		$(this).val($(this).val().replace(/[^\d]/g, ''));
	});

	$("#test_send").click(function(){
			var data="&sender="+$('#input-sender').val()+"&to="+$('#input-phone').val()+"&api="+$('#input-apikey').val()+"&message=test";
			var btn = $(this);
			btn.button('loading');
			$.ajax({
				type: "POST",
				url: "index.php?route=module/smsnot/send&token=<?php echo $token; ?>",
				cache: false,
				data: data,
				success: function(html){
					var jsonData = JSON.parse(html);
					if (jsonData['error'])
						$('#result').html('<div class="alert alert-danger">'+jsonData['text']+'</div>');
					else
					{
						$('#result').html('<div class="alert alert-success">'+jsonData['text']+'</div>');
						$('#balance').html('<?php echo $entry_balance; ?> '+jsonData['balance']);
						btn.button('reset');
					}
				},
			});
		});
});
</script>
</div>
<?php echo $footer; ?>