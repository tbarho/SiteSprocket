			<% require themedCSS(page-payment) %>
			
			<div id="gallery">
				<div class="holder">
					<div class="frame">
						<h1>Review & Checkout</h1>
					</div>
				</div>
			</div>
			<div id="main">
				<div class="checkout">
					<h2>Payment Details</h2>
					<p>Enter your payment details and click "Place Order" to place your order.</p>
					<form  id="Form_PaymentForm" action="site-sprocket-test/PaymentForm" method="post" enctype="application/x-www-form-urlencoded">
						<p id="Form_PaymentForm_error" class="message " style="display: none"></p>
						<fieldset>
								<div id="CardNumber" class="field creditcard ">
									<label class="left" for="Form_PaymentForm_CardNumber">Card number</label>
									<div class="middleColumn">
										<span id="CardNumber_Holder" class="creditCardField"><input autocomplete="off" name="CardNumber[0]" value="" maxlength="4" tabindex = "0" /> - <input autocomplete="off" name="CardNumber[1]" value="" maxlength="4" tabindex = "1" /> - <input autocomplete="off" name="CardNumber[2]" value="" maxlength="4" tabindex = "2" /> - <input autocomplete="off" name="CardNumber[3]" value="" maxlength="4" tabindex = "3" /></span>
									</div>
								</div>
								<div id="CardType" class="field optionset ">
									<ul>
										<li class="visa">Visa</li>
										<li class="mc">Master Card</li>
										<li class="ae">American Express</li>
										<li class="disc">Discover</li>
									</ul>
								</div>
								
								<div id="ExpMonth" class="field dropdown ">
									<label class="left" for="Form_PaymentForm_ExpMonth">Exp. Date</label>
									<div class="middleColumn">
										<select id="Form_PaymentForm_ExpMonth" name="ExpMonth">
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
											<option value="11">11</option>
											<option value="12">12</option>
										</select>
									</div>
								</div>
								<div id="ExpYear" class="field dropdown ">
									<label class="left" for="Form_PaymentForm_ExpYear"> / </label>
									<div class="middleColumn">
										<select id="Form_PaymentForm_ExpYear" name="ExpYear">
											<option value="2010">2010</option>
											<option value="2011">2011</option>
											<option value="2012">2012</option>
											<option value="2013">2013</option>
											<option value="2014">2014</option>
											<option value="2015">2015</option>
											<option value="2016">2016</option>
											<option value="2017">2017</option>
											<option value="2018">2018</option>
											<option value="2019">2019</option>
										</select>
									</div>
								</div>
								<div id="CCV" class="field numeric ">
									<label class="left" for="Form_PaymentForm_CCV">CCV</label>
									<div class="middleColumn">
										<input type="text" class="text" id="Form_PaymentForm_CCV" name="CCV" value="" />
									</div>
								</div>
								<div id="ccv-dialog">
									This is the ccv dialog
								</div>
								<div class="divider">&nbsp;</div>
								<h2>Additional Info</h2>
								<p>Name your project, and enter any special notes or instructions.</p>
								<div id="Title" class="field text ">
									<label class="left" for="Form_PaymentForm_Title">Project title</label>
									<div class="middleColumn">
										<input type="text" class="text" id="Form_PaymentForm_Title" name="Title" value="" />
									</div>
								</div>
								<div id="Notes" class="field textarea ">
									<label class="left" for="Form_PaymentForm_Notes">Notes</label>
									<div class="middleColumn">
										<textarea id="Form_PaymentForm_Notes" name="Notes" rows="5" cols="20"></textarea>
									</div>
								</div>
							
								<input class="hidden nolabel" type="hidden" id="Form_PaymentForm_SecurityID" name="SecurityID" value="1279578733" />
							
							<div class="clear"><!-- --></div>
						</fieldset>
						<div class="Actions">
							<input class="action " id="Form_PaymentForm_action_doPayment" type="submit" name="action_doPayment" value="Order now!" title="Order now!" />
						</div>
					</form>
				</div>
				<div class="sidebar">
					<div class="cart">
						<h2>Order Summary</h2>
						<div id="price-info">
							<table cellpadding="0" cellspacing="0">
								<thead>
									<tr>
										<th>PRODUCT</th>
										<th>PRICE</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Basic Package</td>
										<td class="price">$999.00</td>
									</tr>
									<tr>
										<td>Custom Website Design</td>
										<td class="price">$499.00</td>
									</tr>
									<tr>
										<td>Module X</td>
										<td class="price">$299</td>
									</tr>
									<tr>
										<td>Module Y</td>
										<td class="price">$99.00</td>
									</tr>
									<tr class="summary">
										<td><strong>TOTAL</strong></td>
										<td><strong>$1,896.00</strong></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
