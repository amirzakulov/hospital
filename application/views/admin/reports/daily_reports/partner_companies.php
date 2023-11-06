<div class="row mt-5">
	<div class="col-md-12">
		<h3 class="text-center">Хамкор лаборатория кунлик хисобот</h3>



		<table class="table table-borderless custom-table mb-0 compact1 font-13">
			<thead>
			<tr class="bg-primary text-white">
				<th class="align-text-top" width="100">Номи<br></th>
				<th class="align-text-top text-right" width="200">Жами<br></th>
				<th class="align-text-top text-right" width="200">Улуш сўм</th>
				<th class="align-text-top text-right" width="200">Клиника улуш сўм</th>
				<th width="200"></th>
				<th width="200"></th>
			</tr>
			</thead>
			<tbody>

			<tr class="text-right">
				<td class="text-left">VitaMed</td>
				<td><?= $vitamed["total"]; ?></td>
				<td><?= $vitamed["partner_total"]; ?></td>
				<td ><?= $vitamed["total"] - $vitamed["partner_total"]; ?></td>
				<td></td>
				<td></td>
			</tr>
			</tbody>
		</table>
	</div>
</div>
