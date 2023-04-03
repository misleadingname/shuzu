<?php
?>

<div class="box">
	<div class="boxbar">
		<h3>Ban Managment</h3>
	</div>
	<div class="boxinner">
		<div class="flex-links">
			<form action="/api/"  method="post" enctype="multipart/form-data">
				<input type="text" name="ip" id="ip" placeholder="IP" required>
				<input type="text" name="reason" id="reason" placeholder="Reason" required>
				<input type="date" name="expires" id="expires" placeholder="Expires" required>
				<input type="text" name="boards" id="boards" placeholder="Boards" required>
				<input type="submit" value="Ban">
			</form>
		</div>
		<p>Put <code>"*"</code> in boards to ban from all boards</p>
		<p>Leave expires empty to ban forever</p>
	</div>
</div>

<div class="box">
	<div class="boxbar">
		<h3>Ban list</h3>
	</div>
	<div class="boxinner">
		<table>
			<tbody>
				<tr>
					<th>IP</th>
					<th>Reason</th>
					<th>Expires</th>
					<th>Boards</th>
				</tr>
				<tr>
					<td>test</td>
					<td>test</td>
					<td>test</td>
					<td>test</td>
				</tr>
				<tr>
					<td>test</td>
					<td>test</td>
					<td>test</td>
					<td>test</td>
				</tr>
				<tr>
					<td>test</td>
					<td>test</td>
					<td>test</td>
					<td>test</td>
				</tr>
				<tr class="expired">
					<td>test</td>
					<td>test</td>
					<td>test</td>
					<td>test</td>
				</tr>
				<tr class="expired">
					<td>test</td>
					<td>test</td>
					<td>test</td>
					<td>test</td>
				</tr>
				<tr>
					<td>test</td>
					<td>test</td>
					<td>test</td>
					<td>test</td>
				</tr>
				<tr>
					<td>test</td>
					<td>test</td>
					<td>test</td>
					<td>test</td>
				</tr>
				<tr>
					<td>test</td>
					<td>test</td>
					<td>test</td>
					<td>test</td>
				</tr>
			</tbody>
		</table>
		<p>Light red = expired</p>
	</div>
</div>