<% loop $Products %>
	<div class="large-4 columns">
		<h3>$Title</h3>
		<h5>$SubTitle</h5>
		<% if $ProductImage %>
			<a href="$URL">$ProductImage.SetSize(200,200)</a>
		<% end_if %>
		<h5><strong>Price:</strong> $$Price NZD</h5>
		$Up.OrderForm($ID)
	</div>
<% end_loop %>