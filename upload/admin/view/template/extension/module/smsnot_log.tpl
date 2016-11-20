<div class="table-responsive">
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<td class="text-left"><a href="<?php echo $date; ?>" class="sort"><?php echo $column_date; ?></a></td>
				<td class="text-left"><?php echo $column_text; ?></td>
				<td class="text-left"><?php echo $column_sms_id; ?></td>
				<td class="text-left"><?php echo $column_phone; ?></td>
				<td class="text-right"><?php echo $column_status; ?></td>
			</tr>
		</thead>
		<tbody>
			<?php if ($sends) { ?>
			<?php foreach ($sends as $sms) { ?>
			<tr>
				<td class="text-left"><?php echo $sms['date']; ?></td>
				<td class="text-left"><?php echo $sms['text']; ?></td>
				<td class="text-left"><?php echo $sms['sms_id']; ?></td>
				<td class="text-left"><?php echo $sms['phone']; ?></td>
				<td class="text-right"><?php echo $statuses[$sms['status']]; ?></td>
			</tr>
			<?php } ?>
			<?php } else { ?>
			<tr>
				<td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<div class="row">
	<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
	<div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>
