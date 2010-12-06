	<h1>Viewing Subscription [ID]</h1>
	<h2>for [Jimmy Kung]</h2>
	<div class="payment-details">
		<ul>
			<li>
				<label for="txtStart">Start Date:</label><input id="txtStart" type="text" value="12/2/2010" disabled="disabled" />
			</li>
			<li>
				<label for="txtEnd">End Date:</label><input id="txtEnd" type="text" value="12/2/2010" disabled="disabled" />
			</li>		
			<li>
				<label for="txtAmt">Bill client $</label><input id="txtAmt" type="text" value="15.00" disabled="disabled" /> every <input id="txtLength" type="text" value="1" disabled="disabled" />&nbsp;
				<select name="ddlUnit" id="ddlUnit" disabled="disabled">
					<option value="day">day</option>
					<option value="month" selected="selected">month</option>
					<option value="year">year</option>
				</select>(s).
			</li>
		</ul>
		<div class="status">
			<h3>Inactive</h3>
			<p class="message">Payment was declined.</p>
		</div>
	</div>
	<div class="actions">
		<ul>
			<li>
				<a href="update-subscription.html"><h3>Update Subscription</h3></a>
			</li>
			<li>
				<a href="cancel-confirm.html"><h3>Cancel Subscription</h3></a>
			</li>
		</ul>
	</div>
