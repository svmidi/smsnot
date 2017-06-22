<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-smsnot" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
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
						<a href="http://svmidi.sms.ru/pay.php" target="_blank" class="btn btn-success" title="<?php echo $text_money_add; ?>">+</a>
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
			<li><a href="#tab-log" data-toggle="tab"><?php echo $tab_log; ?></a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active in" id="tab-sending">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-to"><?php echo $entry_to; ?></label>
					<div class="col-sm-10">
						<select name="input_to" id="input-to" class="form-control">
							<option value="0"><?php echo $text_all; ?></option>
							<option value="4"><?php echo $text_arbitrary; ?></option>
							<option value="1"><?php echo $text_newsletter; ?></option>
						<?php echo $option_all.$option_news; ?>
						</select>
					</div>
				</div>
				<div class="form-group hide" id="arbitrary">
					<label class="col-sm-2 control-label" for="input-arbitrary">
						<span data-toggle="tooltip" data-original-title="<?php echo $help_arbitrary; ?>"><?php echo $entry_arbitrary; ?></span>
					</label>
					<div class="col-sm-10">
						<input name="input_arbitrary" id="input-arbitrary" class="form-control digitOnly" value="" placeholder="<?php echo $entry_arbitrary; ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-message">
						<span data-toggle="tooltip" data-original-title="<?php echo $help_message; ?>"><?php echo $entry_message; ?></span>
					</label>
					<div class="col-sm-10">
						<div class="progress">
						  <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div><?php echo $entry_characters; ?> <span id="count">0</span></div>
								<div>SMS: <span id="countSMS">1</span></div>
							</div>
							<div class="col-sm-6 btn-group" role="group">
								<button class="btn btn-default btni mas" type="button" data-insert="{StoreName}" data-target="input-message"><?php echo $button_storename; ?></button>
								<button class="btn btn-default btni mas" type="button" data-insert="{Name}" data-target="input-message"><?php echo $button_name; ?></button>
								<button class="btn btn-default btni mas" type="button" data-insert="{LastName}" data-target="input-message"><?php echo $button_lastname; ?></button>
							</div>
						</div>
						<textarea name="input-message" rows="5" placeholder="<?php echo $entry_message; ?>" id="input-message" class="form-control"></textarea>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button class="btn btn-default" type="button" id="send"><?php echo $button_send; ?></button>
					</div>
				</div>
				<div id="multi-result"></div>
			</div>

			<div class="tab-pane fade" id="tab-notice">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-enabled"><?php echo $entry_enabled; ?></label>
					<div class="col-sm-10">
						<select name="smsnot-enabled" class="form-control">
							<?php if ($data['smsnot-enabled']) { ?>
							<option value="1" selected="selected"><?php echo $text_enable; ?></option>
							<option value="0"><?php echo $text_disable; ?></option>
							<?php } else { ?>
							<option value="1"><?php echo $text_enable; ?></option>
							<option value="0" selected="selected"><?php echo $text_disable; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-message-template">
						<span data-toggle="tooltip" data-original-title="<?php echo $help_message_template; ?>"><?php echo $entry_message_template; ?></span>
					</label>
					<div class="col-sm-10">
						<div class="btn-group-xs btn-group" role="group">
							<button class="btn btn-default btni" type="button" data-insert="{OrderID}" data-target="input-message-template"><?php echo $button_orderid; ?></button>
							<button class="btn btn-default btni" type="button" data-insert="{Status}" data-target="input-message-template"><?php echo $button_status; ?></button>
							<button class="btn btn-default btni" type="button" data-insert="{StoreName}" data-target="input-message-template"><?php echo $button_storename; ?></button>
							<button class="btn btn-default btni" type="button" data-insert="{FirstName}" data-target="input-message-template"><?php echo $button_name; ?></button>
							<button class="btn btn-default btni" type="button" data-insert="{LastName}" data-target="input-message-template"><?php echo $button_lastname; ?></button>
							<button class="btn btn-default btni" type="button" data-insert="{Comment}" data-target="input-message-template"><?php echo $button_comment; ?></button>
						</div>
						<textarea name="smsnot-message-template" rows="5" placeholder="<?php echo $entry_message_template; ?>" id="input-message-template" class="form-control"><?php echo $data['smsnot-message-template']; ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-message-customer">
						<span data-toggle="tooltip" data-original-title="<?php echo $help_message_customer; ?>"><?php echo $entry_message_customer; ?></span>
					</label>
					<div class="col-sm-10">
						<div class="btn-group-xs btn-group" role="group">
							<button class="btn btn-default btni" type="button" data-insert="{OrderID}" data-target="input-message-customer"><?php echo $button_orderid; ?></button>
							<button class="btn btn-default btni" type="button" data-insert="{StoreName}" data-target="input-message-customer"><?php echo $button_storename; ?></button>
							<button class="btn btn-default btni" type="button" data-insert="{Total}" data-target="input-message-customer"><?php echo $button_total; ?></button>
							<button class="btn btn-default btni" type="button" data-insert="{FirstName}" data-target="input-message-customer"><?php echo $button_name; ?></button>
							<button class="btn btn-default btni" type="button" data-insert="{LastName}" data-target="input-message-customer"><?php echo $button_lastname; ?></button>
						</div>
						<textarea name="smsnot-message-customer" rows="5" placeholder="<?php echo $entry_message_customer; ?>" id="input-message-customer" class="form-control"><?php echo $data['smsnot-message-customer']; ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-message-admin">
						<span data-toggle="tooltip" data-original-title="<?php echo $help_message_admin; ?>"><?php echo $entry_message_admin; ?></span>
					</label>
					<div class="col-sm-10">
						<div class="btn-group-xs btn-group" role="group">
							<button class="btn btn-default btni" type="button" data-insert="{OrderID}" data-target="input-message-admin"><?php echo $button_orderid; ?></button>
							<button class="btn btn-default btni" type="button" data-insert="{StoreName}" data-target="input-message-admin"><?php echo $button_storename; ?></button>
							<button class="btn btn-default btni" type="button" data-insert="{Total}" data-target="input-message-admin"><?php echo $button_total; ?></button>
							<button class="btn btn-default btni" type="button" data-insert="{FirstName}" data-target="input-message-admin"><?php echo $button_name; ?></button>
							<button class="btn btn-default btni" type="button" data-insert="{LastName}" data-target="input-message-admin"><?php echo $button_lastname; ?></button>
							<button class="btn btn-default btni" type="button" data-insert="{City}" data-target="input-message-admin"><?php echo $button_city; ?></button>
							<button class="btn btn-default btni" type="button" data-insert="{Address}" data-target="input-message-admin"><?php echo $button_address; ?></button>
							<button class="btn btn-default btni" type="button" data-insert="{Phone}" data-target="input-message-admin"><?php echo $button_phone; ?></button>
							<button class="btn btn-default btni" type="button" data-insert="{Comment}" data-target="input-message-admin"><?php echo $button_comment; ?></button>
						</div>
						<textarea name="smsnot-message-admin" rows="5" placeholder="<?php echo $entry_message_admin; ?>" id="input-message-admin" class="form-control"><?php echo $data['smsnot-message-admin']; ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo $entry_to; ?></label>
					<div class="col-sm-10">
						<div class="well well-sm" style="height: 150px; overflow: auto;">
							<div class="checkbox">
								<label>
								<?php if ((isset($data['smsnot-new-order'])) AND ($data['smsnot-new-order'])) { ?>
								<input type="checkbox" name="smsnot-new-order" checked="checked" />
								<?php } else { ?>
								<input type="checkbox" name="smsnot-new-order" />
								<?php } ?>
								<?php echo $text_new_order; ?>
								</label>
							</div>
							<div class="checkbox">
								<label>
								<?php if ((isset($data['smsnot-order-change'])) AND ($data['smsnot-order-change'])) { $notice = ""; ?>
								<input type="checkbox" name="smsnot-order-change" checked="checked" />
								<?php } else { $notice = " disabled"; ?>
								<input type="checkbox" name="smsnot-order-change" />
								<?php } ?>
								<?php echo $text_order_change; ?>
								</label>
							</div>
							<div class="checkbox" style="padding-left:20px">
								<label>
								<?php if ((isset($data['smsnot-order-change-notice'])) AND ($data['smsnot-order-change-notice'])) { ?>
								<input type="checkbox" name="smsnot-order-change-notice" checked="checked"  <?php echo $notice; ?>/>
								<?php } else { ?>
								<input type="checkbox" name="smsnot-order-change-notice" <?php echo $notice; ?>/>
								<?php } ?>
								<?php echo $text_order_change_notice; ?>
								</label>
							</div>
							<div class="checkbox">
								<label>
								<?php if ((isset($data['smsnot-owner'])) AND ($data['smsnot-owner'])) { ?>
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
					<label class="col-sm-2 control-label" for="input-phone">
						<span data-toggle="tooltip" data-original-title="<?php echo $help_phone; ?>"><?php echo $entry_phone; ?>
					</label>
					<div class="col-sm-10">
						<input name="smsnot-phone" type="text" placeholder="<?php echo $entry_phone; ?>" id="input-phone" class="form-control digitOnly" value="<?php echo $data['smsnot-phone']; ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-sender"><?php echo $entry_sender; ?></label>
					<div class="col-sm-10">
						<input name="smsnot-sender" type="text" placeholder="<?php echo $entry_sender; ?>" id="input-sender" class="form-control" value="<?php echo $data['smsnot-sender']; ?>" maxlength="12">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-sender-log"><?php echo $entry_smsnot_log; ?> </label>
					<div class="col-sm-10">
						<div class="checkbox">&nbsp;&nbsp;&nbsp;
							<?php if ((isset($data['smsnot-log'])) AND ($data['smsnot-log'])) { $show_help = 'show'; ?>
							<input type="checkbox" id="input-smsnot-log" name="smsnot-log" checked="checked" />
							<?php } else { $show_help = 'hidden';?>
							<input type="checkbox" id="input-smsnot-log" name="smsnot-log" />
							<?php } ?>
						</div>
						<div id="help-callback" class="<?php echo $show_help; ?>">
						<?php echo $help_callback; ?>
						<div class="well text-center"><?php echo $callback; ?></div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-6 text-right">
						<a href="http://svmidi.sms.ru/?panel=register" target="_blank" class="btn btn-success"><?php echo $button_refer; ?></a>
					</div>
					<div class="col-sm-6">
						<button class="btn btn-default" type="button" id="test_send"><?php echo $button_test; ?></button>
					</div>
				</div>
				<div id="result"></div>
			</div>

		</form>
			<div class="tab-pane" id="tab-log">
			

			<?php if ((isset($data['smsnot-log'])) AND ($data['smsnot-log'])) { ?>
			<div class="well">
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label" for="input-phone"><?php echo $entry_phone; ?></label>
							<input type="phone" name="filter_phone" value="" placeholder="<?php echo $entry_phone; ?>" id="input-phone" class="form-control">
						</div>
						<div class="form-group">
							<label class="control-label" for="input-text"><?php echo $entry_text; ?></label>
							<input type="text" name="filter_text" value="" placeholder="<?php echo $entry_text; ?>" id="input-text" class="form-control" autocomplete="off">
						</div>
					</div>
					<div class="col-sm-1"></div>
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
							<div class="input-group date">
								<input type="text" name="filter_date_start" value="" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
								<span class="input-group-btn">
								<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
								</span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label" for="input-date-stop"><?php echo $entry_date_stop; ?></label>
							<div class="input-group date">
								<input type="text" name="filter_date_stop" value="" placeholder="<?php echo $entry_date_stop; ?>" data-date-format="YYYY-MM-DD" id="input-date-stop" class="form-control" />
								<span class="input-group-btn">
								<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
								</span>
							</div>
						</div>
					</div>
					<div class="col-sm-1"></div>
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
							<select name="filter_status" id="input-status" class="form-control">
								<option value="*">*</option>
								<?php foreach ($statuses as $key => $title) {
								echo '<option value="'.$key.'">'.$title.'</option>';
								} ?>
							</select>
						</div>
						<div class="form-group">
							<p>&nbsp;</p>
							<button type="button" id="button-filte" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
						</div>
					</div>
				</div>
			</div>

				<div id="log"></div>
			<?php } else { ?>
				<div class="alert alert-info"><?php echo $text_log_disabled; ?></p>
			<?php } ?>
			</div>
		</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {

	$("#input-smsnot-log").on('click change', function() {
		if ($("#input-smsnot-log").prop("checked")) {
			$("#help-callback").addClass('show');
			$("#help-callback").removeClass('hidden');
		} else {
			$("#help-callback").addClass('hidden');
			$("#help-callback").removeClass('show');
		}
	});

	$("input[name=smsnot-order-change]").change(function(){
		if ($(this).is(':checked')) {
			$("input[name=smsnot-order-change-notice]").prop('disabled', false);
		} else {
			$("input[name=smsnot-order-change-notice]").prop('disabled', true);
			$("input[name=smsnot-order-change-notice]").prop('checked', false);
		}
	});

	$("#input-message").keyup(function() {
		max = (/[а-я]/i.test($("#input-message").val()))?70:140;
		smsc = Math.ceil($("#input-message").val().length/max);
		sm = max * (smsc - 1);
		var box = $(this).val();
		var main = (box.length - sm) * 100;
		var value = (main / max);
		$('#count').html(box.length);
		$('.progress-bar').animate({"width": value + '%'}, 1);
		$('#countSMS').html(smsc);
		return false;
	});

	$(".digitOnly").keyup(function() {
		$(this).val($(this).val().replace(/[^\d,]/g, ''));
	});

	$("#test_send").click(function() {
		var data = "&sender="+$('#input-sender').val()+"&to="+$('#input-phone').val()+"&api="+$('#input-apikey').val()+"&message=test";
		var btn = $(this);
		btn.button('loading');
		$.ajax({
			type: "POST",
			url: "index.php?route=extension/module/smsnot/send&token=<?php echo $token; ?>",
			cache: false,
			data: data,
			success: function(html) {
				var jsonData = JSON.parse(html);
				if (jsonData['error'] != 100) {
					$('#result').html('<div class="alert alert-danger">'+jsonData['text']+'</div>');
				} else {
					$('#result').html('<div class="alert alert-success">'+jsonData['text']+'</div>');
					$('#balance').html('<?php echo $entry_balance; ?> '+jsonData['balance']);
				}
				btn.button('reset');
			},
		});
	});

	$("#balance").click(function() {
		var data = "&api="+$('#input-apikey').val();
		var btn = $(this);
		$.ajax({
			type: "POST",
			url: "index.php?route=extension/module/smsnot/balance&token=<?php echo $token; ?>",
			cache: false,
			data: data,
			success: function(html) {
				var jsonData = JSON.parse(html);
				if (jsonData['error']) {
					$('#result').html('<div class="alert alert-danger">'+jsonData['text']+'</div>');
				} else {
					$('#balance').html('<?php echo $entry_balance; ?> '+jsonData['balance']);
				}
			},
		});
	});

	$('#input-to').change(function() {
		if ($(this).val() == 4) {
			$("#arbitrary").removeClass('hide');
			$("#arbitrary").addClass('show');
			$('.mas').addClass('disabled');
		} else {
			$("#arbitrary").removeClass('show');
			$("#arbitrary").addClass('hide');
			$('.mas').removeClass('disabled');
		}
	});

	$("#send").click(function() {
		if (($('#input-to option:selected').val() == 4) && ($('#input-arbitrary').val().length < 11)) {
			$('#arbitrary').addClass('has-error');
		} else {
			$('#arbitrary').removeClass('has-error');
			var data = "&sender="+$('#input-sender').val()+"&api="+$('#input-apikey').val()+"&message="+$('#input-message').val()+"&to="+$('#input-to option:selected').val()+"&arbitrary="+$('#input-arbitrary').val();
			var btn = $(this);
			btn.button('loading');
			$.ajax({
				type: "POST",
				url: "index.php?route=extension/module/smsnot/massend&token=<?php echo $token; ?>",
				cache: false,
				data: data,
				success: function(html){
					try {
						jsonData = $.parseJSON(html);
						if (jsonData['error'] != 100) {
							$('#multi-result').html('<div class="alert alert-danger">'+jsonData['text']+'</div>');
						} else {
							$('#multi-result').html('<div class="alert alert-success">'+jsonData['text']+'</div>');
							$('#balance').html('<?php echo $entry_balance; ?> '+jsonData['balance']);
						}
					} catch (e) {
						$('#multi-result').html('<div class="alert alert-danger">Error: ('+html+')</div>');
					}
					btn.button('reset');
				},
			});
		}
	});
	jQuery.fn.extend({
		insertAtCaret: function(myValue) {
		return this.each(function(i) {
			if (document.selection) {
				this.focus();
				var sel = document.selection.createRange();
				sel.text = myValue;
				this.focus();
			} else if (this.selectionStart || this.selectionStart == '0') {
				var startPos = this.selectionStart;
				var endPos = this.selectionEnd;
				var scrollTop = this.scrollTop;
				this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
				this.focus();
				this.selectionStart = startPos + myValue.length;
				this.selectionEnd = startPos + myValue.length;
				this.scrollTop = scrollTop;
			} else {
				this.value += myValue;
				this.focus();
			}
		});
		}
	});

	$('.btni').click(function() {
		var target = $(this).data('target');
		var text = $(this).data('insert');
		$('#' + target).insertAtCaret(text);
	});

	$('#log').delegate('.sort', 'click', function(e) {
		e.preventDefault();
		$('#log').load(this.href);
	});

	$('#log').delegate('.pagination a', 'click', function(e) {
		e.preventDefault();
		$('#log').load(this.href);
	});

	$('#log').load('index.php?route=extension/module/smsnot/log&token=<?php echo $token; ?>');

	$('.date').datetimepicker({
		pickTime: false
	});

	$('#button-filte').on('click', function(e) {
		url = 'index.php?route=extension/module/smsnot/log&token=<?php echo $token; ?>';

		var filter_phone = $('input[name=\'filter_phone\']').val();

		if (filter_phone) {
			url += '&filter_phone=' + encodeURIComponent(filter_phone);
		}

		var filter_text = $('input[name=\'filter_text\']').val();

		if (filter_text) {
			url += '&filter_text=' + encodeURIComponent(filter_text);
		}

		var filter_status = $('select[name=\'filter_status\']').val();

		if (filter_status != '*') {
			url += '&filter_status=' + encodeURIComponent(filter_status);
		}

		var filter_date_start = $('input[name=\'filter_date_start\']').val();

		if (filter_date_start) {
			url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
		}

		var filter_date_stop = $('input[name=\'filter_date_stop\']').val();

		if (filter_date_stop) {
			url += '&filter_date_stop=' + encodeURIComponent(filter_date_stop);
		}

		$('#log').load(url);
	});
});
</script>
</div>
<?php echo $footer; ?>