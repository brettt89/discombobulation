<% if ExceptionMessage %>
  <p>
    <strong>Error:</strong> $ExceptionMessage <br />
    <% if ValidationMessage %>
      <strong>Validation Error:</strong> $ValidationMessage
    <% end_if %>
  </p>
  $OrderForm
<% end_if %>

<% control Payment %>
<% if $Status = Success %>
	<div data-alert class="alert-box success round">
		  Payment #{$ID}: Successfully Completed
		  <a href="#" class="close">&times;</a>
		</div>
<% else %>
	<% loop Errors %>
		<div data-alert class="alert-box <% if $Up.Status == Failure %>alert <% else %>warning <% end_if %>round">
		  Payment #{$Up.ID}: $ErrorCode - $ErrorMessage
		  <a href="#" class="close">&times;</a>
		</div>
	<% end_loop %>
<% end_if %>
<% end_control %>